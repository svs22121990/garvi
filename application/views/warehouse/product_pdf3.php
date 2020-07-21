<!DOCTYPE html>
<html>
    <head>
        <title>Warehouse Bill</title>
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
                   'warehouse_date' => date('d-m-Y', strtotime($key->warehouse_date)),
                   'employee_name' => $key->employee_name,
                   'purchase_date' => $key->purchase_date,
                    'title' => $key->title,
                    'asset_name' => $key->asset_name,
                    'type' => $key->type,
                    'label' => $key->label,
                    'color' => $key->color,
                    'size' => $key->size,
                    'fabric' => $key->fabric,
                    'craft' => $key->craft,
                    'quantity' => $key->quantity,
                    'price' => "Rs. " . number_format($key->price, 2),
                    'gst_percent' => $key->gst_percent,
                    'hsn' => $key->hsn,
                    'markup_percent' => $key->markup_percent,
                        'cost_total' =>  "Rs. " . $key->cost_total,
                        'product_mrp' =>"Rs. " . $key->product_mrp,
                        'sp_total' => "Rs. " . $key->sp_total,


                );

                $qty = 0;
                $product_mrp = 0;
                $total = 0;
                $totalGST = 0;
                $finalTotal = 0;
                $totalmarkup = 0;
                $selling = 0;
//                $total_amount += $key->total_amount;
                $product_mrp += $key->product_mrp;
                $totalGST += (($key->gst_percent/100) * $key->cost_total);
                $totalmarkup += ($key->product_mrp - $key->price);
                $selling += $key->sp_total;
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
                    <td><?= $result['warehouse_date']; ?></td>
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
                <th>Category Name</th>
                <th>Product Name</th>
                <th>Product Type Name</th>
                <th>Product Type Name 2</th>
                <th>Color</th>
                <th>Size</th>
                <th>Fabric</th>
                <th>Craft</th>
                <th>Quantity</th>
                <th>Product Price</th>
                <th>GST %</th>
                <th>HSN</th>
                <th>Markup %</th>
                <th>Total Cost Amount</th>
                <th>SP</th>
                <th>Total SP Amount</th>

                <!--                    <th>Total Amount</th> -->
            </tr>
            </thead>
            <tbody>
            <?php $no=1; foreach ($results2 as $result) { ?>
                <tr>
                    <td><?= $result['purchase_date']; ?></td>
                    <td><?= $result['title']; ?></td>
                    <td><?= $result['asset_name']; ?></td>
                    <td><?= $result['type']; ?></td>
                    <td><?= $result['label']; ?></td>
                    <td><?= $result['color']; ?></td>
                    <td><?= $result['size']; ?></td>
                    <td><?= $result['fabric']; ?></td>
                    <td><?= $result['craft']; ?></td>
                    <td><?= $result['quantity']; ?></td>
                    <td><?= $result['price']; ?></td>
                    <td><?= $result['gst_percent']; ?></td>
                    <td><?= $result['hsn']; ?></td>
                    <td><?= $result['markup_percent']; ?></td>
                    <td><?= $result['cost_total']; ?></td>
                    <td><?= $result['product_mrp']; ?></td>
                    <td><?= $result['sp_total']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>

            <tr>
                <td colspan="15" >&nbsp;<span class="pull-right">Total CGST Amount</span></td>
                <th>
                    <?= "Rs. " . number_format($totalGST/2, 2); ?>
                </th>
                <th colspan="2"></th>
            </tr>
            <tr>
                <td colspan="15" >&nbsp;<span class="pull-right">Total SGST Amount</span></td>
                <th>
                    <?= "Rs. " . number_format($totalGST/2, 2); ?>
                </th>
                <th colspan="2"></th>
            </tr>

            <tr>
                <td colspan="15" >&nbsp;<span class="pull-right">Total Markup Amount</span></td>
                <th>
                    <?= "Rs. " . number_format($totalmarkup, 2); ?>
                </th>
                <th colspan="2"></th>
            </tr>
            <tr>
                <td colspan="15">&nbsp;<span class="pull-right">Final Selling Amount</span></td>
                <th><?= "Rs. " . number_format($selling, 2); ?></th>
                <td colspan="3"></td>
            </tr>

            </tfoot>
        </table>
    </body>
</html>
        