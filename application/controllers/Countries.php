<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Countries extends CI_Controller {

  function __construct()
    {
    parent::__construct();
    $this->load->model("Countries_model");
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
              if($menu->value=='Countries')
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
                        <li class='active'>Manage Countries</li>
                        </ul>";
            $data = array(
              'breadcrumbs' => $breadcrumbs ,
              'actioncolumn' => '3' ,
              'ajax_manage_page' => site_url('Countries/ajax_manage_page') ,
              'button' => 'Create',
              'heading' => 'Manage Countries',
              'addPermission'=>$add
            );

            $this->load->view('countries/countries_list',$data);
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
      $getData = $this->Countries_model->get_datatables($condition);
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Countries')
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
            $row[] = $Data->country_code;
            $row[] = $Data->country_name;
            $row[] = $status;
            $row[] = $btn;
            $data[] = $row;
            $selected = '';
        }
 
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Countries_model->count_all($condition),
          "recordsFiltered" => $this->Countries_model->count_filtered($condition),
          "data" => $data,
        );
        echo json_encode($output);
    }
   		public function changeStatus(){
        //print_r($_POST);exit;
        $change_status = $this->Crud_model->GetData('mst_countries','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('mst_countries',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('mst_countries',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Countries/index');
    }
    


     public function addData()
      {
          $condDuplication = "country_name='".$this->input->post('country_name')."'";
          $duplication = $this->Crud_model->GetData('mst_countries','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'country_code' => $this->input->post('country_code'),
                'country_name' => $this->input->post('country_name'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
              );
              $this->Crud_model->SaveData('mst_countries',$data);
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
       public function updateData()
      {
          $condDuplication = "country_name='".$this->input->post('country_name')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('mst_countries','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                
                'country_code' => $this->input->post('country_code'),                
                'country_name' => $this->input->post('country_name'),               
              );

              $this->Crud_model->SaveData('mst_countries', $data, "id='".$this->input->post('id')."'");
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }

        public function update($id) 
      {
        $tablename = "mst_countries";
        $condition = "id='".$id."'";
        $row = $this->Crud_model->GetData($tablename,'',$condition,'','','','row');

        if ($row) {
          $data = array(
          'action' => site_url('Countries/update_action/'.$id),
          'id' => set_value('id', $row->id),
          'country_code' => set_value('country_code', $row->country_code),
          'country_name' => set_value('country_name', $row->country_name),
          'modified' => date("Y-m-d H:i:s"),
          
           );
              $this->load->view('countries/countries_list', $data);
            } else {
            $this->session->set_flashdata('message', 'Country Not Found');
            redirect(site_url('Countries/index'));
            }
      }


      public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('mst_countries',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('Countries/index');
      }

      /* public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->SaveData('mst_asset_types','',$con);

        $this->session->set_flashdata('message', 'success');
        redirect('Countries/index');
      }*/

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('mst_countries','',"id='".$_POST['id']."'",'','','','row');   
        //print_r($row);exit; 
        $data = array(
        			'country_code' => $row->country_code,
        			'country_name' => $row->country_name,
        			);
        echo json_encode($data);
        //print_r(trim($row->country_name));
      }

}
