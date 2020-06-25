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
                        <li><a href="javascript:void(0)" title="Add Helpline" data-toggle="modal" data-target="#myModal" ><span class="fa fa-plus"></span></a></li>
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
                                <th>Helpline Type</th>
                                <th>Contact Person</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Address</th>
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
          <h4 class="modal-title"><strong>Add Helpline </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">

         <div class="form-group">
          <label for="datetime">Helpline Type: <span style="color:red">*</span>&nbsp;<span id="helpline_type_idError1" style="color:red"></span></label>
          <div class="form-line">
            <select class="form-control select"  name="helpline_type_id" id="helpline_type_id" onchange="get_state(this.value)"  data-live-search="true">
             <option value="">--Select Helpline Type--</option>
             <?php
             foreach ($helplineTypes as $row_data) 
             {
              ?>
              <option value="<?php echo $row_data->id; ?>"><?php echo $row_data->helpline_type; ?> </option>
              <?php } ?>
            </select>
            
            </div>&nbsp; 
            <div class="form-line">
            <label>Contact Person: <span style="color:red">*</span>&nbsp;<span id="contact_personError" style="color:red"></span></label>
            <input class="form-control"  type="text" name="contact_person" id="contact_person" value="" size="35" placeholder="Contact Person" /> &nbsp; 
          </div>
          <div class="form-line">
            <label>Contact Number: <span style="color:red">*</span>&nbsp;<span id="contact_numberError" style="color:red"></span></label>
            <input class="form-control"  type="text" name="contact_number" id="contact_number" value="" size="35" placeholder="Contact Number" onkeypress="only_number(event)" maxlength="10"  /> &nbsp; 
          </div>
          <div class="form-line">
            <label>Email: <!-- <span style="color:red">*</span> -->&nbsp;<span id="emailError" style="color:red"></span></label>
            <input class="form-control"  type="text" name="email" id="email" value="" size="35" placeholder="Email"/> &nbsp; 
          </div>
          <div class="form-line">
            <label>Address: <!-- <span style="color:red">*</span> -->&nbsp;<span id="addressError" style="color:red"></span></label>
            <textarea class="form-control" name="address" id="address" placeholder="Address"></textarea> &nbsp; 
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
          <h4 class="modal-title"><strong>Edit Helpline </strong><span id="successEditEntry" style="color:green"></span></h4>
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
            <form method="post" action="<?= site_url('Helplines/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Helplines/delete') ?>">       
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
      
      var helpline_type_id = $("#helpline_type_id").val();
      var contact_person = $("#contact_person").val();
      var contact_person1 = /^[a-zA-Z -]+$/;
      var contact_number = $("#contact_number").val();
      var email = $("#email").val();
      var pattern_email = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i; 
      var address = $("#address").val();

      if($.trim(helpline_type_id) == "")
      {
        $("#helpline_type_idError1").fadeIn().html("Please select helpline type");
        setTimeout(function(){$("#helpline_type_idError1").fadeOut();},2000);
        $("#helpline_type_id").focus();
        return false;
      }
      if($.trim(contact_person) == "")
      {
        $("#contact_personError").fadeIn().html("Please enter contact person name");
        setTimeout(function(){$("#contact_personError").fadeOut();},2000);
        $("#contact_person").focus();
        return false;
      }

      if(!contact_person1.test(contact_person))
      {
        $("#contact_personError").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
        setTimeout(function(){$("#contact_personError").fadeOut();},4000);
        $("#contact_person").focus();
        return false;
      }

      if($.trim(contact_number) == "")
      {
        $("#contact_numberError").fadeIn().html("Please enter contact number");
        setTimeout(function(){$("#contact_numberError").fadeOut();},2000);
        $("#contact_number").focus();
        return false;
      }

      else if(!pattern_email.test(email))
      {
        $("#emailError").fadeIn().html("Please enter valid email");
        setTimeout(function(){$("#emailError").html("&nbsp;");},5000)
        $("#email").focus();
        return false;     
      }

      /*if($.trim(email) == "")
      {
        $("#emailError").fadeIn().html("Please enter email");
        setTimeout(function(){$("#emailError").fadeOut();},2000);
        $("#email").focus();
        return false;
      }

      if($.trim(address) == "")
      {
        $("#addressError").fadeIn().html("Please enter address");
        setTimeout(function(){$("#addressError").fadeOut();},2000);
        $("#address").focus();
        return false;
      }*/

      var datastring  = "helpline_type_id="+helpline_type_id+"&contact_person="+contact_person+"&contact_number="+contact_number+"&email="+email+"&address="+address;
      $.ajax({
        type : "post",
        url : "<?php echo site_url('Helplines/addData') ?>",
        data : datastring,
        success : function(response)
        {
          if(response == 1)
          {
            $("#contact_numberError").fadeIn().html("Already exist");
            setTimeout(function(){$("#contact_numberError").fadeOut();},2000);

          }
          else
          {
            $(".close").click(); 
            $("#successStateEntry").fadeIn().html("<span class='label label-success'> State has been Added successfully</span>");
            setTimeout(function(){ window.location.reload(); },1000); 
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
          url: "<?= site_url('Helplines/getUpdateHelpline'); ?>",
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
  var helpline_type_id = $("#edit_helpline_type_id").val();  
  var contact_person = $("#titlecontact_person").val(); 
  var updateId = $("#updateId").val();
  var contact_person2 = /^[a-zA-Z -]+$/;
  var contact_number = $("#titlecontact_number").val(); 
  var email = $("#titleemail").val(); 
  var address = $("#titleaddress").val(); 

  if($.trim(helpline_type_id) == "")
  {
    $("#EdittitleError1").fadeIn().html("Please select helpline");
    setTimeout(function(){$("#EdittitleError").fadeOut();},2000);
    $("#edit_helpline_type_id").focus();
    return false;
  }
  if($.trim(contact_person) == "")
  {
    $("#EdittitleError").fadeIn().html("Please enter contact person name");
    setTimeout(function(){$("#EdittitleError").fadeOut();},2000);
    $("#titlestate_name").focus();
    return false;
  }

  if(!contact_person2.test(contact_person)){
    //alert("Nickname can have only alphabets and numbers.");
    $("#EdittitleError").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
    setTimeout(function(){$("#EdittitleError").fadeOut();},4000);
    $("#titlecontact_person").focus();
    return false;
  }
  if($.trim(contact_number) == "")
  {
    $("#EdittitleError2").fadeIn().html("Please enter contact number");
    setTimeout(function(){$("#EdittitleError").fadeOut();},2000);
    $("#titlecontact_number").focus();
    return false;
  }
  /*if($.trim(email) == "")
  {
    $("#emailError").fadeIn().html("Please enter email");
    setTimeout(function(){$("#emailError").fadeOut();},2000);
    $("#titleemail").focus();
    return false;
  }
  if($.trim(address) == "")
  {
    $("#addressError").fadeIn().html("Please enter address");
    setTimeout(function(){$("#addressError").fadeOut();},2000);
    $("#titleaddress").focus();
    return false;
  }*/

  var datastring  = "helpline_type_id="+helpline_type_id+"&contact_person="+contact_person+"&contact_number="+contact_number+"&email="+email+"&address="+address+"&id="+updateId;

  $.ajax({
    type : "post",
    url : "<?php echo site_url('Helplines/updateData') ?>",
    data : datastring,
    success : function(response)
    {    //alert(response);return false;
      if(response == 1)
      {
        $("#EdittitleError").fadeIn().html("Already exist");
        setTimeout(function(){$("#titleError").fadeOut();},2000);
      }
      else
      {
      $(".close").click(); 
       $("#successStateEntry").fadeIn().html("<span class='label label-success'> State has been Updated successfully</span>");
       setTimeout(function(){ window.location.reload(); },3000); 
     }

   }
 });
}

</script>
<script type="text/javascript">
function only_number(event)
{

  var x = event.which || event.keyCode;
  console.log(x);
  if((x >= 48 ) && (x <= 57 ) || x == 8 | x == 9 || x == 13 )
  {
    return;
  }else{
    event.preventDefault();
  }    
}
</script>