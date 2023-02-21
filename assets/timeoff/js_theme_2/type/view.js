$(function() {
    //
    let callOBJ = {
            CompanyTypes: {
                Main: {
                    action: 'get_types_by_company',
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    filter: {
                        archived: 0,
                        type: '',
                        startDate: '',
                        endDate: '',
                        status: ''
                    },
                    public: 0,
                    page: 1,
                },
                cb: fetchCompanyTypes,
                limit: 0,
                count: 0,
                pages: 0,
            },
            TypeSort: {
                Main: {
                    action: "update_sort_order",
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    public: 0
                }
            },
            TypeHistory: {
                Main: {
                    action: "get_type_history",
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
    window.timeoff.PaginationOBJ.CompanyTypes = callOBJ.CompanyTypes;
    window.timeoff.fetchCompanyTypes = fetchCompanyTypes;

    //
    if (page == '' || page == 'view') fetchCompanyTypes();

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
        callOBJ.CompanyTypes.Main.filter.archived = $(this).data('type') === 'archived' ? 1 : 0;
        //
        $('.js-tab').parent().removeClass('active');
        $(this).parent().addClass('active');
        fetchCompanyTypes();
    });

    // Filter buttons
    $(document).on('click', '.js-apply-filter-btn', applyFilter);
    $(document).on('click', '.js-reset-filter-btn', resetFilter);

    //
    $('.csLisitingArea').on("sortstop", callSort);

    //
    $(document).on('click', '.js-archive-btn', function(e) {
        //
        e.preventDefault();
        //
        var _this = $(this);
        //
        alertify.confirm('Do you really want to deactivate this type?', function() {
            //
            var post = {};
            post.action = 'archive_company_type';
            post.companyId = companyId;
            post.employeeId = employeeId;
            post.employerId = employerId;
            post.public = 0;
            post.typeId = _this.closest('.jsBox').data('id');
            //
            ml(true, 'type');
            //
            $.post(handlerURL, post, function(resp) {
                //
                ml(false, 'type');
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
        alertify.confirm('Do you really want to activate this type?', function() {
            var post = {};
            post.action = 'activate_company_type';
            post.companyId = companyId;
            post.employeeId = employeeId;
            post.employerId = employerId;
            post.public = 0;
            post.typeId = _this.closest('.jsBox').data('id');
            //
            ml(true, 'type');
            //
            $.post(handlerURL, post, function(resp) {
                //
                ml(false, 'type');
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
    $(document).on('click', '.jsTypeHistory', function() {
        Modal({
            Id: 1,
            Title: `Type History for ${$(this).closest('.jsBox').data('name')}`,
            Body: getHistoryTemplate(),
            Loader: 'jsTypeHistoryLoader'
        }, () => {
            // Fetch history
            fetchTypeyHistory($(this).closest('.jsBox').data('id'));
        });
    });

    //
    function resetFilter(e) {
        //
        e.preventDefault();
        //
        $('#js-filter-types').select2('val', '-1');
        $('#js-filter-from-date').val('');
        $('#js-filter-to-date').val('');
        $('#js-filter-status option[value="-1"]').prop('selected', true);
        //
        callOBJ.CompanyTypes.Main.filter.type = '-1';
        callOBJ.CompanyTypes.Main.filter.startDate = '';
        callOBJ.CompanyTypes.Main.filter.endDate = '';
        callOBJ.CompanyTypes.Main.filter.status = '-1';
        callOBJ.CompanyTypes.Main.filter.archived = 0;
        callOBJ.CompanyTypes.Main.page = 1;
        callOBJ.CompanyTypes.Main.limit = 0;
        callOBJ.CompanyTypes.Main.count = 0;
        //
        fetchCompanyTypes();
    }

    //
    function applyFilter(e) {
        //
        e.preventDefault();
        //
        callOBJ.CompanyTypes.Main.filter.type = $('#js-filter-types').val();
        callOBJ.CompanyTypes.Main.filter.startDate = $('#js-filter-from-date').val();
        callOBJ.CompanyTypes.Main.filter.endDate = $('#js-filter-to-date').val();
        callOBJ.CompanyTypes.Main.filter.status = $('#js-filter-status').val();
        callOBJ.CompanyTypes.Main.page = 1;
        callOBJ.CompanyTypes.Main.limit = 0;
        callOBJ.CompanyTypes.Main.count = 0;
        //
        fetchCompanyTypes();
    }

    // Fetch plans
    function fetchCompanyTypes() {
        // //
        // if( window.timeoff.categories === undefined ){
        //     setTimeout(() => {
        //         fetchCompanyTypes();
        //     }, 1000);
        //     return;
        // }
        //
        if (xhr != null) return;
        //
        ml(true, 'type');
        //
        $('.js-error-row').remove();
        //
        xhr = $.post(handlerURL, callOBJ.CompanyTypes.Main, function(resp) {
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
            if (resp.Status === false && callOBJ.CompanyTypes.Main.page == 1) {
                $('.js-ip-pagination').html('');
                $('.csLisitingArea').html(`<p class="alert alert-info text-center">${resp.Response}</p>`);
                //
                ml(false, 'type');
                //
                return;
            }
            //
            if (resp.Status === false) {
                //
                $('.js-ip-pagination').html('');
                //
                ml(false, 'type');
                //
                return;
            }
            //
            if (callOBJ.CompanyTypes.Main.page == 1) {
                callOBJ.CompanyTypes.limit = resp.Limit;
                callOBJ.CompanyTypes.count = resp.TotalRecords;
                callOBJ.CompanyTypes.pages = resp.TotalPages;
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
        let
            rows = '';
        //
        if (resp.Data.length == 0) {
            ml(false, 'type');
            $('.csLisitingArea').html(`<p class="alert alert-info text-center">${resp.Response}</p>`);
            return;
        }
        //
        $.each(resp.Data, function(i, v) {
            oldState[v.type_sid] = i;

            rows += getTypeBox(v);
        });

        //
        load_pagination(
            callOBJ.CompanyTypes.limit,
            5,
            $('.js-ip-pagination'),
            'CompanyTypes'
        );
        //
        $('.csLisitingArea').html(rows);
        //
        $('.jsTooltip').tooltip({ placement: 'top' });
        //
        callDrager(".csLisitingArea");
        //
        ml(false, 'type');
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
            i = 0,
            s = {},
            l = $('.csLisitingArea').find('.jsBox').length;
        for (i; i < l; i++) {
            s[$('.csLisitingArea').find('.jsBox:eq(' + (i) + ')').data('id')] = i;
        }
        updateSortInDb(s);
    }

    //
    function updateSortInDb(s) {
        //
        let o = Object.assign({},
            callOBJ.TypeSort.Main, {
                sort: s,
                type: 'categories'
            }
        );
        //
        $.post(handlerURL, o, (resp) => {
            ml(false, 'type');
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
                            <th>Action Taken</th>
                        </tr>
                    </thead>
                    <tbody id="jsTypeHistoryTable"></tbody>
                </table>
            </div>
        `;


        return html;
    }

    //
    function fetchTypeyHistory(
        typeId
    ) {
        //
        ml(true, 'jsTypeHistoryLoader');
        //
        $('#jsTypeHistoryTable').html('');
        //
        xhr = $.post(handlerURL, Object.assign(callOBJ.TypeHistory.Main, {
            typeId: typeId
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
                ml(false, 'jsTypeHistoryLoader');
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
            $('#jsTypeHistoryTable').html(rows);
            //
            ml(false, 'jsTypeHistoryLoader');
        });
    }


    //
    function getTypeBox(v) {
        let title = callOBJ.CompanyTypes.Main.filter.archived != 0 ? 'Activate Type' : 'Deactivate type',
            icon = callOBJ.CompanyTypes.Main.filter.archived != 0 ? 'fa-check-square-o' : 'fa-archive',
            cl = callOBJ.CompanyTypes.Main.filter.archived != 0 ? 'js-activate-btn' : 'js-archive-btn';
        return `
        <div class="col-sm-3">
            <div class="csBox csShadow csRadius5 csHShort mt10 jsBox" data-id="${v.type_sid}" data-name="${v.type_title}">
                <!-- Box Header -->
                <div class="csBoxHeader csRadius5 csRadiusBL0 csRadiusBR0">

                <span class="pull-left">
                <button class="btn btn-orange" style="font-size:14px !important;font-weight: 400 !important; margin-top: -2px !important;;">
                ${(v.category_type==1)?"Paid":"Unpaid"}
                </button>
                </span>

                    <span class="pull-right">
                        <span class="csCircleBtn csRadius50 jsTooltip js-edit-row-btn" title="Edit history" placement="top"><i
                                class="fa fa-pencil"></i></span>
                        <span class="csCircleBtn csRadius50 jsTooltip jsTypeHistory" title="View history"
                            placement="top"><i class="fa fa-history"></i></span>
                    </span>
                    <div class="clearfix"></div>
                </div>
                <!-- Box Content -->
                <div class="csBoxContent">
                    <!-- Section 1 -->
                    <div class="csBoxContentDateSection pt10 pb10">
                        <div class="col-sm-12">
                            <h3>${ucwords(v.type_title)}</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Section 3 -->
                    <div class="csBoxBalanceSection">
                        <div class="col-sm-12">
                            <p><strong>${v.policies !== null ? v.policies.join(', ') : '-'}</strong></p>
                            <p>Policies</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Section 2 -->
                    <div class="csBoxBalanceSection">
                        <div class="col-sm-12">
                            <p><strong>${moment(v.created_at, '').format(timeoffDateFormatWithTime) }</strong></p>
                            <p>Created On</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!-- Box Footer -->
                <div class="csBoxFooter">
                <button class="btn btn-orange btn-lg form-control ${cl}">
                    <i class="fa ${icon}"></i> ${title}
                </button>
                </div>
            </div>

        </div>
        `;
    }

});