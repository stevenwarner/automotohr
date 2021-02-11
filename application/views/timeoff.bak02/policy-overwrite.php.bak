<?php $get_policy_item_info = get_policy_item_info(); ?>
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
                                    <div class="pto-top-heading-right text-right pr0">
                                        <a id="js-add-policy-btn" href="javascript:void(0)" class="dashboard-link-btn2 cs-btn-add">
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
                                    <li class="tab-btn js-tab" data-type="archived"><a data-toggle="tab">Deactivated</a></li>
                                    <button id="btn_apply_filter" type="button" class="btn btn-apply-filter">FILTER</button>
                                </ul>
                                <div class="filter-content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="">Employees</label>
                                                        <div class="">
                                                            <select class="invoice-fields" name="template" id="js-filter-employee"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="">Policies</label>
                                                        <div class="">
                                                            <select class="invoice-fields" name="template" id="js-filter-policies"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="">Status</label>
                                                    <div class="">
                                                        <select class="invoice-fields" name="template" id="js-filter-status">
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
                                                        <div class="pto-policy-date ">
                                                            <input type="text" readonly="" class="invoice-fields" id="js-filter-from-date" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="">Date to</label>
                                                    <div class="pto-policy-date ">
                                                        <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" />
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

                        <!-- Active Tab -->
                        <div class="active-content">
                            <!-- Pagination Top -->
                            <div class="js-ip-pagination"></div>
                            <div class="row">
                                <div class="col-lg-12 table-responsive">
                                    <table class="table table-bordereds table-striped table-condensed pto-policy-table">
                                        <thead class="heading-grey js-table-head">
                                            <tr>
                                                <th scope="col">Policy</th>
                                                <th scope="col">Start Date</th>
                                                <th scope="col">Use it or lose it</th>
                                                <th scope="col">Created By</th>
                                                <th scope="col">Created On</th>
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
                                            <div class="btn-filter-reset pr10">
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
                                    <label class="">Policy <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['policy_title_info']; ?>" class="fa fa-question-circle"></i></label>
                                    <div class="">
                                        <select class="invoice-fields" name="policy" id="js-policy-add">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Entitled Employee <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['entitled_employee_info']; ?>" class="fa fa-question-circle"></i></label>
                                    <div class="">
                                        <select class="invoice-fields" name="template[]" id="js-employee-add">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <!-- Accrual Date Settings -->
                            <div class="row">
                                <hr />
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Accrual Schedule & Settings</h4>
                                </div>
                                <div class="col-lg-12">
                                    <h5 class="timeline-title allowed-time-off-title-custom">Applicable Date for Policy <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['applicable_date_for_policy_info']; ?>" class="fa fa-question-circle"></i></h5>
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
                                                <label>Minimum Applicable Hours <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['minimum_applicable_hours_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Accrual Method <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_method_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Accrual Rate <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_rate_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Accrual Time <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_time_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Reset Date <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['reset_date_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Accrual Frequency <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_frequency_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Allow Carryover Cap? (use it or lose it) <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['allow_carryover_cap_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Carryover Cap <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['carryover_cap_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Allow Negative Balance <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['allow_negative_balance_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Allowed negative balance (Hours/Days) <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['new_hire_maximum_days_off_HD_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>New hire maximum days off <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['new_hire_maximum_days_off_info']; ?>" class="fa fa-question-circle"></i></label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-newhire-prorate-add" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-12 js-hider-add accrual-settings-line-1 margin-top">
                                    <label>Waiting period <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['waiting_period_info']; ?>" class="fa fa-question-circle"></i></label>
                                    <br />
                                    <span>New hires can request time off after</span>
                                    <div class="form-group form-group-custom form-group-custom-settings">
                                        <input class="form-control form-control-custom" id="js-accrue-new-hire-add" />
                                    </div><span> day(s).</span>
                                </div>
                            </div>

                            <!--  -->

                            <div class="row js-hider-add">
                                <hr />
                                <div class="col-sm-12 col-xs-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Accrual Plans <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_plans']; ?>" class="fa fa-question-circle"></i> </h4>
                                    <button class="btn btn-success js-plan-btn-add" data-type="add"><i class="fa fa-plus"></i>&nbsp; Add Plan</button>
                                </div>
                                <!-- Plans area -->
                                <div class="col-sm-12 col-xs-12" id="js-plan-area-add"></div>
                            </div>
                            
                            <hr />
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
                                            <button id="js-save-add-btn" type="button" class="btn btn-save ml0">APPLY</button>
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
                                        <div class="pto-top-heading-right-add-plan text-right pr10">
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
                                    <label class="">Policy <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['policy_title_info']; ?>" class="fa fa-question-circle"></i></label>
                                    <div class="">
                                        <select class="invoice-fields" name="policy" id="js-policy-edit">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Entitled Employee <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['entitled_employee_info']; ?>" class="fa fa-question-circle"></i></label>
                                    <div class="">
                                        <select class="invoice-fields" name="template[]" id="js-employee-edit">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <!-- Accrual Date Settings -->
                            <div class="row">
                                <hr />
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Accrual Schedule & Settings</h4>
                                </div>
                                <div class="col-lg-12">
                                    <h5 class="timeline-title allowed-time-off-title-custom">Applicable Date for Policy <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['applicable_date_for_policy_info']; ?>" class="fa fa-question-circle"></i></h5>
                                </div>
                                <div class="col-lg-6">
                                    <div class="radio radio-custom">
                                        <label><input type="radio" name="optradio" checked="true" value="hireDate" class="js-hire-date-edit"/>Employee Start Date</label>
                                        <label>
                                            <input type="radio" name="optradio" value="customHireDate" class="js-hire-date-edit" />
                                            <div class="form-group">
                                                <div class="pto-plan-date ">
                                                    <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-edit" />
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
                                                <label>Minimum Applicable Hours <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['minimum_applicable_hours_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Accrual Method <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_method_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Accrual Rate <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_rate_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Accrual Time <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_time_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Reset Date <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['reset_date_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Accrual Frequency <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_frequency_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Allow Carryover Cap? (use it or lose it) <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['allow_carryover_cap_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Carryover Cap <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['carryover_cap_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Allow Negative Balance <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['allow_negative_balance_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>Allowed negative balance(Hours/Days) <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['new_hire_maximum_days_off_HD_info']; ?>" class="fa fa-question-circle"></i></label>
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
                                                <label>New hire maximum days off <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['new_hire_maximum_days_off_info']; ?>" class="fa fa-question-circle"></i></label>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-newhire-prorate-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-12 js-hider-edit accrual-settings-line-1 margin-top">
                                    <label>Waiting period <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['waiting_period_info']; ?>" class="fa fa-question-circle"></i></label>
                                    <br />
                                    <span>New hires can request time off after</span>
                                    <div class="form-group form-group-custom form-group-custom-settings">
                                        <input class="form-control form-control-custom" id="js-accrue-new-hire-edit" />
                                    </div><span> day(s).</span>
                                </div>
                            </div>

                            <!--  -->

                            <div class="row js-hider-edit">
                                <hr />
                                <div class="col-sm-12 col-xs-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">Accrual Plans <i data-toggle="popovers" data-trigger="hover" data-html="true" data-title="Info" data-placement="right" data-content="<?php echo $get_policy_item_info['accrual_plans']; ?>" class="fa fa-question-circle"></i> </h4>
                                    <button class="btn btn-success js-plan-btn-edit" data-type="edit"><i class="fa fa-plus"></i>&nbsp; Add Plan</button>
                                </div>
                                <!-- Plans area -->
                                <div class="col-sm-12 col-xs-12" id="js-plan-area-edit"></div>
                            </div>
                            
                            <hr />
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="bottom-section-pto">
                                        <div class="btn-button-section">
                                            <input type="hidden" id="js-policy-id-edit" />
                                            <button id="js-save-edit-btn" type="button" class="btn btn-save ml0">APPLY</button>
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
