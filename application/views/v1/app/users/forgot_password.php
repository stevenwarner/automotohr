<main>
    <div class="row">
        <div class="col-xs-12 background-image-css light-blue-background">
            <div class="first column-flex-center recovery-password-div-padding ">

                <div class="box-div  background-white flex-coloumn padding-twenty">
                    <h1 class="automotoH1 darkGreyColor sora-family  margin-bottom-30 font-size-40">
                        <?= convertToStrip($forgotPasswordContent['page']['sections']['section_0']['mainHeading']); ?>
                    </h1>
                    <p class="autmotoPara text-center margin-bottom-50 opacity-70">
                        <?= convertToStrip($forgotPasswordContent['page']['sections']['section_0']['details']); ?>
                    </p>
                    <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
                    <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid">
                        <input class="light-blue-border" type="email" name="email" placeholder="email" />
                        <?php echo form_error('email'); ?>
                        <button type="submit" class="margin-bottom-20 border-none password-recovery-submit w-100">
                            <?= convertToStrip($forgotPasswordContent['page']['sections']['section_0']['buttonText']); ?>
                        </button>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-xs-12 background-image-css light-blue-background not-show-on-mob">
            <div class="first column-flex-center ">
                <img src="<?= getImageURL($forgotPasswordContent['page']['sections']['section_0']['sourceFile']); ?>"
                    alt="peoplewithkey" />
            </div>
        </div>
    </div>
</main>
<?php $this->load->view("v1/app/cookie"); ?>