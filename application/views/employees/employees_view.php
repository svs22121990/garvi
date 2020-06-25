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

        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body profile" style="background-color: #fff;">
                    <div class="profile-image">
                        <?php if(!empty($employee_data->image)) { ?>
                          <img src="<?php echo base_url(); ?>uploads/employee_images/<?php echo $employee_data->image; ?>" width='80px' height='110px'>
                        <?php } else { ?>
                          <img src="<?php echo base_url(); ?>uploads/employee_images/default.jpg" width='60px' height='80px'>
                        <?php } ?>
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name" style="color:#000;font-weight: bold">
                            <?php if(!empty($employee_data->name)) { echo ucwords($employee_data->name); } else { echo 'N/A'; } ?>
                        </div>
                        <div class="profile-data-title" style="color: #000;">
                            ( <?php if(!empty($designation->designation_name)) { echo $designation->designation_name; } else { echo 'N/A'; } ?> )
                        </div>
                    </div>                                 
                </div> 
                <div class="panel-body list-group border-bottom">
                    <center><a href="#" class="list-group-item" style="background-color:#ccc; color:#000;"><span class="fa fa-envelope"></span>
                       <?php if(!empty($employee_data->email)) { echo $employee_data->email; } else { echo 'N/A'; } ?>  
                    </a>
                    <a href="#" class="list-group-item" style="background-color:#ccc; color:#000;"><span class="fa fa-mobile"></span>
                        <?php if(!empty($employee_data->mobile)) { echo $employee_data->mobile; } else { echo 'N/A'; } ?>
                    </a></center> 
                </div>
                <!-- <div class="panel-body">
                    <?php if(!empty($allEmployees)) { ?>
                    <h4 class="text-title">Other Users of <?php if(!empty($branch->branch_title)) { echo $branch->branch_title; } else { echo 'N/A'; } ?></h4>
                    <div class="row">
                    	<?php foreach ($allUsers as $row) { ?>
                        <div class="col-md-4 col-xs-4">
                            <a href="#" class="friend" title="<?php echo $row->name; ?>">
                                <?php if(!empty($employee_data->image)) { ?>
		                          <img src="<?php echo base_url(); ?>uploads/employee_images/<?php echo $row->image; ?>" width='50px' height='60px'>
		                        <?php } else { ?>
		                          <img src="<?php echo base_url(); ?>uploads/employee_images/default.jpg" width='60px' height='80px'>
		                        <?php } ?>
                                <span><?php if(strlen($row->name) > 7) { echo substr($row->name,0,7).'...'; } else { echo $row->name;} ?></span>
                            </a>                                            
                        </div>
                    	<?php } ?>
                    </div>
                    <?php } ?>
                </div> -->
            </div>
        </div>

        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> Details of  <span style="color:green; font-weight: bold;"><?php if(!empty($employee_data->name)) { echo ucwords($employee_data->name); } else { echo 'N/A'; } ?></span>  </h3>
                    <ul class="panel-controls">
                       <li><a href="<?=site_url('Users/index');?>" title="BACK"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">                                                                        
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Branch : </label>
                                <div class="col-md-6 col-xs-6"><?php if(!empty($branch->branch_title)) { echo $branch->branch_title; } else { echo 'N/A'; } ?> </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Designation : </label>
                                <div class="col-md-6 col-xs-6"> <?php if(!empty($designation->designation_name)) { echo $designation->designation_name; } else { echo 'N/A'; } ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Name : </label>
                                <div class="col-md-6 col-xs-6"><?php if(!empty($employee_data->name)) { echo $employee_data->name; } else { echo 'N/A'; } ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Email : </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($employee_data->email)) { echo $employee_data->email; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Mobile : </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($employee_data->mobile)) { echo $employee_data->mobile; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Password : </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($employee_data->showpassword)) { echo $employee_data->showpassword; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Country : </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($country->country_name)) { echo $country->country_name; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">State : </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($state->state_name)) { echo $state->state_name; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">City : </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($city->city_name)) { echo $city->city_name; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Address : </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($employee_data->address)) { echo $employee_data->address; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Pincode : </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($employee_data->pincode)) { echo $employee_data->pincode; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Invoice Serial Number Series: </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($employee_data->invoice_serial_number_series)) { echo $employee_data->invoice_serial_number_series; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">Dispatch Note Serial Number Series: </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($employee_data->dispatch_note_serial_number_series)) { echo $employee_data->dispatch_note_serial_number_series; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6">GST Number: </label>
                                <div class="col-md-6 col-xs-6" ><?php if(!empty($employee_data->gst_number)) { echo $employee_data->gst_number; } else { echo 'N/A'; } ?></div>
                            </div> 
                        </div>
                </div>
                    </div>
                    <div class="panel-footer">
                        <a class="btn btn-danger btn-xs" href="<?=site_url('Users/index');?>">Back</a>
                    </div>
            </div>
        </div>

    </div>                    
    
</div>
<!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('common/footer');?>
