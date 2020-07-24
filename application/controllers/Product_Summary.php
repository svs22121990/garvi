<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
class Product_Summary extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Product_Summary_model');
    $this->load->library('upload');
    $this->load->library('image_lib');
    $this->image_lib->clear();
    $this->load->helper(array('form', 'url', 'html'));
    $this->load->database();
  }
  public function search()
  {
    if ($this->input->post()) {
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
        if($this->input->post('color') != "")
            $color = $this->input->post('color');
        else
            $color = 0;
        if($this->input->post('size') != "")
            $size = $this->input->post('size');
        else
            $size = 0;
        if($this->input->post('fabric') != "")
            $fabric = $this->input->post('fabric');
        else
            $fabric = 0;
        if($this->input->post('craft') != "")
            $craft = $this->input->post('craft');
        else
            $craft = 0;
        if($this->input->post('cat') != "")
            $cat = $this->input->post('cat');
        else
            $cat = 0;

        //$newDate = date("Y-m-d", strtotime($date));
        $strUrl = site_url('Product_Summary/ajax_manage_page/' . $date . '/'. $type . '/'. $type2. '/'. $color . '/'. $size . '/'. $fabric . '/'. $craft. '/'. $cat);
        $this->common_view($strUrl, $date, $type, $type2, $color, $size, $fabric, $craft, $cat);
      } else {
        return redirect('Product_Summary');
      }
  }
  public function index()
  {

    $this->common_view(site_url('Product_Summary/ajax_manage_page'));
  }

  public function common_view($action, $date = 0, $type=0, $type2=0, $color = 0, $size=0, $fabric=0, $craft=0, $cat=0)
  {
    // print_r($_SESSION[SESSION_NAME]);exit;
    $import = '';
    $add = '';
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
                      <a href='" . site_url('Dashboard') . "'>Dashboard</a>
                  </li>
                  <li class='active'>Manage Product Summary</li>
                  </ul>";

        $importaction = ''; //'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
        $export = '<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>';
        $download = ''; //'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 
        
        $types = $this->Crud_model->GetData('mst_asset_types', "", "status='Active' and is_delete='No'", 'type');
        $product_types =  $this->Crud_model->GetData('product_type', "", "status='Active'");
        $sizes = $this->Crud_model->GetData('size', "", "status='Active'", '', 'title asc');
        $fabrics = $this->Crud_model->GetData('fabric', "", "status='Active'", '', 'title asc');
        $colors = $this->Crud_model->GetData('color', "", "status='Active'", '', 'title asc');
        $crafts = $this->Crud_model->GetData('craft', "", "status='Active'", '', 'title asc');
        $category = $this->Crud_model->GetData('categories', "", "status='Active'", '', 'title asc');
        
        if($date != 0)
        {
            $date = str_replace("-", "/", $date);
            $date = str_replace("_", " - ", $date);
        } else {
            $date = 0;
        }
        $data = array('sizes' => $sizes,'fabrics' => $fabrics,'colors' => $colors,'crafts' => $crafts, 'category' => $category, 'selected_size' => $size,'selected_fabric' => $fabric, 'selected_color' => $color, 'selected_craft' => $craft, 'selected_cat' => $cat,
        'selected_date' => $date,'selected_type' => $type, 'selected_type2' => $type2, 
        'types' => $types, 'product_types' => $product_types, 'dateinfo' => $date, 'breadcrumbs' => $breadcrumbs, 'actioncolumn' => '9', 'ajax_manage_page' => $action, 'heading' => 'Manage Product Summary', 'addPermission' => $add, 'importaction' => $importaction, 'download' => $download, 'import' => $import, 'export' => $export, 'exportPermission' => $exportbutton);
        $this->load->view('product_summary/list', $data);
      } else {
        redirect('Dashboard');
      }
  }

  public function ajax_manage_page($date = 0, $type = 0, $type2 = 0, $color = 0, $size=0, $fabric=0, $craft=0, $cat=0)
  {

    $con = "p.id<>''";
    if($type != 0)
        $con .= "and a.asset_type_id ='". $type . "'";
    if($type2 != 0)
        $con .= "and a.product_type_id ='". $type2 . "'";
    if($color != 0)
        $con .= "and a.color_id ='". $color . "'";
    if($size != 0)
        $con .= "and a.size_id ='". $size . "'";
    if($fabric != 0)
        $con .= "and a.fabric_id ='". $fabric . "'";
    if($craft != 0)
        $con .= "and a.craft_id ='". $craft . "'";
    if($cat != 0)
        $con .= "and a.category_id ='". $cat . "'";
    if (!empty($_SESSION[SESSION_NAME]['branch_id'])) {
      $con .= " and ast.id in (select asset_id from asset_branch_mappings where branch_id='" . $_SESSION[SESSION_NAME]['branch_id'] . "')";
    }

    $Data = $this->Product_Summary_model->get_datatables($con, $date);
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
            if ($menu->value == 'Products') {

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
        $startDate = $row->product_purchase_date;
        $endDate = date('Y-m-d');

        $datetime1 = date_create($startDate);
        $datetime2 = date_create($endDate);
        $interval = date_diff($datetime1, $datetime2, false);
        $arrTime = array();
        if ($interval->y != 0) {
          $arrTime[] =  $interval->y . ' Year ';
        }
        if ($interval->m != 0) {
          $arrTime[] =  $interval->m . ' Month ';
        }
        $arrTime[] =  $interval->d . ' Day';

//        $no++;
        $product_id =  $row->id;
        $data[] = array(
          'no' => $no++,
          'asset_name' => $row->asset_name,
          'title' => $row->title,
          'type' => $row->type,
          'hsn_code' => $row->hsn,
          'product_mrp' => number_format($row->product_mrp, 2),
          'total_quantity' => $row->total_quantity,
          'damage_qty' => $row->damage_qty,
          'quantity' => $row->quantity,
          'total' => number_format($row->quantity * $row->product_mrp, 2),
          'gst' => number_format(($row->quantity * $row->product_mrp) * ($row->gst_percent/100), 2),
          'purchase_date' => $row->purchase_date,
          'attributes' => '<b>Color : </b>'.$row->color.'</br><b>Size : </b>'.$row->size.'</br><b>Fabric : </b>'.$row->fabric.'</br><b>Craft : </b>'.$row->craft,
          'product_purchase_date' => $row->product_purchase_date,
          'productType' => $row->product_type,
          'time' => implode(" ", $arrTime),
          'btn' =>  "<a href='javascript:void(0)' onclick='addDamage(" . $product_id . ")' title='Add Damage' class='btn btn-danger btn-circle btn-sm edit-qty'><i class='fa fa-plus'></i></a>",
        );
      }
    
    $output = array("data" => $data);

    echo json_encode($output);
  }

  public function damageadd()
  {
    $product_id = $this->input->post('product_id');
    $qty = $this->input->post('qty');
    $this->db->select('*');
    $this->db->where('id', $product_id);
    $query = $this->db->get('assets');
    $product = $query->row();
    if ($qty <= $product->quantity) {
      $data = array('damage_qty' => $product->damage_qty + $qty, 
                    //'quantity' => $product->quantity - $qty
                  );
      $condition = array('id' => $product_id);
      $this->Crud_model->SaveData('assets', $data, $condition);
      $msgArray = array('status' => 'success', 'msg' => 'Damage Quantity added suucessfully!');
    } else {
      $msgArray = array('status' => 'error', 'msg' => 'Please select valid quantity!');
    }
    echo json_encode($msgArray);
  }

  public function date_search()
  {
    echo $this->input->post('start_date') . '<br>';
    echo $this->input->post('end_date');
  }
  public function datetimeDiff($dt1, $dt2)
  {
    $t1 = strtotime($dt1);
    $t2 = strtotime($dt2);

    $dtd = new stdClass();
    $dtd->interval = $t2 - $t1;
    $dtd->total_sec = abs($t2 - $t1);
    $dtd->total_min = floor($dtd->total_sec / 60);
    $dtd->total_hour = floor($dtd->total_min / 60);
    $dtd->total_day = floor($dtd->total_hour / 24);

    $dtd->day = $dtd->total_day;
    $dtd->hour = $dtd->total_hour - ($dtd->total_day * 24);
    $dtd->min = $dtd->total_min - ($dtd->total_hour * 60);
    $dtd->sec = $dtd->total_sec - ($dtd->total_min * 60);
    return $dtd;
  }
  //=======================export list===========================

  public function export_pdf_summary($date = 0)
  {
    $this->db->select('
    p.*,
    a.*,
    c.*,
    t.*,
    (select label from product_type where id=a.product_type_id) as productType2
    ');
    $this->db->from("products p");
    $this->db->join("assets a", "a.product_id = p.id", "left");
    $this->db->join("categories c", "a.category_id = c.id", "left");
    $this->db->join("mst_asset_types t", "t.id = a.asset_type_id", "left");
    //$this->db->where($con);
    if ($date != 0) {
      $dates = explode("_", $date);
      $date1 = date("Y-m-d", strtotime($dates[0]));
      $date2 = date("Y-m-d", strtotime($dates[1]));
      $this->db->where('p.purchase_date >=', $date1);
      $this->db->where('p.purchase_date <=', $date2);
    }


    $this->db->where('a.created_by', $_SESSION[SESSION_NAME]['id']);
    $query = $this->db->get();
    $data['results'] = $query->result();

    //$data['results'] = $this->Product_Summary_model->get_datatables($con,$date);
    $html = $this->load->view('product_summary/pdf_summery', $data, true);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Product_Summary', 'I');
  }

  //=======================/export list==========================

   /* ----- Export functionality start ----- */
   public function export_product_summary($date = 0, $type = 0, $type2 = 0, $color = 0, $size=0, $fabric=0, $craft=0, $cat=0)
   {
       $con = "p.id<>''";
       if($type != 0)
           $con .= "and p.asset_type_id ='". $type . "'";
       if($type2 != 0)
           $con .= "and p.product_type_id ='". $type2 . "'";
       if($color != 0)
           $con .= "and p.color_id ='". $color . "'";
       if($size != 0)
           $con .= "and p.size_id ='". $size . "'";
       if($fabric != 0)
           $con .= "and p.fabric_id ='". $fabric . "'";
       if($craft != 0)
           $con .= "and p.craft_id ='". $craft . "'";
       if($cat != 0)
           $con .= "and p.category_id ='". $cat . "'";
       if (!empty($_SESSION[SESSION_NAME]['branch_id'])) {
           $con .= " and ast.id in (select asset_id from asset_branch_mappings where branch_id='" . $_SESSION[SESSION_NAME]['branch_id'] . "')";
       }

       $results = $this->Product_Summary_model->get_datatables($con, $date);
       //$results = $query->result();
       //$con = "p.id<>0";

       //$results = $this->Warehouse_product_summary_model->ExportCSV($con);
       $FileTitle = 'Product Summary Report';

       $this->load->library('excel');
       //activate worksheet number 1
       $this->excel->setActiveSheetIndex(0);
       //name the worksheet
       $this->excel->getActiveSheet()->setTitle('Report Sheet');
       //set cell A1 content with some text
       $this->excel->getActiveSheet()->setCellValue('A1', 'Gujarat State Handloom & Handicraft Development Corp. Ltd.');
       $this->excel->getActiveSheet()->setCellValue('A2', $_SESSION[SESSION_NAME]['address']);
       $this->excel->getActiveSheet()->setCellValue('A3', $_SESSION[SESSION_NAME]['gst_number']);
       $this->excel->getActiveSheet()->setCellValue('A4', 'Product Summary Report');

       $this->excel->getActiveSheet()->setCellValue('A5', 'Sr. No');
       $this->excel->getActiveSheet()->setCellValue('B5', 'Purchase Date');
       $this->excel->getActiveSheet()->setCellValue('C5', 'Name');
       $this->excel->getActiveSheet()->setCellValue('D5', 'Received From');
       $this->excel->getActiveSheet()->setCellValue('E5', 'Category');
       $this->excel->getActiveSheet()->setCellValue('F5', 'Type');
       $this->excel->getActiveSheet()->setCellValue('G5', 'Type 2');
       $this->excel->getActiveSheet()->setCellValue('H5', 'Size');
       $this->excel->getActiveSheet()->setCellValue('I5', 'Color');
       $this->excel->getActiveSheet()->setCellValue('J5', 'Fabric');
       $this->excel->getActiveSheet()->setCellValue('K5', 'Craft');
       $this->excel->getActiveSheet()->setCellValue('L5', 'HSN Code');
       $this->excel->getActiveSheet()->setCellValue('M5', 'Qty');
       $this->excel->getActiveSheet()->setCellValue('N5', 'Damage Qty ');
//       $this->excel->getActiveSheet()->setCellValue('O5', 'Cost Price');
//       $this->excel->getActiveSheet()->setCellValue('P5', 'Total Cost Amt');
       $this->excel->getActiveSheet()->setCellValue('O5', 'GST Amount');
       $this->excel->getActiveSheet()->setCellValue('P5', 'Selling Price');
//       $this->excel->getActiveSheet()->setCellValue('P5', 'Selling Price');
       $this->excel->getActiveSheet()->setCellValue('Q5', 'Total Amount');
//       $this->excel->getActiveSheet()->setCellValue('U5', 'Barcode Number');
       $this->excel->getActiveSheet()->setCellValue('R5', 'AGE');

       $a = '6';
       $sr = 1;

       $total_quantity =   0;
       $total_available_qty =  0;
       $total_cost_total =  0;
       $total_price = 0;
       $total_gst = 0;
       $total_product_mrp = 0;
       $total_sp_total = 0;
       foreach ($results as $result) {
           $startDate = $result->product_purchase_date;
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

           $this->excel->getActiveSheet()->setCellValue('A' . $a, $sr);
           $this->excel->getActiveSheet()->setCellValue('B' . $a, $result->product_purchase_date);
           $this->excel->getActiveSheet()->setCellValue('C' . $a, $result->asset_name);
           $this->excel->getActiveSheet()->setCellValue('D' . $a, $result->name);
           $this->excel->getActiveSheet()->setCellValue('E' . $a, $result->title);
           $this->excel->getActiveSheet()->setCellValue('F' . $a, $result->type);
           $this->excel->getActiveSheet()->setCellValue('G' . $a, $result->product_type);
           $this->excel->getActiveSheet()->setCellValue('H' . $a, $result->size);
           $this->excel->getActiveSheet()->setCellValue('I' . $a, $result->color);
           $this->excel->getActiveSheet()->setCellValue('J' . $a, $result->fabric);
           $this->excel->getActiveSheet()->setCellValue('K' . $a, $result->craft);
           $this->excel->getActiveSheet()->setCellValue('L' . $a, $result->hsn);
           $this->excel->getActiveSheet()->setCellValue('M' . $a, $result->quantity);
           $this->excel->getActiveSheet()->setCellValue('N' . $a, $result->damage_qty);
//           $this->excel->getActiveSheet()->setCellValue('O' . $a, "Rs. " . number_format($result->price, 2));
//           $this->excel->getActiveSheet()->setCellValue('P' . $a, "Rs. " . number_format(($result->price * $result->available_qty), 2));
//           $this->excel->getActiveSheet()->setCellValue('N' . $a, $result->gst_percent);
           $this->excel->getActiveSheet()->setCellValue('O' . $a,  number_format(($result->quantity * $result->product_mrp) * ($result->gst_percent/100), 2));
           $this->excel->getActiveSheet()->setCellValue('P' . $a, $result->product_mrp);
           $this->excel->getActiveSheet()->setCellValue('Q' . $a, "Rs. " . number_format(($result->product_mrp * $result->quantity), 2));
//           $this->excel->getActiveSheet()->setCellValue('U' . $a, $result->barcode_number);
           $this->excel->getActiveSheet()->setCellValue('R' . $a, implode(" ", $arrTime));

           $sr++;
           $a++;
           $total_quantity +=   $result->quantity;
           $total_available_qty +=  $result->available_qty;
           $total_price +=  $result->price;
           $total_cost_total += ($result->price * $result->available_qty);
           $total_gst += ($result->quantity * $result->product_mrp) * ($result->gst_percent/100);
           $total_product_mrp += $result->product_mrp;
           $total_sp_total += ($result->product_mrp * $result->available_qty);
       }
       $this->excel->getActiveSheet()->setCellValue('M' . $a, $total_quantity);
//       $this->excel->getActiveSheet()->setCellValue('N' . $a, $total_available_qty);
//       $this->excel->getActiveSheet()->setCellValue('O' . $a, "Rs. " . number_format($total_price, 2));
//       $this->excel->getActiveSheet()->setCellValue('P' . $a, "Rs. " . number_format($total_cost_total, 2));
       $this->excel->getActiveSheet()->setCellValue('O' . $a, "Rs. " . number_format($total_gst, 2));
       $this->excel->getActiveSheet()->setCellValue('P' . $a, "Rs. " . number_format($total_product_mrp, 2));
//       $this->excel->getActiveSheet()->setCellValue('P' . $a, "Rs. " . number_format($total_sp_total, 2));

       $this->excel->getActiveSheet()->getStyle('M' . $a)->getFont()->setBold(true);
       $this->excel->getActiveSheet()->getStyle('N' . $a)->getFont()->setBold(true);
       $this->excel->getActiveSheet()->getStyle('O' . $a)->getFont()->setBold(true);
       $this->excel->getActiveSheet()->getStyle('P' . $a)->getFont()->setBold(true);
       $this->excel->getActiveSheet()->getStyle('R' . $a)->getFont()->setBold(true);
       $this->excel->getActiveSheet()->getStyle('S' . $a)->getFont()->setBold(true);
       $this->excel->getActiveSheet()->getStyle('T' . $a)->getFont()->setBold(true);

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
       //$this->excel->getActiveSheet()->mergeCells('A1:H1');
       $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

       $filename = '' . $FileTitle . '.xlsx'; //save our workbook as this file name
       header('Content-Type: application/vnd.ms-excel'); //mime type
       header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
       header('Cache-Control: max-age=0'); //no cache
       ob_clean();

       //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
       //if you want to save it as .XLSX Excel 2007 format
       $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
       //force user to download the Excel file without writing it to server's HD
       $objWriter->save('php://output');
   }
   /* ----- Export functionality end ----- */

  public function getGST()
  {
    $select = $this->Crud_model->GetData("mst_gst", "", "category_id='" . $_POST['category_id'] . "' and status='Active'", "", "", "", "1");
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
