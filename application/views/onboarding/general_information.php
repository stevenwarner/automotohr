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
$title = '';

    $company_sid = $employee['parent_sid'];
    $users_type = $employee['access_level'];
    $users_sid = $employee['sid'];

    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');

    $user_information = $employee;
    if(is_array($extra_info)) {
        $user_information = array_merge($employee, $extra_info);
    }

    $save_post_url = current_url();
    //Field Names
    $field_sid = 'id';
    $field_profile_picture = 'profile_picture';
    $field_address = 'Location_Address';
    $field_phone = 'PhoneNumber';
    $field_city = 'Location_City';
    $field_zipcode = 'Location_ZipCode';
    $field_country = 'Location_Country';
    $field_state = 'Location_State';
    $field_youtube = 'YouTubeVideo';
    $title = 'My Profile';

    // Replace '+1' with ''
    // if(isset($user_information[$field_phone]) && $user_information[$field_phone] != ''){
    //     $user_information[$field_phone] = str_replace('+1', '', $user_information[$field_phone]);
    // }
    // if(isset($user_information['secondary_PhoneNumber']) && $user_information['secondary_PhoneNumber'] != ''){
    //     $user_information['secondary_PhoneNumber'] = str_replace('+1', '', $user_information['secondary_PhoneNumber']);
    // }
    // if(isset($user_information['other_PhoneNumber']) && $user_information['other_PhoneNumber'] != ''){
    //     $user_information['other_PhoneNumber'] = str_replace('+1', '', $user_information['other_PhoneNumber']);
    // }

    //
    $is_regex = 0;
    $input_group_start = $input_group_end = '';
    $primary_phone_number_cc = $primary_phone_number = $user_information[$field_phone];
    if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
        $is_regex = 1;
        $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
        $input_group_end   = '</div>';
        $primary_phone_number = phonenumber_format($user_information[$field_phone], true);
        $primary_phone_number_cc = phonenumber_format($user_information[$field_phone]);
    }else{
        if($primary_phone_number === '+1') $primary_phone_number = ''; 
        if($primary_phone_number_cc === '+1') $primary_phone_number_cc = ''; 
    }

?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
<!--                <div class="btn-panel">-->
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo  $back_url;?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo base_url('login_password')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-fw fa-unlock"></i> Login Credentials</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo base_url('eeo/form')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-fw fa-file"></i> EEOC Form</a>
                        </div>
                    </div>
