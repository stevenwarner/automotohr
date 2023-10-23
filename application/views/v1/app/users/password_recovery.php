<main>
   
    <div class="row">
        <div class="col-xs-12 background-image-css" style="background-image: url(/assets/v1/app/images/loginBackground.png);">
            <div class="top-div">

                <?php if (!$this->session->userdata('logged_in')) { ?>
                    <?php if ($verification != NULL) { ?>
                        <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid">
                            <div class="parent-div">
                                <div class="first-div">
                                    <div class="high-lighted-text-div">
                                        <div class="highlighted-text-upper-div">
                                            <p class="highlighted-text">
                                                <?php echo $passwordRecoveryContent['page']['heading'] ?>
                                            </p>
                                        </div>

                                        <div class="login-section">
                                            <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
                                            <br>
                                            <p>
                                                <?php echo $passwordRecoveryContent['page']['subHeading'] ?>
                                            </p>
                                            <input class="d-block login-inputs" placeholder="Password" value="<?php echo set_value('password'); ?>" type="password" id="password" name="password" style="margin-left: 20px;margin-right: 20px;" />
                                            <?php echo form_error('password'); ?>

                                            <input class="d-block login-inputs" placeholder="Re Enter Password" value="<?php echo set_value('retypepassword'); ?>" type="password" id="retypepassword" name="retypepassword" style="margin-left: 20px;margin-right: 20px;" />
                                            <?php echo form_error('retypepassword'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="second-div ">
                                    <div class="first-child position-relative column-flex-center">
                                        <button class=" login-screen-btns margin-top-30" onclick="validate_form()" value="Submit"> <?php echo $passwordRecoveryContent['page']['btnText'] ?> <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i> </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    <?php } else { ?>

                        <div class="parent-div">
                            <div class="first-div">
                                <div class="high-lighted-text-div">
                                    <div class="highlighted-text-upper-div">
                                        <p class="highlighted-text">
                                            <?php echo $passwordRecoveryContent['page']['heading'] ?>
                                        </p>
                                    </div>
                                    <div class="login-section">
                                        <br>
                                        <p style="padding-left: 50px; padding-right: 50px; text-align: center;">
                                            Your user Name OR Verification Key is not Valid to Reset Password <br> Please click on Forgot Password
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="second-div ">
                                <div class="first-child position-relative column-flex-center">
                                    <button class=" login-screen-btns margin-top-30" onclick="forgotpassword()" value="Submit"> Forgot Password<i class="fa-solid fa-arrow-right top-button-icon ps-3"></i> </button>
                                </div>
                            </div>
                        </div>

                    <?php  } ?>

                <?php } else { ?>

                    <div class="parent-div">
                        <div class="first-div">
                            <div class="high-lighted-text-div">
                                <div class="highlighted-text-upper-div">
                                    <p class="highlighted-text">
                                        <?php echo $passwordRecoveryContent['page']['heading'] ?>
                                    </p>
                                </div>
                                <div class="login-section">
                                    <br>
                                    <p style="padding-left: 50px; padding-right: 50px;">
                                        You are currently logged in as <b><?php echo $_SESSION["logged_in"]["employer_detail"]["username"]; ?></b><br>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</main>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
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
            submitHandler: function(form) {
                form.submit();
            }
        });
    }


    function forgotpassword() {
        window.location.href = '<?= base_url('forgot_password') ?>';
    }
</script>