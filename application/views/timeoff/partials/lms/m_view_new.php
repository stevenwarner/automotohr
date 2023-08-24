<div class="csSperator jsRequestSeparator"></div>
<!-- View Page -->
<div class="js-page" id="js-page-view">
    <!-- Head Row -->
    <div class="row">
        <div class="csMobileWrap">
            <div class="col-xs-12 col-sm-6 jsRequestTag">
                <h4 class="csThemeHeading">Requests</h4>
            </div>
            <div class="col-xs-12 col-sm-3">
                <select id="js-filter-sort">
                    <option value="upcoming">Upcoming</option>
                    <option value="created_start_desc">Newest To Oldest</option>
                    <option value="created_start_asc">Oldest To Newest</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-3">
                <button class="btn btn-orange form-control jsCreateRequest jsCreateRequestEMP" style="height: 39px;"><i
                        class="fa fa-plus"></i> &nbsp;CREATE A REQUEST</button>
            </div>
        </div>
    </div>
    <!--  -->
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
                    <li class="active-tab jsCancelTab">
                        <a href="javascript:void(0)" data-key="cancelled" class="jsReportTab">Canceled</a>
                    </li>
                    <li class="active-tab jsArchiveTab">
                        <a href="javascript:void(0)" data-key="archive" class="jsReportTab">Archived</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="csTabContent">
        <!-- Filter -->
        <div class="csFilter hidden">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Employee</label>
                    <select class="form-control"></select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Policy</label>
                    <select class="form-control"></select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="text" class="form-control" readonly />
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>End Date</label>
                    <input type="text" class="form-control" readonly />
                </div>
            </div>
            <div class="col-sm-12">
                <span class="pull-right">
                    <button class="btn btn-success btn-theme">Reset</button>
                    <button class="btn btn-orange js-apply-filter-btn btn-theme">Apply</button>
                </span>
            </div>

            <div class="clearfix"></div>
        </div>
        <!-- Content -->
        <div class="csContentWrap p10">
            
        </div>
    </div>

</div>
<?php $this->load->view('timeoff/popups/policies'); ?>