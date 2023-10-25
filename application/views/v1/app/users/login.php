<main>
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

                                    <h1><?php echo $loginContent['page']['sections']['section1']['heading'] ?>
                                    </h1>
                                    <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
                                    <input class="d-block login-inputs" placeholder="Username" name="username" id="email" value="<?php echo set_value('username'); ?>" />
                                    <?php echo form_error('username'); ?>

                                    <input class="d-block login-inputs" placeholder="Password" type="password" id="password" name="password" />
                                    <?php echo form_error('password'); ?>

                                    <div class="w-full forgot-password-text">
                                        <span><a href="<?php echo site_url('forgot-password'); ?>">Forgot Password ?</a></span>
                                    </div>

                                    <div class="margin-top-30">
                                        <button class="d-block login-screen-btns jsButtonAnimationSecond" type="submit" value="Login">
                                            <p><?php echo $loginContent['page']['sections']['section1']['btnText'] ?>
                                            </p>
                                        </button>
                                    </div>
                                    <br>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="second-div ">

                        <div class="first-child position-relative column-flex-center">
                            <div class="or-div">
                                <div class="or-child-div">
                                    Or
                                </div>
                            </div>
                            <h2><?php echo $loginContent['page']['sections']['section2']['heading'] ?></h2>
                            <a class="login-screen-btns  jsButtonAnimationSecond margin-top-30" href="<?= base_url("executive_admin"); ?>"> <?php echo $loginContent['page']['sections']['section2']['btnText'] ?> </a>
                        </div>
                        <div class="second-child position-relative column-flex-center">
                            <div class="or-div">
                                <div class="or-child-div">
                                    Or
                                </div>
                            </div>
                            <h2><?php echo $loginContent['page']['sections']['section3']['heading'] ?></h2>
                            <a href="<?= base_url("schedule_your_free_demo"); ?>" class=" login-screen-btns jsButtonAnimationSecond margin-top-30">
                                <p class="btn-text"><?php echo $loginContent['page']['sections']['section3']['btnText'] ?></p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>