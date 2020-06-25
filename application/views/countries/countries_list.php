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
                                <th>Country Code</th>
                                <th>Country Name</th>
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
            <form method="post" action="<?= site_url('Countries/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Countries/delete') ?>">       
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

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Country </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
                <label>Country Code:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="country_codeError" style="color:red"></span></label>
                <input type="text" name="country_code" id="country_code" value="" class="form-control" placeholder="Country Code" size="35"/>
                <label>Country Name:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="country_nameError" style="color:red"></span></label>
                <input type="text" name="country_name" id="country_name" value="" class="form-control" placeholder="Country Name" size="35"/>
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
          <h4 class="modal-title"><strong>Edit Country </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
            <label>Country Code:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="country_codeError1" style="color:red"></span></label>
            <input type="text" name="country_code" id="titlecountry_code" value="" class="form-control" placeholder="Country Code" size="35"/>
            <label>Country Name:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="country_nameError1" style="color:red"></span></label>
            <input type="text" name="country_name" id="titlecountry_name" value="" class="form-control" placeholder="Country Name" size="35"/> 
            
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
        var country_code = $("#country_code").val(); 
        var country_name = $("#country_name").val();
        var country_name1 = /^[a-zA-Z -]+$/;

        if($.trim(country_code) == "")
        {
              $("#country_codeError").fadeIn().html("Please enter country code");
              setTimeout(function(){$("#country_codeError").fadeOut();},4000);
              $("#country_code").focus();
              return false;
        }

        if($.trim(country_name) == "")
        {
              $("#country_nameError").fadeIn().html("Please enter country name");
              setTimeout(function(){$("#country_nameError").fadeOut();},4000);
              $("#country_name").focus();
              return false;
        }

         else if(!country_name1.test(country_name)){
              $("#nameError").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
              setTimeout(function(){$("#country_nameError").fadeOut();},4000);
              $("#country_name").focus();
              return false;
}

        var datastring  = "country_name="+country_name+"&country_code="+country_code;
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Countries/addData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#country_nameError").fadeIn().html("Country name already exist");
              setTimeout(function(){$("#country_nameError").fadeOut();},8000);
            }
            else
            {
             $(".close").click(); 
             $("#successCountryEntry").fadeIn().html("<span class='label label-success'> Country has been Added successfully</span>");
              setTimeout(function(){$("#successCountryEntry").fadeOut();},8000);
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
          url: "<?= site_url('Countries/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          { 
            var obj = $.parseJSON(result);

            var country_code = obj.country_code;
            var country_name = obj.country_name;
            //alert(result);return false;
            $("#titlecountry_code").val(country_code);
            $("#titlecountry_name").val(country_name);
          }             
        });

//$("#titleName").val(rowid);

    }

    function updateData()
    {
        var country_code = $("#titlecountry_code").val();
        var country_name = $("#titlecountry_name").val();
        var updateId = $("#updateId").val();
        var country_name2 = /^[a-zA-Z -]+$/;

        if($.trim(country_code) == "")
        {
              $("#country_codeError1").fadeIn().html("Please enter country code");
              setTimeout(function(){$("#country_codeError1").fadeOut();},8000);
              $("#titlecountry_code").focus();
              return false;
        }

        if($.trim(country_name) == "")
        {
              $("#country_nameError1").fadeIn().html("Please enter country name");
              setTimeout(function(){$("#country_nameError1").fadeOut();},8000);
              $("#titlecountry_name").focus();
              return false;
        }

       else if(!country_name2.test(country_name))
       {
            //alert("Nickname can have only alphabets and numbers.");
            $("#country_nameError1").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
              setTimeout(function(){$("#country_nameError1").fadeOut();},4000);
              $("#titlecountry_name").focus();
              return false;
      }

        var datastring  = "country_code="+country_code+"&id="+updateId+"&country_name="+country_name;
       
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Countries/updateData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#country_nameError").fadeIn().html("Country name already exist");
              setTimeout(function(){$("#country_nameError").fadeOut();},8000);
            }
            else
            {
             $(".close").click();
             $("#successCountryEntry").fadeIn().html("<span class='label label-success'> Country has been updated successfully</span>");
              setTimeout(function(){$("#successCountryEntry").fadeOut();},4000);
             $("#myModaledit").modal("hide"); 
             table.draw();
              //setTimeout(function(){ window.location.reload(); },1000); 
            }
           
          }
        });
    }

</script>