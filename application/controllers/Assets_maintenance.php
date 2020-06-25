<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Assets_maintenance extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('Assets_maintenance_model');
    $this->load->model('Asset_details_model');
    $this->load->model('Assets_maintenance_details_model');
    $this->load->database();
  }
  
  public function index()
  { 
    //print_r($_SESSION);exit;
    $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Assets Maintenance</li>
                    </ul>";
    $act_send_asset_for_maintenance = '';              
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    { 
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Assets_Maintenance')
              { 
                if(!empty($menu->act_send_asset_for_maintenance)){ $act_send_asset_for_maintenance='1'; }else{ $act_send_asset_for_maintenance='0'; }
              }
          }
        }


        $data = array(
                        'actioncolumn' => '7' ,
                        'ajax_manage_page' => site_url('Assets_maintenance/ajax_manage_page') ,
                        'heading' => 'Manage Assets Maintenance',
                        'breadcrumbs' =>$breadcrumbs,
                        'action' => site_url('Assets_maintenance/create'),
                        'sendPermission'=>$act_send_asset_for_maintenance
                     );
        //print_r($data);exit;

        $this->load->view('assets_maintenance/assets_maintenance_list',$data);
    }
    else
    {
      redirect('Dashboard');
    }
  }
  
  public function ajax_manage_page()
  {
    if($_SESSION[SESSION_NAME]['type']=='Admin')
    {
      $con="assets_maintenance.branch_id='0' and assets_maintenance.created_by='".$_SESSION[SESSION_NAME]['id']."' and asset_details.id in(select asset_details.id from asset_details where status='In_use')";
    }
    else
    {
      $con="assets_maintenance.branch_id='".$_SESSION[SESSION_NAME]['branch_id']."'";
    }

      $edit= '';
      $actstatus= '';
      $add = '';
      $actreceived = '';
      $actdelete = '';
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
      { 
        foreach($row as $menu)
        { 
            if($menu->value=='Assets_Maintenance')
            { 
              if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
              if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
              if(!empty($menu->act_received)){  $actreceived='1';}else{ $actreceived='0';}
              if(!empty($menu->act_delete)){  $actdelete='1';}else{ $actdelete='0';}
            }
        }
      }

      $getData = $this->Assets_maintenance_model->get_datatables($con);
      //print_r($this->db->last_query());
      $no = 0;
      $data = array();    
      foreach ($getData as $Data) 
      {
          $btn='';
          if(!empty($actreceived))
          {
            if($Data->status == 'Send' && $Data->date <= date('Y-m-d'))
            {
              $btn .='&nbsp;&nbsp; '. '<a style="color:#fff" title="Received" href="'.site_url('Assets_maintenance/assets_maintenance_received/'.$Data->id).'"><span class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-thumbs-up bigger-130"></i></span></a>';
            }
            if($Data->status == 'Received')
            {
              $btn .='&nbsp;&nbsp; '. '<a style="color:#fff" title="View" href="'.site_url('Assets_maintenance/view_assets_maintenance/'.$Data->id).'"><span class="btn btn-warning btn-circle btn-sm"><i class="ace-icon fa fa-eye bigger-130"></i></span></a>';
            }
          }

          if(!empty($actdelete)){
            $btn .='&nbsp;&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
          }
          
          $status = '';
          if(!empty($actreceived)){
      	      if($Data->status=='Send')
      	      {
      	          $status =  "<span class='label-primary label'> Send </span>";            
      	      }
      	      else
      	      {
      	          $status =  "<span class='label-warning label'> Received </span>";
      	      }
          }    
          if(!empty($Data->image))
          {
            $img="<img src=".base_url('uploads/assetimages/'.$Data->image)." width='50px' height='50px'>";
          }
          else
          {
            $img="<img src=".base_url('uploads/employee_images/default.jpg')." width='50px' height='50px'>";
          }
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
          //$nestedData[] = ucwords(checkEmptyHelp($Data->branch_title));
          $nestedData[] = ucwords($Data->asset_name);
          $nestedData[] = $Data->barcode_number;
          $nestedData[] = $img;
          $nestedData[] = $description;
          $nestedData[] = '<i class="fa fa-inr"></i> '.number_format($Data->price,2);
          $nestedData[] = $Data->date;
          $nestedData[] = $status;
          $nestedData[] = $btn;
          $data[] = $nestedData;
      }    
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Assets_maintenance_model->count_all($con),
            "recordsFiltered" => $this->Assets_maintenance_model->count_filtered($con),
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
      <li class="active"><a href="'.site_url('Assets_maintenance/index').'">Manage Assets Maintenance</a></li>
      <li class="active">Send Asset For Maintenance</li>
      </ul>';

    $assets =$this->Crud_model->GetData('assets',"","","","asset_name"); 
    $branches =$this->Crud_model->GetData('branches','',"status='Active'","","branch_title"); 
    $asset_types =$this->Crud_model->GetData('mst_asset_types','',"status='Active' and is_delete='No'","","type"); 

    $data = array(
                  'heading' => 'Send Asset For Maintenance',
                  'button' => 'Send',
                  'action' => site_url('Assets_maintenance/create_action'),
                  'assets' => $assets,
                  'branches' => $branches,
                  'asset_types' => $asset_types,
                  'breadcrumbs' => $breadcrumbs,
                  //'branch_id' =>  set_value('branch_id',$this->input->post('branch_id')),
                  'assets_id' =>  set_value('assets_id',$this->input->post('assets_id')),
                  'assets_type_id' =>  set_value('assets_type_id',$this->input->post('assets_type_id')),
                  'date' =>  set_value('date',$this->input->post('date')),
                  'asset_details_id' =>  set_value('asset_details_id',$this->input->post('asset_details_id')),
                  //'description' =>  set_value('description',$this->input->post('description')),   
                  //'price' =>  set_value('price',$this->input->post('price')),  
                  //'quantity' =>  set_value('quantity',$this->input->post('quantity')),  
                  );

    $this->load->view('assets_maintenance/assets_maintenance_form',$data);
  
}

 public function chkquant()
 {
    $asset_quantity = $this->input->post('value');
    $branch_id = $this->input->post('branch_id');
    $asset_id = $this->input->post('assets_id');

    if($branch_id !='0' && $asset_id !='0' && $asset_quantity !='0')
    {     
        $getQuant = $this->Crud_model->GetData('asset_branch_mappings',"asset_quantity","branch_id='".$branch_id."' and asset_id='".$asset_id."'",'','','','row');

        if($getQuant->asset_quantity < $asset_quantity || $asset_quantity == 0)
        {
            echo 1;
        }
        else
        {
          echo 0;
        }
    }
    else if($branch_id =='0' && $asset_id !='0' && $asset_quantity !='0')
    {
        $forastQaunt = $this->Crud_model->GetData('assets',"id,quantity","id='".$asset_id."'","","","",'row');
        $getassetavilquant = $this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as totalQuantity","asset_id='".$forastQaunt->id."'",'','','','row');

        if(!empty($getassetavilquant))
        {
          $totalQuantity  = $forastQaunt->quantity-$getassetavilquant->totalQuantity;   
        }
        
        if($totalQuantity < $asset_quantity || $asset_quantity == 0)
        {
            echo 1;
        }
        else
        {
          echo 0;
        }
    }
 }


 public function getQuantityShow()
 {
   
    $branch_id = $this->input->post('branch_id');
    $asset_id = $this->input->post('assets_id');

    if($branch_id !='0' && $asset_id !='0')
    {     
        $getQuant = $this->Crud_model->GetData('asset_branch_mappings',"asset_quantity","branch_id='".$branch_id."' and asset_id='".$asset_id."'",'','','','row');

      if(!empty($getQuant))
        {
            $rowgetQuantData['success'] = '1';
            $rowgetQuantData['quantityShow'] = 'Available quantity is '.$getQuant->asset_quantity;                          
        }
        else
        {
            $rowgetQuantData['success'] = '0';
        }       
        echo json_encode($rowgetQuantData);
    }
    else if($branch_id =='0' && $asset_id !='0')
    {
        $forastQaunt = $this->Crud_model->GetData('assets',"id,quantity","id='".$asset_id."'","","","",'row');
        $getassetavilquant = $this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as totalQuantity","asset_id='".$forastQaunt->id."'",'','','','row');
        if(!empty($getassetavilquant))
        {
            $rowgetQuantData['success'] = '1';
            $rowgetQuantData['quantityShow'] = 'Available quantity is '.$getassetavilquant->totalQuantity;                          
        }
        else
        {
            $rowgetQuantData['success'] = '0';
        }       
        echo json_encode($rowgetQuantData);
    }
 }

