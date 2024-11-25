<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php
                                                                        if ($notification_type == 'billing_invoice') {
                                                                            echo base_url('notification_emails/billing_invoice_notifications');
                                                                        } else if ($notification_type == 'video_interview') {
                                                                            echo base_url('notification_emails/video_interview_notifications');
                                                                        } else if ($notification_type == 'approval_management') {
                                                                            echo base_url('notification_emails/approval_rights_notifications');
                                                                        } else if ($notification_type == 'employment_application') {
                                                                            echo base_url('notification_emails/employment_application');
                                                                        } else if ($notification_type == 'expiration_manager') {
                                                                            echo base_url('notification_emails/expiration_manager');
                                                                        } else if ($notification_type == 'onboarding_request') {
                                                                            echo base_url('notification_emails/onboarding_request');
                                                                        } else if ($notification_type == 'offer_letter') {
                                                                            echo base_url('notification_emails/offer_letter');
                                                                        } else if ($notification_type == 'documents_status') {
                                                                            echo base_url("notification_emails/documents");
                                                                        } else if ($notification_type == 'general_information_status') {
                                                                            echo base_url("notification_emails/general_information");
                                                                        } else if ($notification_type == 'employee_Profile') {
                                                                            echo base_url("notification_emails/employee_Profile");
                                                                        } else if ($notification_type == 'default_approvers') {
                                                                            echo base_url("notification_emails/default_approvers");
                                                                        } else if ($notification_type == 'course_status') {
                                                                            echo base_url("notification_emails/courses");
                                                                        } else {
                                                                            echo base_url('notification_emails/new_applicant_notifications');
                                                                        }
                                                                        ?>">
                                        <i class="fa fa-chevron-left"></i>
                                        <?php if ($notification_type == 'billing_invoice') { ?>
                                            Billing and Invoice Email Notifications
                                        <?php } else if ($notification_type == 'video_interview') { ?>
                                            Video Interview System Email Notifications
                                        <?php } else if ($notification_type == 'approval_management') { ?>
                                            Approval rights Email Notifications
                                        <?php } else if ($notification_type == 'expiration_manager') { ?>
                                            Expiration Manager Email Notifications
                                        <?php } else if ($notification_type == 'employment_application') { ?>
                                            Full Employment Application Email Notifications
                                        <?php } else if ($notification_type == 'onboarding_request') { ?>
                                            Onboarding Request Notifications
                                        <?php } else if ($notification_type == 'offer_letter') { ?>
                                            Offer Letter Notifications
                                        <?php } else if ($notification_type == 'documents_status') { ?>
                                            Documents Notifications
                                        <?php } else if ($notification_type == 'general_information_status') { ?>
                                            General Information Notifications
                                        <?php } else if ($notification_type == 'employee_Profile') { ?>
                                            Employee Profile
                                        <?php } else if ($notification_type == 'default_approvers') { ?>
                                            Default Approvers Email Notifications
                                        <?php } else if ($notification_type == 'course_status') { ?>
                                            Course Report Notifications
                                        <?php } else { ?>
                                            New Applicant Email Notifications
                                        <?php } ?>
                                    </a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <?php echo form_open('', array('id' => 'edit_contact_form')); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="create-job-wrap">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="form-col-50-left">
                                                    <label for="contact_name">Contact Name <span class="staric">*</span></label>
                                                    <input type="text" id="contact_name" name="contact_name" value="<?php echo isset($contact['contact_name']) ? $contact['contact_name'] : ''; ?>" class="invoice-fields">
                                                </li>
                                                <li class="form-col-50-right">
                                                    <label for="contact_no">Contact Number</label>
                                                    <input type="text" id="contact_no" name="contact_no" value="<?php echo isset($contact['contact_no']) ? $contact['contact_no'] : ''; ?>" class="invoice-fields">
                                                </li>
                                                <li class="form-col-100">
                                                    <label for="short_description">Short Description</label>
                                                    <input type="text" name="short_description" value="<?php echo isset($contact['short_description']) ? $contact['short_description'] : ''; ?>" class="invoice-fields">
                                                </li>
                                                <li class="form-col-100">
                                                    <label for="email" <?php if ($contact['employer_sid'] != 0) { ?> style="color:#999;" <?php } ?>>E-Mail Address <span class="staric" <?php if ($contact['employer_sid'] != 0) { ?> style="color:#ea9;" <?php } ?>>*</span></label>
                                                    <input type="text" name="email" value="<?php echo isset($contact['email']) ? $contact['email'] : ''; ?>" class="invoice-fields" <?php if ($contact['employer_sid'] != 0) { ?> style="color:#999;" disabled="disabled" <?php } ?>>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label class="control control--checkbox">
                                                        Enable Emails <small class="text-success">Check to enable email notifications to this contact.</small>
                                                        <input class="status" id="status" name="status" value="1" <?php echo isset($contact['status']) && $contact['status'] == 'active' ? ' checked="checked" ' : ''; ?> type="checkbox" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                    <a href="<?php
                                                                if ($notification_type == 'billing_invoice') {
                                                                    echo base_url('notification_emails/billing_invoice_notifications');
                                                                } else if ($notification_type == 'video_interview') {
                                                                    echo base_url('notification_emails/video_interview_notifications');
                                                                } else if ($notification_type == 'approval_management') {
                                                                    echo base_url('notification_emails/approval_rights_notifications');
                                                                } else if ($notification_type == 'expiration_manager') {
                                                                    echo base_url('notification_emails/expiration_manager');
                                                                } else if ($notification_type == 'employment_application') {
                                                                    echo base_url('notification_emails/employment_application');
                                                                } else if ($notification_type == 'onboarding_request') {
                                                                    echo base_url('notification_emails/onboarding_request');
                                                                } else if ($notification_type == 'offer_letter') {
                                                                    echo base_url('notification_emails/offer_letter');
                                                                } else if ($notification_type == 'documents_status') {
                                                                    echo base_url("notification_emails/documents");
                                                                } else if ($notification_type == 'general_information_status') {
                                                                    echo base_url("notification_emails/general_information");
                                                                } else if ($notification_type == 'employee_Profile') {
                                                                    echo base_url("notification_emails/employee_Profile");
                                                                } else if ($notification_type == 'default_approvers') {
                                                                    echo base_url("notification_emails/default_approvers");
                                                                } else if ($notification_type == 'course_status') {
                                                                    echo base_url("notification_emails/courses");
                                                                } else {
                                                                    echo base_url('notification_emails/new_applicant_notifications');
                                                                }
                                                                ?>" id="cancel_button" class="submit-btn btn-cancel" >Cancel</a>
                                                </li>
                                            </ul>
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
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
    function validate_form() {
        $("#edit_contact_form").validate({
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
            submitHandler: function(form) {
                form.submit();
            }
        });
    }

    $(document).ready(function() {
        $.validator.addMethod("checkExists", function(value, element) {
            var contact_email = '<?php echo isset($contact['email']) ? $contact['email'] : ''; ?>';
            var inputElem = $('#edit_contact_form :input[name="email"]');

            if (contact_email == inputElem.val()) {
                return true;
            }

            data = {
                "email": inputElem.val(),
                "perform_action": "checkuniqueemail",
                "notifications_type": "<?php echo $notification_type; ?>"
            };

            var my_response = $.ajax({
                type: "POST",
                url: "<?php echo base_url('notification_emails/ajax_responder') ?>",
                async: false,
                data: data
            });
            //console.log(my_response);
            if (my_response.responseText == 'exists') {
                return false;
            } else {
                return true;
            }
        }, 'The Email Address already exists in the module');
    });
</script>