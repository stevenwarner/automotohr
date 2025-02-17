<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/select2.css') ?>" />
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-bar-chart"></i><?php echo $title; ?></h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>">
                        <i class="fa fa-long-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
                <!-- ****************** -->
                <div class="row">
                    <div class="dashboard-conetnt-wrp">

                    <?php if ($haveSubordinate == 'yes') { ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section-inner">
                                    <div class="heading-sec">
                                        <div class="row">
                                            <!-- Department Filter  -->
                                            <?php if (!empty($subordinateInfo['departments'])) { ?>
                                                <div class="col-xs-12 col-md-4">
                                                    <label><strong>Department(s)</strong></label>
                                                    <select id="jsSubordinateDepartments" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php foreach ($subordinateInfo['departments'] as $departments) { ?>
                                                            <option value="<?php echo $departments["sid"]; ?>"><?php echo $departments["name"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                            <!-- Team Filter -->
                                            <div class="col-xs-12 <?php echo empty($subordinateInfo['departments']) ? "col-md-6" : "col-md-4"; ?>">
                                                <?php if (!empty($subordinateInfo['teams'])) { ?>
                                                    <label><strong>Team(s)</strong></label>
                                                    <select id="jsSubordinateTeams" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php foreach ($subordinateInfo['teams'] as $teams) { ?>
                                                            <option value="<?php echo $teams["sid"]; ?>"><?php echo $teams["name"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <!-- Employee Filter  -->
                                            <div class="col-xs-12 <?php echo empty($subordinateInfo['departments']) ? "col-md-6" : "col-md-4"; ?>">
                                                <?php if (!empty($subordinateInfo["employees"])) { ?>
                                                    <label><strong>Employee</strong></label>
                                                    <select id="jsSubordinateEmployees" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php foreach ($subordinateInfo["employees"] as $employee) { ?>
                                                            <? //if ($employee['job_title_sid'] > 0) { 
                                                            ?>
                                                            <option value="<?php echo $employee['employee_sid']; ?>"><?php echo $employee['full_name']; ?></option>
                                                        <?php } ?>
                                                        <?php //} 
                                                        ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <!-- Courses Filter  -->
                                            <div class="col-xs-12  col-md-4">
                                                <?php if (!empty($subordinateInfo["courses"])) { ?>
                                                    <label><strong>Courses</strong></label>
                                                    <select id="jsSubordinateCourses" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php foreach ($subordinateInfo["courses"] as $course) { ?>
                                                            <option value="<?php echo $course['sid']; ?>"><?php echo $course['course_title']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-md-3" style="float: right;">
                                                <label><strong>&nbsp;</strong></label>
                                                <div class="row">
                                                    <!-- filters buttons -->
                                                    <div class="col-lg-6">
                                                        <a href="javascript:;" class="btn btn-info btn-block csRadius5 jsSearchEmployees csF16">
                                                            <i class="fa fa-filter" aria-hidden="true"></i> Search
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <a href="<?php echo base_url('lms_subordinate_report/'.$companyId); ?>" class="btn btn-black btn-block csRadius5 csF16">
                                                            <i class="fa fa-times" aria-hidden="true"></i> Clear
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 20px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Team Report: Visual Representation</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="panel panel-default">
                                            <div class="panel-body" style="background: #e6e7ff; border-radius: 4px;">
                                                <?php
                                                $total = $subordinateInfo["total_course"];
                                                $completed = $subordinateInfo["completed"];
                                                $unCompleted = $total - $completed;
                                                $percentage = $total != 0 ? round(($completed / $total * 100), 0, PHP_ROUND_HALF_UP) . '%' : '0%';
                                                ?>
                                                <h2 style="">Completed Courses: <span style="color: #ef6c34;" id="jsOverViewTrainings"><?= $percentage ?></span></h2>
                                                <h3 style="margin-bottom: 0px;"><span id="jsOverViewCourseDueSoon"><?= $unCompleted ?></span> Courses Pending</h3>
                                                <h3 style="margin-top: 0px;"><span id="jsOverViewCourseTotal"><?= $total ?></span> Courses Total</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="container1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Team Report</strong>
                            </div>
                            <div class="panel-body">

                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

                                    </div>

                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right">
                                        <button type="button" class="btn btn-info btn-orange csRadius5 csF16 jsSendReminderEmail">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i> Send Reminder Email
                                        </button>
                                    </div>
                                </div>

                                <div class="section-inner">
                                    <div class="heading-sec">
                                        <?php if ($haveSubordinate == "yes") { ?>
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">

                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover">
                                                            <thead style="background-color: #fd7a2a;">
                                                                <tr>
                                                                    <th>
                                                                        <label class="control control--checkbox">
                                                                            <input type="checkbox" class="jsCheckAll" />
                                                                            <div class="control__indicator" style="margin-top: -10px;"></div>
                                                                        </label>
                                                                    </th>
                                                                    <th>Employee Name</th>
                                                                    <th>Department</th>
                                                                    <th>Team</th>
                                                                    <th>Assigned Course(s)</th>
                                                                    <th>Completed Course(s)</th>
                                                                    <th>Courses in Progress</th>
                                                                    <th>Ready To Start</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="jsSubordinateList">
                                                                <?php if (!empty($subordinateInfo["employees"])) { ?>
                                                                    <?php foreach ($subordinateInfo["employees"] as $employee) { ?>
                                                                        <?php
                                                                        $teamId = $employee['team_sid'];
                                                                        $departmentId = $employee['department_sid'];
                                                                        $assignCourses = !empty($employee['assign_courses']) ? explode(",", $employee['assign_courses']) : [];
                                                                        $courseCount = !empty($assignCourses) ? count($assignCourses) : 0;
                                                                        $courseCountText = $courseCount > 1 ? $courseCount . " courses assigned" : $courseCount . " course assigned";
                                                                        $departmentName =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["department_name"] : "N/A";
                                                                        $teamName =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["name"] : "N/A";
                                                                        //
                                                                        $completedCoursesPercentage = ($employee['coursesInfo']['completed'] / $employee['coursesInfo']['total_course']) * 100;
                                                                        //
                                                                        $rowColor = "bg-danger";
                                                                        //
                                                                        if ($completedCoursesPercentage == "100") {
                                                                            $rowColor = "bg-success";
                                                                        } else if ($completedCoursesPercentage < "90" && $completedCoursesPercentage > "1") {
                                                                            $rowColor = "bg-warning";
                                                                        }
                                                                        ?>
                                                                        <tr class="<?php echo $rowColor; ?>">
                                                                            <td>
                                                                                <?php if ($courseCount > 0 && $employee['coursesInfo']['total_course'] != $employee['coursesInfo']['completed']) { ?>
                                                                                    <label class="control control--checkbox">
                                                                                        <input type="checkbox" class="jsSelectSubordinate" name="employees_ids[]" value="<?php echo $employee['employee_sid']; ?>" />
                                                                                        <div class="control__indicator"></div>
                                                                                    </label>
                                                                                <?php } else { ?>
                                                                                    <label class="control control--checkbox">
                                                                                        <input type="checkbox" value="" disabled />
                                                                                        <div class="control__indicator"></div>
                                                                                    </label>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td class="_csVm js-employee-name">
                                                                                <div class="row">
                                                                                    <div class="col-sm-3">
                                                                                        <img style="width: 80px; height: 80px; border-radius: 50% !important;" src="<?= $employee['profile_picture_url']; ?>" alt="" />
                                                                                    </div>
                                                                                    <div class="col-sm-9">
                                                                                        <p class="text-small weight-6 myb-0" style="font-size: 20px;">
                                                                                            <?= $employee['only_name']; ?>
                                                                                        </p>
                                                                                        <p class="text-small">
                                                                                            <?= $employee['designation']; ?>
                                                                                        </p>
                                                                                        <p class="text-small">
                                                                                            <?= $employee['email']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>        
                                                                            </td>
                                                                            <td class="_csVm"><?php echo $departmentName; ?></td>
                                                                            <td class="_csVm"><?php echo $teamName; ?></td>
                                                                            <td class="_csVm text-center"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['total_course'] : 0; ?></td>
                                                                            <td class="_csVm text-center"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['completed'] : 0; ?></td>
                                                                            <td class="_csVm text-center"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['started'] : 0; ?></td>
                                                                            <td class="_csVm text-center"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['ready_to_start'] : 0; ?></td>
                                                                        </tr>
                                                                        <?php //} 
                                                                        ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <p class="alert alert-info text-center">
                                                                                No employee(s) found.
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php  } else { ?>
                                            <?php echo "No record found!"; ?>
                                        <?php  } ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php } else { ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="alert alert-info text-center">
                                    No subordinate employee found.
                                </p>
                            </div>
                        </div>

                    <?php } ?>

                    </div>
                </div>
                <!-- ****************** -->
            </div>
        </div>
    </div>
</div>


<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait ...
        </div>
    </div>
</div>


<style>
    ._csVm {
        vertical-align: middle !important;
    }

    .stepText2 {
        text-align: center;
    }

    .btn-black {
        background-color: #000;
        color: #fff;
    }

    .btn-black:hover,
    .btn-black:active {
        background-color: #333;
        color: #fff;
    }

    .panel-heading {
        background-color: #81b431 !important;
        color: #fff !important;
    }

    .bg-success {
        background-color: #dff0d8 !important;
    }

    .bg-warning {
        background-color: #fcf8e3 !important;
    }

    .bg-danger {
        background-color: #f2dede !important;
    }
</style>

<script src="<?php echo base_url('assets/js/select2.js') ?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // set the xhr
	    let XHR = null;
        var uniqueKey = "<?php echo $uniqueKey; ?>";
        var haveSubordinate = "<?php echo $haveSubordinate; ?>";
        var employeeId = <?php echo $employee_sid; ?>;
        var companyId = <?php echo $companyId; ?>;
        var departments = "<?php echo $filters['departments']; ?>";
        var teams = "<?php echo $filters['teams']; ?>";
        var employees = "<?php echo $filters['employees']; ?>";
        var courses = "<?php echo $filters['courses']; ?>";
        var baseURL = "<?= base_url(); ?>";
        var totalCourses = <?= $subordinateInfo["total_course"] ?? 0 ?>;
        var dueSoonCourses = <?= $subordinateInfo["expire_soon"] ?? 0 ?>;
        var pastDueCourses = <?= $subordinateInfo["expired"] ?? 0 ?>;
        var inProgressCourses = <?= $subordinateInfo["started"] ?? 0 ?>;
        var completedCourses = <?= $subordinateInfo["completed"] ?? 0 ?>;
        var readyToStart = <?= $subordinateInfo["ready_to_start"] ?? 0 ?>;

        // set the default filter
        let filterObj = {
            teams: "all",
            employees: "all",
            courses: "all",
        };
        //
        let timeOffDateFormatWithTime = "MMM DD YYYY, ddd";
        //
        // load select2 on department
        $("#jsSubordinateDepartments").select2({
            closeOnSelect: false,
        });
        //
        if (departments !== undefined) {
            if ($("#jsSubordinateDepartments").length) {
                $("#jsSubordinateDepartments").select2(
                    "val",
                    departments.split(",")
                );
            }
        }
        // load select2 on teams
        $("#jsSubordinateTeams").select2({
            closeOnSelect: false,
        });
        //
        if (teams !== undefined) {
            if ($("#jsSubordinateTeams").length) {
                $("#jsSubordinateTeams").select2("val", teams.split(","));
            }
        }
        // load select2 on employees
        $("#jsSubordinateEmployees").select2({
            closeOnSelect: false,
        });
        //
        if (employees !== undefined) {
            if ($("#jsSubordinateEmployees").length) {
                $("#jsSubordinateEmployees").select2("val", employees.split(","));
            }
        }
        // load select2 on courses
        $("#jsSubordinateCourses").select2({
            closeOnSelect: false,
        });
        //
        if (courses !== undefined) {
            if ($("#jsSubordinateCourses").length) {
                $("#jsSubordinateCourses").select2("val", courses.split(","));
            }
        }
        //
        /**
         * Get Employee courses
         */
        $(document).on("click", ".jsSearchEmployees", function (event) {
            // stop the default behavior
            event.preventDefault();
            var selectedDepartments = $("#jsSubordinateDepartments").val();
            var selectedTeams = $("#jsSubordinateTeams").val();
            var selectedEmployees = $("#jsSubordinateEmployees").val();
            var selectedCourses = $("#jsSubordinateCourses").val();
            //
            // check and abort previous calls
            if (XHR !== null) {
                XHR.abort();
            }
            // show the loader
            ml(true, "jsPageLoader");
            // make the call
            XHR = $.ajax({
                url: baseURL + "lms_subordinate_report/"+companyId,
                method: "GET",
                data: {
                    departments: selectedDepartments,
                    teams: selectedTeams,
                    employees: selectedEmployees,
                    courses: selectedCourses,
                },
            })
                .success(function (response) {
                    // empty the call
                    XHR = null;
                    //
                    var html = "";
                    //
                    $.each(response.employees, function (index, employee) {
                        var teamId = employee.team_sid;
                        var departmentId = employee.department_sid;
                        var assignCourses = employee.assign_courses
                            ? employee.assign_courses.split(",")
                            : [];
                        var courseCount = assignCourses ? assignCourses.length : 0;
                        var courseCountText =
                            courseCount > 1
                                ? courseCount + " courses assign"
                                : courseCount + " course assign";
                        var departmentName = "N/A";
                        var teamName = "N/A";

                        var completedCoursesPercentage = (employee.coursesInfo.completed / employee.coursesInfo.total_course) * 100;

                        var rowColor = "bg-danger";
                        //
                        if (completedCoursesPercentage == "100") {
                            rowColor = "bg-success";
                        } else if (completedCoursesPercentage < "90" && completedCoursesPercentage > "1") {
                            rowColor = "bg-warning";
                        }

                        html += `<tr class="js-tr ${rowColor}">`;
                        html += `<td>`;
                        if (employee.coursesInfo.total_course > 0 && employee.coursesInfo.total_course != employee.coursesInfo.completed) { 
                        html += `	<label class="control control--checkbox">`;
                        html += `		<input type="checkbox" name="employees_ids[]" value="${employee["employee_sid"]}" />`;
                        html += `		<div class="control__indicator"></div>`;
                        html += `	</label>`;
                        }
                        html += `</td>`;
                        html += `<td class="_csVm">`;
                        html += `	<div class="row">`;
                        html += `		<div class="col-sm-3">`;
                        html += `			<img style="width: 80px; height: 80px; border-radius: 50% !important;" src="${employee['profile_picture_url']}" alt="" />`;
                        html += `		</div>`;
                        html += `		<div class="col-sm-9">`;
                        html += `			<p class="text-small weight-6 myb-0" style="font-size: 20px;">`;
                        html += 				employee['only_name']
                        html += `			</p>`;
                        html += `			<p class="text-small">`;
                        html += 				employee['designation']
                        html += `			</p>`;
                        html += `			<p class="text-small">`;
                        html += 				employee['email']
                        html += `			</p>`;
                        html += `		</div>`;
                        html += `	</div>`; 

                        html += `</td>`;
                        html += `<td class="_csVm">${employee.department_name}</td>`;
                        html += `<td class="_csVm">${employee.team_name}</td>`;
                        html += `<td class="_csVm">${
                            employee.coursesInfo
                                ? employee.coursesInfo.total_course
                                : 0
                        }</td>`;
                        html += `<td class="_csVm">${
                            employee.coursesInfo
                                ? employee.coursesInfo.completed
                                : 0
                        }</td>`;
                        html += `<td class="_csVm">${
                            employee.coursesInfo ? employee.coursesInfo.started : 0
                        }</td>`;
                        html += `<td class="_csVm">${
                            employee.coursesInfo
                                ? employee.coursesInfo.ready_to_start
                                : 0
                        }</td>`;
                        html += `</tr>`;
                    });
                    //
                    $("#jsSubordinateList").html(html);
                    //
                    ml(false, "jsPageLoader");
                })
                .fail(handleErrorResponse)
                .always(function () {
                    // empty the call
                    XHR = null;
                    // hide the loader
                    ml(false, "jsPageLoader");
                });
        });
        //
        $(document).on("click", ".jsCheckAll", selectAllInputs);
        $(document).on("click", ".jsSelectSubordinate", selectSingleInput);

        // Select all input: checkbox
        function selectAllInputs() {
            $(".jsSelectSubordinate").prop("checked", $(this).prop("checked"));
        }

        // Select single input: checkbox
        function selectSingleInput() {
            $(this)
                .find('input[name="employees_ids[]"]')
                .prop(
                    "checked",
                    !$(this).find('input[name="employees_ids[]"]').prop("checked")
                );
            $(".jsCheckAll").prop(
                "checked",
                $(".jsSelectSubordinate").length ==
                    $(".jsSelectSubordinate:checked").length
            );
        }

        function get_all_selected_employees() {
            var tmp = [];
            $.each($('input[name="employees_ids[]"]:checked'), function () {
                var obj = {};
                obj.employee_sid = parseInt($(this).val());
                obj.employee_name = $(this)
                    .closest("tr")
                    .find("td.js-employee-name")
                    .text();

                tmp.push(obj);
            });
            return tmp;
        }

        $(document).on("click", ".jsSendReminderEmail", function (e) {
            e.preventDefault();
            //
            senderList = get_all_selected_employees();
            //
            if (senderList.length === 0) {
                alertify.alert(
                    "ERROR!",
                    "Please select at least one employee to start the process."
                );
                return;
            } else {
                //
                alertify.confirm(
                    'Confirmation!',
                    "Do you really want to send email reminders to the selected employees?",
                    function () {
                        //
                        sendEmailToEmployees(senderList);
                    },
                    function () {

                    }
                );
            }
           
        });

        function sendEmailToEmployees(senderList) {
            // check and abort previous calls
            if (XHR !== null) {
                XHR.abort();
            }
            // show the loader
            ml(true, "jsPageLoader");
            // make the call
            //
            XHR = $.ajax({
                url: baseURL + "lms_company_report/emailReminder/bulk",
                method: "POST",
                data: {
                    employeeList: senderList,
                    companySid: '<?php echo $companyId ?>',
                },
            })
                .success(function (response) {
                    // empty the call
                    XHR = null;
                    //
                    ml(false, "jsPageLoader");
                    //
                    return alertify.alert(
                        "SUCCESS!",
                        "You have successfully sent an email reminder to selected employees.",
                        function () {
                            location.reload();
                        }
                    );
                })
                .fail(handleErrorResponse)
                .done(function (response) {
                    // empty the call
                    XHR = null;
                });
        }

        function loadMyAssignedCoursesBarChart() {
            Highcharts.chart("container1", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "left",
                    text: "Assigned Course(s) Bar Chart",
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    },
                },
                xAxis: {
                    type: "category",
                    labels: {
                        style: {
                            fontSize: "12px", // Change this to your desired size
                        },
                    },
                },
                yAxis: {
                    title: {
                        text: "Total number of assigned course(s)",
                    },
                    labels: {
                        style: {
                            fontSize: "12px", // Change this to your desired size
                        },
                    },
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y}",
                        },
                    },
                },

                tooltip: {
                    headerFormat:
                        '<span style="font-size:14px">{series.name}</span><br>',
                    pointFormat:
                        '<span style="font-size:12px; color:{point.color}">{point.name}:</span> <b style="font-size:12px">{point.y} course(s)</b>',
                },

                series: [
                    {
                        name: "Course(s)",
                        colorByPoint: true,
                        data: [
                            {
                                name: "Assigned ",
                                color: "#6B8ABB",
                                y: totalCourses,
                            },
                            {
                                name: "Pending",
                                color: "#ff834e",
                                y: totalCourses - completedCourses,
                            },
                            {
                                name: "Ready To Start",
                                color: "#2caffe",
                                y: readyToStart,
                            },
                            {
                                name: "In Progress",
                                color: "#544fc5",
                                y: inProgressCourses,
                            },
                            {
                                name: "Passed",
                                color: "#00e272",
                                y: completedCourses,
                            },
                            {
                                name: "Past Due",
                                color: "#fa4b42",
                                y: pastDueCourses,
                            },
                            {
                                name: "Due Soon",
                                color: "#feb56a",
                                y: dueSoonCourses,
                            },
                        ],
                        dataLabels: {
                            style: {
                                fontSize: "12px", // Change this to your desired size
                            },
                        },
                    },
                ],
            });
        }

        function ml(action, id) {
            //
            if (action) {
                $(".jsIPLoader[data-page='" + id + "']").show();
            } else {
                $(".jsIPLoader[data-page='" + id + "']").hide();
            }
        }

        ml(false, "jsPageLoader");
        loadMyAssignedCoursesBarChart(); 
    })
</script>