<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('Users_model');
    $this->load->library('upload');
    $this->load->library('image_lib');
    $this->load->library(array('SendMail'));
    $this->SendMail = new SendMail();
    $this->load->database();
  }
  
  public function index() {
    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $edit = '';
    $view = '';
    $export_button = '';
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  

        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 

          foreach($row as $menu)
          { 
              if($menu->value=='Users')
              { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                if(!empty($menu->act_export)){ $export_button='1'; }else{ $export_button='0'; }
              }
          }
        }
        $condUser ="status='Active'";
        $countries =  $this->Crud_model->GetData('mst_countries','',$condUser);
        $branch = $this->Crud_model->GetData('branches','',"status='Active' and is_delete='No'",'','','','');
        $export =anchor(site_url('Users/export'),'<span title="Export" title="Export" class="fa fa-file-export "></span>');
        if($_SESSION[SESSION_NAME]['type']=='Admin') 
        {
          $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li class='active'>Manage Users</li>
                        </ul>";
        }
        else
        {
          $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li class='active'>Manage Users</li>
                        </ul>";
        }

        if($_SESSION[SESSION_NAME]['type']=='Admin') 
        {
          $actioncolumn ='5';
        }
        else
        {
          $actioncolumn ='4';
        }
        if($_SESSION[SESSION_NAME]['type']=='Admin') 
        {
          $heading ='Manage Users';
        }
        else
        {
          $heading ='Manage Users';
        }

          $data = array(
                    'actioncolumn' => $actioncolumn,
                    'ajax_manage_page' => site_url('Users/ajax_manage_page') ,
                    'heading' => $heading,
                    'countries' =>$countries,
                    'branch' =>$branch,
                    'export' =>$export_button,
                    'breadcrumbs' =>$breadcrumbs,
                    'addPermission'=>$add,
                    'action' => site_url('Users/create'),
                    );

        $this->load->view('employees/employees_list',$data);
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
    $edit = '';
    $view = '';
    $export_button = '';
    if($_SESSION[SESSION_NAME]['type']=='Admin')
    {
      $branch_id = $_POST['SearchData1'];
      $condition="employees.type='Employee'";
      if($branch_id!='')
      {
        $condition .= " and employees.branch_id='".$branch_id."'";
      }
    }
    else
    {
      $condition="employees.type='User' and created_by='".$_SESSION[SESSION_NAME]['id']."'";
    }
    
    $getData = $this->Users_model->get_datatables($condition);
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='Users')
          { 
            if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
            if(!empty($menu->act_view)){ $view='1'; }else{ $view='0'; }
            if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
            if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
          }
      }
    }
    $no = 0;
    $data = array();    
    foreach ($getData as $Data) 
    {
      $btn='';
          if(!empty($view)){
        $btn .='&nbsp;'.'<a style="color:#fff" title="View" href="'.site_url('Users/view/'.$Data->id).'"><span class="btn btn-primary btn-circle btn-sm"><i class="ace-icon fa fa-eye bigger-130"></i></a></span>';
        }
        if(!empty($edit)){
        $btn .=' &nbsp;|&nbsp; '. '<a style="color:#fff" title="Update" href="'.site_url('Users/update/'.$Data->id).'"><span class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-pencil bigger-130"></i></a></span>';
        }
        if(!empty($delete)){
        $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
        }
        $status ='';
             if(!empty($actstatus)){ 
	     if($Data->status=='Active')
	      {
	          $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$Data->id.")'> Active </a>";            
	      }
	      else
	      {
	          $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$Data->id.")'> Inactive </a>";
	      }
      }

        if(!empty($Data->image))
        {
          $img="<img src=".base_url('uploads/employee_images/'.$Data->image)." width='50px' height='50px'>";
        }
        else
        {
          $img="<img src=".base_url('uploads/employee_images/default.jpg')." width='50px' height='50px'>";
        }
        

        $no++;
        $nestedData = array();
        $nestedData[] = $no;
        if($_SESSION[SESSION_NAME]['type']=='Admin')
        {
          $nestedData[] = ucfirst($Data->branch_title);
        }
        $nestedData[] = ucwords($Data->name);
        if(!empty($Data->designation_name))
        {
          $designation_name = ucwords($Data->designation_name);
        }
        else
        {
          $designation_name = 'N/A';
        }
        $nestedData[] = $designation_name;
        $nestedData[] = $Data->email;
        $nestedData[] = $Data->mobile;
        $nestedData[] = $img;
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
    }    
      $output = array(
         // "draw" => $_POST['draw'],
          "recordsTotal" => $this->Users_model->count_all($condition),
          "recordsFiltered" => $this->Users_model->count_filtered($condition),
          "data" => $data,
        );
    //output to json format
    echo json_encode($output);
}

