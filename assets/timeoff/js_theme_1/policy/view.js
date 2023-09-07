// TODO
// Timeline of edit and create policy
$(function () {
    //
    let callOBJ = {
        CompanyPolicies: {
            Main: {
                action: 'get_policies_by_company',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                filter: {
                    archived: 0,
                    policy: '',
                    startDate: '',
                    endDate: '',
                    status: ''
                },
                public: 0,
                page: 1,
            },
            cb: fetchCompanyPolicies,
            limit: 0,
            count: 0,
            pages: 0,
        },
        PolicySort: {
            Main: {
                action: "update_sort_order",
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                public: 0
            }
        },
        PolicyHistory: {
            Main: {
                action: "get_policy_history",
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                public: 0
            }
        }
    },
        oldState = {},
        xhr = null;

    //
    window.timeoff.PaginationOBJ.CompanyPolicies = callOBJ.CompanyPolicies;
    window.timeoff.fetchCompanyPolicies = fetchCompanyPolicies;

    //
    if (page == '' || page == 'view') fetchCompanyPolicies();

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

    //
    $(".js-tab").click(function () {
        callOBJ.CompanyPolicies.Main.filter.archived = $(this).data('type') === 'archived' ? 1 : 0;
        fetchCompanyPolicies();
    });

    // Trigger for policy history
    $(document).on('click', '.jsPolicyHistory', function () {
        Modal({
            Id: 1,
            Title: `Policy History for ${$(this).closest('tr').data('name')}`,
            Body: getHistoryTemplate(),
            Loader: 'jsPolicyHistoryLoader'
        }, () => {
            // Fetch history
            fetchPolicyHistory($(this).closest('tr').data('id'));
        });
    });
    // Filter buttons
    $(document).on('click', '.js-apply-filter-btn', applyFilter);
    $(document).on('click', '.js-reset-filter-btn', resetFilter);

    //
    $("#js-data-area").on("sortstop", callSort);

    //
    $(document).on('click', '.js-archive-btn', function (e) {
        //
        e.preventDefault();
        //
        var _this = $(this);
        //
        alertify.confirm('Do you really want to deactivate this policy?', function () {
            //
            var post = {};
            post.action = 'archive_company_policy';
            post.companyId = companyId;
            post.employeeId = employeeId;
            post.employerId = employerId;
            post.public = 0;
            post.policySid = _this.closest('tr').data('id');
            //
            ml(true, 'policy');
            //
            $.post(handlerURL, post, function (resp) {
                //
                ml(false, 'policy');
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
                    alertify.alert('WARNING!', resp.Response, function () { return; });
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function () { loadViewPage(); });
                return;
            });
        }).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        }).setHeader('CONFIRM!');
    });

    //
    $(document).on('click', '.js-activate-btn', function (e) {
        //
        e.preventDefault();
        //
        var _this = $(this);
        //
        alertify.confirm('Do you really want to activate this policy?', function () {
            var post = {};
            post.action = 'activate_company_policy';
            post.companyId = companyId;
            post.employeeId = employeeId;
            post.employerId = employerId;
            post.public = 0;
            post.policySid = _this.closest('tr').data('id');
            //
            ml(true, 'policy');
            //
            $.post(handlerURL, post, function (resp) {
                //
                ml(false, 'policy');
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
                    alertify.alert('WARNING!', resp.Response, function () { return; });
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function () { loadViewPage(); });
            });
        }).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        }).setHeader('CONFIRM!');
    });

    //
    function resetFilter(e) {
        //
        e.preventDefault();
        //
        $('#js-filter-policies').select2('val', 'all');
        $('#js-filter-from-date').val('');
        $('#js-filter-to-date').val('');
        $('#js-filter-status option[value="-1"]').prop('selected', true);
        //
        callOBJ.CompanyPolicies.Main.filter.policy = 'all';
        callOBJ.CompanyPolicies.Main.filter.startDate = '';
        callOBJ.CompanyPolicies.Main.filter.endDate = '';
        callOBJ.CompanyPolicies.Main.filter.status = '-1';
        callOBJ.CompanyPolicies.Main.filter.archived = 0;
        callOBJ.CompanyPolicies.Main.page = 1;
        callOBJ.CompanyPolicies.Main.limit = 0;
        callOBJ.CompanyPolicies.Main.count = 0;
        //
        fetchCompanyPolicies();
    }

    //
    function applyFilter(e) {
        //
        e.preventDefault();
        //
        callOBJ.CompanyPolicies.Main.filter.policy = $('#js-filter-policies').val();
        callOBJ.CompanyPolicies.Main.filter.startDate = $('#js-filter-from-date').val();
        callOBJ.CompanyPolicies.Main.filter.endDate = $('#js-filter-to-date').val();
        callOBJ.CompanyPolicies.Main.filter.status = $('#js-filter-status').val();
        callOBJ.CompanyPolicies.Main.page = 1;
        callOBJ.CompanyPolicies.Main.limit = 0;
        callOBJ.CompanyPolicies.Main.count = 0;
        //
        fetchCompanyPolicies();
    }

    // Fetch plans
    function fetchCompanyPolicies() {
        //
        if (window.timeoff.categories === undefined) {
            setTimeout(() => {
                fetchCompanyPolicies();
            }, 1000);
            return;
        }
        //
        if (xhr != null) return;
        //
        ml(true, 'policy');
        //
        $('.js-error-row').remove();
        //
        xhr = $.post(handlerURL, callOBJ.CompanyPolicies.Main, function (resp) {
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
            if (resp.Status === false && callOBJ.CompanyPolicies.Main.page == 1) {
                $('.js-ip-pagination').html('');
                $('#js-data-area').html(`<tr class="js-error-row"><td colspan="${$('.js-table-head').find('th').length}"><p class="alert alert-info text-center">${resp.Response}</p></td></tr>`);
                //
                ml(false, 'policy');
                //
                return;
            }
            //
            if (resp.Status === false) {
                //
                $('.js-ip-pagination').html('');
                //
                ml(false, 'policy');
                //
                return;
            }
            //
            if (callOBJ.CompanyPolicies.Main.page == 1) {
                callOBJ.CompanyPolicies.limit = resp.Limit;
                callOBJ.CompanyPolicies.count = resp.TotalRecords;
                callOBJ.CompanyPolicies.pages = resp.TotalPages;
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
        let title = callOBJ.CompanyPolicies.Main.filter.archived != 0 ? 'Activate Policy' : 'Deactivate Policy',
            icon = callOBJ.CompanyPolicies.Main.filter.archived != 0 ? 'fa-check-square-o' : 'fa-archive',
            cl = callOBJ.CompanyPolicies.Main.filter.archived != 0 ? 'js-activate-btn' : 'js-archive-btn',
            rows = '';
        //
        if (resp.Data.length == 0) return;
        //
       
        $.each(resp.Data, function (i, v) {
            oldState[v.policy_id] = i;
            //
            let accruals = JSON.parse(v.accruals);
            //
            rows += `<tr class="jsDragAble" data-id="${v.policy_id}" data-name="${v.policy_title}">`;
            rows += `    <td>`;
            rows += `        <div class="text">`;
            rows += `            <p> ${ucwords(v.policy_title)}  (<strong class="text-${v.category_type == 1 ? "success" : "danger"}">${v.category_type == 1 ? "Paid" : "Unpaid"}</strong>)<br /> (<b>${getTypeNames(v.type_sid)}</b>)</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            rows += `        <div class="text">`;
            rows += `            <p class="js-type-popovers">${getAccrualText(accruals)}</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            rows += `        <div class="text">`;
            rows += `            <p> ${getAccrualText(accruals, true)}</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            rows += `        <div class="text">`;
            rows += `            <p class="js-type-popovers">${accruals.applicableDate === null || accruals.applicableDate == '' || accruals.applicableDate == 0 ? "Joining Date" : moment(accruals.applicableDate, '').format(timeoffDateFormat)}</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            rows += `        <div class="text">`;
            rows += `            <p class="cs-status-text ${accruals.carryOverCheck == 'yes' ? 'cs-success' : 'cs-danger'}">${accruals.carryOverCheck == 'yes' ? 'Yes' : 'No'}</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            if (v.default_policy == 0) {
                rows += `        <div class="action-employee">`;
                if (resp.CompanyTimeoffPoliciesStatus==1){
                rows += `            <a href="javascript:void(0)" class="action-edit js-edit-row-btn"><i class="fa fa-pencil-square-o fa-fw icon_blue" data-toggle="tooltip" title="Edit policy"></i></a>`;
                }
                rows += `            <a href="javascript:void(0)" class="action-activate custom-tooltip jsPolicyHistory"><i class="fa fa-history fa-fw" data-toggle="tooltip" title="View history"></i></a>`;
                rows += `            <a href="javascript:void(0)" class="action-activate custom-tooltip ${cl}"><i class="fa ${icon} fa-fw" data-toggle="tooltip" title="${title}"></i></a>`;
                rows += `        </div>`;
            } else {
                rows += '<p>-</p>';
            }
            rows += `    </td>`;
            rows += `</tr>`;
        });

        //
        load_pagination(
            callOBJ.CompanyPolicies.limit,
            5,
            $('.js-ip-pagination'),
            'CompanyPolicies'
        );
        //
        $('#js-data-area').html(rows);

        //
        $('.js-type-popover').popover({
            html: true,
            trigger: 'hover'
        });
        //
        callDrager("#js-data-area");
        //
        $('[data-toggle="tooltip"]').tooltip({ placement: 'top' });
        //
        ml(false, 'policy');
    }

    // D&D
    function callDrager(target) {
        $(target).sortable({
            placeholder: "ui-state-highlight"
        });
        $(target).disableSelection();
    }

    //
    function callSort() {
        var
            i = 1,
            s = {},
            l = $('#js-data-area').find('tr').length;
        for (i; i <= l; i++) {
            s[$('#js-data-area').find('tr:nth-child(' + (i) + ')').data('id')] = i;
        }
        updateSortInDb(s);
    }

    //
    function updateSortInDb(s) {
        //
        let o = Object.assign({},
            callOBJ.PolicySort.Main, {
            sort: s,
            type: 'policies'
        }
        );
        //
        $.post(handlerURL, o, (resp) => {
            ml(false, 'policy');
        });
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
                            <th>Action Type</th>
                            <th>Action Taken</th>
                        </tr>
                    </thead>
                    <tbody id="jsPolicyHistoryTable"></tbody>
                </table>
            </div>
        `;


        return html;
    }

    //
    function fetchPolicyHistory(
        policyId
    ) {
        //
        ml(true, 'jsPolicyHistoryLoader');
        //
        $('#jsPolicyHistoryTable').html('');
        //
        xhr = $.post(handlerURL, Object.assign(callOBJ.PolicyHistory.Main, {
            policyId: policyId
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
                alertify.alert('WARNING!', resp.Response, () => { });
                //
                ml(false, 'jsPolicyHistoryLoader');
                //
                return;
            }
            //
            let rows = '';
            //
            if (resp.Data.length === 0) {
                rows = `
                    <tr>
                        <td colspan="4">
                            <p class="alert alert-info text-center">${resp.Response}</p>
                        </td>
                    </tr>
                `;
            } else {
                resp.Data.map((v) => {
                    rows += `
                        <tr>
                            <td>${remakeEmployeeName(v)}</td>
                            <td>${v.action.toUpperCase()}</td>
                            <td>${v.action_type.toUpperCase()}</td>
                            <td>${moment(v.created_at).format(timeoffDateFormatWithTime)}</td>
                        </tr>
                    `;
                });
            }

            //
            $('#jsPolicyHistoryTable').html(rows);
            //
            ml(false, 'jsPolicyHistoryLoader');
        });
    }

});