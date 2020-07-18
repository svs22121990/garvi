<?php
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
<!-- END PAGE TITLE -->
<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<style>
    div.dataTables_wrapper {
        width: 1500px;
        margin: 0 auto;
    }
</style>
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <?= form_open('Warehouse_Product_Summary/search',['id'=>'serch_date']); ?>
                <div class="form-group row" style="padding-top: 20px;" >
                    <div class="col-md-3">
					  <input type="text" class="form-control" name="daterange" placeholder="Select Date" autocomplete="off" value="<?php if($selected_date != 0)echo $selected_date; ?>" />
                    </div>
                    <div class="col-md-3">
                        <select name="type" id="type" class="form-control">
                            <option value="">Select Type</option>
                            <?php
                            if(!empty($types)) {
                                foreach ($types as $type) {
                                ?>
                                <option value="<?php echo $type->id; ?>" <?php if($type->id==$selected_type)echo "selected";?> ><?php echo $type->type; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="type2" id="type2" class="form-control">
                            <option value="">Select Type 2</option>
                            <?php
                            if(!empty($product_types)) {
                                foreach ($product_types as $type) {
                                ?>
                                <option value="<?php echo $type->id; ?>" <?php if($type->id == $selected_type2)echo "selected";?> ><?php echo $type->label; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="<?php site_url("Warehouse_Product_Summary/index/")?>" class="btn btn-danger">X</a>
                    </div>
                </div>
                <div class="form-group row" style="padding-top: 10px;" >
                    <div class="col-md-2">
                        <select name="cat" id="cat" class="form-control">
                            <option value="">Select Category</option>
                            <?php
                            if(!empty($category)) {
                                foreach ($category as $type) {
                                ?>
                                <option value="<?php echo $type->id; ?>" <?php if($type->id == $selected_cat)echo "selected";?> ><?php echo $type->title; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="color" id="color" class="form-control">
                            <option value="">Select Color</option>
                            <?php
                            if(!empty($colors)) {
                                foreach ($colors as $type) {
                                ?>
                                <option value="<?php echo $type->id; ?>" <?php if($type->id==$selected_color)echo "selected";?> ><?php echo $type->title; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="size" id="size" class="form-control">
                            <option value="">Select Size</option>
                            <?php
                            if(!empty($sizes)) {
                                foreach ($sizes as $type) {
                                ?>
                                <option value="<?php echo $type->id; ?>" <?php if($type->id == $selected_size)echo "selected";?> ><?php echo $type->title; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="fabric" id="fabric" class="form-control">
                            <option value="">Select Fabric</option>
                            <?php
                            if(!empty($fabrics)) {
                                foreach ($fabrics as $type) {
                                ?>
                                <option value="<?php echo $type->id; ?>" <?php if($type->id==$selected_fabric)echo "selected";?> ><?php echo $type->title; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="craft" id="craft" class="form-control">
                            <option value="">Select Craft</option>
                            <?php
                            if(!empty($crafts)) {
                                foreach ($crafts as $type) {
                                ?>
                                <option value="<?php echo $type->id; ?>" <?php if($type->id == $selected_craft)echo "selected";?> ><?php echo $type->title; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?= form_close(); ?>
                <form method="post" action="<?=site_url("Warehouse_Product_Summary/export_product_summary/$selected_date/$selected_type/$selected_type2/$selected_color/$selected_size/$selected_fabric/$selected_craft/$selected_cat")?>">

                        <div class="panel-heading">
                            <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                            <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                            <ul class="panel-controls">

                                <?php
                                // print_r($import);
                                if($import=='1'){ ?>
                                    <li><?php if($download=='1') { ?>
                                            <?php  echo  $download; ?>
                                        <?php } ?>
                                    </li>
                                <?php }if($importaction=='1') { ?>
                                    <li>
                                        <?php  echo  $importaction; ?>
                                    </li>
                                <?php } ?>
                                <?php if($exportPermission=='1'){?>
                                    <?php if(isset($dateinfo)){ ?>
                                        <li><a href="<?= base_url(); ?>index.php/Product_Summary/export_pdf_summary/<?= $dateinfo; ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li>
                                    <?php } else { ?>
<!--                                        <li><a href="--><?//= base_url(); ?><!--index.php/Product_Summary/export_pdf_summary" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li>-->
                                    <?php } ?>
                                    <li><?=$export; ?></li>
                                    <button type="submit" style="display: none" id="subbtn"></button>
                                <?php }?>
                                <?php if($addPermission=='1'){?>
<!--                                    <li><a href="--><?php //echo site_url("Products/create")?><!--" ><span class="fa fa-plus"></span></a></li>-->
                                <?php }?>

<!--                                <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>-->
                                <!-- <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                                <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-actions example_datatable2" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Purchase Date</th>
                                        <th>Name</th>
                                        <th>Received From</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Type 2</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Fabric</th>
                                        <th>Craft</th>
                                        <th>HSN Code</th>
                                        <th>Qty</th>
                                        <th>Avail. Qty</th>
                                        <th>Cost Price</th>
                                        <th>Total CP Amt</th>
                                        <th>GST %</th>
                                        <th>GST Amt</th>
                                        <th>Selling Price</th>
                                        <th>Total SP Amt</th>
                                        <th>Barcode Number</th>
                                        <th>AGE</th>
                                        <!--<th>Selling Price</th>
                                        <th>Total Selling Amt</th>-->
                                        <!-- <th>Purchase Date</th> -->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="13" style="text-align:right"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<button type="button" style="display: none;" id="damage-add" class="btn btn-info btn-lg" data-toggle="modal" data-target="#qtydamage">Open Modal</button>
<!-- Modal -->
<div id="qtydamage" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Damage Quantity</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="product_id" id="product_id">
                <div class="form-group">
                    <label>Quantity</label>
                    <input class="form-control" type="text" name="damage_qty" id="damage_qty" >
                </div>
                <button type="button" class="btn btn-primary" id="add-qty-button">Add</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">
            <form method="post" action="<?= site_url('Branches/changeStatus') ?>">
                <div class="modal-body" style="height: 100px;padding-top: 10%">
                    <center>
                        <input type="hidden" name="id" id="statusId" style="display: none;">
                        <span style="font-size: 16px">Are you sure to change the status?</span>
                    </center>
                </div>
                <div class="modal-footer" >
                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" action="<?= site_url('Products/delete') ?>">
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <center>
                        <input type="hidden" name="id" id="deleteId" style="display: none;">
                        <span style="font-size: 16px">
                          You want to delete this record.
                        <br>Are you sure? </span>
                    </center>
                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="transferData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" action="<?= site_url('Products/transferAssetsAction') ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Transfer against <strong> <span id="assetName"></span>  (Remaining Asset Stock  <span id="quantity_count_show"></span>)</strong></h4>
                </div>
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <input type="text" name="assettransid" id="assetId" style="display: none;">
                    <div class="col-md-6">
                        <label>Branch:</label> <span style="color: red">*</span><br>
                        <select class="form-control select" data-live-search="true" name="branch_id" id="branch_id">
                            <option value="">--Select Branch--</option>
                            <?php foreach($branch_data as $branch_dataRow) { ?>
                                <option value="<?php echo $branch_dataRow->id ?>"><?php echo $branch_dataRow->branch_title ?></option>
                            <?php }?>

                        </select><!-- <br> -->
                        <span style="color:red" id="branchError"></span>
                    </div>
                    <div class="col-md-6">
                        <label>Quantity:</label> <span style="color: red">*</span> <br>
                        <input type="text" name="quantity" id="quantity" class="form-control numbers" maxlength="10"><!-- <br> -->
                        <span style="color:red" id="QuantityError"></span>
                        <input type="hidden" name="quantity_count" id="quantity_count">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return savetransfer()">Submit</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel </button>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="uploadData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">
            <form method="post" action="<?= site_url('Products/import')?>" method="post" enctype="multipart/form-data" onsubmit="return checkXcel()">
                <div class="modal-header">
                    <span style="font-size:20px">Upload Products</span>
                </div>
                <div class="modal-body" style="padding-top: 3%">
                    <input type="file" name="excel_file" id="excel_file" class="form-control">
                    &nbsp;<span style="color:red" id="errorexcel_file"></span>&nbsp;
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="return validation();">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- END DEFAULT DATATABLE -->
<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.example_datatable2').DataTable({
            "ajax" : url,
            "columns": [
                { "data": "no" },
                { "data": "product_purchase_date" },
                { "data": "asset_name" },
                { "data": "name" },
                { "data": "title" },
                { "data": "type" },
                { "data": "productType" },
                { "data": "size" },
                { "data": "color" },
                { "data": "fabric" },
                { "data": "craft" },
                { "data": "hsn_code" },
                { "data": "quantity" },
                { "data": "available_qty" },
                {
                    "data": "price",
                    "render": function ( data, type, row, meta ) {
                        return 'Rs. '+data;
                    }
                },
                {
                    "data": "cost_total",
                    "render": function ( data, type, row, meta ) {
                        return 'Rs. '+data;
                    }
                },
                //{
                //    "data": "product_mrp",
                //    "render": function ( data, type, row, meta ) {
                //        return 'Rs. '+data;
                //    }
                //},
                { "data": "gst_percent" },
                {
                    "data": "gst",
                    "render": function ( data, type, row, meta ) {
                        return 'Rs. '+data;
                    }
                },
                {
                    "data": "product_mrp",
                    "render": function ( data, type, row, meta ) {
                        return 'Rs. '+data;
                    }
                },
                {
                    "data": "sp_total",
                    "render": function ( data, type, row, meta ) {
                        return 'Rs. '+data;
                    }
                },
                { "data": "barcode_number" },
                { "data": "time" },
                // { "data": "purchase_date" },
                //{ "data": "time" },
                //{ "data": "btn" }
            ],
            "ordering": false,

            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                total3 = api
                    .column(12, {filter: 'applied'})
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 12 ).footer() ).html(total3.toFixed(2));

                total3 = api
                    .column(13, {filter: 'applied'})
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 13 ).footer() ).html(total3.toFixed(2));

                total3 = api
                    .column(14, {filter: 'applied'})
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 14 ).footer() ).html('Rs. '+total3.toFixed(2));

                total2 = api
                    .column(15, {filter: 'applied'})
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 15 ).footer() ).html('Rs. '+total2.toFixed(2));

                total1 = api
                    .column(17, {filter: 'applied'})
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 17 ).footer() ).html('Rs. '+total1);
                total1 = api
                    .column(18, {filter: 'applied'})
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 18 ).footer() ).html('Rs. '+total1);
                total1 = api
                    .column(19, {filter: 'applied'})
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 19 ).footer() ).html('Rs. '+total1);

            }
        });
    });
