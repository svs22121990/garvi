<?php  $this->load->view('common/header');
 $this->load->view('common/left_panel'); ?>
 <style>
 * {
  box-sizing: border-box;
}

#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myTable {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
  font-size: 12px;
}

#myTable th, #myTable td {
  text-align: left;
  padding: 5px;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}
.loader{
	display:none;
}
 </style>
 <?php $created_by = $_SESSION[SESSION_NAME]['id']; ?> <!-- START BREADCRUMB -->
 <?= $breadcrumbs ?> <!-- END BREADCRUMB --> <!-- PAGE TITLE --> 
 <div class="page-title">                         <!-- <h3 class="panel-title"><?= $heading
 ?></h3> --> </div>  <!-- PAGE CONTENT WRAPPER -->            
 <div class="page-content-wrap">                
  <div class="row">
    <div class="col-md-12">                            
      <form class="form-horizontal" method="post" id="myForm" onsubmit="return validateinfo()" action="<?php echo $action;?>"  enctype="multipart/form-data">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><strong><?= $heading ?>
                <?php if($msg = $this->session->flashdata('message')){ echo $msg; } ?>
              </h3>
              <ul class="panel-controls">
               <li><a href="<?= site_url('Warehouse_Dispatch/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
             </ul>
           </div>
           <div class="panel-body">
            <div class="row">
              <div class="col-md-12" style="padding: 0;"> 
				<?php if(isset($dispatch)): ?>
					<input type="hidden" value="<?= $dispatch->id; ?>" name="dispatch_id">
				<?php endif; ?>
				<div class="save_finish_body">
				</div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> DN Number <span style="color: red">*</span> <span  id="dn_no_error" style="color: red"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="dn_no" id="dn_no" class="form-control" placeholder="DN Number" readonly="readonly" value="<?php if(isset($dn_number)){ echo $dn_number; } ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Date <span style="color: red">*</span> <span  id="date_error" style="color: red"></span></label>
                    <div class="col-md-11">
                      <input type="text" name="date" id="date" class="form-control datepicker" placeholder="Date" autocomplete="off" value="<?php if(isset($dispatch)){ echo $dispatch->dispatch_date; } ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label class="col-md-11"> Sent To <span style="color: red">*</span> <span  id="sent_to_error" style="color: red"></span></label>
                    <div class="col-md-11">
                      <select name="sent_to" id="sent_to" class="form-control">
                      	<option value="">Select User</option>
                      	<?php 
                      	if(!empty($users)) {
                      		foreach ($users as $user) {
                      	?>
                      	<option <?php if(isset($dispatch)){ if($dispatch->sent_to == $user->id) { echo "selected"; } } ?> value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
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
        <table class="table table-striped table-bordered" id="thetable">
          <thead>
            <tr>
              <th> Product Name <span style="color: red">*</span><span id="error_asset_name"></span></th>
              <th> Quantity <span style="color: red">*</span><span id="error_quantity"></span></th>
              <th> Attributes </th>
              <th> Price <span style="color: red">*</span><span id="error_price"></span></th>
              <th> GST % <span style="color: red">*</span><span id="error_gst_percent"></span></th>
              <th> Total GST <span style="color: red">*</span><span id="error_gst_total"></span></th>
              <th class="text-center"> <a href="javascript:void(0)" class="btn btn-sm btn-info"  onclick="addrow()" ><i class="fa fa-plus"></i></a></th>
            </tr>
          </thead>
		  
          <tbody id="professorTableBody">  
            <tr class="trRow">
              <td>
                <select class="form-control asset_name" name="asset_name[]">
                  <option value="0">Select Product</option>
                </select>
              </td>
              <td>
                <input type="text" class="form-control quantity" name="quantity[]" placeholder="Enter Quantity" autocomplete="off">
                <span style="color: red" class="quantity_error"></span>
              </td>
              <td>
                <div class="attribute_div">Attributes</div>
              </td>
              <td>
                <input type="text" class="form-control product_mrp" name="product_mrp[]" readonly="readonly" placeholder="Enter Product Price" autocomplete="off" onkeypress="return only_number(event)">
                <span style="color: red" class="price_error"></span>
              </td>
              <td>
                <input type="text" class="form-control gst_percent" name="gst_percent[]" readonly="readonly" placeholder="Enter GST %" autocomplete="off">
                <span style="color: red" class="gst_error"></span>
              </td>
              <td>
                <input type="text" class="form-control gst_total" name="gst_total[]" readonly="readonly" placeholder="Enter GST" autocomplete="off">
                <span style="color: red" class="gst_total_error"></span>
              </td>
              <td class="text-center">
              <a href="javascript:void(0)" onclick="remove_tr($(this).closest('tr').index())" class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></a>
              </td>
            </tr>
          </tbody>
          <tfoot>                   
            <tr>
                <th colspan="3" >&nbsp;<span class="pull-right">Total</span></th>
                <th><input type="text" class="form-control" id="priceTotal" readonly="readonly" value="0"></th>
                <th></th>
                <th><input type="text" class="form-control" id="GSTTotal" readonly="readonly" value="0"></th>
            </tr>
            </tfoot>

        </table>
		
		
      </div>
    </div>  
  </div>
</div>                                
<div class="panel-footer">                                                                       
  <button class="btn btn-success" type="button" id="save_finish"><?= $button;?></button>
</div>
</div>
</form>

</div>
</div>                    

</div>
<!-- END PAGE CONTENT WRAPPER -->

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select Product</h4>
        </div>
        <div class="modal-body">
              <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">

				<table id="myTable" style="max-height:600px;overflow-x:scroll">
				  <tr class="header"> 
            <th>Name</th>
            <th>Total Quantity</th>
				    <th>Available Quantity</th>
				    <th>Price</th>
            <th>Category</th>
            <th>Color</th>
            <th>Size</th>
            <th>Fabric</th>
            <th>Craft</th>
            <th>Purchase Date</th>
            <th>Age</th>
            <th>Add</th>
				  </tr>
				  <?php if(!empty($products)) {
		               foreach ($products as $product) {
                    $startDate = $product->purchase_date; 
                    $endDate = date('Y-m-d');

                    $datetime1 = date_create($startDate);
                    $datetime2 = date_create($endDate);
                    $interval = date_diff($datetime1, $datetime2);
                    $arrTime = array();
                    if($interval->y!=0){
                      $arrTime[] =  $interval->y.' Year ';
                    }
                    if($interval->m!=0){
                      $arrTime[] =  $interval->m .' Months ';
                    }
                    $arrTime[] =  $interval->d.' Days';
		              ?>
						  <tr>
						    <td><?= $product->asset_name; ?></td>
                <td><?= $product->quantity; ?></td>
                <td><?= $product->available_qty; ?></td>
                <td><?= $product->product_mrp; ?></td>
                <td><?= $product->title; ?></td>
                <td><?= $product->color; ?></td>
                <td><?= $product->size; ?></td>
                <td><?= $product->fabric; ?></td>
                <td><?= $product->craft; ?></td>
                <td><?= date("d-m-Y", strtotime($product->purchase_date)); ?></td> 
                <td><?= implode(" ",$arrTime); ?></td>  
						    <td><button id="add_product" class="btn btn-success add_product" data-row="" data-name="<?= $product->asset_name; ?>" data-id="<?= $product->id; ?>"><i class="fa fa-plus"></i></button></td>
						  </tr>
					<?php } } ?>
				</table>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<?php $this->load->view('common/footer');?>

<script>
$(document).on('click','.add_product',function(){
		var id = $(this).attr('data-id');
		var name = $(this).attr('data-name');
    var index = $(this).attr('data-row');
		//$('#asset_name1').html('<option value="'+id+'">'+name+'</option>');
    $('table .asset_name').slice(index, index + 1).html('<option value="'+id+'">'+name+'</option>');
		$('#myModal').modal('hide');
    getGST(id,index);
});
jQuery(document).on('click','#edit-action-button',function(){
	$('#save_finish_body').html('');
    $( "#myForm" ).submit();

});
jQuery(document).on('click','#save_next',function(){
	$('#save_finish_body').html('');
    $( "#myForm" ).submit();
});
jQuery(document).on('click','#save_finish',function(){
	//$('#save_finish_body').html('<input type="hidden" name="save_finish" value="save_finish">');
    //
	$("#myForm"). removeAttr("action");
	$("#myForm").attr("action","<?= base_url(); ?>index.php/warehouse_dispatch/create_action/finish");
	$( "#myForm" ).submit();
	
});
//jQuery(document).on('click','#asset_name1',function(){
//	$('#myModal').modal({backdrop: 'static', keyboard: false});
//});
$("table").on('click', 'tr select', function(e){
   $('.add_product').attr('data-row', $(this).closest('td').parent()[0].sectionRowIndex);
   $('#myModal').modal({backdrop: 'static', keyboard: false});
});
//function modelOpen(e)
//{
//  var colValue= this.dataItem($(e.currentTarget).closest("tr"));
//  alert(colValue);/
//	$('#myModal').modal({backdrop: 'static', keyboard: false});
//}
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}


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
  
  var inp2 = new_row.cells[0].getElementsByTagName('select')[0];
  inp2.value = '';
  //inp2.id = 'asset_name'+(len+1);

  var inp3 = new_row.cells[1].getElementsByTagName('input')[0];
  inp3.value = '';
  //inp3.id = 'quantity'+(len+1);

  //var inp4 = new_row.cells[2].getElementsByClassName('attribute_div')[0];
  //inp4.html = 'Attributes';
  //inp4.id = 'product_mrp'+(len+1);

  var inp5 = new_row.cells[3].getElementsByTagName('input')[0];
  inp5.value = '';
  //inp5.id = 'gst_percent'+(len+1);

  var inp7 = new_row.cells[4].getElementsByTagName('input')[0];
  inp7.value = '';
  //inp7.id = 'lf_no'+(len+1);

  //$('.asset_name').val(len+1);

  y.appendChild(new_row);

  $('table .attribute_div').slice(len, len + 1).empty();
  $('table .attribute_div').slice(len, len + 1).html('Attributes');
}


    $(document).on('keyup','.quantity',function(){
        price();
    });
    function price()
    {
      var GSTTotal = 0;
      var priceTotal = 0;
      // for each row:
      $("tr.trRow").each(function () {
          // get the values from this row:
          var $qty = $('.quantity', this).val();
          var $sp = $('.product_mrp', this).val();
          var $gst = $('.gst_percent', this).val();
          var $total = ($qty * 1) * ($sp * 1) * (($gst * 1) / 100);
          // set total for the row
          $('.gst_total', this).val($total);

          GSTTotal += $total;
          priceTotal += ($sp * 1);
      });
      $("#GSTTotal").val(GSTTotal);
      $("#priceTotal").val(priceTotal);
      //$("#finalTotal").val(mult+totalGST+markup);
    }


