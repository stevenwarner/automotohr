<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
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
                    <!-- Page content -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h2 class="text-large panel-heading-text">
                                        <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                        &nbsp;
                                        <strong>
                                            Company Pay Schedules
                                        </strong>
                                    </h2>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <a href="<?= base_url("schedules/add"); ?>" class="btn btn-orange">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                        &nbsp;Add a new Schedule
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-medium text-red">
                                        <strong>
                                            Please make sure to verify this information is accurate. If this information isn’t correct, it can delay when your team will be paid.
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <p class="text-large">
                                                <strong>Every Week</strong>
                                                <br />
                                                <span class="text-medium">Pay frequency</span>
                                            </p>
                                            <p class="text-large">
                                                <strong><?= formatDateToDB("2023-12-25", DB_DATE, DATE); ?></strong>
                                                <br />
                                                <span class="text-medium">First pay date</span>
                                            </p>
                                            <p class="text-large">
                                                <strong><?= formatDateToDB("2023-12-20", DB_DATE, DATE); ?></strong>
                                                <br />
                                                <span class="text-medium">Deadline to run payroll</span>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <a href="<?= base_url("schedules/edit/"); ?>" class="btn btn-yellow">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                &nbsp;Edit a Pay Schedule
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>