<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">	
                <header class="heading-title">
                    <h2 class="page-title"><?php echo $page_inner_title; ?></h2>
                </header>
            </div>	
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <div class="company-info">
                    <div class="content-wrapper">
                        <h2 class="post-title color">
                            contact details
                        </h2>
                        <div class="contact-details">
                            <ul>
                                <li>
                                    <figure><i class="fa fa-building-o"></i></figure>
                                    <div class="text"><?php echo $company_details["CompanyName"];?></div>
                                </li>
                                <?php if(!empty($company_details['Location_Address'])) { ?>
                                <li>
                                    <figure><i class="fa fa-map-marker"></i></figure>
                                    <div class="text"><?php echo $company_details["Location_Address"];?></div>
                                </li>
                                <?php } if (!empty($company_details['PhoneNumber'])) { ?>
                                <li>
                                    <figure><i class="fa fa-mobile"></i></figure>
                                    <div class="text"><?=phonenumber_format($company_details["PhoneNumber"]);?></div>
                                </li>
                                <?php } if (!empty($company_details['fax'])) { ?>
                                <li>
                                    <figure><i class="fa fa-mobile"></i></figure>
                                    <div class="text">fax: <?php echo $company_details["fax"];?></div>
                                </li>
                                <?php } if (!empty($company_details['email'])) { ?>
                                <li>
                                    <figure><i class="fa fa-envelope-o"></i></figure>
                                    <div class="text"><?php echo $company_details["email"];?></div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>			
            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                <?php $this->load->view($theme_name.'/_parts/admin_flash_message'); ?>                
                <div class="registered-user">
                    <form class="form" id="contactus" action="" method="post">
                        <ul>
                            <li>
                                <label>Your Name<span class="staric">*</span></label>
                                <div class="fields-wrapper">                                    
                                    <?php echo form_input(array('class' => 'form-fileds', 'name' => 'name'), set_value('name')); ?>
                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                    <?php echo form_error('name'); ?>
                                </div>
                            </li>
                            <li>
                                <label>Your Email<span class="staric">*</span></label>
                                <div class="fields-wrapper">
                                    <?php echo form_input(array('class' => 'form-fileds', 'name' => 'email','type'=>'email'), set_value('email')); ?>
                                    <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                    <?php echo form_error('email'); ?>
                                </div>
                            </li>
                            <li>
                                <label>Your Message<span class="staric">*</span></label>
                                <div class="fields-wrapper comment-filed">
                                    <?php echo form_textarea(array('class' => 'form-fileds', 'name' => 'message'), set_value('message')); ?>
                                    <?php echo form_error('message'); ?>
                                </div>
                            </li>
                            <li>
                                <div class="fields-wrapper button-field">
                                    <?php echo form_submit(array('class' => 'site-btn-v2', 'type' => 'submit', 'onclick'=>'validate_form()'), 'submit'); ?>
                                </div>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets/' . $theme_name . '/js/jquery.validate.min.js'); ?>"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets/' . $theme_name . '/js/additional-methods.min.js'); ?>"></script>
<script  language="JavaScript" type="text/javascript">
    function validate_form() {
        $("#contactus").validate({
            ignore: ":hidden:not(select)",
            rules: {
                name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                message: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                name: {
                    required: 'Your Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                message: {
                    required: 'Your Message is required',
                    pattern: 'Special Characters are not allowed'
                }, email: {
                    required: 'Please provide your valid email'
                },
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
</script>