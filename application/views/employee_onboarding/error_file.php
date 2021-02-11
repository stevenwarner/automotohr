
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/style-tabs.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/responsive.css">
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/functions.js"></script>
        <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
        <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" /> 
    </head>
    <body class="login-page">
        <!-- Wrapper Start -->
        <div class="wrapper">
            <div class="top-title-area">
                <h2>Message</h2>
                <div class="row">
                    <div class="dashboard-conetnt-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                            <div class="create-job-wrap">
                                <div class="universal-form-style-v2">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wrapper End -->
    </body>
</html>
