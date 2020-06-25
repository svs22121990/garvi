<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<!-- START BREADCRUMB -->
   <?= $breadcrumbs ?>
<!-- END BREADCRUMB -->   
<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<div class="page-content-wrap"> 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">                                
                    <h3 class="panel-title"><?php echo $heading; ?></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span id="successCountryEntry"></span></h3>
                    <ul class="panel-controls">
                        <?php if($addPermission=='1'){?>
                          <li><a href="<?= $createAction ?>"><span title="Create" class="fa fa-plus"></span></a></li>
                        <?php }?>
                    </ul>
              
                </div> 
             
                <div class="panel-body">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover example_datatable" >
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Order Number</th>
                                    <th>Asset</th>
                                    <th>Quantity</th>
                                    <th>Remain Quantity</th>
                                    <th>Unit</th>
                                    <th>Remark</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var url = '<?= site_url("Purchase_replace/ajax_manage_page")?>';
    var actioncolumn=8;
</script>
<?php $this->load->view('common/footer');?>