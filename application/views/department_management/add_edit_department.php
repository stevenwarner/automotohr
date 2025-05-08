<style>
    .input_hint {
        color: #959595;
        font-weight: 600;
        font-style: italic;
        /*margin: 0 0 30px 0;*/
    }

    .help {
        cursor: pointer;
    }
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                        <?php $this->load->view('main/manage_ems_left_view'); ?>
                    <?php } else { ?>
                        <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                    <?php } ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('department_management'); ?>"><i class="fa fa-chevron-left"></i>Department Management</a>
                                    <?php echo !isset($department) ? 'Add Department' : 'Edit Department'; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="upload-new-doc-heading">
                                        <i class="fa fa-university"></i>
                                        <?php echo $title; ?>
                                    </div>
                                    <p class="upload-file-type">You can easily create departments for your Company</p>
                                    <div class="form-wrp">
                                        <form id="form_add_edit_department_info" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" name="perform_action" value="<?php echo $perform_action; ?>" />

                                            <div class="form-group autoheight">
                                                <label for="name">Department Name<span class="staric">*</span></label>
                                                <input type="text" name="name" class="form-control" value="<?php echo isset($department['name']) ? $department['name'] : ''; ?>">
                                                <?php echo form_error('name'); ?>
                                            </div>
                                            <div class="form-group autoheight">
                                                <?php $description = isset($department['description']) ? html_entity_decode($department['description']) : ''; ?>
                                                <label for="description">Department Description</label>
                                                <textarea name="description" cols="40" rows="10" class="form-control ckeditor" style="visibility: hidden; display: none;"><?php echo $description; ?></textarea>
                                                <?php echo form_error('description'); ?>
                                            </div>
                                            <!-- <div class="form-group autoheight">
                                                <div class="row">
                                                    <?php $status = isset($department['status']) ? $department['status'] : 1; ?>
                                                    <div class="col-lg-12 mb-2"><label>Status</label></div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label class="control control--radio">
                                                            Active
                                                            <input type="radio" name="status" value="1" <?php echo $status == 1 ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label class="control control--radio">
                                                            Inactive
                                                            <input type="radio" name="status" value="0" <?php echo $status == 0 ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="form-group autoheight">
                                                <label for="name">Select Supervisor(s)<span class="staric">* </span><i
                                                        class="fa fa-question-circle-o help"
                                                        src="supervisor_hint" action="show"></i></label>
                                                <p class="input_hint" id="supervisor_hint"><?php echo getUserHint('department_supervisor_hint'); ?></p>
                                                <div class="">
                                                    <select name="supervisor[]" class="invoice-fields" id="supervisor_id" multiple="true">
                                                        <?php foreach ($employees as $key => $employee): ?>
                                                            <option value="<?php echo $employee['sid'] ?>" <?php echo isset($department['supervisor']) && in_array($employee['sid'], explode(',', $department['supervisor']))  ? 'selected="selected"' : ''; ?>>
                                                                <?php echo remakeEmployeeName($employee); ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <span id="add_supervisor_error" class="text-danger person_error"></span>
                                                </div>
                                            </div>
                                            <?php if (checkIfAppIsEnabled('timeoff')) { ?>
                                                <div class="form-group autoheight">
                                                    <label for="name">Approver(s) <i
                                                            class="fa fa-question-circle-o help"
                                                            src="approver_hint" action="show"></i></label>
                                                    <p class="input_hint" id="approver_hint"><?php echo getUserHint('department_approver_hint'); ?></p>
                                                    <div class="">
                                                        <select name="approvers[]" class="invoice-fields" id="approvers_ids" multiple="true">
                                                            <?php foreach ($employees as $key => $employee): ?>
                                                                <option value="<?php echo $employee['sid'] ?>" <?php echo isset($department['approvers']) && in_array($employee['sid'], explode(',', $department['approvers']))  ? 'selected="selected"' : ''; ?>>
                                                                    <?php echo remakeEmployeeName($employee); ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <span id="add_approvers_error" class="text-danger person_error"></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                                <div class="form-group autoheight">
                                                    <label for="name">Reporting Manager(s) <i
                                                            class="fa fa-question-circle-o help"
                                                            src="manager_hint" action="show"></i></label>
                                                    <p class="input_hint" id="manager_hint"><?php echo getUserHint('department_reporting_manager_hint'); ?></p>
                                                    <div class="">
                                                        <select name="reporting_manager[]" class="invoice-fields" id="reporting_manager_ids" multiple="true">
                                                            <?php foreach ($employees as $key => $employee): ?>
                                                                <option value="<?php echo $employee['sid'] ?>" <?php echo isset($department['reporting_managers']) && in_array($employee['sid'], explode(',', $department['reporting_managers']))  ? 'selected="selected"' : ''; ?>>
                                                                    <?php echo remakeEmployeeName($employee); ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <span id="add_reporting_manager_error" class="text-danger person_error"></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if (checkIfAppIsEnabled(MODULE_LMS)) { ?>
                                                <?php
                                                $departmentLMSManagersIds = explode(',', $department['lms_managers_ids']);
                                                ?>
                                                <div class="form-group autoheight">
                                                    <label for="name">LMS Manager(s) <i
                                                            class="fa fa-question-circle-o help"
                                                            src="lms_manager_hint" action="show"></i></label>
                                                    <p class="input_hint" id="lms_manager_hint"><?php echo getUserHint('lms_manager_hint'); ?></p>
                                                    <div class="">
                                                        <select name="lms_managers[]" class="invoice-fields" id="lms_managers" multiple="true">
                                                            <?php foreach ($employees as $key => $employee): ?>
                                                                <?php if (strtolower($employee["access_level"]) == "employee") {
                                                                    continue;
                                                                } ?>
                                                                <option value="<?php echo $employee['sid'] ?>" <?php echo isset($department['lms_managers_ids']) && in_array($employee['sid'], $departmentLMSManagersIds)  ? 'selected="selected"' : ''; ?>>
                                                                    <?php echo remakeEmployeeName($employee); ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <span id="add_lms_managers_error" class="text-danger person_error"></span>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if (checkIfAppIsEnabled(MODULE_COMPLIANCE_SAFETY)) { ?>
                                                <?php
                                                $departmentCSPManagersIds = explode(',', $department['csp_managers_ids']);
                                                ?>
                                                <div class="form-group autoheight">
                                                    <label for="name">Compliance Safety Reporting Manager(s) <i
                                                            class="fa fa-question-circle-o help"
                                                            src="lms_manager_hint" action="show"></i></label>
                                                    <p class="input_hint" id="lms_manager_hint"><?php echo getUserHint('csp_manager_hint'); ?></p>
                                                    <div class="">
                                                        <select name="csp_managers[]" class="invoice-fields" id="csp_managers" multiple="true">
                                                            <?php foreach ($employees as $key => $employee): ?>
                                                                <?php if (strtolower($employee["access_level"]) == "employee") {
                                                                    continue;
                                                                } ?>
                                                                <option value="<?php echo $employee['sid'] ?>" <?php echo isset($department['csp_managers_ids']) && in_array($employee['sid'], $departmentCSPManagersIds)  ? 'selected="selected"' : ''; ?>>
                                                                    <?php echo remakeEmployeeName($employee); ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <span id="add_csp_managers_error" class="text-danger person_error"></span>
                                                    </div>
                                                </div>
                                            <?php } ?>    

                                            <div class="form-group autoheight">
                                                <label>Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control" value="<?php echo isset($department['sort_order']) ? $department['sort_order'] : ''; ?>">
                                            </div>
                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <div class="col-xs-12" style="text-align: right;">
                                                        <button type="submit" class="btn btn-success" onclick="validate_form();"><?php echo $submit_button_text; ?></button>
                                                        <a href="<?php echo base_url('department_management'); ?>" class="btn black-btn">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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
    $(function() {
        $('#supervisor_id').select2({
            closeOnSelect: false
        });
        $('#reporting_manager_ids').select2({
            closeOnSelect: false
        });
        $('#approvers_ids').select2({
            closeOnSelect: false
        });
        $('#lms_managers').select2({
            closeOnSelect: false
        });
        $('#csp_managers').select2({
            closeOnSelect: false
        });
        $('#approvers_ids').trigger('change');
    })
</script>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function validate_form() {
        $('.person_error').text('');
        var supervisor = $('#supervisor_id').val();
        var repoting_manager = $('#reporting_manager_ids').val();
        var approvers = $('#approvers_ids').val();
        if (supervisor == null) {
            $('#add_supervisor_error').text('Supervisor name is required');
        }
        // if(approvers == null) {
        //     $('#add_approvers_error').text('Approvers are required');
        // }
        // if(repoting_manager == null) {
        //     $('#add_reporting_manager_error').text('Reporting managers are required');
        // }

        $("#form_add_edit_department_info").validate({
            ignore: [],
            rules: {
                name: {
                    required: true
                },
                supervisor: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: 'Department name is required',
                },
                supervisor: {
                    required: 'Supervisor name is required',
                }
            },
            submitHandler: function(form) {
                var supervisor = $('#supervisor_id').val();
                // if(supervisor != null && repoting_manager != null && approvers != null) {
                if (supervisor != null) {
                    form.submit();
                }
            }
        });
    }

    $('.help').click(function(event) {

        event.preventDefault();

        var element_id = $(this).attr("src");
        var action = $(this).attr("action");

        if (action == "show") {
            $(this).attr("action", "hide");
            $("#" + element_id).hide();
        } else {
            $(this).attr("action", "show");
            $("#" + element_id).show();
        }
    });
</script>