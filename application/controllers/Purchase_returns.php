<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_returns extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $this->load->database();
        $this->load->library(array('session','form_validation','image_lib'));
        $this->load->model(array('Purchase_returns_model','Party_invoice_model','Purchase_approve_return_model'));
        $this->load->helper(array('form', 'url', 'html'));
    }

    ///FOR BARCODE
    private function set_barcode($code)
    {
      //load library
      $this->load->library('Zend');
      //load in folder Zend
      $this->zend->load('Zend/Barcode');
      $newCode = $code;
      //print_r($newCode);exit;
      //generate barcode
      //Zend_Barcode::render('code128', 'image', array('text'=>$code), array());//Initialize to draw text 

      $file = Zend_Barcode::draw('code128', 'image', array('text' => $newCode,'drawText' =>true), array());
      $code = time().$code;
      $store_image = imagepng($file,"../admin/assets/purchaseOrder_barcode/{$code}.png");
      return $code.'.png';
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
                      if($menu->value=='Purchase_returns')
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
                    <li class="active">Manage Purchase Return/Replace</li>
                    </ul>';

                //$warehouse = $this->Crud_model->GetData('warehouses','name,id','','','name');
                $vendors = $this->Crud_model->GetData('vendors','shop_name,id','','','shop_name');
                $data = array('heading'=>'Purchase Return/Replace','createAction'=>site_url('Purchase_returns/create'),'vendors'=>$vendors,'breadcrumbs'=>$breadcrumbs,'addPermission'=>$add);
                //print_r($data);exit;
                $this->load->view('purchase_orders/return_list',$data); 
        }
        else
        {
            redirect('Dashboard');
        }
    }
   
    public function ajax_manage_page()
    {
        $con = "pr.id!='0'";
        $AllData = $this->Purchase_returns_model->get_datatables($con);
        //print_r($this->db->last_query());exit;
        $no = 0;        
        $data = array();        
        foreach ($AllData as $row) 
        { 
            if(!empty($row)){
            $btn="<a href='".site_url('Purchase_returns/read/'.$row->id)."' title='View Details' class='btn btn-sm btn-primary'><i class='fa fa-eye'></i></a>";

            if($row->status=='Pending'){
                $btn.=" | <a href='".site_url('Purchase_returns/approveReturnReplace/'.$row->id)."' title='Approve Request' class='btn btn-sm btn-info'><i class='fa fa-thumbs-up'></i></a>";
            }
            if($row->status=='Pending'){
                $status="<span class='label label-warning'>Pending</span>";
            }else if($row->status=='Approved'){
                $status="<span class='label label-success'>Approved</span>";

            }else{
                $status="<span class='label label-danger'>Rejected</span>";
            }

            $no++;
            $nestedData = array();
            $nestedData[] = $no; 
            $nestedData[] = "<a href='".site_url('Purchase_orders/read/'.$row->purchase_order_id)."' target='_blank'>".$row->order_number."</a>"; 
            $nestedData[] = $row->asset_name; 
            $nestedData[] = $row->quantity; 
            $nestedData[] = $status; 
            $nestedData[] = $btn; 
            $data[] = $nestedData;
            }
        }
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Purchase_returns_model->count_all($con),
                    "recordsFiltered" => $this->Purchase_returns_model->count_filtered($con),
                    "data" => $data,
                );
        //output to json format
        echo json_encode($output);       
    }

    public function create()
    {    
        //$warehouse = $this->Crud_model->GetData('warehouses','name,id','','','name');
        $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class="active">Manage Purchase Return/ Replace</li>
            </ul>';
        $vendors = $this->Crud_model->GetData('vendors','name,shop_name,id','','','shop_name');
        $data = array('heading'=>'Create Purchase Return/Replace','action'=>site_url('Purchase_returns/create_action'),'vendors'=>$vendors,'breadcrumbs'=>$breadcrumbs);
        $this->load->view('purchase_orders/return_form',$data); 
    }

    public function GetPurchaseOrders()
    {
        $atid = $this->input->post('atid'); $vid = $this->input->post('vid'); 
        //$did = $this->input->post('did');
        //$con = "status='Received' and vendor_id='".$vid."' AND warehouse_id='".$wid."' AND distributor_id='".$did."' AND id not in (select purchase_order_id from purchase_returns)";
        $con = "status='Received' and vendor_id='".$vid."'";
        $result = $this->Purchase_returns_model->GetInvoicenumber($con);

        //print_r($this->db->last_query());exit;        
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


        $purchase_order_details = $this->Purchase_returns_model->GetProductData("po.id='".$poid."'",$is_barcode);
        $pods = $this->Crud_model->GetData('purchase_return_replace_details','',"purchase_order_id='".$poid."' and status!='Rejected'",'');
        $count=count($pods);
        //print_r($count);
        $overallQty=0;
        foreach ($purchase_order_details as $key ) {
             $overallQty=$overallQty+$key->quantity;
        }
       //print_r($overallQty);exit;
        $response = '<table class="table table-striped table-bordered"><thead><tr>
        				<th>Sr.</th>
        				<th>Asset</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                       <th width="60%">Assets (Product SKU)</th>';
        			

        if(!empty($purchase_order_details)){
        if($count == $overallQty)
        {
            $response .='<tr class="trRow"><td colspan="5" align="center">No Items available for replace Or Return.<input type="hidden" value="no" id="noItems"></td></tr>';
        } else {

         foreach($purchase_order_details as $row) 
            {
           
                $recievedQty=$this->Crud_model->GetData('purchase_received_detail_barcode','sum(quantity) as qty',"asset_id='".$row->asset_id."' and purchase_order_id='".$row->purchase_order_id."' and barcode_number not in (select barcode_number from purchase_return_replace_details where status!='Rejected' )",'','','','');
                //print_r($recievedQty);
                if(!empty($recievedQty)){
                    $qty=$recievedQty[0]->qty;
                }else{
                    $qty=$row->quantity;
                }
             


                $purchaseReturnReplace = $this->Crud_model->GetData('purchase_return_replace_details','',"purchase_order_detail_id='".$row->podid."' and purchase_order_id='".$poid."' and status!='Rejected'",'','','','');
                //print_r($purchaseReturnReplace);
                $ttlQty=count($purchaseReturnReplace);
                $prd = $this->Crud_model->GetData('assets','',"id='".$row->asset_id."'",'','','','single');
                
                //$quantity = $purchaseReturn->quantity;
            
                $remainQty = $qty;
                            
                    if($remainQty == 0)
                    {  
                        //<tr class="trRow"><td colspan="5" align="center">No assets are availale for  Return/Replace.<input type="hidden" value="no" id="noItems"></td></tr>
                     $response .='';
                    } 
                    else {
                        $response .='<tr class="trRow"><td><span>'.++$sr.'</span></td>';
                        $response .='<td><span>'.$row->asset_name.'</span></td>';
                        $response .='<td><span>'.$qty.'</span><br><small>Remaining Qty : '.$remainQty.'</small></td>';
                        $response .='<td><span>'.$row->unit.'</span>
                                        <input type="hidden" name="podid[]" value="'.$row->podid.'" >
                                        <input type="hidden" id="oldqty'.$sr.'" name="'.$row->asset_id.'oldQty" value="'.$remainQty.'" >
                                        <input type="hidden" name="asset_id[]" value="'.$row->asset_id.'" >
                                        <input type="hidden" name="asset_type_id[]" value="'.$row->asset_type_id.'" >
                                        <input type="hidden" name="branch_id" value="'.$purchase_order_details[0]->branch_id.'" >
                                        <input type="hidden" name="order_number" value="'.$purchase_order_details[0]->order_number.'" >
                               
                                </td>';
                             
                        $recieveQty=$this->Crud_model->GetData('purchase_received_detail_barcode','id,barcode_number,asset_id',"asset_id='".$row->asset_id."' and purchase_order_id='".$row->purchase_order_id."' and is_returned='No' and is_replaced='No' and barcode_number not in (select barcode_number from purchase_return_replace_details where status!='Rejected')",'','','','');
                
                    $response .='<td> <span id="errRec'.$sr.'" style="color:red"></span><input type="hidden" name="quantity[]" id="quantity'.$sr.'">
                    <div class="col-md-12"  style="margin-bottom:2px;font-weight:bold"><div class="col-md-3">Product SKU</div><div class="col-md-3">Type</div><div class="col-md-3">Remaks</div></div>';
                        foreach ($recieveQty as $key) {
                           $response .='<div class="col-md-12" style="margin-bottom:2px"><div class="col-md-3"><input type="checkbox" onclick="return collectQty('.$sr.')" value="'.$key->id.'" name="'.$key->asset_id.'_barcode_number[]" class="quantitychk'.$sr.'">&nbsp;'.$key->barcode_number.'</div><div     class="col-md-3">   <select name="'.$key->id.'_type" id="'.$key->id.'_type">
                           <option value="">Select</option>
                           <option value="Return">Return</option>
                           <option value="Replace">Replace</option>
                           </select> </div><div class="col-md-6">
                           <input type="text" name="'.$key->id.'_remark" placeholder="Remark" class="form-control">
                           </div></div>';
                        }
                                
                        $response .'</td>';
                        $response .='</tr>';
                    }   
               
                                   
             } 
             
        
        }
    }else{
         $response .='<tr class="trRow"><td colspan="5" align="center">No assets are availale for Return/Replace.<input type="hidden" value="no" id="noItems"></td></tr>';
    }
        $response .= '</table>';                

    
    echo $response; exit;
}
    
    public function read($prrid){

         $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class="active">Manage Purchase Return/ Replace</li>
            </ul>';
        $con1="pr.id='".$prrid."'";
        $prr = $this->Purchase_returns_model->GetPrr($con1);
        //print_r($this->db->last_query());exit;
        $prrd = $this->Purchase_returns_model->GetPrrd($con1);
        $data = array('heading'=>'View Purchase Return/Replace','prr'=>$prr,'prrd'=>$prrd,'breadcrumbs'=>$breadcrumbs);
        $this->load->view('purchase_orders/return_replace_view',$data);         

    }
    public function approveReturnReplace($prrid){

         $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class="active">Approve Purchase Return/ Replace</li>
            </ul>';
        $con1="pr.id='".$prrid."' and pr.status='Pending'";
        $prr = $this->Purchase_returns_model->GetPrr($con1);
        //print_r($this->db->last_query());exit;
        $con2="pr.id='".$prrid."' and prd.status='Pending' and type='Return'";
        $Rprrd = count( $this->Purchase_returns_model->GetPrrd($con2));
        $con3="pr.id='".$prrid."' and prd.status='Pending' and type='Replace'";
        $Replaceprrd = count( $this->Purchase_returns_model->GetPrrd($con3));
        $data = array('heading'=>'Approve Purchase Return/Replace','prr'=>$prr,'returnCount'=>$Rprrd,'replaceCount'=>$Replaceprrd,'breadcrumbs'=>$breadcrumbs,'id'=>$prrid);
        $this->load->view('purchase_orders/return_replace_approve',$data);         

    }

    public function AjaxReturn($id){
     

     $con="pr.id='".$id."' and type='Return' and prd.status='Pending'";
     $Data = $this->Purchase_approve_return_model->get_datatables($con); 
      if(empty($_POST['SearchData1']))
        {
            $client_ids = array();
        }else{
            $client_ids = explode(',', $_POST['SearchData1']);     
        }
       $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  

           if($_POST['select_all1']=="true")
            {
                $chked = "checked";
            }else if(in_array($row->id, $client_ids)){
                $chked = "checked";
            }else{

                $chked = "";
            }

             if($row->status == 'Pending'){ 
                 $status='<label class="label label-warning " style="cursor:default">Pending</label>';
                   } elseif($row->status == 'Approved') { 
                 $status='<label class="label label-success " style="cursor:default">Approved</label>';
                   }elseif($row->status == 'Rejected') { 
                 $status='<label class="label label-danger " style="cursor:default">Rejected</label>';
                }

            $chk = '<input type="checkbox" name="clients[]" id="client_id1_'.$row->id.'" '.$chked.' onchange="checkbox_all1('.$row->id.');" class="client_id1 client_id1_'.$row->id.'" value="'.$row->id.'">';
            

            $no++;
            $nestedData = array();
            $nestedData[] = $chk ;
            $nestedData[] = $row->asset_name.'<input type="hidden" name="asset_id[]" value="'.$row->asset_id.'">' ;
            $nestedData[] = $row->barcode_number; 
            $nestedData[] = $row->remark; 
            $nestedData[] = $status; 
            $nestedData[] = $row->type; 
            $data[] = $nestedData;
            $selected = '';
        }
        $filter = $this->Purchase_approve_return_model->count_r_filtered($con);
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Purchase_approve_return_model->count_all($con),
                    "recordsFiltered" => $this->Purchase_approve_return_model->count_filtered($con),
                    "data" => $data,
                    "ids1" => $filter->ids,
                );
        //print_r($output);
       
        echo json_encode($output);

    }
    public function AjaxReplace($id){
     
     $con="pr.id='".$id."' and type='Replace' and prd.status='Pending'";
     $Data = $this->Purchase_approve_return_model->get_datatables($con); 
      if(empty($_POST['SearchData1']))
        {
            $client_ids = array();
        }else{
            $client_ids = explode(',', $_POST['SearchData1']);     
        }
       $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  

           if($_POST['select_all']=="true")
            {
                $chked = "checked";
            }else if(in_array($row->id, $client_ids)){
                $chked = "checked";
            }else{

                $chked = "";
            }

             if($row->status == 'Pending'){ 
                 $status='<label class="label label-warning " style="cursor:default">Pending</label>';
                   } elseif($row->status == 'Approved') { 
                 $status='<label class="label label-success " style="cursor:default">Approved</label>';
                   }elseif($row->status == 'Rejected') { 
                 $status='<label class="label label-danger " style="cursor:default">Rejected</label>';
                }

            $chk = '<input type="checkbox" name="clients[]" id="client_id2_'.$row->id.'" '.$chked.' onchange="checkbox_all('.$row->id.');" class="client_id2 client_id2_'.$row->id.'" value="'.$row->id.'">';
            

            $no++;
            $nestedData = array();
            $nestedData[] = $chk ;
            $nestedData[] = $row->asset_name ;
            $nestedData[] = $row->barcode_number; 
            $nestedData[] = $row->remark; 
            $nestedData[] = $status; 
            $nestedData[] = $row->type; 
            $data[] = $nestedData;
            $selected = '';
        }
        $filter = $this->Purchase_approve_return_model->count_r_filtered($con);
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Purchase_approve_return_model->count_all($con),
                    "recordsFiltered" => $this->Purchase_approve_return_model->count_filtered($con),
                    "data" => $data,
                    "ids" => $filter->ids,
                );
       
        echo json_encode($output);

    }

    public function approveReplace($id){
        //print_r($_POST);echo "<br/>";
        $getRequestDetails=$this->Crud_model->GetData('purchase_return_replace','',"id='".$id."' and status='Pending'",'','','','row');
        //print_r($getRequestDetails);echo "<br/>";
        $financial_year_id=$getRequestDetails->financial_year_id;
        $vendor_id=$getRequestDetails->vendor_id;
        $purchase_order_id=$getRequestDetails->purchase_order_id;
        $order_number=$_POST['order_number'];
        $qty=count($_POST['clients']);
        $amt=0;
        $assets=array();
        $asset_types=array();
        $quantity=array();
        for($i=0;$i<$qty;$i++)
        {
            $getRequestDetailsdata=$this->Crud_model->GetData('purchase_return_replace_details','',"purchase_return_replace_id='".$id."' and status='Pending' and id='".$_POST['clients'][$i]."'",'','','','row');
            $barcode_no=$getRequestDetailsdata->barcode_number;
            //print_r($barcode_no);exit;
            $asset_id=$getRequestDetailsdata->asset_id;
            $assets[]=$asset_id;
            $asset_type_id=$getRequestDetailsdata->asset_type_id;
            $asset_types[]=$asset_type_id;
            $quantity[]=1;
            $Getasset = $this->Crud_model->GetData('assets','',"id = '".$asset_id."'",'','','','single');
            $stockdata=$this->Crud_model->GetData('asset_details','',"asset_id='".$asset_id."' and barcode_number='".$barcode_no."'",'','','','row');
            $purchase_received_detail_barcode=$this->Crud_model->GetData('purchase_received_detail_barcode','',"asset_id='".$asset_id."' and barcode_number='".$barcode_no."'",'','','','row');
            if($_POST['status']=='Approve'){

             $barcodeData = array(
                        'purchase_order_id'=>$purchase_order_id,
                        'asset_type_id' => $asset_type_id,
                        'purchase_received_id'=>$purchase_received_detail_barcode->purchase_received_id,
                        'purchase_received_detail_id'=>$purchase_received_detail_barcode->purchase_received_detail_id,
                        'is_received'=>$purchase_received_detail_barcode->is_received,
                        'asset_id'=>$asset_id,
                        'quantity'=>1,
                        'parent_id'=> $purchase_received_detail_barcode->id,
                    );
                $this->Crud_model->SaveData('purchase_received_detail_barcode',$barcodeData);
                $barcodeId = $this->db->insert_id(); 
                $barcode_number_new = $barcodeId.'897654';
                $barcodeImg = $this->set_barcode($barcode_number_new);
                //print_r($barcodeImg);echo '<br/>';
                $barcodeData = array(
                                      'barcode_image'=>$barcodeImg,
                                      'barcode_number'=>$barcode_number_new,
                                    );

                $this->Crud_model->SaveData('purchase_received_detail_barcode',$barcodeData,"id='".$barcodeId."'");


                $data1=array(
                'status'=>'Approved',
                'replace_barcode_no'=>$barcode_number_new,
                );  
                $this->Crud_model->SaveData('purchase_return_replace_details',$data1,"id='".$_POST['clients'][$i]."'"); 

                $data2=array(
                'is_replaced'=>'Yes',
                );  
                $this->Crud_model->SaveData('purchase_received_detail_barcode',$data2,"barcode_number='".$barcode_no."'"); 

                if(!empty($stockdata)){

                $data3=array(
                'status'=>'Replaced',
                );  
                $this->Crud_model->SaveData('asset_details',$data3,"barcode_number='".$barcode_no."'"); 
                
                $AddstkData = array(
                    'asset_id' => $asset_id,
                    'parent_id' => $stockdata->id,
                    'barcode_number' => $barcode_number_new,
                    'barcode_image' => $barcodeImg,
                    'quantity' => 1,
                    'price' => $stockdata->price,
                    'image' => $stockdata->image,
                    'long_desc' => $stockdata->long_desc,
                    'short_desc' => $stockdata->short_desc,
                    'commanforall' => $stockdata->commanforall,
                    'warranty_type' => $stockdata->warranty_type,
                    'warranty_from_date' => $stockdata->warranty_from_date,
                    'warranty_to_date' => $stockdata->warranty_to_date,
                    'warranty_type' => $stockdata->warranty_type,
                    'warranty_description' => $stockdata->warranty_description,
                    'type' => $stockdata->type,
                    'status' => 'In_use',
                    'date' => date('Y-m-d'),
                    'created_by' => $_SESSION[SESSION_NAME]['id'],
                    'created'=>date('Y-m-d H:i:s'),
                );
                $this->Crud_model->SaveData('asset_details', $AddstkData);  
                $insert_id = $this->db->insert_id(); 

                $getMappingQtyfasset=$this->Crud_model->GetData('asset_branch_mappings_details',"id,asset_detail_id","asset_detail_id = '".$stockdata->id."'");
                if(!empty($getMappingQtyfasset)){
                    $updateAssetDetailId=array(
                    'asset_detail_id'=> $insert_id,
                    );  
                    $this->Crud_model->SaveData('asset_branch_mappings_details',$updateAssetDetailId,"asset_detail_id='".$stockdata->id."'"); 
                }
                             
                $getMappingQtyfasset=$this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as quantity","asset_id = '".$asset_id."'");
                 if($getMappingQtyfasset[0]->quantity!='0'){
                     $avlquantity= $Getasset->quantity - $getMappingQtyfasset[0]->quantity;//9-2=7
                 }else{
                    $avlquantity= $Getasset->quantity;//7
                 }
                             
                       
                $totQty = $avlquantity;         
                $stkLogData = array(
                    'asset_type_id' => $asset_type_id,
                    'financial_year_id'=>$financial_year_id,
                    'type' => 'Replaced',
                    'is_barcode' => 'Yes',
                    'quantity' => '1',
                    'available_quantity' => $totQty,
                    'asset_id' => $asset_id,
                    'asset_detail_id' => $stockdata->id,
                    'description' => "Replaced 1 quantity against Purchase order no =".$order_number." of ".$Getasset->asset_name."  of Product SKU (".$barcode_no." ) with Product SKU(".$barcode_number_new.")",
                    'date' => date('Y-m-d'),
                    'created_by' => $_SESSION[SESSION_NAME]['id'],
                    'created'=>date('Y-m-d H:i:s'),
                    'modified'=>date('Y-m-d H:i:s')
                );
                $this->Crud_model->SaveData('stock_logs', $stkLogData);  

            }


            }else{

                $data1=array(
                'status'=>'Rejected',
                );  
                $this->Crud_model->SaveData('purchase_return_replace_details',$data1,"id='".$_POST['clients'][$i]."'"); 
            }

        }


        if($_POST['status']=='Approve'){
            $getPendingDetails=$this->Crud_model->GetData('purchase_return_replace_details','',"purchase_return_replace_id='".$id."' and (status='Pending' OR status='Rejected')",'','','','');
            if(count($getPendingDetails)==0){
               $data1=array(
                'status'=>'Approved',
                );  
                $this->Crud_model->SaveData('purchase_return_replace',$data1,"id='".$id."'");      
            }

             $msg='<span class="label label-success text-center" style="margin-bottom:0px">'.$qty.'quantity against P/O No '.$order_number.' has been replaced successfully.';
        }else{
             $getPendingDetails=$this->Crud_model->GetData('purchase_return_replace_details','',"purchase_return_replace_id='".$id."' and (status='Pending' OR status='Approved')",'','','','');
            if(count($getPendingDetails)==0){
               $data1=array(
                'status'=>'Rejected',
                );  
                $this->Crud_model->SaveData('purchase_return_replace',$data1,"id='".$id."'");      
            }

              $msg='<span class="label label-danger text-center" style="margin-bottom:0px">'.$qty.'quantity against P/O No '.$order_number.' has been rejected.';
        }

        $this->session->set_flashdata('message', $msg.'</span>');
        redirect('Purchase_returns');

    }
    public function approveReturn($id){
        //print_r($_POST);echo "<br/>";exit;
        $getRequestDetails=$this->Crud_model->GetData('purchase_return_replace','',"id='".$id."' and status='Pending'",'','','','row');
        //print_r($getRequestDetails);echo "<br/>";
        $financial_year_id=$getRequestDetails->financial_year_id;
        $vendor_id=$getRequestDetails->vendor_id;
        $purchase_order_id=$getRequestDetails->purchase_order_id;
        $order_number=$_POST['order_number'];
        $qty=count($_POST['clients']);
        $amt=0;
        $assets=array();
        $asset_types=array();
        $quantity=array();
        for($i=0;$i<$qty;$i++)
        {
            $getRequestDetailsdata=$this->Crud_model->GetData('purchase_return_replace_details','',"purchase_return_replace_id='".$id."' and status='Pending' and id='".$_POST['clients'][$i]."'",'','','','row');

            $barcode_no=$getRequestDetailsdata->barcode_number;

            $GetBarcodeData=$this->Crud_model->GetData('purchase_received_detail_barcode','',"barcode_number='".$barcode_no."'",'','','','row');

            $asset_id=$getRequestDetailsdata->asset_id;
            $assets[]=$asset_id;
            $asset_type_id=$getRequestDetailsdata->asset_type_id;
            $asset_types[]=$asset_type_id;
            $quantity[]=1;
            $con = "po.id='".$purchase_order_id."' and prd.asset_id='".$asset_id."' and pr.id='".$GetBarcodeData->purchase_received_id."'";
            $PerProReturnAmt = $this->Purchase_returns_model->GetReturnAmt($con);
            $Amt = $PerProReturnAmt->price_per_qty * 1;
            $amt=$amt+$Amt;
            //print_r($barcode_no);echo "<br/>";
            //print_r($Amt);echo "<br/>";exit;
            
           
            $stockdata=$this->Crud_model->GetData('asset_details','',"asset_id='".$asset_id."' and barcode_number='".$barcode_no."'",'','','','row');
            if($_POST['status']=='Approve'){
            //print_r($stockdata);echo "<br/>";
            //print_r($getRequestDetailsdata);exit;
            $data1=array(
            'status'=>'Approved',
            );  
            $this->Crud_model->SaveData('purchase_return_replace_details',$data1,"id='".$_POST['clients'][$i]."'"); 

            $data2=array(
            'is_returned'=>'Yes',
            );  
            $this->Crud_model->SaveData('purchase_received_detail_barcode',$data2,"barcode_number='".$barcode_no."'"); 

            if(!empty($stockdata)){

                $data3=array(
                'status'=>'Returned',
                );  
                $this->Crud_model->SaveData('asset_details',$data3,"barcode_number='".$barcode_no."'"); 
                
                /*-- Update MapDetails--*/
                $mapBarcodeData=$this->Crud_model->GetData('asset_branch_mappings_details','asset_branch_mappings_id',"asset_id='".$asset_id."' and asset_detail_id='".$stockdata->id."'",'','','','row');
                if(!empty($mapBarcodeData)){
                    $this->Crud_model->DeleteData('asset_branch_mappings_details',"asset_id='".$asset_id."' and asset_detail_id='".$stockdata->id."'");
                    $getMappingQty=$this->Crud_model->GetData('asset_branch_mappings',"asset_quantity","id = '".$mapBarcodeData->asset_branch_mappings_id."'");
                    $mapqty=$getMappingQty[0]->asset_quantity - 1; 
                    $updateQty=array(
                        'asset_quantity'=>$mapqty,
                    );
                    $this->Crud_model->SaveData('asset_branch_mappings',$updateQty,"id = '".$mapBarcodeData->asset_branch_mappings_id."'");  
                }

                /*-- Update MapDetails--*/
                /*-- Update Qty--*/
                    $selectTotalQty= $this->Crud_model->GetData('asset_details',"sum(quantity) as quantity","asset_id = '".$asset_id."' and (status='In_use' || status='Scrap')");
                    $AssetQty=array(
                    'quantity'=>$selectTotalQty[0]->quantity,
                    );  
                    $this->Crud_model->SaveData('assets',$AssetQty,"id='".$asset_id."'"); 
                /*-- Update Qty--*/
                 $Getasset = $this->Crud_model->GetData('assets','',"id = '".$asset_id."'",'','','','single');
                
                $getMappingQtyfasset=$this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as quantity","asset_id = '".$asset_id."'");
                 if($getMappingQtyfasset[0]->quantity!='0'){
                     $avlquantity= $Getasset->quantity - $getMappingQtyfasset[0]->quantity;//9-2=7
                 }else{
                    $avlquantity= $Getasset->quantity;//7
                 }
                             
                       
                $totQty = $avlquantity;
                

                $stkLogData = array(
                    'asset_type_id' => $asset_type_id,
                    'financial_year_id'=>$financial_year_id,
                    'type' => 'Returned',
                    'is_barcode' => 'Yes',
                    'asset_id' => $asset_id,
                    'asset_detail_id' => $stockdata->id,
                    'quantity' => '1',
                    'available_quantity' => $totQty,
                    'description' => "Returned 1 quantity against Purchase order no =".$order_number." of ".$Getasset->asset_name."  of Product SKU (".$barcode_no." )",
                    'date' => date('Y-m-d'),
                    'total_amount' => $Amt,
                    'created_by' => $_SESSION[SESSION_NAME]['id'],
                    'created'=>date('Y-m-d H:i:s'),
                    'modified'=>date('Y-m-d H:i:s')
                );
                $this->Crud_model->SaveData('stock_logs', $stkLogData);  

            }

             //Vendor Transaction
            $vendorBal = $this->Crud_model->GetData('vendor_transactions','',"vendor_id='".$vendor_id."'",'','id desc','1','single');
            $bal = $vendorBal->balance - $Amt;
                $vtrans = array(
                    'financial_year_id' => $financial_year_id,
                    'purchase_order_id' => $purchase_order_id,
                    'vendor_id' => $vendor_id,
                    'payment_date' => date('Y-m-d'),
                    'description' => '1 quantity of '.$Getasset->asset_name.' Returned against PO No '.$order_number,
                    'inward' => $Amt,
                    'balance' => $bal,
                    'status' => 'Return'
                    );
                $this->Crud_model->SaveData('vendor_transactions',$vtrans);
            }else{

            $data1=array(
            'status'=>'Rejected',
            );  
            $this->Crud_model->SaveData('purchase_return_replace_details',$data1,"id='".$_POST['clients'][$i]."'"); 
            }
          }
        
        if($_POST['status']=='Approve'){
            $getPendingDetails=$this->Crud_model->GetData('purchase_return_replace_details','',"purchase_return_replace_id='".$id."' and status='Pending'",'','','','');
            if(count($getPendingDetails)==0){
               $data1=array(
                'status'=>'Approved',
                );  
                $this->Crud_model->SaveData('purchase_return_replace',$data1,"id='".$id."'");      
            }

             $msg='<span class="label label-success text-center" style="margin-bottom:0px">'.$qty.'quantity against P/O No '.$order_number.' has been returned successfully.';
        }else{
             $getPendingDetails=$this->Crud_model->GetData('purchase_return_replace_details','',"purchase_return_replace_id='".$id."' and (status='Pending' || status='Approved')",'','','','');
            if(count($getPendingDetails)==0){
               $data1=array(
                'status'=>'Rejected',
                );  
                $this->Crud_model->SaveData('purchase_return_replace',$data1,"id='".$id."'");      
            }

              $msg='<span class="label label-danger text-center" style="margin-bottom:0px">'.$qty.'quantity against P/O No '.$order_number.' has been rejected.';
        }
         $this->session->set_flashdata('message', $msg.'</span>');


         //print_r($assets);echo "<br/>";
         //print_r($asset_types);echo "<br/>";
         //print_r($quantity);exit;

         redirect('Purchase_returns');
    }

   public function create_action()
    {
       //print_r($_POST);echo "</br>";exit;

        $asset_type_id = $this->input->post('asset_type_id'); 
        $vendor_id = $this->input->post('vendor_id');
        $purchase_order_id = $this->input->post('purchase_order_id');
        $podid = $this->input->post('podid');
        $order_number = $this->input->post('order_number');
        $quantity = $this->input->post('quantity');
        $remark = $this->input->post('remark');
        $asset_id = $this->input->post('asset_id');
        $branch_id = $this->input->post('branch_id');
        $count = count($podid);
        //print_r($count);
        $financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
    
        $qty=0;

        for($p=0;$p< count($quantity);$p++){
             $qty= $qty+$quantity[$p];
        }
           
        $table='<table border="1" cellpadding="5" cellspacing="0" width="100%" style="font-size: 12px;"> 
                                            <tr valign="top">
                                                <th>Sr No.</th>
                                                <th>Asset</th>
                                                <th>Product SKU</th>
                                                <th>Type</th>
                                                <th>Remark</th>
                                                <th>Quantity</th>
                                            </tr><tbody>';
        $sr=0;
        for ($k=0; $k < $count; $k++) 
        {
            if(!empty($quantity[$k])){
                $c=count($_POST[$asset_id[$k].'_barcode_number']);
                //print_r($c);echo '<br/>';
                 $astData=$this->Crud_model->GetData("assets",'',"id='".$_POST['asset_id'][$k]."'",'','','','1');
                 $astName=$astData->asset_name;


                for($m=0;$m< $c; $m++)
                {
                 $barcode_id=$_POST[$asset_id[$k].'_barcode_number'][$m];
                 if($_POST[$barcode_id.'_remark']==''){
                 $_POST[$barcode_id.'_remark']='-';
                 }
                 $getBarCode = $this->Crud_model->GetData('purchase_received_detail_barcode','',"id='".$barcode_id."'",'','id desc','','single'); 
                 $product_sku=$getBarCode->barcode_number;
                 $sr++;
                 $table.="<tr>
                            <td>".$sr."</td>
                            <td>".$astName."</td>
                            <td>".$product_sku."</td>
                            <td>".$_POST[$barcode_id.'_type']."</td>
                            <td>".$_POST[$barcode_id.'_remark']."</td>
                            <td>1</td>
                            </tr>";

                      
                }

            }
        } 
        $table.="
         <tr><th colspan='5' style='text-align:right'>Total Qty</th><th  style='text-align:left'>".$qty."</th></td></tbody>
         </table>";
        //print_r($table);exit;





        $qty=0;

        for($p=0;$p< count($quantity);$p++){
             $qty= $qty+$quantity[$p];
        }
        //print_r($qty);exit;


        $data=array(
               'financial_year_id'=>$financialYear->id,
               'vendor_id'=>$vendor_id,
               'purchase_order_id'=>$purchase_order_id,
               'quantity'=>$qty
        );
        $this->Crud_model->SaveData('purchase_return_replace',$data);
        $lastId=$this->db->insert_id();
        for ($i=0; $i < $count; $i++) 
        {   

            if(!empty($quantity[$i])){
                $c=count($_POST[$asset_id[$i].'_barcode_number']);
                for($j=0;$j< $c; $j++)
                {
                 $barcode_id=$_POST[$asset_id[$i].'_barcode_number'][$j];
                 $getBarCode = $this->Crud_model->GetData('purchase_received_detail_barcode','',"id='".$barcode_id."'",'','id desc','','single'); 

                    $data=array(
                           'purchase_return_replace_id'=>$lastId,
                           'purchase_order_id'=>$purchase_order_id,
                           'purchase_order_detail_id'=>$podid[$i],
                           'vendor_id'=>$vendor_id,
                           'asset_type_id'=>$_POST['asset_type_id'][$i],
                           'asset_id'=>$_POST['asset_id'][$i],
                           'barcode_number'=>$getBarCode->barcode_number,
                           'remark'=>$_POST[$barcode_id.'_remark'],
                           'type'=>$_POST[$barcode_id.'_type'],

                    );
                    $this->Crud_model->SaveData('purchase_return_replace_details',$data);

                }

            }
        } 

          /* Mail Code Start Here */
          $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='purchase_return/replace'");
            $vandor = $this->Crud_model->GetData("vendors",'',"id='".$_POST['vendor_id']."'");
            $purchase_orders = $this->Crud_model->GetData("purchase_orders",'',"id='".$_POST['purchase_order_id']."'");
            if(!empty($mail_body))
            {
                $mail_body[0]->mail_body=str_replace("{shop_name}",ucfirst($vandor[0]->shop_name),$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{name}",ucfirst($vandor[0]->name),$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{vendor}",ucfirst($vandor[0]->name),$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{email}",$vandor[0]->email,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{mobile}",$vandor[0]->mobile,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{address}",$vandor[0]->address,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{order_number}",$purchase_orders[0]->order_number,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{table}",$table,$mail_body[0]->mail_body);
                $subject=$mail_body[0]->mail_subject;
                $body=$mail_body[0]->mail_body;
                //print_r($body);exit;
                $MailData = array('mailoutbox_to'=>$vandor[0]->email,'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body[0]->type);
                //$Send=$this->SendMail->Send($MailData);
                $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Your purchase return/replace created successfully, Please check your email</p></div>');
            }
            else
            { 
                $this->session->set_flashdata("message",'<div class="label label-danger text-center" style="margin-bottom:0px;"><p>Error in sending email...</p></div>'); 
            }
          /* Mail Code End Here */
        $msg='<span class="label label-success text-center" style="margin-bottom:0px">Purchase Return/ Replace of '.$qty.'quantity against P/O No '.$order_number.' has been created successfully.';

        
         $this->session->set_flashdata('message', $msg.'</span>');
        redirect('Purchase_returns');
    }

/*Create Payment Of Party Against Invoice*/
    public function GetPartyPayment()
    {
        $party = $this->Crud_model->GetData('corporates','',"status='Active'");
        $types = $this->Crud_model->GetData('payment_types','',"status='Active' and type!='NEFT'");
        $data = array('heading'=>'Party Payment','createAction'=>site_url('Purchase_returns/GetPartyConfirmPayment'),'bread'=>'','party'=>$party,'types'=>$types,);
        $this->layouts->view('purchase_orders/payment_form',$data); 
    }

    public function GetInvoices()
    {
        $inv = $this->Purchase_returns_model->GetPartyInvoice("party_invoice.party_id='".$_POST['id']."' and status='Created'");
        $response = '<option value="">-- Select Invoice --</option>';
        foreach ($inv as $row) {
            $response .= '<option value="'.$row->id.'">'.$row->invoice_no.'</option>';
        }
        echo $response; exit;
    }

    public function GetBalanceAmt()
    {
        $party_invoice_id = $this->input->post('id');
        $bal = $this->Purchase_returns_model->GetPartyInvoice("party_invoice.id='".$party_invoice_id."' and status='Created'");
        print_r($bal[0]->bal); exit;
    }

    public function GetPartyConfirmPayment()
    {
        $financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
        $data = array(
            'financial_year_id' => $financialYear->id,
            'party_id' => $_POST['party_id'],
            'party_invoice_id' => $_POST['party_invoice_id'],
            'amount' => $_POST['amount'],
            'payment_type' => $_POST['payment_type'],
            'cheque_no' => $_POST['cheque_no'],
            'bank_name' => $_POST['bank_name'],
            'cheque_date'  => $_POST['cheque_date'],
            'tran_date'=>date('Y-m-d'),
            );
        $this->Crud_model->SaveData('party_transactions',$data);
        redirect('Purchase_returns');
    }
/*Ends Create Payment Of Party Against Invoice*/

/*Party Invoice Details*/
    public function PartyInvoiceindex($id)
    {
        $party = $this->Crud_model->GetData('corporates','',"id='".$id."'",'','','','single');
        if(empty($party)){ redirect('Purchase_returns'); }
        $data = array('heading'=>'Party Invoice - '.$party->name,'bread'=>'','id'=>$id);
        $this->layouts->view('purchase_orders/partyinvioce_list',$data); 
    }

    public function ajax_PartyInvoice($id)
    {
        $con = "party_id='".$id."'";
        $AllData = $this->Party_invoice_model->get_datatables($con);

        $no = 0;        
        $data = array();        
        foreach ($AllData as $row) 
        { 
            $btn = '<a href="'.site_url('Parties/preview/'.$id.'/'.$row->id).'"><button type="button" class="btn btn-info btn-sm"><i class="fa fa-eye">&nbsp;</i></button></a>';

            $no++;
            $nestedData = array();
            $nestedData[] = $no; 
            $nestedData[] = $row->invoice_no; 
            $nestedData[] = date('d-m-Y',strtotime($row->invoice_date)); 
            $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($row->amount,2); 
            $nestedData[] = $row->per_discount; 
            $nestedData[] = $row->per_gst; 
            $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($row->final_amount,2); 
            $nestedData[] = $row->status; 
            $nestedData[] = $btn; 
            $data[] = $nestedData;
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Party_invoice_model->count_all($con),
                    "recordsFiltered" => $this->Party_invoice_model->count_filtered($con),
                    "data" => $data,
                );
        //output to json format
        echo json_encode($output);       
    }
/*Ends Party Invoice Details*/



    
}
?>