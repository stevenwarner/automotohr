<?php $popupContent = getPageContent("schedule_demo_popup", false); ?>

<div class="form_section_main width_100 d-flex flex-column align-items-center top-tweleverem-margin" id="freedemo">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 csImageAtBottom">
                <img src="<?= image_url('boy.webp'); ?>" class="form_boy-pic d-lg-flex d-none" alt="man image" />
            </div>
            <div class="col-lg-7">
                <div class="position-relative">
                    <div class="purple_bubble_div d-lg-flex d-none">
                        <div class="purple_bubble"></div>
                    </div>
                    <div class="yellow-bubble-div d-lg-block d-none">
                        <div class="yellow-bubble"></div>
                    </div>
                    <div class="form_section px-5 d-flex flex-column justify-content-center">
                        <div class="d-flex flex-column align-items-center mb-5">
                            <p class="lightgrey heading-h4-grey heading opacity-eighty">
                                <?= convertToStrip($popupContent["page"]["sections"]["pageDetails"]["heading"]); ?>
                            </p>
                            <p class="darkgrey title">
                                <?= convertToStrip($popupContent["page"]["sections"]["pageDetails"]["subHeading"]); ?>
                            </p>
                        </div>
                        <?php $this->load->view("v1/app/partials/demo_form", [
                            "buttonText" => convertToStrip($popupContent["page"]["sections"]["pageDetails"]["buttonText"])
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>