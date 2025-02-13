<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8 field-row">
                                                    <?php $keyword = $this->uri->segment(3) == 'all' ? '' : $this->uri->segment(3); ?>
                                                    <label>Keyword</label>
                                                    <input type="text" name="keyword" id="keyword" value="<?php echo urldecode($keyword); ?>" class="invoice-fields">
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 field-row">
                                                    <?php $contact_name = $this->uri->segment(6) == 'all' ? '' : $this->uri->segment(6); ?>
                                                    <label>Contact Name</label>
                                                    <input type="text" name="contact_name" id="contact_name" value="<?php echo urldecode($contact_name); ?>" class="invoice-fields">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5 field-row">
                                                    <?php $company_name = $this->uri->segment(5) == 'all' ? '' : $this->uri->segment(5); ?>
                                                    <label>Company Name</label>
                                                    <input type="text" name="company-name" id="company-name" value="<?php echo urldecode($company_name); ?>" class="invoice-fields">
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 field-row">
                                                    <?php $status = $this->uri->segment(4) == '' ? 'all' : $this->uri->segment(4); ?>
                                                    <label>Status:</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="active" id="active">

                                                            <option value="all" <?php echo $status == 'all' ? 'selected="selected"' : ''; ?>>All</option>
                                                            <option value="active" <?php echo $status == 'active' ? 'selected="selected"' : ''; ?>>Active</option>
                                                            <option value="leave" <?php echo $status == 'leave' ? 'selected="selected"' : ''; ?>>Leave</option>
                                                            <option value="suspended" <?php echo $status == 'suspended' ? 'selected="selected"' : ''; ?>>Suspended</option>
                                                            <option value="retired" <?php echo $status == 'retired' ? 'selected="selected"' : ''; ?>>Retired</option>
                                                            <option value="rehired" <?php echo $status == 'rehired' ? 'selected="selected"' : ''; ?>>Rehired</option>
                                                            <option value="deceased" <?php echo $status == 'deceased' ? 'selected="selected"' : ''; ?>>Deceased</option>
                                                            <option value="terminated" <?php echo $status == 'terminated' ? 'selected="selected"' : ''; ?>>Terminated</option>
                                                            <option value="inactive" <?php echo $status == 'inactive' ? 'selected="selected"' : ''; ?>>Inactive</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                    <label>&nbsp;</label>
                                                    <a id="search_btn" href="#" class="btn btn-success btn-block" style="padding: 9px;">Search</a>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                    <label>&nbsp;</label>
                                                    <a href="<?php echo base_url('manage_admin/employers'); ?>" class="btn black-btn btn-block" style="padding: 9px;">Reset Search</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <div class="hr-items-count">
                                                <strong class="employerCount"><?php echo $total_employers; ?></strong> Employers
                                            </div>
                                            <?php if (check_access_permissions_for_view($security_details, 'show_employer_multiple_actions')) { ?>
                                                <?php $this->load->view('templates/_parts/admin_manage_multiple_actions'); ?>
                                            <?php } ?>
                                            <?php echo $links; ?>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <input type="checkbox" id="check_all">
                                                                </th>
                                                                <th class="text-center">ID</th>
                                                                <th>Username</th>
                                                                <th>Email</th>
                                                                <th>Contact Name</th>
                                                                <th>Additional<br>Information</th>
                                                                <th>Company Name</th>
                                                                <?php $function_names = array('show_employer_multiple_actions', 'employerLogin', 'edit_employers'); ?>
                                                                <?php if (check_access_permissions_for_view($security_details, 'edit_employers')) { ?>
                                                                    <th class="last-col" width="1%" colspan="5">Actions</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($employers)) {

                                                                $employeeIds = array_column($employers, 'sid');
                                                                $doNotHireRecords = checkDontHireText($employeeIds);

                                                            ?>
                                                                <?php
                                                                foreach ($employers as $key => $value) {
                                                                    $doNotHireWarning = doNotHireWarning($value['sid'], $doNotHireRecords, 14);

                                                                ?>
                                                                    <tr id="parent_<?= $value['sid'] ?>">
                                                                        <td class="<?php echo $doNotHireWarning['row']; ?>"><input type="checkbox" name="checkit[]" value="<?php echo $value['sid']; ?>" class="my_checkbox"></td>
                                                                        <td class="text-center <?php echo $doNotHireWarning['row']; ?>">
                                                                            <div class="employee-profile-info">
                                                                                <figure>
                                                                                    <img class="profile-img-responsive" src="<?= getImageURL($value['profile_picture']); ?>" alt="Employee" />
                                                                                </figure>
                                                                            </div>
                                                                            <b><?php echo $value['sid']; ?></b>
                                                                        </td>
                                                                        <td class="<?php echo $doNotHireWarning['row']; ?>">
                                                                            <?php
                                                                            if (empty($value['username'])) {
                                                                                echo 'Employee Onboarding';
                                                                            } else {
                                                                                echo $value['username'];
                                                                            }
                                                                            //
                                                                            echo '<br> <b> Employee Status:</b> ' . (GetEmployeeStatus($value['last_status_text'], $value['active']));
                                                                            //
                                                                            echo '<br> <b> Access Level:</b> ' . ucwords($value['access_level']);
                                                                            echo ($value['access_level_plus'] && $value['pay_plan_flag']) ? ' Plus / Payroll' : ($value['access_level_plus'] ? ' Plus' : ($value['pay_plan_flag'] ? ' Payroll' : '')); ?>
                                                                            <!-- Languages -->
                                                                            <br />
                                                                            <strong>I Speak:</strong> <?= showLanguages($value['languages_speak']); ?>
                                                                            <br />
                                                                            <?php
                                                                            if (!empty($value['username'])) {
                                                                                echo '<br><a href="javascript:;" class="btn btn-success btn-sm send_credentials" title="Send Login Credentials" data-attr="' . $value['sid'] . '" data-name="' . $value['company_name'] . '">Send Login Email</a>';
                                                                            }
                                                                            ?>
                                                                            <br>

                                                                            <?php echo $doNotHireWarning['message']; ?>

                                                                        </td>
                                                                        <td class="<?php echo $doNotHireWarning['row']; ?>">
                                                                            <?php echo $value['email'] . '<br>' . (
                                                                                $value["PhoneNumber"] ? '<b>Primary Number: </b>' . $value['PhoneNumber'] . "<br />" : ''
                                                                            ) . '<b>Title:</b> ' . ucwords($value['job_title']); ?>

                                                                            <?php
                                                                            if (!empty($value['complynet_job_title'])) { ?>
                                                                                <br />
                                                                                <b>ComplyNet Job Title:</b> <?php echo $value['complynet_job_title']; ?>
                                                                            <?php } ?>
                                                                            <?php
                                                                            if (!empty($value['title'])) { ?>
                                                                                <br />
                                                                                <b>LMS Job Title:</b> <?php echo $value['title']; ?>
                                                                            <?php } ?>
                                                                            <br>
                                                                            <b>System Date: </b><?php echo date_with_time($value['system_user_date']); ?>
                                                                        </td>
                                                                        <td class="<?php echo $doNotHireWarning['row']; ?>">
                                                                            <?php
                                                                            $middle_initial = !empty($value['middle_name']) ? ' ' . $value['middle_name'] : '';
                                                                            echo ucwords($value['first_name'] . $middle_initial . ' ' . $value['last_name']);
                                                                            ?>
                                                                            <br />
                                                                            <b>Nick Name: </b>
                                                                            <?php
                                                                            if (!empty($value['nick_name'])) {
                                                                                echo $value['nick_name'];
                                                                            } else {
                                                                                echo "N/A";
                                                                            }
                                                                            ?>
                                                                        </td>

                                                                        <td class="<?php echo $doNotHireWarning['row']; ?>">
                                                                            <b>Joining Date: </b><br><?php
                                                                                                        $joiningDate = get_employee_latest_joined_date($value["registration_date"], $value["joined_at"], "", true);
                                                                                                        //
                                                                                                        if (!empty($joiningDate)) {
                                                                                                            echo $joiningDate;
                                                                                                        } else {
                                                                                                            echo "N/A";
                                                                                                        }
                                                                                                        ?>
                                                                            <?php
                                                                            $rehireDate = get_employee_latest_joined_date("", "", $value["rehire_date"], true);
                                                                            //
                                                                            if (!empty($rehireDate)) {
                                                                                echo '<p></p>
                                                                                                    <b>Rehire Date: </b><br>';
                                                                                echo $rehireDate;
                                                                            }
                                                                            ?>
                                                                            <?php if ($value["employment_date"]) : ?>
                                                                                <p></p>
                                                                                <b>Starting Date as a Full-Time Employee: </b><br />
                                                                                <?= formatDateToDB(
                                                                                    $value["employment_date"],
                                                                                    DB_DATE,
                                                                                    DATE
                                                                                ); ?>
                                                                            <?php endif; ?>

                                                                            <?php
                                                                            // Termination date
                                                                            if ($value['last_status']) {
                                                                                echo '<p></p>';
                                                                                echo '<b>Termination Date: </b><br>';
                                                                                echo formatDateToDB($value['last_status']["termination_date"], DB_DATE, DATE);
                                                                            }
                                                                            ?>
                                                                            <p></p>
                                                                            <?php
                                                                            if (isset($value["transfer_date"]) && !empty($value["transfer_date"])) {
                                                                                echo "<b>Transfer Date: </b><br />" . (formatDateToDB($value['transfer_date'], DB_DATE, DATE));
                                                                            }
                                                                            ?>
                                                                            <p></p>
                                                                            <?php if (!empty($value['departments'])) { ?> <b>Departments:<br> </b> <?php echo implode(", ", array_unique($value['departments']));
                                                                                                                                                } ?>
                                                                            <p></p>
                                                                            <?php if (!empty($value['departments'])) { ?><b>Teams:</b><br> <?php echo implode(", ", $value['teams']);
                                                                                                                                        } ?>
                                                                            <?php
                                                                            $isOnComplyNet = getComplyNetEmployeeCheck($value, 1, 1, true);

                                                                            if (isset($isOnComplyNet["errors"])) {
                                                                                echo "<b>ComplyNet: </b><br />";
                                                                                echo implode("<br>", $isOnComplyNet["errors"]);
                                                                            } else {
                                                                                //
                                                                                if (!empty($isOnComplyNet)) {
                                                                                    echo '<b>ComplyNet Status: </b><br>' . $isOnComplyNet;
                                                                                }
                                                                                if (!in_array(getComplyNetEmployeeCheck($value, 1, 1, false), ['Not on ComplyNet', ''])) {
                                                                                    echo '<b>ComplyNet Link: </b><br><a href="' . (base_url('cn/redirect/' . ($value['sid']) . '')) . '" class="btn btn-success">Show Page</a><br />';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </td>


                                                                        <td class="<?php echo $doNotHireWarning['row']; ?>"><?php echo ucwords($value['company_name']); ?>
                                                                            <?php if ($value['password'] == '' || is_null($value['password'])) { ?>
                                                                                <img class="img-responsive" src="<?= base_url('assets/manage_admin/images/bulb-red.png') ?>">
                                                                            <?php   } else { ?>
                                                                                <img class="img-responsive" src="<?= base_url('assets/manage_admin/images/bulb-green.png') ?>">
                                                                            <?php   } ?>
                                                                        </td>
                                                                        <td class="<?php echo $doNotHireWarning['row']; ?>">
                                                                            <?php if (isTranferredEmployee($value['sid'])) { ?>
                                                                                <button class="btn btn-success btn-sm jsEmployeeTransferLog" title="View Transfer Log" placement="top" data-id="<?php echo $value['sid']; ?>" data-original-title="View Transfer Detail">
                                                                                    <i class="fa fa-history" aria-hidden="true"></i>
                                                                                </button>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <?php if (check_access_permissions_for_view($security_details, 'edit_employers')) { ?>
                                                                            <td class="<?php echo $doNotHireWarning['row']; ?>"><?php echo anchor('manage_admin/employers/edit_employer/' . $value['sid'],  '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm" title="Edit Employer"'); ?></td>
                                                                        <?php   } ?>

                                                                        <?php if (check_access_permissions_for_view($security_details, 'show_employer_multiple_actions')) { ?>
                                                                            <td class="<?php echo $doNotHireWarning['row']; ?>"><a href="javascript:;" class="btn btn-danger btn-sm" title="Delete Employer" onclick="deleteEmployer(<?= $value['sid'] ?>)"><i class="fa fa-times"></i></a>
                                                                                <!--<input class="hr-delete-btn" type="button" id="<?= $value['sid'] ?>" value="Delete" onclick="return deleteEmployer(this.id)" name="button">-->
                                                                            </td>
                                                                        <?php } ?>

                                                                        <?php if (check_access_permissions_for_view($security_details, 'show_employer_multiple_actions')) { ?>
                                                                            <td class="<?php echo $doNotHireWarning['row']; ?>">
<<<<<<< HEAD
                                                                                <?php 
                                                                                if ($value['active']) {
                                                                                    //echo '<a href="javascript:;" class="btn btn-warning btn-sm deactive_employee" id="' . $value['sid'] . '" title="Disable Employee" data-attr="' . $value['sid'] . '">ddd<i class="fa fa-ban"></i></a>';
                                                                                    echo '<a href="'.base_url('manage_admin/employers/EmployeeStatusDetail/'.$value['sid']).'" class="btn btn-warning btn-sm"  title="Disable Employee"><i class="fa fa-ban"></i></a>';
                                                                                } else {
                                                                                    //echo '<a href="javascript:;" class="btn btn-success btn-sm active_employee" id="' . $value['sid'] . '" title="Enable Employee" data-attr="' . $value['sid'] . '">gg<i class="fa fa-shield"></i></a>';
                                                                                    echo '<a href="'.base_url('manage_admin/employers/EmployeeStatusDetail/'.$value['sid']).'" class="btn btn-success btn-sm" title="Enable Employee"><i class="fa fa-shield"></i></a>';
                                                                                } 
                                                                                ?>
=======
                                                                                <?php if ($value['active']) {
                                                                                    echo '<a href="javascript:;" class="btn btn-warning btn-sm deactive_employee" id="' . $value['sid'] . '" title="Disable Employee" data-attr="' . $value['sid'] . '"><i class="fa fa-ban"></i></a>';
                                                                                } else {
                                                                                    echo '<a href="javascript:;" class="btn btn-success btn-sm active_employee" id="' . $value['sid'] . '" title="Enable Employee" data-attr="' . $value['sid'] . '"><i class="fa fa-shield"></i></a>';
                                                                                } ?>
                                                                                <!--    
                                                                                                                                                                  
                                                                                <input class="hr-delete-btn" type="button" id="<?= $value['sid'] ?>" value="Delete" onclick="return deleteEmployer(this.id)" name="button">-->

                                                                               <!-- <a href="<?php //echo base_url('manage_admin/employers/EmployeeStatusDetail/'.$value['sid']);?>" class="btn btn-warning change_status">Change Status</a> -->

>>>>>>> feature/employeeStatus-v1
                                                                            </td>
                                                                        <?php   } ?>

                                                                        <?php if (check_access_permissions_for_view($security_details, 'employerlogin')) { ?>
                                                                            <td class="<?php echo $doNotHireWarning['row']; ?>"><input class="btn btn-success btn-sm" type="button" id="<?= $value['sid'] ?>" onclick="return employerLogin(this.id)" value="Login">
                                                                            </td>
                                                                        <?php } ?>

                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else {  ?>
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="no-data">No Employers Found</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="execute" value="multiple_action">
                                                    <input type="hidden" id="type" name="type" value="employer">
                                                </form>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="hr-items-count">
                                                <strong class="employerCount"><?php echo $total_employers; ?></strong> Employers
                                                <p><?php if ($total_rows != 0) {
                                                        echo 'Displaying <b>' . $offset . ' - ' . $end_offset . '</b> of ' . $total_rows . ' records';
                                                    } ?></p>
                                            </div>
                                            <?php echo $links; ?>
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
<script>
    $(document).keypress(function(e) {
        if (e.which == 13) { // enter pressed
            $('#search_btn').click();
        }
    });

    $(document).on('click', '.send_credentials', function(e) {
        var sid = $(this).attr('data-attr');
        var name = $(this).attr('data-name');
        console.log('ID: ' + sid);
        console.log('Name: ' + name);
        var url = "<?= base_url() ?>manage_admin/employers/send_login_credentials";
        alertify.confirm('Confirmation', "Are you sure you want to send Email to Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'sendemail',
                        sid: sid,
                        name: name
                    },
                    success: function(data) {
                        if (data == 'success') {
                            alertify.success('Email with generate password link is sent.');
                        } else {
                            alerty.error('there was error');
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

    $(document).on('click', '.deactive_employee', function(e) {
        var id = $(this).attr('data-attr');
        var url = "<?= base_url() ?>manage_admin/employers/change_status";
        alertify.confirm('Confirmation', "Are you sure you want to deactivate this Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'deactive',
                        sid: id
                    },
                    success: function() {
                        alertify.success('Employee have been De-Activated.');
                        $('#' + id).removeClass('deactive_employee');
                        $('#' + id).removeClass('btn-warning');
                        $('#' + id).addClass('active_employee');
                        $('#' + id).addClass('btn-success');
                        $('#' + id).attr('title', 'Enable Employee');
                        $('#' + id).find('i').removeClass('fa-ban');
                        $('#' + id).find('i').addClass('fa-shield');
                        //                        window.location.href = '<?php //echo current_url()
                                                                            ?>//';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    $(document).on('click', '.active_employee', function(e) {
        var id = $(this).attr('data-attr');
        var url = "<?= base_url() ?>manage_admin/employers/change_status";
        alertify.confirm('Confirmation', "Are you sure you want to activate this Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'active',
                        sid: id
                    },
                    success: function() {
                        alertify.success('Employee have been Activated.');
                        $('#' + id).removeClass('active_employee');
                        $('#' + id).removeClass('btn-success');
                        $('#' + id).addClass('deactive_employee');
                        $('#' + id).addClass('btn-warning');
                        $('#' + id).attr('title', 'Disable Employee');
                        $('#' + id).find('i').removeClass('fa-shield');
                        $('#' + id).find('i').addClass('fa-ban');
                        //                        window.location.href = '<?php //echo current_url()
                                                                            ?>//';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    $(document).ready(function() {
        $('#keyword').on('keyup', update_url);
        $('#keyword').on('blur', update_url);
        $('#contact_name').on('keyup', update_url);
        $('#contact_name').on('blur', update_url);
        $('#company-name').on('keyup', update_url);
        $('#company-name').on('blur', update_url);
        $('#active').on('change', update_url);
        $('#search_btn').on('click', function(e) {
            e.preventDefault();
            update_url();
            window.location = $(this).attr('href').toString();
        });
    });

    function update_url() {
        var url = '<?php echo base_url('manage_admin/employers/'); ?>';
        var keyword = $('#keyword').val();
        var company_name = $('#company-name').val();
        var status = $('#active').val();
        var contact_name = $('#contact_name').val();

        keyword = keyword == '' ? 'all' : keyword;
        company_name = company_name == '' ? 'all' : company_name;
        contact_name = contact_name == '' ? 'all' : contact_name;
        url = url + '/' + encodeURIComponent(keyword) + '/' + status + '/' + encodeURIComponent(company_name) + '/' + encodeURIComponent(contact_name);
        $('#search_btn').attr('href', url);
    }

    function employerLogin(userId) {
        url_to = "<?= base_url() ?>manage_admin/employers/employer_login";
        $.post(url_to, {
                action: "login",
                sid: userId
            })
            .done(function() {
                window.open("<?= base_url('dashboard') ?>", '_blank');
            });
    }

    function deleteEmployer(id) {
        url = "<?= base_url() ?>manage_admin/employers/employer_task";
        alertify.confirm('Confirmation', "Are you sure you want to delete this Employee?",
            function() {
                $.post(url, {
                        action: 'delete',
                        sid: id
                    })
                    .done(function(data) {
                        employerCounter = parseInt($(".employerCount").html());
                        employerCounter--;
                        $(".employerCount").html(employerCounter);
                        $("#parent_" + id).remove();
                        alertify.success('Selected employee have been Deleted.');
                    });
            },
            function() {
                alertify.error('Canceled');
            });
    }

    function deactive_employee(id) {
        var url = "<?= base_url() ?>manage_admin/employers/change_status";
        alertify.confirm('Confirmation', "Are you sure you want to deactivate this Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'deactive',
                        sid: id
                    },
                    success: function() {
                        alertify.success('Employee have been De-Activated.');
                        console.log(url);
                        return false;
                        window.location.href = '<?php current_url() ?>';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    }

    function active_employee(id) {
        var url = "<?= base_url() ?>manage_admin/employers/change_status";
        alertify.confirm('Confirmation', "Are you sure you want to activate this Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'active',
                        sid: id
                    },
                    success: function() {
                        alertify.success('Employee have been Activated.');
                        window.location.href = '<?php current_url() ?>';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    }

    $('[data-placement="top"]').popover({
        placement: 'top',
        trigger: 'hover'
    });
</script>
<!--  -->
<link rel="stylesheet" href="<?= base_url("assets/css/SystemModel.css"); ?>">
<script src="<?= base_url("assets/js/SystemModal.js"); ?>"></script>

<script>
    // ComplyNet
    $(document).on("click", ".jsAddEmployeeToComplyNet", function(event) {
        //
        event.preventDefault();
        //
        let employeeId = $(this).data("id");
        let companyId = $(this).data("cid");

        //
        return alertify.confirm(
            "Are you sure you want to sync this employee with ComplyNet.<br />In case the employee is not found on ComplyNet, the system will add the employee to ComplyNet.",
            function() {
                addEmployeeToComplyNet(companyId, employeeId)
            }
        );
    });

    function addEmployeeToComplyNet(companyId, employeeId) {
        //

        Modal({
                Id: "jsModelEmployeeToComplyNet",
                Title: "Add Employee To ComplyNet",
                Body: '<div class="container"><div id="jsModelEmployeeToComplyNetBody"><p class="alert alert-info text-center">Please wait while we are syncing employee with ComplyNet. It may take a few moments.</p></div></div>',
                Loader: "jsModelEmployeeToComplyNetLoader",
            },
            function() {
                //
                $.post(window.location.origin + "/cn/" + companyId + "/employee/sync", {
                        employeeId: employeeId,
                        companyId: companyId,
                    })
                    .success(function(resp) {
                        //
                        if (resp.hasOwnProperty("errors")) {
                            //
                            let errors = '';
                            errors += '<strong class="text-danger">';
                            errors += '<p><em>In order to sync employee with ComplyNet the following details are required.';
                            errors += ' Please fill these details from employee\'s profile.</em></p><br />';
                            errors += resp.errors.join("<br />");
                            errors += '</strong>';
                            //
                            $('#jsModelEmployeeToComplyNetBody').html(errors);
                        } else {
                            $('#jsModelEmployeeToComplyNet .jsModalCancel').trigger('click');
                            alertify.alert(
                                'Success',
                                'You have successfully synced the employee with ComplyNet',
                                window.location.reload
                            );
                        }
                    })
                    .fail(window.location.reload);
                ml(false, "jsModelEmployeeToComplyNetLoader");
            }
        );
    }





    var isXHRInProgress = null;

    $(document).on('click', '.jsEmployeeTransferLog', function(event) {

        //
        event.preventDefault();
        //
        var employeeId = $(this).data('id') || null;
        //
        Modal({
            Id: "jsEmployeeQuickProfileModal",
            Loader: 'jsEmployeeQuickProfileModalLoader',
            Title: 'Employee Transfer History',
            Body: '<div class="container"><div id="jsEmployeeQuickProfileModalBody"></div></div>'
        }, function() {

            if (employeeId) {
                var html = '<div id="jsEmployeeQuickProfileModalMainBody"></div>';
                //
                $('#jsEmployeeQuickProfileModalBody').html(html);
                GetSpecificEmployeeDetails(employeeId, 'jsEmployeeQuickProfileModal');
            }
        });
    });



    //

    function GetSpecificEmployeeDetails(
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
            $.get(window.location.origin + '/employer_transfer_log/' + employeeId)
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