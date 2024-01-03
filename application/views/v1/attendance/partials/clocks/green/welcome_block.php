<!-- Clock -->
<?php if (checkIfAppIsEnabled(MODULE_ATTENDANCE)) { ?>
    <div class="dash-box">
        <a href="<?= base_url('attendance/my/overview'); ?>">
            <div class="dashboard-widget-box">
                <div class="csPageLoader jsAttendanceLoader">
                    <div>
                        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
                    </div>
                </div>
                <figure><i class="fa fa-calendar" aria-hidden="true"></i></figure>
                <p class="jsAttendanceCurrentClockDateTime text-medium text-black" style="margin-bottom: 0px;"></p>
                <p class="jsAttendanceClock text-xxxl text-black" style="margin-bottom: 0px;">
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
<?php } ?>