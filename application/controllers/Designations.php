<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Designations extends CI_Controller {

  function __construct()
    {
    parent::__construct();
    $this->load->model("Designations_model");
    }

    //LIST VIEW
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
              if($menu->value=='Designations')
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
                    <li class='active'>Manage Designations</li>
                    </ul>";
        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '2' ,
        'ajax_manage_page' => site_url('Designations/ajax_manage_page') ,
        'button' => 'Create',
        'heading' => 'Manage Designations',
        'addPermission'=>$add
        );
        $this->load->view('designations/designations_list',$data);
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
            if($menu->value=='Designations')
            { 
              if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
            }
        }
      }

      $condition="";
      $getData = $this->Designations_model->get_datatables($condition);

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
              $btn = '';
            if(!empty($edit)){
            $btn .= ('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$Data->id.');">
              <i class="ace-icon fa fa-pencil bigger-130"></i></a>');
            }
            if(!empty($delete)){
            $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
            }
            $status = '';
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
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst($Data->designation_name);
            $row[] = $status;
            $row[] = $btn;
            $data[] = $row;
        }
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Designations_model->count_all($condition),
          "recordsFiltered" => $this->Designations_model->count_filtered($condition),
          "data" => $data,
        );
        echo json_encode($output);
    }
   		public function changeStatus(){
        //print_r($_POST);exit;
        $change_status = $this->Crud_model->GetData('mst_designations','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('mst_designations',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('mst_designations',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Designations/index');
    }
    


     public function addData()
      {
          $condDuplication = "designation_name='".$this->input->post('designation_name')."'";
          $duplication = $this->Crud_model->GetData('mst_designations','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'designation_name' => $this->input->post('designation_name'),
                'modified_by' => $_SESSION[SESSION_NAME]['id'],
                'status' => 'Active',
                'is_delete' => 'No',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
              );
              $this->Crud_model->SaveData('mst_designations',$data);
             //$this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
       public function updateData()
      {
          $condDuplication = "designation_name='".$this->input->post('designation_name')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('mst_designations','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                 
                'designation_name' => $this->input->post('designation_name'),               
              );

              $this->Crud_model->SaveData('mst_designations', $data, "id='".$this->input->post('id')."'");
             //$this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }

        public function update($id) 
      {
        $tablename = "mst_designations";
        $condition = "id='".$id."'";
        $row = $this->Crud_model->GetData($tablename,'',$condition,'','','','row');

        if ($row) {
          $data = array(
          'action' => site_url('Designations/update_action/'.$id),
          'id' => set_value('id', $row->id),
          'designation_name' => set_value('designation_name', $row->designation_name),
          'modified' => date("Y-m-d H:i:s"),
          
           );
              $this->load->view('designations/designations_list', $data);
            } else {
            //$this->session->set_flashdata('message', 'Country Not Found');
            redirect(site_url('Designations/index'));
            }
      }


       public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $data = array('is_delete' =>'Yes',);
        $this->Crud_model->SaveData('mst_designations',$data,$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect(site_url('Designations'));
      }

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('mst_designations','',"id='".$_POST['id']."'",'','','','row');       
        print_r(trim($row->designation_name));
      }

}
