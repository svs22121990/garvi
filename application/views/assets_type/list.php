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
                    <h3><span style="margin-bottom:0px; display: none" id="successAtCatEntry"></span></h3>
                    <?php if($this->session->flashdata('errormessage')) { ?>
                      <h3 id="error_message"><?=$this->session->flashdata('errormessage'); ?></h3>
                    <?php } ?>
                    <ul class="panel-controls">
                      <?php if($addPermission=='1'){?>
                        <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="return resetData();"><span class="fa fa-plus"></span></a></li>
                        <?php }?>
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
                                <th>Type</th>
                                <!-- <th>Is Saleable</th>
                                <th>Is Barcode</th> -->
                                <th>Status</th>                                                  
                                <th>Action</th>
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

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Product Type </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post" id="asset" onsubmit="return saveData()">
            <label>Product Type Name:</label> <span style="color:red">*</span> <span id="nameError" style="color:red"></span><br>
            <input type="text" name="name"  class="form-control" id="name" placeholder="Product Type Name" value="" autocomplete="off" size="35"/> &nbsp; <br>

             <!-- <label>Is saleable:</label> <span style="color:red">*</span> <span id="saleableError" style="color:red"></span><br>
            <select class="form-control" name="saleable" id="saleable">
              <option value="">--Select Type--</option>
              <option value="Yes">Yes</option>
              <option value="NO">No</option>
            </select> <br>

            <label>Is barcode:</label> <span style="color:red">*</span> <span id="barcodeError" style="color:red"></span>
             <select class="form-control" name="barcode" id="barcode">
              <option value="">--Select Type--</option> 
              <option value="Yes">Yes</option>  
              <option value="No">No</option>
            </select> -->


          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="return saveData()">Submit</button>
          <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Product_type/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Product_type/delete') ?>">       
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


<div class="modal fade" id="myModaledit" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Edit Product Type </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">

         <form method="post" id="assetedit" onsubmit="return updateData()" action="<?php echo site_url('Product_type/update_action'); ?>">
            <label>Product Type Name:</label> <span style="color:red">*</span> <span id="titleError" style="color:red"></span><br>
            <input type="text" name="nameEdit" id="titleName" value="" placeholder="Product Type Name" class="form-control" size="35"/> <br> 

            <!-- <label>Is saleable:</label> <span style="color:red">*</span> <span id="saleableErrorEdit" style="color:red"></span><br>
              <select class="form-control" name="saleableEdit" id="saleableEdit">
                <option value="">--Select Type--</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select> <br>

            <label>Is barcode:</label> <span style="color:red">*</span> <span id="barcodeErrorEdit" style="color:red"></span>
             <select class="form-control" name="barcodeEdit" id="barcodeEdit">
              <option value="">--Select Type--</option> 
              <option value="Yes">Yes</option>  
              <option value="No">No</option>
            </select> -->
            
        </div>
        <div class="modal-footer">
          <input type="hidden" name="updateId" id="updateId">
          <button type="submit" class="btn btn-round btn-success" id="statusEdiBtn" onclick="return updateData()">Submit</button>
          <button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- END DEFAULT DATATABLE -->
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


<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>
 
<script type="text/javascript">
  $('#asset').submit(function(event)
  {
    event.preventDefault();
  });
    $('#assetedit').submit(function(event)
  {
    event.preventDefault();
  });
    function saveData()
    {
        
        var name = $("#name").val();
        /*var salable = $("#saleable").val();
        var barcode = $("#barcode").val();*/
        /*var name1 = /^[a-zA-Z -]+$/;*/

        if($.trim(name) == "")
        {
              $("#nameError").fadeIn().html("Please enter Product type name");
              setTimeout(function(){$("#nameError").fadeOut();},4000);
              $("#name").focus();
              return false;
        }

        /*if(salable == "")
        {
              $("#saleableError").fadeIn().html("Please select type");
              setTimeout(function(){$("#saleableError").fadeOut();},4000);
              $("#salable").focus();
              return false;
        }
        if(barcode == "")
        {
              $("#barcodeError").fadeIn().html("Please select type");
              setTimeout(function(){$("#barcodeError").fadeOut();},4000);
              $("#barcode").focus();
              return false;
        }*/

        var datastring  = "name="+name;
        //alert(datastring);
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Product_type/addData'); ?>",
          data : datastring,
          success : function(response)
          {
           // alert(response);return false;
            if(response == 1)
            {
              $("#nameError").fadeIn().html("Product Type name already exist");
              setTimeout(function(){$("#nameError").fadeOut();},8000);
            }
            else
            {
                $(".close").click(); 
                $("#successCountryEntry").fadeIn().html("Product Type has been Added successfully");
                 table.draw();
                $("#successAtCatEntry").fadeIn().html("Product type has been Added successfully");
                setTimeout(function() { $("#successAtCatEntry").fadeOut(); }, 2000); 
            }           
          }
        });
    }
</script>

<script type="text/javascript">
$(document).ready(function(){
  setTimeout(function(){$("#error_message").fadeOut();},5000);
})
function getEditvalue(rowid)
    {     
        $("#updateId").val(rowid);        
        $.ajax({
          type: "POST",
          url: "<?= site_url('Product_type/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          {            
              var obj = $.parseJSON(result);
              if(obj.success == '1') 
              {
                if(obj.disabled=='disabled')
                {                  
                    
                     $("#barcodeEdit").prop("disabled", true);
                }
                else
                {
                   
                     $("#barcodeEdit").prop("disabled", false);
                }                
                  $("#updateId").val(obj.asset_type_id);
                  $("#titleName").val(obj.type);   
                  /*$("#saleableEdit").val(obj.is_sellable);                                                                              
                  $("#barcodeEdit").val(obj.is_barcode);*/
              }                                                                                            
          }             
        });
    }

    function updateData()
    {
        var name = $("#titleName").val();
        /*var salableEdit = $("#saleableEdit").val();
        var barcodeEdit = $("#barcodeEdit").val();*/
        var updateId = $("#updateId").val();

        if($.trim(name) == "")
        {
              $("#titleError").fadeIn().html("Please enter Product type");
              setTimeout(function(){$("#titleError").fadeOut();},8000);
              $("#titleName").focus();
              return false;
        }

        /*if(salableEdit == "")
        {
              $("#saleableErrorEdit").fadeIn().html("Please select type");
              setTimeout(function(){$("#saleableErrorEdit").fadeOut();},8000);
              $("#salableEdit").focus();
              return false;
        }


      if(barcodeEdit == "")
        {
              $("#barcodeErrorEdit").fadeIn().html("Please select type");
              setTimeout(function(){$("#barcodeErrorEdit").fadeOut();},8000);
              $("#barcodeEdit").focus();
              return false;
        }
*/

        var datastring  = "name="+name+"&id="+updateId;
       
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Product_type/updateData') ?>",
          data : datastring,
          success : function(response)
          {
            //alert(response);
            if(response == 1)
            {
              $("#titleError").fadeIn().html("Product type name already exist");
              setTimeout(function(){$("#titleError").fadeOut();},8000);
            }
            else
            {
             $(".close").click(); 
             $("#successCountryEntry").fadeIn().html("Product type has been updated successfully");
              table.draw();
                $("#successAtCatEntry").fadeIn().html("Product type has been updated successfully");
                setTimeout(function() { $("#successAtCatEntry").fadeOut(); }, 2000); 
            }
           
          }
        });
    }

function resetData(){
        var name = $("#name").val('');
        /*var salable = $("#saleable").val('');
        var barcode = $("#barcode").val('');*/
}

</script>


           


