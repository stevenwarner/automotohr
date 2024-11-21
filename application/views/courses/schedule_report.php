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
                                  

                        <div class="box-view reports-filtering">
                            <form method="post" id="export" name="export" type="form/multipart">
                              
                            <div class="panel panel-default <?= isset($dwmc) && $userType == 'applicant' ? 'hidden' : ''; ?>">
                                                <div class="panel-heading"><b>Send Report</b></div>
                                                <div class="panel-body">
                                                    <!--  -->
                                                    <div class="row">
                                                        <!-- Selection row -->
                                                        <div class="col-sm-12">
                                                            <!-- None -->
                                                            <label class="control control--radio">
                                                                None &nbsp;&nbsp;
                                                                <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="none" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <!-- Daily -->
                                                            <label class="control control--radio">
                                                                Daily &nbsp;&nbsp;
                                                                <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="daily" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <!-- Weekly -->
                                                            <label class="control control--radio">
                                                                Weekly &nbsp;&nbsp;
                                                                <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="weekly" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <!-- Monthly -->
                                                            <label class="control control--radio">
                                                                Monthly &nbsp;&nbsp;
                                                                <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="monthly" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <!-- Yearly -->
                                                            <label class="control control--radio">
                                                                Yearly &nbsp;&nbsp;
                                                                <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="yearly" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <!-- Custom -->
                                                            <label class="control control--radio">
                                                                Custom &nbsp;&nbsp;
                                                                <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="custom" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <!--  -->
                                                        </div>

                                                        <!-- Custom date row -->
                                                        <div class="col-sm-12 jsCustomDateRow">
                                                            <br />
                                                            <label id="jsCustomLabel">Select a date & time</label>
                                                            <div class="row">
                                                                <div class="col-sm-4" id="jsCustomDate">
                                                                    <input type="text" class="form-control jsDatePicker" name="assignAndSendCustomDate" readonly="true" />
                                                                </div>
                                                                <div class="col-sm-4" id="jsCustomDay">
                                                                    <select name="assignAndSendCustomDay" id="jsCustomDaySLT">
                                                                        <option value="1">Monday</option>
                                                                        <option value="2">Tuesday</option>
                                                                        <option value="3">Wednesday</option>
                                                                        <option value="4">Thursday</option>
                                                                        <option value="5">Friday</option>
                                                                        <option value="6">Saturday</option>
                                                                        <option value="7">Sunday</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <input type="text" class="form-control jsTimePicker" name="assignAndSendCustomTime" readonly="true" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php if (isset($dwmc)) { ?>
                                                            <!-- Against Selected Employees -->
                                                            <div class="col-sm-12 hidden">
                                                                <label>Employee(s)</label>
                                                                <select multiple="true" name="assignAdnSendSelectedEmployees[]" class="assignSelectedEmployees">
                                                                    <option value="<?= $userSid; ?>" selected="true"></option>
                                                                </select>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="col-sm-12">
                                                                <hr />
                                                            </div>
                                                            <!-- Against Selected Employees -->
                                                            <div class="col-sm-12">
                                                                <label>Employee(s)</label>
                                                                <select multiple="true" name="assignAdnSendSelectedEmployees[]" class="assignSelectedEmployees">
                                                                    <option value="-1">All</option>
                                                                    <?php foreach ($filterData["employees"] as $key => $employee) { ?>
                                                                        <option value="<?= $employee['sid']; ?>"><?= remakeEmployeeName($employee); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>


                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <input type="submit" name="submit" class="submit-btn pull-right" value="Save">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>




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
        var courseType = "<?php echo $filters['courseType']; ?>";
        var employees = "<?php echo $filters['employees']; ?>";
        var startDate = "<?php echo $filters['startDate']; ?>";
        var endDate = "<?php echo $filters['endDate']; ?>";
        var baseURL = "<?= base_url(); ?>";

        // load select2 on teams
        $("#jsCompanyCourses").select2({
            closeOnSelect: false,
        });

        $("#jsCourseType").select2({
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

        if (courseType) {
            $('#jsCourseType').select2('val', courseType.split(','));
        } else {
            $('#jsCourseType').select2('val', 'all');
        }

        //
        if (employees) {
            $('#jsSubordinateEmployees').select2('val', employees.split(','));
        }

        if (startDate) {
            $('#start_date').val(startDate);
        }
        if (endDate) {
            $('#end_date').val(endDate);
        }

    });

    function jsApplyDateFilters() {
        var courses = $('#jsCompanyCourses').val();
        var employees = $('#jsSubordinateEmployees').val();
        var courseType = $('#jsCourseType').val();

        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();


        var url = '<?php echo base_url('lms/courses/reports'); ?>';

        //   departments = departments != '' && departments != null && departments != undefined ? encodeURIComponent(departments) : '0';
        courses = courses != '' && courses != null && courses != undefined ? encodeURIComponent(courses) : '0';
        employees = employees != '' && employees != null && employees != undefined ? encodeURIComponent(employees) : '0';
        courseType = courseType != '' && courseType != null && courseType != undefined ? encodeURIComponent(courseType) : '0';

        startDate = startDate != '' && startDate != null && startDate != undefined ? encodeURIComponent(startDate) : '0';
        endDate = endDate != '' && endDate != null && endDate != undefined ? encodeURIComponent(endDate) : '0';


        url += '/' + courses + '/' + employees + '/' + courseType + '/' + startDate + '/' + endDate;

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

    $(document).ready(function() {
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        }).val();

        $('#start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "1900:+1",
            onSelect: function(value) {}
        });

        $('#end_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "1900:+1",
            onSelect: function(value) {}
        });
    });



    ////////////////////////////////////////////////

        $('.assignSelectedEmployees').select2({
            closeOnSelect: false
        });
        //
        $('#jsCustomDaySLT').select2();


          //
          $('.jsDatePicker').datepicker({
            changeMonth: true,
            dateFormat: 'mm/dd',
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });

        //
        $('.jsTimePicker').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });


        $('.assignAndSendDocument').change(function() {
            //
            $('.jsCustomDateRow').show();
            $('#jsCustomDay').hide();
            $('#jsCustomLabel').text('Select a date & time');
            $('#jsCustomDate').show();
            $('.jsDatePicker').datepicker('option', {
                changeMonth: true
            });
            //
            if ($(this).val().toLowerCase() == 'daily') {
                $('#jsCustomLabel').text('Select time');
                $('#jsCustomDate').hide();
            } else if ($(this).val().toLowerCase() == 'monthly') {
                $('#jsCustomLabel').text('Select a date & time');
                $('.jsDatePicker').datepicker('option', {
                    dateFormat: 'dd'
                });
                $('.jsDatePicker').datepicker('option', {
                    changeMonth: false
                });
            } else if ($(this).val().toLowerCase() == 'weekly') {
                $('#jsCustomDate').hide();
                $('#jsCustomDay').show();
                $('#jsCustomLabel').text('Select day & time');
            } else if ($(this).val().toLowerCase() == 'yearly' || $(this).val().toLowerCase() == 'custom') {
                $('.jsDatePicker').datepicker('option', {
                    dateFormat: 'mm/dd'
                });
            } else if ($(this).val().toLowerCase() == 'none') {
                $('.jsCustomDateRow').hide();
            }
        });



