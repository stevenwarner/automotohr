$(function () {
    //
    let callOBJ = {
        Requests: {
            Main: {
                action: "get_requests",
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                level: level,
                isMine: 1,
                type: "pending",
                filter: {
                    employees: "all",
                    policies: "all",
                    status: "all",
                    order: "upcoming",
                    startDate: "",
                    endDate: "",
                },
                public: 0,
            },
        },
    },
        xhr = null;
    //
    window.timeoff.isMine = callOBJ.Requests.Main.isMine;
    //
    $("#js-filter-status").select2();
    $("#js-filter-sort").select2({
        minimumResultsForSearch: -1
    });
    //
    // fetchTimeOffs();
    // $('.jsReportTab[data-key="pending"]').trigger('click');

    // Set Filter
    //
    $("#js-filter-from-date").datepicker({
        dateFormat: "mm-dd-yy",
        changeYear: true,
        changeMonth: true,
        onSelect: function (v) {
            $("#js-filter-to-date").datepicker("option", "minDate", v);
        },
    });

    //
    $("#js-filter-to-date")
        .datepicker({
            dateFormat: "mm-dd-yy",
            changeYear: true,
            changeMonth: true,
        })
        .datepicker("option", "minDate", $("#js-filter-from-date").val());

    // Filter buttons
    $(document).on("click", ".js-apply-filter-btn", applyFilter);
    $(document).on("change", "#js-filter-sort", applyFilter);
    $(document).on("click", ".js-reset-filter-btn", resetFilter);
    $(document).on("change", ".jsEditResetCheckbox", applyFilter);
    $(".jsReportTab").click(function (e) {
        //
        e.preventDefault();
        //
        callOBJ.Requests.Main.type = $(this).data("key");
        //
        //
        $(".jsReportTab").parent().removeClass("active").removeClass('csActiveTab');
        $(this).parent().addClass("active").addClass('csActiveTab');
        //
        fetchTimeOffs();
    });

    $('.jsReportTab[data-key="pending"]').trigger('click');

    //
    $(".jsArchiveTab").hide(0);
    //
    $(".jsTeamShiftTab").click(function (e) {
        e.preventDefault();
        //
        $(".jsTeamShiftTab").removeClass("active");
        $(this).addClass("active");
        //
        callOBJ.Requests.Main.isMine = $(this).data("key");
        window.timeoff.isMine = callOBJ.Requests.Main.isMine;
        if (callOBJ.Requests.Main.isMine == 1) {
            $('.csPolicyBox').show();
            $('.jsGraphBox').show();
            $('.jsArchiveTab').hide();
            $('.jsRequestSeparator').show();
            $('.jsCreateRequestEMP').parent().show();
            $('.jsRequestTag').removeClass('col-sm-9');
            $('.jsRequestTag').addClass('col-sm-6');
        } else {
            $('.csPolicyBox').hide();
            $('.jsGraphBox').hide();
            $('.jsArchiveTab').show();
            $('.jsRequestSeparator').hide();
            $('.jsCreateRequestEMP').parent().hide();
            $('.jsRequestTag').removeClass('col-sm-6');
            $('.jsRequestTag').addClass('col-sm-9');
        }
        //
        $('.jsReportTab[data-key="pending"]').trigger("click");
    });

    //
    $(document).on("click", ".jsArchiveTimeOff", function (e) {
        //
        e.preventDefault();
        //
        let requestId = $(this).closest(".jsBox").data("id");
        //
        alertify.confirm(
            "Do you really want to archive this time off?",
            () => {
                //
                ml(true, "requests");
                //
                $.post(
                    handlerURL,
                    Object.assign({
                        action: "archive_request",
                        companyId: companyId,
                        employerId: employerId,
                        employeeId: employeeId,
                    }, { requestId: requestId, archive: 1 }),
                    (resp) => {
                        //
                        alertify.alert("SUCCESS", resp.Response, () => {
                            $('.js-apply-filter-btn').click();
                        });
                    }
                );
            },
            () => { }
        ).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        }).setHeader('CONFIRM!');;
    });

    //
    $(document).on("click", ".jsActiveTimeOff", function (e) {
        //
        e.preventDefault();
        //
        let requestId = $(this).closest(".jsBox").data("id");
        //
        alertify.confirm(
            "Do you really want to activate this time off?",
            () => {
                //
                ml(true, "requests");
                //
                $.post(
                    handlerURL,
                    Object.assign({
                        action: "archive_request",
                        companyId: companyId,
                        employerId: employerId,
                        employeeId: employeeId,
                    }, { requestId: requestId, archive: 0 }),
                    (resp) => {
                        //
                        alertify.alert("SUCCESS", resp.Response, () => {
                            $('.js-apply-filter-btn').click();
                        });
                    }
                );
            },
            () => { }
        ).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        }).setHeader('CONFIRM!');;
    });

    //
    $(document).on("click", ".jsCancelTimeOffRequest", function (e) {
        //
        e.preventDefault();
        //
        let requestId = $(this).closest(".jsBox").data("id");
        //
        alertify.confirm(
            "Do you really want to cancel this time off request?",
            () => {
                //
                ml(true, "requests");
                //
                $.post(
                    handlerURL,
                    Object.assign({
                        action: "cancel_request",
                        companyId: companyId,
                        employerId: employerId,
                        employeeId: employeeId,
                    }, { requestId: requestId }),
                    (resp) => {
                        //
                        alertify.alert("SUCCESS", resp.Response, () => {
                            window.location.reload();
                        });
                    }
                );
            },
            () => { }
        );
    });

    //
    $(document).on("click", ".jsHistoryTimeOff", function (e) {
        //
        e.preventDefault();
        //
        let requestId = $(this).closest(".jsBox").data("id");
        //
        Modal({
            Id: "jsTimeOffHistory",
            Title: "Time off - History",
            Body: ` 
                <div class="row">
                <div class="col-sm-3">
                    <div id="jsData"></div>
                </div>
                <div class="col-sm-9">
                    <div class="tabel-responsive">
                        <table class="table table-striped csCustomTableHeader">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Details</th>
                                    <th>Action</th>
                                    <th>Comment</th>
                                    <th>Action Taken</th>
                                </tr>
                            </thead>
                            <tbody id="jsTimeOffHistoryTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>`,
            Loader: "jsTimeOffHistoryLoader",
        },
            () => {
                //
                ml(true, "jsTimeOffHistoryLoader");
                //
                $("#jsPolicyHistoryTable").html("");
                //
                $.post(
                    handlerURL,
                    Object.assign({
                        action: "get_request_history",
                        companyId: companyId,
                        employerId: employerId,
                        employeeId: employeeId,
                    }, {
                        requestId: requestId,
                    }),
                    (resp) => {
                        //
                        if (resp.Redirect === true) {
                            alertify.alert(
                                "WARNING!",
                                "Your session expired. Please, re-login to continue.",
                                () => {
                                    window.location.reload();
                                }
                            );
                            return;
                        }
                        $("#jsData").html(`
                        <div class="employee-info">
                            <figure>
                                <img src="${$(`.jsBox[data-id="${requestId}"]`).find('.csBoxContentEmpSection img').prop('src')}" class="img-circle emp-image" />
                            </figure>
                            <div class="text">
                                <h4>${$(`.jsBox[data-id="${requestId}"]`).find('.csBoxContentEmpSection p').html()}</h4>
                                <p><strong>Policy: </strong></p>
                            </div>
                        </div>
                    `);
                        //
                        if (resp.Status === false) {
                            alertify.alert("WARNING!", resp.Response, () => { });
                            //
                            ml(false, "jsTimeOffHistoryLoader");
                            //
                            return;
                        }
                        //
                        let rows = "";
                        //
                        if (resp.Data.length === 0) {
                            rows = `
                        <tr>
                            <td colspan="5">
                                <p class="alert alert-info text-center">${resp.Response}</p>
                            </td>
                        </tr>
                    `;
                        } else {
                            //
                            resp.Data.map((v) => {
                                //
                                let note = JSON.parse(v.note);
                                //
                                let act = "",
                                    comment = "-";
                                if (v.action == "create") {
                                    act = "Time-off created.";
                                } else {
                                    if (note.status !== undefined) {
                                        //
                                        if (note.status == "archive")
                                            act = "Time-off marked as archive";
                                        else if (note.status == "activate")
                                            act = "Time-off marked as active";
                                        else if (note.status == "approved" && note.canApprove == 1)
                                            act = "Time-off approved 100%";
                                        else if (note.status == "approved" && note.canApprove == 0)
                                            act = "Time-off approved 50%";
                                        else if (note.status == "rejected" && note.canApprove == 1)
                                            act = "Time-off rejected 100%";
                                        else if (note.status == "rejected" && note.canApprove == 0)
                                            act = "Time-off rejected 50%";
                                        else if (note.status == "pending" && note.canApprove == 0)
                                            act = "Time-off updated";
                                        else if (note.status == "cancelled")
                                            act = "Time-off cancelled";

                                        //
                                        if (note.comment != undefined && note.comment != "")
                                            comment = note.comment;
                                    } else {
                                        act = "";
                                    }
                                    if (v.action == "update" && act == "")
                                        act = "Time-off updated.";
                                }
                                //
                                rows += `<tr>`;
                                rows += `<td>`;
                                rows += '        <div class="employee-info">';
                                rows += "            <figure>";
                                rows += `                <img src="${getImageURL(
                                    v.image
                                )}" class="img-circle emp-image" />`;
                                rows += "            </figure>";
                                rows += '            <div class="text">';
                                rows += `                <h4>${v.first_name} ${v.last_name} </h4>`;
                                rows += `                <p>${remakeEmployeeName(v, false)}</p>`;
                                rows += `                <p><a href="${baseURL}employee_profile/${v.userId
                                    }" target="_blank">Id: ${getEmployeeId(
                                        v.userId,
                                        v.employee_number
                                    )}</a></p>`;
                                rows += `                <p>${v.anniversary_text}</p>`;
                                rows += "            </div>";
                                rows += "        </div></td>";
                                rows += `                <td>`;
                                //
                                if (note.details !== undefined) {
                                    rows += `                   <div class="upcoming-time-info">            
                            <div class="icon-image">                   
                                <img src="${baseURL}assets/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1">             
                            </div>             
                            <div class="text">                  
                                <h4>${note.details.startDate == note.details.endDate
                                            ? moment(note.details.startDate).format(
                                                timeoffDateFormat
                                            )
                                            : moment(note.details.startDate).format(
                                                timeoffDateFormat
                                            ) +
                                            " - " +
                                            moment(note.details.endDate).format(
                                                timeoffDateFormat
                                            )
                                        }</h4>                  
                                <span>${note.details.policyTitle}</span><br />          
                                <span>${get_array_from_minutes(note.details.time, v.user_shift_hours, 'H:M').text}</span>
                            </div>       
                        </div>`;
                                }
                                rows += `                </td>`;
                                rows += `                <td>${act}</td>`;
                                rows += `                <td>${comment}</td>`;
                                rows += `                <td>${moment(v.created_at).format(timeoffDateFormatWithTime)}</td>`;
                                rows += `            </tr>`;
                            });
                        }

                        //
                        $("#jsTimeOffHistoryTable").html(rows);
                        //
                        ml(false, "jsTimeOffHistoryLoader");
                    }
                );
            }
        );
    });

    //
    $(document).on("click", ".jsViewPolicies", function (e) {
        //
        e.preventDefault();
        //
        startPolicyProcess(employeeId, employeeName);
    });

    //
    $(document).on("click", ".jsHolidays", function (e) {
        //
        e.preventDefault();
        //
        Modal({
            Id: "jsHolidayModal",
            Title: "Company Holidays",
            Body: ` 
            <div class="tabel-responsive">
                <table class="table table-striped csCustomTableHeader">
                    <thead>
                        <tr>
                            <th>Holiday</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="jsHolidayModalTable"></tbody>
                </table>
            </div>`,
            Loader: "jsHolidayModalLoader",
        },
            () => {
                //
                ml(true, "jsHolidayModalLoader");
                //
                $("#jsHolidayModalTable").html("");

                let rows = "";
                //
                if (window.timeoff.holidays.length === 0) {
                    rows = `
                    <tr>
                        <td colspan="2">
                            <p class="alert alert-info text-center">${resp.Response}</p>
                        </td>
                    </tr>
                `;
                } else {
                    window.timeoff.holidays.map((v) => {
                        rows += `
                        <tr>
                            <td>${v.holiday_title}</td>
                            <td>${getHolidayText(v)}</td>
                        </tr>
                    `;
                    });
                }

                //
                $("#jsHolidayModalTable").html(rows);
                //
                ml(false, "jsHolidayModalLoader");
            }
        );
    });

    //
    function resetFilter(e) {
        //
        e.preventDefault();
        //
        $("#js-filter-employee").select2("val", "all");
        $("#js-filter-policies").select2("val", "all");
        $("#js-filter-status").select2("val", "all");
        $("#js-filter-sort").select2("val", "upcoming");
        $("#js-filter-from-date").val("");
        $("#js-filter-end-date").val("");
        //
        callOBJ.Requests.Main.filter.employees = "all";
        callOBJ.Requests.Main.filter.policies = "all";
        callOBJ.Requests.Main.filter.status = "all";
        callOBJ.Requests.Main.filter.order = "upcoming";
        callOBJ.Requests.Main.filter.startDate = "";
        callOBJ.Requests.Main.filter.endDate = "";
        callOBJ.Requests.Main.filter.isMine = 0;
        //
        fetchTimeOffs();
    }

    //
    function applyFilter(e) {
        //
        e.preventDefault();
        //
        callOBJ.Requests.Main.filter.employees = $("#js-filter-employee").val();
        callOBJ.Requests.Main.filter.policies = $("#js-filter-policies").val();
        callOBJ.Requests.Main.filter.status = $("#js-filter-status").val();
        callOBJ.Requests.Main.filter.order = $("#js-filter-sort").val();
        callOBJ.Requests.Main.filter.startDate = $("#js-filter-from-date").val();
        callOBJ.Requests.Main.filter.endDate = $("#js-filter-to-date").val();
        //
        fetchTimeOffs();
    }

    // Fetch plans
    function fetchTimeOffs() {
        //
        if (window.timeoff.employees === undefined) {
            setTimeout(fetchTimeOffs, 1000);
            return;
        }
        //
        if (xhr != null) return;
        //
        ml(true, "requests");
        //
        $(".js-error-row").remove();
        //
        xhr = $.post(handlerURL, callOBJ.Requests.Main, function (resp) {
            //
            xhr = null;
            //
            if (resp.Redirect === true) {
                alertify.alert(
                    "WARNING!",
                    "Your session expired. Please, re-login to continue.",
                    () => {
                        window.location.reload();
                    }
                );
                return;
            }
            //
            if (resp.Status === false && callOBJ.Balances.Main.page == 1) {
                $(".js-ip-pagination").html("");
                $("#js-data-area").html(
                    `<tr class="js-error-row"><td colspan="${$(".js-table-head").find("th").length
                    }"><p class="alert alert-info text-center">${resp.Response
                    }</p></td></tr>`
                );
                //
                ml(false, "requests");
                //
                return;
            }
            //
            if (resp.Status === false) {
                //
                $(".js-ip-pagination").html("");
                //
                ml(false, "requests");
                //
                return;
            }
            //
            if (callOBJ.Requests.Main.isMine == 0) {
                $("#request_status_info").show();
            } else {
                $("#request_status_info").hide();
            }
            //
            setTable(resp);
        });
    }

    window.fetchTimeOffs = fetchTimeOffs;
    let allComments;
    //
    function setTable(resp) {
        //
        let rows = "";
        //
        if (resp.Data == undefined || resp.Data.length == 0) {
            $(".csContentWrap").html(
                `<p class="alert alert-info text-center">No time-offs found.</p>`
            );
            ml(false, "requests");
            return;
        }
        // Reset policies
        let sortedRequests = {};
        allComments = {};

        //
        $.each(resp.Data, function (i, v) {
            //
            let userRow = getUserById(
                v.employee_sid,
                window.timeoff.employees,
                "user_id"
            );
            if (Object.keys(userRow).length == 0) return;
            //
            // Reset policies
            // Create index if not exists
            if (sortedRequests[v.request_from_date] == undefined) sortedRequests[v.request_from_date] = [];
            v['userRow'] = userRow;
            //
            sortedRequests[v.request_from_date].push(v);
        });

        rows = '';
        //
        $.each(sortedRequests, function (i, v) {
            rows += '<div class="csContentHead">';
            // rows += `	<h4>${moment(i, timeoffDateFormatD).format(timeoffDateFormat)}</h4>`;
            // rows += '	<div class="row">';
            v.map(function (v0) {
                rows += getRequestBox(v0, v0['userRow']);
            });
            // rows += '	</div>';
            rows += '</div>';
        });


        $(".csContentWrap").html(rows);
        //
        $('.jsTooltip').tooltip({ placement: 'top', trigger: 'hover' });
        //
        $(".jsCommentsPopover").popover({
            html: true,
            placement: "right auto",
            trigger: "hover",
            template: '<div class="popover"><div class="arrow"></div><div class="popover-content"></div></div>'
        }).on('inserted.bs.popover', function (e) {
            //
            let rows = '<ul>';
            //
            allComments[$(this).closest('.jsBox').data('id')][0].map(function (li) {
                rows += `<li><strong>${li.msg}</strong> <br /> ${li.employeeName} ${li.employeeRole} <br /> ${moment(li.time, timeoffDateFormatDWT).format(timeoffDateFormatWithTime)} <br /> ${li.employeeCanApprove}</li>`;
            });
            //
            rows += '</ul>';
            $(this).next(".popover").find(".popover-content").html(rows);
        });
    }

    //
    function getApproverLisiting(history) {
        if (history.length == 0) return "";
        //
        let rows = "";
        let arr = [];
        //
        history.map((his) => {
            if (his.action == "create") return "";
            if (his.note == "{}") return "";
            //
            let action = JSON.parse(his.note);
            //
            if (action.canApprove == undefined) return "";
            //
            let msg = `${remakeEmployeeName(his)}`,
                il = "";
            //
            if (action.status == "pending") return "";
            if (action.status == "approved") {
                msg += ` has approved the time-off at ${moment(his.created_at).format(
                    timeoffDateFormatWithTime
                )}`;
                il = '<i class="fa fa-check-circle text-success"></i>';
            } else if (action.status == "rejected") {
                msg += ` has rejected the time-off at ${moment(his.created_at).format(
                    timeoffDateFormatWithTime
                )}`;
                il = '<i class="fa fa-times-circle text-danger"></i>';
            } else if (action.status == "cancelled" || action.status == "cancel") {
                msg += ` has cancelled the time-off at ${moment(his.created_at).format(
                    timeoffDateFormatWithTime
                )}`;
                il = '<i class="fa fa-times-circle text-danger"></i>';
            }
            //
            if ($.inArray(his.userId, arr) !== -1) return "";
            arr.push(his.userId);
            //
            rows += `
                <div class="csApproverBox" title="Approver" data-content="${msg}">
                    <img src="${his.image == null || his.image == ""
                    ? awsURL + "test_file_01.png"
                    : awsURL + his.image
                }" style="width: 40px; height: 40px;" />
                    ${il}
                </div>
            `;
        });
        //
        return rows;
    }
    //

    function getUpdateStatus(history) {
        //
        let msg = '';
        let status = 'pending';
        //
        if (history.length == 0) return status;
        //

        //
        history.map((his, i) => {
            let action = JSON.parse(his.note);

            if (his.action == 'update' && i == 0) {
                msg += `${remakeEmployeeName(his)}`;
                if (action.status == "approved") {
                    status = 'approved';
                    msg += ` has approved the time-off on ${moment(his.created_at).format(timeoffDateFormatWithTime)}`;
                } else if (action.status == "rejected") {
                    status = 'rejected';
                    msg += ` has rejected the time-off on ${moment(his.created_at).format(timeoffDateFormatWithTime)}`;
                } else if (action.status == "cancelled" || action.status == "cancel") {
                    status = 'cancelled';
                    msg += ` has cancelled the time-off on ${moment(his.created_at).format(timeoffDateFormatWithTime)}`;
                }
            }

        });

        let
            obj = {
                status: status,
                message: msg
            };
        //
        return obj;
    }

    //
    function getRequestBox(v, userRow) {
        //
        let progressStatus = v.status == "pending" ? (v.level_status != "pending" ? 50 : 0) : 100;
        let comments = getComments(v.history);
        let request_info = getUpdateStatus(v.history);
        //
        let bgStatusColor = '';
        let bgOldStatusColor = '';
        //
        if (callOBJ.Requests.Main.isMine == 0) {
            let tab_status = v.status;
            //
            if (tab_status == "pending") {
                $("#request_status_info").show();
                if (request_info.status == 'approved') {
                    bgStatusColor = 'background: rgba(129, 180, 49, .2)';
                    bgOldStatusColor = 'background: none';
                } else if (request_info.status == 'rejected') {
                    bgStatusColor = 'background: rgba(242, 222, 222, .5)';
                    bgOldStatusColor = 'background: none';
                }
            } else {
                $("#request_status_info").hide();
            }
        } else {
            $("#request_status_info").hide();
        }

        //
        let rows = '';
        //
        let expired = 0;
        let allow_update = v.allow_update;
        //
        if (callOBJ.Requests.Main.isMine == 1) {
            if (moment() > moment(v.request_from_date)) expired = 1;
            else if (v.status != 'pending' || v.level_status != 'pending') expired = 1;
        }

        rows += `<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">`;
        rows += `    <div class="csBox csShadow csRadius5 p0 jsBox" data-id="${v.sid}" data-status="${v.status}" data-userid="${v.employee_sid}" data-name="${userRow.first_name} ${userRow.last_name}" data-view="${expired}"
        >`;
        rows += `        <!-- Box Loader -->`;
        rows += `        <div class="csIPLoader jsIPLoader dn" data-page="request${v.sid}"><i class="fa fa-circle-o-notch fa-spin"></i></div>`;
        rows += `        <!-- Box Header -->`;
        rows += `        <div class="csBoxHeader csBoxHeaderBlue csRadius5 csRadiusBL0 csRadiusBR0">`;
        rows += `            <span class="pull-right">`;
        if (allow_update == "yes") {
            if (callOBJ.Requests.Main.isMine == 0) {
                rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsEditTimeOff" title="Edit"><i class="fa fa-pencil"></i></span>`;
            } else {
                if (expired == 0) rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsEditTimeOff" title="Edit"><i class="fa fa-pencil"></i></span>`;
            }
        }
        rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsHistoryTimeOff" title="Show History"><i class="fa fa-history"></i></span>`;
        rows += `                <a href="${baseURL}timeoff/print/requests/${v.sid}" target="_blank" style="color: #eee" class="csCircleBtn csRadius50 jsTooltip" title="Print"><i class="fa fa-print"></i></a>`;
        rows += `                <a href="${baseURL}timeoff/download/requests/${v.sid}" target="_blank" style="color: #eee" class="csCircleBtn csRadius50 jsTooltip" title="Download"><i class="fa fa-download"></i></a>`;
        if (allow_update == "yes") {
            if (callOBJ.Requests.Main.isMine == 0) {
                if (v.archive == 0) {
                    rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsArchiveTimeOff" title="Archive"><i class="fa fa-archive"></i></span>`;
                } else {
                    rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsActiveTimeOff" title="Activate"><i class="fa fa-sign-in"></i></span>`;
                }
            }
        }

        rows += `            </span>`;
        rows += `            <div class="clearfix"></div>`;
        rows += `        </div>`;
        rows += `        <!-- Box Content -->`;
        rows += `        <div class="csBoxContent" style="${bgStatusColor}">`;
        rows += `            <!-- Section 1 -->`;
        rows += `            <div class="csBoxContentDateSection">`;
        rows += `                <div class="col-sm-5 col-xs-5">`;
        rows += `                    <h3>${moment(v.request_from_date, timeoffDateFormatD).format(timeoffDateFormatB)}</h3>`;
        rows += `                    <p>${moment(v.request_from_date, timeoffDateFormatD).format(timeoffDateFormatBD)}, ${moment(v.request_from_date, timeoffDateFormatD).format('Y')}</p>`;
        rows += `                </div>`;
        rows += `                <div class="col-sm-2 col-xs-2 pl0 pr0">`;
        rows += `                    <strong class="text-center">`;
        rows += `                        <i class="fa fa-long-arrow-right"></i>`;
        rows += `                    </strong>`;
        rows += `                </div>`;
        rows += `                <div class="col-sm-5 col-xs-5">`;
        rows += `                    <h3>${moment(v.request_to_date, timeoffDateFormatD).format(timeoffDateFormatB)}</h3>`;
        rows += `                    <p>${moment(v.request_to_date, timeoffDateFormatD).format(timeoffDateFormatBD)}, ${moment(v.request_to_date, timeoffDateFormatD).format('Y')}</p>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        rows += `            <!-- Section 2 -->`;
        rows += `            <div class="csBoxContentInfoSection">`;
        rows += `                <div class="col-sm-12">`;
        rows += `                    <p><strong>${v.breakdown.text} of ${v.title} (<strong class="text-${v.categoryType == 1 ? 'success' : 'danger'}">${v.categoryType == 1 ? 'Paid' : 'Unpaid'}</strong>)</strong></p>`;
        rows += `                    <p>Requested on ${moment(v.created_at, timeoffDateFormatDWT).format(timeoffDateFormatWithTime)}</p>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        rows += `            <!-- Section 3 -->`;
        rows += `            <div class="csBoxContentEmpSection">`;
        rows += `                <div class="col-sm-2 col-xs-2">`;
        rows += `                    <img src="${getImageURL(userRow.image)}" class="csRoundImg"  />`;
        rows += `                </div>`;
        rows += `                <div class="col-sm-10 col-xs-10 pr0" style="padding-left: 26px;">`;
        rows += `                    <p><strong style="font-size: 20px;">${userRow.first_name} ${userRow.last_name}</strong> ${remakeEmployeeName(userRow, false)} <br>  ${userRow.anniversary_text}</p>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        rows += `            <!-- Section 4 -->`;
        rows += `            <div class="csBoxContentProgressSection" style="${bgOldStatusColor}">`;
        rows += `                <div class="col-sm-12">`;
        rows += `                    <div class="progress csRadius100">`;
        rows += `                        <div class="progress-bar progress-bar-success csRadius100" role="progressbar" aria-valuenow="${progressStatus}" aria-valuemin="0" aria-valuemax="100" style="width: ${progressStatus}% ;">`;
        rows += `                            <span> ${progressStatus}% Completed</span>`;
        rows += `                        </div>`;
        rows += `                    </div>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        if (callOBJ.Requests.Main.isMine == 0 && v.archive == 0) {
            if (comments.length > 0) {
                rows += `            <!-- Section 5 -->`;
                rows += `            <div class="csBoxContentComentSection" style="${bgOldStatusColor}">`;
                rows += `                <div class="col-sm-2 col-xs-2">`;
                rows += `                    <img src="${comments[0].employeeImage}" class="csRoundImg" />`;
                rows += `                </div>`;
                rows += `                <div class="col-sm-10 col-xs-10 pr0" style="padding-left: 26px;">`;
                rows += `                    <p class="csBoxContentComentName">${comments[0].employeeName} ${comments[0].employeeRole}</p>`;
                if (employerId == comments[0].employeeSid) {
                    rows += `                       <i class="fa fa-pencil jsEditNote" title="Edit Comment" data-empSid="${comments[0].employeeSid}" data-reqSid="${v.sid}"></i>`;
                }
                rows += `                    <p class="csBoxContentComentTag">${moment(comments[0].time, timeoffDateFormatDWT).format(timeoffDateFormatWithTime)}</p>`;
                if (comments[0].msg.length != 0) {
                    rows += `                    <div>"${strip_tags(comments[0].msg).substr(0, 25)}"</div>`;
                }
                if (comments[0].status == 'approved') {
                    rows += `                    <div class="text-success"><b>${strip_tags(comments[0].status).toUpperCase()}</b></div>`;
                } else {
                    rows += `                    <div class="text-danger"><b>${strip_tags(comments[0].status).toUpperCase()}</b></div>`;
                }
                if (allComments[v.sid] === undefined) allComments[v.sid] = [];
                allComments[v.sid].push(comments);
                rows += `                    <span class="jsCommentsPopover" title="p">`;
                rows += `                        <i class="fa fa-comment"></i>`;
                rows += `                    </span>`;
                rows += `                </div>`;
                rows += `                <div class="clearfix"></div>`;
                rows += `            </div>`;
            }
            rows += `            <!-- Section 6 -->`;
            rows += `            <div class="csBoxContentComent2Section">`;
            // rows += `                <div class="col-sm-2 col-xs-2">`;
            // rows += `                    <i class="fa fa-comment-o"></i>`;
            // rows += `                </div>`;
            rows += `                <div class="col-sm-12 col-xs-12 textarea_parent_div">`;
            rows += `                    <textarea class="form-control jsRequestCommentTxt" rows="4" placeholder="Why are you approving/rejecting this time off?"></textarea>`;
            rows += `                </div>`;
            rows += `                <div class="clearfix"></div>`;
            rows += `            </div>`;
        }
        rows += `        </div>`;
        rows += `        <!-- Box Footer -->`;
        rows += `        <div class="csBoxFooter">`;
        if (callOBJ.Requests.Main.isMine == 1) {
            if (expired == 1) {

                if (v.status == "approved" && v.level_status == "approved" && moment(v.request_from_date) > moment()) {
                    rows += `            <div class="col-sm-6">`;
                    rows += `               <button class=" btn alert-danger btn-theme form-control jsCancelTimeOffRequest"><i class="fa fa-times-circle-o"></i> Cancel Request</button>`;
                    rows += `            </div>`;
                    rows += `            <div class="col-sm-6">`;
                    rows += `                <button class="btn btn-orange btn-theme form-control jsEditTimeOff"><i class="fa fa-eye"></i> View</button>`;
                    rows += `            </div>`;
                } else {
                    rows += `            <div class="col-sm-12">`;
                    rows += `                <button class="btn btn-orange btn-theme form-control jsEditTimeOff"><i class="fa fa-eye"></i> View</button>`;
                    rows += `            </div>`;
                }
            } else if (v.status == "pending" && v.level_status == "pending" && moment(v.request_from_date) > moment()) {
                rows += `            <div class="col-sm-12">`;
                rows += `               <button class=" btn alert-danger btn-theme form-control jsCancelTimeOffRequest"><i class="fa fa-times-circle-o"></i> Cancel Request</button>`;
                rows += `            </div>`;
            }
        } else {
            //
            if (allow_update == "yes") {
                if (v.archive == 1) {
                    rows += `            <div class="col-sm-12">`;
                    rows += `                <button class="btn btn-orange form-control"><i class="fa fa-eye"></i> View</button>`;
                    rows += `            </div>`;
                } else {
                    if (v.status == 'pending') {
                        rows += `            <div class="col-sm-6 pl0 pr0">`;
                        rows += `                <button class="btn btn-orange form-control jsRequestBtn" data-type="approve"><i class="fa fa-clock-o"></i> Approve</button>`;
                        rows += `            </div>`;
                        rows += `            <div class="col-sm-6 pr0">`;
                        rows += `                <button class="btn alert-danger btn-theme form-control jsRequestBtn" data-type="reject"><i class="fa fa-times-circle-o"></i> Reject</button>`;
                        rows += `            </div>`;
                    } else if (v.status == 'approved') {
                        rows += `            <div class="col-sm-12">`;
                        rows += `                <button class="btn alert-danger btn-theme form-control jsRequestBtn" data-type="reject"><i class="fa fa-times-circle-o"></i> Reject</button>`;
                        rows += `            </div>`;
                    } else if (v.status == 'rejected') {
                        rows += `            <div class="col-sm-12">`;
                        rows += `                <button class="btn btn-orange form-control jsRequestBtn" data-type="approve"><i class="fa fa-clock-o"></i> Approve</button>`;
                        rows += `            </div>`;
                    } else if (v.status == 'cancelled') {
                        rows += `            <div class="col-sm-6 pl0 pr0">`;
                        rows += `                <button class="btn btn-orange form-control jsRequestBtn" data-type="approve"><i class="fa fa-clock-o"></i> Approve</button>`;
                        rows += `            </div>`;
                        rows += `            <div class="col-sm-6 pr0">`;
                        rows += `                <button class="btn alert-danger btn-theme form-control jsRequestBtn" data-type="reject"><i class="fa fa-times-circle-o"></i> Reject</button>`;
                        rows += `            </div>`;
                    } else {
                        rows += `            <div class="col-sm-12">`;
                        rows += `                <button class="btn btn-orange btn-theme form-control"><i class="fa fa-eye"></i> View</button>`;
                        rows += `            </div>`;
                    }
                }
            }
        }
        rows += `        </div>`;
        rows += `    </div>`;
        rows += `</div>`;
        //
        return rows;
    }

    //
    $(document).on('click', '.jsRequestBtn', function () {
        //
        let tab = callOBJ.Requests.Main.type;
        //
        let obj = {
            action: 'request_status',
            companyId: companyId,
            employerId: employeeId,
            employeeId: employeeId,
            sendEmailNotification: 1,
            requestId: $(this).closest('.jsBox').data('id'),
            status: $(this).data('type') == 'approve' ? 'approved' : 'rejected',
            comment: $(this).closest('.jsBox').find('.jsRequestCommentTxt').val()
        };

        if (tab == 'pending') {
            let request_sid = $(this).closest('.jsBox').data('id');
            let request_type = $(this).data('type');
            ml(true, `request${request_sid}`);

            let myurl = handlerURL + "/requests_status/" + companyId + "/" + request_sid + "/" + request_type;

            $.ajax({
                type: "GET",
                url: myurl,
                async: false,
                success: function (resp) {
                    ml(false, `request${request_sid}`);
                    if (resp.Status === true) {
                        if (resp.code == 1) {
                            alertify.confirm(
                                'Please Confirm',
                                resp.message,
                                function () {
                                    //
                                    sendUpdateStatusRequest(obj);
                                }, function () {
                                    // ml(true, 'editModalLoader');
                                });
                        } else if (resp.code == 2) {
                            alertify.alert(
                                'CONFLICT!',
                                resp.message,
                                function () {
                                    return true;
                                }
                            )
                        }         
                    } else {
                        //
                        sendUpdateStatusRequest(obj);
                    }
                },
                error: function (resp) {

                }
            });
        } else {
            //
            sendUpdateStatusRequest(obj);
        }
    });

    function sendUpdateStatusRequest(obj) {
        //
        ml(true, `request${obj.requestId}`);
        //
        $.post(
            handlerURL,
            obj,
            function (resp) {
                alertify.alert(
                    'SUCCESS!',
                    resp.Response,
                    function () {
                        $('.js-apply-filter-btn').click();
                        ml(false, `request${obj.requestId}`);
                    }
                )
            }
        );
    }


    //
    function getComments(history) {
        //
        let comments = [];
        //
        if (history.length == 0) return comments;
        //
        let arr = [];
        //
        history.map((his) => {
            if (his.action == "create") return;
            if (his.note == "{}") return;
            //
            let action = JSON.parse(his.note);
            //
            let approvel_rights = '';
            //
            if (action.canApprove == undefined) {
                return;
            } else {
                if (action.canApprove == 1) {
                    approvel_rights = 'Time-off approved 100%';
                } else if (action.canApprove == 0) {
                    approvel_rights = 'Time-off approved 50%';
                }
            }
            //
            let
                obj = {
                    status: 'approved',
                    msg: action.comment || '',
                    time: his.created_at,
                    employeeName: `${his.first_name} ${his.last_name}`,
                    employeeRole: remakeEmployeeName(his, false),
                    employeeImage: his.image == null || his.image == "" ? awsURL + "test_file_01.png" : awsURL + his.image,
                    employeeCanApprove: approvel_rights,
                    employeeSid: his.userId
                };
            //
            if (action.status == "pending") return;
            if (action.status == "approved") {
                obj.status = 'approved';
            } else if (action.status == "rejected") {
                obj.status = 'rejected';
            } else if (action.status == "cancelled" || action.status == "cancel") {
                obj.status = 'cancelled';
            }

            comments.push(obj);
        });
        //
        return comments;
    }
    //
    var RTOT = 'my';
    //
    $('.jsReport').click(function (e) {
        //
        e.preventDefault();
        //
        Modal({
            Id: "jsReportModal",
            Title: "Time-off Report",
            Loader: "jsReportModalLoader",
            Body: `<div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <div class="panel-heading col-sm-12 col-xs-12" id="tab_filter" style="background-color: #3554DC !important; color: #fff; padding-bottom: 0; padding-left: 5px;">
                            <span>
                            <a href="javascript:;" style="display: inline-block; padding: 11px" class="" id="my_tf_btn" placement="top" data-key="0" data-original-title="Show time offs for my team members">My Time-off</a>
                            </span>
                            <span>
                                <a href="javascript:;" style="display: inline-block; padding: 11px" class="" id="all_tf_btn" placement="top" data-key="0" data-original-title="Show time offs for my team members">All Time-off</a>
                            </span>
                            <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <form action="" method="GET" id="form_filter">
                                <div class="form-group" id="filter_employees_section">
                                    <label>Employee(s)</label>
                                    <select multiple="true" name="employees" id="filter_employees">
                                        
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group" id="filter_departments_section">
                                    <label>Department(s)</label>
                                    <select multiple="true" name="departments" id="filter_departments">
                                        
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group" id="filter_teams_section">
                                    <label>Team(s)</label>
                                    <select multiple="true" name="teams" id="filter_teams">
                                        
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group" id="filter_jobtitle_section">
                                    <label>Job Title(s)</label>
                                    <select id="jsJobTitles" multiple="true">
                                    
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group" id="filter_employeetype_section">
                                    <label>Employment Type(s)</label>
                                    <select id="jsEmploymentTypes" multiple="true">
                                        <option value="fulltime">Full-time</option>
                                        <option value="parttime">Part-time</option>
                                        <option value="contractual">Contractual</option>
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" id="jsReportStartDate" name="startDate" class="form-control" readonly />
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="text" id="jsReportEndDate" name="endDate" class="form-control" readonly />
                                </div>
                                <input type="hidden" name="user_allow" id="user_allow">
                                <input type="hidden" name="request_type" id="request_type">
                                <input type="hidden" name="request_token" id="request_token">
                                <div class="form-group">
                                    <button class="btn btn-success form-control jsGetReport" data-href="${baseURL + 'timeoff/get_report/' + (employeeId) + ''}">Apply Filter</button>
                                </div>
                            </form>    
                            <!--  -->
                            <div class="form-group">
                                <a href="<?php echo base_url('timeoff/report'); ?>" class="btn btn-black form-control">Clear Filter</a>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-12">
                            <span class="pull-right">
                                <button class="btn btn-success jsReportLink" data-href="${baseURL + 'timeoff/report/print/all'}"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</button>
                                <button class="btn btn-success jsReportLink" data-href="${baseURL + 'timeoff/report/download/all'}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download</button>
                            </span>
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed">
                                    <caption></caption>
                                    <thead>
                                        <tr style="background: #444444; color:#fff;">
                                            <th scope="col">Employee Name</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Team</th>
                                            <th scope="col"># of Requests</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="timeoff_container"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`
        }, function () {
            var date = new Date();
            var y = date.getFullYear();
            var m = date.getMonth();
            var firstDay = new Date(y, m, 1);
            var lastDay = new Date(y, m + 1, 0);
            var type = $('.jsReport').attr('data-action');
            var enable = {
                backgroundColor: "#fd7a2a",
                color: "#fff"
            };

            var disable = {
                backgroundColor: "#fff",
                color: "#000"
            };

            get_user_access_level();


            $('#filter_employees').select2({ closeOnSelect: false });
            $('#filter_departments').select2({ closeOnSelect: false });
            $('#filter_teams').select2({ closeOnSelect: false });
            $('#jsJobTitles').select2({ closeOnSelect: false });
            $('#jsEmploymentTypes').select2({ closeOnSelect: false });

            $("#my_tf_btn").css(enable);
            $("#all_tf_btn").css(disable);
            $("#request_type").val('my');
            //
            $('#filter_employees_section').hide();
            $('#filter_departments_section').hide();
            $('#filter_teams_section').hide();
            $('#filter_jobtitle_section').hide();
            $('#filter_employeetype_section').hide();

            $("#jsReportStartDate").val($.datepicker.formatDate('mm/dd/yy', firstDay));
            $("#jsReportEndDate").val($.datepicker.formatDate('mm/dd/yy', lastDay));
            //
            ml(false, 'jsReportModalLoader');
            //
            $('#jsReportStartDate').datepicker({
                format: 'm/d/y',
                changeMonth: true,
                changeYear: true,
                onSelect: function (d) {
                    $('#jsReportEndDate').datepicker('option', 'minDate', d);
                }
            });
            //
            $("#all_tf_btn").on("click", function () {
                $("#request_type").val('all');
                $("#my_tf_btn").css(disable);
                $("#all_tf_btn").css(enable);
                RTOT = 'all';

                $('#filter_employees_section').show();
                $('#filter_departments_section').show();
                $('#filter_teams_section').show();
                $('#filter_jobtitle_section').show();
                $('#filter_employeetype_section').show();
                get_timeoff_report()
            });

            $("#my_tf_btn").on("click", function () {
                RTOT = 'my';
                $("#request_type").val('my');
                $("#my_tf_btn").css(enable);
                $("#all_tf_btn").css(disable);

                $('#filter_employees_section').hide();
                $('#filter_departments_section').hide();
                $('#filter_teams_section').hide();
                $('#filter_jobtitle_section').hide();
                $('#filter_employeetype_section').hide();
                get_timeoff_report()
            });
            //
            $('#jsReportEndDate').datepicker({
                format: 'm/d/y',
                changeMonth: true,
                changeYear: true
            });
            //
            $(document).on('click', '.timeoff_count', function () {
                $('.' + $(this).data('id')).toggle();
            });
            //
            $(".jsGetReport").click(function (c) {
                //
                c.preventDefault();
                let startDate = $('#jsReportStartDate').val() || 'all',
                    endDate = $('#jsReportEndDate').val() || 'all';
                //
                ml(true, 'jsReportModalLoader');
                //
                var URL = $(this).data('href');
                var formData = $("#form_filter").serialize();
                //
                $.ajax({
                    url: URL,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'GET',
                    data: formData,
                    success: function (resp) {
                        //
                        $('#request_token').val(resp.session_token);
                        $('#timeoff_container').html(resp.modal)
                        //
                        ml(false, 'jsReportModalLoader');
                    },
                    error: function () {
                    }
                });
            });
            //
            $('.jsReportLink').click(function (c) {
                //
                c.preventDefault();
                let startDate = $('#jsReportStartDate').val() || 'all',
                    endDate = $('#jsReportEndDate').val() || 'all',
                    token = $('#request_token').val();
                //
                window.open($(this).data('href') + '?start=' + (startDate) + '&end=' + (endDate) + '&token=' + (token));
            });
        });
    });

    function get_user_access_level() {
        ml(true, 'jsReportModalLoader');
        var my_url = baseURL + 'timeoff/get_employee_status/' + (employeeId);

        $.ajax({
            url: my_url,
            cache: false,
            contentType: false,
            processData: false,
            type: 'get',
            success: function (resp) {
                ml(false, 'jsReportModalLoader');
                //

                if (resp.allow_access == 'no') {
                    $('#filter_employees_section').hide();
                    $('#filter_departments_section').hide();
                    $('#filter_teams_section').hide();
                    $('#filter_jobtitle_section').hide();
                    $('#filter_employeetype_section').hide();
                    $('#user_allow').val('no');
                    $('#tab_filter').hide();

                } else {
                    $('#filter_employees').html(resp.employee);
                    $('#filter_departments').html(resp.department);
                    $('#filter_teams').html(resp.team);

                    $('#user_allow').val('yes');
                    $('#tab_filter').show();
                }

                get_timeoff_report();
            },
            error: function () {
            }
        });
    }

    function get_timeoff_report() {
        //
        ml(true, 'jsReportModalLoader');
        //
        var URL = baseURL + 'timeoff/get_report/' + (employeeId);;
        var formData = $("#form_filter").serialize();
        //
        $.ajax({
            url: URL,
            cache: false,
            contentType: false,
            processData: false,
            type: 'GET',
            data: formData,
            success: function (resp) {
                ml(false, 'jsReportModalLoader');
                //
                $('#timeoff_container').html(resp.modal);
                $('#request_token').val(resp.session_token);
                //
                if (RTOT == 'my') {
                    console.log(RTOT);
                    $("tr").filter(function () {
                        return this.className.match(/timeoff_/);
                    }).show();
                }
                //
                if (resp.main_action_button == 'no') {
                    $('.jsReportLink').hide();
                }
            },
            error: function () {
            }
        });
    }

    //
    $(document).on('click', '.jsEditNote', function () {
        let request_sid = $(this).closest('.jsBox').data('id');
        let employeeSid = $(this).attr('data-empSid');
        let obj = {
            action: 'get_employee_note',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeSid,
            requestId: request_sid
        };
        //
        $.post(
            handlerURL,
            obj,
            function (resp) {
                $("#jsRequestSid").val(request_sid);
                $("#jsEmployeeSid").val(employeeSid);
                $("#jsNoteSection").val(resp.Comment);
                //
                if (window.location.pathname.match(/lms/ig) !== null) {
                    $('#document_modal_title').css('color', '#fff')
                }
                $("#jsAddNoteModal").modal("show");
            }
        );

    });

    //
    $(document).on('click', '#jsSaveEmployeeNote', function () {
        //
        $('#jsAddNoteModalLoader').show();
        // 
        if ($("#jsNoteSection").val().trim().length == 0) {
            alertify.alert("Notice", "Comment is required!")
        } else {
            $('#jsSaveEmployeeNote').prop('disabled', true);
            //
            let obj = {
                action: 'update_employee_note',
                companyId: companyId,
                employerId: employerId,
                employeeId: $("#jsEmployeeSid").val(),
                requestId: $("#jsRequestSid").val(),
                comment: $("#jsNoteSection").val()
            };
            //
            $.post(
                handlerURL,
                obj,
                function (resp) {
                    $('#jsAddNoteModalLoader').hide();
                    $('#jsSaveEmployeeNote').prop('disabled', false);
                    alertify.alert(
                        'SUCCESS!',
                        resp.Response,
                        function () {
                            $("#jsAddNoteModal").modal('hide');
                            fetchTimeOffs();
                        }
                    )
                }
            );
        }
    });
});