<main>
    <!-- Banner -->
    <section class="top-form-section">
        <div class="row product-screen product-screen-top-padding ">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center position-relative">
                <img src="<?= image_url("Ellipse 1.png"); ?>" class="blue-small-full-circle" alt="blue-small-full-circle" />
                <div class="product-screen-text">
                    <h1 class="product-screen-top-heading">
                        <?= convertToStrip($pageContent["banner"]["mainHeading"]); ?>
                    </h1>
                    <p class="product-screen-second-text opacity-80-product">
                        <?= convertToStrip($pageContent["banner"]["details"]); ?>
                    </p>
                </div>
                <img src="<?= image_url("Ellipse 3.png"); ?>" class="grey-full-circle" alt="grey-full-circle" />
                <img src="<?= image_url("Ellipse 4 (1).png"); ?>" class="red-small-full-circle" alt="red-small-full-circle" />
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center form-area-product ">
                <?php $this->load->view("v1/app/partials/demo_form_product", [
                    "heading" => "See AutomotoHR in action",
                    "buttonClass" => "w-100"
                ]); ?>
            </div>
        </div>
    </section>
    <!-- About section -->
    <section class="blue-section dark-blue-background ">
        <div class="row yellow-quarter-background" style="background-image: url('<?= image_url("quarteryellow.png") ?>');">
            <div class="col-xs-12 column-flex-center">
                <div class="text-center div-blue-section-padding-product column-flex-center">
                    <p class="highlighted-div text-white opacity-90-product  margin-top-60">
                        <?= convertToStrip($pageContent["about"]["mainHeading"]); ?>
                    </p>
                    <h2 class="white-heading-product text-white">
                        <?= convertToStrip($pageContent["about"]["subHeading"]); ?>
                    </h2>
                </div>
            </div>
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 blue-image-section-lower-padding">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="text-center">
                                <?=
                                getSourceByType(
                                    $pageContent["about"]["sourceType"],
                                    $pageContent["about"]["sourceFile"],
                                    'class="product-image-div"'
                                ); ?>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center">
                            <div>
                                <h3 class="white-subheading-product text-white">
                                    <?= convertToStrip($pageContent["about"]["heading"]); ?>
                                </h3>
                            </div>
                            <div class="text-white opacity-90-product white-subheading-text-product margin-top-20">
                                <?= convertToStrip($pageContent["about"]["details"]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product section -->
    <section>
        <div class="row">
            <div class="col-md-12 image-section-padding-product">
                <div class="linebackround column-flex-center">
                    <h2><?= convertToStrip($pageContent["product"]["mainHeading"]); ?></h2>
                </div>
            </div>
        </div>

        <?php
        if ($pageContent["products"]) {
            foreach ($pageContent["products"] as $key => $value) {
                $classFirst = "order-2-product";
                $classSecond = "order-1-product";
                //
                if ($value["direction"] === "right_to_left") {
                    $classFirst = $classSecond = "";
                }

        ?>
                <!-- row 1 -->

                <?php if($value['status']!="0"){?>
                <div class="row">
                    <div class="col-xl-12 column-flex-center">
                        <div class="w-80 image-section-padding-product">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 <?= $classFirst; ?>">
                                    <div class="text-center">
                                        <?=
                                        getSourceByType(
                                            $value["sourceType"],
                                            $value["sourceFile"],
                                            'class="product-image-div"'
                                        ); ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center <?= $classSecond; ?>">
                                    <div>
                                        <h3 class="white-subheading-product margin-bottom-30 image-section-subheading">
                                            <?= convertToStrip($value['mainHeading']); ?>
                                        </h3>
                                    </div>
                                    <div class="image-section-text opacity-90-product ">
                                        <p>
                                            <?= (convertToStrip($value['details'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              <?php }?>

        <?php }
        } ?>

    </section>
    <!-- footer section -->
    <?php $this->load->view("v1/app/products/products_footer", [
        "heading" => "See AutomotoHR in action"
    ]); ?>
</main>