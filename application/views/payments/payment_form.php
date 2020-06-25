<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<!-- START BREADCRUMB -->
   <?= $breadcrumbs ?>
<!-- END BREADCRUMB -->          

<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<div class="page-content-wrap"> 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
               
              <div class="panel-heading">                                
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span id="successCountryEntry"></span></h3>
                    <ul class="panel-controls">
                        <li><a href="<?=site_url('Payments/index');?>"><span class="fa fa-arrow-left"></span></a></li>

                    </ul>
              
              </div> 
              <div class="panel-body">           
                <form method="post" role="form" action="<?php echo site_url('Payments/make_confirm_payment') ?>">
                    <div class="form-group">
                      <label class="col-sm-2 control-label"> Vendor <span style="color:red;">*</span></label>
                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-md-6">
                            <select name="vendor_id" id="vendor_id" class="form-control" onchange="GetVendorDetails(this.value)">
                              <option value="">-- Select Vendor --</option>
                              <?php foreach ($vendor as $row) { ?>
                              <option value="<?= $row->id; ?>"><?= $row->shop_name; ?></option>  
                              <?php } ?>
                            </select>
                            <div class="msghide"><?php echo form_error('vendor_id'); ?></div>
                            &nbsp;<span id="vendor_error" style="color:red"></span>
                          </div>                                        
                        </div>
                      </div>                                
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label"> Balance </label>
                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-md-6">
                            <input type="text" class="form-control" id="balance" name="balance" readonly>&nbsp;
                          </div>                                        
                        </div>
                      </div>                                
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label"> Payment Type <span style="color:red;">*</span></label>
                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-md-6">
                            <select name="payment_type" id="payment_type" class="form-control" onchange="GetDetail(this.value)">
                              <option value="">-- Select Payment Type --</option>
                              <?php foreach($types as $row){ ?>
                              <option value="<?= $row->type; ?>"><?= $row->type; ?></option>
                              <?php } ?>                                          
                            </select>
                            <div class="msghide"><?php echo form_error('payment_type'); ?></div>
                            &nbsp;<span id="payment_error" style="color:red"></span>
                          </div>                                        
                        </div>
                      </div>                                
                    </div>

                    <div class="form-group" id="cheque_details" style="display:none">
                      <label class="col-sm-2 control-label"></label>
                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="panel-body">
                              <span id="check_error" style="color:red"></span>&nbsp;
                              <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter Cheque Number" name="cheque_no" id="cheque_no" maxlength="6" onkeypress="return isNumberKey(event);">
                              </div>
                              <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter Bank Name" name="bank_name" id="bank_name">
                              </div>
                              <div class="form-group">
                                <input type="text" class="form-control date123" placeholder="Enter Cheque Date" name="cheque_date" id="cheque_date" readonly>
                              </div>                          
                            </div>
                          </div>                                        
                        </div>
                      </div>                                
                    </div>

                    <div class="form-group" id="neft_details" style="display:none">
                      <label class="col-sm-2 control-label"></label>
                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="panel-body">
                              <div class="form-group">
                                <span id="neft_error" style="color:red"></span>&nbsp;
                                <input type="text" class="form-control" placeholder="Enter Account Name" name="account_name" id="account_name" maxlength="30">
                              </div>
                              <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter Account Number" name="bank_account_no" id="bank_account_no" maxlength="16" onkeypress="return isNumberKey(event);">
                              </div>
                              <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter Bank Name" name="neft_bank_name" id="neft_bank_name">
                              </div>
                              <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter IFSC Code" name="bank_ifsc_code" id="bank_ifsc_code" >
                              </div>   
                            </div>                                        
                          </div>
                        </div>                                
                      </div>
                    </div>  

                    <div class="form-group">
                      <label class="col-sm-2 control-label"> Amount <span style="color:red;">*</span></label>
                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-md-6">
                            <input type="text" class="form-control" id="amount" placeholder="Enter Amount" name="amount" autocomplete="off" onkeypress="return isNumberKey(event);" maxlength="6">
                            <div class="msghide"><?= form_error('amount'); ?></div>
                            &nbsp;<span id="erramount" style="color:red"></span>
                          </div>                                        
                        </div>
                      </div>                                
                    </div>

                    </div>
                    <div class="panel-footer col-md-12">   
                      <button type="submit" onclick="return check_payment()" class="btn btn-success m-b " >Pay Now</button>
                      <a href="<?= site_url('Payments/index')?>">
                      <button type="button" class="btn btn-default m-b">Cancel</button></a>
                    </div>
                  </form>
                  
            </div>
        </div>
    </div>
</div>
<script>
    var url = '';
    var actioncolumn='';
