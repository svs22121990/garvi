<div class="panel-body"> 
    <table class="table table-bordered table-responsive">
        <thead>
            <th>Sr</th>
            <th>Assets</th>
            <th>Quantity</th>
            <th>MRP <span style="color:red">*</span><span style="color:red" id="error2"></span></th>
            <th>Per Unit Price <span style="color:red">*</span><span style="color:red" id="error3"></span></th>

            <th>Amount</th>
        </thead>
        <tbody id="clonetable_feedback">
            <?php
            $sr=1;
             foreach($vendor_data as $data)
             {
             ?>
            <tr>
                <td><?php echo $sr; ?></td>
                <td><?php echo $data->asset_name ?></td>
                <td>
                   <input type="text" onkeypress="only_number(event)" id="quantity<?php echo $sr; ?>"  value="<?php echo $data->quantity ?>" class="qunatity_<?php echo $data->id ?>">
                 <!--  <?php echo $data->quantity ?>  -->
                </td>
                <td>
                    
                    <input class="form-control" onkeypress="only_number(event)" id="mrp<?php echo $sr; ?>" type="text" name="mrp[]" maxlength="5" placeholder="MRP">
                </td>
                <td>
                     <input onkeypress="only_number(event)" class="per_unit_price_<?php echo $data->id ?> form-control" id="per_unit_price<?php echo $sr; ?>" type="text" name="per_unit_price[]" maxlength="5" placeholder="Per Unit Price" oninput="return amount(<?php echo $data->id; ?>)">
                </td>
                <td>
                    <input type="hidden" value="<?php echo $data->id ?>" name="id[]">
                    <input  class="amount_<?php echo $data->id ?> form-control" onkeypress="only_number(event)" id="amount<?php echo $sr; ?>" type="text" name="amount[]" maxlength="5" value="" placeholder="Amount" readonly>
                </td>
            </tr>
        <?php ++$sr; } ?>
        </tbody>
    </table> 
</div>

<script>
function only_number(event)
{
  var x = event.which || event.keyCode;
  console.log(x);
  if((x >= 48 ) && (x <= 57 ) || x == 8 | x == 9 || x == 13 )
  {
    return;
  }else{
    event.preventDefault();
  }    
}

</script>
