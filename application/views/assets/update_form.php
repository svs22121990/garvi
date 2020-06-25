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

<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-12">
      <form class="form-horizontal" method="post" action="<?php echo $action;?>" enctype="multipart/form-data">
        <div class="panel panel-default">

          <div class="panel-heading">
            <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
            <ul class="panel-controls">
              <li><a href="<?= site_url('Products/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">

                <div class="form-group">
                  <label class="col-md-12">  Category <span style="color: red">*</span>
                    <a style="margin-left: 386px;" href="#myModalCategory" data-toggle="modal" data-target="" title="Add new Category">(<i class="fa fa-plus" onclick="blankValue()"></i>)</a>
                    <span  id="subcat_error" style="color: red"></span><span id="successEntry" style="color:green"></span><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
                  </label>
                  <div class="col-md-11">  
                    <select class="form-control cat" name="category_id" id="category_id" onchange="getGST(this.value);">
                      <option value="">--Select Category--</option>
                      <?php foreach($category_data as $category) { ?> 
                      <option value="<?php echo $category->id?>" <?php if($getAssetData->category_id==$category->id)echo "selected";?>><?php echo $category->title ?></option>
                      <?php } ?> 
                    </select>
                    <span class="help-block" id="subcat_error" style="color: red"></span>
                  </div>
                  <input type="hidden" name="">
                </div>

                <div class="form-group">
                  <label class="col-md-11">  Product Type  <a href="#myModalAssetType" class="pull-right" data-toggle="modal" data-target="" title="Add new Product Type">(<i class="fa fa-plus" onclick="blankValue()"></i>)</a><span style="color: red">*</span></label>
                  <div class="col-md-11">
                    <select class="form-control" name="asset_type_id" id="asset_type_id">
                      <option value="">--Select Product Type Name--</option>
                      <?php foreach($asset_type_data as $asset_type_dataRow) { ?>
                      <option value="<?php echo $asset_type_dataRow->id?>" <?php if($getAssetData->asset_type_id==$asset_type_dataRow->id)echo "selected";?>><?php echo $asset_type_dataRow->type ?></option>
                      <?php } ?>
                    </select>
                    <span class="help-block" id="asset_type_error" style="color: red"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12"> SKU<span style="color: red">*</span><span id="sku_error" style="color: red"></span></label>
                  <div class="col-md-11"> 
                    <div >            
                      <input type="text" class="form-control" name="sku" id="sku" value="<?php echo $getAssetData->sku; ?>"/>
                    </div>   
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12"> Product Name<span style="color: red">*</span><span id="asset_name_error" style="color: red"></span></label>
                  <div class="col-md-11"> 
                    <div >            
                      <input type="text" class="form-control" name="asset_name" id="asset_name" value="<?php echo $getAssetData->asset_name; ?>" oninput="checkassetduplicate(this.value)"/>
                    </div>   
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12"> Quantity<span style="color: red">*</span><span id="quantity_error" style="color: red"></span></label>
                  <div class="col-md-11"> 
                    <div >            
                      <input type="text" class="form-control" name="quantity" id="quantity" value="<?php echo $getAssetData->quantity; ?>"/>
                    </div>   
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12"> Price<span style="color: red">*</span><span id="product_mrp_error" style="color: red"></span></label>
                  <div class="col-md-11"> 
                    <div >           
                      <input type="text" class="form-control" name="product_mrp" id="product_mrp" value="<?php echo $getAssetData->product_mrp; ?>" onkeypress="return only_number(event)"/>
                    </div>   
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12"> GST %<span style="color: red">*</span><span id="gst_percent_error" style="color: red"></span></label>
                  <div class="col-md-11"> 
                    <div >           
                      <input type="text" class="form-control" name="gst_percent" id="gst_percent" value="<?php echo $getAssetData->gst_percent; ?>" readonly="readonly" onkeypress="return only_number(event)"/>
                    </div>   
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12"> HSN<span style="color: red">*</span><span id="hsn_error" style="color: red"></span></label>
                  <div class="col-md-11"> 
                    <div >           
                      <input type="text" class="form-control" name="hsn" id="hsn" value="<?php echo $getAssetData->hsn; ?>" onkeypress="return only_number(event)"/>
                    </div>   
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12"> LF No.<span style="color: red">*</span><span id="lf_no_error" style="color: red"></span></label>
                  <div class="col-md-11"> 
                    <div >           
                      <input type="text" class="form-control" name="lf_no" id="lf_no" value="<?php echo $getAssetData->lf_no; ?>" onkeypress="return only_number(event)"/>
                    </div>   
                  </div>
                </div>

              </div>
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


