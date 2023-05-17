<?php
    $companyItems = ['CA ETT','CA SUI','FUTA'];
    $employeeItems = ['Federal Income Tax','Additional Medicare','CA State Income Tax','CA SDI'];
    $shareItems = ['Social Security','Medicare'];
    $employeesTotal = 0;
    $companyTotal = 0;
?>
<br>
<div class="row">
    <div class="col-sm-12">
        <!-- Taxes & Debits -->
        <div class="panel">
            <div class="panel-heading csBG4">
                <div class="row">
                    <div class="col-sm-10">
                        <p class="csF16 csB7 csW mt0 mb0">
                            What Gets Taxed And Debited
                        </p>
                    </div>
                    <div class="col-sm-2 text-right">
                        <i class="fa fa-plus-circle csF22 csCP jsSectionTrigger" data-target="taxes" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="panel-body jsSectionBody dn" data-id="taxes">
                <!-- Taxes -->
                <table class="table table-striped">
                    <caption></caption>
                    <tbody>
                        <tr>
                            <th scope="col" class="vam ban csBG2">
                                <h1 class="csF18 csB7 mt0 mb0 csW">Tax Description</h1>
                            </th>
                            <th scope="col" class="text-right vam ban csBG2">
                                <h1 class="csF18 csB7 mt0 mb0 csW">By Your Employees</h1>
                            </th>
                            <th scope="col" class="text-right vam ban csBG2">
                                <h1 class="csF18 csB7 mt0 mb0 csW">By Your Company</h1>
                            </th>
                        </tr>
                        <?php foreach($payrollReceipt['taxes'] as $v): ?>
                            <tr>
                                <td class="vam ban">
                                    <span class="csF16 csB7">
                                        <?=$v['name'];?>
                                    </span>
                                </td>
                                <td class="text-right ban vam">
                                    <span class="csF16 csB7">
                                        <?php 
                                            if (in_array($v['name'], $shareItems)) {
                                                $shareEmployee = $v['amount'] / 2;
                                                echo '$'.(number_format($shareEmployee, 2));
                                                $employeesTotal = $employeesTotal + $shareEmployee;
                                            } else if (in_array($v['name'], $employeeItems)) {
                                                echo '$'.(number_format($v['amount'], 2));
                                                $employeesTotal = $employeesTotal + $v['amount'];
                                            } else {
                                                echo 'N/A';
                                            }
                                        ?>
                                    </span>
                                </td>
                                <td class="text-right ban vam">
                                    <span class="csF16 csB7">
                                        <?php 
                                            if (in_array($v['name'], $shareItems)) {
                                                $shareCompany = $v['amount'] / 2;
                                                echo '$'.(number_format($shareCompany, 2));
                                                $companyTotal = $companyTotal + $shareCompany;
                                            } else if(in_array($v['name'], $companyItems)) {
                                                echo '$'.(number_format($v['amount'], 2));
                                                $companyTotal = $companyTotal + $v['amount'];
                                            } else {
                                                echo 'N/A';
                                            }
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="vam ban">
                                <p class="csF16 csB7">
                                    <strong>Totals</strong>
                                </p>
                            </td>
                            <td class="text-right ban vam">
                                <p class="csF16 csB7">
                                    <strong>$<?=number_format($employeesTotal, 2);?></strong>
                                </p>
                            </td>
                            <td class="text-right ban vam">
                                <p class="csF16 csB7">
                                    <strong>$<?=number_format($companyTotal, 2);?></strong>
                                </p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <!-- Deductions -->
                <table class="table table-striped">
                    <caption></caption>
                    <tbody>
                        <tr>
                            <th scope="col" class="vam ban csBG2">
                                <h1 class="csF18 csB7 mt0 mb0 csW">
                                    Debited
                                </h1>
                            </th>
                            <th scope="col" class="text-right vam ban csBG2">
                                <h1 class="csF18 csB7 mt0 mb0 csW">
                                    Totals
                                </h1>
                            </th>
                        </tr>
                        <tr>
                            <td class="vam ban">
                                <p>
                                    Direct Deposits
                                </p>
                            </td>
                            <td class="text-right ban vam">
                                <p>
                                    $<?=number_format($payrollReceipt['totals']['net_pay_debit'], 2);?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td class="vam ban">
                                <p>
                                    Reimbursements
                                </p>
                            </td>
                            <td class="text-right ban vam">
                                <p>
                                   $<?=number_format($payrollReceipt['totals']['reimbursement_debit'], 2);?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td class="vam ban">
                                <p>
                                    Garnishments
                                </p>
                            </td>
                            <td class="text-right ban vam">
                                <p>
                                   $<?=number_format($payrollReceipt['totals']['child_support_debit'], 2);?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td class="vam ban">
                                <p>
                                    Taxes (Employees and Employers)
                                </p>
                            </td>
                            <td class="text-right ban vam">
                                <p>
                                    $<?=number_format($payrollReceipt['totals']['tax_debit'], 2);?>
                                </p>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>