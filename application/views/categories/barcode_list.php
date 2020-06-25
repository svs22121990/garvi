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
                    <h3 class="panel-title"><strong><?= $heading ?> - <span class="text-success"><?=$category_name;?></span></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span class="label label-success text-center" style="margin-bottom:0px; display: none" id="successCatEntry"></span></h3>
                    <ul class="panel-controls">
              
                        <li><a target="_blank" href="<?=site_url('Categories/printgenerateBarcode/'.$id);?>" title="Print Barcode"><span class="fa fa-print"></span></a></li>
                        <li><a href="<?=site_url('Categories/generateBarcode/'.$id.'/new');?>" title="Generate Barcodes"><span class="fa fa-plus"></span></a></li>
                        <li><a href="<?=site_url('Categories');?>"><span class="fa fa-arrow-left"></span></a></li>
                       
                    </ul>                                
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Barcode No</th>
                                <th>Barcode Image</th>                                                  
                                <th>Is Used</th>                                                  
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
  <div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Categories/changeBarcodeStatus/'.$id) ?>">       
                <div class="modal-body" style="height: 100px;padding-top: 10%">
                    <center>
                        <input type="hidden" name="id" id="statusId" style="display: none;">
                        <span style="font-size: 16px">Are you sure to change the status?</span>
                    </center>
                </div>
                <div class="modal-footer" >
                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--IMport strart-->
<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
    function checkStatus(id)
      {
        $("#statusId").val(id);
        $("#deleteId").val(id);
      }
</script>

<?php $this->load->view('common/footer');?>



           


