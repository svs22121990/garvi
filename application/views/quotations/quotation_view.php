 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<style type="text/css">
    .bordered{
        margin-bottom: 20px;
        margin-top: 20px;
        border:1px solid #eee;
        width: 100%;
    }
</style>
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
                    <div class="row">
                         
                            <h3 class="panel-title"><strong>Quotation No: <?php echo $quotation_data[0]->quotation_no; ?></strong> ( <small>Request No: <?php echo $quotation_data[0]->request_no; ?></small> )</h3>
                            
                      
                        <div class="pull-right">
                            <ul class="panel-controls">
                                <li>
                                    <a href="<?php echo site_url('Quotations/index') ?>">
                                        <span class="fa fa-arrow-left"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                   </div>
            </div>
        
        <?php foreach($quotation_data as $data){ 
             $cond="qt.vendor_id='".$data->vendor_id."' and qt.quotation_id='".$quotation_data[0]->id."'";
             $vendor_data=$this->Quotations_model->GetVendorQuotData($cond);
             
             $vendorQuoteNo=$vendor_data[0]->vendor_quotation_no;
             $vendor_quote_copy=$vendor_data[0]->vendor_quote_copy;

        ?>
        <div class="col-md-12 panel-body">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-body profile" style="background-color: #fff;">
                    <center><label><h4><b>User Name</b></h4></label></center>
                    <div class="profile-image">
                        <h4><?php echo $data->vendor_name ?></h4>
                    </div>                  
                </div> 
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <?php if(!empty($vendorQuoteNo)){?>
                    <div class="col-md-6">Vendor Quote No : <?=$vendorQuoteNo; ?></div>
                <?php }?>
                 <?php if(!empty($vendor_quote_copy)){?>
                    <div class="col-md-6 text-right">Quotation Copy : <a href="<?=base_url('uploads/quotationcopy/'.$vendor_quote_copy);?>" download><i class="fa fa-download"></i></a></div>
                <?php }?>
               <div class="panel-body"> 
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <th>Sr</th>
                            <th>Assets</th>
                            <th>Quantity</th>
                            <!-- <th>Available Qty</th> -->
                            <?php if(!empty($data->amount)){ ?>
                                <th>Per Unit MRP</th>
                                <th>Per Unit Price</th>
                                <th>Amount</th>
                            <?php } ?>
                        </thead>
                        <tbody>
                            <?php
                           
                             $sr=1;
                             foreach($vendor_data as $data)
                             {
                             ?>
                            <tr>
                                <td><?php echo $sr; ?></td>
                                <td><?php echo  ucfirst($data->asset_name); ?></td>
                                <td><?php echo $data->quantity ?> </td>
                               <!--  <td><?php echo $data->origin_qty-$data->quantity ?> </td> -->
                                <?php if(!empty($data->amount)){ ?>
                                <td><?php echo $data->mrp ?></td>
                                <td><?php echo $data->per_unit_price ?></td>
                                <td><?php echo $data->amount ?></td>
                                <?php } ?>
                            </tr>
                        <?php ++$sr; } ?>
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-body profile" style="background-color: #fff;">
                    <center><label><h4><b>Status</b></h4></label><br>
                    <?php if($data->status=='Pending'){ ?>
                        <!-- <span style="cursor:pointer;" class='label-primary label' data-toggle='modal' data-target="#checkStatus" onclick="change_status(<?php echo $quotation_data[0]->id ?>,<?php echo $data->vendor_id ?>)"><?php echo $data->status ?></span> -->
                        <span style="cursor:pointer;" class='label-primary label' data-toggle='modal'><?php echo $data->status ?></span>
                    <?php } else if($data->status=='Approved'){ ?>
                        <span class='label-success label'><?php echo $data->status ?></span>
                    <?php } else { ?>
                        <span class='label-warning label'><?php echo $data->status ?></span>
                    <?php } ?>
                    
                    </center>
                </div> 
            </div>
        </div>
    </div> 
    <?php } ?>
</div>
 </div>
