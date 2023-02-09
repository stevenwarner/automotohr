<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="page-header-area">
                                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Jobs Approval Module</span>
                                        </div>
                                        <div class="universal-form-style-v2">
                                            <form id="form_job_employees_selection" method="post" enctype="multipart/form-data" action="<?php echo base_url('approval_rights_management'); ?>">
                                                <input type="hidden" id="perform_action" name="perform_action" value="save_job_approving_employees" />
                                                <ul>
                                                    <li class="form-col-100 autoheight">
                                                        <label class="control control--checkbox">
                                                            Enable Job Listing Approval Module
                                                            <input type="checkbox" <?php echo ( $jobs_approval_module_status == 1 ? 'checked=checked' : '') ;?> name="company_job_approval_status" id="company_job_approval_status" value="1" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <label for="job_approval_employees">Select Employees who have Job Approval Rights :<span class="staric">*</span></label>
                                                        <div class="Category_chosen">
                                                            <select <?php echo ( $jobs_approval_module_status == 0 ? 'disabled="disabled"' : '') ;?> required data-placeholder="Please Select" multiple="multiple" onchange="" name="job_approval_employees[]" id="job_approval_employees"  class="chosen-select">
                                                               <!--<option value="0">Please Select</option>-->
                                                                <?php $is_default_selected = false; ?>
                                                                <?php foreach ($current_employees as $current_employee) { ?>
                                                                    <?php $is_default_selected = in_array($current_employee['sid'], $jobs_approving_employee_ids) ? true : false ; ?>
                                                                    <option <?php echo set_select('job_approval_employees', $current_employee['sid'], $is_default_selected); ?> value="<?php echo $current_employee['sid']; ?>" ><?php echo remakeEmployeeName($current_employee); ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('job_approval_employees'); ?>
                                                    </li>
                                                    <li class="form-col-100">
                                                        <button type="button" class="submit-btn" onclick="fValidateJobEmployees();">Save</button>
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="page-header-area">
                                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Applicants Approval Module</span>
                                        </div>
                                        <div class="universal-form-style-v2">
                                            <form id="form_applicant_employees_selection" method="post" enctype="multipart/form-data">
                                                <input type="hidden" id="perform_action" name="perform_action" value="save_applicant_approving_employees" />
                                                <ul>
                                                    <li class="form-col-100 autoheight">
                                                        <label class="control control--checkbox">
                                                            Enable Applicant Listing Approval Module
                                                            <input type="checkbox" <?php echo ( $applicants_approval_module_status == 1 ? 'checked=checked' : '') ;?> name="company_applicant_approval_status" id="company_applicant_approval_status" value="1" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <label for="applicant_approval_employees">Select Employees who have Approval Rights :<span class="staric">*</span></label>
                                                        <div class="Category_chosen">
                                                            <select <?php echo ( $applicants_approval_module_status == 0 ? 'disabled="disabled"' : '') ;?> required data-placeholder="Please Select" multiple="multiple" onchange="" name="applicant_approval_employees[]" id="applicant_approval_employees"  class="chosen-select">
                                                                <!--<option value="0">Please Select</option>-->
                                                                <?php $is_default_selected = false; ?>
                                                                <?php foreach ($current_employees as $current_employee) { ?>
                                                                    <?php $is_default_selected = in_array($current_employee['sid'], $applicants_approving_employee_ids) ? true : false ; ?>
                                                                    <option <?php echo set_select('applicant_approval_employees', $current_employee['sid'], $is_default_selected); ?> value="<?php echo $current_employee['sid']; ?>" ><?php echo remakeEmployeeName($current_employee); ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('applicant_approval_employees'); ?>
                                                    </li>
                                                    <li class="form-col-100">
                                                        <button type="button" class="submit-btn" onclick="fValidateApplicantEmployees();">Save</button>
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--start-->
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="page-header-area">
                                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Task Management Module</span>
                                        </div>
                                        <div class="universal-form-style-v2">
                                            <form method="post" enctype="multipart/form-data" action="<?php echo base_url('approval_rights_management'); ?>">
                                                <input type="hidden" id="perform_action" name="perform_action" value="save_task_management" />
                                                <ul>
                                                    <li class="form-col-100 autoheight">
                                                        <label class="control control--checkbox">
                                                            Enable Task Management Module
                                                            <input type="checkbox" <?php echo ( $task_management_module_status == 1 ? 'checked=checked' : '') ;?> name="task_management_module_status" id="task_management_module_status" value="1" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label><span>It will be enabled for all Hiring Managers</span></label>
                                                    </li>
                                                    <li class="form-col-100">
                                                        <button type="submit" class="submit-btn">Save</button>
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <!--end-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function () {
        $(".chosen-select").chosen();

        $('#company_job_approval_status').on('click', function(){
            if($(this).prop('checked')){
                $('#job_approval_employees').prop('disabled', false).trigger("chosen:updated");
            } else {
                $('#job_approval_employees').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#company_applicant_approval_status').on('click', function(){
            if($(this).prop('checked')){
                $('#applicant_approval_employees').prop('disabled', false).trigger("chosen:updated");
            } else {
                $('#applicant_approval_employees').prop('disabled', true).trigger("chosen:updated");
            }
        });
    });


    function fValidateJobEmployees() {
        $('#form_job_employees_selection').validate({
            ignore: ":hidden:not(select),[disabled]",
            rules: {
                job_approval_employees: {
                    required: true
                }
            },
            messages: {
                job_approval_employees: {
                    required: 'Please Select at least one Employee'
                }
            }
        });

        if ($('#form_job_employees_selection').valid()) {
            $('#form_job_employees_selection').submit();
        }
    }

    function fValidateApplicantEmployees() {
        $('#form_applicant_employees_selection').validate({
            ignore: ":hidden:not(select)",
            rules: {
                job_approval_employees: {
                    required: true
                }
            },
            messages: {
                job_approval_employees: {
                    required: 'Please Select at least one Employee'
                }
            }
        });

        if ($('#form_applicant_employees_selection').valid()) {
            $('#form_applicant_employees_selection').submit();
        }
    }
</script>
