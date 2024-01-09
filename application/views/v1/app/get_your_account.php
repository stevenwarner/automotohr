<main>
    <div class="Get_Your_Account">

        <div class="Background_Image background-image-css center-horizontally" style="background-image: url('<?= AWS_S3_BUCKET_URL . ($pageContent["sections"]["section_0"]["sourceFile"]) ?>')">
            <div class="opacity-on-background column-flex-center">
                <div class="column-flex-center image_height">
                    <h1 class="automotoH1 sora-family center-horizontally text-white line_height font_size_40">
                        <?= convertToStrip($pageContent["sections"]["section_0"]["mainHeading"]); ?>
                    </h1>
                    <div class="autmotoPara sora-family center-horizontally padding_top_30">
                        <button class="center-horizontally font_size_on_MOB button_width_on_MOB Expert-btn  jsScheduleDemoPopup jsButtonAnimate">
                            <p class="text">
                                <?= convertToStrip($pageContent["sections"]["section_0"]["buttonText"]); ?>
                            </p>
                            <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                        </button>
                    </div>
                    <p class="inter-family autmotoPara text-white center-horizontally text-center padding_top_30 text-decoration-underline">
                        Got Questions? Give Us a Call<br />
                        <?= convertToStrip($pageContent["sections"]["section_0"]["phoneNumber"]); ?>
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
                                <?= getSourceByType(
                                    $pageContent["sections"]["section_1"]["sourceType"],
                                    $pageContent["sections"]["section_1"]["sourceFile"],
                                    'class="image_width_XL_screen"',
                                    false
                                ); ?>
                                <div class="blue_Bubble position_top"></div>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12 position-relative pt-5">
                                <p class="sora-family autmotoPara automotoGreytext opacity-80">
                                    <?= convertToStrip($pageContent["sections"]["section_1"]["mainHeading"]); ?>

                                </p>
                                <h3 class="automotoH3 darkGreyColor line_height pt-4">
                                    <?= convertToStrip($pageContent["sections"]["section_1"]["subHeading"]); ?>
                                </h3>
                                <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_top_bottom">
                                    <?= convertToStrip($pageContent["sections"]["section_1"]["details"]); ?>
                                </p>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_1"]["point1"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_1"]["point2"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_1"]["point3"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_1"]["point4"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_1"]["point5"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_1"]["point6"]); ?>
                                    </p>
                                </div>
                                <div class="Ellipse"></div>
                            </div>
                        </div>
                        <div class="Tool-bar d-flex justify-content-center align-items-center">
                            <ul class="d-flex flex-wrap justify-content-center align-items-center mb-0 gap-5 box-shadow bg-white">
                                <li>
                                    <?= getSourceByType(
                                        $pageContent["sections"]["section_3"]["logoType"],
                                        $pageContent["sections"]["section_3"]["logoFile"],
                                        '',
                                        false
                                    ); ?>
                                </li>
                                <li>
                                    <p class="inter-family autmotoPara automotoGreytext line_height_30 mb-0 text-md-start text-center">
                                        <?= convertToStrip($pageContent["sections"]["section_3"]["details"]); ?>
                                    </p>
                                </li>
                                <li>
                                    <?= getSourceByType(
                                        $pageContent["sections"]["section_3"]["sourceType"],
                                        $pageContent["sections"]["section_3"]["sourceFile"],
                                        '',
                                        false
                                    ); ?>
                                </li>
                                <li>
                                    <a href="#">
                                        <button class="font_size_16_MOB jsScheduleDemoPopup jsButtonAnimate">
                                            <?= convertToStrip($pageContent["sections"]["section_3"]["buttonText"]); ?>
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
                                    <?= convertToStrip($pageContent["sections"]["section_2"]["mainHeading"]); ?>
                                </p>
                                <h3 class="automotoH3 darkGreyColor line_height pt-4">
                                    <?= convertToStrip($pageContent["sections"]["section_2"]["subHeading"]); ?>
                                </h3>
                                <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_top_bottom width_70_to_100">
                                    <?= convertToStrip($pageContent["sections"]["section_2"]["details"]); ?>
                                </p>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_2"]["point1"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_2"]["point2"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_2"]["point3"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_2"]["point4"]); ?>
                                    </p>
                                </div>
                                <div class="tick-blue-div-affiliate-screen d-flex">
                                    <div class="tick-blue-icon-div-affiliate">
                                        <span><i class="fa-sharp fa-solid fa-check tick-blue-span-affiliate"></i></span>
                                    </div>
                                    <p class="inter-family autmotoPara automotoGreytext opacity-90 padding_bottom">
                                        <?= convertToStrip($pageContent["sections"]["section_2"]["point5"]); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?= getSourceByType(
                                    $pageContent["sections"]["section_2"]["sourceType"],
                                    $pageContent["sections"]["section_2"]["sourceFile"],
                                    'class="image_width_XL_screen"',
                                    false
                                ); ?>
                            </div>
                        </div>
                        <div class="Tool-bar d-flex justify-content-center align-items-center">
                            <ul class="d-flex flex-wrap justify-content-center align-items-center mb-0 gap-5 box-shadow bg-white">
                                <li>
                                    <?= getSourceByType(
                                        $pageContent["sections"]["section_4"]["logoType"],
                                        $pageContent["sections"]["section_4"]["logoFile"],
                                        '',
                                        false
                                    ); ?>
                                </li>
                                <li>
                                    <p class="inter-family autmotoPara automotoGreytext line_height_30 mb-0 text-md-start text-center">
                                        <?= convertToStrip($pageContent["sections"]["section_4"]["details"]); ?>
                                    </p>
                                </li>
                                <li>
                                    <?= getSourceByType(
                                        $pageContent["sections"]["section_4"]["sourceType"],
                                        $pageContent["sections"]["section_4"]["sourceFile"],
                                        '',
                                        false
                                    ); ?>
                                </li>
                                <li>
                                    <a href="#">
                                        <button class="font_size_16_MOB color_light_grey jsScheduleDemoPopup jsButtonAnimate">
                                            <?= convertToStrip($pageContent["sections"]["section_4"]["buttonText"]); ?>
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
                    <?= getSourceByType(
                        $pageContent["sections"]["section_5"]["logoType"],
                        $pageContent["sections"]["section_5"]["logoFile"],
                        '',
                        false
                    ); ?>
                </div>
                <div class="text-center">
                    <p class="inter-family autmotoPara automotoGreytext opacity-90 mt-4">
                        <?= convertToStrip($pageContent["sections"]["section_5"]["details"]); ?>
                    </p>
                </div>
                <!-- Video div with Iframe -->
                <div class="video-div pt-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="iframe-container">
                                <?= getSourceByType(
                                    $pageContent["sections"]["section_5"]["sourceType"],
                                    $pageContent["sections"]["section_5"]["sourceFile"],
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="padding-top_120">
            <div class="last-part-background-image position-relative w-100">
                <?= getSourceByType(
                    $pageContent["sections"]["section_6"]["sourceType"],
                    $pageContent["sections"]["section_6"]["sourceFile"],
                    'class="get-your-account-image"'
                ); ?>
                <div class="position-absolute top-0 left-0 hire-banner d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="card-box-background">
                            <div class="card-main">
                                <h3 class="sora-family first-heading text-white text-center">
                                    <?= convertToStrip($pageContent["sections"]["section_6"]["mainHeading"]); ?>
                                </h3>
                                <div class="d-flex justify-content-center pt-3">
                                    <button class="btn-yellow font_size_24 jsScheduleDemoPopup jsButtonAnimation">
                                        <?= convertToStrip($pageContent["sections"]["section_6"]["buttonText"]); ?>
                                    </button>
                                </div>
                                <p class="fourth-heading text-white text-center pt-4">
                                    Give Us A Call
                                    <span class="d-block">
                                        <?= convertToStrip($pageContent["sections"]["section_6"]["phoneNumber"]); ?>
                                    </span>
                                </p>
                                <p class="second-heading text-white pt-3">
                                    <?= convertToStrip($pageContent["sections"]["section_6"]["emailAddress"]); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>