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
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs hidden" role="tablist">
                            <li role="presentation" class="text-medium <?= $status === "active" ? "active" : "bg-default"; ?>">
                                <a href="<?= base_url("schedules/active"); ?>">
                                    Active Schedules
                                </a>
                            </li>
                            <li role="presentation" class="text-medium <?= $status === "inactive" ? "active" : "bg-default"; ?>">
                                <a href="<?= base_url("schedules/inactive"); ?>">
                                    InActive Schedules
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <!-- Page content -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h2 class="text-medium panel-heading-text">
                                            <i class="fa fa-calendar text-orange" aria-hidden="true"></i>
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
                                    <?php if ($schedules) {
                                        foreach ($schedules as $v0) {
                                    ?>
                                            <div class="col-md-3">
                                                <div class="panel panel-default csRelative">
                                                    <div class="panel-body">
                                                        <p class="text-medium">
                                                            <strong><?= $v0["custom_name"] ?? "Default"; ?></strong>
                                                            <br />
                                                            <span class="text-small">Name</span>
                                                        </p>
                                                        <p class="text-medium">
                                                            <strong><?= $v0["frequency"]; ?></strong>
                                                            <br />
                                                            <span class="text-small">Pay frequency</span>
                                                        </p>
                                                        <p class="text-medium">
                                                            <strong><?= formatDateToDB($v0["anchor_pay_date"], DB_DATE, DATE); ?></strong>
                                                            <br />
                                                            <span class="text-small">First pay date</span>
                                                        </p>
                                                        <p class="text-medium">
                                                            <strong><?= $v0["deadline_to_run_payroll"] ? formatDateToDB($v0["deadline_to_run_payroll"], DB_DATE, DATE) : "-"; ?></strong>
                                                            <br />
                                                            <span class="text-small">Deadline to run payroll</span>
                                                        </p>
                                                    </div>
                                                    <div class="panel-footer text-center">
                                                        <a href="<?= base_url("schedules/edit/" . $v0['sid']); ?>" class="btn btn-yellow">
                                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                                            &nbsp;Edit
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        $this->load->view("v1/no_data", [
                                            "message" => "No company pay schedules found."
                                        ]);
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