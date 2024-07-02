<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>General Ledger</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/css/i9-style.css')?>">
    </head>
    <!-- Set "A5", "A4" or "A3" for class name -->
    <!-- Set also "landscape" if you need -->
    <body class="A4">
        <div>
            <section class="sheet padding-10mm" id="ledger_pdf">
                <article class="sheet-header">
                    <div class="center-col">
                        <h2>General Ledger</h2>
                    </div>
                </article>
                <?php if ($generalLedger) { ?>
                    <?php foreach ($generalLedger as $key => $ledger) { ?>
                        <?php $payrollDetail = json_decode($ledger['totals'],true); ?>
                        <table class="i9-table">
                            <thead>
                                <tr class="bg-gray">
                                    <th colspan="2">
                                        <strong>Payroll for <?=formatDateToDB($ledger['processed_date'], DB_DATE_WITH_TIME, SITE_DATE)?></strong>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td >
                                        <table class="table table-bordered table-hover table-striped" style="width: 100%">
                                            <thead>
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
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>
                <?php } ?>
            </section>
        </div>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
        <script type="text/javascript">
            $( window ).on( "load", function() {
                var action = '<?php echo $action; ?>';
                if (action == 'print') {
                    setTimeout(function(){
                        window.print();
                    }, 1000); 

                    window.onafterprint = function(){
                        window.close();
                    }
                } else if (action == 'download') {
                    var draw = kendo.drawing;
                    draw.drawDOM($("#ledger_pdf"), {
                        avoidLinks: false,
                        paperSize: "auto",
                        multiPage: true,
                        margin: { bottom: "1cm" },
                        scale: 0.8
                    })
                    .then(function(root) {
                        return draw.exportPDF(root);
                    })
                    .done(function(data) {
                        kendo.saveAs({
                            dataURI: data,
                            fileName: '<?php echo "general_ledger.pdf"; ?>',
                        });
                    });
                    setTimeout(function(){
                        window.close();
                    }, 1000);
                }
            });
        </script>
    </body>
</html>
