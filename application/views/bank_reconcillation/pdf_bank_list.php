<!DOCTYPE html>
<html>
    <head>
        <title>Bank Summary</title>
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
        <h4 style="text-align: center;">Bank Summary</h4>
        <div class="title-center"><img class="img-responsive logo-image" src="http://app.garvigurjari.in/images/logo.jpg" style=""></div>
            <div class="title-center"><?= $_SESSION[SESSION_NAME]['name']; ?></div>
            <div class="title-center"><?= $_SESSION[SESSION_NAME]['address'] ?></div>
            <div style="text-align: center;">GST Number: <?= $_SESSION[SESSION_NAME]['gst_number'] ?></div>
            <?php
                    $arrId = array();
                    $no = 0;
                    $all_total = 0;
                    foreach($results as $result){
                      if(in_array($result->iid, $arrId))
                        {
                            //$sumAmount = '';
                            $invoice_no = '';
                            $sr_no = '';
                            $total_amount = '';
                            $amount_deposited_in_bank = 0;
                              $gst_on_bank_commission = '';
                              $bank_commission = '';
                              $date_of_deposit = '';
                              $type_of_deposit = '';
                        }
                        else
                        {
                            $no++;
                            $arrId[] = $result->iid;
                            //$sumAmount = "Rs ".number_format($row->sumAmount,2);
                            $invoice_no = $result->invoice_no;
                            $amount_deposited_in_bank = $result->amount_deposited_in_bank;
                              $gst_on_bank_commission = $result->gst_on_bank_commission;
                              $bank_commission = $result->bank_commission;
                              $date_of_deposit = $result->date_of_deposit;
                              $type_of_deposit = $result->type_of_deposit;
                            $sr_no = $no;
                            $this->db->where('invoice_id',$result->iid);
                            $query = $this->db->get('invoice_details');
                            $result2 = $query->result();
                            $net_amount = 0;
                            foreach($result2 as $r){
                                $net_amount += $r->net_amount;
                            }
                            $total_amount = "Rs. ".number_format($net_amount,2);
                            $all_total += $net_amount;
                        }
                         $arrBank[] =array(
                        'sr'=>$sr_no,
                        'invoice_no'=>$invoice_no,
                        'date_of_invoice'=>$result->date_of_invoice,
                        'asset_name' => $result->asset_name,

                        'invoice_quantity'=> $result->invoice_quantity,
                        'rate_per_item'=> $result->rate_per_item,
                    
                        'discount_1' => ($result->rate_per_item * $result->discount_1)/100,
                        'discount_2'=> ($result->rate_per_item * $result->discount_2)/100 ,
                        'discount_3'=> ($result->rate_per_item * $result->discount_3)/100,
                        'taxable'=>$result->taxable,
                    
                    'gst_rate'=>$result->gst_rate,
                    'gst_amount'=>$result->gst_amount,

                    'cgst_rate' => $result->cgst_rate,
                    'cgst_amount'=>$result->cgst_amount,

                    'sgst_rate'=>$result->sgst_rate,
                    'sgst_amount'=>$result->sgst_amount,

                    'igst_rate'=>$result->igst_rate,
                    'igst_amount'=>$result->igst_amount,


                    'adjustment_plus'=> $result->adjustment_plus,                
                    'adjustment_minus'=>$result->adjustment_minus,
                    'net_amount'=>$result->net_amount,

                    'shipping_charges'=>$result->shipping_charges,


                     'total_amount' => $total_amount,

                     'amount_deposited_in_bank' => $amount_deposited_in_bank,
                     'type_of_deposit' => $type_of_deposit,
                     'gst_on_bank_commission' => $gst_on_bank_commission,
                     'bank_commission' => $bank_commission,
                     'date_of_deposit'=>$date_of_deposit,
                    );  
                    }
             ?>
        <table id="customers">
            <thead>
                <tr>    
                                <th>Sr No</th>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Product Name</th>
