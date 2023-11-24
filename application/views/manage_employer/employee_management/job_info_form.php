<style>
    .normal {
        font-weight: normal;

    }
</style>
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
                                                    <a href="<?php echo base_url('job_info') . '/employee/' . $employer["sid"]; ?>" class="btn btn-danger">Cancel</a>&nbsp;
                                                    <a href="<?php echo base_url('job_info') . '/employee/' . $employer["sid"]; ?>" class="btn black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Jobs</a>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group">
                                                    <p class="text-danger csF16">

                                                    </p>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">
                                                    <?php $templateTitles = get_templet_jobtitles($employer['parent_sid']); ?>

                                                    <label>Job Title: &nbsp;&nbsp;&nbsp;
                                                        <?php if ($templateTitles) { ?>
                                                            <input type="radio" name="title_option" value="dropdown" class="titleoption" <?php echo $employer['job_title_type'] != '0' ? 'checked' : '' ?>> Choose Job Title&nbsp;&nbsp;
                                                            <input type="radio" name="title_option" value="manual" class="titleoption" <?php echo $employer['job_title_type'] == '0' ? 'checked' : '' ?>> Custom Job Title &nbsp;
                                                        <?php } ?>
                                                    </label>
                                                    <input class="invoice-fields" value="<?php echo set_value('job_title', $jobTitleData["title"]); ?>" type="text" name="job_title" id="job_title">
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
                                                    $shift_start = isset($jobTitleData['shift_start_time']) && !empty($jobTitleData['shift_start_time']) ? $jobTitleData['shift_start_time'] : SHIFT_START;
                                                    $shift_end = isset($jobTitleData['shift_end_time']) && !empty($jobTitleData['shift_end_time']) ? $jobTitleData['shift_end_time'] : SHIFT_END;
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
                                                    $break_hours = isset($jobTitleData['break_hour']) ? $jobTitleData['break_hour'] : BREAK_HOURS;
                                                    $break_minutes = isset($jobTitleData['break_minutes']) && !empty($jobTitleData['break_minutes']) ? $jobTitleData['break_minutes'] : BREAK_MINUTES;
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
                                                    <?php $dayoffs = isset($jobTitleData['week_days_off']) && !empty($jobTitleData['week_days_off']) ? explode(',', $jobTitleData['week_days_off']) : [DAY_OFF]; ?>
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
                                            </div>


                                            <?php if ($this->uri->segment(1) != 'job_info_add') { ?>

                                                <div class="panel panel-success">
                                                    <div class="panel-heading text-right">

                                                        <h1 class="csF16 csW m0">
                                                            <span class="text-left" style="float: left;margin-top: 10px;"><strong> Compensations</strong></span>
                                                            <a href="<?php echo base_url('job_compensation_add/' . $jobTitleData['sid']) ?>" class="btn csBG3 csBR5  csW  csF16" title="" placement="top" data-original-title="Add Compensation">
                                                                <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>&nbsp;
                                                                Add Compensation
                                                            </a>
                                                        </h1>

                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <?php
                                                            if (!empty($jobCompensationData)) {
                                                                foreach ($jobCompensationData as $compensationRow) {
                                                            ?>
                                                                    <table class="table table-bordered table-striped fixTable-header">

                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="row">
                                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 form-group">

                                                                                            <h4 style="margin-top: 0">
                                                                                            </h4>

                                                                                            <p class="csF16 normal">
                                                                                                <strong>FLSA Status:</strong> <?php echo $compensationRow['flsa_status'] ?>
                                                                                            </p>

                                                                                            <p class="csF16 normal">
                                                                                                <strong>Effective Date:</strong> <?php echo $compensationRow['effective_date'] ?>
                                                                                            </p>

                                                                                            <p class="csF16 normal">
                                                                                                <strong>Per:</strong> <?php echo $compensationRow['per'] ?>
                                                                                            </p>
                                                                                            <p class="csF16 normal">
                                                                                                <strong>Amount:</strong> $ <?php echo $compensationRow['compensation_multiplier'] ?>
                                                                                            </p>
                                                                                        </div>

                                                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5 form-group">

                                                                                            <p class="csF16">
                                                                                                <?php if ($compensationRow['is_primary']) { ?>
                                                                                                    <i class="fa fa-check csF16 text-success" aria-hidden="true"></i>&nbsp;<?php } else { ?>
                                                                                                    <i class="fa fa-close csF16 text-danger" aria-hidden="true"></i>&nbsp;

                                                                                                <?php } ?>
                                                                                                is Primary?
                                                                                            </p>

                                                                                            <p class="csF16">
                                                                                                <?php if ($compensationRow['is_active']) { ?>
                                                                                                    <i class="fa fa-check csF16 text-success" aria-hidden="true"></i>&nbsp;<?php } else { ?>
                                                                                                    <i class="fa fa-close csF16 text-danger" aria-hidden="true"></i>&nbsp;
                                                                                                <?php } ?>
                                                                                                is Active?
                                                                                            </p>

                                                                                        </div>

                                                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1 form-group">
                                                                                            <p class="csF16">
                                                                                                <strong><a href="<?php echo base_url('job_compensation_edit/' . $jobTitleData['sid'] . '/' . $compensationRow['sid']) ?>" class="btn btn-success btn-sm" title="Edit Job"><i class="fa fa-pencil"></i></a></strong>
                                                                                            </p>
                                                                                            <p class="csF16">
                                                                                                <?php if ($compensationRow['is_primary'] != 1) { ?>
                                                                                                    <strong><a href="javascript:;" class="btn btn-danger btn-sm" title="Delete Compensation" onclick="deleteCompensation('<?php echo $jobTitleData['sid'] ?>','<?php echo $compensationRow['sid'] ?>')"><i class="fa fa-times"></i></a></strong>
                                                                                                <?php } ?>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                            <?php }
                                                            } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if ($this->uri->segment(1) != 'job_info_add') { ?>
                                                <div class="panel panel-success">
                                                    <div class="panel-heading  text-right">

                                                        <h1 class="csF16 csW m0">
                                                            <span class="text-left" style="float: left;margin-top: 10px;"><strong> Earnings</strong></span>
                                                            <a href="<?php echo base_url('job_earnings_add/' . $jobTitleData['sid']) ?>" class="btn csBG3 csBR5  csW  csF16" title="" placement="top" data-original-title="Add Earning">
                                                                <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>&nbsp;
                                                                Add Earning
                                                            </a>
                                                        </h1>

                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group">
                                                                <?php if ($jobEarningsDetail) { ?>
                                                                    <table class="table table-bordered table-hover table-striped">
                                                                        <caption></caption>
                                                                        <thead>
                                                                            <th>Name</th>
                                                                            <th class="text-center">Is Default?</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            if ($jobEarningsDetail) {

                                                                                foreach ($jobEarningsDetail as $earningRow) {
                                                                            ?>
                                                                                    <tr>
                                                                                        <td><?= $earningRow['name']; ?></td>
                                                                                        <td class="text-center"><?= $earningRow['is_default'] ? "Yes" : "No"; ?></td>
                                                                                    </tr>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>


                                                                            <tr>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                <?php } ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>


                                            <?php if ($this->uri->segment(1) != 'job_info_add') { ?>

                                                <div class="panel panel-success">
                                                    <?php

                                                    foreach ($jobEarningsData as $overtimedata) {

                                                        if ($overtimedata['type'] == 'overtime' && $overtimedata['gusto_companies_earning_sid'] == NULL) {
                                                            $overtime_shift_start_time = $overtimedata['shift_start_time'];
                                                            $overtime_shift_end_time = $overtimedata['shift_end_time'];
                                                            $overtime_rate = $overtimedata['rate'];
                                                            $overtime_is_allowed = $overtimedata['is_allowed'];
                                                        }

                                                        if ($overtimedata['type'] == 'double_overtime') {
                                                            $double_overtime_shift_start_time = $overtimedata['shift_start_time'];
                                                            $double_overtime_shift_end_time = $overtimedata['shift_end_time'];
                                                            $double_overtime_rate = $overtimedata['rate'];
                                                            $double_overtime_is_allowed = $overtimedata['is_allowed'];
                                                        }

                                                        if ($overtimedata['type'] == 'holiday') {
                                                            $holiday_rate = $overtimedata['rate'];
                                                            $holiday_overtime_is_allowed = $overtimedata['is_allowed'];
                                                        }
                                                    }

                                                    ?>

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
                                                                $shift_start = isset($overtime_shift_start_time) && !empty($overtime_shift_start_time) ? $overtime_shift_start_time : SHIFT_START;
                                                                $shift_end = isset($overtime_shift_end_time) && !empty($overtime_shift_end_time) ? $overtime_shift_end_time : SHIFT_END;
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
                                                                <label class="csF16">Multiplier</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-lg jsEmployeeFlowAmount rate" placeholder="0.0" value="<?php echo $overtime_rate; ?>" name="overtime_rate">
                                                                </div>
                                                                <?php echo form_error('overtime_rate'); ?>

                                                            </div>

                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 form-group">
                                                                <!--  --><br>
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="overtime_is_allowed" value="1" <?= $overtime_is_allowed != 0 ? 'checked' : ''; ?> <?= $isOvertimeAllowed == 0 ? 'disabled' : ''; ?> /> is Allowed?
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                                <?php if ($isOvertimeAllowed == 0) { ?>
                                                                    <p class="text-danger csF16" style="float: left;">
                                                                        <strong>
                                                                            <em>
                                                                                Note: In Primary Compensation The FLSA status is (Exempt/Owner) You cannot allow Double overtime .</em>
                                                                        </strong>
                                                                    </p>
                                                                <?php } ?>
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
                                                                $shift_start = isset($double_overtime_shift_start_time) && !empty($double_overtime_shift_start_time) ? $double_overtime_shift_start_time : SHIFT_START;
                                                                $shift_end = isset($double_overtime_shift_end_time) && !empty($double_overtime_shift_end_time) ? $double_overtime_shift_end_time : SHIFT_END;
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
                                                                <label class="csF16">Multiplier</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-lg jsEmployeeFlowAmount rate" placeholder="0.0" value="<?php echo $double_overtime_rate; ?>" name="double_overtime_rate">
                                                                </div>
                                                                <?php echo form_error('double_overtime_rate'); ?>

                                                            </div>

                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 form-group">
                                                                <!--  --><br>
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="double_overtime_is_allowed" value="1" <?= $double_overtime_is_allowed != 0 ? 'checked' : ''; ?> <?= $isOvertimeAllowed == 0 ? 'disabled' : ''; ?> /> is Allowed?
                                                                    <div class="control__indicator"></div>
                                                                </label><br>
                                                                <?php if ($isOvertimeAllowed == 0) { ?>
                                                                    <p class="text-danger csF16" style="float: left;">
                                                                        <strong>
                                                                            <em>
                                                                                Note: In Primary Compensation The FLSA status is (Exempt/Owner) You cannot allow Double overtime .</em>
                                                                        </strong>
                                                                    </p>
                                                                <?php } ?>
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
                                                                <label class="csF16">Multiplier</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-lg jsEmployeeFlowAmount rate" placeholder="0.0" value="<?php echo $holiday_rate; ?>" name="holiday_rate">
                                                                </div>
                                                                <?php echo form_error('holiday_rate'); ?>

                                                            </div>

                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                                <!--  --><br>
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="holiday_overtime_is_allowed" value="1" <?= $holiday_overtime_is_allowed != 0 ? 'checked' : ''; ?> /> is Allowed?
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

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
            alertify.alert("Notice", "Multiplier always greater than 0!");
            this.value = 1;
        }
    });


    function deleteCompensation(jobsid, sid) {
        alertify.confirm('Confirmation', 'Are you sure you want to delete this compensation', function() {
            window.location = "<?php echo base_url('job_compensation_delete') ?>/" + jobsid + '/' + sid;

        }, function() {

        });

    }
</script>



<style>
    .table>thead>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>th,
    .table>tbody>tr>td {
        vertical-align: top;
        font-size: 14px;
        padding: 5px;
    }

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