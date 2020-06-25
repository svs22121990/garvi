 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
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
                            
                            <form class="form-horizontal" method="post" action="<?php echo $action; ?>">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                                    <ul class="panel-controls">
                                        <li><a href="<?=site_url('Purchase_orders/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body">                                                                        
                                    
                                      <div class="row">
                                            <div class="col-md-12">

                                              <div class="col-md-3">
                                                <label for="vendor">Purchase Order Number </label>
                                                <input type="text" value="<?= $order_number; ?>" class="form-control" readonly><div>&nbsp;</div>
                                              </div> 
                                              <div class="col-md-3">
                                                <label for="vendor">Purchase Date </label>
                                                <input type="text" value="<?= date('d-m-Y'); ?>" class="form-control" readonly><div>&nbsp;</div>
                                              </div>  
                                            <!--   <div class="col-md-3">
                                                <label for="vendor">Asset Types <span style="color:red">*</span></label>
                                                 <select class="form-control select" id="asset_type" name="asset_type" data-live-search="true" onchange="getVendors(this.value)"> 
                                                    <option value="">Select Asset Type</option>
                                                      <?php foreach($asset_types as $row) { ?>
                                                  <option value="<?= $row->id ?>"><?= $row->type; ?></option>    
                                                  <?php } ?>
                                                </select>
                                                  &nbsp;<span id="error1" style="color:red"></span>&nbsp;
                                              </div> -->
                                              <!-- onchange="GetAssets(this.value)" -->
                                              <div class="col-md-3">
                                                <label for="vendor">Vendor <span style="color:red">*</span></label>
                                                <select class="form-control select" id="vendor_id" name="vendor_id" onchange="getCategory(this.value)" data-live-search="true">
                                                  <option value=''>Select Vendor</option>
                                                  <?php if(!empty($vendors)) { foreach($vendors as $vendor){ ?>
                                                      <option value='<?=$vendor->id; ?>'><?=$vendor->name.' ('.$vendor->shop_name.')'; ?></option>
                                                  <?php } }?>
                                                </select>
                                                  &nbsp;<span id="error2" style="color:red"></span>&nbsp;
                                              </div>
                                            
                                             <!--   <div class="col-md-3">
                                                <label for="branch">Branch </label>
                                                <select class="form-control select" id="branch_id" name="branch_id" data-live-search="true">
                                                  <option value='0'>Select Branch</option>
                                                  <?php foreach($branches as $row) { ?>
                                                  <option value="<?= $row->id ?>"><?= $row->branch_title; ?></option>    
                                                  <?php } ?>
                                                </select>
                                               
                                              </div> -->
                                              <div class="col-md-3">
                                                <label for="Quotation">Quotation </label>
                                                <select class="form-control select" id="quotation_id" name="quotation_id" data-live-search="true" onchange="getQuotedata(this.value)">
                                                  <option value='0'>Select Quotation</option>
                                                
                                                </select>
                                               
                                              </div>
                                            </div>
                                            <div class="clearfix">&nbsp;</div>
                                            <div class="col-md-12" id="old_table">
                                                    <table class="table table-striped " width="100%" >
                                                      <thead style="border: 1px solid #EAEBEC;box-shadow: 0px 0px 0px 1px #ccc">
                                                        <tr>
                                                          <th width="20%"><center>Category</center></th>
                                                          <th width="20%"><center>Subcategory</center></th>
                                                          <th width="25%"><center>Asset</center></th>
                                                          <!-- <th width="15%"><center>Brand</center></th> -->
                                                          <th width="15%"><center>Quantity</center></th>
                                                          <th width="15%"><center>Unit</center></th>
                                                          <th width="10%"> <button type="button" class="btn btn-info" onclick="addrow()" style="width: 100%;font-size: 16px"><b>+</b></button></th>
                                                        </tr>
                                                      </thead>
                                                      <tbody class="apend_td" id="professorTableBody">
                                                        <tr class="tbl_tr_1 textmult trRow" id="tbl_tr_1">
                                                          <td>
                                                             <select class="form-control cat_id select" id="cat_id1" name="cat_id[]" onchange="GetSubcategory(this.value,'1')" data-live-search="true">
                                                                  <option value=''>Select Category</option>
                                                                 <!--  <?php foreach($category as $row) { ?>
                                                                  <option value="<?= $row->id ?>"><?= $row->title; ?></option>    
                                                                  <?php } ?> -->
                                                                </select>
                                                          </td>
                                                          <td>
                                                            <select tabindex="3" class="form-control subcat_id select" id="subcat_id1" name="subcat_id[]" onchange="Getassetdata(this.value,'1')" data-live-search="true">
                                                            <option value="">Select Subcategory</option>
                                                            </select>
                                                          </td>
                                                          <td>
                                                            <select tabindex="3" class="form-control asset_id select" id="asset_id1" name="asset_id[]" onchange="Getassetunit(this.value,'1')" data-live-search="true">
                                                            <option value="">Select asset</option>
                                                            </select>
                                                          </td>
                                                          <td>
                                                            <input tabindex="4" type="text" name="quantity[]" id="quantity1" class="form-control quantity" placeholder="Quantity" value="1" maxlength="4" onkeypress="only_number(event)" autocomplete="off">
                                                          </td>
                                                          <td>
                                                           <input type="text" readonly="" placeholder="Unit" id="unit_val1" class="form-control unit_val">
                                                           <input type="hidden" placeholder="Unit" name="unit_id[]" id="unit_id1" class="form-control unit_id">
                                                          </td>
                                                          <td>
                                                            <button type="button" class="btn btn-danger m-b" style="width: 100%;font-size: 16px"  onclick="remove_tr($(this).closest('tr').index())" ><b>-</b></button> 
                                                          </td>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                  </div>
                                                  <div class="col-md-12" id="new_table">
                                                      

                                                  </div>
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
    function getVendors(id)
    {
      $('.loader').fadeIn();
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/getVendors') ?>",
        data:{id:id},
        success:function(response){
            $("#vendor_id").html(response).selectpicker('refresh');
            $("#quotation_id").html('<option value="">Select Quotation</option>').selectpicker('refresh');
            $('#old_table').show();
            $('#new_table').hide();
            $('#new_table').html('');
             var keys = [];
                          $('.trRow').each(function(){
                            var trId=$(this).attr('id');
                            var explodetrid=trId.split("_");
                            keys.push(explodetrid[2]);
                          });

                        for (i=0;i<keys.length;i++){
                              var id=keys[i];
                              var catId=$('#cat_id'+id).html('<option value="">Select Category</option>').selectpicker('refresh');
                              var subcatId=$('#subcat_id'+id).html('<option value="">Select Subcategory</option>').selectpicker('refresh');
                              var assetId=$('#asset_id'+id).html('<option value="">Select Asset</option>').selectpicker('refresh');
                              $('#unit_val'+id).val('');
                              $('#unit_id'+id).val('');
                        }
                       $('.loader').fadeOut();
        }
        });
    }

    function getQuotedata(id)
    {
       $('.loader').fadeIn();
      if(id!=''){

      var asset_type_id=$('#asset_type').val();
      var vendor_id=$('#vendor_id').val();
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/getQuotedata') ?>",
        data:{id:id,vendor_id:vendor_id,asset_type_id:asset_type_id},
        success:function(response){
            //alert(response);return false;
            $('#old_table').hide();
            $('#new_table').show();
            $('#new_table').html(response);
             $('.loader').fadeOut();
        }
        });
      }else{
        $('#old_table').show();
        $('#new_table').hide();
        $('#new_table').html('');
         $('.loader').fadeOut();
      } 
    }
    function GetAssets(id)
    {
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/GetAssets') ?>",
        data:{id:id},
        success:function(response){
            $("#asset_type").html(response).selectpicker('refresh');
        }
        });
    }

    function getCategory(id)
    {
       $('.loader').fadeIn();
      var asset_type_id=$('#asset_type').val();
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/getCategory') ?>",
        data:{id:asset_type_id,vendor_id:id},
        success:function(response){
          var dt=jQuery.parseJSON(response);
          //alert(dt);return false;
          $('#quotation_id').html(dt.quotation).selectpicker('refresh');
          $('#old_table').show();
          $('#new_table').hide();
          $('#new_table').html('');
                   var keys = [];
                          $('.trRow').each(function(){
                            var trId=$(this).attr('id');
                            //alert(trId);return false;
                            var explodetrid=trId.split("_");
                            keys.push(explodetrid[2]);
                          });

                        for (i=0;i<keys.length;i++){
                             //alert(keys[i]);
                              var id=keys[i];
                              var catId=$('#cat_id'+id).html(dt.category).selectpicker('refresh');
                              var subcatId=$('#subcat_id'+id).html('<option value="">Select Subcategory</option>').selectpicker('refresh');
                              var assetId=$('#asset_id'+id).html('<option value="">Select Asset</option>').selectpicker('refresh');
                              $('#unit_val'+id).val('');
                              $('#unit_id'+id).val('');
                        }

                         $('.loader').fadeOut();
                }
        });
    }
    function GetSubcategory(id,sr)
    {
       $('.loader').fadeIn();
        var asset_type_id=$('#asset_type').val();
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/GetSubcategory') ?>",
        data:{id:id,asset_type_id:asset_type_id},
        success:function(response){
             //sr = parseInt(sr)+1; 
            $("#subcat_id"+sr).html(response).selectpicker('refresh');
            $("#asset_id"+sr).html('<option value="">Select Asset</option>').selectpicker('refresh');
             $('.loader').fadeOut();
        }
        });
    }
    function Getassetdata(id,sr)
    {
       $('.loader').fadeIn();
      var asset_type_id=$('#asset_type').val();
      var datastr='sub_cat='+id+'&asset_type_id='+asset_type_id;
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/Getassetdata') ?>",
        data:datastr,
        success:function(response){
             //sr = parseInt(sr)+1; 
            $("#asset_id"+sr).html(response).selectpicker('refresh');
            $("#unit_id"+sr).val('');
            $("#unit_val"+sr).val('');
             $('.loader').fadeOut();
        }
        });
    }
     function Getassetunit(id,sr)
    {
      var asset_type_id=$('#asset_type').val();
       $('.loader').fadeIn();
      if(id!=''){
      var datastr='asset_id='+id;
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/Getassetunit') ?>",
        data:datastr,
        success:function(response){
          var obj =jQuery.parseJSON(response);
             //sr = parseInt(sr)+1; 
             //alert(obj.unitid);
            $("#unit_id"+sr).val(obj.unitid);
            $("#unit_val"+sr).val(obj.val);
             $('.loader').fadeOut();
        }
        });
    }else{
           $("#unit_id"+sr).val('');
           $("#unit_val"+sr).val('');
            $('.loader').fadeOut();
    }
  }
    function resetData()
  { 
    $('.cat_id').val('').selectpicker('refresh');
    $('.subcat_id').val('').selectpicker('refresh');
    $('.asset_id').val('').selectpicker('refresh');
    $('.unit_val').val('');
    $('.unit_id').val('');
   
   
 
  }
