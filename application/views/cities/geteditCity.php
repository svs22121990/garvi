<!--For Edit Country Starts-->

<label>Country Name:&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError1" style="color:red"></span></label>

<select class="form-control select"  name="country_id" id="country_id1" onchange="get_state_by_country(this.value)" data-live-search="true">
 <option value="">-- Select country--</option>
 <?php
 foreach ($country as $row_data) 
 {  
  ?>
  <option  value="<?php echo $row_data->id; ?>"<?php if($country_id==$row_data->id)echo "selected";?>><?php echo $row_data->country_name; ?> </option>
  <?php } ?>
 
</select>

<!--For Edit Country Ends-->

<!-- For State Edit-->

<label>State Name:&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError2" style="color:red"></span></label>
<select class="form-control state select"  name="state_id" id="state_id1" data-live-search="true">
 <option value="">--Select States--</option>
 <?php
 foreach ($state as $row_data) 
 {  
  ?>
  <option  value="<?php echo $row_data->id; ?>"<?php if($state_id==$row_data->id)echo "selected";?>><?php echo $row_data->state_name; ?></option>
  <?php } ?>
  
</select>
		

<!--End State Edit-->

 <label>City Name:&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError3" style="color:red"></span> </label>
<input class="form-control" type="text" name="city_name" id="city_name1" placeholder="City Name" value="<?= $city_name ?>" size="35"/> &nbsp; 
<input type="hidden" name="id" id="updateId" value="<?= $id ?>" size="35"/> &nbsp; 



  <script type="text/javascript">
        function get_state_by_country(id)
        { 
          var datastring = "id="+id;
         // alert(datastring);
          $.ajax({
                type:"post",
                url:"<?php echo site_url('Cities/get_state'); ?>",
                data:datastring,
                success:function(returndata)
                { 
               // alert(returndata);
                  $('#city_name1').val('');
                  $('#state_id1').html(returndata).selectpicker('refresh');
                }
          });
        }
      </script>
<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>
