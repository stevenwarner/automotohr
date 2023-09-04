<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/font-awesome.css') ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/employee_panel/alertifyjs/css/alertify.min.css') ?>" />
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/employee_panel/alertifyjs/css/themes/default.min.css') ?>" />
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/employee_panel/css/jquery.datetimepicker.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/select2.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/responsive.css') ?>">
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-1.11.3.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.validate.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/additional-methods.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.datetimepicker.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/alertifyjs/alertify.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/functions.js') ?>"></script>
    <?php if ($this->uri->segment(2) != 'sign_hr_document' && $this->uri->segment(2) != 'my_offer_letter') {?>
    <script src="<?php echo base_url('assets/ckeditor/ckeditor.js') ?>"></script>
    <?php }?>
    <script src="<?php echo base_url('assets/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/select2.js') ?>"></script>
    <style>
        @media only screen and (max-width: 576px){
        .arrow-links ul li a:before{
        display: none !important;
        }
        .arrow-links ul li a:after{
            display: none !important;
        }
        .col-xs-12{
            padding:0px;
        }
        .row{
            margin:0px !important;
        }
        .ajs-dialog{
            width:300px !important;
        }
        .alertify .ajs-dialog{
            margin: 0px !important;
            margin-top: 80px !important;
        }
        .ajs-dimmer{
            width:376px !important;
        }
        .cs_verflow_setting{
            overflow-x:hidden !important;
        }
    }
</style>

<!--  -->
<link rel="stylesheet" href="<?= base_url('assets/css/theme-2021.css?v=' . time()); ?>">
<!--  -->

<!-- Bable -->
<script src="https://unpkg.com/@babel/standalone@7.13.10/babel.min.js"></script>
<!-- Bable -->
  
</head>

