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
                        <p class="sora-family autmotoPara automotoGreytext">
                            <span class="highlighted-light-blue-div">Look</span> in Your Demo
                        </p>
                        <h3 class="automotoH3 darkGreyColor line_height ">
                            Contact Our Talent Partners
                        </h3>
                    </div>
                </div>
                <?php $this->load->view("v1/app/partials/demo_form", [
                    "buttonClass"  => "w-100",
                    "buttonText" => "Schedule My Free Demo"
                ]); ?>
            </div>
        </div>
    </div>
</div>