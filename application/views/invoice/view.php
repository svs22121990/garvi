 <?php
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<style type="text/css">
div.scrollable {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: auto;
}

.form-group{
    margin-top: 10px;
}
</style>
<!-- END BREADCRUMB -->

 <!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">  
    <div class="row">
        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Invoice Details</h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                    	<!-- <li><a href="<?= base_url(); ?>index.php/Invoice/invoicePdf/<?= $id; ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li> -->
                    	<li><a href="<?= base_url(); ?>index.php/Invoice/viewPdf/<?= $id; ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li>
                        <li><a href="<?= site_url('Invoice/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12" style="padding: 0;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-md-11">GST Number</label>
                                    <div class="col-md-11">
                                        <?php echo $invoice->gst_number; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-md-11">Name</label>
                                    <div class="col-md-11">
                                        <?php echo $invoice->name; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-md-11">Sales Type</label>
                                    <div class="col-md-11">
                                        <?=$salesType->label?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-md-11">Payment Mode</label>
                                    <div class="col-md-11">
                                        <?=$paymentMode->type?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-md-11">Serial No of Invoice</label>
                                    <div class="col-md-11">
                                        <?php echo $invoice->serial_no_of_invoice; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-md-11">Address</label>
                                    <div class="col-md-11">
                                        <?php echo $invoice->address; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><strong>Billing Address</strong></h3>
                 
               </div>
               <div class="panel-body">
                <div class="row">
                  <div class="col-md-12" style="padding: 0;">

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">Invoice no.</label>
                        <div class="col-md-11">
                          <?php echo $invoice->invoice_no; ?>
                        </div>
                      </div>
                    </div>


                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">Name</label>
                        <div class="col-md-11">
                          <?php echo $invoice->receiver_bill_name; ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">Billing Address</label>
                        <div class="col-md-11">
                          <?php echo $invoice->receiver_address ?>
                        </div>
                      </div>
                    </div>

                    <!--<div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">Email Address</label>
                        <div class="col-md-11">
                          <?php echo $invoice->receiver_email_address; ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">Mobile Number</label>
                        <div class="col-md-11">
                          <?php echo $invoice->receiver_mobile_number; ?>
                        </div>
                      </div>
                    </div>-->

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">State</label>
                        <div class="col-md-11">
                          <?php echo $receiver_state->state_name ?>
                        </div>
                      </div>
                    </div>

                    <!--<div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">State Code</label>
                        <div class="col-md-11">
                          <?php echo $invoice->receiver_state_code ?>
                        </div>
                      </div>
                    </div>-->

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">GSTIN / Unique No</label>
                        <div class="col-md-11">
                          <?php echo $invoice->receiver_gst_number ?>
                        </div>
                      </div>
                    </div>

                    

                  </div>
                </div>
              </div>
            </div>
            <?php $type = $_SESSION[SESSION_NAME]['type']; ?>
            <?php if($type!='User'): ?>
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
                        <label class="col-md-11">Name</label>
                        <div class="col-md-11">
                          <?php echo $invoice->consignee_name ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">Email Address</label>
                        <div class="col-md-11">
                          <?php echo $invoice->consignee_email_address; ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">Mobile Number</label>
                        <div class="col-md-11">
                          <?php echo $invoice->consignee_mobile_number; ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">State</label>
                        <div class="col-md-11">
                          <?php echo $consignee_state->state_name; ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">State Code</label>
                        <div class="col-md-11">
                          <?php echo $invoice->consignee_state_code ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">GSTIN / Unique No</label>
                        <div class="col-md-11">
                          <?php echo $invoice->consignee_gst_nunber ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="col-md-11">Shipping Address</label>
                        <div class="col-md-11">
                          <?php echo $invoice->consignee_address ?>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Product Details</strong></h3>
                 
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="table-responsive">
                            <?php 

                            if(!empty($invoice_details)) {
                            ?>
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr> 
                                        <th>Sr. no</th>                       
                                        <th>Product Name</th>
                                        <th>HSN Code</th>                             
                                        <th>Quantity</th>
                                        <th>Unit</th>  
                                        <th>Price</th>
                                        <th>ToTal</th>
                                        <th>Discount</th>
                                        <th>Discount Amount</th>
                                        <th>Taxable Amount</th>
                                        <th>CGST Rate</th>
                                        <th>CGST Amount</th>
                                        <th>SGST Rate</th>
                                        <th>SGST Amount</th>
                                        <th>IGST Rate</th>
                                        <th>IGST Amount</th>
                                        <th>Net Amount</th>
                                        <th> Shipping/Other Charge</th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php $i =1; $allTotal = 0; $allqty=0; $allRatPerItem=0; $allDiscountAmt=0; $alltotal = 0;
                                    $allTaxabl = 0; $allCGST = 0; $allSGST = 0; $allIGST =0;
                                    foreach ($invoice_details as $detail) {
                                        $product = $this->Crud_model->GetData("assets","","id='".$detail->product_id."'","","","","1");
                                    ?>
                                    <tr>
                                        <td>
                                        	<?= $i++; ?>
                                        </td>
                                        <td>
                                        	<?php echo $product->asset_name; ?>
                                        </td>
                                        <td><?php echo $detail->hsn_code; ?></td>
                                        <td><?php echo $detail->quantity; 
                                        			$allqty += $detail->quantity; ?></td>
                                        <td><?php echo $detail->unit; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->rate_per_item,2); 
                                        $allRatPerItem += $detail->rate_per_item; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->total,2); 
                                        $alltotal +=$detail->total ?></td>
                                        <td><?php echo $detail->discount_1."%".", ". $detail->discount_2."%".", ". $detail->discount_3."%"; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->discount_amount,2);  
                                        $allDiscountAmt += $detail->discount_amount; ?></td>
                                        <td>
                                        	<?php echo "Rs. ".number_format($detail->taxable,2); 
                                        	$allTaxabl += $detail->taxable; ?>
                                        </td>
                                        <td><?php echo $detail->cgst_rate; 
                                         ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->cgst_amount,2); 
                                        $allCGST +=$detail->cgst_amount;?></td>
                                        <td><?php echo $detail->sgst_rate; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->sgst_amount,2); 
                                        $allSGST += $detail->sgst_amount; ?></td>
                                        <td><?php echo $detail->igst_rate; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->igst_amount,2); 
                                         $allIGST += $detail->igst_amount; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->net_amount,2); 
                                        $allTotal +=  $detail->net_amount;
                                        ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->shipping_charges,2);
                                            $allTotal +=  $detail->shipping_charges;
                                            ?></td>
                                    </tr>       
                                    <?php 
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                	<tr>
                                		<th></th>
                                		<th></th>
                                		<th></th>
                                		<th><?= $allqty ?></th>
                                		<th></th>
                                		<th><?= "Rs. ".number_format($allRatPerItem,2); ?></th>
                                		<th><?= "Rs. ".number_format($alltotal,2); ?></th>
                                		<th></th>
                                		<th><?= "Rs. ".number_format($allDiscountAmt,2); ?></th>
                                		<th><?= "Rs. ".number_format($allTaxabl,2); ?></th>
                                		<th></th>
                                		<th><?= "Rs. ".number_format($allCGST,2); ?></th>
                                		<th></th>
                                		<th><?= "Rs. ".number_format($allSGST,2); ?></th> 
                                		<th></th>
                                		<th><?= "Rs. ".number_format($allIGST,2); ?></th>
                                		<th><?= "Rs. ".number_format($allTotal,2); ?></th>
                                	</tr>
                                </tfoot>
                            </table>
                            <?php 
                            
                            }
                            ?>
                        </div>

                        <div class="clearfix">&nbsp;</div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<?php $this->load->view('common/footer');?>             <!-- END PAGE CONTENT WRAPPER -->


<script>
    $( document ).ready(function() {
    <?php if($this->session->flashdata('print')){ ?>
        window.open("<?= base_url() ?>index.php/Invoice/invoicePdf/<?= $id; ?>", '_blank');

    <?php } ?>
});
</script>