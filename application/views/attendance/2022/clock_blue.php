<?php if (checkIfAppIsEnabled('attendance')) { ?>
    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
        <div class="widget-box">
            <a href="<?php echo base_url('attendance/my'); ?>">
                <div class="link-box bg-info full-width">
                    <div class="text-center jsAttendanceLoader" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; z-index: 1; background: rgba(255,255,255,.9);">
                        <i class="fa fa-circle-o-notch fa-spin text-center csF40 csFC3" style="margin-top: 90px;" aria-hidden="true"></i>
                    </div>
                    <h2>Attendance</h2>
                    <h4 class="jsAttendanceCurrentClockDateTime text-center csW csFC20"></h4>
                    <h2 class="jsAttendanceClock text-center p0 m0 mb10">
                        <span class="jsAttendanceClockHour csW csF26">00</span>
                        <span class="csW csF26">:</span>
                        <span class="jsAttendanceClockMinute csW csF26">00</span>
                        <span class="csW csF26">:</span>
                        <span class="jsAttendanceClockSeconds csW csF26">00</span>
                    </h2>
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