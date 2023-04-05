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
    $back_url = base_url('onboarding/getting_started/' . $unique_sid);
    $emergency_contacts_arr = $emergency_contacts;
    $delete_post_url = current_url();
    $save_post_url = current_url();
    //Field Names
    $field_country = 'country';
    $field_state = 'state';
    $field_city = 'city';
    $field_zipcode = 'Location_ZipCode';
    $field_address = 'Location_Address';
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $emergency_contacts_arr = $emergency_contacts;
    $delete_post_url = current_url();
    $save_post_url = base_url('add_emergency_contacts');
    //Field Names
    $field_country = 'Location_Country';
    $field_state = 'Location_State';
    $field_city = 'Location_City';
    $field_zipcode = 'Location_ZipCode';
    $field_address = 'Location_Address';
} ?>

<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                      <h1 class="section-ttile">emergency contacts</h1>
                    </div>
                    <div class="form-wrp">
                        <div class="table-responsive table-outer">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td class="col-lg-2">Name</td>
                                        <td class="col-lg-2">Relationship</td>
                                        <td class="col-lg-2">Phone No.</td>
                                        <td class="col-lg-3">Address</td>
                                        <td class="text-center col-lg-1">Priority</td>
                                        <td class="text-center col-lg-2" colspan="2">Actions</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($emergency_contacts_arr)) { ?>
                                        <?php foreach($emergency_contacts_arr as $contact) { ?>
                                            <tr>
                                                <td><?php echo $contact['first_name'] . ' ' . $contact['last_name']; ?></td>
                                                <td><?php echo $contact['Relationship']; ?></td>
                                                <td><?php echo $contact['PhoneNumber']; ?></td>
                                                <td><?php echo $contact['Location_Address']; ?></td>
                                                <td class="text-center"><?php echo $contact['priority']; ?></td>
                                                <td>
                                                    <?php if(!isset($onboarding_flag)){?>
                                                        <a href="<?php echo base_url('edit_emergency_contacts/'.$contact['sid'])?>" class="btn btn-info btn-block">Edit</a>
                                                    <?php }
                                                    else{?>
                                                        <a href="<?php echo base_url('onboarding/edit_emergency_contacts/'.$unique_sid . '/' .$contact['sid'])?>" class="btn btn-info btn-block">Edit</a>
                                                    <?php }?>
                                                </td>
                                                <td class="text-center">
                                                    <form id="form_delete_emergency_contact_<?php echo $contact['sid']?>" enctype="multipart/form-data" method="post" action="<?php echo $delete_post_url; ?>">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_emergency_contact" />
                                                        <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                                                        <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                                                        <input type="hidden" id="contact_sid" name="contact_sid" value="<?php echo $contact['sid']; ?>" />
                                                        <button type="button" onclick="func_delete_emergency_contact(<?php echo $contact['sid']?>);" class="btn btn-danger btn-block">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td class="text-center" colspan="6">No Records</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="page-header">
                      <h2 class="section-ttile">Add new emergency contact</h2>
                    </div>
                    <div class="form-wrp">
                    <form id="add_emergency_contacts" action="<?php echo $save_post_url; ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="perform_action" name="perform_action" value="add_emergency_contact" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                        <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                        <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'first_name'; ?>
                                    <?php echo form_label('First Name <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, '', 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'last_name'; ?>
                                    <?php echo form_label('Last Name <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, '', 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'email'; ?>
                                    <?php echo form_label('Email: <span class="required">*</span>', $field_id); ?>
                                    <input type="email" class="form-control" data-rule-required="true" data-rule-email="true" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" />
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'PhoneNumber'; ?>
                                    <?php echo form_label('Phone Number: <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, '', 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_country; ?>
                                    <?php //$country_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                    <?php echo form_label('Country:', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, '<?php echo $field_state; ?>')">
                                            <option value="">Please Select</option>
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                <?php //$default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                <option <?php //echo set_select($field_id, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_state; ?>
                                    <?php //$state_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                    <?php echo form_label('State:', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="<?php echo $field_id?>" id="<?php echo $field_id?>">
                                            <?php if (empty($state_id)) { ?>
                                                <option value="">Select State</option> <?php
                                            } else {
                                                foreach ($active_states[$country_id] as $active_state) { ?>
                                                    <?php //$default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                    <option <?php //echo set_select($field_id, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
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
                                    <?php echo form_input($field_id, '', 'class="form-control" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_zipcode; ?>
                                    <?php echo form_label('Zipcode:', $field_id); ?>
                                    <?php echo form_input($field_id, '', 'class="form-control" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <?php $field_id = $field_address; ?>
                                    <?php echo form_label('Address:', $field_id); ?>
                                    <?php echo form_input($field_id, '', 'class="form-control" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'Relationship'; ?>
                                    <?php echo form_label('Relationship: <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, '', 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'priority'; ?>
                                    <?php echo form_label('Set Priority: <span class="required">*</span>', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="<?php echo $field_id; ?>" data-rule-required="true" id="<?php echo $field_id; ?>">
                                            <option value="">Select Priority</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="btn-wrp full-width mrg-top-20 text-right">
                            <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>">cancel</a>
                            <button type="submit" class="btn btn-info">Save</button>
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
        $('#add_emergency_contacts').validate();
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