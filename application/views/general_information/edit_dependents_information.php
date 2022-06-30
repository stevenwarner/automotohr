<?php
    $company_id = 0;
    $users_type = '';
    $users_sid = 0;
    $back_url = base_url('general_info');
    $dependants_arr = array();

    if (isset($employee)) {

        $company_id = $company_sid;
        $users_type = 'employee';
        $users_sid = $employee['sid'];
        $dependants_arr = $dependant_info;
    }
?>



<div class="main">
    <div class="container">
        <div class="row">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('general_info'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-angle-left"></i> General Information</a>
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
                  <h2 class="section-ttile">Update Dependent</h2>
                </div>
                <div class="form-wrp">
                    <form id="dependantForm" action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="perform_action" name="perform_action" value="add_dependent" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_id; ?>" />
                        <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                        <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                        <input type="hidden" id="sid" name="sid" value="<?php echo $sid; ?>" />
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'first_name'; ?>
                                    <?php echo form_label('First Name <span class="required">*</span>', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['first_name'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'last_name'; ?>
                                    <?php echo form_label('Last Name <span class="required">*</span>', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['last_name'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'address'; ?>
                                    <?php echo form_label('Address', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['address'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'address_line'; ?>
                                    <?php echo form_label('Address 2', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['address_line'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'Location_Country'; ?>
                                    <?php $country_id = ((isset($dependant_info[$field_id]) && !empty($dependant_info[$field_id])) ? $dependant_info[$field_id] : ''); ?>
                                    <?php echo form_label('Country:', $field_id); ?>
                                    <select class="form-control" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'Location_State', <?php echo $dependant_info['Location_State']?>)">
                                        <option value="">Please Select</option>
                                        <?php foreach ($active_countries as $active_country) { ?>
                                            <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                            <option <?php echo set_select($field_id, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>" > <?= $active_country["country_name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'Location_State'; ?>
                                    <?php $state_id = ((isset($dependant_info[$field_id]) && !empty($dependant_info[$field_id])) ? $dependant_info[$field_id] : ''); ?>
                                    <?php echo form_label('State:', $field_id); ?>                                    
                                    <select class="form-control" name="<?php echo $field_id?>" id="<?php echo $field_id?>">
                                        <?php if (empty($state_id)) { ?>
                                            <option value="">Select State</option> <?php
                                        } else {
                                            foreach ($active_states[$country_id] as $active_state) { ?>
                                                <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                <option <?php echo set_select($field_id, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
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
                                    <?php echo form_input($field_name, $dependant_info['city'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'postal_code'; ?>
                                    <?php echo form_label('Postal Code', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['postal_code'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'phone'; ?>
                                    <?php echo form_label('Phone <span class="required">*</span>', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['phone'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'birth_date'; ?>
                                    <?php echo form_label('Date of Birth', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['birth_date'], 'class="form-control datepicker" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'relationship'; ?>
                                    <?php echo form_label('Relationship <span class="required">*</span>', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['relationship'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'ssn'; ?>
                                    <?php echo form_label('Social Security Number', $field_name); ?>
                                    <?php echo form_input($field_name, $dependant_info['ssn'], 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_name = 'gender'; ?>
                                    <?php echo form_label('Gender', $field_name); ?>
                                    <div class="hr-select-dropdown">
                                        <select id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" class="form-control">
                                            <option value="male" <?php echo $dependant_info['gender'] == 'male' ? 'selected="selected"' : ''; ?>>Male</option>
                                            <option value="female" <?php echo $dependant_info['gender'] == 'female' ? 'selected="selected"' : ''; ?>>Female</option>
                                        </select>
                                    </div>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">                                
                                <?php $field_name = 'family_member'; ?>
                                <label for="<?php echo $field_name; ?>" class="control control--checkbox">
                                    Add Family Members
                                    <input name="<?php echo $field_name; ?>"
                                           id="<?php echo $field_name; ?>"
                                        <?php if (isset($dependant_info['family_member']) && $dependant_info['family_member'] == 'on') { ?> checked <?php } ?>
                                           type="checkbox">
                                    <div class="control__indicator"></div>
                                </label>                                
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="btn-wrp full-width text-right">
                                    <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>">cancel</a>
                                    <input class="btn btn-info" value="Update" type="submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function func_delete_dependent(dependent_sid) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you sure you want to delete this dependent?',
            function () {
                $('#form_delete_dependent_' + dependent_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    $(document).ready(function () {
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
            messages:{
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
            }
        });
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
        $('#Location_Country').trigger('change');
    });

    $(document).ready(function () {
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    });

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