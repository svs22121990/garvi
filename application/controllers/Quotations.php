<?php
/* all controller functionality developed by Prashant */
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotations extends CI_Controller {

    function __construct()
    {
        parent::__construct();      
        $this->load->database();
        $this->load->library(array('session','form_validation'));
        $this->load->model(array('Crud_model','Quotations_model'));   
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->library(array('Urlcheck'));
        $this->Urlcheck = new Urlcheck();  
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
                  if($menu->value=='Quotations')
                  { 
                    
                    if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                  }
              }
            }
        $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li class='active'>Manage Quotations</li>
                        </ul>";
        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '6' ,
        'ajax_manage_page' => site_url('Quotations/ajax_manage_page') ,
        'button' => 'Create',
        'heading' => 'Manage Quotations',
        'addPermission'=>$add
        );
        //print_r($data);exit;
        $this->load->view('quotations/quotations_list',$data);
        }
    else
    {
      redirect('Dashboard');
    }  
    }

    public function ajax_manage_page()
    {
      $view  = '';
      $actstatus = '';
      $getData = $this->Quotations_model->get_datatables();
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Quotations')
              { 
                
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_view)){ $view='1'; }else{ $view='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
              }
          }
        }
        $data = array();
        if(empty($_POST['start']))
        {
            $no =0;   
        }
        else
        {
            $no =$_POST['start'];
        }
        //print_r($edit);exit;
        foreach ($getData as $Data) 
        {

            //print_r($Data);
            $btn='';
            
            if(!empty($edit)){
              if($Data->status=='Pending' && $Data->remain_qty!='0')
                {
                $btn ='<a href="'.site_url('Quotations/edit_quotation/'.$Data->id).'"  title="Edit Detail" class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-edit bigger-130"></i></a> | ';
                }
            }
            if(!empty($view)){
            $btn .='<a href="'.site_url('Quotations/view_quotation/'.base64_encode($Data->id)).'" title="View Detail" class="btn btn-primary btn-circle btn-sm"><i class="ace-icon fa fa-eye bigger-130"></i></a>';
            }
            $status ='';
             if(!empty($actstatus)){ 
            if($Data->status=='Approved')
            {
                  $status =  "<a class='label-success label'  > Approved </a>";            
            }
            else if($Data->status=='Pending')
            {
                 $status =  "<a href='#checkStatus' data-toggle='modal' class='label-primary label' onclick='checkStatus(".$Data->id.")'> Pending </a>";
            }
            else
            {
                  $status =  "<a  class='label-warning label'  > Rejected </a>";
            }
            }
            
            $no++;   
            $row = array();
            $row[] = $no;
            $row[] = $Data->quotation_no;
            $row[] = $Data->request_no;
            $row[] = $Data->assets_name;
            $row[] = $Data->vendor_name;
            $row[] = $Data->totalqty;
            $row[] = $Data->total_quantity;
            $row[] = $status;
            $row[] = $btn;
            $data[] = $row;
            $selected = '';
        }
 
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Quotations_model->count_all(),
          "recordsFiltered" => $this->Quotations_model->count_all(),
          "data" => $data,
        );
        echo json_encode($output);
    }

    public function create()
    {
        $nyr = date('y')+1;
        $no = 0;
        $no = ++$no;
        $quotationdata = $this->Crud_model->GetData("quotations",'',"","","id desc");
        if(!empty($quotationdata))
        {
          $cno = explode("/", $quotationdata[0]->quotation_no);
        }

        if(!empty($quotationdata[0]->quotation_no))
        {

            $quotation_no = 'Q/'.date('y').'-'.$nyr.'/000'.($cno[2]+1); 
        }
        else
        {
            $no = 0;
            $no = ++$no;
            $quotation_no = 'Q/'.date('y').'-'.$nyr.'/000'.$no; 
        }

        $con="qr.remain_qty > '0'";
        $quoterequests=$this->Quotations_model->Getreqno($con);        
        //print_r($this->db->last_query());exit;
        $assets = $this->Crud_model->GetData("mst_asset_types",'',"","","type asc");
        $vandors = $this->Crud_model->GetData("mst_users",'',"","","name asc");
       
        $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li><a href='".site_url('Quotations')."'>Manage Quotations</a></li>
                        <li class='active'>Create Quotation</li>
                        </ul>";
        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'quotation_no' => $quotation_no,
        'quoterequests' => $quoterequests,
        'assets' => $assets,
        'vandors' => $vandors,
        'button' => 'Create',
        'heading' => 'Create Quotations',
        'action' => site_url('Quotations/create_action'),
        );
        $this->load->view('quotations/create',$data);
    }

    function getAssets()
    {
        $getAssets = $this->Crud_model->GetData("assets",'',"category_id='".$_POST['cat_id']."' and subcategory_id='".$_POST['subcat_id']."' and asset_type_id='".$_POST['asset_type_id']."'");
        if(!empty($getAssets))
        {
            $response = '<option value="">Select Asset</option>';
            foreach($getAssets as $row)
            {
                 $response .= '<option value="'.$row->id.'">'.$row->asset_name.'</option>';
            }
        }
        else
        {
             $response = '<option value="">Select Asset</option>';
        }

        echo $response;
    }

    function getVendor()
    {
        /*$cond="vatm.asset_type_id='".$_POST['id']."'";
        $vendor_data=$this->Quotations_model->GetVendorData($cond);
        if(!empty($vendor_data))
          {
            $response='';
          foreach ($vendor_data as $row) 
          {
            $response .= '<option value="'.$row->vendor_id.'">'.ucfirst($row->name).'</option>';
          }
          }
          else
          {
            $response = '<option value="0">--Select Vendor--</option>';
          }
          
        $assets_data=$this->Crud_model->GetData('assets','id,asset_name',"asset_type_id='".$_POST['id']."'");
        $data=array(
            'assets_data'=>$assets_data,
        );
        $page=$this->load->view('quotations/assets_list',$data,true);
        $dataKey['response']= $response;
        $dataKey['page']=$page;*/

        //$cond="";
        $vendorData=$this->Crud_model->GetData('mst_users','id,name',"id in (select user_id from quotation_request_details where quotation_request_id='".$_POST['id']."'  and remaining_qty > '0' group by user_id)");

        if(!empty($vendorData))
          {
            $response='<label for="vendor" class="col-md-12">User<span style="color:red">*</span> <span id="error2" style="color:red"></span></label>';
          foreach ($vendorData as $row) 
          {
            $response .= '<div class="col-md-6"><input name="vendor_id" id="vendor_id_'.$row->id.'" class="select_vendor" type="checkbox" value="'.$row->id.'" onclick="getData(this.id,this.value)"> <span id="vendor_name_'.$row->id.'">'.ucfirst($row->name).'</span></div>';
          }
          }
          else
          {
            $response = '<p class="col-md-12">No users available</p>';
          }

          $dataKey['response']= $response;
          echo json_encode($dataKey);exit;

    }
    public function getDetailsData(){
            $GetVendorData=$this->Crud_model->GetData('quotation_request_details','',"quotation_request_id='".$_POST['quotation_request_id']."' and user_id='".$_POST['vendor_id']."' and remaining_qty > '0'",'user_id');
            $getRequestData=$this->Crud_model->GetData('quotation_requests','id,totalqty,remain_qty',"id='".$_POST['quotation_request_id']."'");

            $data=array(
                'detail_data'=>$GetVendorData,
                'user_id'=>$_POST['vendor_id'],
                'totalqty'=>$getRequestData[0]->totalqty,
                'remain_qty'=>$getRequestData[0]->remain_qty,
            );
             $this->load->view('quotations/request_details',$data);
    }

    public function create_action()
    { 
        //print_r($_POST);exit;
        $totalQty='';
        $vendors=count($_POST['vendor_id']);
        for($i=0;$i<$vendors;$i++)
        {
            $vid=$_POST['vendor_id'][$i];
            $quantity= $_POST[$vid.'_quantity'];
            $count=count($quantity);
            
            for($j=0; $j<$count; $j++)
            {
                $totalQty= $totalQty+$quantity[$j];
            }
        }


        $quotation_data=array(
            'quotation_no'=>$_POST['quotation_no'],
            'quotation_request_id'=>$_POST['quotation_request_id'],
            'status'=>'Pending',
            'total_quantity'=>$totalQty,
            'created_by'=>$_SESSION[SESSION_NAME]['id'],
            'created'=>date('Y-m-d h:i:s'),
        );
        $this->Crud_model->SaveData('quotations',$quotation_data);
        $quotation_id=$this->db->insert_id();
        
        $getqrequest=$this->Crud_model->GetData('quotation_requests','',"id='".$_POST['quotation_request_id']."'",'');
        $remainQtyr=$getqrequest[0]->remain_qty;
        $remaining=$remainQtyr-$_POST['totalQtyAll'];
        $data=array(

                           'remain_qty'=>$remainQtyr-$_POST['totalQtyAll'],
        );
        $this->Crud_model->SaveData("quotation_requests",$data,"id='".$_POST['quotation_request_id']."'");

        
        $vendors=count($_POST['vendor_id']);
        for($i=0;$i<$vendors;$i++)
        {
            $vendor_quote_copy='';
             $config = array(
            'upload_path'   => getcwd().'/uploads/quotationcopy/',
            'allowed_types' => '*',
            'overwrite'     => 0,
            'encrypt_name' => FALSE, 
            );
           
            $this->load->library('upload', $config);
            $vid=$_POST['vendor_id'][$i];
            $file=$_FILES[$vid.'_vendor_quote_copy']['name'];
            if($file!=''){
              $fileName = rand(0000,9999) .'_'.$file;
              $config['file_name'] = $fileName; 
              $this->upload->initialize($config);

              if($this->upload->do_upload($vid.'_vendor_quote_copy')) 
              { 
                $data = $this->upload->data();
                $vendor_quote_copy=$data['file_name'];
              }
              else
              {
                 $vendor_quote_copy='';
              }
            }
           //print_r( $vendor_quote_copy);exit;
            $vendor_quotation_no=$_POST[$vid.'_vendor_quotation_no'];


            $quantity= $_POST[$vid.'_quantity'];
            //print_r($quantity[0]);exit;
            $count=count($quantity);
            
            for($j=0; $j<$count; $j++)
            {
                if($_POST[$vid.'_quantity'][$j]!='')
                {
                    $quotation_details_data=array(
                        'quotation_id'=>$quotation_id,
                        'quotation_request_id'=>$_POST['quotation_request_id'],
                        'vendor_id'=>$vid,
                        'vendor_quote_copy'=>$vendor_quote_copy,
                        'vendor_quotation_no'=>$vendor_quotation_no,
                        'mrp'=>$_POST[$vid.'_mrp'][$j],
                        'per_unit_price'=>$_POST[$vid.'_per_unit_price'][$j],
                        'amount'=>$_POST[$vid.'_amount'][$j],
                        'asset_id'=>$_POST[$vid.'_asset_id'][$j],
                        'asset_type_id'=>$_POST[$vid.'_assets_type_id'][$j],
                        'quotation_request_detail_id'=>$_POST[$vid.'_quotation_request_detail_id'][$j],
                        'origin_qty'=>$_POST[$vid.'_originqty'][$j],
                        'quantity'=>$quantity[$j],
                        'approved_date'=>date('Y-m-d'),
                        'approved_by'=>$_SESSION[SESSION_NAME]['id'],
                        'status'=>'Pending',
                    );
                    $this->Crud_model->SaveData('quotation_details',$quotation_details_data);
                    $getqrdata=$this->Crud_model->GetData('quotation_request_details','',"id='".$_POST[$vid.'_quotation_request_detail_id'][$j]."'",'');
                    $remainQty=$getqrdata[0]->remaining_qty;
                    if($remainQty!=0){
                        $qty=$remainQty-$quantity[$j];
                        $data=array(

                           'remaining_qty'=>$remainQty-$quantity[$j],
                        );
                        $this->Crud_model->SaveData("quotation_request_details",$data,"id='".$_POST[$vid.'_quotation_request_detail_id'][$j]."'");
                        if($qty==0){
                            $data=array(

                           'status'=>'Approved',
                        );
                        $this->Crud_model->SaveData("quotation_request_details",$data,"id='".$_POST[$vid.'_quotation_request_detail_id'][$j]."'");
                        }

                    }else{
                        $data=array(

                           'status'=>'Approved',
                        );
                        $this->Crud_model->SaveData("quotation_request_details",$data,"id='".$_POST[$vid.'_quotation_request_detail_id'][$j]."'");
                    }

                }


            }
        }

        if($remaining==0){
                         $data=array(
                            'status'=>'Approved',
                            );
                        $this->Crud_model->SaveData("quotation_request_details",$data,"quotation_request_id='".$_POST['quotation_request_id']."'");
                        
        }

        $this->session->set_flashdata('message', '<span class="label label-success">Quotation Created successfully</span>');
        redirect('Quotations');
    }

    public function edit_quotation($id)
    {
         $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li><a href='".site_url('Quotations')."'>Manage Quotations</a></li>
                        <li class='active'>Update Quotation</li>
                        </ul>";
        $cond="q.id='".$id."'";
        $quotation_data=$this->Quotations_model->GetQuotationData($cond);
      // print_r($quotation_data);exit;
        $data=array(
            'breadcrumbs'=>$breadcrumbs,
            'quotation_data'=>$quotation_data
        );
        $this->load->view('quotations/quotation_edit',$data);
    }
    public function update_action(){
       // print_r($_FILES);echo "<br/>";
       // print_r($_POST);exit;

        $totalQty='';
        $totalOldQty='';
        $vendors=count($_POST['vendor_id']);
        for($i=0;$i<$vendors;$i++)
        {
            $vid=$_POST['vendor_id'][$i];
            $quantity= $_POST[$vid.'_quantity'];
            $quantityold= $_POST[$vid.'_quotation_request_detail_oldQty'];
            $qdid= $_POST[$vid.'_quotation_request_detail_id'];
            $count=count($quantity);
            $countDetails=count($qdid);
            
            for($j=0; $j<$count; $j++)
            {
                $totalQty= $totalQty+$quantity[$j];
                $totalOldQty= $totalOldQty+$quantityold[$j];
            }

            for($k=0; $k< $countDetails; $k++)
            {
                $getqrequestDetails=$this->Crud_model->GetData('quotation_request_details','remaining_qty',"id='".$qdid[$k]."'",'');
                $remainQtyrd=$getqrequestDetails[0]->remaining_qty;
                $remainingQrd=$remainQtyrd+$quantityold[$k];
                $data=array(
                    'remaining_qty'=>$remainingQrd,
                );
                $this->Crud_model->SaveData("quotation_request_details",$data,"id='".$qdid[$k]."'");


                $con="quotation_id='".$_POST['quotation_id']."' and quotation_request_id='".$_POST['quotation_request_id']."' and quotation_request_detail_id='".$qdid[$k]."' and vendor_id='".$vid."'";
                $this->Crud_model->DeleteData('quotation_details',$con);
                //print_r($this->db->last_query());exit();
            }


            if(isset($_POST[$vid.'_oldCopy'])){
                    unlink('uploads/quotationcopy/'.$_POST[$vid.'_oldCopy']); 
            }


        }

        //print_r($totalOldQty);exit;



        $quotation_data=array(
           
            'status'=>'Pending',
            'total_quantity'=>$totalQty,
            'modified'=>date('Y-m-d h:i:s'),
        );
        $this->Crud_model->SaveData('quotations',$quotation_data,"id='".$_POST['quotation_id']."'");
        $quotation_id=$_POST['quotation_id']; 
        
        $getqrequest=$this->Crud_model->GetData('quotation_requests','',"id='".$_POST['quotation_request_id']."'",'');
        $remainQtyr=$getqrequest[0]->remain_qty;
        $remaining=$remainQtyr-$_POST['totalQtyAll'];
        $data=array(
            'remain_qty'=>$remaining+$totalOldQty,
        );
        $this->Crud_model->SaveData("quotation_requests",$data,"id='".$_POST['quotation_request_id']."'");

        
        $vendors=count($_POST['vendor_id']);
        for($i=0;$i<$vendors;$i++)
        {
            $vendor_quote_copy='';
             $config = array(
            'upload_path'   => getcwd().'/uploads/quotationcopy/',
            'allowed_types' => '*',
            'overwrite'     => 0,
            'encrypt_name' => FALSE, 
            );
           
            $this->load->library('upload', $config);
            $vid=$_POST['vendor_id'][$i];
            $file=$_FILES[$vid.'_vendor_quote_copy']['name'];
            if($file!=''){
              $fileName = rand(0000,9999) .'_'.$file;
              $config['file_name'] = $fileName; 
              $this->upload->initialize($config);

              if($this->upload->do_upload($vid.'_vendor_quote_copy')) 
              { 
                $data = $this->upload->data();
                $vendor_quote_copy=$data['file_name'];
              }
              else
              {
                 $vendor_quote_copy='';
              }
            }
           //print_r( $vendor_quote_copy);exit;
            $vendor_quotation_no=$_POST[$vid.'_vendor_quotation_no'];


            $quantity= $_POST[$vid.'_quantity'];
            //print_r($quantity[0]);exit;
            $count=count($quantity);
            
            for($j=0; $j<$count; $j++)
            {
                if($_POST[$vid.'_quantity'][$j]!='')
                {
                    $quotation_details_data=array(
                        'quotation_id'=>$quotation_id,
                        'quotation_request_id'=>$_POST['quotation_request_id'],
                        'vendor_id'=>$vid,
                        'vendor_quote_copy'=>$vendor_quote_copy,
                        'vendor_quotation_no'=>$vendor_quotation_no,
                        'mrp'=>$_POST[$vid.'_mrp'][$j],
                        'per_unit_price'=>$_POST[$vid.'_per_unit_price'][$j],
                        'amount'=>$_POST[$vid.'_amount'][$j],
                        'asset_id'=>$_POST[$vid.'_asset_id'][$j],
                        'asset_type_id'=>$_POST[$vid.'_asset_type_id'][$j],
                        'quotation_request_detail_id'=>$_POST[$vid.'_quotation_request_detail_id'][$j],
                        'origin_qty'=>$_POST[$vid.'_originqty'][$j],
                        'quantity'=>$quantity[$j],
                        'status'=>'Pending',
                    );
                    $this->Crud_model->SaveData('quotation_details',$quotation_details_data);
                    $getqrdata=$this->Crud_model->GetData('quotation_request_details','',"id='".$_POST[$vid.'_quotation_request_detail_id'][$j]."'",'');
                    $remainQty=$getqrdata[0]->remaining_qty;
                    if($remainQty!=0){
                        $qty=$remainQty-$quantity[$j];
                        $data=array(

                           'remaining_qty'=>$remainQty-$quantity[$j],
                        );
                        $this->Crud_model->SaveData("quotation_request_details",$data,"id='".$_POST[$vid.'_quotation_request_detail_id'][$j]."'");
                        if($qty==0){
                            $data=array(

                           'status'=>'Approved',
                        );
                        $this->Crud_model->SaveData("quotation_request_details",$data,"id='".$_POST[$vid.'_quotation_request_detail_id'][$j]."'");
                        }

                    }else{
                        $data=array(

                           'status'=>'Approved',
                        );
                        $this->Crud_model->SaveData("quotation_request_details",$data,"id='".$_POST[$vid.'_quotation_request_detail_id'][$j]."'");
                    }

                }


            }
        }

        if($remaining==0){
                         $data=array(
                            'status'=>'Approved',
                            );
                        $this->Crud_model->SaveData("quotation_request_details",$data,"quotation_request_id='".$_POST['quotation_request_id']."'");
                        
        }

        $this->session->set_flashdata('message', '<span class="label label-success">Quotation Updated successfully</span>');
        redirect('Quotations');


    }


    public function view_quotation($id)
    {
         $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li><a href='".site_url('Quotations')."'>Manage Quotations</a></li>
                        <li class='active'>View Quotation</li>
                        </ul>";
        $cond="q.id='".base64_decode($id)."'";
        $quotation_data=$this->Quotations_model->GetQuotationData($cond);
       // print_r($quotation_data);exit;
        $data=array(
            'breadcrumbs'=>$breadcrumbs,
            'quotation_data'=>$quotation_data
        );
        $this->load->view('quotations/quotation_view',$data);
    }

    public function getQuotaitonDetail()
    {
         $cond="qt.vendor_id='".$_POST['vendor_id']."' and qt.quotation_id='".$_POST['quotation_id']."'";
        $vendor_data=$this->Quotations_model->GetVendorQuotData($cond);
        //print_r($this->db->last_query());exit;
        $data=array(
            'vendor_data'=>$vendor_data
        );
        $this->load->view('quotations/quotation_detail',$data);
    }

    public function Quotation_approved()
    {
       
        $quotation_detail_status=array(
            'status'=>'Approved',
             'approved_date'=>date('Y-m-d'),
             'approved_by'=>$_SESSION[SESSION_NAME]['id'],
        );
        $this->Crud_model->SaveData("quotation_details",$quotation_detail_status,"quotation_id='".$_POST['quotation_id']."'");

         $quotation_status=array(
            'status'=>'Approved',
            'approved_by'=>$_SESSION[SESSION_NAME]['id'],
        );
        $this->Crud_model->SaveData("quotations",$quotation_status,"id='".$_POST['quotation_id']."'");

        $this->session->set_flashdata('message', '<span class="label label-success">Quotation Approved successfully</span>');
        redirect('Quotations');
    }

}