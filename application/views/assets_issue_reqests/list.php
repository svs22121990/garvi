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
                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide" ><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3 class="panel-title"><span id="success" class=""></span></h3>
                    <ul class="panel-controls">
                     
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <!-- <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                    </ul>                                
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Branch</th>
                                <th>Employee</th>
                                <th>Asset</th>
                                <th>Product SKU</th>
                                <th>Remark Type</th>
                                <th>Remark</th>
                                <th width='250px'>Resolve Remark</th>
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
    </script>

<!--Add resolve remark-->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Remark</h4>
      </div>
      <div class="modal-body">
        <textarea class="form-control" rows="5" name="resolve" id="resolve" style="resize: none" placeholder="Resolve Remark"></textarea>
           
      <input type="hidden" name="id" id="statusId" style="display: none;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="return validations()" >Resolve</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
<!--  </form> -->
  </div>
</div>
<!--Add resolve remark-->
<!---->
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

<!---->



<!--Remark Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">View Resolve Remark</h4>
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
<script type="text/javascript">
  function get_desc(id)
  {
    var site_url = $("#site_url").val();
    var url = site_url+"/Assets_issue_request/get_desc"; 
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
  


<script type="text/javascript">
    function validations()
{  
    var resolve=$("#resolve").val().trim();
    var id=$("#statusId").val().trim();
    var site_url = $("#site_url").val();
    var url = site_url+"/Assets_issue_request/changeStatus";
  
     if(resolve=="")
      {
        $("#error_title").fadeIn().html("Required");
        $("#resolve").css("border-color","red");
        setTimeout(function(){$("#error_title").html("&nbsp;");$("#resolve").css("borderColor","#00A654")},5000)
        $("#resolve").focus();
        return false;
      } 

   $('.loader').show();
   
    $.ajax({
        type:"post",
        url:url,
        data:{resolve:resolve,id:id},
        cache:false,
        success:function(returndata)
        {
            var obj = JSON.parse(returndata);
            $("#count_isuue").html(obj.count_isuue);
            $("#resolve").val('');
            $("#myModal").modal("hide");
            $("#myModal2").modal("show");
            $("#parameter").html('Status change successfully');
            table.draw();
            setTimeout(function(){
            $("#myModal2 .close").click();},1500);
            $('.loader').hide();
            return false;
       }
     });
 }
</script>