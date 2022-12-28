<?php if (checkIfAppIsEnabled('attendance')) { ?>
    <div class="dash-box">
        <a href="<?= base_url('attendance/my'); ?>">
            <div class="dashboard-widget-box">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; z-index: 1; background: rgba(255,255,255,.9);" class="jsAttendanceLoader">
                    <i class="fa fa-circle-o-notch fa-spin text-center csF40 csFC3" style="margin-top: 90px;" aria-hidden="true"></i>
                </div>
                <h4 class="jsAttendanceCurrentClockDateTime csFC1 csFC20"></h4>
                <h2 class="jsAttendanceClock p0 m0 mb10">
                    <span class="jsAttendanceClockHour csFC1 csF26">00</span>
                    <span class="csFC1 csF26">:</span>
                    <span class="jsAttendanceClockMinute csFC1 csF26">00</span>
                    <span class="csFC1 csF26">:</span>
                    <span class="jsAttendanceClockSeconds csFC1 csF26">00</span>
                </h2>
                <div class="jsAttendanceBTNs p0 m0">
                    <button class="btn btn-success jsAttendanceBTN dn" data-type="clock_in">
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


    <div class="dash-box" id="locationbox">

        <?php

//echo GOOGLE_API_KEY;
//die();
/*
        $address = "31.4544132,74.2766072";
        if (!empty($address)) {
            $map_url = "https://maps.googleapis.com/maps/api/staticmap?latlng=" . $address . "&zoom=13&size=180x200&key=" . GOOGLE_API_KEY . "&markers=color:blue|label:|" . $address;
            $map_anchor = '<a href = "https://maps.google.com/maps?z=12&t=m&q=' . $address . '"><img src = "' . $map_url . '" alt = "No Map Found!" ></a>';
            $show_map = '<b>Employee Current Location</b>';
            $show_map .= $map_anchor;
            echo $show_map;
        }
        */
        ?>

    </div>



<?php } ?>