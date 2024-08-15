<div class="container">
    <br />
    <div class="panel panel-success">
        <div class="panel-heading">
            <h1 class="csF16 csW m0">
                <strong>
                    Garnishments
                </strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF16 text-danger">
                        <strong>
                            <em>
                                Garnishments, or employee deductions, are fixed amounts or percentages deducted from an employee's pay. They can be deducted a specific number of times or on a recurring basis. Garnishments can also have maximum deductions on a yearly or per-pay-period bases. Common uses for garnishments are court-ordered payments for child support or back taxes. Some companies provide loans to their employees that are repaid via garnishments.
                            </em>
                        </strong>
                    </p>
                </div>
            </div>
            <br>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn csW csBG3 csF16 jsAddGarnishment">
                        <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                        &nbsp;Add a Garnishment
                    </button>
                </div>
            </div>
            <br>

            <?php if ($garnishments) { ?>
                <!--  -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col" class="csW csBG4">
                                    Amount
                                </th>
                                <th scope="col" class="csW csBG4 text-right">
                                    Court<br />ordered
                                </th>
                                <th scope="col" class="csW csBG4 text-right">
                                    Times
                                </th>
                                <th scope="col" class="csW csBG4 text-right">
                                    Recurring
                                </th>
                                <th scope="col" class="csW csBG4 text-right">
                                    Annual<br />maximum
                                </th>
                                <th scope="col" class="csW csBG4 text-right">
                                    Pay<br />period<br />maximum
                                </th>
                                <th scope="col" class="csW csBG4 text-right">
                                    Deduct<br />as<br />Percentage
                                </th>
                                <th scope="col" class="csW csBG4 text-right">
                                    Active
                                </th>
                                <th scope="col" class="csW csBG4 text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($garnishments as $key => $value) { ?>
                                <tr data-id="<?= $value['sid']; ?>">
                                    <td class="vam">
                                        <?= _a($value['amount'], $value['deduct_as_percentage'] ? '%' : '$'); ?>
                                    </td>
                                    <td class="vam text-right">
                                        <?= $value['court_ordered'] ? 'Yes' : 'No'; ?>
                                    </td>
                                    <td class="vam text-right">
                                        <?= $value['times'] ?? '0'; ?>
                                    </td>
                                    <td class="vam text-right">
                                        <?= $value['recurring'] ? 'Yes' : 'No'; ?>
                                    </td>
                                    <td class="vam text-right">
                                        <?php echo $value['annual_maximum'] != '' ? _a($value['annual_maximum'], '') : '0'; ?>
                                    </td>
                                    <td class="vam text-right">
                                        <?= _a($value['pay_period_maximum'], ''); ?>
                                    </td>
                                    <td class="vam text-right">
                                        <?= $value['deduct_as_percentage'] ? 'Yes' : 'No'; ?>
                                    </td>
                                    <td class="vam text-right">
                                        <?= $value['active'] ? 'Yes' : 'No'; ?>
                                    </td>
                                    <td class="vam text-right">
                                        <button class="btn btn-warning csF16 jsEditGarnishment">
                                            <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                            &nbsp;Edit
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <?php $this->load->view('v1/no_data', ['message' => "No garnishments found."]); ?>
            <?php } ?>
        </div>
    </div>
</div>