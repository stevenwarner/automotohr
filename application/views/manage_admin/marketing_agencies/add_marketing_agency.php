<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-briefcase"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/marketing_agencies'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Marketing Agencies</a>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding add-new-company">
                                            <form id="form_marketing_agency" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['full_name']) ? $marketing_agency['full_name'] : '' ); ?>
                                                            <?php echo form_label('Full Name <span class="hr-required">*</span>', 'full_name'); ?>
                                                            <?php echo form_input('full_name', set_value('full_name', $temp), 'class="hr-form-fileds" data-rule-required="true"'); ?>
                                                            <?php echo form_error('full_name');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['company_name']) ? $marketing_agency['company_name'] : '' ); ?>
                                                            <?php echo form_label('Company Name', 'company_name'); ?>
                                                            <?php echo form_input('company_name', set_value('company_name', $temp), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('company_name');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['contact_number']) ? $marketing_agency['contact_number'] : '' ); ?>
                                                            <?php echo form_label('Contact Number', 'contact_number'); ?>
                                                            <?php echo form_input('contact_number', set_value('contact_number', $temp), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('contact_number');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['website']) ? $marketing_agency['website'] : '' ); ?>
                                                            <?php echo form_label('Website', 'method_of_promotion'); ?>
                                                            <?php echo form_input('website', set_value('website', $temp), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('website');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['method_of_promotion']) ? $marketing_agency['method_of_promotion'] : '' ); ?>
                                                            <?php echo form_label('Method of Promotion', 'method_of_promotion'); ?>
                                                            <?php echo form_input('method_of_promotion', set_value('method_of_promotion', $temp), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('method_of_promotion');?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['zip_code']) ? $marketing_agency['zip_code'] : '' ); ?>
                                                            <?php echo form_label('Zip code', 'zip_code'); ?>
                                                            <?php echo form_input('zip_code', set_value('zip_code', $temp), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('zip_code');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['list_of_emails']) ? $marketing_agency['list_of_emails'] : '' ); ?>
                                                            <?php echo form_label('List of Emails', 'list_of_emails'); ?>
                                                            <?php echo form_input('list_of_emails', set_value('list_of_emails', $temp), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('list_of_emails');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight">
                                                            <?php $temp = ( isset($marketing_agency['address']) ? $marketing_agency['address'] : '' ); ?>
                                                            <?php echo form_label('Address', 'address'); ?>
                                                            <?php echo form_textarea('address', set_value('address', $temp), 'class="hr-form-fileds field-row-autoheight"'); ?>
                                                            <?php echo form_error('address');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight">
                                                            <?php $temp = ( isset($marketing_agency['notes']) ? $marketing_agency['notes'] : '' ); ?>
                                                            <?php echo form_label('Notes', 'notes'); ?>
                                                            <?php echo form_textarea('notes', set_value('notes', $temp), 'class="hr-form-fileds field-row-autoheight"'); ?>
                                                            <?php echo form_error('notes');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['email']) ? $marketing_agency['email'] : '' ); ?>
                                                            <?php echo form_label('Email', 'email'); ?>
                                                            <input data-rule-email="true" data-rule-required="true" type="email" id="email" name="email" value="<?php echo set_value('email', $temp); ?>" class="hr-form-fileds" />
                                                            <?php echo form_error('email');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['paypal_email']) ? $marketing_agency['paypal_email'] : '' ); ?>
                                                            <?php echo form_label('Paypal Email', 'paypal_email'); ?>
                                                            <input data-rule-email="true" type="email" id="paypal_email" name="paypal_email" value="<?php echo set_value('paypal_email', $temp); ?>" class="hr-form-fileds" />
                                                            <?php echo form_error('paypal_email');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['initial_commission_value']) ? $marketing_agency['initial_commission_value'] : '0' ); ?>
                                                            <?php echo form_label('Initial Commission <span class="hr-required">*</span>', 'initial_commission_value'); ?>
                                                            <input min="0" data-rule-required="true" type="number" id="initial_commission_value" name="initial_commission_value" value="<?php echo set_value('initial_commission_value', $temp); ?>" class="hr-form-fileds" />
                                                            <?php echo form_error('initial_commission_value');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php echo form_label('Initial Commission Type <span class="hr-required">*</span>', 'initial_commission_type'); ?>
                                                            <?php $temp = ( isset($marketing_agency['initial_commission_type']) ? $marketing_agency['initial_commission_type'] : '' ); ?>
                                                            <div class="hr-select-dropdown">
                                                                <select data-rule-required="true" id="initial_commission_type" name="initial_commission_type" class="invoice-fields">
                                                                    <?php $default_selected = ( $temp == '' ? true : false ); ?>
                                                                    <option <?php echo set_select('initial_commission_type', '', $default_selected); ?> value="">Please Select</option>

                                                                    <?php $default_selected = ( $temp == 'percentage' ? true : false ); ?>
                                                                    <option <?php echo set_select('initial_commission_type', 'percentage', $default_selected); ?> value="percentage">% Percentage</option>

                                                                    <?php $default_selected = ( $temp == 'fixed' ? true : false ); ?>
                                                                    <option <?php echo set_select('initial_commission_type', 'fixed', $default_selected); ?> value="fixed">$ Fixed Amount</option>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('initial_commission_type');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['recurring_commission_value']) ? $marketing_agency['recurring_commission_value'] : '0' ); ?>
                                                            <?php echo form_label('Recurring Commission <span class="hr-required">*</span>', 'recurring_commission_value'); ?>
                                                            <input min="0" data-rule-required="true" type="number" id="recurring_commission_value" name="recurring_commission_value" value="<?php echo set_value('recurring_commission_value', $temp); ?>" class="hr-form-fileds" />
                                                            <?php echo form_error('recurring_commission_value');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php echo form_label('Recurring Commission Type <span class="hr-required">*</span>', 'recurring_commission_type'); ?>
                                                            <?php $temp = ( isset($marketing_agency['recurring_commission_type']) ? $marketing_agency['recurring_commission_type'] : '' ); ?>
                                                            <div class="hr-select-dropdown">
                                                                <select data-rule-required="true" id="recurring_commission_type" name="recurring_commission_type" class="invoice-fields">
                                                                    <?php $default_selected = ( $temp == '' ? true : false ); ?>
                                                                    <option <?php echo set_select('recurring_commission_type', '', $default_selected); ?> value="">Please Select</option>

                                                                    <?php $default_selected = ( $temp == 'percentage' ? true : false ); ?>
                                                                    <option <?php echo set_select('recurring_commission_type', 'percentage', $default_selected); ?> value="percentage">% Percentage</option>

                                                                    <?php $default_selected = ( $temp == 'fixed' ? true : false ); ?>
                                                                    <option <?php echo set_select('recurring_commission_type', 'fixed', $default_selected); ?> value="fixed">$ Fixed Amount</option>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('recurring_commission_type');?>
                                                        </div>
                                                    </div>

                                                    <!--    Secondary Commission Fields Starts     -->
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['initial_commission_value']) ? $marketing_agency['secondary_initial_commission_value'] : '0' ); ?>
                                                            <?php echo form_label('Secondary Initial Commission', 'secondary_initial_commission_value'); ?>
                                                            <input min="0" data-rule-required="false" type="number" id="secondary_initial_commission_value" name="secondary_initial_commission_value" value="<?php echo set_value('secondary_initial_commission_value', $temp); ?>" class="hr-form-fileds" />
                                                            <?php echo form_error('secondary_initial_commission_value');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php echo form_label('Secondary Initial Commission Type', 'secondary_initial_commission_type'); ?>
                                                            <?php $temp = ( isset($marketing_agency['secondary_initial_commission_type']) ? $marketing_agency['secondary_initial_commission_type'] : '' ); ?>
                                                            <div class="hr-select-dropdown">
                                                                <select data-rule-required="false" id="secondary_initial_commission_type" name="secondary_initial_commission_type" class="invoice-fields">
                                                                    <?php $default_selected = ( $temp == '' ? true : false ); ?>
                                                                    <option <?php echo set_select('secondary_initial_commission_type', '', $default_selected); ?> value="">Please Select</option>

                                                                    <?php $default_selected = ( $temp == 'percentage' ? true : false ); ?>
                                                                    <option <?php echo set_select('secondary_initial_commission_type', 'percentage', $default_selected); ?> value="percentage">% Percentage</option>

                                                                    <?php $default_selected = ( $temp == 'fixed' ? true : false ); ?>
                                                                    <option <?php echo set_select('secondary_initial_commission_type', 'fixed', $default_selected); ?> value="fixed">$ Fixed Amount</option>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('secondary_initial_commission_type');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['secondary_recurring_commission_value']) ? $marketing_agency['secondary_recurring_commission_value'] : '0' ); ?>
                                                            <?php echo form_label('Secondary Recurring Commission', 'secondary_recurring_commission_value'); ?>
                                                            <input min="0" data-rule-required="false" type="number" id="secondary_recurring_commission_value" name="secondary_recurring_commission_value" value="<?php echo set_value('secondary_recurring_commission_value', $temp); ?>" class="hr-form-fileds" />
                                                            <?php echo form_error('secondary_recurring_commission_value');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php echo form_label('Secondary Recurring Commission Type', 'secondary_recurring_commission_type'); ?>
                                                            <?php $temp = ( isset($marketing_agency['secondary_recurring_commission_type']) ? $marketing_agency['secondary_recurring_commission_type'] : '' ); ?>
                                                            <div class="hr-select-dropdown">
                                                                <select data-rule-required="false" id="secondary_recurring_commission_type" name="secondary_recurring_commission_type" class="invoice-fields">
                                                                    <?php $default_selected = ( $temp == '' ? true : false ); ?>
                                                                    <option <?php echo set_select('secondary_recurring_commission_type', '', $default_selected); ?> value="">Please Select</option>

                                                                    <?php $default_selected = ( $temp == 'percentage' ? true : false ); ?>
                                                                    <option <?php echo set_select('secondary_recurring_commission_type', 'percentage', $default_selected); ?> value="percentage">% Percentage</option>

                                                                    <?php $default_selected = ( $temp == 'fixed' ? true : false ); ?>
                                                                    <option <?php echo set_select('secondary_recurring_commission_type', 'fixed', $default_selected); ?> value="fixed">$ Fixed Amount</option>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('recurring_commission_type');?>
                                                        </div>
                                                    </div>
                                                    <!--    Secondary Commission Fields End     -->


