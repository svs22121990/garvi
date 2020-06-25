<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cities extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('Cities_model');
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
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='Cities')
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
                <li class='active'>Manage Cities</li>
            </ul>";
        $condUser ="status='Active'";
        $countries =  $this->Crud_model->GetData('mst_countries','',$condUser);
        $states =  $this->Crud_model->GetData('mst_states','',$condUser);
        $data = array(
          'breadcrumbs' => $breadcrumbs ,
          'actioncolumn' => '4' ,
          'ajax_manage_page' => site_url('Cities/ajax_manage_page') ,
          'heading' => 'Manage Cities',
          'countries' => $countries,
          'states' => $states,
          'addPermission'=>$add
          );
    $this->load->view('cities/list',$data);
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
        $Data = $this->Cities_model->get_datatables(); 
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
        { 
          foreach($menu as $row)
          {

              if($row->value=='Cities')
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
        foreach($Data as $row) 
        {
          $btn='';
          if(!empty($edit)){
            $btn = ('<a href="#myModaledit" title="Edit" class="btn btn-info btn-circle btn-sm" data-toggle="modal"  onclick="getEditvalue('.$row->id.');"><i class="ace-icon fa fa-pencil bigger-130"></i></a>');
              }
          if(!empty($delete)){

              $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
          }
             $status ='';
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
            $nestedData[] = $no;
            $nestedData[] = $row->country_name;
            $nestedData[] = $row->state_name;
            $nestedData[] = ucfirst($row->city_name);
            $nestedData[] = $status;
            $nestedData[] = $btn;
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Cities_model->count_all(),
                    "recordsFiltered" => $this->Cities_model->count_filtered(),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }
   
    public function changeStatus(){
        //print_r($_POST);exit;
        $change_status = $this->Crud_model->GetData('mst_cities','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('mst_cities',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('mst_cities',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Cities/index');
    }
    

     public function addData()
      {       
          $condDuplication = "city_name='".$this->input->post('city_name')."' && country_id='".$this->input->post('country_id')."' && state_id='".$this->input->post('state_id')."'";
          $duplication = $this->Crud_model->GetData('mst_cities','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'city_name' => $this->input->post('city_name'),
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
              );
              $this->Crud_model->SaveData('mst_cities', $data);
              $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
       public function updateData()
      {
        //print_r($_POST);exit;
          $condDuplication = "city_name='".$this->input->post('city_name')."' and country_id='".$this->input->post('country_id')."' and state_id='".$this->input->post('state_id')."' and id !='".$this->input->post('id')."'"; 
          $duplication = $this->Crud_model->GetData('mst_cities','',$condDuplication);
          //print_r($this->db->last_query());exit();
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'country_id' => $this->input->post('country_id'),     
                'state_id' => $this->input->post('state_id'),           
                'city_name' => $this->input->post('city_name'),
                'modified' => date('Y-m-d H:i:s'),                   
              );
               // print_r($data);exit;
              $this->Crud_model->SaveData('mst_cities', $data, "id='".$this->input->post('id')."'");
              $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }


        public function update($id) 
      {
        $tablename = "mst_cities";
        $condition = "id='".$id."'";
        $row = $this->Crud_model->GetData($tablename,'',$condition,'','','','row');
        //print_r($row);exit;
        if ($row) {
          $data = array(
          'action' => site_url('Cities/update_action/'.$id),
          'id' => set_value('id', $row->id),
          'country_id' => set_value('country_id', $row->country_id),
          'state_id' => set_value('state_id', $row->state_id),
          'city_name' => set_value('city_name', $row->city_name),
          'modified' => date("Y-m-d H:i:s"),
          
           );
              $this->load->view('cities/list', $data);
            } else {
            $this->session->set_flashdata('message', 'City Not Found');
            redirect(site_url('Cities/index'));
            }
      }

      public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('mst_cities',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('Cities/index');
      }

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('mst_cities','',"id='".$_POST['id']."'",'','','','row');       
        //print_r($row);
        $country = $this->Crud_model->GetData('mst_countries','','status="Active"');
        $state = $this->Crud_model->GetData('mst_states','',"country_id='".$row->country_id."' and status='Active'");

        //  echo "$country->name";

         $data = array('city_name'=> $row->city_name,'id'=> $row->id,'country_id'=> $row->country_id, 'country'=> $country,'state_id'=> $row->state_id, 'state'=> $state);
       
          $this->load->view('cities/geteditCity',$data);
      }

      
      public function get_state()
      {    
      $id = $this->input->post('id'); 
      $cond = "country_id ='".$id."'";
      $getstate = $this->Crud_model->GetData('mst_states','',$cond);
      //print_r( $getstate);exit;
      $html="<option value=''>-- Select State--</option>";
      
      foreach($getstate as $states)
         {
          //print_r($states);
          $html.="<option value=".$states->id.">".$states->state_name."</option>";
          
         }
         echo $html;
    }








}