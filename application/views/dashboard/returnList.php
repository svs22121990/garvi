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
                    <h3 class="panel-title"><b class="text-danger">Returned</b> Assets of type - <?= $assetType; ?></h3>
                                               
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-bordered table-striped table-actions"  id="datatable">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Asset Name</th>                           
                                <th>Barcode No</th>                           
                                <th>Return Quantity </th>
                                <th>Description</th>                                                                   
                                <th>Date</th>                                                                   
                               
                            </tr>
                        </thead>
                        <tbody> 
                        <?php if(!empty($returnData)){ $sr=1; foreach($returnData as $row){ ?>
                          <tr>
                            <td><?=$sr; ?></td>
                            <td><?=$row->asset_name; ?></td>
                            <td><?=$row->barcode_number; ?></td>
                            <td>1</td>
                            <td><?=$row->remark; ?></td>
                            <td><?= date('d M Y',strtotime($row->modified)); ?></td>
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


           


