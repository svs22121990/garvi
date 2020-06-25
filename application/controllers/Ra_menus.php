<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ra_menus extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Ra_menus_model');
  
  $this->load->database();
  }
  public function index($id)
  {   
     $ra_modules = $this->Crud_model->GetData('ra_modules','module_name,id',"id='".$id."'",'','','','row'); 

     $heading=" Module - Manage ".$ra_modules->module_name;

        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li>
                        <a href='".site_url('Ra_modules/index')."'>Manage Modules</a>
                    </li>
                    <li class='active'>".$heading."</li>
                    </ul>";
                   
       
      $data = array('breadcrumbs' => $breadcrumbs ,'ra_modules' => $ra_modules ,'heading' => $heading,'ra_module_id' => $ra_modules->id);

    $this->load->view('menu/list',$data);
  }

  public function ajax_manage_page($ra_module_id)
  {

    $con="rm.ra_module_id='".$ra_module_id."'";
    $Data = $this->Ra_menus_model->get_datatables($con); 
  
    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
      $btn = ('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$row->id.');">
              <i class="ace-icon fa fa-pencil bigger-130"></i></a>');

      $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';

      /*$btn .='<a href="#AssignActionData" data-toggle="modal" title="Assign Actions" style="cursor:pointer" class="cursor" onclick="assignIDs('.$row->id.','.$row->ra_module_id.')"><i class="ace-icon fa fa-plus bigger-130 blue"></i></a>';*/
      $btn .='&nbsp;|&nbsp;'.'<a href="#AssignActionData" data-toggle="modal" title="Assign Actions" class="btn btn-warning btn-circle btn-sm" onclick="assignIDs('.$row->id.','.$row->ra_module_id.')"><i class="ace-icon fa fa-plus bigger-130"></i></a>';

         if($row->status=='Active')
          {
              $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$row->id.")'> Active </a>";            
          }
          else
          {
              $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$row->id.")'> Inactive </a>";
          }
             
        $no++;
        $nestedData = array();
        $nestedData[] = $no ;
        $nestedData[] = $row->module_name;
        $nestedData[] = $row->menu_name;
        $nestedData[] = $row->value;
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
                "draw" => isset($_POST['draw']) ? $_POST['draw'] : '',
                "recordsTotal" => $this->Ra_menus_model->count_all($con),
                "recordsFiltered" => $this->Ra_menus_model->count_filtered($con),
                "data" => $data,
            );
   
    echo json_encode($output);
  }

 public function addData()
      {
          $condDuplication = "menu_name='".$this->input->post('menu_name')."'";
          $duplication = $this->Crud_model->GetData('ra_menus','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'menu_name' => $this->input->post('menu_name'),
                'value' => $this->input->post('value'),
                'ra_module_id' => $this->input->post('ra_module_id'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
              );
              $this->Crud_model->SaveData('ra_menus',$data);
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('ra_menus','',"id='".$_POST['id']."'",'','','','row');   
        
        $data = array(
              'menu_name' => $row->menu_name,
              'value' => $row->value,
              );
        echo json_encode($data);
       
      }

      public function updateData()
      {
          $condDuplication = "menu_name='".$this->input->post('menu_name')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('ra_menus','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                
                'menu_name' => $this->input->post('menu_name'),                
                'value' => $this->input->post('value'),               
              );

              $this->Crud_model->SaveData('ra_menus', $data, "id='".$this->input->post('id')."'");
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
      public function changeStatus(){
        $change_status = $this->Crud_model->GetData('ra_menus','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('ra_menus',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('ra_menus',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Ra_menus/index/'.$change_status->ra_module_id);
    }
    public function delete()
    {
      $change_status = $this->Crud_model->GetData('ra_menus','',"id='".$_POST['id']."'",'','','','row');
      $con = "id='".$_POST['id']."'";
      $this->Crud_model->DeleteData('ra_menus',$con);

      $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Menu deleted successfully</span>');
      redirect('Ra_menus/index/'.$change_status->ra_module_id);
    }
    public function getAllActions(){
    $getaction = $this->Crud_model->GetData('ra_menu_actions','',"ra_module_id='".$_POST['ra_module_id']."' and ra_menu_id='".$_POST['ra_menu_id']."'",'','','','single');

    $data=array('getaction'=>$getaction);
    $this->load->view('menu/ra_action_get',$data);

  }
  public function create_menu_action_values()
  {
    //print_r($_POST);exit;
      $getaction = $this->Crud_model->GetData('ra_menu_actions','',"ra_module_id='".$_POST['ra_module_id']."' and ra_menu_id='".$_POST['ra_menu_id']."'",'','','','single');
      
      if(empty($getaction))
      {
          $_POST['created']=date('Y-m-d H:i:s');
          $this->Crud_model->SaveData("ra_menu_actions", $_POST); 
          $this->session->set_flashdata('message', '<div class="label label-xlg label-success arrowed-in-right arrowed"><p>Actions has been assigned successfully</p></div>');
        }
        else
        {
          $_POST['modified']=date('Y-m-d H:i:s');
          $this->Crud_model->SaveData("ra_menu_actions", $_POST , "ra_module_id='".$_POST['ra_module_id']."' and ra_menu_id='".$_POST['ra_menu_id']."'"); 
          $this->session->set_flashdata('message', '<div class="label label-xlg label-success arrowed-in-right arrowed"><p>Actions has been assigned successfully</p></div>');

        }
        
    redirect(site_url('Ra_menus/index/'.$_POST['ra_module_id']));
  } 

}
