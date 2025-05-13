<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!-- Top -->
                            <div class="row">
                                <div class="heading-title page-title">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <h1 class="page-title">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                            &nbsp;Home page
                                        </h1>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="<?= base_url("manage_admin/cms"); ?>" class="black-btn">
                                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                            &nbsp;
                                            Back To CMS
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <!-- Meta -->
                            <?php $this->load->view("manage_admin/cms/v1/partials/meta"); ?>
                            <!-- Slider -->
                            <?php $this->load->view("manage_admin/cms/v1/partials/slider"); ?>

                            <!-- highlighting Special -->
                            <?php $this->load->view("manage_admin/cms/v1/partials/highlighting_special"); ?>

                            <!-- What we offer -->
                            <?php $this->load->view("manage_admin/cms/v1/partials/home_section_1"); ?>
                            <!-- Products -->
                            <?php $this->load->view("manage_admin/cms/v1/partials/home_section_2"); ?>
                            <!-- Process -->
                            <?php $this->load->view("manage_admin/cms/v1/partials/our_process"); ?>
                            <!-- About -->
                            <?php $this->load->view("manage_admin/cms/v1/partials/about_section"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>