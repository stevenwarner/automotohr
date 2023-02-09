<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo ucwords($title); ?> - Interview Questionnaires</title>
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/alertifyjs/css/alertify.min.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/alertifyjs/css/themes/default.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/responsive.css">

        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/alertifyjs/alertify.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>

    </head>
    <body>
        <div class="main-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12" id="main_div">
                        <div class="dashboard-conetnt-wrp">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    Candidate Instructions for Video Interview
                                </span>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>