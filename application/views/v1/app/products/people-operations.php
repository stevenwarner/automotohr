<main>
    <section class="top-form-section">
        <div class="row product-screen product-screen-top-padding ">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center position-relative">
                <img src="<?= base_url("public/v1/images/"); ?>Ellipse 1.png" class="blue-small-full-circle" alt="blue-small-full-circle" />
                <div class="product-screen-text">
                    <h1 class="product-screen-top-heading">
                        <?php echo $pageContent['page']['sections']['section1']['heading1']; ?> </h1>
                    <p class="product-screen-second-text opacity-80-product">
                        <?php echo $pageContent['page']['sections']['section1']['heading1Detail']; ?>
                    </p>
                </div>
                <img src="<?= base_url("public/v1/images/"); ?>Ellipse 3.png" class="grey-full-circle" alt="grey-full-circle" />
                <img src="<?= base_url("public/v1/images/"); ?>Ellipse 4 (1).png" class="red-small-full-circle" alt="red-small-full-circle" />
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center form-area-product ">
                <?php $this->load->view("v1/app/partials/demo_form_product", [
                    "heading" => $pageContent['page']['sections']['section1']['heading'],
                ]); ?>
            </div>
        </div>
    </section>
    <section class="blue-section dark-blue-background ">
        <div class="row yellow-quarter-background" style="background-image: url(/assets/v1/app/images/quarteryellow.png);">
            <div class="col-xs-12 column-flex-center">
                <div class="text-center div-blue-section-padding-product column-flex-center">
                    <p class="highlighted-div text-white opacity-90-product  margin-top-60"><span class="highlighted-light-blue-div">Up to 20%</span>Lifetime Commissions On Your Referrals for as long as they remain our client</p>
                    <h2 class="white-heading-product text-white"><?php echo $pageContent['page']['sections']['section2']['heading']; ?>
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
                                    <?php echo $pageContent['page']['sections']['section2']['heading1']; ?>
                                </h3>
                            </div>
                            <div class="text-white opacity-90-product white-subheading-text-product margin-top-20">
                                <?php echo $pageContent['page']['sections']['section2']['heading1Detail']; ?>
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
                    <h2><?=$pageContent["mainHeading"]["text"];?></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class=" col-xl-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 order-2-product">
                            <div class="text-center">
                                <img class="product-image-div" src="<?= base_url("public/v1/images/".($pageContent['page']['sections']['section3']['image'])); ?>" alt="dashboard" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center order-1-product">
                            <div>
                                <h3 class="white-subheading-product margin-bottom-30 image-section-subheading">
                                    <?php echo trim($pageContent['page']['sections']['section3']['heading']); ?>
                                </h3>
                            </div>
                            <div class="image-section-text opacity-90-product ">
                                <p>
                                    <?php echo $pageContent['page']['sections']['section3']['headingDetail']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" col-xl-12 column-flex-center">
            <div class="w-80 image-section-padding-product">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                        <div class="text-center">
                            <img class="product-image-div" src="<?= base_url("public/v1/images/" . ($pageContent['page']['sections']['section4']['image'])); ?>" alt="peoplesitting" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center">
                        <div>
                            <h3 class="white-subheading-product margin-bottom-30  image-section-subheading">
                                <?php echo $pageContent['page']['sections']['section4']['heading']; ?>
                            </h3>
                        </div>
                        <div class="image-section-text opacity-90-product "><?php echo $pageContent['page']['sections']['section4']['headingDetail']; ?> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class=" col-xl-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 order-2-product">
                            <div class="text-center">
                                <img class="product-image-div" src="<?= base_url("public/v1/images/" . ($pageContent['page']['sections']['section5']['image'])); ?>" alt="dashboard" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center order-1-product">
                            <div>
                                <h3 class="white-subheading-product margin-bottom-30  image-section-subheading">
                                    <?php echo $pageContent['page']['sections']['section5']['heading']; ?>
                                </h3>
                            </div>
                            <div class="image-section-text opacity-90-product ">
                                <?php echo $pageContent['page']['sections']['section5']['headingDetail']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-xl-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="text-center">
                                <img class="product-image-div" src="<?= base_url("public/v1/images/" . ($pageContent['page']['sections']['section6']['image'])); ?>" alt="peoplesitting" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center">
                            <div>
                                <h3 class="white-subheading-product margin-bottom-30 image-section-subheading">
                                    <?php echo $pageContent['page']['sections']['section6']['heading']; ?>
                                </h3>
                            </div>
                            <div class="image-section-text opacity-90-product ">
                                <?php echo $pageContent['page']['sections']['section6']['headingDetail']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php $this->load->view("v1/app/products/products_footer", [
        "heading" => $pageContent['page']['sections']['section1']['heading']
    ]); ?>
    <?php $this->load->view('v1/app/partials/loader'); ?>
</main>