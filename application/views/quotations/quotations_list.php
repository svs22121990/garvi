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
                        <?php if($addPermission=='1'){?>
                        <li><a href="<?= site_url('Quotations/create');?>" ><span class="fa fa-plus"></span></a></li>
                        <?php }?>
                      
                    </ul>
              
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Quotation No.</th>
                                <th>Quotation Req. No.</th>
                                <th>Assets Name</th>
                                <th>Vendors Name</th>
                                <th>Request Quantity</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END DEFAULT DATATABLE -->
<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">       
             <form method="post" id="quotation_edit" action="<?php echo site_url('Quotations/Quotation_approved') ?>">
                <div class="modal-body" style="height: 100px;padding-top: 10%">
                    <center>
                       <input type="hidden" name="quotation_id" id="quotation_id" >
                        <span style="font-size: 16px">Are you sure to <strong class="text-success"> Approve </strong> this quotation?</span>
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
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>
<script type="text/javascript">
      function checkStatus(id)
      {
        $("#quotation_id").val(id);
      }

      $(document).ready(function(){
        $(".preloader").show();
      })
</script>


 