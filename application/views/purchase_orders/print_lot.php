<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
<head>
	<title>Print</title>
</head>
<body>
	<table cellpadding="10px" cellspacing="0px" border="1" align="center" width="90%">
		<thead>
			<tr>
				<th>Vendor</th>
				<!-- <th>Asset Type</th> -->
			
				<?php if($lot[0]->quotation_id!='0') { ?>
				  <th>Quotation No</th>
				<?php } ?>
				<?php if($lot[0]->branch_id!='0') { ?>
				  <th>Branch</th>
				<?php } ?> 
				<th>PO Number</th>
				<th>PO Date</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center"><?= $lot[0]->shop_name; ?></td>
				<td align="center"><?= $lot[0]->quotation_no; ?></td>
				 <?php if($lot[0]->branch_id!='0') { ?>
			      <td align="center"><?= $lot[0]->branch; ?></td>
			    <?php } ?>
				<td align="center"><?= $lot[0]->order_number; ?></td>
				<td align="center"><?= date('d-m-Y',strtotime($lot[0]->purchase_date)); ?></td>
			</tr>
		</tbody>
	</table>

	<table cellpadding="10px" cellspacing="0px" border="1" align="center" width="90%">
		<tr>
			<td colspan="4">Lot No.: <?= $lot[0]->lot_no; ?></td>
		</tr>
		<tr>
			<td>Bill No.: <?= $lot[0]->bill_no; ?></td>
			<td>Driver Name: <?= $lot[0]->driver_name; ?></td>
			<td>Vehicle Number: <?= $lot[0]->vehicle_number; ?></td>
			<td>Received Date: <?= date('d-m-Y',strtotime($lot[0]->created)); ?></td>
		</tr>
		<tr>
			<td colspan="4" style="border-bottom: none;border-right: none;border-left: none">
				<table cellpadding="10px" cellspacing="0px" border="1" align="center" width="100%">
					<thead>
						<tr>
							<th>Sr No</th>
							<th>Brand</th>
							<th>Asset</th>
							<th>Received Quantity</th>
							<th>Unit</th>
							<th>Rate</th>
							<th>Price</th>
							<th>CGST</th>
							<th>SGST</th>
							<th>CESS</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php $sr=0; foreach ($lot as $row) { ?>				
						<tr>
							<td><?= ++$sr; ?></td>
							<td><?= $row->brand_name; ?></td>
							<td><?= $row->asset_name; ?></td>
							<td><?= $row->received_qty; ?></td>
							<td><?= $row->unit; ?></td>
							<td><?= $row->per_unit_price; ?></td>
							<td><?= $row->price; ?></td>
							<td><?= $row->cgst.'%'; ?></td>
							<td><?= $row->sgst.'%'; ?></td>
							<td><?= $row->fess; ?></td>
							<td>Rs. <?= number_format($row->rate,2); ?></td>
						</tr>
						<?php } ?>
						
						<tr>
							<td colspan="7"></td>
							<td colspan="3"><b>Total Amount</b></td>
							<td>Rs. <?= number_format($lot[0]->total_amount,2); ?></td>
						</tr>
						<tr>
							<td colspan="7"></td>
							<td colspan="3"><b>Labour Charge</b></td>
							<td>Rs. <?= number_format($lot[0]->labour_charge,2); ?></td>
						</tr>
						<tr>
							<td colspan="7"></td>
							<td colspan="3"><b>Extra Vendor Charge</b></td>
							<td>Rs. <?= number_format($lot[0]->extra_vendor_charge,2); ?></td>
						</tr>
						<tr>
							<td colspan="7"></td>
							<td colspan="3"><b>Final Amount</b></td>
							<?php $finalAmt = $lot[0]->total_amount + $lot[0]->labour_charge + $lot[0]->extra_vendor_charge; ?>
							<td>Rs. <?= number_format($finalAmt,2); ?></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>		
		<tr>
			<td colspan="4" align="center" style="border:none;">
				<button onclick="myFunction()" id="printpagebutton">Print</button>
			</td>
		</tr>
	</table>	
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		window.print();		
	});
</script>
<script type="text/javascript">
    function myFunction() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        //Set the print button to 'visible' again 
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
    }
</script>