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
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                
                    <div class="row">
                        <div class="col-md-12">
                            
                            <form class="form-horizontal" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></h3>
                                    <ul class="panel-controls">
                                       <!--  <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                                    </ul>
                                </div>
                                <div class="panel-body">
                                   
                                </div>
                                <div class="panel-body">                                                                        
                                   
                                    <div class="row">
                                                    
                                        
                                        <div class="col-md-8">

                                        <div class="form-group">
                                                <label class="col-md-3 control-label">Brand Name<span style="color: red">*</span></label>
                                                <div class="col-md-9">                                            
                                                    <div class="input-group">
                                                         <select name="manufacturer_id" id="manufacturer_id" class="form-control">
                                                          <option value="" class="form-control">--Select Manufacturer--</option>
                                                          <?php foreach($manu_data as $manu_dataRow) { ?>
                                                          <option value="<?php echo $manu_dataRow->id; ?>" class="form-control"><?php echo $manu_dataRow->name; ?></option>
                                                          <?php } ?>
                                                        </select>
                                                         <span class="help-block" id="manu_error" style="color: red"></span>
                                                    </div>                                            
                                                    
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Brand Name<span style="color: red">*</span></label>
                                                <div class="col-md-9">                                            
                                                    <div class="input-group">
                                                        <!-- <span class="input-group-addon"><span class="fa fa-pencil"></span></span> -->
                                                        <input type="text" class="form-control" name="manu_name" id="manu_name" value="<?= $manu_name; ?>" />
                                                         <span class="help-block" id="manu_error" style="color: red"></span>
                                                    </div>                                            
                                                    
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Image<span style="color: red">*</span></label>
                                                <div class="col-md-9">    
                                                 <div class="input-group">    
                                                 <?php if($button=='Create') { ?>    

                                                    <input type="file" name="photo" id="photo" class="form-control" onclick="imageFile()">
                                                      <span class="help-block" id="img_error" style="color: red"></span>


                                                   <?php } else { ?>   

                                                   <input type="file" name="photo" id="photo" class="form-control" value="<?php echo $getManufacturersData->image?>" onclick="imageFile()">
                                                     <input type="hidden" value="<?php echo $getManufacturersData->image ;?>" 
                                                              name="old_image">
                                                      <span class="help-block" id="img_error" style="color: red"></span>
                                                      <?php if(!empty($getManufacturersData->image)) { ?>                                                     
                                                     <img src="<?php echo base_url()?>uploads/manufacturers/<?php echo $getManufacturersData->image?>" width="100px" height="100px" class="img-resopnsive img-thumbnail"/>

                                                    <!-- <span class="help-block">Default textarea field</span> -->
                                                    <?php } }?>
                                                    </div>
                                                </div>
                                            </div>                                                                                                                                  
                                        </div>                                                                                
                                    </div>
                                </div>

                                <div class="panel-footer">                                                                      
                                    <button class="btn btn-primary pull-right" type="submit" onclick="return validateinfo()"><?= $button;?></button>
                                </div>
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

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
            function get_state_by_country(id) {
                // alert(id);
                 $(".loader").fadeIn('fast'); 
                var datastring = "id=" + id;

                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Branches/get_state'); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);
                        $('.state').html(returndata);
                         $(".loader").fadeOut('fast'); 
                    }
                });
            }

            function get_city_by_state(id) {
                var datastring = "id=" + id;
                 $(".loader").fadeIn('fast'); 
                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Branches/get_city'); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);
                        $('.city').html(returndata);
                         $(".loader").fadeOut('fast'); 
                    }
                });
            }
        </script>

<script type="text/javascript">
    function validateinfo() 
        {                   
                var manu_name = $("#manu_name").val();
                var image = $("#photo").val();
                                      
                if(manu_name=='')
                {
                     $("#manu_error").html("Required").fadeIn();
                    setTimeout(function(){$("#manu_error").fadeOut()},5000);
                     $("#manu_name").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#manu_name").css("border-color", "#ccc");},6000);              
                    return false;
                }

                if(image=='')
                {
                     $("#img_error").html("Required").fadeIn();
                    setTimeout(function(){$("#img_error").fadeOut()},5000);
                     $("#photo").focus().css('border',"2px solid red");
                    setTimeout(function(){$("#photo").css("border-color", "#ccc");},6000);              
                    return false;
                }                               
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
 
