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
                                        echo $doNotHireWarning['message']; ?> <div class="edit-template-from-main">
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
                                                    <?php echo form_label('Job Title', 'job_title'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php
                                                        echo form_input('job_title', set_value('job_title', $data['job_title']), 'class="hr-form-fileds"');
                                                        echo form_error('job_title');
                                                        ?>
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
                                                    <?php echo form_label('Cell Number', 'cell_number'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <span class="input-group-text">+1</span>
                                                            </div>
                                                            <?php
                                                            echo form_input('cell_number', set_value('cell_number', phonenumber_format($data['cell_number'], true)), 'class="hr-form-fileds js-phone" id="PhoneNumber"'); ?>
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