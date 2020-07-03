 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<style type="text/css">
    div.scrollable {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: auto;
}
</style>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                         <div class="row">
                            <div class="clearfix">&nbsp;</div>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                            <form method="post" action="<?php echo site_url('Assets/assetstransfer_action/'.$asset_id) ?>">
                                <div class="panel-heading">
                                    <h3 class="panel-title"> <?php echo $asset_name ?><strong> <span id="assetName"></span>  (Remaining Asset Stock  <span ><?php echo $asset_quantity_count ?></span>)</strong></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Assets/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                 <div class="panel-body panel-body-table">
                                    <div class="row">
                                              <div class="col-md-12">&nbsp;</div>
                                            <div class="col-md-6">
                                             <label>Branch:</label> <span style="color: red">*</span> <span style="color:red" id="branchError"></span><br>
                                             <select class="form-control select filter_search_data2" data-live-search="true" name="branch_id" id="branch_id" >
                                               <option value="">--Select Branch--</option>
                                               <?php foreach($branch_data as $branch_dataRow) { ?>
                                                    <option value="<?php echo $branch_dataRow->id ?>"><?php echo $branch_dataRow->branch_title ?></option>
                                               <?php }?>
                                              
                                             </select><!-- <br> -->
                                            
                                           </div>     
                                            <div class="col-md-6">
                                             <label>Mode Of Transport:</label> <span style="color: red">*</span>  <span style="color:red" id="meanError"></span><br>
                                             <select class="form-control select" data-live-search="true" name="mode_of_transport" id="mode_of_transport">
                                              <option value="">--Mode Of Transport--</option>
                                              <option value="Travel">Travel</option>
                                              <option value="Transport">Transport</option>
                                              <option value="Courier">Courier</option>
                                              <option value="By Hand">By Hand</option>
                                              
                                             </select><!-- <br> -->
                                            
                                           </div> 
                                            <div class="col-md-12">&nbsp;</div>
                                            <div class="col-md-6">
                                             <label>Transport Detail:</label> <span style="color: red">*</span> <span style="color:red" id="transportError"></span><br>
                                             <textarea class="form-control" name="transport_detail" id="transport_detail"></textarea>
                                             
                                           </div>       
                                           
                                            <div class="col-md-12">&nbsp;</div>
                                           <div class="col-md-12 table-responsive" id="assetData">
                                                <h3>In Stock Assets <small></small>
                                               </h3>
                                                  <table class="table table-bordered table-striped table-actions example_datatable">
                                                        <thead>
                                                             <tr>
                                                                <th><input type="checkbox" id="select_all" class="select_all"> </th>
                                                                <th>Sr</th>                           
                                                                <th>Product SKU</th>                  
                                                                <th>Image</th>
                                                              </tr>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                        <tfoot>
                                                          <tr>

                                                              <th colspan="3">
                                                              <input type="text" class="append_ids" style="display: none;">
                                                              </th>
                                                                                    
                                                          </tr>
                                                      </tfoot>
                                                  </table> 
                                             </div>
                                          
                                            
                                          </div>
                                       <br><br>
                                        
                                </div>
                                <div class="panel-footer">                                                                       
                                  <button class="btn btn-success" type="submit" onclick="return validateinfo()">Transfer</button>
                                </div>
                             
                            </div>                                                

                        </div>
                    </div>
                    <!-- END RESPONSIVE TABLES -->    

                    <div></div>

                </div>
                <!-- END PAGE CONTENT WRAPPER -->
<input type="text" name="asset_details_id" id="selected_client" class="filter_search_data1" style="display: none;"> 
   </form>
<script type="text/javascript">
  var url="<?= site_url('Assets/getAssetTransferAjax/'.$asset_id); ?>";
  var actioncolumn="3";
</script>

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
  

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
<script type="text/javascript">
function validateinfo() 
{           
  var branch_id = $("#branch_id").val();
  var mean_of_transport = $("#mode_of_transport").val();
  var transport_detail = $("#transport_detail").val();
 
 if(branch_id=='')
  {
       $("#branchError").html("Required").fadeIn();
      setTimeout(function(){$("#branchError").fadeOut()},5000);
      /* $("#branch_title").focus().css('border',"2px solid red");
      setTimeout(function(){$("#branch_title").css("border-color", "#ccc");},6000);  */            
      return false;
  }
   if(mean_of_transport=='')
  {
       $("#meanError").html("Required").fadeIn();
      setTimeout(function(){$("#meanError").fadeOut()},5000);
       /*$("#address").focus().css('border',"2px solid red");
      setTimeout(function(){$("#address").css("border-color", "#ccc");},6000); */             
      return false;
  }

  if(transport_detail=='')
  {
       $("#transportError").html("Required").fadeIn();
      setTimeout(function(){$("#transportError").fadeOut()},5000);
       /*$("#pincode").focus().css('border',"2px solid red");
      setTimeout(function(){$("#pincode").css("border-color", "#ccc");},6000);*/              
      return false;
  }

   if($('input[type=checkbox]:checked').length == 0)
  {
    alert("Please select atleast one check box"); return false;
  }
 }
</script>



 
