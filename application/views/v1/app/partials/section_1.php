<div class="row video-div auto-video-div">
    <div class="col-12 col-md-12 col-lg-5 no-padding-right">
        <div class="abosulte-div">
            <iframe src="//www.youtube.com/embed/g4BsAB3PliY" class="embed-responsive-item responsive-video" style="border: 0" title="AutomotoHR Video"></iframe>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-7 col-xl-7 no-padding-left">
        <div class="video-text-area">
            <div class="row">
                <div class="col-sm-12 set-contentcenter-ontablet">
                    <div class="margin-btm">
                        <p class="heading-h4-grey heading text-white opacity-eighty">
                            WHAT WE OFFER?
                        </p>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="margin-btm">
                        <h4 class="second-heading text-center-onmobile white-text text-align-center-ontablet">
                            <?php echo $homeContent['page']['sections']['section1']['heading'] ?>

                        </h4>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="margin-btm">
                        <p class="detail-text text-center-onmobile white-text opacity-eighty text-align-center-ontablet">
                            <?php echo $homeContent['page']['sections']['section1']['headingDetail'] ?>

                        </p>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="margin-btm">

                        <?php
                        $bullet1Array = explode('#', $homeContent['page']['sections']['section1']['bullet1']);
                        $bullet2Array = explode('#', $homeContent['page']['sections']['section1']['bullet2']);
                        $bullet3Array = explode('#', $homeContent['page']['sections']['section1']['bullet3']);
                        $bullet4Array = explode('#', $homeContent['page']['sections']['section1']['bullet4']);
                        $bullet5Array = explode('#', $homeContent['page']['sections']['section1']['bullet5']);
                        $bullet6Array = explode('#', $homeContent['page']['sections']['section1']['bullet6']);
                        ?>

                        <div class="d-flex">
                            <img alt="tick icon" src="<?= base_url('assets/v1/app/images/Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet1Array[0]; ?>
                                <?php if ($bullet1Array[1]) { ?> <span class="yellow-text"><?php echo $bullet1Array[1]; ?></span> <?php } ?>
                                <?php echo $bullet1Array[2]; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= base_url('assets/v1/app/images/Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet2Array[0]; ?>
                                <?php if ($bullet2Array[1]) { ?> <span class="yellow-text"><?php echo $bullet2Array[1]; ?></span> <?php } ?>
                                <?php echo $bullet2Array[2]; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= base_url('assets/v1/app/images/Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet3Array[0]; ?>
                                <?php if ($bullet3Array[1]) { ?> <span class="yellow-text"><?php echo $bullet3Array[1]; ?></span> <?php } ?>
                                <?php echo $bullet3Array[2]; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= base_url('assets/v1/app/images/Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet4Array[0]; ?>
                                <?php if ($bullet4Array[1]) { ?> <span class="yellow-text"><?php echo $bullet4Array[1]; ?></span> <?php } ?>
                                <?php echo $bullet4Array[2]; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= base_url('assets/v1/app/images/Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet5Array[0]; ?>
                                <?php if ($bullet5Array[1]) { ?> <span class="yellow-text"><?php echo $bullet5Array[1]; ?></span> <?php } ?>
                                <?php echo $bullet5Array[2]; ?>
                            </p>
                        </div>
                        <div class="d-flex">
                            <img alt="tick icon" src="<?= base_url('assets/v1/app/images/Group.png'); ?>" class="me-3 yellow-icon-image" />
                            <p class="detail-text white-text opacity-eighty">
                                <?php echo $bullet6Array[0]; ?>
                                <?php if ($bullet6Array[1]) { ?> <span class="yellow-text"><?php echo $bullet6Array[1]; ?></span> <?php } ?>
                                <?php echo $bullet6Array[2]; ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row get-started">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 btn-mob-center text-align-center-ontablet">
                    <button class="button explore_btn bg-white mt-4 solution-btn d-inline-flex ms-2 auto-right-top-button">
                        <p class="mb-0 btn-text"> <a href="<?= base_url($homeContent['page']['sections']['section1']['btnSlug']) ?>"> <?php echo $homeContent['page']['sections']['section1']['btnText'] ?></a></p>

                        <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>