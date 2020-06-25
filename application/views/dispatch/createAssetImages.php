<?php $this->load->view('common/header'); ?>
<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>
<!-- START BREADCRUMB -->
<?php echo $breadcrumbs; ?>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">
            
            <form class="form-horizontal" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <ul class="panel-controls">
                      <?php if($button=='Create'){ ?>
                        <li><a href="<?= site_url('Assets/assetImagesList/'.$this->uri->segment(3).'/'.$this->uri->segment(4));?>"><span class="fa fa-arrow-left"></span></a></li>
                      <?php } else { ?>
                        <li><a href="<?= site_url('Assets/assetImagesList/'.$this->uri->segment(4).'/'.$this->uri->segment(5));?>"><span class="fa fa-arrow-left"></span></a></li>
                      <?php } ?>
                    </ul>
                </div>
                <div class="panel-body">   
                        
                        <div class="col-md-12">

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Date <span style="color:red;">*</span> <span id="errdate" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control date123" readonly name="date" id="date" placeholder="Select Date" value="<?php echo $date; ?>"/>
                                    </div> 
                                </div>
                              </div>
                              
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Image <span style="color:red;">*</span> <span id="errorimage" style="color:red"></span><span id="msg" class="error"> <?php echo form_error('image') ?></span></label>
                                    <div class="col-md-10"> 
                                    <?php if($button=='Create'){ ?>
                                    <input class="form-control" type="file" name="image[]" id="image" value="<?php echo $image;?>" onclick="imageFile()" multiple="multiple">
                                    <?php }else { ?>
                                      <input class="form-control" type="file" name="image" id="image" value="<?php echo $image;?>" onclick="imageFile()">
                                    <?php } ?>
                                      <span style="color:blue"><b>Note :</b>Required only JPEG, JPG, PNG file is allowed.<small>(Select Multiple images by pressing Ctr+ )  
                                      </small></span> 
                                    <input type="hidden" name="" value="<?php echo $button; ?>" id="btnn">
                                   
                                  <?php if(!empty($image)){ ?>
                                  <div>
                                    
                                  <img  style="height:80px;width:80px;"  src="<?php echo base_url(); ?>/uploads/assets_images/<?php echo $image ?>"><br>   
                                  </div>
                                  <?php } ?>
                                  <span class= "color" id="errorimage">
                                     </span>&nbsp;
                                  <input type = "hidden" name ="oldimage" value="<?php echo $image; ?>" style = "display:none;">
                                  
                              </div>
                              </div>
                              </div>


                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Description <!-- <span style="color:red;">*</span> --> <span id="errdescription" style="color:red"></span></label>
                                    <div class="col-md-10"> 
                                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description"><?php echo $description; ?></textarea>
                                    </div>  
                                </div>
                              </div>
                              

                        </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" type="submit" id="submit" onclick="return validation();"><?= $button;?></button>
                    <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>
                </div>
            </div>
            </form>
        </div>
    </div> 
</div>

<!-- END PAGE CONTENT WRAPPER -->  
<?php $this->load->view('common/footer'); ?>

<script type="text/javascript" src="<?php echo base_url()?>assets/ckeditor1/ckeditor.js"></script>   
 <script type="text/javascript">
     CKEDITOR.replace('description');
 </script>

<script type="text/javascript">
function validation()
{ 
        var date = $("#date").val(); 
        var image = $("#image").val();
        var btnn= $("#btnn").val();
        //var description = $("#description").val();
        
      if(date=="")
      {
        $("#errdate").fadeIn().html("Please Select Date");
        setTimeout(function(){$("#errdate").html("&nbsp;");},5000)
        $("#date").focus();
        return false;
      } 


      /*if(description=="")
      {
        $("#errdescription").fadeIn().html("Please Enter Description");
        setTimeout(function(){$("#errdescription").html("&nbsp;");},5000)
        $("#description").focus();
        return false;
      }*/


      if(btnn!='Update') { 
      if(image=='')
      {
        $("#errorimage").html("Please Select Image").css("color","red");
        setTimeout(function(){$("#errorimage").html('');},3000);
        $("#image").focus();
        var scrollPos = $("#image").offset().top;
        $(window).scrollTop(scrollPos);
        return false;
      }
   
      if(image!= "")
      {  
        
        var filetype = image.split("."); 
        ext = filetype[filetype.length-1];
        if(!(ext=='jpg') && !(ext=='png') && !(ext=='JPG') && !(ext=='PNG') && !(ext=='JPEG') && !(ext=='jpeg'))
         {
             $("#errorimage").html("<span style='color:red;'>Please select only JPEG, JPG and PNG type of file</span>").fadeIn();
             setTimeout(function(){$("#errorimage").fadeOut();},6000); 
              var scrollPos = $("#image").offset().top;
              $(window).scrollTop(scrollPos);
             return false; 
         }   
      }
    }


             
   }
</script>

<script type="text/javascript">
  function imageFile()
  { 
    $('#image').change(function () {  
    var files = this.files;   
    var reader = new FileReader();
    name=this.value;    
    //validation for photo upload type    
    var filetype = name.split(".");
    ext = filetype[filetype.length-1];  //alert(ext);return false;
    if(!(ext=='jpg') && !(ext=='png') && !(ext=='PNG') && !(ext=='jpeg') && !(ext=='img') && !(ext=='JPEG') && !(ext=='JPG'))
    { 
    $("#errorimage").html("Please select proper type like jpg, png, jpeg image");   
    setTimeout(function(){$("#errorimage").html("&nbsp;")},3000);
    $("#image").val("");
    //return false;
    }
    reader.readAsDataURL(files[0]);
    });
  }
  
</script>
