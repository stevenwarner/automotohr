/**
 * Shift Trade
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Shift Trade
 */


/**
     * holds the modal page id
     */
const modalId = "jsModalPage";
const modalLoader = modalId + "Loader";
const modalBody = modalId + "Body";

let shiftsIds = [];


$(function shiftsTrade() {
    //
    let XHR = null;
    let validatorRef = null;


    let obj = {};
    //
    $(".jsDateRangePicker").daterangepicker({
        showDropdowns: true,
        autoApply: true,
        locale: {
            format: "MM/DD/YYYY",
            separator: " - ",
        },
    });

    //
    $('#check_all').click(function () {
        if ($('#check_all').is(":checked")) {
            $('.my_checkbox:checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $('.my_checkbox:checkbox').each(function () {
                this.checked = false;
            });
        }
    });

    $(".jsTradeShifts").click(function (event) {
        // prevent the event from happening
        event.preventDefault();

        let shiftsArrayIds = [];
        var status = '';
        $(".my_checkbox:checked").each(function () {

            status = $(this).data('status');
            if (status == '' || status == 'rejected' || status == 'cancelled') {
                shiftsArrayIds.push($(this).val());
            }

        });

        if (shiftsArrayIds.length > 0) {
            callToShiftsTrade(shiftsArrayIds);
        } else {
            alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
        }

    });


    $(".jsCancelTradeShifts").click(function (event) {
        // prevent the event from happening
        event.preventDefault();

        let shiftsArrayIds = [];
        var status = '';
        $(".my_checkbox:checked").each(function () {
            status = $(this).data('status');
            if (status == 'awaiting confirmation') {
                shiftsArrayIds.push($(this).val());
            }

        });

        if (shiftsArrayIds.length > 0) {

            alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to cancel the shift swap?',
                function () {
                    //
                    const formObj = new FormData();
                    // set the file object
                    formObj.append("shiftids", shiftsArrayIds);
                    // 
                    processCallWithoutContentType(
                        formObj,
                        '',
                        "settings/shifts/tradeshiftscancel",
                        function (resp) {
                            // show the message
                            _success(resp.msg, function () {
                                window.location.reload();
                            });
                        }
                    );
                },
                function () {

                }
            )

        } else {
            alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
        }

    });

    //
    $(".jsTradeShift").click(function (event) {
        // prevent the event from happening
        event.preventDefault();
        //
        let shiftsArrayIds = [];

        shiftsArrayIds.push($(this).data('shiftid'));
        callToShiftsTrade(shiftsArrayIds);
    });

    //
    $(".jsCancelTradeShift").click(function (event) {
        // prevent the event from happening
        event.preventDefault();

        let shiftsArrayIds = [];
        let shiftId = $(this).data('shiftid');

        alertify.confirm(
            'Are You Sure?',
            'Are you sure you want to cancel the shift swap?',
            function () {
                //
                const formObj = new FormData();
                // set the file object
                shiftsArrayIds.push(shiftId);
                formObj.append("shiftids", shiftsArrayIds);
                // 
                processCallWithoutContentType(
                    formObj,
                    '',
                    "settings/shifts/tradeshiftscancel",
                    function (resp) {
                        // show the message
                        _success(resp.msg, function () {
                            window.location.reload();
                        });
                    }
                );
            },
            function () {

            }
        )
    });

    //
    function callToShiftsTrade(shiftIds) {

        shiftsIds = shiftIds;
        makePage("Swap Shifts", "trade_shift", 0, function () {
            // hides the loader
            ml(false, modalLoader);
            //
            validatorRef = $("#jsPageTradeShiftForm").validate({
                rules: {
                    employees: { required: true },
                },
                errorPlacement: function (error, element) {
                    if ($(element).parent().hasClass("input-group")) {
                        $(element).parent().after(error);
                    } else {
                        $(element).after(error);
                    }
                },
                submitHandler: function (form) {
                    $(form).serializeArray();
                    //

                    if ($('#employees :selected').val() == '0') {
                        return _error("Please select an employee.");
                    } else {
                        employeeId = $('#employees :selected').val();
                    }

                    //
                    alertify.confirm(
                        'Are You Sure?',
                        'Are you sure you want to swap shifts?',
                        function () {
                            const formObj = new FormData();
                            // set the file object
                            formObj.append("employeeid", employeeId);
                            formObj.append("shiftids", shiftIds);
                            //
                            processCallWithoutContentType(
                                formObj,
                                '',
                                "settings/shifts/tradeshifts",
                                function (resp) {
                                    // show the message
                                    _success(resp.msg, function () {
                                        window.location.reload();
                                    });
                                }
                            );

                        },
                        function () {

                        }
                    )

                },
            });
        });
    }

    //
    $(".jsConfirmTradeShifts").click(function (event) {
        // prevent the event from happening
        event.preventDefault();

        let shiftsArrayIds = [];
        var status = '';
        $(".my_checkbox:checked").each(function () {
            status = $(this).data('status');
            if (status == 'awaiting confirmation') {
                shiftsArrayIds.push($(this).val());
            }

        });

        if (shiftsArrayIds.length > 0) {

            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to Confirm shifts?',
                function () {
                    //
                    const formObj = new FormData();
                    // set the file object
                    formObj.append("shiftids", shiftsArrayIds);
                    formObj.append("shiftstatus", 'confirmed');
                    // 
                    processCallWithoutContentType(
                        formObj,
                        '',
                        "settings/shifts/my_trade_change_status",
                        function (resp) {
                            // show the message
                            _success(resp.msg, function () {
                                window.location.reload();
                            });
                        }
                    );
                },
                function () {

                }
            )

        } else {
            alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
        }

    });

    //
    $(".jsRejectTradeShifts").click(function (event) {
        // prevent the event from happening
        event.preventDefault();

        let shiftsArrayIds = [];
        var status = '';
        $(".my_checkbox:checked").each(function () {
            status = $(this).data('status');
            if (status == 'awaiting confirmation' || status == 'confirmed') {
                shiftsArrayIds.push($(this).val());
            }
        });

        if (shiftsArrayIds.length > 0) {
            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to Reject shifts?',
                function () {
                    //
                    const formObj = new FormData();
                    // set the file object
                    formObj.append("shiftids", shiftsArrayIds);
                    formObj.append("shiftstatus", 'rejected');
                    // 
                    processCallWithoutContentType(
                        formObj,
                        '',
                        "settings/shifts/my_trade_change_status",
                        function (resp) {
                            // show the message
                            _success(resp.msg, function () {
                                window.location.reload();
                            });
                        }
                    );
                },
                function () {
                }
            )

        } else {
            alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
        }

    });

    //
    $(".jsRejectTradeShift").click(function (event) {
        // prevent the event from happening
        event.preventDefault();

        let shiftsArrayIds = [];
        shiftsArrayIds.push($(this).data('shiftid'));

        alertify.confirm(
            'Are You Sure?',
            'Are you sure want to reject shifts?',
            function () {
                //
                const formObj = new FormData();
                // set the file object
                formObj.append("shiftids", shiftsArrayIds);
                formObj.append("shiftstatus", 'rejected');
                // 
                processCallWithoutContentType(
                    formObj,
                    '',
                    "settings/shifts/my_trade_change_status",
                    function (resp) {
                        // show the message
                        _success(resp.msg, function () {
                            window.location.reload();
                        });
                    }
                );

            },
            function () {
            }
        )
    });
  

    //
    $(document).on("click", '.jsApproveTradeShift', function (event) {

        // prevent the event from happening
        event.preventDefault();

        let shiftsArrayIds = [];
        let shiftId = $(this).data('shiftid');
        let toEmployeeId = $(this).data('toemployeeid');

        alertify.confirm(
            'Are You Sure?',
            'Are you sure want to approve open shift claim request?',
            function () {
                //
                const formObj = new FormData();
                // set the file object
                shiftsArrayIds.push(shiftId);
                formObj.append("shiftids", shiftsArrayIds);
                formObj.append("toEmployeeId", toEmployeeId);
                // 

                processCallWithoutContentType(
                    formObj,
                    '',
                    "settings/shifts/claimshiftsapprove",
                    function (resp) {
                        // show the message
                        _success(resp.msg, function () {
                            window.location.reload();
                        });
                    }
                );
            },
            function () {

            }
        )
    });


    //
    $(document).on("click", '.jsAdminApproveTradeShifts', function (event) {

        event.preventDefault();

        let shiftsArrayIds = [];
        $(".my_checkbox:checked").each(function () {
            shiftsArrayIds.push($(this).val());
        });


        if (shiftsArrayIds.length > 0) {

            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to approve shift swap requests?',
                function () {
                    //
                    const formObj = new FormData();
                    // set the file object
                    formObj.append("shiftids", shiftsArrayIds);
                    // 

                    processCallWithoutContentType(
                        formObj,
                        '',
                        "settings/shifts/tradeshiftsapprove",
                        function (resp) {
                            // show the message
                            _success(resp.msg, function () {
                                window.location.reload();
                            });
                        }
                    );
                },
                function () {

                }
            )

        } else {
            alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
        }

    });


    //
    $(document).on("click", '.jsAdminRejectTradeShift', function (event) {

        // prevent the event from happening
        event.preventDefault();

        let shiftsArrayIds = [];
        let shiftId = $(this).data('shiftid');
        let toEmployeeId = $(this).data('toemployeeid');

        alertify.confirm(
            'Are You Sure?',
            'Are you sure want to reject open shift claim request?',
            function () {
                //
                const formObj = new FormData();
                // set the file object
                shiftsArrayIds.push(shiftId);
                formObj.append("shiftids", shiftsArrayIds);
                formObj.append("toEmployeeId", toEmployeeId);
                // 
                processCallWithoutContentType(
                    formObj,
                    '',
                    "settings/shifts/claimshiftsreject",
                    function (resp) {
                        // show the message
                        _success(resp.msg, function () {
                            window.location.reload();
                        });
                    }
                );
            },
            function () {

            }
        )
    });


    //
    $(".jsAdminRejectTradeShifts").click(function (event) {

        event.preventDefault();

        let shiftsArrayIds = [];
        $(".my_checkbox:checked").each(function () {
            shiftsArrayIds.push($(this).val());
        });

        //
        if (shiftsArrayIds.length > 0) {
            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to reject open shift claim requests?',
                function () {
                    //
                    const formObj = new FormData();
                    // set the file object
                    formObj.append("shiftids", shiftsArrayIds);
                    processCallWithoutContentType(
                        formObj,
                        '',
                        "settings/shifts/claimshiftsreject",
                        function (resp) {
                            // show the message
                            _success(resp.msg, function () {
                                window.location.reload();
                            });
                        }
                    );
                },
                function () {

                }
            )
        } else {

            alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
        }

    });


    //
    $("#jsApplyFilter").click(function (event) {

        event.preventDefault();

        //
        callOBJ.Requests.Main.type = 'all';
        callOBJ.Requests.Main.filter.dateRange = $(".jsDateRangePicker").val();

        //
        fetchTradeRequests();

    });



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

        params = "params?shiftsIds=" + shiftsIds;

        if (shiftsIds != '') {
            callUrl = baseUrl("settings/page/" + pageSlug + "/" + pageId + "/" + params);
        } else {
            callUrl = baseUrl("settings/page/" + pageSlug + "/" + pageId);
        }

        // check if call is already made
        if (XHR !== null) {
            // abort the call
            XHR.abort();
        }
        // make a new call
        XHR = $.ajax({
            url: callUrl,
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


    // Tabs

    let callOBJ = {
        Requests: {
            Main: {
                action: 'get_openshifts_requests',
                type: 'all',
                filter: {
                    dateRange: ''
                }
            }
        },

    },
        xhr = null;
    //

    let handlerURL = baseUrl("settings/handler");
    //
    $('.jsReportTab').click(function (e) {
        //
        e.preventDefault();
        //
        callOBJ.Requests.Main.type = 'all';
        callOBJ.Requests.Main.filter.dateRange = $(".jsDateRangePicker").val();
        //
        $(".jsReportTab").parent().removeClass("active").removeClass('csActiveTab');
        $(this).parent().addClass("active").addClass('csActiveTab');
        //
        fetchTradeRequests();
    });

    callOBJ.Requests.Main.type = 'all';
    callOBJ.Requests.Main.filter.dateRange = $(".jsDateRangePicker").val();
    //
    fetchTradeRequests();

    // Fetch Trades
    function fetchTradeRequests() {

        //
        if (xhr != null) return;
        //
        ml(true, 'requests');
        //
        $('.js-error-row').remove();
        //
        console.log(callOBJ.Requests.Main);
        xhr = $.post(handlerURL, callOBJ.Requests.Main, function (resp) {
            //
            xhr = null;
            //
            if (resp.Redirect === true) {
                alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                    window.location.reload();
                });
                return;
            }
            //
            if (resp.Status === false && callOBJ.Balances.Main.page == 1) {
                $('.js-ip-pagination').html('');
                $('#js-data-area').html(`<tr class="js-error-row"><td colspan="${$('.js-table-head').find('th').length}"><p class="alert alert-info text-center">${resp.Response}</p></td></tr>`);
                //
                ml(false, 'requests');
                //
                return;
            }
            //
            if (resp.Status === false) {
                //
                $('.js-ip-pagination').html('');
                //
                ml(false, 'requests');
                //
                return;
            }
            //            
            setTable(resp);
        });

    }


    //
    function setTable(resp) {
        //
        let rows = '';
        //
        if (resp.Data.length == 0) {
            $('#js-data-area').html(`<tr><td colspan="7"><p class="alert alert-info text-center">No Request found.</p></td></tr>`);
            ml(false, 'requests');
            return;
        }
        //
        $("#jsAllRequests").html('(' + resp.allRequests + ')');
        //
        if (resp.requestsType == 'all') {
            $("#jsTableHeading").text("All Approval(s)");
        } else if (resp.requestsType == 'confirmed') {
            $("#jsTableHeading").text("Pending Approval(s)");
        } else if (resp.requestsType == 'approved') {
            $("#jsTableHeading").text("Approved Approval(s)");
        } else {
            $("#jsTableHeading").text("Rejected Approval(s)");
        }

        $.each(resp.Data, function (i, v) {
            //
            rows += `<tr>`;
            rows += `<td>`;
            if ((`${v.request_status}`) != "Approved" && (`${v.request_status}`) != "Admin Rejected" && (`${v.request_type}`) != "open") {
                rows += `<label class="control control--checkbox">`;
                rows += `<input type="checkbox" name="checkit[]" value="${v.shift_sid}" class="my_checkbox" data-status="" >`;
                rows += `<div class="control__indicator"></div>`;
                rows += `</label>`;
            }
            rows += `</td>`;

            rows += `<td style="vertical-align: middle;">${v.shift_date} ` + "<br>" + ` ${v.start_time}`;
            rows += `</td>`;
            rows += `</td>`;
            rows += `<td style="vertical-align: middle;">${v.created_at}`;
            rows += `</td>`;
            rows += `<td style="vertical-align: middle;">${v.from_employee}`;


            rows += `<td>`;
            rows += `<div class="col-sm-12 text-right">`;

            if ((`${v.request_status}`) != "Approved" && (`${v.request_status}`) != "Admin Rejected") {

                rows += `<button class="btn btn-red jsAdminRejectTradeShift" data-shiftid="${v.shift_sid}" data-toemployeeid="${v.employee_sid}">Reject`;
                rows += `</button>`;

                rows += `<button class="btn btn-orange jsApproveTradeShift" data-shiftid="${v.shift_sid}" data-toemployeeid="${v.employee_sid}">Approve`;
                rows += `</button>`;
            }

            rows += `</div>`;
            rows += `</td>`;
            rows += `</tr>`;


        });

        //
        $('#js-data-area').html(rows);
        //
        ml(false, 'requests');
    }


});