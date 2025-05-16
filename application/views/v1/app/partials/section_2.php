<?php

$products = $pageContent['sections']['section2']["products"];
$productBg = [
    "1" => [
        "bg" => "career_section_main py-5 width_100 d-flex justify-content-center career_section_bg_color",
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
        "bg" => "payroll_section_main py-5 width_100 d-flex justify-content-center position-relative z-index-one",
        "image_bg" => "",
        "bubbles" => [
            ["payroll_section_bubble_1"],
            ["payroll_section_bubble_2", "bubble_2"],
        ],
        "outer_bubbles" => ["payroll_main_bubble"]
    ],
    "3" => [
        "bg" => "employee_experience_section_main position-relative my-lg-0 my-5 width_100 d-flex justify-content-lg-end justify-content-center",
        "image_bg" => "justify-lg-content-end justify-content-center",
        "image_container" => [
            "employee_experience_img_section position-relative d-lg-flex d-none",
            "employee_experience_bubble",
        ],
        "props" => 'class="employee_img section_img width_100"'
    ],
    "4" => [
        "bg" => "payroll_section_main py-5 width_100 d-flex justify-content-center position-relative",
        "image_bg" => "switch_automoto",
        "bubbles" => [
            ["payroll_section_bubble_1"],
            ["payroll_section_bubble_2", "bubble_2"]
        ],
        "outer_bubbles" => [
            "payroll_main_bubble",
        ],
    ],
    "5" => [
        "bg" => "employee_experience_section_main position-relative my-lg-0 my-5 width_100 d-flex justify-content-lg-end justify-content-center",
        "image_bg" => "",
        "image_container" => [
            "employee_experience_img_section position-relative d-lg-flex d-none",
            "employee_experience_bubble employee_experience_bubble_bg_color"
        ],
        "bubbles" => [],
        "outer_bubbles" => [],
        "props" => 'class="employee_img section_img width_100"'
    ],
    "6" => [
        "bg" => "career_section_main py-5 width_100 d-flex justify-content-center career_section_bg_color",
        "image_bg" => "career_section_inner",
        "bubbles" => [
            ["career_section_bubble_1"],
            ["career_section_bubble_2", "bubble_2"],
            ["career_section_bubble_3", "bubble_3"],
            ["career_section_bubble_4", "bubble_4"],
        ],
        "outer_bubbles" => [],
        "props" => 'class="width_80 margin-left-onmobile section_img"'
    ]
];
?>

<div class="width_100 d-flex flex-column align-items-center">
    <div class="width_80">
        <div class="width_100 d-flex flex-column align-items-center justify-content-center py-5">
            <p class="lightgrey heading-h4-grey heading">
                <?php echo $pageContent['sections']['section2']['mainheading'] ?>
            </p>
            <h5 class="darkgrey text-center mt-3 title px-sm-5 px-2 mx-sm-3 mx-0 title">
                <?php echo $pageContent['sections']['section2']['heading'] ?>
            </h5>
        </div>
    </div>
</div>
<!--  -->

<?php if ($products) { ?>
    <?php foreach ($products as $key => $product) {

        if (!$product["layout"]) {
            continue;
        }


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

        <?php if ($product['status'] != '0') { ?>
            <div class="<?= $productBg[$product["layout"]]["bg"]; ?> csSection">
                <div class="container-fluid">
                    <div class="row d-flex align-items-center justify-content-center">
                        <div class="col-lg-6 col-12  <?= $directionP2; ?>">
                            <div class=" position-relative <?= $productBg[$product["layout"]]["image_bg"]; ?>">
                                <?php if ($layout["images"]) {
                                    foreach ($layout["images"] as $value) {
                                        echo '<div class="' . $value . '"></div>';
                                    }
                                } ?>
                                <?php if ($layout["image_container"]) { ?>
                                    <div class="position-relative">
                                        <div class="<?= $layout["image_container"][0]; ?>">
                                            <div class="<?= $layout["image_container"][1] ?>"></div>
                                        </div>
                                    <?php } ?>

                                    <?= getSourceByType(
                                        $product["sourceType"],
                                        $product["sourceFile"],
                                        $layout['props'] ?? ''
                                    ); ?>
                                    <?php if ($layout["image_container"]) { ?>

                                    </div>
                                <?php } ?>
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
                            <div
                                class="switch_automoto position-relative ps-0 ps-lg-3 width_90 d-flex flex-column align-items-lg-start align-items-center justify-content-center">
                                <p class="lightgrey heading-h4-grey opacity-ninety heading">
                                    <?= convertToStrip($product["mainHeading"]); ?>
                                </p>
                                <p class="darkgrey mt-3 title text-lg-start text-center">
                                    <?= convertToStrip($product["subHeading"]); ?>
                                </p>
                                <p class="lightgrey mt-3 text-lg-start detail text-center opacity-ninety">
                                    <?= convertToStrip($product["details"]); ?>
                                </p>

                                <a href="<?= main_url($product["buttonLink"]); ?>"
                                    class="button jsButtonAnimate explore_btn mt-5 solution-btn d-flex text-white">
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
<?php } ?>