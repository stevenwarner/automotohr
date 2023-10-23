<div class="form_section_main width_100 d-flex flex-column align-items-center top-tweleverem-margin" id="freedemo">
    <div class="width_80 d-flex position-relative">
        <img src="<?= base_url('assets/v1/app/images/boy.webp'); ?>" class="form_boy-pic d-lg-flex d-none" alt="man image" />
        <div class="purple_bubble_div d-lg-flex d-none">
            <div class="purple_bubble"></div>
        </div>
        <div class="form_section px-5 d-flex flex-column justify-content-center">
            <div class="d-flex flex-column align-items-center mb-5">
                <p class="lightgrey heading-h4-grey heading opacity-eighty">
                    <?php echo $homeContent['page']['sections']['section16']['heading'] ?>

                </p>
                <p class="darkgrey title"><?php echo $homeContent['page']['sections']['section16']['heading2'] ?></p>
                <?php $this->load->view('v1/app/partials/admin_flash_message'); ?>

            </div>
            <?php $this->load->view("v1/app/partials/demo_form"); ?>
        </div>
        <div class="yellow-bubble-div d-lg-block d-none">
            <div class="yellow-bubble"></div>
        </div>
    </div>
    <div class="width_100 auto-schedule-btn-height"></div>
</div>