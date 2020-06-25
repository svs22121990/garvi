<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ConfigureReport extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('ConfigureReport_model');
 
  $this->load->database();
  }
  public function index()
  { 

    if($_SESSION[SESSION_NAME]['type']!='Admin'){
      redirect('ConfigureReport/index');
    }

    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $view = '';  
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='ConfigureReport')
              { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              }
          }
        }

        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Configure Report </li>
                    </ul>";

         $data = array(
          'breadcrumbs' => $breadcrumbs ,
          'heading' => 'Manage Configure Report ',
          'actioncolumn' => '4' ,
          'ajax_manage_page' => site_url('ConfigureReport/ajax_manage_page') ,
          'addPermission'=>$add);

        $this->load->view('configure_report/list',$data);
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
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $view = '';
    
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='ConfigureReport')
              { 
               if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              }
          }
        }

    $Data = $this->ConfigureReport_model->get_datatables(); 
   
    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
      

      $status = '';
      if(!empty($actstatus))
      {
          /*if($row->status=='Active')
          {
            $status =  "<span  class='label-warning label'> Active </span>";            
          }
          else if($row->status=='Inactive')
          {
              $status =  "<span class='label-success label'> Inactive </span>";
          }*/

          if($row->status=='Active')
              {
                  $status .=  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$row->id.")'> Active </a>";            
              }
              else
              {
                  $status .= "<a href='#checkStatus' data-toggle='modal' class='label-warning label' onclick='checkStatus(".$row->id.")'> Inactive </a>";
              }
      }
      else
      {
        $status='-';
      }  

      $btn='';
      if(!empty($edit))
      {   
        $btn ='&nbsp;&nbsp;'.'<a class="btn btn-info btn-circle btn-sm"  title="Edit" href="'.site_url('ConfigureReport/update/'.$row->id).'"><i class="ace-icon fa fa-edit bigger-130"></i></a>'; 
      }
      else
      {
        $btn ="";
      }  
         
    
      if(!empty($delete))
      {
        $btn .='&nbsp;&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
      }
      else
      {
         $btn .="";
      } 
          
      if(!empty($btn))
      {
        $btn = $btn;
      }
      else
      {
        $btn = '-';
      }
        $no++;
        $nestedData = array();
        $nestedData[] = $no ;
        $nestedData[] = $row->primary_table;
        $nestedData[] = $row->title;
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->ConfigureReport_model->count_all(),
                "recordsFiltered" => $this->ConfigureReport_model->count_filtered(),
                "data" => $data,
            );
   
    echo json_encode($output);
}