public function create()
{
  if($_SESSION[SESSION_NAME]['type']=='Admin') 
  {
    $breadcrumbs='<ul class="breadcrumb">
                  <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                  </li>
                  <li class="active"><a href="'.site_url('Users/index').'">Manage Users</a></li>
                  <li class="active">Create Employee</li>
                  </ul>';
  }
  else
  {
    $breadcrumbs='<ul class="breadcrumb">
                  <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                  </li>
                  <li class="active"><a href="'.site_url('Users/index').'">Manage Users</a></li>
                  <li class="active">Create User</li>
                  </ul>';
  }

  $branch =$this->Crud_model->GetData('branches','',"status='Active' and is_delete='No'","","branch_title asc"); 

  $country=$this->Crud_model->GetData('mst_countries','',"status='Active'","","country_name asc");

  $state=$this->Crud_model->GetData('mst_states','',"status='Active'","","state_name asc");

  $city=$this->Crud_model->GetData('mst_cities','',"status='Active'","","city_name asc");

  if($_SESSION[SESSION_NAME]['type']=='Admin')
  {
    $con = "mst_designations.status='Active' and (designation_name!='Admin' || designation_name!='admin') and is_delete='No'";
  }
  else
  {
    $con = "status='Active' and id!='21' and is_delete='No'";
  }
  if($_SESSION[SESSION_NAME]['type']=='Admin')
  {
    $heading = 'Create Employee';
  }
  else
  {
    $heading = 'Create User';
  }
  $designations=$this->Crud_model->GetData('mst_designations','',$con,"","designation_name asc");

  $data = array(
    'heading' => $heading,
    'button' => 'Create',
    'action' => site_url('Users/create_action'),
    'branch' => $branch,
    'country' => $country,
    'state' => $state,
    'city' => $city,
    'designations' => $designations,
    'breadcrumbs' => $breadcrumbs,
    'name' =>  set_value('name',$this->input->post('name')),
    'email' =>  set_value('email',$this->input->post('email')),
    'mobile' =>  set_value('mobile',$this->input->post('mobile')),   
    'address' =>  set_value('address',$this->input->post('address')),
    'country_id' =>  set_value('country_id',$this->input->post('country_id')),
    'state_id' =>  set_value('state_id',$this->input->post('state_id')),   
    'city_id' =>  set_value('city_id',$this->input->post('city_id')),   
    'pincode' =>  set_value('pincode',$this->input->post('pincode')),   
    'branch_id' =>  set_value('branch_id',$this->input->post('branch_id')),   
    'designation_id' =>  set_value('designation_id',$this->input->post('designation_id')), 
    'image' =>  set_value('image',$this->input->post('image')),  
    'invoice_serial_number_series' =>  set_value('invoice_serial_number_series',$this->input->post('invoice_serial_number_series')),
    'dispatch_note_serial_number_series' =>  set_value('dispatch_note_serial_number_series',$this->input->post('dispatch_note_serial_number_series')),
    'gst_number' =>  set_value('gst_number',$this->input->post('gst_number')),
  );

  $this->load->view('employees/employees_form',$data);
}

