<?php  $this->load->view('common/header');
 $this->load->view('common/left_panel'); ?>
 <?php $created_by = $_SESSION[SESSION_NAME]['id']; ?> <!-- START BREADCRUMB -->
 <?= $breadcrumbs ?> <!-- END BREADCRUMB --> <!-- PAGE TITLE --> 
 <div class="page-title">                         <!-- <h3 class="panel-title"><?= $heading
 ?></h3> --> </div>  <!-- PAGE CONTENT WRAPPER -->            
 <div class="page-content-wrap">                
  <div class="row">
    <div class="col-md-12">
      <form class="form-horizontal" method="post" action="<?php echo $action;?>"  enctype="multipart/form-data">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><strong><?= $heading ?></h3>
              <ul class="panel-controls">
               <li><a href="<?= site_url('Products/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
             </ul>
           </div>
           <div class="panel-body">
            <div class="row">
              <div class="col-md-12" style="padding: 0;">

                <!-- <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Purchase Date <span style="color: red">*</span> <span  id="purchase_date_error" style="color: red"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="purchase_date" id="purchase_date" class="form-control datepicker" placeholder="Purchase Date">
                    </div>
                  </div>
                </div> -->

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Bill Number <span style="color: red">*</span> <span  id="bill_no_error" style="color: red"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="bill_no" id="bill_no" class="form-control" placeholder="Bill Number">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Bill Date <span style="color: red">*</span> <span  id="bill_date_error" style="color: red"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="bill_date" id="bill_date" class="form-control datepicker" placeholder="Bill Date">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Received From <span style="color: red">*</span> <span  id="received_from_error" style="color: red"></span></label>
                    <div class="col-md-11">
                      <select name="received_from" id="received_from" class="form-control">
                      	<option value="">Select User</option>
                      	<?php 
                      	if(!empty($users)) {
                      		foreach ($users as $user) {
                      	?>
                      	<option id="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                      	<?php 
                      		}
                      	}
                      	?>
                      </select>
                    </div>
                  </div>
                </div>

              </div>

      </div>
    <div class="col-md-12" style="padding: 0;">
      <div class="">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th> Purchase Date <span style="color: red">*</span><span style="color: red"></span></th>
              <th> Category  <a href="#myModalCategory" class="pull-right" data-toggle="modal" data-target="" title="Add new Category">(<i class="fa fa-plus" onclick="blankValue()"></i>)</a><span style="color: red">*</span> <span  id="category_id_error" style="color: red"></span></th>

              <th>Product Type <a href="#myModalAssetType" class="pull-right" data-toggle="modal" data-target="" title="Add new Product Type">(<i class="fa fa-plus" onclick="blankValue()"></i>)</a><span style="color: red">*</span> <span  id="asset_type_id_error" style="color: red"></span></th>

              <th> Product Type 2 <span style="color: red">*</span> </th>
              <th> SKU  <span id="error_sku"></span></th>
              <th> Product Name <span style="color: red">*</span><span style="color: red" id="asset_name_error"></span></th>
              <th> Quantity <span style="color: red">*</span><span style="color: red" id="error_quantity"></span></th>
              <th> Selling Price <span style="color: red">*</span><span style="color: red"id="error_price"></span></th>
              <th>Total</th>
              <th> GST % <span style="color: red">*</span><span  id="error_gst_percent"></span></th>
              <th> HSN <span style="color: red">*</span><span id="error_hsn"></span></th>
              <th> LF No. <span style="color: red">*</span><span style="color: red" id="lf_no_error"></span></th>
              <th class="text-center"> <a  href="javascript:void(0)" class="btn  btn-sm btn-info"  onclick="addrow()" ><i class="fa fa-plus"></i></a></th>
            </tr>
          </thead>
          <tbody id="professorTableBody">
            <tr class="trRow">
            <td>
            <input type="text" name="product_purchase_date[]" id="product_purchase_date1" class="form-control datepicker" autocomplete="off" placeholder="Purchase Date">
            </td>
              <td>
                <select class="form-control" name="category_id[]" id="category_id1" onchange="getGST(this.value,$(this).closest('tr').index());">
                  <option value="">--Select Product Category--</option>
                  <?php foreach($category_data as $category_dataRow) { ?>                                                         
                  <option value="<?php echo $category_dataRow->id?>"><?php echo $category_dataRow->title?></option>    
                  <?php } ?>  
                </select>
              </td>
              <td>
                <select class="form-control" name="asset_type_id[]" id="asset_type_id1">
                        <option value="">--Select Product Type Name--</option>
                        <?php foreach($asset_type_data as $asset_type_dataRow) { ?>
                        <option value="<?php echo $asset_type_dataRow->id?>"><?php echo $asset_type_dataRow->type ?></option>
                        <?php } ?>
                      </select>
              </td>
              <td>
                <select class="form-control" name="asset_type_2_id[]" id="asset_type_2_id1">
                  <option value="">--Select Product Type 2 Name--</option>
                  <?php foreach($productTypes as $asset_type_dataRow) { ?>
                  <option value="<?=$asset_type_dataRow->id?>"><?=$asset_type_dataRow->label?></option>
                  <?php } ?>
                </select>
              </td>
              <td>
                <input type="text" class="form-control" name="sku[]" id="sku1" placeholder="Enter SKU" autocomplete="off" onchange="checkassetduplicate(this.value,$(this).closest('tr').index())">
              </td>
              <td>
                <input type="text" class="form-control" name="asset_name[]" id="asset_name1" placeholder="Enter Product Name" autocomplete="off" onchange="checkassetduplicate(this.value,$(this).closest('tr').index())">
              </td>
              <td>
                <input type="text" class="form-control qty" name="quantity[]" id="quantity1" placeholder="Enter Quantity" autocomplete="off">
              </td>
              <td>
                <input type="text" class="form-control price" name="product_mrp[]" id="product_mrp1" placeholder="Enter Product Price" autocomplete="off" >
              </td>
              <td>
                <input type="text" class="form-control multTotal" name="multitotal[]"  autocomplete="off" onkeypress="return only_number(event)" readonly="readonly">
              </td>
              
              <td>
                <input type="text" class="form-control gstPercent" name="gst_percent[]" id="gst_percent1" readonly="readonly" placeholder="Enter GST %" autocomplete="off">
              </td>
              <td>
                <input type="text" class="form-control" name="hsn[]" id="hsn1" readonly="readonly" autocomplete="off" placeholder="Enter HSN" autocomplete="off">
              </td>
              <td>
                <input type="text" class="form-control" name="lf_no[]" id="lf_no1" placeholder="Enter LF No." autocomplete="off">
              </td>
              <td class="text-center">
                <input type="hidden" class="sectionA" value="1">
                <a href="javascript:void(0)" onclick="remove_tr($(this).closest('tr').index())" class="btn  btn-sm btn-danger"><i class="fa fa-minus"></i></a>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="6" >&nbsp;<span class="pull-right">Total Amount</span></th>
              <th><input type="text" class="form-control" name="gtotal" id="grandTotal" readonly="readonly" value="0"></th>
              <th colspan="2"><span class="pull-right">Total GST Amount</span></th>
              <th>
                <input type="text" class="form-control" id="totalGST" readonly="readonly" value="0">
              </th>
            </tr>
            <tr>
              <th colspan="6" >&nbsp;<span class="pull-right">Final Total Amount</span></th>
              <th>
              <input type="text" class="form-control" id="finalTotal" readonly="readonly" value="0">
              </th>              
            </tr>
          </tfoot>
        </table>
      </div>
    </div>  
  </div>
