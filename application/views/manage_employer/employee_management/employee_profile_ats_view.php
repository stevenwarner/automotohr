<?php
    $field_phone = 'PhoneNumber';
    $field_sphone = 'secondary_PhoneNumber';
    $field_ophone = 'other_PhoneNumber';
    // Replace '+1' with ''
    // if(isset($employer[$field_phone]) && $employer[$field_phone] != ''){
        // $employer[$field_phone] = str_replace('+1', '', $employer[$field_phone]);
    // }
    // if(isset($employer[$field_sphone]) && $employer[$field_sphone] != ''){
    //     $employer[$field_sphone] = str_replace('+1', '', $employer[$field_sphone]);
    // }
    // if(isset($employer[$field_ophone]) && $employer[$field_ophone] != ''){
    //     $employer[$field_ophone] = str_replace('+1', '', $employer[$field_ophone]);
    // }

    //
    $is_regex = 0;
    $input_group_start = $input_group_end = '';
    $primary_phone_number_cc = $primary_phone_number = $employer[$field_phone];
    if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
        $is_regex = 1;
        $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
        $input_group_end   = '</div>';
        $primary_phone_number = phonenumber_format($employer[$field_phone], true);
        $primary_phone_number_cc = phonenumber_format($employer[$field_phone]);
    }else{
        if($primary_phone_number === '+1') $primary_phone_number = '';
        if($primary_phone_number_cc === '+1') $primary_phone_number_cc = 'N/A';
    }

    if(isset($employer["dob"]) && $employer["dob"] != '' && $employer["dob"] != '0000-00-00') $dob = DateTime::createFromFormat('Y-m-d', $employer['dob'])->format('m-d-Y');
    else $dob = '';
    //
    if($_ssv){
        //
        $employer['ssn'] = ssvReplace($employer["ssn"]);
        //
        if($dob != '') $dob = DateTime::createFromFormat('m-d-Y', $dob)->format('M d XXXX, D');
    } else{
        if($dob != '') $dob = DateTime::createFromFormat('m-d-Y', $dob)->format('M d Y, D');
    }
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <script type="text/javascript">
                    $(document).ready(function() {
                        $(".tab_content").hide();
                        $(".tab_content:first").show();
                        $("ul.tabs li").click(function() {
                            $("ul.tabs li").removeClass("active");
                            $(this).addClass("active");
                            $(".tab_content").hide();
                            var activeTab = $(this).attr("rel");
                            $("#" + activeTab).fadeIn();
                        });
                    });
                    </script>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top');?>
                    <div id="HorizontalTab" class="HorizontalTab">
                        <?php if(!$this->session->userdata('logged_in')['employer_detail']['pay_plan_flag']){  ?>
                        <ul class="resp-tabs-list hor_1">
                            <li id="tab1_nav"><a href="javascript:;">Personal Info</a></li>
                            <li id="tab2_nav"><a href="javascript:;">Questionnaire</a></li>
                            <li id="tab3_nav"><a href="javascript:;">Notes</a></li>
                            <li id="tab4_nav"><a href="javascript:;">Messages</a></li>
                            <li id="tab5_nav"><a href="javascript:;">reviews</a></li>
                            <li id="tab6_nav"><a href="javascript:;">Calendar</a></li>
                            <?php if($phone_sid != '') { ?>
                            <li id="tab7_nav"><a href="javascript:void(0);">SMS <span class="js-sms-count"></span></a>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                        <div class="resp-tabs-container hor_1">
                            <div id="tab1" class="tabs-content">
                                <div class="universal-form-style-v2 info_view" <?php if ($edit_form) { ?>
                                    style="display: none;" <?php } ?>>
                                    <ul>
                                        <div class="form-title-section">
                                            <h2>Personal Information</h2>
                                            <?php if(!$this->session->userdata('logged_in')['employer_detail']['pay_plan_flag']){  ?>
                                            <div class="form-btns">
                                                <input type="submit" value="edit" id="edit_button"
                                                    <?php if($employer['is_executive_admin']){echo 'class="disabled-btn"';}?>>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <li class="col-sm-6">
                                            <label>first name:</label>
                                            <p><?php echo $employer["first_name"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Middle name / initial:</label>
                                            <p><?php echo $employer["middle_name"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>last name:</label>
                                            <p><?php echo $employer["last_name"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>email:</label>
                                            <p><?php echo $employer["email"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>mobile number:</label>
                                            <p><?=$primary_phone_number_cc;?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>address:</label>
                                            <p><?php echo $employer["Location_Address"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>city:</label>
                                            <p><?php echo $employer["Location_City"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>zipcode:</label>
                                            <p> <?php echo $employer["Location_ZipCode"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>state:</label>
                                            <p> <?php echo $employer["state_name"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>country:</label>
                                            <p> <?php echo $employer["country_name"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Job Title:</label>
                                            <p> <?php echo $employer["job_title"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Secondary Email:</label>
                                            <?php if(isset($extra_info["secondary_email"])) { ?>
                                            <p><?php echo $extra_info["secondary_email"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Secondary Mobile Number:</label>
                                            <?php if(isset($extra_info["secondary_PhoneNumber"])) { ?>
                                            <p><?=$extra_info["secondary_PhoneNumber"];?></p>
                                            <?php } else { ?>
                                            <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Other Email:</label>
                                            <?php if(isset($extra_info["other_email"])) { ?>
                                            <p><?php echo $extra_info["other_email"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Telephone Number:</label>
                                            <?php if(isset($extra_info["other_PhoneNumber"])) { ?>
                                            <p><?=$extra_info["other_PhoneNumber"];?></p>
                                            <?php } else{ ?>
                                            <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Linkedin public Profile URL</label>
                                            <?php if (isset($employer["linkedin_profile_url"])) { ?>
                                            <p><a href="<?php echo $employer["linkedin_profile_url"]; ?>"
                                                    target="_blank"><?php echo $employer["linkedin_profile_url"]; ?></a>
                                            </p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Employee Number:</label>
                                            <p> <?=$employer["employee_number"];?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Social Security Number:</label>
                                            <p> <?php echo $employer["ssn"]; ?></p>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Date of Birth:</label>
                                            <p><?php
                                            if(!isset($employer["dob"]) || $employer["dob"] == '' || $employer["dob"] == '0000-00-00') echo 'N/A';
                                            else echo $dob;

                                            ?></p>
                                        </li>

                                        <!-- <li class="col-sm-6">-->
                                        <!-- <label>Title:</label>-->
                                        <!-- --><?php //if(isset($extra_info["title"])) { ?>
                                        <!--<p>--><?php //echo $extra_info["title"]; ?>
                                        <!--</p>-->
                                        <!----><?php //} ?>
                                        <!--</li>-->

                                        <li class="col-sm-6">
                                            <label>Department:</label>
                                            <?php if(isset($department_name)) { ?>
                                            <p><?php echo $department_name; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6">
                                            <label>Team:</label>
                                            <?php if(isset($team_names) && !empty($team_names)) { ?>
                                            <p><?php echo $team_names; ?></p>
                                            <?php } else if(isset($team_name)) { ?>
                                            <p><?php echo $team_name; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-12">
                                            <label>Office Location:</label>
                                            <?php if(isset($extra_info["office_location"])) { ?>
                                            <p><?php echo $extra_info["office_location"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <!--  -->
                                        <li class="col-sm-6 auto-height text-justify">
                                            <label>Joining Date:</label>
                                            <?php if(isset($employer["joined_at"])) { ?>
                                            <p><?=$employer["joined_at"] == '' ? 'N/A' : date_with_time($employer["joined_at"]);?>
                                            </p>
                                            <?php } else { ?>
                                            <p><?=$employer["registration_date"] == '' ? 'N/A' : date_with_time($employer["registration_date"]);?>
                                            </p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6 auto-height text-justif">
                                            <label>Shift time:</label>
                                            <p><?php echo $employer["shift_start_time"] != '' ? $employer["shift_start_time"] : SHIFT_START ;?>
                                                -
                                                <?php echo $employer["shift_end_time"] != '' ? $employer["shift_end_time"] : SHIFT_END ;?>
                                            </p>
                                        </li>
                                        <li class="col-sm-6 auto-height text-justify">
                                            <label>Shift Duration:</label>
                                            <?php if(isset($employer["user_shift_hours"])) { ?>
                                            <p><?php echo $employer["user_shift_hours"] .( $employer['user_shift_hours'] == 1 ? ' hour' : ' hours' ).' & '.$employer["user_shift_minutes"] .( $employer['user_shift_minutes'] == 1 ? ' minute' : ' minutes' ); ?>
                                            </p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6 auto-height text-justify">
                                            <label>Break Duration:</label>
                                            <?php if(isset($employer["user_shift_hours"])) { ?>
                                            <p><?php echo $employer["break_hours"] .( $employer['break_hours'] == 1 ? ' hour' : ' hours' ).' & '.$employer["break_mins"] .( $employer['break_mins'] == 1 ? ' minute' : ' minutes' ); ?>
                                            </p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6 auto-height text-justify">
                                            <label>Week Day Offs:</label>
                                            <?php if(isset($employer["offdays"])) { ?>
                                            <p><?php echo str_replace(",", ", ", $employer["offdays"]); ?></p>
                                            <?php } ?>
                                        </li>
                                        <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                                        <!-- TimeZone -->
                                        <li class="col-sm-6 auto-height text-justify">
                                            <label>TimeZone:</label>
                                            <p><?=($employer["timezone"] == '' || $employer["timezone"] == null) || (!preg_match('/^[A-Z]/', $employer['timezone'])) ? 'N/A' : get_timezones($employer["timezone"], 'name');?>
                                            </p>
                                        </li>
                                        <?php } ?>
                                        <?php if(IS_NOTIFICATION_ENABLED == 1 && $phone_sid != '') { ?>
                                        <li class="col-sm-6 auto-height text-justify">
                                            <label>Notified By:</label>
                                            <?php if(isset($employer["notified_by"])) { ?>
                                            <p><?php echo ucwords($employer["notified_by"]) ; ?></p>
                                            <?php } ?>
                                        </li>
                                        <?php } ?>


                                        <li class="col-sm-6 auto-height text-justify hidden">
                                            <label>Employment Status:</label>
                                            <?php if(isset($employment_statuses[$employer["employee_status"]])) { ?>
                                            <p><?= $employment_statuses[$employer["employee_status"]] ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-6 auto-height text-justify">
                                            <label>Employment Type:</label>
                                            <?php if(isset($employment_types[$employer["employee_type"]])) { ?>
                                            <p><?= $employment_types[$employer["employee_type"]] ?></p>
                                            <?php }else{ ?>
                                            <p><?= $employment_types['fulltime'] ?></p>
                                            <?php } ?>
                                        </li>

                                        <li class="col-sm-12 auto-height text-justify">
                                            <label>Interests:</label>
                                            <?php if(isset($extra_info["interests"])) { ?>
                                            <p><?php echo $extra_info["interests"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="col-sm-12 auto-height text-justify">
                                            <label>Short Bio:</label>
                                            <?php if(isset($extra_info["short_bio"])) { ?>
                                            <p><?php echo $extra_info["short_bio"]; ?></p>
                                            <?php } ?>
                                        </li>

                                        <!-- Time off policies -->
                                        <li class="col-sm-12-left auto-height text-justify">
                                            <label>
                                                Policies:
                                            </label>
                                            <?php 
                                                if(!empty($policies)){
                                                    foreach($policies as $key => $policy){ 
                                                        if(!$policy['Implements']) continue;
                                            ?>
                                            <p
                                                style="<?=$key % 2 === 0 ? "background-color: #eee;" : "";?> padding: 10px;">
                                                <strong>Policy Title:</strong> <?php echo $policy['Title'];?>
                                                <br /><span><strong>Remaining Time:</strong>
                                                    <?=$policy['RemainingTime'];?></span>
                                                <br /><span><strong>Employment Status:</strong>
                                                    <?=ucwords($policy['EmployementStatus']);?></span>
                                                <br /><span><strong>Entitled:</strong>
                                                    <?=$policy['Implements'] ? 'Yes' : 'No';?></span>
                                            </p>
                                            <?php   }
                                                }
                                            ?>
                                        </li>

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
                                                <iframe class="embed-responsive-item"
                                                    src="https://www.youtube.com/embed/<?php echo $employer['YouTubeVideo']; ?>"
                                                    frameborder="0" webkitallowfullscreen mozallowfullscreen
                                                    allowfullscreen></iframe>
                                                <?php } elseif($employer['video_type'] == 'vimeo') { ?>
                                                <iframe class="embed-responsive-item"
                                                    src="https://player.vimeo.com/video/<?php echo $employer['YouTubeVideo']; ?>"
                                                    frameborder="0" webkitallowfullscreen mozallowfullscreen
                                                    allowfullscreen></iframe>
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
                                <div <?php if ($edit_form) { ?>style="display: block;"
                                    <?php } else { ?>style="display: none;" <?php } ?>
                                    class="universal-form-style-v2 info_edit">
                                    <form id="edit_employer" method="POST" enctype="multipart/form-data">
                                        <div class="form-title-section">
                                            <h2>Personal Information</h2>
                                            <div class="form-btns">
                                                <input type="submit" value="Save"
                                                    onclick="return validate_employers_form()">
                                                <input type="button" value="cancel" class="view_button">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 form-group">
                                                <label>First Name:<span class="staric">*</span></label>
                                                <input type="text" class="invoice-fields" name="first_name"
                                                    id="first_name"
                                                    value="<?php if(isset($employer['first_name'])) { echo $employer['first_name']; } ?>">
                                                <?php echo form_error('first_name'); ?>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 form-group">
                                                <label>Middle name / initial:</label>
                                                <input
                                                    class="invoice-fields  <?php if (form_error('middle_name') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('middle_name', $employer["middle_name"]); ?>"
                                                    type="text" name="middle_name">
                                                <?php echo form_error('middle_name'); ?>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 form-group">
                                                <label>last name:<samp class="red"> * </samp></label>
                                                <input
                                                    class="invoice-fields  <?php if (form_error('last_name') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('last_name', $employer["last_name"]); ?>"
                                                    type="text" name="last_name">
                                                <?php echo form_error('last_name'); ?>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>email:
                                                    <!--<samp class="red"> * </samp>-->
                                                </label>
                                                <input
                                                    class="invoice-fields <?php if (form_error('email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('email', $employer["email"]); ?>"
                                                    type="email" name="email">
                                                <?php echo form_error('email'); ?>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Mobile number:</label>
                                                <?=$input_group_start;?>
                                                <input class="invoice-fields" id="PhoneNumber"
                                                    value="<?php echo set_value('PhoneNumber', $primary_phone_number); ?>"
                                                    type="text" name="PhoneNumber">
                                                <?=$input_group_end;?>
                                                <?php echo form_error('PhoneNumber'); ?>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group">
                                                <label>address:</label>
                                                <input class="invoice-fields"
                                                    value="<?php echo set_value('Location_Address', $employer["Location_Address"]); ?>"
                                                    type="text" name="Location_Address">
                                                <?php echo form_error('Location_Address'); ?>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>city:</label>
                                                <input class="invoice-fields"
                                                    value="<?php echo set_value('Location_City', $employer["Location_City"]); ?>"
                                                    type="text" name="Location_City">
                                                <?php echo form_error('Location_City'); ?>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>zipcode:</label>
                                                <input class="invoice-fields"
                                                    value="<?php echo set_value('Location_ZipCode', $employer["Location_ZipCode"]); ?>"
                                                    type="text" name="Location_ZipCode">
                                                <?php echo form_error('Location_ZipCode'); ?>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>country:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="Location_Country" id="country"
                                                        onchange="getStates(this.value, <?php echo $states; ?>)">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                        <option value="<?= $active_country["sid"]; ?>"
                                                            <?php if ($employer['Location_Country'] == $active_country["sid"]) { echo 'selected'; } ?>>
                                                            <?= $active_country["country_name"]; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>state:</label>
                                                <p style="display: none;" id="state_id">
                                                    <?php echo $employer['Location_State']; ?></p>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="Location_State" id="state">
                                                        <option value="">Select State</option>
                                                        <option value="">Please Select your country</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Jobs Title:</label>
                                                <input class="invoice-fields"
                                                    value="<?php echo set_value('job_title', $employer["job_title"]); ?>"
                                                    type="text" name="job_title">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Profile picture:</label>
                                                <div class="upload-file invoice-fields">
                                                    <span class="selected-file" id="name_pictures">No file
                                                        selected</span>
                                                    <input type="file" name="pictures" id="pictures"
                                                        onchange="check_file_all('pictures')">
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>secondary email:</label>
                                                <input
                                                    class="invoice-fields <?php if (form_error('secondary_email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('secondary_email', (isset($extra_info["secondary_email"]) ? $extra_info["secondary_email"] : '')); ?>"
                                                    type="email" name="secondary_email">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>secondary mobile number:</label>
                                                <input class="invoice-fields" id="secondary_PhoneNumber"
                                                    value="<?php echo set_value('secondary_PhoneNumber', isset($extra_info["secondary_PhoneNumber"]) ? $extra_info["secondary_PhoneNumber"] : ''); ?>"
                                                    type="text" name="secondary_PhoneNumber">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>other email:</label>
                                                <input class="invoice-fields"
                                                    value="<?php echo set_value('other_email', isset($extra_info["other_email"]) ? $extra_info["other_email"] : ''); ?>"
                                                    type="email" name="other_email">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>telephone number:</label>
                                                <input class="invoice-fields" id="other_PhoneNumber"
                                                    value="<?php echo isset($extra_info["other_PhoneNumber"]) ? $extra_info["other_PhoneNumber"] : ''; ?>"
                                                    type="text" name="other_PhoneNumber">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Linkedin Public Profile URL:</label>
                                                <input class="invoice-fields"
                                                    value="<?php echo set_value('linkedin_profile_url', $employer["linkedin_profile_url"]); ?>"
                                                    type="text" name="linkedin_profile_url">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Employee Number:</label>
                                                <input class="invoice-fields"
                                                    value="<?php echo set_value('employee_number', $employer["employee_number"]); ?>"
                                                    type="text" name="employee_number">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Social Security Number:</label>
                                                <input class="invoice-fields" type="text" name="SSN"
                                                    value="<?php echo isset($employer["ssn"]) ? $employer["ssn"] : ''; ?>">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Date of Birth:</label>
                                                <input class="invoice-fields" id="date_of_birth" readonly="" type="text"
                                                    name="DOB" value="<?php echo $dob != '' ?  $dob : ''; ?>">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Department:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="department" id="department"
                                                        onchange="get_teams(this.value)">
                                                        <option value="0">Select Department</option>
                                                        <?php if (!empty($departments)) { ?>
                                                        <?php foreach ($departments as $department) { ?>
                                                        <option value="<?php echo $department["sid"]; ?>"
                                                            <?php if ($employer['department_sid'] == $department["sid"]) { echo 'selected'; } ?>>
                                                            <?php echo $department["name"]; ?>
                                                        </option>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                        <option value="0">No Department Found</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Team:</label>
                                                <div class="hr-select-dropdown">
                                                    <p style="display: none;" id="team_sid">
                                                        <?php echo $employer['team_sid']; ?></p>
                                                    <select class="invoice-fields" name="teams[]" id="teams"
                                                        multiple="true">
                                                        <option value="">Select Team</option>
                                                        <option value="">Please Select your Department</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <?php $office_location = isset($extra_info["office_location"]) ? $extra_info["office_location"] : ''; ?>
                                                <label>Office Location:</label>
                                                <input class="invoice-fields"
                                                    value="<?php echo set_value('office_location', $office_location); ?>"
                                                    type="text" name="office_location" id="office_location">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <?php if($access_level_plus == 1) {?>
                                                <!-- Joining date -->
                                                <?php $joining_date = isset($employer["joined_at"]) && $employer["joined_at"] != '' ? DateTime::createFromFormat('Y-m-d', $employer["joined_at"])->format('m-d-Y') : ''; ?>
                                                <?php $registration_date = isset($employer["registration_date"]) && $employer["registration_date"] != '' ? DateTime::createFromFormat('Y-m-d H:i:s', $employer["registration_date"])->format('m-d-Y') : ''; ?>
                                                <label>Joining Date:</label>
                                                <input class="invoice-fields js-joining-date" readonly="true"
                                                    value="<?php echo $employer["joined_at"]=='' ? $registration_date : $joining_date ?>"
                                                    type="text" name="joining_date" id="joining_date">
                                                <?php } ?>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <?php if(IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                                                <!-- Timezone -->
                                                <?php if($employer_sid == $employer["sid"] || $access_level_plus == 1) { ?>
                                                <?php $timezone = isset($employer["timezone"]) ? $employer["timezone"] : ''; ?>
                                                <label>TimeZone:</label>
                                                <?=timezone_dropdown(
                                                            $timezone,
                                                            array(
                                                                'class' => 'invoice-fields js-timezone',
                                                                'name' => 'timezone',
                                                                'id' => 'timezone'
                                                            )
                                                        );?>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group dn">
                                                <label>Shift</label>
                                                <div class="row">
                                                    <div class="col-sm-6 shift_div" style="padding-left: 0px;">
                                                        <div class="input-group">
                                                            <input min="0" max="23"
                                                                oninput="this.value = Math.abs(this.value)"
                                                                id="sh_hours" type="number"
                                                                value="<?php echo  $employer["user_shift_hours"] ?>"
                                                                name="shift_hours" class="invoice-fields">
                                                            <div class="input-group-addon"> Hours </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 shift_div shift_end"
                                                        style="padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input min="0" max="59"
                                                                oninput="this.value = Math.abs(this.value)"
                                                                type="number"
                                                                value="<?php echo  $employer["user_shift_minutes"] ?>"
                                                                id="sh_mins" name="shift_mins" class="invoice-fields">
                                                            <div class="input-group-addon">Minutes</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if(IS_NOTIFICATION_ENABLED == 1 && $phone_sid != '') { ?>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Notified By:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="notified_by[]" id="notified_by"
                                                        multiple="true">
                                                        <option value="email"
                                                            <?php if(in_array('email', explode(',', $employer['notified_by']))){echo 'selected' ;}?>>
                                                            Email</option>
                                                        <option value="sms"
                                                            <?php if(in_array('sms', explode(',', $employer['notified_by']))){echo 'selected' ;}?>>
                                                            SMS</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <?php } ?>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group dn">
                                                <label>Employment Status:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="employee-status"
                                                        id="employee-status">
                                                        <?php foreach($employment_statuses as $key => $employee_status) { ?>
                                                        <option value="<?= $key ?>"
                                                            <?php if(strtolower($employer['employee_status']) == $key){echo 'selected' ;}?>>
                                                            <?= $employee_status ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Employment Type:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="employee-type"
                                                        id="employee-type">
                                                        <?php if(!empty($employment_types)) { ?>
                                                        <?php foreach($employment_types as $key => $employment_type) { ?>
                                                        <option value="<?= $key ?>"
                                                            <?php if(strtolower($employer['employee_type']) == $key){echo 'selected' ;}?>>
                                                            <?= $employment_type ?></option>
                                                        <?php } ?>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <?php 
                                                    $shift_start = isset($employer['shift_start_time']) && !empty($employer['shift_start_time']) ? $employer['shift_start_time'] : SHIFT_START;
                                                    $shift_end = isset($employer['shift_end_time']) && !empty($employer['shift_end_time']) ? $employer['shift_end_time'] : SHIFT_END;
                                                ?>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Shift start time:</label>
                                                        <input
                                                            class="invoice-fields js-shift-start-time show_employee_working_info"
                                                            readonly="true" value="<?php echo $shift_start; ?>"
                                                            type="text" name="shift_start_time">
                                                    </div>
                                                    <div class="col-sm-6" style="padding-right: 0px;">
                                                        <label>Shift End time:</label>
                                                        <input
                                                            class="invoice-fields js-shift-end-time show_employee_working_info"
                                                            readonly="true" value="<?php echo $shift_end; ?>"
                                                            type="text" name="shift_end_time">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Break</label>
                                                <?php 
                                                    $break_hours = isset($employer['break_hours']) ? $employer['break_hours'] : BREAK_HOURS;
                                                    $break_minutes = isset($employer['break_mins']) && !empty($employer['break_mins']) ? $employer['break_mins'] : BREAK_MINUTES;
                                                ?>
                                                <div class="row">
                                                    <div class="col-sm-6 shift_div">
                                                        <div class="input-group">
                                                            <input min="0" max="23"
                                                                oninput="this.value = Math.abs(this.value)"
                                                                id="br_hours" type="number"
                                                                value="<?php echo  $break_hours; ?>" name="break_hours"
                                                                class="invoice-fields show_employee_working_info emp_break_info"
                                                                data-type="hours">
                                                            <div class="input-group-addon"> Hours </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 shift_div shift_end">
                                                        <div class="input-group">
                                                            <input min="0" max="59"
                                                                oninput="this.value = Math.abs(this.value)"
                                                                type="number" value="<?php echo  $break_minutes; ?>"
                                                                id="br_mins" name="break_mins"
                                                                class="invoice-fields show_employee_working_info emp_break_info"
                                                                data-type="minutes">
                                                            <div class="input-group-addon">Minutes</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <label>Week Days Off:</label>
                                                <?php $dayoffs = isset($employer['offdays']) && !empty($employer['offdays']) ? explode(',', $employer['offdays']) : [DAY_OFF]; ?>
                                                <select class="show_employee_working_info" name="offdays[]"
                                                    id="js_offdays" multiple="true">
                                                    <option value="Monday"
                                                        <?php echo in_array("Monday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                        Monday</option>
                                                    <option value="Tuesday"
                                                        <?php echo in_array("Tuesday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                        Tuesday</option>
                                                    <option value="Wednesday"
                                                        <?php echo in_array("Wednesday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                        Wednesday</option>
                                                    <option value="Thursday"
                                                        <?php echo in_array("Thursday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                        Thursday</option>
                                                    <option value="Friday"
                                                        <?php echo in_array("Friday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                        Friday</option>
                                                    <option value="Saturday"
                                                        <?php echo in_array("Saturday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                        Saturday</option>
                                                    <option value="Sunday"
                                                        <?php echo in_array("Sunday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                        Sunday</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                <div class="update_employee_info_container">
                                                    <strong id="update_employee_info"></strong>
                                                    <input type="hidden" name="weekly_hours" id="employee_weekly_hours">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group">
                                                <!-- Time off policies -->
                                                <label>
                                                    Policies:
                                                </label>
                                                <select name="policies[]" id="js-policies" multiple="true">
                                                    <?php 
                                                    if(!empty($policies)){
                                                        foreach($policies as $key => $policy){
                                                ?>
                                                    <option <?php if($policy['Implements']) echo 'selected="true"'; ?>
                                                        value="<?=$policy['PolicyId'];?>">
                                                        <?php
                                                        echo $policy['Title'], ' (', $policy['RemainingTime'], ') [',ucwords($policy['EmployementStatus']), ']';
                                                    ?>
                                                    </option>
                                                    <?php   }
                                                    }
                                                    ?>
                                                </select>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 auto-height form-group">
                                                <?php $interests = isset($extra_info["interests"]) ? $extra_info["interests"] : ''; ?>
                                                <label>Interests:</label>
                                                <textarea id="interests" name="interests"
                                                    class="invoice-fields auto-height"
                                                    rows="6"><?php echo set_value('interests', $interests); ?></textarea>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 auto-height form-group">
                                                <?php $short_bio = isset($extra_info["short_bio"]) ? $extra_info["short_bio"] : ''; ?>
                                                <label>Short Bio:</label>
                                                <textarea id="short_bio" id="short_bio" name="short_bio"
                                                    class="invoice-fields auto-height"
                                                    rows="6"><?php echo set_value('short_bio', $short_bio); ?></textarea>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 auto-height form-group">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label for="YouTubeVideo">Select Video:</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label
                                                                            class="control control--radio"><?php echo NO_VIDEO; ?>
                                                                            <input type="radio" name="video_source"
                                                                                class="video_source" value="no_video"
                                                                                checked="">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label
                                                                            class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                                                            <input type="radio" name="video_source"
                                                                                class="video_source" value="youtube"
                                                                                <?php echo !empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'youtube' ? 'checked="checked"':''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label
                                                                            class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                                                            <input type="radio" name="video_source"
                                                                                class="video_source" value="vimeo"
                                                                                <?php echo !empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'vimeo' ? 'checked="checked"':''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label
                                                                            class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                                                            <input type="radio" name="video_source"
                                                                                class="video_source" value="uploaded"
                                                                                <?php echo !empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'uploaded' ? 'checked="checked"':''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight"
                                                    id="youtube_vimeo_input">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?php
                                                                    if (!empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'youtube') {
                                                                        $video_link = 'https://www.youtube.com/watch?v='.$employer['YouTubeVideo'];
                                                                    } else if (!empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'vimeo') {
                                                                        $video_link = 'https://vimeo.com/'.$employer['YouTubeVideo'];
                                                                    } else {
                                                                        $video_link = '';
                                                                    }
                                                                ?>
                                                                <label for="YouTube_Video" id="label_youtube">Youtube
                                                                    Video:</label>
                                                                <label for="Vimeo_Video" id="label_vimeo"
                                                                    style="display: none">Vimeo Video:</label>
                                                                <input type="text" name="yt_vm_video_url"
                                                                    value="<?php echo $video_link; ?>"
                                                                    class="invoice-fields" id="yt_vm_video_url">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight"
                                                    id="upload_input" style="display: none">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <label for="YouTubeVideo">Upload Video:</label>
                                                                <div class="upload-file invoice-fields">
                                                                    <?php
                                                                        if (!empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL && $employer['video_type'] == 'uploaded') {
                                                                    ?>
                                                                    <input type="hidden" id="pre_upload_video_url"
                                                                        name="pre_upload_video_url"
                                                                        value="<?php echo $employer['YouTubeVideo']; ?>">
                                                                    <?php
                                                                        } else {
                                                                    ?>
                                                                    <input type="hidden" id="pre_upload_video_url"
                                                                        name="pre_upload_video_url" value="">
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                    <span class="selected-file"
                                                                        id="name_upload_video">No video selected</span>
                                                                    <input name="upload_video" id="upload_video"
                                                                        onchange="upload_video_checker('upload_video')"
                                                                        type="file">
                                                                    <a href="javascript:;">Choose Video</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if(!empty($employer['YouTubeVideo']) && $employer['YouTubeVideo'] != NULL) {
                                                    if($employer['video_type'] == 'uploaded'){
                                                        $fileExt = $employer['YouTubeVideo'];
                                                        $fileExt = strtolower(pathinfo($fileExt, PATHINFO_EXTENSION));
                                                    }?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div
                                                        class="<?= !empty($fileExt) && $fileExt != 'mp3' ? 'well well-sm' : '';?>">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <?php if($employer['video_type'] == 'youtube') { ?>
                                                            <iframe class="embed-responsive-item"
                                                                src="https://www.youtube.com/embed/<?php echo $employer['YouTubeVideo']; ?>"
                                                                frameborder="0" webkitallowfullscreen mozallowfullscreen
                                                                allowfullscreen></iframe>
                                                            <?php } elseif($employer['video_type'] == 'vimeo') { ?>
                                                            <iframe class="embed-responsive-item"
                                                                src="https://player.vimeo.com/video/<?php echo $employer['YouTubeVideo']; ?>"
                                                                frameborder="0" webkitallowfullscreen mozallowfullscreen
                                                                allowfullscreen></iframe>
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
                                            <div class="form-title-section">
                                                <div class="form-btns">
                                                    <input type="hidden" name="old_profile_picture"
                                                        value="<?php echo $employer['profile_picture']; ?>">
                                                    <input type="hidden" name="id"
                                                        value="<?php echo $employer['sid']; ?>">
                                                    <input type="submit" value="Save" id="add_edit_submit"
                                                        onclick="return validate_employers_form()">
                                                    <input type="button" value="cancel" class="view_button">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--Edit part Ends-->
                            </div>
                            <?php if(!$this->session->userdata('logged_in')['employer_detail']['pay_plan_flag']){  ?>
                            <!-- #tab1 -->
                            <div id="tab2" class="tabs-content">
                                <script type="text/javascript">
                                $(document).ready(function() {

                                    $('.collapse').on('shown.bs.collapse', function() {
                                        $(this).parent().find(".glyphicon-plus").removeClass(
                                            "glyphicon-plus").addClass("glyphicon-minus");
                                    }).on('hidden.bs.collapse', function() {
                                        $(this).parent().find(".glyphicon-minus").removeClass(
                                            "glyphicon-minus").addClass("glyphicon-plus");
                                    });
                                });
                                </script>
                                <!--                                Previous removed From Here -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <!-- Start -->
                                        <?php       if (sizeof($applicant_jobs) > 0) {
                                            $item = 0;
                                            $counter = 0;?>
                                        <div class="tab-header-sec">
                                            <h2 class="tab-title">Screening Questionnaire <button
                                                    class="btn btn-success pull-right hidden-print"
                                                    onclick="window.print()">Print</button></h2>
                                            <!-- next working area-->
                                        </div>
                                        <?php foreach ($applicant_jobs as $job) { ?>
                                        <?php if($job['job_title'] != NULL && $job['job_title'] != '') { ?>
                                        <p class="questionnaire-heading margin-top">Job:
                                            <?php echo $job['job_title']; ?></p>
                                        <?php } ?>
                                        <?php   if ($job['questionnaire'] != NULL && $job['questionnaire'] != '') {
                                                    $my_questionnaire = unserialize($job['questionnaire']);

                                                    if(isset($my_questionnaire['applicant_sid'])) {
                                                        $questionnaire_type = 'new';
                                                        $questionnaire_name = $my_questionnaire['questionnaire_name']; ?>
                                        <p class="questionnaire-heading margin-top" style="background-color: #466b1d;">
                                            <?php echo $questionnaire_name; ?></p>
                                        <div class="tab-btn-panel">
                                            <span>Score: <?php echo $job['score'] ?></span><a
                                                <?php if($job['questionnaire_result'] == 'Fail'){ echo 'style="background-color:#FF0000;"';}?>
                                                href="javascript:;"><?php echo $job['questionnaire_result']; ?></a>
                                        </div>
                                        <div class="questionnaire-body">
                                            <?php   $questionnaire = $my_questionnaire['questionnaire'];
                                                            foreach ($questionnaire as $key => $questionnaire_answers) {
                                                                $answer = $questionnaire_answers['answer'];
                                                                $passing_score = $questionnaire_answers['passing_score'];
                                                                $score = $questionnaire_answers['score'];
                                                                $status = $questionnaire_answers['status'];
                                                                $item ++; ?>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse"
                                                            data-parent="#accordion"
                                                            href="#collapse_<?php echo $item; ?>">
                                                            <span class="glyphicon glyphicon-minus"></span>
                                                            <?php echo $key; ?>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_<?php echo $item; ?>"
                                                    class="panel-collapse collapse in">
                                                    <?php   if (is_array($answer)) {
                                                                            foreach ($answer as $multiple_answer) { ?>
                                                    <div class="panel-body">
                                                        <?php echo $multiple_answer; ?>
                                                    </div>
                                                    <?php       }
                                                                        } else { ?>
                                                    <div class="panel-body">
                                                        <?php echo $answer; ?>
                                                    </div>
                                                    <?php   } ?>

                                                    <div class="panel-body">
                                                        <b>Score: <?php echo $score; ?> points out of possible
                                                            <?php echo $passing_score; ?></b>
                                                        <span class="<?php echo strtolower($status); ?> pull-right"
                                                            style="font-size: 22px;">(<?php echo $status; ?>)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php       } else {
                                                        $questionnaire_type = 'old'; ?>
                                        <div class="tab-btn-panel">
                                            <span>Score : <?php echo $job['score'] ?></span>
                                            <?php if ($job['passing_score'] <= $job['score']) { ?>
                                            <a href="javascript:;">Pass</a>
                                            <?php } else { ?>
                                            <a href="javascript:;">Fail</a>
                                            <?php } ?>
                                        </div>
                                        <p class="questionnaire-heading margin-top" style="background-color: #466b1d;">
                                            Questions / Answers</p>
                                        <div class="panel-group-wrp">
                                            <div class="panel-group" id="accordion">
                                                <?php
                                                                $questionnaire = unserialize($job['questionnaire']);
                                                                foreach ($questionnaire as $key => $value) {
                                                                    $item ++; ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse"
                                                                data-parent="#accordion"
                                                                href="#collapse_<?php echo $item; ?>">
                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_<?php echo $item; ?>"
                                                        class="panel-collapse collapse in">
                                                        <?php
                                                                            if (is_array($value)) {
                                                                                foreach ($value as $answer) {
                                                                                    ?>
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
                                        <?php     } ?>
                                        <?php } else { ?>
                                        <div class="applicant-notes">
                                            <div class="notes-not-found">No questionnaire found!</div>
                                        </div>
                                        <?php }
                                                $job_manual_questionnaire_history = $job['manual_questionnaire_history'];

                                                if(!empty($job_manual_questionnaire_history)) {
                                                    $job_manual_questionnaire_history_count = count($job_manual_questionnaire_history);

                                                    foreach($job_manual_questionnaire_history as $job_man_key => $job_man_value) {
                                                        $job_manual_questionnaire       = $job_man_value['questionnaire'];
                                                        $job_questionnaire_sent_date    = $job_man_value['questionnaire_sent_date'];
                                                        $job_man_questionnaire_result   = $job_man_value['questionnaire_result'];
                                                        $job_man_score                  = $job_man_value['score'];
                                                        $job_man_passing_score          = $job_man_value['passing_score'];

                                                        echo '<br>Resent on: '.date_with_time($job_questionnaire_sent_date).'<hr style="margin-top: 5px; margin-bottom: 5px;">';

                                                        if($job_manual_questionnaire != '' || $job_manual_questionnaire != NULL) {
                                                            $job_manual_questionnaire_array = unserialize($job_manual_questionnaire);

                                                            if(empty($job_manual_questionnaire_array)) {
                                                                echo '<div class="applicant-notes">
                                                                        <div class="notes-not-found">No questionnaire found!</div>
                                                                    </div>';
                                                            } else {
                                                                /************************************************************/
                                                                if(isset($job_manual_questionnaire_array['applicant_sid'])) {
                                                                    $questionnaire_name = $job_manual_questionnaire_array['questionnaire_name']; ?>
                                        <p class="questionnaire-heading margin-top" style="background-color: #466b1d;">
                                            <?php echo $questionnaire_name; ?></p>
                                        <div class="tab-btn-panel">
                                            <span>Score: <?php echo $job_man_score; ?></span><a
                                                <?php if($job_man_questionnaire_result == 'Fail'){ echo 'style="background-color:#FF0000;"';}?>
                                                href="javascript:;"><?php echo $job_man_questionnaire_result; ?></a>
                                        </div>
                                        <div class="questionnaire-body">
                                            <?php   $questionnaire = $job_manual_questionnaire_array['questionnaire'];

                                                                        foreach ($questionnaire as $key => $questionnaire_answers) {
                                                                            $answer = $questionnaire_answers['answer'];
                                                                            $passing_score = $questionnaire_answers['passing_score'];
                                                                            $score = $questionnaire_answers['score'];
                                                                            $status = $questionnaire_answers['status'];
                                                                            $item ++; ?>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse"
                                                            data-parent="#accordion"
                                                            href="#collapse_<?php echo $item; ?>">
                                                            <span class="glyphicon glyphicon-minus"></span>
                                                            <?php echo $key; ?>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_<?php echo $item; ?>"
                                                    class="panel-collapse collapse in">
                                                    <?php   if (is_array($answer)) {
                                                                                        foreach ($answer as $multiple_answer) { ?>
                                                    <div class="panel-body">
                                                        <?php echo $multiple_answer; ?>
                                                    </div>
                                                    <?php       }
                                                                                    } else { ?>
                                                    <div class="panel-body">
                                                        <?php echo $answer; ?>
                                                    </div>
                                                    <?php   } ?>

                                                    <div class="panel-body">
                                                        <b>Score: <?php echo $score; ?> points out of possible
                                                            <?php echo $passing_score; ?></b>
                                                        <span class="<?php echo strtolower($status); ?> pull-right"
                                                            style="font-size: 22px;">(<?php echo $status; ?>)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php       } else { ?>
                                        <div class="tab-btn-panel">
                                            <span>Score : <?php echo $job_man_score; ?></span>
                                            <?php if ($job_man_passing_score <= $job_man_score) { ?>
                                            <a href="javascript:;">Pass</a>
                                            <?php } else { ?>
                                            <a href="javascript:;">Fail</a>
                                            <?php } ?>
                                        </div>
                                        <p class="questionnaire-heading margin-top" style="background-color: #466b1d;">
                                            Questions / Answers</p>
                                        <div class="panel-group-wrp">
                                            <div class="panel-group" id="accordion">
                                                <?php   $questionnaire = $job_manual_questionnaire_array;

                                                                            foreach ($questionnaire as $key => $value) {
                                                                                $item ++; ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse"
                                                                data-parent="#accordion"
                                                                href="#collapse_<?php echo $item; ?>">
                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_<?php echo $item; ?>"
                                                        class="panel-collapse collapse in">
                                                        <?php
                                                                                        if (is_array($value)) {
                                                                                            foreach ($value as $answer) {
                                                                                                ?>
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
                                        <?php     }
                                                            }
                                                        } else {
                                                            echo '<div class="applicant-notes">
                                                        <div class="notes-not-found">No questionnaire found!</div>
                                                    </div>';
                                                        }
                                                    }
                                                } ?>
                                        <?php } ?>
                                        <?php } else { ?>
                                        <div class="tab-header-sec">
                                            <h2 class="tab-title">Screening Questionnaire</h2>
                                            <div class="applicant-notes">
                                                <div class="notes-not-found">No questionnaire found!</div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <!-- End -->
                                    </div>
                                </div>
                            </div>
                            <!-- #tab2 -->
                            <div id="tab3" class="tabs-content">
                                <div class="universal-form-style-v2" id="show_hide">
                                    <form action="<?php echo base_url('employee_management/insert_notes') ?>"
                                        method="POST" id="note_form">
                                        <input type="hidden" name="action" value="add_note">
                                        <input type="hidden" name="employers_sid"
                                            value="<?php echo $employer["parent_sid"]; ?>">
                                        <input type="hidden" name="applicant_job_sid"
                                            value="<?php echo $employer["sid"]; ?>">
                                        <input type="hidden" name="applicant_email"
                                            value="<?php echo $employer["email"]; ?>">
                                        <div class="form-title-section">
                                            <h2>Employee Notes</h2>
                                            <div class="form-btns">
                                                <input type="submit" style="display: none;" class="note_div"
                                                    value="save">
                                                <input type="button" id="cancel_note" style="display: none;"
                                                    class="note_div" value="cancel">
                                                <input type="submit" class="no_note" id="add_notes" value="Add note">
                                            </div>
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <div class="applicant-notes">
                                            <div class="hr-ck-editor note_div" style="display: none;">
                                                <textarea class="ckeditor" id="notes" name="notes" rows="8"
                                                    cols="60"></textarea>
                                            </div>
                                            <span class="notes-not-found  no_note"
                                                <?php if (empty($employee_notes)) { ?>style="display: block;"
                                                <?php } else { ?> style="display: none;" <?php } ?>>No Notes
                                                Found</span>
                                            <?php foreach ($employee_notes as $note) { ?>
                                            <article class="notes-list" id="notes_<?= $note['sid'] ?>">
                                                <h2>
                                                    <span id="<?= $note['sid'] ?>"><?= $note['notes'] ?></span>
                                                    <p class="postdate">
                                                        <?= reset_datetime(array( 'datetime' => $note['insert_date'], '_this' => $this)); ?>
                                                    </p>
                                                    <div class="edit-notes">
                                                        <a href="javascript:;"
                                                            style="height: 20px; line-height: 0; color: white; font-size: 10px;"
                                                            class="grayButton siteBtn notes-btn"
                                                            onclick="modify_note(<?= $note['sid'] ?>)">View / Edit</a>
                                                        <a href="javascript:;"
                                                            style="height: 20px; line-height: 0; color: white; font-size: 10px;"
                                                            class="siteBtn notes-btn btncancel"
                                                            onclick="delete_note(<?= $note['sid'] ?>)">Delete</a>
                                                    </div>
                                                </h2>
                                            </article>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                                <div class="universal-form-style-v2" style="display: none" id="edit_notes">
                                    <form name="edit_note"
                                        action="<?php echo base_url('employee_management/insert_notes') ?>"
                                        method="POST">
                                        <div class="form-title-section">
                                            <h2>Employee Notes</h2>
                                            <div class="form-btns">
                                                <input type="submit" name="note_submit" value="Update">
                                                <input onclick="cancel_notes()" type="button" name="cancel"
                                                    value="Cancel">
                                            </div>
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <textarea class="ckeditor" name="my_edit_notes" id="my_edit_notes" cols="67"
                                            rows="6"></textarea>
                                        <input type="hidden" name="action" value="edit_note">
                                        <input type="hidden" name="employers_sid"
                                            value="<?php echo $employer["parent_sid"]; ?>">
                                        <input type="hidden" name="applicant_job_sid"
                                            value="<?php echo $employer["sid"]; ?>">
                                        <input type="hidden" name="applicant_email"
                                            value="<?php echo $employer["email"]; ?>">
                                        <input type="hidden" name="sid" id="sid" value="">
                                    </form>
                                </div>
                            </div>

                            <!-- #tab3 -->
                            <div id="tab4" class="tabs-content">
                                <form enctype="multipart/form-data"
                                    action="<?php echo base_url('applicant_profile/applicant_message') ?>"
                                    method="post">
                                    <div class="compose-message">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="col-sm-12 autoheight">
                                                    <label>Email Template</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="template" id="template">
                                                            <option id="" data-name="" data-subject="" data-body=""
                                                                value="">Please Select</option>
                                                            <?php if(!empty($portal_email_templates)) { ?>
                                                            <?php foreach($portal_email_templates as $template) { ?>
                                                            <option id="template_<?php echo $template['sid']; ?>"
                                                                data-name="<?php echo $template['template_name']?>"
                                                                data-subject="<?php echo $template['subject']; ?>"
                                                                data-body="<?php echo htmlentities($template['message_body']); ?>"
                                                                value="<?php echo $template['sid']; ?>">
                                                                <?php echo $template['template_name']; ?></option>
                                                            <?php } ?>
                                                            <?php } else { ?>
                                                            <option id="template_" data-name="" data-subject=""
                                                                data-body="" value="">No Custom Template Defined
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="form-title-section">
                                        <h2>Attachments</h2>
                                    </div>
                                    <?php if(!empty($portal_email_templates)) {
                                        foreach ($portal_email_templates as $template) {?>
                                    <ul id="<?php echo $template['sid']; ?>" class="temp-attachment list-group"
                                        style="display: none; float: left; width: 100%;">
                                        <?php if (sizeof($template['attachments']) > 0){
                                                    foreach ($template['attachments'] as $attachment) { ?>
                                        <li class="list-group-item"><?php echo $attachment['original_file_name'] ?></li>
                                        <?php }
                                                }
                                                else{ ?>
                                        <li class="list-group-item">No Attachments</li>
                                        <?php }?>
                                    </ul>
                                    <?php
                                        }
                                    }?>
                                    <ul class="list-group" id="empty-attachment" style="float: left; width: 100%;">
                                        <li class="list-group-item">No Attachments</li>
                                    </ul>

                                    <div class="form-title-section">
                                        <h2>messages</h2>
                                        <div class="form-btns message">
                                            <div class="btn-inner">
                                                <input type="file" name="message_attachment" class="choose-file-filed">
                                                <a href="" class="select-photo">Add Attachment</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-div">
                                        <div class="comment-box">
                                            <div class="textarea">
                                                <input type="hidden" name="to_id" value="<?php echo $id; ?>">
                                                <input type="hidden" name="from_type" value="employer">
                                                <input type="hidden" name="to_type" value="employer">
                                                <input type="hidden" name="applicant_name"
                                                    value="<?php echo $employer["first_name"]; ?> <?php echo $employer["last_name"]; ?>">
                                                <input type="hidden" name="job_id" value="">
                                                <input type="hidden" name="employee_id" value="<?php echo $id; ?>">
                                                <input type="hidden" name="users_type" value="employee">
                                                <input class="message-subject" id="applicantSubject" required="required"
                                                    name="subject" type="text" placeholder="Enter Subject (required)" />
                                                <textarea style="padding:5px; height:200px; width:100%;"
                                                    class="ckeditor" id="applicantMessage" required="required"
                                                    name="message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comment-btn">
                                        <input type="submit" value="Send Message" />
                                    </div>
                                </form>
                                <div class="respond">
                                    <?php   if (count($applicant_message) > 0) {
                                        foreach ($applicant_message as $message) { ?>
                                    <article <?php if ($message['outbox'] == 1) { ?>class="reply" <?php } ?>
                                        id="delete_message<?php echo $message['id']; ?>">
                                        <figure><img <?php  if (empty($message['profile_picture'])) { ?>
                                                src="<?= base_url() ?>assets/images/attachment-img.png"
                                                <?php           } else { ?>
                                                src="<?php echo AWS_S3_BUCKET_URL . $message['profile_picture'] ?>"
                                                width="48" <?php           } ?>>
                                        </figure>
                                        <div class="text">
                                            <div class="message-header">
                                                <div class="message-title">
                                                    <h2>
                                                        <?php   if (!empty($message['first_name'])) {
                                                                        echo ucfirst($message['first_name']);
                                                                        if (!empty($message['last_name'])) {
                                                                            echo " " . ucfirst($message['last_name']);
                                                                        }
                                                                } else {
                                                                    echo $message['username'];
                                                                } ?>
                                                    </h2>
                                                </div>
                                                <ul class="message-option">
                                                    <li>
                                                        <time><?=reset_datetime(array( 'datetime' => $message['date'], '_this' => $this )); ?></time>
                                                    </li>
                                                    <?php if ($message['outbox'] == 1) { ?>
                                                    <?php // do nothing  ?>
                                                    <?php } ?>
                                                    <?php if ($message['attachment']) { ?>
                                                    <li>
                                                        <a class="action-btn"
                                                            href="<?php echo AWS_S3_BUCKET_URL . $message['attachment']; ?>">
                                                            <i class="fa fa-download"></i>
                                                            <span class="btn-tooltip">Download File</span>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                    <li>
                                                        <a class="action-btn remove"
                                                            onclick="delete_message(<?php echo $message['id']; ?>)"
                                                            href="javascript:;">
                                                            <i class="fa fa-remove"></i>
                                                            <span class="btn-tooltip">Delete</span>
                                                        </a>
                                                    </li>
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
                            </div>
                            <!-- #tab4 -->
                            <div id="tab5" class="tabs-content">
                                <?php $this->load->view('manage_employer/employee_management/review_rating_partial');?>
                            </div>
                            <!-- #tab5 -->
                            <div id="tab6" class="tabs-content">
                                <?php //$this->load->view('manage_employer/employee_management/calendar_events_partial'); ?>
                                <?php $this->load->view('manage_employer/employee_management/'.( $is_new_calendar ? 'calendar_events_partial_ajax' : 'calendar_events_partial' ).''); ?>
                            </div>
                            <!-- #tab6 -->
                            <?php if($phone_sid != '') { ?>
                            <div id="tab7" class="tabs-content">
                                <?php $this->load->view('manage_employer/employee_management/sms_partial'); ?>
                            </div>
                            <?php } ?>
                            <!-- #tab7 -->
                            <?php }  ?>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js">
</script>
<!--file opener modal starts-->
<script language="JavaScript" type="text/javascript">
$("#teams").select2();


$('#js-policies').select2({
    closeOnSelect: false
});

$('#js_offdays').select2({
    closeOnSelect: false
});

$(".emp_break_info").on("keyup", function() {
    var type = $(this).data("type");
    validateBreakTime(type);

});

function validateBreakTime(type) {
    //
    var break_hours = $("#br_hours").val();
    var break_minutes = $("#br_mins").val();
    //
    var shift_start = $(".js-shift-start-time").val();
    var shift_end = $(".js-shift-end-time").val();
    //
    //create date format          
    var timeStart = new Date("01/01/2007 " + shift_start).getHours();
    var timeEnd = new Date("01/01/2007 " + shift_end).getHours();
    //
    var hourDiff = timeEnd - timeStart;

    var is_error = "no";
    //  
    if (type == "minutes" && break_minutes > 59) {
        is_error = "yes";
        alertify.alert("Notice", "Break minutes always less than 59 minutes!");
    } else if (type == "hours" && break_hours > 23) {
        is_error = "yes";
        alertify.alert("Notice", "Break hours always less than 23 hours!");
    } else if (break_hours > hourDiff || (break_hours == hourDiff && break_minutes > 1)) {
        is_error = "yes";
        alertify.alert("Notice", "The break time can not be greater than the employees' shift time.!");
    }

    return is_error;
}

$(document).ready(function() {
    generateEmployeeWorkLog();
});

$('#js_offdays').select2('val', <?=json_encode($dayoffs);?>);

function generateEmployeeWorkLog() {
    var shift_start = $(".js-shift-start-time").val();
    var shift_end = $(".js-shift-end-time").val();
    var break_hours = $("#br_hours").val();
    var break_minutes = $("#br_mins").val();
    var dayoffs = $("#js_offdays").val();

    if (break_minutes.toString().length == 1) {
        break_minutes = '0' + break_minutes;
    }

    
    //create date format          
    var timeStart = new Date("01/01/2007 " + shift_start).getHours();
    var timeEnd = new Date("01/01/2007 " + shift_end).getHours();
    var breakHoursTotal = (((break_hours * 60) + parseInt(break_minutes)) / 60).toFixed(1);
    var hourDiff = timeEnd - timeStart - breakHoursTotal;
    var week_total = ((hourDiff) * (7 - dayoffs.length)).toFixed(1);
   
    $("#sh_hours").val(hourDiff);
    $("#employee_weekly_hours").val(week_total);

    var row = "";
    row += "The employee's daily workable time is of " + hourDiff + " hours. ";
    // row += break_hours;
    // row += break_hours > 1 ? " hours" : " hour";
    // if (break_minutes > 0) {
    //     row += " and " + break_minutes;
    //     row += break_minutes > 1 ? " minutes" : " minute";
    // }

    row += "Employee's weekly workable time is " + week_total;
    row += week_total > 1 ? " hours." : " hour.";

    $("#update_employee_info").text(row);
}

$(".show_employee_working_info").on("change", function() {
    generateEmployeeWorkLog();
});

<?php if($access_level_plus == 1 && IS_PTO_ENABLED ==1) {?>
$('.js-shift-start-time').datetimepicker({
    datepicker: false,
    format: 'g:i A',
    formatTime: 'g:i A',
    onShow: function(ct) {
        this.setOptions({
            //maxTime: $('.js-shift-start-time').val() ? $('.js-shift-start-time').val() : false
        });
    }
});
$('.js-shift-end-time').datetimepicker({
    datepicker: false,
    format: 'g:i A',
    formatTime: 'g:i A',
    onShow: function(ct) {
        time = $('.js-shift-start-time').val();
        if (time == '') return false;
        timeAr = time.split(":");
        last = parseInt(timeAr[1].substr(0, 2));
        if (last == 0)
            last = "00";
        mm = timeAr[1].substr(2, 2);
        timeFinal = timeAr[0] + ":" + last + mm;
        this.setOptions({
            //minTime: $('.js-shift-start-time').val() ? timeFinal : false
        })
    }
});
<?php } ?>
<?php if(IS_NOTIFICATION_ENABLED == 1 && $phone_sid != '') { ?>
$('#notified_by').select2({
    closeOnSelect: false,
    allowHtml: true,
    allowClear: true,
    tags: true
});
<?php } ?>

function remove_event(event_sid) {
    alertify.confirm(
        'Are you sure?',
        'Are you sure you want to delete this event?',
        function() {
            var my_request;
            my_request = $.ajax({
                url: '<?php echo base_url('calendar/tasks');?>',
                type: 'POST',
                data: {
                    'action': 'delete_event',
                    'event_sid': event_sid
                }
            });

            my_request.success(function(response) {
                $('#remove_li' + event_sid + '').remove();
                $('.btn').removeClass('disabled').prop('disabled', false);
            });
        },
        function() {
            alertify.error('Canceled!');
        });
}

function validate_employers_form() {

    $("#edit_employer").validate({
        ignore: ":hidden:not(select)",
        rules: {
            first_name: {
                required: true,
                pattern: /[a-zA-Z\-,' ]+$/
            },
            middle_name: {
                pattern: /^[a-zA-Z\-,' ]+$/
            },
            last_name: {
                required: true,
                pattern: /^[a-zA-Z\-,' ]+$/
            },
            Location_Address: {
                pattern: /^[a-zA-Z0-9/\-#,':;. ]+$/
            },
            email: {
                email: true,
            },
            secondary_email: {
                email: true,
            },
            other_email: {
                email: true,
            },
            Location_State: {
                pattern: /^[a-zA-Z0-9\-,' ]+$/
            },
            Location_City: {
                pattern: /^[a-zA-Z0-9\-,' ]+$/
            },
            Location_ZipCode: {
                pattern: /^[0-9\-]+$/
            },
            break_hours: {
                number: true,
                min: 0,
                max: 23
            },
            break_mins: {
                number: true,
                min: 0,
                max: 59
            },
            <?php if($access_level_plus == 1 && IS_PTO_ENABLED==1)  {?>
            shift_hours: {
                required: true,
                number: true,
                min: 1,
                max: 23
            },
            shift_mins: {
                required: true,
                number: true,
                min: 0,
                max: 59
            },
            <?php } ?>
            // PhoneNumber: {
            //     pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
            // },
            // secondary_PhoneNumber: {
            //     // pattern: /^[0-9\- ]+$/
            //     pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
            // },
            // other_PhoneNumber: {
            //     // pattern: /^[0-9\- ]+$/
            //     pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
            // },
            YouTubeVideo: {
                pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
            }
        },
        messages: {
            first_name: {
                required: 'First name is required',
                pattern: 'Letters, numbers, and dashes only please'
            },
            first_name: {
                pattern: 'Letters, numbers, and dashes only please'
            },
            last_name: {
                required: 'Last Name is required',
                pattern: 'Letters, numbers, and dashes only please'
            },
            email: {
                required: 'Please provide Valid email'
            },
            secondary_email: {
                required: 'Please provide Valid email'
            },
            other_email: {
                required: 'Please provide Valid email'
            },
            Location_City: {
                pattern: 'Letters, numbers, and dashes only please',
            },
            Location_Address: {
                pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
            },
            Location_ZipCode: {
                pattern: 'Numeric values only'
            },
            PhoneNumber: {
                // pattern: 'numbers and dashes only please'
                pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
            },
            secondary_PhoneNumber: {
                pattern: 'numbers and dashes only please'
            },
            other_PhoneNumber: {
                pattern: 'numbers and dashes only please'
            },
            YouTubeVideo: {
                pattern: 'Please Enter a Valid Youtube Video Url.'
            },
            break_hours: {
                number: "please enter a number",
                min: "Minimum allowed hours are 1",
                max: "Maximum allowed hours are 23"
            },
            break_mins: {
                number: "please enter a number",
                min: "Minimum allowed minutes are 0",
                max: "Maximum allowed minutes are 59"
            },
            <?php if($access_level_plus == 1 && IS_PTO_ENABLED==1) {?>
            shift_hours: {
                required: "This field in required",
                number: "please enter a number",
                min: "Minimum allowed hours are 1",
                max: "Maximum allowed hours are 23"
            },
            shift_mins: {
                required: "This field in required",
                number: "please enter a number",
                min: "Minimum allowed minutes are 0",
                max: "Maximum allowed minutes are 59"
            },
            <?php } ?>
        },
        errorPlacement: function(e, el) {

            <?php if($is_regex === 1){ ?>
            if ($(el)[0].id == 'PhoneNumber' || $(el)[0].id == 'sh_hours' || $(el)[0].id == 'sh_mins') {
                $(el).parent().after(e);
                e.css("margin-bottom", "5px");
            } else $(el).after(e);
            <?php } else{ ?>
            if ($(el)[0].id == 'sh_hours' || $(el)[0].id == 'sh_mins') {
                $(el).parent().after(e);
                $(e).css("margin-bottom", "5px");
            } else $(el).after(e);
            <?php } ?>

        },
        submitHandler: function(form) {

            var breakValidationError = validateBreakTime("validation");
            //
            if (breakValidationError == "yes") {
                is_error = true;
                return;
            }

            <?php if($is_regex === 1) { ?>
            // TODO
            var is_error = false;

            // Check for phone number
            if (_pn.val() != '' && _pn.val().trim() != '(___) ___-____' && !fpn(_pn.val(), '', true)) {
                alertify.alert('Invalid mobile number provided.', function() {
                    return;
                });
                is_error = true;
                return;
            }

            // Check for secondary number
            // if(_spn.val() != '' && _spn.val().trim() != '(___) ___-____' && !fpn(_spn.val(), '', true)){
            //     alertify.alert('Invalid secondary mobile number provided.', function(){ return; });
            //     is_error = true;
            //     return;
            // }
            // // Check for other number
            // if(_opn.val() != '' && _opn.val().trim() != '(___) ___-____' && !fpn(_opn.val(), '', true)){
            //     alertify.alert('Invalid telephone number provided.', function(){ return; });
            //     is_error = true;
            //     return;
            // }

            if (is_error === false) {
                // Remove and set phone extension
                $('#js-phonenumber').remove();
                // $('#js-secondary-phonenumber').remove();
                // $('#js-other-phonenumber').remove();
                // Check the fields
                // if(_spn.val().trim() == '(___) ___-____') _spn.val('');
                // else $("#edit_employer").append('<input type="hidden" id="js-secondary-phonenumber" name="txt_secondary_phonenumber" value="+1'+(_spn.val().replace(/\D/g, ''))+'" />');

                // if(_opn.val().trim() == '(___) ___-____') _opn.val('');
                // else $("#edit_employer").append('<input type="hidden" id="js-other-phonenumber" name="txt_other_phonenumber" value="+1'+(_opn.val().replace(/\D/g, ''))+'" />');


                if (_pn.val().trim() == '(___) ___-____') _pn.val('');
                else $("#edit_employer").append(
                    '<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1' + (_pn
                        .val().replace(/\D/g, '')) + '" />');
                form.submit();
            }
            <?php } else { ?>
            form.submit();
            <?php } ?>
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
        if (val == 'pictures') {
            if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext !=
                "JPE" && ext != "PNG") {
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

$(document).ready(function() {
    CKEDITOR.replace('rating_comment');
    CKEDITOR.replace('short_bio');
    CKEDITOR.replace('interests');

    CKEDITOR.replace('applicantMessage');
    var myid = $('#state_id').html();
    if (myid) {
        //console.log('I am IN');
        //console.log('My ID: '+myid);

        setTimeout(function() {
            $("#country").change();
        }, 1000);

        setTimeout(function() {
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

    $('#template').on('change', function() {
        var template_sid = $(this).val();
        var msg_subject = $('#template_' + template_sid).attr('data-subject');
        var msg_body = $('#template_' + template_sid).attr('data-body');
        $('#applicantSubject').val(msg_subject);
        // $('#applicantMessage').html($(msg_body).text());
        CKEDITOR.instances.applicantMessage.setData(msg_body);
        $('.temp-attachment').hide();
        $('#empty-attachment').hide();
        $('#' + template_sid).show();
        if (template_sid == '') {
            $('#empty-attachment').show();
        }
    });

    var pre_selected = '<?php echo !empty($employer['YouTubeVideo']) ? $employer['video_type'] : ''; ?>';
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

function getStates(val, states) {
    var html = '';
    if (val == '') {
        $('#state').html('<option value="">Select State</option>');
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
    url = "<?= base_url() ?>application_tracking_system/delete_note";
    alertify.confirm('Confirmation', "Are you sure you want to delete this Note?",
        function() {
            $.post(url, {
                    sid: id
                })
                .done(function(data) {
                    location.reload();
                });
        },
        function() {
            alertify.error('Canceled');
        });
}

function validate_form() {
    $("#event_form").validate({
        ignore: [],
        rules: {
            interviewer: {
                required: true,
            },
            PhoneNumber: {
                pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
            }
        },
        messages: {
            interviewer: {
                required: 'Please select an interviewer',
            },
            PhoneNumber: {
                required: 'Phone Number is required',
                pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
}

$('.interviewer_comment').click(function() {
    if ($('.interviewer_comment').is(":checked")) {
        $('.comment-div').fadeIn();
        $('#interviewerComment').prop('required', true);

    } else {
        $('.comment-div').hide();
        $('#interviewerComment').prop('required', false);
    }
});

$('#candidate_msg').click(function() {
    if ($('#candidate_msg').is(":checked")) {
        $('.message-div').fadeIn();
        $('#applicantMessage').prop('required', true);
    } else {
        $('.message-div').hide();
        $('#applicantMessage').prop('required', false);
    }
});

$('.goto_meeting').click(function() {
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

$('#edit_button').click(function(event) {
    event.preventDefault();
    $('.info_edit').fadeIn();
    $('.info_view').hide();

    setTimeout(function() {
        var team_id = $('#team_sid').html();
        var team_sids = '<?php echo json_encode($team_sids); ?>';
        if (team_id != 0) {
            var html = '';
            var department_sid = $('#department').val();
            var myurl = "<?= base_url() ?>employee_management/get_all_department_teams/" +
                department_sid;
            $.ajax({
                type: "GET",
                url: myurl,
                async: false,
                success: function(data) {
                    if (data != 0) {
                        var obj = jQuery.parseJSON(data);
                        allteams = obj;
                        for (var i = 0; i < allteams.length; i++) {
                            var id = allteams[i].sid;
                            var name = allteams[i].name;
                            // if (team_id == id) {
                            if (jQuery.inArray(id, team_sids) !== -1) {

                                html += '<option value="' + id + '" selected="selected">' +
                                    name + '</option>';
                            } else {
                                html += '<option value="' + id + '">' + name + '</option>';
                            }
                            // html += '<option value="' + id + '">' + name + '</option>';
                        }
                        $('#teams').html(html).select2();
                    }
                },
                error: function(data) {

                }
            });
        }

    }, 1000);
});

$('.view_button').click(function(event) {
    event.preventDefault();
    $('.info_edit').hide();
    $('.info_view').fadeIn();
});

$('#add_notes').click(function(event) {
    event.preventDefault();
    $('.note_div').fadeIn();
    $('.no_note').hide();
});

$('.interviewer').select2({
    placeholder: "Select participant(s)",
    allowClear: true
});

$('.select2-dropdown').css('z-index', '99999999999999999999999');

$('.eventendtime_create').datetimepicker({
    datepicker: false,
    format: 'g:iA',
    formatTime: 'g:iA',
    step: 15,
    onShow: function(ct) {
        time = $($('.eventstarttime_create').get(0)).val();
        timeAr = time.split(":");
        last = parseInt(timeAr[1].substr(0, 2)) + 15;
        if (last == 0)
            last = "00";
        mm = timeAr[1].substr(2, 2);
        timeFinal = timeAr[0] + ":" + last + mm;
        this.setOptions({
            minTime: $($('.eventstarttime_create').get(0)).val() ? timeFinal : false
        })
    }
});

$('.eventstarttime_create').datetimepicker({
    datepicker: false,
    format: 'g:iA',
    formatTime: 'g:iA',
    step: 15,
    onShow: function(ct) {
        time = $($('.eventendtime_create').get(0)).val();
        timeAr = time.split(":");
        last = parseInt(timeAr[1].substr(0, 2)) + 15;
        if (last == 0)
            last = "00";
        mm = timeAr[1].substr(2, 2);
        timeFinal = timeAr[0] + ":" + last + mm;
        this.setOptions({
            maxTime: $($('.eventendtime_create').get(0)).val() ? timeFinal : false
        })
    }
});

$('.eventdate').datepicker({
    dateFormat: 'mm-dd-yy'
}).val();
$('#eventdate').datepicker({
    dateFormat: 'mm-dd-yy'
}).val();
$("#eventdate").datepicker("setDate", new Date());
$('#add_event').click(function() {
    $('.event_create').fadeIn();
    $('.event_detail').hide();
});

$('#cancel_note').click(function(event) {
    event.preventDefault();
    $('.note_div').hide();
    $('#add_notes').fadeIn();
});

function check_file(val) {
    var fileName = $("#" + val).val();
    if (fileName.length > 0) {
        $('#name_' + val).html(fileName);
        var ext = fileName.split('.').pop();
        var ext = ext.toLowerCase();
        if (val == 'resume' || val == 'cover_letter') {
            if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "jpg" && ext != "jpeg" && ext != "png" && ext !=
                "jpe" && ext != "gif" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                $("#" + val).val(null);
                $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .jpg .jpeg .png .jpe .gif) allowed!</p>');
            }
        }
    } else {
        $('#name_' + val).html('Please Select');
    }
}

function check_file_all(val) {
    var fileName = $("#" + val).val();
    if (fileName.length > 0) {
        $('#name_' + val).html('<span>' + fileName + '</span>');
        //console.log(fileName);
    } else {
        $('#name_' + val).html('Please Select');
        //console.log('in else case');
    }
}

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

$('.js-joining-date').datepicker({
    dateFormat: 'mm-dd-yy',
    changeMonth: true,
    changeYear: true,
    maxDate: new Date(),
    yearRange: "<?php echo JOINING_DATE_LIMIT; ?>",
}).val();

$('#add_edit_submit').click(function() {
    if ($('input[name="video_source"]:checked').val() != 'no_video') {
        $('#my_loader').show();
        var flag = 0;
        if ($('input[name="video_source"]:checked').val() == 'youtube') {

            if ($('#yt_vm_video_url').val() != '') {

                var p =
                    /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
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
                            $('#my_loader').hide();
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
                $('#my_loader').hide();
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
                    $('#my_loader').hide();
                    return false;
                } else {
                    flag = 1;
                }
            }
        }

        if (flag == 1) {
            // $('#my_loader').show();
            return true;
        } else {
            $('#my_loader').hide();
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
            if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext !=
                "m4r" && ext != "f4b" && ext != "mov") {
                $("#" + val).val(null);
                alertify.error("Please select a valid video format.");
                $('#name_' + val).html(
                    '<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
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
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1,
                        selected_file.length);
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

function get_teams(department_sid) {
    var html = '';
    if (department_sid == 0) {
        html += '<option value="">Select Team</option>';
        html += '<option value="">Please Select your Department</option>';
        $('#teams').html(html);
    } else {
        var myurl = "<?= base_url() ?>employee_management/get_all_department_teams/" + department_sid;
        $.ajax({
            type: "GET",
            url: myurl,
            async: false,
            success: function(data) {
                if (data == 0) {
                    html += '<option value="">Select Team</option>';
                    html += '<option value="">Please Select your Department</option>';
                    $('#teams').html(html);
                    alertify.error('No Teams Found Please select Department Again');
                } else {
                    var obj = jQuery.parseJSON(data);
                    allteams = obj;
                    for (var i = 0; i < allteams.length; i++) {
                        var id = allteams[i].sid;
                        var name = allteams[i].name;
                        html += '<option value="' + id + '">' + name + '</option>';
                    }
                    $('#teams').html(html);
                }
            },
            error: function(data) {

            }
        });
    }
}
$('.js-timezone').select2();

<?php if($is_regex === 1){ ?>
// Set targets
var _pn = $("#<?=$field_phone;?>");
// _spn = $("#<?=$field_sphone;?>"),
// _opn = $("#<?=$field_ophone;?>");

// Reset phone number on load
// Added on: 05-07-2019
var val = fpn(_pn.val());
if (typeof(val) === 'object') {
    _pn.val(val.number);
    setCaretPosition(_pn, val.cur);
} else _pn.val(val);

// // Reset phone number on load
_pn.keyup(function() {
    var val = fpn($(this).val());
    if (typeof(val) === 'object') {
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

<?php } ?>
</script>

<style>
.select2-container--default .select2-selection--single {
    border: 2px solid #aaaaaa !important;
    background-color: #f7f7f7 !important;
}

.select2-container .select2-selection--single .select2-selection__rendered {
    padding: 5px 20px 5px 8px !important;
}

.select2-container {
    width: 100%;
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
    font-family: fontAwesome;
    content: "\f00c";
    color: #fff;
    background-color: #81b431;
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
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

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

.select-icon .select2-search--dropdown {
    display: none;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    height: 25px !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    height: 30px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #81b431;
    border-color: #81b431;
    color: #ffffff;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    height: 40px;
}

.select2-selection__choice__remove {
    color: #ffffff !important;
}

.select2-selection__rendered li {
    height: 24px !important;
}

.update_employee_info_container {
    height: 28px !important;
    font-size: 18px;
    padding-top: 26px;
}

@media screen and (max-width: 768px) {
    .shift_end {
        margin-top: 12px
    }

    .shift_div {
        padding-left: 0px;
        padding-right: 0px;
    }
}

</style>
