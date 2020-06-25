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
                                     <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                                    <ul class="panel-controls">
                                        <li><a href="<?=site_url('Purchase_orders/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body">           

                                    <div class="row">
                                      <div class="col-md-12">  

                                        <div class="panel panel-default">                    
                                          <div class="panel-body">
                                            <div class="table-responsive">
                                              <table id="example" class="table table-border">
                                                <thead>
                                                  <tr>
                                                    <th>Vendor</th>
                                                   <!--  <th>Asset Type</th> -->
                                                     <?php if($po->branch_id!='0') { ?>
                                                      <th>Branch</th>
                                                    <?php } ?> 
                                                    <?php if($po->quotation_id!='0') { ?>
                                                      <th>Quotation No</th>
                                                    <?php } ?>
                                                    <th>PO Number</th>
                                                    <th>PO Date</th>
                                                    <th>Status</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <tr>
                                                    <td><?= $po->shop_name ?></td>
                                                    <!-- <td><?= $po->type; ?></td> -->
                                                    <?php if($po->branch_id!='0') { ?>
                                                      <td><?= $po->branch; ?></td>
                                                    <?php } ?> 
                                                    <?php if($po->quotation_id!='0') { ?>
                                                      <td><?= $po->quotation_no; ?></td>
                                                    <?php } ?>
                                                    <td><?= $po->order_number; ?></td>
                                                    <td><?= date('d-m-Y',strtotime($po->purchase_date)); ?></td>
                                                    <td>
                                                      <?php if($po->status == 'Pending'){ ?>
                                                      <button class="btn btn-danger btn-sm" style="cursor:default">Pending</button>
                                                      <?php } elseif($po->status == 'Received') { ?>
                                                      <button class="btn btn-success btn-sm" style="cursor:default">Received</button>
                                                      <?php } ?>
                                                    </td>
                                                  </tr>                          
                                                </tbody>
                                              </table>  
                                            </div>  
                                          </div> 
                                        </div> 

                                          <div class="panel panel-default">                                        
                                            <div class="panel-heading">
                                              <span class="col-md-7">Purchase order details</span>
                                             
                                              <span class="col-md-5 ">
                                              <?php if($po->status != 'Received') { ?>                      
                                              <a href="<?= site_url('Purchase_orders/read/'.$id.'?flag=1') ?>"><button  type="button" class="btn btn-sm btn-success pull-right" style="margin-top:-5px">Receive</button></a>
                                              <?php } 
                                              $preceive = $this->Crud_model->GetData('purchase_received','',"purchase_order_id='".$this->uri->segment(3)."'");
                                              if(count($preceive) > 0){ ?>
                                              <a href="<?= site_url('Purchase_orders/purchase_detail/'.$id) ?>"><button type="button" class="btn btn-primary btn-sm pull-right" >Lot</button></a>
                                              <?php } ?>
                                            </span>
                                            </div>
                                            <div class="panel-body">
                                              <div class="table-responsive">
                                                <table id="example" class="table table-striped table-border">
                                                  <thead>
                                                    <tr>
                                                      <th width="3%">Sr. No.</th>
                                                      <th width="5%">Brand</th>
                                                      <th width="25%">Asset Name</th>
                                                      <th width="5%">Quantity</th>
                                                      <th width="5%">Unit</th>
                                                      <th width="10%">Rate</th>
                                                      <th width="10%">CGST</th>
                                                      <th width="10%">SGST</th>
                                                      <th width="10%">CESS</th>
                                                      <th width="10%">Amount</th>
                                                      <th width="10%">Received Date</th>
                                                      <th width="10%">Status</th>
                                                      <th width="15%">Action</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <?php $sr=0; foreach($pod as $row){

                                                     // print_r($row);
                                                    $amt = $this->Purchase_orders_model->GetPrdReceivedAmt("purchase_order_id='".$row->purchase_order_id."' and asset_id='".$row->asset_id."' and purchase_order_detail_id='".$row->id."'");
                                                     
                                                    ?>
                                                    <tr>
                                                      <td><?= ++$sr; ?></td>
                                                      <td><?= ucfirst($row->brand_name) ?></td>
                                                      <td><?php if(!empty($row->asset_name)){ echo ucfirst($row->asset_name); } else { echo '-'; } ?>
                                                        <input type="hidden" id="<?=$sr; ?>_asset_id" value="<?=$row->asset_id?>">
                                                      </td>
                                                      <td><?= $row->quantity;?></td>
                                                      <td><?php if(!empty($row->unit)){ echo $row->unit; }else{ echo '-'; } ?></td>
                                                      <td><i class="fa fa-inr">&nbsp;</i><?php if(!empty($amt->per_unit_price)){ echo number_format($amt->per_unit_price,2); }else{ echo '0.00'; } ?></td>
                                                    
                                                      <td><?php if(!empty($amt->cgst)){ echo $amt->cgst.' %'; }else{ echo '0%'; } ?></td>
                                                      <td><?php if(!empty($amt->sgst)){ echo $amt->sgst.' %'; }else{ echo '0%'; } ?></td>
                                                      <td><i class="fa fa-inr">&nbsp;</i><?php if(!empty($amt->fess)){ echo number_format($amt->fess,2); }else{ echo '0.00'; } ?></td>
                                                      <td><i class="fa fa-inr">&nbsp;</i><?php if(!empty($amt->amount)){ echo number_format($amt->amount,2); }else{ echo '0.00'; } ?></td>
                                                      <td><?php if($row->received_date != '0000-00-00'){ echo date('d-m-Y',strtotime($row->received_date)); }else{ echo '-'; } ?></td>
                                                      <td>
                                                        <?php if($row->status == 'Pending'){ ?>
                                                        <button class="btn btn-danger btn-sm" style="cursor:default">Pending</button>
                                                        <?php } elseif($row->status == 'Received') { ?>
                                                        <button class="btn btn-success btn-sm" style="cursor:default">Received</button>
                                                        <?php } ?>
                                                      </td>
                                                      <td>
                                                        <?php 
                                                      
                                                          $record = $this->Crud_model->GetData('purchase_received_details','',"purchase_order_id='".$row->purchase_order_id."' and  asset_id='".$row->asset_id."'",'','','','single');   
                                                       // print_r($record);                               
                                                          if($row->status == 'Pending' && count($record)==0 && $po->quotation_id=='0'){ 
                                                        ?>
                                                          <button type="button" class="btn btn-sm btn-primary" title="Edit" data-toggle="modal" data-target="#myModal" onclick="EditRow(<?= $row->id; ?>)"><i class="fa fa-pencil"></i></button>
                                                          <button type="button" onclick="DeleteRow(this,<?= $row->id; ?>)" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash-o"></i></button>
                                                        <?php } else { 
                                                          if(count($record)!=0){
                                                         ?>
                                                          <a href="<?= site_url('Purchase_orders/barcode_view/'.$row->id.'/'.$row->asset_id.'/'.$row->asset_type_id)?>" title="View Barcode" class="btn btn-primary btn-sm"><i class="fa fa-barcode"></i></a>
                                                          
                                                        <?php } } ?>
                                                      </td>
                                                    </tr>
                                                    <?php } ?>                          
                                                  </tbody>
                                                </table>  
                                              </div>  
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
    var url="";
    var actioncolumn="";
