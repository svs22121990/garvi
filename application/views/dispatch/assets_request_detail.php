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
                         <form method="post" action="<?php echo site_url('Assets_request/approvedAssets_action/'.$request_id) ?>" id="assets_request">
                                <div class="panel-heading">
                                    <h3 class="panel-title"> <b><?php echo $heading ?></b></strong></h3>&nbsp;&nbsp;
                                     <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span><center><span id="error1" style="color: red"></span></center></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Assets_request/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                 <div class="panel-body panel-body-table">
                                    <div class="row">
                                              <div class="col-md-12">&nbsp; </div>
                                             <div class="col-md-12">&nbsp; </div>
                                             <div class="col-md-12">&nbsp; </div>
                                             <br>
                                           <div class="col-md-12 table-responsive" id="assetData">
                                             <table class="table table-bordered table-striped table-actions example_datatable">
                                                        <thead>
                                                          <tr>
                                                            <th><?php if(!empty($assets_request_details)) { ?><input type="checkbox" id="select_all" class="select_all"><?php } ?> </th>
                                                              
                                                            <th>Asset Name</th>
                                                            <th>Quantity</th>
                                                            <th>Description </th>
                                                            <th>Status</th> 
                                                           </tr>
                                                        <thead>
                                                        <tbody id="clonetable_feedback">
                                                          
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
                                        <input type="text" name="asset_details_id" id="selected_client" class="filter_search_data1" style="display: none;">
                                   </div>
                                   <?php if(!empty($assets_request_details)) { ?>
                                  <div  class="panel-footer">                                                                       
                                    <button class="btn btn-success"  type="submit">Approve Assets</button>
                                 </div>
                                 <?php } ?>
                             </div>   
                             </form>         
                            </div>
                          </div>
                        <div>
                      </div>
                  </div>
   
<script type="text/javascript">
  var url="<?= site_url('Assets_request/ajax_request_detail/'.$request_id); ?>";
  var actioncolumn="4";
</script>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
      function checkStatus(id)
      {
        $("#statusId").val(id);
        $("#deleteId").val(id);
      }

      $(document).ready(function(){
        $(".preloader").show();
      })
</script>

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

           $('.chk_quantity').show();

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
     if($('.client_id_'+id).is(":checked"))
    {
      $('.quantity'+id).show();

    }
    else
    {
      $('.quantity'+id).hide();
      $('.quantity'+id).val('');
    }
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
       ;
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


$('#assets_request').submit(function(event){
      
      $('.client_id').each(function(){
      if(this.checked)
      {
        var id = $(this).val();
        var qtyval = $(".quantity"+id).val();
        var hqty = $(".hidden_qty_"+id).val();
        
        if(qtyval=='')
        {
          $("#error1").html("Required").fadeIn();
          setTimeout(function(){$("#error1").fadeOut()},3000);
          $(".quantity"+id).focus();
          event.preventDefault();
          return false;
        }
        if($.trim(qtyval) == 0)
        {
          
          $("#error1").html("Approved quantity should be greater than zero").fadeIn();
          setTimeout(function(){$("#error1").fadeOut()},3000);
          $(".quantity"+id).focus();
          event.preventDefault();
          return false;
        }
        if(parseFloat(qtyval) > parseFloat(hqty))
        {
          $("#error1").html("Approved quantity should be less than quantity").fadeIn();
          setTimeout(function(){$("#error1").fadeOut()},3000);
          $(".quantity"+id).focus();
          event.preventDefault();
          return false;
        }
      }
      
   });

    if($('input[type=checkbox]:checked').length == 0)
    {
      alert("Please select atleast one check box"); return false;
    }
}) 


function getData(id)
{ 
  var site_url = $("#site_url").val();
  var url = site_url+"/Assets_request/getData"; 
  $.ajax({
      type:"post",
      url:url,
      data : { id : id},
      cache:false,
      success:function(returndata)
      {
        var obj = JSON.parse(returndata);
        $("#value").html(obj.description);
        $("#title").html(obj.title);
     }
   });
}

      function checkStatus(id)
      {
        $("#statusId").val(id);
        $("#deleteId").val(id);
      }
 
 
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
<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Assets_request/changeStatus') ?>">       
                <div class="modal-body" style="height: 100px;padding-top: 10%">
                    <center>
                        <input type="hidden" name="id" id="statusId" style="display: none;">
                        <span style="font-size: 16px">Are you want  to <b style="color: red">Reject</b> this asset request?</span>
                    </center>
                </div>
                <div class="modal-footer" >
                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title"></h4>
      </div>
      <div class="modal-body" id="value">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



 
