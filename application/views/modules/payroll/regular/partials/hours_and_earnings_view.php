<?php if ($payrollEmployees) { ?>
    <?php foreach ($payrollEmployees as $value) { ?>
        <tr class="jsRegularPayrollEmployeeRow" data-id="<?= $value['id']; ?>">
            <td class="vam">
                <label class="control control--checkbox">
                    <input type="checkbox" name="jsSelectSingle" class="jsSelectSingle" value="<?= $value['id']; ?>" />
                    <div class="control__indicator" style="margin-top: -11px;"></div>
                </label>
            </td>
            <td class="vam">
                <p class="text-small">
                    <strong>
                        <?= $value['name']; ?>
                    </strong>
                </p>
                <?= $value['compensation']['text']; ?>
            </td>
            <!-- RH, OV, DOT -->
            <td class="vam">
                <div class="jsSkipPayroll">
                    <?php if ($regularPayroll['employees'][$value['id']]['hourly_compensations']['regular_hours']) { ?>
                        <!-- Regular hours -->
                        <div class="jsBox jsRegularHoursBox" style="margin-bottom: 3px">
                            <button class="btn btn-link text-small jsBoxSwitch">
                                <i class="fa fa-edit text-small"></i>
                                &nbsp; <span class="jsRegularHoursText">0.00 hrs</span> (Regular Hours)
                            </button>
                            <div class="input-group jsBoxField hidden">
                                <div class="input-group-addon">RH</div>
                                <input type="number" class="form-control text-right jsRegularHours jsRegularHoursValue" value="" placeholder="0.0" />
                                <div class="input-group-addon">hr</div>
                                <div class="input-group-addon" style="padding: 0px;">
                                    <button class="btn btn-danger jsCancelRH" style="padding: 4px; margin: 0">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                    <?php if ($regularPayroll['employees'][$value['id']]['hourly_compensations']['overtime']) { ?>
                        <!-- Overtime -->
                        <div class="jsBox jsOvertimeBox" style="margin-bottom: 3px">
                            <button class="btn btn-link text-small jsBoxSwitch">
                                <i class="fa fa-edit text-small"></i>
                                &nbsp; <span class="jsOvertimeText">0.00 hrs</span> (Overtime)
                            </button>
                            <div class="input-group jsBoxField hidden">
                                <div class="input-group-addon">OT</div>
                                <input type="number" class="form-control text-right jsOvertime jsOvertimeValue" value="" placeholder="0.0" />
                                <div class="input-group-addon">hr</div>
                                <div class="input-group-addon" style="padding: 0px;">
                                    <button class="btn btn-danger jsCancelOT" style="padding: 4px; margin: 0">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                    <?php if ($regularPayroll['employees'][$value['id']]['hourly_compensations']['double_overtime']) { ?>
                        <!-- double Overtime -->
                        <div class="jsBox jsDoubleOvertimeBox" style="margin-bottom: 3px">
                            <button class="btn btn-link text-small jsBoxSwitch">
                                <i class="fa fa-edit text-small"></i>
                                &nbsp; <span class="jsDoubleOvertimeText">0.00 hrs</span> (Double Overtime)
                            </button>
                            <div class="input-group jsBoxField hidden">
                                <div class="input-group-addon">DOT</div>
                                <input type="number" class="form-control text-right jsDoubleOvertime jsDoubleOvertimeValue" value="" placeholder="0.0" />
                                <div class="input-group-addon">hr</div>
                                <div class="input-group-addon" style="padding: 0px;">
                                    <button class="btn btn-danger jsCancelDOT" style="padding: 4px; margin: 0">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </td>
            <!-- Additional Earnings -->
            <td class="vam">
                <div class="jsSkipPayroll">
                    <?php if ($regularPayroll['employees'][$value['id']]['fixed_compensations']['bonus']) { ?>
                        <!-- bonus -->
                        <div class="jsBox jsBonusBox" style="margin-bottom: 3px">
                            <button class="btn btn-link text-small jsBoxSwitch">
                                <i class="fa fa-edit text-small"></i>
                                &nbsp; <span class="jsBonusText">$0.00</span>
                            </button>
                            <div class="input-group jsBoxField hidden">
                                <div class="input-group-addon">Bonus</div>
                                <input type="number" class="form-control text-right jsBonus jsBonusValue" value="" placeholder="0.0" />
                                <div class="input-group-addon">$</div>
                                <div class="input-group-addon" style="padding: 0px;">
                                    <button class="btn btn-danger jsCancelBonus" style="padding: 4px; margin: 0">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- additional earnings -->
                    <div class="jsBox jsAdditionalEarningBox jsAdditionalEarningBtn" style="margin-bottom: 3px">
                        <button class="btn btn-link text-small">
                            <i class="fa fa-edit text-small"></i>
                            &nbsp; <span class="jsAdditionalEarningText">$0.00</span>
                        </button>
                        <div class="input-group jsBoxField hidden">
                            <div class="input-group-addon">Additional Earnings</div>
                            <input type="number" readonly class="form-control text-right jsAdditionalEarning jsAdditionalEarningValue" value="" placeholder="0.0" />
                        </div>
                    </div>
                </div>
            </td>
            <!-- Reimbursement Earnings -->
            <td class="vam">
                <div class="jsSkipPayroll">
                    <br>
                    <p class="text-small">
                        <strong class="jsShowTotal">$0.0</strong>
                    </p>
                    <button class="btn btn-link text-small jsReimbursementTotalBoxBtn jsReimbursementBtn" style="padding: 0; margin: 20px 0">
                        <i class="fa fa-plus-circle text-small"></i>
                        &nbsp;Reimbursement
                    </button>
                    <p class="text-small jsReimbursementTotalBox jsReimbursementBtn hidden">
                        <strong class="jsReimburmentTotal">Reimbursement $0.0</strong>
                    </p>
                    <p class="text-small">
                        Pay by: <select class="jsPaymentMethod">
                            <option value="Direct Deposit">Direct Deposit</option>
                            <option value="Check">Check</option>
                        </select>
                    </p>
                </div>
            </td>
            <td class="vam text-right">
                <div class="btn-group-vertical">
                    <button type="button" class="btn btn-danger text-small jsSkipEmployeeFromPayroll hidden">
                        <i class="fa fa-ban text-small" aria-hidden="true"></i>
                        &nbsp;Skip Payroll
                    </button>
                    <button type="button" class="btn btn-orange text-small jsAddEmployeeFromPayroll hidden">
                        <i class="fa fa-plus-circle text-small" aria-hidden="true"></i>
                        &nbsp;Enter Payment
                    </button>
                </div>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td class="vam" colspan="6">
            <?php $this->load->view('v1/no_data', ['message' => 'No employees found.']); ?>
        </td>
    </tr>
<?php } ?>