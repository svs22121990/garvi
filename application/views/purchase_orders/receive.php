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
                                    <h3 class="panel-title"><strong>Receive Purchase Order</strong></h3>
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
                                                   <!--  <td><?= $po->type; ?></td> -->
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

                          <form method="post" action="<?= site_url('Purchase_orders/save_receive_order'); ?>" enctype="multipart/form-data">
                          <input type="hidden" name="amount"  class="amount form-control" readonly>
                          <input type="hidden" name="total_amount"  class="total_amount form-control" readonly>

                          <div class="panel panel-default">                                        
                            <div class="panel-heading">
                              Purchase order details
                            </div>
                            <div class="panel-body">
                              <div class="row">
                                <div class="col-md-12">
                                  &nbsp;<span id="showEditError" style="color:#FF0000; display:none; font-size:14px" align="left"></span>&nbsp;
                                </div>  
                                <div class="col-md-12">
                                  <div class="col-md-3">
                                    <label>Lot No.</label>
                                    <input type="text" id="lot_no" name="lot_no" class="form-control" maxlength="6">                            
                                  </div>
                                  <div class="col-md-3">
                                    <label>Driver Name</label>
                                    <input type="text" id="driver_name" name="driver_name" class="form-control"  onkeypress="only_alpha(event)" maxlength="50">
                                  </div>
                                  <div class="col-md-3">
                                    <label>Vehicle No.</label>
                                    <input type="text" id="vehicle_no" name="vehicle_no" class="form-control" onkeypress="only_alphanum(event)" maxlength="15">
                                  </div>
                                  <div class="col-md-3">
                                    <label>Labour Charge</label>
                                    <input type="text" id="labour_charge"  oninput="multinputs1()" name="labour_charge" class="form-control" onkeypress="Dotnumber(event)" maxlength="9">
                                  </div>
                                </div>                        
                                <div class="col-md-12" style="margin-top: 20px">
                                  <div class="col-md-3">
                                    <label>Extra Vendor Charge</label>
                                    <input type="text" id="extra_vendor_charge" oninput="multinputs1()" name="extra_vendor_charge" class="form-control" onkeypress="Dotnumber(event)" maxlength="9">
                                  </div>
                                  <div class="col-md-3">
                                    <label>Bill No. <span style="color:red">*</span></label>
                                    <input type="text" id="bill_no" name="bill_no" class="form-control" maxlength="20">
                                  </div>
                                  <div class="col-md-6">
                                    <label>Bill copy <span style="color:red">*</span></label>
                                    <input type="file" id="bill_copy" name="bill_copy" class="form-control" accept="image/*" onclick="bill_copy_image();">
                                  </div>
                                </div>
                              </div><br/><br/>


                              <div class="table-responsive">
                                <table id="example" class="table table-striped table-border">
                                  <thead>
                                    <tr>
                                      <th width="5%">Sr. No.</th>
                                      <th width="5%">Brand</th>
                                      <th width="15%">Asset Name</th>
                                      <th width="5%">Qty</th>
                                      <th width="5%">Unit</th>
                                      <th width="8%">Received Qty</th>
                                      <th width="8%">MRP</th>
                                      <th width="8%">Purchase rate</th>
                                      <!-- <th width="8%">Price</th> -->
                                      <th width="6%">CGST(%)</th>
                                      <th width="6%">SGST(%)</th>
                                      <th width="6%">CESS</th>
                                      <th width="8%">Final Price</th>
                                      <th width="10%">Status</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php $sr=0; foreach($pod as $row){ if($row->status != 'Received'){ 
                                      //print_r($row);
                                    /*Fetch purchase Amt from products and Gst Percentage*/
                                    $assetprice = $this->Crud_model->GetData('assets','',"id='".$row->asset_id."'",'','','','single'); 
                                    $asset_name= $assetprice->asset_name;
                                    $product_mrp=$assetprice->product_mrp;
                                    $final_amount=$assetprice->final_amount;
                                    $cgst_asset=$assetprice->cgst_asset;
                                    $sgst_asset=$assetprice->sgst_asset;
                                    $readonly='';
                                    if($po->quotation_id!='0'){
                                      $assetprice = $this->Crud_model->GetData('quotation_details','',"asset_id='".$row->asset_id."' and quotation_id='".$po->quotation_id."' and vendor_id='".$row->vendor_id."' and status='Approved'",'','','','single');  
                                      //print_r($assetprice);
                                      $asset = $this->Crud_model->GetData('assets','',"id='".$row->asset_id."'",'','','','single'); 
                                       $asset_name= $asset->asset_name;
                                       $product_mrp= $assetprice->mrp;
                                       $final_amount= $assetprice->per_unit_price;
                                       $cgst_asset='0';
                                       $sgst_asset='0';
                                       $readonly='readonly';
                                    }


                                    $unit = $this->Crud_model->GetData('unit_types','',"id='".$row->unit_id."'",'','','','single');
                                    //$wpPrice = $this->Crud_model->GetData('weight_price','',"id='".$row->weight_price_id."'",'','','','single');
                                    /*Ends Here*/
                                    $availQty=$this->Crud_model->GetData('purchase_received_details',"sum(received_qty) Qty","purchase_order_id='".$row->purchase_order_id."' and asset_id='".$row->asset_id."' and status='Received'",'','','','single');
                                    $availableQty = $row->quantity - $availQty->Qty;
                                      ?>
                                    <tr class="trRow">
                                      <td><?= ++$sr; ?></td>
                                      <td><?=$row->brand_name ; ?></td>
                                      <td><?=$asset_name ; ?></td>
                                      <td><?= $row->quantity; ?></td>
                                      <td><?php if(!empty($unit->unit)){ echo $unit->unit; }else{ echo '-'; } ?></td>
                                      <td>
                                        <?php if($row->status == 'Received'){ ?>
                                        <input type="text" class="form-control inpwidth" readonly>
                                        <?php } else { ?>
                                        <input type="text" class="form-control inpwidth received_qty received_qty<?= $sr; ?>" maxlength="6" name="received_qty[]" id="received_qty<?= $sr; ?>" oninput="RemainQty(this.value,'<?= $sr; ?>')" onkeypress="only_number(event)" autocomplete='off' value="<?php if(!empty($availableQty)){ echo $availableQty; } else { echo '0'; } ?>" <?=$readonly; ?>>
                                        <?php } ?>
                                        <span id="remainQty<?= $sr; ?>"><small>Remaining Qty: 0</small></span><br>                                
                                        &nbsp;<span id="errRec<?= $sr; ?>" style="color:red"></span>&nbsp;
                                      </td>
                                      <td><input type="text" class="form-control" name="per_unit_mrp[]" id="mrp<?= $sr; ?>" maxlength="9" onkeypress="Dotnumber(event)" value="<?php if(!empty($product_mrp)){ echo $product_mrp; } else { echo '0'; } ?>" <?=$readonly; ?>>
                                         &nbsp;<span id="mrptError<?= $sr; ?>" style="color:red"></span>&nbsp;
                                      </td>

                                      <td><input type="text" class="form-control" oninput="GetFinalPrice('<?= $sr; ?>')" name="per_unit_price[]" id="amount<?= $sr; ?>" maxlength="9" onkeypress="Dotnumber(event)" value="<?php if(!empty($final_amount)){ echo $final_amount; } else { echo '0'; } ?>" <?=$readonly; ?>></td>
                                     <!--  <td style=""><input type="text" class="form-control" name="price[]" id="price<?= $sr; ?>" readonly></td> -->

                                      <td><input type="text" class="form-control" name="cgst[]" id="cgst<?= $sr; ?>" maxlength="12" onkeypress="Dotnumber(event)" value="<?= $cgst_asset; ?>"><input type="hidden" name="cgstAmt[]" id="cgstAmt<?= $sr; ?>"></td>
                                      <td><input type="text" class="form-control" name="sgst[]" id="sgst<?= $sr; ?>" maxlength="12" onkeypress="Dotnumber(event)" value="<?= $sgst_asset; ?>"><input type="hidden" name="sgstAmt[]" id="sgstAmt<?= $sr; ?>"></td>
                                      <td>
                                        <input type="text" class="form-control" oninput="GetFinalPrice('<?= $sr; ?>')" name="fess[]" id="fess<?= $sr; ?>" maxlength="6" onkeypress="Dotnumber(event)">
                                      </td>
                                      <td>
                                        <?php if($row->status == 'Received'){ ?>
                                        <input type="text" class="form-control" value="" readonly>
                                        <?php } else { ?>                                      
                                        <input type="text" name="rate[]" id="rate<?= $sr; ?>" class="form-control rate rate<?= $sr; ?>" readonly>
                                        <?php } ?>
                                        <input class="form-control" type="text" id="spanRate<?= $sr; ?>" readonly style="display:none">
                                        &nbsp;<span id="errRate<?= $sr; ?>" style="color:red"></span>&nbsp;
                                      </td>
                                      <td>
                                        <?php if($row->status != 'Received'){ ?>
                                      <!-- Hidden Data -->
                                      <input type="hidden" name="sr_no" id="sr_no" value="<?= $sr; ?>">
                                      <input type="hidden" name="user_id" id="user_id" value="<?= $_SESSION[SESSION_NAME]['id']; ?>" >
                                     
                                      <input type="hidden" name="order_number" id="order_number"  value="<?= $po->order_number ?>">
                                      <input type="hidden" name="branch_id" id="branch_id"  value="<?= $po->branch_id ?>">
                                      <input type="hidden" name="quotation_id" id="quotation_id"  value="<?= $po->quotation_id ?>">
                                      <input type="hidden" name="asset_type_id[]" id="asset_type_id"  value="<?= $row->asset_type_id ?>">
                                      <input type="hidden" name="vendor_id" id="vendor_id"  value="<?= $row->vendor_id ?>">
                                      <input type="hidden" name="purchase_order_id" id="purchase_order_id"  value="<?= $row->purchase_order_id ?>">
                                      <input type="hidden" name="id[]" id="id" value="<?= $row->id; ?>">
                                      <input type="hidden" name="cat_id[]" id="cat_id<?= $sr; ?>" value="<?= $row->cat_id ?>">
                                      <input type="hidden" name="subcat_id[]" id="subcat_id<?= $sr; ?>" value="<?= $row->subcat_id ?>">
                                      <input type="hidden" name="asset_id[]" id="asset_id<?= $sr; ?>" value="<?= $row->asset_id ?>">
                                      <input type="hidden" name="unit[]" id="unit_id<?= $sr; ?>" value="<?= $row->unit_id ?>">
                                      <input type="hidden" name="quantity[]" id="quantity<?= $sr; ?>" value="<?= $row->quantity ?>">
                                      <input type="hidden" name="availableQty[]" id="availableQty<?= $sr; ?>" value="<?= $availableQty ?>">
                                      <!-- Hidden Data -->
                                        <select class="form-control" id="status<?= $sr; ?>" name="status[]" style="padding-left:5px">
                                          <option value="Pending">Pending</option>
                                          <option value="Received">Received</option>
                                        </select>
                                        <?php } else { ?>
                                        <input class="form-control" type="text" value="<?= $row->status ?>" readonly>
                                        <?php } ?>
                                        &nbsp;<span id="errStatus<?= $sr; ?>" style="color:red"></span>&nbsp;
                                      </td>
                                            
                                    </tr>
                                    <?php } } ?>                          
                                  </tbody>
                                  <tbody>
                                    <tr>
                                      <td colspan="11" style="text-align:right;font-weight:bold">Amount (<i class="fa fa-inr"></i>)</td>
                                      <td colspan="2"><input type="text" class="amount form-control" readonly></td>
                                    </tr>
                                    <tr>
                                      <td colspan="11" style="text-align:right;font-weight:bold">Extra Charegs (<i class="fa fa-inr"></i>)</td>
                                      <td colspan="2">
                                      <input type="text"  class="extraCharges form-control" readonly>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="11" style="text-align:right;font-weight:bold">Total Amount (<i class="fa fa-inr"></i>)</td>
                                      <td colspan="2">
                                      <input type="text"  class="total_amount form-control" readonly>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>  
                              </div>  
                            </div> 
                          </div> 
                          <div class="panel-footer">
                             <button type="submit" class="btn btn-success" onclick="return saveData()">Submit</button>
                          <button onclick="window.history.back()" class="btn btn-default" type="button">Cancel</button>                          
                        </div>
               
                  </form>                              
                </div>  
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 

