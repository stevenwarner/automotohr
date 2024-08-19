<?php $totals = [
    'gross_pay' => 0,
    'reimbursements' => 0,
    'company_taxes' => 0,
    'company_benefits' => 0,
    'subtotal' => 0,
]; ?>
<br>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csF16 m0 csW">
            <strong>Company costs</strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <th scope="col" class="csW csBG4">
                        Employees (<?= count($payroll['employees']); ?>)
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Gross Pay
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Reimbursements
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Company Taxes
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Company Benefits
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Subtotal
                    </th>
                </thead>
                <tbody>

                    <?php foreach ($payroll['employees'] as $employeeId => $value) { ?>
                        <?php
                        $calculatedTaxes = $this->lb_gusto->calculateTax($value['taxes']);
                        $calculatedBenefits = $this->lb_gusto->calculateBenefits($value['benefits']);
                        //
                        $subTotal = 0;
                        $subTotal += $value['gross_pay'];
                        $subTotal += $value['fixed_compensations']['reimbursement']['amount'];
                        $subTotal += $calculatedTaxes['employer_tax_total'];
                        $subTotal += $calculatedBenefits['employer_tax_total'];
                        ?>
                        <tr>
                            <td class="vam">
                                <?= $payrollEmployees[$employeeId]['name']; ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($value['net_pay']); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($value['fixed_compensations']['reimbursement']['amount'] ?? 0); ?>
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
                            <?= _a($payroll["totals"]['gross_pay']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($payroll["totals"]['reimbursement_debit']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($payroll["totals"]['employer_taxes']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($payroll["totals"]['employee_taxes']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($payroll["totals"]['company_debit']); ?>
                        </th>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <h3 class="csF22 text-right">
                                <strong>
                                    <?= _a($payroll["totals"]['company_debit']); ?>
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