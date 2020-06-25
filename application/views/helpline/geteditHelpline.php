<div class="form-group">
<label for="datetime">Helpline Type: <span style="color:red">*</span>&nbsp;<span id="EdittitleError1" style="color:red"></span></label>
<select class="form-control select"  name="helpline_type_id" id="edit_helpline_type_id" onchange="get_state(this.value)" data-live-search="true">
 <option value="">-- Please select Helpline Type--</option>
 <?php
 foreach ($helplineTypes as $row_data) 
 {  
  ?>
  <option  value="<?php echo $row_data->id; ?>" <?php if($helpline_type_id==$row_data->id)echo "selected";?>><?php echo $row_data->helpline_type; ?> </option>
  <?php } ?>
  
</select>

</div>&nbsp; 
<label>Contact Person:<span style="color:red">*</span>&nbsp;<span id="EdittitleError" style="color:red"></span></label>
<input class="form-control" type="text" name="contact_person" id="titlecontact_person" value="<?= $contact_person ?>" placeholder="Contact Person" size="35"/> &nbsp; 

<label>Contact Number:<span style="color:red">*</span>&nbsp;<span id="EdittitleError2" style="color:red"></span></label>
<input class="form-control" type="text" name="contact_number" id="titlecontact_number" value="<?= $contact_number ?>" placeholder="Contact Number" size="35" onkeypress="only_number(event)" maxlength="10" /> &nbsp; 


<label>Email: <!-- <span style="color:red">*</span> -->&nbsp;<span id="emailError" style="color:red"></span></label>
<input class="form-control"  type="text" name="email" id="titleemail" value="<?php echo $email; ?>" placeholder="Email"/> &nbsp; 

<label>Address: <!-- <span style="color:red">*</span> -->&nbsp;<span id="addressError" style="color:red"></span></label>
<textarea class="form-control" name="address" id="titleaddress" placeholder="Address"><?php echo $address; ?></textarea> &nbsp; 


<input type="hidden" name="id" id="updateId" value="<?= $id ?>" size="35"/> &nbsp; 
<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>

