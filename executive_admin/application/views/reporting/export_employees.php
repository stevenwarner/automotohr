<link rel="stylesheet" href="<?= public_url("plugins/select2/css/select2.min.css") ?>">
<script src="<?= public_url("plugins/select2/select2.min.js") ?>"></script>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title">
                        <i class="fa fa-dashboard"></i>
                        Export Employees
                    </h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>">
                        <i class="fa fa-long-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <form id="form_export_employees" enctype="multipart/form-data" method="get" action="<?php echo base_url("reports/export_employees"); ?>">
            <div class="row">
                <div class="col-sm-12">
                    <!--  -->

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4>The Provided CSV File must be in Following Format</h4>
                        </div>
                        <div class="panel-body">
                            <pre>
<b>First Name, Last Name, E-Mail, Primary Number, Street Address, City, Zipcode, State, Country, Access Level, Job Title,Status</b><br>
Jason, Snow, jason@abc.com, +123456789, 123 Street, California, 90001, CA, United States, Admin, General Manager,ActiveEmployee
Albert, King, albert@example.com, +123456789, 98 Street, California, 90001, CA, United States, Manager, Manager Sales,ActiveEmployee,
Nathan, Quite, nathan@example.com, +1823212129, your Street, California, 90001, CA, United States, Hiring Manager,ActiveActiveEmployee, 
Allen, Knight, allen@example.com, +1223312129, your Street, California, 90001, CA, United States, Employee, Office Assistant,InactiveEmployee,
Jack, Brown, jack@example.com, 013212129, your Street, California, 90001, CA, United States,Employee, Team Lead,InactiveEmployee, 
                                </pre>
                        </div>
                        <div class="panel panel-default cs_margin_panel">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-9 col-xs-10 ">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="" id="check_all" value="">
                                            <div class="control__indicator"></div>
                                        </label>
                                        <p class="cs_line">Include columns in export file</p>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse1" class="panel-collapse ">
                                <div class="panel-body">
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox"> Resume Url <input type="checkbox" class="check_it" name="columns[]" value="resume">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Cover Letter Url<input type="checkbox" class="check_it" name="columns[]" value="cover_letter">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Time Zone<input type="checkbox" class="check_it" name="columns[]" value="timezone">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Secondary Email<input type="checkbox" class="check_it" name="columns[]" value="secondary_email">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Secondary Phone Number<input type="checkbox" class="check_it" name="columns[]" value="secondary_PhoneNumber">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Other Email<input type="checkbox" class="check_it" name="columns[]" value="other_email">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Other Phone Number<input type="checkbox" class="check_it" name="columns[]" value="other_PhoneNumber">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Office Location<input type="checkbox" class="check_it" name="columns[]" value="office_location">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Linkedin Url<input type="checkbox" class="check_it" name="columns[]" value="linkedin_profile_url">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Employee Number<input type="checkbox" name="columns[]" class="check_it" value="employee_number">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Social Security Number<input type="checkbox" name="columns[]" class="check_it" value="ssn">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Date of Birth<input type="checkbox" name="columns[]" class="check_it" value="dob">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Department<input type="checkbox" name="columns[]" class="check_it" value="department_sid">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Team<input type="checkbox" name="columns[]" class="check_it" value="team_sid">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Joining Date<input type="checkbox" name="columns[]" class="check_it" value="joined_at">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Shift Hours<input type="checkbox" name="columns[]" class="check_it" value="user_shift_hours">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Shift Minutes<input type="checkbox" name="columns[]" class="check_it" value="user_shift_minutes">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Shift Start Time<input type="checkbox" name="columns[]" class="check_it" value="shift_start_time">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Shift End Time<input type="checkbox" name="columns[]" class="check_it" value="shift_end_time">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Video Url<input type="checkbox" name="columns[]" class="check_it" value="video_type">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Nick Name<input type="checkbox" name="columns[]" class="check_it" value="nick_name">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Hourly Rate<input type="checkbox" name="columns[]" class="check_it" value="hourly_rate">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Middle Name<input type="checkbox" name="columns[]" class="check_it" value="middle_name">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Hourly Technician<input type="checkbox" name="columns[]" class="check_it" value="hourly_technician">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Rehire Date<input type="checkbox" name="columns[]" class="check_it" value="rehire_date">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Flat Rate Technician<input type="checkbox" name="columns[]" class="check_it" value="flat_rate_technician">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Terminated Date<input type="checkbox" name="columns[]" class="check_it" value="terminated_date">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Semi Monthly Salary<input type="checkbox" name="columns[]" class="check_it" value="semi_monthly_salary">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Semi Monthly Draw<input type="checkbox" name="columns[]" class="check_it" value="semi_monthly_draw">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Terminated Reason<input type="checkbox" name="columns[]" class="check_it" value="terminated_reason">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">EEOC Code<input type="checkbox" name="columns[]" class="check_it" value="eeoc_code">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Benefits Salary<input type="checkbox" name="columns[]" class="check_it" value="salary_benefits">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Workers Compensation Code<input type="checkbox" name="columns[]" class="check_it" value="workers_compensation_code">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">I Speak<input type="checkbox" name="columns[]" class="check_it" value="languages_speak">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Marital Status<input type="checkbox" name="columns[]" class="check_it" value="marital_status">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Profile Picture URL<input type="checkbox" name="columns[]" class="check_it" value="profile_picture">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Drivers License<input type="checkbox" name="columns[]" class="check_it" value="drivers_license">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Gender<input type="checkbox" name="columns[]" class="check_it" value="gender">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Union Member<input type="checkbox" name="columns[]" class="check_it" value="union_member">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Union Name<input type="checkbox" name="columns[]" class="check_it" value="union_name">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Uniform Top Size<input type="checkbox" name="columns[]" class="check_it" value="uniform_top_size">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Uniform Bottom Size<input type="checkbox" name="columns[]" class="check_it" value="uniform_bottom_size">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Employment Type<input type="checkbox" name="columns[]" class="check_it" value="employment_type">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                        <div class="checkbox cs_full_width">
                                            <label class="control control--checkbox">Approvers<input type="checkbox" name="columns[]" class="check_it" value="approvers">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="hr-box">
                        <div class="hr-innerpadding">
                            <div class="row">
                                <!--  -->
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <label>Companies:</label>
                                    <div class="">
                                        <select data-rule-required="true" class="" name="companies[]" id="companies" multiple style="width: 100%">
                                            <option value="all" <?= in_array("all", $filter["companies"]) ? "selected" : ""; ?>>All</option>
                                            <?php if (!empty($companies)) { ?>
                                                <?php foreach ($companies as $company) { ?>
                                                    <option value="<?php echo $company["parent_sid"]; ?>" <?= in_array($company["parent_sid"], $filter["companies"]) ? "selected" : ""; ?>><?php echo $company["CompanyName"]; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!--  -->
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <label>Access Level:</label>
                                    <div class="">
                                        <select data-rule-required="true" class="" name="access_level[]" id="access_level" multiple style="width: 100%">
                                            <option value="all" <?= in_array("all", $filter["access_level"]) ? "selected" : ""; ?>>All Access Levels</option>
                                            <?php if (!empty($access_levels)) { ?>
                                                <?php foreach ($access_levels as $access_level) { ?>
                                                    <option value="<?php echo $access_level; ?>" <?= in_array($access_level, $filter["access_level"]) ? "selected" : ""; ?>><?php echo $access_level; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                            <option value="executive_admin">Executive Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <label>Status:</label>
                                    <div class="">
                                        <select data-rule-required="true" class="" name="status[]" id="employee_status" multiple style="width: 100%">

                                            <option <?= in_array("all", $filter["status"]) ? "selected" : ""; ?> value="all">All</option>
                                            <option <?= in_array("active", $filter["status"]) ? "selected" : ""; ?> value="active">Active</option>
                                            <option <?= in_array("leave", $filter["status"]) ? "selected" : ""; ?> value="leave">Leave</option>
                                            <option <?= in_array("suspended", $filter["status"]) ? "selected" : ""; ?> value="suspended">Suspended</option>
                                            <option <?= in_array("retired", $filter["status"]) ? "selected" : ""; ?> value="retired">Retired</option>
                                            <option <?= in_array("rehired", $filter["status"]) ? "selected" : ""; ?> value="rehired">Rehired</option>
                                            <option <?= in_array("deceased", $filter["status"]) ? "selected" : ""; ?> value="deceased">Deceased</option>
                                            <option <?= in_array("terminated", $filter["status"]) ? "selected" : ""; ?> value="terminated">Terminated</option>
                                            <option <?= in_array("inactive", $filter["status"]) ? "selected" : ""; ?> value="inactive">Inactive</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 12px;">

                                <!--  -->
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <label>From:</label>
                                    <input type="text" name="from_date" value="" class="invoice-fields" id="display_start_date">
                                </div>

                                <!--  -->
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <label>To:</label>
                                    <input type="text" name="to_date" value="" class="invoice-fields" id="display_end_date">
                                </div>
                                <!--  -->

                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <label>&nbsp;</label>
                                    <input type="hidden" name="export" value="true" />
                                    <button type="submit" class="btn btn-block btn-success">Export</button>
                                </div>
                            </div>
                            <div class="clear"></div>

                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>


<script>
    $('#access_level').select2({
        closeOnSelect: false
    });
    $('#employee_status').select2({
        closeOnSelect: false
    });

    $('#companies').select2({
        closeOnSelect: false
    });

    $('#display_start_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>",
        onSelect: function(value) {
            $('#display_end_date').datepicker('option', 'minDate', value);
        }
    });

    $('#display_end_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    });

    $("#check_all").click(function() {
        $(".check_it").prop("checked", this.checked);
    });

    $(document).ready(function() {
        $('#form_export_employees').validate();
    });

    $(".fa-minus").hide();

    $(".fa-plus").click(function() {
        $(".fa-plus").hide();
        $(".fa-minus").show();
    });

    $(".fa-minus").click(function() {
        $(".fa-minus").hide();
        $(".fa-plus").show();
    });

    $(".check_it").click(function() {
        var total_boxes = $(".check_it").length;
        var checked_boxes = $(".check_it:checked").length;
        if (total_boxes != checked_boxes) {
            $("#check_all").prop("checked", false);
        } else {
            $("#check_all").prop("checked", true);
        }
    });
    $("#form_export_employees").submit(function(e) {
        var ids = [];
        $(".check_it:checked").map(function() {
            ids.push($(this).val());
        });
        $(this).append("<input type='hidden' name='columns'>");
        $("input[name='columns']").val(ids);

    });
</script>
<style>
    .cs_full_width {
        width: 100%;
    }

    .cs_adjust_margin {
        padding: 0px !important;
        margin-bottom: 10px;
    }

    .cs_icon_margin {
        padding-top: 18px;
        float: right;
    }

    radio label,
    .checkbox label {
        padding-left: 30px;
    }

    .cs_margin_panel {
        margin: 15px;
    }

    .cs_line {
        margin-left: 35px;
        margin-top: -7px;
        font-weight: bolder;
    }
</style>