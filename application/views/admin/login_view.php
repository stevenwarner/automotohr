<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/responsive.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css');?>">
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js');?>"></script>
<script src="<?php echo site_url('assets/manage_admin/js/bootstrap.min.js');?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/manage_admin/js/jquery-ui.js');?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/manage_admin/js/functions.js');?>"></script>
<script src="<?=base_url()?>assets/alertifyjs/alertify.min.js"></script>
<!-- include the style -->
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
<!-- include a theme -->
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />

<!--select2-->
<link href="<?php echo site_url('assets/manage_admin/css/select2.css');?>" rel="stylesheet" />
<script src="<?php echo site_url('assets/manage_admin/js/select2.min.js');?>"></script>

<title><?php echo $page_title;?></title>
</head>
<body>
<div class="wrapper">
    <div class="main">
        <div class="container-fluid">
            <div class="row">		
                <div class="inner-content">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="hr-login-section">								
                            <div class="hr-box-wrap">
                                <div class="hr-login-logo"><img src="<?php echo site_url('assets/manage_admin/images/new_logo.JPG'); ?>" alt="<?php echo STORE_NAME; ?>-logo"></div>
                                <div class="hr-login-box">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <?php echo form_open('',array('class'=>'form-horizontal')); ?>
                                        <ul>
                                            <li>
                                                <div class="fields-wrapper">
                                                    <?php echo form_input('username','','class="form-fileds" placeholder="User Name"'); ?>
                                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                                    <?php echo form_error('username');?>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="fields-wrapper">
                                                    <?php echo form_password('password','','class="form-fileds" placeholder="Password"'); ?>
                                                    <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                                    <?php echo form_error('password'); ?>
                                                </div>
                                            </li>	                                            
                                            <li>
                                                <div class="fields-wrapper">
                                                    <?php echo form_checkbox('remember','1',FALSE,'id=rememberme'); ?> 
                                                    <label for="rememberme">Remember me</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="fields-wrapper">
                                                    <?php echo form_submit('submit', 'login', 'class="hr-login-btn"'); ?>
                                                </div>
                                            </li>
                                        </ul>
                                    <?php echo form_close();?>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="login-copyright">
                                <a href="javascript:;">&copy; <?php echo STORE_NAME; ?></a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>