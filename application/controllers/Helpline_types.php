<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Helpline_types extends CI_Controller {

  function __construct()
    {
    parent::__construct();
    $this->load->model("Helpline_types_model");
    }

    //LIST VIEW
  public function index()
  {
    //print_r($_SESSION[SESSION_NAME]['getMenus']);exit;

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
              if($menu->value=='Helpline_types')
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
                        <li class='active'>Manage Helpline Types</li>
                        </ul>";
            $data = array(
              'breadcrumbs' => $breadcrumbs ,
              'actioncolumn' => '3' ,
              'ajax_manage_page' => site_url('Helpline_types/ajax_manage_page') ,
              'button' => 'Create',
              'heading' => 'Manage Helpline Types',
              'addPermission'=>$add
            );

            $this->load->view('helpline_types/list',$data);
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
      $condition="";
      $getData = $this->Helpline_types_model->get_datatables($condition);
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Helpline_types')
            { 
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
        foreach ($getData as $Data) {
          
          $btn='';
          if(!empty($edit)){
                $btn .= ('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$Data->id.');">
                  <i class="ace-icon fa fa-pencil bigger-130"></i></a>');
          }
          if(!empty($delete)){
               $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
             }
             $status ='';
             if(!empty($actstatus)){ 
                 if($Data->status=='Active')
                  {
                      $status .=  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$Data->id.")'> Active </a>";            
                  }
                  else
                  {
                      $status .=  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$Data->id.")'> Inactive </a>";
                  }
            }
             $no++;   
            $row = array();
            $row[] = $no;
            $row[] = $Data->helpline_type;
            $row[] = $status;
            $row[] = $btn;
            $data[] = $row;
            $selected = '';
        }
 
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Helpline_types_model->count_all($condition),
          "recordsFiltered" => $this->Helpline_types_model->count_filtered($condition),
          "data" => $data,
        );
        echo json_encode($output);
    }
   		public function changeStatus(){
        //print_r($_POST);exit;
        $change_status = $this->Crud_model->GetData('helpline_types','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('helpline_types',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('helpline_types',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Helpline_types/index');
    }
    


     public function addData()
      {
          $condDuplication = "helpline_type='".$this->input->post('type')."'";
          $duplication = $this->Crud_model->GetData('helpline_types','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'helpline_type' => $this->input->post('type'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
              );
              $this->Crud_model->SaveData('helpline_types',$data);
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
       public function updateData()
      {
          $condDuplication = "helpline_type='".$this->input->post('type')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('helpline_types','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                
                 'helpline_type' => $this->input->post('type'),
                 'created' => date('Y-m-d H:i:s'),
                  'modified' => date('Y-m-d H:i:s'),              
              );

              $this->Crud_model->SaveData('helpline_types', $data, "id='".$this->input->post('id')."'");
              $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
     public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('helpline_types','',"id='".$_POST['id']."'",'','','','row');   
      
        $data = array(
        			'type' => $row->helpline_type,
        			
        			);
        echo json_encode($data);
        
      }

}