</div>                                
<div class="panel-footer">                                                                       
  <button class="btn btn-success" type="submit" onclick="return validateinfo()"><?= $button;?></button>
</div>
</div>
</form>

</div>
</div>                    

</div>
<!-- END PAGE CONTENT WRAPPER -->

<!--add new unit-->
<div class="modal fade" id="myModalunit" role="dialog">
  <div class="modal-dialog">     
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" id="closeUnit">&times;</button>
        <h4 class="modal-title">Add New Unit Type <span id="successEntry" style="color:green"></span></h4>
      </div>
      <div class="modal-body">
       <form method="post" id="unit" onsubmit="return saveData()">
        <label>Unit Type Name:</label> <span style="color:red">*</span> <span id="nameError" style="color:red"></span><br>
        <input type="text" name="name"  class="form-control" id="name" value="" autocomplete="off" size="35"/> &nbsp; 


      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="return saveData()">Submit</button>
      <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
    </div>
  </div>
</div>
</div>
<!--add new unit-->

<div id="myModalCategory" class="modal fade" role="dialog xs">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Category</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12 text-success text-center" id="success_message"></div>
            <div class="row">
                <div class="col-md-12">
                  <label>Category Name <span style="color:red;">*</span><span id="error_category_id_title" style="color:red"></span></label>
                  <input type="text" class="form-control" name="category_title" id="category_title" placeholder="Category Name">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="return validation_subcategory();">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
  </div>
