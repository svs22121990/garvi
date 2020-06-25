<!DOCTYPE html>
<html>
    <head>
        <title>Product Summary</title>
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
            <div class="title-center"><?= $_SESSION[SESSION_NAME]['name']; ?></div>
            <div class="title-center"><?= $_SESSION[SESSION_NAME]['address'] ?></div>
            <div style="text-align: center;">GST Number: <?= $_SESSION[SESSION_NAME]['gst_number'] ?></div>
            <h4 style="text-align: center;">Product Summary</h4>
        <table id="customers">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Type 2</th>
                    <th>HSN Code</th>
                    <th>Selling Price</th>
                    <th>Quantity</th>
                    <th>Remaining qty</th>
                    <th>Damage qty</th>
                    <th>Total Amount</th>
                    <th>Purchase Date</th>
                    <th>AGE</th>
                </tr>
            </thead>

            <tbody>
                <?php $sr = 1; $product_mrp = 0; $total_quantity=0; $quantity=0; $damage_qty=0; ?>
                <?php foreach ($results as $result) {
					$startDate = $result->purchase_date;
					$endDate = date('Y-m-d');

					$datetime1 = date_create($startDate);
					$datetime2 = date_create($endDate);
					$interval = date_diff($datetime1, $datetime2, false);
                    $arrTime = array();
                    if($interval->y!=0){
                      $arrTime[] =  $interval->y.' Year ';
                    }
                    if($interval->m!=0){
                      $arrTime[] =  $interval->m .' Months ';
                    }
                    $arrTime[] =  $interval->d.' Days Ago';
                    ?>
                <tr>
                    <td><?= $sr++; ?></td>
                    <td><?= $result->asset_name; ?></td>
                     <td><?= $result->title; ?></td>
                      <td><?= $result->type; ?></td>
                      <td><?= $result->productType2; ?></td>
                      <td><?= $result->hsn; ?></td>
                    <td><?php echo "Rs. ".number_format($result->product_mrp,2); $product_mrp +=$result->product_mrp; ?></td>
                    <td><?php echo  $result->total_quantity; $total_quantity +=$result->total_quantity; ?></td>
                    <td><?php echo $result->quantity; $quantity +=$result->quantity; ?></td>
                    <td><?php echo $result->damage_qty; $damage_qty +=$result->damage_qty; ?></td>
                    <td><?php echo "Rs. ".number_format($result->product_mrp*$result->quantity,2); ?></td>

                    <td><?= $result->purchase_date ?></td>
                    <td><?= implode(" ",$arrTime); ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6"></th>
                    <th><?php echo "Rs. ".number_format($product_mrp,2); ?></th>
                    <th><?php echo  $total_quantity; ?></th>
                    <th><?php echo $quantity; ?></th>
                    <th><?php echo $damage_qty; ?></th>
                    <th><?php echo "Rs. ".number_format($product_mrp*$quantity,2); ?></th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </body>
</html>
