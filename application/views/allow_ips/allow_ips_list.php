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
                                <th>IP Address</th>
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
            <form method="post" action="<?= site_url('Allow_ips/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Allow_ips/delete') ?>">       
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
          <h4 class="modal-title"><strong>Add IP Address </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
                <label>IP Address:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="ip_addressError" style="color:red"></span></label>
                <input type="text" name="ip_address" id="ip_address" value="" class="form-control" placeholder="IP Address" size="35" onkeypress="return isNumberKey(event)" maxlength="25"/>
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
          <h4 class="modal-title"><strong>Edit IP Address </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
            <label>IP Address:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="ip_addressError1" style="color:red"></span></label>
            <input type="text" name="ip_address" id="titleip_address" value="" class="form-control" placeholder="IP Address" size="35"/> 
            
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
        var ip_address = $("#ip_address").val();
        //var ip_address1 = /^[a-zA-Z -]+$/;


        if($.trim(ip_address) == "")
        {
              $("#ip_addressError").fadeIn().html("Please enter IP address");
              setTimeout(function(){$("#ip_addressError").fadeOut();},4000);
              $("#ip_address").focus();
              return false;
        }

        /*else if(!country_name1.test(country_name)){
              $("#nameError").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
              setTimeout(function(){$("#country_nameError").fadeOut();},4000);
              $("#country_name").focus();
              return false;
        }*/

        var datastring  = "ip_address="+ip_address;
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Allow_ips/addData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#ip_addressError").fadeIn().html("IP address already exist");
              setTimeout(function(){$("#ip_addressError").fadeOut();},8000);
            }
            else
            {
             $(".close").click(); 
             $("#successCountryEntry").fadeIn().html("<span class='label label-success'> IP Address has been Added successfully</span>");
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
          url: "<?= site_url('Allow_ips/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          { 
            var obj = $.parseJSON(result);

            var ip_address = obj.ip_address;
            //alert(result);return false;
            $("#titleip_address").val(ip_address);
          }             
        });

//$("#titleName").val(rowid);

    }

    function updateData()
    {
        var ip_address = $("#titleip_address").val();
        var updateId = $("#updateId").val();
        //var ip_address2 = /^[a-zA-Z -]+$/;


        if($.trim(ip_address) == "")
        {
              $("#ip_addressError1").fadeIn().html("Please enter IP Address");
              setTimeout(function(){$("#ip_addressError1").fadeOut();},8000);
              $("#titlecountry_name").focus();
              return false;
        }

       /*else if(!ip_address2.test(ip_address))
       {
            //alert("Nickname can have only alphabets and numbers.");
            $("#ip_addressError1").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
              setTimeout(function(){$("#ip_addressError1").fadeOut();},4000);
              $("#titleip_address").focus();
              return false;
      }*/

        var datastring  = "id="+updateId+"&ip_address="+ip_address;
       
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Allow_ips/updateData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $("#ip_addressError").fadeIn().html("IP Address already exist");
              setTimeout(function(){$("#ip_addressError").fadeOut();},8000);
            }
            else
            {
             $(".close").click();
             $("#successCountryEntry").fadeIn().html("<span class='label label-success'> IP Address has been updated successfully</span>");
              setTimeout(function(){$("#successCountryEntry").fadeOut();},4000);
             $("#myModaledit").modal("hide"); 
             table.draw();
              //setTimeout(function(){ window.location.reload(); },1000); 
            }
           
          }
        });
    }

</script>

<script type="text/javascript">
function isNumberKey(evt)
{
  var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode > 31 && (charCode < 46 || charCode > 57))
    return false;
  return true;
}
</script>