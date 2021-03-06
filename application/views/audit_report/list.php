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
            <div class="form-group row" style="padding-top: 20px;" >
            <?= form_open('Audit_Report/search',['id'=>'serch_date']); ?>
                    <div class="col-md-3">
                        <select name="date" id="date" class="form-control">
                            <?php
                            if(!empty($max_year)) {
                                while ($max_year > $min_year) {
                                ?>
                                <option value="<?php echo $max_year; ?>"  <?php if($max_year==$selected_year)echo "selected";?> ><?php echo $max_year; ?></option>
                                <?php $max_year--;
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                      <button type="submit" class="btn btn-success">Search</button>
                      <a href="<?php site_url("Audit Report/index/")?>" class="btn btn-danger">X</a>
                    </div>
              <?= form_close(); ?>
              <?= form_open('Audit_Report/submit_physical_stock',['id'=>'serch_date']); ?>
                <?php if($download != 1) { ?>
                    <div class="col-md-2">
                      <input type="hidden" class="form-control" name="dateYear" value="<?= $selected_year; ?>">
                      <button type="submit" class="btn btn-warning">Submit Physical Stock</button>
                    </div>
                <?php } ?>
              <?= form_close(); ?>
                  <?php if($download == 1) { ?>
                    <div class="col-md-2">
                      <a download="<?=$fileName?>" class="btn btn-success" title="Download Format" href="<?=$filePath?>">Download Audit Report</a>
                    </div>
                  <?php } ?>
              </div>
              <form method="post" action="<?=site_url("Audit_Report/export_audit_report/$selected_year")?>">
                <div class="panel-heading">                                
                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                     <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                     <h3><span id="successStateEntry"></span></h3>
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
                        
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>                                
                </div>
                <div class="panel-body ">
                <?php if($download != 1) { ?>
                <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions example_datatable2" style="width:100%">
                        <thead>
                            <tr>
                                <th colspan="4"></th>
                                <th colspan="2">Opening Stock as of <?php echo $openingStockDate; ?></th>
                                <th colspan="2">Received during the year</th>
                                <th colspan="2">Dispatch / Purchase Return</th>
                                <th colspan="2">Sales Amount</th>
                                <th colspan="2">Sales Return</th>
                                <th colspan="2">Book Stock</th>
                                <th colspan="2">Physical Stock</th>
                                <th colspan="2">Shortage</th>
                                <th colspan="2">Excess</th>
                                <th colspan="2">Damage</th>
                                <th colspan="4">Year Wise detail of Stock</th>
                                <th>Action</th>
                            </tr>
                             <tr>
                                <th>Sr No</th>
                                <th>LF No</th>
                                <th>Items</th>
                                <th>Rate</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Upto 1</th>
                                <th>1 to 2</th>
                                <th>2 to 3</th>
                                <th>Above 3</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>                           
                        </tbody>
                        <tfoot>
                            <tr>
<!--                              <th colspan="29"></th>-->
                                <th colspan="3"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
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
                <?php } ?>
              </form>
            </div>
            <div class="modal fade" id="myModaledit" role="dialog">
              <div class="modal-dialog">     
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>Physical Stock </strong><span id="successEditEntry" style="color:green"></span></h4>
                  </div>
                  <form method="post">
                  <div class="modal-body" >
                    <div class="form-line">
                      <label>Physical Quantity&nbsp;<span style="color:red">*</span>&nbsp;<span id="editQuantityError" style="color:red"></span> </label>
                      <input class="form-control" type="text" name="quantity" id="quantity" placeholder="Physical Quantity" value="0" size="35"/> &nbsp;
                      <input type="hidden" name="row_id" id="row_id" value="0" />
                    </div> 
                  </div>
                  </form>
                <div class="modal-footer">
                  <input type="hidden" name="id" id="updateId">
                  <button type="button" class="btn btn-round btn-success"  id="statusEdiBtn"  onclick="updateData();">Submit</button>
                  <button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
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
            var dataIndex = filterEl.data('type') === 'salesType' ? 3 : 5;
            if (filterEl.is(':checked') && filterEl.data('label') === aData[dataIndex]) {
                status = true;
            }
        });
        return status;
    });
    var oTable = $('.example_datatable2').DataTable({ 
      "ajax" : url,
      "columns": [
            { "data": "no" },
            { "data": "lf_no" },
            { "data": "asset_name" },
            { "data": "product_mrp" },
            { "data": "quantity" },
            { "data": "total" },
            { "data": "received_quantity" },
            { "data": "received_total" },
            { "data": "return_quantity" },
            { "data": "return_total" },
            { "data": "sales_quantity" },
            { "data": "sales_total" },
            { "data": "sales_return_quantity" },
            { "data": "sales_return_total" },
            { "data": "book_quantity" },
            { "data": "book_total" },
            { "data": "physical_quantity" },
            { "data": "physical_total" },
            { "data": "shortage_quantity" },
            { "data": "shortage_total" },
            { "data": "excess_quantity" },
            { "data": "excess_total" },
            { "data": "damage_quantity" },
            { "data": "damage_total" },
            { "data": "1year" },
            { "data": "2year" },
            { "data": "3year" },
            { "data": "4year" },
            { "data": "btn" },
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
                     .column(3, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 3 ).footer() ).html('Rs. '+total.toFixed(2));

                 total = api
                     .column(4, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 4 ).footer() ).html(total.toFixed(2));

                 total = api
                     .column(5, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 5 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(6, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 6 ).footer() ).html(total.toFixed(2));

                 total = api
                     .column(7, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 7 ).footer() ).html(total.toFixed(2));

                 total = api
                     .column(8, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 8 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(9, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 9 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(10, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 10 ).footer() ).html(total.toFixed(2));



                 total = api
                     .column(11, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 11 ).footer() ).html(total.toFixed(2));

                 total = api
                     .column(12, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 12 ).footer() ).html(total.toFixed(2));

                 total = api
                     .column(13, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 13 ).footer() ).html(total.toFixed(2));



                 total = api
                     .column(14, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 14 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(15, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 15 ).footer() ).html(total.toFixed(2));

                 total = api
                     .column(16, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 16 ).footer() ).html(total.toFixed(2));

                 total = api
                     .column(17, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 17 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(18, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 18 ).footer() ).html(total.toFixed(2));

                 total = api
                     .column(19, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 19 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(20, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 20 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(21, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 21 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(22, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 22 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(23, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 23 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(24, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 24 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(25, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 25 ).footer() ).html(total.toFixed(2));


                 total = api
                     .column(26, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 $( api.column( 26 ).footer() ).html(total.toFixed(2));

                 total2 = api
                     .column(27, {filter: 'applied'})
                     .data()
                     .reduce( function (a, b) {
                         return intVal(a) + intVal(b);
                     }, 0 );
                 var additionalDOM = total2.toFixed(2)+'</div>';
                 $( api.column( 27 ).footer() ).html(additionalDOM);

        }
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


<script type="text/javascript">

    function getEditvalue(rowid)
    {     
        $("#updateId").val(rowid);
        
        $.ajax({
          type: "POST",
          url: "<?= site_url('Audit_Report/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          {
            $("#quantity").val($.trim(result));
            $("#row_id").val(rowid);
          }             
        });
    }

    
function clickSubmit(){
    $('#subbtn').click();
  }
</script>


<script type="text/javascript">
  setTimeout(function(){$("#msghide").html("&nbsp;");},5000)

  function updateData()
  { 
    var quantity = $("#quantity").val();  
    var row_id = $("#row_id").val();  

    if($.trim(quantity) == "")
    {
      $("#editQuantityError").fadeIn().html("Please enter Physical Quantity");
      setTimeout(function(){$("#editQuantityError").fadeOut();},2000);
      $("#quantity").focus();
      return false;
    }

    var datastring  = "row_id="+row_id+"&quantity="+quantity;
    var table = $('.example_datatable').DataTable();
    $.ajax({
      type : "post",
      url : "<?php echo site_url('Audit_Report/updateData') ?>",
      data : datastring,
      success : function(response)
      {    //alert(response);return false;
        if(response == 1)
        {
          $(".close").click(); 
          $("#successStateEntry").fadeIn().html("<span class='label label-success'> Physical Quantity has been Updated successfully</span>");
          $("#myModaledit").modal("hide"); 
          table.ajax.reload();
      }

    }
  });
  }
</script>

           