public function create_action()
{
    if(!empty($_POST['asset_details_id']))
    {
      $asset_details_id = $_POST['asset_details_id'];
      $explode_asset_details_id = explode(',', $asset_details_id);
      $count = count($explode_asset_details_id);
      for($i=0;$i<$count;$i++)
      {
    	  $data = array(
                    'branch_id' => $_SESSION[SESSION_NAME]['branch_id'],
    	              //'branch_id' => $this->input->post('branch_id'),
    	              'asset_details_id' => $explode_asset_details_id[$i],
    	              'assets_id' => $this->input->post('assets_id'),
    	              'asset_type_id' => $this->input->post('assets_type_id'),
    	              'date' => date('Y-m-d',strtotime($this->input->post('date'))),
    	              //'description' => $this->input->post('description'),
    	              //'price' => $this->input->post('price'),
    	              //'quantity' => $this->input->post('quantity'),
    	              'created_by' => $_SESSION[SESSION_NAME]['id'],
    	              'created' => date("Y-m-d H:i:s"),
    	              );
    	  $this->Crud_model->SaveData('assets_maintenance',$data);
      }
        $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Asset send successfully</p></div>');
    	  redirect('Assets_maintenance/index');
    }
    else
    {
      $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>No data found..!!</p></div>');
      redirect('Assets_maintenance/create');
    }
  
}


