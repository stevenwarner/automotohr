<div class="form_section_main width_100 d-flex flex-column align-items-center top-tweleverem-margin">
    <div class="width_80 d-flex position-relative">
        <img src="<?= base_url('assets/v1/app/images/boy.webp'); ?>" class="form_boy-pic d-lg-flex d-none" alt="man image" />
        <div class="purple_bubble_div d-lg-flex d-none">
            <div class="purple_bubble"></div>
        </div>
        <div class="form_section px-5 d-flex flex-column justify-content-center">
            <div class="d-flex flex-column align-items-center mb-5">
                <p class="lightgrey heading-h4-grey heading opacity-eighty">
                    Want the Inside Secret on People Operations?
                </p>
                <p class="darkgrey title">Watch AutomotoHR in action</p>
            </div>
            <form action="javascript:void(0)">
                <div class="form-group">
                    <input type="text" class="form-control" id="name" placeholder="Name*" name="name" required />
                </div>
                <div class="form-group mt-4">
                    <input type="email" class="form-control" id="email" placeholder="Email*" name="email" required />
                </div>
                <div class="form-group mt-4">
                    <input type="text" class="form-control" id="phone-number" placeholder="Phone Number*" name="phone_number" required />
                </div>
                <div class="row mt-4">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control" id="title" placeholder="title*" name="title" required />
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mt-sm-0 mt-4">
                        <select class="form-select select-box" aria-label="Default select example">
                            <option selected>Employee Count*</option>
                            <option value="1-5">1 - 5</option>
                            <option value="6-25">6 - 25</option>
                            <option value="26-50">26 - 50</option>
                            <option value="51-100">51 - 100</option>
                            <option value="101-250">101 - 250</option>
                            <option value="251-500">251 - 500</option>
                            <option value="501+">501+</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mt-4">
                    <input type="text" class="form-control" id="company-name" placeholder="Company Name*" name="company_name" required />
                </div>
                <div class="form-group mt-4">
                    <textarea class="form-control textarea" id="message" rows="3" placeholder="Message"></textarea>
                </div>

                <button class="button p-3 explore_btn schedule-btn d-flex text-white mt-4 width_100 mb-lg-0 mb-5 auto-schedule-btn">
                    <p class="mb-0 btn-text">
                        Schedule Your No Obligation Consultation
                    </p>
                    <i class="fa-solid fa-arrow-right schedule-btn-adj top-button-icon ps-3"></i>
                </button>

            </form>
        </div>
        <div class="yellow-bubble-div d-lg-block d-none">
            <div class="yellow-bubble"></div>
        </div>
    </div>
    <div class="width_100 auto-schedule-btn-height"></div>
</div>