<div class="clearfix"></div>                

                <!--Asset type modal-->
                    <div class="modal fade" id="myModalAssetType" role="dialog">
                        <div class="modal-dialog">     
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" id="assetclosetype">&times;</button>
                              <h4 class="modal-title">Add New Product Type <span id="successEntry" style="color:green"></span></h4>
                            </div>
                            <div class="modal-body">
                             <form method="post" id="asset" onsubmit="return saveDataAssetType()">
                                <label>Product Type Name:</label> <span style="color:red">*</span> <span id="nameErrorAsset" style="color:red"></span><br>
                                <input type="text" name="nametype"  class="form-control" id="nametype" placeholder="Product Type Name" value="" autocomplete="off" size="35"/> &nbsp; <br>

                                 


                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="return saveDataAssetType()">Submit</button>
                              <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
                            </div>
                          </div>
                        </div>
                      </div>
                <!--Asset type modal-->

                <div id="myModalCategory" class="modal fade" role="dialog xs">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Category</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12 text-success text-center" id="success_message"></div>
            <div class="row">
                <div class="col-md-12">
                  <label>Category Name <span style="color:red;">*</span><span id="error_category_id_title" style="color:red"></span></label>
                  <input type="text" class="form-control" name="category_id_title" id="category_id_title" placeholder="Category Name">
                  
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="return validation_subcategory();">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
  </div>
</div>

<?php $this->load->view('common/footer');?>
<script>
function validation_subcategory()
 {
      var category_id_title = $("#category_id_title").val();
      //var subcategory_title = $("#subcategory_title").val();
      var site_url = $("#site_url").val(); 
      if(category_id_title=="") 
      {
          $("#error_category_id_title").fadeIn().html("Please enter category name");
          setTimeout(function(){$("#error_category_id_title").fadeOut();},3000);
          $("#category_id_title").focus();
          return false;
      }
      
      var site_url = $('#site_url').val();
      var url = site_url+"/Products/savemyCategory";
      var dataString = "category_id_title="+category_id_title;
      $.post(url,dataString,function(returndata)
      {
        if(returndata==0) 
        {
          $("#error_category_id_title").fadeIn().html("Category already exist");
           setTimeout(function(){$("#error_category_id_title").fadeOut();},3000);
          $("#category_id_title").focus();
          return false;
        }
        else
        {
          $("#category_id_title").val('');
          //$("#subcategory_title").val('');
          $("#myModalCategory").modal("hide");
          $('#category_id').html(returndata);
          //$("#successEntry").fadeIn().html("<span> Category created successfully</span>");
          //setTimeout(function(){$("#successEntry").fadeOut();},3000);
        }
      });
  }
