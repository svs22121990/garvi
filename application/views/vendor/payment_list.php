
<?php $this->load->view('common/header');
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
                          <form method="post" action="<?=site_url('Vendors/export_vendorPayment/'.$id);?>">
                                <div class="panel-heading">   

                                <div class="col-md-4 text-light-blue"><h3 class="panel-title"><strong><?= $heading.' - <sapn style="color:green">'.$vendor->shop_name; ?></sapn></strong></h3></div>
                                <div class="col-md-3">
                                       <select class="form-control filter_search_data1" id="type" name="type" > 
                                            <option value="">Select Type</option>
                                            <option value="Inward">Inward</option>
                                            <option value="Payment">Payment</option>
                                            <option value="Return">Return</option>
                                        </select>
                                </div>                      
                               
                                <div class="col-md-2">
                                    <input class="form-control filter_search_data2" name="from-date" id="from-date" placeholder="From Date" >
                                </div>
                                <div class="col-md-2">
                                    <input class="form-control filter_search_data3" name="to-date" id="to-date" placeholder="To Date" >
                                </div>  
                               <div class="col-md-1">
                                    <button type="submit" title="Export"  class="btn btn-default "><i class="fa fa-file-excel-o"></i> </button>
                                </div> 
                                                 
                            </div>    
                      
                    
                </form>
                <div class="panel-body">

                    <div class="">
                        <table class="table table-striped table-bordered table-hover example_datatable" >
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>PO Number</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Balance</th>                
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
    </div>

<script>
    var url = '<?= site_url('Vendors/ajax_vendorPayments/'.$id) ?>';
    var actioncolumn='0';
</script>

<?php $this->load->view('common/footer'); ?>