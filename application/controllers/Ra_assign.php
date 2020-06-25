<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ra_assign extends CI_Controller {

  function __construct()
    {
    parent::__construct();
    $this->load->model("Ra_assign_model");
    }

    //LIST VIEW
  public function index()
  {
    $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Assign Modules</li>
                    </ul>";
        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '3' ,
        'ajax_manage_page' => site_url('Ra_assign/ajax_manage_page') ,
        'button' => 'Create',
        'heading' => 'Manage Assign Modules'
        );
        $this->load->view('ra_assign/list',$data);
  }

    public function ajax_manage_page()
    {
      $condition="";
      $getData = $this->Ra_assign_model->get_datatables();

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
            $btn = ('<a href="'.site_url('Ra_assign/update/'.base64_encode($Data->id)).'" title="Edit Assign Menus" class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-pencil bigger-130"></i></a>');

            $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete Assign Menus" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';

             
            $no++;   
            $row = array();
            $row[] = $no;
            $row[] = $Data->designation_name;
            $row[] = $btn;
            $data[] = $row;
            $selected = '';
        }
 
        $output = array(
          //"draw" => $_POST['draw'],
          "recordsTotal" => $this->Ra_assign_model->count_all(),
          "recordsFiltered" => $this->Ra_assign_model->count_filtered(),
          "data" => $data,
        );
        echo json_encode($output);
    }
   		

    public function assign_role()
    {
      $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Assign Modules</li>
                    </ul>";

      $ra_modules=$this->Crud_model->GetData('ra_modules','',"status='Active'");

      $roles=$this->Crud_model->GetData('mst_designations','',"status='Active'",'','designation_name asc');

        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'action'=>site_url('Ra_assign/create_role_action'),
        'ra_modules'=>$ra_modules,
        'roles'=>$roles,
        'button'=>'Assign',
        $roleId = '',
        $module_id= '',
        $menuId='',
        $list='',
        $add='',
        $edit='',
        $delete='',
        $view='',
        $status='',
        $export='',
        $import='',
        $transfer='',
        $add_existing_stock='',
        $log_details='',
        $send_asset_for_maintenance='',
        $received='',
        );
      $this->load->view('ra_assign/assign',$data);
    }