public function changeStatus(){       
        $report_format = $this->Crud_model->GetData('report_format','status',"id='".$_POST['id']."'",'','','','row');
        if($report_format->status=='Active')
        {
            $this->Crud_model->SaveData('report_format',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
       	{
       		 $this->Crud_model->SaveData('report_format',array('status'=>'Active'),"id='".$_POST['id']."'");
       	} 	
        
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('ConfigureReport'));
    }
public function delete()
{
$con = "id='".$_POST['id']."'";
$this->Crud_model->DeleteData('report_format',$con);

$this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Report deleted successfully</span>');
redirect('ConfigureReport/index');
}
public function create()
{
   $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li> 
                    <li> <a href='".site_url('ConfigureReport')."'>Manage Configure Report </a>
                    </li>
                    <li class='active'>Add Report </li>
                    </ul>";

    $data = array(
        'heading'=>'Add Report',
        'breadcrumbs'=>$breadcrumbs,
        'button'=>'Create',
        'action'=> site_url('ConfigureReport/create_action'),
    );
    $this->load->view('configure_report/form',$data);
}
 
public function create_action()
{
  $id = '0';
  $this->_rules($id);
  if($this->form_validation->run() == FALSE) 
  {  
    $this->create();
   
  } 
  else
  {  
       if($this->input->post('primary_table') == 'Assets')
         {
            $_POST['quantity'] =$_POST['asset_quantity'];
            $_POST['category_name'] =$_POST['asset_category_name'];
            $_POST['sub_category_name'] =$_POST['asset_sub_category_name'];
            $_POST['asset_name'] =$_POST['asset_asset_name'];
           
         } 
         if($this->input->post('primary_table') == 'Purchase_order')
         {
            $_POST['quantity'] =$_POST['quantity'];
            $_POST['category_name'] =$_POST['category_name'];
            $_POST['sub_category_name'] =$_POST['sub_category_name'];
            $_POST['vendor_name'] =$_POST['po_vendor_name'];
            $_POST['shop_name'] =$_POST['po_shop_name'];
            $_POST['asset_name'] =$_POST['asset_name'];
		 }

	    if($this->input->post('primary_table') == 'Vendor')
	    {
           	$_POST['vendor_name'] =$_POST['vendor_name'];
            $_POST['shop_name'] =$_POST['shop_name'];
        }

    $this->Crud_model->SaveData('report_format',$_POST);
    $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Report has been created successfully</span>');
    redirect('ConfigureReport/index');
  }  
}
public function update($id)
{
  if(!empty($id))
  {  
    $GetData = $this->Crud_model->GetData('report_format',"","id='".$id."'",'','','','1');  
    if(empty($GetData))
    {
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Record Not Found</span>');
        redirect('ConfigureReport');
    }
   $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li> 
                    <li> <a href='".site_url('ConfigureReport')."'>Manage Configure Report </a>
                    </li>
                    <li class='active'>Update Report </li>
                    </ul>";
    $data = array(
        'heading'=>'Update Report',
        'breadcrumbs'=>$breadcrumbs,
        'GetData'=>$GetData,
        'button'=>'Update',
        'action'=> site_url('ConfigureReport/update_action/'.$id),
    );
    $this->load->view('configure_report/form',$data);
  }
  else
  {
    $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Record Not Found</span>');
    redirect('ConfigureReport');
  }  
}
 public function update_action($id)
    { 
         
         if($this->input->post('primary_table') == 'Assets')
         {
            $_POST['quantity'] =$_POST['asset_quantity'];
            $_POST['category_name'] =$_POST['asset_category_name'];
            $_POST['sub_category_name'] =$_POST['asset_sub_category_name'];
            $_POST['asset_name'] =$_POST['asset_asset_name'];
           
            if(empty($_POST['asset_category_name']))
            $_POST['category_name'] ="No";

            if(empty($_POST['asset_sub_category_name']))
            $_POST['sub_category_name'] ="No";

            if(empty($_POST['asset_quantity']))
            $_POST['quantity'] ="No";

            if(empty($_POST['asset_asset_name']))
            $_POST['asset_name'] ="No";

         } 
         if($this->input->post('primary_table') == 'Purchase_order')
         {
            $_POST['quantity'] =$_POST['quantity'];
            $_POST['category_name'] =$_POST['category_name'];
            $_POST['sub_category_name'] =$_POST['sub_category_name'];
            $_POST['vendor_name'] =$_POST['po_vendor_name'];
            $_POST['shop_name'] =$_POST['po_shop_name'];
            $_POST['asset_name'] =$_POST['asset_name'];

            if(empty($_POST['po_vendor_name']))
            $_POST['vendor_name'] ="No";

            if(empty($_POST['po_shop_name']))
             $_POST['shop_name'] ="No";

            if(empty($_POST['category_name']))
            $_POST['category_name'] ="No";

            if(empty($_POST['sub_category_name']))
            $_POST['sub_category_name'] ="No";

            if(empty($_POST['quantity']))
            $_POST['quantity'] ="No";

            if(empty($_POST['asset_name']))
            $_POST['asset_name'] ="No";
         }

         if($this->input->post('primary_table') == 'Vendor')
         {
            
            $_POST['vendor_name'] =$_POST['vendor_name'];
            $_POST['shop_name'] =$_POST['shop_name'];
           
			 if(empty($_POST['vendor_name']))
            $_POST['vendor_name'] ="No";

            if(empty($_POST['po_shop_name']))
             $_POST['shop_name'] ="No";

           
         }

        if(empty($_POST['po_number']))
        $_POST['po_number'] ="No";

        if(empty($_POST['purchase_date']))
        $_POST['purchase_date'] ="No";

        if(empty($_POST['quotation_no']))
        $_POST['quotation_no'] ="No";

        if(empty($_POST['po_status']))
        $_POST['po_status'] ="No";

        if(empty($_POST['bill_no']))
        $_POST['bill_no'] ="No";

        if(empty($_POST['payment_type']))
        $_POST['payment_type'] ="No";

        if(empty($_POST['check_no']))
        $_POST['check_no'] ="No";

        if(empty($_POST['account_no']))
        $_POST['account_no'] ="No";

        if(empty($_POST['check_date']))
        $_POST['check_date'] ="No";

        if(empty($_POST['paid_by']))
        $_POST['paid_by'] ="No";

        if(empty($_POST['bank_name']))
        $_POST['bank_name'] ="No";

        if(empty($_POST['brand_name']))
        $_POST['brand_name'] ="No";

        if(empty($_POST['asset_type']))
        $_POST['asset_type'] ="No";

        if(empty($_POST['unit_name']))
        $_POST['unit_name'] ="No";
     
        $this->Crud_model->SaveData('report_format',$_POST,"id='".$id."'");  
    
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Report has been updated successfully</span>');
        redirect(site_url('ConfigureReport'));
    }

public function _rules($id)
{
        $table = 'report_format';
        $cond = "title='".trim($this->input->post('title',TRUE))."' and id!='$id'";
        $row = $this->Crud_model->GetData($table,'',$cond,'','','','1');
        $count = count($row); 
        if($count==0)
        {
            $is_unique = "";
        }
        else 
        {
            $is_unique = "|is_unique[report_format.title]";

        } 
        $this->form_validation->set_rules('title', 'Banner Title', 'trim|required'.$is_unique,
        array(
            'required'      => 'Required',
            'is_unique'     => 'Already exists'
          )); 
        

    $this->form_validation->set_rules('id', 'id', 'trim');
    $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');   
}  
  
}
