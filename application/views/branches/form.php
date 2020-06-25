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
                            
                            <form class="form-horizontal" method="post" action="<?php echo site_url('Branches/create_action')?>">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Branches/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                <div class="panel-body">
                                        <div class="row">
                                        
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                <label class="col-md-12">Branch Title<span style="color: red">*&nbsp;</span>
                                                <span id="branch_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11">                                            
                                                    	 <input type="text" class="form-control" name="branch_title" id="branch_title" placeholder="Enter Branch Title" onchange="checkduplicate(this.value)"/>
                                                    </div>                                            
                                                 
                                            </div>
                                            
                                         
                                            <div class="form-group">
                                                <label class="col-md-12">Address<span style="color: red">*&nbsp;</span>
												<span  id="address_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11 col-xs-12">                                            
                                                    <textarea class="form-control" rows="5" name="address" id="address" style="resize: none" placeholder="Enter Address"></textarea>
                                                    <!-- <span class="help-block">Default textarea field</span> -->
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-md-12">Pincode<span style="color: red">*&nbsp;</span>
                                                     <span  id="pincode_error" style="color: red"></span>                                                    
                                                </label>
                                                <div class="col-md-11">    	
                                                	<input type="text" class="form-control numbers" name="pincode" id="pincode" maxlength="6" placeholder="Enter Pincode" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-12">Contact No<span style="color: red">*&nbsp;</span>
                                                   <span class="" id="mobile_error" style="color: red"></span>                                                    
                                                </label>
                                                <div class="col-md-11">   	
                                                  <input type="text" class="form-control numbers" name="mobile" id="mobile" maxlength="10" placeholder="Enter Conatct No" />
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-6">
                                      
                                            <div class="form-group">
                                                <label class="col-md-12"> Select Country<span style="color: red">*&nbsp;</span>
                                                    <span class="" id="country_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11">                                                                                            
                                                    <select class="form-control select" data-live-search="true" name="country_id" id="country_id" onchange="get_state_by_country(this.value);">
                                                        <option value="">--Select Country Name--</option>
                                                        <?php foreach($country_data as $country_dataRow) { ?>                                                         
                                                        <option value="<?php echo $country_dataRow->id?>"><?php echo $country_dataRow->country_name?>
                                                            
                                                        </option>    
                                                        <?php } ?>                                                   
                                                    </select>
                                                </div>
                                            </div>
                                              <div class="form-group">
                                                <label class="col-md-12"> Select State<span style="color: red">*&nbsp;</span>
                                                     <span class="" id="state_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11">                                                                                            
                                                    <select class="form-control state select" data-live-search="true" name="state_id" id="state_id" onchange="get_city_by_state(this.value);">
                                                        <option value="">--Select State Name--</option>
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                              <div class="form-group">
                                                <label class="col-md-12"> Select City<span style="color: red">*&nbsp;</span>
                                                     <span class="" id="city_error" style="color: red"></span>
                                                </label>
                                                <div class="col-md-11">                                                                                            
                                                    <select class="form-control city select" data-live-search="true" name="city_id" id="city_id">
                                                        <option value="">--Select City Name--</option>                                                      
                                                    </select>
                                                </div>
                                            </div>
                                          
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="panel-footer">
                                    <button class="btn btn-success" type="submit" id="statusSubBtn" onclick="return validateinfo()"><?= $button;?></button>
                                    <a href="<?=site_url('Branches')?>" class="btn btn-danger">Cancel</a>                                    
                                </div>
                            </div>
                            </form>
                            
                        </div>
                    </div>                    
                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->

