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
                  
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Barcode View details</strong></h3>
                                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                                    <ul class="panel-controls">
                                        <li><a  href="<?=site_url('Purchase_orders/read/'.$po->id );?>"><span class="fa fa-arrow-left"></span></a></li>
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
                                                        <!-- <th>Distribution Center</th> -->
                                                        <th>Vendor</th>
                                                        <!-- <th>Asset Type</th> -->
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

                                           <div class="panel panel-default">                                        
                                              <div class="panel-heading" id="printpagebutton">
                                                <b  class="col-md-4">Purchase order details</b>
                                            <form method="post" action="<?=$addaction; ;?>">
                                                 <span class="col-md-3">  
                                                <input type="text" id="barcode_number_inp" placeholder="Enter Barcode No."  name="barcode_number" onkeypress="only_number(event)" />
                                                 <div id="searchbox"></div>
                                                  <input type="hidden" name="quotation_id" value="<?=$po->quotation_id; ?>">
                                                  <input type="hidden" name="branch_id" value="<?=$po->branch_id; ?>">
                                                  <input type="hidden" name="asset_type_id" id="asset_type_id" value="<?= $pod->asset_type_id; ?>">
                                                  <input type="hidden" name="order_number" value="<?=$po->order_number; ?>">
                                                  <input type="hidden" name="purchase_detail_id"  id="purchase_detail_id" value="<?= $purchase_detail_id; ?>">
                                                  <input type="hidden" name="asset_id"  id="asset_id" value="<?= $asset_id; ?>">
                                                 

                                                   <button type="submit" class="btn btn-primary btn-sm no-print pull-right" style="margin-top:-25px" id="s"  onclick="return valide()">Add to Stock</button>

                                             <!--    <button class="btn btn-primary btn-sm" onclick="return valide()">View Details</button> -->
                                                 <span id="errBarcode">&nbsp;</span>
                                              </span>
                                                 </form>
                                             
                                              </div>
                                              <div class="panel-body">
                                                <div class="table-responsive">
                                                  <table id="example" class="table table-striped table-border">
                                                    <thead>
                                                      <tr>
                                                        <th width="5%">Brand</th>
                                                        <th width="10%">Asset</th>
                                                        <th width="4%">Unit per price (Rs.)</th>
                                                        <th width="4%">Quantity</th>
                                                        <th width="7%">Unit</th>
                                                        <th width="7%">In Stock</th>
                                                      <?php if(!empty($inreturnProduct)){ ?>
                                                        <th width="7%">Return Qty</th>
                                                      <?php } ?>  
                                                      <?php if(!empty($inreplaceProduct)){ ?>
                                                        <th width="7%">Replace Qty</th>
                                                      <?php } ?>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      <?php //print_r($pod); /*$sr=0; foreach($pod as $row){*/
                                                      ?>
                                                      <tr>
                                                        <td><?php if(!empty($pod->brand_name)){ echo ucfirst($pod->brand_name); } else { echo '-'; } ?>
                                                        <td><?php if(!empty($pod->asset_name)){ echo ucfirst($pod->asset_name); } else { echo '-'; } ?>
                                                        </td>
                                                       
                                                        <td><?= number_format($per_unit_price,2);?></td>
                                                        <td><?= $pod->quantity;?></td>
                                                        <td><?= $pod->unit;?></td>
                                                        <td><?= $instockProduct;?></td>
                                                        <?php if(!empty($inreturnProduct)){ ?>
                                                        <td><a href="<?=site_url('Purchase_orders/return_replaceAssets/return/'.$po->id.'/'.$id.'/'.$asset_id.'/'.$asset_type_id)?>"><?= $inreturnProduct; ?></a></td>
                                                        <?php } ?>
                                                        <?php if(!empty($inreplaceProduct)){ ?>
                                                        <td><a href="<?=site_url('Purchase_orders/return_replaceAssets/replace/'.$po->id.'/'.$id.'/'.$asset_id.'/'.$asset_type_id)?>"><?= $inreplaceProduct; ?></a></td>
                                                        <?php } ?>
                                                      </tr>
                                                      <?php /*}*/ ?>                          
                                                    </tbody>
                                                  </table>  
                                                </div>  
                                              </div> 
                                            </div> 
                                            <div class="panel panel-default">                                        
                                              <div class="panel-heading" id="printpagebutton">
                                                <b>Barcode Data</b>
                                                 <a class="btn btn-primary no-print pull-right" target="_blank" style="margin-top:-5px" href="<?=site_url('Purchase_orders/print_barcode/'.$po->id.'/'.$pod->asset_id.'/'.str_replace(' ','_', $pod->asset_name));?>">Print</a> 
                                              </div>
                                              <div class="row">
                                                <div class="col-md-12">
                                                  <input type="hidden" id="barcodeCount" value="<?=count($barcodeData)?>">
                                                  <?php foreach($barcodeData as $row) { ?>
                                                    <div class="col-md-2"><br/>
                                                      <?php if(!empty($row->barcode_image)) { ?>
                                                      <img src="<?php echo base_url('assets/purchaseOrder_barcode/'.$row->barcode_image)?>" >
                                                      <?php } else { ?>
                                                      <span>-</span>
                                                      <?php } ?>
                                                    </div>
                                                    <input type="hidden" class="barcodenumber" value="<?=$row->barcode_number?>">
                                                  <?php } ?>
                                                </div>
                                              </div>
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
<script>
    $(document).ready(function(e){
      src = '<?= site_url('Purchase_orders/getBarcodes'); ?>';
     var asset_id='<?=$asset_id; ?>';
    var  asset_type_id='<?= $po->asset_type_id;?>';
     var purchase_received_details='<?=$id;?>';
      $("#barcode_number_inp").autocomplete({
        appendTo: "#searchbox",
        source: function(request, response) {

          $(".ui-autocomplete").html('<img src="<?= base_url('assets/default.gif'); ?>" alt="">');
          $.getJSON(src, {search : request.term,asset_id:asset_id,asset_type_id:asset_type_id,id:purchase_received_details}, 
            response);
        },
        select: function(event, ui) {$("#asset_type_id").val(asset_type_id);$("#asset_id").val(asset_id); $("#purchase_received_details").val(purchase_received_details); }
      });

     /* $("#barcode_number_inp").keypress(function(e){
         if(e.which == 13) {
           e.preventDefault();
           $("#s").click();
           return false;             
          }
      });*/
    });
  </script>
<script type="text/javascript">
    function myFunction() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print();
        //Set the print button to 'visible' again 
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
    }

  function valide(){
    var barcode_number_inp=$('#barcode_number_inp').val().trim();
    var barcodeCount=$('#barcodeCount').val().trim();
    if(barcode_number_inp==''){
      
      $('#errBarcode').html('Required').css('color','red');
      setTimeout(function(){$('#errBarcode').html('&nbsp;'); },2000);
      return false;
    }
    var barcodeerror=new Array();
    $('.barcodenumber').each(function(){

      var val=$(this).val(); 
     
        if(val != barcode_number_inp){
           barcodeerror.push($(this).attr("value"));
        }
    });
    //alert(barcodeerror.length);return false;
   // alert(barcodeCount);return false;
     if(barcodeerror.length==barcodeCount){
       $('#errBarcode').html('Enter valid barcode').css('color','red');
      setTimeout(function(){ $('#errBarcode').html('&nbsp;');; },2000);
      return false;
    }

  }
</script> 
