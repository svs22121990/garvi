 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<style type="text/css">
    .bordered{
        margin-bottom: 20px;
        margin-top: 20px;
        border:1px solid #eee;
        width: 100%;
    }
</style>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                
                    <div class="row">
                       <form method="post" action="<?php echo site_url('Maintenance_scheduling/create_scheduling') ?>">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                                    <ul class="panel-controls">
                                        <li><a href="<?=site_url('Maintenance_scheduling/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body">                                                                        
                                    
                                      <div class="row">
                                            <div class="col-md-12">
                                             
                                              <div class="col-md-4">
                                                <label for="vendor">Asset Type <span style="color:red">*</span> <span style="color:red" id="error1"></span></label>
                                                 <select class="form-control select" id="asset_type_id" name="asset_type_id" onchange="GetAssets(this.value)" data-live-search="true">
                                                  <option value=''>Select Asset Type</option>
                                                  <?php if(!empty($asset_types)) { foreach($asset_types as $types){ ?>
                                                      <option value='<?=$types->id; ?>'><?=$types->type; ?></option>
                                                  <?php } }?>
                                                </select><div>&nbsp;</div>
                                              </div> 

                                               <div class="col-md-4">
                                                <label for="vendor">Vendor <span style="color:red">*</span> <span style="color:red" id="error2"></span></label>
                                                 <select class="form-control select" id="vendor_id" name="vendor_id" data-live-search="true">
                                                  <option value=''>Select Vendor</option>
                                                </select><div>&nbsp;</div>
                                              </div>  

                                               <div class="col-md-4">
                                                <label for="vendor">Asset <span style="color:red">*</span> <span style="color:red" id="error3"></span></label>
                                                 <select class="form-control select" id="asset_id" name="asset_id" onchange="getBarcodeData(this.value)" data-live-search="true">
                                                  <option value=''>Select Asset</option>
                                                </select><div>&nbsp;</div>
                                              </div>  

                                            </div>
                                            <div class="clearfix">&nbsp;</div>
                                            <div class="col-md-12" id="barcodeData" style="max-height: 400px;overflow-y: scroll;">
                                                    
                                           </div>
                                          
                                          </div>
                                    </div>
                                
                                <div class="panel-footer">
                                    <button class="btn btn-success" type="submit" id="submit" onclick="return addValidation();"><?= $button;?></button>
                                    <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>                                    
                                </div>
                            </div>
                            </div>
                             </form>
                            
                        </div>
                    </div>                    
                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript">
    var url="";
    var actioncolumn="";
</script>

<?php $this->load->view('common/footer');?><script type="text/javascript">
    function GetAssets(id)
    {
        $(".loader").fadeIn();
        $.ajax({
        type:'post',
        url:"<?= site_url('Maintenance_scheduling/GetAssets') ?>",
        data:{id:id},
        success:function(response){
          
          var obj=$.parseJSON(response);
          $("#asset_id").html(obj.assets).selectpicker('refresh');
          $("#vendor_id").html(obj.vendors).selectpicker('refresh');
          $(".loader").fadeOut();
        }
        });
    }

    function getBarcodeData(id)
    {
      $(".loader").fadeIn();
      $.ajax({
        type:'post',
        url:"<?= site_url('Maintenance_scheduling/GetBarcode') ?>",
        data:{id:id},
        success:function(response){
            $('#barcodeData').html(response);
            $(".loader").fadeOut();
        }
        });
    }

function add_data(id)
{
      if ($('#row_checkbox'+id).is(":checked"))
       {
         $("#date_first"+id).hide();
         $("#date_sec"+id).show();

          $("#desc_one"+id).hide();
         $("#desc_two"+id).show();

         $("#type_first"+id).hide();
         $("#type_sec"+id).show();

       }
       else
       {
         $("#date_first"+id).show();
         $("#date_sec"+id).hide();

         $("#desc_one"+id).show();
         $("#desc_two"+id).hide();

         $("#type_first"+id).show();
         $("#type_sec"+id).hide();
       }
}

</script>

<script type="text/javascript">    
function addValidation() 
{
  var asset_type_id = $("#asset_type_id").val();
  var vendor_id = $("#vendor_id").val();
  var asset_id = $("#asset_id").val();
  var count = $('#asset_table tr').length;
  if(asset_type_id=="")
  {    
    $("#error1").fadeIn().html("Please select asset type");
    setTimeout(function(){ $("#error1").fadeOut(); }, 3000);
    $("#asset_type_id").focus();
    return false; 
  }

  if(vendor_id=="")
  {    
    $("#error2").fadeIn().html("Please select vendor");
    setTimeout(function(){ $("#error2").fadeOut(); }, 3000);
    $("#vendor_id").focus();
    return false; 
  }

  if(asset_id=="")
  {    
    $("#error3").fadeIn().html("Please select asset");
    setTimeout(function(){ $("#error3").fadeOut(); }, 3000);
    $("#asset_id").focus();
    return false; 
  }

  if(count!='')
  {
      for(var i=1; i<=parseInt(count); i++)
      {
        
         if($('#row_checkbox'+i).is(":checked"))
         {
            var date = $("#date_sec"+i).val();
            if(date=='')
            {
              $("#error4").fadeIn().html("Please select date");
              setTimeout(function(){ $("#error4").fadeOut(); }, 3000);
              $("#date_sec"+i).focus();
              return false;
            }
          }

      }
    }

}
</script> 



