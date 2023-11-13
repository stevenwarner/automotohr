<?php

$products = $homeContent['page']['sections']['section2']["products"];
// $products = [];
$productBg = [
    "1" => [
        "bg" => "career_section_main career_section_bg_color",
        "image_bg" => "career_section_inner",
        "images" => ["hiring_section_bg"],
        "bubbles" => [
            ["hiring_section_bubble_1", "bubble_2"],
            ["hiring_section_bubble_2"],
            ["hiring_section_bubble_3", "bubble_3"],
            ["hiring_section_bubble_4", "bubble_4"],
        ]
    ],
    "2" => [
        "bg" => "payroll_section_main position-relative z-index-one",
        "image_bg" => "",
        "bubbles" => [
            ["payroll_section_bubble_1"],
            ["payroll_section_bubble_2", "bubble_2"],
        ],
        "outer_bubbles" => ["payroll_main_bubble"]
    ],
    "3" => [
        "bg" => "employee_experience_section_main",
        "image_bg" => "",
        "images" => [
            ["employee_experience_img_section position-relative d-lg-flex d-none", "employee_experience_bubble"],
        ],
    ]
];
?>

<div class="width_100 d-flex flex-column align-items-center">
    <div class="width_80">
        <div class="width_100 d-flex flex-column align-items-center justify-content-center py-5">
            <p class="lightgrey heading-h4-grey heading">
                <?php echo $homeContent['page']['sections']['section2']['mainheading'] ?>
            </p>
            <h5 class="darkgrey text-center mt-3 title px-sm-5 px-2 mx-sm-3 mx-0 title">
                <?php echo $homeContent['page']['sections']['section2']['heading'] ?>
            </h5>
        </div>
    </div>
</div>
<!--  -->

<?php if ($products) { ?>
    <?php foreach ($products as $key => $product) {
        //
        $layout = $productBg[$product["layout"]];
        // direction
        $directionP1 = "order-lg-1 order-2";
        $directionP2 = "order-lg-2 order-1";
        //
        if ($product["direction"] == "right_to_left") {
            $directionP1 = "order-lg-2 order-1";
            $directionP2 = "order-lg-1 order-2";
        }

    ?>
        <div class="py-5 width_100 d-flex justify-content-center <?= $productBg[$product["layout"]]["bg"]; ?>">
            <div class="container-fluid">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-lg-6 col-12  <?= $directionP2; ?>">
                        <div class="position-relative d-flex justify-content-center align-items-center <?= $productBg[$product["layout"]]["image_bg"]; ?>">
                            <?php if ($layout["images"]) {
                                foreach ($layout["images"] as $value) {
                                    echo '<div class="' . $value . '"></div>';
                                }
                            } ?>
                            <img src="<?= image_url('hiring_section_img.webp'); ?>" class="width_80 margin-left-onmobile hiring_section_laptop_img section_img" alt="laptop image" />
                            <?php if ($productBg[$product["layout"]]["bubbles"]) {
                                foreach ($productBg[$product["layout"]]["bubbles"] as $bubble) { ?>
                                    <?php if (is_array($bubble)) { ?>
                                        <div class="<?= $bubble[0]; ?> d-lg-flex d-none">
                                            <div class="<?= $bubble[1]; ?>"></div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="<?= $bubble; ?> d-lg-flex d-none"></div>
                                    <?php } ?>
                            <?php }
                            } ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 ps-md-3 ps-md-3 ps-lg-5 <?= $directionP1; ?>">
                        <div class="switch_automoto position-relative ps-0 ps-lg-3 width_90 d-flex flex-column align-items-lg-start align-items-center justify-content-center">
                            <p class="lightgrey heading-h4-grey opacity-ninety heading">
                                <?= convertToStrip($product["mainHeading"]); ?>
                            </p>
                            <p class="darkgrey mt-3 title text-lg-start text-center">
                                <?= convertToStrip($product["subHeading"]); ?>
                            </p>
                            <p class="lightgrey mt-3 text-lg-start detail text-center opacity-ninety">
                                <?= convertToStrip($product["details"]); ?>
                            </p>

                            <a href="<?= main_url($product["buttonLink"]); ?>" class="button jsButtonAnimate explore_btn mt-5 solution-btn d-flex text-white">
                                <p class="mb-0 btn-text">
                                    <?= convertToStrip($product["buttonText"]); ?>
                                </p>
                                <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <?php if ($layout["outer_bubbles"]) {
                foreach ($layout["outer_bubbles"] as $value) {
                    echo '<div class="' . $value . ' d-lg-block d-none"></div>';
                }
            } ?>
        </div>
    <?php } ?>
<?php } ?>


