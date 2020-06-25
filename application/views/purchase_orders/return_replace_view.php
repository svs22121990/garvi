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
                                      <div class="col-md-12">  

                                        <div class="panel panel-default">                    
                                          <div class="panel-body">
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
                                                      <?php }  else{ ?>

                                                      <button class="btn btn-danger btn-sm" style="cursor:default">Rejected</button>
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
                                           <b> Return / Request  details </b>
                                          </div>
                                          <div class="panel-body">
                                            <div class="table-responsive">
                                              <table id="example" class="table table-striped table-border">
                                                <thead>
                                                  <tr>
                                                    <th width="10%">Sr. No.</th>
                                                    <th width="25%">Asset name</th>
                                                    <th width="10%">Product SKU</th>
                                                    <th width="10%">Replace By</th>
                                                  
                                                    <th width="25%">Remark</th>
                                                    <th width="10%">Status</th>
                                                    <th width="5%">Type</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php $sr=0; foreach($prrd as $row){ 
                                                    //print_r($row);
                                                    ?>
                                                  <tr>
                                                    <td><?= ++$sr; ?></td>
                                                    <td><?php if(!empty($row->asset_name)){ echo ucfirst($row->asset_name); } else { echo '-'; } ?></td>
                                                    <td><?= $row->barcode_number ;?></td>
                                                    <td><?php  if($row->type == 'Replace'){
                                                      if($row->status == 'Approved') {
                                                     echo $row->replace_barcode_no ; }else{
                                                        echo "-";
                                                      } }else{
                                                        echo "-";
                                                      } ?>
                                                    </td>
                                                    <td><?= $row->remark;?></td>
                                                    <td>   <?php if($row->status == 'Pending'){ ?>
                                                      <label class="label label-warning " style="cursor:default">Pending</label>
                                                      <?php } elseif($row->status == 'Approved') { ?>
                                                      <label class="label label-success " style="cursor:default">Approved</label>
                                                      <?php }elseif($row->status == 'Rejected') { ?>
                                                      <label class="label label-danger " style="cursor:default">Rejected</label>
                                                      <?php } ?></td>
                                                    <td><?php  if($row->type == 'Return'){ ?><label class="label label-warning">Return</label><?php }else{ ?> <label class="label label-primary"> Replace </label> <?php } ?></td>
                                                    
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