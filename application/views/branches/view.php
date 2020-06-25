 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel'); ?>

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
                                    <h3 class="panel-title"> Details of <strong> <?php echo $getBranchData->branch_title ?></strong></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Branches/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                <div class="panel-body panel-body-table">                                                                            
                         <div class="col-md-12">
                            <div>&nbsp;</div>
                             <div class="col-md-12">                                                            
                            <div class="panel panel-default">                                                              
                                <div class="panel-body"> 
                                <table class="table table-bordered table-responsive">
                                    <thead>
                                        <th>Name</th>
                                        <th>Pincode</th>
                                        <th>Contact No</th>
                                        <th>Contact Person</th>
                                        <th>Contact Details</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $getBranchData->branch_title ?></td>
                                            <td><?php echo $getBranchData->pincode ?> </td>
                                            <td><?php echo $getBranchData->contact_no ?> </td>
                                            <td><?php echo $getBranchData->name ?>  </td>
                                            <td><?php echo $getBranchData->mobile ?>   </td>
                                        </tr>
                                    </tbody>
                                </table>                                   
                                    <!-- <div class="row">
                                       <h3>Basic Details of <strong> <?php echo $getBranchData->branch_title ?></strong></h3>
                                        <h4 class="text-title">Name</h4>
                                         <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <?php echo $getBranchData->branch_title ?>                                           
                                            </div>
                                        </div><br>
                                        <h4 class="text-title">Pincode</h4>
                                         <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <?php echo $getBranchData->pincode ?>                                           
                                            </div>
                                        </div><br>
                                        <h4 class="text-title">Contact No</h4>
                                         <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <?php echo $getBranchData->contact_no ?>                                           
                                            </div>
                                        </div>
                                        <br>
                                        <h4 class="text-title">Contact Person</h4>
                                         <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <?php echo $getBranchData->name ?>                                           
                                            </div>
                                        </div>
                                         <br>
                                        <h4 class="text-title">Contact Details</h4>
                                         <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <?php echo $getBranchData->mobile ?>                                           
                                            </div>
                                        </div>                                      
                                    </div> -->
                                </div>
                               
                               
                            </div>                            
                            
                       
                        </div>

                        <div class="col-md-12">
                                 <div class="panel panel-default tabs">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active"><a href="#tab8" data-toggle="tab">Branch Details</a></li>
                                    <li><a href="#tab9" data-toggle="tab">Available Assets</a></li>
                                    <li><a href="#tab10" data-toggle="tab">Employee Details</a></li>
                                </ul>
                                <div class="panel-body tab-content">
                                    <div class="tab-pane active" id="tab8">
                                            <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions">
                                            <thead>
                                                 <tr>
                                                   
                                                    <th>Branch title</th>
                                                    <th>Country Name</th>                                                  
                                                    <th>State Name</th>                                                  
                                                    <th>City Name</th>                                                  
                                                    <th>Address</th>                                                  
                                                    <th>Pincode</th>
                                                    <th>Contact No</th>
                                                </tr>
                                            </thead>
                                            <tbody>  
                                                <tr>
                                                    <td><?php echo checkEmptyHelp($getBranchData->branch_title) ?></td>
                                                    <td><?php echo checkEmptyHelp($getBranchData->country_name) ?></td>
                                                    <td><?php echo checkEmptyHelp($getBranchData->state_name) ?></td>
                                                    <td><?php echo checkEmptyHelp($getBranchData->city_name) ?></td>
                                                    <td><?php echo checkEmptyHelp($getBranchData->address) ?></td>
                                                    <td><?php echo checkEmptyHelp($getBranchData->pincode) ?></td>
                                                    <td><?php echo checkEmptyHelp($getBranchData->contact_no) ?></td>                                                                                                                    
                                                </tr>                                                                                       
                                            </tbody>
                                        </table>
                                    </div>          
                                    </div>
                                    <div class="tab-pane" id="tab9">
                                            
                                              <?php $assetsaginst = $this->Branches_model->getAssetsDetails($getBranchData->id); 
                                              ?>
                                    
                                
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions">
                                            <thead>
                                                 <tr>                                                    
                                                    <th>Sr No.</th>
                                                    <th>Asset Name</th>
                                                    <th>Asset Quantity</th>                                                                              
                                                </tr>
                                            </thead>
                                            <tbody>  
                                                <?php $sr=1; ?>
                                                <?php if(empty($assetsaginst)) { ?>
                                                     <tr>
                                                        <td colspan="2">No Data Found...</td>
                                                    </tr>
                                                <?php  } else {  ?>
                                                <?php foreach($assetsaginst as $assetsaginst) { ?>
                                                <tr>
                                                    <td><?php echo $sr; ?></td>
                                                    <td><?php echo $assetsaginst->asset_name ?></td>
                                                    <td><a href="<?php echo site_url('Assets/log_details/'.$assetsaginst->asset_id.'/'.$getBranchData->id); ?>" title="Log Detail"><?php echo $assetsaginst->asset_quantity  ?></a></td>                     
                                                </tr>
                                                <?php $sr++; } } ?>                      
                                            </tbody>
                                        </table>
                                    </div>       
                                    </div>
                                    <div class="tab-pane" id="tab10">
                                             <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions">
                                            <thead>
                                                 <tr>                                                    
                                                    <th> Name</th>
                                                    <th> Code</th>
                                                    <th>Email</th>
                                                    <th>Mobile</th>                                                                              
                                                </tr>
                                            </thead>
                                            <tbody>  
                                                <?php foreach($getEmployees as $getEmployeesRow) { ?>
                                                <tr>
                                                    <td><?php echo $getEmployeesRow->name ?></td>
                                                    <td><?php echo $getEmployeesRow->emp_code  ?></td>                                                                                                                     
                                                    <td><?php echo $getEmployeesRow->email  ?></td>                                                                                                                                                                       
                                                    <td><?php echo $getEmployeesRow->mobile  ?></td>                                                                                                                                                                       
                                                </tr>
                                                <?php } ?>
                                                                                                                                 
                                            </tbody>
                                        </table>
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
    
    </div>
                <!-- END PAGE CONTENT WRAPPER -->


<?php $this->load->view('common/footer');?>

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
                        $('.state').html(returndata);
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
 
