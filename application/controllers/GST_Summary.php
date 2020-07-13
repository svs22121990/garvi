<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/mpdf/vendor/autoload.php');
class GST_Summary extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('GST_Summary_model');
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
	    $strUrl = site_url('GST_Summary/ajax_manage_page/' . $date . '/'. $type . '/'. $type2 . '/'. $type3);
      $this->common_view($strUrl,$date,$type,$type2,$type3); 
	  
    }
    else
    {
       return redirect('GST_Summary');
    }
       
  }
  public function index()
  {   
    $this->common_view(site_url('GST_Summary/ajax_manage_page'));
  }
  
  public function common_view($action,$date=0,$type=0,$type2=0,$type3=0)
  { 
    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $edit = '';
    $view = '';


    $categories = $this->Crud_model->GetData("categories","","status='Active'","","","","");
    //print_r($categories);exit;
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='GST_Summary')
          { 
            
            if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
            if(!empty($menu->act_export)){ $exportbutton='1'; }else{ $exportbutton='0'; }
          }
      }
    }
        $breadcrumbs = "<ul class='breadcrumb'>
                <li>
                    <i class='ace-icon fa fa-home home-icon'></i>
                    <a href='".site_url('Dashboard')."'>Dashboard</a>
                </li>
                <li class='active'>GST Summary</li>
            </ul>";

        $export ='<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>';
        /*$condUser ="status='Active'";
        $countries =  $this->Crud_model->GetData('mst_countries','',$condUser);
        $states =  $this->Crud_model->GetData('mst_states','',$condUser);*/

        $salesTypes = $this->Crud_model->GetData("sales_type", "", "status='Active'");

        $types = $this->Crud_model->GetData('mst_asset_types', "", "status='Active' and is_delete='No'", 'type');
        $product_types =  $this->Crud_model->GetData('product_type', "", "status='Active'");

      $data = array(
          'types' => $types, 'product_types' => $product_types,'selected_date' => $date,'selected_type' => $type, 'selected_type2' => $type2, 'selected_type3' => $type3,
		      'dateinfo' => $date,
          'breadcrumbs' => $breadcrumbs ,
          'actioncolumn' => '9' ,
          'ajax_manage_page' => $action ,
          'heading' => 'GST Summary',
          'addPermission'=>$add,'export' =>$export, 'exportPermission'=>$exportbutton, 'salesTypes' => $salesTypes
          );
    $this->load->view('gst_summary/list',$data);
  }
  else
    {
      redirect('Dashboard');
    }        
  }


    public function ajax_manage_page($date=0, $type = 0, $type2 = 0, $type3 = 0)
    {
        $delete= '';
        $actstatus= '';
        $add = '';
        $act_add_existing_stock = '';
        $act_log_details = '';
        $act_transfer = '';
        $edit = '';
        $view = '';
        $results = $this->same_query($date, $type, $type2, $type3);
        $no = 0;  
        $data = array(); 
        foreach($results as $row) 
        {
            $no++;  
            $data[] = array(
              'no' => $no,
              'gst_rate' => $row['gst_rate']."%",
              'gst_amount' => number_format($row['gst_amount'],2),
              'taxable' => number_format($row['taxable'],2),
              'cgst_rate' => $row['cgst_rate']."%",
              'cgst_amount' => number_format($row['cgst_amount'],2),
              'sgst_rate' => $row['sgst_rate']."%", 
              'sgst_amount' => number_format($row['sgst_amount'],2),
              'igst_rate' => $row['igst_rate']."%",
              'igst_amount' => number_format($row['igst_amount'],2),
            );
        }
        $output = array( "data" => $data );
        echo json_encode($output);
    }
	
	 //===================================summery pdf=========================

  public function listpdf($date=0, $type = 0, $type2 = 0, $type3 = 0)
  {
      $data['results'] = $this->same_query($date, $type, $type2, $type3);
       //dd($results);
      //$data['results'] = $this->GST_Summary_model->get_datatables(); 
      $html = $this->load->view('gst_summary/pdf_gst_list',$data,true);
      $mpdf = new \Mpdf\Mpdf();
      $mpdf->WriteHTML($html);
      $mpdf->Output('GST_Summary','I');
  }
  public function same_query($date, $type, $type2, $type3){
      $Data =  $this->GST_Summary_model->get_datatables($date);
      $data = array();  
            $no =0;
            $results = array();
            foreach($Data as $r){
                $gst_rate = $r->gst_rate;
                $this->db->select('ide.gst_rate, SUM(gst_amount) as sumgstam, SUM(cgst_amount) as sum_cgst_amount,SUM(sgst_amount) as sum_sgst_amount  ,SUM(igst_amount) as sum_igst_amount,SUM(taxable) as sumtaxable');
                $this->db->from('invoice i');
                $this->db->join('invoice_details ide', 'i.id=ide.invoice_id', 'inner');
                $this->db->join("assets a","a.id = ide.product_id","left");
                //$this->db->distinct();
                //$this->db->order_by("i.id desc");
                $this->db->where('i.created_by',$_SESSION[SESSION_NAME]['id']);
                $this->db->where('ide.gst_rate',$gst_rate);
                if($date!=0){
                  $dates = explode("_",$date);
                  $date1 = date("Y-m-d", strtotime($dates[0]));
                  $date2 = date("Y-m-d", strtotime($dates[1]));
                  $this->db->where('i.date_of_invoice >=', $date1);
                  $this->db->where('i.date_of_invoice <=', $date2);
                }
                if($type!=0){
                  $this->db->where('a.asset_type_id =', $type);
                }
                if($type2!=0){
                  $this->db->where('a.product_type_id =', $type2);
                }
                if($type3!=0){
                  $this->db->where('i.invoice_sales_type =', $type3);
                }
                $query = $this->db->get();
                $results2 = $query->row();
                $gst_amount = 0;
                $taxable = 0;
                $cgst_amount = 0;
                $sgst_amount = 0;
                $igst_amount = 0;
                 $results[] = array(
                  'gst_rate' => $r->gst_rate,
                  'gst_amount' => $results2->sumgstam,
                  'taxable'=> $results2->sumtaxable,
                  'cgst_rate' => $gst_rate/2,
                  'cgst_amount' => $results2->sum_cgst_amount,
                  'sgst_rate' => $gst_rate/2,
                  'sgst_amount' => $results2->sum_sgst_amount,
                  'igst_rate' => $gst_rate,
                  'igst_amount' => $results2->sum_igst_amount,
                );
               
            }
       return $results;
  }

  //===================================/summery pdf========================
   
   
    public function export_gst_summary($date=0, $type = 0, $type2 = 0, $type3 = 0)
    {
        $results = $this->same_query($date, $type, $type2, $type3);
        //dd($results);
        //$results = $query->result();

        //$results = $this->GST_Summary_model->ExportCSV($con);
        $FileTitle='GST Summary Report';
                
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Report Sheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'GST Summary Details ');

        $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
        $this->excel->getActiveSheet()->setCellValue('B3', 'GST Rate');
        $this->excel->getActiveSheet()->setCellValue('C3', 'GST Amount');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Taxable Amount');
        $this->excel->getActiveSheet()->setCellValue('E3', 'CGST Rate');
        $this->excel->getActiveSheet()->setCellValue('F3', 'CGST Amount');
        $this->excel->getActiveSheet()->setCellValue('G3', 'SGST Rate');
        $this->excel->getActiveSheet()->setCellValue('H3', 'SGST Amount');
        $this->excel->getActiveSheet()->setCellValue('I3', 'IGST Rate');
        $this->excel->getActiveSheet()->setCellValue('J3', 'IGST Amount');
            
        $a='4'; $sr = 1; 
        $gst_amount = 0;
        $taxable = 0;
        $cgst_amount = 0;
        $sgst_amount = 0;
        $igst_amount = 0;   
        //print_r($results);exit;
        foreach ($results as $result) 
        {       
            
            /*$total = $result->sum + $result->transport ;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;*/
            
            $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
            $this->excel->getActiveSheet()->setCellValue('B'.$a, $result['gst_rate']);
            $this->excel->getActiveSheet()->setCellValue('C'.$a, "Rs. ".number_format($result['gst_amount'],2));
            $this->excel->getActiveSheet()->setCellValue('D'.$a, "Rs. ".number_format($result['taxable'],2));
            $this->excel->getActiveSheet()->setCellValue('E'.$a, $result['cgst_rate']);
            $this->excel->getActiveSheet()->setCellValue('F'.$a, "Rs. ".number_format($result['cgst_amount'],2));                
            $this->excel->getActiveSheet()->setCellValue('G'.$a, $result['sgst_rate']);                
            $this->excel->getActiveSheet()->setCellValue('H'.$a, "Rs. ".number_format($result['sgst_amount'],2));                
            $this->excel->getActiveSheet()->setCellValue('I'.$a, $result['igst_rate']);                
            $this->excel->getActiveSheet()->setCellValue('J'.$a, "Rs. ".number_format($result['igst_amount'],2));
            $sr++; $a++;     
            $gst_amount += $result['gst_amount'];
            $taxable += $result['taxable'];
            $cgst_amount += $result['cgst_amount'];
            $sgst_amount += $result['sgst_amount'];
            $igst_amount += $result['igst_amount'];           
        }
        $this->excel->getActiveSheet()->setCellValue('C'.$a, "Rs. ".number_format($gst_amount,2));
        $this->excel->getActiveSheet()->setCellValue('D'.$a, "Rs. ".number_format($taxable,2));
        $this->excel->getActiveSheet()->setCellValue('F'.$a, "Rs. ".number_format($cgst_amount,2));
        $this->excel->getActiveSheet()->setCellValue('H'.$a, "Rs. ".number_format($sgst_amount,2));
        $this->excel->getActiveSheet()->setCellValue('J'.$a, "Rs. ".number_format($igst_amount,2));

        $this->excel->getActiveSheet()->getStyle('C'.$a)->getFont()->setBold(true);                
        $this->excel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('H'.$a)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('J'.$a)->getFont()->setBold(true);
        

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

    
}