</script>

<?php $this->load->view('common/footer');?>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Purchase Order</h4>
      </div>
      <div class="modal-body">
      </div>
    </div>

  </div>
</div>  

<script>
    var url = '';
    var actioncolumn = '';

    function DeleteRow(obj,id)
    {
      var cnf = confirm('Are you sure to delete');
      if(cnf==true)
      {
         $('.loader').fadeIn();
        $.ajax({
          type:'post',
          url:"<?= site_url('Purchase_orders/DeletePoRow') ?>",
          data:{id:id},
          success:function(response)
          {
             $('.loader').fadeOut();
            if(response == 1)
            window.location.reload();
            $(obj).parent().parent().hide();
          }
        });
      }
    }

    function EditRow(id)
    {
       $('.loader').fadeIn();
      $(".dashboardLoader").show();
      $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/EditPoRow') ?>",
        data:{id:id},
        success:function(response)
        {
          $(".modal-body").html(response);
           $('.loader').fadeOut();
          $(".dashboardLoader").hide();
        }
      });
    }

  function GetSubcategory(id)
  { 
    $('.loader').fadeIn();
    var asset_type_id=$('#editasset_type_id').val();
    $.ajax({
      type:'post',
      url:"<?= site_url('Purchase_orders/GetSubcategory') ?>",
      data:{id:id,asset_type_id:asset_type_id},
      success:function(returndata){ 
        $('.loader').fadeOut();
        $("#subcat_id").html(returndata);
        $('#asset_id').html('<option value="">Select Asset</option>');
        $('#unit_val').val('');
        $('#unit_id').val('');
      }
    });
  }
    function Getassetdata(id)
    {
       $('.loader').fadeIn();
      var asset_type_id=$('#editasset_type_id').val();
      var datastr='sub_cat='+id+'&asset_type_id='+asset_type_id;
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/Getassetdata') ?>",
        data:datastr,
        success:function(response){
            $('.loader').fadeOut();
            $("#asset_id").html(response);
            $('#unit_val').val('');
            $('#unit_id').val('');
        }
        });
    }

 function Getassetunit(id)
    {
       $('.loader').fadeIn();
      var asset_type_id=$('#asset_type').val();
      if(id!=''){

      
      var datastr='asset_id='+id;
        $.ajax({
        type:'post',
        url:"<?= site_url('Purchase_orders/Getassetunit') ?>",
        data:datastr,
        success:function(response){
           var obj =jQuery.parseJSON(response);
           $('.loader').fadeOut();
            $("#unit_id").val(obj.unitid);
            $("#unit_val").val(obj.val);
        }
        });
    }else{
            $('.loader').fadeOut();
            $("#unit_id").val('');
            $("#unit_val").val('');
    }
  }

  function resetData()
  { 
    $('#cat_id').val('');
    $('#subcat_id').val('');
    $('#asset_id').val('');
    $('#unit_val').val('');
    $('#unit_id').val('');
 
  }

  function GetVariant(id)
  {  $('.loader').fadeIn();
    $.ajax({
      type:'post',
      url:"<?= site_url('Purchase_orders/GetVariant') ?>",
      data:{id:id},
      success:function(returndata){ 
         $('.loader').fadeOut();
        var jd = $.parseJSON(returndata);
        $("#variant_id").html(jd.response);
        $("#unit").val(jd.unit);
      }
    });
  }

  function EditValidate(){
    var astIds=$('#astIds').val();
    var asset_id=$('#asset_id').val();
    var cat_id=$('#cat_id').val();
    var subcat_id=$('#subcat_id').val();
    var eqty=$('#eqty').val().trim();
    var asset_id=$('#asset_id').val();
    //alert(astIds);
    var split_str = astIds.split(",");
    
    if(cat_id==''){
      $('#ecaterr').html("Required").css('color','red');
      setTimeout(function(){ $('#ecaterr').html("&nbsp;"); },2000);
      $('#cat_id').focus();
      return false;
    }
    if(subcat_id==''){
      $('#esuberr').html("Required").css('color','red');
      setTimeout(function(){ $('#esuberr').html("&nbsp;"); },2000);
      $('#subcat_id').focus();
      return false;
    }
    if(asset_id==''){
      $('#easterr').html("Required").css('color','red');
      setTimeout(function(){ $('#easterr').html("&nbsp;"); },2000);
      $('#asset_id').focus();
      return false;
    }
    if (split_str.indexOf(asset_id) !== -1) {
          $('#easterr').html("Asset must be unique").css('color','red');
          setTimeout(function(){ $('#easterr').html("&nbsp;"); },2000);
          $('#asset_id').focus();
          return false;
    }
    if(eqty=='' || eqty=='0'){
      $('#eqtyerr').html("Required").css('color','red');
      setTimeout(function(){ $('#eqtyerr').html("&nbsp;"); },2000);
      $('#asset_id').focus();
      return false;
    }
     $('.loader').fadeIn();
  }

</script>


<script type="text/javascript">
function only_number(event)
{
  var x = event.which || event.keyCode;
  console.log(x);
  if((x >= 48 ) && (x <= 57 ) || x == 8 || x == 46 || x == 9 || x == 13 )
  {
    return;
  }else{
    event.preventDefault();
  }    
}
</script>