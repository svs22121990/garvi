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
                    <h3 class="panel-title"><strong><?= $heading.'&nbsp;('.$schedule_category_assets.')' ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                      
                        <li><a href="<?php echo site_url('Schedule_categories') ?>" ><span class="fa fa-arrow-left"></span></a></li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <!-- <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                    </ul>                                
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Asset</th>
                                <th>Product SKU </th>
                                <th>Barcode Image</th>
                                <th>Scan By</th>                                                  
                                <th>Date</th>                                                  
                                <th>Status</th>                                                  
                                <th width='250px'>Remark</th>                                                  
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

<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
      function checkStatus(id)
      {
        $("#statusId").val(id);
        $("#deleteId").val(id);
      }

       function get_desc(id)
      {
        var site_url = $("#site_url").val();
        var url = site_url+"/Schedule_categories/get_desc"; 
        $.ajax({
            type:"post",
            url:url,
            data : { id : id},
            cache:false,
            success:function(returndata)
            {
              var obj = JSON.parse(returndata);
              $("#value").html(obj.description);
           }
         });
      }
    </script>
<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Schedule_categories/changeStatus') ?>">       
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
<!--view remark start-->
<!--Remark Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">View Remark</h4>
      </div>
      <div class="modal-body">
        <p id="value"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--view remark end-->


