<?php
/* all controller functionality developed by Prashant */
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_orders extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $this->load->database();
        $this->load->library(array('session','form_validation','image_lib'));
        $this->load->model(array('Crud_model','Purchase_orders_model'));
        $this->load->helper(array('form', 'url', 'html'));
        //$this->load->library(array('Urlcheck'));
        //$this->Urlcheck = new Urlcheck();
        $this->load->library(array('SendMail'));
        $this->SendMail = new SendMail();
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
    $exportbutton = '';
     if(!empty($_SESSION[SESSION_NAME]['getMenus']))
     {  
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Purchase_orders')
              { 
                 if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                  if(!empty($menu->act_export)){ $exportbutton='1'; }else{ $exportbutton='0'; }
              }
          }
        }

        $controller_name = $this->uri->segment(1);
        $create =anchor(site_url($controller_name.'/create'),'<span title="Create" class="fa fa-plus"></span>');  
        $export ='<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>'; 
      
        
        $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class="active">Manage Purchase Orders</li>
            </ul>';
        $assets = $this->Crud_model->GetData('mst_asset_types','type,id',"status='Active' and is_delete='No'");
        $vendors = $this->Crud_model->GetData('vendors','shop_name,name,id');
        $quotations = $this->Crud_model->GetData('quotations','id,quotation_no');
        $data = array('heading'=>'Purchase Orders','createAction'=>site_url('Purchase_orders/create'),'changeAction'=>site_url('Purchase_orders/changeStatus'),'deleteAction'=>site_url('Purchase_orders/delete'),'assets'=>$assets,'vendors'=>$vendors,'quotations'=>$quotations,'create' =>$create,'export' =>$export,'breadcrumbs'=>$breadcrumbs, 'addPermission'=>$add, 'exportPermission'=>$exportbutton );
        $this->load->view('purchase_orders/list',$data); 
        }
    else
    {
      redirect('Dashboard');
    } 
    }
    
    public function ajax_manage_page()
    {
        //print_r($_POST);exit();
        /* ----- Condition for superadmin Start----- */
        $controller_name = $this->uri->segment(1);
        /* ----- Condition for Search start ----- */   
        $fromdate = $_POST['SearchData1'];
        $todate = $_POST['SearchData2'];
        $quotation_id = $_POST['SearchData3'];
        $vendor_id = $_POST['SearchData4'];
        
        
        $con = "P.id<>0";
        if($vendor_id!='')
        {
            $con .=" and P.vendor_id = '".$vendor_id."' ";
        }
        if($quotation_id!='')
        {
            $con .=" and P.quotation_id = '".$quotation_id."' ";
        }
        /*if($distributor_id!='')
        {
            $con .=" and P.distributor_id = '".$distributor_id."' ";
        }*/
        if($fromdate!='')
        {
            $con .=" and P.purchase_date >= '".date('Y-m-d',strtotime($fromdate))."' ";
        }
        if($todate!='')
        {
            $con .=" and P.purchase_date <= '".date('Y-m-d',strtotime($todate))."' ";
        }
        /* ----- Condition for Search end ----- */
       /* foreach($_SESSION[SESSION_NAME]['moduleAction'] as $row)
        { 
            if($row->value=='Purchase_orders')
            { 
              if(!empty($row->act_view)){  $view='1';}else{ $view='0';}
              if(!empty($row->act_status)){  $act_status='1';}else{ $act_status='0';}
            }
        }*/

        $act_status= '';
        $view = '';
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
          { 
            foreach($row as $menu)
            { 
                if($menu->value=='Purchase_orders')
                { 
                    if(!empty($menu->act_view)){  $view='1';}else{ $view='0';}
                    if(!empty($menu->act_status)){  $act_status='1';}else{ $act_status='0';}
                }
            }
          }


        $AllData = $this->Purchase_orders_model->get_datatables($con);      
        $no = 0;        
        $data = array();        
        foreach ($AllData as $rowData) 
        {  
            
            if(!empty($view)){
            $view ='<a href="'.site_url('Purchase_orders/read/').$rowData->id.'"><span title="View PO" class="btn btn-primary btn-circle btn-sm"><i class="fa fa-eye"></i></span></a>';
           }
            $poreceive = $this->Crud_model->GetData('purchase_received_details','',"purchase_order_id='".$rowData->id."'");
            
            if(count($poreceive)==0)
            {
              $print =" | ".anchor(site_url('Purchase_orders/read/'.$rowData->id.'?flag=print'),'<span title="Print PO" class="btn btn-primary btn-circle btn-sm"><i class="fa fa-print"></i></span>', 'target="_blank"');
            }
            else
            {
              $print ="";
            }
          
            $btn = '';
            if(!empty($view)){
                $btn .=$view;
                $btn .=$print;
            }

            $status = '';
          //$btn .=anchor(site_url('Purchase_orders/barcode_view/'.$rowData->id),'| <button title="View Barcode" class="btn btn-primary btn-circle btn-sm"><i class="fa fa-barcode"></i></button>');

            if(!empty($act_status))
            {
                if($rowData->status=='Received')
                {
                    $status = "<a href='#checkStatus' data-toggle='modal' class='label-success label'>Received</a>";    
                } else {
                    $status = "<a href='#checkStatus' data-toggle='modal'  class='label-warning label'>Pending</a>";
                }
            }    
          
            $total = $rowData->sum + $rowData->transport ;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$rowData->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;
            if(!empty($rowData->distrubution_name))
            {
                $dist = ucwords($rowData->distrubution_name);
            } else {
                $dist = '-';
            }
            if(!empty($rowData->type))
            {
                $type = ucwords($rowData->type);
            } else {
                $type = '-';
            }
            if(!empty($rowData->vendorname))
            {
                $vendorname = ucwords($rowData->vendorname);
            } else {
                $vendorname = '-';
            }
            if(!empty($status))
            {
                $status = $status;
            }
            else
            {
                $status = '-';
            }

            $no++;
            $nestedData = array();
            $nestedData[] = $no; 
            $nestedData[] = $vendorname;
            $nestedData[] = $rowData->quotation_no;
            $nestedData[] = $rowData->order_number;
            $nestedData[] = date('d-m-Y',strtotime($rowData->purchase_date));
            $nestedData[] = $rowData->total_items;
            $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($total,2);
            $nestedData[] = $status;
            $nestedData[] = $btn;
            $data[] = $nestedData;
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Purchase_orders_model->count_all(),
                    "recordsFiltered" => $this->Purchase_orders_model->count_filtered(),
                    "data" => $data,
                );
        //output to json format
        echo json_encode($output);       
    }

    public function create()
    {   
        $mo=date('m');        
        if($mo > 03)
        {
            $from = date('Y');
            $to = date('Y', strtotime('+1 Year'));
        }elseif ($mo < 04) {            
            $from = date('Y', strtotime('-1 Year'));
            $to = date('Y');
        }   
        $Asesment = date('y',strtotime($from.'-01-01')).'-'.date('y',strtotime($to.'-01-01'));            
        $po = $this->Crud_model->GetData('purchase_orders','','','','id desc','1','single');
        $asset_types = $this->Crud_model->GetData('mst_asset_types','',"is_delete='No'",'','type');
        $category = $this->Crud_model->GetData('categories','','','','title');
        $units = $this->Crud_model->GetData('unit_types','','status="Active"','','unit');
        $brands = $this->Crud_model->GetData('brands','','status="Active"','','brand_name');
        $vendors = $this->Crud_model->GetData('vendors','','status="Active"','','name');
        $branches = $this->Crud_model->GetData('branches','','status="Active" and is_delete="No"','','branch_title');
        if(empty($po)){
            $no = '1';
        } else {
            $ord = explode('/', $po->order_number);
            if($ord[1]==$Asesment){
                $no = $po->id + 1;
            } else {
                $no = '1';                
            }
        }
        $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class=""><a href="'.site_url('Dashboard/index').'">Manage Purchase Orders</a></li>
            <li class="active">Create Purchase Orders</li>
            </ul>';
        $order_number = 'PO/'.$Asesment.'/'.$no;
        $data = array(
                    'heading'=>'Create Purchase orders',
                    'button'=>'Create',
                    'breadcrumbs'=>$breadcrumbs,     
                    'action'=>site_url('Purchase_orders/create_action'),
                    'order_number'=>$order_number,
                    'asset_types'=>$asset_types,
                    'category'=>$category,
                    'units'=>$units,
                    'brands'=>$brands,
                    'branches'=>$branches,
                    'vendors'=>$vendors,
                );
        
        $this->load->view('purchase_orders/form',$data);        
    }

    /*public function create_action()
    {   
        //print_r($_POST);exit;
       // $sess = $this->session->userdata(SESSION_NAME);
        //$branch_id = '0'/*$this->input->post('branch_id');
        $quotation_id = $this->input->post('quotation_id');
        $vendor_id = $this->input->post('vendor_id');

        //$asset_type_id = $this->input->post('asset_type');

        $count = count($this->input->post('asset_id'));
        if(!empty($quotation_id)){
        $count = count($this->input->post('qasset_id')); 
        }

        $mo=date('m');
        if($mo > 03) { 
            $from = date('Y');
            $to = date('Y', strtotime('+1 Year'));
        }elseif($mo < 04) {
            $from = date('Y', strtotime('-1 Year'));
            $to = date('Y');
        }
        $Asesment = date('y',strtotime($from.'-01-01')).'-'.date('y',strtotime($to.'-01-01'));
        $po = $this->Crud_model->GetData('purchase_orders','','','','id desc','1','single');
        if(empty($po)){ $no = '1'; } else {
            $ord = explode('/', $po->order_number);
            if($ord[1]==$Asesment){
                $no = $po->id + 1;
            } else {
                $no = '1';
            }
        }
        $order_number = 'PO/'.$Asesment.'/'.$no;
        $financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
        $PoData = array(
            'financial_year_id' => $financialYear->id,
            'branch_id' => $branch_id,
            'quotation_id' => $quotation_id,
            'vendor_id' => $vendor_id,
            ///'asset_type_id' => $asset_type_id,
            'purchase_date' => date('Y-m-d'),
            'total_items' => $count,
            'order_number' => $order_number,
            );
        $this->Crud_model->SaveData('purchase_orders',$PoData);
        $LastId=$this->db->insert_id();

        for ($i=0; $i < $count; $i++)
        {
                $GetAssets= $this->Crud_model->GetData('assets','',"id='".$this->input->post('asset_id')[$i]."'",'','','','single');
                $asset_type_id=$GetAssets->asset_type_id;
                if(!empty($quotation_id)){
                    
                    $GetAssets= $this->Crud_model->GetData('assets','',"id='".$this->input->post('qasset_id')[$i]."'",'','','','single');
                    $_POST['cat_id'][$i]=$_POST['qcat_id'][$i];
                    $_POST['subcat_id'][$i]=$_POST['qsub_cat_id'][$i];
                    $_POST['asset_id'][$i]= $_POST['qasset_id'][$i];
                    $_POST['quantity'][$i]=$_POST['qquantity'][$i];
                    $_POST['unit_id'][$i]=$_POST['qunit_id'][$i];
                    $asset_type_id=$GetAssets->asset_type_id;
                }
                
                $PodData = array(
                    'vendor_id' => $vendor_id,
                    'asset_type_id' => $asset_type_id,
                    'purchase_order_id' => $LastId,
                    'cat_id' => $this->input->post('cat_id')[$i],
                    'subcat_id' => $this->input->post('subcat_id')[$i],
                    'asset_id' => $this->input->post('asset_id')[$i],
                    'quantity' => $this->input->post('quantity')[$i],
                    'unit_id' => $this->input->post('unit_id')[$i],
                    );
                $this->Crud_model->SaveData('purchase_order_details',$PodData);

                $LastPodId = $this->db->insert_id();
               // print_r($LastPodId);exit;
            
        }

        $this->session->set_flashdata('message', '<div class="alert alert-block alert-success text-center" style="margin-bottom:0px;"><p>Purchase order created successfully</p></div>');
        redirect(site_url('Purchase_orders/read/'.$LastId.'?flag=2&tmp=po'));
    }*/
    public function create_action()
    {   
        //print_r($_POST);exit;
    
       
        $sess = $this->session->userdata(SESSION_NAME);
        $branch_id = '0';
        $quotation_id = $this->input->post('quotation_id');
        $vendor_id = $this->input->post('vendor_id');


        $count = count($this->input->post('asset_id'));
        if(!empty($quotation_id)){
        $count = count($this->input->post('qasset_id')); 
        }
            $mo=date('m');
            if($mo > 03) { 
                $from = date('Y');
                $to = date('Y', strtotime('+1 Year'));
            }elseif($mo < 04) {
                $from = date('Y', strtotime('-1 Year'));
                $to = date('Y');
            }
            $Asesment = date('y',strtotime($from.'-01-01')).'-'.date('y',strtotime($to.'-01-01'));
            $po = $this->Crud_model->GetData('purchase_orders','','','','id desc','1','single');
            if(empty($po)){ $no = '1'; } else {
                $ord = explode('/', $po->order_number);
                if($ord[1]==$Asesment){
                    $no = $po->id + 1;
                } else {
                    $no = '1';
                }
            }
            $order_number = 'PO/'.$Asesment.'/'.$no;
            
            $financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
            $PoData = array(
                'financial_year_id' => $financialYear->id,
                'branch_id' => $branch_id,
                'quotation_id' => $quotation_id,
                'vendor_id' => $vendor_id,
                'purchase_date' => date('Y-m-d'),
                'total_items' => $count,
                'order_number' => $order_number,
                );
            $this->Crud_model->SaveData('purchase_orders',$PoData);
            $LastId=$this->db->insert_id();
            /* Table Start*/
            $table='<table border="1" cellpadding="5" cellspacing="0" width="100%" style="font-size: 12px;"> 
                                    <tr valign="top">
                                        <th>Sr No.</th>
                                        <th>Asset</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                    </tr><tbody>';
         $sr=0;                           
         $qty=0;                           
         for ($i=0; $i < $count; $i++)
            {
                $sr++;
                 if(!empty($quotation_id)){
                    
                     $quotations = $this->Crud_model->GetData("quotations",'quotation_no',"id='".$quotation_id."'");      
                     $_POST['asset_id'][$i]= $_POST['qasset_id'][$i];
                     $_POST['quantity'][$i]= $_POST['qquantity'][$i];
                     $_POST['unit_id'][$i]= $_POST['qunit_id'][$i];
                   
                }else{
                    $quotations='';
                     $_POST['asset_id'][$i];
                     $_POST['quantity'][$i];
                     $_POST['unit_id'][$i];
                }
                $qty=$qty+$_POST['quantity'][$i]; 
                 $GetAssets= $this->Crud_model->GetData('assets','',"id='".$this->input->post('asset_id')[$i]."'",'','','','single');

                 $unit_types=$this->Crud_model->GetData("unit_types",'',"id='".$_POST['unit_id'][$i]."'",'','','','1');
                 $astName=$GetAssets->asset_name;
                 $unit_type=$unit_types->unit;
                 $table.="<tr>
                        <td>".$sr."</td>
                        <td>".$astName."</td>
                        <td>".$unit_type."</td>
                        <td>".$_POST['quantity'][$i]."</td>

                    </tr>";

            }
            $table.="<tr><th colspan='3' style='text-align:right'>Total Qty</th><th  style='text-align:left'>".$qty."</th></td></tbody></table>"; 

            for ($i=0; $i < $count; $i++)
            {
                $GetAssets= $this->Crud_model->GetData('assets','',"id='".$this->input->post('asset_id')[$i]."'",'','','','single');
                $asset_type_id=$GetAssets->asset_type_id;
                if(!empty($quotation_id)){
                    
                    $GetAssets= $this->Crud_model->GetData('assets','',"id='".$this->input->post('qasset_id')[$i]."'",'','','','single');
                    $_POST['cat_id'][$i]=$_POST['qcat_id'][$i];
                    $_POST['subcat_id'][$i]=$_POST['qsub_cat_id'][$i];
                    $_POST['asset_id'][$i]= $_POST['qasset_id'][$i];
                    $_POST['quantity'][$i]=$_POST['qquantity'][$i];
                    $_POST['unit_id'][$i]=$_POST['qunit_id'][$i];
                    $asset_type_id=$GetAssets->asset_type_id;
                }
                
                $PodData = array(
                    'vendor_id' => $vendor_id,
                    'asset_type_id' => $asset_type_id,
                    'purchase_order_id' => $LastId,
                    'cat_id' => $this->input->post('cat_id')[$i],
                    'subcat_id' => $this->input->post('subcat_id')[$i],
                    'asset_id' => $this->input->post('asset_id')[$i],
                    'quantity' => $this->input->post('quantity')[$i],
                    'unit_id' => $this->input->post('unit_id')[$i],
                    );
                $this->Crud_model->SaveData('purchase_order_details',$PodData);

                $LastPodId = $this->db->insert_id();
             }

            /* Table End*/
            $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='purchase_order'");
            $vandor = $this->Crud_model->GetData("vendors",'',"id='".$vendor_id."'");
       
            if(!empty($mail_body))
              {
                $mail_body[0]->mail_body=str_replace("{shop_name}",ucfirst($vandor[0]->shop_name),$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{name}",ucfirst($vandor[0]->name),$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{email}",$vandor[0]->email,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{mobile}",$vandor[0]->mobile,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{address}",$vandor[0]->address,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{PO_number}",$order_number,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{PO_date}",date('d-m-Y'),$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{vendor}",ucfirst($vandor[0]->name),$mail_body[0]->mail_body);

                if(!empty($quotations)){
                    $mail_body[0]->mail_body=str_replace("{quotation}",$quotations[0]->quotation_no,$mail_body[0]->mail_body);
                }else{
                    $mail_body[0]->mail_body=str_replace("{quotation}",'-',$mail_body[0]->mail_body);
                }


                $mail_body[0]->mail_body=str_replace("{table}",$table,$mail_body[0]->mail_body);
                $subject=$mail_body[0]->mail_subject;
                $body=$mail_body[0]->mail_body;
                //print_r($body);exit;
                $MailData = array('mailoutbox_to'=>$vandor[0]->email,'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body[0]->type);
                //$Send=$this->SendMail->Send($MailData);
             
            }
         

        $this->session->set_flashdata('message', '<div class="alert alert-block alert-success text-center" style="margin-bottom:0px;"><p>Purchase order created successfully</p></div>');
        redirect(site_url('Purchase_orders/read/'.$LastId.'?flag=2&tmp=po'));
    }


    public function read($id)
    {
        $po = $this->Purchase_orders_model->GetPurchaseorders("p.id='".$id."'"); 
        if(empty($po)){ redirect(site_url('Purchase_orders')); }
        $pod = $this->Purchase_orders_model->GetPurchaseorderdetails("pod.purchase_order_id = '".$id."'"); 
        //print_r($pod);
        $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class="active">Manage Purchase Orders</li>
            </ul>';
        $data = array(
            'id'=>$id,
            'heading'=>'View Details',
            'breadcrumbs'=>$breadcrumbs,
            'po'=>$po,
            'pod'=>$pod,
            );
        
        if(!empty($_GET['flag'])){
        if($_GET['flag']==1){
            $this->load->view('purchase_orders/receive',$data);        
        } elseif($_GET['flag']==2){
            $this->load->view('purchase_orders/view',$data);        
        } elseif($_GET['flag']=='print'){
            $this->load->view('purchase_orders/print',$data);        
        } else{
            redirect(site_url('Purchase_orders/index'));   
        } } else {        
            $this->load->view('purchase_orders/read',$data);
        }
    }

    public function viewBarcodeDetails()
    {
        $row = $this->Crud_model->GetData('purchase_received_detail_barcode','','barcode_number="'.$_POST['barcode_number'].'"','','','','1');
        if(!empty($row))
        {
            $assetType = $this->Crud_model->GetData('mst_asset_types','','id="'.$row->asset_type_id.'"','','','','1');
            $con = 'a.id="'.$row->asset_id.'"'; 
            $getData = $this->Purchase_orders_model->GetBarcodeDetails($con);

            $po = $this->Purchase_orders_model->GetPurchaseorders("p.id='".$row->purchase_order_id."'");
            //print_r($this->db->last_query()); exit;
        }
        else
        {
            $getData = '';
            $assetType = '';
            $po = '';
        }
         $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class=""> <a href="'.site_url('Purchase_orders/index').'">Manage Purchase Orders</a></li>
            <li class="active">Barcode view details</li>
            </ul>';
        $data = array(
                'breadcrumbs'=>$breadcrumbs,
                'getData' => $getData,
                'row' => $row,
                'assetType' => $assetType,
                'po' => $po,
            );
        $this->load->view('purchase_orders/view_details_barcode',$data);
    }

    public function save_receive_order()
    {  
        
            

        $id=$this->input->post('id');
        $sr=$this->input->post('sr_no');
        $unit=$this->input->post('unit');
        $rate=$this->input->post('rate');
        $fess=$this->input->post('fess');
        $cat_id=$this->input->post('cat_id');
        if($this->input->post('lot_no') =='')
        {
            $lot_no ='1';
        }
        else
        {
            $lot_no = $this->input->post('lot_no');
        }
        $status=$this->input->post('status');
        $quantity=$this->input->post('quantity');
        $vendor_id=$this->input->post('vendor_id');
        $cat_id=$this->input->post('cat_id');
        $subcat_id=$this->input->post('subcat_id');
        $asset_id=$this->input->post('asset_id');
        $asset_type_id=$this->input->post('asset_type_id');
        $availableQty=$this->input->post('availableQty');
        $received_qty=$this->input->post('received_qty');
        //$weight_price_id=$this->input->post('weight_price_id');
        $quotation_id = $this->input->post('quotation_id');
        $branch_id = $this->input->post('branch_id');
        $purchase_order_id=$this->input->post('purchase_order_id');
        $quotation_id=$this->input->post('quotation_id');
        $countLoop=count($rate);

        if($_FILES['bill_copy']['error']=='0')
        { 
            $file_element_name = 'bill_copy';
            /*set file parameters*/
            $config['upload_path'] = getcwd().'/uploads/bill_copy';
            $config['allowed_types'] = '*';//pdf|jpeg|doc|xls|xml|jpg|png|txt
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = False;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($file_element_name))
            {
                $error = array('error' => $this->upload->display_errors('<p>', '</p>'));
               print_r($error);exit();
            }
            else
            {
                $fileUploaded = $this->upload->data();
            }
        }

        $financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
        $receivedData = array('financial_year_id'=>$financialYear->id,'purchase_order_id'=>$purchase_order_id,'lot_no'=>$lot_no,'bill_no'=>$_POST['bill_no'],'bill_copy'=>$fileUploaded['file_name'],'amount'=>$_POST['amount'],'total_amount'=>$_POST['total_amount'],'created'=>date('Y-m-d H:i:s'),'modified'=>date('Y-m-d H:i:s'),);
        $this->Crud_model->SaveData('purchase_received',$receivedData);
        $LastReceivedId = $this->db->insert_id();

        $vehicleData = array('financial_year_id'=>$financialYear->id,'purchase_order_id'=>$purchase_order_id,'purchase_received_id'=>$LastReceivedId,'driver_name'=>$_POST['driver_name'],'vehicle_number'=>$_POST['vehicle_no'],'labour_charge'=>$_POST['labour_charge'],'extra_vendor_charge'=>$_POST['extra_vendor_charge'],'created'=>date('Y-m-d H:i:s'),'modified'=>date('Y-m-d H:i:s'),);
        $this->Crud_model->SaveData('vehicle_management',$vehicleData);     

        $rectotal = 0;
        for ($i=0; $i < $countLoop; $i++) 
        {    
            if(($rate[$i] != '') || ($status[$i] != 'Pending'))
            {


                   
                $SaveData = array(
                    'purchase_order_id'=>$purchase_order_id, 
                    'purchase_order_detail_id'=>$id[$i], 
                    'purchase_received_id'=>$LastReceivedId, 
                    'cat_id' => $cat_id[$i],
                    'subcat_id' => $subcat_id[$i],
                    'asset_id' => $asset_id[$i],
                    'asset_type_id' => $asset_type_id[$i],
                    'branch_id' => $branch_id,
                    'received_qty' => $received_qty[$i],
                    'per_unit_price'=>$_POST['per_unit_price'][$i],
                    'per_unit_mrp'=>$_POST['per_unit_mrp'][$i],
                    'price'=>$_POST['rate'][$i],
                    'cgst'=>$_POST['cgst'][$i],
                    'sgst'=>$_POST['sgst'][$i],
                    'cgstAmt'=>$_POST['cgstAmt'][$i],
                    'sgstAmt'=>$_POST['sgstAmt'][$i],
                    'fess' => $fess[$i],
                    'rate' => $rate[$i],
                    'status' => $status[$i],
                    'created'=>date('Y-m-d H:i:s'),
                    'modified'=>date('Y-m-d H:i:s')
                );
                $rectotal += $rate[$i];
                $this->Crud_model->SaveData('purchase_received_details',$SaveData);                
                $LastReceivedDetailsId = $this->db->insert_id();  
                if($availableQty[$i] == $received_qty[$i])
                {
                    $UpdateData = array('received_date'=>date('Y-m-d'),'status'=>$status[$i],'price'=>$_POST['per_unit_price'][$i]);
                    $this->Crud_model->SaveData('purchase_order_details',$UpdateData,"id='".$id[$i]."'");
                }

                // save in barcode table as per quantity i.e barcode of product is equal to quantity
                $GetPurchaseordersData = $this->Crud_model->GetData('purchase_orders','asset_type_id',"id = '".$purchase_order_id."'",'','','','single');
            
                    for ($j=0; $j < $received_qty[$i]; $j++) 
                    { 
                        $barcodeData = array(
                                'purchase_order_id'=>$purchase_order_id,
                                'asset_type_id' => $asset_type_id[$i],
                                'purchase_received_id'=>$LastReceivedId,
                                'purchase_received_detail_id'=>$LastReceivedDetailsId,
                                'asset_id'=>$asset_id[$i],
                                'quantity'=>1,
                                'ast_amount'=>$_POST['per_unit_price'][$i],

                            );
                        $this->Crud_model->SaveData('purchase_received_detail_barcode',$barcodeData);
                        $barcodeId = $this->db->insert_id(); 
                        $barcode_number = $barcodeId.'897654';
                        $barcodeImg = $this->set_barcode($barcode_number);
                        //print_r($barcodeImg);echo '<br/>';
                        $barcodeData = array(
                                              'barcode_image'=>$barcodeImg,
                                              'barcode_number'=>$barcode_number,
                                            );

                        $this->Crud_model->SaveData('purchase_received_detail_barcode',$barcodeData,"id='".$barcodeId."'");
                    }
     
            }
        
        }    
            $_POST['amount'] = round($rectotal,2);
            $_POST['total_amount'] = round($rectotal);
            $this->Crud_model->SaveData('purchase_received',array('amount'=>$_POST['amount'],'total_amount'=>$_POST['total_amount']),"id='".$LastReceivedId."'");
            /*insert data in vendor transactions starts*/
            $lastVendorTransId=$this->Crud_model->GetData('vendor_transactions','',"vendor_id='".$vendor_id."'",'','id desc','1','single');
            if(!empty($lastVendorTransId))
            {
                $bal=$lastVendorTransId->balance + $_POST['total_amount'];
            }else{ 
                $bal=$_POST['total_amount'];
            }
            $description = 'Against Lot No: '.$lot_no.' and Bill No. '.$_POST['bill_no'];
            $vendorTransData=array('financial_year_id'=>$financialYear->id,'vendor_id'=>$vendor_id, 'purchase_order_id'=>$purchase_order_id, 'payment_date'=>date('Y-m-d'), 'description'=>$description, 'inward'=>$_POST['total_amount'], 'balance'=>$bal, 'status'=>'Inward','created'=>date('Y-m-d H:i:s'),'modified'=>date('Y-m-d H:i:s'));                  
            $this->Crud_model->SaveData('vendor_transactions',$vendorTransData); 
            /*Ends Vendor Transaction*/

            $PurchaseOrders = $this->Crud_model->GetData('purchase_order_details','',"purchase_order_id='".$purchase_order_id."' and status!='Received'");
            $count=count($PurchaseOrders); 
            if($count == 0){
              $this->Crud_model->SaveData('purchase_orders',array('status' => 'Received','total_amount'=>$_POST['total_amount'],), "id='".$purchase_order_id."'");
            }
            
            /*Mail Start*/
            $details = $this->Purchase_orders_model->GetPoReceivedMail("pr.id='".$LastReceivedId."'");
            $vendor = $this->Crud_model->GetData('vendors','',"id='".$vendor_id."'",'','','','single');
            $table = '<table cellspacing="0px" cellpadding="5px" align="center" border="1" width="100%" style="font-size:12px"><thead><tr><th>Sr. No</th><th>Asset Type</th><th>Asset</th><th>Unit</th><th>Quantity</th></thead><tbody>';
            $sr=1;
            foreach($details as $row){
            $table .= '<tr><td align="center">'.$sr.'</td>';
            $table .= '<td align="center">'.ucfirst($row->ast_type).'</td>';
            $table .= '<td align="center">'.ucfirst($row->asset_name).'</td>';
            $table .= '<td align="center">'.$row->unit.'</td>';
            $table .= '<td align="center">'.$row->received_qty.'</td></tr>';
            $sr++;   }
            $table .= '</tbody></table>';
            //print_r($table);exit;
            $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='purchase_order_receive'",'','','','single');
            $mail_body->mail_body=str_replace("{vendor_name}",ucfirst($vendor->shop_name),$mail_body->mail_body);
            $mail_body->mail_body=str_replace("{no_item_count}",$countLoop,$mail_body->mail_body);
            $mail_body->mail_body=str_replace("{PO_number}",$details[0]->order_number,$mail_body->mail_body);
            $mail_body->mail_subject=str_replace("{PO_number}",$details[0]->order_number,$mail_body->mail_subject);
            $mail_body->mail_body=str_replace("{PO_date}",date('d-m-Y',strtotime($details[0]->purchase_date)),$mail_body->mail_body);
            $mail_body->mail_body=str_replace("{payable_amount}",$details[0]->total_amount,$mail_body->mail_body);
            $mail_body->mail_body=str_replace("{table}",$table,$mail_body->mail_body);

            $subject=$mail_body->mail_subject;
            $body=$mail_body->mail_body;
            $MailData = array('mailoutbox_to'=>$vendor->email,'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body->type);
            //$Send=$this->SendMail->Send($MailData);
            /*Mail Ends*/
           
            $this->session->set_flashdata('message', '<span class="label label-success">Assets has been received successfully.</span>');
            redirect(site_url('Purchase_orders/read/'.$purchase_order_id));
    }

    function GetDistributor()
    {
        $distributor = $this->Crud_model->GetData('warehouse_distribution_center','',"warehouse_id='".$_POST['id']."' and status='Active'");
            $response = '<option value="">-- Select Distribution Centre --</option>';
        foreach($distributor as $row){
            $response .= '<option value="'.$row->id.'">'.$row->distrubution_name.'</option>';
        }
        echo $response; exit;
    }

   /* function GetCategory()
    {
        $map = $this->Purchase_orders_model->GetPrdCategory("map.vendor_id='".$_POST['id']."'");
        $response = '<option value="">-- Select Category --</option>';
        foreach($map as $row){
            $response .= '<option value="'.$row->prdcat_id.'">'.$row->prdcat_name.'</option>';            
        }
        echo $response; exit;
    }*/

    /*function GetSubcategory()
    {
        $subcat = $this->Crud_model->GetData('prd_subcategories','',"prd_category_id='".$_POST['id']."'");
        $response = '<option value="">-- Select Subcategory --</option>';
        foreach ($subcat as $row) {
           $response .= '<option value="'.$row->prdsubcat_id.'">'.$row->prdsubcat_name.'</option>';            
        }
        echo $response; exit;
    }*/

    function GetProduct()
    {
        $product = $this->Crud_model->GetData('products','',"prd_subcategory_id='".$_POST['id']."'");
        $response = '<option value="">-- Select Product --</option>';
        foreach ($product as $row) {
          $brand = $this->Crud_model->GetData('brands','',"id='".$row->brand_id."'",'','','','single');
           $response .= '<option value="'.$row->prd_id.'">'.$row->prd_name.'-'.$brand->brand_name.'</option>';            
        }
        echo $response; exit;
    }

    function GetVariant()
    {
        $prd = $this->Crud_model->GetData('products','',"prd_id='".$_POST['id']."'",'','','','single');
        $variant = $this->Crud_model->GetData('weight_price','',"product_id='".$_POST['id']."'");
        $response = '<option value="">-- Select Variant --</option>';        
        foreach ($variant as $row) {
           $response .= '<option value="'.$row->id.'">'.$row->weight.'</option>';            
        }
        $product = $this->Crud_model->GetData('products','',"prd_id='".$_POST['id']."'",'','','','single');
        if($product->product_type == 'Branded')
        {
          $response = $response;
        } else {
          $response = '<option value="0">-- No Variant --</option>';
        }

        $returnData['response'] = $response;
        $returnData['unit'] = $prd->product_unit;
        echo json_encode($returnData); exit;
    }

    public function LotTracking($id)
    {
        $po = $this->Purchase_orders_model->GetPurchaseorders("p.id='".$id."'"); 
        if(empty($po)){ redirect(site_url('Purchase_orders')); }
        $prd = $this->Crud_model->GetData('purchase_received','',"purchase_order_id='".$id."'");
        $data = array(
            'id'=>$id,
            'po'=>$po,
            'prd'=>$prd,
            );
        $this->layouts->view('purchase_orders/lotTracking',$data); 
    } 

    public function purchase_detail($pid)
    {
         $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class=""><a href="'.site_url('Purchase_orders/index').'">Manage Purchase Orders</a></li>
            <li class="active">Purchase Order details</li>
            </ul>';
        $po = $this->Purchase_orders_model->GetPOData("po.id='".$pid."'");
        $purchaseReceived = $this->Purchase_orders_model->GetBillTransport("pr.purchase_order_id='".$pid."' and vm.purchase_order_id='".$pid."'");
        //print_r($this->db->last_query());exit;
        $data = array('heading'=>'Purchase Order details','id'=>$pid,'po'=>$po,'purchaseReceived'=>$purchaseReceived,'breadcrumbs'=>$breadcrumbs);
        $this->load->view('purchase_orders/purchase_details',$data);
    }

    public function print_lot($id)
    {
        $lot = $this->Purchase_orders_model->GetPrintLot("prd.purchase_received_id='".$id."'");
        //print_r($this->db->last_query());exit;
        if(empty($lot)){
            redirect('Purchase_orders/index');
        }
        $data = array('lot'=>$lot);
        $this->load->view('purchase_orders/print_lot',$data);
    }

    public function LotTrackingDetails($id,$recid)
    {
        $po = $this->Purchase_orders_model->GetPurchaseorders("p.id='".$id."'"); 
        if(empty($po)){ redirect(site_url('Purchase_orders')); }
        $prec = $this->Crud_model->GetData('purchase_received','',"purchase_order_id='".$id."'",'','','','single');
        $prd = $this->Crud_model->GetData('purchase_received_details','',"purchase_order_id='".$id."' and purchase_received_id='".$recid."'");
        $data = array(
            'id'=>$id,
            'po'=>$po,
            'prd'=>$prd,
            'prec'=>$prec,
            );
        $this->layouts->view('purchase_orders/lotTrackingdetails',$data); 
    } 
    /* ----- Implemented by shubham chandrhas ----- */
         /* ----- Export functionality start ----- */
    public function export()
    {
        //print_r($_POST);exit;
        $todate = $this->input->post('to-date');
        $fromdate = $this->input->post('from-date');
        $vendor_id = $this->input->post('vendor_id');
        $quotation_id = $this->input->post('quotation_id');
        //$distributor_id = $this->input->post('distributor_id');
        
        $con = "po.id<>0";
        if($todate != '')
        {
            $con .= " and po.purchase_date <='".date('Y-m-d',strtotime($todate))."'";
        }
        if($fromdate != '')
        {
            $con .= " and po.purchase_date >='".date('Y-m-d',strtotime($fromdate))."'";
        }
        if($vendor_id != '')
        {
            $con .= " and po.vendor_id= '".$vendor_id."'";
        }
        if($quotation_id != '')
        {
            $con .= " and po.quotation_id= '".$quotation_id."'";
        }
        /*if($distributor_id != '')
        {
            $con .= " and po.distributor_id= '".$distributor_id."'";
        }*/

            $results = $this->Purchase_orders_model->ExportCSV($con);
        
            $FileTitle='Purchase Order Report';
                
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Report Sheet');
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', 'Purchase Order Details ');
            $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
            $this->excel->getActiveSheet()->setCellValue('C3', 'Vendor Name');
            $this->excel->getActiveSheet()->setCellValue('B3', 'Quotation No');
            $this->excel->getActiveSheet()->setCellValue('D3', 'Order Number');
            $this->excel->getActiveSheet()->setCellValue('E3', 'Total Item');
            $this->excel->getActiveSheet()->setCellValue('F3', 'Purchase Date');
            $this->excel->getActiveSheet()->setCellValue('G3', 'Status');
            $this->excel->getActiveSheet()->setCellValue('H3', 'Total Amount');
            
            $a='4'; $sr = 1;    
            //print_r($results);exit;
            foreach ($results as $result) 
            {       
                
                $total = $result->sum + $result->transport ;
                $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
                $total = $total - $returnAmt->return_amount;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
                $this->excel->getActiveSheet()->setCellValue('C'.$a, $result->shop_name);
                $this->excel->getActiveSheet()->setCellValue('B'.$a, $result->quotation_no);
                $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->order_number);                
                $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->total_items);
                $this->excel->getActiveSheet()->setCellValue('F'.$a, date('d-m-Y',strtotime($result->purchase_date)));
                $this->excel->getActiveSheet()->setCellValue('G'.$a, $result->status);
                $this->excel->getActiveSheet()->setCellValue('H'.$a, $total);
                $sr++; $a++;                
            }
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);                
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $filename=''.$FileTitle.'.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            ob_clean();
                        
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');   
    }
        /* ----- Export functionality end ----- */

    public function GetAssets()
    {
        $vendor_id = $this->input->post('id');
        $asset_types = $this->Crud_model->GetData('mst_asset_types','',"id in (select asset_type_id from vendor_asset_type_map where vendor_id='".$vendor_id."')");
        $response = '<option value="">Select Asset Type</option>';
        foreach($asset_types as $row){
            $response .= '<option value="'.$row->id.'">'.$row->type.'</option>';
        }
        echo $response; exit;
    }

   public function getVendors()
    {
        $asset_type_id = $this->input->post('id');
        $vendors = $this->Crud_model->GetData('vendors','',"id in (select vendor_id from vendor_asset_type_map where asset_type_id='".$asset_type_id."')");
        $response = '<option value="">Select Vendor</option>';
        foreach($vendors as $row){
            $response .= '<option value="'.$row->id.'">'.$row->shop_name.'</option>';
        }
        echo $response; exit;
    } 

      public function getCategory()
    {
        $asset_type_id = $this->input->post('id');
        $vendor_id = $this->input->post('vendor_id');


        $categories = $this->Purchase_orders_model->getCategoryData("vat.vendor_id='".$vendor_id."' and c.status='Active'");



        $response['category'] = '<option value="">Select Category</option>';
        foreach($categories as $row){
            $response['category'] .= '<option value="'.$row->id.'">'.$row->title.'</option>';
        }

        $getQuotation=$this->Crud_model->GetData('quotations','',"id in (select quotation_id from quotation_details where vendor_id ='".$vendor_id."' and status='Approved') and id not in (select quotation_id from purchase_orders where vendor_id='".$vendor_id."')");
        //print_r($this->db->last_query());

        $response['quotation'] = '<option value="">Select Quotation</option>';
        foreach($getQuotation as $key){
            $response['quotation'] .= '<option value="'.$key->id.'">'.$key->quotation_no.'</option>';
        }
        //print_r($response);exit;


        echo json_encode($response); exit;
    }

  public function getQuotedata(){
    $quotation_id = $this->input->post('id');
    $vendor_id = $this->input->post('vendor_id');
    $asset_type_id = $this->input->post('asset_type_id');
    $getQuotation=$this->Purchase_orders_model->getQuotedata("vendor_id ='".$vendor_id."' and quotation_id='".$quotation_id."' and quotation_id not in (select quotation_id from purchase_orders where vendor_id='".$vendor_id."')");
    //print_r($getQuotation);exit;
    $html='<table class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th width="20%"><center>Category</center></th>
                  <th width="20%"><center>Subcategory</center></th>
                  <th width="25%"><center>Asset</center></th>
                  <th width="15%"><center>Quantity</center></th>
                  <th width="15%"><center>Unit</center></th>
                </tr>
            </thead>
            <tbody>';
                foreach ($getQuotation as $key) {
                    $html.='<tr>
                        <td><input type="text" readonly class="form-control" value="'.ucfirst($key->title).'">
                        <input type="hidden" readonly name="qcat_id[]" value="'.ucfirst($key->cat_id).'"> </td>
                        <td><input type="text" readonly class="form-control" value="'.ucfirst($key->sub_cat_title).'">
                        <input type="hidden" readonly name="qsub_cat_id[]" value="'.$key->subcat_id.'"></td>
                        <td><input type="text" readonly class="form-control" value="'.ucfirst($key->asset_name).'">
                        <input type="hidden" readonly name="qasset_id[]" value="'.$key->asset_id.'"></td>
                        <input type="hidden" readonly name="qasset_type_id[]" value="'.$key->asset_type_id.'"></td>
                        <td><input type="text" readonly class="form-control" name="qquantity[]" value="'.$key->quantity.'"> </td>
                        <td><input type="text" readonly class="form-control" value="'.$key->unit.'">
                        <input type="hidden" readonly name="qunit_id[]" value="'.$key->unit_id.'"> </td>

                    </tr>';
                }
    $html.='</tbody>
    </table>';

 echo $html; exit;

  }

  public function GetSubcategory()
    {
        $cat_id = $this->input->post('id');
        $asset_type_id = $this->input->post('asset_type_id');
        $subcategoryies= $this->Crud_model->GetData('sub_categories','',"id in (select subcategory_id from assets where category_id='".$cat_id."')");
        $response = '<option value="">Select Subcategory</option>';
        foreach($subcategoryies as $row){
            $response .= '<option value="'.$row->id.'">'.$row->sub_cat_title.'</option>';
        }
        echo $response; exit;
    }   
     public function Getassetdata()
    {
        $subcat_id = $this->input->post('sub_cat');
        $asset_type_id = $this->input->post('asset_type_id');
        $assets= $this->Crud_model->GetData('assets','',"subcategory_id='".$subcat_id."'");
        $response = '<option value="">Select Asset</option>';
        foreach($assets as $row){
            $brand=$this->Crud_model->GetData('brands','',"id = '".$row->brand_id."'",'','','','single');

            $response .= '<option value="'.$row->id.'">'.ucfirst($row->asset_name).' ('.ucfirst($brand->brand_name).')</option>';
        }
        echo $response; exit;
    } 
    public function Getassetunit()
    {
        $asset_id = $this->input->post('asset_id');
        $assets= $this->Crud_model->GetData('assets','',"id='".$asset_id."'");
        $response['unitid'] =$assets[0]->unit_id;
        $unitVal= $this->Crud_model->GetData('unit_types','',"id='".$assets[0]->unit_id."'");
        $response['val'] = '';//$unitVal[0]->unit;
        //print_r(json_encode($response));exit;
        echo json_encode($response); exit;
    }

    public function DeletePoRow()
    {
        $row = $this->Crud_model->GetData('purchase_order_details','',"id='".$_POST['id']."'",'','','','1');
        $pod = $this->Crud_model->GetData('purchase_order_details','',"purchase_order_id='".$row->purchase_order_id."'");
        if(count($pod)=='1')
        {
            $this->Crud_model->DeleteData('purchase_orders',"id='".$row->purchase_order_id."'");
            echo '1';
        } else {
            echo '0';
        }
        $this->Crud_model->DeleteData('purchase_order_details',"id='".$_POST['id']."'");
        $this->chkpo($row->purchase_order_id);
    } 

    function chkpo($poid)
    {
        $row = $this->Crud_model->GetData('purchase_orders','',"id='".$poid."'",'','','','1');
        if(!empty($row))
        {
            $r = $this->Crud_model->GetData('purchase_order_details','',"purchase_order_id='".$poid."' and status='Pending'");
            if(empty($r))
            $this->Crud_model->SaveData('purchase_orders',array('status'=>'Received'),"id='".$poid."'");
        }
    }

    public function EditPoRow()
    {
        //print_r($_POST);exit;
        $row = $this->Crud_model->GetData('purchase_order_details','',"id='".$_POST['id']."'",'','','','1');
        $asset_types = $this->Crud_model->GetData('mst_asset_types','',"id in (select asset_type_id from vendor_asset_type_map where vendor_id='".$row->vendor_id."')");
        $cat= $this->Purchase_orders_model->getCategoryData("vat.vendor_id='".$row->vendor_id."' and c.status='Active'");
        //$cat = $this->Crud_model->GetData("categories",'',"id in (select category_id from assets where asset_type_id='".$row->asset_type_id."')");
        $subcat = $this->Crud_model->GetData('sub_categories','',"category_id='".$row->cat_id."'");
        $assets = $this->Crud_model->GetData('assets','',"subcategory_id='".$row->subcat_id."' and asset_type_id='".$row->asset_type_id."'");
        $units = $this->Crud_model->GetData('unit_types','',"id='".$row->unit_id."'");
        $action = site_url('Purchase_orders/UpdatePoRow/'.$_POST['id']);
        $assetid=array();
        $getAllData=$this->Crud_model->GetData('purchase_order_details','asset_id',"purchase_order_id='".$row->purchase_order_id."' and id!='".$_POST['id']."'",'','','','');
        foreach($getAllData as $ast){
            $assetid[]=$ast->asset_id;
        }
        $implodeastid=implode(',', $assetid);
        //print_r($implodeastid);exit;
        $html = '<form method="post" action="'.$action.'">
        <input type="hidden" name="asset_type_id" id="editasset_type_id" value="'.$row->asset_type_id.'">
        <input type="hidden" value="'.$implodeastid.'" id="astIds">
          <div class="form-group">            
            <label>Category</label> &nbsp;<span id="ecaterr">&nbsp;</span> 
           
            <select class="form-control" name="cat_id" id="cat_id" onchange="GetSubcategory(this.value)">
            <option value="">--Select Category -- </option>';
            foreach($cat as $c){ if($c->id==$row->cat_id){ $se='selected'; } else { $se=''; }
        $html .= '<option value="'.$c->id.'" '.$se.'>'.$c->title.'</option>';
            }
        $html .= '</select>
          </div>
          <div class="form-group">            
            <label>Subcategory</label> &nbsp;<span id="esuberr">&nbsp;</span>
            <select class="form-control" name="subcat_id" id="subcat_id" onchange="Getassetdata(this.value)">
            <option value="">--Select Subcategory-- </option>';
            foreach($subcat as $s){ if($s->id==$row->subcat_id){ $se='selected'; } else { $se=''; }
        $html .= '<option value="'.$s->id.'" '.$se.'>'.$s->sub_cat_title.'</option>';
            }
        $html .= '</select>
          </div>
          <div class="form-group">            
            <label>Asset</label> &nbsp;<span id="easterr">&nbsp;</span>    
            <select class="form-control" name="asset_id" id="asset_id"  onchange="Getassetunit(this.value)" >
            <option value="">--Select  Asset-- </option>';
            foreach($assets as $p){ if($p->id==$row->asset_id){ $se='selected'; } else { $se=''; }
          
        $html .= '<option value="'.$p->id.'" '.$se.'>'.$p->asset_name.'</option>';
            }
        $html .= '</select>
          </div>
            <div class="form-group">            
            <label>Quantity</label> &nbsp;<span id="eqtyerr">&nbsp;</span>    
            <input type="text" class="form-control" name="quantity" id="eqty" value="'.$row->quantity.'">
          </div>
          <div class="form-group">            
            <label>Unit</label>    
           <input type="text" readonly="" placeholder="Unit"  value="'.$units[0]->unit.'"  id="unit_val" class="form-control unit_val">
           <input type="hidden" value='.$row->unit_id.' placeholder="Unit" name="unit_id" id="unit_id" class="form-control unit_id">
          </div>
          </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-success" onclick="return EditValidate();">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';

        echo $html; exit;
    }

    public function UpdatePoRow($id)
    {
        $row = $this->Crud_model->GetData('purchase_order_details','',"id='".$id."'",'','','','1');
        $data = array(
                'cat_id' => $_POST['cat_id'],
                'subcat_id' => $_POST['subcat_id'],
                'asset_id' => $_POST['asset_id'],
                'unit_id' => $_POST['unit_id'],
                'quantity' => $_POST['quantity'],
               
            );
        $this->Crud_model->SaveData('purchase_order_details',$data,"id='".$id."'");
        redirect('Purchase_orders/read/'.$row->purchase_order_id);
    }

    public function image_update()
    {
        if($_FILES['bill_copy']['error']=='0')
            { 
                $file_element_name = 'bill_copy';
                /*set file parameters*/
                $config['upload_path'] = getcwd().'/uploads/bill_copy';
                $config['allowed_types'] = '*';//pdf|jpeg|doc|xls|xml|jpg|png|txt
                $config['max_size'] = 1024 * 8;
                $config['encrypt_name'] = False;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($file_element_name))
                {
                    $error = array('error' => $this->upload->display_errors('<p>', '</p>'));
                   print_r($error);exit();
                }
                else
                {
                    $fileUploaded = $this->upload->data();
            //        $_POST['file_name'] =$data['file_name'];
                    //print_r($fileUploaded);echo '<br/>';
                     //print_r($fileUploaded['file_name']);exit();
                }
            }
        $receivedData = array('bill_copy'=>$fileUploaded['file_name']);
        //print_r($receivedData);exit;
        $cond = "id='".$_POST['purchase_received_id']."'";
        $this->Crud_model->SaveData('purchase_received',$receivedData,$cond);
        redirect('Purchase_orders/purchase_detail/'.$_POST['pid']);
    }

    function barcode_view($id,$asset_id,$asset_type_id)
    {
        $purchase_received_details = $this->Crud_model->GetData('purchase_received_details','',"purchase_order_detail_id = '".$id."' and asset_id = '".$asset_id."' and asset_type_id = '".$asset_type_id."' ",'','','','single');

        $po = $this->Purchase_orders_model->GetPurchaseorders("p.id='".$purchase_received_details->purchase_order_id."'"); 

        $barcodeData = $this->Crud_model->GetData('purchase_received_detail_barcode','',"purchase_order_id = '".$purchase_received_details->purchase_order_id."' and asset_id = '".$asset_id."' and is_returned='No' and is_replaced='No'",'','id desc','','');
        //print_r($purchase_received_details);exit;
        if(empty($po))
            { 
                redirect(site_url('Purchase_orders')); 
            }
        $pod = $this->Purchase_orders_model->GetPurchaseorderdetails_single("pod.purchase_order_id = '".$purchase_received_details->purchase_order_id."' and pod.asset_id = '".$asset_id."' and pod.id='".$purchase_received_details->purchase_order_detail_id."'"); 

        //print_r($pod);exit;
         $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class=""><a href="'.site_url('Purchase_orders/index').'">Manage Purchase Orders</a></li>
            <li class="active">Barcode View</li>
            </ul>';

        $instockProduct= count($this->Crud_model->GetData('purchase_received_detail_barcode','',"purchase_order_id = '".$purchase_received_details->purchase_order_id."' and asset_id = '".$asset_id."' and is_received='Yes'  and is_returned='No' and is_replaced='No'",'','id desc','',''));
        $inreturnProduct=count($this->Crud_model->GetData('purchase_received_detail_barcode','',"purchase_order_id = '".$purchase_received_details->purchase_order_id."' and asset_id = '".$asset_id."' and is_returned='Yes' and is_replaced='No'",'','id desc','',''));
        $inreplaceProduct=count($this->Crud_model->GetData('purchase_received_detail_barcode','',"purchase_order_id = '".$purchase_received_details->purchase_order_id."' and asset_id = '".$asset_id."' and parent_id!='0'",'','id desc','',''));

        $data = array(
            'id'=>$purchase_received_details->purchase_order_id,
            'purchase_detail_id'=>$id,
            'asset_id'=>$asset_id,
            'asset_type_id'=>$asset_type_id,
            'breadcrumbs'=>$breadcrumbs,
            'po'=>$po,
            'barcodeData'=>$barcodeData,
            'pod'=>$pod,
            'per_unit_price'=>$purchase_received_details->per_unit_price,
            'instockProduct'=>$instockProduct,
            'inreturnProduct'=>$inreturnProduct,
            'inreplaceProduct'=>$inreplaceProduct,
            'addaction'=>site_url('Purchase_orders/addTostock/'.$purchase_received_details->purchase_order_id.'/'.$asset_id.'/'.$asset_type_id),
            );
         //print_r($data);exit;
         $this->load->view('purchase_orders/barcode_view',$data);
    }

    public function return_replaceAssets($flag,$pid,$purchase_received_detail_id,$asset_id,$asset_type_id){

         $backAction=site_url('Purchase_orders/barcode_view/'.$purchase_received_detail_id.'/'.$asset_id.'/'.$asset_type_id );
         $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li class=""><a href="'.site_url('Purchase_orders/index').'">Manage Purchase Orders</a></li>
            <li class=""><a href="'.$backAction.'">Barcode View</a></li>
            <li class="active">Assets '.$flag.'</li>
            </ul>';

        $po = $this->Purchase_orders_model->GetPurchaseorders("p.id='".$pid."'"); 
        //print_r($po);exit;
        if($flag=='return'){

        $assetsBarcode=$this->Purchase_orders_model->getReturnReplaceData("pb.purchase_order_id = '".$pid."' and pb.asset_id = '".$asset_id."' and pb.is_returned='Yes' and pb.is_replaced='No'");
        }
        else{

        $assetsBarcode=$this->Purchase_orders_model->getReturnReplaceData("pb.purchase_order_id = '".$pid."' and pb.asset_id = '".$asset_id."' and pb.parent_id!='0'");
        }
        //print_r($this->db->last_query());exit;
        //print_r($po);exit;
       
        $data=array(
            'breadcrumbs'=>$breadcrumbs,
            'po'=>$po,
            'assetsBarcode'=>$assetsBarcode,
            'flag'=>$flag,
            'purchase_received_detail_id'=>$purchase_received_detail_id,
            'asset_id'=>$asset_id,
            'asset_type_id'=>$asset_type_id,
            'backAction'=>$backAction

        );
        $this->load->view('purchase_orders/returnreplaceList',$data);

    }

    function barcode_scan()
    {
         $data = array(
            'bread'=>'Barcode Scan',
                        );
        $this->layouts->view('purchase_orders/barcode_scan',$data);
    } 

    public function addTostock($id,$asset_id,$asset_type_id)
     {
        
       if($_POST['barcode_number']!=''){
            $row = $this->Crud_model->GetData('purchase_received_detail_barcode','','barcode_number="'.$_POST['barcode_number'].'"','','','','1');
            $assetType = $this->Crud_model->GetData('mst_asset_types','','id="'.$row->asset_type_id.'"','','','','1');
            $con = 'a.id="'.$row->asset_id.'"'; 
            $getData = $this->Purchase_orders_model->GetBarcodeDetails($con);
            $po = $this->Purchase_orders_model->GetPurchaseorders("p.id='".$row->purchase_order_id."'");
            //print_r($row); exit;

          $asset_type_id=$row->asset_type_id;
          $asset_id=$_POST['asset_id'];
          $received_qty=$row->quantity;
          $branch_id=$_POST['branch_id'];
          $purchase_detail_id=$_POST['purchase_detail_id'];
          $purchase_received_detail_id=$row->purchase_received_detail_id;
          $purchase_received_detail_barcode_id=$row->id;

          $purchase_received_detail_barcode = $this->Crud_model->GetData('purchase_received_detail_barcode','',"id='".$purchase_received_detail_barcode_id."'",'','','','row');
          if($purchase_received_detail_barcode->is_received=='Yes')
          {
            $this->session->set_flashdata('message', '<span class="label label-danger">Asset already added into stock.</span>');
            redirect(site_url('Purchase_orders/barcode_view/'.$purchase_detail_id.'/'.$asset_id.'/'.$asset_type_id));
          }
          else
          {

            $financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
            $Getasset = $this->Crud_model->GetData('assets','',"id = '".$asset_id."'",'','','','single');
            $Getpurchase_received_details = $this->Crud_model->GetData('purchase_received_details','',"id = '".$purchase_received_detail_id."'",'','','','single');

            /*Add to stock*/
            	 $stockData = array(

                'asset_id' => $asset_id,
                'barcode_number' => $purchase_received_detail_barcode->barcode_number,
                'barcode_image'=>$purchase_received_detail_barcode->barcode_image,
                'quantity'=>$received_qty,
                'price'=>$Getpurchase_received_details->per_unit_price,
                'type'=>'New',
                'created'=>date('Y-m-d H:i:s'),

            );
               $this->Crud_model->SaveData('asset_details', $stockData);


            /*Add to stock*/

            /*Update Assets*/
            $amt=$Getpurchase_received_details->per_unit_mrp-$Getpurchase_received_details->per_unit_price; 
            $dis=$amt*100;
            $discountamt= $dis/$Getpurchase_received_details->per_unit_mrp;
            $quantity=$Getasset->quantity+$received_qty;
            $UpdateData = array(
                'received_date'=>date('Y-m-d'),
                'cgst_asset'=>$Getpurchase_received_details->cgst,
                'sgst_asset'=>$Getpurchase_received_details->sgst,
                'final_amount'=>$Getpurchase_received_details->per_unit_price,
                'product_mrp'=>$Getpurchase_received_details->per_unit_mrp,
                'discount_amount'=>$discountamt,
                'quantity'=>$quantity,
                'modified'=>date('Y-m-d H:i:s'),

            );
            $this->Crud_model->SaveData('assets',$UpdateData,"id='".$asset_id."'");
           	
            /*Update Assets*/

            if(!empty($_POST['branch_id']))
            {

               $Getbranchasset = $this->Crud_model->GetData('asset_branch_mappings','',"asset_id = '".$asset_id."' and branch_id='".$_POST['branch_id']."'",'','','','single');
               if(!empty($Getbranchasset)){
                $quantity1=$Getbranchasset->asset_quantity+$received_qty;
                $UpdateData1 = array(
                   'asset_quantity'=> $quantity1,
                   'modified'=>date('Y-m-d H:i:s'),
               );
                $this->Crud_model->SaveData('asset_branch_mappings',$UpdateData1,"asset_id = '".$asset_id."' and branch_id='".$_POST['branch_id']."'");
            }else{
               $mapData = array(

                'asset_id' => $asset_id,
                'branch_id' => $branch_id,
                'asset_quantity'=>$received_qty,
                'created'=>date('Y-m-d H:i:s'),

            );
               $this->Crud_model->SaveData('asset_branch_mappings', $mapData); 
           }

       }
       $Getasset = $this->Crud_model->GetData('assets','',"id = '".$asset_id."'",'','','','single');
       if(!empty($_POST['branch_id'])){

          $Getasset = $this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as quantity","asset_id = '".$asset_id."' and branch_id='".$branch_id."'");
          $Getasset=$Getasset[0];
          $avlquantity= $Getasset->quantity;
      }else
      {
           $getMappingQtyfasset=$this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as quantity","asset_id = '".$asset_id."'");
           if($getMappingQtyfasset[0]->quantity!='0'){ $avlquantity= $Getasset->quantity - $getMappingQtyfasset[0]->quantity; }
           else{ $avlquantity= $Getasset->quantity;  }
      }

        $stkLogData = array(
            'financial_year_id'=>$financialYear->id,
            'asset_id' => $asset_id,
            'branch_id' => $_POST['branch_id'],
            'quotation_id' => $_POST['quotation_id'],
            'asset_type_id' => $asset_type_id,
            'received_qty' => $received_qty,
            'available_quantity' => $avlquantity,
            'per_unit_price'=>$Getpurchase_received_details->per_unit_price,
            'per_unit_mrp'=>$Getpurchase_received_details->per_unit_mrp,
            'total_amount'=>$Getpurchase_received_details->per_unit_price,
            'price'=>$Getpurchase_received_details->per_unit_price,
            'description' => "Purchase against Purchase order no =".$_POST['order_number']." of quantity ".$received_qty."",
            'type' => 'Received',
            'is_barcode' => 'Yes',
            'date' => date('Y-m-d'),
            'created_by' => $_SESSION[SESSION_NAME]['id'],
            'created'=>date('Y-m-d H:i:s'),
            'modified'=>date('Y-m-d H:i:s')
        );
        $this->Crud_model->SaveData('stock_logs', $stkLogData); 
        $UpdateData = array(
            'is_received'=>'Yes',
            'modified'=>date('Y-m-d H:i:s'),

        );
        $this->Crud_model->SaveData('purchase_received_detail_barcode',$UpdateData,"id='".$purchase_received_detail_barcode_id."'");


        $this->session->set_flashdata('message', '<span class="label label-success">Asset has been received successfully.</span>');
        redirect(site_url('Purchase_orders/barcode_view/'.$purchase_detail_id.'/'.$asset_id.'/'.$asset_type_id)); 
        } 
        }else{
              $this->session->set_flashdata('message', '<span class="label label-danger">Something went wrong,Please try again.</span>');
            redirect(site_url('Purchase_orders/barcode_view/'.$purchase_detail_id.'/'.$asset_id.'/'.$asset_type_id)); 
        }   
    }

    function getRows($count){

        //print_r($_POST);exit;
         $vendor_id = $this->input->post('id');
         $categories= $this->Purchase_orders_model->getCategoryData("vat.vendor_id='".$vendor_id."' and c.status='Active'");
        //$categories = $this->Crud_model->GetData('categories','',"id in (select category_id from assets where asset_type_id='".$asset_type_id."') and status='Active'");
       // print_r($this->db->last_query());exit;
        $data = array(
                    
                    'category'=>$categories,
                    'count'=>$count,
                
                );
        
        $this->load->view('purchase_orders/porow',$data);  
    }

public function getBarcodes(){
    //print_r($_REQUEST);
    $barcodeData = $this->Crud_model->GetData('purchase_received_detail_barcode','',"purchase_order_id = '".$_REQUEST['id']."' and asset_id = '".$_REQUEST['asset_id']."' and is_returned='No' and is_replaced='No' and barcode_number like'".$_REQUEST['search']."%'",'','id desc','','');

    $json=array();

            foreach($barcodeData as $key){
                $json[]=array('label'=>$key->barcode_number);
            }
        
        echo json_encode($json);exit;   
}

  public function print_barcode($id,$asset_id,$astName)
  {
    $barcodeData = $this->Crud_model->GetData('purchase_received_detail_barcode','',"purchase_order_id = '".$id."' and asset_id = '".$asset_id."' and is_returned='No' and is_replaced='No'",'','id desc','','');

    $data = array(
                'asset_details' => $barcodeData,
                'astName' => str_replace('_', " ", $astName),
                  );

    $this->load->view('purchase_orders/barcode_print',$data);
  }

}
?>