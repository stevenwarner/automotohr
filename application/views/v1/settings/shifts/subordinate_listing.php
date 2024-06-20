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

<style>
    .conflict-label {
        border-radius: 0 !important;
        font-size: 12px !important;
        height: 23px !important;
        padding: 8px 39px 2px !important;
        right: -28px !important;
        top: -9px !important;
    }

    .conflict-label i {
        color: #cc0000;
    }

    .conflict-label {
        /* background-color: #cc0000; */
        color: #fff;
        font-size: 14px;
        font-style: italic;
        font-weight: 600;
        padding: 10px 33px 2px 33px;
        position: absolute;
        right: -27px;
        text-align: center;
        text-transform: uppercase;
        top: -7px;
        width: 82px;
        height: 30px;
    }
</style>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Page header -->
                    <div class="page-header-area"> </div>
                    <!-- Page title -->
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><br>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("dashboard"); ?>" class="btn btn-blue ">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Dashboard
                            </a>
                            <a href="<?= base_url("shifts/my"); ?>" class="btn btn-orange">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                &nbsp;My Shifts
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
                                    Team Scheduling
                                </h2>
                            </div>
                            <div class="panel-body">
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-4 text-left">

                                    </div>
                                    <div class="col-sm-4 text-center">

                                        <?php
                                        $filterFields = '';
                                        if ($filter["departments"]) {
                                            foreach ($filter["departments"] as $v0) {
                                                $filterFields .= '&departments[]=' . $v0;
                                            }
                                        } else {
                                            $filterFields .= '&departments[]=all';
                                        }

                                        if ($filter["teams"]) {
                                            foreach ($filter["teams"] as $v0) {
                                                $filterFields .= '&teams[]=' . $v0;
                                            }
                                        } else {
                                            $filterFields .= '&teams[]=all';
                                        }

                                        $filterFields .= '&start_date=' . $filter["start_date"];
                                        $filterFields .= '&end_date=' . $filter["end_date"];


                                        ?>
                                        <a href="<?= base_url("shifts/my/subordinates?mode=week" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Week
                                        </a>
                                        <a href="<?= base_url("shifts/my/subordinates?mode=two_week" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "two_week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            2 Week
                                        </a>
                                        <a href="<?= base_url("shifts/my/subordinates?mode=month" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "month" ? "disabled" : ""; ?>">
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
                                    <form action="<?= current_url(); ?>" method="get">
                                        <br>
                                        <div class="col-sm-3">
                                            <?php if ($departments) { ?>
                                                <label class="text-medium">Departments</label>
                                                <select id="js-filter-department" class="jsSelect2" name="departments[]" multiple="multiple">
                                                    <option value="all" <?= !$filter["departments"] ? "selected" : ""; ?>>All</option>
                                                    <?php foreach ($departments as $v0) {
                                                    ?>
                                                        <option value="<?php echo $v0['sid']; ?>" <?= in_array($v0["sid"], $filter["departments"]) ? "selected" : ""; ?>>
                                                            <?= $v0['title']; ?>
                                                        </option>
                                                    <?php }  ?>
                                                </select>
                                            <?php } ?>
                                        </div>

                                        <div class="col-sm-3">
                                            <?php if ($teams) { ?>
                                                <label class="text-medium">Teams</label>
                                                <select id="js-filter-team" class="jsSelect2" name="teams[]" multiple="multiple">
                                                    <option value="all" <?= !$filter["teams"] ? "selected" : ""; ?>>All</option>
                                                    <?php foreach ($teams as $v0) { ?>
                                                        <option value="<?php echo $v0['sid']; ?>" <?= in_array($v0["sid"], $filter["teams"]) ? "selected" : ""; ?>>
                                                            <?= $v0['title']; ?>
                                                        </option>
                                                    <?php }  ?>
                                                </select>
                                            <?php } ?>
                                        </div>

                                        <div class="col-sm-2">
                                            <label class="text-medium">
                                                From Date
                                            </label>
                                            <input type="text" class="form-control datepicker hasDatepicker" name="start_date" id="shift_date_from" autocomplete="off" />
                                        </div>

                                        <div class="col-sm-2">
                                            <label class="text-medium">
                                                To Date
                                            </label>
                                            <input type="text" class="form-control datepicker hasDatepicker" name="end_date" id="shift_date_to" autocomplete="off" />
                                        </div>

                                        <div class="col-sm-2 text-right">
                                            <span class="pull-right">
                                                <br>
                                                <button id="btn_apply" type="submit" class="btn btn-orange jsFilterApplyBtn">APPLY</button>
                                                <a href="<?= current_url() . "?mode=" . $filter["mode"]; ?>" class="btn btn-black btn-theme jsFilterResetBtn">RESET</a>
                                                <input type="hidden" name="mode" value="<?= $filter["mode"]; ?>">
                                            </span>
                                        </div>
                                    </form>
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

                                <?php if ($subordinateEmployees) { ?>
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
                                                    <!-- employee boxes -->
                                                    <?php if ($subordinateEmployees) {
                                                        foreach ($subordinateEmployees as $employee) {
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
                                                    <?php $todaysDate = getSystemDate("Y-m-d");
                                                    foreach ($monthDates as $monthDate) { ?>
                                                        <?php $totalHoursInSeconds = 0; ?>
                                                        <?php

                                                        $highlightStyle = $todaysDate === $monthDate ? "bg-success" : "";
                                                        ?>
                                                        <!-- column-->
                                                        <div class="schedule-column-container" data-date="<?= $monthDate; ?>">
                                                            <div class="schedule-column-header text-center <?= $highlightStyle; ?>">
                                                                <?= formatDateToDB($monthDate, DB_DATE, "D d"); ?>
                                                                </p>
                                                            </div>
                                                            <?php if ($subordinateEmployees) {
                                                                foreach ($subordinateEmployees as $employee) {
                                                                    $employeeLeave = $leaves[$employee["userId"]][$monthDate];
                                                                    // get the employee shift
                                                                    $employeeShift = $shifts[$employee["userId"]]["dates"][$monthDate];
                                                                    $bgColor = $shifts[$employee["userId"]]["jobColor"] ?? "";
                                                                    //
                                                                    $available = true;
                                                                    $conflict = false;
                                                                    $conflictText = '';
                                                                    $unavailableHighlightStyle = '';
                                                                    $unavailableHighlightText = '';
                                                                    $unavailableTime = [];
                                                                    $unavailableText = '';
                                                                    //
                                                                    if ($unavailability) {
                                                                        foreach ($unavailability[$employee["userId"]]['unavailableDates'] as $ukey => $unavailable) {
                                                                            if ($unavailable['date'] == $monthDate) {
                                                                                //
                                                                                $unavailableHighlightStyle = 'bg-danger';
                                                                                $unavailableHighlightText = $unavailable['status'];
                                                                                $available = false;
                                                                                //
                                                                                $unavailableText = remakeEmployeeName($employee, true, true);
                                                                                if ($unavailableHighlightText == "Partial Day") {
                                                                                    $unavailableText .= ' is currently unavailable for a portion of the day.';
                                                                                } elseif ($unavailableHighlightText == "Full Day") {
                                                                                    $unavailableText .= ' is unavailable for the entire day.';
                                                                                }
                                                                                //
                                                                                $unavailableText .= '<br><strong>Reason:</strong> ';
                                                                                $unavailableText .= !empty($unavailable['note']) ? $unavailable['note'] : 'N/A';
                                                                                if ($unavailable['time']) {
                                                                                    //
                                                                                    $unavailableText .= '<br><strong>Unavailable slots are:</strong> ';
                                                                                    $unavailable['time'] = array_values($unavailable['time']);
                                                                                    foreach ($unavailable['time'] as $slotKey => $partial) {
                                                                                        //
                                                                                        $unavailableText .= '<br><strong>' . ($slotKey + 1) . '</strong>) ' . $partial['startTime'] . ' - ' . $partial['emdTime'];
                                                                                        //
                                                                                    }
                                                                                }
                                                                                //
                                                                                if ($employeeShift) {
                                                                                    //
                                                                                    if ($unavailable['time']) {
                                                                                        //
                                                                                        $unavailableHours = 0;
                                                                                        $unavailableTime = $unavailable['time'];
                                                                                        //
                                                                                        foreach ($unavailableTime as $partial) {
                                                                                            $time1 = strtotime($partial['startTime']);
                                                                                            $time2 = strtotime($partial['emdTime']);
                                                                                            //
                                                                                            $unavailableHours = $unavailableHours + round(abs($time2 - $time1) / 3600, 2);
                                                                                            //
                                                                                            $time3 = strtotime($employeeShift['start_time']);
                                                                                            $time4 = strtotime($employeeShift['end_time']);
                                                                                            //
                                                                                            if ($time1 > $time3 && $time1 < $time4 || $time2 > $time3 && $time2 < $time4) {
                                                                                                $conflict = true;
                                                                                            }
                                                                                        }
                                                                                        //
                                                                                        $shiftHours = round($employeeShift['totalTime'] / 3600, 2);
                                                                                        $breakHours = round($employeeShift['breakTime'] / 3600, 2);
                                                                                        //
                                                                                        if ((($shiftHours + $unavailableHours) - $breakHours) > 8) {
                                                                                            $conflict = true;
                                                                                        }
                                                                                    } else {
                                                                                        $conflict = true;
                                                                                    }
                                                                                    //
                                                                                    if ($conflict) {
                                                                                        $conflictText = remakeEmployeeName($employee, true, true);
                                                                                        if ($unavailableHighlightText == "Partial Day") {
                                                                                            $conflictText .= ' is currently unavailable for a portion of the day.';
                                                                                        } elseif ($unavailableHighlightText == "Full Day") {
                                                                                            $conflictText .= ' is unavailable for the entire day.';
                                                                                        }
                                                                                    }
                                                                                    //
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                            ?>
                                                                    <?php if (!$available) { ?>
                                                                        <div style="cursor: pointer" data-container="body" data-toggle="cpopover" data-placement="left" data-title="Unavailability" data-content="<?= $unavailableText ?>">

                                                                        <?php } ?>
                                                                        <div class="schedule-column  schedule-column-<?= $employee["userId"]; ?> text-center <?= $available ? $highlightStyle : $unavailableHighlightStyle; ?>" data-eid="<?= $employee["userId"]; ?>">

                                                                            <?php if ($employeeLeave) { ?>
                                                                                <div class="schedule-dayoff text-primary text-small">
                                                                                    <strong>
                                                                                        <?= $employeeLeave["title"]; ?>
                                                                                    </strong>
                                                                                </div>
                                                                            <?php } elseif ($employeeShift) {
                                                                                $totalHoursInSeconds += $employeeShift["totalTime"];
                                                                            ?>
                                                                                <div class="schedule-item" data-id="<?= $employeeShift["sid"]; ?>" style="background: <?= $bgColor; ?>" title="<?= $employee["job_title"]; ?>" placement="top">
                                                                                    <?php if ($employeeShift["job_sites"] && $employeeShift["job_sites"][0]) { ?>
                                                                                        <span class="circle circle-orange"></span>
                                                                                    <?php } ?>
                                                                                    <p class="text-small">
                                                                                        <?php if ($conflict) { ?>
                                                                                            <span class="conflict-label" style="cursor: pointer" data-container="body" data-toggle="cpopover" data-placement="top" data-title="Shift Conflict" data-content="<?= $conflictText ?>">
                                                                                                <i class="fa fa-exclamation-triangle start_animation" aria-hidden="true"></i>
                                                                                            </span>
                                                                                        <?php } ?>
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
                                                                            <?php } ?>
                                                                        </div>
                                                                        <?php if (!$available) { ?>
                                                                        </div>
                                                                    <?php } ?>
                                                            <?php
                                                                }
                                                            }
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
                                <?php } else { ?>

                                    <div class="schedule-container myt-10">
                                        <div class="row">
                                            <div class="col-sm-12 text-center" style="padding-right: 0">
                                                <span class="schedule-navigator-text jsWeekDaySelect" style="display: none;">
                                                    <?= formatDateToDB($startDate, SITE_DATE,  "M d, y"); ?> -
                                                    <?= formatDateToDB($endDate, SITE_DATE, "M d, y"); ?>
                                                </span>
                                                <span> <b>Shifts Not Found</b> </span><br><br>
                                            </div>
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