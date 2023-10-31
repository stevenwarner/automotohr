<section class="padding-top-50">
    <div class="row">
        <div class="col-xs-12 column-flex-center">
            <div class="w-80 image-section-padding-product">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-5 position-relative">
                        <div class="text-center width-height-100 not-show-on-mob">
                            <img class="w-100 man-standing-image" src="<?= image_url("/"); ?>image 42.png" alt="dashboard" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-7 column-center padding-20-products">
                        <?php $this->load->view("v1/app/partials/demo_form_product", [
                            "heading" => $heading,
                            "buttonClass" => "w-100",
                            "buttonClass2" => "w-100",
                            "formId" => "jsScheduleFreeDemoFooter"
                        ]); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>