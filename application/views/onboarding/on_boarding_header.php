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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.css?v=1.0') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/responsive.css') ?>">

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

    <?= $pageCSS ? GetCss($pageCSS) : ""; ?>
    <?= $appCSS ?? ""; ?>
    <?= bundleCSS([
        "v1/app/css/global",
    ], "public/v1/app/", "global", true); ?>

<body>
    <div class="wrapper-outer">
        <?php if (isset($applicant)) { ?>
            <header class="header ">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 ">
                            <div class="logo">
                                <?php if (isset($session['company_detail']) && $session['portal_detail']['enable_company_logo'] == 1) { ?>

                                    <a href="javascript:;"><img src="<?php echo AWS_S3_BUCKET_URL . $session['company_detail']['Logo']; ?>"></a>
                                <?php } else { ?>
                                    <!-- <a href="javascript:;"><img src="<?php //echo base_url(); 
                                                                            ?>assets/employee_panel/images/logo.jpg"></a> -->
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 pull-right  ">
                            <div class="row">
                                <nav class="navbar navigation">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <div class="navbar-header">
                                        <button type="button" data-target="#main_nav" data-toggle="collapse" class="navbar-toggle">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="menu-title">menu</span>
                                        </button>
                                    </div>
                                    <!-- Collection of nav links and other content for toggling -->
                                    <div id="main_nav" class="collapse navbar-collapse">

                                        <ul class="nav navbar-nav pull-right">
                                            <li>
                                                <a data-toggle="dropdown" href="#" class="dropdown-toggle">Quick Links<span class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="<?php echo base_url('onboarding/getting_started/' . $unique_sid); ?>"><i class="fa fa-fw fa-th"></i>&nbsp;&nbsp;Getting Started</a></li>
                                                    <li><a href="<?php echo base_url('onboarding/my_profile/' . $unique_sid); ?>"><i class="fa fa-fw fa-user"></i>&nbsp;&nbsp;My Profile</a></li>
                                                    <li><a href="<?php echo base_url('onboarding/documents/' . $unique_sid); ?>"><i class="fa fa-fw fa-file"></i>&nbsp;&nbsp;Documents</a></li>
                                                    <li><a href="<?php echo base_url('onboarding/calendar/' . $unique_sid); ?>"><i class="fa fa-fw fa-calendar"></i>&nbsp;&nbsp;Calendar</a></li>
                                                    <li><a href="<?php echo base_url('onboarding/emergency_contacts/' . $unique_sid); ?>"><i class="fa fa-fw fa-ambulance"></i>&nbsp;&nbsp;Emergency Contacts</a></li>
                                                    <li><a href="<?php echo base_url('onboarding/license_info/' . $unique_sid); ?>"><i class="fa fa-fw fa-automobile"></i>&nbsp;&nbsp;License Info</a></li>
                                                    <li><a href="<?php echo base_url('onboarding/dependents/' . $unique_sid); ?>"><i class="fa fa-fw fa-child"></i>&nbsp;&nbsp;Dependents</a></li>
                                                    <li><a href="<?php echo base_url('onboarding/direct_deposit/' . $unique_sid); ?>"><i class="fa fa-fw fa-bank"></i>&nbsp;&nbsp;Direct Deposit</a></li>
                                                    <!--<li><a href="<?php /*echo base_url('onboarding/learning_center/' . $unique_sid); */ ?>">Learning and Training Center</a></li>-->
                                                    <li><a href="<?php echo base_url('onboarding/e_signature/' . $unique_sid); ?>"><i class="fa fa-fw fa-check"></i>&nbsp;&nbsp;E Signature</a></li>

                                                    <?php if ($company_eeoc_form_status == 1) { ?>
                                                        <li><a href="<?php echo base_url('onboarding/eeoc_form/' . $unique_sid); ?>"><i class="fa fa-fw fa-file"></i>&nbsp;&nbsp;EEOC Form</a></li>
                                                    <?php } ?>

                                                    <li><a href="<?php echo base_url('onboarding/my_credentials/' . $unique_sid); ?>"><i class="fa fa-fw fa-id-card"></i>&nbsp;&nbsp;My Credentials</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <!-- <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <div class="title-text text-center">
                                <?php //echo $title; 
                                ?>
                            </div>
                        </div> -->
                    </div>
                </div>
            </header>
            <div class="emp-info-strip <?= in_array('iframe', $this->uri->segment_array()) ? 'hidden' : ''; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="emp-info-box">
                                <div class="figure">
                                    <?php if (isset($applicant['pictures']) && !empty($applicant['pictures'])) { ?>
                                        <div class="container-fig">
                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $applicant['pictures']; ?>">
                                        </div>
                                    <?php } else { ?>
                                        <span><?php echo substr($applicant['first_name'], 0, 1) . substr($applicant['last_name'], 0, 1); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="text text-white">
                                    <h3>
                                        <?php echo $applicant['first_name'] . ' ' . $applicant['last_name']; ?>
                                        <span>Applicant</span>
                                    </h3>
                                    <!--<p>Administrator at ABC</p>-->
                                    <ul class="contact-info">
                                        <?php if (!empty($applicant['phone_number'])) { ?>
                                            <li><i class="fa fa-phone"></i> <?php echo $applicant['phone_number']; ?></li>
                                        <?php } ?>

                                        <?php if (!empty($applicant['email'])) { ?>
                                            <li><i class="fa fa-envelope"></i> <?php echo $applicant['email']; ?></li>
                                        <?php } ?>

                                    </ul>
                                </div>
                                <div class="btn-link-wrp">
                                    <a href="<?php echo base_url('onboarding/my_profile/' . $unique_sid); ?>" class="btn btn-info btn-orange" style="-webkit-border-radius: 5px !important;"><i class="fa fa-pencil"></i> my profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="arrow-links">
                        <?php
                        if ($company_eeoc_form_status == 1) {
                            $width = round(100 / 10, 2);
                        } else {
                            $width = round(100 / 9, 2);
                        }
                        $url_segment = $this->uri->segment(2);
                        ?>
                        <ul>
                            <li class="<?php echo $url_segment == 'getting_started' ? 'active' : ''; ?> <?php echo $complete_steps['getting_started'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/getting_started/' . $unique_sid); ?>">
                                    <span>Step 1</span>Getting Started <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>
                            <li class="<?php echo $url_segment == 'my_profile' ? 'active' : ''; ?> <?php echo $complete_steps['my_profile'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/my_profile/' . $unique_sid); ?>">
                                    <span>Step 2</span>Profile <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>
                            <li class="<?php echo $url_segment == 'documents' ? 'active' : ''; ?> <?php echo $complete_steps['documents'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/documents/' . $unique_sid); ?>">
                                    <span>Step 3</span>Documents <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>
                            <li class="<?php echo $url_segment == 'emergency_contacts' ? 'active' : ''; ?> <?php echo $complete_steps['emergency_contacts'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/emergency_contacts/' . $unique_sid); ?>">
                                    <span>Step 8</span>Emergency Contacts <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>
                            <li class="<?php echo $url_segment == 'license_info' ? 'active' : ''; ?> <?php echo $complete_steps['license_info'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/license_info/' . $unique_sid); ?>">
                                    <span>Step 5</span>License Info. <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>
                            <li class="<?php echo $url_segment == 'dependents' ? 'active' : ''; ?> <?php echo $complete_steps['dependents'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/dependents/' . $unique_sid); ?>">
                                    <span>Step 7</span>Dependents <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>
                            <li class="<?php echo $url_segment == 'direct_deposit' ? 'active' : ''; ?> <?php echo $complete_steps['direct_deposit'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/direct_deposit/' . $unique_sid); ?>">
                                    <span>Step 6</span>Direct Deposit <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>
                            <li class="<?php echo $url_segment == 'e_signature' ? 'active' : ''; ?> <?php echo $complete_steps['e_signature'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/e_signature/' . $unique_sid); ?>">
                                    <span>Step 4</span>E-Signature <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>

                            <?php if ($company_eeoc_form_status == 1) { ?>
                                <li class="<?php echo $url_segment == 'eeoc_form' ? 'active' : ''; ?> <?php echo $complete_steps['eeoc_form'] > 0 ? 'done' : ''; ?>" style="width: <?php echo $width ?>%;">
                                    <a href="<?php echo base_url('onboarding/eeoc_form/' . $unique_sid); ?>">
                                        <span>Step 9</span>EEOC Form <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                    </a>
                                </li>
                            <?php } ?>

                            <li class="<?php echo $url_segment == 'my_credentials' ? 'active' : ''; ?>" style="width: <?php echo $width ?>%;">
                                <a href="<?php echo base_url('onboarding/my_credentials/' . $unique_sid); ?>">
                                    <span>Finish</span>Login Credentials <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } else if (isset($employee)) { ?>
            <header class="header <?= in_array('iframe', $this->uri->segment_array()) ? 'hidden' : ''; ?>">
                <div class="container<?= strtolower($this->router->fetch_class()) == 'performance_management' || strtolower($this->router->fetch_class()) == 'payroll' ? '-fluid' : ''; ?>">
                    <div class="row">
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <div class="logo">
                                <?php if (isset($session['company_detail']) && $session['portal_detail']['enable_company_logo'] == 1) { ?>
                                    <a href="javascript:;"><img src="<?php echo AWS_S3_BUCKET_URL . $session['company_detail']['Logo']; ?>"></a>
                                    <p><?= $session['company_detail']['CompanyName']; ?></p>
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
                                                            <li><a href="<?= base_url('cn/redirect'); ?>"><i class="fa fa-fw fa-fire-extinguisher"></i>&nbsp;&nbsp;ComplyNet</a></li>
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


            <div class="emp-info-strip <?= in_array('iframe', $this->uri->segment_array()) ? 'hidden' : ''; ?> <?= isset($hide_employer_section) ? 'hidden' : ''; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-sm-8">
                            <div class="emp-info-box">
                                <div class="figure">
                                    <?php if (isset($employee['profile_picture']) && !empty($employee['profile_picture'])) { ?>
                                        <div class="container-fig">
                                            <img class="img-responsive" src="<?= getImageUrl($employee['profile_picture']); ?>" alt="<?= remakeEmployeeName($employee); ?>">
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
                                    </span>
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
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <?php $this->load->view('v1/attendance/partials/clocks/blue/welcome_block'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                        <div class="col-lg-12 text-right">

                            <?php if ($employee['is_executive_admin'] == 0) { ?>
                                <div>
                                    <?php if ((isset($employerData) && $employerData['access_level'] != 'Employee') || (isset($employee) && $employee['access_level'] != 'Employee')) { ?>
                                        <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-orange"><i class="fa fa-cogs"></i> Management Dashboard </a>
                                    <?php } ?>
                                    <?php if ($this->uri->segment(1) == 'employee_management_system' || $this->uri->segment(1) == 'dashboard') { ?>
                                        <a href="<?php echo base_url('my_profile'); ?>" class="btn btn-orange"><i class="fa fa-pencil"></i> my profile</a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url('employee_management_system'); ?>" class="btn btn-orange">EMS Dashboard</a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
        ?>
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