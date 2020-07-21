<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('Dashboard_model');
    $this->load->model('Invoice_model');
    //$this->load->model('Crud_model');
    $this->load->database();
  }

  public function search()
  {
    if($this->input->post())
    {
      $date = $this->input->post('daterange'); 
      $date = date("Y-m-d", strtotime($date)); 
      $date = str_replace("-","_",$date);
    //$date = str_replace("/","-",$date);
    //$date = str_replace(" ","",$date);
      
      //$newDate = date("Y-m-d", strtotime($date));
    $strUrl = site_url('Sales/ajax_manage_page/'.$date);
      $this->common_view($strUrl,$date); 
    
    }
    else
    {
       return redirect('Dashboard');
    }
       
  }
  public function index()
  {   
    
    $this->common_view(site_url('Sales/ajax_manage_page'));
  }
  
  public function common_view($action,$date=0) {
	  /*echo"<pre>";
  	print_r($_SESSION);exit;*/
  	$total_sales = $this->Dashboard_model->GetInvoiceTotalSales();
  	if(!empty($total_sales)) {
  		$response = array();
  		foreach($total_sales as $total_sale) {
  			$tmp['name'] = $total_sale->name;
  			$tmp['y'] = $total_sale->total_sales;
  			array_push($response, $tmp);
  		}

  		$response_data = json_encode($response,JSON_NUMERIC_CHECK);
      
  	} else {
  		$response = array();
  		$response_data = '';
  	}
      $inventory = $this->Invoice_model->get_limitRecord();

     //echo "<pre>"; print_r(); exit();

      $year = $this->datefind();
      $marchDate = $year.'-4-1';

      //yearly
      $where = array('date_of_invoice>='=>$marchDate);
      $arrYear = $this->Dashboard_model->salecount($where);
      

      $today = date('Y').'-'.date('m').'-1';
      $where2 = array('date_of_invoice>='=>$today);
      $arrMonth = $this->Dashboard_model->salecount($where2);

      $today = date('Y-m-d');
      $where3 = array('date_of_invoice='=>$today);
      $arrToday = $this->Dashboard_model->salecount($where3);

  	//print_r();exit;
    $breadcrumbs = "<ul class='breadcrumb'><li class='active'><i class='fa fa-home'></i> Dashboard</li></ul>";
    $data = array(
      'breadcrumbs' => $breadcrumbs,
      'response' => $response_data,
      'inventory' => $inventory,
      'yearSale' => $arrYear,
      'monthSale' => $arrMonth,
      'todaySale' =>  $arrToday,
      'ajax_manage_page' => $action,
      'actioncolumn' => '4'
    );
    
    $this->load->view('dashboard/dashboard',$data);
  }

  public function datefind()
  {
     $month = date('m');
     $year = date('Y');
     if($month == 1 || $month == 2)
     {
         $date = strtotime("-1 year", strtotime($year));
         $year = date("Y", $date);
     }
     return $year;
  }

  public function assetTypeData($flag,$assetId,$assetType) {
            $breadcrumbs='<ul class="breadcrumb">
              <li><i class="ace-icon fa fa-home home-icon"></i>
                <a href="'.site_url('Dashboard/index').'">Dashboard</a>
              </li>
             </ul>';
            if($flag=='return'){
              $con="pb.asset_type_id='".$assetId."' and pb.type='Return' and status='Approved'";
              $returnData = $this->Dashboard_model->Get_returnData($con);
              //print_r($this->db->last_query());exit;
              $data=array(
                'returnData'=>$returnData,
                'breadcrumbs'=>$breadcrumbs,
                'assetType'=>str_replace('%20', ' ', $assetType),
              );
              $this->load->view('dashboard/returnList',$data);
            }
            if($flag=='Instock' || $flag=='Received'){
              $con="prd.asset_type_id='".$assetId."' and prd.is_received = 'Yes' AND prd.is_replaced='No' and prd.is_returned='No'";
              $assetData = $this->Dashboard_model->Getbarcodedata($con);
              //print_r($assetData);exit;
              $data=array(
                'assetData'=>$assetData,
                'breadcrumbs'=>$breadcrumbs,
                'flag'=>$flag,
                'assetType'=>str_replace('%20', ' ', $assetType),
              );
              $this->load->view('dashboard/instockList',$data);

            }
            if($flag=='ReceivedPending'){
              $con="prd.asset_type_id='".$assetId."' and prd.is_received = 'No' AND prd.is_replaced='No' and prd.is_returned='No'";
              $assetData = $this->Dashboard_model->Getbarcodedata($con);
              $data=array(
                'assetData'=>$assetData,
                'breadcrumbs'=>$breadcrumbs,
                'flag'=>$flag,
                'assetType'=>str_replace('%20', ' ', $assetType),
              );
              $this->load->view('dashboard/receivePendingList',$data);

        }
    }

  public function print_barcode($id,$astName)
  {
      $con="asset_id='".$id."' and id not in(select asset_detail_id from asset_branch_mappings_details) and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
    if(!empty($_SESSION[SESSION_NAME]['branch_id'])){

      $con="id in(select asset_detail_id from asset_branch_mappings_details where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."' and asset_id='".$id."') and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
    }
    $asset_details = $this->Crud_model->GetData('asset_details',"",$con,'','','','');

    $data = array(
                'asset_details' => $asset_details,
                'astName' => str_replace('_', " ", $astName),
                  );

    $this->load->view('assets/barcode_print',$data);
  }

  public function get_asset_name()
  {
      $con="status!='Returned' and barcode_number like'".$_REQUEST['search']."%'";
      if(!empty($_SESSION[SESSION_NAME]['branch_id'])){

        $con="id in(select asset_detail_id from asset_branch_mappings_details where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."')  and barcode_number like'".$_REQUEST['search']."%' and status!='Returned'";
      }
      $asset_details = $this->Crud_model->GetData('asset_details',"",$con,'','','','');

       $json=array();

            foreach($asset_details as $key){
                $json[]=array('label'=>$key->barcode_number);
            }
        
        echo json_encode($json);exit;  
  }
  public function dashboard_search_barcode($barcode_number)
  {
    if($barcode_number !=''){
     $asset_details = $this->Crud_model->GetData('asset_details','',"barcode_number = '".$barcode_number."'","","","","1");

     if(!empty($asset_details))
     {
          $breadcrumbs = "<ul class='breadcrumb'><li><i class='fa fa-home'></i><a href='".site_url('Dashboard')."'>Dashboard</a></li><li class='active'> Details of ".$asset_details->barcode_number."</li></ul>";
           $assets = $this->Crud_model->GetData('assets','',"id='".$asset_details->asset_id."'",'','','','1');

           $replaceDetails = '';
        if(!empty($asset_details->parent_id)){
          $replaceDetails = $this->Crud_model->GetData('asset_details','',"asset_id='".$asset_id."' and id='".$asset_details->parent_id."'",'','','','1');
        }
        $assets_maintenance = $this->Crud_model->GetData('assets_maintenance','',"asset_details_id='".$asset_details->id."' and status='Send'",'','','','1');
        $assets_scrap = $this->Crud_model->GetData('assets_scrap','',"asset_detail_id='".$asset_details->id."'",'','','','1');
        $assets_audit_remarks = $this->Crud_model->GetData('assets_audit_remarks','remark_type_id,remarks',"asset_detail_id='".$asset_details->id."'",'','','','1');
        if(!empty($assets_audit_remarks))
        {

        $remark_types = $this->Crud_model->GetData('remark_types','type',"id='".$assets_audit_remarks->remark_type_id."'",'','','','1');
        }  
        else
        {
          $remark_types="";
        }
        $branch_title = $this->Crud_model->branch_title();
        $asset_multiple_images = $this->Crud_model->GetData('asset_multiple_images','image',"asset_details_id='".$asset_details->id."'");


        $data = array(
                    'barcode_number' => $asset_details->barcode_number,
                    'breadcrumbs' => $breadcrumbs,
                    'assets' => $assets,
                    'asset_details' => $asset_details,
                    'replaceDetails' => $replaceDetails,
                    'assets_maintenance' => $assets_maintenance,
                    'assets_scrap' => $assets_scrap,
                    'assets_audit_remarks' => $assets_audit_remarks,
                    'remark_types' => $remark_types,
                    'branch_title' => $branch_title,
                    'asset_multiple_images' => $asset_multiple_images,
                      );

        $this->load->view('dashboard_search_barcode',$data);
      }
      else
      {
         $this->session->set_flashdata('message', '<lable class="label label-danger">Invalid Barcode Number</label>');
        redirect('Dashboard');
      }  
    }
    else{
        redirect('Dashboard');
    }
  }
  public function asset_all_images($asset_id)
  {
    if(!empty($asset_id))
    {
      $asset_multiple_images = $this->Crud_model->GetData('asset_multiple_images','image,id',"asset_details_id = '".$asset_id."' and status='Active'");

      if(!empty($asset_multiple_images))
      {
          $asset_details = $this->Crud_model->GetData('asset_details','barcode_number,id,asset_id',"id = '".$asset_id."'","","","","1");
          $assets = $this->Crud_model->GetData('assets','asset_name,id',"id = '".$asset_details->asset_id."'","","","","1");
          $heading="View All Images ".$assets->asset_name;

            $breadcrumbs = "<ul class='breadcrumb'><li><i class='fa fa-home'></i><a href='".site_url('Dashboard')."'>Dashboard</a></li><li><a href='".site_url('Dashboard/dashboard_search_barcode/'.$asset_details->barcode_number)."'> Details of ".$asset_details->barcode_number."</a></li><li class='active'>".$heading."</li></ul>";

             $data = array(
                        'barcode_number' => $asset_details->barcode_number,
                        'breadcrumbs' => $breadcrumbs,
                        'heading' => $heading,
                        'asset_multiple_images' => $asset_multiple_images,
                   );
            $this->load->view('asset_all_images',$data);
      } 
      else
      {
        redirect('Dashboard');
      }     
    }  
    else{
        redirect('Dashboard');
    }
  }
  public function get_images_desc()
 {
    $id = $this->input->post("id"); 
    $row = $this->Crud_model->GetData("asset_multiple_images","","id='".$id."'");
    $data = array('description' => $row[0]->description, );
    echo json_encode($data);
 }
}