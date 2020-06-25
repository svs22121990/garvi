 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>

<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                                    <ul class="panel-controls">
                                        <li><a href="<?=site_url('Purchase_returns/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body">           

                                    <div class="row">
                                  

                                        <div class="panel panel-default">                    
                                          <div class="">
                                            <div class="table-responsive">
                                              <table id="example" class="table table-border">
                                                <thead>
                                                  <tr>
                                                    <th>Vendor</th>
                                                    <th>PO Number</th>
                                                    <th>Quantity</th>
                                                    <th>PO Date</th>
                                                    <th>Status</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <tr>
                                                    <td><?= $prr->name.' ('.$prr->shop_name.')'; ?></td>
                                                    <td><?= $prr->order_number; ?></td>
                                                    <td><?= $prr->quantity; ?></td>
                                                    <td><?= date('d-m-Y',strtotime($prr->purchase_date)); ?></td>
                                                    <td>
                                                       <?php if($prr->status == 'Pending'){ ?>
                                                      <button class="btn btn-danger btn-sm" style="cursor:default">Pending</button>
                                                      <?php } elseif($prr->status == 'Approved') { ?>
                                                      <button class="btn btn-success btn-sm" style="cursor:default">Approved</button>
                                                      <?php } else { ?>

                                                      <button class="btn btn-danger btn-sm" style="cursor:default">Rejected</button>
                                                      <?php } ?>
                                                    </td>
                                                  </tr>                          
                                                </tbody>
                                              </table>  
                                            </div>  
                                          </div> 
                                        </div>  

                                         <div class="panel panel-default tabs">
                                              <ul class="nav nav-tabs nav-justified">
                                                  <li class="active"><a data-toggle="tab" href="#tab8" aria-expanded="true">Return 
                                                <span class="badge badge-danger pull-right"><?=$returnCount?></span></a></li>
                                                  <li class=""><a data-toggle="tab" href="#tab9" aria-expanded="false">Replace  <span class="pull-right badge badge-danger" ><?=$replaceCount?></span></a></li>
                                              </ul>
                                              <div class="panel-body tab-content">
                                          <span id="errVal"> &nbsp;</span>
                                                  <div id="tab8" class="tab-pane active">
                                                <form method="post" action="<?=site_url('Purchase_returns/approveReturn/'.$id);?>">
                                                       <div class="table-responsive">
                                                          <table id="" class="table table-striped table-border example_datatable1">
                                                            <thead>
                                                              <tr>
                                                                <th width="5%"><input type="checkbox" id="select_all1" class="select_all1"></th>
                                                                <th width="30%">Asset name</th>
                                                                <th width="20%">Product SKU</th>
                                                                <th width="30%">Remark</th>
                                                                <th width="10%">Status</th>
                                                                <th width="5%">Type</th>
                                                              </tr>
                                                            </thead>
                                                            <tbody>
                                                                                    
                                                            </tbody>
                                                              <tfoot>
                                                                <tr>
                                                                    <th colspan="5>
                                                                    <input type="text" class="append_ids1" style="display: none;">
                                                                    </th>
                                                                                          
                                                                </tr>
                                                           </tfoot>
                                                          </table>  
                                                           <input type="text" name="returnCheck" id="selected_client1" class="filter_search_data1" style="display: none;">
                                                           <input type="hidden" name="order_number" value="<?=$prr->order_number?>">
                                                           <?php if($returnCount > 0) { ?>
                                                           <select name="status" id="returnStatus">
                                                             <option value=""> Select Action</option>
                                                             <option value="Approve"> Approve </option>
                                                             <option value="Reject"> Reject</option>
                                                           </select>
                                                           <button type="submit" class="btn btn-success" onclick="return validate('Return')"> Submit </button>
                                                          <?php } ?>
                                                        </div> 
                                                 </form>
                                                  </div>
                                                  <div id="tab9" class="tab-pane">
                                                 <form method="post" action="<?=site_url('Purchase_returns/approveReplace/'.$id);?>">
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-striped table-border example_datatable text-left">
                                                          <thead>
                                                            <tr>
                                                              <th width="5%"><input type="checkbox"  id="select_all" class="select_all"></th>
                                                              <th width="30%">Asset name</th>
                                                              <th width="20%">Product SKU</th>
                                                              <th width="30%">Remark</th>
                                                              <th width="10%">Status</th>
                                                              <th width="5%">Type</th>
                                                            </tr>
                                                          </thead>
                                                          <tbody>
                                                                                  
                                                          </tbody>
                                                          <tfoot>
                                                                <tr>
                                                                    <th colspan="5">
                                                                    <input type="text" class="append_ids" style="display: none;">
                                                                    </th>
                                                                                          
                                                                </tr>
                                                           </tfoot>
                                                        </table>  
                                                         <input type="text" name="replaceCheck" id="selected_client" class="filter_search_data" style="display: none;">
                                                          <?php if($replaceCount > 0) { ?>
                                                           <select name="status" id="replaceStatus">
                                                             <option value=""> Select Action</option>
                                                             <option value="Approve"> Approve </option>
                                                             <option value="Reject"> Reject</option>
                                                           </select>
                                                           <input type="hidden" name="order_number" value="<?=$prr->order_number;?>">
                                                           <button type="submit" class="btn btn-success" onclick="return validate('Replace')"> Submit </button>
                                                        <?php } ?>
                                                      </div> 
                                                  </form>                     
                                                  </div>
                                              </div>
                                          </div>
                                      </div>  
                                    </div>   
                               </div>
                            </div>                            
                        </div>
                </div>
                <!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript">
    var url1="<?=site_url('Purchase_returns/AjaxReturn/'.$id);?>";
    var actioncolumn1="5"; 

    var url="<?=site_url('Purchase_returns/AjaxReplace/'.$id);?>";
    var actioncolumn="5";

    function validate(title){
      if(title=='Return'){
        var selectedval=$('#selected_client1').val();
        var status=$('#returnStatus').val();
        var id='returnStatus';
      }else{

        var selectedval=$('#selected_client').val();
        var status=$('#replaceStatus').val();
        var id='replaceStatus';
      }

      if(selectedval==''){
        $('#errVal').fadeIn().html('Please select alteast one checkbox').css('color','red');
        setTimeout(function(){ $('#errVal').html('&nbsp;');},3000);
        return false;
      }
       if(status==''){
        $('#errVal').fadeIn().html('Please select action').css('color','red');
        $('#'+id).css('border-color','red');
        setTimeout(function(){ $('#errVal').html('&nbsp;');$('#'+id).css('border-color','#000');},3000);
        $('#'+id).focus();
        return false;
      }
      $('.loader').fadeIn('fast');
    }

