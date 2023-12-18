<?php
if ($filter["mode"] === "month") {
    $monthDates = getMonthDatesByYearAndMonth($filter["year"], $filter["month"], DB_DATE);
    $startDate = formatDateToDB(
        $monthDates[0],
        DB_DATE,
        SITE_DATE
    );
    $endDate = formatDateToDB(
        $monthDates[count($monthDates) - 1],
        DB_DATE,
        SITE_DATE
    );
} else {
    $startDate = $filter["start_date"];
    $endDate = $filter["end_date"];
    $monthDates = getDatesInRange(
        $filter["start_date"],
        $filter["end_date"],
        DB_DATE
    );
}
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
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
                                <h2 class="text-large weight-6 panel-heading-text">
                                    Scheduling
                                </h2>
                                <p class="text-small myt-10">
                                    Manage shift scheduling from one place.
                                </p>
                            </div>
                            <div class="panel-body">
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-4 text-left">
                                        <button class="btn btn-orange jsApplyTemplate">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            Apply Template
                                        </button>
                                        <button class="btn btn-orange">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            Create
                                        </button>
                                        <button class="btn btn-red">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                            Delete
                                        </button>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <a href="<?= base_url("settings/shifts/manage?mode=week"); ?>" class="btn btn-orange <?= $filter["mode"] === "week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Week
                                        </a>
                                        <a href="<?= base_url("settings/shifts/manage?mode=two_week"); ?>" class="btn btn-orange <?= $filter["mode"] === "two_week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            2 Week
                                        </a>
                                        <a href="<?= base_url("settings/shifts/manage?mode=month"); ?>" class="btn btn-orange <?= $filter["mode"] === "month" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Month
                                        </a>
                                    </div>

                                    <div class="col-sm-4 text-right">
                                        <button class="btn btn-orange" data-type="month">
                                            <i class="fa fa-exchange" aria-hidden="true"></i>
                                            Copy shifts from last cycle
                                        </button>
                                        <button class="btn btn-blue">
                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                            Filters
                                        </button>
                                    </div>
                                </div>
                                <br />
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="text-small">
                                            <span class="circle circle-orange"></span>
                                            Geo Fence
                                        </p>
                                    </div>
                                </div>
                                <!-- Main -->
                                <div class="schedule-container myt-10">
                                    <div class="row">
                                        <div class="col-sm-3" style="padding-right: 0">
                                            <div class="schedule-sidebar">
                                                <!-- navigator -->
                                                <div class="schedule-navigator">
                                                    <span class="schedule-navigator-arrow schedule-navigator-left-arrow jsNavigateLeft">
                                                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                                    </span>
                                                    <span class="schedule-navigator-text jsWeekDaySelect">
                                                        <?= formatDateToDB($startDate, SITE_DATE,  "M d, y"); ?> -
                                                        <?= formatDateToDB($endDate, SITE_DATE, "M d, y"); ?>
                                                    </span>
                                                    <span class="schedule-navigator-arrow schedule-navigator-right-arrow jsNavigateRight">
                                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <!-- employee boxes -->
                                                <?php if ($employees) {
                                                    foreach ($employees as $employee) {

                                                        $employeeShiftRow = $shifts[$employee["userId"]];
                                                ?>
                                                        <div class="schedule-employee-row" data-id="<?= $employee["userId"]; ?>">
                                                            <div class="row">
                                                                <div class="col-sm-2">
                                                                    <img src="<?= getImageURL($employee["profile_picture"]); ?>" alt="" />
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <p class="text-small weight-6 myb-0">
                                                                        <?= remakeEmployeeName($employee, true, true); ?>
                                                                    </p>
                                                                    <p class="text-small">
                                                                        <?= remakeEmployeeName($employee, false); ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2 text-right">
                                                                    <span class="text-small">
                                                                        <?= $employeeShiftRow["totalTimeText"]; ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <!-- planned hours -->
                                                <div class="schedule-footer">
                                                    <p class="text-medium weight-6 text-center">
                                                        Planned Hours
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9" style="padding-left: 0">
                                            <div class="schedule-row-container">
                                                <?php foreach ($monthDates as $monthDate) { ?>
                                                    <?php $totalHoursInSeconds = 0; ?>
                                                    <!-- column-->
                                                    <div class="schedule-column-container" data-date="<?= $monthDate; ?>">
                                                        <div class="schedule-column-header">
                                                            <p class="text-center text-small">
                                                                <?= formatDateToDB($monthDate, DB_DATE, "D d"); ?>
                                                            </p>
                                                        </div>
                                                        <?php if ($employees) {
                                                            foreach ($employees as $employee) {
                                                                // get the employee shift
                                                                $employeeShift = $shifts[$employee["userId"]]["dates"][$monthDate];
                                                        ?>
                                                                <div class="schedule-column schedule-column-clickable schedule-column-<?= $employee["userId"]; ?> text-center" data-eid="<?= $employee["userId"]; ?>">
                                                                    <?php if ($employeeShift) {
                                                                        $totalHoursInSeconds += $employeeShift["totalTime"];
                                                                    ?>
                                                                        <div class="schedule-item">
                                                                            <?php if ($employeeShift["geo_fence"]) { ?>
                                                                                <span class="circle circle-orange"></span>
                                                                            <?php } ?>
                                                                            <p class="text-small">
                                                                                <?= formatDateToDB(
                                                                                    $employeeShift["start_time"],
                                                                                    "H:i:s",
                                                                                    "h:i a"
                                                                                ); ?> -
                                                                                <?= formatDateToDB(
                                                                                    $employeeShift["end_time"],
                                                                                    "H:i:s",
                                                                                    "h:i a"
                                                                                ); ?>
                                                                            </p>
                                                                        </div>
                                                                    <?php } elseif ($holidays[$monthDate]) { ?>
                                                                        <div class="schedule-dayoff">
                                                                            <button class="btn btn-red text-small btn-xs">
                                                                                <?= $holidays[$monthDate]["title"]; ?>
                                                                            </button>
                                                                        </div>
                                                                    <?php } else { ?>

                                                                    <?php } ?>
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <div class="schedule-footer schedule-border">
                                                            <p class="text-small text-center">
                                                                <?= convertSecondsToTime($totalHoursInSeconds); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php } ?>
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
</div>