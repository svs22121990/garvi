<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
class Credit_Sales_Report extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Credit_sales_model');
    $this->load->library('upload');
    $this->load->library('image_lib');
    $this->image_lib->clear();
    $this->load->helper(array('form', 'url', 'html'));
    $this->load->database();
  }

  public function search()
  {
    if ($this->input->post()) {
        $date = $this->input->post('daterange');
        $date = str_replace("-", "_", $date);
        $date = str_replace("/", "-", $date);
        $date = str_replace(" ", "", $date);

        //$newDate = date("Y-m-d", strtotime($date));
        $strUrl = site_url('Credit_Sales_Report/ajax_manage_page/' . $date);
        $this->common_view($strUrl, $date);
      } else {
        return redirect('Sales_Summary');
      }
  }
  public function index()
  {
    $this->common_view(site_url('Credit_Sales_Report/ajax_manage_page'));
  }
  public function common_view($action, $date = 0)
  {
    // print_r($_SESSION[SESSION_NAME]);exit;
    $import = '';
    $add = '';
    if (!empty($_SESSION[SESSION_NAME]['getMenus'])) {
        foreach ($_SESSION[SESSION_NAME]['getMenus'] as $row) {
            foreach ($row as $menu) {
                if ($menu->value == 'Credit_Sales_Report') {

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
	                <li class='active'>Credit Sales Report</li>
	                </ul>";

        $importaction = ''; // '<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
        $export = ''; // '<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>'; 
        $download = ''; // '<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 

        $salesTypes = $this->Crud_model->GetData("sales_type", "", "status='Active'");
        $paymentModes = $this->Crud_model->GetData("payment_types", "", "status='Active'");

        $data = array(
          'dateinfo' => $date,
          'breadcrumbs' => $breadcrumbs,
          'actioncolumn' => '5',
          'ajax_manage_page' => $action,
          'heading' => 'Credit Sales Report',
          'addPermission' => $add,
          'importaction' => $importaction,
          'download' => $download,
          'import' => $import,
          'export' => $export,
          'exportPermission' => '', //$exportbutton,
          'salesTypes' => $salesTypes,
          'paymentModes' => $paymentModes,
        );

        $this->load->view('credit_sale/list', $data);
      } else {
        redirect('Dashboard');
      }
  }

  public function ajax_manage_page($date = 0)
  {
    $con = "i.id<>''";

    $Data = $this->Credit_sales_model->get_datatables($con, $date);
    $settlements = $this->Credit_sales_model->get_settlements($con, $date);

    foreach ($_SESSION[SESSION_NAME]['getMenus'] as $row) {
        foreach ($row as $menu) {
            if ($menu->value == 'Credit_Sales_Report') {

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
    $arrId = array();
    foreach ($Data as $row) {
        if (in_array($row->id, $arrId)) {
            $sumAmount = '';
            $invoice_no = '';
            $sr_no = '';
          } else {
            $no++;
            $arrId[] = $row->id;
            $sumAmount = number_format($row->sumAmount, 2);
            $invoice_no = $row->invoice_no;
            $sr_no = $no;
          }
        $btn = '<a href=' . site_url("Credit_Sales_Report/clear/" . $row->id) . ' title="Clear Payment" class="btn btn-primary btn-circle btn-sm"><i class="fa fa-check"></i></a>';
        $data[] = array(
          'no' => $sr_no,
          'invoice_no' => $invoice_no,
          'date_of_invoice' => date('d-m-Y', strtotime($row->date_of_invoice)),
          'invoice_sales_type' => $row->salesType,
          'asset_name' => $row->asset_name,
          'paymentMode' => $row->paymentMode,
          'quantity' => $row->quantity,
          'net_amount' => number_format($row->net_amount, 2),
          'sumAmount' => $sumAmount,
          'productType' => $row->product_type,
          'btn' => $btn
          //'' => number_format($row->sumAmount,2),
        );
      }

    $settlementData = array();
    $no = 0;
    $arrId = array();
    foreach ($settlements as $row) {
      if (in_array($row->id, $arrId)) {
          $sumAmount = '';
          $invoice_no = '';
          $sr_no = '';
        } else {
          $no++;
          $arrId[] = $row->id;
          $sumAmount = number_format($row->sumAmount, 2);
          $invoice_no = $row->invoice_no;
          $sr_no = $no;
        }
      $settlementData[] = array(
        'no' => $sr_no,
        'invoice_no' => $invoice_no,
        'date_of_invoice' => date('d-m-Y', strtotime($row->date_of_invoice)),
        // 'asset_name' => $row->asset_name,
        'paymentMode' => $row->paymentMode,
        // 'quantity' => $row->quantity,
        // 'net_amount' => number_format($row->net_amount,2),
        'sumAmount' => $sumAmount,
        'settlement_date' => $row->settlement_date,
      );
    }
    $output = array(
      "data" => $data,
      "settlementData" => $settlementData,
    );

    echo json_encode($output);
  }

  public function clear($id = null)
  {
    if ($id != null) {
      $condition = array('id' => $id);
      $arrDispatch = $this->Crud_model->GetData('invoice', '', $condition, '', '', '', 1);

      if ($arrDispatch) {
        $data = array(
          'invoice_id' => $arrDispatch->id,
          'date' => date('Y-m-d H:i:s')
        );

        $status = array(
          'payment_status' => 'clear'
        );

        $this->Crud_model->SaveData('invoice_settlements', $data);
        $this->Crud_model->SaveData('invoice', $status, $condition);;
      }
    }

    redirect('Credit_Sales_Report');
  }

  //============================export pdf ============================
  public function sales_pdf($date = 0)
  {
    $con = "i.id<>''";

    $data['results'] = $this->Credit_sales_model->get_datatables($con, $date);
    $html = $this->load->view('credit_sale/pdf_credit_summary', $data, true);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Credit_Summary_Report', 'I');
  }

  public function settlement_pdf($date = 0)
  {
    $con = "i.id<>''";

    $data['results'] = $this->Credit_sales_model->get_settlements($con, $date);
    $html = $this->load->view('credit_sale/pdf_settlement_summary', $data, true);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Settlement_Summary_Report', 'I');
  }

  public function listpdf($date = 0)
  {
    $con = "i.id<>''";

    $data['results'] = $this->Sales_Summary_model->get_datatables($con, $date);

    $html = $this->load->view('sales_summary/pdf_summary', $data, true);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Sales_Summary_Report', 'I');
  }
  //============================/export pdf ============================


  /* ----- Export functionality start ----- */
  public function export_sales_summary($date = 0)
  {
    $con = "i.id<>''";

    $results = $this->Credit_sales_model->get_datatables($con, $date);
    $FileTitle = 'Credit Sales Report';

    $this->load->library('excel');
    //activate worksheet number 1
    $this->excel->setActiveSheetIndex(0);
    //name the worksheet
    $this->excel->getActiveSheet()->setTitle('Report Sheet');
    //set cell A1 content with some text

    $this->excel->getActiveSheet()->setCellValue('A1', 'Gujarat State Handloom & Handicraft Development Corp. Ltd.');
    $this->excel->getActiveSheet()->setCellValue('A2', $_SESSION[SESSION_NAME]['address']);
    $this->excel->getActiveSheet()->setCellValue('A3', $_SESSION[SESSION_NAME]['gst_number']);
    $this->excel->getActiveSheet()->setCellValue('A4', 'Credit Sales Details');

    $this->excel->getActiveSheet()->setCellValue('A5', 'Sr. No');
    $this->excel->getActiveSheet()->setCellValue('B5', 'Invoice No.');
    $this->excel->getActiveSheet()->setCellValue('C5', 'Date');
    $this->excel->getActiveSheet()->setCellValue('D5', 'Product Name');
    $this->excel->getActiveSheet()->setCellValue('E5', 'Payment Mode');
    $this->excel->getActiveSheet()->setCellValue('F5', 'Quantity');
    $this->excel->getActiveSheet()->setCellValue('G5', 'Product Type 2');
    $this->excel->getActiveSheet()->setCellValue('H5', 'Sub-Total');
    $this->excel->getActiveSheet()->setCellValue('I5', 'Total Amount');

    $a = '6';
    $sr = 1;
    $total_sum = 0;
    $qty = 0;
    $net_amount = 0;
    $arrId = array();
    foreach ($results as $result) {
        if (in_array($result->id, $arrId)) {
            $sums = '';
            $invoice = '';
            $no = '';
          } else {
            $arrId[] = $result->id;
            $sums = "Rs. " . number_format($result->sumAmount, 2);
            $total_sum += $result->sumAmount;
            $invoice = $result->invoice_no;
            $no = $sr++;
          }

        $this->excel->getActiveSheet()->setCellValue('A' . $a, $no);
        $this->excel->getActiveSheet()->setCellValue('B' . $a, $invoice);
        $this->excel->getActiveSheet()->setCellValue('C' . $a, date('d-m-Y', strtotime($result->date_of_invoice)));
        $this->excel->getActiveSheet()->setCellValue('D' . $a, $result->asset_name);
        $this->excel->getActiveSheet()->setCellValue('E' . $a, $result->paymentMode);
        $this->excel->getActiveSheet()->setCellValue('F' . $a, $result->quantity);
        $this->excel->getActiveSheet()->setCellValue('G' . $a, $result->product_type);
        $this->excel->getActiveSheet()->setCellValue('H' . $a, "Rs. " . number_format($result->net_amount, 2));

        $this->excel->getActiveSheet()->setCellValue('I' . $a, $sums);
        $a++;
        $qty += $result->quantity;
        $net_amount += $result->net_amount;
      }
    $this->excel->getActiveSheet()->setCellValue('F' . $a, $qty);
    $this->excel->getActiveSheet()->setCellValue('H' . $a, "Rs. " . number_format($net_amount, 2));
    $this->excel->getActiveSheet()->setCellValue('I' . $a, "Rs. " . number_format($total_sum, 2));

    $this->excel->getActiveSheet()->getStyle('F' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('H' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('I' . $a)->getFont()->setBold(true);

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

  public function export_settlement_summary($date = 0)
  {
    $con = "i.id<>''";

    $results = $this->Credit_sales_model->get_settlements($con, $date);
    $FileTitle = 'Credit Settlement Report';

    $this->load->library('excel');
    //activate worksheet number 1
    $this->excel->setActiveSheetIndex(0);
    //name the worksheet
    $this->excel->getActiveSheet()->setTitle('Report Sheet');
    //set cell A1 content with some text
    $this->excel->getActiveSheet()->setCellValue('A1', 'Credit Settlement Details ');

    $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
    $this->excel->getActiveSheet()->setCellValue('B3', 'Invoice No.');
    $this->excel->getActiveSheet()->setCellValue('C3', 'Date');
    $this->excel->getActiveSheet()->setCellValue('D3', 'Settlement Date');
    $this->excel->getActiveSheet()->setCellValue('E3', 'Payment Mode');
    $this->excel->getActiveSheet()->setCellValue('F3', 'Total Amount');

    $a = '4';
    $sr = 1;
    $total_sum = 0;
    $qty = 0;
    $net_amount = 0;
    $arrId = array();
    foreach ($results as $result) {
        if (in_array($result->id, $arrId)) {
            $sums = '';
            $invoice = '';
            $no = '';
          } else {
            $arrId[] = $result->id;
            $sums = "Rs. " . number_format($result->sumAmount, 2);
            $total_sum += $result->sumAmount;
            $invoice = $result->invoice_no;
            $no = $sr++;
          }

        $this->excel->getActiveSheet()->setCellValue('A' . $a, $no);
        $this->excel->getActiveSheet()->setCellValue('B' . $a, $invoice);
        $this->excel->getActiveSheet()->setCellValue('C' . $a, date('d-m-Y', strtotime($result->date_of_invoice)));
        $this->excel->getActiveSheet()->setCellValue('D' . $a, date('d-m-Y', strtotime($result->settlement_date)));
        $this->excel->getActiveSheet()->setCellValue('E' . $a, $result->paymentMode);
        $this->excel->getActiveSheet()->setCellValue('F' . $a, "Rs. " . number_format($result->sumAmount, 2));

        $a++;
        $qty += $result->quantity;
        $net_amount += $result->net_amount;
      }
    $this->excel->getActiveSheet()->setCellValue('F' . $a, "Rs. " . number_format($total_sum, 2));

    $this->excel->getActiveSheet()->getStyle('F' . $a)->getFont()->setBold(true);

    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);

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
