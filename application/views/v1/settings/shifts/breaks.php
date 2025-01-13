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
                            <a href="<?= base_url("dashboard"); ?>" class="btn btn-black">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Dashboard
                            </a>
                            <a href="<?= base_url("settings/shifts/manage"); ?>" class="btn btn-black">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Manage Shifts
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
                                    <div class="col-sm-6">
                                        <h2 class="text-medium panel-heading-text">
                                            <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                            &nbsp;
                                            <strong>
                                                Breaks
                                            </strong>
                                        </h2>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button class="btn btn-orange jsAddBreakBtn">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            &nbsp;Add Break
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
                                                            <strong><?= $v0["break_name"] ?></strong>
                                                        </p>

                                                        <p class="text-medium">
                                                            <span class="text-small">Duration</span>
                                                            <br />
                                                            <strong><?= $v0["break_duration"] ?> mins</strong>
                                                        </p>
                                                        <p class="text-medium">
                                                            <span class="text-small">Type</span>
                                                            <br />
                                                            <strong><?= ucfirst($v0["break_type"]); ?></strong>
                                                        </p>
                                                    </div>
                                                    <div class="panel-footer text-center">
                                                        <button class="btn btn-yellow jsEditShiftBreakBtn">
                                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                                            &nbsp;Edit
                                                        </button>
                                                        <button class="btn btn-red jsDeleteShiftBreakBtn">
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