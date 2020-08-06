<!DOCTYPE html>
<html>
    <head>
        <title>Rebate Summary</title>
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
            <h4 style="text-align: center;">Rebate Summary</h4>
        <table id="customers">
                        <thead>
                             <tr>
                                <th>Sr No</th>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Product Name</th>
                                <th>Product Type1</th>
                                <th>Product Type2</th>
                                <th>HSN</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Total Value</th>
                                <th>Rebate 5%</th>
                                <th>Discount</th>
                                <th>Other Discount</th>
                                <th>Total Taxable Value</th>
                                <?php foreach($gstarray as $arr): ?>
                                  <th><?= $arr; ?>% CGST <?php $gst['cgst_amount'][$arr] = 0; ?></th>
                                  <th><?= $arr; ?>% SGST <?php $gst['sgst_amount'][$arr] = 0; ?></th>
                                  <th><?= $arr; ?>% C+S GST <?php $gst['csgst_amount'][$arr] = 0; ?></th>
                                  <th><?= $arr; ?>% IGST <?php $gst['igst_amount'][$arr] = 0; ?></th>
                                <?php endforeach; ?>
                                <th>Adj +</th>
                                <th>Adj -</th>
                                <th>Amount after GST</th>
                                <th>Shipping Charges</th>
                                <th>Net Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                                $qty = 0;
                                $rate_per_item = 0;
                                $total = 0;
                                $taxable = 0; 
                                $adjustment_plus = 0;
                                $adjustment_minus = 0;
                                $net_amount = 0;
                                $total_amount = 0;
                                $arrId = array();
                                foreach($view as $key): ?>
                                <tr>
                                <td><?= $key['no']; ?></td>
                                <?php 
                                  if(in_array($result->iid, $arrId))
                                  {?>
                                    <td><?= $key['invoice_no']; ?></td>
                                  <?php } else {
                                    ?><td><?= $key['invoice_no']; ?></td>
                                  <?php $arrId[] = $result->iid;
                                  }
                                ?>
                                <td><?= $key['date_of_invoice']; ?></td>
                                <td><?= $key['asset_name']; ?></td>
                                <td><?= $key['productType']; ?></td>
                                <td><?= $key['assetType']; ?></td>
                                <td><?= $key['hsn']; ?></td>
                                <td><?php echo $key['invoice_quantity']; $qty +=$key['invoice_quantity'];  ?></td>
                                <td><?php echo "Rs. ".$key['rate_per_item']; $rate_per_item += $key['rate_per_item']; ?></td>
                                <td><?php echo "Rs. ".$key['total']; $total += $key['total']; ?></td>
                                <td><?= "Rs. ".(($key['rate_per_item'] * $key['discount_1'])/100); ?></td>
                                <td><?= "Rs. ".(($key['rate_per_item'] * $key['discount_2'])/100); ?></td>
                                <td><?= "Rs. ".(($key['rate_per_item'] * $key['discount_3'])/100); ?></td>
                                <td><?php echo "Rs. ".$key['taxable']; $taxable +=$key['taxable']; ?></td>
                                <?php foreach($gstarray as $arr): ?>
                                  <td>
                                    <?php if($key['gst_rate'] == $arr): ?>
                                    <?php echo "Rs. ".$key['cgst_amount']; $gst['cgst_amount'][$arr] +=$key['cgst_amount']; ?>
                                    <?php endif; ?>   
                                  </td>
                                  <td>
                                    <?php if($key['gst_rate'] == $arr): ?>
                                    <?php echo "Rs. ".$key['sgst_amount']; $gst['sgst_amount'][$arr] +=$key['sgst_amount']; ?>
                                    <?php endif; ?>   
                                  </td>
                                  <td>
                                    <?php if($key['gst_rate'] == $arr): ?>
                                    <?php echo "Rs. ".$key['cgst_amount']+$key['sgst_amount']; $gst['csgst_amount'][$arr] +=$key['cgst_amount'] + $key['sgst_amount']; ?>
                                    <?php endif; ?>   
                                  </td>
                                  <td>
                                    <?php if($key['gst_rate'] == $arr): ?>
                                    <?php echo "Rs. ".$key['igst_amount']; $gst['igst_amount'][$arr] +=$key['igst_amount']; ?>
                                    <?php endif; ?>   
                                  </td>
                                <?php endforeach; ?>
                                <td><?php echo $key['adjustment_plus']; $adjustment_plus +=$key['adjustment_plus']; ?></td>
                                <td><?php echo $key['adjustment_minus']; $adjustment_minus +=$key['adjustment_minus']; ?></td>
                                <td><?php echo $key['net_amount']; $net_amount += $key['net_amount'];  ?></td>
                                <td><?= $key['shipping_charges']; ?></td>
                                <td><?php if($key['total_amount']){ echo "Rs. ".$key['total_amount']; } $total_amount +=$key['total_amount'];  ?></td>
                            </tr>                  
                        
                      <?php endforeach; ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th colspan="7"></th>
                                <th><?= $qty; ?></th>
                                <th><?= "Rs. ".$rate_per_item ?></th>
                                <th><?= "Rs. ".$total; ?></th>
                                <th><?= "Rs. ".$discount_1; ?></th>
                                <th><?= "Rs. ".$discount_2; ?></th>
                                <th><?= "Rs. ".$discount_3; ?></th>
                                <!-- <th colspan="3"></th> -->
                                <th><?= "Rs. ".$taxable; ?></th>
                                <?php foreach($gstarray as $arr): ?>
                                  <th><?= "Rs. ".$gst['cgst_amount'][$arr]; ?></th>
                                  <th><?= "Rs. ".$gst['sgst_amount'][$arr]; ?></th>
                                  <th><?= "Rs. ".$gst['csgst_amount'][$arr]; ?></th>
                                  <th><?= "Rs. ".$gst['igst_amount'][$arr]; ?></th>
                                <?php endforeach; ?>
                                <th><?= $adjustment_plus; ?></th>
                                <th><?= $adjustment_minus; ?></th>
                                <th><?= "Rs. ".$net_amount; ?></th>
                                <th></th>
                                <th><?= "Rs. ".$total_amount; ?></th>
                        </tr>
                      </tfoot>
                    </table>                  
    </body>
</html>
        