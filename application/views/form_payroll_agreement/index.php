<?php $this->load->view('main/static_header'); ?>

<body>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php
                    $readonly = '';
                    if ($company_document['status'] == 'signed') {
                        $readonly = ' readonly="readonly" ';
                    }
                    ?>
                    <div class="end-user-wrp-outer">
                        <div class="top-logo text-center">
                            <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                        </div>
                        <!-- page print -->
                        <div class="top-logo" id="print_div">
                            <a href="javascript:;" class="btn btn-success affiliate_end_user_agreement_color_btn" onclick="print_page('.container');">
                                <i class="fa fa-print" aria-hidden="true"></i> Print or Save
                            </a>
                        </div>

                        <!-- page print -->
                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">PAYROLL AGREEMENT</span>
                        <div class="end-user-agreement-wrp">
                            <form action="<?php echo base_url('form_payroll_agreement' . '/' . $verification_key) . ($is_pre_fill == 1 ? '/pre_fill' : ''); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_document['company_sid']; ?>" />
                                <input type="hidden" id="is_pre_fill" name="is_pre_fill" value="<?php echo $is_pre_fill; ?>" />


                                <div> <?php $this->load->view('payroll/partials/form_payroll_agreement'); ?></div>

                                <div style="font-size:18px;">End User License Agreement (this “Agreement”) by and between AUTOMOTOSOCIAL, LLC (“COMPANY”) and <div class="form-outer company-name" style="max-width:700px;"><input type="text" class="invoice-fields" name="the_entity" id="the_entity" value="<?php echo set_value('the_entity', $company_document['the_entity']); ?>" <?php echo $readonly; ?> /> <?php echo form_error('the_entity'); ?></div>, an entity located at <div class="form-outer" style="max-width: 700px !important; display: inline-block;"><input style="width: 700px !important; max-width: 100% !important;" type="text" class="invoice-fields" name="the_client" id="the_client" value="<?php echo set_value('the_client', $company_document['the_client']); ?>" <?php echo $readonly; ?> /> <?php echo form_error('the_client'); ?></div> (the “CLIENT”). COMPANY and CLIENT are sometimes referred to herein collectively as the “parties” or individually as a “party.”</div>
                                <br>
                                <div> An amount equal to $ <div class="form-outer"><input type="text" class="invoice-fields" name="development_fee" id="development_fee" value="<?php echo set_value('development_fee', $company_document['development_fee']); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> /> <?php echo form_error('development_fee'); ?></div> is due and payable at the time of this Agreement toward the setup, development, and deployment of the SOFTWARE by COMPANY to CLIENT; and</div>
                                <br>
                                <div class="form-col-100" <?php if ($is_pre_fill == 0) {
                                                                echo 'style="visibility:hidden;"';
                                                            } ?>>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label class="control control--radio">
                                                Monthly Subscription
                                                <input class="static-class" type="radio" name="payment_method" value="monthly_subscription" checked="checked">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="control control--radio">
                                                Trial Period
                                                <input class="static-class" type="radio" name="payment_method" value="trial_period" <?php if ((isset($company_document['payment_method']) && $company_document['payment_method'] == 'trial_period') || $company_document['is_trial_period'] == '1') {
                                                                                                                                        echo 'checked="checked"';
                                                                                                                                    } ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="monthly-subscription">
                                    <?php echo form_error('monthly_fee'); ?>
                                    <?php echo form_error('number_of_rooftops_locations'); ?>
                                    <?php echo form_error('number_of_employees'); ?>
                                    A monthly fee of <b>$</b>
                                    <div class="form-outer">
                                        <input type="text" class="invoice-fields" name="monthly_fee" id="monthly_fee" value="<?php if (isset($company_document['monthly_fee']) && $company_document['is_trial_period'] == '0') {
                                                                                                                                    echo set_value('monthly_fee', $company_document['monthly_fee']);
                                                                                                                                } ?>" <?php echo $readonly; ?> <?php if ($is_pre_fill == 0) {
                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                } ?> />
                                        <?php if ($is_pre_fill == 0) { ?>
                                            <input type="hidden" name="monthly_fee" id="monthly_fee" value="<?php if (isset($company_document['monthly_fee']) && $company_document['is_trial_period'] == '0') {
                                                                                                                echo set_value('monthly_fee', $company_document['monthly_fee']);
                                                                                                            } ?>" />
                                        <?php } ?>
                                    </div>
                                    for <div class="form-outer"><input type="text" class="invoice-fields" name="number_of_rooftops_locations" id="number_of_rooftops_locations" value="<?php if (isset($company_document['number_of_rooftops_locations']) && $company_document['is_trial_period'] == '0') {
                                                                                                                                                                                            echo set_value('number_of_rooftops_locations', $company_document['number_of_rooftops_locations']);
                                                                                                                                                                                        } ?>" <?php echo $readonly; ?> <?php if ($is_pre_fill == 0) {
                                                                                                                                                                                                                            echo 'disabled';
                                                                                                                                                                                                                        } ?> /></div> Rooftop Location(s) and <div class="form-outer"><input type="text" class="invoice-fields" name="number_of_employees" id="number_of_employees" value="<?php if (isset($company_document['no_of_employees']) && $company_document['is_trial_period'] == 0) {
                                                                                                                                                                                                                                                                                                                                                                                                echo set_value('no_of_employees', $company_document['no_of_employees']);
                                                                                                                                                                                                                                                                                                                                                                                            } ?>" <?php echo $readonly; ?> <?php if ($is_pre_fill == 0) {
                                                                                                                                                                                                                                                                                                                                                                                                                                echo 'disabled';
                                                                                                                                                                                                                                                                                                                                                                                                                            } ?> /></div> Number of employee(s). The monthly fees contracted in this Agreement do not include tax. Additional taxes may be collected dependent on the CLIENT’s local jurisdiction tax laws.
                                    <p>
                                        Monthly fees include a multi-user, single-server license and unlimited telephone technical support, which will commence once any installation contracted on the Agreement is initiated. Monthly fees may be prorated based on the date services are initiated. CLIENT shall pay the monthly license, maintenance, and support fee on the first business day of each month, after the initiation of the SOFTWARE. SOFTWARE services by the COMPANY are subject to termination if any COMPANY invoice is more than 15 days past due. Written email notification will be given to the CLIENT prior to this termination.
                                    </p>
                                </div>

                                <div class="trial-period">
                                    <?php echo form_error('trial_limit'); ?>
                                    <?php echo form_error('trial_fee'); ?>
                                    <?php echo form_error('recurring_payment_day'); ?>
                                    <?php echo form_error('number_of_rooftops_locations_trial'); ?>
                                    <?php echo form_error('number_of_employees_trial'); ?>
                                    Trial Period and Requirements to Convert to a full Subscription License:<br><br>
                                    The Trial Period for the Trial Services will be for
                                    <?php if ($is_pre_fill == 0) { ?>
                                        <?php echo $company_document['trial_limit']; ?>
                                    <?php } else { ?>
                                        <input type="number" name="trial_limit" id="trial_limit" min="0" value="<?php echo $company_document['trial_limit']; ?>" />
                                    <?php } ?>
                                    days from the Trial Service Activation Date, unless: a) such Trial Period is for a longer term as specified by <?php echo STORE_NAME; ?> / AutomotoSocial LLC; or such Trial Period is extended by mutual Agreement of the parties. Customer acknowledges and agrees that, at the end of the Trial Period, Customer’s access to the Trial Services will be AUTOMATICALLY converted, with or without notice, to license the Services on a paid subscription basis at a rate of <b>$</b>
                                    <br>
                                    <div class="form-outer">
                                        <input type="text" class="invoice-fields" name="trial_fee" id="trial_fee" value="<?php if (isset($company_document['monthly_fee']) && $company_document['is_trial_period'] == '1') {
                                                                                                                                echo set_value('trial_fee', $company_document['monthly_fee']);
                                                                                                                            } ?>" <?php if ($is_pre_fill == 0) {
                                                                                                                                        echo 'disabled';
                                                                                                                                    } ?> />
                                        <?php if ($is_pre_fill == 0) { ?>
                                            <input type="hidden" name="trial_fee" id="trial_fee" value="<?php if (isset($company_document['monthly_fee']) && $company_document['is_trial_period'] == '1') {
                                                                                                            echo set_value('trial_fee', $company_document['monthly_fee']);
                                                                                                        } ?>" />
                                        <?php } ?>
                                    </div>

                                    a month billed on the <br>
                                    <div class="form-outer">
                                        <select name="recurring_payment_day" id="recurring_payment_day" class="invoice-fields" <?php if ($is_pre_fill == 0) {
                                                                                                                                    echo 'disabled';
                                                                                                                                } ?>>
                                            <?php for ($i = 1; $i < 29; $i++) {
                                                echo '<option value="' . $i . '"';
                                                if (isset($company_document['recurring_payment_day']) && $company_document['recurring_payment_day'] == $i) {
                                                    echo 'selected';
                                                }
                                                echo '>' . $i . '</option>';
                                            } ?>
                                        </select>
                                        <?php if ($is_pre_fill == 0) { ?>
                                            <input type="hidden" name="recurring_payment_day" id="recurring_payment_day" value="<?php echo $company_document['recurring_payment_day']; ?>" />
                                        <?php } ?>
                                    </div>
                                    day of the month for <br>
                                    <div class="form-outer"><input type="text" class="invoice-fields" name="number_of_rooftops_locations_trial" id="number_of_rooftops_locations" value="<?php if (isset($company_document['number_of_rooftops_locations']) && $company_document['is_trial_period'] == 1) {
                                                                                                                                                                                                echo set_value('number_of_rooftops_locations', $company_document['number_of_rooftops_locations']);
                                                                                                                                                                                            } ?>" <?php echo $readonly; ?> <?php if ($is_pre_fill == 0) {
                                                                                                                                                                                                                                echo 'disabled';
                                                                                                                                                                                                                            } ?> /></div> Rooftop Location(s) <br>

                                    <div class="form-outer"><input type="text" class="invoice-fields" name="number_of_employees_trial" id="number_of_employees" value="<?php if (isset($company_document['no_of_employees']) && $company_document['is_trial_period'] == 1) {
                                                                                                                                                                            echo set_value('no_of_employees', $company_document['no_of_employees']);
                                                                                                                                                                        } ?>" <?php echo $readonly; ?> <?php if ($is_pre_fill == 0) {
                                                                                                                                                                                                            echo 'disabled';
                                                                                                                                                                                                        } ?> /></div> Number of employee(s). The monthly fees contracted in this Agreement do not include tax. Additional taxes may be collected dependent on the CLIENT’s local jurisdiction tax laws.<p>
                                        Customer must contact <?php echo STORE_NAME; ?> / AutomotoSocial LLC at least fifteen (15) business days prior to the end of the Trial Period if Customer wishes to cancel the Services beyond the Trial Period.
                                    </p>
                                </div>


                                <br>

                                <div class="end-user-form-wrp">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">CONTRACT INFORMATION</span>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="recurring-payment-text-area col-lg-12">
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-lg-6 col-md-6 col-xs-6 col-sm-12">

                                                            Contract Term <div class="form-outer">
                                                                <select class="invoice-fields" id="contract_term" name="contract_term">
                                                                    <option value="">Please Select</option>

                                                                    <option value="Year">Year</option>
                                                                    <option value="Month">Month</option>

                                                                </select>
                                                                <?php echo form_error('contract_term'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-lg-6 col-md-6 col-xs-6 col-sm-12">

                                                            Number Of <div class="form-outer">
                                                                <input type="text" name="term_number_of" id="term_number_of" value="<?php echo set_value('billing_email_address', $company_document['term_number_of']); ?>" class="invoice-fields" />
                                                                <?php echo form_error('term_number_of'); ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <br>
                                <div class="end-user-form-wrp" style="margin-top: 30px;">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">CREDIT CARD AUTHORIZATION</span>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="recurring-payment-text-area col-lg-2">
                                                    <p>Schedule your payment to be automatically deducted from your bank account, or charged to your Visa, MasterCard, American Express or Discover Card. Just complete and sign this form to get started! </p>
                                                    <span class="underline-text">Recurring Payments Will Make Your Life Easier: </span>
                                                    <ul>
                                                        <li>It's convenient (saving you time and postage) </li>
                                                        <li>Your payment is always on time (even if you're out of town), eliminating late charges </li>
                                                    </ul>
                                                    <span class="underline-text">Here's How Recurring Payments Work:</span>
                                                    <p>You authorize regularly scheduled charges to your checking/savings account or credit card. You will be charged the amount indicated below each billing period. A receipt for each payment will be emailed to you and the charge will appear on your credit card/bank statement as a Credit Card Charge or ACH debit. You agree that no prior-notification will be provided unless the date or amount changes, in which case you will receive notice from us at least 10 days prior to the payment being collected. </p>
                                                </div>
                                                <?php $pre_fill_flag = '';

                                                if ($is_pre_fill == 1) {
                                                    $pre_fill_flag = '/pre_fill';
                                                } else {
                                                    $pre_fill_flag = '';
                                                } ?>
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_document['company_sid']; ?>" />
                                                <input type="hidden" id="is_pre_fill" name="is_pre_fill" value="<?php echo $is_pre_fill; ?>" />
                                                <input type="hidden" id="action" name="action" value="upload_file" />

                                                <div class="recurring-payment-text-area col-lg-12">

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <strong>I</strong>,
                                                            <div class="form-outer">
                                                                <input type="text" placeholder="Full Name" name="authorized_person_full_name" id="authorized_person_full_name" class="invoice-fields" value="<?php echo set_value('authorized_person_full_name', $company_document['authorized_person_full_name']); ?>" <?php echo $readonly; ?> /> <?php echo form_error('authorized_person_full_name'); ?>
                                                            </div>
                                                            authorize <strong>AutomotoSocial LLC/<?php echo STORE_NAME; ?></strong> to charge my credit card indicated below for $ <div class="form-outer">
                                                                <input placeholder="Recurring Amount" type="text" name="recurring_amount" id="recurring_amount" class="invoice-fields" value="<?php echo set_value('recurring_amount', $company_document['recurring_amount']); ?>" <?php echo $readonly; ?> /><?php echo form_error('recurring_amount'); ?>
                                                            </div> on the <div class="form-outer">
                                                                <select <?php echo ($company_document['status'] == 'signed' ? 'disabled="disabled"' : ''); ?> class="invoice-fields" id="day_of_payment" name="day_of_payment" <?php echo $readonly; ?>>
                                                                    <option value="">Please Select</option>
                                                                    <?php for ($count = 1; $count <= 28; $count++) { ?>
                                                                        <?php $is_default_day_of_payment = false;
                                                                        if (intval($company_document['day_of_payment']) == $count) {
                                                                            $is_default_day_of_payment = true;
                                                                        } else {
                                                                            $is_default_day_of_payment = false;
                                                                        } ?>
                                                                        <option <?php echo set_select('day_of_payment', $count, $is_default_day_of_payment); ?> value="<?php echo $count ?>"><?php echo $count ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <?php echo form_error('day_of_payment'); ?>
                                                            </div>day of each Month for payment of my account.
                                                        </div>
                                                    </div>
                                                </div>

                                                <span class="credit-card-form-heading underline-text col-lg-12" style="font-size: 14px;margin-top: 10px;">Check only one:<br></span>
                                                <?php $is_default_self = false;
                                                $is_default_company = false;

                                                if ($company_document['authorization_on_behalf_of'] == 'self') {
                                                    $is_default_self = true;
                                                }

                                                if ($company_document['authorization_on_behalf_of'] == 'company') {
                                                    $is_default_company = true;
                                                } ?>
                                                <div>
                                                    <div class="recurring-payment-text-area col-lg-12" style="margin-top: 10px;">

                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <label class="control control--radio">As the Individual cardholder, I hereby authorize this card to be used for the deposit required.
                                                                    <input type="radio" name="authorization_on_behalf_of" id="authorization_on_behalf_of_self" value="self" <?php echo set_checkbox('authorization_on_behalf_of', 'self', $is_default_self); ?> <?php echo $readonly; ?> />
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="recurring-payment-text-area col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <label class="control control--radio">As the company representative, I hereby authorize this card to be used for the deposit required.
                                                                    <input type="radio" name="authorization_on_behalf_of" id="authorization_on_behalf_of_company" value="company" <?php echo set_checkbox('authorization_on_behalf_of', 'company', $is_default_company); ?> <?php echo $readonly; ?> />
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('authorization_on_behalf_of'); ?>
                                                </div>

                                                <span class="credit-card-form-heading col-lg-12" style="font-size: 14px; margin-top:10px;">Credit Card Billing Address:</span>
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                        <div class="card-fields-row">
                                                            <div class="recurring-payment-text-area col-lg-12 row">
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                                    <label>Billing Address</label>
                                                                </div>
                                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                                                    <input type="text" name="billing_address" id="billing_address" class="invoice-fields" value="<?php echo set_value('billing_address', $company_document['billing_address']); ?>" <?php echo $readonly; ?> />
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
                                                                    <input type="text" name="billing_phone_number" id="billing_phone_number" class="invoice-fields" value="<?php echo set_value('billing_phone_number', $company_document['billing_phone_number']); ?>" <?php echo $readonly; ?> />
                                                                    <?php echo form_error('billing_phone_number'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                                        <div class="card-fields-row">
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <div class="recurring-payment-text-area col-lg-12 row">
                                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><label>City</label></div>
                                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                            <input type="text" name="billing_city" id="billing_city" class="invoice-fields" value="<?php echo set_value('billing_city', $company_document['billing_city']); ?>" <?php echo $readonly; ?> />
                                                                            <?php echo form_error('billing_city'); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><label>State</label></div>
                                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                            <input type="text" name="billing_state" id="billing_state" class="invoice-fields" value="<?php echo set_value('billing_state', $company_document['billing_state']); ?>" <?php echo $readonly; ?> />
                                                                            <?php echo form_error('billing_state'); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><label>Zip Code</label></div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                            <input type="text" name="billing_zip_code" id="billing_zip_code" class="invoice-fields" value="<?php echo set_value('billing_zip_code', $company_document['billing_zip_code']); ?>" <?php echo $readonly; ?> />
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
                                                                    <input type="text" name="billing_email_address" id="billing_email_address" class="invoice-fields" value="<?php echo set_value('billing_email_address', $company_document['billing_email_address']); ?>" <?php echo $readonly; ?> />
                                                                    <?php echo form_error('billing_email_address'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <?php $is_default_visa = false;
                                                        $is_default_mastercard = false;
                                                        $is_default_amex = false;
                                                        $is_default_discovery = false;

                                                        if ($company_document['cc_type'] == 'visa') {
                                                            $is_default_visa = true;
                                                        }

                                                        if ($company_document['cc_type'] == 'mastercard') {
                                                            $is_default_mastercard = true;
                                                        }

                                                        if ($company_document['cc_type'] == 'amex') {
                                                            $is_default_amex = true;
                                                        }

                                                        if ($company_document['cc_type'] == 'discover') {
                                                            $is_default_discovery = true;
                                                        } ?>
                                                        <?php if ($dont_show_it != 'view') { ?>

                                                            <div class="card-boxes recurring-payment-text-area col-lg-12">
                                                                <span class="credit-card-form-heading" style="font-size: 14px;">Credit Card Details</span>
                                                                <div class="card-box-inner">
                                                                    <br />
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <div class="card-fields-row">
                                                                            <label class="control control--radio">Visa
                                                                                <input type="radio" value="visa" name="cc_type" id="cc_type_visa" class="select-domain" <?php echo set_radio('cc_type', 'visa', $is_default_visa); ?> <?php echo $readonly; ?> />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <div class="card-fields-row">
                                                                            <label class="control control--radio">Mastercard
                                                                                <input type="radio" value="mastercard" name="cc_type" id="cc_type_mastercard" class="select-domain" <?php echo set_radio('cc_type', 'mastercard', $is_default_mastercard); ?> <?php echo $readonly; ?> />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <div class="card-fields-row">
                                                                            <label class="control control--radio">Amex
                                                                                <input type="radio" value="amex" name="cc_type" id="cc_type_amex" class="select-domain" <?php echo set_radio('cc_type', 'amex', $is_default_amex); ?> <?php echo $readonly; ?> />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <div class="card-fields-row">
                                                                            <label class="control control--radio">Discover
                                                                                <input type="radio" value="discover" name="cc_type" id="cc_type_discover" class="select-domain" <?php echo set_radio('cc_type', 'discover', $is_default_discovery); ?> <?php echo $readonly; ?> />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <br /><br /><br />
                                                                    <?php echo form_error('cc_type'); ?>
                                                                    <div class="card-fields-row">
                                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                            <label>Cardholder Name</label>
                                                                        </div>
                                                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                            <input type="text" class="invoice-fields" name="cc_holder_name" id="cc_holder_name" value="<?php echo set_value('billing_email_address', $company_document['cc_holder_name']); ?>" />
                                                                            <?php echo form_error('cc_holder_name'); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-fields-row">
                                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                            <label>Credit Card Number</label>
                                                                        </div>
                                                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                            <input type="text" class="invoice-fields" name="cc_number" id="cc_number" value="<?php echo set_value('billing_email_address', $company_document['cc_number']); ?>" />
                                                                            <?php echo form_error('cc_number'); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-fields-row">
                                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                            <label>Expiration Month</label>
                                                                        </div>
                                                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                            <select <?php echo ($company_document['status'] == 'signed' ? 'disabled="disabled"' : ''); ?> style="width:100%;" class="invoice-fields" id="cc_expiration_month" name="cc_expiration_month">
                                                                                <option value="">Please Select</option>
                                                                                <?php for ($count = 1; $count <= 12; $count++) {
                                                                                    $is_default_exp_month = false;

                                                                                    if (intval($company_document['cc_expiration_month']) == $count) {
                                                                                        $is_default_exp_month = true;
                                                                                    } else {
                                                                                        $is_default_exp_month = false;
                                                                                    } ?>
                                                                                    <option <?php echo set_select('cc_expiration_month', $count, $is_default_exp_month) ?> value="<?php echo $count ?>"><?php echo str_pad($count, 2, "0", STR_PAD_LEFT); ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <?php echo form_error('cc_expiration_month'); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-fields-row">
                                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                            <label>Expiration Year</label>
                                                                        </div>
                                                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                            <select <?php echo ($company_document['status'] == 'signed' ? 'disabled="disabled"' : ''); ?> style="width:100%;" class="invoice-fields" id="cc_expiration_year" name="cc_expiration_year">
                                                                                <option value="">Please Select</option>
                                                                                <?php for ($count = '2016'; $count <= (intval(date('Y')) + 10); $count++) {
                                                                                    $is_default_exp_year = false;

                                                                                    if (intval($company_document['cc_expiration_year']) == $count) {
                                                                                        $is_default_exp_year = true;
                                                                                    } else {
                                                                                        $is_default_exp_year = false;
                                                                                    } ?>
                                                                                    <option <?php echo set_select('cc_expiration_year', $count, $is_default_exp_year) ?> value="<?php echo $count ?>"><?php echo $count ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <?php echo form_error('cc_expiration_year'); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>



                                <div class="end-user-form-wrp" style="margin-top: 30px;">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">COMPANY </span>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>By:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_by = $company_document['company_by'];
                                                    if ($company_by == '') {
                                                        $company_by = STORE_NAME;
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_by" name="company_by" value="<?php echo set_value('company_by', $company_by); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_by'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Name:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_name = $company_document['company_name'];
                                                    if ($company_name == '') {
                                                        $company_name = 'Steven Warner';
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_name" name="company_name" value="<?php echo set_value('company_name', $company_name); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_name'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Title:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_title = $company_document['company_title'];
                                                    if ($company_title == '') {
                                                        $company_title = 'CEO';
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_title" name="company_title" value="<?php echo set_value('company_title', $company_title); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_title'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>DATE:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    if ($company_document['status'] == 'signed') {
                                                        $company_document['company_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['company_date'])));
                                                    } else {
                                                        $company_document['company_date'] = date('m-d-Y');
                                                    }

                                                    ?>
                                                    <input type="text" class="invoice-fields startdate" id="company_date" name="company_date" value="<?php echo set_value('company_date', $company_document['company_date']); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_date'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">CLIENT</span>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>By:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_by" name="client_by" value="<?php echo set_value('client_by', $company_document['client_by']); ?>" <?php echo $readonly; ?> />
                                                    <?php echo form_error('client_by'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Name:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_name" name="client_name" value="<?php echo set_value('client_name', $company_document['client_name']); ?>" <?php echo $readonly; ?> />
                                                    <?php echo form_error('client_name'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Title:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_title" name="client_title" value="<?php echo set_value('client_title', $company_document['client_title']); ?>" <?php echo $readonly; ?> />
                                                    <?php echo form_error('client_title'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>DATE:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    if ($company_document['status'] == 'signed') {
                                                        $company_document['client_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['client_date'])));
                                                    } else {
                                                        $company_document['client_date'] = date('m-d-Y');
                                                    }

                                                    ?>

                                                    <input type="text" class="invoice-fields startdate" id="client_date" name="client_date" value="<?php echo set_value('client_date', $company_document['client_date']); ?>" <?php echo $readonly; ?> />
                                                    <?php echo form_error('client_date'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <br>

                                <p style="float:left; width:100%; margin-top: 30px;" class="col-lg-12">IN WITNESS WHEREOF, the parties have caused this Agreement to be executed in its name and attested to by its duly authorized officers or individuals as of the Date below. </p>

                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card-fields-row">
                                                <div class="col-lg-3">
                                                    <label class="signature-label" style="font-size:14px;">E-SIGNATURE</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <?php
                                                    $company_signature = $company_document['company_signature'];
                                                    if ($company_signature == '') {
                                                        $company_signature = 'Steven Warner';
                                                    }
                                                    ?>
                                                    <input type="text" class="signature-field" name="company_signature" id="company_signature" value="<?php echo set_value('company_signature', $company_signature); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <p>Please type your First and Last Name</p>
                                                    <?php echo form_error('company_signature'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card-fields-row">
                                                <div class="col-lg-3">
                                                    <label class="signature-label" style="font-size:14px;">E-SIGNATURE</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input data-rule-required="true" type="text" class="signature-field" name="client_signature" id="client_signature" value="<?php echo set_value('client_signature', $company_document['client_signature']); ?>" <?php echo $readonly; ?> />
                                                    <p>Please type your First and Last Name</p>
                                                    <?php echo form_error('client_signature'); ?>
                                                </div>
                                            </div>

                                            <?php $uri_segment = $this->uri->segment(3); ?>
                                            <?php if ($uri_segment == 'view' || $uri_segment == null) { ?>
                                                <div class="form-col-100">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                                            <label class="" style="font-size:14px;">IP Address</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                                            <span>
                                                                <?php if (!empty($ip_track)) { ?>
                                                                    <?php echo $ip_track['ip_address']; ?>
                                                                <?php } else { ?>
                                                                    <?php echo getUserIP(); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                                            <label class="" style="font-size:14px;">Date/Time</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                                            <span>
                                                                <?php if (!empty($ip_track)) { ?>
                                                                    <?php echo date('m/d/Y h:i A', strtotime($ip_track['document_timestamp'])); ?>
                                                                <?php } else { ?>
                                                                    <?php echo date('m/d/Y h:i A'); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <p style="text-align: center !important;">

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-fields-row">
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_HEADING; ?>
                                    </p>
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_TITLE; ?>
                                    </p>
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_DESCRIPTION; ?>
                                    </p>
                                </div>
                                <div class="card-fields-row acknowledgment-row">
                                    <?php
                                    $is_default_accepted = false;
                                    if ($company_document['acknowledgement'] == 'terms_accepted') {
                                        $is_default_accepted = true;
                                    }
                                    ?>
                                    <label class="control control--checkbox" for="acknowledgement">
                                        <input type="checkbox" value="terms_accepted" id="acknowledgement" name="acknowledgement" <?php echo set_checkbox('acknowledgement', 'terms_accepted', $is_default_accepted); ?> <?php echo ($company_document['status'] == 'signed' ? 'onclick="return false"' : ''); ?> />&nbsp;
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_CHECKBOX; ?>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <?php echo form_error('acknowledgement'); ?>
                                </div>

                                <?php if ($company_document['status'] != 'signed') { ?>
                                    <div class="col-lg-6 col-lg-offset-3" id="signed">
                                        <?php if ($is_pre_fill == 1) { ?>
                                            <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn">Save</button>
                                        <?php } else { ?>
                                            <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn"><?php echo DEFAULT_SIGNATURE_CONSENT_BUTTON; ?></button>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    ?>

    <script>
        $(document).ready(function() {
            $('#form_end_user_license_agreement').validate();

            var value = $("input[name='payment_method']:checked").val();
            display(value);
        });

        function display(value) {
            if (value == 'monthly_subscription') {
                $('.monthly-subscription').show();
                $('.trial-period').hide();
            } else {
                $('.monthly-subscription').hide();
                $('.trial-period').show();
            }
        }
    </script>

    <?php if ($company_document['status'] != 'smigned') { ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.startdate').datepicker({
                    dateFormat: 'mm-dd-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "<?php echo DOB_LIMIT; ?>"
                }).val();

                $('.static-class').each(function() {
                    $(this).on('change', function() {
                        var current = $(this).val();
                    });
                });
            });



            $("input[type='radio']").click(function() {
                var value = $("input[name='payment_method']:checked").val();
                display(value);
            });
        </script>
    <?php } ?>
    <script>
        // print page button
        function print_page(elem) {
            $('form input[type=text]').each(function() {
                $(this).attr('value', $(this).val());
            });

            // hide the signed button
            $('#signed').hide();
            $('#print_div').hide();

            var data = ($(elem).html());
            var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

            mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery-ui-datepicker-custom.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/responsive.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');

            mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
            mywindow.document.close();
            mywindow.focus();

            // display the button again
            $('#signed').show();
            $('#print_div').show();
        }


        $('#contract_term').val('<?php echo $company_document['contract_term'] ?>');


        $('#term_number_of').on('change', function() {
            var termNumberOf = this.value;
            if (termNumberOf < 1) {
                alert('Number Of should be greater than 0 ');
                $('#term_number_of').val(1);
            }
        });
    </script>
</body>

</html>