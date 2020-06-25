 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<style type="text/css">
    .bordered{
        margin-bottom: 20px;
        margin-top: 20px;
        border:1px solid #eee;
        width: 100%;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/lightbox/lightbox.min.css') ?>">
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                
                    <div class="row">
                        <div class="col-md-12">
                          <?php $this->load->view('vendor/common') ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading."-<sapn style='color:green'>". $po->shop_name.'</span>' ?></strong></h3>
                                    <ul class="panel-controls">
                                       <li><a onclick="window.history.back()"   title="Back" data-toggle="tooltip"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                     <div class="panel-body"> 
                        <table class="table table-striped">
                          <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Asset Type</th>
                                    <th>PO Number</th>
                                    <th>PO Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $po->shop_name; ?></td>
                                    <td><?= $po->type; ?></td>
                                    <td><?= $po->order_number; ?></td>
                                    <td><?= $po->purchase_date; ?></td>
                                    <td><?= $po->status; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php foreach($purchaseReceived as $receive){  ?>
                 <div class="panel panel-default">
                <div class="panel-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12"><b>Lot No.</b>: <?= $receive->lot_no; ?><br/><br/></div>
                            <div class="col-md-3"><b>Driver Name</b>: <?= $receive->driver_name;; ?><br/></div>
                            <div class="col-md-3"><b>Vehicle Number</b>: <?= $receive->vehicle_number; ?><br/></div>
                            <div class="col-md-2"><b>Bill No.</b>: <?= $receive->bill_no; ?></div>
                            <div class="col-md-2"><b>Bill Copy.</b>: <a class="example-image-link" data-lightbox="example-1" href="<?= base_url('uploads/bill_copy/'.$receive->bill_copy); ?>"><span class="example-image">View</span></a></div>
                            <div class="col-md-2"><b><a target="_blank" href="<?= site_url('Purchase_orders/print_lot/'.$receive->id) ?>">Print</a></b></div>
                        </div>
                    </div><br>
                    <div class="">
                        <table class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Asset</th>
                                    <th>Quantity</th>
                                    <th>Received Qty</th>
                                    <th>Unit</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $prd = $this->Crud_model->GetData('purchase_received_details','',"purchase_received_id='".$receive->purchase_received_id."'");
                                    $sr=0; if(!empty($prd)){ foreach($prd as $row){                                     
                                    $pod = $this->Crud_model->GetData('purchase_order_details','',"purchase_order_id='".$receive->purchase_order_id."' and asset_id='".$row->asset_id."'",'','','','single');
                                    $assets = $this->VendorPurchaseOrders_model->GetPrdLotDetails("a.id='".$row->asset_id."'");
                                ?>
                                <tr>
                                    <td><?= ++$sr; ?></td>
                                    <td><?= $assets->asset_name; ?></td>
                                    <td><?= $pod->quantity; ?></td>
                                    <td><?= $row->received_qty; ?></td>                                    
                                    <td><?= $assets->unit; ?></td>                                    
                                    <td><?php if($row->created=='0000-00-00'){ echo '-'; } else { echo date('d-m-Y',strtotime($row->created)); } ?></td>
                                    <td><i class="fa fa-inr">&nbsp;</i><?php if(!empty($row->rate)){ echo number_format($row->rate,2); } else { echo '0.00'; }  ?></td>
                                </tr>
                                <?php } } ?>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><b>Amount</b></td>
                                    <td><b><i class="fa fa-inr">&nbsp;</i><?= number_format($receive->amount,2); ?></b></td>
                                </tr>
                             <!--    <tr>
                                    <td colspan="5"></td>
                                    <td><b>CGST</b></td>
                                    <td><b>(+)<?= $receive->cgst.'%'; ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><b>SGST</b></td>
                                    <td><b>(+)<?= $receive->sgst.'%'; ?></b></td>
                                </tr> -->
                                <tr>
                                    <td colspan="5"></td>
                                    <td style="border-bottom:1px solid #000"><b>Total Amount</b></td>
                                    <td style="border-bottom:1px solid #000"><b><i class="fa fa-inr">&nbsp;</i><?= number_format($receive->total_amount,2); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><b>Labour Charge</b></td>
                                    <td><b><i class="fa fa-inr">&nbsp;</i><?= number_format($receive->labour_charge,2); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><b>Extra Vendor Charge</b></td>
                                    <td><b><i class="fa fa-inr">&nbsp;</i><?= number_format($receive->extra_vendor_charge,2); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><b>Final Amount</b></td>
                                    <?php $finalAmt = $receive->labour_charge + $receive->extra_vendor_charge + $receive->total_amount; ?>
                                    <td><b><i class="fa fa-inr">&nbsp;</i><?= number_format($finalAmt,2); ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><br/>
                <?php } ?>
        </div>
    </div>
</div>
<script>
    var url = '';
    var actioncolumn='';
</script>
<script src="<?= base_url('assets/lightbox/lightbox-plus-jquery.min.js'); ?>" ></script>
<?php $this->load->view('common/footer');?>
