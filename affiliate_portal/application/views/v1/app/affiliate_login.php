<main>
    <div class="row">
        <div class="col-xs-12 background-image-css" style="background-image: url(<?= image_url('AffiliateLoginimage.png') ?>">
            <div class="first column-flex-center first-div-padding">
                <div class="automoto-img-div margin-bottom-30">
                    <img alt="logo image " src=" <?= image_url('ahr_logo_new 1.png') ?>" />
                </div>

                <div class="box-div  background-white flex-coloumn padding-twenty">
                    <h1 class="executive-admin-text">Affiliate Login
                    </h1>
                    <?php if ($this->session->flashdata('message')) { ?>
                        <div class="flash_error_message">
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <?php echo $this->session->flashdata('message'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <form action="" class="form-horizontal" method="post">
                        <input class="executive-admin-input" placeholder="User Name" name="identity" value="<?php echo set_value('identity'); ?>" />
                        <?php echo form_error('identity'); ?>

                        <input class=" executive-admin-input" type="password" placeholder="Password" name="password" value="" />
                        <?php echo form_error('password'); ?>

                        <button class="m exective-input-buttons margin-bottom-20" name="submit">LOGIN</button>
                    </form>

                    <a href="<?php echo base_url('dashboard/forgot_password'); ?>" class="forgot-password-mention margin-bottom-zero">Forgot Password ?</a>
                    <a href="<?= main_url("affiliate-program"); ?>" class="not-automoto-affiliate dark-blue-text" target="_blank">Not An AutomotoHR Affiliate ?</a>
                </div>
                <p class="text-white margin-top-20 rights-white-text dark-blue-text">©2023 AutomotoHR. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</main>