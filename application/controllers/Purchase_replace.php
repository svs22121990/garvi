<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_replace extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('Purchase_replace_model'));
    }

    public function index()
    {    
      $add = '';
        if(!empty($_SESSION[SESSION_NAME]['getMenus']))
        {  

            foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
            { 
              foreach($row as $menu)
              { 
                  if($menu->value=='Purchase_replace')
                  { 
                    if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                  }
              }
            }

            $breadcrumbs='<ul class="breadcrumb">
                <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                </li>
                <li class="active">Manage Purchase Replace</li>
                </ul>';
            $data = array('heading'=>'Purchase Replace','createAction'=>site_url('Purchase_replace/create'),'breadcrumbs'=>$breadcrumbs,'addPermission'=>$add);
            $this->load->view('purchase_orders/replace_list',$data); 
        }
        else
        {
             redirect('Dashboard');
        }
    }
   
    public function ajax_manage_page()
    {
        $con = "pr.id<>0";
        $AllData = $this->Purchase_replace_model->get_datatables($con);
        $edit = '';
        $view = '';
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Purchase_replace')
              { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_view)){ $view='1'; }else{ $view='0'; }
              }
          }
        }
        $no = 0;        
        $data = array();        
        foreach ($AllData as $row) 
        { 
            

            $asset = $this->Crud_model->GetData('assets','',"id='".$row->asset_id."'",'','','','single');
        /* if($product->product_type == 'Open')
            {
                if($product->product_unit == 'kg' || $product->product_unit == 'ltr')
                {
                    $quantity = $row->quantity / 1000;
                    $remainQty = $row->remaining_quantity / 1000;
                } else {
                    $quantity = $row->quantity;
                    $remainQty = $row->remaining_quantity;
                }    
            } else {*/
                $quantity = $row->quantity;
                $remainQty = $row->remaining_quantity;
           /* }*/
           $btn='';
            if(!empty($view)){
            $btn = anchor(site_url('Purchase_replace/view/'.$row->id),'<button title="View" class="btn btn-primary btn-circle btn-sm"><i class="fa fa-eye"></i></button> | ');
            }
             if(!empty($edit)){
            if($remainQty!=0)
                $btn .= anchor(site_url('Purchase_replace/update/'.$row->id),'<button title="Edit" class="btn btn-info btn-circle btn-sm"><i class="fa fa-pencil"></i></button>');
              }

            $no++;
            $nestedData = array();
            $nestedData[] = $no; 
            $nestedData[] = "<a href='".site_url('Purchase_orders/read/'.$row->pod)."'>".$row->order_number."</a>"; 
            $nestedData[] = $row->asset_name; 
            $nestedData[] = $quantity; 
            $nestedData[] = $remainQty; 
            $nestedData[] = $row->unit; 
            $nestedData[] = $row->remark; 
            $nestedData[] = date('d-m-Y',strtotime($row->replace_date)); 
            $nestedData[] = $btn; 
            $data[] = $nestedData;
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Purchase_replace_model->count_all($con),
                    "recordsFiltered" => $this->Purchase_replace_model->count_filtered($con),
                    "data" => $data,
                );
        //output to json format
        echo json_encode($output);       
    }

    public function create()
    {    
      //  $warehouse = $this->Crud_model->GetData('warehouses','name,id','','','name');
        $vendors = $this->Crud_model->GetData('vendors','name,shop_name,id','status="Active"','','shop_name');
        $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class="active">Manage Purchase Replace</li>
            </ul>';
        $data = array('heading'=>'Create Purchase Replace','action'=>site_url('Purchase_replace/create_action'),'vendors'=>$vendors,'breadcrumbs'=>$breadcrumbs);
        $this->load->view('purchase_orders/replace_form',$data); 
    }

    public function GetPurchaseOrders()
    {
        $wid = $this->input->post('wid'); $vid = $this->input->post('vid'); $did = $this->input->post('did');
        //$con = "status='Received' and vendor_id='".$vid."' AND warehouse_id='".$wid."' AND distributor_id='".$did."' AND id not in (select purchase_order_id from purchase_replace)";
        $con = "status='Received' and vendor_id='".$vid."' AND id not in (select purchase_order_id from purchase_replace)";
        $result = $this->Crud_model->GetData('purchase_orders','id,order_number',$con);

        $response = '<option value="">-- Select Purchase Order --</option>';
        foreach($result as $row){
            $response .= '<option value="'.$row->id.'">'.$row->order_number.'</option>';
        }
        echo $response; exit;
    }

    public function ProductDetails()
    { 
        $sr=0;  
        $poid = $this->input->post('id');
        if($poid == ''){ exit; }
        $is_barcode='Yes';
        $purchase_order_details = $this->Purchase_replace_model->GetProductData("po.id='".$poid."'",$is_barcode);
        $pods = $this->Crud_model->GetData('purchase_returns','sum(quantity) as quantity',"purchase_order_id='".$poid."'",'purchase_order_detail_id');
       
         if(!empty($pods))
        {
            $returnQty= $pods[0]->quantity;
        }else{
            $returnQty=0;
        }
        $overallQty=0;
        foreach ($purchase_order_details as $key ) {
             $overallQty=$overallQty+$key->quantity;
        }

        $response = '<table class="table table-striped table-bordered"><thead><tr>
                        <th>Sr.</th>
                        <th>Asset</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th width="30%">Replace Asset (Product SKU)</th>
                        <th>Remark</th></tr></thead><tbody>';
        if(!empty($purchase_order_details)){
        if($returnQty == $overallQty)
        {
            $response .='<tr class="trRow"><td colspan="7" align="center">All Item are already Returned.<input type="hidden" value="no" id="noItems"></td></tr>';
          
        } else {
            foreach($purchase_order_details as $row) {


                 $recievedQty=$this->Crud_model->GetData('purchase_received_detail_barcode','sum(quantity) as qty',"asset_id='".$row->asset_id."' and purchase_order_id='".$row->purchase_order_id."' and is_returned='No'",'','','','');

                $purchaseReplace = $this->Crud_model->GetData('purchase_replace','sum(quantity) as quantity',"purchase_order_detail_id='".$row->podid."'",'','','','single');

                $quantity = $purchaseReplace->quantity;
                if(!empty($recievedQty)){
                    $qty=$recievedQty[0]->qty;
                }else{
                    $qty=$row->quantity;
                }

                 $remainQty = $qty - $quantity;
                   if($remainQty == 0)
                    {   
                        $response .='<tr class="trRow"><td colspan="6" align="center">No assets are availale for replace.<input type="hidden" value="no" id="noItems"></td></tr>';
                    }else{ 

                        $response .='<tr class="trRow"><td><span>'.++$sr.'</span></td>';
                        $response .='<td><span>'.$row->asset_name.'</span></td>';
                        $response .='<td><span>'.$qty.'</span><br><small>Remaining Qty : '.$remainQty.'</small></td>';
                        $response .='<td><span>'.$row->unit.'</span>
                                        <input type="hidden" name="podid[]" value="'.$row->podid.'" >
                                        <input type="hidden" id="oldqty'.$sr.'" name="'.$row->asset_id.'oldQty" value="'.$remainQty.'" >
                                        <input type="hidden" name="asset_id[]" value="'.$row->asset_id.'" >
                                        <input type="hidden" name="asset_type_id[]" value="'.$row->asset_type_id.'" >
                                        <input type="hidden" name="order_number" value="'.$purchase_order_details[0]->order_number.'" >
                               
                                </td>';

                    $recieveQty=$this->Crud_model->GetData('purchase_received_detail_barcode','id,barcode_number',"asset_id='".$row->asset_id."' and purchase_order_id='".$row->purchase_order_id."' and is_returned='No'",'','','','');
                
                    $response .='<td> <span id="errRec'.$sr.'" style="color:red"></span><input type="hidden" name="quantity[]" id="quantity'.$sr.'">';
                        foreach ($recieveQty as $key) {
                           $response .='<div class="col-md-6"><input type="checkbox" onclick="return collectQty('.$sr.')" value="'.$key->id.'" name="barcode_number[]" class="quantitychk'.$sr.'">&nbsp;'.$key->barcode_number.'</div>';
                        }
                                
                        $response .'</td>';
                        $response .='<td><input type="text" name="remark[]" class="form-control"></td></tr>';

                }
             }
          } 
        }else{
             $response .='<tr class="trRow"><td colspan="6" align="center">No assets are availale for replace.<input type="hidden" value="no" id="noItems"></td></tr>';
         }
        $response .= '</table>';                
        echo $response; exit;
    }

    public function create_action()
    {
        print_r($_POST); exit;
        $asset_type_id = $this->input->post('asset_type_id'); 
        $vendor_id = $this->input->post('vendor_id');
        $purchase_order_id = $this->input->post('purchase_order_id');
        $podid = $this->input->post('podid');
        $quantity = $this->input->post('quantity');
        $remark = $this->input->post('remark');
        $asset_id = $this->input->post('asset_id');
        $branch_id = $this->input->post('branch_id');
        $count = count($podid);
        $is_barcode='Yes';
        $financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
        for ($i=0; $i < $count; $i++) 
        { 
            
               for($j=0;$j < count($_POST['barcode_number']);$j++){
                      $data=array(
                            'is_replaced'=>'Yes',
                        );  
                        $this->Crud_model->SaveData('purchase_received_detail_barcode',$data,"asset_id = '".$asset_id[$i]."' and purchase_order_id='".$purchase_order_id."' and id='".$_POST['barcode_number'][$j]."'"); 

                        $betBarCode = $this->Crud_model->GetData('purchase_received_detail_barcode','',"id='".$_POST['barcode_number'][$j]."'",'','id desc','','single');  
                        $GetAsset=$this->Crud_model->GetData('asset_details','id,barcode_number',"barcode_number='".$betBarCode->barcode_number."'",'','id desc','','single');
                        if(!empty($GetAsset)){
                            $is_receive=1;
                            $data=array(
                            'status'=>'Replaced',
                        );  
                        $this->Crud_model->SaveData('asset_details',$data,"asset_id = '".$asset_id[$i]."' and id='".$GetAsset->id."'"); 
                        

                        
                        }


                    }


        }
        $this->session->set_flashdata('message', '<div class="alert alert-block alert-success text-center" style="margin-bottom:0px;"><p>Purchase replace created successfully</p></div>');
        //$this->session->set_flashdata('message',"Purchase replace created successfully");
        redirect('Purchase_replace');
    }
    
    public function view($replaceId)
    {
        if(empty($replaceId))
            redirect('Purchase_replace');

        $getReplace = $this->Purchase_replace_model->getPurchaseOrderData($replaceId);
        //print_r($getReplace);exit;
        if(empty($getReplace))
            redirect('Purchase_replace');
        if($getReplace->product_type == 'Open')
        {
            if($getReplace->product_unit == 'kg' || $getReplace->product_unit == 'ltr')
            {
                $getReplace->quantity = $getReplace->quantity / 1000;
                $getReplace->remaining_quantity = $getReplace->remaining_quantity / 1000;
            } else {
                $getReplace->quantity = $getReplace->quantity;
                $getReplace->remaining_quantity = $getReplace->remaining_quantity;
            }    
        }
        
        $getReplaceData = $this->Crud_model->GetData('purchase_replace_details','',"purchase_replace_id='".$replaceId."'");
        $data = array('listData'=>$getReplace,'replaceData'=>$getReplaceData);
        //print_r($data);exit;
        $this->layouts->view('purchase_orders/replace_view',$data);
    }

    public function update($replaceId)
    {
        if(empty($replaceId))
            redirect('Purchase_replace');

        $getReplace = $this->Purchase_replace_model->getPurchaseOrderData($replaceId);
        
        if(empty($getReplace))
            redirect('Purchase_replace');

        if($getReplace->product_type == 'Open')
        {
            if($getReplace->product_unit == 'kg' || $getReplace->product_unit == 'ltr')
            {
                $getReplace->quantity = $getReplace->quantity / 1000;
                $getReplace->remaining_quantity = $getReplace->remaining_quantity / 1000;
            } else {
                $getReplace->quantity = $getReplace->quantity;
                $getReplace->remaining_quantity = $getReplace->remaining_quantity;
            }    
        }

        $data = array('listData'=>$getReplace);

        //print_r($data);exit;
        $this->layouts->view('purchase_orders/replace_edit',$data);
    }

    public function update_action()
    {
        //print_r($_SESSION);exit();
        if(empty($_POST['replaced_quantity']) || empty($_POST['id']))
            redirect('Purchase_replace');

         //Stock Maintainance
            $getReplaceData = $this->Crud_model->GetData('purchase_replace','',"id='".$_POST['id']."'",'','','','single');
            $financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
            $getOrderDetail = $this->Crud_model->GetData('purchase_order_details','',"id='".$getReplaceData->purchase_order_detail_id."'",'','','','single');
            $Qty = $_POST['replaced_quantity'];;
            $replacedQty = $_POST['replaced_quantity'];
            $warehouse_id = $getOrderDetail->warehouse_id;
            $distributor_id = $getOrderDetail->distributor_id;

            $checkProduct = $this->Crud_model->GetData('products','',"prd_id='".$getOrderDetail->product_id."'",'','','','single');
            if($checkProduct->product_type=='Branded')
            {
                $conStock = "warehouse_id='".$warehouse_id."' and distributor_id='".$distributor_id."' and product_id='".$getOrderDetail->product_id."' and weight_price_id='".$getOrderDetail->weight_price_id."'";
                $checkStock = $this->Crud_model->GetData('warehouse_product_stocks','',$conStock,'','','','single');

                $addQuantity = $checkStock->product_quantity + $Qty;
            }else{
                $conStock = "warehouse_id='".$warehouse_id."' and distributor_id='".$distributor_id."' and product_id='".$getOrderDetail->product_id."' ";
                $checkStock = $this->Crud_model->GetData('warehouse_product_stocks','',$conStock,'','','','single');

                if(($checkProduct->product_unit=='kg') || ($checkProduct->product_unit=='ltr'))
                {   
                    $addQuantity = $checkStock->product_quantity + ($Qty *1000);
                    $replacedQty = $replacedQty*1000;
                    $Qty = $Qty *1000;
                }else{
                    $addQuantity = $checkStock->product_quantity + $Qty;
                }
            }   
            //print_r($addQuantity);exit();
            $updateWarehouseStock = $this->Crud_model->SaveData('warehouse_product_stocks',array('product_quantity'=>$addQuantity),"id='".$checkStock->id."'");
            
            //Insert in log
            $logData = array('financial_year_id'=>$financialYear->id,
                        'warehouse_product_stock_id'=>$checkStock->id,
                        'type'=>'Replace_return',
                        'product_id'=>$getOrderDetail->product_id,
                        'weight_price_id'=>$getOrderDetail->weight_price_id,
                        'product_quantity'=>$Qty,
                        'available_quantity'=>$addQuantity,
                        'product_unit'=>$checkProduct->product_unit,
                        'date'=>date('Y-m-d'),
                        'created_by'=>$_SESSION['Comp']['id'],
                    );
            $saveLogData  = $this->Crud_model->SaveData('stock_logs',$logData);
            //print_r($this->db->last_query());exit();
        //Upadate replace data
       
        $NewQuantity = $getReplaceData->remaining_quantity - $replacedQty;

        $updateData = array('remaining_quantity'=>$NewQuantity);
        $udpateReplace = $this->Crud_model->SaveData('purchase_replace',$updateData,"id='".$_POST['id']."'");

        //Insert detail
            $insertData = array('purchase_replace_id'=>$_POST['id'],
                        'quantity'=>$replacedQty,
                        'date'=>date('Y-m-d')
                    );
            $udpateReplace = $this->Crud_model->SaveData('purchase_replace_details',$insertData);


        $this->session->set_flashdata('message', '<div class="alert alert-block alert-success text-center" style="margin-bottom:0px;"><p>Purchase products replaced successfully</p></div>');
        //$this->session->set_flashdata('message',"Purchase products replaced successfully");
        redirect('Purchase_replace');
    }
}
?>