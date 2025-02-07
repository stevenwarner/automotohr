<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$active_companies = '';
$active_companies .= '<option value="0">[Select Company]</option>';
foreach ($companies as $company)
    $active_companies .= '<option value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">




                            <?php echo form_open(base_url('manage_admin/lms_employees/manual_assign'), array('id' => 'manualAssignForm')); ?>

                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <!-- Main Page -->
                                    <div id="js-main-page">
                                        <div class="hr-setting-page">
                                            <ul>
                                                <li>
                                                    <label>Companies<span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-from-company" name="company">
                                                            <option value="0">Select a Company</option>
                                                            <?php
                                                            foreach ($companies as $key => $company) {
                                                                echo '<option id="from_' . ($company['sid']) . '" value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Employees <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-employee" name="employee">
                                                            <option value="0">Select an Employee</option>
                                                        </select>
                                                    </div>
                                                </li>

                                                <li>
                                                    <a class="site-btn" id="js-fetch-courses" href="#">Fetch Courses</a>

                                                </li>
                                            </ul>

                                        </div>
                                    </div>

                                    <!-- Default Courses listing  -->
                                    <div id="js-enployees-list-block">
                                        <div class="hr-box js-hide-fetch">
                                            <div class="hr-box-header">
                                                <h4>Default Courses</h4>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th><input type="checkbox" class="js-check-all"></th>
                                                                <th>Course Title</th>
                                                                <th>Course Type</th>
                                                                <th>Course Version</th>
                                                                <th>Assign Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="js-courses-list-show-area"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <?php echo form_close(); ?>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .my_loader {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99;
            background-color: rgba(0, 0, 0, .7);
        }

        .loader-icon-box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: auto;
            z-index: 9999;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .loader-icon-box i {
            font-size: 14em;
            color: #81b431;
        }

        .loader-text {
            display: inline-block;
            padding: 10px;
            color: #000;
            background-color: #fff !important;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
        }
    </style>

    <!-- Loader -->
    <div id="js-loader" class="text-center my_loader" style="display: none;">
        <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
        <div class="loader-icon-box cs-loader-box">
            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
            <div class="loader-text cs-loader-text" id="js-loader-text" style="display:block; margin-top: 35px;">Please wait ...</div>
        </div>
    </div>
    <style>
        .my_loader {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99;
            background-color: rgba(0, 0, 0, .7);
        }

        .loader-icon-box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: auto;
            z-index: 9999;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .loader-icon-box i {
            font-size: 14em;
            color: #81b431;
        }

        .loader-text {
            display: inline-block;
            padding: 10px;
            color: #000;
            background-color: #fff !important;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
        }
    </style>

    <!-- Loader -->
    <div id="js-loader" class="text-center my_loader" style="display: none;">
        <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
        <div class="loader-icon-box cs-loader-box">
            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
            <div class="loader-text cs-loader-text" id="js-loader-text" style="display:block; margin-top: 35px;">Please wait ...</div>
        </div>
    </div>
    <style>
        #js-enployees-list-block {
            display: none;
        }

        .cs-required {
            font-weight: bolder;
            color: #cc0000;
        }

        /* Alertify CSS */
        .ajs-header {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }

        .ajs-ok {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }

        .ajs-cancel {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }
    </style>


    <script>
        $(function courseManagement() {
            let companyId,
                employeeId,
                currentCourseIndex = 0,
                courseIds = [];

            //[] bind selectors
            $('#js-from-company').select2();
            $('#js-employee').select2();
            // when company is changed
            $('#js-from-company').on('change', function() {
                companyId = $(this).val();
                resetData();
                fetch_employee();
            });

            //
            $("#js-fetch-courses").on('click', function() {

                employeeId = $("#js-employee").val();

                if (!employeeId || !companyId) {
                    alertify.alert('Please select a company and an employee.');
                } else {
                    loader(true);
                    $('#js-loader-text').html('Please wait, we are loading employees courses <br> which may take few minutes!');
                    fetch_employee_courses();
                    return;
                }
            });

            //
            $(document).on('click', '.js-mark-course-completed-btn', function(event) {
                event.preventDefault();
                //
                courseId = $(this).data("courseid");
                courseLanguage = $(this).closest("tr").find("select.jsCourseLanguages option:selected").val();

                alertify.confirm(
                    'Are you sure you want to manually complete this course?',
                    function() {
                        markCourseCompleted(
                            courseId, courseLanguage
                        )

                    }
                )
            });

            function markCourseCompleted(courseId, courseLanguage) {
                //
                loader(true);
                $
                    .post("<?= base_url('manage_admin/lms/manual_course_complete'); ?>", {
                        companyId: companyId,
                        employeeId: employeeId,
                        courseId: courseId,
                        language: courseLanguage

                    })
                    .always(function() {
                        loader(false);
                    })
                    .fail(function(e) {
                        console.log(e)
                        if (e.responseJSON) {

                            alertify.alert(
                                "Errors!",
                                e.responseJSON.errors.join("<br/>")
                            )
                            return;
                        }
                    })
                    .done(function(resp) {
                        alertify.alert(
                            "Success!",
                            resp.message,
                            function() {
                                fetch_employee_courses();
                            }
                        );

                    });
            }

            //
            function fetch_employee() {
                loader(true);
                $
                    .ajax({
                        url: "<?= base_url('manage_admin/Lms_employees/get_companies_employees'); ?>/" + companyId,
                        method: "GET"
                    })
                    .fail(function(e) {
                        console.log(e)
                    })
                    .done(function(resp) {
                        $('#js-employee').append('<option value="0">Select employee</option>');
                        $.each(resp, function(key, value) {
                            employee_name = RemakeEmployeeName(value);
                            var newOption = $('<option>', {
                                value: value.sid,
                                text: employee_name
                            });

                            $('#js-employee').append(newOption);
                        });
                        loader(false);
                    });
            }

            //
            function fetch_employee_courses() {
                $
                    .ajax({
                        url: "<?= base_url('manage_admin/lms/getallcourses'); ?>/" + (companyId) + "/" + employeeId,
                        method: "GET"
                    })
                    .fail(function(e) {
                        console.log(e)
                    })
                    .done(function(resp) {
                        var newRow = '';
                        $.each(resp, function(key, value) {
                            //
                            let disabled = '';

                            if (value.assigned == 1) {
                                disabled = 'disabled';
                            }

                            newRow += '<tr class="js-tr">';
                            newRow += '<td><input type="checkbox" name="courses_ids[]" value="' + value.sid + '" ' + disabled + '></td>';
                            newRow += '<td>' + value.course_title + '</td>';
                            newRow += '<td>' + (value.course_type == "scorm" ? "Scorm" : "Manual")+ '</td>';
                            newRow += '<td>' + value.course_version + '</td>';
                            newRow += '<td>' + (value.assigned == 1 ? "Assigned" : "Not Assigned") + '</td>';
                            newRow += '</tr>';
                        });


                        newRow += '<tr class="js-tr">';
                        newRow += '<td colspan="5"><a class="site-btn pull-right" id="js-save-courses" href="javascript:void(0)">Assign Course(s)</a></td>';
                        newRow += '</tr>';
                        console.log(newRow)

                        $('#js-courses-list-show-area').html(newRow);
                        $('#js-enployees-list-block').show();

                        loader(false);
                    });
            }


            // Loader
            function loader(show_it, msg) {
                msg = msg === undefined ? 'Please, wait while we are processing your request.' : msg;
                show_it = show_it === undefined || show_it == true || show_it === 'show' ? 'show' : show_it;
                if (show_it === 'show') {
                    $('#js-loader').show();
                    $('#js-loader-text').html(msg);
                } else {
                    $('#js-loader').hide();
                    $('#js-loader-text').html('');
                }
            }

            //
            function RemakeEmployeeName(emp) {
                var row = '';
                //
                row += emp['first_name'];
                row += ' ' + emp['last_name'];
                row += ' (' + emp['access_level'];
                row += emp['access_level_plus'] == 1 || emp['pay_plan_flag'] == 1 ? ' Plus' : '';
                row += ' )';
                row += emp['job_title'] ? ' [' + emp['job_title'] + ']' : '';
                //
                return row;
            }

            // reset all
            function resetData() {
                employeeId = undefined;
                currentCourseIndex = 0;
                courseIds = [];
                $('#js-employee').empty();
                $('#js-enployees-list-block').hide();
                $('#js-courses-list-show-area').html('');
            }

            //
            function getCourseStatus(course) {
                //
                const obj = {
                    text: "",
                    slug: ""
                };
                //
                if (course.lesson_status === "ready_to_start") {
                    obj.text = "Ready To Start";
                    obj.slug = "ready_to_start";
                } else {
                    obj.text = "In Progress";
                    obj.slug = "in_progress";
                }
                return obj;
            }

        });



        $(document).on('click', '.js-check-all', selectAllInputs);
        // Select all input: checkbox
        function selectAllInputs() {
            $('.js-tr').find('input[name="courses_ids[]"]').prop('checked', $(this).prop('checked'));
            $('input[type="checkbox"]:disabled').prop('checked', false);
        }


        //
        $(document).on('click', '#js-save-courses', function() {
            selected_employees = get_all_selected_courses();
            if (selected_employees.length === 0) {
                alertify.alert('ERROR!', 'Please select at least one course.');
                return;
            }

            //
            alertify.confirm(
                'Confirmation!',
                'Do you really want to manually assign selected course(s)?', 
                function() {
                    $('#manualAssignForm').submit();
                },
                function () {

                }
            );

        });


        //
        function get_all_selected_courses() {
            var tmp = [];
            $.each($('input[name="courses_ids[]"]:checked'), function() {
                var obj = {};
                obj.course_sid = parseInt($(this).val());
                obj.course_title = $(this).closest('tr').find('td.js-course-title').text();
                tmp.push(obj);
            });
            return tmp;
        }
    </script>