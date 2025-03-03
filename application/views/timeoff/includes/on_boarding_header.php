<!doctype html>
<html>

<head>
    <?php $class = strtolower($this->router->fetch_class()); ?>
    <?php $method = $this->router->fetch_method(); ?>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png?v=<?= time(); ?>" type="image/x-icon" /> -->
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/responsive.css') ?>">

    <?php if (in_array('timeoff', $this->uri->segment_array()) || in_array('employee_management_system', $this->uri->segment_array())  || in_array('dashboard', $this->uri->segment_array())) { ?>
        <?php $this->load->view('timeoff/style'); ?>
        <link rel="stylesheet" href="<?= base_url('assets/timeoff/css/blue' . ($GLOBALS['minified_version']) . '.css?v=' . (ENVIRONMENT == 'development' ? $GLOBALS['asset_version'] : '1.0') . ''); ?>" />
    <?php } ?>

    <script src="<?php echo base_url('assets/employee_panel/js/jquery-1.11.3.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/additional-methods.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.datetimepicker.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/alertifyjs/alertify.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/functions.js') ?>"></script>

    <script src="<?php echo base_url('assets/ckeditor/ckeditor.js') ?>"></script>
    <!--  -->
    <script src="<?php echo base_url('assets/js/select2.js') ?>"></script>
</head>

