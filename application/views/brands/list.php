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
                    <ul class="panel-controls">
                       <?php if($importPermission=='1'){ ?>
                      <li>
                           <?php if(!empty($import)) { ?>  
                                 <?php  echo  $import; ?>
                            <?php } ?>
                      </li>
                      <?php } ?>
                      <?php if($addPermission=='1'){?>
                      <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="blankval()"><span class="fa fa-plus"></span></a></li>
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
                                <th>Manufacturer Name</th>
                                <th>Brand Name</th>
                                <th>Image</th>
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
          <h4 class="modal-title"><strong>Add Brand </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
            <form method="post" action="<?php echo site_url('Brands/create_action'); ?>" enctype="multipart/form-data" onsubmit="return saveData()" id="brand">

            <label>Select Manufacturer:</label> <span style="color: red">*</span> <span id="manu_error" style="color: red"> </span><a href="#myModalmanufacturer" class="pull-right" data-toggle="modal" data-target="" title="Add new Manufacturer" onclick="blankValue()">(<i class="fa fa-plus"></i>)</a> 

            <select name="manufacturer_id" id="manufacturer_id" class="form-control select manuname" data-live-search="true">
                  <option value="" class="form-control">--Select Manufacturer--</option>
                  <?php foreach($manu_data as $manu_dataRow) { ?>
                  <option value="<?php echo $manu_dataRow->id; ?>"><?php echo $manu_dataRow->name; ?></option>
                  <?php } ?>
            </select>
            <br><br>
            <label>Brand Name:</label><span style="color: red">*</span> <span  id="brand_error" style="color: red"></span>
            <input type="text" class="form-control" name="brand_name" id="brand_name" value="" placeholder="Brand Name"/><br>
           
            <label>Image:</label><span style="color: red">*</span>  <span id="img_error" style="color:red"></span><br>
            <input type="file" name="photo" id="photo" class="form-control" onclick="imageFile()"> &nbsp;
            <span style="color: blue">Image type must be (png,jpg,jpeg),Dimentions:(200x200)</span> 
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-round btn-success" id="statusSubBtn" onclick="return saveData()">Submit</button>
        </form>
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
          <h4 class="modal-title"><strong>Edit Brand </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
        <form method="post" action="<?php echo site_url('Brands/update_action'); ?>" enctype="multipart/form-data" id="brandedit" onsubmit="return editData()">
                <label>Select Manufacturer:</label> <span style="color: red">*</span> <span  id="manu_error_edit" style="color: red"></span><a href="#myModalmanufacturer" class="pull-right" data-toggle="modal" data-target="" title="Add new Manufacturer">(<i class="fa fa-plus"></i>)</a> 
                 <select name="manu_id_edit" id="manu_id_edit" class="form-control manuname">
                      <option value="">--Select Manufacturer--</option>
                      <?php foreach($manu_data as $manu_dataRow) { ?>
                      <option value="<?php echo $manu_dataRow->id; ?>"><?php echo $manu_dataRow->name; ?></option>
                      <?php } ?>
                </select>
            <br>

            <label>Brand Name:</label> <span style="color: red">*</span> <span  id="brand_error_edit" style="color: red"></span>
            <input type="text" class="form-control" name="brand_name_edit" id="brand_name_edit" value="" placeholder="Brand Name" />
            &nbsp; <br>

            <input type="hidden" value="" name="old_image" id="old_image" class="form-control"  placeholder="Value"/>

            <label>Image:</label> <span style="color: red">*</span> <span id="img_error_edit" style="color:red"></span>
            <input type="file" name="photo_edit" id="photo_edit" class="form-control" onclick="imageFile_edit()"> &nbsp;
            <span style="color: blue">Image type must be (png,jpg,jpeg),Dimentions:(200x200)</span> <br>
            <span id="brand_image"></span>
             
             
        </div>
                <div class="modal-footer">
                  <input type="hidden" name="updateId" id="updateId">
                  <button type="submit" class="btn btn-round btn-success" id="statusEdiBtn" onclick="return editData()">Submit</button>
        </form>
          <button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>




  <div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Brands/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Brands/delete') ?>">       
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

<!--Add new manu-->
<div class="modal fade" id="myModalmanufacturer" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="manumodalclose" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Manufacturer <span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
    <form method="post" action="<?php echo site_url('Manufacturers/create_action'); ?>" enctype="multipart/form-data" id="manuform">
            <label>Manufacturer Name:</label> <span style="color:red">*</span> <span id="manu_error_modal" style="color: red"></span><br>
            <input type="text" class="form-control" name="manu_name_modal" id="manu_name_modal" value="" /><br>                                  
            <label>Image:</label> <span style="color:red">*</span> <span id="img_error_modal" style="color:red"></span><br>
            <input type="file" name="photo_modal" id="photo_modal" class="form-control" onclick="imageFile()"> &nbsp;
            <span style="color: blue">Image type must be (png,jpg,jpeg)</span>            
        </div>
        <div class="modal-footer">
          <button type="butt" class="btn btn-round btn-success" id="statusSubBtn">Submit</button>
    </form>
          <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
<!--Add new manu-->

<?php $this->load->view('common/footer');?>

<!-- <script type="text/javascript">
 $('#myModal').on('shown.bs.modal', function (e) {
  $(".modal-open").css({"padding-right": "0px"});
})
</script> -->
<script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>

<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>
<!--IMport strart-->
<div class="modal inmodal" id="uploadData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Brands/import')?>" method="post" enctype="multipart/form-data" onsubmit="return checkXcel()">  
                <div class="modal-header">
                        <span style="font-size:20px">Upload Brand</span>

                    </div>     
                <div class="modal-body" style="padding-top: 3%">
                 <a download class="pull-right" href="<?php echo base_url(); ?>uploads/brand.xls" style="font-size:10px">Download Sample Format</a>
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
    /*$('#brand').submit(function(event)
      {
        event.preventDefault();
      });
        $('#brandedit').submit(function(event)
      {
        event.preventDefault();
      });*/
    function saveData() 
        {               
                var manufacturer_id = $("#manufacturer_id").val(); 
                var brand_name = $("#brand_name").val();
                var image = $("#photo").val();

                if(manufacturer_id=='')
                {
                    $("#manu_error").html("Required").fadeIn();
                    setTimeout(function(){$("#manu_error").fadeOut()},5000);                                   
                    return false;
                }
                                      
                if($.trim(brand_name)=='')
                {
                    $("#brand_error").html("Required").fadeIn();
                    setTimeout(function(){$("#brand_error").fadeOut()},5000);                                
                    return false;
                }

                if(image=='')
                {
                    $("#img_error").html("Required").fadeIn();
                    setTimeout(function(){$("#img_error").fadeOut()},5000);                                
                    return false;
                }                               
        }

        function editData() 
        {         

       
                var manufacturer_id_edit = $("#manu_id_edit").val();
                var brand_name_edit = $("#brand_name_edit").val();
               /* var image = $("#photo").val();*/

                if(manufacturer_id_edit=='')
                {
                    $("#manu_error_edit").html("Required").fadeIn();
                    setTimeout(function(){$("#manu_error_edit").fadeOut()},5000);                                 
                    return false;
                }
                                      
                if($.trim(brand_name_edit)=='')
                {
                    $("#brand_error_edit").html("Required").fadeIn();
                    setTimeout(function(){$("#brand_error_edit").fadeOut()},5000);                                
                    return false;
                }   
        }
</script>

<script type="text/javascript">
    function blankval()
    {

      $("#manufacturer_id").val("").selectpicker('refresh');
      $("#brand_name").val("");
      $("#photo").val("");
      $("#myModal").click();
      
    }

    function blankValue()
    {
         $("#manu_name_modal").val("");
         $("#photo_modal").val("");
         $("#myModalmanufacturer").click();
        
    }
</script>

<script type="text/javascript">
    function getEditvalue(rowid)
    {     
        $("#updateId").val(rowid);
        //alert(rowid);
        $.ajax({
          type: "POST",
          url: "<?= site_url('Brands/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          {            
            var obj = $.parseJSON(result);
            if (obj.success == '1') 
            {                                               
                $("#updateId").val(obj.brand_id);
                $("#manu_id_edit").val(obj.manu_id);   
                $("#brand_name_edit").val(obj.brand_name);   
                $("#old_image").val(obj.brand_image);                    
                var imgpath = "<?php echo base_url()?>uploads/brands/";  
                var image = imgpath+obj.brand_image;                                              
                $("#brand_image").html( "<img src="+image+" width='100px' height='100px' class='img-resopnsive img-thumbnail' />" );
            }             
          }             
        });
    }
