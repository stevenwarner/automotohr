<main>
    <section class="aboutus-first">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 first-section-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12 col-lg-12">
                            <div class="col-xs-12">
                                <h1 class="automotoH1 text-start why-us-heading text-center w-100">
                                    <?= convertToStrip($pageContent["page"]["sections"]["section_1"]["mainHeading"]); ?>
                                </h1>
                            </div>
                            <div class="col-xs-12 center-horizontally">
                                <div class="aboutus-first-sec-margin-bottom">
                                    <p class="autmotoPara opacity-70 w-100 why-us-first-para padding-x-35 about-us-first-para text-center"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-12">
                            <div class="text-center">
                                <?=
                                getSourceByType(
                                    $pageContent["page"]["sections"]["section_1"]["sourceType"],
                                    $pageContent["page"]["sections"]["section_1"]["sourceFile"],
                                    'class="whyus-center-image"',
                                    false
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="aboutus-second about-us-dark-blue-bg">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 section-padding-whyus aboutus-second-sec-margin-top">
                    <div class="row">
                        <div class="col-xs-12 col-lg-12">
                            <div class="col-xs-12 center-horizontally">
                                <div class="aboutus-first-p-w70">
                                    <p class="autmotoPara w-100 why-us-first-para padding-x-35 about-us-first-para text-center text-white">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section_1"]["subHeading"]); ?>
                                    </p>
                                    <p class="autmotoPara w-100 padding-x-5 opacity-90 text-white text-center">
                                        <?= convertToStrip($pageContent["page"]["sections"]["section_1"]["details"]); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php if ($pageContent["page"]["sections"]["section_2"]["status"] == "on") { ?>
        <section class="whyus-second why-us">
            <div class="row">
                <div class="col-xs-12 column-flex-center">
                    <div class="w-80 section-padding-whyus">
                        <div class="row">
                            <div class="col-xs-12 col-xl-6 flex-start">
                                <div class="image-containing-div position-relative">
                                    <?=
                                    getSourceByType(
                                        $pageContent["page"]["sections"]["section_2"]["sourceType"],
                                        $pageContent["page"]["sections"]["section_2"]["sourceFile"],
                                        'class="full-width-height-img"'
                                    ); ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-6 column-flex-center">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h2 class="automotoH1 text-start why-us-heading award-wining-heading w-100 text-white">
                                            <?= convertToStrip($pageContent["page"]["sections"]["section_2"]["mainHeading"]); ?>
                                        </h2>
                                    </div>
                                    <div class="col-xs-12">
                                        <p class="autmotoPara w-100 padding-x-5 opacity-90 text-white">
                                            <?= convertToStrip($pageContent["page"]["sections"]["section_2"]["details"]); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>
    <section class="whyus-third">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-75 section-two-padding-whyus">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="automotoH1">
                                <?= convertToStrip($pageContent["page"]["sections"]["section_3"]["mainHeading"]); ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>
    </section>


    <?php if ($pageContent["page"]["sections"]["teams"]) { ?>
        <?php foreach ($pageContent["page"]["sections"]["teams"] as $key => $value) {
            $fileIndex = $key % 2 === 0 ? 1 : 2;
            $this->load->view("v1/app/partials/team_list_" . ($fileIndex), [
                'data' => $value,
                "hasTopPadding" => $key > 1
            ]);
        } ?>
    <?php } ?>


</main>