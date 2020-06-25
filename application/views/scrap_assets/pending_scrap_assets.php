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
                                                            <th>Branch </th>                           
                                                            <th>Asset Type</th>                           
                                                            <th>Asset Name</th>                  
                                                            <th>Product SKU</th>
                                                            <th>Is Saleable</th>
                                                            <th>Price</th>
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
  var url="<?= site_url('Scrap_assets/ajax_manage_page_pending'); ?>";
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

<div class="modal fade" id="myModalStatus" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" id="close_1" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Change Status</h4>
            </div>
            <div class="modal-body">
             <center><p><b>If you want to approved or rejected click below</b></p></center>
            </div>
             <input type="hidden" name="id" id="statusId" value="">
            <div class="modal-footer">
                <button type="button" onclick="claim_status('Approved')" class="btn btn-success btn-sm pull-left" id="Approved">Approved</button>
                <button type="button" onclick="claim_status('Rejected')" class="btn btn-danger btn-sm pull-right" id="Reject" >Rejected</button>
            </div>
          </div>
        </div>
      </div>
       <div class="modal fade" id="myModal2" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">         
            <div class="modal-body">    
            <button type="button" class="close" id="close_1" data-dismiss="modal">&times;</button>
             <center><h5><b><span id="parameter"></span></b></h5></center>
            </div>
          </div>
        </div>
      </div>
<script type="text/javascript">
  function claim_status(status)
{ //alert('hii');return false;
  var id = $("#statusId").val(); 
  var site_url = $("#site_url").val();
  var url = site_url+"/Scrap_assets/changeStatus"; 
  $.ajax({
      type:"post",
      url:url,
       data : { id : id, status : status },
      cache:false,
      success:function(returndata)
      {
      
       if(returndata==1) 
        {
          $("#myModalStatus").modal("hide");
          $("#myModal2").modal("show");
          $("#parameter").html('Status change successfully');
          table.draw();
          setTimeout(function(){
          $("#myModal2 .close").click();},1500);
          return false;
        }
     }
   });
}
</script>


 