</script>
<!-- END DEFAULT DATATABLE -->
        <script type="text/javascript">
            function imageFile() {
                $('#photo').change(function() 
                {
                    var files = this.files;
                    var reader = new FileReader();
                    name = this.value;
                    var filetype = name.split(".");
                    ext = filetype[filetype.length - 1]; //alert(ext);return false;
                    if (!(ext == 'jpg') && !(ext == 'JPG') && !(ext == 'png') && !(ext == 'PNG') && !(ext == 'jpeg') && !(ext == 'img') && !(ext == 'JPEG')) {
                        $("#img_error").html("Please select proper type like jpg, png, jpeg image");
                        setTimeout(function() {
                            $("#img_error").html("&nbsp;")
                        }, 8000);
                        $("#photo").val("");
                        return false;
                    }
                    reader.readAsDataURL(files[0]);
                });
            }

             function imageFile_edit() {
                //alert("hii");return false;
                $('#photo_edit').change(function() {
                    var files = this.files;
                    var reader = new FileReader();
                    name = this.value;
                    //validation for photo upload type    
                    var filetype = name.split(".");
                    ext = filetype[filetype.length - 1]; //alert(ext);return false;
                    if (!(ext == 'jpg') && !(ext == 'JPG') && !(ext == 'png') && !(ext == 'PNG') && !(ext == 'jpeg') && !(ext == 'img') && !(ext == 'JPEG')) {
                        $("#img_error_edit").html("Please select proper type like jpg, png, jpeg image");
                        setTimeout(function() {
                            $("#img_error_edit").html("&nbsp;")
                        }, 8000);
                        $("#photo_edit").val("");
                        return false;
                    }
                    reader.readAsDataURL(files[0]);
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
            function imageFile() {
                //alert("hii");return false;
                $('#photo').change(function() {
                    var files = this.files;
                    var reader = new FileReader();
                    name = this.value;
                    //validation for photo upload type    
                    var filetype = name.split(".");
                    ext = filetype[filetype.length - 1]; //alert(ext);return false;
                    if (!(ext == 'jpg') && !(ext == 'JPG') && !(ext == 'png') && !(ext == 'PNG') && !(ext == 'jpeg') && !(ext == 'img') && !(ext == 'JPEG')) {
                        $("#img_error").html("Please select proper type like jpg, png, jpeg image");
                        setTimeout(function() {
                            $("#errorimg").html("&nbsp;")
                        }, 8000);
                        $("#photo").val("");
                        return false;
                    }
                    reader.readAsDataURL(files[0]);
                });
            }
        </script>

        <script type="text/javascript">
            /*function saveDatamanu()
            {*/
                $("#manuform").submit(function(e)
                    {
                        var manu_name_modal = $("#manu_name_modal").val();
                        var photo_modal = $("#photo_modal").val();
                                              
                        if(manu_name_modal=='')
                        {
                            $("#manu_error_modal").html("Required").fadeIn();
                            setTimeout(function(){$("#manu_error_modal").fadeOut()},5000);                             
                            return false;
                        }

                        if(photo_modal=='')
                        {
                            $("#img_error_modal").html("Required").fadeIn();
                            setTimeout(function(){$("#img_error_modal").fadeOut()},5000);                               
                            return false;
                        }     
                            
                    var formObj = $(this);
                    {
                    //$(".loader").show();
                    $(".loader").fadeIn('slow');
                    $("#buttons").hide();

                    var formData = new FormData(this);
                    var site_url = "<?= site_url('Brands'); ?>";
                    //alert(site_url);
                    var url = site_url+"/savemanufaccturer";
                    //alert(url);
                    
                    $.ajax({

                    url: url,
                    type: 'POST',
                    data:formData,
                    mimeType:"multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data)
                    {
                        //alert(data);
                        if(data == 1)
                        {
                          $("#manu_error_modal").fadeIn().html("Manufacturer name already exist");
                          setTimeout(function(){$("#manu_error_modal").fadeOut();},8000);
                        }
                        else
                        {
                            $("#manumodalclose").click(); 
                            $("#manufacturer_id").append(data).selectpicker('refresh');
                            $("#manu_id_edit").append(data).selectpicker('refresh');
                            $("#successCountryEntry").fadeIn().html("Category has been Added successfully");
                            /*table.draw();*/
                            /*setTimeout(function(){ window.location.reload(); },100);*/ 
                        }  
                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                    alert(textStatus+', errorThrown='+errorThrown);
                    }          
                    });
                    e.preventDefault();
                    //e.unbind();
                    }
                    });
           /* }*/
        </script>





           


