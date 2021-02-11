<!-- View Page -->
<div class="js-page p10" id="js-page-view" <?=$page != 'view' ? 'style="display: none;"' : '';?>>
    <div class="row mg-lr-0">
        <div class="border-top-section border-bottom csHeader">
            <div class="col-xs-12">
                <div class="">
                    <p>Approvers</p>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="">
                    <a href="javascript:void(0)" class="btn btn-success jsMobileBTN manage_my_team">Manage Teams</a>
                    <a id="js-add-type-btn" href="javascript:void(0)" class="dashboard-link-btn2 cs-btn-add jsMobileBTN ma10 text-center" >
                        <span><i class=
                        "fa fa-plus"></i></span>&nbsp; ADD AN APPROVER
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="row mg-lr-0">
        <div class="pto-tabs">
            <ul class="nav nav-tabs">
                <li class="active btn-active js-tab" data-type="active"><a data-toggle="tab">Active</a></li>
                <li class="tab-btn js-tab" data-type="archived"><a data-toggle="tab">Deactivated</a></li>
                <button id="btn_apply_filter" type="button" class="btn btn-apply-filter"><i
                        class="fa fa-sliders"></i>FILTER</button>
            </ul>
            <div class="filter-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="">Employees</label>
                            <div class="">
                                <select class="invoice-fields" id="js-filter-employee"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="">Departments</label>
                            <div class="">
                                <select class="invoice-fields" id="js-filter-departments" multiple="true"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="">Teams</label>
                            <div class="">
                                <select class="invoice-fields" id="js-filter-teams" multiple="true"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 hidden">
                        <label class="">Status</label>
                        <div class="">
                            <select class="invoice-fields" id="js-filter-status">
                                <option value="-1">All</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="">Date From</label>
                            <div class="pto-policy-date">
                                <input type="text" readonly="" class="invoice-fields" id="js-filter-from-date" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="">Date to</label>
                        <div class="pto-policy-date">
                            <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" />
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="btn-filter-reset pull-right">
                            <br />
                            <button id="btn_apply" type="button"
                                class="btn btn-success js-apply-filter-btn">APPLY</button>
                            <button id="btn_reset" type="button"
                                class="btn btn-black js-reset-filter-btn">RESET</button>
                        </div>
                    </div>
                </div>
            </div>
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
                                <th class="col-sm-1">Can Approve <i class="fa fa-question-circle"
                                        title="Note"
                                        data-trigger="hover"
                                        data-html="true"
                                        data-content="'Yes' means the employee can approve/reject all assigned requests. <br /> 'No' means the employee can't approve/reject all assigned requests."
                                        data-toggle="popover"></i></th>
                                <th class="col-sm-3">Department / Team</th>
                                <th class="col-sm-2">Created On</th>
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