<!--                                <th>Type 1</th>-->
<!--                                <th>Type 2</th>-->
                                <!--<th>HSN</th>-->
                                <th>Quantity</th>
                                <th> Selling Price</th>
                                <!--<th>Total Value</th>-->
                                <th>Rebate 5%</th>
                                <th>Corporation Discount</th>
                                <th> Taxable Amount </th>
								<th>GST Rate</th>
                                <th>GST Amount</th>
                                <th>CGST Rate</th>
                                <th>CGST Amount</th>
                                <th>SGST RATE</th>
                                <th>SGST Amount</th>

                                <th>IGST RATE</th>
                                <th>IGST RATE</th>

                                <th>Adj +</th>
                                <th>Adj -</th>

                                <th>Amount after GST</th>
                                <th>COD /Shipping </th>
                                <th>Sub total</th>

                                <th>Net Amount</th>
                                <th> Amount Deposited in Bank </th>
                                <th>Type of Deposit</th>
                                <th> GST on Bank Commission  </th>
                                <th> Bank Commission </th>
                                <th>Date of Deposit</th>
                
                </tr>
            </thead>
            
            <tbody> 
                <?php $no = 1; $invoice_quantity = 0; $rate_per_item = 0; $taxable = 0; $gst_amount = 0; $sgst_amount=0; $cgst_amount= 0; $igst_amount = 0; $net_amount = 0; $amount_deposited_in_bank2 = 0; $total_ad_plus = 0; $total_ad_minus = 0; $total_rebate = 0; $total_crop_discount = 0; ?>
                <?php foreach ($arrBank as $result) { ?>
                <tr>
                    <td><?= $result['sr']; ?></td>
                    <td><?= $result['invoice_no']; ?></td>
                    <td><?= date('d-m-Y', strtotime($result['date_of_invoice'])); ?></td>
                    <td><?= $result['asset_name']; ?></td>
<!--                    <td>--><?//= $result['productType']; ?><!--</td>-->
<!--                    <td>--><?//= $result['assetType']; ?><!--</td>-->
                    
                    <td><?php echo $result['invoice_quantity']; $invoice_quantity += $result['invoice_quantity'];  ?></td>
                    <td><?php echo  "Rs. ".number_format($result['rate_per_item'],2); $rate_per_item +=$result['rate_per_item']; ?></td>
                    
                    <td><?= "Rs. ".number_format( $result['discount_1'] ); $total_rebate +=$result['discount_1']; ?></td>
                    <td><?= "Rs. ".number_format( $result['discount_2'] + $result['discount_3'] ); $total_crop_discount += $result['discount_2'] + $result['discount_3']; ?></td>
                    <td><?php echo "Rs. ".number_format($result['taxable'],2); $taxable +=$result['taxable'];  ?></td>
					
					<td><?= $result['gst_rate']; ?> </td>
                    <td><?php echo "Rs. ".number_format($result['gst_amount'],2); $gst_amount +=$result['gst_amount']; ?></td>

                    <td><?= $result['cgst_rate']; ?> </td>
                    <td><?php echo "Rs. ".number_format($result['cgst_amount'],2); $cgst_amount +=$result['cgst_amount']; ?></td>

                    <td><?= $result['sgst_rate']; ?></td>
                    <td><?php echo "Rs. ".number_format($result['sgst_amount'],2); $sgst_amount +=$result['sgst_amount']; ?></td>

                    <td><?= $result['igst_rate']; ?></td>
                    <td><?php echo "Rs. ".number_format($result['igst_amount'],2); $igst_amount += $result['igst_amount']; ?></td>


                    <td><?= $result['adjustment_plus']; $total_ad_plus += $result['adjustment_plus'];     ?> </td>                 
                    <td><?= $result['adjustment_minus']; $total_ad_minus += $result['adjustment_minus']; ?> </td>
            		<td><?php echo "Rs. ".number_format($result['net_amount'],2); $net_amount +=$result['net_amount'];  ?></td>

                    <td><?= $result['shipping_charges']; ?></td>

                    <td><?= "Rs. ".number_format($result['net_amount'],2); ?></td>

                     <td><?= $result['total_amount']; ?></td>

                     <td><?php if($result['amount_deposited_in_bank']){ echo "Rs. ".number_format($result['amount_deposited_in_bank'],2); } $amount_deposited_in_bank2 += $result['amount_deposited_in_bank']; ?></td>
                     <td><?= $result['type_of_deposit']; ?></td>
                     <td><?= $result['gst_on_bank_commission']; ?></td>
                     <td><?= $result['bank_commission']; ?></td>
                     <td><?= $result['date_of_deposit']; ?></td>

                      
                </tr>    
                <?php } ?>                  
            </tbody>
            <tfoot>
                 <tr>
                    <th colspan="4"></th>
                    <!--<th>HSN</th>-->
                    <th><?= $invoice_quantity; ?></th>
                    <th><?= "Rs. ".number_format($rate_per_item,2); ?></th>
                    <!--<th>Total Value</th>-->
                    <th><?= "Rs. ".number_format($total_rebate,2); ?></th>
                    <th><?= "Rs. ".number_format($total_crop_discount,2); ?></th>

                    <th><?= "Rs. ".number_format($taxable,2); ?></th>
                     <th></th>
                     <th><?= "Rs. ".number_format($gst_amount,2); ?></th>
                    <th></th>
                    <th><?= "Rs. ".number_format($cgst_amount,2); ?></th>

                    <th></th>
                    <th><?= "Rs. ".number_format($sgst_amount,2); ?></th>

                    <th></th>
                    <th><?= "Rs. ".number_format($igst_amount,2); ?></th>

                    <th><?= "Rs. ".number_format($total_ad_plus,2); ?></th>
                    <th><?= "Rs. ".number_format($total_ad_minus,2); ?></th>

                    <th><?= "Rs. ".number_format($net_amount,2); ?></th>
                    <th></th>
                    <th><?= "Rs. ".number_format($net_amount,2); ?></th>

                    <th><?= "Rs. ".number_format($all_total,2); ?></th>

                    <th><?= "Rs. ".number_format($amount_deposited_in_bank2,2); ?> </th>
                    <th colspan="5"></th>
                     
                </tr>
            </tfoot>
        </table>                   
    </body>
</html>
        