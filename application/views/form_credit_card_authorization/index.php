<?php $this->load->view('main/static_header'); ?>
<body>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php   $readonly = '';
                    
                            if ($company_document['status'] == 'signed') {
                                $readonly = ' readonly="readonly" ';
                            } ?>
                    <div class="credit-card-authorization">
                        <div class="top-logo text-center">
                            <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                        </div>
                        <!-- page print -->
                        <?php //if($is_pre_fill == 0) { ?>
                        <div class="top-logo" id="print_div">
                            <a href="javascript:;" class="btn btn-success" onclick="print_page('.container');">
                                <i class="fa fa-print" aria-hidden="true"></i> Print or Save
                            </a>
                        </div>
                        <?php //} ?>
                        <!-- page print -->
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>CREDIT CARD AUTHORIZATION FORM</span>
                        <div class="end-user-agreement-wrp recurring-payment-authorization">
                            <div class="recurring-payment-text-area">
                                <p>Schedule your payment to be automatically deducted from your bank account, or charged to your Visa, MasterCard, American Express or Discover Card. Just complete and sign this form to get started! </p>
                                <h2 class="credit-card-form-heading">Recurring Payments Will Make Your Life Easier: </h2>
                                <ul>
                                    <li>It's convenient (saving you time and postage) </li>
                                    <li>Your payment is always on time (even if you're out of town), eliminating late charges </li>
                                </ul>
                                <h2 class="credit-card-form-heading">Here's How Recurring Payments Work:</h2>
                                <p>You authorize regularly scheduled charges to your checking/savings account or credit card. You will be charged the amount indicated below each billing period. A receipt for each payment will be emailed to you and the charge will appear on your credit card/bank statement as a Credit Card Charge or ACH debit. You agree that no prior-notification will be provided unless the date or amount changes, in which case you will receive notice from us at least 10 days prior to the payment being collected. </p>
                            </div>
                            <?php   $pre_fill_flag = '';

                                    if ($is_pre_fill == 1) {
                                        $pre_fill_flag = '/pre_fill';
                                    } else {
                                        $pre_fill_flag = '';
                                    } ?>
                            <form id="form_credit_card_authorization_form" action="<?php echo base_url('form_credit_card_authorization' . '/' . $verification_key . $pre_fill_flag); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_document['company_sid']; ?>" />
                                <input type="hidden" id="is_pre_fill" name="is_pre_fill" value="<?php echo $is_pre_fill; ?>" />
                                <input type="hidden" id="action" name="action" value="upload_file" />

                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <strong>I</strong>,
                                            <div class="form-outer">
                                                <input type="text" placeholder="Full Name" name="authorized_person_full_name" id="authorized_person_full_name" class="invoice-fields" value="<?php echo set_value('authorized_person_full_name', $company_document['authorized_person_full_name']); ?>" <?php echo $readonly; ?> /> <?php echo form_error('authorized_person_full_name'); ?>
                                            </div>
                                            authorize <strong>AutomotoSocial LLC/<?php echo STORE_NAME; ?></strong> to charge my credit card indicated below for $ <div class="form-outer">
                                                <input placeholder="Recurring Amount" type="text" name="recurring_amount" id="recurring_amount" class="invoice-fields" value="<?php echo set_value('recurring_amount', $company_document['recurring_amount']); ?>"  <?php echo $readonly; ?>  /><?php echo form_error('recurring_amount'); ?></div> on the <div class="form-outer">
                                                <select <?php echo ($company_document['status'] == 'signed' ? 'disabled="disabled"' : ''); ?> class="invoice-fields" id="day_of_payment" name="day_of_payment"  <?php echo $readonly; ?> >
                                                    <option value="">Please Select</option>
                                                    <?php for ($count = 1; $count <= 28; $count++) { ?>
                                                        <?php   $is_default_day_of_payment = false;
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
                                <h2 class="credit-card-form-heading">Check only one:</h2>
                                <?php   $is_default_self = false;
                                        $is_default_company = false;

                                        if ($company_document['authorization_on_behalf_of'] == 'self') {
                                            $is_default_self = true;
                                        }

                                        if ($company_document['authorization_on_behalf_of'] == 'company') {
                                            $is_default_company = true;
                                        } ?>
                                <div>
                                    <div class="card-checkbox-row">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="control control--radio">As the Individual cardholder, I hereby authorize this card to be used for the deposit required.
                                                    <input type="radio" name="authorization_on_behalf_of" id="authorization_on_behalf_of_self" value="self" <?php echo set_checkbox('authorization_on_behalf_of', 'self', $is_default_self); ?>  <?php echo $readonly; ?> />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-checkbox-row">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="control control--radio">As the company representative, I hereby authorize this card to be used for the deposit required.
                                                    <input type="radio" name="authorization_on_behalf_of" id="authorization_on_behalf_of_company" value="company" <?php echo set_checkbox('authorization_on_behalf_of', 'company', $is_default_company); ?>   <?php echo $readonly; ?> />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo form_error('authorization_on_behalf_of'); ?>
                                </div>
                                <h2 class="credit-card-form-heading">Credit Card Billing Address:</h2>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                        <div class="card-fields-row">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                    <label>Billing Address</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                                    <input type="text" name="billing_address" id="billing_address" class="invoice-fields" value="<?php echo set_value('billing_address', $company_document['billing_address']); ?>"  <?php echo $readonly; ?> />
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
                                                    <input type="text" name="billing_phone_number" id="billing_phone_number" class="invoice-fields" value="<?php echo set_value('billing_phone_number', $company_document['billing_phone_number']); ?>"  <?php echo $readonly; ?> />
                                                    <?php echo form_error('billing_phone_number'); ?>
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
                                                            <input type="text" name="billing_city" id="billing_city" class="invoice-fields" value="<?php echo set_value('billing_city', $company_document['billing_city']); ?>"  <?php echo $readonly; ?> />
                                                            <?php echo form_error('billing_city'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><label>State</label></div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <input type="text" name="billing_state" id="billing_state" class="invoice-fields" value="<?php echo set_value('billing_state', $company_document['billing_state']); ?>"  <?php echo $readonly; ?> />
                                                            <?php echo form_error('billing_state'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><label>Zip Code</label></div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <input type="text" name="billing_zip_code" id="billing_zip_code" class="invoice-fields" value="<?php echo set_value('billing_zip_code', $company_document['billing_zip_code']); ?>"  <?php echo $readonly; ?> />
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
                                                    <input type="text" name="billing_email_address" id="billing_email_address" class="invoice-fields" value="<?php echo set_value('billing_email_address', $company_document['billing_email_address']); ?>"  <?php echo $readonly; ?> />
                                                    <?php echo form_error('billing_email_address'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php   $is_default_visa = false;
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
                                        <?php if($dont_show_it != 'view') { ?>
                                        <div class="card-boxes">
                                            <h2 class="credit-card-form-heading">Credit Card Details</h2>
                                            <div class="card-box-inner">
                                                <br/>                                                
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="card-fields-row">
                                                        <label class="control control--radio">Visa
                                                            <input type="radio" value="visa" name="cc_type" id="cc_type_visa" class="select-domain" <?php echo set_radio('cc_type', 'visa', $is_default_visa); ?>  <?php echo $readonly; ?> />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="card-fields-row">
                                                        <label class="control control--radio">Mastercard
                                                            <input type="radio" value="mastercard" name="cc_type" id="cc_type_mastercard" class="select-domain" <?php echo set_radio('cc_type', 'mastercard', $is_default_mastercard); ?>  <?php echo $readonly; ?> />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="card-fields-row">
                                                        <label class="control control--radio">Amex
                                                            <input type="radio" value="amex" name="cc_type" id="cc_type_amex" class="select-domain" <?php echo set_radio('cc_type', 'amex', $is_default_amex); ?>  <?php echo $readonly; ?> />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="card-fields-row">
                                                        <label class="control control--radio">Discover
                                                            <input type="radio" value="discover" name="cc_type" id="cc_type_discover" class="select-domain" <?php echo set_radio('cc_type', 'discover', $is_default_discovery); ?>  <?php echo $readonly; ?> />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <br/><br/><br/>
                                                <?php echo form_error('cc_type'); ?>
                                                <div class="card-fields-row">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label>Cardholder Name</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <input type="text" class="invoice-fields" name="cc_holder_name" id="cc_holder_name" value="<?php echo set_value('cc_holder_name'); ?>"/>
                                                        <?php echo form_error('cc_holder_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="card-fields-row">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label>Credit Card Number</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <input type="text" class="invoice-fields" name="cc_number" id="cc_number" value="<?php echo set_value('cc_number'); ?>"/>
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
                                                            <?php   for ($count = 1; $count <= 12; $count++) { 
                                                                        $is_default_exp_month = false;
                                                                        
                                                                        if (intval($company_document['cc_expiration_month']) == $count) {
                                                                            $is_default_exp_month = true;
                                                                        } else {
                                                                            $is_default_exp_month = false;
                                                                        } ?>
                                                                <option <?php echo set_select('cc_expiration_month', $count, $is_default_exp_month) ?> value="<?php echo $count ?>"><?php echo str_pad($count,2,"0",STR_PAD_LEFT); ?></option>
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
                                                                <option <?php echo set_select('cc_expiration_year', $count, $is_default_exp_year) ?>  value="<?php echo $count ?>"><?php echo $count ?></option>
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
                                <?php /* ?>
                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label>Credit Card Front</label>
                                            <div class="upload-img-container">
                                            <?php   $show_upload_button = false;
                                                    
                                                    if ($company_document['cc_front_image'] == '' || $company_document['cc_front_image'] == 'error') {
                                                        $cc_front_image = base_url('assets/images/cc-front.png');
                                                        $show_upload_button = true;
                                                    } else {
                                                        $cc_front_image = base_url('assets/images/companies/' . $company_document['company_sid'] . '/' . $company_document['cc_front_image']);
                                                        $show_upload_button = false;
                                                    } ?>

                                                <figure><img class="img-responsive" id="cc_front_image_preview" src="<?php echo $cc_front_image; ?>"></figure>

                                                <?php   if ($company_document['status'] != 'signed' && $show_upload_button == true) { ?>
                                                            <div class="upload-file file-button-container">
                                                                <span class="selected-file" id="file_cc_front_image">No file selected</span>
                                                                <input id="cc_front_image" name="cc_front_image" onchange="check_file('cc_front_image');" type="file">
                                                                <a href="javascript:;">Choose File</a>
                                                                <?php echo form_error('cc_front_image'); ?>
                                                            </div>
                                                <?php   } ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                        <?php   $show_upload_button = false;
                                        
                                                    if ($company_document['cc_back_image'] == '' || $company_document['cc_back_image'] == 'error') {
                                                        $cc_back_image = base_url('assets/images/cc-back.png');
                                                        $show_upload_button = true;
                                                    } else {
                                                        $cc_back_image = base_url('assets/images/companies/' . $company_document['company_sid'] . '/' . $company_document['cc_back_image']);
                                                        $show_upload_button = false;
                                                    } ?>

                                            <label>Credit Card Back</label>
                                            <div class="upload-img-container">
                                                <figure><img class="img-responsive" id="cc_back_image_preview" src="<?php echo $cc_back_image; ?>"></figure>
                                                <?php if ($company_document['status'] != 'signed' && $show_upload_button == true) { ?>
                                                    <div class="upload-file file-button-container">
                                                        <span class="selected-file" id="file_cc_back_image">No file selected</span>
                                                        <input id="cc_back_image" name="cc_back_image" onchange="check_file('cc_back_image');" type="file">
                                                        <a href="javascript:;">Choose File</a>
                                                        <?php echo form_error('cc_back_image'); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <?php //echo '**' . $company_document['driving_license_front_image'] . '**';

                                            $show_upload_button = false;
                                            
                                            if ($company_document['driving_license_front_image'] == '' || $company_document['driving_license_front_image'] == 'error') {
                                                $driving_license_front_image = base_url('assets/images/driver-license.jpg');
                                                $show_upload_button = true;
                                            } else {
                                                $driving_license_front_image = base_url('assets/images/companies/' . $company_document['company_sid'] . '/' . $company_document['driving_license_front_image']);
                                                $show_upload_button = false;
                                            } ?>

                                            <label>Scanned License Image</label>
                                            <div class="upload-img-container">
                                                <figure><img class="img-responsive" id="driving_license_front_image_preview" src="<?php echo $driving_license_front_image; ?>"></figure>
                                                <?php if ($company_document['status'] != 'signed' && $show_upload_button == true) { ?>
                                                    <div class="upload-file file-button-container">
                                                        <span class="selected-file" id="file_driving_license_front_image">No file selected</span>
                                                        <input id="driving_license_front_image" name="driving_license_front_image" onchange="check_file('driving_license_front_image');" type="file">
                                                        <a href="javascript:;">Choose File</a>
                                                        <?php echo form_error('driving_license_front_image'); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php */ ?>
                                <div class="card-fields-row">
                                    <div class="col-lg-2">
                                        <label class="signature-label">E-SIGNATURE</label>
                                    </div>
                                    <div class="col-lg-5">
                                        <input type="text" class="signature-field" name="signature" id="signature" value="<?php echo set_value('signature', $company_document['authorized_signature']); ?>" <?php echo $readonly; ?> />
                                        <p>Please type your First and Last Name</p>
                                        <?php echo form_error('signature'); ?>
                                    </div>
                                    <div class="col-lg-1">
                                        <label class="signature-label">DATE</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <?php
                                        //if ($company_document['authorization_date'] == '0000-00-00 00:00:00') {
                                            if ($company_document['status'] == 'signed') {
                                                $company_document['authorization_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['authorization_date'])));
                                            } else {
                                                $company_document['authorization_date'] = date('m-d-Y');
                                            }
                                        //} else {
                                        //    $company_document['authorization_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['authorization_date'])));
                                        //}
                                        ?>
                                        <div class="calendar-picker">
                                            <input type="text" class="invoice-fields startdate" name="authorization_date" id="authorization_date" value="<?php echo set_value('authorization_date', date('m/d/Y', strtotime(str_replace('-', '/', $company_document['authorization_date'])))); ?>"  <?php echo $readonly; ?> />
                                        </div>
                                        <?php echo form_error('authorization_date'); ?>
                                    </div>
                                </div>

                                <?php $uri_segment = $this->uri->segment(3); ?>
                                <?php if ($uri_segment == 'view' || $uri_segment == null) { ?>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label class="" style="font-size:14px;">IP Address</label>
                                        </div>
                                        <div class="col-lg-5">
                                            <span>
                                                <?php if(!empty($ip_track)) { ?>
                                                    <?php echo $ip_track['ip_address']; ?>
                                                <?php } else { ?>
                                                    <?php echo getUserIP(); ?>
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label class="" style="font-size:14px;">Date/Time</label>
                                        </div>
                                        <div class="col-lg-5">
                                            <span>
                                                <?php if(!empty($ip_track)) { ?>
                                                    <?php echo date('m/d/Y h:i A', strtotime($ip_track['document_timestamp'])); ?>
                                                <?php } else { ?>
                                                    <?php echo date('m/d/Y h:i A'); ?>
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php } ?>

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
                                <div class="card-fields-row">
                                    <div>2. I understand that this authorization will remain in effect until I cancel it in writing, and I agree to notify AutomotoSocial LLC / <?php echo STORE_NAME; ?> in writing of any changes in my account information or termination of this authorization at least 15 days prior to the next billing date. If the above noted payment dates fall on a weekend or holiday, I understand that the payments may be executed on the next business day. For ACH debits to my checking/savings account, I understand that because these are electronic transactions, these funds may be withdrawn from my account as soon as the above noted periodic transaction dates. In the case of an ACH Transaction being rejected for Non Sufficient Funds (NSF) I understand that AutomotoSocial LLC / <?php echo STORE_NAME; ?> may at its discretion attempt to process the charge again within 30 days, and agree to an additional
                                        $ <div class="form-outer"><input placeholder="Fee" type="text" name="additional_fee" id="additional_fee" class="invoice-fields" value="<?php echo set_value('additional_fee', $company_document['additional_fee']); ?>"  <?php echo $readonly; ?>  /><?php echo form_error('additional_fee'); ?></div> charge for each attempt returned NSF which will be initiated as a separate transaction from the authorized recurring payment. I acknowledge that the origination of ACH transactions to my account must comply with the provisions of U.S. law. I certify that I am an authorized user of this credit card/bank account and will not dispute these scheduled transactions with my bank or credit card company; so long as the transactions correspond to the terms indicated in this authorization form</div>
                                </div>
                                <div class="card-fields-row acknowledgment-row">
                                <?php   $is_default_accepted = false;
                                
                                            if ($company_document['acknowledgement'] == 'terms_accepted') {
                                                $is_default_accepted = true;
                                            } ?>

                                    <label class="control control--checkbox" for="acknowledgement">
                                        <input type="checkbox" value="terms_accepted" id="acknowledgement" name="acknowledgement" <?php echo set_checkbox('acknowledgement', 'terms_accepted', $is_default_accepted); ?> <?php echo ($company_document['status'] == 'signed' ? 'onclick="return false"' : ''); ?> />&nbsp;
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_CHECKBOX; ?><div class="control__indicator"></div>
                                    </label>
                                    <?php echo form_error('acknowledgement'); ?>
                                </div>
                                <?php if ($company_document['status'] != 'signed') { ?>
                                    <div class="card-fields-row">
                                        <div class="col-lg-6 col-lg-offset-3" id="signed">
                                            <?php if ($is_pre_fill == 1) { ?>
                                                <button type="submit" class="page-heading">Save</button>
                                            <?php } else { ?>
                                                <button type="button" onclick="fValidateFormAndSubmit();" class="page-heading"><?php echo DEFAULT_SIGNATURE_CONSENT_BUTTON; ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($company_document['status'] != 'signed') { ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.startdate').datepicker({
                    dateFormat: 'mm/dd/yy',
                    changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
                }).val();
            });
        </script>
    <?php } else { ?>
        <script>
            $(document).ready(function () {
                var radio_buttons = $('input[type=radio]:not([checked=checked])');

                $(radio_buttons).each(function () {
                    $(this).attr('disabled', true);
                });
                //console.log(radio_buttons);
            });
        </script>
    <?php } ?>
    <script>
        function fValidateFormAndSubmit() {
            $('#form_credit_card_authorization_form').validate({
                rules: {
                    acknowledgement: {
                        required: true
                    }
                },
                messages: {
                    acknowledgement: {
                        required: 'You must accept terms and conditions.'
                    }
                },
                errorElement: "span"

            });

            if ($('#form_credit_card_authorization_form').valid()) {
                $('#form_credit_card_authorization_form').submit();
            }
        }

        // Upload Image file
        function check_file(val) {
            var fileName = $("#" + val).val();
            if (fileName.length > 0) {
                $('#file_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                if (val == 'cc_front_image') {
                    if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                        $("#" + val).val(null);
                        alertify.error("Please select a valid Image format.");
                        $('#file_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                        return false;
                    } else
                        return true;
                }
            } else {
                $('#file_' + val).html('No file selected');
            }
        }

    // print page button
    function print_page(elem)
    {
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
    </script>
</body>
</html>