<?php echo isset($show_header_footer) && $show_header_footer == true ? EMAIL_HEADER : ''; ?>
<div class="email-wrp" style="max-width:1000px; margin:10px auto; clear: both;">
    <!-- Email BODY -->
    <?php if(!empty($company_info)) { ?>
        <table width="100%" style="font-family: Helvetica, Arial, sans-serif; background: #fff; border: 1px solid #e0dfdf; border-collapse: collapse; margin-bottom: 20px;">
            <tbody>
                <tr>
                    <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="left">Company</th>
                    <td style="padding: 8px; border:1px solid #e0dfdf;"><?php echo ucwords(strtolower($company_info['CompanyName'])); ?></td>
                </tr>
                <tr>
                    <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="left">Address</th>
                    <td style="padding: 8px; border:1px solid #e0dfdf;">
                        <?php $state_info = db_get_state_name($company_info['Location_State'])?>
                        <?php echo ucwords(strtolower($company_info['Location_Address'])); ?>
                        <?php echo (!empty($company_info['Location_City']) ? ', ' . ucwords(strtolower($company_info['Location_City'])) : '' ) ; ?>
                        <?php echo (!empty($company_info['Location_ZipCode']) ? ', ' . ucwords(strtolower($company_info['Location_ZipCode'])) : '' ) ; ?>
                        <?php echo (!empty($company_info['Location_State']) ? ', ' . $state_info['state_name'] . ', ' . $state_info['country_name'] : '' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="left">Email</th>
                    <td style="padding: 8px; border:1px solid #e0dfdf;"><?php echo strtolower($company_info['email']); ?></td>
                </tr>
                <tr>
                    <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="left">Phone</th>
                    <td style="padding: 8px; border:1px solid #e0dfdf;"><?php echo $company_info['PhoneNumber']; ?></td>
                </tr>
            </tbody>
        </table>
    <?php } ?>

    <table width="100%" style="font-family: Helvetica, Arial, sans-serif; background: #fff; border: 1px solid #e0dfdf; border-collapse: collapse;">
        <thead>
        <tr>
            <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="center">Invoice Number</th>
            <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="center">Date</th>
            <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="center">Payment Status</th>
            <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="center">Amount</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($invoices as $invoice) {?>
                <tr>
                    <td style="padding: 8px; border:1px solid #e0dfdf;" align="center"><?php echo $invoice['invoice_number']; ?></td>
                    <td style="padding: 8px; border:1px solid #e0dfdf;" align="center"><?php echo convert_date_to_frontend_format($invoice['created']); ?></td>
                    <td style="padding: 8px; border:1px solid #e0dfdf; color: #a94442;" align="center"><?php echo ucwords(strtolower($invoice['payment_status'])); ?></td>
                    <td style="padding: 8px; border:1px solid #e0dfdf;" align="right">$
                        <!--
                        <?php /*if($invoice['is_discounted'] == 1) { */?>
                            <?php /*echo number_format($invoice['total_after_discount'], 2); */?>
                        <?php /*} else { */?>
                            <?php /*echo number_format($invoice['value'], 2); */?>
                        <?php /*} */?>
                        -->
                        <?php echo number_format($invoice['total_after_discount'], 2); ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="left" colspan="3">Total</th>
            <th style="background: #ededed; padding: 8px; border:1px solid #e0dfdf;" align="right">$ <?php echo number_format($grand_total, 2); ?></th>
        </tr>
        </tfoot>
    </table>

    <!-- /Email BODY End -->
</div>

<?php echo isset($show_header_footer) && $show_header_footer == true ? EMAIL_FOOTER : ''; ?>

<?php if(isset($show_header_footer) && $show_header_footer == true) { ?>
    <script>
        $(document).ready(function () {
            window.print();
        });
    </script>
<?php } ?>
