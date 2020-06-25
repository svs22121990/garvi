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
                          <?php $this->load->view('vendor/common') ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading."-<sapn style='color:green'>".$vendor->shop_name.'</span>' ?></h3>
                                    <ul class="panel-controls">
                                       <li><a  onclick="window.history.back()" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body">
                        <table class="table table-striped table-bordered table-hover example_datatable" >
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>PO No.</th>
                                    <th>Vehicle No.</th>                                  
                                    <th>Driver Name</th>
                                    <th>Labour Charge (Rs.)</th>                                    
                                    <th>Extra Vendor Charge (Rs.)</th>                                    
                                    <th>Charged Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                <div class="panel-footer">
                <a class="btn btn-default" href="<?=site_url('Vendors/index');?>">Back</a>                                    
                  
                </div>
            </div>
            
        </div>
    </div>                    
    
    </div>
                <!-- END PAGE CONTENT WRAPPER -->
<script>
    var url = '<?= site_url("Vendors/ajax_vehicleManagement/".$id)?>';
    var actioncolumn=0;
</script>

<?php $this->load->view('common/footer'); ?>