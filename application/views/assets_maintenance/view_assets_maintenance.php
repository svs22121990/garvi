<?php $this->load->view('common/header'); ?>
<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>
<!-- START BREADCRUMB -->
<?php echo $breadcrumbs; ?>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" method="post" action="<?php echo site_url('Assets_maintenance/save_received_data/'.$this->uri->segment(3)); ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <ul class="panel-controls">
                        <li><a href="<?= site_url('Assets_maintenance/index');?>" title="Back"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">                           
                        <div class="col-md-12">
                              <div class="col-md-4">
                                <div class="form-group">                                        
                                  <label class="col-md-12"><b>Send Date</b></label>
                                  <div class="col-md-10">
                                    <input type="text" name="date" id="date" class="form-control" value="<?php echo $assets_maintenance_data->date; ?>" readonly placeholder="Select Date" style="color: #000;">
                                  </div>
                                </div>
                              </div>
                              
                              <div class="col-md-4">
                                <div class="form-group">                                        
                                  <label class="col-md-12"><b>Asset Type</b></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="assets_type_id" id="assets_type_id" data-live-search="true" onchange="getAssets()" disabled>
                                            <option value="0">--Select Asset Type--</option>
                                            <?php 
                                               foreach($asset_types as $row) { ?>
                                                 <option value="<?php echo $row->id ?>" <?php if($assets_maintenance_data->asset_type_id == $row->id){ echo "selected"; }?>><?php echo $row->type; ?></option>
                                            <?php } ?>
                                          </select>      
                                          
                                      </div>
                                  </div>
                              </div>
                              
                            <div class="col-md-4">
                              <div class="form-group">                                        
                                  <label class="col-md-12"><b>Assets</b></label>
                                      <div class="col-md-10">
                                          <select class="form-control select"  name="assets_id" id="assets_id" data-live-search="true" onchange="return assetData(this.value)" disabled>
                                            <option value="0">--Select Assets--</option>
                                            <?php 
                                               foreach($assets as $row) { ?>
                                                 <option value="<?php echo $row->id ?>"<?php if($assets_maintenance_data->assets_id == $row->id){ echo "selected"; }?>><?php echo $row->asset_name; ?></option>
                                            <?php } ?> 
                                          </select>  
                                                                                    
                                      </div>
                                  </div>
                              </div>

                              <div class="clearfix">&nbsp;</div>


                              <table class="table table-bordered table-striped table-actions example_datatable1">
                                <tr>   
                                  <th>Product SKU</th>                                                  
                                  <th>Image</th>                           
                                  <th>Received Date </th>                                                  
                                  <th>Price</th>                           
                                  <th>Description</th>                           
                                </tr>
                                <tr>
                                  <td>
                                    <center><?php echo $asset_details->barcode_number; ?></center>
                                  </td>
                                  <td>
                                    <center>
                                    <?php if(!empty($asset_details->image)) { ?>
                                      <img src="<?php echo base_url(); ?>uploads/assetimages/<?php echo $asset_details->image; ?>" width='100px'>
                                    <?php } else { ?>
                                      <img src="<?php echo base_url(); ?>uploads/employee_images/default.jpg" width='100px'>
                                    <?php } ?>
                                    </center>
                                  </td>
                                  <td>
                                    <center><?php echo $assets_maintenance_data->received_date; ?></center>
                                  </td>
                                  <td>
                                    <center><i class="fa fa-inr"></i> <?php echo number_format($assets_maintenance_data->price,2); ?></center>
                                  </td>
                                  <td>
                                    <span><?php echo $assets_maintenance_data->description; ?></span>
                                  </td>
                                </tr>
                              </table>

                        </div>
                </div>
                <div class="panel-footer">
                    <a href="<?php echo site_url('Assets_maintenance/index'); ?>" class="btn btn-danger btn-xs">Cancel</a>
                </div>
            </div>
            </form>
            
        </div>
    </div>                    
    
</div>
<!-- END PAGE CONTENT WRAPPER -->  
<?php $this->load->view('common/footer'); ?>

<script type="text/javascript">
function validation()
{ 
      var received_date = $("#received_date").val();
      var price = $("#price").val(); //alert(branch_id);return false;
      var description = $("#description").val();

      if(received_date=="0000-00-00")
      {
        $("#errreceived_date").fadeIn().html("Required");
        setTimeout(function(){$("#errreceived_date").html("&nbsp;");},5000)
        $("#received_date").focus();
        return false;
      }

      if(price=="")
      {
        $("#errprice").fadeIn().html("Required");
        setTimeout(function(){$("#errprice").html("&nbsp;");},5000)
        $("#price").focus();
        return false;
      }
      if(description=="")
      {
        $("#errdescription").fadeIn().html("Required");
        setTimeout(function(){$("#errdescription").html("&nbsp;");},5000)
        $("#description").focus();
        return false;
      }
             
   }
</script>


<script type="text/javascript">
  function only_number(event)
{

  var x = event.which || event.keyCode;
        console.log(x);
        //alert(x);
        if((x >= 48 ) && (x <= 57 ) || x == 8 | x == 9 || x == 13 || x == 46 )
        {
          return;
        }
        else
        {
          event.preventDefault();
        }   
}
</script>
