<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
 <?= $breadcrumbs ?>
<div class="page-content-wrap">
     <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?php echo $heading; ?> </h3>
                      <ul class="panel-controls">
                        <li><a href="<?php echo site_url('ConfigureReport'); ?>"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
               </div>
                <div class="panel-body">                                                                        
                   <div class="col-md-4"> 
                        <div class="form-group">                                        
                          <label class="col-md-12">Title<span style="color:red;">*</span> <span id="error_title" style="color:red"></span><span style="color:red;"><?php echo form_error('title') ?></span></label>
                          <div class="col-md-10">
                                    <input type="text" class="form-control" name="title" placeholder="Title" id="title" value="<?php if(!empty($GetData->title)) echo $GetData->title; ?>"/>
                              </div>
                          </div>
                    </div> 

                    <div class="col-md-4">
                        <div class="form-group">                                        
                          <label class="col-md-12">Primary Table <span style="color:red;">*</span> <span id="error_primary_table" style="color:red"></span></label>
                          <div class="col-md-10">
                            <select class="form-control select" name="primary_table" id="primary_table" onchange="return get_data(this.value)" >
                                <option value="">Select Primary Table </option>
                                <option value="Purchase_order" <?php if(!empty($GetData->primary_table)){ if($GetData->primary_table =="Purchase_order") echo "selected";} ?>>Purchase Order</option>
                                <option value="Vendor" <?php if(!empty($GetData->primary_table)){ if($GetData->primary_table =="Vendor") echo "selected";} ?>>Vendor</option>
                                <option value="Assets" <?php if(!empty($GetData->primary_table)){ if($GetData->primary_table =="Assets") echo "selected";} ?>>Assets</option>
                               
                            </select>
                              </div>
                          </div>
                    </div>
                    
                    <div class="col-md-12">&nbsp;</div>
                    <span style="display: none" id="purchase_order">
                    <div class="col-md-12">
                        <div class="form-group">                                        
                          <label class="col-md-12"><b>Purchase Order</b> <span id="errassets_type_id" style="color:red"></span></label>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" name="po_number" id="po_number" class="icheckbox" value="Yes" <?php if(!empty($GetData->po_number)){ if($GetData->po_number =="Yes") echo "checked";} ?>/> PO Number</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox"  name="po_vendor_name" id="vendor_name" value="Yes" <?php if(!empty($GetData->vendor_name)){ if($GetData->vendor_name =="Yes") echo "checked";} ?>/> Vendor Name</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="po_shop_name" id="shop_name" value="Yes" <?php if(!empty($GetData->shop_name)){ if($GetData->shop_name =="Yes") echo "checked";} ?>/> Shop Name</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="purchase_date" id="purchase_date" value="Yes" <?php if(!empty($GetData->purchase_date)){ if($GetData->purchase_date =="Yes") echo "checked";} ?>/>Purchase Date</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="quotation_no" id="quotation_no" value="Yes" <?php if(!empty($GetData->quotation_no)){ if($GetData->quotation_no =="Yes") echo "checked";} ?>/>Quotation No</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="po_status" id="po_status" value="Yes" <?php if(!empty($GetData->po_status)){ if($GetData->po_status =="Yes") echo "checked";} ?>/>PO status</label>
                            </div> 
                            
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="category_name" id="category_name" value="Yes" <?php if(!empty($GetData->category_name)){ if($GetData->category_name =="Yes") echo "checked";} ?>/>Category</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="sub_category_name" id="sub_category_name" value="Yes" <?php if(!empty($GetData->sub_category_name)){ if($GetData->sub_category_name =="Yes") echo "checked";} ?>/>Sub Category</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="asset_name" id="asset_name" value="Yes" <?php if(!empty($GetData->asset_name)){ if($GetData->asset_name =="Yes") echo "checked";} ?>/>Asset Name</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="quantity" id="quantity" value="Yes" <?php if(!empty($GetData->quantity)){ if($GetData->quantity =="Yes") echo "checked";} ?>/>Quantity</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="bill_no" id="bill_no" value="Yes" <?php if(!empty($GetData->bill_no)){ if($GetData->bill_no =="Yes") echo "checked";} ?>/>Bill No</label>
                            </div>
                          </div>
                    </div> 
                    </span>
                  <span style="display: none" id="vendor">
                    <div class="col-md-12">
                        <div class="form-group">                                        
                          <label class="col-md-12"><b>Vendor</b> <span id="errassets_type_id" style="color:red"></span></label>
                           <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox"  name="vendor_name" id="vendor_name" <?php if(!empty($GetData->vendor_name)){ if($GetData->vendor_name =="Yes") echo "checked";} ?> value="Yes"/> Vendor Name</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="shop_name" id="shop_name" value="Yes" <?php if(!empty($GetData->shop_name)){ if($GetData->shop_name =="Yes") echo "checked";} ?>/> Shop Name</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="payment_type" id="payment_type" value="Yes" <?php if(!empty($GetData->payment_type)){ if($GetData->payment_type =="Yes") echo "checked";} ?>/>Payment Type</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="check_no" id="check_no" value="Yes" <?php if(!empty($GetData->check_no)){ if($GetData->check_no =="Yes") echo "checked";} ?>/> Check No</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="account_no" id="account_no" value="Yes"/>Account No</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="check_date" id="check_date" value="Yes" <?php if(!empty($GetData->account_no)){ if($GetData->account_no =="Yes") echo "checked";} ?>/>Check Date</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="paid_by" id="paid_by" value="Yes" <?php if(!empty($GetData->paid_by)){ if($GetData->paid_by =="Yes") echo "checked";} ?>/>Paid By</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="bank_name" id="bank_name" value="Yes" <?php if(!empty($GetData->bank_name)){ if($GetData->bank_name =="Yes") echo "checked";} ?>/>Bank Name</label>
                            </div>
                          </div>
                    </div>
                    </span>
                    <span style="display: none" id="assets">
                    <div class="col-md-12">
                        <div class="form-group">                                        
                          <label class="col-md-12"><b>Assets</b> <span id="errassets_type_id" style="color:red"></span></label>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="asset_asset_name" id="asset_name" value="Yes" <?php if(!empty($GetData->asset_name)){ if($GetData->asset_name =="Yes") echo "checked";} ?>/>Asset Name</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="asset_quantity" id="quantity" value="Yes" <?php if(!empty($GetData->quantity)){ if($GetData->quantity =="Yes") echo "checked";} ?>/>Asset Quantity</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="asset_category_name" id="asset_category_name" value="Yes" <?php if(!empty($GetData->category_name)){ if($GetData->category_name =="Yes") echo "checked";} ?>/>Asset Category</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="asset_sub_category_name" id="asset_sub_category_name" value="Yes" <?php if(!empty($GetData->sub_category_name)){ if($GetData->sub_category_name =="Yes") echo "checked";} ?>/>Asset Sub Category</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="brand_name" id="brand_name" value="Yes" <?php if(!empty($GetData->brand_name)){ if($GetData->brand_name =="Yes") echo "checked";} ?>/>Asset Brand</label>
                            </div> 
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="asset_type" id="asset_type" value="Yes" <?php if(!empty($GetData->asset_type)){ if($GetData->asset_type =="Yes") echo "checked";} ?>/>Asset Type</label>
                            </div>
                            <div class="col-md-2">
                                  <label class="check"><input type="checkbox" class="icheckbox" name="unit_name" id="unit_name" value="Yes" <?php if(!empty($GetData->unit_name)){ if($GetData->unit_name =="Yes") echo "checked";} ?>/>Asset Unit</label>
                            </div>
                          </div>
                    </div>
                     </span>
                </div>
                <div class="panel-footer">
                    <button type="submit" onclick="return validations()" class="btn btn-success">Submit</button>
                    <a href="<?php echo site_url('ConfigureReport'); ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
            </form>
            
        </div>
    </div>                    
 </div>
