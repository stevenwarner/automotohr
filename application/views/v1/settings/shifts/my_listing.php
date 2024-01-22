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
                            <a href="<?= base_url("settings/subordinateshifts/manage"); ?>" class="btn btn-orange">
                                &nbsp;My Subordinate
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
                                    My Scheduling
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
                                        if ($filter_team != '') {
                                            $filterEmployeesSid = implode(',', $filter_employees);
                                            $filterJobtitle = implode(',', $filter_jobtitle);

                                            $filterFields = '&employees=' . $filterEmployeesSid . '&team=' . $filter_team . '&jobtitle=' . $filterJobtitle;
                                        }

                                        ?>
                                        <a href="<?= base_url("settings/myshifts/manage?mode=week" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Week
                                        </a>
                                        <a href="<?= base_url("settings/myshifts/manage?mode=two_week" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "two_week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            2 Week
                                        </a>
                                        <a href="<?= base_url("settings/myshifts/manage?mode=month" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "month" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Month
                                        </a>
                                    </div>

                                    <div class="col-sm-4 text-right">

                                        <button class="btn btn-blue expander">
                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                            Filters
                                        </button>
                                    </div>
                                </div>

                                <div class="row" id="TableData">
                                    <br>

                                    <div class="col-sm-2">
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="text-medium">
                                            From Date
                                        </label>
                                        <input type="text" class="form-control datepicker hasDatepicker" name="shift_date_from" id="shift_date_from"  autocomplete="off"/>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="text-medium">
                                            To Date
                                        </label>
                                        <input type="text" class="form-control " name="shift_date_to" id="shift_date_to" autocomplete="off"/>
                                    </div>

                                    <div class="col-sm-2 text-right">
                                        <span class="pull-right">
                                            <br>
                                            <button id="btn_apply" type="button" class="btn btn-orange js-apply-my-filter-btn">APPLY</button>
                                            <button id="btn_reset" type="button" class="btn btn-black btn-theme js-reset-my-filter-btn">RESET</button>
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

                                <?php if ($employees) { ?>
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
                                                        <?php
                                                        $employeeLeave = $leaves[$employee["userId"]][$monthDate];
                                                        ?>
                                                        <!-- column-->
                                                        <div class="schedule-column-container" data-date="<?= $monthDate; ?>">

                                                            <?php
                                                            $curentDate = date('Y-m-d');
                                                            $todatDate = strtotime($curentDate);
                                                            $cDate = strtotime($monthDate);
                                                            ?>
                                                            <div class="schedule-column-header text-center" <?php echo $todatDate == $cDate ? ' style=background-color:#e5e0e0;color:#c10;font-weight:900;font-size:20px;' : '' ?>>
                                                                <?= formatDateToDB($monthDate, DB_DATE, "D d"); ?>
                                                                </p>
                                                            </div>
                                                            <?php if ($employees) {
                                                                foreach ($employees as $employee) {
                                                                    // get the employee shift
                                                                    $employeeShift = $shifts[$employee["userId"]]["dates"][$monthDate];
                                                            ?>
                                                                    <div class="schedule-column  schedule-column-<?= $employee["userId"]; ?> text-center" data-eid="<?= $employee["userId"]; ?>" <?php echo $todatDate == $cDate ? ' style=background-color:#e5e0e0;font-weight:900;font-size:20px;' : '' ?>>
                                                                        <?php if ($employeeLeave) { ?>
                                                                            <div class="schedule-dayoff text-primary text-small">
                                                                                <strong>
                                                                                    <?= $employeeLeave["title"]; ?>
                                                                                </strong>
                                                                            </div>
                                                                        <?php } elseif ($employeeShift) {
                                                                            $totalHoursInSeconds += $employeeShift["totalTime"];
                                                                        ?>
                                                                            <div class="schedule-item" data-id="<?= $employeeShift["sid"]; ?>">
                                                                                <?php if ($employeeShift["job_sites"] && $employeeShift["job_sites"][0]) { ?>
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
                                                                            <button class="btn btn-red text-small btn-xs">
                                                                                Day Off
                                                                            </button>
                                                                        <?php } ?>
                                                                    </div>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                            <div class="schedule-footer schedule-border" <?php echo $todatDate == $cDate ? ' style=background-color:#e5e0e0;font-weight:900;font-size:20px;' : '' ?>>
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
<script>
    let filterTeam = '<?php echo $filter_team; ?>'
    let filterEmployees = '<?php echo $filter_employees; ?>'
    let filterToggle = '<?php echo $filter_toggle; ?>'
    let filterEmployeesSid = '<?php echo implode(',', $filter_employees); ?>'
    let filterJobtitle = '<?php echo implode(',', $filter_jobtitle); ?>'

    let filterEndDate = '<?php echo $filterEndDate; ?>'
    let filterStartDate = '<?php echo $filterStartDate; ?>'

</script>