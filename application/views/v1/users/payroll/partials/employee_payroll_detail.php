<!-- payroll detail pills -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="attendance_reports_main">
            <li> 
                <div class="attendance_report_item regular_time"> 
                    <div class="attendance_report_details"> 
                        <div class="attendance_report_content">
                            <h3>Regular Time</h3>
                            <p>
                            <?= $clockArray ? $clockArray['text']['regular_time'] : "0h"; ?> <br> <?= $clockArray ? getWageFromTime($clockArray['regular_time'], $clockArray['normal_rate']) : "$0"; ?>
                            </p>
                        </div>
                    </div> 
                </div> 
            </li>
            <!-- <li> 
                <div class="attendance_report_item paid_break_time"> 
                    <div class="attendance_report_details"> 
                        <div class="attendance_report_content">
                            <h3>Paid Break Time</h3>
                            <p>
                                <?= $clockArray ? $clockArray['text']['paid_break_time'] : "0h"; ?> <br> <?= $clockArray ? getWageFromTime($clockArray['paid_break_time'], $clockArray['normal_rate']) : "$0"; ?>
                            </p>
                        </div>
                    </div> 
                </div> 
            </li> -->
            <li> 
                <div class="attendance_report_item over_time"> 
                    <div class="attendance_report_details"> 
                        <div class="attendance_report_content">
                            <h3>Overtime</h3>
                            <p>
                                <?= $clockArray ? $clockArray['text']['overtime'] : "0h"; ?> <br> <?= $clockArray ? getWageFromTime($clockArray['overtime'], $clockArray['over_time_rate']) : "$0"; ?>
                            </p>
                        </div>
                    </div> 
                </div> 
            </li>
            <li> 
                <div class="attendance_report_item double_over_time"> 
                    <div class="attendance_report_details"> 
                        <div class="attendance_report_content">
                            <h3>Double Overtime</h3>
                            <p>
                                <?= $clockArray ? $clockArray['text']['double_overtime'] : "0h"; ?> <br> <?= $clockArray ? getWageFromTime($clockArray['double_overtime'], $clockArray['double_over_time_rate']) : "$0"; ?>
                            </p>
                        </div>
                    </div> 
                </div> 
            </li>
            <li> 
                <div class="attendance_report_item paid_time_off"> 
                    <div class="attendance_report_details"> 
                        <div class="attendance_report_content">
                            <h3>Paid Time Off</h3>
                            <p>
                                <?= $clockArray ? $clockArray['paid_time_off']['total_days']." Day(s)" : "0 Day"; ?>
                                <br> 
                                <?= $clockArray ? getWageFromTime(($clockArray['paid_time_off']['total_hours'] * 60 * 60), $clockArray['normal_rate']) : "$0"; ?>
                            </p>
                        </div>
                    </div> 
                </div> 
            </li>
            <li> 
                <div class="attendance_report_item total_paid"> 
                    <div class="attendance_report_details"> 
                        <div class="attendance_report_content">
                            <h3>Total Amount</h3>
                            <p><?= $clockArray ? getTotalWageFromTime($clockArray, 'all') : "$0"; ?></p>
                        </div>
                    </div> 
                </div> 
            </li>
        </ul>
    </div>
</div>

<hr>

<!-- payroll break down -->
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead style="background-color: #fd7a2a;">
            <tr>
                <th>Items</th>
                <th>Hours</th>
                <th>Rate</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Regular Time</b></td>
                <td><?= $clockArray ? $clockArray['text']['regular_time'] : "0h"; ?></td>
                <td><?= $clockArray ? "$".$clockArray['normal_rate'] : '$0'; ?></td>
                <td><?= $clockArray ? getWageFromTime($clockArray['regular_time'], $clockArray['normal_rate']) : "$0"; ?></td>
            </tr>

            <tr>
                <td><b>Overtime</b></td>
                <td><?= $clockArray ? $clockArray['text']['overtime_detail'] : "0h"; ?></td>
                <td><?= $clockArray ? "$".$clockArray['over_time_rate'] : "$0"; ?></td>
                <td><?= $clockArray ? getWageFromTime($clockArray['overtime'], $clockArray['over_time_rate']) : "$0"; ?></td>

            </tr>

            <tr>
                <td><b>Double Overtime</b></td>
                <td><?= $clockArray ? $clockArray['text']['double_overtime_detail'] : "0h"; ?></td>
                <td><?= $clockArray ? "$".$clockArray['double_over_time_rate'] : "$0"; ?></td>
                <td><?= $clockArray ? getWageFromTime($clockArray['double_overtime'], $clockArray['double_over_time_rate']) : "$0"; ?></td>
            </tr>

            <tr>
                <td><b>Worked Time</b></td>
                <td><?= $clockArray ? getTotalWorkTime($clockArray) : "0h"; ?></td>
                <td></td>
                <td><?= $clockArray ? getTotalWageFromTime($clockArray, 'workTime') : "$0"; ?></td>

            </tr>
            <?php if ($clockArray['paid_time_off']['polices']) { ?>
                <?php foreach ($clockArray['paid_time_off']['polices'] as $policy) { ?>
                    <tr>
                        <td><b><?= $policy['title']; ?></b></td>
                        <td><?= $policy['hours']."h"; ?></td>
                        <td><?= $clockArray ? "$".$clockArray['normal_rate'] : '$0'; ?></td>
                        <td><?= $clockArray ? getWageFromTime(($policy['hours'] * 60 * 60), $clockArray['normal_rate']) : "$0"; ?></td>
                    </tr>
                <?php } ?>  
            <?php } ?>        

            <tr style="background-color: #eeedee;">
                <td><b>Total</b></td>
                <td><b></b></td>
                <td><b></b></td>
                <td><b><?= $clockArray ? getTotalWageFromTime($clockArray, 'all') : "$0"; ?></b></td>
            </tr>

        </tbody>
    </table>
