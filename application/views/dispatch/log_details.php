<?php $this->load->view('common/header'); ?>
<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
<!-- START RESPONSIVE TABLES -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Log Details of <b><span style="color:green"><?php echo ucwords($assets_name->asset_name); ?></span></b>
                </h3>
                <ul class="panel-controls">
                    <?php if(!empty($this->uri->segment(4))) { ?>
                        <li><a href="<?php echo site_url('Branches/view/'.$this->uri->segment(4)); ?>" title="Back"><span class="fa fa-arrow-left"></span></a></li>
                        <li><a href="javascript:void(0)" onclick="clickexport()" title="Export"><span class="fa fa-file-excel-o"></span></a></li>
                    <?php } else { ?>
                        <li><a href="<?php echo site_url('Assets/index'); ?>" title="Back"><span class="fa fa-arrow-left"></span></a></li>
                        <li><a href="javascript:void(0)" onclick="clickexport()" title="Export"><span class="fa fa-file-excel-o"></span></a></li>
                    <?php } ?>
                </ul> 
            </div>

            <div class="panel-heading">                                
                    <form method="post" action="<?=site_url('Assets/export/'.$id.'/'.$branch_id);?>">
                        <div class="col-md-12">                                                  
                            <div class="col-md-3">                                            
                                <select class="form-control filter_search_data5 select" id="type" name="type" data-live-search="true"> 
                                    <option value="">Select Type</option>
                                    <option value="Received">Purchase</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Returned">Returned</option>
                                    <option value="TReceived">Received</option>
                                    <option value="OpenStock">Open Stock</option>
                                </select>
                            </div>                                                                    
                        </div>
                        <input type="submit" name="btn_sub" id="myclick" value="Export" style="display: none;">
                    </form>                                                          
            </div>

            <div class="panel-body ">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Available Quantity</th>
                                <th>Description</th>
                                <th>Transfer From</th>
                                <th>Transfer To</th>
                                <th>Source</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>                                                

    </div>
</div>
<!-- END RESPONSIVE TABLES -->
</div>




<!-- END PAGE CONTENT WRAPPER  -->
<script type="text/javascript">
    var url="<?= $ajax_manage_pages; ?>";
    var actioncolumn="<?= $actioncolumns; ?>";
</script>

<?php $this->load->view('common/footer'); ?>

<script type="text/javascript">
    function clickexport()
    {       
        $("#myclick").click();
    }
</script>

