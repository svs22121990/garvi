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

    .col-md-2
    {
        width: 50% ! important;
    }
     .col-md-4
    {
        width: 50% ! important;
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
                                    <ul class="panel-controls">
                                        <li><a href="<?=site_url('Maintenance_scheduling/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body">                                                                        
                                    
                                    <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Asset Type</label>
                                                    <div class="col-md-10">
                                                        <input readonly type="text" class="form-control" id="shop_name" value="<?= $rows->type; ?>">     
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Vendor       
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input readonly type="text" class="form-control" id="name" value="<?= $rows->name; ?>">        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Asset       
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input readonly type="text" class="form-control" id="email" value="<?= $rows->asset_name; ?>">    
                                                    </div>
                                                </div>
                                            </div>
                                          <div class="col-md-6">
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-md-12">Barcode    
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input readonly type="text" class="form-control" id="email" value="<?= $rows->barcode_number; ?>">    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="col-md-12">Barcode Image      
                                                    </label>
                                                    <div class="col-md-10">
                                                        <img src="<?php echo base_url() ?>/assets/purchaseOrder_barcode/<?php echo $rows->barcode_image; ?>">  
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Date <span style="color:red;">*</span>
                                                        <span class="" id="erroremail" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input readonly type="text" name="date" class="form-control date" id="date" value="<?= $rows->date; ?>">    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-12">Deacription 
                                                        <span class="" id="erroremail" style="color: red"></span>      
                                                    </label>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control" name="description"><?php echo $rows->description ?></textarea> 
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="id" value="<?php echo $rows->id ?>">

                                        </div>
                                    
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

<?php $this->load->view('common/footer'); ?>
<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript">
  $(".date").datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      numberOfMonths: 1,
      minDate: 0,
      
    }); 
</script>
 

