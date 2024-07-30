<?php if ($benefits) { ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <caption></caption>
            <thead>
                <tr>
                    <th scope="col" class="csW csBG4">
                        Name
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Employees<br />enrolled
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Status
                    </th>
                    <th scope="col" class="csW csBG4 text-right">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($benefits as $value) { ?>
                    <tr data-id="<?= $value['sid'] ?>">
                        <td class="vam">
                            <?= $value['description']; ?>
                        </td>
                        <td class="vam text-right">
                            <?= $value['employee_count']; ?>
                        </td>
                        <td class="vam text-right text-<?= $value['active'] ? 'success' : 'danger'; ?>">
                            <strong>
                                <?= $value['active'] ? 'ACTIVE' : 'INACTIVE'; ?>
                            </strong>
                        </td>
                        <td class="vam text-right">
                            <button class="btn btn-warning jsEditBenefit">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                                &nbsp;Edit benefit
                            </button>
                            <?php if (!$value['employee_count']) { ?>
                                <button class="btn btn-warning jsEditEmployeesBenefit" data-id="<?= $value['sid'] ?>">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    &nbsp;Edit employees
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-warning jsBenefitEmployeesListing">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    &nbsp;Edit employees
                                </button>
                            <?php } ?>
                            <?php if (!$value['employee_count']) { ?>
                                <button class="btn btn-danger jsDeleteBenefit">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    &nbsp;Delete
                                </button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <?php $this->load->view('v1/no_data', ['message' => 'No benefits found.']); ?>
<?php } ?>