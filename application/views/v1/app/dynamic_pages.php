<main>
    <div class="row">
            <?php if ($this->uri->segment(1) === "market-place-and-integrations"): ?>
            <div class="col-xs-12 column-flex-center">
                <br>
                <br>
                <img src="<?= getImageURL($pageContent["pageDetails"]["sourceFile"]); ?>" class="img-responsive">
                 <div class="background-image-div-contact-us  ">
                    <h1 class="text-white contact-us-text">
                        <?= convertToStrip($pageContent["pageDetails"]["heading"]); ?>
                    </h1>
                </div>
            </div>
        <?php else: ?>
            <div class="col-xs-12 column-flex-center background-border-radius top-background-div-service"
                style="background-image: url('<?= getImageURL($pageContent["pageDetails"]["sourceFile"]); ?>');">
                <div class="background-image-div-contact-us  ">
                    <h1 class="text-white contact-us-text">
                        <?= convertToStrip($pageContent["pageDetails"]["heading"]); ?>
                    </h1>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="service-main-div">
                <?= convertToStrip($pageContent["pageDetails"]["details"]); ?>
            </div>
        </div>
    </div>
</main>