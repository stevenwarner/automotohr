<main>
    <div class="row">
        <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid">
            <div class="col-xs-12 background-image-css light-blue-background">
                <div class="first column-flex-center recovery-password-div-padding ">
                    <div class="box-div  background-white flex-coloumn padding-twenty">
                        <h1 class="automotoH1 darkGreyColor sora-family  margin-bottom-30 font-size-40">
                            <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['mainHeading']); ?>
                        </h1>

                        <p class="autmotoPara text-center margin-bottom-50 opacity-70">
                            <?php if ($this->session->flashdata('message')) { ?>
                        <div class="flash_error_message">
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <?php echo $this->session->flashdata('message'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['details']); ?>
                    </p>
                    <input class="light-blue-border" type="email" placeholder="email" value="<?php echo set_value('email'); ?>" id="email" name="email" />
                    <?php echo form_error('email'); ?>

                    <button class=" border-none password-recovery-submit" type="submit">
                        <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['buttonText']); ?>
                    </button>
                    <a href="<?= main_url($passwordRecoveryContent['page']["sections"]["section_0"]['buttonLinkCancel']); ?>" type="button" class="button d-flex justify-content-center align-items-center margin-top-20 cancel-button forgot-password-buttons btn-animate w-100">
                        <?= convertToStrip($passwordRecoveryContent['page']["sections"]["section_0"]['buttonTextCancel']); ?>
                    </a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 background-image-css light-blue-background not-show-on-mob ">
                <div class="first column-flex-center ">
                    <img src="<?= getImageURL($passwordRecoveryContent['page']["sections"]["section_0"]['sourceFile']) ?>" alt="with standing with key image" />
                </div>
            </div>
        </form>
    </div>
</main>