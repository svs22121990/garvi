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
                        <li><a href="javascript:void(0)" title="Add Rebate" data-toggle="modal" onclick="add();" data-target="#myModal" ><span class="fa fa-plus"></span></a></li>
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
                                <!-- <th>Product Type Name</th> -->
                                <th>Description</th>
                                <th>Rebate %</th>
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
          <h4 class="modal-title"><strong>Add Rebate % </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">

         <div class="form-group">
          <!-- <div class="form-line">
          <label for="datetime">Product Type Name <span style="color:red">*</span>&nbsp;<span id="nameError1" style="color:red"></span></label>
            <select class="form-control select"  name="product_type_id" id="product_type_id" data-live-search="true">
             <option value="">--Select Product Type Name--</option>
             <?php
             //print_r($categories);//exit;
             foreach ($asset_types as $row_data) 
             {
              ?>
              <option value="<?php echo $row_data->id; ?>"><?php echo $row_data->type; ?> </option>
              <?php } ?>
            </select> &nbsp;
            
            </div> -->
            <div class="form-line">
            <label for="datetime">Description <span style="color:red">*</span>&nbsp;<span id="state_idError1" style="color:red"></span></label>
              <textarea class="form-control"  type="text" name="description" id="description" placeholder="Description"></textarea>&nbsp; 
            </div>

            <div class="form-line">
            <label>Rebate %<span style="color:red">*</span>&nbsp;<span id="city_nameError" style="color:red"></span></label>
            <input class="form-control"  type="text" name="rebate_percent" id="rebate_percent" placeholder="Rebate %" value="" size="35"/> &nbsp; 
            
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
          <h4 class="modal-title"><strong>Edit Rebate % </strong><span id="successEditEntry" style="color:green"></span></h4>
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
            <form method="post" action="<?= site_url('Rebate/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Rebate/delete') ?>">       
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
      
      //var product_type_id = $("#product_type_id").val();
      var description = $("#description").val();
      var rebate_percent = $("#rebate_percent").val();
      //var city_name1 = /^[a-zA-Z -]+$/;

      /*if($.trim(product_type_id) == "")
      {
        $("#nameError1").fadeIn().html("Please select product type");
        setTimeout(function(){$("#nameError1").fadeOut();},2000);
        $("#product_type_id").focus();
        return false;
      }*/
      if($.trim(description) == "")
      {
        $("#state_idError1").fadeIn().html("Please enter description");
        setTimeout(function(){$("#state_idError1").fadeOut();},2000);
        $("#description").focus();
        return false;
      }
      if($.trim(rebate_percent) == "")
      {
        $("#city_nameError").fadeIn().html("Please enter Rebate %");
        setTimeout(function(){$("#city_nameError").fadeOut();},2000);
        $("#rebate_percent").focus();
        return false;
      }

      var datastring  = "description="+description+"&rebate_percent="+rebate_percent;
      $.ajax({
        type : "post",
        url : "<?php echo site_url('Rebate/addData') ?>",
        data : datastring,
        success : function(response)
        {
        //alert(response);return false;
          if(response == 1)
          {
            $("#city_nameError").fadeIn().html("Rebate % already exists");
            setTimeout(function(){$("#city_nameError").fadeOut();},2000);
          }
          else
          {
          $(".close").click(); 
          $("#successStateEntry").fadeIn().html("<span class='label label-success'> Rebate has been Added successfully</span>");
            setTimeout(function(){$("#successStateEntry").fadeOut();},2000);
           $("#myModal").modal("hide"); 
              table.draw();
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
          url: "<?= site_url('Rebate/getUpdateName'); ?>",
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
  //var product_type_id = $("#product_type_id1").val();  
  var description = $("#description1").val();  
  var rebate_percent = $("#rebate_percent1").val(); 
  var updateId = $("#updateId").val();
   //var city_name2 = /^[a-zA-Z -]+$/;

  /*if($.trim(product_type_id) == "")
  {
    $("#EdittitleError1").fadeIn().html("Please select product type");
    setTimeout(function(){$("#EdittitleError1").fadeOut();},2000);
    $("#product_type_id1").focus();
    return false;
  }*/
  if($.trim(description) == "")
  {
    $("#EdittitleError2").fadeIn().html("Please enter description");
    setTimeout(function(){$("#EdittitleError2").fadeOut();},2000);
    $("#description1").focus();
    return false;
  }
  if($.trim(rebate_percent) == "")
  {
    $("#EdittitleError3").fadeIn().html("Please enter Rebate %");
    setTimeout(function(){$("#EdittitleError3").fadeOut();},2000);
    $("#rebate_percent1").focus();
    return false;
  }


  var datastring  = "description="+description+"&id="+updateId+"&rebate_percent="+rebate_percent;

  $.ajax({
    type : "post",
    url : "<?php echo site_url('Rebate/updateData') ?>",
    data : datastring,
    success : function(response)
    {    //alert(response);return false;
      if(response == 1)
      {
        $("#EdittitleError").fadeIn().html("Rebate % already exists.");
        setTimeout(function(){$("#titleError").fadeOut();},2000);
      }
      else
      {
      $(".close").click(); 
       $("#successStateEntry").fadeIn().html("<span class='label label-success'> Rebate % has been Updated successfully</span>");
       $("#myModaledit").modal("hide"); 
       table.draw();
       //setTimeout(function(){ window.location.reload(); },1000); 
     }

   }
 });
}

function add() {
  $('#product_type_id').val('');
  $('#description').val();
  $('#rebate_percent').val('');
}

</script>

<script type="text/javascript">
    function get_state_by_country(id)
    { 
      //alert(id);
      var datastring = "id="+id;

      $.ajax({
        type:"post",
        url:"<?php echo site_url('Cities/get_state'); ?>",
        data:datastring,
        success:function(returndata)
        { 
          //alert(returndata);
          
          $('#state_id').html(returndata).selectpicker('refresh');
        }
      });
    }
</script>