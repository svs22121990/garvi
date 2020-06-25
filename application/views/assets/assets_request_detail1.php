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
                         <form method="post" action="<?php echo site_url('Assets_request/approvedAssets_action/'.$request_id) ?>" id="assets_request">
                                <div class="panel-heading">
                                    <h3 class="panel-title"> <b><?php echo $heading ?></b></strong></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Assets/req_new_asset_list')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                 <div class="panel-body panel-body-table">
                                    <div class="row">
                                              <div class="col-md-12">&nbsp;</div>
                                             <div class="col-md-12">&nbsp; </div>
                                           <div class="col-md-12 table-responsive" id="assetData">
                                             <table class="table table-bordered table-striped table-actions example_datatable">
                                                        <thead>
                                                          <tr>
                                                            <th>Sr No</th>
                                                            <th>Asset Name</th>
                                                            <th>Request Quantity </th>
                                                            <th>Approved Quantity </th>
                                                            <th>Description </th>
                                                            <th>Status</th> 
                                                           </tr>
                                                        <thead>
                                                        <tbody id="clonetable_feedback">
                                                          
                                                        </tbody>
                                                       <tfoot>
                                                          <tr>

                                                              <th colspan="3">
                                                              <input type="text" class="append_ids" style="display: none;">
                                                              </th>
                                                                                    
                                                          </tr>
                                                      </tfoot>
                                                  </table> 
                                             </div>
                                           </div>
                                       <br><br>
                                        <input type="text" name="asset_details_id" id="selected_client" class="filter_search_data1" style="display: none;">
                                   </div>
                                   <?php if(!empty($assets_request_details)) { ?>
                                  <div  class="panel-footer">                                                                       
                                    <button class="btn btn-success"  type="submit">Approve Assets</button>
                                 </div>
                                 <?php } ?>
                             </div>   
                             </form>         
                            </div>
                          </div>
                        <div>
                      </div>
                  </div>
   
<script type="text/javascript">
  var url="<?= site_url('Assets/ajax_request_detail/'.$request_id); ?>";
  var actioncolumn="6";
</script>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
function getData(id)
{ 
  var site_url = $("#site_url").val();
  var url = site_url+"/Assets/getData"; 
  $.ajax({
      type:"post",
      url:url,
      data : { id : id},
      cache:false,
      success:function(returndata)
      {
        var obj = JSON.parse(returndata);
        $("#value").html(obj.description);
        $("#title").html(obj.title);
     }
   });
}
</script>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title"></h4>
      </div>
      <div class="modal-body" id="value">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>





 
