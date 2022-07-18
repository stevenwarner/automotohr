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

    .show {
        cursor: pointer;

    }

    .empbgcolor {
        padding: 2px;
        color: #3554dc;
        font-size: 14px;
        text-decoration: underline;
    }

    hr {
        margin-top: 10px !important;
        margin-bottom: 10px !important;

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
            <input type="hidden" id="report_to_date" name="to_date" value="" />
            <input type="hidden" id="report_from_date" name="from_date" value="" />
            <input type="hidden" id="report_all_columns" name="report_all_columns" value="0" />

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
                    <label id="employeelable">Employee(s)</label>
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
                        <a id="save_report_setting" class="btn btn-block btn-success">Schedule Report</a>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading"><b>Scheduled Reports </b></div>
    <div class="panel-body">
        <div class="col-sm-12">


            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Frequency</th>
                        <th>Employees</th>
                        <th>Filters</th>
                        <th>Selected Fields</th>
                        <th class="last-col" width="1%" colspan="3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!--All records-->

                    <?php if (sizeof($csv_report_settings) > 0) {
                        foreach ($csv_report_settings as $setting_row) {
                    ?>
                            <tr>
                                <td class="vam">

                                    <?= ucfirst($setting_row['custom_type']); ?><br>
                                    <?php if ($setting_row['custom_type'] == 'daily') {
                                        echo "At : " . $setting_row['custom_time'] . "<br>";
                                    }
                                    if ($setting_row['custom_type'] == 'monthly') {
                                        echo "On " . $setting_row['custom_date'] . "th At : " . $setting_row['custom_time'] . "<br>";
                                    }

                                    if ($setting_row['custom_type'] == 'weekly') {
                                        echo "On " . $setting_row['custom_day'] . "th day At : " . $setting_row['custom_time'] . "<br>";
                                    }
                                    if ($setting_row['custom_type'] == 'yearly') {
                                        echo "On " . $setting_row['custom_date'] . " At : " . $setting_row['custom_time'] . "<br>";
                                    }

                                    ?>
                                </td>
                                <td class="vam">
                                    <?php
                                    if ($setting_row['sender_list'] != 'all') {
                                        $sender_list = explode(',', $setting_row['sender_list']);
                                    ?>

                                        <div class="show"><span class="empbgcolor"><?= count($sender_list); ?> Employees</span>
                                            <div class="menu" style="display: none;">
                                                <?php
                                                foreach ($sender_list as $employee_sid) {
                                                    echo  getUserNameBySID($employee_sid) . "<hr>";
                                                } ?>
                                            </div>
                                        </div>
                                    <?php

                                    } else {
                                        echo "All Employees";
                                    }

                                    ?>
                                </td>
                                <td class="vam">
                                    <?php
                                    echo "<b>Date:  </b> <br>(" . date('M d Y, D', strtotime($setting_row['to_date']));
                                    echo " &nbsp; - &nbsp;" . date('M d Y, D', strtotime($setting_row['from_date'])) . ")<br>";

                                    if ($setting_row['employee_type'] != null) {

                                        if ($setting_row['employee_type'] == 'all') {
                                            echo "Access Level: All Employes <br>";
                                        } else {
                                            echo "<b>Access Level: </b>" . str_replace(",", ", ", $setting_row['employee_type']) . " <br>";
                                        }
                                        echo "<b>Status: </b>" . ucfirst($setting_row['employee_status']);
                                    }

                                    ?>
                                </td>

                                <td class="vam"><?php
                                                if ($setting_row['selected_columns'] == 'all') {
                                                    echo "All Fields";
                                                } else {
                                                    echo ucwords(str_replace(",", ", ", str_replace("_", " ", $setting_row['selected_columns'])));
                                                }
                                                ?>
                                </td>

                                <td class="vam">
                                    <a class="btn btn-danger btn-sm btn-block" href="javascript:;" id="<?php echo $setting_row['sid']; ?>" onclick="todo('delete', this.id);">
                                        <i class="fa fa-trash fa-fw" style="font-size: 12px;"></i>
                                        <span>Delete</span>
                                    </a>
                                </td>
                                <?php
                                ?>
                            </tr>
                        <?php  }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <span class="no-data">Settings are not found</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>


<script>
    $(document).ready(function() {
        $('.jsCustomDateRow').hide();

        $('.show').click(function() {
            $(this).find(".menu").toggle();
        });

    });

    $(function() {
        //
        $('.assignSelectedEmployees').select2({
            closeOnSelect: false
        });


        $('#access_level').select2({
            closeOnSelect: false
        });

        $('.assignSelectedEmployees').next(".select2-container").hide();
        $('#employeelable').hide();
        //$("#save_report_setting").attr("disabled", "disabled");

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
            if ($(this).val().toLowerCase() == 'none') {
                $(".assignSelectedEmployees").select2("val", "");
                $('.assignSelectedEmployees').next(".select2-container").hide();
                $('#employeelable').hide();
                // $("#save_report_setting").attr("disabled", "disabled");
            }

            if ($(this).val().toLowerCase() == 'daily') {
                $('#employeelable').show();
                $('.assignSelectedEmployees').next(".select2-container").show();
                $('#jsCustomLabel').text('Select time');
                $('#jsCustomDate').hide();
            } else if ($(this).val().toLowerCase() == 'monthly') {
                $('#employeelable').show();
                $('.assignSelectedEmployees').next(".select2-container").show();
                $('#jsCustomLabel').text('Select a date & time');
                $('.jsDatePicker').datepicker('option', {
                    dateFormat: 'dd'
                });
                $('.jsDatePicker').datepicker('option', {
                    changeMonth: false
                });
            } else if ($(this).val().toLowerCase() == 'weekly') {
                $('#employeelable').show();
                $('.assignSelectedEmployees').next(".select2-container").show();
                $('#jsCustomDate').hide();
                $('#jsCustomDay').show();
                $('#jsCustomLabel').text('Select day & time');
            } else if ($(this).val().toLowerCase() == 'yearly' || $(this).val().toLowerCase() == 'custom') {
                $('#employeelable').show();
                $('.assignSelectedEmployees').next(".select2-container").show();
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

            var display_start_date = $("#display_start_date").val();
            var display_end_date = $("#display_end_date").val();
            //
            $("#report_access_level").val(access_level);
            $("#report_employee_status").val(status);

            $("#report_to_date").val(display_start_date);
            $("#report_from_date").val(display_end_date);

            if ($('#check_all').is(":checked")) {
                $("#report_all_columns").val('1');
            } else {
                $("#report_all_columns").val('0');
            }

            //
            // Start Validation
            var custom_type = $('input[name="assignAndSendDocument"]:checked').val();
            var custom_time = $('input[name="assignAndSendCustomTime"]').val();
            var custom_date = $('input[name="assignAndSendCustomDate"]').val();
            var selected_employes = $('.assignSelectedEmployees').val();

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
                // submitforms();
                var ids = [];
                $(".check_it:checked").map(function() {
                    ids.push($(this).val());
                });

                $(this).append("<input type='hidden' name='test'>");
                $("input[name='test']").val(ids);

                $("#form_export_employees_report").submit();

            }

        });

    });


    function todo(action, id) {

        url = "<?= base_url() ?>/export_employees_csv/report_setting_remove";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " setting?",
            function() {
                $.post(url, {
                        action: action,
                        sid: id
                    })
                    .done(function(data) {
                        alertify.success('Selected setting have been ' + action + 'd.');
                        location.reload();
                    });
            },
            function() {
                alertify.error('Canceled');
            });
    }
</script>