public function create_role_action() 
{ 

     $tablename = "ra_role_access";
     $cond = "ra_designation_id='".$_REQUEST['ra_designation_id']."'";
    for($ra_module_id = 0; $ra_module_id < count($_REQUEST['ra_module_id']); $ra_module_id++)
    { 
        for($roleAccess=0; $roleAccess < count($_REQUEST['ra_menu_id']); $roleAccess++)
        {    
            $getactionModuleId = $this->Crud_model->GetData('ra_menus','ra_module_id',"id='".$_REQUEST['ra_menu_id'][$roleAccess]."' and  ra_module_id='".$_REQUEST['ra_module_id'][$ra_module_id]."'",'','','','single');
            if(!empty($getactionModuleId))
            {
                $module_id= $getactionModuleId->ra_module_id;
                 if(isset($_POST['list_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['list_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='list'){
                    $list='1';
                  }else{
                    $list='0';
                  }
                }else{
                   $list='0';
                }

                if(isset($_POST['add_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['add_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='add'){
                    $add='1';
                  }else{
                    $add='0';
                  }
                }else{
                   $add='0';
                }
                if(isset($_POST['view_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['view_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='view'){
                    $view='1';
                  }else{
                    $view='0';
                  }
                }else{
                  $view='0';

                }
                if(isset($_POST['edit_'.$_REQUEST['ra_menu_id'][$roleAccess]])){

                  if($_POST['edit_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='edit'){
                    $edit='1';
                  }else{
                    $edit='0';
                  }
                }else{
                  $edit='0';
                }

                 if(isset($_POST['delete_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['delete_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='delete'){
                    $delete='1';
                  }else{
                    $delete='0';
                  }
                }else{
                  $delete='0';
                }

              if(isset($_POST['status_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['status_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='status'){
                    $status='1';
                  }else{
                    $status='0';
                  }
                }else{
                  $status='0';
                }

                  if(isset($_POST['import_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['import_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='import'){
                    $import='1';
                  }else{
                    $import='0';
                  }
                }
                  else{
                    $import='0';
                  }

                if(isset($_POST['export_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['export_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='export'){
                    $export='1';
                  }else{
                    $export='0';
                  } }else{
                     $export='0';
                  }

                  if(isset($_POST['transfer_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['transfer_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='transfer'){
                    $transfer='1';
                  }else{
                    $transfer='0';
                  } }else{
                     $transfer='0';
                  }

                  if(isset($_POST['add_existing_stock_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['add_existing_stock_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='add_existing_stock'){
                    $add_existing_stock='1';
                  }else{
                    $add_existing_stock='0';
                  } }else{
                     $add_existing_stock='0';
                  }

                  if(isset($_POST['log_details_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['log_details_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='log_details'){
                    $log_details='1';
                  }else{
                    $log_details='0';
                  } }else{
                     $log_details='0';
                  }

                  if(isset($_POST['send_asset_for_maintenance_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['send_asset_for_maintenance_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='send_asset_for_maintenance'){
                    $send_asset_for_maintenance='1';
                  }else{
                    $send_asset_for_maintenance='0';
                  } }else{
                     $send_asset_for_maintenance='0';
                  }

                  if(isset($_POST['received_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['received_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='received'){
                    $received='1';
                  }else{
                    $received='0';
                  } }else{
                     $received='0';
                  }

               
                    $data = array(
                      'ra_designation_id' =>$_REQUEST['ra_designation_id'], 
                      'ra_module_id' =>$module_id,
                      'ra_menu_id' =>$_REQUEST['ra_menu_id'][$roleAccess],
                      'status' =>'Active',
                      'act_list'=>$list,
                      'act_add'=>$add,
                      'act_edit'=>$edit,
                      'act_delete'=>$delete,
                      'act_view'=>$view,
                      'act_status'=>$status,
                      'act_export'=>$export,
                      'act_import'=>$import,
                      'act_transfer'=>$transfer,
                      'act_add_existing_stock'=>$add_existing_stock,
                      'act_log_details'=>$log_details,
                      'act_send_asset_for_maintenance'=>$send_asset_for_maintenance,
                      'act_received'=>$received,
                      'created' => date("Y-m-d H:i:s"),
                      );
                    //print_r($data);exit;
                    $this->Crud_model->SaveData($tablename, $data);
            }
        }
    }  
    $this->session->set_flashdata('message', '<div class="label label-xlg label-success arrowed-in-right arrowed"><p>Role has been assign successfully<p></div>');
    redirect(site_url('Ra_assign/index'));
        
}


public function update($id)
{

    $breadcrumbs = "<ul class='breadcrumb'>
                      <li>
                          <i class='ace-icon fa fa-home home-icon'></i>
                          <a href='".site_url('Dashboard')."'>Dashboard</a>
                      </li>
                      <li class='active'>Update Assign Modules</li>
                      </ul>";

    $designation_id=base64_decode($id);
   // print_r($designation_id);exit;
    $cond = "ra_designation_id='".$designation_id."'"; 
    $roleAccess = $this->Crud_model->GetData('ra_role_access','',$cond);
    $getModules= $this->Crud_model->GetData('ra_modules','',"status='Active'");
    $getDesignation= $this->Crud_model->GetData('mst_designations','',"status='Active'");
    if(!empty($roleAccess))
    {
      foreach ($roleAccess as $row)
     {

            $menuId[] = $row->ra_menu_id;

            $getactionModuleId = $this->Crud_model->GetData('ra_menus','',"id='".$row->ra_menu_id."'",'','','','single');
                  //print_r($getactionModuleId);exit;
            if(!empty($getactionModuleId)){

            $module_id[]= $getactionModuleId->ra_module_id;
            }else{
             $module_id='';
            }

          if($row->act_list=='1'){
            $list[]=$row->ra_menu_id;
          }else{
               $list[]='';
          }
          if($row->act_add=='1'){
            $add[]=$row->ra_menu_id;
          }else{
               $add[]='';
          }
          if($row->act_edit=='1'){
            $edit[]=$row->ra_menu_id;
          }else{
               $edit[]='';
          }
          if($row->act_delete=='1'){
            $delete[]=$row->ra_menu_id;
          }else{
               $delete[]='';
          }
          if($row->act_view=='1'){
            $view[]=$row->ra_menu_id;
          }else{
               $view[]='';
          }
          if($row->act_status=='1'){
            $status[]=$row->ra_menu_id;
          }else{
               $status[]='';
          } 
         if($row->act_export=='1'){
            $export[]=$row->ra_menu_id;
          }else{
               $export[]='';
          }   
           if($row->act_import=='1'){
            $import[]=$row->ra_menu_id;
          }else{
               $import[]='';
          } 
           if($row->act_transfer=='1'){
            $transfer[]=$row->ra_menu_id;
          }else{
               $transfer[]='';
          } 
          if($row->act_add_existing_stock=='1'){
            $add_existing_stock[]=$row->ra_menu_id;
          }else{
               $add_existing_stock[]='';
          } 
          if($row->act_log_details=='1'){
            $log_details[]=$row->ra_menu_id;
          }else{
               $log_details[]='';
          } 
          if($row->act_send_asset_for_maintenance  =='1'){
            $send_asset_for_maintenance[]=$row->ra_menu_id;
          }else{
               $send_asset_for_maintenance[]='';
          } 
          if($row->act_received  =='1'){
            $received[]=$row->ra_menu_id;
          }else{
               $received[]='';
          } 

       }
    }
    else
    {
          $menuId= '';
          $module_id= '';
          $list='';
          $add='';
          $edit='';
          $delete='';
          $view='';
          $status='';
          $export='';
          $import='';
          $transfer='';
          $add_existing_stock='';
          $log_details='';
          $send_asset_for_maintenance='';
          $received='';
         
    }
     $data = array('heading'=>'Update Assign Module',
          'subheading'=>'Update Assign Module',
          'breadcrumbs' => $breadcrumbs ,
          'bread'=>'Allow',
          'button'=>'Update',
          'action'=>site_url('Ra_assign/update_role_action/'.$designation_id),                            
          'roles' => $getDesignation,
          'id' =>set_value('id'),
          'ra_modules'=>$getModules,
          'roleId'=>$designation_id,
          'menuId'=>$menuId,
          'module_id'=>$module_id,
          'list'=>$list,
          'add'=>$add,
          'edit'=>$edit,
          'delete'=>$delete,
          'view'=>$view,
          'status'=>$status,
          'export'=>$export,
          'import'=>$import,
          'transfer'=>$transfer,
          'add_existing_stock'=>$add_existing_stock,
          'log_details'=>$log_details,
          'send_asset_for_maintenance'=>$send_asset_for_maintenance,
          'received'=>$received,
  );
  $this->load->view('ra_assign/assign',$data); 
}

public function update_role_action($designation_id)
{
     $cond = "ra_designation_id='".$designation_id."'";
     $this->Crud_model->DeleteData('ra_role_access',$cond);
     $tablename = "ra_role_access";
    for($ra_module_id = 0; $ra_module_id < count($_REQUEST['ra_module_id']); $ra_module_id++)
    { 
        for($roleAccess=0; $roleAccess < count($_REQUEST['ra_menu_id']); $roleAccess++)
        { 
             $getactionModuleId = $this->Crud_model->GetData('ra_menus','ra_module_id',"id='".$_REQUEST['ra_menu_id'][$roleAccess]."' and  ra_module_id='".$_REQUEST['ra_module_id'][$ra_module_id]."'",'','','','single');
            if(!empty($getactionModuleId))
            {
                $module_id= $getactionModuleId->ra_module_id;
                 if(isset($_POST['list_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['list_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='list'){
                    $list='1';
                  }else{
                    $list='0';
                  }
                }else{
                   $list='0';
                }

                if(isset($_POST['add_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['add_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='add'){
                    $add='1';
                  }else{
                    $add='0';
                  }
                }else{
                   $add='0';
                }
                if(isset($_POST['view_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['view_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='view'){
                    $view='1';
                  }else{
                    $view='0';
                  }
                }else{
                  $view='0';

                }
                if(isset($_POST['edit_'.$_REQUEST['ra_menu_id'][$roleAccess]])){

                  if($_POST['edit_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='edit'){
                    $edit='1';
                  }else{
                    $edit='0';
                  }
                }else{
                  $edit='0';
                }

                 if(isset($_POST['delete_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['delete_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='delete'){
                    $delete='1';
                  }else{
                    $delete='0';
                  }
                }else{
                  $delete='0';
                }

              if(isset($_POST['status_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['status_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='status'){
                    $status='1';
                  }else{
                    $status='0';
                  }
                }else{
                  $status='0';
                }

                  if(isset($_POST['import_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['import_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='import'){
                    $import='1';
                  }else{
                    $import='0';
                  }
                }
                  else{
                    $import='0';
                  }

                if(isset($_POST['export_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['export_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='export'){
                    $export='1';
                  }else{
                    $export='0';
                  } }else{
                     $export='0';
                  }

                  if(isset($_POST['transfer_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['transfer_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='transfer'){
                    $transfer='1';
                  }else{
                    $transfer='0';
                  } }else{
                     $transfer='0';
                  }

                  if(isset($_POST['add_existing_stock_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['add_existing_stock_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='add_existing_stock'){
                    $add_existing_stock='1';
                  }else{
                    $add_existing_stock='0';
                  } }else{
                     $add_existing_stock='0';
                  }

                  if(isset($_POST['log_details_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['log_details_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='log_details'){
                    $log_details='1';
                  }else{
                    $log_details='0';
                  } }else{
                     $log_details='0';
                  }

                  if(isset($_POST['send_asset_for_maintenance_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['send_asset_for_maintenance_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='send_asset_for_maintenance'){
                    $send_asset_for_maintenance='1';
                  }else{
                    $send_asset_for_maintenance='0';
                  } }else{
                     $send_asset_for_maintenance='0';
                  }

                  if(isset($_POST['received_'.$_REQUEST['ra_menu_id'][$roleAccess]])){
                  if($_POST['received_'.$_REQUEST['ra_menu_id'][$roleAccess]]=='received'){
                    $received='1';
                  }else{
                    $received='0';
                  } }else{
                     $received='0';
                  }

               
                    $data = array(
                      'ra_designation_id' =>$designation_id, 
                      'ra_module_id' =>$module_id,
                      'ra_menu_id' =>$_REQUEST['ra_menu_id'][$roleAccess],
                      'status' =>'Active',
                      'act_list'=>$list,
                      'act_add'=>$add,
                      'act_edit'=>$edit,
                      'act_delete'=>$delete,
                      'act_view'=>$view,
                      'act_status'=>$status,
                      'act_export'=>$export,
                      'act_import'=>$import,
                      'act_transfer'=>$transfer,
                      'act_add_existing_stock'=>$add_existing_stock,
                      'act_log_details'=>$log_details,
                      'act_send_asset_for_maintenance'=>$send_asset_for_maintenance,
                      'act_received'=>$received,
                      'created' => date("Y-m-d H:i:s"),
                      );
                    $this->Crud_model->SaveData($tablename, $data);
            }
        }
    } 
     $this->session->set_flashdata('message', '<div class="label label-xlg label-success arrowed-in-right arrowed"><p>Role has been updated successfully<p></div>');
    redirect(site_url('Ra_assign/index'));
}


public function delete_assign_menus()
{
      $this->Crud_model->DeleteData('ra_role_access',"ra_designation_id='".$_POST['id']."'");
      $this->session->set_flashdata('message', '<div class="label label-xlg label-danger arrowed-in-right arrowed"><p>Record deleted successfully</p></div>');
   
      redirect(site_url('Ra_assign/index'));
}

}
