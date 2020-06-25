
<div class="page-content-wrap"> 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">                                
                    <h3 class="panel-title">Assets List</h3>
                    <h3 class="panel-title"></h3>
                    <h3><span id="successCountryEntry"></span></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Quantity <span style="color:red">*</span> <span style="color:red" id="error3"></span></th>
                            </tr>
                        </thead>
                        <tbody id="asset_table">
                          <?php if(!empty($assets_data)){ $sr=1; foreach($assets_data as $data){ ?>
                          <tr>
                              <td><input type="checkbox" id="checkbox<?php echo $sr; ?>" class="ace" onclick="add_quantity(<?php echo $sr ?>)"></td>
                              <td><input type="hidden" name="asset_id[]" id="asset_id<?php echo $sr; ?>" value="<?php echo $data->id ?>"><?php echo $data->asset_name; ?></td>
                              <td><input type="text" onkeypress="only_number(event)" name="quantity[]" id="quantity<?php echo $sr; ?>" class="form-control" disabled>
                               <input type="text" onkeypress="only_number(event)" name="quantity[]" id="show_quantity<?php echo $sr; ?>" class="form-control qaunt_asst<?php echo $sr; ?>" value="" style="display:none">
                               </td>
                          </tr>
                        <?php ++$sr; } } else { ?>
                           <tr>
                              <td colspan="2">No Record Found</td>
                          </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END DEFAULT DATATABLE -->

<script>
function add_quantity(id)
{
  
      if ($('#checkbox'+id).is(":checked"))
       {
          $("#quantity"+id).hide();
         $("#show_quantity"+id).show();
       }
       else
       {
         $("#quantity"+id).show();
         $("#show_quantity"+id).hide();
       }
      
   
}
</script>
