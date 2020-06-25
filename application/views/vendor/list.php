<?php $this->load->view('common/header'); ?>
<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>
   <!-- START BREADCRUMB -->
   <?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
<!-- START RESPONSIVE TABLES -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
 <input type="hidden" name="flag" value="<?php echo $flag?>">
            <div class="panel-heading">
                <h3 class="panel-title"><strong><?= $heading ?> &nbsp;</strong></h3>
                <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                      <?php if($act_import=='1'){ ?>
                        <li><?php if(!empty($download)) { ?>  
                               <?php  echo  $download; ?>
                             <?php } ?>         
                       </li>

                      <?php } if($act_import=='1'){?>
                      <li>
                         <?php if(!empty($import)) { ?>  
                               <?php  echo  $import; ?>
                          <?php } ?>
                      </li>
                      <?php }?>
                      <?php if($addPermission=='1'){?>
                      <li>
                         <?php if(!empty($page_create)) { ?>  
                           <?php  echo  $page_create; ?>
                         <?php } ?>
                      </li>
                      <?php }?>
                    </ul>  
            </div>

            <div class="panel-body ">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                        <thead>
                            <tr>
                                <th>Sr No</th>  
                                <th>Name</th>
                                <th>Shop Name</th>                                  
                                <th>Email</th>
                                <th>Mobile</th>                                    
                                <th>Outstanding Balance</th>                                    
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
<!-- END RESPONSIVE TABLES -->
</div>
<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">
            <form method="post" action="<?= $changeAction ?>">       
                <div class="modal-body" style="height: 100px;padding-top: 10%">
                    <center>
                        <input type="hidden" name="id" id="statusId" style="display: none;">
                        <span style="font-size: 16px">Are you sure to change the status?</span>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="uploadData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Vendors/import')?>" method="post" enctype="multipart/form-data" onsubmit="return checkXcel()">  
                <div class="modal-header">
                        <span style="font-size:20px">Upload Vendors</span>
                    </div>     
                <div class="modal-body" style="padding-top: 3%">
                    <input type="file" name="excel_file" id="excel_file" class="form-control">
                        &nbsp;<span style="color:red" id="errorexcel_file"></span>&nbsp;
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
<script type="text/javascript">

    <?php if(!empty($flag)){ ?>
    var url = '<?= site_url("Vendors/ajax_manage_page"); ?>?flag=Outstanding';
    <?php } else { ?>
    var url = '<?= $ajax_manage_page; ?>';
    <?php } ?>

    var actioncolumn="<?= $actioncolumn; ?>";
</script>
<?php $this->load->view('common/footer'); ?>

