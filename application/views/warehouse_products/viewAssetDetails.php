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
                       <li><a href="javascript:void(0)" title="BACK" onclick="window.history.back();"><span class="fa fa-arrow-left"></span></a></li>
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
                </div> 
            </div>
        </div>
    </div> 
</div>
<!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('common/footer');?>