</div>

<hr>

<!-- time & attendance -->
<div>
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
                        Clocked Time
                    </th>
                    <th scope="col" class="bg-black">
                        Worked Time
                    </th>
                    <th scope="col" class="bg-black">
                        Regular Time
                    </th>
                    <th scope="col" class="bg-black">
                        Paid Breaks
                    </th>
                    <th scope="col" class="bg-black">
                        Unpaid Breaks
                    </th>
                    <th scope="col" class="bg-black">
                        Schedule Time
                    </th>
                    <th scope="col" class="bg-black">
                        Difference
                    </th>
                    <th scope="col" class="bg-black">
                        Overtime
                    </th>
                    <th scope="col" class="bg-black">
                        Double Overtime
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
                $datesPool = getDatesBetweenDates($startDate, $endDate);
                $totalWorkedTime =
                    $totalBreakTime =
                    $totalOvertime = 0;
                //
                foreach ($datesPool as $v0) {
                    $attendance = $records[$v0["date"]] ?? [];
                    $processedData = $clockArray['periods'][$v0["date"]] ?? [];
                    $leave = $leaves && $leaves[$v0["date"]] ? $leaves[$v0["date"]] :  [];

                    if ($attendance) {
                        $totalWorkedTime += $attendance["worked_time"];
                        $totalBreakTime += $attendance["breaks"];
                        $totalOvertime += $attendance["overtime"];
                    }
                ?>
                    <tr class="<?=$v0["date"] === getSystemDate("Y-m-d") ? "bg-success" :"";?>" data-date="<?= $v0["date"]; ?>" data-id="<?= $attendance ? $attendance["sid"] : "0"; ?>">
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
                                <?= $processedData ? $processedData['text']['clocked_time'] : "0h"; ?> 
                            </td>
                            <td class="csVerticalAlignMiddle mh-100">
                                <?= $processedData ? $processedData['text']['worked_time'] : "0h"; ?> 
                            </td>
                            <td class="csVerticalAlignMiddle mh-100">
                                <?= $processedData ? $processedData['text']['regular_time'] : "0h"; ?> <br> <?= $processedData ? getWageFromTime($processedData['regular_time'], $clockArray['normal_rate']) : "$0"; ?>
                            </td>
                            <td class="csVerticalAlignMiddle mh-100">
                                <?= $processedData ? $processedData['text']['paid_break_time'] : "0h"; ?>
                            </td>
                            <td class="csVerticalAlignMiddle mh-100">
                                <?= $processedData ? $processedData['text']['unpaid_break_time'] : "0h"; ?>
                            </td>
                            <td class="csVerticalAlignMiddle mh-100">
                                <?= $processedData ? $processedData['text']['schedule_time'] : "0h"; ?>
                            </td>
                            <td class="csVerticalAlignMiddle mh-100">
                                <?= $processedData ? $processedData['text']['difference_time'] : ""; ?>
                            </td>
                            <td class="csVerticalAlignMiddle mh-100">
                                <?= $processedData ? $processedData['text']['overtime'] : "0h"; ?> <br> <?= $processedData ? getWageFromTime($processedData['overtime'], $clockArray['over_time_rate']) : "$0"; ?>
                            </td>
                            <td class="csVerticalAlignMiddle mh-100">
                                <?= $processedData ? $processedData['text']['double_overtime'] : "0h"; ?> <br> <?= $processedData ? getWageFromTime($processedData['double_overtime'], $clockArray['double_over_time_rate']) : "$0"; ?>
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
                            <td class="csVerticalAlignMiddle text-center mh-100" colspan="14">
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
                        <?= $clockArray ? $clockArray['text']['clocked_time'] : "0h"; ?>
                    </th>
                    <th scope="col" class="bg-black">
                        <?= $clockArray ? $clockArray['text']['worked_time'] : "0h"; ?>
                    </th>
                    <th scope="col" class="bg-black">
                        <?= $clockArray ? $clockArray['text']['regular_time'] : "0h"; ?> 
                        <br> 
                        <?= $clockArray ? getWageFromTime($clockArray['regular_time'], $clockArray['normal_rate']) : "$0"; ?>
                    </th>
                    <th scope="col" class="bg-black">
                        <?= $clockArray ? $clockArray['text']['paid_break_time'] : "0h"; ?> 
                        <br> 
                        <!-- <?= $clockArray ? getWageFromTime($clockArray['paid_break_time'], $clockArray['normal_rate']) : "$0"; ?> -->
                    </th>
                    <th scope="col" class="bg-black">
                        <?= $clockArray ? $clockArray['text']['unpaid_break_time'] : "0h"; ?>
                    </th>
                    <th scope="col" class="bg-black">
                        <?= $clockArray ? $clockArray['text']['schedule_time'] : "0h"; ?>
                    </th>
                    <th scope="col" class="bg-black">
                        <?= $clockArray ? $clockArray['text']['difference_time'] : "0h"; ?>
                    </th>
                    <th scope="col" class="bg-black">
                        <?= $clockArray ? $clockArray['text']['overtime'] : "0h"; ?> 
                        <br> 
                        <?= $clockArray ? getWageFromTime($clockArray['overtime'], $clockArray['over_time_rate']) : "$0"; ?>
                    </th>
                    <th scope="col" class="bg-black">
                        <?= $clockArray ? $clockArray['text']['double_overtime'] : "0h"; ?> 
                        <br> 
                        <?= $clockArray ? getWageFromTime($clockArray['double_overtime'], $clockArray['double_over_time_rate']) : "$0"; ?>
                    </th>
                    <th scope="col" class="bg-black"></th>
                    <th scope="col" class="bg-black"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>