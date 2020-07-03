<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');

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
                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                   
                    <ul class="panel-controls">
                         <li><a href="<?= site_url('Assets_request/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <!-- <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                    </ul>                                
                </div>
                <div class="panel-body ">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions example_datatable">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Asset Name</th>                           
                                <th>Request Quantity </th>
                                <th>Approved Quantity </th>
                                <th>Description</th>
                                <th>Status</th>
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
</div>

 

<!-- END DEFAULT DATATABLE -->
<script type="text/javascript">
  var url="<?= site_url('Assets_request/approved_assets_request_ajax/'.$request_id); ?>";
  var actioncolumn="5";
</script>
<script type="text/javascript">
  
function getData(id)
{ 
  var site_url = $("#site_url").val();
  var url = site_url+"/Assets_request/getData"; 
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
<?php $this->load->view('common/footer');?>
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


           


