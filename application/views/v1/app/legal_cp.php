<main>
    <div class="row">
        <div class="col-xs-12 column-flex-center">
            <br>
            <img src="<?= getImageURL($pageContent['page']['sections']['section0']["sourceFile"]); ?>"
                class="img-responsive">
            <br>
            <div class="background-image-div-contact-us  ">
                <h1 class=" contact-us-text">
                    <?= convertToStrip($pageContent['page']['sections']['section0']["title"]); ?>
                </h1>
            </div>
            <?php if ($pageContent['page']['sections']['section0']["details"]): ?>
                <br>
                <div class="background-image-div-contact-us  ">
                    <h1 class=" contact-us-text">
                        <?= convertToStrip($pageContent['page']['sections']['section0']["details"]); ?>
                    </h1>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!--  -->
    <div class="px-5 py-5">
        <?php if ($pageContent["page"]["sections"]["section0"]["tags"]) {
            foreach ($pageContent["page"]["sections"]["section0"]["tags"] as $tag) {
                if ($tag["status"] != '0') {
        ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="csHeading">
                                <?= convertToStrip($tag["title"]); ?>
                            </h3>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($tag["cards"]) {
                            foreach ($tag["cards"] as $key => $card) {
                                if ($card["status"] != '0') {
                        ?>
                                    <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
                                        <div class="csCard">
                                            <h4>
                                                <?= convertToStrip($card["title"]); ?>
                                            </h4>
                                            <p>
                                                <?= convertToStrip($card["details"]); ?>
                                            </p>
                                            <a href="<?= generateLink($card["buttonLink"]) ?>">
                                                <?= convertToStrip($card["buttonText"]); ?>
                                                <i class="fa fa-chevron-right"></i>
                                            </a>
                                        </div>
                                    </div>
                        <?php }
                            }
                        }
                        ?>
                    </div>
        <?php
                }
            }
        }
        ?>
    </div>
</main>