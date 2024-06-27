<div class="js-page p10" id="js-page-clone" style="display: none;">

    <div class="csPageHeader <?php echo $this->agent->is_mobile() ? 'csMobileWrap' : ''; ?>">
        <div class="row">
            <div class="col-sm-6">
                <h4>
                    Clone Policy <strong id="jsPolicyTitleEdit-clone"></strong>
                </h4>
            </div>
            <div class="col-sm-6">
                <span class="pull-right">
                    <button class="btn btn-orange btn-theme jsEditResetCheckbox" data-type="cp">Current Policy</button>
                    <button class="btn btn-default btn-theme jsEditResetCheckbox" data-type="rp">On Reset
                        Policy</button>
                    <button type="button" class="btn btn-orange jsViewPoliciesBtn">
                        <span>
                            <i class="fa fa-arrow-circle-left"></i>
                        </span>&nbsp;VIEW POLICIES
                    </button>
                </span>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="csPageBody">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-sm-2">
                <?php $this->load->view('timeoff/partials/new_steps', ['stepType' => 'clone']); ?>
            </div>

            <!-- Content -->
            <div class="col-sm-10">
                <div class="csProcessWrap">
                    <div id="jsEditPageClone">
                        <!-- Step 1 - Basic Information -->
                        <div class="js-step" data-type="clone" data-step="1">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>

                            <div class="row mb10 csRow" id="js-policy-type-box-edit-clone">
                                <div class="col-md-6 offset-md-3">
                                    <div class="form-group margin-bottom-custom">
                                        <label>Policy Category<span class="cs-required">*</span> <i class="fa fa-question-circle" data-hint="js-hint" data-target="type"></i></label>
                                        <div class="js-hint js-hint-type">Policy can be paid or unpaid
                                        </div>
                                        <div>
                                            <select id="js-policy-type-edit-clone">
                                                <option value="0">Unpaid</option>
                                                <option value="1">Paid</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Policy Type - Edit -->
                            <div class="row mb10 csRow" id="js-policy-categories-edit-clone">
                                <div class="col-md-6 offset-md-3">
                                    <div class="form-group margin-bottom-custom">
                                        <label><?php echo $get_policy_item_info['policy_type_label']; ?> <span class="cs-required">*</span> <i class="fa fa-question-circle" data-hint="js-hint" data-target="type"></i></label>
                                        <div class="js-hint js-hint-type">
                                            <?php echo $get_policy_item_info['type_info']; ?>
                                        </div>
                                        <div>
                                            <select id="js-category-edit-clone"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Policy Ttile - Edit -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group margin-bottom-custom">
                                        <div class="form-group margin-bottom-custom">
                                            <label class=""><?php echo $get_policy_item_info['policy_title_label']; ?>
                                                <span class="cs-required">*</span>
                                                <i class="fa fa-question-circle" data-hint="js-hint" data-target="info"></i>
                                            </label>
                                            <div class="js-hint js-hint-info">
                                                <?php echo $get_policy_item_info['policy_title_info']; ?>
                                            </div>
                                            <div>
                                                <input class="invoice-fields" name="policyTitle" id="js-policy-title-edit-clone" placeholder="Sick leave" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Policy Sort Order - Edit -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <label><?php echo $get_policy_item_info['sort_order_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="sort"></i>
                                    </label>
                                    <div class="js-hint js-hint-sort">
                                        <?php echo $get_policy_item_info['sort_order_info']; ?></div>
                                    <div>
                                        <input class="invoice-fields" name="template" id="js-sort-order-edit-clone" />
                                    </div>
                                </div>
                            </div>

                            <!-- Policy Entitled Employees - Edit -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <label class="control control--radio">
                                        <?php echo $get_policy_item_info['non_employees_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="employees"></i>

                                        <input type="radio" class="jsIsEntitledEmployee" name="is_entitled_employee" value="0" id="NonEntitledEmployees" checked>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <br>
                                    <label class="control control--radio">
                                        <?php echo $get_policy_item_info['entitled_employee_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="employees2"></i>

                                        <input type="radio" class="jsIsEntitledEmployee" name="is_entitled_employee" value="1" id="EntitledEmployees" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <br>
                                    <br>
                                    <div class="js-hint js-hint-employees">
                                        <?php echo $get_policy_item_info['non_entitled_employee_info']; ?>
                                    </div>
                                    <div class="js-hint js-hint-employees2">
                                        <?php echo $get_policy_item_info['entitled_employee_info']; ?>
                                    </div>


                                    <div>
                                        <select class="invoice-fields" name="template[]" id="js-employee-edit-clone" multiple="true">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Applicable on type -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <label><?php echo $get_policy_item_info['employees_type_label']; ?>
                                        <span class="cs-required">*</span>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="employees-type"></i>
                                    </label>
                                    <div class="js-hint js-hint-employees-type">
                                        <?php echo $get_policy_item_info['employees_type_info']; ?></div>
                                    <div>
                                        <select class="invoice-fields" name="templatesadas2[]" id="js-employee-type-edit-clone" multiple="true">
                                            <option value="all">All</option>
                                            <option value="fulltime">Full-time</option>
                                            <option value="parttime">Part-time</option>
                                            <option value="contractual">Contractual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Off days in week -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <label><?php echo $get_policy_item_info['week_off_days_info']; ?>
                                    </label>
                                    <select name="templatedayedit[]" id="js-off-days-edit-clone" multiple="true">
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                        <option value="sunday">Sunday</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb10">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-approver-check-edit-clone" data-type="edit" />
                                            <?php echo $get_policy_item_info['approvers_can_see_label']; ?>
                                            <span class="control__indicator"></span>
                                        </label>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="approvers"></i>
                                        <div class="js-hint js-hint-approvers">The policy will only be visible to selected approvers.</div>
                                    </div>
                                    <!-- To allow approvers -->
                                    <div class="jsMultipleApproverList" data-type="edit">
                                        <br>
                                        <select class="invoice-fields" name="jsMultipleApproversListsEdit[]" id="js-approvers-list-edit-clone" multiple="true"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-archive-check-edit-clone" />
                                            <?php echo $get_policy_item_info['deactivate_label']; ?>
                                            <span class="control__indicator"></span>
                                        </label>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="deactivate"></i>
                                        <div class="js-hint js-hint-deactivate">
                                            <?php echo $get_policy_item_info['deactivate_policy']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-include-check-edit-clone" />
                                            <?php echo $get_policy_item_info['include_in_balance_label']; ?>
                                            <span class="control__indicator"></span>
                                        </label>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="balance"></i>
                                        <div class="js-hint js-hint-balance">
                                            <?php echo $get_policy_item_info['include_in_balance']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-is-esst-edit-clone" />
                                            <?php echo $get_policy_item_info['is_esst_policy_label']; ?>
                                            <span class="control__indicator"></span>
                                        </label>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="balance"></i>
                                        <div class="js-hint js-hint-balance">
                                            <?php echo $get_policy_item_info['is_esst_policy_hint']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FMLA Range -->
                            <div class="row mb10 csRow js-fmla-range-wrap-edit-clone hidden" style="display: none;">
                                <div class="col-sm-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom csHeading">FMLA Range</h4>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-edit" value="standard_year" class="js-fmla-range-edit-clone" />&nbsp;Standard Year (Jan-Dec)
                                            <div class="control__indicator"></div>
                                        </label> <br />
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-edit" value="employee_start_date" class="js-fmla-range-edit-clone" />&nbsp;Employee Start Date
                                            <div class="control__indicator"></div>
                                        </label> <br />
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-edit" value="start_year" class="js-fmla-range-edit-clone" />&nbsp;Starting
                                            from First FMLA usage
                                            <i class="fa fa-question-circle js-popover" data-content="The 12-month period measured forward from the date of your first FMLA leave usage."></i>
                                            <div class="control__indicator"></div>
                                        </label> <br />
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-edit" value="end_year" class="js-fmla-range-edit-clone" />&nbsp;Ending on
                                            your First FMLA usage
                                            <i class="fa fa-question-circle js-popover" data-content="A “rolling” 12-month period measured backward from the date of any FMLA leave usage."></i>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Step buttons -->
                            <div class="js-step-buttons" data-type="clone" data-step="2">
                                <hr />
                                <button class="btn btn-black btn-theme jsViewPoliciesBtn">Cancel</button>
                               <!-- <button class="btn btn-orange jsStepSaveclone">Save</button>-->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 2 - Accrual setting -->
                        <div class="js-step" data-type="clone" data-step="2">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>

                            <!-- Minimum Applicable Hours - Edit -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6">
                                    <!--  -->
                                    <div>
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['minimum_applicable_time_label']; ?> <i class="fa fa-question-circle" data-hint="js-hint" data-target="minimum-hours"></i></h5>
                                        <div class="js-hint js-hint-minimum-hours">
                                            <?php echo $get_policy_item_info['minimum_applicable_hours_info']; ?></div>
                                        <div>
                                            <label class="control control--radio">
                                                Hours &nbsp;&nbsp;
                                                <input type="radio" name="js-minimum-applicable-time-edit" class="js-minimum-applicable-time-edit" checked="true" value="hours" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">
                                                Days &nbsp;&nbsp;
                                                <input type="radio" name="js-minimum-applicable-time-edit" class="js-minimum-applicable-time-edit" value="days" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">
                                                Months &nbsp;&nbsp;
                                                <input type="radio" name="js-minimum-applicable-time-edit" class="js-minimum-applicable-time-edit" value="months" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">
                                                Years &nbsp;&nbsp;
                                                <input type="radio" name="js-minimum-applicable-time-edit" class="js-minimum-applicable-time-edit" value="years" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div style="margin-top: 5px;">
                                            <input class="invoice-fields" name="template" id="js-minimum-applicable-hours-edit-clone" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Accrual Method - Edit -->
                            <div class="row mb10 csRow hidden">
                                <div class="col-sm-6">
                                    <div>
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrual_method_label']; ?> <span class="cs-required">*</span>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="method"></i>
                                        </h5>
                                        <div class="js-hint js-hint-method">
                                            <?php echo $get_policy_item_info['accrual_method_info']; ?>
                                        </div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-accrual-method-edit-clone">
                                                <option value="days_per_year">Days per Year</option>
                                                <option value="hours_per_month" selected="true">Hours per Month</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Accrual Time - Edit -->
                            <div class="row mb10 csRow js-hider-edit">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrual_time_label']; ?> <span class="cs-required">*</span>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="info"></i>
                                        </h5>
                                        <div class="js-hint js-hint-info">
                                            <?php echo $get_policy_item_info['accrual_time_info']; ?></div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-accrual-time-edit-clone">
                                                <option value="none" selected="true">Jan to Dec</option>
                                                <option value="start_of_period">Jan to June</option>
                                                <option value="end_of_period">July to Dec</option>
                                            </select>
                                        </div>
                                        <br />
                                        <p style="color: #c10000; display: none;" id="js-accrual-time-text-edit-clone"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Accrual Frequency - Edit -->
                            <div class="row mb10 csRow js-hider-edit">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrual_frequency_label']; ?> <span class="cs-required">*</span> <i class="fa fa-question-circle" data-hint="js-hint" data-target="frequency"></i></h5>
                                        <div class="js-hint js-hint-frequency">
                                            <?php echo $get_policy_item_info['accrual_frequency_info']; ?></div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-accrual-frequency-edit-clone">
                                                <option value="none" selected="true">None</option>
                                                <option value="yearly">Yearly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                        </div>
                                        <div class="jsCustomBoxAdd" style="margin-top: 5px; display: none;">
                                            <span>Every</span>
                                            <div class="form-group form-group-custom form-group-custom-settings">
                                                <input class="form-control form-control-custom" id="js-accrual-frequency-val-edit-clone" />
                                            </div>
                                            <span> month(s).</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alowed Time -->
                            <div class="row mb10 csRow js-hider-edit">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrual_rate_label']; ?> <span class="cs-required">*</span>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="rate"></i>
                                        </h5>
                                        <div class="js-hint js-hint-rate">
                                            <?php echo $get_policy_item_info['accrual_rate_info']; ?></div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input class="form-control" name="template" id="js-accrual-rate-edit-clone" autocomplete="off" />
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control jsTimeTypeSelect-edit" id="js-accrual-rate-type-edit-clone">
                                                    <option value="days">Day(s)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <small id="jsFormula-edit-clone"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb10">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-accrual-default-flow-edit-clone" />
                                            <?php echo $get_policy_item_info['allowed_accrual_default_flow_label']; ?>
                                            <span class="control__indicator"></span>
                                        </label>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="default-flow"></i>
                                        <div class="js-hint js-hint-default-flow"><?php echo $get_policy_item_info['allowed_accrual_default_flow_info']; ?></div>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row mb10 csRow">
                                <div class="col-lg-6">
                                    <h4 class="timeline-title allowed-time-off-title-custom csHeading">
                                        <?php echo $get_policy_item_info['accruals_plans_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="plans"></i>
                                        <span class="pull-right">
                                            <button class="btn btn-orange js-plan-btn-edit" data-type="edit" style="margin-top: -5px;"><i class="fa fa-plus"></i>&nbsp; Add
                                                Plan</button>
                                        </span>
                                    </h4>
                                    <div class="js-hint js-hint-plans">
                                        <?php echo $get_policy_item_info['accrual_plans']; ?></div>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row mb10 csRow js-hider-edit">
                                <!-- Plans area -->
                                <div class="col-sm-12 col-xs-12 jsPlanArea" id="js-plan-area-edit-clone"></div>
                            </div>

                            <!--  -->
                            <div class="js-step-buttons" data-type="clone" data-step="3">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                               <!-- <button class="btn btn-orange jsStepSaveclone">Save</button>-->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 3 - Carryover -->
                        <div class="js-step" data-type="clone" data-step="3">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>
                            <!-- Carryover - Edit -->
                            <div class="row mb10 csRow js-hider-edit-clone">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['carry_over_label']; ?> <span class="cs-required">*</span>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="carycheck"></i>
                                        </h5>
                                        <div class="js-hint js-hint-carycheck">
                                            <?php echo $get_policy_item_info['allow_carryover_cap_info']; ?></div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-carryover-cap-check-edit-clone">
                                                <option value="yes" selected="true">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carryover Value - Edit -->
                            <div class="row mb10 csRow js-hider-edit js-carryover-box-edit">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['carryover_cap_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="caryval"></i>
                                        </h5>
                                        <div class="js-hint js-hint-caryval">
                                            <?php echo $get_policy_item_info['carryover_cap_info']; ?></div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input class="form-control" name="template" id="js-carryover-cap-edit-clone" />
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control jsTimeTypeSelect-edit" id="js-accrual-carryover-type-edit-clone"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Carryover Cycle - Edit -->
                            <div class="row mb10 csRow js-hider-edit js-carryover-box-edit">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['carryover_cycle_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="caryCycle"></i>
                                        </h5>
                                        <div class="js-hint js-hint-caryCycle">
                                            <?php echo $get_policy_item_info['carryover_cycle_info']; ?></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input class="form-control" name="template" id="js-carryover-cycle-edit-clone" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="clone" data-step="4">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                               <!-- <button class="btn btn-orange jsStepSaveclone">Save</button>-->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 4 - Negative Balance -->
                        <div class="js-step" data-type="clone" data-step="4">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>
                            <!-- Negative Balance - Edit -->
                            <div class="row mb10 csRow js-hider-edit">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['negative_balance_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="negativecheck"></i>
                                        </h5>
                                        <div class="js-hint js-hint-negativecheck">
                                            <?php echo $get_policy_item_info['allow_negative_balance_info']; ?></div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-accrual-balance-edit-clone">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Negative Balance - Edit -->
                            <div class="row mb10 csRow js-hider-edit js-negative-box-edit">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['negative_balance_limit_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="negativeval"></i>
                                        </h5>
                                        <div class="js-hint js-hint-negativeval">
                                            <?php echo $get_policy_item_info['new_hire_maximum_days_off_HD_info']; ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input class="form-control" name="template" id="js-maximum-balance-edit-clone" />
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control jsTimeTypeSelect-edit" id="js-accrual-negative-balance-type-edit-clone"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="clone" data-step="5">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                               <!-- <button class="btn btn-orange jsStepSaveclone">Save</button> -->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 5 - Applicable Date -->
                        <div class="js-step" data-type="clone" data-step="5">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>
                            <!-- Policy Implement Date - Edit -->
                            <div class="row mb10 csRow">
                                <div class="col-lg-12">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['applicable_date_label']; ?> <i class="fa fa-question-circle" data-hint="js-hint" data-target="applicable-date"></i></h5>
                                    <div class="js-hint js-hint-applicable-date">
                                        <?php echo $get_policy_item_info['applicable_date_for_policy_info']; ?>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="control control--radio">
                                            <?php echo $get_policy_item_info['employee_joining_date_label']; ?>
                                            <input type="radio" name="js-hire-date-edit" checked="true" value="hireDate" class="js-hire-date-edit" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <br />
                                        <label class="control control--radio">
                                            <?php echo $get_policy_item_info['pick_a_date_label']; ?>
                                            <input type="radio" name="js-hire-date-edit" value="customHireDate" class="js-hire-date-edit" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <div class="jsImplementDateBox-edit" style="display: none; margin-top: 5px;">
                                            <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-edit-clone" />
                                        </div>
                                    </div>
                                    <br />
                                    <p style="color: #c10000; display: none;" id="js-applicable-date-text-edit-clone">
                                        <i><?php echo $get_policy_item_info['applicable_date_msg_label']; ?></i>
                                    </p>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="clone" data-step="6">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                              <!--  <button class="btn btn-orange jsStepSaveclone">Save</button> -->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>


                        <!-- Step 6 - Reset Date -->
                        <div class="js-step" data-type="clone" data-step="6">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>

                            <!-- Policy Reset Date - Edit  -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['reset_date_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="reset-date"></i>
                                        </h5>
                                        <div class="js-hint js-hint-reset-date">
                                            <?php echo $get_policy_item_info['reset_date_info']; ?>
                                        </div>
                                        <div class="">
                                            <label class="control control--radio">
                                                <?php echo $get_policy_item_info['reset_date_1_label']; ?>
                                                <input type="radio" name="js-policy-reset-date-edit" class="js-policy-reset-date-edit" checked="true" value="policyDate" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <br />
                                            <label class="control control--radio">
                                                <?php echo $get_policy_item_info['reset_date_2_label']; ?>
                                                <input type="radio" name="js-policy-reset-date-edit" class="js-policy-reset-date-edit" value="policyDateCustom" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <div class="jsResetDateBox-edit" style="display: none; margin-top: 5px;">
                                                <input type="text" readonly="true" class="invoice-fields" id="js-custom-reset-date-edit-clone" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="clone" data-step="7">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                               <!-- <button class="btn btn-orange jsStepSaveclone">Save</button>-->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 7 - New Hire -->
                        <div class="js-step" data-type="clone" data-step="7">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <p style="color: #cc1100; margin-top: 10px;">
                                        <b><?php echo $get_policy_item_info['new_hire_tag']; ?></b>
                                    </p>
                                    <br />
                                </div>
                            </div>

                            <!--  -->
                            <div class="row mb10 csRow">
                                <div class="col-lg-12 js-hider-edit accrual-settings-line-1 mb10">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['probation_period_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="waiting"></i>
                                    </h5>
                                    <div class="js-hint js-hint-waiting">
                                        <?php echo $get_policy_item_info['waiting_period_info']; ?></div>
                                    <span></span>
                                    <div class="form-group form-group-custom form-group-custom-settings">
                                        <input class="form-control" id="js-accrue-new-hire-edit-clone" />
                                    </div><span> <select class="form-control" style="width: 200px; display: inline;" id="js-accrual-new-hire-time-type-edit-clone">
                                            <option value="hours">Hours</option>
                                            <option value="days">Days</option>
                                            <option value="months">Months</option>
                                            <!-- <option value="per_week">Per Week</option>
                        <option value="per_month">Per Month</option> -->
                                        </select></span>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row mb10 csRow js-hider-edit">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['probation_period_rate_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="newhiretime"></i>
                                        </h5>
                                        <div class="js-hint js-hint-newhiretime">
                                            <?php echo $get_policy_item_info['new_hire_maximum_days_off_info']; ?></div>
                                        <div class="input-group">
                                            <input class="form-control" name="template" id="js-newhire-prorate-edit-clone" />
                                            <span class="input-group-addon jsTimeType-edit">Day(s)</span>
                                        </div>
                                    </div>
                                    <p class="js-newhire-text-edit"></p>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="clone" data-step="8">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                                <button class="btn btn-orange js-to-step-clone">Save & Finish</button>
                            </div>
                        </div>
                    </div>
                    <!-- Edit page ends -->

                    <div id="jsResetPageClone" style="display: none;">
                        <?php $this->load->view('timeoff/partials/steps', ['stepType' => 'reset']); ?>

                        <!-- Step 1 - Basic Information -->
                        <div class="js-step" data-type="reset" data-step="1">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>

                            <!-- Policy Type - reset -->
                            <div class="row mb10 csRow" id="js-policy-categories-reset">
                                <div class="col-md-6 offset-md-3">
                                    <div class="form-group margin-bottom-custom">
                                        <label><?php echo $get_policy_item_info['policy_type_label']; ?> <span class="cs-required">*</span> <i class="fa fa-question-circle" data-hint="js-hint" data-target="type"></i></label>
                                        <div class="js-hint js-hint-type">
                                            <?php echo $get_policy_item_info['type_info']; ?>
                                        </div>
                                        <div>
                                            <select id="js-category-reset"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Policy Ttile - reset -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group margin-bottom-custom">
                                        <div class="form-group margin-bottom-custom">
                                            <label class=""><?php echo $get_policy_item_info['policy_title_label']; ?>
                                                <span class="cs-required">*</span>
                                                <i class="fa fa-question-circle" data-hint="js-hint" data-target="info"></i>
                                            </label>
                                            <div class="js-hint js-hint-info">
                                                <?php echo $get_policy_item_info['policy_title_info']; ?>
                                            </div>
                                            <div>
                                                <input class="invoice-fields" name="policyTitle" id="js-policy-title-reset" placeholder="Sick leave" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Policy Sort Order - reset -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <label><?php echo $get_policy_item_info['sort_order_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="sort"></i>
                                    </label>
                                    <div class="js-hint js-hint-sort">
                                        <?php echo $get_policy_item_info['sort_order_info']; ?></div>
                                    <div>
                                        <input class="invoice-fields" name="template" id="js-sort-order-reset" />
                                    </div>
                                </div>
                            </div>

                            <!-- Policy Entitled Employees - reset -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <label><?php echo $get_policy_item_info['non_employees_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="employees"></i>
                                    </label>
                                    <div class="js-hint js-hint-employees">
                                        <?php echo $get_policy_item_info['entitled_employee_info']; ?>
                                    </div>
                                    <div>
                                        <select class="invoice-fields" name="template[]" id="js-employee-reset" multiple="true">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Applicable on type -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <label><?php echo $get_policy_item_info['non_employees_label']; ?>
                                        <span class="cs-required">*</span>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="employees"></i>
                                    </label>
                                    <div class="js-hint js-hint-employees">
                                        <?php echo $get_policy_item_info['entitled_employee_info']; ?></div>
                                    <div>
                                        <select class="invoice-fields" name="templatesadasdas2[]" id="js-employee-type-reset" multiple="true">
                                            <option value="all">All</option>
                                            <option value="fulltime">Full-time</option>
                                            <option value="parttime">Part-time</option>
                                            <option value="contractual">Contractual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-approver-check-reset" />
                                            <?php echo $get_policy_item_info['approvers_can_see_label']; ?>
                                            <span class="control__indicator"></span>
                                        </label>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="approvers"></i>
                                        <div class="js-hint js-hint-approvers">
                                            <?php echo $get_policy_item_info['approvers_only']; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-archive-check-reset" />
                                            <?php echo $get_policy_item_info['deactivate_label']; ?>
                                            <span class="control__indicator"></span>
                                        </label>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="deactivate"></i>
                                        <div class="js-hint js-hint-deactivate">
                                            <?php echo $get_policy_item_info['deactivate_policy']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb10 csRow">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-include-check-reset" />
                                            <?php echo $get_policy_item_info['include_in_balance_label']; ?>
                                            <span class="control__indicator"></span>
                                        </label>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="balance"></i>
                                        <div class="js-hint js-hint-balance">
                                            <?php echo $get_policy_item_info['include_in_balance']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FMLA Range -->
                            <div class="row mb10 csRow js-fmla-range-wrap-reset hidden" style="display: none;">
                                <div class="col-sm-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom csHeading">FMLA Range</h4>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-reset" value="standard_year" class="js-fmla-range-reset" />&nbsp;Standard Year (Jan-Dec)
                                            <div class="control__indicator"></div>
                                        </label> <br />
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-reset" value="employee_start_date" class="js-fmla-range-reset" />&nbsp;Employee Start Date
                                            <div class="control__indicator"></div>
                                        </label> <br />
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-reset" value="start_year" class="js-fmla-range-reset" />&nbsp;Starting
                                            from First FMLA usage
                                            <i class="fa fa-question-circle js-popover" data-content="The 12-month period measured forward from the date of your first FMLA leave usage."></i>
                                            <div class="control__indicator"></div>
                                        </label> <br />
                                        <label class="control control--radio">
                                            <input type="radio" name="fmla-range-reset" value="end_year" class="js-fmla-range-reset" />&nbsp;Ending on
                                            your First FMLA usage
                                            <i class="fa fa-question-circle js-popover" data-content="A “rolling” 12-month period measured backward from the date of any FMLA leave usage."></i>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Step buttons -->
                            <div class="js-step-buttons" data-type="reset" data-step="2">
                                <hr />
                                <button class="btn btn-black btn-theme jsViewPoliciesBtn">Cancel</button>
                               <!-- <button class="btn btn-orange jsStepSaveReset">Save</button> -->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 2 - Accrual setting -->
                        <div class="js-step" data-type="reset" data-step="2">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>

                            <!-- Minimum Applicable Hours - reset -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6">
                                    <!--  -->
                                    <div>
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['minimum_applicable_time_label']; ?> <i class="fa fa-question-circle" data-hint="js-hint" data-target="minimum-hours"></i></h5>
                                        <div class="js-hint js-hint-minimum-hours">
                                            <?php echo $get_policy_item_info['minimum_applicable_hours_info']; ?></div>
                                        <div>
                                            <label class="control control--radio">
                                                Hours &nbsp;&nbsp;
                                                <input type="radio" name="js-minimum-applicable-time-reset" class="js-minimum-applicable-time-reset" checked="true" value="hours" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">
                                                Days &nbsp;&nbsp;
                                                <input type="radio" name="js-minimum-applicable-time-reset" class="js-minimum-applicable-time-reset" value="days" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">
                                                Months &nbsp;&nbsp;
                                                <input type="radio" name="js-minimum-applicable-time-reset" class="js-minimum-applicable-time-reset" value="months" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">
                                                Years &nbsp;&nbsp;
                                                <input type="radio" name="js-minimum-applicable-time-reset" class="js-minimum-applicable-time-reset" value="years" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div style="margin-top: 5px;">
                                            <input class="invoice-fields" name="template" id="js-minimum-applicable-hours-reset" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Accrual Method - reset -->
                            <div class="row mb10 csRow hidden">
                                <div class="col-sm-6">
                                    <div>
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrual_method_label']; ?> <span class="cs-required">*</span>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="method"></i>
                                        </h5>
                                        <div class="js-hint js-hint-method">
                                            <?php echo $get_policy_item_info['accrual_method_info']; ?>
                                        </div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-accrual-method-reset">
                                                <option value="days_per_year">Days per Year</option>
                                                <option value="hours_per_month" selected="true">Hours per Month</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Accrual Time - reset -->
                            <div class="row mb10 csRow js-hider-reset">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrual_time_label']; ?> <span class="cs-required">*</span>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="info"></i>
                                        </h5>
                                        <div class="js-hint js-hint-info">
                                            <?php echo $get_policy_item_info['accrual_time_info']; ?></div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-accrual-time-reset">
                                                <option value="none" selected="true">Jan to Dec</option>
                                                <option value="start_of_period" selected="true">Jan to Jun</option>
                                                <option value="end_of_period">Jul to Dec</option>
                                            </select>
                                        </div>
                                        <br />
                                        <p style="color: #c10000; display: none;" id="js-accrual-time-text-reset"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Accrual Frequency - reset -->
                            <div class="row mb10 csRow js-hider-reset">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrual_frequency_label']; ?> <span class="cs-required">*</span> <i class="fa fa-question-circle" data-hint="js-hint" data-target="frequency"></i></h5>
                                        <div class="js-hint js-hint-frequency">
                                            <?php echo $get_policy_item_info['accrual_frequency_info']; ?></div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-accrual-frequency-reset">
                                                <option value="none" selected="true">None</option>
                                                <option value="yearly">Yearly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                        </div>
                                        <div class="jsCustomBoxAdd" style="margin-top: 5px; display: none;">
                                            <span>Every</span>
                                            <div class="form-group form-group-custom form-group-custom-settings">
                                                <input class="form-control form-control-custom" id="js-accrual-frequency-val-reset" />
                                            </div>
                                            <span> month(s).</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alowed Time -->
                            <div class="row mb10 csRow js-hider-reset">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrual_rate_label']; ?> <span class="cs-required">*</span>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="rate"></i>
                                        </h5>
                                        <div class="js-hint js-hint-rate">
                                            <?php echo $get_policy_item_info['accrual_rate_info']; ?></div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input class="form-control" name="template" id="js-accrual-rate-reset" autocomplete="off" />
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control jsTimeTypeSelect-reset" id="js-accrual-rate-type-reset">
                                                    <option value="days">Day(s)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <small id="jsFormula-reset"></small>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row mb10 csRow">
                                <div class="col-lg-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom csHeading">
                                        <?php echo $get_policy_item_info['accruals_plans_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="plans"></i>
                                        <span class="pull-right">
                                            <button class="btn btn-orange js-plan-btn-reset" data-type="reset" style="margin-top: -5px;"><i class="fa fa-plus"></i>&nbsp; Add
                                                Plan</button>
                                        </span>
                                    </h4>
                                    <div class="js-hint js-hint-plans">
                                        <?php echo $get_policy_item_info['accrual_plans']; ?></div>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row mb10 csRow js-hider-reset">
                                <!-- Plans area -->
                                <div class="col-sm-12 col-xs-12 jsPlanArea" id="js-plan-area-reset"></div>
                            </div>

                            <!--  -->
                            <div class="js-step-buttons" data-type="reset" data-step="3">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                              <!--  <button class="btn btn-orange jsStepSaveReset">Save</button> -->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 3 - Carryover -->
                        <div class="js-step" data-type="reset" data-step="3">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>
                            <!-- Carryover - reset -->
                            <div class="row mb10 csRow js-hider-reset">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['carry_over_label']; ?> <span class="cs-required">*</span>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="carycheck"></i>
                                        </h5>
                                        <div class="js-hint js-hint-carycheck">
                                            <?php echo $get_policy_item_info['allow_carryover_cap_info']; ?></div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-carryover-cap-check-reset">
                                                <option value="yes" selected="true">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carryover Value - reset -->
                            <div class="row mb10 csRow js-hider-reset js-carryover-box-reset">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['carryover_cap_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="caryval"></i>
                                        </h5>
                                        <div class="js-hint js-hint-caryval">
                                            <?php echo $get_policy_item_info['carryover_cap_info']; ?></div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input class="form-control" name="template" id="js-carryover-cap-reset" />
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control jsTimeTypeSelect-reset" id="js-accrual-carryover-type-reset"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="reset" data-step="4">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                               <!-- <button class="btn btn-orange jsStepSaveReset">Save</button> -->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 4 - Negative Balance -->
                        <div class="js-step" data-type="reset" data-step="4">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>
                            <!-- Negative Balance - reset -->
                            <div class="row mb10 csRow js-hider-reset">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['negative_balance_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="negativecheck"></i>
                                        </h5>
                                        <div class="js-hint js-hint-negativecheck">
                                            <?php echo $get_policy_item_info['allow_negative_balance_info']; ?></div>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-accrual-balance-reset">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Negative Balance - reset -->
                            <div class="row mb10 csRow js-hider-reset js-negative-box-reset">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['negative_balance_limit_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="negativeval"></i>
                                        </h5>
                                        <div class="js-hint js-hint-negativeval">
                                            <?php echo $get_policy_item_info['new_hire_maximum_days_off_HD_info']; ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input class="form-control" name="template" id="js-maximum-balance-reset" />
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control jsTimeTypeSelect-reset" id="js-accrual-negative-balance-type-reset"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="reset" data-step="5">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                               <!-- <button class="btn btn-orange jsStepSaveReset">Save</button> -->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 5 - Applicable Date -->
                        <div class="js-step" data-type="reset" data-step="5">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>
                            <!-- Policy Implement Date - reset -->
                            <div class="row mb10 csRow">
                                <div class="col-lg-12">
                                    <h5 class="timeline-title allowed-time-off-title-custom">Applicable date for policy
                                        to
                                        take affect <i class="fa fa-question-circle" data-hint="js-hint" data-target="applicable-date"></i></h5>
                                    <div class="js-hint js-hint-applicable-date">
                                        <?php echo $get_policy_item_info['applicable_date_for_policy_info']; ?>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="control control--radio">
                                            <?php echo $get_policy_item_info['employee_joining_date_label']; ?>
                                            <input type="radio" name="js-hire-date-reset" checked="true" value="hireDate" class="js-hire-date-reset" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <br />
                                        <label class="control control--radio">
                                            <?php echo $get_policy_item_info['pick_a_date_label']; ?>
                                            <input type="radio" name="js-hire-date-reset" value="customHireDate" class="js-hire-date-reset" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <div class="jsImplementDateBox-reset" style="display: none; margin-top: 5px;">
                                            <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-reset" />
                                        </div>
                                    </div>
                                    <br />
                                    <p style="color: #c10000; display: none;" id="js-applicable-date-text-reset">
                                        <i>This policy will affect the balances of entitled employee(s).</i>
                                    </p>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="reset" data-step="6">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                               <!-- <button class="btn btn-orange jsStepSaveReset">Save</button> -->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 6 - Reset Date -->
                        <div class="js-step" data-type="reset" data-step="6">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>

                            <!-- Policy Reset Date - reset  -->
                            <div class="row mb10 csRow">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['reset_date_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="reset-date"></i>
                                        </h5>
                                        <div class="js-hint js-hint-reset-date">
                                            <?php echo $get_policy_item_info['reset_date_info']; ?>
                                        </div>
                                        <div class="">
                                            <label class="control control--radio">
                                                <?php echo $get_policy_item_info['reset_date_1_label']; ?>
                                                <input type="radio" name="js-policy-reset-date-reset" class="js-policy-reset-date-reset" checked="true" value="policyDate" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <br />
                                            <label class="control control--radio">
                                                <?php echo $get_policy_item_info['reset_date_2_label']; ?>
                                                <input type="radio" name="js-policy-reset-date-reset" class="js-policy-reset-date-reset" value="policyDateCustom" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <div class="jsResetDateBox-reset" style="display: none; margin-top: 5px;">
                                                <input type="text" readonly="true" class="invoice-fields" id="js-custom-reset-date-reset" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="reset" data-step="7">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                                <!--<button class="btn btn-orange jsStepSaveclone">Save</button>-->
                                <button class="btn btn-orange js-to-step-clone">Next</button>
                            </div>
                        </div>

                        <!-- Step 7 - New Hire -->
                        <div class="js-step" data-type="reset" data-step="7">
                            <!--  -->
                            <?php $this->load->view('timeoff/partials/note'); ?>


                            <div class="row">
                                <div class="col-lg-12">
                                    <p style="color: #cc1100; margin-top: 10px;">
                                        <b><?php echo $get_policy_item_info['new_hire_tag']; ?></b>
                                    </p>
                                    <br />
                                </div>
                            </div>

                            <!--  -->
                            <div class="row mb10 csRow">
                                <div class="col-lg-12 js-hider-reset accrual-settings-line-1 mb10">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['probation_period_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="waiting"></i>
                                    </h5>
                                    <div class="js-hint js-hint-waiting">
                                        <?php echo $get_policy_item_info['waiting_period_info']; ?></div>
                                    <span></span>
                                    <div class="form-group form-group-custom form-group-custom-settings">
                                        <input class="form-control" id="js-accrue-new-hire-reset" />
                                    </div><span> <select class="form-control" style="width: 200px; display: inline;" id="js-accrual-new-hire-time-type-reset">
                                            <option value="hours">Hours</option>
                                            <option value="days">Days</option>
                                            <option value="months">Months</option>
                                            <option value="per_week">Per Week</option>
                                            <option value="per_month">Per Month</option>
                                        </select></span>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row mb10 csRow js-hider-reset">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['probation_period_rate_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="newhiretime"></i>
                                        </h5>
                                        <div class="js-hint js-hint-newhiretime">
                                            <?php echo $get_policy_item_info['new_hire_maximum_days_off_info']; ?></div>
                                        <div class="input-group">
                                            <input class="form-control" name="template" id="js-newhire-prorate-reset" />
                                            <span class="input-group-addon jsTimeType-reset">Day(s)</span>
                                        </div>
                                    </div>
                                    <p class="js-newhire-text-reset"></p>
                                </div>
                            </div>
                            <!--  -->
                            <div class="js-step-buttons" data-type="reset" data-step="8">
                                <hr />
                                <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                                <button class="btn btn-orange js-to-step-clone">Save & Finish</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>