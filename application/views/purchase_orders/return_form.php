<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<!-- START BREADCRUMB -->
   <?= $breadcrumbs ?>
<!-- END BREADCRUMB -->   
<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<style>
textarea {
    resize: none;
}
</style>


<div class="page-content-wrap"> 
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">  
          <div class="col-md-4 text-light-blue"><h3><?= $heading ?></h3></div>
          <div class="col-md-4"></div>
          <div class="col-md-4"><span style="color:red;float:right;">* Fields required</span></div>
          <div class="clearfix"></div>
        </div>
        <div class="panel-body">
          <form id="" method="POST" action="<?php echo $action; ?>" class="form-horizontal">
            <div class="row">

              <div class="col-md-12" style="padding: 0;">

                <div class="col-md-4">
                  <div class="form-line">
                    <label>Date <span style="color: red">*</span> <span  id="date_error" style="color: red"></span></label>
                    <input type="text" name="date" id="date" class="form-control">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-line">
                    <label>DN Number <span style="color: red">*</span> <span  id="dn_number_error" style="color: red"></span></label>
                    <input type="text" name="dn_number" id="dn_number" class="form-control">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-line">
                    <label>Sent To <span style="color: red">*</span> <span  id="sent_to_error" style="color: red"></span></label>
                    <select class="form-control" name="sent_to" id="sent_to">
                      <option value="0">Select User</option>
                    </select>
                  </div>
                </div>

              </div>

              <div class="col-md-12" id="showProductsUserWise">
              
                <div class="col-md-12" style="padding: 0;">
                  <div class="">
                    <table class="table table-striped table-bordered" style="margin: 10px 0;">
                      <thead>
                        <tr>
                          <th></th>
                          <th> Category </th>

                          <th>Product Type </th>

                          <th> SKU  </th>
                          <th> Product Name </span></th>
                          <th> Quantity <span style="color: red">*</span><span id="error_quantity"></span></th>
                          <th> Price <span style="color: red">*</span><span id="price_error"></span></th>
                          <th> GST % <span style="color: red">*</span><span id="error_gst_percent"></span></th>
                          <th> LF No. <span style="color: red">*</span><span id="error_lf_no"></span></th>
                          <th class="text-center"> <a  href="javascript:void(0)" class="btn  btn-sm btn-info"  onclick="addrow()" ><i class="fa fa-plus"></i></a></th>
                        </tr>
                      </thead>
                      <tbody id="professorTableBody">
                        <tr class="trRow">
                          <td><input type="checkbox" name="check_product[]" id="check_product"></td>
                          <td>
                            
                          </td>
                          <td>
                            
                          </td>
                          <td>
                            
                          </td>
                          <td>
                            
                          </td>
                          <td>
                            <input type="text" class="form-control" name="quantity[]" id="quantity" placeholder="Enter Quantity" autocomplete="off">
                          </td>
                          <td>
                            <input type="text" class="form-control" name="product_mrp[]" id="product_mrp1" placeholder="Enter Product Price" autocomplete="off" onkeypress="return only_number(event)">
                          </td>
                          <td>
                            <input type="text" class="form-control" name="gst_percent[]" id="gst_percent" placeholder="Enter GST %" autocomplete="off">
                          </td>
                          <td>
                            <input type="text" class="form-control" name="lf_no[]" id="lf_no" placeholder="Enter LF No." autocomplete="off">
                          </td>
                          <td class="text-center">
                            <input type="hidden" class="sectionA" value="1">
                            <a href="javascript:void(0)" onclick="remove_tr($(this).closest('tr').index())" class="btn  btn-sm btn-danger"><i class="fa fa-minus"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div> 
              </div>

            </div>
          </form>
        </div>
        <div class="panel-footer">                                                                       
          <button class="btn btn-success" type="submit" onclick="return validateinfo()">Return</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function only_number(event)
{
  var x = event.which || event.keyCode;
  console.log(x);
  if((x >= 48 ) && (x <= 57 ) || x == 8 || x==46 || x == 9 || x == 13 )
  {
    return;
  }else{
    event.preventDefault();
  }    
}

