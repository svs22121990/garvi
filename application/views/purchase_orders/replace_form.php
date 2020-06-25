<?php
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<!-- START BREADCRUMB -->
   <?= $breadcrumbs ?>
<!-- END BREADCRUMB -->   
<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<style>
textarea {
    resize: none;
}
</style>


<div class="page-content-wrap"> 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">                           
                  <div class="col-md-4 text-light-blue"><h3><?= $heading ?></h3></div>
                  <div class="col-md-4"></div>
                  <div class="col-md-4"><span style="color:red;float:right;">* Fields required</span></div>
                  <div class="clearfix"></div>   
                </div>
                <div class="panel-body">
                  <form id="" method="POST" action="<?php echo $action; ?>" class="form-horizontal">
                <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label for="vendor">Vendor <span style="color:red">*</span></label>
                    <select class="form-control select" id="vendor_id" name="vendor_id" onchange="GetPurchaseOrders(this.value)" data-live-search="true">
                      <option value=''>-- Select Vendor --</option>
                      <?php foreach($vendors as $row) { ?>
                      <option value="<?= $row->id ?>"><?= $row->name.' ('.$row->shop_name.')'; ?></option>    
                      <?php } ?>
                    </select>
                      &nbsp;<span id="error2" style="color:red"></span>&nbsp;
                  </div>

                  <div class="col-md-6">
                    <label for="vendor">PO Number <span style="color:red">*</span></label>
                    <select class="form-control select" id="purchase_order_id" name="purchase_order_id" onchange="GetProductDetails(this.value)" data-live-search="true">
                      <option value=''>-- Select Purchase Order --</option>                      
                    </select>
                      &nbsp;<span id="error3" style="color:red"></span>&nbsp;
                  </div>
                </div>
              </div>
              <span id="showEditError" style="color:red;"></span>
              <div id="appendTable"></div>
                             
              <div class="hr-line-dashed"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <button class="btn btn-primary" type="submit" id="submit" onclick="return addValidation();">Create</button>
                    <button onclick="window.history.back()" class="btn btn-default" type="button">Cancel</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

<script>
    var url = '';
    var actioncolumn = '';
</script>
<script type="text/javascript">
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
<script type="text/javascript">
    function GetAssets(id)
    {
        $.ajax({
            type:'post',
            url:"<?= site_url('Purchase_orders/GetAssets') ?>",
            data:{id:id},
            success:function(response){
                $("#asset_type_id").html(response).selectpicker('refresh');
                $('#purchase_order_id').val('');
                $("#appendTable").html('');
            }
        });
    } 

  function GetPurchaseOrders(id)
  {
    var atid = $("#asset_type_id").val();
    var vid = $("#vendor_id").val();
    $.ajax({
      type:'post',
      url:"<?= site_url('Purchase_replace/GetPurchaseOrders') ?>",
      data:{vid:vid,atid:atid},
      success:function(response){ 
        $("#purchase_order_id").html(response).selectpicker('refresh');
         $("#appendTable").html('');
      }
    })
  }

  function GetProductDetails(id)
  {
    $.ajax({
      type:'post',
      url:"<?= site_url('Purchase_replace/ProductDetails') ?>",
      data:{id:id},
      success:function(response){
        $("#appendTable").html(response).selectpicker('refresh');
      }
    });
  }
</script>
<script type="text/javascript">
  function addValidation()
  {
    var noItems = $("#noItems").val();
    if(noItems == 'no'){
      $("#showEditError").fadeIn().html("No items to replace");
      setTimeout(function(){$("#showEditError").fadeOut();},3000);
      return false;  
    }
    var vendor_id = $("#vendor_id").val();
    var purchase_order_id = $("#purchase_order_id").val();


    if(vendor_id=='')
    {
      $("#error2").fadeIn().html("please select vendor.");
      setTimeout(function(){$("#error2").fadeOut();},3000);
      $("#vendor_id").focus();
      return false;
    }

    if(purchase_order_id=='')
    {
      $("#error3").fadeIn().html("please select purchase order.");
      setTimeout(function(){$("#error3").fadeOut();},3000);
      $("#purchase_order_id").focus();
      return false;
    }

    var srNo = $(".trRow").length;
    var count = 0;
    for(var i=1; i<=srNo; i++)
    { 
      var oldqty = $("#oldqty"+i).val(); 
      var quantity = $("#quantity"+i).val();
      
      if(parseFloat(quantity) > parseFloat(oldqty))
      {
        $("#errRec"+i).fadeIn().html("please enter valid quantity.");
        setTimeout(function(){$("#errRec"+i).fadeOut();},3000);
        $("#quantity"+i).focus();
        return false;
      } 

      if(quantity!=''){
        count++;
      }
    } 

    if(count == 0)
    {
      $("#showEditError").fadeIn().html("Please enter some field");
      setTimeout(function(){$("#showEditError").fadeOut();},3000);
      return false; 
    }   

  }
  function collectQty(sr){
    var len=$('.quantitychk'+sr).filter(':checked').length
    var qty=$('#quantity'+sr).val(len);
    //alert(len);
  }
</script>
<?php $this->load->view('common/footer');?>