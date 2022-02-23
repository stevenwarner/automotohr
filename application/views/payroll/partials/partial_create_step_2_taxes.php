<?php
    // Taxes & Debits
    $taxDebitArray = [
        'taxes' => [],
        'total' => 0,
        'employee_total' => 0,
        'employer_total' => 0
    ];

    //
    foreach($Payroll['employee_compensations'] as $Row){
        //
        if(!isset($Row['taxes'])){
            continue;
        }
        //
        foreach($Row['taxes'] as $tax){
            //
            if(!isset($taxDebitArray['taxes'][$tax['name']])){
                $taxDebitArray['taxes'][$tax['name']] = [
                    'name' => $tax['name'],
                    'employee_total' => 0,
                    'employer_total' => 0,
                ];
            }
            //
            $type = $tax['employer'] ? 'employer_total' : 'employee_total';
            //
            $taxDebitArray['taxes'][$tax['name']][$type] += $tax['amount'];
            //
            $taxDebitArray[$type] += $tax['amount'];
            $taxDebitArray['total'] += $tax['amount'];
        }
    }
    //
    asort($taxDebitArray['taxes']);
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
                        <?php foreach($taxDebitArray['taxes'] as $v): ?>
                            <tr>
                                <td class="vam ban">
                                    <span class="csF16 csB7">
                                        <?=$v['name'];?>
                                    </span>
                                </td>
                                <td class="text-right ban vam">
                                    <span class="csF16 csB7">
                                        <?=$v['employee_total'] == 0 ? 'N/A' : '$'.(number_format($v['employee_total'], 2));?>
                                    </span>
                                </td>
                                <td class="text-right ban vam">
                                    <span class="csF16 csB7">
                                        <?=$v['employer_total'] == 0 ? 'N/A' : '$'.(number_format($v['employer_total'], 2));?>
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
                                    <strong>$<?=number_format($taxDebitArray['employee_total'], 2);?></strong>
                                </p>
                            </td>
                            <td class="text-right ban vam">
                                <p class="csF16 csB7">
                                    <strong>$<?=number_format($taxDebitArray['employer_total'], 2);?></strong>
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
                                    $<?=number_format($Payroll['totals']['net_pay_debit'], 2);?>
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
                                   $<?=number_format($Payroll['totals']['reimbursements'], 2);?>
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
                                   $<?=number_format(0, 2);?>
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
                                    $<?=number_format($taxDebitArray['total'], 2);?>
                                </p>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>