<?php $this->load->view('common/header');
//print_r($_SESSION);exit;
 ?>

<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>                    

<!-- START BREADCRUMB -->
<?= $breadcrumbs; ?>
<!-- END BREADCRUMB -->                       

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">

    <div class="col-md-12">
                                  
    <div class="panel panel-default">                                
        <div class="panel-body">
          <center><h1>DETAILS OF <?php echo $barcode_number; ?></h1></center> 
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-body profile" style="background-color: #fff;">
                        <center><label>Barcode Image</label></center>
                        <div class="profile-image">
                            <?php if(!empty($asset_details->barcode_image)) { ?>
                              <img src="<?php echo base_url(); ?>assets/purchaseOrder_barcode/<?php echo $asset_details->barcode_image; ?>" width='100px'>
                            <?php } else { ?>
                              <img src="<?php echo base_url(); ?>uploads/employee_images/default.jpg" width='100px'>
                            <?php } ?>
                        </div>
                        <div class="profile-data">
                            <div class="profile-data-name" style="color:#000;font-weight: bold">
                                <?php if(!empty($asset_details->barcode_number)) { echo ucwords($asset_details->barcode_number); } else { echo 'N/A'; } ?>
                                <?php if(!empty($replaceDetails)) { ?><br/>
                                    <small class="text-info">Replace Against</small>
                                <?php echo ucwords($replaceDetails->barcode_number); }  ?>
                            </div>
                        </div>                                 
                    </div> 
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> Details of  <span style="color:green; font-weight: bold;"><?php if(!empty($assets->asset_name)) { echo ucwords($assets->asset_name); } else { echo 'N/A'; } ?></span>  </h3>
                        <ul class="panel-controls">
                           <li><a href="<?php echo site_url('Dashboard') ?>" title="BACK" onclick="window.history.back();"><span class="fa fa-arrow-left"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">                                                                        
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Product SKU : </label>
                                    <div class="col-md-6 col-xs-6"><?php if(!empty($asset_details->barcode_number)) { echo $asset_details->barcode_number; } else { echo 'N/A'; } ?> </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Price : </label>
                                    <div class="col-md-6 col-xs-6"><i class="fa fa-inr"></i> <?php if(!empty($asset_details->price)) { echo number_format($asset_details->price,2); } else { echo 'N/A'; } ?></div>
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-3">Short Description : </label>
                                    <div class="col-md-9 col-xs-9" ><?php if(!empty($asset_details->short_desc)) { echo $asset_details->short_desc; } else { echo 'N/A'; } ?></div>
                                </div> 
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-3">Long Description : </label>
                                    <div class="col-md-9 col-xs-9" ><?php if(!empty($asset_details->long_desc)) { echo $asset_details->long_desc; } else { echo 'N/A'; } ?></div>
                                </div> 
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Warranty From : </label>
                                    <div class="col-md-6 col-xs-6" ><?php if(!empty($asset_details->warranty_from_date)) { echo $asset_details->warranty_from_date; } else { echo 'N/A'; } ?></div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Warranty To : </label>
                                    <div class="col-md-6 col-xs-6" ><?php if(!empty($asset_details->warranty_to_date)) { echo $asset_details->warranty_to_date; } else { echo 'N/A'; } ?></div>
                                </div> 
                            </div>
                            <?php if(!empty($branch_title)) {  ?>
                            <div class="clearfix">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Branch : </label>
                                    <div class="col-md-6 col-xs-6"><?php if($branch_title->branch_title !='') { echo $branch_title->branch_title; } else { echo 'N/A'; } ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                &nbsp;
                            </div>
                            <?php } ?>
                            <div class="clearfix">&nbsp;</div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-3">Warranty Description : </label>
                                    <div class="col-md-9 col-xs-9" ><?php if(!empty($asset_details->warranty_description)) { echo $asset_details->warranty_description; } else { echo 'N/A'; } ?></div>
                                </div> 
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-body profile" style="background-color: #fff;">
                        <center><label>Asset Image</label></center>
                        <div class="profile-image">
                            <?php if(!empty($asset_details->image)) { ?>
                              <img src="<?php echo base_url(); ?>uploads/assetimages/<?php echo $asset_details->image; ?>" width='100px'>
                            <?php } else { ?>
                              <img src="<?php echo base_url(); ?>uploads/employee_images/default.jpg" width='100px'>
                            <?php } ?>
                        </div> 
                        <?php if(!empty($asset_multiple_images)) { ?> 
                           <center><a href="<?php echo site_url('Dashboard/asset_all_images/'.$asset_details->id) ?>" class="label label-primary">View All Images</a></center> 
                        <?php } ?>                              
                    </div> 
                </div>
            </div>
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-2">&nbsp;</div>
            <?php if(!empty($assets_maintenance)) { ?>
              <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> Asset in maintenance  </h3>
                        
                    </div>
                    <div class="panel-body">                                                                        
                        <div class="col-md-12">
                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Price : </label>
                                    <div class="col-md-6 col-xs-6"> <?php if($assets_maintenance->price !='0') { ?><i class="fa fa-inr"></i> <?php echo number_format($assets_maintenance->price,2); ?> <?php } else { ?> <?php echo 'N/A'; ?> <?php } ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Send Date : </label>
                                    <div class="col-md-6 col-xs-6"><?php if($assets_maintenance->date !='0000-00-00') { echo $assets_maintenance->date; } else { echo 'N/A'; } ?></div>
                                </div>
                            </div>
                             <div class="clearfix">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Received Date : </label>
                                    <div class="col-md-6 col-xs-6"><?php if($assets_maintenance->received_date !='0000-00-00') { echo $assets_maintenance->received_date; } else { echo 'N/A'; } ?></div>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Quantity : </label>
                                    <div class="col-md-6 col-xs-6"><?php if($assets_maintenance->quantity !='0') { echo $assets_maintenance->quantity; } else { echo 'N/A'; } ?></div>
                                </div>
                            </div>
                             <div class="clearfix">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Status : </label>
                                    <div class="col-md-6 col-xs-6"><?php if($assets_maintenance->status =='Send') { ?> <span class="label-primary label"><?php echo $assets_maintenance->status; ?></span> <?php  } else { ?><span class="label-success label"><?php echo $assets_maintenance->status; ?></span> <?php } ?></div>
                                </div>
                            </div>
                             <div class="col-md-6">&nbsp;</div>
                              <div class="clearfix">&nbsp;</div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-3"> Description : </label>
                                    <div class="col-md-9 col-xs-9" ><?php if(!empty($asset_details->description)) { echo $asset_details->description; } else { echo 'N/A'; } ?></div>
                                </div> 
                            </div>
                          
                        </div>
                    </div>
                   
                </div>
            </div>
            <?php } ?> 
            <?php if(!empty($assets_scrap)) { ?>
           
              <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> Asset in Scrap  </h3>
                        
                    </div>
                    <div class="panel-body">                                                                        
                        <div class="col-md-12">
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Price : </label>
                                    <div class="col-md-6 col-xs-6"> <?php if($assets_scrap->price !='0') { ?><i class="fa fa-inr"></i><?php echo number_format($assets_scrap->price,2); ?> <?php  } else { ?> <?php echo 'N/A';?> <?php } ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Is Saleable : </label>
                                    <div class="col-md-6 col-xs-6"> <?php if($assets_scrap->sale =='Yes') {?>  <span class="label-warning label"><?php echo $assets_scrap->sale; ?></span> <?php } else {  ?> <span class="label-primary label"><?php echo $assets_scrap->sale; ?></span> <?php  } ?></div>
                                </div>
                            </div>
                           
                             <div class="clearfix">&nbsp;</div>
                            
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Status : </label>
                                    <div class="col-md-6 col-xs-6"><?php if($assets_scrap->status =='Pending') { ?> <span class="label-warning label"><?php echo $assets_scrap->status; ?></span> <?php  } elseif($assets_scrap->status =='Approved') { ?><span class="label-success label"><?php echo $assets_scrap->status; ?></span> <?php } elseif($assets_scrap->status =='Approved') { ?> <span class="label-primary label"><?php echo $assets_scrap->status; ?></span> <?php } ?></div>
                                </div>
                            </div>
                              <div class="clearfix">&nbsp;</div>
                          
                        </div>
                    </div>
                   
                </div>
            </div>
            <?php } ?> 
            <?php if(!empty($assets_audit_remarks)) { ?>
              <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> Audit Remarks  </h3>
                        
                    </div>
                    <div class="panel-body">                                                                        
                        <div class="col-md-12">
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Remark Type : </label>
                                    <div class="col-md-6 col-xs-6"> <?php if($remark_types->type !='') { ?><?php echo $remark_types->type; ?> <?php  } else { ?> <?php echo 'N/A';?> <?php } ?></div>
                                </div>
                            </div>
                            
                           
                             <div class="clearfix">&nbsp;</div>
                            
                              <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-6">Remark : </label>
                                    <div class="col-md-8 col-xs-8"><?php if($assets_audit_remarks->remarks !='') { ?> <?php echo $assets_audit_remarks->remarks; ?></span> <?php  } else { ?><?php echo 'N/A'; ?> <?php } ?></div>
                                </div>
                            </div>
                              <div class="clearfix">&nbsp;</div>
                          
                        </div>
                    </div>
                   
                </div>
            </div>
            <?php } ?>
            <div class="col-md-2">&nbsp;</div>
        </div>
    </div>
    
    </div>
 </div>                    
</div>
<!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('common/footer'); ?>
