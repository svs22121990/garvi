<?php $this->load->view('common/header');
$this->load->view('common/left_panel'); ?>
<?php $created_by = $_SESSION[SESSION_NAME]['id']; ?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->

<style type="text/css">
    .table-striped>tbody>tr:nth-child(odd)>td,
    .table-striped>tbody>tr:nth-child(odd)>th {
        background: #F8FAFC;
    }

    .table>thead>tr>th,
    .table>tbody>tr>th,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>td,
    .table>tfoot>tr>td {
        border-color: #E5E5E5;
        border-width: 1px;
    }

    .table>thead>tr>th,
    .table>tbody>tr>th,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>td,
    .table>tfoot>tr>td {
        padding: 1px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .form-control {
        padding: 2px 2px;
        height: 20px;
    }

    .select2-search {
        max-width: 200px;
    }

    .select2-results {
        max-width: 200px;
    }

    .select2-dropdown {
        max-width: 200px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 20px;
    }

    .select2-container .select2-selection--single {
        height: 20px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 20px;
    }

    * {
        box-sizing: border-box;
    }

    #myInput {
        background-image: url('/css/searchicon.png');
        background-position: 10px 10px;
        background-repeat: no-repeat;
        width: 100%;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
    }

    #myTable {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
        font-size: 12px;
    }

    #myTable th,
    #myTable td {
        text-align: left;
        padding: 5px;
    }

    #myTable tr {
        border-bottom: 1px solid #ddd;
    }

    #myTable tr.header,
    #myTable tr:hover {
        background-color: #f1f1f1;
    }

    .hideSibling + div {
        display: none;
    }
</style>
<div class="page-title">
    <!-- <h3 class="panel-title"><?= $heading
                                    ?></h3> -->
