<?php if (checkIfAppIsEnabled(MODULE_ATTENDANCE) && $this->session->userdata('logged_in')) : ?>
    <?php
    //
    $attendanceAdditionalScripts = ["js/app_helper"];
    $attendanceScripts = [];
    $attendanceScripts[] = "v1/plugins/ms_modal/main";
    $attendanceScripts[] = "v1/attendance/js/timer";
    ?>
    <?= $this->uri->segment(1) !== "attendance" ?  bundleJs($attendanceAdditionalScripts, "public/v1/js/attendance/", "attendance_page_common",  true) : ""; ?>
    <?php echo bundleJs($attendanceScripts, "public/v1/js/attendance/", "timer",  true); ?>
<?php endif; ?>