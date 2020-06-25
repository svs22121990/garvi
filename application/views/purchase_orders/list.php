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
                <form method="post" action="<?=site_url('Purchase_orders/export')?>">
                <div class="panel-heading">                                
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span id="successCountryEntry"></span></h3>
                    <ul class="panel-controls">
                        <?php if($addPermission=='1'){?>
                          <li><?=$create; ?></li>
                        <?php }?>
                        <?php if($exportPermission=='1'){?>
                          <li><?=$export; ?>
                          <?php }?>
                              <button type="submit" style="display: none" id="subbtn"></button>
                          </li>
                        
                    </ul>
              
                </div> 
                 <div class="panel-heading">                                
                        <form method="post" action="<?=site_url('Purchase_orders/export');?>">
                        <div class="col-md-12">
                        
                          
                            <div class="col-md-3">                      
                                <select class="form-control filter_search_data4 select" id="vendor_id" name="vendor_id" onchange="GetAssets(this.value)" data-live-search="true"> 
                                    <option value="">Select Vendor</option>
                                    <?php foreach($vendors as $vendor) { ?>
                                    <option value="<?= $vendor->id; ?>"><?= $vendor->name.' ('.$vendor->shop_name.')';?></option>
                                    <?php } ?>
                                </select>
                            </div>
                             <div class="col-md-3">                       
                                <select class="form-control filter_search_data3 select" id="quotation_id" name="quotation_id"  data-live-search="true"> 
                                    <option value="">Select Quotaion</option>
                                    <?php if(!empty($quotations)){ foreach($quotations as $quotation) { ?>
                                        <option value="<?=$quotation->id?>"><?=$quotation->quotation_no; ?></option>
                                    <?php } } ?>
                                </select>
                          
                            </div>
                            <div class="col-md-3">
                                <input class="form-control filter_search_data1" name="from-date" id="from-date" placeholder="From Date" >
                            </div>
                            <div class="col-md-3">
                                <input class="form-control filter_search_data2" name="to-date" id="to-date" placeholder="To Date" >
                            </div>                      
                            
                        </div>
                    </form> 
                        
                   
              
                </div>
                </form>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                            <thead>
                                <tr>
                                    <th>Sr No</th>                   
                                    <th>Vendor Name</th>
                                    <th>Quotation No</th>
									<th>Order Number</th> 
                                    <th>Purchase Date</th>
                                    <th>Total Items</th>                                
                                    <th>Amount (Rs.)</th>                                    
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
<!-- END DEFAULT DATATABLE -->
<script>
    var url = '<?= site_url("Purchase_orders/ajax_manage_page")?>';
    var actioncolumn=8;

</script>

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
    function GetAssets(id)
    {
        $.ajax({
            type:'post',
            url:"<?= site_url('Purchase_orders/GetAssets') ?>",
            data:{id:id},
            success:function(response){
                $("#asset_type").html(response).selectpicker('refresh');
            }
        });
    }
  function clickSubmit(){
    $('#subbtn').click();
  }
</script>
