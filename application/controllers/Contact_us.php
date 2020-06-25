<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_us extends CI_Controller {

  function __construct()
  {
  parent::__construct();
/*  $this->load->library(array('Sms'));*/
  $this->load->model('Contact_us_model');
  $this->load->database();
  $this->load->library('email');
  $this->load->library('Sms');
  $this->load->library('SendMail');
  $this->Sms = new Sms();
  $this->SendMail = new SendMail();  
  }
  
  public function enquiry()
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
              if($menu->value=='Unit_types')
              { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              }
          }
        }

        $breadcrumbs = "<ul class='breadcrumb'>
                        <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
                        <li class='active'>Manage Enquiry</li>
                        </ul>";
        $data = array('breadcrumbs' => $breadcrumbs , 'heading' => 'Manage Enquiry','addPermission'=>$add);
        $this->load->view('contact_us/contact_us_list',$data);
    }
    else
    {
      redirect('Dashboard');
    }
  }
public function ajax_list()
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
        if($menu->value=='Unit_types')
        { 
          if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
          if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
          if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
          if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
        }
    }
  }

  $con="type='Enquiry'";
  $Data = $this->Contact_us_model->get_datatables($con);
  $data = array();        
  foreach($Data as $row) 
  { 
    $btn = '';
     if(!empty($view))
     {
        $btn = '<span class="action-buttons"><a class="orange pointer" title="View" data-toggle="modal" data-target="#myModal1" onclick="getcontact('.$row->id.')"><i class="ace-icon fa fa-search-plus bigger-130"></i></a></span>';
     
      if(empty($row->reply))
      {
        $btn .= ' &nbsp;'.'<span class="action-buttons"><a class="blue pointer" title="reply" data-toggle="modal" data-target="#myModal"" onclick="get_contact_us('.$row->id.')"> Reply</a></span>';
      }
      else
      {
        $btn .=' &nbsp;'. '<span class="action-buttons"><a class="purple pointer" title="View" data-toggle="modal" data-target="#view_reply"" onclick="get_reply('.$row->id.')">View reply</a></span>';
      }
    }
    
    $nestedData = array();
    $nestedData[] = ucwords($row->name);
    $nestedData[] = $row->email;
    $nestedData[] = $row->comment;
    $nestedData[] = $btn;
    $data[] = $nestedData;
    $selected = '';
      }

      $output = array(
                  "draw" => $_POST['draw'],
                  "recordsTotal" => $this->Contact_us_model->count_all($con),
                  "recordsFiltered" => $this->Contact_us_model->count_filtered($con),
                  "data" => $data,
              );
     
      echo json_encode($output);
}
public function contactus()
{
  $id = $this->input->post('id');
  $getreply=$this->Admin_model->GetData("contact_us","name,reply,email","id='".$id."'");
    $settings = $this->Admin_model->GetData('settings','sitetitle,header_logo');
  $data = array(
        
        'getreply' => $getreply,
        'id' => $id,
        'reply' => ucwords($this->input->post('reply')),
        'created' => date('Y-m-d H:i:s'),
      );
    $this->Admin_model->SaveData("contact_us",$data,"id='".$id."'");
    $mail_body=$this->Admin_model->GetData("email","","title='contact us'");

        if(!empty($mail_body))
         {

            $mail_body[0]->subject=str_replace("{company_name}",ucfirst($settings[0]->sitetitle),$mail_body[0]->subject);
            $mail_body[0]->template=str_replace("{user_name}",ucfirst($getreply[0]->name),$mail_body[0]->template);
            $mail_body[0]->template=str_replace("{company_name}",ucfirst($settings[0]->sitetitle),$mail_body[0]->template);
             $mail_body[0]->template=str_replace("{reply}",ucfirst($this->input->post('reply')),$mail_body[0]->template);
            $mail_body[0]->template=str_replace("{Company Logo}","<img src='".base_url('uploads/logo/'.$settings[0]->header_logo)."'>",$mail_body[0]->template);
                
            $subject=$mail_body[0]->subject;
            $body=$mail_body[0]->template;
          
            $to = $getreply[0]->email;
            $MailData = array('mailoutbox_to'=>$to,'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body);

            $this->SendMail->Send($MailData);
          }
}

public function feedback()
  {   
    $breadcrumbs = "<ul class='breadcrumb'>
                    <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
                    <li class='active'>Manage Feedback</li>
                    </ul>";
    $data = array('breadcrumbs' => $breadcrumbs , 'heading' => 'Manage Feedback' );
    $this->load->view('contact_us/contact_us_feedback',$data);
  }
public function ajax_list_feed()
{
  $con="type='Feedback'";
  $Data = $this->Contact_us_model->get_datatables($con);
  $data = array();        
  foreach($Data as $row) 
  { 
     $btn = '<span class="action-buttons"><a class="orange pointer" title="View" data-toggle="modal" data-target="#myModal1" onclick="getcontact('.$row->id.')"><i class="ace-icon fa fa-search-plus bigger-130"></i></a></span>';
    if(empty($row->reply))
    {
      $btn .= ' &nbsp;'.'<span class="action-buttons"><a class="blue pointer" title="reply" data-toggle="modal" data-target="#myModal"" onclick="get_contact_us('.$row->id.')"> Reply</a></span>';
    }
    else
    {
      $btn .= ' &nbsp;'.'<span class="action-buttons"><a class="purple pointer" title="View" data-toggle="modal" data-target="#view_reply"" onclick="get_reply('.$row->id.')">View reply</a></span>';
    }
      $nestedData = array();
      $nestedData[] = ucwords($row->name);
      $nestedData[] = $row->email;
      $nestedData[] = $row->comment;
      $nestedData[] = $btn;
      $data[] = $nestedData;
      $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Contact_us_model->count_all($con),
                    "recordsFiltered" => $this->Contact_us_model->count_filtered($con),
                    "data" => $data,
                );
       
        echo json_encode($output);
}
public function complimentary()
  {   
    $breadcrumbs = "<ul class='breadcrumb'>
                    <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
                    <li class='active'>Manage Complimentary</li>
                    </ul>";
    $data = array('breadcrumbs' => $breadcrumbs , 'heading' => 'Manage Complimentary' );
    $this->load->view('contact_us/contact_us_complimentary',$data);
  }
public function ajax_list_comp()
{
  $con="type='Complimentary'";
  $Data = $this->Contact_us_model->get_datatables($con);
  $data = array();        
  foreach($Data as $row) 
  { 
     $btn = '<span class="action-buttons"><a class="orange pointer" title="View" data-toggle="modal" data-target="#myModal1" onclick="getcontact('.$row->id.')"><i class="ace-icon fa fa-search-plus bigger-130"></i></a></span>';

    if(empty($row->reply))
    {
      $btn .= ' &nbsp;'.'<span class="action-buttons"><a class="blue pointer" title="reply" data-toggle="modal" data-target="#myModal"" onclick="get_contact_us('.$row->id.')"> Reply</a></span>';
    }
    else
    {
      $btn .= ' &nbsp;'.'<span class="action-buttons"><a class="purple pointer" title="View" data-toggle="modal" data-target="#view_reply"" onclick="get_reply('.$row->id.')">View reply</a></span>';
    }
      $nestedData = array();
      $nestedData[] = ucwords($row->name);
      $nestedData[] = $row->email;
      $nestedData[] = $row->comment;
      $nestedData[] = $btn;
      $data[] = $nestedData;
      $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Contact_us_model->count_all($con),
                    "recordsFiltered" => $this->Contact_us_model->count_filtered($con),
                    "data" => $data,
                );
       
        echo json_encode($output);
}

public function get_reply()
{
  $id = $this->input->post('id');
  $getreply=$this->Admin_model->GetData("contact_us","reply","id='".$id."'");
  $data = array(
        'reply' => $getreply[0]->reply,
      );
    echo json_encode($data);exit;
    
}
public function view()
{ 
  $id = $this->input->post("id");
  $table="contact_us";
  $cond="id='".$id."'";
  $row = $this->Admin_model->GetData($table,'',$cond,'','','','1'); 
  $data = array('row' => $row );
  $this->load->view("contact_us/view",$data);
}
 
}?>
