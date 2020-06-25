 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<style type="text/css">
    div.scrollable {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: auto;
}
</style>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                         <div class="row">
                            <div class="clearfix">&nbsp;</div>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                         
                                <div class="panel-heading">
                                    <h3 class="panel-title"> <b><?php echo ucfirst($branch_title).'</b>&nbsp;-&nbsp;'.ucfirst($assets->asset_name) ?><strong> (Total Quantity  <span ><?php echo $astQty; ?></span>)</strong></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Assets/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                 <div class="panel-body panel-body-table">
                                    <div class="row">
                                              <div class="col-md-12">&nbsp;</div>
                                             <div class="col-md-12">&nbsp;</div>
                                           <div class="col-md-12 table-responsive" id="assetData">
                                             <table class="table table-bordered table-striped table-actions example_datatable">
                                                        <thead>
                                                          <tr>
                                                            <th>Sr</th>                           
                                                            <th>Barcode Image</th>                           
                                                            <th>Product SKU</th>                  
                                                            <th>Image</th>
                                                            <th>Action</th>
                                                          </tr>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                  </table> 
                                             </div>
                                           </div>
                                       <br><br>
                                   </div>
                             </div>                                                
                           </div>
                          </div>
                        <div>
                      </div>
                  </div>
   
<script type="text/javascript">
  var url="<?= site_url('Assets/getAssetTransferViewAjax/'.$asset_branch_mapping_id); ?>";
  var actioncolumn="5";
</script>

<?php $this->load->view('common/footer');?>




 
