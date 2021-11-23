<div class="form-title-section">
    <h2>Personal Information</h2>
    <?php if(!$this->session->userdata('logged_in')['employer_detail']['pay_plan_flag']){  ?>
    <div class="form-btns">
        <input type="submit" value="edit" id="edit_button"
            <?= $employer['is_executive_admin'] ? 'class="disabled-btn"' : ''; ?>>
    </div>
    <?php } ?>
</div>
<!--  -->
<div>
    <!--  -->
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <label class="csF16">First Name</label>
            <p class="csF16"><?=GetVal($employer["first_name"]); ?></p>
        </div>
        <div class="col-md-4 col-xs-12">
            <label class="csF16">Middle Name / Initial</label>
            <p class="csF16"><?=GetVal($employer["middle_name"]); ?></p>
        </div>
        <div class="col-md-4 col-xs-12">
            <label class="csF16">Last Name</label>
            <p class="csF16"><?=GetVal($employer["last_name"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Email</label>
            <p class="csF16"><?=GetVal($employer["email"]); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Mobile Number</label>
            <p class="csF16"><?=GetVal($primary_phone_number_cc); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Gender</label>
            <p class="csF16"><?=GetVal(ucfirst($employer["gender"])); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Date of Birth</label>
            <p class="csF16"><?php
                if(!isset($employer["dob"]) || $employer["dob"] == '' || $employer["dob"] == '0000-00-00') echo 'Not Specified';
                else echo $dob;?>
            </p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Job Title</label>
            <p class="csF16"><?=GetVal($employer["job_title"]); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Address</label>
            <p class="csF16"><?=GetVal($employer["Location_Address"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">City</label>
            <p class="csF16"><?=GetVal($employer["Location_City"]); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Zipcode</label>
            <p class="csF16"><?=GetVal($employer["Location_ZipCode"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">State</label>
            <p class="csF16"><?=GetVal($employer["state_name"]); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Country</label>
            <p class="csF16"><?=GetVal($employer["country_name"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Social Security Number</label>
            <p class="csF16"><?=GetVal($employer["ssn"]); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Employee Number</label>
            <p class="csF16"><?=GetVal($employer["employee_number"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <!-- TimeZone -->
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Timezone</label>
            <p class="csF16">
                <?=($employer["timezone"] == '' || $employer["timezone"] == null) || (!preg_match('/^[A-Z]/', $employer['timezone'])) ? 'Not Specified' : get_timezones($employer["timezone"], 'name');?>
            </p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Joining Date</label>
            <?php
                $joiningDate = date_with_time(!empty($employer["joined_at"]) ? $employer["joined_at"] : $employer["registration_date"]);
            ?>
            <p class="csF16"><?=GetVal($joiningDate); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Employment Type</label>
            <p class="csF16">
                <?=GetVal(isset($employment_types[$employer["employee_type"]]) ? $employment_types[$employer["employee_type"]] : $employment_types['fulltime']); ?>
            </p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Employee Status</label>
            <p class="csF16">
                <?=GetVal(isset($employment_statuses[$employer["employee_status"]]) ? $employment_statuses[$employer["employee_status"]] : '' ); ?>
            </p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Secondary Email</label>
            <?php
                $secondaryEmail = isset($extra_info["secondary_email"]) && !empty($extra_info["secondary_email"]) ? $extra_info["secondary_email"] : $employer["alternative_email"];
            ?>
            <p class="csF16"><?=GetVal($secondaryEmail); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Secondary Mobile Number</label>
            <p class="csF16"><?=GetVal($extra_info['secondary_PhoneNumber']); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Other Email</label>
            <p class="csF16"><?=GetVal($extra_info['other_email']); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Other Phone Number</label>
            <p class="csF16"><?=GetVal($extra_info['other_PhoneNumber']); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Linkedin Profile URL</label>
            <?php if (isset($employer["linkedin_profile_url"])) { ?>
            <p class="csF16"><a href="<?=$employer["linkedin_profile_url"]; ?>"
                    target="_blank"><?=$employer["linkedin_profile_url"]; ?></a>
            </p>
            <?php } else{ ?>
            <p class="csF16"><?=GetVal($extra_info['other_PhoneNumber']); ?></p>
            <?php } ?>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Office Location</label>
            <p class="csF16"><?=GetVal($extra_info['office_location']); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Department</label>
            <p class="csF16"><?=GetVal($department_name); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Teams</label>
            <p class="csF16"><?=GetVal(!empty($team_names) ? $team_names : $team_name); ?></p>
        </div>
    </div>
    <?php if(IS_NOTIFICATION_ENABLED == 1 && $phone_sid != '') { ?>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <label class="csF16">Notified By</label>
            <p class="csF16"><?=GetVal(ucwords($employer["notified_by"])); ?></p>
        </div>
    </div>
    <?php } ?>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Shift Start / End Time</label>
            <p class="csF16"><?=$employer["shift_start_time"] != '' ? $employer["shift_start_time"] : SHIFT_START;?> -
                <?=$employer["shift_end_time"] != '' ? $employer["shift_end_time"] : SHIFT_END ;?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Week Day Offs</label>
            <p class="csF16">
                <?php if(isset($employer["offdays"])) { ?>
                <?php echo str_replace(",", ", ", $employer["offdays"]); ?>
                <?php } else { ?>
                Not Specified
                <?php } ?>
            </p>
        </div>

    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Shift Duration</label>
            <p class="csF16">
                <?php if(isset($employer["user_shift_hours"])) { 
                echo $employer["user_shift_hours"] .( $employer['user_shift_hours'] == 1 ? ' hour' : ' hours' ).' & '.$employer["user_shift_minutes"] .( $employer['user_shift_minutes'] == 1 ? ' minute' : ' minutes' );
                } else{
                    echo 'Not Specified';
                } ?>
            </p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Break Duration</label>
            <p class="csF16">
                <?php if(isset($employer["user_shift_hours"])) { ?>
                <?=$employer["break_hours"] .( $employer['break_hours'] == 1 ? ' hour' : ' hours' ).' & '.$employer["break_mins"] .( $employer['break_mins'] == 1 ? ' minute' : ' minutes' ); ?>
                <?php } else{ ?>
                Not Specified
                <?php } ?>
            </p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <label class="csF16">Interests</label>
            <p class="csF16"><?=GetVal(isset($extra_info["interests"]) ? $extra_info["interests"] : ''); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <label class="csF16">Short Bio</label>
            <p class="csF16"><?=GetVal(isset($extra_info["short_bio"]) ? $extra_info["short_bio"] : ''); ?></p>
        </div>
    </div>
    <?php if(checkIfAppIsEnabled('timeoff')){ ?>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <label class="csF16">Policies</label>
            <?php 
            if(!empty($policies)){
                foreach($policies as $key => $policy){ 
                    if(!$policy['Implements']) { continue; }
                ?>
                <p style="<?=$key % 2 === 0 ? "background-color: #eee;" : "";?> padding: 10px;">
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
        </div>
    </div>
    <?php } ?>
</div>

<?php if (isset($employer["YouTubeVideo"]) && $employer["YouTubeVideo"] != "") {
    if($employer['video_type'] == 'uploaded'){
        $fileExt = $employer['YouTubeVideo'];
        $fileExt = strtolower(pathinfo($fileExt, PATHINFO_EXTENSION));
    }?>
<div class="applicant-video">
    <div class="<?= !empty($fileExt) && $fileExt != 'mp3' ? 'well well-sm' : '';?>">
        <div class="embed-responsive embed-responsive-16by9">
            <?php if($employer['video_type'] == 'youtube') { ?>
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$employer['YouTubeVideo']; ?>"
                 webkitallowfullscreen mozallowfullscreen allowfullscreen title=""></iframe>
            <?php } elseif($employer['video_type'] == 'vimeo') { ?>
            <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?=$employer['YouTubeVideo']; ?>"
                 webkitallowfullscreen mozallowfullscreen allowfullscreen  title=""></iframe>
            <?php } else {
                    if ($fileExt == 'mp3') {?>
            <audio controls>
                <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $employer['YouTubeVideo']; ?>"
                    type='audio/mp3'>
            </audio>
            <?php } else { ?>
            <video controls>
                <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $employer['YouTubeVideo']; ?>"
                    type='video/mp4'>
            </video>
            <?php }
                } ?>
        </div>
    </div>
</div>
<?php } ?>