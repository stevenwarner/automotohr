$(function() {
    //
    var XHR = null;

    /**
     * 
     */
    function LoadClock() {
        // 
        XHR = $.get(baseURI + 'attendance/load/clock')
            .done(function(resp) {
                // SetClock(resp);
            })
            .fail(HandleError);
    }

    /**
     * Marks attendance
     * @param {string} action 
     */
    function MarkAttendance(action) {
        CheckAndGetLatLon();
        // // 
        // XHR = $.post(
        //         baseURI + 'attendance/mark/attendance', {
        //             action: action
        //         }
        //     )
        //     .done(function(resp) {
        //         // SetClock(resp);
        //     })
        //     .fail(HandleError);
    }

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
        var lat = position.coords.latitude;
        var long = position.coords.longitude;
    }

    /**
     * 
     * @returns 
     */
    function onFail() {
        return alertify.alert("Please, allow location API access to AutomotoHR.", CB);
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
        return alertify.alert(
            'Something went wrong while processing the request.',
            function() {}
        ).setHeader(resp.statusText + ' - ' + resp.status);
    }

    /**
     * Empty callback
     */
    function CB() {}


    // LoadClock();
    // MarkAttendance('clock_in');
});