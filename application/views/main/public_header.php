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
                            <?php if (isset($company_detail) && get_company_logo_status($company_detail['sid']) == 1) {?>
                            <a href="javascript:;"><img
                                    src="<?php echo AWS_S3_BUCKET_URL . $company_detail['Logo']; ?>"></a>
                            <?php } ?>
                        </div>
                    </div>
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
                                            <h4><?php echo $company_detail['CompanyName']; ?></h4>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
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
                                        <?php echo getUserNameBySID($employee['sid']); ?>
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
                                            <h4><?php echo $company_detail['CompanyName']; ?></h4>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>