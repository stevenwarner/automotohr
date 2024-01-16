<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <!-- page header -->
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                </span>
                            </div>
                            <!-- main buttons area -->
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a class="btn btn-black" href="<?= $return_title_heading_link; ?>">
                                        <i class="fa fa-arrow-left"></i>
                                        <?= $return_title_heading; ?>
                                    </a>
                                </div>
                            </div>
                            <hr />
                            <!-- main content area -->
                            <div class="row">
                                <!-- pay schedule -->
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-calendar text-orange" aria-hidden="true"></i>
                                                Pay schedule
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <span class="text-small">Name</span>
                                                <br />
                                                <strong><?= GetVal($paySchedule["custom_name"]); ?></strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Pay frequency</span>
                                                <br />
                                                <strong><?= GetVal($paySchedule["frequency"]); ?></strong>
                                            </p>
                                            <?php if(in_array($paySchedule["frequency"], ["monthly"])) {?>
                                            <?php if ($paySchedule["day_1"] && $paySchedule["day_2"]) { ?>
                                                <p class="text-medium">
                                                    <span class="text-small">First day of payment</span>
                                                    <br />
                                                    <strong>"<?= GetVal($paySchedule["day_1"]); ?>" of the month</strong>
                                                </p>
                                                <p class="text-medium">
                                                    <span class="text-small">Second day of payment</span>
                                                    <br />
                                                    <strong>"<?= GetVal($paySchedule["day_2"]); ?>" of the month</strong>
                                                </p>
                                            <?php } ?>
                                            <?php }?>
                                            <p class="text-medium">
                                                <span class="text-small">Pay period start date</span>
                                                <br />
                                                <strong><?= $paySchedule["anchor_pay_date"] ? formatDateToDB($paySchedule["anchor_pay_date"], DB_DATE, DATE) : "Not specified"; ?></strong>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow jsEditPaySchedule">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit Pay schedule
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- wage -->
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-money text-orange" aria-hidden="true"></i>
                                                Job & wage
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <span class="text-small">Position</span>
                                                <br />
                                                <strong>
                                                    <?php echo $jobWageData['title']; ?>
                                                </strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Employment type</span>
                                                <br />
                                                <strong>
                                                    <?php
                                                    if ($jobWageData['employmentType'] == 'fulltime') {
                                                        echo "Full Time";
                                                    } else if ($jobWageData['employmentType'] == 'parttime') {
                                                        echo "Part Time";
                                                    } else if ($jobWageData['employmentType'] == 'contractual') {
                                                        echo "Contractual";
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    ?>
                                                </strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Hire date</span>
                                                <br />
                                                <strong>
                                                    <?php echo $jobWageData['hireDate']; ?>
                                                </strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Wage</span>
                                                <br />
                                                <strong>$<?php echo $jobWageData['rate']; ?> /<?php echo $jobWageData['paymentUnit']; ?></strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Overtime Rule</span>
                                                <br />
                                                <strong>
                                                    <?php echo $jobWageData['overtimeRule']; ?>
                                                </strong>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow jsEditJobWage">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit wage
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- time & attendance -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h2 class="text-large">
                                                <strong>
                                                    <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                                                    &nbsp;Time Sheet <?= $records ? " of " . $timeSheetName : ""; ?>
                                                </strong>
                                                <p class="mt-5">
                                                    <?= formatDateToDB(
                                                        $startDate,
                                                        DB_DATE,
                                                        DATE
                                                    ); ?>
                                                    -
                                                    <?= formatDateToDB(
                                                        $endDate,
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
                                                        $datesPool = getDatesBetweenDates($startDate, $endDate);
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<script>
    const profileUserInfo = {
        userId: <?= $userId; ?>,
        userType: "<?= $userType; ?>",
        nameWithRole: "<?= remakeEmployeeName($employer); ?>"
    };
</script>