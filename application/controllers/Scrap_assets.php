<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Scrap_assets extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Assets_scrap_model');
  $this->load->model('Pending_assets_scrap_model');
  $this->load->database();
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
      $add = "";
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
      { 
        foreach($row as $menu)
        { 
            if($menu->value=='Scrap_assets')
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
                    <li class='active'>Manage Scrap Assets</li>
                    </ul>";

      if($_SESSION[SESSION_NAME]['branch_id'] != '')              
        $scrap_notification = $this->Crud_model->GetData('notifications','',"type='Scrap' and branch_id='".$_SESSION[SESSION_NAME]['branch_id']."'");
     // print_r($scrap_notification);exit;

        foreach ($scrap_notification as $row) {
        $data1 = array(
                'is_branch_read' => 'Yes',
                'modified' => date("Y-m-d H:i:s"),
                  );
        $this->Crud_model->SaveData('notifications',$data1,"id='".$row->id."'");
      }



        $data = array('breadcrumbs' => $breadcrumbs ,'heading' => 'Manage Scrap Assets','addPermission'=>$add);
        //print_r($data);exit;

    $this->load->view('scrap_assets/list',$data);
    }
    else
    {
      redirect('Dashboard');
    } 
  }

  public function ajax_manage_page()
  {

    //$con="ar.status='Pending'";
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
    $cond="ass.branch_id='".$_SESSION[SESSION_NAME]['branch_id']."'";
    $Data = $this->Assets_scrap_model->get_datatables($cond); 
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Scrap_assets')
            { 
              if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
              if(!empty($row->act_delete)){ $delete='1'; }else{ $delete='0'; }
              if(!empty($row->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            }
        }
      }
    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  


      if($row->sale=='No')
        {
          $sale =  "<span  class='label-warning label'> No </span>";            
        }
        else
        {
            $sale =  "<span class='label-success label'> Yes </span>";
        }
      $status ='';
      if(!empty($actstatus)){ 
         if($row->status=='Pending')
        {
          $status .=  "<span  class='label-warning label'> Pending </span>";            
        }
        else if($row->status=='Approved')
        {
            $status .=  "<span class='label-success label'> Approved </span>";
        }elseif($row->status=='Rejected')
        {
            $status .=  "<span class='label-danger label'> Rejected </span>";
        }
      }

       if(!empty($row->price))
      {
        $price =  "<span  class='fa fa-inr'>&nbsp;".$row->price."</span>";            
      }
      else
      {
          $price =  "-";
      }
      $btn='';
          if(!empty($edit)){   

         if($_SESSION[SESSION_NAME]['type'] !='Admin')  
         {   
          if($row->status =='Pending')
          {  
            $btn ='&nbsp;&nbsp;'.'<a class="btn btn-info btn-circle btn-sm"  title="Edit" href="'.site_url('Scrap_assets/edit/'.$row->id).'"><i class="ace-icon fa fa-edit bigger-130"></i></a>'; 
          }
          else
          {
            $btn ="";
          }  
         }
         else
         {
            $btn ='&nbsp;&nbsp;'.'<a class="btn btn-info btn-circle btn-sm"  title="Edit" href="'.site_url('Scrap_assets/edit/'.$row->id).'"><i class="ace-icon fa fa-edit bigger-130"></i></a>'; 
         } 

      }
      if(!empty($delete))
      {
        if($_SESSION[SESSION_NAME]['type'] !='Admin')  
         {   
          if($row->status =='Pending')
          {  
            $btn .='&nbsp;&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
          }
          else
          {
             $btn .="";
          } 
         }
         else
         {
            $btn .='&nbsp;&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>'; 
         }  
      }
        if(!empty($btn))
        {
          $btn = $btn;
        }
        else
        {
          $btn = '-';
        }
        $no++;
        $nestedData = array();
        $nestedData[] = $no ;
        $nestedData[] = $row->type;
        $nestedData[] = $row->asset_name;
        $nestedData[] = $row->barcode_number;
        $nestedData[] = $sale;
         $nestedData[] = $price;
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Assets_scrap_model->count_all($cond),
                "recordsFiltered" => $this->Assets_scrap_model->count_filtered($cond),
                "data" => $data,
            );
   
    echo json_encode($output);
  }
     public function delete()
      {
        $con = "id='".$_POST['id']."'";      
        $this->Crud_model->DeleteData('assets_scrap',$con);
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px"> Scrap Asset has been deleted successfully</span>');
        redirect(site_url('Scrap_assets'));
      }
  public function create()
  {   
        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'><a href='".site_url('Scrap_assets')."'>Manage Scrap Assets</a></li>
                    <li class='active'>Add Scrap Assets</li>
                    </ul>";
      $asset_types = $this->Crud_model->GetData('mst_asset_types',"","is_delete='No'");            
     /* $assets = $this->Crud_model->GetData('assets',"asset_name,id","");            
      $asset_details = $this->Crud_model->GetData('asset_details',"barcode_number,id","");  */          

       $action =  site_url("Scrap_assets/create_action");                 
       $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'heading' => 'Add Scrap Asset', 
        'button'=>'Add', 
        'action' =>$action,
        'asset_types' => $asset_types ,
      /*  'assets' => $assets ,
        'asset_details' => $asset_details ,*/
        'assets_type_id' => set_value('assets_type_id') ,
        'asset_id' => set_value('asset_id') ,
        'assets_type_id' => set_value('assets_type_id') ,
        'asset_detail_id' => set_value('asset_detail_id') ,
        'barcode_number' => set_value('barcode_number') ,
        'price' => set_value('price') ,
        'sale' => set_value('sale') ,
        );

    $this->load->view('scrap_assets/form',$data);
  }
  public function create_action()
    {
      //print_r($_POST);exit;
    	$id = '0';
		$this->_rules($id);
	  if($this->form_validation->run() == FALSE) 
	  {  
	    $this->create();
	   
	  } 
	  else
	  {  
	  	$barcode_number = $this->Crud_model->GetData('asset_details',"barcode_number",
	                 	"barcode_number='".$_POST['asset_detail']."'",'','','','1');   
	        if(!empty($barcode_number))    
	        { 
              if($_POST['price'] =='')
              {
                $price='';
              }
              else
              {
                $price=$_POST['price'];
              }  


               if($_SESSION[SESSION_NAME]['branch_id']=='0')
              {  

          	         $data = array(
                        'branch_id'=>$_SESSION[SESSION_NAME]['branch_id'],
          	            'asset_type_id'=>$_POST['assets_type_id'],
          	            'asset_id'=>$_POST['assets_id'],
          	            'asset_detail_id'=>$_POST['asset_detail_id'],
                        'sale'=>$_POST['sale'],
          	            'price'=>$price,
          	            'created'=>date('Y-m-d H:i:s'),            
          	            );
          	            $this->Crud_model->SaveData("assets_scrap",$data);  

                        $con="asset_id='".$_POST['assets_id']."' and id not in(select asset_detail_id from asset_branch_mappings_details) and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
                        $asset_details = $this->Crud_model->GetData('asset_details',"id",$con);

                        $avlQuant=count($asset_details);
                        $available_quantity=$avlQuant - '1';
                        $description="Asset in Scrap ".$_POST['asset_detail']." (Qty-1)";
                        $financial_years = $this->Crud_model->GetData('financial_years','id',"status='Active'","","","","1");
                         $dataStockLogs = array(
                        'asset_id'=>$_POST['assets_id'],
                        'financial_year_id'=>$financial_years->id,
                        'asset_type_id'=>$_POST['assets_type_id'],
                        'asset_detail_id'=>$_POST['asset_detail_id'],
                        'branch_id'=>$_SESSION[SESSION_NAME]['branch_id'],
                        'quantity'=>'1',
                        'available_quantity'=>$available_quantity,
                        'description'=>$description,
                        'created_by'=>$_SESSION[SESSION_NAME]['id'],
                        'type'=>"Scrap",
                        'date'=> date('Y-m-d'),
                        'created'=> date('Y-m-d H:i:s'),
                      );

                      $this->Crud_model->SaveData("stock_logs",$dataStockLogs);

                       $data_asset_details = array(
                        'status'=>'Scrap',
                        'modified'=>date('Y-m-d H:i:s'),            
                        );
                        $this->Crud_model->SaveData("asset_details",$data_asset_details,"id='".$_POST['asset_detail_id']."'");


                $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px"> Assets has been added into scrap successfully</span>');
                  redirect('Scrap_assets/index');

                }  
                if($_SESSION[SESSION_NAME]['branch_id']!='0')
                {

                       $data = array(
                        'branch_id'=>$_SESSION[SESSION_NAME]['branch_id'],
                        'asset_type_id'=>$_POST['assets_type_id'],
                        'asset_id'=>$_POST['assets_id'],
                        'asset_detail_id'=>$_POST['asset_detail_id'],
                        'sale'=>$_POST['sale'],
                        'price'=>$price,
                        'status'=>'Pending',
                        'created'=>date('Y-m-d H:i:s'),            
                        );
                        $this->Crud_model->SaveData("assets_scrap",$data);


                        $data_noti = array(
                        'branch_id'=>$_SESSION[SESSION_NAME]['branch_id'],
                        'assets_id'=>$_POST['assets_id'],
                        'asset_details_id'=>$_POST['asset_detail_id'],
                        'is_read' =>'No',
                        'description' =>'Asset Scrap',
                        'type' =>'Scrap',
                        'date'=>date('Y-m-d'),
                        'created'=>date('Y-m-d H:i:s'),            
                        );
                        $this->Crud_model->SaveData("notifications",$data_noti);


                   $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px"> Assets has been successfully added to scrap and notification is send to admin for approval</span>');
                  redirect('Scrap_assets/index');
               }
          }
	        else
	        {
	        	$this->session->set_flashdata('message', 'Invalid Product Sku');
		            redirect('Scrap_assets/create');
	        }    
        }    
     
    }

     public function edit($id)
    {   
        if(!empty($id))
        {  
          $assets_scrap = $this->Crud_model->GetData('assets_scrap',"","id='".$id."'",'','','','1'); 
          if(!empty($id))
          { 
                $breadcrumbs = "<ul class='breadcrumb'>
                            <li>
                                <i class='ace-icon fa fa-home home-icon'></i>
                                <a href='".site_url('Dashboard')."'>Dashboard</a>
                            </li>
                            <li class='active'><a href='".site_url('Scrap_assets')."'>Manage Scrap Assets</a></li>
                            <li class='active'>Edit Scrap Asset</li>
                            </ul>";
                $asset_types = $this->Crud_model->GetData('mst_asset_types',"","is_delete='No'"); 
				          $barcode_number = $this->Crud_model->GetData('asset_details',"barcode_number",
                 	"id='".$assets_scrap->asset_detail_id."'",'','','','1');   

                 $assets = $this->Crud_model->GetData('assets',"","asset_type_id='".$assets_scrap->asset_type_id."'");  
               	         

               $action =  site_url("Scrap_assets/edit_action/".$id);                 
               $data = array(
                'breadcrumbs' => $breadcrumbs ,
                'heading' => 'Edit Scrap Asset', 
                'button'=>'Edit', 
                'action' =>$action,
                'asset_types' => $asset_types ,
                'assets' => $assets ,
                //'asset_details' => $asset_details ,
                'barcode_number' => $barcode_number->barcode_number ,
                'assets_type_id' => $assets_scrap->asset_type_id ,
                'asset_id' => $assets_scrap->asset_id ,
                'asset_detail_id' => $assets_scrap->asset_detail_id   ,
                'sale' => $assets_scrap->sale,
                'price' => $assets_scrap->price,
                );

            $this->load->view('scrap_assets/form',$data);
          }
          else
          {
            redirect('Scrap_assets/index');
          }  
      }
      else
      {
        redirect('Scrap_assets/index');
      }  
    }

    public function edit_action($id)
    {//print_r($_POST);exit;
    $this->_rules($id);
	  if ($this->form_validation->run() == FALSE)
	  {     
	 
	      $this->edit_action($id);
	  }
	  else 
	  { 

		  	$barcode_number = $this->Crud_model->GetData('asset_details',"barcode_number",
	                 	"barcode_number='".$_POST['asset_detail']."'",'','','','1');   
    
	        if(!empty($barcode_number))    
	        {  


	        	$assets_scrap = $this->Crud_model->GetData('assets_scrap',"asset_detail_id",
	                 	"id='".$id."'",'','','','1');    
            	$stock_logs = $this->Crud_model->GetData('stock_logs','id',"type='Scrap' and asset_detail_id ='".$assets_scrap->asset_detail_id."'","","","","1");
          

               $description="Asset in Scrap ".$_POST['asset_detail']." (Qty-1)";


               $con="asset_id='".$_POST['assets_id']."' and id not in(select asset_detail_id from asset_branch_mappings_details) and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
              $asset_details = $this->Crud_model->GetData('asset_details',"id",$con);
              $avlQuant=count($asset_details);
              $available_quantity=$avlQuant - '1';

               $financial_years = $this->Crud_model->GetData('financial_years','id',"status='Active'","","","","1");
               $dataStockLogs = array(
                  'asset_id'=>$_POST['assets_id'],
                  'financial_year_id'=>$financial_years->id,
                  'asset_type_id'=>$_POST['assets_type_id'],
                  'asset_detail_id'=>$_POST['asset_detail_id'],
                //  'branch_id'=>"0",
                  'quantity'=>'1',
                  'available_quantity'=>$available_quantity,
                  'description'=>$description,
                  'created_by'=>$_SESSION[SESSION_NAME]['id'],
                  'type'=>"Scrap",
                  'date'=> date('Y-m-d'),
                  'modified'=> date('Y-m-d H:i:s'),
                );

                $this->Crud_model->SaveData("stock_logs",$dataStockLogs,"id='".$stock_logs->id."'"); 
               
			       if($_POST['price'] =='')
              {
                $price='';
              }
              else
              {
                $price=$_POST['price'];
              }  

          	 
		         $data = array(
		            'asset_type_id'=>$_POST['assets_type_id'],
		            'asset_id'=>$_POST['assets_id'],
		            'asset_detail_id'=>$_POST['asset_detail_id'],
                	'sale'=>$_POST['sale'],
		            'price'=>$_POST['price'],
		            'modified'=>date('Y-m-d H:i:s'),            
		            );
		           $this->Crud_model->SaveData("assets_scrap",$data,"id='".$id."'");  

		    	 $data_asset_details = array(
                  'status'=>'Scrap',
                  'modified'=>date('Y-m-d H:i:s'),            
                  );
                 $this->Crud_model->SaveData("asset_details",$data_asset_details,"id='".$_POST['asset_detail_id']."'");
		             
		           $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Scrap Assets has been edited successfully</span>');
		            redirect('Scrap_assets/index');
		    }
		    else
		    {
		    	$this->session->set_flashdata('message', 'Invalid Product Sku');
		            redirect('Scrap_assets/edit/'.$id);
		    }        
       }     
     
    }

