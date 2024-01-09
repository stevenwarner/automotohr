<?php $popupContent = getPageContent("schedule_demo_popup", false); ?>
<style>
    .modal-content {
        border-radius: 40px;
        background-color: #F1F6F9;
    }
</style>
<div class="modal fade" id="jsScheduleDemoModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form_section px-3 d-flex flex-column justify-content-center">
                    <div class="d-flex flex-column align-items-center mb-5">
                        <br />
                        <p>
                            <span class="highlighted-light-blue-div"><?= convertToStrip(substr($popupContent["page"]["sections"]["pageDetails"]["heading"], 0, 4)); ?></span>
                            <?= convertToStrip(substr($popupContent["page"]["sections"]["pageDetails"]["heading"], 4)); ?>
                        </p>
                        <h3 class="automotoH3 darkGreyColor line_height ">
                            <?= convertToStrip($popupContent["page"]["sections"]["pageDetails"]["subHeading"]); ?>
                        </h3>
                    </div>
                </div>
                <?php $this->load->view("v1/app/partials/demo_form", [
                    "id" => "jsScheduleFreeDemoPopUp",
                    "buttonClass"  => "w-100",
                    "buttonText" => convertToStrip($popupContent["page"]["sections"]["pageDetails"]["buttonText"])
                ]); ?>
            </div>
        </div>
    </div>
</div>