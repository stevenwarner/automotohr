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
                                    <div class="add-new-company">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><?php echo $company_name; ?></h1>
                                        </div>

                                        <div class="edit-template-from-main">
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
                                                            <?php echo form_label('User Name <span class="hr-required">*</span>', 'username'); ?>
                                                            <?php echo form_input('username', set_value('username'), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('username'); ?>
                                                        </div>
                                                    </div>

                                                    <!--
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php //echo form_label('Job Title', 'job_title'); 
                                                            ?>
                                                            <?php //echo form_input('job_title', set_value('job_title'), 'class="hr-form-fileds"'); 
                                                            ?>
                                                            <?php //echo form_error('job_title'); 
                                                            ?>
                                                        </div>
                                                    </div>
-->





                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <?php $templateTitles = get_templet_jobtitles($company_sid);
                                                        ?>

                                                        <div class="field-row">
                                                            <label>Job Title: &nbsp;&nbsp;&nbsp;
                                                                <?php if ($templateTitles) { ?>
                                                                    <input type="radio" name="title_option" value="dropdown" class="titleoption" <?php echo $employer['job_title_type'] != '0' ? 'checked' : '' ?>> Choose Job Title&nbsp;&nbsp;
                                                                    <input type="radio" name="title_option" value="manual" class="titleoption" <?php echo $employer['job_title_type'] == '0' ? 'checked' : '' ?>> Custom Job Title &nbsp;
                                                                <?php } ?>
                                                            </label>
                                                            <input class="invoice-fields" value="<?php echo set_value('job_title', $employer["job_title"]); ?>" type="text" name="job_title" id="job_title">
                                                            <?php if ($templateTitles) { ?>
                                                                <select name="temppate_job_title" id="temppate_job_title" class="invoice-fields" style="display: none;">
                                                                    <option value="0">Please select job title</option>
                                                                    <?php foreach ($templateTitles as $titleRow) { ?>
                                                                        <option value="<?php echo $titleRow['sid'] . '#' . $titleRow['title']; ?>"> <?php echo $titleRow['title']; ?> </option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } ?>

                                                        </div>
                                                    </div>







                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Employee Type:</label>
                                                            <select name="employee_type" id="employee_type" class="invoice-fields">
                                                                <option <?= $data["employee_type"] == 'fulltime' ? 'selected' : ''; ?> value="fulltime">Full-Time</option>
                                                                <option <?= $data["employee_type"] == 'parttime' ? 'selected' : ''; ?> value="parttime">Part-Time</option>
                                                                <option <?= $data["employee_type"] == 'contractual' ? 'selected' : ''; ?> value="contractual">Contractual</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <?php if (isCompanyOnComplyNet($company_sid) != 0) { ?>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label>ComplyNet Job Title</label>

                                                                <div class="hr-select-dropdown">
                                                                    <select name="complynet_job_title" id="complynet_job_title" class="invoice-fields">
                                                                        <option selected value="null">
                                                                            Please select job title
                                                                        </option>
                                                                        <option value="BDC Person">
                                                                            BDC Person
                                                                        </option>
                                                                        <option value="Body Shop Estimator">
                                                                            Body Shop Estimator
                                                                        </option>
                                                                        <option value="Body Shop Manager">
                                                                            Body Shop Manager
                                                                        </option>
                                                                        <option value="Body Shop Tech">
                                                                            Body Shop Tech
                                                                        </option>
                                                                        <option value="Cashier">
                                                                            Cashier
                                                                        </option>
                                                                        <option value="CFO">
                                                                            CFO
                                                                        </option>
                                                                        <option value="Detail Manager">
                                                                            Detail Manager
                                                                        </option>
                                                                        <option value="Detailer">
                                                                            Detailer
                                                                        </option>
                                                                        <option value="F&I Manager">
                                                                            F&I Manager
                                                                        </option>
                                                                        <option value="F&I Writer">
                                                                            F&I Writer
                                                                        </option>
                                                                        <option value="Fixed Operations Director">
                                                                            Fixed Operations Director
                                                                        </option>
                                                                        <option value="GM">
                                                                            GM
                                                                        </option>
                                                                        <option value="HR Assistant">
                                                                            HR Assistant
                                                                        </option>
                                                                        <option value="HR Manager">
                                                                            HR Manager
                                                                        </option>
                                                                        <option value="IT">
                                                                            IT
                                                                        </option>
                                                                        <option value="Office Employee">
                                                                            Office Employee
                                                                        </option>
                                                                        <option value="Office Manager">
                                                                            Office Manager
                                                                        </option>
                                                                        <option value="Owner">
                                                                            Owner
                                                                        </option>
                                                                        <option value="Parts Desk">
                                                                            Parts Desk
                                                                        </option>
                                                                        <option value="Parts Driver">
                                                                            Parts Driver
                                                                        </option>
                                                                        <option value="Parts Manager">
                                                                            Parts Manager
                                                                        </option>
                                                                        <option value="Parts Sales">
                                                                            Parts Sales
                                                                        </option>
                                                                        <option value="Parts Shipper">
                                                                            Parts Shipper
                                                                        </option>
                                                                        <option value="Porter">
                                                                            Porter
                                                                        </option>
                                                                        <option value="Receptionist">
                                                                            Receptionist
                                                                        </option>
                                                                        <option value="Sales Employee">
                                                                            Sales Employee
                                                                        </option>
                                                                        <option value="Sales Manager">
                                                                            Sales Manager
                                                                        </option>
                                                                        <option value="Sales Person">
                                                                            Sales Person
                                                                        </option>
                                                                        <option value="Service Advisor">
                                                                            Service Advisor
                                                                        </option>
                                                                        <option value="Service Director">
                                                                            Service Director
                                                                        </option>
                                                                        <option value="Service Manager">
                                                                            Service Manager
                                                                        </option>
                                                                        <option value="Service Office">
                                                                            Service Office
                                                                        </option>
                                                                        <option value="Service Tech">
                                                                            Service Tech
                                                                        </option>
                                                                        <option value="Warranty Clerk">
                                                                            Warranty Clerk
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>


                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Email <span class="hr-required">*</span>', 'E-Mail Address'); ?>
                                                            <input type="email" value="<?php echo set_value('email'); ?>" id="email" name="email" class="hr-form-fileds" />
                                                            <?php echo form_error('email'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Secondary Email Address', 'alternative_email'); ?>
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
                                                            <?php //echo form_label('Access Level Plus ', 'access_level_plus'); 
                                                            ?>
                                                            <div class="hr-select-dropdown">
                                                                <select name="access_level_plus" class="invoice-fields">
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>
                                                            <?php //echo form_error('access_level_plus'); 
                                                            ?>
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
                                                            <?php
                                                            $requiredText = get_company_module_status($company_sid, 'primary_number_required') == 1 ? '<span class="hr-required">*</span>' : ''; ?>

                                                            <?php echo form_label('Primary Number ' . $requiredText, 'cell_number'); ?>
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
                                                            <label>Joining Date <span class="hr-required">*</span></label>
                                                            <input class="invoice-fields datepicker" id="registration_date" name="registration_date" value="" readonly="readonly" />
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Gender:</label>
                                                            <select class="invoice-fields" name="gender">
                                                                <option value="">Please Select Gender</option>
                                                                <option <?= $user_information["gender"] == 'male' ? 'selected' : ''; ?> value="male">Male</option>
                                                                <option <?= $user_information["gender"] == 'female' ? 'selected' : ''; ?> value="female">Female</option>
                                                                <option <?= $user_information["gender"] == 'other' ? 'selected' : ''; ?> value="other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Payment Method:</label>
                                                            <select class="invoice-fields" name="payment_method">
                                                                <option <?= $user_information["payment_method"] == 'direct_deposit' ? 'selected' : ''; ?> value="direct_deposit">Direct Deposit</option>
                                                                <option <?= $user_information["payment_method"] == 'check' ? 'selected' : ''; ?> value="check">Check</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">

                                                        <div class="field-row">
                                                            <label>Team:</label>
                                                            <br>
                                                            <?= get_company_departments_teams($company_sid, 'teamId'); ?>
                                                        </div>
                                                    </div>

                                                    <script>
                                                        $('.jsSelect2').select2();
                                                    </script>




                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <div class="row js-timezone-row">
                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                    <div class=" input-grey ">
                                                                        <?php $field_id = 'timezone'; ?>
                                                                        <?php echo form_label('Timezone:', $field_id); ?>
                                                                        <?= timezone_dropdown(
                                                                            '',
                                                                            array(
                                                                                'class' => 'invoice-fields js-timezone ',
                                                                                'id' => 'timezone',
                                                                                'name' => 'timezone'
                                                                            )
                                                                        ); ?>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Workers Compensation Code</label>
                                                            <input class="invoice-fields" value="" type="text" name="workers_compensation_code">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>EEOC Code</label>
                                                            <input class="invoice-fields" value="" type="text" name="eeoc_code">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Salary Benefits</label>
                                                            <textarea autocomplete="nope" class="invoice-fields" name="salary_benefits" id="salary_benefits"></textarea>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <input name="action" type="hidden" id="submit_action" value="">
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
    $(document).ready(function() {
        $('.datepicker').datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: 'mm-dd-yy',
            yearRange: "<?= JOINING_DATE_LIMIT; ?>"
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
                    required: true,
                    minlength: 5
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
                },

                <?php if (get_company_module_status($company_sid, 'primary_number_required') == 1) { ?>
                    cell_number: {
                        required: true
                    },

                <?php } ?>

                registration_date: {
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
                    required: 'Username is Required',
                    minlength: 'The User Name field must be at least 5 characters in length.'
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
                },
                <?php if (get_company_module_status($company_sid, 'primary_number_required') == 1) { ?>
                    cell_number: {
                        required: 'Primary Number is required'
                    },
                <?php } ?>
                registration_date: {
                    required: 'Joining Date is Required'
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


    //
    <?php if ($templateTitles) { ?>

        <?php if ($employer['job_title_type'] != '0') { ?>
            $('#temppate_job_title').show();
            $('#temppate_job_title').val('<?php echo $employer['job_title_type'] . '#' . $employer['job_title']; ?>');
            $('#job_title').hide();
        <?php } ?>

        $('.titleoption').click(function() {
            var titleOption = $(this).val();
            if (titleOption == 'dropdown') {
                $('#temppate_job_title').show();
                $('#temppate_job_title').val(0);
                $('#job_title').hide();
            } else if (titleOption == 'manual') {
                $('#temppate_job_title').hide();
                $('#temppate_job_title').val('0');
                $('#job_title').show();
            }

        });
    <?php } ?>
</script>