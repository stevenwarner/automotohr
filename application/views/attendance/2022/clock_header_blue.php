<?php if (checkIfAppIsEnabled('attendance')) { ?>
    <div style="position: absolute; right: 0; top: 0">
        <!--  -->
        <p class="csF26 csW text-center">
        <span class="jsAttendanceClockHour">00</span><span>:</span><span class="jsAttendanceClockMinute">00</span>
        </p>
        <ul style="list-style: none;">
            <li class="jsAttendanceClockHeaderBTNs" style="display: inline-block;">
                <button class="btn btn-success jsAttendanceClockBTN" data-type="clock_in"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;Clock In</button>
            </li>
            <li class="jsAttendanceClockHeaderBTNs" style="display: inline-block;">
                <button class="btn btn-warning jsAttendanceClockBTN" data-type="break_in"><i class="fa fa-pause" aria-hidden="true"></i>&nbsp;Break Start</button>
            </li>
            <li class="jsAttendanceClockHeaderBTNs" style="display: inline-block;">
                <button class="btn btn-black jsAttendanceClockBTN" data-type="break_out"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;Break End</button>
            </li>
            <li class="jsAttendanceClockHeaderBTNs" style="display: inline-block;">
                <button class="btn btn-danger jsAttendanceClockBTN" data-type="clock_out"><i class="fa fa-stop" aria-hidden="true"></i>&nbsp;Clock Out</button>
            </li>
            
        </ul>
    </div>
<?php } ?>