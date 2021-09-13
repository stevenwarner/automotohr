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
        <div class="panel panel-theme">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-10">
                        <p class="csF16 csB7 csW mt0 mb0">
                            What Your Company Pays
                        </p>
                    </div>
                    <div class="col-sm-2 text-right">
                        <i class="fa fa-plus-circle csF22 csCP jsSectionTrigger" data-target="companies_pay" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="panel-body jsSectionBody dn" data-id="companies_pay">
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
                                            Employees (<?=count($Payroll['employee_compensations']);?>)
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Gross Pay	
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Reimbursements
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Company Taxes	
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Company Benefits	
                                        </h1>
                                    </th>
                                    <th scope="col" class="vam ban csBG2 text-right">
                                        <h1 class="csF18 csB7 mt0 mb0 csW">
                                            Subtotal
                                        </h1>
                                    </th>
                                </tr>

                                <?php 
                                    //
                                    $gTotalGrossPay = 0.00;
                                    $gTotalReimbursements = 0.00;
                                    $gTotalEmployeeTaxes = 0.00;
                                    $gTotalEmployeeBenefits = 0.00;
                                    $gSubTotal = 0.00;
                                ?>

                                <!--  -->
                                <?php foreach($Payroll['employee_compensations'] as $row): ?>
                                    <?php 
                                        //
                                        $emp = $PayrollEmployees[$row['employee_id']];
                                        //
                                        $totalBenifits = 0.00;
                                        //
                                        if(!empty($row['benefits'])){
                                            $totalBenifits = array_sum(array_column($row['benefits'], 'company_contribution'));
                                        }
                                        //
                                        $totalReimbursement = 0.00;
                                        //
                                        if(!empty($row['fixed_compensations']['reimbursement'])){
                                            $totalReimbursement = array_sum(array_column($row['fixed_compensations']['reimbursement'], 'amount'));
                                        }
                                        //
                                        $totalTaxes = 0.00;
                                        //
                                        if(!empty($row['taxes'])){
                                            $totalTaxes = array_sum(array_column(array_filter($row['taxes'], function($tax){
                                                if($tax['employer']) {
                                                    return 1;
                                                }
                                            }), 'amount'));
                                        }
                                        //
                                        $totalPayment = ($row['gross_pay'] + $totalReimbursement ) + ($totalTaxes + $totalBenifits);
                                        //
                                        $gSubTotal += $totalPayment;
                                        $gTotalGrossPay += $row['gross_pay'];
                                        $gTotalReimbursements += $totalReimbursement;
                                        $gTotalEmployeeTaxes += $totalTaxes;
                                        $gTotalEmployeeBenefits += $totalBenifits;

                                    ?>
                                    <tr>
                                       <td class="vam ban">
                                           <h6 class="csF16">
                                           <?=($emp['last_name'].', '.$emp['first_name']);?>
                                           </h6>
                                       </td> 
                                       <td class="vam ban text-right">
                                           <h6 class="csF16">
                                               $<?=number_format($row['gross_pay'], 2);?>
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
                                               $<?=number_format($totalPayment, 2);?>
                                           </h6>
                                       </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="vam ban">
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
                                            $<?=number_format($gSubTotal, 2);?>
                                        </h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="vam ban text-right" colspan="6">
                                        <h6 class="csF26 csB7">
                                            $<?=number_format( 
                                                $Payroll['totals']['gross_pay'] +
                                                $Payroll['totals']['reimbursement_debit'] +
                                                $Payroll['totals']['employer_taxes'] +
                                                $Payroll['totals']['benefits'], 2
                                            );?>
                                        </h6>
                                        <h6 class="csF16 csB7">
                                            Total Payroll
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