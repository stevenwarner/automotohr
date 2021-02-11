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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-file-code-o"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/system_notification_emails'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> System Notification Emails</a>
                                    </div>
                                    <div class="hr-add-new-promotions hr-add-new-template add-new-company">

                                        <div class="heading-title page-title">
                                            <h1 class="page-title">Email Record Details</h1>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-2"><strong>Full Name</strong></div>
                                            <div class="col-xs-1 text-right">:</div>
                                            <div class="col-xs-9"><?php echo isset($system_notification_email) ? $system_notification_email['full_name'] : ''; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-2"><strong>Email</strong></div>
                                            <div class="col-xs-1 text-right">:</div>
                                            <div class="col-xs-9"><?php echo isset($system_notification_email) ? $system_notification_email['email'] : ''; ?></div>
                                        </div>

                                        <hr />

                                        <form id="form_notification_email_config" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="sid" name="sid" value="<?php echo $system_notification_email['sid']; ?>" />

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $default_selected = false; ?>
                                                    <?php foreach($system_notification_email_config as $config) { ?>
                                                        <?php
                                                        if($config['has_access_to'] == 'billing_and_invoice_emails') {
                                                            $default_selected = true;
                                                            break;
                                                        } else {
                                                            $default_selected = false;
                                                        }
                                                        ?>
                                                    <?php } ?>

                                                    <label class="control control--checkbox">
                                                        <input <?php echo set_checkbox('has_access_to[]', 'billing_and_invoice_emails', $default_selected); ?> data-rule-required="true" name="has_access_to[]" id="billing_and_invoice_emails" value="billing_and_invoice_emails" type="checkbox">
                                                        Billing and Invoice Emails
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $default_selected = false; ?>
                                                    <?php foreach($system_notification_email_config as $config) { ?>
                                                        <?php
                                                        if($config['has_access_to'] == 'support_ticket_emails') {
                                                            $default_selected = true;
                                                            break;
                                                        } else {
                                                            $default_selected = false;
                                                        }
                                                        ?>
                                                    <?php } ?>

                                                    <label class="control control--checkbox">
                                                        <input <?php echo set_checkbox('has_access_to[]', 'support_ticket_emails', $default_selected); ?> data-rule-required="true" name="has_access_to[]" id="support_ticket_emails" value="support_ticket_emails" type="checkbox">
                                                        Support Ticket Emails
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $default_selected = false; ?>
                                                    <?php foreach($system_notification_email_config as $config) { ?>
                                                        <?php
                                                        if($config['has_access_to'] == 'private_message_emails') {
                                                            $default_selected = true;
                                                            break;
                                                        } else {
                                                            $default_selected = false;
                                                        }
                                                        ?>
                                                    <?php } ?>

                                                    <label class="control control--checkbox">
                                                        <input <?php echo set_checkbox('has_access_to[]', 'private_message_emails', $default_selected); ?>  data-rule-required="true" name="has_access_to[]" id="private_message_emails" value="private_message_emails" type="checkbox">
                                                        Private Message Emails
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>


                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $default_selected = false; ?>
                                                    <?php foreach($system_notification_email_config as $config) { ?>
                                                        <?php
                                                        if($config['has_access_to'] == 'accurate_background_emails') {
                                                            $default_selected = true;
                                                            break;
                                                        } else {
                                                            $default_selected = false;
                                                        }
                                                        ?>
                                                    <?php } ?>

                                                    <label class="control control--checkbox">
                                                        <input <?php echo set_checkbox('has_access_to[]', 'accurate_background_emails', $default_selected); ?>  data-rule-required="true" name="has_access_to[]" id="accurate_background_emails" value="accurate_background_emails" type="checkbox">
                                                        Accurate Background Emails
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $default_selected = false; ?>
                                                    <?php foreach($system_notification_email_config as $config) { ?>
                                                        <?php
                                                        if($config['has_access_to'] == 'free_demo_enquiry_emails') {
                                                            $default_selected = true;
                                                            break;
                                                        } else {
                                                            $default_selected = false;
                                                        }
                                                        ?>
                                                    <?php } ?>

                                                    <label class="control control--checkbox">
                                                        <input <?php echo set_checkbox('has_access_to[]', 'free_demo_enquiry_emails', $default_selected); ?>  data-rule-required="true" name="has_access_to[]" id="free_demo_enquiry_emails" value="free_demo_enquiry_emails" type="checkbox">
                                                        Free Demo Enquiry / Contact Us Emails
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $default_selected = false; ?>
                                                    <?php foreach($system_notification_email_config as $config) { ?>
                                                        <?php
                                                        if($config['has_access_to'] == 'company_account_expiration_emails') {
                                                            $default_selected = true;
                                                            break;
                                                        } else {
                                                            $default_selected = false;
                                                        }
                                                        ?>
                                                    <?php } ?>

                                                    <label class="control control--checkbox">
                                                        <input <?php echo set_checkbox('has_access_to[]', 'company_account_expiration_emails', $default_selected); ?>  data-rule-required="true" name="has_access_to[]" id="company_account_expiration_emails" value="company_account_expiration_emails" type="checkbox">
                                                        Company Account Expiration Emails
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $default_selected = false; ?>
                                                    <?php foreach($system_notification_email_config as $config) { ?>
                                                        <?php
                                                        if($config['has_access_to'] == 'documents_management_emails') {
                                                            $default_selected = true;
                                                            break;
                                                        } else {
                                                            $default_selected = false;
                                                        }
                                                        ?>
                                                    <?php } ?>

                                                    <label class="control control--checkbox">
                                                        <input <?php echo set_checkbox('has_access_to[]', 'documents_management_emails', $default_selected); ?>  data-rule-required="true" name="has_access_to[]" id="documents_management_emails" value="documents_management_emails" type="checkbox">
                                                        Documents Management Emails
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>


                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <button type="button" class="btn btn-success" onclick="func_validate_and_submit_form();">Save Configuration</button>
                                                    &nbsp;
                                                    <a href="<?php echo base_url('manage_admin/system_notification_emails'); ?>" class="black-btn">Cancel</a>
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function func_validate_and_submit_form(){
        $('#form_notification_email_config').validate();

        if($('#form_notification_email_config').valid()){
            $('#form_notification_email_config').submit();
        }
    }

    $(document).ready(function () {
        $('#all_emails').on('click', function(){
            if($(this).prop('checked') == true){
                $('input[type=checkbox]:not(#all_emails)').prop('disabled', true);
            } else {
                $('input[type=checkbox]:not(#all_emails)').prop('disabled', false);
            }
        });

        $('input[type=checkbox]:not(#all_emails)').on('click', function(){
            if($(this).prop('checked') == true){
                $('#all_emails').prop('disabled', true);
            } else {
                $('#all_emails').prop('disabled', false);
            }
        });
    });
</script>