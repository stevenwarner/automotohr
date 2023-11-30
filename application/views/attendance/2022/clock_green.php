<?php if (checkIfAppIsEnabled('attendance') && isPayrollOrPlus()) { ?>
    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
        <div class="dash-box">
            <div class="dashboard-widget-box">
                <figure><i class="fa fa-pie-chart" aria-hidden="true"></i></figure>
                <h2 class="post-title">
                    <a href="<?= base_url('attendance/today_overview'); ?>">Attendance Management</a>
                </h2>
                <div class="count-box" style="font-size: 12px">
                    <small style="font-size: 12px"></small>
                </div>
                <div class="button-panel">
                    <a href="<?= base_url('attendance/today_overview'); ?>" class="site-btn">Manage</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (checkIfAppIsEnabled('attendance')) { ?>
    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
        <div class="dash-box">
            <a href="<?= base_url('attendance/my'); ?>">
                <div class="dashboard-widget-box">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; z-index: 1; background: rgba(255,255,255,.9);" class="jsAttendanceLoader">
                        <i class="fa fa-circle-o-notch fa-spin text-center csF40 csFC3" style="margin-top: 90px;" aria-hidden="true"></i>
                    </div>
                    <figure><i class="fa fa-calendar" aria-hidden="true"></i></figure>
                    <h4 class="jsAttendanceCurrentClockDateTime csFC1 csFC20"></h4>
                    <h2 class="jsAttendanceClock p0 m0 mb10">
                        <span class="jsAttendanceClockHour csFC1 csF26">00</span>
                        <span class="csFC1 csF26">:</span>
                        <span class="jsAttendanceClockMinute csFC1 csF26">00</span>
                        <span class="csFC1 csF26">:</span>
                        <span class="jsAttendanceClockSeconds csFC1 csF26">00</span>
                    </h2>
                    <div class="jsAttendanceBTNs">
                        <button class="btn btn-success jsAttendanceBTN dn" data-type="clock_in">
                            <i class="fa fa-play" aria-hidden="true"></i>&nbsp;Clock In
                        </button>
                        <button class="btn btn-warning jsAttendanceBTN dn" data-type="break_in">
                            <i class="fa fa-pause" aria-hidden="true"></i>&nbsp;Break Start
                        </button>
                        <button class="btn btn-black jsAttendanceBTN dn" data-type="break_out">
                            <i class="fa fa-play" aria-hidden="true"></i>&nbsp;Break End
                        </button>
                        <button class="btn btn-danger jsAttendanceBTN dn" data-type="clock_out">
                            <i class="fa fa-stop" aria-hidden="true"></i>&nbsp;Clock Out
                        </button>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <?php if (isPayrollOrPlus()) { ?>
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
            <div class="dash-box">
                <div class="dashboard-widget-box">
                    <figure><i class="fa fa-calendar" aria-hidden="true"></i></figure>
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
<?php } ?>