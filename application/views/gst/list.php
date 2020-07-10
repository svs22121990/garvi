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

            <div class="panel-heading">
                <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                <h3><span id="successStateEntry"></span></h3>
                    <ul class="panel-controls">
                      <?php if($addPermission=='1'){?>
                        <li><a href="javascript:void(0)" title="Add GST %" data-toggle="modal" data-target="#myModal" ><span class="fa fa-plus"></span></a></li>
                      <?php }?>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>  
            </div>

            <div class="panel-body ">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Category Name</th>
                                <th>Code</th>
                                <th>HSN</th>
                                <th>GST %</th>
                                <th>Markup %</th>
                                <th>Status</th>
                                <th>Actions</th>
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


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add GST % </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">

         <div class="form-group">
          <div class="form-line">
          <label for="datetime">Category Name: <span style="color:red">*</span>&nbsp;<span id="nameError1" style="color:red"></span></label>
            <select class="form-control select"  name="category_id" id="category_id" data-live-search="true">
             <option value="">--Select Category Name--</option>
             <?php
             //print_r($categories);//exit;
             foreach ($categories as $row_data) 
             {
              ?>
              <option value="<?php echo $row_data->id; ?>"><?php echo $row_data->title; ?> </option>
              <?php } ?>
            </select> &nbsp;
            </div>

            <div class="form-line">
            <label for="datetime">Code: <span style="color:red">*</span>&nbsp;<span id="codeError" style="color:red"></span></label>
              <input class="form-control"  type="text" name="code" id="code" placeholder="Code" value="" size="2"/> &nbsp; 
            </div>

            <div class="form-line">
            <label for="datetime">HSN: <span style="color:red">*</span>&nbsp;<span id="state_idError1" style="color:red"></span></label>
              <input class="form-control"  type="text" name="hsn" id="hsn" placeholder="HSN" value="" size="35"/> &nbsp; 
            </div>

            <div class="form-line">
              <label>GST %<span style="color:red">*</span>&nbsp;<span id="city_nameError" style="color:red"></span></label>
              <input class="form-control"  type="text" name="gst_percent" id="gst_percent" placeholder="GST %" value="" size="35"/> &nbsp;   
            </div>
             <div class="form-line">
                 <label>Markup %<span style="color:red">*</span>&nbsp;<span id="city_nameError2" style="color:red"></span></label>
                 <input class="form-control"  type="text" name="markup_percent" id="markup_percent" placeholder="Markup %" value="" size="35"/> &nbsp;

             </div>
        </div> 

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
          <h4 class="modal-title"><strong>Edit GST % </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <form method="post">
        <div class="modal-body" id="getEditForm">
          
      </div>
        </form>
      <div class="modal-footer">
        <input type="hidden" name="id" id="updateId">
        <button type="button" class="btn btn-round btn-success"  id="statusEdiBtn"  onclick="updateData();">Submit</button>
        <button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('GST/changeStatus') ?>">       
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
<div class="modal fade" id="deleteData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">   
            <form method="post" action="<?= site_url('GST/delete') ?>">       
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <center>
                        <input type="hidden" name="id" id="deleteId" style="display: none;">
                        <span style="font-size: 16px"> 
                          If you want to delete this record,all associated records will be deleted permanently from the Database. 
                        <br>Are you sure? </span>
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


