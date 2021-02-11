

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
                        <div class="row mg-lr-0">
                            <div class="border-top-section border-bottom">
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-left pl0">
                                        <p>Time Off Holidays</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right text-right">
                                        <a id="js-add-btn" href="javascript:void(0)" class="dashboard-link-btn2">
                                            <span><i class="fa fa-plus"></i></span>&nbsp; ADD HOLIDAY
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mg-lr-0">
                            <div class="pto-tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active btn-active js-tab" data-type="active"><a data-toggle="tab">Active</a></li>
                                    <li class="tab-btn js-tab" data-type="archived"><a data-toggle="tab">Deactivated</a></li>
                                    <button id="btn_apply_filter" type="button" class="btn btn-apply-filter">FILTER</button>
                                </ul>
                                <div class="filter-content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    <div class="form-group">
                                                        <label>Year</label>
                                                        <div>
                                                            <select class="invoice-fields" id="js-filter-years">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <div class="btn-filter-reset" style="float: none; margin: auto;">
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
                        </div>

                        <!-- Active Tab -->
                        <div class="active-content">
                            <!-- Pagination Top -->
                            <div class="js-ip-pagination"></div>
                            <div class="row">
                                <div class="col-lg-12 table-responsive">
                                    <table class="table table-bordereds table-striped table-condensed pto-policy-table">
                                        <thead class="heading-grey js-table-head">
                                            <tr>
                                                <th scope="col">Holiday</th>
                                                <th scope="col">Day</th>
                                                <th scope="col">Year</th>
                                                <th scope="col">Created By</th>
                                                <th scope="col">Created At</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="js-data-area">
                                            <tr class="js-error-row"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Pagination Bottom -->
                            <div class="js-ip-pagination"></div>
                        </div>
                    </div>

                    <!-- Add Page--->
                    <div class="right-content-2 js-page" id="js-page-add" <?=$page != 'add' ? 'style="display: none;"' : '';?>>
                        <div class="js-top-bar">
                            <div class="row mg-lr-0">
                                <div class="border-top-section border-bottom">
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-left">
                                            <p>Add HOLIDAY</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-right-add-plan text-right">
                                            <div class="btn-filter-reset">
                                                <button type="button" class="btn btn-reset js-view-page-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW HOLIDAYS</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-content-area">
                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Year <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" id="js-year-add"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Holiday <span class="cs-required">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="js-holiday-add" />
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Holiday Icon</label>
                                    <div>
                                        <div class="cs-icon-box hidden" id="js-icon-plc-box-add" style="width: 50px;">
                                            <img src="" id="js-icon-plc-add" />
                                            <span style="position: absolute; left: 80px; top: 40px; color: #cc1100;" id="js-icon-remove-add"><i class="fa fa-close"></i></span>
                                        </div>
                                        <input type="hidden" id="js-holiday-icon-add" />
                                        <button class="btn btn-success" id="js-icon-add"> Choose Icon</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-3 col-xs-12">
                                    <label>Start Date <span class="cs-required">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="js-from-date-add" readonly="true" />
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <label>End Date <span class="cs-required">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="js-to-date-add" readonly="true" />
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top hidden">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Frequency</label>
                                    <div class="">
                                        <select id="js-frequency-add">
                                            <option value="yearly">Yearly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top hidden">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Sort Order</label>
                                    <div class="">
                                        <input type="text" class="form-control" id="js-sort-order-add" value="1" />
                                    </div>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-archive-check-add" />
                                            Deactivate
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="bottom-section-pto">
                                        <div class="btn-button-section">
                                            <button id="js-save-add-btn" type="button" class="btn btn-save">APPLY</button>
                                            <button type="button" class="btn btn-cancel js-view-page-btn">CANCEL</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Page--->
                    <div class="right-content-2 js-page" id="js-page-edit" <?=$page != 'edit' && $holidaySid != null ? 'style="display: none;"' : '';?>>
                        <div class="js-top-bar">
                            <div class="row mg-lr-0">
                                <div class="border-top-section border-bottom">
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-left">
                                            <p>Edit Holiday</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-right-add-plan text-right">
                                            <div class="btn-filter-reset">
                                                <button type="button" class="btn btn-reset js-view-page-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW HOLIDAYS</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-content-area">
                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Year <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" id="js-year-edit"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Holiday <span class="cs-required">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="js-holiday-edit" />
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Holiday Icon</label>
                                    <div>
                                        <div class="cs-icon-box hidden" id="js-icon-plc-box-edit" style="width: 50px;">
                                            <img src="" id="js-icon-plc-edit" />
                                            <span style="position: absolute; left: 80px; top: 40px; color: #cc1100;" id="js-icon-remove-edit"><i class="fa fa-close"></i></span>
                                        </div>
                                        <input type="hidden" id="js-holiday-icon-edit" />
                                        <button class="btn btn-success" id="js-icon-edit"> Choose Icon</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-3 col-xs-12">
                                    <label>Start Date <span class="cs-required">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="js-from-date-edit" readonly="true" />
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <label>End Date <span class="cs-required">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="js-to-date-edit" readonly="true" />
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top hidden">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Frequency</label>
                                    <div class="">
                                        <select id="js-frequency-edit">
                                            <option value="yearly">Yearly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top hidden">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Sort Order</label>
                                    <div class="">
                                        <input type="text" class="form-control" id="js-sort-order-edit" value="1" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-archive-check-edit" />
                                            Deactivate
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="bottom-section-pto">
                                        <div class="btn-button-section">
                                            <input type="hidden" id="js-id-edit" />
                                            <button id="js-save-edit-btn" type="button" class="btn btn-save">APPLY</button>
                                            <button type="button" class="btn btn-cancel js-view-page-btn">CANCEL</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee on off for mobile -->
                    <div id="js-employee-off-box-mobile"></div>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('timeoff/loader'); ?>
<?php $this->load->view('timeoff/scripts/holiday-modal'); ?>
<?php $this->load->view('timeoff/scripts/holidays'); ?>