public function create_action()
{
  //print_r($_FILES);exit;
  $id=0;
  /*$this->validation_rules($id);
  if ($this->form_validation->run() == FALSE)
  {
     $this->create(); 
  }
  else
  {*/  
    $emp_code = 'Emp_'.date('Y').'_'.rand(0000,9999);
    if($_FILES['image']['error']==0)
    { 
      $file_element_name = 'image'; 
      $_POST['image']= 'AT_'.rand(0000,9999).$_FILES['image']['name'];
      $config2['image_library'] = 'gd2';
      $config2['source_image'] =  $_FILES['image']['tmp_name'];
      $config2['new_image'] =   getcwd().'/uploads/employee_images/'.$_POST['image'];
      $config2['upload_path'] =  getcwd().'/uploads/employee_images/'.$_POST['image'];
      $config2['allowed_types'] = 'JPG|PNG|jpg|png|jpeg|JPEG';
      $config2['maintain_ratio'] = TRUE;
      $config2['max_size'] = '1024';
      $config2['width'] = "200";
      $config2['height'] = "300";

      $this->image_lib->initialize($config2);
      if(!$this->image_lib->resize())
      {
        echo('<pre>');
        echo ($this->image_lib->display_errors());
      }
      else
      { 
        $image= $_POST['image'];
      }
    } else {
      $image = '';
    }
      if($_SESSION[SESSION_NAME]['type']=='Admin')
      {
        $type = 'Employee';
        $branch_id = $this->input->post('branch_id');
        $created = 0;
      }
      else
      {
        $type = 'User';
        $branch_id = $_SESSION[SESSION_NAME]['branch_id'];
        $created =  $_SESSION[SESSION_NAME]['id'];
      }
      $data = array(
        'name' => $this->input->post('name'),
        'email' => $this->input->post('email'),
        'mobile' => $this->input->post('mobile'),
        'password' => md5('pass123'),
        'showpassword' =>'pass123',
        'country_id' => $this->input->post('country_id'),
        'state_id' => $this->input->post('state_id'),
        'city_id' => $this->input->post('city_id'),
        'address' => $this->input->post('address'),
        'pincode' => $this->input->post('pincode'),
        'designation_id' => $this->input->post('designation_id'),
        'type' => $type,
        'created_by' =>  $created,  
        'emp_code' => $emp_code,
        'image' =>$image,
        'invoice_serial_number_series' =>  $this->input->post('invoice_serial_number_series'),
        'dispatch_note_serial_number_series' =>  $this->input->post('dispatch_note_serial_number_series'),
        'gst_number' =>  $this->input->post('gst_number'),
        'created' => date("Y-m-d H:i:s"),
      );
      $this->Crud_model->SaveData('employees',$data);
      $password = 'pass123';
      $branches = $this->Crud_model->GetData("branches",'',"id='".$this->input->post('branch_id')."'",'','','','1');
      $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='create_account'");
      $imgpath = base_url('uploads/employee_images/'.$image);
      if(!empty($mail_body))
      {
        /*$mail_body[0]->mail_body=str_replace("{branch}",ucfirst($branches->branch_title),$mail_body[0]->mail_body);
        $mail_body[0]->mail_body=str_replace("{username}",ucwords($_POST['name']),$mail_body[0]->mail_body);
        $mail_body[0]->mail_body=str_replace("{email}",$_POST['email'],$mail_body[0]->mail_body);
        $mail_body[0]->mail_body=str_replace("{password}",$password,$mail_body[0]->mail_body);
        $mail_body[0]->mail_body=str_replace("{imgurl}",$imgpath,$mail_body[0]->mail_body);
        $subject=$mail_body[0]->mail_subject;
        $body=$mail_body[0]->mail_body;*/
        //print_r($body);exit;
        //$MailData = array('mailoutbox_to'=>$_POST['email'],'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body[0]->type);
        //$Send=$this->SendMail->Send($MailData);
        //$this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>You are registered successfully, Please check your email</p></div>');
      }
      else
      { 
        $this->session->set_flashdata("message",'<div class="label label-success text-center" style="margin-bottom:0px;"><p>Error in sending email...</p></div>'); 
      }

      $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Record created successfully</p></div>');
      redirect('Users/index');
    //}
  //}
}

function getState()
{
  $country_id = $this->input->post('country_id');
  $getStates = $this->Crud_model->GetData("mst_states","","country_id='".$country_id."'","","state_name asc");
  
  if(!empty($getStates))
  {
  $response = '<option value="0">Select State</option>';
  foreach ($getStates as $row) 
  {
    $response .= '<option value="'.$row->id.'">'.ucfirst($row->state_name).'</option>';
  }
  }
  else
  {
    $response = '<option value="0">Select State</option>';
  }
  echo $response;exit;
 
}

