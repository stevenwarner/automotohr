<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Complete Document</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?=base_url('assets/images/favi-icon.png');?>" type="image/x-icon" />
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
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.datetimepicker.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/alertifyjs/alertify.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/functions.js') ?>"></script>
    <script src="<?php echo base_url('assets/ckeditor/ckeditor.js') ?>"></script>
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
                </div>
            </div>
        </header>
       