<?php if ($employees && $schedules) {
    // convert to key value pair
    $tmp = [];
    foreach ($schedules as $v0) {
        $tmp[$v0["sid"]] = $v0;
    }
    $schedules = $tmp;
    // convert to key value pair
    $tmp = [];
    foreach ($employees as $v0) {
        $tmp[$v0["userId"]] = $v0;
    }
    $employees = $tmp;
    // set
    $employeesInGroup = [];
}
?>

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
                                                Employee Pay Schedules
                                            </strong>
                                        </h2>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="<?= base_url("schedules/employees/edit"); ?>" class="btn btn-orange">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                            &nbsp;Edit Employees Pay Schedule
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($employee_schedule_ids) {
                            foreach ($employee_schedule_ids as $payScheduleId => $employeeIds) {
                        ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h2 class="text-medium panel-heading-text">
                                            <i class="fa fa-calendar text-orange" aria-hidden="true"></i>
                                            &nbsp;
                                            <strong>
                                                <?= convertPayScheduleToText($schedules[$payScheduleId]); ?>
                                            </strong>
                                        </h2>
                                    </div>
                                    <div class="panel-body">
                                        <?php foreach ($employeeIds as $employeeId) {
                                            $employeesInGroup[] = $employeeId;
                                        ?>
                                            <div class="csEmployeeBox">
                                                <a href="javascript:void(0)">
                                                    <img src="<?= getImageURL($employees[$employeeId]["profile_picture"]); ?>" alt="" />
                                                    <p class=""><?= remakeEmployeeName($employees[$employeeId], true, true); ?></p>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>

                        <?php if ($employees) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="text-medium panel-heading-text">
                                        <i class="fa fa-calendar text-orange" aria-hidden="true"></i>
                                        &nbsp;
                                        <strong>
                                            Not on Pay Schedule
                                        </strong>
                                    </h2>
                                </div>
                                <div class="panel-body">
                                    <?php foreach ($employees as $v0) {
                                        if (in_array($v0["userId"], $employeesInGroup)) {
                                            continue;
                                        } ?>
                                        <div class="csEmployeeBox">
                                            <a href="javascript:void(0)">
                                                <img src="<?= getImageURL($v0["profile_picture"]); ?>" alt="" />
                                                <p class=""><?= remakeEmployeeName($v0, true, true); ?></p>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>