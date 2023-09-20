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
                <p class="csF16">
                    <strong>
                        <?= $value['name']; ?>
                    </strong>
                </p>
                <?= $value['compensation']['text']; ?>
            </td>
            <!-- RH, OV, DOT -->
            <td class="vam">
                <?php if ($regularPayroll['employees'][$value['id']]['hourly_compensations']['regular_hours']) { ?>
                    <!-- Regular hours -->
                    <div class="jsBox jsRegularHoursBox" style="margin-bottom: 3px">
                        <button class="btn btn-link csF16 jsBoxSwitch">
                            <i class="fa fa-edit csF16"></i>
                            &nbsp; <span class="jsRegularHoursText">0.00 hrs</span> (RH)
                        </button>
                        <div class="input-group jsBoxField dn">
                            <div class="input-group-addon">RH</div>
                            <input type="number" class="form-control text-right jsRegularHours jsRegularHoursValue" value="" placeholder="0.0" />
                            <div class="input-group-addon">hr</div>
                        </div>
                    </div>

                <?php } ?>
                <?php if ($regularPayroll['employees'][$value['id']]['hourly_compensations']['overtime']) { ?>
                    <!-- Overtime -->
                    <div class="jsBox jsOvertimeBox" style="margin-bottom: 3px">
                        <button class="btn btn-link csF16 jsBoxSwitch">
                            <i class="fa fa-edit csF16"></i>
                            &nbsp; <span class="jsOvertimeText">0.00 hrs</span> (OT)
                        </button>
                        <div class="input-group jsBoxField dn">
                            <div class="input-group-addon">OT</div>
                            <input type="number" class="form-control text-right jsOvertime jsOvertimeValue" value="" placeholder="0.0" />
                            <div class="input-group-addon">hr</div>
                        </div>
                    </div>

                <?php } ?>
                <?php if ($regularPayroll['employees'][$value['id']]['hourly_compensations']['double_overtime']) { ?>
                    <!-- double Overtime -->
                    <div class="jsBox jsDoubleOvertimeBox" style="margin-bottom: 3px">
                        <button class="btn btn-link csF16 jsBoxSwitch">
                            <i class="fa fa-edit csF16"></i>
                            &nbsp; <span class="jsDoubleOvertimeText">0.00 hrs</span> (DOT)
                        </button>
                        <div class="input-group jsBoxField dn">
                            <div class="input-group-addon">DOT</div>
                            <input type="number" class="form-control text-right jsDoubleOvertime jsDoubleOvertimeValue" value="" placeholder="0.0" />
                            <div class="input-group-addon">hr</div>
                        </div>
                    </div>

                <?php } ?>
            </td>
            <!-- Additional Earnings -->
            <td class="vam">
                <?php if ($regularPayroll['employees'][$value['id']]['fixed_compensations']['bonus']) { ?>
                    <!-- bonus -->
                    <div class="jsBox jsBonusBox" style="margin-bottom: 3px">
                        <button class="btn btn-link csF16 jsBoxSwitch">
                            <i class="fa fa-edit csF16"></i>
                            &nbsp; <span class="jsBonusText">$0.00</span>
                        </button>
                        <div class="input-group jsBoxField dn">
                            <div class="input-group-addon">B</div>
                            <input type="number" class="form-control text-right jsBonus jsBonusValue" value="" placeholder="0.0" />
                            <div class="input-group-addon">$</div>
                        </div>
                    </div>
                <?php } ?>
                <!-- additional earnings -->
                <div class="jsBox jsAdditionalEarningBox jsAdditionalEarningBtn" style="margin-bottom: 3px">
                    <button class="btn btn-link csF16">
                        <i class="fa fa-edit csF16"></i>
                        &nbsp; <span class="jsAdditionalEarningText">$0.00</span>
                    </button>
                    <div class="input-group jsBoxField dn">
                        <div class="input-group-addon">AE</div>
                        <input type="number" readonly class="form-control text-right jsAdditionalEarning jsAdditionalEarningValue" value="" placeholder="0.0" />
                    </div>
                </div>

            </td>
            <!-- Reimbursement Earnings -->
            <td class="vam">
                <br>
                <p class="csF16">
                    <strong class="jsShowTotal">$0.0</strong>
                </p>
                <button class="btn btn-link csF16 jsReimbursementBtn" style="padding: 0; margin: 20px 0">
                    <i class="fa fa-plus-circle csF16"></i>
                    &nbsp;Reimbursement
                </button>
                <p class="csF16">
                    Pay by: <select class="jsPaymentMethod">
                        <option value="Direct Deposit">Direct Deposit</option>
                        <option value="Check">Check</option>
                    </select>
                </p>
            </td>
            <td class="vam text-right">
                <button class="btn btn-default">
                    <i class="fa fa-ellipsis-v csF16"></i>
                </button>
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