public function getAssets()
{
  if($this->input->post('id') !='0')
  {   
      $id = $this->input->post('id'); 
      if($_SESSION[SESSION_NAME]['type']=='Admin') {
        $cond = "asset_type_id ='".$id."'";
      }
      else
      {

        $cond = "asset_type_id ='".$id."' and id in(select asset_id from asset_branch_mappings where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."')";
      }
      $getassets = $this->Crud_model->GetData('assets','',$cond,'',"","asset_name");

      if(!empty($getassets))
      {
           $html="<option value=''>-- Select Asset--</option>";
            foreach($getassets as $getassetsRow)
             {
                $html.="<option value=".$getassetsRow->id.">".$getassetsRow->asset_name."</option>";          
             }
      }
      else
      {
          $html="<option value=''>-- Select Asset--</option>";
      }
       echo $html;
  } 
  
}


public function changeStatus()
{
        $change_status = $this->Crud_model->GetData('assets_maintenance','',"id='".$_POST['id']."'",'','','','row');

        if($change_status->status=='Active')
        {
            $this->Crud_model->SaveData('assets_maintenance',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('assets_maintenance',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect('Assets_maintenance/index');
    }

    public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('assets_maintenance',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('Assets_maintenance/index');
      }

    public function view($id)
    {
      $breadcrumbs='<ul class="breadcrumb">
      <li>
      <i class="ace-icon fa fa-home home-icon"></i>
      <a href="'.site_url('Dashboard/index').'">Dashboard</a>
      </li>
      <li class="active"><a href="'.site_url('Assets_maintenance/index').'">Manage Assets Maintenance</a></li>
      <li class="active">View Asset Maintenance</li>
      </ul>';

      $assets_maintenance_data = $this->Crud_model->GetData('assets_maintenance','',"id='".$id."'",'','','','1');

      $assets = $this->Crud_model->GetData('assets','',"id='".$assets_maintenance_data->assets_id."'",'','','','1');
      $branches = $this->Crud_model->GetData('branches','',"id='".$assets_maintenance_data->branch_id."'",'','','','1');

      $data = array(
                    'breadcrumbs' => $breadcrumbs,
                    'assets_maintenance_data' => $assets_maintenance_data,
                    'assets' => $assets,
                    'branches' => $branches,
                    );

      $this->load->view('assets_maintenance/assets_maintenance_view',$data);
    }

    public function Assets_maintenanceData()
    {
        $assets_id = $_POST['assets_id'];
        $asset_details = $this->Crud_model->GetData('asset_details','',"asset_id='".$assets_id."'");

        $data = array(
                    'asset_details'=>$asset_details,
                    'id'=>$assets_id,
                    );
        $this->load->view('assets_maintenance/assets_maintenance_detail',$data);
    }

    public function getAssetdataAjax($id)
    { 
      if($_SESSION[SESSION_NAME]['type']=='Admin') 
      {

        $con="asset_id='".$id."' and id not in(select asset_detail_id from asset_branch_mappings_details) and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_Use'";
      } 
      else
      {
        $con="asset_id='".$id."' and id in(select asset_detail_id from asset_branch_mappings_details where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."') and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_Use'";
      }
        
       $Data = $this->Asset_details_model->get_datatables($con); 
       $data = array();     
       if(empty($_POST['SearchData1']))
        {
            $client_ids = array();
        }else{
            $client_ids = explode(',', $_POST['SearchData1']);     
        }
        $no=0; 
        foreach($Data as $row) 
        { 
          if($_POST['select_all']=="true")
            {
                $chked = "checked";
            }else if(in_array($row->id, $client_ids)){
                $chked = "checked";
            }else{

                $chked = "";
            }


             $chk = '<input type="checkbox" name="client_id" id="client_id_'.$row->id.'" '.$chked.' onchange="checkbox_all('.$row->id.');" class="client_id client_id_'.$row->id.'" value="'.$row->id.'">';

            //$btn ='<a  download="'.$row->barcode_number.'" href="'.base_url("assets/purchaseOrder_barcode/").$row->barcode_image.'" title="Download" class="h3"><i class="fa fa-download"></i></a>';

            //$img ='<img src="'.base_url("assets/purchaseOrder_barcode/").$row->barcode_image.'" title="" width="100px"/>';
            $photo='-';
            if($row->image!='')
            {
              $photo ='<img src="'.base_url("uploads/assetimages/").$row->image.'" title="" width="100px" height="80px" />';
            }
            $price='-';
            if($row->price!='0'){
              $price =$row->price;
            }

            $assets_count = $this->Crud_model->GetData('assets_maintenance','',"asset_details_id='".$row->id."'");

            if(count($assets_count) > 0)
            {
              $btn ='<a style="color:#fff" title="View" href="'.site_url('Assets_maintenance/view_assets_maintenance_details/'.$row->id).'"><span class="badge badge-warning badge-circle btn-sm">'.count($assets_count).'</span></a>';
            }
            else
            {
              $btn ='<a style="color:#fff" title="View" href="#"><span class="badge badge-warning badge-circle btn-sm">'.count($assets_count).'</span></a>';
            }

             
              $no++;
              $nestedData = array();
              $nestedData[] = $chk;       
              $nestedData[] = $no ;
              $nestedData[] = $row->barcode_number;     
              $nestedData[] = $photo;   
              if($_SESSION[SESSION_NAME]['type']=='Admin') 
              {  
                $nestedData[] = ucfirst($row->type);
              }                  
              $nestedData[] = $btn;              
              $data[] = $nestedData;
              $selected = '';
           
        }
        $filter = $this->Asset_details_model->count_client_filtered($con);
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Asset_details_model->count_all($con),
                    "recordsFiltered" => $this->Asset_details_model->count_filtered($con),
                    "data" => $data,
                    "ids" => $filter->ids,
                );
       
        echo json_encode($output);
    
    }

    public function assets_maintenance_received($id)
    {
      if(!empty($id))
      {
      	$breadcrumbs='<ul class="breadcrumb">
  				      <li>
  				      <i class="ace-icon fa fa-home home-icon"></i>
  				      <a href="'.site_url('Dashboard/index').'">Dashboard</a>
  				      </li>
  				      <li class="active">Received Asset For Maintenance</li>
  				      </ul>';

        if($_SESSION[SESSION_NAME]['type']=='Admin') 
        {
        	$con =""; 
        }
        else
        {
          $con ="assets.id in(select asset_id from asset_branch_mappings where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."')";  
        }
        $assets =$this->Crud_model->GetData('assets',"",$con,"","asset_name"); 
    		$asset_types =$this->Crud_model->GetData('mst_asset_types','',"status='Active' and is_delete='No'","","type");
      	$assetsMaintenanceSendData = $this->Crud_model->GetData('assets_maintenance','',"id='".$id."' and status='Send'",'','','','1');
      	$asset_details = $this->Crud_model->GetData('asset_details',"","id='".$assetsMaintenanceSendData->asset_details_id."'",'','','','1');
      	$data = array(
      				'assetsMaintenanceSendData' => $assetsMaintenanceSendData,
      				'assets' => $assets,
      				'asset_types' => $asset_types,
      				'asset_details' => $asset_details,
      				'breadcrumbs' => $breadcrumbs,
              'heading' =>'Received Asset For Maintenance',
      				);

      	$this->load->view('assets_maintenance/assets_maintenance_received',$data);
    }
  }

  public function save_received_data($id)
  {
    if(!empty($id))
    {
    	$data = array(
                  'price' =>$this->input->post('price',TRUE),
                  'description' =>$this->input->post('description',TRUE),
                  'received_date' =>$this->input->post('received_date',TRUE),
                  'status' =>'Received',
                  'created'=> date('Y-m-d H:i:s'),       
                );
	   $this->Crud_model->SaveData('assets_maintenance',$data,"id='".$id."'");
	   $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Asset received successfully</p></div>');
	   redirect('Assets_maintenance/index');
    }
  }

  public function view_assets_maintenance($id)
  {
    $assets_maintenance_data = $this->Crud_model->GetData('assets_maintenance',"","id='".$id."'","","",'','1'); 
    $assets =$this->Crud_model->GetData('assets',"","","","asset_name"); 
    $asset_types =$this->Crud_model->GetData('mst_asset_types','',"status='Active' and is_delete='No'","","type");
    $asset_details = $this->Crud_model->GetData('asset_details',"","id='".$assets_maintenance_data->asset_details_id."'",'','','','1');

    $breadcrumbs='<ul class="breadcrumb">
                <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                </li>
                <li class="active">View Asset Maintenance</li>
                </ul>';

    $data = array(
                'assets_maintenance_data' => $assets_maintenance_data,
                'breadcrumbs' => $breadcrumbs,
                'assets' => $assets,
                'asset_types' => $asset_types,
                'asset_details' => $asset_details,
                'heading' => 'View Asset Maintenance',
                  );

    $this->load->view('assets_maintenance/view_assets_maintenance',$data);
  }

  public function view_assets_maintenance_details($id)
  {
    $breadcrumbs='<ul class="breadcrumb">
                <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="'.site_url('Dashboard/index').'">Dashboard</a>
                </li>
                <li><a href="'.site_url('Assets_maintenance/create').'">Create Asset Maintenance</a></li>
                <li class="active">View Asset Maintenance</li>
                </ul>';
    $data = array(
                  'breadcrumbs' => $breadcrumbs,
                  'heading' => 'View Details',
                  'actioncolumn' => '8' ,
                  'view_asset_maintenance_details' => site_url('Assets_maintenance/view_asset_maintenance_details/'.$id) ,
                  );
    $this->load->view('assets_maintenance/view_assets_maintenance_details',$data);
  }

  public function view_asset_maintenance_details($id)
  {
    if($_SESSION[SESSION_NAME]['type']=='Admin') 
    {
      $con="assets_maintenance.asset_details_id='".$id."' and assets_maintenance.status='Received'";
    }
    else
    {
      $con="assets_maintenance.branch_id='".$_SESSION[SESSION_NAME]['branch_id']."' and assets_maintenance.asset_details_id='".$id."' and assets_maintenance.status='Received'";
    }
    $getData = $this->Assets_maintenance_details_model->get_datatables($con);
    
    $no = 0;
    $data = array();    
    foreach ($getData as $Data) 
    {
        if(!empty($Data->image))
        {
          $img="<img src=".base_url('../admin/uploads/assetimages/'.$Data->image)." width='50px' height='50px'>";
        }
        else
        {
          $img="<img src=".base_url('../admin/uploads/employee_images/default.jpg')." width='50px' height='50px'>";
        }
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
        $nestedData[] = $Data->barcode_number;
        $nestedData[] = $img;
        $nestedData[] = $Data->date;
        $nestedData[] = $Data->received_date;
        $nestedData[] = '<i class="fa fa-inr"></i> '.number_format($Data->price,2);
        $nestedData[] = $description;
        $data[] = $nestedData;
    }    
      $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Assets_maintenance_details_model->count_all($con),
                        "recordsFiltered" => $this->Assets_maintenance_details_model->count_filtered($con),
                        "data" => $data,
                      );
    //output to json format
    echo json_encode($output);
  }





















}