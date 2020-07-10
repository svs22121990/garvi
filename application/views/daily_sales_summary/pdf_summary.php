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
            <h4 style="text-align: center;">Sales Summary Report</h4>
        <table id="customers">
            <thead>
                <tr>    
                    <th>Sr. No.</th>  
                    <th>Invoice No</th>                  
                    <th>Date</th>
                    <th>Type of Sales</th>
                    <th>Product Name</th> 
                    <th>Type 1</th>
                     <th>Type 2</th>
                    <!-- <th>Payment Mode</th> -->
                    <th>Quantity</th>
                    <th>Sub Total</th>  
                    <th>Total Amount</th>
                    
                </tr>
            </thead>
            
            <tbody> 
                <?php $sr = 1;  $arrId = array(); $qty=0; $net_amount=0; $totalSum=0; ?>
                <?php foreach ($results as $row) {   if(in_array($row->id, $arrId))
                    {
                        $sums = '';
                        $invoice ='';
                        $no = '';
                      }
                    else
                    {
                        $arrId[] = $row->id;
                        $sums = "Rs. ".number_format($row->sumAmount,2);
                        $totalSum += $row->sumAmount;
                        $invoice = $row->invoice_no;
                        $no = $sr++;
                    } ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $invoice; ?></td>
                    <td><?= date('d-m-Y', strtotime($row->date_of_invoice)) ?></td>
                    <td><?= $row->salesType ?></td>
                    <td><?= $row->asset_name; ?></td>
                    <td><?= $row->ProductType; ?></td>
                    <td><?= $row->assetType; ?></td>
                    <!-- <td> rr </td> -->
                    <td><?php echo $row->quantity; $qty +=$row->quantity; ?></td>
                    <td><?php echo "Rs. ".number_format($row->net_amount,2); $net_amount += $row->net_amount;  ?></td>
                    <td><?= $sums;  ?></td>
                </tr>    
                <?php } ?>                  
            </tbody>
            <thead>
                <tr>    
                    <th colspan="5"></th>                               
                    <th><?= $qty; ?></th>
                    <th><?= "Rs. ".number_format($net_amount,2); ?></th>  
                    <th><?= "Rs. ".number_format($totalSum,2); ?></th>
                    
                </tr>
            </thead>
        </table>                   
    </body>
</html>
        