</script>
<script>
  function addrow()
  {
     $('.loader').fadeIn();
      var y=document.getElementById('professorTableBody'); 
      var new_row = y.rows[0].cloneNode(true); 
      var len = y.rows.length; 
  
       var keys = 0;
        $('.trRow').each(function(){
          var trId=$(this).attr('id');
          //alert(trId);return false;
          var explodetrid=trId.split("_");
          keys = explodetrid[2];
         

        });
       var key=parseInt((keys))+1;
       var vendor_id=$('#vendor_id').val();
       var datastr='count='+(key)+'&id='+vendor_id;
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/getRows/') ?>"+(key),
        data:datastr,
        success:function(response){
         $('#professorTableBody').append(response);
         $('#cat_id'+(key)).selectpicker('refresh');  
         $('#subcat_id'+(key)).selectpicker('refresh');  
         $('#asset_id'+(key)).selectpicker('refresh');  

          $('.loader').fadeOut();
        }
        });
      


  }
   
  function remove_tr(row)
  {  
      var y=document.getElementById('professorTableBody');
      var len = y.rows.length;
      if(len>1)
      {
        var i= (len-1);
        document.getElementById('professorTableBody').deleteRow(row);
      }
  }   
</script>
 <script type="text/javascript">    
function addValidation() 
{
  $(".error_msg").html('');
  var cat_id = "";
  var asset_id = "";
  var subcat_id = "";
  var brand_id = "";
  var quantity_data = "";
  var cat_count = 0;
  var asset_count = 0;
  var subcat_count = 0;
  var brandt_count = 0;
  var quantity_count = 0;
  var unit_count = 0;
  var error_count = 0;
  var error_msg = "";
  var trRow = $(".trRow").length;

  var vendor_id = $("#vendor_id").val();
  var asset_type = $("#asset_type").val();
  var quotation_id = $("#quotation_id").val();
  $("#error1").html('&nbsp;');
  $("#error2").html('&nbsp;');




if(asset_type==""){    
  $("#error1").fadeIn().html("Please select asset type");
  setTimeout(function(){ $("#error1").fadeOut(); }, 3000);
  $("#asset_type").focus();
  return false; 
  }

if(vendor_id==""){    
  $("#error2").fadeIn().html("Please select vendor");
  setTimeout(function(){ $("#error2").fadeOut(); }, 3000);
  $("#vendor_id").focus();
  return false; 
  }


  var keys = [];
  $('.trRow').each(function(){
    var trId=$(this).attr('id');
    //alert(trId);return false;
    var explodetrid=trId.split("_");
    keys.push(explodetrid[2]);
   

  });
  var keylen=keys.length;
if(quotation_id==''){
for (i=0;i<keys.length;i++){
     //alert(keys[i]);
     var id=keys[i];
      var catId=$('#cat_id'+id).val();
      var subcatId=$('#subcat_id'+id).val();
      var assetId=$('#asset_id'+id).val();
      var quantity=$('#quantity'+id).val().trim();
      
     var ckkdp= checkDuplicate(keylen,assetId);

      //alert(ckkdp);return false;
            if(catId==""){    
               alert("Please select category");
              $('#cat_id'+id).focus();
              return false; 
            }
            if(subcatId==""){    
               alert("Please select sub category");
              $('#subcat_id'+id).focus();
              return false; 
            }
            if(assetId==""){    
               alert("Please select asset");
              $('#asset_id'+id).focus();
              return false; 
            }

            if(ckkdp==1){
                alert("Asset should be unique" );
              $('#asset_id'+id).focus();
              return false; 
            }

             if(quantity=="" || quantity=="0"){    
               alert("Please enter quantity");
              $('#quantity'+id).focus();
              return false; 
            }

         }
    }
    $('.loader').fadeIn("fast");
}

function checkDuplicate(keylen,id){
var myarray = [];
  var duplicate=0;
  var duplicates_list=[];
  if(keylen > 1){
    $("select[name~='asset_id[]']").each(function(){
        var val=$(this).val();
        if($.inArray(val, duplicates_list ) == -1){
                    duplicates_list.push(val);
                }
    });
  }
  var duplen= duplicates_list.length;
  if(keylen > 1){
    if(duplen < keylen){
      duplicate=1;
    } 
  }
return duplicate;


}
    
</script> 
