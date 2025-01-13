<main>
    <div class="row">
        <div class="col-xs-12 background-image-css" style="background-image: url('<?= AWS_S3_BUCKET_URL . $loginContent['page']['sections']['section_0']['sourceFile']; ?>');">
            <div class="top-div">
                <div class="parent-div">
                    <div class="first-div">
                        <div class="high-lighted-text-div">
                            <div class="highlighted-text-upper-div">
                                <p class="highlighted-text">
                                    <?= convertToStrip($loginContent['page']['sections']['section_0']['mainHeading']); ?>
                                </p>
                            </div>
                            <form action="" method="post" id="loginForm" class="ng-pristine ng-valid">
                                <div class="login-section">

                                    <h1>
                                        <?= convertToStrip($loginContent['page']['sections']['section_0']['subHeading']); ?>
                                    </h1>
                                    <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
                                    <input class="d-block login-inputs" placeholder="Username" name="username" id="email" value="<?php echo set_value('username'); ?>" />
                                    <?php echo form_error('username'); ?>

                                    <input class="d-block login-inputs" placeholder="Password" type="password" id="password" name="password" />
                                    <?php echo form_error('password'); ?>

                                    <div class="w-full forgot-password-text">
                                        <span><a href="<?php echo site_url('forgot-password'); ?>">Forgot your Username / Password?</a></span>
                                    </div>

                                    <div class="margin-top-30">
                                        <button class="d-block login-screen-btns jsButtonAnimationSecond" type="submit" value="Login">
                                            <p>
                                                <?= convertToStrip($loginContent['page']['sections']['section_0']['buttonText']); ?>
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
                            <h2>
                                <?= convertToStrip($loginContent['page']['sections']['section_0']['mainHeadingExecutiveAdmin']); ?>
                            </h2>
                            <a class="login-screen-btns  jsButtonAnimationSecond margin-top-30" href="<?= base_url($loginContent['page']['sections']['section_0']['buttonLinkExecutiveAdmin']); ?>">
                                <?= convertToStrip($loginContent['page']['sections']['section_0']['buttonTextExecutiveAdmin']); ?>
                            </a>
                        </div>
                        <div class="second-child position-relative column-flex-center">
                            <div class="or-div">
                                <div class="or-child-div">
                                    Or
                                </div>
                            </div>
                            <h2>
                                <?= convertToStrip($loginContent['page']['sections']['section_0']['mainHeadingContact']); ?>
                            </h2>
                            <a href="<?= base_url($loginContent['page']['sections']['section_0']['buttonLinkContact']); ?>" class=" login-screen-btns jsButtonAnimationSecond margin-top-30">
                                <p class="btn-text">
                                    <?= convertToStrip($loginContent['page']['sections']['section_0']['buttonTextContact']); ?>
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>