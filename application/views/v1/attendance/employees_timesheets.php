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
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 custom-col">
                    <div class="form-group">
                        <label>
                            Department
                        </label>
                        <select name="department" class="form-control">
                            <?php if ($departments) { ?>
                                <option value="all" <?php echo $filter["departments"] == "all" ? 'selected="selected"' : ''; ?>>All</option>
                                <?php foreach ($departments as $department) { ?>
                                    <option value="<?php echo $department['sid']; ?>" <?php echo isset($filter["departments"]) && $filter["departments"] ==  $department['sid'] ? 'selected="selected"' : ''; ?>><?php echo $department['name']; ?></option>
                                <?php } ?>
                            <?php } else { ?>
                                <option value="0">No Department Found</option>
                            <?php } ?>    
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 custom-col">
                    <div class="form-group">
                        <label>
                            Team
                        </label>
                        <select name="teams[]" class="form-control multipleSelect" multiple>
                            <?php if ($teams) { ?>
                                <option value="all" <?php echo in_array("all", $filter["teams"]) ? 'selected="selected"' : ''; ?>>All</option>
                                <?php foreach ($teams as $team) { ?>
                                    <option value="<?php echo $team['sid']; ?>" <?php echo in_array($team['sid'], $filter["teams"]) ? 'selected="selected"' : ''; ?>><?php echo $team['name']; ?></option>
                                <?php } ?>
                            <?php } else { ?>
                                <option value="0">No Team Found</option>
                            <?php } ?>    
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 custom-col">
                    <div class="form-group">
                        <label>
                            Job Title
                        </label>
                        <select name="jobTitle[]" class="form-control multipleSelect" multiple>
                            <?php if ($jobTitles) { ?>
                                <option value="all" <?php echo in_array("all", $filter["jobTitles"]) ? 'selected="selected"' : ''; ?>>All</option>
                                <?php foreach ($jobTitles as $jobTitle) { ?>
                                    <option value="<?php echo $jobTitle['title']; ?>" <?php echo in_array($jobTitle['title'], $filter["jobTitles"]) ? 'selected="selected"' : ''; ?>><?php echo $jobTitle['title']; ?></option>
                                <?php } ?>
                            <?php } else { ?>
                                <option value="0">No Job Title Found</option>
                            <?php } ?> 
                        </select>
                    </div>
                </div>     
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>
                            Employees
                        </label>
                        <select name="employees[]" class="form-control multipleSelect" multiple>
                            <?php if ($employees) { ?>
                                <option value="all" <?php echo in_array("all", $filter["employees"]) ? 'selected="selected"' : ''; ?>>All</option>
                                <?php foreach ($employees as $v0) { ?>
                                    <option value="<?= $v0["userId"]; ?>" <?= in_array($v0["userId"], $filter["employees"]) ? "selected" : ""; ?>><?= remakeEmployeeName($v0); ?></option>
                                <?php } ?>
                            <?php } else { ?>
                                <option value="0">No employee Found</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>
                            Select date range
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" class="form-control jsDateRangePicker" readonly placeholder="MM/DD/YYYY - MM/DD/YYYY" name="date_range" value="<?= $filter["dateRange"] ?? ""; ?>" />
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

<?php if ($employees) { ?>

    <!-- data -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="text-large">
                <strong>
                    <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                    &nbsp;Time Sheet
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
                <table class="table table-bordered table-hover table-striped">
                    <thead style="background-color: #fd7a2a;">
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Clocked Time</th>
                            <th>Worked Time</th>
                            <th>Regular Time</th>
                            <th>Paid Break</th>
                            <th>Unpaid Break</th>
                            <th>Schedule Time</th>
                            <th>Difference</th>
                            <th>Overtime</th>
                            <th>Double Overtime</th>
                            <th>Paid Timeoff</th>
                            <th>Total Wage</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($filterEmployees)) { ?>
                            <tr>
                                <td colspan="10">
                                    <span class="no-data">No Employee Payroll Found</span>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($filterEmployees as $employee) { ?>
                                <tr>
                                    <td>
                                        <?=remakeEmployeeName($employee);?>
                                    </td>
                                    <td>
                                        <?php if ($employee['clockArray']["shift_status"]['approved_count'] > 0 && $employee['clockArray']["shift_status"]['unapproved_count'] > 0) { ?>
                                            <table class="table table-bordered table-condensed table-hover">
                                                <tbody>
                                                    <tr>
                                                        <th class="col-xs-8 text-success">Approved Shifts</th>
                                                        <td class="col-xs-4"><?= $employee['clockArray']["shift_status"]['approved_count']." Shift(s)"; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-8 text-danger">Unapproved Shifts</th>
                                                        <td class="col-xs-4"><?= $employee['clockArray']["shift_status"]['unapproved_count']." Shift(s)"; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <?php } else if ($employee['clockArray']["shift_status"]['approved_count'] == 0 && $employee['clockArray']["shift_status"]['total_shifts'] > 0) { ?>
                                            <table class="table table-bordered table-condensed table-hover">
                                                <tbody>
                                                    <tr>
                                                        <th class="col-xs-8 text-danger">Unapproved Shifts</th>
                                                        <td class="col-xs-4"><?= $employee['clockArray']["shift_status"]['unapproved_count']." Shift(s)"; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>    
                                        <?php } else if ($employee['clockArray']["shift_status"]['unapproved_count'] == 0 && $employee['clockArray']["shift_status"]['total_shifts'] > 0) { ?>  
                                            <table class="table table-bordered table-condensed table-hover">
                                                <tbody>
                                                    <tr>
                                                        <th class="col-xs-8 text-success">Approved Shifts</th>
                                                        <td class="col-xs-4"><?= $employee['clockArray']["shift_status"]['approved_count']." Shift(s)"; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>    
                                        <?php } else { ?> 
                                            <p>No shift found.</p> 
                                        <?php } ?>           
                                    </td>
                                    <td>
                                        <?= $employee['clockArray'] ? $employee['clockArray']['text']['clocked_time'] : "0h"; ?>           
                                    </td>
                                    <td>
                                        <?= $employee['clockArray'] ? $employee['clockArray']['text']['worked_time'] : "0h"; ?>            
                                    </td>
                                    <td>
                                        <table class="table table-bordered table-condensed table-hover">
                                            <tbody>
                                                <tr>
                                                    <th class="col-xs-6">Hours</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? $employee['clockArray']['text']['worked_time'] : "0h"; ?></td>
                                                </tr> 
                                                <tr>
                                                    <th class="col-xs-6">Rate</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? "$".$employee['clockArray']['normal_rate'] : '$0'; ?></td>
                                                </tr> 
                                                <tr>
                                                    <th class="col-xs-6">Wage</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? getWageFromTime($employee['clockArray']['regular_time'], $employee['clockArray']['normal_rate']) : "$0"; ?></td>
                                                </tr> 
                                            </tbody>
                                        </table>               
                                    </td>
                                    <td>
                                        <?= $employee['clockArray'] ? $employee['clockArray']['text']['paid_break_time'] : "0h"; ?>              
                                    </td>
                                    <td>
                                        <?= $employee['clockArray'] ? $employee['clockArray']['text']['unpaid_break_time'] : "0h"; ?>              
                                    </td>
                                    <td>
                                        <?= $employee['clockArray'] ? $employee['clockArray']['text']['schedule_time'] : "0h"; ?>              
                                    </td>
                                    <td>
                                        <?= $employee['clockArray'] ? $employee['clockArray']['text']['difference_time'] : "0h"; ?>              
                                    </td>
                                    <td>
                                        <table class="table table-bordered table-condensed table-hover">
                                            <tbody>
                                                <tr>
                                                    <th class="col-xs-6">Hours</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? $employee['clockArray']['text']['overtime'] : "0h"; ?></td>
                                                </tr> 
                                                <tr>
                                                    <th class="col-xs-6">Rate</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? "$".$employee['clockArray']['over_time_rate'] : '$0'; ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="col-xs-6">Wage</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? getWageFromTime($employee['clockArray']['overtime'], $employee['clockArray']['over_time_rate']) : "$0"; ?></td>
                                                </tr> 
                                            </tbody>
                                        </table>               
                                    </td>
                                    <td>
                                        <table class="table table-bordered table-condensed table-hover">
                                            <tbody>
                                                <tr>
                                                    <th class="col-xs-6">Hours</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? $employee['clockArray']['text']['double_overtime'] : "0h"; ?></td>
                                                </tr> 
                                                <tr>
                                                    <th class="col-xs-6">Rate</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? "$".$employee['clockArray']['double_over_time_rate'] : '$0'; ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="col-xs-6">Wage</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? getWageFromTime($employee['clockArray']['double_overtime'], $employee['clockArray']['double_over_time_rate']) : "$0"; ?></td>
                                                </tr> 
                                            </tbody>
                                        </table>               
                                    </td>
                                    <td>
                                        <table class="table table-bordered table-condensed table-hover">
                                            <tbody>
                                                <tr>
                                                    <th class="col-xs-6">Days</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? $employee['clockArray']['paid_time_off']['total_hours']."h" : "0h"; ?></td>
                                                </tr> 
                                                <tr>
                                                    <th class="col-xs-6">Rate</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? "$".$employee['clockArray']['normal_rate'] : '$0'; ?></td>
                                                </tr> 
                                                <tr>
                                                    <th class="col-xs-6">Wage</th>
                                                    <td class="col-xs-6"><?= $employee['clockArray'] ? getWageFromTime(($employee['clockArray']['paid_time_off']['total_hours'] * 60 * 60), $employee['clockArray']['normal_rate']) : "$0"; ?></td>
                                                </tr> 
                                            </tbody>
                                        </table>               
                                    </td>
                                    <td>
                                        <?= $employee['clockArray'] ? getTotalWageFromTime($employee['clockArray'], 'all') : "$0"; ?>
                                    </td>
                                    <td>
                                        <?php if ($employee['clockArray']["shift_status"]['total_shifts'] != 0) { ?>
                                            <a class="btn btn-orange" target="_blank" href="<?php echo base_url("attendance/timesheet")."?employees=".$employee['sid']."&date_range=".$filter["dateRange"]; ?>">
                                                <i class="fa fa-eye"></i>
                                                View Detail
                                            </a>
                                        <?php } ?>    
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php } ?>