<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="hr-login-section">
                    <div class="executive-login-screen-heading"><h1><?php echo $page_title; ?></h1></div>

                    <div class="hr-box-wrap">					
                        <div class="hr-login-box">
                            <h4 class="password_forgot">Please enter your email in the field below and we'll send you a link to a page where you can change your password:</h4>
                            <?php if ($this->session->flashdata('message')) { ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid"> <!-- class="form-horizontal"  -->
                                <ul>
                                    <li>
                                        <div class="fields-wrapper">
<!--                                            <input name="email" id="email" value="" class="form-fileds" placeholder="Enter Email" type="email">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>-->
                                            <input class="form-fileds" value="<?php echo set_value('email'); ?>" type="email" id="email" name="email" placeholder="Email">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                            <?php echo form_error('email'); ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
<!--                                            <input name="submit" value="Submit" class="hr-login-btn" type="submit">-->
                                            <input class="hr-login-btn" type="submit" onclick="validate_form()" value="Submit">
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="login-copyright">&copy;<?php echo date('Y') .' '. STORE_NAME;?>. All Rights Reserved.</div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
                                                function validate_form() {
                                                    $("#forgotForm").validate({
                                                        ignore: ":hidden:not(select)",
                                                        rules: {
                                                            email: {
                                                                required: true,
                                                                email: true
                                                            }
                                                        },
                                                        messages: {
                                                            email: {
                                                                required: '<p class="error"><i class="fa fa-exclamation-circle"></i> Please provide Valid email</p>'
                                                            }
                                                        },
                                                        submitHandler: function (form) {
                                                            form.submit();
                                                        }
                                                    });
                                                }
</script>

<style>
    .password_forgot{
        padding: 8%;
    }
    form#forgotForm {
        padding: 0% 10% 0% 10%;
    }

    .error_message {
        margin-left: 33%;
    }
</style>