<br>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-heading-text text-medium">
            <strong>Employee hours and earnings</strong>
        </h3>
    </div>
    <div class="panel-body">
        <!--  -->
        <div class="row">
            <div class="col-sm-12 text-right">
                <button type="button" data-target="hours" class="btn btn-default jsToggleHoursPay active">Hours</button>
                <button type="button" data-target="pay" class="btn btn-default jsToggleHoursPay">Pay</button>
            </div>
        </div>
        <div class="table-responsive">
            <!-- hours table -->
            <table class="table table-striped " data-key="hours">
                <caption></caption>
                <thead>
                    <th scope="col" class="bg-black">
                        Employees (<?= count($payroll['employees']); ?>)
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Compensation<br />type
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Regular
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Overtime<br />(1.5x)
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Double<br /> overtime (2x)
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Total<br />hours
                    </th>
                </thead>
                <tbody>

                    <?php foreach ($payroll['employees'] as $employeeId => $value) { ?>
                        <?php
                        //
                        $regularHours = $value['hourly_compensations']['regular_hours']['hours'] ?? 0;
                        $overTimeHours = $value['hourly_compensations']['overtime']['hours'] ?? 0;
                        $doubleOverHours = $value['hourly_compensations']['double_overtime']['hours'] ?? 0;
                        $totalHours = $regularHours + $overTimeHours + $doubleOverHours;
                        ?>
                        <tr>
                            <td class="vam">
                                <?= $payrollEmployees[$employeeId]['name']; ?>
                            </td>
                            <td class="vam text-right">
                                <?= $payrollEmployees[$employeeId]['compensation']['payment_unit_text']; ?>
                            </td>
                            <td class="vam  text-right">
                                <?= _a($regularHours, ''); ?>
                            </td>
                            <td class="vam  text-right">
                                <?= _a($overTimeHours, ''); ?>
                            </td>
                            <td class="vam  text-right">
                                <?= _a($doubleOverHours, ''); ?>
                            </td>
                            <td class="vam  text-right">
                                <?= _a($totalHours, ''); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- pay table -->
            <table class="table table-striped hidden" data-key="pay">
                <caption></caption>
                <thead>
                    <th scope="col" class="bg-black">
                        Employees (<?= count($payroll['employees']); ?>)
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Compensation<br />type
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Regular
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Overtime<br />(1.5x)
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Double<br />overtime (2x)
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Paid<br />Time Off
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Total<br />pay
                    </th>
                </thead>
                <tbody>

                    <?php foreach ($payroll['employees'] as $employeeId => $value) { ?>
                        <?php
                        //
                        $rate = $payrollEmployees[$employeeId]['compensation']['rate'] ?? 0;
                        $paymentUnit = $payrollEmployees[$employeeId]['compensation']['payment_unit'] ?? 'Hour';
                        //
                        $ratePerHour = getRatePerHour(
                            $rate,
                            $paymentUnit
                        );
                        //
                        $regularPay = (($value['hourly_compensations']['regular_hours']['hours'] ?? 0) * $rate);
                        $overTimePay = (($value['hourly_compensations']['overtime']['hours'] ?? 0) * $rate) * ($value['hourly_compensations']['overtime']['compensation_multiplier'] ?? 0);
                        $doubleOverPay = (($value['hourly_compensations']['double_overtime']['hours'] ?? 0) * $rate) * ($value['hourly_compensations']['double_overtime']['compensation_multiplier'] ?? 0);
                        $totalPay = $regularPay + $overTimePay + $doubleOverPay + ($value['fixed_compensations']['personal_time_off_paid_']['amount'] ?? 0);
                        ?>
                        <tr>
                            <td class="vam">
                                <?= $payrollEmployees[$employeeId]['name']; ?>
                            </td>
                            <td class="vam text-right">
                                <?= $payrollEmployees[$employeeId]['compensation']['payment_unit_text']; ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($regularPay); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($overTimePay); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($doubleOverPay); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($value['fixed_compensations']['personal_time_off_paid_']['amount'] ?? 0); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($totalPay); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <hr />
            <!-- Taxes -->
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <th scope="col" class="bg-black">
                        Employees (<?= count($payroll['employees']); ?>)
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Payment<br />type
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Gross<br />pay
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Deductions
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Reimbursements
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Employee<br />taxes
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Employee<br />benefits
                    </th>
                    <th scope="col" class="bg-black text-right">
                        Payment
                    </th>
                </thead>
                <tbody>
                    <?php
                    $totals = [
                        'gross_pay' => 0,
                        'deductions' => 0,
                        'reimbursements' => 0,
                        'company_taxes' => 0,
                        'company_benefits' => 0,
                        'subtotal' => 0
                    ];
                    ?>
                    <?php foreach ($payroll['employees'] as $employeeId => $value) { ?>
                        <?php
                        //
                        $taxes = $this->lb_gusto->calculateTax($value['taxes']);
                        $benefits = $this->lb_gusto->calculateBenefits($value['benefits']);
                        $deductions = $this->lb_gusto->calculateDeductions($value['deductions']);
                        //
                        $total = 0;
                        // gross pay
                        $total += $value['gross_pay'];
                        // deductions
                        $total += $deductions['employee_tax_total'] ?? 0;
                        // reimbursement
                        $total += $value['fixed_compensations']['reimbursement']['amount'] ?? 0;
                        // company taxes
                        $total += $taxes['employer_tax_total'] ?? 0;
                        // company benefits
                        $total += $benefits['employer_tax_total'] ?? 0;

                        //
                        $totals['gross_pay'] += $value['gross_pay'];
                        $totals['deductions'] += $deductions['employee_tax_total']  ?? 0;
                        $totals['reimbursements'] += $value['fixed_compensations']['reimbursement']['amount'] ?? 0;
                        $totals['company_taxes'] += $taxes['employer_tax_total']  ?? 0;
                        $totals['company_benefits'] += $benefits['employer_tax_total']  ?? 0;
                        $totals['subtotal'] += $total;
                        ?>
                        <tr>
                            <td class="vam">
                                <?= $payrollEmployees[$employeeId]['name']; ?>
                            </td>
                            <td class="vam text-right">
                                <?= $value['payment_method']; ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($value['gross_pay']); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($deductions['employee_tax_total'] ?? 0); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($value['fixed_compensations']['reimbursement']['amount'] ?? 0); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($taxes['employer_tax_total'] ?? 0); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($benefits['employer_tax_total'] ?? 0); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($total); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="vam" scope="col">
                            Totals
                        </th>
                        <th class="vam text-right" scope="col"></th>
                        <th class="vam text-right" scope="col">
                            <?= _a($totals['gross_pay']); ?>
                        </th>
                        <th class="vam text-right" scope="col">
                            <?= _a($totals['deductions']); ?>
                        </th>
                        <th class="vam text-right" scope="col">
                            <?= _a($totals['reimbursements']); ?>
                        </th>
                        <th class="vam text-right" scope="col">
                            <?= _a($totals['company_taxes']); ?>
                        </th>
                        <th class="vam text-right" scope="col">
                            <?= _a($totals['company_benefits']); ?>
                        </th>
                        <th class="vam text-right" scope="col">
                            <?= _a($totals['subtotal']); ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>