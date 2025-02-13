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
                    
                    <!--  -->
                    <div class="filter-form-wrp">
                        <span>Search Employee(s):</span>
                        <div class="tracking-filter">
                            <form action="" method="GET">
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
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                            <div class="hr-select-dropdown">
                                                <select name="order_by" class="invoice-fields">
                                                    <option value="sid" <?= $order_by == 'sid' ? 'selected="selected"' : ''?>>Created Date</option>
                                                    <option value="first_name" <?= $order_by == 'first_name' ? 'selected="selected"' : ''?>>Name</option>
                                                    <option value="termination_date" <?= $order_by == 'termination_date' ? 'selected="selected"' : ''?>>Termination Date</option>
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

                    <!--
                        <div class="row">
                            <div class="col-xs-4"></div>
                            <div class="col-xs-4">
                                <a class="btn btn-success btn-block" href="javascript:;" id="ej_controll_activate">Activate Selected</a>
                            </div>
                            <div class="col-xs-4">
                                <a class="btn btn-danger btn-block" href="javascript:;" id="ej_controll_deactivate">De-activate Selected</a>
                            </div>
                        </div>
-->

                    </div>
                    <?php   $employee_array = $employees; ?>
                    <div class="table-responsive table-outer">
                        <div id="show_no_jobs">
                            <?php if (empty($employee_array)) { ?>
                                <span class="applicant-not-found">No Terminated Employees found!</span>
                            <?php } else { ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                <label class="control control--checkbox checkallbtn">
                                                    <input type="checkbox" id="selectall" disabled>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </th>
                                            <th width="30%">Employees</th>
                                            <th width="25%">Designation</th>
                                            <th class="text-center">Access Level</th>
                                            <th class="text-center">Starting Date</th>
                                            <th class="text-center">Termination Date</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                    <form method="POST" name="ej_form" id="ej_form">
                                        <?php foreach ($employee_array as $employee) { ?>
                                            <tr id="manual_row<?php echo $employee["sid"]; ?>">

                                                <td class="text-center">
                                                    <label class="control control--checkbox">
                                                        <input name="ej_check[]" type="checkbox" value="<?php echo $employee['sid']; ?>" class="ej_checkbox" <?=$employer_id != $employee['sid'] ? '' : 'disabled="true"';?> disabled>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
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
                                                                                } else {
                                                                                    echo $employee["access_level"];
                                                                                } ?>
                                                </td>
                                                <td class="text-center"><?php 
                                                
                                                if(isset($employee["joined_at"])) {
                                                    echo formatDateToDB($employee['joined_at'], DB_DATE, DATE);
                                                }
                                                 ?></td>
                                                <td class="text-center"><?php echo formatDateToDB($employee["termination_date"], DB_DATE, DATE); ?></td>
<!--                                                <td class="text-center">-->
                                                    <!-- <a class="action-btn"  href="<?php //echo base_url('send_offer_letter_documents'); ?>/<?php //echo $employee["sid"]; ?>">
                                                            <i class="fa fa-file"></i>
                                                            <span class="btn-tooltip">HR-Documents</span>
                                                        </a>-->
<!--                                                    <a title="Delete" data-toggle="tooltip" data-placement="bottom"  class="btn btn-danger btn-sm" onclick="delete_single_employee(--><?php //echo $employee["sid"]; ?>
<!--                                                        <i class="fa fa-remove"></i>-->
<!--                                                    </a>-->
<!--                                                </td>-->
<!--                                                <td class="text-center">-->
<!--                                                    <a title="Re Activate Employee" data-toggle="tooltip" data-placement="bottom" class="btn btn-info btn-sm" onclick="restore_employee(--><?php //echo $employee["sid"]; ?><!--)" href="javascript:;">
//                                                        <i class="fa fa-undo"></i>
//                                                    </a>
//                                                </td>-->
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
                        <a class="btn btn-success" href="<?php echo base_url('archived_employee'); ?>" >Archived Employee / Team Members</a>
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
            alertify.alert('Please select employee(s) to activate');
        }
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
    function restore_employee(id) {
        alertify.confirm("Please Confirm Re-Activation", "Are you sure you want to Re-Activate employee?",
                function () {
                    var url = "<?= base_url() ?>employee_management/revert_termination_single_employee";
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
                                    $('#show_no_jobs').html('<span class="applicant-not-found">No Terminated Employees found!</span>');
                                }
                                alertify.notify(data, 'success');
                            });
                },
                function () {
                    alertify.error('Cancelled');
                });
    }
</script>