function getGST(val,len) {
	var site_url = $('#site_url').val();
	var dataString = "product_id="+val;
  //alert(dataString);
	var url = site_url+"/Warehouse_Dispatch/getGST";
	$.post(url, dataString, function(returndata){
		//alert(returndata);
		var obj = jQuery.parseJSON(returndata);

      $('table .gst_percent').slice(len, len + 1).val(obj.gst_percent);
      $('table .product_mrp').slice(len, len + 1).val(obj.price);

      $('table .attribute_div').slice(len, len + 1).empty();
      $('table .attribute_div').slice(len, len + 1).html('<b>Category : </b>'+obj.title+'</br><b>Type : </b>'+obj.type+'</br><b>Color : </b>'+obj.color+'</br><b>Size : </b>'+obj.size+'</br><b>Fabric : </b>'+obj.fabric+'</br><b>Craft : </b>'+obj.craft);
      //$('#gst_percent1').val(obj.gst_percent);
			//$('#product_mrp1').val(obj.price);
			//$('#hsn'+(len+1)).val(obj.hsn);
		
	});
  price();
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
            $("#asset_name"+sr).val("");
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
  var dn_no = $('#dn_no').val(); 
  var date = $('#date').val(); 
  var sent_to = $('#sent_to').val(); 
  var sectionA = $("#thetable td").closest("tr").length;
  //var sectionA = $('.asset_name').val();
  
  if(dn_no=='') {
    $("#dn_no_error").html("Please enter DN Number").fadeIn();
    setTimeout(function(){$("#dn_no_error").fadeOut()},5000);
    return false;
  } else if(date=='') {
    $("#date_error").html("Please select Date").fadeIn();
    setTimeout(function(){$("#date_error").fadeOut()},5000);
    return false;
  }else if(sent_to=='') {
    $("#sent_to_error").html("Please select sent to").fadeIn();
    setTimeout(function(){$("#sent_to_error").fadeOut()},5000);
    return false;
  } else {
    for(var i = 0; i < sectionA; i++) {
      
      var product_name = $('.asset_name').eq(i).val();
      var quantity = $('.quantity').eq(i).val();
      var product_mrp = $('.product_mrp').eq(i).val();
      var gst_percent = $('.gst_percent').eq(i).val();
      var lf_no = $('.lf_no').eq(i).val();
      
      if(quantity=='') {
        $('.quantity_error').eq(i).html("Please enter Quantity").fadeIn();
        setTimeout(function(){$(".quantity_error").eq(i).fadeOut()},5000);
        return false;
      }

      if(product_mrp=='') {
        $('.price_error').eq(i).html("Please enter Price").fadeIn();
        setTimeout(function(){$(".price_error").eq(i).fadeOut()},5000);
        return false;
      }

      if(gst_percent=='') {
        $('.gst_error').eq(i).html("Please enter GST").fadeIn();
        setTimeout(function(){$(".gst_error").eq(i).fadeOut()},5000);
        return false;
      }

      if(lf_no=='') {
        $('.lf_no_error').eq(i).html("Please enter LF No.").fadeIn();
        setTimeout(function(){$(".lf_no_error").eq(i).fadeOut()},5000);
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

