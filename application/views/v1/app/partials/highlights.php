<?php

$products = $pageContent['sections']['section2']["products"];
$productBg = [
    "1" => [
        "bg" => "career_section_main py-5 width_100 d-flex justify-content-center ",
        "image_bg" => "career_section_inner",
        "images" => ["hiring_section_bg"]
    ]
];
?>

<?php
//
$layout = $productBg[1];
// direction
$directionP1 = "order-lg-1 order-1";
$directionP2 = "order-lg-2 order-1";
?>
<div class="<?= $productBg[1]["bg"]; ?> csSection">
    <div class="container-fluid">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-6 col-12  <?= $directionP2; ?>">
                <div class=" position-relative ">
                    <?php if ($pageContent['sections']['specialhighlights']['mainheading']): ?>
                        <p class="darkgrey mt-3 title text-lg-start text-center">
                            <?= $pageContent['sections']['specialhighlights']['mainheading']; ?>
                        </p>
                    <?php endif; ?>
                    <form method="post" action="javascript:void(0)" class="form" id="<?= $id ?? "jsHighlightsForm"; ?>">
                        <div class="form-group">
                            <input type="text" class="form-control" id="wname" placeholder="Name*" name="wname" />
                            <?php echo form_error('wname'); ?>
                        </div>
                        <div class="form-group mt-4">
                            <input type="email" class="form-control" id="wemail_id" placeholder="Email*"
                                name="wemail" />
                            <?php echo form_error('wemail'); ?>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="wcname" placeholder="Company name"
                                name="wcompany_name" />
                            <?php echo form_error('wcompany_name'); ?>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="wctel" placeholder="Phone number"
                                name="wphone_number" />
                            <?php echo form_error('wphone_number'); ?>
                        </div>

                        <div class="form-group mt-4">
                            <div class="g-recaptcha" data-sitekey="<?= getCreds("AHR")->GOOGLE_CAPTCHA_API_KEY_V2; ?>">
                            </div>
                            <?php echo form_error('g-recaptcha-response'); ?>
                        </div>

                        <button
                            class="button p-3 explore_btn schedule-btn schedule-btn-demo d-flex text-white mt-4 width_100 mb-lg-0 mb-5 auto-schedule-btn jsButtonAnimate jsScheduleHighlightsBtn "
                            id="schedule-free-demo-form-submit" type="submit">
                            <p class="mb-0 btn-text">
                                Join Wait-list
                            </p>
                            <i class="fa-solid fa-arrow-right schedule-btn-adj ps-3"></i>
                        </button>

                    </form>

                </div>
            </div>



            <div class="col-lg-6 col-12 ps-md-3 ps-md-3 ps-lg-5 <?= $directionP1; ?>">
                <div
                    class="switch_automoto position-relative ps-0 ps-lg-3 width_90 d-flex flex-column align-items-lg-start align-items-center justify-content-center">
                    <?php if ($pageContent['sections']['specialhighlights']['heading']): ?>
                        <p class="darkgrey mt-3 title text-lg-start text-center">
                            <?= $pageContent['sections']['specialhighlights']['heading']; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($pageContent['sections']['specialhighlights']['details']): ?>
                        <p class="lightgrey mt-3 text-lg-start detail text-center opacity-ninety">
                            <?= $pageContent['sections']['specialhighlights']['details']; ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($pageContent['sections']['specialhighlights']['btnText'] != '') { ?>
                        <a href="<?= base_url($pageContent['sections']['specialhighlights']['btnSlug']) ?>"
                            class="button jsButtonAnimate explore_btn mt-5 solution-btn d-flex text-white">
                            <p class="mb-0 btn-text">
                                <?php echo $pageContent['sections']['specialhighlights']['btnText']; ?>
                            </p>
                            <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                        </a>
                    <?php } ?>

                    <img src="<?= base_url("assets/v1/app/images/ai_recruiter.png"); ?>" alt=""
                        class="img-responsive" />
                </div>
            </div>

        </div>
    </div>

</div>