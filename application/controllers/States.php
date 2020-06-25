<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class States extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('States_model');
  $this->load->database();
  $this->load->library(array('SendMail'));
  $this->SendMail = new SendMail();
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
              if($menu->value=='States')
              { 
                
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              }
          }
        }
    $condUser ="status='Active'";
    $countries =  $this->Crud_model->GetData('mst_countries','',$condUser);
   $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage States</li>
                    </ul>";
        $data = array(
          'actioncolumn' => '3' ,
          'ajax_manage_page' => site_url('States/ajax_manage_page') ,
           'heading' => 'Manage States',
           'countries' =>$countries,
           'breadcrumbs' =>$breadcrumbs,
           'addPermission'=>$add
            );

    $this->load->view('states/list',$data);
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
    $condition="";
    $getData = $this->States_model->get_datatables($condition);
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='States')
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
    foreach ($getData as $Data) 
    {
      /* $btn ='<a href="'.site_url('States/update/'.$Data->id).'" title="Update"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';*/
      $btn='';
          if(!empty($edit)){
      $btn .= ('<a href="#myModaledit" title="Edit" class="btn btn-info btn-circle btn-sm" data-toggle="modal"  onclick="getEditvalue('.$Data->id.');"> <i class="fa fa-pencil bigger-130"></i></a>');
      }
          if(!empty($delete)){
            
      $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="fa fa-trash-o bigger-130"></i></a>';
      }
             $status ='';
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
        $nestedData[] = $Data->country_name;
        $nestedData[] = ucwords($Data->state_name);
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
    }    
      $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->States_model->count_all($condition),
          "recordsFiltered" => $this->States_model->count_filtered($condition),
          "data" => $data,
        );
    //output to json format
    echo json_encode($output);
}

    public function changeStatus(){
        //print_r($_POST);exit;
        $change_status = $this->Crud_model->GetData('mst_states','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('mst_states',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('mst_states',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('States/index');
    }


     public function addData()
      {
          $condDuplication = "state_name='".$this->input->post('state_name')."' && country_id='".$this->input->post('country_id')."'";
          $duplication = $this->Crud_model->GetData('mst_states','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'country_id' => $this->input->post('country_id'),
                'state_name' => $this->input->post('state_name'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'heading' => 'States',
              );
              $this->Crud_model->SaveData('mst_states', $data);
              $this->session->set_flashdata('message', 'success');
              echo "2";
          }  
          
      }
       public function updateData()
      {  
          $condDuplication = "state_name='".$this->input->post('state_name')."' && country_id='".$this->input->post('country_id')."' and id !='".$this->input->post('id')."'"; 
          $duplication = $this->Crud_model->GetData('mst_states','',$condDuplication); 
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {  
              $data = array(                
                'country_id' => $this->input->post('country_id'),
                'state_name' => $this->input->post('state_name'),
                'modified' => date('Y-m-d H:i:s'),                
              );  

              $this->Crud_model->SaveData('mst_states', $data, "id='".$this->input->post('id')."'"); 
              $this->session->set_flashdata('message', 'success');
              echo "2";
          }  
          
      }

        public function update($id) 
      {
        $tablename = "mst_states";
        $condition = "id='".$id."'";
        $row = $this->Crud_model->GetData($tablename,'',$condition,'','','','row');
       //print_r($row);exit;
        if ($row) {
          $data = array(
          'action' => site_url('States/update_action'.$id),
          'id' => set_value('id', $row->id),
          'state_name' => set_value('state_name', $row->state_name),
          'country_id' => set_value('country_id', $row->country_id),
          'modified' => date("Y-m-d H:i:s"),
          'heading' => 'States'
          
           );
              $this->load->view('states/list', $data);
            } else {
            $this->session->set_flashdata('message', 'State Not Found');
            redirect(site_url('States/index'));
            }
      }


      public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('mst_states',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('States/index');
      }

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('mst_states','',"id='".$_POST['id']."'",'','','','row');   
      
        //print_r(trim($row->name));
        
        $country = $this->Crud_model->GetData('mst_countries','','status="Active"');
      
       //echo "$country->name";
         $data = array('state_name'=> $row->state_name,'id'=> $row->id,'country_id'=> $row->country_id, 'country'=> $country);
         $this->load->view('states/geteditState',$data);
        
      }



 public function MailFunction(){

      $branches = $this->Crud_model->GetData("branches",'',"id='2'",'','','','1');
      $image='a.png';
      $mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='create_account'");
      $imgpath = base_url('uploads/employee_images/'.$image);
      
        $mail_body[0]->mail_body=str_replace("{branch}",ucfirst($branches->branch_title),$mail_body[0]->mail_body);
        $mail_body[0]->mail_body=str_replace("{username}",ucwords('Aman'),$mail_body[0]->mail_body);
        $mail_body[0]->mail_body=str_replace("{email}",'abc@gmail.com',$mail_body[0]->mail_body);
        $mail_body[0]->mail_body=str_replace("{password}",'1100',$mail_body[0]->mail_body);
        $mail_body[0]->mail_body=str_replace("{imgurl}",$imgpath,$mail_body[0]->mail_body);
        $subject=$mail_body[0]->mail_subject;
        $body=$mail_body[0]->mail_body;
        //print_r($body);exit;
        $MailData = array('mailoutbox_to'=>'gcw@tbsind.com','mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body[0]->type);
        //$Send=$this->SendMail->Send($MailData);
        print_r($Send);exit;
       
    
 }















}
