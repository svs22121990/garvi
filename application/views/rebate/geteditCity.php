<!--For Edit Country Starts-->
<!-- <div class="form-line">
  <label>Product Type Name&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError1" style="color:red"></span></label>

  <select class="form-control select" name="product_type_id" id="product_type_id1" data-live-search="true">
   <option value="">-- Select Product Asset--</option>
   <?php
   foreach ($asset_types as $row_data) 
   {  
    ?>
    <option  value="<?php echo $row_data->id; ?>"<?php if($product_type_id==$row_data->id)echo "selected";?>><?php echo $row_data->type; ?> </option>
    <?php } ?>
   
  </select> &nbsp;
</div> -->

<!--For Edit Country Ends-->

<!-- For State Edit-->
<div class="form-line">
  <label>Description&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError2" style="color:red"></span></label>
  <textarea class="form-control"  type="text" name="description" id="description1" placeholder="Description"><?php echo $description ?></textarea>&nbsp;
</div>
		

<!--End State Edit-->
  <div class="form-line">
   <label>Rebate %&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError3" style="color:red"></span> </label>
  <input class="form-control" type="text" name="rebate_percent" id="rebate_percent1" placeholder="Rebate %" value="<?= $rebate_percent ?>" size="35"/> &nbsp;
  </div> 
  <input type="hidden" name="id" id="updateId" value="<?= $id ?>" size="35"/> &nbsp; 


<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>
