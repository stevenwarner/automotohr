<div id="messageBox">
    <div id="content">
        <div class="row">
            <div  class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php if (!$this->session->userdata('logged_in')) { ?>
                <?php if($verification!=NULL){ ?>
                    <div class="login-section ">
                        <div class="registered-user">
                            <h2 class="form-heading">Password Recovery</h2>
                            <h4 class="password_forgot">Please, Enter your New Password:<h4>
                            <?php if($this->session->flashdata('message')){ ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <?php echo $this->session->flashdata('message');?>
                                    </div>
                                </div>
                            <?php } ?>      
                                    <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid">
                                <ul>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input class="form-fileds" value="<?php echo set_value('password'); ?>" type="password" id="password" name="password" placeholder="Password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                            <?php echo form_error('password'); ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                           <input class="form-fileds" value="<?php echo set_value('retypepassword'); ?>" type="password" id="retypepassword" name="retypepassword" placeholder="Re Enter Password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                            <?php echo form_error('retypepassword'); ?>
                                        </div>
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
                <?php }else{?>
                
                  <div class="login-section ">
                        <div class="registered-user">
                            <h2 class="form-heading">Password Recovery</h2>
                            <h4 class="password_forgot">Your user Name OR Verification Key is not Valid to Reset Password Please go to following link </br>
                                <a href="<?php echo site_url();?>forgot_password" class="login-btn forgot-btn">Forgot Password</a>   
                        </div>
                        <div class="clear"></div>
                    </div>
                
                <?php  }?>
                <?php } else {?>
                    <div class="login-section ">
                        <div class="registered-user">
                            <h2 class="form-heading">Already Registered? Login Here.</h2>
                            <p class="error_message"><i class="fa fa-exclamation-circle"></i>
                                You are currently logged in as <b><?php echo $_SESSION["logged_in"]["employer_detail"]["username"]; ?></b><br>
                            </p>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php } ?>
            </div>
        </div>		
    </div>
</div>
<script  language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
function validate_form() {
        $("#forgotForm").validate({
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