<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Report_model');
 
  $this->load->database();
  }
 
public function index($id)
{
  if(empty($id))
  {
      redirect(site_url('Dashboard'));
  }
   $GetData = $this->Crud_model->GetData('report_format',"","id='".$id."'","","","","1"); 
    if(!empty($GetData))
    {
	    $categories = $this->Crud_model->GetData('categories',"","status='Active'"); 
	    $brands = $this->Crud_model->GetData('brands',"brand_name,id","status='Active'"); 
	    $asset_type_data = $this->Crud_model->GetData('mst_asset_types',"type,id","status='Active' and is_delete='No'",'type');
       	$unit_data =  $this->Crud_model->GetData('unit_types',"unit,id","status='Active'","","unit");            
       	$payment_types =  $this->Crud_model->GetData('payment_types',"type,id","status='Active'","","type");            
      
      	$breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                   <li class='active'>Manage".ucwords($GetData->title)."</li>
                    </ul>";              

      $data = array('heading' => "Manage ".ucwords($GetData->title)." Report",
      'breadcrumbs'=> $breadcrumbs,
      'GetData'=> $GetData,
      'categories'=> $categories,
      'brands'=> $brands,
      'asset_type'=> $asset_type_data,
      'unit'=> $unit_data,
      'payment_types'=> $payment_types,
        );
       $this->load->view('report/list',$data);
    }    
    else
    {
      redirect(site_url('Dashboard'));
    }  
            
}

public function ajax_report_list($id,$table)
    {   
       
        if($table == "Purchase_order")
        {
          echo $this->ajax_purchaseOrder_serverside($id,$table);
        } else if($table == "Vendor")
        {
          echo $this->ajax_vendor_serverside($id,$table);
        }else if($table == "Assets")
        {
          echo $this->ajax_assets_serverside($id,$table);
        }
       
        
    }

public function ajax_vendor_serverside()
{
	print_r($_POST['FormData']);exit;

	$vendor_payments=array('payment_type','bank_account_no','cheque_no','cheque_date','bank_name');
	$vendor=array('name','shop_name');
	$employee_name=array('name');

	for($i=0;$i< count($_POST['FormData']);$i++)
        {
            if(!empty($_POST['FormData'][$i]['value']))
            {
                if(in_array($_POST['FormData'][$i]['name'], $vendor_payments)){
                    $Cond[] = " vp.".$_POST['FormData'][$i]['name']." = '".$_POST['FormData'][$i]['value']."' ";
                }else {
                    $Cond[] = " vp.".$_POST['FormData'][$i]['name']." like '%".$_POST['FormData'][$i]['value']."%' ";
                }if(in_array($_POST['FormData'][$i]['name'], $vendor)){
                    $Cond[] = " v.".$_POST['FormData'][$i]['name']." = '".$_POST['FormData'][$i]['value']."' ";
                }else {
                    $Cond[] = " v.".$_POST['FormData'][$i]['name']." like '%".$_POST['FormData'][$i]['value']."%' ";
                }if(in_array($_POST['FormData'][$i]['name'], $employee_name)){
                    $Cond[] = " e.".$_POST['FormData'][$i]['name']." = '".$_POST['FormData'][$i]['value']."' ";
                }else {
                    $Cond[] = " e.".$_POST['FormData'][$i]['name']." like '%".$_POST['FormData'][$i]['value']."%' ";
                }
            }     
        }
      
        if(!empty($Cond)){
            $Condition = implode(' && ', $Cond);
        }else{
            $Condition = '';
        }
print_r($Condition);exit;
	$Data = $this->ConfigureReport_model->get_datatables($Condition); 


	
}    
public function ajax_purchaseOrder_serverside()
{

    print_r($_POST['FormData']);exit;

    $Data = $this->ConfigureReport_model->get_datatables(); 
   
    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
      

      $status = '';
      if(!empty($actstatus))
      {
          /*if($row->status=='Active')
          {
            $status =  "<span  class='label-warning label'> Active </span>";            
          }
          else if($row->status=='Inactive')
          {
              $status =  "<span class='label-success label'> Inactive </span>";
          }*/

          if($row->status=='Active')
              {
                  $status .=  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$row->id.")'> Active </a>";            
              }
              else
              {
                  $status .= "<a href='#checkStatus' data-toggle='modal' class='label-warning label' onclick='checkStatus(".$row->id.")'> Inactive </a>";
              }
      }
      else
      {
        $status='-';
      }  

      $btn='';
      if(!empty($edit))
      {   
        $btn ='&nbsp;&nbsp;'.'<a class="btn btn-info btn-circle btn-sm"  title="Edit" href="'.site_url('ConfigureReport/update/'.$row->id).'"><i class="ace-icon fa fa-edit bigger-130"></i></a>'; 
      }
      else
      {
        $btn ="";
      }  
         
    
      if(!empty($delete))
      {
        $btn .='&nbsp;&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
      }
      else
      {
         $btn .="";
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
        $nestedData[] = $row->primary_table;
        $nestedData[] = $row->title;
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->ConfigureReport_model->count_all(),
                "recordsFiltered" => $this->ConfigureReport_model->count_filtered(),
                "data" => $data,
            );
   
    echo json_encode($output);
}

  public function get_sub_cat()
    {
      $sub_categories = $this->Crud_model->GetData("sub_categories",'sub_cat_title,id',"category_id='".$_REQUEST['id']."' and status='Active'","","sub_cat_title asc");
    
      if(!empty($sub_categories))
      {
      $response = '<option value="0">Select Sub Category </option>';
      foreach ($sub_categories as $s) 
      {
        $response .= '<option value="'.$s->id.'">'.ucfirst($s->sub_cat_title).'</option>';
      }
      }
      else
      {
      $response = '<option value="0">Select Sub Category </option>';
      }
      echo $response;exit;
    }
}