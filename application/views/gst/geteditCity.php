<!--For Edit Country Starts-->
<div class="form-line">
  <label>Category Name&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError1" style="color:red"></span></label>

  <select class="form-control select" name="category_id" id="category_id1" data-live-search="true">
   <option value="">-- Select Category--</option>
   <?php
   foreach ($categories as $row_data) 
   {  
    ?>
    <option  value="<?php echo $row_data->id; ?>"<?php if($category_id==$row_data->id)echo "selected";?>><?php echo $row_data->title; ?> </option>
    <?php } ?>
   
  </select> &nbsp;
</div>

<div class="form-line">
  <label for="datetime">Code: <span style="color:red">*</span>&nbsp;<span id="codeErrorEdit" style="color:red"></span></label>
  <input class="form-control"  type="text" name="code" id="code1" placeholder="Code" value="<?php echo $code ?>" size="2"/> &nbsp; 
</div>

<!--For Edit Country Ends-->

<!-- For State Edit-->
<div class="form-line">
  <label>HSN&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError2" style="color:red"></span></label>
  <input class="form-control"  type="text" name="hsn" id="hsn1" placeholder="HSN" value="<?php echo $hsn ?>" size="35"/> &nbsp;&nbsp;
</div>
		

<!--End State Edit-->
  <div class="form-line">
   <label>GST %&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError3" style="color:red"></span> </label>
  <input class="form-control" type="text" name="gst_percent" id="gst_percent1" placeholder="GST %" value="<?= $gst_percent ?>" size="35"/> &nbsp;
  </div> 

  <div class="form-line">
   <label>Markup %&nbsp;<span style="color:red">*</span>&nbsp;<span id="EdittitleError4" style="color:red"></span> </label>
  <input class="form-control" type="text" name="markup_percent" id="markup_percent1" placeholder="Markup %" value="<?= $markup_percent ?>" size="35"/> &nbsp;
  </div> 

  <input type="hidden" name="id" id="updateId" value="<?= $id ?>" size="35"/> &nbsp; 


<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>
