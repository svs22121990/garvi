<?php
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<style type="text/css">
    div.scrollable {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: auto;
    }
</style>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">
    <?php  ?>
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">Warehouse Details</h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                        <!--<li><a href="<?php //site_url("Warehouse/export_pdf/$id") ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li>
                        <li><a href="<?php //site_url("Warehouse/export_summary/$id") ?>"><span title="Export" class="fa fa-file-excel-o"></span></a></li>-->
                        <li><a href="<?= site_url('Warehouse/index') ?>"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body panel-body-table">
                    <div class="row" style="padding: 10px; font-size: 1.3em;">
                        <div class="col-md-4">
                            DN No.: <strong><?php echo !empty($getAssetData) ? $getAssetData[0]->dn_number : ""; ?></strong>
                        </div>
                        <div class="col-md-4">
                             Date: <strong><?php echo !empty($getAssetData) ? $getAssetData[0]->warehouse_date : ""; ?></strong>
                        </div>
                        <div class="col-md-4">
                            Received From: <strong><?php echo !empty($getAssetData) ? $getAssetData[0]->employee_name : ""; ?></strong>
                        </div>
                    </div>
                    <div class="row">

                        <div class="table-responsive">
                            <?php
                            if (!empty($getAssetData)) {
                                ?>
                                <table class="table table-bordered table-striped table-actions">
                                    <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Product Name</th>
                                        <th>Product Type Name</th>
                                        <th>Product Type Name 2</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Fabric</th>
                                        <th>Craft</th>
                                        <th>Quantity</th>
                                        <th>Product Price</th>
                                        <th>GST %</th>
                                        <th>HSN</th>
                                        <th>Markup %</th>
                                        <th>Total Cost Amount</th>
                                        <th>SP</th>
                                        <th>Total SP Amount</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $qty = 0;
                                    $product_mrp = 0;
                                    $total = 0;
                                    $totalGST = 0;
                                    $finalTotal = 0;
                                    foreach ($getAssetData as $getData) {
                                        ?>
                                        <tr>
                                            <td><?php echo $getData->title ?></td>
                                            <td><?php echo $getData->asset_name ?></td>
                                            <td><?php echo $getData->type ?></td>
                                            <td><?php echo $getData->label ?></td>
                                            <td><?php echo $getData->color ?></td>
                                            <td><?php echo $getData->size ?></td>
                                            <td><?php echo $getData->fabric ?></td>
                                            <td><?php echo $getData->craft ?></td>
                                            <td><?php echo $getData->quantity ?></td>
                                            <td><?php echo "Rs. " . number_format($getData->price, 2); ?></td>
                                            <td><?php echo $getData->gst_percent; ?></td>
                                            <td><?php echo $getData->hsn ?></td>
                                            <td>
                                                <div class=scrollable><?php echo $getData->markup_percent ?></div>
                                            </td>
                                            <td><?php echo "Rs. " . $getData->cost_total; ?></td>
                                            <td><?php echo "Rs. " . $getData->product_mrp; ?></td>
                                            <td><?php echo "Rs. " . $getData->sp_total; ?></td>
                                            <td><a href="<?= site_url('Warehouse/export_single_pdf/' . $getData->id) ?>" title="PDF" target="_blank" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-file-pdf-o"></i></a></td>

                                        </tr>
                                        <?php
                                        //$qty += $getData->quantity;
                                        $product_mrp += $getData->product_mrp;
                                        $totalGST += (($getData->gst_percent/100) * $getData->cost_total);
                                        $totalmarkup += ($getData->product_mrp - $getData->price);
                                        $selling += $getData->sp_total;
                                       // print_r($totalmarkup);exit;
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>

                                    <tr>
                                        <td colspan="15" >&nbsp;<span class="pull-right">Total CGST Amount</span></td>
                                        <th>
                                            <?= "Rs. " . number_format($totalGST/2, 2); ?>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <td colspan="15" >&nbsp;<span class="pull-right">Total SGST Amount</span></td>
                                        <th>
                                            <?= "Rs. " . number_format($totalGST/2, 2); ?>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
<!--                                    <tr>-->
<!--                                        <td colspan="13" >&nbsp;<span class="pull-right">Final Total Amount</span></td>-->
<!--                                        <th>-->
<!--                                            --><?//= "Rs. " . number_format($totalGST + $total, 2); ?>
<!--                                        </th>-->
<!--                                        <th colspan="2"></th>-->
<!--                                    </tr>-->
                                    <tr>
                                        <td colspan="15" >&nbsp;<span class="pull-right">Total Markup Amount</span></td>
                                        <th>
                                            <?= "Rs. " . number_format($totalmarkup, 2); ?>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <td colspan="15">&nbsp;<span class="pull-right">Final Selling Amount</span></td>
                                        <th><?= "Rs. " . number_format($selling, 2); ?></th>
                                        <td colspan="3"></td>
                                    </tr>

                                    </tfoot>
                                </table>
                                <?php
                            }
                            ?>
                        </div>


                        <div class="clearfix">&nbsp;</div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END RESPONSIVE TABLES -->

    <div></div>

</div>
<!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript">
    var url = "<?= site_url('Warehouse/getAssetdataAjax/' . $id); ?>";
    var actioncolumn = "4";
</script>
<?php $this->load->view('common/footer'); ?>

<script type="text/javascript">
    /*$(".select").remove();
     */
    function get_state_by_country(id) {
        $(".loader").fadeIn('fast');
        var datastring = "id=" + id;

        $.ajax({
            type: "post",
            url: "<?php echo site_url('Branches/get_state'); ?>",
            data: datastring,
            success: function(returndata) {
                //alert(returndata);
                $('.state').html(returndata);
                $(".loader").fadeOut('fast');
            }
        });
    }

    function get_city_by_state(id) {
        var datastring = "id=" + id;
        $(".loader").fadeIn('fast');
        $.ajax({
            type: "post",
            url: "<?php echo site_url('Branches/get_city'); ?>",
            data: datastring,
            success: function(returndata) {
                //alert(returndata);
                $('.city').html(returndata);
                $(".loader").fadeOut('fast');
            }
        });
    }
</script>

<script type="text/javascript">
    function validateinfo() {

        var branch_title = $("#branch_title").val();
        var pincode = $("#pincode").val();
        var mobile = $("#mobile").val();
        var address = $("#address").val();
        var country_id = $("#country_id").val();
        var state_id = $("#state_id").val();
        var city_id = $("#city_id").val();


        if (branch_title == '') {
            $("#branch_error").html("Required").fadeIn();
            setTimeout(function() {
                $("#branch_error").fadeOut()
            }, 5000);
            /* $("#branch_title").focus().css('border',"2px solid red");
             setTimeout(function(){$("#branch_title").css("border-color", "#ccc");},6000);  */
            return false;
        }
        if (address == '') {
            $("#address_error").html("Required").fadeIn();
            setTimeout(function() {
                $("#address_error").fadeOut()
            }, 5000);
            /*$("#address").focus().css('border',"2px solid red");
             setTimeout(function(){$("#address").css("border-color", "#ccc");},6000); */
            return false;
        }

        if (pincode == '') {
            $("#pincode_error").html("Required").fadeIn();
            setTimeout(function() {
                $("#pincode_error").fadeOut()
            }, 5000);
            /*$("#pincode").focus().css('border',"2px solid red");
             setTimeout(function(){$("#pincode").css("border-color", "#ccc");},6000);*/
            return false;
        }

        if (mobile == '') {
            $("#mobile_error").html("Required").fadeIn();
            setTimeout(function() {
                $("#mobile_error").fadeOut()
            }, 5000);
            /*$("#mobile").focus().css('border',"2px solid red");
             setTimeout(function(){$("#mobile").css("border-color", "#ccc");},6000);*/
            return false;
        }



        if (country_id == '') {
            $("#country_error").html("Required").fadeIn();
            setTimeout(function() {
                $("#country_error").fadeOut()
            }, 5000);
            /*$("#country_id").focus().css('border',"2px solid red");
             setTimeout(function(){$("#country_id").css("border-color", "#ccc");},6000); */
            return false;
        }

        if (state_id == '') {
            $("#state_error").html("Required").fadeIn();
            setTimeout(function() {
                $("#state_error").fadeOut()
            }, 5000);
            /*$("#state_id").focus().css('border',"2px solid red");
             setTimeout(function(){$("#state_id").css("border-color", "#ccc");},6000);*/
            return false;
        }

        if (city_id == '') {
            $("#city_error").html("Required").fadeIn();
            setTimeout(function() {
                $("#city_error").fadeOut()
            }, 5000);
            /* $("#city_id").focus().css('border',"2px solid red");
             setTimeout(function(){$("#city_id").css("border-color", "#ccc");},6000); */
            return false;
        }

    }
</script>

<script type="text/javascript">
    function popitup(url) {
        newwindow = window.open(url, 'name', 'height=1700,width=1000');
        var html = $("#container")[0].outerHTML;
        newwindow.document.write(html);
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    }
</script>