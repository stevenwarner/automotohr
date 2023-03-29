<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/getting_started/' . $unique_sid);
    $dependants_arr = $dependents;
    $delete_post_url = current_url();
    $save_post_url = current_url();
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $dependants_arr = $emergency_contacts;
    $delete_post_url = current_url();
    $save_post_url = base_url('add_dependant_information');
}

?>

<?php
//
$is_regex = 0;
$input_group_start = $input_group_end = '';
// if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
//     $is_regex = 1;
//     $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
//     $input_group_end   = '</div>';
// }
?>


<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">Dependents</h1>
                </div>
                <div class="table-responsive table-outer">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td class="col-lg-2">Name</td>
                                <td class="col-lg-2">Phone No.</td>
                                <td class="col-lg-4">Address</td>
                                <td class="col-lg-2">Relationship</td>
                                <td class="text-center col-lg-2" colspan="2">Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dependants_arr)) { ?>
                                <?php foreach ($dependants_arr as $dependent) { ?>
                                    <tr>
                                        <td><?php echo $dependent['first_name'] . ' ' . $dependent['last_name']; ?></td>
                                        <?php if ($is_regex === 1) { ?>
                                            <td><?php echo phonenumber_format($dependent['phone']); ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $dependent['phone']; ?></td>
                                        <?php } ?>
                                        <td><?php echo $dependent['address']; ?></td>
                                        <td><?php echo $dependent['relationship']; ?></td>
                                        <td class="text-center">
                                            <?php if (!isset($onboarding_flag)) { ?>
                                                <a href="<?php echo base_url('edit_dependant_information/' . $dependent['sid']) ?>" class="btn btn-info btn-block">Edit</a>
                                            <?php } else { ?>
                                                <a href="<?php echo base_url('onboarding/edit_dependant_information/' . $unique_sid . '/' . $dependent['sid']) ?>" class="btn btn-info btn-block">Edit</a>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <form id="form_delete_dependent_<?php echo $dependent['sid']; ?>" method="post" action="<?php echo $delete_post_url; ?>" enctype="multipart/form-data">
                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_dependent" />
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                                                <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                                                <input type="hidden" id="dependent_sid" name="dependent_sid" value="<?php echo $dependent['sid']; ?>" />
                                                <button type="button" class="btn btn-danger btn-block" onclick="func_delete_dependent(<?php echo $dependent['sid']; ?>);">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center">No Records</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="page-header">
                    <h2 class="section-ttile">Add new Dependent</h2>
                </div>
                <div class="form-wrp">
                    <form id="dependantForm" action="<?php echo $save_post_url; ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="perform_action" name="perform_action" value="add_dependent" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                        <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                        <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'first_name'; ?>
                                    <?php echo form_label('First Name <span class="required">*</span>', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'last_name'; ?>
                                    <?php echo form_label('Last Name <span class="required">*</span>', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'address'; ?>
                                    <?php echo form_label('Address', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'address_line'; ?>
                                    <?php echo form_label('Address 2', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'Location_Country'; ?>
                                    <?php $country_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                    <?php echo form_label('Country:', $field_id); ?>
                                    <select class="form-control" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'Location_State')">
                                        <option value="">Please Select</option>
                                        <?php foreach ($active_countries as $active_country) { ?>
                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                            <option <?php echo set_select($field_id, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'Location_State'; ?>
                                    <?php $state_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                    <?php echo form_label('State:', $field_id); ?>
                                    <select class="form-control" name="<?php echo $field_id ?>" id="<?php echo $field_id ?>">
                                        <?php if (empty($state_id)) { ?>
                                            <option value="">Select State</option> <?php
                                                                                } else {
                                                                                    foreach ($active_states[$country_id] as $active_state) { ?>
                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                <option <?php echo set_select($field_id, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'city'; ?>
                                    <?php echo form_label('City', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'postal_code'; ?>
                                    <?php echo form_label('Postal Code', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'phone'; ?>
                                    <?php echo form_label('Phone <span class="required">*</span>', $field_name); ?>
                                    <?= $input_group_start; ?>
                                    <?php echo form_input($field_name, '', 'class="form-control js-phone" id="' . $field_name . '"'); ?>
                                    <?= $input_group_end; ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'birth_date'; ?>
                                    <?php echo form_label('Date of Birth', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control datepicker" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'relationship'; ?>
                                    <?php echo form_label('Relationship <span class="required">*</span>', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'ssn'; ?>
                                    <?php echo form_label('Social Security Number', $field_name); ?>
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'gender'; ?>
                                    <?php echo form_label('Gender', $field_name); ?>
                                    <div class="hr-select-dropdown">
                                        <select id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" class="form-control">
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php $field_name = 'family_member'; ?>
                                <label for="<?php echo $field_name; ?>" class="control control--checkbox">
                                    Add Family Members
                                    <input name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" type="checkbox">
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="btn-wrp full-width text-right">
                                    <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>">cancel</a>
                                    <input class="btn btn-info" value="save" type="submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php if ($users_type != 'applicant') { ?>
                <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); 
                ?>
                <!-- </div> -->
            <?php } ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    function func_delete_dependent(dependent_sid) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you sure you want to delete this dependent?',
            function() {
                $('#form_delete_dependent_' + dependent_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    $(document).ready(function() {
        $('#dependantForm').validate({
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                phone: {
                    required: true
                },
                relationship: {
                    required: true
                }
            },
            messages: {
                first_name: {
                    message: 'First Name is required'
                },
                last_name: {
                    message: 'Last Name is required'
                },
                phone: {
                    message: 'Phone number is required'
                },
                relationship: {
                    message: 'Relationship is required'
                }
            },
            errorPlacement: function(e, el) {
                <?php if ($is_regex === 1) { ?>
                    if ($(el)[0].id == "phone") $(el).parent().after(e);
                    else $(el).after(e);
                <?php } else { ?>
                    $(el).after(e);
                <?php } ?>
            },
            submitHandler: function(form) {
                <?php if ($is_regex === 1) { ?>
                    var is_error = false;
                    // Check for phone number
                    if ($("#phone").val() != '' && $("#phone").val().trim() != '(___) ___-____' && !fpn($("#phone").val(), '', true)) {
                        alertify.alert('Invalid phone number.', function() {
                            return;
                        });
                        is_error = true;
                        return;
                    }

                    if (is_error === false) {
                        // Remove and set phone extension
                        $('#js-phonenumber').remove();
                        // Check the fields
                        if ($("#phone").val().trim() == '(___) ___-____') $("#phone").val('');
                        else $("#dependantForm").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1' + ($("#phone").val().replace(/\D/g, '')) + '" />');

                        form.submit();
                    }
                <?php } else { ?>
                    form.submit();
                <?php } ?>
            }
        });
        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    $(document).ready(function() {
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    });

    function getStates(val, states, select_id) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            html += '<option value="">Select State</option>';
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#' + select_id).html(html);
            $('#' + select_id).trigger('change');
        }
    }
</script>



<?php if ($is_regex === 1) { ?>

    <script>
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
<?php } ?>