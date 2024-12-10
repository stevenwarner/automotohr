<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view($left_navigation); ?>
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
<b>Employee ID, Employee Number, Employee SSN, Employee Email, Employee Phone Number,  First Name, Last Name, Access Level, Job Title, Status, Course Title, Lesson Status, Course Status, Course Type, Course Taken Count, Course Start Date, Course Completion Date</b><br>
1234, E1234, 219-09-9999, jason@example.com, +1234567892, Jason, Snow, Admin, General Manager, ActiveEmployee, EHS Training, completed, passed, manual, 3, 5/8/2024, 6/9/2024,
1235, E1235, 219-08-8888, albert@example.com, +123456789, Albert, King, Manager, Manager Sales, ActiveEmployee,Respiratory Management, completed, passed, scorm, 2, 5/8/2024, 6/9/2024,
1236, E1236, 219-07-7777, nathan@example.com, +1823212129, Nathan, Quite, Hiring Manager, ActiveActiveEmployee, Sales & Finance Training, incomplete, failed, scorm, 1, 5/8/2024, 6/9/2024,
1237, E1237, 219-06-6666, allen@example.com, +1223312129, Allen, Knight, Employee, Office Assistant, InactiveEmployee, EHS Training, completed, passed, scorm, 4, 5/8/2024, 6/9/2024
1238, E1238, 219-05-5555, jack@example.com, +013212129, Jack, Brown, Employee, Team Lead, InactiveEmployee, Respiratory Management, completed, passed, scorm, 3, 5/8/2024, 6/9/2024,
                                </pre>
                            </div>
                            <?php if ($session['employer_detail']['access_level_plus'] || $session['employer_detail']['pay_plan_flag']) { ?>
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
                                                    <label class="control control--checkbox"> Employee Id <input type="checkbox" class="check_it" name="employee_id" value="employee_id">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Employee Number<input type="checkbox" class="check_it" name="employee_no" value="employee_no">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Employee SSN<input type="checkbox" class="check_it" name="employee_ssn" value="employee_ssn">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Employee Email<input type="checkbox" class="check_it" name="employee_email" value="employee_email">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Employee Phone Number<input type="checkbox" class="check_it" name="employee_phone_number" value="employee_PhoneNumber">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">First Name<input type="checkbox" class="check_it" name="first_name" value="first_name">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Last Name<input type="checkbox" class="check_it" name="last_name" value="last_name">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Access Level<input type="checkbox" class="check_it" name="access_level" value="access_level">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Job Title<input type="checkbox" class="check_it" name="job_title" value="job_title">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Course Title<input type="checkbox" class="check_it" name="course_title" value="course_title">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Lesson Status,<input type="checkbox" class="check_it" name="lesson_status" value="lesson_status">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Course Status<input type="checkbox" class="check_it" name="course_status" value="course_status">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Course Type<input type="checkbox" class="check_it" name="course_type" value="course_type">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Course Taken Count<input type="checkbox" class="check_it" name="course_taken_count" value="course_taken_count">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Course Start Date<input type="checkbox" class="check_it" name="course_started_date" value="course_started_date">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                <div class="checkbox cs_full_width">
                                                    <label class="control control--checkbox">Course Completion Date<input type="checkbox" class="check_it" name="course_completion_date"  value="course_completion_date">
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
                                    <form id="form_export_courses" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="export_courses" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $companyId; ?>" />
                                        <div class="row">
                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label>Course Title:</label>
                                                <div class="hr-select-dropdown autoheight">
                                                    <select data-rule-required="true" class="invoice-fields" name="courses[]" id="jsCourses" multiple>
                                                        <option value="0">All Courses</option>
                                                        <?php if (!empty($companyCourses)) { ?>
                                                            <?php foreach ($companyCourses as $course) { ?>
                                                                <option value="<?php echo $course['sid']; ?>"><?php echo $course['course_title']; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <label>Course Status:</label>
                                                <div class="hr-select-dropdown autoheight">
                                                    <select data-rule-required="true" class="invoice-fields" name="courseStatus" id="jsCourseStatus">
                                                        <option value="all">All</option>
                                                        <option value="Passed">Passed</option>
                                                        <option value="failed">Failed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 12px;">

                                            <!--  -->
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                
                                            </div>

                                            <!--  -->
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                
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
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#jsCourses').select2({
        closeOnSelect: false
    });

    $('#jsCourseStatus').select2({
        closeOnSelect: false
    });

    $("#check_all").click(function() {
        $(".check_it").prop("checked", this.checked);
    });

    $(document).ready(function() {
        $('#jsCourses').select2('val', ['all']);
        $('#form_export_courses').validate();
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
    <?php if ($session['employer_detail']['access_level_plus'] || $session['employer_detail']['pay_plan_flag']) { ?>
        $("#form_export_courses").submit(function(e) {
            var ids = [];
            $(".check_it:checked").map(function() {
                ids.push($(this).val());
            });
            //
            if (!ids.length) {
                $(".check_it").map(function() {
                    ids.push($(this).val());
                });
            }
            //
            $(this).append("<input type='hidden' name='columns'>");
            $("input[name='columns']").val(ids);

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