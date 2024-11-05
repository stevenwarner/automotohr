<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view($left_navigation); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>


                    <div class="row">
                        <div class="applicant-reg-date">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <form id="form-filters" method="post" enctype="multipart/form-data" action="">
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
                                            </select>
                                        </div>
                                        <!-- Filter Buttons  -->
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8"></div>

                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4s">
                                            <div class="report-btns">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <button type="button" class="form-btn-orange" onclick="jsApplyDateFilters();">
                                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                                            &nbsp;
                                                            Apply Filter
                                                        </button>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <button type="button" class="form-btn btn-black" onclick="jsClearDateFilters();">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                            &nbsp;
                                                            Clear Filter
                                                        </button>
                                                    </div>
                                                    <button type="submit" id="jsFetchCSVReport" class="dn"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($employeeCoursesData)) { ?>


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-9 col-xs-10 ">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="" id="check_all" value="">
                                            <div class="control__indicator" style="background: #fff;"></div>
                                        </label>
                                        <p class="cs_line" style="padding-left:35px;margin-top: -12px;"><strong>Include columns in export file</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="box-view reports-filtering">
                                <form method="post" id="export" name="export" type="form/multipart">
                                    <div class="panel panel-default cs_margin_panel">
                                        <div id="collapse1" class="panel-collapse ">
                                            <div class="panel-body" style="min-height:100px;">
                                                <?php $index = 1; ?>
                                                <?php foreach ($columns as $v0) : ?>
                                                    <?php if ($index == 1) {
                                                        echo '<div class="row">';
                                                    } ?>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                            <label class="control control--checkbox" style="padding-left:35px;">
                                                                <?= $v0["value"] ?? SlugToString($v0["slug"]); ?>
                                                                <input type="checkbox" class="check_it jsExtraColumn" data-target="<?= stringToSlug($v0["slug"], ""); ?>" name="columns[<?= $v0["slug"]; ?>]" <?= $v0["selected"] ? "checked" : "" ?> />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php if ($index == 4) {
                                                        echo "</div>";
                                                        $index = 0;
                                                    } ?>
                                                    <?php $index++; ?>
                                                <?php endforeach; ?>

                                                <div class="row">
                                                    <?php if ($extraColumns) : ?>
                                                        <?php foreach ($extraColumns as $v0) : ?>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                <div class="checkbox cs_full_width" style="width: 100%;">
                                                                    <label class="control control--checkbox" style="padding-left:35px;">
                                                                        <?= $v0; ?>
                                                                        <input type="checkbox" class="check_it jsExtraColumn" data-target="<?= stringToSlug($v0, ""); ?>" name="columns[<?= stringToSlug($v0, "_"); ?>]">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <input type="submit" name="submit" class="submit-btn pull-right" value="Export">

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <div class="panel panel-default" style="display: none;">
                            <div class="panel-heading">
                                <strong>Report</strong>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive" id="print_div">
                                    <table class=" table table-bordered horizontal-scroll">
                                        <thead style="background-color: #fd7a2a;">
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Employee Number</th>
                                                <th>Employee SSN</th>
                                                <th>Employee Email</th>
                                                <th>Employee Phone Number</th>
                                                <th>Course Title</th>
                                                <th>Lesson Status</th>
                                                <th>Course Status</th>
                                                <th>Course Type</th>
                                                <th>Course Taken Count</th>
                                                <th>Course Start Date</th>
                                                <th>Course End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($employeeCoursesData as $rowData) { ?>
                                                <tr>
                                                    <td><b><?php echo $rowData["sid"]; ?></b></td>
                                                    <td><?php echo $rowData["employee_number"]; ?></td>
                                                    <td><?php echo $rowData["ssn"]; ?></td>
                                                    <td><?php echo $rowData["email"]; ?></td>
                                                    <td><?php echo $rowData["PhoneNumber"]; ?></td>
                                                    <td><?php echo $rowData["course_title"]; ?></td>
                                                    <td><?php echo $rowData["lesson_status"]; ?></td>
                                                    <td><?php echo $rowData["course_status"]; ?></td>
                                                    <td><?php echo $rowData["course_type"]; ?></td>
                                                    <td><?php echo $rowData["course_taken_count"]; ?></td>
                                                    <td><?php echo formatDateToDB($rowData['course_start_period'], DB_DATE, DATE); ?></td>
                                                    <td><?php echo formatDateToDB($rowData['course_end_period'], DB_DATE, DATE); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
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

<script type="text/javascript">
    $(document).ready(function() {
        var courses = "<?php echo $filters['courses']; ?>";
        var employees = "<?php echo $filters['employees']; ?>";
        var baseURL = "<?= base_url(); ?>";

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
        var courses = $('#jsCompanyCourses').val();
        var employees = $('#jsSubordinateEmployees').val();

        var url = '<?php echo base_url('lms/courses/reports'); ?>';

        //   departments = departments != '' && departments != null && departments != undefined ? encodeURIComponent(departments) : '0';
        courses = courses != '' && courses != null && courses != undefined ? encodeURIComponent(courses) : '0';
        employees = employees != '' && employees != null && employees != undefined ? encodeURIComponent(employees) : '0';

        url += '/' + courses + '/' + employees;

        window.location = url;
    }


    function excel_export() {
        $("#jsFetchCSVReport").click();
    }

    function jsClearDateFilters() {
        var url = '<?php echo base_url("lms/courses/reports"); ?>';
        window.location = url;
    }


    $("#check_all").click(function() {

        if ($(this).prop("checked") === true) {
            $(`.jsExtraColumn`).prop("checked", true);
            $(`.jsExtraColumnBody`).removeClass("hidden");
        } else {
            $(`.jsExtraColumn`).prop("checked", false);
            $(`.jsExtraColumnBody`).addClass("hidden");
        }

    });
</script>