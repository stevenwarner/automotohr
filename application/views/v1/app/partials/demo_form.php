<form method="post" action="javascript:void(0)" class="form" id="jsScheduleFreeDemoMain">
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
    <div class="row mt-4">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Title*" name="job_roles" />
                <?php echo form_error('title'); ?>

            </div>
        </div>
        <div class="col-sm-6 col-12 mt-sm-0 mt-4">
            <select class="form-select select-box" aria-label="Default select example" name="company_size">
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
        <textarea class="form-control textarea" id="client_message" rows="10" placeholder="Message" name="client_message"></textarea>
    </div>

    <div class="row mt-4">
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

    <button class="button p-3 explore_btn schedule-btn schedule-btn-demo d-flex text-white mt-4 width_100 mb-lg-0 mb-5 auto-schedule-btn jsButtonAnimate jsScheduleDemoMainBtn" id="schedule-free-demo-form-submit" type="submit">
        <p class="mb-0 btn-text">
            Schedule Your No Obligation Consultation
        </p>
        <i class="fa-solid fa-arrow-right schedule-btn-adj top-button-icon ps-3"></i>
    </button>
</form>