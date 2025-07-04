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

                        <?php if (!empty($companyReport["departments_report"]) || !empty($companyReport["CoursesList"])) { ?>

                            <div class="row">
                                <div class="applicant-reg-date">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                        <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                            <div class="row">
                                                <!-- Department Filter  -->
                                                <div class="col-xs-12 col-md-4">
                                                    <label><strong>Department(s)</strong></label>
                                                    <select id="jsCompanyDepartments" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php if (!empty($filterData['departments'])) { ?>
                                                            <?php foreach ($filterData['departments'] as $departments) { ?>
                                                                <option value="<?php echo $departments["sid"]; ?>"><?php echo $departments["name"]; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <!-- Courses Filter -->
                                                <div class="col-xs-12 col-md-4">
                                                    <label><strong>Courses(s)</strong></label>
                                                    <select id="jsCompanyCourses" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php if (!empty($filterData['courses'])) { ?>
                                                            <?php foreach ($filterData['courses'] as $course) { ?>
                                                                <option value="<?php echo $course["sid"]; ?>"><?php echo $course["course_title"]; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <!-- Employee Filter  -->
                                                <div class="col-xs-12 col-md-4">
                                                    <label><strong>Employee(s)</strong></label>
                                                    <select id="jsSubordinateEmployees" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php if (!empty($filterData["employees"])) { ?>
                                                            <?php foreach ($filterData["employees"] as $employee) { ?>
                                                                <?php if ($executiveUserId != $employee['sid']) { ?>
                                                                    <option value="<?php echo $employee['sid']; ?>">
                                                                        <?php echo remakeEmployeeName([
                                                                            'first_name' => $employee['first_name'],
                                                                            'last_name' => $employee['last_name'],
                                                                            'access_level' => $employee['access_level'],
                                                                            'timezone' => isset($employee['timezone']) ? $employee['timezone'] : '',
                                                                            'access_level_plus' => $employee['access_level_plus'],
                                                                            'is_executive_admin' => $employee['is_executive_admin'],
                                                                            'pay_plan_flag' => $employee['pay_plan_flag'],
                                                                            'job_title' => $employee['job_title'],
                                                                        ]); ?>
                                                                    </option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row0">
                                                <!-- Filter Buttons  -->
                                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8"></div>

                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 p0">
                                                    <div class="report-btns text-right">
                                                        <!-- <div class="row">
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> -->
                                                        <button type="button" class="orange-btn" onclick="jsApplyDateFilters();">
                                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                                            &nbsp;
                                                            Apply Filter
                                                        </button>
                                                        <!-- </div>
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> -->
                                                        <button type="button" class="black-btn" onclick="jsClearDateFilters();">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                            &nbsp;
                                                            Clear Filter
                                                        </button>
                                                        <!-- </div> -->
                                                        <button type="submit" id="jsFetchCSVReport" class="dn"></button>
                                                        <!-- </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color: #fd7a2a!important;">
                                    <strong>Overview</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <figure class="highcharts-figure">
                                                <div id="jsCompanyEmployees"></div>
                                                <p class="highcharts-description">
                                                    This graph shows the distribution of employees who have been assigned courses versus those who have not been assigned courses yet.
                                                </p>
                                            </figure>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <figure class="highcharts-figure">
                                                <div id="jsDepartmentsGraph"></div>
                                                <p class="highcharts-description">
                                                    This graph illustrates the distribution of employees in different departments who have been assigned courses versus those who have not been assigned courses yet.
                                                </p>
                                                <table class="dn" id="datatable">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Employee(s) with Assigned Courses</th>
                                                            <th>Employee(s) with NO Courses Assigned</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <? if (!empty($companyReport["departments_report"])) { ?>
                                                            <?php foreach ($companyReport["departments_report"] as $department) { ?>
                                                                <tr>
                                                                    <th><?php echo $department['name']; ?></th>
                                                                    <td><?php echo $department['employee_have_courses']; ?></td>
                                                                    <td><?php echo $department['employee_not_have_courses']; ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <? } ?>
                                                    </tbody>
                                                </table>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color: #fd7a2a!important;">
                                    <strong>Progress</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <figure class="highcharts-figure">
                                                <div id="jsDepartmentsCoursesProgressGraph"></div>
                                                <p class="highcharts-description">
                                                    This graph illustrates the count of completed and pending courses for employees across different departments.
                                                </p>
                                                <table class="dn" id="jsDepartmentsCoursesProgressGraphTable">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Completed</th>
                                                            <th>Pending</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <? if (!empty($companyReport["departments_report"])) { ?>
                                                            <?php foreach ($companyReport["departments_report"] as $department) { ?>
                                                                <tr>
                                                                    <th><?php echo $department['name']; ?></th>
                                                                    <td><?php echo $department['completed_courses'] > 0 ? (round(($department['completed_courses'] / $department['total_courses']) * 100,0,PHP_ROUND_HALF_UP)) : 0; ?></td>
                                                                    <td><?php echo $department['pending_courses'] > 0 ? (round(($department['pending_courses'] / $department['total_courses']) * 100,0,PHP_ROUND_HALF_UP)) : 0; ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <? } ?>
                                                    </tbody>
                                                </table>
                                            </figure>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <figure class="highcharts-figure">
                                                <div id="jsCoursesProgressGraph"></div>
                                                <p class="highcharts-description">
                                                    This graph illustrates the count of employees who have completed and have pending courses across the entire company.
                                                </p>
                                                <table class="dn" id="jsCoursesProgressGraphTable">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Employee(s) with Completed Course</th>
                                                            <th>Employee(s) with Pending Course</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <? if (!empty($companyReport["CoursesList"])) { ?>
                                                            <?php foreach ($companyReport["CoursesList"] as $course) { ?>
                                                                <tr>
                                                                    <th><?php echo $course['course_title']; ?></th>
                                                                    <td><?php echo $course['assign_employee_completed_count']; ?></td>
                                                                    <td><?php echo $course['assign_employee_pending_count']; ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <? } ?>
                                                    </tbody>
                                                </table>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color: #fd7a2a!important;">
                                    <strong>Report</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="row" style="margin-bottom:10px;">
                                        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                                            <div class="row" style="margin: 5px 5px;">
                                                <div class="col-lg-2 bg-success" style="padding: 16px;"></div>
                                                <div class="col-lg-10" style="padding: 6px; font-weight: 700;">The employee has successfully completed 100% of their required courses.</div>
                                            </div>
                                            <div class="row" style="margin: 5px 5px;">
                                                <div class="col-lg-2 bg-warning" style="padding: 16px;"></div>
                                                <div class="col-lg-10" style="padding: 6px; font-weight: 700;">The employee has made progress by starting their assigned courses, but still has work left to complete them.</div>
                                            </div>

                                            <div class="row" style="margin: 5px 5px;">
                                                <div class="col-lg-2 bg-danger" style="padding: 16px;"></div>
                                                <div class="col-lg-10" style="padding: 6px; font-weight: 700;">The employee hasn't started their assigned courses yet, or possibly hasn't been assigned any.</div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <button type="button" class="form-btn" onclick="excel_export()" style="background-color: #1032c3;">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                Export CSV
                                            </button>

                                            <a type="button" class="form-btn margin-top" id="jsSimpleReportURL" target="_blank" href="<?php echo base_url("lms/courses/simple_company_report"); ?>" style="background-color: #1032c3;">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                Download Report
                                            </a>

                                            <button type="button" class="form-btn  jsSendReminderEmail" style="margin-top: 10px; width:100%;margin-left:0px; background-color: #fd7a2a;">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Send Reminder Email
                                            </button>
                                        </div>
                                    </div>


                                    <div class="hr-box" style="border-bottom: 0px;margin-bottom: -10px; padding-left:12px;">
                                        <div class="hr-innerpadding" style="margin-bottom: 0px">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" class="jsCheckAll" />
                                                <div class="control__indicator" style="margin-top: -10px;background: #afaaaa;"> </div>
                                            </label>
                                        </div>
                                    </div>


                                    <?php foreach ($companyReport['departments_report'] as $department) { ?>
                                        <?php if (!empty($department["employees"])) { ?>
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead style="background-color: #fd7a2a !important;">
                                                                <tr>
                                                                    <th colspan="6"><?php echo $department["name"]; ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Employee Name</th>
                                                                    <th>Assigned Course(s)</th>
                                                                    <th>Pending Course(s)</th>
                                                                    <th>Completed Course(s)</th>
                                                                    <th>Completion Percentage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($department['employees'] as $employee) { ?>
                                                                    <?php
                                                                    $employeeInfo = get_employee_profile_info($employee);
                                                                    $assignCourses = $companyReport["EmployeeList"][$employee]["courses_statistics"]['courseCount'];

                                                                    $courseCount = !empty($assignCourses) ? count($assignCourses) : 0;


                                                                    $pendingCourses = $companyReport["EmployeeList"][$employee]["courses_statistics"]['pendingCount'];
                                                                    $completedCourses = $companyReport["EmployeeList"][$employee]["courses_statistics"]['completedCount'];
                                                                    $completedCoursesPercentage = $companyReport["EmployeeList"][$employee]["courses_statistics"]['percentage'];
                                                                    //
                                                                    $assignText = $assignCourses > 1 ? $assignCourses . " courses assigned" : $assignCourses . " courses assigned";
                                                                    $pendingText = $pendingCourses > 1 ? $pendingCourses . " courses pending" : $pendingCourses . " courses pending";
                                                                    $completedText = $completedCourses > 1 ? $completedCourses . " courses completed" : $completedCourses . " courses completed";
                                                                    //
                                                                    $rowColor = "bg-danger";
                                                                    //
                                                                    if ($completedCoursesPercentage == "100") {
                                                                        $rowColor = "bg-success";
                                                                    } else if ($completedCoursesPercentage < "99" && $completedCoursesPercentage > "1") {
                                                                        $rowColor = "bg-warning";
                                                                    }


                                                                    ?>
                                                                    <tr class="<?php echo $rowColor; ?>">
                                                                        <td class="_csVm">
                                                                            <div class="row">
                                                                                <div class="col-sm-1">
                                                                                    <?php if ($courseCount > 0 && $companyReport["EmployeeList"][$employee]['courses_statistics']['courseCount'] != $companyReport["EmployeeList"][$employee]['courses_statistics']['completedCount']) { ?>
                                                                                        <label class="control control--checkbox">
                                                                                            <input type="checkbox" class="jsSelectSubordinate" name="employees_ids[]" value="<?php echo $employee ?>" />
                                                                                            <div class="control__indicator" style="background: #afaaaa;"></div>
                                                                                        </label>
                                                                                    <?php } else { ?>
                                                                                        <label class="control control--checkbox">
                                                                                            <input type="checkbox" value="" disabled />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    <?php } ?>
                                                                                </div>

                                                                                <div class="col-sm-3">
                                                                                    <img style="width: 80px; height: 80px; border-radius: 50% !important;" src="<?= getImageURL($employeeInfo["profile_picture"]); ?>" alt="" />
                                                                                </div>
                                                                                <div class="col-sm-8">

                                                                                    <p class="text-small weight-6 myb-0" style="font-size: 20px;">
                                                                                        <?= remakeEmployeeName($employeeInfo, true, true); ?>
                                                                                    </p>
                                                                                    <p class="text-small">
                                                                                        <?= remakeEmployeeName($employeeInfo, false); ?>
                                                                                    </p>
                                                                                    <p class="text-small">
                                                                                        <?= $employeeInfo['email']; ?>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="_csVm"><?php echo $assignText; ?></td>
                                                                        <td class="_csVm"><?php echo $pendingText; ?></td>
                                                                        <td class="_csVm"><?php echo $completedText; ?></td>
                                                                        <td class="_csVm stepText2"><?php echo $completedCoursesPercentage . " %"; ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>

                        <?php } else { ?>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="alert alert-info text-center">
                                        No course(s) found.
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

<?php
$haveCourses = $companyReport['employee_have_courses'];
$NotHaveCourses = $companyReport['employee_not_have_courses'];
$TotalEmployees = $companyReport['total_employees'];
//
$percentageHaveCourses = (round(($haveCourses / $TotalEmployees) * 100,0,PHP_ROUND_HALF_UP));
$percentageNotHaveCourses = (round(($NotHaveCourses / $TotalEmployees) * 100,0,PHP_ROUND_HALF_UP));
?>


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
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var departments = "<?php echo $filters['departments']; ?>";
        var courses = "<?php echo $filters['courses']; ?>";
        var employees = "<?php echo $filters['employees']; ?>";
        var baseURL = "<?= base_url(); ?>";

        var originalURL = window.location.href;
        var newURL = originalURL.replace("company_report", "simple_company_report")
        $("#jsSimpleReportURL").attr('href', newURL);

        // load select2 on department
        $("#jsCompanyDepartments").select2({
            closeOnSelect: false,
        });
        //
        if (departments) {
            $('#jsCompanyDepartments').select2('val', departments.split(','));
        }
        // load select2 on teams
        $("#jsCompanyCourses").select2({
            closeOnSelect: false,
        });
        //
        if (courses) {
            $('#jsCompanyCourses').select2('val', courses.split(','));
        }
        // load select2 on employees
        $("#jsSubordinateEmployees").select2({
            closeOnSelect: false,
        });
        //
        if (employees) {
            $('#jsSubordinateEmployees').select2('val', employees.split(','));
        }

    });

    function jsApplyDateFilters() {
        var departments = $('#jsCompanyDepartments').val();
        var courses = $('#jsCompanyCourses').val();
        var employees = $('#jsSubordinateEmployees').val();

        var url = '<?php echo base_url('lms_company_report/' . $companyId); ?>';

        departments = departments != '' && departments != null && departments != undefined ? encodeURIComponent(departments) : '0';
        courses = courses != '' && courses != null && courses != undefined ? encodeURIComponent(courses) : '0';
        employees = employees != '' && employees != null && employees != undefined ? encodeURIComponent(employees) : '0';

        url += '/' + departments + '/' + courses + '/' + employees;

        window.location = url;
    }


    function excel_export() {
        $("#jsFetchCSVReport").click();
    }

    function jsClearDateFilters() {
        var url = '<?php echo base_url("lms_company_report/" . $companyId); ?>';
        window.location = url;
    }

    // //
    Highcharts.chart('jsCompanyEmployees', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: 'Company Employee(s)',
            align: 'center',
            verticalAlign: 'middle',
            y: 60
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                dataLabels: {
                    enabled: true,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        fontSize: "10"
                    }
                },
                startAngle: -90,
                endAngle: 90,
                center: ['50%', '75%'],
                size: '110%'
            }
        },
        series: [{
            type: 'pie',
            name: 'Employee(s)',
            innerSize: '50%',
            data: [
                ['Assigned Courses', <?php echo $percentageHaveCourses; ?>],
                ['No Courses Assigned', <?php echo $percentageNotHaveCourses; ?>]
            ]
        }],
        colors: ['#4CBB17', '#fd7a2a']
    });
    // //
    Highcharts.chart('jsDepartmentsGraph', {
        data: {
            table: 'datatable'
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Department Distribution'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Employee(s)'
            }
        },
        colors: ['#4CBB17', '#fd7a2a'],
        style: {
            fontSize: '15'
        }
    });
    // //
    Highcharts.chart('jsDepartmentsCoursesProgressGraph', {
        data: {
            table: 'jsDepartmentsCoursesProgressGraphTable'
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Department Courses Completion Distribution'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Employee(s)'
            }
        },
        colors: ['#4CBB17', '#fd7a2a']
    });
    // //
    Highcharts.chart('jsCoursesProgressGraph', {
        data: {
            table: 'jsCoursesProgressGraphTable'
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Courses Completion Distribution'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Employee(s)'
            }
        },
        colors: ['#4CBB17', '#fd7a2a']
    });


    //

    $(document).on("click", ".jsCheckAll", selectAllInputs);

    function selectAllInputs() {

        $('input[name="employees_ids[]').prop('checked', $(this).prop("checked"));
    }

    //
    function get_all_selected_employees() {
        var tmp = [];
        $.each($('input[name="employees_ids[]"]:checked'), function() {
            var obj = {};
            obj.employee_sid = parseInt($(this).val());
            obj.employee_name = $(this).closest('tr').find('td.js-employee-name').text();

            tmp.push(obj);
        });
        return tmp;
    }

    $(document).on('click', '.jsSendReminderEmail', function(e) {
      //  e.preventDefault();
        //
        senderList = get_all_selected_employees();
        //
        if (senderList.length === 0) {
            alertify.alert('ERROR!', 'Please select at least one employee to start the process.');
            return;
        }
        //
        alertify.confirm('Do you really want to send email reminders to the selected employees?', function() {
            //
            $('#my_loader').show();
            sendEmailToEmployees(senderList);

        });
    });


    function sendEmailToEmployees(senderList) {
        // check and abort previous calls
       
        //
        XHR = $.ajax({
                url: "<?php echo base_url() ?>" + "lms_company_report/emailReminder/bulk",
                method: "POST",
                data: {
                    employeeList: senderList,
                    companySid: '<?php echo $companyId ?>',
                },
            })
            .success(function(response) {
                // empty the call
                alertify.success(
                    "You have successfully sent an email reminder to selected employees.");

                $('#my_loader').hide();
                location.reload();

            })
            .fail(handleErrorResponse)
            .done(function(response) {
                // empty the call
            });
    }
</script>