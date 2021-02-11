<!--  -->
<div class="row mb10">
    <div class="col-sm-12">
        <h4 class="timeline-title allowed-time-off-title-custom csHeading">Basic Settings</h4>
    </div>
</div>


<!-- Policy Entitled Employees - Edit -->
<div class="row margin-top">
    <div class="col-sm-6 col-xs-12">
        <label>Entitled Employee(s) <i class="fa fa-question-circle" data-hint="js-hint" data-target="employee"></i></label>
                <p class="js-hint js-hint-employee"><?php echo $get_policy_item_info['entitled_employee_info']; ?></p>
        <div>
            <select class="invoice-fields" name="templates[]" id="js-employee-edit-reset" multiple="true"></select>
        </div>
    </div>
</div>

<div class="row mb10">
    <div class="col-sm-6 col-xs-12">
        <div class="">
            <label class="control control--checkbox">
                <input type="checkbox" id="js-approver-check-edit-reset" />
                Only Approver(s) can see
                <span class="control__indicator"></span>
            </label>
            <i class="fa fa-question-circle" data-hint="js-hint" data-target="approver"></i></label>
            <p class="js-hint js-hint-approver"><?php echo $get_policy_item_info['type_info']; ?></p>
        </div>
    </div>
</div>

<div class="row mb10">
    <div class="col-sm-6 col-xs-12">
        <div class="">
            <label class="control control--checkbox">
                <input type="checkbox" id="js-archive-check-edit-reset" />
                Deactivate
                <span class="control__indicator"></span>
            </label>
            <i class="fa fa-question-circle" data-hint="js-hint" data-target="deactivate"></i></label>
            <p class="js-hint js-hint-deactivate"><?php echo $get_policy_item_info['type_info']; ?></p>
        </div>
    </div>
</div>

<div class="row mb10">
    <div class="col-sm-6 col-xs-12">
        <div class="">
            <label class="control control--checkbox">
                <input type="checkbox" id="js-include-check-edit-reset" />
                Include in Balance
                <span class="control__indicator"></span>
            </label>
            <i class="fa fa-question-circle" data-hint="js-hint" data-target="balance"></i></label>
            <p class="js-hint js-hint-balance"><?php echo $get_policy_item_info['type_info']; ?></p>
        </div>
    </div>
</div>

<!-- Policy Accruals - Edit -->
<div class="row">
    <div class="col-lg-12">
        <h4 class="timeline-title allowed-time-off-title-custom csHeading">Accrual Schedule & Settings</h4>
    </div>
</div>

<!-- Accrual Methid - Edit -->
<div class="row mb10">
    <div class="col-sm-6">
        <div>
            <h5 class="timeline-title allowed-time-off-title-custom">Accrual Method <span class="cs-required">*</span>
            <i class="fa fa-question-circle" data-hint="js-hint" data-target="ami"></i></label></h5>
                    <p class="js-hint js-hint-ami"><?php echo $get_policy_item_info['accrual_method_info']; ?></p>
            <div>
                <select class="invoice-fields" name="template" id="js-accrual-method-edit-reset">
                    <option value="unlimited">Unlimited</option>
                    <option value="days_per_year" selected="true">Days per Year</option>
                    <option value="hours_per_month">Hours per Month</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Accrual Time - Edit -->
<div class="row mb10 js-hider-edit-reset">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">Accrual Time <span class="cs-required">*</span>  <i class="fa fa-question-circle" data-hint="js-hint" data-target="ati"></i></label></h5>
                    <p class="js-hint js-hint-ati"><?php echo $get_policy_item_info['accrual_time_info']; ?></p>
            <div>
                <select class="invoice-fields" name="template" id="js-accrual-time-edit-reset">
                    <option value="none" selected="true">None</option>
                    <option value="start_of_period" selected="true">Start of the period</option>
                    <option value="end_of_period">End of the period</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Accrual Frequency - Edit -->
<div class="row mb10 js-hider-edit-reset">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">Accrual Frequency <span
                    class="cs-required">*</span>  <i class="fa fa-question-circle" data-hint="js-hint" data-target="afi"></i></label></h5>
                    <p class="js-hint js-hint-afi"><?php echo $get_policy_item_info['accrual_frequency_info']; ?></p>
            <div>
                <select class="invoice-fields" name="template" id="js-accrual-frequency-edit-reset">
                    <option value="none" selected="true">None</option>
                    <option value="yearly">Yearly</option>
                    <option value="monthly">Monthly</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="jsCustomBoxEditReset" style="margin-top: 5px; display: none;">
                <span>Every</span>
                <div class="form-group form-group-custom form-group-custom-settings">
                    <input class="form-control form-control-custom" id="js-accrue-custom-frequency-edit-reset" />
                </div>
                <span> month(s).</span>
            </div>
        </div>
    </div>
</div>

<!-- Alowed Time -->
<div class="row mb10 js-hider-edit-reset">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">Accrual Rate <span class="cs-required">*</span>  <i class="fa fa-question-circle" data-hint="js-hint" data-target="ari"></i></label></h5>
                    <p class="js-hint js-hint-ari"><?php echo $get_policy_item_info['accrual_rate_info']; ?></p>
            <div>
                <input class="invoice-fields" name="template" id="js-accrual-rate-edit-reset" />
            </div>
        </div>
    </div>
