<?php  $this->load->view('common/header');
$this->load->view('common/left_panel'); ?>
<?php $created_by = $_SESSION[SESSION_NAME]['id']; ?> <!-- START BREADCRUMB -->
<?= $breadcrumbs ?> <!-- END BREADCRUMB --> <!-- PAGE TITLE -->
<div class="page-title">                         <!-- <h3 class="panel-title"><?= $heading?></h3> --> </div>  <!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" method="post" action="<?php echo $action;?>"  enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong><?= $heading ?></h3>
                        <ul class="panel-controls">
                            <li><a href="<?= site_url('Warehouse/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body" style="overflow-x: auto;">
                        <div class="row">
                            <div class="col-md-12" style="padding: 0;">

                                <!-- <div class="col-md-3">
                                  <div class="form-group">
                                    <label class="col-md-11"> Purchase Date <span style="color: red">*</span> <span  id="purchase_date_error" style="color: red"></span></label>
                                    <div class="col-md-11">
                                      <input type="text" name="purchase_date" id="purchase_date" class="form-control datepicker" placeholder="Purchase Date">
                                    </div>
                                  </div>
                                </div> -->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> DN Number <span style="color: red">*</span> <span  id="dn_number_error" style="color: red"></span></label>
                                        <div class="col-md-11">
                                            <input type="text" name="dn_number" id="dn_number" value="<?php echo $products->dn_number; ?>" class="form-control" placeholder="dn Number">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11">  Date <span style="color: red">*</span> <span  id="warehouse_date_error" style="color: red"></span></label>
                                        <div class="col-md-11">
                                            <input type="text" name="warehouse_date" id="warehouse_date" value="<?php echo $products->warehouse_date; ?>" class="form-control datepicker" placeholder=" Date">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-11"> Received From <span style="color: red">*</span> <span  id="received_from_error" style="color: red"></span></label>
                                        <div class="col-md-11">
                                            <select name="received_from" id="received_from" class="form-control">
                                                <option value="">--Select User-</option>
                                                <?php foreach($users as $user) { ?>
                                                    <option value="<?php echo $user->id?>"<?php if($products->received_from==$user->id)echo "selected";?>><?php echo $user->name?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-12" style="padding: 0;">
                            <div style="width: 1600px; margin: 0 auto;">
                                <table class="table table-striped table-bordered" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th> Purchase Date <span style="color: red">*</span><span style="color: red"></span></th>
                                        <th> Category  <a href="#myModalCategory" class="pull-right" data-toggle="modal" data-target="" title="Add new Category"></a><span style="color: red">*</span> <span  id="category_id_error" style="color: red"></span></th>
                                        <th>Product Type <a href="#myModalAssetType" class="pull-right" data-toggle="modal" data-target="" title="Add new Product Type"></a><span style="color: red">*</span> <span  id="asset_type_id_error" style="color: red"></span></th>
                                        <th>Product Type 2 <span style="color: red">*</span> </th>
                                        <th> Name <span style="color: red">*</span><span style="color: red" id="asset_name_error"></span></th>
                                        <th> Qty <span style="color: red">*</span><span style="color: red" id="error_quantity"></span></th>
                                        <th> Color<span style="color: red">*</span> </th>
                                        <th> Size <span style="color: red">*</span> </th>
                                        <th> Fabric <span style="color: red">*</span> </th>
                                        <th> Craft<span style="color: red">*</span> </th>
                                        <th> Cost Price  <span style="color: red">*</span><span style="color: red"id="error_price"></span></th>
                                        <th> Total CP Amt  <span style="color: red">*</span></th>
                                        <th> GST % <span style="color: red">*</span><span  id="error_gst_percent"></span></th>
                                        <th> HSN <span style="color: red">*</span><span id="error_hsn"></span></th>
                                        <th> Markup1 %<span style="color: red">*</span><span style="color: red" id="lf_no_error"></span></th>
                                        <th> Markup2 %<span style="color: red">*</span><span style="color: red" id="lf_no_error"></span></th>
                                        <th> Markup Amt 1<span style="color: red">*</span></th>
                                        <th> Markup Amt 2<span style="color: red">*</span></th>
                                        <th> Selling Price 1</th>
                                        <th> Selling Price 2</th>
                                        <th>Total SP Amt 1</th>
                                        <th>Total SP Amt 2</th>
                                        <!--                                        <th class="text-center"> <a  href="javascript:void(0)" class="btn  btn-sm btn-info"  onclick="addrow()" ><i class="fa fa-plus"></i></a></th>-->
                                    </tr>
                                    </thead>
                                    <tbody id="professorTableBody">
                                    <?php for ($i=0; $i<count($getAssetData); $i++) { ?>
                                    <input type="hidden" name="detail_id[]" value="<?php echo $getAssetData[$i]->id; ?>" >
                                    <tr class="trRow">
                                        <td>
                                            <input type="text" name="product_purchase_date[]" id="product_purchase_date<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->purchase_date; ?>" class="form-control datepicker" placeholder="Purchase Date">
                                        </td>
                                        <td>
                                            <select class="form-control" name="category_id[]" id="category_id<?php echo $i; ?>" onchange="getGST(this.value,$(this).closest('tr').index());">
                                                <option value="">--Select Product Category--</option>
                                                <?php foreach($category_data as $category_dataRow) { ?>
                                                    <option value="<?php echo $category_dataRow->id?>"<?php if($getAssetData[$i]->category_id==$category_dataRow->id)echo "selected";?>><?php echo $category_dataRow->title?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="asset_type_id[]" id="asset_type_id<?php echo $i; ?>">
                                                <option value="">--Select Product Type Name--</option>
                                                <?php foreach($asset_type_data as $asset_type_dataRow) { ?>
                                                    <option value="<?php echo $asset_type_dataRow->id?>"<?php if($getAssetData[$i]->asset_type_id==$asset_type_dataRow->id)echo "selected";?>><?php echo $asset_type_dataRow->type ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="asset_type_2_id[]" id="asset_type_2_id<?php echo $i; ?>">
                                                <option value="">--Select Product Type 2 Name--</option>
                                                <?php foreach($productTypes as $asset_type_dataRow) { ?>
                                                    <option value="<?=$asset_type_dataRow->id?>"<?php if($getAssetData[$i]->product_type_id==$asset_type_dataRow->id)echo "selected";?>><?=$asset_type_dataRow->label?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="asset_name[]" id="asset_name<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->asset_name; ?>" placeholder="Enter Product Name" autocomplete="off" onchange="checkassetduplicate(this.value,$(this).closest('tr').index())">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control qty" name="quantity[]" id="quantity<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->quantity; ?>" placeholder="Enter Quantity" autocomplete="off">
                                        </td>
                                        <td>
                                            <select class="form-control" name="color_id[]" id="color_id<?php echo $i; ?>">
                                                <option value="">--Select Product Color--</option>
                                                <?php foreach($color as $asset_type_dataRow) { ?>
                                                    <option value="<?=$asset_type_dataRow->id?>"<?php if($getAssetData[$i]->color_id==$asset_type_dataRow->id)echo "selected";?>><?=$asset_type_dataRow->title?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="size_id[]" id="size_id<?php echo $i; ?>">
                                                <option value="">--Select Product Size--</option>
                                                <?php foreach($size as $asset_type_dataRow) { ?>
                                                    <option value="<?=$asset_type_dataRow->id?>"<?php if($getAssetData[$i]->size_id==$asset_type_dataRow->id)echo "selected";?>><?=$asset_type_dataRow->title?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="fabric_id[]" id="fabric_id<?php echo $i; ?>">
                                                <option value="">--Select Product Fabric--</option>
                                                <?php foreach($fabric as $asset_type_dataRow) { ?>
                                                    <option value="<?=$asset_type_dataRow->id?>"<?php if($getAssetData[$i]->fabric_id==$asset_type_dataRow->id)echo "selected";?>><?=$asset_type_dataRow->title?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="craft_id[]" id="craft_id<?php echo $i; ?>">
                                                <option value="">--Select Product Craft--</option>
                                                <?php foreach($craft as $asset_type_dataRow) { ?>
                                                    <option value="<?=$asset_type_dataRow->id?>"<?php if($getAssetData[$i]->craft_id==$asset_type_dataRow->id)echo "selected";?>><?=$asset_type_dataRow->title?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control price" name="price[]" id="price<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->price; ?>" placeholder="Enter Product Price" autocomplete="off" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control cost_total" name="cost_total[]" id="cost_total<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->price; ?>" readonly="readonly" autocomplete="off" >
                                        </td>

                                        <td>
                                            <input type="text" class="form-control gstPercent" name="gst_percent[]" id="gst_percent<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->gst_percent; ?>" readonly="readonly" placeholder="Enter GST %" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="hsn[]" id="hsn<?php echo $i; ?>"  value="<?php echo $getAssetData[$i]->hsn; ?>" readonly="readonly" autocomplete="off" placeholder="Enter HSN" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control markup" name="markup[]" id="markup<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->markup_percent; ?>" readonly="readonly" placeholder="Enter Markup" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control markup_2" name="markup_2[]" id="markup_2<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->markup_percent2; ?>" readonly="readonly" placeholder="Enter Markup" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control markup_amt" name="markup_amt[]" id="markup_amt1" readonly="readonly" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control markup_amt_2" name="markup_amt_2[]" id="markup_amt21" readonly="readonly" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control sp" name="sp[]" id="sp<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->product_mrp; ?>" readonly="readonly" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control sp_2" name="sp_2[]" id="sp21<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->product_mrp_2; ?>" readonly="readonly" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control sp_total" name="sp_total[]" value="<?php echo $getAssetData[$i]->sp_total; ?>" id="sp_total<?php echo $i; ?>" autocomplete="off" readonly="readonly">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control sp_total_2" name="sp_total_2[]" id="sp_total21<?php echo $i; ?>" value="<?php echo $getAssetData[$i]->sp_total_2; ?>" autocomplete="off" onkeypress="return only_number(event)" readonly="readonly">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
<!--                                        total CP amount-->
                                        <th colspan="11" >&nbsp;<span class="pull-right">Total</span></th>
                                        <th colspan=""><input type="text" class="form-control" id="costTotal" readonly="readonly" value="0"></th>

                                        <th colspan="4" >&nbsp;<span class="pull-right"></span></th>
                                        <th colspan=""><input type="text" class="form-control" name="gtotal" id="grandTotal" readonly="readonly" value="0"></th>
                                        <th colspan=""><input type="text" class="form-control" name="" id="grand_2Total" readonly="readonly" value="0"></th>
                                        <!--                                        total SP Amt 1-->
                                        <th colspan="2" >&nbsp;<span class="pull-right"></span></th>
                                        <th><input type="text" class="form-control" id="spTotal" readonly="readonly" value="0"></th>

                                        <!--                                        total SP Amt2-->
                                        <th><input type="text" class="form-control"  id="spTotal_2" readonly="readonly" value="0"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="12"><span class="pull-right">Total SGST Amount</span></th>
                                        <th colspan="3">
                                            <input type="text" class="form-control" id="totalSGST" readonly="readonly" value="0">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="12"><span class="pull-right">Total CGST Amount</span></th>
                                        <th colspan="3">
                                            <input type="text" class="form-control" id="totalCGST" readonly="readonly" value="0">
                                        </th>
                                    </tr>
<!--                                    <tr>-->
<!--                                        <th colspan="14" >&nbsp;<span class="pull-right">Total Markup Amount 1</span></th>-->
<!--                                        <th colspan="3"><input type="text" class="form-control" name="gtotal" id="grandTotal" readonly="readonly" value="0"></th>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <th colspan="14" >&nbsp;<span class="pull-right">Total Markup Amount 2</span></th>-->
<!--                                        <th colspan="3"><input type="text" class="form-control" name="" id="grand_2Total" readonly="readonly" value="0"></th>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <th colspan="14" >&nbsp;<span class="pull-right">Total CP Amt. </span></th>-->
<!--                                        <th colspan="3">-->
<!--                                            <input type="text" class="form-control" id="costTotal" readonly="readonly" value="0">-->
<!--                                        </th>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <th colspan="14" >&nbsp;<span class="pull-right">Total SP Amt.1 </span></th>-->
<!--                                        <th colspan="3">-->
<!--                                            <input type="text" class="form-control" id="spTotal" readonly="readonly" value="0">-->
<!--                                        </th>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <th colspan="14" >&nbsp;<span class="pull-right">Total SP Amt.2 </span></th>-->
<!--                                        <th colspan="3">-->
<!--                                            <input type="text" class="form-control" id="spTotal_2" readonly="readonly" value="0">-->
<!--                                        </th>-->
<!--                                    </tr>-->
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" type="submit" onclick="return validateinfo()"><?= $button;?></button>
                </div>
        </div>
        </form>

    </div>
</div>

</div>
<!-- END PAGE CONTENT WRAPPER -->

<!--add new unit-->
<div class="modal fade" id="myModalunit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="closeUnit">&times;</button>
                <h4 class="modal-title">Add New Unit Type <span id="successEntry" style="color:green"></span></h4>
            </div>
            <div class="modal-body">
                <form method="post" id="unit" onsubmit="return saveData()">
                    <label>Unit Type Name:</label> <span style="color:red">*</span> <span id="nameError" style="color:red"></span><br>
                    <input type="text" name="name"  class="form-control" id="name" value="" autocomplete="off" size="35"/> &nbsp;


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="return saveData()">Submit</button>
                <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!--add new unit-->

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
                        <input type="text" class="form-control" name="category_title" id="category_title" placeholder="Category Name">
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

                    <!-- <label>Is saleable:</label> <span style="color:red">*</span> <span id="saleableError" style="color:red"></span><br>
                    <select class="form-control" name="saleable" id="saleable">
                      <option value="">--Select Type--</option>
                      <option value="Yes">Yes</option>
                      <option value="NO">No</option>
                    </select> <br>

                    <label>Is barcode:</label> <span style="color:red">*</span> <span id="barcodeError" style="color:red"></span>
                    <select class="form-control" name="barcode" id="barcode">
                      <option value="">--Select Type--</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select> -->


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



<?php $this->load->view('common/footer');?>
<script>
    $(document).on('keyup','.price',function(){
        price();
    });
    $(document).on('keyup','.qty',function(){
        price();
    });
    function price()
    {
        var markup = 0;
        var markup2 = 0;
        var mult = 0;
        var totalGST = 0;
        var finalTotal = 0;
        $("tr.trRow").each(function () {
            // get the values from this row:
            var $val1 = $('.price', this).val();
            var $val2 = $('.qty', this).val();
            var $total = ($val1 * 1) * ($val2 * 1);
            // set total for the row
            $('.cost_total', this).val($total);

            mult += $total;

            var $markup = $('.markup', this).val();
            $('.markup_amt', this).val($total * (($markup * 1)/100));

            var $markup2 = $('.markup_2', this).val();
            $('.markup_amt_2', this).val($total * (($markup2 * 1)/100));

            var $sp = ($val1 * 1) + (($val1 * 1) * (($markup * 1)/100));
            $('.sp', this).val($sp);

            var $sp_2 = ($val1 * 1) + (($val1 * 1) * (($markup2 * 1)/100));
            $('.sp_2', this).val($sp_2);

            var $sp_total_2 = ($sp_2 * 1) * ($val2 * 1);
            $('.sp_total_2', this).val($sp_total_2);
            markup2+= ($markup2 / 100) * $total;

            var $sp_total = ($sp * 1) * ($val2 * 1);
            $('.sp_total', this).val($sp_total);
            markup+= ($markup / 100) * $total;
            
            var $gstPercent = $('.gstPercent', this).val();
            totalGST+= ($gstPercent / 100) * $total;
        });
        $("#grandTotal").val(markup);
        $("#grand_2Total").val(markup2);
        $("#totalCGST").val(totalGST / 2);
        $("#totalSGST").val(totalGST / 2);
        $("#costTotal").val(mult);
        $("#spTotal").val(mult+markup);
        $("#spTotal_2").val(mult+markup2);
    }
</script>
<script>
    $(document).ready(function(e){
        price();
        src = '<?= site_url('Products/getSubcategory'); ?>';
        $("#subcategory_id").autocomplete({
            appendTo: "#searchbox",
            source: function(request, response) {
                $("#check").val('');
                $(".ui-autocomplete").html('<img src="<?= base_url('assets/default.gif'); ?>" alt="">');
                $.getJSON(src, {search : request.term},
                    response);
            },
            select: function(event, ui) {$("#check").val(ui.item.sub_cat_title); }
        });
    });
</script>

<!-- <script type="text/javascript">
  function checkSubcat()
  {
      var subcategory_id = $("#subcategory_id").val();
      var datastring  = "subcategory_id="+subcategory_id;
      $.ajax({
        type : "post",
        url : "<?php echo site_url('Assets/checkSubcat'); ?>",
        data : datastring,
        success : function(response)
        {
          if(response == 1)
          {
            $("#subcategory_id").val("");
            $("#subcat_error").fadeIn().html("Invalid");
            setTimeout(function(){$("#subcat_error").fadeOut();},5000);
          }
          else
          {
             //alert("fdh");return false;
          }
        }
      });
  }
</script> -->

<script type="text/javascript">
    function onlyImage(sr){
        sr=sr+1;
        var file=$('#photo'+sr).val();
        var filetype = file.split(".");
        ext = filetype[filetype.length-1];
        //alert(ext);
        if((ext!='png') && (ext!='jpg') && (ext!='jpeg') && (ext!='PNG') && (ext!='JPG'))
        {
            $("#error_photo").fadeIn().html("Invalid format").css('color','red');
            setTimeout(function(){$("#error_photo").fadeOut();},4000);
            $("#photo"+sr).focus();
            $("#photo"+sr).val('');
            return false;
        }
    }

    function addrow() {
        var y = document.getElementById('professorTableBody');
        var new_row = y.rows[0].cloneNode(true);
        var len = y.rows.length;
        var _date = new Date();

        var inp15 = new_row.cells[0].getElementsByTagName('input')[0];
        inp15.value = ''; // _date.getDate() + '/' + _date.getMonth() + '/' + _date.getFullYear();
        inp15.id = 'product_purchase_date'+(len+1);
        inp15.class = '';
        //inp15.classList.remove('datepicker', 'hasDatepicker');

        var inp12 = new_row.cells[1].getElementsByTagName('select')[0];
        inp12.value = '';
        inp12.id = 'category_id'+(len+1);

        var inp13 = new_row.cells[2].getElementsByTagName('select')[0];
        inp13.value = '';
        inp13.id = 'asset_type_id'+(len+1);

        var inp16 = new_row.cells[3].getElementsByTagName('select')[0];
        inp16.value = '';
        inp16.id = 'asset_type_2_id'+(len+1);

        var inp1 = new_row.cells[4].getElementsByTagName('input')[0];
        inp1.value = '';
        inp1.id = 'sku'+(len+1);

        var inp2 = new_row.cells[5].getElementsByTagName('input')[0];
        inp2.value = '';
        inp2.id = 'asset_name'+(len+1);

        var inp3 = new_row.cells[6].getElementsByTagName('input')[0];
        inp3.value = '';
        inp3.id = 'quantity'+(len+1);

        var inp4 = new_row.cells[7].getElementsByTagName('input')[0];
        inp4.value = '';
        inp4.id = 'product_mrp'+(len+1);

        var inp5 = new_row.cells[8].getElementsByTagName('input')[0];
        inp5.value = '';
        inp5.id = 'total'+(len+1);
        inp5.class = 'multTotal';


        var inp6 = new_row.cells[9].getElementsByTagName('input')[0];
        inp6.value = '';
        inp6.id = 'gst_percent'+(len+1);


        var inp7 = new_row.cells[10].getElementsByTagName('input')[0];
        inp7.value = '';
        inp7.id = 'hsn'+(len+1);

        var inp8 = new_row.cells[11].getElementsByTagName('input')[0];
        inp8.value = '';
        inp8.id = 'lf_no'+(len+1);

        $('.sectionA').val(len+1);

        y.appendChild(new_row);
        $('#'+inp15.id).datepicker();
    }


    function getGST(val,len) {
        var site_url = $('#site_url').val();
        var dataString = "category_id="+val;
        var url = site_url+"/Products/getGST";
        $.post(url, dataString, function(returndata){
            //alert(returndata);
            var obj = jQuery.parseJSON(returndata);
            $('#gst_percent'+(len-1)).val(obj.gst_percent);
            $('#hsn'+(len-1)).val(obj.hsn);
            $('#markup'+(len-1)).val(obj.markup);
            $('#markup_2'+(len-1)).val(obj.markup_2);
        });
        price();
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


    function checkassetduplicate(val,sr)
    {
        var datastring  = "astName="+val;
        $.ajax({
            type : "post",
            url : "<?php echo site_url('Products/chkAssetName'); ?>",
            data : datastring,
            success : function(response)
            {
                if(response == 1)
                {
                    sr=sr+1;
                    //$("#asset_name"+sr).val("");
                    $("#error_asset_name").fadeIn().html("Product name already exist").css('color','red');
                    setTimeout(function(){$("#error_asset_name").fadeOut();},5000);
                }

            }
        });
    }
</script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>
<script type="text/javascript">
    function saveData()
    {
        //alert();
        var name = $("#name").val();
        /*var name1 = /^[a-zA-Z -]+$/;*/

        if($.trim(name) == "")
        {
            $("#nameError").fadeIn().html("Please enter Unit type name");
            setTimeout(function(){$("#nameError").fadeOut();},4000);
            $("#name").focus();
            return false;
        }


        var datastring  = "name="+name;
        $.ajax({
            type : "post",
            url : "<?php echo site_url('Products/addDataUnit'); ?>",
            data : datastring,
            success : function(response)
            {
                //alert(response);return false;
                if(response == 1)
                {
                    $("#nameError").fadeIn().html("Unit Type name already exist");
                    setTimeout(function(){$("#nameError").fadeOut();},8000);
                }
                else
                {
                    $("#closeUnit").click();
                    $("#unit_id").append(response).selectpicker('refresh');
                    /*$("#namecat").val("");*/
                    // $("#successCountryEntry").fadeIn().html("Unit Type has been Added successfully");
                }

            }
        });
    }


    function saveDataAssetType()
    {


        var nametype = $("#nametype").val();
        /*var salable = $("#saleable").val();
         var barcode = $("#barcode").val();*/
        /*var name1 = /^[a-zA-Z -]+$/;*/

        if($.trim(nametype) == "")
        {
            $("#nameErrorAsset").fadeIn().html("Please enter Product type name");
            setTimeout(function(){$("#nameErrorAsset").fadeOut();},4000);
            $("#nametype").focus();
            return false;
        }

        /*if(salable == "")
         {
         $("#saleableError").fadeIn().html("Please select type");
         setTimeout(function(){$("#saleableError").fadeOut();},4000);
         $("#salable").focus();
         return false;
         }
         if(barcode == "")
         {
         $("#barcodeError").fadeIn().html("Please select type");
         setTimeout(function(){$("#barcodeError").fadeOut();},4000);
         $("#barcode").focus();
         return false;
         }*/

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
        //alert();
        var nametype = $("#nametype").val("");
        /*var salable = $("#saleable").val("");
         var barcode = $("#barcode").val("");*/
        var name = $("#name").val("");
    }
</script>
<script type="text/javascript">
    function warrantydata(type)
    {
        if(type=='Yes')
        {
            $("#warrantyspan").show();
        }
        else if(type=='No')
        {
            $("#warrantyspan").hide();
        }
    }

    function stockData(type)
    {
        if(type=='Yes')
        {
            $("#quantityStock").show();
        }
        else if(type=='No')
        {
            $("#quantityStock").hide();
            $("#quantity").val("");

        }
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
                $('#subcategory_id').html(returndata).selectpicker('refresh');
            }
        });
    }
</script>



<script type="text/javascript">
    function validateinfo() {
        //alert("hi");
        // var purchase_date = $('#purchase_date').val();
        var CurrentMonth = '<?= date('m/d/Y'); ?>';//date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear();
        console.log(CurrentMonth);
        //var SelectedDate =
        //var purchase_date = $('#purchase_date').val();
        var dn_number = $('#dn_number').val();
        var warehouse_date = $('#warehouse_date').val();
        var received_from = $('#received_from').val();
        var sectionA = $('.sectionA').val();

        // if(purchase_date=='') {
        //   $("#purchase_date_error").html("Please select Purchase Date").fadeIn();
        //   setTimeout(function(){$("#purchase_date_error").fadeOut()},5000);
        //   return false;
        // }else if( CurrentMonth >  purchase_date ){
        //   $("#purchase_date_error").html("Please select valid Purchase Date").fadeIn();
        //   setTimeout(function(){$("#purchase_date_error").fadeOut()},5000);
        //   return false;
        // } else
        if(dn_number=='') {
            $("#dn_number_error").html("Please enter Bill No").fadeIn();
            setTimeout(function(){$("#dn_number_error").fadeOut()},5000);
            return false;
        } else if(warehouse_date=='') {
            $("#warehouse_date_error").html("Please select Bill Date").fadeIn();
            setTimeout(function(){$("#warehouse_date_error").fadeOut()},5000);
            return false;
        }else if(received_from=='') {
            $("#received_from_error").html("Please select Received From").fadeIn();
            setTimeout(function(){$("#received_from_error").fadeOut()},5000);
            return false;
        } else if(sectionA==1) {
            //alert("1");
            var category_id = $('#category_id1').val();
            var asset_type_id = $('#asset_type_id1').val();
            var sku = $('#sku1').val();
            var product_name = $('#asset_name1').val();
            var quantity = $('#quantity1').val();
            var product_mrp = $('#product_mrp1').val();
            var gst_percent = $('#gst_percent1').val();
            var hsn = $('#hsn1').val();
            var lf_no = $('#lf_no1').val();

            /*alert(category_id);
             alert(asset_type_id);
             alert(sku);
             alert(product_name);
             alert(quantity);
             alert(product_mrp);
             alert(gst_percent);
             alert(hsn);
             alert(lf_no);*/
            if(category_id=='') {
                $("#category_id_error").html("Please select category").fadeIn();
                setTimeout(function(){$("#category_id_error").fadeOut()},5000);
                return false;
            }else if(asset_type_id=='') {
                $("#asset_type_id_error").html("Please select product type").fadeIn();
                setTimeout(function(){$("#asset_type_id_error").fadeOut()},5000);
                return false;
            }else if(product_name=='') {
                $("#asset_name_error").html("Please enter product name").fadeIn();
                setTimeout(function(){$("#asset_name_error").fadeOut()},5000);
                return false;
            }else if(quantity=='') {
                $("#error_quantity").html("Please enter quantity").fadeIn();
                setTimeout(function(){$("#error_quantity").fadeOut()},5000);
                return false;
            }else if(product_mrp=='') {
                $("#error_price").html("Please enter product price").fadeIn();
                setTimeout(function(){$("#error_price").fadeOut()},5000);
                return false;
            }else if(gst_percent=='') {
                $("#gst_percent_error").html("Please enter GST %").fadeIn();
                setTimeout(function(){$("#gst_percent_error").fadeOut()},5000);
                return false;
            }else if(hsn=='') {
                $("#hsn_error").html("Please enter HSN").fadeIn();
                setTimeout(function(){$("#hsn_error").fadeOut()},5000);
                return false;
            }else if(lf_no=='') {
                $("#lf_no_error").html("Please enter LF No.").fadeIn();
                setTimeout(function(){$("#lf_no_error").fadeOut()},5000);
                return false;
            }

        } else if(sectionA > 1) {
            /*alert(sectionA);*/
            for(var i = 1; i <= sectionA; i++) {

                var category_id = $('#category_id'+i).val();
                var asset_type_id = $('#asset_type_id'+i).val();
                var sku = $('#sku'+i).val();
                var product_name = $('#asset_name'+i).val();
                var quantity = $('#quantity'+i).val();
                var product_mrp = $('#product_mrp'+i).val();
                var gst_percent = $('#gst_percent'+i).val();
                var hsn = $('#hsn'+i).val();
                var lf_no = $('#lf_no'+i).val();

                /*alert(category_id);
                 alert(asset_type_id);
                 alert(sku);
                 alert(product_name);
                 alert(quantity);
                 alert(product_mrp);
                 alert(gst_percent);
                 alert(hsn);
                 alert(lf_no);  */

                if(category_id=='') {
                    $("#category_id_error").html("Please select category").fadeIn();
                    setTimeout(function(){$("#category_id_error").fadeOut()},5000);
                    return false;
                }else if(asset_type_id=='') {
                    $("#asset_type_id_error").html("Please select product type").fadeIn();
                    setTimeout(function(){$("#asset_type_id_error").fadeOut()},5000);
                    return false;
                }else if(product_name=='') {
                    $("#asset_name_error").html("Please enter Product Name").fadeIn();
                    setTimeout(function(){$("#asset_name_error").fadeOut()},5000);
                    return false;
                }else if(quantity=='') {
                    $("#error_quantity").html("Please enter quantity").fadeIn();
                    setTimeout(function(){$("#error_quantity").fadeOut()},5000);
                    return false;
                }else if(product_mrp=='') {
                    $("#error_price").html("Please enter product price").fadeIn();
                    setTimeout(function(){$("#error_price").fadeOut()},5000);
                    return false;
                }else if(gst_percent=='') {
                    $("#gst_percent_error").html("Please enter GST %").fadeIn();
                    setTimeout(function(){$("#gst_percent_error").fadeOut()},5000);
                    return false;
                }else if(hsn=='') {
                    $("#hsn_error").html("Please enter HSN").fadeIn();
                    setTimeout(function(){$("#hsn_error").fadeOut()},5000);
                    return false;
                }else if(lf_no=='') {
                    $("#lf_no_error").html("Please enter LF No.").fadeIn();
                    setTimeout(function(){$("#lf_no_error").fadeOut()},5000);
                    return false;
                }
            }
        }
    }
</script>


<script type="text/javascript">
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
                $("#error_subcategory_title").fadeIn().html("Subcategory already exist");
                setTimeout(function(){$("#error_subcategory_title").fadeOut();},3000);
                $("#subcategory_title").focus();
                return false;
            }
            else
            {
                $("#category_id_title").val('');
                $("#subcategory_title").val('');
                $("#myModalSubcategory").modal("hide");
                $('#subcategory_id').html(returndata);
                $("#successEntry").fadeIn().html("<span> Subcategory created successfully</span>");
                setTimeout(function(){$("#successEntry").fadeOut();},3000);
            }
        });
    }

</script>




