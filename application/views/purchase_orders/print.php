<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
	<head>
		<title>Purchase Order</title>
		<style type="text/css">
		body 
		{
			margin:20px;
			font-family:verdana;
		}
		@media print
		{    
			.no-print, .no-print, #printpagebutton 
			{
			display: none !important;
			}
		}
		#container
		{
		  	width:1000px;
		  	height:auto;
		 	border:1px solid #000;
		  	margin:auto;
		}
		#header
		{
			height:120px;
		}
		#left-header
		{
			float:left;
			margin-top:10px;
			margin-left:20px;
		}
		#right-header
		{
			float:right;
			color:#3C5573;
			font-weight:bold;
			font-size:21px;
			margin:12px 14px 0px 0px;
		}
		#comapany-name
		{	
			height:140px;
			padding-left:18px;
		}
		#company-left
		{
			float:left;
		}
		#company-left h1 
		{
			font-size:15px;
			font-family:verdana;
			font-weight:bold;
		}
		#company-right
		{
			float:right;
			text-align:right;
			padding:10px 14px 0px 0px;
		}
		#vendor
		{
			background-color:#FFF;
			height:200px;
		}
		#left-align
		{
			float:left;
			margin:25px;
		}
		#vendor-left
		{
			float:left;
			margin-top:45px;
		}
		.vendor-margin p 
		{
		 	margin:0px;
		}
		#vendor-left h5
		{
			text-align:left;
		}
		#right-align 
		{
			float:left;
			margin-right:13px;
		}
		#vendor-content
		{
			float:right;
			margin-top:50px;
			margin-right:130px;
		}
		#vendor-right
		{
			float:right;
		}
		#vendor-right h5
		{
		  	margin:50px;
		}
		#first-table

		{
			border:2px solid #68768D;
		}
		.table-text
		{
			background-color:#E2E9F5;
			text-align:center;
			border:2px solid #68768D;
			font-size:20px;	
		}
		.table-content
		{
			border:2px solid #68768D;
			background-color:#FFFF;
			text-align: center;
			line-height: 2em;
		}
		#table-space
		{
			padding:25px;
		}
		#spacing
		{
			padding:25px;
		}
		#equal-space
		{
			padding:15px;
		}
		#second-space { padding:15px; }
		#third-row { padding:15px; }
		#second-table { border:0px solid #68768D; width: 98%; }
		#last-row { background-color:#FFFF; border:3px solid #68768D; }
		#instruction-wrapper { height:200px; }
		#left-instruction
		{
			float:left;
			width:500px;
			font-size:15px;
			font-weight:bold;
			margin-top:28px;
		}
		#left-instruction ol li { padding:7px; }
		#right-instruction
		{
			width:400px;
			float:right;
			margin-top:100px;
			border-top:2px solid #000;
		}
		#authorized { text-align: center; }
		</style>
	</head>
	<body>
	<?php
		$vendor = $this->Crud_model->GetData('vendors','',"id='".$po->vendor_id."'",'','','','single');
	//print_r($pod);
		$cond = "type='Admin'";

        $compDetail = $this->Crud_model->GetData('employees','',$cond,'','','','single');        
        
       /* $condition = "id='6'";        
        $GSTno = $this->Crud_model->GetData('global_settings','',$condition,'','','','single');*/
		
		$city = $this->Crud_model->GetData('mst_cities','',"id='".$vendor->city_id."'",'','','','single');
		$state = $this->Crud_model->GetData('mst_states','',"id='".$vendor->state_id."'",'','','','single');
	?>
		  <div id="container">
		  					<!--HEADER SECTION-->
		  		<div id="header">
		  			      <!--HEADER LOGO SECTION-->
		  				<div id="left-header">
		  					<?php /*if(!empty($compDetail->logo)){ ?>
								<img src="<?php echo base_url('../images/employees/'.$compDetail->logo); ?>" alt="img" style="height:100px; width:100px;"/>
							<?php } else { ?>
								<img src="<?= base_url(); ?>uploads/logo.png" alt="img" style="height:100px; width:100px;"/>
							<?php }*/ ?>
		  				</div>
		  				 <!--HEADER RIGHT SECTION-->
		  				<div id="right-header">
		  					<h2>PURCHASE ORDER</h2>
		  				</div>
		  		</div>
		  		           <!--COMPANY NAME SECTION-->
		  		<div id="comapany-name">
		  				<div id="company-left">
		  					<span><font size="6">Asset Tracking</font></span><br/>
		  					<span><?= $compDetail->address; ?></span><br/>
		  					<span>	Mobile No: <?= $compDetail->mobile; ?></span><br/>
		  				<!-- 	<span>	GST: <?= $GSTno->value; ?></span><br/> -->
		  					
		  				</div>
		  				   <!--COMPANY ADDRESS SECTION-->
		  				<div id="company-right">
		  					<p>P.O: <?= $po->order_number; ?></p>
		  					<p>DATE: <?= date('F d,Y',strtotime($po->purchase_date)); ?></p>
		  				</div>
		  		</div>
		  		             <!--VENDOR NAME AND SHIPMENT SECTION-->
		  		<div id="vendor">

		  				<div id="left-align"><h3>VENDOR</h3></div>
		  						<!--VENDOR LEFT SEGMENT SECTION-->

		  				<div id="vendor-left" class="vendor-margin">
		  					 
		  					 <p><?= $vendor->shop_name; ?></p>
		  					 <p><?= $vendor->address; ?></p>
		  					 <?php if(!empty($city->city_name)){$cities = $city->city_name; }else{ $cities = '';}
		  					 	if(!empty($state->state_name)){ $states = $state->state_name ; }
		  					 	else
		  					 	{	$states = '';}
		  					 	if(!empty($vendor->pincode)){ $vendors =  $vendor->pincode; }
		  					 	else{ $vendors = ''; }
		  					 ?>
		  					 <p><?= $cities.' '.$states.' '.$vendors; ?></p>
		  					 <p><?= $vendor->mobile; ?></p>
		  					 <p>GST: <?= $vendor->gst_no; ?></p>

		  				</div>
		  				<!--VENDOR RIGHT SEGMENT SECTION-->
		  				<!-- <div id="vendor-right">
		  					<div id="right-align"><h5>SHIP <br>TO</h5></div>
		  					<div id="vendor-content" class="vendor-margin">
		  					 <p>[Name]</p>
		  					 <p>[Company Name]</p>
		  					 <p>[Street Address]</p>
		  					 <p>[City, ST ZIP Code]</p>
		  					 <p>[Phone]</p>
		  					 <p> Customer ID[ABC12345]</p>	
		  					</div>
		  				</div> -->

		  		</div>
		  		           <!--WRAPPER SECTION CONTAINING  FIRST TABLE-->
		  		<div id="content-wrapper">
		  			<div>
		  			<!-- <table cellpadding="0px" cellspacing="0px" width="1000px" align="center" border="0px" id="first-table">

		  				<tr id="row-one">
		  					<td class="table-text">
		  						<h6>SHIPPING METHOD</h6>
		  					</td>
		  					<td class="table-text">
		  						<h6>SHIPPING TERMS</h6>
		  					</td>
		  					<td class="table-text">
		  						<h6>DELIVERY DATE</h6>
		  					</td>
		  				</tr>
		  				<tr>
		  					<td class="table-content" id="table-space">1</td>
		  					<td class="table-content">1</td>
		  					<td class="table-content">1</td>
		  				</tr>
		  				<tr colspan="0" id="last-row">
		  					<td >1</td>		  	
		  				</tr>
		  			</table> -->
		  		    </div>
		  		      <!--WRAPPER SECTION CONTAINING SECOND TABLE-->
		  		    <div>
		  		    	<table cellpadding="0px" cellspacing="0px" width="1000px" align="center" border="0px" id="second-table">
		  		    		<tr>
		  		    			<td class="table-text">
		  		    				<h5>Sr.</h5>
		  		    			</td>
		  		    			<td class="table-text">
		  		    				<h5>Brand</h5>
		  		    			</td>
		  		    			<td class="table-text">
		  		    				<h5>Asset</h5>
		  		    			</td>
		  		    			<td class="table-text">
		  		    				<h5>Asset Type</h5>
		  		    			</td>
		  		    			<td class="table-text">
		  		    				<h5>QTY</h5>
		  		    			</td>
		  		    			<td class="table-text">
		  		    				<h5>UNIT</h5>
		  		    			</td>
		  		    		</tr>
		  		    		<?php $sr=0; foreach($pod as $row){ ?>
		  		    		<tr>
		  		    			<td class="table-content"><?= ++$sr; ?></td>
		  		    			<td class="table-content"><?= ucfirst($row->brand_name); ?></td>
		  		    			<td class="table-content"><?= ucfirst($row->asset_name); ?></td>
		  		    			<td class="table-content"><?= $row->type; ?></td>
		  		    			<td class="table-content"><?= $row->quantity; ?></td>
		  		    			<td class="table-content"><?= $row->unit; ?></td>
		  		    		</tr>
		  		    		<?php } ?>
		  		    		<!-- <tr>
		  		    			<td colspan="5" align="right"><b>SUBTOTAL</b></td>
		  		    			<td class="table-content" id="equal-space">1</td>
		  		    		</tr>
		  		    		<tr>
		  		    			<td colspan="5" align="right"><b>SALES TAX</b></td>
		  		    			<td class="table-content" id="second-space">1</td>
		  		    		</tr>
		  		    		<tr>
		  		    			<td colspan="5" align="right" ><b>TOTAL</b></td>
		  		    			<td class="table-content" id="third-row">1</td>
		  		    			
		  		    		</tr> -->
		  		    		
		  		    	</table>

		  		    </div>

		  		</div>
		  		           <!--INSTRUCTION SECTION-->
		  		<div id="instruction-wrapper">

		  				<!-- <div id="left-instruction">
		  					<ol>
		  						<li>Please send two copies of your invoice</li>
		  						<li>Enter this order in accordance with prices,terms, delivery method,and specification listed above</li>
		  						<li>Please notify us immediately if you are unable ship us specified</li>
		  						<li>Send all correspondance to:</li>
		  						<p>[Name]</p>
		  					    <p>[Street Address]</p>
		  					    <p>[City, ST ZIP Code]</p>
		  					    <p>	Phone[000.000.0000] Fax[000.000.0000]</p>
		  					</ol>

		  				</div> -->
		  				    <!--AUTHORIZED AND SIGN SECTION-->
		  				<div id="right-instruction">
		  					<div id="authorized" >
		  						<p><b><i>Authorized by </i></b></p> 
		  					</div>
		  				</div>
		  		</div> 
		  		<div align="center">
					<button onclick="myFunction()" id="printpagebutton">Print</button>
				</div>

		  </div>
	</body>

</html>
<!-- <script src="<?= base_url(); ?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script> -->
<script type="text/javascript">
	/*$(document).ready(function(){
		window.print();		
	});*/
</script>
<script type="text/javascript">
    function myFunction() {
        var printButton = document.getElementById("printpagebutton");
        printButton.style.visibility = 'hidden';
        window.print()
        printButton.style.visibility = 'visible';
    }
</script>