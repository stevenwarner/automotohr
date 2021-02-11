<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="<?=base_url('assets/js/select2.multi-checkboxes.js');?>"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/timeoffstyle.css"/>

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
                                        <p>Policy Overwrites</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right text-right">
                                        <a id="js-add-policy-btn" href="javascript:void(0)" class="dashboard-link-btn2">
                                            <span><i class="fa fa-plus"></i></span>&nbsp; ADD POLICY OVERWRITE
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
                                                        <label class="">Policies</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="template" id="js-filter-policies"></select>
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
                                                        <div class="pto-policy-date hr-select-dropdown">
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
                                                    <div class="pto-policy-date hr-select-dropdown">
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
                                    <table class="table table-bordereds table-striped table-condensed pto-policy-table">
                                        <thead class="heading-grey js-table-head">
                                            <tr>
                                                <th scope="col">Employee</th>
                                                <th scope="col">Policy</th>
                                                <th scope="col">Accural Type</th>
                                                <th scope="col">Accural Start Date</th>
                                                <th scope="col">Use it or lose it</th>
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
                        <div class="js-top-bar">
                            <div class="row mg-lr-0">
                                <div class="border-top-section border-bottom">
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-left">
                                            <p>Add Policy Overwrite</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-right-add-plan text-right">
                                            <div class="btn-filter-reset">
                                                <button type="button" class="btn btn-reset js-view-page-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW POLICY OVERWRITES</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-content-area">
                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Policy <span class="cs-required">*</span></label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="policy" id="js-policy-add">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Assigned Employee(s) <span class="cs-required">*</span></label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="template[]" id="js-employee-add">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Status</label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="template" id="js-status-add">
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-approver-check-add" />
                                            Only Approver(s) can see
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-unlimited-policy-check-add" />
                                            Unlimited Time-off
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-archive-check-add" />
                                            Archived
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <!-- Accrual Settings -->
                            <div class="js-accural-settings">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <h4 class="timeline-title allowed-time-off-title-custom">Accrual Settings</h4>
                                        <div class="form-group">
                                            <label class="font-wieght-light">Accrual Type <span class="cs-required">*</span></label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="template" id="js-accural-type-add">
                                                    <option value="0">None</option>
                                                    <option value="pay_per_year">Pay Per Year</option>
                                                    <option value="pay_per_period">Pay Per Period</option>
                                                    <!-- <option value="pay_per_hour">Based on hours worked</option> -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="row">
                                    <div class="col-lg-12 accrual-settings-line-1 margin-top">
                                        <span>Accruals will take place</span>
                                        <div class="form-group form-group-custom form-group-custom-settings">
                                            <select class="form-control form-control-custom" id="js-accrue-start-add">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div><span> day(s) after the end of each pay period.</span>
                                    </div>
                                </div> -->

                                <!-- <div class="row">
                                    <div class="col-lg-12 accrual-settings-line-1">
                                        <div class="alert alert-warning" style="display: inline-block;">
                                            Salaried employees accrue based on a 40-hour work week.
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 accrual-settings-line-1">
                                        <span>Accrue</span>
                                        <div class="form-group form-group-custom"><input type="text" class="form-control form-control-custom" id="usr"></div>
                                        <span>hours per </span>
                                        <div class="form-group form-group-custom"><input type="text" class="form-control form-control-custom" id="usr"></div>
                                        <span>hours worked</span>
                                    </div>
                                </div> -->


                                <div class="row">
                                    <br />
                                    <div class="col-lg-12 checkbox-styling-settings info-styling-custom font-wieght-bold">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" class="checkbox-sizing" id="js-lose-add"> <span><strong>Use it or lose it</strong>&nbsp; </span>
                                            <span class="control__indicator"></span>
                                        </label><i 
                                                data-toggle="popovers"
                                                data-trigger="hover"
                                                data-title="info"
                                                data-placement="right"
                                                data-content="If enabled then unconsumed time adds to this policy"
                                                class="fa fa-info-circle"
                                            ></i>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <!-- Accrual Date Settings -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Accrual Date Settings</h4>
                                </div>
                                <div class="col-lg-2">
                                    <div class="radio radio-custom">
                                        <label><input type="radio" name="optradio" checked="true" value="hireDate" class="js-hire-date-add"/>Hire Date</label>
                                        <label>
                                            <input type="radio" name="optradio" value="customHireDate" class="js-hire-date-add" />
                                            <div class="form-group">
                                                <div class="pto-plan-date hr-select-dropdown">
                                                    <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-add" />
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <div class="row" id="js-plan-box-add">
                                <div class="col-md-6 offset-md-3">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Plans</h4>
                                    <div class="pto-allowed-years">
                                        <div>
                                            <div>
                                                <select id="js-plans-select-add" multiple="true"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="demo-page-list pto-allowed-years">
                                        <ul id="js-plan-area-add"></ul>
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
                    <div class="right-content-2 js-page" id="js-page-edit" <?=$page != 'edit' && $policySid != null ? 'style="display: none;"' : '';?>>
                        <div class="js-top-bar">
                            <div class="row mg-lr-0">
                                <div class="border-top-section border-bottom">
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-left">
                                            <p>Edit Policy Overwrite</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-right-add-plan text-right">
                                            <div class="btn-filter-reset">
                                                <button type="button" class="btn btn-reset js-view-page-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW POLICY OVERWRITES</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="js-content-area">
                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Policy <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" name="policy" id="js-policy-edit">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Assigned Employee(s) <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" name="template[]" id="js-employee-edit">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Status</label>
                                    <div class="">
                                        <select class="invoice-fields" name="template" id="js-status-edit">
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-approver-check-edit" />
                                            Only Approver(s) can see
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-unlimited-policy-check-edit" />
                                            Unlimited Time-off
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-archive-check-edit" />
                                            Archived
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <!-- Accrual Settings -->
                            <div class="js-accural-settings">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <h4 class="timeline-title allowed-time-off-title-custom">Accrual Settings</h4>
                                        <div class="form-group">
                                            <label class="font-wieght-light">Accrual Type <span class="cs-required">*</span></label>
                                            <div class="">
                                                <select class="invoice-fields" name="template" id="js-accural-type-edit">
                                                    <option value="0">None</option>
                                                    <option value="pay_per_year">Pay Per Year</option>
                                                    <option value="pay_per_period">Pay Per Period</option>
                                                    <!-- <option value="pay_per_hour">Based on hours worked</option> -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="row">
                                    <div class="col-lg-12 accrual-settings-line-1 margin-top">
                                        <span>Accruals will take place</span>
                                        <div class="form-group form-group-custom form-group-custom-settings">
                                            <select class="form-control form-control-custom" id="js-accrue-start-edit">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div><span> day(s) after the end of each pay period.</span>
                                    </div>
                                </div> -->

                                <!-- <div class="row">
                                    <div class="col-lg-12 accrual-settings-line-1">
                                        <div class="alert alert-warning" style="display: inline-block;">
                                            Salaried employees accrue based on a 40-hour work week.
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 accrual-settings-line-1">
                                        <span>Accrue</span>
                                        <div class="form-group form-group-custom"><input type="text" class="form-control form-control-custom" id="usr"></div>
                                        <span>hours per </span>
                                        <div class="form-group form-group-custom"><input type="text" class="form-control form-control-custom" id="usr"></div>
                                        <span>hours worked</span>
                                    </div>
                                </div> -->


                                <div class="row">
                                    <br />
                                    <div class="col-lg-12 checkbox-styling-settings info-styling-custom font-wieght-bold">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" class="checkbox-sizing" id="js-lose-edit"> <span><strong>Use it or lose it</strong></span>
                                            <i 
                                                data-toggle="popovers"
                                                data-trigger="hover"
                                                data-title="info"
                                                data-placement="right"
                                                data-content="If enabled then unconsumed time adds to this policy"
                                                class="fa fa-info-circle"
                                            ></i>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <!-- Accrual Date Settings -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Accrual Date Settings</h4>
                                </div>
                                <div class="col-lg-2">
                                    <div class="radio radio-custom">
                                        <label><input type="radio" name="optradio" checked="true" value="hireDate" class="js-hire-date-edit"/>Hire Date</label>
                                        <label>
                                            <input type="radio" name="optradio" value="customHireDate" class="js-hire-date-edit" />
                                            <div class="form-group">
                                                <div class="pto-plan-date hr-select-dropdown">
                                                    <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-edit" />
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <div class="row" id="js-plan-box-edit">
                                 <div class="col-md-6 offset-md-3">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Plans</h4>
                                    <div class="pto-allowed-years">
                                        <div>
                                            <div>
                                                <select id="js-plans-select-edit" multiple="true"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="demo-page-list pto-allowed-years">
                                        <ul id="js-plan-area-edit"></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="bottom-section-pto">
                                        <div class="btn-button-section">
                                            <input type="hidden" id="js-policy-id-edit" />
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
<?php $this->load->view('timeoff/scripts/policy-overwrite'); ?>
