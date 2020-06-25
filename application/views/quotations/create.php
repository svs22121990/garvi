 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<style type="text/css">
    .bordered{
        margin-bottom: 20px;
        margin-top: 20px;
        border:1px solid #eee;
        width: 100%;
    }
</style>
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
                            
                            <form class="form-horizontal" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                                    <ul class="panel-controls">
                                        <li><a href="<?=site_url('Quotations/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body">                                                                        
                                    
                                      <div class="row">
                                            <div class="col-md-12">
                                              <div class="col-md-3">
                                                <label for="vendor">Quotation No.</label>
                                                <input type="text"  name="quotation_no" value="<?= $quotation_no;?>" class="form-control" readonly><div>&nbsp;</div>
                                              </div> 
                                              <div class="col-md-3">
                                                <label for="quoterequests">Request No<span style="color:red">*</span> <span id="error1" style="color:red"></span></label>
                                                 <select class="form-control select" id="quotation_request_id" name="quotation_request_id" data-live-search="true" onchange="getVendor(this.value)"> 
                                                    <option value="">Select Request No</option>
                                                    <?php foreach($quoterequests as $row){?>
                                                    <option value="<?= $row->id;?>"><?= $row->request_no;?></option>
                                                    <?php }?>
                                                </select>
                                                  &nbsp;&nbsp;
                                              </div>
                                         
                                              <div class="col-md-5" id="vendorData">
                                               
                                              </div>

                                           
                                               <div class="col-md-1">
                                                &nbsp;
                                               </div>
                                             
                                              <div id="assets_list"></div>
                                            </div>
                                            <div class="clearfix">
                                              
                                            </div>
                                            </div>
                                    </div>
                                
                                <div class="panel-footer">
                                    <button class="btn btn-success" type="submit" style="display: none" id="submit" disabled="" ><?= $button;?></button>
                                    <button class="btn btn-success" type="button" onclick="return addValidation();"><?= $button;?></button>
                                    <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>                                    
                                </div>
                            </div>
                            </div>
                            </form>
                        </div>
                    </div>                    
                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript">
    var url="";
    var actioncolumn="";
</script>

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
    function getVendor(id)
    {
      $('.loader').fadeIn();
      if(id!=''){

        $.ajax({
        type:'post',
        url:"<?= site_url('Quotations/getVendor') ?>",
        data:{id:id},
        success:function(response)
        {
          $('.loader').fadeOut();
            var obj=jQuery.parseJSON(response);
            $('#vendorData').html(obj.response);
            $('#assets_list').html('');
            
        }
      });
      }else{
          
          $('.loader').fadeOut();
          $('#vendorData').html('');
          $('#assets_list').html('');
          $('#submit').attr('disabled',true);
      }
    }
 function getData(id,value){
  var chk=$('#'+id).prop("checked");
  var quotation_request_id=$('#quotation_request_id').val();
  $('.loader').fadeIn();
  if(chk==true){
     $.ajax({
        type:'post',
        url:"<?= site_url('Quotations/getDetailsData') ?>",
        data:{vendor_id:value,quotation_request_id:quotation_request_id},
        success:function(response)
        {
            $('.loader').fadeOut();
            $('#assets_list').append(response);
            var name=$('#vendor_name_'+value).text();
            $('#vendorName_'+value).html(name);

            var len=$('.acc').length;
            //alert(len);
           /* if(len > 1){
              $('#totaldiv'+value).hide();
            }*/

            //$('#submit').removeAttr('disabled');

            
        }

      });

  }else{
    $('.loader').fadeOut();
    $('#acc_'+value).remove();
    $('#submit').attr('disabled',true);
  }


 }

 function toggleAcc(id){
   var getClass=$('#accTwoCol'+id).attr('class');
  if(getClass=='panel-body panel-body-open'){
  //$('#accTwoCol'+id).removeClass('panel-body panel-body-open').addClass('panel-body');
  $("#accTwoCol"+id).slideToggle("slow");
  }else{
    //$('#accTwoCol'+id).removeClass('panel-body').addClass('panel-body panel-body-open');
     $("#accTwoCol"+id).slideDown("slow");
  }
 }
</script>
 <script type="text/javascript">    
