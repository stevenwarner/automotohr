<?php

$products = $pageContent['sections']['section2']["products"];
$productBg = [
    "1" => [
        "bg" => "career_section_main py-5 width_100 d-flex justify-content-center career_section_bg_color",
        "image_bg" => "career_section_inner",
        "images" => ["hiring_section_bg"]
    ]
];
?>

<?php
//
$layout = $productBg[1];
// direction
$directionP1 = "order-lg-1 order-2";
$directionP2 = "order-lg-2 order-1";
?>
<div class="<?= $productBg[1]["bg"]; ?> csSection" style="margin-bottom: -55px;margin-top: 50px;">
    <div class="container-fluid">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-6 col-12  <?= $directionP2; ?>">
                <div class=" position-relative ">                   

                   <form method="post" action="javascript:void(0)" class="form" id="<?= $id ?? "jsHighlightsForm"; ?>">
                <div class="form-group">
                    <input type="text" class="form-control" id="wname" placeholder="Name*" name="name" />
                    <?php echo form_error('name'); ?>
                </div>
                <div class="form-group mt-4">
                    <input type="email" class="form-control" id="wemail_id" placeholder="Email*" name="email" />
                    <?php echo form_error('email'); ?>
                </div>

                <div class="form-group mt-4">
                    <div class="g-recaptcha" data-sitekey="<?= getCreds("AHR")->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                    <?php echo form_error('g-recaptcha-response'); ?>
                </div>

                <button class="button p-3 explore_btn schedule-btn schedule-btn-demo d-flex text-white mt-4 width_100 mb-lg-0 mb-5 auto-schedule-btn jsButtonAnimate jsScheduleHighlightsBtn <?= $buttonClass ?? ""; ?>" id="schedule-free-demo-form-submit" type="submit">
                    <p class="mb-0 btn-text">
                        <?= $buttonText ?? "Join Whishlist"; ?>
                    </p>
                    <i class="fa-solid fa-arrow-right schedule-btn-adj ps-3"></i>
                </button>

            </form>

                </div>
            </div>



            <div class="col-lg-6 col-12 ps-md-3 ps-md-3 ps-lg-5 <?= $directionP1; ?>">
                <div class="switch_automoto position-relative ps-0 ps-lg-3 width_90 d-flex flex-column align-items-lg-start align-items-center justify-content-center">
                    <p class="lightgrey heading-h4-grey opacity-ninety heading">
                        <?= $pageContent['sections']['specialhighlights']['mainheading']; ?>
                    </p>
                    <p class="darkgrey mt-3 title text-lg-start text-center">
                        <?= $pageContent['sections']['specialhighlights']['heading']; ?>
                    </p>
                    <p class="lightgrey mt-3 text-lg-start detail text-center opacity-ninety">
                        <?= $pageContent['sections']['specialhighlights']['details']; ?>
                    </p>

                    <?php if ($pageContent['sections']['specialhighlights']['btnText'] != '') { ?>
                        <a href="<?= base_url($pageContent['sections']['specialhighlights']['btnSlug']) ?>" class="button jsButtonAnimate explore_btn mt-5 solution-btn d-flex text-white">
                            <p class="mb-0 btn-text">
                                <?php echo $pageContent['sections']['specialhighlights']['btnText']; ?>
                            </p>
                            <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                        </a>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>

</div>