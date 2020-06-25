<!DOCTYPE html>
<html>
    <head>
        <title>Dispatch/Return</title>
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
            <h4 style="text-align: center;">Dispatch/Return</h4>
        <table id="customers">
            <thead>
                <tr>    
                    <th>Sr. No.</th>                    
                    <th>DN No</th>
                    <th>Date</th>                             
                    <th>Amount</th>
                    <th>User</th>  
                </tr>
            </thead>
            
            <tbody> 
                <?php $sr = 1; $sum_amount=0; ?>
                <?php foreach ($rows as $row) { 
                    ?>
                <tr>
                    <td><?= $sr++; ?></td>
                    <td><?= $row->dn_number; ?></td>
                    <td><?= date('d-m-Y', strtotime($row->dispatch_date)); ?></td>
                    <td><?php echo "Rs. ".number_format($row->sum_amount,2); $sum_amount += $row->sum_amount; ?></td>
                    <td><?= $row->employee_name; ?></td>
                     
                </tr>    
                <?php } ?>                  
            </tbody>
            <tfoot>
                <tr>    
                    <th colspan="3"></th>                                                
                    <th><?= "Rs. ".number_format($sum_amount,2); ?></th>
                    <th></th>  
                </tr>
            </tfoot>
        </table>                   
    </body>
</html>
        