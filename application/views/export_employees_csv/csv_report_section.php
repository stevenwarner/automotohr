<style>
    .nopaddingleft {
        padding-left: 0;
    }

    .nopaddingright {
        padding-right: 0;
    }

    .select2-container {
        width: 100% !important;
        min-width: 0 !important;
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        border: 1px solid #aaa !important;
    }
</style>

<!-- Panel for send documents DMWYC -->
<div class="panel panel-default">
    <div class="panel-heading"><b>Send CSV Report</b></div>
    <div class="panel-body">
        <!--  -->
        <form id="form_export_employees_report" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
            <input type="hidden" name="perform_action" value="save_report_setting" />
            <input type="hidden" name="company_sid" value="<?php echo $company_sid; ?>" />
            <input type="hidden" id="report_access_level" name="access_level" value="" />
            <input type="hidden" id="report_employee_status" name="status" value="" />

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

                <div class="col-sm-12">
                    <hr />
                </div>
                <!-- Against Selected Employees -->
                <div class="col-sm-12">
                    <label>Employee(s)</label>
                    <select multiple="true" name="assignAdnSendSelectedEmployees[]" class="assignSelectedEmployees">
                        <option value="-1">All</option>
                        <?php foreach ($employeesList as $key => $employee) { ?>
                            <option value="<?= $employee['sid']; ?>"><?= remakeEmployeeName($employee); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-12">
                    <!--  -->
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">

                    </div>
                    <!--  -->
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">

                    </div>
                    <!--  -->
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                        <label>&nbsp;</label>
                        <a id="save_report_setting" class="btn btn-block btn-success">Save Report Setting</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.jsCustomDateRow').hide();

    });

    $(function() {
        //
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
        $('.assignAndSendDocument[value="none"]').prop('checked', true);
        //
        $("#save_report_setting").on("click", function(e) {
             //  e.preventDefault();

            var form_validation = 0;
            var access_level = $("#access_level").val();
            var status = $("#employee_status").val();
            //
            $("#report_access_level").val(access_level);
            $("#report_employee_status").val(status);
            //
            // Start Validation
            var custom_type = $('input[name="assignAndSendDocument"]:checked').val();
            var custom_time = $('input[name="assignAndSendCustomTime"]').val();
            var custom_date = $('input[name="assignAndSendCustomDate"]').val();
            var selected_employes = $('.assignSelectedEmployees').val();

            //  console.log(custom_type);
            //  alert(custom_type)
            //
            if (custom_type == "none") {
                form_validation == 1;
                alertify.alert("Error", "Please select Report Type");
                return;
            } else if (custom_type == "daily" || custom_type == "weekly") {
                if (custom_time == '' || custom_time == undefined) {
                    form_validation == 1;
                    alertify.alert("Error", "Please select Time");
                    return;
                }
            } else if (custom_type == "monthly" || custom_type == "yearly" || custom_type == "custom") {

                if (custom_date == '' || custom_date == undefined) {
                    form_validation == 1;
                    alertify.alert("Error", "Please select Date");
                    return;
                }

                if (custom_time == '' || custom_time == undefined) {
                    form_validation == 1;
                    alertify.alert("Error", "Please select Time");
                    return;
                }
            }

            if (selected_employes == '' || selected_employes == undefined) {
                form_validation == 1;
                alertify.alert("Error", "Please select Employee");
                return;
            }

            if (form_validation == 0) {
                submitforms();
            }


        });


        function submitforms() {
            $("#form_export_employees_report").submit(function(e) {
         
                var ids = [];
                $(".check_it:checked").map(function() {
                    ids.push($(this).val());
                });
                $(this).append("<input type='hidden' name='test'>");
                $("input[name='test']").val(ids);

            });
        }
    });
</script>