<br />
<br />
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="csF16 m0">
                <strong>
                    Edit benefit of <?= remakeEmployeeName($employeeBenefit); ?>
                </strong>
            </h1>
        </div>
        <div class="panel-body">

            <form action="">
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Employee deduction per pay period
                        <strong class="text-danger"></strong>
                    </label>
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control jsDeduction" value="<?= _a($employeeBenefit['employee_deduction'], ''); ?>" />
                    </div>
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Company contribution per pay period
                        <strong class="text-danger"></strong>
                    </label>
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control jsCompanyContribution" value="<?= _a($employeeBenefit['company_contribution'], ''); ?>" />
                    </div>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Currently active?
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            <em>
                                You can always switch between active and inactive. The information above is saved.
                            </em>
                        </strong>
                    </p>
                    <select class="form-control jsActive">
                        <option <?= $employeeBenefit['active'] ? 'selected' : ''; ?> value="yes">Yes, continue to make deductions and contributions</option>
                        <option <?= !$employeeBenefit['active'] ? 'selected' : ''; ?> value="no">No, stop making deductions and contributions for now</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="panel-footer text-right">
            <input type="hidden" class="jsKey" value="<?= $employeeBenefitId; ?>" />
            <button class="btn csW csBG4 csF16 jsBenefitEmployeesListingWithin" data-key="<?= $employeeBenefit['company_benefit_sid']; ?>">
                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                &nbsp;Back
            </button>
            <button class="btn csW csBG3 csF16 jsUpdateBenefitEmployee" data-key="<?= $employeeBenefitId; ?>">
                <i class="fa fa-save" aria-hidden="true"></i>
                &nbsp;Update
            </button>
        </div>
    </div>
</div>