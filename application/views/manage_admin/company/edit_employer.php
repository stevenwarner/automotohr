<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
<?php           $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
<?php                       $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="edit-email-template">
                                        <?php if(count($creator)) { ?>
                                        <p>Employee created by : <strong><?=remakeEmployeeName($creator, true).' ['.( $creator['email'] ).'] ('.( $creator['active'] == 1 ? 'Active' : 'InActive' ).')';?></strong></p>
                                        <hr />
                                        <?php } ?>
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        <div class="edit-template-from-main" >
<?php                                       echo form_open_multipart('', array('class' => 'form-horizontal js-form')); ?>
                                            <ul>
                                                <li>
                                                    <?php echo form_label('First Name', 'first_name'); ?>
                                                    <div class="hr-fields-wrap">
<?php                                                   echo form_input('first_name', set_value('first_name', $data['first_name']), 'class="hr-form-fileds"');
                                                        echo form_error('first_name'); ?>
                                                    </div>
                                                </li>
                                                
                                                <li>
                                                    <?php echo form_label('Last Name', 'last_name'); ?>
                                                    <div class="hr-fields-wrap">
<?php                                                   echo form_input('last_name', set_value('last_name', $data['last_name']), 'class="hr-form-fileds"');
                                                        echo form_error('last_name'); ?>
                                                    </div>
                                                </li>
                                                
                                                <li>
                                                    <?php echo form_label('User Name <span class="hr-required">*</span>', 'username'); ?>
                                                    <div class="hr-fields-wrap">
<?php                                                   echo form_input('username', set_value('username', $data['username']), 'class="hr-form-fileds"');
                                                        echo form_error('username'); ?>
                                                    </div>
                                                </li>
                                                                                                
                                                <li>
                                                    <?php echo form_label('email', 'E-Mail Address'); ?>
                                                    <div class="hr-fields-wrap">
<?php                                                   echo form_input('email', set_value('email', $data['email']), 'class="hr-form-fileds"');
                                                        echo form_error('email'); ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Alternative Email Address', 'alternative_email'); ?>
                                                    <div class="hr-fields-wrap">
<?php                                                   echo form_input('alternative_email', set_value('alternative_email', $data['alternative_email']), 'class="hr-form-fileds"');
                                                        echo form_error('alternative_email'); ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Job Title', 'job_title'); ?>
                                                    <div class="hr-fields-wrap">
<?php                                                   echo form_input('job_title', set_value('job_title', $data['job_title']), 'class="hr-form-fileds"');
                                                        echo form_error('job_title'); ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Direct Business Number', 'direct_business_number'); ?>
                                                    <div class="hr-fields-wrap">
