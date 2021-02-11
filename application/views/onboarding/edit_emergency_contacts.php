<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
$field_country = '';
$field_state = '';
$field_city = '';
$field_zipcode = '';
$field_address = '';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/emergency_contacts/' . $unique_sid);
    $emergency_contacts_arr = $emergency_contacts;
    $delete_post_url = current_url();
    $save_post_url = current_url();
    //Field Names
    $field_country = 'Location_Country';
    $field_state = 'Location_State';
    $field_city = 'Location_City';
    $field_zipcode = 'Location_ZipCode';
    $field_address = 'Location_Address';
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = base_url('emergency_contacts');
    $emergency_contacts_arr = $emergency_contacts;
    $delete_post_url = current_url();
    $save_post_url = base_url('edit_emergency_contacts');
    //Field Names
    $field_country = 'Location_Country';
    $field_state = 'Location_State';
    $field_city = 'Location_City';
    $field_zipcode = 'Location_ZipCode';
    $field_address = 'Location_Address';
} ?>


<?php 
    //
    $is_regex = 0;
    $input_group_start = $input_group_end = '';
    $primary_phone_number_cc = $primary_phone_number = $emergency_contacts['PhoneNumber'];
    if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
        // $is_regex = 1;
        // $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
        // $input_group_end   = '</div>';
        // $primary_phone_number = phonenumber_format($emergency_contacts['PhoneNumber'], true);
        // $primary_phone_number_cc = phonenumber_format($emergency_contacts['PhoneNumber']);
    }else{
        if($primary_phone_number === '+1') $primary_phone_number = ''; 
        if($primary_phone_number_cc === '+1') $primary_phone_number_cc = 'Not Specified'; 
    }
?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo $back_url; ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Emergency Contacts</a>
                </div>
            </div>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<!--                    <div class="page-header">-->
