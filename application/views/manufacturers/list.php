<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit; ?>
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
                    <h3><span><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span class="label label-success text-center" style="margin-bottom:0px; display: none" id="successManuEntry"></span></h3>
                   <!--  <h3><span class="label label-success text-center" style="margin-bottom:0px; display: none" id="successManuEntry"></span></h3> -->
                    <ul class="panel-controls">
                        <!-- <li><a href="<?php echo site_url("Manufacturers/create")?>" ><span class="fa fa-plus"></span></a></li> -->
                        <?php if($addPermission=='1'){?>
                        <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="blankval()" ><span class="fa fa-plus"></span></a></li>
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
                                <th>Name</th>
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
          <h4 class="modal-title"><strong>Add Manufacturer </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
    <form method="post" action="<?php echo site_url('Manufacturers/create_action'); ?>" enctype="multipart/form-data">
            <label>Manufacturer Name:</label> <span style="color:red">*</span> <span id="manu_error" style="color: red"></span><br>
            <input type="text" class="form-control" name="manu_name" id="manu_name" value="" placeholder="Manufacturer Name" />                       
            &nbsp; <span id="sub_cat_idError" style="color:red"></span><br>
            <label>Image:</label> <span style="color:red">*</span> <span id="img_error" style="color:red"></span><br>
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
          <h4 class="modal-title"><strong>Edit Manufacturer </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
        <form method="post" action="<?php echo site_url('Manufacturers/update_action'); ?>" enctype="multipart/form-data">
                  <label>Manufacturer Name:</label> <span style="color:red">*</span> <span class="help-block" id="manu_error" style="color: red"></span>

                    <input type="text" class="form-control" name="manu_name_edit" id="manu_name_edit" value="" placeholder="Manufacturer Name" />
                    
                   
                    &nbsp; <span id="manu_error_edit" style="color:red"></span><br>
                    <label>Image:</label> <span style="color:red">*</span> <span id="img_error_edit" style="color:red"></span><br>
                    <input type="file" name="photo_edit" id="photo_edit" class="form-control" onclick="imageFileEdit()">&nbsp;
                    <span style="color: blue">Image type must be (png,jpg,jpeg),Dimentions:(200x200)</span>  <br>
                     <span id="manu_test_img"></span>
                    

                     <input type="hidden" value="" name="old_image" id="old_image" class="form-control"  placeholder="Value"/>
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
            <form method="post" action="<?= site_url('Manufacturers/changeStatus') ?>">       
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
            <form method="post" action="<?= site_url('Manufacturers/delete') ?>">       
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



<!-- END DEFAULT DATATABLE -->
<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<?php $this->load->view('common/footer');?>

 <script type="text/javascript">
    function blankval()
    {
      $("#manu_name").val("");
      $("#photo").val("");
      $("#myModal").click();
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
                            $("#img_error").html("&nbsp;")
                        }, 8000);
                        $("#photo").val("");
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
    function validateinfo() 
        {                   
                                        
        }
</script>


<script type="text/javascript">


    function saveData()
    {
                var manu_name = $("#manu_name").val();
                var image = $("#photo").val();
                                      
                if($.trim(manu_name) =="")
                {
                    $("#manu_error").html("Required").fadeIn();
                    setTimeout(function(){$("#manu_error").fadeOut()},5000);                             
                    return false;
                }

                if(image=='')
                {
                    $("#img_error").html("Required").fadeIn();
                    setTimeout(function(){$("#img_error").fadeOut()},5000);                               
                    return false;
                }     
    }
</script>

<script type="text/javascript">

    function editData()
    {
                var manu_name_edit = $("#manu_name_edit").val();
                /*var image_edit = $("#photo_edit").val();*/
                                      
                if($.trim(manu_name_edit) =="")
                {
                     $("#manu_error_edit").html("Required").fadeIn();
                    setTimeout(function(){$("#manu_error").fadeOut()},5000);
                     $("#manu_name_edit").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#manu_name_edit").css("border-color", "#ccc");},6000);              
                    return false;
                }

               /* if(image_edit=='')
                {
                     $("#img_error_edit").html("Required").fadeIn();
                    setTimeout(function(){$("#img_error_edit").fadeOut()},5000);
                     $("#photo_edit").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#photo_edit").css("border-color", "#ccc");},6000);              
                    return false;
                }    */  
    }
</script>


<script type="text/javascript">

function getEditvalue(rowid)
    {     
        $("#updateId").val(rowid);        
        $.ajax({
          type: "POST",
          url: "<?= site_url('Manufacturers/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          {
            var obj = $.parseJSON(result);
            if (obj.success == '1') 
            {
                $("#updateId").val(obj.manu_id);
                $("#manu_name_edit").val(obj.manu_name);   
                $("#old_image").val(obj.manu_image);                                                                            
                var imgpath = "<?php echo base_url()?>uploads/manufacturers/";                                                                
                var image = imgpath+obj.manu_image;                
                $("#manu_test_img").html( "<img src="+image+" width='90px' height='90px' class='img-resopnsive img-thumbnail' />" );
            }             
          }             
        });
    }

</script>

<script type="text/javascript">
    function imageFileEdit() {
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
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>
 



           


