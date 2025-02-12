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
                    <?php   $employee_array = $employees; ?> 
                    <div class="table-responsive table-outer">
                        <div id="show_no_jobs">
                            <?php if (empty($employee_array)) { ?>
                                <span class="applicant-not-found">No Archived Employees found!</span>
                            <?php } else { ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="30%">Employees</th>
                                            <th width="25%">Designation</th>
                                            <th class="text-center">Access Level</th>
                                            <th class="text-center">Starting Date</th>
                                            <th colspan="2" class="text-center">Actions</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                    <form method="POST" name="ej_form" id="ej_form">
                                        <?php foreach ($employee_array as $employee) { ?>
                                            <tr id="manual_row<?php echo $employee["sid"]; ?>">
                                                <td width="30%">
                                                    <div class="employee-profile-info">
                                                        <figure>
                                                            <a href="<?php echo base_url('employee_profile') . '/' . $employee["sid"]; ?>" title="<?php echo $employee["first_name"] . ' ' . $employee["last_name"]; ?>">
                                                                <?php if (!empty($employee['profile_picture'])) { ?>
                                                                    <img src="<?php echo AWS_S3_BUCKET_URL . $employee['profile_picture']; ?>"> 
                                                                <?php } else { ?>
                                                                    <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                                <?php } ?>
                                                            </a>
                                                        </figure>
                                                        <div class="text">
                                                            <?php   if (!empty($employee['first_name']) || !empty($employee['last_name'])) {
                                                                        $name = $employee['first_name'] . ' ' . $employee['last_name'];
                                                                    } else {
                                                                        $name = $employee['email'];
                                                                    } ?>
                                                            <a href="<?php echo base_url('employee_profile') . '/' . $employee["sid"]; ?>"><?php echo $name; ?></a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="25%">
                                                    <?php   if (empty($employee["job_title"])) {
                                                                echo "No job designation found!";
                                                            } else {
                                                                echo $employee["job_title"];
                                                            } ?>
                                                </td>
                                                <td class="text-center"><?php   if($employee["is_executive_admin"] == 1) { 
                                                            echo 'Executive Admin';
                                                            echo ($employee['access_level_plus'] && $employee['pay_plan_flag']) ? ' Plus / PayPlan' : ($employee['access_level_plus'] ? ' Plus' : ($employee['pay_plan_flag'] ? ' PayPlan' : ''));
                                                        } else {
                                                            echo $employee["access_level"];
                                                            echo ($employee['access_level_plus'] && $employee['pay_plan_flag']) ? ' Plus / PayPlan' : ($employee['access_level_plus'] ? ' Plus' : ($employee['pay_plan_flag'] ? ' PayPlan' : ''));
                                                        } ?>
                                                </td>
                                                <td class="text-center"><?php echo my_date_format($employee["registration_date"]); ?></td>
<!--                                                <td class="text-center">-->
                                                    <!-- <a class="action-btn"  href="<?php //echo base_url('send_offer_letter_documents'); ?>/<?php //echo $employee["sid"]; ?>">
                                                            <i class="fa fa-file"></i>
                                                            <span class="btn-tooltip">HR-Documents</span>
                                                        </a>-->
<!--                                                    <a title="Delete" data-toggle="tooltip" data-placement="bottom"  class="btn btn-danger btn-sm" onclick="delete_single_employee(--><?php //echo $employee["sid"]; ?>
<!--                                                        <i class="fa fa-remove"></i>-->
<!--                                                    </a>-->
<!--                                                </td>-->
                                                <td class="text-center">                                                
                                                <a title="Re Activate Employee" data-toggle="tooltip" data-placement="bottom" class="btn btn-info btn-sm" href="<?= base_url() ?>employee_status/<?php echo $employee["sid"]; ?>">
                                                        <i class="fa fa-undo"></i>
                                                    </a>                                                  
                                                <!--
                                                   <a title="Re Activate Employee" data-toggle="tooltip" data-placement="bottom" class="btn btn-info btn-sm" onclick="restore_employee(<?php //echo $employee["sid"]; ?>)" href="javascript:;">
                                                        <i class="fa fa-undo"></i>
                                                    </a>

                                                    -->

                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </form>
                                    </tbody>
                                </table>
                            <?php } ?> 
                            <input type="hidden" name="countainer_count" id="countainer_count" value="<?php echo sizeof($employee_array); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                        <a class="btn btn-success" href="<?php echo base_url('employee_management'); ?>" >Active Employee / Team Members</a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                        <a class="btn btn-success" href="<?php echo base_url('terminated_employee'); ?>" >Terminated Employee / Team Members</a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                        <a class="btn btn-success" href="<?php echo base_url('employee_management?employee_type=offline'); ?>" >All Onboarding & De-activated Employees</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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
                                    $('#show_no_jobs').html('<span class="applicant-not-found">No Archived Employees found!</span>');
                                }
                                alertify.notify(data, 'success');
                            });
                },
                function () {
                    alertify.error('Cancelled');
                });
    }
    
    function restore_employee(id) {
        alertify.confirm("Please Confirm Re-Activation", "Are you sure you want to Re-Activate employee?",
                function () {
                    url = "<?= base_url() ?>employee_management/reactivate_single_employee";
                    $.post(url, {id: id, action: "restore_employee"})
                            .done(function (data) {
                                //console.log(data);
                                $('#manual_row' + id).hide();
                                var total_rows = $('#countainer_count').val();
                                //console.log(total_rows);
                                total_rows = total_rows - 1;
                                $('#countainer_count').val(total_rows);
                                if (total_rows <= 0) {
                                    show_no_jobs
                                    $('#hide_del_row').hide();
                                    $('#show_no_jobs').html('<span class="applicant-not-found">No Archived Employees found!</span>');
                                }
                                alertify.notify(data, 'success');
                            });
                },
                function () {
                    alertify.error('Cancelled');
                });
    }
</script>