<!-- Slide 3 -->
<div class="employee_experience_section_main position-relative my-lg-0 my-5 width_100 d-flex justify-content-lg-end justify-content-center">
    <div class="employee_experience_section width_90">
        <div class="row d-flex align-items-center justify-content-lg-between justify-content-center">
            <div class="col-xl-5 col-lg-6 col-12 d-flex justify-content-center order-lg-1 order-2">
                <div class="switch_automoto position-relative ps-0 ps-lg-3 width_90 d-flex flex-column align-items-lg-start align-items-center justify-content-center">
                    <p class="lightgrey heading-h4-grey opacity-eighty heading">
                        <?php echo $homeContent['page']['sections']['section4']['mainheading'] ?>
                    </p>
                    <p class="darkgrey mt-3 title text-lg-start text-center">
                        <?php echo $homeContent['page']['sections']['section4']['heading'] ?>
                    </p>
                    <p class="lightgrey mt-3 text-lg-start detail text-center opacity-eighty">
                        <?php echo strip_tags($homeContent['page']['sections']['section4']['headingDetail']) ?>
                    </p>

                    <a href="<?= base_url($homeContent['page']['sections']['section4']['btnSlug']) ?>" class="button jsButtonAnimate explore_btn mt-5 solution-btn d-flex text-white">
                        <p class="mb-0 btn-text"><?php echo $homeContent['page']['sections']['section4']['btnText'] ?></p>
                        <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-12 d-flex justify-lg-content-end justify-content-center order-lg-2 order-1">
                <div class="position-relative">
                    <div class="employee_experience_img_section position-relative d-lg-flex d-none">
                        <div class="employee_experience_bubble"></div>
                    </div>
                    <img src="<?= image_url('employee_experience_img.webp'); ?>" class="employee_img width_100 section_img" alt="mobile and tablet image" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Slide 4 -->
<div class="payroll_section_main py-5 width_100 d-flex justify-content-center position-relative">
    <div class="row d-flex flex-lg-row flex-column align-items-center width_80">
        <div class="col-lg-6 col-12">
            <div class="position-relative d-flex justify-content-center">
                <img src="<?= image_url('monitoring_section_img.webp'); ?>" class="payroll_img section_img" alt="mobile and tablet image" />
                <div class="payroll_section_bubble_1 d-lg-block d-none"></div>
                <div class="payroll_section_bubble_2 d-lg-block d-none">
                    <div class="bubble_2"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-center">
            <div class="switch_automoto position-relative ps-0 ps-lg-3 width_90 d-flex flex-column align-items-lg-start align-items-center justify-content-center">
                <p class="lightgrey heading-h4-grey opacity-ninety heading">
                    <?php echo $homeContent['page']['sections']['section5']['mainheading'] ?>
                </p>
                <p class="darkgrey mt-3 title text-lg-start text-center">
                    <?php echo $homeContent['page']['sections']['section5']['heading'] ?>

                </p>
                <p class="lightgrey mt-3 text-lg-start detail text-center opacity-ninety">
                    <?php echo strip_tags($homeContent['page']['sections']['section5']['headingDetail']) ?>

                </p>

                <a href="<?= base_url($homeContent['page']['sections']['section5']['btnSlug']) ?>" class="button jsButtonAnimate explore_btn mt-5 solution-btn d-flex text-white">
                    <p class="mb-0 btn-text">
                        <?php echo $homeContent['page']['sections']['section5']['btnText'] ?>
                    </p>
                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="payroll_main_bubble d-lg-block d-none"></div>
