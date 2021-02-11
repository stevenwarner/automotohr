

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_pto_left_view'); ?>
                    <div id="js-employee-off-box-desktop"></div>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- View Page -->
                    <div class="right-content js-page" id="js-page-view" <?=$page != 'view' ? 'style="display: none;"' : '';?>>
                        <div class="mg-lr-0">
                            <div class="border-top-section border-bottom">
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-left pl0">
                                        <p>Time Off Request Report</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right">
                                        <button id="btn_apply_filter" type="button" class="btn btn-apply-filter">FILTER</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mg-lr-0">
                            <div class="pto-tabs">
                                <div class="filter-content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Employees</label>
                                                        <div>
                                                            <select class="invoice-fields" name="template" id="js-filter-employee"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label>Policies</label>
                                                        <div>
                                                            <select class="invoice-fields" name="template" id="js-filter-policies"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>Status</label>
                                                    <div>
                                                        <select class="invoice-fields" name="template" id="js-filter-status">
                                                            <option value="pending">Pending</option>
                                                            <option value="approved">Approved</option>
                                                            <option value="rejected">Rejected</option>
                                                            <option value="cancelled">Canceled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label>Date From</label>
                                                        <div class="pto-policy-date ">
                                                            <input type="text" readonly="" class="invoice-fields" id="js-filter-from-date" value="01-01-<?php echo date('Y') ?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>Date to</label>
                                                    <div class="pto-policy-date ">
                                                        <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" value="12-31-<?php echo date('Y') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="btn-filter-reset pull-right">
                                                        <br />
                                                        <button id="btn_reset" type="button" class="btn btn-reset js-reset-filter-btn">RESET</button>
                                                        <button id="btn_apply" type="button" class="btn btn-apply js-apply-filter-btn">APPLY</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div id="js-pending-chart" style="width: 100%;"></div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div id="js-approved-chart" style="width: 100%;"></div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div id="js-rejected-chart" style="width: 100%;"></div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div id="js-cancelled-chart" style="width: 100%;"></div>
                            </div>
                        </div>

                        <div class="pto-tabs">
                            <div class="col-sm-12 col-sm-12" style="padding-left: 0px; margin-bottom: 6px;">
                                <ul class="nav nav-tabs nav_report_status" >
                                    <li class="active active-tab">
                                        <a href="javascript:void(0)" data-key="pending" class="request_report_status">Pending</a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" data-key="approved" class="request_report_status">Approved</a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" data-key="rejected" class="request_report_status">Rejected</a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" data-key="cancelled" class="request_report_status">Canceled</a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" data-key="archive" class="request_report_status">Archived</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="pto-tabs type_and_filter_nav">
                            <div class="col-sm-9 col-sm-12">
                                <ul class="nav nav-tabs btn-apply-filter-custom js-request-titles"></ul>
                            </div>
                            <div class="col-sm-3 col-sm-12">
                                <select class="form-control js-sort-select" style="margin-top: 5px;">
                                    <optgroup label="Time Off Created Date">
                                        <option value="created_desc">Newest to Oldest</option>
                                        <option value="created_asc">Oldest to Newest</option>
                                    </optgroup>
                                    <optgroup label="Time Off Start Date">
                                        <option value="created_start_desc">Newest to Oldest</option>
                                        <option value="created_start_asc">Oldest to Newest</option>
                                    </optgroup>
                                    <optgroup label="Upcoming Time Offs">
                                        <option value="upcoming" selected="true">Upcoming</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <!-- Active Tab -->
                        <div class="active-content">
                            <!-- Pagination Top -->
                            <div class="js-ip-pagination"></div>
                            <div class="row">
                                <div class="col-lg-12 table-responsive">
                                    <table class="table table-bordereds table-striped table-condensed pto-request-table">
                                        <thead class="heading-grey js-table-head">
                                            <tr>
                                                <th scope="col">Employee</th>
                                                <th scope="col">Policy</th>
                                                <th scope="col">Progress</th>
                                                <th scope="col">Reason</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="js-data-load-area"></tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Pagination Bottom -->
                            <div class="js-ip-pagination"></div>
                        </div>
                    </div>

                    <!-- Employee on off for mobile -->
                    <div id="js-employee-off-box-mobile"></div>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('timeoff/loader'); ?>
<?php $this->load->view('timeoff/scripts/print_download'); ?>
<?php $this->load->view('timeoff/scripts/edit-modal'); ?>
<?php $this->load->view('timeoff/scripts/request_report'); ?>
