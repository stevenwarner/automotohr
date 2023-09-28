<main>
    <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
    <div class="row">
        <div class="col-xs-12 background-image-css" style="background-image: url(/assets/v1/app/images/loginBackground.png);">
            <div class="top-div">
                <form action="" method="post" id="forgotForm" class="ng-pristine ng-valid">
                    <div class="parent-div">
                        <div class="first-div">
                            <div class="high-lighted-text-div">
                                <div class="highlighted-text-upper-div">
                                    <p class="highlighted-text">
                                        FORGOT PASSWORD
                                    </p>
                                </div>

                                <div class="login-section">
                                    <p>Please, enter your email in the field below and we'll <br /> send you a link to a page where you can change <br /> your password: </p>
                                    <input class="d-block login-inputs" placeholder="Email" value="<?php echo set_value('email'); ?>" type="Email" id="email" name="email" />
                                    <?php echo form_error('email'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="second-div ">
                            <div class="first-child position-relative column-flex-center">
                                <button class=" login-screen-btns margin-top-30" onclick="validate_form()" value="Submit"> Submit <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i> </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>
<script type="text/javascript">
    function validate_form() {
        $("#forgotForm").validate({
            ignore: ":hidden:not(select)",
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: '<p class="error"><i class="fa fa-exclamation-circle"></i> Please provide Valid email</p>'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }
</script>