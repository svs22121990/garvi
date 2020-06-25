<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helplines extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('Helplines_model');
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
              if($menu->value=='Helplines')
              { 
                
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              }
          }
        }
        $helplineTypes =  $this->Crud_model->GetData('helpline_types',"","status='Active'",'','helpline_type');
        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Helplines</li>
                    </ul>";
        $data = array(
           'actioncolumn' => '4' ,
           'ajax_manage_page' => site_url('Helplines/ajax_manage_page') ,
           'heading' => 'Manage Helplines',
           'helplineTypes' =>$helplineTypes,
           'breadcrumbs' =>$breadcrumbs,
           'addPermission'=>$add
        );
    $this->load->view('helpline/list',$data);
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
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Helplines')
            { 
              if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
              if(!empty($row->act_delete)){ $delete='1'; }else{ $delete='0'; }
              if(!empty($row->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            }
        }
      }

    $condition="";
      $getData = $this->Helplines_model->get_datatables($condition);
    
    $data = array();
    if(empty($_POST['start']))
            {
                $no =0;   
            }
            else
            {
                $no =$_POST['start'];
            }    
    foreach ($getData as $Data) 
    {
      $btn = '';
      if(!empty($edit)){
      $btn .= ('<a href="#myModaledit" title="Edit" class="btn btn-info btn-circle btn-sm" data-toggle="modal"  onclick="getEditvalue('.$Data->id.');"> <i class="fa fa-pencil bigger-130"></i></a>');
      }

      if(!empty($delete)){     
      $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="fa fa-trash-o bigger-130"></i></a>';
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
        $nestedData = array();
        $nestedData[] = $no;
        $nestedData[] = $Data->helpline_type;
        $nestedData[] = ucwords($Data->contact_person);
        $nestedData[] = $Data->contact_number;
        if(!empty($Data->email))
        {
          $email = $Data->email;
        }
        else
        {
          $email = 'N/A';
        }
        $nestedData[] = $email;
        if(!empty($Data->address))
        {
          $address = $Data->address;
        }
        else
        {
          $address = 'N/A';
        }
        $nestedData[] = $address;
        if(!empty($status))
        {
          $status = $status;
        }
        else
        {
          $status = 'N/A';
        }
        $nestedData[] = $status;
        if(!empty($btn))
        {
          $btn = $btn;
        }
        else
        {
          $btn = 'N/A';
        }
        $nestedData[] = $btn;
        $data[] = $nestedData;
    }    
      $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Helplines_model->count_all($condition),
          "recordsFiltered" => $this->Helplines_model->count_filtered($condition),
          "data" => $data,
        );
    //output to json format
    echo json_encode($output);
}

    public function changeStatus(){
        //print_r($_POST);exit;
        $change_status = $this->Crud_model->GetData('helplines','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('helplines',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('helplines',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Helplines/index');
    }


     public function addData()
      {
          $condDuplication = "contact_number='".$this->input->post('contact_number')."' && helpline_type_id='".$this->input->post('helpline_type_id')."'";
          $duplication = $this->Crud_model->GetData('helplines','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'helpline_type_id' => $this->input->post('helpline_type_id'),
                'contact_person' => $this->input->post('contact_person'),
                'contact_number' => $this->input->post('contact_number'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'heading' => 'Helplines',
              );
              $this->Crud_model->SaveData('helplines', $data);
              //$this->session->set_flashdata('message', 'success');
              echo "2";
          }  
          
      }
       public function updateData()
      {  
          $condDuplication = "contact_person='".$this->input->post('contact_person')."' && helpline_type_id='".$this->input->post('helpline_type_id')."' and id !='".$this->input->post('id')."'"; 
          $duplication = $this->Crud_model->GetData('helplines','',$condDuplication); 
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {  
              $data = array(                
                'helpline_type_id' => $this->input->post('helpline_type_id'),
                'contact_person' => $this->input->post('contact_person'),
                'contact_number' => $this->input->post('contact_number'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'modified' => date('Y-m-d H:i:s'),                
              );  

              $this->Crud_model->SaveData('helplines', $data, "id='".$this->input->post('id')."'"); 
              //$this->session->set_flashdata('message', 'success');
              echo "2";
          }  
          
      }

        public function update($id) 
      {
        $tablename = "helplines";
        $condition = "id='".$id."'";
        $row = $this->Crud_model->GetData($tablename,'',$condition,'','','','row');
       //print_r($row);exit;
        if ($row) {
          $data = array(
          'action' => site_url('Helplines/update_action'.$id),
          'id' => set_value('id', $row->id),
          'address' => set_value('address', $row->address),
          'email' => set_value('email', $row->email),
          'contact_person' => set_value('contact_person', $row->contact_person),
          'contact_number' => set_value('contact_number', $row->contact_number),
          'helpline_type_id' => set_value('helpline_type_id', $row->helpline_type_id),
          'modified' => date("Y-m-d H:i:s"),
          'heading' => 'Helplines'
          
           );
              $this->load->view('helpline/list', $data);
            } else {
            $this->session->set_flashdata('message', 'Helpline Not Found');
            redirect(site_url('Helplines/index'));
            }
      }


      public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('helplines',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('Helplines/index');
      }

      public function getUpdateHelpline()
      {
        $row = $this->Crud_model->GetData('helplines','',"id='".$_POST['id']."'",'','','','row');   
      
        //print_r(trim($row->name));
        
           $helplineTypes =  $this->Crud_model->GetData('helpline_types',"","status='Active'",'','helpline_type');
      
       //echo "$country->name";
         $data = array('email'=> $row->email,'address'=> $row->address,'contact_person'=> $row->contact_person,'contact_number'=> $row->contact_number,'id'=> $row->id,'helpline_type_id'=> $row->helpline_type_id, 'helplineTypes'=> $helplineTypes);
         $this->load->view('helpline/geteditHelpline',$data);
        
      }

















}
