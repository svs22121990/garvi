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
            
            <div class="title-center"><?= $_SESSION[SESSION_NAME]['address'] ?></div>
            <!--<div style="text-align: center;">GST Number: <?/*= $_SESSION[SESSION_NAME]['gst_number'] */?></div>-->
            <h4 style="text-align: center;">Warehouse Products</h4>
            <?php $qty=0; $product_mrp=0; $sr = 1; $data = array(); $total_cost = 0; ; $total_sp = 0;  ?>
            <?php foreach ($results as $result) { 
                $data[] = array(
                    'no' => $sr++,
                    'dn_number' =>$result->dn_number,
                    'warehouse_date' => $result->warehouse_date,
                    'employee_name' =>$result->employee_name,
                    'cost_total' =>$result->cost_total,
                    'sp_total' =>$result->sp_total,
                );

                $total_cost += $result->cost_total;
                $total_sp += $result->sp_total;
                
             } 

             ?>
             

        <table id="customers">
            <thead>
                <tr>    
                <th class="text-center">Sr. No.</th>
                <th class="text-center">DN. No.</th>
                <th class="text-center"> Date</th>
                <th class="text-center">Received from</th>
                <th class="text-center">Total CP Amt.</th>
                <th class="text-center">Total SP Amt.</th>
                </tr>
            </thead>
            
            <tbody> 
                <?php $sr = 1; ?>
                <?php foreach ($results as $result) { ?>
                <tr>
                    <td><?php echo $sr++; ?></td>
                    <td><?php echo $result->dn_number; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($result->warehouse_date)); ?></td>
                    <td><?php echo $result->employee_name; ?></td>
                    <td><?php echo "Rs. ".number_format($result->cost_total,2); ?></td>
                    <td> <?php echo "Rs. ".number_format($result->sp_total,2); ?></td>

                </tr>    
                <?php } ?>                  
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Total</td>
                <th>
                    <?php echo "Rs. " . number_format($total_cost, 2); ?>
                </th>  
                <th>
                    <?php echo "Rs. " . number_format($total_sp, 2); ?>
                </th>
            </tr>
            
            </tfoot>
        </table>
        </br>
        

    </body>
</html>
        