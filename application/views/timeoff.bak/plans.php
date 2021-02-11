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
                                        <p>Plans</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right text-right">
                                        <a id="js-add-plan-btn" href="javascript:void(0)" class="dashboard-link-btn2">
                                            <span><i class="fa fa-plus"></i></span>&nbsp; ADD PLAN
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mg-lr-0">
                            <div class="pto-tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active btn-active js-tab" data-type="active"><a data-toggle="tab">Active</a></li>
                                    <li class="tab-btn js-tab" data-type="archived"><a data-toggle="tab">Archive</a></li>
                                    <button id="btn_apply_filter" type="button" class="btn btn-apply-filter">APPLY FILTER</button>
                                </ul>
                                <div class="filter-content">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Plan Periods</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="template" id="js-filter-plans"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Employees</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="template" id="js-filter-employee"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Date From</label>
                                                        <div class="pto-plan-date hr-select-dropdown">
                                                            <input type="text" readonly="" class="invoice-fields" id="js-filter-from-date" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="">Date to</label>
                                                    <div class="pto-plan-date hr-select-dropdown">
                                                        <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="">Status</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="template" id="js-filter-status">
                                                            <option value="-1">All</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">In-Active</option>
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

                        <!-- Active Tab -->
                        <div class="active-content">
                            <!-- Pagination Top -->
                            <div class="js-ip-pagination"></div>
                            <div class="row">
                                <div class="col-lg-12 table-responsive">
                                    <table class="table table-bordered pto-plan-table">
                                        <thead class="heading-grey">
                                            <tr>
                                                <th scope="col">Creator</th>
                                                <th scope="col">Plan</th>
                                                <th scope="col">Allowed Time-Off</th>
                                                <th scope="col">Created At</th>
                                                <th scope="col">Status</th>
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
                        <div class="row mg-lr-0">
                            <div class="border-top-section border-bottom">
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-left pl0">
                                        <p>Add Plan</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right-add-plan text-right">
                                        <div class="btn-filter-reset">
                                            <button type="button" class="btn btn-reset js-view-plan-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW PLANS</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row123 margin-custom">
                            <div class="">
                                <label>Plan Period</label>
                                <div class="">
                                    <div class="checkbox-inline" style="padding-left: 0;">
                                        <label>
                                            <input type="radio" name="plan_type" class="js-plan-type-add" value="existed" checked="true" /> Pre-existed
                                        </label>
                                    </div>
                                    <div class="checkbox-inline" style="padding-left: 0;">
                                        <label>
                                            <input type="radio" name="plan_type" class="js-plan-type-add" value="custom" /> Custom
                                        </label>
                                    </div>
                                </div>
                                <div class="hr-select-dropdown"  id="js-plan-add-select">
                                    <select class="invoice-fields" name="template" id="js-plan-period-add"></select>
                                </div>
                                <div id="js-plan-add-input" style="display: none;">
                                    <div class="input-group">
                                        <input type="number" value="" class="form-control" id="js-plan-period-add-input" />
                                        <span class="input-group-addon">Year(s)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <label class="">Status</label>
                                <div class="hr-select-dropdown">
                                    <select class="invoice-fields" name="template" id="js-status-add">
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="col-md-6 offset-md-3">
                                <br />
                                <div class="pto-allowed-years">
                                    <div class="timeline-content">
                                        <label class="">Allowed Time Off</label>
                                        <div class="row">
                                            <?php if(in_array('D', $timeOffFormat)) { ?>
                                            <div class="col-lg-4 col-sm-4 cols-xs-12">
                                                <div class="input-group pto-time-off-margin-custom">
                                                    <input type="text" class="form-control" aria-label="Amount (rounded to the nearest dollar)" id="js-allowed-days-add" />
                                                    <span class="input-group-addon">Days</span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php if(in_array('H', $timeOffFormat)) { ?>
                                            <div class="col-lg-4 col-sm-4 cols-xs-12">
                                                <div class="input-group pto-time-off-margin-custom">
                                                    <input type="text" class="form-control" aria-label="Amount (rounded to the nearest dollar)" id="js-allowed-hours-add" />
                                                    <span class="input-group-addon">Hours</span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php if(in_array('M', $timeOffFormat)) { ?>
                                            <div class="col-lg-4 col-sm-4 cols-xs-12">
                                                <div class="input-group pto-time-off-margin-custom">
                                                    <input type="text" class="form-control" aria-label="Amount (rounded to the nearest dollar)" id="js-allowed-minutes-add" />
                                                    <span class="input-group-addon">Minutes</span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 checkbox-styling margin-top">
                                <label>
                                    <input type="checkbox" class="checkbox-sizing" id="js-archived-add" /> <span>Is Archived?</span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="bottom-section-pto">
                                    <div class="btn-button-section">
                                        <button id="js-save-add-btn" type="button" class="btn btn-save">APPLY</button>
                                        <button type="button" class="btn btn-cancel js-view-plan-btn">CANCEL</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Page--->
                    <div class="right-content-2 js-page" id="js-page-edit" <?=$page != 'edit' && $planSid != null ? 'style="display: none;"' : '';?>>
                        <div class="row mg-lr-0">
                            <div class="border-top-section border-bottom">
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-left pl0">
                                        <p>Edit Plan</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right-edit-plan text-right">
                                        <div class="btn-filter-reset">
                                            <button type="button" class="btn btn-reset js-view-plan-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW PLANS</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row123 margin-custom">
                            <div class="">
                                <label>Plan Period</label>
                                <div class="">
                                    <div class="checkbox-inline" style="padding-left: 0;">
                                        <label>
                                            <input type="radio" name="plan_type" class="js-plan-type-edit" value="existed" checked="true" /> Pre-existed
                                        </label>
                                    </div>
                                    <div class="checkbox-inline" style="padding-left: 0;">
                                        <label>
                                            <input type="radio" name="plan_type" class="js-plan-type-edit" value="custom" /> Custom
                                        </label>
                                    </div>
                                </div>
                                <div class="hr-select-dropdown" id="js-plan-edit-select">
                                    <select class="invoice-fields" name="template" id="js-plan-period-edit"></select>
                                </div>
                                <div id="js-plan-edit-input" style="display: none;">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="js-plan-period-edit-input" />
                                        <span class="input-group-editon">Year(s)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <label class="">Status</label>
                                <div class="hr-select-dropdown">
                                    <select class="invoice-fields" name="template" id="js-status-edit">
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="col-md-6 offset-md-3">
                                <br />
                                <div class="pto-allowed-years">
                                    <div class="timeline-content">
                                        <label class="">Allowed Time Off</label>
                                        <div class="row">
                                            <?php if(in_array('D', $timeOffFormat)) { ?>
                                            <div class="col-lg-4 col-sm-4 cols-xs-12">
                                                <div class="input-group pto-time-off-margin-custom">
                                                    <input type="text" class="form-control" aria-label="Amount (rounded to the nearest dollar)" id="js-allowed-days-edit" />
                                                    <span class="input-group-addon">Days</span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php if(in_array('H', $timeOffFormat)) { ?>
                                            <div class="col-lg-4 col-sm-4 cols-xs-12">
                                                <div class="input-group pto-time-off-margin-custom">
                                                    <input type="text" class="form-control" aria-label="Amount (rounded to the nearest dollar)" id="js-allowed-hours-edit" />
                                                    <span class="input-group-addon">Hours</span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php if(in_array('M', $timeOffFormat)) { ?>
                                            <div class="col-lg-4 col-sm-4 cols-xs-12">
                                                <div class="input-group pto-time-off-margin-custom">
                                                    <input type="text" class="form-control" aria-label="Amount (rounded to the nearest dollar)" id="js-allowed-minutes-edit" />
                                                    <span class="input-group-addon">Minutes</span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 checkbox-styling margin-top">
                                <label>
                                    <input type="checkbox" class="checkbox-sizing" id="js-archived-edit" /> <span>Is Archived?</span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="bottom-section-pto">
                                    <div class="btn-button-section">
                                        <input type="hidden" id="js-plan-id-edit" />
                                        <button id="js-save-edit-btn" type="button" class="btn btn-save">UPDATE</button>
                                        <button type="button" class="btn btn-cancel js-view-plan-btn">CANCEL</button>
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
<?php $this->load->view('timeoff/scripts/plan-script'); ?>