<!-- END PAGE CONTENT WRAPPER -->
<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>
<?php $this->load->view('common/footer'); ?>

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
  <script type="text/javascript">
    function saveData()
    {
      var category_id = $("#category_id").val();
      var code = $("#code").val();  
      var hsn = $("#hsn").val();
      var gst_percent = $("#gst_percent").val();
      var markup_percent = $("#markup_percent").val();
      //var city_name1 = /^[a-zA-Z -]+$/;

      if($.trim(category_id) == "")
      {
        $("#nameError1").fadeIn().html("Please select category");
        setTimeout(function(){$("#nameError1").fadeOut();},2000);
        $("#category_id").focus();
        return false;
      }
      if($.trim(code) == "")
      {
        $("#codeError").fadeIn().html("Please enter Code");
        setTimeout(function(){$("#codeError").fadeOut();},2000);
        $("#code").focus();
        return false;
      } else {
          $.ajax({
            type: "POST",
            url: "<?= site_url('GST/checkCode'); ?>",
            data: {code:$.trim(code)},
            cache: false,       
            success: function(result)
            {
              if(result == 1)
              {
                $("#codeError").fadeIn().html("Code Already in use.");
                setTimeout(function(){$("#codeError").fadeOut();},2000);
                return false;
              }
            }             
          });
      }
      if($.trim(hsn) == "")
      {
        $("#state_idError1").fadeIn().html("Please enter hsn");
        setTimeout(function(){$("#state_idError1").fadeOut();},2000);
        $("#hsn").focus();
        return false;
      }
      if($.trim(gst_percent) == "")
      {
        $("#city_nameError").fadeIn().html("Please enter GST %");
        setTimeout(function(){$("#city_nameError").fadeOut();},2000);
        $("#gst_percent").focus();
        return false;
      }
      if($.trim(markup_percent) == "")
      {
          $("#city_nameError2").fadeIn().html("Please enter Markup %");
          setTimeout(function(){$("#city_nameError2").fadeOut();},2000);
          $("#markup_percent").focus();
          return false;
      }
      var datastring  = "category_id="+category_id+"&code="+code+"&hsn="+hsn+"&gst_percent="+gst_percent+"&markup_percent="+markup_percent;
      var table = $('.example_datatable').DataTable();
      $.ajax({
        type : "post",
        url : "<?php echo site_url('GST/addData') ?>",
        data : datastring,
        success : function(response)
        {
        //alert(response);return false;
          if(response == 1)
          {
            $("#city_nameError").fadeIn().html("GST % already exist");
            setTimeout(function(){$("#city_nameError").fadeOut();},2000);
          }
          else if(response == 3)
          {
            $("#codeError").fadeIn().html("Code Already in use.");
            setTimeout(function(){$("#codeError").fadeOut();},2000);
          } else {
          $(".close").click(); 
          $("#successStateEntry").fadeIn().html("<span class='label label-success'> GST has been Added successfully</span>");
            setTimeout(function(){$("#successStateEntry").fadeOut();},2000);
            $("#myModal").modal("hide"); 
            $("#category_id").val("");
            $("#code").val("");  
            $("#hsn").val("");
            $("#gst_percent").val("");
            $("#markup_percent").val("");
            table.ajax.reload();
            //setTimeout(function(){ window.location.reload(); },1000); 
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
          url: "<?= site_url('GST/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          { 
            $("#getEditForm").html(result);
          }             
        });
    }

function updateData()
{ 
  var category_id = $("#category_id1").val();  
  var code = $("#code1").val();  
  var hsn = $("#hsn1").val();  
  var gst_percent = $("#gst_percent1").val();
    var markup_percent = $("#markup_percent1").val();
    var updateId = $("#updateId").val();
   //var city_name2 = /^[a-zA-Z -]+$/;

  if($.trim(category_id) == "")
  {
    $("#EdittitleError1").fadeIn().html("Please select category");
    setTimeout(function(){$("#EdittitleError1").fadeOut();},2000);
    $("#category_id1").focus();
    return false;
  }
  if($.trim(code) == "")
  {
    $("#codeErrorEdit").fadeIn().html("Please enter Code");
    setTimeout(function(){$("#codeErrorEdit").fadeOut();},2000);
    $("#code1").focus();
    return false;
  }
  if($.trim(hsn) == "")
  {
    $("#EdittitleError2").fadeIn().html("Please enter HSN");
    setTimeout(function(){$("#EdittitleError2").fadeOut();},2000);
    $("#hsn1").focus();
    return false;
  }
  if($.trim(gst_percent) == "")
  {
    $("#EdittitleError3").fadeIn().html("Please enter GST %");
    setTimeout(function(){$("#EdittitleError3").fadeOut();},2000);
    $("#gst_percent1").focus();
    return false;
  }
    if($.trim(markup_percent) == "")
    {
        $("#EdittitleError3").fadeIn().html("Please enter Markup %");
        setTimeout(function(){$("#EdittitleError3").fadeOut();},2000);
        $("#markup_percent").focus();
        return false;
    }

  var datastring  = "category_id="+category_id+"&code="+code+"&id="+"&hsn="+hsn+"&id="+updateId+"&gst_percent="+gst_percent+"&markup_percent="+markup_percent;
  var table = $('.example_datatable').DataTable();
  $.ajax({
    type : "post",
    url : "<?php echo site_url('GST/updateData') ?>",
    data : datastring,
    success : function(response)
    {    //alert(response);return false;
      if(response == 1)
      {
        $("#EdittitleError").fadeIn().html("GST % already exist");
        setTimeout(function(){$("#titleError").fadeOut();},2000);
      } else if(response == 3)
      {
        $("#codeErrorEdit").fadeIn().html("Code Already in use.");
        setTimeout(function(){$("#codeErrorEdit").fadeOut();},2000);
      } 
      else
      {
      $(".close").click(); 
       $("#successStateEntry").fadeIn().html("<span class='label label-success'> GST % has been Updated successfully</span>");
       $("#myModaledit").modal("hide"); 
       table.ajax.reload();
       //setTimeout(function(){ window.location.reload(); },1000); 
     }

   }
 });
}

</script>