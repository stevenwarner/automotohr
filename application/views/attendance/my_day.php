
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <div class="row">
                                        <div class="col-xs-4"></div>
                                        <div class="col-xs-4">
                                            <?php echo $subtitle; ?>
                                        </div>
                                        <div class="col-xs-4"></div>
                                    </div>
                                    <span class="beta-label">beta</span>
                                </span>
                            </div>
                            <div class="btn-panel text-right no-padding-top">
                                <div class="row">
                                    <a class="btn btn-success" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i> <?php echo $return_title_heading; ?></a>
                                    <a class="btn btn-success" href="<?php echo base_url('attendance'); ?>"><i class="fa fa-chevron-left"></i> Time & Attendance</a>
                                </div>
                            </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <div class="text-success text-center">
                                                <p class="text-success">Time Now</p>
                                                <strong id="timer" style="font-size: 50px;"></strong>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <?php if( $show_clock_out_button == true ) { ?>
                                                    <div class="text-success text-center">
                                                        <p class="text-success">Clocked Time <small>(Including Breaks)</small></p>
                                                        <span class="text-success" id="my_current_clocked_time" style="font-size: 50px;"></span>
                                                    </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <?php if($show_break_end_button == true) { ?>
                                                    <div class="text-success text-center">
                                                        <p class="text-danger">Current Break Total</p>
                                                        <span class="text-danger" id="my_current_break_time" style="font-size: 50px;"></span>
                                                    </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <p>This Week</p>
                                            <p style="font-size: 30px;">
                                                <?php echo str_pad($weeks_total_clocked_hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($weeks_total_clocked_minutes, 2, 0, STR_PAD_LEFT); ?> / <?php echo str_pad($weekly_hours_quota, 2, 0, STR_PAD_LEFT) . ':00'; ?>
                                            </p>
                                            <div class="progress">
                                                <div id="weekly_progress" class="progress-bar <?php echo $weeks_percentage > 100 ? 'progress-bar-danger' : 'progress-bar-success' ;?>" role="progressbar">
                                                    <?php echo str_pad($weeks_total_clocked_hours, 2, 0, STR_PAD_LEFT)  . ':' . str_pad($weeks_total_clocked_minutes, 2, 0, STR_PAD_LEFT); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <p>Today</p>
                                            <p style="font-size: 30px;">
                                                <?php echo str_pad($total_clocked_hours_today, 2, 0, STR_PAD_LEFT) . ':' . str_pad($total_clocked_minutes_today, 2, 0, STR_PAD_LEFT); ?> / <?php echo str_pad($daily_hours_quota, 2, 0, STR_PAD_LEFT) . ':00'; ?>
                                            </p>
                                            <div class="progress">
                                                <div id="todays_time" class="progress-bar <?php echo $todays_clocked_percentage > 100 ? 'progress-bar-danger' : 'progress-bar-success' ;?>" role="progressbar" >
                                                    <?php echo str_pad($total_clocked_hours_today, 2, 0, STR_PAD_LEFT) . ':' . str_pad($total_clocked_minutes_today, 2, 0, STR_PAD_LEFT); ?> / <?php echo str_pad($daily_hours_quota, 2, 0, STR_PAD_LEFT) . ':00'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <p>Break </p>
                                            <p style="font-size: 30px;">
                                                <?php echo str_pad($total_break_hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($total_break_minutes, 2, 0, STR_PAD_LEFT); ?>/ <?php echo str_pad(0, 2, 0, STR_PAD_LEFT) . ':00'; ?>
                                            </p>
                                            <div class="progress">
                                                <div id="break_progress" class="progress-bar <?php echo $break_percentage    > 100 ? 'progress-bar-danger' : 'progress-bar-success' ;?>" role="progressbar" >
                                                    <?php echo str_pad($total_break_hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($total_break_minutes, 2, 0, STR_PAD_LEFT); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if(!empty($last_attendance_record)) { ?>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                                                <p class="text-center">
                                                    <span>You are Currently </span>
                                                    <?php if($last_attendance_record['attendance_type'] == 'clock_in') { ?>
                                                                <span class="text-success"><strong>Clocked In</strong></span>
                                                    <?php } else if($last_attendance_record['attendance_type'] == 'clock_out') { ?>
                                                                <span class="text-success"><strong>Clocked Out</strong></span>
                                                    <?php } else if($last_attendance_record['attendance_type'] == 'break_start') { ?>
                                                                <span class="text-danger"><strong>On Break</strong></span>
                                                    <?php } else if($last_attendance_record['attendance_type'] == 'break_end') { ?>
                                                                <span class="text-success"><strong>Clocked In</strong></span>
                                                    <?php } ?>
                                                    <span> Since </span>
                                                    <span><strong><?php echo date('m/d/Y H:i:s', strtotime($last_attendance_record['attendance_date'])); ?></strong></span>
                                                </p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <hr />

                                    <div class="row" id="attendance_container">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 pull-right">
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <div style="width: 100%; height: 200px;" id="map"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 pull-left">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center col-xs-3" >Date</th>
                                                                    <th class="text-center col-xs-3" >Time</th>
                                                                    <th class="text-center col-xs-3" >Status</th>
                                                                    <!--<th class="text-center col-xs-3" >Clocked Time <br /> <small>(HH:MM)</small> </th>-->
                                                                    <!-- <th class="text-center col-xs-1" >Actions</th> -->
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(!empty($todays_logs)) { ?>
                                                                <?php foreach($todays_logs as $my_log) { ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo convert_date_to_frontend_format($my_log['attendance_date']); ?></td>
                                                                        <td class="text-center"><?php echo date('H:i:s', strtotime($my_log['attendance_date'])); ?></td>
                                                                        <td class="text-center">
                                                                            <?php if($my_log['attendance_type'] == 'clock_in') { ?>
                                                                                        <span class="text-success">Clocked In</span>
                                                                            <?php } else if ( $my_log['attendance_type'] == 'clock_out') { ?>
                                                                                        <span class="text-success">Clocked Out</span>
                                                                            <?php } else if ( $my_log['attendance_type'] == 'break_start') { ?>
                                                                                        <span class="text-danger">Break Started</span>
                                                                            <?php } else if ( $my_log['attendance_type'] == 'break_end') { ?>
                                                                                        <span class="text-danger">Break Ended</span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <!-- <td class="text-center">
                                                                            <?php //echo ( $my_log['attendance_type'] == 'clock_out' || $my_log['attendance_type'] == 'break_end' ?  str_pad(number_format($my_log['total_hours'], 0),2,0,STR_PAD_LEFT) . ':' . str_pad(number_format($my_log['total_minutes'], 0),2,0,STR_PAD_LEFT): 'N/A' );?>
                                                                        </td> -->
                                                                        <!-- <td class="text-center">
                                                                            <?php  //$base_64 = base64_encode(file_get_contents('https://maps.googleapis.com/maps/api/staticmap?center=' . $my_log['latitude'] . ',' . $my_log['longitude']  . '&zoom=14&size=600x600&key=AIzaSyCmtUfghRpk42-zrSArYT7lGvILUWqmy3c&markers=' . $my_log['latitude'] . ',' . $my_log['longitude']));?>
                                                                            <button type="button" data-map_base_64="<?php  //echo $base_64; ?>" onclick="func_map_coordinates(this);" class="btn btn-success btn-sm">Map</button>
                                                                        </td>  -->
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td colspan="5" class="text-center">
                                                                        <span class="no-data">No Time Logged</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if($year == date('Y') && $month == date('m') && $day == date('d')) { ?>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <form id="form_mark_attendance" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_attendance" />
                                                            <input type="hidden" id="latitude" name="latitude" value="" />
                                                            <input type="hidden" id="longitude" name="longitude" value="" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid?>" />
                                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid?>" />
                                                            <input type="hidden" id="current_attendance_type" name="current_attendance_type" value="<?php echo $current_attendance_type?>" />
                                                            <input type="hidden" id="last_break_type" name="last_break_type" value="<?php echo (isset($last_break_record['attendance_type']) ? $last_break_record['attendance_type'] : 'break_end'); ?>" />
                                                            <input type="hidden" id="last_break_date" name="last_break_date" value="<?php echo (isset($last_break_record['attendance_date']) ? $last_break_record['attendance_date'] : '0000-00-00 00:00:00'); ?>" />
                                                            <input type="hidden" id="last_working_type" name="last_working_type" value="<?php echo (isset($last_working_record['attendance_type']) ? $last_working_record['attendance_type'] : 'clock_out'); ?>" />
                                                            <input type="hidden" id="last_working_date" name="last_working_date" value="<?php echo (isset($last_working_record['attendance_date']) ? $last_working_record['attendance_date'] : 'clock_out'); ?>" />
                                                            <input type="hidden" id="clock_in_sid" name="clock_in_sid" value="<?php echo $clock_in_sid; ?>" />
                                                            <?php if($show_clock_in_button == true) { ?>
                                                                      
                                                                        <button onclick="func_submit_form('form_mark_attendance', '<?php echo $current_attendance_type; ?>');" type="button" class="btn btn-success btn-lg btn-block"><strong>Clock In</strong></button>
                                                            <?php } ?>

                                                            <?php if($show_clock_out_button == true) { ?>
                                                                        <button onclick="func_submit_form('form_mark_attendance', '<?php echo $current_attendance_type; ?>');" type="button" class="btn btn-danger btn-lg btn-block"><strong>Clock Out</strong></button>
                                                            <?php } ?>
                                                        </form>
                                                    </div>

                                                    <?php if($current_attendance_type == 'clock_out') { ?>
                                                    <div class="col-xs-6">
                                                        <form id="form_mark_break" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_break" />
                                                            <input type="hidden" id="break_latitude" name="latitude" value="" />
                                                            <input type="hidden" id="break_longitude" name="longitude" value="" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid?>" />
                                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid?>" />
                                                            <input type="hidden" id="current_break_type" name="current_break_type" value="<?php echo $current_break_type?>" />
                                                            <input type="hidden" id="clock_in_sid" name="clock_in_sid" value="<?php echo $clock_in_sid; ?>" />

                                                            <?php if($show_break_start_button == true) { ?>
                                                                        <button onclick="func_submit_form('form_mark_break', '<?php echo $current_break_type; ?>');" type="button" class="btn btn-success btn-lg btn-block">
                                                                            <strong>Break Start</strong>
                                                                        </button>
                                                            <?php } ?>

                                                            <?php if($show_break_end_button == true) { ?>
                                                                        <button onclick="func_submit_form('form_mark_break', '<?php echo $current_break_type; ?>');" type="button" class="btn btn-danger btn-lg btn-block">
                                                                            <strong>Break End</strong>
                                                                        </button>
                                                            <?php } ?>
                                                        </form>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row" id="message_container">
                                        <div class="col-xs-12">
                                            <div class="text-center">
                                                <span class="no-data">Please Allow Location from Browser</span>
                                            </div>
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
<script>
    $(document).ready(function () {
        String.prototype.paddingLeft = function (paddingValue) {
            return String(paddingValue + this).slice(-paddingValue.length);
        };

        getLocation();
        get_current_time();
        update_progress_bar('break_progress', '<?php echo $break_percentage; ?>');
        update_progress_bar('weekly_progress', '<?php echo $weeks_percentage; ?>');

        <?php if(isset($todays_clocked_percentage) && $todays_clocked_percentage > 0) { ?>
                    update_progress_bar('todays_time', '<?php echo $todays_clocked_percentage; ?>');
        <?php } ?>

        setInterval(function () {
            func_calculate_clocked_time("<?php echo isset($last_clock_in_for_clock) ? $last_clock_in_for_clock : ''; ?>", "<?php echo isset($current_date) ? $current_date : ''; ?>", "<?php echo isset($company_timezone) ? $company_timezone : ''; ?>", 'my_current_clocked_time');
            func_calculate_clocked_time("<?php echo isset($last_break_start_for_clock) ? $last_break_start_for_clock : ''; ?>", "<?php echo isset($current_date) ? $current_date : ''; ?>", "<?php echo isset($company_timezone) ? $company_timezone : ''; ?>", 'my_current_break_time  ');
        }, 1000);
    });

    function update_progress_bar_clocked_time(){
        var daily_quota = <?php echo $daily_minutes_quota; ?>;
        var clock_in_moment = new moment();
        clock_in_moment.tz('<?php echo isset($company_timezone) ? $company_timezone : ''; ?>');
        // Split timestamp into [ Y, M, D, h, m, s ]
        var datetime_array = "<?php echo isset($last_clock_in_for_clock) ? $last_clock_in_for_clock : ''; ?>".split(/[- :]/);
        clock_in_moment.set('year', datetime_array[0]);
        clock_in_moment.set('month', datetime_array[1]);  // April
        clock_in_moment.set('date', datetime_array[2]);
        clock_in_moment.set('hour', datetime_array[3]);
        clock_in_moment.set('minute', datetime_array[4]);
        clock_in_moment.set('second', datetime_array[5]);
        var current_datetime = new moment();
        current_datetime.tz('<?php echo isset($company_timezone) ? $company_timezone : ''; ?>');
        current_datetime.set('year', datetime_array[0]);
        current_datetime.set('month', datetime_array[1]);
        var clock_in_unix = clock_in_moment.unix();
        var current_unix = current_datetime.unix();
        var difference = current_unix - clock_in_unix;
        var difference_hours = Math.floor(difference / 3600);
        var remainder = (difference % 3600);
        var difference_minutes = Math.floor(remainder / 60);
        var remainder_sec = remainder % 60;
        var total_minutes = difference / 60;

        if(parseFloat(daily_quota) >= parseFloat(total_minutes)){
            $('#todays_time').addClass('progress-bar-success');
            $('#todays_time').removeClass('progress-bar-danger');
        } else {
            $('#todays_time').addClass('progress-bar-danger');
            $('#todays_time').removeClass('progress-bar-success');
        }

        var time_percentage = ( total_minutes / daily_quota ) * 100;
        console.log(daily_quota + '-' + total_minutes);
        $('#todays_time').text(difference_hours.toString().paddingLeft("00") + ':' + difference_minutes.toString().paddingLeft("00") + ':' + remainder_sec.toString().paddingLeft("00"));
        update_progress_bar('todays_time', time_percentage);
    }


    function update_progress_bar(id, percentage){
        $('#' + id).css('width', percentage + '%');
    }


    function func_map_coordinates(source){

        var img = $('<img />');
        img.attr('src', 'data:image/png;base64,'  + $(source).attr('data-map_base_64'));
        img.addClass('img-responsive');

        var my_container = $('<div></div>');
        my_container.attr('id', 'map');
        my_container.addClass('img-thumbnail');

        my_container.append(img);



        //var my_map = $('<div class="img-thumbnail" id="map"><img class="img-responsive" id="dynamic_map" src="' + image_source + '" /></div>');

        $('#popupmodallabel').text('Map');
        $('#popupmodalbody').html(my_container.html());


        $('#popupmodal').modal('toggle');
    }

    function func_submit_form(form_id, attendance_type) {

        var my_url = "<?php echo base_url('attendance/ajax_responder/'); ?>";

        if(form_id == 'form_mark_attendance'){

            if(attendance_type == 'clock_in' || attendance_type == 'clock_out'){
                var myRequest;
                myRequest = $.ajax({
                                url : my_url,
                                data : { 'perform_action' : 'get_confirmation_dialog', 'form_id' : form_id, 'attendance_type' : attendance_type },
                                type : 'POST'
                            });

                myRequest.done(function (response) {
                    var modal_title = 'Are you sure?';
                    $('#popupmodallabel').text(modal_title);
                    $('#popupmodalbody').html(response);
                    $('#popupmodal').modal('toggle');
                });
            }

        } else if( form_id == 'form_mark_break') {
            $('#' + form_id).submit();
        }

    }

    function getLocation() {
        var options = {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    };

        if (navigator.geolocation) {
            $('#attendance_container').show();
            $('#message_container').hide();
            navigator.geolocation.getCurrentPosition(map_coordinates, on_error, options);
        } else {
            $('#attendance_container').hide();
            $('#message_container').show();
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function map_coordinates(position) {
        var x = position.coords.latitude;
        var y = position.coords.longitude;

        $('#latitude').val(x);
        $('#break_latitude').val(x);
        $('#longitude').val(y);
        $('#break_longitude').val(y);

        var uluru = {lat: x, lng: y};
        var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 14,
                    center: uluru
                });
        var marker = new google.maps.Marker({
                    position: uluru,
                    map: map
                });
    }

    function on_error(error) {
        console.log(error);
        // var location = "<?php //echo $location_info; ?>";
        var location={latitude:"31.443369699999995",longitude:"74.26782639999999"}
        var mylatitude = location.latitude;
        var mylongitude = location.longitude;

        if (mylatitude == 0) {
            mylatitude = 31.5546;
        }

        if (mylongitude == 0) {
            mylongitude = 74.3572;
        }

        $('#latitude').val(mylatitude);
        $('#break_latitude').val(mylatitude);
        $('#longitude').val(mylongitude);
        $('#break_longitude').val(mylongitude);


        var uluru = {lat: mylatitude, lng: mylongitude};
        var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 14,
                    center: uluru
                });
                
        var marker = new google.maps.Marker({
                    position: uluru,
                    map: map
                });
        console.log(location);
    }

    function get_current_time() {
        var cur_year = <?php echo $year; ?>;
        var cur_month = <?php echo $month; ?>;
        var cur_day = <?php echo $day; ?>;
        var cur_hour = <?php echo $hours; ?>;
        var cur_minute = <?php echo $minutes; ?>;
        var cur_seconds = <?php echo $seconds; ?>;
        var today = new Date(cur_year, cur_month, cur_day, cur_hour, cur_minute, cur_seconds, 0); // YYYY (M-1) D H m s ms (start time and date from DB)
        //var today = new Date();
        var myday = today.getDate();
        var mymonth = today.getMonth() + 1; //January is 0!
        var myyear = today.getFullYear();
        var myhours = today.getHours();
        var myminutes = today.getMinutes();
        var myseconds = today.getSeconds();

        setInterval(function () {
            today.setSeconds(today.getSeconds() + 1);
            $('#timer').text((today.getHours().toString().paddingLeft("00") + ':' + today.getMinutes().toString().paddingLeft("00") + ':' + today.getSeconds().toString().paddingLeft("00") ));
        }, 1000);
    }

    function initMap() {

    }

    function func_form_confirm(form_id){
        if($('#confirm_checkbox').prop('checked') == true) {
            $('#popupmodallabel').modal('toggle');
            $('#' + form_id).submit();
        } else {
            alertify.error('Please confirm by marking checkbox!');
        }
    }


    function func_calculate_clocked_time(current_clock_in, current_date,  timezone, output_element_id) {
        var clock_in_moment = new moment();
        clock_in_moment.tz(timezone);
        // Split timestamp into [ Y, M, D, h, m, s ]
        var datetime_array = current_clock_in.split(/[- :]/);
        clock_in_moment.set('year', datetime_array[0]);
        clock_in_moment.set('month', datetime_array[1]);  // April
        clock_in_moment.set('date', datetime_array[2]);
        clock_in_moment.set('hour', datetime_array[3]);
        clock_in_moment.set('minute', datetime_array[4]);
        clock_in_moment.set('second', datetime_array[5]);
        var current_datetime = new moment();
        current_datetime.tz(timezone);
        var current_date_array = current_date.split(/[- :]/);
        current_datetime.set('year', current_date_array[0]);
        current_datetime.set('month', current_date_array[1]);  // April
        current_datetime.set('date', current_date_array[2]);
        var clock_in_unix = clock_in_moment.unix();
        var current_unix = current_datetime.unix();
        var difference = current_unix - clock_in_unix;
        var difference_hours = Math.floor(difference / 3600);
        var remainder = (difference % 3600);
        var difference_minutes = Math.floor(remainder / 60);
        var remainder_sec = remainder % 60;
        //console.log(output_element_id + '   ' + difference_hours + ':' + difference_minutes + ':' + remainder_sec);
        $('#' + output_element_id).text(difference_hours.toString().paddingLeft("00") + ':' + difference_minutes.toString().paddingLeft("00") + ':' + remainder_sec.toString().paddingLeft("00"));
    }
</script>
<!-- <script src="<?php //echo base_url('assets/js') . '/moment.min.js'?>"></script> -->
<!-- <script src="<?php //echo base_url('assets/js') . '/moment-timezone-all-years.min.js'?>"></script> -->
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANZkNB9OCGKQjpkNtuZGeUAMDaVXx-Rc4"></script> -->