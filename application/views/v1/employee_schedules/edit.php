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
                                                Edit Employees Pay Schedule
                                            </strong>
                                        </h2>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="<?= base_url("schedules/employees"); ?>" class="btn btn-orange">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            &nbsp;View Employees Pay Schedule
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php if ($employees) {
                                    foreach ($employees as $v0) {
                                ?>
                                        <div class="row jsPayScheduleRow" data-id="<?= $v0["userId"] ?>">
                                            <br />
                                            <div class="col-sm-6">
                                                <p class="text-medium">
                                                    <?= remakeEmployeeName($v0); ?>
                                                </p>
                                            </div>

                                            <div class="col-sm-6">
                                                <select class="form-control jsPaySchedule">
                                                    <?php if ($schedules) {
                                                        $scheduleOptions = '<option value="0"></option>';

                                                        foreach ($schedules as $v1) {
                                                            $scheduleOptions .= '<option value="' . ($v1["sid"]) . '" ' . ($employee_schedule_ids[$v0["userId"]] && $employee_schedule_ids[$v0["userId"]] == $v1["sid"] ? "selected" : "") . '>' . (convertPayScheduleToText($v1)) . '</option>';
                                                        }

                                                        echo $scheduleOptions;
                                                    } ?>
                                                </select>
                                            </div>

                                        </div>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <div class="alert alert-info text-center">
                                        <p class="text-medium">
                                            No employees found.
                                        </p>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="panel-footer text-right">
                                <a href="<?= base_url("schedules/employees"); ?>" class="btn btn-black">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    &nbsp;Cancel
                                </a>
                                <?php if ($employees) { ?>
                                    <button class="btn btn-orange jsUpdateEmployeesPaySchedule">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                        Update Employees Pay Schedule
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if ($employees && $employee_schedule_ids) {
    $to = [];
    foreach ($employee_schedule_ids as $index => $v0) {
        if ($to[$v0]) {
            $to[$v0] = [];
        }
        $to[$v0][] = $index;
    }
?>

    <script>
        const selectedValues = <?= json_encode($to); ?>;
    </script>
<?php
}
?>