</div>

<!--Asset type modal-->
<div class="modal fade" id="myModalAssetType" role="dialog">
  <div class="modal-dialog">     
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" id="assetclosetype">&times;</button>
        <h4 class="modal-title">Add New Product Type <span id="successEntry" style="color:green"></span></h4>
      </div>
      <div class="modal-body">
       <form method="post" id="asset" onsubmit="return saveDataAssetType()">
        <label>Product Type Name:</label> <span style="color:red">*</span> <span id="nameErrorAsset" style="color:red"></span><br>
        <input type="text" name="nametype"  class="form-control" id="nametype" placeholder="Product Type Name" value="" autocomplete="off" size="35"/> &nbsp; <br>

        <!-- <label>Is saleable:</label> <span style="color:red">*</span> <span id="saleableError" style="color:red"></span><br>
        <select class="form-control" name="saleable" id="saleable">
          <option value="">--Select Type--</option>
          <option value="Yes">Yes</option>
          <option value="NO">No</option>
        </select> <br>

        <label>Is barcode:</label> <span style="color:red">*</span> <span id="barcodeError" style="color:red"></span>
        <select class="form-control" name="barcode" id="barcode">
          <option value="">--Select Type--</option> 
          <option value="Yes">Yes</option>  
          <option value="No">No</option>
        </select> -->


      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="return saveDataAssetType()">Submit</button>
      <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
    </div>
  </div>
</div>
</div>
<!--Asset type modal-->



<?php $this->load->view('common/footer');?>
<script type="text/javascript" src="<?= base_url(); ?>assets/date_r_picker/moment.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/date_r_picker/daterangepicker.min.js"></script>
<script>
    $(".datepicker").datepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

  $(document).on('keyup','.price',function(){
    price();
  });
  $(document).on('keyup','.qty',function(){
    price();
  });
  function price()
  {
     var mult = 0;
     var totalGST = 0;
     var finalTotal = 0;
      // for each row:
      $("tr.trRow").each(function () {
          // get the values from this row:
          var $val1 = $('.price', this).val();
          var $val2 = $('.qty', this).val();
          var $total = ($val1 * 1) * ($val2 * 1);
          // set total for the row
          $('.multTotal', this).val($total);
          //consol.log($total);
          mult += $total;

          var $gstPercent = $('.gstPercent', this).val();
          totalGST+= ($gstPercent / 100) * $total;
      });
      $("#grandTotal").val(mult);
      $("#totalGST").val(totalGST);
      $("#finalTotal").val(mult+totalGST);
  }
</script>
<script>
    $(document).ready(function(e){
      src = '<?= site_url('Products/getSubcategory'); ?>';
      $("#subcategory_id").autocomplete({
        appendTo: "#searchbox",
        source: function(request, response) {
          $("#check").val('');
          $(".ui-autocomplete").html('<img src="<?= base_url('assets/default.gif'); ?>" alt="">');
           $.getJSON(src, {search : request.term}, 
            response);
        },
        select: function(event, ui) {$("#check").val(ui.item.sub_cat_title); }
      });
    });
</script> 

