<?php if (!$load_view) {
    $user_information = $employee;
    if(is_array($extra_info)) {
        $user_information = array_merge($employee, $extra_info);
    }


    $field_phone = 'PhoneNumber';
    $field_sphone = 'secondary_PhoneNumber';
    $field_ophone = 'other_PhoneNumber';
    // Replace '+1' with ''
    // if(isset($user_information[$field_phone]) && $user_information[$field_phone] != ''){
    //     $user_information[$field_phone] = str_replace('+1', '', $user_information[$field_phone]);
    // }
    // if(isset($user_information[$field_sphone]) && $user_information[$field_sphone] != ''){
    //     $user_information[$field_sphone] = str_replace('+1', '', $user_information[$field_sphone]);
    // }
    // if(isset($user_information[$field_ophone]) && $user_information[$field_ophone] != ''){
    //     $user_information[$field_ophone] = str_replace('+1', '', $user_information[$field_ophone]);
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

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $(".tab_content").hide();
                            $(".tab_content:first").show();

                            $("ul.tabs li").click(function () {
                                $("ul.tabs li").removeClass("active");
                                $(this).addClass("active");
                                $(".tab_content").hide();
                                var activeTab = $(this).attr("rel");
                                $("#" + activeTab).fadeIn();
                            });
                        });
                    </script>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                    <div id="HorizontalTab" class="HorizontalTab">					
                        <ul class="resp-tabs-list hor_1">
                            <li id="tab1_nav"><a href="javascript:;">Personal Info</a></li>
                            <li id="tab2_nav"><a href="javascript:;">Questionnaire</a></li>
                            <li id="tab3_nav"><a href="javascript:;">Notes</a></li>
                            <li id="tab4_nav"><a href="javascript:;">Messages</a></li>
                            <li id="tab5_nav"><a href="javascript:;">reviews</a></li>
                            <li id="tab6_nav"><a href="javascript:;">Calendar</a></li>
                        </ul>
                        <div class="resp-tabs-container hor_1">
                            <div id="tab1" class="tabs-content">
                                <div  class="universal-form-style-v2 info_view" <?php if ($edit_form) { ?> style="display: none;" <?php } ?>>
                                    <ul>
                                        <div class="form-title-section">
                                            <h2>Personal Information</h2>
                                            <div class="form-btns">
                                                <input type="submit" value="edit" id="edit_button">
                                            </div>												
                                        </div>
                                        <li class="form-col-50-left">
                                            <label>first name:</label>
                                            <p><?php echo $employer["first_name"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>last name:</label>
                                            <p><?php echo $employer["last_name"] ?></p>
                                        </li>								
                                        <li class="form-col-50-left">
                                            <label>email:</label>
                                            <p><?php echo $employer["email"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>phone number:</label>
                                            <p><?=$primary_phone_number_cc;?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>address:</label>
                                            <p><?php echo $employer["Location_Address"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>city:</label>
                                            <p><?php echo $employer["Location_City"] ?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>zipcode:</label>
                                            <p> <?php echo $employer["Location_ZipCode"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>state:</label>									
                                            <p> <?php echo $employer["state_name"] ?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>country:</label>	
                                            <p> <?php echo $employer["country_name"] ?></p>							
                                        </li>
                                        <li class="form-col-50-right">	
                                            <label>Job Title:</label>									
                                            <p> <?php echo $employer["job_title"]; ?></p>								
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Secondary Email:</label>
                                            <?php if (isset($extra_info["secondary_email"])) { ?>
                                                <p> <?php echo $extra_info["secondary_email"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Secondary Phone Number:</label>
                                            <?php if (isset($extra_info["secondary_PhoneNumber"])) { ?>
                                                <p> <?=($extra_info["secondary_PhoneNumber"]);?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Other Email:</label>
                                            <?php if (isset($extra_info["other_email"])) { ?>
                                                <p> <?php echo $extra_info["other_email"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Other Phone Number:</label>
                                            <?php if (isset($extra_info["other_PhoneNumber"])) { ?>
                                                <p> <?=($extra_info["other_PhoneNumber"]);?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-100">
                                            <label>Linkedin public Profile URL</label>
                                            <?php if (isset($employer["linkedin_profile_url"])) { ?>
                                                <p><a href="<?php echo $employer["linkedin_profile_url"]; ?>" target="_blank"> <?php echo $employer["linkedin_profile_url"]; ?></a></p>
                                            <?php } ?>
                                        </li>

                                        <li class="form-col-50-left">
                                            <label>Title:</label>
                                            <?php if(isset($extra_info["title"])) { ?>
                                                <p><?php echo $extra_info["title"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Division:</label>
                                            <?php if(isset($extra_info["division"])) { ?>
                                                <p><?php echo $extra_info["division"]; ?></p>
                                            <?php } ?>
                                        </li>

                                        <li class="form-col-50-left">
                                            <label>Department:</label>
                                            <?php if(isset($extra_info["department"])) { ?>
                                                <p><?php echo $extra_info["department"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Office Location:</label>
                                            <?php if(isset($extra_info["office_location"])) { ?>
                                                <p><?php echo $extra_info["office_location"]; ?></p>
                                            <?php } ?>
                                        </li>
                                 

                                        <?php if(IS_NOTIFICATION_ENABLED == 1 && $phone_sid = '') { ?>
                                        <li class="form-col-50-right">
                                            <label>Notified By</label>
                                            <p><?php echo ucwords($employer["notified_by"]); ?></p>
                                        </li>
                                        <?php } ?>

                                        <li class="form-col-100 auto-height">
                                            <label>Interests:</label>
                                            <?php if(isset($extra_info["interests"])) { ?>
                                                <p><?php echo $extra_info["interests"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-100 auto-height">
                                            <label>Short Bio:</label>
                                            <?php if(isset($extra_info["short_bio"])) { ?>
                                                <p><?php echo $extra_info["short_bio"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        
                                        <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                                        <!-- Timezone -->
                                        <li class="form-col-100 auto-height">
                                            <label>Timezone:</label>
                                            <p><?=get_timezones( $employer['timezone'], 'name' );?></p>
                                        </li>
                                        <?php } ?>

                                    </ul>
                                    <?php if (isset($employer["YouTubeVideo"]) && $employer["YouTubeVideo"] != "") {
                                        if($employer['video_type'] == 'uploaded'){
                                            $fileExt = $employer['YouTubeVideo'];
                                            $fileExt = strtolower(pathinfo($fileExt, PATHINFO_EXTENSION));
                                        }?>
                                        <div class="applicant-video">
                                            <div class="<?= !empty($fileExt) && $fileExt != 'mp3' ? 'well well-sm' : '';?>">
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <?php if($employer['video_type'] == 'youtube') { ?>
                                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $employer['YouTubeVideo']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                    <?php } elseif($employer['video_type'] == 'vimeo') { ?>
                                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $employer['YouTubeVideo']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                    <?php } else {
                                                        if ($fileExt == 'mp3') {?>
                                                            <audio controls>
                                                                <source
                                                                    src="<?php echo base_url() . 'assets/uploaded_videos/' . $employer['YouTubeVideo']; ?>"
                                                                    type='audio/mp3'>
                                                            </audio>
                                                        <?php } else { ?>
                                                            <video controls>
                                                                <source
                                                                    src="<?php echo base_url() . 'assets/uploaded_videos/' . $employer['YouTubeVideo']; ?>"
                                                                    type='video/mp4'>
                                                            </video>
                                                        <?php }
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!--Edit part-->
                                <div <?php if ($edit_form) { ?>style="display: block;" <?php } else { ?>style="display: none;" <?php } ?> class="universal-form-style-v2 info_edit">
                                    <ul>
                                        <form id="edit_employer" method="POST" enctype="multipart/form-data">
                                            <div class="form-title-section">
                                                <h2>Personal Information</h2>
                                                <div class="form-btns">
                                                    <input type="submit" value="Save" onclick="return validate_employers_form()">
                                                    <input type="button" value="cancel" class="view_button"  >
                                                </div>												
                                            </div>
                                            <li class="form-col-50-left">
                                                <label>First Name:<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" name="first_name" id="first_name" value="<?php
                                                if (isset($employer['first_name'])) { echo $employer['first_name']; } ?>">
                                                <?php echo form_error('first_name'); ?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>last name:<samp class="red"> * </samp></label>
                                                <input class="invoice-fields  <?php if (form_error('last_name') !== "") { ?> error <?php } ?>"  value="<?php echo set_value('last_name', $employer["last_name"]); ?>"  type="text" name="last_name">
                                                <?php echo form_error('last_name'); ?>
                                            </li>								
                                            <li class="form-col-50-left">
                                                <label>email: <!--<samp class="red"> * </samp>--></label>
                                                <input <?php if($employer['is_executive_admin'] == '1') { echo 'readonly'; }?> class="invoice-fields <?php if (form_error('email') !== "") { ?> error <?php } ?>"  value="<?php echo set_value('email', $employer["email"]); ?>"  type="email" name="email">
                                                <?php echo form_error('email'); ?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>phone number:</label>
                                                <?=$input_group_start;?>
                                                    <input class="invoice-fields" id="<?=$field_phone;?>"  value="<?php echo set_value('PhoneNumber', $primary_phone_number); ?>" type="text" name="PhoneNumber">
                                                <?=$input_group_end;?>
                                                <?php echo form_error('PhoneNumber'); ?>
                                            </li>
                                            <li class="form-col-100">
                                                <label>address:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('Location_Address', $employer["Location_Address"]); ?>" type="text" name="Location_Address">
                                                <?php echo form_error('Location_Address'); ?>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>city:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('Location_City', $employer["Location_City"]); ?>"  type="text" name="Location_City">
                                                <?php echo form_error('Location_City'); ?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>zipcode:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('Location_ZipCode', $employer["Location_ZipCode"]); ?>" type="text" name="Location_ZipCode">
                                                <?php echo form_error('Location_ZipCode'); ?>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>country:</label>								
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="Location_Country" id="country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?= $active_country["sid"]; ?>"
                                                            <?php if ($employer['Location_Country'] == $active_country["sid"]) { ?>
                                                                        selected
                                                                    <?php } ?> >
                                                                        <?= $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>								
                                            </li>
                                            <li class="form-col-50-right">	
                                                <label>state:</label>
                                                <p style="display: none;" id="state_id"><?php echo $employer['Location_State']; ?></p>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="Location_State" id="state">
                                                        <option value="">Select State</option>  
                                                        <option value="">Please Select your country</option> 
                                                    </select>
                                                </div>								
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Jobs Title:</label>
                                                <input class="invoice-fields"  value="<?php echo set_value('job_title', $employer["job_title"]); ?>" type="text" name="job_title">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Profile picture:</label>
                                                <div class="upload-file invoice-fields">
                                                    <span class="selected-file" id="name_profile_picture">No file selected</span>
                                                    <input type="file" name="profile_picture" id="profile_picture" onchange="check_file_all('profile_picture')">
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>secondary email:</label>
                                                <input class="invoice-fields <?php if (form_error('secondary_email') !== "") { ?> error <?php } ?>"  value="<?php echo set_value('secondary_email', $extra_info["secondary_email"]); ?>"  type="email" name="secondary_email">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>secondary phone number:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="input-group-text" id="basic-addon1">+1</span>
                                                    </div>
                                                    <input class="invoice-fields" id="<?=$field_sphone;?>"  value="<?php echo set_value('secondary_PhoneNumber', phonenumber_format($extra_info["secondary_PhoneNumber"]), true); ?>" type="text" name="secondary_PhoneNumber">
                                                </div>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>other email:</label>
                                                <input class="invoice-fields"  value="<?php echo set_value('other_email', $extra_info["other_email"]); ?>"  type="email" name="other_email">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>other phone number:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="input-group-text" id="basic-addon1">+1</span>
                                                    </div>
                                                    <input class="invoice-fields" id="<?=$field_ophone;?>"  value="<?php echo set_value('other_PhoneNumber', phonenumber_format($extra_info["other_PhoneNumber"]), true); ?>" type="text" name="other_PhoneNumber">
                                                </div>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Linkedin Public Profile URL:</label>
                                                <input class="invoice-fields"  value="<?php echo set_value('linkedin_profile_url', $employer["linkedin_profile_url"]); ?>" type="text" name="linkedin_profile_url">
                                            </li>
                                            
                                            <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                                            <li class="form-col-50-right">
                                                <label>Timezone:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="timezone" id="timezone">
                                                        <option value="">Please Select</option>
                                                        <?php if(!empty($timezones)) { ?>
                                                            <?php foreach($timezones as $key => $zone) { ?>
                                                                <?php $default_selected = ( $zone['value'] == $employer['timezone'] ? true : false ); ?>
                                                                <option <?=set_select('timezone', $key, $default_selected); ?> value="<?php echo $zone['value']?>"><?php echo $zone['name']; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <?=form_error('company_timezone'); ?>
                                            </li>
                                            <?php } ?>

                                            <li class="form-col-50-left">
                                                <?php $title = isset($extra_info["title"]) ? $extra_info["title"] : ''; ?>
                                                <label>Title:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('title', $title); ?>" type="text" name="title" id="title">
                                            </li>

                                            <li class="form-col-50-right">
                                                <?php $division = isset($extra_info["division"]) ? $extra_info["division"] : ''; ?>
                                                <label>Division:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('division', $division); ?>" type="text" name="division" id="division">
                                            </li>

                                            <li class="form-col-50-left">
                                                <?php $field_id = 'ssn'; ?>
                                                <?php $required_asterisk = $ssn_required ? '<span class="required">*</span>' : ''; ?>
                                                <?php $required_rule = $ssn_required ? 'required="required"' : ''; ?>
                                                <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id])) ? $user_information[$field_id] : ''); ?>
                                                <?php echo form_label('Social Security Number: '.$required_asterisk, $field_id); ?>
                                                <?php echo form_input($field_id, set_value($field_id, $temp), 'class="invoice-fields" id="' . $field_id . '"'. $required_rule); ?>
                                            </li>

                                            <li class="form-col-50-right">
                                                <?php $field_id = 'dob'; ?>
                                                <?php $required_asterisk = $dob_required ? '<span class="required">*</span>' : ''; ?>
                                                <?php $required_rule = $dob_required ? 'data-rule-required="true"' : ''; ?>
                                                <?php $temp = ((isset($user_information[$field_id]) && !empty($user_information[$field_id]) && $user_information[$field_id] != '0000-00-00') ? date('m-d-Y', strtotime(str_replace('-', '/', $user_information[$field_id]))) : ''); ?>
                                                <label>Date of Birth: <?= $required_asterisk;?></label>
                                                <input class="invoice-fields" id="date_of_birth" readonly="" type="text" <?= $required_rule;?> name="<?php echo $field_id;?>" value="<?php echo $temp; ?>">
                                            </li>


                                            <li class="form-col-50-left">
                                                <?php $department = isset($extra_info["department"]) ? $extra_info["department"] : ''; ?>
                                                <label>Department:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('department', $department); ?>" type="text" name="department" id="department">
                                            </li>
                                             <li class="form-col-50-right">
                                                <?php $office_location = isset($extra_info["office_location"]) ? $extra_info["office_location"] : ''; ?>
                                                <label>Office Location:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('office_location', $office_location); ?>" type="text" name="office_location" id="office_location">
                                            </li>

                                            <li class="form-col-50-right">
                                                <?php $uniform_top_size = isset($employer["uniform_top_size"]) ? $employer["uniform_top_size"] : ''; ?>
                                                <label>Uniform Top Size:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('uniform_top_size', $uniform_top_size); ?>" type="text" name="uniform_top_size" id="uniform_top_size">
                                            </li>

                                            <li class="form-col-50-right">
                                                <?php $uniform_bottom_size = isset($employer["uniform_bottom_size"]) ? $employer["uniform_bottom_size"] : ''; ?>
                                                <label>Uniform Bottom Size:</label>
                                                <input class="invoice-fields" value="<?php echo set_value('uniform_bottom_size', $uniform_bottom_size); ?>" type="text" name="uniform_bottom_size" id="uniform_bottom_size">
                                            </li>
                                            
                                            
                                            <?php if(IS_NOTIFICATION_ENABLED == 1 && $phone_sid = ''){ ?>
                                            <li class="form-col-50-left">
                                                <label>Notified By</label>
                                                <div class="hr-fields-wrap">
                                                     <div class="hr-select-dropdown">
                                                        <select  class="invoice-fields" name="notified_by[]" id="notified_by" multiple="true">
                                                            <option value="email" <?php if(in_array('email', explode(',', $employer['notified_by']))){echo 'selected="true"';}?> >Email</option>
                                                            <option value="sms" <?php if(in_array('sms', explode(',', $employer['notified_by']))){echo 'selected="true"';}?>>SMS</option> 
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php } ?>
                                            
                                            <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?> 
                                            <!-- Timezone -->
                                            <li class="form-col-100">
                                                <label>Timezone:</label>
                                                <?=timezone_dropdown(
                                                    $employer['timezone'], 
                                                    array(
                                                        'class' => 'invoice-fields js-timezone',
                                                        'id' => 'timezone',
                                                        'name' => 'timezone'
                                                    )
                                                );?>
                                            </li>
                                            <?php } ?>

                                            <li class="form-col-100 auto-height">
                                                <?php $interests = isset($extra_info["interests"]) ? $extra_info["interests"] : ''; ?>
                                                <label>Interests:</label>
                                                <textarea id="interests" name="interests" class="invoice-fields auto-height ckeditor" rows="6"><?php echo set_value('interests', $interests); ?></textarea>
                                            </li>

                                            <li class="form-col-100 auto-height">
                                                <?php $short_bio = isset($extra_info["short_bio"]) ? $extra_info["short_bio"] : ''; ?>
                                                <label>Short Bio:</label>
                                                <textarea id="short_bio" name="short_bio" class="invoice-fields auto-height ckeditor" rows="6"><?php echo set_value('short_bio', $short_bio); ?></textarea>
                                            </li>
                                            <li class="form-col-100 auto-height">
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
                                                                            <input type="radio" name="video_source" class="video_source" value="youtube" <?php echo !empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'youtube' ? 'checked="checked"':''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                                                            <input type="radio" name="video_source" class="video_source" value="vimeo" <?php echo !empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'vimeo' ? 'checked="checked"':''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                                                            <input type="radio" name="video_source" class="video_source" value="uploaded" <?php echo !empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'uploaded' ? 'checked="checked"':''; ?>>
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
                                                                    if (!empty($employer['YouTubeVideo']) && $employer['video_type'] == 'youtube') {
                                                                        $video_link = 'https://www.youtube.com/watch?v='.$employer['YouTubeVideo'];
                                                                    } else if (!empty($employer['YouTubeVideo']) && $employer['video_type'] == 'vimeo') {
                                                                        $video_link = 'https://vimeo.com/'.$employer['YouTubeVideo'];
                                                                    } else {
                                                                        $video_link = '';
                                                                    }
                                                                ?>
                                                                <label for="YouTube_Video" id="label_youtube">Youtube Video:</label>
                                                                <label for="Vimeo_Video" id="label_vimeo" style="display: none">Vimeo Video:</label>
                                                                <input type="text" name="yt_vm_video_url" value="<?php echo $video_link; ?>" class="invoice-fields" id="yt_vm_video_url">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="upload_input" style="display: none">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <label for="YouTubeVideo">Upload Video:</label>                                     
                                                                <div class="upload-file invoice-fields">
                                                                    <?php 
                                                                        if (!empty($employer['YouTubeVideo']) && $employer['video_type'] == 'uploaded') {
                                                                    ?>
                                                                            <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $employer['YouTubeVideo']; ?>">
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
                                                <?php if(!empty($employer['YouTubeVideo'])) {
                                                    if($employer['video_type'] == 'uploaded'){
                                                        $fileExt = $employer['YouTubeVideo'];
                                                        $fileExt = strtolower(pathinfo($fileExt, PATHINFO_EXTENSION));
                                                    }?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="<?= !empty($fileExt) && $fileExt != 'mp3' ? 'well well-sm' : '';?>">
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                            <?php if($employer['video_type'] == 'youtube') { ?>
                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $employer['YouTubeVideo']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                            <?php } elseif($employer['video_type'] == 'vimeo') { ?>
                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $employer['YouTubeVideo']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                            <?php } else {
                                                                if ($fileExt == 'mp3') {?>
                                                                    <audio controls>
                                                                        <source
                                                                            src="<?php echo base_url() . 'assets/uploaded_videos/' . $employer['YouTubeVideo']; ?>"
                                                                            type='audio/mp3'>
                                                                    </audio>
                                                                <?php } else { ?>
                                                                    <video controls>
                                                                        <source
                                                                            src="<?php echo base_url() . 'assets/uploaded_videos/' . $employer['YouTubeVideo']; ?>"
                                                                            type='video/mp4'>
                                                                    </video>
                                                                <?php }
                                                            }?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </li>

                                            <div class="form-title-section">
                                                <div class="form-btns">
                                                    <input type="hidden" name="old_profile_picture" value="<?php echo $employer['profile_picture']; ?>">
                                                    <input type="hidden" name="id" value="<?php echo $employer['sid']; ?>">
                                                    <input type="submit" value="Save" onclick="return validate_employers_form()">
                                                    <input type="button" value="cancel" class="view_button">
                                                </div>												
                                            </div>
                                        </form>
                                    </ul>
                                </div>
                                <!--Edit part Ends-->
                            </div>
                            <!-- #tab1 -->
                            <div id="tab2" class="tabs-content">
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('.collapse').on('shown.bs.collapse', function () {
                                            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
                                        }).on('hidden.bs.collapse', function () {
                                            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
                                        });
                                    });
                                </script>
                                <?php
                                $employer['test'] = false;
                                
                                if ($employer['test']) { ?>
                                    <div class="tab-header-sec">
                                        <h2 class="tab-title">Screening Questionnaire</h2>
                                        <div class="tab-btn-panel">
                                            <span>Score : <?php echo $employer["score"] ?></span>
                                            <a href="javascript:;">Pass</a>
                                        </div>
                                        <p class="questionnaire-heading">Question’s / Answer’s</p>
                                    </div>
                                    <div class="panel-group-wrp">					      	
                                        <div class="panel-group" id="accordion">
                                            <?php $counter = 0;
                                            foreach ($employer['questionnaire'] as $key => $value) { ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?php echo $counter ?>">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseOne_<?php echo $counter ?>" class="panel-collapse collapse">
                                                        <?php
                                                        if (is_array($value)) {
                                                            foreach ($value as $answer) { ?>
                                                                <div class="panel-body">
                                                                    <?php echo $answer; ?>
                                                                </div>
                                                                <?php
                                                            }
                                                        } else { ?>
                                                            <div class="panel-body">
                                                                <?php echo $value; ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>  
                                                </div>
                                                <?php
                                                $counter++;
                                            } ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="tab-header-sec">
                                        <h2 class="tab-title">Screening Questionnaire</h2>
                                        <div class="applicant-notes">                                        
                                            <p class="notes-not-found">No questionnaire found!</p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div><!-- #tab2 --> 
                            <div id="tab3" class="tabs-content">
                                <div class="universal-form-style-v2" id="show_hide">
                                    <form action="<?php echo base_url('employee_management/insert_notes') ?>" method="POST" id="note_form">
                                        <input type="hidden" name="action" value="add_note">
                                        <input type="hidden" name="employers_sid" value="<?php echo $employer["parent_sid"]; ?>">
                                        <input type="hidden" name="applicant_job_sid" value="<?php echo $employer["sid"]; ?>">
                                        <input type="hidden" name="applicant_email" value="<?php echo $employer["email"]; ?>">
                                        <div class="form-title-section">
                                            <h2>Employee Notes</h2>
                                            <div class="form-btns"> 
                                                <!--<input type="submit" style="display: none;" class="note_div" value="save">
                                                <input type="button" id="cancel_note" style="display: none;" class="note_div" value="cancel">
                                                <input type="submit" class="no_note" id="add_notes" value="Add note">-->
                                            </div>												
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <div class="applicant-notes">
                                            <div class="hr-ck-editor note_div" style="display: none;" >
                                                <textarea class="ckeditor" id="notes"  name="notes" rows="8" cols="60" ></textarea>
                                            </div>
                                            <span class="notes-not-found  no_note" <?php if (empty($employee_notes)) { ?>style="display: block;" <?php } else { ?> style="display: none;"<?php } ?>>No Notes Found</span> 
                                            <?php foreach ($employee_notes as $note) { ?>
                                                <article class="notes-list" id="notes_<?= $note['sid'] ?>">
                                                    <h2>
                                                        <span id="<?= $note['sid'] ?>"><?= $note['notes'] ?></span>
                                                        <p class="postdate"><?php echo date('m-d-Y', strtotime($note['insert_date'])); ?></p>
                                                        <!--<div class="edit-notes">
                                                            <a href="javascript:;" style="height: 20px; line-height: 0; color: white; font-size: 10px;" class="grayButton siteBtn notes-btn" onclick="modify_note(<?= $note['sid'] ?>)">View / Edit</a>
                                                            <a href="javascript:;" style="height: 20px; line-height: 0; color: white; font-size: 10px;" class="siteBtn notes-btn btncancel" onclick="delete_note(<?= $note['sid'] ?>)">Delete</a>
                                                        </div>-->
                                                    </h2>
                                                </article>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                                <div class="universal-form-style-v2" style="display: none" id="edit_notes">
                                    <form name="edit_note" action="<?php echo base_url('employee_management/insert_notes') ?>" method="POST">
                                        <div class="form-title-section">
                                            <h2>Employee Notes</h2>
                                            <div class="form-btns">              
                                                <input type="submit" name="note_submit" value="Update">
                                                <input onclick="cancel_notes()" type="button" name="cancel" value="Cancel">
                                            </div>
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <textarea class="ckeditor" name="my_edit_notes" id="my_edit_notes" cols="67" rows="6" ></textarea>
                                        <input type="hidden" name="action" value="edit_note">
                                        <input type="hidden" name="employers_sid" value="<?php echo $employer["parent_sid"]; ?>">
                                        <input type="hidden" name="applicant_job_sid" value="<?php echo $employer["sid"]; ?>">
                                        <input type="hidden" name="applicant_email" value="<?php echo $employer["email"]; ?>">
                                        <input type="hidden" name="sid" id="sid" value="">
                                    </form>
                                </div>				       
                            </div><!-- #tab3 --> 
                            <div id="tab4" class="tabs-content">
                                <!--<form enctype="multipart/form-data" action="<?php echo base_url('applicant_profile/applicant_message') ?>" method="post">
                                    <div class="form-title-section">
                                        <h2>messages</h2>
                                        <div class="form-btns message">
                                            <div class="btn-inner">
                                                <input type="file" name="message_attachment" class="choose-file-filed"> 
                                                <a href="" class="select-photo" style="background: grey;">Add Attachment</a>
                                            </div>
                                        </div>
                                        <div class="message-div" >
                                            <div class="comment-box">
                                                <div class="textarea">
                                                    <input type="hidden" name="to_id" value="<?php echo $employer["email"]; ?>" >
                                                    <input type="hidden" name="from_type" value="employer" >
                                                    <input type="hidden" name="to_type" value="admin" >
                                                    <input type="hidden" name="applicant_name" value="<?php echo $employer["first_name"]; ?> <?php echo $employer["last_name"]; ?>" >
                                                    <input type="hidden" name="job_id" value="<?php // echo $id;      ?>">
                                                    <input type="hidden" name="employee_id" value="<?php echo $id; ?>">

                                                    <input type="hidden" name="users_type" value="employee" >

                                                    <input class="message-subject" required="required" name="subject" type="text" placeholder="Enter Subject (required)"/>
                                                    <textarea id="applicantMessage" required="required" name="message"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment-btn">
                                            <input type="submit" value="Send Message" style="background: grey;">
                                        </div>											
                                    </div>
                                </form>	-->
                                <div class="respond">
                                    <?php
                                    if (count($applicant_message) > 0) {
                                        foreach ($applicant_message as $message) {
                                            ?>
                                            <article <?php if ($message['outbox'] == 1) { ?>class="reply"<?php } ?> id="delete_message<?php echo $message['id']; ?>">
                                                <figure><img <?php if (empty($message['profile_picture'])) { ?>
                                                            src="<?= base_url() ?>assets/images/attachment-img.png"
                                                        <?php } else { ?>
                                                            src="<?php echo AWS_S3_BUCKET_URL . $message['profile_picture']; ?>" width="48"
                                                        <?php } ?>
                                                        >
                                                </figure>
                                                <div class="text">
                                                    <div class="message-header">
                                                        <div class="message-title">
                                                            <h2>
                                                                <?php
                                                                if (!empty($message['first_name'])) {
                                                                    echo ucfirst($message['first_name']);
                                                                    if (!empty($message['last_name'])) {
                                                                        echo " " . ucfirst($message['last_name']);
                                                                    }
                                                                } else {
                                                                    echo $message['username'];
                                                                }
                                                                ?>
                                                            </h2>
                                                        </div>
                                                        <ul class="message-option">
                                                            <li>
                                                                <time><?php echo my_date_format($message['date']); ?></time>
                                                            </li>
                                                            <?php if ($message['outbox'] == 1) { ?>
                                                                <?php // do nothing  ?>
                                                            <?php } ?>
                                                            <?php if ($message['attachment']) { ?>
                                                                <li>
                                                                    <a class="action-btn" href="<?php echo AWS_S3_BUCKET_URL . $message['attachment']; ?>">
                                                                        <i class="fa fa-download"></i>
                                                                        <span class="btn-tooltip">Download File</span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <!--<li>
                                                            <a class="action-btn remove" onclick="delete_message(<?php //echo $message['id']; ?>)" href="javascript:;">
                                                                <i class="fa fa-remove"></i>
                                                                <span class="btn-tooltip">Delete</span>
                                                            </a>
                                                        </li>-->
                                                        </ul>
                                                    </div>
                                                    <span><?php echo ucfirst($message['subject']); ?></span>
                                                    <p><?php echo ucfirst($message['message']); ?></p>
                                                </div>
                                            </article>
                                            <?php
                                        }
                                    } else {
                                        echo '  <div class="applicant-notes">
                                                    <span class="notes-not-found ">No Messages Found!</span> 
                                                </div>';
                                    } ?>
                                </div>			       
                            </div><!-- #tab4 --> 
                            <div id="tab5" class="tabs-content">
                                <div class="universal-form-style-v2">
                                    <div class="form-title-section">
                                        <h2>Reviews and Ratings</h2>
                                        <div class="form-btns"> 
                                        </div>												
                                    </div>
                                    <?php if ($applicant_ratings_count !== NULL) { ?>
                                        <div class="start-rating yellow-stars">
                                            <input readonly="readonly"  id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?> type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                                            <p class="rating-count"><?php echo $applicant_average_rating; ?></p>
                                            <p><?php echo $applicant_ratings_count; ?> review(s)</p>
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Rating By All Employers</p>
                                        </div>
                                        <div class="applicant-notes">
                                            <?php foreach ($applicant_all_ratings as $rating) { ?>
                                                <article class="comment-list box-view">
                                                    <h2><?php echo $rating['username']; ?></h2>
                                                    <div class="start-rating">
                                                        <input readonly="readonly"  id="input-21b" value="<?php echo $rating['rating']; ?>"type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                                                    </div>
                                                    <p><?php echo $rating['comment']; ?></p>
                                                </article>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="applicant-notes">
                                            <span class="notes-not-found ">No Review Found</span> 
                                        </div>
                                    <?php } ?>
                                </div>		       
                            </div><!-- #tab5 --> 
                            <div id="tab6" class="tabs-content">
                                <div class="form-title-section">
                                    <h2>Calendar & Scheduling</h2>
                                    <div class="form-btns event_detail">              
                                        <input type="button" id="add_event" value="Add Event">
                                    </div>
                                    <form action="<?php echo base_url('applicant_profile/event_schedule'); ?>" name="event_form" id="event_form" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="applicant_job_sid" value="<?= $id ?>">
                                        <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                        <input type="hidden" name="users_type" value="employee">
                                        <input type="hidden" name="applicant_email" value="<?= $email ?>">
                                        <input type="hidden" name="action" value="add_event">
                                        <input type="hidden" name="redirect_to" value="my_profile">
                                        <div class="event_create" style="display: none">
                                            <div class="form-btns event_create" style="display: none">
                                                <input type="submit" value="Save" />
                                                <input onclick="cancel_event()" type="button"  value="Cancel">
                                            </div>
                                            <div class="form-col-100">
                                                <div class="hr-box">
                                                    <div class="hr-box-header">
                                                        <h1 class="hr-registered">Schedule Event</h1>
                                                    </div>
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <label class="group-label">Event Title<span
                                                                                class="staric">*</span>
                                                                        </label>
                                                                    </th>
                                                                    <th>
                                                                        <label class="group-label">
                                                                            <i class="fa fa-calendar"></i>Date<span
                                                                                class="staric">*</span>
                                                                        </label>
                                                                    </th>
                                                                    <th class="text-center" colspan="3">
                                                                        <label class="group-label">
                                                                            <i class="fa fa-clock-o"></i>Time<span
                                                                                class="staric">*</span>
                                                                        </label>
                                                                    </th>
                                                                    <th class="text-center">
                                                                        <label class="group-label">
                                                                            <i class="fa fa-user"></i>Participant(s)<span
                                                                                class="staric">*</span>
                                                                        </label>
                                                                    </th>
                                                                    <th>Category</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="group-element">
                                                                        <input type="text" placeholder="Event Title" name="title" id="title" class="invoice-fields">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="group-element">
                                                                        <input type="text" readonly="" placeholder="Event date" name="date" class="invoice-fields" required="required" id="eventdate">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="group-element">
                                                                        <input name="eventstarttime" id="eventstarttime" value="12:00AM" readonly="readonly" type="text" class="stepExample1 eventstarttime" required="required">

                                                                    </div>
                                                                </td>
                                                                <td style="vertical-align: middle;">To</td>

                                                                <td>
                                                                    <div class="group-element">
                                                                        <input name="eventendtime" id="eventendtime" readonly="readonly" value="12:00PM" type="text" class="stepExample2 eventendtime" required="required">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="group-element">
                                                                        <select class='contact_id' multiple name="interviewer[]" >
                                                                            <option></option>
                                                                            <?php foreach ($company_accounts as $account) { ?>
                                                                                <option
                                                                                    value="<?= $account['sid'] ?>">
                                                                                    <?= $account['username'] ?>
                                                                                </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="group-element">
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" id='category' name='category' required="required" >
                                                                                <option value="call">Call</option>
                                                                                <option value="email">Email</option>
                                                                                <option value="meeting">Meeting</option>
                                                                                <!--                                                                            <option selected="selected" value="interview">Interview</option>-->
                                                                                <option value="personal">Personal</option>
                                                                                <option value="other">Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="Location_Address-area form-col-100">
                                                    <div class="cl-title">
                                                        <h2>Meeting Location</h2>
                                                    </div>
                                                    <input type="text" name="address" placeholder="Enter valid address for Google Maps" class="form-col-100 invoice-fields">
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="applicant-comments-label">
                                                    <input id="goto_meeting" class="goto_meeting" value="1" name="goToMeetingCheck" type="checkbox">
                                                    <label for="goto_meeting">Meeting Call In Details</label>
                                                </div>
                                                <div class="show-hide-meeting meeting-div" style="display: none">
                                                    <div class="address-area form-col-50-left">
                                                        <input type="text" name="meetingCallNumber" id="meetingCallNumber" placeholder="Meeting Call In Number" class="form-col-100 invoice-fields">
                                                    </div>
                                                    <div class="address-area form-col-50-right">
                                                        <input type="text" name="meetingId" id="meetingId" placeholder="Meeting ID Number" class="form-col-100 invoice-fields">
                                                    </div>
                                                    <div class="address-area form-col-100">
                                                        <input type="text" name="meetingURL" id="meetingURL" placeholder="Webinar/Meeting log in URL" class="form-col-100 invoice-fields">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="applicant-comments-label">
                                                    <input id="interviewer_comment" class="interviewer_comment" value="1" name="commentCheck" type="checkbox">
                                                    <label for="interviewer_comment">Comment For Participant(s)</label>
                                                </div>
                                                <div class="show-hide-comments comment-div" style="display: none">
                                                    <div class="comment-box">
                                                        <div class="textarea">
                                                            <textarea id="interviewerComment" name="comment"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="applicant-comments-label">
                                                    <input id="candidate_msg" name="messageCheck" value="1" type="checkbox">
                                                    <label for="candidate_msg">Notes for <?php echo $employer["first_name"] . ' ' . $employer["last_name"]; ?></label>
                                                </div>
                                                <div class="show-hide-comments message-div" style="display: none">
                                                    <div class="comment-box">
                                                        <div class="textarea">
                                                            <input class="message-subject" name="subject" type="text" placeholder="Enter Subject (required)"/>
                                                            <textarea id="applicantMessage" name="message"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="upload-file invoice-fields upload-sm">
                                                        <span class="selected-file" id="name_message_file_add">No file selected</span>
                                                        <input type="file" id="message_file_add" name="messageFile" onchange="check_file_all('message_file_add'); " />
                                                        <a href="javascript:;">Add Attachment</a>
                                                    </div>
                                                </div>
                                                <div class="form-btns event_create" style="display: none">
                                                    <input type="submit" onclick="validate_form()" value="Save">
                                                    <input onclick="cancel_event()" type="button"  value="Cancel">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="event_detail">
                                    <?php
                                    if (!empty($applicant_events)) {
                                        foreach ($applicant_events as $event) { ?>
                                            <div class="hr-box" id="remove_li<?= $event["sid"] ?>">
                                                <div class="hr-box-header">
                                                    <span class="pull-left">
                                                        <strong>
                                                        Event : <?php echo ( $event['title'] != '' ? $event['title'] : 'No Title Specified'); ?>
                                                        </strong>
                                                    </span>
                                                    <span class="pull-right">
                                                        <a href="javascript:;"  class="btn btn-info btn-xs" data-toggle="modal" data-target="#editModal_<?= $event["sid"] ?>">
                                                            <i class="fa fa-pencil"></i></a>
                                                        <a href="javascript:void(0);" class="btn btn-danger btn-xs"  onclick="remove_event(<?= $event["sid"] ?>)" >
                                                            <i class="fa fa-remove"></i></a>
                                                    </span>
                                                </div>
                                                <div class="table-responsive hr-innerpadding">
                                                <table class="table table-striped table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <th>Event Category</th>
                                                        <th class="text-center">Event Date</th>
                                                        <th class="text-center">Start Time</th>
                                                        <th class="text-center">End Time</th>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo ucwords( $event['category']); ?></td>
                                                        <td class="text-center"><?php echo date("M jS, Y", strtotime($event['date'])) ?></td>
                                                        <td class="text-center"><?php echo $event['eventstarttime']; ?></td>
                                                        <td class="text-center"><?php echo $event['eventendtime']; ?></td>
                                                    </tr>
                                                    </tr>
                                                    <?php if($event['subject'] != '' && $event['message'] != '') { ?>
                                                        <tr>
                                                            <th class="col-lg-3">Message to Candidate</th>
                                                            <td colspan="3">
                                                                <h5 style="margin-top: 0;"><strong><?php echo $event['subject']; ?></strong></h5>
                                                                <p><?php echo $event['message']; ?></p>
                                                        <span class="pull-right">
                                                            <?php if($event['messageFile'] != '') { ?>
                                                                <a href="<?php echo AWS_S3_BUCKET_URL . $event['messageFile']; ?>" class="btn btn-success btn-sm">Message Attachment</a>
                                                            <?php } else { ?>
                                                                <a href="javascript:void(0);" class="btn btn-success btn-sm disabled">Message Attachment</a>
                                                            <?php } ?>
                                                        </span>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <th class="col-lg-3">Participants</th>
                                                        <td colspan="3">
                                                            <?php $event['interviewer'] = explode(',', $event['interviewer']);
                                                            foreach ($company_accounts as $subaccount) {
                                                                foreach ($event['interviewer'] as $interviewer) {
                                                                    if ($interviewer == $subaccount['sid']) {
                                                                        ?>
                                                                        <div
                                                                            class="badge"><?php echo $subaccount['username']; ?></div>
                                                                        <?php
                                                                    }
                                                                }
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                    <?php if($event['goToMeetingCheck'] == 1) { ?>
                                                        <tr>
                                                            <th>Meeting Call Details</th>
                                                            <td colspan="3">
                                                                <table class="table">
                                                                    <tr>
                                                                        <th class="col-lg-4 text-center">Number</th>
                                                                        <th class="col-lg-4 text-center">Meeting ID</th>
                                                                        <th class="col-lg-4 text-center">Meeting Url</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo $event['meetingCallNumber']?></td>
                                                                        <td class="text-center"><?php echo $event['meetingId']?></td>
                                                                        <td class="text-center"><?php echo $event['meetingURL']?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if($event['comment'] != '') { ?>
                                                        <tr>
                                                            <td class="col-lg-3"><strong>Comment</strong></td>
                                                            <td colspan="3">
                                                                <?php echo $event['comment']; ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else { ?>
                                        <div class="applicant-notes">
                                            <p class="notes-not-found">No event scheduled!</p>
                                        </div>
                                    <?php } ?> 
                                </div>
                            </div><!-- #tab6 --> 
                        </div>	
                    </div>				
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
    <?php if (!empty($applicant_events)) { ?>
        
        <?php foreach ($applicant_events as $event) { ?>
            <!-- Modal -->
            <div id="editModal_<?= $event["sid"] ?>" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header modal-header-bg">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Event Management</h4>
                        </div>
                        <form class="date_form" action="<?php echo base_url('applicant_profile/event_schedule'); ?>" method="POST" enctype="multipart/form-data" >
                            <div class="modal-body">
                                <div class="universal-form-style-v2 modal-form">
                                    <ul class="row">
                                        <input type="hidden" name="applicant_job_sid" value="<?= $id ?>">
                                        <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                        <input type="hidden" name="users_type" value="employee">
                                        <input type="hidden" name="applicant_email" value="<?= $email ?>">
                                        <input type="hidden" name="action" value="edit_event">
                                        <input type="hidden" name="sid" value="<?php echo $event["sid"]; ?>">
                                        <input type="hidden" name="redirect_to" value="my_profile">
                                        
                                        <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> 
                                            <label>Title:</label>
                                            <input id="eventtitle<?= $event["sid"] ?>" type="text" name="title" placeholder='Enter title here' value="<?= $event["title"] ?>" class="invoice-fields" >
                                        </li>
                                        <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label>Category:</label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" id='category' name='category'>
                                                    <option value="call" <?php if ($event["category"] == 'call') { ?> selected <?php } ?> >Call</option>
                                                    <option value="email" <?php if ($event["category"] == 'email') { ?> selected <?php } ?> >Email</option>
                                                    <option value="meeting" <?php if ($event["category"] == 'meeting') { ?> selected <?php } ?> >Meeting</option>
                                                    <!--<option value="interview" <?php if ($event["category"] == 'interview') { ?> selected <?php } ?>  >Interview</option>-->
                                                    <option value="personal" <?php if ($event["category"] == 'personal') { ?> selected <?php } ?>  >Personal</option>
                                                    <option value="other" <?php if ($event["category"] == 'other') { ?> selected <?php } ?>  >Other</option>
                                                </select>
                                            </div>
                                        </li>
                                        <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">  
                                            <label>Event Date:</label>
                                            <input class="eventdate invoice-fields" name="date" type="text" value="<?php echo date('m-d-Y', strtotime($event["date"])); ?>" id="datepicker101<?= $event['sid'] ?>" readonly="readonly" required="">
                                        </li> 
                                        <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4"> 
                                            <label>Start Time:</label>
                                            <input id="eventstarttime_<?= $event['sid'] ?>" name="eventstarttime" type="text" value="<?= $event["eventstarttime"] ?>" class="stepExample1 eventstarttime invoice-fields">
                                        </li>
                                        <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">                                                       
                                            <label>End Time: </label>
                                            <input id="eventendtime_<?= $event['sid'] ?>" name="eventendtime" type="text" value="<?= $event["eventendtime"] ?>"  class="stepExample2 eventendtime invoice-fields">
                                        </li>
                                        <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                                            <label>Participant(s)</label>
                                            <select class='contact_id invoice-fields' multiple name="interviewer[]" > 
                                                <?php
                                                $event['interviewer'] = explode(',', $event['interviewer']);
                                                foreach ($company_accounts as $account) {
                                                    ?>
                                                    <option value="<?= $account['sid'] ?>"
                                                    <?php
                                                    foreach ($event['interviewer'] as $interviewer) {
                                                        if ($interviewer == $account['sid']) {
                                                            ?>
                                                                    selected="selected"
                                                                    <?php
                                                                }
                                                            }
                                                            ?>>
                                                                <?= $account['username'] ?>

                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </li>
                                        <?php //if ($event['commentCheck'] == 1) { ?>
                                            <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                                <label>Comment for Participant(s):</label>
                                                <input  value="1" name="commentCheck" type="hidden">
                                                <textarea class="invoice-fields comment-field" id="interviewerComment" name="comment"><?php echo $event['comment']; ?></textarea>
                                                
                                            </li>
                                        <?php //} ?>
                                        <?php //if ($event['messageCheck'] == 1) { ?>
                                            <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                                <label>Notes for <?php echo $employer["first_name"] . ' ' . $employer["last_name"]; ?>:</label>
                                                <input  value="1" name="messageCheck" type="hidden">
                                                <div class="comment-box">
                                                    <div class="textarea">
                                                        <input class="message-subject" name="subject" type="text" value="<?php echo $event['subject']; ?>" placeholder="Enter Subject (required)"/>
                                                        <textarea  name="message"><?php echo $event['message']; ?></textarea>                                                                    
                                                    </div>
                                                </div>
                                            </li>
                                        <?php //} ?>                                               
                                        <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label>Message Attachment: </label>
                                            <div class="upload-file invoice-fields">
                                                <span class="selected-file" id="name_messageFile">No file selected</span>
                                                <input type="file" id="messageFile" name="messageFile"  onchange="check_file_all('messageFile');" />
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                            <div class="current-attachments bg-success col-lg-12">
                                                <label class="pull-left">Current Attachment: </label>
                                                <div class="pull-right">
                                                    <?php if($event['messageFile'] != '') { ?>
                                                        <a href="<?php echo AWS_S3_BUCKET_URL . $event['messageFile']; ?>"><i class="fa fa-paperclip"></i> <span><?php echo $event['messageFile']; ?></span></a>
                                                    <?php } else { ?>
                                                        <a href="javascript:;"><i class="fa fa-paperclip"></i> <span>No file attached</span></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>  
                                        <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label>Address: </label>
                                            <input class="invoice-fields"  name="address" type="text" value="<?php echo $event["address"] ?>"  >
                                        </li>                                              
                                        <?php //if ($event['goToMeetingCheck'] == 1) { ?>
                                            <input  value="1" name="goToMeetingCheck" type="hidden">
                                            <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label>Meeting Call In #: </label>
                                                <input class="invoice-fields"  name="meetingCallNumber" type="text" value="<?php echo $event["meetingCallNumber"] ?>"  >
                                            </li>
                                            <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label>Meeting ID #: </label>
                                                <input class="invoice-fields"  name="meetingId" type="text" value="<?php echo $event["meetingId"] ?>"  >
                                            </li>
                                            <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <label>Webinar/Meeting log in URL: </label>
                                                <input class="invoice-fields"  name="meetingURL" type="text" value="<?php echo $event["meetingURL"] ?>"  >
                                            </li>
                                        <?php //} ?>
                                        <!--<li>
                                            <label>Description:</label>
                                            <div class="fields-wrapper">
                                                <textarea name="description" id='description_1' class="eventtextarea"><?//= $event["description"] ?></textarea>
                                            </div>
                                            </p>
                                        </li>-->
                                    </ul>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input class="btn btn-success"  type='submit' value="Save" id="add_edit_submit" >
                                <a href="javascript:;" class="btn btn-default" data-dismiss="modal">Close</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>            

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

<!-- Main End -->
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<!--file opener modal starts--> 
<script language="JavaScript" type="text/javascript">
<?php if(IS_NOTIFICATION_ENABLED == 1 && $phone_sid = '') {?>
$('#notified_by').select2({
    closeOnSelect : false,
    allowHtml: true,
    allowClear: true,
    tags: true 
});
<?php } ?>
    $('.startdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50",
    }).val();

    $('#date_of_birth').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();
    
    function remove_event(val) {
        var sid = val;
        alertify.defaults.glossary.title = 'Delete Event';
        alertify.confirm("Are you sure you want to delete the event?",
            function () {
                $.ajax({
                    url: "<?= base_url('applicant_profile/deleteEvent') ?>?action=remove_event&sid=" + sid,
                    success: function (data) {
                        console.log(data);
                    }
                });

                $('#remove_li' + val).hide();
                alertify.success('Event deleted successfully.');
            },
            function () {
                //alertify.error('');
            });
    }
    function validate_employers_form() {
        $("#edit_employer").validate({
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
                Location_Address: {
                    pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                },
                email: {
                    email: true,
                },
                Location_State: {
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                Location_City: {
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                Location_ZipCode: {
                    pattern: /^[0-9\-]+$/
                },
                PhoneNumber: {
                    <?php if($is_regex === 1){ ?>
                    pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/
                    <?php } else { ?>
                    pattern: /^[0-9\- ]+$/
                    <?php } ?>
                },
                YouTubeVideo: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            },
            messages: {
                first_name: {
                    required: 'First name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                last_name: {
                    required: 'Last Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                email: {
                    required: 'Please provide Valid email'
                }, Location_City: {
                    pattern: 'Letters, numbers, and dashes only please',
                },
                Location_Address: {
                    pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                },
                Location_ZipCode: {
                    pattern: 'Numeric values only'
                },
                PhoneNumber: {
                    <?php if($is_regex === 1){ ?>
                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                    <?php } else{ ?>
                    pattern: 'numbers and dashes only please'
                    <?php } ?>
                },
                YouTubeVideo: {
                    pattern: 'Please Enter a Valid Youtube Video Url.'
                }
            },
            errorPlacement: function(e, el){
                <?php if($is_regex === 1){ ?>
                if($(el)[0].id == 'PhoneNumber') $(el).parent().after(e);
                else $(el).after(e);
                <?php } else{ ?>
                $(el).after(e);
                <?php } ?>
            },
            submitHandler: function (form) {
                var is_error = false;
                <?php if($is_regex === 1){ ?>
                //
                // Check for secondary number
                if(_spn.val() != '' && _spn.val().trim() != '(___) ___-____' && !fpn(_spn.val(), '', true)){
                    alertify.alert('Invalid secondary phone number provided.', function(){ return; });
                    is_error = true;
                    return;
                }
                // Check for other number
                // if(_opn.val() != '' && _opn.val().trim() != '(___) ___-____' && !fpn(_opn.val(), '', true)){
                //     alertify.alert('Invalid other phone number provided.', function(){ return; });
                //     is_error = true;
                //     return;
                // }
                <?php } ?>

                if (is_error === false) {
                    <?php if($is_regex === 1){ ?>
                    // Remove and set phone extension
                    $('#js-phonenumber').remove();
                    // $('#js-secondary-phonenumber').remove();
                    // $('#js-other-phonenumber').remove();
                    // Check the fields
                    if(_spn.val().trim() == '(___) ___-____') _spn.val('');
                    else $("#edit_employer").append('<input type="hidden" id="js-secondary-phonenumber" name="txt_secondary_phonenumber" value="+1'+(_spn.val().replace(/\D/g, ''))+'" />');

                    // if(_opn.val().trim() == '(___) ___-____') _opn.val('');
                    // else $("#edit_employer").append('<input type="hidden" id="js-other-phonenumber" name="txt_other_phonenumber" value="+1'+(_opn.val().replace(/\D/g, ''))+'" />');

                    $("#edit_employer").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+(_pn.val().replace(/\D/g, ''))+'" />');
                    <?php } ?>
                    
                    if($('input[name="video_source"]:checked').val() != 'no_video'){
                        $('#my_loader').show();
                    }
                }
                //
                var flag = 0;
                if($('input[name="video_source"]:checked').val() != 'no_video'){
                    if($('input[name="video_source"]:checked').val() == 'youtube'){
                        
                        if($('#yt_vm_video_url').val() != '') { 

                            var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                            if (!$('#yt_vm_video_url').val().match(p)) {
                                alertify.error('Not a Valid Youtube URL');
                                flag = 0;
                                $('#my_loader').hide();
                                return false;
                            } else {
                                flag = 1;
                            }
                        } else {
                            flag = 0;
                            alertify.error('Please add valid youtube video link.');
                            $('#my_loader').hide();
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
                                        $('#my_loader').hide();
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
                            $('#my_loader').hide();
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
                                $('#my_loader').hide();
                                return false;    
                            } else {
                                flag = 1;
                            }
                        }
                    }
                    if(flag == 1){
                        $('#my_loader').show();
                        form.submit(); 
                    } else {
                        $('#my_loader').hide();
                        return false;
                    }
                }else{
                    $('#my_loader').show();
                    form.submit();
                }
            }
        });

        //var form = $( "#edit_employer" );
        //alert( "Valid: " + form.valid() );
    }

    function check_file_all(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
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

    $(document).ready(function () {
        var myid = $('#state_id').html();

        if (myid) {
            //console.log('I am IN');
            //console.log('My ID: '+myid);
            
            setTimeout(function () {
                $("#country").change();
            }, 1000);
            
            setTimeout(function () {
                //console.log('I am in');
                $('#state').val(myid);
            }, 2000);
        }

        $('#HorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function() {}
        });

        var pre_selected = '<?php echo !empty($employer['YouTubeVideo']) ? $employer['video_type'] : ''; ?>';
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
    
    function getStates(val, states) {
        //console.log('VAL: '+val);
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }

    function modify_note(val) {
        var edit_note_text = document.getElementById(val).innerHTML;
        document.getElementById("sid").value = val;
        CKEDITOR.instances.my_edit_notes.setData(edit_note_text);
        $('#edit_notes').show();
        $('#show_hide').hide();
    }

    function cancel_notes() {
        $('#show_hide').show();
        $('#edit_notes').hide();
    }

    function cancel_event() {
        $('.event_detail').fadeIn();
        $('.event_create').hide();
    }

    function delete_note(id) {
        url = "<?= base_url() ?>applicant_profile/delete_note";
        alertify.confirm('Confirmation', "Are you sure you want to delete this Note?",
            function () {
                $.post(url, {sid: id})
                    .done(function (data) {
                        location.reload();
                    });
            },
            function () {
                alertify.error('Canceled');
            });
    }

    function validate_form() {
        $("#event_form").validate({
            ignore: [],
            rules: {
                interviewer: {
                    required: true,
                }
            },
            messages: {
                interviewer: {
                    required: 'Please select an interviewer',
                }
            },
            submitHandler: function (form) {
                $('#my_loader').show(); 
                form.submit();
            }
        });
    }
    $('.interviewer_comment').click(function () {
        if ($('.interviewer_comment').is(":checked")) {
            $('.comment-div').fadeIn();
            $('#interviewerComment').prop('required', true);

        } else {
            $('.comment-div').hide();
            $('#interviewerComment').prop('required', false);
        }
    });

    $('#candidate_msg').click(function () {
        if ($('#candidate_msg').is(":checked")) {
            $('.message-div').fadeIn();
            $('#applicantMessage').prop('required', true);
        } else {
            $('.message-div').hide();
            $('#applicantMessage').prop('required', false);
        }
    });

    $('.goto_meeting').click(function () {
        if ($('.goto_meeting').is(":checked")) {
            $('.meeting-div').fadeIn();
            $('#meetingId').prop('required', true);
            $('#meetingCallNumber').prop('required', true);
            $('#meetingURL').prop('required', true);
        } else {
            $('.meeting-div').hide();
            $('#meetingId').prop('required', false);
            $('#meetingCallNumber').prop('required', false);
            $('#meetingURL').prop('required', false);
        }
    });
    
    $('#edit_button').click(function (event) {
        event.preventDefault();
        $('.info_edit').fadeIn();
        $('.info_view').hide();
    });

    $('.view_button').click(function (event) {
        event.preventDefault();
        $('.info_edit').hide();
        $('.info_view').fadeIn();
    });

    $('#add_notes').click(function (event) {
        event.preventDefault();
        $('.note_div').fadeIn();
        $('.no_note').hide();
    });

    $('.contact_id').select2({
        placeholder: "Select participant(s)",
        allowClear: true
    });
    
    $('.select2-dropdown').css('z-index', '99999999999999999999999');

    $('.eventendtime').datetimepicker({
        datepicker: false,
        format: 'g:iA',
        formatTime: 'g:iA',
        step: 15,
        onShow: function (ct) {
            time = $('.eventstarttime').val();
            timeAr = time.split(":");
            last = parseInt(timeAr[1].substr(0, 2)) + 15;
            if (last == 0)
                last = "00";
            mm = timeAr[1].substr(2, 2);
            timeFinal = timeAr[0] + ":" + last + mm;
            this.setOptions({
                    minTime: $('.eventstarttime').val() ? timeFinal : false
                }
            )
        }
    });
    
    $('.eventstarttime').datetimepicker({
        datepicker: false,
        format: 'g:iA',
        formatTime: 'g:iA',
        step: 15,
        onShow: function (ct) {
            this.setOptions({
                    maxTime: $('.eventendtime').val() ? $('.eventendtime').val() : false
                }
            )
        }
    });
    
    $('.eventdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();
    $('#eventdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();
    $("#eventdate").datepicker("setDate", new Date());
    $('#add_event').click(function () {
        $('.event_create').fadeIn();
        $('.event_detail').hide();
    });

    $('#cancel_note').click(function (event) {
        event.preventDefault();
        $('.note_div').hide();
        $('#add_notes').fadeIn();
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
    <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
    // Added on: 26-06-2019
    $('.js-timezone').select2();
    <?php } ?>


    <?php if($is_regex === 1){ ?>
     // Set targets
    var _pn = $("#<?=$field_phone;?>");
    // _spn = $("#<?=$field_sphone;?>"),
    // _opn = $("#<?=$field_ophone;?>");

    // Reset phone number on load
    // Added on: 05-07-2019
    var val = fpn(_pn.val());
    if(typeof(val) === 'object'){
        _pn.val(val.number);
        setCaretPosition(_pn, val.cur);
    } else _pn.val(val);
    // Reset phone number on load
    _pn.keyup(function(){
        var val = fpn($(this).val());
        if(typeof(val) === 'object'){
            $(this).val(val.number);
            setCaretPosition(this, val.cur);
        } else $(this).val(val);
    });

    // var val2 = fpn(_spn.val());
    // if(typeof(val2) === 'object'){
    //     _spn.val(val2.number);
    //     setCaretPosition(_spn, val2.cur);
    // } else _spn.val(val2);
    // // Reset phone number on load
    // _spn.keyup(function(){
    //     var val = fpn($(this).val());
    //     if(typeof(val) === 'object'){
    //         $(this).val(val.number);
    //         setCaretPosition(this, val.cur);
    //     } else $(this).val(val);
    // });

    // var val3 = fpn(_opn.val());
    // if(typeof(val3) === 'object'){
    //     _opn.val(val3.number);
    //     setCaretPosition(_opn, val3.cur);
    // } else _opn.val(val3);
    // // Reset phone number on load
    // _opn.keyup(function(){
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
</script>
<style>
    .select2-selection__choice{ height: auto !important; }
    .select2-selection__rendered{ height: 40px !important; }
</style>

<?php //} else if ($load_view == 'new') { ?>
<?php } else {?>
    <?php $this->load->view('onboarding/general_information'); ?>
<?php } ?>

