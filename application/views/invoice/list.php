<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<style type="text/css">
td{
  text-align: center !important;
}
</style>
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
			<?= form_open('Invoice/search',['id'=>'serch_date']); ?>
                  <div class="form-group row" style="padding-top: 20px;" >
                    <label class="col-md-2"> select Date<span style="color: red">*</span> <span  id="purchase_date_error" style="color: red"></span></label>
                    <div class="col-md-3">
                      <!--<input type="text" name="purchase_date" id="purchase_date" class="form-control datepicker" placeholder="Purchase Date" required>-->
					  <input type="text" class="form-control" name="daterange" value="" />
                    </div>
                    <div class="col-md-2">
                      <button type="submit" class="btn btn-success">Search</button>
                    </div>
					<div  class="col-md-4">
					


					</div>
                  </div>
                  <?= form_close(); ?>
				  
					   <form method="post" id="export_excle" action="<?= site_url("Invoice/export_product_summary/$dateinfo"); ?>">
				
              
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
						<li>
							<?php if($dateinfo !=0){ ?>
							<a href="<?= base_url(); ?>index.php/Invoice/export_pdf_summary/<?= $dateinfo; ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a>
							<?php } else { ?>
							<a href="<?= base_url(); ?>index.php/Invoice/export_pdf_summary" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a>
							<?php } ?>
						</li>
                          <li><?=$export; ?></li>
                          <button type="submit" style="display: none" id="subbtn"></button>
                          <?php }?>
                        <?php if($addPermission=='1'){?>
                         <li><a href="<?php echo site_url("Invoice/create")?>" ><span class="fa fa-plus"></span></a></li>
                        <?php }?>
                       
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <!-- <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                    </ul>                                
                </div>
                <div class="panel-body ">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <ul class="list-unstyled list-inline">
                            <?php
                                foreach ($salesTypes as $salesType):
                            ?>
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input class="salesTypeFilters dtFiltersCal" value="<?=$salesType->id?>" data-label="<?=$salesType->label?>" type="checkbox" data-type="salesType"> <?=$salesType->label?>
                                    </label>
                                </div>
                            </li>
                            <?php
                                endforeach;
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-6 text-right">
                        <ul class="list-unstyled list-inline">
                            <?php
                                foreach ($paymentModes as $paymentMode):
                            ?>
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input class="paymentModeFilters dtFiltersCal" value="<?=$paymentMode->id?>" data-label="<?=$paymentMode->type?>" type="checkbox" data-type="paymentMode"> <?=$paymentMode->type?>
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
                                <th class="text-center">Sr No</th>
                                <th class="text-center">Invoice No</th>
                                <th class="text-center">GST Number</th>
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Type of Sales</th>
                                <th class="text-center">Date of Invoice</th>
                                <th class="text-center">Payment Mode</th>
                                <!--<th class="text-center">Serial Number of Invoice</th>-->
                                <th class="text-center">Invoice Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>                           
                        </tbody>
                        <tfoot>
                             <tr>
                                <th colspan="7"></th>
                                <th class="text-center">Invoice Amount</th>
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
            <form method="post" action="<?= site_url('Invoice/delete') ?>">       
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
    // checkbox filtering dataTable
    // @rohanOnly @tSe
    $.fn.dataTableExt.afnFiltering.push(function(oSettings, aData, iDataIndex) {
        var dtFiltersCal = $('.dtFiltersCal');
        var paymentModeFilters = $('.paymentModeFilters');
        // we want to show all rows when no filter is applied
        if (!dtFiltersCal.is(':checked')) {
            return true;
        }
        var status = false;
        dtFiltersCal.each(function(index) {
            var filterEl = $(this);
            var dataIndex = filterEl.data('type') === 'salesType' ? 4 : 6;
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
            { "data": "invoice_no" },
            { "data": "gst_number" },
            { "data": "receiver_bill_name" },
            { "data": "salesType" },
            { "data": "date_of_invoice" },
            { "data": "paymentMode" },
            {
                "data": "sumAmount",
                "render": function ( data, type, row, meta ) {
                  return 'Rs. '+data;
                }
            },
            { "data": "btn" }
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
                .column(7, {filter: 'applied'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $( api.column( 7 ).footer() ).html('Rs. '+total.toFixed(2));
        }
    });

    $('.dtFiltersCal').on("click", function(e) {
        oTable.draw();

        var api = oTable, data;
 
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };
        total = api
            .column(7, {filter: 'applied'})
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        $( api.column( 7 ).footer() ).html('Rs. '+total.toFixed(2));
    });
  });
</script>
<script type="text/javascript" src="<?= base_url(); ?>assets/date_r_picker/moment.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/date_r_picker/daterangepicker.min.js"></script>

<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
	  locale: {
            format: 'DD/MM/YYYY'
        },
    opens: 'right'
  }, function(start, end, label) {
    var startDate = start.format('YYYY-MM-DD');
	var endDate = end.format('YYYY-MM-DD');
  });
});
</script>

    <script type="text/javascript">
      function clickSubmit(){
        $('#export_excle').submit();
      }
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
  setTimeout(function(){$("#msghide").html("&nbsp;");},5000)
</script>

           