<!-- <script type="text/javascript">
  function checkSubcat()
  {
      var subcategory_id = $("#subcategory_id").val();
      var datastring  = "subcategory_id="+subcategory_id;
      $.ajax({            
        type : "post",
        url : "<?php echo site_url('Assets/checkSubcat'); ?>",
        data : datastring,
        success : function(response)
        {
          if(response == 1)
          {
            $("#subcategory_id").val("");
            $("#subcat_error").fadeIn().html("Invalid");
            setTimeout(function(){$("#subcat_error").fadeOut();},5000);
          }
          else
          {                
             //alert("fdh");return false;         
          }           
        }
      });
  }
</script> -->

<script type="text/javascript">
  function onlyImage(sr){
   sr=sr+1;
   var file=$('#photo'+sr).val();
   var filetype = file.split(".");
   ext = filetype[filetype.length-1];  
   //alert(ext);
   if((ext!='png') && (ext!='jpg') && (ext!='jpeg') && (ext!='PNG') && (ext!='JPG'))
   {
    $("#error_photo").fadeIn().html("Invalid format").css('color','red');
    setTimeout(function(){$("#error_photo").fadeOut();},4000);
    $("#photo"+sr).focus();
    $("#photo"+sr).val('');
    return false;
  }
}

function addrow() {
  var y = document.getElementById('professorTableBody'); 
  var new_row = y.rows[0].cloneNode(true); 
  var len = y.rows.length; 
  var _date = new Date();

  var inp15 = new_row.cells[0].getElementsByTagName('input')[0];
  inp15.value = ''; // _date.getDate() + '/' + _date.getMonth() + '/' + _date.getFullYear();
  inp15.id = 'product_purchase_date'+(len+1);
  inp15.class = '';
  inp15.classList.remove('datepicker', 'hasDatepicker');

  var inp12 = new_row.cells[1].getElementsByTagName('select')[0];
  inp12.value = '';
  inp12.id = 'category_id'+(len+1);

  var inp13 = new_row.cells[2].getElementsByTagName('select')[0];
  inp13.value = '';
  inp13.id = 'asset_type_id'+(len+1);

  var inp16 = new_row.cells[3].getElementsByTagName('select')[0];
  inp16.value = '';
  inp16.id = 'asset_type_2_id'+(len+1);

  //var inp1 = new_row.cells[4].getElementsByTagName('input')[0];
  //inp1.value = '';
  //inp1.id = 'sku'+(len+1);

  var inp2 = new_row.cells[5].getElementsByTagName('input')[0];
  inp2.value = '';
  inp2.id = 'asset_name'+(len+1);

  var inp3 = new_row.cells[6].getElementsByTagName('input')[0];
  inp3.value = '';
  inp3.id = 'quantity'+(len+1);

  var inp4 = new_row.cells[7].getElementsByTagName('input')[0];
  inp4.value = '';
  inp4.id = 'product_mrp'+(len+1);

  var inp5 = new_row.cells[8].getElementsByTagName('input')[0];
  inp5.value = '';
  inp5.id = 'total'+(len+1);
  inp5.class = 'multTotal';


  var inp6 = new_row.cells[9].getElementsByTagName('input')[0];
  inp6.value = '';
  inp6.id = 'gst_percent'+(len+1);

  
  var inp7 = new_row.cells[10].getElementsByTagName('input')[0];
  inp7.value = '';
  inp7.id = 'hsn'+(len+1);

  var inp8 = new_row.cells[11].getElementsByTagName('input')[0];
  inp8.value = '';
  inp8.id = 'lf_no'+(len+1);

  $('.sectionA').val(len+1);
  
  y.appendChild(new_row);
    $('#'+inp15.id).datepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
//  $('#'+inp15.id).datepicker();
}


