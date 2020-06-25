<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ra_modules extends CI_Controller {

  function __construct()
    {
    parent::__construct();
    $this->load->model("Ra_modules_model");
    }

    //LIST VIEW
  public function index()
  {
    $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Modules</li>
                    </ul>";
        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '3' ,
        'ajax_manage_page' => site_url('Ra_modules/ajax_manage_page') ,
        'button' => 'Create',
        'heading' => 'Manage Modules'
        );
        $this->load->view('ra_modules/ra_modules_list',$data);
  }

    public function ajax_manage_page()
    {
      $condition="";
      $getData = $this->Ra_modules_model->get_datatables($condition);

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

            $btn ='&nbsp; '. '<a style="color:#fff" title="Add Menu" href="'.site_url('Ra_menus/index/'.$Data->id).'"><span class="btn btn-warning btn-circle btn-sm"><i class="ace-icon fa fa-plus bigger-130"></i></a></span>';

            $btn .= '&nbsp;|&nbsp;'.('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$Data->id.');">
              <i class="ace-icon fa fa-pencil bigger-130"></i></a>');

            $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';

             if($Data->status=='Active')
              {
                  $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$Data->id.")'> Active </a>";            
              }
              else
              {
                  $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$Data->id.")'> Inactive </a>";
              }
            $no++;   
            $row = array();
            $row[] = $no;
            $row[] = $Data->module_name;
            $row[] = $status;
            $row[] = $btn;
            $data[] = $row;
            $selected = '';
        }
 
        $output = array(
          //"draw" => $_POST['draw'],
          "recordsTotal" => $this->Ra_modules_model->count_all($condition),
          "recordsFiltered" => $this->Ra_modules_model->count_filtered($condition),
          "data" => $data,
        );
        echo json_encode($output);
    }
   		public function changeStatus(){
        
        $change_status = $this->Crud_model->GetData('ra_modules','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('ra_modules',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('ra_modules',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Ra_modules/index');
    }
    


     public function addData()
      {
        //print_r($_POST);exit;
          $condDuplication = "module_name='".$this->input->post('module_name')."'";
          $duplication = $this->Crud_model->GetData('ra_modules','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'module_name' => $this->input->post('module_name'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
              );
              $this->Crud_model->SaveData('ra_modules',$data);
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
       public function updateData()
      {
          $condDuplication = "module_name='".$this->input->post('module_name')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('ra_modules','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                 
                'module_name' => $this->input->post('module_name'),               
              );

              $this->Crud_model->SaveData('ra_modules', $data, "id='".$this->input->post('id')."'");
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }

        public function update($id) 
      {
        $tablename = "ra_modules";
        $condition = "id='".$id."'";
        $row = $this->Crud_model->GetData($tablename,'',$condition,'','','','row');

        if ($row) {
          $data = array(
          'action' => site_url('Ra_modules/update_action/'.$id),
          'id' => set_value('id', $row->id),
          'module_name' => set_value('module_name', $row->module_name),
          'modified' => date("Y-m-d H:i:s"),
          
           );
              $this->load->view('ra_modules/ra_modules_list', $data);
            } else {
            $this->session->set_flashdata('message', 'Module Not Found');
            redirect(site_url('Ra_modules/index'));
            }
      }


      public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('ra_modules',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('Ra_modules/index');
      }

      /* public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->SaveData('mst_asset_types','',$con);

        $this->session->set_flashdata('message', 'success');
        redirect('Ra_modules/index');
      }*/

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('ra_modules','',"id='".$_POST['id']."'",'','','','row');   
        //print_r($row);exit; 
        $data = array(
        			'module_name' => $row->module_name,
        			);
        echo json_encode($data);
        //print_r(trim($row->module_name));
      }

}
