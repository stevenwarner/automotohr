<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo STORE_NAME; ?>: <?= $title ?></title>

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css">

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/star-rating.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/easy-responsive-tabs.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.nicescroll.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/star-rating.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.parallax-scroll.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/easyResponsiveTabs.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>assets/js/functions.js"></script>
    <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css"/>
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css"/>
    <script src="<?= base_url() ?>assets/ckeditor/ckeditor.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery-ui.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui-datepicker-custom.css">
    <script type="text/javascript" src="<?= base_url('/assets/js/jquery.timepicker.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('/assets/css/jquery.timepicker.css') ?>">
    <link href="<?= base_url() ?>assets/css/select2.css" rel="stylesheet"/>
    <script src="<?= base_url() ?>assets/js/select2.js"></script>

    <!-- Range Slider -->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/rangeslider.css" />
    <script type="text/javascript" src="<?= base_url() ?>assets/js/rangeslider.js"></script>
    <!-- Range Slider -->

    <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>


    <?php if($this->uri->segment(1) == 'organizational_hierarchy') { ?>
        <!-- Organizational Chart -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css')?>/jquery.orgchart.css">
        <script src="<?php echo base_url('assets/js')?>/jquery.orgchart.js"></script>
        <!-- Organizational Chart -->
    <?php } ?>

    <?php if($this->uri->segment(1) == 'application_tracking_system') { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css')?>/selectize.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css')?>/selectize.bootstrap3.css">
        <script src="<?php echo base_url('assets/js')?>/selectize.min.js"></script>
    <?php } ?>

    <?php if($this->uri->segment(1) == 'events_management') { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/fullcalendar-3.4.0/fullcalendar.css')?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/fullcalendar-3.4.0/fullcalendar.print.css')?>" media="print">
        <script src="<?php echo base_url('assets/fullcalendar-3.4.0/lib/moment.min.js')?>"></script>
        <script src="<?php echo base_url('assets/fullcalendar-3.4.0/fullcalendar.js')?>"></script>
    <?php } ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>