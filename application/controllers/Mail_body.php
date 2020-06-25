<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_body extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('Mail_body_model');
  $this->load->database();
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
              if($menu->value=='Mail_body')
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
                    <li class='active'>Manage Mail Body</li>
                    </ul>";

        $country_data = $this->Crud_model->GetData('mst_countries');
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '3' ,'ajax_manage_page' => site_url('Mail_body/ajax_manage_page'),'heading' => 'Manage Mail Body','country_data'=>$country_data);
        $this->load->view('mail_body/list',$data);
    }
    else
    {
      redirect('Dashboard');
    }
  }



    public function ajax_manage_page()
    {
        
        $edit = '';
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
        { 
          foreach($menu as $row)
          {

              if($row->value=='Mail_body')
              { 
                if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
              }
          }
        }

        $Data = $this->Mail_body_model->get_datatables(); 
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
            $btn = '';

            if(!empty($edit)){
            $btn = ('<a href='.site_url("Mail_body/update/".$row->id).' class="green" ><span class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-pencil bigger-130"></i></span></a>');   
            }           
            
            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->type;
            $nestedData[] = $row->mail_subject;
            $nestedData[] = $row->mail_body;      
            $nestedData[] = $btn;       
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Mail_body_model->count_all(),
                    "recordsFiltered" => $this->Mail_body_model->count_filtered(),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }

    public function update($id)
    {
      $mail_body = $this->Crud_model->GetData('mst_mail_body',"","id='".$id."'",'','','','row');
       $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                     <li>
                        <a href='".site_url('Mail_body')."'>Manage Assets</a>
                    </li>
                    <li class='active'>Update Mail Body</li>
                    </ul>";
      
      $data = array(
                      'button' => 'Update',
                      'action' => site_url('Mail_body/update_action/'.$id), 
                      'breadcrumbs' => $breadcrumbs, 
                       'heading' => 'Update Mail Body',                   
                       'id' => set_value('id',$mail_body->id),
                       'type' => set_value('type',$mail_body->type),
                       'mail_subject' => set_value('mail_subject',$mail_body->mail_subject),
                       'mail_body' => set_value('mail_body',$mail_body->mail_body),
                    );

      $this->load->view('mail_body/form',$data);

    }

    public function update_action($id)
    {
      if($id!='')
      {
          $data = array(
            'mail_subject'=>$_POST['mail_subject'],
            'mail_body'=>$_POST['mail_body'],          
            );

            $this->Crud_model->SaveData("mst_mail_body",$data,"id='".$id."'");      
            $this->session->set_flashdata('message', 'success');
            redirect('Mail_body/index');
      }
      else
      {
        redirect('Mail_body');
      }
    }
}