<?php $this->load->view('common/footer');?>
 <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
  
    var type = $("#primary_table").val();
   
    console.log(type);
    get_data(type);
  })
function get_data(value)
{
   console.log(value);
    if(value == 'Purchase_order')
    {
        $('#purchase_order').show();
        $('#vendor').hide();
        $('#assets').hide();
    }
    if(value == 'Vendor')
    {
        $('#purchase_order').hide();
        $('#vendor').show();
        $('#assets').hide();
    } 
    if(value == 'Assets')
    {
        $('#purchase_order').hide();
        $('#vendor').hide();
        $('#assets').show();
    } 

}  

function validations()
{ 
  var title=$("#title").val().trim();
  var primary_table = $("#primary_table").val();

 if(title=="")
  {
    $("#error_title").fadeIn().html("Required");
    $("#title").css("border-color","red");
    setTimeout(function(){$("#error_title").html("&nbsp;");$("#title").css("borderColor","#00A654")},5000)
    $("#title").focus();
    return false;
  } 
  if(primary_table=="")
  {
    $("#error_primary_table").fadeIn().html("Required");
    $("#primary_table").css("border-color","red");
    setTimeout(function(){$("#error_primary_table").html("&nbsp;");$("#primary_table").css("borderColor","#00A654")},5000)
    $("#primary_table").focus();
    return false;
  }

  if($('input[type=checkbox]:checked').length == 0)
  {
    alert("Please select atleast one check box"); return false;
  } 
 
}

</script>
