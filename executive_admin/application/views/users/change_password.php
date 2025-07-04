<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hr-login-section">
                    <div class="executive-login-screen-heading"><h1><?php echo $page_title; ?></h1></div>
                    <div class="hr-box-wrap">					
                        <div class="hr-login-box">
                            <h4 class="password_forgot">Please enter your new password:</h4>
                            <?php if ($this->session->flashdata('message')) { ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </div>
                                </div>
                            <?php } ?>                            
                            <form action="" method="post" id="change_password_form" name="change_password_form" class="ng-pristine ng-valid"> 
                                <ul>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="password" id="password" value="" class="form-fileds" placeholder="New Password" type="password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="retypepassword" id="retypepassword" value="" class="form-fileds" placeholder="Retype New Password" type="password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="submit" value="Submit" class="hr-login-btn" type="submit" onclick="validate_form()">
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
<script  language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
function validate_form() {
        $("#change_password_form").validate({
            ignore: ":hidden:not(select)",
             rules: {
                 password: {
                    required: true
                },
                retypepassword: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                password: {
                    required: '<p class="error_message"><i class="fa fa-exclamation-circle"></i>Password is required</p>'
                },
                retypepassword: {
                    required: '<p class="error_message"><i class="fa fa-exclamation-circle"></i>Confirm Password is required</p>',
                    equalTo: '<p class="error_message"><i class="fa fa-exclamation-circle"></i>Confirm Password does not match</p>'
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
.forgot-btn {
    margin-top: 10%;
}
</style>