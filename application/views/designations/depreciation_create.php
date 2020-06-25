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
                <div class="col-md-6">
                  <label for="vendor">Select Asset Type<span style="color:red">*</span><span id="error_asset_type_id" style="color:red"></span></label>
                  <select name="asset_type_id[]" class="form-control select asset_type_id" id="asset_type_id" data-live-search="true">
                    <option value="">Select Asset Type</option>
                  </select>
                </div>  
                <div class="col-md-6">
                  <label for="vendor">Select Asset <span style="color:red">*</span><span id="error_asset_id" style="color:red"></span></label>
                  <select name="asset_id" class="form-control select asset_id" id="asset_id" data-live-search="true">
                    <option value="">Select Asset</option>
                  </select>
                </div>  
                <div class="clearfix">&nbsp;</div>
              </div>
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
  function getAssets(asset_type_id,sr)
  {
    $('.loader').fadeIn();
    var i = parseInt(sr)+1;
    $.ajax({
      type:'post',
      url:"<?= site_url('Quotation_request/getAssets'); ?>",
      data:{asset_type_id:asset_type_id},
      success:function(response){
        $('#asset_'+i).html(response);
        $('.loader').fadeOut();
      }
    }); 
  }

  $("#vandor_id").change(function(){
    $('.loader').fadeIn();
    var datastring = $("#request_form").serialize();
    $.ajax({
      type:'post',
      url:"<?= site_url('Quotation_request/getAssetType'); ?>",
      data:datastring,
      success:function(response){
        $(".asset_type").html(response);
        $('.loader').fadeOut();
      }
    });
  });

</script>
<script>
  $(".qty").keypress(function(event){
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
    var vandor_id = $("#vandor_id").val();
    if(vandor_id==null)
    {    
      $("#error_vandor_id").fadeIn().html("Please select vandor");
      setTimeout(function(){ $("#error_vandor_id").fadeOut(); }, 3000);
      $("#vandor_id").focus();
      return false; 
    }

    var count = $('#request_tbody tr').length;
    for(var i=1; i <= count;  i++)
    {
      var asset_type1 = $("#asset_type_"+i).val();
      var asset1 = $("#asset_"+i).val();
      var qty1 = $("#qty_"+i).val();
      if(asset_type1=="")
      {    
        $("#erraAssetType").fadeIn().html("Please select asset type");
        setTimeout(function(){ $("#erraAssetType").fadeOut(); }, 3000);
        $("#asset_type_"+i).focus();
        return false; 
      }

      if(asset1=="")
      {    
        $("#errAsset").fadeIn().html("Please select asset");
        setTimeout(function(){ $("#errAsset").fadeOut(); }, 3000);
        $("#asset_"+i).focus();
        return false; 
      }

      if(qty1=="")
      {    
        $("#errQuantity").fadeIn().html("Please enter quantity");
        setTimeout(function(){ $("#errQuantity").fadeOut(); }, 3000);
        $("#qty_"+i).focus();
        return false; 
      }

      value=[];  
      $('.attrname').each(function(){ value.push(($(this).val().trim()));}); 
      var chk_duble = checkDuplicate(value);  

      if(chk_duble == true)
      {
        $("#errAsset").html("Already exist").fadeIn();
        setTimeout(function(){$("#errAsset").fadeOut();},8000)
        return false;
      }

    }

  }

  function checkDuplicate(name)
  { 
    var name_array = name.sort(); 
    var name_duplicate = [];
    for (var i = 0; i < name_array.length - 1; i++) 
    {
      if (name_array[i + 1] == name_array[i]) 
      {
       name_duplicate.push(name_array[i]);
     }
   }
      isValid = false;
   if(name_duplicate!='')
   {
     isValid = true;
   }
  return isValid;
}

</script> 