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
            <form class="form-horizontal" method="post" action="<?php echo $action; ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                        <li><a href="<?= site_url('Scrap_assets/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">                           
                        <div class="col-md-12">
                             <div class="col-md-4">
                                <div class="form-group">                                        
                                  <label class="col-md-12">Asset Type<span style="color:red;">*</span> <span id="errassets_type_id" style="color:red"></span></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="assets_type_id" id="assets_type_id" data-live-search="true" onchange="getAssets()">
                                            <option value="0">--Select Asset Type--</option>
                                            <?php 
                                               foreach($asset_types as $row) { ?>
                                                 <option value="<?php echo $row->id ?>" <?php if($assets_type_id == $row->id){ echo "selected"; }?>><?php echo $row->type; ?></option>
                                            <?php } ?>
                                          </select>      
                                          
                                      </div>
                                  </div>
                              </div>
                              
                              <div class="col-md-4">
                              <div class="form-group">                                        
                                  <label class="col-md-12">Assets <span style="color:red;">*</span> <span id="errassets_id" style="color:red"></span></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="assets_id" id="assets_id" data-live-search="true" onchange="return assetDetail(this.value)">
                                            <option value="0">--Select Assets--</option>
                                           <?php if(!empty($assets)) {
                                               foreach($assets as $row1) { ?>
                                                 <option value="<?php echo $row1->id ?>" <?php if($asset_id == $row1->id){ echo "selected"; }?>><?php echo $row1->asset_name; ?></option>
                                            <?php } } ?> 
                                          </select>  
                                                                                    
                                      </div>
                                  </div>
                              </div>

                              <div class="col-md-4">
                              <div class="form-group">                                        
                                  <label class="col-md-12">Product SKU <span style="color:red;">*</span> <span id="errasset_detail_id" style="color:red"></span><span style="color:red"> <?php echo form_error('asset_detail_id') ?></span><span style="color:red" class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></label>
                                  <div class="col-md-10">
                                  <input type="text" name="asset_detail" id="asset_detail_id" class="form-control" value="<?php echo $barcode_number ?>" Placeholder="Product Sku" onkeypress="only_number(event)">
                                   
                                  <div id="searchbox"></div>
                                     
                                  </div>
                              </div>
                              </div>
                              <input type="hidden" name="asset_detail_id" id="check" value="<?php echo $asset_detail_id ?>">
                              <div class="col-md-4">
                              <div class="form-group">                                        
                                  <label class="col-md-12">Is Saleable<span style="color:red;">*</span> <span id="errsale" style="color:red"></span></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="sale" id="sale" data-live-search="true" onchange="return append_price(this.value)">
                                            <option value="0">--Select--</option>
                                            <option value="Yes" <?php if($sale == 'Yes'){ echo "selected"; }?>>Yes</option>
                                            <option value="No" <?php if($sale == 'No'){ echo "selected"; }?>>No</option>
                                           
                                          </select>  
                                                                                    
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-4" <?php if($button == 'Edit') { if($sale == 'No') { ?> style="display: none" <?php } } else { ?> style="display: none" <?php } ?> id="show_price">
                              <div class="form-group">                                        
                                  <label class="col-md-12">Price<span style="color:red;">*</span> <span id="errprice" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        
                                        <input type="text" name="price" id="price" class="form-control" value="<?php 
                                         if($price != '0'){ echo $price; } else { ''; } ?>" Placeholder="Price" onkeypress="only_number(event)" maxlength="6">                                             
                                    </div>
                                  </div>
                              </div>

                              <div class="clearfix">&nbsp;</div>
                   </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" type="submit" id="submit" onclick="return validation();"><?= $button;?></button>
                    <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>
                </div>
            </div>
            <input type="hidden" name="btnType" id="btnType" value="<?php echo $button; ?>">
            </form>
            
        </div>
    </div>                    
    
</div>
<!-- END PAGE CONTENT WRAPPER -->  
<?php $this->load->view('common/footer'); ?>

<script type="text/javascript">
  function getAssets(date)
  {
    var asset_type_id = $("#assets_type_id").val();  
    if(asset_type_id !='0')
    {
        //var datastring = "id="+asset_type_id+"&branch_id="+branch_id;   
        $('.loader').fadeIn();             
        var datastring = "id="+asset_type_id;                
            $.ajax({
                type: "post",
                url: "<?php echo site_url('Scrap_assets/getAssets'); ?>",
                data: datastring,
                success: function(returndata) {   
                //alert(returndata);                    
                    $('#assets_id').html(returndata).selectpicker('refresh');   
                    $('#asset_detail_id').val('');   
                    $('#check').val('');
                    $('.loader').fadeOut();
                }
            });
    }

  
  }

  function assetDetail(asset_id,asset_detail_id='')
  {
    var site_url = $("#site_url").val(); 
      src = site_url+"/Scrap_assets/assetDetail/"+asset_id;
      $('#asset_detail_id').val(asset_detail_id);   
               
 
      $("#asset_detail_id").autocomplete({
       
        appendTo: "#searchbox",
        source: function(request, response) {
   		//alert(request);return false;
          $(".ui-autocomplete").html('<img src="<?= base_url('../admin/assets/default.gif'); ?>" alt="">');
           $.getJSON(src, {search : request.term}, 
            response);
        },

       select: function(event, ui) {$("#check").val(ui.item.sub_cat_title); }

      });

  }

</script>
<?php if($button == 'Edit') { ?>
<script type="text/javascript">
  var asset_id = $('#assets_id').val();
  var asset_detail_id = $('#asset_detail_id').val();
  assetDetail(asset_id,asset_detail_id);
</script>
<?php } ?>
<script type="text/javascript">

function validation()
{       
        var sale = $("#sale").val();
        var assets_type_id = $("#assets_type_id").val(); //alert(branch_id);return false;
        var assets_id = $("#assets_id").val(); //alert(branch_id);return false;
        var asset_detail_id = $('#asset_detail_id').val();
        var price = $('#price').val();
        
       if(assets_type_id=="0")
      {
        $("#errassets_type_id").fadeIn().html("Please Select Asset Type");
        setTimeout(function(){$("#errassets_type_id").html("&nbsp;");},5000)
        $("#assets_type_id").focus();
        return false;
      } 


      if(assets_id=="")
      {
        $("#errassets_id").fadeIn().html("Please Select Asset");
        setTimeout(function(){$("#errassets_id").html("&nbsp;");},5000)
        $("#assets_id").focus();
        return false;
      } 

      if(asset_detail_id=="")
      {
        $("#errasset_detail_id").fadeIn().html("Please Select Asset Detail");
        setTimeout(function(){$("#asset_detail_id").html("&nbsp;");},5000)
        $("#asset_detail_id").focus();
        return false;
      } 
       if(sale==0)
      {
        $("#errsale").fadeIn().html("Please Select sale");
        setTimeout(function(){$("#errsale").html("&nbsp;");},5000)
        $("#sale").focus();
        return false;
      }

      if(sale == 'Yes')
      {
        if(price=='')
        {
          $("#errprice").fadeIn().html("Please Enter Price");
          setTimeout(function(){$("#errprice").html("&nbsp;");},5000)
          $("#price").focus();
          return false;
        }
      }
    
   }
   
    function append_price(value)
    {
      if(value == 'Yes')
      {
        $('#show_price').show();
      } 
      else
      {
        $('#show_price').hide();
        $('#price').val('');
      } 
    }

   
  function only_number(event)
{

  var x = event.which || event.keyCode;
  console.log(x);
  if((x >= 48 ) && (x <= 57 ) || x == 8 | x == 9 || x == 13 )
  {
    return;
  }else{
    event.preventDefault();
  }    
}


</script>




