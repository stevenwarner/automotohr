<?php
$taxes = extractTaxes($payroll['employees']);
$totals = [
    'company_taxes' => 0,
    'employee_taxes' => 0,
];
?>
<br>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csF16 m0 csW">
            <strong>Tax breakdown</strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <th scope="col" class="csW csBG4">
                        Tax name
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Employee taxes
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Company taxes
                    </th>
                </thead>
                <tbody>

                    <?php foreach ($taxes as $value) { ?>
                        <?php
                        //
                        $totals['company_taxes'] += $value['employer_tax_total'];
                        $totals['employee_taxes'] += $value['employee_tax_total'];
                        ?>
                        <tr>
                            <td class="vam">
                                <?= $value['name']; ?>
                            </td>

                            <td class="vam text-right">
                                <?= _a($value['employee_tax_total']); ?>
                            </td>
                            <td class="vam text-right">
                                <?= _a($value['employer_tax_total']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th scope="col" class="vam">
                            Totals
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($totals['employee_taxes']); ?>
                        </th>
                        <th scope="col" class="vam text-right">
                            <?= _a($totals['company_taxes']); ?>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>