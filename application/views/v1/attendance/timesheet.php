<?php
$timeSheetName = "";
?>
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
                            Select Employee
                            <strong class="text-danger">*</strong>
                        </label>
                        <select name="employees" class="form-control">
                            <option value="0"></option>
                            <?php if ($employees) {
                                foreach ($employees as $v0) {

                                    if ($v0["userId"] == $filter["employeeId"]) {
                                        $timeSheetName = remakeEmployeeName($v0);
                                    }
                            ?>
                                    <option value="<?= $v0["userId"]; ?>" <?= $v0["userId"] == $filter["employeeId"] ? "selected" : ""; ?>><?= remakeEmployeeName($v0); ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>
                            Select Year
                            <strong class="text-danger">*</strong>
                        </label>
                        <select name="year" class="form-control">
                            <option <?= $filter["year"] === "2023" ? "selected" : ""; ?> value="2023">2023</option>
                            <option <?= $filter["year"] === "2024" ? "selected" : ""; ?> value="2024">2024</option>
                            <option <?= $filter["year"] === "2025" ? "selected" : ""; ?> value="2025">2025</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>
                            Select Month
                            <strong class="text-danger">*</strong>
                        </label>
                        <select name="month" class="form-control">
                            <option <?= $filter["month"] === "01" ? "selected" : ""; ?> value="01">January</option>
                            <option <?= $filter["month"] === "02" ? "selected" : ""; ?> value="02">February</option>
                            <option <?= $filter["month"] === "03" ? "selected" : ""; ?> value="03">March</option>
                            <option <?= $filter["month"] === "04" ? "selected" : ""; ?> value="04">April</option>
                            <option <?= $filter["month"] === "05" ? "selected" : ""; ?> value="05">May</option>
                            <option <?= $filter["month"] === "06" ? "selected" : ""; ?> value="06">June</option>
                            <option <?= $filter["month"] === "07" ? "selected" : ""; ?> value="07">July</option>
                            <option <?= $filter["month"] === "08" ? "selected" : ""; ?> value="08">August</option>
                            <option <?= $filter["month"] === "09" ? "selected" : ""; ?> value="09">September</option>
                            <option <?= $filter["month"] === "10" ? "selected" : ""; ?> value="10">October</option>
                            <option <?= $filter["month"] === "11" ? "selected" : ""; ?> value="11">November</option>
                            <option <?= $filter["month"] === "12" ? "selected" : ""; ?> value="12">December</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 text-right">
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
        </form>
    </div>
</div>

<?php if ($filter["employeeId"]) { ?>

    <!-- data -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="text-large">
                <strong>
                    <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                    &nbsp;Time Sheet <?= $records ? " of " . $timeSheetName : ""; ?>
                </strong>
                <p class="mt-5">
                    <?= formatDateToDB(
                        $filter["startDate"],
                        DB_DATE,
                        DATE
                    ); ?>
                    -
                    <?= formatDateToDB(
                        $filter["endDate"],
                        DB_DATE,
                        DATE
                    ); ?>
                </p>
            </h2>
        </div>


        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12 text-right">
                    <?php if ($records) { ?>
                        <button class="btn btn-green jsApproveTimeSheet">
                            Approve
                        </button>

                        <button class="btn btn-red jsUnApproveTimeSheet">
                            UnApproved
                        </button>
                    <?php } ?>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col" class="bg-black">
                                <label class="control control--checkbox">
                                    <input type="checkbox" name="select_all" class="jsSelectAll" />
                                    <div class="control__indicator"></div>
                                </label>
                            </th>

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
                            <th scope="col" class="bg-black">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $datesPool = getDatesBetweenDates($filter["startDate"], $filter["endDate"]);
                        $totalWorkedTime =
                            $totalBreakTime =
                            $totalOvertime = 0;
                        //
                        foreach ($datesPool as $v0) {
                            $attendance = $records[$v0["date"]] ?? [];
                            $leave = $leaves && $leaves[$v0["date"]] ? $leaves[$v0["date"]] :  [];

                            if ($attendance) {
                                $totalWorkedTime += $attendance["worked_time"];
                                $totalBreakTime += $attendance["breaks"];
                                $totalOvertime += $attendance["overtime"];
                            }
                        ?>
                            <tr data-date="<?= $v0["date"]; ?>" data-id="<?= $attendance ? $attendance["sid"] : "0"; ?>">
                                <td class="csVerticalAlignMiddle mh-100">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="individualSelect" class="<?= $attendance ? "jsSingleSelect" : ""; ?> " <?= !$leave && $attendance ? 'value="' . $attendance["sid"] . '"' : "disabled"; ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </td>
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
                                        )  : "Missing"; ?>
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
                                    <td class="csVerticalAlignMiddle mh-100 text-<?= $attendance["is_approved"] ? "green" : "red"; ?>">
                                        <strong>

                                            <?= $attendance["is_approved"] ? "APPROVED" : "UNAPPROVED"; ?>
                                        </strong>
                                    </td>
                                    <td class="csVerticalAlignMiddle mh-100">
                                        <?php if ($attendance) { ?>
                                            <button class="btn btn-blue jsViewTimeSheet">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                &nbsp;
                                                History
                                            </button>
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
                            <th scope="col" class="bg-black"></th>
                            <th scope="col" class="bg-black"></th>
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
                            <th scope="col" class="bg-black"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

<?php } ?>