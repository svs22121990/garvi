<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
 <?= $breadcrumbs ?>
<div class="page-content-wrap">
     <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal filter_data_form" action="<?php //echo $action; ?>" method="post">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?php echo $heading; ?> </h3>
                </div>
                <div class="panel-body">  
                  <?php if($GetData->po_number=="Yes") { ?>                                                                      
                   <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">PO Number</label>
                        <div class="col-md-10">
                          <input type="text" class="form-control" name="po_number" placeholder="PO Number" id="po_number" value=""/>
                        </div>
                      </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->vendor_name=="Yes") { ?>
                    <div class="col-md-4"> 
                        <div class="form-group">                                        
                          <label class="col-md-12">Vendor Name</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" name="name" placeholder="Vendor Name" id="vendor_name" value=""/>
                              </div>
                          </div>
                    </div> 
                    <?php } ?> 
                    <?php if($GetData->shop_name=="Yes") { ?>
                    <div class="col-md-4"> 
                        <div class="form-group">                                        
                          <label class="col-md-12">Shop Name</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" name="shop_name" placeholder="Shop Name" id="shop_name" value=""/>
                          </div>
                          </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->purchase_date=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Purchase Date</label>
                        <div class="col-md-10">
                          <input type="text" class="form-control" name="purchase_date" placeholder="Purchase Date" id="purchase_date" value=""/>
                        </div>
                      </div>
                    </div> 
                    <?php } ?> 
                    <?php if($GetData->quotation_no=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Quotation No</label>
                        <div class="col-md-10">
                          <input type="text" class="form-control" name="quotation_no" placeholder="Quotation No" id="quotation_no" value=""/>
                        </div>
                      </div>
                    </div> 
                    <?php } ?>
                     <?php if($GetData->po_status=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">PO Status</label>
                        <div class="col-md-10">
                          <select class="form-control select" name="po_status" id="po_status" >
                              <option value="">Select PO Status </option>
                              <option value="Pending">Pending</option>
                              <option value="Received">Received</option>
                           </select>
                        </div>
                      </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->category_name=="Yes") { ?>
                    <div class="col-md-4"> 
                        <div class="form-group">                                        
                          <label class="col-md-12">Category</label>
                          <div class="col-md-10">
                              <select class="form-control select" name="category_id" id="category_id" onchange="return get_sub_cat(this.value)">
                                <option value="">Select Category </option>
                                <?php if(!empty($categories)) { foreach ($categories as $cat) { ?>
                                <option value="<?php echo $cat->id; ?>"><?php echo $cat->title; ?></option>
                                <?php } } ?>
                            </select>
                          </div>
                          </div>
                    </div> 
                    <?php } ?>
                   <?php if($GetData->sub_category_name=="Yes") { ?>
                    <div class="col-md-4">
                        <div class="form-group">                                        
                          <label class="col-md-12">Sub Category</label>
                          <div class="col-md-10">
                            <select class="form-control select" name="sub_cat_id" id="sub_cat_id"  >
                                <option value="0">Select Sub Category </option>
                            </select>
                              </div>
                          </div>
                    </div>
                    <?php } ?> 
                    <?php if($GetData->asset_name=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Asset Name</label>
                        <div class="col-md-10">
                          <input type="text" class="form-control" name="asset_name" placeholder="Asset Name" id="asset_name" value=""/>
                        </div>
                      </div>
                    </div> 
                    <?php } ?> 
                    <?php if($GetData->quantity=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Quantity</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" name="quantity" placeholder="Quantity" id="quantity" value=""/>
                          </div>
                      </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->bill_no=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Bill No</label>
                        <div class="col-md-10">
                          <input type="text" class="form-control" name="bill_no" placeholder="Bill No" id="bill_no" value=""/>
                        </div>
                      </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->brand_name=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Brand</label>
                        <div class="col-md-10">
                            <select class="form-control select" name="brand_id" id="brand_id" >
                              <option value="">Select Brand </option>
                              <?php if(!empty($brands)) { foreach ($brands as $brand) { ?>
                              <option value="<?php echo $brand->id; ?>"><?php echo $brand->brand_name; ?></option>
                              <?php } } ?>
                          </select>
                        </div>
                      </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->asset_type=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Asset Type</label>
                        <div class="col-md-10">
                            <select class="form-control select" name="asset_type_id" id="asset_type_id" >
                              <option value="">Select Asset Type </option>
                              <?php if(!empty($asset_type)) { foreach ($asset_type as $type) { ?>
                              <option value="<?php echo $type->id; ?>"><?php echo $type->type; ?></option>
                              <?php } } ?>
                          </select>
                        </div>
                      </div>
                    </div> 
                    <?php } ?> 
                    <?php if($GetData->unit_name=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Unit</label>
                        <div class="col-md-10">
                            <select class="form-control select" name="unit_id" id="unit_id" >
                              <option value="">Select Unit </option>
                              <?php if(!empty($unit)) { foreach ($unit as $un) { ?>
                              <option value="<?php echo $un->id; ?>"><?php echo $un->unit; ?></option>
                              <?php } } ?>
                          </select>
                        </div>
                        </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->payment_type=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Payment Type</label>
                        <div class="col-md-10">
                            <select class="form-control select" name="payment_type" id="payment_type" onchange="return get_cheque()">
                              <option value="">Select Payment Type </option>
                              <?php if(!empty($payment_types)) { foreach ($payment_types as $payment) { ?>
                              <option value="<?php echo $payment->id; ?>"><?php echo $payment->type; ?></option>
                              <?php } } ?>
                          </select>
                        </div>
                        </div>
                    </div> 
                    <?php } ?>
                     <?php if($GetData->account_no=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Account No</label>
                        <div class="col-md-10">
                          <input type="text" class="form-control" name="account_no" placeholder="Account No" id="account_no" value=""/>
                            </div>
                        </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->check_no=="Yes") { ?>
                    <div class="col-md-4"> 
                      <div class="form-group">                                        
                        <label class="col-md-12">Cheque No</label>
                        <div class="col-md-10">
                          <input type="text" class="form-control" name="cheque_no" placeholder="Cheque No" id="check_no" value=""/>
                        </div>
                        </div>
                    </div> 
                    <?php } ?>
                   
                    <?php if($GetData->check_date=="Yes") { ?>
                    <div class="col-md-4"> 
                        <div class="form-group">                                        
                          <label class="col-md-12">Cheque Date</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" name="cheque_date" placeholder="Cheque Date" id="check_date" value=""/>
                          </div>
                      </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->paid_by=="Yes") { ?>
                    <div class="col-md-4"> 
                        <div class="form-group">                                        
                          <label class="col-md-12">Paid By</label>
                          <div class="col-md-10">
                           <input type="text" class="form-control" name="name" placeholder="Paid By" id="name" value=""/>
                            </div>
                        </div>
                    </div> 
                    <?php } ?>
                    <?php if($GetData->bank_name=="Yes") { ?>
                    <div class="col-md-4"> 
                        <div class="form-group">                                        
                          <label class="col-md-12">Bank Name</label>
                          <div class="col-md-10">
                                    <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" id="bank_name" value=""/>
                              </div>
                          </div>
                    </div> 
                    <?php } ?>
                 </div>
                <div class="panel-footer">
                    <button type="button" onclick="return validations()" class="btn btn-success reload_table_1"><i class="fa fa-search"></i>&nbsp;Filter Data</button>
                    <a href="<?php echo site_url('Report/index/'.$this->uri->segment(3)); ?>" class="btn btn-default"><i class="fa fa-retweet" aria-hidden="true"></i>&nbsp;Reset</a>
                </div>
            </div>
            </form>

             <div class="panel panel-default">
                  <div class="panel-body">

                      <table class="table example_datatable_report">
                        <?php if($GetData->primary_table=="Purchase_order"){ ?>
                          <thead>
                              <tr>
                                  <th>PO Number </th>
                                  <th>Vendor Name</th>
                                  <th>Shop Name</th>
                                  <th>Purchase Date</th>
                                  <th>Quotation No</th>
                                  <th>PO Status</th>
                                  <th>Category</th>
                                  <th>Sub Category</th>
                                  <th>Asset</th>
                                  <th>Quantity</th>
                                  <th>Bill No</th>
                              </tr>
                          </thead>
                        <?php } else if($GetData->primary_table=="Vendor"){ ?> 
                           <thead>
                              <tr>
                                  <th>Vendor Name</th>
                                  <th>Shop Name</th>
                                  <th>Payment Type</th>
                                  <th>Cheque No</th>
                                  <th>Account No</th>
                                  <th>Cheque Date</th>
                                  <th>Paid By</th>
                                  <th>Bank Name</th>
                              </tr>
                          </thead>
                       
                        <?php }else if($GetData->primary_table=="Assets"){ ?> 
                           <thead>
                              <tr>
                                  <th>Category</th>
                                  <th>Sub Category</th>
                                  <th>Asset</th>
                                  <th>Quantity</th>
                                  <th>Brand</th>
                                  <th>Asset Type</th>
                                  <th>Unit</th>
                              </tr>
                          </thead>
                        <?php } ?>
                      </table>
                  </div>
              </div>
          </div>
    </div>                    
 </div>
 <script type="text/javascript">
  /*var url="<?= site_url('Scrap_assets/ajax_manage_page'); ?>";
  var actioncolumn="6";*/
  var url= "";
  var actioncolumn='';
  var url1= "<?php echo site_url('Report/ajax_report_list/'.$GetData->id.'/'.$GetData->primary_table)?>";
  var actioncolumn1=0;
  
