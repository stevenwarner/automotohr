<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/jquery-ui-datepicker-custom.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>">
    <?php if (!isset($loadUp)) { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/star-rating.css'); ?>">
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <?php } ?>

    <!-- <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" /> -->
    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= image_url('favicon_io'); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= image_url('favicon_io'); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= image_url('favicon_io'); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= image_url('favicon_io'); ?>/site.webmanifest">

    <script src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo site_url('assets/manage_admin/js/jquery-ui.js'); ?>"></script>
    <script src="<?php echo site_url('assets/manage_admin/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('assets/manage_admin/js/functions.js?v=2.0'); ?>"></script>
    <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
    <!-- include the style -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
    <!-- include a theme -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />
    <script src="<?= base_url() ?>assets/ckeditor/ckeditor.js"></script>

    <!--select2-->
    <?php
    if (!isset($customSelect2)) {

        if (strpos($_SERVER['REQUEST_URI'], "manage_admin/my-events") !== false) {
    ?>
            <link href="<?php echo site_url('assets/css/select2.css'); ?>" rel="stylesheet" />
            <script src="<?php echo site_url('assets/js/select2.js'); ?>"></script>
        <?php
        } else {
        ?>
            <link href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" rel="stylesheet" />
            <script src="<?php echo site_url('assets/manage_admin/js/select2.min.js'); ?>"></script>
    <?php
        }
    }
    ?>

    <?php if (!isset($loadUp)) { ?>
        <!-- Include MultiSelect -->
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>">
        <script src="<?php echo site_url('assets/manage_admin/js/chosen.jquery.js'); ?>"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/manage_admin/css') ?>/selectize.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/manage_admin/css') ?>/selectize.bootstrap3.css">
        <script src="<?php echo base_url('assets/manage_admin/js') ?>/selectize.min.js"></script>

        <!-- Include Jquery Validate -->
        <script src="<?php echo site_url('assets/manage_admin/js/jquery.validate.js'); ?>"></script>
        <script src="<?php echo site_url('assets/manage_admin/js/additional-methods.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/manage_admin/js/tableHeadFixer.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/manage_admin/js/star-rating.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/manage_admin/js/Chart.bundle.min.js'); ?>"></script>
    <?php } ?>

    <?php if (isset($PageCSS)) : ?>
        <!-- Stylesheets -->
        <?= GetCss($PageCSS); ?>
    <?php endif; ?>
    <?php if (isset($appCSS)) {
        echo $appCSS;
    } ?>


    <title><?php echo $page_title; ?></title>
    <?php echo $before_head; ?>

    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>
</head>

