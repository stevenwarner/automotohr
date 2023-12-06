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
                            <a href="<?= base_url("settings/shifts/breaks"); ?>" class="btn btn-orange">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                &nbsp;Manage Breaks
                            </a>
                            <a href="<?= base_url("settings/shifts/templates"); ?>" class="btn btn-orange">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                &nbsp;Manage Shift Templates
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
                                    <div class="col-sm-12">
                                        <h2 class="text-medium panel-heading-text">
                                            <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                            &nbsp;
                                            <strong>
                                                Manage Shifts
                                            </strong>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>