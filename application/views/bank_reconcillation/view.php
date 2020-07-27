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
</style>
<!-- END BREADCRUMB -->

 <!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">  
    <div class="row">
        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bank Summary Details</h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                        <li><a href="<?= site_url('Bank_Reconcillation/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
            </div>                                                

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
                                        <th>Product Name</th>
                                        <th>Product Name</th>
                                        <th>Product Name</th>

                                        <th>HSN Code</th>                             
                                        <th>Quantity</th>
                                        <th>Unit</th>  
                                        <th>Rate per price</th>
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
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php 
                                    foreach ($invoice_details as $detail) {
                                        $product = $this->Crud_model->GetData("assets","","id='".$detail->product_id."'","","","","1");
                                    ?>
                                    <tr>
                                        <td><?php echo $product->asset_name; ?></td>
                                        <td><?php echo $product->asset_name; ?></td>
                                        <td><?php echo $product->asset_name; ?></td>

                                        <td><?php echo $detail->hsn_code; ?></td>
                                        <td><?php echo $detail->quantity; ?></td>
                                        <td><?php echo $detail->unit; ?></td>
                                        <td><?php echo "Rs. ".$detail->rate_per_item; ?></td>
                                        <td><?php echo "Rs. ".$detail->total; ?></td>
                                        <td><?php echo $detail->discount_1."%".", ". $detail->discount_2."%".", ". $detail->discount_3."%"; ?></td>
                                        <td><?php echo "Rs. ".$detail->discount_amount; ?></td>
                                        <td><?php echo "Rs. ".$detail->taxable; ?></td>
                                        <td><?php echo $detail->cgst_rate; ?></td>
                                        <td><?php echo "Rs. ".$detail->cgst_amount; ?></td>
                                        <td><?php echo $detail->sgst_rate; ?></td>
                                        <td><?php echo "Rs. ".$detail->sgst_amount; ?></td>
                                        <td><?php echo $detail->igst_rate; ?></td>
                                        <td><?php echo "Rs. ".$detail->igst_amount; ?></td>
                                        <td><?php echo "Rs. ".$detail->net_amount; ?></td>
                                    </tr>       
                                    <?php 
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php 
                            }
                            ?>
                        </div>

                        <div class="clearfix">&nbsp;</div>
                    </div>

                </div>
            </div>

            <div class="panel panel-default">
              
               <div class="panel-body">
                <div class="row">

                <div class="col-md-2">
                <div class="form-group">
                  <label class="col-md-11"> Type of Deposit</label>
                  <div class="col-md-11">
                    <?php if(!empty($bank_reconcillation)) { echo $bank_reconcillation->type_of_deposit; } ?>
                  </div>
                </div>
              </div>

              <div class="col-md-10" style="padding: 0;">

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Amount Deposited in Bank </label>
                    <div class="col-md-11">
                      <?php if(!empty($bank_reconcillation)) { echo "Rs. ".number_format($bank_reconcillation->amount_deposited_in_bank,2); } ?>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> GST on Bank Commission </label>
                    <div class="col-md-11">
                      <?php if(!empty($bank_reconcillation)) { echo $bank_reconcillation->gst_on_bank_commission; } ?>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Bank Commission <span style="color: red;">*</span> <span id="error_bank_commission" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <?php if(!empty($bank_reconcillation)) { echo $bank_reconcillation->bank_commission; } ?>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Date of Deposit </label>
                    <div class="col-md-11">
                      <?php if(!empty($bank_reconcillation)) { echo $bank_reconcillation->date_of_deposit; } ?>
                    </div>
                  </div>
                </div>

              </div>
            </div>
              </div>
            </div>
        </div>
    </div>

</div>
<?php $this->load->view('common/footer');?>             <!-- END PAGE CONTENT WRAPPER -->


