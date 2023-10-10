<main>
    <section class="padding_top">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <p class="Affiliate-title Opacity_80">
                    <span class="highlighted-light-blue-div">Fuel</span> your Finances
                </p>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                    <h1 class="Affiliate-heading"><?php echo $affiliateContent['page']['sections']['section1']['heading'] ?></h1>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center no_padding_on_mobile">
                    <p class="Affiliate-content Opacity_90">
                        <?php echo $affiliateContent['page']['sections']['section1']['headingDetail'] ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="row padding_bottom_120">
            <div class="col-xs-12 w-100 column-flex-center">
                <div class="w-80 pt-5">
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="position_relative">
                                <img src="./assets/v1/app/images/Affiliate1.png" alt="Affiliate1" class="image_width_80" />
                                <div class="pink_bubble"></div>
                                <div class="green_bubble"></div>
                                <div class="red_bubble">
                                    <div class="red_circle"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 position_relative">
                            <h3 class="Affiliate-subtitle">
                                <?php echo $affiliateContent['page']['sections']['section2']['heading'] ?>
                            </h3>
                            <p class="Affiliate-content-picture Opacity_90">
                                <?php echo $affiliateContent['page']['sections']['section2']['headingDetail'] ?>
                            </p>
                            <button class="Affiliate-btn">
                                <p class="text"><?php echo $affiliateContent['page']['sections']['section2']['btnText'] ?></p>
                                <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                            </button>
                            <div class="Blue_bubble"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="blue-form-section section-top-padding">
        <div class="row">
            <div class="col-sm-12 margin-bottom-20">
                <p class="text-center affiliate-screen-top-heading">
                    <?php echo $affiliateContent['page']['sections']['section3']['heading'] ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 center-horizontally">
                <div class="blue-form w-80">
                    <form enctype="multipart/form-data" id="affiliated-form" method="post" action="<?= base_url('can-we-send-you-a-check-every-month') ?>
