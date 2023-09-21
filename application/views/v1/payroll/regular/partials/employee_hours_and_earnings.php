<?php $totals = [
    'gross_pay' => 0,
    'reimbursements' => 0,
    'company_taxes' => 0,
    'company_benefits' => 0,
    'subtotal' => 0,
]; ?>
<br>
<div class="panel">
    <div class="panel-heading">
        <h3 class="csF16 m0 csW">
            <strong>Employee hours and earnings</strong>
        </h3>
    </div>
    <div class="panel-body">
        <!--  -->
        <div class="row">
            <div class="col-sm-12 text-right">
                    <button type="button" class="btn btn-default active">Hours</button>
                    <button type="button" class="btn btn-default">Pay</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <th scope="col" class="csW csBG4">
                        Employees (<?= count($payroll['employees']); ?>)
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Compensation type
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Regular
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Overtime (1.5x)
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Double overtime (2x)
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Paid Time Off
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Total hours
                    </th>
                </thead>
                <tbody>

                    <?php foreach ($payroll['employees'] as $employeeId => $value) { ?>
                        <?php
                       
                        //
                        $subTotal = 0;
                        $subTotal += $value['hourly_compensations']['hours'];
                        $subTotal += $value['fixed_compensations']['reimbursement']['amount'];
                        $subTotal += $calculatedTaxes['employer_tax_total'];
                        $subTotal += $calculatedBenefits['employer_tax_total'];

                        //
                        $totals['gross_pay'] += $value['gross_pay'];
                        $totals['reimbursements'] += $value['fixed_compensations']['reimbursement']['amount'];
                        $totals['company_taxes'] += $calculatedTaxes['employer_tax_total'];
                        $totals['company_benefits'] += $calculatedBenefits['employer_tax_total'];
                        $totals['subtotal'] += $subTotal;
                        ?>
                        <tr>
                            <td class="vam">
                                <?= $payrollEmployees[$employeeId]['name']; ?>
                            </td>
                            <td class="vam text-right">
                                <?= payrollEmployees[$employeeId]['compensation']['payment_unit_text']; ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($value['fixed_compensations']['reimbursement']['amount']); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($calculatedTaxes['employer_tax_total']); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($calculatedBenefits['employer_tax_total']); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($subTotal); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th scope="col" class="vam">
                            Totals
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($totals['gross_pay']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($totals['reimbursements']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($totals['company_taxes']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($totals['company_benefits']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($totals['subtotal']); ?>
                        </th>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <h3 class="csF22 text-right">
                                <strong>
                                    <?= _a($totals['subtotal']); ?>
                                </strong>
                            </h3>
                            <p class="csF16 text-right">
                                <strong>
                                    <sup>Total Payroll</sup>
                                </strong>
                            </p>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>