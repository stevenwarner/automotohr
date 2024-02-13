<br />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-right">
            <a href="<?= base_url("dashboard"); ?>" class="btn btn-black">
                <i class="fa fa-arrow-left csF16" aria-hidden="true"></i>
                &nbsp;Dashboard
            </a>
            <a href="<?= base_url("attendance/my/overview"); ?>" class="btn btn-orange">
                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                &nbsp;Overview
            </a>
        </div>
    </div>
    <hr />
    <div class="row">
        <!-- Sidebar -->
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-large">
                        <strong>
                            <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                            &nbsp;My Time
                        </strong>
                    </h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="jsAttendanceCurrentClockDateTime text-medium"></div>
                        </div>

                        <div class="col-sm-12 text-center">
                            <p class="csF26 text-center text-xxxl">
                                <span class="jsAttendanceClockHour"></span>
                                <span class="jsAttendanceClockSeparator"></span>
                                <span class="jsAttendanceClockMinute"></span>
                                <span class="jsAttendanceClockSeparator"></span>
                                <span class="jsAttendanceClockSeconds"></span>
                            </p>
                            <div class="jsAttendanceBTNs text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right side -->
        <div class="col-sm-9">
            <!-- Filter -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-large">
                        <strong>
                            <i class="fa fa-filter text-orange" aria-hidden="true"></i>
                            &nbsp;Filter
                        </strong>
                    </h2>
                </div>
                <div class="panel-body">
                    <form action="<?= current_url(); ?>" method="get">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>
                                        Select date range
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <input type="text" class="form-control jsDateRangePicker" readonly placeholder="MM/DD/YYYY - MM/DD/YYYY" name="date_range" value="<?= $filter["dateRange"] ?? ""; ?>" />
                                </div>
                            </div>

                            <div class="col-sm-6 text-right">
                                <div class="form-group">
                                    <br>
                                    <button class="btn btn-orange">
                                        <i class="fa fa-search"></i>
                                        Apply filter
                                    </button>
                                    <a class="btn btn-black" href="<?= current_url(); ?>">
                                        <i class="fa fa-times-circle"></i>
                                        Clear filter
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- data -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-large">
                        <strong>
                            <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                            &nbsp;My Time Sheet
                        </strong>
                        <p class="mt-5">
                            <?= formatDateToDB(
                                $filter["startDateDB"],
                                DB_DATE,
                                DATE
                            ); ?>
                            -
                            <?= formatDateToDB(
                                $filter["endDateDB"],
                                DB_DATE,
                                DATE
                            ); ?>
                        </p>
                    </h2>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table ">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th scope="col" class="bg-black">
                                        Date
                                    </th>

                                    <th scope="col" class="bg-black">
                                        Period
                                    </th>
                                    <th scope="col" class="bg-black">
                                        Worked Time
                                    </th>
                                    <th scope="col" class="bg-black">
                                        Breaks
                                    </th>
                                    <th scope="col" class="bg-black">
                                        Overtime
                                    </th>
                                    <th scope="col" class="bg-black">
                                        Status
                                    </th>
                                    <?php if ($isEditAllowed) { ?>
                                        <th scope="col" class="bg-black">
                                            Actions
                                        </th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $datesPool = getDatesBetweenDates($filter["startDateDB"], $filter["endDateDB"]);
                                $totalWorkedTime =
                                    $totalBreakTime =
                                    $totalOvertime = 0;
                                //
                                $employeeTodayDate = getSystemDateInLoggedInPersonTZ(DB_DATE);
                                //
                                foreach ($datesPool as $v0) {
                                    $attendance = $records[$v0["date"]] ?? [];
                                    $leave = $leaves[$v0["date"]] ?? [];

                                    if ($attendance) {
                                        $totalWorkedTime += $attendance["worked_time"];
                                        $totalBreakTime += $attendance["breaks"];
                                        $totalOvertime += $attendance["overtime"];
                                    }
                                ?>
                                    <tr class="<?= $v0["date"] === $employeeTodayDate ? "bg-success" : ""; ?>" data-date="<?= $v0["date"]; ?>" data-id="<?= $attendance ? $attendance["sid"] : "0"; ?>">
                                        <td class="csVerticalAlignMiddle mh-100">
                                            <?= formatDateToDB($v0["date"], DB_DATE, DATE); ?>
                                        </td>
                                        <?php if (!$leave) { ?>
                                            <td class="csVerticalAlignMiddle mh-100">
                                                <?= $attendance && $attendance["clocked_in"] ?
                                                    formatDateToDB(
                                                        $attendance["clocked_in"],
                                                        DB_DATE_WITH_TIME,
                                                        "h:i a"
                                                    ) : "Missing"; ?>
                                                -
                                                <?= $attendance && $attendance["clocked_out"] ? formatDateToDB(
                                                    $attendance["clocked_out"],
                                                    DB_DATE_WITH_TIME,
                                                    "h:i a"
                                                ) : "Missing"; ?>
                                            </td>
                                            <td class="csVerticalAlignMiddle mh-100">
                                                <?= $attendance ? convertSecondsToTime($attendance["worked_time"]) : "0h"; ?>
                                            </td>
                                            <td class="csVerticalAlignMiddle mh-100">
                                                <?= $attendance ? convertSecondsToTime($attendance["breaks"]) : "0h"; ?>
                                            </td>
                                            <td class="csVerticalAlignMiddle mh-100">
                                                <?= $attendance ? convertSecondsToTime($attendance["overtime"]) : "0h"; ?>
                                            </td>
                                            <td class="csVerticalAlignMiddle mh-100 text-<?= $attendance && $attendance["is_approved"] ? "green" : "red"; ?>">
                                                <strong>
                                                    <?= $attendance && $attendance["is_approved"] ? "APPROVED" : "PENDING"; ?>
                                                </strong>
                                            </td>
                                            <?php if ($isEditAllowed) { ?>
                                                <td class="csVerticalAlignMiddle mh-100">
                                                    <?php if ($attendance) { ?>
                                                        <?php if ($attendance["is_approved"] == 0) { ?>
                                                            <button class="btn btn-orange jsEditTimeSheet">
                                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                                &nbsp;
                                                                Edit
                                                            </button>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <button class="btn btn-orange jsAddTimeSheet">
                                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                            &nbsp;
                                                            Add
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <td class="csVerticalAlignMiddle text-center mh-100" colspan="6">
                                                <strong class="text-primary">
                                                    Time off - <?= $leave["title"]; ?>
                                                </strong>
                                                <?php if ($leave["reason"]) { ?>
                                                    <p><?= $leave["reason"] ?></p>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope=" col" class="bg-black"></th>
                                    <th scope="col" class="bg-black"></th>
                                    <th scope="col" class="bg-black">
                                        <?= convertSecondsToTime($totalWorkedTime); ?>
                                    </th>
                                    <th scope="col" class="bg-black">
                                        <?= convertSecondsToTime($totalBreakTime); ?>
                                    </th>
                                    <th scope="col" class="bg-black">
                                        <?= convertSecondsToTime($totalOvertime); ?>
                                    </th>
                                    <th scope="col" class="bg-black"></th>
                                    <?php if ($isEditAllowed) { ?>
                                        <th scope="col" class="bg-black"></th>
                                    <?php } ?>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>