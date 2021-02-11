

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
                                        <p>Time Off Requests</p>
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
                                        <div class="col-lg-7">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Policies</label>
                                                        <div>
                                                            <select class="invoice-fields" name="template" id="js-filter-policies"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Employees</label>
                                                        <div>
                                                            <select class="invoice-fields" name="template" id="js-filter-employee"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Date From</label>
                                                        <div class="pto-policy-date ">
                                                            <input type="text" readonly="" class="invoice-fields" id="js-filter-from-date" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Date to</label>
                                                    <div class="pto-policy-date ">
                                                        <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Status</label>
                                                    <div>
                                                        <select class="invoice-fields" name="template" id="js-filter-status">
                                                            <option value="all">All</option>
                                                            <option value="pending">Pending</option>
                                                            <option value="approved">Approved</option>
                                                            <option value="rejected">Rejected</option>
                                                            <option value="cancelled">Cancelled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="btn-filter-reset">
                                                <button id="btn_reset" type="button" class="btn btn-reset js-reset-filter-btn">RESET</button>
                                                <button id="btn_apply" type="button" class="btn btn-apply js-apply-filter-btn">APPLY</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="pto-tabs">
                            <ul class="nav nav-tabs btn-apply-filter-custom js-request-titles">
                            </ul>
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
                                                <th scope="col">Level</th>
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
<?php $this->load->view('timeoff/scripts/edit-modal'); ?>
<?php $this->load->view('timeoff/scripts/requests'); ?>
