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
                            
                            <form class="form-horizontal" method="post" action="<?php echo $action; ?>">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                                   <!--  <ul class="panel-controls">
                                        <li><a href="<?=site_url('Vendors/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul> -->
                                </div>
                               
                                <div class="panel-body">                                                                        
                                    
                                    <div class="col-md-12">
                                            <h4 class="text-left">Vendor's Personal Details</h4>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Firm Name <span style="color:red;">*</span> <span class="" id="errorshopname" style="color: red"></span> </label>
                                                    <div class="col-md-10">
                                                        <input type="text" placeholder="Firm Name" class="form-control" name="shop_name" id="shop_name" value="<?= $shop_name; ?>">     
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Name <span style="color:red;">*</span>
                                                        <span class="" id="errorname" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input type="text" placeholder="Name" class="form-control" name="name" id="name" value="<?= $name; ?>">        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Email <span style="color:red;">*</span>
                                                        <span class="" id="erroremail" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input type="text" placeholder="Email" class="form-control" name="email" id="email" value="<?= $email; ?>">    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Mobile <span style="color:red;">*</span>
                                                        <span class="" id="errormobile" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                         <input type="text" placeholder="Mobile" class="form-control" name="mobile" id="mobile" value="<?= $mobile; ?>" maxlength="10" onkeypress="only_number(event)">     
                                                    </div>
                                                </div> 
                                            </div>

                                        </div>
                                       <p class="col-md-12 bordered"></p>
                                        <div class="col-md-12">
                                            <h4 class="text-left">Vendor's Address Details</h4>
                                           
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Select Country<!-- <span style="color: red">*</span> -->
                                                    </label>
                                                    <div class="col-md-10">                                                                                            
                                                        <select class="form-control select" name="country_id" id="country_id" onchange="get_state_by_country(this.value);" data-live-search="true">
                                                            <option value="">--Select Country Name--</option>
                                                            <?php foreach($countries as $country_dataRow) { ?>                                                         
                                                            <option value="<?php echo $country_dataRow->id?>" <?php if($button=='Update' && $country_dataRow->id == $country_id) { echo "selected"; } ?>><?php echo $country_dataRow->country_name?></option>    

                                                            <?php } ?>                                                   
                                                        </select>
                                                        <span class="" id="country_error" style="color: red"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                  <div class="form-group">
                                                    <label class="col-md-12">Select State<!-- <span style="color: red">*</span> -->
                                                    </label>
                                                    <div class="col-md-10">                                                                                            
                                                        <select class="form-control state select" name="state_id" id="state_id" onchange="get_city_by_state(this.value);" data-live-search="true">
                                                            <option value="">--Select State Name--</option>
                                                           <?php  if($button=='Update'){ foreach($states as $state)
                                                            {
                                                            ?>
                                                              <option value="<?php echo  $state->id; ?>" <?php if($state->id == $state_id) { echo "selected"; } ?> ><?php echo ucfirst($state->state_name);?></option>
                                                         <?php } } ?>
                                                        </select>
                                                         <span class="" id="errorstate_state_id" style="color: red"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Select City<!-- <span style="color: red">*</span> -->
                                                    </label>
                                                    <div class="col-md-10">                                                                                            
                                                        <select class="form-control city select" name="city_id" id="city_id" data-live-search="true">
                                                            <option value="">--Select City Name--</option>
                                                             <?php if($button=='Update'){ foreach ($cities as $city) { ?>
                                                                 <option value="<?= $city->id;  ?>" <?php  if($city->id == $city_id) { echo "selected"; } ?>><?= $city->city_name; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                         <span class="" id="errorcity_city_id" style="color: red"></span>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Pincode<!-- <span style="color: red">*</span> -->
                                                         <span class="" id="errorpincode" style="color: red"></span>                                                    
                                                    </label>
                                                    <div class="col-md-10">                                                                                                                                        
                                                        <input type="text" class="form-control" value="<?=$pincode?>" name="pincode" id="pincode" placeholder="Pincode" maxlength="6" onkeypress="only_number(event)"/>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label class="col-md-12">Address<!-- <span style="color: red">*</span> -->
                                                          <span class="" id="erroraddress" style="color: red"></span>
                                                    </label>
                                                    <div class="col-md-10 col-xs-12">                                            
                                                          <textarea placeholder="Address" class="form-control" name="address" id="address" style="resize: none"><?= $address; ?></textarea>
                                                      
                                                    </div>
                                                 </div>
                                             </div>
                                             
                                        </div>
                                       <p class="col-md-12 bordered"></p>
                                        <div class="col-md-12">
                                            <h4 class="text-left">Vendor's Account Details</h4>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">GST No. <span style="color:red;">*</span>
                                                        <span class="" id="errorgst_no" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input type="text" placeholder="GST No." class="form-control" name="gst_no" id="gst_no" value="<?= $gst_no; ?>" maxlength="15">  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                         <span class="" ></span>
                                                    <label class="col-md-12">Account Type </label>
                                                    <div class="col-md-10">
                                                         <input type="text" placeholder="Account Type" class="form-control" name="account_type" id="account_type" value="<?= $account_type; ?>"> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Account Holder Name<!-- <span style="color:red;">*</span> -->
                                                        <span class="" id="errorgst_no" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                       <input type="text" placeholder="Account Holder Name" class="form-control" name="bank_account_name" id="bank_account_name" value="<?= $bank_account_name; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Bank Account Number<!-- <span style="color:red;">*</span> -->
                                                        <span class="" id="errorbank_account_no" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                      <input type="text" placeholder="Bank Account Number" class="form-control" name="bank_account_no" id="bank_account_no" value="<?= $bank_account_no; ?>" maxlength="16" onkeypress="only_number(event)"> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Bank Name<!-- <span style="color:red;">*</span> -->
                                                        <span class="" id="errorbank_name" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                     <input type="text" placeholder="Bank Name" class="form-control" name="bank_name" id="bank_name" value="<?= $bank_name; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Bank IFSC Code<!-- <span style="color:red;">*</span> -->
                                                        <span class="" id="errorbank_ifsc_code" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                    <input type="text" placeholder="Bank IFSC Code" class="form-control" name="bank_ifsc_code" id="bank_ifsc_code" value="<?= $bank_ifsc_code; ?>" maxlength="11">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Payment Term</label>
                                                    <div class="col-md-10">
                                                    <select class="form-control" name="payment_term" id="payment_term" value="<?= $payment_term; ?>">
                                                        <option value="">Select</option>
                                                        <option value="By cheque" <?php if($payment_term=='By cheque'){ echo 'selected';}?>>By cheque</option>
                                                        <option value="Online" <?php if($payment_term=='Online'){echo 'selected';}?>>Online</option>
                                                        <option value="Cash" <?php if($payment_term=='Cash'){echo 'selected';}?>>Cash</option>
                                                    </select>     
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <input type="hidden" id="categorCount" value="<?php echo count($assets_type); ?>">
                                    <?php
                                     if(count($assets_type) > 0)
                                     { ?>
                                        <p class="col-md-12 bordered"></p>
                                        <div class="col-md-12">
                                            <h4 class="text-left">Assign Dealing Assets types<span style="color:red;">*</span>&nbsp;<small id="error_category" style="color:red"></small></h4>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                <?php
                                                    foreach ($assets_type as $row){
                                                        
                                                        $checked = $this->Crud_model->GetData('vendor_asset_type_map','',"asset_type_id = '".$row->id."' and vendor_id = '".$id."'",'','','','single');
                                                       // print_r($this->db->last_query());exit;
                                                ?>
                                                <div class="col-md-3">
                                                    <label>

                                                   <input class="category" type="checkbox" name="category[]" id="checks" value="<?php echo $row->id;?>" <?php if($button == 'Update') { if(!empty($checked) && ($row->id == $checked->asset_type_id)) { echo "checked"; } } ?>>&nbsp;&nbsp;<?php echo $row->type; ?>
                                                    </label>
                                                    
                                                </div>
                                                <?php } ?>

                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    
                                    </div>
                                
                                <div class="panel-footer">
                                    <button class="btn btn-success" type="submit" id="submit" onclick="return validation();"><?= $button;?></button>
                                    <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>                                    
                                </div>
                            </div>
                            </form>
                            </div>
                            
                        </div>
                    </div>                    
                    
                
                <!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript">
    var url="";
    var actioncolumn="";
</script>
<?php $this->load->view('common/footer');?>
<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>


<script type="text/javascript">
          function get_state_by_country(id) {
                // alert(id);
                 $(".loader").fadeIn('fast'); 
                var datastring = "id=" + id;

                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Branches/get_state'); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);
                        //$('.state').html(returndata).selectpicker('refresh');
                        $('#state_id').html(returndata).selectpicker('refresh');
                       $( ".state" ).addClass( "select" );
                        $(".loader").fadeOut('fast'); 
                    }
                });
            }

            function get_city_by_state(id) {
                var datastring = "id=" + id;
                 $(".loader").fadeIn('fast'); 
                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Branches/get_city'); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);
                        //$('.city').html(returndata).selectpicker('refresh');
                        $('#city_id').html(returndata).selectpicker('refresh');
                         $(".loader").fadeOut('fast'); 
                    }
                });
            }
        </script>