</div>
</div>
<!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('common/footer');?>
<script>
   function change_status(quotation_id,vendor_id)
   {
        $.ajax({
            type:"post",
            url:"<?= site_url('Quotations/getQuotaitonDetail') ?>",
            cache:false,
            data:{quotation_id:quotation_id,vendor_id:vendor_id},
            success:function(returndata)
            {
                $("#quotation_details").html(returndata);
                $("#quotation_id").val(quotation_id);
                $("#vendor_id").val(vendor_id);
            }
        })
   }
</script>

<div class="modal fade" id="myModal" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">   
                <form method="post" id="quotation_edit" action="<?php //echo site_url('Quotations/add_amount') ?>">
                 <input type="hidden" name="quotation_id" id="quotation_id" value="">
                   <input type="hidden" name="vendor_id" id="vendor_id" value="">
                <div class="modal-body" id="quotation_details">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" onclick="return check_validation()">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
                </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">       
                <div class="modal-body" style="height: 100px;padding-top: 10%">
                    <center>
                        <input type="hidden" name="id" id="statusId" style="display: none;">
                        <span style="font-size: 16px">Are you sure to change the status?</span>
                    </center>
                </div>
                <div class="modal-footer" >
                    <button type="button" onclick="show_assets()" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function check_validation()
{   var site_url = $("#site_url").val();
    var count = $('#clonetable_feedback tr').length;
    for(var i=1; i <= count;  i++)
    {
        var amount1=$("#amount"+i).val();
        var mrp1=$("#mrp"+i).val();
        var per_unit_price1=$("#per_unit_price"+i).val();

         if($.trim(mrp1)=="")
        {
          $("#error2").html("Required").fadeIn();
          setTimeout(function(){$("#error2").fadeOut()},3000);
          $("#mrp"+i).focus();
          return false;
        }
         if($.trim(per_unit_price1)=="")
        {
          $("#error3").html("Required").fadeIn();
          setTimeout(function(){$("#error3").fadeOut()},3000);
          $("#per_unit_price"+i).focus();
          return false;
        }
        if($.trim(amount1)=="")
        {
          $("#error1").html("Required").fadeIn();
          setTimeout(function(){$("#error1").fadeOut()},3000);
          $("#amount"+i).focus();
          return false;
        } 
        if($.trim(per_unit_price1)=="0")
        {
        $("#per_unit_price"+i).val('');  
          $("#error1").html("Price should be greater than zero").fadeIn();
          setTimeout(function(){$("#error3").fadeOut()},3000);
          $("#per_unit_price"+i).focus();
          return false;
        } 
       
    }   

 var ask=confirm('Are you sure to change the status ?');

   if(ask == true)
    { 
        var datastring = $("#quotation_edit").serialize();
        $.ajax({
            type  :  "post",
            url   :  site_url+"/Quotations/add_amount",
            data  :  datastring,
            beforeSend : function()
            {
                $(".preloader").show();
            },
            success : function(response)
            {
                
                 window.location.href=site_url+'/Quotations';
              
            }
        });
    }    

}
</script>

<script>
   function show_assets()
   {
        $('#checkStatus').modal('hide');
        $('#myModal').modal('show');
   }

   function amount(id)
   {
  
        var qunatity = $('.qunatity_'+id).val();
        var per_unit_price = $('.per_unit_price_'+id).val();

        if(per_unit_price == '')
        {
            $(".amount_"+id).val('');
            $("#error3").html("Required").fadeIn();
            setTimeout(function(){$("#error3").fadeOut()},3000);
            $(".per_unit_price_"+id).focus();
            return false;
        } 
        if(per_unit_price == '0')
        {
            $(".amount_"+id).val('');
            $(".per_unit_price_"+id).val('');
            $("#error3").html("Price should be greater than zero").fadeIn();
            setTimeout(function(){$("#error3").fadeOut()},3000);
            $(".per_unit_price_"+id).focus();
            return false;
        }    


        var finalAmt = parseFloat(qunatity) * parseFloat(per_unit_price);
        $(".amount_"+id).val(finalAmt);
    }
</script>
