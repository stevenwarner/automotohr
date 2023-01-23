<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo $back_btn_url; ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                        <span class="beta-label">beta</span>
                    </span>
                </div>
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <span class="hr-registered pull-left">
                                        <span class=""><?php echo $subtitle; ?></span>
                                    </span>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <?php if (!empty($employee_info)) { ?>
                                        <div class="row">
                                            <div class="col-xs-9">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <th class="col-xs-3">Name</th>
                                                                <td class="col-xs-6"><?php echo $employee_info['first_name'] . ' ' . $employee_info['last_name']; ?></td>
                                                            </tr>
                                                            <?php   if(!empty($employee_info['position_info'])) { ?>
                                                                        <tr>
                                                                            <th class="col-xs-3">Position</th>
                                                                            <td class="col-xs-6"><?php echo $employee_info['position_info']['position_name']; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="col-xs-3">Department</th>
                                                                            <td class="col-xs-6"><?php echo $employee_info['position_info']['department_name']; ?></td>
                                                                        </tr>
                                                            <?php   } else { ?>
                                                                        <tr>
                                                                            <th class="col-xs-3">Position</th>
                                                                            <td class="col-xs-6"><?php echo $employee_info['job_title']; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="col-xs-3">Department</th>
                                                                            <td class="col-xs-6">Not Assigned</td>
                                                                        </tr>
                                                            <?php   } ?>
                                                            <tr>
                                                                <th class="col-xs-3">Employee Since</th>
                                                                <td class="col-xs-6"><?php echo convert_date_to_frontend_format($employee_info['registration_date']); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="img-thumbnail pull-right">
                                                    <?php   if(!empty($employee_info['profile_picture'])) { ?>
                                                                <img src="<?php echo AWS_S3_BUCKET_URL . $employee_info['profile_picture']; ?>" class="img-responsive img-rounded" />
                                                    <?php   } else { ?>
                                                                <img src="<?php echo base_url('assets/images/default_pic.jpg'); ?>" class="img-responsive img-rounded" />
                                                    <?php   } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                    <?php } ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-2">Status</th>
                                                    <th class="col-xs-8" colspan="1">Date and Time</th>
                                                    <th class="col-xs-2" colspan="2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php   if(!empty($attendance_records)) { ?>
                                                <?php   foreach ($attendance_records as $attendance) { ?>
                                                            <tr>
                                                                <td>
                                                        <?php       if ($attendance['attendance_type'] == 'clock_in') { ?>
                                                                        <span class="text-success">Clocked In</span>
                                                        <?php       } else if ($attendance['attendance_type'] == 'clock_out') { ?>
                                                                        <span class="text-success">Clocked Out</span>
                                                        <?php       } else if ($attendance['attendance_type'] == 'break_start') { ?>
                                                                        <span class="text-danger">Break Started</span>
                                                        <?php       } else if ($attendance['attendance_type'] == 'break_end') { ?>
                                                                        <span class="text-danger">Break Ended</span>
                                                        <?php       } ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span style="display: none;" id="view_datetime">
                                                                        <?php echo date('m/d/Y H:i', strtotime($attendance['attendance_date'])); ?>
                                                                    </span>
                                                                    <span id="edit_datetime" class="edit-datetime">
                                                                        <form id="form_update_attendance_<?php echo $attendance['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="update_timelog" />
                                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $attendance['company_sid']; ?>" />
                                                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $attendance['employer_sid']; ?>" />
                                                                            <input type="hidden" id="attendance_sid" name="attendance_sid" value="<?php echo $attendance['sid']; ?>" />
                                                                            <input type="hidden" id="attendance_type" name="attendance_type" value="<?php echo $attendance['attendance_type']; ?>" />
                                                                            <input type="hidden" id="clock_in_sid" name="clock_in_sid" value="<?php echo $attendance['clock_in_sid']; ?>" />
                                                                            <!--<input type="hidden" id="clock_out_sid" name="clock_out_sid" value="<?php /* echo $clock_out_sid; */ ?>" />-->
                                                                            <input type="hidden" id="employee_sid" name="employee_sid" value="<?php echo $employee_sid; ?>" />
                                                                            <input class="datetimepicker form-control " readonly type="text" id="new_datetime" name="new_datetime" value="<?php echo date('m/d/Y H:i', strtotime($attendance['attendance_date'])); ?>" />
                                                                        </form>
                                                                    </span>
                                                                </td>
                                                                <!-- <td><button type="button" class="btn btn-success btn-sm btn-block" onclick="func_submit_form(<?php //echo $attendance['sid']; ?>);">Update</button></td> -->
                                                                <!-- <td>
                                                                    <button onclick="func_map_coordinates(<?php //echo round($attendance['latitude'], 6) ?>, <?php //echo round($attendance['longitude'], 6) ?>)" type="button" class="btn btn-success btn-sm btn-block">Map</button>
                                                                </td> -->
                                                                <td>
                                                                    <form id="form_delete_attendance_record_<?php echo $attendance['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_attendance_record" />
                                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $attendance['company_sid']; ?>" />
                                                                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $attendance['employer_sid']; ?>" />
                                                                        <input type="hidden" id="attendance_sid" name="attendance_sid" value="<?php echo $attendance['sid']; ?>" />
                                                                        <input type="hidden" id="attendance_date" name="attendance_date" value="<?php echo $attendance['attendance_date']; ?>" />
                                                                        <input type="hidden" id="clock_in_sid" name="clock_in_sid" value="<?php echo $attendance['clock_in_sid']; ?>" />
                                                                        <!--<input type="hidden" id="clock_out_sid" name="clock_out_sid" value="<?php /* echo $clock_out_sid; */ ?>" />-->
                                                                        <input type="hidden" id="attendance_type" name="attendance_type" value="<?php echo $attendance['attendance_type']; ?>" />
                                                                    </form>
                                                                    <?php if ($attendance['attendance_type'] == 'clock_in' && $attendance['linked_records_count'] != 0) { ?>
                                                                        <button type="button" class="btn btn-danger btn-sm btn-block disabled" onclick="javascript:void(0);">Delete</button>
                                                                    <?php } else { ?>
                                                                        <button type="button" class="btn btn-danger btn-sm btn-block" onclick="func_delete_attendance_record(<?php echo $attendance['sid']; ?>);">Delete</button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">
                                                            <span class="no-data">No Records</span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php if ($show_clock_in_btn == true || $show_clock_out_btn == true || $show_break_start_btn == true || $show_break_end_btn == true) { ?>
                                <div class="hr-box">
                                    <div class="hr-box-header bg-header-green">
                                        <span class="hr-registered pull-left">
                                            <span class="">Manual Time Management</span>
                                        </span>
                                    </div>
                                    <div class="hr-innerpadding">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-2">Status</th>
                                                        <th class="col-xs-10" colspan="2">Date and Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($show_clock_in_btn == true) { ?>
                                                        <tr>
                                                            <td class="col-xs-2">
                                                                <span class="text-success">Clock In</span>
                                                            </td>
                                                            <td class="col-xs-8">
                                                                <form id="form_manual_clock_in" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_clock_in" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                    <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employee_sid; ?>" />
                                                                    <input type="hidden" id="attendance_sid" name="attendance_sid" value="0" />
                                                                    <input type="hidden" id="attendance_type" name="attendance_type" value="clock_in" />
                                                                    <input type="hidden" id="clock_in_sid" name="clock_in_sid" value="0" />
                                                                    <input class="datetimepicker form-control " readonly type="text" id="new_datetime" name="new_datetime" value="<?php echo date('m/d/Y H:i', strtotime($selected_date)); ?>" />
                                                                </form>
                                                            </td>
                                                            <td class="col-xs-2">
                                                                <button type="button" class="btn btn-success btn-sm btn-block" onclick="func_submit_manual_form('form_manual_clock_in');">Clock In</button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if ($show_break_start_btn == true) { ?>
                                                        <tr>
                                                            <td>
                                                                <span class="text-danger">Break Start</span>
                                                            </td>
                                                            <td>
                                                                <form id="form_manual_break_start" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_break_start" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                    <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employee_sid; ?>" />
                                                                    <input type="hidden" id="attendance_sid" name="attendance_sid" value="0" />
                                                                    <input type="hidden" id="attendance_type" name="attendance_type" value="break_start" />
                                                                    <!--<input type="hidden" id="clock_in_sid" name="clock_in_sid" value="<?php /* echo $clock_in_sid; */ ?>" />-->
                                                                    <!--<input type="hidden" id="clock_out_sid" name="clock_out_sid" value="<?php /* echo $clock_out_sid; */ ?>" />-->
                                                                    <input class="datetimepicker form-control " readonly type="text" id="new_datetime" name="new_datetime" value="<?php echo date('m/d/Y H:i', strtotime($selected_date)); ?>" />
                                                                </form>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm btn-block" onclick="func_submit_manual_form('form_manual_break_start');">Start Break</button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if ($show_break_end_btn == true) { ?>
                                                        <tr>
                                                            <td>
                                                                <span class="text-danger">Break End</span>
                                                            </td>
                                                            <td>
                                                                <form id="form_manual_break_end" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_break_end" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                    <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employee_sid; ?>" />
                                                                    <input type="hidden" id="attendance_sid" name="attendance_sid" value="0" />
                                                                    <input type="hidden" id="attendance_type" name="attendance_type" value="break_end" />
                                                                    <input type="hidden" id="last_attendance_sid" name="last_attendance_sid" value="<?php echo (isset($last_break_start['sid']) ? $last_break_start['sid'] : 0); ?>" />
                                                                    <!--<input type="hidden" id="clock_in_sid" name="clock_in_sid" value="<?php /* echo $clock_in_sid; */ ?>" />-->
                                                                    <!--<input type="hidden" id="clock_out_sid" name="clock_out_sid" value="<?php /* echo $clock_out_sid; */ ?>" />-->
                                                                    <input class="datetimepicker form-control " readonly type="text" id="new_datetime" name="new_datetime" value="<?php echo date('m/d/Y H:i', strtotime($selected_date)); ?>" />
                                                                </form>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm btn-block" onclick="func_submit_manual_form('form_manual_break_end');">End Break</button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php if ($show_clock_out_btn == true) { ?>
                                                        <tr>
                                                            <td>
                                                                <span class="text-success">Clock Out</span>
                                                            </td>
                                                            <td class="col-xs-8">
                                                                <form id="form_manual_clock_out" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_clock_out" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                    <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employee_sid; ?>" />
                                                                    <input type="hidden" id="attendance_sid" name="attendance_sid" value="0" />
                                                                    <input type="hidden" id="attendance_type" name="attendance_type" value="clock_out" />
                                                                    <input type="hidden" id="last_attendance_sid" name="last_attendance_sid" value="<?php echo $last_clock_in['sid']; ?>" />
                                                                    <!--<input type="hidden" id="clock_in_sid" name="clock_in_sid" value="<?php /* echo $clock_in_sid; */ ?>" />-->
                                                                    <!--<input type="hidden" id="clock_out_sid" name="clock_out_sid" value="<?php /* echo $clock_out_sid; */ ?>" />-->
                                                                    <input class="datetimepicker form-control " readonly type="text" id="new_datetime" name="new_datetime" value="<?php echo date('m/d/Y H:i', strtotime($selected_date)); ?>" />
                                                                </form>
                                                            </td>
                                                            <td class="col-xs-2">
                                                                <button type="button" class="btn btn-success btn-sm btn-block" onclick="func_submit_manual_form('form_manual_clock_out');">Clock Out</button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function func_delete_attendance_record(attendance_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this attendance record?',
            function () {
                $('#form_delete_attendance_record_' + attendance_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_submit_form(attendance_sid){
        $('#form_update_attendance_' + attendance_sid).submit();
    }

    $('.datetimepicker').datetimepicker({
        format: 'm/d/Y H:i',
        allowTimes: <?php echo $allowed_time; ?>,
        minDate:'<?php echo date('Y/m/d', strtotime($selected_date)); ?>',
        maxDate:'<?php echo date('Y/m/d', strtotime($selected_date)); ?>'
    });

    function func_map_coordinates(myLat, myLong){
        var my_map = $('<div class="img-thumbnail"><img class="img-responsive" id="dynamic_map" src="https://maps.googleapis.com/maps/api/staticmap?center=' + myLat + ',' + myLong + '&zoom=14&size=600x600&key=AIzaSyCmtUfghRpk42-zrSArYT7lGvILUWqmy3c&markers=' + myLat + ',' + myLong + '" /></div>');
        $('#popupmodallabel').text('Map');
        $('#popupmodalbody').html(my_map);
        $('#popupmodal').modal('toggle');
    }

    function func_submit_manual_form(form_id){
        $('#' + form_id).submit();
    }
</script>