">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>

                                <p class="highlighted-div text-white opacity-90-product margin-top-60">
                                    <span class="highlighted-light-blue-div background"> <?php echo $affiliateContent['page']['sections']['section3']['heading1'] ?>
                                    </span>

                                </p>
                                <h1 class="white-heading-product text-white margin-bottom-40">
                                    <?php echo $affiliateContent['page']['sections']['section3']['heading2'] ?>
                                </h1>

                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                <input class="w-100" placeholder="First Name*" name="firstname" type="text" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                <input class="w-100" placeholder="Last Name*" name="lastname" type="text" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                <input class="w-100" placeholder="Email*" name="email" type="email" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <input class="w-100" placeholder="Paypal Email" name="paypal_email" type="email" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 margin-bottom-15">
                                <a class="affiliate-anchor-color under-line margin-left-15 extra-text" href="https://www.paypal.com/welcome/signup/#/email_one_password" target="_blank">
                                    Create your FREE PayPal account now.</a>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Company" name="company" type="text" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Street*" name="street" type="text" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="City*" name="city" type="text" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="State / Province*" name="state" type="text" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Zip Code / Postal Code" name="zip" type="text" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <select class="form-select w-100 select-form-field" aria-label="Default select example" name="country" id="country">
                                    <option value="">Please Select Country</option>
                                    <?php foreach ($countries as $country) { ?>
                                        <option value="<?php echo $country['country_name'] ?>">
                                            <?php echo $country['country_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Method Of Promotion" name="MOP" type="text" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Your Website" type="text" name="website" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Contact Number*" type="text" name="contact_number" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Do you have an list ? if so, how many name?" type="number" name="no_of_names" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <p class="text-white extra-text margin-left-15 margin-bottom-10">
                                    Please Upload W9 Form ( For Our U.S Affiliates )
                                </p>

                                <div class="file-btn-div">
                                    <input type="file" id="file-input" class="upload-file-input" name="w9_form" />
                                    <label for="file-input" id="first-upload-btn" class="custom-file-upload">
                                        Upload File
                                    </label>
                                </div>

                                <p class="text-white extra-text margin-left-15 margin-bottom-20">
                                    W9 link:
                                    <a class="under-line" href="https://www.irs.gov/pub/irs-pdf/fw9.pdf">https://www.irs.gov/pub/irs-pdf/fw9.pdf</a>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <p class="text-white extra-text margin-left-15 margin-bottom-10">
                                    Please Upload W8 Form ( For Affiliates Outside of the U.S )
                                </p>
                                <div class="file-btn-div">
                                    <input type="file" id="file-input-two" class="upload-file-input" name="w8_form" />
                                    <label for="file-input-two" id="first-upload-btn" class="custom-file-upload">
                                        Upload File
                                    </label>
                                </div>
                                <p class="text-white extra-text margin-left-15 margin-bottom-20">
                                    W8 link:
                                    <a class="under-line" href="https://www.irs.gov/pub/irs-pdf/fw8ben.pdf">https://www.irs.gov/pub/irs-pdf/fw8ben.pdf
                                    </a>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <textarea placeholder="Message" class="form-control border-radius-25" rows="8" id="location_address"></textarea>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <p class="text-white margin-left-15 margin-top-30 extra-text">
                                    Security check
                                </p>

                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 margin-bottom-30">
                                <p class="text-white extra-text">
                                    Please Acknowledge that you have Read and Agree with the
                                    AutomotoHR Terms of Service and Privacy Policy and by
                                    adding a check mark below and proceeding with the
                                    application you are giving your express permission to
                                    contact you, store your personal data for the purpose of
                                    the AutomotoHR Affiliate Program tracking and payments.
                                    Please mark the box if you Agree. Please mark the box if
                                    you Agree.
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <input class="form-check-input checkbox" type="checkbox" value="" id="flexCheckDefault" name="terms_and_condition" />
                                <label class="form-check-label text-white checkbox-label" for="flexCheckDefault">
                                    By checking this box, you agree to our
                                    <a href="#" class="under-line affiliate-anchor-color">Terms of Service</a>
                                    and
                                    <a href="<?= base_url('services/privacy-policy') ?>" class="under-line affiliate-anchor-color" target="_blank">
                                        Privacy Policy.</a>
                                </label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center margin-top-30">
                                <button class="d-flex justify-content-center align-items-center affiliate-submit" id="app-submit" type="submit">
                                    <p class="text"><?php echo $affiliateContent['page']['sections']['section3']['btnText'] ?>
                                    </p>
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="row padding_top_bottom">
            <div class="col-xs-12 w-100 column-flex-center">
                <div class="w-80">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 padding_LR position_relative order-2-products">
                            <h3 class="first-heading">
                                <?php echo $affiliateContent['page']['sections']['section4']['heading'] ?>
                            </h3>

                            <h3 class="second-heading">
                                <?php echo $affiliateContent['page']['sections']['section4']['heading1'] ?>
                            </h3>

                            <ul class="padding_top_29">
                                <div class="d-flex">
                                    <img src="./assets/v1/app/images/tick.png" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?php echo $affiliateContent['page']['sections']['section4']['tick1'] ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="./assets/v1/app/images/tick.png" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?php echo $affiliateContent['page']['sections']['section4']['tick2'] ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="./assets/v1/app/images/tick.png" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?php echo $affiliateContent['page']['sections']['section4']['tick3'] ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="./assets/v1/app/images/tick.png" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?php echo $affiliateContent['page']['sections']['section4']['tick4'] ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="./assets/v1/app/images/tick.png" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?php echo $affiliateContent['page']['sections']['section4']['tick5'] ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="./assets/v1/app/images/tick.png" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?php echo $affiliateContent['page']['sections']['section4']['tick6'] ?>
                                    </li>
                                </div>
                            </ul>

                            <div class="blue_Bubble"></div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 image_end_on position_relative order-1-products d-flex align-items-center">
                            <img src="./assets/v1/app/images/whyChoose.png" alt="whyChoose" class="image_width_on_medium" />
                            <div class="Ellipse"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="blue-image-with-text-on-right dark-blue-background position-relative mobile-section-padding">
        <img src="./assets/v1/app/images/fullsmallpinkcircle.png" class="fullsmallpinkcircle" alt="full-small-pink-circle" />
        <div class="row">
            <div class="col-xs-12 column-flex-center affiliate-blue-section-padding">
                <div class="w-80">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 position-relative">
                            <img src="./assets/v1/app/images/halfredcircle.png" class="half-red-circle" alt="half-red-circle" />
                            <img src="./assets/v1/app/images/womenwithlaptop.png" alt="women-with-laptop" class="product-image-div" />
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 position-relative">
                            <img src="./assets/v1/app/images/fullsmallyellow.png" class="fullsmallyellow" alt="full-small-yellow" />
                            <h2 class="text-white first-heading text-left margin-bottom-20">
                                <?php echo $affiliateContent['page']['sections']['section5']['heading'] ?>
                            </h2>

                            <h3 class="text-white second-heading margin-bottom-10">
                                <?php echo $affiliateContent['page']['sections']['section5']['heading1'] ?>
                            </h3>

                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?php echo $affiliateContent['page']['sections']['section5']['tick1'] ?>
                                </p>
                            </div>
                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?php echo $affiliateContent['page']['sections']['section5']['tick2'] ?>
                                </p>
                            </div>
                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?php echo $affiliateContent['page']['sections']['section5']['tick3'] ?>
                                </p>
                            </div>

                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?php echo $affiliateContent['page']['sections']['section5']['tick4'] ?>
                                </p>
                            </div>

                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?php echo $affiliateContent['page']['sections']['section5']['tick5'] ?>
                                </p>
                            </div>

                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?php echo $affiliateContent['page']['sections']['section5']['tick6'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-xs-12 w-100 column-flex-center padding_top_bottom">
                <div class="w-80">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 padding_LR order-2-products">
                            <h3 class="first-heading">
                                <?php echo $affiliateContent['page']['sections']['section6']['heading'] ?>
                            </h3>
                            <div class="row margin_top position_relative">
                                <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <h3 class="second-heading">
                                        <?php echo $affiliateContent['page']['sections']['section6']['heading1'] ?>
                                    </h3>
                                    <p class="affiliate-paragraph">
                                        <?php echo $affiliateContent['page']['sections']['section6']['heading1Detail'] ?>
                                    </p>
                                </div>
                                <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <h3 class="second-heading"><?php echo $affiliateContent['page']['sections']['section6']['heading2'] ?>
                                    </h3>
                                    <p class="affiliate-paragraph">
                                        <?php echo $affiliateContent['page']['sections']['section6']['heading2Detail'] ?>
                                    </p>
                                </div>
                                <div class="light_blue_Bubble"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 d-flex align-items-center position_relative image_center_on_tab order-1-products">
                            <img src="./assets/v1/app/images/image 80.png" alt="image_80" class="image_width_100" />
                            <div class="light_pink_bubble"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="fifth-section-audience light-grey-background-affiliate position-relative mobile-section-padding">
        <img src="/assets/v1/app/images/fullsmallpinkcircle.png" class="small-pink-fith-section" alt="small-pink-circle" />
        <div class="row">
            <div class="col-xs-12 column-flex-center affiliate-blue-section-padding">
                <div class="w-80">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 position-relative">
                            <img src="/assets/v1/app/images/smallfulllightblue.png" class="small-lightblue-fith-section" alt="small-light-blue-circle" />
                            <img src="/assets/v1/app/images/Group 6966.png" class="product-image-div" alt="girl-with-glasses" />
                            <img src="/assets/v1/app/images/smallfullyellow.png" class="small-fullyellow-fith-section" alt="small-yellow-circle" />
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 position-relative">
                            <img src="/assets/v1/app/images/smallfullred.png" class="small-fullred-fith-section" alt="small-red-circle" />
                            <h2 class="first-heading dark-grey-affiliate text-left">
                                <?php echo $affiliateContent['page']['sections']['section7']['heading'] ?>
                            </h2>
                            <p class="affiliate-paragraph opacity-90-product">
                                <?php echo $affiliateContent['page']['sections']['section7']['headingDetail'] ?>
                            </p>

                            <div class="row">
                                <div class="col-xs-12">
                                    <h3 class="third-heading dark-grey-affiliate dark-grey-affiliate">
                                        <?php echo $affiliateContent['page']['sections']['section7']['heading1'] ?>

                                    </h3>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?php echo $affiliateContent['page']['sections']['section7']['tick1'] ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?php echo $affiliateContent['page']['sections']['section7']['tick2'] ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?php echo $affiliateContent['page']['sections']['section7']['tick3'] ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?php echo $affiliateContent['page']['sections']['section7']['tick4'] ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?php echo $affiliateContent['page']['sections']['section7']['tick5'] ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?php echo $affiliateContent['page']['sections']['section7']['tick6'] ?>
                                        </p>
                                    </div>
                                    <p class="opacity-90-product affiliate-paragraph margin-top-10">
                                        <?php echo $affiliateContent['page']['sections']['section7']['headingDetail'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="padding-top-50">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 column-center padding-20-products">
                            <h1 class="first-heading text-center">
                                <?php echo $affiliateContent['page']['sections']['section8']['heading'] ?>
                            </h1>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center padding-20-products">
                            <h3 class="affiliate-sub-heading margin-bottom-10">
                                <span class="big-number-affiliate">1</span> <?php echo $affiliateContent['page']['sections']['section8']['heading1'] ?>

                            </h3>
                            <p class="opacity-90-product affiliate-paragraph margin-bottom-20 margin-left-20">
                                <?php echo $affiliateContent['page']['sections']['section8']['heading1Detail'] ?>
                            </p>
                            <h3 class="affiliate-sub-heading margin-bottom-10">
                                <span class="big-number-affiliate">2</span> <?php echo $affiliateContent['page']['sections']['section8']['heading2'] ?>
                            </h3>

                            <p class="opacity-90-product affiliate-paragraph margin-left-20">
                                <?php echo $affiliateContent['page']['sections']['section8']['heading2Detail'] ?>
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-5">
                            <div class="text-center width-height-100 d-flex align-items-center">
                                <img class="w-100" src="./assets/v1/app/images/image 81.png" alt="dashboard" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100 flex">
            <div class="w-80 column-flex-center padding_top_60">
                <h3 class="first-heading text-center">
                    <?php echo $affiliateContent['page']['sections']['section9']['heading'] ?>
                </h3>
                <p class="affiliate-paragraph text-center margin-top-10 opacity-90-product">
                    <?php echo $affiliateContent['page']['sections']['section9']['headingDetail'] ?>
                </p>
                <div class="row margin_top padding_square_side">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 box_center_on_mobile">
                        <div class="common_card_box">
                            <div class="padding_top_left">
                                <p class="box_heading"><?php echo $affiliateContent['page']['sections']['section9']['heading1'] ?>
                                </p>
                                <p class="affiliate-paragraph opacity-90-product">
                                    <?php echo $affiliateContent['page']['sections']['section9']['heading1Detail'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 box_center_on_mobile">
                        <div class="common_card_box">
                            <div class="padding_top_left">
                                <p class="box_heading"><?php echo $affiliateContent['page']['sections']['section9']['heading2'] ?></p>
                                <p class="affiliate-paragraph opacity-90-product">
                                    <?php echo $affiliateContent['page']['sections']['section9']['heading2Detail'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 box_center_on_mobile">
                        <div class="common_card_box">
                            <div class="padding_top_left">
                                <p class="box_heading"><?php echo $affiliateContent['page']['sections']['section9']['heading3'] ?></p>
                                <p class="affiliate-paragraph opacity-90-product">
                                    <?php echo $affiliateContent['page']['sections']['section9']['heading3Detail'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="padding-top-50">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-5 position-relative">
                            <div class="text-center width-height-100 not-show-on-mob">
                                <img class="w-100 man-standing-image" src="./assets/v1/app/images/image 42.png" alt="dashboard" />
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-7 column-center padding-20-products">
                            <form method="post" action="<?= base_url('schedule_your_free_demo'); ?>" class="form" id="schedule-free-demo-form">

                                <div class="position-relative">
                                    <img src="./assets/v1/app/images/Ellipse 9.png" class="man-standingimage second-purple-half" alt="half-purple-circle" />
                                    <div class="form-div">
                                        <div class="highlighted-div column-flex-center opacity-80-product">
                                            <p>
                                                <span class="highlighted-light-blue-div">Want</span>the Inside Secret on People Operations?
                                            </p>
                                        </div>

                                        <h2 class=""><?php echo $affiliateContent['page']['sections']['section10']['heading'] ?></h2>

                                        <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>
                                        <input type="hidden" class="d-block " id="pagename" placeholder="Name*" name="pagename" value="<?php echo $pageSlug; ?>" />
                                        <input class="d-block" placeholder="Name*" id="name" placeholder="Name*" name="name" />
                                        <?php echo form_error('name'); ?>
                                        <input class="d-block" placeholder="Email*" id="email_id" placeholder="Email*" name="email" />
                                        <?php echo form_error('email'); ?>

                                        <input class="d-block" placeholder="Phone Number*" id="phone_number" name="phone_number" />
                                        <input class="title-field" placeholder="Title*" name="title" />
                                        <?php echo form_error('title'); ?>

                                        <select class="form-select select-form-field" aria-label="Default select example" name="company_size">
                                            <option selected>Employee Count*</option>
                                            <option value="1-5">1 - 5</option>
                                            <option value="6-25">6 - 25</option>
                                            <option value="26-50">26 - 50</option>
                                            <option value="51-100">51 - 100</option>
                                            <option value="101-250">101 - 250</option>
                                            <option value="251-500">251 - 500</option>
                                            <option value="501+">501+</option>
                                        </select>
                                        <input class="d-block" placeholder="Company Name*" name="company_name" />
                                        <textarea placeholder="Your Message" class="form-control border-radius-25 dark-grey-color" id="exampleFormControlTextarea1" rows="4" name="client_message"></textarea>
                                        <div class="form-group mt-4">
                                            <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                            <?php echo form_error('g-recaptcha-response'); ?>
                                        </div>
                                        <button class="margin-top-twent w-100 center-horizontally schedule-btn-product margin-top-twenty btn-animate" id="schedule-free-demo-form-submit" type="submit">
                                            <p class="text screen-adjusted-text">
                                                Schedule Your No Obligation Consultation
                                            </p>
                                            <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                        </button>
                                    </div>
                                    <img src="./assets/v1/app/images/yellow-half.png" class="second-light-blue-half-circle-form" alt="half-purple-circle" />
                                </div>
                             </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

<script type="text/javascript">
    $(document).ready(function() {

        $("#affiliated-form").validate({
            ignore: [],
            rules: {
                firstname: {
                    required: true
                },
                lastname: {
                    required: true
                },
                email: {
                    required: true
                },
                street: {
                    required: true
                },
                city: {
                    required: true
                },
                state: {
                    required: true
                },
                country: {
                    required: true
                },

                contact_number: {
                    required: true
                },
                terms_and_condition: {
                    required: true
                },
                w8_form: {
                    extension: "docx|rtf|doc|pdf|PDF"
                },
                w9_form: {
                    extension: "docx|rtf|doc|pdf|PDF"
                }
            },
            messages: {
                firstname: {
                    required: 'First Name is required!'
                },
                lastname: {
                    required: 'Last Name is required!'
                },
                email: {
                    required: 'Email is required!'
                },
                street: {
                    required: 'Street Address is required!'
                },
                city: {
                    required: 'City is required!'
                },
                state: {
                    required: 'State / Province is required!'
                },
                country: {
                    required: 'Country is required!'
                },

                contact_number: {
                    required: 'Contact number is required!'
                },
                terms_and_condition: {
                    required: 'Please Agree with our terms and policy!'
                },
                w8_form: {
                    extension: "Only .doc, .docx, and .pdf files are allowed."
                },
                w9_form: {
                    extension: "Only .doc, .docx, and .pdf files are allowed."
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

    });


    $('#schedule-free-demo-form-submit').click(function() {
        $("#schedule-free-demo-form").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                phone_number: {
                    required: true,
                },
                company_name: {
                    required: true,
                },
                title: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: 'Please provide user name.',
                },
                email: {
                    required: 'Please provide valid email.',
                },
                phone_number: {
                    required: 'Please provide valid phone number',
                },
                company_name: {
                    required: 'Please provide company name.',
                },
                title: {
                    required: 'Please provide title.',
                }
            },
            submitHandler: function(form) {
                //

                if ($('#g-recaptcha-response').val() == '') {
                    alert('Captcha is required.');
                    return;
                }

                var myurl = "<?= base_url() ?>demo/check_already_applied";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {
                        email: $('#email_id').val()
                    },
                    dataType: "json",
                    success: function(data) {
                        var obj = jQuery.parseJSON(data);
                        if (obj == 0) {
                            form.submit();
                        } else {
                            $("#schedule-free-demo-form-submit").attr("disabled", true);
                            form.submit();
                        }
                    },
                    error: function(data) {
                        alertify.error('Sorry we will fix that issue');
                    }
                });

            }
        });

    });
</script>