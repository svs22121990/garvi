<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<!-- START BREADCRUMB -->
<?//= $breadcrumbs ?>
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
                    <h3 class="panel-title">Assets</h3>
                     <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                        <button class="create_button_color btn btn-danger btn-xs pull-right" onclick="window.history.back()">Back</button>
                    </ul>                                
                </div>
                <div class="panel-body ">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover" >
	                    <thead>
	                        <tr>
	                            <th>Assets Type</th>                                  
	                            <th>Category</th>
	                            <th>Sub Category</th>
	                            <th>Asset Name</th>
	                            <th>Brand</th>
	                            <th>Price</th>
	                            <th>Detail</th>
	                            <th>Unit</th>
	                            <th>Reason</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <?php
	                            foreach ($existAssets as $assets):
	                        ?>
	                        <tr>
	                            <td><?= $assets[0];?></td>
	                            <td><?= $assets[1];?></td>
	                            <td><?= $assets[2];?></td>
	                            <td><?= $assets[3];?></td>
	                            <td><?= $assets[4];?></td>
	                            <td><?= $assets[5];?></td>
	                            <td><?= $assets[6];?></td>
	                            <td><?= $assets[7];?></td>
	                            <td><?= $assets[8];?></td>
	                        </tr>
	                    <?php endforeach; ?>
	                    </tbody>
	                </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>





<?php $this->load->view('common/footer');?>


           


