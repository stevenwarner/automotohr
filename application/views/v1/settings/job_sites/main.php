<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Page header -->
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <?php $this->load->view('manage_employer/company_logo_name'); ?>
                        </span>
                    </div>
                    <!-- Page title -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("my_settings"); ?>" class="btn btn-black">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Settings
                            </a>
                        </div>
                    </div>
                    <br />

                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <div role="tabpanel">
                        <!-- Page content -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h2 class="text-medium panel-heading-text">
                                            <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                            &nbsp;
                                            <strong>
                                                Job sites
                                            </strong>
                                        </h2>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button class="btn btn-orange jsAddJobSiteBtn">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            &nbsp;Add a Job Site
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <?php if ($records) {
                                        foreach ($records as $v0) {
                                    ?>
                                            <div class="col-md-3 jsBox" data-id="<?= $v0["sid"]; ?>">
                                                <div class="panel panel-default">
                                                    <div class="panel-body">

                                                        <p class="text-medium">
                                                            <span class="text-small">Name</span>
                                                            <br />
                                                            <strong><?= $v0["site_name"] ?></strong>
                                                        </p>

                                                        <p class="text-medium">
                                                            <span class="text-small">Address</span>
                                                            <br />
                                                            <strong>
                                                                <?=makeAddress($v0);?>
                                                            </strong>
                                                        </p>

                                                        <p class="text-medium">
                                                            <span class="text-small">Radius</span>
                                                            <br />
                                                            <strong><?= $v0["site_radius"] ?> meters</strong>
                                                        </p>
                                                    </div>
                                                    <div class="panel-footer text-center">
                                                        <button class="btn btn-yellow jsEditJobSiteBtn">
                                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                                            &nbsp;Edit
                                                        </button>
                                                        <button class="btn btn-red jsDeleteJobSiteBtn">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                            &nbsp;Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        $this->load->view("v1/no_data");
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>