<!DOCTYPE html>
<html>
    <head>
        <title>Invoice</title>
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
            <h4 style="text-align: center;">Invoice</h4>
        <table id="customers">
            <thead>
                <tr>    
                    <th>Sr. No.</th>                    
                    <th>Invpice No.</th>
                    <th>GST Number</th>                             
                    <th>Customer Name</th>
                    <th>Date of invoice</th>  
                    <!--<th>Serial Number of Invoice</th>-->
                    <th>Invoice Amount</th>
                </tr>
            </thead>
            
            <tbody> 
                <?php $sr = 1; $sumAmount =0; ?>
                <?php foreach ($results as $result) { 
                    ?>
                <tr>
                    <td><?= $sr++; ?></td>
                    <td><?= $result->invoice_no; ?></td>
                    <td><?= $result->gst_number; ?></td>
                    <td><?= $result->receiver_bill_name; ?></td>
                    <td><?= date('d-m-Y', strtotime($result->date_of_invoice)) ?></td>
                    <!--<td><?= $result->serial_no_of_invoice ?></td>-->
                    <td><?php echo "Rs. ".number_format($result->sumAmount,2); $sumAmount +=$result->sumAmount; ?></td> 
                </tr>    
                <?php } ?>                  
            </tbody>
            <tfoot>
                <tr>    
                    <th colspan="5"></th>                    
                    <th><?= "Rs. ".number_format($sumAmount,2); ?></th>
                </tr>
            </tfoot>
        </table>                   
    </body>
</html>
        