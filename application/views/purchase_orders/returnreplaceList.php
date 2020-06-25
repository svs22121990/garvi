<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<div class="page-content-wrap"> 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">                                
                    <h3 class="panel-title"><b class="text-danger"><?=ucfirst($flag)?></b> Assets of PO No <?=$po->order_number; ?></h3>
                    <ul class="panel-controls">
                        <li><a  href="<?= $backAction; ?>"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>                             
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-bordered table-striped table-actions"  id="datatable">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Asset Name</th>                           
                                <th>Product SKU</th>                           
                                <th>Image</th>                           
                                <th>Quantity </th>
                                <?php if($flag=='replace'){ ?>
                                    <th>Replaced From</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody> 
                        <?php if(!empty($assetsBarcode)){ $sr=1; foreach($assetsBarcode as $row){ ?>
                          <tr>
                            <td><?=$sr; ?></td>
                            <td><?=$row->asset_name; ?></td>
                            <td><?=$row->barcode_number; ?></td>
                            <td><img src="<?=base_url('assets/purchaseOrder_barcode/').$row->barcode_image; ?>"></td>
                            <td><?=$row->quantity; ?></td>
                            <?php if($flag=='replace'){ ?>
                            <td><?= $row->parent_barcode; ?></td>
                             <?php } ?>
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


           


