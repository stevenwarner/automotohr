<main>
    <section class="whyus-first">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 order-2-on-mobile">
                            <div class="col-xs-12 ">
                                <p class="Affiliate-title Opacity_80 margin-bottom-25">
                                    <span class="highlighted-light-blue-div">
                                        <?= substr($whyUsContent["page"]["sections"]["section_1"]["mainHeading"], 0, 4); ?>
                                    </span>
                                    <?= convertToStrip(
                                        substr($whyUsContent["page"]["sections"]["section_1"]["mainHeading"], 4)
                                    ); ?>
                                </p>
                            </div>
                            <div class="col-xs-12 ">
                                <h1 class="automotoH1 text-start why-us-heading">
                                    <?= convertToStrip($whyUsContent["page"]["sections"]["section_1"]["subHeading"]); ?>
                                </h1>
                            </div>
                            <div class="col-xs-12 ">
                                <p class="autmotoPara opacity-70 why-us-first-para padding-x-35 why-us-first-para-color">
                                    <?= convertToStrip($whyUsContent["page"]["sections"]["section_1"]["details"]); ?>
                                </p>
                            </div>
                            <div class="col-xs-12 margin-top-30 ">
                                <button class="d-flex load-more justify-content-center align-items-center login-screen-btns  btn-animate margin-top-30 jsButtonAnimate jsScheduleDemoPopup w-80">
                                    <p class="text">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_1"]["buttonText"]); ?>
                                    </p>
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 order-1-on-mobile">
                            <div class="position-relative">
                                <img src="<?= base_url(); ?>assets/v1/app/images/WhyUsBackCover.png" class="whyUsBackCover" alt="why us back cover" />
                                <?=
                                getSourceByType(
                                    $whyUsContent["page"]["sections"]["section_1"]["sourceType"],
                                    $whyUsContent["page"]["sections"]["section_1"]["sourceFile"],
                                    'class="girl-width-book"'
                                ); ?>
                                <img src="<?= base_url(); ?>assets/v1/app/images/girlWithBook.png" class="girl-width-book" alt="girl with book" />
                                <img src="<?= base_url(); ?>assets/v1/app/images/fullPurpleCircle.png" class="whyus-full-purple" alt="purple circle" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="whyus-second why-us">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-two-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 ">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h1 class="automotoH1 text-start why-us-heading w-100 text-white">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_2"]["mainHeading"]); ?>
                                    </h1>
                                </div>
                            </div>
                            <div class="row margin-top-30">
                                <div class="col-3 col-sm-3 text-center ">
                                    <?=
                                    getSourceByType(
                                        $whyUsContent["page"]["sections"]["section_2"]["headingPoint1Type"],
                                        $whyUsContent["page"]["sections"]["section_2"]["headingPoint1File"],
                                        '',
                                        false
                                    ); ?>
                                </div>
                                <div class="col-9 col-sm-9 ">
                                    <p class="text-bold text-white inter-family whyus-sub-heading">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_2"]["headingPoint1"]); ?>
                                    </p>
                                    <p class="text-white inter-family autmotoPara line-height-30 opacity-90">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_2"]["detailsPoint1"]); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <?=
                                    getSourceByType(
                                        $whyUsContent["page"]["sections"]["section_2"]["headingPoint2Type"],
                                        $whyUsContent["page"]["sections"]["section_2"]["headingPoint2File"],
                                        '',
                                        false
                                    ); ?>
                                </div>
                                <div class="col-9">
                                    <p class="text-bold text-white inter-family whyus-sub-heading">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_2"]["headingPoint2"]); ?>
                                    </p>
                                    <p class="text-white inter-family autmotoPara line-height-30 opacity-90">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_2"]["detailsPoint2"]); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <?=
                                    getSourceByType(
                                        $whyUsContent["page"]["sections"]["section_2"]["headingPoint3Type"],
                                        $whyUsContent["page"]["sections"]["section_2"]["headingPoint3File"],
                                        '',
                                        false
                                    ); ?>
                                </div>
                                <div class="col-9">
                                    <p class="text-bold text-white inter-family whyus-sub-heading">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_2"]["headingPoint3"]); ?>
                                    </p>
                                    <p class="text-white inter-family autmotoPara line-height-30 opacity-90">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_2"]["detailsPoint3"]); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 flex-end">
                            <div class="image-containing-div position-relative">
                                <?=
                                getSourceByType(
                                    $whyUsContent["page"]["sections"]["section_2"]["sourceType"],
                                    $whyUsContent["page"]["sections"]["section_2"]["sourceFile"],
                                    'class="full-width-height-img"'
                                ); ?>
                                <img src="<?= base_url(); ?>assets/v1/app/images/lightbluehalfcircle.png" class="whyus-half-blue-circle" alt="blue half circle" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="whyus-third why-us">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-two-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 order-2-product">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="automotoH1 text-start why-us-heading w-100 ">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_3"]["mainHeading"]); ?>
                                    </h2>
                                </div>
                            </div>
                            <div class="row margin-top-30">
                                <div class="col-3 col-sm-3 text-center ">
                                    <div class="whyus-icons">
                                        <?=
                                        getSourceByType(
                                            $whyUsContent["page"]["sections"]["section_3"]["headingPoint1Type"],
                                            $whyUsContent["page"]["sections"]["section_3"]["headingPoint1File"],
                                            '',
                                            false
                                        ); ?>
                                    </div>

                                </div>
                                <div class="col-9 col-sm-9 ">
                                    <p class="text-bold  inter-family whyus-sub-heading">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_3"]["headingPoint1"]); ?>
                                    </p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_3"]["detailsPoint1"]); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <?=
                                    getSourceByType(
                                        $whyUsContent["page"]["sections"]["section_3"]["headingPoint2Type"],
                                        $whyUsContent["page"]["sections"]["section_3"]["headingPoint2File"],
                                        '',
                                        false
                                    ); ?>
                                </div>
                                <div class="col-9">
                                    <p class="text-bold inter-family whyus-sub-heading">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_3"]["headingPoint2"]); ?>
                                    </p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_3"]["detailsPoint2"]); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <?=
                                    getSourceByType(
                                        $whyUsContent["page"]["sections"]["section_3"]["headingPoint3Type"],
                                        $whyUsContent["page"]["sections"]["section_3"]["headingPoint3File"],
                                        '',
                                        false
                                    ); ?>
                                </div>
                                <div class="col-9">
                                    <p class="text-bold  inter-family whyus-sub-heading">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_3"]["headingPoint3"]); ?>
                                    </p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90">
                                        <?= convertToStrip($whyUsContent["page"]["sections"]["section_3"]["detailsPoint3"]); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 flex-start order-1-product">
                            <div class="image-containing-div position-relative">
                                <?=
                                getSourceByType(
                                    $whyUsContent["page"]["sections"]["section_3"]["sourceType"],
                                    $whyUsContent["page"]["sections"]["section_3"]["sourceFile"],
                                    'class="full-width-height-img"'
                                ); ?>
                                <img src="<?= base_url(); ?>assets/v1/app/images/lightbluehalfcircle.png" class="whyus-half-blue-circle-start" alt="blue with half" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="whyus-fourth light-grey-bluish-background why-us">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-two-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 ">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="automotoH1 text-start why-us-heading w-100 "><?php echo $whyUsContent['page']['sections']['section4']['mainHeading'] ?></h2>
                                </div>
                            </div>
                            <div class="row margin-top-30">
                                <div class="col-3 col-sm-3 text-center ">
                                    <img src="<?= base_url(); ?>assets/v1/app/images/Group_h_1.png" alt="speaker icon" />
                                </div>
                                <div class="col-9 col-sm-9 ">
                                    <p class="text-bold inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section4']['heading1'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section4']['heading1Detail'] ?></p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <img src="<?= base_url(); ?>assets/v1/app/images/Group_h_3.png" alt="settings icon" />

                                </div>
                                <div class="col-9">
                                    <p class="text-bold  inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section4']['heading2'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section4']['heading2Detail'] ?></p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <img src="<?= base_url(); ?>assets/v1/app/images/Group_h_2.png" alt="checklist icon" />
                                </div>
                                <div class="col-9">
                                    <p class="text-bold  inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section4']['heading3'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section4']['heading3Detail'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 flex-end">
                            <div class="image-containing-div position-relative">
                                <img src="<?= base_url(); ?>assets/v1/app/images/twoPersonsHandShaking.png" class="full-width-height-img" alt="girl with documents" />
                                <img src="<?= base_url(); ?>assets/v1/app/images/lightbluehalfcircle.png" class="whyus-half-blue-circle" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="whyus-fifth why-us">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-two-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 order-2-product">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="automotoH1 text-start why-us-heading w-100 "><?php echo $whyUsContent['page']['sections']['section5']['mainHeading'] ?></h2>
                                </div>
                            </div>
                            <div class="row margin-top-30">
                                <div class="col-3 col-sm-3 text-center ">
                                    <div class="whyus-icons">
                                        <img src="<?= base_url(); ?>assets/v1/app/images/office_face_icon.png" alt="officer icon" />
                                    </div>

                                </div>
                                <div class="col-9 col-sm-9 ">
                                    <p class="text-bold  inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section5']['heading1'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section5']['heading1Detail'] ?></p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <img src="<?= base_url(); ?>assets/v1/app/images/clockUmbrella.png" alt="attendance tracking icon" />
                                </div>
                                <div class="col-9">
                                    <p class="text-bold inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section5']['heading2'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section5']['heading2Detail'] ?></p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <img src="<?= base_url(); ?>assets/v1/app/images/alienShip.png" alt="workflow  icon" />
                                </div>
                                <div class="col-9">
                                    <p class="text-bold  inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section5']['heading3'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section5']['heading3Detail'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 flex-start order-1-product">
                            <div class="image-containing-div position-relative">
                                <img src="<?= base_url(); ?>assets/v1/app/images/manSmilingWithBook.png" class="full-width-height-img" alt="man sitting with book" />
                                <img src="<?= base_url(); ?>assets/v1/app/images/lightbluehalfcircle.png" class="whyus-half-blue-circle-start" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="whyus-sixth light-grey-bluish-background why-us">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-two-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 ">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="automotoH1 text-start why-us-heading w-100 "><?php echo $whyUsContent['page']['sections']['section6']['mainHeading'] ?></h2>
                                </div>
                            </div>
                            <div class="row margin-top-30">
                                <div class="col-3 col-sm-3 text-center ">
                                    <img src="<?= base_url(); ?>assets/v1/app/images/selfServiceOne.png" alt="database icon" />
                                </div>
                                <div class="col-9 col-sm-9 ">
                                    <p class="text-bold inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section6']['heading1'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section6']['heading1Detail'] ?></p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <img src="<?= base_url(); ?>assets/v1/app/images/selfServiceTwo.png" alt="attendance tracking icon" />
                                </div>
                                <div class="col-9">
                                    <p class="text-bold  inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section6']['heading2'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section6']['heading2Detail'] ?></p>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-lg-6 flex-end">
                            <div class="image-containing-div position-relative">
                                <img src="<?= base_url(); ?>assets/v1/app/images/girlWithPhoneAndTablet.png" class="full-width-height-img" alt="girl with phone and tablet" />
                                <img src="<?= base_url(); ?>assets/v1/app/images/lightbluehalfcircle.png" class="whyus-half-blue-circle" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="whyus-seventh why-us">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-two-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 order-2-product">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="automotoH1 text-start why-us-heading w-100 "><?php echo $whyUsContent['page']['sections']['section7']['mainHeading'] ?></h2>
                                </div>
                            </div>
                            <div class="row margin-top-30">
                                <div class="col-3 col-sm-3 text-center ">
                                    <div class="whyus-icons">
                                        <img src="<?= base_url(); ?>assets/v1/app/images/scaleableOne.png" alt="Businesses of All Sizes" />
                                    </div>

                                </div>
                                <div class="col-9 col-sm-9 ">
                                    <p class="text-bold  inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section7']['heading1'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section7']['heading1Detail'] ?> </p>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-3 text-center ">
                                    <img src="<?= base_url(); ?>assets/v1/app/images/scaleableTwo.png" alt="Industry Relevant icon" />
                                </div>
                                <div class="col-9">
                                    <p class="text-bold inter-family whyus-sub-heading"><?php echo $whyUsContent['page']['sections']['section7']['heading2'] ?></p>
                                    <p class=" inter-family autmotoPara line-height-30 opacity-90"><?php echo $whyUsContent['page']['sections']['section7']['heading2Detail'] ?></p>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-lg-6 flex-start order-1-product">
                            <div class="image-containing-div position-relative">
                                <img src="<?= base_url(); ?>assets/v1/app/images/manAndWomenClapping.png" class="full-width-height-img" alt="man sitting with book" />
                                <img src="<?= base_url(); ?>assets/v1/app/images/lightbluehalfcircle.png" class="whyus-half-blue-circle-start" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="whyus-last">
        <div class="row whyus-bluish-background">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6">
                            <img src="<?= base_url(); ?>assets/v1/app/images/whyUsLastSectionImage.png" class="w-h-100" />
                        </div>
                        <div class="col-xs-12 col-lg-6">
                            <div class="row">
                                <div class="col-xs-12 ">
                                    <h2 class="automotoH1 text-start why-us-heading w-100 text-white"><?php echo $whyUsContent['page']['sections']['section8']['heading'] ?></h2>
                                </div>
                                <div class="col-xs-12 ">
                                    <p class="autmotoPara opacity-70  padding-x-35 text-white"><?php echo $whyUsContent['page']['sections']['section8']['detailHeading'] ?></p>
                                </div>
                                <div class="col-xs-12  ">
                                    <button class="d-flex  justify-content-center align-items-center  whyus-yellow-btn btn-animate jsButtonAnimate jsScheduleDemoPopup">
                                        <p class="text"><?php echo $whyUsContent['page']['sections']['section8']['btnText'] ?></p>
                                        <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>