public function getAssets()
{
  if($this->input->post('id') !='0')
  {   
      $id = $this->input->post('id'); 
      $cond = "asset_type_id ='".$id."'";
      if($_SESSION[SESSION_NAME]['branch_id']!='0'){
        $cond .=" and id in(select asset_id from asset_branch_mappings where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."')";
      }
      $getassets = $this->Crud_model->GetData('assets','',$cond,'',"","asset_name");

      if(!empty($getassets))
      {
           $html="<option value=''>-- Select Asset--</option>";
            foreach($getassets as $getassetsRow)
             {
                $html.="<option value=".$getassetsRow->id." >".$getassetsRow->asset_name."</option>";          
             }
      }
      else
      {
          $html="<option value=''>-- Select Asset--</option>";
      }
       echo $html;
  } 
  
}
public function assetDetail($asset_id)
{
	$id = $asset_id; 
  if($_SESSION[SESSION_NAME]['type']=='Admin')
  {

    $cond = "asset_id ='".$id."' and barcode_number like'".$_REQUEST['search']."%' and id not in(select asset_detail_id from asset_branch_mappings_details) and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
  }
  else{
    $cond = "asset_id ='".$id."' and barcode_number like'".$_REQUEST['search']."%' and id in(select asset_detail_id from asset_branch_mappings_details where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."') and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
  }

    $assetDetail = $this->Crud_model->GetData('asset_details','barcode_number,image,id',$cond,'',"","");

	$json=array();

            foreach($assetDetail as $key){

            	if(!empty($key->image))
            	{
            		//$image='<img class="img-thumbnail pull-right" src="'.base_url("uploads/assetimages/".$key->image).'" />';
            	 $image ='';
            	}	
            	else
            	{
            		$image ='';
            	}	
            $json[]=array('label'=>$key->barcode_number.' '.$image,'sub_cat_title'=>$key->id);
            }
  
        echo json_encode($json);exit;  

}

