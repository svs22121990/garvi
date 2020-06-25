<!DOCTYPE html>
<html>

<head>
    <title><?= $invoice->invoice_no; ?> - Invoice</title>
    <style type="text/css">
         @page {
            size: A4;
            /* margin: 0; */
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
            }
        }

        html,
        body {
            width: 210mm;
            height: 297mm;
        }

        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid black;
            padding: 2px;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 2px;
            padding-bottom: 2px;
            text-align: left;
            background-color: #ddd;
            color: black;
        }

        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        .no-border {
            border: none !important;
        }

        .title-center {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        .logo-image{
          /* height: 30%; */
          width: 80%;
          /* position: relative; */
          text-align: center;
        }

        .img-responsive {
            width: 100%;
        }
        .logo-image{
          height: 30%;
          width: 80%;
          /* position: relative; */
          text-align: center;
        }
        .container {
            border-bottom: 1px solid #ccc;
            margin-bottom: 3cm;
        }
        .footer{
            background-color: #fff;
            width: 100%;
            position: fixed;
            text-align: center;
            bottom: 0;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="title-center"><img class="img-responsive logo-image" src="http://app.garvigurjari.in/images/gg_logo2.jpg"></div>
    <!--<div class="title-center"><?= $_SESSION[SESSION_NAME]['name']; ?></div>-->
    <div class="title-center"><?= $_SESSION[SESSION_NAME]['address'] ?></div>
    <div style="text-align: center;">GST Number: <?= $_SESSION[SESSION_NAME]['gst_number'] ?></div>

    <div style="width: 50%;float: left;">Mobile:<?= $_SESSION[SESSION_NAME]['mobile']; ?></div>
    <div style="width: 50%;text-align: left;">Email:<?= $_SESSION[SESSION_NAME]['email']; ?></div>

    <h4 style="text-align: center;"><u>Invoice</u></h4>
    <div style="width: 60%;float: left;">Invoice no.: <?= $invoice->invoice_no; ?></div>
    <div style="width: 40%;float: left;">Bill Date: <?= date("d-m-Y", strtotime($invoice->date_of_invoice)); ?></div>

    <div style="width: 60%;float: left;">Name: <?= $invoice->receiver_bill_name; ?></div>
    <div style="width: 40%;float: left;">Payment Mode :  <?= $paymentMode->type; ?></div>

    <div style="width: 60%;float: left;">Address: <?= $invoice->receiver_address; ?></div>
    <div style="width: 40%;float: left;">Sales Type : <?= $salesType->label; ?></div>


    <div style="width: 60%;float: left;">State: <?= $receiver_state->state_name ?></div>
    <div style="width: 40%;float: left;">GSTIN : <?= $invoice->receiver_gst_number; ?></div>


    <br>
    <!--<table style="width:100%">
                <tr>
                    <th>Details of Receiver (Billed to)</th>
                    <th></th>
                    <th>Details of Consignee (Shipped to)</th>
                </tr>
                <tr>
                    <td>Name: <?= $invoice->receiver_bill_name; ?></td>
                    <td></td>
                    <td>Name: <?= $invoice->consignee_name ?></td>
                </tr>
                <tr>
                    <td>Address: <?= $invoice->receiver_address ?></td>
                    <td></td>
                    <td>Address: <?= $invoice->consignee_address ?></td>
                </tr>
                <tr>
                    <td>State: <?= $invoice->receiver_state ?></td>
                    <td></td>
                    <td>State: <?= $invoice->consignee_state ?></td>
                </tr>
                <tr>
                    <td>State Code: <?= $invoice->receiver_state_code ?></td>
                    <td></td>
                    <td>State Code: <?= $invoice->consignee_state_code ?></td>
                </tr>
                <tr>
                    <td>GSTIN/Unique ID: <?= $invoice->receiver_gst_number ?></td>
                    <td></td>
                    <td>GSTIN/Unique ID: <?= $invoice->consignee_gst_nunber ?></td>
                </tr>
            </table><br>-->
    <table id="customers">
        <thead>
            <tr>
                <th rowspan="2">Sr. No.</th>
                <th rowspan="2">Product Name</th>
                <th rowspan="2">HSN Code</th>
                <th rowspan="2">Quantity</th>
                <!-- <th rowspan="2">Unit</th> -->
                <th rowspan="2">Price</th>
                <th rowspan="2">Total</th>
                <th rowspan="2" colspan="2">&nbsp;&nbsp;&nbsp;Discount&nbsp;&nbsp;&nbsp;</th>
                <!-- <th rowspan="2">Discount amount</th> -->

                <th rowspan="2">Taxable Amount</th>
                <th colspan="2" style="text-align: center">GST</th>
                <th rowspan="2">Net Amount</th>
            </tr>
        </thead>
        <!-- <thead>
            <tr>

                <th >Rate</th>

                <th style="padding-left: 20px;padding-right: 20px;">Amount</th>

            </tr>
        </thead> -->
        <tbody>

            <?php $i = 1;
            $quantity = 0;
            $allCGST = 0;
            $allSGST = 0;
            $allIGST = 0;
            $allNetAmt = 0;
            $alladjustment_plus = 0;
            $alladjustment_minus = 0;
            $rate_per_item = 0;
            $total = 0;
            $discount_amount = 0;
            $taxable = 0;
            foreach ($invoice_details as $detail) {
                $product = $this->Crud_model->GetData("assets", "", "id='" . $detail->product_id . "'", "", "", "", "1");
                ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $product->asset_name; ?></td>
                <td><?= $detail->hsn_code; ?></td>
                <td><?php echo $detail->quantity;
                    $quantity += $detail->quantity; ?></td>
                <!-- <td><?= $detail->unit; ?></td> -->
                <td><?php echo "Rs " . number_format($detail->rate_per_item, 2);
                    $rate_per_item += $detail->rate_per_item;  ?></td>
                <td><?php echo "Rs " . number_format($detail->total, 2);
                    $total += $detail->total; ?></td>

                <td style="border-right: 0;">
                    <?= $detail->discount_1 ?>%-<?= $detail->discount_1 * $detail->total / 100 ?>, <br>
                    <?= $detail->discount_2 ?>%-<?= $detail->discount_2 * $detail->total / 100 ?>, <br>
                    <?= $detail->discount_3 ?>%-<?= $detail->discount_3 * $detail->total / 100 ?><br>
                </td>
                <td style="border-left: 0;"><?php echo "Rs " . number_format($detail->discount_amount, 2);
                    $discount_amount += $detail->discount_amount; ?></td>

                <td><?php echo "Rs " . number_format($detail->taxable, 2);
                    $taxable += $detail->taxable; ?></td>

                <td style="border-right: 0;">
                    CGST <?= $detail->cgst_rate ?>%<br>
                    SGST <?= $detail->sgst_rate ?>%<br>
                    IGST <?= $detail->igst_rate ?>%
                </td>

                <td style="border-left: 0;">
                    <span>Rs. <?= $detail->cgst_amount ?><br>
                        <?php $allCGST += $detail->cgst_amount; ?>
                        Rs. <?= $detail->sgst_amount ?><br>
                        <?php $allSGST += $detail->sgst_amount; ?>
                        Rs. <?= $detail->igst_amount ?>
                        <?php $allIGST += $detail->igst_amount; ?>
                </td>
                <!--<td><?= $detail->sgst_rate ?></td>
                        <td>Rs. <?= $detail->sgst_amount ?></td>
                        <td><?= $detail->igst_rate ?></td>
                        <td>Rs. <?= $detail->igst_amount ?></td>-->

                <td><?= "Rs " . number_format($detail->net_amount, 2); ?></td>
            </tr>

            <?php $allNetAmt += $detail->net_amount;
            $alladjustment_plus += $detail->adjustment_plus;
            $alladjustment_minus += $detail->adjustment_minus;
        } ?>
        <tfoot>
            <tr>
                <th colspan="3" rowspan="2"></th>
                <th rowspan="2"><?php echo $quantity; ?></th>
                <th rowspan="2"><?php echo "Rs " . number_format($rate_per_item, 2); ?></th>
                <th rowspan="2"><?php echo "Rs " . number_format($total, 2); ?></th>
                <th rowspan="2" colspan="2">
                    <?php echo "Rs " . number_format($discount_amount, 2); ?>
                </th>
                <th rowspan="2"><?php echo "Rs " . number_format($taxable, 2); ?></th>
                <th rowspan="2">
                    CGST:<?= $allCGST ?>
                    <br>
                    SGST:<?= $allSGST ?>
                    <br>
                    IGST:<?= $allIGST ?>
                </th>
                <?php
                $rprice = (round($allNetAmt, 0));
                $adj = $rprice - $allNetAmt;
                ?>
                <th>
                    ADJ+: <?= $alladjustment_plus ?><br>
                    ADJ-: <?= $alladjustment_minus ?>
                </th>
                <th><?= "Rs " . number_format($allNetAmt, 2); ?></th>
            </tr>

            <tr>

                <th colspan="2">Net: <?= "Rs " . number_format($rprice, 2); ?> </th>
            </tr>
        </tfoot>


        </tbody>
    </table>
    <p>All textile items to be dry cleaned only.<br>
        Goods sold once will not be taken back or exchanged.<br>
        Subject to Gandhinagar jurisdiction. E. & O.E.

    </p>

</div>
    <div class="footer">

        <div class="title-center"><img class="img-responsive logo-image" src="http://app.garvigurjari.in/images/logo.jpg" style="max-width: 500px;"></div>
        <div class="title-center">
            Handloom Technology Institute Building,
            Nr. Mahatma Mandir, <br>Sector 13,
            Gandhinagar 382 016, Gujarat, India.<br>
            Phone No +91-79-23247033, +91-79-23241712. <br>
            E-mail - contact@gurjari.co.in<br>
            CIN : U22219GJ1973SGC002360</div>
    </div>

    <script>
        window.onload = function(){
            window.onbeforeprint = console.log('beforePrint');
            window.onafterprint = () => window.close();
            window.print();
        };
    </script>
</body>

</html>
