<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('Crud_model');
    $this->load->library('image_lib');
    $this->image_lib->resize();  
    $this->load->database();
  }
  
  public function index()
  {
    $this->load->view('login/login');
  }

  public function login()
  { 
     $email = $this->input->post('email'); 
     $password = $this->input->post('password');

     $cond = "email='".$email."' && password='".md5($password)."'";
     $chk_login = $this->Crud_model->GetData("employees",'',$cond,'','','','single');
     //$user_role = $this->Crud_model->GetData("mst_designations",'',"id='".$chk_login->designation_id."'",'','','','single');
     //$allow_ips = $this->Crud_model->GetData("mst_allow_ips",'',"status='active' and ip_address='".$_SERVER['REMOTE_ADDR']."'",'','','','single');
     
     //if(!empty($allow_ips)){ 

  	   if(!empty($chk_login))
  	   { 
         if($chk_login->type!='Superadmin' && $chk_login->type!='Admin')
         {
            $data = array(
              'branch_id' => $chk_login->branch_id,
              'employee_id' => $chk_login->id,
              'login_time' => date("Y-m-d H:i:s"),
              'login_ip' => $_SERVER['REMOTE_ADDR'],
              'user_type' => $chk_login->type,
              'status' => 'Web',
            );      
            $this->Crud_model->SaveData('login_logs',$data);
        }

        if($chk_login->type!='Superadmin')
        {
          $user_role = $this->Crud_model->GetData("mst_designations",'',"id='".$chk_login->designation_id."'",'','','','single');
          $condPermissoion = "ra.ra_designation_id='".$chk_login->designation_id."' and ra.status='Active'";
          $moduleAction = $this->Crud_model->getRolePermissions($condPermissoion);
          //print_r($this->db->last_query());exit;
          $actionvalue = array();
          foreach ($moduleAction as $key => $value ) 
          {
            $actionvalue[] = $value->module_name;

          }
          $getMenus=array();
          foreach ($actionvalue as $val){
            $cond="r.status='Active' and rm.module_name='".$val."' and ra.ra_designation_id='".$chk_login->designation_id."'";
            $getMenus[]=$this->Crud_model->getRolePermissionSubMenu($cond);
            //print_r($this->db->last_query());
            //echo "<br/>";
          }

  	     $sess_array[SESSION_NAME] = array(
  	      'id' => $chk_login->id,
  	      'name' => $chk_login->name,
  	      'email' => $chk_login->email,  
          'mobile' => $chk_login->mobile,
          'type' => $chk_login->type,
          'state_id' => $chk_login->state_id,
          'branch_id' => $chk_login->branch_id,
  	      'designation_id' => $chk_login->designation_id,
  	      'action_list' => $actionvalue,
          'moduleAction' => $moduleAction,      
          'getMenus' => $getMenus,      
  	       'gst_number' => $chk_login->gst_number,
           'invoice_serial_number_series' => $chk_login->invoice_serial_number_series,
           'address' => $chk_login->address,
        ); 

        $this->session->set_userdata($sess_array); 
        //Warehouse User
        if($chk_login->designation_id == 31)
        {
          echo "2";exit;
        } else {
          echo "0"; exit; 
        }
  	    }
  	    else
  	    {
  	      echo "1";exit;
  	    }
	   }
  	 else
  	 {
  	 	if (!empty($chk_login))
  	   {        
  	    $sess_array[SESSION_NAME] = array(
  	      'id' => $chk_login->id,
  	      'name' => $chk_login->name,
  	      'email' => $chk_login->email,      
  	      'type' => $chk_login->type,
          'designation_id' => $chk_login->designation_id,
  	    );  
  	    
  	    $this->session->set_userdata($sess_array); 
  	    echo "0"; exit;
  	    }
  	    else
  	    {
  	      echo "1";exit;
  	    }
  	 }
    /*}
    else
    {
      echo "2";exit;
    }*/
  }

/*
public function imageut()
{
 $login = $this->session->userdata();
 $this->session->unset_userdata($login);
 session_destroy();
 redirect('Welcome/index');
}*/

public function forgotPass()
{
  $condition = "email='".$this->input->post("email")."' and type='Admin'";
  $checkUser = $this->Crud_model->GetData("employees",'',$condition,'','','','single');
  if(!empty($checkUser))
  {
    $rand ='pass_'.rand(1111,9999); 
    $data_users= array(
      'password' => md5($rand),
      'showpassword' => $rand,
    );

    $updatePass = $this->Crud_model->SaveData('employees',$data_users,$condition);
    echo "1";exit(); 
  }
  else
  {
    echo "0";exit;
  }
} 

