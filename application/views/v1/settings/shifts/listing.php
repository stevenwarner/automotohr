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
                                        <button class="btn btn-orange jsEmployeesShifts">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            Create
                                        </button>
                                        <button class="btn btn-red jsEmployeeShiftsDelete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                            Delete
                                        </button>
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
                                        <a href="<?= base_url("settings/shifts/manage?mode=week" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Week
                                        </a>
                                        <a href="<?= base_url("settings/shifts/manage?mode=two_week" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "two_week" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            2 Week
                                        </a>
                                        <a href="<?= base_url("settings/shifts/manage?mode=month" . $filterFields); ?>" class="btn btn-orange <?= $filter["mode"] === "month" ? "disabled" : ""; ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            Month
                                        </a>
                                    </div>

                                    <div class="col-sm-4 text-right">
                                        <button class="btn btn-orange jsEmployeeShiftsCopy" data-type="month">
                                            <i class="fa fa-exchange" aria-hidden="true"></i>
                                            Copy shifts
                                        </button>
                                        <button class="btn btn-blue expander">
                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                            Filters
                                        </button>
                                    </div>
                                </div>

                                <div class="row" id="TableData" style="display: none;">
                                    <br>

                                    <div class="col-sm-3">
                                        <label>Employees</label>
                                        <select id="js-filter-employee_ooo" class="js-filter-employee" multiple="multiple">
                                            <option value="all">All</option>
                                            <?php foreach ($allemployees as $empRow) { ?>
                                                <option value="<?php echo $empRow['userId']; ?>" <?= in_array($empRow["userId"], $filter_employees) ? "selected" : ""; ?>>
                                                    <?= remakeEmployeeName($empRow); ?>
                                                </option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Departments / Teams</label>
                                        <br>
                                        <?= get_company_departments_teams_dropdown($company_sid, 'teamId', $filter_team ?? 0); ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Job Title</label>
                                        <br>
                                        <?= get_jobTitle_dropdown_for_search($company_sid, 'jobtitleId') ?>
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        <span class="pull-right">
                                            <br>
                                            <button id="btn_apply" type="button" class="btn btn-orange js-apply-filter-btn">APPLY</button>
                                            <button id="btn_reset" type="button" class="btn btn-black btn-theme js-reset-filter-btn">RESET</button>
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
                                                                <a href="<?= base_url("employee_profile/" . $employee["userId"]); ?>">
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
                                                                </a>
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
                                                                    <div 
                                                                        style="cursor: pointer" 
                                                                        data-container="body" 
                                                                        data-toggle="cpopover" 
                                                                        data-placement="left" 
                                                                        data-title="Unavailability" 
                                                                        data-content="<?= $unavailableText ?>">
                                                                            
                                                                <?php } ?>
                                                                    <div class="schedule-column <?= $employeeLeave || !$available ? "" : "schedule-column-clickable"; ?> schedule-column-<?= $employee["userId"]; ?> text-center <?= !$available ? $unavailableHighlightStyle : ''; ?>" data-eid="<?= $employee["userId"]; ?>" <?php echo $todatDate == $cDate ? ' style=background-color:#e5e0e0;font-weight:900;font-size:20px;' : '' ?>>
                                                                       
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
                                                                                        <span 
                                                                                            class="conflict-label" 
                                                                                            style="cursor: pointer" 
                                                                                            data-container="body" 
                                                                                            data-toggle="cpopover" 
                                                                                            data-placement="left" 
                                                                                            data-title="Shift Conflict" 
                                                                                            data-content="<?= $conflictText ?>"
                                                                                            >
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
                                                                        <?php } else { ?>

                                                                        <?php } ?>
                                                                    </div>
                                                                <?php if (!$available) { ?>
                                                                    </div>
                                                                <?php } ?>    
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
</script>