function addValidation() 
{
  $(".error_msg").html('');
  var quotation_request_id = $("#quotation_request_id").val();
  var vendor = $("#vandor_id").val();
  var requredQty  = $(".requredQty").val();
  //alert(requredQty);
  $("#error1").html('&nbsp;');
  $("#error2").html('&nbsp;');
  var count = $('#asset_table tr').length;
  if(quotation_request_id=="")
  {    
    $("#error1").fadeIn().html("Please select request no");
    setTimeout(function(){ $("#error1").html('&nbsp;'); }, 3000);
    $("#asset_type_id").focus();
    return false; 
  }

  var a=0;
$(".select_vendor").each(function(){
  if($(this).prop("checked")==true){
    a=1;
  }
});
  if(a==0)
  {    
    $("#error2").fadeIn().html("Please select vendor");
    setTimeout(function(){ $("#error2").html('&nbsp;'); }, 3000);
    $(".select_vendor").focus();
    return false; 
  }
  
  var ttlqty=0;
  $('.ttlqtyval').each(function(){
    var qty=$(this).val();
    ttlqty=parseFloat(ttlqty)+parseFloat(qty);
  });

  $('.totalQtyAll').val(ttlqty);

  if(ttlqty > requredQty){
    $(".errQty").fadeIn().html("Total Quantity should be less or equals of Required Qty").css('color','red');
    $(".totalQtyAll").css('border-color','red');
    setTimeout(function(){ $(".errQty").html('&nbsp;');$(".totalQtyAll").css('border-color','black'); }, 5000);
    $(".totalQtyAll").focus();
    return false;
  }

   var err=0;
    var errqty=0;
    var errmrp=0;
    var errp=0;
  $('.vids').each(function(){
    var val=$(this).val();
    //alert(val);
  $('.qunatity_'+val).each(function(){
      var id=$(this).attr('id');
      var quantity=$(this).val();
           
      if(quantity=='0' || quantity==''){
        $("#qerror2"+val).fadeIn().html("Required");
        setTimeout(function(){ $("#qerror2"+val).fadeOut(); }, 3000);
        $('#'+id).focus();
        errqty=1;
        return false;
        }
     });

     $('.mrp_'+val).each(function(){
      var id=$(this).attr('id');
      
      var mrp=$(this).val();
           
      if(mrp=='0' || mrp==''){
        $("#merror2"+val).fadeIn().html("Required");
        setTimeout(function(){ $("#merror2"+val).fadeOut(); }, 3000);
        $('#'+id).focus();
        errmrp=1;
        return false;
        }
     });

    $('.per_unit_price_'+val).each(function(){
      var id=$(this).attr('id');
      
      var per_unit_price=$(this).val();
           
      if(per_unit_price=='0' || per_unit_price==''){
        $("#perror3"+val).fadeIn().html("Required");
        setTimeout(function(){ $("#perror3"+val).fadeOut(); }, 3000);
        $('#'+id).focus();
        errp=1;
        return false;
        }
     });

  });

if(errqty==1){
    return false;
  }
if(errmrp==1){
    return false;
  }
if(errp==1){
    return false;
  }
 $('#submit').removeAttr('disabled').click();
}
</script> 

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

function remove_tr(row,id,vid,aid,sr)
  {  

    var y=document.getElementById('clonetable_feedback_'+vid);
    var len = y.rows.length;
    if(len>2)
    {
     var result = confirm("Do you really want to delete?");
    if (result) {
        var i= (len-1);
        document.getElementById('clonetable_feedback_'+vid).deleteRow(row);
        getRemainingqty(id,vid,aid,sr);
      }
    }
  } 

function getRemainingqty(id,vid,aid,sr){
  //alert(id);
  var originqty=$('#originqty'+id).val();
  //alert(originqty);
  var qty=$('#qunatity_'+id).val();
  if(qty!=''){

  var same=0;
 var len= $('.originqty_'+aid).length;
//alert(len);
if(len > 1 ){
 var orgval=0;
 var totalval=0;
    $('.originqty_'+aid).each(function(){
        orgval= $(this).val();
        inptotal=0
        $('.qunatity_'+aid+'-'+sr).each(function(){
          var inputqty=$(this).val();
          inptotal=parseFloat(inptotal)+parseFloat(inputqty);
        });
    totalval=parseFloat(inptotal);
  });
  
  if(totalval > orgval){
      same=1;
    }

}

if(same==1){
   $("#qerror2"+vid).fadeIn().html("Quantity of same asset can not be greater").css('color','red');
    setTimeout(function(){ $("#qerror2"+vid).html('&nbsp;'); }, 5000);
    $('#qunatity_'+id).focus();
    $('#qunatity_'+id).val('');
    return false;
}


  if(parseInt(qty) > parseInt(originqty)){
     $("#qerror2"+vid).fadeIn().html("Quantity shuld be less than or equals of available qty");
        setTimeout(function(){ $("#qerror2"+vid).fadeOut(); }, 3000);
        $('#qunatity_'+id).focus();
        $('#qunatity_'+id).val(originqty);
        $('#remainQty'+id).text('(0)');
        amount(id,vid);
        return false;
  }else{

    var remain=parseInt(originqty)-parseInt(orgval);
    $('#remainQty'+id).text('('+remain+')');
    if(len > 1 ){
          var remain=parseInt(totalval)-parseInt(qty);
          $('#remainQty'+id).text('('+remain+')');
      }
     amount(id,vid);
     var ttl=0;
     $('.qunatity_'+vid).each(function(){
        var val=$(this).val();
        ttl=parseFloat(ttl)+parseFloat(val);
     });
     //alert(ttl);
     $('#ttlqty'+vid).text(ttl);
     $('#ttlqtyval'+vid).val(ttl);
  }



 }else{
       $("#qerror2"+vid).fadeIn().html("Required");
        setTimeout(function(){ $("#qerror2"+vid).fadeOut(); }, 3000);
        $('#qunatity_'+id).focus();
        $('#remainQty'+id).text('(0)');
        return false;
 }
}

function amount(id,vid){
  var qty=$('#qunatity_'+id).val();
  var pup=$('#per_unit_price_'+id).val();
  var total=(parseInt(qty))*(parseInt(pup));
  $('#amount_'+id).val(total);

}

function onlyimage(vid){
  //var files = this.files;   
   // var reader = new FileReader();
    name=$('#vendor_quote_copy_'+vid).val();   
    //alert(name);
    //validation for photo upload type    
    var filetype = name.split(".");
  ext = filetype[filetype.length-1];  //alert(ext);
    if(!(ext=='jpg') && !(ext=='png') && !(ext=='PNG') && !(ext=='jpeg') && !(ext=='img') && !(ext=='JPEG') && !(ext=='JPG') && !(ext=='PDF') && !(ext=='pdf'))
    { 
    $("#errorimage"+vid).html("Please select pdf or image file").css('color','red');   
    setTimeout(function(){$("#errorimage"+vid).html("&nbsp;")},3000);
    $("#vendor_quote_copy_"+vid).val("");
    //return false;
    }
    //reader.readAsDataURL(files[0]);

}
</script>