<?php                                                   echo form_input('direct_business_number', set_value('direct_business_number', $data['direct_business_number']), 'class="hr-form-fileds"');
                                                        echo form_error('direct_business_number'); ?>
                                                    </div>
                                                </li>

                                                <li>
                                                    <?php echo form_label('Cell Number', 'cell_number'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="input-group">
                                                          <div class="input-group-addon">
                                                              <span class="input-group-text">+1</span>
                                                          </div>
<?php                                                   echo form_input('cell_number', set_value('cell_number', phonenumber_format($data['cell_number'], true)), 'class="hr-form-fileds js-phone" id="PhoneNumber"');?>
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
                                                    <label>Start Date</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php $registration_date = $data['registration_date'] != NULL && $data['registration_date'] != '0000-00-00 00:00:00' ? DateTime::createFromFormat('Y-m-d H:i:s', $data['registration_date'])->format('m-d-Y') : ''; ?>
                                                        <input class="invoice-fields datepicker" id="registration_date" name="registration_date" value="<?php echo set_value('registration_date', $registration_date); ?>" />
                                                        <?php echo form_error('direct_business_number'); ?>
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
                                                                            <option value="<?php echo $security_access_level; ?>" <?php if($access_level == $security_access_level) { echo 'selected';}?>><?php echo ucwords($security_access_level); ?></option>
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
                                                                <option value="0" <?php if($access_level_plus == 0) { echo 'selected';}?>>No</option>
                                                                <option value="1" <?php if($access_level_plus == 1) { echo 'selected';}?>>Yes</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('access_level_plus'); ?>
                                                    </div>
                                                </li>
                                                
                                                <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                                                <li class="js-timezone-row">
                                                    <?=
                                                        form_label('Timezone', 'timezone').
                                                        form_error('timezone'); 
                                                    ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <?=timezone_dropdown(
                                                                $data['timezone'],
                                                                array(
                                                                    'class' => 'invoice-fields',
                                                                    "id" => 'timezone',
                                                                    "name" => 'timezone'
                                                                )
                                                            );?>
                                                           
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
                                                                <option value="0" <?php if($complynet_status == 0) { echo 'selected';}?>>No</option>
                                                                <option value="1" <?php if($complynet_status == 1) { echo 'selected';}?>>Yes</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('access_level_plus'); ?>
                                                    </div>
                                                </li>
                                                <?php if(IS_PTO_ENABLED == 1) {?>
                                                <li>
                                                    <?php echo form_label('Shift', 'Shift'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="row">
                                                            <div class="col-md-6 col-lg-6 col-xl-6 col-xs-12">
                                                                <div class="col-md-6 col-lg-6 col-xl-6 col-xs-12"style="padding-left:0px;padding-right:0px;">
                                                                    <div class="input-group" >
                                                                        <input oninput="this.value=Math.abs(this.value)"style="border-top-right-radius: 0px;border-bottom-right-radius: 0px;border-right-width: 1px;"  id="sh_hours" type="number" value="<?php echo !empty($data["user_shift_hours"]) ? $data["user_shift_hours"] : '';?>"  name="shift_hours"  class="invoice-fields">
                                                                        <div class="input-group-addon"style="border-top-right-radius: 4px;border-bottom-right-radius: 4px;">  Hours </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-lg-6 col-xl-6 col-xs-12"style="padding-left:0px;padding-right:0px;">
                                                                    <div class="input-group" >
                                                                        <input oninput="this.value=Math.abs(this.value)" style="border-top-right-radius: 0px;border-bottom-right-radius: 0px;margin-left: 14px;"  type="number" value="<?php echo  $data["user_shift_minutes"];?>" id="sh_mins" name="shift_mins"  class="invoice-fields">
                                                                        <div class="input-group-addon"style="padding-left: 23px;">Minutes</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php } ?>
                                                <?php if(IS_NOTIFICATION_ENABLED == 1) {?>
                                                <li>
                                                    <label>Notified By</label>
                                                    <div class="hr-fields-wrap">
                                                         <div>
                                                            <select  class="invoice-fields" name="notified_by[]" id="notified_by" multiple="true">
                                                                <option value="email" <?php if(in_array('email', explode(',', $data['notified_by']))){echo 'selected';}?> >Email</option>
                                                                <option value="sms" <?php if(in_array('sms', explode(',', $data['notified_by']))){echo 'selected';}?>>SMS</option> 
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php } ?>
                                                <li>
                                                    <?php echo form_label('', ''); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="col-md-6 col-lg-6 col-xl-6" style="padding-left:0px">
                                                            <div class="input-group" style="float: left;">
                                                                      <div class="col-md-6 col-lg-6 col-xl-6 error"  id="sh_hours_globe" style="padding:0px;width:212px;"></div>
                                                             
                                                                       <div class="col-md-6 col-lg-6 col-xl-6 error"  id="sh_mins_globe" style="padding:0px"> </div>  
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
                                                    <input type="submit" name="submit" value="Apply" class="btn btn-success">
                                                    <input type="submit" name="submit" value="Save" class="btn btn-success">
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

<script type="text/javascript">
 
    $(document).ready(function () {
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        });

        <?php if(IS_NOTIFICATION_ENABLED == 1) {?>
        $('#notified_by').select2({
            closeOnSelect : false,
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

    <?php if(IS_TIMEZONE_ACTIVE  && $show_timezone != '') { ?>
    $('#timezone').select2();
    <?php } ?>
</script>
<script>

    $('.js-form').submit(function(event) {
        
        // Check for phone number
        if($('#PhoneNumber').val() != '' && $('#PhoneNumber').val().trim() != '(___) ___-____' && !fpn($('#PhoneNumber').val(), '', true)){
            alertify.alert('Error!', 'Invalid phone number.', function(){ return; });
            event.preventDefault();
            return;
        }

        // Remove and set phone extension
        $('#js-phonenumber').remove();
        // Check the fields
        if($('#PhoneNumber').val().trim() == '(___) ___-____') $('#PhoneNumber').val('');
        else $(this).append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+($('#PhoneNumber').val().replace(/\D/g, ''))+'" />');
      
        var min_flag = 0,
        hrs_flag = 0,
        errorMSG = '',
        hrs = $("#sh_hours").val(),
        mins=$("#sh_mins").val();
       
        if (hrs < 1) errorMSG = "Minimum allowed hours are 1";
        else if (hrs > 23) errorMSG = "Maximum allowed hours are 23";
        else if (hrs=='') errorMSG = "Shift hours are required";
        else if (mins < 0) errorMSG = "Minimum allowed minutes are 1";
        else if (mins > 59) errorMSG = "Maximum allowed minutes are 59";
        else if (mins =='') errorMSG = "Shift minutes are required";

        if (errorMSG != '') {
            alertify.alert(errorMSG);
            event.preventDefault();
            return;
        }
    });

    $.each($('.js-phone'), function() {
        var v = fpn($(this).val().trim());
        if(typeof(v) === 'object'){
            $(this).val( v.number );
            setCaretPosition(this, v.cur);
        }else $(this).val( v );
    });


    $('.js-phone').keyup(function(e){
        var val = fpn($(this).val().trim());
        if(typeof(val) === 'object'){
            $(this).val( val.number );
            setCaretPosition(this, val.cur);
        }else $(this).val( val );
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
        if(cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
        //
        if (match) {
            var intlCode = '';
            if( format == 'e164') intlCode = (match[1] ? '+1 ' : '');
            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
        } else{
            var af = '', an = '', cur = 1;
            if(cleaned.substring(0,1) != '') { af += "(_"; an += '('+cleaned.substring(0,1); cur++; }
            if(cleaned.substring(1,2) != '') { af += "_";  an += cleaned.substring(1,2); cur++; }
            if(cleaned.substring(2,3) != '') { af += "_) "; an += cleaned.substring(2,3)+') '; cur = cur + 3; }
            if(cleaned.substring(3,4) != '') { af += "_"; an += cleaned.substring(3,4);  cur++;}
            if(cleaned.substring(4,5) != '') { af += "_"; an += cleaned.substring(4,5);  cur++;}
            if(cleaned.substring(5,6) != '') { af += "_-"; an += cleaned.substring(5,6)+'-';  cur = cur + 2;}
            if(cleaned.substring(6,7) != '') { af += "_"; an += cleaned.substring(6,7);  cur++;}
            if(cleaned.substring(7,8) != '') { af += "_"; an += cleaned.substring(7,8);  cur++;}
            if(cleaned.substring(8,9) != '') { af += "_"; an += cleaned.substring(8,9);  cur++;}
            if(cleaned.substring(9,10) != '') { af += "_"; an += cleaned.substring(9,10);  cur++;}

            if(is_return) return match === null ? false : true;

            return { number: default_number.replace(af, an), cur: cur };
        }
    }

    // Change cursor position in input
    function setCaretPosition(elem, caretPos) {
        if(elem != null) {
            if(elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            }
            else {
                if(elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else elem.focus();
            }
        }
    }
</script>

<style>
   /* Remove the radius from left fro phone field*/
   .input-group input{ border-top-left-radius: 0; border-bottom-left-radius: 0; }
   .select2-container-multi .select2-choices .select2-search-choice,
   .select2-container-multi .select2-choices .select2-search-field{ width: auto; }
   .select2-container-multi{ padding: 0 !important; }
</style>