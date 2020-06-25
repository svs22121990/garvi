<?php $this->load->view('common/header'); ?>
<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>
<!-- START BREADCRUMB -->
<?php echo $breadcrumbs; ?>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" method="post" action="<?php echo $action; ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                        <li><a href="<?= site_url('Assets_maintenance/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">                           
                        <div class="col-md-12">
                            <!-- <div class="col-md-6">
                              <div class="form-group">                                        
                                  <label class="col-md-12">Branch </label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="branch_id" id="branch_id"  data-live-search="true" onchange="getAssets()">
                                            <option value="0">--Select Branch--</option>
                                            <?php 
                                               foreach($branches as $row) { ?>
                                                 <option value="<?php echo $row->id ?>"<?php if($branch_id == $row->id){ echo "selected"; }?>>    <?php echo $row->branch_title; ?>
                                                 </option>
                                            <?php } ?>
                                          </select>                                            
                                      </div>
                                  </div>
                              </div> -->

                              <div class="col-md-4">
                                <div class="form-group">                                        
                                  <label class="col-md-12">Send Date <span style="color:red;">*</span> <span id="errdate" style="color:red"></span></label>
                                  <div class="col-md-10">
                                    <input type="text" name="date" id="date" class="form-control date" value="<?php echo $date; ?>" readonly placeholder="Select Date">
                                  </div>
                                </div>
                              </div>
                              
                              <div class="col-md-4">
                                <div class="form-group">                                        
                                  <label class="col-md-12">Asset Type<span style="color:red;">*</span> <span id="errassets_type_id" style="color:red"></span></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="assets_type_id" id="assets_type_id" data-live-search="true" onchange="getAssets()">
                                            <option value="0">--Select Asset Type--</option>
                                            <?php 
                                               foreach($asset_types as $row) { ?>
                                                 <option value="<?php echo $row->id ?>" <?php if($assets_type_id == $row->id){ echo "selected"; }?>><?php echo $row->type; ?></option>
                                            <?php } ?>
                                          </select>      
                                          
                                      </div>
                                  </div>
                              </div>
                              
                            <div class="col-md-4">
                              <div class="form-group">                                        
                                  <label class="col-md-12">Assets <span style="color:red;">*</span> <span id="errassets_id" style="color:red"></span></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="assets_id" id="assets_id" data-live-search="true" onchange="return assetData(this.value)">
                                            <option value="0">--Select Assets--</option>
                                            <?php 
                                               foreach($assets as $row) { ?>
                                                 <option value="<?php echo $row->id ?>"<?php if($assets_id == $row->id){ echo "selected"; }?>><?php echo $row->asset_name; ?></option>
                                            <?php } ?> 
                                          </select>  
                                                                                    
                                      </div>
                                  </div>
                              </div>

                              <div class="clearfix">&nbsp;</div>

                              <span id="errassets_chk" style="color:red;">&nbsp;</span>

                              <div class="col-md-12">
                                  <div class="table-responsive" id="assetData">
                                    <!-- append data table -->
                                  </div>
                              </div>
                              
                              <!-- <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Price <span style="color:red;">*</span> <span id="errprice" style="color:red"></span><span class="form_error" style="color:red"> <?php echo form_error('price') ?></span></label>
                                    <div class="col-md-10">  
                                        <input type="text" class="form-control" name="price" id="price" placeholder="Enter Price" value="<?php echo $price; ?>"  onkeypress="only_number(event)" autocomplete="off"/>
                                    </div> 
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Quantity <span style="color:red;">*</span> <span id="showQuantity" style="color:blue"></span></label>
                                    <div class="col-md-10">  
                                        <input type="text" class="form-control numbers" name="quantity" id="quantity" placeholder="Enter Quantity" onkeyup="chkquant(this.value)" maxlength="10" value="<?php echo $quantity; ?>"/ autocomplete="off">
                                        <span id="errQuantity" style="color:red"></span>
                                    </div> 
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Description <span style="color:red;">*</span> <span id="errdescription" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description"><?php echo $description; ?></textarea>
                                    </div> 
                                </div>
                              </div> -->
                              
                        </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" type="submit" id="submit" onclick="return validation();"><?= $button;?></button>
                    <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>
                </div>
            </div>
            <input type="hidden" name="btnType" id="btnType" value="<?php echo $button; ?>">
            </form>
            
        </div>
    </div>                    
    