<body>
    <div class="wrapper-outer">
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <div class="logo">
                            <?php if (isset($session['company_detail']) && get_company_logo_status($session['company_detail']['sid']) == 1) {?>
                            <a href="javascript:;"><img
                                    src="<?php echo AWS_S3_BUCKET_URL . $session['company_detail']['Logo']; ?>"></a>
                            <?php } else {?>
                            <!-- <a href="javascript:;"><img src="<?php //echo base_url(); ?>assets/employee_panel/images/logo.jpg"></a> -->
                            <?php }?>
                        </div>
                    </div>
                    <!--<h4><?php //echo $company_info['CompanyName']; ?></h4>-->
                </div>
            </div>
        </header>
        <div class="emp-info-strip">
            <div class="container">
                <div class="row">
                    <?php if (isset($applicant)) {?>
                    <div class="col-lg-12">
                        <div class="emp-info-box">
                            <div class="figure">
                                <?php if (isset($applicant['pictures']) && !empty($applicant['pictures'])) {?>
                                    <div class="container-fig">
                                        <img class="img-responsive"
                                            src="<?php echo AWS_S3_BUCKET_URL . $applicant['pictures']; ?>">
                                    </div>
                                <?php } else {?>
                                    <span><?php echo substr($applicant['first_name'], 0, 1) . substr($applicant['last_name'], 0, 1); ?></span>
                                <?php }?>
                            </div>
                            <div class="text text-white">
                                <h3>
                                    <?php echo $applicant['first_name'] . ' ' . $applicant['last_name']; ?>
                                    <span>On-Boarding Applicant</span>
                                </h3>
                                <!--<p>Administrator at ABC</p>-->
                                <ul class="contact-info">

                                    <?php if (!empty($applicant['phone_number'])) {?>
                                    <li><i class="fa fa-phone"></i> <?php echo $applicant['phone_number']; ?></li>
                                    <?php }?>

                                    <?php if (!empty($applicant['email'])) {?>
                                    <li><i class="fa fa-envelope"></i> <?php echo $applicant['email']; ?></li>
                                    <?php }?>

                                    <li>
                                        <h4><?php echo $company_info['CompanyName']; ?></h4>
                                    </li>
                                </ul>
                            </div>
                            <!--                                <div class="btn-link-wrp">-->
                            <!--                                    <a href="--><?php //echo base_url('onboarding/my_profile/' . $unique_sid); ?>
                            <!--"><i class="fa fa-pencil"></i> my profile</a>-->
                            <!--                                </div>-->
                        </div>
                    </div>
                    <?php } else {?>
                    <div class="col-lg-12">
                        <div class="emp-info-box">
                            <div class="figure">
                                <?php if (isset($employee['pictures']) && !empty($employee['pictures'])) {?>
                                <div class="container-fig">
                                    <img class="img-responsive"
                                        src="<?php echo AWS_S3_BUCKET_URL . $employee['pictures']; ?>">
                                </div>
                                <?php } else {?>
                                <span><?php echo substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1); ?></span>
                                <?php }?>
                            </div>
                            <div class="text text-white">
                                <h3>
                                    <?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>
                                    <span>On-Boarding Applicant</span>
                                </h3>
                                <!--<p>Administrator at ABC</p>-->
                                <ul class="contact-info">

                                    <?php if (!empty($employee['phone_number'])) {?>
                                    <li><i class="fa fa-phone"></i> <?php echo $employee['phone_number']; ?></li>
                                    <?php }?>

                                    <?php if (!empty($employee['email'])) {?>
                                    <li><i class="fa fa-envelope"></i> <?php echo $employee['email']; ?></li>
                                    <?php }?>

                                    <li>
                                        <h4><?php echo $company_info['CompanyName']; ?></h4>
                                    </li>
                                </ul>
                            </div>
                            <!--                                <div class="btn-link-wrp">-->
                            <!--                                    <a href="--><?php //echo base_url('onboarding/my_profile/' . $unique_sid); ?>
                            <!--"><i class="fa fa-pencil"></i> my profile</a>-->
                            <!--                                </div>-->
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
        <?php if ($this->uri->segment(2) != 'my_offer_letter' && $this->uri->segment(2) != 'send_requested_resume') {?>
        <div class="row">
            <div class="col-xs-12">
                <div class="arrow-links">
                    <?php 
                        $eeo_form_status = getCompanyEEOCFormStatus($session['company_detail']['sid']);
                        //
                        if ($enable_learbing_center && ($company_eeoc_form_status == 1 && $eeo_form_status == 1)) {
                            $width = round(100 / 8, 4);
                        } else if (!$enable_learbing_center && $company_eeoc_form_status == 0) {
                            $width = round(100 / 6, 3);
                        } else if (!$enable_learbing_center || $company_eeoc_form_status == 0) {
                            $width = round(100 / 7, 4);
                        } else {
                            $width = round(100 / 6, 3);
                        }

                        $url_segment = $this->uri->segment(2);
                    ?>
                    <ul>
                        <li class="<?php echo $url_segment == 'getting_started' || $url_segment == 'colleague_profile' ? 'active' : ''; ?>"
                            style="width: <?php echo $width ?>%;">
                            <a href="<?php echo base_url('onboarding/getting_started/' . $unique_sid); ?>">
                                <span>Dashboard</span>
                                <div class="step-text">Getting Started</div> <i class="star" data-toggle="tooltip"
                                    data-placement="left" title="Done!"></i>
                            </a>
                        </li>
                        <li class="<?php echo $url_segment == 'e_signature' ? 'active' : ''; ?> <?php echo $complete_steps['e_signature'] > 0 ? 'done1' : ''; ?>"
                            style="width: <?php echo $width ?>%;">
                            <a href="<?php echo base_url('onboarding/e_signature/' . $unique_sid); ?>">
                                <span>Step 1</span>
                                <div class="step-text">E-Signature</div> <i class="star" data-toggle="tooltip"
                                    data-placement="left" title="Done!"></i>
                            </a>
                        </li>
                        <li class="<?php echo $url_segment == 'hr_documents' || $url_segment == 'sign_hr_document' || $url_segment == 'sign_offer_letter' || $url_segment == 'form_w4' || $url_segment == 'form_i9' ? 'active' : ''; ?> <?php echo $complete_steps['documents'] > 0 ? 'done1' : ''; ?>"
                            style="width: <?php echo $width ?>%;">
                            <a href="<?php echo base_url('onboarding/hr_documents/' . $unique_sid); ?>">
                                <span>Step 2</span>
                                <div class="step-text">Document Management</div> <i class="star" data-toggle="tooltip"
                                    data-placement="left" title="Done!"></i>
                            </a>
                        </li>
                        <li class="<?php echo $url_segment == 'my_profile' ? 'active' : ''; ?> <?php echo $complete_steps['my_profile'] > 0 ? 'done1' : ''; ?>"
                            style="width: <?php echo $width ?>%;">
                            <a href="<?php echo base_url('onboarding/my_profile/' . $unique_sid); ?>">
                                <span>Step 3</span>
                                <div class="step-text">My Profile</div> <i class="star" data-toggle="tooltip"
                                    data-placement="left" title="Done!"></i>
                            </a>
                        </li>
                       <li class="<?php echo $url_segment == 'general_information' || $url_segment == 'edit_dependant_information' || $url_segment == 'edit_emergency_contacts' ? 'active' : ''; ?> <?php echo $complete_steps['license_info'] > 0 ? 'done1' : ''; ?>"
                            style="width: <?php echo $width ?>%">
                            <a href="<?php echo base_url('onboarding/general_information/' . $unique_sid); ?>">
                                <span>Step 4</span>
                                <div class="step-text">General Information</div> <i class="star" data-toggle="tooltip"
                                    data-placement="left" title="Done!"></i>
                            </a>
                        </li>


                        <!--                            <li class="--><?php //echo $url_segment == 'documents' || $url_segment == 'sign_u_document' || $url_segment == 'sign_g_document' || $url_segment == 'sign_offer_letter' || $url_segment == 'form_w4' || $url_segment == 'form_i9'? 'active' : ''; ?>
                        <!-- --><?php //echo $complete_steps['documents'] > 0 ? 'done1' : ''; ?>
                        <!--" style="width: --><?php //echo $width ?>
                        <!--                                <a href="--><?php //echo base_url('onboarding/documents/' . $unique_sid); ?>
                        <!--">-->
                        <!--                                    <span>Step 4</span>Document Management <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>-->
                        <!--                                </a>-->
                        <!--                            </li>-->

                        <?php if ($company_eeoc_form_status == 1) {?>
                        <li class="<?php echo $url_segment == 'eeoc_form' ? 'active' : ''; ?> <?php echo $complete_steps['eeoc_form'] > 0 ? 'done1' : ''; ?>"
                            style="width: <?php echo $width ?>%;">
                            <a href="<?php echo base_url('onboarding/eeoc_form/' . $unique_sid); ?>">
                                <span>Step 5</span>
                                <div class="step-text">EEOC Form</div> <i class="star" data-toggle="tooltip"
                                    data-placement="left" title="Done!"></i>
                            </a>
                        </li>
                        <?php }?>
                        <!--                            <li class="--><?php //echo $url_segment == 'license_info' ? 'active' : ''; ?>
                        <!-- --><?php //echo $complete_steps['license_info'] > 0 ? 'done' : ''; ?>
                        <!--" style="width: --><?php //echo $width ?>
                        <!--/*%;">*/
<!--                                <a href="--><?php //echo base_url('onboarding/license_info/' . $unique_sid); ?>
                        <!--">-->
                        <!--                                    <span>Step 5</span>License Info. <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>-->
                        <!--                                </a>-->
                        <!--                            </li>-->
                        <!--                            <li class="--><?php //echo $url_segment == 'direct_deposit' ? 'active' : ''; ?>
                        <!-- --><?php //echo $complete_steps['direct_deposit'] > 0 ? 'done1' : ''; ?>
                        <!--" style="width: --><?php //echo $width ?>
                        <!--                                <a href="*/<?php //echo base_url('onboarding/direct_deposit/' . $unique_sid); ?><!--">-->
                        <!--                                    <span>Step 6</span>Direct Deposit <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>-->
                        <!--                                </a>-->
                        <!--                            </li>-->


                        <?php if ($enable_learbing_center) {?>
                        <li class="<?php echo $url_segment == 'learning_center' || $url_segment == 'view_supported_attachment_document' || $url_segment == 'watch_video' ? 'active' : ''; ?>"
                            style="width: <?php echo $width ?>%;">
                            <a href="<?php echo base_url('onboarding/learning_center/' . $unique_sid); ?>">
                                <span>Step 6</span>
                                <div class="step-text">Learning Center</div> <i class="star" data-toggle="tooltip"
                                    data-placement="left" title="Done!"></i>
                            </a>
                        </li>
                        <?php }?>
                        <!--                            <li class="--><?php //echo $url_segment == 'dependents' ? 'active' : ''; ?>
                        <!-- --><?php //echo $complete_steps['dependents'] > 0 ? 'done' : ''; ?>
                        <!--" style="width: --><?php //echo $width ?>
                        <!--/*%;">*/
<!--                                <a href="--><?php //echo base_url('onboarding/dependents/' . $unique_sid); ?>
                        <!--">-->
                        <!--                                    <span>Step 7</span>Dependents <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>-->
                        <!--                                </a>-->
                        <!--                            </li>-->
                        <!--                            <li class="--><?php //echo $url_segment == 'emergency_contacts' ? 'active' : ''; ?>
                        <!-- --><?php //echo $complete_steps['emergency_contacts'] > 0 ? 'done' : ''; ?>
                        <!--" style="width: --><?php //echo $width ?>
                        <!--/*%;">*/
<!--                                <a href="--><?php //echo base_url('onboarding/emergency_contacts/' . $unique_sid); ?>
                        <!--">-->
                        <!--                                    <span>Step 8</span>Emergency Contacts <i class="star" data-toggle="tooltip" data-placement="left" title="Done!"></i>-->
                        <!--                                </a>-->
                        <!--                            </li>-->

                        <li class="<?php echo $url_segment == 'my_credentials' ? 'active' : ''; ?>"
                            style="width: <?php echo $width ?>%;">
                            <a href="<?php echo base_url('onboarding/my_credentials/' . $unique_sid); ?>">
                                <span>Finish</span>
                                <div class="step-text">Login Credentials</div> <i class="star" data-toggle="tooltip"
                                    data-placement="left" title="Done!"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php }?>