<script type="text/javascript">
   function validation()
    {
        var shop_name = $("#shop_name").val();
        var name = $("#name").val(); 
        var email = $("#email").val();
        var mobile = $("#mobile").val();

        var gst_no = $("#gst_no").val();
        
        var pattern_name = /^[A-Za-z ]{0,50}$/i;  
        //var pattern_bank_name= /^[A-Za-z ]{0,50}$/i; 
        var pattern_mobile = /^[0-9]{10,13}$/i;
        //var pattern_pincode = /^[0-9]{6,8}$/i;
        var validateEmail = function(elementValue) 
        {
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
           return emailPattern.test(elementValue);
        }
        var categorCount = $("#categorCount").val(); //alert(categorCount);return false;

        
        if(shop_name.trim()=='')
        {
            $("#errorshopname").fadeIn().html("Please enter firm name");
            setTimeout(function(){$("#errorshopname").fadeOut("&nbsp");},3000)
            $("#shop_name").focus();
            return false;
        } 

        if(name.trim()=='')
        {
            $("#errorname").fadeIn().html("Please enter name");
            setTimeout(function(){$("#errorname").fadeOut("&nbsp");},3000)
            $("#name").focus();
            return false;
        }
        else if(!pattern_name.test(name))
        {
            $("#errorname").fadeIn().html("Please enter valid name");
            setTimeout(function(){$("#errorname").fadeOut("&nbsp");},3000);
            $("#name").focus();
            return false;
        }  
        if(email.trim()=='')
        {
            $("#erroremail").fadeIn().html("Please enter email");
            setTimeout(function(){$("#erroremail").fadeOut("&nbsp");},3000)
            $("#email").focus();
            return false;
        } 
       if(email!="")
        {
            var valid = validateEmail(email);
            if (!valid) 
            {
            $("#erroremail").fadeIn().html("Please enter vaild email");
            setTimeout(function(){ $("#erroremail").fadeOut(); }, 3000);
            $("#email").focus();
            return false;   
            }
        }

        if(mobile.trim()=='')
        {
            $("#errormobile").fadeIn().html("Please enter mobile");
            setTimeout(function(){$("#errormobile").fadeOut("&nbsp");},3000)
            $("#mobile").focus();
            return false;
        }
        if(mobile!="")
        {
            if(!pattern_mobile.test(mobile))
            {
              $("#errormobile").fadeIn().html("Please enter valid mobile");
              setTimeout(function(){ $("#errormobile").fadeOut(); }, 3000);
              $("#mobile").focus();
              return false; 
            }
        }
        if(gst_no.trim()=='')
        {
            $("#errorgst_no").fadeIn().html("Please enter gst no");
            setTimeout(function(){$("#errorgst_no").html("");},3000)
            $("#gst_no").focus();
            return false;
        }

        if(categorCount > 0)
        {
            category = $('.category').is(':checked');
            if(category==false)
            {
                $("#error_category").text("Please select at least one assets type");
                setTimeout(function(){ $("#error_category").text(""); }, 3000);
                return false;
            }
        }  
    $(".loader").fadeIn('fast'); 
                   
    }
</script>
 

