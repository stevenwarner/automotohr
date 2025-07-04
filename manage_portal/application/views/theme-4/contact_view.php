<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="comapny-video <?php if($header_video_overlay == 0) {echo 'no_overlay';} ?>">
    <img src="<?php echo base_url('/assets/' . $theme_name);?>/images/banner-4.jpg">
    <div class="caption-text">
        <span class="page-title">Contact Us</span>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
        </div>
    </div>
</div>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <div class="address-area">
                    <header class="heading-title">
                        <h1 class="section-title">Mailing<span>Address:</span></h1>
                    </header>
                    <address>
                            <p><?php echo $company_details["CompanyName"];?></p>
                        <?php   if(!empty($company_details['Location_Address'])) { ?>
                                    <p><?php echo $company_details["Location_Address"];?></p>
                        <?php   } if (!empty($company_details['PhoneNumber'])) { ?>
                                    <p><?=phonenumber_format($company_details["PhoneNumber"]);?></p>
                        <?php   } if (!empty($company_details['fax'])) { ?>
                                    <p>fax: <?php echo $company_details["fax"];?></p>
                        <?php } if (!empty($company_details['email'])) { ?>
                                    <p><?php echo $company_details["email"];?></p>
                        <?php } ?>
                    </address>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                <br/>
                <div class="contact-form">
                    <div class="universal-form-style">
                        <ul>
                            <?php $this->load->view($theme_name.'/_parts/admin_flash_message'); ?>
                                <form  id="contactus" action="" method="post">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <li>
                                            <label>Your Name<span class="staric">*</span></label>
                                            <div class="fields-wrapper">
                                                <?php echo form_input(array('class' => 'input-field', 'name' => 'name'), set_value('name')); ?>                                                
                                                <?php echo form_error('name'); ?>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <li>
                                            <label>Your Email<span class="staric">*</span></label>
                                            <div class="fields-wrapper">
                                                <?php echo form_input(array('class' => 'input-field', 'name' => 'email','type'=>'email'), set_value('email')); ?>                                               
                                                <?php echo form_error('email'); ?>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <li class="autoheight">
                                            <label>Your Message<span class="staric">*</span></label>
                                            <div class="fields-wrapper comment-filed">
                                                <?php echo form_textarea(array('class' => 'input-field', 'name' => 'message'), set_value('message')); ?>
                                                <?php echo form_error('message'); ?>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <li class="autoheight">
                                            <?php echo form_submit(array('class' => 'submit-btn bg-color', 'type' => 'submit', 'onclick'=>'validate_form()'), 'submit'); ?>
                                        </li>
                                    </div>
                                </form>
                        </ul>
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