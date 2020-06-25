<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
class Inventory extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Inventory_model');
    $this->load->library('upload');
    $this->load->library('image_lib');
    $this->image_lib->clear();
    $this->load->helper(array('form', 'url', 'html'));
    $this->load->database();
  }
  public function index()
  {
    // print_r($_SESSION[SESSION_NAME]);exit;
    $import = '';
    $add = '';
    if (!empty($_SESSION[SESSION_NAME]['getMenus'])) {
        foreach ($_SESSION[SESSION_NAME]['getMenus'] as $row) {
            foreach ($row as $menu) {
                if ($menu->value == 'Inventory') {

                    if (!empty($menu->act_add)) {
                      $add = '1';
                    } else {
                      $add = '0';
                    }
                    if (!empty($menu->act_export)) {
                      $exportbutton = '1';
                    } else {
                      $exportbutton = '0';
                    }
                    if (!empty($menu->act_import)) {
                      $import = '1';
                    } else {
                      $import = '0';
                    }
                  }
              }
          }

        $breadcrumbs = "<ul class='breadcrumb'>
	                <li>
	                    <i class='ace-icon fa fa-home home-icon'></i>
	                    <a href='" . site_url('Dashboard') . "'>Dashboard</a>
	                </li>
	                <li class='active'>Inventory</li>
	                </ul>";

        $importaction = ''; //'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
        $export = '<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>';
        $download = ''; //'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>';

        $data = array('breadcrumbs' => $breadcrumbs, 'actioncolumn' => '6', 'ajax_manage_page' => site_url('Inventory/ajax_manage_page'), 'heading' => 'Inventory (Product)', 'addPermission' => $add, 'importaction' => $importaction, 'download' => $download, 'import' => $import, 'export' => $export, 'exportPermission' => $exportbutton);

        $this->load->view('inventory/list', $data);
      } else {
        redirect('Dashboard');
      }
  }

  public function ajax_manage_page()
  {
    $con = "e.id<>''";

    $Data = $this->Inventory_model->get_datatables($con);
    //print_r($this->db->last_query());exit;
    $edit = '';
    $delete = '';
    $actstatus = '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $view = '';

    foreach ($_SESSION[SESSION_NAME]['getMenus'] as $row) {
        foreach ($row as $menu) {
            if ($menu->value == 'Sales') {

                if (!empty($menu->act_edit)) {
                  $edit = '1';
                } else {
                  $edit = '0';
                }
                if (!empty($menu->act_delete)) {
                  $delete = '1';
                } else {
                  $delete = '0';
                }
                if (!empty($menu->act_status)) {
                  $actstatus = '1';
                } else {
                  $actstatus = '0';
                }
                if (!empty($menu->act_add)) {
                  $add = '1';
                } else {
                  $add = '0';
                }
                if (!empty($menu->act_add_existing_stock)) {
                  $act_add_existing_stock = '1';
                } else {
                  $act_add_existing_stock = '0';
                }
                if (!empty($menu->act_log_details)) {
                  $act_log_details = '1';
                } else {
                  $act_log_details = '0';
                }
                if (!empty($menu->act_transfer)) {
                  $act_transfer = '1';
                } else {
                  $act_transfer = '0';
                }
                if (!empty($menu->act_view)) {
                  $view = '1';
                } else {
                  $view = '0';
                }
              }
          }
      }

    $data = array();
    $no = 0;
    foreach ($Data as $row) {
        $last_six = $this->countDate('-6 month');
        $six_where = array('a.purchase_date>=' => $last_six, 'a.created_by' => $row->id);
        $six_month = $this->Inventory_model->count_inventory($six_where);

        $last_one = $this->countDate('-1 year');
        $one_where = array('a.purchase_date>=' => $last_one, 'a.created_by' => $row->id);
        $one_year = $this->Inventory_model->count_inventory($one_where);

        $last_two = $this->countDate('-2 year');
        $two_where = array('a.purchase_date>=' => $last_two, 'a.created_by' => $row->id);
        $two_year = $this->Inventory_model->count_inventory($two_where);

        $last_three = $this->countDate('-3 year');
        $three_where = array('a.purchase_date>=' => $last_three, 'a.created_by' => $row->id);
        $three_year = $this->Inventory_model->count_inventory($three_where);

        $three_where_1 = array('a.purchase_date<=' => $last_three, 'a.created_by' => $row->id);
        $three_year1 = $this->Inventory_model->count_inventory($three_where_1);

        $total_am_where = array('a.created_by' => $row->id);
        $total_am = $this->Inventory_model->count_inventory($total_am_where);

        $no++;
        $nestedData = array();
        $nestedData[] = $no;
        $nestedData[] = $row->name;
        $nestedData[] = "Rs. " . number_format($six_month, 2);
        $nestedData[] = "Rs. " . number_format($one_year - $six_month, 2);
        $nestedData[] = "Rs. " . number_format($two_year - $one_year, 2);
        $nestedData[] = "Rs. " . number_format($three_year - $two_year, 2);
        $nestedData[] = "Rs. " . number_format($three_year1, 2);
        $nestedData[] = "Rs. " . number_format($total_am, 2);
        $nestedData[] = '<a style="color:#fff" title="View" href="' . site_url('Inventory/view/' . $row->id) . '"><span class="btn btn-primary btn-circle btn-sm"><i class="ace-icon fa fa-eye bigger-130"></i></span></a>';
        $data[] = $nestedData;
        $selected = '';
      }

    $output = array(
      //"draw" => $_POST['draw'],
      "recordsTotal" => $this->Inventory_model->count_all($con),
      "recordsFiltered" => $this->Inventory_model->count_filtered($con),
      "data" => $data,
    );

    echo json_encode($output);
  }

  public function countDate($time)
  {
    $start_date = date('Y-m-d');
    $date = DateTime::createFromFormat('Y-m-d', $start_date);

    $date->modify($time);
    return $last_date = $date->format('Y-m-d');
  }

  public function export_pdf_summary($id, $date = 0)
  {
    $this->db->select('p.*,a.*,c.*,t.*');
    $this->db->from("products p");
    $this->db->join("assets a", "a.product_id = p.id", "left");
    $this->db->join("categories c", "a.category_id = c.id", "left");
    $this->db->join("mst_asset_types t", "t.id = a.asset_type_id", "left");
    //$this->db->where($con);
    if ($date != 0) {
      $dates = explode("_", $date);
      $date1 = date("Y-m-d", strtotime($dates[0]));
      $date2 = date("Y-m-d", strtotime($dates[1]));
      $this->db->where('a.purchase_date >=', $date1);
      $this->db->where('a.purchase_date <=', $date2);
    }


    $this->db->where('a.created_by', $id);
    $query = $this->db->get();
    $data['results'] = $query->result();

    //$data['results'] = $this->Product_Summary_model->get_datatables($con,$date);
    $html = $this->load->view('product_summary/pdf_summery', $data, true);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Product Summary of a User', 'I');
  }

  /* ----- Export functionality start ----- */
  public function export_product_summary($id, $date = 0)
  {
    $this->db->select('p.*,a.*,c.*,t.*');
    $this->db->from("products p");
    $this->db->join("assets a", "a.product_id = p.id", "left");
    $this->db->join("categories c", "a.category_id = c.id", "left");
    $this->db->join("mst_asset_types t", "t.id = a.asset_type_id", "left");
    //$this->db->where($con);
    if ($date != 0) {
      $dates = explode("_", $date);
      $date1 = date("Y-m-d", strtotime($dates[0]));
      $date2 = date("Y-m-d", strtotime($dates[1]));
      $this->db->where('a.purchase_date >=', $date1);
      $this->db->where('a.purchase_date <=', $date2);
    }
    $this->db->where('a.created_by', $id);
    $query = $this->db->get();
    $results = $query->result();
    //$con = "p.id<>0";

    //$results = $this->Product_Summary_model->ExportCSV($con);
    $FileTitle = 'Product Summary Report';

    $this->load->library('excel');
    //activate worksheet number 1
    $this->excel->setActiveSheetIndex(0);
    //name the worksheet
    $this->excel->getActiveSheet()->setTitle('Report Sheet');
    //set cell A1 content with some text
    $this->excel->getActiveSheet()->setCellValue('A1', 'Product Summary Details ');

    $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
    $this->excel->getActiveSheet()->setCellValue('B3', 'Name');
    $this->excel->getActiveSheet()->setCellValue('C3', 'Category');
    $this->excel->getActiveSheet()->setCellValue('D3', 'Type');
    $this->excel->getActiveSheet()->setCellValue('E3', 'Selling Price');
    $this->excel->getActiveSheet()->setCellValue('F3', 'Quantity');
    $this->excel->getActiveSheet()->setCellValue('G3', 'Remaining');
    $this->excel->getActiveSheet()->setCellValue('H3', 'Purchase Date');
    $this->excel->getActiveSheet()->setCellValue('I3', 'Age');

    $a = '4';
    $sr = 1;
    //print_r($results);exit;
    foreach ($results as $result) {
        $startDate = $result->purchase_date;
        $endDate = date('Y-m-d');

        $datetime1 = date_create($startDate);
        $datetime2 = date_create($endDate);
        $interval = date_diff($datetime1, $datetime2, false);
        $arrTime = array();
        if ($interval->y != 0) {
          $arrTime[] =  $interval->y . ' Year ';
        }
        if ($interval->m != 0) {
          $arrTime[] =  $interval->m . ' Months ';
        }
        $arrTime[] =  $interval->d . ' Days Ago';
        /*$total = $result->sum + $result->transport ;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;*/

        $this->excel->getActiveSheet()->setCellValue('A' . $a, $sr);
        $this->excel->getActiveSheet()->setCellValue('B' . $a, $result->asset_name);
        $this->excel->getActiveSheet()->setCellValue('C' . $a, $result->title);
        $this->excel->getActiveSheet()->setCellValue('D' . $a, $result->type);
        $this->excel->getActiveSheet()->setCellValue('E' . $a, "Rs. " . $result->product_mrp);
        $this->excel->getActiveSheet()->setCellValue('F' . $a, $result->total_quantity);
        $this->excel->getActiveSheet()->setCellValue('G' . $a, $result->quantity);
        $this->excel->getActiveSheet()->setCellValue('H' . $a, $result->purchase_date);
        $this->excel->getActiveSheet()->setCellValue('I' . $a, implode(" ", $arrTime));

        $sr++;
        $a++;
      }
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
    //$this->excel->getActiveSheet()->mergeCells('A1:H1');
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $filename = '' . $FileTitle . '.xls'; //save our workbook as this file name
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    ob_clean();

    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    //force user to download the Excel file without writing it to server's HD
    $objWriter->save('php://output');
  }

  public function view($id)
  {
    //$con = '';
    $this->db->select('p.*,a.*,c.*,t.*,(select label from product_type where id=a.product_type_id) as product_type,');
    $this->db->from("products p");
    $this->db->join("assets a", "a.product_id = p.id", "left");
    $this->db->join("categories c", "a.category_id = c.id", "left");
    $this->db->join("mst_asset_types t", "t.id = a.asset_type_id", "left");
    //$this->db->where($con);
    $this->db->where('a.created_by', $id);
    $query = $this->db->get();
    $products = $query->result();

    $import = '';
    $add = "p.id<>''";
    if (!empty($_SESSION[SESSION_NAME]['getMenus'])) {
        foreach ($_SESSION[SESSION_NAME]['getMenus'] as $row) {
            foreach ($row as $menu) {
                if ($menu->value == 'Product_Summary') {

                    if (!empty($menu->act_add)) {
                      $add = '1';
                    } else {
                      $add = '0';
                    }
                    if (!empty($menu->act_export)) {
                      $exportbutton = '1';
                    } else {
                      $exportbutton = '0';
                    }
                    if (!empty($menu->act_import)) {
                      $import = '1';
                    } else {
                      $import = '0';
                    }
                  }
              }
          }

        $breadcrumbs = "<ul class='breadcrumb'>
                  <li>
                      <i class='ace-icon fa fa-home home-icon'></i>
                      <a href='/Dashboard'>Dashboard</a>
                  </li>
                  <li class='active'>Product Summary of a User </li>
                  </ul>";
//  <a href='" . site_url('Dashboard') . "'>Dashboard</a>
        $importaction = ''; //'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
        $export = '<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>';
        $download = ''; //'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>';
        //echo $date;
        //exit();
        $data = array('user_id' => $id, 'breadcrumbs' => $breadcrumbs, 'actioncolumn' => '9', 'heading' => 'Product Summary of a User ', 'addPermission' => $add, 'importaction' => $importaction, 'download' => $download, 'import' => $import, 'export' => $export, 'products' => $products);
        $this->load->view('inventory/product_list', $data);
      } else {
        redirect('Dashboard');
      }
  }

  /* ----- Export functionality start ----- */
  public function export_sales_summary()
  {
    $con = "e.id<>0";

    $results = $this->Inventory_model->get_datatables($con);
    $FileTitle = 'Inventory Report';

    $this->load->library('excel');

    $objWorkSheet = new PHPExcel();

    //activate worksheet number 1
    $this->excel->setActiveSheetIndex(0);

    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName("name");
    $objDrawing->setDescription("Description");
    $objDrawing->setPath(base_url() . 'images/logo.jpg', false);
    // $objDrawing->setPath(site_url('images/logo.jpg'));
    $objDrawing->setCoordinates('A1');
    $objDrawing->setWorksheet($objWorkSheet->getActiveSheet());
    //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

    //name the worksheet
    $this->excel->getActiveSheet()->setTitle('Report Sheet');
    //set cell A1 content with some text
    $this->excel->getActiveSheet()->setCellValue('A1', 'Inventory');

    $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
    $this->excel->getActiveSheet()->setCellValue('B3', 'User');
    $this->excel->getActiveSheet()->setCellValue('C3', 'Less then 6 months');
    $this->excel->getActiveSheet()->setCellValue('D3', '6 Months to 1 Year');
    $this->excel->getActiveSheet()->setCellValue('E3', '1 Year to 2 Year');
    $this->excel->getActiveSheet()->setCellValue('F3', '2 Year to 3 Year');
    $this->excel->getActiveSheet()->setCellValue('G3', 'Above 3 Year');
    $this->excel->getActiveSheet()->setCellValue('H3', 'Total');

    $a = '4';
    $sr = 1;
    //print_r($results);exit;
    foreach ($results as $result) {
        $last_six = $this->countDate('-6 month');
        $six_where = array('a.purchase_date>=' => $last_six, 'a.created_by' => $result->id);
        $six_month = $this->Inventory_model->count_inventory($six_where);

        $last_one = $this->countDate('-1 year');
        $one_where = array('a.purchase_date>=' => $last_one, 'a.created_by' => $result->id);
        $one_year = $this->Inventory_model->count_inventory($one_where);

        $last_two = $this->countDate('-2 year');
        $two_where = array('a.purchase_date>=' => $last_two, 'a.created_by' => $result->id);
        $two_year = $this->Inventory_model->count_inventory($two_where);

        $last_three = $this->countDate('-3 year');
        $three_where = array('a.purchase_date>=' => $last_three, 'a.created_by' => $result->id);
        $three_year = $this->Inventory_model->count_inventory($three_where);

        $three_where_1 = array('a.purchase_date<=' => $last_three, 'a.created_by' => $result->id);
        $three_year1 = $this->Inventory_model->count_inventory($three_where_1);

        $total_am_where = array('a.created_by' => $result->id);
        $total_am = $this->Inventory_model->count_inventory($total_am_where);


        /*$total = $result->sum + $result->transport ;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;*/

        $this->excel->getActiveSheet()->setCellValue('A' . $a, $sr);
        $this->excel->getActiveSheet()->setCellValue('B' . $a, $result->name);
        $this->excel->getActiveSheet()->setCellValue('C' . $a, "Rs. " . number_format($six_month, 2));
        $this->excel->getActiveSheet()->setCellValue('D' . $a, "Rs. " . number_format($one_year - $six_month, 2));
        $this->excel->getActiveSheet()->setCellValue('E' . $a, "Rs. " . number_format($two_year - $one_year, 2));
        $this->excel->getActiveSheet()->setCellValue('F' . $a, "Rs. " . number_format($three_year - $two_year, 2));
        $this->excel->getActiveSheet()->setCellValue('G' . $a, "Rs. " . number_format($three_year1, 2));
        $this->excel->getActiveSheet()->setCellValue('H' . $a, "Rs. " . number_format($total_am, 2));

        //$this->excel->getActiveSheet()->setCellValue('R'.$a, $row->shipping_charges);
        //$this->excel->getActiveSheet()->setCellValue('G'.$a, $result->status);
        //$this->excel->getActiveSheet()->setCellValue('H'.$a, $total);
        $sr++;
        $a++;
      }
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

    //$this->excel->getActiveSheet()->mergeCells('A1:H1');
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $filename = '' . $FileTitle . '.xls'; //save our workbook as this file name
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    ob_clean();

    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    //force user to download the Excel file without writing it to server's HD
    $objWriter->save('php://output');
  }
  /* ----- Export functionality end ----- */

  public function printpdf()
  {
    $this->db->select('*');
    $this->db->from("employees e");
    $this->db->where('type', 'User');
    $query = $this->db->get();
    $arrSales =  $query->result();
    //$arrSales = $this->Sales_model->get_datatables($con);
    //$data['results'] = $this->Assets_model->getAllDetails($id);
    $arrResult = array();
    foreach ($arrSales as $arr) {
      $last_six = $this->countDate('-6 month');
      $six_where = array('a.purchase_date>=' => $last_six, 'a.created_by' => $arr->id);
      $six_month = $this->Inventory_model->count_inventory($six_where);

      $last_one = $this->countDate('-1 year');
      $one_where = array('a.purchase_date>=' => $last_one, 'a.created_by' => $arr->id);
      $one_year = $this->Inventory_model->count_inventory($one_where);

      $last_two = $this->countDate('-2 year');
      $two_where = array('a.purchase_date>=' => $last_two, 'a.created_by' => $arr->id);
      $two_year = $this->Inventory_model->count_inventory($two_where);

      $last_three = $this->countDate('-3 year');
      $three_where = array('a.purchase_date>=' => $last_three, 'a.created_by' => $arr->id);
      $three_year = $this->Inventory_model->count_inventory($three_where);

      $three_where_1 = array('a.purchase_date<=' => $last_three, 'a.created_by' => $arr->id);
      $three_year1 = $this->Inventory_model->count_inventory($three_where_1);

      $total_am_where = array('a.created_by' => $arr->id);
      $total_am = $this->Inventory_model->count_inventory($total_am_where);


      $arrResult[] = array(
        'name' => $arr->name,
        'six_month' => $six_month,
        'one_year' => $one_year - $six_month,
        'two_year' => $two_year - $one_year,
        'three_year' => $three_year - $two_year,
        'three_year1' => $three_year1,
        'total' => $total_am,
      );
    }

    $data['results'] = $arrResult;

    $html = $this->load->view('inventory/in_pdf', $data, TRUE);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Inventory', 'I');
  }

  public function getGST()
  {
    $select = $this->Crud_model->GetData("assets", "", "product_id='" . $_POST['product_id'] . "'", "", "", "", "1");
    if (!empty($select)) {
      $response['success'] = '1';
      $response['gst_percent'] = $select->gst_percent;
      $response['hsn'] = $select->hsn;
    } else {
      $response['success'] = '0';
    }

    echo json_encode($response);
    exit;
  }
}
