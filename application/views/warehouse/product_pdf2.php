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
            <div class="title-center"><?= $_SESSION[SESSION_NAME]['address'] ?></div>
            <div style="text-align: center;">GST Number: <?= $_SESSION[SESSION_NAME]['gst_number'] ?></div>
            <h4 style="text-align: center;">Warehouse Bill</h4>
             <?php $sr = 1; ?>
            <?php $results2=array(); $total_amount= 0; 
            foreach($results as $key){
                $no = $sr++;
                $results2[] = array(
                   'no' => $no,
                   'dn_number' =>  $key->dn_number,
                   'warehouse_date' => date('d-m-Y', strtotime($key->warehouse_date)),
                   'employee_name' => $key->employee_name,
                   'total_amount' => $key->total_amount,
                );
                $total_amount += $key->total_amount;
            } 
            $results2[] = array(
                   'no' => '',
                   'dn_number' =>  '',
                   'warehouse_date' => '',
                   'employee_name' => '',
                   'total_amount' => $total_amount,
                );
            ?>
        <table id="customers">
            <thead>
                <tr>    
                    <th style="width:60px;">Sr. No.</th>                    
                    <th>DN No.</th>
                    <th>Warehouse Bill Date</th>
                    <th>Recived From</th>
                    <th>Total Amount</th> 
                </tr>
            </thead>
            <tbody> 
                <?php $no=1; foreach ($results2 as $result) { ?>
                <tr>
                    <td><?= $result['no']; ?></td>
                    <td><?= $result['dn_number']; ?></td>
                    <td><?= $result['warehouse_date']; ?></td>
                    <td><?= $result['employee_name']; ?></td>
                    <td><?= "Rs. ".number_format($result['total_amount'],2); ?></td>  
                </tr> 
                <?php } ?>                     
            </tbody>
        </table>                   
    </body>
</html>
        