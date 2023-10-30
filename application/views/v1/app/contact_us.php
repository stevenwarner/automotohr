<main>
    <div class="row">
        <div class="col-xs-12 column-flex-center background-border-radius top-background-div" style="background-image: url('<?= base_url("public/v1/images/"); ?>frame85.png');">
            <div class="background-image-div-contact-us  ">
                <h1 class="text-white contact-us-text"><?= $pageContent["page"]["contactUs"]["mainheading"]; ?></h1>
                <p class="text-white margin-top-twenty contact-us-line text-center-mobile"><?= $pageContent["page"]["contactUs"]["subheading"]; ?></p>
            </div>
            <div class="row margin-top-40 boxes-absolute ">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 padding-right-box">
                    <div class="background-white contact-boxes-width py-4">
                        <div class="sales-support-div">
                            <h2 class="sales-support"><?= $pageContent["page"]["salesHeading"]["text"]; ?></h2>
                        </div>
                        <div class="flex-center justify-content-center-mobile">
                            <div class="anchor-span-grey">
                                <a href="#" class="simple-anchor-icons "><i class="fa-solid  dark-grey-color fa-phone"></i></a>
                            </div>
                            <a class="contact-us-icons-size number-text " href="tel:<?= $pageContent["page"]["salesNumber"]["text"]; ?>"><?= $pageContent["page"]["salesNumber"]["text"]; ?></a>
                        </div>
                        <div class="flex-center justify-content-center-mobile">
                            <div class="anchor-span">
                                <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                            </div>
                            <a class="contact-us-icons-size email-text" href="mailto:<?= $pageContent["page"]["salesEmail"]["text"]; ?>"><?= $pageContent["page"]["salesEmail"]["text"]; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 ">
                    <div class="background-white contact-boxes-width py-4">
                        <div class="sales-support-div">
                            <h2 class="sales-support"><?= $pageContent["page"]["technicalHeading"]["text"]; ?></h2>
                        </div>
                        <div class="flex-center justify-content-center-mobile">
                            <div class="anchor-span-grey ">
                                <a href="#" class="simple-anchor-icons "><i class="fa-solid dark-grey-color fa-phone"></i></a>
                            </div>
                            <a class="contact-us-icons-size number-text " href="tel:<?= $pageContent["page"]["technicalNumber"]["text"]; ?>"><?= $pageContent["page"]["technicalNumber"]["text"]; ?></a>
                        </div>
                        <div class="flex-center justify-content-center-mobile">
                            <div class="anchor-span">
                                <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                            </div>
                            <a class="contact-us-icons-size email-text" href="mailto:<?= $pageContent["page"]["technicalEmail"]["text"]; ?>"><?= $pageContent["page"]["technicalEmail"]["text"]; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row margin-top-75 w-100 flex-jutify-center mobile-margin-none">
        <div class="w-75 flex-auto ">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6  px-3">
                <div class="mobile-padding-15">
                    <form id="jsContactForm" href="javascript:void(0)">
                        <div class="row">
                            <div class="col-xs-12 ">
                                <div class="form-adjustment">
                                    <h2 class="send-us-text margin-bottom-20 text-center-mobile"><?= $pageContent["page"]["formHeading1"]["text"]; ?></h2>
                                    <p class="dark-grey-color margin-bottom-30 text-center-mobile"><?= $pageContent["page"]["formHeading2"]["text"]; ?></p>
                                    <input class="contact-us-input dark-grey-color" placeholder="Your Name*" name="name" type="text" />
                                    <input class="contact-us-input dark-grey-color" placeholder="Your Email*" name="email" type="email" />
                                    <textarea placeholder="Your Message*" class="form-control border-radius-25 dark-grey-color" name="message" rows="4"></textarea>
                                    <p class="dark-grey-color margin-top-10">Please Check the Box*</p>
                                    <div class="g-recaptcha" data-sitekey="<?= getCreds("AHR")->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                                    <?php echo form_error('g-recaptcha-response'); ?>
                                    <br>
                                    <button class="d-flex justify-content-center align-items-center forgot-password-buttons btn-animate jsButtonAnimate jsContactBtn">
                                        <p class="text">Submit</p> <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mobile-display-none background-white">
                <div class="contact-us-image-div-adj">
                    <img alt="group photo" src="<?= base_url("public/v1/images/"); ?>Frame 40.png" />
                </div>

            </div>
        </div>
    </div>
</main>