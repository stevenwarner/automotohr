<section class="whyus-third why-us ">
    <div class="row">
        <div class="col-xs-12 column-flex-center">
            <div class="w-75">
                <div class="row">
                    <div class="col-xs-12 col-xl-6 padding-top-80 order-2-aboutus yellow-border-right position-relative">
                        <img src="<?= image_url("Polygon 7.png") ?>" class="yellow-small-triangle-aboutus third-pointer-nose-aboutus" alt="small forward icon tip" />
                        <div class="aboutus-grey-background">
                            <div class="d-flex align-items-center">
                                <h3>
                                    <?= convertToStrip($data["mainHeading"]); ?>
                                </h3>
                            </div>

                            <div class="autmotoPara aboutus-boxes-para opacity-90 margin-bottom-0">
                                <?= convertToStrip($data["details"]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-xl-6 padding-top-80 order-1-aboutus order-2-aboutus">
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