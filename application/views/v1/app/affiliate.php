<?php $sections = $pageContent["page"]["sections"]; ?>
<main>
    <section class="padding_top">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <p class="Affiliate-title Opacity_80">
                    <span class="highlighted-light-blue-div">
                        <?= substr($sections["section_1"]["mainHeading"], 0, 4); ?>
                    </span> <?= convertToStrip(substr($sections["section_1"]["mainHeading"], 4)); ?>
                </p>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                    <h1 class="Affiliate-heading"><?= convertToStrip($sections["section_1"]['subHeading']); ?></h1>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center no_padding_on_mobile">
                    <p class="Affiliate-content Opacity_90">
                        <?= convertToStrip($sections["section_1"]['details']); ?>
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
                                <?= getSourceByType(
                                    $sections["section_1"]['sourceType'],
                                    $sections["section_1"]['sourceFile'],
                                    'class="image_width_80"',
                                    false
                                ); ?>
                                <div class="pink_bubble"></div>
                                <div class="green_bubble"></div>
                                <div class="red_bubble">
                                    <div class="red_circle"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 position_relative">
                            <h3 class="Affiliate-subtitle">
                                <?= convertToStrip($sections["section_1"]['mainHeadingContent']); ?>
                            </h3>
                            <p class="Affiliate-content-picture Opacity_90">
                                <?= convertToStrip($sections["section_1"]['detailsContent']); ?>
                            </p>
                            <button class="Affiliate-btn jsButtonAnimate" target="jsFormSection">
                                <p class="text">
                                    <?= convertToStrip($sections["section_1"]['buttonText']); ?>
                                </p>
                                <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                            </button>
                            <div class="Blue_bubble"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="blue-form-section section-top-padding" id="jsFormSection">
        <div class="row">
            <div class="col-sm-12 margin-bottom-20">
                <p class="text-center affiliate-screen-top-heading">
                    <?= convertToStrip($sections["section_2"]['mainHeading']); ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 center-horizontally">
                <div class="blue-form w-80">

                    <form action="<?= base_url("affiliate_program/process") ?>" enctype="multipart/form-data" method="post" id="affiliated-form">
                        <div class=" row">
                            <div class="col-sm-12">
                                <p class="highlighted-div text-white opacity-90-product margin-top-60" style="font-size: 36px; line-height: 36px;">
                                    <strong> <span class="highlighted-light-blue-div background">
                                            <?= substr($sections["section_2"]["subHeading"], 0, 4); ?>
                                        </span>
                                        <?= convertToStrip(substr($sections["section_2"]['subHeading'], 4)); ?>
                                    </strong>
                                </p>
                                <h1 class="white-heading-product text-white margin-bottom-40">
                                    <?= convertToStrip($sections["section_2"]['formHeading']); ?>
                                </h1>
                                <?php if ($this->session->flashdata("errors")) { ?>
                                    <div class="alert alert-danger">
                                        <?php $errors = explode(",", $this->session->flashdata("errors"));
                                        foreach ($errors as $value) {
                                        ?>
                                            <p>
                                                <strong>
                                                    <?= $value; ?>
                                                </strong>
                                            </p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if ($this->session->flashdata("success")) { ?>
                                    <div class="alert alert-success">
                                        <p>
                                            <strong>
                                                <?= $this->session->flashdata("success") ?>
                                            </strong>
                                        </p>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                <input class="w-100" placeholder="First Name*" type="text" name="firstname" />
                            </div>
                            <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                <input class="w-100" placeholder="Last Name*" type="text" name="lastname" />
                            </div>
                            <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                <input class="w-100" placeholder="Email*" type="email" name="email" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <input class="w-100" placeholder="Paypal Email" type="email" name="paypal_email" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 margin-bottom-15">
                                <a target="_blank" class="affiliate-anchor-color under-line margin-left-15 extra-text" href="https://www.paypal.com/welcome/signup/#/email_one_password">
                                    Create your FREE PayPal account now.</a>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Company" type="text" name="company" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Street*" type="text" name="street" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="City*" type="text" name="city" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="State / Province*" type="text" name="state" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Zip Code / Postal Code" type="text" name="zip" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <select class="form-select w-100 select-form-field" aria-label="Default select example" name="country" id="country">
                                    <option class="ats_search_filter_inactive" value="0">Please Select Country</option>
                                    <?php foreach ($countries as $country) { ?>
                                        <option class="ats_search_filter_inactive" value="<?php echo $country['country_name'] ?>">
                                            <?php echo $country['country_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <input class="w-100" placeholder="Method Of Promotion" type="text" name="MOP" />
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
                                    <input type="file" class="upload-file-input" name="w9_form" accept=" .doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf" />
                                    <label for="file-input" id="first-upload-btn" class="custom-file-upload">
                                        Upload File
                                    </label>
                                </div>

                                <p class="text-white extra-text margin-left-15 margin-bottom-20">
                                    W9 link:
                                    <a class="under-line" href="https://www.irs.gov/pub/irs-pdf/fw9.pdf" target="_blank">https://www.irs.gov/pub/irs-pdf/fw9.pdf</a>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <p class="text-white extra-text margin-left-15 margin-bottom-10">
                                    Please Upload W8 Form ( For Affiliates Outside of the U.S
                                    )
                                </p>
                                <div class="file-btn-div">
                                    <input type="file" class="upload-file-input" name="w8_form" accept=".doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf" />
                                    <label for="file-input-two" id="first-upload-btn" class="custom-file-upload">
                                        Upload File
                                    </label>
                                </div>
                                <p class="text-white extra-text margin-left-15 margin-bottom-20">
                                    W8 link:
                                    <a class="under-line" href="https://www.irs.gov/pub/irs-pdf/fw8ben.pdf" target="_blank">https://www.irs.gov/pub/irs-pdf/fw8ben.pdf
                                    </a>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <textarea placeholder="Message" class="form-control border-radius-25" name="info" rows="10"></textarea>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <p class="text-white margin-left-15 margin-top-30 extra-text">
                                    Security check
                                </p>
                                <div class="g-recaptcha" data-sitekey="<?= getCreds('AHR')->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 margin-bottom-30">
                                <p class="text-white extra-text">
                                    <?= convertToStrip($sections["section_2"]['terms']); ?>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <input class="form-check-input checkbox" type="checkbox" value="" id="flexCheckDefault" name="terms_and_condition" />
                                <label class="form-check-label text-white checkbox-label">
                                    By checking this box, you agree to our
                                    <a href="#" class="under-line affiliate-anchor-color jsTermsOfServicePopUp">Terms of Service</a>
                                    and
                                    <a href="#" class="under-line affiliate-anchor-color jsPrivacyPolicyPopUp">
                                        Privacy Policy.</a>
                                </label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center margin-top-30">
                                <button class="d-flex justify-content-center align-items-center affiliate-submit jsButtonAnimate jsFrmBtn">
                                    <p class="text">
                                        <?= convertToStrip($sections["section_2"]['buttonText']); ?>
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
                                <?= convertToStrip($sections["section_3"]['mainHeading']); ?>
                            </h3>

                            <h3 class="second-heading">
                                <?= convertToStrip($sections["section_3"]['details']); ?>
                            </h3>

                            <ul class="padding_top_29">
                                <div class="d-flex">
                                    <img src="<?= image_url("tick.png"); ?>" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?= convertToStrip($sections["section_3"]['point1']); ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="<?= image_url("tick.png"); ?>" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?= convertToStrip($sections["section_3"]['point2']); ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="<?= image_url("tick.png"); ?>" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?= convertToStrip($sections["section_3"]['point3']); ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="<?= image_url("tick.png"); ?>" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?= convertToStrip($sections["section_3"]['point4']); ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="<?= image_url("tick.png"); ?>" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?= convertToStrip($sections["section_3"]['point5']); ?>
                                    </li>
                                </div>
                                <div class="d-flex">
                                    <img src="<?= image_url("tick.png"); ?>" alt="tick" class="image_size" />
                                    <li class="affiliate-paragraph">
                                        <?= convertToStrip($sections["section_3"]['point6']); ?>
                                    </li>
                                </div>
                            </ul>
                            <div class="blue_Bubble"></div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 image_end_on position_relative order-1-products d-flex align-items-center">
                            <?= getSourceByType(
                                $sections["section_3"]['sourceType'],
                                $sections["section_3"]['sourceFile'],
                                'class="image_width_on_medium"',
                                false
                            ); ?>
                            <div class="Ellipse"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="blue-image-with-text-on-right dark-blue-background position-relative mobile-section-padding">
        <img src="<?= image_url("/"); ?>fullsmallpinkcircle.png" class="fullsmallpinkcircle" alt="full-small-pink-circle" />
        <div class="row">
            <div class="col-xs-12 column-flex-center affiliate-blue-section-padding">
                <div class="w-80">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 position-relative">
                            <img src="<?= image_url("halfredcircle.png"); ?>" class="half-red-circle" alt="half-red-circle" />
                            <?= getSourceByType(
                                $sections["section_4"]['sourceType'],
                                $sections["section_4"]['sourceFile'],
                                'class="product-image-div"',
                                false
                            ); ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 position-relative">
                            <img src="<?= image_url("fullsmallyellow.png"); ?>" class="fullsmallyellow" alt="full-small-yellow" />
                            <h2 class="text-white first-heading text-left margin-bottom-20">
                                <?= convertToStrip($sections["section_4"]['mainHeading']); ?>
                            </h2>

                            <h3 class="text-white second-heading margin-bottom-10">
                                <?= convertToStrip($sections["section_4"]['details']); ?>
                            </h3>
                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?= convertToStrip($sections["section_4"]['point1']); ?>
                                </p>
                            </div>
                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?= convertToStrip($sections["section_4"]['point2']); ?>
                                </p>
                            </div>
                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?= convertToStrip($sections["section_4"]['point3']); ?>
                                </p>
                            </div>

                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?= convertToStrip($sections["section_4"]['point4']); ?>
                                </p>
                            </div>

                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?= convertToStrip($sections["section_4"]['point5']); ?>
                                </p>
                            </div>

                            <div class="tick-div-affiliate-screen d-flex justify-center">
                                <div class="tick-icon-div-affiliate">
                                    <span><i class="fa-sharp fa-solid fa-check tick-span-affiliate"></i></span>
                                </div>
                                <p class="text-white">
                                    <?= convertToStrip($sections["section_4"]['point6']); ?>
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
                                <?= convertToStrip($sections["section_5"]['mainHeading']); ?>
                            </h3>
                            <div class="row margin_top position_relative">
                                <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <h3 class="second-heading">
                                        <?= convertToStrip($sections["section_5"]['heading1']); ?>
                                    </h3>
                                    <p class="affiliate-paragraph">
                                        <?= convertToStrip($sections["section_5"]['details1']); ?>
                                    </p>
                                </div>
                                <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <h3 class="second-heading">
                                        <?= convertToStrip($sections["section_5"]['heading2']); ?>
                                    </h3>
                                    <p class="affiliate-paragraph">
                                        <?= convertToStrip($sections["section_5"]['details2']); ?>
                                    </p>
                                </div>
                                <div class="light_blue_Bubble"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 d-flex align-items-center position_relative image_center_on_tab order-1-products">
                            <?= getSourceByType(
                                $sections["section_5"]['sourceType'],
                                $sections["section_5"]['sourceFile'],
                                'class="image_width_100"',
                                false
                            ); ?>
                            <div class="light_pink_bubble"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="fifth-section-audience light-grey-background-affiliate position-relative mobile-section-padding">
        <img src="<?= image_url("fullsmallpinkcircle.png"); ?>" class="small-pink-fith-section" alt="small-pink-circle" />
        <div class="row">
            <div class="col-xs-12 column-flex-center affiliate-blue-section-padding">
                <div class="w-80">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 position-relative">
                            <img src="<?= image_url("smallfulllightblue.png"); ?>" class="small-lightblue-fith-section" alt="small-light-blue-circle" />
                            <?= getSourceByType(
                                $sections["section_6"]['sourceType'],
                                $sections["section_6"]['sourceFile'],
                                'class="product-image-div"',
                                false
                            ); ?>
                            <img src="<?= image_url("smallfullyellow.png"); ?>" class="small-fullyellow-fith-section" alt="small-yellow-circle" />
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 position-relative">
                            <img src="<?= image_url("/"); ?>smallfullred.png" class="small-fullred-fith-section" alt="small-red-circle" />
                            <h2 class="first-heading dark-grey-affiliate text-left">
                                <?= convertToStrip($sections["section_6"]['mainHeading']); ?>
                            </h2>
                            <p class="affiliate-paragraph opacity-90-product">
                                <?= convertToStrip($sections["section_6"]['details']); ?>
                            </p>
                            <div class="row">
                                <div class="col-xs-12">

                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?= convertToStrip($sections["section_6"]['point1']); ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?= convertToStrip($sections["section_6"]['point2']); ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?= convertToStrip($sections["section_6"]['point3']); ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?= convertToStrip($sections["section_6"]['point4']); ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?= convertToStrip($sections["section_6"]['point5']); ?>
                                        </p>
                                    </div>
                                    <div class="tick-blue-div-affiliate-screen d-flex justify-center">
                                        <div class="tick-blue-icon-div-affiliate">
                                            <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                        </div>
                                        <p class="opacity-90-product affiliate-paragraph">
                                            <?= convertToStrip($sections["section_6"]['point6']); ?>
                                        </p>
                                    </div>
                                    <p class="opacity-90-product affiliate-paragraph margin-top-10">
                                        <?= convertToStrip($sections["section_6"]['detailsBottom']); ?>
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
                                <?= convertToStrip($sections["section_7"]['mainHeading']); ?>
                            </h1>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center padding-20-products">
                            <h3 class="affiliate-sub-heading margin-bottom-10">
                                <span class="big-number-affiliate">1</span>
                                <?= convertToStrip($sections["section_7"]['heading1']); ?>
                            </h3>
                            <p class="opacity-90-product affiliate-paragraph margin-bottom-20 margin-left-20">
                                <?= convertToStrip($sections["section_7"]['details1']); ?>
                            </p>
                            <h3 class="affiliate-sub-heading margin-bottom-10">
                                <span class="big-number-affiliate">2</span>
                                <?= convertToStrip($sections["section_7"]['heading2']); ?>
                            </h3>
                            <p class="opacity-90-product affiliate-paragraph margin-left-20">
                                <?= convertToStrip($sections["section_7"]['details2']); ?>
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-5">
                            <div class="text-center width-height-100 d-flex align-items-center">
                                <?= getSourceByType(
                                    $sections["section_7"]['sourceType'],
                                    $sections["section_7"]['sourceFile'],
                                    'class="w-100"',
                                    false
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100 flex">
            <div class="w-80 column-flex-center padding_top_60">
                <h3 class="first-heading text-center">
                    <?= convertToStrip($sections["section_8"]['mainHeading']); ?>
                </h3>
                <p class="affiliate-paragraph text-center margin-top-10 opacity-90-product">
                    <?= convertToStrip($sections["section_8"]['details']); ?>
                </p>
                <div class="row margin_top padding_square_side">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 box_center_on_mobile">
                        <div class="common_card_box">
                            <div class="padding_top_left">
                                <p class="box_heading"><?= convertToStrip($sections["section_8"]['heading1']); ?></p>
                                <p class="affiliate-paragraph opacity-90-product">
                                    <?= convertToStrip($sections["section_8"]['details1']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 box_center_on_mobile">
                        <div class="common_card_box">
                            <div class="padding_top_left">
                                <p class="box_heading"><?= convertToStrip($sections["section_8"]['heading2']); ?></p>
                                <p class="affiliate-paragraph opacity-90-product">
                                    <?= convertToStrip($sections["section_8"]['details2']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 box_center_on_mobile">
                        <div class="common_card_box">
                            <div class="padding_top_left">
                                <p class="box_heading"><?= convertToStrip($sections["section_8"]['heading3']); ?></p>
                                <p class="affiliate-paragraph opacity-90-product">
                                    <?= convertToStrip($sections["section_8"]['details3']); ?>
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
                                <img class="w-100 man-standing-image" src="<?= image_url("image 42.png"); ?>" alt="dashboard" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-7 column-center padding-20-products">
                            <?php $this->load->view("v1/app/partials/demo_form_product", [
                                "heading" => "Look In Your Demo",
                                "buttonClass" => "w-100",
                                "buttonClass2" => "w-100",
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>