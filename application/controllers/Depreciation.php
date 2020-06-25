<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Depreciation extends CI_Controller {

  function __construct()
    {
    parent::__construct();
    $this->load->model(array('Crud_model','Depreciation_model'));
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
              if($menu->value=='Depreciation')
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
                        <li class='active'>Manage Assets Depreciation</li>
                        </ul>";
        $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '6' ,
        'ajax_manage_page' => site_url('Depreciation/ajax_manage_page') ,
        'button' => 'Create',
        'heading' => 'Manage Assets Depreciation',
        'addPermission'=>$add
        );
        $this->load->view('depreciation/depreciation_list',$data);
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
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
      { 
        foreach($row as $menu)
        { 
            if($menu->value=='Depreciation')
            { 
              if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
            }
        }
      }

      $condition="";
      $getData = $this->Depreciation_model->get_datatables($condition);
      $data = array();
      if(empty($_POST['start']))
      {
          $no =0;   
      }
      else
      {
          $no =$_POST['start'];
      }
      $status = '';
      $btn = '-';
      foreach ($getData as $Data) 
      {
            if($Data->depreciated_amount<=0 and $Data->dstatus=='In_use')
            {
                $btn .='&nbsp;&nbsp;'.'<a href='.site_url('Depreciation/sendToScrap/'.base64_encode($Data->id)).' data-toggle="modal" title="Send to Scrap" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-paper-plane bigger-130"></i></a>';
            }

            if($Data->dstatus=='In_use')
            {
                $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$Data->id.")'> In Used </a>";
            }
            else
            {
                $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$Data->id.")'> Scrap </a>";
                $btn .='&nbsp;&nbsp;'.'<a href='.site_url('Depreciation/delete/'.base64_encode($Data->id)).' data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="return confirm(\'Are you want to delete this record?\');"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
            }
         
            $img="<img src=".base_url('assets/purchaseOrder_barcode/'.$Data->barcode_image)." width='50px' height='50px'>";

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $Data->asset_name;
            $row[] = $img;
            $row[] = $Data->depreciated_rate;
            $row[] = $Data->actual_amount;
            $row[] = $Data->depreciated_amount;
            $row[] = $status;
            $row[] = $btn;
            $data[] = $row;
      }

      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->Depreciation_model->count_all($condition),
        "recordsFiltered" => $this->Depreciation_model->count_filtered($condition),
        "data" => $data,
      );
      echo json_encode($output);
    }

    public function delete($id)
    {
        $id = base64_decode($id);
        $this->Crud_model->DeleteData("assets_depreciation_log","id='".$id."'");
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Record Deleted successfully</span>');
        redirect('Depreciation');
    }

    public function sendToScrap($id)
    {
        $id = base64_decode($id);
        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li>Manage Assets Depreciation</li>
                    <li class='active'>Add Scrap Assets</li>
                    </ul>";
        $logData = $this->Crud_model->GetData("assets_depreciation_log",'asset_id,asset_detail_id',"id='".$id."'");
        if(!empty($logData))
        {
          $assetData = $this->Crud_model->GetData("assets","id,asset_name,asset_type_id","id='".$logData[0]->asset_id."'");
          $assetTypeData = $this->Crud_model->GetData("mst_asset_types","id,type","id='".$assetData[0]->asset_type_id."'");
          $assetDetails = $this->Crud_model->GetData("asset_details","id,barcode_number","id='".$logData[0]->asset_detail_id."'");
          $data = array(
          'breadcrumbs' => $breadcrumbs ,
          'action' => site_url('Depreciation/send_to_scrap_action') ,
          'assetData' => $assetData,
          'assetDetails' => $assetDetails,
          'assetTypeData' => $assetTypeData,
          'button' => 'Add',
          'heading' => 'Add Scrap Assets'
          );
          $this->load->view('depreciation/add_scrap',$data);
        }
        else
        {
          redirect('Depreciation');
        }
        
    }

    public function send_to_scrap_action()
    {
        $statusData = array('status' => 'Scrap');
        $this->Crud_model->SaveData('asset_details',$statusData,"id='".$_POST['asset_details_id']."'");
        if($_POST['price'] =='')
        {
          $price='';
        }
        else
        {
          $price=$_POST['price'];
        }

        $data = array(
          'asset_type_id'=> $_POST['asset_type_id'],
          'asset_id'=> $_POST['asset_id'],
          'asset_detail_id'=> $_POST['asset_details_id'],
          'sale'=> $_POST['salebale'],
          'price'=> $_POST['price'],
          'modified'=>date('Y-m-d H:i:s'),            
        );
        
        $this->Crud_model->SaveData("assets_scrap",$data);
        $con="asset_id='".$_POST['asset_id']."' and id not in(select asset_detail_id from asset_branch_mappings_details) and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
        $asset_details = $this->Crud_model->GetData('asset_details',"id",$con);
        $avlQuant=count($asset_details);
        $available_quantity=$avlQuant - '1';
        $description="Asset in Scrap ".$_POST['barcode_number']." (Qty-1)";
        $financial_years = $this->Crud_model->GetData('financial_years','id',"status='Active'","","","","1");
        $dataStockLogs = array(
          'asset_id'=>$_POST['asset_id'],
          'financial_year_id'=>$financial_years->id,
          'asset_type_id'=>$_POST['asset_type_id'],
          'asset_detail_id'=>$_POST['asset_details_id'],
          'branch_id'=>"0",
          'quantity'=>'1',
          'available_quantity'=>$available_quantity,
          'description'=>$description,
          'created_by'=>$_SESSION[SESSION_NAME]['id'],
          'type'=>"Scrap",
          'date'=> date('Y-m-d'),
          'created'=> date('Y-m-d H:i:s'),
        );
        $this->Crud_model->SaveData("stock_logs",$dataStockLogs);  
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Status change successfully</span>');
        redirect('Depreciation/index');
    }



}