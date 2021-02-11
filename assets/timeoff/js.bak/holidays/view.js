$(function() {
            //
            let callOBJ = {
                    CompanyHolidays: {
                        Main: {
                            action: 'get_holidays_by_company',
                            companyId: companyId,
                            employerId: employerId,
                            employeeId: employeeId,
                            filter: {
                                archived: 0,
                                years: ''
                            },
                            public: 0,
                            page: 1,
                        },
                        cb: fetchCompanyHolidays,
                        limit: 0,
                        count: 0,
                        pages: 0,
                    },
                    HolidayHistory: {
                        Main: {
                            action: "get_holiday_history",
                            companyId: companyId,
                            employerId: employerId,
                            employeeId: employeeId,
                            public: 0
                        }
                    }
                },
                xhr = null;

            //
            window.timeoff.PaginationOBJ.CompanyHolidays = callOBJ.CompanyHolidays;
            window.timeoff.fetchCompanyHolidays = fetchCompanyHolidays;

            //
            if (page == '' || page == 'view') fetchCompanyHolidays();

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
                callOBJ.CompanyHolidays.Main.filter.archived = $(this).data('type') === 'archived' ? 1 : 0;
                fetchCompanyHolidays();
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
                alertify.confirm('Do you really want to deactivate this holiday?', function() {
                    //
                    var post = {};
                    post.action = 'archive_company_holiday';
                    post.companyId = companyId;
                    post.employeeId = employeeId;
                    post.employerId = employerId;
                    post.public = 0;
                    post.holidayId = _this.closest('tr').data('id');
                    //
                    ml(true, 'holiday');
                    //
                    $.post(handlerURL, post, function(resp) {
                        //
                        ml(false, 'holiday');
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
                alertify.confirm('Do you really want to activate this holiday?', function() {
                    var post = {};
                    post.action = 'activate_company_holiday';
                    post.companyId = companyId;
                    post.employeeId = employeeId;
                    post.employerId = employerId;
                    post.public = 0;
                    post.holidayId = _this.closest('tr').data('id');
                    //
                    ml(true, 'holiday');
                    //
                    $.post(handlerURL, post, function(resp) {
                        //
                        ml(false, 'holiday');
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
            $(document).on('click', '.jsHolidayHistory', function() {
                Modal({
                    Id: 1,
                    Title: `Holiday History for ${$(this).closest('tr').data('name')}`,
                    Body: getHolidayTemplate(),
                    Loader: 'jsHolidayHistoryLoader'
                }, () => {
                    // Fetch history
                    fetchHolidayHistory($(this).closest('tr').data('id'));
                });
            });

            //
            function resetFilter(e) {
                //
                e.preventDefault();
                //
                $('#js-filter-years option[value="2020"]').prop('selected', true);
                //
                callOBJ.CompanyHolidays.Main.filter.years = '2020';
                callOBJ.CompanyHolidays.Main.filter.archived = 0;
                callOBJ.CompanyHolidays.Main.page = 1;
                callOBJ.CompanyHolidays.Main.limit = 0;
                callOBJ.CompanyHolidays.Main.count = 0;
                //
                fetchCompanyHolidays();
            }

            //
            function applyFilter(e) {
                //
                e.preventDefault();
                //
                callOBJ.CompanyHolidays.Main.filter.years = $('#js-filter-year').val();
                callOBJ.CompanyHolidays.Main.page = 1;
                callOBJ.CompanyHolidays.Main.limit = 0;
                callOBJ.CompanyHolidays.Main.count = 0;
                //
                fetchCompanyHolidays();
            }

            // Fetch plans
            function fetchCompanyHolidays() {
                //
                if (xhr != null) return;
                //
                ml(true, 'holiday');
                //
                $('.js-error-row').remove();
                //
                xhr = $.post(handlerURL, callOBJ.CompanyHolidays.Main, function(resp) {
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
                    if (resp.Status === false && callOBJ.CompanyHolidays.Main.page == 1) {
                        $('.js-ip-pagination').html('');
                        $('#js-data-area').html(`<tr class="js-error-row"><td colspan="${$('.js-table-head').find('th').length}"><p class="alert alert-info text-center">${resp.Response}</p></td></tr>`);
                        //
                        ml(false, 'holiday');
                        //
                        return;
                    }
                    //
                    if (resp.Status === false) {
                        //
                        $('.js-ip-pagination').html('');
                        //
                        ml(false, 'holiday');
                        //
                        return;
                    }
                    //
                    if (callOBJ.CompanyHolidays.Main.page == 1) {
                        callOBJ.CompanyHolidays.limit = resp.Limit;
                        callOBJ.CompanyHolidays.count = resp.TotalRecords;
                        callOBJ.CompanyHolidays.pages = resp.TotalPages;
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
                let title = callOBJ.CompanyHolidays.Main.filter.archived != 0 ? 'Activate Holiday' : 'Deactivate Holiday',
                    icon = callOBJ.CompanyHolidays.Main.filter.archived != 0 ? 'fa-check-square-o' : 'fa-archive',
                    cl = callOBJ.CompanyHolidays.Main.filter.archived != 0 ? 'js-activate-btn' : 'js-archive-btn',
                    rows = '';
                //
                if (resp.Data.length == 0) return;
                //
                $.each(resp.Data, function(i, v) {
                            //
                            let d1 = moment(v.from_date, 'YYYY-MM-DD');
                            let d2 = moment(v.to_date, 'YYYY-MM-DD');
                            //
                            rows += `<tr data-id="${v.sid}" data-name="${v.holiday_title}">`;
                            rows += `    <td>`;
                            rows += `        <div class="text">`;
                            rows += `            <p> ${ucwords(v.holiday_title)}</p>`;
                            rows += `        </div>`;
                            rows += `    </td>`;
                            rows += `    <td>`;
                            rows += `        <div class="text">`;
                            rows += `            <p> ${v.icon != '' && v.icon !== null && v.icon != 0 ? `<img style="padding: 0; height: 30px;" src="${baseURL}assets/images/holidays/${v.icon}" class="cs-icon-box" />` : '-'}</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            rows += `        <div class="text">`;
            rows += `            <p> ${ucwords(v.holiday_year)}</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            rows += `        <div class="text">`;
            rows += `            <p>${d1.format(timeoffDateFormat)} ${ d1.diff(d2, 'days') > 1 ? `- ${d2.format(timeoffDateFormat)}` : ''}</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            rows += `        <div class="text">`;
            rows += `            <p class="js-type-popovers">${ v.created_at === null ? "Joining Date" : moment(v.created_at, '').format(timeoffDateFormat) }</p>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `    <td>`;
            rows += `        <div class="action-employee">`;
            rows += `            <a href="javascript:void(0)" class="action-edit js-edit-row-btn"><i class="fa fa-pencil-square-o fa-fw icon_blue" data-toggle="tooltip" title="Edit holiday"></i></a>`;
            rows += `            <a href="javascript:void(0)" class="action-activate custom-tooltip jsHolidayHistory"><i class="fa fa-history fa-fw" data-toggle="tooltip" title="View history"></i></a>`;
            rows += `            <a href="javascript:void(0)" class="action-activate custom-tooltip ${cl}"><i class="fa ${icon} fa-fw" data-toggle="tooltip" title="${ title}"></i></a>`;
            rows += `        </div>`;
            rows += `    </td>`;
            rows += `</tr>`;
        });

        //
        load_pagination(
            callOBJ.CompanyHolidays.limit,
            5,
            $('.js-ip-pagination'),
            'CompanyHolidays'
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
        ml(false, 'holiday');
    }

    //
    function getHolidayTemplate(){
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
                    <tbody id="jsHolidayHistoryTable"></tbody>
                </table>
            </div>
        `;


        return html;
    }

    //
    function fetchHolidayHistory(
        holidayId
    ){
        //
        ml(true, 'jsHolidayHistoryLoader');
        //
        $('#jsHolidayHistoryTable').html('');
        //
        xhr = $.post(handlerURL, Object.assign(callOBJ.HolidayHistory.Main, {
            holidayId: holidayId
        }), (resp) => {
            //
            xhr = null;
            //
            if(resp.Redirect === true){
                alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                    window.location.reload();
                });
                return;
            }
            //
            if(resp.Status === false){
                alertify.alert('WARNING!', resp.Response, () => {});
                //
                ml(false, 'jsHolidayHistoryLoader');
                //
                return;
            }
            //
            let rows = '';
            //
            if(resp.Data.length === 0){
                rows = `
                    <tr>
                        <td colspan="3">
                            <p class="alert alert-info text-center">${resp.Response}</p>
                        </td>
                    </tr>
                `;
            } else{
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
            $('#jsHolidayHistoryTable').html(rows);
            //
            ml(false, 'jsHolidayHistoryLoader');
        });
    }
    
});