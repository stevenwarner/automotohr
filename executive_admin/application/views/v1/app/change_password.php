<main>
    <div class="row">
        <form action="" method="post" id="change_password_form" name="change_password_form" class="ng-pristine ng-valid">
            <div class="col-xs-12 background-image-css light-blue-background">
                <div class="first column-flex-center recovery-password-div-padding ">
                    <div class="box-div  background-white flex-coloumn padding-twenty">
                        <h1 class="automotoH1 darkGreyColor sora-family  margin-bottom-30 font-size-40">
                            Executive Admin Change Password
                        </h1>

                        <p class="autmotoPara text-center margin-bottom-50 opacity-70">
                            <?php if ($this->session->flashdata('message')) { ?>
                        <div class="flash_error_message">
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <?php echo $this->session->flashdata('message'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    Please enter your new password:
                    </p>

                    <div class="fields-wrapper">
                        <input name="password" id="password" value="" class="light-blue-border" placeholder="New Password" type="password">
                    </div>

                    <div class="fields-wrapper">
                        <input name="retypepassword" id="retypepassword" value="" class="light-blue-border" placeholder="Retype New Password" type="password">
                    </div>

                    <button class=" border-none password-recovery-submit" type="submit">
                        Submit
                    </button>

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