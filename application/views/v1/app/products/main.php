<main>
    <section class="top-form-section">
        <div class="row product-screen product-screen-top-padding ">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center position-relative">
                <img src="<?= base_url("public/v1/images/"); ?>Ellipse 1.png" class="blue-small-full-circle" alt="blue-small-full-circle" />
                <div class="product-screen-text">
                    <h1 class="product-screen-top-heading">
                        <?php echo convertToStrip($pageContent['page']['sections']['section1']['heading1']); ?> </h1>
                    <p class="product-screen-second-text opacity-80-product">
                        <?php echo convertToStrip($pageContent['page']['sections']['section1']['heading1Detail']); ?>
                    </p>
                </div>
                <img src="<?= base_url("public/v1/images/"); ?>Ellipse 3.png" class="grey-full-circle" alt="grey-full-circle" />
                <img src="<?= base_url("public/v1/images/"); ?>Ellipse 4 (1).png" class="red-small-full-circle" alt="red-small-full-circle" />
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center form-area-product ">
                <?php $this->load->view("v1/app/partials/demo_form_product", [
                    "heading" => convertToStrip($pageContent['page']['sections']['section1']['heading']),
                ]); ?>
            </div>
        </div>
    </section>
    <section class="blue-section dark-blue-background ">
        <div class="row yellow-quarter-background" style="background-image: url('<?= base_url("public/v1/images/") ?>quarteryellow.png');">
            <div class="col-xs-12 column-flex-center">
                <div class="text-center div-blue-section-padding-product column-flex-center">
                    <p class="highlighted-div text-white opacity-90-product  margin-top-60"><span class="highlighted-light-blue-div">Up to 20%</span>Lifetime Commissions On Your Referrals for as long as they remain our client</p>
                    <h2 class="white-heading-product text-white"><?php echo convertToStrip($pageContent['page']['sections']['section2']['heading']); ?>
                    </h2>
                </div>
            </div>
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 blue-image-section-lower-padding">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="text-center">
                                <img class="product-image-div" src="<?= base_url("public/v1/images/"); ?>peoplesitting.png" alt="peoplesitting" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center">
                            <div>
                                <h3 class="white-subheading-product text-white">
                                    <?php echo convertToStrip($pageContent['page']['sections']['section2']['heading1']); ?>
                                </h3>
                            </div>
                            <div class="text-white opacity-90-product white-subheading-text-product margin-top-20">
                                <?php echo convertToStrip($pageContent['page']['sections']['section2']['heading1Detail']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-md-12 image-section-padding-product">
                <div class="linebackround column-flex-center">
                    <h2><?= convertToStrip($pageContent["page"]["mainHeading"]["text"]); ?></h2>
                </div>
            </div>
        </div>

        <?php
        $sectionsArray = array_values(
            $pageContent["page"]["sections"]
        );
        foreach ($sectionsArray as $key => $value) {
            $classFirst = "order-2-product";
            $classSecond = "order-1-product";
            if ($key <= 2 || !$value['headingDetail']) {
                continue;
            }
            //
            if ($key % 2 === 0) {
                $classFirst = $classSecond = "";
            }
        ?>
            <!-- row 1 -->
            <div class="row">
                <div class="col-xl-12 column-flex-center">
                    <div class="w-80 image-section-padding-product">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 <?= $classFirst; ?>">
                                <div class="text-center">
                                    <img class="product-image-div" src="<?= base_url("public/v1/images/" . $value['image']); ?>" alt="dashboard" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center <?= $classSecond; ?>">
                                <div>
                                    <h3 class="white-subheading-product margin-bottom-30 image-section-subheading">
                                        <?= convertToStrip($value['heading']); ?>
                                    </h3>
                                </div>
                                <div class="image-section-text opacity-90-product ">
                                    <p>
                                        <?= ($value['headingDetail']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </section>
    <?php $this->load->view("v1/app/products/products_footer", [
        "heading" => convertToStrip($pageContent['page']['sections']['section1']['heading'])
    ]); ?>
</main>