</div>
<!-- END PAGE CONTENT WRAPPER -->  
<?php $this->load->view('common/footer'); ?>

<script type="text/javascript">

     function getAssets(date)
      {
        //var branch_id = $("#branch_id").val();       
        var asset_type_id = $("#assets_type_id").val();  
      
        //if(branch_id =='0' || asset_type_id !='0')
        if(asset_type_id !='0')
        {
            //var datastring = "id="+asset_type_id+"&branch_id="+branch_id;
            $('.loader').fadeIn();                
            var datastring = "id="+asset_type_id;                
                $.ajax({
                    type: "post",
                    url: "<?php echo site_url('Assets_maintenance/getAssets'); ?>",
                    data: datastring,
                    success: function(returndata) {   
                    //alert(returndata);                    
                        $('#assets_id').html(returndata).selectpicker('refresh');   
                        $('.loader').fadeOut();                     
                    }
                });
        }

        /*else if(branch_id !='0' && asset_type_id !='0')
        {         
            var datastring = "id="+asset_type_id+"&branch_id="+branch_id;            
            var datastring = "id="+asset_type_id;            
             $.ajax({
                type: "post",
                url: "<?php echo site_url('Assets_maintenance/getAssets'); ?>",
                data: datastring,
                success: function(returndata) {
                //alert(returndata);                        
                    $('#assets_id').html(returndata).selectpicker('refresh');                        
                }
            });
        }     */
      }
</script>




<!-- <script type="text/javascript">
    var btnType = $("#btnType").val();

    if(btnType=='Update')
    {
      getAssets();
    }
</script> -->

<script type="text/javascript">
function validation()
{ 
        
/*var branch_id = $("#branch_id").val();*/ //alert(branch_id);return false;
        var date = $(".date").val();
        var assets_type_id = $("#assets_type_id").val(); //alert(branch_id);return false;
        var assets_id = $("#assets_id").val(); //alert(branch_id);return false;
        var selected_client = $('#selected_client').val();
        /*var price = $("#price").val();
         var asset_quant = $("#quantity").val();
        var description = $("#description").val();*/
        
      /*if(branch_id=="0")
      {
        $("#errbranch_id").fadeIn().html("Please Select Branch");
        setTimeout(function(){$("#errbranch_id").html("&nbsp;");},5000)
        $("#branch_id").focus();
        return false;
      } 
  */

      if(date=="")
      {
        $("#errdate").fadeIn().html("Please Select Date");
        setTimeout(function(){$("#errdate").html("&nbsp;");},5000)
        $(".date").focus();
        return false;
      }
       
       if(assets_type_id=="0")
      {
        $("#errassets_type_id").fadeIn().html("Please Select Asset Type");
        setTimeout(function(){$("#errassets_type_id").html("&nbsp;");},5000)
        $("#assets_type_id").focus();
        return false;
      } 


      if(assets_id=="")
      {
        $("#errassets_id").fadeIn().html("Please Select Asset");
        setTimeout(function(){$("#errassets_id").html("&nbsp;");},5000)
        $("#assets_id").focus();
        return false;
      } 

      if(selected_client=="")
      {
        $("#errassets_chk").fadeIn().html("Please Select Atleast One Asset");
        setTimeout(function(){$("#errassets_chk").html("&nbsp;");},5000)
        $("#select_all").focus();
        return false;
      } 

     /* if(price=="")
      {
        $("#errprice").fadeIn().html("Please Enter Price");
        setTimeout(function(){$("#errprice").html("&nbsp;");},5000)
        $("#price").focus();
        return false;
      }

       if(asset_quant=="")
        {
          $("#errQuantity").fadeIn().html("Please Enter Quantity");
          setTimeout(function(){$("#errQuantity").html("&nbsp;");},5000)
          $("#quantity").focus();
          return false;
        }
       
      if(description=="")
      {
        $("#errdescription").fadeIn().html("Please Enter Description");
        setTimeout(function(){$("#errdescription").html("&nbsp;");},5000)
        $("#description").focus();
        return false;
      }*/
             
   }
