<?php $this->load->view('common/header'); ?>
<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>
   <!-- START BREADCRUMB -->
   <?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<style type="text/css">
td{
  text-align: center !important;
}
</style>
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
<!-- START RESPONSIVE TABLES -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
		  <?= form_open('GST_Summary/search',['id'=>'serch_date']); ?>
                  <div class="form-group row" style="padding-top: 20px;" >
                  <div class="col-md-3">
                        <input type="text" class="form-control" name="daterange" placeholder="Select Date" autocomplete="off" value="<?php if($selected_date != 0)echo $selected_date; ?>" />
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <select name="type3" id="type3" class="form-control">
                            <option value="">Select Sale Type</option>
                            <?php
                            if(!empty($salesTypes)) {
                                foreach ($salesTypes as $type) {
                                ?>
                                <option value="<?php echo $type->id; ?>" <?php if($type->id == $selected_type3)echo "selected";?> ><?php echo $type->label; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                      <button type="submit" class="btn btn-success">Search</button>
                      <a href="<?php site_url("Daily_Sales/index/")?>" class="btn btn-danger">X</a>
                    </div>
                  </div>
                  <?= form_close(); ?>
            <?php
            if($selected_date == 0)
            {
                $formatted_date = 0;
            } else {
                $formatted_date = $selected_date;
                $formatted_date = str_replace("-", "_", $formatted_date);
                $formatted_date = str_replace("/", "-", $formatted_date);
                $formatted_date = str_replace(" ", "", $formatted_date);
            }
            ?>
          <form method="post" action='<?=site_url("GST_Summary/export_gst_summary/$formatted_date/$selected_type/$selected_type2/$selected_type3")?>'>
            <div class="panel-heading">
                <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                <h3><span id="successStateEntry"></span></h3>
                    <ul class="panel-controls">
                        <?php if($exportPermission=='1'){?>
<!--						<li><a href="--><?//= base_url("index.php/GST_Summary/listpdf/$formatted_date/$selected_type/$selected_type2/$selected_type3"); ?><!--" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li>-->
                          <li><?=$export; ?></li>
                          <button type="submit" style="display: none" id="subbtn"></button>
                          <?php }?>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>  
            </div>

            <div class="panel-body ">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions example_datatable2 ">
                        <thead>
                            <tr>
                                <th class="text-center">Sr no.</th>
                                <th class="text-center">GST Rate</th>
                                <th class="text-center">GST Amount</th>
                                <th class="text-center">Taxable Value</th>
                                <th class="text-center">CGST Rate</th>
                                <th class="text-center">CGST Amount</th>
                                <th class="text-center">SGST Rate</th>
                                <th class="text-center">SGST Amount</th>
                                <th class="text-center">IGST Rate</th>
                                <th class="text-center">IGST Amount</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th class="text-center">GST Amount</th>
                                <th class="text-center">Taxable Value</th>
                                <th class="text-center"></th>
                                <th class="text-center">CGST Amount</th>
                                <th class="text-center"></th>
                                <th class="text-center">SGST Amount</th>
                                <th class="text-center"></th>
                                <th class="text-center">IGST Amount</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </form>
        </div>                                                

    </div>
</div>
<!-- END RESPONSIVE TABLES -->
</div>




<!-- END PAGE CONTENT WRAPPER -->
<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>
<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
  $(document).ready(function() {
    $('.example_datatable2').DataTable({ 
      "ajax" : url,
      "columns": [
            { "data": "no" },
            { "data": "gst_rate" },
            {
                "data": "gst_amount",
                "render": function ( data, type, row, meta ) {
                  return 'Rs. '+data;
                }
            },
            {
                "data": "taxable",
                "render": function ( data, type, row, meta ) {
                  return 'Rs. '+data;
                }
            },
            { "data": "cgst_rate" },
            {
                "data": "cgst_amount",
                "render": function ( data, type, row, meta ) {
                  return 'Rs. '+data;
                }
            },
            { "data": "sgst_rate" },
            {
                "data": "sgst_amount",
                "render": function ( data, type, row, meta ) {
                  return 'Rs. '+data;
                }
            },
            { "data": "igst_rate" },
            {
                "data": "igst_amount",
                "render": function ( data, type, row, meta ) {
                  return 'Rs. '+data;
                }
            }
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
            total = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( 2 ).footer() ).html('Rs. '+total.toFixed(2));

            total1 = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( 3 ).footer() ).html('Rs. '+total1.toFixed(2));

            total2 = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( 5 ).footer() ).html('Rs. '+total2.toFixed(2));

            total3 = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( 7 ).footer() ).html('Rs. '+total3.toFixed(2));

            total4 = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( 9 ).footer() ).html('Rs. '+total4.toFixed(2));
        }
    });
  });
</script>
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
<!--$(function() {-->
<!--  $('input[name="daterange"]').daterangepicker({-->
<!--	  locale: {-->
<!--            format: 'DD/MM/YYYY'-->
<!--        },-->
<!--    opens: 'right'-->
<!--  }, function(start, end, label) {-->
<!--    var startDate = start.format('YYYY-MM-DD');-->
<!--	var endDate = end.format('YYYY-MM-DD');-->
<!--  });-->
<!--});-->
<!--</script>-->
<script type="text/javascript">
function clickSubmit(){
    $('#subbtn').click();
  }
</script>

