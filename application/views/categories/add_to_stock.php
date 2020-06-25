 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
//print_r($_SESSION['id']);exit;
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
                            
                            <form class="form-horizontal" method="post" action="<?php echo $action;?>" enctype="multipart/form-data">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Assets/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12">Is Existing </label>
                                            <div class="col-md-12">
                                              <input type="radio" onclick="check_asset_type(this.value);" value="Yes" id="is_existing" name="is_existing">Yes &nbsp;&nbsp;&nbsp;&nbsp;
                                              <input type="radio"  onclick="check_asset_type(this.value);" value="No" id="is_existing" name="is_existing">No

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <span id="show_existing" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12">Assets </label>
                                                <div class="col-md-12">
                                                   <select class="form-control select" name="asset_id" id="asset_id" data-live-search="true" onchange="return asset_detail(this.value)">
                                                        <option value="">--Select Assets--</option>
                                                         <?php foreach($assets as $as) { ?>                                                         
                                                        <option value="<?php echo $as->id?>" ><?php echo $as->asset_name; ?>
                                                            
                                                        </option>    
                                                        <?php } ?>
                                                      
                                                    </select>

                                                  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12">Price </label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="price" id="product_mrp" value="<?php //echo $getAssetData->product_mrp; ?>" onkeypress="return only_number(event)"/>

                                                  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12">Short Desc </label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="short_description" id="product_mrp" value="<?php //echo $getAssetData->product_mrp; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12">long Desc </label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="long_description" id="product_mrp" value="<?php //echo $getAssetData->product_mrp; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label class="col-md-6">Image <!-- <span style="color:red;">*</span> --> <span id="errorimage" style="color:red"></span><span id="msg" class="error"> <?php echo form_error('photo') ?></span></label>
                                            <div class="col-md-10"> 
                                            <input class="form-control" type="file" name="photo" id="image"  onclick="imageFile()">
                                              <span style="color:blue"><b>Note :</b>Required only JPEG, JPG, PNG file is allowed.</span>
                                            <?php //if(!empty($getAssetData->photo)){ ?>
                                            <div>
                                            <img  style="height:80px;width:80px;"  src="<?php echo base_url(); ?>uploads/assetimages/<?php //echo $getAssetData->photo ?>"><br>   
                                            </div>
                                            <?php //} ?>
                                            <span class= "color" id="errorimage">
                                             </span>&nbsp;
                                            <input type = "hidden" name ="oldimage" value="<?php //echo $getAssetData->photo; ?>" style = "display:none;">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12">Is warrenty </label>
                                            <div class="col-md-12">
                                              <input type="radio" onclick="is_warrenty(this.value);" value="Yes" id="warranty_type" name="warranty_type">Yes &nbsp;&nbsp;&nbsp;&nbsp;
                                              <input type="radio"  onclick="is_warrenty(this.value);" value="No" id="warranty_type" name="warranty_type">No

                                              
                                            </div>
                                        </div>
                                    </div>


                                    <span id="warrantyspan" style="display: none">

                                            <div class="form-group">
                                                <label class="col-md-12">Warranty From<span style="color: red">*</span><span  id="warranty_from_error" style="color: red"></span></label>
                                                <div class="col-md-11">                                            
                                                    <div >                                                      
                                                        <input type="text" class="form-control datepicker" value="<?php //echo $getAssetData->warranty_from_date ?>" name="warranty_from" placeholder="YYYY-MM-DD" readonly="" id="warranty_from">
                                                         <span class="help-block" id="quantity_error" style="color: red"></span>
                                                    </div>                                            
                                                    
                                                </div>
                                            </div>

                                              <div class="form-group">
                                                <label class="col-md-12">Warranty To<span style="color: red">*</span> <span  id="warranty_to_error" style="color: red"></span></label>
                                                <div class="col-md-11">                                            
                                                    <div>                                                      
                                                         <input type="text" class="form-control datepicker" value="<?php //echo $getAssetData->warranty_to_date ?>" name="warranty_to" id="warranty_to" placeholder="YYYY-MM-DD" readonly="">
                                                         <span class="help-block" id="quantity_error" style="color: red"></span>
                                                    </div>                                            
                                                    
                                                </div>
                                            </div>

                                             <div class="form-group">
                                                <label class="col-md-12">Warranty Description<span style="color: red">*</span> <span  id="wd_error" style="color: red"></span></label>
                                                <div class="col-md-11">                                            
                                                    <div>                                                      
                                                         <textarea  name="warranty_desc" id="warranty_desc" class="ckdesc"><?php //echo  $getAssetData->warranty_description ?></textarea> 
                                                         
                                                         <span class="help-block" id="wd_error" style="color: red"></span>
                                                    </div>                                            
                                                    
                                                </div>
                                            </div>

                                            </span>

                                    </span> 
                                       
                                      <input type="text" name="id" value="<?php echo $id; ?>">  
                                    </div>
                                </div>                                
                                <div class="panel-footer">                                                                       
                                <button class="btn btn-primary" type="submit" id="statusBtn" onclick="return validateinfo()"><?= $button;?></button>
                                </div>
                            </div>
                            </form>
                            
                        </div>
                    </div>                    
                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->


               


                
<?php $this->load->view('common/footer');?>

<!-- <script type="text/javascript">
    function checkSubcat()
    {
        var subcategory_id = $("#subcategory_id").val();

         var datastring  = "subcategory_id="+subcategory_id;
        //alert(datastring);
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Assets/checkSubcat'); ?>",
          data : datastring,
          success : function(response)
          {
            //alert(response);return false;
            if(response == 1)
            {
               $("#subcategory_id").val('<?php echo $subCatData->sub_cat_title; ?>');
               $("#subcategory_id").focus();
               $("#subcat_error").fadeIn().html("Invalid");
               setTimeout(function(){$("#subcat_error").fadeOut();},5000);
               $('#statusBtn').prop('disabled', false);
               $("#astinput").val(0); 
               //setTimeout(function(){ window.location.reload(); },1000);
            }
            else
            {                
                $("#astinput").val(1);  
            }             
          }
        });

    }
</script> -->


<script type="text/javascript">
   function check_asset_type(value)
   {
    if(value == 'Yes')
     {
        $('#show_existing').show();
         $('#hide_existing').hide();
     } 
     else
     {
        $('#hide_existing').show();
        $('#show_existing').hide();
     }  
   }
    function is_warrenty(value)
   {
    if(value == 'Yes')
     {
        $('#warrantyspan').show();
     } 
     else
     {
        $('#warrantyspan').hide();
     }  
   }

   function asset_detail(id)
   {

      var site_url = $("#site_url").val();
      var url = site_url+"/Schedule_categories/asset_detail";
     
      $.ajax({
            type : 'POST',
            url : url,
            data : {id:id},
            cache : false,
            success : function(returndata){

              var obj = JSON.parse(returndata);
             
              $("#product_mrp").val(obj.product_mrp);
              
           }
        });      
   }
</script>


  