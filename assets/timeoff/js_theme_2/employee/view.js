$(function () {
    //
    let callOBJ = {
        Requests: {
            Main: {
                action: 'get_requests',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                level: level,
                isMine: 1,
                type: 'pending',
                filter: {
                    employees: 'all',
                    policies: 'all',
                    status: 'all',
                    order: 'upcoming',
                    startDate: '',
                    endDate: ''
                },
                public: 0,
            }
        }
    },
        xhr = null;
    //
    $('#js-filter-status').select2();
    $('#js-filter-sort').select2({
        minimumResultsForSearch: -1
    });
    //
    // fetchTimeOffs();
    // $('.jsReportTab[data-key="pending"]').trigger('click');

    // Set Filter
    //
    $('#js-filter-from-date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
        onSelect: function (v) { $('#js-filter-to-date').datepicker('option', 'minDate', v); }
    });

    //
    $('#js-filter-to-date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
    }).datepicker('option', 'minDate', $('#js-filter-from-date').val());

    // Filter buttons
    $(document).on('click', '.js-apply-filter-btn', applyFilter);
    $(document).on('click', '.js-reset-filter-btn', resetFilter);
    $(document).on('change', '.jsEditResetCheckbox', applyFilter);
    $(document).on('change', '#js-filter-sort', applyFilter);
    //
    $('.jsReportTab').click(function (e) {
        //
        e.preventDefault();
        //
        callOBJ.Requests.Main.type = $(this).data('key');
        //
        //
        $(".jsReportTab").parent().removeClass("active").removeClass('csActiveTab');
        $(this).parent().addClass("active").addClass('csActiveTab');
        //
        fetchTimeOffs();
    });
    //

    $('.jsReportTab[data-key="pending"]').trigger('click');

    //
    function resetFilter(e) {
        //
        e.preventDefault();
        //
        $('#js-filter-employee').select2('val', 'all');
        $('#js-filter-policies').select2('val', 'all');
        $('#js-filter-status').select2('val', 'all');
        $('#js-filter-sort').select2('val', 'upcoming');
        $('#js-filter-from-date').val('');
        $('#js-filter-end-date').val('');
        //
        callOBJ.Requests.Main.filter.employees = 'all';
        callOBJ.Requests.Main.filter.policies = 'all';
        callOBJ.Requests.Main.filter.status = 'all';
        callOBJ.Requests.Main.filter.order = 'upcoming';
        callOBJ.Requests.Main.filter.startDate = '';
        callOBJ.Requests.Main.filter.endDate = '';
        callOBJ.Requests.Main.filter.isMine = 0;
        //
        fetchTimeOffs();
    }

    //
    function applyFilter(e) {
        //
        e.preventDefault();
        //
        callOBJ.Requests.Main.filter.employees = $('#js-filter-employee').val();
        callOBJ.Requests.Main.filter.policies = $('#js-filter-policies').val();
        callOBJ.Requests.Main.filter.status = $('#js-filter-status').val();
        callOBJ.Requests.Main.filter.order = $('#js-filter-sort').val();
        callOBJ.Requests.Main.filter.startDate = $('#js-filter-from-date').val();
        callOBJ.Requests.Main.filter.endDate = $('#js-filter-to-date').val();
        //
        fetchTimeOffs();
    }

    // Fetch plans
    function fetchTimeOffs() {
        //
        if (
            window.timeoff.employees === undefined
        ) {
            setTimeout(fetchTimeOffs, 1000);
            return;
        }
        //
        if (xhr != null) return;
        //
        ml(true, 'requests');
        //
        $('.js-error-row').remove();
        //
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

    let allComments = {};

    // 
    function setTable(resp) {
        //
        let rows = '';
        allComments = {};
        //
        if (resp.Data.length == 0) {
            $(".jsBoxWrap").html(`<p class="alert alert-info text-center">No time-offs found.</p>`);
            ml(false, 'requests');
            return;
        }
        //
        let sortedRequests = {};
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
        //
        $(".jsBoxWrap").html(rows);
        //
        $('.jsTooltip').tooltip({ placement: 'top' });
        //
        $(".jsCommentsPopover").popover({
            html: true,
            trigger: "hover",
            template: '<div class="popover"><div class="arrow"></div><div class="popover-content"></div></div>'
        }).on('inserted.bs.popover', function (e) {
            //
            let rows = '<ul>';
            //
            allComments[$(this).closest('.jsBox').data('id')][0].map(function (li) {
                rows += `<li><strong>${li.msg}</strong> <br /> ${li.employeeName} ${li.employeeRole} <br /> ${moment(li.time, timeoffDateFormatDWT).format(timeoffDateFormatWithTime)}</li>`;
            });
            //
            rows += '</ul>';
            $(this).next(".popover").find(".popover-content").html(rows);
        });

        //
        ml(false, 'requests');
    }

    //
    $(document).on('click', '.jsArchiveTimeOff', function (e) {
        //
        e.preventDefault();
        //
        let requestId = $(this).closest('.jsBox').data('id');
        //
        alertify.confirm('Do you really want to archive this time off?', () => {
            //
            ml(true, 'requests');
            //
            $.post(handlerURL, Object.assign({
                action: 'archive_request',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
            }, { requestId: requestId, archive: 1 }), (resp) => {
                //
                alertify.alert('SUCCESS', resp.Response, () => {
                    $('.js-apply-filter-btn').click();
                });
            })
        }, () => { });
    });

    //
    $(document).on('click', '.jsActiveTimeOff', function (e) {
        //
        e.preventDefault();
        //
        let requestId = $(this).closest('.jsBox').data('id');
        //
        alertify.confirm('Do you really want to activate this time off?', () => {
            //
            ml(true, 'requests');
            //
            $.post(handlerURL, Object.assign({
                action: 'archive_request',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
            }, { requestId: requestId, archive: 0 }), (resp) => {
                //
                alertify.alert('SUCCESS', resp.Response, () => {
                    $('.js-apply-filter-btn').click();
                });
            })
        }, () => { });
    });

    //
    $(document).on('click', '.jsHistoryTimeOff', function (e) {
        //
        e.preventDefault();
        //
        let requestId = $(this).closest('.jsBox').data('id');
        //
        Modal({
            Id: 'jsTimeOffHistory',
            Title: 'Time-off, History',
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
            Loader: 'jsTimeOffHistoryLoader'
        }, () => {
            //
            ml(true, 'jsTimeOffHistoryLoader');
            //
            $('#jsPolicyHistoryTable').html('');
            //
            $.post(handlerURL, Object.assign({
                action: 'get_request_history',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
            }, {
                requestId: requestId
            }), (resp) => {
                //
                if (resp.Redirect === true) {
                    alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                        window.location.reload();
                    });
                    return;
                }
                //
                //
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
                    alertify.alert('WARNING!', resp.Response, () => { });
                    //
                    ml(false, 'jsTimeOffHistoryLoader');
                    //
                    return;
                }
                //
                let rows = '';
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
                        let act = '',
                            comment = '-';
                        if (v.action == 'create') {
                            act = "Time-off created.";
                        } else {
                            if (note.status !== undefined) {
                                //
                                if (note.status == 'archive') act = "Time-off marked as archive";
                                else if (note.status == 'activate') act = "Time-off marked as active";
                                else if (note.status == 'approved' && note.canApprove == 1) act = "Time-off approved 100%";
                                else if (note.status == 'approved' && note.canApprove == 0) act = "Time-off approved 50%";
                                else if (note.status == 'rejected' && note.canApprove == 1) act = "Time-off rejected 100%";
                                else if (note.status == 'rejected' && note.canApprove == 0) act = "Time-off rejected 50%";
                                else if (note.status == 'pending' && note.canApprove == 0) act = "Time-off updated";
                                else if (note.status == 'cancelled') act = "Time-off cancelled";

                                //
                                if (note.comment != undefined && note.comment != '') comment = note.comment;
                            } else {
                                act = "";
                            }
                            if (v.action == 'update' && act == '') act = "Time-off updated.";
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
                $('#jsTimeOffHistoryTable').html(rows);
                //
                ml(false, 'jsTimeOffHistoryLoader');
            });
        });

    });

    //
    $(document).on('click', '.jsViewPolicies', function (e) {
        //
        e.preventDefault();
        //
        startPolicyProcess(
            employeeId,
            employeeName
        );
    });

    //
    $(document).on('click', '.jsHolidays', function (e) {
        //
        e.preventDefault();
        //
        Modal({
            Id: 'jsHolidayModal',
            Title: 'Company Holidays',
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
            Loader: 'jsHolidayModalLoader'
        }, () => {
            //
            ml(true, 'jsHolidayModalLoader');
            //
            $('#jsHolidayModalTable').html('');

            let rows = '';
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
            $('#jsHolidayModalTable').html(rows);
            //
            ml(false, 'jsHolidayModalLoader');
        });

    });

    //
    function getApproverLisiting(
        history
    ) {
        if (history.length == 0) return '';
        //
        let rows = '';
        let arr = [];
        //
        history.map((his) => {
            if (his.action == 'create') return '';
            if (his.note == '{}') return '';
            //
            let action = JSON.parse(his.note);
            //
            if (action.canApprove == undefined) return '';
            //
            let msg = `${remakeEmployeeName(his)}`,
                il = '';
            //
            if (action.status == 'pending') return '';
            if (action.status == 'approved') {
                msg += ` has approved the time-off at ${moment(his.created_at).format(timeoffDateFormatWithTime)}`;
                il = '<i class="fa fa-check-circle text-success"></i>';
            } else if (action.status == 'rejected') {
                msg += ` has rejected the time-off at ${moment(his.created_at).format(timeoffDateFormatWithTime)}`;
                il = '<i class="fa fa-times-circle text-danger"></i>';
            }
            //
            if ($.inArray(his.userId, arr) !== -1) return '';
            arr.push(his.userId);
            //
            rows += `
                <div class="csApproverBox" title="Approver" data-content="${msg}">
                    <img src="${his.image == null || his.image == '' ? awsURL + 'test_file_01.png' : awsURL + his.image}" style="width: 40px; height: 40px;" />
                    ${il}
                </div>
            `;
        });
        //
        return rows;
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
            if (action.canApprove == undefined) return;
            //
            let
                obj = {
                    status: 'approved',
                    msg: action.comment || '',
                    time: his.created_at,
                    employeeName: `${his.first_name} ${his.last_name}`,
                    employeeRole: remakeEmployeeName(his, false),
                    employeeImage: his.image == null || his.image == "" ? awsURL + "test_file_01.png" : awsURL + his.image
                };
            //
            if (action.status == "pending") return;
            if (action.status == "approved") {
                obj.status = 'approved';
            } else if (action.status == "rejected") {
                obj.status = 'rejected';
            }

            comments.push(obj);
        });
        //
        return comments;
    }

    //
    function getRequestBox(v, userRow) {
        //
        let progressStatus = v.status == "pending" ? (v.level_status != "pending" ? 50 : 0) : 100;
        let comments = getComments(v.history);
        let rows = '';
        rows += `<div class="col-sm-12">`;
        rows += `    <div class="csBox csShadow csRadius5 p0 jsBox" data-id="${v.sid}"  data-status="${v.status}" data-userid="${v.employee_sid}" data-name="${userRow.first_name} ${userRow.last_name}" >`;
        rows += `        <!-- Box Loader -->`;
        rows += `        <div class="csIPLoader jsIPLoader dn" data-page="request${v.sid}"><i class="fa fa-circle-o-notch fa-spin"></i></div>`;
        rows += `        <!-- Box Header -->`;
        rows += `        <div class="csBoxHeader csRadius5 csRadiusBL0 csRadiusBR0">`;
        rows += `            <span class="pull-right">`;
        rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsEditTimeOff" title="Edit"><i class="fa fa-pencil"></i></span>`;
        rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsHistoryTimeOff" title="Show History"><i class="fa fa-history"></i></span>`;
        rows += `                <a href="${baseURL}timeoff/print/requests/${v.sid}" target="_blank" style="color: #eee" class="csCircleBtn csRadius50 jsTooltip" title="Print"><i class="fa fa-print"></i></a>`;
        rows += `                <a href="${baseURL}timeoff/download/requests/${v.sid}" target="_blank" style="color: #eee" class="csCircleBtn csRadius50 jsTooltip" title="Download"><i class="fa fa-download"></i></a>`;
        if (v.archive == 0) {
            rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsArchiveTimeOff" title="Archive"><i class="fa fa-archive"></i></span>`;
        } else {
            rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsActiveTimeOff" title="Activate"><i class="fa fa-sign-in"></i></span>`;
        }
        rows += `            </span>`;
        rows += `            <div class="clearfix"></div>`;
        rows += `        </div>`;
        rows += `        <!-- Box Content -->`;
        rows += `        <div class="csBoxContent">`;
        rows += `            <!-- Section 1 -->`;
        rows += `            <div class="csBoxContentDateSection">`;
        rows += `                <div class="col-sm-5 col-xs-5">`;
        rows += `                    <h3>${moment(v.request_from_date, timeoffDateFormatD).format(timeoffDateFormatB)}</h3>`;
        rows += `                    <p>${moment(v.request_from_date, timeoffDateFormatD).format(timeoffDateFormatBD)}</p>`;
        rows += `                </div>`;
        rows += `                <div class="col-sm-2 col-xs-2 pl0 pr0">`;
        rows += `                    <strong class="text-center">`;
        rows += `                        <i class="fa fa-long-arrow-right"></i>`;
        rows += `                    </strong>`;
        rows += `                </div>`;
        rows += `                <div class="col-sm-5 col-xs-5">`;
        rows += `                    <h3>${moment(v.request_to_date, timeoffDateFormatD).format(timeoffDateFormatB)}</h3>`;
        rows += `                    <p>${moment(v.request_to_date, timeoffDateFormatD).format(timeoffDateFormatBD)}</p>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        rows += `            <!-- Section 2 -->`;
        rows += `            <div class="csBoxContentInfoSection">`;
        rows += `                <div class="col-sm-12">`;
        rows += `                    <p><strong>${v.breakdown.text} of ${v.title}</strong></p>`;
        rows += `                    <p>Requested on ${moment(v.created_at, timeoffDateFormatDWT).format(timeoffDateFormatWithTime)}</p>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        rows += `            <!-- Section 3 -->`;
        rows += `            <div class="csBoxContentEmpSection">`;
        rows += `                <div class="col-sm-2 col-xs-2">`;
        rows += `                    <img src="${getImageURL(userRow.image)}" class="csRoundImg"  />`;
        rows += `                </div>`;
        rows += `                <div class="col-sm-10 col-xs-10 pr0">`;
        rows += `                    <p><strong>${userRow.first_name} ${userRow.last_name}</strong> ${remakeEmployeeName(userRow, false)}</p>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        rows += `            <!-- Section 4 -->`;
        rows += `            <div class="csBoxContentProgressSection">`;
        rows += `                <div class="col-sm-12">`;
        rows += `                    <div class="progress csRadius100">`;
        rows += `                        <div class="progress-bar progress-bar-success csRadius100" role="progressbar" aria-valuenow="${progressStatus}" aria-valuemin="0" aria-valuemax="100" style="width: ${progressStatus}% ;">`;
        rows += `                            <span> ${progressStatus}% Completed</span>`;
        rows += `                        </div>`;
        rows += `                    </div>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        if (comments.length > 0) {
            rows += `            <!-- Section 5 -->`;
            rows += `            <div class="csBoxContentComentSection">`;
            rows += `                <div class="col-sm-2 col-xs-2">`;
            rows += `                    <img src="${comments[0].employeeImage}" class="csRoundImg" />`;
            rows += `                </div>`;
            rows += `                <div class="col-sm-10 col-xs-10 pr0">`;
            rows += `                    <div><strong>${strip_tags(comments[0].msg).substr(0, 25)}</strong></div>`;
            rows += `                    <p class="csBoxContentComentName">${comments[0].employeeName} ${comments[0].employeeRole}</p>`;
            rows += `                    <p class="csBoxContentComentTag">${moment(comments[0].time, timeoffDateFormatDWT).format(timeoffDateFormatWithTime)}</p>`;
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
        rows += `                <div class="col-sm-2 col-xs-2">`;
        rows += `                    <i class="fa fa-comment-o"></i>`;
        rows += `                </div>`;
        rows += `                <div class="col-sm-10 col-xs-10">`;
        rows += `                    <textarea class="form-control jsRequestCommentTxt" rows="1"></textarea>`;
        rows += `                </div>`;
        rows += `                <div class="clearfix"></div>`;
        rows += `            </div>`;
        rows += `        </div>`;
        rows += `        <!-- Box Footer -->`;
        rows += `        <div class="csBoxFooter">`;
        //
        if (v.archive == 1) {
            rows += `            <div class="col-sm-12">`;
            rows += `                <button class="btn btn-orange form-control jsEditTimeOff"><i class="fa fa-eye"></i>View</button>`;
            rows += `            </div>`;
        } else {
            if (v.status == 'pending') {
                rows += `            <div class="col-sm-6 pl0 pr0">`;
                rows += `                <button class="btn btn-orange form-control jsRequestBtn" data-type="approve"><i class="fa fa-clock-o"></i>Approve</button>`;
                rows += `            </div>`;
                rows += `            <div class="col-sm-6 pr0">`;
                rows += `                <button class="btn alert-danger btn-theme form-control jsRequestBtn" data-type="reject"><i class="fa fa-times-circle-o"></i>Reject</button>`;
                rows += `            </div>`;
            } else if (v.status == 'approved') {
                rows += `            <div class="col-sm-12">`;
                rows += `                <button class="btn alert-danger btn-theme form-control jsRequestBtn" data-type="reject"><i class="fa fa-times-circle-o"></i>Reject</button>`;
                rows += `            </div>`;
            } else if (v.status == 'rejected') {
                rows += `            <div class="col-sm-12">`;
                rows += `                <button class="btn btn-orange form-control jsRequestBtn" data-type="approve"><i class="fa fa-clock-o"></i>Approve</button>`;
                rows += `            </div>`;
            } else if (v.status == 'cancelled') {
                rows += `            <div class="col-sm-6 pl0 pr0">`;
                rows += `                <button class="btn btn-orange form-control jsRequestBtn" data-type="approve"><i class="fa fa-clock-o"></i>Approve</button>`;
                rows += `            </div>`;
                rows += `            <div class="col-sm-6 pr0">`;
                rows += `                <button class="btn alert-danger btn-theme form-control jsRequestBtn" data-type="reject"><i class="fa fa-times-circle-o"></i>Reject</button>`;
                rows += `            </div>`;
            } else {
                rows += `            <div class="col-sm-12">`;
                rows += `                <button class="btn alert-orange btn-theme form-control jsEditTimeOff"><i class="fa fa-eye"></i>View</button>`;
                rows += `            </div>`;
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
        let obj = {
            action: 'request_status',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            sendEmailNotification: 1,
            requestId: $(this).closest('.jsBox').data('id'),
            status: $(this).data('type') == 'approve' ? 'approved' : 'rejected',
            comment: $(this).closest('.jsBox').find('.jsRequestCommentTxt').val()
        };
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
    });


});