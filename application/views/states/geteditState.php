<div class="form-group">
<label for="datetime">Country Name: <span style="color:red">*</span>&nbsp;<span id="EdittitleError1" style="color:red"></span></label>
<select class="form-control select"  name="country_id" id="edit_country_id" onchange="get_state(this.value)" data-live-search="true">
 <option value="">-- Please select country--</option>
 <?php
 foreach ($country as $row_data) 
 {  
  ?>
  <option  value="<?php echo $row_data->id; ?>" <?php if($country_id==$row_data->id)echo "selected";?>><?php echo $row_data->country_name; ?> </option>
  <?php } ?>
  
</select>

</div>
<label>State Name:<span style="color:red">*</span>&nbsp;<span id="EdittitleError" style="color:red"></span></label>
<input class="form-control" type="text" name="state_name" id="titlestate_name" value="<?= $state_name ?>" placeholder="State Name" size="35"/> &nbsp; 
<input type="hidden" name="id" id="updateId" value="<?= $id ?>" size="35"/> &nbsp; 
<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>

