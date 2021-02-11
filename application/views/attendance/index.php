<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
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
                            <div class="custom-btns">
                                <?php if(check_access_permissions_for_view($security_details, 'my_day')) { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('attendance/my_day'); ?>">Clock My Day</a>
                                <?php } ?>
                                <?php if(check_access_permissions_for_view($security_details, 'my_time_sheet')) { ?>
                                    <a class="btn btn-success" href="<?php echo base_url('attendance/my_time_sheet'); ?>">My Time Sheet</a>
                                <?php } ?>
                            <?php if($session['employer_detail']['access_level_plus']==1 || $session['employer_detail']['pay_plan_flag']==1 ) {;?>
                                    <?php if(check_access_permissions_for_view($security_details, 'time_sheet')) { ?>
                                        <a class="btn btn-success" href="<?php echo base_url('attendance/time_sheet'); ?>">Time Sheet</a>
                                    <?php } ?>
                                    <?php if(check_access_permissions_for_view($security_details, 'weekly_attendance')) { ?>
                                        <a class="btn btn-success" href="<?php echo base_url('attendance/weekly_attendance'); ?>">Manage Attendance</a>
                                    <?php }?>
                            <?php } ?>
                            
                            </div>
                            <hr />
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <h1 class="hr-registered pull-left">
                                        <span class=""><?php echo $subtitle; ?></span>
                                    </h1>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center" colspan="2">Employee</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Regular Time<br/>(HH:MM)</th>
                                                <th class="text-center">Break Time<br/>(HH:MM)</th>
                                                <th class="text-center">Overtime<br/>(HH:MM)</th>
                                                <th class="text-center">Total Time<br/>(HH:MM)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($attendance_records)) { ?>
                                                <?php foreach($attendance_records as $attendance_record) { ?>
                                                <?php   $total_after_break_hours = $attendance_record['total_after_break_hours']; // Logged hours after taking out break
                                                        $total_after_break_minutes = $attendance_record['total_after_break_minutes']; // Logged minutes after taking out break
                                                        
                                                        $daily_time_quota_hours = $attendance_record['daily_time_quota_hours']; // total overtime hours after break
                                                        $daily_break_quota_hours = $attendance_record['daily_break_quota_hours']; // total minutes hours after break
                                                        
                                                        $total_over_quota_hours = $attendance_record['total_over_quota_hours']; // total hours allowed quota
                                                        $total_over_quota_minutes = $attendance_record['total_over_quota_minutes']; // total minutes allowed quota
                                                        
                                                        $total_regular_hour_time = $total_after_break_hours - $total_over_quota_hours;
                                                        $total_regular_minutes_time = $total_after_break_minutes - $total_over_quota_minutes;
                                                ?>
                                                    <tr>
                                                        <td class="text-center" style="width: 70px; vertical-align: middle;">
                                                            <div class="img-thumbnail">
                                                                <?php if(!empty($attendance_record['profile_picture'])) { ?>
                                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $attendance_record['profile_picture']; ?>" />
                                                                <?php } else { ?>
                                                                    <img class="img-responsive" src="<?php echo base_url('assets/images')?>/default_pic.jpg" />
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo ucwords($attendance_record['first_name'] . ' ' . $attendance_record['last_name']); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo date('m/d/Y', strtotime($attendance_record['attendance_date'])); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo str_pad($total_regular_hour_time, 2, 0, STR_PAD_LEFT) . ':' . str_pad($total_regular_minutes_time, 2, 0, STR_PAD_LEFT); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo str_pad($attendance_record['total_break_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['total_break_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo str_pad($attendance_record['total_over_quota_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['total_over_quota_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo str_pad(($total_regular_hour_time + $total_over_quota_hours), 2, 0, STR_PAD_LEFT) . ':' . str_pad(($total_regular_minutes_time + $total_over_quota_minutes), 2, 0, STR_PAD_LEFT); ?>
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
                                            <?php if(!empty($grand_totals)) { ?>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">Total</th>
                                                    <th class="text-center"><?php echo $grand_totals['gt_regular_time_hours'] . ':' . $grand_totals['gt_regular_time_minutes']; ?></th>
                                                    <th class="text-center"><?php echo $grand_totals['gt_break_time_hours'] . ':' . $grand_totals['gt_break_time_minutes']; ?></th>
                                                    <th class="text-center"><?php echo $grand_totals['gt_over_quota_time_hours'] . ':' . $grand_totals['gt_over_quota_time_minutes']; ?></th>
                                                    <th class="text-center"><?php echo $grand_totals['gt_after_break_time_hours'] . ':' . $grand_totals['gt_after_break_time_minutes']; ?></th>
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
                <!-- main table view end -->
            </div>
        </div>
    </div>
</div>
