<!-- View Page -->
<div class="js-page" id="js-page-view">
    <!-- View Page -->
    <div class="js-page " id="js-page-view" <?=$page != 'view' ? 'style="display: none;"' : '';?>>
        <div class="csPageHeader csMobileWrap">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Requests</h4>
                </div>
                <div class="col-sm-12">
                    <select id="js-filter-sort" title="Sort time-offs">
                        <option value="upcoming">Upcoming time-offs</option>
                        <option value="created_start_desc">Newest To Oldest</option>
                        <option value="created_start_asc">Oldest To Newest</option>
                    </select>
                </div>
                <div class="col-sm-12">
                    <button class="btn btn-orange form-control jsCalendarView"><i class="fa fa-calendar"></i> VIEW
                        CALENDAR</button>
                </div>
            </div>
        </div>
        <!-- Nav -->
        <div class="csPageNav">
            <div class="row">
                <div class="col-sm-12">
                    <span class="pull-right">
                        <button class="btn btn-success btn-theme jsFilterBtn" data-target="jsFilterBoxHolidays">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="csPageBody">
            <!-- Filter -->
            <div class="csBalanceBox csShadow csRadius5 jsFilterBoxHolidays dn">
                <div class="col-sm-3">
                    <label>Employees</label>
                    <select id="js-filter-employee"></select>
                </div>
                <div class="col-sm-3">
                    <label>Policy(s)</label>
                    <select id="js-filter-policies"></select>
                </div>
                <div class="col-sm-3 hidden">
                    <label>Status</label>
                    <select class="invoice-fields" id="js-filter-status">
                        <option value="all" selected="true">All</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="rejected">Rejected</option>
                        <option value="cancelled">Canceled</option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <label>From Date</label>
                    <input type="text" readonly class="invoice-fields csRadius100" id="js-filter-from-date" />
                </div>
                <div class="col-sm-3">
                    <label>To Date</label>
                    <input type="text" readonly class="invoice-fields csRadius100" id="js-filter-to-date" />
                </div>
                <div class="col-sm-12">
                    <!-- <span class="pull-right"> -->
                        <br />
                        <button id="btn_apply" type="button" class="btn btn-orange form-control js-apply-filter-btn">APPLY</button>
                        <button id="btn_reset" type="button"
                            class="btn btn-black btn-theme form-control js-reset-filter-btn">RESET</button>
                    <!-- </span> -->
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="csTabMenu">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="csMobileWrap">
                            <li class="active-tab active">
                                <a href="javascript:void(0)" data-key="pending" class="jsReportTab">Pending</a>
                            </li>
                            <li class="active-tab">
                                <a href="javascript:void(0)" data-key="approved" class="jsReportTab">Approved</a>
                            </li>
                            <li class="active-tab">
                                <a href="javascript:void(0)" data-key="rejected" class="jsReportTab">Rejected</a>
                            </li>
                            <li class="active-tab">
                                <a href="javascript:void(0)" data-key="cancelled" class="jsReportTab">Canceled</a>
                            </li>
                            <li class="active-tab jsArchiveTab">
                                <a href="javascript:void(0)" data-key="archive" class="jsReportTab">Archived</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Listing area -->
            <div class="csTabContent">
                <br />
                <div class="csLisitingArea">
                    <div class="csBoxWrap jsBoxWrap"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>