public function getCities()
{
  $state_id = $this->input->post('state_id');
  $city = $this->Crud_model->GetData("mst_cities",'',"state_id='".$state_id."'","","city_name asc");
  
  if(!empty($city))
  {
  $response = '<option value="0">Select City</option>';
  foreach ($city as $cities) 
  {
    $response .= '<option value="'.$cities->id.'">'.ucfirst($cities->city_name).'</option>';
  }
  }
  else
  {
  $response = '<option value="0">Select City</option>';
  }
  echo $response;exit;
}

public function update($id)
{
  if($_SESSION[SESSION_NAME]['type']=='Admin') 
  {
    $breadcrumbs='<ul class="breadcrumb">
                  <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                  </li>
                  <li class="active"><a href="'.site_url('Users/index').'">Manage Users</a></li>
                  <li class="active">Update Employee</li>
                  </ul>';
  }
  else
  {
    $breadcrumbs='<ul class="breadcrumb">
                  <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                  </li>
                  <li class="active"><a href="'.site_url('Users/index').'">Manage Users</a></li>
                  <li class="active">Update User</li>
                  </ul>';
  }

  $employee_data = $this->Crud_model->GetData('employees','',"id='".$id."'",'','','','1');
  
  $branch =$this->Crud_model->GetData('branches','',"status='Active'"); 

  $country=$this->Crud_model->GetData('mst_countries','',"status='Active'",'','country_name');

  $state=$this->Crud_model->GetData('mst_states','',"country_id='".$employee_data->country_id."' and status='Active'",'','state_name');

  $city=$this->Crud_model->GetData('mst_cities','',"state_id='".$employee_data->state_id."' and status='Active'",'','city_name');

  if($_SESSION[SESSION_NAME]['type']=='Admin')
  {
    $con = "status='Active'  and (designation_name!='Admin' || designation_name!='admin') and is_delete='No'";
  }
  else
  {
    $con = "status='Active' and id!='21'  and  is_delete='No'";
  }
  if($_SESSION[SESSION_NAME]['type']=='Admin')
  {
    $heading = 'Update User';
  }
  else
  {
    $heading = 'Update User';
  }
  $designations=$this->Crud_model->GetData('mst_designations','',$con,'','designation_name');


  $data = array(
    'heading' => $heading,
    'button' => 'Update',
    'action' => site_url('Users/update_action/'.$id),
    'branch' => $branch,
    'country' => $country,
    'state' => $state,
    'city' => $city,
    'designations' => $designations,
    'employee_data' => $employee_data,
    'breadcrumbs' => $breadcrumbs,
    'name' => $employee_data->name,
    'email' => $employee_data->email,
    'mobile' => $employee_data->mobile,
    'address' => $employee_data->address,
    'country_id' => $employee_data->country_id,
    'state_id' => $employee_data->state_id,
    'city_id' => $employee_data->city_id,
    'pincode' => $employee_data->pincode,
    'branch_id' => $employee_data->branch_id,
    'designation_id' => $employee_data->designation_id,
    'image' => $employee_data->image,
    'invoice_serial_number_series' => $employee_data->invoice_serial_number_series,
    'dispatch_note_serial_number_series' => $employee_data->dispatch_note_serial_number_series,
    'gst_number' => $employee_data->gst_number,
  );

  $this->load->view('employees/employees_form',$data);
}

