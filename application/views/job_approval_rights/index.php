<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="universal-form-style-v2">
                                            <form id="form_employees_selection" method="post" enctype="multipart/form-data">
                                                <ul>
                                                    <li class="form-col-100 autoheight">
                                                        <label for="company_job_approval_status">
                                                            <input type="checkbox" <?php echo $jobApprovalModuleStatus; ?>  name="company_job_approval_status" id="company_job_approval_status" value="1" />
                                                            Enable Job Listing Approval Module
                                                        </label>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <label for="company_applicant_approval_status">
                                                            <input type="checkbox" <?php echo $applicantApprovalModuleStatus; ?>  name="company_applicant_approval_status" id="company_applicant_approval_status" value="1" />
                                                            Enable Applicant Listing Approval Module
                                                        </label>
                                                    </li>
                                                    <li class="form-col-100">
                                                        <label>Select Employees who have Approval Rights :<span class="staric">*</span></label>
                                                        <div class="Category_chosen">

                                                            <select required data-placeholder="Please Select" multiple="multiple" onchange="" name="employees[]" id="employees"  class="chosen-select">
                                                               <!--<option value="0">Please Select</option>-->
                                                                <?php

                                                                if (set_value('employees') != '') {
                                                                    $employeesArray = set_value('employees');
                                                                } else {
                                                                    if(!isset($employeesArray)){
                                                                        $employeesArray = array();
                                                                    }
                                                                }
                                                                foreach ($current_employees as $current_employee) {
                                                                    ?>
                                                                    <option <?php if (in_array($current_employee['sid'], $employeesArray)) { ?>
                                                                        selected
                                                                    <?php } ?>
                                                                        value="<?php echo $current_employee['sid']; ?>" ><?php echo ucwords($current_employee['first_name'] . ' ' . $current_employee['last_name']); ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('employees'); ?>
                                                    </li>
                                                    <li class="form-col-100">
                                                        <button type="button" class="submit-btn" onclick="fValidateEmployees();">Save</button>
                                                    </li>
                                                </ul>
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
</div>


<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script>
    $(document).ready(function () {
        $(".chosen-select").chosen();






    });


    function fValidateEmployees(){
        $('#form_employees_selection').validate({
            ignore: ":hidden:not(select)",
            rules: {
               employees: {
                   required : true
               }
           },
            messages: {
                employees: {
                    required : 'Please Select at least one Employee'
                }
            }
        });

        if($('#form_employees_selection').valid()){
            $('#form_employees_selection').submit();
        }
    }





</script>
