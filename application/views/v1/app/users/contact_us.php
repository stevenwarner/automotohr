<main>
    <div class="row">
        <div class="col-xs-12 column-flex-center background-border-radius top-background-div"
            style="background-image: url(/assets/v1/app/images/frame85.png);">
            <div class="background-image-div-contact-us  ">
                <h1 class="text-white contact-us-text">Contact Us</h1>
                <p class="text-white margin-top-twenty contact-us-line text-center-mobile">Contact one of our talent
                    network partners at </p>
            </div>
            <div class="row margin-top-40 boxes-absolute ">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 padding-right-box">
                    <div class="background-white contact-boxes-width py-4">
                        <div class="sales-support-div">
                            <h2 class="sales-support"> <?php echo $contactUsContent['page']['salesHeading']['text'] ?>
                            </h2>
                        </div>
                        <div class="flex-center justify-content-center-mobile">
                            <div class="anchor-span-grey">
                                <a href="#" class="simple-anchor-icons "><i
                                        class="fa-solid  dark-grey-color fa-phone"></i></a>
                            </div>
                            <a class="contact-us-icons-size number-text "
                                href="tel:<?php echo $contactUsContent['page']['salesNumber']['text'] ?>">
                                <?php echo $contactUsContent['page']['salesNumber']['text'] ?></a>
                        </div>
                        <div class="flex-center justify-content-center-mobile">
                            <div class="anchor-span">
                                <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                            </div>
                            <a class="contact-us-icons-size email-text"
                                href="mailto:<?php echo $contactUsContent['page']['salesEmail']['text'] ?>"><?php echo $contactUsContent['page']['salesEmail']['text'] ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 ">
                    <div class="background-white contact-boxes-width py-4">
                        <div class="sales-support-div">
                            <h2 class="sales-support">
                                <?php echo $contactUsContent['page']['technicalHeading']['text'] ?></h2>
                        </div>
                        <div class="flex-center justify-content-center-mobile">
                            <div class="anchor-span-grey ">
                                <a href="#" class="simple-anchor-icons "><i
                                        class="fa-solid dark-grey-color fa-phone"></i></a>
                            </div>
                            <a class="contact-us-icons-size number-text "
                                href="tel:<?php echo $contactUsContent['page']['technicalNumber']['text'] ?>"><?php echo $contactUsContent['page']['technicalNumber']['text'] ?></a>
                        </div>
                        <div class="flex-center justify-content-center-mobile">
                            <div class="anchor-span">
                                <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                            </div>
                            <a class="contact-us-icons-size email-text"
                                href="mailto:<?php echo $contactUsContent['page']['technicalEmail']['text'] ?>"><?php echo $contactUsContent['page']['technicalEmail']['text'] ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row margin-top-10 w-100 flex-jutify-center mobile-margin-none">
        <div class="w-75 flex-auto ">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6  px-3">
                <div class="mobile-padding-15">
                    <form action="" method="post" id="contactusform">
                        <div class="row">
                            <div class="col-xs-12 ">

                                <div class="form-adjustment">
                                    <h2 class="send-us-text margin-bottom-20 text-center-mobile">
                                        <?php echo $contactUsContent['page']['formHeading1']['text'] ?> </h2>
                                    <p class="dark-grey-color margin-bottom-30 text-center-mobile">
                                        <?php echo $contactUsContent['page']['formHeading2']['text'] ?></p>

                                    <?php if ($this->session->flashdata('message')) { ?>
                                        <div class="flash_error_message">
                                            <div class="alert alert-info alert-dismissible" role="alert">
                                                <?php echo $this->session->flashdata('message'); ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <input class="contact-us-input dark-grey-color" placeholder="Your Name*"
                                        value="<?php echo set_value('name'); ?>" type="text" name="name" id="name" />
                                    <?php echo form_error('name');
                                    ?>
                                    <input class="contact-us-input dark-grey-color" placeholder="Your Email*"
                                        value="<?php echo set_value('email'); ?>" type="Email" id="email"
                                        name="email" />
                                    <?php echo form_error('email');
                                    ?>

                                    <textarea placeholder="Your Message"
                                        class="form-control border-radius-25 dark-grey-color" id="message" rows="4"
                                        name="message"><?php echo set_value('message'); ?> </textarea>
                                    <?php echo form_error('message'); ?>

                                    <p class="dark-grey-color margin-top-10">Please Check the Box*</p>
                                    <div class="fields-wrapper  ">
                                        <div class="g-recaptcha"
                                            data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                    </div>
                                    <?php echo form_error('g-recaptcha-response'); ?>

                                    <br><button
                                        class="d-flex justify-content-center align-items-center forgot-password-buttons btn-animate"
                                        type="submit" onclick="validate_form()">
                                        <p class="text">Submit</p> <i
                                            class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mobile-display-none background-white">
                <div class="contact-us-image-div-adj">
                    <img alt="group photo" src="/assets/v1/app/images/Frame 40.png" />
                </div>

            </div>
        </div>

    </div>

    </div>
</main>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript"
    src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>



<script type="text/javascript">
    function validate_form() {
        $("#contactusform").validate({
            ignore: ":hidden:not(select)",
            rules: {
                email: {
                    required: true,
                    email: true
                },
                name: {
                    required: true
                },
                message: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: '<p class="error"><i class="fa fa-exclamation-circle"></i> Your Valid Email is required</p>'
                },
                name: {
                    required: '<p class="error"><i class="fa fa-exclamation-circle"></i> Your Name is required</p>'
                },
                message: {
                    required: '<p class="error"><i class="fa fa-exclamation-circle"></i> Your Message is required</p>'
                },

            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
</script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php $this->load->view("v1/app/cookie"); ?>