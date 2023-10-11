<form method="post" action="javascript:void(0)" class="form jsScheduleForm">
    <div class="position-relative">
        <img src="<?= base_url(""); ?>public/v1/images/Ellipse 9.png" class="purple-half-div" alt="half-purple-circle" />
        <div class="form-div">
            <div class="highlighted-div column-flex-center opacity-80-product">
                <p><span class="highlighted-light-blue-div">Want</span>the Inside Secret on People Operations?</p>
            </div>
            <h2>
                <?php echo $productsContent['page']['sections']['section1']['heading']; ?>
            </h2>
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <input type="text" class="d-block " id="name<?=$id??1;?>" placeholder="Name*" name="name" required />
                </div>
                <div class="col-sm-12">
                    <input type="email" class="d-block" id="email_id<?=$id??1;?>" placeholder="Email*" name="email" required />
                </div>
                <div class="col-sm-12">
                    <input type="text" class="d-block" id="phone_number<?=$id??1;?>" placeholder="Phone Number*" name="phone_number" required />
                </div>
                <div class="col-sm-12 col-xl-6">
                    <input type="text" class="d-block" placeholder="Title" name="job_role" id="title<?=$id??1;?>" />
                </div>
                <div class="col-sm-12 col-xl-6">
                    <select class="form-select select-form-field" aria-label="Default select example" name="company_size" id="company_size<?=$id??1;?>">
                        <option selected disabled>Employee Count*</option>
                        <option value="1-5">1 - 5</option>
                        <option value="6-25">6 - 25</option>
                        <option value="26-50">26 - 50</option>
                        <option value="51-100">51 - 100</option>
                        <option value="101-250">101 - 250</option>
                        <option value="251-500">251 - 500</option>
                        <option value="501+">501+</option>
                    </select>
                </div>
                <div class="col-sm-12">
                    <input type="text" class="d-block" placeholder="Company Name*" name="company_name" id="company_name<?=$id??1;?>" required />
                </div>
                <div class="col-sm-12">
                    <textarea class="form-control border-radius-25 dark-grey-color" id="client_message" rows="4" placeholder="Message" name="client_message" id="client_message<?=$id??1;?>"></textarea>
                </div>
                <div class="col-sm-12">
                    <div class="form-group mt-4">
                        <div class="g-recaptcha" data-sitekey="<?= getCreds('AHR')->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button class="w-100 center-horizontally schedule-btn-product margin-top-twenty btn-animate has-spinner jsHover" type="submit">
                        <p class="text">Schedule Your No Obligation Consultation</p>
                        <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                    </button>
                </div>
            </div>

        </div>
        <img src="<?= base_url(""); ?>public/v1/images/Ellipse 2.png" class="yellow-half-circle-form" alt="half-purple-circle" />
        <img src="<?= base_url(""); ?>public/v1/images/Ellipse 10.png" class="light-blue-half-circle-form" alt="half-purple-circle" />
    </div>
</form>