$(function () {
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
    //
    var CountDown;

    var checkInCheck = '';

    var lateCheck = '';
    var lngeCheck = '';


    /**
     * Handles clock in/out, break in/out
     */
    $(document).on('click', '.jsAttendanceClockBTN, .jsAttendanceBTN', function (event) {
        //
        event.preventDefault();
        //
        ml(true, 'jsAttendanceLoader');
        CheckPost($(this).data('type'));
    });

    /**
     * Shows the location on map
     */
    $(document).on('click', '.jsAttendanceViewLocation', function (event) {
        //
        event.preventDefault();
        //
        var data = $(this).closest('.jsAttendanceMyList').data();
        //
        Model({
            Id: 'jsAttendanceViewLocationModal',
            Title: 'Location',
            Loader: 'jsAttendanceViewLocationModalLoader',
            Body: '<div class="container"><iframe style="width:100%; height: 400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' + (data.lat) + ',' + (data.lon) + '&hl=en&z=17&amp;output=embed"></iframe></div>'
        }, function () {
            ml(false, 'jsAttendanceViewLocationModalLoader');
        });
    });

    /**
     * 
     */
    $('.jsAttendanceManageUpdate').click(function (event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('.jsAttendanceMyList').data('id');
        var time = $(this).closest('.jsAttendanceMyList').find('.jsTimeField').val();
        //
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(time);

        if (!isValid) {
            return alertify.alert("Notice", "Please enter valid time format", CB);
        }
        //
        ml(true, 'jsAttendanceManageLoader');
        //
        XHR = $.post(
            baseURI + 'attendance/manage', {
            Id: id,
            time: CheckTime(time)
        }
        )
            .success(function (resp) {
                HandleManualCheckIn(resp, "Time slot updated successfully!");
            })
            .fail(HandleError);

    });

    /**
     * 
     */
    $('.jsAttendanceAddNewSlot').click(function (event) {
        //
        event.preventDefault();
        //
        var id = $(this).data('attendance_sid');
        //
        ml(true, 'jsAttendanceManageLoader');
        //
        XHR = $.get(
            baseURI + 'attendance/add_slot/' + id
        )
            .done(function (resp) {
                if (resp.success) {
                    $(".jsAddAttendanceSlot").show();
                    $("#jsAttendanceSlotID").val(id);
                    $("#jsAttendanceDate").val(resp.success.date);
                    $("#jsAttendanceStatus").append(resp.success.option);
                    ml(false, 'jsAttendanceManageLoader');
                }

            })
            .fail(HandleError);

    });

    /**
     * Save manual time slot 
     */
    $(document).on('click', '.jsAttendanceSaveSlot', function (event) {
        //
        event.preventDefault();
        //
        var status = $('#jsAttendanceStatus').val();
        var time = $('#jsAttendanceTime').val();
        var id = $("#jsAttendanceSlotID").val();
        var date = $("#jsAttendanceDate").val();
        var previousTime = $("#jsAttendanceLastSlotTime").val();
        //
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(time);

        if (!isValid) {
            return alertify.alert("Notice", "Please enter valid time format", CB);
        }
        //
        var new_time = CheckTime(time);
        //
        if (previousTime > new_time || previousTime == new_time) {
            return alertify.alert("Notice", "Please enter greater time then previous slot.", CB);
        }
        //
        ml(true, 'jsAttendanceManageLoader');
        CheckPost(status, { time: new_time, id: id, date: date });
    });

    /**
     * Cancel manual time slot 
     */
    $(document).on('click', '.jsAttendanceCancelSlot', function (event) {
        //
        $("#jsAttendanceSlotID").val("");
        $("#jsAttendanceDate").val("");
        $('#jsAttendanceTime').val("");
        $("#jsAttendanceStatus").html("");
        $(".jsAddAttendanceSlot").hide();
    });

    /**
     * 
     */
    $('.jsAttendanceSaveSettings').click(SaveSettings);

    /**
     * Add permission check
     * @param {string} action 
     */
    function CheckPost(action, obj) {
        CBObj.cb = MarkAttendance;
        CBObj.params = {};
        CBObj.params.action = action;
        if (obj !== undefined) {
            for (var i in obj) {
                CBObj.params[i] = obj[i];
            }
        }

        console.log(CBObj)
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

    function HandleManualCheckIn(resp, message) {
        alertify.alert("Success", message, function () {
            ml(false, 'jsAttendanceManageLoader');
            window.location.reload();
        });
    }

    /**
     * Marks attendance
     * @param {string} action 
     */
    function MarkAttendance(props) {
        //
        clearInterval(CountDown);
        //
        $('.jsAttendanceLoader').show(0);
        // 
        XHR = $.post(
            baseURI + 'attendance/mark/attendance', props
        )
            .done(function (resp) {
                if (props.id) {
                    return HandleManualCheckIn(resp, "Manual time slot added successfully!");
                }
                //
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
                    function () {
                        LoadClock();
                    }
                )
            })
            .fail(HandleError)
            .always(function () {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsAttendanceLoader");
			});
    }

    /**
     * Show clock
     */
    function LoadClock() {
        //
        $('.jsAttendanceLoader').show(0);
        let currentTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        moment.tz.setDefault("America/Los_Angeles");
        console.log(currentTimeZone)
        // 
        XHR = $.ajax({
			url: baseURI + 'attendance/get/clock',
			method: "GET",
            data: {
                "timezone": currentTimeZone
            }
		})
        .done(function (resp) {
            //
            SetClock(resp.success.last_status);
            //
            if (resp.success.last_status == "break_in" || resp.success.last_status == "clock_in") {
                //
                var diffHours = 0;
                var diffMiuntes = 0;
                var diffSeconds = 0;
                //
                // break record
                if (resp.success.break_record && resp.success.last_status == "break_in") {
                    var breakTime = resp.success.break_record;

                    var breakIn = '';

                    let totalBreakduration = moment.duration(0, 'hours');

                    breakTime.map(function (break_record, index) {

                        if (break_record.action == 'break_in') {
                            breakIn = break_record.action_date_time;
                        }
                        if (break_record.action == 'break_out' && breakIn != '') {
                            breakDifference = moment.duration(moment(break_record.action_date_time).diff(breakIn));
                            varDif = breakDifference.hours() + '-' + breakDifference.minutes() + '-' + breakDifference.seconds();
                            console.log(varDif);
                            totalBreakduration.add(breakDifference.hours(), 'hours');
                            totalBreakduration.add(breakDifference.minutes(), 'minutes');
                            totalBreakduration.add(breakDifference.seconds(), 'seconds');
                            breakIn = '';
                        }

                        if (index == (breakTime.length - 1) && break_record.action == 'break_in') {
                            breakDifference = moment.duration(moment().diff(breakIn));
                            totalBreakduration.add(breakDifference.hours(), 'hours');
                            totalBreakduration.add(breakDifference.minutes(), 'minutes');
                            totalBreakduration.add(breakDifference.seconds(), 'seconds');
                        }
                    })

                    diffHours = totalBreakduration.hours();
                    diffMiuntes = totalBreakduration.minutes();
                    diffSeconds = totalBreakduration.seconds();
                }
                //
                if (resp.success.last_status == "clock_in") {
                    // Get Current Date Time 
                    var checkinTime = resp.success.action_date_time;
                    var difference = moment.duration(moment().diff(moment(checkinTime, 'Y-M-D H:m:s')));
                    //
                    diffHours = difference.hours();
                    diffMiuntes = difference.minutes();
                    diffSeconds = difference.seconds();
                }
                //
                CountDownTimer(diffHours, diffMiuntes, diffSeconds, resp.success.last_status);
            }
        })
        .fail(HandleError);
    }

    function trackEmployeeLocation() {
        //  console.log(checkInCheck);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                /// drawLocationMap(lat, lng);

                var obj = {};
                obj.lat = lat;
                obj.lng = lng;

                //
                XHR = $.ajax({
                    url: baseURI + 'attendance/savelocation',
                    method: "post",
                    data: obj
                })
                    .success(function (resp) {
                    })
                    .fail(HandleError);

                lateCheck = lat;
                lngeCheck = lng;
            }, function (error) {
            });

            //
            if (checkInCheck == 'clock_in') {
                setTimeout(function () {
                    trackEmployeeLocation();
                }, 5000);

            }

            //  lateCheck = lat;
            //  lngeCheck = lng;
        }
    }


    /*
        function drawLocationMap(late, lnge) {
            var address = late + ',' + lnge;
            if (address != '') {
                if (lateCheck !== late || lngeCheck !== lnge) {
                    var map_url = "https://maps.googleapis.com/maps/api/staticmap?latlng=" + address + "&zoom=13&size=180x200&key=" + googleMapKey + "&markers=color:blue|label:|" + address;
                    var map_anchor = '<a href = "https://maps.google.com/maps?z=12&t=m&q=' + address + '"><img src = "' + map_url + '" alt = "No Map Found!" ></a>';
                    var show_map = '<b>Employee Current Location</b>';
                    show_map += map_anchor;
                    $('#locationbox').html(show_map);
                }
            }
            lateCheck = late;
            lngeCheck = lnge;
        }
    */

    /**
     * Saves settings
     * @param {object} event 
     */
    function SaveSettings(event) {
        //
        event.preventDefault();
        //
        var obj = {};
        obj.roles = $('#js-roles').val() || [];
        obj.departments = $('#js-specific-department-visibility').val() || [];
        obj.teams = $('#js-specific-team-visibility').val() || [];
        obj.employees = $('#js-specific-employee-visibility').val() || [];
        obj.payroll = $('.is_visible_to_payroll').prop('checked') ? 1 : 0;
        //
        ml(true, 'jsAttendanceSettingsLoader');
        //
        XHR = $.ajax({
            url: baseURI + 'attendance/settings',
            method: "post",
            data: obj
        })
            .success(function (resp) {
                //
                ml(false, 'jsAttendanceSettingsLoader');
                //
                return alertify.alert('Success!', 'Settings have been updated.', CB);
            })
            .fail(HandleError);
    }

    /**
     * Handles clock buttons
     * @param {string} action 
     */
    function SetClock(action) {
        //
        $('.jsAttendanceClockBTN').hide(0);
        $('.jsAttendanceBTN').addClass('dn');
        //
        if (action == 'clock_in' || action == 'break_out') {
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

    /**
     * Shows the clocked time
     * @param {object} initialDate 
     */
    function CountDownTimer(h, m, s, lastStatus) {
        //
        clearInterval(CountDown);
        //
        if (lastStatus === 'clock_out' || lastStatus === '') {
            //
            $('.jsAttendanceClockHour').text(h.toString().length == 1 ? '0' + h : h);
            $('.jsAttendanceClockMinute').text(m.toString().length == 1 ? '0' + m : m);
            $('.jsAttendanceClockSeconds').text(s.toString().length == 1 ? '0' + s : s);
            return;
        }
        //
        var td = new Date();
        //
        var ld = new Date(td.getFullYear(), td.getMonth(), td.getDate(), h, m, s, 0);
        //
        var id = new Date(ld.getTime());
        //
        id.setSeconds(id.getSeconds() + 1);
        //
        var md = new Date(id.getFullYear(), id.getMonth(), id.getDate(), id.getHours(), id.getMinutes(), id.getSeconds(), 0);
        //
        var
            hours,
            minutes,
            seconds;
        //
        CountDown = setInterval(function () {
            //
            id = new Date(md.getTime());
            //
            id.setSeconds(id.getSeconds() + 1);
            //
            md = new Date(td.getFullYear(), td.getMonth(), td.getDate(), id.getHours(), id.getMinutes(), id.getSeconds(), 0);
            //
            hours = md.getHours();
            minutes = md.getMinutes();
            seconds = md.getSeconds();
            //
            $('.jsAttendanceClockHour').text(hours.toString().length == 1 ? '0' + hours : hours);
            $('.jsAttendanceClockMinute').text(minutes.toString().length == 1 ? '0' + minutes : minutes);
            $('.jsAttendanceClockSeconds').text(seconds.toString().length == 1 ? '0' + seconds : seconds);
        }, 1000);

        if (lastStatus == 'clock_in') {
            checkInCheck = 'clock_in';
            $('#locationbox').show();
            trackEmployeeLocation();
        } else {
            checkInCheck = '';
            $('#locationbox').hide();
        }

    }

    /**
     * Check and start the clock
     * @returns 
     */
    function InitClock() {
        if ($('.jsAttendanceCurrentClockHour') === undefined) {
            return;
        }
        //
        return setInterval(StartClock, 1);
    }

    /**
     * Starts the clock
     */
    function StartClock() {
        //
        var dt = new Date();
        //
        $('.jsAttendanceCurrentClockHour').text(dt.getHours().toString().length === 1 ? '0' + dt.getHours() : dt.getHours());
        $('.jsAttendanceCurrentClockMinutes').text(dt.getMinutes().toString().length === 1 ? '0' + dt.getMinutes() : dt.getMinutes());
        $('.jsAttendanceCurrentClockSeconds').text(dt.getSeconds().toString().length === 1 ? '0' + dt.getSeconds() : dt.getSeconds());
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
     * Validates the time
     * @param {string} input 
     * @returns 
     */
    function CheckTime(input) {
        //
        var ov = input.replace(/[^0-9]/g, '');
        //
        var av = [0, 0, ':', 0, 0];
        //
        var sv = ov.split('');
        //
        av[0] = sv[0] ? sv[0] : 0;
        av[1] = sv[1] ? sv[1] : 0;
        av[3] = sv[2] ? sv[2] : 0;
        av[4] = sv[3] ? sv[3] : 0;
        //
        var fv = av.join('');
        //
        if (fv > '23:59') {
            fv = '23:59';
        }
        //
        return fv;
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
        //
        locOBJ.lat = 0;
        locOBJ.lon = 0;
        //
        CBObj.cb(CBObj.params);
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
        HideLoaders();
    }

    /**
     * Hides the loaders
     */
    function HideLoaders() {
        //
        //
        $('.jsAttendanceLoader').hide(0);
        //
        if ($('[data-page="jsAttendanceSettingsLoader"]').length) {
            ml(false, 'jsAttendanceSettingsLoader');
        }
        //
        if ($('[data-page="jsAttendanceManageLoader"]').length) {
            ml(false, 'jsAttendanceManageLoader');
        }
    }

    /**
     * Starts the current clock
     */
    function InitCurrentClock() {
        //
        //
        setInterval(function () {
            //
            $('.jsAttendanceCurrentClockDateTime').text(moment().format('MMM D YYYY, ddd HH:mm:ss'));
        }, 1000);
    }

    /**
     * Empty callback
     */
    function CB() { }

    //
    if ($('.jsDatePicker').length) {
        $('.jsDatePicker').datepicker({
            changeYear: true,
            changeMonth: true,
        });
    }

    //
    if ($('.jsTimePicker').length) {
        $('.jsTimePicker').datetimepicker({
            format: 'm/d/Y H:m',
            minDate: 0,
            maxDate: 0,
            interval: 15
        });
    }

    //
    if ($('#jsSpecificEmployees').length) {
        $('#jsSpecificEmployees').select2({
            closeOnSelect: false
        });
    }
    //
    CheckClock();
    //
    InitClock();
    //
    HideLoaders();
    //
    InitCurrentClock();
    //
    
    //
    // navigator.permissions.query({ name: 'geolocation' }).then(console.log);
});