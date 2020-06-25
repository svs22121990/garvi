<?php $this->load->view('common/header');
//print_r($_SESSION);exit;
 ?>

<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>                    

<!-- START BREADCRUMB -->
<?= $breadcrumbs; ?>
<!-- END BREADCRUMB -->                       

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">

    <div class="col-md-12">
                                  
    
    
    </div>
    


        <?php
         $financialyearid=$this->Crud_model->GetData('financial_years','',"status='Active'");
         if(!empty($financialyearid)){
             $finyear=$financialyearid[0]->id;
            }else{
                $finyear='0';
            }
            //print_r($finyear);
        foreach ($asset_types as $row)
        {
           
            $getQtyandAmout=$this->Crud_model->getAssettypewiseCount($row->id,$finyear);
            $sumofAmt=0;
            $Receiveamt=$this->Crud_model->GetData('purchase_order_details','group_concat(id) as ids, group_concat(purchase_order_id) as pids,sum(price*quantity)as amt',"asset_type_id='".$row->id."' and status='Received'",'','','','row');
            
            if($getQtyandAmout!=''){

             $returnQtyandAmt=$this->Crud_model->GetData('purchase_received_detail_barcode','sum(ast_amount) as retamt, sum(quantity) as retqty',"purchase_order_id in (".$Receiveamt->pids.") and asset_type_id='".$row->id."' and is_returned='Yes' and is_replaced='No'",'','','','row');
             
             
            //print_r($this->db->last_query());exit;
             $outstockqty='0';
          
                $outsockStockQty=$this->Crud_model->GetData('purchase_received_detail_barcode','sum(quantity) as outstockqty',"asset_type_id='".$row->id."' and is_received='Yes' and is_returned='No' and is_replaced='No'",'','','','row');
                //print_r($outsockStockQty);
                if($outsockStockQty->outstockqty!=''){
                    $outstockqty=$outsockStockQty->outstockqty;
                }
            

                $totalpurchaseqty= $getQtyandAmout->totalQty;
                $returnQty= $returnQtyandAmt->retqty;
                $totalAmt = $Receiveamt->amt-$returnQtyandAmt->retamt ;  $totalQty = $getQtyandAmout->totalQty-$returnQtyandAmt->retqty-$outstockqty;
            }else{
                 $totalpurchaseqty= '0';
                 $returnQty= '0';
                 $totalAmt = '0'; $totalQty ='0'; 
            }

        
                $inStockQty=$this->Crud_model->GetData('purchase_received_detail_barcode','sum(quantity) as instockqty',"asset_type_id='".$row->id."' and is_returned='No' and is_received='Yes' and is_replaced='No'",'','','','row');
                //$inStockQtydirect=$this->Crud_model->GetData('stock_logs','sum(quantity) as instockqty',"asset_type_id='".$row->id."' and type='OpenStock' and financial_year_id='".$finyear."'",'','','','row');
               if($inStockQty->instockqty!=''){ $inStockqty=$inStockQty->instockqty;}else{ $inStockqty='0'; }
            


        ?>
        <?php if($_SESSION[SESSION_NAME]['type']=='SuperAdmin'){ ?>
        <div class="col-md-6">
            <!-- START Assets BLOCK -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title-box">
                        <h3><?= $row->type; ?></h3>
                        <span>Total <?= $row->type; ?> with Actual Total Amount</span>
                    </div>
                </div>
                <div class="panel-body panel-body-table">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="15%">Total Purchase</th>
                                    <th width="15%">Total Return</th>
                                    <th width="20%">Received </th>
                                    <th width="15%">In Stock</th>
                                    <th width="35%">Purchase Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$totalpurchaseqty; ?></td>
                                    <td><?php if($returnQty > 0){ ?> <a href="<?=site_url('Dashboard/assetTypeData/return/'.$row->id.'/'.$row->type);?>"><?= $returnQty ?></a> <?php }else{ echo $returnQty; }; ?></td>
                                    <td><?php
                                    $url=site_url('Dashboard/assetTypeData/ReceivedPending/'.$row->id.'/'.$row->type);
                                  
                                           if($totalQty >0 ){ ?><a href="<?= $url; ?>"><?= $totalQty; ?> </a><?php }else{ echo $totalQty; } 


                                      ?></td>
                                    <td><?php if($inStockqty >0 ){ ?> <a href="<?=site_url('Dashboard/assetTypeData/Instock/'.$row->id.'/'.$row->type);?>"><?= $inStockqty ?></a><?php }else{ echo $inStockqty; } ?></td>
                                    <td><strong><span class="fa fa-inr"> <?= number_format($totalAmt,2); ?></span></strong></td>
                                </tr>
                               
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- END Assets BLOCK -->
        </div>
    <?php } ?>
    <?php } ?>

    <?php if($_SESSION[SESSION_NAME]['type']=='User'){ ?>
        <div class="col-md-6">
            <!-- START Assets BLOCK -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title-box">
                        <h3>Recent Products</h3>
                    </div>
                </div>
                <div class="panel-body panel-body-table">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="20%">Product Type</th>
                                    <th width="20%">Product Name</th>
                                    <th width="10%">Quantity </th>
                                    <th width="15%">Image</th>
                                    <th width="15%">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(!empty($assets)){
                                    foreach ($assets as $assets_row) {
                                        //print_r($assets_row);echo "<br/>";
                                ?>
                                <tr>
                                    <td>
                                        <?php echo ucfirst($assets_row->type); ?>
                                    </td>
                                    <td>
                                        <?php echo ucfirst($assets_row->asset_name); ?>
                                    </td>
                                    <td>
                                        <?php if($_SESSION[SESSION_NAME]['type']=='Admin'){
                                             $instockQty=$this->Crud_model->GetData('asset_branch_mappings','sum(asset_quantity) as instockqty',"asset_id='".$assets_row->id."'",'','','','row');
                                             //print_r($instockQty->instockqty);
                                             $avlqty=$assets_row->quantity-$instockQty->instockqty;
                                            echo $avlqty;
                                        }else{
                                            echo $assets_row->quantity;
                                        }
                                         ?>    
                                    </td>
                                    <td>
                                        <?php if(!empty($assets_row->photo)){ ?>
                                        <img  style="width:60px;height:30px;" src="<?php echo base_url(); ?>uploads/assetimages/<?php echo $assets_row->photo ?>">
                                        <?php } else { ?>
                                        <img  style="width:60px;height:30px;" src="<?php echo base_url(); ?>uploads/employee_images/default.jpg ?>">   
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('Products/view/'.$assets_row->id); ?>" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i></a>
                                    </td>
                                      
                                </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="5"><center>No record found..!!</center></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- END Assets BLOCK -->
        </div>

        <!-- <div class="col-md-6">
            <!-- START Assets BLOCK --
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title-box">
                        <h3>Recent Assets For Maintenance</h3>
                    </div>
                </div>
                <div class="panel-body panel-body-table">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="15%">Asset Type</th>
                                    <th width="20%">Asset Name</th>
                                    <th width="15%">Product SKU</th>
                                    <th width="15%">Send Date </th>
                                    <th width="15%">Image</th>
                                    <!-- <th width="35%">Purchase Amount</th> --
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(!empty($assets_maintenance)){
                                    foreach ($assets_maintenance as $assets_maintenance_row) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo ucfirst($assets_maintenance_row->type); ?>
                                    </td> 
                                    <td>
                                        <?php echo ucfirst($assets_maintenance_row->asset_name); ?>
                                    </td>
                                    <td>
                                        <?php echo $assets_maintenance_row->barcode_number; ?>
                                    </td>
                                    <td>
                                        <?php echo $assets_maintenance_row->date; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($assets_maintenance_row->photo)){ ?>
                                        <img  style="width:60px;height:30px;"  src="<?php echo base_url(); ?>uploads/assetimages/<?php echo $assets_maintenance_row->photo ?>">
                                        <?php } else { ?>
                                        <img  style="width:60px;height:30px;"  src="<?php echo base_url(); ?>uploads/employee_images/default.jpg ?>">   
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="5"><center>No record found..!!</center></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <?php if(!empty($assets_maintenance)) { if(count($assets_maintenance) > 5) { ?>
                        <a href="<?php echo site_url('Assets_maintenance/index'); ?>" class="btn btn-xs btn-info pull-right">View more..</a>
                    <?php } } ?>

                </div>
            </div>
            <!-- END Assets BLOCK --
        </div> -->
    <?php } ?>
     
    </div>                    
</div>
<!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
   
   function dashboard_search_barcode()
   {
        var barcode_number=$("#barcode").val().trim();
         var site_url = $('#site_url').val();
         if(barcode_number=="")
          {
            $("#barcode").css("border-color","red");
            setTimeout(function(){$("#error_title").html("&nbsp;");$("#barcode").css("borderColor","#00A654")},5000)
            $("#barcode").focus();
            return false;
          } 


          window.location = site_url+'/Dashboard/dashboard_search_barcode/'+barcode_number;

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
function auto_search_barcode(){
     
         src = '<?= site_url('Dashboard/get_asset_name'); ?>';
          $("#barcode").autocomplete({
           
            appendTo: "#searchbox",
            source: function(request, response) {
           
              $(".ui-autocomplete").html('<img src="<?= base_url('/assets/default.gif'); ?>" alt="">');
               $.getJSON(src, {search : request.term}, 
                response);
            },
          });
 }     
   auto_search_barcode()

</script>
<script async defer
   src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVNitSwVJ5IoV9bQFx0IQ6UDtNcNDy6OM&callback=initMap"></script>
<script>
  function initMap() {
     var map = new google.maps.Map(document.getElementById('map') , {
       zoom: 8,
       center: {lat: 20.5937, lng: 78.9629}
     });
   
   
    // var image = '<?php echo base_url();?>assets/images/Truck_Pin.png';
    //mumbai
   <?php foreach($branches as $row1){ 
     
      ?>
   var beachmarker4 = new google.maps.Marker({
       position: {lat:<?= $row1->latitude; ?> , lng:<?= $row1->longitude; ?>},
       map: map,
      // icon: image,
   title: 'Branch:<?= $row1->branch_title; ?>'
     });
   beachmarker4.addListener('click', function() {
       map.setZoom(8);
       map.setCenter(beachmarker4.getPosition());
     });
   <?php } ?>
   
   
   }
</script>