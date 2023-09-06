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
                                        <span class="page-title"><i class="fa fa-users"></i>Manage Executive Admin</span>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="<?php echo current_url(); ?>" method="POST" name='edit_admin' id="edit_admin" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <span class="page-title"><?php echo $page_title; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>First Name<span class="hr-required">*</span></label>
                                                        <?php echo form_input('first_name', set_value('first_name', $administrator['first_name']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('first_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Last Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('last_name', set_value('last_name', $administrator['last_name']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('last_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Username<span class="hr-required">*</span></label>
                                                        <?php echo form_input('username', set_value('username', $administrator['username']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('username'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Email<span class="hr-required">*</span></label>
                                                        <?php echo form_input('email', set_value('email', $administrator['email']), 'class="hr-form-fileds" id="jsemail"'); ?>
                                                        <?php echo form_error('email'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Alternative Email</label>
                                                        <?php echo form_input('alternative_email', set_value('alternative_email', $administrator['alternative_email']), 'class="hr-form-fileds" id="jsalternative_email"'); ?>
                                                        <?php echo form_error('alternative_email'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Job Title<span class="hr-required"></span></label>
                                                        <?php echo form_input('job_title', set_value('job_title', $administrator['job_title']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('job_title'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Direct Business Number<span class="hr-required"></span></label>
                                                        <?php echo form_input('direct_business_number', set_value('direct_business_number', $administrator['direct_business_number']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('direct_business_number'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Cell Number<span class="hr-required"></span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <span class="input-group-text">+1</span>
                                                            </div>
                                                            <?php echo form_input('cell_number', set_value('cell_number', phonenumber_format($administrator['cell_number'], true)), 'class="hr-form-fileds js-phone" id="PhoneNumber"'); ?>
                                                        </div>
                                                        <?php echo form_error('cell_number'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>New Password<span class="hr-required"></span></label>
                                                        <?php echo form_input('password', set_value('password'), 'class="hr-form-fileds" placeholder="Enter new password to replace old"'); ?>
                                                        <?php echo form_error('password'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="gender">Gender</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="gender">
                                                                <option value="0">Please select gender</option>
                                                                <option value="male" <?php echo $administrator['gender'] == "male" ? "selected='selected'" : ""; ?>>Male</option>
                                                                <option value="female" <?php echo $administrator['gender'] == "female" ? "selected='selected'" : ""; ?>>Female</option>
                                                                <option value="female" <?php echo $administrator['gender'] == "other" ? "selected='selected'" : ""; ?>>Other</option>
                                                            </select>
                                                            <?php echo form_error('gender'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="profile_picture">Profile Picture:</label>
                                                        <div class="upload-file form-control">
                                                            <span class="selected-file" id="name_profile_picture">No file selected</span>
                                                            <input name="profile_picture" id="profile_picture" onchange="check_file_all('profile_picture')" accept="image/*" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <?php echo form_error('profile_picture'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="country">Access Level</label>
                                                        <div class="hr-select-dropdown">
                                                            <?php $selected = $administrator['access_level']; ?>
                                                            <select class="invoice-fields" name="access_level">
                                                                <?php if (!empty($access_levels)) { ?>
                                                                    <?php foreach ($access_levels as $accessLevel) { ?>
                                                                        <?php $is_default_select = ($accessLevel == $selected ? true : false); ?>
                                                                        <option <?php echo set_select('access_level', $accessLevel, $is_default_select); ?> value="<?php echo $accessLevel; ?>"><?php echo $accessLevel; ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error('access_level'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (IS_TIMEZONE_ACTIVE) { ?>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 js-timezone-row">
                                                        <div class="field-row">
                                                            <label for="active">Timezone</label>
                                                            <?= timezone_dropdown(
                                                                $administrator['timezone'],
                                                                array(
                                                                    'name' => 'timezone',
                                                                    'id' => 'timezone',
                                                                    'class' => 'hr-form-fileds js-timezone',
                                                                    'style' => 'padding: 0;'
                                                                )
                                                            ); ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                    <div class="field-row active-admin-status">
                                                        <input type="checkbox" name="active" id='active' class="my_checkbox" value='1' <?php if ($administrator['active'] == '1') {
                                                                                                                                            echo 'checked';
                                                                                                                                        } ?>>
                                                        <label for="active">&nbsp;Is Active</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <input type="submit" class="search-btn" value="Update Administrator" name="submit">
                                                    <a href="<?php echo $cancel_url; ?>" class="search-btn black-btn">Cancel</a>
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

<script type="text/javascript">
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

    <?php if (IS_TIMEZONE_ACTIVE) { ?>
        $('.js-timezone').select2();
    <?php } ?>
</script>


<script>
    //
    var phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/);
    //
    $('form#edit_admin').submit(function(e) {
        phone_regex.lastIndex = 0;
        var phone = $('#PhoneNumber').val().trim();
        if (phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)) {
            alertify.alert('Error!', 'Contact number is invalid. Please use following format (XXX) XXX-XXXX.', function() {
                return;
            });
            e.preventDefault();
        }
        if (phone != '' && phone != '(___) ___-____') $(this).append('<input type="hidden" name="txt_phonenumber" value="+1' + (phone.replace(/\D/g, '')) + '" />');
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

    //
    $("#jsemail").focusout(function() {
        var email = $(this).val();
        $(this).val(email.toLowerCase());
    });
    //
    $("#jsalternative_email").focusout(function() {
        var email = $(this).val();
        $(this).val(email.toLowerCase());
    });
</script>

<style>
    /* Remove the radius from left fro phone field*/
    .input-group input {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>