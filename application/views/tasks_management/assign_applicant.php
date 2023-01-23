<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a class="dashboard-link-btn" href="<?php echo base_url('task_management')?>"><i class="fa fa-chevron-left"></i>Task Management</a>
                            <?php echo $title; ?></span>
                    </div>
                    <div class="multistep-progress-form">
                        <form class="msform" action="" id="assign_applicant_form" method="POST" enctype="multipart/form-data">
                            <fieldset>
                                <div class="job-title-text">                
                                    <p>You can assign applicant to your hiring managers. Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                                </div>
                                <div class="universal-form-style-v2">
                                    <ul>
                                        <li class="form-col-100 autoheight">
                                            <label>Applicant: <span class="staric">*</span></label>  
                                            <div class="Category_chosen">
                                                <select multiple="multiple" data-placeholder="Please Select" name="applicant_sid[]" id="applicant_sid" class="chosen-select">
                                                    <option value="">Please Select Applicant</option>
                                                    <?php foreach ($primary_applicants_data as $applicant) { ?>
                                                        <?php $selected = in_array($applicant['sid'], $pre_selected); ?>
                                                        <option <?php echo set_select('applicant_sid', $applicant['sid'], $selected); ?> value="<?php echo $applicant['sid']; ?>">
                                                            <?php echo ucwords($applicant['email']).'&nbsp; - &nbsp;'.ucwords($applicant['first_name']).'&nbsp;'.ucwords($applicant['last_name']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('applicant_sid'); ?>
                                            </div>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <label>Employee: <span class="staric">*</span></label>
                                            <div class="Category_chosen">
                                                <select data-placeholder="Please Select" name="employee_sid" id="employee_sid" class="chosen-select">
                                                    <option value="">Please Select Employee</option>
                                            <?php   foreach ($all_employees as $employee) { ?>
                                                        <?php   $excutive_admin_check = '';
                                                                if($employee['is_executive_admin'] == 1) { 
                                                                    $excutive_admin_check = '&nbsp;(Executive Admin)';
                                                                } ?>
                                                        <option value="<?php echo $employee['sid']; ?>"><?php echo ucwords($employee['email']).'&nbsp; - &nbsp;'.remakeEmployeeName($employee).$excutive_admin_check; ?></option>
                                            <?php   } ?>
                                                </select>
                                                <?php echo form_error('employee_sid'); ?>
                                            </div>
                                            <?php echo form_error('job_sid'); ?>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <label>Assignment Notes: <span class="staric">*</span></label>
                                            <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" name="notes" id="notes"><?php echo set_value('notes'); ?></textarea>
                                            <?php echo form_error('notes'); ?>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <div> <!-- name="submit" -->
                                                <input type="submit" value="Assign Now" class="submit-btn" id="submit_button">
                                                <a class="submit-btn btn-cancel" href="<?php echo base_url('task_management')?>">Cancel</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div> 
            </div>          
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function () {
        $(".chosen-select").chosen();
        CKEDITOR.replace('notes');
    });

    $('#assign_applicant_form').submit(validate_form);
    
    function validate_form(e) {
        var applicant_length = $('#applicant_sid :selected').val();
        if (applicant_length == undefined || applicant_length == 0) {
            e.preventDefault();
            alertify.alert('Error! Please Select Applicant', "You must select applicant to be assigned.");
            return false;
        }
        
        var employee_length = $('#employee_sid :selected').val();
        if (employee_length == undefined || employee_length == 0) {
            e.preventDefault();
            alertify.alert('Error! Please Select Employee', "You must select employee to whom you want to assign applicant.");
            return false;
        }

        var text_pass = $.trim(CKEDITOR.instances.notes.getData());
        if (text_pass.length === 0) {
            e.preventDefault();
            alertify.alert('Error! Assignment Notes Missing', "Please provide assignment notes");
            return false;
        }

        // $("#assign_applicant_form").submit();
    }
</script>