</script>





<script>
    $(function() {
        //
        let eDoc = <?= json_encode($eDoc); ?>;
        //
        alert('asd');
        $('.assignSelectedEmployees').select2({
            closeOnSelect: false
        });
        //
        $('#jsCustomDaySLT').select2();
        //
        $('.assignAndSendDocument').change(function() {
            //
            $('.jsCustomDateRow').show();
            $('#jsCustomDay').hide();
            $('#jsCustomLabel').text('Select a date & time');
            $('#jsCustomDate').show();
            $('.jsDatePicker').datepicker('option', {
                changeMonth: true
            });
            //
            if ($(this).val().toLowerCase() == 'daily') {
                $('#jsCustomLabel').text('Select time');
                $('#jsCustomDate').hide();
            } else if ($(this).val().toLowerCase() == 'monthly') {
                $('#jsCustomLabel').text('Select a date & time');
                $('.jsDatePicker').datepicker('option', {
                    dateFormat: 'dd'
                });
                $('.jsDatePicker').datepicker('option', {
                    changeMonth: false
                });
            } else if ($(this).val().toLowerCase() == 'weekly') {
                $('#jsCustomDate').hide();
                $('#jsCustomDay').show();
                $('#jsCustomLabel').text('Select day & time');
            } else if ($(this).val().toLowerCase() == 'yearly' || $(this).val().toLowerCase() == 'custom') {
                $('.jsDatePicker').datepicker('option', {
                    dateFormat: 'mm/dd'
                });
            } else if ($(this).val().toLowerCase() == 'none') {
                $('.jsCustomDateRow').hide();
            }
        });

        //
        $('.jsDatePicker').datepicker({
            changeMonth: true,
            dateFormat: 'mm/dd',
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });

        //
        $('.jsTimePicker').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });

        //
        if (eDoc.sid !== undefined) {
            let SE = null;
            //
            if (eDoc.assigned_employee_list != null && eDoc.assigned_employee_list != '' && eDoc.assigned_employee_list == 'all') {
                SE = ['-1'];
            }
            if (eDoc.assigned_employee_list != null && eDoc.assigned_employee_list != '' && eDoc.assigned_employee_list != 'all') {
                SE = JSON.parse(eDoc.assigned_employee_list);
            }
            //
            $(`.assignAndSendDocument[value="${eDoc.assign_type}"]`).prop('checked', true).trigger('change');
            $('.assignSelectedEmployees').select2('val', SE);
            $('.jsDatePicker').val(eDoc.assign_date)
            if (eDoc.assign_type == 'weekly') $('#jsCustomDaySLT').select2('val', eDoc.assign_date);
            $('.jsTimePicker').val(eDoc.assign_time)
        } else $('.assignAndSendDocument[value="none"]').prop('checked', true);
    });
</script>