</div>

<!-- Carryover - Edit -->
<div class="row mb10 js-hider-edit-reset">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">Allow Carryover Cap? (use it or lose it) <span
                    class="cs-required">*</span>  <i class="fa fa-question-circle" data-hint="js-hint" data-target="acci"></i></label></h5>
                    <p class="js-hint js-hint-acci"><?php echo $get_policy_item_info['allow_carryover_cap_info']; ?></p>
            <div>
                <select class="invoice-fields" name="template" id="js-carryover-cap-check-edit-reset">
                    <option value="yes" selected="true">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Carryover Value - Edit -->
<div class="row mb10 js-hider-edit-reset js-carryover-box-edit-reset">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">Carryover Cap  <i class="fa fa-question-circle" data-hint="js-hint" data-target="cci"></i></label></h5>
                    <p class="js-hint js-hint-cci"><?php echo $get_policy_item_info['carryover_cap_info']; ?></p>
            <div>
                <input class="invoice-fields" name="template" id="js-carryover-cap-edit-reset" />
            </div>
        </div>
    </div>
</div>

<!-- Negative Balance - Edit -->
<div class="row mb10 js-hider-edit-reset">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">Allow Negative Balance  <i class="fa fa-question-circle" data-hint="js-hint" data-target="anbi"></i></label></h5>
                    <p class="js-hint js-hint-anbi"><?php echo $get_policy_item_info['allow_negative_balance_info']; ?></p>
            <div>
                <select class="invoice-fields" name="template" id="js-accrual-balance-edit-reset">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Negative Balance - Edit -->
<div class="row mb10 js-hider-edit-reset js-negative-box-edit-reset">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">Allowed negative balance (Hours/Days)  <i class="fa fa-question-circle" data-hint="js-hint" data-target="nhmdodi"></i></label></h5>
                    <p class="js-hint js-hint-nhmdodi"><?php echo $get_policy_item_info['new_hire_maximum_days_off_HD_info']; ?></p>
            <div>
                <input class="invoice-fields" name="template" id="js-maximum-balance-edit-reset" />
            </div>
        </div>
    </div>
</div>

<!-- Policy Implement Date - Edit -->
<div class="row mb10">
    <div class="col-lg-12">
        <h5 class="timeline-title allowed-time-off-title-custom">Applicable Date for Policy  <i class="fa fa-question-circle" data-hint="js-hint" data-target="adfp"></i></label></h5>
                <p class="js-hint js-hint-adfp"><?php echo $get_policy_item_info['applicable_date_for_policy_info']; ?></p>
    </div>
    <div class="col-lg-6">
        <div class="">
            <label class="control control--radio">
                Employee Start Date
                <input type="radio" name="js-hire-date-edit-reset" checked="true" value="hireDate" class="js-hire-date-edit-reset" />
                <div class="control__indicator"></div>
            </label>
            <br />
            <label class="control control--radio">
                Pick a Date
                <input type="radio" name="js-hire-date-edit-reset" value="customHireDate" class="js-hire-date-edit-reset" />
                <div class="control__indicator"></div>
            </label>
            <div class="jsImplementDateBoxReset" style="display: none; margin-top: 5px;">
                <input type="text" readonly="true" class="invoice-fields" id="js-custom-date-edit-reset" />
            </div>
        </div>
    </div>
</div>

<!-- Policy Reset Date - Edit  -->
<div class="row mb10">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">Reset Date  <i class="fa fa-question-circle" data-hint="js-hint" data-target="rdi"></i></label></h5>
                    <p class="js-hint js-hint-rdi"><?php echo $get_policy_item_info['reset_date_info']; ?></p>
            <div class="">
                <label class="control control--radio">
                    Policy Applicable Date
                    <input type="radio" name="js-policy-reset-date-edit-reset" class="js-policy-reset-date-edit-reset"
                        checked="true" value="policyDate" />
                    <div class="control__indicator"></div>
                </label>
                <br />
                <label class="control control--radio">
                    Pick a Date
                    <input type="radio" name="js-policy-reset-date-edit-reset" class="js-policy-reset-date-edit-reset"
                        value="policyDateCustom" />
                    <div class="control__indicator"></div>
                </label>
                <div class="jsResetDateBoxReset" style="display: none; margin-top: 5px;">
                    <input type="text" readonly="true" class="invoice-fields" id="js-custom-reset-date-edit-reset" />
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
            <h5 class="timeline-title allowed-time-off-title-custom">Minimum Applicable Time <i class="fa fa-question-circle" data-hint="js-hint" data-target="mahi"></i></label></h5>
                    <p class="js-hint js-hint-mahi"><?php echo $get_policy_item_info['minimum_applicable_hours_info']; ?></p>
            <div>
                <label class="control control--radio">
                    Hours &nbsp;&nbsp;
                    <input type="radio" name="js-minimum-applicable-time-edit-reset" class="js-minimum-applicable-time-edit-reset"
                        checked="true" value="hours" />
                    <div class="control__indicator"></div>
                </label>
                <label class="control control--radio">
                    Days &nbsp;&nbsp;
                    <input type="radio" name="js-minimum-applicable-time-edit-reset" class="js-minimum-applicable-time-edit-reset"
                        value="days" />
                    <div class="control__indicator"></div>
                </label>
                <label class="control control--radio">
                    Months &nbsp;&nbsp;
                    <input type="radio" name="js-minimum-applicable-time-edit-reset" class="js-minimum-applicable-time-edit-reset"
                        value="months" />
                    <div class="control__indicator"></div>
                </label>
                <label class="control control--radio">
                    Years &nbsp;&nbsp;
                    <input type="radio" name="js-minimum-applicable-time-edit-reset" class="js-minimum-applicable-time-edit-reset"
                        value="years" />
                    <div class="control__indicator"></div>
                </label>
            </div>
            <div style="margin-top: 5px;">
                <input class="invoice-fields" name="template" id="js-minimum-applicable-hours-edit-reset" />
            </div>
        </div>
    </div>
