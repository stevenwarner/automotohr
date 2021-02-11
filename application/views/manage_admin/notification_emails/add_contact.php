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
                                        <h1 class="page-title"><i class="fa fa-envelope"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                        <h1 class="page-title"><?php echo $company_name; ?></h1>
                                        <a class="black-btn pull-right" href="<?php 
                                        if($notification_type == 'billing_invoice'){
                                            echo base_url('manage_admin/notification_emails/billing_invoice_notifications') . '/' . $company_sid;
                                        } else {
                                            echo base_url('manage_admin/notification_emails/new_applicant_notifications') . '/' . $company_sid;
                                        }
                                        ?>">
                                            <i class="fa fa-long-arrow-left"></i> 
                                            <?php if($notification_type == 'billing_invoice'){ ?>
                                                Back to Billing And Invoice Emails Management
                                            <?php } else { ?>
                                                Back to New Applicant Emails Management
                                            <?php } ?>
                                        </a>
                                        </div>
                                    </div>
                                    <div class="row">                        
                                        <?php echo form_open('', array('id' => 'add_contact_form')); ?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="create-job-wrap">
                                                <div class="universal-form-style-v2">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="contact_name">Contact Name <span class="hr-required">*</span></label>
                                                            <input type="text" id="contact_name" name="contact_name" value="" class="hr-form-fileds">
                                                            <?php echo form_error('contact_name'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="contact_no">Contact Number</label>
                                                            <input type="text" id="contact_no" name="contact_no" value="" class="hr-form-fileds">
                                                            <?php echo form_error('contact_no'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="short_description">Short Description</label>
                                                            <input type="text" name="short_description" value="" class="hr-form-fileds">
                                                            <?php echo form_error('short_description'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="email">E-Mail Address <span class="hr-required">*</span></label>
                                                            <input type="text" name="email" value="" class="hr-form-fileds">								
                                                            <?php echo form_error('email'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <label id="lbl_is_registers_in_ahr" class="control control--checkbox">
                                                                Enable Email <small class="text-success">Check to enable email notifications to this contact.</small>
                                                                <input class="status" id="status" name="status" value="1" type="checkbox">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <input type="submit" value="Save" onclick="return validate_form()"class="btn btn-success" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
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
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
                                                                function validate_form() {
                                                                    $("#add_contact_form").validate({
                                                                        ignore: ":hidden:not(select)",
                                                                        rules: {
                                                                            contact_name: {
                                                                                required: true
                                                                            },
                                                                            email: {
                                                                                required: true,
                                                                                email: true,
                                                                                checkExists: true
                                                                            }
                                                                        },
                                                                        messages: {
                                                                            contact_name: {
                                                                                required: 'Contact Name is required!'
                                                                            },
                                                                            email: {
                                                                                required: 'Email Address is required!'
                                                                            }
                                                                        },
                                                                        submitHandler: function (form) {
                                                                            form.submit();
                                                                        }
                                                                    });
                                                                }

                                                                $(document).ready(function () {
                                                                    $.validator.addMethod("checkExists", function (value, element) {
                                                                        var contact_email = '<?php echo isset($contact['email']) ? $contact['email'] : ''; ?>';
                                                                        var inputElem = $('#add_contact_form :input[name="email"]');

                                                                        data = {"email": inputElem.val(),
                                                                            "perform_action": "checkuniqueemail",
                                                                            "notifications_type": "<?php echo $notification_type; ?>",
                                                                            "company_sid": "<?php echo $company_sid; ?>"
                                                                        };

                                                                        var my_response = $.ajax({
                                                                            type: "POST",
                                                                            url: "<?php echo base_url('manage_admin/notification_emails/ajax_responder') ?>",
                                                                            async: false,
                                                                            data: data
                                                                        });

                                                                        if (my_response.responseText == 'exists') {
                                                                            return false;
                                                                        } else {
                                                                            return true;
                                                                        }
                                                                    }, 'The Email Address already exists in the module');
                                                                });
</script>