function getGST(val,len) {
	var site_url = $('#site_url').val();
	var dataString = "category_id="+val;
	var url = site_url+"/Products/getGST";
	$.post(url, dataString, function(returndata){
		//alert(returndata);
		var obj = jQuery.parseJSON(returndata);
		
			$('#gst_percent'+(len+1)).val(obj.gst_percent);
			$('#hsn'+(len+1)).val(obj.hsn);
		
	});
}


      function remove_tr(row)
      {  
        var y=document.getElementById('professorTableBody');
        var len = y.rows.length;
        if(len>1)
        {
          var i= (len-1);
          document.getElementById('professorTableBody').deleteRow(row);
        }
      }  


      function checkassetduplicate(val,sr)
      {
        var datastring  = "astName="+val;
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Products/chkAssetName'); ?>",
          data : datastring,
          success : function(response)
          {
           if(response == 1)
           {
            sr=sr+1;
            //$("#asset_name"+sr).val("");
            $("#error_asset_name").fadeIn().html("Product name already exist").css('color','red');
            setTimeout(function(){$("#error_asset_name").fadeOut();},5000);
            }

          }
        });
      }
    </script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>
    <script type="text/javascript">
      function saveData()
      {
        //alert();
        var name = $("#name").val();
        /*var name1 = /^[a-zA-Z -]+$/;*/

        if($.trim(name) == "")
        {
          $("#nameError").fadeIn().html("Please enter Unit type name");
          setTimeout(function(){$("#nameError").fadeOut();},4000);
          $("#name").focus();
          return false;
        }


        var datastring  = "name="+name;
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Products/addDataUnit'); ?>",
          data : datastring,
          success : function(response)
          {
            //alert(response);return false;
            if(response == 1)
            {
              $("#nameError").fadeIn().html("Unit Type name already exist");
              setTimeout(function(){$("#nameError").fadeOut();},8000);
            }
            else
            {
              $("#closeUnit").click(); 
              $("#unit_id").append(response).selectpicker('refresh');
              /*$("#namecat").val("");*/
               // $("#successCountryEntry").fadeIn().html("Unit Type has been Added successfully");                
             }   

           }
         });
      }


      function saveDataAssetType()
      {


        var nametype = $("#nametype").val();
        /*var salable = $("#saleable").val();
        var barcode = $("#barcode").val();*/
        /*var name1 = /^[a-zA-Z -]+$/;*/

        if($.trim(nametype) == "")
        {
          $("#nameErrorAsset").fadeIn().html("Please enter Product type name");
          setTimeout(function(){$("#nameErrorAsset").fadeOut();},4000);
          $("#nametype").focus();
          return false;
        }

        /*if(salable == "")
        {
          $("#saleableError").fadeIn().html("Please select type");
          setTimeout(function(){$("#saleableError").fadeOut();},4000);
          $("#salable").focus();
          return false;
        }
        if(barcode == "")
        {
          $("#barcodeError").fadeIn().html("Please select type");
          setTimeout(function(){$("#barcodeError").fadeOut();},4000);
          $("#barcode").focus();
          return false;
        }*/

        var datastring  = "nametype="+nametype;
        //alert(datastring);
        $.ajax({            
          type : "post",
          url : "<?php echo site_url('Products/addDataAssetType'); ?>",
          data : datastring,
          success : function(response)
          {
           // alert(response);return false;
           if(response == 1)
           {
            $("#nameErrorAsset").fadeIn().html("Product Type name already exist");
            setTimeout(function(){$("#nameError").fadeOut();},8000);
          }
          else
          {                
            $("#assetclosetype").click(); 
            $("#asset_type_id").html(response);
                //$("#successCountryEntry").fadeIn().html("Asset Type has been Added successfully");                
              }           
            }
          });
      }

      function blankValue()
      {
        //alert();
        var nametype = $("#nametype").val("");
        /*var salable = $("#saleable").val("");
        var barcode = $("#barcode").val("");*/
        var name = $("#name").val("");
      }
    </script>
    <script type="text/javascript">
      function warrantydata(type)
      {
        if(type=='Yes')
        {
          $("#warrantyspan").show();
        }
        else if(type=='No')
        {
          $("#warrantyspan").hide();
        }
      }

      function stockData(type)
      {
        if(type=='Yes')
        {
          $("#quantityStock").show();
        }
        else if(type=='No')
        {
          $("#quantityStock").hide();
          $("#quantity").val("");

        }
      }
    </script>

    <script type="text/javascript">
      $(document).ready(function() {
        $('#final_amount').keyup(function() {

          var product_mrp_price = $("#product_mrp").val();
          var final_amount = $("#final_amount").val();

          if(parseInt(final_amount) <= parseInt(product_mrp_price))
          {
           var discounted_price = product_mrp_price - final_amount;                        
           var percentagePrice = (discounted_price/product_mrp_price) *100;                                                             
           $('#discount').val(percentagePrice);
                                //alert(percentagePrice);
                              }
                              else
                              {                           
                                $('#discount').val(0);
                                $('#final_amount').val("");
                              }

                            });
      });
    </script>
    <script type="text/javascript">
     function get_sub_cat(id) {
      var datastring = "id=" + id;                
      $.ajax({
        type: "post",
        url: "<?php echo site_url('Products/getSubcat'); ?>",
        data: datastring,
        success: function(returndata) {
                        //alert(returndata);
                        $('#subcategory_id').html(returndata).selectpicker('refresh');                        
                      }
                    });
    }
  </script>



