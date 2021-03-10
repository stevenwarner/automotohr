<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                    <div class="add-new-company">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><?php echo $company_name; ?></h1>
                                        </div>
                                        
                                        <div class="edit-template-from-main" >
                                            <form method="post" enctype="multipart/form-data" id="form_add_new_employer">
                                                <?php echo form_hidden('company_sid', $company_sid); ?>
                                                <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('First Name <span class="hr-required">*</span>', 'first_name'); ?>
                                                            <?php echo form_input('first_name', set_value('first_name'), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('first_name'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Last Name <span class="hr-required">*</span>', 'last_name'); ?>
                                                            <?php echo form_input('last_name', set_value('last_name'), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('last_name'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('User Name <span class="hr-required">*</span>', 'username'); ?> <br />Username should consist of a minimum of 5 characters.
                                                            <?php echo form_input('username', set_value('username'), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('username'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Job Title', 'job_title'); ?>
                                                            <?php echo form_input('job_title', set_value('job_title'), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('job_title'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Email <span class="hr-required">*</span>', 'E-Mail Address'); ?>
                                                            <input type="email" value="<?php echo set_value('email'); ?>" id="email" name="email" class="hr-form-fileds" />
                                                            <?php echo form_error('email'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Alternative Email Address', 'alternative_email'); ?>
                                                            <input type="email" value="<?php echo set_value('alternative_email'); ?>" id="alternative_email" name="alternative_email" class="hr-form-fileds" />
                                                            <?php echo form_error('alternative_email'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Security Access Level <span class="hr-required">*</span>', 'security_access_level'); ?>
                                                            <div class="hr-select-dropdown">
                                                                <select name="security_access_level" id="security_access_level" class="invoice-fields">
                                                                    <option value="">Please Select</option>
                                                                    <?php if (!empty($security_access_levels)) { ?>
                                                                        <?php foreach ($security_access_levels as $security_access_level) { ?>
                                                                            <option value="<?php echo $security_access_level; ?>"><?php echo ucwords($security_access_level); ?></option>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('security_access_level'); ?>
                                                        </div>
                                                    </div>
                                                    
                                                   <!--  <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php //echo form_label('Access Level Plus ', 'access_level_plus'); ?>
                                                            <div class="hr-select-dropdown">
                                                                <select name="access_level_plus" class="invoice-fields">
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>
                                                            <?php //echo form_error('access_level_plus'); ?>
                                                        </div>
                                                    </div> -->
                                                    
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Access Level Plus <span class="hr-required">*</span>', 'access_level_plus'); ?>
                                                            <div class="hr-select-dropdown">
                                                                <select name="access_level_plus" class="invoice-fields">
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>
                                                            <?php echo form_error('access_level_plus'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Direct Business Number', 'direct_business_number'); ?>
                                                            <?php echo form_input('direct_business_number', set_value('direct_business_number'), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('direct_business_number'); ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Cell Number', 'cell_number'); ?>
                                                            <?php echo form_input('cell_number', set_value('cell_number'), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('cell_number'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="profile_picture">Profile Picture:</label>
                                                            <div class="upload-file form-control">
                                                                <span class="selected-file" id="name_profile_picture">No file selected</span>
                                                                <input name="profile_picture" class="hr-form-fileds" id="profile_picture" onchange="check_file_all('profile_picture')" accept="image/*" type="file">
                                                                <a href="javascript:;">Choose File</a>
                                                            </div>
                                                            <?php echo form_error('profile_picture'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Start Date</label>
                                                            <input class="invoice-fields datepicker" id="registration_date" name="registration_date" value="" readonly="readonly" />
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <input name="action" type="hidden" id="submit_action" value="" >
                                                            <input type="button" name="action" value="Add New Employer" onclick="return fValidateForm('addonly')" class="site-btn">
                                                            <input type="button" name="action" value="Add New Employer & Send Email" onclick="return fValidateForm('sendemail')" class="site-btn">
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
</div>

<script>
    $(document).ready(function () {
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        });
    });

    function fValidateForm(actionType) {
        $("#submit_action").val(actionType);
        $('#form_add_new_employer').validate({
            debug: true,
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                username: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                alternative_email: {
                    email: true
                },
                security_access_level: {
                    required: true
                }
            },
            messages: {
                first_name: {
                    required: 'First Name is Required'
                },
                last_name: {
                    required: 'Last Name is Required'
                },
                username: {
                    required: 'Username is Required'
                },
                email: {
                    required: 'Email is Required',
                    email: 'Please input a valid email address'
                },
                alternative_email: {
                    email: 'Please input a valid email address'
                },
                security_access_level: {
                    required: 'Please select an Access Level'
                }
            }
        });

        if ($('#form_add_new_employer').valid()) {
            document.getElementById('form_add_new_employer').submit();
        }
    }

    function check_file_all(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            ext = ext.toLowerCase();
            
            if (val == 'profile_picture') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "PNG" && ext != "JPE") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }
</script>
