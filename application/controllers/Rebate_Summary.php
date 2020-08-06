<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/mpdf/vendor/autoload.php');
class Rebate_Summary extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Rebate_Summary_model');
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
	  $strUrl = site_url('Rebate_Summary/ajax_manage_page/' . $date . '/'. $type . '/'. $type2 . '/'. $type3);
      $this->common_view($strUrl,$date,$type,$type2,$type3); 
	  
    }
    else
    {
       return redirect('Rebate_Summary');
    }
       
  }
  public function index()
  {   
    
    $this->common_view(site_url('Rebate_Summary/ajax_manage_page'));
  }
  
  public function common_view($action,$date=0,$type=0,$type2=0,$type3=0)
  {   
    $userdata = $this->commonData($date, $type, $type2, $type3);
    $import = '';
    $add = '';
  	if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
	    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
	    { 
	      foreach($row as $menu)
	      { 
	          if($menu->value=='Rebate_Summary')
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
	                <li class='active'>Rebate Summary</li>
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
      $data = array('types' => $types, 'product_types' => $product_types,'selected_date' => $date,'selected_type' => $type, 'selected_type2' => $type2, 'selected_type3' => $type3,'salesTypes' => $salesTypes,
      'gstarray'=>$userdata['gstArray'],'dateinfo'=>$date,'breadcrumbs' => $breadcrumbs ,'actioncolumn' => '16' ,'ajax_manage_page' => $action, 'heading' => 'Rebate Summary', 'addPermission'=>$add, 'importaction' => $importaction, 'download' => $download, 'import' => $import,'export' =>$export, 'exportPermission'=>$exportbutton,'view'=>$userdata['rebateData']);

	    $this->load->view('rebate_summary/list',$data);
  	}
  	else
  	{
  		redirect('Dashboard');
  	}
  }

  public function commonData($date=0, $type = 0, $type2 = 0, $type3 = 0){
    $con="i.id<>''";
    if($type != 0)
      $con .= "and a.asset_type_id ='". $type . "'";
    if($type2 != 0)
      $con .= "and a.product_type_id ='". $type2 . "'";
    if($type3 != 0)
      $con .= "and i.invoice_sales_type ='". $type3 . "'";
    $Data = $this->Rebate_Summary_model->get_datatables($con,$date);
    //dd($Data);
    $data = array();       
    $no=0; 
    $arrId = array();
    $gstArray = array();
    $rebateData = array();
    foreach($Data as $row) 
    {  
      if(in_array($row->gst_rate, $gstArray))
      {
      }
      else
      {
          if($row->gst_rate){
            $gstArray[] = $row->gst_rate;
          }
      }
      if(in_array($row->invoice_id, $arrId))
      {
          $invoice_no = '';
          $sr_no = '';
          $total_amount = '';
      }
      else
      {
          $no++;
          $arrId[] = $row->invoice_id;
          $invoice_no = $row->invoice_no;
          $sr_no = $no;
          $this->db->where('invoice_id',$row->invoice_id);
          $query = $this->db->get('invoice_details');
          $result = $query->result();
          $net_amount = 0;
          foreach($result as $r){
            $net_amount += $r->net_amount;
          }
          $total_amount = $net_amount;
      }
    
        $rebateData[] = array(
          'no' => $sr_no,
          'invoice_no' => $invoice_no,
          'date_of_invoice' => date('d-m-Y', strtotime($row->date_of_invoice)),
          'asset_name' => $row->asset_name,
          'product_type' => $row->productType,
          'asset_type_id' => $row->assetType,
          'hsn' => $row->hsn,
          'invoice_quantity' => $row->invoice_quantity,
          'rate_per_item' => $row->rate_per_item,
          'total' => $row->total,
          'discount_1' => $row->discount_1,
          'discount_2' => $row->discount_2,
          'discount_3' => $row->discount_3,
          'taxable' => $row->taxable,
          'gst_rate' => $row->gst_rate,
          'gst_amount' => $row->gst_amount,
          'cgst_rate' => $row->cgst_rate,
          'cgst_amount' => $row->cgst_amount,
          'sgst_rate' => $row->sgst_rate,
          'sgst_amount' => $row->sgst_amount,
          'igst_rate' => $row->igst_rate,
          'igst_amount' => $row->igst_amount,
          'adjustment_plus' => $row->adjustment_plus,
          'adjustment_minus' => $row->adjustment_minus,
          'net_amount' => $row->net_amount,
          'shipping_charges' => $row->shipping_charges,
          'total_amount' => $total_amount,
        );
    }
    sort($gstArray);
    return array('rebateData'=>$rebateData,'gstArray'=>$gstArray);
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
    $Data = $this->Rebate_Summary_model->get_datatables($con,$date);
	  //echo"<pre>";
    //print_r($Data);exit;
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
          if($menu->value=='Rebate_Summary')
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
		if(in_array($row->invoice_id, $arrId))
        {
            //$sumAmount = '';
			$invoice_no = '';
			$sr_no = '';
			$total_amount = '';
        }
        else
        {
			$no++;
            $arrId[] = $row->invoice_id;
            //$sumAmount = "Rs ".number_format($row->sumAmount,2);
			$invoice_no = $row->invoice_no;
			$sr_no = $no;
			$this->db->where('invoice_id',$row->invoice_id);
			$query = $this->db->get('invoice_details');
			$result = $query->result();
			$net_amount = 0;
			foreach($result as $r){
				$net_amount += $r->net_amount;
			}
			$total_amount = "Rs. ".number_format($net_amount,2);
        }
        $no++;
        $nestedData = array();
        $nestedData[] = $sr_no;                       
        $nestedData[] = $invoice_no;                       
        $nestedData[] = date('d-m-Y', strtotime($row->date_of_invoice));                       
        $nestedData[] = $row->asset_name;                              
        $nestedData[] = $row->productType;                              
        $nestedData[] = $row->assetType;                              
        $nestedData[] = $row->hsn;                       
        $nestedData[] = $row->invoice_quantity;                       
        $nestedData[] = "Rs. ".number_format($row->rate_per_item,2);                       
        $nestedData[] = "Rs. ".number_format($row->total,2);                       
        $nestedData[] = "Rs. ".number_format(($row->rate_per_item * $row->discount_1)/100);                       
        $nestedData[] = "Rs. ".number_format(($row->rate_per_item * $row->discount_2)/100);                       
        $nestedData[] = "Rs. ".number_format(($row->rate_per_item * $row->discount_3)/100);                       
        $nestedData[] = "Rs. ".number_format($row->taxable,2);

        $nestedData[] = "Rs. ".number_format($row->cgst_amount,2);
        $nestedData[] = "Rs. ".number_format($row->sgst_amount,2);
        $nestedData[] = "Rs. ".number_format($row->cgst_amount+$row->sgst_amount,2);                       
        $nestedData[] = "Rs. ".number_format($row->igst_amount,2);


        $nestedData[] = $row->adjustment_plus;                       
        $nestedData[] = $row->adjustment_minus;                       
        $nestedData[] = "Rs. ".number_format($row->net_amount,2);                       
        $nestedData[] = $row->shipping_charges;                       
        $nestedData[] = $total_amount;                       
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
               // "draw" => $_POST['draw'],
                "recordsTotal" => $this->Rebate_Summary_model->count_all($con),
                "recordsFiltered" => $this->Rebate_Summary_model->count_filtered($con),
                "data" => $data,
            );
   
    echo json_encode($output);
  }
  
  //===================================summery pdf=========================

  public function listpdf($date=0, $type = 0, $type2 = 0, $type3 = 0)
  {
      $userdata = $this->commonData($date, $type, $type2, $type3);   
      $data['view'] = $userdata['rebateData'];
      $data['gstarray'] = $userdata['gstArray'];
      $html = $this->load->view('rebate_summary/pdf_rebate_list',$data,true);
      $mpdf = new \Mpdf\Mpdf();
      $mpdf->WriteHTML($html);
      $mpdf->Output('Rebate_Summary', 'I');
  }

  //===================================/summery pdf========================


  /* ----- Export functionality start ----- */
    public function export_rebate_summary($date=0, $type = 0, $type2 = 0, $type3 = 0)
    {
      $con="i.id<>''";    
      if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
      if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
      if($type3 != 0)
        $con .= "and i.invoice_sales_type ='". $type3 . "'";
      $results = $this->Rebate_Summary_model->get_datatables($con,$date);
      //$results = $query->result();
        
        //$results = $this->Rebate_Summary_model->ExportCSV($con);
        $FileTitle='Rebate Summary Report';
                
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Report Sheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Gujarat State Handloom & Handicraft Development Corp. Ltd.');
        $this->excel->getActiveSheet()->setCellValue('A2', $_SESSION[SESSION_NAME]['address']);
        $this->excel->getActiveSheet()->setCellValue('A3', $_SESSION[SESSION_NAME]['gst_number']);
        $this->excel->getActiveSheet()->setCellValue('A4', 'Rebate Summary Details');

        $this->excel->getActiveSheet()->setCellValue('A5', 'Sr. No');
        $this->excel->getActiveSheet()->setCellValue('B5', 'Invoice No.');
        $this->excel->getActiveSheet()->setCellValue('C5', 'Date');
        $this->excel->getActiveSheet()->setCellValue('D5', 'Product Name');
        $this->excel->getActiveSheet()->setCellValue('E5', 'Product Type1');
        $this->excel->getActiveSheet()->setCellValue('F5', 'Product Type2');
        $this->excel->getActiveSheet()->setCellValue('G5', 'HSN');
        $this->excel->getActiveSheet()->setCellValue('H5', 'Quantity');
        $this->excel->getActiveSheet()->setCellValue('I5', 'Rate');
        $this->excel->getActiveSheet()->setCellValue('J5', 'Total Value');
        $this->excel->getActiveSheet()->setCellValue('K5', 'Rebate 5%');
        $this->excel->getActiveSheet()->setCellValue('L5', 'Discount');
        $this->excel->getActiveSheet()->setCellValue('M5', 'Other Discount');
        $this->excel->getActiveSheet()->setCellValue('N5', 'Total Taxable Amount');

        $this->excel->getActiveSheet()->setCellValue('O5', 'CGST');
        $this->excel->getActiveSheet()->setCellValue('P5', 'SGST');
        $this->excel->getActiveSheet()->setCellValue('Q5', 'C+S GST');
        $this->excel->getActiveSheet()->setCellValue('R5', 'IGST');

        $this->excel->getActiveSheet()->setCellValue('S5', 'Adj +');
        $this->excel->getActiveSheet()->setCellValue('T5', 'Adj -');
        $this->excel->getActiveSheet()->setCellValue('U5', 'Amount after GST');
        $this->excel->getActiveSheet()->setCellValue('V5', 'Shipping Charge');
        $this->excel->getActiveSheet()->setCellValue('W5', 'Net Anount');
            
        $a='6'; $no = 1;    
        $total_sum = 0;
        $total_qty = 0;
        $total_total_amount = 0;
        $total_net_amount = 0;
        //print_r($results);exit;
        $arrId = array();
        foreach ($results as $result) 
        {    
            if(in_array($result->invoice_id, $arrId))
            {
              $sumAmount = '';
              $invoice_no = '';
              $sr_no = '';
              $total_amount = '';
            }
            else
            {
              $no++;
              $arrId[] = $result->invoice_id;
              //$sumAmount = "Rs ".number_format($row->sumAmount,2);
              $invoice_no = $result->invoice_no;
              $sr_no = $no;
              $this->db->where('invoice_id',$result->invoice_id);
              $query = $this->db->get('invoice_details');
              $result2 = $query->result();
              $net_amount = 0;
              foreach($result2 as $r){
                $net_amount += $r->net_amount;
              }
              $total_amount = "Rs. ".number_format($net_amount,2);
            }   
            
            /*$total = $result->sum + $result->transport ;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;*/
            
            $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr_no);
            $this->excel->getActiveSheet()->setCellValue('B'.$a, $invoice_no);
            $this->excel->getActiveSheet()->setCellValue('C'.$a, date('d-m-Y', strtotime($result->date_of_invoice)));
            $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->asset_name);
            $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->productType);
            $this->excel->getActiveSheet()->setCellValue('F'.$a, $result->assetType);
            $this->excel->getActiveSheet()->setCellValue('G'.$a, $result->hsn);
            $this->excel->getActiveSheet()->setCellValue('H'.$a, $result->invoice_quantity);                
            $this->excel->getActiveSheet()->setCellValue('I'.$a, "Rs. ".number_format($result->rate_per_item,2));                
            $this->excel->getActiveSheet()->setCellValue('J'.$a, "Rs. ".number_format($result->total,2));                
            $this->excel->getActiveSheet()->setCellValue('K'.$a, "Rs. ".number_format(($result->rate_per_item * $result->invoice_quantity * $result->discount_1)/100));             
            $this->excel->getActiveSheet()->setCellValue('L'.$a, "Rs. ".number_format(($result->rate_per_item * $result->invoice_quantity * $result->discount_2)/100));                
            $this->excel->getActiveSheet()->setCellValue('M'.$a, "Rs. ".number_format(($result->rate_per_item * $result->invoice_quantity * $result->discount_3)/100));
            $this->excel->getActiveSheet()->setCellValue('N'.$a, "Rs. ".number_format($result->taxable,2));

           // $this->excel->getActiveSheet()->setCellValue('O'.$a, "Rs. ".$result->gst_rate);
          //  $this->excel->getActiveSheet()->setCellValue('P'.$a, "Rs. ".number_format($result->sgst_amount,2));

            $this->excel->getActiveSheet()->setCellValue('O'.$a, $result->cgst_amount,2);
            $this->excel->getActiveSheet()->setCellValue('P'.$a, "Rs. ".number_format($result->sgst_amount,2));
            $this->excel->getActiveSheet()->setCellValue('Q'.$a, "Rs. ".number_format($result->cgst_amount + $result->sgst_amount,2));
            $this->excel->getActiveSheet()->setCellValue('R'.$a, "Rs. ".number_format($result->igst_amount,2));
            $this->excel->getActiveSheet()->setCellValue('S'.$a, $result->adjustment_plus);
            $this->excel->getActiveSheet()->setCellValue('T'.$a, $result->adjustment_minus);
            $this->excel->getActiveSheet()->setCellValue('U'.$a, "Rs. ".number_format($result->net_amount,2));
            $this->excel->getActiveSheet()->setCellValue('V'.$a, $result->shipping_charges);
            $this->excel->getActiveSheet()->setCellValue('W'.$a, $total_amount);
            //$this->excel->getActiveSheet()->setCellValue('R'.$a, $row->shipping_charges);
            //$this->excel->getActiveSheet()->setCellValue('G'.$a, $result->status);
            //$this->excel->getActiveSheet()->setCellValue('H'.$a, $total);
            //$sr++;
            $a++;
            $total_sum += $result->total; 
			$total_rate+= $result->rate_per_item;
            $total_qty += $result->invoice_quantity; 
            $total_total_amount += $total_amount;   
            $total_net_amount += $result->net_amount;
			$dis1+= ((($result->rate_per_item * $result->invoice_quantity * $result->discount_1)/100)); 
			$dis2+= ((($result->rate_per_item * $result->invoice_quantity * $result->discount_2)/100)); 
			$dis3+= ((($result->rate_per_item * $result->invoice_quantity * $result->discount_3)/100));
			$adj1+= $result->adjustment_plus;
			$adj2+= $result->adjustment_minus;
			$tax+= $result->taxable;
			$cgst+= $result->cgst_amount;
			$sgst+= $result->sgst_amount;
			$igst+= ($result->cgst_amount + $result->sgst_amount);
			$igst2+= $result->igst_amount;
			$ship+= $result->shipping_charges;


        }

    $this->excel->getActiveSheet()->setCellValue('H'.$a, $total_qty);   
		$this->excel->getActiveSheet()->setCellValue('I'.$a, $total_rate);

														 
    $this->excel->getActiveSheet()->setCellValue('J'.$a, "Rs. ".number_format($total_sum,2)); 
		$this->excel->getActiveSheet()->setCellValue('K'.$a, "Rs. ".number_format($dis1));	
		$this->excel->getActiveSheet()->setCellValue('L'.$a, "Rs. ".number_format($dis2)); 	
		$this->excel->getActiveSheet()->setCellValue('M'.$a, "Rs. ".number_format($dis3));
		$this->excel->getActiveSheet()->setCellValue('N'.$a, "Rs. ".number_format($tax));
		$this->excel->getActiveSheet()->setCellValue('O'.$a, "Rs. ".number_format($cgst));	
		$this->excel->getActiveSheet()->setCellValue('P'.$a, "Rs. ".number_format($sgst));	
		$this->excel->getActiveSheet()->setCellValue('Q'.$a, "Rs. ".number_format($igst));	
		$this->excel->getActiveSheet()->setCellValue('R'.$a, "Rs. ".number_format($igst2));	
		$this->excel->getActiveSheet()->setCellValue('S'.$a, "Rs. ".number_format($adj1));	
		$this->excel->getActiveSheet()->setCellValue('T'.$a, "Rs. ".number_format($adj2));
		$this->excel->getActiveSheet()->setCellValue('U'.$a, "Rs. ".number_format($total_total_amount,2));
    $this->excel->getActiveSheet()->setCellValue('V'.$a, "Rs. ".number_format($ship));
    $this->excel->getActiveSheet()->setCellValue('W'.$a, "Rs. ".number_format($total_net_amount,2));

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
		$this->excel->getActiveSheet()->getStyle('R'.$a)->getFont()->setBold(true);	
		$this->excel->getActiveSheet()->getStyle('S'.$a)->getFont()->setBold(true);	
		$this->excel->getActiveSheet()->getStyle('T'.$a)->getFont()->setBold(true);	
		$this->excel->getActiveSheet()->getStyle('U'.$a)->getFont()->setBold(true);	
		$this->excel->getActiveSheet()->getStyle('V'.$a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Y'.$a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('W'.$a)->getFont()->setBold(true);

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
    $this->excel->getActiveSheet()->getStyle('R5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('S5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('T5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('U5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('V5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('X5')->getFont()->setBold(true);
        //$this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
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