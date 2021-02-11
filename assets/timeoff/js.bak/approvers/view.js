$(function() {
    //
    let callOBJ = {
            CompanyApprovers: {
                Main: {
                    action: 'get_approvers_by_company',
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    filter: {
                        archived: 0,
                        employees: 'all',
                        departments: [],
                        teams: [],
                        startDate: '',
                        endDate: '',
                        status: ''
                    },
                    public: 0,
                    page: 1,
                },
                cb: fetchCompanyApprovers,
                limit: 0,
                count: 0,
                pages: 0,
            },
            ApproverHistory: {
                Main: {
                    action: "get_approver_history",
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    public: 0
                }
            }
        },
        xhr = null;
    //
    window.timeoff.PaginationOBJ.CompanyApprovers = callOBJ.CompanyApprovers;
    window.timeoff.fetchCompanyApprovers = fetchCompanyApprovers;

    //
    if (page == '' || page == 'view') fetchCompanyApprovers();

    // Set Filter
    //
    $('#js-filter-from-date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
        onSelect: function(v) { $('#js-filter-to-date').datepicker('option', 'minDate', v); }
    });

    //
    $('#js-filter-to-date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
    }).datepicker('option', 'minDate', $('#js-filter-from-date').val());

    //
    $(".js-tab").click(function() {
        callOBJ.CompanyApprovers.Main.filter.archived = $(this).data('type') === 'archived' ? 1 : 0;
        fetchCompanyApprovers();
    });

    // Filter buttons
    $(document).on('click', '.js-apply-filter-btn', applyFilter);
    $(document).on('click', '.js-reset-filter-btn', resetFilter);

    //
    $(document).on('click', '.js-archive-btn', function(e) {
        //
        e.preventDefault();
        //
        var _this = $(this);
        //
        alertify.confirm('Do you really want to deactivate this approver?', function() {
            //
            var post = {};
            post.action = 'archive_company_approver';
            post.companyId = companyId;
            post.employeeId = employeeId;
            post.employerId = employerId;
            post.public = 0;
            post.approverId = _this.closest('tr').data('id');
            //
            ml(true, 'approver');
            //
            $.post(handlerURL, post, function(resp) {
                //
                ml(false, 'approver');
                //
                if (resp.Redirect === true) {
                    //
                    alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                        window.location.reload();
                    });
                    return;
                }
                //
                if (resp.Status === false) {
                    alertify.alert('WARNING!', resp.Response, function() { return; });
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function() { loadViewPage(); });
                return;
            });
        }).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        }).setHeader('CONFIRM!');
    });

    //
    $(document).on('click', '.js-activate-btn', function(e) {
        //
        e.preventDefault();
        //
        var _this = $(this);
        //
        alertify.confirm('Do you really want to activate this approver?', function() {
            var post = {};
            post.action = 'activate_company_approver';
            post.companyId = companyId;
            post.employeeId = employeeId;
            post.employerId = employerId;
            post.public = 0;
            post.approverId = _this.closest('tr').data('id');
            //
            ml(true, 'approver');
            //
            $.post(handlerURL, post, function(resp) {
                //
                ml(false, 'approver');
                //
                if (resp.Redirect === true) {
                    //
                    alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                        window.location.reload();
                    });
                    return;
                }
                //
                if (resp.Status === false) {
                    alertify.alert('WARNING!', resp.Response, function() { return; });
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function() { loadViewPage(); });
            });
        }).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        }).setHeader('CONFIRM!');
    });

    //
    // Trigger for policy history
    $(document).on('click', '.jsApproverHistory', function() {
        Modal({
            Id: 1,
            Title: `Approver History for ${$(this).closest('tr').data('name')}`,
            Body: getHistoryTemplate(),
            Loader: 'jsApproverHistoryLoader'
        }, () => {
            // Fetch history
            fetchApproverHistory($(this).closest('tr').data('id'));
        });
    });

    //
    function resetFilter(e) {
        //
        e.preventDefault();
        //
        $('#js-filter-employee').select2('val', 'all');
        $('#js-filter-departments').select2('val', '-1');
        $('#js-filter-teams').select2('val', '-1');
        $('#js-filter-from-date').val('');
        $('#js-filter-to-date').val('');
        $('#js-filter-status option[value="-1"]').prop('selected', true);
        //
        callOBJ.CompanyApprovers.Main.filter.employees = 'all';
        callOBJ.CompanyApprovers.Main.filter.departments = [];
        callOBJ.CompanyApprovers.Main.filter.teams = [];
        callOBJ.CompanyApprovers.Main.filter.startDate = '';
        callOBJ.CompanyApprovers.Main.filter.endDate = '';
        callOBJ.CompanyApprovers.Main.filter.status = '-1';
        callOBJ.CompanyApprovers.Main.filter.archived = 0;
        callOBJ.CompanyApprovers.Main.page = 1;
        callOBJ.CompanyApprovers.Main.limit = 0;
        callOBJ.CompanyApprovers.Main.count = 0;
        //
        fetchCompanyApprovers();
    }

    //
    function applyFilter(e) {
        //
        e.preventDefault();
        //
        callOBJ.CompanyApprovers.Main.filter.employees = $('#js-filter-employee').val();
        callOBJ.CompanyApprovers.Main.filter.departments = $('#js-filter-departments').val();
        callOBJ.CompanyApprovers.Main.filter.teams = $('#js-filter-teams').val();
        callOBJ.CompanyApprovers.Main.filter.startDate = $('#js-filter-from-date').val();
        callOBJ.CompanyApprovers.Main.filter.endDate = $('#js-filter-to-date').val();
        callOBJ.CompanyApprovers.Main.filter.status = $('#js-filter-status').val();
        callOBJ.CompanyApprovers.Main.page = 1;
        callOBJ.CompanyApprovers.Main.limit = 0;
        callOBJ.CompanyApprovers.Main.count = 0;
        //
        fetchCompanyApprovers();
    }

    // Fetch plans
    function fetchCompanyApprovers() {
        //
        if (
            window.timeoff.employees === undefined
        ) {
            setTimeout(() => {
                fetchCompanyApprovers();
            }, 1000);
            return;
        }
        //
        if (xhr != null) return;
        //
        ml(true, 'approver');
        //
        $('.js-error-row').remove();
        //
        xhr = $.post(handlerURL, callOBJ.CompanyApprovers.Main, function(resp) {
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
            if (resp.Status === false && callOBJ.CompanyApprovers.Main.page == 1) {
                $('.js-ip-pagination').html('');
                $('#js-data-area').html(`<tr class="js-error-row"><td colspan="${$('.js-table-head').find('th').length}"><p class="alert alert-info text-center">${resp.Response}</p></td></tr>`);
                //
                ml(false, 'approver');
                //
                return;
            }
            //
            if (resp.Status === false) {
                //
                $('.js-ip-pagination').html('');
                //
                ml(false, 'approver');
                //
                return;
            }
            //
            if (callOBJ.CompanyApprovers.Main.page == 1) {
                callOBJ.CompanyApprovers.limit = resp.Limit;
                callOBJ.CompanyApprovers.count = resp.TotalRecords;
                callOBJ.CompanyApprovers.pages = resp.TotalPages;
            }
            //
            setTable(resp);
        });
    }

    // 
    function setTable(resp) {
        //
        oldState = {};
        //
        let title = callOBJ.CompanyApprovers.Main.filter.archived != 0 ? 'Activate approver' : 'Deactivate approver',
            icon = callOBJ.CompanyApprovers.Main.filter.archived != 0 ? 'fa-check-square-o' : 'fa-archive',
            cl = callOBJ.CompanyApprovers.Main.filter.archived != 0 ? 'js-activate-btn' : 'js-archive-btn',
            rows = '';
        //
        if (resp.Data.length == 0) return;
        //
        $.each(resp.Data, function(i, v) {
            rows += `<tr data-id="${v.approver_id}" data-name="${v.first_name} ${v.last_name}">`;
            rows += '    <td scope="row">';
            rows += '        <div class="employee-info">';
            rows += '            <figure>';
            rows += `                <img src="${getImageURL(v.img)}" class="img-circle emp-image" />`;
            rows += '            </figure>';
            rows += '            <div class="text">';
            rows += `                <h4>${v.first_name} ${v.last_name}</h4>`;
            rows += `                <p>${remakeEmployeeName(v, false)}</p>`;
            rows += `                <p><a href="${baseURL}employee_profile/${v.employee_id}" target="_blank">Id: ${getEmployeeId(v.employee_id, v.employee_number)}</a></p>`;
            rows += '            </div>';
            rows += '        </div>';
            rows += '    </td>';
            rows += '    <td>';
            rows += '        <div class="text">';
            //
            rows += `            <p class="${v.approver_percentage == 1 ? 'csTxtSuccess' : 'csTxtDanger' }">${v.approver_percentage == 1 ? "Yes" : "No"}</p>`;
            rows += '        </div>';
            rows += '    </td>';
            rows += '    <td>';
            rows += '        <div class="text">';
            //
            if (v.is_department == 1)
                rows += '            <p>' + (v.department_name == null ? 'All Departments' : ucwords(v.department_name)) + '</p>';
            else
                rows += '            <p>' + (v.team_name == null ? 'All Teams' : ucwords(v.team_name)) + '</p>';
            rows += '        </div>';
            rows += '    </td>';
            rows += '    <td>';
            rows += '        <div class="text">';
            rows += '            <p>' + (moment(v.created_at, 'YYYY-MM-DD').format(timeoffDateFormat)) + '</p>';
            rows += '        </div>';
            rows += '    </td>';
            rows += `    <td>`;
            rows += `        <div class="action-employee">`;
            rows += `            <a href="javascript:void(0)" class="action-edit js-edit-row-btn"><i class="fa fa-pencil-square-o fa-fw icon_blue" data-toggle="tooltip" title="Edit approver"></i></a>`;
            rows += `            <a href="javascript:void(0)" class="action-activate custom-tooltip jsApproverHistory"><i class="fa fa-history fa-fw" data-toggle="tooltip" title="View history"></i></a>`;
            rows += `            <a href="javascript:void(0)" class="action-activate custom-tooltip ${cl}"><i class="fa ${icon} fa-fw" data-toggle="tooltip" title="${ title}"></i></a>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `</tr>`;
        });

        //
        load_pagination(
            callOBJ.CompanyApprovers.limit,
            5,
            $('.js-ip-pagination'),
            'CompanyApprovers'
        );
        //
        $('#js-data-area').html(rows);
        //
        $('[data-toggle="tooltip"]').tooltip({ placement: 'top' });
        //
        $('.js-type-popover').popover({
            html: true,
            trigger: 'hover'
        });
        //
        ml(false, 'approver');
    }

    //
    function getHistoryTemplate() {
        let html = `
            <div class="tabel-responsive">
                <table class="table table-striped csCustomTableHeader">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Action</th>
                            <th>Action Taken</th>
                        </tr>
                    </thead>
                    <tbody id="jsApproverHistoryTable"></tbody>
                </table>
            </div>
        `;


        return html;
    }

    //
    function fetchApproverHistory(
        approverId
    ) {
        //
        ml(true, 'jsApproverHistoryLoader');
        //
        $('#jsApproverHistoryTable').html('');
        //
        xhr = $.post(handlerURL, Object.assign(callOBJ.ApproverHistory.Main, {
            approverId: approverId
        }), (resp) => {
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
            if (resp.Status === false) {
                alertify.alert('WARNING!', resp.Response, () => {});
                //
                ml(false, 'jsApproverHistoryLoader');
                //
                return;
            }
            //
            let rows = '';
            //
            if (resp.Data.length === 0) {
                rows = `
                    <tr>
                        <td colspan="3">
                            <p class="alert alert-info text-center">${resp.Response}</p>
                        </td>
                    </tr>
                `;
            } else {
                resp.Data.map((v) => {
                    rows += `
                        <tr>
                            <td>${ remakeEmployeeName(v) }</td>
                            <td>${ v.action.toUpperCase() }</td>
                            <td>${ moment(v.created_at).format(timeoffDateFormatWithTime) }</td>
                        </tr>
                    `;
                });
            }

            //
            $('#jsApproverHistoryTable').html(rows);
            //
            ml(false, 'jsApproverHistoryLoader');
        });
    }

});