<table class='table table-bordered table-striped'>
  <thead>
    <tr>
      <th><input type="checkbox" id="select_all" class="select_all"></th>
      <th>Barcode No</th>
      <th>Barcode Sticker</th>
      <th>Date  <span style="color:red">*</span> <span style="color:red" id="error4"></span></th>
      <th>Description</th>
      <th>Type</th>
  </tr>
  </thead>
  <tbody id="asset_table">
  <?php $sr=1;
        foreach ($getData as $data ) { ?>
        <tr>
          <td><input type='checkbox' id='row_checkbox<?php echo $sr ?>' onclick="add_data(<?php echo $sr ?>)"></td>
          
          <td><?= $data->barcode_number ?></td>
          <td><img src="<?php echo base_url() ?>assets/purchaseOrder_barcode/<?php echo $data->barcode_image; ?>"></td>
          <td> 
            <input type="text" id="date_first<?php echo $sr ?>" class="form-control readonly date date1" disabled>
            <input type="text" id="date_sec<?php echo $sr ?>" class="form-control readonly date date2" name="date[]" style="display:none">
          </td>
          <td> 
            <textarea class="form-control desc1" id="desc_one<?php echo $sr ?>" readonly=""></textarea>
            <textarea class="form-control desc2" id="desc_two<?php echo $sr ?>" name="description[]" style="display:none"></textarea>
          </td>
          <td> 
            <select class="form-control type1" disabled id="type_first<?php echo $sr ?>">
                  <option value="Free">Free</option>
                  <option value="Paid">Paid</option>
            </select>

            <select class="form-control type2" name="type[]" id="type_sec<?php echo $sr ?>" style="display:none">
                  <option value="Free">Free</option>
                  <option value="Paid">Paid</option>
            </select>
          </td>
          <input type="hidden" name="asset_detail_id[]" value="<?php echo $data->id ?>">
        </tr>
        <?php $sr++; } ?>
   </tbody>
</table>

<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript">
  $(".date").datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      numberOfMonths: 1,
      minDate: 0,
      
    }); 

  $("#select_all").click(function () {

    var chk_all = $(".select_all").is(":checked");
    if(chk_all)
    {
       $('input:checkbox').not(this).prop('checked', this.checked);
       $(".desc1").hide();
       $(".desc2").show();

       $(".date1").hide();
       $(".date2").show();

       $(".type1").hide();
       $(".type2").show();
    }
    else
    {
      $('input:checkbox').not(this).prop('checked', this.checked);
      $(".desc1").show();
       $(".desc2").hide();

       $(".date1").show();
       $(".date2").hide();

       $(".type1").show();
       $(".type2").hide();
    }


 });
</script>
