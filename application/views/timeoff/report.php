<?php $this->load->view('timeoff/includes/header'); ?>
<style>
    .form-group label {
        font-size: 16px !important;
    }

    table.table thead tr th {
        font-size: 16px !important;

    }

    table.table tbody tr td,
    table.table tbody tr td a.btn {
        font-size: 14px !important;
    }

    .timeoff_count {
        cursor: pointer;
        text-decoration: underline;
        color: #3554DC;
    }

    /*  .timeoff_count:hover{
        border-bottom: 2px solid #00BFFF;
    }*/

    .td_setting {
        vertical-align: middle !important;
    }

    .subheader {
        background-color: #444444;
        color: #fff;
    }

    .subheader th {
        font-size: 16px !important;
    }
</style>

<?php
$jobTitles = [];
$employees = '';
$departments = '';
$teams = '';

$policy = '';
//
$departmentArray = $teamArray = [];
//
if (!empty($company_employees)) {
    foreach ($company_employees as $key => $emp) {
        $employees .= '<option value="' . ($emp['sid']) . '">' . (remakeEmployeeName($emp)) . '</option>';
        //
        $company_employees[$key]['DepartmentIds'] = array_values($emp['DepartmentIds']);
        $company_employees[$key]['TeamIds'] = array_values($emp['TeamIds']);
        //
        $slug = trim(preg_replace('/[^a-zA-Z]/i', '', strtolower($emp['job_title'])));
        //
        if (!empty($slug)) {
            $jobTitles[$slug] = [
                $slug,
                $emp['job_title']
            ];
        }
    }
    //
    asort($jobTitles);
}
//
if (!empty($DT['Departments'])) {
    foreach ($DT['Departments'] as $v) {
        $departments .= '<option value="' . ($v['department_id']) . '">' . ($v['title']) . '</option>';
        //
        $departmentArray[$v['department_id']] = $v['title'];
    }
}
//
if (!empty($DT['Teams'])) {
    foreach ($DT['Teams'] as $v) {
        $teams .= '<option value="' . ($v['team_id']) . '">' . ($v['title']) . '</option>';
        //
        $teamArray[$v['team_id']] = $v['title'];
    }
}
?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="csPIPage">
                        <!-- Loader -->
                        <div class="csIPLoader jsIPLoader dn" data-page="report"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></div>

                        <!--  -->
                        <div class="csPageWrap">
                            <div class="csPageHeader pl10 pr10 pb10">
                                <h3> <strong>Report</strong>
                                    <span class="pull-right">
                                        <a class="btn btn-success" href="javascript:;" id="view_report"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Report</a>
                                        <a class="btn btn-success jsReportLinkBulk" target="_blank" href="<?= base_url("timeoff/report/print"); ?>"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print Report</a>
                                        <a class="btn btn-success jsReportLinkBulk" target="_blank" href="<?= base_url("timeoff/report/download"); ?>" style="display: none;"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Report</a>
                                        <a class="btn btn-success jsReportLinkBulk" target="_blank" href="<?= base_url("timeoff/exporttocsv"); ?>"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Export Report</a>

                                    </span>
                                </h3>
                            </div>
                            <div class="csPageBody p10">
                                <div class="row">
                                    <!-- Filter -->
                                    <div class="col-sm-3 col-xs-12">
                                        <!--  -->
                                        <div class="form-group bbb">
                                            <label><strong>Filter</strong></label>
                                        </div>
                                        <!--  -->
                                        <form action="" method="GET" id="form_filter">
                                            <div class="form-group">
                                                <label>Employee(s)</label>
                                                <select multiple="true" id="filter_employees">
                                                    <?php if (!empty($assign_employees)) { ?>
                                                        <?php foreach ($assign_employees as $sid) { ?>
                                                            <option value="<?php echo $sid; ?>" <?php $filter_employees != 'all' && in_array($sid, $filter_employees) ? 'selected="seleccted"' : ''; ?>><?php echo getUserNameBySID($sid); ?></option>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <option value="0">No Employee Found!</option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                            <!--  -->
                                            <div class="form-group">
                                                <label>Department(s)</label>
                                                <select multiple="true" id="filter_departments">
                                                    <?php if (!empty($assign_departments)) { ?>
                                                        <?php foreach ($assign_departments as $sid) { ?>
                                                            <option value="<?php echo $sid; ?>"><?php echo getDepartmentNameBySID($sid); ?></option>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <option value="0">No Department Found!</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <!--  -->
                                            <div class="form-group">
                                                <label>Team(s)</label>
                                                <select multiple="true" id="filter_teams">
                                                    <?php if (!empty($assign_teams)) { ?>
                                                        <?php foreach ($assign_teams as $sid) { ?>
                                                            <option value="<?php echo $sid; ?>"><?php echo getTeamNameBySID($sid); ?></option>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <option value="0">No Team Found!</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <!--  -->
                                            <div class="form-group">
                                                <label>Job Title(s)</label>
                                                <select id="jsJobTitles" multiple="true">
                                                    <?php
                                                    if (!empty($jobTitles)) {
                                                        foreach ($jobTitles as $job) { ?>
                                                            <option value="<?= $job[0]; ?>"><?= $job[1]; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <!--  -->
                                            <div class="form-group">
                                                <label>Employment Type(s)</label>
                                                <select id="jsEmploymentTypes" multiple="true">
                                                    <option value="fulltime">Full-time</option>
                                                    <option value="parttime">Part-time</option>
                                                    <option value="contractual">Contractual</option>
                                                </select>
                                            </div>

                                                <div class="form-group">
                                                    <label>Employee Status</label>
                                                    <div>
                                                        <?= showEmployeeStatusSelect([
                                                            $this->input->get("employee_status", true) ?? 0
                                                        ], 'class="jsFilterEmployeeStatus" name="employee_status"'); ?>
                                                    </div>
                                                </div>


                                            <!--  -->
                                            <div class="form-group">
                                                <label>Policy</label>
                                                <select multiple="true" id="filter_policy">
                                                    <?php if (!empty($policies)) { ?>
                                                        <?php foreach ($policies as $policyRow) { ?>
                                                            <option value="<?php echo $policyRow['sid']; ?>"><?php echo $policyRow['title']; ?></option>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <option value="0">No Policy Found!</option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <!--  -->
                                            <div class="form-group">
                                                <label>Start Date</label>
                                                <?php $sfd = !empty($start_date) ? $start_date : ''; ?>
                                                <input type="text" readonly="true" class="form-control" name="startDate" id="jsReportStartDate" value="<?php echo $sfd; ?>" />
                                            </div>
                                            <!--  -->
                                            <div class="form-group">
                                                <label>End Date</label>
                                                <?php $efd = !empty($end_date) ? $end_date : ''; ?>
                                                <input type="text" readonly="true" class="form-control" name="endDate" id="jsReportEndDate" value="<?php echo $efd; ?>" />
                                            </div>

                                            <div class="form-group">
                                                <label class="control control--checkbox">
                                                    Include start & end date
                                                    <?php
                                                    if (isset($_GET['startDate']) && !isset($_GET['includeStartandEndDate'])) {
                                                        $includeStartandEndDateChecked = '';
                                                    } else {
                                                        $includeStartandEndDateChecked = 'checked';
                                                    } ?>

                                                    <input class="select-domain" type="checkbox" name="includeStartandEndDate" id="includeStartandEndDate" value="1" <?php echo $includeStartandEndDateChecked; ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>


                                            <input type="hidden" name="token" id="session_key" value="<?php echo $_GET['token']; ?>">
                                            <div class="form-group">
                                                <button class="btn btn-success form-control" id="apply_filter">Apply Filter</button>
                                            </div>
                                        </form>
                                        <!--  -->
                                        <div class="form-group">
                                            <a href="<?php echo base_url('timeoff/report'); ?>" class="btn btn-black form-control">Clear Filter</a>
                                        </div>
                                    </div>
                                    <!-- Employee listing area -->
                                    <div class="col-sm-9 col-xs-12">
                                        <!--  -->
                                        <div class="row" style="margin: 5px 5px;">
                                            <div class="col-lg-2" style="background: rgba(242, 222, 222, .5); padding: 16px;"></div>
                                            <div class="col-lg-10" style="padding: 6px; font-weight: 700;">The time-off request is consumed by an employee.</div>
                                        </div>
                                        <!--  -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-condensed">
                                                <caption></caption>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Employee Name</th>
                                                        <th scope="col">Department(s)</th>
                                                        <th scope="col">Team(s)</th>
                                                        <th scope="col"># of Time Off(s)</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($company_employees)) {
                                                        foreach ($company_employees as $emp) { ?>
                                                            <tr class="jsReportEmployeeRow" data-id="<?= $emp['sid']; ?>">
                                                                <td>
                                                                    <strong><?= ucwords($emp['first_name'] . ' ' . $emp['last_name']); ?></strong>
                                                                    <br /><?= remakeEmployeeName($emp, false); ?>
                                                                </td>
                                                                <td class="td_setting">
                                                                    <?php
                                                                    if (!empty($emp['DepartmentIds'])) {
                                                                        //
                                                                        $t = '';
                                                                        foreach ($emp['DepartmentIds'] as $v) {
                                                                            $t .= $departmentArray[$v] . ', ';
                                                                        }
                                                                        //
                                                                        echo rtrim($t, ', ');
                                                                    } else {
                                                                        echo 'N/A';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td class="td_setting">
                                                                    <?php
                                                                    if (!empty($emp['TeamIds'])) {
                                                                        //
                                                                        $t = '';
                                                                        foreach ($emp['TeamIds'] as $v) {
                                                                            $t .= $teamArray[$v] . ', ';
                                                                        }
                                                                        //
                                                                        echo rtrim($t, ', ');
                                                                    } else {
                                                                        echo 'N/A';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td class="td_setting">
                                                                    <span class="timeoff_count" data-status="hide" data-id="timeoff_<?php echo $emp['sid']; ?>" data-toggle="tooltip" data-placement="top" title="Click to see request!">
                                                                        <?php
                                                                        $count = count($emp['timeoffs']);
                                                                        echo $count . ' Request(s)';
                                                                        ?>
                                                                    </span>
                                                                </td>
                                                                <td class="td_setting">
                                                                    <a class="btn btn-success jsReportLink" target="_blank" href="<?= base_url("timeoff/report/print/" . ($emp['sid']) . ""); ?>">
                                                                        <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
                                                                    </a>
                                                                    <a class="btn btn-success jsReportLink" target="_blank" href="<?= base_url("timeoff/report/download/" . ($emp['sid']) . ""); ?>" style="display: none;">
                                                                        <i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download
                                                                    </a>
                                                                    <a class="btn btn-success jsReportLink" target="_blank" href="<?= base_url("timeoff/exporttocsv/" . ($emp['sid']) . ""); ?>">
                                                                        <i class="fa fa-download" aria-hidden="true"></i>&nbsp;Export
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php if (!empty($emp['timeoffs'])) { ?>
                                                                <!-- <div class="row" > -->
                                                                <tr class="timeoff_<?php echo $emp['sid']; ?> subheader" style="display: none;">
                                                                    <th style="font-size: 14px !important;">Policy</th>
                                                                    <th style="font-size: 14px !important;">Time Taken</th>
                                                                    <th style="font-size: 14px !important;">Start Date</th>
                                                                    <th style="font-size: 14px !important;">End Date</th>
                                                                    <th style="font-size: 14px !important;">Status</th>
                                                                </tr>
                                                                <?php foreach ($emp['timeoffs'] as $timeoff) { ?>
                                                                    <tr class="timeoff_<?php echo $emp['sid']; ?>" style="display: none; <?php echo str_replace('CONSUMED', '', $timeoff['request_status']) != $timeoff['request_status'] ? 'background-color:  #f2dede !important;' : '';  ?>">
                                                                        <td><?php echo $timeoff['policy_name']; ?></td>
                                                                        <?php
                                                                        $hours = floor($timeoff['requested_time'] / 60);
                                                                        $hours = $hours . ' Hour(s)';
                                                                        ?>
                                                                        <td><?php echo $hours; ?></td>
                                                                        <td><?php echo DateTime::createfromformat('Y-m-d', $timeoff['request_from_date'])->format('m/d/Y'); ?></td>
                                                                        <td><?php echo DateTime::createfromformat('Y-m-d', $timeoff['request_to_date'])->format('m/d/Y'); ?></td>
                                                                        <td>
                                                                            <?php
                                                                            $status = $timeoff['status'];

                                                                            if ($status == 'approved') {
                                                                                echo '<strong class="text-success">APPROVED</strong> (' . $timeoff['request_status'] . ')';
                                                                            } else if ($status == 'rejected') {
                                                                                echo '<strong class="text-danger">REJECTED</strong> (<strong class="text-warning">PENDING</strong>)';
                                                                            } else if ($status == 'pending') {
                                                                                echo '<strong class="text-warning">PENDING</strong> (<strong class="text-warning">PENDING</strong>)';
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <!-- </div>    -->
                                                            <?php } ?>
                                                    <?php
                                                        }
                                                    } ?>
                                                </tbody>
                                            </table>
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

<div class="modal fade" id="js-view-report-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="float: none;">
                <div class="row">
                    <div class="col-xs-6">
                        <p>Company: <strong><?= $session['company_detail']['CompanyName']; ?></strong></p>
                        <p>Employee Name: <strong><?= ucwords($session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name']); ?> <?= remakeEmployeeName($session['employer_detail'], false); ?></strong></p>
                        <p>Report Period: <strong>
                                <?php
                                if ($this->input->get('start', true) && $this->input->get('end', true)) {
                                    echo $this->input->get('start', true) . ' - ' . $this->input->get('end', true);
                                } else if ($this->input->get('start', true)) {
                                    echo $this->input->get('start', true) . ' - N/A';
                                } else if ($this->input->get('end', true)) {
                                    echo 'N/A - ' . $this->input->get('end', true);
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </strong>
                        <p>
                    </div>
                    <div class="col-xs-6">
                        <p class="text-right">Report Date <strong><?= date('m/d/Y H:i', strtotime('now')); ?></strong></p>
                    </div>
                </div>
                <div class="row" style="margin: 5px 5px;">
                    <div class="col-lg-2" style="background: rgba(242, 222, 222, .5); padding: 16px;"></div>
                    <div class="col-lg-10" style="padding: 6px; font-weight: 700;">The allotted time off has been consumed.</div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <a class="btn btn-success jsReportLinkBulk" target="_blank" href="<?= base_url("timeoff/report/print"); ?>"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print Report</a>
                        <a class="btn btn-success jsReportLinkBulk" target="_blank" href="<?= base_url("timeoff/report/download"); ?>" style="display: none;"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Report</a>
                        <a class="btn btn-success jsReportLinkBulk" target="_blank" href="<?= base_url("timeoff/exporttocsv"); ?>"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Export Report</a>

                    </div>
                </div>
                <table class="table table-striped table-condensed table-bordered">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">Employee</th>
                            <th scope="col">Policy</th>
                            <th scope="col">Time Taken</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($company_employees)) { ?>
                            <?php foreach ($company_employees as $employee) { ?>

                                <?php
                                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
                                $employee_role = remakeEmployeeName($employee, false);
                                $employee_no = !empty($employee['employee_number']) ? $employee['employee_number'] : $employee['sid'];
                                ?>
                                <?php foreach ($employee['timeoffs'] as $timeoff) : ?>
                                    <tr style="<?php echo str_replace('CONSUMED', '', $timeoff['request_status']) != $timeoff['request_status'] ? 'background-color:  #f2dede !important;' : '';  ?>">
                                        <td>
                                            <strong>
                                                <?php echo $employee_name; ?>
                                            </strong>
                                            <br>
                                            <?php echo $employee_role; ?>
                                            <br>
                                            <?php echo $employee_no; ?>
                                        </td>
                                        <td><?php echo $timeoff['policy_name']; ?></td>
                                        <?php
                                        $hours = floor($timeoff['requested_time'] / 60);
                                        if ($hours > 1) {
                                            $hours = $hours . ' Hours';
                                        } else {
                                            $hours = $hours . ' Hour';
                                        }
                                        ?>
                                        <td><?php echo $hours; ?></td>
                                        <td>
                                            <?php echo DateTime::createfromformat('Y-m-d', $timeoff['request_from_date'])->format('m/d/Y'); ?>
                                        </td>
                                        <td>
                                            <?php echo DateTime::createfromformat('Y-m-d', $timeoff['request_to_date'])->format('m/d/Y'); ?>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $timeoff['status'];

                                            if ($status == 'approved') {
                                                echo '<strong class="text-success">APPROVED</strong> (' . $timeoff['request_status'] . ')';
                                            } else if ($status == 'rejected') {
                                                echo '<strong class="text-danger">REJECTED</strong> (<strong class="text-warning">PENDING</strong>)';
                                            } else if ($status == 'pending') {
                                                echo '<strong class="text-warning">PENDING</strong> (<strong class="text-warning">PENDING</strong>)';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5">Sorry, no records found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script>
    //
    $('#filter_employees').select2({
        closeOnSelect: false
    });
    $('#filter_departments').select2({
        closeOnSelect: false
    });
    $('#filter_teams').select2({
        closeOnSelect: false
    });
    let employeeList = <?= json_encode($company_employees); ?>;

    $('#filter_policy').select2({
        closeOnSelect: false
    });


    $('#view_report').on("click", function() {
        $("#js-view-report-modal").modal('show')
    });

    $('.timeoff_count').on('click', function() {
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');

        if (status == 'hide') {
            $('.' + id).show();
            $(this).attr('data-status', 'show');
        } else {
            $('.' + id).hide();
            $(this).attr('data-status', 'hide');
        }

    });

    $("#apply_filter").on("click", function(event) {
        event.preventDefault();
        var employees = $("#filter_employees").val();
        var departments = $("#filter_departments").val();
        var teams = $("#filter_teams").val();
        var policy = $("#filter_policy").val();

        var form_data = new FormData();
        form_data.append('employees', employees);
        form_data.append('departments', departments);
        form_data.append('teams', teams);
        form_data.append('action', 'generate_session');
        form_data.append('policy', policy);


        ml(true, 'report');
        $.ajax({
            url: '<?= base_url('timeoff/generateFilterSession') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(resp) {
                ml(false, 'report');
                if (resp.status == 'success') {
                    $("#session_key").val(resp.token);
                    $("#form_filter").submit();
                }

            },
            error: function() {}
        });
    })
</script>