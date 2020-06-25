 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
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
                            
                          
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                                    <ul class="panel-controls">
                                        <li><a href="<?=site_url('Purchase_orders/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body"> 
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Vendor</th>
                                               <!--  <th>Asset Type</th> -->
                                                <?php if($po->quotation_id!='0') { ?>
                                                      <th>Quotation No</th>
                                                <?php } ?>   
                                                <?php if($po->branch_id!='0') { ?>
                                                      <th>Branch</th>
                                                <?php } ?>
                                                <th>PO Number</th>
                                                <th>PO Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                              
                                                <td><?= $po->shop_name; ?></td>
                                               <!--  <td><?= $po->type; ?></td> -->
                                                 <?php if($po->quotation_id!='0') { ?>
                                                      <td><?= $po->quotation_no; ?></td>
                                                <?php } ?>
                                                <?php if($po->branch_id!='0') { ?>
                                                      <td><?= $po->branch; ?></td>
                                                <?php } ?>
                                                <td><?= $po->order_number; ?></td>
                                                <td><?= date('d-m-Y',strtotime($po->purchase_date)); ?></td>
                                                <td><?= $po->status; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                <?php foreach($purchaseReceived as $receive){ ?>
                 <div class="panel panel-default">
                    <div class="panel-body"> 
                    <div class="row">
                        <?php 
                            $str = $receive->bill_copy; 
                            $exetension = substr($str, strrpos($str, '.') + 1);
                        ?>
                        <div class="col-md-12">
                            <div class="col-md-12"><b>Lot No.</b>: <?= $receive->lot_no; ?><br/><br/></div>
                            <div class="col-md-3"><b>Driver Name</b>: <?= $receive->driver_name;; ?><br/></div>
                            <div class="col-md-3"><b>Vehicle Number</b>: <?= $receive->vehicle_number; ?><br/></div>
                            <div class="col-md-2"><b>Bill No.</b>: <?= $receive->bill_no; ?></div>
                            <div class="col-md-2"><b>Bill Copy.</b>: 
                                <?php if($exetension=='pdf' || $exetension=='PDF'){ ?>  
                                <a href="<?= base_url('uploads/bill_copy/'.$receive->bill_copy); ?>" title="View File" target="_blank"><span class="example-image">View</span></a>
                                <?php } else { ?>
                                <a class="example-image-link" title="View File" data-lightbox="example-1" href="<?= base_url('uploads/bill_copy/'.$receive->bill_copy); ?>"><span class="example-image">View</span></a>
                                <?php } ?>
                                &nbsp;&nbsp;<a data-toggle="modal" data-target="#myModal" onclick="getValue(<?= $receive->id; ?>)" style="cursor: pointer;" title="Edit File"><i class="fa fa-edit"></i></a>
                            </div>
                            <div class="col-md-2">
                                <b><a target="_blank" href="<?= site_url('Purchase_orders/print_lot/'.$receive->prid) ?>">Print</a></b>
                            </div>
                        </div>
                    </div><br>
                    <div class="">
                        <table class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Brand</th>
                                    <th>Asset</th>
                                    <th>Quantity</th>
                                    <th>Received Qty</th>
                                    <th>Rate</th>
                                    <th>Price</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>CESS</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $prd = $this->Crud_model->GetData('purchase_received_details','',"purchase_received_id='".$receive->purchase_received_id."'");
                                    $sr=0; if(!empty($prd)){ foreach($prd as $row){ 
                                    $pod = $this->Crud_model->GetData('purchase_order_details','',"purchase_order_id='".$receive->purchase_order_id."' and asset_id='".$row->asset_id."'",'','','','single'); 
                                    
                                    $con = "a.id='".$row->asset_id."'";
                                    $assets = $this->Purchase_orders_model->GetPrdLotDetails($con);
                                ?>
                                <tr>
                                    <td><?= ++$sr; ?></td>
                                    <td><?= $assets->brand_name; ?></td>
                                    <td><?= $assets->asset_name; ?></td>
                                    <td><?= $pod->quantity; ?></td>
                                    <td><?= $row->received_qty.' '.$assets->unit; ?></td>
                                    <td><?= $row->per_unit_price; ?></td>
                                    <td><?= $row->price; ?></td>
                                    <td><?= $row->cgst.'%'; ?></td>
                                    <td><?= $row->sgst.'%'; ?></td>
                                    <td><?= $row->fess; ?></td>
                                    <td><?php if($row->created=='0000-00-00'){ echo '-'; } else { echo date('d-m-Y',strtotime($row->created)); } ?></td>
                                    <td><i class="fa fa-inr">&nbsp;</i><?php if(!empty($row->rate)){ echo number_format($row->rate,2); } else { echo '0.00'; }  ?></td>
                                </tr>
                                <?php }
                                 } ?>
                      
                                <tr>
                                    <td colspan="10"></td>
                                    <td style="border-bottom:1px solid #000"><b>Total Amount</b></td>
                                    <td style="border-bottom:1px solid #000"><b><i class="fa fa-inr">&nbsp;</i><?= number_format($receive->total_amount,2); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="10"></td>
                                    <td><b>Labour Charge</b></td>
                                    <td><b><i class="fa fa-inr">&nbsp;</i><?= number_format($receive->labour_charge,2); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="10"></td>
                                    <td><b>Extra Vendor Charge</b></td>
                                    <td><b><i class="fa fa-inr">&nbsp;</i><?= number_format($receive->extra_vendor_charge,2); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="10"></td>
                                    <td><b>Final Amount</b></td>
                                    <?php $finalAmt = $receive->labour_charge + $receive->extra_vendor_charge + $receive->total_amount; ?>
                                    <td><b><i class="fa fa-inr">&nbsp;</i><?= number_format($finalAmt,2); ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                <?php } ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form method="post" action="<?= site_url('Purchase_orders/image_update'); ?>" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Update File</h4>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <label>Bill copy <span style="color:red">*</span></label>
                    </div>
                </div>
                <div>
                    <div class="col-md-6">
                        <input type="file" id="bill_copy" name="bill_copy" class="form-control" >
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <span id="showEditError" style="color:#FF0000; font-size:14px" align="left"></span>&nbsp;
                    </div>
                </div>
            </div>
          </div>
          <input type="hidden" name="pid" value="<?= $id;?>">
          <input type="hidden" name="purchase_received_id" id="purchaseReceivedId">
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" onclick="return validation();">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    </form>
  </div>
</div>
<script>
    var url = '';
    var actioncolumn='';

</script>

<script src="<?= base_url('assets/lightbox/lightbox-plus-jquery.min.js'); ?>" ></script>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
    function validation() {
        var bill_copy = $("#bill_copy").val();

        if(bill_copy == '')
          {
            $("#showEditError").fadeIn().html("Please select bill_copy");
            setTimeout(function(){$("#showEditError").fadeOut();},3000);
            $("#bill_copy").focus();
            $("#myModal").show;
            return false; 
          }
    }

    function getValue(value)
    {
        //alert(value);return false;
        $("#purchaseReceivedId").val(value);
    }
</script>