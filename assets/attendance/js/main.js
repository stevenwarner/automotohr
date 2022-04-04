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
     * Shows the location on map
     */
    $(document).on('click', '.jsAttendanceViewLocation', function(event) {
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
        }, function() {
            ml(false, 'jsAttendanceViewLocationModalLoader');
        });
    });

    /**
     * 
     */
    $('.jsAttendanceManageUpdate').click(function(event) {
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
            .success(function(resp) {
                HandleManualCheckIn(resp, "Time slot updated successfully!");
            })
            .fail(HandleError);

    });

    /**
     * 
     */
    $('.jsAttendanceAddNewSlot').click(function(event) {
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
            .done(function(resp) {
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
    $(document).on('click', '.jsAttendanceSaveSlot', function(event) {
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
    $(document).on('click', '.jsAttendanceCancelSlot', function(event) {
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
        alertify.alert("Success", message, function() {
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
        $('.jsAttendanceLoader').show(0);
        // 
        XHR = $.post(
                baseURI + 'attendance/mark/attendance', props
            )
            .done(function(resp) {
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

                var today = new Date();
                var initialDate = new Date(today.getFullYear(), today.getMonth(), today.getDate(), resp.success.hours, resp.success.minutes, resp.success.seconds);
                //
                SetClockCount(initialDate);

            })
            .fail(HandleError);
    }

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
            .success(function(resp) {
                //
                ml(false, 'jsAttendanceSettingsLoader');
                //
                return alertify.alert('Success!', 'Settings have been updated.', CB);
            })
            .fail(HandleError);
        //
        console.log(obj)
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
        // return alertify.alert("Please, allow location API access to AutomotoHR.", function() {
        //
        locOBJ.lat = 0;
        locOBJ.lon = 0;
        //
        CBObj.cb(CBObj.params);
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
        HideLoaders();
        // //
        // return alertify.alert(
        //     'Something went wrong while processing the request.',
        //     function() {}
        // ).setHeader(resp.statusText + ' - ' + resp.status);
    }

    /**
     * Hides the loaders
     */
    function HideLoaders() {
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
     * Empty callback
     */
    function CB() {}

    //
    $('.jsDatePicker').datepicker({
        changeYear: true,
        changeMonth: true,
    });

    //
    $('.jsTimePicker').datetimepicker({
        format: 'm/d/Y H:m',
        minDate: 0,
        maxDate: 0,
        interval: 15
    });

    //
    $('.jsAttendanceClockBTN').hide(0);
    $('.jsAttendanceBTN').addClass('dn');
    //
    CheckClock();
    //
    InitClock();
    //

    $('#jsSpecificEmployees').select2({
        closeOnSelect: false
    });

    HideLoaders();
});