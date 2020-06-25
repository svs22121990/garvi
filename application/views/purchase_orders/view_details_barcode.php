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
        <h3 class="panel-title"><strong>Barcode view details</strong></h3>
        <ul class="panel-controls">
          <li><a onclick="window.history.back()"  href="#"><span class="fa fa-arrow-left"></span></a></li>
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
                      <th>Asset Type</th>
                   
                       <?php if($po->branch_id!='0') { ?>
                        <th>Branch</th>
                      <?php } ?>
                      <th>PO Number</th>
                      <th>PO Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?= $po->shop_name ?></td>
                      <td><?= $po->type; ?></td>
                       <?php if($po->branch_id!='0') { ?>
                          <td><?= $po->branch; ?></td>
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
              <b>Barcode view details
              <?php if(!empty($row)) { ?>
                - <?= $row->barcode_number; ?>
              <?php }?>
            </b>
         <?php if(!empty($row)) { if($row->is_received!='Yes'){ ?>
         <form method="post" action="<?=site_url('Purchase_orders/addTostock')?>">
          <input type="hidden" name="asset_id" value="<?=$getData->id; ?>">
          <input type="hidden" name="quantity" value="<?=$row->quantity; ?>">
          <input type="hidden" name="purchase_received_detail_barcode_id" value="<?=$row->id; ?>">
          <input type="hidden" name="purchase_received_detail_id" value="<?=$row->purchase_received_detail_id; ?>">
          <input type="hidden" name="branch_id" value="<?=$po->branch_id; ?>">
          <input type="hidden" name="asset_type_id" value="<?=$po->asset_type_id; ?>">
          <input type="hidden" name="order_number" value="<?=$po->order_number; ?>">
         

           <button type="submit" class="btn btn-primary btn-sm no-print pull-right" style="margin-top:-5px">Add to Stock</button>
         </form>
       <?php }else{?>
          <p  class="label label-danger pull-right" style="margin-top:-5px">Asset added into stock</p>

       <?php } }?>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table id="example" class="table table-striped table-border">
                <thead>
                  <tr>
                    <th width="10%">Category</th>
                    <th width="10%">Sub-Category</th>
                    <th width="10%">Asset</th>
                    <th width="7%">Barcode Image</th>
                    <th width="4%">Quantity</th>
                  <!--   <th width="7%">Unit</th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <?php if(!empty($row)) { ?>
                      <td>
                        <?php if($getData != '') { ?>
                          <?= $getData->title; ?>
                        <?php } else { ?>
                          N/A
                        <?php } ?>
                      </td>
                      <td>
                        <?= $getData->sub_cat_title ; ?>
                      </td>
                      <td>
                        <?= $getData->asset_name; ?>
                      </td>
                      
                      <td>
                        <img src="<?= base_url('assets/purchaseOrder_barcode/'.$row->barcode_image )?>" height="60px" width="60px;">
                      </td> 
                      <td><?= $row->quantity; ?></td>
                    <!--   <td><?= $getData->unit; ?></td> -->
                    <?php } ?>
                  </tr> 
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
<script type="text/javascript">
  var url="";
  var actioncolumn="";
</script>

<?php $this->load->view('common/footer');?>