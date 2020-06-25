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
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span class="label label-success text-center" style="margin-bottom:0px; display: none" id="successSubCatEntry"></span></h3>
                    <ul class="panel-controls">
                    <?php if($importPermission=='1'){ ?>
                      <li>
                           <?php if(!empty($import)) { ?>  
                                 <?php  echo  $import; ?>
                            <?php } ?>
                      </li>
                      <?php } ?>
                      <?php if($addPermission=='1'){?>
                        <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="blankData()"><span class="fa fa-plus"></span></a></li>
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
                                <th>Category</th>
                                <th>Subcategory</th>
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
          <h4 class="modal-title"><strong>Add SubCategory </strong><span class="label label-success text-center" style="margin-bottom:0px; display: none" id="successCatEntry"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post" id="subcat" onsubmit="return saveData()">
            <label>Select Category Name:</label>  <span style="color:red">*</span> <a href="#myModalcat" class="pull-right" data-toggle="modal" data-target="" title="Add new category">(<i class="fa fa-plus"></i>)</a>
                                                             
 <span id="sub_cat_idError" style="color:red"></span><br>
            <select name="sub_cat_id"  class="form-control catname select" id="sub_cat_id" value="" data-live-search="true">
              <option value="" >--Select Category--</option>
              <?php foreach($category_data as $category_dataRow) { ?>
              <option value="<?php echo $category_dataRow->id ?>"><?php echo $category_dataRow->title ?></option>
              <?php } ?>
            </select>
            &nbsp; <br>
            <label>Sub Category Name:</label> <span style="color:red">*</span> <span id="nameError" style="color:red"></span><br>
            <input type="text" name="name"  class="form-control" id="name" value="" autocomplete="off" size="35"/> &nbsp; 
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
            <form method="post" action="<?= site_url('Sub_category/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Sub_category/delete') ?>">       
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
          <h4 class="modal-title"><strong>Edit SubCategory </strong></h4> <span class="label label-success text-center" style="margin-bottom:0px; display: none" id="successCatEntry"></span>
        </div>
        <div class="modal-body">
         <!-- <form method="post" id="subcatedit" action="<?php echo site_url('Sub_category/update_action'); ?>"> -->
         <form method="post" id="subcatedit" action="">

         <label>Select Category Name:</label>  <span style="color:red">*</span>  <a href="#myModalcat" class="pull-right" data-toggle="modal" data-target="" title="Add new category">(<i class="fa fa-plus"></i>)</a><span id="sub_cat_idErroredit" style="color:red"></span><br>
            <select name="sub_cat_id_edit"  class="form-control catname select" id="sub_cat_id_edit" value="" data-live-search="true">
              <!-- <option value="">--Select Category--</option> -->
              <?php foreach($category_data as $category_dataRow) { ?>
              <option value="<?php echo $category_dataRow->id ?>" ><?php echo $category_dataRow->title ?></option>
              <?php } ?>
            </select>
            &nbsp; <br>
            <label>Sub Category Name:</label> <span style="color:red">*</span> <span id="titleErroredit" style="color:red"></span><br>
            <input type="text" name="titleNameedit" id="titleNameedit" value="" class="form-control" size="35"/> &nbsp; 
            
        </div>
        <div class="modal-footer">
          <input type="hidden" name="updateId" id="updateId">
          <button type="button" class="btn btn-round btn-success" id="statusEdiBtn" onclick="return updateData()">Submit</button>
          <button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
          </form>
        </div>
      </div>
    </div>
  </div>



  <div class="modal fade" id="myModalcat" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="closecat" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Category <span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post" id="cat" onsubmit="return saveDatacat()">
            <label>Category Name:</label><span style="color:red">*</span>  <span id="nameErrorcat" style="color:red"></span><br>
            <input type="text" name="namecat"  class="form-control" id="namecat" value="" autocomplete="off" size="35"/> &nbsp; 


          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="return saveDatacat()">Submit</button>
          <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

<!-- END DEFAULT DATATABLE -->
<!--IMport strart-->
<div class="modal inmodal" id="uploadData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Sub_category/import')?>" method="post" enctype="multipart/form-data" onsubmit="return checkXcel()">  
                <div class="modal-header">
                        <span style="font-size:20px">Upload Sub categories</span>

                    </div>     
                <div class="modal-body" style="padding-top: 3%">
                 <a download class="pull-right" href="<?php echo base_url(); ?>uploads/sub_categories.xls" style="font-size:10px">Download Sample Format</a>
                    <input type="file" name="excel_file" id="excel_file" class="form-control">
                        &nbsp;<span style="color:red" id="errorexcel_file"></span>&nbsp;
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="return validation();">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--IMport strart-->
<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
    function blankData()
    {
      $("#sub_cat_id").val("").selectpicker('refresh');
      $("#name").val("");
      $("#myModal").click();
    }
</script>

<script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>

<script type="text/javascript">

function getEditvalue(rowid)
    {     
        $("#updateId").val(rowid);        
        $.ajax({
          type: "POST",
          url: "<?= site_url('Sub_category/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          {
            //alert(result);
             var obj = $.parseJSON(result);
            if(obj.success == '1') 
            {
                $("#updateId").val(obj.sub_cat_id);
                $("#sub_cat_id_edit").val(obj.cat_id).selectpicker('refresh');   
                $("#titleNameedit").val(obj.sub_cat_title);                                                                                            
            }             
          }             
        });
    }

    function updateData()
    {
        var sub_cat_id_edit = $("#sub_cat_id_edit").val();
        var titleNameedit = $("#titleNameedit").val();
        var updateId = $("#updateId").val();

        if(sub_cat_id_edit == "")
        {
              $("#sub_cat_idErroredit").fadeIn().html("Please select Category");
              setTimeout(function(){$("#sub_cat_idError").fadeOut();},4000);
              $("#sub_cat_id_edit").focus();
              return false;
        }

        if($.trim(titleNameedit) == "")
        {
              $("#titleErroredit").fadeIn().html("Please enter SubCategory");
              setTimeout(function(){$("#titleErroredit").fadeOut();},4000);
              $("#titleNameedit").focus();
              return false;
        }

        var datastring  = "sub_cat_id_edit="+sub_cat_id_edit+"&id="+updateId+"&titleNameedit="+titleNameedit;
       //alert(datastring);
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Sub_category/updateData') ?>",
          data : datastring,
          success : function(response)
          {
            //alert(response);
            if(response == 1)
            {
              $("#titleErroredit").fadeIn().html("SubCategory name already exist");
              setTimeout(function(){$("#titleError").fadeOut();},8000);
            }
            else
            {
             $(".close").click(); 
             $("#successCountryEntry").fadeIn().html("Subcategory has been updated successfully");
             table.draw();
              $("#successSubCatEntry").fadeIn().html("SubCategory has been updated successfully");
                setTimeout(function() { $("#successSubCatEntry").fadeOut(); }, 2000); 
            /*setTimeout(function(){ window.location.reload(); },1000);*/ 
            }           
          }
        });
    }
