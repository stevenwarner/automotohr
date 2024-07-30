<br />
<br />
<div class="container">
    <!--  -->
    <div class="row">
        <div class="col-sm-12 text-right">
            <button class="btn btn-orange jsEditEmployeesBenefit" data-id="<?= $benefitId; ?>">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                &nbsp;Add employee to benefit
            </button>
        </div>
    </div>
    <?php if ($benefitEmployees) { ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col" class="bg-black">
                            Name
                        </th>
                        <th scope="col" class="bg-black text-right">
                            Employee<br />deduction
                        </th>
                        <th scope="col" class="bg-black text-right">
                            Company<br />contribution
                        </th>
                        <th scope="col" class="bg-black text-right">
                            Status
                        </th>
                        <th scope="col" class="bg-black text-right">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($benefitEmployees as $value) { ?>
                        <tr data-id="<?= $value['sid'] ?>">
                            <td class="csVerticalAlignMiddle">
                                <?= remakeEmployeeName($value); ?>
                            </td>
                            <td class="csVerticalAlignMiddle text-right">
                                <?= _a($value['employee_deduction']); ?>
                            </td>
                            <td class="csVerticalAlignMiddle text-right">
                                <?= _a($value['company_contribution']); ?>
                            </td>
                            <td class="csVerticalAlignMiddle text-right text-<?= $value['active'] ? 'success' : 'danger'; ?>">
                                <strong>
                                    <?= $value['active'] ? 'ACTIVE' : 'INACTIVE'; ?>
                                </strong>
                            </td>
                            <td class="csVerticalAlignMiddle text-right">
                                <button class="btn btn-warning csF16 jsEditEmployeeBenefit">
                                    <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                    &nbsp;Edit
                                </button>
                                <button class="btn btn-danger csF16 jsRemoveEmployeeFromBenefit">
                                    <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                                    &nbsp;Delete
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <?php $this->load->view('v1/no_data', ['message' => 'No benefit employees found.']); ?>
    <?php } ?>
</div>