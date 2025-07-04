<div id="messageBox">
    <div id="content">
        <div class="row">
            <div  class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="login-section ">
                        <div class="registered-user contact-us">
                            <div class="address-panel">
                                <h2 class="form-heading"><?php echo $title; ?></h2>
                                <h4>Contact one of our Talent Network Partners at</h4>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <h5>Sales Support</h5>
                                    <ul>
                                        
                                        <li><i class="fa fa-phone"></i><?php echo TALENT_NETWORK_SALE_CONTACTNO; ?></li>
                                        <li><a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo TALENT_NETWORK_SALES_EMAIL; ?></a></li>
                                    </ul>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <h5>Technical Support</h5>
                                    <ul>
                                        
                                        <li><i class="fa fa-phone"></i><?php echo TALENT_NETWORK_SUPPORT_CONTACTNO; ?></li>
                                        <li><a href="mailto:<?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></li>
                                    </ul>
                                </div>
                            </div> 
                            <h4 class="password_forgot"><?php echo $sub_title; ?></h4>
                            <?php if($this->session->flashdata('message')){ ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <?php echo $this->session->flashdata('message');?>
                                    </div>
                                </div>
                            <?php } ?>
                              <form action="" method="post" id="contactusform" class="ng-pristine ng-valid">
                                <ul>
                                    <li>
                                        <label>Your Name<span class="staric">*</span></label>
                                        <div class="fields-wrapper">
                                            <input class="form-fileds" value="<?php echo set_value('name'); ?>" type="text" name="name">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                            <?php echo form_error('name'); ?>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Your Email<span class="staric">*</span></label>
                                        <div class="fields-wrapper">
                                            <input class="form-fileds" value="<?php echo set_value('email'); ?>" type="Email" id="email" name="email">
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
                                        <label>Please Check The Box<span class="staric">*</span></label>
                                        <div class="fields-wrapper  ">
                                            <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                        </div>
                                        <?php echo form_error('g-recaptcha-response'); ?>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input class="login-btn" type="submit" onclick="validate_form()" value="Submit">
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
            </div>
        </div>		
    </div>
</div>
<script  language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
function validate_form() {
        $("#contactusform").validate({
            ignore: ":hidden:not(select)",
             rules: {
                email: {
                        required: true,
                        email: true
                        },
                name: {
                        required: true
                        },
                message: {
                        required: true
                        }
              },
            messages: {
                email: {
                            required: '<p class="error"><i class="fa fa-exclamation-circle"></i> Your Valid Email is required</p>'
                        },
                name: {
                            required: '<p class="error"><i class="fa fa-exclamation-circle"></i> Your Name is required</p>'
                        },
                message: {
                            required: '<p class="error"><i class="fa fa-exclamation-circle"></i> Your Message is required</p>'
                        },
                                
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
</script>
<script src='https://www.google.com/recaptcha/api.js'></script>