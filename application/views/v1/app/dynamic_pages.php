<main>
    <div class="row">
        <div class="col-xs-12 column-flex-center background-border-radius top-background-div-service" style="background-image: url('<?= getImageURL($pageContent["pageDetails"]["sourceFile"]); ?>');">
            <div class="background-image-div-contact-us  ">
                <h1 class="text-white contact-us-text">
                    <?= convertToStrip($pageContent["pageDetails"]["heading"]); ?>
                </h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="service-main-div">
                <?= convertToStrip($pageContent["pageDetails"]["details"]); ?>
            </div>
        </div>
    </div>
</main>