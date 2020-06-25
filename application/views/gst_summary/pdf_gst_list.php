<!DOCTYPE html>
<html>
    <head>
        <title>GST Summary</title>
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
            <h4 style="text-align: center;">GST Summary</h4>
        <table id="customers">
            <thead>
                <tr>    
                    <th>Sr. No.</th>                    
                    <th>GST Rate</th>
                    <th>GST Amount</th>                             
                    <th>Taxable Value</th>
                    <th>CGST Rate</th>  
                    <th>CGST Amount</th>
                    <th>SGST Rate</th>
                    <th>SGST Amount</th>
                    <th>IGST Rate</th>
                    <th>IGST Amount</th>
                </tr>
            </thead>
            
            <tbody> 
                <?php $sr = 1; $gst_amount=0; $taxable=0; $sgst_amount=0; $igst_amount=0; $cgst_amount =0;  ?>
                <?php foreach ($results as $row) { 
                    ?>
                <tr>
                    <td><?= $sr++; ?></td>
                    <td><?= $row['gst_rate']; ?> %</td>
                    <td><?php echo "Rs. ".number_format($row['gst_amount'],2); $gst_amount +=$row['gst_amount']; ?></td>
                    <td><?php echo "Rs. ".number_format($row['taxable'],2); $taxable +=$row['taxable'];  ?></td>
                    <td><?= $row['cgst_rate']."%"; ?></td>
                    <td><?php echo "Rs. ".number_format($row['cgst_amount'],2); $cgst_amount +=$row['cgst_amount']; ?></td>
                    <td><?= $row['sgst_rate']."%"; ?></td>
                    <td><?php echo "Rs. ".number_format($row['sgst_amount'],2); $sgst_amount +=$row['sgst_amount'];  ?> </td>
                    <td><?=  $row['igst_rate']."%"; ?></td>
                    <td><?php echo "Rs. ".number_format($row['igst_amount'],2); $igst_amount +=$row['igst_amount']; ?></td> 
                </tr>    
                <?php } ?>                  
            </tbody>
            <thead>
                <tr>    
                    <th colspan="2"></th>                    
                    <th><?php echo "Rs. ".number_format($gst_amount,2); ?></th>                             
                    <th><?php echo "Rs. ".number_format($taxable,2); ?></th>
                    <th></th>  
                    <th><?php echo "Rs. ".number_format($cgst_amount,2); ?></th>
                    <th></th>
                    <th><?php echo "Rs. ".number_format($sgst_amount,2); ?></th>
                    <th></th>
                    <th><?= "Rs. ".number_format($igst_amount,2); ?></th>
                </tr>
            </thead>
        </table>                   
    </body>
</html>
        