</div> <!-- PAGE CONTENT WRAPPER -->
<form class="form-horizontal" method="post" onsubmit="return checkValidation('save_n_next')" action="<?php echo $action; ?>" id="myForm" enctype="multipart/form-data">
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong><?= $heading ?></strong>
                            <?php if ($msg = $this->session->flashdata('message')) {
                                echo $msg;
                            } ?>
                        </h3>
                        <ul class="panel-controls">
                            <li><a href="<?= site_url('Invoice/index') ?>"><span class="fa fa-arrow-left"></span></a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="gst_number" id="gst_number" readonly="readonly" class="form-control" placeholder="GST Number" value="<?php if (isset($_SESSION['ASSETSTRACKING'])) {
                                                                                                                                                        echo $_SESSION['ASSETSTRACKING']['gst_number'];
                                                                                                                                                    } ?>">
                    <input type="hidden" name="name" id="name" readonly="readonly" class="form-control" placeholder="Name" value="<?php if (isset($_SESSION['ASSETSTRACKING'])) {
                                                                                                                                        echo $_SESSION['ASSETSTRACKING']['name'];
                                                                                                                                    } ?>">
                    <input type="hidden" name="serial_no_invoice" id="serial_no_invoice" class="form-control" placeholder="Serial No. of Invoice" readonly="readonly" value="<?php if (isset($_SESSION['ASSETSTRACKING'])) {
                                                                                                                                                                                    echo $_SESSION['ASSETSTRACKING']['invoice_serial_number_series'];
                                                                                                                                                                                } ?>">
                    <!--<div class="panel-body">
            <div class="row">
              <div class="col-md-12" style="padding: 0;">

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> GST Number <span style="color: red;">*</span> <span id="error_gst_number" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="gst_number" id="gst_number" readonly="readonly" class="form-control" placeholder="GST Number" value="<?php if (isset($_SESSION['ASSETSTRACKING'])) {
                                                                                                                                                        echo $_SESSION['ASSETSTRACKING']['gst_number'];
                                                                                                                                                    } ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Name <span style="color: red;">*</span> <span id="error_name" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="name" id="name" readonly="readonly" class="form-control" placeholder="Name" value="<?php if (isset($_SESSION['ASSETSTRACKING'])) {
                                                                                                                                        echo $_SESSION['ASSETSTRACKING']['name'];
                                                                                                                                    } ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Serial No of Invoice <span style="color: red;">*</span> <span id="error_serial_no_invoice" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="serial_no_invoice" id="serial_no_invoice" class="form-control" placeholder="Serial No. of Invoice" readonly="readonly" value="<?php if (isset($_SESSION['ASSETSTRACKING'])) {
                                                                                                                                                                                    echo $_SESSION['ASSETSTRACKING']['invoice_serial_number_series'];
                                                                                                                                                                                } ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Address <span style="color: red;">*</span> <span id="error_address" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <textarea name="address" id="address" class="form-control" placeholder="Address" readonly="readonly"><?php if (isset($_SESSION['ASSETSTRACKING'])) {
                                                                                                                                echo $_SESSION['ASSETSTRACKING']['address'];
                                                                                                                            } ?></textarea>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>-->
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Billing Address</strong></h3>

                    </div>
                    <div class="panel-body">
                    
                    <div class="row" style="margin-bottom: 1em;" >
                            <div class="col-md-6" style="padding: 0" id="invoiceIDSelector">
                                <!-- <div class="col-md-12">
                                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#createIDModal" data-backdrop="static" data-keyboard="false" onclick="updateNewInvoiceNoVTxt()">
                                        <span class="fa fa-plus"></span> &nbsp; Invoice No. <small>(optional)</small>
                                    </button>
                                </div> -->
                            </div>

                            <div class="col-md-6" style="text-align: right;">
                            <div class="form-group">
                                <label class="col-md-11 pull-right">Invoice Date</label>
                                <div class="col-md-4 pull-right">
                                <input type="text" name="date_of_invoice" class="form-control datepicker" placeholder="Date of Invoice">
                                </div>
                            </div>
                            </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12" style="padding: 0;">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> Name <span style="color: red;">*</span> <span id="error_receiver_name" style="color: red;"></span></label>
                                        <div class="col-md-11">
                                            <input type="text" name="receiver_name" id="receiver_name" class="form-control" placeholder="Name" value="<?php if (isset($invoice)) {
                                                                                                                                                            echo $invoice->receiver_bill_name;
                                                                                                                                                        } ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> Email <span id="error_receiver_email_address" style="color: red;"></span></label>
                                        <div class="col-md-11">
                                            <input type="text" name="receiver_email_address" id="receiver_email_address" class="form-control" placeholder="Email" value="<?php if (isset($invoice)) {
                                                                                                                                                                                echo $invoice->receiver_email_address;
                                                                                                                                                                            } ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> Mobile Number <span id="error_receiver_mobile_no" style="color: red;"></span></label>
                                        <div class="col-md-11">
                                            <input type="text" name="receiver_mobile_no" id="receiver_mobile_no" maxlength="10" class="form-control" placeholder="Mobile Number" value="<?php if (isset($invoice)) {
                                                                                                                                                                                            echo $invoice->receiver_mobile_number;
                                                                                                                                                                                        } ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> State <span style="color: red;">*</span> <span id="error_receiver_state" style="color: red;"></span></label>
                                        <div class="col-md-11">
                                            <select name="receiver_state" id="receiver_state" class="form-control" onchange="countGst();">
                                                <option value="">Select State</option>
                                                <?php
                                                if (!empty($states)) {
                                                    foreach ($states as $state) {
                                                        ?>
                                                        <option <?php if (isset($invoice)) {
                                                                    if ($invoice->receiver_state == $state->id) {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="<?php echo $state->id ?>"><?php echo $state->state_name; ?></option>
                                                    <?php

                                                }
                                            }
                                            ?>
                                            </select>
                                            <!-- <input type="text" name="receiver_state" id="receiver_state" class="form-control" placeholder="State"> -->
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> State Code <span style="color: red;">*</span> <span id="error_state_code" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="state_code" id="state_code" class="form-control" placeholder="State Code">
                    </div>
                  </div>
                </div>-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> GSTIN / Unique No <span id="error_gst_unique_number_receiver" style="color: red;"></span></label>
                                        <div class="col-md-11">
                                            <input type="text" name="gst_unique_number_receiver" id="gst_unique_number_receiver" class="form-control" placeholder="GSTIN / Unique No" value="<?php if (isset($invoice)) {
                                                                                                                                                                                                    echo $invoice->receiver_gst_number;
                                                                                                                                                                                                } ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> Billing Address <span id="error_address_receiver" style="color: red;"></span></label>
                                        <div class="col-md-11">
                                            <textarea name="address_receiver" id="address_receiver" class="form-control" placeholder="Address"><?php if (isset($invoice)) {
                                                                                                                                                    echo $invoice->receiver_address;
                                                                                                                                                } ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> Sales Type <span style="color: red;">*</span><span id="error_invoice_sales_type" style="color: red;"></span></label>
                                        <div class="col-md-11">
                                            <select name="invoice_sales_type" id="invoice_sales_type" class="form-control" <?php if (isset($invoice)) {
                                                                                                                                echo "value =" . $invoice->invoice_sales_type;
                                                                                                                            } ?>>
                                                <option value="">Select Sales Type</option>
                                                <?php
                                                if (!empty($salesTypes)) {
                                                    foreach ($salesTypes as $salesType) : ?>
                                                        <option <?php if (isset($invoice)) {
                                                                    if ($invoice->invoice_sales_type == $salesType->id) {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="<?= $salesType->id ?>"><?= $salesType->label ?></option>
                                                    <?php
                                                endforeach;
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> Payment Mode <span style="color: red;">*</span> <span id="error_invoice_payment_mode" style="color: red;"></span></label>
                                        <div class="col-md-11">
                                            <select name="invoice_payment_mode" id="invoice_payment_mode" class="form-control" <?php if (isset($invoice)) {
                                                                                                                                    echo "value =" . $invoice->payment_mode;
                                                                                                                                } ?>>
                                                <option value="">Select Payment Mode</option>
                                                <?php
                                                if (!empty($paymentModes)) {
                                                    foreach ($paymentModes as $paymentMode) : ?>
                                                        <option <?php if (isset($invoice)) {
                                                                    if ($invoice->payment_mode == $paymentMode->id) {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="<?= $paymentMode->id ?>"><?= $paymentMode->type ?></option>
                                                    <?php
                                                endforeach;
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <?php $type = $_SESSION[SESSION_NAME]['type']; ?>
                    <?php if ($type != 'User') : ?>
                        <!-- Consignee -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Shipping Address</strong></h3>

                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12" style="padding: 0;">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-md-11"> Name <span style="color: red;">*</span> <span id="error_consignee_name" style="color: red;"></span></label>
                                                <div class="col-md-11">
                                                    <input type="text" name="consignee_name" id="consignee_name" class="form-control" placeholder="Name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-md-11"> Email </label>
                                                <div class="col-md-11">
                                                    <input type="text" name="consignee_email" id="consignee_email" class="form-control" placeholder="Email">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-md-11"> Mobile Number <span id="error_consignee_mobile_number" style="color: red;"></span></label>
                                                <div class="col-md-11">
                                                    <input type="text" name="consignee_mobile_number" id="consignee_mobile_number" maxlength="10" class="form-control" placeholder="Mobile Number">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-md-11"> State <span style="color: red;">*</span> <span id="error_consignee_state" style="color: red;"></span></label>
                                                <div class="col-md-11">
                                                    <select name="consignee_state" id="consignee_state" class="form-control" onchange="countGst();">
                                                        <option value="">Select State</option>
                                                        <?php
                                                        if (!empty($states)) {
                                                            foreach ($states as $state) {
                                                                ?>
                                                                <option value="<?php echo $state->id ?>"><?php echo $state->state_name; ?></option>
                                                            <?php

                                                        }
                                                    }
                                                    ?>
                                                    </select>
                                                    <!-- <input type="text" name="consignee_state" id="consignee_state" class="form-control" placeholder="State"> -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-md-11"> State Code <span style="color: red;">*</span> <span id="error_state_code_consignee" style="color: red;"></span></label>
                                                <div class="col-md-11">
                                                    <input type="text" name="state_code_consignee" id="state_code_consignee" class="form-control" placeholder="State Code">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-md-11"> GSTIN / Unique No <span id="error_gst_unique_number_consignee" style="color: red;"></span></label>
                                                <div class="col-md-11">
                                                    <input type="text" name="gst_unique_number_consignee" id="gst_unique_number_consignee" class="form-control" placeholder="GSTIN / Unique No"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-md-11"> Shipping Address <span style="color: red;">*</span> <span id="error_address_consignee" style="color: red;"></span></label>
                                                <div class="col-md-11">
                                                    <textarea name="address_consignee" id="address_consignee" class="form-control" placeholder="Address"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <input type="checkbox" name="same_as_billing" id="same_as_billing" onclick="sameAsBilling();">&nbsp;&nbsp; Same as Billing
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-md-12" style="padding: 0;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                Barcode
                                                <span style="color: red;">*</span> <span id="error_barcode" style="color: red;"></span></th>
                                            <th>
                                                Product
                                                <span style="color: red;">*</span> <span id="error_asset_name" style="color: red;"></span></th>
                                            <th>
                                                HSN
                                                <span style="color: red;">*</span> <span id="error_hsn_code" style="color: red;"></span>
                                            </th>
                                            <th>
                                                Qty
                                                <span style="color: red;">*</span> <span id="error_quantity" style="color: red;"></span>
                                            </th>
                                            <th>
                                                Rate per Item
                                                <span style="color: red;">*</span> <span id="error_rate_per_item" style="color: red;"></span>
                                            </th>
                                            <th>
                                                Total
                                                <span style="color: red;">*</span> <span id="error_total" style="color: red;"></span>
                                            </th>
                                            <th>
                                                Discount
                                                <span style="color: red;">*</span> <span id="error_discount_1" style="color: red;"></span>
                                            </th>
                                            <th>
                                                Taxable Amount
                                                <span style="color: red;">*</span> <span id="error_discount_amount" style="color: red;"></span>
                                            </th>
                                            <th>
                                                GST
                                                <span style="color: red;">*</span> <span id="error_cgst_rate" style="color: red;"></span>
                                            </th>
                                            <th>
                                                Amount after GST
                                                <span style="color: red;">*</span> <span sstyle="color: red;"></span>
                                            </th>
                                            <th>
                                                Shipping/Other Charge
                                                <span style="color: red;">*</span> <span id="error_shipping_charge" style="color: red;"></span>
                                            </th>
                                            <th>
                                                Total Tax
                                                <span style="color: red;">*</span> <span id="error_total_tax" style="color: red;"></span>
                                            </th>
                                            <th>
                                                Net Amount
                                                <span style="color: red;">*</span> <span id="error_net_amount" style="color: red;"></span>
                                            </th>
                                            <th>
                                                <?php if (!isset($edit_data)) : ?>
                                                    <button type="button" id="add_invoice2" class="btn btn-success">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                <?php endif; ?>

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="professorTableBody">
                                        <?php if (!isset($edit_data)) : ?>
                                            <?php if (isset($view)) : ?>
                                                <?php foreach ($view as $key) : ?>
                                                    <tr>
                                                    <td>
                                                        <input type="text" class="form-control barcode" name="barcode" placeholder="Enter Barcode No." autocomplete="off">
                                                    </td>
                                                        <td>
                                                            <select class="form-control" style="min-width: 100px;" readonly>
                                                                <option value="">Select Product</option>
                                                                <?php
                                                                if (!empty($products)) {
                                                                    foreach ($products as $product) {
                                                                        ?>
                                                                        <option <?php if (isset($view)) {
                                                                                    if ($key->product_id == $product->id) {
                                                                                        echo "selected";
                                                                                    }
                                                                                } ?> value="<?php echo $product->id ?>"><?php echo $product->asset_name; ?></option>
                                                                    <?php

                                                                }
                                                            }
                                                            ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" placeholder="HSN Code" readonly="readonly" autocomplete="off" value="<?= $key->hsn_code ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control count-qty" placeholder="Quantity" autocomplete="off" readonly value="<?= $key->quantity ?>">

                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control count-rate-per-item" readonly="readonly" value="<?= $key->rate_per_item ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control count-total" readonly="readonly" value="<?= $key->total ?>">
                                                        </td>
                                                        <td>
                                                            <select readonly class="form-control discount-class">
                                                                <option value="">Discount 1</option>
                                                                <?php
                                                                if (!empty($rebate)) {
                                                                    foreach ($rebate as $single_row) {
                                                                        ?>
                                                                        <option <?php if (isset($view)) {
                                                                                    if ($key->discount_1 == $single_row->rebate_percent) {
                                                                                        echo "selected";
                                                                                    }
                                                                                } ?> value="<?php echo $single_row->rebate_percent; ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                                    <?php

                                                                }
                                                            }
                                                            ?>
                                                            </select>
                                                            <select readonly class="form-control discount-class">
                                                                <option value="">Discount 2</option>
                                                                <?php
                                                                if (!empty($rebate)) {
                                                                    foreach ($rebate as $single_row) {
                                                                        ?>
                                                                        <option <?php if (isset($view)) {
                                                                                    if ($key->discount_2 == $single_row->rebate_percent) {
                                                                                        echo "selected";
                                                                                    }
                                                                                } ?> value="<?php echo $single_row->rebate_percent ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                                    <?php

                                                                }
                                                            }
                                                            ?>
                                                            </select>

                                                            <select readonly class="form-control discount-class">
                                                                <option value="">Discount 3</option>
                                                                <?php
                                                                if (!empty($rebate)) {
                                                                    foreach ($rebate as $single_row) {
                                                                        ?>
                                                                        <option <?php if (isset($view)) {
                                                                                    if ($key->discount_3 == $single_row->rebate_percent) {
                                                                                        echo "selected";
                                                                                    }
                                                                                } ?> value="<?php echo $single_row->rebate_percent ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                                    <?php

                                                                }
                                                            }
                                                            ?>
                                                            </select><br>
                                                            Total:<input type="text" class="form-control count-discount" readonly placeholder="Discount Amount" readonly="readonly" autocomplete="off" value="<?= $key->discount_amount ?>">
                                                        </td>

                                                        <td>
                                                            <input type="text" class="form-control count-taxable" readonly placeholder="Taxable Amount" readonly="readonly" autocomplete="off" value="<?= $key->taxable ?>">
                                                        </td>
                                                        <td>
                                                            CGST
                                                            <input type="text" class="form-control" readonly placeholder="CGST Rate" readonly="readonly" autocomplete="off" value="<?= $key->cgst_rate ?>">
                                                            <input type="text" class="form-control count-gst" readonly placeholder="CGST Amount" readonly="readonly" autocomplete="off" value="<?= $key->cgst_amount ?>">

                                                            SGST
                                                            <input type="text" class="form-control" readonly placeholder="SGST Rate" readonly="readonly" autocomplete="off" value="<?= $key->sgst_rate ?>">
                                                            <input type="text" class="form-control count-gst" readonly placeholder="SGST Amount" readonly="readonly" autocomplete="off" value="<?= $key->sgst_amount ?>">

                                                            IGST
                                                            <input type="text" class="form-control" readonly placeholder="IGST Rate" readonly="readonly" autocomplete="off" value="<?= $key->igst_rate ?>">
                                                            <input type="text" class="form-control count-gst" name="igst_amount" placeholder="IGST Amount" readonly="readonly" autocomplete="off" value="<?= $key->igst_amount ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control count-amt-gst" placeholder="Amount after GST" readonly="readonly" autocomplete="off" value="<?= $key->amount_after_gst ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control count-shipping" readonly placeholder="Other Charge" autocomplete="off" onkeypress="return only_number(event);" oninput="getNetAmountAfterShipping();" value="0" value="<?= $key->shipping_charges ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control count-total-tax" readonly placeholder="Total tax" readonly="readonly" autocomplete="off" value="<?= $key->total_tax ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control count-total-amount" readonly placeholder="Net Amount" readonly="readonly" autocomplete="off" value="<?= $key->net_amount ?>">
                                                        </td>
                                                        <td style="width: 60px;">
                                                            <a href="<?= base_url(); ?>index.php/Invoice/save_invoice_edit/<?= $invoice_id ?>/<?= $key->id; ?>" class="btn  btn-xs btn-primary"><i class="fa fa-edit"></i></a>

                                                            <a href="<?= base_url(); ?>index.php/Invoice/save_invoice_remove/<?= $invoice_id ?>/<?= $key->id; ?>" class="btn  btn-xs btn-danger"><i class="fa fa-minus"></i></a>


                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <tr>
                                                <?php if (isset($invoice)) : ?>
                                                    <input type="hidden" name="invoice_id2" value="<?= $invoice_id ?>">
                                                <?php endif; ?>
                                                <td>
                                                    <input type="text" class="form-control barcode" name="barcode" placeholder="Enter Barcode No." autocomplete="off">
                                                </td>
                                                <td>

                                                    <select class="form-control" name="asset_name" id="asset_name1" onchange="getGST(this.value);" style="min-width: 100px;">
                                                        <option value="">Select Product</option>

                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="hsn_code" id="hsn_code1" placeholder="HSN Code" readonly="readonly" autocomplete="off">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-qty" name="quantity" id="quantity1" placeholder="Quantity" autocomplete="off" oninput="getTotalAmount();">

                                                    <input type="hidden" class="form-control" name="available_quantity" id="available_quantity1"></td>
                                                <td>
                                                    <input type="text" class="form-control count-rate-per-item" name="rate_per_item" id="rate_per_item1" placeholder="Rate (per item)" autocomplete="off" oninput="getTotalAmount();" readonly="readonly">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-total" name="total" id="total1" placeholder="total" readonly="readonly" autocomplete="off" >
                                                </td>
                                                <td>
                                                    <select name="discount_1" id="discount_1_1" class="form-control discount-class" onchange="getDiscountAmount();">
                                                        <option value="">Discount 1</option>
                                                        <?php
                                                        if (!empty($rebate)) {
                                                            foreach ($rebate as $single_row) {
                                                                ?>
                                                                <option value="<?php echo $single_row->rebate_percent; ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                            <?php

                                                        }
                                                    }
                                                    ?>
                                                    </select>
                                                    <select name="discount_2" id="discount_2_1" class="form-control discount-class" onchange="getDiscountAmount();">
                                                        <option value="">Discount 2</option>
                                                        <?php
                                                        if (!empty($rebate)) {
                                                            foreach ($rebate as $single_row) {
                                                                ?>
                                                                <option value="<?php echo $single_row->rebate_percent ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                            <?php

                                                        }
                                                    }
                                                    ?>
                                                    </select>

                                                    <select name="discount_3" id="discount_3_1" class="form-control discount-class" onchange="getDiscountAmount();">
                                                        <option value="">Discount 3</option>
                                                        <?php
                                                        if (!empty($rebate)) {
                                                            foreach ($rebate as $single_row) {
                                                                ?>
                                                                <option value="<?php echo $single_row->rebate_percent ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                            <?php

                                                        }
                                                    }
                                                    ?>
                                                    </select><br>
                                                    Total:<input type="text" class="form-control count-discount" name="discount_amount" id="discount_amount1" placeholder="Discount Amount" readonly="readonly" autocomplete="off">
                                                    <br>
                                                    <!-- Payment Mode: <select name="invoice_payment_mode" id="invoice_payment_mode" class="form-control">
                                                         <option value="">Select Payment Mode</option>
                                                         <?php
                                                            if (!empty($paymentModes)) {
                                                                foreach ($paymentModes as $paymentMode) : ?>
                                                                 <option value="<?= $paymentMode->id ?>"><?= $paymentMode->type ?></option>
                                                             <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                     </select> -->
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control count-taxable" name="taxable_amount" id="taxable_amount1" placeholder="Taxable Amount" readonly="readonly" autocomplete="off">
                                                </td>
                                                <td>
                                                    CGST
                                                    <input type="text" class="form-control" name="cgst_rate" id="cgst_rate1" placeholder="CGST Rate" readonly="readonly" autocomplete="off">
                                                    <input type="text" class="form-control count-gst" name="cgst_amount" id="cgst_amount1" placeholder="CGST Amount" readonly="readonly" autocomplete="off">

                                                    SGST
                                                    <input type="text" class="form-control" name="sgst_rate" id="sgst_rate1" placeholder="SGST Rate" readonly="readonly" autocomplete="off">
                                                    <input type="text" class="form-control count-gst" name="sgst_amount" id="sgst_amount1" placeholder="SGST Amount" readonly="readonly" autocomplete="off">

                                                    IGST
                                                    <input type="text" class="form-control" name="igst_rate" id="igst_rate1" placeholder="IGST Rate" readonly="readonly" autocomplete="off">
                                                    <input type="text" class="form-control count-gst" name="igst_amount" id="igst_amount1" placeholder="IGST Amount" readonly="readonly" autocomplete="off">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-amt-gst" name="amount_after_gst" id="amount_after_gst1" placeholder="Amount after GST" readonly="readonly" autocomplete="off">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-shipping" name="shipping_charge" id="shipping_charge1" placeholder="Other Charge" autocomplete="off" onkeypress="return only_number(event);" oninput="getNetAmountAfterShipping();" value="0">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-total-tax" name="total_tax" id="total_tax1" placeholder="Total tax" readonly="readonly" autocomplete="off">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-total-amount" name="net_amount" id="net_amount1" placeholder="Net Amount" readonly="readonly" autocomplete="off">
                                                </td>
                                                <td>
                                                    <!--<input type="hidden" class="sectionA" value="1">
                                    <a href="javascript:void(0)" onclick="remove_tr($(this).closest('tr').index())" class="btn  btn-sm btn-danger"><i class="fa fa-minus"></i></a>-->
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (isset($edit_data)) : ?>
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control barcode" name="barcode" placeholder="Enter Barcode No." autocomplete="off">
                                                </td>
                                                <td>

                                                    <select class="form-control" name="asset_name" id="asset_name1" onchange="getGST(this.value);" style="min-width: 100px;">
                                                        <option value="">Select Product</option>
                                                        <?php
                                                        if (!empty($products)) {
                                                            foreach ($products as $product) {
                                                                ?>
                                                                <option <?php if (isset($edit_data)) {
                                                                            if ($edit_data->product_id == $product->id) {
                                                                                echo "selected";
                                                                            }
                                                                        } ?> value="<?php echo $product->id ?>"><?php echo $product->asset_name; ?></option>
                                                            <?php

                                                        }
                                                    }
                                                    ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="hsn_code" id="hsn_code1" placeholder="HSN Code" readonly="readonly" autocomplete="off" value="<?= $edit_data->hsn_code ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-qty" name="quantity" id="quantity1" placeholder="Quantity" autocomplete="off" oninput="getTotalAmount();" value="<?= $edit_data->quantity ?>">

                                                    <input type="hidden" class="form-control" name="available_quantity" id="available_quantity1"></td>
                                                <td>
                                                    <input type="text" class="form-control count-rate-per-item" name="rate_per_item" id="rate_per_item1" placeholder="Rate (per item)" autocomplete="off" oninput="getTotalAmount();" readonly="readonly" value="<?= $edit_data->rate_per_item ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-total" name="total" id="total1" placeholder="total" readonly="readonly" autocomplete="off" value="<?= $edit_data->total ?>">
                                                </td>
                                                <td>
                                                    <select name="discount_1" id="discount_1_1" class="form-control discount-class" onchange="getDiscountAmount();">
                                                        <option value="">Discount 1</option>
                                                        <?php
                                                        if (!empty($rebate)) {
                                                            foreach ($rebate as $single_row) {
                                                                ?>
                                                                <option <?php if (isset($edit_data)) {
                                                                            if ($edit_data->discount_1 == $single_row->rebate_percent) {
                                                                                echo "selected";
                                                                            }
                                                                        } ?> value="<?php echo $single_row->rebate_percent; ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                            <?php

                                                        }
                                                    }
                                                    ?>
                                                    </select>
                                                    <select name="discount_2" id="discount_2_1" class="form-control discount-class" onchange="getDiscountAmount();">
                                                        <option value="">Discount 2</option>
                                                        <?php
                                                        if (!empty($rebate)) {
                                                            foreach ($rebate as $single_row) {
                                                                ?>
                                                                <option <?php if (isset($edit_data)) {
                                                                            if ($edit_data->discount_2 == $single_row->rebate_percent) {
                                                                                echo "selected";
                                                                            }
                                                                        } ?> value="<?php echo $single_row->rebate_percent ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                            <?php

                                                        }
                                                    }
                                                    ?>
                                                    </select>

                                                    <select name="discount_3" id="discount_3_1" class="form-control discount-class" onchange="getDiscountAmount();">
                                                        <option value="">Discount 3</option>
                                                        <?php
                                                        if (!empty($rebate)) {
                                                            foreach ($rebate as $single_row) {
                                                                ?>
                                                                <option <?php if (isset($edit_data)) {
                                                                            if ($edit_data->discount_3 == $single_row->rebate_percent) {
                                                                                echo "selected";
                                                                            }
                                                                        } ?> value="<?php echo $single_row->rebate_percent ?>"><?php echo $single_row->rebate_percent . " %"; ?></option>
                                                            <?php

                                                        }
                                                    }
                                                    ?>
                                                    </select><br>
                                                    Total:<input type="text" class="form-control count-discount" name="discount_amount" id="discount_amount1" placeholder="Discount Amount" readonly="readonly" autocomplete="off" value="<?= $edit_data->discount_amount ?>">
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control count-taxable" name="taxable_amount" id="taxable_amount1" placeholder="Taxable Amount" readonly="readonly" autocomplete="off" value="<?= $edit_data->taxable ?>">
                                                </td>
                                                <td>
                                                    CGST
                                                    <input type="text" class="form-control" name="cgst_rate" id="cgst_rate1" placeholder="CGST Rate" readonly="readonly" autocomplete="off" value="<?= $edit_data->cgst_rate ?>">
                                                    <input type="text" class="form-control count-gst" name="cgst_amount" id="cgst_amount1" placeholder="CGST Amount" readonly="readonly" autocomplete="off" value="<?= $edit_data->cgst_amount ?>">

                                                    SGST
                                                    <input type="text" class="form-control" name="sgst_rate" id="sgst_rate1" placeholder="SGST Rate" readonly="readonly" autocomplete="off" value="<?= $edit_data->sgst_rate ?>">
                                                    <input type="text" class="form-control count-gst" name="sgst_amount" id="sgst_amount1" placeholder="SGST Amount" readonly="readonly" autocomplete="off" value="<?= $edit_data->sgst_amount ?>">

                                                    IGST
                                                    <input type="text" class="form-control" name="igst_rate" id="igst_rate1" placeholder="IGST Rate" readonly="readonly" autocomplete="off" value="<?= $edit_data->igst_rate ?>">
                                                    <input type="text" class="form-control count-gst" name="igst_amount" id="igst_amount1" placeholder="IGST Amount" readonly="readonly" autocomplete="off" value="<?= $edit_data->igst_amount ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-amt-gst" name="amount_after_gst" id="amount_after_gst1" placeholder="Amount after GST" readonly="readonly" autocomplete="off" value="<?= $edit_data->amount_after_gst ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-shipping" name="shipping_charge" id="shipping_charge1" placeholder="Other Charge" autocomplete="off" onkeypress="return only_number(event);" oninput="getNetAmountAfterShipping();" value="0" value="<?= $edit_data->shipping_charges ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-total-tax" name="total_tax" id="total_tax1" placeholder="Total tax" readonly="readonly" autocomplete="off" value="<?= $edit_data->total_tax ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control count-total-amount" name="net_amount" id="net_amount1" placeholder="Net Amount" readonly="readonly" autocomplete="off" value="<?= $edit_data->net_amount ?>">

                                                </td>
                                                <?php if (isset($edit_data)) : ?>
                                                    <input type="hidden" value="<?= $invoice_id2; ?>" name="invoice_id2">
                                                    <input type="hidden" value="<?= $in_detail_id; ?>" name="edit_invoice_dt_id">
                                                <?php endif; ?>
                                                <td>
                                                    <!--<input type="hidden" class="sectionA" value="1">-->
                                                    <?php if (isset($edit_data)) : ?>
                                                        <a href="javascript:void(0)" id="edit_save_btn" class="btn  btn-xs btn-success"><i class="fa fa-save"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><input type="text" class="form-control" id="total_qty" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_rate_per_item" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_amount_qty" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_discount" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_taxable_amount" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_gst" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_amount_gst" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_shipping" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_tax" readonly="readonly"></th>
                                            <th><input type="text" class="form-control" id="total_net_amount" readonly="readonly"></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="save_finish_body">
            </div>
            <div class="panel-footer">
                <input type="hidden" name="invoice_id" id="invoice_id">
                <input type="hidden" name="type_submit" id="type_submit">

                <input type="hidden" name="adjustment_plus" id="adjustment_plus">
                <input type="hidden" name="adjustment_minus" id="adjustment_minus">

                <input type="hidden" name="gst_percent" id="gstPercent">
                <input type="hidden" name="gst_amount" id="gstAmount">

                <!--<button class="btn btn-success" type="button" onclick="checkValidation('save_n_next');" id="submit_save_n_next2">Save & Next</button>
    <button class="btn btn-success" type="submit"  id="submit_save_n_next">Save & Next2</button>
    <button class="btn btn-success" type="button" onclick="checkValidation('save');">Save</button>-->
                <button class="btn btn-success" type="button" id="save_finish">Save</button>
                <a href="<?php echo site_url('Invoice'); ?>"><button class="btn btn-default" type="button">Cancel</button></a>
            </div>
        </div>
</form>

</div>
</div>

</div>
<!-- END PAGE CONTENT WRAPPER -->

<!-- Modal -->
<div class="modal fade" id="createIDModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Invoice Number</h4>
        </div>
        <div class="modal-body" style="font-size: 2em; padding: 25px 20%;">
          <input style="height: 1.5em; font-size: 1em; padding: 5px;" type="text" class="form-control" id="newInvoiceNo" onchange="updateNewInvoiceNoVTxt()">
          <p style="color:red; font-size:12px; margin-top: 5px;" id="newInvoiceNoVTxt"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-primary" onclick="checkInvoiceNo()">Add</button>
        </div>
      </div>
      
    </div>
  </div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Product</h4>
            </div>
            <div class="modal-body">
                <!--<pre><?php
                            if (!empty($products)) {
                                /* foreach ($products as $product) {
              ?>
              <option value="<?php echo $product->id ?>"><?php echo $product->asset_name; ?></option>
              <?php
                }*/
                                print_r($products);
                            }
                            ?></pre>-->
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">

                <table id="myTable" style="max-height:600px;overflow-x:scroll">
                    <tr class="header">
                        <th>Name</th>
                        <th>Barcode</th>
                        <th>Available Quantity</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Fabric</th>
                        <th>Craft</th>
                        <th>Purchase Date</th>
                        <th>AGE</th>
                        <th>Add</th>
                    </tr>
                    <?php if (!empty($products)) {
                        foreach ($products as $product) {
                            $startDate = $product->purchase_date;
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
                            $arrTime[] =  $interval->d . ' Days';
                            ?>
                            <tr>
                                <td><?= $product->asset_name; ?></td>
                                <td><?= $product->barcode_number; ?></td>
                                <td><?= $product->quantity; ?></td>
                                <td><?= $product->product_mrp; ?></td>
                                <td><?= $product->title; ?></td>
                                <td><?= $product->color; ?></td>
                                <td><?= $product->size; ?></td>
                                <td><?= $product->fabric; ?></td>
                                <td><?= $product->craft; ?></td>
                                <td><?= date("d-m-Y", strtotime($product->purchase_date)); ?></td>
                                <td><?= implode(" ", $arrTime); ?></td>
                                <td><button id="add_product" class="btn btn-success add_product" data-name="<?= $product->asset_name; ?>" data-id="<?= $product->id; ?>"><i class="fa fa-plus"></i></button></td>
                            </tr>
                        <?php
                    }
                } ?>
                </table>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<?php $this->load->view('common/footer'); ?>

<!-- hide code -->
<script type="text/javascript">
    //onload call function 
    $(document).ready(function() {
        allQty();
        allRateItem();
        allTotal();
    });
    //qty keyup query
    $(document).on('keyup', '.count-qty', function() {
        allQty();
        allRateItem();
        allTotal();
    });
    $(document).on('change', '.discount-class', function() {
        allDiscount();
        allTaxable();
        allGst();
        allAmtGst();
        allShipping();
        allTotalTax();
        allTotalAmount();
    });
    $(document).on('keyup', '.count-shipping', function() {
        allShipping();
        allTotalTax();
        allTotalAmount();
    });

    //count all total text
    function allTotalAmount() {
        var $totalamtsum = 0;
        $('.count-total-amount').each(function() {
            if (this.value != '') {
                $totalamtsum += parseFloat(this.value);
            }
        });
        $("#total_net_amount").val($totalamtsum);
    }



    //count all total text
    function allTotalTax() {
        var $shippingsum = 0;
        $('.count-total-tax').each(function() {
            if (this.value != '') {
                $shippingsum += parseFloat(this.value);
            }
        });
        $("#total_tax").val($shippingsum);
    }


    //count all gst
    function allShipping() {
        var $shippingsum = 0;
        $('.count-shipping').each(function() {
            if (this.value != '') {
                $shippingsum += parseFloat(this.value);
            }
        });
        $("#total_shipping").val($shippingsum);
    }


    //count all gst
    function allAmtGst() {
        var $gstamtsum = 0;
        $('.count-amt-gst').each(function() {
            if (this.value != '') {
                $gstamtsum += parseFloat(this.value);
            }
        });
        $("#total_amount_gst").val($gstamtsum);
    }


    //count all gst
    function allGst() {
        var $gstsum = 0;
        $('.count-gst').each(function() {
            if (this.value != '') {
                $gstsum += parseFloat(this.value);
            }
        });
        $("#total_gst").val($gstsum);
    }

    //couunt all taxable amount
    function allTaxable() {
        var $taxablesum = 0;
        $('.count-taxable').each(function() {
            if (this.value != '') {
                $taxablesum += parseFloat(this.value);
            }
        });
        $("#total_taxable_amount").val($taxablesum);
    }

    //count all total discount
    function allDiscount() {
        var $discountsum = 0;
        $('.count-discount').each(function() {
            if (this.value != '') {
                $discountsum += parseFloat(this.value);
            }
        });
        $("#total_discount").val($discountsum);
    }

    //couunt all qty
    function allQty() {
        var $qtysum = 0;
        $('.count-qty').each(function() {
            if (this.value != '') {
                $qtysum += parseFloat(this.value);
            }
        });
        $("#total_qty").val($qtysum);
    }
    //count rate per item
    function allRateItem() {
        var $ratesum = 0;
        $('.count-rate-per-item').each(function() {
            if (this.value != '') {
                $ratesum += parseFloat(this.value);
            }
        });
        $("#total_rate_per_item").val($ratesum);
    }

    //count all total
    function allTotal() {
        var $totalsum = 0;
        $('.count-total').each(function() {
            if (this.value != '') {
                $totalsum += parseFloat(this.value);
            }
        });
        $("#total_amount_qty").val($totalsum);
    }
</script>
<script>
    function myFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<!-- /hide code -->
<script src="<?= base_url(); ?>assets/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).on('click', '#asset_name1', function() {
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(document).on('click', '.add_product', function() {
        var id = $(this).attr('data-id');
        //alert(id);
        getGST(id);
        getTotalAmount();
        var name = $(this).attr('data-name');
        $('#asset_name1').html('<option value="' + id + '">' + name + '</option>');
        $('#myModal').modal('hide');
        allQty();
        allRateItem();
        allTotal();
        allDiscount();
        allTaxable();
        allGst();
        allAmtGst();
        allShipping();
        allTotalTax();
        allTotalAmount();

    });
    /*$(document).ready(function(){

         $('#asset_name1').select2({
            placeholder: 'Select an option'
          });
    });*/
    jQuery(document).on('change', '.js-example-basic-single', function() {
        sameAsBilling();
    });

    jQuery(document).on('click', '#add_invoice2', function() {
        $('#save_finish_body').html('');
        $("#myForm").submit();
    });

    jQuery(document).on('click', '#edit_save_btn', function() {
        $('#save_finish_body').html('');
        $("#myForm").submit();
    });
    jQuery(document).on('click', '#save_finish', function() {
        $('#save_finish_body').html('<input type="hidden" name="save_finish" value="save_finish">');
        $("#myForm").submit();
    });
    jQuery('#quantity1').keyup(function() {
        //alert('hi');
        getTotalAmount();
        getDiscountAmount();
    });
</script>






<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>

<script>
    var site_url = $('#site_url').val();

    function sameAsBilling() {
        var same_as_billing = $('#same_as_billing').is(':checked');
        var receiver_email_address = $('#receiver_email_address').val();
        var receiver_mobile_no = $('#receiver_mobile_no').val();
        var receiver_state = $('#receiver_state').val();
        var state_code = $('#state_code').val();
        var gst_unique_number_receiver = $('#gst_unique_number_receiver').val();
        var address_receiver = $('#address_receiver').val();
        if (same_as_billing == true) {
            $('#consignee_state').attr('readonly', 'readonly');
            $('#consignee_state').val(receiver_state);
            $('#consignee_email').attr('readonly', 'readonly');
            $('#consignee_mobile_number').attr('readonly', 'readonly');
            $('#consignee_email').val(receiver_email_address);
            $('#consignee_mobile_number').val(receiver_mobile_no);
            $('#state_code_consignee').attr('readonly', 'readonly');
            $('#state_code_consignee').val(state_code);
            $('#gst_unique_number_consignee').attr('readonly', 'readonly');
            $('#gst_unique_number_consignee').val(gst_unique_number_receiver);
            $('#address_consignee').attr('readonly', 'readonly');
            $('#address_consignee').val(address_receiver);
        } else {
            $('#consignee_state').removeAttr('readonly');
            $('#consignee_state').val('');
            $('#consignee_email').removeAttr('readonly');
            $('#consignee_email').val('');
            $('#consignee_mobile_number').removeAttr('readonly');
            $('#consignee_mobile_number').val('');
            $('#state_code_consignee').removeAttr('readonly');
            $('#state_code_consignee').val('');
            $('#gst_unique_number_consignee').removeAttr('readonly');
            $('#gst_unique_number_consignee').val('');
            $('#address_consignee').removeAttr('readonly');
            $('#address_consignee').val('');
        }
    }

    function checkValidation(type) {
        //alert("ji");
        var gst_number = $('#gst_number').val();
        var name = $('#name').val();
        var serial_no_invoice = $('#serial_no_invoice').val();
        var address = $('#address').val();
        var invoice_sales_type = $('#invoice_sales_type').val();
        var invoice_payment_mode = $('#invoice_payment_mode').val();

        var receiver_name = $('#receiver_name').val();
        var receiver_email_address = $('#receiver_email_address').val();
        var receiver_mobile_no = $('#receiver_mobile_no').val();
        var receiver_state = $('#receiver_state').val();
        var state_code = $('#state_code').val();
        var gst_unique_number_receiver = $('#gst_unique_number_receiver').val();
        var address_receiver = $('#address_receiver').val();

        var consignee_name = $('#consignee_name').val();
        var consignee_email = $('#consignee_email').val();
        var consignee_mobile_number = $('#consignee_mobile_number').val();
        var consignee_state = $('#consignee_state').val();
        var state_code_consignee = $('#state_code_consignee').val();
        var gst_unique_number_consignee = $('#gst_unique_number_consignee').val();
        var address_consignee = $('#address_consignee').val();

        var asset_name = $('#asset_name1').val();
        var hsn_code = $('#hsn_code1').val();
        var quantity = $('#quantity1').val();
        var unit = $('#unit1').val();
        var rate_per_item = $('#rate_per_item1').val();
        var total = $('#total1').val();
        var discount_1 = $('#discount_1_1').val();
        var discount_2 = $('#discount_2_1').val();
        var discount_3 = $('#discount_3_1').val();
        var discount_amount = $('#discount_amount1').val();
        var taxable_amount = $('#taxable_amount1').val();
        var cgst_rate = $('#cgst_rate1').val();
        var cgst_amount = $('#cgst_amount1').val();
        var sgst_rate = $('#sgst_rate1').val();
        var sgst_amount = $('#sgst_amount1').val();
        var igst_rate = $('#igst_rate1').val();
        var igst_amount = $('#igst_amount1').val();
        var totaltax = $('#total_tax1').val();
        var netamount = $('#net_amount1').val();
        var shipping_charge = $('#shipping_charge1').val();

        if (gst_number == '') {
            $("#error_gst_number").fadeIn().html("Please enter gst number");
            setTimeout(function() {
                $("#error_gst_number").html("&nbsp;");
            }, 5000)
            $("#gst_number").focus();
            return false;
        } else if (name == '') {
            $("#error_name").fadeIn().html("Please enter name");
            setTimeout(function() {
                $("#error_name").html("&nbsp;");
            }, 5000)
            $("#name").focus();
            return false;
        } else if (serial_no_invoice == '') {
            $("#error_serial_no_invoice").fadeIn().html("Please enter serial no invoice");
            setTimeout(function() {
                $("#error_serial_no_invoice").html("&nbsp;");
            }, 5000)
            $("#serial_no_invoice").focus();
            return false;
        } else if (address == '') {
            $("#error_address").fadeIn().html("Please enter address");
            setTimeout(function() {
                $("#error_address").html("&nbsp;");
            }, 5000)
            $("#address").focus();
            return false;
        }
        else if (invoice_sales_type == ''){
            $('#error_invoice_sales_type').fadeIn().html("Please select Invoice  sales type");
            setTimeout(function() {
                $('#error_invoice_sales_type').html("&nbsp;");
            }, 5000);
            $('#invoice_sales_type').focus();
            return false;
        }
        else if (invoice_payment_mode == ''){
            $('#error_invoice_payment_mode').fadeIn().html("Please select Invoice  payment mode");
            setTimeout(function() {
                $('#error_invoice_payment_mode').html("&nbsp;");
            }, 5000);
            $('#invoice_payment_mode').focus();
            return false;
        }
        else if (receiver_name == '') {
            $("#error_receiver_name").fadeIn().html("Please enter receiver name");
            setTimeout(function() {
                $("#error_receiver_name").html("&nbsp;");
            }, 5000)
            $("#receiver_name").focus();
            return false;
        } else if (receiver_state == '') {
            $("#error_receiver_state").fadeIn().html("Please select state");
            setTimeout(function() {
                $("#error_receiver_state").html("&nbsp;");
            }, 5000)
            $("#receiver_state").focus();
            return false;
        } else if (consignee_name == '') {
            $("#error_consignee_name").fadeIn().html("Please enter consignee name");
            setTimeout(function() {
                $("#error_consignee_name").html("&nbsp;");
            }, 5000)
            $("#consignee_name").focus();
            return false;
        } else if (consignee_state == '') {
            $("#error_consignee_state").fadeIn().html("Please select state");
            setTimeout(function() {
                $("#error_consignee_state").html("&nbsp;");
            }, 5000)
            $("#consignee_state").focus();
            return false;
        } else if (asset_name == '') {
            $("#error_asset_name").fadeIn().html("Please select product");
            setTimeout(function() {
                $("#error_asset_name").html("&nbsp;");
            }, 5000)
            $("#asset_name1").focus();
            return false;
        } else if (hsn_code == '') {
            $("#error_hsn_code").fadeIn().html("Please enter HSN code");
            setTimeout(function() {
                $("#error_hsn_code").html("&nbsp;");
            }, 5000)
            $("#hsn_code1").focus();
            return false;
        } else if (quantity == '') {
            $("#error_quantity").fadeIn().html("Please enter quantity");
            setTimeout(function() {
                $("#error_quantity").html("&nbsp;");
            }, 5000)
            $("#quantity1").focus();
            return false;
        } else if (rate_per_item == '') {
            $("#error_rate_per_item").fadeIn().html("Please enter rate per item");
            setTimeout(function() {
                $("#error_rate_per_item").html("&nbsp;");
            }, 5000)
            $("#rate_per_item1").focus();
            return false;
        } else if (total == '') {
            $("#error_total").fadeIn().html("Please enter total");
            setTimeout(function() {
                $("#error_total").html("&nbsp;");
            }, 5000)
            $("#total1").focus();
            return false;
        } else if (discount_1 == '') {
            $("#error_discount_1").fadeIn().html("Please select discount 1");
            setTimeout(function() {
                $("#error_discount_1").html("&nbsp;");
            }, 5000)
            $("#discount_1_1").focus();
            return false;
        } else if (discount_2 == '') {
            $("#error_discount_1").fadeIn().html("Please select discount 2");
            setTimeout(function() {
                $("#error_discount_1").html("&nbsp;");
            }, 5000)
            $("#discount_2_1").focus();
            return false;
        } else if (discount_3 == '') {
            $("#error_discount_1").fadeIn().html("Please select discount 3");
            setTimeout(function() {
                $("#error_discount_1").html("&nbsp;");
            }, 5000)
            $("#discount_3_1").focus();
            return false;
        } else if (discount_amount == '') {
            $("#error_discount_1").fadeIn().html("Please enter discount amount");
            setTimeout(function() {
                $("#error_discount_1").html("&nbsp;");
            }, 5000)
            $("#discount_amount1").focus();
            return false;
        } else if (taxable_amount == '') {
            $("#error_taxable_amount").fadeIn().html("Please enter taxable amount");
            setTimeout(function() {
                $("#error_taxable_amount").html("&nbsp;");
            }, 5000)
            $("#taxable_amount1").focus();
            return false;
        } else if (cgst_rate == '') {
            $("#error_cgst_rate").fadeIn().html("Please enter cgst");
            setTimeout(function() {
                $("#error_cgst_rate").html("&nbsp;");
            }, 5000)
            $("#cgst_rate1").focus();
            return false;
        } else if (cgst_amount == '') {
            $("#error_cgst_rate").fadeIn().html("Please enter cgst amount");
            setTimeout(function() {
                $("#error_cgst_rate").html("&nbsp;");
            }, 5000)
            $("#cgst_amount1").focus();
            return false;
        } else if (sgst_rate == '') {
            $("#error_cgst_rate").fadeIn().html("Please enter sgst rate");
            setTimeout(function() {
                $("#error_cgst_rate").html("&nbsp;");
            }, 5000)
            $("#sgst_rate1").focus();
            return false;
        } else if (sgst_amount == '') {
            $("#error_cgst_rate").fadeIn().html("Please enter sgst amount");
            setTimeout(function() {
                $("#error_cgst_rate").html("&nbsp;");
            }, 5000)
            $("#sgst_amount1").focus();
            return false;
        } else if (igst_rate == '') {
            $("#error_cgst_rate").fadeIn().html("Please enter igst rate");
            setTimeout(function() {
                $("#error_cgst_rate").html("&nbsp;");
            }, 5000)
            $("#igst_rate1").focus();
            return false;
        } else if (igst_amount == '') {
            $("#error_cgst_rate").fadeIn().html("Please enter igst amount");
            setTimeout(function() {
                $("#error_cgst_rate").html("&nbsp;");
            }, 5000)
            $("#igst_amount1").focus();
            return false;
        } else if (totaltax == '') {
            $("#error_total_tax").fadeIn().html("Please total tax");
            setTimeout(function() {
                $("#error_total_tax").html("&nbsp;");
            }, 5000)
            $("#total_tax1").focus();
            return false;
        } else if (netamount == '') {
            $("#error_net_amount").fadeIn().html("Please enter net amount");
            setTimeout(function() {
                $("#error_net_amount").html("&nbsp;");
            }, 5000)
            $("#net_amount1").focus();
            return false;
        } else if (shipping_charge == '') {
            $("#error_shipping_charge").fadeIn().html("Please enter other charge");
            setTimeout(function() {
                $("#error_shipping_charge").html("&nbsp;");
            }, 5000)
            $("#shipping_charge1").focus();
            return false;
        } else {
            if (type == 'save_n_next') {
                //$('#submit_save_n_next').click();
                return true;
            } else {
                $('#submit_save').click();
            }
        }
    }

    function save(type) {
        $('#type_submit').val(type);
        $('#myForm').ajaxForm(function(returndata) {
            //alert(returndata);
            var obj = jQuery.parseJSON(returndata);
            if (obj.success == 1 && obj.invoice_id != '') {
                $('#invoice_id').val(obj.invoice_id);

                $('#asset_name1').val('');
                $('#hsn_code1').val('');
                $('#quantity1').val('');
                $('#unit1').val('');
                $('#rate_per_item1').val('');
                $('#total1').val('');
                $('#discount_1_1').val('');
                $('#discount_2_1').val('');
                $('#discount_3_1').val('');
                $('#discount_amount1').val('');
                $('#taxable_amount1').val('');
                $('#cgst_rate1').val('');
                $('#cgst_amount1').val('');
                $('#sgst_rate1').val('');
                $('#sgst_amount1').val('');
                $('#igst_rate1').val('');
                $('#igst_amount1').val('');
                $('#amount_after_gst1').val('');
                $('#shipping_charge1').val('');
                $('#total_tax1').val('');
                $('#net_amount1').val('');

            } else {
                window.location = site_url + "/Invoice";
            }
        });
    }
</script>



<script type="text/javascript">
    function countGst() {
        // for each row:
        var product = $('#asset_name1').val();
        if (product != '') {
            getGST(product);
        }

    }

    function getGST(val, len) {
        var site_url = $('#site_url').val();
        var dataString = "product_id=" + val;
        //alert(dataString);

        var consignee_state = "<?= $_SESSION['ASSETSTRACKING']['state_id'] ?>";
        var receiver_state = $('#receiver_state').val();

        var url = site_url + "/Invoice/getGST";
        $.post(url, dataString, function(returndata) {
            //alert(returndata);
            var obj = jQuery.parseJSON(returndata);
            //$('#gstPercent').val(obj.gst_percent);
            if (receiver_state == consignee_state || receiver_state == '') {
                var half_gst = obj.gst_percent / 2;
                $('#cgst_rate1').val(half_gst);
                $('#sgst_rate1').val(half_gst);

                $('#igst_rate1').val(0);
            } else {
                $('#igst_rate1').val(obj.gst_percent);
                $('#cgst_rate1').val(0);
                $('#sgst_rate1').val(0);
            }

            $('#gstPercent').val(obj.gst_percent);
            $('#hsn_code1').val(obj.hsn);
            $('#rate_per_item1').val(obj.product_mrp);

            $('#available_quantity1').val(obj.quantity);
            getDiscountAmount();
        });
    }

    $(document).on('keyup','.barcode',function(e){
          /* ENTER PRESSED*/
          if (e.keyCode == 13) {

            var dataString = "barcode="+this.value;
            var index = $(this).closest('td').parent()[0].sectionRowIndex;

            var url = "<?php echo site_url('Invoice/checkBarcode'); ?>";
            $.post(url, dataString, function(returndata){
              //alert(returndata);
              var obj = jQuery.parseJSON(returndata);
              
              if(obj.success == "1")
              {

                getGST(obj.id);
                getTotalAmount();
                var name = $(this).attr('data-name');
                $('#asset_name1').html('<option value="' + obj.id + '">' + obj.name + '</option>');
                $('#myModal').modal('hide');
                allQty();
                allRateItem();
                allTotal();
                allDiscount();
                allTaxable();
                allGst();
                allAmtGst();
                allShipping();
                allTotalTax();
                allTotalAmount();

              } else {
                $('.barcode_error').eq(index).html("Invalid Barcode").fadeIn();
                setTimeout(function(){$(".barcode_error").eq(index).fadeOut()},5000);
              }

            });
          }
      });

    function getTotalAmount() {
        var rate_per_item = $('#rate_per_item1').val();
        var quantity = $('#quantity1').val();
        //alert(quantity);
        /*var available_quantity = $('#available_quantity1').val();
        if(quantity > available_quantity) {
          $("#error_quantity").fadeIn().html("Please enter quantity less or equal to available quantity");
          setTimeout(function(){$("#error_quantity").html("&nbsp;");},5000)
          $("#quantity1").focus();
        } else {*/
        if (rate_per_item != '' && quantity != '') {
            var total_amount = parseFloat(quantity) * parseFloat(rate_per_item);
        } else {
            var total_amount = '';
        }
        //}
        $('#total1').val(total_amount);
    }

    function getDiscountAmount(len) {
        var gst_percent = $('#gstPercent').val();
        var cgst_rate = $('#cgst_rate1').val();
        var sgst_rate = $('#sgst_rate1').val();
        var igst_rate = $('#igst_rate1').val();

        var total = $('#total1').val();
        if (total == '') {
            var total_amt = 0;
        } else {
            var total_amt = total;
        }
        var discount_1 = $('#discount_1_1').val();
        var discount_2 = $('#discount_2_1').val();
        var discount_3 = $('#discount_3_1').val();

        var discount_amount_1 = (discount_1 * total_amt) / 100;
        var discount_amount_2 = (discount_2 * total_amt) / 100;
        var discount_amount_3 = (discount_3 * total_amt) / 100;

        var total_discount_amount = parseFloat(discount_amount_1) + parseFloat(discount_amount_2) + parseFloat(discount_amount_3);
        $('#discount_amount1').val(total_discount_amount.toFixed(2));

        var taxable_amount = parseFloat(total_amt) - parseFloat(total_discount_amount);
        $('#taxable_amount1').val(taxable_amount.toFixed(2));

        var gst_amt = (gst_percent * taxable_amount) / 100;
        $('#gstAmount').val(gst_amt.toFixed(2));

        if (igst_rate == 0 || igst_rate == '') {
            var amt = (cgst_rate * taxable_amount) / 100;
            $('#cgst_amount1').val(amt.toFixed(2));
            $('#sgst_amount1').val(amt.toFixed(2));

            $('#igst_amount1').val(0);
            var total_tax = parseFloat(amt) + parseFloat(amt);
            $('#total_tax1').val(total_tax.toFixed(2));
            var amount_after_gst = parseFloat(taxable_amount) + parseFloat(total_tax);
            $('#amount_after_gst1').val(amount_after_gst.toFixed(2));
        } else {
            var amt = (igst_rate * taxable_amount) / 100;
            $('#igst_amount1').val(amt.toFixed(2));
            $('#cgst_amount1').val(0);
            $('#sgst_amount1').val(0);

            $('#total_tax1').val(amt.toFixed(2));
            var amount_after_gst = parseFloat(taxable_amount) + parseFloat(amt);
            $('#amount_after_gst1').val(amount_after_gst.toFixed(2));
        }
        getNetAmountAfterShipping();
    }

    function getNetAmountAfterShipping() {
        var amount_after_gst = $('#amount_after_gst1').val();
        var shipping_charge = $('#shipping_charge1').val();
        if (amount_after_gst != '') {
            var netamount = parseFloat(amount_after_gst) + parseFloat(shipping_charge);
            $('#net_amount1').val(netamount.toFixed(2));
        } else {
            $('#net_amount1').val(0);
        }
    }

    function sameAsBilling() {
        if ($("#same_as_billing").prop('checked') == true) {
            var receiver_name = $('#receiver_name').val();
            var receiver_email_address = $('#receiver_email_address').val();
            var receiver_mobile_no = $('#receiver_mobile_no').val();
            var receiver_state = $('#receiver_state').val();
            var state_code = $('#state_code').val();
            var gst_unique_number_receiver = $('#gst_unique_number_receiver').val();
            var address_receiver = $('#address_receiver').val();

            $('#consignee_name').val(receiver_name);
            $('#consignee_email').val(receiver_email_address);
            $('#consignee_mobile_number').val(receiver_mobile_no);
            $('#consignee_state').val(receiver_state);
            $('#state_code_consignee').val(state_code);
            $('#gst_unique_number_consignee').val(gst_unique_number_receiver);
            $('#address_consignee').val(address_receiver);
        } else {
            $('#consignee_name').val('');
            $('#consignee_email').val('');
            $('#consignee_mobile_number').val('');
            $('#consignee_state').val('');
            $('#state_code_consignee').val('');
            $('#gst_unique_number_consignee').val('');
            $('#address_consignee').val('');
        }

    }

    function checkInvoiceNo() {
        var validTxt = $("#newInvoiceNoVTxt");
        var newInvoiceNo = $("#newInvoiceNo").val();
        if(newInvoiceNo && newInvoiceNo.length > 2) {
            $.ajax({
                url: "/invoice/checkUid",
                method: "POST",
                data: "invoice_no="+newInvoiceNo,
                success: function(data){
                    if(data.status === "success") {
                        var holder = document.createElement("div");
                        holder.classList.add("col-md-6", "hideSibling")
                        holder.style.padding = 0;

                        var labelEl = document.createElement("label");
                        labelEl.innerText = "Invoice No.";

                        var inputEl = document.createElement("input");
                        inputEl.type = "text";
                        inputEl.name = "invoice_no";
                        inputEl.classList.add("form-control");
                        inputEl.style.height = "1.5em";
                        inputEl.style.fontSize = "1em";
                        inputEl.style.padding = "5px";
                        inputEl.value = newInvoiceNo;
                        inputEl.readOnly = true;

                        holder.appendChild(labelEl);
                        holder.appendChild(inputEl);

                        var invoiceIDSelector = document.getElementById("invoiceIDSelector");
                        invoiceIDSelector.insertBefore(holder, invoiceIDSelector.childNodes[0]);

                        $("#createIDModal").modal("toggle");
                    } else {
                        updateNewInvoiceNoVTxt(data.message);
                    }
                },
                error: function(error){}
            });
        } else {
            updateNewInvoiceNoVTxt("Please type invoice number, at least 3 characters.");
        }
    }

    function updateNewInvoiceNoVTxt(msg) {
        var validTxt = $("#newInvoiceNoVTxt");
        var newMsg = msg ? msg : "";
        validTxt.text(newMsg);
    }
</script>