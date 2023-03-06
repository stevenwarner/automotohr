<?php
$hd = message_header_footer($invoice['company_sid'], $invoice['company_name']);
?>

<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css?v=1.0.1">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/responsive.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/easy-responsive-tabs.css">
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.nicescroll.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.parallax-scroll.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/easyResponsiveTabs.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/functions.js"></script>
<script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />
<script src="<?= base_url() ?>assets/js/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui-datepicker-custom.css">
<script type="text/javascript" src="<?= base_url('/assets/js/jquery.timepicker.js') ?>"></script>
<link href="<?= base_url() ?>assets/css/select2.css" rel="stylesheet" />
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>

<?php echo $hd['header']; ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php echo $invoicehtml; ?>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($payment_status == 'unpaid') { ?>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <form action="<?php echo base_url('invoice_new/process_payment_admin_invoice/' . $invoice['sid']) ?>" method="post" id="form_credit_card_details">
                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoice['company_sid']; ?>" />
                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                    <input type="hidden" id="perform_action" name="perform_action" value="process_credit_card_payment" />
                                    <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php echo $invoice['total_after_discount']; ?>" />

                                    <div id="credit_card_details" class="credit_card_details">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label>Credit Card Number</label>
                                                    <input type="text" name="cc_number" id="cc_number" class="invoice-fields">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label>Credit Card Type</label>
                                                    <div class="hr-select-dropdown">
                                                        <select name="cc_type" id="cc_type" class="invoice-fields">
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
                                                    <label>Expiration Month</label>
                                                    <div class="hr-select-dropdown">
                                                        <select name="cc_expiration_month" id="cc_expiration_month" class="invoice-fields">
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
                                                    <label>Expiration Year</label>
                                                    <div class="hr-select-dropdown">
                                                        <select name="cc_expiration_year" id="cc_expiration_year" class="invoice-fields">
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
                                                    <label>CCV</label>
                                                    <input type="text" name="cc_ccv" id="cc_ccv" class="invoice-fields">
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
                                    </div>
                                    <div class="btn-panel">
                                        <button class="delete-all-btn active-btn" style="background-color: #3554dc;" type="button" onclick="fProcessPayment()">Process Payment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function fProcessPayment() {

            var cc_type = $('#cc_type').val();
            var cc_number = $('#cc_number').val();
            var cc_expiration_month = $('#cc_expiration_month').val();
            var cc_expiration_year = $('#cc_expiration_year').val();
            var cc_ccv = $('#cc_ccv').val();
            let ccv_regex = new RegExp(/^[0-9]{3,4}$/);

            if (cc_number == '') {
                alertify.alert('Please specify credit card number.');
            } else if (cc_type == '') {
                alertify.alert('Please specify credit card type.');
            } else if (cc_expiration_month == '') {
                alertify.alert('Please specify expiration month.');
            } else if (cc_expiration_year == '') {
                alertify.alert('Please specify expiration year.');
            } else if (cc_ccv == '') {
                alertify.alert('Please specify CCV.');
            } else if (cc_ccv != '' && ccv_regex.test(cc_ccv) == false) {
                alertify.alert('Please specify CCV in numbers  not more than  4 digits .');
            } else if (cc_number != '' && cc_type != '' && ValidateCreditCardNumber(cc_number, cc_type) == false) {
                alertify.alert('Please specify a valid card number .');
            } else if (cc_expiration_month != '' && cc_expiration_year != '' && ValidateCreditCardExp(cc_expiration_month, cc_expiration_year) == false) {
                alertify.alert('The expiry date is before today date. Please select a valid expiry date.');
            } else {
                alertify.confirm(
                    'Are You Sure?',
                    'Are you sure want to Process Payment against this invoice?',
                    function() {
                         $('#form_credit_card_details').submit();
                    },
                    function() {}
                )
            }

        }

        $(document).ready(function() {
            $('#prev_saved_cc').on('change', function() {

                var selected_card = $(this).val();
                console.log(selected_card);
                if (selected_card == 0) {
                    $('#credit_card_details').show();
                } else {
                    $('#credit_card_details').hide();
                }
            });
        });


        function ValidateCreditCardNumber(cardno, cardtype) {

            var ccNum = cardno;
            var visaRegEx = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
            var mastercardRegEx = /^(?:5[1-5][0-9]{14})$/;
            var amexpRegEx = /^(?:3[47][0-9]{13})$/;
            var discovRegEx = /^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;
            var isValid = false;

            if (cardtype == 'visa' && visaRegEx.test(ccNum)) {
                isValid = true;
            } else if (cardtype == 'mastercard' && mastercardRegEx.test(ccNum)) {
                isValid = true;
            } else if (cardtype == 'amex' && amexpRegEx.test(ccNum)) {
                isValid = true;
            } else if (cardtype == 'discover' && discovRegEx.test(ccNum)) {
                isValid = true;
            }
            return isValid;

        }

        function ValidateCreditCardExp(exMonth, exYear) {
            var today, someday;
            today = new Date();
            someday = new Date();
            someday.setFullYear(exYear, exMonth, 1);

            if (someday < today) {
                return false;
            }
        }
    </script>
<?php } ?>

<?php echo $hd['footer']; ?>