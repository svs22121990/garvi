<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->

<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<div class="page-content-wrap"> 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">                                
                    <h3 class="panel-title"><b class="text-success"><?=$flag; ?></b> Assets of type - <?= $assetType; ?></h3>
                                               
                </div>
                <div class="panel-body ">
                    <table class="table table-bordered table-striped" id="datatable">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Asset Name</th>                           
                                <th>Product SKU</th>                           
                                <th>Quantity</th>                                                        
                               
                            </tr>
                        </thead>
                        <tbody> 
                        <?php if(!empty($assetData)){ $sr=1; foreach($assetData as $row){ ?>
                          <tr>
                            <td><?=$sr; ?></td>
                            <td><?=$row->asset_name; ?></td>
                            <td><img src="<?=base_url('assets/purchaseOrder_barcode/'.$row->barcode_image);?>"></td>
                            <td><?=$row->quantity; ?></td>
                        
                          </tr>
                          <?php $sr++; }  } ?>                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- END DEFAULT DATATABLE -->


<?php $this->load->view('common/footer');?>
<script type="text/javascript">
	
$('#datatable').dataTable();
</script>



           


