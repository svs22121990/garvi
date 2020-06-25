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
                                          <div class="panel-heading">
                                            Purchase order details
                                          </div>
                                          <div class="panel-body">
                                            <div class="table-responsive">
                                              <table id="example" class="table table-striped table-border">
                                                <thead>
                                                  <tr>
                                                    <th width="10%">Sr. No.</th>
                                                    <th width="15%">Brand</th>
                                                    <th width="30%">Asset name</th>
                                                    <th width="5%">Quantity</th>
                                                    <th width="5%">Unit</th>
                                                    <th width="15%">Status</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php $sr=0; foreach($pod as $row){ ?>
                                                  <tr>
                                                    <td><?= ++$sr; ?></td>
                                                    <td><?php if(!empty($row->brand_name)){ echo ucfirst($row->brand_name); } else { echo '-'; } ?></td>
                                                    <td><?php if(!empty($row->asset_name)){ echo ucfirst($row->asset_name); } else { echo '-'; } ?></td>
                                                    <td><?= $row->quantity;?></td>
                                                    <td><?php if(!empty($row->unit)){ echo $row->unit; }else{ echo '-'; } ?></td>
                                                    <td>
                                                      <?php if($row->status == 'Pending'){ ?>
                                                      <button class="btn btn-danger btn-sm" style="cursor:default">Pending</button>
                                                      <?php } elseif($row->status == 'Received') { ?>
                                                      <button class="btn btn-success btn-sm" style="cursor:default">Received</button>
                                                      <?php } ?>
                                                    </td>
                                                  </tr>
                                                  <?php } ?>                          
                                                </tbody>
                                              </table>  
                                            </div>  
                                            <div class="col-md-12">
                                              <div class="col-md-5"></div>
                                              <div class="col-md-2"><a target="_blank" href="<?= site_url('Purchase_orders/read/'.$id.'/?flag=print') ?>"><button type="button" class="btn btn-primary">Print</button></a></div>
                                              <div class="col-md-5"></div>
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