</script>
<script type="text/javascript">
  <?php 
$controller = $this->uri->segment(1);
$function = $this->uri->segment(2);
if(!empty($controller) && !empty($function)){
  if($controller == "Purchase_returns" && $function=="approveReturnReplace" )
  {
    $show = "show";
    $len = 10;
  }
  }else{
    $len = 10;
    $show = "";
    } ?>


</script>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
     table1 = $('.example_datatable1').DataTable({ 
              "oLanguage": {
              "sProcessing": "<img src='<?= base_url()?>assets/img/loaders/default.gif'>" 
        },
        
           
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "lengthMenu" : [[10,25, 100,200,500,1000,2000], [10,25, 100,200,500,1000,2000 ]],"pageLength" : 10,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url1,
                "type": "POST",
                 "data": function(d) {
                        d.select_all1 = $(".select_all1").is(":checked");
                        d.SearchData = $(".filter_search_data").val();
                        d.SearchData1 = $(".filter_search_data1").val();
                        d.SearchData2 = $(".filter_search_data2").val();
                        d.FormData = $(".filter_data_form").serializeArray();
                    }
                     
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,actioncolumn1 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],
            
            <?php if(!empty($show)){ ?>
                    "fnDrawCallback": function() {
                    var api = this.api()
                    var json = api.ajax.json();
                   $(".append_ids1").val(json.ids1);
                    uni_array1(); 
                 
                  },
                  <?php } ?> 
                  
        });

   
</script>
<script type="text/javascript">
  
     setInterval(function(){ 
        uni_array(); 
    }, 3000);

    setInterval(function(){ 
        uni_array1(); 
    }, 3000);
   
    function uni_array1(){

      var chk_all = $(".select_all1").is(":checked");
      // console.log(chk_all);
      if(chk_all == true)
      {
        var ids = $(".append_ids1").val();
        $("#selected_client1").val(ids);

      }
        var strVale = $("#selected_client1").val();
        var arr = strVale.split(',');
        var arr1 = Array.from(new Set(arr));
        //console.log(arr1);
        $("#selected_client1").val(arr1);
    }

    function remove_data1(remove_val){
    var array_val1 = $("#selected_client1").val();
    
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
function checkbox_all1(id) 
{ 

    var myarray = new Array();
    myarray.push($("#selected_client1").val());
    var checkbox_all = $("#client_id1_"+id).is(":checked");
    // console.log(checkbox_all);

    if(checkbox_all==true)
    {
        if(myarray=='')
        {
            myarray[0]=($("#client_id1_"+id).val());

        }else
        {
            
            myarray.push($("#client_id1_"+id).val());
        }
        $("#client_id1_"+id).attr('name', 'clients[]');     

    }
    else
    {

      $("#select_all1").attr('checked',false);
        var remove_val = $("#client_id1_"+id).val();
        var new_arr = remove_data1(remove_val);
        myarray = new_arr;
       $("#client_id1_"+id).attr('name', 'YeNhiJayega');  
    
    }
    // console.log(myarray);
    $("#selected_client1").val(myarray);

}

$('#select_all1').click(function(){

     var checkbox_all = $(this).is(":checked");
     if(checkbox_all==true)
        { 
            table1.draw();
        }else{
            $('#selected_client1').val('');
             table1.draw();
        }


});

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

    var myarray = new Array();
    myarray.push($("#selected_client").val());
    var checkbox_all = $("#client_id2_"+id).is(":checked");
    // console.log(checkbox_all);

    if(checkbox_all==true)
    {
        if(myarray=='')
        {
            myarray[0]=($("#client_id2_"+id).val());

      }else
        {
            // if(jQuery.inArray($("#client_id_"+id).val(), $("#selected_client").val()) !== -1)
            myarray.push($("#client_id2_"+id).val());
        }
        $("#client_id2_"+id).attr('name', 'clients[]');     



    }
    else
    {
      $("#select_all").attr('checked',false);
        var remove_val = $("#client_id2_"+id).val();
       var new_arr = remove_data(remove_val);
        myarray = new_arr;
        $("#client_id2_"+id).attr('name', 'YeNhiJayega');  
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