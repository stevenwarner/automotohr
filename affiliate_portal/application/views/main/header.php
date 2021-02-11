<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title><?= isset($title) ? $title : $page_title;?> - Affiliate Portal - AutomotoHR</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <?php $method =  $this->router->fetch_method(); ?>
    <?php 
    if(
        $method == 'login' ||
        $method == 'generate_password' ||
        $method == 'forgot_password' ||
        $method == 'change_password' ||
        $method == 'thankyou' ||
        $method == 'linked_expired'
    ) {
        $this->load->view('main/partials/styles_login');
    }
    else {
        $this->load->view('main/partials/styles_header');
        $this->load->view('main/partials/scripts_header');
    }
    ?>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css"/>
    <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/css/chosen.css"/>
    <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/css/selectize.css"/>
    <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/css/selectize.bootstrap3.css"/>
    <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/css/jquery.datetimepicker.css"/>
    <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/css/jquery-ui-datepicker-custom.css"/>
    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/chosen.jquery.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.datetimepicker.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/jquery-ui.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/bootstrap_4/js/bootstrap.bundle.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/selectize.min.js"></script>
</head>
<body class="hold-transition
    <?php
        if(
            $method == 'login' ||
            $method == 'generate_password' ||
            $method == 'forgot_password' ||
            $method == 'change_password' ||
            $method == 'thankyou' ||
            $method == 'linked_expired'
        ) {
           echo 'login-page';
        }
        else {
           echo 'skin-blue sidebar-mini';
        }
    ?>
">
	<div class="wrapper">
        <?php if(
                $method == 'login' ||
                $method == 'generate_password' ||
                $method == 'forgot_password' ||
                $method == 'change_password' ||
                $method == 'thankyou' ||
                $method == 'linked_expired'
        ) {

        } else {
            if ($this->uri->segment(2) == 'form_w9') {
                $this->load->view('main/partials/public_top_header',$name);
            } else {
                $this->load->view('main/partials/top_header',$name);
                $this->load->view('main/partials/left_nav');
            }    
        } ?>
  