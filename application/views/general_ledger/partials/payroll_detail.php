<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <!-- Taxes & Debits -->    
            <div class="hr-box">
                <div class="hr-innerpadding">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead style="background-color: #fd7a2a;">
                                <tr>
                                    <th>Debit Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>Company Debit</b></td>
                                    <td><?php echo _a($payrollDetail['company_debit']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Net Pay Debit</b></td>
                                    <td><?php echo _a($payrollDetail['net_pay_debit']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Tax Debit</b></td>
                                    <td><?php echo _a($payrollDetail['tax_debit']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Reimbursement Debit</b></td>
                                    <td><?php echo _a($payrollDetail['reimbursement_debit']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Child Support Debit</b></td>
                                    <td><?php echo _a($payrollDetail['child_support_debit']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Reimbursements</b></td>
                                    <td><?php echo _a($payrollDetail['reimbursements']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Net Pay</b></td>
                                    <td><?php echo _a($payrollDetail['net_pay']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Gross Pay</b></td>
                                    <td><?php echo _a($payrollDetail['gross_pay']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Employee Bonuses</b></td>
                                    <td><?php echo _a($payrollDetail['employee_bonuses']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Employee Commissions</b></td>
                                    <td><?php echo _a($payrollDetail['employee_commissions']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Employee Cash Tips</b></td>
                                    <td><?php echo _a($payrollDetail['employee_cash_tips']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Additional Earnings</b></td>
                                    <td><?php echo _a($payrollDetail['additional_earnings']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Owners Draw</b></td>
                                    <td><?php echo _a($payrollDetail['owners_draw']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Check Amount</b></td>
                                    <td><?php echo _a($payrollDetail['check_amount']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Employer Taxes</b></td>
                                    <td><?php echo _a($payrollDetail['employer_taxes']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Employee Taxes</b></td>
                                    <td><?php echo _a($payrollDetail['employee_taxes']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Benefits</b></td>
                                    <td><?php echo _a($payrollDetail['benefits']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Employee Benefits Deductions</b></td>
                                    <td><?php echo _a($payrollDetail['employee_benefits_deductions']); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Deferred Payroll Taxes</b></td>
                                    <td><?php echo _a($payrollDetail['deferred_payroll_taxes']); ?></td>
                                </tr>
                                <tr style="background-color: #eeedee;">
                                    <td><b>Other Deductions</b></td>
                                    <td><b><?php echo _a($payrollDetail['other_deductions']); ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>                  