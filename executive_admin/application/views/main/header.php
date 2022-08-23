<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Executive Admin Panel</title>	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css??v=11" />
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/responsive.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css" />	
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/selectize.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/basic-responsive-table/css/basictable.css" />
	<link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery-ui-datepicker-custom.css" />
	<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>

    
	<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>	
	<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/selectize.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/bootstrap-filestyle/js/bootstrap-filestyle.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/basic-responsive-table/js/jquery.basictable.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/functions.js"></script>
</head>
<body>
	<div class="wrapper">		
            <header class="header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5"></div>
                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                            <div class="logo text-center"><a href="<?php echo base_url('dashboard');?>"><img src="<?= base_url() ?>assets/images/logo.png"></a></div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                            <?php if($this->session->userdata('executive_loggedin') && !empty($executive_user)){ ?>
                                <div class="topRight">
                                    Welcome <?php echo $executive_user['first_name'].' '.$executive_user['last_name'];?><br>
                                    <a href="<?php echo base_url('dashboard');?>">Dashboard</a> | 
                                    <a href="<?php echo base_url('logout');?>">Logout</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>			
            </header>
		