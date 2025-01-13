<main>
    <div class="row">
        <div class="col-xs-12 background-image-css" style="background-image: url('<?= image_url("AffiliateLoginimage.png") ?>')">
            <div class="first column-flex-center first-div-padding">
                <div class="box-div background-white flex-coloumn padding-twenty over_ride_box">
                    <h1 class="background text-white automotoH1 font_size_40 excutive_admin_h1_font_size padding_dual">
                        Executive Admin Login
                    </h1>
                    <?php if($this->session->flashdata('message')){ ?>
                    <div class="flash_error_message">
                        <div class="alert alert-info alert-dismissible text-center" role="alert">
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    </div>
                    <?php }?>

                    <div class="padding_on_four">
                        <form action="" class="form-horizontal" method="post">

                            <div class="Rectangle text_feild">
                                <div class="input-icon">
                                    <img src="<?= image_url("Lock1.png"); ?>" alt="User" />
                                </div>
                                <input class="input_feild_backgroundColor inter-family font-weight_600" placeholder="User Name" name="identity" value="<?php echo set_value('identity'); ?>" />
                            </div>
                            <?php echo form_error('identity'); ?>

                            <div class="Rectangle text_feild">
                                <div class="input-icon">
                                    <img src="<?= image_url("Lock2.png"); ?>" alt="lock_icon" class="ms-2" />
                                </div>
                                <input class="input_feild_backgroundColor inter-family font-weight_600" type="password" placeholder="Password" name="password" />
                            </div>
                            <?php echo form_error('password'); ?>

                            <a href="<?= base_url('dashboard/forgot_password'); ?>" class="forgot-password-mention margin-bottom-zero text-end d-block inter-family forget_text_color">Forgot your Username / Password? Click Here</a>
                            <button class="m exective-input-buttons margin-bottom-20 w-100 login_btn center-horizontally font-size_24" type="submit">
                                LOGIN
                            </button>
                    </div>
                    </form>
                </div>
                <p class="text-white margin-top-20 rights-white-text dark-blue-text">
                    ©2023 AutomotoHR. All Rights Reserved.
                </p>
            </div>
        </div>
    </div>
</main>