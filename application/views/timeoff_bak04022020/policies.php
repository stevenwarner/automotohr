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
                                        <p>Policies</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right text-right">
                                        <a id="js-add-policy-btn" href="javascript:void(0)" class="dashboard-link-btn2">
                                            <span><i class="fa fa-plus"></i></span>&nbsp; ADD POLICY
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
                                        <div class="col-lg-7">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Policies</label>
                                                        <div>
                                                            <select class="invoice-fields" name="template" id="js-filter-policies"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Employees</label>
                                                        <div>
                                                            <select class="invoice-fields" name="template" id="js-filter-employee"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Date From</label>
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
                                                    <label class="">Date to</label>
                                                    <div class="pto-policy-date ">
                                                        <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="">Status</label>
                                                    <div>
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
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordereds table-striped table-condensed pto-policy-table">
                                            <thead class="heading-grey js-table-head">
                                                <tr>
                                                    <th scope="col">Policy</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Start Date</th>
                                                    <th scope="col">Use it or lose it</th>
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
                                            <p>Add Policy</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-right-add-plan text-right">
                                            <div class="btn-filter-reset">
                                                <button type="button" class="btn btn-reset js-view-page-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW POLICIES</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="js-content-area">
                            <div class="row margin-top" id="js-policy-categories-add">
                                <div class="col-md-6 offset-md-3">
                                    <div class="form-group margin-bottom-custom margin-right">
                                        <label class="">Type <span class="cs-required">*</span> </label>
                                        <div>
                                            <select id="js-category-add"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group margin-bottom-custom margin-right">
                                        <label class="">Policy Title <span class="cs-required">*</span>  </label>
                                        <div>
                                            <input class="invoice-fields" name="policyTitle" id="js-policy-title-add" placeholder="Sick leave" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group margin-bottom-custom margin-right">
                                        <label class="">Policy Start Date <span class="cs-required">*</span>  <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-content=" It’s an actual Date when Policy will be applicable with all its conditions to all selected employees." class="fa fa-question-circle"></i></label>
                                        <div>
                                            <input class="invoice-fields" id="js-policy-start-date-add" readonly="true" />
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Sort Order</label>
                                    <div>
                                        <input class="invoice-fields" name="template" id="js-sort-order-add" />
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Entitled Employee(s)</label>
                                    <div>
                                        <select class="invoice-fields" name="template[]" id="js-employee-add" multiple="true">
                                        </select>
                                    </div>
                                    <!-- <p>
                                        <strong id="js-employee-count-add">0</strong> employee(s) selected
                                    </p> -->
                                </div>
                            </div>

                            <!-- Accrual Date Settings -->
                            <div class="row">
                                <hr />
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Accrual Schedule & Settings</h4>
                                </div>
                                <div class="col-lg-12">
                                    <h5 class="timeline-title allowed-time-off-title-custom">Applicable Date for Policy</h5>
                                </div>
                                <div class="col-lg-6">
                                    <div class="radio radio-custom">
                                        <label><input type="radio" name="optradio" checked="true" value="hireDate" class="js-hire-date-add"/>Employee Start Date</label>
                                        <label>
                                            <input type="radio" name="optradio" value="customHireDate" class="js-hire-date-add" />
                                            <div class="form-group">
                                                <div class="pto-plan-date ">
                                                    <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-add" />
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-12"></div>
                                <!--  -->
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-sm-12 mb-20">
                                            <!--  -->
                                            <div>
                                                <br />
                                                <label>Minimum Applicable Hours</label></label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-minimum-applicable-hours-add" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row">
                                        <div class="col-sm-12 mb-20">
                                            <div    >
                                                <label>Accrual Method <span class="cs-required">*</span></label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-method-add">
                                                        <option value="unlimited">Unlimited</option>
                                                        <option value="days_per_year" selected="true">Days per Year</option>
                                                        <option value="hours_per_month">Hours per Month</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Accrual Rate <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-content="Maximum hours in a month or days in a year, depend on Accural Method. Add 0 for no limit" class="fa fa-question-circle"></i></label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-accrual-rate-add" />
                                                </div>
                                            </div>    
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Accrual Time <span class="cs-required">*</span></label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-time-add">
                                                        <option value="start_of_period" selected="true">Start of the period</option>
                                                        <option value="end_of_period">End of the period</option>
                                                    </select>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Reset Date</label>
                                                <div class="radio radio-custom">
                                                    <label><input type="radio" name="js-policy-reset-date" checked="true" value="policyDate" class="js-hire-date-add"/>Policy Applicable Date</label>
                                                    <label>
                                                        <input type="radio" name="js-policy-reset-date" value="policyDateCustom" class="js-reset-date-add" />
                                                        <div class="form-group">
                                                            <div class="pto-plan-date ">
                                                                <input type="text" readonly="true" class="invoice-fields" id="js-custom-reset-date-add" />
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Accrual Frequency <span class="cs-required">*</span></label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-frequency-add">
                                                        <option value="none" selected="true">None</option>
                                                        <option value="yearly">Yearly</option>
                                                        <option value="monthly">Monthly</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row js-hider-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Allow Carryover Cap? (use it or lose it) <span class="cs-required">*</span></label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-carryover-cap-check-add">
                                                        <option value="yes" selected="true">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--  -->
                                    <div class="row js-hider-add js-carryover-box-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Carryover Cap </label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-carryover-cap-add" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Allow Negative Balance</label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-balance-add">
                                                        <option value="yes" >Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--  -->
                                    <div class="row js-hider-add js-negative-box-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Allow Maximum Negative Balance (Hours/Days)</label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-maximum-balance-add" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-add">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>New hire maximum days off</label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-newhire-prorate-add" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-12 js-hider-add accrual-settings-line-1 margin-top">
                                    <label>Waiting period</label>
                                    <br />
                                    <span>New hires can request time off after</span>
                                    <div class="form-group form-group-custom form-group-custom-settings">
                                        <input class="form-control form-control-custom" id="js-accrue-new-hire-add" />
                                    </div><span> day(s).</span>
                                </div>
                            </div>

                            <div class="row">
                                <hr />
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
                                            <input type="checkbox" id="js-archive-check-add" />
                                            Deactivate
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

             
                            <!-- FMLA Range -->
                            <div class="row js-fmla-range-wrap-add" style="display: none;">
                                <hr />
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">FMLA Range</h4>
                                </div>
                                <div class="col-lg-6">
                                    <div class="radio radio-custom">
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-add" value="standard_year" class="js-fmla-range-add"/>&nbsp;Standard Year (Jan-Dec)
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-add" value="employee_start_date" class="js-fmla-range-add"/>&nbsp;Employee Start Date
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-add" value="start_year" class="js-fmla-range-add"/>&nbsp;Starting from First FMLA usage
                                            <i class="fa fa-question-circle js-popover" data-content="The 12-month period measured forward from the date of your first FMLA leave usage."></i>
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-add" value="end_year" class="js-fmla-range-add"/>&nbsp;Ending on your First FMLA usage
                                            <i class="fa fa-question-circle js-popover" data-content="A “rolling” 12-month period measured backward from the date of any FMLA leave usage."></i>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row" id="js-plan-box-add">
                                <hr />
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
                            </div> -->

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
                                            <p>Edit Policy</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-right-edit-plan text-right">
                                            <div class="btn-filter-reset">
                                                <button type="button" class="btn btn-reset js-view-page-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW POLICIES</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="js-content-area">

                            <div class="row margin-top" id="js-policy-categories-add">
                                <div class="col-md-6 offset-md-3">
                                    <!--  -->
                                    <div class="form-group margin-bottom-custom margin-right">
                                        <label class="">Type <span class="cs-required">*</span>  </label>
                                        <div>
                                            <select id="js-category-edit"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group margin-bottom-custom margin-right">
                                        <label class="">Policy Title <span class="cs-required">*</span>  </label>
                                        <div>
                                            <input class="invoice-fields" name="policyTitle" id="js-policy-title-edit" placeholder="Sick leave" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group margin-bottom-custom margin-right">
                                        <label class="">Policy Start Date <span class="cs-required">*</span>  <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-content=" It’s an actual Date when Policy will be applicable with all its conditions to all selected employees." class="fa fa-question-circle"></i></label>
                                        <div>
                                            <input class="invoice-fields" id="js-policy-start-date-edit" readonly="true" />
                                        </div>
                                    </div>
                                </div>
                            </div> -->


                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Sort Order</label>
                                    <div>
                                        <input class="invoice-fields" name="template" id="js-sort-order-edit" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Entitled Employee(s)</label>
                                    <div>
                                        <select class="invoice-fields" name="template[]" id="js-employee-edit" multiple="true">
                                        </select>
                                    </div>
                                    <!-- <p>
                                        <strong id="js-employee-count-edit">0</strong> employee(s) selected
                                    </p> -->
                                </div>
                            </div>

                            <!--  -->
                            <div class="row">
                                
                                <hr />
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Accrual Schedule & Settings</h4>
                                </div>
                                <div class="col-lg-12">
                                    <h5 class="timeline-title allowed-time-off-title-custom">Applicable Date for Policy</h5>
                                </div>
                                <div class="col-lg-6">
                                <!-- <div class="col-lg-12"> -->
                                    <div class="radio radio-custom">
                                        <label><input type="radio" name="optradio" checked="true" value="hireDate" class="js-hire-date-edit"/>Employee Start Date</label>
                                        <label>
                                            <input type="radio" name="optradio" value="customHireDate" class="js-hire-date-edit" />
                                            <div class="form-group">
                                                <div class="pto-plan-date">
                                                    <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-edit" />
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            <!-- </div>/ -->

                                <div class="col-sm-12"></div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-sm-12 mb-20">
                                            <!--  -->
                                            <div>
                                                <br />
                                                <label>Minimum Applicable Hours <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-content="" class="fa fa-question-circle"></i></label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-minimum-applicable-hours-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row">
                                        <div class="col-sm-12 mb-20">
                                            <div    >
                                                <label>Accrual Method <span class="cs-required">*</span></label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-method-edit">
                                                        <option value="unlimited">Unlimited</option>
                                                        <option value="days_per_year" selected="true">Days per Year</option>
                                                        <option value="hours_per_month">Hours per Month</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Accrual Rate <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-content="Maximum hours in a month or days in a year, depend on Accural Method. Add 0 for no limit" class="fa fa-question-circle"></i></label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-accrual-rate-edit" />
                                                </div>
                                            </div>    
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Accrual Time <span class="cs-required">*</span></label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-time-edit">
                                                        <option value="start_of_period" selected="true">Start of the period</option>
                                                        <option value="end_of_period">End of the period</option>
                                                    </select>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Reset Date</label>
                                                <div class="radio radio-custom">
                                                    <label><input type="radio" name="js-policy-reset-date" checked="true" value="policyDate" class="js-hire-date-edit"/>Policy Applicable Date</label>
                                                    <label>
                                                        <input type="radio" name="js-policy-reset-date" value="policyDateCustom" class="js-reset-date-edit" />
                                                        <div class="form-group">
                                                            <div class="pto-plan-date ">
                                                                <input type="text" readonly="true" class="invoice-fields" id="js-custom-reset-date-edit" />
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Accrual Frequency <span class="cs-required">*</span></label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-frequency-edit">
                                                        <option value="none" selected="true">None</option>
                                                        <option value="yearly">Yearly</option>
                                                        <option value="monthly">Monthly</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row js-hider-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Allow Carryover Cap? (use it or lose it) <span class="cs-required">*</span></label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-carryover-cap-check-edit">
                                                        <option value="yes" selected="true">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--  -->
                                    <div class="row js-hider-edit js-carryover-box-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Carryover Cap </label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-carryover-cap-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Allow Negative Balance</label>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-balance-edit">
                                                        <option value="yes" >Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--  -->
                                    <div class="row js-hider-edit js-negative-box-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>Allow Maximum Negative Balance (Hours/Days)</label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-maximum-balance-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row js-hider-edit">
                                        <div class="col-sm-12 mb-20">
                                            <div class="form-group">
                                                <label>New hire maximum days off</label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-newhire-prorate-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                     <!--  -->
                                    <div class="col-lg-12 js-hider-edit accrual-settings-line-1 margin-top">
                                        <label>Waiting period</label>
                                        <br />
                                        <span>New hires can request time off after</span>
                                        <div class="form-group form-group-custom form-group-custom-settings">
                                            <input class="form-control form-control-custom" id="js-accrue-new-hire-edit" />
                                        </div><span> day(s).</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!--  -->
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
                                            <input type="checkbox" id="js-archive-check-edit" />
                                            Deactivate
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <!-- FMLA Range -->
                            <div class="row js-fmla-range-wrap-edit" style="display: none;">
                                <hr />
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">FMLA Range</h4>
                                </div>
                                <div class="col-lg-6">
                                    <div class="radio radio-custom">
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-edit" value="standard_year" class="js-fmla-range-edit"/>&nbsp;Standard Year (Jan-Dec)
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-edit" value="employee_start_date" class="js-fmla-range-edit"/>&nbsp;Employee Start Date
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-edit" value="start_year" class="js-fmla-range-edit"/>&nbsp;Starting from First FMLA usage
                                            <i class="fa fa-question-circle js-popover" data-content="The 12-month period measured forward from the date of your first FMLA leave usage."></i>
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-edit" value="end_year" class="js-fmla-range-edit"/>&nbsp;Ending on your First FMLA usage
                                            <i class="fa fa-question-circle js-popover" data-content="A “rolling” 12-month period measured backward from the date of any FMLA leave usage."></i>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row" id="js-plan-box-edit">
                                <hr />
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
                            </div> -->

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
<?php $this->load->view('timeoff/scripts/policy'); ?>