<!--                      <h1 class="section-ttile">emergency contacts</h1>-->
<!--                    </div>-->
                    <div class="page-header">
                      <h2 class="section-ttile">Edit emergency contact</h2>
                    </div>
                    <div class="form-wrp">
                    <form id="add_emergency_contacts" action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="perform_action" name="perform_action" value="edit_emergency_contact" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                        <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                        <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                        <input type="hidden" id="sid" name="sid" value="<?php echo $sid; ?>" />
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'first_name'; ?>
                                    <?php echo form_label('First Name <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, $emergency_contacts['first_name'], 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'last_name'; ?>
                                    <?php echo form_label('Last Name <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, $emergency_contacts['last_name'], 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'email'; ?>
                                    <?php echo form_label('Email: <span class="required">*</span>', $field_id); ?>
                                    <input type="email" value="<?php echo $emergency_contacts['email']; ?>" class="form-control" data-rule-required="true" data-rule-email="true" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" />
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'PhoneNumber'; ?>
                                    <?php echo form_label('Phone Number: <span class="required">*</span>', $field_id); ?>
                                    <?=$input_group_start;?>
                                    <?php echo form_input($field_id, $primary_phone_number, 'class="form-control js-phone" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                    <?=$input_group_end;?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_country; ?>
                                    <?php $country_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                    <?php echo form_label('Country:', $field_id);?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, '<?php echo $field_state; ?>', <?php echo $emergency_contacts['Location_State'];?>)">
                                            <option value="">Please Select</option>
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                <?php $default_selected = $emergency_contacts['Location_Country'] == $active_country['sid'] ? true : false; ?>
                                                <option <?php echo set_select($field_id, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_state; ?>
                                    <?php $state_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                    <?php echo form_label('State:', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="<?php echo $field_id?>" id="<?php echo $field_id?>">
                                            <?php if (empty($state_id)) { ?>
                                                <option value="">Select State</option> <?php
                                            } else {
                                                foreach ($active_states[$country_id] as $active_state) { ?>
                                                    <?php $default_selected = $emergency_contacts['Location_State'] == $active_state['sid'] ? true : false; ?>
                                                    <option <?php echo set_select($field_id, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_city; ?>
                                    <?php echo form_label('City:', $field_id); ?>
                                    <?php echo form_input($field_id, $emergency_contacts['Location_City'], 'class="form-control" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_zipcode; ?>
                                    <?php echo form_label('Zipcode:', $field_id); ?>
                                    <?php echo form_input($field_id, $emergency_contacts['Location_ZipCode'], 'class="form-control" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <?php $field_id = $field_address; ?>
                                    <?php echo form_label('Address:', $field_id); ?>
                                    <?php echo form_input($field_id, $emergency_contacts['Location_Address'], 'class="form-control" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'Relationship'; ?>
                                    <?php echo form_label('Relationship: <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, $emergency_contacts['Relationship'], 'class="form-control" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'priority'; ?>
                                    <?php echo form_label('Set Priority: <span class="required">*</span>', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>">
                                            <option value="">Select Priority</option>
                                            <option value="1" <?php
                                            if ($emergency_contacts['priority'] == '1') {
                                                echo 'selected';
                                            }
                                            ?>>1</option>
                                            <option value="2" <?php
                                            if ($emergency_contacts['priority'] == '2') {
                                                echo 'selected';
                                            }
                                            ?>>2</option>
                                            <option value="3" <?php
                                            if ($emergency_contacts['priority'] == '3') {
                                                echo 'selected';
                                            }
                                            ?>>3</option>
                                        </select>
                                        <?php echo form_error($field_id); ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="btn-wrp full-width mrg-top-20 text-right">
                            <a class="btn btn-black margin-right" href="<?php echo $back_url?>">Cancel</a>
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
                </div>
                <?php if($users_type != 'applicant') { ?>
                    <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                        <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
                    <!-- </div> -->
                <?php } ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#add_emergency_contacts').validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                email: {
                    required: true,
                    email: true
                },
                PhoneNumber: {
                    required: true,
                    <?php if($is_regex === 1) { ?>
                    pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
                    <?php } else { ?>
                    pattern: /^[0-9\- ]+$/
                    <?php } ?>
                },
                Location_City: {
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                Location_ZipCode: {
                    pattern: /^[0-9\-]+$/
                },
                Location_Address: {
                    pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                },
                Relationship: {
                    required: true,
                    pattern: /^[a-zA-Z\-#,':;. ]+$/
                },
                priority: {
                    required: true
                }
            },
            messages: {
                first_name: {
                    required: 'First Name is required',
                    pattern: 'Letters, numbers and dashes only please'
                },
                last_name: {
                    required: 'Last Name is required',
                    pattern: 'Letters, numbers and dashes only please'
                },
                email: {
                    required: 'E-Mail is required',
                    email: 'Valid E-Mail Please'
                },
                PhoneNumber: {
                    required: 'Phone Number is required',
                    <?php if($is_regex === 1) { ?>
                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                    <?php } else { ?>
                    pattern: 'numbers and dashes only please'
                    <?php } ?>
                },
                Location_City: {
                    pattern: 'Letters, numbers and dashes only please',
                },
                Location_ZipCode: {
                    pattern: 'Numeric values and dashes only please'
                },
                Location_Address: {
                    pattern: 'Valid Address Please'
                },
                Relationship: {
                    required: 'Relationship is required',
                    pattern: 'Please provide valid relation'
                },
                priority: {
                    required: 'Priority is required'
                }
            },
            errorPlacement: function(e, el){
                <?php if($is_regex === 1) { ?>
                if($(el)[0].id == 'PhoneNumber') $(el).parent().after(e);
                else $(el).after(e);
                <?php } else { ?>
                $(el).after(e);
                <?php } ?>
            },
            submitHandler: function (form) {
            <?php if($is_regex === 1) { ?>
                                                            
            var is_error = false;
            // Check for phone number
            if($('#PhoneNumber').val() != '' && $('#PhoneNumber').val().trim() != '(___) ___-____' && !fpn($('#PhoneNumber').val(), '', true)){
                alertify.alert('Invalid phone number.', function(){ return; });
                is_error = true;
                return;
            }

            if (is_error === false) {
                // Remove and set phone extension
                $('#js-phonenumber').remove();
                // Check the fields
                if($('#PhoneNumber').val().trim() == '(___) ___-____') $('#PhoneNumber').val('');
                else $("#add_emergency_contacts").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+($('#PhoneNumber').val().replace(/\D/g, ''))+'" />');
    
                form.submit();
            }
            <?php } else { ?>
                form.submit();
            <?php } ?>
            }                                            
            });
        $('#Location_Country').trigger('change');
    });

    function func_delete_emergency_contact(contact_id) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to delete this contact?',
            function () {
                $('#form_delete_emergency_contact_' + contact_id).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function getStates(val, states, select_id, select_val = null) {
        var html = '';
        var select = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            html += '<option value="">Select State</option>';
            for (var i = 0; i < allstates.length; i++) {
                select = '';
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                if(select_val == id){
                    select = 'selected="selected"';
                }
                html += '<option value="' + id + '"'+ select +'>' + name + '</option>';
            }
            $('#' + select_id).html(html);
            $('#' + select_id).trigger('change');
        }
    }
</script>



 <?php if($is_regex === 1) { ?>

<script>
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

<?php } ?>