<div class="row video-div auto-video-div">
    <div class="col-12 col-md-12 col-lg-5">
        <div class="csVideoBox">
            <?= getSourceByType(
                $pageContent['sections']['section1']['sourceType'],
                $pageContent['sections']['section1']['sourceFile']
            ); ?>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-7 col-xl-7 no-padding-left">
        <div class="video-text-area">
            <div class="row">
                <div class="col-sm-12 set-contentcenter-ontablet">
                    <div class="margin-btm">
                        <p class="heading-h4-grey heading text-white opacity-eighty">
                            <?php echo $pageContent['sections']['section1']['mainheading'] ?>
                        </p>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="margin-btm">
                        <h4 class="second-heading text-center-onmobile white-text text-align-center-ontablet">
                            <?php echo $pageContent['sections']['section1']['heading'] ?>

                        </h4>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="margin-btm">
                        <p class="detail-text text-center-onmobile white-text opacity-eighty text-align-center-ontablet">
                            <?php echo $pageContent['sections']['section1']['headingDetail'] ?>

                        </p>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="margin-btm">

                        <?php
                        $bullet1Array = convertToStrip($pageContent['sections']['section1']['bullet1']);
                        $bullet2Array = convertToStrip($pageContent['sections']['section1']['bullet2']);
                        $bullet3Array = convertToStrip($pageContent['sections']['section1']['bullet3']);
                        $bullet4Array = convertToStrip($pageContent['sections']['section1']['bullet4']);
                        $bullet5Array = convertToStrip($pageContent['sections']['section1']['bullet5']);
                        $bullet6Array = convertToStrip($pageContent['sections']['section1']['bullet6']);
                        ?>

                        <div class="d-flex">
                            <img alt="tick icon" src="<?= image_url('Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet1Array; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= image_url('Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet2Array; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= image_url('Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet3Array; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= image_url('Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet4Array; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= image_url('Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet5Array; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= image_url('Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet6Array; ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row get-started">
                <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 btn-mob-center text-align-center-ontablet">
                    <a href="<?= base_url($pageContent['sections']['section1']['btnSlug']) ?>" class="button explore_btn bg-white mt-4 solution-btn d-inline-flex ms-2 auto-right-top-button jsButtonAnimate">
                        <p class="mb-0 btn-text"><?php echo $pageContent['sections']['section1']['btnText'] ?></p>

                        <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>