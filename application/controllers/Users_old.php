<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('Crud_model');
    $this->load->model('Users_model');
    $this->load->library('image_lib');
    $this->image_lib->resize();  
    $this->load->database();
  }
  
  public function index()
  {
    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $edit = '';
    $view = '';
    $export_button = '0';
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
        //$branch = $this->Crud_model->GetData('branches','',"status='Active' and is_delete='No'",'','','','');
        //$export =anchor(site_url('Employees/export'),'<span title="Export" title="Export" class="fa fa-file-export "></span>');
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
          $actioncolumn ='6';
        }
        else
        {
          $actioncolumn ='6';
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
                    'breadcrumbs' =>$breadcrumbs,
                    'addPermission'=>$add,
                    'action' => site_url('Users/create'),
                    );

        $this->load->view('users/list',$data);
    }
    else
    {
      redirect('Dashboard');
    }  
  }



  public function ajax_manage_page()
  {
      /*$delete= '';
      $actstatus= '';
      $add = '';
      $act_add_existing_stock = '';
      $act_log_details = '';
      $act_transfer = '';
      $edit = '';
      $view = '';*/
      $Data = $this->Users_model->get_datatables(); 
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Users')
            { 
              if(!empty($row->act_add)){ $add='1'; }else{ $add='0'; }
              if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
              if(!empty($row->act_delete)){ $delete='1'; }else{ $delete='0'; }
              if(!empty($row->act_status)){  $actstatus='1';}else{ $actstatus='0';}
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
      foreach($Data as $row) 
      {
        $btn='';
        if(!empty($edit)){
          $btn = ('<a href="'.site_url('Users/update/'.$row->id).'" title="Edit" class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-pencil bigger-130"></i></a>');
            }
        if(!empty($delete)){

            $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
        }
           $status ='';
         if(!empty($actstatus)){ 

           if($row->status=='Active')
            {
                $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$row->id.")'> Active </a>";            
            }
            else
            {
                $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$row->id.")'> Inactive </a>";
            }
          }
      
          $no++;  
          $nestedData = array();
          $nestedData[] = $no;
          $nestedData[] = $row->name;
          $nestedData[] = $row->invoice_serial_number_series;
          $nestedData[] = $row->dispatch_note_serial_number_series;
          $nestedData[] = $row->gst_number;
          $nestedData[] = $status;
          $nestedData[] = $btn;
          $data[] = $nestedData;
          $selected = '';
      }

      $output = array(
                  "draw" => $_POST['draw'],
                  "recordsTotal" => $this->Users_model->count_all(),
                  "recordsFiltered" => $this->Users_model->count_filtered(),
                  "data" => $data,
              );
     
      echo json_encode($output);
  }
 
  public function changeStatus(){
      //print_r($_POST);exit;
      $change_status = $this->Crud_model->GetData('mst_users','',"id='".$_POST['id']."'",'','','','row');

      if($change_status->status=='Active')
      {
          $this->Crud_model->SaveData('mst_users',array('status'=>'Inactive'),"id='".$_POST['id']."'");
      }
      else
      {
          $this->Crud_model->SaveData('mst_users',array('status'=>'Active'),"id='".$_POST['id']."'");
      }
      $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
      redirect('Users/index');
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
                    <li class="active">Create User</li>
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

    //$branch =$this->Crud_model->GetData('branches','',"status='Active' and is_delete='No'","","branch_title asc"); 

    $country=$this->Crud_model->GetData('mst_countries','',"status='Active'","","country_name asc");

    $state=$this->Crud_model->GetData('mst_states','',"status='Active'","","state_name asc");

    $city=$this->Crud_model->GetData('mst_cities','',"status='Active'","","city_name asc");

    /*if($_SESSION[SESSION_NAME]['type']=='Admin')
    {
      $con = "mst_designations.status='Active' and (designation_name!='Admin' || designation_name!='admin') and is_delete='No'";
    }
    else
    {
      $con = "status='Active' and id!='21' and is_delete='No'";
    }*/
    if($_SESSION[SESSION_NAME]['type']=='Admin')
    {
      $heading = 'Create User';
    }
    else
    {
      $heading = 'Create User';
    }
    //$designations=$this->Crud_model->GetData('mst_designations','',$con,"","designation_name asc");

    $data = array(
      'heading' => $heading,
      'button' => 'Create',
      'action' => site_url('Users/create_action'),
      'country' => $country,
      'state' => $state,
      'city' => $city,
      'breadcrumbs' => $breadcrumbs,
      'name' =>  set_value('name',$this->input->post('name')),
      'invoice_serial_number_series' =>  set_value('invoice_serial_number_series',$this->input->post('invoice_serial_number_series')),
      'dispatch_note_serial_number_series' =>  set_value('dispatch_note_serial_number_series',$this->input->post('dispatch_note_serial_number_series')),
      'gst_number' =>  set_value('gst_number',$this->input->post('gst_number')),
      'username' =>  set_value('username',$this->input->post('username')),
      'address' =>  set_value('address',$this->input->post('address')),
      'country_id' =>  set_value('country_id',$this->input->post('country_id')),
      'state_id' =>  set_value('state_id',$this->input->post('state_id')),   
      'city_id' =>  set_value('city_id',$this->input->post('city_id')),   
      'pincode' =>  set_value('pincode',$this->input->post('pincode')),   
    );

    $this->load->view('users/form',$data);
  }

  public function create_action()
  {
    $id=0;
    $this->validation_rules($id);
    if ($this->form_validation->run() == FALSE)
    {
       $this->create(); 
    }
    else
    {  
        if($_SESSION[SESSION_NAME]['type']=='Admin')
        {
          $type = 'User';
          //$branch_id = $this->input->post('branch_id');
          $created = 0;
        }
        else
        {
          $type = 'User';
          //$branch_id = $_SESSION[SESSION_NAME]['branch_id'];
          $created =  $_SESSION[SESSION_NAME]['id'];
        }
        $data = array(
          'name' => $this->input->post('name'),
          'invoice_serial_number_series' =>  $this->input->post('invoice_serial_number_series'),
          'dispatch_note_serial_number_series' =>  $this->input->post('dispatch_note_serial_number_series'),
          'gst_number' =>  $this->input->post('gst_number'),
          'username' =>  $this->input->post('username'),
          'password' => md5('password'),
          'show_password' => 'pass123',
          'country_id' => $this->input->post('country_id'),
          'state_id' => $this->input->post('state_id'),
          'city_id' => $this->input->post('city_id'),
          'address' => $this->input->post('address'),
          'pincode' => $this->input->post('pincode'),
          'status' => 'Active',
          'created_by' =>  $created,  
          'created' => date("Y-m-d H:i:s"),
        );
        //print_r($data);exit;
        $this->Crud_model->SaveData('mst_users',$data);
        /*$password = 'pass123';
        $branches = $this->Crud_model->GetData("branches",'',"id='".$this->input->post('branch_id')."'",'','','','1');
        $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='create_account'");
        $imgpath = base_url('uploads/employee_images/'.$image);
        if(!empty($mail_body))
        {
          $mail_body[0]->mail_body=str_replace("{branch}",ucfirst($branches->branch_title),$mail_body[0]->mail_body);
          $mail_body[0]->mail_body=str_replace("{username}",ucwords($_POST['name']),$mail_body[0]->mail_body);
          $mail_body[0]->mail_body=str_replace("{email}",$_POST['email'],$mail_body[0]->mail_body);
          $mail_body[0]->mail_body=str_replace("{password}",$password,$mail_body[0]->mail_body);
          $mail_body[0]->mail_body=str_replace("{imgurl}",$imgpath,$mail_body[0]->mail_body);
          $subject=$mail_body[0]->mail_subject;
          $body=$mail_body[0]->mail_body;
          //print_r($body);exit;
          $MailData = array('mailoutbox_to'=>$_POST['email'],'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body[0]->type);*/
          //$Send=$this->SendMail->Send($MailData);
          //$this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>You are registered successfully, Please check your email</p></div>');
        /*}
        else
        { 
          $this->session->set_flashdata("message",'<div class="label label-success text-center" style="margin-bottom:0px;"><p>Error in sending email...</p></div>'); 
        }*/

        $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Record created successfully</p></div>');
        redirect('Users/index');
    }
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

  public function validation_rules($id)
  {
      $table = 'mst_users';
      $cond = "name='".$this->input->post('name',TRUE)."' and id!='$id'";
      $row = $this->Crud_model->GetData($table,'',$cond);    
      $count = count($row); 
      if($count==0)
      {
          $is_unique = "";
      }
      else {
          $is_unique = "|is_unique[mst_users.name]";
      } 

      $this->form_validation->set_rules('name', 'Name', 'trim|required'.$is_unique,
      array(
          'required'      => 'Required',
          'is_unique'     => 'Already Exist'
        ));

      /*$cond = "email='".$this->input->post('email',TRUE)."' and id!='$id'";
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
        ));*/

      $this->form_validation->set_rules('id', 'id', 'trim');
      $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');   
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
                    <li class="active">Update Users</li>
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

    $employee_data = $this->Crud_model->GetData('mst_users','',"id='".$id."'",'','','','1');
    
    //$branch =$this->Crud_model->GetData('branches','',"status='Active'"); 

    $country=$this->Crud_model->GetData('mst_countries','',"status='Active'",'','country_name');

    $state=$this->Crud_model->GetData('mst_states','',"country_id='".$employee_data->country_id."' and status='Active'",'','state_name');

    $city=$this->Crud_model->GetData('mst_cities','',"state_id='".$employee_data->state_id."' and status='Active'",'','city_name');

    /*if($_SESSION[SESSION_NAME]['type']=='Admin')
    {
      $con = "status='Active'  and (designation_name!='Admin' || designation_name!='admin') and is_delete='No'";
    }
    else
    {
      $con = "status='Active' and id!='21'  and  is_delete='No'";
    }*/
    if($_SESSION[SESSION_NAME]['type']=='Admin')
    {
      $heading = 'Update User';
    }
    else
    {
      $heading = 'Update User';
    }
    //$designations=$this->Crud_model->GetData('mst_designations','',$con,'','designation_name');

    $data = array(
      'heading' => $heading,
      'button' => 'Update',
      'action' => site_url('Users/update_action/'.$id),
      'country' => $country,
      'state' => $state,
      'city' => $city,
      'employee_data' => $employee_data,
      'breadcrumbs' => $breadcrumbs,
      'name' => $employee_data->name,
      'invoice_serial_number_series' => $employee_data->invoice_serial_number_series,
      'dispatch_note_serial_number_series' => $employee_data->dispatch_note_serial_number_series,
      'gst_number' => $employee_data->gst_number,
      'username' => $employee_data->username,
      'address' => $employee_data->address,
      'country_id' => $employee_data->country_id,
      'state_id' => $employee_data->state_id,
      'city_id' => $employee_data->city_id,
      'pincode' => $employee_data->pincode
    );

    $this->load->view('users/form',$data);
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
        //$branch_id = $this->input->post('branch_id',TRUE);
        $created = 0;
      }
      else
      {
        //$created =  $_SESSION[SESSION_NAME]['id'];
        $branch_id = $_SESSION[SESSION_NAME]['branch_id'];
      }
      $con = "id='".$id."'";
  
      $data = array(
        'name' => ucfirst($this->input->post('name',TRUE)),
        'country_id' =>$this->input->post('country_id',TRUE),
        'state_id' =>$this->input->post('state_id',TRUE),
        'city_id' =>$this->input->post('city_id',TRUE),
        'address' =>$this->input->post('address',TRUE),
        'pincode' =>$this->input->post('pincode',TRUE),
        'modified'=> date('Y-m-d H:i:s'),       
      );
     
      $this->Crud_model->SaveData('mst_users',$data,$con);
    
      $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Record updated successfully</p></div>');
      redirect('Users/index');
    }
  }

  public function delete()
  {
    $con = "id='".$_POST['id']."'";
    //$row = $this->Crud_model->GetData('mst_users','',$con,'','','','1'); 
    //unlink('uploads/employee_images/'.$row->image);
    $this->Crud_model->DeleteData('mst_users',$con);
    $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
    redirect('Users/index');
  }

}