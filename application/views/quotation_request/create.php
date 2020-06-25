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
              <li><a href="<?=site_url('Quotation_request/index');?>"><span class="fa fa-arrow-left"></span></a></li>
            </ul>
          </div>

          <div class="panel-body">                                                                        
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  <label for="vendor">Request No.</label>
                  <input type="text"  name="quotation_request_no" value="<?= $quotation_request_no;?>" class="form-control" readonly><div>&nbsp;</div>
                </div>
                <div class="col-md-6">
                  <label for="vendor">Select User <span style="color:red">*</span><span id="error_vandor_id" style="color:red"></span></label>
                  <select name="vandor[]" class="form-control select vandor_id" id="vandor_id" data-live-search="true"  multiple>
                    <option value="">Select User</option>
                    <?php foreach($vandor as $row){?>
                      <option value="<?= $row->id;?>" <?php if($button=='Update'){foreach($vandorIds as $vandors){if($vandors->vandor_id==$row->id){echo 'selected';}}}?>><?= $row->name;?></option>
                    <?php }?>
                  </select>
                  <input type="hidden" id="vandor_input" name="vandor_input[]">
                </div>  
                <div class="col-md-12">
                  <table class="table table-bordered table-responsive" id="request_table">
                    <thead>
                      <tr>
                       <th class="col-xs-5">Product Type <span style="color:red">*</span><span id="erraAssetType" style="color:red"></span></th>
                       <th class="col-xs-5">Products <span style="color:red">*</span><span id="errAsset"  style="color:red"></span></th>
                       <th class="col-xs-5">Quantity <span style="color:red">*</span><span id="errQuantity"  style="color:red"></span> </th>
                       <th width="10%" class="text-center"> <button type="button" class="btn btn-info btn-sm" onclick="addrow()" style="font-size: 16px"><b>+</b></button></th>
                     </tr>
                   </thead>
                   <tbody id="request_tbody">
                    <?php if($button=='Create'){?>
                      <tr>
                        <td>
                          <select name="asset_type[]" class="form-control asset_type" id="asset_type_1" onchange="getAssets(this.value,$(this).closest('tr').index());">
                            <option value="">Select Product Type</option>
                            <?php foreach($asset_type as $type) { ?>
                            <option value="<?php echo $type->id ?>"><?php echo $type->type; ?></option>
                            <?php } ?>
                          </select>
                          <input type="hidden" value="1" class="rowvalue">
                        </td>
                        <td>
                          <select name="asset[]" class="form-control attrname" id="asset_1">
                            <option value="">Select Product</option>
                          </select>
                        </td>
                        <td>
                          <input type="text" name="quantity[]" class="form-control qty" id="qty_1">
                        </td>
                        <td class="text-center">
                          <button title="Delete row" type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)" style="font-size: 16px"><b><i class="fa fa-minus"></i></b></button>
                        </td>
                      </tr>
                    <?php }else{
                      $sr=1; foreach($quotation_requests_details as $request){
                        $assets = $this->Crud_model->GetData("assets",'asset_name,id',"asset_type_id='".$request->assets_type_id."'","","asset_name asc");
                        ?>
                        <tr>
                          <td>
                            <select name="asset_type[]" class="form-control asset_type" id="asset_type_<?= $sr;?>" onchange="getAssets(this.value,$(this).closest('tr').index());">
                              <option value="">Select Product Type</option>
                              <?php foreach ($asset_type as $row) { ?>
                                <option value="<?= $row->id ?>" <?php if($request->assets_type_id==$row->id)echo "selected"; ?>><?= $row->type ?></option>
                              <?php } ?>
                            </select>
                            <input type="hidden" value="1" class="rowvalue">
                          </td>
                          <td>
                            <select name="asset[]" class="form-control asst attrname" id="asset_<?= $sr;?>">
                              <option value="">Select Product</option>
                              <?php foreach($assets as $row){?>
                                <option value="<?= $row->id; ?>" <?php if($request->asset_id==$row->id)echo 'selected';?> ><?= $row->asset_name;?></option>
                              <?php }?>
                            </select>
                          </td>
                          <td>
                            <input type="text" name="quantity[]" class="form-control qty" id="qty_<?= $sr;?>" value="<?= $request->quantity?>">
                          </td>
                          <td class="text-center">
                            <button title="Delete row" type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)" style="font-size: 16px"><b><i class="fa fa-minus"></i></b></button>
                          </td>
                        </tr>
                        <?php $sr++;}}?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="clearfix">&nbsp;</div>
              </div>
            </div>
            <div class="panel-footer">
              <button class="btn btn-success" type="submit" id="submit" onclick="return addValidation();"><?= $button;?></button>
              <input type="hidden" id="request_id" name="request_id" value="<?php if($button=='Update'){ echo $id;}?>">
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
<script>
 function addrow()
 {   
  var y=document.getElementById('request_tbody');
  var new_row = y.rows[0].cloneNode(true);
  var len = y.rows.length;
  
  var inp1 = new_row.cells[0].getElementsByTagName('select')[0];
  inp1.value = '';
  inp1.id = 'asset_type_'+(len+1);

  var inp2 = new_row.cells[1].getElementsByTagName('select')[0];
  inp2.value = '';
  inp2.id = 'asset_'+(len+1);

  var inp3 = new_row.cells[2].getElementsByTagName('input')[0];
  inp3.value = '';
  inp3.id = 'qty_'+(len+1);
  y.appendChild(new_row);

  //$('#asset_type_'+(len+1)).selectpicker('refresh');
  //$('#asset_'+(len+1)).selectpicker('refresh');
}

   //delete row 
   function deleteRow(row)
   {
     var y= document.getElementById('request_table');
     var len = y.rows.length;
     if(len>2)
     {
       var i= row.parentNode.parentNode.rowIndex;
       document.getElementById('request_table').deleteRow(i);
     }
   }
 </script>
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

  /*$("#vandor_id").change(function(){
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
  });*/

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
      $("#error_vandor_id").fadeIn().html("Please select user");
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
        $("#erraAssetType").fadeIn().html("Please select product type");
        setTimeout(function(){ $("#erraAssetType").fadeOut(); }, 3000);
        $("#asset_type_"+i).focus();
        return false; 
      }

      if(asset1=="")
      {    
        $("#errAsset").fadeIn().html("Please select product");
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
        $("#errAsset").html("Product should be unique").fadeIn();
        setTimeout(function(){$("#errAsset").fadeOut();},8000)
        return false;
      }

    }
$(".loader").fadeIn('fast'); 
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