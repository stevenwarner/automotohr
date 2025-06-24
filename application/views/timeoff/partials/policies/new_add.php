<div class="js-page p10" id="js-page-add" style="display: none;">

    <div class="csPageHeader <?php echo $this->agent->is_mobile() ? 'csMobileWrap' : ''; ?>">
        <div class="row">
            <div class="col-sm-6">
                <h4>
                    Add Policy <strong id="jsPolicyTitleAdd"></strong>
                </h4>
            </div>
            <div class="col-sm-6">
                <span class="pull-right">
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
                <?php $this->load->view('timeoff/partials/new_steps', ['stepType' => 'add']); ?>
            </div>

            <!-- Content -->
            <div class="col-sm-10">
                <div class="csProcessWrap">

                    <div class="js-step" data-type="add" data-step="0">
                        <!-- Policy Type - Edit -->
                        <div class="row mb10 csRow" id="js-policy-categories-add">
                            <div class="col-md-12 offset-md-3">
                                <div class="form-group margin-bottom-custom">
                                    <label><?php echo $get_policy_item_info['select_a_template_label']; ?> <span class="cs-required">*</span></label>
                                    <br />
                                    <div>
                                        <?php foreach ($templates as $template) : ?>
                                            <?php if (!$template['show']) continue; ?>
                                            <label class="control control--radio">
                                                <?= $template['title']; ?>
                                                <input type="radio" name="template-add" class="js-template-add" value="<?= $template['value']; ?>" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <br />
                                        <?php endforeach; ?>
                                        <label class="control control--radio">
                                            Custom
                                            <input type="radio" name="template-add" class="js-template-add" value="0" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Step buttons -->
                        <div class="js-step-buttons" data-type="add" data-step="1">
                            <hr />
                            <button class="btn btn-black btn-theme jsViewPoliciesBtn">Cancel</button>
                            <button class="btn btn-orange js-to-step">Next</button>
                        </div>
                    </div>

                    <!-- Step 1 - Basic Information -->
                    <div class="js-step" data-type="add" data-step="1">
                        <!--  -->
                        <?php $this->load->view('timeoff/partials/note'); ?>

                        <!-- Policy Type - Edit -->
                        <div class="row mb10 csRow" id="js-policy-type-box-add">
                            <div class="col-md-6 offset-md-3">
                                <div class="form-group margin-bottom-custom">
                                    <label>Policy Category<span class="cs-required">*</span> <i class="fa fa-question-circle" data-hint="js-hint" data-target="type"></i></label>
                                    <div class="js-hint js-hint-type">Policy can be paid or unpaid
                                    </div>
                                    <div>
                                        <select id="js-policy-type-add">
                                            <option value="0">Unpaid</option>
                                            <option value="1">Paid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Policy Type - Edit -->
                        <div class="row mb10 csRow" id="js-policy-categories-add">
                            <div class="col-md-6 offset-md-3">
                                <div class="form-group margin-bottom-custom">
                                    <label><?php echo $get_policy_item_info['policy_type_label']; ?> <span class="cs-required">*</span> <i class="fa fa-question-circle" data-hint="js-hint" data-target="type"></i></label>
                                    <div class="js-hint js-hint-type"><?php echo $get_policy_item_info['type_info']; ?>
                                    </div>
                                    <div>
                                        <select id="js-category-add"></select>
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
                                            <?php echo $get_policy_item_info['policy_title_info']; ?></div>
                                        <div>
                                            <input class="invoice-fields" name="policyTitle" id="js-policy-title-add" placeholder="Sick leave" />
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
                                    <input class="invoice-fields" name="template" id="js-sort-order-add" />
                                </div>
                            </div>
                        </div>

                        <!-- Policy Entitled Employees - Edit -->
                        <div class="row mb10 csRow">
                            <div class="col-sm-6 col-xs-12">
                                <label class="control control--radio">
                                    <?php echo $get_policy_item_info['non_employees_label']; ?>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="employees"></i>

                                    <input type="radio" class="jsIsEntitledEmployee" name="is_entitled_employee" value="0" id="NonEntitledEmployeesadd" checked="checked">
                                    <div class="control__indicator"></div>
                                </label>
                                <br>
                                <label class="control control--radio">
                                    <?php echo $get_policy_item_info['entitled_employee_label']; ?>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="employees2"></i>

                                    <input type="radio" class="jsIsEntitledEmployee" name="is_entitled_employee" value="1" id="EntitledEmployeesadd" />
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
                                    <select class="invoice-fields" name="template[]" id="js-employee-add" multiple="true">
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
                                    <select class="invoice-fields" name="template22[]" id="js-employee-type-add" multiple="true">
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
                                <select name="templatedayadd[]" id="js-off-days-add" multiple="true">
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
                                        <input type="checkbox" id="js-approver-check-add" data-type="add" />
                                        <?php echo $get_policy_item_info['approvers_can_see_label']; ?>
                                        <span class="control__indicator"></span>
                                    </label>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="approvers"></i>
                                    <div class="js-hint js-hint-approvers">The policy will only be visible to selected approvers.</div>
                                </div>
                                <!-- To allow approvers -->
                                <div class="jsMultipleApproverList" data-type="add">
                                    <br>
                                    <select class="invoice-fields" name="jsMultipleApproversLists[]" id="js-approvers-list-add" multiple="true"></select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb10 csRow">
                            <div class="col-sm-6 col-xs-12">
                                <div class="">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" id="js-archive-check-add" />
                                        <?php echo $get_policy_item_info['deactivate_label']; ?>
                                        <span class="control__indicator"></span>
                                    </label>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="deactivate"></i>
                                    <div class="js-hint js-hint-deactivate">
                                        <?php echo $get_policy_item_info['deactivate_policy']; ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb10 csRow">
                            <div class="col-sm-6 col-xs-12">
                                <div class="">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" id="js-include-check-add" />
                                        <?php echo $get_policy_item_info['include_in_balance_label']; ?>
                                        <span class="control__indicator"></span>
                                    </label>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="balance"></i>
                                    <div class="js-hint js-hint-balance">
                                        <?php echo $get_policy_item_info['include_in_balance']; ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb10 csRow">
                            <div class="col-sm-6 col-xs-12">
                                <div class="">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" id="js-is-esst-add" />
                                        <?php echo $get_policy_item_info['is_esst_policy_label']; ?>
                                        <span class="control__indicator"></span>
                                    </label>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="esst"></i>
                                    <div class="js-hint js-hint-esst">
                                        <?php echo $get_policy_item_info['is_esst_policy_hint']; ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb10 csRow">
                            <div class="col-sm-6 col-xs-12">
                                <div class="">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" id="js-is-esta-add" />
                                        <?php echo $get_policy_item_info['is_esta_policy_label']; ?>
                                        <span class="control__indicator"></span>
                                    </label>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="esta"></i>
                                    <div class="js-hint js-hint-esta">
                                        <?php echo $get_policy_item_info['is_esta_policy_hint']; ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb10 csRow">
                            <div class="col-sm-6 col-xs-12">
                                <div class="">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" id="jsPartTimePolicy" />
                                        <?php echo $get_policy_item_info['custom_policy_label']; ?>
                                        <span class="control__indicator"></span>
                                    </label>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="custom"></i>
                                    <div class="js-hint js-hint-custom">
                                        <?php echo $get_policy_item_info['custom_policy_hint']; ?></div>
                                </div>
                            </div>
                        </div>
                                            
                        <!-- PartTime Policy values - add -->
                        <div class="jsPartTimePolicySection" style="display: none;">
                            <div class="row mb10 csRow">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['waiting_period_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="waiting_period"></i>
                                        </h5>
                                        <div class="js-hint js-hint-waiting_period">
                                            <?php echo $get_policy_item_info['waiting_period_hint']; ?></div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input class="form-control" name="template" id="jsWaitingPeriodValue" />
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control" id="jsWaitingPeriodType">
                                                    <option value="days">Days</option>
                                                    <option value="weeks">Weeks</option>
                                                    <option value="months">Months</option>
                                                </select>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row mb10 csRow">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['maximum_allowed_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="maximum_allowed"></i>
                                        </h5>
                                        <div class="js-hint js-hint-maximum_allowed">
                                            <?php echo $get_policy_item_info['maximum_allowed_hint']; ?></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input class="form-control" name="template" id="jsMaximumAllowedValue" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row mb10 csRow">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <h5 class="timeline-title allowed-time-off-title-custom">
                                            <?php echo $get_policy_item_info['accrue_time_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="accrue_time"></i>
                                        </h5>
                                        <div class="js-hint js-hint-accrue_time">
                                            <?php echo $get_policy_item_info['accrue_time_hint']; ?></div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input class="form-control" name="template" id="jsAccrueValue" />
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control" id="jsAccrueType">
                                                    <option value="per_week">Per Week</option>
                                                    <option value="per_month">Per Month</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <!-- FMLA Range -->
                        <div class="row mb10 csRow js-fmla-range-wrap-add hidden" style="display: none;">
                            <div class="col-sm-12">
                                <h4 class="timeline-title allowed-time-off-title-custom csHeading">FMLA Range</h4>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label class="control control--radio">
                                        <input type="radio" name="fmla-range-add" value="standard_year" class="js-fmla-range-add" />&nbsp;Standard Year (Jan-Dec)
                                        <div class="control__indicator"></div>
                                    </label> <br />
                                    <label class="control control--radio">
                                        <input type="radio" name="fmla-range-add" value="employee_start_date" class="js-fmla-range-add" />&nbsp;Employee Start Date
                                        <div class="control__indicator"></div>
                                    </label> <br />
                                    <label class="control control--radio">
                                        <input type="radio" name="fmla-range-add" value="start_year" class="js-fmla-range-add" />&nbsp;Starting
                                        from First FMLA usage
                                        <i class="fa fa-question-circle js-popover" data-content="The 12-month period measured forward from the date of your first FMLA leave usage."></i>
                                        <div class="control__indicator"></div>
                                    </label> <br />
                                    <label class="control control--radio">
                                        <input type="radio" name="fmla-range-add" value="end_year" class="js-fmla-range-add" />&nbsp;Ending
                                        on
                                        your First FMLA usage
                                        <i class="fa fa-question-circle js-popover" data-content="A “rolling” 12-month period measured backward from the date of any FMLA leave usage."></i>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Step buttons -->
                        <div class="js-step-buttons" data-type="add" data-step="2">
                            <hr />
                            <button class="btn btn-black btn-theme js-to-step-back">Back To Templates</button>
                            <button class="btn btn-orange js-to-step">Next</button>
                        </div>
                    </div>

                    <!-- Step 2 - Accrual setting -->
                    <div class="js-step" data-type="add" data-step="2">
                        <!--  -->
                        <?php $this->load->view('timeoff/partials/note'); ?>

                        <!-- Minimum Applicable Hours - Edit -->
                        <div class="row mb10 csRow">
                            <div class="col-sm-6">
                                <!--  -->
                                <div>
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['minimum_applicable_time_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="minimum-hours"></i>
                                    </h5>
                                    <div class="js-hint js-hint-minimum-hours">
                                        <?php echo $get_policy_item_info['minimum_applicable_hours_info']; ?></div>
                                    <div>
                                        <label class="control control--radio">
                                            Hours &nbsp;&nbsp;
                                            <input type="radio" name="js-minimum-applicable-time-add" class="js-minimum-applicable-time-add" checked="true" value="hours" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            Days &nbsp;&nbsp;
                                            <input type="radio" name="js-minimum-applicable-time-add" class="js-minimum-applicable-time-add" value="days" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            Months &nbsp;&nbsp;
                                            <input type="radio" name="js-minimum-applicable-time-add" class="js-minimum-applicable-time-add" value="months" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            Years &nbsp;&nbsp;
                                            <input type="radio" name="js-minimum-applicable-time-add" class="js-minimum-applicable-time-add" value="years" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div style="margin-top: 5px;">
                                        <input class="invoice-fields" name="template" id="js-minimum-applicable-hours-add" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <!-- Accrual Method - Edit -->
                        <div class="row mb10 csRow hidden">
                            <div class="col-sm-6">
                                <div>
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['accrual_method_label']; ?> <span class="cs-required">*</span>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="method"></i>
                                    </h5>
                                    <div class="js-hint js-hint-method">
                                        <?php echo $get_policy_item_info['accrual_method_info']; ?></div>
                                    <div>
                                        <select class="invoice-fields" name="template" id="js-accrual-method-add">
                                            <option value="days_per_year">Days per Year</option>
                                            <option value="hours_per_month" selected="true">Hours per Month</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accrual Time - Edit -->
                        <div class="row mb10 csRow js-hider-add">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['accrual_time_label']; ?> <span class="cs-required">*</span>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="info"></i>
                                    </h5>
                                    <div class="js-hint js-hint-info">
                                        <?php echo $get_policy_item_info['accrual_time_info']; ?></div>
                                    <div>
                                        <select class="invoice-fields" name="templateer" id="js-accrual-time-add">
                                            <option value="none" selected="true">Jan To Dec</option>
                                            <option value="start_of_period">Jan to Jun</option>
                                            <option value="end_of_period">Jul to Dec</option>
                                        </select>
                                    </div>
                                    <br />
                                    <p style="color: #c10000; display: none;" id="js-accrual-time-text-add"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Accrual Frequency - Edit -->
                        <div class="row mb10 csRow js-hider-add">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['accrual_frequency_label']; ?> <span class="cs-required">*</span> <i class="fa fa-question-circle" data-hint="js-hint" data-target="frequency"></i></h5>
                                    <div class="js-hint js-hint-frequency">
                                        <?php echo $get_policy_item_info['accrual_frequency_info']; ?>
                                    </div>
                                    <div>
                                        <select class="invoice-fields" name="template" id="js-accrual-frequency-add">
                                            <option value="none" selected="true">None</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                            <option value="custom">Custom</option>
                                        </select>
                                    </div>
                                    <div class="jsCustomBoxAdd" style="margin-top: 5px; display: none;">
                                        <span>Every</span>
                                        <div class="form-group form-group-custom form-group-custom-settings">
                                            <input class="form-control form-control-custom" id="js-accrual-frequency-val-add" />
                                        </div>
                                        <span> month(s).</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alowed Time -->
                        <div class="row mb10 csRow js-hider-add">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['accrual_rate_label']; ?><span class="cs-required">*</span>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="rate"></i>
                                    </h5>
                                    <div class="js-hint js-hint-rate">
                                        <?php echo $get_policy_item_info['accrual_rate_info']; ?></div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <input class="form-control" name="template" id="js-accrual-rate-add" autocomplete="off" />
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control jsTimeTypeSelect-add" id="js-accrual-rate-type-add">
                                                <option value="days">Day(s)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <small id="jsFormula-add"></small>
                                </div>

                                <div class="row mb10">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" id="js-accrual-default-flow-add" />
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
                                    <div class="col-lg-12">
                                        <h4 class="timeline-title allowed-time-off-title-custom csHeading">
                                            <?php echo $get_policy_item_info['accruals_plans_label']; ?>
                                            <i class="fa fa-question-circle" data-hint="js-hint" data-target="plans"></i>
                                            <span class="pull-right">
                                                <button class="btn btn-orange js-plan-btn-add" data-type="add" style="margin-top: -5px;"><i class="fa fa-plus"></i>&nbsp; Add
                                                    Plan</button>
                                            </span>
                                        </h4>
                                        <div class="js-hint js-hint-plans">
                                            <?php echo $get_policy_item_info['accrual_plans']; ?></div>
                                    </div>
                                </div>

                                <!--  -->
                                <div class="row mb10 csRow js-hider-add">
                                    <!-- Plans area -->
                                    <div class="col-sm-12 col-xs-12 jsPlanArea" id="js-plan-area-add"></div>
                                </div>
                            </div>
                        </div>


                        <!--  -->
                        <div class="js-step-buttons" data-type="add" data-step="3">
                            <hr />
                            <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                            <button class="btn btn-orange js-to-step">Next</button>
                        </div>
                    </div>

                    <!-- Step 3 - Carryover -->
                    <div class="js-step" data-type="add" data-step="3">
                        <!--  -->
                        <?php $this->load->view('timeoff/partials/note'); ?>
                        <!-- Carryover - Edit -->
                        <div class="row mb10 csRow js-hider-add">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['carry_over_label']; ?><span class="cs-required">*</span>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="carycheck"></i>
                                    </h5>
                                    <div class="js-hint js-hint-carycheck">
                                        <?php echo $get_policy_item_info['allow_carryover_cap_info']; ?></div>
                                    <div>
                                        <select class="invoice-fields" name="template" id="js-carryover-cap-check-add">
                                            <option value="yes" selected="true">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carryover Value - Edit -->
                        <div class="row mb10 csRow js-hider-add js-carryover-box-add">
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
                                            <input class="form-control" name="template" id="js-carryover-cap-add" />
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control jsTimeTypeSelect-add" id="js-accrual-carryover-type"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carryover Cycle - Edit -->
                        <div class="row mb10 csRow js-hider-edit js-carryover-box-add">
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
                                            <input class="form-control" name="template" id="js-carryover-cycle-add" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="js-step-buttons" data-type="add" data-step="4">
                            <hr />
                            <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                            <button class="btn btn-orange js-to-step">Next</button>
                        </div>
                    </div>

                    <!-- Step 4 - Negative Balance -->
                    <div class="js-step" data-type="add" data-step="4">
                        <!--  -->
                        <?php $this->load->view('timeoff/partials/note'); ?>
                        <!-- Negative Balance - Edit -->
                        <div class="row mb10 csRow js-hider-add">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['negative_balance_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="negativecheck"></i>
                                    </h5>
                                    <div class="js-hint js-hint-negativecheck">
                                        <?php echo $get_policy_item_info['allow_negative_balance_info']; ?></div>
                                    <div>
                                        <select class="invoice-fields" name="template" id="js-accrual-balance-add">
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Negative Balance - Edit -->
                        <div class="row mb10 csRow js-hider-add js-negative-box-add">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['negative_balance_limit_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="negativeval"></i>
                                    </h5>
                                    <div class="js-hint js-hint-negativeval">
                                        <?php echo $get_policy_item_info['new_hire_maximum_days_off_HD_info']; ?></div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <input class="form-control" name="template" id="js-maximum-balance-add" />
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control jsTimeTypeSelect-add" id="js-accrual-negative-balance-type"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="js-step-buttons" data-type="add" data-step="5">
                            <hr />
                            <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                            <button class="btn btn-orange js-to-step">Next</button>
                        </div>
                    </div>

                    <!-- Step 5 - Applicable Date -->
                    <div class="js-step" data-type="add" data-step="5">
                        <!--  -->
                        <?php $this->load->view('timeoff/partials/note'); ?>
                        <!-- Policy Implement Date - Edit -->
                        <div class="row mb10 csRow">
                            <div class="col-lg-12">
                                <h5 class="timeline-title allowed-time-off-title-custom">
                                    <?php echo $get_policy_item_info['applicable_date_label']; ?> <i class="fa fa-question-circle" data-hint="js-hint" data-target="applicable-date"></i></h5>
                                <div class="js-hint js-hint-applicable-date">
                                    <?php echo $get_policy_item_info['applicable_date_for_policy_info']; ?></div>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <div class="">
                                    <label class="control control--radio">
                                        <?php echo $get_policy_item_info['employee_joining_date_label']; ?>
                                        <input type="radio" name="optradio" checked="true" value="hireDate" class="js-hire-date-add" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <br />
                                    <label class="control control--radio">
                                        <?php echo $get_policy_item_info['pick_a_date_label']; ?>
                                        <input type="radio" name="optradio" value="customHireDate" class="js-hire-date-add" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <div class="jsImplementDateBox-add" style="display: none; margin-top: 5px;">
                                        <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-add" />
                                    </div>
                                </div>
                                <br />
                                <p style="color: #c10000; display: none;" id="js-applicable-date-text-add">
                                    <i><?php echo $get_policy_item_info['applicable_date_msg_label']; ?></i>
                                </p>
                            </div>
                        </div>
                        <!--  -->
                        <div class="js-step-buttons" data-type="add" data-step="6">
                            <hr />
                            <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                            <button class="btn btn-orange js-to-step">Next</button>
                        </div>
                    </div>


                    <!-- Step 6 - Reset Date -->
                    <div class="js-step" data-type="add" data-step="6">
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
                                        <?php echo $get_policy_item_info['reset_date_info']; ?></div>
                                    <div class="">
                                        <label class="control control--radio">
                                            <?php echo $get_policy_item_info['reset_date_1_label']; ?>
                                            <input type="radio" name="js-policy-reset-date-add" class="js-policy-reset-date-add" checked="true" value="policyDate" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <br />
                                        <label class="control control--radio">
                                            <?php echo $get_policy_item_info['reset_date_2_label']; ?>
                                            <input type="radio" name="js-policy-reset-date-add" class="js-policy-reset-date-add" value="policyDateCustom" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <div class="jsResetDateBox-add" style="display: none; margin-top: 5px;">
                                            <input type="text" readonly="true" class="invoice-fields" id="js-custom-reset-date-add" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="js-step-buttons" data-type="add" data-step="7">
                            <hr />
                            <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                            <button class="btn btn-orange js-to-step">Next</button>
                        </div>
                    </div>

                    <!-- Step 7 - New Hire -->
                    <div class="js-step" data-type="add" data-step="7">
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
                            <div class="col-lg-12 js-hider-add accrual-settings-line-1 mb10">
                                <h5 class="timeline-title allowed-time-off-title-custom">
                                    <?php echo $get_policy_item_info['probation_period_label']; ?>
                                    <i class="fa fa-question-circle" data-hint="js-hint" data-target="waiting"></i>
                                </h5>
                                <div class="js-hint js-hint-waiting">
                                    <?php echo $get_policy_item_info['waiting_period_info']; ?></div>
                                <span></span>
                                <div class="form-group form-group-custom form-group-custom-settings">
                                    <input class="form-control" id="js-accrue-new-hire-add" />
                                </div><span> <select class="form-control" style="width: 200px; display: inline;" id="js-accrual-new-hire-time-type">
                                        <option value="hours">Hours</option>
                                        <option value="days">Days</option>
                                        <option value="months">Months</option>
                                        <!-- <option value="per_week">Per Week</option> -->
                                        <!-- <option value="per_month">Per Month</option> -->
                                    </select></span>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row mb10 csRow js-hider-add">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5 class="timeline-title allowed-time-off-title-custom">
                                        <?php echo $get_policy_item_info['probation_period_rate_label']; ?>
                                        <i class="fa fa-question-circle" data-hint="js-hint" data-target="newhiretime"></i>
                                    </h5>
                                    <div class="js-hint js-hint-newhiretime">
                                        <?php echo $get_policy_item_info['new_hire_maximum_days_off_info']; ?></div>
                                    <div class="input-group">
                                        <input class="form-control" name="template" id="js-newhire-prorate-add" />
                                        <span class="input-group-addon jsTimeType-add">Days</span>
                                    </div>
                                </div>
                                <p class="js-newhire-text-add"></p>
                            </div>
                        </div>
                        <!--  -->
                        <div class="js-step-buttons" data-type="add" data-step="8">
                            <hr />
                            <button class="btn btn-black btn-theme js-to-step-back">Back</button>
                            <button class="btn btn-orange js-to-step">Save & Finish</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>