<!DOCTYPE html>
<html>
<head>
    <title>Warehouse Products</title>
    <style type="text/css">
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        #customers td, #customers th {
            border: 1px solid black;
            padding: 2px;
        }
        #customers tr:hover {background-color: #ddd;}
        #customers th {
            padding-top: 2px;
            padding-bottom: 2px;
            text-align: left;
            background-color: #ddd;
            color: black;
        }
        .title-center {
            text-align: center;font-size: 14px;font-weight: bold;
        }
    </style>
</head>
<body>

<div class="title-center"><img class="img-responsive logo-image" src="http://app.garvigurjari.in/images/logo.jpg" style=""></div>
<div class="title-center"><?= $_SESSION[SESSION_NAME]['name'] ?></div>
<div class="title-center"><?= $_SESSION[SESSION_NAME]['address'] ?></div>
<!--<div style="text-align: center;">GST Number: <?/*= $_SESSION[SESSION_NAME]['gst_number'] */?></div>-->
<h4 style="text-align: center;">Warehouse Products Summary</h4>
<?php $qty=0; $product_mrp=0; $sr = 1; $data = array(); $total_cost = 0; ; $total_sp = 0; $arrTime=0;  ?>
<?php foreach ($results as $result) {
    $data[] = array(
        'no' => $sr++,
        'asset_name' =>$result->asset_name,
        'purchase_date' =>$result->purchase_date,
        'title' => $result->title,
        'type' =>$result->type,
        'size' =>$result->size,
        'color' =>$result->color,
        'fabric' =>$result->fabric,
        'craft' =>$result->craft,
        'hsn' =>$result->hsn,
        'available_qty' => $result->available_qty,
        'quantity' => $result->quantity,
        'price' => $result->price,
        'cost_total' => $result->cost_total,
        'product_mrp' => number_format($result->product_mrp, 2),
        'sp_total' => number_format(($result->product_mrp * $result->available_qty), 2),
        'gst_percent' => $result->gst_percent,
        'gst' => number_format(($result->available_qty * $result->price) * ($result->gst_percent/100), 2),
        'total' => number_format($result->quantity * $result->product_mrp, 2),
        'product_purchase_date' => $result->product_purchase_date,
        'productType' => $result->product_type,
        'name' => $result->name,
        'barcode_number' => $result->barcode_number,
    );

//    $total_cost += $result->cost_total;
//    $total_sp += $result->sp_total;

}

?>


<table id="customers">
    <thead>
    <tr>
        <th class="text-center">Sr. No.</th>
        <th class="text-center">purchase_date</th>
        <th class="text-center">Name</th>
        <th class="text-center"> Received from</th>
        <th class="text-center">Category</th>
        <th class="text-center">Size </th>
        <th class="text-center">Color</th>
        <th class="text-center">Fabric</th>
        <th class="text-center">Craft </th>
        <th class="text-center">HSN Code </th>
        <th class="text-center">Qty </th>
        <th class="text-center">Avail. Qty</th>
        <th class="text-center">Cost Price</th>
        <th class="text-center">Total Cost Amt </th>
        <th class="text-center">GST % </th>
        <th class="text-center">GST Amt </th>
        <th class="text-center">Selling Price</th>
        <th class="text-center">Total SP</th>
        <th class="text-center">Barcode Number </th>
<!--        <th class="text-center">AGE </th>-->
    </tr>
    </thead>

    <tbody>
    <?php $sr = 1; ?>
    <?php foreach ($results as $result) { ?>
        <tr>
            <td><?php echo $sr++; ?></td>
            <td><?php echo $result->purchase_date; ?></td>
            <td><?php echo $result->asset_name; ?></td>
            <td><?php echo $result->title; ?></td>
            <td><?php echo $result->type; ?></td>
            <td><?php echo $result->size; ?></td>
            <td><?php echo $result->color; ?></td>
            <td><?php echo $result->fabric; ?></td>
            <td><?php echo $result->craft; ?></td>
            <td><?php echo $result->hsn; ?></td>
            <td><?php echo $result->quantity; ?></td>
            <td><?php echo $result->available_qty; ?></td>
            <td><?php echo $result->cost_total; ?></td>
            <td><?php echo $result->cost_total; ?></td>
            <td><?php echo $result->gst_percent; ?></td>
            <td><?php echo $result->gst_percent; ?></td>
            <td><?php echo $result->sp_total; ?></td>
            <td><?php echo $result->sp_total; ?></td>
            <td><?php echo $result->barcode_number; ?></td>
<!--            <td>--><?php //echo $result->time; ?><!--</td>-->

<!--            <td>--><?php //echo "Rs. ".number_format($result->cost_total,2); ?><!--</td>-->
<!--            <td> --><?php //echo "Rs. ".number_format($result->sp_total,2); ?><!--</td>-->

        </tr>
    <?php } ?>
    </tbody>
<!--    <tfoot>-->
<!--    <tr>-->
<!--        <td colspan="4" style="text-align:right;">Total</td>-->
<!--        <th>-->
<!--            --><?php //echo "Rs. " . number_format($total_cost, 2); ?>
<!--        </th>-->
<!--        <th>-->
<!--            --><?php //echo "Rs. " . number_format($total_sp, 2); ?>
<!--        </th>-->
<!--    </tr>-->
<!---->
<!--    </tfoot>-->
</table>
</br>


</body>
</html>
        