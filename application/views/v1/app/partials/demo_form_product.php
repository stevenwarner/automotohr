<?php $popupContent = getPageContent("schedule_demo_popup", false); ?>
<form method="post" action="javascript:void(0)" class="form <?= $buttonClass2 ?? "w-80" ?>" id="<?= $formId ?? "jsScheduleFreeDemoMain"; ?>">
    <div class="position-relative">
        <img src="<?= image_url("/"); ?>Ellipse 9.png" class="purple-half-div" alt="half-purple-circle" />
        <div class="form-div">
            <div class="highlighted-div column-flex-center opacity-80-product">
                <p>
                    <span class="highlighted-light-blue-div"><?= convertToStrip(substr($popupContent["page"]["sections"]["pageDetails"]["heading"], 0, 4)); ?></span>
                    <?= convertToStrip(substr($popupContent["page"]["sections"]["pageDetails"]["heading"], 4)); ?>
                </p>
            </div>
            <h2>
                <?= convertToStrip($popupContent["page"]["sections"]["pageDetails"]["subHeading"]); ?>
            </h2>
            <div class="form-group">
                <input type="text" class="form-control" id="name" placeholder="Name*" name="name" />
                <?php echo form_error('name'); ?>
            </div>
            <div class="form-group mt-4">
                <input type="email" class="form-control" id="email_id" placeholder="Email*" name="email" />
                <?php echo form_error('email'); ?>
            </div>
            <div class="form-group mt-4">
                <input type="text" class="form-control" id="phone_number" placeholder="Phone Number*" name="phone_number" />
                <?php echo form_error('phone_number'); ?>
            </div>
            <div class="form-group mt-4">
                <select class="form-control jsCountrySelect" placeholder="Country*" name="country">
                    <option value="">Please select a country</option>
                    <option value="United States">United States</option>
                    <option value="Canada">Canada</option>
                </select>
                <?php echo form_error('Country'); ?>
            </div>
            <div class="form-group mt-4">
                <select class="form-control" placeholder="State*" name="state">
                    <option value="">Please select a state</option>
                </select>
                <?php echo form_error('State'); ?>
            </div>
            <div class="row mt-4">
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Title*" name="job_roles" />
                        <?php echo form_error('title'); ?>
                    </div>
                </div>
                <div class="col-sm-6 col-12 mt-sm-0 mt-4">
                    <select class="form-control" aria-label="Default select example" name="company_size">
                        <option selected>Employee Count*</option>
                        <option value="1-5">1 - 5</option>
                        <option value="6-25">6 - 25</option>
                        <option value="26-50">26 - 50</option>
                        <option value="51-100">51 - 100</option>
                        <option value="101-250">101 - 250</option>
                        <option value="251-500">251 - 500</option>
                        <option value="501+">501+</option>
                    </select>
                    <?php echo form_error('company_size'); ?>

                </div>
            </div>
            <div class="form-group mt-4">
                <input type="text" class="form-control" placeholder="Company Name*" name="company_name" />
                <?php echo form_error('company_name'); ?>

            </div>
            <div class="form-group mt-4">
                <textarea class="form-control textarea" id="client_message" rows="10" placeholder="Message Please let us know the types of Features that are most important to you and what you are looking for in your new system." name="client_message"></textarea>
            </div>

            <div class="row mt-4" style="display: none">
                <div class="col-sm-12">
                    <label>
                        Subscribe to our weekly newsletter!
                    </label>
                </div>
                <div class="col-sm-12">
                    <input type="radio" name="newsletter_subscribe" value="1" checked />
                    <label>Yes</label>
                    &nbsp;&nbsp;
                    <input type="radio" name="newsletter_subscribe" value="0" />
                    <label>No</label>
                </div>
            </div>

            <div class="form-group mt-4">
                <div class="g-recaptcha" data-sitekey="<?= getCreds("AHR")->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                <?php echo form_error('g-recaptcha-response'); ?>
            </div>

            <button class="button p-3 explore_btn schedule-btn schedule-btn-demo d-flex text-white mt-4 width_100 mb-lg-0 mb-5 auto-schedule-btn jsButtonAnimate jsScheduleDemoMainBtn <?= $buttonClass ?? ""; ?>" id="schedule-free-demo-form-submit" type="submit">
                <p class="mb-0 btn-text">
                    <?= convertToStrip($popupContent["page"]["sections"]["pageDetails"]["buttonText"]); ?>
                </p>
                <i class="fa-solid fa-arrow-right schedule-btn-adj top-button-icon ps-3"></i>
            </button>
        </div>
        <img src="<?= image_url("Ellipse 2.png"); ?>" class="yellow-half-circle-form" alt="half-purple-circle" />
        <img src="<?= image_url("Ellipse 10.png"); ?>" class="light-blue-half-circle-form" alt="half-purple-circle" />
    </div>
</form>