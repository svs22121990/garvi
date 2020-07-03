 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<style type="text/css">
    div.scrollable {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: auto;
}
</style>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                         <div class="row">
                            <div class="clearfix">&nbsp;</div>
                        <div class="col-md-12">
                     
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h3 class="panel-title">Add Stock of  <strong> <?php echo $getAssetData->asset_name ?></strong></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Warehouse/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
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
                                                                    <td> - </td>
                                                                <?php } ?>             
                                                            </tr>                                                                                       
                                                        </tbody>
                                                    </table>
                                                
                                            <div class="clearfix">&nbsp;</div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <label class="col-md-12"> Enter Quantity : <span style="color:red">*</span> <span class="err" style="color:red"></span></label>
                                                <div class="col-md-3 col-xs-9">
                                                    <input type="text" name="quantity" id="quantity" class="form-control" placeholder="Quantity" onkeypress="return only_number(event)" maxlength="3" >
                                                    <input type="hidden" id="assetName" name="assetName" value="<?=$getAssetData->asset_name; ?>">
                                                    <input type="hidden" id="asset_type_id" name="asset_type_id" value="<?=$getAssetData->asset_type_id; ?>">
                                                </div>
                                                <div class="col-md-3 col-xs-3">
                                                    <button type="button" onclick="return getstickerData()" class="btn btn-sm btn-primary">Ok</button>
                                                </div>
                                                    
                                                </div>
                                            </div>
                                             <div class="clearfix">&nbsp;</div>
                                             <div class="col-md-12" id="stickerData" style="max-height: 400px;overflow-y: scroll;">
                                                

                                             </div>
                                              <div class="clearfix">&nbsp;</div>
                                          </div>
                                   
                                </div>
                                <div class="panel-footer btnappend">                                                                       
                                   <button class="btn btn-success" id="subbtn" type="submit" style="display: none" onclick="return validateinfo()">Add to Stock</button>
                                </div>
                            </div>                                                
                           
                        </div>
                    </div>
                    <!-- END RESPONSIVE TABLES -->    

                  
                </div>
                <!-- END PAGE CONTENT WRAPPER -->


<?php $this->load->view('common/footer');?>

<script type="text/javascript">

/*$(".select").remove();
*/          function getstickerData() 
            {
                var val= $("#quantity").val();
                if(val==0 || val==''){
                  $("#quantity").val("");
                  $("#quantity").focus();
                  $(".err").fadeIn().html("Enter valid quantity");
                  setTimeout(function(){$(".err").fadeOut();},4000);
                 return false;
                }
               var datastring = "val=" + val;
               $('.loader').show();
                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Warehouse/getstickerData/'.$id); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);return false;
                        $('#stickerData').html(returndata);
                        $('.loader').hide();
                        $('#subbtn').show();
                    }
                });
            }
 function validateinfo(){
          var val= $("#quantity").val();
          var asset_type_id= $("#asset_type_id").val();
                if(val==0 || val==''){
                  $("#quantity").val("");
                  $("#quantity").focus();
                  $(".err").fadeIn().html("Enter valid quantity");
                  setTimeout(function(){$(".err").fadeOut();},4000);
                 return false;
                }
          var datastring = "val=" + val+"&asset_type_id="+asset_type_id;
           $('.loader').show();
                $.ajax({
                    type: "post",
                    url: "<?php echo $action; ?>",
                    data: datastring,
                    success: function(returndata) {
                       $('.loader').hide();
                         window.location.href="<?=site_url('Warehouse/view/'.$id);?>";
                    }
                });
 }
          
</script>
 