<body>
    <div class="wrapper-outer">

        <header class="header <?= in_array('iframe', $this->uri->segment_array()) ? 'hidden' : ''; ?>">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <div class="logo">
                            <?php if (isset($session['company_detail']) && $session['portal_detail']['enable_company_logo'] == 1) { ?>
                                <a href="javascript:;"><img src="<?php echo AWS_S3_BUCKET_URL . $session['company_detail']['Logo']; ?>"></a>
                            <?php } else { ?>
                                <!-- <a href="javascript:;"><img src="<?php //echo base_url('assets/employee_panel/images/logo.jpg'); 
                                                                        ?>"></a> -->
                            <?php } ?>
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
                                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">Quick
                                                Links&nbsp;&nbsp;<span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="<?php echo base_url('employee_management_system'); ?>"><i class="fa fa-fw fa-th"></i>&nbsp;&nbsp;Dashboard</a></li>
                                                <?php if ((isset($employerData) && $employerData['access_level'] != 'Employee') || (isset($employee) && $employee['access_level'] != 'Employee')) { ?>
                                                    <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-fw fa-th"></i>&nbsp;&nbsp;Management
                                                            Dashboard</a></li>
                                                <?php } ?>

                                                <?php if (in_array('login_password', $security_details) || check_access_permissions_for_view($security_details, 'login_password')) { ?>
                                                    <li><a href="<?php echo base_url('login_password'); ?>"><i class="fa fa-fw fa-unlock"></i>&nbsp;&nbsp;Login
                                                            Credentials</a></li>
                                                <?php } ?>

                                                <li><a href="<?php echo base_url('hr_documents_management/my_documents'); ?>"><i class="fa fa-fw fa-file"></i>&nbsp;&nbsp;Documents</a></li>
                                                <li><a href="<?php echo base_url('calendar/my_events'); ?>"><i class="fa fa-fw fa-calendar"></i>&nbsp;&nbsp;Calendar</a>
                                                </li>
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

                                                <li><a href="<?php echo base_url('e_signature'); ?>"><i class="fa fa-fw fa-check"></i>&nbsp;&nbsp;E Signature</a>
                                                </li>
                                                <li><a href="<?php echo base_url('my_referral_network'); ?>"><i class="fa fa-fw fa-link"></i>&nbsp;&nbsp;My Referral
                                                        Network</a></li>

                                                <?php $incident = $this->session->userdata('incident_config');
                                                if ($incident > 0) { ?>
                                                    <li><a href="<?php echo base_url('incident_reporting_system'); ?>"><i class="fa fa-fw fa-file-text"></i>&nbsp;&nbsp;Incidents</a>
                                                    </li>
                                                    <!-- <li><a href="<?php //echo base_url('incident_reporting_system/assigned_incidents'); 
                                                                        ?>"><i class="fa fa-fw fa-file-text"></i>&nbsp;&nbsp;Assigned Incidents</a></li> -->
                                                <?php } ?>

                                                <li><a href="<?php echo base_url('private_messages'); ?>"><i class="fa fa-fw fa-envelope"></i>&nbsp;&nbsp;Private
                                                        Messages</a></li>
                                                <li><a href="<?php echo base_url('list_announcements'); ?>"><i class="fa fa-fw fa-bullhorn"></i>&nbsp;&nbsp;Announcements</a>
                                                </li>
                                                <li><a href="<?php echo base_url('learning_center/my_learning_center'); ?>"><i class="fa fa-fw fa-graduation-cap"></i>&nbsp;&nbsp;My
                                                        Learning Center</a></li>

                                                <?php
                                                if (isset($session['safety_sheet_flag']) &&  $session['safety_sheet_flag'] > 0) { ?>
                                                    <li><a href="<?php echo base_url('safety_sheets'); ?>"><i class="fa fa-fw fa-fire-extinguisher"></i>&nbsp;&nbsp;Safety
                                                            Sheets</a></li>
                                                <?php } ?>

                                                <?php $comply_status = $session["company_detail"]["complynet_status"];
                                                $employee_status = isset($employerData) ? $employerData["complynet_status"] : $employee["complynet_status"];
                                                $access_level  = isset($employee) ? $employee['access_level'] : $employerData['access_level'];
                                                if (check_access_permissions_for_view($security_details, 'complynet') && $comply_status && $access_level != 'Employee' && $employee_status) { ?>
                                                    <li><a href="<?php echo base_url('complynet'); ?>"><i class="fa fa-fw fa-fire-extinguisher"></i>&nbsp;&nbsp;ComplyNet</a>
                                                    </li>
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

        <div class="emp-info-strip">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="emp-info-box">
                            <div class="figure">
                                <?php if (isset($employee['profile_picture']) && !empty($employee['profile_picture'])) { ?>
                                    <div class="container-fig">
                                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $employee['profile_picture']; ?>">
                                    </div>
                                <?php } else { ?>
                                    <span><?php echo substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1); ?></span>
                                <?php } ?>
                            </div>
                            <div class="text text-white">
                                <h3>
                                    <?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>
                                    <span><?php echo $employee['access_level']; ?></span>
                                </h3>
                               
                                <span>
                                    <?= get_user_anniversary_date(
                                        $employee['joined_at'],
                                        $employee['registration_date'],
                                        $employee['rehire_date']
                                    );
                                    ?>
                                </span><br>
                                <span>Employee Type:
                                        <?= formateEmployeeJobType($employee['employee_type']); ?>
                                    </span>
                                <!--<p>Administrator at ABC</p>-->
                                <ul class="contact-info">
                                    <?php if (!empty($employee['PhoneNumber'])) { ?>
                                        <li><i class="fa fa-phone"></i> <?php echo $employee['PhoneNumber']; ?></li>
                                    <?php } ?>
                                    <?php if (!empty($employee['email'])) { ?>
                                        <li><i class="fa fa-envelope"></i> <?php echo $employee['email']; ?></li>
                                    <?php } ?>
                                    <li><?php echo $session['company_detail']['CompanyName']; ?></li>
                                </ul>
                            </div>
                            <div class="btn-link-wrp">
                                <?php if ((isset($employerData) && $employerData['access_level'] != 'Employee') || (isset($employee) && $employee['access_level'] != 'Employee')) { ?>
                                    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-info btn-orange" style="-webkit-border-radius: 5px !important;"> Management Dashboard </a>
                                <?php } ?>
                                <?php if ($this->uri->segment(1) == 'employee_management_system') { ?>
                                    <a href="<?php echo base_url('my_profile'); ?>" class="btn btn-info btn-orange"><i class="fa fa-pencil"></i> my profile</a>
                                <?php } else { ?>
                                    <a href="<?php echo base_url('employee_management_system'); ?>" class="btn btn-info btn-orange">EMS Dashboard</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>