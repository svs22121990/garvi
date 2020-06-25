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
       <form class="form-horizontal" method="post" action="<?php echo site_url('Quotations/update_action/'); ?>" enctype="multipart/form-data">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading">
                    <div class="row">
                         
                            <h3 class="panel-title"><strong>Quotation No: <?php echo $quotation_data[0]->quotation_no; ?></strong> ( <small>Request No: <?php echo $quotation_data[0]->request_no; ?></small> )</h3>
                            <input type="hidden" name="quotation_id" value="<?php echo $quotation_data[0]->id ?>">
                            <input type="hidden" name="quotation_request_id" value="<?php echo $quotation_data[0]->quotation_request_id ?>">
                      
                        <div class="pull-right">
                            <ul class="panel-controls">
                                <li>
                                    <a href="<?php echo site_url('Quotations/index') ?>">
                                        <span class="fa fa-arrow-left"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                   </div>
            </div>
          <div class="col-md-12 panel-body">
        <?php 
        $no=1;
        foreach($quotation_data as $data){ 
             $cond="qt.vendor_id='".$data->vendor_id."' and qt.quotation_id='".$quotation_data[0]->id."'";
             $vendor_data=$this->Quotations_model->GetVendorQuotData($cond);
             
             $vendorQuoteNo=$vendor_data[0]->vendor_quotation_no;
             $vendor_quote_copy=$vendor_data[0]->vendor_quote_copy;

        ?>
        
        <div class="col-md-12">  
          <input type="hidden" name="vendor_id[]" class="vids" value="<?=$data->vendor_id; ?>">
            <div class="panel panel-default">
               <div class="panel-heading">
                    <h4>Vendor : <?php echo $data->vendor_name ?> <small>(<?=$data->shop_name?>)</small> <?php if ($no==1) { ?>
                                        <small class="pull-right text-right"> <div class="text col-md-12" id="totaldiv<?=$data->vendor_id; ?>" style="color:black">
                                                <label class="col-md-3"> Required Qty :</label>
                                                <input type="text" readonly="" value="<?php echo $data->totalqty; ?>" id="requredQty<?=$data->vendor_id; ?>" class="requredQty col-md-3">
                                                <label class="col-md-3"> Total Qty :</label>
                                                <input type="text" readonly="" name="totalQtyAll" value="<?php echo $data->totalqty-$data->remain_qty; ?>"  class="totalQtyAll col-md-3">

                                                <input type="hidden" name="requredQty" value="<?php echo $data->totalqty; ?>" id="orgtotalqty">
                                                 <div class="col-md-12">
                                                     <span class="errQty">&nbsp;</span>
                                                </div>
                                            </div></small>
                    <?php } ?></h4>
                    </div> 
               <div class="col-md-12">
                <div class="clearfix">&nbsp;</div>
               <div class="col-md-6">Vendor Quote No : <input type="text" name="<?=$data->vendor_id?>_vendor_quotation_no"  value="<?=$vendorQuoteNo?>" placeholder="Vendor quotation no" class="form-control"></div>
               <div class="col-md-5"> 
               Vendor Quotation copy <span id="errorimage<?=$data->vendor_id?>">&nbsp;</span>
                  <input type="file" id="vendor_quote_copy_<?=$data->vendor_id?>" name="<?=$data->vendor_id?>_vendor_quote_copy" class="form-control" onchange="onlyimage('<?=$data->vendor_id?>');">
                  <p><small class="text-danger">File as type of Image(jpg,png)/Pdf.</small></p>
                </div>
                <div class="col-md-1"> 
                <?php if(!empty($vendor_quote_copy)){ ?>
                  <span>Download</span>
                <a href="<?=base_url('uploads/quotationcopy/'.$vendor_quote_copy);?>" download><i class="fa fa-download"></i></a>
                <input type="hidden" name="<?=$data->vendor_id?>_oldCopy" value="<?=$vendor_quote_copy; ?>">
                <?php } ?>
               </div>
              </div>
               <div class="panel-body"> 
                    
                                     <table class="table table-bordered table-responsive">
                                        <thead>
                                            <th>Sr</th>
                                            <th>Asset Type</th>
                                            <th>Assets</th>
                                            <th>Quantity <span style="color:red">*</span><span style="color:red" id="qerror2<?=$data->vendor_id?>"></span></th>
                                            <th>Per Unit MRP <span style="color:red">*</span><span style="color:red" id="merror2<?=$data->vendor_id?>"></span></th>
                                            <th>Per Unit Price <span style="color:red">*</span><span style="color:red" id="perror3<?=$data->vendor_id?>"></span></th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody id="clonetable_feedback_<?=$data->vendor_id?>">
                                        <?php 
                                        $ttlQty=0;
                                        $overAllRemaining=0;
                                         $sr=1;
                                        foreach ($vendor_data as $row ) {
                                         $ttlQty=$ttlQty+$row->quantity;
                                           
                                        //print_r($row);
                                        
                                        ?>
                                         <tr>
                                                <td><?php echo $sr; ?></td>
                                                <td><?php echo $row->type ?>
                                                    <input type="hidden" name="<?=$row->vendor_id?>_asset_type_id[]" value="<?=$row->asset_type_id; ?>">
                                                </td>
                                                <td><?php echo ucfirst($row->asset_name); ?>
                                                    <input type="hidden" name="<?=$row->vendor_id?>_asset_id[]" value="<?=$row->asset_id; ?>">
                                                    
                                                </td>
                                                <td>
                                                   <input name="<?=$row->vendor_id?>_quantity[]" type="text" onkeypress="only_number(event)" id="qunatity_<?php echo $row->id ?>"  value="<?php echo $row->quantity ?>" class="form-control qunatity_<?php echo $row->vendor_id ?> qunatity_<?=$row->asset_id.'-'.$sr; ?>" oninput="return getRemainingqty('<?php echo $row->id; ?>','<?=$row->vendor_id?>','<?=$row->asset_id; ?>','<?= $sr; ?>')">
                                                   <input type="hidden" name="<?=$row->vendor_id?>_originqty[]" id="originqty<?=$row->id; ?>" value="<?=$row->origin_qty?>" class="originqty_<?=$row->asset_id?>">
                                                  <small>Required Qty<span class="text-success"> <b>(<?=$row->origin_qty?>)</b></span></small>


                                                  <input type="hidden" name="<?=$row->vendor_id?>_quotation_request_detail_oldQty[]" value="<?=$row->quantity; ?>">
                                                </td>
                                                <td>
                                                    
                                                    <input class="form-control mrp_<?php echo $row->vendor_id ?>" onkeypress="only_number(event)" id="mrp_<?php echo $row->id; ?>" type="text" name="<?=$row->vendor_id?>_mrp[]" maxlength="5" placeholder="MRP" value="<?=$row->mrp; ?>">
                                                </td>
                                                <td>
                                                     <input onkeypress="only_number(event)" class="per_unit_price_<?php echo $row->vendor_id ?> form-control" id="per_unit_price_<?php echo $row->id; ?>" type="text" name="<?=$row->vendor_id?>_per_unit_price[]" maxlength="5" placeholder="Per Unit Price" oninput="return amount('<?php echo $row->id; ?>','<?=$row->vendor_id?>')" value="<?=$row->per_unit_price; ?>">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="<?php echo $row->quotation_request_detail_id ?>" name="<?=$row->vendor_id?>_quotation_request_detail_id[]">
                                                    <input  class="amount_<?php echo $row->id ?> form-control" onkeypress="only_number(event)" id="amount_<?php echo $row->id; ?>" type="text" name="<?=$row->vendor_id?>_amount[]" maxlength="5" value="<?=$row->amount?>" placeholder="Amount" readonly>
                                                </td>
                                                 <td>
                                                    <a href="javascript:void(0)" onclick="remove_tr($(this).closest('tr').index(),'<?php echo $row->id; ?>','<?=$row->vendor_id?>','<?=$row->asset_id; ?>','<?= $sr; ?>')" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                </td>
                                            </tr>


                                       <?php $sr++;   } ?>
                                       <tr>
                                           <td colspan="3">&nbsp;</td>
                                           <td>Total Qty : <span id="ttlqty<?=$data->vendor_id?>"><?=$ttlQty; ?></span>
                                            <input type="hidden" id="ttlqtyval<?=$data->vendor_id?>" value="<?=$ttlQty; ?>" class="ttlqtyval" name="ttlqtyval[]">
                                           </td>
                                           <td colspan="4">&nbsp;</td>
                                       </tr>
                                   </tbody>
                               </table> 
                              </div>
                              
                          </div>
                      </div>
                     
                  <?php  $no++; }  ?>
                  </div> 
               <div class="panel-footer">
                <button class="btn btn-success" type="submit" style="display: none" id="submit" disabled="" >Update</button>
                <button class="btn btn-success" type="button" onclick="return EditValidation();">Update</button>
                <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>                                    
             </div>
            </div>
          </div>
        </form>
      </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('common/footer');?>
<script>
   function change_status(quotation_id,vendor_id)
   {
        $.ajax({
            type:"post",
            url:"<?= site_url('Quotations/getQuotaitonDetail') ?>",
            cache:false,
            data:{quotation_id:quotation_id,vendor_id:vendor_id},
            success:function(returndata)
            {
                $("#quotation_details").html(returndata);
                $("#quotation_id").val(quotation_id);
                $("#vendor_id").val(vendor_id);
            }
        })
   }
</script>


<script type="text/javascript">
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
function EditValidation() 
{
  var requredQty  = $(".requredQty").val();
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

 var ttlqty=0;
  $('.ttlqtyval').each(function(){
    var qty=$(this).val();
    ttlqty=parseFloat(ttlqty)+parseFloat(qty);
  });

  $('.totalQtyAll').val(ttlqty);
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

<script>
function amount(id,vid){
  var qty=$('#qunatity_'+id).val();
  var pup=$('#per_unit_price_'+id).val();
  var total=(parseInt(qty))*(parseInt(pup));
  $('#amount_'+id).val(total);

}
</script>
