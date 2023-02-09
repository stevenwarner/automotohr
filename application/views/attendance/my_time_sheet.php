<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('attendance'); ?>" class="dashboard-link-btn">
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
                            <div class="panel-group-wrp">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                <h4 class="panel-title">
                                                    Advanced Search Filters <span class="glyphicon glyphicon-plus"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="universal-form-style-v2">
                                                        <ul class="row">
                                                            <li class="col-xs-12 col-sm-4 col-md-5 col-lg-5">
                                                                <label>From</label>
                                                                <input type="text" class="invoice-fields" id="start_date" name="start_date" value="<?php echo $start_date; ?>" />
                                                            </li>
                                                            <li class="col-xs-12 col-sm-4 col-md-5 col-lg-5">
                                                                <label>To</label>
                                                                <input type="text" class="invoice-fields" id="end_date" name="end_date" value="<?php echo $end_date; ?>" />
                                                            </li>
                                                            <li class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
                                                                <label>&nbsp;</label>
                                                                <a id="my_search_btn" href="" class="submit-btn btn-equalizer">Search</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <div class="hr-registered pull-left">
                                        <span class="">Latest Status</span>
                                    </div>
                                    <a class="btn btn-success btn-sm pull-right" href="<?php echo current_url(); ?>">Fetch Latest</a>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Regular Time<br/>(HH:MM)</th>
                                                    <th class="text-center">Break Time<br/>(HH:MM)</th>
                                                    <th class="text-center">Overtime<br/>(HH:MM)</th>
                                                    <th class="text-center">Total Time<br/>(HH:MM)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                               
                                                <?php if($current_clocked_status == 'login') { ?>
                                                        <td class="text-center">
                                                            <?php echo date('m/d/Y', strtotime($current_attendance_date)); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo str_pad($regular_clocked_hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($regular_clocked_minutes, 2, 0, STR_PAD_LEFT); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo str_pad($break_clocked_hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($break_clocked_minutes, 2, 0, STR_PAD_LEFT); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo str_pad($overtime_clocked_hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($overtime_clocked_minutes, 2, 0, STR_PAD_LEFT); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo str_pad($current_after_break_hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($current_after_break_minutes, 2, 0, STR_PAD_LEFT); ?>
                                                        </td>
                                                <?php } else { ?>
                                                        <td colspan="5" class="text-center">
                                                            <div class="clocked-status" style="color: #ff0000">Clocked Out</div>
                                                        </td>
                                                <?php } ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <div class="hr-registered pull-left">
                                        <span class=""><?php echo $subtitle; ?></span>
                                    </div>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" colspan="2">Employee</th>
                                                    <th class="text-center">Date</th>
                                                    <!--<th class="text-center">Due Time<br/>(HH:MM)</th>-->
                                                    <th class="text-center">Regular Time<br/>(HH:MM)</th>
                                                    <th class="text-center">Break Time<br/>(HH:MM)</th>
                                                    <th class="text-center">Overtime<br/>(HH:MM)</th>
                                                    <th class="text-center">Total Time<br/>(HH:MM)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php //   echo "<pre>";
//                                                        print_r($attendance_totals);
//                                                        echo "</pre>";
//                                                        exit;
                                                        ?>
                                                <?php if (!empty($attendance_totals)) { 
                                                            foreach ($attendance_totals as $attendance_record) { ?>
                                                                <tr>
                                                                    <td class="text-center" style="width: 70px; vertical-align: middle;">
                                                                        <div class="img-thumbnail">
                                                                            <?php if (!empty($attendance_record['profile_picture'])) { ?>
                                                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $attendance_record['profile_picture']; ?>" />
                                                                            <?php } else { ?>
                                                                                    <img class="img-responsive" src="<?php echo base_url('assets/images') ?>/default_pic.jpg" />
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo ucwords($attendance_record['first_name'] . ' ' . $attendance_record['last_name']); ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo date('m/d/Y', strtotime($attendance_record['attendance_date'])); ?>
                                                                    </td>
                                                                    <!-- <td class="text-center">
                                                                        <?php //echo str_pad($attendance_record['daily_time_quota_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad(0, 2, 0, STR_PAD_LEFT); ?>
                                                                    </td>-->
                                                                    <td class="text-center">
                                                                        <?php echo str_pad($attendance_record['regular_clocked_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['regular_clocked_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo str_pad($attendance_record['total_break_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['total_break_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo str_pad($attendance_record['total_over_quota_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['total_over_quota_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo str_pad($attendance_record['total_after_break_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['total_after_break_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                <?php } else { ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center">
                                                                <span class="no-data">No Logs Found</span>
                                                            </td>
                                                        </tr>
                                                <?php } ?>
                                            </tbody>
                                            <?php if (!empty($grand_totals)) { ?>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">Total</th>
                                                            <th class="text-center"><?php echo $grand_totals['gt_regular_clocked_hours'] . ':' . $grand_totals['gt_regular_clocked_minutes']; ?></th>
                                                            <th class="text-center"><?php echo $grand_totals['gt_total_break_hours'] . ':' . $grand_totals['gt_total_break_minutes']; ?></th>
                                                            <th class="text-center"><?php echo $grand_totals['gt_total_over_quota_hours'] . ':' . $grand_totals['gt_total_over_quota_minutes']; ?></th>
                                                            <th class="text-center"><?php echo $grand_totals['gt_total_after_break_hours'] . ':' . $grand_totals['gt_total_after_break_minutes']; ?></th>
                                                        </tr>
                                                    </tfoot>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function func_map_clock_in_clock_out(in_lat, in_long, out_lat, out_long){
        var my_map = $('<div class="img-thumbnail"><img class="img-responsive" id="dynamic_map" src="https://maps.googleapis.com/maps/api/staticmap?center=' + in_lat + ',' + in_long + '&zoom=14&size=600x600&markers=color:green%7Clabel:I%7C' + in_lat + ',' + in_long + '&markers=color:blue%7Clabel:O%7C' + out_lat + ',' + out_long + '&key=AIzaSyCmtUfghRpk42-zrSArYT7lGvILUWqmy3c" /></div>');
        $('#popupmodallabel').text('Map');
        $('#popupmodalbody').html(my_map);
        $('#popupmodal').modal('toggle');
    }

    $(document).ready(function () {
        String.prototype.paddingLeft = function (paddingValue) {
            return String(paddingValue + this).slice( - paddingValue.length);
        };
        
    $('#start_date').datepicker({
        onSelect: function(dateText) {
                update_url();
            },
            format: 'm/d/Y'
    });
    
    $('#end_date').datepicker({
        onSelect: function(dateText) {
            update_url();
        },
        format: 'm/d/Y'
    });
    
    $('.collapse').on('shown.bs.collapse', function () {
        $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
    }).on('hidden.bs.collapse', function () {
        $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    });
    
    setInterval(function () {
        <?php if (!empty($attendance_records)) { ?>
            <?php foreach ($attendance_records as $attendance_record) { ?>
                <?php if ($attendance_record['out_sid'] == 0) { ?>
                        func_calculate_clocked_time('<?php echo $attendance_record['attendance_date']; ?>', '<?php echo $current_date; ?>', '<?php echo $company_timezone; ?>', 'clocked_time_<?php echo $attendance_record['sid']; ?>', <?php echo $daily_minutes_quota; ?>);
                        func_calculate_total_time_excluding_breaks(<?php echo $attendance_record['sid'] ?>, '<?php echo $company_timezone; ?>', 'total_<?php echo $attendance_record['sid']; ?>', <?php echo $daily_minutes_quota; ?>);
                <?php } ?>
            <?php } ?>
        <?php } ?>
    }, 1000);
    });
    
    $('#employee_sid').on('change', function () {
        update_url();
    });
    
    function update_url(){
        //var employee = $('#employee_sid').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        start_date = start_date.split('/').join('_');
        end_date = end_date.split('/').join('_');
        var start_date_obj = new Date($('#start_date').val());
        var end_date_obj = new Date($('#end_date').val());
        var start_date_unix = start_date_obj.getTime();
        var end_date_unix = end_date_obj.getTime();
        var url;
        if (start_date_unix <= end_date_unix){
            url = '<?php echo base_url("attendance/my_time_sheet"); ?>/' + start_date + '/' + end_date;
        } else {
            url = 'javascript:void(0);';
            alertify.error('Invalid Dates!');
        }
        $('#my_search_btn').attr('href', url);
    }

    function func_calculate_clocked_time(current_clock_in, current_date, timezone, output_element_id, daily_quota = 1) {
        var clock_in_moment = new moment();
        clock_in_moment.tz(timezone);
        // Split timestamp into [ Y, M, D, h, m, s ]
        var datetime_array = current_clock_in.split(/[- :]/);
        clock_in_moment.set('year', datetime_array[0]);
        clock_in_moment.set('month', datetime_array[1]); // April
        clock_in_moment.set('date', datetime_array[2]);
        clock_in_moment.set('hour', datetime_array[3]);
        clock_in_moment.set('minute', datetime_array[4]);
        clock_in_moment.set('second', datetime_array[5]);
        var current_datetime = new moment();
        current_datetime.tz(timezone);
        var current_date_array = current_date.split(/[- :]/);
        current_datetime.set('year', current_date_array[0]);
        current_datetime.set('month', current_date_array[1]); // April
        current_datetime.set('date', current_date_array[2]);
        var clock_in_unix = clock_in_moment.unix();
        var current_unix = current_datetime.unix();
        var difference = current_unix - clock_in_unix;
        var difference_hours = Math.floor(difference / 3600);
        var remainder = (difference % 3600);
        var difference_minutes = Math.floor(remainder / 60);
        var remainder_sec = remainder % 60;

        if ((difference / 60) > daily_quota){
            $('#' + output_element_id).addClass('text-danger');
            $('#' + output_element_id).removeClass('text-success');
        } else {
            $('#' + output_element_id).removeClass('text-danger');
            $('#' + output_element_id).addClass('text-success');
        }

        $('#dynamic_hours_' + output_element_id).val(difference_hours);
        $('#dynamic_minutes_' + output_element_id).val(difference_minutes);
        $('#dynamic_seconds_' + output_element_id).val(remainder_sec);
        //console.log(output_element_id + '   ' + difference_hours + ':' + difference_minutes + ':' + remainder_sec);

        $('#' + output_element_id).text(difference_hours.toString().paddingLeft("00") + ':' + difference_minutes.toString().paddingLeft("00") + ':' + remainder_sec.toString().paddingLeft("00"));
    }

    function func_calculate_total_time_excluding_breaks(attendance_sid, timezone, output_element_id, daily_quota = 1) {
        var clocked_in = $('#clocked_in_' + attendance_sid).val();
        var clocked_out = $('#clocked_out_' + attendance_sid).val();
        var total_break_hours;
        var total_break_minutes;
        var total_clocked_hours;
        var total_clocked_minutes;
        var dynamic_break_hours;
        var dynamic_break_minutes;
        var dynamic_clocked_hours;
        var dynamic_clocked_minutes;
        var total_after_break_hours;
        var total_after_break_minutes;
        
        if (attendance_sid > 0 && attendance_sid != '' && attendance_sid != null && attendance_sid != undefined){
            total_break_hours = parseInt($('#total_break_hours_' + attendance_sid).val());
            total_break_minutes = parseInt($('#total_break_minutes_' + attendance_sid).val());
            total_clocked_hours = parseInt($('#total_clocked_hours_' + attendance_sid).val());
            total_clocked_minutes = parseInt($('#total_clocked_minutes_' + attendance_sid).val());
            dynamic_clocked_hours = parseInt($('#dynamic_hours_clocked_time_' + attendance_sid).val());
            dynamic_clocked_minutes = parseInt($('#dynamic_minutes_clocked_time_' + attendance_sid).val());
            total_after_break_hours = parseInt($('total_after_break_hours_' + attendance_sid).val());
            total_after_break_minutes = parseInt($('total_after_break_minutes_' + attendance_sid).val());
        } else {
            total_break_hours = 0;
            total_break_minutes = 0;
            total_clocked_hours = 0;
            total_clocked_minutes = 0;
            dynamic_clocked_hours = 0;
            dynamic_clocked_minutes = 0;
            total_after_break_hours = 0;
            total_after_break_minutes = 0;
        }

        total_break_minutes = (total_break_hours * 60) + total_break_minutes;
        total_clocked_minutes = (total_clocked_hours * 60) + total_clocked_minutes;
        var last_break_sid = $('#last_break_sid_' + attendance_sid).val();

        if (last_break_sid > 0 && last_break_sid != '' && last_break_sid != null && last_break_sid != undefined){
            dynamic_break_hours = parseInt($('#dynamic_hours_last_break_' + last_break_sid).val());
            dynamic_break_minutes = parseInt($('#dynamic_minutes_last_break_' + last_break_sid).val());
        } else {
            dynamic_break_hours = 0;
            dynamic_break_minutes = 0;
        }

        dynamic_break_minutes = (dynamic_break_hours * 60) + dynamic_break_minutes;
        dynamic_clocked_minutes = (dynamic_clocked_hours * 60) + dynamic_clocked_minutes;
        //console.log(total_break_minutes);
        //console.log(total_clocked_minutes);
        //console.log(dynamic_break_minutes);
        //console.log(dynamic_clocked_minutes);

        var total_minutes = total_clocked_minutes + dynamic_clocked_minutes;
        var total_break = total_break_minutes + dynamic_break_minutes;
        var balance_minutes = total_minutes - total_break;
        var balance_hours = Math.floor(balance_minutes / 60);
        var balance_minutes = balance_minutes % 60;
        //console.log(total_minutes + ' - ' + total_break + ' = ' + balance_minutes);

        if (balance_minutes > daily_quota){
            $('#' + output_element_id).addClass('text-danger');
            $('#' + output_element_id).removeClass('text-success');
        } else {
            $('#' + output_element_id).removeClass('text-danger');
            $('#' + output_element_id).addClass('text-success');
        }

        $('#' + output_element_id).text(balance_hours.toString().paddingLeft("00") + ':' + balance_minutes.toString().paddingLeft("00"));
    }
</script>
<script src="<?php echo base_url('assets/js') . '/moment.min.js' ?>"></script>
<script src="<?php echo base_url('assets/js') . '/moment-timezone-all-years.min.js' ?>"></script>