<main>
    <div class="row">
        <div class="col-xs-12 column-flex-center background-border-radius top-background-div-service" style="background-image: url('<?= AWS_S3_BUCKET_URL.$pageContent['page']['sections']['section_0']["sourceFile"] ?>');">
            <div class="background-image-div-contact-us  ">
                <h1 class="text-white contact-us-text">
                    <?= convertToStrip($pageContent['page']['sections']['section_0']["mainHeading"]); ?>
                </h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="service-main-div">
                <?= convertToStrip($pageContent['page']['sections']['section_0']["details"]); ?>
            </div>
        </div>
    </div>
</main>