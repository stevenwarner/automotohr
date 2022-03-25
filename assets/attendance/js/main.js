$(function() {
    //
    var XHR = null;
    //
    var CBObj = {
        cb: {},
        params: {}
    };
    //
    var locOBJ = {
        lat: 0,
        lon: 0
    };

    /**
     * Handles clock in/out, break in/out
     */
    $(document).on('click', '.jsAttendanceClockBTN, .jsAttendanceBTN', function(event) {
        //
        event.preventDefault();
        //
        CheckPost($(this).data('type'));
    });

    /**
     * Add permission check
     * @param {string} action 
     */
    function CheckPost(action) {
        CBObj.cb = MarkAttendance;
        CBObj.params = {};
        CBObj.params.action = action;
        CheckAndGetLatLon();
    }

    /**
     * Load Clock
     * @param {string} action 
     */
    function CheckClock() {
        CBObj.cb = LoadClock;
        CBObj.params = {};
        CheckAndGetLatLon();
    }

    /**
     * Marks attendance
     * @param {string} action 
     */
    function MarkAttendance(props) {
        $('.jsAttendanceLoader').show(0);
        // 
        XHR = $.post(
                baseURI + 'attendance/mark/attendance', props
            )
            .done(function(resp) {
                $('.jsAttendanceLoader').hide(0);
                //
                if (resp.errors) {
                    return alertify.alert(
                        'Error!',
                        resp.errors.join('<br />'),
                        CB
                    )
                }
                //
                return alertify.alert(
                    'Success',
                    resp.success,
                    function() {
                        SetClock(props.action);
                    }
                )
            })
            .fail(HandleError);
    }

    /**
     * Show clock
     */
    function LoadClock() {
        // 
        XHR = $.get(
                baseURI + 'attendance/get/clock'
            )
            .done(function(resp) {
                //
                SetClock(resp.success.last_status);
                //
                if (
                    resp.success.last_status == 'clock_in' ||
                    resp.success.last_status == 'break_out'
                ) {
                    var today = new Date();
                    var initialDate = new Date(today.getFullYear(), today.getMonth(), today.getDate(), resp.success.hours, resp.success.minutes, resp.success.seconds);
                    //
                    SetClockCount(initialDate);
                }
            })
            .fail(HandleError);
    }

    function SetClock(action) {
        //
        $('.jsAttendanceClockBTN').hide(0);
        $('.jsAttendanceBTN').addClass('dn');
        //
        if (action == 'clock_in') {
            $('.jsAttendanceClockBTN[data-type="break_in"]').show(0);
            $('.jsAttendanceClockBTN[data-type="clock_out"]').show(0);
            $('.jsAttendanceBTN[data-type="break_in"]').removeClass('dn');
            $('.jsAttendanceBTN[data-type="clock_out"]').removeClass('dn');
        } else if (action == 'break_in') {
            $('.jsAttendanceClockBTN[data-type="break_out"]').show(0);
            $('.jsAttendanceClockBTN[data-type="clock_out"]').show(0);
            $('.jsAttendanceBTN[data-type="break_out"]').removeClass('dn');
            $('.jsAttendanceBTN[data-type="clock_out"]').removeClass('dn');
        } else {
            $('.jsAttendanceClockBTN[data-type="clock_in"]').show(0);
            $('.jsAttendanceBTN[data-type="clock_in"]').removeClass('dn');
        }
        //
        $('.jsAttendanceLoader').hide(0);
    }

    function SetClockCount(initialDate) {
        //
        let hour = initialDate.getHours();
        let minutes = initialDate.getMinutes();
        let seconds = initialDate.getSeconds();
        //
        $('.jsAttendanceClockHour').text(hour.toString().length == 1 ? '0' + hour : hour);
        $('.jsAttendanceClockMinute').text(minutes.toString().length == 1 ? '0' + minutes : minutes);
        $('.jsAttendanceClockSeconds').text(seconds.toString().length == 1 ? '0' + seconds : seconds);

    }

    /**
     * 
     */
    function CheckAndGetLatLon() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(onSuccess, onFail);
        } else {
            console.log('Geolocation is required for this page, but your browser doesn\'t support it. Try it with a browser that does');
        }
    }

    /**
     * Retrieves lat lon
     * @param {object} position 
     */
    function onSuccess(position) {
        //
        locOBJ.lat = position.coords.latitude;
        locOBJ.lon = position.coords.longitude;
        //
        CBObj.params.lat = position.coords.latitude;
        CBObj.params.lon = position.coords.longitude;
        //
        CBObj.cb(CBObj.params);
    }

    /**
     * 
     * @returns 
     */
    function onFail() {
        // return alertify.alert("Please, allow location API access to AutomotoHR.", function() {
            //
            locOBJ.lat = 0;
            locOBJ.lon = 0;
            //
            CBObj.cb(CBObj.param);
        // });
    }

    /**
     * Handles AJAX errors
     * @param {object} resp 
     * @returns 
     */
    function HandleError(resp) {
        //
        XHR = null;
        //
        // return alertify.alert(
        //     'Something went wrong while processing the request.',
        //     function() {}
        // ).setHeader(resp.statusText + ' - ' + resp.status);
    }

    /**
     * Empty callback
     */
    function CB() {}
    //
    $('.jsAttendanceClockBTN').hide(0);
    $('.jsAttendanceBTN').addClass('dn');

    CheckClock();
});