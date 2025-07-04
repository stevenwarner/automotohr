<!doctype html>
<html>

<head>
    <?php $class = strtolower($this->router->fetch_class()); ?>
    <?php $method = $this->router->fetch_method(); ?>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" /> -->
    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= image_url('favicon_io'); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= image_url('favicon_io'); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= image_url('favicon_io'); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= image_url('favicon_io'); ?>/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/font-awesome.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/alertifyjs/css/alertify.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/alertifyjs/css/themes/default.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/jquery.datetimepicker.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/select2.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/theme-2021.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/responsive.css') ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo _m(base_url('assets/2022/css/app'), 'css', getAssetTag('1.0.3')) ?>">

    <script src="<?php echo base_url('assets/employee_panel/js/jquery-1.11.3.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.validate.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/additional-methods.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.datetimepicker.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/alertifyjs/alertify.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/functions.js') ?>"></script>

    <?php if ($class != 'hr_documents_management ' && $method != 'sign_hr_document') { ?>
        <script src="<?php echo base_url('assets/ckeditor/ckeditor.js') ?>"></script>
    <?php } ?>
    <!--  -->
    <?php if (in_array('timeoff', $this->uri->segment_array()) || in_array('employee_management_system', $this->uri->segment_array())  || in_array('dashboard', $this->uri->segment_array())) { ?>
        <?php $this->load->view('timeoff/style'); ?>
        <link rel="stylesheet" href="<?= base_url('assets/timeoff/css/blue' . ($GLOBALS['minified_version']) . '.css?v=' . (ENVIRONMENT == 'development' ? $GLOBALS['asset_version'] : '1.0') . ''); ?>" />
    <?php } ?>

    <!--  -->
    <?php if (in_array('performance-management', $this->uri->segment_array())) { ?>
        <?php $this->load->view("{$pp}styles"); ?>
    <?php } ?>


    <script src="<?php echo base_url('assets/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/select2.js') ?>"></script>

    <!-- Bable -->
    <script src="https://unpkg.com/@babel/standalone@7.13.10/babel.min.js"></script>
    <!-- Bable -->

    <?php if (isset($PageCSS)) : ?>
        <!-- Stylesheets -->
        <?= GetCss($PageCSS); ?>
    <?php endif; ?>
    <?php if (isset($appCSS)) {
        echo $appCSS;
    } ?>



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
    <div class="wrapper-outer">
        <header class="header <?= in_array('iframe', $this->uri->segment_array()) ? 'hidden' : ''; ?>">
            <div class="container<?= strtolower($this->router->fetch_class()) == 'payroll' ? '-fluid' : ''; ?>">
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <div class="logo">
                            <?php if (isset($session['company_detail']) && $session['portal_detail']['enable_company_logo'] == 1) { ?>
                                <a href="javascript:;"><img src="<?php echo AWS_S3_BUCKET_URL . $session['company_detail']['Logo']; ?>"></a>
                            <?php } else { ?>
                                <!-- <a href="javascript:;"><img src="<?php //echo base_url('assets/employee_panel/images/logo.jpg'); 
                                                                        ?>"></a> -->
                            <?php } ?>
                            <p><?php echo $session['company_detail']['CompanyName']; ?></p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 pull-right cs-full-width">
                        <div class="row">
                            <nav class="navbar navigation">
                                <div class="navbar-header">
                                    <button type="button" data-target="#main_nav" data-toggle="collapse" class="navbar-toggle">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="menu-title">menu</span>
                                    </button>
                                </div>

                                <div id="main_nav" class="collapse navbar-collapse">

                                    <ul class="nav navbar-nav pull-right">
                                        <li>
                                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">Quick Links&nbsp;&nbsp;<span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="<?php echo base_url('employee_management_system'); ?>"><i class="fa fa-fw fa-th"></i>&nbsp;&nbsp;Dashboard</a></li>
                                                <?php if ((isset($employerData) && $employerData['access_level'] != 'Employee') || (isset($employee) && $employee['access_level'] != 'Employee')) { ?>
                                                    <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-fw fa-th"></i>&nbsp;&nbsp;Management Dashboard</a></li>
                                                <?php } ?>

                                                <?php if (in_array('login_password', $security_details) || check_access_permissions_for_view($security_details, 'login_password')) { ?>
                                                    <li><a href="<?php echo base_url('login_password'); ?>"><i class="fa fa-fw fa-unlock"></i>&nbsp;&nbsp;Login Credentials</a></li>
                                                <?php } ?>

                                                <li><a href="<?php echo base_url('hr_documents_management/my_documents'); ?>"><i class="fa fa-fw fa-file"></i>&nbsp;&nbsp;Documents</a></li>
                                                <li><a href="<?php echo base_url('calendar/my_events'); ?>"><i class="fa fa-fw fa-calendar"></i>&nbsp;&nbsp;Calendar</a></li>
                                                <!-- <li><a href="<?php //echo base_url('emergency_contacts'); 
                                                                    ?>"><i class="fa fa-fw fa-ambulance"></i>&nbsp;&nbsp;Emergency Contacts</a></li> -->
                                                <!-- <li><a href="<?php //echo base_url('drivers_license_info'); 
                                                                    ?>"><i class="fa fa-fw fa-automobile"></i>&nbsp;&nbsp;Drivers License Info</a></li> -->
                                                <!-- <li><a href="<?php //echo base_url('occupational_license_info'); 
                                                                    ?>"><i class="fa fa-fw fa-industry"></i>&nbsp;&nbsp;Occupational License Info</a></li> -->
                                                <!-- <li><a href="<?php //echo base_url('dependants'); 
                                                                    ?>"><i class="fa fa-fw fa-child"></i>&nbsp;&nbsp;Dependants</a></li> -->
                                                <!-- <li><a href="<?php //echo base_url('direct_deposit'); 
                                                                    ?>"><i class="fa fa-fw fa-bank"></i>&nbsp;&nbsp;Direct Deposit</a></li> -->
                                                <!--<li><a href="<?php /*echo base_url('my_learning_center'); */ ?>">Learning and Training Center</a></li>-->

                                                <li><a href="<?php echo base_url('e_signature'); ?>"><i class="fa fa-fw fa-check"></i>&nbsp;&nbsp;E Signature</a></li>
                                                <li><a href="<?php echo base_url('my_referral_network'); ?>"><i class="fa fa-fw fa-link"></i>&nbsp;&nbsp;My Referral Network</a></li>

                                                <?php $incident = $this->session->userdata('incident_config');
                                                if ($incident > 0) { ?>
                                                    <li><a href="<?php echo base_url('incident_reporting_system'); ?>"><i class="fa fa-fw fa-file-text"></i>&nbsp;&nbsp;Incidents</a></li>
                                                    <!-- <li><a href="<?php //echo base_url('incident_reporting_system/assigned_incidents'); 
                                                                        ?>"><i class="fa fa-fw fa-file-text"></i>&nbsp;&nbsp;Assigned Incidents</a></li> -->
                                                <?php } ?>

                                                <li><a href="<?php echo base_url('private_messages'); ?>"><i class="fa fa-fw fa-envelope"></i>&nbsp;&nbsp;Private Messages</a></li>
                                                <li><a href="<?php echo base_url('list_announcements'); ?>"><i class="fa fa-fw fa-bullhorn"></i>&nbsp;&nbsp;Announcements</a></li>
                                                <li><a href="<?php echo base_url('learning_center/my_learning_center'); ?>"><i class="fa fa-fw fa-graduation-cap"></i>&nbsp;&nbsp;My Learning Center</a></li>

                                                <?php
                                                if (isset($session['safety_sheet_flag']) &&  $session['safety_sheet_flag'] > 0) { ?>
                                                    <li><a href="<?php echo base_url('safety_sheets'); ?>"><i class="fa fa-fw fa-fire-extinguisher"></i>&nbsp;&nbsp;Safety Sheets</a></li>
                                                <?php } ?>

                                                <?php $comply_status = $session["company_detail"]["complynet_status"];
                                                $employee_status = isset($employerData) ? $employerData["complynet_status"] : $employee["complynet_status"];
                                                $access_level  = isset($employee) ? $employee['access_level'] : $employerData['access_level'];
                                                if (check_access_permissions_for_view($security_details, 'complynet') && $comply_status && $access_level != 'Employee' && $employee_status) { ?>
                                                    <?php $complyNetLink = getComplyNetLink($this->session->userdata('logged_in')['company_detail']['sid'], $this->session->userdata('logged_in')['employer_detail']['sid']); ?>
                                                    <?php if ($complyNetLink) { ?>
                                                        <li><a href="<?= base_url('cn/redirect'); ?>"><i class=" fa fa-fw fa-fire-extinguisher"></i>&nbsp;&nbsp;ComplyNet</a></li>
                                                    <?php } ?>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li><a href="<?php echo base_url('logout'); ?>"><i class="fa fa-fw fa-lock"></i>&nbsp;&nbsp;Logout</a></li>
                                    </ul>

                                    <div class="pull-right notify-me">
                                        <button class="notification-bell" data-toggle="dropdown">
                                            <i class="fa fa-bell" style="color: #0000ff;"></i>
                                            <span class="notification-count count-increament" id="js-notification-count">0</span>
                                        </button>
                                        <ul role="menu" class="dropdown-menu dropdown-menu-wide" id="js-notification-box"></ul>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->

        <?php $this->load->view("main/employee_strip"); ?>
        <style>
            @media only screen and (max-width: 576px) {
                .cs-full-width {
                    width: 100%;
                }

                .cs-btn-setting {
                    width: 100%;
                    margin-bottom: 10px;

                }

                .cs-word-break {
                    word-wrap: break-word;
                }
            }

            .notify-me .dropdown-menu>li a:hover {
                background-color: #0000ff !important;
                color: #ffffff;
            }
        </style>
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
                }
            }
        </style>