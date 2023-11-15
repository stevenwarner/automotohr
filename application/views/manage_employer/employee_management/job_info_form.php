<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(".tab_content").hide();
                            $(".tab_content:first").show();
                            $("ul.tabs li").click(function() {
                                $("ul.tabs li").removeClass("active");
                                $(this).addClass("active");
                                $(".tab_content").hide();
                                var activeTab = $(this).attr("rel");
                                $("#" + activeTab).fadeIn();
                            });
                        });
                    </script>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                    <div id="HorizontalTab" class="HorizontalTab">

                        <div class="resp-tabs-container hor_1">
                            <div id="tab1" class="tabs-content">
                                <div id="jsPrimaryEmployeeBox">

                                    <div class="universal-form-style-v2 info_edit">
                                        <form id="edit_employer" method="POST" enctype="multipart/form-data">
                                            <div class="form-title-section"><br>
                                                <h2>Job Information</h2>
                                                <div class="text-right">
                                                    <input type="button" value="Save" onclick="submitResult();" class="btn btn-success">
                                                    <a href="<?php echo base_url('job_info') . '/employee/' . $employer["sid"]; ?>" class="btn btn-danger">Cancel</a>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group">
                                                    <p class="text-danger csF16">
                                                        <strong>
                                                            <em>
                                                           Note: The FLSA status for this compensation. Salaried ('Exempt') employees are paid a fixed salary every pay period. Salaried with overtime ('Salaried Nonexempt') employees are paid a fixed salary every pay period, and receive overtime pay when applicable. Hourly ('Nonexempt') employees are paid for the hours they work, and receive overtime pay when applicable. Owners ('Owner') are employees that own at least twenty percent of the company.                                                            </em>
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 form-group">
                                                <?php $templateTitles = get_templet_jobtitles($employer['parent_sid']); ?>

                                                <label>Job Title: &nbsp;&nbsp;&nbsp;
                                                        <?php if ($templateTitles) { ?>
                                                            <input type="radio" name="title_option" value="dropdown" class="titleoption" <?php echo $employer['job_title_type'] != '0' ? 'checked' : '' ?>> Choose Job Title&nbsp;&nbsp;
                                                            <input type="radio" name="title_option" value="manual" class="titleoption" <?php echo $employer['job_title_type'] == '0' ? 'checked' : '' ?>> Custom Job Title &nbsp;
                                                        <?php } ?>
                                                    </label>
                                                    <input class="invoice-fields" value="<?php echo set_value('job_title', $employer["job_title"]); ?>" type="text" name="job_title" id="job_title">
                                                    <?php if ($templateTitles) { ?>
                                                        <select name="temppate_job_title" id="temppate_job_title" class="invoice-fields" style="display: none;">
                                                            <option value="0">Please select job title</option>
                                                            <?php foreach ($templateTitles as $titleRow) { ?>
                                                                <option value="<?php echo $titleRow['sid'] . '#' . $titleRow['title']; ?>"> <?php echo $titleRow['title']; ?> </option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>


                                                    <?php echo form_error('job_title'); ?>
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 form-group">
                                                    <label>FLSA Status:</label>
                                                    <select class="invoice-fields" id="flsa_status" required="" name="flsa_status">
                                                        <option value="Exempt">Exempt</option>
                                                        <option value="Salaried Nonexempt">Salaried Nonexempt</option>
                                                        <option value="Nonexempt">Nonexempt</option>
                                                        <option value="Owner">Owner</option>
                                                    </select>
                                                    <?php echo form_error('flas_status'); ?>
                                                </div>


                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <!--  --><br>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" name="primary" value="1" <?= $employer['isprimary'] !== false ? 'checked' : ''; ?> /> Primary?
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <!--  --><br>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" name="isactive" value="1" <?= $employer['isactive'] !== false ? 'checked' : ''; ?> /> Is Avtive?
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <!--  -->
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                    <?php
                                                    $shift_start = isset($employer['shift_start_time']) && !empty($employer['shift_start_time']) ? $employer['shift_start_time'] : SHIFT_START;
                                                    $shift_end = isset($employer['shift_end_time']) && !empty($employer['shift_end_time']) ? $employer['shift_end_time'] : SHIFT_END;
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label>Shift start time:</label>
                                                            <input class="invoice-fields js-shift-start-time show_employee_working_info" readonly="true" value="<?php echo $shift_start; ?>" type="text" name="shift_start_time">
                                                        </div>
                                                        <div class="col-sm-6" style="padding-right: 0px;">
                                                            <label>Shift End time:</label>
                                                            <input class="invoice-fields js-shift-end-time show_employee_working_info" readonly="true" value="<?php echo $shift_end; ?>" type="text" name="shift_end_time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  -->
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                    <label>Break</label>
                                                    <?php
                                                    $break_hours = isset($employer['break_hours']) ? $employer['break_hours'] : BREAK_HOURS;
                                                    $break_minutes = isset($employer['break_mins']) && !empty($employer['break_mins']) ? $employer['break_mins'] : BREAK_MINUTES;
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-6 shift_div">
                                                            <div class="input-group">
                                                                <input min="0" max="23" oninput="this.value = Math.abs(this.value)" id="br_hours" type="number" value="<?php echo  $break_hours; ?>" name="break_hours" class="invoice-fields show_employee_working_info emp_break_info" data-type="hours">
                                                                <div class="input-group-addon"> Hours </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 shift_div shift_end">
                                                            <div class="input-group">
                                                                <input min="0" max="59" oninput="this.value = Math.abs(this.value)" type="number" value="<?php echo  $break_minutes; ?>" id="br_mins" name="break_mins" class="invoice-fields show_employee_working_info emp_break_info" data-type="minutes">
                                                                <div class="input-group-addon">Minutes</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  -->
                                            </div>

                                            <div class="row">
                                                <!--  -->
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5 form-group">
                                                    <label>Week Days Off:</label>
                                                    <?php $dayoffs = isset($employer['offdays']) && !empty($employer['offdays']) ? explode(',', $employer['offdays']) : [DAY_OFF]; ?>
                                                    <select class="show_employee_working_info" name="offdays[]" id="js_offdays" multiple="true">
                                                        <option value="Monday" <?php echo in_array("Monday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                            Monday</option>
                                                        <option value="Tuesday" <?php echo in_array("Tuesday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                            Tuesday</option>
                                                        <option value="Wednesday" <?php echo in_array("Wednesday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                            Wednesday</option>
                                                        <option value="Thursday" <?php echo in_array("Thursday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                            Thursday</option>
                                                        <option value="Friday" <?php echo in_array("Friday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                            Friday</option>
                                                        <option value="Saturday" <?php echo in_array("Saturday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                            Saturday</option>
                                                        <option value="Sunday" <?php echo in_array("Sunday", $dayoffs) ? 'selected="true"' : ''; ?>>
                                                            Sunday</option>
                                                    </select>
                                                </div>


                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <label class="csF16">Rate <strong class="text-danger">*</strong></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-dollar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control input-lg " placeholder="0.0" value="25.00" name="rate">
                                                    </div>
                                                </div>


                                                <div class=" col-lg-2 col-md-2 col-xs-12 col-sm-2  form-group">
                                                    <label class="csF16">Per <strong class="text-danger">*</strong></label>
                                                    <select class="form-control input-lg rate_per">
                                                        <option value="Hour">Per hour</option>
                                                        <option value="Week">Per week</option>
                                                        <option value="Month">Per month</option>
                                                        <option value="Year">Per year</option>
                                                        <option value="Paycheck">Per paycheck</option>
                                                    </select>
                                                </div>


                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <label>Effected Date:</label>

                                                    <?php
                                                    $effectedDate = $employer['effected_date'] != NULL && $employer['rehire_date'] != '0000-00-00' ? DateTime::createFromFormat('Y-m-d', $employer['rehire_date'])->format('m-d-Y') : '';
                                                    ?>
                                                    <input class="invoice-fields js-rehireDate" id="js-effected-date" readonly="" type="text" name="effected_date" value="<?php echo $effectedDate; ?>">
                                                    <?php echo form_error('rehireDate'); ?>
                                                </div>

                                            </div>


                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h1 class="csF16 csW m0">
                                                        <strong>
                                                            Overtime Information
                                                        </strong>
                                                    </h1>
                                                </div>
                                                <div class="panel-body">

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                            <?php
                                                            $shift_start = isset($employer['shift_start_time']) && !empty($employer['shift_start_time']) ? $employer['shift_start_time'] : SHIFT_START;
                                                            $shift_end = isset($employer['shift_end_time']) && !empty($employer['shift_end_time']) ? $employer['shift_end_time'] : SHIFT_END;
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label>Shift start time:</label>
                                                                    <input class="invoice-fields js-shift-start-time show_employee_working_info" readonly="true" value="<?php echo $shift_start; ?>" type="text" name="shift_start_time">
                                                                </div>
                                                                <div class="col-sm-6" style="padding-right: 0px;">
                                                                    <label>Shift End time:</label>
                                                                    <input class="invoice-fields js-shift-end-time show_employee_working_info" readonly="true" value="<?php echo $shift_end; ?>" type="text" name="shift_end_time">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <label class="csF16">Rate <strong class="text-danger">*</strong></label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-dollar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control input-lg jsEmployeeFlowAmount" placeholder="0.0" value="25.00" name="amount">
                                                            </div>
                                                        </div>

                                                   

                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <!--  --><br>
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="isactive" value="1" <?= $employer['isactive'] !== false ? 'checked' : ''; ?> /> is Allowed?
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h1 class="csF16 csW m0">
                                                        <strong>
                                                            Double Overtime Information
                                                        </strong>
                                                    </h1>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                            <?php
                                                            $shift_start = isset($employer['shift_start_time']) && !empty($employer['shift_start_time']) ? $employer['shift_start_time'] : SHIFT_START;
                                                            $shift_end = isset($employer['shift_end_time']) && !empty($employer['shift_end_time']) ? $employer['shift_end_time'] : SHIFT_END;
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label>Shift start time:</label>
                                                                    <input class="invoice-fields js-shift-start-time show_employee_working_info" readonly="true" value="<?php echo $shift_start; ?>" type="text" name="shift_start_time">
                                                                </div>
                                                                <div class="col-sm-6" style="padding-right: 0px;">
                                                                    <label>Shift End time:</label>
                                                                    <input class="invoice-fields js-shift-end-time show_employee_working_info" readonly="true" value="<?php echo $shift_end; ?>" type="text" name="shift_end_time">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <label class="csF16">Rate <strong class="text-danger">*</strong></label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-dollar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control input-lg jsEmployeeFlowAmount" placeholder="0.0" value="25.00" name="amount">
                                                            </div>
                                                        </div>
                                                
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <!--  --><br>
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="isactive" value="1" <?= $employer['isactive'] !== false ? 'checked' : ''; ?> /> is Allowed?
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>



                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h1 class="csF16 csW m0">
                                                        <strong>
                                                            Holiday Information
                                                        </strong>
                                                    </h1>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">


                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <label class="csF16">Rate <strong class="text-danger">*</strong></label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-dollar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control input-lg jsEmployeeFlowAmount" placeholder="0.0" value="25.00" name="amount">
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <!--  --><br>
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="isactive" value="1" <?= $employer['isactive'] !== false ? 'checked' : ''; ?> /> is Allowed?
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
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

                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>



<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js">
</script>
<!--file opener modal starts-->
<script language="JavaScript" type="text/javascript">
    var old_rehire_date = '<?php echo $rehireDate; ?>';
    //
    var timeOff = '<?= $timeOff ?>';
    $("#teams").select2();


    $('#js-policies').select2({
        closeOnSelect: false
    });

    $('#js_offdays').select2({
        closeOnSelect: false
    });





    if (timeOff == "enable") {
        $(".emp_break_info").on("keyup", function() {

            var type = $(this).data("type");
            validateBreakTime(type);
        });

        function validateBreakTime(type) {
            //
            var break_hours = $("#br_hours").val();
            var break_minutes = $("#br_mins").val();
            //
            var shift_start = $(".js-shift-start-time").val();
            var shift_end = $(".js-shift-end-time").val();
            //
            //create date format          
            var timeStart = new Date("01/01/2007 " + shift_start).getHours();
            var timeEnd = new Date("01/01/2007 " + shift_end).getHours();
            //
            var hourDiff = timeEnd - timeStart;

            var is_error = "no";
            //  
            if (type == "minutes" && break_minutes > 59) {
                is_error = "yes";
                alertify.alert("Notice", "Break minutes always less than 59 minutes!");
            } else if (type == "hours" && break_hours > 23) {
                is_error = "yes";
                alertify.alert("Notice", "Break hours always less than 23 hours!");
            } else if (break_hours > hourDiff || (break_hours == hourDiff && break_minutes > 1)) {
                is_error = "yes";
                alertify.alert("Notice", "The break time can not be greater than the employees' shift time.!");
            }

            return is_error;
        }

        $(document).ready(function() {
            generateEmployeeWorkLog();
        });

        $('#js_offdays').select2('val', <?= json_encode($dayoffs); ?>);

        function generateEmployeeWorkLog() {
            var shift_start = $(".js-shift-start-time").val();
            var shift_end = $(".js-shift-end-time").val();
            var break_hours = $("#br_hours").val();
            var break_minutes = $("#br_mins").val();
            var dayoffs = $("#js_offdays").val();

            if (break_minutes.toString().length == 1) {
                break_minutes = '0' + break_minutes;
            }

            dayoffs = dayoffs != null ? dayoffs.length : 0;

            //create date format          
            var timeStart = new Date("01/01/2021 " + shift_start).getHours();
            var timeEnd = new Date("01/01/2021 " + shift_end).getHours();
            var breakHoursTotal = (((break_hours * 60) + parseInt(break_minutes)) / 60).toFixed(1);
            var hourDiff = timeEnd - timeStart - breakHoursTotal;
            var weekTotal = ((hourDiff) * (7 - dayoffs)).toFixed(1);
            var weekAllowedWorkHours = 40;
            var weekWorkableHours = weekTotal < weekAllowedWorkHours ? weekTotal : weekAllowedWorkHours;
            var overTime = weekTotal > weekAllowedWorkHours ? weekTotal - weekAllowedWorkHours : 0;

            var row = "";
            row += "<p>";

            if (overTime != 0) {
                row += "<span class='text-danger'>";
                row += "Any time over 40.00 hours a week goes into overtime.";
            }

            row += "The employee's daily workable time is of " + hourDiff.toFixed(2) + " hours.";
            row += " Employee's weekly workable time is " + weekWorkableHours.toFixed(2);
            row += weekWorkableHours > 1 ? " hours." : " hour.";

            if (overTime != 0) {
                row += " Employee's over time is " + overTime.toFixed(2);
                row += overTime > 1 ? " hours." : " hour.";
                row += "</span>";
            }

            row += "</p>";

            $("#update_employee_info").html(row);
        }

        $(".show_employee_working_info").on("change", function() {
            generateEmployeeWorkLog();
        });
    }


    <?php if ($access_level_plus == 1 && IS_PTO_ENABLED == 1) { ?>
        $('.js-shift-start-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            onShow: function(ct) {
                this.setOptions({
                    //maxTime: $('.js-shift-start-time').val() ? $('.js-shift-start-time').val() : false
                });
            }
        });
        $('.js-shift-end-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            onShow: function(ct) {
                time = $('.js-shift-start-time').val();
                if (time == '') return false;
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2));
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                    //minTime: $('.js-shift-start-time').val() ? timeFinal : false
                })
            }
        });
    <?php } ?>
    <?php if (IS_NOTIFICATION_ENABLED == 1 && $phone_sid != '') { ?>
        $('#notified_by').select2({
            closeOnSelect: false,
            allowHtml: true,
            allowClear: true,
            tags: true
        });
    <?php } ?>

    function remove_event(event_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this event?',
            function() {
                var my_request;
                my_request = $.ajax({
                    url: '<?php echo base_url('calendar/tasks'); ?>',
                    type: 'POST',
                    data: {
                        'action': 'delete_event',
                        'event_sid': event_sid
                    }
                });

                my_request.success(function(response) {
                    $('#remove_li' + event_sid + '').remove();
                    $('.btn').removeClass('disabled').prop('disabled', false);
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function submitResult() {
        var new_rehire_date = $('#js-rehire-date').val();
        //
        if (new_rehire_date != old_rehire_date) {
            var message = '';
            //
            if (old_rehire_date == '' || old_rehire_date == undefined) {
                var status = '"<strong>Rehired</strong>"';
                message = "By adding rehire date the employee's '<strong>Employee Status</strong>' will be changed to " + status + ".<br><br>Do you wish to continue?";
            } else {
                message = 'Are you sure you want to change the rehire date';
            }
            //

            alertify.confirm('Confirmation', message,
                function() {
                    $('#submit_form').click();
                    alertify.confirm().destroy();
                },
                function() {});
        } else {
            $('#submit_form').click();
            alertify.confirm().destroy();
        }
    }

    function validate_employers_form() {

        $("#edit_employer").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /[a-zA-Z\-,' ]+$/
                },
                middle_name: {
                    pattern: /^[a-zA-Z\-,' ]+$/
                },
                Location_Address: {
                    pattern: /^[a-zA-Z0-9/\-#,':;. ]+$/
                },
                email: {
                    email: true,
                },
                secondary_email: {
                    email: true,
                },
                other_email: {
                    email: true,
                },
                Location_State: {
                    pattern: /^[a-zA-Z0-9\-,' ]+$/
                },
                Location_City: {
                    pattern: /^[a-zA-Z0-9\-,' ]+$/
                },
                Location_ZipCode: {
                    pattern: /^[0-9\-]+$/
                },
                break_hours: {
                    number: true,
                    min: 0,
                    max: 23
                },
                break_mins: {
                    number: true,
                    min: 0,
                    max: 59
                },

                <?php if (get_company_module_status($session["company_detail"]["sid"], 'primary_number_required') == 1) { ?>
                    PhoneNumber: {
                        required: true
                    },
                <?php  } ?>


                <?php if ($access_level_plus == 1 && IS_PTO_ENABLED == 1) { ?>
                    shift_hours: {
                        required: true,
                        number: true,
                        min: 1,
                        max: 23
                    },
                    shift_mins: {
                        required: true,
                        number: true,
                        min: 0,
                        max: 59
                    },
                <?php } ?>
                YouTubeVideo: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                },
                <?php if ($portalData["uniform_sizes"]) { ?>
                    uniform_top_size: {
                        required: true
                    },
                    uniform_bottom_size: {
                        required: true
                    },
                <?php } ?>
            },
            messages: {
                first_name: {
                    required: 'First name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                first_name: {
                    pattern: 'Letters, numbers, and dashes only please'
                },
                email: {
                    required: 'Please provide Valid email'
                },
                secondary_email: {
                    required: 'Please provide Valid email'
                },
                other_email: {
                    required: 'Please provide Valid email'
                },
                Location_City: {
                    pattern: 'Letters, numbers, and dashes only please',
                },
                Location_Address: {
                    pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                },
                Location_ZipCode: {
                    pattern: 'Numeric values only'
                },
                PhoneNumber: {
                    // pattern: 'numbers and dashes only please'
                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                },
                secondary_PhoneNumber: {
                    pattern: 'numbers and dashes only please'
                },
                other_PhoneNumber: {
                    pattern: 'numbers and dashes only please'
                },
                YouTubeVideo: {
                    pattern: 'Please Enter a Valid Youtube Video Url.'
                },
                <?php if ($portalData["uniform_sizes"]) { ?>
                    uniform_top_size: {
                        required: "Uniform top size is required."
                    },
                    uniform_bottom_size: {
                        required: "Uniform bottom size is required."
                    },
                <?php } ?>
                break_hours: {
                    number: "please enter a number",
                    min: "Minimum allowed hours are 1",
                    max: "Maximum allowed hours are 23"
                },
                break_mins: {
                    number: "please enter a number",
                    min: "Minimum allowed minutes are 0",
                    max: "Maximum allowed minutes are 59"
                },
                <?php if ($access_level_plus == 1 && IS_PTO_ENABLED == 1) { ?>
                    shift_hours: {
                        required: "This field in required",
                        number: "please enter a number",
                        min: "Minimum allowed hours are 1",
                        max: "Maximum allowed hours are 23"
                    },
                    shift_mins: {
                        required: "This field in required",
                        number: "please enter a number",
                        min: "Minimum allowed minutes are 0",
                        max: "Maximum allowed minutes are 59"
                    },
                <?php } ?>
            },
            errorPlacement: function(e, el) {

                <?php if ($is_regex === 1) { ?>
                    if ($(el)[0].id == 'PhoneNumber' || $(el)[0].id == 'sh_hours' || $(el)[0].id == 'sh_mins') {
                        $(el).parent().after(e);
                        e.css("margin-bottom", "5px");
                    } else $(el).after(e);
                <?php } else { ?>
                    if ($(el)[0].id == 'sh_hours' || $(el)[0].id == 'sh_mins') {
                        $(el).parent().after(e);
                        $(e).css("margin-bottom", "5px");
                    } else $(el).after(e);
                <?php } ?>

            },
            submitHandler: function(form) {

                //
                if (timeOff == "enable") {
                    var shift_start = $(".js-shift-start-time").val();
                    var shift_end = $(".js-shift-end-time").val();
                    var break_hours = $("#br_hours").val();
                    var break_minutes = $("#br_mins").val();
                    var dayoffs = $("#js_offdays").val();

                    if (break_minutes.toString().length == 1) {
                        break_minutes = '0' + break_minutes;
                    }

                    dayoffs = dayoffs != null ? dayoffs.length : 0;

                    //create date format          
                    var timeStart = new Date("01/01/2021 " + shift_start).getHours();
                    var timeEnd = new Date("01/01/2021 " + shift_end).getHours();
                    var breakHoursTotal = (((break_hours * 60) + parseInt(break_minutes)) / 60).toFixed(1);
                    var hourDiff = timeEnd - timeStart - breakHoursTotal;
                    var weekTotal = ((hourDiff) * (7 - dayoffs)).toFixed(1);
                    var weekAllowedWorkHours = 40;
                    var weekWorkableHours = weekTotal < weekAllowedWorkHours ? weekTotal : weekAllowedWorkHours;
                    var overTime = weekTotal > weekAllowedWorkHours ? weekTotal - weekAllowedWorkHours : 0;

                    var row = "";
                    row += "<p>";

                    if (overTime != 0) {
                        row += "<span class='text-danger'>";
                        row += "Any time over 40.00 hours a week goes into overtime.</br>";
                    }

                    row += "The employee's daily workable time is of " + hourDiff.toFixed(2) + " hours.";
                    row += " Employee's weekly workable time is " + weekWorkableHours.toFixed(2);
                    row += weekWorkableHours > 1 ? " hours." : " hour.";

                    if (overTime != 0) {
                        row += " Employee's over time is " + overTime.toFixed(2);
                        row += overTime > 1 ? " hours." : " hour.";
                        row += "</span>";
                    }

                    row += "</p>";
                }

                var breakValidationError = validateBreakTime("validation");
                //
                if (breakValidationError == "yes") {
                    is_error = true;
                    return;
                }

                <?php if ($is_regex === 1) { ?>
                    // TODO
                    var is_error = false;

                    // Check for phone number
                    if (_pn.val() != '' && _pn.val().trim() != '(___) ___-____' && !fpn(_pn.val(), '', true)) {
                        alertify.alert('Invalid mobile number provided.', function() {
                            return;
                        });
                        is_error = true;
                        return;
                    }

                    // Check for secondary number
                    // if(_spn.val() != '' && _spn.val().trim() != '(___) ___-____' && !fpn(_spn.val(), '', true)){
                    //     alertify.alert('Invalid secondary mobile number provided.', function(){ return; });
                    //     is_error = true;
                    //     return;
                    // }
                    // // Check for other number
                    // if(_opn.val() != '' && _opn.val().trim() != '(___) ___-____' && !fpn(_opn.val(), '', true)){
                    //     alertify.alert('Invalid telephone number provided.', function(){ return; });
                    //     is_error = true;
                    //     return;
                    // }

                    if (is_error === false) {
                        // Remove and set phone extension
                        $('#js-phonenumber').remove();
                        // $('#js-secondary-phonenumber').remove();
                        // $('#js-other-phonenumber').remove();
                        // Check the fields
                        // if(_spn.val().trim() == '(___) ___-____') _spn.val('');
                        // else $("#edit_employer").append('<input type="hidden" id="js-secondary-phonenumber" name="txt_secondary_phonenumber" value="+1'+(_spn.val().replace(/\D/g, ''))+'" />');

                        // if(_opn.val().trim() == '(___) ___-____') _opn.val('');
                        // else $("#edit_employer").append('<input type="hidden" id="js-other-phonenumber" name="txt_other_phonenumber" value="+1'+(_opn.val().replace(/\D/g, ''))+'" />');


                        if (_pn.val().trim() == '(___) ___-____') _pn.val('');
                        else $("#edit_employer").append(
                            '<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1' + (_pn
                                .val().replace(/\D/g, '')) + '" />');

                        if (timeOff == "enable") {
                            console.log("up")
                            if (weekTotal > 40) {
                                alertify.confirm('Confirmation', row,
                                    function() {
                                        form.submit();

                                    },
                                    function() {});
                            } else {
                                form.submit();
                            }
                        } else {
                            form.submit();
                        }
                    }
                <?php } else { ?>

                    console.log("up 1")
                    if (timeOff == "enable") {
                        console.log("down")
                        if (weekTotal > 40) {
                            alertify.confirm('Confirmation', row,
                                function() {
                                    form.submit();
                                },
                                function() {});
                        } else {
                            form.submit();
                        }
                    } else {
                        form.submit();
                    }
                <?php } ?>
            }
        });
    }

    function check_file_all(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            alert(ext)
            if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext !=
                    "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    $(document).ready(function() {
        CKEDITOR.replace('rating_comment');
        CKEDITOR.replace('short_bio');
        CKEDITOR.replace('interests');

        CKEDITOR.replace('applicantMessage');
        var myid = $('#state_id').html();
        if (myid) {
            //console.log('I am IN');
            //console.log('My ID: '+myid);

            setTimeout(function() {
                $("#country").change();
            }, 1000);

            setTimeout(function() {
                //console.log('I am in');
                $('#state').val(myid);
            }, 2000);
        }

        $('#HorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function() {}
        });

        $('#template').on('change', function() {
            var template_sid = $(this).val();
            var msg_subject = $('#template_' + template_sid).attr('data-subject');
            var msg_body = $('#template_' + template_sid).attr('data-body');
            $('#applicantSubject').val(msg_subject);
            // $('#applicantMessage').html($(msg_body).text());
            CKEDITOR.instances.applicantMessage.setData(msg_body);
            $('.temp-attachment').hide();
            $('#empty-attachment').hide();
            $('#' + template_sid).show();
            if (template_sid == '') {
                $('#empty-attachment').show();
            }
        });

        var pre_selected = '<?php echo !empty($employer['YouTubeVideo']) ? $employer['video_type'] : ''; ?>';
        if (pre_selected == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (pre_selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (pre_selected == 'uploaded') {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').show();
        } else {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').hide();
        }

    });

    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option>');
        } else {
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }

    function modify_note(val) {
        var edit_note_text = document.getElementById(val).innerHTML;
        document.getElementById("sid").value = val;
        CKEDITOR.instances.my_edit_notes.setData(edit_note_text);
        $('#edit_notes').show();
        $('#show_hide').hide();
    }

    function cancel_notes() {
        $('#show_hide').show();
        $('#edit_notes').hide();
    }


    function cancel_event() {
        $('.event_detail').fadeIn();
        $('.event_create').hide();
    }

    function delete_note(id) {
        url = "<?= base_url() ?>application_tracking_system/delete_note";
        alertify.confirm('Confirmation', "Are you sure you want to delete this Note?",
            function() {
                $.post(url, {
                        sid: id
                    })
                    .done(function(data) {
                        location.reload();
                    });
            },
            function() {
                alertify.error('Canceled');
            });
    }

    function validate_form() {
        $("#event_form").validate({
            ignore: [],
            rules: {
                interviewer: {
                    required: true,
                },
                PhoneNumber: {
                    pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
                }
            },
            messages: {
                interviewer: {
                    required: 'Please select an interviewer',
                },
                PhoneNumber: {
                    required: 'Phone Number is required',
                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }

    $('.interviewer_comment').click(function() {
        if ($('.interviewer_comment').is(":checked")) {
            $('.comment-div').fadeIn();
            $('#interviewerComment').prop('required', true);

        } else {
            $('.comment-div').hide();
            $('#interviewerComment').prop('required', false);
        }
    });

    $('#candidate_msg').click(function() {
        if ($('#candidate_msg').is(":checked")) {
            $('.message-div').fadeIn();
            $('#applicantMessage').prop('required', true);
        } else {
            $('.message-div').hide();
            $('#applicantMessage').prop('required', false);
        }
    });

    $('.goto_meeting').click(function() {
        if ($('.goto_meeting').is(":checked")) {
            $('.meeting-div').fadeIn();
            $('#meetingId').prop('required', true);
            $('#meetingCallNumber').prop('required', true);
            $('#meetingURL').prop('required', true);
        } else {
            $('.meeting-div').hide();
            $('#meetingId').prop('required', false);
            $('#meetingCallNumber').prop('required', false);
            $('#meetingURL').prop('required', false);
        }
    });

    $('#edit_button').click(function(event) {
        event.preventDefault();
        $('.info_edit').fadeIn();
        $('.info_view').hide();

        setTimeout(function() {
            var team_id = $('#team_sid').html();
            var team_sids = <?php echo json_encode($team_sids ? $team_sids : []); ?>;
            if (team_id != 0) {
                var html = '';
                var department_sid = $('#department').val();
                var myurl = "<?= base_url() ?>employee_management/get_all_department_teams/" +
                    department_sid;
                $.ajax({
                    type: "GET",
                    url: myurl,
                    async: false,
                    success: function(data) {
                        if (data != 0) {
                            var obj = jQuery.parseJSON(data);
                            allteams = obj;
                            for (var i = 0; i < allteams.length; i++) {
                                var id = allteams[i].sid;
                                var name = allteams[i].name;
                                // if (team_id == id) {

                                if (jQuery.inArray(id, team_sids) !== -1) {
                                    html += '<option value="' + id + '" selected="selected">' +
                                        name + '</option>';
                                } else {
                                    html += '<option value="' + id + '">' + name + '</option>';
                                }
                                // html += '<option value="' + id + '">' + name + '</option>';
                            }
                            $('#teams').html(html).select2();
                        }
                    },
                    error: function(data) {

                    }
                });
            }

        }, 1000);

        //
        var state_id = "<?= (isset($employer['Location_State'])) ? $employer['Location_State'] : 0; ?>";
        $('#state option[value="' + state_id + '"]').attr('selected', 'selected');
    });

    $('.view_button').click(function(event) {
        event.preventDefault();
        $('.info_edit').hide();
        $('.info_view').fadeIn();
    });

    $('#add_notes').click(function(event) {
        event.preventDefault();
        $('.note_div').fadeIn();
        $('.no_note').hide();
    });

    $('.interviewer').select2({
        placeholder: "Select participant(s)",
        allowClear: true
    });

    $('.select2-dropdown').css('z-index', '99999999999999999999999');

    $('.eventendtime_create').datetimepicker({
        datepicker: false,
        format: 'g:iA',
        formatTime: 'g:iA',
        step: 15,
        onShow: function(ct) {
            time = $($('.eventstarttime_create').get(0)).val();
            timeAr = time.split(":");
            last = parseInt(timeAr[1].substr(0, 2)) + 15;
            if (last == 0)
                last = "00";
            mm = timeAr[1].substr(2, 2);
            timeFinal = timeAr[0] + ":" + last + mm;
            this.setOptions({
                minTime: $($('.eventstarttime_create').get(0)).val() ? timeFinal : false
            })
        }
    });

    $('.eventstarttime_create').datetimepicker({
        datepicker: false,
        format: 'g:iA',
        formatTime: 'g:iA',
        step: 15,
        onShow: function(ct) {
            time = $($('.eventendtime_create').get(0)).val();
            timeAr = time.split(":");
            last = parseInt(timeAr[1].substr(0, 2)) + 15;
            if (last == 0)
                last = "00";
            mm = timeAr[1].substr(2, 2);
            timeFinal = timeAr[0] + ":" + last + mm;
            this.setOptions({
                maxTime: $($('.eventendtime_create').get(0)).val() ? timeFinal : false
            })
        }
    });

    $('.eventdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();
    $('#eventdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();
    $("#eventdate").datepicker("setDate", new Date());
    $('#add_event').click(function() {
        $('.event_create').fadeIn();
        $('.event_detail').hide();
    });

    $('#cancel_note').click(function(event) {
        event.preventDefault();
        $('.note_div').hide();
        $('#add_notes').fadeIn();
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName);
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            if (val == 'resume' || val == 'cover_letter') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "jpg" && ext != "jpeg" && ext != "png" && ext !=
                    "jpe" && ext != "gif" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .jpg .jpeg .png .jpe .gif) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function check_profile_picture(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName);
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'pictures' || val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "gif") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png .jpe .gif) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function check_file_all(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html('<span>' + fileName + '</span>');
            //console.log(fileName);
        } else {
            $('#name_' + val).html('Please Select');
            //console.log('in else case');
        }
    }

    $('.startdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50",
    }).val();

    $('#date_of_birth').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();

    $('.js-joining-date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo JOINING_DATE_LIMIT; ?>",
    }).val();

    $('.js-rehireDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo JOINING_DATE_LIMIT; ?>",
    }).val();

    $('#add_edit_submit').click(function() {
        if ($('input[name="video_source"]:checked').val() != 'no_video') {
            $('#my_loader').show();
            var flag = 0;
            if ($('input[name="video_source"]:checked').val() == 'youtube') {

                if ($('#yt_vm_video_url').val() != '') {

                    var p =
                        /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                    if (!$('#yt_vm_video_url').val().match(p)) {
                        alertify.error('Not a Valid Youtube URL');
                        flag = 0;
                        $('#my_loader').hide();
                        return false;
                    } else {
                        flag = 1;
                    }
                } else {
                    flag = 0;
                    alertify.error('Please add valid youtube video link.');
                    $('#my_loader').hide();
                    return false;
                }
            } else if ($('input[name="video_source"]:checked').val() == 'vimeo') {

                if ($('#yt_vm_video_url').val() != '') {
                    var flag = 0;
                    var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {
                            url: $('#yt_vm_video_url').val()
                        },
                        async: false,
                        success: function(data) {
                            if (data == false) {
                                alertify.error('Not a Valid Vimeo URL');
                                flag = 0;
                                $('#my_loader').hide();
                                return false;
                            } else {
                                flag = 1;
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    flag = 0;
                    alertify.error('Please add valid vimeo video link.');
                    $('#my_loader').hide();
                    return false;
                }
            } else if ($('input[name="video_source"]:checked').val() == 'uploaded') {
                var old_uploaded_video = $('#pre_upload_video_url').val();
                if (old_uploaded_video != '') {
                    flag = 1;
                } else {
                    var file = upload_video_checker('upload_video');
                    if (file == false) {
                        flag = 0;
                        $('#my_loader').hide();
                        return false;
                    } else {
                        flag = 1;
                    }
                }
            }

            if (flag == 1) {
                // $('#my_loader').show();
                return true;
            } else {
                $('#my_loader').hide();
                return false;
            }
        } else {
            return true;
        }
    });

    $('.video_source').on('click', function() {
        var selected = $(this).val();
        if (selected == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'uploaded') {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').show();
        } else {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').hide();
        }
    });

    function upload_video_checker(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'upload_video') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext !=
                    "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html(
                        '<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1,
                            selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }

            }
        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
            return false;

        }
    }

    function get_teams(department_sid) {
        var html = '';
        if (department_sid == 0) {
            html += '<option value="">Select Team</option>';
            html += '<option value="">Please Select your Department</option>';
            $('#teams').html(html);
        } else {
            var myurl = "<?= base_url() ?>employee_management/get_all_department_teams/" + department_sid;
            $.ajax({
                type: "GET",
                url: myurl,
                async: false,
                success: function(data) {
                    if (data == 0) {
                        html += '<option value="">Select Team</option>';
                        html += '<option value="">Please Select your Department</option>';
                        $('#teams').html(html);
                        alertify.error('No Teams Found Please select Department Again');
                    } else {
                        var obj = jQuery.parseJSON(data);
                        allteams = obj;
                        for (var i = 0; i < allteams.length; i++) {
                            var id = allteams[i].sid;
                            var name = allteams[i].name;
                            html += '<option value="' + id + '">' + name + '</option>';
                        }
                        $('#teams').html(html);
                    }
                },
                error: function(data) {

                }
            });
        }
    }
    $('.js-timezone').select2();

    <?php if ($is_regex === 1) { ?>
        // Set targets
        var _pn = $("#<?= $field_phone; ?>");
        // _spn = $("#<?= $field_sphone; ?>"),
        // _opn = $("#<?= $field_ophone; ?>");

        // Reset phone number on load
        // Added on: 05-07-2019
        var val = fpn(_pn.val());
        if (typeof(val) === 'object') {
            _pn.val(val.number);
            setCaretPosition(_pn, val.cur);
        } else _pn.val(val);

        // // Reset phone number on load
        _pn.keyup(function() {
            var val = fpn($(this).val());
            if (typeof(val) === 'object') {
                $(this).val(val.number);
                setCaretPosition(this, val.cur);
            } else $(this).val(val);
        });

        // var val2 = fpn(_spn.val());
        // if(typeof(val2) === 'object'){
        //     _spn.val(val2.number);
        //     setCaretPosition(_spn, val2.cur);
        // } else _spn.val(val2);
        // // Reset phone number on load
        // _spn.keyup(function(){
        //     var val = fpn($(this).val());
        //     if(typeof(val) === 'object'){
        //         $(this).val(val.number);
        //         setCaretPosition(this, val.cur);
        //     } else $(this).val(val);
        // });

        // var val3 = fpn(_opn.val());
        // if(typeof(val3) === 'object'){
        //     _opn.val(val3.number);
        //     setCaretPosition(_opn, val3.cur);
        // } else _opn.val(val3);
        // // Reset phone number on load
        // _opn.keyup(function(){
        //     var val = fpn($(this).val());
        //     if(typeof(val) === 'object'){
        //         $(this).val(val.number);
        //         setCaretPosition(this, val.cur);
        //     } else $(this).val(val);
        // });

        // Format Phone Number
        // @param phone_number
        // The phone number string that
        // need to be reformatted
        // @param format
        // Match format
        // @param is_return
        // Verify format or change format
        function fpn(phone_number, format, is_return) {
            //
            var default_number = '(___) ___-____';
            var cleaned = phone_number.replace(/\D/g, '');
            if (cleaned.length > 10) cleaned = cleaned.substring(0, 10);
            match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
            //
            if (match) {
                var intlCode = '';
                if (format == 'e164') intlCode = (match[1] ? '+1 ' : '');
                return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
            } else {
                var af = '',
                    an = '',
                    cur = 1;
                if (cleaned.substring(0, 1) != '') {
                    af += "(_";
                    an += '(' + cleaned.substring(0, 1);
                    cur++;
                }
                if (cleaned.substring(1, 2) != '') {
                    af += "_";
                    an += cleaned.substring(1, 2);
                    cur++;
                }
                if (cleaned.substring(2, 3) != '') {
                    af += "_) ";
                    an += cleaned.substring(2, 3) + ') ';
                    cur = cur + 3;
                }
                if (cleaned.substring(3, 4) != '') {
                    af += "_";
                    an += cleaned.substring(3, 4);
                    cur++;
                }
                if (cleaned.substring(4, 5) != '') {
                    af += "_";
                    an += cleaned.substring(4, 5);
                    cur++;
                }
                if (cleaned.substring(5, 6) != '') {
                    af += "_-";
                    an += cleaned.substring(5, 6) + '-';
                    cur = cur + 2;
                }
                if (cleaned.substring(6, 7) != '') {
                    af += "_";
                    an += cleaned.substring(6, 7);
                    cur++;
                }
                if (cleaned.substring(7, 8) != '') {
                    af += "_";
                    an += cleaned.substring(7, 8);
                    cur++;
                }
                if (cleaned.substring(8, 9) != '') {
                    af += "_";
                    an += cleaned.substring(8, 9);
                    cur++;
                }
                if (cleaned.substring(9, 10) != '') {
                    af += "_";
                    an += cleaned.substring(9, 10);
                    cur++;
                }

                if (is_return) return match === null ? false : true;

                return {
                    number: default_number.replace(af, an),
                    cur: cur
                };
            }
        }

        // Change cursor position in input
        function setCaretPosition(elem, caretPos) {
            if (elem != null) {
                if (elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.move('character', caretPos);
                    range.select();
                } else {
                    if (elem.selectionStart) {
                        elem.focus();
                        elem.setSelectionRange(caretPos, caretPos);
                    } else elem.focus();
                }
            }
        }

    <?php } ?>


    //
    <?php if ($templateTitles) { ?>

        <?php if ($employer['job_title_type'] != '0') { ?>
            $('#temppate_job_title').show();
            $('#temppate_job_title').val('<?php echo $employer['job_title_type'] . '#' . $employer['job_title']; ?>');
            $('#job_title').hide();
        <?php } ?>

        $('.titleoption').click(function() {
            var titleOption = $(this).val();
            if (titleOption == 'dropdown') {
                $('#temppate_job_title').show();
                $('#temppate_job_title').val('<?php echo $employer['job_title_type'] == '0' ? '0' : $employer['job_title_type'] . '#' . $employer['job_title']; ?>');
                $('#job_title').hide();
            } else if (titleOption == 'manual') {
                $('#temppate_job_title').hide();
                $('#temppate_job_title').val('0');
                $('#job_title').show();
            }

        });
    <?php } ?>
</script>

<style>
    .select2-container--default .select2-selection--single {
        border: 2px solid #aaaaaa !important;
        background-color: #f7f7f7 !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding: 5px 20px 5px 8px !important;
    }

    .select2-container {
        width: 100%;
    }

    .select2-results__option {
        padding-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option:before {
        content: "";
        display: inline-block;
        position: relative;
        height: 20px;
        width: 20px;
        border: 2px solid #e9e9e9;
        border-radius: 4px;
        background-color: #fff;
        margin-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option[aria-selected=true]:before {
        font-family: fontAwesome;
        content: "\f00c";
        color: #fff;
        background-color: #81b431;
        border: 0;
        display: inline-block;
        padding-left: 3px;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #fff;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eaeaeb;
        color: #272727;
    }

    .select2-container--default .select2-selection--multiple {
        margin-bottom: 10px;
    }

    .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
        border-radius: 4px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #81b431;
        border-width: 2px;
    }

    .select2-container--default .select2-selection--multiple {
        border-width: 2px;
    }

    .select2-container--open .select2-dropdown--below {

        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

    }

    .select2-selection .select2-selection--multiple:after {
        content: 'hhghgh';
    }

    /* select with icons badges single*/
    .select-icon .select2-selection__placeholder .badge {
        display: none;
    }

    .select-icon .placeholder {
        display: none;
    }

    .select-icon .select2-results__option:before,
    .select-icon .select2-results__option[aria-selected=true]:before {
        display: none !important;
        /* content: "" !important; */
    }

    .select-icon .select2-search--dropdown {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        height: 25px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 30px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #81b431;
        border-color: #81b431;
        color: #ffffff;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 40px;
    }

    .select2-selection__choice__remove {
        color: #ffffff !important;
    }

    .select2-selection__rendered li {
        height: 24px !important;
    }

    .update_employee_info_container {
        height: 28px !important;
        font-size: 18px;
        padding-top: 0px;
    }

    @media screen and (max-width: 768px) {
        .shift_end {
            margin-top: 12px
        }

        .shift_div {
            padding-left: 0px;
            padding-right: 0px;
        }
    }
</style>