<?php if (checkIfAppIsEnabled('attendance')) { ?>
    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
        <div class="widget-box">
            <a href="<?php echo base_url('attendance/my'); ?>">
                <div class="link-box bg-orange full-width">
                    <div class="text-center jsAttendanceLoader" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; z-index: 1; background: rgba(255,255,255,.9);">
                        <i class="fa fa-circle-o-notch fa-spin text-center csF40 csFC3" style="margin-top: 90px;" aria-hidden="true"></i>
                    </div>
                    <!--  -->
                    <p class="text-center csW csF12" style="margin: 0;">
                        <strong>Current Date</strong>
                    </p>
                    <p style="margin: 0; padding: 0; padding-bottom: 5px;" class="text-center csW csF16 csW jsAttendanceCurrentClockDate"></p>
                    <!--  -->
                    <p style="margin: 0; padding: 0; padding-bottom: 5px;" class="text-center csW csF12">
                        <strong>Current Time</strong>
                    </p>
                    <h2 class="jsAttendanceCurrentClock p0 m0 text-center csW csF16" style="margin: 0; padding: 0; padding-bottom: 5px;">
                        <span class="jsAttendanceCurrentClockHour csW csF22">00</span>
                        <span class="csW csF22">:</span>
                        <span class="jsAttendanceCurrentClockMinute csW csF22">00</span>
                        <span class="csW csF22">:</span>
                        <span class="jsAttendanceCurrentClockSeconds csW csF22">00</span>
                    </h2>
                    <p style="margin: 0; padding: 0; padding-bottom: 5px;" class="text-center csW csF12">
                        <strong>Clocked Time</strong>
                    </p>
                    <h2 class="jsAttendanceClock p0 m0 text-center csW csF16" style="margin: 0; padding: 0; padding-bottom: 5px;">
                        <span class="jsAttendanceClockHour csW csF22">00</span>
                        <span class="csW csF22">:</span>
                        <span class="jsAttendanceClockMinute csW csF22">00</span>
                        <span class="csW csF22">:</span>
                        <span class="jsAttendanceClockSeconds csW csF22">00</span>
                    </h2>
                    <!--  -->
                    <div class="status-panel text-center">
                        <button class="btn btn-orange jsAttendanceBTN dn" data-type="clock_in">
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
<?php } ?>