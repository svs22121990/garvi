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
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <?= form_open('Sales_Summary/search', ['id' => 'serch_date']); ?>
                <div class="form-group row" style="padding-top: 20px;">
                    <label class="col-md-2"> select Date<span style="color: red">*</span> <span id="purchase_date_error" style="color: red"></span></label>
                    <div class="col-md-3">
                        <!--<input type="text" name="purchase_date" id="purchase_date" class="form-control datepicker" placeholder="Purchase Date" required>-->
                        <input type="text" class="form-control" name="daterange" value="" />
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                <?= form_close(); ?>
                <form method="post" action="<?= site_url("Credit_Sales_Report/export_credit_sales_report/$dateinfo") ?>">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                        <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                        <ul class="panel-controls">
                            <li>
                                <a href="<?= base_url(); ?>index.php/Credit_Sales_Report/sales_pdf/<?= $dateinfo ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>index.php/Credit_Sales_Report/export_sales_summary/<?= $dateinfo ?>" target="_blank"><span title="Excel" class="fa fa-file-excel-o"></span></a>
                            </li>
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                            <!-- <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                        </ul>
                    </div>
                    <div class="panel-body ">
                        <div class="row">
                            <div class="col-md-6 text-left">
                            </div>
                            <div class="col-md-6 text-right">
                                <ul class="list-unstyled list-inline">
                                    <?php
                                    foreach ($paymentModes as $paymentMode) :
                                        ?>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input class="paymentModeFilters dtFiltersCal" value="<?= $paymentMode->id ?>" data-label="<?= $paymentMode->type ?>" type="checkbox" data-type="paymentMode"> <?= $paymentMode->type ?>
                                            </label>
                                        </div>
                                    </li>
                                    <?php
                                endforeach;
                                ?>
                                </ul>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions example_datatable2">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Invoice No.</th>
                                        <th>Date</th>
                                        <th>Product Name</th>
                                        <th>Payment Mode</th>
                                        <th>Quantity</th>
                                        <th>Product Type 2</th>
                                        <th>Sub - Total</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th>Quantity</th>
                                        <th>
                                        <th>Sub - Total</th>
                                        <th>Total Amount</th>
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

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-toggled">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Settlements</strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                    <li>
                                <a href="<?= base_url(); ?>index.php/Credit_Sales_Report/settlement_pdf/<?= $dateinfo ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>index.php/Credit_Sales_Report/export_settlement_summary/<?= $dateinfo ?>" target="_blank"><span title="Excel" class="fa fa-file-excel-o"></span></a>
                            </li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions settlement_datatable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Invoice No.</th>
                                <th>Date</th>
                                <th>Settlement Date</th>
                                <th>Payment Mode</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>
                                <th>Total Amount</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- END DEFAULT DATATABLE -->
<script type="text/javascript">
    var url = "<?= $ajax_manage_page; ?>";
    var actioncolumn = "<?= $actioncolumn; ?>";
</script>