public function update_action($id)
{
  $this->validation_rules($id);
  if ($this->form_validation->run() == FALSE)
  {
    $this->update($id);
  }
  else  
  {

  if($_SESSION[SESSION_NAME]['type']=='Admin')
    {
      $branch_id = $this->input->post('branch_id',TRUE);
      $created = 0;
    }
    else
    {
      $created =  $_SESSION[SESSION_NAME]['id'];
      $branch_id = $_SESSION[SESSION_NAME]['branch_id'];
    }
  $con = "id='".$id."'";
  if($_FILES['image']['name']!='')
  {
    $file_element_name = 'image';    
    $_POST['image']= 'AT_'.rand(0000,9999).$_FILES['image']['name'];
    $config2['image_library'] = 'gd2';
    $config2['source_image'] =  $_FILES['image']['tmp_name'];
    $config2['new_image'] =   getcwd().'/uploads/employee_images/'.$_POST['image'];
    $config2['upload_path'] =  getcwd().'/uploads/employee_images/'.$_POST['image'];
    $config2['allowed_types'] = 'JPG|PNG|jpg|png|jpeg|JPEG';
    $config2['maintain_ratio'] = TRUE;
    $config2['max_size'] = '1024';
    $config2['width'] = "200";
    $config2['height'] = "300";

    $this->image_lib->initialize($config2);
    if(!$this->image_lib->resize())
    {
      echo('<pre>');
      echo ($this->image_lib->display_errors());
    }
    else
    { 
      $image= $_POST['image'];
    }
   
    $data = array(
      'name' => ucfirst($this->input->post('name',TRUE)),
      'email' =>$this->input->post('email',TRUE),
      'mobile' =>$this->input->post('mobile',TRUE),
      'country_id' =>$this->input->post('country_id',TRUE),
      'state_id' =>$this->input->post('state_id',TRUE),
      'city_id' =>$this->input->post('city_id',TRUE),
      'address' =>$this->input->post('address',TRUE),
      'pincode' =>$this->input->post('pincode',TRUE),
      'branch_id' =>$branch_id,
      'created_by' =>  $created,
      'designation_id' =>$this->input->post('designation_id',TRUE),
      'image' => $image,
      'invoice_serial_number_series' => $this->input->post('invoice_serial_number_series',TRUE),
      'dispatch_note_serial_number_series' => $this->input->post('dispatch_note_serial_number_series',TRUE),
      'gst_number' => $this->input->post('gst_number',TRUE),
      'modified'=> date('Y-m-d H:i:s'),                
    );
    $this->Crud_model->SaveData("employees",$data,"id='".$id."'");
    unlink('uploads/employee_images/'.$_POST['oldimage']); 
  }
  else
  {
    $image = $this->input->post('oldimage');
  }
  $data = array(
    'name' => ucfirst($this->input->post('name',TRUE)),
    'email' =>$this->input->post('email',TRUE),
    'mobile' =>$this->input->post('mobile',TRUE),
    'country_id' =>$this->input->post('country_id',TRUE),
    'state_id' =>$this->input->post('state_id',TRUE),
    'city_id' =>$this->input->post('city_id',TRUE),
    'address' =>$this->input->post('address',TRUE),
    'pincode' =>$this->input->post('pincode',TRUE),
    'branch_id' =>$branch_id,
    'created_by' =>  $_SESSION[SESSION_NAME]['id'],
    'designation_id' =>$this->input->post('designation_id',TRUE),
    'image' => $image,
    'invoice_serial_number_series' => $this->input->post('invoice_serial_number_series',TRUE),
    'dispatch_note_serial_number_series' => $this->input->post('dispatch_note_serial_number_series',TRUE),
    'gst_number' => $this->input->post('gst_number',TRUE),
    'modified'=> date('Y-m-d H:i:s'),       
  );
   
  $this->Crud_model->SaveData('employees',$data,$con);
  if($_FILES['image']['error']==0)
  {
    unlink('uploads/employee_images/'.$_POST['oldimage']); 
  } 
  $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Record updated successfully</p></div>');
  redirect('Users/index');
  }
}

public function changeStatus()
{
  $change_status = $this->Crud_model->GetData('employees','',"id='".$_POST['id']."'",'','','','row');

  if($change_status->status=='Active')
  {
      $this->Crud_model->SaveData('employees',array('status'=>'Inactive'),"id='".$_POST['id']."'");
  }
  else
  {
      $this->Crud_model->SaveData('employees',array('status'=>'Active'),"id='".$_POST['id']."'");
  }
  $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
  redirect('Users/index');
}

