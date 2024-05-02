<?php
if ($this->uri->segment(1) == 'e_signature') $sideBar = '';
else $sideBar = onboardingHelpWidget($company_info['sid']);

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

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/hr_documents/' . $unique_sid);
    $user_information = $applicant_information;
    $save_post_url = current_url();
    //Field Names
    $field_sid = 'applicant_sid';
    $field_profile_picture = 'pictures';
    $field_address = 'address';
    $field_phone = 'phone_number';
    $field_city = 'city';
    $field_zipcode = 'zipcode';
    $field_country = 'country';
    $field_state = 'state';
    $field_youtube = 'YouTube_Video';
    $title = 'My Profile';
}

$dob = isset($user_information['dob']) && !empty($user_information['dob']) && $user_information['dob'] != '0000-00-0' ? date('m-d-Y', strtotime(str_replace('-', '/', $user_information['dob']))) : '';
//
if ($_ssv) {
    $user_information['ssn'] = ssvReplace($user_information['ssn']);
    if ($dob != '') $user_information['dob'] = $dob = ssvReplace($dob, true);
}

?>
<div class="main">
    <div class="container">

        <div class="row">
            <?php if ($this->uri->segment(1) != 'e_signature') { ?>
                <div class="col-sm-12">
                    <p style="color: #cc0000;"><b><i>We suggest that you only use Google Chrome to access your account
                                and use its Features. Internet Explorer is not supported and may cause certain feature
                                glitches and security issues.</i></b></p>
                    <?= $sideBar; ?>
                </div>
            <?php } ?>
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block"><i class="fa fa-angle-left"></i> Review Previous Step</a>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <a href="<?= base_url('onboarding/general_information/' . $unique_sid); ?>" class="btn btn-warning btn-block"> Bypass This Step <i class="fa fa-angle-right"></i></a>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <a href="javascript:;" class="btn btn-success btn-block" id="go_next"> Save And Proceed Next <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                <div class="form-wrp">
                    <p class="text-blue">Lets Start with the basics. Please complete our employee profile form and add your profile photo.</p>
                    <p class="text-blue">This will ensure that we have the most up-to-date information. Please add any missing information and update your details. Add fun facts and information about yourself in the "Your Interests" and the "Short Bio" sections so that your new team can get to know you. We are excited to have you on our team and want to give you the tools to help you hit the ground running.</p>
                    <p class="text-blue"><b>Please review your details and proceed.</b></p>
                    <form id="form_applicant_information" method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                        <input type="hidden" id="perform_action" name="perform_action" value="update_applicant_information" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                        <input type="hidden" id="<?php echo $field_sid; ?>" name="<?php echo $field_sid; ?>" value="<?php echo $users_sid; ?>" />

                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 pull-right">
                                <div id="" class="img-thumbnail emply-picture pull-right">
                                    <?php $field_id = $field_profile_picture; ?>
                                    <?php $temp = isset($user_information[$field_id]) && !empty($user_information[$field_id]) ? $user_information[$field_id] : base_url('assets/images/default_pic.jpg'); ?>
                                    <?php if (isset($user_information[$field_id]) && !empty($user_information[$field_id])) { ?>
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
                                            <label>Profile picture:</label>
                                            <?php $field_id = $field_profile_picture; ?>
                                            <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                            <div class="upload-file form-control">
                                                <span class="selected-file" id="name_<?php echo $field_profile_picture; ?>">No file selected</span>
                                                <input name="<?php echo $field_profile_picture; ?>" id="<?php echo $field_profile_picture; ?>" onchange="check_file_all('<?php echo $field_profile_picture; ?>')" accept="image/*" type="file">
                                                <a href="javascript:;">Choose File</a>
                                            </div>
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
                                    <?php
                                    $requiredText = get_company_module_status($company_info['sid'], 'primary_number_required') == 1 ? '<span class="required">*</span>' : ''; ?>
                                    <?php $field_id = $field_phone; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Primary Number: ' . $requiredText, $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '" data-rule-required="true"'); ?>
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
                                        <select class="form-control" data-rule-required="true" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, '<?php echo $field_state ?>')">
                                            <option value="">Please Select</option>
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
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
                                    <?php $state_id = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('State: <span class="required">*</span>', $field_id); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" data-rule-required="true" name="<?php echo $field_id ?>" id="<?php echo $field_id ?>">
                                            <?php if (empty($state_id)) { ?>
                                                <option value="">Select State</option> <?php
                                                                                    } else {
                                                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                    <option <?php echo set_select($field_id, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php echo form_error($field_id); ?>
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

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Gender:</label>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="gender">
                                            <option value="">Please Select Gender</option>
                                            <option <?= $user_information["gender"] == 'male' ? 'selected' : ''; ?> value="male">Male</option>
                                            <option <?= $user_information["gender"] == 'female' ? 'selected' : ''; ?> value="female">Female</option>
                                            <option <?= $user_information["gender"] == 'other' ? 'selected' : ''; ?> value="other">Other</option>
                                        </select>
                                        <?php echo form_error('gender'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Marital Status:</label>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="marital_status">
                                            <option value="">
                                                Please select marital status
                                            </option>
                                            <option <?= $user_information["marital_status"] == 'Single' ? 'selected' : ''; ?> value="Single">
                                                Single
                                            </option>
                                            <option <?= $user_information["marital_status"] == 'Married' ? 'selected' : ''; ?> value="Married">
                                                Married
                                            </option>
                                            <option <?= $user_information["marital_status"] == 'Other' ? 'selected' : ''; ?> value="Other">
                                                Other
                                            </option>
                                        </select>
                                        <?php echo form_error('marital_status'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'readonly class="form-control" id="' . $field_id . '"'); ?>
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
                                    <?php echo form_label('Social Security Number: ' . $required_asterisk, $field_id); ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"' . $required_rule); ?>
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'dob'; ?>
                                    <?php $required_asterisk = $dob_required ? '<span class="required">*</span>' : ''; ?>
                                    <?php $required_rule = $dob_required ? 'data-rule-required="true"' : ''; ?>
                                    <?php $temp = $dob; ?>
                                    <label>Date of Birth: <?= $required_asterisk; ?></label>
                                    <input class="form-control" id="date_of_birth" readonly="" type="text" <?= $required_rule; ?> name="<?php echo $field_id; ?>" value="<?php echo $temp; ?>">
                                    <?php echo form_error($field_id); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'uniform_top_size'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Uniform Top Size: ', $field_id); ?>
                                    <?= $portalData["uniform_sizes"] ? '<strong class="text-danger">*</strong>' : ''; ?>
                                    <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php $field_id = 'uniform_bottom_size'; ?>
                                    <?php $temp2 = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>

                                    <label>Uniform Bottom Size:</label>
                                    <?= $portalData["uniform_sizes"] ? '<strong class="text-danger">*</strong>' : ''; ?>
                                    <input class="form-control" id="date_of_birth" type="text" name="<?php echo $field_id; ?>" value="<?php echo $temp2; ?>">
                                </div>
                            </div>
                        </div>



                        <?php
                        //
                        $hasOther = [];
                        //
                        if ($user_information['languages_speak']) {
                            $hasOther = array_filter(explode(',', $user_information['languages_speak']), function ($lan) {
                                return !in_array($lan, ['english', 'spanish', 'russian']) && !empty($lan);
                            });
                        }
                        ?>

                        <div class="row">
                            <!--  -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>I Speak:</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <!--  -->
                                <label class="control control--checkbox">
                                    <input type="checkbox" name="secondaryLanguages[]" value="english" <?= strpos($user_information['languages_speak'], 'english') !== false ? 'checked' : ''; ?> /> English
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <!--  -->
                                <label class="control control--checkbox">
                                    <input type="checkbox" name="secondaryLanguages[]" value="spanish" <?= strpos($user_information['languages_speak'], 'spanish') !== false ? 'checked' : ''; ?> /> Spanish
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <!--  -->
                                <label class="control control--checkbox">
                                    <input type="checkbox" name="secondaryLanguages[]" value="russian" <?= strpos($user_information['languages_speak'], 'russian') !== false ? 'checked' : ''; ?> /> Russian
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
                        <div class="row jsOtherLanguage <?= $hasOther ? '' : 'dn'; ?>">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <input type="text" class="form-control" name="secondaryLanguages[]" placeholder="French, German" value="<?= $hasOther ? ucwords(implode(',', $hasOther)) : ''; ?>" />
                                <p><strong class="text-danger"><i>Add comma separated languages. e.g. French, German</i></strong></p>
                            </div>
                        </div>

                        <script>
                            $('[name="secondaryOption"]').click(function() {
                                $('.jsOtherLanguage').toggleClass('dn');
                            });
                        </script>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <?php $field_id = 'interests'; ?>
                                    <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                    <?php echo form_label('Your Interests:', $field_id); ?>
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

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <label for="YouTube_Video">Select Video:</label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <label class="control control--radio"><?php echo NO_VIDEO; ?>
                                                        <input type="radio" name="video_source" class="video_source" value="no_video" checked="">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <label class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                                        <input type="radio" name="video_source" class="video_source" value="youtube" <?php echo $user_information['video_type'] == 'youtube' ? 'checked="checked"' : ''; ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <label class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                                        <input type="radio" name="video_source" class="video_source" value="vimeo" <?php echo $user_information['video_type'] == 'vimeo' ? 'checked="checked"' : ''; ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                    <label class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                                        <input type="radio" name="video_source" class="video_source" value="uploaded" <?php echo $user_information['video_type'] == 'uploaded' ? 'checked="checked"' : ''; ?>>
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
                                            if (!empty($user_information['YouTube_Video']) && $user_information['video_type'] == 'youtube') {
                                                $video_link = 'https://www.youtube.com/watch?v=' . $user_information['YouTube_Video'];
                                            } else if (!empty($user_information['YouTube_Video']) && $user_information['video_type'] == 'vimeo') {
                                                $video_link = 'https://vimeo.com/' . $user_information['YouTube_Video'];
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
                                            <label for="YouTube_Video">Upload Video:</label>
                                            <div class="upload-file form-control">
                                                <?php
                                                if (!empty($user_information['YouTube_Video']) && $user_information['video_type'] == 'uploaded') {
                                                ?>
                                                    <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $user_information['YouTube_Video']; ?>">
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
                            <?php if (!empty($user_information['YouTube_Video'])) { ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="well well-sm">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <?php if ($user_information['video_type'] == 'youtube') { ?>
                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $user_information['YouTube_Video']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            <?php } elseif ($user_information['video_type'] == 'vimeo') { ?>
                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $user_information['YouTube_Video']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            <?php } else { ?>
                                                <video controls>
                                                    <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $user_information['YouTube_Video']; ?>" type='video/mp4'>
                                                </video>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="btn-wrp full-width">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <a class="btn btn-info btn-block mb-2" href="<?php echo $back_url; ?>">Review Previous Step</a>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <a href="<?= base_url('onboarding/general_information/' . $unique_sid); ?>" class="btn btn-warning btn-block mb-2"> Bypass This Step <i class="fa fa-angle-right"></i></a>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <input class="btn btn-success btn-block mb-2" id="add_edit_submit" value="Save And Proceed Next" type="submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php if ($sideBar != '') { ?>
</div>
<?php } ?>
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
    $(document).ready(function() {
        //       $('form').validate();
        CKEDITOR.replace('short_bio');
        CKEDITOR.replace('interests');

        $('#date_of_birth').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

        var pre_selected = '<?php echo !empty($user_information['YouTube_Video']) ? $user_information['video_type'] : ''; ?>';
        if (pre_selected == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (pre_selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (pre_selected == 'uploaded') {
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

    $('#go_next').click(function() {
        $('#add_edit_submit').click();
    });

    $(function() {
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
                    required: true
                },
                Location_Address: {
                    required: true
                },
                address: {
                    required: true
                },
                PhoneNumber: {
                    required: true
                },

                <?php if (get_company_module_status($company_info['sid'], 'primary_number_required') == 1) { ?>

                    phone_number: {
                        required: true
                    },
                <?php } ?>

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
                },
                <?php if ($portalData["uniform_sizes"]) { ?>
                    uniform_top_size: {
                        required: true
                    },
                    uniform_bottom_size: {
                        required: true
                    },
                <?php } ?>
            },
            messages: {
                first_name: {
                    required: 'First Name is required'
                },
                last_name: {
                    required: 'Last Name is required'
                },
                email: {
                    required: 'Email is required'
                },
                Location_Address: {
                    required: 'Address is required'
                },
                address: {
                    required: 'Address is required'
                },
                <?php if (get_company_module_status($company_info['sid'], 'primary_number_required') == 1) { ?>

                    PhoneNumber: {
                        required: 'Phone Number is required'
                    },
                <?php } ?>

                phone_number: {
                    required: 'Phone Number is required'
                },
                city: {
                    required: 'City is required'
                },
                Location_Country: {
                    required: 'Country is required'
                },
                country: {
                    required: 'Country is required'
                },
                Location_State: {
                    required: 'State is required'
                },
                state: {
                    required: 'State is required'
                },
                Location_ZipCode: {
                    required: 'Zip Code is required'
                },
                zipcode: {
                    required: 'Zip Code is required'
                },
                Location_City: {
                    required: 'City is required'
                },
                <?php if ($portalData["uniform_sizes"]) { ?>
                    uniform_top_size: {
                        required: "Uniform top size is required."
                    },
                    uniform_bottom_size: {
                        required: "Uniform bottom size is required."
                    },
                <?php } ?>
            },
            submitHandler: function(form) {
                $('#my_loader').show();
                form.submit();
            }
        });
    });

    $('#add_edit_submit').click(function() {
        if ($('input[name="video_source"]:checked').val() != 'no_video') {
            var flag = 0;
            if ($('input[name="video_source"]:checked').val() == 'youtube') {

                if ($('#yt_vm_video_url').val() != '') {

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
            } else if ($('input[name="video_source"]:checked').val() == 'vimeo') {

                if ($('#yt_vm_video_url').val() != '') {
                    var flag = 0;
                    var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {
                            url: $('#yt_vm_video_url').val()
                        },
                        async: false,
                        success: function(data) {
                            if (data == false) {
                                alertify.error('Not a Valid Vimeo URL');
                                flag = 0;
                                return false;
                            } else {
                                flag = 1;
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    flag = 0;
                    alertify.error('Please add valid vimeo video link.');
                    return false;
                }
            } else if ($('input[name="video_source"]:checked').val() == 'uploaded') {
                var old_uploaded_video = $('#pre_upload_video_url').val();
                if (old_uploaded_video != '') {
                    flag = 1;
                } else {
                    var file = upload_video_checker('upload_video');
                    if (file == false) {
                        flag = 0;
                        return false;
                    } else {
                        flag = 1;
                    }
                }
            }

            if (flag == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    });

    $('.video_source').on('click', function() {
        var selected = $(this).val();
        if (selected == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'uploaded') {
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
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
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

    //
    let countryId = $("#country").val();
    let stateId = $("#state").val();

    if (countryId !== '' && stateId == '') {
        $("#country").trigger('change');
    }
</script>