</script>

<script type="text/javascript">
  function saveDatacat()
    {
        var namecat= $("#namecat").val();
        if($.trim(namecat) == "")
        {
              $("#nameErrorcat").fadeIn().html("Please enter Category");
              setTimeout(function(){$("#nameErrorcat").fadeOut();},4000);
              $("#namecat").focus();
              return false;
        }
        var datastring  = "namecat="+namecat;
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Sub_category/addNewCat'); ?>",
          data : datastring,
          success : function(response)
          {            
            if(response == 1)
            {
              $("#nameErrorcat").fadeIn().html("Category name already exist");
              setTimeout(function(){$("#nameErrorcat").fadeOut();},8000);
            }
            else
            {
                $("#closecat").click(); 
                $("#sub_cat_id").append(response).selectpicker('refresh');
                $("#sub_cat_id_edit").append(response).selectpicker('refresh');
                $("#namecat").val("");
                $("#successCatEntry").fadeIn().html("Category has been Added successfully");
                setTimeout(function() { $("#successCatEntry").fadeOut(); }, 2000);               
            }           
          }
        });
    }
</script>

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
    function saveData()
    {       
        var sub_cat_id = $("#sub_cat_id").val();
        var name = $("#name").val();       

        if($.trim(sub_cat_id) == "")
        {
              $("#sub_cat_idError").fadeIn().html("Please select Category");
              setTimeout(function(){$("#sub_cat_idError").fadeOut();},4000);
              $("#sub_cat_id").focus();
              return false;
        }
        if($.trim(name) == "")
        {
              $("#nameError").fadeIn().html("Please enter SubCategory");
              setTimeout(function(){$("#nameError").fadeOut();},4000);
              $("#name").focus();
              return false;
        }
        var datastring  = "name="+name+"&sub_cat_id="+sub_cat_id;
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Sub_category/addData'); ?>",
          data : datastring,
          success : function(response)
          {           
            if(response == 1)
            {
              $("#nameError").fadeIn().html("Sub Category name already exist");
              setTimeout(function(){$("#nameError").fadeOut();},8000);
            }
            else
            {
              $(".close").click(); 
              $("#successCountryEntry").fadeIn().html("Sub Category has been Added successfully");
              table.draw();
              $("#successSubCatEntry").fadeIn().html("SubCategory has been Added successfully");
              setTimeout(function() { $("#successSubCatEntry").fadeOut(); }, 2000);   
              /*setTimeout(function(){ window.location.reload(); },100);*/ 
            }           
          }

        });
    }
</script>




           


