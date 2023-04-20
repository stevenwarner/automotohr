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
<div class="row">
    <div class="col-sm-12">
        <!-- Taxes & Debits -->
        <div class="panel">
            <div class="panel-heading csBG4">
                <div class="row">
                    <div class="col-sm-10">
                        <p class="csF16 csB7 csW mt0 mb0">
                            What Your Employees Worked & Take Home
                        </p>
                    </div>
                    <div class="col-sm-2 text-right">
                        <i class="fa fa-plus-circle csF22 csCP jsSectionTrigger" data-target="worked_hours" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="panel-body jsSectionBody dn" data-id="worked_hours">
                <!--  -->
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" class="csF16 csB7" href="#home">Hours</a></li>
                    <li><a data-toggle="tab" class="csF16 csB7" href="#menu1">Pay</a></li>
                </ul>
                <!--  -->
                <div class="tab-content">
                    <!-- Hours -->
                    <div id="home" class="tab-pane fade in active">
                        <!-- By Pay -->
                        <table class="table table-striped">
                            <caption></caption>
                            <tbody>
                                <tr>
                                    <th scope="col" class="vam ban csBG2">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Employees (<?=count($payrollReceipt['employee_compensations']);?>)
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Compensation Type
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Regular
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Overtime (1.5x)
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Double OT (2x)
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Paid Time Off 
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Total Hours
                                        </h1>
                                    </th>
                                </tr>

                                <!--  -->
                                <?php foreach($payrollReceipt['employee_compensations'] as $row): ?>
                                    <?php 
                                        //
                                        $compensationType = '';
                                        $regularWorkedHours = 0.00;
                                        $overtimeHours = 0.00;
                                        $doubleOvertimeHours = 0.00;
                                        //
                                        if (!empty($row['hourly_compensations'])) {
                                            foreach ($row['hourly_compensations'] as $compensation) {
                                                if ($compensation['name'] == 'Regular Hours') {
                                                    $compensationType = $compensation['flsa_status'] == 'Nonexempt' ? "Paid by the hour" : "Salary/No overtime";
                                                    $regularWorkedHours = $compensation['hours'];
                                                } else if ($compensation['name'] == 'Overtime') {
                                                    $overtimeHours = $compensation['hours'];
                                                } else if ($compensation['name'] == 'Double overtime') {
                                                    $doubleOvertimeHours = $compensation['hours'];
                                                }
                                            }
                                        }
                                        //
                                        $totalHours = number_format(($regularWorkedHours + $overtimeHours + $doubleOvertimeHours), 2);
                                    ?>
                                    <tr>
                                       <td class="vam ban">
                                           <h6 class="csF16">
                                               <?=($row['employee_last_name'].', '.$row['employee_first_name']);?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban">
                                           <h6 class="csF16">
                                               <?=$compensationType;?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               <?=number_format($regularWorkedHours, 2);?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               <?=number_format($overtimeHours , 2); ?>
                                            </h6>
                                        </td> 
                                        <td class="vam ban text-right">
                                            <h6 class="csF16">
                                               <?=number_format($doubleOvertimeHours , 2); ?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               <?=0.0;?>
                                           </h6>
                                       </td>
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               <?=number_format($totalHours, 2);?>
                                           </h6>
                                       </td> 
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pay -->
                    <div id="menu1" class="tab-pane fade">
                        <!-- By Pay -->
                        <table class="table table-striped">
                            <caption></caption>
                            <tbody>
                                <tr>
                                    <th scope="col" class="vam ban csBG2">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Employees (<?=count($payrollReceipt['employee_compensations']);?>)
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Compensation Type
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Regular
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Overtime (1.5x)
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Double OT (2x)
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Total Payment
                                        </h1>
                                    </th>
                                </tr>

                                <!--  -->
                                <?php foreach($payrollReceipt['employee_compensations'] as $row): ?>
                                    <?php 
                                        //
                                        $compensationType = '';
                                        $regularWorkedAmount = 0.00;
                                        $overtimeAmount = 0.00;
                                        $doubleOvertimeAmount = 0.00;
                                        //
                                        if (!empty($row['hourly_compensations'])) {
                                            foreach ($row['hourly_compensations'] as $compensation) {
                                                if ($compensation['name'] == 'Regular Hours') {
                                                    $compensationType = $compensation['flsa_status'] == 'Nonexempt' ? "Paid by the hour" : "Salary/No overtime";
                                                    $regularWorkedAmount = $compensation['amount'];
                                                } else if ($compensation['name'] == 'Overtime') {
                                                    $overtimeAmount = $compensation['amount'];
                                                } else if ($compensation['name'] == 'Double overtime') {
                                                    $doubleOvertimeAmount = $compensation['amount'];
                                                }
                                            }
                                        }
                                        //
                                        $totalAmount = number_format(($regularWorkedAmount + $overtimeAmount + $doubleOvertimeAmount), 2);
                                    ?>
                                    <tr>
                                       <td class="vam ban">
                                           <h6 class="csF16">
                                               <?=($row['employee_last_name'].', '.$row['employee_first_name']);?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban">
                                           <h6 class="csF16">
                                               <?=$compensationType;?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               $<?=number_format($regularWorkedAmount, 2);?>
                                            </h6>
                                        </td> 
                                        <td class="vam ban text-right">
                                            <h6 class="csF16">
                                               $<?=number_format($overtimeAmount, 2);?>
                                            </h6>
                                        </td> 
                                        <td class="vam ban text-right">
                                            <h6 class="csF16">
                                               $<?=number_format($doubleOvertimeAmount, 2);?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               $<?=$totalAmount;?>
                                           </h6>
                                       </td> 
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                         <!-- By Pay -->
                         <table class="table table-striped">
                            <caption></caption>
                            <tbody>
                                <tr>
                                    <th scope="col" class="vam ban csBG2">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Employees (<?=count($payrollReceipt['employee_compensations']);?>)
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Payment Type
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Gross Pay
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Deductions
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Reimbursements
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Employee Taxes
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Employee Benefits
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Payment
                                        </h1>
                                    </th>
                                </tr>

                                <?php 
                                    //
                                    $gTotalGrossPay = 0.00;
                                    $gTotalDeductions = 0.00;
                                    $gTotalReimbursements = 0.00;
                                    $gTotalEmployeeTaxes = 0.00;
                                    $gTotalEmployeeBenefits = 0.00;
                                    $gSubTotal = 0.00;
                                ?>

                                <!--  -->
                                <?php foreach($payrollReceipt['employee_compensations'] as $row): ?>
                                    <?php 
                                        //
                                        $payment_method = $row['payment_method'];
                                        $netPay = $row['net_pay'];
                                        $grossPay = 0.00;
                                        //
                                        if (!empty($row['hourly_compensations'])) {
                                            foreach ($row['hourly_compensations'] as $compensation) {
                                                if ($compensation['name'] == 'Regular Hours') {
                                                    $grossPay = $compensation['amount'];
                                                }
                                            }
                                        }
                                        //
                                        $totalDeductions = 0.00;
                                        //
                                        if(!empty($row['deductions'])){
                                            $totalDeductions = $row['deductions'];
                                        }
                                        //
                                        $totalBenifits = 0.00;
                                        //
                                        if(!empty($row['benefits'])){
                                            $totalBenifits = $row['benefits'];
                                        }
                                        //
                                        $totalReimbursement = 0.00;
                                        //
                                        if(!empty($row['total_reimbursement'])){
                                            $totalReimbursement = $row['total_reimbursement'];
                                        }
                                        //
                                        $totalTaxes = 0.00;
                                        //
                                        if(!empty($row['total_tax'])){
                                            $totalTaxes = $row['taxes'];
                                        }
                                        //
                                        $totalPayment = ($grossPay +
                                         $totalReimbursement ) - ($totalDeductions + $totalTaxes + $totalBenifits);
                                        //
                                        $gSubTotal += $totalPayment;
                                        $gTotalGrossPay += $grossPay;
                                        $gTotalNetPay += $netPay;
                                        $gTotalDeductions += $totalDeductions;
                                        $gTotalReimbursements += $totalReimbursement;
                                        $gTotalEmployeeTaxes += $totalTaxes;
                                        $gTotalEmployeeBenefits += $totalBenifits;

                                    ?>
                                    <tr>
                                       <td class="vam ban">
                                           <h6 class="csF16">
                                           <?=($row['employee_last_name'].', '.$row['employee_first_name']);?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban">
                                           <h6 class="csF16">
                                               <?=$payment_method;?>
                                           </h6>
                                       </td>
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               $<?=number_format($grossPay, 2);?>
                                           </h6>
                                       </td>
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               $<?=number_format($totalDeductions, 2);?>
                                           </h6>
                                       </td>
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               $<?=number_format($totalReimbursement, 2);?>
                                           </h6>
                                       </td>
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               $<?=number_format($totalTaxes, 2);?>
                                           </h6>
                                       </td>
                                        <td class="vam ban text-right">
                                            <h6 class="csF16">
                                                $<?=number_format($totalBenifits, 2);?>
                                            </h6>
                                        </td>
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               $<?=number_format($netPay, 2);?>
                                           </h6>
                                       </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="vam ban" colspan="2">
                                        <h6 class="csF16 csB7">
                                            Totals
                                        </h6>
                                    </td>
                                    <td class="vam ban text-right">
                                        <h6 class="csF16 csB7">
                                            $<?=number_format($gTotalGrossPay, 2);?>
                                        </h6>
                                    </td>
                                    <td class="vam ban text-right">
                                        <h6 class="csF16 csB7">
                                            $<?=number_format($gTotalDeductions, 2);?>
                                        </h6>
                                    </td>
                                    <td class="vam ban text-right">
                                        <h6 class="csF16 csB7">
                                            $<?=number_format($gTotalReimbursements, 2);?>
                                        </h6>
                                    </td>
                                    <td class="vam ban text-right">
                                        <h6 class="csF16 csB7">
                                            $<?=number_format($gTotalEmployeeTaxes, 2);?>
                                        </h6>
                                    </td>
                                    <td class="vam ban text-right">
                                        <h6 class="csF16 csB7">
                                            $<?=number_format($gTotalEmployeeBenefits, 2);?>
                                        </h6>
                                    </td>
                                    <td class="vam ban text-right">
                                        <h6 class="csF16 csB7">
                                            $<?=number_format($gTotalNetPay, 2);?>
                                        </h6>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>