function addrow() {
  var y=document.getElementById('professorTableBody'); 
  var new_row = y.rows[0].cloneNode(true); 
  var len = y.rows.length; 

  var inp2 = new_row.cells[0].getElementsByTagName('input')[0];

  inp2.value = '';
  inp2.id = 'check_product'+(len+1);

  var inp3 = new_row.cells[5].getElementsByTagName('input')[0];
  alert(inp3);
  inp3.value = '';
  inp3.id = 'quantity'+(len+1);

  var inp4 = new_row.cells[6].getElementsByTagName('input')[0];
  inp4.value = '';
  inp4.id = 'price'+(len+1);

  var inp5 = new_row.cells[7].getElementsByTagName('input')[0];
  inp5.value = '';
  inp5.id = 'gst_percent'+(len+1);

  var inp7 = new_row.cells[8].getElementsByTagName('input')[0];
  inp7.value = '';
  inp7.id = 'lf_no'+(len+1);

  $('.sectionA').val(len+1);

  y.appendChild(new_row);
}

function remove_tr(row) {  
  var y=document.getElementById('professorTableBody');
  var len = y.rows.length;
  if(len>1)
  {
    var i= (len-1);
    document.getElementById('professorTableBody').deleteRow(row);
  }
}  

function validateinfo() {    
  var date = $('#date').val(); 
  var dn_number = $('#dn_number').val(); 
  var sent_to = $('#sent_to').val();
  var sectionA = $('.sectionA').val();
  
  if(date=='') {
    $("#date_error").html("Please select Date").fadeIn();
    setTimeout(function(){$("#date_error").fadeOut()},5000);
    return false;
  } else if(dn_number=='') {
    $("#dn_number_error").html("Please enter DN No").fadeIn();
    setTimeout(function(){$("#dn_number_error").fadeOut()},5000);
    return false;
  } else if(sent_to=='') {
    $("#sent_to_error").html("Please select Sent To").fadeIn();
    setTimeout(function(){$("#sent_to_error").fadeOut()},5000);
    return false;
  } else if(sectionA==1) {
    var quantity = $('#quantity').val();
    var gst_percent = $('#gst_percent').val();
    var lf_no = $('#lf_no').val();
    var price = $('#price').val();

    if(quantity=='') {
      $("#quantity_error").html("Please enter quantity").fadeIn();
      setTimeout(function(){$("#quantity_error").fadeOut()},5000);
      return false;
    }

    if(price=='') {
      $("#price_error").html("Please enter price").fadeIn();
      setTimeout(function(){$("#price_error").fadeOut()},5000);
      return false;
    }
 
    if(gst_percent) {
      $("#gst_percent_error").html("Please enter GST %").fadeIn();
      setTimeout(function(){$("#gst_percent_error").fadeOut()},5000);
      return false;
    }

    if(lf_no=='') {
      $("#lf_no_error").html("Please enter LF No.").fadeIn();
      setTimeout(function(){$("#lf_no_error").fadeOut()},5000);
      return false;
    }

  } else if(sectionA > 1) {
    //alert("2");
    for(var i = 1; i <= sectionA; i++) {
      if(i==1) {
        var quantity = $('#quantity').val();
        var price = $('#price').val();
        var gst_percent = $('#gst_percent').val();
        var lf_no = $('#lf_no').val();  
      } else {
        var quantity = $('#quantity'+i).val();
        var price = $('#price'+i).val();
        var gst_percent = $('#gst_percent'+i).val();
        var lf_no = $('#lf_no'+i).val();
      }
      
      if(quantity=='') {
        $("#quantity_error").html("Please enter quantity").fadeIn();
        setTimeout(function(){$("#quantity_error").fadeOut()},5000);
        return false;
      }

      if(price=='') {
        $("#price_error").html("Please enter price").fadeIn();
        setTimeout(function(){$("#price_error").fadeOut()},5000);
        return false;
      }

      if(gst_percent) {
        $("#gst_percent_error").html("Please enter GST %").fadeIn();
        setTimeout(function(){$("#gst_percent_error").fadeOut()},5000);
        return false;
      }

      if(lf_no=='') {
        $("#lf_no_error").html("Please enter LF No.").fadeIn();
        setTimeout(function(){$("#lf_no_error").fadeOut()},5000);
        return false;
      }
    }
  }
}
</script>
<?php $this->load->view('common/footer');?>