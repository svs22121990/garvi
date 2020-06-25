<div class="content-wrapper"> 
<section class="content-header">
     <h1>
        &nbsp;
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?= site_url('Dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="<?= site_url('Purchase_orders')?>">Purchase Order</a></li>
          <li class="active">View Replace Purchase Order</li>
      </ol>
    </section>   
  <section class="content">   
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-danger">
          <div class="box-header with-border">                           
            <div class="col-md-8 text-light-blue"><h3>View Replace Purchase Order</h3></div>
            <div class="col-md-4">
                <button class="btn btn-primary btn-circle pull-right" onclick="window.history.back()" title="Back" data-toggle="tooltip"><i class="fa fa-hand-o-left"></i></button>  
            <div class="clearfix"></div>   
          </div>
          <div class="box-body">            

              <div class="row">
                <div class="col-md-12">  

                  <div class="panel panel-default">                    
                    <div class="panel-body">
                      <div class="table-responsive">
                        <table id="example" class="table table-border">
                          <thead>
                            <tr>
                              <th>Warehouse</th>
                              <th>Vendor</th>
                              <th>PO Number</th>
                              <th>PO Date</th>
                              <th>PO Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><?= $listData->name; ?></td>
                              <td><?= $listData->shop_name ?></td>
                              <td><?= $listData->order_number; ?></td>
                              <td><?= date('d-m-Y',strtotime($listData->purchase_date)); ?></td>
                              <td>
                                <?php if($listData->status == 'Pending'){ ?>
                                <button class="btn btn-danger btn-sm" style="cursor:default">Pending</button>
                                <?php } elseif($listData->status == 'Received') { ?>
                                <button class="btn btn-success btn-sm" style="cursor:default">Received</button>
                                <?php } ?>
                              </td>
                            </tr>                          
                          </tbody>
                        </table>
                        <table id="example" class="table table-border">
                          <thead>
                            <tr>
                              <th>Product Name</th>
                              <th>Weight</th>
                              <th>Unit</th>
                              <th>For Replacement Quantity</th>
                              <th>Remain Quantity</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><?php echo $listData->prd_name; if(!empty($listData->brand_name)) { echo "-".$listData->brand_name; } ?></td>
                              <td><?= $listData->weight; ?></td>
                              <td><?= $listData->product_unit; ?></td>
                              <td><?= $listData->quantity; ?></td>
                              <td><?= $listData->remaining_quantity; ?></td>
                            </tr>                          
                          </tbody>
                        </table>  
                      </div>  
                    </div> 
                  </div>  

                  <div class="panel panel-default">                                        
                    <div class="panel-heading">
                      Replace Purchase Order Details
                    </div>
                    <div class="panel-body">
                      <div class="table-responsive">
                        <table id="" class="table table-striped table-border example1">
                          <thead>
                            <tr>
                              <th width="50px">Sr. No.</th>
                              <th>Quantity</th>
                              <th>Replaced Date</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $sr=0; foreach($replaceData as $rowData){

                            	if($listData->product_type == 'Open')
								        {
								            if($listData->product_unit == 'kg' || $listData->product_unit == 'ltr')
								            {
								               $rowData->quantity = $rowData->quantity / 1000;
								            } else {
								               $rowData->quantity = $rowData->quantity;
								            }    
								        }
                            	?>
                            <tr>
                              <td><?= ++$sr; ?></td>
                              <td><?= $rowData->quantity ?></td>
                              <td><?= $rowData->date ?></td>
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
  </section>
</div>

<script>
    var url = '';
    var actioncolumn = '';
</script>