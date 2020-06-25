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
                        <li><a href="#" data-toggle="modal" data-target="#myModal" ><span class="fa fa-plus"></span></a></li>
                      <?php }?>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        
                    </ul>
              
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable table-condensed ">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Type</th>
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

<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Payment_types/changeStatus') ?>">       
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


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Payment Type </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
                <label>Type:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="typeError" style="color:red"></span></label>
               
                <input type="text" name="type" id="type" value="" class="form-control" placeholder="Type" size="35"/>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="saveData()">Submit</button>
          <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="myModaledit" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Edit Type </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
            <label>Type:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="typeError1" style="color:red"></span></label>
            <input type="text" name="type" id="titletype" value="" class="form-control" placeholder="Type" size="35"/>
            
            
          </form>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id" id="updateId">
          <button type="button" class="btn btn-round btn-success" id="statusEdiBtn" onclick="updateData()">Submit</button>
          <button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

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

<?php $this->load->view('common/footer');?>

 <script type="text/javascript">
    function saveData()
    {
        var type = $("#type").val(); 
        if($.trim(type) == "")
        {
              $("#typeError").fadeIn().html("Please enter type");
              setTimeout(function(){$("#typeError").fadeOut();},4000);
              $("#type").focus();
              return false;
        }

        var datastring  = "type="+type;
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Payment_types/addData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#typeError").fadeIn().html("Type already exist");
              setTimeout(function(){$("#typeError").fadeOut();},8000);
            }
            else
            {
               $(".close").click(); 
               $("#successCountryEntry").fadeIn().html("<span class='label label-success'> Type has been Added successfully</span>");
                setTimeout(function(){$("#successCountryEntry").fadeOut();},8000);
                $("#myModal").modal("hide");
                table.draw();
          
            }
           
          }
        });
    }
</script>

<!--EDIT DATA VIA AJAX STARTS-->
<script type="text/javascript">

function getEditvalue(rowid)
    {     
        $("#updateId").val(rowid);
        //alert(rowid);
        $.ajax({
          type: "POST",
          url: "<?= site_url('Payment_types/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          { 
            var obj = $.parseJSON(result);
            var type = obj.type;
            $("#titletype").val(type);
           
          }             
        });

   }

    function updateData()
    {
        var type = $("#titletype").val();
        var updateId = $("#updateId").val();
       
       if($.trim(type) == "")
        {
              $("#typeError1").fadeIn().html("Please enter type");
              setTimeout(function(){$("#typeError1").fadeOut();},8000);
              $("#titletype").focus();
              return false;
        }

        var datastring  = "type="+type+"&id="+updateId;
       
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Payment_types/updateData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#typeError1").fadeIn().html("Type already exist");
              setTimeout(function(){$("#typeError1").fadeOut();},8000);
            }
            else
            {
               $(".close").click();
               $("#successCountryEntry").fadeIn().html("<span class='label label-success'> Type has been updated successfully</span>");
                setTimeout(function(){$("#successCountryEntry").fadeOut();},4000);
               $("#myModaledit").modal("hide"); 
                table.draw();
              
            }
           
          }
        });
    }

</script>