<!--                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">-->
<!--                                                        <div class="field-row">-->
<!--                                                            --><?php //echo form_label('Documents and Forms', 'documents_and_forms'); ?>
<!--                                                            <div class="upload-file form-control">-->
<!--                                                                <span class="selected-file" id="name_docs">No file selected</span>-->
<!--                                                                <input name="documents_and_forms" id="documents_and_forms" type="file">-->
<!--                                                                <a href="javascript:;">Choose File</a>-->
<!--                                                            </div>-->
<!---->
<!--                                                            --><?php //echo form_error('documents_and_forms');?>
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">-->
<!--                                                        <div class="field-row">-->
<!--                                                            --><?php //echo form_label('User Name', 'username'); ?>
<!--                                                                <input type="text" id="username" name="username" class="hr-form-fileds" value="--><?php //echo isset($marketing_agency['username']) ? $marketing_agency['username'] : ''?><!--">-->
<!--                                                                <input type="hidden" id="db-username" value="--><?php //echo isset($marketing_agency['username']) ? $marketing_agency['username'] : ''?><!--">-->
<!--                                                            --><?php //echo form_error('username');?>
<!--                                                        </div>-->
<!--                                                    </div>-->

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php echo form_label('Referred By', 'referred'); ?>

                                                            <div class="hr-select-dropdown">
                                                                <select data-rule-required="false" id="referred" name="referred" class="invoice-fields">
                                                                    <option value="">Please Select</option>
                                                                    <?php $default_selected = isset($marketing_agency['referred_by']) ? $marketing_agency['referred_by'] : ''; ?>
                                                                    <?php foreach($all_agencies as $agency){?>
                                                                        <option <?php echo set_select('referred', '', $agency['sid'] == $default_selected); ?> value="<?= $agency['sid']?>"><?php echo $agency['full_name']?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>


<!--                                                            <input type="text" id="referred" name="referred" class="hr-form-fileds" value="--><?php //echo isset($marketing_agency['referred_by']) ? $marketing_agency['referred_by'] : ''?><!--">-->

                                                            <?php echo form_error('referred');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php echo form_label('User Name', 'username'); ?>
                                                            <input type="text" id="username" name="username" class="hr-form-fileds" value="<?php echo isset($marketing_agency['username']) ? $marketing_agency['username'] : ''?>">
                                                            <input type="hidden" id="db-username" value="<?php echo isset($marketing_agency['username']) ? $marketing_agency['username'] : ''?>">
                                                            <?php echo form_error('username');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Please Upload W9 Form ( For Our U.S Affiliates )</label>
                                                            <div class="upload-file form-control">
                                                                <span class="selected-file" id="name_w9_form">No file selected</span>
                                                                <input type="file" name="w9_form" id="w9_form" class="hr-form-fileds" onchange="check_file('w9_form')">
                                                                <a href="javascript:;">Choose File</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Please Upload W8 Form ( For Affiliates Outside of the U.S )</label>
                                                            <div class="upload-file form-control">
                                                                <span class="selected-file" id="name_w8_form">No file selected</span>
                                                                <input type="file" name="w8_form" id="w8_form" class="hr-form-fileds" onchange="check_file('w8_form')">
                                                                <a href="javascript:;">Choose File</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php //if(isset($marketing_agency) && $_SERVER['HTTP_HOST']=='localhost') { ?>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <label class="full-width">&nbsp;</label>
                                                            <a href="javascript:;" id="send-cred" class="btn btn-primary text-right">Send Login Request</a>
                                                        </div>
                                                    </div>
                                                    <?php //} ?>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <label class="full-width">&nbsp;</label>
                                                            <?php if(isset($marketing_agency)) { ?>
                                                                <button onclick="func_validate_and_submit_form();" type="button" class="btn btn-success">Update</button>
                                                            <?php } else { ?>
                                                                <button onclick="func_validate_and_submit_form();" type="button" class="btn btn-success">Create</button>
                                                            <?php } ?>
                                                            <a href="<?php echo base_url('manage_admin/marketing_agencies'); ?>" class="btn black-btn">Cancel</a>

<!--                                                            --><?php //if(isset($marketing_agency)) {
//                                                                if (sizeof($agreement_flag) > 0) {
//                                                                    $link = base_url('form_affiliate_end_user_license_agreement/'.$agreement_flag[0]['verification_key'].'/pre_fill');
//                                                                    $btn_text = $agreement_flag[0]['status'] == 'signed' ? 'View Signed Document' : 'View Document';
//                                                                    echo '<a href="'.$link.'" class="btn btn-success" target="_blank">'.$btn_text.'</a>';
//                                                                } else { ?>
<!--                                                                    <input type="button" value="View And Send" class="btn btn-success" onclick="func_send_affiliate_agreement();">-->
<!--                                                                --><?php //}
//                                                            }?>
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <form id="form_generate_prefill" method="post" action="<?php echo base_url('manage_admin/marketing_agencies/generate_prefill'); ?>">
                                        <?php if(isset($marketing_agency)){ ?>
                                            <input type="hidden" id="perform_action" name="perform_action" value="generate_prefill" />
                                            <input type="hidden" id="market_sid" name="market_sid" value="<?php echo $marketing_agency['sid']; ?>" />
                                        <?php }?>
                                    </form>
                                    <?php if(isset($marketing_agency)){ ?>
                                        <article class="information-box">
                                            <header class="hr-box-header">Document Management
                                                <a class="site-btn pull-right" href="<?php echo base_url('manage_admin/marketing_agency_documents/' . $marketing_agency['sid']); ?>">Manage Documents</a>
                                                <a class="site-btn pull-right" href="<?php echo base_url('manage_admin/marketing_agency_documents/send/' . $marketing_agency['sid']); ?>">Send</a>
                                            </header>
                                            <div class="table-outer">
                                                <div class="info-row">
                                                    <ul>
                                                        <li>
                                                            <label>Form Name</label>
                                                            <div class="text text-center col-xs-6">
                                                                <strong>Status</strong>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <label>Affiliate End User License Agreement</label>
                                                            <div class="text text-center">
                                                                <?php $eula_status = (sizeof($market_documents_status) > 0 && $market_documents_status['eula_status'] != '' ? $market_documents_status['eula_status'] : 'Not Generated');?>
                                                                <span class="<?php echo strtolower(str_replace(' ', '-', $eula_status)); ?>">
                                                                    <?php echo ucwords(str_replace('-', ' ', $eula_status)) ?>
                                                                    <?php if (strtolower($eula_status) == 'not sent' || strtolower($eula_status) == 'generated' || strtolower($eula_status) == 'pre-filled' || strtolower($eula_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($eula_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                </span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <header class="hr-box-header hr-box-footer"></header>
                                        </article>
                                    <?php }?>
<!--                                    <div class="hr-box">-->
<!--                                        <div class="hr-box-header bg-header-green">-->
<!--                                            <span class="hr-registered pull-left"><span class="text-success"></span>Documents and Forms</span>-->
<!--                                        </div>-->
<!--                                        <div class="hr-box-body hr-innerpadding">-->
<!--                                            <div class="table-responsive">-->
<!--                                                <table class="table table-bordered table-hover table-stripped">-->
<!--                                                    <thead>-->
<!--                                                        <tr>-->
<!--                                                            <th class="text-center col-xs-2">Date</th>-->
<!--                                                            <th class="text-center col-xs-6">Document Name</th>-->
<!--                                                            <th class="text-center col-xs-2">Actions</th>-->
<!--                                                        </tr>-->
<!--                                                    </thead>-->
<!--                                                    <tbody>-->
<!--                                                    --><?php //  if(!empty($marketing_agency_documents)) {
//                                                                foreach($marketing_agency_documents as $documents) { ?>
<!--                                                                <tr>-->
<!--                                                                    <td class="text-center">--><?php //echo date_with_time($documents['insert_date'], true); ?><!--</td>-->
<!--                                                                    <td class="text-left">--><?php //echo $documents['document_name']; ?><!--</td>-->
<!--                                                                    <td class="text-center">-->
<!--                                                                        <a href="javascript:void(0);" onclick="fLaunchModal(this);" class=" btn btn-success" data-preview-url="--><?php //echo $documents['aws_document_name']; ?><!--" data-download-url="--><?php //echo $documents['aws_document_name']; ?><!--" data-document-title="--><?php //echo $documents['document_name']; ?><!--" ><i class = "fa fa-download"></i></a>-->
<!--                                                                    </td>-->
<!--                                                                </tr>-->
<!--                                                            --><?php //} ?>
<!--                                                        --><?php //} else { ?>
<!--                                                            <tr>-->
<!--                                                                <td class="text-center" colspan="3">-->
<!--                                                                    <span class="no-data">No Documents found!</span>-->
<!--                                                                </td>-->
<!--                                                            </tr>-->
<!--                                                        --><?php //} ?>
<!--                                                    </tbody>-->
<!--                                                </table>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
        } else {
            $('#name_' + val).html('No file selected');
        }
    }
    $(document).ready(function () {
        $('#form_marketing_agency').validate();

        $('#send-cred').click(function() {
            var formname = $('#username').val();
            var dbname = $('#db-username').val();
            alertify.confirm('Confirmation', "Are you sure you want to send Login Request?",function () {
                if ((formname == '' || formname == null) && (dbname == '' || dbname == null)) {
                    alertify.alert('Please Provide Username');
                    return false;
                } else if (dbname != '' && dbname != null) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('manage_admin/marketing_agencies/send_login_request')?>',
                        data: {
                            username: dbname,
                            id: '<?= isset($marketing_agency) ? $marketing_agency['sid'] : '';?>',
                            flag: 'db'
                        },
                        success: function (data) {
                            console.log(data);
                            alertify.success('Login Request have been sent successfully');
                            window.location.href = window.location.href;
                        },
                        error: function () {

                        }
                    });
                } else if (formname != '' && formname != null) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('manage_admin/marketing_agencies/send_login_request')?>',
                        data: {
                            username: formname,
                            id: '<?= isset($marketing_agency) ? $marketing_agency['sid'] : '' ;?>',
                            flag: 'form'
                        },
                        success: function (data) {
                            console.log(data);
                            if (data == 'exist') {
                                alertify.alert('Username already exist');
                            } else {
                                $('#db-username').val(formname);
                                alertify.success('Login Request have been sent successfully');
                                window.location.href = window.location.href;
                            }
                        },
                        error: function () {

                        }
                    });
                }
            },
            function () {
                alertify.error('Cancelled');
            });
        });
    });

    function func_validate_and_submit_form(){
        $('#form_marketing_agency').validate();

        if($('#form_marketing_agency').valid()){
            $('#form_marketing_agency').submit();
        }
    }

    function func_send_affiliate_agreement(){
        alertify.confirm('Confirmation', "Are you sure you want to Pre-Fill this agreement?",function () {
            $('#form_generate_prefill').submit();
        },
        function () {
            alertify.error('Cancelled');
        });
    }
    
    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var type = document_preview_url.split(".");
        var file_type = type[type.length - 1];
        var modal_content = '';
        var footer_content = '';
        var iframe_url = 'https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '&embedded=true';
        var is_document = false;

        if (document_preview_url != '') {
            if (file_type == 'jpg' || file_type == 'jpe' || file_type == 'jpeg' || file_type == 'png' || file_type == 'gif'){
                modal_content = '<img src="<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '" style="width:100%; height:500px;" />';
            } else {
                is_document = true;
                modal_content = '<iframe id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
            }
            
            footer_content = '<a class="btn btn-success" href="<?php echo AWS_S3_BUCKET_URL; ?>' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#file_preview_modal').modal("toggle");

        if (is_document) {
            document.getElementById('preview_iframe').contentWindow.location = iframe_url;
        }
    }
</script>