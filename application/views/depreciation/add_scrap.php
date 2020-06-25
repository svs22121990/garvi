<?php
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<style type="text/css">
.bordered{
  margin-bottom: 20px;
  margin-top: 20px;
  border:1px solid #eee;
  width: 100%;
}
</style>
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

      <form class="form-horizontal" method="post" action="<?php echo $action; ?>" id="request_form">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
            <ul class="panel-controls">
              <li><a href="<?=site_url('Depreciation/index');?>"><span class="fa fa-arrow-left"></span></a></li>
            </ul>
          </div>

          <div class="panel-body">                                                                        
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-4">
                  <label for="asset">Asset Type<span style="color:red">*</span></label>
                  <input type="text" class="form-control" value="<?php if(!empty($assetTypeData[0]->type)) {echo $assetTypeData[0]->type;}?>" readonly>
                  <input type="hidden" name="asset_type_id" class="form-control" value="<?php if(!empty($assetTypeData[0]->id)){echo $assetTypeData[0]->id;}?>">
                </div>
                <div class="col-md-4">
                  <label for="asset">Asset Name<span style="color:red">*</span></label>
                  <input type="text" class="form-control" value="<?php if(!empty($assetData[0]->asset_name)){echo $assetData[0]->asset_name;}?>" readonly>
                  <input type="hidden" name="asset_id" class="form-control" value="<?php if(!empty($assetData[0]->id)){echo $assetData[0]->id;}?>">
                </div>  
                <div class="col-md-4">
                  <label for="assets">Product SKU<span style="color:red">*</span></label>
                  <input type="text" class="form-control" name="barcode_number" value="<?php if(!empty($assetDetails[0]->barcode_number)){echo $assetDetails[0]->barcode_number;}?>" readonly>
                  <input type="hidden" name="asset_details_id" class="form-control" value="<?php if(!empty($assetDetails[0]->id)){echo $assetDetails[0]->id;}?>">
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-4">
                  <label for="assets">Is Scalable<span style="color:red">*</span><span id="error_sale" style="color:red"></span></label>
                  <select name="salebale" class="form-control select" id="salebale" onchange="return append_price(this.value)">
                    <option value="">Please Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                <div class="col-md-4" style="display: none;" id="pricediv">
                  <label for="assets">Price<span style="color:red">*</span><span id="error_price" style="color:red"></span></label>
                  <input type="text" name="price" id="price" class="form-control" Placeholder="Price" onkeypress="only_number(event)" maxlength="6">
                </div>
              </div>
                <div class="clearfix">&nbsp;</div>
            </div>
            <div class="panel-footer">
              <button class="btn btn-success" type="submit" id="submit" onclick="return addValidation();"><?= $button;?></button>
              <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>                                    
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>                    

</div>
<!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript">
  var url="";
  var actioncolumn="";
</script>

<?php $this->load->view('common/footer');?>
 <script type="text/javascript">
  function append_price(value)
  {
    if(value == 'Yes')
    {
      $('#pricediv').show();
    } 
    else
    {
      $('#pricediv').hide();
      $('#price').val('');
    } 
  }

</script>
<script>
  $("#price").keypress(function(event){
    var x = event.which || event.keyCode;
    console.log(x);
    if((x >= 48 ) && (x <= 57 ) || x == 8 | x == 9 || x == 13 )
    {
      return;
    }else{
      event.preventDefault();
    } 
  })
</script>
<script type="text/javascript">    
  function addValidation() 
  {
    var sale = $("#salebale").val();
    var price = $('#price').val();
    if(sale==null || sale=='')
    {    
      $("#error_sale").fadeIn().html("Required");
      setTimeout(function(){ $("#error_sale").fadeOut(); }, 3000);
      $("#error_sale").focus();
      return false; 
    }

    if(sale == 'Yes')
    {
        if(price=='')
        {
          $("#error_price").fadeIn().html("Please Enter Price");
          setTimeout(function(){$("#error_price").html("&nbsp;");},5000)
          $("#price").focus();
          return false;
        }
    }

  }

</script> 