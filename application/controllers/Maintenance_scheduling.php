<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Maintenance_scheduling extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('Assets_maintenance_model');
    $this->load->model('Asset_details_model');
    $this->load->model('Assets_maintenance_scheduling_model');
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
              if($menu->value=='Maintenance_scheduling')
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
                    <li class='active'>Manage Assets Scheduling</li>
                    </ul>";
    $data = array(
                    'actioncolumn' => '7' ,
                    'ajax_manage_page' => site_url('Maintenance_scheduling/ajax_manage_page') ,
                    'heading' => 'Manage Assets Scheduling',
                    'breadcrumbs' =>$breadcrumbs,
                    'action' => site_url('Assets_maintenance/create'),
                    'addPermission'=>$add
                 );

    $this->load->view('maintenance_scheduling/list',$data);
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
    $cond="ams.branch_id='".$_SESSION[SESSION_NAME]['branch_id']."'";
    $getData = $this->Assets_maintenance_scheduling_model->get_datatables($cond);
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Maintenance_scheduling')
            { 
              if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
              if(!empty($row->act_delete)){ $delete='1'; }else{ $delete='0'; }
              if(!empty($row->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            }
        }
      }
    $no = 0;
    $data = array();    
    foreach ($getData as $Data) 
    {
        $btn = '';
         if(!empty($edit)){
        $btn .= ('<a href="'.site_url('Maintenance_scheduling/update/'.base64_encode($Data->id)).'" title="Edit" class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-pencil bigger-130"></i></a>');
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
    	      else if($Data->status=='Inactive')
    	      {
    	          $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$Data->id.")'> Inactive </a>";
    	      }
            else
            {
              $status =  "<span class='label-danger label'> Expired </span>";
            }
        }
          $img="<img src=".base_url('assets/purchaseOrder_barcode/'.$Data->barcode_image)." width='50px' height='50px'>";
       
        if(!empty($Data->description))
        {
          $description = $Data->description;
        }
        else
        {
          $description = '-';
        }

        $no++;
        $nestedData = array();
        $nestedData[] = $no;
        $nestedData[] = ucwords($Data->type);
        $nestedData[] = ucwords($Data->asset_name);
        $nestedData[] = ucwords($Data->name);
        $nestedData[] = $img;
        $nestedData[] = $Data->date;
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
    }    
      $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Assets_maintenance_scheduling_model->count_all($cond),
          "recordsFiltered" => $this->Assets_maintenance_scheduling_model->count_filtered($cond),
          "data" => $data,
        );
    //output to json format
    echo json_encode($output);
}

public function create()
{
  $breadcrumbs='<ul class="breadcrumb">
    <li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="'.site_url('Dashboard/index').'">Dashboard</a>
    </li>
    <li class="active"><a href="'.site_url('Maintenance_scheduling/index').'">Manage Assets Scheduling</a></li>
    <li class="active">Create Scheduling For Asset Maintenance</li>
    </ul>';
 
  $asset_types =$this->Crud_model->GetData('mst_asset_types','',"status='Active' and is_delete='No'","","type"); 

  $data = array(
                'heading' => 'Create Scheduling For Asset Maintenance',
                'button' => 'Create',
                'action' => site_url('Maintenance_scheduling/create_action'),
                'breadcrumbs'=>$breadcrumbs,
                'asset_types' => $asset_types,
                );

  $this->load->view('maintenance_scheduling/create',$data);
}


public function create_scheduling()
{
    //print_r($_POST);exit;
    $asset_type_id = $_POST['asset_type_id'];
    $vendor_id = $_POST['vendor_id'];
    $asset_id = $_POST['asset_id'];
    $count = count($_POST['asset_detail_id']);
    for($i=0;$i<$count;$i++)
    {
      if(!empty($this->input->post('date')[$i]))
      {
  	  $data = array(
                  'branch_id' => $_SESSION[SESSION_NAME]['branch_id'],
                  'created_by' => $_SESSION[SESSION_NAME]['id'],
  	              'asset_type_id' => $asset_type_id,
  	              'asset_id' =>$asset_id,
  	              'vendor_id' =>$vendor_id,
                  'asset_detail_id' =>$this->input->post('asset_detail_id')[$i],
  	              'date' => $this->input->post('date')[$i],
  	              'description' => $this->input->post('description')[$i],
  	              'type' => $this->input->post('type')[$i],
  	              'created' => date("Y-m-d H:i:s"),
  	              );
      //print_r($data);exit;
  	  $this->Crud_model->SaveData('assets_maintenance_schedule',$data);
    }
    }
      $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Asset scheduling created successfully</p></div>');
  	  redirect('Maintenance_scheduling/index');
  
}