</script> 

    <script type="text/javascript">
    function checkassetduplicate()
    {
        $('#statusSubBtn').prop('disabled', true);
        var astName = $("#asset_name").val();
        var id = '<?php echo $id; ?>';

         var datastring  = "astName="+astName+'&id='+id;
        //alert(datastring);
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Products/chkAssetName'); ?>",
          data : datastring,
          success : function(response)
          {
           // alert(response);return false;
            if(response == 1)
            {
              $("#asset_name").val('<?php echo $getAssetData->asset_name; ?>');
              $("#asset_name").focus();
              $("#asset_name_error").fadeIn().html("Product name already exist");
              setTimeout(function(){$("#asset_name_error").fadeOut();},5000);
               $('#statusSubBtn').prop('disabled', false);
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
</script>
    

    <script type="text/javascript">
    function saveDataAssetType()
    {
        var nametype = $("#nametype").val();

        if($.trim(nametype) == "")
        {
              $("#nameErrorAsset").fadeIn().html("Please enter Product type name");
              setTimeout(function(){$("#nameErrorAsset").fadeOut();},4000);
              $("#nametype").focus();
              return false;
        }

        var datastring  = "nametype="+nametype;
        //alert(datastring);
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Products/addDataAssetType'); ?>",
          data : datastring,
          success : function(response)
          {
           // alert(response);return false;
            if(response == 1)
            {
              $("#nameErrorAsset").fadeIn().html("Product Type name already exist");
              setTimeout(function(){$("#nameError").fadeOut();},8000);
            }
            else
            {                
                $("#assetclosetype").click(); 
                $("#asset_type_id").html(response);
                //$("#successCountryEntry").fadeIn().html("Asset Type has been Added successfully");                
            }           
          }
        });
    }

    function blankValue()
    {
        var nametype = $("#nametype").val("");
        
        var name = $("#name").val("");
    }
</script>


    
<script type="text/javascript">
                $(document).ready(function() {
                    $('#final_amount').keyup(function() {

                        var product_mrp_price = $("#product_mrp").val();
                        var final_amount = $("#final_amount").val();

                         if(parseInt(final_amount) <= parseInt(product_mrp_price))
                        {
                               var discounted_price = product_mrp_price - final_amount;                        
                                var percentagePrice = (discounted_price/product_mrp_price) *100;                                                             
                                $('#discount').val(percentagePrice);
                                //alert(percentagePrice);
                        }
                        else
                        {                           
                            $('#discount').val(0);
                            $('#final_amount').val("");
                        }
                        
                    });
                });
            </script>
<script type="text/javascript">
     function get_sub_cat(id) {
                var datastring = "id=" + id;                
                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Products/getSubcat'); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);
                       /* $('.subcat').html(returndata);*/ 
                        $('#category_id').html(returndata).selectpicker('refresh');                         
                    }
                });
            }
</script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/summernote/summernote.js"></script>
 

<script type="text/javascript">
function getGST(val) {
  var site_url = $('#site_url').val();
  var dataString = "category_id="+val;
  var url = site_url+"/Products/getGST";
  $.post(url, dataString, function(returndata){
    var obj = jQuery.parseJSON(returndata);
    $('#gst_percent').val(obj.gst_percent);
    $('#hsn').val(obj.hsn);
  });
}

function validateinfo() 
{                  
    var category_id = $("#category_id").val();
    var asset_type_id = $("#asset_type_id").val();
    var sku = $('#sku').val();
    var asset_name = $("#asset_name").val();
    var quantity = $("#quantity").val();
    var product_mrp = $("#product_mrp").val();
    var gst_percent = $("#gst_percent").val();
    var hsn = $("#hsn").val();
    var lf_no = $("#lf_no").val();
    var check = $("#check").val();
    
    if(category_id=='')
    {
        $("#cat_error").html("Please select category").fadeIn();
        setTimeout(function(){$("#cat_error").fadeOut()},5000);                             
        return false;
    }
     
    if(asset_type_id=='')
    {
         $("#asset_type_error").html("Please select product type").fadeIn();
        setTimeout(function(){$("#asset_type_error").fadeOut()},5000);                                
        return false;
    }

    if(sku=='')
    {
         $("#sku_error").html("Please enter SKU").fadeIn();
        setTimeout(function(){$("#sku_error").fadeOut()},5000);                                
        return false;
    }

    if(asset_name=='')
    {
         $("#asset_name_error").html("Please enter product name").fadeIn();
        setTimeout(function(){$("#asset_name_error").fadeOut()},5000);                                 
        return false;
    }

    if(quantity=='')
    {
         $("#quantity_error").html("Please enter quantity").fadeIn();
        setTimeout(function(){$("#quantity_error").fadeOut()},5000);                                 
        return false;
    }

    if(product_mrp=='')
    {
        $("#product_mrp_error").html("Please enter product price").fadeIn();
        setTimeout(function(){$("#product_mrp_error").fadeOut()},5000);                                 
        return false;
    }

    if(gst_percent=='')
    {
        $("#gst_percent_error").html("Please enter GST %").fadeIn();
        setTimeout(function(){$("#product_mrp_error").fadeOut()},5000);                                 
        return false;
    }

    if(hsn=='')
    {
        $("#hsn_error").html("Please enter HSN").fadeIn();
        setTimeout(function(){$("#hsn_error").fadeOut()},5000);                                 
        return false;
    }

    if(lf_no=='')
    {
        $("#lf_no_error").html("Please enter LF No.").fadeIn();
        setTimeout(function(){$("#lf_no_error").fadeOut()},5000);                                 
        return false;
    }
            
}
</script>

