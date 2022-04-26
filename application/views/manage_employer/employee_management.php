<?php 
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
                        <span class="page-heading down-arrow"><?php echo $title; ?></span>
                    </div>
                    <?php  
                        //
                        if (isset($employee_type) && $employee_type == 'offline') {
                            $all = false;
                            $active = false;
                            $offline = true;
                            $terminated = false;
                            $employee_array = $offline_employees;
                        } else if (isset($employee_type) && $employee_type == 'terminated') {
                            $all = false;
                            $active = false;
                            $offline = false;
                            $terminated = true;
                            $employee_array = $terminated_employees;
                        } else if (isset($employee_type) && $employee_type == 'all') {
                            $all = true;
                            $active = false;
                            $offline = false;
                            $terminated = false;
                            $employee_array = $all_company_employees;
                        } else if (isset($employee_type) && $employee_type == 'active') {
                            $all = false;
                            $active = true;
                            $offline = false;
                            $terminated = false;
                            $employee_array = $employees;
                        }  
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
                                                            <option value="all" <?php if ($all) { echo ' selected="selected"'; } ?>>
                                                                All Employees
                                                            </option>
                                                            <option value="active"  <?php if ($active) { echo ' selected="selected"'; } ?>>
                                                                All Active Employees
                                                            </option>
                                                            <option value="terminated" <?php if ($terminated) { echo ' selected="selected"'; } ?>>
                                                                Terminated Employees
                                                            </option> 
                                                            <option value="offline" <?php if ($offline) { echo ' selected="selected"'; } ?>>
                                                                All Onboarding & De-activated Employees
                                                            </option> 
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
                                                    <input type="text" placeholder="Search Employee by Name or Email" name="keyword" class="invoice-fields search-job" value="<?php echo $keyword; ?>">
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
                                                                <option value="sid" <?= $order_by == 'sid' ? 'selected="selected"' : ''?>>Created Date</option>
                                                                <option value="first_name" <?= $order_by == 'first_name' ? 'selected="selected"' : ''?>>Name</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                                        <div class="hr-select-dropdown">
                                                            <select name="order" class="invoice-fields">
                                                                <option value="desc" <?= $order == 'desc' ? 'selected="selected"' : ''?>>Descending</option>
                                                                <option value="asc" <?= $order == 'asc' ? 'selected="selected"' : ''?>>Ascending</option>
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
                                        <div class="col-xs-4 text-left">
                                        <?php if ($session['employer_detail']['access_level_plus']) { ?>
                                            <a class="btn btn-success jsEmployeeQuickProfile" title="Quick View of Employee Profile" placement="top">
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                                Employee Profile
                                            </a>
                                        <?php  } ?>
                                        </div>
                                        <?php if($canEMSPermission) { ?> 
                                            <div class="col-xs-4">
                                                <a class="btn btn-success btn-block" href="<?php echo base_url(); ?>invite_colleagues">+ Add Employee / Team Members</a>
                                            </div>
                                            <div class="col-xs-4">
                                                <?php if ($offline) { ?>
                                                    <a class="btn btn-success btn-block" href="javascript:;" id="ej_controll_activate">Activate Selected</a>
                                                <?php } else { ?>
                                                    <a class="btn btn-danger btn-block" href="javascript:;" id="ej_controll_deactivate">De-activate Selected</a>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table-outer">
                        <div id="show_no_jobs">
                            <?php if (empty($employee_array)) { ?>
                                        <span class="applicant-not-found">Employee not found!</span>
                            <?php } else { ?>
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
                                            <th colspan="3" class="text-center">Actions</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <form method="POST" name="ej_form" id="ej_form">
                                            <?php $sizeof = sizeof($employee_array); ?>
                                            <?php foreach ($employee_array as $employee) { ?>
                                                <tr id="manual_row<?php echo $employee['sid']; ?>">
                                                    <td class="text-center">
                                                        <?php if($employee['is_executive_admin'] == 0) { ?> 
                                                        <label class="control control--checkbox">
                                                            <input name="ej_check[]" type="checkbox" value="<?php echo $employee['sid']; ?>" class="<?= $employee['is_executive_admin'] == 0 ? 'ej_checkbox' : ''; ?>" <?=$employer_id != $employee['sid'] ? '' : 'disabled="true"';?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <?php } ?>
                                                    </td>
                                                    <td width="30%">
                                                        <div class="employee-profile-info">
                                                            <figure>
                                                                <?php if(check_access_permissions_for_view($security_details, 'employee_profile')) { ?>
                                                                            <a href="<?php echo ($session['employer_detail']['access_level_plus'] && $employer_id != $employee['sid']) || $session['employer_detail']['pay_plan_flag'] ? base_url('employee_profile') . '/' . $employee['sid'] : 'javascript:;'; ?>" title="<?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>">
                                                                                <?php if (!empty($employee['profile_picture']) && !preg_match('/pdf|doc|docx|xls|xlxs/i', strtolower($employee['profile_picture']))) { ?>
                                                                                        <img src="<?php echo AWS_S3_BUCKET_URL . $employee['profile_picture']; ?>"> 
                                                                                <?php } else { ?>
                                                                                        <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                                                <?php } ?>
                                                                                
                                                                            </a>
                                                                <?php } else { ?>
                                                                    <?php if (!empty($employee['profile_picture'])) { ?>
                                                                                <img src="<?php echo AWS_S3_BUCKET_URL . $employee['profile_picture']; ?>"> 
                                                                    <?php } else { ?>
                                                                                <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                                    <?php } ?>
                                                                <?php   } ?>
                                                            </figure>
                                                            <div class="text">
                                                                <?php 
                                                                   $middle_initial = !empty($employee['middle_name']) ? ' '.$employee['middle_name'] : ''; 
                                                                   //
                                                                    if (!empty($employee['first_name']) || !empty($employee['last_name'])) {
                                                                        $name = $employee['first_name'] . $middle_initial . ' ' . $employee['last_name'];
                                                                    }
                                                                    if($session['employer_detail']['access_level_plus'] == 1 && !empty($employee['username'])){
                                                                        //
                                                                        $nick_name = !empty($employee['nick_name']) ? '['.$employee['nick_name'].']' : '';    
                                                                        $name .= ' ('.($employee['username']).')'.$nick_name ;
                                                                    }
                                                                    
                                                                ?>
                                                                <?php if(check_access_permissions_for_view($security_details, 'employee_profile')) { ?>
                                                                    <a href="<?php 
                                                                        if($employee['is_executive_admin'] == 0 && ($session['employer_detail']['access_level_plus'] && $employer_id != $employee['sid']) || $session['employer_detail']['pay_plan_flag']){
                                                                            echo base_url('employee_profile') . '/' . $employee['sid']; 
                                                                        } else{
                                                                            echo "javascript:void(0);";
                                                                        }
                                                                        ?>"><?php echo $name; ?></a>
                                                                <?php } else { ?>
                                                                    <?php echo $name; ?>
                                                                <?php } ?>
                                                                    <?php 
                                                                        echo '<br />'.$employee['email'];
                                                                        echo '<br> <b> Employee Status:</b> '. (GetEmployeeStatus($employee['last_status_text'], $employee['active'])); 
                                                                    ?>
                                                                    <br>
                                                                    <?php
                                                                        if($employee['is_executive_admin'] == 1) { 
                                                                            echo '[Executive Admin';
                                                                            echo ($employee['access_level_plus'] && $employee['pay_plan_flag']) ? ' Plus / Payroll' : ($employee['access_level_plus'] ? ' Plus' : ($employee['pay_plan_flag'] ? ' Payroll' : ''));
                                                                            echo ']';
                                                                        } else {
                                                                            echo '['.$employee['access_level'];
                                                                            echo ($employee['access_level_plus'] && $employee['pay_plan_flag']) ? ' Plus / Payroll' : ($employee['access_level_plus'] ? ' Plus' : ($employee['pay_plan_flag'] ? ' Payroll' : ''));
                                                                            echo ']';
                                                                        } 
                                                                    ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="25%">
                                                        <?php   
                                                            if (empty($employee["job_title"])) {
                                                                echo 'No job designation found!';
                                                            } else {
                                                                echo $employee['job_title'];
                                                            }   
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (check_access_permissions_for_view($security_details, 'send_login_email')) { ?>
                                                            <?php   if(($employee['password'] == '' || is_null($employee['password'])) && ($employee['is_executive_admin'] != 1)) { ?>
                                                                        <img src="<?= base_url('assets/manage_admin/images/bulb-red.png') ?>">
                                                            <?php       echo '<br><a href="javascript:;" class="btn btn-success btn-sm send_credentials" title="Send Login Credentials" data-attr="'.$employee['sid'].'">Send Login Email</a>'; ?>
                                                            <?php   } else { ?>
                                                                        <img src="<?= base_url('assets/manage_admin/images/bulb-green.png') ?>">
                                                            <?php   } ?>
                                                        <?php   } ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php 
                                                            $joiningDate = get_employee_latest_joined_date($employee["registration_date"],$employee["joined_at"],"",true);
                                                            //
                                                            if (!empty($joiningDate)) {
                                                                echo $joiningDate;
                                                            } else {
                                                                echo "N/A";
                                                            }
                                                        ?> 
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $rehireDate = get_employee_latest_joined_date("","",$employee["rehire_date"],true);
                                                            //
                                                            if (!empty($rehireDate)) {
                                                                echo $rehireDate;
                                                            } else {
                                                                echo "N/A";
                                                            }
                                                        ?>
                                                    </td>
                                                    <?php if ($all === true || $terminated === true) { ?>
                                                        <td class="text-center">
                                                            <?php 
                                                                //
                                                                if($employee['last_status']){
                                                                    //
                                                                    echo formatDateToDB($employee['last_status']["termination_date"], DB_DATE, DATE);
                                                                    if($employee['last_status']['details']) {
                                                                        echo "<br/> Reason: ".(html_entity_decode($employee['last_status']['details'])); 
                                                                    }
                                                                }
                                                                else {
                                                                    echo "N/A";
                                                                }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if($employer_id != $employee['sid']){ ?>
                                                        <?php if(
                                                            check_access_permissions_for_view($security_details, 'employee_send_documents') || 
                                                            $canAccessDocument
                                                        ) { ?>
                                                        <td class="text-center">
                                                            <?php //if($employee['terminated_status'] == 0) { ?>
                                                            <?php if($ems_status == 1) {?>
                                                                    <?php if($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1 || $canAccessDocument) {?>
                                                                    <a title="Document Management"  data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-sm" href="<?php echo base_url('hr_documents_management/documents_assignment/employee') . '/' . $employee['sid']; ?>">
                                                                        <i class="fa fa-file" aria-hidden="true"></i>
                                                                    </a>
                                                                    <?php } ?>
                                                                    <?php if(checkIfAppIsEnabled('timeoff', FALSE) && $employee['terminated_status'] == 0){ ?>
                                                                        <?php 
                                                                            if(
                                                                                ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) ||
                                                                                (in_array($employee['sid'], $teamMemberIds))
                                                                            ) { 
                                                                        ?>
                                                                            <?php if($employee['is_executive_admin'] == 0) { ?>    
                                                                                <a title="Time Off" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-sm" href="<?php echo base_url('timeoff/create_employee') . '/' . $employee['sid']; ?>">
                                                                                        <i class="fa fa-clock-o"></i>
                                                                                </a>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <?php if($employee['is_executive_admin'] == 0) { ?> 
                                                                        <a title="Send HR Documents" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-sm"  href="<?php echo base_url('send_offer_letter_documents') . '/' . $employee['sid']; ?>">
                                                                            <i class="fa fa-file"></i>
                                                                            <span class="btn-tooltip">HR-Documents</span>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <?php if ($session['employer_detail']['access_level_plus'] && $employee['is_executive_admin'] == 0) { ?>
                                                                    <!-- Employee Quick Profile -->
                                                                    <button class="btn btn-success jsEmployeeQuickProfile" title="Employee Profile Quick View" placement="top" data-id="<?=$employee['sid'];?>">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                    </button>
                                                                <?php } ?>
                                                            <?php //} ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="text-center">
                                                        <?php if($canEMSPermission) { ?> 
                                                            <?php if($employee['is_executive_admin'] == 0) { ?> 
                                                                <?php if ($employee['active'] == 1 && $employee['terminated_status'] == 0 && $employee['archived'] == 0) { ?> 
                                                                    <a title="Deactivate" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-sm" onclick="deactivate_single_employee(<?php echo $employee['sid']; ?>)" href="javascript:;"><img style="width: 17px; height: 17px;" src="<?= base_url('assets/images/deactivate.png') ?>"></a>
                                                                <?php } else if ($employee['active'] == 0 && $employee['terminated_status'] == 0 && $employee['archived'] == 0) { ?>
                                                                    <a title="Archive Employee" data-toggle="tooltip" data-placement="bottom"  class="btn btn-warning btn-sm" onclick="archive_single_employee(<?php echo $employee['sid']; ?>)" href="javascript:;">
                                                                        <i class="fa fa-archive"></i>
                                                                    </a>
                                                                <?php } ?>  
                                                            <?php } ?>  
                                                        <?php } ?>  
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if($canEMSPermission) { ?>   
                                                            <?php if($employee['is_executive_admin'] == 0) { ?>     
                                                                <?php if($employee['terminated_status'] == 0) { ?>         
                                                                    <?php if (!empty($employee['applicant_sid'])) { ?>
                                                                        <a class="btn btn-info btn-sm" onclick="revert_applicant(<?php echo $employee['applicant_sid']; ?>, <?php echo $employee['sid']; ?>)" href="javascript:;">
                                                                            <i class="fa fa-undo"></i>
                                                                            <span class="btn-tooltip">Revert</span>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <?php } else{ echo '<td colspan="'.($sizeof == 1 ? '1' : '3' ).'"></td>'; } ?>
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
                                <a class="btn btn-success" href="<?php echo base_url('archived_employee'); ?>" >Archived Employee</a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                <?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus']) { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('terminated_employee'); ?>" >Terminated Employee</a>
                                <?php } ?>    
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                        <?php   if($employee_type == 'active') { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('employee_management?employee_type=offline'); ?>" >All Onboarding & De-activated Employees</a>
                        <?php   } else { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('employee_management'); ?>" >Active Employee / Team Members</a>
                        <?php   } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function deactivate_single_employee(id) {
        alertify.confirm("Please Confirm Deactivate", "Are you sure you want to Deactivate employee?",
        function () {
            url = "<?= base_url() ?>employee_management/deactivate_single_employee";

            $.post(url, {del_id: id, action: "deactivate_single_employee"})
                .done(function (data) {
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
        function () {
            alertify.error('Cancelled');
        });
    }

    function delete_single_employee(id) {
        alertify.confirm("Please Confirm Delete", "Are you sure you want to Permanently Delete employee?",
        function () {
            url = "<?= base_url() ?>employee_management/delete_single_employee";

            $.post(url, {del_id: id, action: "delete_single_employee"})
                .done(function (data) {
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
        function () {
            alertify.error('Cancelled');
        });
    }

    function archive_single_employee(id) {
        alertify.confirm("Please Confirm Archive", "Are you sure you want to Archive employee?",
        function () {
            url = "<?= base_url() ?>employee_management/archive_single_employee";
            $.post(url, {archive_id: id, action: "archive_single_employee"})
            .done(function (data) {
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
        function () {
            alertify.error('Cancelled');
        });
    }

    function revert_applicant(revert_id, id) {
        alertify.confirm("Please Confirm Revert", "Are you sure you want to Revert employee back to Applicant?",
        function () {
            url = "<?= base_url() ?>employee_management/revert_employee_back_to_applicant";

            $.post(url, {revert_id: revert_id, id: id, action: "revert_applicant"})
                .done(function (data) {

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
        function () {
            alertify.error('Cancelled');
        });
    }

    $(document).ready(function () {
        $('#ej_controll_delete').click(function () {
            var butt = $(this);
            
            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to delete application(s)?",
                    function () {
                        $('#ej_form').append('<input type="hidden" name="delete_contacts" value="true" />');
                        $("#ej_form").submit();
                        alertify.success('Deleted');

                    },
                    function () {
                        alertify.error('Cancelled');
                    });
                }
            } else {
                alertify.alert('Please select application(s) to delete');
            }
        });

        $('#ej_controll_deactivate').click(function () {
            var butt = $(this);
            if ($(".ej_checkbox:checked").size() > 0) {
                
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to de-activate employee(s)?",
                    function () {
                        $('#ej_form').append('<input type="hidden" name="deactivate_employees" value="true" />');
                        $("#ej_form").submit();
                        alertify.success('De-activated');
                    },
                    function () {
                        alertify.error('Cancelled');
                    });
                }
            } else {
                alertify.alert('Please select employee(s) to de-activate');
            }
        });

        $('#ej_controll_activate').click(function () {
            var butt = $(this);
            
            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to activate employee(s)?",
                    function () {
                        $('#ej_form').append('<input type="hidden" name="activate_employees" value="true" />');
                        $("#ej_form").submit();
                        alertify.success('activated');
                    },
                    function () {
                        alertify.error('Cancelled');
                    });
                }
            } else {
                alertify.alert('Please select employee(s) to de-activate');
            }
        });

        $('.selected').click(function () {
            $(this).next().css("display", "block");
        });

        $('.candidate').click(function () {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            status = $(this).find('#status').html();
            id = $(this).parent().find('#id').html();
            url = "<?= base_url() ?>application_tracking_system/update_status";
            
            $.post(url, {"id": id, "status": status, "action": "ajax_update_status_candidate"})
                .done(function (data) {
                    alertify.success("Candidate status updated successfully.");
                });
        });

        $('.candidate').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.applicant').click(function () {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            var status = $(this).find('#status').html();
            var id = $(this).parent().find('#id').html();
            url = "<?= base_url() ?>application_tracking_system/update_status";
            
            $.post(url, {"id": id, "status": status, "action": "ajax_update_status"})
                .done(function (data) {
                    alertify.success("Candidate status updated successfully.");
                });
        });

        $('.applicant').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function () {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function () {
            $(this).parent().css("display", "none");
        });
        $.each($(".selected"), function () {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });

        $('#selectall').click(function (event) {  //on click
            if (this.checked) { // check select status
                $('.ej_checkbox').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"              
                });
            } else {
                $('.ej_checkbox').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                      
                });
            }
        });
    });
    
    $(document).on('click','.send_credentials',function(e) {
        var sid = $(this).attr('data-attr');
        var url = "<?= base_url('employee_management/send_login_credentials') ?>";
        console.log(url);
        console.log(sid);
        alertify.confirm('Confirmation', "Are you sure you want to send login credentials?",
            function () {
                $.ajax({
                    url:url,
                    type:'POST',
                    data:{
                        action: 'sendemail',
                        sid: sid
                    },
                    success: function(data) {
                        if(data == 'success') {
                            alertify.success('Email with Login credentials is sent.');
                        } else {
                            alerty.error('there was error, please try again!');
                        }
                    },
                    error: function(){

                    }
                });
            },
            function () {
                alertify.error('Canceled');
            });
    });

    /**
     * Employee search form
     */
    $('.jsSubmitEmployeeForm').submit(function(event){
        //
        event.preventDefault();
        var url = "<?=rtrim(base_url(), '/');?>/employee_management?";
        //
        var department = $('select[name="department"]').find(':selected').val();
        var orderBy = $('select[name="order_by"]').find(':selected').val();
        var order = $('select[name="order"]').find(':selected').val();
        var employeeType = $('select[name="employee_type"]').find(':selected').val();
        var keyword = $('.search-job').val().trim();

        url += 'employee_type='+employeeType;
        url += '&department='+department;
        url += '&keyword='+keyword;
        url += '&order_by='+orderBy;
        url += '&order='+order;
        //
        window.location.href = url;
    });

</script>