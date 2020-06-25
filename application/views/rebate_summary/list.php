<?php
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
//dd($gstarray);

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
			<?= form_open('Rebate_Summary/search',['id'=>'serch_date']); ?>
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
              <form method="post" action="<?=site_url("Rebate_Summary/export_rebate_summary/$dateinfo")?>">
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
						<li><a href="<?= base_url("index.php/Rebate_Summary/listpdf/$dateinfo") ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li>
                          <!--<li><?=$export; ?></li>-->
                          <button type="submit" style="display: none" id="subbtn"></button>
                          <?php }?>
                        <?php if($addPermission=='1'){?>
                         <li><a href="<?php echo site_url("Rebate_Summary/create")?>" ><span class="fa fa-plus"></span></a></li>
                        <?php }?>

                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <!-- <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                    </ul>
                </div>
                <div class="panel-body ">
                <div class="table-responsive">
                  <?php
                    $qty = 0;
                                $rate_per_item = 0;
                                $total = 0;
                                // $discount_1 = 0;
                                // $discount_2 = 0;
                                // $discount_3 = 0;
                                $taxable = 0;
                                $adjustment_plus = 0;
                                $adjustment_minus = 0;
                                $net_amount = 0;
                                $total_amount = 0;
                                foreach($gstarray as $arr):
                                  $gst['cgst_amount'][$arr] = 0;
                                  $gst['sgst_amount'][$arr] = 0;
                                  $gst['csgst_amount'][$arr] = 0;
                                  $gst['igst_amount'][$arr] = 0;
                                  endforeach;
                                 ?>
                    <table class="table table-bordered table-striped table-actions example_datatable2">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Product Name</th>
                                <th>Type 1</th>
                                <th>Type 2</th>

                                <th>HSN</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Total Value</th>
                                <th>Rebate 5%</th>
                                <th>Discount</th>
                                <th>Other Discount</th>
                                <th>Total Taxable Value</th>
                                <?php if($gstarray){ foreach($gstarray as $arr): ?>
                                  <th><?= $arr/2; ?>% CGST</th>
                                  <th><?= $arr/2; ?>% SGST</th>
                                  <th><?= $arr; ?>% C+S GST</th>
                                  <th><?= $arr; ?>% IGST</th>
                                <?php endforeach; } ?>
                                <th>Adj +</th>
                                <th>Adj -</th>
                                <th>Amount after GST</th>
                                <th>Shipping Charges</th>
                                <th>Net Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($view as $key): ?>
                                <tr>
                                <td><?= $key['no']; ?></td>
                                <td><?= $key['invoice_no']; ?></td>
                                <td><?= $key['date_of_invoice']; ?></td>
                                <td><?= $key['asset_name']; ?></td>
                                <td><?= $key['product_type']; ?></td>
                                <td><?= $key['asset_type_id']; ?></td>
                                <td><?= $key['hsn']; ?></td>
                                <td><?php echo $key['invoice_quantity']; $qty +=$key['invoice_quantity'];  ?></td>
                                <td><?php echo "Rs. ".number_format($key['rate_per_item']); $rate_per_item += $key['rate_per_item']; ?></td>
                                <td><?php echo "Rs. ".($key['total']); $total += $key['total']; ?></td>
                                <td><?php echo "Rs. ".(($key['total'] * $key['discount_1'])/100); 


                                // $discount_1 += $key['discount_1']; 
                                // 
                                $discount_1 += (($key['total'] * $key['discount_1'])/100);
                                
                                ?>
                                
                              
                              </td>
                                <td><?php echo "Rs. ".(($key['total'] * $key['discount_2'])/100); 
                                
                                // $discount_2 += $key['discount_2']; 
                                $discount_2 += (($key['total'] * $key['discount_2'])/100); 
                                
                                ?></td>
                                <td><?php echo "Rs. ".(($key['total'] * $key['discount_3'])/100);
                                
                                $discount_3 += (($key['total'] * $key['discount_3'])/100);
                                // $discount_3 += $key['discount_3']; 
                                
                                ?></td>
                                <td><?php echo "Rs. ".($key['taxable']); $taxable +=$key['taxable']; ?></td>
                                <?php if($gstarray){ foreach($gstarray as $arr): ?>
                                  <td>
                                    <?php if($key['gst_rate'] == $arr): ?>
                                    <?php echo "Rs. ".$key['cgst_amount']; $gst['cgst_amount'][$arr] +=$key['cgst_amount']; ?>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <?php if($key['gst_rate'] == $arr): ?>
                                    <?php echo "Rs. ".$key['sgst_amount']; $gst['sgst_amount'][$arr] +=$key['sgst_amount']; ?>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <?php if($key['gst_rate'] == $arr): ?>
                                    <?php echo "Rs. ".($key['cgst_amount']+ $key['sgst_amount']); $gst['csgst_amount'][$arr] +=$key['cgst_amount'] + $key['sgst_amount']; ?>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <?php if($key['gst_rate'] == $arr): ?>
                                    <?php echo "Rs. ".$key['igst_amount']; $gst['igst_amount'][$arr] +=$key['igst_amount']; ?>
                                    <?php endif; ?>
                                  </td>
                                <?php endforeach; } ?>
                                <td><?php echo $key['adjustment_plus']; $adjustment_plus +=$key['adjustment_plus']; ?></td>
                                <td><?php echo $key['adjustment_minus']; $adjustment_minus +=$key['adjustment_minus']; ?></td>
                                <td><?php echo "Rs. ".number_format($key['net_amount']); $net_amount += $key['net_amount'];  ?></td>
                                <td><?= $key['shipping_charges']; ?></td>
                                <td><?php if($key['total_amount']) { echo "Rs. ".number_format($key['total_amount']); } $total_amount +=$key['total_amount'];  ?></td>
                            </tr>

                      <?php endforeach; ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th colspan="7"></th>
                                <th><?= $qty; ?></th>
                                <th><?= "Rs. ".$rate_per_item ?></th>
                                <th><?= "Rs. ".$total; ?></th>
                                <th><?= "Rs. ".$discount_1; ?></th>
                                <th><?= "Rs. ".$discount_2; ?></th>
                                <th><?= "Rs. ".$discount_3; ?></th>
                                <!-- <th colspan="3"></th> -->
                                <th><?= "Rs. ".$taxable; ?></th>
                                <?php foreach($gstarray as $arr): ?>
                                  <th><?= "Rs. ".$gst['cgst_amount'][$arr]; ?></th>
                                  <th><?= "Rs. ".$gst['sgst_amount'][$arr]; ?></th>
                                  <th><?= "Rs. ".$gst['csgst_amount'][$arr]; ?></th>
                                  <th><?= "Rs. ".$gst['igst_amount'][$arr]; ?></th>
                                <?php endforeach; ?>
                                <th><?= $adjustment_plus; ?></th>
                                <th><?= $adjustment_minus; ?></th>
                                <th><?= "Rs. ".$net_amount; ?></th>
                                <th></th>
                                <th><?= "Rs. ".$total_amount; ?></th>
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


<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css">

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.print.min.js"></script>
<script>
  $(document).ready( function(){
    $('.example_datatable2').DataTable({
      "ordering": false,
      dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
        ],

    });
} );
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
