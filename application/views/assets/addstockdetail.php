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
<form method="post" action="<?= $action;?>" enctype="multipart/form-data">              
    <div class="row">
     <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">Details of barcode No. <strong class="text-primary"><?=$getassetDetails->barcode_number; ?> </strong></h3>
                    <ul class="panel-controls">
                         <li><a href="<?= site_url('Assets/view/'.$ast_id)?>" ><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body panel-body-table">
                    <div class="row">
                        <table class="table table-bordered table-striped table-actions">
                                        <thead>
                                             <tr>                                                               
                                                <th>Category Name</th>
                                                <th>Subcategory Name</th>                                                  
                                                <th>Asset Type Name</th>                                                  
                                                <th>Brand Name</th>                                                  
                                                <th>Asset Name</th> 
                                                <th>Unit Name</th>                                                  
                                                <th>Image</th>                                                  
                                                                                                        
                                            </tr>
                                        </thead>
                                        <tbody>  
                                            <tr>
                                                <td><?php echo $getAssetData->title ?></td>
                                                <td><?php echo $getAssetData->sub_cat_title  ?></td>
                                                <td><?php echo $getAssetData->type ?></td>
                                                <td><?php echo $getAssetData->brand_name ?></td>
                                                <td><?php echo $getAssetData->asset_name ?></td>
                                                <td><?php echo $getAssetData->unit ?></td>
                                                 <?php if($getAssetData->photo!='') {?>
                                                <td><img src="<?php echo base_url('uploads/assetimages/').$getAssetData->photo  ?>" width="90px"/></td> 
                                                <?php }else{ ?>
                                                    <td><img src="<?php echo base_url('uploads/employee_images/default.jpg')?>" width="90px"/></td>
                                                <?php } ?>              
                                            </tr>                                                                                       
                                        </tbody>
                                    </table>
                                <div class="clearfix">&nbsp;</div>
                             <div class="col-md-12">
                                <div ><h3><?=$heading; ?></h3></div>    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Price <span style="color:red">*</span> <span id="errprice" style="color:red"></span></label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Enter Price" class="form-control" id="price" name="price" <?php if($flag=='update') { ?> value="<?=$getassetDetails->price?>" <?php } else { ?> value="<?=$getAssetData->product_mrp?>" <?php } ?> onkeypress="return only_number(event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $star='*'; if($getassetDetails->image!=''){ $star=''; }?>

                                        <label class="col-md-12">Image <span style="color:red"></span><span  style="color:red"><?//=$star; ?></span><span id="errimage" style="color:red"></span></label>
                                        <div class="col-md-7">
                                          <input type="file" name="image" id="photo" onchange="return onlyImage()">
                                          <b class="text-info">png,jpg and jpeg format only</b>
                                        </div>
                                        <div class="col-md-5">
                                          <?php if($getassetDetails->image!=''){ ?>
                                            <input type="hidden" id="old_image" name="old_image" value="<?=$getassetDetails->image; ?>">
                                            <img src="<?= base_url('uploads/assetimages/'.$getassetDetails->image); ?>" width="100px" height="80px" />
                                          <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Short Description <span id="errsd" style="color:red"></span></label>
                                        <div class="col-md-12">
                                        <textarea id="short_desc" name="short_desc" class="form-control" placeholder="Enter short description"><?=$getassetDetails->short_desc; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Long Description <span id="errld" style="color:red"></span></label>
                                        <div class="col-md-12">
                                         <textarea id="long_desc" name="long_desc" class="form-control" placeholder="Enter long description"><?=$getassetDetails->long_desc; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Is Warranty </label>
                                        <div class="col-md-12">
                                          <input type="radio" name="warranty_type" id="warranty_type" value="Yes"  onclick="warranty(this.value);" <?php if($getassetDetails->warranty_type == 'Yes') { echo 'checked'; } ?>>Yes &nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type="radio" name="warranty_type" id="warranty_type" value="No"  onclick="warranty(this.value);" <?php if($getassetDetails->warranty_type == 'No') { echo 'checked'; } ?>>No

                                          <div class="clearfix">&nbsp;</div>
                                          <div class="col-md-6">
                                            <div class="form-group" id="warranty_date" <?php if($getassetDetails->warranty_type == 'No') { ?> style="display: none;" <?php } ?>>
                                                <label>Warranty From <span style="color:red">*</span><span id="errwarranty_from_date" style="color:red"></span></label>
                                                <!-- <div class="col-md-12"> -->
                                                 <input type="text" name="warranty_from_date" id="from_date" class="form-control" placeholder="Warranty from date" value="<?php echo $getassetDetails->warranty_from_date; ?>" readonly> 
                                                <!-- </div> -->
                                                <div class="clearfix">&nbsp;</div>
                                                <label>Warranty To <span style="color:red">*</span><span id="errwarranty_to_date" style="color:red"></span></label>
                                                <!-- <div class="col-md-12"> -->
                                                 <input type="text" name="warranty_to_date" id="to_date" class="form-control" placeholder="Warranty to date" value="<?php echo $getassetDetails->warranty_to_date; ?>" readonly>
                                                <!-- </div> -->
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" id="warranty_desc" <?php if($getassetDetails->warranty_type == 'No') { ?> style="display: none;" <?php } ?>>
                                    <div class="form-group">
                                        <label class="col-md-12">Warranty Description</label>
                                        <div class="col-md-12">
                                          <textarea id="warranty_description" name="warranty_description" class="form-control" placeholder="Enter warranty description"><?php echo $getassetDetails->warranty_description; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>

                             <div class="clearfix">&nbsp;</div>
                            <?php if($flag=='add'){ ?>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <input type="checkbox" name="commanforall" id="commanforall" value="Yes"> &nbsp;Check if above details are Common for All.
                                </div>
                            </div>
                            <?php } ?>
                            <?php if($flag=='update') { ?>
                            <input type="hidden" name="commanforall_update" value="<?php echo $all_update; ?>">
                          <?php } else { ?>
                            <input type="hidden" name="commanforall_update" value="">
                          <?php } ?>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="panel-footer">                                                                       
                  <button class="btn btn-success" type="submit" onclick="return validateinfo()"><?= $button;?></button>
                </div>
            </div>                                                
        </div>
    </div>
    <!-- END RESPONSIVE TABLES -->    
</form>
   
</div>
<!-- END PAGE CONTENT WRAPPER -->


<?php $this->load->view('common/footer');?>
 <script type="text/javascript" src="<?php echo base_url()?>assets/ckeditor1/ckeditor.js"></script>   
 <script type="text/javascript">
     CKEDITOR.replace('long_desc');
     CKEDITOR.replace('short_desc');
     CKEDITOR.replace('warranty_description');
 </script>
<script type="text/javascript">
  function onlyImage(){
       var file=$('#photo').val();
       var filetype = file.split(".");
       ext = filetype[filetype.length-1];  
       //alert(ext);
       if((ext!='png') && (ext!='jpg') && (ext!='jpeg') && (ext!='PNG') && (ext!='JPG'))
       {
        $("#errimage").fadeIn().html("Invalid format").css('color','red');
        setTimeout(function(){$("#errimage").fadeOut();},4000);
        $("#photo").focus();
        $("#photo").val('');
        return false;
      }
    }

  function validateinfo() 
  { 
    var price = $("#price").val();
    //var photo = $("#photo").val();
    var old_image = $("#old_image").val();
    var warranty_type = $('input[name="warranty_type"]:checked').val(); 
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    //var short_desc =  CKEDITOR.instances.short_desc.getData();
    //var long_desc =  CKEDITOR.instances.long_desc.getData();
    
    if(price=='' || price=='0')
    {
       $("#errprice").html("Required").fadeIn();
       setTimeout(function(){$("#errprice").fadeOut()},5000);
       return false;
    }

    if(warranty_type == 'Yes')
    {
      if(from_date == '0000-00-00')
      { 
           $("#errwarranty_from_date").html("Required").fadeIn();
           setTimeout(function(){$("#errwarranty_from_date").fadeOut()},5000);
           return false;
      } 
      if(to_date == '0000-00-00' || to_date == '')
      {
           $("#errwarranty_to_date").html("Required").fadeIn();
           setTimeout(function(){$("#errwarranty_to_date").fadeOut()},5000);
           return false;
      }
    } 
    /* if(old_image==undefined || old_image=='')
    {
         if(photo=='')
        {
         $("#errimage").html("Required").fadeIn();
         setTimeout(function(){$("#errimage").fadeOut()},5000);
         return false;
        }
    }*/

    /*if(short_desc=='')
    {
         $("#errsd").html("Required").fadeIn();
        setTimeout(function(){$("#errsd").fadeOut()},5000);
        return false;
    }

    if(long_desc=='')
    {
         $("#errld").html("Required").fadeIn();
         setTimeout(function(){$("#errld").fadeOut()},5000);
         return false;
    }*/
  }
</script>

<script type="text/javascript">
  function warranty(value)
  {
    if(value == 'Yes')
    {
      $("#warranty_date").show(1000);
      $("#warranty_desc").show(1000);
    }
    else
    {
      $("#warranty_date").hide(1000);
      $("#warranty_desc").hide(1000);
    }
  }
</script>
 
