/**
 * Manage shifts
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @package Time & Attendance
 * @version 1.0
 */
$(function manageShifts() {
    /**
     * XHR holder
     */
    let XHR = null;

    /**
     * XHR validator
     */
    let validatorRef = null;

    /**
     * holds the modal page id
     */
    const modalId = "jsModalPage";
    const modalLoader = modalId + "Loader";
    const modalBody = modalId + "Body";

    //
    const mode = getSearchParam("mode") || "month";
    let dateStartEnd = '';

    // apply date picker
    $(".jsWeekDaySelect").daterangepicker({
        opens: "center",
        singleDatePicker: true,
        showDropdowns: true,
        autoApply: true,
        startDate: getStartDate(),
        locale: {
            format: "MM/DD/YYYY",
        },
        isInvalidDate: function (date) {
            // Check if the date is within the week (Monday to Sunday)
            if (mode === "month") {
                return date.format("DD") !== "01";
            } else {
                return date.day() !== 1;
            }
        },
    });
    // capture change event
    $(".jsWeekDaySelect").on("apply.daterangepicker", function (ev, picker) {
        //do something, like clearing an input
        const startDate =
            mode === "month"
                ? picker.startDate.clone().startOf("month").format("MM/DD/YYYY")
                : picker.startDate.clone().format("MM/DD/YYYY");
        //
        let incrementDays = 0;

        //
        if (mode === "week") {
            incrementDays = 6;
        } else if (mode === "two_week") {
            incrementDays = 13;
        } else {
            incrementDays = picker.startDate
                .endOf("month")
                .format("MM/DD/YYYY");
        }

        const endDate = picker.startDate
            .add(incrementDays, "days")
            .format("MM/DD/YYYY");


        if (mode == "month") {
            return (window.location.href = baseUrl(
                "settings/subordinateshifts/manage?mode=" +
                mode +
                "&year=" +
                picker.startDate.clone().format("YYYY") +
                "&month=" +
                picker.startDate.clone().format("MM")+
                "&departments=" + selectedDepartments +
                "&teams=" + selectedTeams
            ));
        }

        //
        window.location.href = baseUrl(
            "settings/subordinateshifts/manage?mode=" +
            mode +
            "&start_date=" +
            startDate +
            "&end_date=" +
            endDate+
            "&departments=" + selectedDepartments +
                "&teams=" + selectedTeams

        );
    });

    // adjust the height of cells
    $(".schedule-employee-row").map(function () {
        $(".schedule-column-" + $(this).data("id")).height($(this).height());
    });

    /**
     * capture click event on cell
     */
    $(".schedule-column-clickable").click(function (event) {
        // prevent the event from happening
        event.preventDefault();
        //
        callToCreateBox(
            $(this).data("eid"),
            $(this).closest(".schedule-column-container").data("date")
        );
    });

    //
    $(".jsEmployeesShifts").click(function (event) {
        // prevent the event from happening
        event.preventDefault();
        //
        callToCreateBoxMultiShifts();
    });

    $(".jsEmployeeShiftsDelete").click(function (event) {
        // prevent the event from happening
        event.preventDefault();
        //
        callToDeleteBoxMultiShifts();
    });



    /**
     * on break select
     */
    $(document).on("change", ".jsBreakSelect", function () {
        //
        const uniqId = $(this).closest(".jsBreakRow").data("key");
        //
        $('[name="breaks[' + uniqId + '][duration]"]').val(
            $(this).find("option:selected").data("duration")
        );
    });

    /**
     * generates the modal
     * @param {string} pageTitle
     * @param {string} pageSlug
     * @param {number} pageId
     * @param {function} cb
     */
    function makePage(pageTitle, pageSlug, pageId, cb) {
        Modal(
            {
                Id: modalId,
                Title: pageTitle,
                Body: '<div id="' + modalBody + '"></div>',
                Loader: modalLoader,
            },
            function () {
                fetchPage(pageSlug, pageId, cb);
            }
        );
    }

    /**
     * fetch page from server
     * @param {string} pageSlug
     * @param {function} cb
     */
    function fetchPage(pageSlug, pageId, cb) {
        // check if call is already made
        if (XHR !== null) {
            // abort the call
            XHR.abort();
        }
        // make a new call
        XHR = $.ajax({
            url: baseUrl("settings/page/" + pageSlug + "/" + pageId),
            method: "GET",
        })
            .always(function () {
                XHR = null;
            })
            .fail(handleErrorResponse)
            .done(function (resp) {
                // load the new body
                $("#" + modalBody).html(resp.view);
                // call the callback
                cb(resp);
            });
    }

    /**
     * process the call
     * @param {object} formObj
     * @param {string} buttonRef
     * @param {string} url
     * @param {Object} cb
     */
    function processCall(formObj, buttonRef, url, cb) {
        // check if call is already made
        if (XHR !== null) {
            // abort the call
            return;
        }
        //
        const btnRef = callButtonHook(buttonRef, true);
        // make a new call
        XHR = $.ajax({
            url: baseUrl(url),
            method: "POST",
            data: formObj,
            processData: true,
        })
            .always(function () {
                //
                callButtonHook(btnRef, false);
                //
                XHR = null;
            })
            .fail(handleErrorResponse)
            .done(function (resp) {
                //
                validatorRef?.destroy();
                return cb(resp);
            });
    }

    /**
     * process the call
     * @param {object} formObj
     * @param {string} buttonRef
     * @param {string} url
     * @param {Object} cb
     */
    function processCallWithoutContentType(formObj, buttonRef, url, cb) {
        // check if call is already made
        if (XHR !== null) {
            // abort the call
            return;
        }
        let btnRef;
        //
        if (buttonRef) {
            btnRef = callButtonHook(buttonRef, true);
        }

        // make a new call
        XHR = $.ajax({
            url: baseUrl(url),
            method: "POST",
            data: formObj,
            processData: false,
            contentType: false,
        })
            .always(function () {
                //
                if (buttonRef) {
                    callButtonHook(btnRef, false);
                }
                //
                XHR = null;
            })
            .fail(handleErrorResponse)
            .done(function (resp) {
                //
                validatorRef?.destroy();
                return cb(resp);
            });
    }

    /**
     * get the start date
     * @returns
     */
    function getStartDate() {
        let startDate = "";

        if (mode === "month") {
            // make date from month and year
            const year = getSearchParam("year") || moment().format("YYYY");
            const month = getSearchParam("month") || moment().format("MM");
            //
            startDate = month + "/01/" + year;
        } else {
            startDate =
                getSearchParam("start_date") ||
                moment().weekday(1).format("MM/DD/YYYY");
        }
        return startDate;
    }

    /**
     * get the end date
     *
     * @param {string} startDate
     * @returns
     */
    function getEndDate(startDate) {
        let endDate = "";

        if (mode === "week") {
            endDate =
                getSearchParam("end_date") ||
                moment(startDate, "MM/DD/YYYY")
                    .endOf("week")
                    .add(1, "day")
                    .format("MM/DD/YYYY");
        } else if (mode === "two_week") {
            endDate =
                getSearchParam("end_date") ||
                moment(startDate, "MM/DD/YYYY")
                    .add(2, "week")
                    .subtract(1, "days")
                    .format("MM/DD/YYYY");
        } else {
            // make date from month and year
            const year = getSearchParam("year") || moment().format("YYYY");
            const month = getSearchParam("month") || moment().format("MM");
            //
            endDate = moment(year + "-" + month + "-01")
                .endOf("month")
                .format("MM/DD/YYYY");
        }
        return endDate;
    }


    //
    function applyDatePicker() {
        $("#shift_date_from").daterangepicker({
            opens: "center",
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            startDate: getStartDate(),
            locale: {
                format: "MM/DD/YYYY",
            },
        });

        $("#shift_date_to").daterangepicker({
            opens: "center",
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            startDate: getStartDate(),
            locale: {
                format: "MM/DD/YYYY",
            },
        });
    }

    applyDatePicker();


    $("#shift_date_to").val(filterEndDate);
    $("#shift_date_from").val(filterStartDate);

    $(function () {
        $(".expander").on("click", function () {
            $("#TableData").toggle();
        });
    });

    //
    if (filterToggle == true) {
        $("#TableData").toggle();
    }

    // $(".js-filter-employee").select2();
    $(".jsSelect2").select2();

    $("#js-filter-department").select2('val', [selectedDepartments.split(',')]);
    $("#js-filter-team").select2('val', [selectedTeams.split(',')]);



    /**
     * generates break h*ml
     * @param {number} uniqId
     * @param {object|undefined} data
     * @returns
     */
    function generateBreakHtml(uniqId, data) {
        //
        let breakOptions = "";
        breakOptions += "<option></option>";
        //
        breaksObject.map(function (v) {
            breakOptions +=
                '<option value="' +
                v.break_name +
                '" data-duration="' +
                v.break_duration +
                '" ' +
                (data !== undefined && data.break === v.break_name
                    ? "selected"
                    : "") +
                ">" +
                v.break_name +
                " (" +
                v.break_type +
                ")</option>";
        });

        //
        let html = "";
        html += '<div class="row jsBreakRow" data-key="' + uniqId + '">';
        html += "    <br> ";
        html += '     <div class="col-sm-5">';
        html += '        <label class="text-medium">';
        html += "            Break ";
        html += '            <strong class="text-red">*</strong>';
        html += "         </label>";
        html +=
            '         <select name="breaks[' +
            uniqId +
            '][break]" class="form-control jsBreakSelect">';
        html += breakOptions;
        html += "         </select>";
        html += "     </div>";
        html += '     <div class="col-sm-3">';
        html += '         <label class="text-medium">';
        html += "             Duration ";
        html += '             <strong class="text-red">*</strong>';
        html += "         </label>";
        html += '         <div class="input-group">';
        html +=
            '             <input type="number" class="form-control jsDuration" name="breaks[' +
            uniqId +
            '][duration]" value="' +
            (data?.duration || "") +
            '" />';
        html += '             <div class="input-group-addon">mins</div>';
        html += "         </div>";
        html += "     </div>";
        html += '     <div class="col-sm-3">';
        html += '         <label class="text-medium">';
        html += "             Start TIme ";
        html += "         </label>";
        html +=
            '         <input type="text" class="form-control jsTimeField jsStartTime" placeholder="HH:MM" name="breaks[' +
            uniqId +
            '][start_time]"value="' +
            (data?.start_time
                ? moment(data.start_time, "HH:mm").format("h:mm a")
                : "") +
            '" />';
        html += "     </div>";
        html += '     <div class="col-sm-1">';
        html += "         <br>";
        html +=
            '         <button class="btn btn-red jsDeleteBreakRow" title="Delete this break" type="button">';
        html +=
            '             <i class="fa fa-trash" style="margin-right: 0"></i>';
        html += "         </button>";
        html += "     </div>";
        html += "</div>";
        //
        return html;
    }

    ////////

    $(".js-reset-my-filter-btn").click(function (event) {
        event.preventDefault();
        const startDate =
            $(".jsWeekDaySelect").data("daterangepicker").startDate;
        let newSearchurl = "";

        $("#shift_date_from").val('');
        $("#shift_date_to").val('');

        newSearchurl =
            "settings/subordinateshifts/manage?mode=month";
        //
        return (window.location.href = baseUrl(newSearchurl));
    });


    //

    $(".jsNavigateRightMy").click(function (event) {
        event.preventDefault();
        //

        let filterFields = '';
        const startDate =
            $(".jsWeekDaySelect").data("daterangepicker").startDate;

        // get the end date
        let endDate = "";
        let adder = 1;
        if (mode === "week") {
            endDate = startDate.clone().add(1, "week");
        } else if (mode === "two_week") {
            adder = 2;
            endDate = startDate.clone().add(2, "week");
        } else {
            endDate = startDate.clone().endOf("month");
        }
        //
        if (mode !== "month") {
            //
            return (window.location.href = baseUrl(
                "settings/subordinateshifts/manage?mode=" +
                mode +
                "&start_date=" +
                endDate.clone().format("MM/DD/YYYY") + filterFields +
                "&end_date=" +
                endDate
                    .clone()
                    .add(adder, "week")
                    .subtract(1, "day")
                    .format("MM/DD/YYYY") +
                "&departments=" + selectedDepartments +
                "&teams=" + selectedTeams
            ));
        } else {
            //
            const startDateObj = endDate.clone().add(1, "month");

            selectedDepartments
            //
            return (window.location.href = baseUrl(
                "settings/subordinateshifts/manage?mode=" +
                mode +
                "&year=" +
                startDateObj.clone().format("YYYY") +
                "&month=" +
                startDateObj.clone().format("MM") + filterFields +
                "&departments=" + selectedDepartments +
                "&teams=" + selectedTeams

            ));
        }
    });


    //
    $(".jsNavigateLeftMy").click(function (event) {
        event.preventDefault();

        let filterFields = '';
        const startDate =
            $(".jsWeekDaySelect").data("daterangepicker").startDate;
        //
        if (mode !== "month") {
            //
            return (window.location.href = baseUrl(
                "settings/subordinateshifts/manage?mode=" +
                mode +
                "&start_date=" +
                startDate
                    .clone()
                    .subtract(mode === "week" ? 1 : 2, "week")
                    .format("MM/DD/YYYY") +
                "&end_date=" +
                startDate.clone().subtract(1, "day").format("MM/DD/YYYY") + filterFields+
                "&departments=" + selectedDepartments +
                "&teams=" + selectedTeams

            ));
        } else {
            //
            const startDateObj = startDate.clone().subtract(1, "month");
            //
            return (window.location.href = baseUrl(
                "settings/subordinateshifts/manage?mode=" +
                mode +
                "&year=" +
                startDateObj.clone().format("YYYY") +
                "&month=" +
                startDateObj.clone().format("MM") + filterFields+
                "&departments=" + selectedDepartments +
                "&teams=" + selectedTeams

            ));
        }
    });

    //

    $(".js-apply-my-filter-btn").click(function (event) {
        event.preventDefault();

        const startDate =
            $(".jsWeekDaySelect").data("daterangepicker").startDate;
        let newSearchurl = "";

        let start_Filter_Date = $("#shift_date_from").val();
        let end_Filter_Date = $("#shift_date_to").val();

        let departments = $("#js-filter-department").val();
        let teams = $("#js-filter-team").val();

        if (start_Filter_Date != '' && end_Filter_Date != '') {
            dateStartEnd = "&start_date=" + start_Filter_Date + "&end_date=" + end_Filter_Date;
        }


        newSearchurl =
            "settings/subordinateshifts/manage?mode=week" +
            dateStartEnd +
            "&departments=" +
            departments +
            "&teams=" +
            teams;
        //
        return (window.location.href = baseUrl(newSearchurl));
    });

});