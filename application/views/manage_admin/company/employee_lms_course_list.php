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
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <!-- Main Page -->
                                    <div id="js-main-page">
                                        <div class="hr-setting-page">
                                            <?php echo form_open(base_url('manage_admin/copy_applicants/'), array('id' => 'copy-form')); ?>
                                            <ul>
                                                <li>
                                                    <label>Companies<span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-from-company">
                                                            <option value="0">Select Company</option>
                                                            <?php
                                                            foreach ($companies as $key => $company) {
                                                                echo '<option id="from_' . ($company['sid']) . '" value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>employees <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-employee">
                                                            <option value="0">Select employee</option>
                                                        </select>
                                                    </div>
                                                </li>

                                                <li>
                                                    <a class="site-btn" id="js-fetch-courses" href="#">Fetch Courses</a>
                                                </li>
                                            </ul>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>

                                    <!-- Employees listing Block -->
                                    <div id="js-enployees-list-block">
                                        <div class="hr-box js-hide-fetch">
                                            <div class="hr-box-header">
                                                <h4>Employee Not Completed Courses</h4>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Course Title</th>
                                                                <th>Status</th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="js-courses-list-show-area"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                            </div>
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
        var old_corporates;
        var old_companies = '<?php echo json_encode($companies); ?>';
        var old_from_company = 0;
        var old_to_company = 0;
        var selected_employees = [];
        var copy_employee_count = 0;
        var coped_employees = 0;
        $(document).on('click', '.js-copy-employees-btn', function(e) {
            e.preventDefault();
            start_copy_process()
        });

        // Select 2
        $('#js-from-company').select2();
        $('#js-employee').select2();

        $('#js-from-company').on('change', function() {

            var from_company_sid = this.value;
            fetch_employee(from_company_sid);
        });


        //
        $("#js-fetch-courses").on('click', function() {

            var employee_sid = $("#js-employee").val();
            var company_sid = $("#js-from-company").val();

            if (employee_sid == 0 || company_sid == 0) {
                alertify.alert('Please select company and employee');
            } else {
                loader();
                $('#js-loader-text').html('Please wait, we are loading employees courses <br> which may take few minutes!');

                fetch_employee_courses(company_sid, employee_sid);
                return;
            }
        });



        //
        function fetch_employee(company_sid) {

            var myurl = "<?php echo base_url('manage_admin/Lms_employees/get_companies_employees') ?>" + "/" + company_sid;
            $.get(myurl, function(resp) {
                resp = JSON.parse(resp)

                $('#js-employee').empty();               

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
        function fetch_employee_courses(company_sid, employee_sid) {

            var myurl = "<?php echo base_url('lms/employeecourses/') ?>" + "/" + company_sid + "/" + employee_sid;
            $.get(myurl, function(resp) {
                resp = JSON.parse(resp)

                $('#js-enployees-list-block').show();
                $('#js-courses-list-show-area').html('');

                var newRow = '';
                $.each(resp, function(key, value) {

                    newRow += '<tr>';
                    newRow += '<td>' + value.course_title + '</td>';
                    newRow += '<td>' + value.course_status + '</td>';
                    newRow += '<td> <button type="button" class="btn btn-success pull-right js-mark-course-completed-btn" data-employeeid=' + employee_sid + ' data-companyid=' + company_sid + ' data-courseid=' + value.sid + '>Mark Completed </button></td></tr>';
                });

                $('#js-courses-list-show-area').html(newRow);


                loader(false);

            });
        }


        //
        $(document).on('click', '.js-mark-course-completed-btn', function() {

            var _this = this;
            let courseId = $(this).data("courseid");
            let companyId = $(this).data("companyid");
            let employeeId = $(this).data("employeeid");

            alertify.confirm('Confirm Action', 'Are you sure you want to complete this course?',
                function() {
                    //
                    loader(true);
                    $.post("<?= base_url('lms/coursesmanualcompleted'); ?>", {
                        company_sid: companyId,
                        employee_sid: employeeId,
                        course_sid: courseId,

                    }).done(function() {
                        alertify.success('Course completed successfully!');
                        _this.closest("tr").remove();
                        loader(false);

                    });

                },
                function() {});

        });



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

        $(document).on('click', '.js-check-all', selectAllInputs);
        $(document).on('click', '.js-tr', selectSingleInput);


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


        let policyObj = {
            hasErrors: []
        };


        function callLoader() {
            // get the employees
            let selectedEmployees = [];
            $.each($('input[name="employees_ids[]"]:checked'), function() {
                selectedEmployees.push(parseInt($(this).val()));
            });
            //
            policyObj = {
                hasErrors: []
            };
            // Get company policies
            let fromCompanySid = $('#js-from-company').val();
            let toCompanySid = $('#js-to-company').val();

            if (fromCompanySid == 0 || toCompanySid == 0) {
                return alertify.alert('Please select "From & To" company to proceed.');
            }

            //Get From and to Company Policies
            var myurl = "<?php echo base_url('manage_admin/copy_employees/getCompaniesPolicies') ?>" + "/" + fromCompanySid + "/" + toCompanySid;
            $.ajax({
                type: "POST",
                url: myurl,
                async: false,
                data: {
                    employeeIds: selectedEmployees
                },
                success: function(data) {
                    if (data.fromCompanyPolicies.length === 0) {
                        return start_copy_process('bypass');
                    }
                    loadModal(data);
                },
                error: function(data) {}
            });
        }
    </script>