<script type="text/javascript">
function validateinfo() { 
  //alert("hi");  
  // var purchase_date = $('#purchase_date').val();
  var CurrentMonth = '<?= date('m/d/Y'); ?>';//date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear(); 
  console.log(CurrentMonth);
  //var SelectedDate = 
  //var purchase_date = $('#purchase_date').val(); 
  var bill_no = $('#bill_no').val(); 
  var bill_date = $('#bill_date').val(); 
  var received_from = $('#received_from').val(); 
  var sectionA = $('.sectionA').val();
  
  // if(purchase_date=='') {
  //   $("#purchase_date_error").html("Please select Purchase Date").fadeIn();
  //   setTimeout(function(){$("#purchase_date_error").fadeOut()},5000);
  //   return false;
  // }else if( CurrentMonth >  purchase_date ){
  //   $("#purchase_date_error").html("Please select valid Purchase Date").fadeIn();
  //   setTimeout(function(){$("#purchase_date_error").fadeOut()},5000);
  //   return false;
  // } else 
  if(bill_no=='') {
    $("#bill_no_error").html("Please enter Bill No").fadeIn();
    setTimeout(function(){$("#bill_no_error").fadeOut()},5000);
    return false;
  } else if(bill_date=='') {
    $("#bill_date_error").html("Please select Bill Date").fadeIn();
    setTimeout(function(){$("#bill_date_error").fadeOut()},5000);
    return false;
  }else if(received_from=='') {
    $("#received_from_error").html("Please select Received From").fadeIn();
    setTimeout(function(){$("#received_from_error").fadeOut()},5000);
    return false;
  } else if(sectionA==1) {
    //alert("1");
    var category_id = $('#category_id1').val();
    var asset_type_id = $('#asset_type_id1').val();
    //var sku = $('#sku1').val();
    var product_name = $('#asset_name1').val();
    var quantity = $('#quantity1').val();
    var product_mrp = $('#product_mrp1').val();
    var gst_percent = $('#gst_percent1').val();
    var hsn = $('#hsn1').val();
    var lf_no = $('#lf_no1').val();

    /*alert(category_id);
    alert(asset_type_id);
    alert(sku);      
    alert(product_name);      
    alert(quantity);      
    alert(product_mrp);      
    alert(gst_percent);      
    alert(hsn);      
    alert(lf_no);*/
    if(category_id=='') {
      $("#category_id_error").html("Please select category").fadeIn();
      setTimeout(function(){$("#category_id_error").fadeOut()},5000);
      return false;
    }else if(asset_type_id=='') {
      $("#asset_type_id_error").html("Please select product type").fadeIn();
      setTimeout(function(){$("#asset_type_id_error").fadeOut()},5000);
      return false;
    }else if(product_name=='') {
      $("#asset_name_error").html("Please enter product name").fadeIn();
      setTimeout(function(){$("#asset_name_error").fadeOut()},5000);
      return false;
    }else if(quantity=='') {
      $("#error_quantity").html("Please enter quantity").fadeIn();
      setTimeout(function(){$("#error_quantity").fadeOut()},5000);
      return false;
    }else if(product_mrp=='') {
      $("#error_price").html("Please enter product price").fadeIn();
      setTimeout(function(){$("#error_price").fadeOut()},5000);
      return false;
    }else if(gst_percent=='') {
      $("#gst_percent_error").html("Please enter GST %").fadeIn();
      setTimeout(function(){$("#gst_percent_error").fadeOut()},5000);
      return false;
    }else if(hsn=='') {
      $("#hsn_error").html("Please enter HSN").fadeIn();
      setTimeout(function(){$("#hsn_error").fadeOut()},5000);
      return false;
    }else if(lf_no=='') {
      $("#lf_no_error").html("Please enter LF No.").fadeIn();
      setTimeout(function(){$("#lf_no_error").fadeOut()},5000);
      return false;
    }

  } else if(sectionA > 1) {
    /*alert(sectionA);*/
    for(var i = 1; i <= sectionA; i++) {
      
      var category_id = $('#category_id'+i).val();
      var asset_type_id = $('#asset_type_id'+i).val();
      //var sku = $('#sku'+i).val();
      var product_name = $('#asset_name'+i).val();
      var quantity = $('#quantity'+i).val();
      var product_mrp = $('#product_mrp'+i).val();
      var gst_percent = $('#gst_percent'+i).val();
      var hsn = $('#hsn'+i).val();
      var lf_no = $('#lf_no'+i).val();
      
      /*alert(category_id);
      alert(asset_type_id);
      alert(sku);      
      alert(product_name);      
      alert(quantity);      
      alert(product_mrp);      
      alert(gst_percent);      
      alert(hsn);      
      alert(lf_no);  */  

      if(category_id=='') {
        $("#category_id_error").html("Please select category").fadeIn();
        setTimeout(function(){$("#category_id_error").fadeOut()},5000);
        return false;
      }else if(asset_type_id=='') {
        $("#asset_type_id_error").html("Please select product type").fadeIn();
        setTimeout(function(){$("#asset_type_id_error").fadeOut()},5000);
        return false;
      }else if(product_name=='') {
        $("#asset_name_error").html("Please enter Product Name").fadeIn();
        setTimeout(function(){$("#asset_name_error").fadeOut()},5000);
        return false;
      }else if(quantity=='') {
        $("#error_quantity").html("Please enter quantity").fadeIn();
        setTimeout(function(){$("#error_quantity").fadeOut()},5000);
        return false;
      }else if(product_mrp=='') {
        $("#error_price").html("Please enter product price").fadeIn();
        setTimeout(function(){$("#error_price").fadeOut()},5000);
        return false;
      }else if(gst_percent=='') {
        $("#gst_percent_error").html("Please enter GST %").fadeIn();
        setTimeout(function(){$("#gst_percent_error").fadeOut()},5000);
        return false;
      }else if(hsn=='') {
        $("#hsn_error").html("Please enter HSN").fadeIn();
        setTimeout(function(){$("#hsn_error").fadeOut()},5000);
        return false;
      }else if(lf_no=='') {
        $("#lf_no_error").html("Please enter LF No.").fadeIn();
        setTimeout(function(){$("#lf_no_error").fadeOut()},5000);
        return false;
      }
    }
  }
}
</script>


