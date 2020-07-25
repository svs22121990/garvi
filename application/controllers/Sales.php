<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/mpdf/vendor/autoload.php');
class Sales extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Sales_model');
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
       return redirect('Sales');
    }
       
  }
  public function index()
  {   
    
    $this->common_view(site_url('Sales/ajax_manage_page'));
  }


  public function common_view($action,$date=0)
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
	          if($menu->value=='Sales')
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
	                <li class='active'>Sales Report</li>
	                </ul>";

	    $importaction = '';//'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
      $export ='<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>'; 
	    $download = '';//'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 

	    $data = array('date_data'=>$date,'breadcrumbs' => $breadcrumbs ,'actioncolumn' => '4' ,'ajax_manage_page' => $action, 'heading' => 'Sales', 'addPermission'=>$add, 'importaction' => $importaction, 'download' => $download, 'import' => $import,'export' =>$export, 'exportPermission'=>$exportbutton);

	    $this->load->view('sales/list',$data);
  	}
  	else
  	{
  		redirect('Dashboard');
  	}
  }

  public function ajax_manage_page($date=0)
  {
//    if($date!=0){
//      $date =  str_replace("_","-",$date);
//    }else{
//      $date = date('Y-m-d');
//    }
    $con="e.id<>''";
    
    $Data = $this->Sales_model->get_datatables($con,$date);
   
    //print_r($this->db->last_query());exit;
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
          if($menu->value=='Sales')
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
    foreach($Data as $row) 
    {
        $year = $this->datefind($date);
        $marchDate = $year.'-4-1';

        //yearly
        $where = array('in.date_of_invoice>='=>$marchDate,'in.created_by'=>$row->id);
        $arrYear = $this->Sales_model->salecount($where);
        

        $tomonth = date('Y', strtotime($date)).'-'.date('m', strtotime($date)).'-1';
        print_r($tomonth);
        $tomonth2 = date('Y', strtotime($date)).'-'.date('m', strtotime($date));
        $tomonth2 = date("Y-m-t", strtotime($tomonth2));
        //$tomonth2 = date('Y', strtotime($date)).'-'.date('m', strtotime($date)).'-t';
        $where2 = array('in.date_of_invoice>='=>$tomonth,'in.date_of_invoice<='=>$tomonth2,'in.created_by'=>$row->id);
        $arrMonth = $this->Sales_model->salecount($where2);

        $today = $date;
        $where3 = array('in.date_of_invoice='=>$today,'in.created_by'=>$row->id);
        $arrToday = $this->Sales_model->salecount($where3);

        $where4 = array('in.created_by'=>$row->id);
        $strTotal = $this->Sales_model->salecount($where4);
  
        $no++;
        $nestedData = array();
        $nestedData[] = $no;                       
        $nestedData[] = $row->name;                                            
        $nestedData[] = "Rs. ".number_format($arrToday,2);                       
        $nestedData[] = "Rs. ".number_format($arrMonth,2);
        $nestedData[] = "Rs. ".number_format($arrYear,2);
        $nestedData[] = "Rs. ".number_format($strTotal,2);                      
        //$nestedData[] = "Rs. ".number_format($row->yearly_sale,2);
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
                //"draw" => $_POST['draw'],
                "recordsTotal" => $this->Sales_model->count_all($con),
                "recordsFiltered" => $this->Sales_model->count_filtered($con),
                "data" => $data,
            );

    echo json_encode($output);
  }

   public function datefind($date)
  {

     $month =date('m', strtotime($date));
     $year =  date('Y', strtotime($date));
     if($month == 1 || $month == 2)
     {
         $date = strtotime("-1 year", strtotime($year));
         $year = date("Y", $date);
     }
     return $year;
  }

  public function printpdf($date=0)
  {    
      if($date!=0){
        $date =  str_replace("_","-",$date);
      }else{
        $date = date('Y-m-d');
      }
      $con="e.id<>''";
      $this->db->select('*');
        $this->db->from('employees as e');
       // $this->db->from("dispatch i");
        //$this->db->join("dispatch_details ide","i.id = ide.dispatch_id","left");
        //$this->db->join("assets a","a.id = ide.product_id","left");
       // $this->db->join("employees e","e.id = ide.created_by","left");
        $this->db->where($con);
        $this->db->where('type','User');
        $this->db->group_by("e.id");
        $query = $this->db->get();
        $arrSales =  $query->result();
      //$arrSales = $this->Sales_model->get_datatables($con);
      //$data['results'] = $this->Assets_model->getAllDetails($id);
      $arrResult = array();
      foreach($arrSales as $arr){
          $year = $this->datefind($date);
            $marchDate = $year.'-3-1';

            //yearly
            $where = array('in.date_of_invoice>='=>$marchDate,'in.created_by'=>$arr->id);
            $arrYear = $this->Sales_model->salecount($where);
            

            $tomonth = date('Y', strtotime($date)).'-'.date('m', strtotime($date)).'-1';
            $tomonth2 = date('Y', strtotime($date)).'-'.date('m', strtotime($date));
            $tomonth2 = date("Y-m-t", strtotime($tomonth2));
            //$tomonth2 = date('Y', strtotime($date)).'-'.date('m', strtotime($date)).'-t';
            $where2 = array('in.date_of_invoice>='=>$tomonth,'in.date_of_invoice<='=>$tomonth2,'in.created_by'=>$arr->id);
            $arrMonth = $this->Sales_model->salecount($where2);

            $today = $date;
            $where3 = array('in.date_of_invoice='=>$today,'in.created_by'=>$arr->id);
            $arrToday = $this->Sales_model->salecount($where3);

            $where4 = array('in.created_by'=>$arr->id);
            $strTotal = $this->Sales_model->salecount($where4);

          $arrResult[] = array(
            'name' => $arr->name,
            'today' => $arrToday,
            'month' => $arrMonth,
            'year' => $arrYear,
            'total' => $strTotal,
          );
      }

      $data['results'] = $arrResult;
      
      $html = $this->load->view('sales/sales_pdf',$data,TRUE);
      $mpdf = new \Mpdf\Mpdf();
      $mpdf->WriteHTML($html);
      $mpdf->Output();
  }

  /* ----- Export functionality start ----- */
    public function export_sales_summary($date=0)
    {
        if($date!=0){
          $date =  str_replace("_","-",$date);
        }else{
          $date = date('Y-m-d');
        }
        $con="e.id<>''";
    
        $results = $this->Sales_model->get_datatables($con,$date);

        $FileTitle='Showroomwise Sales Summary';
                
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Report Sheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Sales Summary Details ');

        $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
        $this->excel->getActiveSheet()->setCellValue('B3', 'User');
        $this->excel->getActiveSheet()->setCellValue('C3', 'Today\'s Sales');
        $this->excel->getActiveSheet()->setCellValue('D3', 'Current Month sales');
        $this->excel->getActiveSheet()->setCellValue('E3', 'Current Year sales');
        $this->excel->getActiveSheet()->setCellValue('F3', 'Total Sales');
            
        $a='4'; $sr = 1;    
        //print_r($results);exit;
        foreach ($results as $result) 
        {     

            $year = $this->datefind($date);
            $marchDate = $year.'-3-1';

            //yearly
            $where = array('in.date_of_invoice>='=>$marchDate,'in.created_by'=>$result->id);
            $arrYear = $this->Sales_model->salecount($where);
            

            $tomonth = date('Y', strtotime($date)).'-'.date('m', strtotime($date)).'-1';
            $tomonth2 = date('Y', strtotime($date)).'-'.date('m', strtotime($date));
            $tomonth2 = date("Y-m-t", strtotime($tomonth2));
            //$tomonth2 = date('Y', strtotime($date)).'-'.date('m', strtotime($date)).'-t';
            $where2 = array('in.date_of_invoice>='=>$tomonth,'in.date_of_invoice<='=>$tomonth2,'in.created_by'=>$result->id);
            $arrMonth = $this->Sales_model->salecount($where2);

            $today = $date;
            $where3 = array('in.date_of_invoice='=>$today,'in.created_by'=>$result->id);
            $arrToday = $this->Sales_model->salecount($where3);

            $where4 = array('in.created_by'=>$result->id);
            $strTotal = $this->Sales_model->salecount($where4); 

            
            /*$total = $result->sum + $result->transport ;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;*/
            
            $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
            $this->excel->getActiveSheet()->setCellValue('B'.$a, $result->name);
            $this->excel->getActiveSheet()->setCellValue('C'.$a, "Rs. ".number_format($arrToday,2));
            $this->excel->getActiveSheet()->setCellValue('D'.$a, "Rs. ".number_format($arrMonth,2));                
            $this->excel->getActiveSheet()->setCellValue('E'.$a, "Rs. ".number_format($arrYear,2));                
            $this->excel->getActiveSheet()->setCellValue('F'.$a, "Rs. ".number_format($strTotal,2));                
            
            //$this->excel->getActiveSheet()->setCellValue('R'.$a, $row->shipping_charges);
            //$this->excel->getActiveSheet()->setCellValue('G'.$a, $result->status);
            //$this->excel->getActiveSheet()->setCellValue('H'.$a, $total);
            $sr++; $a++;                
        }
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);                
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        
        //$this->excel->getActiveSheet()->mergeCells('A1:H1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
        $filename=''.$FileTitle.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        ob_clean();
                    
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
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
