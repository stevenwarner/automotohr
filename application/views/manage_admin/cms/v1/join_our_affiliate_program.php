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
                                            &nbsp;Join Our Affiliate Program
                                        </h1>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="<?= base_url("manage_admin/cms"); ?>" class="black-btn">
                                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                            &nbsp;
                                            Back To CMS
                                        </a>

                                        <?php if ($page["status"]) { ?>
                                            <button class="btn btn-danger jsPageStatus" data-key="0">
                                                <i class="fa fa-ban"></i>
                                                &nbsp;
                                                Unpublish Page
                                            </button>
                                        <?php } else { ?>
                                            <button class="btn btn-success jsPageStatus" data-key="1">
                                                <i class="fa fa-shield"></i>
                                                &nbsp;
                                                Publish Page
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <!-- Meta -->
                            <?php $this->load->view("manage_admin/cms/v1/partials/meta"); ?>
                            <?php $this->load->view("manage_admin/cms/v1/partials/affiliate_program/section_1"); ?>
                            <?php $this->load->view("manage_admin/cms/v1/partials/affiliate_program/section_2"); ?>
                            <?php $this->load->view("manage_admin/cms/v1/partials/affiliate_program/section_3"); ?>
                            <?php $this->load->view("manage_admin/cms/v1/partials/affiliate_program/section_4"); ?>
                            <?php $this->load->view("manage_admin/cms/v1/partials/affiliate_program/section_5"); ?>
                            <?php $this->load->view("manage_admin/cms/v1/partials/affiliate_program/section_6"); ?>
                            <?php $this->load->view("manage_admin/cms/v1/partials/affiliate_program/section_7"); ?>
                            <?php $this->load->view("manage_admin/cms/v1/partials/affiliate_program/section_8"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>