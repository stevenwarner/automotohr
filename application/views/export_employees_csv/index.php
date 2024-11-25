<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="dashboard-conetnt-wrp">
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
                            <?php if ($access_level_plus || $pay_plan_flag == 1) { ?>
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
                                            <!--                                            <div class="col-lg-3 col-xs-2 ">-->
                                            <!--                                                <span  class="fa fa-plus cs_icon_margin" data-toggle="collapse" href="#collapse1"></span><span  class="fa fa-minus cs_icon_margin" data-toggle="collapse" href="#collapse1"></span>-->
                                            <!--                                            </div>-->
                                        </div>
                                    </div>
                                    <div id="collapse1" class="panel-collapse ">
                                        <div class="panel-body">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox"> Resume Url <input type="checkbox" class="check_it" name="resume_url" value="resume">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Cover Letter Url<input type="checkbox" class="check_it" name="cover_letter_url" value="cover_letter">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Time Zone<input type="checkbox" class="check_it" name="time_zone" value="timezone">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Secondary Email<input type="checkbox" class="check_it" name="secondary_email" value="secondary_email">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Secondary Phone Number<input type="checkbox" class="check_it" name="secondary_phone_number" value="secondary_PhoneNumber">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Other Email<input type="checkbox" class="check_it" name="other_email" value="other_email">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Other Phone Number<input type="checkbox" class="check_it" name="other_phone_number" value="other_PhoneNumber">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Office Location<input type="checkbox" class="check_it" name="office_location" value="office_location">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Linkedin Url<input type="checkbox" class="check_it" name="linkedin_url" value="linkedin_profile_url">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Employee Number<input type="checkbox" name="emplolyee_number" class="check_it" value="employee_number">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Social Security Number<input type="checkbox" name="social_security_number" class="check_it" value="ssn">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Date of Birth<input type="checkbox" name="dob" class="check_it" value="dob">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Department<input type="checkbox" name="department" class="check_it" value="department_sid">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Team<input type="checkbox" name="team" class="check_it" value="team_sid">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Joining Date<input type="checkbox" name="joining_date" class="check_it" value="joined_at">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Shift Hours<input type="checkbox" name="shift_hours" class="check_it" value="user_shift_hours">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Shift Minutes<input type="checkbox" name="shift_minutes" class="check_it" value="user_shift_minutes">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Shift Start Time<input type="checkbox" name="shift_start_time" class="check_it" value="shift_start_time">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Shift End Time<input type="checkbox" name="shift_end_time" class="check_it" value="shift_end_time">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Interest<input type="checkbox" name="interest" class="check_it" value="Interest"><div class="control__indicator"></div></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Short Bio<input type="checkbox" name="short_bio" class="check_it" value="Short Bio"><div class="control__indicator"></div></label>
                                                </div>
                                            </div> -->
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Video Url<input type="checkbox" name="video_url" class="check_it" value="video_type">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Nick Name<input type="checkbox" name="nick_name" class="check_it" value="nick_name">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Hourly Rate<input type="checkbox" name="hourly_rate" class="check_it" value="hourly_rate">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Middle Name<input type="checkbox" name="middle_name" class="check_it" value="middle_name">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Hourly Technician<input type="checkbox" name="hourly_technician" class="check_it" value="hourly_technician">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Rehire Date<input type="checkbox" name="rehire_date" class="check_it" value="rehire_date">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Flat Rate Technician<input type="checkbox" name="flat_rate_technician" class="check_it" value="flat_rate_technician">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Terminated Date<input type="checkbox" name="terminated_date" class="check_it" value="terminated_date">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Semi Monthly Salary<input type="checkbox" name="semi_monthly_salary" class="check_it" value="semi_monthly_salary">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Semi Monthly Draw<input type="checkbox" name="semi_monthly_draw" class="check_it" value="semi_monthly_draw">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Terminated Reason<input type="checkbox" name="terminated_reason" class="check_it" value="terminated_reason">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <?php if (isPayrollOrPlus(true)) { ?>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                    <div class="checkbox cs_full_width">
                                                        <label class="control control--checkbox">EEOC Code<input type="checkbox" name="eeoc_code" class="check_it" value="eeoc_code">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                    <div class="checkbox cs_full_width">
                                                        <label class="control control--checkbox">Benefits Salary<input type="checkbox" name="salary_benefits" class="check_it" value="salary_benefits">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                    <div class="checkbox cs_full_width">
                                                        <label class="control control--checkbox">Workers Compensation Code<input type="checkbox" name="workers_compensation_code" class="check_it" value="workers_compensation_code">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">I Speak<input type="checkbox" name="languages_speak" class="check_it" value="languages_speak">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>


                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Marital Status<input type="checkbox" name="marital_status" class="check_it" value="marital_status">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Profile Picture URL<input type="checkbox" name="profile_picture" class="check_it" value="profile_picture">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>


                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Drivers License<input type="checkbox" name="drivers_license" class="check_it" value="drivers_license">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Gender<input type="checkbox" name="gender" class="check_it" value="gender">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Union Member<input type="checkbox" name="union_member" class="check_it" value="union_member">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Union Name<input type="checkbox" name="union_name" class="check_it" value="union_name">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Uniform Top Size<input type="checkbox" name="uniform_top_size" class="check_it" value="uniform_top_size">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Uniform Bottom Size<input type="checkbox" name="uniform_bottom_size" class="check_it" value="uniform_bottom_size">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Employment Type<input type="checkbox" name="employment_type" class="check_it" value="employment_type">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Approvers<input type="checkbox" name="approvers" class="check_it" value="approvers">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <form id="form_export_employees" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="export_employees" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <div class="row">
                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label>Access Level:</label>
                                                <div class="hr-select-dropdown autoheight">
                                                    <select data-rule-required="true" class="invoice-fields" name="access_level[]" id="access_level" multiple>
                                                        <option value="all">All Employees</option>
                                                        <?php if (!empty($access_levels)) { ?>
                                                            <?php foreach ($access_levels as $access_level) { ?>
                                                                <option value="<?php echo $access_level; ?>"><?php echo $access_level; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <option value="executive_admin">Executive Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label>Status:</label>
                                                <div class="hr-select-dropdown autoheight">
                                                    <select data-rule-required="true" class="invoice-fields" name="status[]" id="employee_status" multiple>

                                                        <option value="all">All</option>
                                                        <option value="active">Active</option>
                                                        <option value="leave">Leave</option>
                                                        <option value="suspended">Suspended</option>
                                                        <option value="retired">Retired</option>
                                                        <option value="rehired">Rehired</option>
                                                        <option value="deceased">Deceased</option>
                                                        <option value="terminated">Terminated</option>
                                                        <option value="inactive">Inactive</option>

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
                                                <button type="submit" class="btn btn-block btn-success">Export</button>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('export_employees_csv/csv_report_section'); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#employee_status').select2({
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
    <?php if ($access_level_plus || $pay_plan_flag == 1) { ?>
        $("#form_export_employees").submit(function(e) {
            var ids = [];
            $(".check_it:checked").map(function() {
                ids.push($(this).val());
            });
            $(this).append("<input type='hidden' name='test'>");
            $("input[name='test']").val(ids);

        });
    <?php } ?>
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