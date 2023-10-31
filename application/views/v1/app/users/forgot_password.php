<main>
    <div class="row">
        <div class="col-xs-12 background-image-css light-blue-background">
            <div class="first column-flex-center recovery-password-div-padding ">

                <div class="box-div  background-white flex-coloumn padding-twenty">
                    <h1 class="automotoH1 darkGreyColor sora-family  margin-bottom-30 font-size-40">
                        <?php echo $forgotPasswordContent['page']['heading'] ?>
                    </h1>
                    <p class="autmotoPara text-center margin-bottom-50 opacity-70">
                        <?= $forgotPasswordContent["page"]["subHeading"]; ?>
                    </p>
                    <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
                    <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid">
                        <input class="light-blue-border" type="email" name="email" placeholder="email" />
                        <?php echo form_error('email'); ?>
                        <button type="submit" class="margin-bottom-20 border-none password-recovery-submit w-100">
                            <?= $forgotPasswordContent["page"]["btn1Text"]; ?>
                        </button>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-xs-12 background-image-css light-blue-background not-show-on-mob">
            <div class="first column-flex-center ">
                <img src="<?= image_url("peoplewithkey.png"); ?>" alt="peoplewithkey" />
            </div>
        </div>
    </div>
</main>