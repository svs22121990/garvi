 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
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
                            
                            <form class="form-horizontal" method="post" action="<?php echo site_url('Branches/update_action')?>">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></h3>
                                    <ul class="panel-controls">
                                        <li><a href="<?= site_url('Branches/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                <div class="panel-body">
                                   
                                </div>
                                <div class="panel-body">                                                                        
                                   
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Branch Title<span style="color: red">*</span></label>
                                                <div class="col-md-9">                                            
                                                    <div >
                                                        <!-- <span class="input-group-addon"><span class="fa fa-pencil"></span></span> -->
                                                        <input type="text" class="form-control" name="branch_title" id="branch_title"  value="<?= $getBranchData->branch_title?>" />
                                                         <span class="help-block" id="branch_error" style="color: red"></span> 
                                                    </div>                                            
                                                    
                                                </div>
                                            </div>

                                              <div class="form-group">
                                                <label class="col-md-3 control-label">Conatct No<span style="color: red">*</span></label>
                                                <div class="col-md-9">                                                                                                                                        
                                                    <input type="text" class="form-control" name="mobile" id="mobile" maxlength="10" value="<?= $getBranchData->contact_no?>"/>
                                                      <span class="help-block" id="mobile_error" style="color: red"></span>                                                    
                                                </div>
                                            </div>
                                            
                                         
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Address<span style="color: red">*</span></label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <textarea class="form-control" rows="5" name="address" id="address" >
                                                        <?= $getBranchData->address?>
                                                    </textarea>
                                                      <span class="help-block" id="address_error" style="color: red"></span>
                                                    <!-- <span class="help-block">Default textarea field</span> -->
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Pincode<span style="color: red">*</span></label>
                                                <div class="col-md-9">                                                                                                                                        
                                                    <input type="text" class="form-control" name="pincode" id="pincode" maxlength="6" value="<?= $getBranchData->pincode?>"/>
                                                     <span class="help-block" id="pincode_error" style="color: red"></span>                                                    
                                                </div>
                                            </div>

                                              
                                            
                                        </div>
                                        <div class="col-md-6">
                                            
                                         
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> Select Contry<span style="color: red">*</span></label>
                                                <div class="col-md-9">                                                                                            
                                                    <select class="form-control" name="country_id" id="country_id" onchange="get_state_by_country(this.value);">
                                                        <option value="">--Select Country Name--</option>
                                                        <?php foreach($country_data as $country_dataRow) { ?>                                                         
                                                        <option value="<?php echo $country_dataRow->id?>"<?php if($getBranchData->country_id==$country_dataRow->id)echo "selected";?>><?php echo $country_dataRow->country_name?>
                                                            
                                                        </option>    
                                                        <?php } ?>                                                   
                                                    </select>
                                                    <span class="help-block" id="country_error" style="color: red"></span>
                                                </div>
                                            </div>
                                              <div class="form-group">
                                                <label class="col-md-3 control-label"> Select State<span style="color: red">*</span></label>
                                                <div class="col-md-9">                                                                                            
                                                    <select class="form-control state" name="state_id" id="state_id" onchange="get_city_by_state(this.value);">
                                                        <option value="">--Select State Name--</option>
                                                         <?php
                                                                   foreach ($state_data as $state_row_data) 
                                                                   {  
                                                                    ?>
                                                                    <option  value="<?php echo $state_row_data->id; ?>"<?php if($getBranchData->state_id==$state_row_data->id)echo "selected";?>><?php echo $state_row_data->state_name; ?></option>
                                                                    <?php } ?>
                                                       
                                                    </select>
                                                     <span class="help-block" id="state_error" style="color: red"></span>
                                                </div>
                                            </div>
                                              <div class="form-group">
                                                <label class="col-md-3 control-label"> Select City<span style="color: red">*</span></label>
                                                <div class="col-md-9">                                                                                            
                                                    <select class="form-control city" name="city_id" id="city_id">
                                                        <option value="">--Select City Name--</option>

                                                         <?php
                                                                   foreach ($city_data as $city_row_data) 
                                                                   {  
                                                                    ?>
                                                                    <option  value="<?php echo $city_row_data->id; ?>"<?php if($getBranchData->city_id==$city_row_data->id)echo "selected";?>><?php echo $city_row_data->city_name; ?></option>
                                                                    <?php } ?>
                                                      
                                                    </select>
                                                     <span class="help-block" id="city_error" style="color: red"></span>
                                                </div>
                                            </div>                                            
                                         <input type="hidden" name="update_id" value="<?php echo $update_id; ?>">                                            
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <!-- <button class="btn btn-default">Clear Form</button> -->                                    
                                    <button class="btn btn-primary pull-right" type="submit" onclick="return validateinfo()"><?= $button; ?></button>
                                </div>
                            </div>
                            </form>                            
                        </div>
                    </div>                                        
                </div>
                <!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<?php $this->load->view('common/footer');?>
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
                        $('.state').html(returndata);
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
                        $('.city').html(returndata);
                         $(".loader").fadeOut('fast'); 
                    }
                });
            }
        </script>

<script type="text/javascript">
    function validateinfo() 
        {           
        
                var branch_title = $("#branch_title").val();
                var pincode = $("#pincode").val();
                var mobile = $("#mobile").val();
                var address = $("#address").val();
                var country_id = $("#country_id").val();
                var state_id = $("#state_id").val();
                var city_id = $("#city_id").val();
                            

                if(branch_title=='')
                {
                     $("#branch_error").html("Required").fadeIn();
                    setTimeout(function(){$("#branch_error").fadeOut()},5000);
                    /* $("#branch_title").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#branch_title").css("border-color", "#ccc");},6000);*/              
                    return false;
                }

                 if(mobile=='')
                {
                     $("#mobile_error").html("Required").fadeIn();
                    setTimeout(function(){$("#mobile_error").fadeOut()},5000);
                    /* $("#mobile").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#mobile").css("border-color", "#ccc");},6000);     */         
                    return false;
                }
                 if(address=='')
                {
                     $("#address_error").html("Required").fadeIn();
                    setTimeout(function(){$("#address_error").fadeOut()},5000);
                     /*$("#address").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#address").css("border-color", "#ccc");},6000);  */            
                    return false;
                }

                if(pincode=='')
                {
                     $("#pincode_error").html("Required").fadeIn();
                    setTimeout(function(){$("#pincode_error").fadeOut()},5000);
                     /*$("#pincode").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#pincode").css("border-color", "#ccc");},6000); */             
                    return false;
                }

               

                

                if(country_id=='')
                {
                     $("#country_error").html("Required").fadeIn();
                    setTimeout(function(){$("#country_error").fadeOut()},5000);
                     /*$("#country_id").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#country_id").css("border-color", "#ccc");},6000);*/              
                    return false;
                }

                if(state_id=='')
                {
                     $("#state_error").html("Required").fadeIn();
                    setTimeout(function(){$("#state_error").fadeOut()},5000);
                     /*$("#state_id").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#state_id").css("border-color", "#ccc");},6000);*/              
                    return false;
                }

                 if(city_id=='')
                {
                     $("#city_error").html("Required").fadeIn();
                    setTimeout(function(){$("#city_error").fadeOut()},5000);
                     /*$("#city_id").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#city_id").css("border-color", "#ccc");},6000);  */            
                    return false;
                }
                
        }
</script>
 
