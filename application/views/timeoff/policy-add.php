<!--  -->
<div class="row mb10">
                                        <div class="col-sm-12">
                                            <h4 class="timeline-title allowed-time-off-title-custom csHeading">Basic Settings</h4>
                                        </div>
                                    </div>

                                    <!-- Policy Type - Edit -->
                                    <div class="row margin-top" id="js-policy-categories-add">
                                        <div class="col-md-6 offset-md-3">
                                            <div class="form-group margin-bottom-custom">
                                                <label>Type <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['type_info']; ?>" class="fa fa-question-circle"></i></label>
                                                <div>
                                                    <select id="js-category-edit"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Policy Ttile - Edit -->
                                    <div class="row margin-top">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group margin-bottom-custom">
                                                <div class="form-group margin-bottom-custom">
                                                    <label class="">Policy Title <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['policy_title_info']; ?>" class="fa fa-question-circle"></i></label>
                                                    <div>
                                                        <input class="invoice-fields" name="policyTitle" id="js-policy-title-edit" placeholder="Sick leave" />
                                                    </div>
                                                </div>    
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Policy Sort Order - Edit -->
                                    <div class="row margin-top">
                                        <div class="col-sm-6 col-xs-12">
                                            <label>Sort Order <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['sort_order_info']; ?>" class="fa fa-question-circle"></i></label>
                                            <div>
                                                <input class="invoice-fields" name="template" id="js-sort-order-edit" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Policy Entitled Employees - Edit -->
                                    <div class="row margin-top">
                                        <div class="col-sm-6 col-xs-12">
                                            <label>Entitled Employee(s) <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['entitled_employee_info']; ?>" class="fa fa-question-circle"></i></label>
                                            <div>
                                                <select class="invoice-fields" name="template[]" id="js-employee-edit" multiple="true">
                                                </select>
                                            </div>
                                            <!-- <p>
                                                <strong id="js-employee-count-edit">0</strong> employee(s) selected
                                            </p> -->
                                        </div>
                                    </div>

                                    <div class="row mb10">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="">
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" id="js-approver-check-edit" />
                                                    Only Approver(s) can see
                                                    <span class="control__indicator"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb10">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="">
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" id="js-archive-check-edit" />
                                                    Deactivate
                                                    <span class="control__indicator"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb10">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="">
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" id="js-include-check-edit" />
                                                    Include in Balance
                                                    <span class="control__indicator"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Policy Accruals - Edit -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4 class="timeline-title allowed-time-off-title-custom csHeading">Accrual Schedule & Settings</h4>
                                        </div>
                                    </div>

                                    <!-- Policy Implement Date - Edit -->
                                    <div class="row mb10">
                                        <div class="col-lg-12">
                                            <h5 class="timeline-title allowed-time-off-title-custom">Applicable Date for Policy <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['applicable_date_for_policy_info']; ?>" class="fa fa-question-circle"></i></h5>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="">
                                                <label class="control control--radio">
                                                    Employee Start Date
                                                    <input type="radio" name="optradio" checked="true" value="hireDate" class="js-hire-date-edit" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <br />
                                                <label class="control control--radio">
                                                    Pick a Date
                                                    <input type="radio" name="optradio" value="customHireDate" class="js-hire-date-edit" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <div class="jsImplementDateBox" style="display: none; margin-top: 5px;">
                                                    <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Policy Reset Date - Edit  -->
                                    <div class="row mb10">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">Reset Date <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['reset_date_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div class="">
                                                    <label  class="control control--radio">
                                                        Policy Applicable Date
                                                        <input type="radio" name="js-policy-reset-date-edit" class="js-policy-reset-date-edit" checked="true" value="policyDate"/>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <br />
                                                    <label  class="control control--radio">
                                                        Pick a Date
                                                        <input type="radio" name="js-policy-reset-date-edit" class="js-policy-reset-date-edit" value="policyDateCustom" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <div class="jsResetDateBox" style="display: none; margin-top: 5px;">
                                                        <input type="text" readonly="true" class="invoice-fields" id="js-custom-reset-date-edit" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Minimum Applicable Hours - Edit -->
                                    <div class="row mb10">
                                        <div class="col-sm-6">
                                            <!--  -->
                                            <div>
                                                <h5 class="timeline-title allowed-time-off-title-custom">Minimum Applicable Time <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['minimum_applicable_hours_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div>
                                                    <label class="control control--radio">
                                                        Hours &nbsp;&nbsp;
                                                        <input type="radio" name="js-minimum-applicable-time-add" checked="true" value="hours" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <label class="control control--radio">
                                                        Days &nbsp;&nbsp;
                                                        <input type="radio" name="js-minimum-applicable-time-add" value="days" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <label class="control control--radio">
                                                        Months &nbsp;&nbsp;
                                                        <input type="radio" name="js-minimum-applicable-time-add" value="months" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <label class="control control--radio">
                                                        Years &nbsp;&nbsp;
                                                        <input type="radio" name="js-minimum-applicable-time-add" value="Years" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div style="margin-top: 5px;">
                                                    <input class="invoice-fields" name="template" id="js-minimum-applicable-hours-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Accrual Methid - Edit -->
                                    <div class="row mb10">
                                        <div class="col-sm-6">
                                            <div>
                                                <h5 class="timeline-title allowed-time-off-title-custom">Accrual Method <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['accrual_method_info']; ?>" class="fa fa-question-circle"></i></h5>
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

                                    <!-- Accrual Time - Edit -->
                                    <div class="row mb10 js-hider-edit">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">Accrual Time <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['accrual_time_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-time-edit">
                                                        <option value="start_of_period" selected="true">Start of the period</option>
                                                        <option value="end_of_period">End of the period</option>
                                                    </select>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>

                                    <!-- Carryover - Edit -->
                                    <div class="row mb10 js-hider-edit">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">Allow Carryover Cap? (use it or lose it) <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['allow_carryover_cap_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-carryover-cap-check-edit">
                                                        <option value="yes" selected="true">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Negative Balance - Edit -->
                                    <div class="row mb10 js-hider-edit">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">Allow Negative Balance <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['allow_negative_balance_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div>
                                                    <select class="invoice-fields" name="template" id="js-accrual-balance-edit">
                                                        <option value="yes" >Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Accrual Frequency - Edit -->
                                    <div class="row mb10 js-hider-edit">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">Accrual Frequency <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['accrual_frequency_info']; ?>" class="fa fa-question-circle"></i></h5>
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

                                    <!-- Alowed Time -->
                                    <div class="row mb10 js-hider-edit">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">Accrual Rate <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['accrual_rate_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-accrual-rate-edit" />
                                                </div>
                                            </div>    
                                        </div>
                                    </div>

                                    <!-- Carryover Value - Edit -->
                                    <div class="row mb10 js-hider-edit js-carryover-box-edit">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">Carryover Cap <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['carryover_cap_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-carryover-cap-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Negative Balance - Edit -->
                                    <div class="row mb10 js-hider-edit js-negative-box-edit">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">Allowed negative balance (Hours/Days) <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['new_hire_maximum_days_off_HD_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-maximum-balance-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4 class="timeline-title allowed-time-off-title-custom csHeading">New Hire</h4>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row mb10">
                                        <div class="col-lg-12 js-hider-edit accrual-settings-line-1 margin-top">
                                            <h5 class="timeline-title allowed-time-off-title-custom">Waiting period <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['waiting_period_info']; ?>" class="fa fa-question-circle"></i></h5>
                                            <span>New hires can request time off after</span>
                                            <div class="form-group form-group-custom form-group-custom-settings">
                                                <input class="form-control form-control-custom" id="js-accrue-new-hire-edit" />
                                            </div><span> day(s).</span>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row mb10 js-hider-edit">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <h5 class="timeline-title allowed-time-off-title-custom">New hire maximum days off <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['new_hire_maximum_days_off_info']; ?>" class="fa fa-question-circle"></i></h5>
                                                <div>
                                                    <input class="invoice-fields" name="template" id="js-newhire-prorate-edit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                     <!--  -->
                                    <div class="row mb10">
                                        <div class="col-lg-12">
                                            <h4 class="timeline-title allowed-time-off-title-custom csHeading">Accrual Plans 
                                                <i data-toggle="popovers" data-trigger="hover" data-title="Info" data-placement="right" data-html="true" data-content="<?php echo $get_policy_item_info['accrual_plans']; ?>" class="fa fa-question-circle"></i>
                                                <span class="pull-right">
                                                    <button class="btn btn-success js-plan-btn-edit" data-type="edit" style="margin-top: -5px;"><i class="fa fa-plus"></i>&nbsp; Add Plan</button>
                                                </span>
                                            </h4>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row mb10 js-hider-edit">
                                        <div class="col-sm-6 col-xs-12">
                                            
                                        </div>
                                        <!-- Plans area -->
                                        <div class="col-sm-12 col-xs-12" id="js-plan-area-edit"></div>
                                    </div>