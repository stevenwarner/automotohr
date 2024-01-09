<section class="whyus-fourth">
    <div class="row">
        <div class="col-xs-12 column-flex-center">
            <div class="w-80">
                <div class="row">
                    <div class="col-xs-12 col-xl-6 order-2-aboutus-responsive padding-top-80">
                        <div class="aboutus-grey-background">
                            <div class="d-flex align-items-center">
                                <h3>
                                    <?= convertToStrip($data["mainHeading"]); ?>
                                </h3>
                            </div>

                            <div class="autmotoPara aboutus-boxes-para opacity-90">
                                <?= convertToStrip($data["details"]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-xl-6 yellow-border-right order-1-aboutus-responsive position-relative  padding-top-80">
                        <img src="<?= image_url("Polygon 3.png") ?>" alt="right arrow pointer" class="right-arrow-pointer-about-us" />
                        <div class="center-horizontally">
                            <?=
                            getSourceByType(
                                $data["sourceType"],
                                $data["sourceFile"],
                                '',
                                false
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>