</script>
<script>
    $(document).on('click','#add-qty-button',function(){
        var $product_id = $('#product_id').val();
        //alert($product_id);
        var table = $('.example_datatable').DataTable();
        var $qty = $('#damage_qty').val();
        $.post("<?= base_url('index.php/Product_Summary/damageadd'); ?>",{ product_id: $product_id,qty:$qty }, function(data, status){
            //alert("Data: " + data + "\nStatus: " + status);
            var myArr = JSON.parse(data)
            if(myArr.status){
                table.ajax.reload();
            }
            $(".close").click();
            alert(myArr.status+' : '+myArr.msg);
        });
    });

</script>
<script type="text/javascript" src="<?= base_url(); ?>assets/date_r_picker/moment.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/date_r_picker/daterangepicker.min.js"></script>

<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY'
            },
            opens: 'right'
        }, function(start, end, label) {
            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
    function addDamage($product_id){
        $('#product_id').val($product_id);
        $("#damage-add").click();
    }
</script>
<script type="text/javascript">
    function checkStatus(id)
    {
        $("#statusId").val(id);
        $("#deleteId").val(id);
    }
</script>


<script type="text/javascript">
    function savetransfer()
    {
        var branch_id = $("#branch_id").val();
        var quantity = $("#quantity").val();
        var quantity_count = $("#quantity_count").val();
        //alert(quantity_count);return false;

        if(branch_id == "")
        {
            $("#branchError").fadeIn().html("Required");
            setTimeout(function(){$("#branchError").fadeOut();},8000);
            $("#branch_id").focus();
            return false;
        }

        if($.trim(quantity) == "")
        {
            $("#QuantityError").fadeIn().html("Required");
            setTimeout(function(){$("#QuantityError").fadeOut();},8000);
            $("#quantity").focus();
            return false;
        }
        if(parseInt(quantity) > parseInt(quantity_count) || quantity==0)
        {
            $("#QuantityError").fadeIn().html("Quantity should be less than total quantity and not equal to zero");
            setTimeout(function(){$("#QuantityError").fadeOut();},8000);
            $("#quantity").focus();
            return false;
        }
    }
</script>


<script type="text/javascript">
    function getassetvalue(rowid)
    {
        $.ajax({
            type: "POST",
            url: "<?= site_url('Products/getassetdetail'); ?>",
            data: {id:rowid},
            cache: false,
            success: function(result)
            {
                var obj = $.parseJSON(result);
                if(obj.success == '1')
                {
                    $("#assetId").val(obj.asset_id);
                    $("#assetName").html(obj.asset_name);
                    $("#quantity_count").val(obj.rowassetTransfers);
                    $("#quantity_count_show").html(obj.rowassetTransfers);
                }
            }
        });
    }
</script>


<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<script type="text/javascript">

    function saveData()
    {
        var name = $("#name").val();

        if($.trim(name) == "")
        {
            $("#nameError").fadeIn().html("Please enter Asset type name");
            setTimeout(function(){$("#nameError").fadeOut();},4000);
            $("#name").focus();
            return false;
        }
        var datastring  = "name="+name;
        $.ajax({
            type : "post",
            url : "<?php echo site_url('Product_type/addData'); ?>",
            data : datastring,
            success : function(response)
            {
                if(response == 1)
                {
                    $("#nameError").fadeIn().html("Asset Type name already exist");
                    setTimeout(function(){$("#nameError").fadeOut();},8000);
                }
                else
                {
                    $(".close").click();
                    $("#successCountryEntry").fadeIn().html("Asset Type has been Added successfully");
                    setTimeout(function(){ window.location.reload(); },100);
                }
            }
        });
    }
</script>

<script type="text/javascript">

    function getEditvalue(rowid)
    {
        $("#updateId").val(rowid);

        $.ajax({
            type: "POST",
            url: "<?= site_url('Product_type/getUpdateName'); ?>",
            data: {id:rowid},
            cache: false,
            success: function(result)
            {
                $("#titleName").val($.trim(result));
            }
        });
    }

    function updateData()
    {
        var name = $("#titleName").val();
        var updateId = $("#updateId").val();


        if($.trim(name) == "")
        {
            $("#titleError").fadeIn().html("Please enter Asset type");
            setTimeout(function(){$("#titleError").fadeOut();},8000);
            $("#titleName").focus();
            return false;
        }

        var datastring  = "name="+name+"&id="+updateId;

        $.ajax({
            type : "post",
            url : "<?php echo site_url('Product_type/updateData') ?>",
            data : datastring,
            success : function(response)
            {
                if(response == 1)
                {
                    $("#titleError").fadeIn().html("Asset type name already exist");
                    setTimeout(function(){$("#titleError").fadeOut();},8000);
                }
                else
                {
                    $(".close").click();
                    $("#successCountryEntry").fadeIn().html("Asset type has been updated successfully");
                    setTimeout(function(){ window.location.reload(); },1000);
                }

            }
        });
    }
    function clickSubmit(){
        $('#subbtn').click();
    }
</script>


<script type="text/javascript">
    setTimeout(function(){$("#msghide").html("&nbsp;");},5000)
</script>