<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        // checkbox filtering dataTable
        // @rohanOnly @tSe
        $.fn.dataTableExt.afnFiltering.push(function(oSettings, aData, iDataIndex) {
            var dtFiltersCal = $('.dtFiltersCal');
            // we want to show all rows when no filter is applied
            if (!dtFiltersCal.is(':checked')) {
                return true;
            }
            var status = false;
            dtFiltersCal.each(function(index) {
                var filterEl = $(this);
                var dataIndex = 4;
                if (filterEl.is(':checked') && filterEl.data('label') === aData[dataIndex]) {
                    status = true;
                }
            });
            return status;
        });
        var oTable = $('.example_datatable2').DataTable({
            "ajax": {
                "url": url,
                "dataSrc": "data",
            },
            "columns": [{
                    "data": "no"
                },
                {
                    "data": "invoice_no"
                },
                {
                    "data": "date_of_invoice"
                },
                {
                    "data": "asset_name"
                },
                {
                    "data": "paymentMode"
                },
                {
                    "data": "quantity"
                },
                {
                    "data": "productType"
                },
                {
                    "data": "net_amount",
                    "render": function(data, type, row, meta) {
                        return 'Rs. ' + data;
                    }
                },
                {
                    "data": "sumAmount",
                    "render": function(data, type, row, meta) {
                        if (data) {
                            return 'Rs. ' + data;
                        } else {
                            return data;
                        }
                    }
                },
                {
                    "data": "btn",
                },
            ],
            "ordering": false,

            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                total = api
                    .column(5, {
                        filter: 'applied'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(5).footer()).html(total);

                total1 = api
                    .column(7, {
                        filter: 'applied'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(7).footer()).html('Rs. ' + total1.toFixed(2));

                total2 = api
                    .column(8, {
                        filter: 'applied'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                var additionalDOM = 'Rs.' + total2.toFixed(2);
                $(api.column(8).footer()).html(additionalDOM);
            }
        });

        var sTable = $('.settlement_datatable').DataTable({
            "ajax": {
                "url": url,
                "dataSrc": "settlementData",
            },
            "columns": [{
                    "data": "no"
                },
                {
                    "data": "invoice_no"
                },
                {
                    "data": "date_of_invoice"
                },
                {
                    "data": "settlement_date"
                },
                {
                    "data": "paymentMode"
                },
                {
                    "data": "sumAmount",
                    "render": function(data, type, row, meta) {
                        if (data) {
                            return 'Rs. ' + data;
                        } else {
                            return data;
                        }
                    }
                },
            ],
            "ordering": false,

            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                total2 = api
                    .column(5, {
                        filter: 'applied'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                var additionalDOM = 'Rs.' + total2.toFixed(2);
                $(api.column(5).footer()).html(additionalDOM);
            }
        });

        $('.dtFiltersCal').on("click", function(e) {
            oTable.draw();
            sTable.draw();

            var api = oTable,
                sapi = sTable,
                data;

            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };
            total = api
                .column(5, {
                    filter: 'applied'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            $(api.column(5).footer()).html(total);

            total1 = api
                .column(7, {
                    filter: 'applied'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            $(api.column(7).footer()).html('Rs. ' + total1.toFixed(2));

            total2 = api
                .column(8, {
                    filter: 'applied'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            var additionalDOM = 'Rs.' + total2.toFixed(2);
            $(api.column(8).footer()).html(additionalDOM);

            total3 = sapi
                    .column(5, {
                        filter: 'applied'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
            $(sapi.column(5).footer()).html('Rs.' + total3.toFixed(2));
        });
    });
</script>
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>-->
<script type="text/javascript" src="<?= base_url(); ?>assets/date_r_picker/moment.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/date_r_picker/daterangepicker.min.js"></script>
<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            showDropdowns: true,
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
</script>
<!--<script>-->
<!--    $(function() {-->
<!--        $('input[name="daterange"]').daterangepicker({-->
<!--            locale: {-->
<!--                format: 'DD/MM/YYYY'-->
<!--            },-->
<!--            opens: 'right'-->
<!--        }, function(start, end, label) {-->
<!--            var startDate = start.format('YYYY-MM-DD');-->
<!--            var endDate = end.format('YYYY-MM-DD');-->
<!--        });-->
<!--    });-->
<!--</script>-->


<script type="text/javascript">
    function checkStatus(id) {
        $("#statusId").val(id);
        $("#deleteId").val(id);
    }
</script>


<script type="text/javascript">
    function savetransfer() {
        var branch_id = $("#branch_id").val();
        var quantity = $("#quantity").val();
        var quantity_count = $("#quantity_count").val();
        //alert(quantity_count);return false;           

        if (branch_id == "") {
            $("#branchError").fadeIn().html("Required");
            setTimeout(function() {
                $("#branchError").fadeOut();
            }, 8000);
            $("#branch_id").focus();
            return false;
        }

        if ($.trim(quantity) == "") {
            $("#QuantityError").fadeIn().html("Required");
            setTimeout(function() {
                $("#QuantityError").fadeOut();
            }, 8000);
            $("#quantity").focus();
            return false;
        }
        if (parseInt(quantity) > parseInt(quantity_count) || quantity == 0) {
            $("#QuantityError").fadeIn().html("Quantity should be less than total quantity and not equal to zero");
            setTimeout(function() {
                $("#QuantityError").fadeOut();
            }, 8000);
            $("#quantity").focus();
            return false;
        }
    }
</script>


<script type="text/javascript">
    function getassetvalue(rowid) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('Products/getassetdetail'); ?>",
            data: {
                id: rowid
            },
            cache: false,
            success: function(result) {
                var obj = $.parseJSON(result);
                if (obj.success == '1') {
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
    var url2 = "<?= $ajax_manage_page; ?>";
    var actioncolumn = "<?= $actioncolumn; ?>";
</script>

<script type="text/javascript">
    function saveData() {
        var name = $("#name").val();

        if ($.trim(name) == "") {
            $("#nameError").fadeIn().html("Please enter Asset type name");
            setTimeout(function() {
                $("#nameError").fadeOut();
            }, 4000);
            $("#name").focus();
            return false;
        }
        var datastring = "name=" + name;
        $.ajax({
            type: "post",
            url: "<?php echo site_url('Product_type/addData'); ?>",
            data: datastring,
            success: function(response) {
                if (response == 1) {
                    $("#nameError").fadeIn().html("Asset Type name already exist");
                    setTimeout(function() {
                        $("#nameError").fadeOut();
                    }, 8000);
                } else {
                    $(".close").click();
                    $("#successCountryEntry").fadeIn().html("Asset Type has been Added successfully");
                    setTimeout(function() {
                        window.location.reload();
                    }, 100);
                }
            }
        });
    }
</script>

<script type="text/javascript">
    function getEditvalue(rowid) {
        $("#updateId").val(rowid);

        $.ajax({
            type: "POST",
            url: "<?= site_url('Product_type/getUpdateName'); ?>",
            data: {
                id: rowid
            },
            cache: false,
            success: function(result) {
                $("#titleName").val($.trim(result));
            }
        });
    }

    function updateData() {
        var name = $("#titleName").val();
        var updateId = $("#updateId").val();


        if ($.trim(name) == "") {
            $("#titleError").fadeIn().html("Please enter Asset type");
            setTimeout(function() {
                $("#titleError").fadeOut();
            }, 8000);
            $("#titleName").focus();
            return false;
        }

        var datastring = "name=" + name + "&id=" + updateId;

        $.ajax({
            type: "post",
            url: "<?php echo site_url('Product_type/updateData') ?>",
            data: datastring,
            success: function(response) {
                if (response == 1) {
                    $("#titleError").fadeIn().html("Asset type name already exist");
                    setTimeout(function() {
                        $("#titleError").fadeOut();
                    }, 8000);
                } else {
                    $(".close").click();
                    $("#successCountryEntry").fadeIn().html("Asset type has been updated successfully");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }

            }
        });
    }

    function clickSubmit() {
        $('#subbtn').click();
    }
</script>


<script type="text/javascript">
    setTimeout(function() {
        $("#msghide").html("&nbsp;");
    }, 5000)
</script> 