<script type="text/javascript">
 function validation_subcategory()
 {
      var category_id_title = $("#category_id_title").val();
      //var subcategory_title = $("#subcategory_title").val();
      var site_url = $("#site_url").val(); 
      if(category_id_title=="") 
      {
          $("#error_category_id_title").fadeIn().html("Please enter category name");
          setTimeout(function(){$("#error_category_id_title").fadeOut();},3000);
          $("#category_id_title").focus();
          return false;
      }
      
      var site_url = $('#site_url').val();
      var url = site_url+"/Products/savemyCategory";
      var dataString = "category_id_title="+category_id_title;
      $.post(url,dataString,function(returndata)
      {
        if(returndata==0) 
        {
          $("#error_subcategory_title").fadeIn().html("Subcategory already exist");
           setTimeout(function(){$("#error_subcategory_title").fadeOut();},3000);
          $("#subcategory_title").focus();
          return false;
        }
        else
        {
          $("#category_id_title").val('');
          $("#subcategory_title").val('');
          $("#myModalSubcategory").modal("hide");
          $('#subcategory_id').html(returndata);
          $("#successEntry").fadeIn().html("<span> Subcategory created successfully</span>");
          setTimeout(function(){$("#successEntry").fadeOut();},3000);
        }
      });
  }
   
</script>