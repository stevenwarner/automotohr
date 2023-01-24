<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recurring Payments Authorization Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/responsive.css">

    <script src="<?php echo base_url('assets') ?>/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/bootstrap.min.js"></script>
</head>
<body>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="credit-card-authorization">
                    <div class="top-logo text-center">
                        <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                    </div>
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>RECURRING PAYMENT AUTHORIZATION FORM</span>
                    <div class="end-user-agreement-wrp recurring-payment-authorization">
                        <div class="recurring-payment-text-area">
                            <p>Schedule your payment to be automatically deducted from your bank account, or charged to your Visa, MasterCard, American Express or Discover Card. Just complete and sign this form to get started! </p>
                            <h2 class="credit-card-form-heading">Recurring Payments Will Make Your Life Easier: </h2>
                            <ul>
                                <li>It's convenient (saving you time and postage) </li>
                                <li>Your payment is always on time (even if you're out of town), eliminating late charges </li>
                            </ul>
                            <h2 class="credit-card-form-heading">Here's How Recurring Payments Work:</h2>
                            <p>You authorize regularly scheduled charges to your checking/savings account or credit card. You will be charged the amount indicated below each billing period. A receipt for each payment will be emailed to you and the charge will appear on your bank statement as an "ACH Debit." You agree that no prior-notification will be provided unless the date or amount changes, in which case you will receive notice from us at least 10 days prior to the payment being collected. </p>
                        </div>
                        <form action="<?php //echo base_url('form_recurring_payments_authorization' . '/' . $verification_key);?>" method="post" enctype="multipart/form-data">
                            <div class="card-fields-row">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <strong>I</strong>,
                                        <div class="form-outer">
                                            <input type="text" name="authorized_person_full_name" id="authorized_person_full_name" class="invoice-fields" value="<?php echo set_value('authorized_person_full_name'); ?>" />
                                            <?php echo form_error('authorized_person_full_name'); ?>
                                        </div>
                                        authorize <strong><?php echo STORE_NAME; ?></strong> to charge my credit card indicated below on the <div class="form-outer">
                                            <select class="invoice-fields" id="payment_day" name="payment_day">
                                                <option value="">Please Select</option>
                                                <?php for($count = 1; $count <= 28; $count++) { ?>
                                                    <option value="<?php echo $count?>"><?php echo $count?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('payment_day'); ?>
                                        </div> of each Month for payment of my
                                    </div>
                                </div>
                            </div>
                            <h2 class="credit-card-form-heading">Credit Card Billing Address:</h2>
                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                            <label>Billing Address</label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                            <input type="text" name="billing_address" id="billing_address" class="invoice-fields" value="<?php echo set_value('billing_address');?>" />
                                            <?php echo form_error('billing_address'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <label>Phone#</label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                            <input type="text" name="phone_number" id="phone_number" class="invoice-fields" value="<?php echo set_value('phone_number');?>" />
                                            <?php echo form_error('phone_number'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><label>City</label></div>
                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                    <input type="text" name="billing_city" id="billing_city" class="invoice-fields" value="<?php echo set_value('billing_city');?>" />
                                                    <?php echo form_error('billing_city'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><label>State</label></div>
                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                    <input type="text" name="billing_state" id="billing_state" class="invoice-fields" value="<?php echo set_value('billing_state');?>" />
                                                    <?php echo form_error('billing_state'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><label>Zip Code</label></div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <input type="text" name="billing_zip_code" id="billing_zip_code" class="invoice-fields" value="<?php echo set_value('billing_zip_code');?>" />
                                                    <?php echo form_error('billing_zip_code'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                            <input type="text" name="email" id="email" class="invoice-fields" value="<?php echo set_value('email');?>" />
                                            <?php echo form_error('email'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="card-boxes">
                                    <h2 class="credit-card-form-heading text-center">Checking/Savings Account</h2>
                                    <div class="card-box-inner">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio" for="bank_account_type_checking">Checking
                                                <input type="radio" value="checking" name="bank_account_type" id="bank_account_type_checking" class="select-domain" <?php echo set_radio('bank_account_type');?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio" for="bank_account_type_savings">Savings
                                                <input type="radio" value="savings" name="bank_account_type" id="bank_account_type_savings" class="select-domain" <?php echo set_radio('bank_account_type');?> >
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <?php echo form_error('bank_account_type'); ?>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Bank Name</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <input type="text" class="invoice-fields" name="bank_name" id="bank_name" value="<?php echo set_value('bank_name'); ?>" />
                                                <?php echo form_error('bank_name'); ?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Account Title</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <input type="text" class="invoice-fields" name="bank_account_title" id="bank_account_title"  value="<?php echo set_value('bank_account_title'); ?>" />
                                                <?php echo form_error('bank_account_title'); ?>
                                            </div>
                                        </div>

                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Account Number</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <input type="text" class="invoice-fields" name="bank_account_number" id="bank_account_number" value="<?php echo set_value('bank_account_number'); ?>" />
                                                <?php echo form_error('bank_account_number'); ?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Bank Routing #</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <input type="text" class="invoice-fields" name="bank_routing_number" id="bank_routing_number" value="<?php echo set_value('bank_routing_number'); ?>" />
                                                <?php echo form_error('bank_routing_number'); ?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Bank State</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <input type="text" class="invoice-fields" name="bank_state" id="bank_state" value="<?php echo set_value('bank_state'); ?>" />
                                                <?php echo form_error('bank_state'); ?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Bank City</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <input type="text" class="invoice-fields" name="bank_city" id="bank_city" value="<?php echo set_value('bank_city'); ?>" />
                                                <?php echo form_error('bank_city'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="card-boxes">
                                    <h2 class="credit-card-form-heading text-center">Credit Card</h2>
                                    <div class="card-box-inner">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">Visa
                                                <input type="radio" value="visa" name="cc_type" id="cc_type_visa" class="select-domain" <?php echo set_radio('cc_type_visa'); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">Mastercard
                                                <input type="radio" value="mastercard" name="cc_type" id="cc_type_mastercard" class="select-domain" <?php echo set_radio('cc_type_visa'); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">Amex
                                                <input type="radio" value="amex" name="cc_type" id="cc_type_amex" class="select-domain" <?php echo set_radio('cc_type_visa'); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">Discover
                                                <input type="radio" value="discover" name="cc_type" id="cc_type_discover" class="select-domain" <?php echo set_radio('cc_type_visa'); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <?php echo form_error('cc_type'); ?>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Cardholder Name</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <input type="text" class="invoice-fields" name="cc_holder_name" id="cc_holder_name" value="<?php echo set_value('cc_holder_name'); ?>" />
                                                <?php echo form_error('cc_holder_name'); ?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Credit Card Number</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <input type="text" class="invoice-fields" name="cc_number" id="cc_number" value="<?php echo set_value('cc_number'); ?>" />
                                                <?php echo form_error('cc_number'); ?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Expiration Month</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <select style="width:100%;" class="invoice-fields" id="cc_expiration_month" name="cc_expiration_month">
                                                    <option value="">Please Select</option>
                                                    <?php for($count = 1; $count <= 12; $count++) { ?>
                                                        <option value="<?php echo $count?>"><?php echo $count?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('cc_expiration_month'); ?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Expiration Year</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <select style="width:100%;" class="invoice-fields" id="cc_expiration_year" name="cc_expiration_year">
                                                    <option value="">Please Select</option>
                                                    <?php for($count = intval(date('Y')); $count <= (intval(date('Y')) + 10); $count++) { ?>
                                                        <option value="<?php echo $count?>"><?php echo $count?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('cc_expiration_year'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-fields-row">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-fields-row">
                                            <div class="col-lg-2">
                                                <label class="signature-label">SIGNATURE</label>
                                            </div>
                                            <div class="col-lg-10">
                                                <input type="text" class="signature-field" name="signature" id="signature" value="<?php echo set_value('signature'); ?>" />
                                                <?php echo form_error('signature'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="card-fields-row">
                                            <div class="col-lg-2">
                                                <label>DATE</label>
                                            </div>
                                            <div class="col-lg-10">
                                                <input type="text" class="invoice-fields" name="date_of_authorization" id="date_of_authorization" value="<?php echo set_value('date_of_authorization', date('m/d/Y'));?>"/>
                                                <?php echo form_error('date_of_authorization'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-fields-row">
                                <p>I understand that this authorization will remain in effect until I cancel it in writing, and I agree to notify <strong><?php echo STORE_NAME; ?></strong> in writing of any changes in my account information or termination of this authorization at least 15 days prior to the next billing date. If the above noted payment dates fall on a weekend or holiday, I understand that the payments may be executed on the next business day. For ACH debits to my checking/savings account, I understand that because these are electronic transactions, these funds may be withdrawn from my account as soon as the above noted periodic transaction dates. In the case of an ACH Transaction being rejected for Non Sufficient Funds (NSF) I understand that </p>
                            </div>
                            <div style="border-top:1px solid #ddd; padding-top:8px;" class="card-fields-row">
                                <p>must comply with the previous U.S law. I certify that I am an authorized user of this credit card/bank account and will not dispute these scheduled transaction with my bank or credit card company; so long as the transactions coresspond to the terms indicated in this authorization form. </p>
                            </div>
                            <div class="card-fields-row">
                                <div class="col-lg-6 col-lg-offset-3">
                                    <button type="submit" class="page-heading">I Authorize <?php echo STORE_NAME; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>