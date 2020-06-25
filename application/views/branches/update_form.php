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
                                                <label class="col-md-12">Branch Title<span style="color: red"> * &nbsp;</span>
                                                 <span   id="branch_error" style="color: red"></span> 
                                                </label>
                                                <div class="col-md-11">                                            
                                                   
                                                        <!-- <span class="input-group-addon"><span class="fa fa-pencil"></span></span> -->
                                                        <input type="text" class="form-control" name="branch_title" id="branch_title"  value="<?= $getBranchData->branch_title?>" onchange="checkduplicate(this.value)"/>
                                                  
                                                </div>
                                            </div>

                                              <div class="form-group">
                                                <label class="col-md-12">Contact No<span style="color: red"> * &nbsp;</span>
                                                <span   id="mobile_error" style="color: red"></span>                                                    
                                                </label>
                                                <div class="col-md-11">                                                                                                                                        
                                                    <input type="text" class="form-control" name="mobile" id="mobile" onkeypress="only_number(event)" maxlength="10" value="<?= $getBranchData->contact_no?>"/>
                                                </div>
                                            </div>
                                            
                                         
                                            <div class="form-group">
                                                <label class="col-md-12">Address<span style="color: red"> * &nbsp;</span>
                                                <span   id="address_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11 col-xs-12">                                            
                                                    <textarea class="form-control" rows="5" name="address" id="address" style="resize: none"><?= $getBranchData->address?></textarea>
                                                    <!-- <span  >Default textarea field</span> -->
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-md-12">Pincode<span style="color: red"> * &nbsp;</span>
                                                <span   id="pincode_error" style="color: red"></span>                                                    
                                                </label>
                                                <div class="col-md-11">                                                                                                                                        
                                                    <input type="text" class="form-control" name="pincode" id="pincode" onkeypress="only_number(event)" maxlength="6" value="<?= $getBranchData->pincode?>"/>
                                                </div>
                                            </div>

                                                <!-- <div class="form-group">
                                                <label class="col-md-12"> Select Employee<span style="color: red"> * &nbsp;</span>
                                                <span   id="emp_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11">                                                                                            
                                                    <select class="form-control" name="emp_id" id="emp_id">
                                                        <option value="">--Select Employee Name--</option>
                                                       <?php foreach($empupdate_data as $empupdate_dataRow) { ?>                                                         
                                                        <option value="<?php echo $empupdate_dataRow->id?>"<?php if($getBranchData->emp_id==$empupdate_dataRow->id)echo "selected";?>><?php echo $empupdate_dataRow->name?>
                                                            
                                                        </option>    
                                                        <?php } ?>                                                  
                                                    </select>
                                                </div>
                                            </div> -->                                                                 
                                        </div>
                                        <div class="col-md-6">                                                                                                                                 
                                            <div class="form-group">
                                                <label class="col-md-12"> Select Country<span style="color: red"> * &nbsp;</span>
                                                <span  id="country_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11">                                                                                            
                                                    <select class="form-control select"  data-live-search="true" name="country_id" id="country_id" onchange="get_state_by_country(this.value);">
                                                        <option value="">--Select Country Name--</option>
                                                        <?php foreach($country_data as $country_dataRow) { ?>                                                         
                                                        <option value="<?php echo $country_dataRow->id?>"<?php if($getBranchData->country_id==$country_dataRow->id)echo "selected";?>><?php echo $country_dataRow->country_name?>                                                            
                                                        </option>    
                                                        <?php } ?>                                                   
                                                    </select>
                                                </div>
                                            </div>
                                              <div class="form-group">
                                                <label class="col-md-12"> Select State<span style="color: red"> * &nbsp;</span>
                                                 <span  id="state_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11">                                                                                            
                                                    <select class="form-control state select" data-live-search="true" name="state_id" id="state_id" onchange="get_city_by_state(this.value);">
                                                        <option value="">--Select State Name--</option>
                                                         <?php
                                                                   foreach ($state_data as $state_row_data) 
                                                                   {  
                                                                    ?>
                                                                    <option  value="<?php echo $state_row_data->id; ?>"<?php if($getBranchData->state_id==$state_row_data->id)echo "selected";?>><?php echo $state_row_data->state_name; ?></option>
                                                                    <?php } ?>
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                              <div class="form-group">
                                                <label class="col-md-12"> Select City<span style="color: red"> * &nbsp;</span>
                                                <span  id="city_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11">                                                                                            
                                                    <select class="form-control city select" data-live-search="true" name="city_id" id="city_id">
                                                        <option value="">--Select City Name--</option>

                                                         <?php
                                                                   foreach ($city_data as $city_row_data) 
                                                                   {  
                                                                    ?>
                                                                    <option  value="<?php echo $city_row_data->id; ?>"<?php if($getBranchData->city_id==$city_row_data->id)echo "selected";?>><?php echo $city_row_data->city_name; ?></option>
                                                                    <?php } ?>                                                      
                                                    </select>
                                                </div>
                                            </div>                                            
                                         <input type="hidden" name="update_id" value="<?php echo $update_id; ?>">
                                         <input type="hidden" name="astinput" id="astinput" >                                           
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <button class="btn btn-success" type="submit" id="statusSubBtn" onclick="return validateinfo()"><?= $button; ?></button>
                                    <a href="<?=site_url('Branches')?>" class="btn btn-danger">Cancel</a>                              
                                </div>
                            </div>
                            </form>                            
                        </div>
                    </div>                                        
                </div>
                <!-- END PAGE CONTENT WRAPPER -->



<?php $this->load->view('common/footer');?>

<script type="text/javascript">
    function checkduplicate()
    {
        var branch_title = $("#branch_title").val();

         var datastring  = "branch_title="+branch_title;
        //alert(datastring);
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Branches/chkName'); ?>",
          data : datastring,
          success : function(response)
          {
            //alert(response);return false;
            if(response == 1)
            {
               $("#branch_title").val('<?php echo $getBranchData->branch_title; ?>');
               $("#branch_title").focus();
               $("#branch_error").fadeIn().html("Branch title already exist");
               setTimeout(function(){$("#branch_error").fadeOut();},5000);
               $('#statusBtn').prop('disabled', false);
               $("#astinput").val(0); 
               //setTimeout(function(){ window.location.reload(); },1000);
            }
            else
            {                
                $("#astinput").val(1);  
            }             
          }
        });

    }
</script>

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
                        $(".loader").fadeOut('fast'); 
                        $('#state_id').html(returndata).selectpicker('refresh');
                         //$(".loader").fadeOut('fast'); 
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
                          $(".loader").fadeOut('fast'); 
                         $('#city_id').html(returndata).selectpicker('refresh');
                         //$(".loader").fadeOut('fast'); 
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
                 var emp_id = $("#emp_id").val();
                            

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

                 if(emp_id=='')
                {
                    $("#emp_error").html("Required").fadeIn();
                    setTimeout(function(){$("#emp_error").fadeOut()},5000);                                 
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
 