public function getAssets()
{
  if($this->input->post('id') !='0')
  {   
      $id = $this->input->post('id'); 
      $cond = "asset_type_id ='".$id."'";
      $getassets = $this->Crud_model->GetData('assets','',$cond,'',"","asset_name");

      $cons="vatm.asset_type_id='".$id."'";
      $getvendors = $this->Assets_maintenance_scheduling_model->GetAssetTypeVendor($cond);

      if(!empty($getassets))
      {
           $assets="<option value=''>-- Select Asset--</option>";
            foreach($getassets as $getassetsRow)
             {
                $assets.="<option value=".$getassetsRow->id.">".$getassetsRow->asset_name."</option>";          
             }
      }
      else
      {
          $assets="<option value=''>-- Select Asset--</option>";
      }

      if(!empty($getvendors))
      {
           $vendors="<option value=''>-- Select Vendors--</option>";
            foreach($getvendors as $getvendorsRow)
             {
                $vendors.="<option value=".$getvendorsRow->id.">".$getvendorsRow->name."(".$getvendorsRow->shop_name.")</option>";          
             }
      }
      else
      {
          $vendors="<option value=''>-- Select Vendors--</option>";
      }

      $dataKey['assets']=$assets;
      $dataKey['vendors']=$vendors;
      echo json_encode($dataKey);exit;
  } 
  
}


public function GetBarcode()
{
     $getData=$this->Crud_model->GetData('asset_details',"","asset_id='".$_POST['id']."' and status='In_use'");
     $data=array(
      'getData'=>$getData,
     );
    $this->load->view('maintenance_scheduling/assets_detail_list',$data);
}


public function update($id)
{
  $id=base64_decode($id);
  $breadcrumbs='<ul class="breadcrumb">
    <li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="'.site_url('Dashboard/index').'">Dashboard</a>
    </li>
    <li class="active"><a href="'.site_url('Maintenance_scheduling/index').'">Manage Assets Scheduling</a></li>
    <li class="active">Update Scheduling For Asset Maintenance</li>
    </ul>';
 
  $cond="ams.id='".$id."'";
  $schedule_data =$this->Assets_maintenance_scheduling_model->GetScheduleData($cond); 
  //print_r($schedule_data);exit;
  $data = array(
                'heading' => 'Update Scheduling For Asset Maintenance',
                'button' => 'Update',
                'action' => site_url('Maintenance_scheduling/update_action'),
                'breadcrumbs'=>$breadcrumbs,
                'rows'=>$schedule_data,
                );

  $this->load->view('maintenance_scheduling/edit_schedule',$data);
}

public function update_action()
{
    $data=array(
      'branch_id' => $_SESSION[SESSION_NAME]['branch_id'],
      'created_by' => $_SESSION[SESSION_NAME]['id'],
      'date'=>$this->input->post('date'),
      'description'=>$this->input->post('date'),
      'modified'=>date("Y-m-d H:i:s"),
    );
    $this->Crud_model->SaveData('assets_maintenance_schedule',$data,"id='".$this->input->post('id')."'");
    $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Asset scheduling updates successfully</p></div>');
    redirect('Maintenance_scheduling/index');
}

public function delete()
{
  $con = "id='".$_POST['id']."'";
  $this->Crud_model->DeleteData('assets_maintenance_schedule',$con);
  $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Schedule has been deleted successfully</span>');
  redirect(site_url('Maintenance_scheduling'));
        
}

 public function changeStatus()
 {
        $change_status = $this->Crud_model->GetData('assets_maintenance_schedule','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('assets_maintenance_schedule',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('assets_maintenance_schedule',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Maintenance_scheduling/index');
    }


public function save_service_notification()
{
  $expired_services=$this->Crud_model->GetData('assets_maintenance_schedule',"","date='".date('y-m-d')."'");
  if(!empty($expired_services))
  {
      foreach($expired_services as $data)
      {
        $service_data=array(
          'assets_id'=>$data->asset_id,
          'asset_details_id'=>$data->asset_detail_id,
          'description'=>'Asset Serivce Expired',
          'type'=>'Serivce',
          'date'=>date("Y-m-d"),
          'created'=>date("Y-m-d H:i:s"),
        );
        $this->Crud_model->SaveData('notifications',$service_data);
      }
  }

  $previous_date=date('Y-m-d', strtotime('-1 day'));
  $previous_services=$this->Crud_model->GetData('assets_maintenance_schedule',"","date='".$previous_date."'");
    foreach($previous_services as $data)
    {
        $expired_status=array(
           'status'=>'Expired',
           'modified'=>date("Y-m-d H:i:s"),
        );
        $this->Crud_model->SaveData('assets_maintenance_schedule',$expired_status,"id='".$data->id."'");
    }

}



}