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
                                                            <th>Request No</th>                           
                                                            <th>Branch</th>                  
                                                            <th>Employee</th>
                                                            <th>Total Assets</th>
                                                            <th>Total Quantity</th>
                                                            <th>Total Approved Quantity</th>
                                                            <th>Status</th>  
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
  var url="<?= site_url('Assets_request/ajax_manage_page'); ?>";
  var actioncolumn="6";
</script>

<?php $this->load->view('common/footer');?>




 
