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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/2022/css/main.css?v=1737460488') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/responsive.css') ?>">
    <link rel="StyleSheet" type="text/css" href="<?php echo base_url('assets/css/chosen.css'); ?>"/>

    <script src="<?php echo base_url('assets/employee_panel/js/jquery-1.11.3.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.validate.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/additional-methods.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.datetimepicker.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/alertifyjs/alertify.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/functions.js') ?>"></script>
    <script src="<?php echo base_url('assets/ckeditor/ckeditor.js') ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/select2.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/chosen.jquery.js'); ?>"></script>
</head>

<body>
    <div class="wrapper-outer">
        <header class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <div class="logo">
                            <?php if (isset($company_sid) && get_company_logo_status($company_sid == 1)) {?>
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="emp-info-box">
                            <div class="figure">
                                <?php if (isset($user_picture) && !empty($user_picture)) {?>
                                    <div class="container-fig">
                                        <img class="img-responsive"
                                            src="<?php echo AWS_S3_BUCKET_URL . $user_picture; ?>">
                                    </div>
                                <?php } else {?>
                                    <span>
                                        <?php echo substr($user_first_name, 0, 1) . substr($user_last_name, 0, 1); ?>
                                    </span>
                                <?php }?>
                            </div>
                            <div class="text text-white">
                                <h3>
                                    <?php echo $user_first_name . ' ' . $user_last_name; ?>
                                </h3>
                                <ul class="contact-info">

                                    <?php if (!empty($user_phone)) {?>
                                    <li><i class="fa fa-phone"></i> <?php echo $user_phone; ?></li>
                                    <?php }?>

                                    <?php if (!empty($user_email)) {?>
                                    <li><i class="fa fa-envelope"></i> <?php echo $user_email; ?></li>
                                    <?php }?>

                                    <li>
                                        <h4><?php echo $company_name; ?></h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>