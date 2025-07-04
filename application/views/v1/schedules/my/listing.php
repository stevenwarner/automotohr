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
        formatDateToDB($filter["start_date"], SITE_DATE, DB_DATE),
        formatDateToDB($filter["end_date"], SITE_DATE, DB_DATE),
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
                    <div class="page-header-area"> </div>
                    <!-- Page title -->

                    <div class="row"><br>
                        <div class="col-sm-4 text-left">
                            <a href="<?= base_url("dashboard"); ?>" class="btn btn-blue ">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Dashboard
                            </a>
                            <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(SCHEDULE_MODULE)) { ?>
                                <a href="<?= base_url("settings/shifts/manage"); ?>" class="btn btn-orange">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    &nbsp;Shifts
                                </a>

                            <?php } ?>
                        </div>
                        <div class="col-sm-8 text-right">
                            <a href="<?= base_url("shifts/my/subordinates"); ?>" class="btn btn-orange">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                &nbsp;My Team Shifts
                            </a>

                            <a href="<?= base_url("shifts/trade"); ?>" class="btn btn-orange">
                                <i class="fa fa-exchange" aria-hidden="true"></i>
                                &nbsp;Swap Shifts
                            </a>

                            <a href="<?= base_url("shifts/myTrade"); ?>" class="btn btn-orange">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                &nbsp;Shifts Swap Requests
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
                                    My Shifts
                                </h2>
                            </div>
                            <div class="panel-body">
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-4 text-left">

                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <a href="<?= base_url("shifts/my?mode=week"); ?>" class="btn btn-orange <?= $filter["mode"] === "week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Week
                                        </a>
                                        <a href="<?= base_url("shifts/my?mode=two_week"); ?>" class="btn btn-orange <?= $filter["mode"] === "two_week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            2 Week
                                        </a>
                                        <a href="<?= base_url("shifts/my?mode=month"); ?>" class="btn btn-orange <?= $filter["mode"] === "month" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Month
                                        </a>
                                    </div>

                                    <div class="col-sm-4 text-right">

                                        <button class="btn btn-blue jsFilterBtn">
                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                            Filters
                                        </button>
                                    </div>
                                </div>

                                <div class="row jsFilterRow hidden">
                                    <br>
                                    <div class="col-sm-4">
                                        <label class="text-medium">
                                            From Date
                                        </label>
                                        <input type="text" class="form-control datepicker hasDatepicker" name="start_date" id="start_date" autocomplete="off" value="<?= $filter["start_date"]; ?>" />
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="text-medium">
                                            To Date
                                        </label>
                                        <input type="text" class="form-control " name="end_date" id="end_date" autocomplete="off" value="<?= $filter["end_date"]; ?>" />
                                    </div>

                                    <div class=" col-sm-4 text-right">
                                        <span class="pull-right">
                                            <br>
                                            <button id="btn_apply" type="button" class="btn btn-orange jsFilterApplyBtn">APPLY</button>
                                            <button id="btn_reset" type="button" class="btn btn-black btn-theme jsFilterResetBtn">RESET</button>
                                        </span>
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
                                                    <span class="schedule-navigator-arrow schedule-navigator-left-arrow jsNavigateLeftMy">
                                                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                                    </span>
                                                    <span class="schedule-navigator-text jsWeekDaySelect">
                                                        <?= formatDateToDB($startDate, SITE_DATE,  "M d, y"); ?> -
                                                        <?= formatDateToDB($endDate, SITE_DATE, "M d, y"); ?>
                                                    </span>
                                                    <span class="schedule-navigator-arrow schedule-navigator-right-arrow jsNavigateRightMy">
                                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                    </span>
                                                </div>

                                                <!-- Open Shifts-->
                                                <div class="schedule-employee-row multishiftcell" data-id="<?= $loggedInEmployee["userId"]; ?>" style="background-color: #ceffef;">
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                        </div>
                                                        <div class="col-sm-8 schedule-footer" style="border: 0px;">
                                                            <p class="text-medium weight-6 text-center"> Open Shifts</p>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            <span class="text-small">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End -->

                                                <!-- employee boxes -->
                                                <?php $employeeShiftRow = $shifts[$loggedInEmployee["sid"]]; ?>
                                                <div class="schedule-employee-row multishiftcell" data-id="<?= $loggedInEmployee["userId"]; ?>">
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <img src="<?= getImageURL($loggedInEmployee["profile_picture"]); ?>" alt="" />
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <p class="text-small weight-6 myb-0">
                                                                <?= remakeEmployeeName($loggedInEmployee, true, true); ?>
                                                            </p>
                                                            <p class="text-small">
                                                                <?= remakeEmployeeName($loggedInEmployee, false); ?>
                                                            </p>
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            <span class="text-small">
                                                                <?= $employeeShiftRow["totalTimeText"]; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                <?php
                                                $todaysDate = getSystemDate("Y-m-d");
                                                foreach ($monthDates as $monthDate) { ?>
                                                    <?php $totalHoursInSeconds = 0; ?>
                                                    <?php
                                                    $employeeLeave = $leaves[$loggedInEmployee["sid"]][$monthDate];

                                                    $highlightStyle = $todaysDate === $monthDate ? "bg-success" : "";
                                                    ?>

                                                    <!-- column-->
                                                    <div class="schedule-column-container" data-date="<?= $monthDate; ?>">
                                                        <div class="schedule-column-header text-center <?= $highlightStyle; ?>">
                                                            <?= formatDateToDB($monthDate, DB_DATE, "D d"); ?>
                                                            </p>
                                                        </div>
                                                        <?php
                                                        $employeeShift = $shifts[$loggedInEmployee["sid"]]["dates"][$monthDate];
                                                        $employeeOpenShift = $openShifts[0]["dates"][$monthDate];
                                                        ?>

                                                        <!-- open Shift -->
                                                        <div class="schedule-column schedule-column-<?= $loggedInEmployee["sid"]; ?> text-center <?= $highlightStyle; ?> multishiftcell-schedule-column" data-eid="<?= $loggedInEmployee["sid"]; ?> " style="height: 120px;">

                                                            <?php if ($employeeOpenShift) {
                                                                $totalHoursInSeconds += $employeeOpenShift["totalTime"];
                                                                 foreach ($employeeOpenShift as $openshiftRow) { 
                                                            ?>
                                                                <div class="schedule-item item-openshift " data-id="<?= $openshiftRow["sid"]; ?>">
                                                                    <p class="text-small">
                                                                        <?= formatDateToDB(
                                                                            $openshiftRow["start_time"],
                                                                            "H:i:s",
                                                                            "h:i a"
                                                                        ); ?> -
                                                                        <?= formatDateToDB(
                                                                            $openshiftRow["end_time"],
                                                                            "H:i:s",
                                                                            "h:i a"
                                                                        ); ?>
                                                                    </p>
                                                                </div>
                                                            <?php } } ?>
                                                        </div>
                                                        <!-- End--->

                                                        <div class="schedule-column schedule-column-<?= $loggedInEmployee["sid"]; ?> text-center <?= $highlightStyle; ?> multishiftcell-schedule-column" data-eid="<?= $loggedInEmployee["sid"]; ?>" style="height: 120px;">
                                                            <?php if ($employeeLeave) { ?>
                                                                <div class="schedule-dayoff text-primary text-small">
                                                                    <strong>
                                                                        <?= $employeeLeave["title"]; ?>
                                                                    </strong>
                                                                </div>
                                                            <?php } elseif ($employeeShift) {
                                                                $totalHoursInSeconds += $employeeShift["totalTime"];
                                                            ?>

                                                                <?php foreach ($employeeShift as $shiftRow) { ?>
                                                                    <div class="schedule-item " data-id="<?= $shiftRow["sid"]; ?>" >
                                                                        <?php if ($shiftRow["job_sites"] && $shiftRow["job_sites"][0]) { ?>
                                                                            <span class="circle circle-orange"></span>
                                                                        <?php } ?>
                                                                        <p class="text-small">
                                                                            <?= formatDateToDB(
                                                                                $shiftRow["start_time"],
                                                                                "H:i:s",
                                                                                "h:i a"
                                                                            ); ?> -
                                                                            <?= formatDateToDB(
                                                                                $shiftRow["end_time"],
                                                                                "H:i:s",
                                                                                "h:i a"
                                                                            ); ?>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>

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
                                                        ?>
                                                        <div class="schedule-footer schedule-border <?= $highlightStyle; ?>">
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