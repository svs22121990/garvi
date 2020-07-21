<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
class Bank_Reconcillation extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Bank_Reconcillation_model');
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

      //$newDate = date("Y-m-d", strtotime($date));
      $strUrl = site_url('Bank_Reconcillation/ajax_manage_page/' . $date . '/'. $type . '/'. $type2);
      $this->common_view($strUrl, $date, $type, $type2);
    } else {
      return redirect('Bank_Reconcillation');
    }
  }
  public function index()
  {

    $this->common_view(site_url('Bank_Reconcillation/ajax_manage_page'));
  }
  public function common_view($action, $date = 0, $type=0, $type2=0)
  {
    // print_r($_SESSION[SESSION_NAME]);exit;
    $import = '';
    $add = '';
    if (!empty($_SESSION[SESSION_NAME]['getMenus'])) {
      foreach ($_SESSION[SESSION_NAME]['getMenus'] as $row) {
        foreach ($row as $menu) {
          if ($menu->value == 'Bank_Reconcillation') {

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
	                <li class='active'>Bank Summary</li>
	                </ul>";

      $importaction = ''; //'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
      $export = '<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>';
      $download = ''; //'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 
      $types = $this->Crud_model->GetData('mst_asset_types', "", "status='Active' and is_delete='No'", 'type');
      $product_types =  $this->Crud_model->GetData('product_type', "", "status='Active'");
      if($date != 0)
      {
          $date = str_replace("-", "/", $date);
          $date = str_replace("_", " - ", $date);
      } else {
          $date = 0;
      }
      $data = array('selected_date' => $date,'selected_type' => $type, 'selected_type2' => $type2, 'types' => $types, 'product_types' => $product_types, 'breadcrumbs' => $breadcrumbs, 'actioncolumn' => '17', 'ajax_manage_page' => $action, 'heading' => 'Bank Summary', 'addPermission' => $add, 'importaction' => $importaction, 'download' => $download, 'import' => $import, 'export' => $export, 'exportPermission' => $exportbutton);

      $this->load->view('bank_reconcillation/list', $data);
    } else {
      redirect('Dashboard');
    }
  }

  public function ajax_manage_page($date = 0, $type = 0, $type2 = 0)
  {
    $con = "i.id<>''";
    if($type != 0)
      $con .= "and a.asset_type_id ='". $type . "'";
    if($type2 != 0)
      $con .= "and a.product_type_id ='". $type2 . "'";

    $Data = $this->Bank_Reconcillation_model->get_datatables($con, $date);
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
        if ($menu->value == 'Bank_Reconcillation') {

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

      if (in_array($row->iid, $arrId)) {
        //$sumAmount = '';
        $invoice_no = '';
        $sr_no = '';
        $total_amount = '';
        $amount_deposited_in_bank = '';
        $gst_on_bank_commission = '';
        $bank_commission = '';
        $date_of_deposit = '';
        $type_of_deposit = '';
      } else {
        $no++;
        $arrId[] = $row->iid;
        //$sumAmount = "Rs ".number_format($row->sumAmount,2);
        $invoice_no = $row->invoice_no;
        $amount_deposited_in_bank = number_format($row->amount_deposited_in_bank, 2);
        $gst_on_bank_commission = $row->gst_on_bank_commission;
        $bank_commission = $row->bank_commission;
        $date_of_deposit = $row->date_of_deposit;
        $type_of_deposit = $row->type_of_deposit;

        $sr_no = $no;
        $this->db->where('invoice_id', $row->iid);
        $query = $this->db->get('invoice_details');
        $result = $query->result();
        $net_amount = 0;
        foreach ($result as $r) {
          $net_amount += $r->net_amount;
        }
        $total_amount = number_format($net_amount, 2);
      }

      $btn = '';
      if (!empty($view)) {
        $btn = '<a href=' . site_url("Bank_Reconcillation/view/" . $row->iid) . ' data-toggle="modal" title="View" class="btn btn-primary btn-circle btn-sm"><i class="ace-icon fa fa-eye"></i></a>';
      }
      $btn .= '<a href=' . site_url("Bank_Reconcillation/add/" . $row->iid) . ' data-toggle="modal" title="Add" class="btn btn-success btn-circle btn-sm"><i class="ace-icon fa fa-plus"></i></a>';

      $data[] = array(
        'no' => $sr_no,
        'invoice_no' => $invoice_no,
        'salesType' => $row->salesType,
        'paymentMode' => $row->paymentMode,
        'date_of_invoice' => date('d-m-Y', strtotime($row->date_of_invoice)),
        'asset_name' => $row->asset_name,
        // 'asset_name' => $row->asset_name,
        // 'asset_name' => $row->asset_name,
        'productType'=> $row->productType,
        'assetType'=> $row->assetType,
        'invoice_quantity' => $row->invoice_quantity,
        'rate_per_item' => number_format($row->rate_per_item, 2),
        'total_amt' => number_format($row->invoice_quantity * $row->rate_per_item, 2),
        'discount_1' => (($row->rate_per_item * $row->invoice_quantity * $row->discount_1) / 100),
        'total_discount' => (($row->rate_per_item * $row->invoice_quantity * $row->discount_3 ) / 100),
        'taxable' => number_format($row->taxable, 2),
        'gst_rate' => $row->gst_rate,
        'gst_amount' => number_format($row->gst_amount, 2),
        'cgst_rate' => $row->cgst_rate,
        'cgst_amount' => number_format($row->cgst_amount, 2),
        'sgst_rate' => $row->sgst_rate,
        'sgst_amount' => number_format($row->sgst_amount, 2),
        'igst_rate' => $row->igst_rate,
        'igst_amount' => number_format($row->igst_amount, 2),
        'adjustment_plus' => $row->adjustment_plus,
        'adjustment_minus'  => $row->adjustment_minus,
        'net_amount' => number_format($row->net_amount, 2),
        'shipping_charges' => $row->shipping_charges,
        'net_amount2' => number_format($row->net_amount, 2),
        'total_amount' => $total_amount,
        'amount_deposited_in_bank' => $amount_deposited_in_bank,
        'type_of_deposit' => $type_of_deposit,
        'gst_on_bank_commission' => $gst_on_bank_commission,
        'bank_commission' => $bank_commission,
        'date_of_deposit' => $date_of_deposit,
        'btn' => $btn,
      );
    }

    $output = array(
      "data" => $data,
    );

    echo json_encode($output);
  }

  //===================================print pdf===========================
  public function printpdf($date = 0)
  {
    $this->db->select('i.*,i.id as iid,ide.id as id,ide.rate_per_item as rate_per_item,ide.total as total, ide.discount_1 as discount_1, ide.discount_2 as discount_2, ide.discount_3 as discount_3, ide.taxable as taxable, ide.adjustment_plus as adjustment_plus, ide.adjustment_minus as adjustment_minus,  ide.quantity as invoice_quantity, ide.amount_after_gst as amount_after_gst,  ide.shipping_charges as shipping_charges, ide.net_amount as net_amount,  a.asset_name as asset_name,a.hsn as hsn,ide.cgst_rate,ide.cgst_amount,ide.sgst_rate,ide.igst_rate,ide.sgst_amount,ide.igst_amount,b.*,ide.gst_rate,ide.gst_amount');
    $this->db->from("invoice i");
    $this->db->join("invoice_details ide", "i.id = ide.invoice_id", "left");
    $this->db->join("bank_reconcillation b", "b.invoice_id = i.id", "left");

    $this->db->join("assets a", "a.id = ide.product_id", "left");
    // $this->db->where($con);
    $this->db->where('i.created_by', $_SESSION[SESSION_NAME]['id']);
    if ($date != 0) {
      $dates = explode("_", $date);
      $date1 = date("Y-m-d", strtotime($dates[0]));
      $date2 = date("Y-m-d", strtotime($dates[1]));
      $this->db->where('i.date_of_invoice >=', $date1);
      $this->db->where('i.date_of_invoice <=', $date2);
    }
    $query = $this->db->get();
    $data['results'] = $query->result();
    //$con="i.id<>''";

    //$data['results'] = $this->Bank_Reconcillation_model->get_datatables($con);
    $html = $this->load->view('bank_reconcillation/pdf_bank_list', $data, true);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Bank_Summary', 'I');
  }
  //===================================/print pdf==========================

  /* ----- Export functionality start ----- */
  public function export1($date = 0, $type = 0, $type2 = 0)
  {
    $this->db->select('
    i.*,
    i.id as iid,
    ide.id as id,
    (select label from sales_type where id=i.invoice_sales_type) as salesType,
    (select type from payment_types where id=i.payment_mode) as paymentMode,
    ide.rate_per_item as rate_per_item,
    ide.total as total,
    ide.discount_1 as discount_1,
    ide.discount_2 as discount_2,
    ide.discount_3 as discount_3,
    ide.taxable as taxable,
    ide.adjustment_plus as adjustment_plus,
    ide.adjustment_minus as adjustment_minus,
    ide.quantity as invoice_quantity,
    ide.amount_after_gst as amount_after_gst,
    ide.shipping_charges as shipping_charges, 
    ide.net_amount as net_amount,  
    a.asset_name as asset_name,
    a.hsn as hsn,
    (select label from product_type where id=a.product_type_id) as productType,
    (select type from mst_asset_types where id=a.asset_type_id) as assetType,
    ide.cgst_rate,
    ide.cgst_amount,
    ide.sgst_rate,
    ide.igst_rate,
    ide.sgst_amount,
    ide.igst_amount,
    b.*,
    ide.gst_rate,
    ide.gst_amount');
    $this->db->from("invoice i");
    $this->db->join("invoice_details ide", "i.id = ide.invoice_id", "left");
    $this->db->join("bank_reconcillation b", "b.invoice_id = i.id", "left");
    $this->db->join("assets a", "a.id = ide.product_id", "left");
    $this->db->join("mst_asset_types mat","mat.id = a.asset_type_id","left");
        $this->db->join("product_type prdt","prdt.id = a.product_type_id","left");
    // $this->db->where($con);
    $this->db->where('i.created_by', $_SESSION[SESSION_NAME]['id']);
    if ($date != 0) {
      $dates = explode("_", $date);
      $date1 = date("Y-m-d", strtotime($dates[0]));
      $date2 = date("Y-m-d", strtotime($dates[1]));
      $this->db->where('i.date_of_invoice >=', $date1);
      $this->db->where('i.date_of_invoice <=', $date2);
    }
    $query = $this->db->get();
    $results = $query->result();
    /*$con = "i.id<>0";

        $results = $this->Bank_Reconcillation_model->ExportCSV($con);*/
    $FileTitle = 'Bank Reconcillation Report';

    $this->load->library('excel');
    //activate worksheet number 1
    $this->excel->setActiveSheetIndex(0);
    //name the worksheet
    $this->excel->getActiveSheet()->setTitle('Report Sheet');
    //set cell A1 content with some text
    $this->excel->getActiveSheet()->setCellValue('A1', 'Gujarat State Handloom & Handicraft Development Corp. Ltd.');
    $this->excel->getActiveSheet()->setCellValue('A2', $_SESSION[SESSION_NAME]['address']);
    $this->excel->getActiveSheet()->setCellValue('A3', $_SESSION[SESSION_NAME]['gst_number']);
    $this->excel->getActiveSheet()->setCellValue('A4', 'Bank Reconcillation Details');

    $this->excel->getActiveSheet()->setCellValue('A5', 'Sr. No');
    $this->excel->getActiveSheet()->setCellValue('B5', 'Invoice No');
    $this->excel->getActiveSheet()->setCellValue('C5', 'Invoice Date');
    $this->excel->getActiveSheet()->setCellValue('D5', 'Product Name');
    $this->excel->getActiveSheet()->setCellValue('E5', 'TYPE 1');
    $this->excel->getActiveSheet()->setCellValue('F5', 'TYPE 2');
    $this->excel->getActiveSheet()->setCellValue('G5', 'Quantity');
    $this->excel->getActiveSheet()->setCellValue('H5', 'Selling Price');
    $this->excel->getActiveSheet()->setCellValue('I5', 'Sales Type');
    $this->excel->getActiveSheet()->setCellValue('J5', 'Payment Mode');
    $this->excel->getActiveSheet()->setCellValue('K5', 'Rebate 5%');
    $this->excel->getActiveSheet()->setCellValue('L5', 'Corporation Discount');
    $this->excel->getActiveSheet()->setCellValue('M5', 'Taxable Amount ');

    $this->excel->getActiveSheet()->setCellValue('N5', 'GST');
    $this->excel->getActiveSheet()->setCellValue('O5', 'GST Amount');

    $this->excel->getActiveSheet()->setCellValue('P5', 'CGST Rate');
    $this->excel->getActiveSheet()->setCellValue('Q5', 'CGST Amount');
    $this->excel->getActiveSheet()->setCellValue('R5', 'SGST Rate');
    $this->excel->getActiveSheet()->setCellValue('S5', 'SGST Amount');
    $this->excel->getActiveSheet()->setCellValue('T5', 'IGST Rate');
    $this->excel->getActiveSheet()->setCellValue('U5', 'IGST Amount');

    $this->excel->getActiveSheet()->setCellValue('V5', 'Adj +');
    $this->excel->getActiveSheet()->setCellValue('W5', 'Adj -');
    $this->excel->getActiveSheet()->setCellValue('X5', 'Amount after GST');
    $this->excel->getActiveSheet()->setCellValue('Y5', 'COD /Shipping');
    $this->excel->getActiveSheet()->setCellValue('Z5', 'Sub total');

    $this->excel->getActiveSheet()->setCellValue('AA5', 'Net Amount');
    $this->excel->getActiveSheet()->setCellValue('AB5', 'Amount Deposited in Bank ');
    $this->excel->getActiveSheet()->setCellValue('AC5', 'Type of Deposit');
    $this->excel->getActiveSheet()->setCellValue('AD5', 'GST on Bank Commission');
    $this->excel->getActiveSheet()->setCellValue('AE5', 'Bank Commission');
    $this->excel->getActiveSheet()->setCellValue('AF5', 'Date of Deposit');
    $a = '6';
    $no = 1;
    //print_r($results);exit;
    $arrId = array();
    $invoice_quantity = 0;
    $rate_per_item = 0;
    $taxable = 0;
    $gst_amount = 0;
    $cgst_amount = 0;
    $sgst_amount = 0;
    $igst_amount = 0;
    $net_amount = 0;
    $all_amount = 0;
    $total_ad_plus = 0;
    $total_ad_minus = 0;
    $total_rebate = 0;
    $total_crop_discount = 0; 
    $amount_deposited_in_bank2 = 0;
    foreach ($results as $result) {
      if (in_array($result->iid, $arrId)) {
        //$sumAmount = '';
        $sr_no = '';
        $invoice_no = '';
        $total_amount = '';
        $amount_deposited_in_bank = '';
        $gst_on_bank_commission = '';
        $bank_commission = '';
        $date_of_deposit = '';
        $type_of_deposit = '';
      } else {
        $no++;
        $arrId[] = $result->iid;
        //$sumAmount = "Rs ".number_format($row->sumAmount,2);
        $invoice_no = $result->invoice_no;
        $amount_deposited_in_bank = $result->amount_deposited_in_bank;
        $gst_on_bank_commission = $result->gst_on_bank_commission;
        $bank_commission = $result->bank_commission;
        $date_of_deposit = $result->date_of_deposit;
        $type_of_deposit = $result->type_of_deposit;
        $sr_no = $no;
        $this->db->where('invoice_id', $result->iid);
        $query = $this->db->get('invoice_details');
        $result2 = $query->result();
        $net_amount_ = 0;
        foreach ($result2 as $r) {
          $net_amount_ += $r->net_amount;
        }
        $all_amount += $net_amount_;
        $total_amount = "Rs. " . number_format($net_amount_, 2);
      }
      /*$total = $result->sum + $result->transport ;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;*/

      $this->excel->getActiveSheet()->setCellValue('A' . $a, $sr_no);
      $this->excel->getActiveSheet()->setCellValue('B' . $a, $invoice_no);
      $this->excel->getActiveSheet()->setCellValue('C' . $a, date('d-m-Y', strtotime($result->date_of_invoice)));
      $this->excel->getActiveSheet()->setCellValue('D' . $a, $result->asset_name);
      $this->excel->getActiveSheet()->setCellValue('E' . $a, $result->productType);
      $this->excel->getActiveSheet()->setCellValue('F' . $a, $result->assetType);
      $this->excel->getActiveSheet()->setCellValue('G' . $a, $result->invoice_quantity);

      $this->excel->getActiveSheet()->setCellValue('H' . $a, "Rs. " . number_format($result->rate_per_item, 2));
      $this->excel->getActiveSheet()->setCellValue('I' . $a, $result->salesType);

      $this->excel->getActiveSheet()->setCellValue('J' . $a, $result->paymentMode);
      $this->excel->getActiveSheet()->setCellValue('K' . $a, "Rs. " . (($result->rate_per_item * $result->invoice_quantity * $result->discount_1) / 100));
      $this->excel->getActiveSheet()->setCellValue('L' . $a, "Rs. " . (($result->rate_per_item * $result->invoice_quantity * $result->discount_3) / 100 ));
      $this->excel->getActiveSheet()->setCellValue('M' . $a, "Rs. " . number_format($result->taxable, 2));

      $this->excel->getActiveSheet()->setCellValue('N' . $a, $result->gst_rate);
      $this->excel->getActiveSheet()->setCellValue('O' . $a, "Rs. " . number_format($result->gst_amount, 2));

      $this->excel->getActiveSheet()->setCellValue('P' . $a, $result->cgst_rate);
      $this->excel->getActiveSheet()->setCellValue('Q' . $a, "Rs. " . number_format($result->cgst_amount, 2));

      $this->excel->getActiveSheet()->setCellValue('R' . $a, $result->sgst_rate);
      $this->excel->getActiveSheet()->setCellValue('S' . $a, "Rs " . number_format($result->sgst_amount, 2));

      $this->excel->getActiveSheet()->setCellValue('T' . $a, $result->igst_rate);
      $this->excel->getActiveSheet()->setCellValue('U' . $a, "Rs " . number_format($result->igst_amount, 2));



      $this->excel->getActiveSheet()->setCellValue('V' . $a, $result->adjustment_plus);
      $this->excel->getActiveSheet()->setCellValue('W' . $a, $result->adjustment_minus);
      $this->excel->getActiveSheet()->setCellValue('X' . $a, "Rs " . number_format($result->net_amount, 2));


      $this->excel->getActiveSheet()->setCellValue('Y' . $a, $result->shipping_charges, 2);

      $this->excel->getActiveSheet()->setCellValue('Z' . $a, "Rs. " . number_format($result->net_amount, 2));
      $this->excel->getActiveSheet()->setCellValue('AA' . $a, $total_amount);
      if ($amount_deposited_in_bank) {
        $this->excel->getActiveSheet()->setCellValue('AB' . $a, "Rs. " . number_format($amount_deposited_in_bank, 2));
      }
      $this->excel->getActiveSheet()->setCellValue('AC' . $a, $type_of_deposit);
      $this->excel->getActiveSheet()->setCellValue('AD' . $a, $gst_on_bank_commission);
      $this->excel->getActiveSheet()->setCellValue('AE' . $a, $bank_commission);

      $this->excel->getActiveSheet()->setCellValue('AF' . $a, $date_of_deposit);


      $sr++;
      $a++;
      $invoice_quantity += $result->invoice_quantity;
      $rate_per_item += $result->rate_per_item;
      $taxable += $result->taxable;
      $gst_amount += $result->gst_amount;
      $cgst_amount += $result->cgst_amount;
      $sgst_amount += $result->sgst_amount;
      $igst_amount += $result->igst_amount;
      $net_amount += $result->net_amount;
      $total_ad_plus += $result->adjustment_plus;
      $total_ad_minus += $result->adjustment_minus;
      $total_rebate += (($result->rate_per_item * $result->invoice_quantity * $result->discount_1) / 100);
      $total_crop_discount += (($result->rate_per_item * $result->invoice_quantity * $result->discount_3) / 100);
      //$all_amount += $total_amount;
      $amount_deposited_in_bank2 += $amount_deposited_in_bank;
    }
    $this->excel->getActiveSheet()->setCellValue('G' . $a, $invoice_quantity);
    $this->excel->getActiveSheet()->setCellValue('H' . $a, "Rs. " . number_format($rate_per_item, 2));
    $this->excel->getActiveSheet()->setCellValue('K' . $a, "Rs. " . number_format($total_rebate, 2));
    $this->excel->getActiveSheet()->setCellValue('L' . $a, "Rs. " . number_format($total_crop_discount, 2));
    $this->excel->getActiveSheet()->setCellValue('M' . $a, "Rs. " . number_format($taxable, 2));
    $this->excel->getActiveSheet()->setCellValue('O' . $a, "Rs. " . number_format($gst_amount, 2));

    $this->excel->getActiveSheet()->setCellValue('Q' . $a, "Rs. " . number_format($cgst_amount, 2));
    $this->excel->getActiveSheet()->setCellValue('S' . $a, "Rs. " . number_format($sgst_amount, 2));
    $this->excel->getActiveSheet()->setCellValue('U' . $a, "Rs. " . number_format($igst_amount, 2));
    $this->excel->getActiveSheet()->setCellValue('V' . $a, "Rs " . number_format($total_ad_plus, 2));
    $this->excel->getActiveSheet()->setCellValue('W' . $a, "Rs " . number_format($total_ad_minus, 2));
    $this->excel->getActiveSheet()->setCellValue('X' . $a, "Rs " . number_format($net_amount, 2));
    $this->excel->getActiveSheet()->setCellValue('Z' . $a, "Rs. " . number_format($net_amount, 2));
    $this->excel->getActiveSheet()->setCellValue('AA' . $a, "Rs. " . number_format($all_amount, 2));
    $this->excel->getActiveSheet()->setCellValue('AB' . $a, "Rs. " . number_format($amount_deposited_in_bank2, 2));

    $this->excel->getActiveSheet()->getStyle('F' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('J' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('I' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('E' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('H' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('K' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('M' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('O' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Q' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('S' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('V' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('X' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Y' . $a)->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Z' . $a)->getFont()->setBold(true);




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
    $this->excel->getActiveSheet()->getStyle('W5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('X5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Y5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Z5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('AA5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('AB5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('AC5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('AD5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('AE5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('AF5')->getFont()->setBold(true);
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


  public function add($id)
  {
    $breadcrumbs = "<ul class='breadcrumb'>
          <li>
            <i class='ace-icon fa fa-home home-icon'></i>
            <a href='" . site_url('Dashboard') . "'>Dashboard</a>
          </li>
          <li class=''> 
            <a href='" . site_url('Bank_Reconcillation') . "'>Bank Summary</a>
          </li>
          <li class='active'>Add Bank Summary Details</li>
        </ul>";
    $invoice = $this->Crud_model->GetData("invoice", "", "id='" . $id . "'", "", "", "", "1");
    $invoice_details = $this->Crud_model->GetData("invoice_details", "", "invoice_id='" . $id . "'");

    //print_r($invoice_details);exit;
    $data = array(
      'breadcrumbs' => $breadcrumbs,
      'heading' => 'Bank Summary details',
      'invoice' => $invoice,
      'invoice_details' => $invoice_details,
      'action' => site_url('Bank_Reconcillation/add_action'),
    );

    $this->load->view('bank_reconcillation/form', $data);
  }

  public function add_action()
  {

    //print_r($_POST);exit;
    $data_array = array(
      'type_of_deposit' => $_POST['type_of_deposit'],
      'amount_deposited_in_bank' => $_POST['amount_deposited_in_bank'],
      'gst_on_bank_commission' => $_POST['gst_on_bank_commission'],
      'bank_commission' => $_POST['bank_commission'],
      'date_of_deposit' => date('Y-m-d', strtotime($_POST['date_of_deposit'])),
      'invoice_id' => $_POST['invoice_id'],
      'total_amount' => $_POST['total_amount']
    );
    $iid = (int) $_POST['invoice_id'];
    $query = $this->db
      ->where('invoice_id', $iid)
      ->get('bank_reconcillation');
    $check = $query->result();
    if ($check) {
      $where = array('invoice_id' => $iid);
      $this->Crud_model->SaveData("bank_reconcillation", $data_array, $where);
    } else {
      $this->Crud_model->SaveData("bank_reconcillation", $data_array);
    }


    // $this->Crud_model->SaveData("bank_reconcillation",$data_array);
    redirect(site_url('Bank_Reconcillation'));
  }

  public function view($id)
  {
    $breadcrumbs = "<ul class='breadcrumb'>
          <li>
            <i class='ace-icon fa fa-home home-icon'></i>
            <a href='" . site_url('Dashboard') . "'>Dashboard</a>
          </li>
          <li class=''> 
            <a href='" . site_url('Bank_Reconcillation') . "'>Bank Summary</a>
          </li>
          <li class='active'>View Bank Summary Details</li>
        </ul>";
    $invoice = $this->Crud_model->GetData("invoice", "", "id='" . $id . "'", "", "", "", "1");
    $invoice_details = $this->Crud_model->GetData("invoice_details", "", "invoice_id='" . $id . "'");
    $bank_reconcillation = $this->Crud_model->GetData("bank_reconcillation", "", "invoice_id='" . $id . "'", "", "", "", "1");
    //print_r($invoice_details);exit;
    $data = array(
      'breadcrumbs' => $breadcrumbs,
      'heading' => 'Bank Summary details',
      'invoice' => $invoice,
      'invoice_details' => $invoice_details,
      'bank_reconcillation' => $bank_reconcillation
    );

    $this->load->view('bank_reconcillation/view', $data);
  }

  /* ----- Export functionality start ----- */
  public function export()
  {
    $con = "i.id<>0";

    $results = $this->Bank_Reconcillation_model->ExportCSV($con);
    $FileTitle = 'Bank Reconcillation Report';

    $this->load->library('excel');
    //activate worksheet number 1
    $this->excel->setActiveSheetIndex(0);
    //name the worksheet
    $this->excel->getActiveSheet()->setTitle('Report Sheet');
    //set cell A1 content with some text
    $this->excel->getActiveSheet()->setCellValue('A1', 'Bank Reconcillation Details ');

    $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
    $this->excel->getActiveSheet()->setCellValue('B3', 'Invoice No.');
    $this->excel->getActiveSheet()->setCellValue('C3', 'Invoice Date');
    $this->excel->getActiveSheet()->setCellValue('D3', 'Product Name');
    $this->excel->getActiveSheet()->setCellValue('E3', 'Quantity');
    $this->excel->getActiveSheet()->setCellValue('F3', 'Selling Price');
    $this->excel->getActiveSheet()->setCellValue('G3', 'Sales Type');
    $this->excel->getActiveSheet()->setCellValue('H3', 'Payment Mode');
    $this->excel->getActiveSheet()->setCellValue('I3', 'Rebate 5%');
    $this->excel->getActiveSheet()->setCellValue('J3', 'Corporation Discount');
    $this->excel->getActiveSheet()->setCellValue('K3', 'Taxable Amount');
    $this->excel->getActiveSheet()->setCellValue('L3', 'GST');
    $this->excel->getActiveSheet()->setCellValue('M3', 'CGST Rate');
    $this->excel->getActiveSheet()->setCellValue('N3', 'CGST Amount');
    $this->excel->getActiveSheet()->setCellValue('O3', 'SGST Rate');
    $this->excel->getActiveSheet()->setCellValue('P3', 'SGST Amount');
    $this->excel->getActiveSheet()->setCellValue('Q3', 'IGST Rate');
    $this->excel->getActiveSheet()->setCellValue('R3', 'IGST Amount');
    $this->excel->getActiveSheet()->setCellValue('S3', 'Other Adj +');
    $this->excel->getActiveSheet()->setCellValue('T3', 'Other Adj -');
    $this->excel->getActiveSheet()->setCellValue('U3', 'Amount After GST');
    $this->excel->getActiveSheet()->setCellValue('V3', 'COD/Shipping');
    $this->excel->getActiveSheet()->setCellValue('W3', 'Sub Total');
    $this->excel->getActiveSheet()->setCellValue('X3', 'Net Amount');
    $this->excel->getActiveSheet()->setCellValue('Y3', 'Amount Deposited in Bank');
    $this->excel->getActiveSheet()->setCellValue('Z3', 'GST on Bank Commission');
    $this->excel->getActiveSheet()->setCellValue('AA3', 'Bank Commission');
    $this->excel->getActiveSheet()->setCellValue('AB3', 'Date of Deposit');

    $a = '4';
    $sr = 1;
    //print_r($results);exit;
    foreach ($results as $result) {

      /*$total = $result->sum + $result->transport ;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;*/

      $this->excel->getActiveSheet()->setCellValue('A' . $a, $sr);
      $this->excel->getActiveSheet()->setCellValue('B' . $a, $result->invoice_no);
      $this->excel->getActiveSheet()->setCellValue('C' . $a, date('d-m-Y', strtotime($result->date_of_invoice)));
      $this->excel->getActiveSheet()->setCellValue('D' . $a, $result->asset_name);
      $this->excel->getActiveSheet()->setCellValue('E' . $a, $result->quantity);
      $this->excel->getActiveSheet()->setCellValue('F' . $a, $result->rate_per_item);
      $this->excel->getActiveSheet()->setCellValue('G' . $a, $result->salesType);
      $this->excel->getActiveSheet()->setCellValue('H' . $a, $result->paymentMode);
      $this->excel->getActiveSheet()->setCellValue('I' . $a, $result->discount_1);
      $this->excel->getActiveSheet()->setCellValue('J' . $a, $result->discount_2);
      $this->excel->getActiveSheet()->setCellValue('K' . $a, $result->taxable);
      $this->excel->getActiveSheet()->setCellValue('L' . $a, $result->gst_rate);
      $this->excel->getActiveSheet()->setCellValue('M' . $a, $result->cgst_rate);
      $this->excel->getActiveSheet()->setCellValue('N' . $a, $result->cgst_amount);
      $this->excel->getActiveSheet()->setCellValue('O' . $a, $result->sgst_rate);
      $this->excel->getActiveSheet()->setCellValue('P' . $a, $result->sgst_amount);
      $this->excel->getActiveSheet()->setCellValue('Q' . $a, $result->igst_rate);
      $this->excel->getActiveSheet()->setCellValue('R' . $a, $result->igst_amount);
      $this->excel->getActiveSheet()->setCellValue('S' . $a, $result->adjustment_plus);
      $this->excel->getActiveSheet()->setCellValue('T' . $a, $result->adjustment_minus);
      $this->excel->getActiveSheet()->setCellValue('U' . $a, $result->amount_after_gst);
      $this->excel->getActiveSheet()->setCellValue('V' . $a, $result->shipping_charges);
      $this->excel->getActiveSheet()->setCellValue('W' . $a, '');
      $this->excel->getActiveSheet()->setCellValue('X' . $a, $result->net_amount);
      $this->excel->getActiveSheet()->setCellValue('Y' . $a, $result->amount_deposited_in_bank);
      $this->excel->getActiveSheet()->setCellValue('Z' . $a, $result->gst_on_bank_commission);
      $this->excel->getActiveSheet()->setCellValue('AA' . $a, $result->bank_commission);
      $this->excel->getActiveSheet()->setCellValue('AB' . $a, date('d-m-Y', strtotime($result->date_of_deposit)));
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
    $this->excel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('K3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('L3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('M3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('N3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('O3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('P3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Q3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('R3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('S3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('T3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('U3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('V3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('W3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('X3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Y3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('Z3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('AA3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('AB3')->getFont()->setBold(true);
    //$this->excel->getActiveSheet()->getStyle('R3')->getFont()->setBold(true);
    //$this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
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
