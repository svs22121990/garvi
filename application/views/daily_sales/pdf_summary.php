<!DOCTYPE html>
<html>
    <head>
        <title>Sales Summary Report</title>
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
            <h4 style="text-align: center;">Daily Sales Report</h4>
        <table id="customers">
            <thead>
                <tr>    
                    <th>Sr No</th>
                    <th>Date</th>
                    <th>Invoice No.</th>
                    <th>Description of Goods</th>
                    <th>HSN</th>
                    <th>Sale Price</th>
                    <th>Qty</th>
                    <th>Total Amt</th>
                    <th>Discount 1</th>
                    <th>Discount 2</th>
                    <th>Discount 3</th>
                    <th>Total Discount</th>
                    <th>Amt After Disc.</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            
            <tbody> 
                <?php $sr = 1;  $arrId = array(); $qty=0; $net_amount=0; $totalSum=0; ?>
                <?php foreach ($results as $row) {   if(in_array($row->id, $arrId))
                    {
                        $invoice ='';
                        $no = '';
                      }
                    else
                    {
                        $arrId[] = $row->id;
                        $totalSum += $row->total;
                        $totalNet += $row->net_amount;
                        $invoice = $row->invoice_no;
                        $no = $sr++;
                    } ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= date('d-m-Y', strtotime($row->date_of_invoice)) ?></td>
                    <td><?= $invoice; ?></td>
                    <td><?= $row->asset_name ?></td>
                    <td><?= $row->hsn_code; ?></td>
                    <td><?= $row->rate_per_item; ?></td>
                    <td><?php echo $row->quantity; $qty +=$row->quantity; ?></td>
                    <td><?= $row->total; ?></td>
                    <td><?= $row->discount_1; ?></td>
                    <td><?= $row->discount_2; ?></td>
                    <td><?= $row->discount_3; ?></td>
                    <td><?= $row->discount_amount; ?></td>
                    <td><?= $row->taxable; ?></td>
                    <td><?= $row->cgst_amount; ?></td>
                    <td><?= $row->sgst_amount; ?></td>
                    <td><?= $row->net_amount; ?></td>
                </tr>    
                <?php } ?>                  
            </tbody>
            <thead>
                <tr>    
                    <th colspan="6"></th>                               
                    <th><?= $qty; ?></th>
                    <th><?= "Rs. ".number_format($totalSum,2); ?></th>
                    <th colspan="7"></th>
                    <th><?= "Rs. ".number_format($totalNet,2); ?></th>    
                </tr>
            </thead>
        </table>                   
    </body>
</html>
        