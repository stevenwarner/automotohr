<?php if (checkIfAppIsEnabled(MODULE_ATTENDANCE) && $this->session->userdata['logged_in']) { ?>
    <div class="text-right text-white" style="position: relative">
        <!--  -->
        <h4 class="text-center text-medium jsAttendanceCurrentClockDateTime text-center"></h4>
        <p class="text-center text-xxxl">
            <span class="jsAttendanceClockHour"></span>
            <span class="jsAttendanceClockSeparator"></span>
            <span class="jsAttendanceClockMinute"></span>
            <span class="jsAttendanceClockSeparator"></span>
            <span class="jsAttendanceClockSeconds"></span>
        </p>
        <div class="jsAttendanceBTNs text-center"></div>
    </div>
    <br />
<?php } ?>