<script>
    var url = '';
    var actioncolumn = '';
</script>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
  function bill_copy_image()
  {     
    $('#bill_copy').change(function () { 
    var files = this.files;   
    var reader = new FileReader();
    name=this.value;    
    //validation for photo upload type    
    var filetype = name.split(".");
    ext = filetype[filetype.length-1];  //alert(ext);
    if(!(ext=='jpg') && !(ext=='png') && !(ext=='PNG') && !(ext=='jpeg') && !(ext=='img') && !(ext=='pdf'))
    {
      $("#showEditError").fadeIn().html("Please select proper type like jpg, png, jpeg image");
      setTimeout(function(){$("#showEditError").html("&nbsp;")},3000);
      $("#bill_copy").val("");
      return false; 
    }
    reader.readAsDataURL(files[0]); 
    });
  }

    function saveData()
    { 
      var srNo = $(".trRow").length;
      var status = $(".status").val();
      var cgst = $("#cgst").val();
      var sgst = $("#sgst").val();
      var lot_no = $("#lot_no").val();
      var bill_no = $("#bill_no").val();
      var bill_copy = $("#bill_copy").val();
      var count = 0;  
/*
      if(lot_no == '')
      {
        $("#showEditError").fadeIn().html("Please enter lot no");
        setTimeout(function(){$("#showEditError").fadeOut();},3000);
        $("#lot_no").focus();
        return false; 
      }  */  

      if(bill_no == '')
      {
        $("#showEditError").fadeIn().html("Please enter bill no");
        setTimeout(function(){$("#showEditError").fadeOut();},3000);
        $("#bill_no").focus();
        return false; 
      }    

      if(bill_copy == '')
      {
        $("#showEditError").fadeIn().html("Please select bill copy");
        setTimeout(function(){$("#showEditError").fadeOut();},3000);
        $("#bill_copy").focus();
        return false; 
      }   

      for(var i=1; i<=srNo; i++)
      { 
        var availableQty = $("#availableQty"+i).val(); 
        var received_qty = $("#received_qty"+i).val();
        var status = $("#status"+i).val();
        var mrp = $("#mrp"+i).val(); 
        var rate = $("#rate"+i).val(); 
        var rate_filter = /^[0-9.]{0,80}$/i;
        //alert(rate);
        if(parseFloat(received_qty) > parseFloat(availableQty))
        {
          $("#errRec"+i).fadeIn().html("please enter valid quantity.");
          setTimeout(function(){$("#errRec"+i).fadeOut();},3000);
          $("#received_qty"+i).focus();
          return false;
        }
        if(mrp=='' || mrp=='0')
        {
          $("#mrptError"+i).fadeIn().html("Please enter valid MRP");
          setTimeout(function(){$("#mrptError"+i).fadeOut();},3000);
          $("#mrp"+i).focus();
          return false; 
        }
        if((rate!= '') && (rate!=undefined))
        {
          var count_rate = rate.split('.').length;  


          if((!rate_filter.test(rate)) || (count_rate>2) || (rate==0))
          {
            $("#errRate"+i).fadeIn().html("Please enter valid amount.");
            setTimeout(function(){$("#errRate"+i).fadeOut();},3000);
            $("#rate"+i).focus();
            return false;
          }
          if(received_qty == '')
          {
            $("#errRec"+i).fadeIn().html("Please enter qty.");
            setTimeout(function(){$("#errRec"+i).fadeOut();},3000);
            $("#received_qty"+i).focus();
            return false; 
          }
          if(status == 'Pending')
          {
            $("#errStatus"+i).fadeIn().html("Please change the status.");
            setTimeout(function(){$("#errStatus"+i).fadeOut();},3000);
            $("#status"+i).focus();
            return false; 
          }
          count++;
        }

        else if(rate=='')
        { 
          if(status == 'Received')
          {
            if(received_qty == '')
            {
              $("#errRec"+i).fadeIn().html("Please enter qty.");
              setTimeout(function(){$("#errRec"+i).fadeOut();},3000);
              $("#received_qty"+i).focus();
              return false; 
            }
            $("#errRate"+i).fadeIn().html("Please enter rate.");
            setTimeout(function(){$("#errRate"+i).fadeOut();},3000);
            $("#rate"+i).focus();
            count++;
            return false; 
          }
          else if(status == 'Returned')
          {
            count++;
          }
        } 
      } 

    if(count == 0)
    {
      $("#showEditError").fadeIn().html("Please enter some field");
      setTimeout(function(){$("#showEditError").fadeOut();},3000);
      return false; 
    }

    if(cgst=='' || cgst=='0')
    {
      $("#cgstError").fadeIn().html("Please enter cgst");
      setTimeout(function(){$("#cgstError").fadeOut();},3000);
      $("#cgst").focus();
      return false; 
    }

    if(sgst=='' || sgst=='0')
    {
      $("#sgstError").fadeIn().html("Please enter sgst");
      setTimeout(function(){$("#sgstError").fadeOut();},3000);
      $("#sgst").focus();
      return false; 
    }
    $(".dashboardLoader").show();
    $('.loader').fadeIn("fast");
  }

  function multinputs1(event)
  {      
    var mult = 0;
    $("tr.trRow").each(function () {
      var $total_amount_detail = $('.rate', this).val();
      var $total = ($total_amount_detail * 1);
      mult += $total;
    }); 
    $(".amount").val(mult.toFixed(2));
    var labour_charge = $("#labour_charge").val();
    var extra_vendor_charge = $("#extra_vendor_charge").val();
    if(labour_charge=='')
    {
      labour_charge=0;
    }
    if(extra_vendor_charge==''){
      extra_vendor_charge=0;
    } 
    /*
    var cgst= $("#cgst").val();
    if(cgst=='' || cgst==0){ cgst=0; }
    var cgstAmt = parseFloat(mult)*parseFloat(cgst)/100; 
    $("#cgst_amt").val(cgstAmt.toFixed(2));

    var sgst= $("#sgst").val();
    if(sgst=='' || sgst==0){ sgst=0; }
    var sgstAmt = parseFloat(mult)*parseFloat(sgst)/100; 
    $("#sgst_amt").val(sgstAmt.toFixed(2));

    var gst= parseFloat(cgst) + parseFloat(sgst);
    $("#gst").val(gst); 

    var gstAmt= parseFloat(cgstAmt) + parseFloat(sgstAmt);
    $("#gst_amt").val(gstAmt.toFixed(2));
    */
    var extra = parseFloat(labour_charge) + parseFloat(extra_vendor_charge);    
    var total = parseFloat(mult) + parseFloat(extra); 
    var total_amount = total;
    $(".extraCharges").val(Math.round(extra));
    $(".total_amount").val(Math.round(total_amount));
  }

  function GetGst(gst)
  {
    var amount= $("#amount").val();
    if(amount=='' || amount==0.00){ amount=0; }
    var gstAmt = parseFloat(amount)*parseFloat(gst)/100;
    var total = parseFloat(amount) + parseFloat(gstAmt);
    $("#amount").val(total.toFixed(2));
    $("#total_amount").val(Math.round(total));
    multinputs1();
  }

  function RemainQty(val,sr)
  {
    if(val==''){ val=0; }
    var availQty = $("#availableQty"+sr).val();

    if(parseFloat(val) > parseFloat(availQty))
    { 
      $("#received_qty"+sr).val(availQty);
      $("#remainQty"+sr).html("<small>Remaining Qty: 0</small>");
      alert('Enter quantity not be more than available quantity'); 
      GetFinalPrice(sr);
      return false;
    } 
    var remainQty = parseFloat(availQty) - parseFloat(val);
    $("#remainQty"+sr).html("<small>Remaining Qty: "+remainQty+"</small>");
    GetFinalPrice(sr);
  }

  function GetFinalPrice(sr)
  {
    var received_qty = $("#received_qty"+sr).val(); if(received_qty==''){ received_qty=0; }
    var amount = $("#amount"+sr).val(); if(amount==''){ amount=0; }
    var fess = $("#fess"+sr).val(); if(fess==''){ fess=0; }
   
    var price = parseFloat(amount) * parseFloat(received_qty);
   
   /* var cgst = $("#cgst"+sr).val(); if(cgst==''){ cgst=0; }
    var sgst = $("#sgst"+sr).val(); if(sgst==''){ sgst=0; }

    var cgstAmt = parseFloat(price) * parseFloat(cgst) / 100;    $("#cgstAmt"+sr).val(cgstAmt);*/
    

    var TotalGst =parseFloat(price) + parseFloat(fess) ;
    $("#rate"+sr).val(TotalGst);
    multinputs1();
  }

</script>
<script type="text/javascript">
  $(function(){
    var srNo = $(".trRow").length;    
     for(var i=1; i<=srNo; i++)
      { 
        GetFinalPrice(i);
      }  
  });
  
function only_number(event)
{
  var x = event.which || event.keyCode;
  console.log(x);
  if((x >= 48 ) && (x <= 57 ) || x == 8 || x == 9 || x == 13 )
  {
    return;
  }else{
    event.preventDefault();
  }    
}

function Dotnumber(event)
{
  var x = event.which || event.keyCode;
  console.log(x);
  if((x >= 48 ) && (x <= 57 ) || x == 8 || x == 9 || x == 13 || x == 46)
  {
    return;
  }else{
    event.preventDefault();
  }    
}

function only_alpha(event)
{
  var x = event.which || event.keyCode;
  console.log(x);
  if((x >= 97 ) && (x <= 122 ) || (x >= 65 ) && (x <= 90 ) || x == 37 || x == 39 || x == 46 || x == 8|| x == 9)
  {
    return;
  }else{
    event.preventDefault();
  }    
}
</script>