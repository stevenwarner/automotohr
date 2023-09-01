<main>
    <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
    <div class="row">
        <div class="col-xs-12 background-image-css" style="background-image: url(/assets/v1/app/images/loginBackground.png);">
            <div class="top-div">
                <div class="parent-div">
                    <div class="first-div">
                        <div class="high-lighted-text-div">
                            <div class="highlighted-text-upper-div">
                                <p class="highlighted-text">

                                    ALREADY REGISTERED
                                </p>
                            </div>
                            <form action="" method="post" id="loginForm" class="ng-pristine ng-valid">
                                <div class="login-section">
                                    <h1>Login here</h1>
                                    <input class="d-block login-inputs" placeholder="Username" name="username" id="email" value="<?php echo set_value('username'); ?>" />
                                    <?php echo form_error('username'); ?>

                                    <input class="d-block login-inputs" placeholder="Password" type="password" id="password" name="password" />
                                    <?php echo form_error('password'); ?>

                                    <div class="w-full forgot-password-text">
                                        <span><a href="<?php echo site_url('forgot_password'); ?>">Forgot Password ?</a></span>
                                    </div>

                                    <div class="margin-top-30">
                                        <button class="d-block login-screen-btns" type="submit" value="Login"> Login <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i> </button>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                    <div class="second-div ">

                        <div class="first-child position-relative column-flex-center">
                            <div class="or-div">
                                <div class="or-child-div">
                                    Or
                                </div>
                            </div>
                            <h2>Executive Admin Login here</h2>
                            <button class=" login-screen-btns margin-top-30" id="executiveadmin"> Executive Admin Login <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i> </button>
                        </div>
                        <div class="second-child position-relative column-flex-center">
                            <div class="or-div">
                                <div class="or-child-div">
                                    Or
                                </div>
                            </div>
                            <h2>Don't Have An AutomotoHR Account Yet? <br />No Problem Get Yours Today.</h2>
                            <button class=" login-screen-btns margin-top-30" id="contactsupport"> Contact Support <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    $('input').keydown(function(e) {
        if (e.keyCode == 13) {
            $(this).closest('form').submit();
        }
    });


    $("#executiveadmin").click(function() {
        window.location.href = '<?php echo STORE_PROTOCOL_SSL . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']) . 'executive_admin'; ?>';
    });

    $("#contactsupport").click(function() {
        window.location.href = '<?php echo base_url('schedule_your_free_demo'); ?>';
    });
</script>