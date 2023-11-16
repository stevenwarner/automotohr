<div class="section-padding blue-portion-relative blue-background third-last-section-background">
    <div class="row margin-bottom-100 margin-left-twenty">
        <div class="col-sm-12 col-md-12 col-lg-6 div-two">
            <div class="col-sm-12 hide-on-mobile set-contentcenter-ontablet">
                <div class="margin-btm">
                    <p class="heading-h4-grey text-white opacity-ninety margin-top-inovating">
                        <?= convertToStrip($pageContent['sections']['about']['mainHeading']); ?>
                    </p>
                </div>
            </div>
            <div class="col-sm-12 hide-on-mobile">
                <div class="margin-btm">
                    <p class="second-heading white-text text-align-center-ontablet">
                        <?= convertToStrip($pageContent['sections']['about']['subHeading']); ?>
                    </p>
                </div>
            </div>
            <div class="col-sm-12">
                <div>
                    <nav class="nav nav-tabs nav-tabs-modified" id="nav-tab" role="tablist">
                        <a class="nav-link nav-link-modified active text-bold" id="nav-home-tab " data-bs-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Our Mission</a>
                        <a class="nav-link nav-link-modified text-bold" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Our Vision</a>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active mt-3" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="col-sm-12 show-on-mobile">
                                <div class="margin-btm">
                                    <p class="heading-h4-grey text-white">
                                        <?= convertToStrip($pageContent['sections']['about']['mainHeading']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-12 show-on-mobile">
                                <div class="margin-btm">
                                    <p class="second-heading white-text">
                                        <?= convertToStrip($pageContent['sections']['about']['subHeading']); ?>
                                    </p>
                                </div>
                            </div>
                            <p class="detail-text white-text opacity-ninety">
                                <?= convertToStrip($pageContent['sections']['about']['ourMission']); ?>

                            </p>
                        </div>
                        <div class="tab-pane fade mt-3" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="col-sm-12 show-on-mobile">
                                <div class="margin-btm">
                                    <p class="heading-h4-grey text-white">
                                        <?= convertToStrip($pageContent['sections']['about']['mainHeading']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-12 show-on-mobile">
                                <div class="margin-btm">
                                    <p class="second-heading white-text">
                                        <?= convertToStrip($pageContent['sections']['about']['subHeading']); ?>
                                    </p>
                                </div>
                            </div>
                            <p class="detail-text white-text opacity-ninety">
                                <?= convertToStrip($pageContent['sections']['about']['ourVision']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-6 position-relative div-one">
            <div class="text-right text-align-center-ontablet">
                <?= getSourceByType(
                    $pageContent['sections']['about']['sourceType'],
                    $pageContent['sections']['about']['sourceFile'],
                    'class="rotate-img"'
                ); ?>
                <img src="<?= image_url('Ellipse_9.webp'); ?>" class="light-green-half-circle" alt="half circle" />
                <img src="<?= image_url('Ellipse_10.webp'); ?>" class="light-red-circle" alt="half circle" />
            </div>
            <img src="<?= image_url('Ellipse_8.webp'); ?>" class="yellow-half-circle" alt="half circle" />
        </div>
    </div>
    <div class="row notable-benifits-row"></div>
    <div class="row box-row-absolute">
        <div class="col-sm-12">
            <div class="margin-btm">
                <p class="white-text second-heading text-align-center-ontablet">
                    Notable Benefits
                </p>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-4 white-boxes box-shadow margin-right-twenty number-one-span">
            <div class="white-box-padding">
                <div class="row items-align-center-ontablet">
                    <div class="col-3 col-sm-3 col-md-2 col-lg-3">
                        <div class="light-blue-span white-text">
                            <span class="number-span auto-white-box">01</span>
                        </div>
                    </div>
                    <div class="col-9 col-sm-9 col-md-10 col-lg-9">
                        <p class="mid-heading grey-text">
                            <?= convertToStrip($pageContent['sections']['about']['notableBenefitHeading1']); ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="detail-text grey-text opacity-ninety margin-top-five mb-2rem">
                            <?= convertToStrip($pageContent['sections']['about']['notableBenefitDetail1']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-4 white-boxes box-shadow margin-right-twenty number-two-span">
            <div class="white-box-padding">
                <div class="row items-align-center-ontablet">
                    <div class="col-3 col-sm-3 col-md-2 col-lg-3">
                        <div class="light-blue-span white-text">
                            <span class="number-span auto-white-box">02</span>
                        </div>
                    </div>
                    <div class="col-9 col-sm-9 col-md-10 col-lg-9">
                        <p class="mid-heading grey-text">
                            <?= convertToStrip($pageContent['sections']['about']['notableBenefitHeading2']); ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="detail-text grey-text opacity-ninety margin-top-five mb-2rem">
                            <?= convertToStrip($pageContent['sections']['about']['notableBenefitDetail2']); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-4 white-boxes box-shadow number-three-span">
            <div class="white-box-padding">
                <div class="row items-align-center-ontablet">
                    <div class="col-3 col-sm-3 col-md-2 col-lg-3">
                        <div class="light-blue-span white-text">
                            <span class="number-span auto-white-box">03</span>
                        </div>
                    </div>
                    <div class="col-9 col-sm-9 col-md-10 col-lg-9">
                        <p class="mid-heading grey-text">
                            <?= convertToStrip($pageContent['sections']['about']['notableBenefitHeading3']); ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="detail-text grey-text opacity-ninety margin-top-five mb-2rem">
                            <?= convertToStrip($pageContent['sections']['about']['notableBenefitDetail3']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>