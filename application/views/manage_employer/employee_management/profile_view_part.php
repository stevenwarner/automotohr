<div class="form-title-section">
    <div class="row">
        <div class="col-sm-6">
            <h2>Personal Information </h2>
        </div>
        <?php if (!$this->session->userdata('logged_in')['employer_detail']['pay_plan_flag'] || $this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {  ?>
            <div class="col-sm-6 text-right">
                <?php if (($session['employer_detail']['access_level_plus'] || !$session['employer_detail']['pay_plan_flag']) && isTranferredEmployee($employer['sid'])) { ?>
                    <button class="btn btn-success btn-sm jsEmployeeTransferLog" title="View Transfer Log" placement="top" data-id="<?= $employer_id; ?>" data-original-title="View Transfer Detail">
                        <i class="fa fa-history" aria-hidden="true"></i>
                    </button>
                <?php } ?>
                <?php if (!empty($MergeData)) { ?>
                    <button id="jsSecondaryButton" class="btn btn-success">
                        Merged Employee Information
                    </button>
                <?php } ?>

                <?php if (!empty($EmployeeBeforeHireData)) { ?>
                    <button id="jsBeforHireButton" class="btn btn-success">
                        Employee Information Before Hire
                    </button>
                <?php } ?>

                <?php if ($profileHistory > 0) { ?>
                    <button type="button" class="btn btn-warning" id="jsProfileHistory" data-id="<?= $employer_id; ?>" data-name="<?= remakeEmployeeName($employer); ?>">Profile History</button>
                <?php } ?>
                <button id="<?php echo $employer['is_executive_admin'] ? '' : 'edit_button'; ?>" class="btn btn-success <?php echo $employer['is_executive_admin'] ? 'disabled-btn' : ''; ?>">Edit profile</button>
            </div>
        <?php } ?>
    </div>
</div>

<div>
    <!--  -->
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <label class="csF16">First Name</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["first_name"]); ?></p>
        </div>
        <div class="col-md-3 col-xs-12">
            <label class="csF16">Nick Name</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["nick_name"]); ?></p>
        </div>
        <div class="col-md-3 col-xs-12">
            <label class="csF16">Middle Name / Initial</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["middle_name"]); ?></p>
        </div>
        <div class="col-md-3 col-xs-12">
            <label class="csF16">Last Name</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["last_name"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Email</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["email"]); ?></p>
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
            <p class="dummy-invoice-fields"><?= GetVal(ucfirst($employer["gender"])); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Job Title</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["job_title"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">



        <?php if (isCompanyOnComplyNet($employer['parent_sid']) != 0) { ?>
            <div class="col-md-6 col-xs-12">
                <label class="csF16">ComplyNet Job Title</label>
                <p class="dummy-invoice-fields"><?= $employer["complynet_job_title"];?></p>
            </div>

        <?php } ?>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Payment Method</label>
            <p class="dummy-invoice-fields"><?= $employer["payment_method"] == 'direct_deposit' ? 'Direct Deposit' : 'Check'; ?></p>
        </div>
    </div>

    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <label class="csF16">Address</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["Location_Address"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">City</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["Location_City"]); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Zipcode</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["Location_ZipCode"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">State</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["state_name"]); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Country</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["country_name"]); ?></p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Social Security Number</label>
            <p class="dummy-invoice-fields"><?= _secret(GetVal($employer["ssn"])); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Employee Number</label>
            <p class="dummy-invoice-fields"><?= GetVal($employer["employee_number"]); ?></p>
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
                <?= GetVal(isset($employment_types[$employer["employee_type"]]) ? $employment_types[$employer["employee_type"]] : ''); ?>
            </p>
        </div>
        <div class="col-md-6 col-xs-12 dn">
            <label class="csF16">Employee Status</label>
            <p class="dummy-invoice-fields">
                <?= GetVal(isset($employment_statuses[$employer["employee_status"]]) ? $employment_statuses[$employer["employee_status"]] : ''); ?>
            </p>
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
                    <?= ($employer["timezone"] == '' || $employer["timezone"] == null) || (!preg_match('/^[A-Z]/', $employer['timezone'])) ? 'Not Specified' : get_timezones($employer["timezone"], 'name'); ?>
                </p>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Joining Date</label>
            <?php
            $joiningDate = date_with_time(!empty($employer["joined_at"]) ? $employer["joined_at"] : $employer["registration_date"]);
            ?>
            <p class="dummy-invoice-fields"><?= GetVal($joiningDate); ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Date of Birth</label>
            <p class="dummy-invoice-fields">
                <?php
                if (strpos($dob, XSYM) !== false) {
                    echo _secret($dob, true);
                } else {
                    if (!isset($employer["dob"]) || $employer["dob"] == '' || $employer["dob"] == '0000-00-00') {
                        echo 'Not Specified';
                    } else {
                        echo _secret($dob, true);
                    }
                }

                ?>
            </p>
        </div>
    </div>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Rehire Date</label>
            <?php
            $rehireDate = $employer['rehire_date'] != NULL && $employer['rehire_date'] != '0000-00-00' ? date_with_time($employer['rehire_date']) : '';
            ?>
            <p class="dummy-invoice-fields">
                <?= GetVal($rehireDate); ?>
            </p>
        </div>

        <div class="col-md-6 col-xs-12">
            <label class="csF16">Starting Date as a Full-Time Employee</label>
            <?php
            $employmentDate = $employer['employment_date'] != NULL && $employer['employment_date'] != '0000-00-00' ? date_with_time($employer['employment_date']) : '';
            ?>
            <p class="dummy-invoice-fields">
                <?= GetVal($employmentDate); ?>
            </p>
        </div>



    </div <!-- -->
    <br>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Linkedin Profile URL</label>
            <?php if (isset($employer["linkedin_profile_url"])) { ?>
                <p class="dummy-invoice-fields"><a href="<?= $employer["linkedin_profile_url"]; ?>" target="_blank"><?= $employer["linkedin_profile_url"]; ?></a>
                </p>
            <?php } else { ?>
                <p class="dummy-invoice-fields"><?= GetVal($extra_info['other_PhoneNumber']); ?></p>
            <?php } ?>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Secondary Email</label>
            <?php
            $secondaryEmail = isset($extra_info["secondary_email"]) && !empty($extra_info["secondary_email"]) ? $extra_info["secondary_email"] : $employer["alternative_email"];
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
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Department</label>
            <p class="dummy-invoice-fields"><?= isset($departmentTeamInfo) && !empty($departmentTeamInfo['name']) ? GetVal($departmentTeamInfo['name']) : ''; ?></p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Teams</label>
            <p class="dummy-invoice-fields"><?= isset($departmentTeamInfo) && !empty($departmentTeamInfo['team_name']) ? GetVal($departmentTeamInfo['team_name']) : ''; ?></p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Marital Status</label>
            <p class="dummy-invoice-fields">
                <?php echo $employer["marital_status"] == "not_specified" ? "Not Specified" : GetVal($employer["marital_status"]); ?>
            </p>
        </div>
    </div>
    <?php if (IS_NOTIFICATION_ENABLED == 1 && $phone_sid != '') { ?>
        <!--  -->
        <br>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="csF16">Notified By</label>
                <p class="dummy-invoice-fields"><?= GetVal(ucwords($employer["notified_by"])); ?></p>
            </div>
        </div>
    <?php } ?>
    <!--  -->
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Hourly Rate</label>
            <p class="dummy-invoice-fields">
                <?= GetVal($employer['hourly_rate']); ?>
            </p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Hourly Technician</label>
            <p class="dummy-invoice-fields">
                <?= GetVal($employer['hourly_technician']); ?>
            </p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Flat Rate Technician</label>
            <p class="dummy-invoice-fields">
                <?= GetVal($employer['flat_rate_technician']); ?>
            </p>
        </div>
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Semi Monthly Salary</label>
            <p class="dummy-invoice-fields">
                <?= GetVal($employer['semi_monthly_salary']); ?>
            </p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <label class="csF16">Semi Monthly Draw</label>
            <p class="dummy-invoice-fields">
                <?= GetVal($employer['semi_monthly_draw']); ?>
            </p>
        </div>
        <?php if (isPayrollOrPlus(true)) { ?>
            <div class="col-md-6 col-xs-12">
                <label class="csF16">Workers Compensation Code</label>
                <p class="dummy-invoice-fields">
                    <?= GetVal($employer['workers_compensation_code']); ?>
                </p>
            </div>
        <?php } ?>

    </div>
    <?php if (isPayrollOrPlus(true)) { ?>
        <br>

        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="csF16">EEOC Code</label>
                <p class="dummy-invoice-fields">
                    <?= GetVal($employer['eeoc_code']); ?>
                </p>
            </div>

            <div class="col-md-6 col-xs-12">
                <label class="csF16">Benefits Salary</label>
                <p class="dummy-invoice-fields">
                    <?= GetVal($employer['salary_benefits']); ?>
                </p>
            </div>

        </div>
    <?php } ?>
    <br>

    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <label class="csF16">I Speak</label>
            <p class="dummy-invoice-fields"><?= $employer['languages_speak'] ? showLanguages($employer['languages_speak']) : 'Not Specified'; ?></p>
        </div>
    </div>
    <br />


    <div class="row">
        <div class="col-sm-6">
            <label class="csF16">Union Member</label>
            <p class="dummy-invoice-fields" <?= $employer['union_member'] ? "style='height: 100px'" : '' ?>><?= $employer['union_member'] ? 'Yes <br><br>' . $employer['union_name'] : 'No'; ?></p>

        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-sm-6">
            <label class="csF16">Uniform Top Size</label>
            <p class="dummy-invoice-fields"><?= $employer['uniform_top_size']; ?></p>

        </div>

        <div class="col-sm-6">
            <label class="csF16">Uniform Bottom Size</label>
            <p class="dummy-invoice-fields"><?= $employer['uniform_bottom_size']; ?></p>

        </div>

    </div>
    <br />




    <?php if ($timeOff == 'enable') { ?>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="csF16">Shift Time</label>
                <?php
                $shift_start = isset($employer['shift_start_time']) && !empty($employer['shift_start_time']) ? $employer['shift_start_time'] : SHIFT_START;
                $shift_end = isset($employer['shift_end_time']) && !empty($employer['shift_end_time']) ? $employer['shift_end_time'] : SHIFT_END;
                ?>
                <p class="dummy-invoice-fields" id="employee_shift_time">

                </p>
            </div>
            <div class="col-md-6 col-xs-12">
                <label class="csF16">Break Time</label>
                <?php
                $break_hours = isset($employer['break_hours']) ? $employer['break_hours'] : BREAK_HOURS;
                $break_minutes = isset($employer['break_mins']) && !empty($employer['break_mins']) ? $employer['break_mins'] : BREAK_MINUTES;
                ?>
                <p class="dummy-invoice-fields" id="employee_break_timing">

                </p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="csF16">Week Days Off</label>
                <p class="dummy-invoice-fields">
                    <?php if (isset($employer["offdays"])) { ?>
                        <?php echo str_replace(",", ", ", $employer["offdays"]); ?>
                    <?php } else { ?>
                        Not Specified
                    <?php } ?>
                </p>
            </div>
            <div class="col-md-6 col-xs-12" id="display_employee_shift_detaail">
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
</div>

<?php if (isset($employer["YouTubeVideo"]) && $employer["YouTubeVideo"] != "") {
    if ($employer['video_type'] == 'uploaded') {
        $fileExt = $employer['YouTubeVideo'];
        $fileExt = strtolower(pathinfo($fileExt, PATHINFO_EXTENSION));
    } ?>
    <div class="applicant-video">
        <div class="<?= !empty($fileExt) && $fileExt != 'mp3' ? 'well well-sm' : ''; ?>">
            <div class="embed-responsive embed-responsive-16by9">
                <?php if ($employer['video_type'] == 'youtube') { ?>
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $employer['YouTubeVideo']; ?>" webkitallowfullscreen mozallowfullscreen allowfullscreen title=""></iframe>
                <?php } elseif ($employer['video_type'] == 'vimeo') { ?>
                    <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?= $employer['YouTubeVideo']; ?>" webkitallowfullscreen mozallowfullscreen allowfullscreen title=""></iframe>
                    <?php } else {
                    if ($fileExt == 'mp3') { ?>
                        <audio controls>
                            <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $employer['YouTubeVideo']; ?>" type='audio/mp3'>
                        </audio>
                    <?php } else { ?>
                        <video controls>
                            <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $employer['YouTubeVideo']; ?>" type='video/mp4'>
                        </video>
                <?php }
                } ?>
            </div>
        </div>
    </div>
<?php } ?>

<style>
    #display_employee_shift_detaail {
        padding-top: 30px;
        font-size: 16px;
        font-weight: 600;
    }
