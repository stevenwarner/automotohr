<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('attendance/time_sheet'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>

                        <span class="beta-label">beta</span>
                    </span>

                </div>
                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <h1 class="hr-registered pull-left">
                                        <span class=""><?php echo $subtitle; ?></span>
                                    </h1>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Status</th>
                                                    <th colspan="2">Date and Time</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($attendance_records)) { ?>
                                                    <?php foreach($attendance_records as $attendance) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php if($attendance['attendance_type'] == 'clock_in') { ?>
                                                                    <span class="text-success">Clocked In</span>
                                                                <?php } else if ( $attendance['attendance_type'] == 'clock_out') { ?>
                                                                    <span class="text-success">Clocked Out</span>
                                                                <?php } else if ( $attendance['attendance_type'] == 'break_start') { ?>
                                                                    <span class="text-danger">Break Started</span>
                                                                <?php } else if ( $attendance['attendance_type'] == 'break_end') { ?>
                                                                    <span class="text-danger">Break Ended</span>
                                                                <?php } ?>
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

                                                                        <input type="hidden" id="clock_in_sid" name="clock_in_sid" value="<?php echo $clock_in_sid; ?>" />
                                                                        <input type="hidden" id="clock_out_sid" name="clock_out_sid" value="<?php echo $clock_out_sid; ?>" />
                                                                        <input type="hidden" id="employee_sid" name="employee_sid" value="<?php echo $employee_sid; ?>" />

                                                                        <input class="datetimepicker form-control " readonly type="text" id="new_datetime" name="new_datetime" value="<?php echo date('m/d/Y H:i', strtotime($attendance['attendance_date'])); ?>" />
                                                                        
                                                                       </form>
                                                                </span>

                                                            </td>
                                                            <td><button type="button" class="btn btn-success btn-sm btn-block" onclick="func_submit_form(<?php echo $attendance['sid']; ?>);">Update</button></td>
                                                            <td>
                                                                <button onclick="func_map_coordinates(<?php echo round($attendance['latitude'], 6)?>, <?php echo round($attendance['longitude'], 6)?>)" type="button" class="btn btn-success btn-sm btn-block">Map</button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- main table view end -->


            </div>
        </div>
    </div>
</div>

<script>
    function func_submit_form(attendance_sid){
        $('#form_update_attendance_' + attendance_sid).submit();
    }

    $('.datetimepicker').datetimepicker({
        format: 'm/d/Y H:i',
        allowTimes: <?php echo $allowed_time; ?>
    });


    function func_map_coordinates(myLat, myLong){
        var my_map = $('<div class="img-thumbnail"><img class="img-responsive" id="dynamic_map" src="https://maps.googleapis.com/maps/api/staticmap?center=' + myLat + ',' + myLong + '&zoom=14&size=600x600&key=AIzaSyCmtUfghRpk42-zrSArYT7lGvILUWqmy3c&markers=' + myLat + ',' + myLong + '" /></div>');


        $('#popupmodallabel').text('Map');
        $('#popupmodalbody').html(my_map);

        $('#popupmodal').modal('toggle');

    }
</script>