</script>
<script type="text/javascript">
  function isNumberKey(evt)
  {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
    return true;
  }

  function check_payment()
  { 
    var amount=$("#amount").val();
    var vendor=$("#vendor_id").val();
    var balance=$("#balance").val(); 
    //var cheque =$("input[type='radio']:checked").val();
    var payment_type =$("#payment_type").val(); 
    var account_name =$("#account_name").val();
    var bank_account_no =$("#bank_account_no").val();
    var bank_ifsc_code =$("#bank_ifsc_code").val();
    var cheque_no =$("#cheque_no").val();
    var cheque_len=cheque_no.length; 
    var cheque_date =$("#cheque_date").val();
    var bank_name =$("#bank_name").val();
    var neft_bank_name =$("#neft_bank_name").val();

    if($.trim(vendor)=="")
    {
      $("#vendor_error").fadeIn().html("Please select vendor");
      setTimeout(function(){$("#vendor_error").html("&nbsp;");},3000);
      $("#vendor_id").focus();
      return false;
    }

    if($.trim(payment_type)=="")
    {
      $("#payment_error").fadeIn().html("Please select payment type");
      setTimeout(function(){$("#payment_error").html("&nbsp;");},3000);
      $("#payment_type").focus();
      return false; 
    }    

    if(payment_type=='NEFT')
    {
        if($.trim(account_name)=="")
        {
          $("#neft_error").fadeIn().html("Please enter account name");
          setTimeout(function(){$("#neft_error").html("&nbsp;");},3000);
          $("#account_name").focus();
          return false; 
        }
        if($.trim(bank_account_no)=="")
        {
          $("#neft_error").fadeIn().html("Please enter account number");
          setTimeout(function(){$("#neft_error").html("&nbsp;");},3000);
          $("#bank_account_no").focus();
          return false; 
        }
        if($.trim(neft_bank_name)=="")
        {
          $("#neft_error").fadeIn().html("Please enter bank name");
          setTimeout(function(){$("#neft_error").html("&nbsp;");},3000);
          $("#neft_bank_name").focus();
          return false; 
        }
        if($.trim(bank_ifsc_code)=="")
        {
          $("#neft_error").fadeIn().html("Please enter bank ifsc code");
          setTimeout(function(){$("#neft_error").html("&nbsp;");},3000);
          $("#bank_ifsc_code").focus();
          return false; 
        }
    }  

    if(payment_type=='Cheque')
    { 
        if($.trim(cheque_no)=="")
        {
          $("#check_error").fadeIn().html("Please enter cheque number");
          setTimeout(function(){$("#check_error").html("&nbsp;");},3000);
          $("#cheque_no").focus();
          return false; 
        }
        if((cheque_len!=6) || (cheque_no==0))
        {
          $("#check_error").fadeIn().html("Please enter valid cheque number");
          setTimeout(function(){$("#check_error").html("&nbsp;");},3000);
          $("#cheque_no").focus();
          return false; 
        }
        if($.trim(bank_name)=="")
        {
          $("#check_error").fadeIn().html("Please enter bank name");
          setTimeout(function(){$("#check_error").html("&nbsp;");},3000);
          $("#bank_name").focus();
          return false; 
        }
        if($.trim(cheque_date)=="")
        {
          $("#check_error").fadeIn().html("Please enter cheque date");
          setTimeout(function(){$("#check_error").html("&nbsp;");},3000);
          $("#cheque_date").focus();
          return false; 
        }
    } 

    
      if($.trim(amount)=="")
      {
        $("#erramount").fadeIn().html("Please enter amount");
        setTimeout(function(){$("#erramount").html("&nbsp;");},3000);
        $("#amount").focus();
        return false; 
      }

      if(parseFloat(amount) < 1)
      {
        $("#erramount").fadeIn().html("Please enter valid amount");
        setTimeout(function(){$("#erramount").html("&nbsp;");},3000);
        $("#amount").focus();
        return false; 
      }

      if(parseFloat(amount) > parseFloat(balance))
      {
        $("#erramount").fadeIn().html("Please enter value less than balance amount");
        setTimeout(function(){$("#erramount").html("&nbsp;");},3000);
        $("#amount").focus();
        return false; 
      }      
  }  
 
</script>

<script>  


  function GetVendorDetails(id)  
  {
    $('.loader').fadeIn("fast");
    $.ajax({
      type:'post',
      url:"<?= site_url('Payments/GetVendorData') ?>",
      data:{id:id},
      success:function(response){ //alert(response); return false;
        var jd = $.parseJSON(response);
        $("#balance").val(jd.balance);
        $("#bank_name").val(jd.bank_name);
        $("#neft_bank_name").val(jd.bank_name);
        $("#account_name").val(jd.bank_account_name);
        $("#bank_account_no").val(jd.bank_account_no);
        $("#bank_ifsc_code").val(jd.bank_ifsc_code);
        $('.loader').fadeOut("fast");
      }
    });
  }

  function GetDetail(value)
  {
    if(value=='Cheque')
    {
      $("#cheque_details").show();
      $("#neft_details").hide();        
    }
    else if(value=='NEFT')
    {
      $("#neft_details").show();        
      $("#cheque_details").hide();
    }
    else 
    {
      $("#neft_details").hide();        
      $("#cheque_details").hide();      
    }
  }
</script>
<?php $this->load->view('common/footer');?>