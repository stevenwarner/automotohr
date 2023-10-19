<?php if (checkIfAppIsEnabled('attendance') && $this->session->userdata['logged_in']) { ?>
    <div style="position: absolute; right: 0; top: 0">
        <!--  -->
        <div class="text-center jsAttendanceLoader" style="position: absolute; inset: 0px; width: 100%; z-index: 1; background: rgba(255, 255, 255, 0.9); display: none;">
            <i class="fa fa-circle-o-notch fa-spin text-center csF40 csFC3" style="margin-top: 90px;" aria-hidden="true"></i>
        </div>
        <!--  -->
        <p class="csF26 csW text-center">
            <h4 class="jsAttendanceCurrentClockDateTime csW csFC20 text-center"></h4>
        </p>
        <p class="csF26 csW text-center">
            <span class="jsAttendanceClockHour">00</span>
            <span>:</span>
            <span class="jsAttendanceClockMinute">00</span>
            <span>:</span>
            <span class="jsAttendanceClockSeconds">00</span>
        </p>
        <div class="jsAttendanceBTNs text-center"></div>
    </div>
<?php } ?>