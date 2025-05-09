<main>

    <div class="row">
        <div class="col-xs-12 background-image-css"
            style="background-image: url(/assets/v1/app/images/loginBackground.png);">
            <div class="top-div">

                <?php if (!$this->session->userdata('logged_in')) { ?>
                    <?php if ($verification != NULL) { ?>
                        <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid">
                            <div class="parent-div">
                                <div class="first-div">
                                    <div class="high-lighted-text-div">
                                        <div class="highlighted-text-upper-div">
                                            <p class="highlighted-text">
                                                <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['mainHeading']); ?>
                                            </p>
                                        </div>

                                        <div class="login-section">
                                            <p>
                                                <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['details']); ?>
                                            </p>
                                            <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
                                            <input class="d-block login-inputs" placeholder="Password"
                                                value="<?php echo set_value('password'); ?>" type="password" id="password"
                                                name="password" style="margin-left: 20px;margin-right: 20px;" />
                                            <?php echo form_error('password'); ?>

                                            <input class="d-block login-inputs" placeholder="Re Enter Password"
                                                value="<?php echo set_value('retypepassword'); ?>" type="password"
                                                id="retypepassword" name="retypepassword"
                                                style="margin-left: 20px;margin-right: 20px;" />
                                            <?php echo form_error('retypepassword'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="second-div ">
                                    <div class="first-child  position-relative column-flex-center">
                                        <button class="jsButtonAnimationSecond login-screen-btns margin-top-30" value="Submit">
                                            <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['buttonText']); ?>
                                        </button>
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
                                            <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['mainHeading']); ?>
                                        </p>
                                    </div>
                                    <div class="login-section">
                                        <br>
                                        <p style="padding-left: 50px; padding-right: 50px; text-align: center;">
                                            Your user Name OR Verification Key is not Valid to Reset Password <br> Please click
                                            on Forgot Password
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="second-div ">
                                <div class="first-child position-relative column-flex-center">
                                    <a class="jsButtonAnimationSecond login-screen-btns margin-top-30"
                                        href="<?= base_url('forgot-password') ?>" value="Submit"> Forgot Password</a>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="parent-div">
                        <div class="first-div">
                            <div class="high-lighted-text-div">
                                <div class="highlighted-text-upper-div">
                                    <p class="highlighted-text">
                                        <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['mainHeading']); ?>
                                    </p>
                                </div>
                                <div class="login-section">
                                    <br>
                                    <p style="padding-left: 50px; padding-right: 50px;">
                                        You are currently logged in as
                                        <b><?php echo $_SESSION["logged_in"]["employer_detail"]["username"]; ?></b><br>
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
<?php $this->load->view("v1/app/cookie"); ?>