<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
           <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
                        <?php $this->load->view('main/manage_ems_left_view'); ?>
                    <?php } else { ?>
                        <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                    <?php } ?> 
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('department_management/manage_department').'/'.$department_sid; ?>"><i class="fa fa-chevron-left"></i>Team Management</a>
                                    <?php echo $team_name; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="upload-new-doc-heading">
                                            <i class="fa fa-file-text"></i>
                                            <?php echo $title; ?>
                                        </div>
                                    <p class="upload-file-type">You can easily assign employees for team</p>
                                    <div class="form-wrp">
                                        <form id="form_assign_employee_2_team" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" name="perform_action" value="assign_employees" />
                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <label class="control control--checkbox pull-left">
                                                        <input type="checkbox" id="selectall">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <h4 class="hr-registered pull-left">
                                                        Select Employees
                                                    </h4>
                                                    <div class="text-right">(<span id="count_employees"></span>) Assigned</div>
                                                    
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="row">
                                                        <?php if (!empty($employees)) { 
                                                                foreach ($employees as $key => $employee) { 
                                                                    $cat_name = 'employees'; ?>
                                                                    <div class="col-xs-6">
                                                                        <label class="control control--checkbox font-normal">
                                                                            <?php echo $employee['first_name']. ' ' .$employee['last_name'].''.( $employee['job_title'] != '' && $employee['job_title'] != null ? ' ('.( $employee['job_title'] ).')' : '' ).' ['.( remakeAccessLevel($employee) ).']'; ?>
                                                                            <input class="employee_checkbox" name="employees[]" value="<?php echo $employee['sid']; ?>" type="checkbox" <?php echo in_array($employee['sid'], $assigned_employees) ? 'checked="checked"' : ''; ?>>
                                                                            <div class="control__indicator"></div>  
                                                                        </label>
                                                                    </div>
                                                        <?php   } ?>
                                                        <?php } else { ?>
                                                            <div class="col-xs-12 text-center">
                                                                <span class="no-data">No employees</span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <div class="col-xs-12" style="text-align: right;">
                                                        <button type="submit" id="gen_boc_btn" class="btn btn-success" onclick="validate_form();">Save</button>
                                                        <a href="<?php echo base_url('department_management/manage_department').'/'.$department_sid; ?>" class="btn black-btn">Cancel</a>
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

<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>

    $(document).ready(function() {
        $('#count_employees').text($('.employee_checkbox:checked').length);
        $(".disable_employee_checkbox").click(function(e) {
            e.preventDefault();
            alertify.error('Archive document not allowed to select!');
        });

        $('input[type="checkbox"]').click(function(){
            $('#count_employees').text($('.employee_checkbox:checked').length);
        });
    });

    $('#selectall').click(function (event) { 
        if (this.checked) { 
            $('.employee_checkbox').each(function () { 
                this.checked = true;  
            });
        } else {
            $('.employee_checkbox').each(function () { 
                this.checked = false;
            });
        }
    });

    function validate_form() {
        // $("#form_assign_employee_2_team").validate({
        //     ignore: [],
        //     rules: {
        //         employees: {
        //             required: true
        //         }
        //     },
        //     messages: {
        //         employees: {
        //             required: 'Team name is required',
        //         }
        //     },
        //     submitHandler: function (form) {
        //         var checked = $('.employee_checkbox:checked').length;
        //         if (checked == 0) {
        //             alertify.error('please select employees for this team!');
        //         } else {
        //             form.submit();
        //         }   
        //     }
        // });
    }
</script>