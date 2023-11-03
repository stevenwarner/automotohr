<main>
    <div class="Get_Your_Account">

        <div class="Background_Image background-image-css center-horizontally" style="background-image: url('<?= base_url("public/v1/images/AccountBackground.png") ?>')">
            <div class="opacity-on-background column-flex-center">
                <div class="column-flex-center image_height">
                    <h1 class="automotoH1 sora-family center-horizontally text-white line_height font_size_40">
                        <?= convertToStrip($pageContent["page"]["sections"]["banner"]["heading"]); ?>
                    </h1>
                    <div class="autmotoPara sora-family center-horizontally padding_top_30">
                        <button class="center-horizontally font_size_on_MOB button_width_on_MOB Expert-btn  jsScheduleDemoPopup jsButtonAnimate">
                            <p class="text"><?= convertToStrip($pageContent["page"]["sections"]["banner"]["btnText"]); ?></p>
                            <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                        </button>
                    </div>
                    <p class="inter-family autmotoPara text-white center-horizontally text-center padding_top_30 text-decoration-underline">
                        <?= convertToStrip($pageContent["page"]["sections"]["banner"]["calltext"]); ?><br />
                        <?= convertToStrip($pageContent["page"]["sections"]["banner"]["callnumber"]); ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="padding-20-on-medium-small">
            <div class="row padding_top_120">
                <div class="col-xs-12 w-100 column-flex-center">
                    <div class="w-80">
                        <div class="row d-flex padding_bottom_70">
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12 position-relative">
                                <img src="<?= image_url("/"); ?>tabletPic.png" alt="tablet_picture" class="image_width_XL_screen" />
                                <div class="blue_Bubble position_top"></div>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12 position-relative pt-5">
                                <p class="sora-family autmotoPara automotoGreytext opacity-80">
                                    <?= convertToHilited($pageContent["page"]["sections"]["section1"]["mainheading"]); ?>

                                </p>
                                <h3 class="automotoH3 darkGreyColor line_height pt-4">
                                    <?= convertToStrip($pageContent["page"]["sections"]["section1"]["heading"]); ?>
                                </h3>
                                <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_top_bottom">
                                    <?= convertToStrip($pageContent["page"]["sections"]["section1"]["headingDetail"]); ?>
                                </p>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section1"]["bullet1"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section1"]["bullet2"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section1"]["bullet3"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section1"]["bullet4"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section1"]["bullet5"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section1"]["bullet6"]); ?>
                                    </p>
                                </div>
                                <div class="Ellipse"></div>
                            </div>
                        </div>
                        <div class="Tool-bar d-flex justify-content-center align-items-center">
                            <ul class="d-flex flex-wrap justify-content-center align-items-center mb-0 gap-5 box-shadow bg-white">
                                <li>
                                    <img src="<?= image_url("/"); ?>Mask group.png" alt="Logo" />
                                </li>
                                <li>
                                    <p class="inter-family autmotoPara automotoGreytext line_height_30 mb-0 text-md-start text-center">
                                        <?= convertEnterToSpan($pageContent["page"]["sections"]["section1"]["bannertext"]); ?>
                                    </p>
                                </li>
                                <li>
                                    <img src="<?= image_url("/"); ?>layer.png" alt="Boy_with_laptop" />
                                </li>
                                <li>
                                    <a href="<?= base_url("/".$pageContent["page"]["sections"]["section1"]["bannerbtnText"]); ?>">
                                    <button class="font_size_16_MOB jsScheduleDemoPopup jsButtonAnimate">
                                        <?= $pageContent["page"]["sections"]["section1"]["bannerbtnText"]; ?>
                                    </button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Finding Possible Solutions Section -->
            <div class="row padding_top_bottom_120">
                <div class="col-xs-12 w-100 column-flex-center background_Color PTB">
                    <div class="w-80">
                        <div class="row d-flex padding_bottom_70">
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12 pt-5">
                                <p class="sora-family autmotoPara automotoGreytext opacity-80">
                                    <?= convertToHilited($pageContent["page"]["sections"]["section2"]["mainheading"]); ?>
                                </p>
                                <h3 class="automotoH3 darkGreyColor line_height pt-4">
                                    <?= convertToStrip($pageContent["page"]["sections"]["section2"]["heading"]); ?>
                                </h3>
                                <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_top_bottom width_70_to_100">
                                    <?= convertToStrip($pageContent["page"]["sections"]["section2"]["headingDetail"]); ?>
                                </p>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section2"]["bullet1"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section2"]["bullet2"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section2"]["bullet3"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section2"]["bullet4"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section2"]["bullet5"]); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <img src="<?= image_url("/"); ?>mantab.png" alt="man-with-tab" class="image_width_XL_screen" />
                            </div>
                        </div>
                        <div class="Tool-bar d-flex justify-content-center align-items-center">
                            <ul class="d-flex flex-wrap justify-content-center align-items-center mb-0 gap-5 box-shadow bg-white">
                                <li>
                                    <img src="<?= image_url("/"); ?>Colorlogo.png" alt="Logo" />
                                </li>
                                <li>
                                    <p class="inter-family autmotoPara automotoGreytext line_height_30 mb-0 text-md-start text-center">
                                            <?= convertEnterToSpan($pageContent["page"]["sections"]["section2"]["bannertext"]); ?>
                                    </p>
                                </li>
                                <li>
                                    <img src="<?= image_url("/"); ?>Hurry.jpg" alt="Boy_with_table" />
                                </li>
                                <li>
                                <a href="<?= base_url("/".$pageContent["page"]["sections"]["section2"]["bannerbtnText"]); ?>">
                                    <button class="font_size_16_MOB color_light_grey jsScheduleDemoPopup jsButtonAnimation">
                                    <?= $pageContent["page"]["sections"]["section2"]["bannerbtnText"]; ?>
                                    </button>
                                </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Video Image part  -->
            <div class="Get-your-Account-video_div">
                <div class="text-center">
                    <img src="<?= image_url("/"); ?>VideoLogo.png" alt="video-logo" />
                </div>
                <div class="text-center">
                    <p class="inter-family autmotoPara automotoGreytext opacity-90 mt-4">
                        <?= convertToStrip($pageContent["page"]["sections"]["section3"]["youtubeheading"]); ?>
                    </p>
                </div>
                <!-- <div class="video-div pt-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 center-horizontally">
                            <video poster="./assets/images/video-image.png" src="<?= image_url("/"); ?>sample.webm" controls="true" class="video-div-width width_full_on_medium"></video>
                        </div>
                    </div>
                </div> -->
                <!-- Video div with Iframe -->
                <div class="video-div pt-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="iframe-container">
                                <iframe src="https://www.youtube.com/embed/M5AML8r7vP8?si=hRaor3oTvujB4qiJ&amp;start=8" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="padding-top_120">
            <div class="last-part-background-image position-relative w-100">
                <img src="<?= image_url("/"); ?>group.png" alt="group_Picture" class="get-your-account-image" />
                <div class="position-absolute top-0 left-0 hire-banner d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="card-box-background">
                            <div class="card-main">
                                <h3 class="sora-family first-heading text-white text-center">
                                    <?= convertToStrip($pageContent["page"]["sections"]["section3"]["heading"]); ?>
                                </h3>
                                <div class="d-flex justify-content-center pt-3">
                                    <button class="btn-yellow font_size_24  jsScheduleDemoPopup jsButtonAnimation">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section3"]["btnSlug"]); ?>
                                    </button>
                                </div>
                                <p class="fourth-heading text-white text-center pt-4">
                                    <?= convertToStrip($pageContent["page"]["sections"]["section3"]["phoneHeading"]); ?>
                                    <span class="d-block"><?= convertToStrip($pageContent["page"]["sections"]["section3"]["phoneNumber"]); ?></span>
                                </p>
                                <p class="second-heading text-white pt-3">
                                    <?= convertToStrip($pageContent["page"]["sections"]["section3"]["email"]); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>