<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends CI_Controller {

  function __construct()
  {
  parent::__construct();

  $this->load->database();
   $this->load->model('Email_model');
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
  if(!empty($_SESSION[SESSION_NAME]['getMenus']))
  { 
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
      { 
        foreach($row as $menu)
        { 
            if($menu->value=='Email')
            { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
            }
        }
      }
      $breadcrumbs="<ol class='breadcrumb'>
       <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
         <li class='active'>Manage Email</li>
        </ol>";

   // $row = $this->Admin_model->GetData("email","","","","id desc");

      $data = array(
          'heading'=>'Manage Email',
          'breadcrumbs'=>$breadcrumbs,
          'addPermission'=>$add
      );
    $this->load->view('email/email_list',$data);
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
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='Email')
          { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
          }
      }
    }

    $getData = $this->Email_model->get_datatables();
  
    $data = array();    
    foreach ($getData as $Data) 
    {    
      if($Data->subject != '') 
      {
       $sub = $Data->subject; 
      } 
      else
      {  
       $sub='-';  
      }
        $btn = '';
        if(!empty($view)){
        $btn = '<span class="action-buttons"><a  class="orange pointer" title="View" data-toggle="modal" data-target="#myModal" onclick="getView('.$Data->id.')"><i class="fa fa-search-plus bigger-130" aria-hidden="true"></i></a></span>';
        }
        if(!empty($edit)){
        $btn .=' &nbsp; '.'<span class="action-buttons"><a href="'.site_url('Email/update/'.base64_encode($Data->id)).'" title="Update"><i class="ace-icon fa fa-pencil bigger-130"></i></a></span>';
        }
        $nestedData = array();
        $nestedData[] = ucwords($Data->title);
        $nestedData[] = $sub;
        $nestedData[] = $btn;
        $data[] = $nestedData;
    }

        $condition="";
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Email_model->count_all('email',$condition),
          "recordsFiltered" => $this->Email_model->count_filtered('email',$condition),
          "data" => $data,
        );
    //output to json format
    echo json_encode($output);
}

function getView()
{ 
  $id = $this->input->post('id');
  $row = $this->Admin_model->GetData("email","","id='".$id."'");
  echo $row[0]->template;
}


public function update($id)
{ 
if(!empty($id))
 { 
  $id=base64_decode($id); 
    $table = 'email';
    $cond = "id='".$id."'"; 
    $row = $this->Admin_model->GetData($table,'',$cond,'','','','1'); 
    
   $breadcrumbs="<ol class='breadcrumb'>
        <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
          <li><a href='".site_url('Email/index')."'>Manage Email</a></li>
          <li class='active'>Update Email</li>
          </ol>";

  $data = array(
                'heading'=>'Update Email',
                'button'=>'Update',
                'breadcrumbs'=>$breadcrumbs,
                'canBtn'=>'Cancel',
                'action'=>site_url('Email/update_action'),
                'id' =>set_value('id',$row[0]->id),
                'title' =>set_value('title',$row[0]->title),
                'subject' =>set_value('subject',$row[0]->subject),
                'template' =>set_value('template',$row[0]->template),
                
        ); 

      $this->load->view('email/email_form',$data);
   }
    else
    {
         redirect('Tax');
    }
}
public function update_action()
{  

  $id=$this->input->post('id');
 
  $this->_rules($id);
  if ($this->form_validation->run() == FALSE)
  {  
     $this->update(base64_encode($id));
  }
  else 
  {
     $data = array(
            'title' => $this->input->post('title',TRUE),                               
            'subject' => $this->input->post('subject',TRUE),                               
            'template' => $_POST['template'],
            'created' => date("Y-m-d H:i:s"),
            'modified' => date("Y-m-d H:i:s"),
            );
       
          $this->Admin_model->SaveData("email",$data,"id='".$id."'");   
     
          $this->session->set_flashdata('message', '<p>Email has been updated successfully<p>');
           redirect('Email/update/'.base64_encode($id));
   }
}
public function _rules($id) 
{   
     
        $this->form_validation->set_rules('subject', 'subject', 'trim|required',
            array(
                    'required'      => 'Required',
                    
                 ));
        $this->form_validation->set_rules('template', 'template', 'trim|required',
            array(
                    'required'      => 'Required',
                    
                 )); 
      
        
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');
 }  
 
}
