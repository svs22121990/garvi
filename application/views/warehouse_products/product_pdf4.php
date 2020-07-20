<!DOCTYPE html>
<html>
<head>
    <title>Warehouse Products Bill</title>
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
<div style="text-align: center;">GST Number: <?= $_SESSION[SESSION_NAME]['gst_number'] ?></div>
<h4 style="text-align: center;">Warehouse Product Bill</h4>
<?php $sr = 1; ?>
<?php $results2=array(); $total_amount= 0;
foreach($results as $key){
    $no = $sr++;
    $results2[] = array(
        'no' => $no,
        'dn_number' =>  $key->dn_number,
        'dispatch_date' => date('d-m-Y', strtotime($key->dispatch_date)),
        'employee_name' => $key->employee_name,
        'purchase_date' => $key->purchase_date,
        'asset_name' => $key->title,
        'title' => $key->asset_name,
        'size' => $key->type,
        'color' => $key->label,
        'fabric' => $key->color,
        'craft' => $key->size,
        'product_mrp' => $key->fabric,
        'gst_percent' => 'Rs. '.$key->product_mrp ,
        't_product_mrp' => 'Rs. '.(($key->product_mrp * $key->quantity) * ($key->gst_percent/100)),
        'price' => "Rs. " . number_format($key->price, 2),


);
//                $total_amount += $key->total_amount;

}
?>
<table id="customers">
    <thead>
    <tr>
        <th style="width:60px;">Sr. No.</th>
        <th>DN No.</th>
        <th>Warehouse Bill Date</th>
        <th>Recived From</th>
        <!--                    <th>Total Amount</th> -->
    </tr>
    </thead>
    <tbody>
    <?php $no=1; foreach ($results2 as $result) { ?>
        <tr>
            <td><?= $result['no']; ?></td>
            <td><?= $result['dn_number']; ?></td>
            <td><?= $result['dispatch_date']; ?></td>
            <td><?= $result['employee_name']; ?></td>
            <!--                    <td>--><?//= "Rs. ".number_format($result['total_amount'],2); ?><!--</td>  -->
        </tr>
    <?php } ?>
    </tbody>
</table>
<br>
<table id="customers">
    <thead>
    <tr>
        <th>Purchase Date</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Category</th>
        <th>Size</th>
        <th>Color</th>
        <th>Fabric</th>
        <th>Craft</th>
        <th>Selling Price</th>
        <th>GST %</th>
        <th>GST</th>

        <!--                    <th>Total Amount</th> -->
    </tr>
    </thead>
    <tbody>
    <?php $no=1; foreach ($results2 as $result) { ?>
        <tr>
            <td><?= $result['purchase_date']; ?></td>
            <td><?= $result['asset_name']; ?></td>
            <td><?= $result['quantity']; ?></td>
            <td><?= $result['title']; ?></td>
            <td><?= $result['size']; ?></td>
            <td><?= $result['color']; ?></td>
            <td><?= $result['fabric']; ?></td>
            <td><?= $result['craft']; ?></td>
            <td><?= $result['product_mrp']; ?></td>
            <td><?= $result['gst_percent']; ?></td>
            <td><?= $result['product_mrp']; ?></td>

        </tr>
    <?php } ?>
    </tbody>

</table>
</body>
</html>
