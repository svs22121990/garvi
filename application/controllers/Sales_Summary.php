<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/mpdf/vendor/autoload.php');
class Sales_Summary extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Sales_Summary_model');
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
      if($this->input->post('daterange') != "")
      {
          $date = $this->input->post('daterange');
          $date = str_replace("-", "_", $date);
          $date = str_replace("/", "-", $date);
          $date = str_replace(" ", "", $date);
      } else {
          $date = 0;
      }
      if($this->input->post('type') != "")
          $type = $this->input->post('type');
      else
          $type = 0;
      if($this->input->post('type2') != "")
          $type2 = $this->input->post('type2');
      else
          $type2 = 0;
      if($this->input->post('type3') != "")
          $type3 = $this->input->post('type3');
      else
          $type3 = 0;
      
      //$newDate = date("Y-m-d", strtotime($date));
	  $strUrl = site_url('Sales_Summary/ajax_manage_page/' . $date . '/'. $type . '/'. $type2 . '/'. $type3);
      $this->common_view($strUrl,$date,$type,$type2,$type3); 
	  
    }
    else
    {
       return redirect('Sales_Summary');
    }
       
  }
  public function index()
  {   
    
    $this->common_view(site_url('Sales_Summary/ajax_manage_page'));
  }
  public function common_view($action,$date=0,$type=0,$type2=0,$type3=0)
  {   
    // print_r($_SESSION[SESSION_NAME]);exit;
    $import = '';
    $add = '';
  	if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
	    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
	    { 
	      foreach($row as $menu)
	      { 
	          if($menu->value=='Sales_Summary')
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
	                <li class='active'>Sales Summary Report</li>
	                </ul>";

	    $importaction = '';//'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
      $export ='<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>'; 
	    $download = '';//'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 

      $salesTypes = $this->Crud_model->GetData("sales_type", "", "status='Active'");
      $paymentModes = $this->Crud_model->GetData("payment_types", "", "status='Active'");

      $types = $this->Crud_model->GetData('mst_asset_types', "", "status='Active' and is_delete='No'", 'type');
      $product_types =  $this->Crud_model->GetData('product_type', "", "status='Active'");

	    $data = array(
        'types' => $types, 'product_types' => $product_types,'selected_date' => $date,'selected_type' => $type, 'selected_type2' => $type2, 'selected_type3' => $type3,
        'dateinfo'=>$date,
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '5' ,
        'ajax_manage_page' => $action,
        'heading' => 'Sales Summary Report',
        'addPermission'=>$add,
        'importaction' => $importaction,
        'download' => $download,
        'import' => $import,
        'export' =>$export,
        'exportPermission'=>$exportbutton,
        'salesTypes' => $salesTypes,
        'paymentModes' => $paymentModes,
      );

	    $this->load->view('sales_summary/list',$data);
  	}
  	else
  	{
  		redirect('Dashboard');
  	}
  }

  public function ajax_manage_page($date=0, $type = 0, $type2 = 0, $type3 = 0)
  {
    $con="i.id<>''";
    if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
    if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
    if($type3 != 0)
        $con .= "and i.invoice_sales_type ='". $type3 . "'";
    $Data = $this->Sales_Summary_model->get_datatables($con,$date);
    
    $edit = ''; 
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
          if($menu->value=='Sales_Summary')
          { 
            
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                if(!empty($menu->act_add_existing_stock)){ $act_add_existing_stock='1'; }else{ $act_add_existing_stock='0'; }
                if(!empty($menu->act_log_details)){ $act_log_details='1'; }else{ $act_log_details='0'; }
                if(!empty($menu->act_transfer)){ $act_transfer='1'; }else{ $act_transfer='0'; }
                if(!empty($menu->act_view)){ $view='1'; }else{ $view='0'; }
          }
      }
    }

    $data = array();       
    $no=0; 
    $arrId = array();
    foreach($Data as $row) 
    {  
		if(in_array($row->id, $arrId))
        {
            $sumAmount = '';
      			$invoice_no = '';
      			$sr_no = '';
        }
        else
        {
    			$no++;
          $arrId[] = $row->id;
          $sumAmount = number_format($row->sumAmount,2);
    			$invoice_no = $row->invoice_no;
    			$sr_no = $no;
        }
        $data[] = array(
          'no' => $sr_no,
          'invoice_no' => $invoice_no,
          'date_of_invoice' => date('d-m-Y', strtotime($row->date_of_invoice)),
          'invoice_sales_type' => $row->salesType,
          'asset_name' => $row->asset_name,
          'paymentMode' => $row->paymentMode,
          'quantity' => $row->quantity,
          'net_amount' => number_format($row->net_amount,2),
          'sumAmount' => $sumAmount,
          'productType' => $row->product_type,
          'assetType' => $row->asset_type,
        );
    }
    $output = array(
                "data" => $data,
            );
   
    echo json_encode($output);
  }
  
  //============================export pdf ============================
  public function listpdf($date=0, $type = 0, $type2 = 0, $type3 = 0)
  {
      $con="i.id<>''";
      if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
      if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
      if($type3 != 0)
        $con .= "and i.invoice_sales_type ='". $type3 . "'";
    
      $data['results'] = $this->Sales_Summary_model->get_datatables($con,$date);

      $html = $this->load->view('sales_summary/pdf_summary',$data,true);
      $mpdf = new \Mpdf\Mpdf();
      $mpdf->WriteHTML($html);
      $mpdf->Output('Sales_Summary_Report','I');
  }
  //============================/export pdf ============================


  /* ----- Export functionality start ----- */
    public function export_sales_summary($date=0, $type = 0, $type2 = 0, $type3 = 0)
    {
      $con="i.id<>''";
      if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
      if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
      if($type3 != 0)
        $con .= "and i.invoice_sales_type ='". $type3 . "'";
      
      $results = $this->Sales_Summary_model->get_datatables($con,$date);
      //$results = $this->Sales_Summary_model->ExportCSV($con);
      $FileTitle='Sales Summary Report';
                
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Report Sheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Sales Summary Details ');

        $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
        $this->excel->getActiveSheet()->setCellValue('B3', 'Invoice No.');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Date');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Type of Sales');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Product Name');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Payment Mode');
        $this->excel->getActiveSheet()->setCellValue('G3', 'Quantity');
        $this->excel->getActiveSheet()->setCellValue('H3', 'Type 1');
        $this->excel->getActiveSheet()->setCellValue('I3', ' Type 2');
        $this->excel->getActiveSheet()->setCellValue('J3', 'Sub-Total');
        $this->excel->getActiveSheet()->setCellValue('K3', 'Total Amount');
            
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
              $sums = '';
              $invoice ='';
              $no = '';
            }
          else
          {
              $arrId[] = $result->id;
              $sums = "Rs. ".number_format($result->sumAmount,2);
              $total_sum += $result->sumAmount;
              $invoice = $result->invoice_no;
              $no = $sr++;
          }   
            
            $this->excel->getActiveSheet()->setCellValue('A'.$a, $no);
            $this->excel->getActiveSheet()->setCellValue('B'.$a, $invoice);
            $this->excel->getActiveSheet()->setCellValue('C'.$a, date('d-m-Y', strtotime($result->date_of_invoice)));
            $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->asset_name);
            $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->salesType);
            $this->excel->getActiveSheet()->setCellValue('F'.$a, $result->paymentMode);                
            $this->excel->getActiveSheet()->setCellValue('G'.$a, $result->quantity);                
            $this->excel->getActiveSheet()->setCellValue('H'.$a, $result->product_type);                
            $this->excel->getActiveSheet()->setCellValue('I'.$a, $result->asset_type);                
            $this->excel->getActiveSheet()->setCellValue('J'.$a, "Rs. ".number_format($result->net_amount,2));
            
            $this->excel->getActiveSheet()->setCellValue('K'.$a, $sums);
             $a++;   
             $qty += $result->quantity; 
             $net_amount += $result->net_amount;         
        }
        $this->excel->getActiveSheet()->setCellValue('G'.$a, $qty);                
        $this->excel->getActiveSheet()->setCellValue('J'.$a, "Rs. ".number_format($net_amount,2));
        $this->excel->getActiveSheet()->setCellValue('K'.$a, "Rs. ".number_format($total_sum,2));
        
        $this->excel->getActiveSheet()->getStyle('G'.$a)->getFont()->setBold(true);                
        $this->excel->getActiveSheet()->getStyle('J'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K'.$a)->getFont()->setBold(true);
        
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
        
        //$this->excel->getActiveSheet()->mergeCells('A1:H1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $filename=''.$FileTitle.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        ob_clean();
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
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