public function logout()
{
 $login = $this->session->userdata();
 $this->session->unset_userdata($login);
 session_destroy();
 redirect('Welcome/index');
}
public function profile()
  {
    $loginInfo=$this->Crud_model->GetData('employees','',"id='".$_SESSION[SESSION_NAME]['id']."'",'','','','1');
    $data=array(
          'heading'=>'Update Profile',
          'loginInfo'=>$loginInfo,
          );
    $this->load->view('common/header');
    $this->load->view('common/left_panel');
    $this->load->view('profile/profile',$data);
    $this->load->view('common/footer');
  }

  function duplicateEmailMobile()
  {
    $loginEmail=$this->Crud_model->GetData('employees','id,email',"email='".$this->input->post('email')."' and id!='".$this->session->userdata('id')."'",'','','','1');
    $loginmobile=$this->Crud_model->GetData('employees','id,email',"mobile='".$this->input->post('mobile')."' and id!='".$this->session->userdata('id')."'",'','','','1');

    if(!empty($loginEmail))
    {
      echo "1";exit;
    }
    else if(!empty($loginmobile))
    {
      echo "2";exit;
    }

  }

  function update_action()
  {
    if($_FILES['image']['name']!='')
    {   
      $_POST['image']= rand(0000,9999)."_".$_FILES['image']['name'];
      $config2['image_library'] = 'gd2';
      $config2['source_image'] =  $_FILES['image']['tmp_name'];
      $config2['new_image'] =   getcwd().'/uploads/employee_images/'.$_POST['image'];  
      $config2['upload_path'] =  getcwd().'/uploads/employee_images/';  
      $config2['allowed_types'] = 'JPG|PNG|JPEG|jpg|png|jpeg';
      $config2['maintain_ratio'] = FALSE; 
      $this->image_lib->initialize($config2);
      if(!$this->image_lib->resize())
      {
          echo('<pre>');
                echo ($this->image_lib->display_errors());
               exit;
      } 
      else 
      {
          unlink("uploads/employee_images/".$_POST['old_image']); 
          $_POST['image']=$_POST['image']; 
      }
    }
    else
    {
      $_POST['image']=$_POST['old_image']; 
    }

    $data = array(
          'image'=>$_POST['image'],
          'name'=>ucfirst($this->input->post('name')),
          'email'=>$this->input->post('email'),
          'mobile'=>$this->input->post('mobile'),
          'address'=>$this->input->post('address'),
          );
    $this->Crud_model->SaveData('employees',$data,"id='".$_SESSION[SESSION_NAME]['id']."'");
    $_SESSION[SESSION_NAME]['id']=$_SESSION[SESSION_NAME]['id'];
    $_SESSION[SESSION_NAME]['name']=ucfirst($this->input->post('name'));
    $_SESSION[SESSION_NAME]['email']=$this->input->post('email');
    $_SESSION[SESSION_NAME]['mobile']=$this->input->post('mobile');
    $_SESSION[SESSION_NAME]['designation_id']=$this->input->post('designation_id');
  
    $this->session->set_flashdata('message','Profile has been updated successfully');
    redirect('Welcome/profile');
  }

  public function changePassword()
  {
    
    $data=array(
          'heading'=>'Change Password',
          
          );
    $this->load->view('common/header');    
    $this->load->view('common/left_panel');
    $this->load->view('profile/changePassword',$data);
    $this->load->view('common/footer');
  }

  public function change_password()
  {
    $Info=$this->Crud_model->GetData('employees','',"id='".$_SESSION[SESSION_NAME]['id']."' and password='".md5($_POST['old_password'])."'",'','','','1');
      
    if(!empty($Info))
    {
      $condition="id='".$_SESSION[SESSION_NAME]['id']."'";
      $pass=md5($_POST['new_password']);
      $data=array(
      'password'=>$pass,
      'show_password'=>$_POST['new_password'],
      );
      $this->Crud_model->SaveData("employees",$data,$condition);
      echo "0";exit;
    }
    else
    {
      echo "1";exit;
    }
  }

  public function change_password_action()
  {     
    $tableName="employees";
    $condition="id='".$_SESSION[SESSION_NAME]['id']."'";
    $pass=md5($_POST['new_password']);
            $data=array(
            'password'=>$pass,
            'show_password'=>$_POST['new_password'],
            );
            $this->Crud_model->SaveData($tableName,$data,$condition);
            redirect('Welcome/logout');
  }

}