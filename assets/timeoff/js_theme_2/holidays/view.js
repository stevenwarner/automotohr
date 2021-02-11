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
                                years: moment().format('YYYY')
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
                //
                $('.js-tab').parent().removeClass('active');
                $(this).parent().addClass('active');
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
                    post.holidayId = _this.closest('.jsBox').data('id');
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
                    post.holidayId = _this.closest('.jsBox').data('id');
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
                    Title: `Holiday History for ${$(this).closest('.jsBox').data('name')}`,
                    Body: getHolidayTemplate(),
                    Loader: 'jsHolidayHistoryLoader'
                }, () => {
                    // Fetch history
                    fetchHolidayHistory($(this).closest('.jsBox').data('id'));
                });
            });

            //
            function resetFilter(e) {
                //
                e.preventDefault();
                //
                $('#js-filter-years option[value="' + (moment().format('YYYY')) + '"]').prop('selected', true);
                //
                callOBJ.CompanyHolidays.Main.filter.years = moment().format('YYYY');
                callOBJ.CompanyHolidays.Main.filter.archived = 0;
                callOBJ.CompanyHolidays.Main.page = 1;
                callOBJ.CompanyHolidays.Main.limit = 0;
                callOBJ.CompanyHolidays.Main.count = 0;
                //
                $(".js-tab[data-type='active']").click();
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
                        $('.csLisitingArea').html(`<p class="alert alert-info text-center">${resp.Response}</p>`);
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
                let rows = '';
                //
                if (resp.Data.length == 0) return;
                //
                $.each(resp.Data, function(i, v) {
                    //
                    let d1 = moment(v.from_date, 'YYYY-MM-DD');
                    let d2 = moment(v.to_date, 'YYYY-MM-DD');

                    rows += getHolidayBox(v, d1, d2);
                });

                //
                load_pagination(
                    callOBJ.CompanyHolidays.limit,
                    5,
                    $('.js-ip-pagination'),
                    'CompanyHolidays'
                );

                //
                $('.csLisitingArea').html(rows);
                $('.csLisitingArea').prepend('<br />');
                $('.csLisitingArea').append('<div class="clearfix"></div>');
                //
                ml(false, 'holiday');
                //
                $('.jsTooltip').tooltip();
            }

            //
            function getHolidayTemplate() {
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
            ) {
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
                        ml(false, 'jsHolidayHistoryLoader');
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
                    $('#jsHolidayHistoryTable').html(rows);
                    //
                    ml(false, 'jsHolidayHistoryLoader');
                });
            }

            //
            function getHolidayBox(v, d1, d2) {
                //
                let title = callOBJ.CompanyHolidays.Main.filter.archived != 0 ? 'Activate Holiday' : 'Deactivate Holiday',
                    icon = callOBJ.CompanyHolidays.Main.filter.archived != 0 ? 'fa-check-square-o' : 'fa-archive',
                    cl = callOBJ.CompanyHolidays.Main.filter.archived != 0 ? 'js-activate-btn' : 'js-archive-btn';

                return `
                <div class="col-sm-3">
                    <div class="csBox csShadow csRadius5 csHShort mt10 jsBox" data-id="${v.sid}" data-name="${v.holiday_title}">
                        <!-- Box Header -->
                        <div class="csBoxHeader csRadius5 csRadiusBL0 csRadiusBR0">
                            <span class="pull-right">
                                <span class="csCircleBtn csRadius50 jsTooltip js-edit-row-btn" title="Edit history" placement="top"><i
                                        class="fa fa-pencil"></i></span>
                                <span class="csCircleBtn csRadius50 jsTooltip jsHolidayHistory" title="View history"
                                    placement="top"><i class="fa fa-history"></i></span>
                            </span>
                            <div class="clearfix"></div>
                        </div>
                        <!-- Box Content -->
                        <div class="csBoxContent">
                            <!-- Section 1 -->
                            <div class="csBoxContentDateSection pt10 pb10">
                                <div class="col-sm-5">
                                    <h3>${d1.format(timeoffDateFormatB)}</h3>
                                    <p>${d1.format(timeoffDateFormatBD)}</p>
                                </div>
                                <div class="col-sm-2 pl0 pr0">
                                    <strong class="text-center">
                                        <i class="fa fa-long-arrow-right"></i>
                                    </strong>
                                </div>
                                <div class="col-sm-5">
                                    <h3>${d2.format(timeoffDateFormatB)}</h3>
                                    <p>${d2.format(timeoffDateFormatBD)}</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- Section 2 -->
                            <div class="csBoxContentHolidaySection">
                                <div class="col-sm-12">
                                    <p>
                                        <strong>${ucwords(v.holiday_title)}</strong> 
                                        <span class="pull-right">${v.icon != '' && v.icon !== null && v.icon != 0 ? `<img style="padding: 0; height: 30px;" src="${baseURL}assets/images/holidays/${v.icon}" />` : ''}
                                        </span>
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- Section 3 -->
                            <div class="csBoxBalanceSection">
                                <div class="col-sm-12">
                                    <p><strong>${v.holiday_year}</strong></p>
                                    <p>Year</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- Section 4 -->
                            <div class="csBoxBalanceSection">
                                <div class="col-sm-12">
                                    <p><strong>${ moment(v.created_at, '').format(timeoffDateFormatWithTime) }</strong></p>
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