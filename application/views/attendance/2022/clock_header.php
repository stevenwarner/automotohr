<?php if (checkIfAppIsEnabled('attendance')) { ?>
    <li class="jsAttendanceClockHeaderBTNs">
        <button class="btn btn-success jsAttendanceClockBTN" data-type="clock_in"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;Clock In</button>
        <button class="btn btn-danger jsAttendanceClockBTN" data-type="clock_out"><i class="fa fa-stop" aria-hidden="true"></i>&nbsp;Clock Out</button>
    </li>
<?php } ?>