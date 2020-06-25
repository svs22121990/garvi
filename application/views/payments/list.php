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
                <form method="post" action="<?=site_url('Payments/export');?>">
                 <div class="panel-heading">                                
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span id="successCountryEntry"></span></h3>
                    <ul class="panel-controls">
                        <?php if($addPermission=='1'){?>
                          <li><?=$page_create; ?></li>
                        <?php }?>
                        <?php if($exportPermission=='1') { ?>  
                          <li> <a href="javascript:void(0)" title="Export" onclick="document.getElementById('export-submit').click()"><i class="fa fa-file-excel-o"></i></a><button type="submit" id="export-submit" style="display: none"></button>
                          </li>
                        <?php } ?>

                    </ul>
              
                 </div> 
                 <div class="panel-heading">                                
                            
                            <div class="col-md-3">  
                                <select class="form-control filter_search_data1" id="vendor_id" name="vendor_id" > 
                                    <option value="">Select Vendor</option>
                                    <?php foreach($vendors as $row){ ?>
                                    <option value="<?= $row->id; ?>"><?= ucwords($row->shop_name); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">  
                                <select class="form-control filter_search_data2" id="type" name="type" > 
                                    <option value="">Select Type</option>
                                    <option value="Inward">Inward</option>
                                    <option value="Payment">Payment</option>
                                    <option value="Return">Return</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control filter_search_data3" name="from-date" id="from-date" placeholder="From Date" >
                            </div>
                            <div class="col-md-3">
                                <input class="form-control filter_search_data4" name="to-date" id="to-date" placeholder="To Date" >
                            </div>
                 </div>
                </form> 
                <div class="panel-body">
                        <table class="table table-striped table-bordered table-hover example_datatable" >
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Vendor</th>
                                    <th>PO Number</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Outstanding Balance</th>
                                    <th>Type</th>
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
<script>
    var url = '<?= site_url("Payments/ajax_manage_page")?>';
    var actioncolumn=0;
</script>
<?php $this->load->view('common/footer');?>