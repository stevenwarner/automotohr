<?php
$isPayrollEnabled = checkIfAppIsEnabled(PAYROLL);
$canAccessDocument = hasDocumentsAssigned($session['employer_detail']);
$canEMSPermission = hasEMSPermission($session['employer_detail']);
?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <?php
                    //
                    if (isset($employee_type) && $employee_type == 'offline') {
                        $all = false;
                        $active = false;
                        $offline = true;
                        $terminated = false;
                    } else if (isset($employee_type) && $employee_type == 'terminated') {
                        $all = false;
                        $active = false;
                        $offline = false;
                        $terminated = true;
                    } else if (isset($employee_type) && $employee_type == 'all') {
                        $all = true;
                        $active = false;
                        $offline = false;
                        $terminated = false;
                    } else if (isset($employee_type) && $employee_type == 'active') {
                        $all = false;
                        $active = true;
                        $offline = false;
                        $terminated = false;
                    } else {
                        $all = false;
                        $active = true;
                        $offline = false;
                        $terminated = false;
                    }
                    //
                    $employee_array = $employees;
                    //
                    ?>
                    <div class="applicant-filter">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="filter-form-wrp">
                                    <span>Active / Inactive:</span>
                                    <div class="tracking-filter">
                                        <form action="" class="jsSubmitEmployeeForm" method="GET">
                                            <div class="row">
                                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                                    <div class="hr-select-dropdown">
                                                        <select name="employee_type" class="invoice-fields">

                                                            <option value="all" <?php echo $employee_type == 'all' ? 'selected="selected"' : ''; ?>>All</option>
                                                            <option value="active" <?php echo $employee_type == 'active' ? 'selected="selected"' : ''; ?>>Active</option>
                                                            <option value="leave" <?php echo $employee_type == 'leave' ? 'selected="selected"' : ''; ?>>Leave</option>
                                                            <option value="suspended" <?php echo $employee_type == 'suspended' ? 'selected="selected"' : ''; ?>>Suspended</option>
                                                            <option value="retired" <?php echo $employee_type == 'retired' ? 'selected="selected"' : ''; ?>>Retired</option>
                                                            <option value="rehired" <?php echo $employee_type == 'rehired' ? 'selected="selected"' : ''; ?>>Rehired</option>
                                                            <option value="deceased" <?php echo $employee_type == 'deceased' ? 'selected="selected"' : ''; ?>>Deceased</option>
                                                            <option value="terminated" <?php echo $employee_type == 'terminated' ? 'selected="selected"' : ''; ?>>Terminated</option>
                                                            <option value="inactive" <?php echo $employee_type == 'inactive' ? 'selected="selected"' : ''; ?>>Inactive</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                    <input type="hidden" name="keyword" value="<?php echo $keyword; ?>">
                                                    <input type="hidden" name="order_by" value="<?php echo $order_by; ?>">
                                                    <input type="hidden" name="order" value="<?php echo $order; ?>">
                                                    <input type="submit" value="Filter" class="form-btn">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>



                                <div class="filter-form-wrp">
                                    <span>Search Login Credentials:</span>
                                    <div class="tracking-filter">
                                        <form action="" class="jsSubmitEmployeeForm" method="GET">
                                            <div class="row">
                                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                                    <div class="hr-select-dropdown">
                                                        <select name="logincred" class="invoice-fields">
                                                            <option value="all" <?php echo $logincred == 'all' ? 'selected="selected"' : ''; ?>>All</option>
                                                            <option value="yes" <?php echo $logincred == 'yes' ? 'selected="selected"' : ''; ?>>Show Employees with ACTIVE Login credentials</option>
                                                            <option value="no" <?php echo $logincred == 'no' ? 'selected="selected"' : ''; ?>>Show Employees who have not yet Activated their Login credentials</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="order_by" value="<?php echo $order_by; ?>">
                                                <input type="hidden" name="order" value="<?php echo $order; ?>">
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                    <input type="submit" value="Filter" class="form-btn">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>



                                <?php if (!empty($departments)) { ?>
                                    <div class="filter-form-wrp">
                                        <span>Search Department:</span>
                                        <div class="tracking-filter">
                                            <form action="" class="jsSubmitEmployeeForm" method="GET">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                                        <div class="hr-select-dropdown">
                                                            <select name="department" class="invoice-fields">
                                                                <option value="0">Please Select Department</option>
                                                                <?php foreach ($departments as $department) { ?>
                                                                    <option value="<?php echo $department['sid']; ?>" <?php echo isset($department_sid) && $department_sid ==  $department['sid'] ? 'selected="selected"' : ''; ?>><?php echo $department['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="order_by" value="<?php echo $order_by; ?>">
                                                    <input type="hidden" name="order" value="<?php echo $order; ?>">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                        <input type="submit" value="Filter" class="form-btn">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="filter-form-wrp">
                                    <span>Search Employee(s):</span>
                                    <div class="tracking-filter">
                                        <form action="" class="jsSubmitEmployeeForm" method="GET">
                                            <div class="row">
                                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">

                                                    <input type="text" placeholder="Search Employee by Name, Email or Phone number" name="keyword" class="invoice-fields search-job" value="<?php echo $keyword; ?>">
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                    <input type="hidden" name="employee_type" value="<?php echo $employee_type; ?>">
                                                    <input type="hidden" name="order_by" value="<?php echo $order_by; ?>">
                                                    <input type="hidden" name="order" value="<?php echo $order; ?>">
                                                    <input type="submit" value="Search" class="form-btn">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="filter-form-wrp">
                                    <span>Sort Order:</span>
                                    <div class="tracking-filter">
                                        <form action="" class="jsSubmitEmployeeForm" method="GET">
                                            <div class="row">
                                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                                        <div class="hr-select-dropdown">
                                                            <select name="order_by" class="invoice-fields">
                                                                <option value="sid" <?= $order_by == 'sid' ? 'selected="selected"' : '' ?>>Created Date</option>
                                                                <option value="first_name" <?= $order_by == 'first_name' ? 'selected="selected"' : '' ?>>Name</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                                        <div class="hr-select-dropdown">
                                                            <select name="order" class="invoice-fields">
                                                                <option value="desc" <?= $order == 'desc' ? 'selected="selected"' : '' ?>>Descending</option>
                                                                <option value="asc" <?= $order == 'asc' ? 'selected="selected"' : '' ?>>Ascending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                    <input type="hidden" name="employee_type" value="<?php echo $employee_type; ?>">
                                                    <input type="hidden" name="keyword" value="<?php echo $keyword; ?>">
                                                    <input type="submit" value="Search" class="form-btn">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="btn-panel text-right">
                                    <div class="row">
                                        <div class="col-xs-2 text-left">
                                            <?php if ($session['employer_detail']['access_level_plus']) { ?>
                                                <a class="btn btn-success jsEmployeeQuickProfile" title="Quick View of Employee Profile" placement="top">
                                                    <i class="fa fa-users" aria-hidden="true"></i>
                                                    Employee Profile
                                                </a> &nbsp;&nbsp;&nbsp;
                                            <?php  } ?>
                                        </div>
                                        <?php if ($canEMSPermission) { ?>

                                            <div class="col-xs-2 text-left" style="padding-right: 0px;">
                                                <a href="javascript:void(0);" class="btn btn-success btn-block" id="send_bulk_email"><i class="fa fa-envelope" aria-hidden="true"></i> Send Bulk Email</a>

                                            </div>

                                            <?php if (get_company_module_status($session['company_detail']['sid'], 'bulk_email') == 1) { ?>
                                                <div class="col-xs-3 text-left" style="padding-right: 0px; padding-left: 5px">
                                                    <a href="javascript:void(0);" class="btn btn-success btn-block" id="send_bulk_email_login"><i class="fa fa-envelope" aria-hidden="true"></i> Send Bulk Login Email</a>

                                                </div>
                                            <?php } ?>


                                            <div class="col-xs-3" style="padding-right: 0px; padding-left: 5px">
                                                <a class="btn btn-success btn-block" href="<?php echo base_url(); ?>invite_colleagues">+ Add Employee / Team Members</a>
                                            </div>
                                            <div class="col-xs-2 text-left" style="padding-right: 5px; padding-left: 5px">
                                                <?php if ($offline) { ?>
                                                  <!--  <a class="btn btn-success btn-block" href="javascript:;" id="ej_controll_activate">Activate Selected</a> -->
                                                <?php } else { ?>
                                                  <!--  <a class="btn btn-danger btn-block" href="javascript:;" id="ej_controll_deactivate">De-activate Selected</a> -->
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <?php if ($logincred != 'all') { ?>
                                        <div class="row">
                                            <div class="col-xs-12 text-left">
                                                <a class="btn btn-success" href="javascript:;" id="logincredcsv">Export Employees To CSV</a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table-outer">
                        <div id="show_no_jobs">
                            <?php if (empty($employee_array)) { ?>
                                <span class="applicant-not-found">Employee not found!</span>
                            <?php } else {
                                $employeeIds = array_column($employee_array, 'sid');
                                $doNotHireRecords = checkDontHireText($employeeIds);
                            ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                <label class="control control--checkbox checkallbtn">
                                                    <input type="checkbox" id="selectall">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </th>
                                            <th width="30%">Employees</th>
                                            <th width="25%">Designation</th>
                                            <th>Password</th>
                                            <th class="text-center">Joining Date</th>
                                            <th class="text-center">Rehire Date</th>
                                            <?php if ($all === true || $terminated === true) { ?>
                                                <th class="text-center">Termination Date</th>
                                            <?php } ?>
                                            <th class="text-center">Transfer Date</th>
                                            <th colspan="3" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form method="POST" name="ej_form" id="ej_form">
                                            <?php $sizeof = sizeof($employee_array);
                                            $bulkloginEmailIds = array();
                                            $bulkloginEmployeeName = array();
                                            ?>
                                            <?php foreach ($employee_array as $employee) {
                                                //
                                                if ($employee['is_executive_admin'] && $employee['active'] == 0) {
                                                    continue;
                                                }

                                                $doNotHireWarning = doNotHireWarning($employee['sid'], $doNotHireRecords, 14);
                                            ?>
                                                <tr id="manual_row<?php echo $employee['sid']; ?>">
                                                    <td class="text-center <?php echo $doNotHireWarning['row']; ?>">
                                                        <?php if ($employee['is_executive_admin'] == 0) { ?>
                                                            <label class="control control--checkbox">
                                                                <input name="ej_check[]" type="checkbox" value="<?php echo $employee['sid']; ?>" class="<?= $employee['is_executive_admin'] == 0 ? 'ej_checkbox' : ''; ?>" <?= $employer_id != $employee['sid'] ? '' : 'disabled="true"'; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        <?php } ?>
                                                    </td>
                                                    <td width="30%" class="<?php echo $doNotHireWarning['row']; ?>">
                                                        <div class="employee-profile-info">
                                                            <figure>
                                                                <?php if (check_access_permissions_for_view($security_details, 'employee_profile')) { ?>
                                                                    <a href="<?php echo ($session['employer_detail']['access_level_plus'] && $employer_id != $employee['sid']) || $session['employer_detail']['pay_plan_flag'] ? base_url('employee_profile') . '/' . $employee['sid'] : 'javascript:;'; ?>" title="<?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>">
                                                                        <img src="<?php echo getImageURL($employee['profile_picture']); ?>">
                                                                    </a>
                                                                <?php } else { ?>
                                                                    <img src="<?php echo getImageURL($employee['profile_picture']); ?>">
                                                                <?php   } ?>
                                                            </figure>
                                                            <div class="text">
                                                                <?php
                                                                $middle_initial = !empty($employee['middle_name']) ? ' ' . $employee['middle_name'] : '';
                                                                //
                                                                if (!empty($employee['first_name']) || !empty($employee['last_name'])) {
                                                                    $name = $employee['first_name'] . $middle_initial . ' ' . $employee['last_name'];
                                                                }
                                                                if ($session['employer_detail']['access_level_plus'] == 1 && !empty($employee['username'])) {
                                                                    //
                                                                    $nick_name = !empty($employee['nick_name']) ? '[' . $employee['nick_name'] . ']' : '';
                                                                    $name .= ' (' . ($employee['username']) . ')' . $nick_name;
                                                                }

                                                                ?>
                                                                <?php if (check_access_permissions_for_view($security_details, 'employee_profile')) { ?>
                                                                    <a href="<?php
                                                                                if ($employee['is_executive_admin'] == 0 && ($session['employer_detail']['access_level_plus'] && $employer_id != $employee['sid']) || $session['employer_detail']['pay_plan_flag']) {
                                                                                    echo base_url('employee_profile') . '/' . $employee['sid'];
                                                                                } else {
                                                                                    echo "javascript:void(0);";
                                                                                }
                                                                                ?>" style="font-size:16px;font-weight: bold;"><?php echo $name; ?></a>
                                                                <?php } else { ?>
                                                                    <p style="font-size:16px;font-weight: bold;"><?php echo $name; ?></p>
                                                                <?php } ?>
                                                                <?php
                                                                echo '<br />' . $employee['email'];                                                                
                                                                echo '<br /> <b> Primary Number: </b>' . $employee['PhoneNumber'];
                                                                echo '<br /> <b> Employee Type: </b>' . formateEmployeeJobType($employee['employee_type']);
                                                                echo '<br> <b> Employee Status:</b> ' . (GetEmployeeStatus($employee['last_status_text'], $employee['active']));
                                                                ?>
                                                                <br>
                                                                <?php
                                                                if ($employee['is_executive_admin'] == 1) {
                                                                    echo '[Executive Admin';
                                                                    echo ($employee['access_level_plus'] && $employee['pay_plan_flag']) ? ' Plus / Payroll' : ($employee['access_level_plus'] ? ' Plus' : ($employee['pay_plan_flag'] ? ' Payroll' : ''));
                                                                    echo ']';
                                                                } else {
                                                                    echo '[' . $employee['access_level'];
                                                                    echo ($employee['access_level_plus'] && $employee['pay_plan_flag']) ? ' Plus / Payroll' : ($employee['access_level_plus'] ? ' Plus' : ($employee['pay_plan_flag'] ? ' Payroll' : ''));
                                                                    echo ']';
                                                                }
                                                                ?>

                                                                <?php echo $doNotHireWarning['message']; ?>
                                                                <?php
                                                                $onComplyNet = getComplyNetEmployeeCheck($employee, $session['employer_detail']['pay_plan_flag'], $session['employer_detail']['access_level_plus'], false);
                                                                if (isset($onComplyNet["errors"])) {
                                                                    echo "<br/><b>ComplyNet: </b><br />";
                                                                    echo implode("<br>", $onComplyNet["errors"]);
                                                                } else {
                                                                    if ($onComplyNet) {

                                                                        echo $employee['is_executive_admin'] == 0 ? '<br> <b> ComplyNet Status:</b> ' . ($onComplyNet) : '';
                                                                    }
                                                                }
                                                                ?>
                                                                <!-- Languages -->
                                                                <br />
                                                                <strong>I Speak:</strong> <?= showLanguages($employee['languages_speak']); ?>
                                                            </div>

                                                        </div>
                                                    </td>
                                                    <td width="25%" class="<?php echo $doNotHireWarning['row']; ?>">
                                                        <?php
                                                        if (empty($employee["job_title"])) {
                                                            echo 'No job designation found!' . '<br>';
                                                        } else {
                                                            echo "<b>Job Title: </b>" . $employee['job_title'] . "<br>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center <?php echo $doNotHireWarning['row']; ?>">
                                                        <?php if (check_access_permissions_for_view($security_details, 'send_login_email')) { ?>
                                                            <?php
                                                            if (($employee['password'] == '' || is_null($employee['password'])) && ($employee['is_executive_admin'] != 1)) {
                                                                $employeeStatus = GetEmployeeStatus($employee['last_status_text'], $employee['active']);
                                                                if ($employeeStatus == "Active" && !empty($employee['email'])) {
                                                                    array_push($bulkloginEmailIds, $employee['sid']);
                                                                    array_push($bulkloginEmployeeName, $name);
                                                                }
                                                            ?>
                                                                <img src="<?= base_url('assets/manage_admin/images/bulb-red.png') ?>">
                                                                <?php echo '<br><a href="javascript:;" class="btn btn-success btn-sm send_credentials" title="Send Login Credentials" data-attr="' . $employee['sid'] . '">Send Login Email</a>'; ?>
                                                            <?php   } else { ?>
                                                                <img src="<?= base_url('assets/manage_admin/images/bulb-green.png') ?>">
                                                            <?php   } ?>
                                                        <?php   } ?>
                                                    </td>
                                                    <td class="text-center <?php echo $doNotHireWarning['row']; ?>">
                                                        <?php
                                                        $joiningDate = get_employee_latest_joined_date($employee["registration_date"], $employee["joined_at"], "", true);
                                                        //
                                                        if (!empty($joiningDate)) {
                                                            echo $joiningDate;
                                                        } else {
                                                            echo "N/A";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="<?php echo $doNotHireWarning['row']; ?>">
                                                        <?php
                                                        $rehireDate = get_employee_latest_joined_date("", "", $employee["rehire_date"], true);
                                                        //
                                                        if (!empty($rehireDate)) {
                                                            echo $rehireDate;
                                                        } else {
                                                            echo "N/A";
                                                        }
                                                        ?>
                                                    </td>
                                                    <?php if ($all === true || $terminated === true) { ?>
                                                        <td class="text-center <?php echo $doNotHireWarning['row']; ?>">
                                                            <?php
                                                            //
                                                            if ($employee['last_status']) {
                                                                //
                                                                echo formatDateToDB($employee['last_status']["termination_date"], DB_DATE, DATE);
                                                                //if($employee['last_status']['details']) {
                                                                if ($employee['last_status']['termination_reason']) {
                                                                    // echo "<br/> Reason: ".(html_entity_decode($employee['last_status']['details'])); 
                                                                    echo "<br/> Reason: ";
                                                                    $reason = $employee['last_status']['termination_reason'];

                                                                    if ($reason == 1) {
                                                                        echo 'Resignation';
                                                                    } else if ($reason == 2) {
                                                                        echo 'Fired';
                                                                    } else if ($reason == 3) {
                                                                        echo 'Tenure Completed';
                                                                    } else if ($reason == 4) {
                                                                        echo 'Personal';
                                                                    } else if ($reason == 5) {
                                                                        echo 'Personal';
                                                                    } else if ($reason == 6) {
                                                                        echo 'Problem with Supervisor';
                                                                    } else if ($reason == 7) {
                                                                        echo 'Relocation';
                                                                    } else if ($reason == 8) {
                                                                        echo 'Work Schedule';
                                                                    } else if ($reason == 9) {
                                                                        echo 'Retirement';
                                                                    } else if ($reason == 10) {
                                                                        echo 'Return to School';
                                                                    } else if ($reason == 11) {
                                                                        echo 'Pay';
                                                                    } else if ($reason == 12) {
                                                                        echo 'Without Notice/Reason';
                                                                    } else if ($reason == 13) {
                                                                        echo 'Involuntary';
                                                                    } else if ($reason == 14) {
                                                                        echo 'Violating Company Policy';
                                                                    } else if ($reason == 15) {
                                                                        echo 'Attendance Issues';
                                                                    } else if ($reason == 16) {
                                                                        echo 'Performance';
                                                                    } else if ($reason == 17) {
                                                                        echo 'Workforce Reduction';
                                                                    } else if ($reason == 19) {
                                                                        echo 'Did Not Hire';
                                                                    } else {
                                                                        echo 'N/A';
                                                                    }
                                                                }
                                                            } else {
                                                                echo "N/A";
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="<?php echo $doNotHireWarning['row']; ?>">
                                                        <?php
                                                        if (!empty($employee['transfer_date'])) {
                                                            echo formatDateToDB($employee['transfer_date'], DB_DATE, DATE);
                                                        } else {
                                                            echo "N/A";
                                                        }
                                                        ?>
                                                    </td>
                                                    <?php if ($employer_id != $employee['sid']) { ?>
                                                        <?php if (
                                                            check_access_permissions_for_view($security_details, 'employee_send_documents') ||
                                                            $canAccessDocument
                                                        ) { ?>
                                                            <td class="text-center <?php echo $doNotHireWarning['row']; ?>">
                                                                <?php //if($employee['terminated_status'] == 0) { 
                                                                ?>
                                                                <?php if ($ems_status == 1) { ?>
                                                                    <?php if ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1 || $canAccessDocument) { ?>
                                                                        <a title="Document Management" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-sm" href="<?php echo base_url('hr_documents_management/documents_assignment/employee') . '/' . $employee['sid']; ?>">
                                                                            <i class="fa fa-file" aria-hidden="true"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if (checkIfAppIsEnabled('timeoff', FALSE) && $employee['terminated_status'] == 0) { ?>
                                                                        <?php
                                                                        if (
                                                                            ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) ||
                                                                            (in_array($employee['sid'], $teamMemberIds))
                                                                        ) {
                                                                        ?>
                                                                            <?php if ($employee['is_executive_admin'] == 0) { ?>
                                                                                <a title="Time Off" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-sm" href="<?php echo base_url('timeoff/create_employee') . '/' . $employee['sid']; ?>">
                                                                                    <i class="fa fa-clock-o"></i>
                                                                                </a>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <?php if ($employee['is_executive_admin'] == 0) { ?>
                                                                        <a title="Send HR Documents" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-sm" href="<?php echo base_url('send_offer_letter_documents') . '/' . $employee['sid']; ?>">
                                                                            <i class="fa fa-file"></i>
                                                                            <span class="btn-tooltip">HR-Documents</span>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <?php if ($session['employer_detail']['access_level_plus'] && $employee['is_executive_admin'] == 0) { ?>
                                                                    <!-- Employee Quick Profile -->
                                                                    <button class="btn btn-success jsEmployeeQuickProfile" title="Employee Profile Quick View" placement="top" data-id="<?= $employee['sid']; ?>">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                    </button>
                                                                <?php
                                                                } ?>
                                                                <?php if (isPayrollOrPlus() && $isPayrollEnabled && isEmployeeOnPayroll($employee["sid"])) { ?>
                                                                    <a href="<?= base_url("payrolls/dashboard/employee/" . $employee["sid"]); ?>" class="btn btn-success" title="Employee payroll dashboard" placement="top">
                                                                        <i class="fa fa-dashboard" aria-hidden="true"></i>
                                                                    </a>
                                                                <?php } ?>
                                                            </td>
                                                        <?php } ?>
                                                        <td class="text-center <?php echo $doNotHireWarning['row']; ?>">
                                                            <?php if ($canEMSPermission) { ?>
                                                                <?php if ($employee['is_executive_admin'] == 0) { ?>
                                                                    <?php if ($employee['active'] == 1 && $employee['terminated_status'] == 0 && $employee['archived'] == 0) { ?>
                                                                        <a title="Deactivate" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-sm" onclick="deactivate_single_employee(<?php echo $employee['sid']; ?>)" href="javascript:;"><img style="width: 17px; height: 17px;" src="<?= base_url('assets/images/deactivate.png') ?>"></a>
                                                                    <?php } else if ($employee['active'] == 0 && $employee['terminated_status'] == 0 && $employee['archived'] == 0) { ?>
                                                                        <a title="Archive Employee" data-toggle="tooltip" data-placement="bottom" class="btn btn-warning btn-sm" onclick="archive_single_employee(<?php echo $employee['sid']; ?>)" href="javascript:;">
                                                                            <i class="fa fa-archive"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center <?php echo $doNotHireWarning['row']; ?>">
                                                            <?php if ($canEMSPermission) { ?>
                                                                <?php if ($employee['is_executive_admin'] == 0) { ?>
                                                                    <?php if ($employee['terminated_status'] == 0) { ?>
                                                                        <?php if (!empty($employee['applicant_sid'])) { ?>
                                                                            <a class="btn btn-info btn-sm" onclick="revert_applicant(<?php echo $employee['applicant_sid']; ?>, <?php echo $employee['sid']; ?>)" href="javascript:;">
                                                                                <i class="fa fa-undo"></i>
                                                                                <span class="btn-tooltip">Revert</span>
                                                                            </a>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <?php if (($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) && (isTranferredEmployee($employee['sid']))) { ?>
                                                                <button class="btn btn-success jsEmployeeTransferLog" title="" placement="top" data-id="<?php echo $employee['sid']; ?>" data-original-title="View Transfer Details">
                                                                    <i class="fa fa-history" aria-hidden="true"></i>
                                                                </button>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } else {
                                                        echo '<td colspan="' . ($sizeof == 1 ? '1' : '3') . '"></td>';
                                                    } ?>
                                                </tr>
                                            <?php } ?>
                                        </form>
                                    </tbody>
                                </table>
                            <?php } ?>
                            <input type="hidden" name="countainer_count" id="countainer_count" value="<?php echo sizeof($employee_array); ?>">
                        </div>
                    </div>
                    <div class="btn-panel">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                <a class="btn btn-success" href="<?php echo base_url('archived_employee'); ?>">Archived Employee</a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                <?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus']) { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('terminated_employee'); ?>">Terminated Employee</a>
                                <?php } ?>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                <?php if ($employee_type == 'active') { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('employee_management?employee_type=offline'); ?>">All Onboarding & De-activated Employees</a>
                                <?php   } else { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('employee_management'); ?>">Active Employee / Team Members</a>
                                <?php   } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="bulk_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Bulk Email to Employees</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100 autoheight">
                                <label>Email Template</label>
                                <div class="hr-select-dropdown">
                                    <select class="invoice-fields" name="template" id="template">
                                        <option id="" data-name="" data-subject="" data-body="" value="">Please Select</option>
                                        <?php if (!empty($portal_email_templates)) { ?>
                                            <?php foreach ($portal_email_templates as $template) { ?>
                                                <option id="template_<?php echo $template['sid']; ?>" data-name="<?php echo $template['template_name'] ?>" data-subject="<?php echo $template['subject']; ?>" data-body="<?php echo htmlentities($template['message_body']); ?>" value="<?php echo $template['sid']; ?>"><?php echo $template['template_name']; ?></option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option id="template_" data-name="" data-subject="" data-body="" value="">No Custom Template Defined</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </li>
                            <form method='post' id='register-form' name='register-form'>

                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Subject<span class="hr-required red"> * </span></label>
                                            <input type='text' class="invoice-fields" id="bulk_email_subject" name='subject' />
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Message<span class="hr-required red"> * </span></label>
                                            <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="bulk_email_message" name="bulk_email_message"></textarea>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label>Attachments</label>
                                    <?php if (!empty($portal_email_templates)) {
                                        foreach ($portal_email_templates as $template) { ?>
                                            <div id="<?php echo $template['sid']; ?>" class="temp-attachment" style="display: none">
                                                <?php if (sizeof($template['attachments']) > 0) {
                                                    foreach ($template['attachments'] as $attachment) { ?>
                                                        <div class="invoice-fields">
                                                            <span class="selected-file"><?php echo $attachment['original_file_name'] ?></span>
                                                        </div>
                                                    <?php }
                                                } else { ?>
                                                    <div class="invoice-fields">
                                                        <span class="selected-file">No Attachments</span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                    <?php
                                        }
                                    } ?>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label>Additional Attachments</label>
                                    <div class="upload-file invoice-fields">
                                        <span class="selected-file">No file selected</span>
                                        <input type="file" name="message_attachment" id="message_attachment" class="image">
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                    <button class="btn btn-link jsAdditionalAttachmentBtn">
                                        <i class="fa fa-plus-circle"></i>
                                        &nbsp;
                                        Add Attachments
                                    </button>
                                </li>

                                <div class="jsAdditionalAttachmentsContainer"></div>


                                <li class="form-col-100 autoheight">
                                    <div class="message-action-btn">
                                        <input type="submit" value="Send Message" id="send-message-email" class="submit-btn" onclick="bulk_email_form_validate()">
                                    </div>
                                </li>
                                <div class="custom_loader">
                                    <div id="loader" class="loader" style="display: none">
                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                        <span>Sending...</span>
                                    </div>
                                </div>

                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function deactivate_single_employee(id) {
        window.location.href = "<?= base_url() ?>employee_status/" + id
        // alertify.confirm("Please Confirm Deactivate", "Are you sure you want to Deactivate employee?",
        //     function() {
        //         // url = "<?= base_url() ?>employee_management/deactivate_single_employee";

        //         // $.post(url, {
        //         //         del_id: id,
        //         //         action: "deactivate_single_employee"
        //         //     })
        //         //     .done(function(data) {
        //         //         $('#manual_row' + id).hide();
        //         //         var total_rows = $('#countainer_count').val();
        //         //         total_rows = total_rows - 1;
        //         //         $('#countainer_count').val(total_rows);

        //         //         if (total_rows <= 0) {
        //         //             show_no_jobs
        //         //             $('#hide_del_row').hide();
        //         //             $('#show_no_jobs').html('<span class="applicant-not-found">No Employees found!</span>');
        //         //         }


        //         //         alertify.notify(data, 'success');
        //         //     });
        //     },
        //     function() {
        //         alertify.error('Cancelled');
        //     });
    }

    function delete_single_employee(id) {
        alertify.confirm("Please Confirm Delete", "Are you sure you want to Permanently Delete employee?",
            function() {
                url = "<?= base_url() ?>employee_management/delete_single_employee";

                $.post(url, {
                        del_id: id,
                        action: "delete_single_employee"
                    })
                    .done(function(data) {
                        $('#manual_row' + id).hide();
                        var total_rows = $('#countainer_count').val();
                        total_rows = total_rows - 1;
                        $('#countainer_count').val(total_rows);

                        if (total_rows <= 0) {
                            show_no_jobs
                            $('#hide_del_row').hide();
                            $('#show_no_jobs').html('<span class="applicant-not-found">No Employees found!</span>');
                        }

                        alertify.notify(data, 'success');
                    });
            },
            function() {
                alertify.error('Cancelled');
            });
    }

    function archive_single_employee(id) {
        window.location.href = "<?= base_url() ?>employee_status/" + id
        // alertify.confirm("Please Confirm Archive", "Are you sure you want to Archive employee?",
        //     function() {
        //         url = "<?= base_url() ?>employee_management/archive_single_employee";
        //         $.post(url, {
        //                 archive_id: id,
        //                 action: "archive_single_employee"
        //             })
        //             .done(function(data) {
        //                 $('#manual_row' + id).hide();
        //                 var total_rows = $('#countainer_count').val();
        //                 total_rows = total_rows - 1;
        //                 $('#countainer_count').val(total_rows);

        //                 if (total_rows <= 0) {
        //                     show_no_jobs
        //                     $('#hide_del_row').hide();
        //                     $('#show_no_jobs').html('<span class="applicant-not-found">No Employees found!</span>');
        //                 }

        //                 alertify.notify(data, 'success');
        //             });
        //     },
        //     function() {
        //         alertify.error('Cancelled');
        //     });
    }

    function revert_applicant(revert_id, id) {
        alertify.confirm("Please Confirm Revert", "Are you sure you want to Revert employee back to Applicant?",
            function() {
                url = "<?= base_url() ?>employee_management/revert_employee_back_to_applicant";

                $.post(url, {
                        revert_id: revert_id,
                        id: id,
                        action: "revert_applicant"
                    })
                    .done(function(data) {

                        if (data == 'success') {
                            $('#manual_row' + id).hide();
                            var total_rows = $('#countainer_count').val();
                            total_rows = total_rows - 1;
                            $('#countainer_count').val(total_rows);

                            if (total_rows <= 0) {
                                show_no_jobs
                                $('#hide_del_row').hide();
                                $('#show_no_jobs').html('<span class="applicant-not-found">No Employees found!</span>');
                            }

                            window.location.href = '<?php echo base_url('employee_management') ?>';
                            // alertify.notify(data, 'success');
                        } else {
                            alertify.error('There was some error! Please consult Technical Support');
                        }
                    });
            },
            function() {
                alertify.error('Cancelled');
            });
    }

    $(document).ready(function() {
        $('#ej_controll_delete').click(function() {
            var butt = $(this);

            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to delete application(s)?",
                        function() {
                            $('#ej_form').append('<input type="hidden" name="delete_contacts" value="true" />');
                            $("#ej_form").submit();
                            alertify.success('Deleted');

                        },
                        function() {
                            alertify.error('Cancelled');
                        });
                }
            } else {
                alertify.alert('Please select application(s) to delete');
            }
        });

        $('#ej_controll_deactivate').click(function() {
            var butt = $(this);
            if ($(".ej_checkbox:checked").size() > 0) {

                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to de-activate employee(s)?",
                        function() {
                            $('#ej_form').append('<input type="hidden" name="deactivate_employees" value="true" />');
                            $("#ej_form").submit();
                            alertify.success('De-activated');
                        },
                        function() {
                            alertify.error('Cancelled');
                        });
                }
            } else {
                alertify.alert('Please select employee(s) to de-activate');
            }
        });

        $('#ej_controll_activate').click(function() {
            var butt = $(this);

            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to activate employee(s)?",
                        function() {
                            $('#ej_form').append('<input type="hidden" name="activate_employees" value="true" />');
                            $("#ej_form").submit();
                            alertify.success('activated');
                        },
                        function() {
                            alertify.error('Cancelled');
                        });
                }
            } else {
                alertify.alert('Please select employee(s) to de-activate');
            }
        });

        $('.selected').click(function() {
            $(this).next().css("display", "block");
        });

        $('.candidate').click(function() {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            status = $(this).find('#status').html();
            id = $(this).parent().find('#id').html();
            url = "<?= base_url() ?>application_tracking_system/update_status";

            $.post(url, {
                    "id": id,
                    "status": status,
                    "action": "ajax_update_status_candidate"
                })
                .done(function(data) {
                    alertify.success("Candidate status updated successfully.");
                });
        });

        $('.candidate').hover(function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.applicant').click(function() {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            var status = $(this).find('#status').html();
            var id = $(this).parent().find('#id').html();
            url = "<?= base_url() ?>application_tracking_system/update_status";

            $.post(url, {
                    "id": id,
                    "status": status,
                    "action": "ajax_update_status"
                })
                .done(function(data) {
                    alertify.success("Candidate status updated successfully.");
                });
        });

        $('.applicant').hover(function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function() {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function() {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function() {
            $(this).parent().css("display", "none");
        });
        $.each($(".selected"), function() {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });

        $('#selectall').click(function(event) { //on click
            if (this.checked) { // check select status
                $('.ej_checkbox').each(function() { //loop through each checkbox
                    this.checked = true; //select all checkboxes with class "checkbox1"              
                });
            } else {
                $('.ej_checkbox').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                      
                });
            }
        });
    });

    $(document).on('click', '.send_credentials', function(e) {
        var sid = $(this).attr('data-attr');
        var url = "<?= base_url('employee_management/send_login_credentials') ?>";
        console.log(url);
        console.log(sid);
        alertify.confirm('Confirmation', "Are you sure you want to send login credentials?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'sendemail',
                        sid: sid
                    },
                    success: function(data) {
                        if (data == 'success') {
                            alertify.success('Email with Login credentials is sent.');
                        } else {
                            alerty.error('there was error, please try again!');
                        }
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    /**
     * Employee search form
     */
    $('.jsSubmitEmployeeForm').submit(function(event) {
        //
        event.preventDefault();
        var url = "<?= rtrim(base_url(), '/'); ?>/employee_management?";
        //
        var department = $('select[name="department"]').find(':selected').val();
        var orderBy = $('select[name="order_by"]').find(':selected').val();
        var order = $('select[name="order"]').find(':selected').val();
        var employeeType = $('select[name="employee_type"]').find(':selected').val();
        var keyword = $('.search-job').val().trim();

        var loginCred = $('select[name="logincred"]').find(':selected').val();




        url += 'employee_type=' + employeeType;
        url += '&department=' + department;
        url += '&keyword=' + keyword;
        url += '&order_by=' + orderBy;
        url += '&order=' + order;
        url += '&logincred=' + loginCred;
        //
        window.location.href = url;
    });

    // 

    $('#template').on('change', function() {
        var template_sid = $(this).val();
        var msg_subject = $('#template_' + template_sid).attr('data-subject');
        var msg_body = $('#template_' + template_sid).attr('data-body');
        $('#bulk_email_subject').val(msg_subject);
        CKEDITOR.instances.bulk_email_message.setData(msg_body);
        $('.temp-attachment').hide();
        $('#' + template_sid).show();
    });


    $('#message_attachment').on('change', function() {
        var fileName = $(this).val();
        if (fileName.length > 0) {
            $(this).prev().html(fileName.substring(0, 45));
        } else {
            $(this).prev().html('No file selected');
        }
    });

    function toggle_bulk_email_modal() {
        $('#bulk_email_modal').modal('toggle');
        $('#bulk_email_subject').val('');
        $('#template').val('');
        CKEDITOR.instances.bulk_email_message.setData('');
        $('.temp-attachment').hide();
    }

    var additionalAttachmentsCount = 1;


    $('#send_bulk_email').click(function() {
        var butt = $(this);
        additionalAttachmentsCount = 1;
        $(".jsAdditionalAttachmentsContainer").html("");

        if ($(".ej_checkbox:checked").size() > 0) {
            if (butt.attr("id") == "ej_controll_mark") {
                $("#ej_action").val("mark");
            } else {
                alertify.confirm("Are you sure you want to send bulk email to selected employee(s)?",
                    function() {
                        setTimeout(toggle_bulk_email_modal, 1000);
                    },
                    function() {
                        alertify.error('Cancelled');
                    });
            }
        } else {
            alertify.alert('Please select employee(s) to send bulk email.');
        }
    });


    function bulk_email_form_validate() {
        $("#register-form").validate({
            ignore: [],
            rules: {
                subject: {
                    required: true
                },
                bulk_email_message: {
                    required: function() {
                        CKEDITOR.instances.bulk_email_message.updateElement();
                    },
                    minlength: 10
                }
            },
            messages: {
                subject: {
                    required: 'E-Mail Subject is required'
                },
                bulk_email_message: {
                    required: "E-Mail Message is required",
                    minlength: "Please enter few characters"
                }
            },
            submitHandler: function(form) {
                var ids = [{}];
                var counter = 0;

                $.each($(".ej_checkbox:checked"), function() {
                    ids[counter++] = $(this).val();
                });

                var file_data = $('#message_attachment').prop('files')[0];
                var subject = ($('#bulk_email_subject').val()).trim();
                var message = ($('#bulk_email_message').val()).trim();
                var template = $('#template').val();
                var form_data = new FormData();
                form_data.append('message_attachment[]', file_data);
                $(".jsAttachmentsFiles").map(function() {
                    form_data.append('message_attachment[]',
                        $(this).prop("files")[0]);
                })
                form_data.append('subject', subject);
                form_data.append('ids', ids);
                form_data.append('action', 'bulk_email');
                form_data.append('message', message);
                form_data.append('template_id', template);
                $('#loader').show();
                $('#send-message-email').addClass('disabled-btn');
                $('#send-message-email').prop('disabled', true);
                url_to = "<?= base_url() ?>send_manual_email/send_bulk_email_employees";
                $.ajax({
                    url: url_to,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(response) {
                        $("#bulk_email_modal .close").click();
                        $('#loader').hide();
                        $('#send-message-email').removeClass('disabled-btn');
                        $('#send-message-email').prop('disabled', false);
                        alertify.success('Bulk email sent to selected applicant(s).');
                    },
                    error: function() {}
                });
                return false;
            }
        });
    }


    $(".jsAdditionalAttachmentBtn").click(function(event) {
        event.preventDefault();

        $(".jsAdditionalAttachmentsContainer").append(
            generateAttachmentUI()
        )
    });

    $(document).on("click", ".jsAdditionalAttachmentsRowRemove", function(event) {
        event.preventDefault();
        const id = $(this).data('id')
        $(`.jsAdditionalAttachmentsRow${id}`).remove()
    });


    function generateAttachmentUI() {
        const rowId = additionalAttachmentsCount;
        const cls = `jsAdditionalAttachmentsRow${rowId}`;
        additionalAttachmentsCount++;
        return `
         <li class="form-col-100 autoheight ${cls}">
            <label>Additional Attachments ${rowId}</label>
            <div class="upload-file invoice-fields">
                <span class="selected-file">No file selected</span>
                <input type="file" class="jsAttachmentsFiles" name="jsAttachementsFile${rowId}" class="image" />
                <a href="javascript:;">Choose File</a>
            </div>
            <button class="btn btn-link text-red jsAdditionalAttachmentsRowRemove" data-id="${rowId}">
            <i class="fa fa-trash"></i>
            &nbsp;
            Remove Attachment
            </button>
        </li>
        `;
    }

    $(document).on('change', '.jsAttachmentsFiles', function() {
        var fileName = $(this).val();
        if (fileName.length > 0) {
            $(this).prev().html(fileName.substring(0, 45));
        } else {
            $(this).prev().html('No file selected');
        }
    });


    $(document).on('click', '#send_bulk_email_login', function(e) {
        //
        var employee_sids = "<?php echo implode(',', $bulkloginEmailIds); ?> ";
        var url = "<?= base_url('employee_management/send_login_credentials_bulk') ?>";
        var employee_name = "<?php echo implode('<br>', $bulkloginEmployeeName); ?>";
        //
        alertify.confirm(
            'Confirmation',
            "Are you sure you want to send login credentials to?<br>" + employee_name,
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'sendemail',
                        sids: employee_sids
                    },
                    success: function(data) {
                        if (data == 'success') {
                            alertify.alert('SUCCESS', 'Email with Login credentials is sent.');
                        } else {
                            alertify.alert('WARNING', 'There was error, please try again!');
                        }
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });



    $('#logincredcsv').click(function() {
        //
        event.preventDefault();
        var url = "<?= rtrim(base_url(), '/'); ?>/employee_export_csv?";
        //
        var department = $('select[name="department"]').find(':selected').val();
        var orderBy = $('select[name="order_by"]').find(':selected').val();
        var order = $('select[name="order"]').find(':selected').val();
        var employeeType = $('select[name="employee_type"]').find(':selected').val();
        var keyword = $('.search-job').val().trim();

        var loginCred = $('select[name="logincred"]').find(':selected').val();

        url += 'employee_type=' + employeeType;
        url += '&department=' + department;
        url += '&keyword=' + keyword;
        url += '&order_by=' + orderBy;
        url += '&order=' + order;
        url += '&logincred=' + loginCred;
        //
        window.location.href = url;
    });








    $(document).on('click', '.jsEmployeeTransferLog', function(event) {

        //
        event.preventDefault();
        //
        var employeeId = $(this).data('id') || null;
        //
        Model({
            Id: "jsEmployeeQuickProfileModal",
            Loader: 'jsEmployeeQuickProfileModalLoader',
            Title: 'Employee Transfer History',
            Body: '<div class="container"><div id="jsEmployeeQuickProfileModalBody"></div></div>'
        }, function() {

            if (employeeId) {
                var html = '<div id="jsEmployeeQuickProfileModalMainBody"></div>';
                //
                $('#jsEmployeeQuickProfileModalBody').html(html);
                GetEmployeeTransferDetails(employeeId, 'jsEmployeeQuickProfileModal');
            }
        });
    });



    //

    function GetEmployeeTransferDetails(
        employeeId,
        id
    ) {
        //
        if (employeeId === 0) {
            // flush view
            $('#' + id + 'MainBody').html('');
            return;
        }
        //
        if (isXHRInProgress != null) {
            isXHRInProgress.abort();
        }
        $('.jsIPLoader[data-page="' + (id) + 'Loader"]').show(0);
        //
        isXHRInProgress =
            $.get(window.location.origin + '/employee_management/employer_transfer_log/' + employeeId)
            .done(function(resp) {
                //
                isXHRInProgress = null;
                //
                if (resp.Status === false) {
                    $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
                    $('#' + id + 'MainBody').html(resp.Msg);
                    return;
                }
                $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
                //
                $('#' + id + 'MainBody').html(resp.Data);
            })
            .error(function(err) {
                //
                isXHRInProgress = null;
                $('#' + id).html('Something went wrong while accessing the employee transfer.');
            });
        //
        return '<div id="' + (id) + '"><p class="text-center"><i class="fa fa-spinner fa-spin csF18 csB7" aria-hidden="true"></i></p></div>';
    }



    $(document).on('click', '.jsToggleRow', function(e) {
        e.preventDefault();
        let id = $(this).closest('tr').data('id');
        $('.jsToggleTable' + id).toggle();
    });
</script>