<main>
    <div class="row">
        <div class="col-xs-12 column-flex-center  top-background-div-resources" style="background-image: url(./assets/v1/app/images/resourcesBanner.png);">
            <div class="background-image-div-contact-us width-50 ">
                <h1 class="automotoH1 darkGreyColor sora-family  margin-bottom-30 font-size-40 ">Resources</h1>
                <p class="autmotoPara text-center opacity-80">Delve into expert-crafted insights, tips, tools, and articles covering various professional domains.</p>
            </div>
        </div>
    </div>
    <section class="light-grey-background resources">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product resources-page">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="desktop-left sora-family darkGreyColor margin-bottom-30">Latest blog posts</h2>
                        </div>
                    </div>
                    <div class="row" id="jsBlogSection">
                        <?php $this->load->view("v1/app/resources/partials/blogs", [
                            "blogs" => $blogs
                        ]); ?>
                    </div>
                    <?php if (!empty($resources)) { ?>
                        <div class="row">
                            <div class="col-xs-12 margin-top-30 column-flex-center">
                                <button class="d-flex justify-content-center align-items-center load-more login-screen-btns admin_btn btn-animate margin-top-30 jsLoadMoreBlog">
                                    <p class="text">Load More</p>
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="resources">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product resources-page">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="d-flex search-div-border margin-bottom-20">
                                <div class="background-white">
                                    <img src="<?= base_url('assets/v1/app/images/iconsearch.png'); ?>" alt="icon search" />
                                </div>
                                <input id="jsSearchResources" class="no-margin opacity-50 search-input-font" type="email" placeholder="search topics and keywords" />
                            </div>
                        </div>
                        <div class="reource-row-wrapper">
                            <div class="col-xs-12 resources-checkbox-row  d-flex  justify-between padding-left-right-50">
                                <div class="d-flex  ">
                                    <p>Resource Type :</p>
                                </div>
                                <div class=" resources-select ">
                                    <select class="form-select form-select-lg mb-3 padding-top-0px" aria-label=".form-select-lg example">
                                        <option selected>Select</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="d-flex show-on-desktop ">
                                    <div class="form-check">
                                        <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Video" type="checkbox">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Videos
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Audio" type="checkbox">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Audios
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Webinars" type="checkbox">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Webinars
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Articles" type="checkbox">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Articles
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="eBooks" type="checkbox">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            eBooks
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Other" type="checkbox">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row card-second-section-resources" id="jsResourceSection">
                        <?php $this->load->view("v1/app/resources/partials/resources", [
                            "resources" => $resources
                        ]); ?>
                    </div>
                    <?php if (!empty($resources)) { ?>
                        <div class="row">
                            <div class="col-xs-12 margin-top-30 column-flex-center">
                                <button class="d-flex load-more justify-content-center align-items-center login-screen-btns admin_btn btn-animate margin-top-30 jsLoadMoreResources">
                                    <p class="text">Load More</p>
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="resources-subscribe-section ">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product resources-page padding-top-0px">
                    <div class="row resources-subscribe-row ">
                        <div class="col-xs-12 col-xl-7 column-center">
                            <p class="autmotoPara subscribe-para">
                                Join our community of subscribers and experience growth through expertly curated insights for your business.
                            </p>
                            <div class="file-btn-div subscribe-input-div">
                                <input id="jsSubscriberEmail" class="upload-file-input subscribe-input" placeholder="you@yourcompany.com" />
                                <button for="file-input" id="jsSubscribeCommunity" class="custom-file-upload ">
                                    Subscribe
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-xs-12 col-xl-5">
                            <div class="resources-subscribe-image-div">
                                <img src="<?= base_url('assets/v1/app/images/multipleletters.png'); ?>" alt="multiple letters" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade resources-subscribe-model" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog position-relative">
            <div class="modal-content">
                <button type="button resources-subscribe-model-btn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="<?= base_url('assets/v1/app/images/letterWithTick.png'); ?>" alt="letter with tick" />
                    </div>
                    <p class="text-center darkGreyColor fw-500">Thank you for opting to stay updated through our blog!</p>
                    <p class="text-center dark-grey-color">Get ready to receive exciting content.</p>
                </div>
            </div>
        </div>
    </div>


    <div id="js-loader" class="text-center my_loader" style="display: none;">
        <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
        <div class="loader-icon-box cs-loader-box">
            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
            <div class="loader-text cs-loader-text" id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...</div>
        </div>
    </div>

</main>