<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/mpdf/vendor/autoload.php');
class Audit_Report extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->library('upload');
  $this->load->library('image_lib');
  $this->image_lib->clear();   
  $this->load->helper(array('form','url','html'));
  $this->load->database();
  }
  public function search()
  {
    if($this->input->post())
    {
      if($this->input->post('date') != "")
      {
          $date = $this->input->post('date');
      } else {
          $date = 2020;
      }
      
      //$newDate = date("Y-m-d", strtotime($date));
	    $strUrl = site_url('Audit_Report/ajax_manage_page/' . $date);
      $this->common_view($strUrl,$date); 
	  
    }
    else
    {
       return redirect('Audit_Report');
    }
       
  }
  public function index()
  {   
    $this->common_view(site_url('Audit_Report/ajax_manage_page'));
  }
  public function common_view($action,$date=2020)
  {   
    // print_r($_SESSION[SESSION_NAME]);exit;
    $import = '';
    $add = '';
    $exportbutton='';
  	if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
	    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
	    { 
	      foreach($row as $menu)
	      { 
	          if($menu->value=='Audit_Report')
	          { 
	            
              if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              if(!empty($menu->act_export)){ $exportbutton='1'; }else{ $exportbutton='0'; }
              if(!empty($menu->act_import)){ $import='1'; }else{ $import='0'; }
	          }
	      }
	    }

	    $breadcrumbs = "<ul class='breadcrumb'>
	                <li>
	                    <i class='ace-icon fa fa-home home-icon'></i>
	                    <a href='".site_url('Dashboard')."'>Dashboard</a>
	                </li>
	                <li class='active'>Audit Report</li>
	                </ul>";

	    $importaction = '';//'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
      $export ='<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>'; 
	    $download = '';//'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 

      $this->db->select('YEAR(min(purchase_date)) as min_date, YEAR(max(purchase_date)) as max_date');
      $this->db->from("assets");
      if($_SESSION[SESSION_NAME]['type'] != 'SuperAdmin')
        $this->db->where('created_by',$_SESSION[SESSION_NAME]['id']);
      
      $query = $this->db->get();
      $dateRange = $query->result();
      $min_year = 2015;
      $max_year = $dateRange[0]->max_date;

      $dateCheck = new DateTime();
      $openingStockDate = $dateCheck->setDate($date, 3, 31)->format('Y-m-d');

      if(file_exists('admin/assets/audit_report/Audit Report'.'-'.$date.'-'.($date+1).'.xlsx'))
        $download = 1; 
      else
        $download = 0;

	    $data = array(
        'selected_year' => $date,
        'openingStockDate' => $openingStockDate,
        'min_year'=>$min_year,'max_year'=>$max_year,
        'dateinfo'=>$date,
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '5' ,
        'ajax_manage_page' => $action,
        'heading' => 'Audit Report',
        'addPermission'=>$add,
        'importaction' => $importaction,
        'download' => $download,
        'import' => $import,
        'export' =>$export,
        'exportPermission'=>$exportbutton,
        'download' =>$download,
        'fileName' =>'Audit Report'.'-'.$date.'-'.($date+1).'.xlsx',
        'filePath' =>base_url('admin/assets/audit_report/Audit Report'.'-'.$date.'-'.($date+1).'.xlsx'),
      );

	    $this->load->view('audit_report/list',$data);
  	}
  	else
  	{
  		redirect('Dashboard');
  	}
  }

  public function ajax_manage_page($date=2020)
  {
    $dateCheck = new DateTime();
    $purchaseDate = $dateCheck->setDate($date, 3, 31)->format('Y-m-d');
    $purchaseDateUpper = $dateCheck->setDate(($date+1), 4, 1)->format('Y-m-d');
    $purchaseDateLower = $dateCheck->setDate(($date-1), 4, 1)->format('Y-m-d');

    $this->db->select(
    'a.id,a.asset_name,a.product_mrp,a.quantity,a.total_quantity,a.lf_no,a.purchase_date,a.physical_qty,a.damage_qty,
     a.physical_status,a.qty_before_physical
    ');
    $this->db->from("assets a");
    if($_SESSION[SESSION_NAME]['type'] != 'SuperAdmin')
      $this->db->where('a.created_by',$_SESSION[SESSION_NAME]['id']);
      $this->db->where('a.quantity >', 0);
    //$this->db->where('a.purchase_date >', $purchaseDate);
      $this->db->where('a.purchase_date <', $purchaseDateUpper);
    $this->db->order_by('id', "desc");
    $query = $this->db->get();
    $Data = $query->result();

    $data = array();       
    $no=0; 
    
  foreach($Data as $row) 
  { 
    $no++;
    $sr_no = $no;

    $array = array();
    $array['no'] = $sr_no;
    $array['lf_no'] = $row->lf_no;
    $array['asset_name'] = $row->asset_name;
    $array['product_mrp'] = $row->product_mrp;
    $array['book_quantity'] = $row->quantity;
    $array['book_total'] = $row->quantity * $row->product_mrp;

    $array['1year'] = "";
    $array['2year'] = "";
    $array['3year'] = "";
    $array['4year'] = "";

    $startDate = $row->purchase_date;
    $endDate = date('Y-m-d');

    $datetime1 = date_create($startDate);
    $datetime2 = date_create($endDate);
    $interval = date_diff($datetime1, $datetime2, false);
    if ($interval->y < 1) {
      $array['1year'] = $row->quantity * $row->product_mrp;
    } else if ($interval->y > 1 && $interval->y < 2) {
      $array['2year'] = $row->quantity * $row->product_mrp;
    } else if ($interval->y > 2 && $interval->y < 3) {
      $array['3year'] = $row->quantity * $row->product_mrp;
    } else if ($interval->y > 3) {
      $array['4year'] = $row->quantity * $row->product_mrp;
    }
    
    $array['physical_quantity'] = $row->physical_qty;
    if($row->physical_qty == "")
    {
      $array['physical_total'] = "";
      $array['shortage_quantity'] = "";
      $array['shortage_total'] = "";
      $array['excess_quantity'] = "";
      $array['excess_total'] = "";
    } else {
      $array['physical_total'] = $row->physical_qty * $row->product_mrp;
      $array['shortage_quantity'] = "";
      $array['shortage_total'] = "";
      $array['excess_quantity'] = "";
      $array['excess_total'] = "";

      if($row->quantity > $row->physical_qty)
      {
        $array['shortage_quantity'] = $row->quantity - $row->physical_qty;
        $array['shortage_total'] = ($row->quantity - $row->physical_qty) * $row->product_mrp;
      } else if($row->quantity < $row->physical_qty) {
        $array['excess_quantity'] = $row->physical_qty - $row->quantity;
        $array['excess_total'] = ($row->physical_qty -$row->quantity) * $row->product_mrp;
      }
      
    }
    if($row->damage_qty == 0)
    {
      $array['damage_quantity'] = "";
      $array['damage_total'] = "";
    } else {
      $array['damage_quantity'] = $row->damage_qty;
      $array['damage_total'] = $row->damage_qty * $row->product_mrp;
    }
    $this->db->select('quantity');
    $this->db->from("purchase_returns");
    $this->db->where('asset_id',$row->id);
    $query = $this->db->get();
    $purchase_return = $query->result();
    if(count($purchase_return) > 0)
    {
      $array['return_quantity'] = $purchase_return[0]->quantity;
      $array['return_total'] = $row->product_mrp * $purchase_return[0]->quantity;
    } else {
      $array['return_quantity'] = "";
      $array['return_total'] = "";
    }
    
    $this->db->select('quantity, rate_per_item');
    $this->db->from("invoice_details");
    $this->db->where('product_id',$row->id);
    $query = $this->db->get();
    $sales = $query->result();
    if(count($sales) > 0)
    {
      $array['sales_quantity'] = $sales[0]->quantity;
      $array['sales_total'] = $sales[0]->rate_per_item * $sales[0]->quantity;
    } else {
      $array['sales_quantity'] = "";
      $array['sales_total'] = "";
    }

    $this->db->select('quantity, price');
    $this->db->from("sales_return_details");
    $this->db->where('product_id',$row->id);
    $query = $this->db->get();
    $sales_return = $query->result();
    if(count($sales_return) > 0)
    {
      $array['sales_return_quantity'] = $sales_return[0]->quantity;
      $array['sales_return_total'] = $sales_return[0]->price * $sales_return[0]->quantity;
    } else {
      $array['sales_return_quantity'] = "";
      $array['sales_return_total'] = "";
    }

    if($row->purchase_date > $purchaseDate)
    {
      $array['quantity'] = "";
      $array['total'] = "";
      $array['received_quantity'] = $row->quantity;
      $array['received_total'] = $row->product_mrp * $row->quantity;
    } else {
      $array['quantity'] = $row->quantity;
      $array['total'] = $row->product_mrp * $row->quantity;
      $array['received_quantity'] = "";
      $array['received_total'] = "";
    }
    if($row->physical_status == 0)
      $btn = ('<a href="#myModaledit" title="Edit" class="btn btn-info btn-circle btn-sm" data-toggle="modal"  onclick="getEditvalue('.$row->id.');"><i class="ace-icon fa fa-pencil bigger-130"></i></a>');
    else
      $btn = "";
    $array['btn'] = $btn;
    $data[] = $array;
    
  }

    $output = array(
      "data" => $data,
    );
   
    echo json_encode($output);
  }

  public function getUpdateName()
  {
    $row = $this->Crud_model->GetData('assets','',"id='".$_POST['id']."'",'','','','row');       
    echo $row->physical_qty;
  }

  public function updateData()
    {
      $data = array(
        'physical_qty' => $this->input->post('quantity'),   
        'modified' => date('Y-m-d H:i:s'),                   
      );

      $this->Crud_model->SaveData('assets', $data, "id='".$this->input->post('row_id')."'");
      $this->session->set_flashdata('message', 'success');

      echo "1"; 
    }

  public function submit_physical_stock()
  {
    $date = $this->input->post('dateYear');

    $this->export_audit_report($date);

    $dateCheck = new DateTime();
    $purchaseDate = $dateCheck->setDate($date, 3, 31)->format('Y-m-d');
    $purchaseDateLimit = $dateCheck->setDate(($date+1), 4, 1)->format('Y-m-d');

    $this->db->select(
    'a.id,a.asset_name,a.product_mrp,a.quantity,a.total_quantity,a.lf_no,a.purchase_date,a.physical_qty,a.damage_qty
    ');
    $this->db->from("assets a");
    if($_SESSION[SESSION_NAME]['type'] != 'SuperAdmin')
      $this->db->where('a.created_by',$_SESSION[SESSION_NAME]['id']);
    $this->db->where('a.purchase_date >', $purchaseDate);
    $this->db->where('a.purchase_date <', $purchaseDateLimit);
    $this->db->where('a.physical_status =', 0);
    $this->db->where('a.physical_qty !=', NULL);
    $this->db->order_by('id', "desc");
    $query = $this->db->get();
    $Data = $query->result();

    foreach($Data as $row) 
    {  

      $data_array = array(
        'qty_before_physical' => $row->quantity,
        'quantity' => $row->physical_qty,
        'physical_status' => 1
      );

      $this->Crud_model->SaveData("assets", $data_array, "id='" . $row->id . "'");

    }
    $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Physical Stock Updated successfully</span>');
    redirect('Audit_Report/index');
  }

  /* ----- Export functionality start ----- */
    public function export_audit_report($date=2020)
    {
      $dateCheck = new DateTime();
      $purchaseDate = $dateCheck->setDate($date, 3, 31)->format('Y-m-d');
      $purchaseDateLimit = $dateCheck->setDate(($date+1), 4, 1)->format('Y-m-d');

      $this->db->select(
        'a.id,a.asset_name,a.product_mrp,a.quantity,a.total_quantity,a.lf_no,a.purchase_date,a.physical_qty,a.damage_qty
        ');
        $this->db->from("assets a");
        if($_SESSION[SESSION_NAME]['type'] != 'SuperAdmin')
          $this->db->where('a.created_by',$_SESSION[SESSION_NAME]['id']);
        $this->db->where('a.purchase_date >', $purchaseDate);
        $this->db->where('a.purchase_date <', $purchaseDateLimit);
        $this->db->order_by('id', "desc");
        $query = $this->db->get();
        $Data = $query->result();
    
        $data = array();       
        $no=0; 
    
        
      foreach($Data as $row) 
      {  
        $no++;
        $sr_no = $no;
    
        $array = array();
        $array['no'] = $sr_no;
        $array['lf_no'] = $row->lf_no;
        $array['asset_name'] = $row->asset_name;
        $array['product_mrp'] = $row->product_mrp;
        $array['book_quantity'] = $row->quantity;
        $array['book_total'] = $row->quantity * $row->product_mrp;
    
        $array['1year'] = "";
        $array['2year'] = "";
        $array['3year'] = "";
        $array['4year'] = "";
    
        $startDate = $row->purchase_date;
        $endDate = date('Y-m-d');
    
        $datetime1 = date_create($startDate);
        $datetime2 = date_create($endDate);
        $interval = date_diff($datetime1, $datetime2, false);
        if ($interval->y < 1) {
          $array['oneyear'] = $row->quantity * $row->product_mrp;
        } else if ($interval->y > 1 && $interval->y < 2) {
          $array['twoyear'] = $row->quantity * $row->product_mrp;
        } else if ($interval->y > 2 && $interval->y < 3) {
          $array['threeyear'] = $row->quantity * $row->product_mrp;
        } else if ($interval->y > 3) {
          $array['fouryear'] = $row->quantity * $row->product_mrp;
        }
        
        $array['physical_quantity'] = $row->physical_qty;
        if($row->physical_qty == "")
        {
          $array['physical_total'] = "";
          $array['shortage_quantity'] = "";
          $array['shortage_total'] = "";
          $array['excess_quantity'] = "";
          $array['excess_total'] = "";
        } else {
          $array['physical_total'] = $row->physical_qty * $row->product_mrp;
          $array['shortage_quantity'] = "";
          $array['shortage_total'] = "";
          $array['excess_quantity'] = "";
          $array['excess_total'] = "";
    
          if($row->quantity > $row->physical_qty)
          {
            $array['shortage_quantity'] = $row->quantity - $row->physical_qty;
            $array['shortage_total'] = ($row->quantity - $row->physical_qty) * $row->product_mrp;
          } else if($row->quantity < $row->physical_qty) {
            $array['excess_quantity'] = $row->physical_qty - $row->quantity;
            $array['excess_total'] = ($row->physical_qty -$row->quantity) * $row->product_mrp;
          }
          
        }
        if($row->damage_qty == 0)
        {
          $array['damage_quantity'] = "";
          $array['damage_total'] = "";
        } else {
          $array['damage_quantity'] = $row->damage_qty;
          $array['damage_total'] = $row->damage_qty * $row->product_mrp;
        }
        $this->db->select('quantity');
        $this->db->from("purchase_returns");
        $this->db->where('asset_id',$row->id);
        $query = $this->db->get();
        $purchase_return = $query->result();
        if(count($purchase_return) > 0)
        {
          $array['return_quantity'] = $purchase_return[0]->quantity;
          $array['return_total'] = $row->product_mrp * $purchase_return[0]->quantity;
        } else {
          $array['return_quantity'] = "";
          $array['return_total'] = "";
        }
        
        $this->db->select('quantity, rate_per_item');
        $this->db->from("invoice_details");
        $this->db->where('product_id',$row->id);
        $query = $this->db->get();
        $sales = $query->result();
        if(count($sales) > 0)
        {
          $array['sales_quantity'] = $sales[0]->quantity;
          $array['sales_total'] = $sales[0]->rate_per_item * $sales[0]->quantity;
        } else {
          $array['sales_quantity'] = "";
          $array['sales_total'] = "";
        }
    
        $this->db->select('quantity, price');
        $this->db->from("sales_return_details");
        $this->db->where('product_id',$row->id);
        $query = $this->db->get();
        $sales_return = $query->result();
        if(count($sales_return) > 0)
        {
          $array['sales_return_quantity'] = $sales_return[0]->quantity;
          $array['sales_return_total'] = $sales_return[0]->price * $sales_return[0]->quantity;
        } else {
          $array['sales_return_quantity'] = "";
          $array['sales_return_total'] = "";
        }
    
        if($row->purchase_date > $dateCheck->format('Y-m-d'))
        {
          $array['quantity'] = "";
          $array['total'] = "";
          $array['received_quantity'] = $row->total_quantity;
          $array['received_total'] = $row->product_mrp * $row->total_quantity;
        } else {
          $array['quantity'] = $row->total_quantity;
          $array['total'] = $row->product_mrp * $row->total_quantity;
          $array['received_quantity'] = "";
          $array['received_total'] = "";
        }
        $btn = ('<a href="#myModaledit" title="Edit" class="btn btn-info btn-circle btn-sm" data-toggle="modal"  onclick="getEditvalue('.$row->id.');"><i class="ace-icon fa fa-pencil bigger-130"></i></a>');
        $array['btn'] = $btn;
        $data[] = $array;
        
      }
      
      $results = $data;
      $FileTitle='Audit Report';
                
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Report Sheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Audit Report ');

        $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
        $this->excel->getActiveSheet()->setCellValue('B3', 'LF No');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Items');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Rate');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Opening Stock Qty');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Opening Stock Total');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Received Qty');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Received Total');
        $this->excel->getActiveSheet()->setCellValue('I3', 'Purchase Return Qty');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Purchase Return Total');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Sales Amount Qty');
        $this->excel->getActiveSheet()->setCellValue('L3', 'Sales Amount Total');
        $this->excel->getActiveSheet()->setCellValue('M3', 'Sales Return Qty');
        $this->excel->getActiveSheet()->setCellValue('N3', 'Sales Return Total');
        $this->excel->getActiveSheet()->setCellValue('O3', 'Book Stock Qty');
        $this->excel->getActiveSheet()->setCellValue('P3', 'Book Stock Total');
        $this->excel->getActiveSheet()->setCellValue('Q3', 'Physical Stock Qty');
        $this->excel->getActiveSheet()->setCellValue('R3', 'Physical Stock Total');
        $this->excel->getActiveSheet()->setCellValue('S3', 'Shortage Qty');
        $this->excel->getActiveSheet()->setCellValue('T3', 'Shortage Total');
        $this->excel->getActiveSheet()->setCellValue('U3', 'Excess Qty');
        $this->excel->getActiveSheet()->setCellValue('V3', 'Excess Total');
        $this->excel->getActiveSheet()->setCellValue('W3', 'Damage Qty');
        $this->excel->getActiveSheet()->setCellValue('X3', 'Damage Total');
        $this->excel->getActiveSheet()->setCellValue('Y3', 'Upto 1');
        $this->excel->getActiveSheet()->setCellValue('Z3', '1 to 2');
        $this->excel->getActiveSheet()->setCellValue('AA3', '2 to 3');
        $this->excel->getActiveSheet()->setCellValue('AB3', 'Above 2');
            
        $a='4'; $sr = 1;    
        //print_r($results);exit;
        $total_sum = 0;
        $qty = 0;
        $net_amount = 0;
        $arrId = array();
        foreach ($results as $result) 
        { 
          if(in_array($result->id, $arrId))
          {
              $invoice ='';
              $no = '';
            }
          else
          {
              $arrId[] = $result->id;
              $invoice = $result->invoice_no;
              $no = $sr++;
          }   
            
            $this->excel->getActiveSheet()->setCellValue('A'.$a, $no);
            $this->excel->getActiveSheet()->setCellValue('B'.$a, $result['lf_no']);
            $this->excel->getActiveSheet()->setCellValue('C'.$a, $result['asset_name']);
            $this->excel->getActiveSheet()->setCellValue('D'.$a, $result['product_mrp']);
            $this->excel->getActiveSheet()->setCellValue('E'.$a, $result['quantity']);
            $this->excel->getActiveSheet()->setCellValue('F'.$a, $result['total']);                
            $this->excel->getActiveSheet()->setCellValue('G'.$a, $result['received_quantity']);                
            $this->excel->getActiveSheet()->setCellValue('H'.$a, $result['received_total']);                
            $this->excel->getActiveSheet()->setCellValue('I'.$a, $result['return_quantity']);
            $this->excel->getActiveSheet()->setCellValue('J'.$a, $result['return_total']);
            $this->excel->getActiveSheet()->setCellValue('K'.$a, $result['sales_quantity']);   
            $this->excel->getActiveSheet()->setCellValue('L'.$a, $result['sales_total']);
            $this->excel->getActiveSheet()->setCellValue('M'.$a, $result['sales_return_quantity']);
            $this->excel->getActiveSheet()->setCellValue('N'.$a, $result['sales_return_total']);
            $this->excel->getActiveSheet()->setCellValue('O'.$a, $result['book_quantity']);             
            $this->excel->getActiveSheet()->setCellValue('P'.$a, $result['book_total']);
            $this->excel->getActiveSheet()->setCellValue('Q'.$a, $result['physical_quantity']);             
            $this->excel->getActiveSheet()->setCellValue('R'.$a, $result['physical_total']);
            $this->excel->getActiveSheet()->setCellValue('S'.$a, $result['shortage_quantity']);             
            $this->excel->getActiveSheet()->setCellValue('T'.$a, $result['shortage_total']);
            $this->excel->getActiveSheet()->setCellValue('U'.$a, $result['excess_quantity']);             
            $this->excel->getActiveSheet()->setCellValue('V'.$a, $result['excess_total']);
            $this->excel->getActiveSheet()->setCellValue('W'.$a, $result['damage_quantity']);             
            $this->excel->getActiveSheet()->setCellValue('X'.$a, $result['damage_total']);
            $this->excel->getActiveSheet()->setCellValue('Y'.$a, $result['oneyear']);             
            $this->excel->getActiveSheet()->setCellValue('Z'.$a, $result['twoyear']);
            $this->excel->getActiveSheet()->setCellValue('AA'.$a, $result['threeyear']);             
            $this->excel->getActiveSheet()->setCellValue('AB'.$a, $result['fouryear']);
            
             $a++;   
             //$qty += $result->quantity; 
             //$total_sum += $result->total;   
             //$net_amount += $result->net_amount;         
        }
        //$this->excel->getActiveSheet()->setCellValue('G'.$a, $qty);                
        //$this->excel->getActiveSheet()->setCellValue('H'.$a, "Rs. ".number_format($total_sum,2));
        //$this->excel->getActiveSheet()->setCellValue('P'.$a, "Rs. ".number_format($net_amount,2));
        
        //$this->excel->getActiveSheet()->getStyle('G'.$a)->getFont()->setBold(true);                
        //$this->excel->getActiveSheet()->getStyle('H'.$a)->getFont()->setBold(true);
        //$this->excel->getActiveSheet()->getStyle('P'.$a)->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);                
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('M3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('N3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('O3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('P3')->getFont()->setBold(true);
        
        //$this->excel->getActiveSheet()->mergeCells('A1:H1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $filename=''.$FileTitle.'-'.$date.'-'.($date+1).'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        ob_clean();
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save(str_replace(__FILE__,'admin/assets/audit_report/'.$filename,__FILE__));
        $objWriter->save('php://output');   
    }
  /* ----- Export functionality end ----- */

  public function getGST() {
      $select = $this->Crud_model->GetData("assets","","product_id='".$_POST['product_id']."'","","","","1");
      if(!empty($select)) {
        $response['success'] = '1';
        $response['gst_percent'] = $select->gst_percent;
        $response['hsn'] = $select->hsn;
      } else {
        $response['success'] = '0';
      }

      echo json_encode($response);exit;
    }
}