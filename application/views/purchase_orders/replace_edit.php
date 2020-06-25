<style>
textarea {
    resize: none;
}
</style>
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
                      </div>  
                    </div> 
                  </div>  

                  <div class="panel panel-default">                                        
                    <div class="panel-heading">
                      Update Replace Purchase order
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="<?= site_url('Purchase_replace/update_action'); ?>" class="form-horizontal" onSubmit="return checkReplaceValidation();">
                        <div class="row">
                            <div class="col-md-12" style="margin-bottom: 1%">
                              <div class="col-md-6">
                                <label for="vendor">Product Name  </label>
                                <div><?php echo ucfirst($listData->prd_name); if(!empty($listData->brand_name)) { echo "-".$listData->brand_name; } ?></div>
                              </div>
                             
                              <div class="col-md-3">
                                <label for="vendor">Weight  </label>
                                <div><?= $listData->weight; ?></div>
                              </div>
                              <div class="col-md-3">
                                <label for="vendor">Unit  </label>
                                <div><?= $listData->product_unit ?></div>
                              </div>
                            </div>
                            <div class="col-md-12" style="margin-bottom: 1%">  
                              <div class="col-md-6">
                                <label for="vendor">Replacement Quantity  </label>
                                <div><?= $listData->quantity; ?></div>
                              </div>
                            

                              <div class="col-md-6">
                                <label for="vendor">Remain Quantity  </label>
                                <div><?= $listData->remaining_quantity; ?></div>
                              </div>
                            </div>
                            <div class="col-md-12"> 
                              <div class="col-md-6">
                                <label for="vendor">Replace Quantity </label>
                                <input type="text" name="replaced_quantity" id="replaced_quantity" class="form-control" onkeypress="only_number(event)" maxlength="4">
                                <input type="hidden" id="remaining_quantity" value="<?= $listData->remaining_quantity; ?>">
                                &nbsp;<span id="error1" style="color:red"></span>&nbsp;
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="col-md-6">
                                <input type="hidden" name="id" value="<?= $listData->id ?>">
                                <button class="btn btn-primary" type="submit" id="submit">Submit</button>
                                <button onclick="window.history.back()" class="btn btn-default" type="button">Cancel</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>  
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
<script type="text/javascript">
  function checkReplaceValidation() {
    var replaced_quantity = $("#replaced_quantity").val();
    var remaining_quantity = $("#remaining_quantity").val();
    if($.trim(replaced_quantity)=="" || (replaced_quantity <= 0)){    
      $("#error1").fadeIn().html("Please enter quantity");
      setTimeout(function(){ $("#error1").fadeOut(); }, 3000);
      $("#replaced_quantity").focus();
      return false; 
    }

    if($.trim(replaced_quantity) > $.trim(remaining_quantity)){    
      $("#error1").fadeIn().html("Quantity must be less than or equal to remain quantity");
      setTimeout(function(){ $("#error1").fadeOut(); }, 3000);
      $("#replaced_quantity").focus();
      return false; 
    }
  }
  function only_number(event)
{
  var x = event.which || event.keyCode;
  //console.log(x);
  if((x >= 48 ) && (x <= 57 ) || x == 8 | x == 9 || x == 13 )
  {
    return;
  }else{
    event.preventDefault();
  }    
}
</script>