</style>

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

            $("#display_employee_shift_detaail").html(row);
            $("#employee_shift_time").text(shift_start + ' - ' + shift_end + ' (' + shift_time + ')');
            $("#employee_break_timing").text(break_timming);
        });
    </script>
<?php } ?>

<script>
    $('#jsSecondaryButton').click(function(e) {
        //
        e.preventDefault();
        //
        $('#jsPrimaryEmployeeBox').hide();
        $('#jsSecondaryEmployeeBox').show();
    });



    $('#jsBeforHireButton').click(function(e) {
        //
        e.preventDefault();
        //
        $('#jsPrimaryEmployeeBox').hide();
        $('#jsBeforHireBox').show();
    });



    //
    $(document).on('click', '.jsEmployeeTransferLog', function(event) {

        //
        event.preventDefault();
        //
        var employeeId = $(this).data('id') || null;
        //
        Model({
            Id: "jsEmployeeQuickProfileModal",
            Loader: 'jsEmployeeQuickProfileModalLoader',
            Title: 'Employee Transfer History',
            Body: '<div class="container"><div id="jsEmployeeQuickProfileModalBody"></div></div>'
        }, function() {

            if (employeeId) {
                var html = '<div id="jsEmployeeQuickProfileModalMainBody"></div>';
                //
                $('#jsEmployeeQuickProfileModalBody').html(html);
                GetEmployeeTransferLog(employeeId, 'jsEmployeeQuickProfileModal');
            }
        });
    });



    //
    function GetEmployeeTransferLog(
        employeeId,
        id
    ) {
        //
        if (employeeId === 0) {
            // flush view
            $('#' + id + 'MainBody').html('');
            return;
        }
        //
        if (isXHRInProgress != null) {
            isXHRInProgress.abort();
        }
        $('.jsIPLoader[data-page="' + (id) + 'Loader"]').show(0);
        //
        isXHRInProgress =
            $.get(window.location.origin + '/employee_management/employer_transfer_log/' + employeeId)
            .done(function(resp) {
                //
                isXHRInProgress = null;
                //
                if (resp.Status === false) {
                    $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
                    $('#' + id + 'MainBody').html(resp.Msg);
                    return;
                }
                $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
                //
                $('#' + id + 'MainBody').html(resp.Data);
            })
            .error(function(err) {
                //
                isXHRInProgress = null;
                $('#' + id).html('Something went wrong while accessing the employee transfer.');
            });
        //
        return '<div id="' + (id) + '"><p class="text-center"><i class="fa fa-spinner fa-spin csF18 csB7" aria-hidden="true"></i></p></div>';
    }

    $(document).on('click', '.jsToggleRow', function(e) {
        e.preventDefault();
        let id = $(this).closest('tr').data('id');
        $('.jsToggleTable' + id).toggle();
    });
</script>