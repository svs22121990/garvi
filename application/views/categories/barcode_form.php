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
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<div class="page-content-wrap"> 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">                                
                    <h3 class="panel-title"><strong><?= $heading ?> - <span  class="text-success"><?=$category_name;?></span></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                  
                        <li><a href="<?=site_url('Categories')?>" class=""><span class="fa fa-arrow-left"></span></a></li>
                       
                    </ul>                                
                </div>
           
                <div class="panel-body">
                          <div class="col-md-12">
                              <div class="col-md-5 col-xs-8">
                               <label> Enter Quantity : <span class="errqtyy">&nbsp;</span></label>
                               <input type="text" id="qty" name="qty" class="form-control" placeholder="Enter Qantity" maxlength="3" onkeypress="return only_number(event)" oninput="return checkRow();"> 
                              </div>
                              <div class="col-md-7 col-xs-4">
                                  <label class="col-md-12 col-xs-11"> &nbsp;</label>
                                <button type="button" onclick="return getBarcodeData()" class="btn btn-sm btn-primary"> Ok</button>
                              </div>
                              <div>&nbsp;</div>
                              <div class="col-md-12" id="bacodeData" style="max-height: 400px;overflow-y: scroll;">&nbsp;</div>
                          </div>
                </div>
                <input type="hidden" name="category_name" id="category_name" value="<?=$category_name; ?>">
                <div class="panel-footer">                                                                       
                   <button class="btn btn-success" id="subbtn" type="submit" style="display: none" onclick="return validateinfo()">Submit</button>
                </div>
            
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('common/footer');?>

<script type="text/javascript">
  function checkRow(){
    var qty=$('#qty').val().trim();
    var len= $('#barcodeTbody tr').length;
    if(len!=qty){
      $('#subbtn').hide();
    }else{
      $('#subbtn').show();
    }
  }

  function getBarcodeData() {
   var qty=$('#qty').val().trim();
    if(qty==0 || qty==''){
                  $("#qty").val("");
                  $("#qty").focus();
                  $(".errqtyy").fadeIn().html("Enter valid quantity").css('color','red');
                  setTimeout(function(){$(".errqtyy").html('&nbsp;');},4000);
                 return false;
                }
               var datastring = "qty=" + qty;
               $('.loader').show();
                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Categories/getstickerData/'.$id); ?>",
                    data: datastring,
                    success: function(returndata) {
                        //alert(returndata);return false;
                        $('#bacodeData').html(returndata);
                        $('.loader').hide();
                        $('#subbtn').show();
                    }
                });
  }

   function validateinfo(){
          var qty= $("#qty").val();
          var category_name= $("#category_name").val();
               if(qty==0 || qty==''){
                 $("#qty").val("");
                  $("#qty").focus();
                  $(".errqtyy").fadeIn().html("Enter valid quantity").css('color','red');
                  setTimeout(function(){$(".errqtyy").html('&nbsp;');},4000);
                 return false;
                }
          var datastring = "qty="+qty+"&category_name="+category_name;
           $('.loader').show();
                $.ajax({
                    type: "post",
                    url: "<?=site_url('Categories/saveBarcode/'.$id)?>",
                    data: datastring,
                    success: function(returndata) {
                       $('.loader').hide();
                         window.location.href="<?=site_url('Categories/generateBarcode/'.$id);?>";
                    }
                });
 }
</script>
           


