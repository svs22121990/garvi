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
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span id="successCountryEntry"></span></h3>
                    <ul class="panel-controls">
                        <?php if($addPermission=='1'){ ?>
                          <li><a href="<?= $createAction ?>"><span title="Create" class="fa fa-plus"></span></a></li>
                        <?php } ?>
                        
                    </ul>
              
                </div> 
             
                <div class="panel-body">
                    <div class="">
                        <table class="table table-striped table-bordered table-hover example_datatable" >
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Order Number</th>
                                    <th>Asset name</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
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
    var url = '<?= site_url("Purchase_returns/ajax_manage_page")?>';
    var actioncolumn=0;
</script>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
    
</script>