public function delete()
{
  $con = "id='".$_POST['id']."'";
  $row = $this->Crud_model->GetData('employees','',$con,'','','','1'); 
  unlink('uploads/employee_images/'.$row->image);
  $this->Crud_model->DeleteData('employees',$con);
  $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
  redirect('Users/index');
}

public function validation_rules($id)
{
    $table = 'employees';
    $cond = "mobile='".$this->input->post('mobile',TRUE)."' and id!='$id'";
    $row = $this->Crud_model->GetData($table,'',$cond);    
    $count = count($row); 
    if($count==0)
    {
        $is_unique = "";
    }
    else {
        $is_unique = "|is_unique[employees.mobile]";
    } 

    $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required'.$is_unique,
    array(
        'required'      => 'Required',
        'is_unique'     => 'Already Exist'
      ));

    $cond = "email='".$this->input->post('email',TRUE)."' and id!='$id'";
    $row = $this->Crud_model->GetData($table,'',$cond); 
    $count = count($row); 
    if($count==0)
    {
        $is_unique = "";
    }
    else {
        $is_unique = "|is_unique[employees.email]";
    } 
    $this->form_validation->set_rules('email', 'Email', 'trim|required'.$is_unique,
    array(
        'required'      => 'Required',
        'is_unique'     => 'Already Exist'
      ));

    $this->form_validation->set_rules('id', 'id', 'trim');
    $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');   
}

public function view($id)
{
  if($_SESSION[SESSION_NAME]['type']=='Admin')
  {
    $breadcrumbs='<ul class="breadcrumb">
                  <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                  </li>
                  <li class="active"><a href="'.site_url('Users/index').'">Manage Users</a></li>
                  <li class="active">View User</li>
                  </ul>';
  }
  else
  {
    $breadcrumbs='<ul class="breadcrumb">
                  <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                  </li>
                  <li class="active"><a href="'.site_url('Users/index').'">Manage Users</a></li>
                  <li class="active">View User</li>
                  </ul>';
  }

  $employee_data = $this->Crud_model->GetData('employees','',"id='".$id."'",'','','','1');

  $branch = $this->Crud_model->GetData('branches','',"id='".$employee_data->branch_id."' and status='Active' and is_delete='No'",'','','','1');

  $designation = $this->Crud_model->GetData('mst_designations','',"id='".$employee_data->designation_id."'",'','','','1');

  $country = $this->Crud_model->GetData('mst_countries','',"id='".$employee_data->country_id."'",'','','','1');

  $state = $this->Crud_model->GetData('mst_states','',"id='".$employee_data->state_id."'",'','','','1');

  $city = $this->Crud_model->GetData('mst_cities','',"id='".$employee_data->city_id."'",'','','','1');

  if($_SESSION[SESSION_NAME]['type']=='Admin')
  {
    $con = "id!='".$id."'";
  }
  else
  {
    $con = "id!='".$id."' and type='User'";
  }

  $allEmployees = $this->Crud_model->GetData('employees','',$con,'','','','');

  $data = array(
                'breadcrumbs' => $breadcrumbs,
                'employee_data' => $employee_data,
                'branch' => $branch,
                'designation' => $designation,
                'country' => $country,
                'state' => $state,
                'city' => $city,
                'allEmployees' => $allEmployees,
                );

  $this->load->view('employees/employees_view',$data);
}

