<?php
if ($this->uri->segment(1) == 'eeo') $sideBar = '';
else $sideBar = onboardingHelpWidget($company_info['sid']);


$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
$next_btn = '';
$next_url = '';
$opt_ut = '';
$center_btn = '';
$back_btn = 'My Profile';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/general_information/' . $unique_sid);
    $next_btn = '<a href="javascript:;" class="btn btn-success btn-block go_next"> Proceed To Next <i class="fa fa-angle-right"></i></a>';
    $opt_ut = '<a href="javascript:;" data-key="opt_out" class="btn btn-warning btn-block go_next"> Opt out <i class="fa fa-angle-right"></i></a>';

    if ($enable_learbing_center) {
        $next_btn_function = 'learning_center';
    } else {
        $next_btn_function = 'my_credentials';
    }
    $next_url = base_url("onboarding/" . $next_btn_function . "/" . $unique_sid);

    $center_btn = '<a href="' . base_url('onboarding/' . $next_btn_function . '/' . $unique_sid) . '" class="btn btn-warning btn-block"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
    $back_btn = 'Review Previous Step';
    $save_post_url = current_url();
    $first_name = $applicant['first_name'];
    $last_name = $applicant['last_name'];
    $email = $applicant['email'];
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = base_url('my_profile');
    if ($eeoc_status != 'Yes') {
        $next_btn = '<a href="javascript:;" class="btn btn-success btn-block go_next"> Save </a>';
    } else {
        $next_btn = '';
    }
    $save_post_url = current_url();
    $next_url = base_url('my_profile');
    $first_name = $employee['first_name'];
    $last_name = $employee['last_name'];
    $email = $employee['email'];
}

$eeocFormOptions = get_eeoc_options_status($company_sid);

