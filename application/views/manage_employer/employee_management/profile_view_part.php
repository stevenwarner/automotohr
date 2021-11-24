<ul>
    <div class="form-title-section">
        <h2>Personal Information</h2>
        <?php if(!$this->session->userdata('logged_in')['employer_detail']['pay_plan_flag']){  ?>
        <div class="form-btns">
            <input type="submit" value="edit" id="edit_button" <?= $employer['is_executive_admin'] ? 'class="disabled-btn"' : ''; ?>>
        </div>
        <?php } ?>
    </div>
    <li class="col-sm-4">
        <label>first name:</label>
        <p><?=$employer["first_name"]; ?></p>
    </li>
    <li class="col-sm-4">
        <label>Middle name / initial:</label>
        <p><?=$employer["middle_name"]; ?></p>
    </li>
    <li class="col-sm-4">
        <label>last name:</label>
        <p><?=$employer["last_name"]; ?></p>
    </li>
    <li class="col-sm-6">
        <label>email:</label>
        <p><?=$employer["email"]; ?></p>
    </li>
    <li class="col-sm-6">
        <label>mobile number:</label>
        <p><?=$primary_phone_number_cc;?></p>
    </li>
    <li class="col-sm-6">
        <label>Gender:</label>
        <p><?=ucfirst($employer["gender"]); ?></p>
    </li>
    <li class="col-sm-6">
        <label>Job Title:</label>
        <p> <?=$employer["job_title"]; ?></p>
    </li>
    <li class="col-sm-6">
        <label>address:</label>
        <p><?=$employer["Location_Address"]; ?></p>
    </li>
    <li class="col-sm-6">
        <label>city:</label>
        <p><?=$employer["Location_City"]; ?></p>
    </li>
    <li class="col-sm-6">
        <label>zipcode:</label>
        <p> <?=$employer["Location_ZipCode"]; ?></p>
    </li>
    <li class="col-sm-6">
        <label>state:</label>
        <p> <?=$employer["state_name"]; ?></p>
    </li>
    <li class="col-sm-6">
        <label>country:</label>
        <p> <?=$employer["country_name"]; ?></p>
    </li>
    
    <li class="col-sm-6">
        <label>Secondary Email:</label>
        <?php
            $secondaryEmail = isset($extra_info["secondary_email"]) && !empty($extra_info["secondary_email"]) ? $extra_info["secondary_email"] : $employer["alternative_email"];
        ?>
        <p><?=$secondaryEmail;?></p>
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
        <p><a href="<?=$employer["linkedin_profile_url"]; ?>"
                target="_blank"><?=$employer["linkedin_profile_url"]; ?></a>
        </p>
        <?php } ?>
    </li>
    <li class="col-sm-6">
        <label>Employee Number:</label>
        <p> <?=$employer["employee_number"];?></p>
    </li>
    <li class="col-sm-6">
        <label>Social Security Number:</label>
        <p> <?=$employer["ssn"]; ?></p>
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
        <p><?=$employer["shift_start_time"] != '' ? $employer["shift_start_time"] : SHIFT_START ;?>
            -
            <?=$employer["shift_end_time"] != '' ? $employer["shift_end_time"] : SHIFT_END ;?>
        </p>
    </li>
    <li class="col-sm-6 auto-height text-justify">
        <label>Shift Duration:</label>
        <?php if(isset($employer["user_shift_hours"])) { ?>
        <p><?=$employer["user_shift_hours"] .( $employer['user_shift_hours'] == 1 ? ' hour' : ' hours' ).' & '.$employer["user_shift_minutes"] .( $employer['user_shift_minutes'] == 1 ? ' minute' : ' minutes' ); ?>
        </p>
        <?php } ?>
    </li>
    <li class="col-sm-6 auto-height text-justify">
        <label>Break Duration:</label>
        <?php if(isset($employer["user_shift_hours"])) { ?>
        <p><?=$employer["break_hours"] .( $employer['break_hours'] == 1 ? ' hour' : ' hours' ).' & '.$employer["break_mins"] .( $employer['break_mins'] == 1 ? ' minute' : ' minutes' ); ?>
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
                src="https://www.youtube.com/embed/<?=$employer['YouTubeVideo']; ?>"
                frameborder="0" webkitallowfullscreen mozallowfullscreen
                allowfullscreen></iframe>
            <?php } elseif($employer['video_type'] == 'vimeo') { ?>
            <iframe class="embed-responsive-item"
                src="https://player.vimeo.com/video/<?=$employer['YouTubeVideo']; ?>"
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