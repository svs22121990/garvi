<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
class Invoice extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Invoice_model');
    $this->load->model('Crud_model');
    $this->load->model('Alldata', 'model');
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
      $strUrl = site_url('Invoice/ajax_manage_page/' . $date);
      $this->common_view($strUrl, $date);
    } else {
      return redirect('Invoice');
    }
  }
  public function index()
  {

    $this->common_view(site_url('Invoice/ajax_manage_page'));
  }

  public function common_view($action, $date = 0)
  {
    // print_r($_SESSION[SESSION_NAME]);exit;
    $import = '';
    $add = '';
    if (!empty($_SESSION[SESSION_NAME]['getMenus'])) {
      foreach ($_SESSION[SESSION_NAME]['getMenus'] as $row) {
        foreach ($row as $menu) {
          if ($menu->value == 'Invoice') {

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
	                <li class='active'>Invoice</li>
	                </ul>";

      $importaction = ''; //'<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
      $export = '<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>';
      $download = ''; //'<a download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 
      $salesTypes = $this->Crud_model->GetData("sales_type", "", "status='Active'");
      $paymentModes = $this->Crud_model->GetData("payment_types", "", "status='Active'");


      $data = array(
        'dateinfo' => $date,
        'breadcrumbs' => $breadcrumbs,
        'actioncolumn' => '7',
        'ajax_manage_page' => $action,
        'heading' => 'Invoice',
        'addPermission' => $add,
        'importaction' => $importaction,
        'download' => $download,
        'import' => $import,
        'export' => $export,
        'exportPermission' => $exportbutton,
        'salesTypes' => $salesTypes,
        'paymentModes' => $paymentModes,
      );

      $this->load->view('invoice/list', $data);
    } else {
      redirect('Dashboard');
    }
  }

  public function ajax_manage_page($date = 0)
  {
    $con = "i.id<>''";


    $Data = $this->Invoice_model->get_datatables($con, $date);
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
        if ($menu->value == 'Invoice') {

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
      $btn = '';
      $btn = '<a href=' . site_url("Invoice/view/" . $row->id) . ' title="Details" class="btn btn-primary btn-circle btn-sm"><i class="fa fa-eye"></i></a>';
      $btn .= '&nbsp;|&nbsp;' . '<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus(' . $row->id . ')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';

      $no++;
      /*$nestedData = array();
        $nestedData[] = $no;                       
        $nestedData[] = $row->invoice_no;                       
        $nestedData[] = $row->gst_number;                       
        $nestedData[] = $row->receiver_bill_name;                       
        $nestedData[] = date('d-m-Y', strtotime($row->date_of_invoice));                       
        //$nestedData[] = $row->serial_no_of_invoice;                                              
        $nestedData[] = "Rs. ".number_format($row->sumAmount,2);                                              
        $nestedData[] = $btn;*/
      //$data[] = $nestedData;
      $data[] = array(
        'no' => $no,
        'invoice_no' => $row->invoice_no,
        'gst_number' => $row->gst_number,
        'receiver_bill_name' => $row->receiver_bill_name,
        'salesType' => $row->salesType,
        'paymentMode' => $row->paymentMode,
        'date_of_invoice' => date('d-m-Y', strtotime($row->date_of_invoice)),
        'sumAmount' => number_format($row->sumAmount, 2),
        'btn' => $btn,
      );
      //$selected = '';
    }

    $output = array(
      // "draw" => $_POST['draw'],
      "recordsTotal" => $this->Invoice_model->count_all($con),
      "recordsFiltered" => $this->Invoice_model->count_filtered($con),
      "data" => $data,
    );

    echo json_encode($output);
  }

  public function view($id)
  {
    $breadcrumbs = "<ul class='breadcrumb'>
          <li>
            <i class='ace-icon fa fa-home home-icon'></i>
            <a href='" . site_url('Dashboard') . "'>Dashboard</a>
          </li>
          <li class=''> 
            <a href='" . site_url('Invoice') . "'>Invoice</a>
          </li>
          <li class='active'>View Invoice Details</li>
        </ul>";
    $invoice = $this->Crud_model->GetData("invoice", "", "id='" . $id . "'", "", "", "", "1");
    $salesType = $this->Crud_model->GetData("sales_type", "", "id='" . $invoice->invoice_sales_type . "'", "", "", "", "1");
    $paymentMode = $this->Crud_model->GetData("payment_types", "", "id='" . $invoice->payment_mode . "'", "", "", "", "1");
    $receiver_state = $this->Crud_model->GetData("mst_states", "", "id='" . $invoice->receiver_state . "'", "", "", "", "1");
    $consignee_state = $this->Crud_model->GetData("mst_states", "", "id='" . $invoice->consignee_state . "'", "", "", "", "1");
    $invoice_details = $this->Crud_model->GetData("invoice_details", "", "invoice_id='" . $id . "'");
    //print_r($receiver_state);exit;
    $data = array(
      'breadcrumbs' => $breadcrumbs,
      'heading' => 'Invoice details',
      'invoice' => $invoice,
      'salesType' => $salesType,
      'paymentMode' => $paymentMode,
      'invoice_details' => $invoice_details,
      'receiver_state' => $receiver_state,
      'consignee_state' => $consignee_state,
      'id' => $id
    );

    $this->load->view('invoice/view', $data);
  }

  public function viewPDF($id)
  {
    $data['invoice'] = $this->Crud_model->GetData("invoice", "", "id='" . $id . "'", "", "", "", "1");
    $data['receiver_state'] = $this->Crud_model->GetData("mst_states", "", "id='" . $data['invoice']->receiver_state . "'", "", "", "", "1");
    $data['consignee_state'] = $this->Crud_model->GetData("mst_states", "", "id='" . $data['invoice']->consignee_state . "'", "", "", "", "1");
    $data['invoice_details'] = $this->Crud_model->GetData("invoice_details", "", "invoice_id='" . $id . "'");
    $data['salesType'] = $this->Crud_model->GetData("sales_type", "", "id='" . $data['invoice']->invoice_sales_type . "'", "", "", "", "1");
    $data['paymentMode'] = $this->Crud_model->GetData("payment_types", "", "id='" . $data['invoice']->payment_mode . "'", "", "", "", "1");

    $this->load->view('invoice/pdfview', $data);

    // $html = $this->load->view('invoice/pdfview', $data, TRUE);
    // $mpdf = new \Mpdf\Mpdf();
    // $mpdf->SetTitle('Invoice - '.$data['invoice']->invoice_no);
    // $mpdf->WriteHTML($html);
    // $mpdf->Output('Invoice - '.$data['invoice']->invoice_no, 'I');
  }

  public function invoicePdf($id)
  {
    //dd($_SESSION[SESSION_NAME]);
    $data['invoice'] = $this->Crud_model->GetData("invoice", "", "id='" . $id . "'", "", "", "", "1");
    $data['receiver_state'] = $this->Crud_model->GetData("mst_states", "", "id='" . $data['invoice']->receiver_state . "'", "", "", "", "1");
    $data['consignee_state'] = $this->Crud_model->GetData("mst_states", "", "id='" . $data['invoice']->consignee_state . "'", "", "", "", "1");
    $data['invoice_details'] = $this->Crud_model->GetData("invoice_details", "", "invoice_id='" . $id . "'");
    $data['salesType'] = $this->Crud_model->GetData("sales_type", "", "id='" . $data['invoice']->invoice_sales_type . "'", "", "", "", "1");
    $data['paymentMode'] = $this->Crud_model->GetData("payment_types", "", "id='" . $data['invoice']->payment_mode . "'", "", "", "", "1");

    $html = $this->load->view('invoice/pdfview', $data, TRUE);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetTitle('Invoice - ' . $data['invoice']->invoice_no);
    $mpdf->WriteHTML($html);
    $mpdf->Output('Invoice - ' . $data['invoice']->invoice_no, 'I');
  }

  public function create()
  {
    /*echo"<pre>";
    print_r($_SESSION['ASSETSTRACKING']);exit;*/
    $breadcrumbs = "<ul class='breadcrumb'>
                <li>
                    <i class='ace-icon fa fa-home home-icon'></i>
                    <a href='" . site_url('Dashboard') . "'>Dashboard</a>
                </li>
                 <li class=''> <a href='" . site_url('Invoice') . "'>Invoice</a></li>
                <li class='active'>Create Invoice</li>
                </ul>";

    //$products = $this->Crud_model->GetData("assets","");
    $products = $this->Invoice_model->getProduct();
    /*echo"<pre>";
    print_r($products);
    exit();*/
    $rebate = $this->Crud_model->GetData("mst_rebate", "", "status='Active'");
    $states = $this->Crud_model->GetData("mst_states", "", "status='Active'");
    $salesTypes = $this->Crud_model->GetData("sales_type", "", "status='Active'");
    $paymentModes = $this->Crud_model->GetData("payment_types", "", "status='Active'");
    $action =  site_url("Invoice/save_invoice");

    $data = array(
      'breadcrumbs' => $breadcrumbs,
      'heading' => 'Create Invoice',
      'button' => 'Create',
      'products' => $products,
      'rebate' => $rebate,
      'action' => $action,
      'states' => $states,
      'salesTypes' => $salesTypes,
      'paymentModes' => $paymentModes,
    );

    $this->load->view('invoice/form', $data);

    //$this->view('invoice/create');
  }

  public function generate_numbers($start, $count, $digits)
  {
    $result = array();
    for ($n = $start; $n < $start + $count; $n++) {

      $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);
    }
    return $result;
  }

  public function checkUid()
  {
    if (isset($_POST['invoice_no'])) {
      $id = $_POST['invoice_no'];
      $result = $this->Crud_model->GetData("invoice", "id", "invoice_no='" . $id . "'", "", "", "1", "1");

      if ($result) {
        return $this->output
          ->set_content_type('application/json')
          ->set_output(
            json_encode([
              'status' => 'error',
              'message' => 'Invoice ID already exists'
            ])
          );
      }

      return $this->output
        ->set_content_type('application/json')
        ->set_output(
          json_encode([
            'status' => 'success'
          ])
        );
    }
  }

  public function save_invoice()
  {

    $con = "status='Inactive'";      
    $this->Crud_model->DeleteData('invoice',$con);
    $con = "status='Inactive'";      
    $this->Crud_model->DeleteData('invoice_details',$con);

    //Adjustment
    $adjustment_explode = $this->input->post('net_amount');
    // $adjustment_explode = 6.4;
    $intAdj = round($adjustment_explode);
    $point = $intAdj - $adjustment_explode;
    //$adjustment = 100 - $point;

    if ($point == 0) {
      $adjustment_plus = 0;
      $adjustment_minus = 0;
    } else if ($point > 0) {
      $adjustment_minus = 0;
      $adjustment_plus = round($point, 2);
    } else if ($point <= 0) {
      $adjustment_minus = round($point, 2);
      $adjustment_plus = 0;
    }
    $netAmount = $intAdj;


    if ($this->input->post()) {
      $product_id = $_POST['asset_name'];
      $qty = $this->input->post('quantity');
      $this->db->select('*');
      $this->db->where('id', $product_id);
      $query = $this->db->get('assets');
      $product = $query->row();
      if ($qty <= $product->quantity) {

        $invoice_sales_type_id = $_POST['invoice_sales_type'];
        $seriesNo = $_SESSION['ASSETSTRACKING']['invoice_serial_number_series'];

        // $number = $this->Crud_model->GetData("invoice", "count(*) as count", "invoice_no like '" . $seriesNo . "%'", "", "id desc", "1", "1");
        
        //$arrWhere = array('id' => $detail[$i]->id);
        //$objProduct = $this->model->gatData('assets', $arrWhere);
        $lastInvoice = $this->Crud_model->GetData("invoice", "invoice_no", "created", "invoice_no like '" . $seriesNo . "%'", "status='Active'","", "created desc", "1", "1");
        $payment_status = $this->Crud_model->GetData("sales_type", "default_status", "id='" . $invoice_sales_type_id . "'", "", "", "1", "1");

        $invoice_no = null;
        $date_of_invoice = null;

        if (isset($_POST['invoice_no'])) {
          $invoice_no = $_POST['invoice_no'];
        }
        // else {
        //   if ($number->count != 0) {
        //     $invoice_no1 = $this->generate_numbers($number->count + 1, '1', '5');
        //   } else {
        //     $invoice_no1 = $this->generate_numbers('1', '1', '5');
        //   }
        //   $invoice_no = $_SESSION['ASSETSTRACKING']['invoice_serial_number_series'] . "-" . $invoice_no1[0];
        // }

        $newIndexStr = "00001";
        if (isset($lastInvoice)) {
          $ivNoTokens = explode("-", $lastInvoice->invoice_no);
          $lastIndex = intval($ivNoTokens[count($ivNoTokens) - 1]);
          $newIndex = $lastIndex + 1;
          $newIndexStr = strval($newIndex);
          if (strlen($newIndexStr) < 5) {
            $newIndexStr = str_pad($newIndexStr, 5, "0", STR_PAD_LEFT);
          }
        }
        $invoice_no = $_SESSION['ASSETSTRACKING']['invoice_serial_number_series']."-".$newIndexStr;
        
        // if($number->count != 0) {
        //             $checkInvoiceNo = $this->Crud_model->GetData("invoice", "id", "invoice_no='".$invoice_no1."'", "","","1","1");
        //             if (isset($checkInvoiceNo)) {
        //                 $invoice_no1 = $this->generate_numbers($number->count+2, '1', '5');
        //                 $invoice_no = $_SESSION['ASSETSTRACKING']['invoice_serial_number_series']."-".$invoice_no1[0];
        //             }
        //         }
        
        $dateLimit = "1999-12-12";
        if (isset($_POST['date_of_invoice']) && date("Y-m-d", strtotime($_POST['date_of_invoice'])) > $dateLimit) {
          $date_of_invoice = date("Y-m-d", strtotime($_POST['date_of_invoice']));
        } else {
          $date_of_invoice = date('Y-m-d');
        }
        //print_r($invoice_no);exit;
        $consignee_state_code = '0';

        $data_array = array(
          // 'invoice_no' => $invoice_no, add invoice no. only when invoice_id2 is absent, i.e. new record
          'gst_number' => $_POST['gst_number'],
          'name' => $_POST['name'],
          'date_of_invoice' => $date_of_invoice,
          'serial_no_of_invoice' => $_POST['serial_no_invoice'],
          'invoice_sales_type' => $invoice_sales_type_id,
          'payment_mode' => $_POST['invoice_payment_mode'],
          'payment_status' => $payment_status->default_status,
          'address' => $_SESSION['ASSETSTRACKING']['address'],
          'receiver_bill_name' => $_POST['receiver_name'],
          'receiver_email_address' => $_POST['receiver_email_address'],
          'receiver_mobile_number' => $_POST['receiver_mobile_no'],
          'receiver_state' => $_POST['receiver_state'],
          'receiver_gst_number' => $_POST['gst_unique_number_receiver'],
          'receiver_address' => $_POST['address_receiver'],
          'consignee_name' => $_POST['receiver_name'],
          'consignee_email_address' => $_POST['receiver_email_address'],
          'consignee_mobile_number' => $_POST['receiver_mobile_no'],
          'consignee_state' => $_POST['receiver_state'],
          'consignee_state_code' => $consignee_state_code,
          'consignee_gst_number' => $_POST['gst_unique_number_receiver'],
          'consignee_address' => $_POST['address_receiver'],
          'created_by' => $_SESSION['ASSETSTRACKING']['id']
        );
        if (!$this->input->post('save_finish')) {
          $data_array['status'] = 'Inactive';
        }
        ///print_r($data_array);exit;
        $invoice_id = $this->input->post('invoice_id2');
        if ($invoice_id) {
          $where = array('id' => $invoice_id);
          $this->Crud_model->SaveData("invoice", $data_array, $where);
          $last_id = $invoice_id;
        } else {
          $data_array['invoice_no'] = $invoice_no;
          $this->Crud_model->SaveData("invoice", $data_array);
          $last_id = $this->db->insert_id();
        }
        if ($last_id) {

          $data = array(
            'invoice_id' => $last_id,
            'product_id' => $_POST['asset_name'],
            'hsn_code' => $_POST['hsn_code'],
            'quantity' => $_POST['quantity'],
            'rate_per_item' => $_POST['rate_per_item'],
            'total' => $_POST['total'],
            'discount_1' => $_POST['discount_1'],
            'discount_2' => $_POST['discount_2'],
            'discount_3' => $_POST['discount_3'],
            'discount_amount' => $_POST['discount_amount'],
            'taxable' => $_POST['taxable_amount'],
            'gst_rate' => $_POST['gst_percent'],
            'gst_amount' => $_POST['gst_amount'],
            'cgst_rate' => $_POST['cgst_rate'],
            'cgst_amount' => $_POST['cgst_amount'],
            'sgst_rate' => $_POST['sgst_rate'],
            'sgst_amount' => $_POST['sgst_amount'],
            'igst_rate' => $_POST['igst_rate'],
            'igst_amount' => $_POST['igst_amount'],
            'amount_after_gst' => $_POST['amount_after_gst'],
            'adjustment_plus' => $adjustment_plus,
            'adjustment_minus' => $adjustment_minus,
            'total_tax' => $_POST['total_tax'],
            'net_amount' => $netAmount,
            'shipping_charges' => $_POST['shipping_charge'],
            'created' => date('Y-m-d'),
          );
          
          if ($this->input->post('save_finish')) {
            $data['status'] = 'Active';
          } else {
            $data['status'] = 'Inactive';
          }

          if ($this->input->post('edit_invoice_dt_id')) {
            $where = array('id' => $this->input->post('edit_invoice_dt_id'));
            $this->Crud_model->SaveData("invoice_details", $data, $where);

            if ($this->input->post('save_finish')) {
              $arrWhere = array('id' => $_POST['asset_name']);
              $objProduct = $this->model->gatData('assets', $arrWhere);
              $allQty = $objProduct[0]['quantity'] - $_POST['quantity'];

              $arrData = array('quantity' => $allQty);
              $this->model->updateData('assets', $arrData, $arrWhere);
            }

          } else {
            $saveData = $this->Crud_model->SaveData('invoice_details', $data);

            if ($this->input->post('save_finish')) {
              $arrWhere = array('id' => $_POST['asset_name']);
              $objProduct = $this->model->gatData('assets', $arrWhere);
              $allQty = $objProduct[0]['quantity'] - $_POST['quantity'];

              $arrData = array('quantity' => $allQty);
              $this->model->updateData('assets', $arrData, $arrWhere);
            }
          }
        }
        if ($this->input->post('save_finish')) {

          $this->db->select('id, product_id, quantity');
          $this->db->from("invoice_details");      
          $this->db->where('invoice_id',$last_id);
          $query = $this->db->get();
          $detail = $query->result();
          for ($i = 0; $i < count($detail); $i++) {

            $arrWhere = array('id' => $detail[$i]->id);
            $objProduct = $this->model->gatData('assets', $arrWhere);
            $allQty = $objProduct[0]['quantity'] - $_POST['quantity'];

            $arrData = array('quantity' => $allQty);
            $this->model->updateData('assets', $arrData, $arrWhere);

            $update['status'] ="Active";
            $this->Crud_model->SaveData('invoice_details',$update,"id='".$detail[$i]->id."'");
          }

          $update['status'] ="Active";
          $this->Crud_model->SaveData('invoice',$update,"id='".$last_id."'");

          $this->session->set_flashdata('print', 'print');
          //redirect("Invoice/invoicePdf/".$last_id, 'location', 301);
          return redirect('Invoice/view/' . $last_id);
        } else {
          return redirect('Invoice/save_action/' . $last_id);
        }
      } else {
        $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Please select avilable quntity!</span>');
        if ($invoice_id = $this->input->post('invoice_id2')) {
          return redirect('Invoice/save_action/' . $invoice_id);
        } else {
          return redirect('Invoice/create/');
        }
      }
    } else {
      return redirect('Invoice/create');
    }
  }

  public function save_invoice_remove($invoice_id, $id)
  {
    $where = array('id' => $id);
    $this->Crud_model->DeleteData('invoice_details', $where);
    return redirect('Invoice/save_action/' . $invoice_id);
  }

  public function save_invoice_edit($invoice_id, $id)
  {
    //print_r($_SESSION['ASSETSTRACKING']);exit;
    $breadcrumbs = "<ul class='breadcrumb'>
                <li>
                    <i class='ace-icon fa fa-home home-icon'></i>
                    <a href='" . site_url('Dashboard') . "'>Dashboard</a>
                </li>
                 <li class=''> <a href='" . site_url('Invoice') . "'>Invoice</a></li>
                <li class='active'>Create Invoice</li>
                </ul>";

    //$products = $this->Crud_model->GetData("assets","");
    $query = $this->db
      ->select('a.asset_name,a.quantity,a.product_mrp,p.purchase_date,a.id')
      ->from('assets as a')
      ->join('products as p', 'p.id=a.product_id')
      ->where('a.created_by', $_SESSION[SESSION_NAME]['id'])
      ->where('a.quantity>', 0)
      ->get();
    $products = $query->result();

    $rebate = $this->Crud_model->GetData("mst_rebate", "", "status='Active'");
    $states = $this->Crud_model->GetData("mst_states", "", "status='Active'");
    $salesTypes = $this->Crud_model->GetData("sales_type", "", "status='Active'");
    $paymentModes = $this->Crud_model->GetData("payment_types", "", "status='Active'");
    $action =  site_url("Invoice/save_invoice");
    $where = array('id' => $id, 'invoice_id' => $invoice_id);
    $content = array(
      'breadcrumbs' => $breadcrumbs,
      'heading' => 'Create Invoice',
      'button' => 'Create',
      'products' => $products,
      'rebate' => $rebate,
      'action' => $action,
      'states' => $states,
      'edit_data' => $this->Crud_model->GetData("invoice_details", "", $where, "", "", "", "1"),
      'invoice' => $this->Crud_model->GetData("invoice", "", "id='" . $invoice_id . "'", "", "", "", "1"),
      'invoice_id2' => $invoice_id,
      'in_detail_id' => $id,
      'salesTypes' => $salesTypes,
      'paymentModes' => $paymentModes,
    );

    /*echo"<pre>";
        print_r($content);
        exit();*/
    $this->load->view('invoice/form', $content);
  }

  public function save_action($last_id)
  {
    //print_r($_SESSION['ASSETSTRACKING']);exit;
    $breadcrumbs = "<ul class='breadcrumb'>
                <li>
                    <i class='ace-icon fa fa-home home-icon'></i>
                    <a href='" . site_url('Dashboard') . "'>Dashboard</a>
                </li>
                 <li class=''> <a href='" . site_url('Invoice') . "'>Invoice</a></li>
                <li class='active'>Create Invoice</li>
                </ul>";

    //$products = $this->Crud_model->GetData("assets",""); 
    $products = $this->Invoice_model->getProduct();

    $rebate = $this->Crud_model->GetData("mst_rebate", "", "status='Active'");
    $states = $this->Crud_model->GetData("mst_states", "", "status='Active'");
    $salesTypes = $this->Crud_model->GetData("sales_type", "", "status='Active'");
    $paymentModes = $this->Crud_model->GetData("payment_types", "", "status='Active'");
    $invoice = $this->Crud_model->GetData("invoice", "", "id='" . $last_id . "'", "", "", "", "1");

    $action =  site_url("Invoice/save_invoice");

    $content = array(
      'breadcrumbs' => $breadcrumbs,
      'heading' => 'Create Invoice',
      'button' => 'Create',
      'products' => $products,
      'rebate' => $rebate,
      'action' => $action,
      'states' => $states,
      'view' => $this->Crud_model->GetData("invoice_details", "", "invoice_id='" . $last_id . "'"),
      'invoice' => $invoice,
      'invoice_id' => $last_id,
      'salesTypes' => $salesTypes,
      'paymentModes' => $paymentModes,
    );
    // echo"<pre>";
    // print_r($content);
    // exit();
    $this->load->view('invoice/form', $content);
  }


  public function create_action()
  {
    //print_r($_POST);exit;
    //Adjustment
    $adjustment_explode = explode('.', $_POST['net_amount']);
    $point = $adjustment_explode[1];
    //$adjustment = 100 - $point;

    if ($point == '00') {
      $adjustment_plus = 0;
      $adjustment_minus = 0;
    } else if ($point > 50) {
      $adjustment_minus = 0;
      $adjustment_plus = $point;
    } else if ($point <= 50) {
      $adjustment_minus = $point;
      $adjustment_plus = 0;
    }

    $netAmount = $adjustment_explode[0];

    if ($_POST['type_submit'] == 'save_n_next' && $_POST['invoice_id'] == '') {
      $number = $this->Crud_model->GetData("invoice", "count(*) as count", "", "", "id desc", "1", "1");
      if ($number->count != 0) {
        $invoice_no1 = $this->generate_numbers($number->count + 1, '1', '5');
      } else {
        $invoice_no1 = $this->generate_numbers('1', '1', '5');
      }
      $invoice_no = "GST-" . $invoice_no1[0];
      //print_r($invoice_no);exit;

      if ($_POST['state_code_consignee'] == '') {
        $consignee_state_code = '0';
      } else {
        $consignee_state_code = $_POST['state_code_consignee'];
      }

      $data_array = array(
        'invoice_no' => $invoice_no,
        'gst_number' => $_POST['gst_number'],
        'name' => $_POST['name'],
        'date_of_invoice' => date('Y-m-d'),
        'serial_no_of_invoice' => $_POST['serial_no_invoice'],
        'address' => $_POST['address'],
        'receiver_bill_name' => $_POST['receiver_name'],
        'receiver_email_address' => $_POST['receiver_email_address'],
        'receiver_mobile_number' => $_POST['receiver_mobile_no'],
        'receiver_state' => $_POST['receiver_state'],
        'receiver_state_code' => $_POST['state_code'],
        'receiver_gst_number' => $_POST['gst_unique_number_receiver'],
        'receiver_address' => $_POST['address_receiver'],
        'consignee_name' => $_POST['consignee_name'],
        'consignee_email_address' => $_POST['consignee_email'],
        'consignee_mobile_number' => $_POST['consignee_mobile_number'],
        'consignee_state' => $_POST['consignee_state'],
        'consignee_state_code' => $consignee_state_code,
        'consignee_gst_number' => $_POST['gst_unique_number_consignee'],
        'consignee_address' => $_POST['address_consignee'],
        'created_by' => $_SESSION['ASSETSTRACKING']['id']
      );
      ///print_r($data_array);exit;
      $this->Crud_model->SaveData("invoice", $data_array);
      $last_id = $this->db->insert_id();

      if ($last_id) {

        $data = array(
          'invoice_id' => $last_id,
          'product_id' => $_POST['asset_name'],
          'hsn_code' => $_POST['hsn_code'],
          'quantity' => $_POST['quantity'],
          'unit' => $_POST['unit'],
          'rate_per_item' => $_POST['rate_per_item'],
          'total' => $_POST['total'],
          'discount_1' => $_POST['discount_1'],
          'discount_2' => $_POST['discount_2'],
          'discount_3' => $_POST['discount_3'],
          'discount_amount' => $_POST['discount_amount'],
          'taxable' => $_POST['taxable_amount'],
          'gst_rate' => $_POST['gst_percent'],
          'gst_amount' => $_POST['gst_amount'],
          'cgst_rate' => $_POST['cgst_rate'],
          'cgst_amount' => $_POST['cgst_amount'],
          'sgst_rate' => $_POST['sgst_rate'],
          'sgst_amount' => $_POST['sgst_amount'],
          'igst_rate' => $_POST['igst_rate'],
          'igst_amount' => $_POST['igst_amount'],
          'amount_after_gst' => $_POST['amount_after_gst'],
          'adjustment_plus' => $adjustment_plus,
          'adjustment_minus' => $adjustment_minus,
          'total_tax' => $_POST['total_tax'],
          'net_amount' => $netAmount,
          'shipping_charges' => $_POST['shipping_charge'],
          'status' => 'Active',
          'created' => date('Y-m-d'),
        );
        //print_r($data);
        $this->Crud_model->SaveData('invoice_details', $data);
      }

      $response['success'] = '1';
      $response['invoice_id'] = $last_id;
    } else if ($_POST['type_submit'] == 'save_n_next' && $_POST['invoice_id'] != '') {
      $data = array(
        'invoice_id' => $_POST['invoice_id'],
        'product_id' => $_POST['asset_name'],
        'hsn_code' => $_POST['hsn_code'],
        'quantity' => $_POST['quantity'],
        'unit' => $_POST['unit'],
        'rate_per_item' => $_POST['rate_per_item'],
        'total' => $_POST['total'],
        'discount_1' => $_POST['discount_1'],
        'discount_2' => $_POST['discount_2'],
        'discount_3' => $_POST['discount_3'],
        'discount_amount' => $_POST['discount_amount'],
        'taxable' => $_POST['taxable_amount'],
        'gst_rate' => $_POST['gst_percent'],
        'gst_amount' => $_POST['gst_amount'],
        'cgst_rate' => $_POST['cgst_rate'],
        'cgst_amount' => $_POST['cgst_amount'],
        'sgst_rate' => $_POST['sgst_rate'],
        'sgst_amount' => $_POST['sgst_amount'],
        'igst_rate' => $_POST['igst_rate'],
        'igst_amount' => $_POST['igst_amount'],
        'amount_after_gst' => $_POST['amount_after_gst'],
        'adjustment_plus' => $adjustment_plus,
        'adjustment_minus' => $adjustment_minus,
        'total_tax' => $_POST['total_tax'],
        'net_amount' => $netAmount,
        'shipping_charges' => $_POST['shipping_charge'],
        'status' => 'Active',
        'created' => date('Y-m-d'),
      );
      //print_r($data);
      $this->Crud_model->SaveData('invoice_details', $data);

      $response['success'] = '1';
      $response['invoice_id'] = $_POST['invoice_id'];
    } else if ($_POST['type_submit'] == 'save' && $_POST['invoice_id'] == '') {

      $number = $this->Crud_model->GetData("invoice", "count(*) as count", "", "", "id desc", "1", "1");
      if ($number->count != 0) {
        $invoice_no1 = $this->generate_numbers($number->count + 1, '1', '5');
      } else {
        $invoice_no1 = $this->generate_numbers('1', '1', '5');
      }
      $invoice_no = "GST-" . $invoice_no1[0];
      //print_r($invoice_no);exit;
      if ($_POST['state_code_consignee'] == '') {
        $consignee_state_code = '0';
      } else {
        $consignee_state_code = $_POST['state_code_consignee'];
      }

      $data_array = array(
        'invoice_no' => $invoice_no,
        'gst_number' => $_POST['gst_number'],
        'name' => $_POST['name'],
        'date_of_invoice' => date('Y-m-d'),
        'serial_no_of_invoice' => $_POST['serial_no_invoice'],
        'address' => $_POST['address'],
        'receiver_bill_name' => $_POST['receiver_name'],
        'receiver_email_address' => $_POST['receiver_email_address'],
        'receiver_mobile_number' => $_POST['receiver_mobile_no'],
        'receiver_state' => $_POST['receiver_state'],
        'receiver_state_code' => $_POST['state_code'],
        'receiver_gst_number' => $_POST['gst_unique_number_receiver'],
        'receiver_address' => $_POST['address_receiver'],
        'consignee_name' => $_POST['consignee_name'],
        'consignee_email_address' => $_POST['consignee_email'],
        'consignee_mobile_number' => $_POST['consignee_mobile_number'],
        'consignee_state' => $_POST['consignee_state'],
        'consignee_state_code' => $consignee_state_code,
        'consignee_gst_number' => $_POST['gst_unique_number_consignee'],
        'consignee_address' => $_POST['address_consignee'],
        'created_by' => $_SESSION['ASSETSTRACKING']['id']
      );
      ///print_r($data_array);exit;
      $this->Crud_model->SaveData("invoice", $data_array);
      $last_id = $this->db->insert_id();

      if ($last_id) {

        $data = array(
          'invoice_id' => $last_id,
          'product_id' => $_POST['asset_name'],
          'hsn_code' => $_POST['hsn_code'],
          'quantity' => $_POST['quantity'],
          'unit' => $_POST['unit'],
          'rate_per_item' => $_POST['rate_per_item'],
          'total' => $_POST['total'],
          'discount_1' => $_POST['discount_1'],
          'discount_2' => $_POST['discount_2'],
          'discount_3' => $_POST['discount_3'],
          'discount_amount' => $_POST['discount_amount'],
          'taxable' => $_POST['taxable_amount'],
          'gst_rate' => $_POST['gst_percent'],
          'gst_amount' => $_POST['gst_amount'],
          'cgst_rate' => $_POST['cgst_rate'],
          'cgst_amount' => $_POST['cgst_amount'],
          'sgst_rate' => $_POST['sgst_rate'],
          'sgst_amount' => $_POST['sgst_amount'],
          'igst_rate' => $_POST['igst_rate'],
          'igst_amount' => $_POST['igst_amount'],
          'amount_after_gst' => $_POST['amount_after_gst'],
          'adjustment_plus' => $adjustment_plus,
          'adjustment_minus' => $adjustment_minus,
          'total_tax' => $_POST['total_tax'],
          'net_amount' => $netAmount,
          'shipping_charges' => $_POST['shipping_charge'],
          'status' => 'Active',
          'created' => date('Y-m-d'),
        );
        //print_r($data);
        $this->Crud_model->SaveData('invoice_details', $data);
      }

      $response['success'] = '1';
      $response['invoice_id'] = '';
    } else if ($_POST['type_submit'] == 'save' && $_POST['invoice_id'] != '') {

      $data = array(
        'invoice_id' => $_POST['invoice_id'],
        'product_id' => $_POST['asset_name'],
        'hsn_code' => $_POST['hsn_code'],
        'quantity' => $_POST['quantity'],
        'unit' => $_POST['unit'],
        'rate_per_item' => $_POST['rate_per_item'],
        'total' => $_POST['total'],
        'discount_1' => $_POST['discount_1'],
        'discount_2' => $_POST['discount_2'],
        'discount_3' => $_POST['discount_3'],
        'discount_amount' => $_POST['discount_amount'],
        'taxable' => $_POST['taxable_amount'],
        'gst_rate' => $_POST['gst_percent'],
        'gst_amount' => $_POST['gst_amount'],
        'cgst_rate' => $_POST['cgst_rate'],
        'cgst_amount' => $_POST['cgst_amount'],
        'sgst_rate' => $_POST['sgst_rate'],
        'sgst_amount' => $_POST['sgst_amount'],
        'igst_rate' => $_POST['igst_rate'],
        'igst_amount' => $_POST['igst_amount'],
        'amount_after_gst' => $_POST['amount_after_gst'],
        'adjustment_plus' => $adjustment_plus,
        'adjustment_minus' => $adjustment_minus,
        'total_tax' => $_POST['total_tax'],
        'net_amount' => $netAmount,
        'shipping_charges' => $_POST['shipping_charge'],
        'status' => 'Active',
        'created' => date('Y-m-d'),
      );
      //print_r($data);
      $this->Crud_model->SaveData('invoice_details', $data);

      $response['success'] = '1';
      $response['invoice_id'] = '';
    }

    echo json_encode($response);
    exit;
  }

  public function checkBarcode()
  {
      $query =  $this->db->select('a.id,a.asset_name,a.barcode_number,a.gst_percent,a.quantity,a.product_mrp')
              ->join("mst_asset_types mat","mat.id = a.asset_type_id","left")
              ->from('assets as a')
              ->where('a.barcode_number=',$this->input->post('barcode'))
              ->get();
      $select = $query->result();

      if(!empty($select)) {
          $response['success'] = '1';
          $response['gst_percent'] = $select[0]->gst_percent;
          $response['id'] = $select[0]->id;
          $response['name'] = $select[0]->asset_name;
          $response['price'] = $select[0]->product_mrp;
          $response['barcode_number'] = $select[0]->barcode_number;
          //$response['hsn'] = $select->hsn;
        } else {
          $response['success'] = '0';
        }
  
        echo json_encode($response);exit;

  }


  public function delete()
  {
    $con = "id='" . $_POST['id'] . "'";
    $con1 = "invoice_id='" . $_POST['id'] . "'";

    // get all invoice_details
    $invoice_details = $this->Crud_model->GetData("invoice_details", "", $con1);
    if(!empty($invoice_details)) {
      // foreach detail, get product
      foreach ($invoice_details as $invoice_detail) {
        $arrWhere = array('id' => $invoice_detail->product_id);
        $objProduct = $this->model->gatData('assets', $arrWhere);
        
        // product qty = qty + detail_qty
        $allQty = $objProduct[0]['quantity'] + $invoice_detail->quantity;
        $arrData = array('quantity' => $allQty);
        $this->model->updateData('assets', $arrData, $arrWhere);
      }
    }

    $this->Crud_model->DeleteData('invoice_details', $con1);
    $this->Crud_model->DeleteData('invoice', $con);
    $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Asset has been deleted successfully</span>');
    redirect(site_url('Invoice'));
  }
  public function export_pdf_summary($date = 0)
  {
    $this->db->select('i.*,(select SUM(net_amount) from invoice_details where invoice_id=i.id) as sumAmount');
    $this->db->from("invoice i");
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


    $html = $this->load->view('invoice/pdf_invoice_list', $data, true);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output();
  }

  /* ----- Export functionality start ----- */
  public function export_product_summary($date = 0)
  {
    $this->db->select('
      i.*,
      (select SUM(net_amount) from invoice_details where invoice_id=i.id) as sumAmount,
      (select label from sales_type where id=i.invoice_sales_type) as salesType,
      (select type from payment_types where id=i.payment_mode) as paymentMode
      ');
    $this->db->from("invoice i");
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
    $FileTitle = 'Invoice';

    $this->load->library('excel');
    //activate worksheet number 1
    $this->excel->setActiveSheetIndex(0);
    //name the worksheet
    $this->excel->getActiveSheet()->setTitle('Invoice');
    //set cell A1 content with some text
    $this->excel->getActiveSheet()->setCellValue('A1', 'Invoice');

    $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
    $this->excel->getActiveSheet()->setCellValue('B3', 'Invoice No');
    $this->excel->getActiveSheet()->setCellValue('C3', 'GST Number');
    $this->excel->getActiveSheet()->setCellValue('D3', 'Customer Name');
    $this->excel->getActiveSheet()->setCellValue('E3', 'Type of Sales');
    $this->excel->getActiveSheet()->setCellValue('F3', 'Date of Invoice');
    //$this->excel->getActiveSheet()->setCellValue('F3', 'Serial Number of invoice');
    $this->excel->getActiveSheet()->setCellValue('G3', 'Payment Mode');
    $this->excel->getActiveSheet()->setCellValue('H3', 'Invoice Amount');
    //$this->excel->getActiveSheet()->setCellValue('H3', 'HSN');
    //$this->excel->getActiveSheet()->setCellValue('I3', 'GST %');
    //$this->excel->getActiveSheet()->setCellValue('J3', 'Category');
    //$this->excel->getActiveSheet()->setCellValue('K3', 'LF No.');

    $a = '4';
    $sr = 1;
    //print_r($results);exit;
    $sumAmount = 0;
    foreach ($results as $result) {

      /*$total = $result->sum + $result->transport ;
          $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
          $total = $total - $returnAmt->return_amount;*/

      $this->excel->getActiveSheet()->setCellValue('A' . $a, $sr);
      $this->excel->getActiveSheet()->setCellValue('B' . $a, $result->invoice_no);
      $this->excel->getActiveSheet()->setCellValue('C' . $a, $result->gst_number);
      $this->excel->getActiveSheet()->setCellValue('D' . $a, $result->receiver_bill_name);
      $this->excel->getActiveSheet()->setCellValue('E' . $a, $result->salesType);
      $this->excel->getActiveSheet()->setCellValue('F' . $a, date('d-m-Y', strtotime($result->date_of_invoice)));
      //$this->excel->getActiveSheet()->setCellValue('F'.$a, $result->serial_no_of_invoice);                
      $this->excel->getActiveSheet()->setCellValue('G' . $a, $result->paymentMode);
      $this->excel->getActiveSheet()->setCellValue('H' . $a, "Rs. " . number_format($result->sumAmount, 2));

      $sr++;
      $a++;
      $sumAmount += $result->sumAmount;
    }
    $this->excel->getActiveSheet()->setCellValue('H' . $a, "Rs. " . number_format($sumAmount, 2));
    $this->excel->getActiveSheet()->getStyle('H' . $a)->getFont()->setBold(true);
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
    //$this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
    //$this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
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

    $select = $this->Crud_model->GetData("assets", "", "id='" . $_POST['product_id'] . "'", "", "", "", "1");
    if (!empty($select)) {
      $response['success'] = '1';
      $response['gst_percent'] = $select->gst_percent;
      $response['hsn'] = $select->hsn;
      $response['product_mrp'] = $select->product_mrp;
      $response['quantity'] = $select->quantity;
    } else {
      $response['success'] = '0';
    }

    echo json_encode($response);
    exit;
  }
}