?>
<div class="main">
    <div class="container">


        <div class="row">
            <?php if ($this->uri->segment(1) != 'eeo') { ?>
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
                        <div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block"><i class="fa fa-angle-left"></i> <?= $back_btn; ?></a>
                        </div>
                        <div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <?php echo $center_btn; ?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <?php echo $opt_ut; ?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <?php echo $next_btn; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header">
                            <h1 class="section-ttile">EEOC Form</h1>
                        </div>
                        <p class="text-blue"><b>Although, the EEOC Form is optional we are requesting that you complete it.</b></p>
                        <form id="form_eeoc" method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                            <input type="hidden" id="perform_action" name="perform_action" value="update_eeo_data" />
                            <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                            <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <strong>EQUAL EMPLOYMENT OPPORTUNITY FORM</strong>
                                    <p>
                                        We are an equal opportunity employer and we give consideration for employment to qualified applicants without regard to race, color, religion, sex, sexual orientation, gender identity, national origin, disability, or protected veteran status.
                                    </p>
                                    <p>
                                        If you'd like more information about your EEO rights as an applicant under the law, please click here:
                                        <br /><a class="cs_link_adjust" href="http://www.dol.gov/ofccp/regs/compliance/posters/pdf/eeopost.pdf" target="_blank">http://www.dol.gov/ofccp/regs/compliance/posters/pdf/eeopost.pdf.</a>
                                    </p>
                                    <p>Federal law requires employers to provide reasonable accommodation to qualified individuals with disabilities. In the event you require reasonable accommodation to apply for this job, please contact our company and appropriate assistance will be provided.</p>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 ">
                                            <?php $field_id = 'eeoc_form_status'; ?>
                                            <?php $temp = isset($eeoc[$field_id]) && !empty($eeoc[$field_id]) ? $eeoc[$field_id] : $eeoc_status; ?>
                                            <lsbel>This EEO form is optional, do you want to fill it out?</lsbel>
                                            <br />
                                            <br />
                                            <label class="control control--radio col-lg-2 col-md-6 col-xs-6 col-sm-6">
                                                <?php $default_checked = $temp == 'Yes' ? true : false; ?>
                                                Yes
                                                <input <?php echo set_radio($field_id, 'Yes', $default_checked); ?> type="radio" name="<?php echo $field_id ?>" id="<?php echo $field_id . '_'; ?>yes" class="eeoc_form_status" value="Yes">
                                                <div class="control__indicator"></div>
                                            </label>

                                            <label class="control control--radio col-lg-2 col-md-6 col-xs-6 col-sm-6">
                                                <?php $default_checked = $temp == 'No' ? true : false; ?>
                                                No
                                                <input <?php echo set_radio($field_id, 'No', $default_checked); ?> type="radio" name="<?php echo $field_id ?>" id="<?php echo $field_id . '_'; ?>no" class="eeoc_form_status" value="No">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="eeoc_form_container" class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header" style="background-color: #3598dc; color: #ffffff;">
                                            <?php
                                            $required_label = '';

                                            if ($dl_citizen == 1) {
                                                $required_label = '<span style="color: red; font-size: 16px;"> * </span>';
                                            }
                                            ?>
                                            I am a U.S. citizen or permanent resident <?php echo $required_label; ?>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 ">
                                                    <?php $field_id = 'us_citizen'; ?>
                                                    <?php $temp = isset($eeoc[$field_id]) && !empty($eeoc[$field_id]) ? $eeoc[$field_id] : ''; ?>
                                                    <label class="control control--radio col-lg-2 col-md-6 col-xs-6 col-sm-6">
                                                        <?php $default_checked = $temp == 'yes' ? true : false; ?>
                                                        Yes
                                                        <input <?php echo set_radio($field_id, 'Yes', $default_checked); ?> type="radio" name="<?php echo $field_id ?>" id="citizen-yes" class="citizen_check" value="yes">
                                                        <div class="control__indicator"></div>
                                                    </label>

                                                    <label class="control control--radio col-lg-2 col-md-6 col-xs-6 col-sm-6">
                                                        <?php $default_checked = $temp == 'no' ? true : false; ?>
                                                        No
                                                        <input <?php echo set_radio($field_id, 'No', $default_checked); ?> type="radio" name="<?php echo $field_id ?>" id="citizen-no" class="citizen_check" value="no">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div id="visa_status_div" class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="display: none;">
                                                    <?php $field_id = 'visa_status'; ?>
                                                    <?php $temp = isset($eeoc[$field_id]) && !empty($eeoc[$field_id]) ? $eeoc[$field_id] : ''; ?>
                                                    <div class="form-group">
                                                        <label>If no, please indicate your visa status</label>
                                                        <textarea name="<?php echo $field_id ?>" id="<?php echo $field_id ?>" class="form-control input-grey"><?php echo set_value($field_id, $temp); ?></textarea>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header" style="background-color: #3598dc; color: #ffffff;">
                                            1. GROUP STATUS (PLEASE CHECK ONE)
                                        </div>
                                        <div class="hr-innerpadding">
                                            <?php $field_id = 'group_status'; ?>
                                            <?php $temp = isset($eeoc[$field_id]) && !empty($eeoc[$field_id]) ? $eeoc[$field_id] : ''; ?>
                                            <div class="checkbox-radio-row">
                                                <label class="control control--radio">
                                                    <?php $default_checked = $temp == 'Hispanic or Latino' ? true : false; ?>
                                                    Hispanic or Latino <span>- A person of Cuban, Mexican, Puerto Rican, South or Central American, or other Spanish culture or origin regardless of race.</span>
                                                    <input <?php echo set_radio($field_id, 'Hispanic or Latino', $default_checked); ?> type="radio" name="group_status" id="q1" value="Hispanic or Latino">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="checkbox-radio-row">
                                                <label class="control control--radio">
                                                    <?php $default_checked = $temp == 'White' ? true : false; ?>
                                                    White (Not Hispanic or Latino) <span>- A person having origins in any of the original peoples of Europe, the Middle East or North Africa.</span>
                                                    <input <?php echo set_radio($field_id, 'White', $default_checked); ?> type="radio" name="group_status" id="q2" value="White">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="checkbox-radio-row">
                                                <label class="control control--radio">
                                                    <?php $default_checked = $temp == 'Black or African American' ? true : false; ?>
                                                    Black or African American (Not Hispanic or Latino) <span>- A person having origins in any of the black racial groups of Africa.</span>
                                                    <input <?php echo set_radio($field_id, 'Black or African American', $default_checked); ?> type="radio" name="group_status" id="q3" value="Black or African American">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="checkbox-radio-row">
                                                <label class="control control--radio">
                                                    <?php $default_checked = $temp == 'Native Hawaiian or Other Pacific Islander' ? true : false; ?>
                                                    Native Hawaiian or Other Pacific Islander (Not Hispanic or Latino) <span>- A person having origins in any of the peoples of Hawaii, Guam, Samoa or other Pacific Islands.</span>
                                                    <input <?php echo set_radio($field_id, 'Native Hawaiian or Other Pacific Islander', $default_checked); ?> type="radio" name="group_status" id="q4" value="Native Hawaiian or Other Pacific Islander">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="checkbox-radio-row">
                                                <label class="control control--radio">
                                                    <?php $default_checked = $temp == 'Asian' ? true : false; ?>
                                                    Asian (Not Hispanic or Latino) <span>- A person having origins in any of the original peoples of the Far East, Southeast Asia or the Indian Subcontinent, including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand and Vietnam.</span>
                                                    <input <?php echo set_radio($field_id, 'Asian', $default_checked); ?> type="radio" name="group_status" id="q5" value="Asian">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="checkbox-radio-row">
                                                <label class="control control--radio">
                                                    <?php $default_checked = $temp == 'American Indian or Alaska Native' ? true : false; ?>
                                                    American Indian or Alaska Native (Not Hispanic or Latino) <span>- A person having origins in any of the original peoples of North and South America (including Central America) and who maintain tribal affiliation or community attachment.</span>
                                                    <input <?php echo set_radio($field_id, 'American Indian or Alaska Native', $default_checked); ?> type="radio" name="group_status" id="q6" value="American Indian or Alaska Native">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="checkbox-radio-row">
                                                <label class="control control--radio">
                                                    <?php $default_checked = $temp == 'Two or More Races' ? true : false; ?>
                                                    Two or More Races (Not Hispanic or Latino) <span>- All persons who identify with more than one of the above five races.</span>
                                                    <input <?php echo set_radio($field_id, 'Two or More Races', $default_checked); ?> type="radio" name="group_status" id="q7" value="Two or More Races">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($eeocFormOptions['dl_vet'] == 1) { ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header" style="background-color: #3598dc; color: #ffffff;">
                                                2. VETERAN
                                            </div>
                                            <div class="hr-innerpadding">
                                                <?php $field_id = 'veteran'; ?>
                                                <?php $temp = isset($eeoc[$field_id]) && !empty($eeoc[$field_id]) ? $eeoc[$field_id] : ''; ?>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        <?php $default_checked = $temp == 'Disabled Veteran' ? true : false; ?>
                                                        Disabled Veteran: <span>A veteran of the U.S. military, ground, naval or air service who is entitled to compensation (or who but for the receipt of military retired pay would be entitled to compensation) under laws administered by the Secretary of Veterans Affairs; or a person who was discharged or released from active duty because of a service-connected disability.</span>
                                                        <input <?php echo set_radio($field_id, 'Disabled Veteran', $default_checked); ?> type="radio" name="veteran" id="q8" value="Disabled Veteran">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        <?php $default_checked = $temp == 'Recently Separated Veteran' ? true : false; ?>
                                                        Recently Separated Veteran: <span>A "recently separated veteran" means any veteran during the three-year period beginning on the date of such veteran's discharge or release from active duty in the U.S. military, ground, naval, or air service.</span>
                                                        <input <?php echo set_radio($field_id, 'Recently Separated Veteran', $default_checked); ?> type="radio" name="veteran" id="q9" value="Recently Separated Veteran">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        <?php $default_checked = $temp == 'Active Duty Wartime or Campaign Badge Veteran' ? true : false; ?>
                                                        Active Duty Wartime or Campaign Badge Veteran: <span>An "active duty wartime or campaign badge veteran" means a veteran who served on active duty in the U.S. military, ground, naval or air service during a war, or in a campaign or expedition for which a campaign badge has been authorized under the laws administered by the Department of Defense. </span>
                                                        <input <?php echo set_radio($field_id, 'Active Duty Wartime or Campaign Badge Veteran', $default_checked); ?> type="radio" name="veteran" id="q10" value="Active Duty Wartime or Campaign Badge Veteran">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        <?php $default_checked = $temp == 'Armed Forces Service Medal Veteran' ? true : false; ?>
                                                        Armed Forces Service Medal Veteran: <span>An "Armed forces service medal veteran" means a veteran who, while serving on active duty in the U.S. military, ground, naval or air service, participated in a United States military operation for which an Armed Forces service medal was awarded pursuant to Executive Order 12985.</span>
                                                        <input <?php echo set_radio($field_id, 'Armed Forces Service Medal Veteran', $default_checked); ?> type="radio" name="veteran" id="q11" value="Armed Forces Service Medal Veteran">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        <?php $default_checked = $temp == 'I Am Not a Protected Veteran' ? true : false; ?>
                                                        I Am Not a Protected Veteran
                                                        <input <?php echo set_radio($field_id, 'I Am Not a Protected Veteran', $default_checked); ?> type="radio" name="veteran" id="q12" value="I Am Not a Protected Veteran">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($eeocFormOptions['dl_vol'] == 1) { ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header" style="background-color: #3598dc; color: #ffffff;">
                                                3. VOLUNTARY SELF-IDENTIFICATION OF DISABILITY
                                            </div>
                                            <div class="hr-innerpadding">
                                                <?php $field_id = 'disability'; ?>
                                                <?php $temp = isset($eeoc[$field_id]) && !empty($eeoc[$field_id]) ? $eeoc[$field_id] : ''; ?>
                                                <div class="plain-text-box">
                                                    <strong>Why are you being asked to complete this form?</strong>
                                                    <p></p>
                                                    <p>Because we do business with the government, we must reach out to, hire, and provide equal opportunity to qualified people with disabilities.i To help us measure how well we are doing, we are asking you to tell us if you have a disability or if you ever had a disability. Completing this form is voluntary, but we hope that you will choose to fill it out. If you are applying for a job, any answer you give will be kept private and will not be used against you in any way.</p>
                                                    <p>If you already work for us, your answer will not be used against you in any way. Because a person may become disabled at any time, we are required to ask all of our employees to update their information every five years. You may voluntarily self-identify as having a disability on this form without fear of any punishment because you did not identify as having a disability earlier. </p>
                                                </div>
                                                <div class="plain-text-box">
                                                    <strong>How do I know if I have a disability?</strong>
                                                    <p></p>
                                                    <p>You are considered to have a disability if you have a physical or mental impairment or medical condition that substantially limits a major life activity, or if you have a history or record of such an impairment or medical condition. </p>
                                                    <p>Disabilities include, but are not limited to: </p>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <div class="disabilities-list">
                                                            <ul>
                                                                <li>Blindness</li>
                                                                <li>Deafness</li>
                                                                <li>Cancer</li>
                                                                <li>Diabetes</li>
                                                                <li>Epilepsy</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <div class="disabilities-list">
                                                            <ul>
                                                                <li>Autism</li>
                                                                <li>Cerebral palsy</li>
                                                                <li>HIV/AIDS</li>
                                                                <li>Schizophrenia</li>
                                                                <li>Muscular dystrophy </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <div class="disabilities-list">
                                                            <ul>
                                                                <li>Bipolar disorder</li>
                                                                <li>Major depression</li>
                                                                <li>Multiple sclerosis (MS)</li>
                                                                <li>Missing limbs or partially missing limbs </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <div class="disabilities-list">
                                                            <ul>
                                                                <li>Post-traumatic stress disorder (PTSD)</li>
                                                                <li>Obsessive compulsive disorder</li>
                                                                <li>Impairments requiring the use of a wheelchair</li>
                                                                <li>Intellectual disability (previously called mental retardation)</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="page-header">
                                                            <h4><strong>Please check one of the boxes below:</strong></h4>
                                                        </div>
                                                        <div class="checkbox-radio-row">
                                                            <label class="control control--radio">
                                                                <?php $default_checked = $temp == 'YES, I HAVE A DISABILITY' ? true : false; ?>
                                                                YES, I HAVE A DISABILITY (or previously had a disability)
                                                                <input <?php echo set_radio($field_id, 'YES, I HAVE A DISABILITY', $default_checked); ?> name="disability" id="disability" type="radio" value="YES, I HAVE A DISABILITY">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="checkbox-radio-row">
                                                            <label class="control control--radio">
                                                                <?php $default_checked = $temp == 'NO, I DON\'T HAVE A DISABILITY' ? true : false; ?>
                                                                NO, I DON'T HAVE A DISABILITY
                                                                <input <?php echo set_radio($field_id, 'NO, I DON\'T HAVE A DISABILITY', $default_checked); ?> name="disability" id="no-disability" type="radio" value="NO, I DON'T HAVE A DISABILITY">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="checkbox-radio-row">
                                                            <label class="control control--radio">
                                                                <?php $default_checked = $temp == 'I DON\'T WISH TO ANSWER' ? true : false; ?>
                                                                I DON'T WISH TO ANSWER
                                                                <input <?php echo set_radio($field_id, 'I DON\'T WISH TO ANSWER', $default_checked); ?> name="disability" id="no-answer" type="radio" value="I DON'T WISH TO ANSWER">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($eeocFormOptions['dl_gen'] == 1) { ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header" style="background-color: #3598dc; color: #ffffff;">
                                                4. GENDER (PLEASE CHECK ONE)
                                            </div>
                                            <div class="hr-innerpadding">
                                                <?php $field_id = 'gender'; ?>
                                                <?php $temp = isset($eeoc[$field_id]) && !empty($eeoc[$field_id]) ? $eeoc[$field_id] : ''; ?>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        <?php $default_checked = $temp == 'Male' ? true : false; ?>
                                                        Male
                                                        <input <?php echo set_radio($field_id, 'Male', $default_checked); ?> name="gender" id="male" type="radio" value="Male">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        <?php $default_checked = $temp == 'Female' ? true : false; ?>
                                                        Female
                                                        <input <?php echo set_radio($field_id, 'Female', $default_checked); ?> name="gender" id="female" type="radio" value="Female">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="checkbox-radio-row">
                                                    <label class="control control--radio">
                                                        <?php $default_checked = $temp == 'Other' ? true : false; ?>
                                                        Other
                                                        <input <?php echo set_radio($field_id, 'Other', $default_checked); ?> name="gender" id="other" type="radio" value="Other">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>

                            <div class="btn-wrp full-width">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                        <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block"><i class="fa fa-angle-left"></i> <?= $back_btn; ?></a>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                        <?php echo $center_btn; ?>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                        <?php echo $opt_ut; ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                        <?php echo $next_btn; ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php if ($users_type != 'applicant') { ?>
                        <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"> -->
                        <?php //$this->load->view('manage_employer/employee_hub_right_menu'); 
                        ?>
                        <!-- </div> -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($sideBar != '') { ?>
</div>
<?php } ?>
</div>
<style>
    .cs_link_adjust {
        word-wrap: break-word;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('.go_next').click(function() {
            var next = '<?= $next_url ?>';
            var eeoc_check = $('input[name="eeoc_form_status"]:checked').val();
            var error_flag = 0;
            var citizenFlag = <?php echo $dl_citizen; ?>

            if (eeoc_check == 'Yes' && $(this).data("key") === undefined) {
                var citizen = $('input[name="us_citizen"]:checked').val();
                //
                if (citizenFlag == 1 && (citizen === undefined || citizen.length == 0)) {
                    alertify.alert('Please, select a citizen.');
                    error_flag++;
                    return;
                }

                if ($('input[name="group_status"]:checked').length == 0) {
                    alertify.error('Please select group status');
                    error_flag++;
                }
                <?php if ($eeocFormOptions['dl_vet'] == 1) { ?>
                    if ($('input[name="veteran"]:checked').length == 0) {
                        alertify.error('Please select veteran');
                        error_flag++;
                    }
                <?php } ?>

                <?php if ($eeocFormOptions['dl_vol'] == 1) { ?>
                    if ($('input[name="disability"]:checked').length == 0) {
                        alertify.error('Please select voluntary self-identification of disability');
                        error_flag++;
                    }
                <?php } ?>

                <?php if ($eeocFormOptions['dl_gen'] == 1) { ?>
                    if ($('input[name="gender"]:checked').length == 0) {
                        alertify.error('Please select gender');
                        error_flag++;
                    }
                <?php } ?>

                if (error_flag > 0) {
                    return false;
                } else {
                    $('#form_eeoc').submit();
                }
            } else {

                if ($(this).data("key") !== undefined) {
                    $('#eeoc_form_status_no').prop("checked", true)
                }
                window.location.href = next;
            }
        });

        $('.eeoc_form_status').on('click', function() {
            var selected = $(this).val();

            if (selected == 'Yes') {
                $('#eeoc_form_container').show();
            } else if (selected == 'No') {
                $('#eeoc_form_container').hide();
            }
        });

        $('.citizen_check').click(function() {
            var selected = $(this).val();

            if (selected == 'yes') {
                $("#visa_status").prop('required', false);
                $("#visa_status").prop('disabled', true);
                $("#visa_status_div").hide();
            } else {
                $("#visa_status").prop('required', true);
                $("#visa_status").prop('disabled', false);
                $("#visa_status_div").show();
            }

        });

        $('input[type=radio]:checked').each(function() {
            $(this).trigger('click');
        });

    });
</script>