<!-- <div id="myModalEmployee" class="modal fade" role="dialog xs">
  <div class="modal-dialog">
    <form action="<?php //echo site_url('Branches/savemyEmployee')  ?>" method="post" enctype="multipart/form-data" id="addmyEmployee">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title text-center">Add Employee</h3>
        </div>
        <div class="modal-body">
            <div class="col-md-12 text-success text-center" id="success_message"></div>
            <div class="row">
                <div class="col-md-12">
                  <label>Name <span style="color:red;">*</span><span id="error_employee_title" class="errors"></span></label>
                  <input type="text" class="form-control" name="employee_title" id="employee_title">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="return validation();">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
   </form>
  </div>
</div> -->
<script type="text/javascript">

/*$(".select").remove();
*/            function get_state_by_country(id) 
            {
                 $(".loader").fadeIn('fast'); 
                var datastring = "id=" + id;

                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Branches/get_state'); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);
                        $('#state_id').html(returndata).selectpicker('refresh');
                         $(".loader").fadeOut('fast'); 
                    }
                });
            }

            function get_city_by_state(id) 
            {
                var datastring = "id=" + id;
                $(".loader").fadeIn('fast'); 
                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Branches/get_city'); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);
                        $('#city_id').html(returndata).selectpicker('refresh');
                         $(".loader").fadeOut('fast'); 
                    }
                });
            }
        </script>
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
           // alert(response);return false;
            if(response == 1)
            {
              $("#branch_title").val("");
              $("#branch_error").fadeIn().html("Branch title already exist");
              setTimeout(function(){$("#branch_error").fadeOut();},5000);
            }
            else
            {                
                        
            }           
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
                //var emp_id = $("#emp_id").val();
                          
 

                if(branch_title=='')
                {
                     $("#branch_error").html("Required").fadeIn();
                    setTimeout(function(){$("#branch_error").fadeOut()},5000);
                    /* $("#branch_title").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#branch_title").css("border-color", "#ccc");},6000);  */            
                    return false;
                }
                 if(address=='')
                {
                     $("#address_error").html("Required").fadeIn();
                    setTimeout(function(){$("#address_error").fadeOut()},5000);
                     /*$("#address").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#address").css("border-color", "#ccc");},6000); */             
                    return false;
                }

                if(pincode=='')
                {
                     $("#pincode_error").html("Required").fadeIn();
                    setTimeout(function(){$("#pincode_error").fadeOut()},5000);
                     /*$("#pincode").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#pincode").css("border-color", "#ccc");},6000);*/              
                    return false;
                }

                if(mobile=='')
                {
                     $("#mobile_error").html("Required").fadeIn();
                    setTimeout(function(){$("#mobile_error").fadeOut()},5000);
                     /*$("#mobile").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#mobile").css("border-color", "#ccc");},6000);*/              
                    return false;
                }

                /* if(emp_id=='')
                {
                    $("#emp_error").html("Required").fadeIn();
                    setTimeout(function(){$("#emp_error").fadeOut()},5000);                                 
                    return false;
                }*/

                

                

                if(country_id=='')
                {
                     $("#country_error").html("Required").fadeIn();
                    setTimeout(function(){$("#country_error").fadeOut()},5000);
                     /*$("#country_id").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#country_id").css("border-color", "#ccc");},6000); */             
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
                    /* $("#city_id").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#city_id").css("border-color", "#ccc");},6000); */             
                    return false;
                }
                
        }
</script>



<!-- <script type="text/javascript">
   function validation()
   {
        var employee_title = $("#employee_title").val();
        var site_url = $("#site_url").val(); 
        if(employee_title=="") 
        {
            $("#error_employee_title").fadeIn().html("Please enter name");
            setTimeout(function(){$("#error_employee_title").fadeOut();},3000);
            $("#employee_title").focus();
            return false;
        }
         var site_url = $('#site_url').val();
            var url = site_url+"/Branches/savemyEmployee";
            var dataString = "employee_title="+employee_title;
            $.post(url,dataString,function(returndata)
            {
                 $("#myModalEmployee").modal("hide");
              $("#refresh_payment").load(location.href + " #refresh_payment>*", "");
            });
           }
   
</script> -->







 
