<?php 
	$tag = $this->uri->segment(2);
	$vendor=$this->uri->segment(3); 
	$balance = $this->Crud_model->GetData('vendor_transactions','balance',"vendor_id='".$vendor."'",'','id desc','1','single');
?>

   <div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
                <a href="<?= site_url('Vendors/read/'.$vendor)?>"><button type="button" class="btn <?php if($tag =='read') { echo "btn-info"; } ?> btn-default" style="margin-left:20px">Details</button></a>
		    	<a href="<?= site_url('Vendors/purchase_order/'.$vendor)?>"><button type="button" class="btn <?php if($tag =='purchase_order' || $tag =='purchase_detail') { echo "btn-info"; } ?> btn-default"">Purchase Order</button></a>
		    	<a href="<?= site_url('Vendors/vendorPayment/'.$vendor)?>"><button type="button" class="btn <?php if($tag =='vendorPayment') { echo "btn-info"; } ?> btn-default"">Vendor Payment</button></a>
		    	<a href="<?= site_url('Vendors/Vehicle/'.$vendor)?>"><button type="button" class="btn <?php if($tag =='Vehicle') { echo "btn-info"; } ?> btn-default"">Vehicle Management</button></a>
		    	<div class="pull-right" style="margin-right: 20px; margin-top: 2px; font-weight: bold; font-size: 15px; color: #00ACD6">Balance: <i class="fa fa-inr">&nbsp;</i><?php if(!empty($balance->balance)){ echo number_format($balance->balance,2); } else { echo '0.00'; } ?></div>
        </div>    
    </div>
</div>