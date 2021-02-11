<!-- View Page -->
<div class="js-page p10" id="js-page-view">
    <div class="row mg-lr-0">
        <div class="border-top-section border-bottom csHeader">
            <div class="col-xs-12 col-sm-6">
                <div class="pto-top-heading-left pl0">
                    <p>Requests</p>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 pr0 mr0">
                <select id="js-filter-sort" title="Sort time-offs">
                    <option value="upcoming">Upcoming time-offs</option>
                    <option value="created_start_desc">Newest To Oldest</option>
                    <option value="created_start_asc">Oldest To Newest</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-2">
                <span class="pull-right">
                    <button class="cs-btn-add btn-success jsCalendarView" style="margin-top: 2px;"><i
                            class="fa fa-plus"></i> &nbsp;VIEW CALENDAR </button>
                    <button class="cs-btn-add hidden" style="margin-top: 2px;"><i class="fa fa-calendar"></i>
                        &nbsp;CREATE A TIME-OFF</button>
                </span>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>


    <div class="row mg-lr-0">
        <div class="pto-tabs cs-bl-tabs">
            <ul class="nav nav-tabs">
                <button id="btn_apply_filter" title="Filter" placement="left" type="button" class="btn btn-apply-filter"><i
                        class="fa fa-sliders"></i>FILTER</button>
            </ul>
            <div class="filter-content">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Employee(s)</label>
                            <div>
                                <select class="invoice-fields" id="js-filter-employee"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Policy(s)</label>
                            <div>
                                <select class="invoice-fields" id="js-filter-policies"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Status</label>
                            <div>
                                <select class="invoice-fields" id="js-filter-status">
                                    <option value="all" selected="true">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancelled">Canceled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="pull-right">
                            <br />
                            <button id="btn_reset" type="button"
                                class="btn btn-black js-reset-filter-btn">RESET</button>
                            <button id="btn_apply" type="button"
                                class="btn btn-success js-apply-filter-btn">APPLY</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Requests status -->
    <div class="row">
        <div class="pto-tabs">
            <ul class="nav nav-tabs nav_report_status">
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
    <!-- Active Tab -->
    <div class="active-content">
        <!-- Pagination Top -->
        <div class="js-ip-pagination mb10"></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-condensed pto-policy-table csCustomTableHeader">
                        <thead class="js-table-head">
                            <tr>
                                <th class="col-sm-3">Employee</th>
                                <th class="col-sm-3">Policy</th>
                                <th class="col-sm-2">Progress</th>
                                <th class="col-sm-1">Reason</th>
                                <th class="col-sm-2">Requested On</th>
                                <th class="col-sm-1">Action</th>
                            </tr>
                        </thead>
                        <tbody id="js-data-area">
                            <tr class="js-error-row"></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Pagination Bottom -->
        <hr />
        <div class="js-ip-pagination"></div>
    </div>
</div>