<?php
/* all controller functionality developed by Prashant */
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_request extends CI_Controller {

    function __construct()
    {
        parent::__construct();      
        $this->load->database();
        $this->load->library(array('session','form_validation'));
        $this->load->model(array('Crud_model','Quotation_request_model'));   
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->library(array('Urlcheck'));
        $this->Urlcheck = new Urlcheck();  
        $this->load->library(array('SendMail'));
        $this->SendMail = new SendMail();
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
                  if($menu->value=='Quotation_request')
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
                        <li class='active'><a href='".site_url('Quotation_request')."'>Manage Quotation Request</a></li>
                        </ul>";
        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '4' ,
        'ajax_manage_page' => site_url('Quotation_request/ajax_manage_page') ,
        'button' => 'Create',
        'heading' => 'Manage Quotation Request',
        'addPermission'=>$add
        );
        $this->load->view('quotation_request/list',$data);
        }
    else
    {
      redirect('Dashboard');
    }   
    }

    public function ajax_manage_page()
    {
        $delete= '';
        $actstatus= ''; 
        $edit = ''; 
        $getData = $this->Quotation_request_model->get_datatables();
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Quotation_request')
              { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
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

            $btn ='';
            $quotation_data = $this->Crud_model->GetData("quotations",'',"quotation_request_id='".$Data->id."'");
            if(empty($quotation_data))
            {
                  if(!empty($edit)){
                $btn .='&nbsp;<a href="'.site_url('Quotation_request/edit_quotation_request/'.base64_encode($Data->id)).'" title="Edit Detail" class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-edit bigger-130"></i></a>';
                  }
            if(!empty($delete)){
                $btn .='&nbsp;<a href="'.site_url('Quotation_request/delete/'.base64_encode($Data->id)).'" title="Delete Detail" class="btn btn-danger btn-circle btn-sm" onclick="return confirm(\'Are you want to delete this record?\');"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
                 }
              
            }
            else
            {
            	$btn .= '-';
            }
            
            $status = '';
             if(!empty($actstatus)){
                if($Data->status=='Approved')
                {
                      $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$Data->quotation_request_id.")'> Approved </a>";            
                }
                else if($Data->status=='Pending')
                {
                     $status =  "<a href='#checkStatus' data-toggle='modal' class='label-primary label' onclick='checkStatus(".$Data->quotation_request_id.")'> Pending </a>";
                }
                else
                {
                      $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$Data->quotation_request_id.")'> Rejected </a>";
                }
            }
            $no++;   
            $row = array();
            $row[] = $no;
            $row[] = $Data->request_no;
            $row[] = $Data->assets_name;
            $row[] = $Data->vendor_name;
            $row[] = $Data->totalqty;
            $row[] = $status;
            $row[] = $btn;
            $data[] = $row;
            $selected = '';
        }
 
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Quotation_request_model->count_all(),
          "recordsFiltered" => $this->Quotation_request_model->count_all(),
          "data" => $data,
        );
        echo json_encode($output);
    }

    function create()
    {
        $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li><a href='".site_url('Quotation_request')."'>Manage Quotation Request</a></li>
                        <li class='active'>Create Quotation Request</li>
                        </ul>";
        $nyr = date('y')+1;
        $no = 0;
        $no = ++$no;
        $quotationdata = $this->Crud_model->GetData("quotation_requests",'max(request_no) as request_no');
        $assets = array();
        if(!empty($quotationdata))
        {
          $cno = explode("/", $quotationdata[0]->request_no);
        }

        if(!empty($quotationdata[0]->request_no))
        {

            $quotation_no = 'R/'.date('y').'-'.$nyr.'/000'.($cno[2]+1); 
        }
        else
        {
            $no = 0;
            $no = ++$no;
            $quotation_no = 'R/'.date('y').'-'.$nyr.'/000'.$no; 
        }

        $asset_type = $this->Crud_model->GetData("mst_asset_types","id,type","status='Active'","","type asc");
        
        $vandor = $this->Crud_model->GetData("mst_users",'',"","","name asc");
        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'quotation_requests' => array(),
        'quotation_requests_details' => array(),
        'assets' => $assets,
        'quotation_request_no' => $quotation_no,
        'asset_type' => $asset_type, 
        'vandor' => $vandor, 
        'button' => 'Create',
        'heading' => 'Create Quotation Request',
        'action' => site_url('Quotation_request/create_action'),
        'id' => '',
        );
        $this->load->view('quotation_request/create',$data);
    }

    function changeStatus() {
        $getasset_types = $this->Crud_model->GetData('quotation_request_details','',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Pending')
        {
            $this->Crud_model->SaveData('quotation_request_details',array('status'=>'Approved'),"id='".$_POST['id']."'");
        }
        else if($getasset_types->status=='Approved')
        {
            $this->Crud_model->SaveData('quotation_request_details',array('status'=>'Rejected'),"id='".$_POST['id']."'");
        } 
        else {
          $this->Crud_model->SaveData('quotation_request_details',array('status'=>'Pending'),"id='".$_POST['id']."'");  
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Status has been changed successfully</span>');
        redirect(site_url('Quotation_request/index'));
    }

    function getAssets()
    {
        $getAssets = $this->Crud_model->GetData("assets",'',"asset_type_id='".$_POST['asset_type_id']."'",'',"asset_name asc");
        if(!empty($getAssets))
        {
            $response = '<option value="">Select Product</option>';
            foreach($getAssets as $row)
            {
                 $response .= '<option value="'.$row->id.'">'.$row->asset_name.'</option>';
            }
        }
        else
        {
             $response = '<option value="">Select Product</option>';
        }

        echo $response;
    }

    function create_action()
    {
        //print_r($_POST);exit;
         $assetCount = count($_POST['asset']);
         $html='<table border="1" cellpadding="5" cellspacing="0" width="100%" style="font-size: 12px;"> 
                                            <tr valign="top">
                                                <th>Sr No.</th>
                                                <th>Product Type</th>
                                                <th>Products</th>
                                                <th>Quantity</th>
                                            </tr><tbody>';
            $qty=0;
            $sr=0;
            for ($j=0; $j < $assetCount ; $j++) { 
                $astData=$this->Crud_model->GetData("assets",'',"id='".$_POST['asset'][$j]."'");
                $astTypeData=$this->Crud_model->GetData("mst_asset_types",'',"id='".$_POST['asset_type'][$j]."'");
                $astName=$astData[0]->asset_name;
                $astTName=$astTypeData[0]->type;
                $sr++;
                $qty=$qty+$_POST['quantity'][$j];
                $html.="<tr>
                    <td>".$sr."</td>
                    <td>".$astTName."</td>
                    <td>".$astName."</td>
                    <td>".$_POST['quantity'][$j]."</td>

                </tr>";

             }
             $html.="
             <tr><th colspan='3' style='text-align:right'>Total Qty</th><th  style='text-align:left'>".$qty."</th></td></tbody>
             </table>";

        $data = array(
            'request_no' => $this->input->post('quotation_request_no'), 
            'request_date' => date('Y-m-d'),
            'created_by' => $_SESSION['ASSETSTRACKING']['id'],
            'totalqty' => array_sum($_POST['quantity']),
            'remain_qty' => array_sum($_POST['quantity']),
        );
        $this->Crud_model->SaveData("quotation_requests",$data);
        $quotation_request_id = $this->db->insert_id();
        $vandorCount = count($_POST['vandor']);
        for ($i=0; $i < $vandorCount; $i++) 
        {
            $vandor = $this->Crud_model->GetData("mst_users",'',"id='".$_POST['vandor'][$i]."'");
           
            /*$mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='quotation_request'");
            $request_date = date('Y-m-d');
            if(!empty($mail_body))
            {
                $mail_body[0]->mail_body=str_replace("{shop_name}",ucfirst($vandor[0]->shop_name),$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{name}",ucfirst($vandor[0]->name),$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{email}",$vandor[0]->email,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{mobile}",$vandor[0]->mobile,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{address}",$vandor[0]->address,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{request_no}",$_POST['quotation_request_no'],$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{request_date}",$request_date,$mail_body[0]->mail_body);
                $mail_body[0]->mail_body=str_replace("{html}",$html,$mail_body[0]->mail_body);
                $subject=$mail_body[0]->mail_subject;
                $body=$mail_body[0]->mail_body;
                $MailData = array('mailoutbox_to'=>$vandor[0]->email,'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body[0]->type);
                //$Send=$this->SendMail->Send($MailData);
                //print_r($Send);exit;
                $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Your quotation request create successfully, Please check your email</p></div>');
            }
            else
            { 
                $this->session->set_flashdata("message",'<div class="label label-danger text-center" style="margin-bottom:0px;"><p>Error in sending email...</p></div>'); 
            }*/

            $assetCount = count($_POST['asset']);
            for ($j=0; $j < $assetCount ; $j++) { 
                $data = array(
                    'quotation_request_id' => $quotation_request_id,
                    'assets_type_id' => $_POST['asset_type'][$j],
                    'asset_id' => $_POST['asset'][$j],
                    'quantity' => $_POST['quantity'][$j],
                    'remaining_qty' => $_POST['quantity'][$j],
                    'user_id' => $_POST['vandor'][$i],
                );

                $this->Crud_model->SaveData("quotation_request_details",$data); 
            }



        }

        redirect('Quotation_request/index');
    }

    function edit_quotation_request($id)
    {
        $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li><a href='".site_url('Quotation_request')."'>Manage Quotation Request</a></li>
                        <li class='active'>Update Quotation Request</li>
                        </ul>";
        $id = base64_decode($id);
        $quotation_requests = $this->Crud_model->GetData("quotation_requests",'',"id='".$id."'");
        $quotation_requests_details = $this->Crud_model->GetData("quotation_request_details",'',"quotation_request_id='".$id."'",'asset_id','id asc');
        $vandorIds = $this->Crud_model->GetData("quotation_request_details",'group_concat(distinct user_id) as vandor_id',"quotation_request_id='".$id."'",'user_id');
        foreach ($vandorIds as $key) {
        	$vandors[]=$key->vandor_id;
        }
        $vandor = $this->Crud_model->GetData("mst_users",'',"","","name asc");
        $explodeVandors = implode(',', $vandors);
        $count=count($vandors);
        $asset_type = $this->Crud_model->GetData("mst_asset_types","id,type","status='Active'");
        $data = array(
            'breadcrumbs' => $breadcrumbs ,
            'quotation_requests' => $quotation_requests,
            'quotation_requests_details' => $quotation_requests_details,
            'quotation_request_no' => $quotation_requests[0]->request_no,
            'asset_type' => $asset_type,
            'vandorIds' => $vandorIds,
            'vandor' => $vandor, 
            'button' => 'Update',
            'heading' => 'Update Quotation Request',
            'action' => site_url('Quotation_request/update_action/'.$id),
            'id' => $id,
        );
        $this->load->view('quotation_request/create',$data);    
    }

    function update_action($id)
    {

        $vandorCount = count($_POST['vandor']);
        $assetCount = count($_POST['asset']);
        $html='<table border="1" cellpadding="5" cellspacing="0" width="100%" style="font-size: 12px;"> 
                                            <tr valign="top">
                                                <th>Sr No.</th>
                                                <th>Product Type</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                            </tr><tbody>';
            $qty=0;
            $sr=0;
            for ($j=0; $j < $assetCount ; $j++) { 
                $astData=$this->Crud_model->GetData("assets",'',"id='".$_POST['asset'][$j]."'");
                $astTypeData=$this->Crud_model->GetData("mst_asset_types",'',"id='".$_POST['asset_type'][$j]."'");
                $astName=$astData[0]->asset_name;
                $astTName=$astTypeData[0]->type;
                $sr++;
                $qty=$qty+$_POST['quantity'][$j];
                $html.="<tr>
                    <td>".$sr."</td>
                    <td>".$astTName."</td>
                    <td>".$astName."</td>
                    <td>".$_POST['quantity'][$j]."</td>

                </tr>";

            }
         $html.="
         <tr><th colspan='3' style='text-align:right'>Total Qty</th><th  style='text-align:left'>".$qty."</th></td></tbody>
         </table>";

        $vandorIds = $this->Crud_model->GetData("quotation_request_details",'group_concat(distinct user_id) as vandor_id',"quotation_request_id='".$id."'",'user_id');
        
        $vandor =$_POST;echo "<br/>";
        $vandor =$_POST['vandor'];
        $match_ids=array();
        $unmatch_ids =array();
        foreach ($vandorIds as $vandorIds_value) { //print_r($vandorIds_value->vandor_id);echo '<br/>';
        if(in_array($vandorIds_value->vandor_id, $vandor)){
                $match_ids[] = $vandorIds_value->vandor_id;
            }else{
                $unmatch_ids[] = $vandorIds_value->vandor_id;
            }
        }
       
        foreach ($vandor as $value) {
            if (!in_array($value, $match_ids)) {
                $unmatch_ids[] = $value;
            }
        }
        

        $unmatch_vendorCount = count($unmatch_ids);
        $vendorCount = count($match_ids);


        for($i=0; $i < $vendorCount; $i++)
        { 
          //print_r("quotation_update");exit;
                    $vandor = $this->Crud_model->GetData("mst_users",'',"id='".$match_ids[$i]."'");
                    /*$this->email->set_mailtype("html");
                    $this->email->from('info@assettracking.com', 'Asset Tracking');
                    $this->email->to($vandor[0]->email);
                    $this->email->cc('admin@assettracking.com');
                    $this->email->subject('Quotation Request');

                    $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='quotation_update'");
                    $request_date = date('Y-m-d');
                    if(!empty($mail_body))
                    { 
                        //print_r("quotation_update");exit;
                        $mail_body[0]->mail_body=str_replace("{shop_name}",ucfirst($vandor[0]->shop_name),$mail_body[0]->mail_body);
                        $mail_body[0]->mail_body=str_replace("{name}",ucfirst($vandor[0]->name),$mail_body[0]->mail_body);
                        $mail_body[0]->mail_body=str_replace("{email}",$vandor[0]->email,$mail_body[0]->mail_body);
                        $mail_body[0]->mail_body=str_replace("{mobile}",$vandor[0]->mobile,$mail_body[0]->mail_body);
                        $mail_body[0]->mail_body=str_replace("{address}",$vandor[0]->address,$mail_body[0]->mail_body);
                        $mail_body[0]->mail_body=str_replace("{request_no}",$_POST['quotation_request_no'],$mail_body[0]->mail_body);
                        $mail_body[0]->mail_body=str_replace("{request_date}",$request_date,$mail_body[0]->mail_body);
                        $mail_body[0]->mail_body=str_replace("{html}",$html,$mail_body[0]->mail_body);
                        $subject=$mail_body[0]->mail_subject;
                        $body=$mail_body[0]->mail_body;
                        //print_r($body);echo'<br/>';
                        $MailData = array('mailoutbox_to'=>$vandor[0]->email,'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body[0]->type);
                        //$Send=$this->SendMail->Send($MailData);
                        
                    }*/
                    
                
        }

        for($j=0; $j < $unmatch_vendorCount; $j++)
        {
            $vendor_id = $unmatch_ids[$j];
            $quotation_request_details = $this->Crud_model->GetData('quotation_request_details','',"user_id='".$vendor_id."' and quotation_request_id='".$id."'",'','','','1');
            if(empty($quotation_request_details))
            {
                        $vandor = $this->Crud_model->GetData("mst_users",'',"id='".$vendor_id."'");
                        /*$this->email->set_mailtype("html");
                        $this->email->from('info@assettracking.com', 'Asset Tracking');
                        $this->email->to($vandor[0]->email);
                        $this->email->cc('admin@assettracking.com');
                        $this->email->subject('Quotation Request');


                        $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='quotation_request'");
                        $request_date = date('Y-m-d');
                        if(!empty($mail_body))
                        {
                            $mail_body[0]->mail_body=str_replace("{shop_name}",ucfirst($vandor[0]->shop_name),$mail_body[0]->mail_body);
                            $mail_body[0]->mail_body=str_replace("{name}",ucfirst($vandor[0]->name),$mail_body[0]->mail_body);
                            $mail_body[0]->mail_body=str_replace("{email}",$vandor[0]->email,$mail_body[0]->mail_body);
                            $mail_body[0]->mail_body=str_replace("{mobile}",$vandor[0]->mobile,$mail_body[0]->mail_body);
                            $mail_body[0]->mail_body=str_replace("{address}",$vandor[0]->address,$mail_body[0]->mail_body);
                            $mail_body[0]->mail_body=str_replace("{request_no}",$_POST['quotation_request_no'],$mail_body[0]->mail_body);
                            $mail_body[0]->mail_body=str_replace("{request_date}",$request_date,$mail_body[0]->mail_body);
                            $mail_body[0]->mail_body=str_replace("{html}",$html,$mail_body[0]->mail_body);
                            $subject=$mail_body[0]->mail_subject;
                            $body=$mail_body[0]->mail_body;
                            //print_r($body);echo'<br/>';
                            $MailData = array('mailoutbox_to'=>$vandor[0]->email ,'mailoutbox_subject'=>$subject, 'mailoutbox_body'=>$body, 'mail_type'=>$mail_body[0]->type);
                            //$Send=$this->SendMail->Send($MailData);
                       }*/
                        
                
            }
            else
            {
                //print_r("quotation_cancel");exit;
                /*$vandor = $this->Crud_model->GetData("vendors",'',"id='".$vendor_id."'");
                $this->email->set_mailtype("html");
                $this->email->from('info@assettracking.com', 'Asset Tracking');
                $this->email->to($vandor[0]->email);
                $this->email->cc('admin@assettracking.com');
                $this->email->subject('Quotation Request');
                
                $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='quotation_cancel'");
                $request_date = date('Y-m-d');
                if(!empty($mail_body))
                {
                    $mail_body[0]->mail_body=str_replace("{shop_name}",ucfirst($vandor[0]->shop_name),$mail_body[0]->mail_body);
                    $mail_body[0]->mail_body=str_replace("{name}",ucfirst($vandor[0]->name),$mail_body[0]->mail_body);
                    $mail_body[0]->mail_body=str_replace("{email}",$vandor[0]->email,$mail_body[0]->mail_body);
                    $mail_body[0]->mail_body=str_replace("{mobile}",$vandor[0]->mobile,$mail_body[0]->mail_body);
                    $mail_body[0]->mail_body=str_replace("{address}",$vandor[0]->address,$mail_body[0]->mail_body);
                    $mail_body[0]->mail_body=str_replace("{request_no}",$_POST['quotation_request_no'],$mail_body[0]->mail_body);
                    $subject=$mail_body[0]->mail_subject;
                    $body=$mail_body[0]->mail_body;
                    //print_r($body);echo'<br/>';
                    $MailData = array('mailoutbox_to'=>$vandor[0]->email,'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body[0]->type);
                    //$Send=$this->SendMail->Send($MailData);
                   
                }*/
                
            }//exit;
        }
            

        //$this->Crud_model->DeleteData("quotation_requests","id='".$id."'");
        $this->Crud_model->DeleteData("quotation_request_details","quotation_request_id='".$id."'");

        $data = array(
            'totalqty' => array_sum($_POST['quantity']),
            'remain_qty' => array_sum($_POST['quantity']),
            'modified'=>date('Y-m-d H:i:s'),
        );
        $this->Crud_model->SaveData("quotation_requests",$data,"id='".$id."'");
        $quotation_request_id = $id;
        $vandorCount = count($_POST['vandor']);
        for ($i=0; $i < $vandorCount; $i++) 
        { 
            $assetCount = count($_POST['asset']);
            for ($j=0; $j < $assetCount ; $j++) { 
                $data = array(
                    'quotation_request_id' => $quotation_request_id,
                    'assets_type_id' => $_POST['asset_type'][$j],
                    'asset_id' => $_POST['asset'][$j],
                    'quantity' => $_POST['quantity'][$j],
                    'user_id' => $_POST['vandor'][$i],
                    'remaining_qty' => $_POST['quantity'][$j],
                );

                $this->Crud_model->SaveData("quotation_request_details",$data); 
            }
        }

         $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Your quotation updated successfully.</p></div>');
        redirect('Quotation_request/index');
    }

    function delete($id)
    {
        $id = base64_decode($id);
        $this->Crud_model->DeleteData("quotation_requests","id='".$id."'");
        $this->Crud_model->DeleteData("quotation_request_details","quotation_request_id='".$id."'");
        redirect('Quotation_request/index');
    }

    function getAssetType()
    {
        $explodeVandors = implode(',', $_POST['vandor']);
        $count=count($_POST['vandor']);
        $assetTypeData = $this->Quotation_request_model->getAssetData($explodeVandors,$count);
        
        if(!empty($assetTypeData))
        {
        	$response = '<option value="">Select Product Type</option>';
    		foreach($assetTypeData as $row)
            {
                 $response .= '<option value="'.$row->id.'">'.$row->type.'</option>';
            }
        }
        else
        {
             $response = '<option value="">Select Product Type</option>';
        }

        echo $response; 
    }
}