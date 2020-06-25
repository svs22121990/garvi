 <?php  $this->load->view('common/header');
 $this->load->view('common/left_panel'); ?>
 <?php $created_by = $_SESSION[SESSION_NAME]['id']; ?> <!-- START BREADCRUMB -->
 <?= $breadcrumbs ?> <!-- END BREADCRUMB --> <!-- PAGE TITLE --> 
 <style type="text/css">
 .form-control{
  margin-bottom: 20px;
 }
 </style>
 <div class="page-title">                         <!-- <h3 class="panel-title"><?= $heading
 ?></h3> --> </div>  <!-- PAGE CONTENT WRAPPER --> 
 <form class="form-horizontal" method="post" action="<?php echo $action;?>" id="submit_save" enctype="multipart/form-data">           
 <div class="page-content-wrap">                
  <div class="row">
    <div class="col-md-12">                            
      

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
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
                                        <th>Product Type 1</th>
                                        <th>Product Type 2</th>

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
                                    $total_amt = 0;
                                    foreach ($invoice_details as $detail) {
                                        $product = $this->Crud_model->GetData("assets","","id='".$detail->product_id."'","","","","1");
                                        $total_amt = $total_amt + $detail->net_amount;
                                    ?>
                                    <tr>
                                        <td><?php echo $product->asset_name; ?></td>
                                        <td><?php echo $product->asset_name; ?></td>
                                        <td><?php echo $product->asset_name; ?></td>
                                        <td><?php echo $detail->hsn_code; ?></td>
                                        <td><?php echo $detail->quantity; ?></td>
                                        <td><?php echo $detail->unit; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->rate_per_item,2); ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->total,2); ?></td>
                                        <td><?php echo $detail->discount_1."%".", ". $detail->discount_2."%".", ". $detail->discount_3."%"; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->discount_amount,2); ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->taxable,2); ?></td>
                                        <td><?php echo $detail->cgst_rate; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->cgst_amount,2); ?></td>
                                        <td><?php echo $detail->sgst_rate; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->sgst_amount,2); ?></td>
                                        <td><?php echo $detail->igst_rate; ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->igst_amount,2); ?></td>
                                        <td><?php echo "Rs. ".number_format($detail->net_amount,2); ?></td>
                                    </tr>       
                                    <?php 
                                    }
                                    ?>
                                    <tr>
                                      <td colspan="15" class="text-right">Total Amount</td>
                                      <td colspan="16">
                                        <?php echo "Rs. ".number_format($total_amt,2); ?>
                                        <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $total_amt; ?>">    
                                      </td>
                                    </tr>
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
                  <label class="col-md-11"> Type of Deposit <span style="color: red;">*</span> <span id="error_type_of_deposit" style="color: red;"></span></label>
                  <div class="col-md-11">
                    <select name="type_of_deposit" id="type_of_deposit" class="form-control">
                      <option value="">Select type</option>
                      <option value="CASH">Cash</option>
                      <option value="CHEQUE">Cheque</option>
                      <option value="CARD">Card</option>
                    </select>

                  </div>
                </div>
              </div>

              <div class="col-md-10" style="padding: 0;">

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Amount Deposited in Bank <span style="color: red;">*</span> <span id="error_amount_deposited_in_bank" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="amount_deposited_in_bank" id="amount_deposited_in_bank" class="form-control" placeholder="Amount Deposited in Bank">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> GST on Bank Commission <span style="color: red;">*</span> <span id="error_gst_on_bank_commission" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="gst_on_bank_commission" id="gst_on_bank_commission" class="form-control" placeholder="GST on Bank Commission">
                      <!-- <input type="text" name="receiver_state" id="receiver_state" class="form-control" placeholder="State"> -->
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Bank Commission <span style="color: red;">*</span> <span id="error_bank_commission" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="bank_commission" id="bank_commission" class="form-control" placeholder="Bank Commission">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Date of Deposit <span style="color: red;">*</span> <span id="error_date_of_deposit" style="color: red;"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="date_of_deposit" id="date_of_deposit" class="form-control datepicker" placeholder="Date of Deposit">
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
    </div>
  </div>                                
  <div class="panel-footer">  
  <input type="hidden" name="invoice_id" id="invoice_id" value="<?php echo $invoice->id ?>">   
    <button class="btn btn-success" type="button" onclick="checkValidation();">Save</button>
    <a href="<?php echo site_url('Bank_Reconcillation'); ?>"><button class="btn btn-default" type="button">Cancel</button></a>
  </div>
</div>
</form>

</div>
</div>                    

</div>
<!-- END PAGE CONTENT WRAPPER -->




<?php $this->load->view('common/footer');?>

<script> 
var site_url = $('#site_url').val();
function checkValidation() {
  var amount_deposited_in_bank = $('#amount_deposited_in_bank').val();
  var total_amount = $('#total_amount').val();
  var gst_on_bank_commission = $('#gst_on_bank_commission').val();
  var bank_commission = $('#bank_commission').val();
  var date_of_deposit = $('#date_of_deposit').val();
  var type_of_deposit = $('#type_of_deposit').val();

  if (type_of_deposit === "") {
    $("#error_type_of_deposit").fadeIn().html("Please select the type of deposit");
    setTimeout(function(){$("#error_type_of_deposit").html("&nbsp;");},5000);
    $("#type_of_deposit").focus();
    return false;
  } else if(amount_deposited_in_bank=='') {
    $("#error_amount_deposited_in_bank").fadeIn().html("Please enter amount deposited in bank");
    setTimeout(function(){$("#error_amount_deposited_in_bank").html("&nbsp;");},5000)
    $("#amount_deposited_in_bank").focus();
    return false;
  } else if(amount_deposited_in_bank > total_amount) {
    $("#error_amount_deposited_in_bank").fadeIn().html("Please enter amount less or equal to total amount");
    setTimeout(function(){$("#error_amount_deposited_in_bank").html("&nbsp;");},5000)
    $("#amount_deposited_in_bank").focus();
    return false;
  } else if(gst_on_bank_commission=='') {
    $("#error_gst_on_bank_commission").fadeIn().html("Please enter GST on bank commission");
    setTimeout(function(){$("#error_gst_on_bank_commission").html("&nbsp;");},5000)
    $("#gst_on_bank_commission").focus();
    return false;
  } else if(bank_commission=='') {
    $("#error_bank_commission").fadeIn().html("Please enter bank commission");
    setTimeout(function(){$("#error_bank_commission").html("&nbsp;");},5000)
    $("#bank_commission").focus();
    return false;
  } else if(date_of_deposit=='') {
    $("#error_date_of_deposit").fadeIn().html("Please enter date of deposit");
    setTimeout(function(){$("#error_date_of_deposit").html("&nbsp;");},5000)
    $("#date_of_deposit").focus();
    return false;
  } else {
      $('#submit_save').submit();
  }
}
</script>

