<?php if (checkIfAppIsEnabled(MODULE_ATTENDANCE)) { ?>
    <?php if (isPayrollOrPlus()) { ?>
        <!-- Attendance management -->
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
            <div class="dash-box">
                <div class="dashboard-widget-box">
                    <figure><i class="fa fa-cogs" aria-hidden="true"></i></figure>
                    <h2 class="post-title">
                        <a href="<?= base_url('attendance/dashboard'); ?>">Attendance<br />Management</a>
                    </h2>
                    <div class="count-box" style="font-size: 12px">
                        <small style="font-size: 12px"></small>
                    </div>
                    <div class="button-panel">
                        <a href="<?= base_url('attendance/dashboard'); ?>" class="site-btn">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- Attendance employee dashboard -->
    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
        <div class="dash-box">
            <a href="<?= base_url('attendance/my/overview'); ?>">
                <div class="dashboard-widget-box">
                    <div class="csPageLoader jsAttendanceLoader">
                        <div>
                            <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
                        </div>
                    </div>
                    <figure><i class="fa fa-calendar" aria-hidden="true"></i></figure>
                    <p class="jsAttendanceCurrentClockDateTime text-medium text-black"></p>
                    <p class="jsAttendanceClock text-xxxl text-black">
                        <span class="jsAttendanceClockHour">00</span>
                        <span class="jsAttendanceSeparator">:</span>
                        <span class="jsAttendanceClockMinute">00</span>
                        <span class="jsAttendanceSeparator">:</span>
                        <span class="jsAttendanceClockSeconds">00</span>
                    </p>
                    <div class="jsAttendanceBTNs"></div>
                </div>
            </a>
        </div>
    </div>
<?php } ?>