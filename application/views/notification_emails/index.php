<style type="text/css">
    .dash-box {
        height: 100% !important;
    }

    .notification_Info {
        color: #000;
        font-style: italic;
    }

    .dash-box-main .dash-box {
        padding-bottom: 60px !important;
    }

    .dash-box-main {
        margin-bottom: 30px;
    }

    .dashboard-widget-box .button-panel {
        margin-top: 0 !important;
    }

    ul li {
        list-style: none;
        font-weight: 700;
    }
</style>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4 dash-box-main col-md-6 col-xs-12 col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/billing_invoice_notifications'); ?>">Billing and Invoice Notifications</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Billing and Invoice Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo BILLING_AND_INVOICE; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/billing_invoice_notifications'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 dash-box-main col-md-6 col-xs-12 col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/new_applicant_notifications'); ?>">New Applicant Notifications</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View New Applicant Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo NEW_APPLICANT; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/new_applicant_notifications'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6  col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/video_interview_notifications'); ?>">Video Interview Notifications</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Video Interview Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo VIDEO_INTERVIEW; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/video_interview_notifications'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/approval_rights_notifications'); ?>">Approval Rights Management</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Approval Rights Management</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo APPROVAL_RIGHTS; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/approval_rights_notifications'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/employment_application'); ?>">Full Employment Application</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Full Employment Application</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo FULL_EMPLOYMENT; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/employment_application'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/expiration_manager'); ?>">Expiration Manager</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Expiration Manager</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo EXPIRATION_MANAGER; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/expiration_manager'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/onboarding_request'); ?>">Onboarding Request Notifications</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Onboarding Request Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo ONBOARDING_REQUEST; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/onboarding_request'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/offer_letter'); ?>">Offer Letter Notifications</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Offer Letter Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo OFFER_LETTER; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/offer_letter'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/documents'); ?>">Document Notifications</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Document Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo DOCUMENT_ASSIGN; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/documents'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/general_information'); ?>">General Information Notifications</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View General Information Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo GENERAL_DOCUMENT; ?>
                                                            </strong>
                                                        </p>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                Driver's/Occupational license, dependent details, emergency contacts, direct deposit information.
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/general_information'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/employee_profile'); ?>">Employee Profile changes</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Employee Profile Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo EMPLOYEE_PROFILE; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/employee_profile'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/default_approvers'); ?>">Default Document Approvers</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Default Document Approvers</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo DEFAULT_APPROVERS; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/default_approvers'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/private_message_notification'); ?>">Private Messages Notification</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Private Messages Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                <?php echo PRIVATE_MESSAGE; ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/private_message_notification'); ?>">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Attendance notifications -->
                                        <div class="col-lg-4 col-md-6 col-xs-12 dash-box-main col-sm-12">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-calendar"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('notification_emails/attendance_notification'); ?>">Attendance Notifications</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>View Attendance Notifications</small>
                                                        <br>
                                                        <p>
                                                            <strong class="notification_Info">
                                                                The recipient(s) will be notified by email when any employee breach daily limit.
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a class="site-btn" href="<?php echo base_url('notification_emails/attendance_notification'); ?>">View</a>
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
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/matchHeight.js'); ?>"></script>

<script type="text/javascript">
    jQuery('.dash-box-main').matchHeight();
</script>