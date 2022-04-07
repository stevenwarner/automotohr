<?php if (checkIfAppIsEnabled('attendance')) { ?>
        <div class="dash-box">
            <a href="<?=base_url('attendance/my');?>">
                <div class="dashboard-widget-box">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; z-index: 1; background: rgba(255,255,255,.9);" class="jsAttendanceLoader">
                        <i class="fa fa-circle-o-notch fa-spin text-center csF40 csFC3" style="margin-top: 90px;" aria-hidden="true"></i>
                    </div>
                    <p style="margin: 0;" class="text-left csF12">
                        <strong>Current Date</strong>
                    </p>
                    <p style="margin: 0; margin-bottom: 5px;" class="text-left csF16 csFC3 jsAttendanceCurrentClockDate"></p>
                    <p style="margin: 0;" class="text-left csF12">
                        <strong>Current Time</strong>
                    </p>
                    <h2 class="jsAttendanceCurrentClock p0 m0 text-left csF16" style=" margin-bottom: 5px;">
                        <span class="jsAttendanceCurrentClockHour csFC3 csF16">00</span>
                        <span class="csFC3 csF16">:</span>
                        <span class="jsAttendanceCurrentClockMinute csFC3 csF16">00</span>
                        <span class="csFC3 csF16">:</span>
                        <span class="jsAttendanceCurrentClockSeconds csFC3 csF16">00</span>
                    </h2>
                    <p style="margin: 0;" class="text-left csF12">
                        <strong>Clocked Time</strong>
                    </p>
                    <h2 class="jsAttendanceClock p0 m0 text-left csF16" style=" margin-bottom: 5px;">
                        <span class="jsAttendanceClockHour csFC3 csF16">00</span>
                        <span class="csFC3 csF16">:</span>
                        <span class="jsAttendanceClockMinute csFC3 csF16">00</span>
                        <span class="csFC3 csF16">:</span>
                        <span class="jsAttendanceClockSeconds csFC3 csF16">00</span>
                    </h2>
                    <div class="jsAttendanceBTNs p0 m0 text-center">
                        <button class="btn btn-xs btn-success jsAttendanceBTN dn" data-type="clock_in">
                            <i class="fa fa-play" aria-hidden="true"></i>&nbsp;Clock In
                        </button>
                        <button class="btn btn-xs btn-warning jsAttendanceBTN dn" data-type="break_in">
                            <i class="fa fa-pause" aria-hidden="true"></i>&nbsp;Break Start
                        </button>
                        <button class="btn btn-xs btn-black jsAttendanceBTN dn" data-type="break_out">
                            <i class="fa fa-play" aria-hidden="true"></i>&nbsp;Break End
                        </button>
                        <button class="btn btn-xs btn-danger jsAttendanceBTN dn" data-type="clock_out">
                            <i class="fa fa-stop" aria-hidden="true"></i>&nbsp;Clock Out
                        </button>
                    </div>
                </div>
            </a>
        </div>
<?php } ?>