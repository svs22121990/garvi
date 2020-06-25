<!DOCTYPE html>
<html>
    <head>
        <title>Inventory </title>
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
        <h4 style="text-align: center;">Inventory (Product)</h4>
        <div class="title-center"><img class="img-responsive logo-image" src="http://app.garvigurjari.in/images/logo.jpg" style=""></div>
            <div class="title-center"><?= $_SESSION[SESSION_NAME]['name']; ?></div>
            <div class="title-center"><?= $_SESSION[SESSION_NAME]['address'] ?></div>
            <div style="text-align: center;">GST Number: <?= $_SESSION[SESSION_NAME]['gst_number'] ?></div>
        <table id="customers">
            <thead>
                <tr>    
                    <th>Sr. No</th>                    
                    <th>User</th>
                    <th>Less then 6 months</th>                             
                    <th>6 Months to 1 Year</th>
                    <th>1 Year to 2 Year</th> 
					<th>2 Year to 3 Year</th>
                    <th>Above 3 Year</th>
                    <th>Total</th>
                    
                </tr>
            </thead>
            
            <tbody> 
                <?php $sr = 1; ?>
                <?php foreach ($results as $result) { 
                    ?>
                <tr>
                    <td><?= $sr++; ?></td>
                    <td><?= $result['name']; ?></td>
                    <td><?= "Rs. ".number_format($result['six_month'],2); ?></td>
                    <td><?= "Rs. ".number_format($result['one_year'],2); ?></td>
                    <td><?= "Rs. ".number_format($result['two_year'],2); ?></td>
					<td> <?= "Rs. ".number_format($result['three_year'],2); ?></td>
                    <td> <?= "Rs. ".number_format($result['three_year1'],2); ?></td>
                    <td> <?= "Rs. ".number_format($result['total'],2); ?></td>   
                </tr>    
                <?php } ?>                  
            </tbody>
        </table>                   
    </body>
</html>
        