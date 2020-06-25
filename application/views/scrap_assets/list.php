 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<style type="text/css">
    div.scrollable {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: auto;
}
</style>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                         <div class="row">
                            <div class="clearfix">&nbsp;</div>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                         
                                <div class="panel-heading">
                                    <h3 class="panel-title"> <b><?php echo $heading ?></b></strong></h3>
                                     <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span><center><span id="error1" style="color: red"></span></center></h3>
                                    <ul class="panel-controls">
                                        <?php if($addPermission=='1'){?>
                                         <li><a href="<?= site_url('Scrap_assets/create')?>" ><span class="fa fa-plus"></span></a></li>
                                         <?php }?> 
                                    </ul>
                                </div>
                                 <div class="panel-body panel-body-table">
                                    <div class="row">
                                              <div class="col-md-12">&nbsp;</div>
                                             <div class="col-md-12">&nbsp;</div>
                                           <div class="col-md-12 table-responsive" id="assetData">
                                             <table class="table table-bordered table-striped table-actions example_datatable">
                                                        <thead>
                                                          <tr>
                                                            <th>Sr</th>                           
                                                            <th>Asset Type</th>                           
                                                            <th>Asset Name</th>                  
                                                            <th>Product SKU</th>
                                                            <th>Is Saleable</th>
                                                            <th>Price</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                           </tr>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                  </table> 
                                             </div>
                                           </div>
                                       <br><br>
                                   </div>
                             </div>                                                
                           </div>
                          </div>
                        <div>
                      </div>
                  </div>
   
<script type="text/javascript">
  var url="<?= site_url('Scrap_assets/ajax_manage_page'); ?>";
  var actioncolumn="7";
</script>

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
      function checkStatus(id)
      {
        $("#statusId").val(id);
        $("#deleteId").val(id);
      }

      $(document).ready(function(){
        $(".preloader").show();
      })
</script>
<div class="modal fade" id="deleteData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">   
            <form method="post" action="<?= site_url('Scrap_assets/delete') ?>">       
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <center>
                        <input type="hidden" name="id" id="deleteId" style="display: none;">
                        <span style="font-size: 16px"> 
                          
                        <br>Are you sure want to delete? </span>
                    </center>
                </div>
                <div class="modal-footer">
                    
                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>





 
