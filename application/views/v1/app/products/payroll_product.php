<main>
    <section class="top-form-section">
        <div class="row product-screen product-screen-top-padding ">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center position-relative">
                <img src="/assets/v1/app/images/Ellipse 1.png" class="blue-small-full-circle" alt="blue-small-full-circle" />
                <div class="product-screen-text">
                    <h1 class="product-screen-top-heading">
                        <?php echo $productsContent['page']['sections']['section1']['heading1']; ?> </h1>
                    <p class="product-screen-second-text opacity-80-product">
                        <?php echo $productsContent['page']['sections']['section1']['heading1Detail']; ?>
                    </p>
                </div>
                <img src="/assets/v1/app/images/Ellipse 3.png" class="grey-full-circle" alt="grey-full-circle" />
                <img src="/assets/v1/app/images/Ellipse 4 (1).png" class="red-small-full-circle" alt="red-small-full-circle" />
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center form-area-product ">
                <form method="post" action="<?= base_url('schedule_your_free_demo'); ?>" class="form" id="schedule-free-demo-form">

                    <div class="position-relative">
                        <img src="/assets/v1/app/images/Ellipse 9.png" class="purple-half-div" alt="half-purple-circle" />
                        <div class="form-div">
                            <div class="highlighted-div column-flex-center opacity-80-product">
                                <p><span class="highlighted-light-blue-div">Want</span>the Inside Secret on People Operations?</p>
                            </div>

                            <h2 class="">
                                <?php echo $productsContent['page']['sections']['section1']['heading']; ?>
                            </h2>


                            <input type="hidden" class="d-block " id="pagename" placeholder="Name*" name="pagename" value="<?php echo $pageSlug; ?>" />

                            <input type="text" class="d-block " id="name1" placeholder="Name*" name="name" required />
                            <?php echo form_error('name'); ?>
                            <input type="email" class="d-block" id="email_id1" placeholder="Email*" name="email" required />
                            <?php echo form_error('email'); ?>
                            <input type="text" class="d-block" id="phone_number1" placeholder="Phone Number*" name="phone_number" required />
                            <?php echo form_error('phone_number'); ?>

                            <input type="text" class="title-field" placeholder="title*" name="title" id="title1" required />
                            <?php echo form_error('title'); ?>

                            <select class="form-select select-form-field" aria-label="Default select example" name="company_size" id="company_size1">
                                <option selected>Employee Count*</option>
                                <option value="1-5">1 - 5</option>
                                <option value="6-25">6 - 25</option>
                                <option value="26-50">26 - 50</option>
                                <option value="51-100">51 - 100</option>
                                <option value="101-250">101 - 250</option>
                                <option value="251-500">251 - 500</option>
                                <option value="501+">501+</option>
                            </select>

                            <?php echo form_error('job_roles'); ?>

                            <input type="text" class="d-block" placeholder="Company Name*" name="company_name" id="company_name1" required />
                            <?php echo form_error('company_name'); ?><br>

                            <textarea class="form-control border-radius-25 dark-grey-color" id="client_message" rows="4" placeholder="Message" name="client_message" id="client_message1"></textarea>
                            <div class="form-group mt-4">
                                <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                <?php echo form_error('g-recaptcha-response'); ?>
                            </div>

                            <button class="margin-top-twent center-horizontally schedule-btn-product margin-top-twenty btn-animate has-spinner" id="schedule-free-demo-form-submit1" type="submit">
                                <p class="text">Schedule Your No Obligation Consultation</p> <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                            </button>
                        </div>
                        <img src="/assets/v1/app/images/Ellipse 2.png" class="yellow-half-circle-form" alt="half-purple-circle" />
                        <img src="/assets/v1/app/images/Ellipse 10.png" class="light-blue-half-circle-form" alt="half-purple-circle" />
                    </div>
                </form>
            </div>


        </div>
    </section>
    <section class="blue-section dark-blue-background ">
        <div class="row yellow-quarter-background" style="background-image: url(/assets/v1/app/images/quarteryellow.png);">
            <div class="col-xs-12 column-flex-center">
                <div class="text-center div-blue-section-padding-product column-flex-center">
                    <p class="highlighted-div text-white opacity-90-product  margin-top-60"><span class="highlighted-light-blue-div">Up to 20%</span>Lifetime Commissions On Your Referrals for as long as they remain our client</p>
                    <h2 class="white-heading-product text-white"><?php echo $productsContent['page']['sections']['section2']['heading']; ?>
                    </h2>
                </div>
            </div>
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 blue-image-section-lower-padding">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="text-center">
                                <img class="product-image-div" src="/assets/v1/app/images/peoplesitting.png" alt="peoplesitting" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-flex-center">
                            <div>
                                <h3 class="white-subheading-product text-white">
                                    <?php echo $productsContent['page']['sections']['section2']['heading1']; ?>
                                </h3>
                            </div>
                            <div class="text-white opacity-90-product white-subheading-text-product margin-top-20">
                                <?php echo $productsContent['page']['sections']['section2']['heading1Detail']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-md-12 image-section-padding-product">
                <div class=" linebackround column-flex-center">
                    <h2>Payroll</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class=" col-xl-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 order-2-product">
                            <div class="text-center">
                                <img class="product-image-div" src="/assets/v1/app/images/framewithoutshadow.png" alt="dashboard" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center order-1-product">
                            <div>
                                <h3 class="white-subheading-product margin-bottom-30 image-section-subheading">
                                    <?php echo $productsContent['page']['sections']['section3']['heading']; ?>
                                </h3>
                            </div>
                            <div class="image-section-text opacity-90-product ">
                                <?php echo $productsContent['page']['sections']['section3']['headingDetail']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" col-xl-12 column-flex-center">
            <div class="w-80 image-section-padding-product">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                        <div class="text-center">
                            <img class="product-image-div" src="/assets/v1/app/images/Frame 242.png" alt="peoplesitting" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center">
                        <div>
                            <h3 class="white-subheading-product margin-bottom-30  image-section-subheading">
                                <?php echo $productsContent['page']['sections']['section4']['heading']; ?>
                            </h3>
                        </div>
                        <div class="image-section-text opacity-90-product "><?php echo $productsContent['page']['sections']['section4']['headingDetail']; ?> </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
            <div class=" col-xl-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 order-2-product">
                            <div class="text-center">
                                <img class="product-image-div" src="/assets/v1/app/images/Frame 241.png" alt="dashboard" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center order-1-product">
                            <div>
                                <h3 class="white-subheading-product margin-bottom-30  image-section-subheading">
                                    <?php echo $productsContent['page']['sections']['section5']['heading']; ?>
                                </h3>
                            </div>
                            <div class="image-section-text opacity-90-product ">
                                <?php echo $productsContent['page']['sections']['section5']['headingDetail']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-xl-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="text-center">
                                <img class="product-image-div" src="/assets/v1/app/images/Frame 2.png" alt="peoplesitting" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center">
                            <div>
                                <h3 class="white-subheading-product margin-bottom-30 image-section-subheading">
                                    <?php echo $productsContent['page']['sections']['section6']['heading']; ?>
                                </h3>
                            </div>
                            <div class="image-section-text opacity-90-product ">
                                <?php echo $productsContent['page']['sections']['section6']['headingDetail']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class=" col-xl-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 order-2-product">
                            <div class="text-center">
                                <img class="product-image-div" src="/assets/v1/app/images/Frame 2.png" alt="peoplesitting" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center">
                            <div>
                                <h3 class="white-subheading-product margin-bottom-30 image-section-subheading">
                                    <?php echo $productsContent['page']['sections']['section7']['heading']; ?>
                                </h3>
                            </div>
                            <div class="image-section-text opacity-90-product ">
                                <?php echo $productsContent['page']['sections']['section7']['headingDetail']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class=" col-xl-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="text-center">
                                <img class="product-image-div" src="/assets/v1/app/images/Frame 2.png" alt="peoplesitting" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 column-center">
                            <div>
                                <h3 class="white-subheading-product margin-bottom-30 image-section-subheading">
                                    <?php echo $productsContent['page']['sections']['section8']['heading']; ?>
                                </h3>
                            </div>
                            <div class="image-section-text opacity-90-product ">
                                <?php echo $productsContent['page']['sections']['section8']['headingDetail']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
    <section class="padding-top-50">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-5 position-relative">
                            <div class="text-center width-height-100 not-show-on-mob">
                                <img class="w-100 man-standing-image  " src="/assets/v1/app/images/image 42.png" alt="dashboard" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-7 column-center padding-20-products">

                            <form method="post" action="<?= base_url('schedule_your_free_demo'); ?>" class="form" id="schedule-free-demo-form2">

                                <div class="position-relative">
                                    <img src="/assets/v1/app/images/Ellipse 9.png" class="man-standingimage second-purple-half" alt="half-purple-circle" />
                                    <div class="form-div">
                                        <div class="highlighted-div column-flex-center opacity-80-product">
                                            <p><span class="highlighted-light-blue-div">Want</span>the Inside Secret on People Operations?</p>
                                        </div>

                                        <h2 class="">
                                            <?php echo $productsContent['page']['sections']['section9']['heading']; ?>
                                        </h2>

                                        <input type="hidden" class="d-block " id="pagename" placeholder="Name*" name="pagename" value="<?php echo $pageSlug; ?>" />

                                        <input type="text" class="d-block " id="name2" placeholder="Name*" name="name" required />
                                        <?php echo form_error('name'); ?>
                                        <input type="email" class="d-block" id="email_id2" placeholder="Email*" name="email" required />
                                        <?php echo form_error('email'); ?>
                                        <input type="text" class="d-block" id="phone_number2" placeholder="Phone Number*" name="phone_number" required />
                                        <?php echo form_error('phone_number'); ?>

                                        <input type="text" class="title-field" placeholder="title*" name="title" id="title2" required />
                                        <?php echo form_error('title'); ?>

                                        <select class="form-select select-form-field" aria-label="Default select example" name="company_size" id="company_size2">
                                            <option selected>Employee Count*</option>
                                            <option value="1-5">1 - 5</option>
                                            <option value="6-25">6 - 25</option>
                                            <option value="26-50">26 - 50</option>
                                            <option value="51-100">51 - 100</option>
                                            <option value="101-250">101 - 250</option>
                                            <option value="251-500">251 - 500</option>
                                            <option value="501+">501+</option>
                                        </select>

                                        <?php echo form_error('job_roles'); ?>

                                        <input type="text" class="d-block" placeholder="Company Name*" name="company_name" id="company_name2" required />
                                        <?php echo form_error('company_name'); ?><br>

                                        <textarea class="form-control border-radius-25 dark-grey-color" id="client_message2" rows="4" placeholder="Message" name="client_message"></textarea>

                                        <div class="form-group mt-4">
                                            <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                            <?php echo form_error('g-recaptcha-response'); ?>
                                        </div>

                                        <button class="margin-top-twent w-100 center-horizontally schedule-btn-product margin-top-twenty btn-animate has-spinner" id="schedule-free-demo-form-submit2" type="submit">
                                            <p class="text">Schedule Your No Obligation Consultation</p> <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                        </button>
                                    </div>

                                    <img src="/assets/v1/app/images/yellow-half.png" class="second-light-blue-half-circle-form" alt="half-purple-circle" />
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <?php $this->load->view('v1/app/partials/loader'); ?>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

<script type="text/javascript">
  var BASEURL = "<?php echo base_url(); ?>";
</script>