<!--                    <a href="--><?php //echo $back_url; ?><!--" class="btn btn-info"><i class="fa fa-angle-left"></i> Dashboard</a>-->
<!--                    <a href="--><?php //echo base_url('incident_reporting_system/list_incidents')?><!--" class="btn btn-info"><i class="fa fa-heartbeat"></i> Login Credentials</a>-->
<!--                </div>                -->
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                <div class="form-wrp">
                    <form id="form_applicant_information" method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                        <input type="hidden" id="perform_action" name="perform_action" value="update_applicant_information" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                        <input type="hidden" id="<?php echo $field_sid; ?>" name="<?php echo $field_sid; ?>" value="<?php echo $users_sid; ?>" />

                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 pull-right">
                                <div id="" class="img-thumbnail emply-picture pull-right">
                                    <?php $field_id = $field_profile_picture;?>
                                    <?php $temp = isset($user_information[$field_id]) && !empty($user_information[$field_id]) ? $user_information[$field_id] : base_url('assets/images/default_pic.jpg'); ?>
                                    <?php if(isset($user_information[$field_id]) && !empty($user_information[$field_id])) { ?>
                                        <img class="img-responsive img-rounded" src="<?php echo AWS_S3_BUCKET_URL . $temp; ?>">
                                    <?php } else { ?>
                                        <img class="img-responsive" src="<?php echo $temp; ?>">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <?php $field_id = 'first_name'; ?>
                                            <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                            <?php echo form_label('First Name: <span class="required">*</span>', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '" data-rule-required="true"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <?php $field_id = 'last_name'; ?>
                                            <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                            <?php echo form_label('Last Name: <span class="required">*</span>', $field_id); ?>
                                            <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '" data-rule-required="true"'); ?>
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <?php $field_id = 'email'; ?>
                                            <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                            <?php echo form_label('Email: ', $field_id); ?><!-- <span class="required">*</span>-->
                                            <input type="email" class="form-control" data-rule-email="true" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" value="<?php echo set_value($field_id, $temp); ?>" />
                                            <?php echo form_error($field_id); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_address; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Address: <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '" data-rule-required="true"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_phone; ?>
                                    <?php $temp = $primary_phone_number; ?>
                                    <?php echo form_label('Mobile Number:', $field_id); ?>
                                        <?=$input_group_start;?>
                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '" data-rule-required="true" placeholder="(555) 123-1234"'); ?>
                                        <?=$input_group_end;?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_city; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('City: <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '" data-rule-required="true"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_zipcode; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Zipcode: <span class="required">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '" data-rule-required="true"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = $field_country; ?>
                                    <?php $country_id = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Country: <span class="required">*</span>', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control " data-rule-required="true" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, '<?php echo $field_state?>')">
                                            <option value="">Please Select</option>
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
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
                                    <?php $state_id = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('State: <span class="required">*</span>', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" data-rule-required="true" name="<?php echo $field_id?>" id="<?php echo $field_id?>">
                                            <?php if (empty($state_id)) { ?>
                                                <option value="">Select State</option> <?php
                                            } else {
                                                foreach ($active_states[$country_id] as $active_state) { ?>
                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                    <option <?php echo set_select($field_id, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>" ><?= $active_state["state_name"] ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Profile picture:</label>
                                    <?php $field_id = $field_profile_picture; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <div class="upload-file form-control input-grey">
                                        <span class="selected-file" id="name_<?php echo $field_profile_picture; ?>">No file selected</span>
                                        <input name="<?php echo $field_profile_picture; ?>" id="<?php echo $field_profile_picture; ?>" onchange="check_file_all('<?php echo $field_profile_picture; ?>')" accept="image/*" type="file">
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'secondary_email'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Secondary Email:', $field_id); ?>
                                    <input type="email" data-rule-email="true" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" value="<?php echo set_value($field_id, $temp); ?>" class="form-control" />
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'secondary_PhoneNumber'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Secondary Mobile Number:', $field_id); ?>
                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'other_email'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Other Email:', $field_id); ?>
                                    <input type="email" data-rule-email="true" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" value="<?php echo set_value($field_id, $temp); ?>" class="form-control" />
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'other_PhoneNumber'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Telephone Number:', $field_id); ?>
                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(isset($applicant)) { ?>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <?php $field_id = 'referred_by_name'; ?>
                                        <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                        <?php echo form_label('Referred By:', $field_id); ?>
                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                        <?php echo form_error($field_id); ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <?php $field_id = 'referred_by_email'; ?>
                                        <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                        <?php echo form_label('Referrer Email:', $field_id); ?>
                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                        <?php echo form_error($field_id); ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                         <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'linkedin_profile_url'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Linkedin Public Profile URL:', $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'employee_number'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Employee Number:', $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'ssn'; ?>
                                    <?php $required_asterisk = $ssn_required ? '<span class="required">*</span>' : ''; ?>
                                    <?php $required_rule = $ssn_required ? 'required="required"' : ''; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Social Security Number: '.$required_asterisk, $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'. $required_rule); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'dob'; ?>
                                    <?php $required_asterisk = $dob_required ? '<span class="required">*</span>' : ''; ?>
                                    <?php $required_rule = $dob_required ? 'data-rule-required="true"' : ''; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id]) && $user_information[$field_id] != '0000-00-00') ? date('m-d-Y', strtotime(str_replace('-', '/', $user_information[$field_id]))) : ''); ?>
                                    <label>Date of Birth: <?= $required_asterisk;?></label>
                                    <input class="form-control startdate " readonly="" type="text" <?= $required_rule;?> name="<?php echo $field_id;?>" value="<?php echo $temp; ?>">
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>

<!--                        <div class="row">-->
<!--                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">-->
<!--                                <div class="form-group">-->
<!--                                    <label>Timezone:</label>-->
<!--                                    <div class="hr-select-dropdown">-->
<!--                                        <select class="form-control" name="timezone" id="timezone">-->
<!--                                            <option value="">Please Select</option>-->
<!--                                            --><?php //if(!empty($timezones)) { ?>
<!--                                                --><?php //foreach($timezones as $key => $zone) { ?>
<!--                                                    --><?php //$default_selected = ( $zone['value'] == $employer['timezone'] ? true : false ); ?>
<!--                                                    <option --><?php //echo set_select('timezone', $key, $default_selected); ?><!-- value="--><?php //echo $zone['value']?><!--">--><?php //echo $zone['name']; ?><!--</option>-->
<!--                                                --><?php //} ?>
<!--                                            --><?php //} ?>
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                    --><?php //echo form_error('company_timezone'); ?>
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="row">


                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'job_title'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Job Title:', $field_id); ?>
                                    <input type="text" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" value="<?php echo set_value($field_id, $temp); ?>" class="form-control" />
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'division'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Division:', $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'department'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Department:', $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'office_location'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Office Location:', $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>

                        <?php if(IS_NOTIFICATION_ENABLED == 1 && $phone_sid != '') { ?>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 ">
                                <div class="form-group">
                                    <label>Notified By</label>
                                    <div class="hr-select-dropdown form-control " style="padding:0px;">
                                        <select  class="invoice-fields" name="notified_by[]" id="notified_by" multiple="true">
                                            <option value="email" <?php if(in_array('email', explode(',', $employer['notified_by']))){echo 'selected';}?>>Email</option>
                                            <option value="sms" <?php if(in_array('sms', explode(',', $employer['notified_by']))){echo 'selected';}?>>SMS</option> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <?php } ?>
                          
                      

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <?php $field_id = 'interests'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Interests:', $field_id); ?>
                                    <textarea id="interests" name="interests" class="invoice-fields auto-height" rows="6"><?php echo set_value($field_id, $temp); ?></textarea>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <?php $field_id = 'short_bio'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Short Bio:', $field_id); ?>
                                    <textarea id="short_bio" id="short_bio" name="short_bio" class="invoice-fields auto-height" rows="6"><?php echo set_value($field_id, $temp); ?></textarea>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                        <!-- Timezone -->
                        <div class="row js-timezone-row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group input-grey autoheight ">
                                    <?php $field_id = 'timezone'; ?>
                                    <?php echo form_label('Timezone:', $field_id); ?>
                                    <?=timezone_dropdown(
                                        $user_information['timezone'], 
                                        array(
                                            'class' => 'form-control js-timezone ',
                                            'id' => 'timezone',
                                            'name' => 'timezone'
                                        )
                                    );?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <label for="YouTubeVideo">Select Video:</label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <label class="control control--radio"><?php echo NO_VIDEO; ?>
                                                        <input type="radio" name="video_source" class="video_source" value="no_video"  checked="">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <label class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                                        <input type="radio" name="video_source" class="video_source" value="youtube" <?php echo !empty($user_information['YouTubeVideo']) && $user_information['YouTubeVideo'] != NULL && $user_information['video_type'] == 'youtube' ? 'checked="checked"':''; ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <label class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                                        <input type="radio" name="video_source" class="video_source" value="vimeo" <?php echo !empty($user_information['YouTubeVideo']) && $user_information['YouTubeVideo'] != NULL && $user_information['video_type'] == 'vimeo' ? 'checked="checked"':''; ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <label class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                                        <input type="radio" name="video_source" class="video_source" value="uploaded" <?php echo !empty($user_information['YouTubeVideo']) && $user_information['YouTubeVideo'] != NULL && $user_information['video_type'] == 'uploaded' ? 'checked="checked"':''; ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="youtube_vimeo_input">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php 
                                                if (!empty($user_information['YouTubeVideo']) && $user_information['YouTubeVideo'] != NULL && $user_information['video_type'] == 'youtube') {
                                                    $video_link = 'https://www.youtube.com/watch?v='.$user_information['YouTubeVideo'];
                                                } else if (!empty($user_information['YouTubeVideo']) && $user_information['YouTubeVideo'] != NULL && $user_information['video_type'] == 'vimeo') {
                                                    $video_link = 'https://vimeo.com/'.$user_information['YouTubeVideo'];
                                                } else {
                                                    $video_link = '';
                                                }
                                            ?>
                                            <label for="YouTube_Video" id="label_youtube">Youtube Video:</label>
                                            <label for="Vimeo_Video" id="label_vimeo" style="display: none">Vimeo Video:</label>
                                            <input type="text" name="yt_vm_video_url" value="<?php echo $video_link; ?>" class="form-control" id="yt_vm_video_url">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="upload_input" style="display: none">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label for="YouTubeVideo">Upload Video:</label>                                     
                                            <div class="upload-file form-control">
                                                <?php 
                                                    if (!empty($user_information['YouTubeVideo']) && $user_information['YouTubeVideo'] != NULL && $user_information['video_type'] == 'uploaded') {
                                                ?>
                                                        <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $user_information['YouTubeVideo']; ?>">
                                                <?php        
                                                    } else {
                                                ?>
                                                    <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="">
                                                <?php        
                                                    }
                                                ?>
                                                <span class="selected-file" id="name_upload_video">No video selected</span>
                                                <input name="upload_video" id="upload_video" onchange="upload_video_checker('upload_video')" type="file">
                                                <a href="javascript:;">Choose Video</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if(!empty($user_information['YouTubeVideo']) && $user_information['YouTubeVideo'] != NULL) { ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="well well-sm">
                                        <div class="embed-responsive embed-responsive-16by9">
                                        <?php if($user_information['video_type'] == 'youtube') { ?>
                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $user_information['YouTubeVideo']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        <?php } elseif($user_information['video_type'] == 'vimeo') { ?>
                                            <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $user_information['YouTubeVideo']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        <?php } else {?> 
                                            <video controls>
                                                <source src="<?php echo base_url().'assets/uploaded_videos/'.$user_information['YouTubeVideo']; ?>" type='video/mp4'>
                                            </video>
                                        <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="btn-wrp full-width text-right">
                            <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>">cancel</a>
                            <input class="btn btn-info" id="add_edit_submit" value="save" type="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<script type="text/javascript">

   $(document).ready(function(){
//       $('form').validate();
       CKEDITOR.replace('short_bio');
       CKEDITOR.replace('interests');

       $('.startdate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();

        var pre_selected = '<?php echo !empty($user_information['YouTubeVideo']) && $user_information['YouTubeVideo'] != NULL ? $user_information['video_type'] : ''; ?>';
        if(pre_selected == 'youtube'){
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (pre_selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if(pre_selected == 'uploaded') {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').show();
        } else {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').hide();
        }
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

    function check_file_all(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html('<span>' + fileName + '</span>');
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    $(function () {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#form_applicant_information").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                email: {
                    email: true
//                    required: true
                },
                Location_Address: {
                    required: true
                },
                address: {
                    required: true
                },
                PhoneNumber: {
                    // required: true,
                    // pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
                },
                phone_number: {
                    required: true
                },
                city: {
                    required: true
                },
                Location_Country: {
                    required: true
                },
                country: {
                    required: true
                },
                Location_State: {
                    required: true
                },
                state: {
                    required: true
                },
                Location_ZipCode: {
                    required: true
                },
                zipcode: {
                    required: true
                },
                Location_City: {
                    required: true
                }
            },
            messages: {
                first_name:{
                    required: 'First Name is required'
                },
                last_name:{
                    required: 'Last Name is required'
                },
                email:{
                    email: 'Provide Valid Email'
//                    required: 'Email is required'
                },
                Location_Address:{
                    required: 'Address is required'
                },
                address:{
                    required: 'Address is required'
                },
                PhoneNumber:{
                    required: 'Phone Number is required',
                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                },
                phone_number:{
                    required: 'Phone Number is required'
                },
                city:{
                    required: 'City is required'
                },
                Location_Country:{
                    required: 'Country is required'
                },
                country:{
                    required: 'Country is required'
                },
                Location_State:{
                    required: 'State is required'
                },
                state:{
                    required: 'State is required'
                },
                Location_ZipCode:{
                    required: 'Zip Code is required'
                },
                zipcode:{
                    required: 'Zip Code is required'
                },
                Location_City:{
                    required: 'City is required'
                }
            },
            errorPlacement: function(e, el){
                <?php if($is_regex === 1){ ?>
                if($(el)[0].id == 'PhoneNumber') $(el).parent().after(e);
                else $(el).after(e);
                <?php } else { ?>
                $(el).after(e);
                <?php } ?>
            },
            submitHandler: function (form) {
                <?php if($is_regex === 1){ ?>
               // TODO
                var is_error = false;
                // Check for phone number
                if($('input[name="PhoneNumber"]').val() != '' && $('input[name="PhoneNumber"]').val().trim() != '(___) ___-____' && !fpn($('input[name="PhoneNumber"]').val(), '', true)){
                    alertify.alert('Error!', 'Invalid mobile number provided.', function(){ return; });
                    is_error = true;
                    return;
                }
                // Check for secondary number
                // if($('input[name="secondary_PhoneNumber"]').val() != '' && $('input[name="secondary_PhoneNumber"]').val().trim() != '(___) ___-____' && !fpn($('input[name="secondary_PhoneNumber"]').val(), '', true)){
                //     alertify.alert('Invalid secondary mobile number provided.', function(){ return; });
                //     is_error = true;
                //     return;
                // }
                // Check for other number
                // if($('input[name="other_PhoneNumber"]').val() != '' && $('input[name="other_PhoneNumber"]').val().trim() != '(___) ___-____' && !fpn($('input[name="other_PhoneNumber"]').val(), '', true)){
                //     alertify.alert('Invalid telephone number provided.', function(){ return; });
                //     is_error = true;
                //     return;
                // }

                if (is_error === false) {
                    // Check the fields
                    if($('input[name="PhoneNumber"]').val().trim() == '(___) ___-____') $('input[name="PhoneNumber"]').val('');
                    else $("#form_applicant_information").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+($("#<?=$field_phone;?>").val().replace(/\D/g, ''))+'" />');

                    // if($('input[name="secondary_PhoneNumber"]').val().trim() == '(___) ___-____') $('input[name="secondary_PhoneNumber"]').val('');
                    // else $("#form_applicant_information").append('<input type="hidden" id="js-secondary-phonenumber" name="txt_secondary_phonenumber" value="+1'+($("input[name=\"secondary_PhoneNumber\"]").val().replace(/\D/g, ''))+'" />');

                    // if($('input[name="other_PhoneNumber"]').val().trim() == '(___) ___-____') $('input[name="other_PhoneNumber"]').val('');
                    // else $("#form_applicant_information").append('<input type="hidden" id="js-other-phonenumber" name="txt_other_phonenumber" value="+1'+($("input[name=\"other_PhoneNumber\"]").val().replace(/\D/g, ''))+'" />');

                    if($('input[name="video_source"]:checked').val() != 'no_video'){
                        $('#my_loader').show();
                    }
                    // Remove and set phone extension
                    $('#js-phonenumber').remove();
                    // $('#js-secondary-phonenumber').remove();
                    // $('#js-other-phonenumber').remove();
                    // $("#form_applicant_information").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+($("#<?=$field_phone;?>").val().replace(/\D/g, ''))+'" />');
                    form.submit();
                }
                <?php } else { ?>
                    form.submit();
                <?php } ?>
            }
        });
    });

    $('#add_edit_submit').click(function () {
        if($('input[name="video_source"]:checked').val() != 'no_video'){
            var flag = 0;
            if($('input[name="video_source"]:checked').val() == 'youtube'){
                
                if($('#yt_vm_video_url').val() != '') { 

                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                    if (!$('#yt_vm_video_url').val().match(p)) {
                        alertify.error('Not a Valid Youtube URL');
                        flag = 0;
                        return false;
                    } else {
                        flag = 1;
                    }
                } else {
                    flag = 0;
                    alertify.error('Please add valid youtube video link.');
                    return false;
                }
            } else if($('input[name="video_source"]:checked').val() == 'vimeo'){
                
                if($('#yt_vm_video_url').val() != '') {              
                    var flag = 0;
                    var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {url: $('#yt_vm_video_url').val()},
                        async : false,
                        success: function (data) {
                            if (data == false) {
                                alertify.error('Not a Valid Vimeo URL');
                                flag = 0;
                                return false;
                            } else {
                                flag = 1;
                            }
                        },
                        error: function (data) {
                        }
                    });
                } else {
                    flag = 0;
                    alertify.error('Please add valid vimeo video link.');
                    return false;
                }
            } else if ($('input[name="video_source"]:checked').val() == 'uploaded') {
                var old_uploaded_video = $('#pre_upload_video_url').val();
                if(old_uploaded_video != ''){
                    flag = 1;
                } else {
                    var file = upload_video_checker('upload_video');
                    if (file == false){
                        flag = 0;
                        return false;    
                    } else {
                        flag = 1;
                    }
                }
            }

            if(flag == 1){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }   
    });

    $('.video_source').on('click', function(){
        var selected = $(this).val();
        if(selected == 'youtube'){
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if(selected == 'uploaded') {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').show();
        } else {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').hide();
        }
    });

    function upload_video_checker(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'upload_video') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else{
                    var file_size = Number(($("#" + val)[0].files[0].size/1024/1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }

            }
        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
            return false;

        }
    }

    <?php if($is_regex === 1){ ?>

    // Reset phone number on load
    // Added on: 05-07-2019
    var val = fpn($("#<?=$field_phone;?>").val());
    if(typeof(val) === 'object'){
        $("#<?=$field_phone;?>").val(val.number);
        setCaretPosition($("#<?=$field_phone;?>"), val.cur);
    } else $("#<?=$field_phone;?>").val(val);
    // Reset phone number on load
    $("#<?=$field_phone;?>").keyup(function(){
        var val = fpn($(this).val());
        if(typeof(val) === 'object'){
            $(this).val(val.number);
            setCaretPosition(this, val.cur);
        } else $(this).val(val);
    });


    // var val2 = fpn($('input[name="secondary_PhoneNumber"]').val());
    // if(typeof(val2) === 'object'){
    //     $('input[name="secondary_PhoneNumber"]').val(val2.number);
    //     setCaretPosition($('input[name="secondary_PhoneNumber"]'), val2.cur);
    // } else $('input[name="secondary_PhoneNumber"]').val(val2);
    // // Reset phone number on load
    // $('input[name="secondary_PhoneNumber"]').keyup(function(){
    //     var val = fpn($(this).val());
    //     if(typeof(val) === 'object'){
    //         $(this).val(val.number);
    //         setCaretPosition(this, val.cur);
    //     } else $(this).val(val);
    // });


    // var val3 = fpn($('#other_PhoneNumber').val());
    // if(typeof(val3) === 'object'){
    //     $('#other_PhoneNumber').val(val3.number);
    //     setCaretPosition($('#other_PhoneNumber'), val3.cur);
    // } else $('#other_PhoneNumber').val(val3);
    // // Reset phone number on load
    // $('#other_PhoneNumber').keyup(function(){
    //     var val = fpn($(this).val());
    //     if(typeof(val) === 'object'){
    //         $(this).val(val.number);
    //         setCaretPosition(this, val.cur);
    //     } else $(this).val(val);
    // });

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

    <?php } ?>

    // 555 123 1234 56

    <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
    // Added on: 26-06-2019
    $('.js-timezone').select2();
    <?php } ?>
    $('#notified_by').select2({
            closeOnSelect : false,
            allowHtml: true,
            allowClear: true,
            tags: true 
        });
</script>
<style>
 .select2-container--default .select2-selection--single{ border: 2px solid #aaaaaa !important; background-color: #f7f7f7 !important; }
    .select2-container .select2-selection--single .select2-selection__rendered{ padding:5px 20px 5px 8px !important; }
    .select2-container {
  min-width: 400px;
}

.select2-results__option {
  padding-right: 20px;
  vertical-align: middle;
}
.select2-results__option:before {
  content: "";
  display: inline-block;
  position: relative;
  height: 20px;
  width: 20px;
  border: 2px solid #e9e9e9;
  border-radius: 4px;
  background-color: #fff;
  margin-right: 20px;
  vertical-align: middle;
}
.select2-results__option[aria-selected=true]:before {
  font-family:fontAwesome;
  content: "\f00c";
  color: #fff;
  background-color: #5897fb;
  border: 0;
  display: inline-block;
  padding-left: 3px;
}
.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #fff;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #eaeaeb;
    color: #272727;
}
.select2-container--default .select2-selection--multiple {
    margin-bottom: 10px;
}
.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
    border-radius: 4px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #81b431;
    border-width: 2px;
}
.select2-container--default .select2-selection--multiple {
    border-width: 2px;
}
.select2-container--open .select2-dropdown--below {
    
    border-radius: 6px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);

}
.select2-selection .select2-selection--multiple:after {
    content: 'hhghgh';
}
/* select with icons badges single*/
.select-icon .select2-selection__placeholder .badge {
    display: none;
}
.select-icon .placeholder {
    display: none;
}
.select-icon .select2-results__option:before,
.select-icon .select2-results__option[aria-selected=true]:before {
    display: none !important;
    /* content: "" !important; */
}
.select-icon  .select2-search--dropdown {
    display: none;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice{
    height: 25px !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered{
    height: 30px;
    padding: 0px;
}
</style>