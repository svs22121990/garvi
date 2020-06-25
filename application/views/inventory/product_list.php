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
				<!--<?= form_open('Product_Summary/search',['id'=>'serch_date']); ?>
                  <div class="form-group row" style="padding-top: 20px;" >
                    <label class="col-md-2"> select Date<span style="color: red">*</span> <span  id="purchase_date_error" style="color: red"></span></label>
                    <div class="col-md-3">

					  <input type="text" class="form-control" name="daterange" value="" />
                    </div>
                    <div class="col-md-2">
                      <button type="submit" class="btn btn-success">Search</button>
                    </div>
					<div  class="col-md-4">



					</div>
                  </div>
                  <?= form_close(); ?>-->
				  <?php if($dateinfo!= 0 ){ ?>
				  <form method="post" action="<?=site_url("inventory/export_product_summary/$user_id/$dateinfo")?>">
				  <?php }else{ ?>
				  <form method="post" action="<?=site_url("inventory/export_product_summary/$user_id")?>">
				  <?php } ?>

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

						  <?php if(isset($dateinfo)){ ?>
						   <li><a href="<?= base_url(); ?>index.php/inventory/export_pdf_summary/<?= $user_id; ?>/<?= $dateinfo; ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li>
						   <?php } else { ?>
						   <li><a href="<?= base_url(); ?>index.php/inventory/export_pdf_summary/<?= $user_id; ?>" target="_blank"><span title="PDF" class="fa fa-file-pdf-o"></span></a></li>
						   <?php } ?>
                          <li><?=$export; ?></li>
                          <button type="submit" style="display: none" id="subbtn"></button>

                        <?php if($addPermission=='1'){?>
                         <li><a href="<?php echo site_url("Products/create")?>" ><span class="fa fa-plus"></span></a></li>
                        <?php }?>
                        <li><a href="<?= base_url('index.php/inventory'); ?>" class=""><span class="fa fa-arrow-left"></span></a></li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <!-- <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                    </ul>
                </div>
                <div class="panel-body ">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Type1</th>
                                <th>Type2</th>
                                <th>Selling Price</th>
                                <th>Quantity</th>
                                <th>Remaining</th>
                                <th>Total Amount</th>
                                <th>Purchase Date</th>
                                <th>AGE</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php $i=1; foreach($products as $row):
                            $startDate = $row->purchase_date;
                            $endDate = date('Y-m-d');

                            $datetime1 = date_create($startDate);
                            $datetime2 = date_create($endDate);
                            $interval = date_diff($datetime1, $datetime2, false);
                            $arrTime = array();
                            if($interval->y!=0){
                              $arrTime[] =  $interval->y.' Year ';
                            }
                            if($interval->m!=0){
                              $arrTime[] =  $interval->m .' Months ';
                            }
                            $arrTime[] =  $interval->d.' Days Ago';
                            ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row->asset_name; ?></td>
                                <td><?= $row->title; ?></td>
                                <td><?= $row->type; ?></td>
                                <td><?= $row->product_type?></td>
                                <td><?= "Rs ".number_format($row->product_mrp,2); ?></td>
                                <td><?= $row->total_quantity; ?></td>
                                <td><?= $row->quantity; ?></td>
                                <td><?= "Rs ".number_format($row->quantity * $row->product_mrp,2) ; ?></td>
                                <td><?= $row->purchase_date; ?></td>
                                <td><?= implode(" ",$arrTime); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>









<!-- END DEFAULT DATATABLE -->
<!--<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>-->

<?php $this->load->view('common/footer');?>
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
