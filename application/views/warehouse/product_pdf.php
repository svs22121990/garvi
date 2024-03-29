<!DOCTYPE html>
<html>
    <head>
        <title>Warehouse Product Details</title>
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

            <div class="title-center"><?= $_SESSION[SESSION_NAME]['address'] ?></div>
            <!--<div style="text-align: center;">GST Number: <?/*= $_SESSION[SESSION_NAME]['gst_number'] */?></div>-->
            <h4 style="text-align: center;">Warehouse Products Received </h4>
            <?php $qty=0; $product_mrp=0; $sr = 1; $data = array(); $allTotal = 0; ; $markup = 0; $totalGST = 0; $finalTotal = 0; ?>
            <?php foreach ($results as $result) {
                $data[] = array(
                    'no' => $sr++,
                    'title' =>$result->title,
                    'type' =>$result->type,
                    'asset_name' =>$result->asset_name,
                    'quantity' =>$result->quantity,
                    'product_mrp' =>$result->product_mrp,
                    'total' => $result->sp_total,
                    'gst_percent' =>$result->gst_percent,
                    'hsn' =>$result->hsn,
                    'markup_percent' =>$result->markup_percent,
                    //'hsn' =>$result->hsn;
                    //'gst_percent' =>$result->gst_percent,
                );
                $qty += $result->quantity;
                $product_mrp += $result->product_mrp;
                $allTotal += $result->sp_total;
                $totalGST += (($result->gst_percent/100) * ($result->product_mrp));
                $markup += (($result->markup_percent/100) * ($result->product_mrp));
             }
//             $data[] = array(
//                    'no'  => '',
//                    'title' =>'',
//                    'type' =>'',
//                    'asset_name' =>'',
//                    'quantity' =>$qty,
//                    'product_mrp' =>$product_mrp,
//                    'total' => $allTotal,
//                    'gst_percent' =>'',
//                    'hsn' =>'',
//                    'markup_percent' =>'',
//                    //'hsn' =>$result->hsn;
//                    //'gst_percent' =>$result->gst_percent,
//                );
             ?>
             <div class="row" style="padding: 10px; font-size: 1.3em;">
                    <div class="col-md-4">
                        DN No.: <strong><?php echo !empty($results) ? $results[0]->dn_number : ""; ?></strong>
                    </div>
                    <div class="col-md-4">
                        Warehouse Bill Date: <strong><?php echo !empty($results) ? $results[0]->dispatch_date : ""; ?></strong>
                    </div>
                    <div class="col-md-4">
                        Received From: <strong><?php echo !empty($results) ? $results[0]->employee_name : ""; ?></strong>
                    </div>
                    <!--
                 <div class="col-md-4">
                     Category Name: <strong><?php //echo !empty($results) ? $results[0]->title : ""; ?></strong>
                 </div>
                 <div class="col-md-4">
                     Product Type Name: <strong><?php// echo !empty($results) ? $results[0]->type : ""; ?></strong>
                 </div>
                 <div class="col-md-4">
                     Product Name: <strong><?php// echo !empty($results) ? $results[0]->asset_name : ""; ?></strong>
                 </div>-->

<!--                 <div class="col-md-4">-->
<!--                     Quantity: <strong>--><?php //echo !empty($results) ? $results[0]->quantity : ""; ?><!--</strong>-->
<!--                 </div>-->

                </div>
        <!--
        <table id="customers">
            <thead>
                <tr>
                     <th>Sr. No.</th>
                    <th>Category Name</th>
                    <th>Product Type Name</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
					<th>GST %</th>
					<th>HSN</th>
					<th>Markup %</th>
                    <th>Total Amount</th>
                </tr>
            </thead>

            <tbody>
                <?php// $sr = 1; ?>
                <?php// foreach ($data as $result) { ?>
                <tr>
                    <td><?php //$result['no']; ?></td>
                    <td><?php// $result['title']; ?></td>
                    <td><?php// $result['type']; ?></td>
                    <td><?php// $result['asset_name']; ?></td>
                    <td><?php// $result['quantity']; ?></td>
                    <td><?php// "Rs. ".number_format($result['product_mrp'],2); ?></td>
					<td> <?php// $result['gst_percent'];  ?></td>
					<td> <?php// $result['hsn']; ?></td>
					<td> <?php// $result['markup_percent']; ?></td>
                    <td> <?php// "Rs. ".number_format($result['total'],2); ?></td>

                </tr>
                <?php //} ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="9" style="text-align:right;">Total GST Amount</td>
                <th>
                    <?php //"Rs. " . number_format($totalGST, 2); ?>
                </th>

            </tr>
            <tr>
                <td colspan="9" style="text-align:right;">Total Markup Amount</td>
                <th>
                    <?php //"Rs. " . number_format($markup, 2); ?>
                </th>

            </tr>
            <tr>
                <td colspan="9" style="text-align:right;">Final Selling Amount</td>
                <th>
                    <?php// "Rs. " . number_format($totalGST + $allTotal, 2); ?>
                </th>

            </tr>

            </tfoot>
        </table>
        </br>-->
        <h4 style="text-align: center;">Barcode Details</h4>
        <table id="barcode" border=1 style="width: 100%;">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Barcode Number</th>
                    <th>Barcode Image</th>
					<th style="width:0%;"></th>
                </tr>
            </thead>

            <tbody>
                <?php $sr = 1; ?>
                <?php foreach ($barcodes as $barcode) {
                    $label = $barcode->asset_name.'-';
                    $label .= number_format($barcode->product_mrp, 2).'-';
                    if($barcode->asset_type_id == 7)
                        $label .= 'HL';
                    else
                        $label .= 'HC';
                    ?>
                <tr>
                    <td style="padding: 10px; text-align: center"><?php echo $sr ?></td>
                    <td style="padding: 10px; text-align: center"> <?= $barcode->barcode_number; ?></td>
                    <td style="padding: 10px; text-align: center">
                        <div>
                            <div><?= $label; ?></div>
                            <div><img src="<?php echo base_url(); ?>admin/assets/warehouse_barcode/<?php echo $barcode->barcode_image; ?>"></div>
                        </div>
                    </td>
                </tr>
                <?php $sr++; } ?>
            </tbody>
        </table>

    </body>
</html>
        