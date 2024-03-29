<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/mpdf/vendor/autoload.php');
class Daily_Sales extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Daily_Sales_model');
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
	    $strUrl = site_url('Daily_Sales/ajax_manage_page/' . $date . '/'. $type . '/'. $type2 . '/'. $type3);
      $this->common_view($strUrl,$date,$type,$type2,$type3); 
	  
    }
    else
    {
       return redirect('Daily_Sales');
    }
       
  }
  public function index()
  {   
    
    $this->common_view(site_url('Daily_Sales/ajax_manage_page'));
  }
  public function common_view($action,$date=0,$type=0,$type2=0,$type3=0)
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
	          if($menu->value=='Daily_Sales')
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
	                <li class='active'>Daily Sales Report</li>
	                </ul>";

	    $importaction = '';//'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
      $export ='<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>'; 
	    $download = '';//'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 

      $salesTypes = $this->Crud_model->GetData("sales_type", "", "status='Active'");

      $types = $this->Crud_model->GetData('mst_asset_types', "", "status='Active' and is_delete='No'", 'type');
      $product_types =  $this->Crud_model->GetData('product_type', "", "status='Active'");
      if($date != 0)
      {
          $date = str_replace("-", "/", $date);
          $date = str_replace("_", " - ", $date);
      } else {
          $date = 0;
      }
	    $data = array(
        'types' => $types, 'product_types' => $product_types,'selected_date' => $date,'selected_type' => $type, 'selected_type2' => $type2, 'selected_type3' => $type3,
        'dateinfo'=>$date,
        'breadcrumbs' => $breadcrumbs ,
        'actioncolumn' => '5' ,
        'ajax_manage_page' => $action,
        'heading' => 'Daily Sales Report',
        'addPermission'=>$add,
        'importaction' => $importaction,
        'download' => $download,
        'import' => $import,
        'export' =>$export,
        'exportPermission'=>$exportbutton,
        'salesTypes' => $salesTypes,
      );

	    $this->load->view('daily_sales/list',$data);
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
    $Data = $this->Daily_Sales_model->get_datatables($con,$date);
    
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
          if($menu->value=='Daily_Sales')
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
          //$sumAmount = number_format($row->sumAmount,2);
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
          'hsn_code' => $row->hsn_code,
          'rate_per_item' => $row->rate_per_item,
          'total' => $row->total,
          'discount_1' => ($row->discount_1 * $row->quantity),
          'discount_2' => ($row->discount_2 * $row->quantity),
          'discount_3' => ($row->discount_3 * $row->quantity),
          'discount_amount' => $row->discount_amount,
          'taxable' => $row->taxable,
          'net_amount' => number_format($row->net_amount,2),
          'cgst_amount' => $row->cgst_amount,
          'sgst_amount' => $row->sgst_amount,
          'igst_amount' => $row->igst_amount,
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
    
      $data['results'] = $this->Daily_Sales_model->get_datatables($con,$date);

      $html = $this->load->view('daily_sales/pdf_summary',$data,true);
      $mpdf = new \Mpdf\Mpdf();
      $mpdf->WriteHTML($html);
      $mpdf->Output('Sales_Summary_Report','I');
  }
  //============================/export pdf ============================


  /* ----- Export functionality start ----- */
    public function export_daily_sales($date=0, $type = 0, $type2 = 0, $type3 = 0)
    {
      $con="i.id<>''";
      if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
      if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
      if($type3 != 0)
        $con .= "and i.invoice_sales_type ='". $type3 . "'";
      
      $results = $this->Daily_Sales_model->get_datatables($con,$date);
      //$results = $this->Daily_Sales_model->ExportCSV($con);
      $FileTitle='Daily Sales Report';
                
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Report Sheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Gujarat State Handloom & Handicraft Development Corp. Ltd.');
        $this->excel->getActiveSheet()->setCellValue('A2', $_SESSION[SESSION_NAME]['address']);
        $this->excel->getActiveSheet()->setCellValue('A3', $_SESSION[SESSION_NAME]['gst_number']);
        $this->excel->getActiveSheet()->setCellValue('A4', 'Daily Sales Details');

        $this->excel->getActiveSheet()->setCellValue('A5', 'Sr. No');
        $this->excel->getActiveSheet()->setCellValue('B5', 'Date');
        $this->excel->getActiveSheet()->setCellValue('C5', 'Invoice No.');
        $this->excel->getActiveSheet()->setCellValue('D5', 'Description of Goods');
        $this->excel->getActiveSheet()->setCellValue('E5', 'HSN');
        $this->excel->getActiveSheet()->setCellValue('F5', 'Sale Price');
        $this->excel->getActiveSheet()->setCellValue('G5', 'Quantity');
        $this->excel->getActiveSheet()->setCellValue('H5', 'Total Amount');
        $this->excel->getActiveSheet()->setCellValue('I5', 'Discount 1');
        $this->excel->getActiveSheet()->setCellValue('J5', 'Discount 2');
        $this->excel->getActiveSheet()->setCellValue('K5', 'Discount 3');
        $this->excel->getActiveSheet()->setCellValue('L5', 'Total Discount');
        $this->excel->getActiveSheet()->setCellValue('M5', 'Amt After Dic.');
        $this->excel->getActiveSheet()->setCellValue('N5', 'CGST');
        $this->excel->getActiveSheet()->setCellValue('O5', 'SGST');
        $this->excel->getActiveSheet()->setCellValue('P5', 'IGST');
        $this->excel->getActiveSheet()->setCellValue('Q5', 'Total Amount');
            
        $a='6'; $sr = 1;    
        //print_r($results);exit;
        $total_sum = 0;
        $qty = 0;
        $net_amount = 0;
        $sale_price = 0;
        $dis1 = 0;
        $dis2 = 0;
        $dis3 = 0;
        $tax = 0;
        $dis_amt = 0;
        $cgst = 0;
        $sgst = 0;
        $igst=0;

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
            $this->excel->getActiveSheet()->setCellValue('B'.$a, date('d-m-Y', strtotime($result->date_of_invoice)));
            $this->excel->getActiveSheet()->setCellValue('C'.$a, $invoice);
            $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->asset_name);
            $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->hsn_code);
            $this->excel->getActiveSheet()->setCellValue('F'.$a, $result->rate_per_item);                
            $this->excel->getActiveSheet()->setCellValue('G'.$a, $result->quantity);                
            $this->excel->getActiveSheet()->setCellValue('H'.$a, "Rs. ".number_format($result->total,2));                
            $this->excel->getActiveSheet()->setCellValue('I'.$a, ($result->discount_1 * $result->quantity));
            $this->excel->getActiveSheet()->setCellValue('J'.$a, ($result->discount_2 * $result->quantity));
            $this->excel->getActiveSheet()->setCellValue('K'.$a, ($result->discount_3 * $result->quantity));   
            $this->excel->getActiveSheet()->setCellValue('L'.$a, $result->discount_amount);
            $this->excel->getActiveSheet()->setCellValue('M'.$a, $result->taxable);
            $this->excel->getActiveSheet()->setCellValue('N'.$a, $result->cgst_amount);
            $this->excel->getActiveSheet()->setCellValue('O'.$a, $result->sgst_amount);
            $this->excel->getActiveSheet()->setCellValue('P'.$a, $result->igst_amount);
            $this->excel->getActiveSheet()->setCellValue('Q'.$a, "Rs. ".number_format($result->net_amount,2));
            
             $a++;  
			$sale_price += $result->rate_per_item;
             $qty += $result->quantity; 
             $total_sum += $result->total;   
             $net_amount += $result->net_amount; 
			$dis1 += ($result->discount_1 * $result->quantity);
			$dis2 += ($result->discount_2 * $result->quantity);
			$dis3 += ($result->discount_3 * $result->quantity);
			$dis_amt += $result->discount_amount;
			$tax += $result->taxable;
			$cgst += $result->cgst_amount;
			$sgst += $result->sgst_amount;
            $igst += $result->igst_amount;
			
        }
		$this->excel->getActiveSheet()->setCellValue('F'.$a,"Rs. ".number_format($sale_price,2)); 
		$this->excel->getActiveSheet()->setCellValue('G'.$a, $qty);                  
        $this->excel->getActiveSheet()->setCellValue('H'.$a, "Rs. ".number_format($total_sum,2));  
		$this->excel->getActiveSheet()->setCellValue('I'.$a, "Rs. ".number_format($dis1,2));  
		$this->excel->getActiveSheet()->setCellValue('J'.$a, "Rs. ".number_format($dis2,2));  
		$this->excel->getActiveSheet()->setCellValue('K'.$a, "Rs. ".number_format($dis3,2));   
		$this->excel->getActiveSheet()->setCellValue('L'.$a, "Rs. ".number_format($dis_amt,2));  
		$this->excel->getActiveSheet()->setCellValue('M'.$a, "Rs. ".number_format($tax,2)); 
		$this->excel->getActiveSheet()->setCellValue('N'.$a, "Rs. ".number_format($cgst,2));  
		$this->excel->getActiveSheet()->setCellValue('O'.$a, "Rs. ".number_format($sgst,2));
        $this->excel->getActiveSheet()->setCellValue('P'.$a, "Rs. ".number_format($igst,2));
        $this->excel->getActiveSheet()->setCellValue('Q'.$a, "Rs. ".number_format($net_amount,2));
        
        $this->excel->getActiveSheet()->getStyle('F'.$a)->getFont()->setBold(true); 
		$this->excel->getActiveSheet()->getStyle('G'.$a)->getFont()->setBold(true);                
               
        $this->excel->getActiveSheet()->getStyle('H'.$a)->getFont()->setBold(true); 
		$this->excel->getActiveSheet()->getStyle('I'.$a)->getFont()->setBold(true); 
		$this->excel->getActiveSheet()->getStyle('J'.$a)->getFont()->setBold(true);  
		$this->excel->getActiveSheet()->getStyle('K'.$a)->getFont()->setBold(true); 
		$this->excel->getActiveSheet()->getStyle('L'.$a)->getFont()->setBold(true); 
		$this->excel->getActiveSheet()->getStyle('M'.$a)->getFont()->setBold(true); 
		$this->excel->getActiveSheet()->getStyle('N'.$a)->getFont()->setBold(true);  
		$this->excel->getActiveSheet()->getStyle('O'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('P'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('Q'.$a)->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);              
        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('I5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('M5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('N5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('O5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('P5')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('Q5')->getFont()->setBold(true);
        
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
