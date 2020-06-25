<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
	<head>
		<title>Barcode</title>
		<style type="text/css">
		body 
		{
			margin:20px;
			font-family:verdana;
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
		@media print
		{    
			.no-print, .no-print, #printpagebutton 
			{
			display: none !important;
			}
		}
		
		</style>
	</head>
	<body>
		  <div id="container">
		  		<div id="content-wrapper">
		  		    <div>
		  		    	<div style="padding: 2px;"><h3>Asset Name : <?php echo str_replace('%20', ' ', $astName); ?> (Qty = <?= count($asset_details) ?>) </h3></div>
		  		    	<table cellpadding="0px" cellspacing="0px" width="1000px" align="center" border="1px" id="second-table">
		  		    		
		  		    		<tr>
		  		    		<?php $sr=0; foreach($asset_details as $row) { if ($sr % 5 == 0) { echo '</tr>'; } ?>
							  	<td style="padding: 10px; text-align: center"><img src="<?php echo base_url(); ?>assets/purchaseOrder_barcode/<?php echo $row->barcode_image; ?>"></td>
							<?php $sr++; } ?>
		  		    		</tr>

		  		    	</table>

		  		    </div>
		  		    <div align="center">
					<button onclick="myFunction()" id="printpagebutton">Print</button>
				</div>
		  		</div>
		  </div>
	</body>

</html>
<script type="text/javascript">
    function myFunction() {
        var printButton = document.getElementById("printpagebutton");
        printButton.style.visibility = 'hidden';
        window.print()
        printButton.style.visibility = 'visible';
    }
</script>