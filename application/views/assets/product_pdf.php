<!DOCTYPE html>
<html>
    <head>
        <title>Product Details</title>
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
            <div style="text-align: center;">GST Number: <?= $_SESSION[SESSION_NAME]['gst_number'] ?></div>
            <h4 style="text-align: center;">Product Details</h4>
            <?php $qty=0; $product_mrp=0; $sr = 1; $data = array(); $allTotal = 0;  $totalGST = 0; $finalTotal = 0; ?>
            <?php foreach ($results as $result) { 
                $total = $result->product_mrp * $result->total_quantity;
                $data[] = array(
                    'no' => $sr++,
                    'title' =>$result->title,
                    'type' =>$result->type,
                    'asset_name' =>$result->asset_name,
                    'quantity' =>$result->total_quantity,
                    'product_mrp' =>$result->product_mrp,
                    'total' => $total,
                    'gst_percent' =>$result->gst_percent,  
                    'hsn' =>$result->hsn,
                    'lf_no' =>$result->lf_no,
                    //'hsn' =>$result->hsn; 
                    //'gst_percent' =>$result->gst_percent, 
                );
                $qty += $result->total_quantity;
                $product_mrp += $result->product_mrp;
                $allTotal += $total;
                $totalGST += (($result->gst_percent/100) * ($total));
             } 
             $data[] = array(
                    'no'  => '',
                    'title' =>'',
                    'type' =>'', 
                    'asset_name' =>'',
                    'quantity' =>$qty,
                    'product_mrp' =>$product_mrp,
                    'total' => $allTotal,
                    'gst_percent' =>'', 
                    'hsn' =>'',
                    'lf_no' =>'',
                    //'hsn' =>$result->hsn; 
                    //'gst_percent' =>$result->gst_percent, 
                );
             ?>
             <div class="row" style="padding: 10px; font-size: 1.3em;">
                    <div class="col-md-4">
                        Bill No.: <strong><?php echo !empty($results) ? $results[0]->bill_no : ""; ?></strong>
                    </div>
                    <div class="col-md-4">
                        Bill Date: <strong><?php echo !empty($results) ? $results[0]->bill_date : ""; ?></strong>
                    </div>
                    <div class="col-md-4">
                        Received From: <strong><?php echo !empty($results) ? $results[0]->employee_name : ""; ?></strong>
                    </div>
                </div>
        <table id="customers">
            <thead>
                <tr>    
                     <th>Sr. No.</th>
                    <th>Category Name</th>                    
                    <th>Product Type Name</th>
                    <th>Product Name</th>                             
                    <th>Quantity</th>
                    <th>Product Price</th> 
					<th>Total Amount</th>
					<th>GST %</th>
					<th>HSN</th>
					<th>LF No.</th>
                </tr>
            </thead>
            
            <tbody> 
                <?php $sr = 1; ?>
                <?php foreach ($data as $result) { ?>
                <tr>
                    <td><?= $result['no']; ?></td>
                    <td><?= $result['title']; ?></td>
                    <td><?= $result['type']; ?></td>
                    <td><?= $result['asset_name']; ?></td>
                    <td><?= $result['quantity']; ?></td>
                    <td><?= "Rs. ".number_format($result['product_mrp'],2); ?></td>
					<td> <?= "Rs. ".number_format($result['total'],2); ?></td>
					<td> <?= $result['gst_percent'];  ?></td>
					<td> <?= $result['hsn']; ?></td>
					<td> <?= $result['lf_no']; ?></td>    
                </tr>    
                <?php } ?>                  
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6" style="text-align:right;">Total GST Amount</td>
                <th>
                    <?= "Rs. " . number_format($totalGST, 2); ?>
                </th>  
                <td colspan="3"></td>            
            </tr>
            <tr>
                <td colspan="6" style="text-align:right;">Final Total Amount</td>
                <th>
                    <?= "Rs. " . number_format($totalGST + $allTotal, 2); ?>
                </th>              
                <td colspan="3"></td>            
            </tr>
            
            </tfoot>
        </table>                   
    </body>
</html>
        