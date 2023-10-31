<main>
    <div class="row">
        <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid">
            <div class="col-xs-12 background-image-css light-blue-background">
                <div class="first column-flex-center recovery-password-div-padding ">
                    <div class="box-div  background-white flex-coloumn padding-twenty">
                        <h1 class="automotoH1 darkGreyColor sora-family  margin-bottom-30 font-size-40">
                            <?php echo $passwordRecoveryContent['page']['heading']; ?>
                        </h1>

                        <p class="autmotoPara text-center margin-bottom-50 opacity-70">
                            <?php if ($this->session->flashdata('message')) { ?>
                        <div class="flash_error_message">
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <?php echo $this->session->flashdata('message'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php echo $passwordRecoveryContent['page']['subHeading']; ?>
                    </p>
                    <input class="light-blue-border" type="email" placeholder="email" value="<?php echo set_value('email'); ?>" id="email" name="email" />
                    <?php echo form_error('email'); ?>

                    <button class=" border-none password-recovery-submit" type="submit"><?php echo $passwordRecoveryContent['page']['btnText']; ?></button>
                    <a href="<?= base_url('login'); ?>" type="button" class="button d-flex justify-content-center align-items-center margin-top-20 cancel-button forgot-password-buttons btn-animate w-100">
                        Cancel
                            </a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 background-image-css light-blue-background not-show-on-mob ">
                <div class="first column-flex-center ">
                    <img src="<?= image_url('manstandingwithkey.png'); ?>" alt="with standing with key image" />
                </div>
            </div>
        </form>
    </div>
</main>