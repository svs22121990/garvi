<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

function __construct()
{
parent::__construct();
$this->load->model('Faq_model');
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
  $export_button = '';
  if(!empty($_SESSION[SESSION_NAME]['getMenus']))
  { 
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Faq')
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
            <li>Manage Faq's</li>
            </ol>";
        $data=array(
          'heading' => "Manage Faq's ",
          'breadcrumbs'=>$breadcrumbs,
          'addPermission'=>$add
        );
        $this->load->view('faq/faq_list',$data);
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
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='Faq')
          { 
            if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
            if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
            if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
          }
      }
    }
    $getData = $this->Faq_model->get_datatables();
    
    $data = array();    
    foreach ($getData as $Data) 
    {    

       if(strlen($Data->answer) > 30)
       {
         $answer = substr($Data->answer, 0,30).'...';
       }
       else
       {
         $answer = $Data->answer;
       } 

       if(strlen($Data->question) > 30)
       {
         $question = substr($Data->question, 0,30).'...';
       }
       else
       {
         $question = $Data->question;
       }
    
       $btn = ''; 
       if(!empty($view)){ 
        $btn .= '<span class="action-buttons"><a class="orange pointer" title="View" data-toggle="modal" data-target="#myModal1" onclick="getFAQ('.$Data->id.')"><i class="ace-icon fa fa-search-plus bigger-130"></i></a></span>';
       }
       if(!empty($edit)){
        $btn .='<span class="action-buttons"><a href="'.site_url('Faq/update/'.base64_encode($Data->id)).'" title="Update"><i class="ace-icon fa fa-pencil bigger-130"></i></a></span>';
       } 
       
       if(!empty($delete)){ 
        $btn .=' &nbsp;'.'<span class="action-buttons"> '.anchor(site_url('Faq/delete/'.base64_encode($Data->id)),'<i class="ace-icon fa fa-trash-o bigger-130 red"></i></span>','onclick="javasciprt: return confirm(\'Are you sure to delete the record?\')" title="Delete"'); 
       }
       
       $status = '';
        if(!empty($act_status)){
           if($Data->status == 'Active')
           {
              $status = '<label class="label label-success arrowed-in arrowed-in-right">Active</label>';
           }
           else
           {
              $status = '<label class="label label-danger arrowed-in arrowed-in-right">Inactive</label>';
           }
        }
        $nestedData = array();
        $nestedData[] = ucwords($question);
        $nestedData[] = $answer;
        $nestedData[] = $Data->type;
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
    }

        $condition="";
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Faq_model->count_all('faqs',$condition),
          "recordsFiltered" => $this->Faq_model->count_filtered('faqs',$condition),
          "data" => $data,
        );
    //output to json format
    echo json_encode($output);
}
public function view()
{ 
  $id = $this->input->post("id");
  $table="faqs";
  $cond="id='".$id."'";
  $row = $this->Admin_model->GetData($table,'',$cond,'','','','1'); 
  $data = array('row' => $row );
  $this->load->view("faq/faqs_view",$data);
}


public function delete($id)
{ 
  $id = base64_decode($id);
  $table="faqs";
  $cond="id='".$id."'";
  $row = $this->Faq_model->GetData($table,'',$cond,'','','','1'); 

  
  $this->Faq_model->DeleteData("faqs","id='".$id."'");
  $this->session->set_flashdata('message', 'Faq has been deleted successfully');
  redirect('Faq');
}

public function create()
{  

   $breadcrumbs="<ol class='breadcrumb'>
       <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
        <li><a href='".site_url('Faq/index')."'>Manage Faq's</a></li>
        <li class='active'>Create Faq</li>
        </ol>";

    $parent_pages = $this->Admin_model->GetData("faqs","","status='Active'","","question asc");
    
    $data = array(
              'heading'=>'Create Faq',
              'button'=>'Create',
              'breadcrumbs'=>$breadcrumbs,
              'canBtn'=>'Cancel',
              'action'=>site_url('Faq/create_action'),
              'question' =>set_value('question'),
              'answer' =>set_value('answer'),
              'type' =>set_value('type'),
              'status' =>  set_value('status',$this->input->post('status')),
              'id' =>set_value('id'),
              'parent_pages' => $parent_pages,
         );

    $this->load->view('faq/faq_form',$data);
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
    $data = array(
        'question' => $this->input->post('question',TRUE),                              
        'answer' => $this->input->post('answer',TRUE),                               
        'type' => $this->input->post('type',TRUE),      
        'created'=> date('Y-m-d H:i:s'),
      ); 
  }
    
    $this->Admin_model->SaveData("faqs",$data);
    $this->session->set_flashdata('message', 'Faqs has been created successfully');
    redirect('Faq/create');

   
}

public function update($id)
{ 

if(!empty($id))
 {  
  $id=base64_decode($id);
  $table = 'faqs';
  $cond = "id='".$id."'";
  $row = $this->Faq_model->GetData($table,'',$cond,'','','','1');
  

 
  $breadcrumbs="<ol class='breadcrumb'>
        <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
        <li><a href='".site_url('Faq/index')."'>Manage Faq's</a></li>
        <li class='active'>Update Faq</li>
        </ol>";
  $parent_pages = $this->Admin_model->GetData("faqs","","status='Active'","","question asc");
  $data = array(
            'heading'=>'Update Faq',
            'button'=>'Update',
            'breadcrumbs'=>$breadcrumbs,
            'canBtn'=>'Cancel',
            'action'=>site_url('Faq/update_action/'.base64_encode($id)),
            'id' =>set_value('id',$row->id),
            'question' =>set_value('question',$row->question),
            'answer' =>set_value('answer',$row->answer),
            'type' =>set_value('type',$row->type),
            'status' =>set_value('status',$row->status),
            
            //'id' =>set_value('id'),
            'parent_pages' => $parent_pages,
            
             );  //print_r($data);exit;

    $this->load->view('faq/faq_form',$data);
 }
  else
  {
         
            redirect('Faq');
  }
}

public function update_action($id)
{

  $id=base64_decode($id);
 
  $this->_rules($id);
  if ($this->form_validation->run() == FALSE)
  {  
     $this->update(base64_encode($id));
  }
  else 
  {  
           
      $data = array(
                                     
        'question' => $this->input->post('question',TRUE),                              
        'answer' => $this->input->post('answer',TRUE), 
        'type' => $this->input->post('type',TRUE), 
        'status' => $this->input->post('status',TRUE),
        'modified'=> date('Y-m-d H:i:s'),
      );  
    
        $tablename = "faqs";
        $cond = "id='".$id."'";
        $this->Faq_model->SaveData($tablename,$data,$cond);   
        $this->session->set_flashdata('message', 'Faq has been updated successfully');
         redirect(site_url('Faq/update/'.base64_encode($id)));   
  }
}
public function _rules($id) 
{   
      $table = 'faqs';

       $this->form_validation->set_rules('type', ' type', 'trim|required',
            array(
                    'required'      => 'Required',
                 ));
        $this->form_validation->set_rules('question', 'question', 'trim|required',
            array(
                    'required'      => 'Required',
                    'is_unique'     => 'Already exists',
                 ));

        $this->form_validation->set_rules('answer', ' answer', 'trim|required',
            array(
                    'required'      => 'Required',
                 ));

      
      
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');
 }  
 
}
