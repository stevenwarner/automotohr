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
                                        <form id="save_jobinfo" method="POST" enctype="multipart/form-data">
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
                                                                Note: The FLSA status for this compensation. Salaried ('Exempt') employees are paid a fixed salary every pay period. Salaried with overtime ('Salaried Nonexempt') employees are paid a fixed salary every pay period, and receive overtime pay when applicable. Hourly ('Nonexempt') employees are paid for the hours they work, and receive overtime pay when applicable. Owners ('Owner') are employees that own at least twenty percent of the company. </em>
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6 form-group">
                                                    <?php $templateTitles = get_templet_jobtitles($employer['parent_sid']); ?>

                                                    <label>Job Title: &nbsp;&nbsp;&nbsp;
                                                        <?php if ($templateTitles) { ?>
                                                            <input type="radio" name="title_option" value="dropdown" class="titleoption" <?php echo $employer['job_title_type'] != '0' ? 'checked' : '' ?>> Choose Job Title&nbsp;&nbsp;
                                                            <input type="radio" name="title_option" value="manual" class="titleoption" <?php echo $employer['job_title_type'] == '0' ? 'checked' : '' ?>> Custom Job Title &nbsp;
                                                        <?php } ?>
                                                    </label>
                                                    <input class="invoice-fields" value="<?php echo set_value('job_title', $jobTitleData["job_title"]); ?>" type="text" name="job_title" id="job_title">
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
                                                    <select class="invoice-fields" id="flsa" name="flsa">
                                                        <option value="Exempt">Exempt</option>
                                                        <option value="Salaried Nonexempt">Salaried Nonexempt</option>
                                                        <option value="Nonexempt">Nonexempt</option>
                                                        <option value="Owner">Owner</option>
                                                    </select>
                                                    <?php echo form_error('flsa'); ?>
                                                </div>


                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <!--  --><br>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" name="is_primary" id="is_primary" value="1" <?= $jobTitleData['is_primary'] != 0 ? 'checked' : ''; ?> /> Primary?
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <!--  --><br>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" name="is_active" value="1" <?= $jobTitleData['is_active'] != 0 ? 'checked' : ''; ?> /> Is Avtive?
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <!--  -->
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                    <?php
                                                    $shift_start = isset($jobTitleData['normal_shift_start_time']) && !empty($jobTitleData['normal_shift_start_time']) ? $jobTitleData['normal_shift_start_time'] : SHIFT_START;
                                                    $shift_end = isset($jobTitleData['normal_shift_end_time']) && !empty($jobTitleData['normal_shift_end_time']) ? $jobTitleData['normal_shift_end_time'] : SHIFT_END;
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label>Shift start time:</label>
                                                            <input class="invoice-fields js-shift-start-time show_employee_working_info" readonly="true" value="<?php echo $shift_start; ?>" type="text" name="normal_shift_start_time">
                                                        </div>
                                                        <div class="col-sm-6" style="padding-right: 0px;">
                                                            <label>Shift End time:</label>
                                                            <input class="invoice-fields js-shift-end-time show_employee_working_info" readonly="true" value="<?php echo $shift_end; ?>" type="text" name="normal_shift_end_time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  -->
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                    <label>Break</label>
                                                    <?php
                                                    $break_hours = isset($jobTitleData['normal_break_hour']) ? $jobTitleData['normal_break_hour'] : BREAK_HOURS;
                                                    $break_minutes = isset($jobTitleData['normal_break_minutes']) && !empty($jobTitleData['normal_break_minutes']) ? $jobTitleData['normal_break_minutes'] : BREAK_MINUTES;
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-6 shift_div">
                                                            <div class="input-group">
                                                                <input min="0" max="23" oninput="this.value = Math.abs(this.value)" id="normal_break_hour" type="number" value="<?php echo  $break_hours; ?>" name="normal_break_hour" class="invoice-fields show_employee_working_info emp_break_info" data-type="hours">
                                                                <div class="input-group-addon"> Hours </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 shift_div shift_end">
                                                            <div class="input-group">
                                                                <input min="0" max="59" oninput="this.value = Math.abs(this.value)" type="number" value="<?php echo  $break_minutes; ?>" id="normal_break_minutes" name="normal_break_minutes" class="invoice-fields show_employee_working_info emp_break_info" data-type="minutes">
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
                                                    <?php $dayoffs = isset($jobTitleData['normal_week_days_off']) && !empty($jobTitleData['normal_week_days_off']) ? explode(',', $jobTitleData['normal_week_days_off']) : [DAY_OFF]; ?>
                                                    <select class="show_employee_working_info" name="normal_week_days_off[]" id="js_offdays" multiple="true">
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
                                                        <input type="text" class="form-control input-lg rate" placeholder="0.0" value="<?php echo $jobTitleData['normal_rate'] ?>" name="normal_rate">
                                                    </div>
                                                    <?php echo form_error('normal_rate'); ?>

                                                </div>


                                                <div class=" col-lg-2 col-md-2 col-xs-12 col-sm-2  form-group">
                                                    <label class="csF16">Per <strong class="text-danger">*</strong></label>
                                                    <select class="form-control input-lg rate_per" name="normal_per" id="normal_per">
                                                        <option value="Hour">Per hour</option>
                                                        <option value="Week">Per week</option>
                                                        <option value="Month">Per month</option>
                                                        <option value="Year">Per year</option>
                                                        <option value="Paycheck">Per paycheck</option>
                                                    </select>
                                                    <?php echo form_error('normal_per'); ?>


                                                </div>


                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <label>Effected Date: <strong class="text-danger">*</strong></label>

                                                    <?php
                                                    $effectedDate = $jobTitleData['normal_effected_date'] != NULL && $jobTitleData['normal_effected_date'] != '0000-00-00' ? DateTime::createFromFormat('Y-m-d', $jobTitleData['normal_effected_date'])->format('m-d-Y') : '';
                                                    ?>
                                                    <input class="invoice-fields js-rehireDate" id="normal_effected_date" readonly="" type="text" name="normal_effected_date" value="<?php echo $effectedDate; ?>">
                                                    <?php echo form_error('normal_effected_date'); ?>
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
                                                            $shift_start = isset($jobTitleData['overtime_shift_start_time']) && !empty($jobTitleData['overtime_shift_start_time']) ? $jobTitleData['overtime_shift_start_time'] : SHIFT_START;
                                                            $shift_end = isset($jobTitleData['overtime_shift_end_time']) && !empty($jobTitleData['overtime_shift_end_time']) ? $jobTitleData['overtime_shift_end_time'] : SHIFT_END;
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label>Shift start time:</label>
                                                                    <input class="invoice-fields js-shift-start-time show_employee_working_info" readonly="true" value="<?php echo $shift_start; ?>" type="text" name="overtime_shift_start_time">
                                                                </div>
                                                                <div class="col-sm-6" style="padding-right: 0px;">
                                                                    <label>Shift End time:</label>
                                                                    <input class="invoice-fields js-shift-end-time show_employee_working_info" readonly="true" value="<?php echo $shift_end; ?>" type="text" name="overtime_shift_end_time">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <label class="csF16">Rate <strong class="text-danger">*</strong></label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-dollar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control input-lg jsEmployeeFlowAmount rate" placeholder="0.0" value="<?php echo $jobTitleData['overtime_rate']; ?>" name="overtime_rate">
                                                            </div>
                                                            <?php echo form_error('overtime_rate'); ?>

                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <!--  --><br>
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="overtime_is_allowed" value="1" <?= $jobTitleData['overtime_is_allowed'] != 0 ? 'checked' : ''; ?> /> is Allowed?
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
                                                            $shift_start = isset($jobTitleData['double_overtime_shift_start_time']) && !empty($jobTitleData['double_overtime_shift_start_time']) ? $jobTitleData['double_overtime_shift_start_time'] : SHIFT_START;
                                                            $shift_end = isset($jobTitleData['double_overtime_shift_end_time']) && !empty($jobTitleData['double_overtime_shift_end_time']) ? $jobTitleData['double_overtime_shift_end_time'] : SHIFT_END;
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label>Shift start time:</label>
                                                                    <input class="invoice-fields js-shift-start-time show_employee_working_info" readonly="true" value="<?php echo $shift_start; ?>" type="text" name="double_overtime_shift_start_time">
                                                                </div>
                                                                <div class="col-sm-6" style="padding-right: 0px;">
                                                                    <label>Shift End time:</label>
                                                                    <input class="invoice-fields js-shift-end-time show_employee_working_info" readonly="true" value="<?php echo $shift_end; ?>" type="text" name="double_overtime_shift_end_time">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <label class="csF16">Rate <strong class="text-danger">*</strong></label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-dollar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control input-lg jsEmployeeFlowAmount rate" placeholder="0.0" value="<?php echo $jobTitleData['double_overtime_rate']; ?>" name="double_overtime_rate">
                                                            </div>
                                                            <?php echo form_error('double_overtime_rate'); ?>

                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <!--  --><br>
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="double_overtime_is_allowed" value="1" <?= $jobTitleData['double_overtime_is_allowed'] != 0 ? 'checked' : ''; ?> /> is Allowed?
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
                                                                <input type="text" class="form-control input-lg jsEmployeeFlowAmount rate" placeholder="0.0" value="<?php echo $jobTitleData['holiday_rate']; ?>" name="holiday_rate">
                                                            </div>
                                                            <?php echo form_error('holiday_rate'); ?>

                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                            <!--  --><br>
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="holiday_overtime_is_allowed" value="1" <?= $jobTitleData['holiday_overtime_is_allowed'] != 0 ? 'checked' : ''; ?> /> is Allowed?
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


    //
    $('#flsa').val('<?php echo $jobTitleData['flsa'] ?>');
    $('#normal_per').val('<?php echo $jobTitleData['normal_per'] ?>');



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


    function submitResult() {

        var myurl = "<?= base_url() ?>job_info_primary_check/<?php echo $employeetype ?>/<?php echo $employeesid ?>";
        var isprimary = false;
        if ($("#is_primary").prop('checked') == true) {

            $.ajax({
                type: 'GET',
                url: myurl,
                success: function(res) {

                    if (res == 'yes') {

                        alertify.confirm('Notice', 'A Job marked as primary is already exist. do you wants to mark this job as primary?', function() {
                            $('#save_jobinfo').submit();

                        }, function() {

                            $('#is_primary').prop('checked', false);

                            $('#save_jobinfo').submit();
                        });

                    } else {
                        $('#save_jobinfo').submit();
                    }

                },
                error: function() {

                }
            });

        } else {
            $('#save_jobinfo').submit();

        }


    }




    $('.js-rehireDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo JOINING_DATE_LIMIT; ?>",
    }).val();



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

    $('#temppate_job_title').on('change', function() {

        var jobtitle = this.value;
        let jobtitleAry = jobtitle.split("#");
        $('#job_title').val(jobtitleAry[1]);
    });



    //
    $('.rate').on('change', function() {

        var rate = this.value;
        if (rate < 1) {
            alertify.alert("Notice", "Rate always greater than 0!");
            this.value = 1;
        }
    });
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