<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_types extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('Unit_model');
  $this->load->database();
  }
  
  public function index()
  {  
     $edit = ''; 
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
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Unit Type</li>
                    </ul>";
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '2' ,'ajax_manage_page' => site_url('Unit_types/ajax_manage_page') , 'heading' => 'Manage Unit Type','addPermission'=>$add );

    $this->load->view('unit_type/list',$data);
   }
   else
  {
    redirect('Dashboard');
  }
  }



    public function ajax_manage_page()
    {

         $edit = ''; 
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
              if($menu->value=='Unit_types')
              { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              }
          }
        }

        $Data = $this->Unit_model->get_datatables(); 
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
          $status = '';
          /*if(!empty($actstatus)){
           if($row->status == 'Active')
           {
              $status = '<label class="label label-success">Active</label>';
           }
           else
           {
              $status = '<label class="label label-warning">Inactive</label>';
           }
          } */
          $btn = '';
          if(!empty($edit)){
            $btn .= ('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$row->id.');">
              <i class="ace-icon fa fa-pencil"></i></a>');
          }  
            /*$btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="red" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';*/
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
            $nestedData[] = $no ;
            $nestedData[] = $row->unit;
            $nestedData[] = $status;            
            $nestedData[] = $btn;       
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Unit_model->count_all(),
                    "recordsFiltered" => $this->Unit_model->count_filtered(),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }


    public function changeStatus(){       
        $getasset_types = $this->Crud_model->GetData('unit_types','',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Active')
        {
            $this->Crud_model->SaveData('unit_types',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('unit_types',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('Unit_types'));
    }

    public function addData()
      {        
          $condDuplication = "unit='".$this->input->post('name')."'";
          $duplication = $this->Crud_model->GetData('unit_types','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                            'unit' => $this->input->post('name'),
                            'status' => 'Active',
                            'is_delete' => 'No',
                            'created' => date('Y-m-d H:i:s'),
                          );
              $this->Crud_model->SaveData('unit_types', $data);
              //$this->session->set_flashdata('message', 'success');
              echo "2";
          }  
          
      }

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('unit_types','',"id='".$_POST['id']."'",'','','','row');       
        print_r(trim($row->unit));
      }

      public function updateData()
      {
          $condDuplication = "unit='".$this->input->post('name')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('unit_types','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                
                'unit' => $this->input->post('name')                
              );
             $this->Crud_model->SaveData('unit_types', $data, "id='".$this->input->post('id')."'");
             $this->session->set_flashdata('message', 'success');
             echo "2";
          }            
      }


       /*public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $data = array('is_delete' =>'Yes',);
        $this->Crud_model->SaveData('unit_types',$data,$con);

        $this->session->set_flashdata('message', 'success');
        redirect(site_url('Unit_types'));
      }*/
}
