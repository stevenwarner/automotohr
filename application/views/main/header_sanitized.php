<!doctype html>
<html lang="en">

<head>
    <?php $class = strtolower($this->router->fetch_class()); ?>
    <?php $method = $this->router->fetch_method(); ?>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= main_url("public/v1/plugins/bootstrap/css/bootstrap.min.css?v=3.0"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/v1/plugins/fontawesome/4/font-awesome.min.css?v=3.0') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.min.css?v=3.0') ?>">
    <?= bundleCSS([
        "v1/app/css/global",
    ], "public/v1/app/", "global", false); ?>
    <?= $pageCSS ? GetCss($pageCSS) : ''; ?>
    <!-- CSS bundles -->
    <?= $appCSS ?? ""; ?>

    <div class="wrapper-outer">
        <!--  -->
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
                        <div class="logo">
                            <?php if (isset($session['company_detail']) && $session['portal_detail']['enable_company_logo'] == 1) { ?>
                                <a href="javascript:;">
                                    <img src="<?= getImageURL($session['company_detail']['Logo']); ?>" alt="company logo" />
                                </a>
                                <p><?= $session['company_detail']['CompanyName']; ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-10 col-md-10 col-lg-10 pull-right cs-full-width">
                        <div class="row">
                            <nav class="navbar navigation">
                                <div class="navbar-header">
                                    <button type="button" data-target="#main_nav" data-toggle="collapse" class="navbar-toggle">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="menu-title">Menu</span>
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
                                                <li><a href="<?php echo base_url('e_signature'); ?>"><i class="fa fa-fw fa-check"></i>&nbsp;&nbsp;E Signature</a></li>
                                                <li><a href="<?php echo base_url('my_referral_network'); ?>"><i class="fa fa-fw fa-link"></i>&nbsp;&nbsp;My Referral Network</a></li>

                                                <?php $incident = $this->session->userdata('incident_config');
                                                if ($incident > 0) { ?>
                                                    <li><a href="<?php echo base_url('incident_reporting_system'); ?>"><i class="fa fa-fw fa-file-text"></i>&nbsp;&nbsp;Incidents</a></li>
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
        <?php $this->load->view("main/employee_strip"); ?>