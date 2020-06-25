<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Assets_request extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Assets_request_model');
  $this->load->model('Assets_request_details_model');
  $this->load->model('Approved_assets_request_model');
  $this->load->database();
  }
  public function index()
  { 

    if($_SESSION[SESSION_NAME]['type']!='Admin'){
      redirect('Assets/req_new_asset_list');
    }

    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $view = '';  
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Assets_request')
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
                    <li class='active'>Manage Assets Request</li>
                    </ul>";

       
        $data = array('breadcrumbs' => $breadcrumbs ,'heading' => 'Manage Assets Request','addPermission'=>$add);

        $this->load->view('assets/assets_request',$data);
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
    $view = '';
    
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Assets_request')
              { 
               if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              }
          }
        }

    //$con="ar.status='Pending'";
    $Data = $this->Assets_request_model->get_datatables(); 
   // print_r($this->db->last_query());exit;
    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
      $assets_request_details = count($this->Crud_model->GetData('assets_request_details','',"asset_request_id='".$row->id."'"));
    	
      if($assets_request_details =='0')
      {
        $total_assets="<span class='badge badge-info'>".$row->total_assets."</span></a>";
      }
      else
      {  
        $total_assets="<a href=".site_url('Assets_request/assets_request_details/'.$row->id)."><span class='badge badge-info'>".$assets_request_details."</span></a>";
      }  
      $total_quantity="<span class='badge badge-default'>".$row->total_quantity."</span>";
      if($row->approved_quantity =='0')
      {
          $approved_quantity="-";
      } 
      else
      {
        $approved_quantity="<a href=".site_url('Assets_request/approved_assets_request/'.$row->id)."><span class='badge badge-success'>".$row->approved_quantity."</span></a>";
      } 

      $status = '';
      if(!empty($actstatus))
      {
          if($row->status=='Pending')
          {
            $status =  "<span  class='label-warning label'> Pending </span>";            
          }
          else if($row->status=='Approved')
          {
              $status =  "<span class='label-success label'> Approved </span>";
          }
          else
          {
              $status =  "<span class='label-danger label'> Rejected </span>";
          }  
      }
      
             
        $no++;
        $nestedData = array();
        $nestedData[] = $no ;
        $nestedData[] = $row->request_no;
        $nestedData[] = $row->branch_title;
        $nestedData[] = $row->name;
        $nestedData[] = $total_assets;
        $nestedData[] = $total_quantity;
        $nestedData[] = $approved_quantity;
        $nestedData[] = $status;
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Assets_request_model->count_all(),
                "recordsFiltered" => $this->Assets_request_model->count_filtered(),
                "data" => $data,
            );
   
    echo json_encode($output);
  }

  public function assets_request_details($request_id)
  {
    if(!empty($request_id))
    {  
      $assets_requests = $this->Crud_model->GetData('assets_requests','branch_id,request_no',"id='".$request_id."'",'','','','1');
      $assets_request_details = $this->Crud_model->GetData('assets_request_details','',"status='Pending' and asset_request_id='".$request_id."'");
      //print_r($assets_request_details);exit;
      
      $branches = $this->Crud_model->GetData('branches','branch_title',"id='".$assets_requests->branch_id."'",'','','','1');
      if(!empty($assets_requests))
      {  
          $heading=  'Assets Request of '.$assets_requests->request_no.'&nbsp;-&nbsp;'.$branches->branch_title;   
          $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li class='active'><a href='".site_url('Assets_request')."'>Manage Assets Request</a></li>
                        <li class='active'>".$heading."</li>
                        </ul>";              
                    
           
          $data = array('breadcrumbs' => $breadcrumbs ,'heading' => $heading,'request_id' => $request_id,'assets_request_details' => $assets_request_details);

          $this->load->view('assets/assets_request_detail',$data);
      }
      else
      {
        redirect('Assets_request/index');
      }    
    }
    else 
    {
       redirect('Assets_request/index');
    }  
  }
  public function ajax_request_detail($request_id)
  {
    $con="ard.asset_request_id='".$request_id."'";
    $Data = $this->Assets_request_details_model->get_datatables($con); 
    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
       $no++;
      $quantity="<span class='badge badge-info'>".$row->quantity."</span> <input type='text' name='approved_quantity[]' id='attributename".$no."' maxlength='6'  class='pull-right  quantity".$row->id." chk_quantity' onkeypress='only_number(event)' placeholder='Approved Quantity' style='display:none'/> <input type='hidden' id='quantitys".$no."' value='".$row->quantity."' class='hidden_qty_".$row->id."'   placeholder='Approved Quantity' />";

      

      if($row->status=='Pending')
      {
       // $status =  "<span class='label-warning label'> Pending </span>";     
        $status =  "<a data-toggle='modal' data-target='#checkStatus' data-toggle='modal' class='label-warning label' onclick='checkStatus(".$row->id.")'> Pending </a>";         
      }
      else if($row->status=='Rejected')
      {
          $status =  "<span class='label-danger label'> Rejected </span>";
      }else if($row->status=='Approved')
      {
          $status =  "<span class='label-success label'> Approved </span>";
      }
     if(empty($_POST['SearchData1']))
      {
          $client_ids = array();
      }else{
          $client_ids = explode(',', $_POST['SearchData1']);     

          
      }

	 if($_POST['select_all']=="true")
      {
          $chked = "checked";
      }else if(in_array($row->id, $client_ids)){
          $chked = "checked";
      }else{

          $chked = "";
      }

       
     if($row->status =='Pending')
      { 
        $chk = '<input type="checkbox" name="client_id" id="client_id_'.$row->id.'" '.$chked.' onchange="checkbox_all('.$row->id.');" class="client_id client_id_'.$row->id.'" value="'.$row->id.'">';
      }
      else
      {
        $chk='-';
      }

      if($row->asset_id == '0')
      {
          $asset_name=ucwords($row->asset_name).'&nbsp;&nbsp;'.'<span class="label label-danger">New</span>';
      }  
      else
      {
        $asset_name=ucwords($row->asset_name);
      }  

      if(!empty($row->description))
      {
          if(strlen($row->description)>20) 
          { 
            $description=substr($row->description, 0, 18).'<div data-toggle="modal" data-target="#myModal" style="cursor:pointer;color:lightblue" onclick="return getData('.$row->id.')">...read more</div>';
          } 
          else 
          {
            $description=$row->description; 
          }
      }  
      else
      {
        $description='-';
      }  


       
        $nestedData = array();
        $nestedData[] = $chk ;
      //  $nestedData[] = $no ;
        $nestedData[] = $asset_name;
        $nestedData[] = $quantity;
        $nestedData[] = $description;
        $nestedData[] = $status;
        $data[] = $nestedData;
        $selected = '';
    }
 	$filter = $this->Assets_request_details_model->count_client_filtered($con);
    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Assets_request_details_model->count_all($con),
                "recordsFiltered" => $this->Assets_request_details_model->count_filtered($con),
                   "recordsFiltered" => $this->Assets_request_details_model->count_filtered($con),
                "data" => $data,
                "ids" => $filter->ids,
            );
   
    echo json_encode($output);
  }
  public function changeStatus(){
        
        $change_status = $this->Crud_model->GetData('assets_request_details','',"id='".$_POST['id']."'",'','','','row');
       // print_r($change_status);exit;

        if($change_status->status=='Pending')
        {
            $this->Crud_model->SaveData('assets_request_details',array('status'=>'Rejected'),"id='".$_POST['id']."'");
        }

         $assets_request_details_p = count($this->Crud_model->GetData('assets_request_details','',"status='Pending' and asset_request_id='".$change_status->asset_request_id."'"));
        $assets_request_details_a = count($this->Crud_model->GetData('assets_request_details','',"status='Approved' and asset_request_id='".$change_status->asset_request_id."'"));
        
      // print_r($assets_request_details_p);exit;

          if($assets_request_details_a > '0')
          { 
              $data = array(                
                'status'=>'Approved',    
              );
          }
          else if($assets_request_details_p > '0') 
          {
             $data = array(                
               'status'=>'Pending',    
              );
          } 
          else
          {
            $data = array(                
               'status'=>'Rejected',    
              );
          }
          $this->Crud_model->SaveData("assets_requests",$data,"id='".$change_status->asset_request_id."'");
        
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Assets_request/assets_request_details/'.$change_status->asset_request_id);
    }
   
   public function approvedAssets_action($request_id)
   {
      $asset_details_id = $_POST['asset_details_id'];
      $explode_asset_details_id = explode(',', $asset_details_id);
      $count_asset_id=count($explode_asset_details_id);
      $quantity=$this->input->post('approved_quantity[]');
      $approved_quantity=array_sum($quantity);

      $assets_requests =$this->Crud_model->GetData('assets_requests','approved_quantity',"id='".$request_id."'",'','','','1');
      $sumApprovedQuantity=$assets_requests->approved_quantity + $approved_quantity;

     $data = array(                
          	'approved_quantity' => $sumApprovedQuantity, 
          	'modified'=>date('Y-m-d H:i:s'),            
          );
     $this->Crud_model->SaveData("assets_requests",$data,"id='".$request_id."'");

      $asset_name_count = count($this->input->post('approved_quantity[]'));
        for($i=0;$i<$asset_name_count;$i++)
      	{
	        if($_POST['approved_quantity'][$i]!='Approved')
	        {
             $datadetail = array(
  	          	'status'=>'Approved',
  	            'approved_quantity'=>$_POST['approved_quantity'][$i],
  	           	'modified'=> date('Y-m-d H:i:s'),
  	          );
  	          $con="id = '".$explode_asset_details_id[$i]."'"; 
  	          $this->Crud_model->SaveData("assets_request_details",$datadetail,$con);
           }
      	}

        $assets_request_details_p = count($this->Crud_model->GetData('assets_request_details','',"status='Pending' and asset_request_id='".$request_id."'"));
        $assets_request_details_a = count($this->Crud_model->GetData('assets_request_details','',"status='Approved' and asset_request_id='".$request_id."'"));
        
     //  print_r($assets_request_details_a);exit;
          if($assets_request_details_p > '0') 
          {
             $data = array(                
               'status'=>'Pending',    
              );
          } 
            else if($assets_request_details_a > '0')
          { 
              $data = array(                
                'status'=>'Approved',    
              );
          }
        
          else
          {
            $data = array(                
               'status'=>'Rejected',    
              );
          }
          $this->Crud_model->SaveData("assets_requests",$data,"id='".$request_id."'");

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Request approved successfully</span>');

         $assets_requests =$this->Crud_model->GetData('assets_requests','',"id='".$request_id."' and status='Approved'",'','','','1');
         if(empty($assets_requests))
         {
            
             redirect('Assets_request/assets_request_details/'.$request_id);
         }
         else
         {
            redirect('Assets_request');

         }
   }
   public function getData()
    { 
      $id = $this->input->post("id"); 
      $row = $this->Crud_model->GetData("assets_request_details","asset_name,description","id='".$id."'");
      $data = array('description' => ucwords($row[0]->description),'title' => ucwords($row[0]->asset_name) );
      echo json_encode($data);
    }

     public function approved_assets_request($request_id)
  {
    if(!empty($request_id))
    {  
      $assets_requests = $this->Crud_model->GetData('assets_requests','branch_id,request_no',"id='".$request_id."'",'','','','1');
      $assets_request_details = $this->Crud_model->GetData('assets_request_details','',"status='Pending' and asset_request_id='".$request_id."'");
      //print_r($assets_request_details);exit;
      
      $branches = $this->Crud_model->GetData('branches','branch_title',"id='".$assets_requests->branch_id."'",'','','','1');
      if(!empty($assets_requests))
      {  
          $heading=  'Assets Request of '.$assets_requests->request_no.'&nbsp;-&nbsp;'.$branches->branch_title;   
          $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li class='active'><a href='".site_url('Assets_request')."'>Manage Assets Request</a></li>
                        <li class='active'>".$heading."</li>
                        </ul>";              
                    
           
          $data = array('breadcrumbs' => $breadcrumbs ,'heading' => $heading,'request_id' => $request_id);

          $this->load->view('assets/approved_assets_request',$data);
      }
      else
      {
        redirect('Assets_request/index');
      }    
    }
    else 
    {
       redirect('Assets_request/index');
    }  
  }
  public function approved_assets_request_ajax($request_id)
  {
    $con="ard.status='Approved' and ard.asset_request_id='".$request_id."'";
    $Data = $this->Approved_assets_request_model->get_datatables($con); 

    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
      $no++;
      $quantity="<span class='badge badge-info'>".$row->quantity."</span>";
      $approved_quantity="<span class='badge badge-info'>".$row->approved_quantity."</span>";
      $status =  "<span class='label-success label'> Approved </span>";
     

      if($row->asset_id == '0')
      {
          $asset_name=$row->asset_name.'&nbsp;&nbsp;'.'<span class="label label-danger">New</span>';
      }  
      else
      {
        $asset_name=$row->asset_name;
      }     

      if(!empty($row->description))
      {
          if(strlen($row->description)>25) 
          { 
            $description=substr($row->description, 0, 20).'<div data-toggle="modal" data-target="#myModal" style="cursor:pointer;color:lightblue" onclick="return getData('.$row->id.')">...read more</div>';
          } 
          else 
          {
            $description=$row->description; 
          }
      }  
      else
      {
        $description='-';
      }    
       
        $nestedData = array();
        $nestedData[] = $no;
        $nestedData[] = $asset_name;
        $nestedData[] = $quantity;
        $nestedData[] = $approved_quantity;
        $nestedData[] = $description;
        $nestedData[] = $status;
        $data[] = $nestedData;
        $selected = '';
    }
  
    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Approved_assets_request_model->count_all($con),
                "recordsFiltered" => $this->Approved_assets_request_model->count_filtered($con),
                "data" => $data,
       );
   
    echo json_encode($output);
  }
}
