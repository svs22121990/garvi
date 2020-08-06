<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/mpdf/vendor/autoload.php');
class Daily_Sales_Summary extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Daily_Sales_Summary_model');
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
	  $strUrl = site_url('Daily_Sales_Summary/ajax_manage_page/' . $date . '/'. $type . '/'. $type2 . '/'. $type3);
      $this->common_view($strUrl,$date,$type,$type2,$type3);
	  
    }
    else
    {
       return redirect('Daily_Sales_Summary');
    }
       
  }
  public function index()
  {   
    
    $this->common_view(site_url('Daily_Sales_Summary/ajax_manage_page'));
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
	          if($menu->value=='Daily_Sales_Summary')
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
	                <li class='active'>Daily Sales Summary Report</li>
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
        'heading' => 'Daily Sales Summary Report',
        'addPermission'=>$add,
        'importaction' => $importaction,
        'download' => $download,
        'import' => $import,
        'export' =>$export,
        'exportPermission'=>$exportbutton,
        'salesTypes' => $salesTypes,
      );

	    $this->load->view('daily_sales_summary/list',$data);
  	}
  	else
  	{
  		redirect('Dashboard');
  	}
  }

  public function ajax_manage_page($date=0, $type = 0, $type2 = 0, $type3 = 0)
  {
    $con="ide.invoice_id<>''";
    if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
    if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
    if($type3 != 0)
        $con .= "and i.invoice_sales_type ='". $type3 . "'";
    $Data = $this->Daily_Sales_Summary_model->get_datatables($con,$date);
    
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
          if($menu->value=='Daily_Sales_Summary')
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
        if(array_search(date('d-m-Y', strtotime($row->date_of_invoice)), array_column($data, 'date_of_invoice')) !== False)
        {
          foreach($data as $key => $d)
          {
            if ( $d['date_of_invoice'] === date('d-m-Y', strtotime($row->date_of_invoice)) ) {
              $data[$key]['total'] += $row->total;
              $data[$key]['discount_1'] += $row->discount_1;
              $data[$key]['discount_2'] += $row->discount_2;
              $data[$key]['discount_3'] += $row->discount_3;
              $data[$key]['discount_amount'] += $row->discount_amount;
              $data[$key]['taxable'] += $row->taxable;
              $data[$key]['net_amount'] += $row->net_amount;
              $data[$key]['cgst_amount'] += $row->cgst_amount;
              $data[$key]['sgst_amount'] += $row->sgst_amount;
              $data[$key]['igst_amount'] += $row->igst_amount;
              $data[$key]['gst_amount'] += $row->cgst_amount + $row->sgst_amount + $row->igst_amount;
              break;
            }
          }
        } else {
          $data[] = array(
            'no' => $sr_no,
            'date_of_invoice' => date('d-m-Y', strtotime($row->date_of_invoice)),
            'invoice_sales_type' => $row->salesType,
            'paymentMode' => $row->paymentMode,
            'quantity' => $row->quantity,
            'rate_per_item' => $row->rate_per_item,
            'total' => $row->total,
            'discount_1' => ($row->discount_1),
            'discount_2' => ($row->discount_2),
            'discount_3' => ($row->discount_3),
            'discount_amount' => $row->discount_amount,
            'taxable' => $row->taxable,
            'net_amount' => $row->net_amount,
            'cgst_amount' => $row->cgst_amount,
            'sgst_amount' => $row->sgst_amount,
            'igst_amount' => $row->igst_amount,
            'gst_amount' => $row->cgst_amount + $row->sgst_amount + $row->igst_amount,
          );
        }
    }

    $output = array(
                "data" => $data,
            );
   
    echo json_encode($output);
  }
  
  //============================export pdf ============================
  public function listpdf($date=0, $type = 0, $type2 = 0, $type3 = 0)
  {
    $con="ide.invoice_id<>''";
    if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
    if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
    if($type3 != 0)
        $con .= "and i.invoice_sales_type ='". $type3 . "'";
    $data['results'] = $this->Daily_Sales_Summary_model->get_datatables($con,$date);

    $html = $this->load->view('daily_sales_summary/pdf_summary',$data,true);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Sales_Summary_Report','I');
  }
  //============================/export pdf ============================


  /* ----- Export functionality start ----- */
    public function export_sales_summary($date=0, $type = 0, $type2 = 0, $type3 = 0)
    {
      $con="ide.invoice_id<>''";
      if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
      if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
      if($type3 != 0)
        $con .= "and i.invoice_sales_type ='". $type3 . "'";
      $results = $this->Daily_Sales_Summary_model->get_datatables($con,$date);
      //$results = $this->Daily_Sales_Summary_model->ExportCSV($con);
      $FileTitle='Daily Sales Summary Report';
                
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Report Sheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Gujarat State Handloom & Handicraft Development Corp. Ltd.');
        $this->excel->getActiveSheet()->setCellValue('A2', $_SESSION[SESSION_NAME]['address']);
        $this->excel->getActiveSheet()->setCellValue('A3', $_SESSION[SESSION_NAME]['gst_number']);
        $this->excel->getActiveSheet()->setCellValue('A4', 'Daily Sales Summary Details');

        $this->excel->getActiveSheet()->setCellValue('A5', 'Sr. No');
        $this->excel->getActiveSheet()->setCellValue('B5', 'Date');
        $this->excel->getActiveSheet()->setCellValue('C5', 'Total Amount');
        $this->excel->getActiveSheet()->setCellValue('D5', 'Discount 1');
        $this->excel->getActiveSheet()->setCellValue('E5', 'Discount 2');
        $this->excel->getActiveSheet()->setCellValue('F5', 'Discount 3');
        $this->excel->getActiveSheet()->setCellValue('G5', 'Total Discount');
        $this->excel->getActiveSheet()->setCellValue('H5', 'Amt After Dic.');
        $this->excel->getActiveSheet()->setCellValue('I5', 'CGST');
        $this->excel->getActiveSheet()->setCellValue('J5', 'SGST');
        $this->excel->getActiveSheet()->setCellValue('K5', 'IGST');
        $this->excel->getActiveSheet()->setCellValue('L5', 'Total GST');
        $this->excel->getActiveSheet()->setCellValue('M5', 'Total Amount');
            
        $a='6'; $sr = 1;    
        //print_r($results);exit;
        $total_sum = 0;
        $disc1_sum = 0;
        $disc2_sum = 0;
        $disc3_sum = 0;
        $discount_sum = 0;
        $taxable_sum = 0;
        $net_amount = 0;
        $tgst=0;
        $igst=0;
        $sgst=0;
        $cgst=0;
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
              $no = $sr++;
          }   
            
          $this->excel->getActiveSheet()->setCellValue('A'.$a, $no);
          $this->excel->getActiveSheet()->setCellValue('B'.$a, date('d-m-Y', strtotime($result->date_of_invoice)));            
          $this->excel->getActiveSheet()->setCellValue('C'.$a, "Rs. ".number_format($result->total,2));                
          $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->discount_1);
          $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->discount_2);
          $this->excel->getActiveSheet()->setCellValue('F'.$a, $result->discount_3);   
          $this->excel->getActiveSheet()->setCellValue('G'.$a, number_format($result->discount_amount,2));
          $this->excel->getActiveSheet()->setCellValue('H'.$a, number_format($result->taxable,2));
          $this->excel->getActiveSheet()->setCellValue('I'.$a, number_format($result->cgst_amount,2));
          $this->excel->getActiveSheet()->setCellValue('J'.$a, number_format($result->sgst_amount,2));
            $this->excel->getActiveSheet()->setCellValue('K'.$a, number_format($result->igst_amount,2));
            $this->excel->getActiveSheet()->setCellValue('L'.$a, number_format($result->cgst_amount + $result->sgst_amount + $result->igst_amount,2));
            $this->excel->getActiveSheet()->setCellValue('M'.$a, "Rs. ".number_format($result->net_amount,2));
             $a++;
             $total_sum += $result->total;
             $disc1_sum += $result->discount_1;
             $disc2_sum += $result->discount_2;
             $disc3_sum += $result->discount_3;
             $discount_sum += $result->discount_amount;
             $taxable_sum += $result->taxable;
			$cgst += $result->cgst_amount;
			$sgst += $result->sgst_amount;
            $igst += $result->igst_amount;
            $tgst += $result->cgst_amount + $result->sgst_amount + $result->igst_amount;
             $net_amount += $result->net_amount;         
        }
        $this->excel->getActiveSheet()->setCellValue('C'.$a, "Rs. ".number_format($total_sum,2));
        $this->excel->getActiveSheet()->setCellValue('D'.$a, "Rs. ".number_format($disc1_sum,2));
        $this->excel->getActiveSheet()->setCellValue('E'.$a, "Rs. ".number_format($disc2_sum,2));
        $this->excel->getActiveSheet()->setCellValue('F'.$a, "Rs. ".number_format($disc3_sum,2));
        $this->excel->getActiveSheet()->setCellValue('G'.$a, "Rs. ".number_format($discount_sum,2));
        $this->excel->getActiveSheet()->setCellValue('H'.$a, "Rs. ".number_format($taxable_sum,2)); 
		$this->excel->getActiveSheet()->setCellValue('I'.$a, "Rs. ".number_format($cgst,2));  
		$this->excel->getActiveSheet()->setCellValue('J'.$a, "Rs. ".number_format($sgst,2));
        $this->excel->getActiveSheet()->setCellValue('K'.$a, "Rs. ".number_format($igst,2));
        $this->excel->getActiveSheet()->setCellValue('L'.$a, "Rs. ".number_format($tgst,2));

		
        $this->excel->getActiveSheet()->setCellValue('M'.$a, "Rs. ".number_format($net_amount,2));
        
        $this->excel->getActiveSheet()->getStyle('C'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('G'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H'.$a)->getFont()->setBold(true); 
		$this->excel->getActiveSheet()->getStyle('I'.$a)->getFont()->setBold(true);   
		$this->excel->getActiveSheet()->getStyle('J'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('K'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('L'.$a)->getFont()->setBold(true);

        $this->excel->getActiveSheet()->getStyle('M'.$a)->getFont()->setBold(true);
        
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
