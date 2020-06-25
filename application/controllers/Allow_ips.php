<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Allow_ips extends CI_Controller {

  function __construct()
    {
    parent::__construct();
    $this->load->model("Allow_ips_model");
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
              if($menu->value=='Allow_ips')
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
                        <li class='active'>Manage Allow_ips</li>
                        </ul>";
            $data = array(
              'breadcrumbs' => $breadcrumbs ,
              'actioncolumn' => '3' ,
              'ajax_manage_page' => site_url('Allow_ips/ajax_manage_page') ,
              'button' => 'Create',
              'heading' => 'Manage Allow IPs',
              'addPermission'=>$add
            );

            $this->load->view('allow_ips/allow_ips_list',$data);
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
      $getData = $this->Allow_ips_model->get_datatables($condition);
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Allow_ips')
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
            $row[] = $Data->ip_address;
            $row[] = $status;
            $row[] = $btn;
            $data[] = $row;
            $selected = '';
        }
 
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Allow_ips_model->count_all($condition),
          "recordsFiltered" => $this->Allow_ips_model->count_filtered($condition),
          "data" => $data,
        );
        echo json_encode($output);
    }
   		public function changeStatus(){
        //print_r($_POST);exit;
        $change_status = $this->Crud_model->GetData('mst_allow_ips','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('mst_allow_ips',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('mst_allow_ips',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Allow_ips/index');
    }
    


     public function addData()
      {
          $condDuplication = "ip_address='".$this->input->post('ip_address')."'";
          $duplication = $this->Crud_model->GetData('mst_allow_ips','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'ip_address' => $this->input->post('ip_address'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
              );
              $this->Crud_model->SaveData('mst_allow_ips',$data);
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
       public function updateData()
      {
          $condDuplication = "ip_address='".$this->input->post('ip_address')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('mst_allow_ips','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                   
                'ip_address' => $this->input->post('ip_address'),               
              );

              $this->Crud_model->SaveData('mst_allow_ips', $data, "id='".$this->input->post('id')."'");
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }

        public function update($id) 
      {
        $tablename = "mst_allow_ips";
        $condition = "id='".$id."'";
        $row = $this->Crud_model->GetData($tablename,'',$condition,'','','','row');

        if ($row) {
          $data = array(
                      'action' => site_url('Allow_ips/update_action/'.$id),
                      'id' => set_value('id', $row->id),
                      'ip_address' => set_value('ip_address', $row->ip_address),
                      'modified' => date("Y-m-d H:i:s"),
                       );
              $this->load->view('allow_ips/allow_ips_list', $data);
            } else {
            $this->session->set_flashdata('message', 'IP address Not Found');
            redirect(site_url('Allow_ips/index'));
            }
      }


      public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('mst_allow_ips',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('Allow_ips/index');
      }

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('mst_allow_ips','',"id='".$_POST['id']."'",'','','','row');   
        //print_r($row);exit; 
        $data = array(
        			'ip_address' => $row->ip_address,
        			);
        echo json_encode($data);
        //print_r(trim($row->ip_address));
      }

}
