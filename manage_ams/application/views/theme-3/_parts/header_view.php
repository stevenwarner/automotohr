<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html>
    <head>
        <?php $this->load->view('common/job_details_social_metatags_partial');?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        if ($meta_title) {
            $heading_title = $meta_title;
        }
        ?>
        <title><?php echo $heading_title; ?></title>
        <?php if ($meta_description) { ?>
            <meta name="description" content="<?php echo $meta_description; ?>" />
        <?php } ?>
        <?php if ($meta_keywords) { ?>
            <meta name="keywords" content= "<?php echo $meta_keywords; ?>" />
        <?php } ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/' . $theme_name . '/css/style.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/' . $theme_name . '/css/bootstrap.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/' . $theme_name . '/css/font-awesome.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/' . $theme_name . '/css/responsive.css'); ?>">
        <script type="text/javascript" src="<?php echo base_url('assets/' . $theme_name . '/js/jquery-1.11.3.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/' . $theme_name . '/js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/' . $theme_name . '/js/jquery.nicescroll.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/' . $theme_name . '/js/functions.js'); ?>"></script>
        <!--        {*Share This Script*}
                <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
                <script type="text/javascript">
                    stLight.options({
                        publisher: "1be703d3-9992-4d1e-b33b-aa77ebec1707", doNotHash: false, doNotCopy: false, hashAddressBar: false
                    });
                </script>-->
    </head>
    <body>
        <div class="wrapper">
            <!--<header class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php /*if ($enable_company_logo) { */?>
                                <div class="logo">
                                    <a href="<?php /*echo base_url('/'); */?>">
                                        <?php /*if (empty($company_details['Logo'])) { */?>
                                            <img src="<?php /*echo base_url('assets/' . $theme_name . '/images/new_logo.JPG'); */?>">
                                        <?php /*} else { */?>
                                            <img src="<?php /*echo AWS_S3_BUCKET_URL . $company_details['Logo']; */?>">
                                        <?php /*} */?>
                                    </a>
                                </div>
                            <?php /*} */?>
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                            <nav class="navigation">
                                <ul id="menus">
                                    <li <?php /*if ($this->uri->segment(1) != 'contact_us') { */?>class="active" <?php /*} */?>><a href="<?php /*echo base_url('/'); */?>"><i class="fa fa-home"></i> home</a></li>
                                    <?php /*if (!empty($dealership_website)) { */?>
                                        <li><a href="<?php /*echo $dealership_website; */?>" target="_blank">Company Website</a></li>
                                    <?php /*} */?>
                                    <?php /*if ($contact_us_page) { */?>
                                        <li <?php /*if ($this->uri->segment(1) == 'contact_us') { */?>class="active" <?php /*} */?>><a href="<?php /*echo base_url('/contact_us'); */?>  "><i class="fa fa-tty"></i>contact</a></li>
                                    <?php /*} */?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>-->
            <?php $this->load->view('common/header_menu'); ?>
            <div class="page-banner">
                <img src="<?php echo AWS_S3_BUCKET_URL . $pictures; ?>" alt="Banner Image">     
            </div>
            <!-- Page Banner End -->
            <!--customize theme settings-->
            <style>
                .showDetail,.copyright, .custom-apply-now, .bg-color, .btn-close,  .apply-now-btn, .custom-theme {
                    background: <?= $body_bgcolor ?> !important;
                }
                .icon-job.contact-icon i, .contact-details ul li figure{
                    color: <?= $body_bgcolor ?> !important;
                }
                /*-------*/
                .join-btn{
                    background-color: <?= $hf_bgcolor ?> !important;
                }
                /*-------*/
                .color,.page-title,.job-dtails h1{
                    color: <?= $font_color ?> !important;
                }
                .custom-theme-text{
                    background-color: <?= $font_color ?> !important;
                }
                .job-description-text h2{
                    color: <?= $title_color ?> !important;
                }
                nav.navigation ul li.active a{
                    color: <?= $heading_color ?> !important;
                }
                /*.social-links ul li{
                    border-left: 1px solid <?= $heading_color ?> !important;
                    border-right: 1px solid  <?= $heading_color ?>  !important;
                }*/
                .header{
                    background-color: <?= $f_bgcolor ?> !important;
                }
            </style>