public function pending_scrap_assets()
  {   
      $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Request For Scrap Assets</li>
                    </ul>";
        $scrap_notification = $this->Crud_model->GetData('notifications','',"type='Scrap'");

        foreach ($scrap_notification as $row) {
        $data1 = array(
                'is_read' => 'Yes',
                'modified' => date("Y-m-d H:i:s"),
                  );
        $this->Crud_model->SaveData('notifications',$data1,"assets_id='".$row->assets_id."'");
      }
       
        $data = array('breadcrumbs' => $breadcrumbs ,'heading' => 'Manage Request For Scrap Assets');
        //print_r($data);exit;

    $this->load->view('scrap_assets/pending_scrap_assets',$data);
     
  }

public function ajax_manage_page_pending()
  {

    //$con="ar.status='Pending'";
    /* $edit = ''; 
    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $edit = '';
    $view = '';
    $export_button = '';*/
    $cond="ass.branch_id!='0'";
    $Data = $this->Pending_assets_scrap_model->get_datatables($cond); 
   /* foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Scrap_assets')
            { 
              if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
              if(!empty($row->act_delete)){ $delete='1'; }else{ $delete='0'; }
              if(!empty($row->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            }
        }
      }*/
    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
      $sale ='';
      //if(!empty($actstatus)){ 
        if($row->sale=='No')
        {
          $sale =  "<span  class='label-warning label'> No </span>";            
        }
        else
        {
            $sale =  "<span class='label-success label'> Yes </span>";
        }
     // }

      

         if($row->status=='Pending')
        {
            $status =  "<a href='#myModalStatus' data-toggle='modal' class='label-warning label' onclick='checkStatus(".$row->id.")'> Pending </a>";            
        }
        elseif($row->status=='Approved')
        {
            $status =  "<span class='label-success label'> Approved </span>";
        }
        elseif($row->status=='Rejected')
        {
            $status =  "<span class='label-danger label'> Rejected </span>";
        }


       if(!empty($row->price))
      {
        $price =  "<span  class='fa fa-inr'>&nbsp;".$row->price."</span>";            
      }
      else
      {
          $price =  "-";
      }
    

        $no++;
        $nestedData = array();
        $nestedData[] = $no ;
        $nestedData[] = $row->branch_title;
        $nestedData[] = $row->type;
        $nestedData[] = $row->asset_name;
        $nestedData[] = $row->barcode_number;
        $nestedData[] = $sale;
        $nestedData[] = $price;
        $nestedData[] = $status;
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Pending_assets_scrap_model->count_all($cond),
                "recordsFiltered" => $this->Pending_assets_scrap_model->count_filtered($cond),
                "data" => $data,
            );
   
    echo json_encode($output);
  }


  public function changeStatus()
    {       
        $assets_scrap = $this->Crud_model->GetData('assets_scrap','',"id='".$_POST['id']."'",'','','','row');
       
        
        if($_POST['status']=='Approved')
        {
            $this->Crud_model->SaveData('assets_scrap',array('status'=>'Approved'),"id='".$assets_scrap->id."'");
        }
        else if($_POST['status']=='Rejected')
        {
            $this->Crud_model->SaveData('assets_scrap',array('status'=>'Rejected'),"id='".$assets_scrap->id."'");
        }  

          if($_POST['status'] =='Approved')
          {
                $asset_branch_mappings_data = $this->Crud_model->GetData('asset_branch_mappings','',"asset_id='".$assets_scrap->asset_id."' and branch_id='".$assets_scrap->branch_id."'",'','','','1');

                $this->Crud_model->DeleteData('asset_branch_mappings_details',"branch_id='".$assets_scrap->branch_id."' and asset_detail_id='".$assets_scrap->asset_detail_id."' and asset_id='".$assets_scrap->asset_id."'");

                $asset_quantity = $asset_branch_mappings_data->asset_quantity-1;
                $asset_branch_mappings = array(
                                              'asset_quantity'=>$asset_quantity,
                                              'modified'=>date('Y-m-d H:i:s'),            
                                              );
                $this->Crud_model->SaveData("asset_branch_mappings",$asset_branch_mappings,"id='".$asset_branch_mappings_data->id."'");


                $remove_asset_branch_mappings_data = $this->Crud_model->GetData('asset_branch_mappings','id,asset_quantity',"asset_id='".$assets_scrap->asset_id."' and branch_id='".$assets_scrap->branch_id."'",'','','','1');

                if($remove_asset_branch_mappings_data->asset_quantity == '0')
                {
                   $this->Crud_model->DeleteData('asset_branch_mappings',"branch_id='".$assets_scrap->branch_id."' and id='".$asset_branch_mappings_data->id."'");
                   $available_quantity='0';
                }  
                else
                {


                   $available_quantity=$remove_asset_branch_mappings_data->asset_quantity - '1';
                }  


                $barcode_number = $this->Crud_model->GetData('asset_details',"barcode_number",
                            "id='".$assets_scrap->asset_detail_id."'",'','','','1');

               /* $con="asset_id='".$assets_scrap->asset_id."' and id not in(select asset_detail_id from asset_branch_mappings_details) and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
                $asset_details = $this->Crud_model->GetData('asset_details',"id",$con);
                $avlQuant=count($asset_details);
                $available_quantity=$avlQuant - '1';*/

                 $description="Asset in Scrap ".$barcode_number->barcode_number." (Qty-1)";

                 $financial_years = $this->Crud_model->GetData('financial_years','id',"status='Active'","","","","1");
                 $dataStockLogs = array(
                    'asset_id'=>$assets_scrap->asset_id,
                    'financial_year_id'=>$financial_years->id,
                    'asset_type_id'=>$assets_scrap->asset_type_id,
                    'asset_detail_id'=>$assets_scrap->asset_detail_id,
                    'branch_id'=>$assets_scrap->branch_id,
                    'quantity'=>'1',
                    'available_quantity'=>$available_quantity,
                    'description'=>$description,
                    'created_by'=>$_SESSION[SESSION_NAME]['id'],
                    'type'=>"Scrap",
                    'date'=> date('Y-m-d'),
                    'modified'=> date('Y-m-d H:i:s'),
                  );
                 $this->Crud_model->SaveData("stock_logs",$dataStockLogs); 

                 $data_asset_details = array(
                        'status'=>'Scrap',
                        'modified'=>date('Y-m-d H:i:s'),            
                        );
                        $this->Crud_model->SaveData("asset_details",$data_asset_details,"id='".$assets_scrap->asset_detail_id."'");
            }

            $data_noti = array(
                        'branch_id'=>$assets_scrap->branch_id,
                        'assets_id'=>$assets_scrap->asset_id,
                        'asset_details_id'=>$assets_scrap->asset_detail_id,
                        'is_read' =>'Yes',
                        'is_branch_read' =>'No',
                        'description' =>'Asset Scrap',
                        'type' =>'Scrap',
                        'date'=>date('Y-m-d'),
                        'created'=>date('Y-m-d H:i:s'),            
                        );
                        $this->Crud_model->SaveData("notifications",$data_noti);

           echo '1';exit();

    
    }


public function _rules($id)
{
        $table = 'assets_scrap';
        $cond = "asset_detail_id='".trim($this->input->post('asset_detail_id',TRUE))."' and id!='".$id."' and status ='Approved' or status ='Pending'";
        $row = $this->Crud_model->GetData($table,'',$cond,'','','','1');
       
        $count = count($row); 
        if($count==0)
        {
            $is_unique = "";
        }
        else 
        {
            $is_unique = "|is_unique[assets_scrap.asset_detail_id]";

        } 
        $this->form_validation->set_rules('asset_detail_id', 'Banner Title', 'trim'.$is_unique,
        array(
           // 'required'      => 'Required',
           	'is_unique'     => 'Already exists'
          )); 
        

    $this->form_validation->set_rules('id', 'id', 'trim');
    $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');   
}
}