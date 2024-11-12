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

                                    <div class="edit-email-template">
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <img src="<?= AWS_S3_BUCKET_URL . $company_detail[0]['Logo']; ?>" alt="<?= $company_detail[0]['CompanyName']; ?>" style="width: 75px; height: 75px;" />
                                                <p><?= $company_detail[0]['CompanyName']; ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php if (count($creator)) { ?>
                                            <p>Employee created by : <strong><?= remakeEmployeeName($creator, true) . ' [' . ($creator['email']) . '] (' . ($creator['active'] == 1 ? 'Active' : 'InActive') . ')'; ?></strong></p>
                                            <hr />
                                        <?php } ?>
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        <?php
                                        $doNotHireRecords = checkDontHireText([$data['sid']]);
                                        //
                                        $doNotHireWarning = doNotHireWarning($data['sid'], $doNotHireRecords, 14);
                                        //
                                        echo $doNotHireWarning['message']; ?>
                                        <div class="edit-template-from-main">
                                            <?php echo form_open_multipart('', array('class' => 'form-horizontal', 'id' => 'js-update-employee-form')); ?>
                                            <ul>
                                                <li>
                                                    <label for="employee_profile_picture">Employee Profile Picture:</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="avatar">
                                                            <span class="image_holder">
                                                                <img src="<?= getImageURL($data["profile_picture"]); ?>" class="table-image" alt="" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('First Name', 'first_name'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        echo form_input('first_name', set_value('first_name', $data['first_name'], false), 'class="hr-form-fileds"');
                                                        echo form_error('first_name');
                                                        ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Nick Name', 'nick_name'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        echo form_input('nick_name', set_value('nick_name', $data['nick_name'], false), 'class="hr-form-fileds"');
                                                        echo form_error('nick_name');
                                                        ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Middle Name / Initial', 'middle_name'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        echo form_input('middle_name', set_value('middle_name', $data['middle_name'], false), 'class="hr-form-fileds"');
                                                        echo form_error('middle_name');
                                                        ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Last Name', 'last_name'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        echo form_input('last_name', set_value('last_name', $data['last_name'], false), 'class="hr-form-fileds"');
                                                        echo form_error('last_name');
                                                        ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('User Name <span class="hr-required">*</span>', 'username'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <p>Username should consist of a minimum of 5 characters.</p>
                                                        <?php
                                                        echo form_input('username', set_value('username', $data['username']), 'class="hr-form-fileds"');
                                                        echo form_error('username');
                                                        ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('email', 'E-Mail Address'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <button class="btn btn-xs btn-success jsToLowerCase" style="margin-bottom: 5px;" title="Convert the email address to lower case" placement="top">To Lower Case</button>
                                                        <?php
                                                        echo form_input('email', set_value('email', $data['email']), 'class="hr-form-fileds"');
                                                        echo form_error('email');
                                                        ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Secondary Email Address', 'alternative_email'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        echo form_input('alternative_email', set_value('alternative_email', $data['alternative_email']), 'class="hr-form-fileds"');
                                                        echo form_error('alternative_email');
                                                        ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Employee Type</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select name="employee_type" id="employee_type" class="invoice-fields">
                                                                <option <?= $data["employee_type"] == 'fulltime' ? 'selected' : ''; ?> value="fulltime">Full-Time</option>
                                                                <option <?= $data["employee_type"] == 'parttime' ? 'selected' : ''; ?> value="parttime">Part-Time</option>
                                                                <option <?= $data["employee_type"] == 'contractual' ? 'selected' : ''; ?> value="contractual">Contractual</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('employee_type'); ?>
                                                    </div>
                                                </li>


                                                <li>
                                                    <?php echo form_label('Job Title', 'job_title'); ?>

                                                    <?php $templateTitles = get_templet_jobtitles($data['parent_sid']); ?>

                                                    <div class="hr-fields-wrap">
                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-12 col-xl-12 col-xs-12">
                                                                <div class="col-md-12 col-lg-12 col-xl-12 col-xs-12" style="padding-left:0px;padding-right:0px;">
                                                                    <?php if ($templateTitles) { ?>
                                                                        <input type="radio" name="title_option" value="manual" class="titleoption" <?php echo $data['job_title_type'] == '0' ? 'checked' : '' ?>> <strong>Add Manual &nbsp;</strong>
                                                                        <input type="radio" name="title_option" value="dropdown" class="titleoption" <?php echo $data['job_title_type'] != '0' ? 'checked' : '' ?>> <strong> From Drop Down </strong>
                                                                         
                                                                        <br>
                                                                    <?php } ?>
                                                                    <?php
                                                                    echo form_input('job_title', set_value('job_title', $data['job_title']), 'class="hr-form-fileds" id="job_title"');
                                                                    echo form_error('job_title');
                                                                    ?>
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
                                                        </div>
                                                    </div>
                                                </li>


                                                <?php if (isCompanyOnComplyNet($data['parent_sid']) != 0) { ?>
                                                    <li>
                                                        <label>ComplyNet Job Title</label>
                                                        <div class="hr-fields-wrap">
                                                            <div class="hr-select-dropdown">
                                                                <select name="complynet_job_title" id="complynet_job_title" class="invoice-fields">
                                                                    <option <?= $data["complynet_job_title"] == null ? 'selected' : ''; ?> value="null">
                                                                        Please select job title
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'BDC Person' ? 'selected' : ''; ?> value="BDC Person">
                                                                        BDC Person
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Body Shop Estimator' ? 'selected' : ''; ?> value="Body Shop Estimator">
                                                                        Body Shop Estimator
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Body Shop Manager' ? 'selected' : ''; ?> value="Body Shop Manager">
                                                                        Body Shop Manager
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Body Shop Tech' ? 'selected' : ''; ?> value="Body Shop Tech">
                                                                        Body Shop Tech
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Cashier' ? 'selected' : ''; ?> value="Cashier">
                                                                        Cashier
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'CFO' ? 'selected' : ''; ?> value="CFO">
                                                                        CFO
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Detail Manager' ? 'selected' : ''; ?> value="Detail Manager">
                                                                        Detail Manager
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Detailer' ? 'selected' : ''; ?> value="Detailer">
                                                                        Detailer
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'F&I Manager' ? 'selected' : ''; ?> value="F&I Manager">
                                                                        F&I Manager
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'F&I Writer' ? 'selected' : ''; ?> value="F&I Writer">
                                                                        F&I Writer
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Fixed Operations Director' ? 'selected' : ''; ?> value="Fixed Operations Director">
                                                                        Fixed Operations Director
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'GM' ? 'selected' : ''; ?> value="GM">
                                                                        GM
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'HR Assistant' ? 'selected' : ''; ?> value="HR Assistant">
                                                                        HR Assistant
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'HR Manager' ? 'selected' : ''; ?> value="HR Manager">
                                                                        HR Manager
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'IT' ? 'selected' : ''; ?> value="IT">
                                                                        IT
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Office Employee' ? 'selected' : ''; ?> value="Office Employee">
                                                                        Office Employee
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Office Manager' ? 'selected' : ''; ?> value="Office Manager">
                                                                        Office Manager
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Owner' ? 'selected' : ''; ?> value="Owner">
                                                                        Owner
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Parts Desk' ? 'selected' : ''; ?> value="Parts Desk">
                                                                        Parts Desk
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Parts Driver' ? 'selected' : ''; ?> value="Parts Driver">
                                                                        Parts Driver
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Parts Manager' ? 'selected' : ''; ?> value="Parts Manager">
                                                                        Parts Manager
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Parts Sales' ? 'selected' : ''; ?> value="Parts Sales">
                                                                        Parts Sales
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Parts Shipper' ? 'selected' : ''; ?> value="Parts Shipper">
                                                                        Parts Shipper
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Porter' ? 'selected' : ''; ?> value="Porter">
                                                                        Porter
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Receptionist' ? 'selected' : ''; ?> value="Receptionist">
                                                                        Receptionist
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Sales Employee' ? 'selected' : ''; ?> value="Sales Employee">
                                                                        Sales Employee
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Sales Manager' ? 'selected' : ''; ?> value="Sales Manager">
                                                                        Sales Manager
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Sales Person' ? 'selected' : ''; ?> value="Sales Person">
                                                                        Sales Person
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Service Advisor' ? 'selected' : ''; ?> value="Service Advisor">
                                                                        Service Advisor
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Service Director' ? 'selected' : ''; ?> value="Service Director">
                                                                        Service Director
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Service Manager' ? 'selected' : ''; ?> value="Service Manager">
                                                                        Service Manager
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Service Office' ? 'selected' : ''; ?> value="Service Office">
                                                                        Service Office
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Service Tech' ? 'selected' : ''; ?> value="Service Tech">
                                                                        Service Tech
                                                                    </option>
                                                                    <option <?= $data["complynet_job_title"] == 'Warranty Clerk' ? 'selected' : ''; ?> value="Warranty Clerk">
                                                                        Warranty Clerk
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } ?>


                                                <li>
                                                    <?php echo form_label('LMS Job Title', 'lms_job_title'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-12 col-xl-12 col-xs-12">
                                                                <div class="col-md-12 col-lg-12 col-xl-12 col-xs-12" style="padding-left:0px;padding-right:0px;">
                                                                    <?php if ($templateTitles) { ?>
                                                                        <select name="lms_job_title" id="lms_job_title" class="invoice-fields">
                                                                            <option value="0">Please select job title</option>
                                                                            <?php foreach ($templateTitles as $titleRow) { ?>
                                                                                <option value="<?php echo $titleRow['sid']; ?>" <?=$titleRow["sid"] == $data["lms_job_title"] ? "selected": "";?>> <?php echo $titleRow['title']; ?> </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Direct Business Number', 'direct_business_number'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        echo form_input('direct_business_number', set_value('direct_business_number', $data['direct_business_number']), 'class="hr-form-fileds"');
                                                        echo form_error('direct_business_number');
                                                        ?>
                                                    </div>
                                                </li>

                                                <li>

                                                    <?php
                                                    $requiredText = get_company_module_status($data['parent_sid'], 'primary_number_required') == 1 ? '<span class="hr-required">*</span>' : ''; ?>
                                                    <?php echo form_label('Primary Number' . $requiredText, 'cell_number'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <span class="input-group-text">+1</span>
                                                            </div>
                                                            <?php
                                                            echo form_input('cell_number', set_value('cell_number', phonenumber_format($data['PhoneNumber'], true)), 'class="hr-form-fileds js-phone" id="PhoneNumber"'); ?>
                                                        </div>
                                                        <?php echo form_error('cell_number'); ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label for="profile_picture">Profile Picture:</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="upload-file form-control">
                                                            <span class="selected-file" id="name_profile_picture">No file selected</span>
                                                            <input name="profile_picture" id="profile_picture" onchange="check_file_all('profile_picture')" accept="image/*" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <?php echo form_error('profile_picture'); ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Joining Date</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        $registration_date = $data['joined_at'] != NULL && $data['joined_at'] != '0000-00-00' ? DateTime::createFromFormat('Y-m-d', $data['joined_at'])->format('m-d-Y') : '';
                                                        if (empty($registration_date)) {
                                                            $registration_date = $data['registration_date'] != NULL && $data['registration_date'] != '0000-00-00 00:00:00' ? DateTime::createFromFormat('Y-m-d H:i:s', $data['registration_date'])->format('m-d-Y') : '';
                                                        }
                                                        ?>
                                                        <input class="invoice-fields datepicker" id="registration_date" readonly name="registration_date" value="<?php echo set_value('registration_date', $registration_date); ?>" />
                                                        <?php echo form_error('direct_business_number'); ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Rehire Date</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        $rehireDate = $data['rehire_date'] != NULL && $data['rehire_date'] != '0000-00-00' ? DateTime::createFromFormat('Y-m-d', $data['rehire_date'])->format('m-d-Y') : '';
                                                        ?>
                                                        <input class="invoice-fields datepicker" id="js-rehire-date" readonly name="rehire_date" value="<?php echo set_value('rehire_date', $rehireDate); ?>" />
                                                        <?php echo form_error('rehire_date'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Starting Date as a Full-Time Employee</label>
                                                    <div class="hr-fields-wrap">
                                                        <input class="invoice-fields datepicker" id="js-employment-date" name="employment_date" value="<?php echo set_value('employment_date', $data["employment_date"] ? formatDateToDB($data["employment_date"], DB_DATE, "m-d-Y") : ""); ?>" />
                                                        <?php echo form_error('employment_date'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Gender</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select name="gender" id="gender" class="invoice-fields">
                                                                <option value="">Please Select Gender</option>
                                                                <option <?= $data["gender"] == 'male' ? 'selected' : ''; ?> value="male">Male</option>
                                                                <option <?= $data["gender"] == 'female' ? 'selected' : ''; ?> value="female">Female</option>
                                                                <option <?= $data["gender"] == 'other' ? 'selected' : ''; ?> value="other">Other</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('gender'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Payment Method</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="payment_method">
                                                                <option <?= $data["payment_method"] == 'direct_deposit' ? 'selected' : ''; ?> value="direct_deposit">Direct Deposit</option>
                                                                <option <?= $data["payment_method"] == 'check' ? 'selected' : ''; ?> value="check">Check</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('payment_method'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Marital Status</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select name="marital_status" id="marital_status" class="invoice-fields">
                                                                <option <?= $data["marital_status"] == 'not_specified' ? 'selected' : ''; ?> value="not_specified">
                                                                    Please select marital status
                                                                </option>
                                                                <option <?= $data["marital_status"] == 'Single' ? 'selected' : ''; ?> value="Single">
                                                                    Single
                                                                </option>
                                                                <option <?= $data["marital_status"] == 'Married' ? 'selected' : ''; ?> value="Married">
                                                                    Married
                                                                </option>
                                                                <option <?= $data["marital_status"] == 'Other' ? 'selected' : ''; ?> value="Other">
                                                                    Other
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('marital_status'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Security Access Level</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php $access_level = $data['access_level']; ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="security_access_level" id="security_access_level" class="invoice-fields">
                                                                <option value="">Please Select</option>
                                                                <?php if (!empty($security_access_levels)) { ?>
                                                                    <?php foreach ($security_access_levels as $security_access_level) { ?>
                                                                        <option value="<?php echo $security_access_level; ?>" <?php if ($access_level == $security_access_level) {
                                                                                                                                    echo 'selected';
                                                                                                                                } ?>><?php echo ucwords($security_access_level); ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('security_access_level'); ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Access Level Plus</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php $access_level_plus = $data['access_level_plus']; ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="access_level_plus" class="invoice-fields">
                                                                <option value="0" <?php if ($access_level_plus == 0) {
                                                                                        echo 'selected';
                                                                                    } ?>>No</option>
                                                                <option value="1" <?php if ($access_level_plus == 1) {
                                                                                        echo 'selected';
                                                                                    } ?>>Yes</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('access_level_plus'); ?>
                                                    </div>
                                                </li>

                                                <?php if (IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                                                    <li class="js-timezone-row">
                                                        <?=
                                                        form_label('Timezone', 'timezone') .
                                                            form_error('timezone');
                                                        ?>
                                                        <div class="hr-fields-wrap">
                                                            <div class="hr-select-dropdown">
                                                                <?= timezone_dropdown(
                                                                    $data['timezone'],
                                                                    array(
                                                                        'class' => 'invoice-fields',
                                                                        "id" => 'timezone',
                                                                        "name" => 'timezone'
                                                                    )
                                                                ); ?>

                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } ?>

                                                <li>
                                                    <label>ComplyNet Status</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php $complynet_status = $data['complynet_status']; ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="complynet_status" class="invoice-fields">
                                                                <option value="0" <?php if ($complynet_status == 0) {
                                                                                        echo 'selected';
                                                                                    } ?>>No</option>
                                                                <option value="1" <?php if ($complynet_status == 1) {
                                                                                        echo 'selected';
                                                                                    } ?>>Yes</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('access_level_plus'); ?>
                                                    </div>
                                                </li>
                                                <?php if (IS_PTO_ENABLED == 1) { ?>
                                                    <li>
                                                        <?php echo form_label('Shift', 'Shift'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <div class="row">
                                                                <div class="col-md-6 col-lg-6 col-xl-6 col-xs-12">
                                                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xs-12" style="padding-left:0px;padding-right:0px;">
                                                                        <div class="input-group">
                                                                            <input oninput="this.value=Math.abs(this.value)" style="border-top-right-radius: 0px;border-bottom-right-radius: 0px;border-right-width: 1px;" id="sh_hours" type="number" value="<?php echo !empty($data["user_shift_hours"]) ? $data["user_shift_hours"] : ''; ?>" name="shift_hours" class="invoice-fields">
                                                                            <div class="input-group-addon" style="border-top-right-radius: 4px;border-bottom-right-radius: 4px;"> Hours </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xs-12" style="padding-left:0px;padding-right:0px;">
                                                                        <div class="input-group">
                                                                            <input oninput="this.value=Math.abs(this.value)" style="border-top-right-radius: 0px;border-bottom-right-radius: 0px;margin-left: 14px;" type="number" value="<?php echo  $data["user_shift_minutes"]; ?>" id="sh_mins" name="shift_mins" class="invoice-fields">
                                                                            <div class="input-group-addon" style="padding-left: 23px;">Minutes</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <?php echo form_label('Hourly Rate', 'hourly_rate'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <input type="text" class="hr-form-fileds" name="hourly_rate" id="jsHR" value="<?php echo $data['hourly_rate']; ?>">
                                                            <?php
                                                            echo form_error('hourly_rate');
                                                            ?>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <?php echo form_label('Hourly Technician', 'hourly_technician'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <input type="text" class="hr-form-fileds" name="hourly_technician" id="jsHT" value="<?php echo $data['hourly_technician']; ?>">
                                                            <?php
                                                            echo form_error('hourly_technician');
                                                            ?>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <?php echo form_label('Flat Rate Technician', 'flat_rate_technician'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <input type="text" class="hr-form-fileds" name="flat_rate_technician" id="jsFRT" value="<?php echo $data['flat_rate_technician']; ?>">
                                                            <?php
                                                            echo form_error('flat_rate_technician');
                                                            ?>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <?php echo form_label('Semi Monthly Salary', 'semi_monthly_salary'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <input type="text" class="hr-form-fileds" name="semi_monthly_salary" id="jsSMS" value="<?php echo $data['semi_monthly_salary']; ?>">
                                                            <?php
                                                            echo form_error('semi_monthly_salary');
                                                            ?>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <?php echo form_label('Semi Monthly Draw', 'semi_monthly_draw'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <input type="text" class="hr-form-fileds" name="semi_monthly_draw" id="jsSMD" value="<?php echo $data['semi_monthly_draw']; ?>">
                                                            <?php
                                                            echo form_error('semi_monthly_draw');
                                                            ?>
                                                        </div>
                                                    </li>



                                                <?php } ?>


                                                <li>
                                                    <label>Workers Compensation Code</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" class="hr-form-fileds" name="workers_compensation_code" value="<?php echo $data['workers_compensation_code']; ?>">

                                                    </div>
                                                </li>

                                                <li>
                                                    <label>EEOC Code</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" class="hr-form-fileds" name="eeoc_code" value="<?php echo $data['eeoc_code']; ?>">

                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Salary Benefits</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" class="hr-form-fileds" name="salary_benefits" id="salary_benefits" value="<?php echo $data['salary_benefits']; ?>">
                                                    </div>
                                                </li>

                                                <?php if (IS_NOTIFICATION_ENABLED == 1) { ?>
                                                    <li>
                                                        <label>Notified By</label>
                                                        <div class="hr-fields-wrap">
                                                            <div>
                                                                <select class="invoice-fields" name="notified_by[]" id="notified_by" multiple="true">
                                                                    <option value="email" <?php if (in_array('email', explode(',', $data['notified_by']))) {
                                                                                                echo 'selected';
                                                                                            } ?>>Email</option>
                                                                    <option value="sms" <?php if (in_array('sms', explode(',', $data['notified_by']))) {
                                                                                            echo 'selected';
                                                                                        } ?>>SMS</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <label>Employee Status</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" class="hr-form-fileds" readonly value="<?= (GetEmployeeStatus($data['last_status_text'], $data['active'])); ?>">
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Terminated Date</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" class="hr-form-fileds" readonly value="<?= !$data['last_status'] ? "N/A" : formatDateToDB($data['last_status']["termination_date"], DB_DATE, DATE); ?>">
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Terminated Reason</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-form-fileds" readonly><?= !$data['last_status'] ? "N/A" : html_entity_decode($data['last_status']['details']); ?></div>
                                                    </div>
                                                </li>
                                                <li>

                                                    <label>Team:</label>
                                                    <div class="hr-fields-wrap">
                                                        <?= get_company_departments_teams($data['parent_sid'], 'teamId', $data['team_sid'] ?? 0); ?>
                                                    </div>
                                                    <script>
                                                        $('.jsSelect2').select2();
                                                    </script>
                                                </li>
                                                <?php
                                                //
                                                $hasOther = [];
                                                //
                                                if ($data['languages_speak']) {
                                                    $hasOther = array_filter(explode(',', $data['languages_speak']), function ($lan) {
                                                        return !in_array($lan, ['english', 'spanish', 'russian']) && !empty($lan);
                                                    });
                                                }
                                                ?>
                                                <li>
                                                    <label>I Speak:</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <!--  -->
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="secondaryLanguages[]" value="english" <?= strpos($data['languages_speak'], 'english') !== false ? 'checked' : ''; ?> /> English
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <!--  -->
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="secondaryLanguages[]" value="spanish" <?= strpos($data['languages_speak'], 'spanish') !== false ? 'checked' : ''; ?> /> Spanish
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <!--  -->
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="secondaryLanguages[]" value="russian" <?= strpos($data['languages_speak'], 'russian') !== false ? 'checked' : ''; ?> /> Russian
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <!--  -->
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="secondaryOption" value="other" <?= $hasOther ? 'checked' : ''; ?> /> Others
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row jsOtherLanguage <?= $hasOther ? '' : 'dn'; ?>">
                                                            <div class="col-sm-12">
                                                                <input type="text" class="invoice-fields" name="secondaryLanguages[]" placeholder="French, German" value="<?= $hasOther ? ucwords(implode(',', $hasOther)) : ''; ?>" id='otherOtherLanguage' />
                                                                <p><strong class="text-danger"><i>Add comma separated languages. e.g. French, German</i></strong></p>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            $('[name="secondaryOption"]').click(function() {

                                                                if ($('[name="secondaryOption"]').is(":checked")) {
                                                                    $('#otherOtherLanguage').val('<?= $hasOther ? ucwords(implode(',', $hasOther)) : ''; ?>');

                                                                } else {
                                                                    $('#otherOtherLanguage').val('');
                                                                }

                                                                $('.jsOtherLanguage').toggleClass('dn');
                                                            });
                                                        </script>
                                                    </div>
                                                </li>


                                                <li>
                                                    <label>Union Member:</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                                            <label class="control control--radio " style="margin-left: -20px;">Yes <input type="radio" name="union_member" class="unionmember" value="1" <?php echo $data['union_member'] ? 'checked' : '' ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                                            <label class="control control--radio " style="margin-left: -10px;">No <input type="radio" name="union_member" value="0" class="unionmember" <?php echo $data['union_member'] ? '' : 'checked' ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>

                                                        <br>
                                                        <br>
                                                        <div class="row jsunionname">
                                                            <div class="col-sm-12">
                                                                <input type="text" class="invoice-fields" name="union_name" placeholder="Union Name" value="<?php echo $data['union_name']; ?>" />
                                                            </div>
                                                        </div>

                                                        <script>
                                                            <?php if ($data['union_member'] == 0) { ?>
                                                                $('.jsunionname').hide();
                                                            <?php } ?>

                                                            $('.unionmember').on('click', function() {
                                                                var selected = $(this).val();
                                                                if (selected == '1') {
                                                                    $('.jsunionname').show();

                                                                } else {
                                                                    $('.jsunionname').hide();
                                                                }
                                                            });
                                                        </script>
                                                    </div>
                                                    <br>
                                                </li>

                                                <li>
                                                    <label>Uniform Top Size</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" class="hr-form-fileds" value="<?= !$data['uniform_top_size'] ? "" : $data['uniform_top_size']; ?>" name="uniform_top_size">
                                                    </div>
                                                </li>
                                                <br>
                                                <li>
                                                    <label>Uniform Bottom Size</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" class="hr-form-fileds" value="<?= !$data['uniform_bottom_size'] ? "" : $data['uniform_bottom_size']; ?>" name="uniform_bottom_size">
                                                    </div>
                                                </li>


                                                <?php
                                                $isOnComplyNet = getComplyNetEmployeeCheck($data, 1, 1, true);
                                                if (isset($isOnComplyNet["errors"])) {
                                                ?>
                                                    <li>
                                                        <label>ComplyNet Status:</label>
                                                        <div class="hr-field-wrap">
                                                            <?= implode("<br>", $isOnComplyNet["errors"]); ?>
                                                        </div>
                                                    </li>
                                                    <?php } else {
                                                    //
                                                    if (!empty($isOnComplyNet)) { ?>
                                                        <li>
                                                            <label>ComplyNet Status:</label>
                                                            <div class="hr-field-wrap">
                                                                <?= $isOnComplyNet; ?>
                                                            </div>
                                                        </li>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <li>
                                                    <?php echo form_label('', ''); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="col-md-6 col-lg-6 col-xl-6" style="padding-left:0px">
                                                            <div class="input-group" style="float: left;">
                                                                <div class="col-md-6 col-lg-6 col-xl-6 error" id="sh_hours_globe" style="padding:0px;width:212px;"></div>

                                                                <div class="col-md-6 col-lg-6 col-xl-6 error" id="sh_mins_globe" style="padding:0px"> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('', ''); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="col-md-12 col-lg-12 col-xl-12" style="padding-left:0px">
                                                            <div class="input-group" style="float: left;">
                                                                <a href="<?php echo base_url('manage_admin/employers/AssignBulkDocuments') . "/" . $data['sid']; ?>" class="btn btn-success">Add Bulk Documents</a>
                                                                <a href="<?php echo base_url('manage_admin/employers/EmployeeDocuments') . "/" . $data['sid']; ?>" class="btn btn-success change_status">Employee Documents</a>
                                                                <a href="<?php echo base_url('manage_admin/employers/EmployeeStatusDetail') . "/" . $data['sid']; ?>" class="btn btn-warning change_status">Change Employee Status</a>
                                                                <a href="javascript:;" class="btn btn-success change_status jsEmployeeQuickProfile" data-id="<?php echo $data['sid']; ?>">Quick View</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <input type="hidden" name="sid" value="<?php echo $data['sid']; ?>">
                                                </li>
                                            </ul>
                                            <div class="row" style="float: right;">
                                                <div class="col-xs-12">
                                                    <input type="button" value="Apply" class="btn btn-success js-update-employee">
                                                    <input type="button" value="Save" class="btn btn-success js-update-employee">
                                                    <a href="<?php echo base_url('manage_admin/employers'); ?>" class="btn black-btn">Cancel</a>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
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
<link rel="stylesheet" href="<?= base_url('assets/css/theme-2021.css?v=' . time()); ?>">
<script src="<?= base_url('assets/js/common.js'); ?>"></script>
<script type="text/javascript">
    var old_rehire_date = '<?php echo $rehireDate; ?>';
    // 
    $(document).ready(function() {
        $('.datepicker').datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: 'mm-dd-yy',
            yearRange: "<?= JOINING_DATE_LIMIT; ?>"
        });

        <?php if (IS_NOTIFICATION_ENABLED == 1) { ?>
            $('#notified_by').select2({
                closeOnSelect: false,
                allowHtml: true,
                allowClear: true,
            });
        <?php } ?>
    });

    function check_file_all(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            ext = ext.toLowerCase();

            if (val == 'profile_picture') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe") {
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

    <?php if (IS_TIMEZONE_ACTIVE  && $show_timezone != '') { ?>
        $('#timezone').select2();
    <?php } ?>
</script>
<script>
    $('.js-update-employee').on("click", function(event) {
        // Check for phone number

        if ($('#PhoneNumber').val() != '' && $('#PhoneNumber').val().trim() != '(___) ___-____' && !fpn($('#PhoneNumber').val(), '', true)) {
            alertify.alert('Error!', 'Invalid phone number.', function() {
                return;
            });
            event.preventDefault();
            return;
        }

        // Remove and set phone extension
        $('#js-phonenumber').remove();
        // Check the fields
        if ($('#PhoneNumber').val().trim() == '(___) ___-____') $('#PhoneNumber').val('');
        else $(this).append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1' + ($('#PhoneNumber').val().replace(/\D/g, '')) + '" />');



        <?php if (get_company_module_status($data['parent_sid'], 'primary_number_required') == 1) { ?>
            if ($('#PhoneNumber').val() == '' || $('#PhoneNumber').val().trim() == '(___) ___-____') {
                alertify.alert('Error!', 'Invalid phone number.', function() {
                    return;
                });
                event.preventDefault();
                return;
            }

        <?php } ?>


        var min_flag = 0,
            hrs_flag = 0,
            errorMSG = '',
            hrs = $("#sh_hours").val(),
            mins = $("#sh_mins").val();

        if (hrs < 1) errorMSG = "Minimum allowed hours are 1";
        else if (hrs > 23) errorMSG = "Maximum allowed hours are 23";
        else if (hrs == '') errorMSG = "Shift hours are required";
        else if (mins < 0) errorMSG = "Minimum allowed minutes are 1";
        else if (mins > 59) errorMSG = "Maximum allowed minutes are 59";
        else if (mins == '') errorMSG = "Shift minutes are required";

        //
        var HR = $('#jsHR').val().trim()
        var HT = $('#jsHT').val().trim()
        var FRT = $('#jsFRT').val().trim()
        var SMS = $('#jsSMS').val().trim()
        var SMD = $('#jsSMD').val().trim()

        if (HR && HR.match('[^0-9.]') !== null) {
            errorMSG = "Hourly rate should contain only numeric value.";
        }
        if (HT && HT.match('[^0-9.]') !== null) {
            errorMSG = "Hourly technician should contain only numeric value.";
        }
        if (FRT && FRT.match('[^0-9.]') !== null) {
            errorMSG = "Flat rate technician should contain only numeric value.";
        }
        if (SMS && SMS.match('[^0-9.]') !== null) {
            errorMSG = "Semi monthly salary should contain only numeric value.";
        }
        if (SMD && SMD.match('[^0-9.]') !== null) {
            errorMSG = "Semi monthly draw should contain only numeric value.";
        }

        if (errorMSG != '') {
            alertify.alert(errorMSG);
            event.preventDefault();
            return;
        }

        //

        var new_rehire_date = $('#js-rehire-date').val();
        //
        if (new_rehire_date != old_rehire_date) {
            var message = '';
            //
            if (old_rehire_date == '' || old_rehire_date == undefined) {
                var status = '"<strong>Rehired</strong>"';
                message = "By adding rehire date the employee's '<strong>Employee Status</strong>' will be changed to " + status + ".<br><br>Do you wish to continue?";
            } else {
                message = 'Are you sure you want to change the rehire date';
            }
            //

            alertify.confirm('Confirmation', message,
                function() {
                    $('#js-update-employee-form').submit();
                },
                function() {

                });
        } else {
            $('#js-update-employee-form').submit();
        }
    });

    $.each($('.js-phone'), function() {
        var v = fpn($(this).val().trim());
        if (typeof(v) === 'object') {
            $(this).val(v.number);
            setCaretPosition(this, v.cur);
        } else $(this).val(v);
    });


    $('.js-phone').keyup(function(e) {
        var val = fpn($(this).val().trim());
        if (typeof(val) === 'object') {
            $(this).val(val.number);
            setCaretPosition(this, val.cur);
        } else $(this).val(val);
    })


    // Format Phone Number
    // @param phone_number
    // The phone number string that 
    // need to be reformatted
    // @param format
    // Match format 
    // @param is_return
    // Verify format or change format
    function fpn(phone_number, format, is_return) {
        // 
        var default_number = '(___) ___-____';
        var cleaned = phone_number.replace(/\D/g, '');
        if (cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
        //
        if (match) {
            var intlCode = '';
            if (format == 'e164') intlCode = (match[1] ? '+1 ' : '');
            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
        } else {
            var af = '',
                an = '',
                cur = 1;
            if (cleaned.substring(0, 1) != '') {
                af += "(_";
                an += '(' + cleaned.substring(0, 1);
                cur++;
            }
            if (cleaned.substring(1, 2) != '') {
                af += "_";
                an += cleaned.substring(1, 2);
                cur++;
            }
            if (cleaned.substring(2, 3) != '') {
                af += "_) ";
                an += cleaned.substring(2, 3) + ') ';
                cur = cur + 3;
            }
            if (cleaned.substring(3, 4) != '') {
                af += "_";
                an += cleaned.substring(3, 4);
                cur++;
            }
            if (cleaned.substring(4, 5) != '') {
                af += "_";
                an += cleaned.substring(4, 5);
                cur++;
            }
            if (cleaned.substring(5, 6) != '') {
                af += "_-";
                an += cleaned.substring(5, 6) + '-';
                cur = cur + 2;
            }
            if (cleaned.substring(6, 7) != '') {
                af += "_";
                an += cleaned.substring(6, 7);
                cur++;
            }
            if (cleaned.substring(7, 8) != '') {
                af += "_";
                an += cleaned.substring(7, 8);
                cur++;
            }
            if (cleaned.substring(8, 9) != '') {
                af += "_";
                an += cleaned.substring(8, 9);
                cur++;
            }
            if (cleaned.substring(9, 10) != '') {
                af += "_";
                an += cleaned.substring(9, 10);
                cur++;
            }

            if (is_return) return match === null ? false : true;

            return {
                number: default_number.replace(af, an),
                cur: cur
            };
        }
    }

    // Change cursor position in input
    function setCaretPosition(elem, caretPos) {
        if (elem != null) {
            if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            } else {
                if (elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else elem.focus();
            }
        }
    }
</script>

<style>
    /* Remove the radius from left fro phone field*/
    .input-group input {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .select2-container-multi .select2-choices .select2-search-choice,
    .select2-container-multi .select2-choices .select2-search-field {
        width: auto;
    }

    .select2-container-multi {
        padding: 0 !important;
    }

    .change_status {
        margin-left: 10px;
    }

    .avatar {
        margin-top: 8px !important;
        align-items: center;
        justify-content: center;
    }

    .avatar span.image_holder {
        float: left;
        width: 130px;
        height: 130px;
        border: 4px solid #fff;
    }

    .avatar span.image_holder img {
        border-radius: 8px;
        height: 100%;
        width: 100%;

    }
</style>

<script>
    $(function() {
        //
        $('.jsToLowerCase').popover({
            trigger: 'hover',
            placement: "right auto"
        });

        //
        $('.jsToLowerCase').click(function(event) {
            //
            event.preventDefault();
            //
            $(this).parent().find('input').val(
                $(this).parent().find('input').val().toLowerCase().trim()
            )
        });
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



    <?php if ($templateTitles && $data['job_title_type'] != '0') { ?>
        $('#temppate_job_title').show();
        $('#temppate_job_title').val('<?php echo $data['job_title_type'] . '#' . $data['job_title']; ?>');
        $('#job_title').hide();
    <?php } ?>

    $('.titleoption').click(function() {
        var titleOption = $(this).val();
        if (titleOption == 'dropdown') {
            $('#temppate_job_title').show();
            $('#temppate_job_title').val('<?php echo $data['job_title_type'] == '0' ? '0' : $data['job_title_type'] . '#' . $data['job_title']; ?>');
            $('#job_title').hide();
        } else if (titleOption == 'manual') {
            $('#temppate_job_title').hide();
            $('#temppate_job_title').val('0');
            $('#job_title').show();
        }

    });
</script>