</script>

<script type="text/javascript">
function only_number(event)
{

  var x = event.which || event.keyCode;
        console.log(x);
        //alert(x);
        if((x >= 48 ) && (x <= 57 ) || x == 8 | x == 9 || x == 13 || x == 46 )
        {
          return;
        }
        else
        {
          event.preventDefault();
        }   
}
</script>

<script type="text/javascript">
  function chkquant(value)
  {
      var assets_id = $("#assets_id").val();
      var branch_id = $("#branch_id").val();

      if(assets_id !='' && value !='')
      {
           var datastring = "value="+value+"&assets_id="+assets_id+"&branch_id="+branch_id;
            $.ajax({
            type: "post",
            url: "<?php echo site_url('Assets_maintenance/chkquant'); ?>",
            data: datastring,
            success: function(returndata) {                       
              if(returndata == 1)
              {
                  $("#errQuantity").fadeIn().html("Quantity should be less than total quantity and not equal to zero");
                  $("#quantity").val("");
                  setTimeout(function(){$("#errQuantity").fadeOut();},4000);
              } 
              else
              {

              }                                                   
            }
        });
      }
  }
</script>

<script type="text/javascript">
  function getQuantity()
  {
      $("#showQuantity").html("");
      var assets_id = $("#assets_id").val();
      var branch_id = $("#branch_id").val();

      if(assets_id !='')
      {
           var datastring = "assets_id="+assets_id+"&branch_id="+branch_id;
            $.ajax({
            type: "post",
            url: "<?php echo site_url('Assets_maintenance/getQuantityShow'); ?>",
            data: datastring,
            success: function(returndata) {     
            //alert(returndata);                  
                var obj = $.parseJSON(returndata);
                if(obj.success == '1') 
                {                                               
                    $("#showQuantity").append(obj.quantityShow);                                                                                            
                }                                                    
            }
        });
      }
  }
</script>
<?php 
$controller = $this->uri->segment(1);
$function = $this->uri->segment(2);
if(!empty($controller) && !empty($function)){
  if($controller == "Assets_maintenance" && $function=="create")
  {
    $show = "show";
    $len = 10;
  }
  }else{
    $len = 10;
    $show = "";
    } ?>
<script type="text/javascript">


  function assetData(assets_id)
  {
    //alert(assets_id);return false;
     var assets_id = $("#assets_id").val(); 
     var id = $("#assets_id").val(); 
     var datastring = "assets_id="+assets_id;
     $('.loader').fadeIn();
     $.ajax({
      type  :  "post",
      url   :  "<?php echo site_url('Assets_maintenance/Assets_maintenanceData') ?>", 
      data  :  datastring,
      success : function(response)
      {       
        //alert(response);return false;
        $("#assetData").html(response);

        /*datatable start for append assets data*/
         table = $('.example_datatable1').DataTable({ 
              "oLanguage": {
              "sProcessing": "<img src='<?= base_url()?>assets/img/loaders/default.gif'>" 
        },
        
           
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "lengthMenu" : [[10,25, 100,200,500,1000,2000], [10,25, 100,200,500,1000,2000 ]],"pageLength" : <?= $len; ?>,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url1,
                "type": "POST",
                 "data": function(d) {
                        d.select_all = $(".select_all").is(":checked");
                        d.SearchData = $(".filter_search_data").val();
                        d.SearchData1 = $(".filter_search_data1").val();
                        d.SearchData2 = $(".filter_search_data2").val();
                        d.FormData = $(".filter_data_form").serializeArray();
                    }
                     
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,actioncolumn1 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],
                <?php if(!empty($show)){ ?>
                    "fnDrawCallback": function() {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(".append_ids").val(json.ids);
                    uni_array(); 
                 
                  },
                  <?php } ?> 

        });
        /*datatable end */
        $('.loader').fadeOut();
      }

     });
  }
</script>