<body>
    <?php if ($this->ion_auth->logged_in()) { ?>
        <?php $current_user = $this->ion_auth->user()->row(); ?>

        <?php if ($current_user->active == 1) { ?>
            <div class="wrapper">
                <header class="header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="logo"><a href="<?php echo site_url('manage_admin/'); ?>"><img src="<?php echo site_url('assets/manage_admin/images/new_logo.JPG'); ?>"></a></div>
                                <div class="topRight">
                                    Welcome back, <?php echo $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name; ?><br>
                                    <a href="<?php echo site_url('manage_admin/'); ?>">Dashboard</a> |
                                    <a href="<?php echo site_url('manage_admin/user/logout'); ?>">Logout</a>
                                </div>

                                <div class="btn-group pull-right notify-me">
                                    <button type="button" data-toggle="dropdown" aria-expanded="false" class="notification-bell">
                                        <i class="fa fa-bell <?php if ($header_notifications['awaiting_response'] > 0 || $header_notifications['feedback_required'] > 0 || $header_notifications['pending_jobs_to_feed'] > 0 || $header_notifications['indeed_pending_status'] > 0) {
                                                                    echo 'faa-shake animated';
                                                                } ?>"></i>
                                        <span class="notification-count <?php if ($header_notifications['awaiting_response'] > 0 || $header_notifications['feedback_required'] > 0 || $header_notifications['pending_jobs_to_feed'] > 0 || sizeof($header_notifications['affiliations']) > 0 || $header_notifications['unpaid_commissions_count'] > 0 || $header_notifications['end_user_license_signed'] > 0 || $header_notifications['client_refer_by_affiliate'] > 0 || $header_notifications['form_document_credit_card_authorization'] > 0 || sizeof($header_notifications['form_affiliate_end_user_license_agreement']) > 0 || $header_notifications['private_messages'] > 0 || $header_notifications['pending_incidents'] > 0 || $header_notifications['indeed_pending_status'] > 0) {
                                                                            echo 'count-increament';
                                                                        } ?>">
                                            <?php
                                            $notifications_count = 0;


                                            if ($header_notifications['awaiting_response'] > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['feedback_required'] > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['pending_demo_requests'] > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['pending_jobs_to_feed'] > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['cc_expiring'] > 0) {
                                                $notifications_count++;
                                            }

                                            if (sizeof($header_notifications['affiliations']) > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['unpaid_commissions_count'] > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['end_user_license_signed'] > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['client_refer_by_affiliate'] > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['form_document_credit_card_authorization'] > 0) {
                                                $notifications_count++;
                                            }

                                            if (sizeof($header_notifications['form_affiliate_end_user_license_agreement']) > 0) {
                                                $notifications_count++;
                                            }

                                            if ($header_notifications['private_messages'] > 0) {
                                                $notifications_count++;
                                            }

                                            // For Admin SMS
                                            if (isset($header_notifications['sms_notifications']) && $header_notifications['sms_notifications'] > 0) {
                                                $notifications_count++;
                                            }

                                            if (isset($header_notifications['pending_incidents']) && $header_notifications['pending_incidents'] > 0) {
                                                $notifications_count++;
                                            }

                                            if (isset($header_notifications['profile_date_change']) && $header_notifications['profile_date_change'] > 0) {
                                                $notifications_count++;
                                            }

                                            if (isset($header_notifications['indeed_pending_status']) && $header_notifications['indeed_pending_status'] > 0) {
                                                $notifications_count++;
                                            }

                                            if (isset($header_notifications['indeed_job_has_errors']) && $header_notifications['indeed_job_has_errors'] > 0) {
                                                $notifications_count++;
                                            }

                                            

                                            echo $notifications_count;
                                            ?>
                                        </span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu dropdown-menu-wide">
                                        <?php if (isset($header_notifications['sms_notifications']) && $header_notifications['sms_notifications'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/sms/view'); ?>">
                                                    <span class="pull-left">New SMS (<?= $header_notifications['sms_notifications']; ?>)</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['awaiting_response'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/support_tickets/lists/awaiting'); ?>" data-toggle="tooltip" title="View">
                                                    <span class="pull-left">Ticket Notification (Awaiting Response)</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['feedback_required'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/support_tickets/lists/feedback'); ?>">
                                                    <span class="pull-left">Ticket Notification (Feedback Required)</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['pending_demo_requests'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/free_demo'); ?>">
                                                    <span class="pull-left">Free Demo Requests (Pending Response)</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['pending_jobs_to_feed'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/job_feeds_management'); ?>">
                                                    <span class="pull-left">Job Feed Management (Pending Jobs To Feed)</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['cc_expiring'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/cc_expires/expiring_in_month'); ?>">
                                                    <span class="pull-left">CC Status (Expiring In A Month)</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (sizeof($header_notifications['affiliations']) > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url($header_notifications['affiliations_link']); ?>">
                                                    <span class="pull-left">New Affiliation Request</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['unpaid_commissions_count'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/invoice/pending_commissions'); ?>">
                                                    <span class="pull-left">Pending Unpaid Commissions</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['end_user_license_signed'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/documents'); ?>">
                                                    <span class="pull-left">New End User License Signed</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['client_refer_by_affiliate'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/referred_clients'); ?>">
                                                    <span class="pull-left">New Client is Referred</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['form_document_credit_card_authorization'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/documents'); ?>">
                                                    <span class="pull-left">New Credit Card Authorization</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (sizeof($header_notifications['form_affiliate_end_user_license_agreement']) > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/marketing_agency_documents/' . $header_notifications['form_affiliate_end_user_license_agreement'][0]['marketing_agency_sid']); ?>">
                                                    <span class="pull-left">New Affiliate End User License Agreement</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['private_messages'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/private_messages'); ?>">
                                                    <span class="pull-left">New Private Message</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($header_notifications['pending_incidents'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/reports/incident_reporting/reported_incidents'); ?>">
                                                    <span class="pull-left"><?php echo $header_notifications['pending_incidents']; ?> Pending Incidents</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                            <?php } ?><?php if ($header_notifications['profile_date_change'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('employee_profile_data_report'); ?>">
                                                    <span class="pull-left"><?php echo $header_notifications['profile_date_change']; ?> Employee Profile Change (Today)</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        
                                        <?php if ($header_notifications['indeed_pending_status'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/indeed/disposition/status/map'); ?>">
                                                    <span class="pull-left">Pending Indeed Disposition Status</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($header_notifications['indeed_job_has_errors'] > 0) { ?>
                                            <li>
                                                <a href="<?php echo base_url('manage_admin/reports/indeed'); ?>">
                                                    <span class="pull-left">Indeed Job Errors</span>
                                                    <span class="pull-right"><i class="fa fa-eye"></i></span>
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($notifications_count == 0) { ?>
                                            <li class="text-center">
                                                <div class="no-data">No New Notifications</div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

            <?php } ?>
        <?php } ?>

        <style>
            .notify-me .dropdown-menu.dropdown-menu-wide {
                min-height: 300px;
                max-height: 400px;
            }
        </style>