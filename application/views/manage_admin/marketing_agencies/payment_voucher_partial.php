<div class="">
    <table style="width: 100%; border: thin black solid;">
        <tbody>
            <tr>
                <th colspan="4" style="background: #eeeeee; width: 100%; border: thin black solid; vertical-align: top; padding: 5px; text-align: center;">Payment Voucher # <?php echo $voucher['voucher_number']; ?></th>
            </tr>
            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Dated</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;"><?php echo date('m-d-Y', strtotime($voucher['created'])); ?></td>
            </tr>
            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Marketing Agency</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
                    <div><?php echo $agency['full_name']; ?></div>
                    <div><?php echo $agency['address']; ?></div>
                    <div><?php echo $agency['contact_number']; ?></div>
                </td>
            </tr>
            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Company Account</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
                    <div><?php echo ucwords($company['CompanyName']); ?></div>
                    <div><?php echo ucwords($company['Location_Address']); ?></div>
                    <div><?php echo ucwords($company['PhoneNumber']); ?></div>
                </td>
            </tr>
<!--            <tr>-->
<!--                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Commission Invoice Number</th>-->
<!--                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">--><?php //echo $voucher['commission_invoice_no']; ?><!--</td>-->
<!--            </tr>-->

<!--            <tr>-->
<!--                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Commission Invoice Date</th>-->
<!--                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">--><?php //echo date('m-d-Y', strtotime($invoice['created'])); ?><!--</td>-->
<!--            </tr>-->


            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Invoice Number</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
<!--                    --><?php //if($invoice_origin == 'super_admin') { ?>
                        <?php echo $invoice['invoice_number']; ?>
<!--                    --><?php //} else { ?>
<!--                        --><?php //echo 'MP-' . str_pad($invoice['sid'],6,0, STR_PAD_LEFT) ; ?>
<!--                    --><?php //} ?>
                </td>
            </tr>
            <!--
            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Invoice Date</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
                    <?php /*if($invoice_origin == 'super_admin') { */?>
                        <?php /*echo date('m-d-Y', strtotime($invoice['created'])); */?>
                    <?php /*} else { */?>
                        <?php /*echo date('m-d-Y', strtotime($invoice['date'])); */?>
                    <?php /*} */?>
                </td>
            </tr>
            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Invoice Value</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
                    <?php /*if($invoice_origin == 'super_admin') { */?>
                        <?php /*echo '$' . number_format($invoice['total_after_discount'], 2); */?>
                    <?php /*} else { */?>
                        <?php /*echo '$' . number_format($invoice['total'], 2); */?>
                    <?php /*} */?>
                </td>
            </tr>
            -->

            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Payment Status</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
                    <span style="color: <?php echo $voucher['payment_status'] == 'paid' ? 'green' : 'red'; ?>;" ><?php echo ucwords($voucher['payment_status']); ?></span>
                </td>
            </tr>
            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Payment Date</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
                    <?php echo date('m-d-Y', strtotime($voucher['payment_date'])); ?>
                </td>
            </tr>
            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Amount Paid</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
                    $<?php echo number_format($voucher['paid_amount'], 2); ?>
                </td>
            </tr>

            <tr>
                <th style="background: #eeeeee; width: 30%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">Amount In Words</th>
                <td style="width: 70%; border: thin black solid; vertical-align: top; text-align: left; padding: 5px;">
                    <?php echo ucwords(convert_number_to_words(ceil($voucher['paid_amount']))); ?> Only
                </td>
            </tr>
        </tbody>
    </table>
</div>