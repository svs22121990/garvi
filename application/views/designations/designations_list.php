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
                    <h3><span id="successDesignationsEntry"></span></h3>
                    <ul class="panel-controls">
                      <?php if($addPermission=='1'){?>
                        <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="blankIt()"><span class="fa fa-plus"></span></a></li>
                        <?php }?>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        
                    </ul>
              
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Designation Name</th>
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
            <form method="post" action="<?= site_url('Designations/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Designations/delete') ?>">       
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <center>
                        <input type="hidden" name="id" id="deleteId" style="display: none;">
                        <span style="font-size: 16px"> 
                           You want to delete this record. 
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

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Designation </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
                <label>Designation Name:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="designation_nameError" style="color:red"></span></label>
                <input type="text" name="designation_name" id="designation_name" value="" class="form-control lgn" placeholder="Designation Name" size="35"/>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-success lgnbtn" id="statusSubBtn" onclick="return saveData()">Submit</button>
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
          <h4 class="modal-title"><strong>Edit Designation </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
            <label>Designation Name:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="designation_nameError1" style="color:red"></span></label>
            <input type="text" name="designation_name" id="titledesignation_name" value="" class="form-control lgn" placeholder="Designation Name" size="35"/> 
            
          </form>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id" id="updateId">
          <button type="button" class="btn btn-round btn-success lgnbtn" id="statusEdiBtn" onclick="return updateData()">Submit</button>
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
  function blankIt()
  {
    $("#designation_name").val("");
    $("#myModal").click("");
  }
</script>

 <script type="text/javascript">
    function saveData()
    {
        var designation_name = $("#designation_name").val();
        var designation_name1 = /^[a-zA-Z -]+$/;

        if($.trim(designation_name) == "")
        {
              $("#designation_nameError").fadeIn().html("Please enter designation name");
              setTimeout(function(){$("#designation_nameError").fadeOut();},4000);
              $("#designation_name").focus();
              return false;
        }

         else if(!designation_name1.test(designation_name)){
              $("#designation_nameError").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
              setTimeout(function(){$("#designation_nameError").fadeOut();},4000);
              $("#designation_name").focus();
              return false;
}

        var datastring  = "designation_name="+designation_name;
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Designations/addData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#designation_nameError").fadeIn().html("Designation name already exist");
              setTimeout(function(){$("#designation_nameError").fadeOut();},8000);
            }
            else
            {
             $(".close").click(); 
             $("#successDesignationsEntry").fadeIn().html("<span class='label label-success'> Designation has been Added successfully</span>");
              setTimeout(function(){$("#successDesignationsEntry").fadeOut();},8000);

            
            $("#myModal").modal("hide"); 
              table.draw();
            //setTimeout(function(){ window.location.reload(); },100); 
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
          url: "<?= site_url('Designations/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          {
            $("#titledesignation_name").val($.trim(result));
          }             
        });

//$("#titleName").val(rowid);

    }

    function updateData()
    {
        var designation_name = $("#titledesignation_name").val();
        var updateId = $("#updateId").val();
        var designation_name2 = /^[a-zA-Z -]+$/;

        if($.trim(designation_name) == "")
        {
              $("#designation_nameError1").fadeIn().html("Please enter designation name");
              setTimeout(function(){$("#designation_nameError1").fadeOut();},8000);
              $("#titledesignation_name").focus();
              return false;
        }

       else if(!designation_name2.test(designation_name))
       {
            //alert("Nickname can have only alphabets and numbers.");
            $("#designation_nameError1").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
              setTimeout(function(){$("#designation_nameError1").fadeOut();},4000);
              $("#titledesignation_name").focus();
              return false;
      }

        var datastring  = "designation_name="+designation_name+"&id="+updateId;
       
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Designations/updateData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#designation_nameError1").fadeIn().html("Designation name already exist");
              setTimeout(function(){$("#designation_nameError1").fadeOut();},8000);
            }
            else
            {
             $(".close").click(); 
             $("#successDesignationsEntry").fadeIn().html("<span class='label label-success'> Designation has been updated successfully</span>");
              setTimeout(function(){$("#successDesignationsEntry").fadeOut();},8000);

             
             $("#myModaledit").modal("hide"); 
             table.draw();
              //setTimeout(function(){ window.location.reload(); },1000); 
            }
           
          }
        });
    }


/*for enter click validations*/
    $('document').ready(function()
    { 
      $(".lgn").keypress(function(e)
      {
        if(e.which == 13) 
        {
          e.preventDefault();
          $(".lgnbtn").click();
          return false;             
        }
      });
    });

</script>