</div>

<!--  -->
<div class="row">
    <div class="col-lg-12">
        <h4 class="timeline-title allowed-time-off-title-custom csHeading">New Hire <small><?php echo $get_policy_item_info['new_hire_tag']; ?></small></h4>
    </div>
</div>

<!--  -->
<div class="row mb10">
    <div class="col-lg-12 js-hider-edit-reset accrual-settings-line-1 margin-top">
        <h5 class="timeline-title allowed-time-off-title-custom">Waiting period  <i class="fa fa-question-circle" data-hint="js-hint" data-target="wpi"></i></label></h5>
                <p class="js-hint js-hint-wpi"><?php echo $get_policy_item_info['waiting_period_info']; ?></p>
        <span>New hires can request time off after</span>
        <div class="form-group form-group-custom form-group-custom-settings">
            <input class="form-control form-control-custom" id="js-accrue-new-hire-edit-reset" />
        </div><span> day(s).</span>
    </div>
</div>

<!--  -->
<div class="row mb10 js-hider-edit-reset">
    <div class="col-sm-6">
        <div class="form-group">
            <h5 class="timeline-title allowed-time-off-title-custom">New hire maximum days off  <i class="fa fa-question-circle" data-hint="js-hint" data-target="nhmdoi"></i></label></h5>
                    <p class="js-hint js-hint-nhmdoi"><?php echo $get_policy_item_info['new_hire_maximum_days_off_info']; ?></p>
            <div>
                <input class="invoice-fields" name="template" id="js-newhire-prorate-edit-reset" />
            </div>
        </div>
    </div>
</div>

<!--  -->
<div class="row mb10">
    <div class="col-lg-12">
        <h4 class="timeline-title allowed-time-off-title-custom csHeading">Accrual Plans
        <i class="fa fa-question-circle" data-hint="js-hint" data-target="ap"></i></label>
                <p class="js-hint js-hint-ap"><?php echo $get_policy_item_info['accrual_plans']; ?></p>
            <span class="pull-right">
                <button class="btn btn-success js-plan-btn-edit-reset" data-type="edit" style="margin-top: -5px;"><i
                        class="fa fa-plus"></i>&nbsp; Add Plan</button>
            </span>
        </h4>
    </div>
</div>

<!--  -->
<div class="row mb10 js-hider-edit-reset">
    <!-- Plans area -->
    <div class="col-sm-12 col-xs-12 jsPlanArea" id="js-plan-area-edit-reset"></div>
</div>

<!-- FMLA Range -->
<div class="row mb10 js-fmla-range-wrap-edit-reset" style="display: none;">
    <div class="col-lg-12">
        <h4 class="timeline-title allowed-time-off-title-custom csHeading">FMLA Range</h4>
    </div>
    <div class="col-lg-6">
        <div class="">
            <label class="control control--radio">
                <input type="radio" name="fmla-range-edit-reset" value="standard_year"
                    class="js-fmla-range-edit-reset" />&nbsp;Standard Year (Jan-Dec)
                <div class="control__indicator"></div>
            </label>
            <label class="control control--radio">
                <input type="radio" name="fmla-range-edit-reset" value="employee_start_date"
                    class="js-fmla-range-edit-reset" />&nbsp;Employee Start Date
                <div class="control__indicator"></div>
            </label>
            <label class="control control--radio">
                <input type="radio" name="fmla-range-edit-reset" value="start_year"
                    class="js-fmla-range-edit-reset" />&nbsp;Starting from First FMLA usage
                <i class="fa fa-question-circle js-popover"
                    data-content="The 12-month period measured forward from the date of your first FMLA leave usage."></i>
                <div class="control__indicator"></div>
            </label>
            <label class="control control--radio">
                <input type="radio" name="fmla-range-edit-reset" value="end_year" class="js-fmla-range-edit-reset" />&nbsp;Ending on
                your First FMLA usage
                <i class="fa fa-question-circle js-popover"
                    data-content="A “rolling” 12-month period measured backward from the date of any FMLA leave usage."></i>
                <div class="control__indicator"></div>
            </label>
        </div>
    </div>
</div>