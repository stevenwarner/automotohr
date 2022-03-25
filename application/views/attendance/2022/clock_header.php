<?php if (checkIfAppIsEnabled('attendance') && $this->session->userdata['logged_in']) { ?>
    <li class="jsAttendanceClockHeaderBTNs">
        <button class="btn btn-success jsAttendanceClockBTN" data-type="clock_in"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;Clock In</button>
        <button class="btn btn-warning jsAttendanceClockBTN" data-type="break_in"><i class="fa fa-pause" aria-hidden="true"></i>&nbsp;Break Start</button>
        <button class="btn btn-black jsAttendanceClockBTN" data-type="break_out"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;Break Out</button>
        <button class="btn btn-danger jsAttendanceClockBTN" data-type="clock_out"><i class="fa fa-stop" aria-hidden="true"></i>&nbsp;Clock Out</button>
    </li>
<?php } ?>