</script>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
  function get_sub_cat(id)
  {
    var datastring = "id="+id;
    $.ajax({
      type:'post',
      url:"<?= site_url('Report/get_sub_cat');?>",
      data:datastring,
      success:function(returndata){ 
        $("#sub_cat_id").html(returndata).selectpicker('refresh');
         }
       });
     } 

  function get_cheque()
  {
    var payment_type=$('#payment_type').val();
    alert(payment_type);
  }   
</script>
 <script type="text/javascript">
    table1 = $('.example_datatable_report').DataTable({ 

          "oLanguage": {
         "sProcessing": "<img src='<?= base_url()?>assets/server_side/media/images/ajax-loader.gif'>"
    },
    
        "scrollX":true,
        "responsive": {
            "details": {
                renderer: function ( api, rowIdx ) {
                var data = api.cells( rowIdx, ':hidden' ).eq(0).map( function ( cell ) {
                    var header = $( api.column( cell.column ).header() );
                    return  '<p style="color:#00A">'+header.text()+' : '+api.cell( cell ).data()+'</p>';
                } ).toArray().join('');

                return data ? $('<table/>').append( data ) :    false;
                }
            }
            },
            "columns": [
            <?php if($GetData->primary_table=="Purchase_order"){ ?>
        null,null, null, null, null, null, null, null, null, null,null,
        <?php }else if($GetData->primary_table=="Vendor"){ ?>
        null,null, null, null, null, null, null, null,
      <?php }else if($GetData->primary_table=="Assets"){ ?>
        null,null, null, null, null, null,null
      <?php } ?>
        ],
            "dom": 'Bfrtip',
            "lengthMenu": [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
             "buttons": [
            'pageLength','colvis','copy', 'csv', 'excel', 'print'
        ],

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "lengthMenu" : [[10,25, 100,200,500,1000,2000,4000,10000], [10,25, 100,200,500,1000,2000,4000,10000 ]],"pageLength" : 10,
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url1,
            "type": "POST",
            "data": function(d) {
                  d.Foo = 'gmm';
                  d.Filter1 = $(".filter").val();
                  d.FooBar = "foobarz";
                  d.SearchData1 = $(".filter_search_data1").val();
                  d.SearchData = $(".filter_search_data").val();
                  d.SearchString = $(".search_string_val").val();
                  d.FormData = $(".filter_data_form").serializeArray();
              }                
        },
        <?php if($GetData->primary_table=="Order"){ ?>
        "fnDrawCallback": function() {
        var api = this.api()
        var json = api.ajax.json();
        $(api.column(8).footer()).html(json.total);
      },
      <?php } ?>
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    

    });
    $(document).ready(function() {

     $(".filter_search").keypress(function(e) {
            // Enter pressed?
            if(e.which == 10 || e.which == 13) {
              //console.log(e.which);
              // $(".reload_table").click(); 
              var SearchData = $(".SearchData").val();
      
                table
                .search(SearchData)
              .draw();   
            }
        });

     $(".reload_table_1").click(function(){
        table1
        .draw();   
        });

     $(".reset_btn").click(function(){
      $('.select2').val('');
      $(".select2").select2();
        table1
        .draw();   
        });
    });
 </script>