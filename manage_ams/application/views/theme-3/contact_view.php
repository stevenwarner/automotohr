<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container">
        <header class="heading-title">
            <h2 class="page-title color">Contact us</h2>
        </header>
        <div class="main-content">
            <div class="row">
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
                                        <div class="text"><?php echo $company_details["CompanyName"]; ?></div>
                                    </li>
                                    <?php if (!empty($company_details['Location_Address'])) { ?>
                                        <li>
                                            <figure><i class="fa fa-map-marker"></i></figure>
                                            <div class="text"><?php echo $company_details["Location_Address"]; ?></div>
                                        </li>
                                    <?php } if (!empty($company_details['PhoneNumber'])) { ?>
                                        <li>
                                            <figure><i class="fa fa-mobile"></i></figure>
                                            <div class="text"><?php echo $company_details["PhoneNumber"]; ?></div>
                                        </li>
                                    <?php } if (!empty($company_details['fax'])) { ?>
                                        <li>
                                            <figure><i class="fa fa-mobile"></i></figure>
                                            <div class="text">fax: <?php echo $company_details["fax"]; ?></div>
                                        </li>
                                    <?php } if (!empty($company_details['email'])) { ?>
                                        <li>
                                            <figure><i class="fa fa-envelope-o"></i></figure>
                                            <div class="text"><?php echo $company_details["email"]; ?></div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                    <div class="contact-info">
                                            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>

                        <div class="job-detail-description">
                            <div class="content-wrapper">
                                <article class="job-article">
                                    <div class="icon-job contact-icon"><i class="fa fa-cubes"></i></div>
                                    <div class="job-title">
                                        <h2 class="post-title color">contact us</h2>
                                        <p>Please send us your enquiry and we will get back to you.</p>
                                    </div>
                                    <div class="description-wrapper">
                                        <div class="contact-form">
                                            <ul>
                                                <form class="form" id="contactus" action="" method="post">
                                                    <li>
                                                        <label>Your Name<span class="staric">*</span></label>
                                                        <?php echo form_input(array('class' => 'form-fileds', 'placeholder' => '', 'name' => 'name'), set_value('name')); ?>
                                                        <?php echo form_error('name'); ?>
                                                    </li>
                                                    <li>
                                                        <label>Your Email<span class="staric">*</span></label>
                                                        <?php echo form_input(array('class' => 'form-fileds', 'placeholder' => '', 'name' => 'email', 'type' => 'email'), set_value('email')); ?>
                                                        <?php echo form_error('email'); ?>
                                                    </li>
                                                    <li class="full-width">
                                                        <label>Your Message<span class="staric">*</span></label>
                                                        <?php echo form_textarea(array('class' => 'form-fileds', 'placeholder' => '', 'name' => 'message'), set_value('message')); ?>
                                                        <?php echo form_error('message'); ?>
                                                        <!--<p class="hint_message">Minimum 50 characters</p>-->
                                                    </li>
                                                    <li>
                                                        <?php echo form_submit(array('class' => 'siteBtn bg-color', 'type' => 'submit', 'onclick' => 'validate_form()'), 'send enquiry'); ?>
                                                    </li>
                                                </form>
                                            </ul>
                                        </div>						
                                    </div>
                                </article>
                            </div>
                        </div>						
                    </div>
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