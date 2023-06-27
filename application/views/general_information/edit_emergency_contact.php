<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = base_url('general_info');
$dependants_arr = array();
$field_country = '';
$field_state = '';
$field_city = '';
$field_zipcode = '';
$field_address = '';

if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $emergency_contacts_arr = $emergency_contacts;
    $field_country = 'Location_Country';
    $field_state = 'Location_State';
    $field_city = 'Location_City';
    $field_zipcode = 'Location_ZipCode';
    $field_address = 'Location_Address';
}
?>

<div class="main">
    <div class="container">
        <div class="row">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-angle-left"></i> General Information</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
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
                                    <?php
                                    $field_id = 'email';
                                    $emailRequired = '';
                                    if ($contactOptionsStatus['emergency_contact_email_status'] == 1) {
                                        $emailRequired = ' <span class="required">*</span>';
                                    }
                                    ?>
                                    <?php echo form_label('Email:' . $emailRequired, $field_id); ?>
                                    <input type="email" value="<?php echo $emergency_contacts['email']; ?>" class="form-control" data-rule-email="true" <?php echo $contactOptionsStatus['emergency_contact_email_status'] == 1 ? 'data-rule-required="true"' : ''  ?> name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" />
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php
                                    $field_id = 'PhoneNumber';
                                    $phoneNumberRequired = '';
                                    $phoneNumberRequiredRule = '';
                                    if ($contactOptionsStatus['emergency_contact_phone_number_status'] == 1) {
                                        $phoneNumberRequired = ' <span class="required">*</span>';
                                        $phoneNumberRequiredRule = ' data-rule-required="true"';
                                    }
                                    ?>
                                    <?php echo form_label('Phone Number:' . $phoneNumberRequired, $field_id); ?>
                                    <?php echo form_input($field_id, $emergency_contacts['PhoneNumber'], 'class="form-control" id="' . $field_id . ' "' . $phoneNumberRequiredRule); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_country; ?>
                                    <?php $country_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                    <?php echo form_label('Country:', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, '<?php echo $field_state; ?>', <?php echo $emergency_contacts['Location_State']; ?>)">
                                            <option value="">Please Select</option>
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                <?php $default_selected = $emergency_contacts['Location_Country'] == $active_country['sid'] ? true : false; ?>
                                                <option <?php echo set_select($field_id, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
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
                                        <select class="form-control" name="<?php echo $field_id ?>" id="<?php echo $field_id ?>">
                                            <?php if (empty($state_id)) { ?>
                                                <option value="">Select State</option> <?php
                                                                                    } else {
                                                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                    <?php $default_selected = $emergency_contacts['Location_State'] == $active_state['sid'] ? true : false; ?>
                                                    <option <?php echo set_select($field_id, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
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
                                    <?php echo form_label('Set Priority:<span class="required">*</span>', $field_id); ?>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-wrp full-width mrg-top-20 text-right">
                            <a class="btn btn-black margin-right" href="<?php echo $back_url ?>">Cancel</a>
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#add_emergency_contacts').validate();
        $('#Location_Country').trigger('change');
    });

    function func_delete_emergency_contact(contact_id) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to delete this contact?',
            function() {
                $('#form_delete_emergency_contact_' + contact_id).submit();
            },
            function() {
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
                if (select_val == id) {
                    select = 'selected="selected"';
                }
                html += '<option value="' + id + '"' + select + '>' + name + '</option>';
            }
            $('#' + select_id).html(html);
            $('#' + select_id).trigger('change');
        }
    }
</script>