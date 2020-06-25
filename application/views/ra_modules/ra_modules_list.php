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
                        <li><a href="#" data-toggle="modal" data-target="#myModal" ><span class="fa fa-plus"></span></a></li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        
                    </ul>
              
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Module Name</th>
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
            <form method="post" action="<?= site_url('Ra_modules/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Ra_modules/delete') ?>">       
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <center>
                        <input type="hidden" name="id" id="deleteId" style="display: none;">
                        <span style="font-size: 16px"> 
                          Are you sure want to delete this record ?? </span>
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
          <h4 class="modal-title"><strong>Add Module </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
                <label>Module Name:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="module_nameError" style="color:red"></span></label>
                <input type="text" name="module_name" id="module_name" value="" class="form-control" placeholder="Module Name" size="35"/>
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
          <h4 class="modal-title"><strong>Edit Module </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
            <label>Module Name:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="module_nameError1" style="color:red"></span></label>
            <input type="text" name="module_name" id="titlemodule_name" value="" class="form-control" placeholder="Module Name" size="35"/> 
            
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
 <script type="text/javascript">
    function saveData()
    {
        var module_name = $("#module_name").val();
        //var module_name1 = /^[a-zA-Z -]+$/;

        //alert(module_name);
        if($.trim(module_name) == "")
        {
              $("#module_nameError").fadeIn().html("Please enter module name");
              setTimeout(function(){$("#module_nameError").fadeOut();},4000);
              $("#module_name").focus();
              return false;
        }
        else {

        var datastring  = "module_name="+module_name;
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Ra_modules/addData') ?>",
          data : datastring,
          success : function(response)
          {
            //alert(response);
            if(response == 1)
            {
              $("#module_nameError").fadeIn().html("Module name already exist");
              setTimeout(function(){$("#module_nameError").fadeOut();},8000);
            }
            else
            {
             $(".close").click(); 
            $("#successCountryEntry").fadeIn().html("<span class='label label-success'> Module has been Added successfully</span>");
             $("#myModal").modal("hide"); 
            table.draw();
            //setTimeout(function(){ window.location.reload(); },100); 
            }
           
          }
        });
      }
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
          url: "<?= site_url('Ra_modules/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          { 
            var obj = $.parseJSON(result);
            var module_name = obj.module_name;
            //alert(result);return false;
            $("#titlemodule_name").val(module_name);
          }             
        });

//$("#titleName").val(rowid);

    }

    function updateData()
    {
        var module_name = $("#titlemodule_name").val();
        var updateId = $("#updateId").val();
        var module_name2 = /^[a-zA-Z -]+$/;


        if($.trim(module_name) == "")
        {
              $("#module_nameError1").fadeIn().html("Please enter module name");
              setTimeout(function(){$("#module_nameError1").fadeOut();},8000);
              $("#titlemodule_name").focus();
              return false;
        }

       else if(!module_name2.test(module_name))
       {
            $("#module_nameError1").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
              setTimeout(function(){$("#module_nameError1").fadeOut();},4000);
              $("#titlemodule_name").focus();
              return false;
      }

        var datastring  = "id="+updateId+"&module_name="+module_name;
       
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Ra_modules/updateData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#module_nameError1").fadeIn().html("Module name already exist");
              setTimeout(function(){$("#module_nameError1").fadeOut();},8000);
            }
            else
            {
             $(".close").click(); 
             $("#successCountryEntry").fadeIn().html("<span class='label label-success'> Module has been updated successfully</span>");
               $("#myModaledit").modal("hide"); 
              table.draw();
              //setTimeout(function(){ window.location.reload(); },1000); 
            }
           
          }
        });
    }

</script>