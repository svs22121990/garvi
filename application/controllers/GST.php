<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GST extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('GST_model');
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


    $categories = $this->Crud_model->GetData("categories","","status='Active'","","","","");
    //print_r($categories);exit;
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='GST')
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
                <li class='active'>Manage GST</li>
            </ul>";
        /*$condUser ="status='Active'";
        $countries =  $this->Crud_model->GetData('mst_countries','',$condUser);
        $states =  $this->Crud_model->GetData('mst_states','',$condUser);*/
        $data = array(
          'breadcrumbs' => $breadcrumbs ,
          'actioncolumn' => '5' ,
          'ajax_manage_page' => site_url('GST/ajax_manage_page') ,
          'heading' => 'Manage GST',
          'categories' => $categories,
          //'countries' => $countries,
          //'states' => $states,
          'addPermission'=>$add
          );
    $this->load->view('gst/list',$data);
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
        $Data = $this->GST_model->get_datatables(); 
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
        { 
          foreach($menu as $row)
          {

              if($row->value=='GST')
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
            $nestedData[] = $row->title;
            $nestedData[] = $row->hsn;
            $nestedData[] = $row->gst_percent;
            $nestedData[] = $row->markup_percent;
            $nestedData[] = $status;
            $nestedData[] = $btn;
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    //"draw" => $_POST['draw'],
                    "recordsTotal" => $this->GST_model->count_all(),
                    "recordsFiltered" => $this->GST_model->count_filtered(),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }
   
    public function changeStatus(){
        //print_r($_POST);exit;
        $change_status = $this->Crud_model->GetData('mst_gst','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('mst_gst',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('mst_gst',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('GST/index');
    }
    

     public function addData()
      {       
          $condDuplication = "category_id='".$this->input->post('category_id')."' && hsn='".$this->input->post('hsn')."' && gst_percent='".$this->input->post('gst_percent')."'&& markup_percent='".$this->input->post('markup_percent')."'";
          $duplication = $this->Crud_model->GetData('mst_gst','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'category_id' => $this->input->post('category_id'),
                'hsn' => $this->input->post('hsn'),
                'gst_percent' => $this->input->post('gst_percent'),
                  'markup_percent' => $this->input->post('markup_percent'),
                'status' => 'Active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
              );
              $this->Crud_model->SaveData('mst_gst', $data);
              $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }
       public function updateData()
      {
        //print_r($_POST);exit;
          $condDuplication = "category_id='".$this->input->post('category_id')."' and hsn='".$this->input->post('hsn')."' and gst_percent='".$this->input->post('gst_percent')."' and markup_percent='".$this->input->post('markup_percent')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('mst_gst','',$condDuplication);
          //print_r($this->db->last_query());exit();
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'category_id' => $this->input->post('category_id'),     
                'hsn' => $this->input->post('hsn'),           
                'gst_percent' => $this->input->post('gst_percent'),
                  'markup_percent' => $this->input->post('markup_percent'),
                'modified' => date('Y-m-d H:i:s'),                   
              );
               // print_r($data);exit;
              $this->Crud_model->SaveData('mst_gst', $data, "id='".$this->input->post('id')."'");
              $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }


        public function update($id) 
      {
        $tablename = "mst_gst";
        $condition = "id='".$id."'";
        $row = $this->Crud_model->GetData($tablename,'',$condition,'','','','row');
        //print_r($row);exit;
        if ($row) {
          $data = array(
          'action' => site_url('GST/update_action/'.$id),
          'id' => set_value('id', $row->id),
          'category_id' => set_value('category_id', $row->category_id),
          'hsn' => set_value('hsn', $row->hsn),
          'gst_percent' => set_value('gst_percent', $row->gst_percent),
              'markup_percent' => set_value('markup_percent', $row->markup_percent),
          'modified' => date("Y-m-d H:i:s"),
          
           );
              $this->load->view('GST/list', $data);
            } else {
            $this->session->set_flashdata('message', 'No Record Found..!!');
            redirect(site_url('GST/index'));
            }
      }

      public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('mst_gst',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('GST/index');
      }

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('mst_gst','',"id='".$_POST['id']."'",'','','','row');       
        //print_r($row);
        $categories = $this->Crud_model->GetData('categories','','status="Active"');
        //$state = $this->Crud_model->GetData('mst_states','',"country_id='".$row->country_id."' and status='Active'");

        //  echo "$country->name";

         $data = array('gst_percent'=> $row->gst_percent,'markup_percent'=> $row->markup_percent,'id'=> $row->id,'category_id'=> $row->category_id, 'categories'=> $categories,'hsn'=> $row->hsn);
       
          $this->load->view('GST/geteditCity',$data);
      }

    
}