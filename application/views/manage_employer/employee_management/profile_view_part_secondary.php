<div class="form-title-section">
    <h2>Merged Employees</h2>
    <span class="pull-right">
        <button class="btn btn-cancel csW" id="jsBackToPrimary">Cancel</button>
    </span>
</div>

<div class="clearfix"></div>

<?php foreach ($MergeData as $md) : ?>
    <?php
    //
    $me = "";
    $secondary_data = unserialize($md['secondary_employee_profile_data']);
    //
    if (isset($secondary_data["user_profile"])) {
        $me = $secondary_data["user_profile"];
    } else {
        $me = $secondary_data;
    }
    //
    $extra_info = unserialize($me['extra_info']);
    $field_phone = 'PhoneNumber';
    $field_sphone = 'secondary_PhoneNumber';
    $field_ophone = 'other_PhoneNumber';
    $timeOff = 'disable';

    //
    $is_regex = 0;
    $input_group_start = $input_group_end = '';
    $primary_phone_number_cc = $primary_phone_number = $me[$field_phone];
    //
    if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
        $is_regex = 1;
        $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
        $input_group_end   = '</div>';
        $primary_phone_number = phonenumber_format($me[$field_phone], true);
        $primary_phone_number_cc = phonenumber_format($me[$field_phone]);
    } else {
        if ($primary_phone_number === '+1') $primary_phone_number = '';
        if ($primary_phone_number_cc === '+1') $primary_phone_number_cc = 'N/A';
    }

    if (isset($me["dob"]) && $me["dob"] != '' && $me["dob"] != '0000-00-00') $dob = DateTime::createFromFormat('Y-m-d', $me['dob'])->format('m-d-Y');
    else $dob = '';
    //
    if ($_ssv) {
        //
        $me['ssn'] = ssvReplace($me["ssn"]);
        //
        if ($dob != '') $dob = DateTime::createFromFormat('m-d-Y', $dob)->format('M d XXXX, D');
    } else {
        if ($dob != '') $dob = DateTime::createFromFormat('m-d-Y', $dob)->format('M d Y, D');
    }

    if (checkIfAppIsEnabled('timeoff')) {
        $timeOff = 'enable';
    }
    ?>
    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="pt0 pb0"><strong><?= ucwords($me['first_name'] . ' ' . $me['last_name']); ?></strong> merged at: <?= formatDateToDB($md['merge_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                        <span class="pull-right">
                            <i class="fa fa-plus" aria-hidden="true" data-toggle="collapse" href="#collapseExample<?= $me['sid']; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?= $me['sid']; ?>"></i>
                        </span>
                    </h4>
                </div>
                <div class="panel-body collapse" id="collapseExample<?= $me['sid']; ?>">
                    <!--  -->
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <label class="csF16">First Name</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["first_name"]); ?></p>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label class="csF16">Nick Name</label>
                            <p class="dummy-invoice-fields"><?= isset($me["nick_name"]) ? GetVal($me["nick_name"]) : 'Not Specified'; ?></p>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label class="csF16">Middle Name / Initial</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["middle_name"]); ?></p>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label class="csF16">Last Name</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["last_name"]); ?></p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Email</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["email"]); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Primary Number</label>
                            <p class="dummy-invoice-fields"><?= GetVal($primary_phone_number_cc); ?></p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Gender</label>
                            <p class="dummy-invoice-fields"><?= GetVal(ucfirst($me["gender"])); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Job Title</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["job_title"]); ?></p>
                        </div>

                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF16">Address</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["Location_Address"]); ?></p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">City</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["Location_City"]); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Zipcode</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["Location_ZipCode"]); ?></p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">State</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["state_name"]); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Country</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["country_name"]); ?></p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Social Security Number</label>
                            <p class="dummy-invoice-fields"><?= _secret(GetVal($me["ssn"]), false, true); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Employee Number</label>
                            <p class="dummy-invoice-fields"><?= GetVal($me["employee_number"]); ?></p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Office Location</label>
                            <p class="dummy-invoice-fields"><?= GetVal($extra_info['office_location']); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Employment Type</label>
                            <p class="dummy-invoice-fields">
                                <?= GetVal(isset($employment_types[$me["employee_type"]]) ? $employment_types[$me["employee_type"]] : $employment_types['fulltime']); ?>
                            </p>
                        </div>
                        <div class="col-md-6 col-xs-12 dn">
                            <label class="csF16">Employee Status</label>
                            <p class="dummy-invoice-fields">
                                <?= GetVal(isset($employment_statuses[$me["employee_status"]]) ? $employment_statuses[$me["employee_status"]] : ''); ?>
                            </p>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Union Member</label>
                            <p class="dummy-invoice-fields" <?= $me['union_member'] ? "style='height: 100px'" : '' ?>><?= $me['union_member'] ? 'Yes <br><br>' . $me['union_name'] : 'No'; ?></p>
                        </div>
                    </div>

                    <br>
                    <!--  -->
                    <?php if (IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                        <div class="row">
                            <!-- TimeZone -->
                            <div class="col-md-12 col-xs-12">
                                <label class="csF16">Timezone</label>
                                <p class="dummy-invoice-fields">
                                    <?= ($me["timezone"] == '' || $me["timezone"] == null) || (!preg_match('/^[A-Z]/', $me['timezone'])) ? 'Not Specified' : get_timezones($me["timezone"], 'name'); ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Joining Date</label>
                            <?php
                            $joiningDate = get_employee_latest_joined_date($me['registration_date'], $me['joined_at'], $me['rehire_date'], true);
                            ?>
                            <p class="dummy-invoice-fields"><?= GetVal($joiningDate); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Date of Birth</label>
                            <p class="dummy-invoice-fields"><?php
                                                            if (!isset($me["dob"]) || $me["dob"] == '' || $me["dob"] == '0000-00-00') echo 'Not Specified';
                                                            else echo _secret($dob, true, true); ?>
                            </p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Secondary Email</label>
                            <?php
                            $secondaryEmail = isset($extra_info["secondary_email"]) && !empty($extra_info["secondary_email"]) ? $extra_info["secondary_email"] : $me["alternative_email"];
                            ?>
                            <p class="dummy-invoice-fields"><?= GetVal($secondaryEmail); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Secondary Mobile Number</label>
                            <p class="dummy-invoice-fields"><?= GetVal($extra_info['secondary_PhoneNumber']); ?></p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Other Email</label>
                            <p class="dummy-invoice-fields"><?= GetVal($extra_info['other_email']); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Other Phone Number</label>
                            <p class="dummy-invoice-fields"><?= GetVal($extra_info['other_PhoneNumber']); ?></p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF16">Linkedin Profile URL</label>
                            <?php if (isset($me["linkedin_profile_url"])) { ?>
                                <p class="dummy-invoice-fields"><a href="<?= $me["linkedin_profile_url"]; ?>" target="_blank"><?= $me["linkedin_profile_url"]; ?></a>
                                </p>
                            <?php } else { ?>
                                <p class="dummy-invoice-fields"><?= GetVal($extra_info['other_PhoneNumber']); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Department</label>
                            <p class="dummy-invoice-fields"><?= GetVal($department_name); ?></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label class="csF16">Teams</label>
                            <p class="dummy-invoice-fields"><?= GetVal(!empty($team_names) ? $team_names : $team_name); ?></p>
                        </div>
                    </div>
                    <?php if (IS_NOTIFICATION_ENABLED == 1 && $phone_sid != '') { ?>
                        <!--  -->
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label class="csF16">Notified By</label>
                                <p class="dummy-invoice-fields"><?= GetVal(ucwords($me["notified_by"])); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                    <!--  -->
                    <br>
                    <?php if ($timeOff == 'enable') { ?>
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <label class="csF16">Shift Time</label>
                                <?php
                                $shift_start = isset($me['shift_start_time']) && !empty($me['shift_start_time']) ? $me['shift_start_time'] : SHIFT_START;
                                $shift_end = isset($me['shift_end_time']) && !empty($me['shift_end_time']) ? $me['shift_end_time'] : SHIFT_END;
                                ?>
                                <p class="dummy-invoice-fields" id="employee_shift_times">

                                </p>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label class="csF16">Break Time</label>
                                <?php
                                $break_hours = isset($me['break_hours']) ? $me['break_hours'] : BREAK_HOURS;
                                $break_minutes = isset($me['break_mins']) && !empty($me['break_mins']) ? $me['break_mins'] : BREAK_MINUTES;
                                ?>
                                <p class="dummy-invoice-fields" id="employee_break_timings">

                                </p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <label class="csF16">Week Days Off</label>
                                <p class="dummy-invoice-fields">
                                    <?php if (isset($me["offdays"])) { ?>
                                        <?php echo str_replace(",", ", ", $me["offdays"]); ?>
                                    <?php } else { ?>
                                        Not Specified
                                    <?php } ?>
                                </p>
                            </div>
                            <div class="col-md-6 col-xs-12" id="display_employee_shift_detaails">
                                <!-- Employee shift information come here -->
                            </div>
                        </div>
                    <?php } ?>
                    <!--  -->
                    <br>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF16">Interests</label>
                            <p class="dummy-invoice-fields"><?= GetVal(isset($extra_info["interests"]) ? $extra_info["interests"] : ''); ?></p>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF16">Short Bio</label>
                            <p class="dummy-invoice-fields"><?= GetVal(isset($extra_info["short_bio"]) ? $extra_info["short_bio"] : ''); ?></p>
                        </div>
                    </div>
                    <?php if (checkIfAppIsEnabled('timeoff')) { ?>
                        <!--  -->
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label class="csF16">Policies</label>
                                <?php
                                if (!empty($policies)) {
                                    foreach ($policies as $key => $policy) {
                                        if (!$policy['Implements']) {
                                            continue;
                                        }
                                ?>
                                        <p style="<?= $key % 2 === 0 ? "background-color: #eee;" : ""; ?> padding: 10px;">
                                            <strong>Policy Title:</strong> <?php echo $policy['Title']; ?>
                                            <br /><span><strong>Remaining Time:</strong>
                                                <?= $policy['RemainingTime']; ?></span>
                                            <br /><span><strong>Employment Status:</strong>
                                                <?= ucwords($policy['EmployementStatus']); ?></span>
                                            <br /><span><strong>Entitled:</strong>
                                                <?= $policy['Implements'] ? 'Yes' : 'No'; ?></span>
                                        </p>
                                <?php   }
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <!--  -->
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

<script>
    $('#jsBackToPrimary').click(function(e) {
        //
        e.preventDefault();
        $('#jsSecondaryEmployeeBox').hide();
        $('#jsPrimaryEmployeeBox').show();
    });

    $('.collapse').on('show.bs.collapse', function() {
        $(this).parent().find('[data-toggle="collapse"]').toggleClass('fa-plus fa-minus');
    })
    $('.collapse').on('hide.bs.collapse', function() {
        $(this).parent().find('[data-toggle="collapse"]').toggleClass('fa-minus fa-plus');
    })
</script>

<?php if ($timeOff == 'enable') { ?>
    <script>
        $(document).ready(function() {
            var shift_start = '<?php echo $shift_start ?>';
            var shift_end = '<?php echo $shift_end ?>';
            var break_hours = '<?php echo $break_hours; ?>';
            var break_minutes = '<?php echo $break_minutes; ?>';
            var dayoffs = '<?php echo !empty($employer["offdays"]) ? count(explode(',', $employer["offdays"])) : 0; ?>';

            if (break_minutes.toString().length == 1) {
                break_minutes = '0' + break_minutes;
            }

            //create date format          
            var timeStart = new Date("01/01/2007 " + shift_start).getHours();
            var timeEnd = new Date("01/01/2007 " + shift_end).getHours();
            var breakHoursTotal = (((break_hours * 60) + parseInt(break_minutes)) / 60).toFixed(1);
            var hourDiff = timeEnd - timeStart - breakHoursTotal;
            var weekTotal = ((hourDiff) * (7 - dayoffs)).toFixed(1);
            var weekAllowedWorkHours = 40;
            var weekWorkableHours = weekTotal < weekAllowedWorkHours ? weekTotal : weekAllowedWorkHours;
            var overTime = weekTotal > weekAllowedWorkHours ? weekTotal - weekAllowedWorkHours : 0;

            var row = "";
            row += "<p>";

            if (overTime != 0) {
                row += "<span class='text-danger'>";
                row += "Any time over 40.00 hours a week goes into overtime.</br>";
            }

            row += "The employee's daily workable time is of " + hourDiff.toFixed(2) + " hours.";
            row += " Employee's weekly workable time is " + weekWorkableHours.toFixed(2);
            row += weekWorkableHours > 1 ? " hours." : " hour.";

            if (overTime != 0) {
                row += " Employee's over time is " + overTime.toFixed(2);
                row += overTime > 1 ? " hours." : " hour.";
                row += "</span>";
            }

            row += "</p>";

            var shift_time = hourDiff + (hourDiff > 1 ? " hours" : " hour");
            var break_hour_text = break_hours > 0 ? (break_hours + (break_hours > 1 ? " hours" : " hour")) : 0;
            var break_minute_text = break_minutes > 0 ? (break_minutes + (break_minutes > 1 ? " minutes" : " minute")) : 0;
            var break_concat = break_minute_text != 0 ? ' & ' : '';
            var break_timming = (break_hour_text != 0 ? (break_hour_text + break_concat) : '') + (break_minute_text != 0 ? break_minute_text : '');

            $("#display_employee_shift_detaails").html(row);
            $("#employee_shift_times").text(shift_start + ' - ' + shift_end + ' (' + shift_time + ')');
            $("#employee_break_timings").text(break_timming);
        });
    </script>
<?php } ?>