public function savemyBranch()
{
  $record= $this->Crud_model->GetData('branches','',"branch_title='".$_POST['branch_title']."'",'','','','1');

  if(count($record) > 0)
  {
     echo "0";
  }
  else
  {
    $data = array(
      'branch_title' => ucwords($_POST['branch_title']),
      'status' => "Active",
      'is_delete' => "No",
      'created' => date("Y-m-d H:i:s"),
     );
    $this->Crud_model->SaveData("branches",$data);  
    $branchs = $this->Crud_model->GetData("branches",'',"status='Active' and is_delete='No'","","branch_title asc");
 
    $response = '<option value="0">Select Branch</option>';
    foreach ($branchs as $branch) 
    {
      $response .= '<option value="'.$branch->id.'">'.ucfirst($branch->branch_title).'</option>';
    }
    
      echo $response ;exit; 
    
   
  }
}
public function savemyDesignation()
{
  $record= $this->Crud_model->GetData('mst_designations','',"designation_name='".$_POST['designation_title']."'",'','','','1');

  if(count($record) > 0)
  {
     echo "0";
  }
  else
  {
    $data = array(
      'designation_name' => ucwords($_POST['designation_title']),
      'status' => "Active",
      'is_delete' => "No",
      'modified_by' => $_SESSION[SESSION_NAME]['id'],
      'created' => date("Y-m-d H:i:s"),
     );
    $this->Crud_model->SaveData("mst_designations",$data);  

  $mst_designations = $this->Crud_model->GetData("mst_designations",'',"status='Active' and is_delete='No'","","designation_name asc");
 
  $response = '<option value="0">Select Designation</option>';
  foreach ($mst_designations as $designation) 
  {
    $response .= '<option value="'.$designation->id.'">'.ucfirst($designation->designation_name).'</option>';
  }
  
    echo $response ;exit; 
  }
}

public function export()
{
  if($_SESSION[SESSION_NAME]['type']=='Admin')
  {
    $branch_id = $_POST['branch_id'];
    $condition="employees.type='Employee'";
    if($branch_id!='')
    {
      $condition .= "and employees.branch_id='".$branch_id."'";
    }
  }
  else
  {
    $condition = "employees.type='User'";
  }

    $results = $this->Users_model->ExportCSV($condition);    
    
    $FileTitle='Employee Report';
    $this->load->library('excel');
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Report Sheet');
    
    $this->excel->getActiveSheet()->setCellValue('A1', 'Employee');
    $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
    if($_SESSION[SESSION_NAME]['type']=='Admin')
    {
      $this->excel->getActiveSheet()->setCellValue('B3', 'Branch');     
    }
    $this->excel->getActiveSheet()->setCellValue('C3', 'Employee Name');
    $this->excel->getActiveSheet()->setCellValue('D3', 'Designation');
    $this->excel->getActiveSheet()->setCellValue('E3', 'Email');
    $this->excel->getActiveSheet()->setCellValue('F3', 'Mobile');
    
    $a='4'; $sr = '1';    
    foreach ($results as $result) 
    {
      if(!empty($result->name))
      {
        $name = $result->name;
      } else {
        $name = ' - ';
      } 
      $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
      if($_SESSION[SESSION_NAME]['type']=='Admin')
      {
        $this->excel->getActiveSheet()->setCellValue('B'.$a, $result->branch_title);
      }
      $this->excel->getActiveSheet()->setCellValue('C'.$a, $name);
      $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->designation_name);
      $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->email);
      $this->excel->getActiveSheet()->setCellValue('F'.$a, $result->mobile);
      $sr++; $a++;
    }
    
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setSize(12);

    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
    
    $this->excel->getActiveSheet()->mergeCells('A1:H1');
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $filename=''.$FileTitle.'.xls'; //save our workbook as this file name
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    ob_clean();
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
    $objWriter->save('php://output'); 
}


public function get_designation()
{    
  $id = $this->input->post('id'); 
  $cond = "branch_id ='".$id."' and designation_id='21'";
  $getEmployee = $this->Crud_model->GetData('employees','',$cond);

  if(!empty($getEmployee)){
      $designations=$this->Crud_model->GetData('mst_designations','',"status='Active' and id!='21'  and (designation_name!='Admin' || designation_name!='admin') and is_delete='No'","","designation_name asc");

       $html="<option value=''>-- Select Designation--</option>";
  
    foreach($designations as $getdesignation)
     {
      //print_r($states);
      $html.="<option value=".$getdesignation->id.">".$getdesignation->designation_name."</option>";
      
     }
     echo $html;
  }else{
    $designations=$this->Crud_model->GetData('mst_designations','',"status='Active'  and (designation_name!='Admin' || designation_name!='admin') and is_delete='No'","","designation_name asc");

       $html="<option value=''>-- Select Designation--</option>";
  
    foreach($designations as $getdesignation)
     {
      //print_r($states);
      $html.="<option value=".$getdesignation->id.">".$getdesignation->designation_name."</option>";
      
     }
     echo $html;
  }

  
}


















}