</div>

<!-- Slide 5 -->
<div class="employee_experience_section_main position-relative my-lg-0 my-5 width_100 d-flex justify-content-lg-end justify-content-center">
    <div class="employee_experience_section width_90">
        <div class="row d-flex align-items-center justify-content-lg-between justify-content-center">
            <div class="col-xl-5 col-lg-6 col-12 d-flex justify-content-center order-lg-1 order-2">
                <div class="switch_automoto position-relative ps-0 ps-lg-3 width_90 d-flex flex-column align-items-lg-start align-items-center justify-content-center">
                    <p class="lightgrey heading-h4-grey opacity-eighty heading">
                        <?php echo $homeContent['page']['sections']['section6']['mainheading'] ?>
                    </p>
                    <p class="darkgrey mt-3 title text-lg-start text-center">
                        <?php echo $homeContent['page']['sections']['section6']['heading'] ?>

                    </p>
                    <p class="lightgrey mt-3 text-lg-start detail text-center opacity-eighty">
                        <?php echo strip_tags($homeContent['page']['sections']['section6']['headingDetail']) ?>
                    </p>

                    <a href="<?= base_url($homeContent['page']['sections']['section6']['btnSlug']) ?>" class="button jsButtonAnimate explore_btn mt-5 solution-btn d-flex text-white">
                        <p class="mb-0 btn-text"><?php echo $homeContent['page']['sections']['section6']['btnText'] ?></p>
                        <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-12 d-flex justify-lg-content-end justify-content-center order-lg-2 order-1">
                <div class="position-relative">
                    <div class="employee_experience_img_section position-relative d-lg-flex d-none">
                        <div class="employee_experience_bubble employee_experience_bubble_bg_color"></div>
                    </div>
                    <img src="<?= image_url('succeed_section_img.webp'); ?>" class="employee_img section_img" alt="mobile and tablet image" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Slide 6 -->
<div class="career_section_main py-5 width_100 d-flex justify-content-center career_section_bg_color">
    <div class="width_80">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-6 col-12 d-flex justify-content-center">
                <div class="position-relative d-flex justify-content-center career_section_inner">
                    <img src="<?= image_url('career_section_img.webp'); ?>" class="width_80 margin-left-onmobile section_img" alt="mobile and tablet image with jobs search screen" />
                    <div class="career_section_bubble_1 d-lg-flex d-none"></div>
                    <div class="career_section_bubble_2 d-lg-flex d-none">
                        <div class="bubble_2"></div>
                    </div>
                    <div class="career_section_bubble_3 d-lg-flex d-none">
                        <div class="bubble_3"></div>
                    </div>
                    <div class="career_section_bubble_4 d-lg-block d-none">
                        <div class="bubble_4"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12 ps-lg-3 ps-lg-5 d-flex justify-content-center">
                <div class="switch_automoto position-relative ps-0 ps-lg-3 width_90 d-flex flex-column align-items-lg-start align-items-center justify-content-center">
                    <p class="lightgrey heading-h4-grey opacity-ninety heading">
                        <?php echo $homeContent['page']['sections']['section7']['mainheading'] ?>
                    </p>
                    <p class="darkgrey mt-3 title text-lg-start text-center">
                        <?php echo $homeContent['page']['sections']['section7']['heading'] ?>

                    </p>
                    <p class="lightgrey mt-3 text-lg-start detail text-center opacity-ninety">
                        <?php echo strip_tags($homeContent['page']['sections']['section7']['headingDetail']) ?>

                    </p>

                    <a href="<?= base_url($homeContent['page']['sections']['section7']['btnSlug']) ?>" class="button jsButtonAnimate explore_btn mt-5 solution-btn d-flex text-white">
                        <p class="mb-0 btn-text"><?php echo $homeContent['page']['sections']['section7']['btnText'] ?></p>
                        <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>