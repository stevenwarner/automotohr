<?php if ($sanitizedView) {
    $this->load->view("main/header_sanitized");
} else { ?>
    <?php $load_view = isset($load_view) ? $load_view : false; ?>

    <?php if (!$load_view) { ?>
        <!doctype html>
        <html>

        <head>
            <?php $class = strtolower($this->router->fetch_class()); ?>
            <?php $method = $this->router->fetch_method(); ?>
            <meta charset="utf-8">
            <title><?php echo STORE_NAME; ?>: <?= $title ?></title>
            <meta name="keywords" content="" />
            <meta name="description" content="" />
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?php if ($this->uri->segment(1) == 'add_listing_share') { ?>
                <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
                <script type="text/javascript">
                    stLight.options({
                        publisher: "1be703d3-9992-4d1e-b33b-aa77ebec1707",
                        doNotHash: false,
                        doNotCopy: false,
                        hashAddressBar: false
                    });
                    stButtons.getCount("https://www.sharethis.com", "twitter", document.getElementById('tweetcount'));
                </script>
                <meta property="og:title" content="<?php echo $jobDetail['Title']; ?>" />
                <meta property="og:url" content="<?php echo $jobDetail['sub_domain']; ?>/job_details/<?php echo $jobDetail['job_sid']; ?>" />
                <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $jobDetail['pictures']; ?>" />
                <meta property="og:description" content="<?php echo str_replace('"', "'", strip_tags($jobDetail['JobDescription'], '<br>')); ?>" />
                <meta property="og:site_name" content="<?php echo STORE_NAME; ?>" />
            <?php   } ?>

            <?php if ($this->uri->segment(2) == 'new_page') { ?>
                <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/performance_review/css/style.css">
                <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/performance_review/css/bootstrap.css">
            <?php } else { ?>
                <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
            <?php } ?>
            <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css?v=1.0.1">
            <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css">
            <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/responsive.css">
            <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/star-rating.css">
            <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/easy-responsive-tabs.css">
            <!-- favicons -->
            <link rel="apple-touch-icon" sizes="180x180" href="<?= image_url('favicon_io'); ?>/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="<?= image_url('favicon_io'); ?>/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="<?= image_url('favicon_io'); ?>/favicon-16x16.png">
            <link rel="manifest" href="<?= image_url('favicon_io'); ?>/site.webmanifest">
            <!-- <link rel="icon" href="<?= base_url() ?>assets/images/favi-icon.png?v=<?= time(); ?>" /> -->
            <!-- <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" /> -->
            <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
            <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
            <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.nicescroll.min.js"></script>
            <script type="text/javascript" src="<?= base_url() ?>assets/js/star-rating.js"></script>
            <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.parallax-scroll.js"></script>
            <script type="text/javascript" src="<?= base_url() ?>assets/js/easyResponsiveTabs.js"></script>
            <script type="text/javascript" src="<?= base_url() ?>assets/js/functions.js"></script>
            <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
            <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
            <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />
            <script src="<?= base_url() ?>assets/ckeditor/ckeditor.js"></script>
            <script src="<?= base_url() ?>assets/js/jquery-ui.js"></script>
            <script src="<?= base_url() ?>assets/js/jquery.datetimepicker.js"></script>
            <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.datetimepicker.css" />
            <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
            <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui-datepicker-custom.css">
            <script type="text/javascript" src="<?= base_url('/assets/js/jquery.timepicker.js') ?>"></script>
            <link rel="stylesheet" href="<?= base_url('/assets/css/jquery.timepicker.css') ?>">
            <link href="<?= base_url() ?>assets/css/select2.css" rel="stylesheet" />
            <?php if ($this->uri->segment(2) == 'new_page') { ?>
                <script type="text/javascript" src="<?= base_url() ?>assets/performance_review/js/bootstrap.min.js"></script>
            <?php } else { ?>
                <script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
            <?php } ?>

            <script src="<?= base_url() ?>assets/js/select2.js"></script>

            <?php if ($class == 'turnover_cost_calculator') { ?>
                <!-- Range Slider -->
                <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/rangeslider.css" />
                <script type="text/javascript" src="<?= base_url() ?>assets/js/rangeslider.js"></script>
                <!-- Range Slider -->
            <?php } ?>
            <!-- jQuery SmartWizard -->
            <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/smart_wizard.css" />
            <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.smartWizard.min.js"></script>
            <!-- jQuery SmartWizard -->

            <!-- Bootstrap File Select -->
            <script type="text/javascript" src="<?= base_url() ?>assets/bootstrap-filestyle/js/bootstrap-filestyle.min.js"></script>
            <!-- Bootstrap File Select -->

            <?php if ($this->uri->segment(1) == 'organizational_hierarchy') { ?>
                <!-- Organizational Chart -->
                <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css') ?>/jquery.orgchart.css">
                <script src="<?php echo base_url('assets/js') ?>/jquery.orgchart.js"></script>
                <!-- Organizational Chart -->
            <?php } ?>

            <?php if ($this->uri->segment(1) == 'learning_center' || $this->uri->segment(1) == 'onboarding' || $this->uri->segment(1) == 'edit_ems_notification' || $this->uri->segment(1) == 'add_ems_notification') { ?>
                <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
                <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
            <?php } ?>

            <?php if ($this->uri->segment(1) == 'accurate_background' || $this->uri->segment(1) == 'application_tracking_system' || $this->uri->segment(1) == 'safety_sheets' || $this->uri->segment(1) == 'reports' || $this->uri->segment(1) == 'export_applicants_csv' || $this->uri->segment(1) == 'can-we-send-you-a-check-every-month') { ?>
                <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css') ?>/selectize.css">
                <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css') ?>/selectize.bootstrap3.css">
                <script src="<?php echo base_url('assets/js') ?>/selectize.min.js"></script>
            <?php } ?>

            <?php if ($class == 'events_management') { ?>
                <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/fullcalendar-3.4.0/fullcalendar.css') ?>">
                <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/fullcalendar-3.4.0/fullcalendar.print.css') ?>" media="print">
                <script src="<?php echo base_url('assets/fullcalendar-3.4.0/lib/moment.min.js') ?>"></script>
                <script src="<?php echo base_url('assets/fullcalendar-3.4.0/fullcalendar.js') ?>"></script>
            <?php } ?>

            <?php if ($class == 'video_interview_system') { ?>
                <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/video_interview/video-js/video-js.css') ?>" />
                <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/video_interview/video-js-record/dist/css/videojs.record.css') ?>" />
                <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/video_interview/video-js-wavesurfer/dist/css/videojs.wavesurfer.css') ?>" />

                <script src="<?php echo base_url('assets/video_interview/video-js/video.js') ?>"></script>
                <script src="<?php echo base_url('assets/video_interview/adapter-js/adapter.js') ?>"></script>
                <script src="<?php echo base_url('assets/video_interview/RecordRTC/RecordRTC.js') ?>"></script>
                <script src="<?php echo base_url('assets/video_interview/wavesurfer-js/wavesurfer.js') ?>"></script>
                <script src="<?php echo base_url('assets/video_interview/wavesurfer-js/wavesurfer.microphone.js') ?>"></script>
                <script src="<?php echo base_url('assets/video_interview/video-js-wavesurfer/dist/videojs.wavesurfer.js') ?>"></script>
                <script src="<?php echo base_url('assets/video_interview/video-js-record/dist/videojs.record.js') ?>"></script>
            <?php } ?>

            <?php if (checkIfAppIsEnabled('payroll') && $session['company_detail']['on_payroll'] && isPayrollOrPlus()) { ?>
                <script src="<?php echo base_url('assets/v1/payroll/js/employee-onboard.js') ?>"></script>
            <?php } ?>

            <?php if (isset($PageCSS)) : ?>
                <!-- Stylesheets -->
                <?= GetCss($PageCSS); ?>
            <?php endif; ?>

            <!--  -->
            <?php if (in_array('performance-management', $this->uri->segment_array())) { ?>
                <?php $this->load->view("{$pp}styles"); ?>
            <?php } ?>

            <!-- Modal -->
            <div class="modal fade" id="popupmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content user-login-popup">
                        <div class="modal-header modal-header-bg">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="popupmodallabel"><?php echo STORE_NAME; ?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="text" id="popupmodalbody">
                                <div class="myloader"><img src="<?= base_url() ?>assets/images/modelpopuploader2.gif"></div>
                            </div>
                        </div>
                        <div class="modal-footer" id="popupmodalbodyfooter">

                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <!--  -->
            <link rel="stylesheet" href="<?= base_url('assets/css/theme-2021.css?v=' . time()); ?>">
            <!--  -->
            <?php if (in_array('timeoff', $this->uri->segment_array()) || in_array('dashboard', $this->uri->segment_array())) { ?>
                <?php $this->load->view('timeoff/style'); ?>
            <?php } ?>

            <?php if (in_array('performance-management', $this->uri->segment_array())) { ?>
                <!-- Performance Management  -->
                <?php $this->load->view("{$pp}styles"); ?>
            <?php } ?>

            <!-- Bable -->
            <!-- <script src="https://unpkg.com/@babel/standalone@7.13.10/babel.min.js"></script> -->
            <!-- Bable -->

            <?php
            if (isset($pageCSS)) {
                echo GetCss($pageCSS);
            }
            ?>
            <?php if (isset($appCSS)) {
                echo $appCSS;
            } ?>

            <?= bundleCSS([
                "v1/app/css/global",
            ], "public/v1/app/", "global", true); ?>
        </head>

        <body>

            <div class="alert alert-info fade in alert-dismissable maintenance-message" id="maintenance-message">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <strong>Notice! </strong> We are performing scheduled maintenance and we will try our best to provide you uninterrupted service
            </div>
            <!-- Notification Side Panel -->
            <?php if ($this->session->userdata('logged_in')) { // only show the clocked in status bar if logged in
                $data['session'] = $this->session->userdata('logged_in');
                $company_sid = $data['session']['company_detail']['sid'];
            ?>



                <?php $class_name = $this->router->fetch_class(); ?>
                <?php if (in_array($class_name, array('affiliates'))) { ?>
                    <div class="page-banner 2 affiliates-banner">
                        <figure>

                            <?php if ($validate_flag == false) { ?>
                                <img src="<?= base_url() ?>assets/images/affiliates/bannar-2.jpg" alt="246" />
                            <?php } elseif ($validate_flag == true) { ?>
                                <?php if ($video_source == 'youtube_video') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $video_url; ?>/?autoplay=1&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1"></iframe>
                                    </div>
                                <?php } elseif ($video_source == 'vimeo_video') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $video_url; ?>?autoplay=1&loop=1&muted=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } else { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <video autoplay loop muted>
                                            <source src="<?php echo base_url() . 'assets/uploaded_videos/super_admin' . $video_url; ?>" type='video/mp4'>
                                            <p class="vjs-no-js">
                                                To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                            </p>
                                        </video>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <figcaption class="affiliates-header-text">
                                <h2><?php echo $home_page['header_text']; ?></h2>
                                <h4><?php echo $home_page['header_sub_text']; ?></h4>
                                <?php if ($home_page['sign_up_btn']) { ?>
                                    <div class="text-center">
                                        <a href="<?= base_url('can-we-send-you-a-check-every-month') ?>" class="btn affiliate-signup-btn">Join our Affiliate Program</a>
                                        <p class="contact-at">Got Questions Give Us a Call <a class="" href="tel:888 794-0794">(888) 794-0794</a></p>
                                    </div>
                                <?php } ?>
                            </figcaption>
                        </figure>
                        <?php if ($validate_flag == true) { ?>
                            <?php if ($video_source == 'youtube_video') { ?>
                                <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i class="fa fa-volume-off"></i></button>
                            <?php } elseif ($video_source == 'vimeo_video') { ?>
                                <button class="btn-mute-unmute" style="" onclick="vimeovolumestate(this);"><i class="fa fa-volume-off"></i></button>
                            <?php } else { ?>
                                <button class="btn-mute-unmute" style="" onclick="uploadedvolumestate(this);"><i class="fa fa-volume-off"></i></button>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } /*else { ?>
            <img data-parallax='{"z": 200}' src="<?php echo AWS_S3_BUCKET_URL . $home_page['header_banner']; ?>" alt="">
            <figcaption>
                <h2><?php echo $home_page['header_text']; ?></h2>
                <h4><?php echo $home_page['header_sub_text']; ?></h4>
            </figcaption>
        </figure>
        <?php } */ ?>

            <?php } ?>

            <?php if (in_array('ems', $this->uri->segment_array())) { ?>
                <style>
                    .fc-day-header.fc-widget-header,
                    .dashboard-link-btn,
                    .fc-axis,
                    .fc-widget-header,
                    .page-heading {
                        background-color: #1032c3 !important;
                        color: #ffffff !important;
                    }

                    .page-header-area:after {
                        border-top-color: #1032c3 !important;
                    }
                </style>
            <?php   }
            ?>

            <!-- Notification Side Panel End -->
            <div id="loading"></div>
            <div id="messageBox"></div>
            <div id="wrapper">
                <header class="<?= in_array('iframe', $this->uri->segment_array()) ? 'hidden' : ''; ?> header<?php
                                                                                                                if (
                                                                                                                    isset($isLoggedInView) ||
                                                                                                                    $class == 'dashboard' ||
                                                                                                                    $class == 'time_off' ||
                                                                                                                    $class == 'job_listings' ||
                                                                                                                    $class == 'i9' ||
                                                                                                                    $class == 'screening_questionnaires' ||
                                                                                                                    $class == 'settings' ||
                                                                                                                    $class == 'employee_login_text' ||
                                                                                                                    $class == 'bulk_resume_download' ||
                                                                                                                    $class == 're_assign_candidate' ||
                                                                                                                    $class == 'job_fair_configuration' ||
                                                                                                                    $class == 'incident_reporting_system' ||
                                                                                                                    $class == 'resend_screening_questionnaire' ||
                                                                                                                    $class == 'users' ||
                                                                                                                    $class == 'eeo' ||
                                                                                                                    // $class == 'demo' ||
                                                                                                                    $class == 'application_tracking' ||
                                                                                                                    $class == 'market_place' ||
                                                                                                                    $class == 'portal_email_templates' ||
                                                                                                                    $class == 'portal_sms_templates' ||
                                                                                                                    $class == 'manual_candidate' ||
                                                                                                                    $class == 'private_messages' ||
                                                                                                                    $class == 'xml_export' ||
                                                                                                                    $class == 'employee_management' ||
                                                                                                                    $class == 'department_management' ||
                                                                                                                    $class == 'onboarding_block' ||
                                                                                                                    $class == 'appearance' ||
                                                                                                                    $class == 'hr_documents' ||
                                                                                                                    $class == 'background_check' ||
                                                                                                                    $class == 'my_hr_documents' ||
                                                                                                                    $class == 'order_history' ||
                                                                                                                    $class == 'received_documents' ||
                                                                                                                    $class == 'order_detail' ||
                                                                                                                    $class == 'reference_checks' ||
                                                                                                                    $class == 'i9form'  ||
                                                                                                                    $class == 'form_i9' ||
                                                                                                                    $class == 'form_w4' ||
                                                                                                                    $class == 'form_w9' ||
                                                                                                                    $class == 'resource_center' ||
                                                                                                                    $class == 'facebook_configuration' ||
                                                                                                                    $class == 'security_access_level' ||
                                                                                                                    $class == 'reference_network' ||
                                                                                                                    $class == 'expirations_manager' ||
                                                                                                                    $class == 'reports' ||
                                                                                                                    $class == 'approval_rights_management' ||
                                                                                                                    $class == 'job_approval_management' ||
                                                                                                                    $class == 'applicant_approval_management' ||
                                                                                                                    $class == 'misc' ||
                                                                                                                    $class == 'cc_management' ||
                                                                                                                    $class == 'import_csv' ||
                                                                                                                    $class == 'accurate_background' ||
                                                                                                                    $class == 'job_listing_categories_manager' ||
                                                                                                                    $class == 'support_tickets' ||
                                                                                                                    $class == 'resume_database' ||
                                                                                                                    $class == 'notification_emails' ||
                                                                                                                    $class == 'post_on_jobs_to_career' ||
                                                                                                                    $class == 'application_status' ||
                                                                                                                    $class == 'interview_questionnaire' ||
                                                                                                                    $class == 'application_tracking_system' ||
                                                                                                                    $class == 'import_applicants_csv' ||
                                                                                                                    $class == 'assign_bulk_documents' ||
                                                                                                                    $class == 'turnover_cost_calculator' ||
                                                                                                                    $class == 'photo_gallery' ||
                                                                                                                    $class == 'organizational_hierarchy' ||
                                                                                                                    $class == 'video_interview_system' ||
                                                                                                                    $class == 'attendance' ||
                                                                                                                    $class == 'task_management' ||
                                                                                                                    $class == 'export_employees_csv' ||
                                                                                                                    $class == 'export_applicants_csv' ||
                                                                                                                    $class == 'events_management' ||
                                                                                                                    $class == 'onboarding' ||
                                                                                                                    $class == 'direct_deposit' ||
                                                                                                                    $class == 'learning_center' ||
                                                                                                                    $class == 'e_signature' ||
                                                                                                                    $class == 'my_learning_center' ||
                                                                                                                    $class == 'documents_management' ||
                                                                                                                    $class == 'hr_documents_management' ||
                                                                                                                    $class == 'calendar' ||
                                                                                                                    $class == 'company_addresses' ||
                                                                                                                    $class == 'form_full_employment_application' ||
                                                                                                                    $class == 'emergency_contacts' ||
                                                                                                                    $class == 'dependents' ||
                                                                                                                    $class == 'announcements' ||
                                                                                                                    $class == 'manage_ems' ||
                                                                                                                    $class == 'safety_sheets' ||
                                                                                                                    $class == 'general_info' ||
                                                                                                                    $class == 'complynet' ||
                                                                                                                    $class == 'turnover_cost_calculator' ||
                                                                                                                    $class == 'paid_time_off' ||
                                                                                                                    $class == 'performance_management' ||
                                                                                                                    $class == 'goals' ||
                                                                                                                    $class == 'govt_user' ||
                                                                                                                    $class == 'terminate_employee' ||
                                                                                                                    ($class == 'home' && $method == 'resource_page') ||
                                                                                                                    ($class == 'home' && $method == 'event') ||
                                                                                                                    ($class == 'home' && $method == 'services' && $this->uri->segment(2) == 'questionnaires-tutorial') ||
                                                                                                                    $class == 'performance_review' ||
                                                                                                                    $class == 'payroll' ||
                                                                                                                    isset($logged_in_view)
                                                                                                                ) {
                                                                                                                    echo " header-position";
                                                                                                                } elseif ($class == 'demo') {
                                                                                                                    echo " header-new-fixed";
                                                                                                                } ?>">
                    <div class="container-fluid">
                        <div class="row hidden-print">
                            <div class="col-md-12">
                                <div class="<?php
                                            if (
                                                $class != 'demo'
                                            ) {
                                                echo " hr-lanugages";
                                            } else {
                                                echo " hr-lanugages-new";
                                            } ?>">
                                    <div id="google_translate_element"></div>
                                </div>
                                <div class="country-flag">
                                    <ul>
                                        <li>
                                            <img src="<?= base_url() ?>assets/images/usa.png">
                                        </li>
                                        <li>
                                            <img src="<?= base_url() ?>assets/images/canada.png">
                                        </li>
                                        <?php $this->load->view("v1/attendance/partials/clocks/green/header"); ?>
                                    </ul>
                                </div>
                                <?php if (!$this->session->userdata('logged_in')) { ?>
                                    <div class="schedule-demo-btn-wrp <?php
                                                                        if ($this->uri->segment(1) == '') { /* home-demo-btn */
                                                                        } ?>">
                                        <ul>
                                            <?php if ($this->uri->segment(1) != 'demo' && $this->uri->segment(1) != 'schedule_your_free_demo') { ?>
                                                <li><a href="<?= base_url('schedule_your_free_demo') ?>"><i class="fa fa-calendar"></i>Schedule Your FREE DEMO</a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <div class="logo">
                                    <?php if ($this->uri->segment(1) == 'demo' || $this->uri->segment(1) == 'schedule_your_free_demo' || $this->uri->segment(1) == 'thank_you' || $this->uri->segment(1) == 'affiliate-program' || $this->uri->segment(1) == 'can-we-send-you-a-check-every-month') { ?>
                                        <a href="<?= base_url() ?>">
                                            <img class="" src="<?= base_url() ?>assets/images/affiliates/ahr_logo_demo_new.png">
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?= base_url() ?>">
                                            <img src="<?= base_url() ?>assets/images/ahr_logo_138X80_wt.png">
                                        </a>
                                    <?php } ?>
                                </div>
                                <?php if ($this->session->userdata('logged_in')) { ?>
                                    <?php $get_cart_content = $this->session->userdata('logged_in'); ?>
                                    <?php if (isset($get_cart_content['cart']) && !empty($get_cart_content['cart'])) { ?>
                                        <?php $cart_content = $get_cart_content['cart']; ?>
                                        <?php $cart_count = count($cart_content); ?>
                                        <?php $sub_total = 0; ?>
                                        <?php $has_coupon = false;
                                        $coupon_data = array();

                                        if ($this->session->userdata('coupon_data')) {
                                            $coupon_data = $this->session->userdata('coupon_data');
                                            if (!empty($coupon_data)) {
                                                $has_coupon = true;
                                            }
                                        }

                                        if ($this->uri->segment(1) != 'add_listing_advertise' && $this->uri->segment(1) != 'schedule_your_free_demo' && $this->uri->segment(1) != 'demo' && $this->uri->segment(1) != 'thank_you') { ?>
                                            <div class="cart-area">
                                                <div class="cart-header">
                                                    <button class="cart-button">
                                                        <span id="cart_total_top"><?php echo $cart_count; ?></span>
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </button>
                                                    <div class="view-cart outter-view-cart" id="view-cart">
                                                        <div class="cart-header-inner">
                                                            <p id="cart_total_inner">Your cart (<?php echo $cart_count; ?>items)</p>
                                                        </div>
                                                        <div class="cart-body" id="show_no_cart">
                                                            <input type="hidden" name="cart_count" id="cart_count" value="<?php echo $cart_count; ?>">
                                                            <?php foreach ($cart_content as $key => $value) { ?>
                                                                <article id="viewcart_<?php echo $value['sid']; ?>">
                                                                    <figure>
                                                                        <img src="<?php echo AWS_S3_BUCKET_URL;
                                                                                    if (!empty($value['product_image'])) {
                                                                                        echo $value['product_image'];
                                                                                    } else { ?>default_pic-ySWxT.jpg<?php } ?>">
                                                                    </figure>
                                                                    <div class="text">
                                                                        <p><?php echo $value['name']; ?></p>
                                                                        <?php $serialized_extra_info = unserialize($value['serialized_extra_info']); ?>
                                                                        <?php if (empty($value['price'])) {
                                                                            $cart_price = 0;
                                                                        } else {
                                                                            $cart_price = $value['price'];
                                                                        }

                                                                        $no_of_days = $value['no_of_days'];

                                                                        if ($no_of_days == 0) {
                                                                            $product_total = $value['qty'] * $cart_price;
                                                                            //echo '<p>'.$value['qty'] . ' x $' . $cart_price.' = '. '$' . $product_total .'</p>';
                                                                            echo '<p>$' . $cart_price . ' x ' . $value['qty'] . ' Qty = ' . '$' . $product_total . '</p>';
                                                                        } else {
                                                                            $product_total = $no_of_days * $cart_price;
                                                                            echo '<p>$' . $cart_price . ' x ' . $no_of_days . ' day(s) = ' . '$' . $product_total . '</p>';
                                                                        }

                                                                        $sub_total += $product_total; ?>

                                                                        <p style="display: none;" id="product_total_<?php echo $value['sid']; ?>"><?php echo $product_total; ?></p>
                                                                        <?php /* if($no_of_days==0){ ?>
                                                                  <p><?php echo $value['qty'] . ' x $' . $cart_price; ?> = <?php echo '$' . $product_total; ?></p>
                                                                  <?php } else { ?>
                                                                  <p><?php echo $value['qty'] . ' x $' . $cart_price; ?> = <?php echo '$' . $product_total; ?></p>
                                                                  <?php } */ ?>
                                                                    </div>
                                                                    <a class="remove-item-btn" href="javascript:;" onclick="remove_cart_item(<?php echo $value['sid']; ?>)">X</a><!-- function defined at footer -->
                                                                </article>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="cart-footer" id="hide_cart_footer">
                                                            <div class="sub-total-count">
                                                                <?php //if (isset($get_cart_content['coupon_data']) && !empty($get_cart_content['coupon_data'])) {    
                                                                ?>
                                                                <label>Sub-Total:</label>
                                                                <p style="display: none;" id="cart_subtotal_value"><?php echo $sub_total; ?></p>
                                                                <p id="cart_subtotal"><?php echo '$' . $sub_total; ?></p>
                                                                <?php //}    
                                                                ?>
                                                            </div>
                                                            <div class="check-out-btn">
                                                                <a data-toggle="modal" id="checkout_cart_click" data-target="#checkout_cart_model" href="javascript:;">Checkout</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Cart checkout Modal *** START *** -->
                                            <div id="checkout_cart_model" class="modal fade" role="dialog">
                                                <div class="modal-dialog checkout_cart_model">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Market Place Product Checkout</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="universal-form-style-v2 payment-area">
                                                                <ul>
                                                                    <div class="row">
                                                                        <form method="post" action="">
                                                                            <input type="hidden" name="action" value="cart_checkout">
                                                                            <input type="hidden" id="total" name="total" value="">
                                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                <div class="table-responsive table-outer">
                                                                                    <div class="product-detail-area">
                                                                                        <table>
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <td colspan="2" width="60%">Item </td>
                                                                                                    <td class="text-align">Qty / Day(s) </td>
                                                                                                    <td class="text-align">Price</td>
                                                                                                    <td class="text-align">Sub Total </td>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php foreach ($cart_content as $key => $value) { ?>
                                                                                                    <tr id="checkoutcart_<?php echo $value['sid']; ?>">
                                                                                                        <td>
                                                                                                            <figure>
                                                                                                                <?php if (!empty($value['product_image'])) { ?>
                                                                                                                    <img src="<?php echo AWS_S3_BUCKET_URL . $value['product_image']; ?>">
                                                                                                                <?php } ?>
                                                                                                            </figure>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <h3 class="details-title--polite"><?php echo $value['name']; ?></h3>
                                                                                                            <p class="error noproducterrors" id="noproduct_<?php echo $value['sid']; ?>"></p>
                                                                                                        </td>
                                                                                                        <?php if (empty($value['price'])) {
                                                                                                            $cart_price = 0;
                                                                                                        } else {
                                                                                                            $cart_price = $value['price'];
                                                                                                        }

                                                                                                        $no_of_days = $value['no_of_days'];

                                                                                                        if ($no_of_days == 0) {
                                                                                                            $product_total = $value['qty'] * $cart_price;
                                                                                                            $total_qty = $value['qty'];
                                                                                                        } else {
                                                                                                            $product_total = $value['qty'] * $cart_price * $no_of_days;
                                                                                                            $total_qty = $no_of_days;
                                                                                                        } ?>
                                                                                                        <td class="text-align"><?php echo $total_qty; ?></td>
                                                                                                        <td class="text-align"><?php echo '$' . $cart_price; ?></td>
                                                                                                        <td class="text-align"><?php echo '$' . $product_total; ?></td>
                                                                                                    </tr>
                                                                                                <?php } ?>
                                                                                            </tbody>
                                                                                            <tfoot>
                                                                                                <tr>
                                                                                                    <td colspan="2" width="60%">
                                                                                                        &nbsp;</td>
                                                                                                    <td>&nbsp;</td>
                                                                                                    <td class="text-align">
                                                                                                        <b id="checkout_title">
                                                                                                            <?php if ($has_coupon) {
                                                                                                                echo 'Sub-Total';
                                                                                                            } else {
                                                                                                                echo 'Total';
                                                                                                            } ?>
                                                                                                        </b>
                                                                                                    </td>
                                                                                                    <td class="text-align">
                                                                                                        <p id="checkout_subtotal"><?php echo '$' . $sub_total; ?></p>
                                                                                                    </td>
                                                                                                    <p style="display: none;" id="checkout_subtotal_value"><?php echo $sub_total; ?></p>
                                                                                                    <p style="display: none;" id="coupon_code">
                                                                                                        <?php if ($has_coupon) {
                                                                                                            echo $coupon_data['coupon_code'];
                                                                                                        } ?>
                                                                                                    </p>
                                                                                                </tr>
                                                                                                <?php if ($has_coupon) { ?>
                                                                                                    <?php $coupon_type = $coupon_data['coupon_type']; ?>
                                                                                                    <?php if ($coupon_type == 'fixed') {
                                                                                                        $coupon_discount = $coupon_data['coupon_discount'];
                                                                                                    } else {
                                                                                                        $coupon_discount = round((($sub_total * $coupon_data['coupon_discount']) / 100), 2);
                                                                                                    } ?>
                                                                                                    <tr id="show_coupon_amount">
                                                                                                        <td colspan="2" width="60%"> &nbsp;</td>
                                                                                                        <td>&nbsp;</td>
                                                                                                        <td class="text-align"><b>Coupon (<?php echo $coupon_data['coupon_code']; ?>)</b></td>
                                                                                                        <td class="text-align">
                                                                                                            <p id="coupon_amount"> -$<?php echo $coupon_discount; ?></p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr id="show_coupon_total">
                                                                                                        <td colspan="2" width="60%"> &nbsp;</td>
                                                                                                        <td>&nbsp;</td>
                                                                                                        <td class="text-align">
                                                                                                            <b>Total</b>
                                                                                                        </td>
                                                                                                        <?php $final_total = round(($sub_total - $coupon_discount), 2); ?>
                                                                                                        <p style="display: none;" id="checkout_subtotal_value"><?php echo $final_total; ?></p>
                                                                                                        <td class="text-align">
                                                                                                            <p id="checkout_total"><?php echo '$' . $final_total; ?></p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                <?php } else { ?>
                                                                                                    <tr id="show_coupon_amount"></tr>
                                                                                                    <tr id="show_coupon_total"></tr>
                                                                                                <?php } ?>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                        <div class="form-col-100">
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <li>
                                                                                    <label>Discount Coupon</label>
                                                                                    <input type="text" value="" name="discount_coupon" id="discount_coupon_main_cart" class="invoice-fields">
                                                                                    <div id="coupon_response_main_cart"></div>
                                                                                </li>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <li>
                                                                                    <label>&nbsp;</label>
                                                                                    <input type="button" id="apply_coupon" value="Apply Coupon" class="submit-btn">
                                                                                    <div id="coupon_spinner" class="spinner hide"><i class="fa fa-refresh fa-spin"></i>
                                                                                        Processing...
                                                                                    </div>
                                                                                </li>

                                                                            </div>
                                                                        </div>
                                                                        <div id="free_no_payment">
                                                                            <div class="form-col-100">
                                                                                <form id="form_free_checkout">
                                                                                    <?php foreach ($cart_content as $key => $value) { ?>
                                                                                        <div id="removeitfromcart_free_<?php echo $value['sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][mid]" value="<?php echo $value['sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][id]" value="<?php echo $value['product_sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][qty]" value="<?php echo $value['qty']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][name]" value="<?php echo $value['name']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][no_of_days]" value="<?php echo $value['no_of_days']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][price]" value="<?php echo $value['price']; ?>">
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                    <div id="maincartcouponarea_free">
                                                                                        <?php if ($has_coupon) { ?>
                                                                                            <input type="hidden" name="coupon_code" value="<?php echo $coupon_data['coupon_code']; ?>">
                                                                                            <input type="hidden" name="coupon_type" value="<?php echo $coupon_data['coupon_type']; ?>">
                                                                                            <input type="hidden" name="coupon_discount" value="<?php echo $coupon_data['coupon_discount']; ?>">
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                    <div class="col-xs-12">
                                                                                        <input id="btn-free-checkout" class="submit-btn" style="width: 50%; margin:0 auto;" type="button" id="free_order_btn" name="free_order_btn" value="Process Free Order" onclick="fProcessFreeCheckout();" />
                                                                                        <div id="free_spinner" class="spinner hide"> <i class="fa fa-refresh fa-spin"></i>
                                                                                            Processing...
                                                                                        </div>
                                                                                        <input type="hidden" id="is_free_checkout" name="is_free_checkout" value="0" />
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        <div id="cr_card_payment">
                                                                            <div class="form-col-100">
                                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                    <li>
                                                                                        <label>Payment with</label>
                                                                                        <div class="hr-select-dropdown">
                                                                                            <select name="p_with_main" id="p_with_main" onchange="check_ccd(this.value)" class="invoice-fields">
                                                                                                <option value="new">Add new credit card </option>
                                                                                                <?php $get_data = $this->session->userdata('logged_in');
                                                                                                $cards = db_get_card_details($get_data['company_detail']['sid']);
                                                                                                if (!empty($cards)) {
                                                                                                    foreach ($cards as $card) {
                                                                                                        echo '<option value="' . $card['sid'] . '">' . $card['number'] . ' - ' . $card['type'] . ' ';
                                                                                                        echo ($card['is_default'] == 1) ? '(Default)' : '';
                                                                                                        echo '</option>';
                                                                                                    }
                                                                                                } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </li>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                    <div class="payment-method"><img src="<?= base_url() ?>assets/images/payment-img.jpg">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-col-100 savedccd">
                                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                    <span>(<span class="staric hint-str">*</span>) Denotes required fields</span>
                                                                                </div>
                                                                            </div>
                                                                            <?php echo form_open('', array('id' => 'ccmain')); ?>
                                                                            <div id="novalidatemain"></div>
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 savedccd">
                                                                                <header class="payment-heading">
                                                                                    <h2>Credit Card Details</h2>
                                                                                </header>
                                                                                <div class="form-col-100">
                                                                                    <li>
                                                                                        <label>Number<span class="staric">*</span></label>
                                                                                        <input id="cc_card_no" type="text" name="cc_card_no" value="" class="invoice-fields">
                                                                                    </li>
                                                                                </div>
                                                                                <div class="form-col-100">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                            <li>
                                                                                                <label>Expiration Month<span class="staric">*</span></label>
                                                                                                <div class="hr-select-dropdown">
                                                                                                    <select id="cc_expire_month" name="cc_expire_month" class="invoice-fields">
                                                                                                        <option value=""></option>
                                                                                                        <option value="01">01 </option>
                                                                                                        <option value="02">02 </option>
                                                                                                        <option value="03">03 </option>
                                                                                                        <option value="04">04 </option>
                                                                                                        <option value="05">05 </option>
                                                                                                        <option value="06">06 </option>
                                                                                                        <option value="07">07 </option>
                                                                                                        <option value="08">08 </option>
                                                                                                        <option value="09">09 </option>
                                                                                                        <option value="10">10 </option>
                                                                                                        <option value="11">11 </option>
                                                                                                        <option value="12">12 </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </li>
                                                                                        </div>
                                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                            <li>
                                                                                                <label>Year<span class="staric">*</span></label>
                                                                                                <div class="hr-select-dropdown">
                                                                                                    <?php $current_year = date('Y'); ?>
                                                                                                    <select id="cc_expire_year" name="cc_expire_year" class="invoice-fields">
                                                                                                        <option value=""></option>
                                                                                                        <?php for ($i = $current_year; $i <= $current_year + 10; $i++) { ?>
                                                                                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                                                        <?php } ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </li>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 savedccd">
                                                                                <header class="payment-heading">
                                                                                    <h2>&nbsp;</h2>
                                                                                </header>
                                                                                <div class="form-col-100">
                                                                                    <li>
                                                                                        <label>Type<span class="staric">*</span></label>
                                                                                        <div class="hr-select-dropdown">
                                                                                            <select id="cc_type" name="cc_type" class="invoice-fields">
                                                                                                <option value=""></option>
                                                                                                <option value="visa">Visa</option>
                                                                                                <option value="mastercard">Mastercard</option>
                                                                                                <option value="discover">Discover</option>
                                                                                                <option value="amex">Amex</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </li>
                                                                                </div>
                                                                                <div class="form-col-100">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-7 col-md-7 col-xs-12 col-sm-6">
                                                                                            <li>
                                                                                                <label class="small-case">ccv</label>
                                                                                                <input id="cc_ccv" type="text" name="cc_ccv" value="" class="invoice-fields">
                                                                                            </li>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-col-100 autoheight">
                                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                    <div class="checkbox-field savedccd">
                                                                                        <input type="checkbox" name="cc_future_payment" id="future-payment">
                                                                                        <label for="future-payment">Save this card for future payment</label>
                                                                                    </div>
                                                                                    <?php foreach ($cart_content as $key => $value) { ?>
                                                                                        <div id="removeitfromcart_<?php echo $value['sid']; ?>">
                                                                                            <input type="hidden" name="process_credit_card" id="process_credit_card" value="1">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][mid]" value="<?php echo $value['sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][id]" value="<?php echo $value['product_sid']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][qty]" value="<?php echo $value['qty']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][name]" value="<?php echo $value['name']; ?>">
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][no_of_days]" value="<?php echo $value['no_of_days']; ?>">
                                                                                            <?php if (empty($value['price'])) {
                                                                                                $cart_price = 0;
                                                                                            } else {
                                                                                                $cart_price = $value['price'];
                                                                                            }

                                                                                            $product_total = $value['qty'] * $cart_price; ?>
                                                                                            <input type="hidden" name="product[<?php echo $key; ?>][price]" value="<?php echo $cart_price; ?>">
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                    <div id="maincartcouponarea">
                                                                                        <?php if ($has_coupon) { ?>
                                                                                            <input type="hidden" name="coupon_code" value="<?php echo $coupon_data['coupon_code']; ?>">
                                                                                            <input type="hidden" name="coupon_type" value="<?php echo $coupon_data['coupon_type']; ?>">
                                                                                            <input type="hidden" name="coupon_discount" value="<?php echo $coupon_data['coupon_discount']; ?>">
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                    <div class="btn-panel">
                                                                                        <input type="submit" id="cc_send" value="Confirm payment" onclick="return pp_confirm_main()" class="submit-btn">
                                                                                        <div id="cc_spinner" class="spinner hide"><i class="fa fa-refresh fa-spin"></i>
                                                                                            Processing...
                                                                                        </div>
                                                                                    </div>
                                                                                    <p id="checkout_error_message"></p>
                                                                                    <p id="checkout_error_message_coupon"></p>
                                                                                </div>
                                                                            </div>
                                                                            <?php echo form_close(); ?>
                                                                        </div>
                                                                    </div>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="row">
                                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-6">
                                                                    <div class="media-content">
                                                                        <h3 class="details-title">Secure payment</h3>
                                                                        <p class="details-desc">This is a secure 256-bit SSL encrypted payment</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                    <span class="payment-secured">Powered by <strong>Paypal</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Cart checkout Modal *** END *** -->
                                    <?php
                                        }
                                    } ?>
                                <?php } ?>
                                <nav class="navigation">
                                    <?php
                                    if ($this->uri->segment(1) == 'schedule_your_free_demo' || $this->uri->segment(1) == 'demo') {
                                    ?>
                                        <div id="main-navigation">
                                            <ul id="menu-primary-menu" class="nav navbar-nav navbar-right">
                                                <li>
                                                    <a class="header-new-fixed-links" id="button-about" title="About">
                                                        About
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="header-new-fixed-links" id="button-join" title="Join">
                                                        Join
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="header-new-fixed-links" id="button-contact" title="Contact">
                                                        Contact
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <?php if (null !== ($this->session->userdata('logged_in'))) { ?>
                                            <div class="pull-left notify-me">
                                                <button class="notification-bell" data-toggle="dropdown">
                                                    <i class="fa fa-bell"></i>
                                                    <span class="notification-count count-increament" id="js-notification-count">0</span>
                                                </button>
                                                <ul role="menu" class="dropdown-menu dropdown-menu-wide" id="js-notification-box"></ul>
                                            </div>
                                            <ul id="menus">
                                                <li class="active"><a href="javascript:void(0)">Quick Links<i class="fa fa-angle-down"></i></a>
                                                    <ul>
                                                        <li>
                                                            <a <?php if (base_url(uri_string()) == site_url('dashboard')) {
                                                                    echo 'class="active_header_nav"';
                                                                } ?> href="<?php echo base_url('dashboard'); ?>">
                                                                <figure><i class="fa fa-th"></i></figure> Dashboard
                                                            </a>
                                                        </li>
                                                        <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('employee_management_system')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('employee_management_system'); ?>">
                                                                    <figure><i class="fa fa-th"></i></figure> EMS
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (check_access_permissions_for_view($security_details, 'add_listing')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('add_listing')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('add_listing') ?>">
                                                                    <figure><i class="fa fa-pencil-square-o"></i></figure>
                                                                    Create A New Job
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--1-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'market_place')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('market_place')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('market_place') ?>">
                                                                    <figure><i class="fa fa-shopping-cart"></i></figure>
                                                                    My Marketplace
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--2-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'my_listings')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('my_listings') || $this->uri->segment(1) == 'edit_listing' || $this->uri->segment(1) == 'clone_listing') {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('my_listings/active') ?>">
                                                                    <figure><i class="fa fa-list-alt"></i></figure>
                                                                    My Jobs
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--3-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'application_tracking')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('application_tracking_system')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('application_tracking_system/active/all/all/all/all/all/all/all/all/all') ?>">
                                                                    <figure><i class="fa fa-line-chart"></i></figure>
                                                                    Application Tracking
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--4-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'my_events')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('calendar/my_events')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('calendar/my_events') ?>">
                                                                    <figure><i class="fa fa-calendar"></i></figure>
                                                                    Calender / Events
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--5-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'private_messages')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('private_messages')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('private_messages') ?>">
                                                                    <figure><i class="fa fa-envelope"></i></figure>
                                                                    Private Message
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--6-->
                                                        <?php
                                                        $canAccessDocument = hasDocumentsAssigned($session['employer_detail']);
                                                        ?>
                                                        <?php if (check_access_permissions_for_view($security_details, 'employee_management') || $canAccessDocument) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('employee_management') || base_url(uri_string()) == site_url('invite_colleagues')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url(); ?>employee_management">
                                                                    <figure><i class="fa fa-users"></i></figure>
                                                                    Employee / Team Members
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--7-->
                                                        <?php if (check_company_ems_status($this->session->userdata('logged_in')['company_detail']['sid'])) { ?>
                                                            <!--                                            --><?php //if(check_access_permissions_for_view($security_details, 'documents_management')) { 
                                                                                                                ?>
                                                            <!--                                                <li>-->
                                                            <!--                                                    <a --><?php //if (base_url(uri_string()) == site_url('hr_documents_management')) {
                                                                                                                            //                                                        echo 'class="active_header_nav"';
                                                                                                                            //                                                    } 
                                                                                                                            ?>
                                                            <!-- href="--><?php //echo base_url('hr_documents_management') 
                                                                            ?>
                                                            <!--">-->
                                                            <!--                                                        <figure><i class="fa fa-file"></i></figure>-->
                                                            <!--                                                        Document Management</a>-->
                                                            <!--                                                </li>-->
                                                            <!--                                            --><?php //} 
                                                                                                                ?>
                                                        <?php } else { ?>
                                                            <?php if (check_access_permissions_for_view($security_details, 'hr_documents')) { ?>
                                                                <li>
                                                                    <a <?php if (base_url(uri_string()) == site_url('hr_documents') || base_url(uri_string()) == site_url('add_hr_document')) {
                                                                            echo 'class="active_header_nav"';
                                                                        } ?> href="<?php echo base_url('hr_documents') ?>">
                                                                        <figure><i class="fa fa-file"></i></figure>
                                                                        Admin HR Documents
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <!--8-->
                                                        <?php //if(check_access_permissions_for_view($security_details, 'resume_database')) { 
                                                        ?>
                                                        <!--                                            <li>
                                                <a <?php if (base_url(uri_string()) == site_url('resume_database')) {
                                                        echo 'class="active_header_nav"';
                                                    } ?> href="<?php echo base_url('resume_database'); ?>">
                                                    <figure><i class="fa fa-database"></i></figure>
                                                    Resume Database</a>
                                            </li>-->
                                                        <?php //} 
                                                        ?>
                                                        <!--9-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'my_settings')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('my_settings')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('my_settings') ?>">
                                                                    <figure><i class="fa fa-sliders"></i></figure>
                                                                    Settings
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--10-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'screening_questionnaires')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('screening_questionnaires')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('screening_questionnaires') ?>">
                                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                                    Candidate Questionnaires
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--11-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'video_interview_system')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('video_interview_system')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('video_interview_system') ?>">
                                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                                    Video Interview System
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if (check_access_permissions_for_view($security_details, 'interview_questionnaire')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('interview_questionnaire')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url(); ?>interview_questionnaire">
                                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                                    Interview Questionnaires
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--12-->
                                                        <?php if (check_access_permissions_for_view($security_details, 'background_check')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('background_check')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url('accurate_background'); ?>">
                                                                    <figure><i class="fa fa-file"></i></figure>
                                                                    Background Checks Report
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <!--13-->
                                                        <?php /*if(check_access_permissions_for_view($security_details, 'settings')) { ?>
                                            <li>
                                                <a <?php if (base_url(uri_string()) == site_url('employee_login')) {
                                                    echo 'class="active_header_nav"';
                                                } ?> href="<?php echo base_url('employee_login'); ?>">
                                                    <figure><i class="fa fa-file"></i></figure>
                                                    Employee Login Text</a>
                                            </li>
                                        <?php } */ ?>
                                                        <!--13-->
                                                        <!--14-->

                                                        <?php /*if(check_access_permissions_for_view($security_details, 'organizational_hierarchy')) { */ ?>
                                                        <!--
                                            <li>
                                                <a <?php /*if (base_url(uri_string()) == site_url('organizational_hierarchy')) {
                                                    echo 'class="active_header_nav"';
                                                } */ ?> href="<?php /*echo base_url(); */ ?>organizational_hierarchy">
                                                    <figure><i class="fa fa-users"></i></figure>
                                                    Organizational Hierarchy</a>
                                            </li>
                                        --><?php /*} */ ?>
                                                        <!--15-->

                                                        <?php $data['session'] = $this->session->userdata('logged_in'); ?>
                                                        <?php $company_sid = $data["session"]["company_detail"]["sid"]; ?>
                                                        <?php $this->load->view("v1/attendance/partials/clocks/green/quick_links"); ?>


                                                        <!--16-->

                                                        <!--                                        --><?php //if($this->session->userdata('logged_in')['company_detail']['ems_status']) { 
                                                                                                        ?>
                                                        <!--                                            --><?php //$data['session'] = $this->session->userdata('logged_in'); 
                                                                                                            ?>
                                                        <!--                                            --><?php //$company_sid = $data["session"]["company_detail"]["sid"]; 
                                                                                                            ?>
                                                        <!--                                            --><?php //if(in_array($company_sid, explode(',', TEST_COMPANIES))) { 
                                                                                                            ?>
                                                        <!--                                                <li>-->
                                                        <!--                                                    <a --><?php //if (base_url(uri_string()) == site_url('learning_center')) {
                                                                                                                        //                                                        echo 'class="active_header_nav"';
                                                                                                                        //                                                    } 
                                                                                                                        ?>
                                                        <!-- href="--><?php //echo base_url(); 
                                                                        ?>
                                                        <!--learning_center">-->
                                                        <!--                                                        <figure><i class="fa fa-book"></i></figure>-->
                                                        <!--                                                        Learning Center <span class="beta-label">beta</span></a>-->
                                                        <!--                                                </li>-->
                                                        <!--                                            --><?php //} 
                                                                                                            ?>
                                                        <!--                                        --><?php //} 
                                                                                                        ?>
                                                        <!--<!--17-->

                                                        <?php if (check_access_permissions_for_view($security_details, 'support_tickets')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('support_tickets')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url(); ?>support_tickets">
                                                                    <figure><i class="fa fa-tags"></i></figure>
                                                                    Support Tickets
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] && check_access_permissions_for_view($security_details, 'ems_portal')) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <a href="<?php echo base_url('manage_ems') ?>">
                                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                                    Manage EMS
                                                                </a>
                                                            </li>
                                                        <?php  } ?>

                                                        <li>
                                                            <?php $get_data = $this->session->userdata('logged_in');
                                                            $sub_domain_url = db_get_sub_domain($get_data['company_detail']['sid']); ?>
                                                            <a href="<?php echo base_url('authorized_document'); ?>">
                                                                <figure><i class="fa fa-clipboard"></i></figure>
                                                                Assigned Documents
                                                            </a>
                                                        </li>

                                                        <?php $comply_status = $data["session"]["company_detail"]["complynet_status"];
                                                        $employee_comply_status = $session["employer_detail"]["complynet_status"];
                                                        ?>
                                                        <?php if (check_access_permissions_for_view($security_details, 'complynet') && $comply_status && $employee_comply_status) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <?php $complyNetLink = getComplyNetLink($this->session->userdata('logged_in')['company_detail']['sid'], $this->session->userdata('logged_in')['employer_detail']['sid']); ?>
                                                                <?php if ($complyNetLink) { ?>
                                                                    <a href="<?= base_url('cn/redirect'); ?>">
                                                                        <figure><i class=" fa fa-book"></i></figure>
                                                                        ComplyNet
                                                                    </a>
                                                                <?php } ?>
                                                            </li>
                                                        <?php  } ?>

                                                        <?php if (check_resource_permission($session['company_detail']['sid']) && check_access_permissions_for_view($security_details, 'resource_center_panel')) { ?>
                                                            <li>
                                                                <a <?php if (base_url(uri_string()) == site_url('resource_center')) {
                                                                        echo 'class="active_header_nav"';
                                                                    } ?> href="<?php echo base_url(); ?>resource_center">
                                                                    <figure><i class="fa fa-files-o"></i></figure>
                                                                    Resource Center
                                                                </a>
                                                            </li>
                                                        <?php } ?>

                                                        <?php
                                                        $pto_user_access = get_pto_user_access($session['employer_detail']['parent_sid'], $session['employer_detail']['sid']);
                                                        ?>
                                                        <?php if (checkIfAppIsEnabled('timeoff') && $pto_user_access['quick_link'] == 1) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <a href="<?php echo $pto_user_access['url']; ?>">
                                                                    <figure><i class="fa fa-clock-o"></i></figure>
                                                                    Time Off
                                                                </a>
                                                            </li>
                                                        <?php  } ?>
                                                        <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <a href="<?php echo base_url('performance-management/dashboard'); ?>">
                                                                    <figure><i class="fa fa-pencil-square-o"></i></figure>
                                                                    Performance Management
                                                                </a>
                                                            </li>
                                                        <?php  } ?>
                                                        <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                                            <li>
                                                                <?php $get_data = $this->session->userdata('logged_in'); ?>
                                                                <a href="<?php echo base_url('performance-management/goals'); ?>">
                                                                    <figure><i class="fa fa-pencil-square-o"></i></figure>
                                                                    Goals
                                                                </a>
                                                            </li>
                                                        <?php  } ?>
                                                     

                                                        <?php if (isPayrollOrPlus() && checkIfAppIsEnabled(SCHEDULE_MODULE)) { ?>

                                                            <li>
                                                                <a href="<?= base_url('settings/shifts/manage'); ?>">
                                                                    <figure><i class="fa fa-calendar"></i></figure>Manage Shifts
                                                                </a>
                                                            </li>

                                                        <?php } ?>

                                                        <?php if (checkIfAppIsEnabled('payroll')) { ?>
                                                            <?php
                                                            $isCompanyOnPayroll = isCompanyLinkedWithGusto($session['company_detail']['sid']);
                                                            $isTermsAgreed = hasAcceptedPayrollTerms($session['company_detail']['sid']);
                                                            ?>

                                                            <?php if ($isCompanyOnPayroll && $isTermsAgreed && isPayrollOrPlus()) { ?>
                                                                <li>
                                                                    <a href="<?= base_url('payroll/dashboard'); ?>">
                                                                        <figure><i class="fa fa-dollar" aria-hidden="true"></i></figure>
                                                                        Payroll Dashboard
                                                                    </a>
                                                                </li>
                                                            <?php } ?>

                                                        <?php } ?>

                                                        <?php if (checkIfAppIsEnabled(MODULE_LMS)) { ?>
                                                            <!--  -->
                                                            <?php if ($session['employer_detail']['access_level_plus'] == 1) { ?>
                                                                <li>
                                                                    <a href="<?= base_url('lms/courses/company_courses'); ?>">
                                                                        <figure><i class="fa fa-file" aria-hidden="true"></i></figure>
                                                                        Course Management
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } ?>



                                                        <li>
                                                            <?php $get_data = $this->session->userdata('logged_in');
                                                            $sub_domain_url = db_get_sub_domain($get_data['company_detail']['sid']); ?>
                                                            <a href="<?php echo STORE_PROTOCOL_SSL . $sub_domain_url; ?>" target="_blank">
                                                                <figure><i class="fa fa-globe"></i></figure>
                                                                Career Website
                                                            </a>
                                                        </li>

                                                        
                                                    </ul>
                                                </li>
                                                <li><a href="<?= base_url('logout') ?>">Logout</a></li>
                                            </ul>
                                        <?php } else { ?>
                                            <div class="site-login-btn">
                                                <ul>
                                                    <li>&nbsp;</li>
                                                    <li><a href="<?= base_url('login') ?>">Login</a></li>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    <?php
                                    }
                                    ?>
                                </nav>
                                <!--  got questions START -->
                                <?php //if ($class == 'dashboard' || $class == 'screening_questionnaires' || $class == 'settings' || $class == 'users' || $class == 'eeo' || $class == 'demo' || $class == 'application_tracking' || $class == 'market_place' || $class == 'manual_candidate' || $class == 'private_messages' || $class == 'xml_export' || $class == 'employee_management' || $class == 'appearance') {  
                                ?>
                                <?php //}   
                                ?>
                            </div>
                        </div>
                    </div>
                </header>
                <?php if ($this->uri->segment(2) == "developers") { ?>
                    <div class="page-banner 1">
                        <figure>
                            <img src="<?= base_url() ?>assets/images/developer-new-1.jpg" alt="Banner Images">
                        </figure>
                        <div class="apibtn-wrp">
                            <div class="wrp-inner">
                                <a class="site-btn market-api" href="javascript:;">Marketplace API</a>
                                <a class="site-btn customer-api" href="javascript:;">Customer API</a>
                            </div>
                        </div>
                        <div class="down_btn"></div>
                    </div>
                <?php } else if (!$this->session->userdata('logged_in') && $this->uri->segment(1) != 'register' && $title != 'Register' && $this->uri->segment(1) != 'employee_login' && $this->uri->segment(1) != 'login' && $this->uri->segment(1) != 'forgot_password' && $this->uri->segment(1) != 'contact_us' && $this->uri->segment(1) != 'schedule_your_free_demo' && $this->uri->segment(1) != 'turnover_cost_calculator' && $this->uri->segment(1) != 'resource_page' && $this->uri->segment(2) != 'questionnaires-tutorial') { ?>

                    <?php $class_name = $this->router->fetch_class(); ?>
                    <?php if (in_array($class_name, array('affiliates'))) { ?>
                        <div class="page-banner 2 affiliates-banner">
                        <?php } else { ?>
                            <?php
                            if ($this->uri->segment(1) != "thank_you" && $this->uri->segment(1) != "demo") {
                            ?>
                                <div class="page-banner 2">
                                <?php
                            }
                                ?>
                            <?php } ?>
                            <?php if ($this->uri->segment(2) == 'eeoc-compliant') { ?>
                                <figure>
                                    <img src="<?= base_url() ?>assets/images/eeoc-banner.jpg" alt="" />
                                </figure>
                                <?php } else {
                                if ($this->uri->segment(1) != 'demo') {
                                    if ($home_page['header_video_flag'] == 1) { ?>
                                        <figure>
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $home_page['header_video']; ?>/?autoplay=1&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1"></iframe>
                                            </div>
                                            <figcaption>
                                                <h2><?php echo $home_page['header_text']; ?></h2>
                                                <h4><?php echo $home_page['header_sub_text']; ?></h4>
                                            </figcaption>
                                            <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i class="fa fa-volume-off"></i></button>
                                        </figure>
                                    <?php } else { ?>
                                        <?php
                                        if ($this->uri->segment(1) != "thank_you") {
                                        ?>
                                            <figure>
                                                <?php $class_name = $this->router->fetch_class(); ?>
                                                <?php if (in_array($class_name, array('affiliates'))) { ?>
                                                    <?php if ($validate_flag == false) { ?>
                                                        <img src="<?= base_url() ?>assets/images/affiliates/bannar-2.jpg" alt="1112" />
                                                    <?php } elseif ($validate_flag == true) { ?>
                                                        <?php if ($video_source == 'youtube_video') { ?>
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $video_url; ?>/?autoplay=1&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1"></iframe>
                                                            </div>
                                                        <?php } elseif ($video_source == 'vimeo_video') { ?>
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $video_url; ?>?autoplay=1&loop=1&muted=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                <video autoplay loop muted>
                                                                    <source src="<?php echo base_url() . 'assets/uploaded_videos/super_admin' . $video_url; ?>" type='video/mp4'>
                                                                    <p class="vjs-no-js">
                                                                        To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                                        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                                                    </p>
                                                                </video>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <figcaption class="affiliates-header-text">
                                                        <h2><?php echo $home_page['header_text']; ?></h2>
                                                        <h4><?php echo $home_page['header_sub_text']; ?></h4>
                                                        <?php if ($home_page['sign_up_btn']) { ?>
                                                            <div class="text-center">
                                                                <a href="<?= base_url('can-we-send-you-a-check-every-month') ?>" class="btn affiliate-signup-btn">Join our Affiliate Program</a>
                                                                <p class="contact-at">Got Questions Give Us a Call <a class="" href="tel:888 794-0794">(888) 794-0794</a></p>
                                                            </div>
                                                        <?php } ?>
                                                    </figcaption>
                                                <?php } else { ?>
                                                    <img data-parallax='{"z": 200}' src="<?php echo AWS_S3_BUCKET_URL . $home_page['header_banner']; ?>" alt="">
                                                    <figcaption>
                                                        <h2><?php echo $home_page['header_text']; ?></h2>
                                                        <h4><?php echo $home_page['header_sub_text']; ?></h4>
                                                    </figcaption>
                                                <?php } ?>
                                            </figure>
                                            <?php if ($validate_flag == true) { ?>
                                                <?php if ($video_source == 'youtube_video') { ?>
                                                    <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i class="fa fa-volume-off"></i></button>
                                                <?php } elseif ($video_source == 'vimeo_video') { ?>
                                                    <button class="btn-mute-unmute" style="" onclick="vimeovolumestate(this);"><i class="fa fa-volume-off"></i></button>
                                                <?php } else { ?>
                                                    <button class="btn-mute-unmute" style="" onclick="uploadedvolumestate(this);"><i class="fa fa-volume-off"></i></button>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php
                                        }
                                        ?>
                            <?php }
                                }
                            } ?>
                            <?php //$class_name = $this->router->fetch_class(); 
                            ?>
                            <?php //if ( !in_array($class_name, array('affiliates' ))) { 
                            ?>
                            <div class="down_btn"></div>
                            <?php //} 
                            ?>
                                </div>
                            <?php } else if (!$this->session->userdata('logged_in') && $this->uri->segment(2) == 'login' && $this->uri->segment(2) == 'employee_login') { ?>
                                <div class="page-banner 3">
                                    <?php if ($this->uri->segment(2) == 'eeoc-compliant') { ?>
                                        <img src="<?= base_url() ?>assets/images/eeoc-banner.jpg" alt="">
                                        <?php } else {
                                        if ($home_page['header_video_flag'] == 1) { ?>
                                            <figure>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $home_page['header_video']; ?>/?autoplay=1&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1"></iframe>
                                                </div>
                                                <figcaption>
                                                    <h2><?php echo $home_page['header_text']; ?></h2>
                                                    <h4><?php echo $home_page['header_sub_text']; ?></h4>
                                                </figcaption>
                                            </figure>
                                            <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i class="fa fa-volume-off"></i></button>
                                        <?php } else { ?>
                                            <figure>
                                                <img data-parallax='{"z": 200}' src="<?php echo AWS_S3_BUCKET_URL . $home_page['header_banner']; ?>" alt="">
                                                <figcaption>
                                                    <h2><?php echo $home_page['header_text']; ?></h2>
                                                    <h4><?php echo $home_page['header_sub_text']; ?></h4>
                                                </figcaption>
                                            </figure>
                                    <?php }
                                    } ?>
                                    <div class="down_btn"></div>
                                </div>
                            <?php } else if ($this->router->fetch_class() == 'home' && $this->router->fetch_method() == 'resource_page') { ?>
                                <?php //$this->load->view('home/resource_page_banner_partial');
                                ?>
                            <?php } else if (($this->router->fetch_class() == 'home' || $this->router->fetch_class() == 'users' && $this->uri->segment(2) == 'login') && $this->uri->segment(2) != 'questionnaires-tutorial') { ?>
                                <div class="page-banner 4">
                                    <?php if ($this->uri->segment(2) == 'eeoc-compliant') { ?>
                                        <figure><img src="<?= base_url() ?>assets/images/eeoc-banner.jpg" alt=""></figure>
                                        <?php } else {
                                        if ($home_page['header_video_flag'] == 1) { ?>
                                            <figure>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $home_page['header_video']; ?>/?autoplay=1&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1"></iframe>
                                                </div>
                                                <figcaption>
                                                    <h2><?php echo $home_page['header_text']; ?></h2>
                                                    <h4><?php echo $home_page['header_sub_text']; ?></h4>
                                                </figcaption>
                                            </figure>
                                            <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i class="fa fa-volume-off"></i></button>
                                        <?php } else { ?>
                                            <figure>
                                                <img data-parallax='{"z": 200}' src="<?php echo AWS_S3_BUCKET_URL . $home_page['header_banner']; ?>" alt="">
                                                <figcaption>
                                                    <h2><?php echo $home_page['header_text']; ?></h2>
                                                    <h4><?php echo $home_page['header_sub_text']; ?></h4>
                                                </figcaption>
                                            </figure>
                                    <?php }
                                    } ?>
                                    <div class="down_btn"></div>
                                </div>
                            <?php } ?>
                            <?php if ($this->router->fetch_class() == 'home') {
                            }
                            //echo 'Class: '.$this->router->fetch_class();
                            //echo '<br>Method: '.$this->router->fetch_method();
                            //echo  'Class: '. $this->uri->segment(2).' - '.$title;
                            ?>
                            <script src="//f.vimeocdn.com/js/froogaloop2.min.js"></script>
                            <script>
                                var isAuidoMuted;

                                $(document).ready(function() {
                                    isAuidoMuted = true;
                                });

                                function vimeovolumestate(source) {
                                    var vimeo_iframe = $('#vimeo_player')[0];
                                    var player = $f(vimeo_iframe);

                                    if (isAuidoMuted === true) {
                                        player.addEvent('ready', function() {
                                            player.api('setVolume', 0.5);
                                        });
                                        isAuidoMuted = false;

                                    } else {
                                        player.addEvent('ready', function() {
                                            player.api('setVolume', 0);
                                        });
                                        isAuidoMuted = true;
                                    }
                                }

                                function uploadedvolumestate(source) {

                                    if (isAuidoMuted === true) {
                                        $("video").prop('muted', false);
                                        isAuidoMuted = false;

                                    } else {
                                        $("video").prop('muted', true);
                                        isAuidoMuted = true;
                                    }
                                }

                                var tag = document.createElement('script');
                                tag.src = "https://www.youtube.com/iframe_api";
                                var firstScriptTag = document.getElementsByTagName('script')[0];
                                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                                var player;

                                function onYouTubeIframeAPIReady() {
                                    player = new YT.Player('player', {
                                        events: {
                                            'onReady': onPlayerReady,
                                            'onStateChange': onPlayerStateChange
                                        }
                                    });
                                }

                                function onPlayerReady(event) {
                                    event.target.playVideo();
                                    player.mute();
                                    player.setLoop(true);
                                }

                                var done = false;

                                function onPlayerStateChange(event) {
                                    if (event.data == YT.PlayerState.ENDED) {
                                        player.playVideo();
                                    }
                                }

                                function stopVideo() {
                                    player.stopVideo();
                                }

                                function muteVideo() {
                                    player.mute();
                                }

                                function unMuteVideo() {
                                    player.unMute();
                                }

                                function fChangeVolumeState(source) {
                                    $(source).find('i').each(function() {
                                        if ($(this).hasClass('fa-volume-off')) {
                                            unMuteVideo();
                                            $(this).removeClass('fa-volume-off');
                                            $(this).addClass('fa-volume-up');
                                        } else if ($(this).hasClass('fa-volume-up')) {
                                            muteVideo();
                                            $(this).removeClass('fa-volume-up');
                                            $(this).addClass('fa-volume-off');
                                        }
                                    });
                                }

                                $(document).ready(function() {
                                    $('.down_btn').each(function() {
                                        $(this).on('click', function() {
                                            $('html, body').animate({
                                                scrollTop: $('.main-content').offset().top
                                            }, 1000);
                                        });
                                    });
                                });
                                // $('.maintenance-message').show();
                            </script>
                            <?php /*
    if (
        ($class == 'dashboard' ||
        $class == 'screening_questionnaires' ||
        $class == 'settings' ||
        $class == 'users' ||
        $class == 'eeo' ||
        $class == 'demo' ||
        $class == 'application_tracking' ||
        $class == 'market_place' ||
        $class == 'portal_email_templates' ||
        $class == 'manual_candidate' ||
        $class == 'private_messages' ||
        $class == 'xml_export' ||
        $class == 'employee_management' ||
        $class == 'appearance' ||
        $class == 'hr_documents' ||
        $class == 'background_check' ||
        $class == 'my_hr_documents' ||
        $class == 'order_history' ||
        $class == 'received_documents' ||
        $class == 'order_detail' ||
        $class == 'reference_checks' ||
        $class == 'i9form' ||
        $class == 'facebook_configuration' ||
        $class == 'security_access_level' ||
        $class == 'reference_network' ||
        $class == 'expirations_manager' ||
        $class == 'reports' ||
        $class == 'approval_rights_management' ||
        $class == 'job_approval_management' ||
        $class == 'applicant_approval_management' ||
        $class == 'misc' ||
        $class == 'cc_management' ||
        $class == 'import_csv' ||
        $class == 'accurate_background' ||
        $class == 'job_listing_categories_manager' ||
        $class == 'support_tickets' ||
        $class == 'resume_database' ||
        $class == 'notification_emails' ||
        $class == 'post_on_jobs_to_career' ||
        $class == 'application_status' ||
        $class == 'interview_questionnaire' ||
        $class == 'application_tracking_system' ||
        $class == 'import_applicants_csv' ||
        $class == 'turnover_cost_calculator' ||
        $class == 'photo_gallery' ||
        $class == 'organizational_hierarchy' ||
        $class == 'video_interview_system' ||
        $class == 'attendance' ||
        $class == 'task_management') && $this->session->userdata('logged_in')
    ) {
        $cookie_name = "maintenance-message";
        $cookie_value = "true";

        if(!isset($_COOKIE[$cookie_name])) { ?>
            <script> $('.maintenance-message').show(); </script><?php
            setcookie($cookie_name, $cookie_value, time() + (86400), "/");
        }
    } */ ?>
                            <style>
                                .notification-bell {
                                    border: none;
                                    margin: 0px 20px 0 0;
                                    margin-top: -10px;
                                    font-size: 25px;
                                    border-radius: 5px;
                                    cursor: pointer;
                                    border: none;
                                    color: #fff;
                                    height: 65px;
                                    background-color: transparent;
                                    position: relative;
                                }

                                .notification-count {
                                    position: absolute;
                                    top: 4px;
                                    right: -10px;
                                    font-size: 12px;
                                    color: #fff;
                                    padding: 5px;
                                    width: 25px;
                                    height: 25px;
                                    line-height: 15px;
                                    background-color: #81b431;
                                    border-radius: 100%;
                                }

                                .count-increament {
                                    background-color: #b4052c;
                                }

                                .notify-me .dropdown-menu.dropdown-menu-wide {
                                    max-width: 350px !important;
                                    width: 100%;
                                    left: auto;
                                    right: 125px;
                                    margin-top: 12px;
                                    padding: 0;
                                    max-height: 300px;
                                    border-radius: 0;
                                    z-index: 9999;
                                    border: none;
                                }

                                .notify-me .dropdown-menu.dropdown-menu-wide {
                                    min-height: 300px;
                                    max-height: 400px;
                                }

                                .notify-me .dropdown-menu:before {
                                    content: '';
                                    position: absolute;
                                    top: -21px;
                                    left: 0;
                                    right: 0;
                                    width: 0;
                                    height: 0;
                                    margin: auto;
                                    border-left: 16px solid transparent;
                                    border-right: 16px solid transparent;
                                    border-bottom: 20px solid #fff;
                                }

                                .notify-me .dropdown-menu>li {
                                    display: inline-block;
                                    width: 100%;
                                    vertical-align: top;
                                    margin-left: 0;
                                }

                                .notify-me .dropdown-menu>li a {
                                    display: block;
                                    float: left;
                                    width: 100%;
                                    text-transform: capitalize;
                                    padding: 25px 10px;
                                    border: 2px solid #e0e0e0;
                                    margin: -1px 0;
                                    background-color: #FFFFFF;
                                    color: #000000;
                                }

                                .notify-me .dropdown-menu>li a:hover {
                                    background-color: #81b431;
                                    color: #ffffff;
                                }

                                .notify-me .dropdown-menu:before {
                                    content: '';
                                    position: absolute;
                                    top: -21px;
                                    right: 17px;
                                    width: 0;
                                    height: 0;
                                    border-left: 16px solid transparent;
                                    border-right: 16px solid transparent;
                                    border-bottom: 20px solid #fff;
                                }

                                .faa-shake,
                                .faa-shake.animated,
                                .faa-shake.animated-hover:hover {
                                    -webkit-animation: bell-shake 8s ease infinite;
                                    animation: bell-shake 8s ease infinite;
                                }

                                @-webkit-keyframes bell-shake {
                                    1% {
                                        -webkit-transform: rotateZ(15deg);
                                        transform-origin: 50% 10%;
                                    }

                                    2% {
                                        -webkit-transform: rotateZ(-15deg);
                                        transform-origin: 50% 10%;
                                    }

                                    3% {
                                        -webkit-transform: rotateZ(20deg);
                                        transform-origin: 50% 10%;
                                    }

                                    4% {
                                        -webkit-transform: rotateZ(-20deg);
                                        transform-origin: 50% 10%;
                                    }

                                    5% {
                                        -webkit-transform: rotateZ(15deg);
                                        transform-origin: 50% 10%;
                                    }

                                    6% {
                                        -webkit-transform: rotateZ(-15deg);
                                        transform-origin: 50% 10%;
                                    }

                                    7% {
                                        -webkit-transform: rotateZ(0);
                                        transform-origin: 50% 10%;
                                    }

                                    100% {
                                        -webkit-transform: rotateZ(0);
                                        transform-origin: 50% 10%;
                                    }
                                }

                                @-moz-keyframes bell-shake {
                                    1% {
                                        -moz-transform: rotateZ(15deg);
                                        transform-origin: 50% 0%;
                                    }

                                    2% {
                                        -moz-transform: rotateZ(-15deg);
                                        transform-origin: 50% 0%;
                                    }

                                    3% {
                                        -moz-transform: rotateZ(20deg);
                                        transform-origin: 50% 0%;
                                    }

                                    4% {
                                        -moz-transform: rotateZ(-20deg);
                                        transform-origin: 50% 0%;
                                    }

                                    5% {
                                        -moz-transform: rotateZ(15deg);
                                        transform-origin: 50% 0%;
                                    }

                                    6% {
                                        -moz-transform: rotateZ(-15deg);
                                        transform-origin: 50% 0%;
                                    }

                                    7% {
                                        -moz-transform: rotateZ(0);
                                        transform-origin: 50% 0%;
                                    }

                                    100% {
                                        -moz-transform: rotateZ(0);
                                        transform-origin: 50% 0%;
                                    }
                                }

                                @keyframes bell-shake {
                                    1% {
                                        transform: rotateZ(15deg);
                                        transform-origin: 50% 0%;
                                    }

                                    2% {
                                        transform: rotateZ(-15deg);
                                        transform-origin: 50% 0%;
                                    }

                                    3% {
                                        transform: rotateZ(20deg);
                                        transform-origin: 50% 0%;
                                    }

                                    4% {
                                        transform: rotateZ(-20deg);
                                        transform-origin: 50% 0%;
                                    }

                                    5% {
                                        transform: rotateZ(15deg);
                                        transform-origin: 50% 0%;
                                    }

                                    6% {
                                        transform: rotateZ(-15deg);
                                        transform-origin: 50% 0%;
                                    }

                                    7% {
                                        transform: rotateZ(0);
                                        transform-origin: 50% 0%;
                                    }

                                    100% {
                                        transform: rotateZ(0);
                                        transform-origin: 50% 0%;
                                    }
                                }

                                @media only screen and (max-width: 576px) {
                                    .notify-me {
                                        text-align: center !important;
                                        float: none !important;
                                    }

                                    .notify-me .fa {
                                        color: #ffffff !important;
                                    }

                                    .notify-me .dropdown-menu.dropdown-menu-wide {
                                        right: 10px !important;
                                        top: 208px !important;
                                    }
                                }
                            </style>
                        <?php } else { ?>
                            <?php $this->load->view('onboarding/on_boarding_header'); ?>
                        <?php } ?>
                        <?php
                        $API_TOKENS = $this->session->userdata('API_TOKENS');
                        ?>
                        <script type="text/javascript">
                            //
                            window.onerror = function(message, source, lineno, colno, error) {
                                // 
                                var ErrorOBJ = {
                                    ErrorMessage: message,
                                    OnPage: source,
                                    Page: window.location.href,
                                    LineNumber: lineno,
                                    UserAgent: navigator.userAgent,
                                    OccurrenceTime: new Date().toLocaleString(),
                                    ErrorLogTime: ''
                                };
                                //
                                var API_KEY = "<?php echo $API_TOKENS; ?>";
                                var API_URL = "<?php echo GetErrorUrl(); ?>";
                                //
                                var o = {};
                                o.error = JSON.stringify(ErrorOBJ);

                                xhr = $.ajax({
                                        method: "POST",
                                        url: "<?php echo base_url('reports/error_report'); ?>",
                                        data: ErrorOBJ
                                    })
                                    .done(function(resp) {
                                        console.log("Report Error");
                                    })
                                    .error();
                            }
                        </script>
                    <?php } ?>