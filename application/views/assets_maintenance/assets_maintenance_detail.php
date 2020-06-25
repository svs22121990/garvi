
<table class="table table-bordered table-striped table-actions example_datatable1">
    <thead>
         <tr>
                                                           
            <th><input type="checkbox" id="select_all" class="select_all assets_chk"> </th>
            <th>Sr</th>                                                  
            <th>Product SKU</th>                                                  
            <th>Image</th>  
            <?php if($_SESSION[SESSION_NAME]['type']=='Admin') { ?>                        
            <th>Type</th>   
            <?php } ?>                        
            <th>Action</th>                           
        </tr>
    </thead>
    <tbody>
        
    </tbody>
     <tfoot>
    <tr>

        <th colspan="4">
        <input type="text" class="append_ids" style="display: none;">
        </th>
                              
    </tr>
</tfoot>
</table>
<input type="text" name="asset_details_id" id="selected_client" class="filter_search_data1" style="display: none;"> 
<script type="text/javascript">
    var url1="<?= site_url('Assets_maintenance/getAssetdataAjax/'.$id); ?>";
    <?php if($_SESSION[SESSION_NAME]['type']=='Admin') { ?>
    var actioncolumn1="4";
    <?php } else { ?>
        var actioncolumn1="3";
    <?php } ?>

     setInterval(function(){ 
        uni_array(); 
    }, 3000);
   
    function uni_array(){

      var chk_all = $(".select_all").is(":checked");
      // console.log(chk_all);
      if(chk_all == true)
      {
        var ids = $(".append_ids").val();
        $("#selected_client").val(ids);
      }
        var strVale = $("#selected_client").val();
        var arr = strVale.split(',');
        var arr1 = Array.from(new Set(arr));
        // console.log(arr1);
        $("#selected_client").val(arr1);
    }

    function remove_data(remove_val){
    var array_val1 = $("#selected_client").val();
    
    var difference = [];
    var array_val = array_val1.split(",");
   
    for( var i = 0; i < array_val.length; i++ ) { //console.log(remove_val);
        // if( $.inArray( array_val[i], remove_val ) == -1 ) {
        if(array_val[i] != remove_val) {
            // console.log(array_val[i]);
                difference.push(array_val[i]);
        }
    }
    return difference;
}  
function checkbox_all(id) 
{
    // $('#myCheckbox').prop('checked', false);  
   
    var myarray = new Array();
    myarray.push($("#selected_client").val());
    var checkbox_all = $("#client_id_"+id).is(":checked");
    // console.log(checkbox_all);

    if(checkbox_all==true)
    {
        if(myarray=='')
        {
            myarray[0]=($("#client_id_"+id).val());
        }else
        {
            // if(jQuery.inArray($("#client_id_"+id).val(), $("#selected_client").val()) !== -1)
            myarray.push($("#client_id_"+id).val());
        }
        $("#client_id_"+id).attr('name', 'clients[]');        
    }
    else
    {$("#select_all").attr('checked',false);
        var remove_val = $("#client_id_"+id).val();
        //removeA(myarray, $("#lead_ids"+id).val()); 
        var new_arr = remove_data(remove_val);
        myarray = new_arr;
        $("#client_id_"+id).attr('name', 'YeNhiJayega');  
        $("#client_id_"+id).attr('name', 'YeNhiJayega');  
    }
    // console.log(myarray);
    $("#selected_client").val(myarray);
   
}

$('#select_all').click(function(){
     var checkbox_all = $(this).is(":checked");
     if(checkbox_all==true)
        { 
            table.draw();
        }else{
            $('#selected_client').val('');
             table.draw();
        }


});


</script>