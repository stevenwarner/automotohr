<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css?v=1.0.1">
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
    <title>Pay Invoice</title>
</head>

<body>

    <?= $hf['header']; ?>

    <?php if ($invoiceDetails['payment_status'] != 'unpaid') { ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info text-center">
                    <strong>You have Successfully processed this payment! Thank you for your business.</strong>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <?= generate_invoice_html($invoiceDetails['sid']); ?>

        <hr />
        <form action="<?php echo base_url('misc/process_payment_admin_public_invoice/' . $invoiceDetails['sid']) ?>" method="post" id="form_credit_card_details">
            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoiceDetails['company_sid']; ?>" />
            <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoiceDetails['sid']; ?>" />
            <input type="hidden" id="perform_action" name="perform_action" value="process_credit_card_payment" />
            <input type="hidden" id="redirect_url" name="redirect_url" value="pay/invoice/<?= $invoiceDetails['sid']; ?>" />

            <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php echo $invoiceDetails['total_after_discount']; ?>" />
            <div id="credit_card_details" class="credit_card_details">
                <?php if ($this->session->flashdata('message')) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="flash_error_message">
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="field-row">
                            <label>Credit Card Number <strong class="text-danger">*</strong></label>
                            <input type="text" name="cc_number" id="cc_number" class="invoice-fields" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="field-row">
                            <label>Credit Card Type <strong class="text-danger">*</strong></label>
                            <div class="hr-select-dropdown">
                                <select name="cc_type" id="cc_type" class="invoice-fields" required>
                                    <option value="">Please Select Card Type</option>
                                    <option value="visa">Visa</option>
                                    <option value="mastercard">Mastercard</option>
                                    <option value="discover">Discover</option>
                                    <option value="amex">Amex</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="field-row">
                            <label>Expiration Month <strong class="text-danger">*</strong></label>
                            <div class="hr-select-dropdown">
                                <select name="cc_expiration_month" id="cc_expiration_month" class="invoice-fields" required>
                                    <option value="">Please Select</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="field-row">
                            <label>Expiration Year <strong class="text-danger">*</strong></label>
                            <div class="hr-select-dropdown">
                                <select name="cc_expiration_year" id="cc_expiration_year" class="invoice-fields" required>
                                    <option value="">Please Select</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="field-row">
                            <label>CCV <strong class="text-danger">*</strong></label>
                            <input type="text" name="cc_ccv" id="cc_ccv" class="invoice-fields" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="field-row">
                            <input type="checkbox" checked="checked" name="cc_save_for_future" id="cc_save_for_future" value="1">
                            <label for="cc_save_for_future">Save Card for Future Use</label>
                        </div>
                    </div>
                </div>
                <hr>

                <button class="btn btn-primary" type="button" onclick="fProcessPayment()">Process Payment</button>
            </div>
        </form>

    <?php } ?>


    <?= $hf['footer']; ?>

    <script>
        function fProcessPayment() {

            var cc_type = $('#cc_type').val();
            var cc_number = $('#cc_number').val();

            //
            if (!$('#cc_number').val().trim()) {
                return alertify.alert(
                    'Warning!',
                    'Credit card number is required.'
                );
            }

            //
            if (!$('#cc_type').val()) {
                return alertify.alert(
                    'Warning!',
                    'Credit card type is required.'
                );
            }

            //
            if (!$('#cc_expiration_month').val()) {
                return alertify.alert(
                    'Warning!',
                    'Credit card month is required.'
                );
            }

            //
            if (!$('#cc_expiration_year').val()) {
                return alertify.alert(
                    'Warning!',
                    'Credit card year is required.'
                );
            }

            //
            if (!$('#cc_ccv').val().trim()) {
                return alertify.alert(
                    'Warning!',
                    'Credit card CCV is required.'
                );
            }
            //
            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to Process Payment against this invoice?',
                function() {
                    $('#form_credit_card_details').submit();
                },
                function() {

                }
            )
        }
    </script>

</body>

</html>