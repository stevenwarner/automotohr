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
                        Company contribution Annual Maximum
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            The maximum company contribution amount per year. A null value signifies no limit.
                        </strong>
                    </p>
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control jsCompanyContributionAnnualMaximum" value="<?= _a($employeeBenefit['company_contribution_annual_maximum'], ''); ?>"/>
                    </div>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Limit Option
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Some benefits require additional information to determine their limit. For example, for an HSA benefit, the limit option should be either "Family" or "Individual". For a Dependent Care FSA benefit, the limit option should be either "Joint Filing or Single" or "Married and Filing Separately".
                        </strong>
                    </p>
                    
                    <input type="text" class="form-control jsCompanyLimitOption" value="<?= $employeeBenefit['limit_option']; ?>"/>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Catch Up
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Whether the employee should use a benefit’s "catch up" rate. Only Roth 401k and 401k benefits use this value for employees over 50.
                        </strong>
                    </p>
                    
                    <select class="form-control jsCompanyCatchUp">
                        <option value="false" <?php echo $employeeBenefit['catch_up'] == 0 ? "selected='selected'" : ""; ?>>False</option>
                        <option value="true" <?php echo $employeeBenefit['catch_up'] == 0 ? "selected='selected'" : ""; ?>>True</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Coverage Amount
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            The amount that the employee is insured for. Note: company contribution cannot be present if coverage amount is set.
                        </strong>
                    </p>
                    
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control jsCoverageAmount" value="<?= _a($employeeBenefit['coverage_amount'], ''); ?>"/>
                    </div>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Coverage Salary Multiplier
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            The coverage amount as a multiple of the employee’s salary. Only applicable for Group Term Life benefits. Note: cannot be set if coverage amount is also set.
                        </strong>
                    </p>
                    
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control jsCoverageSalaryMultiplier" value="<?= $employeeBenefit['coverage_salary_multiplier']; ?>"/>
                    </div>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Deduction Reduces Taxable Income
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Whether the employee deduction reduces taxable income or not. Only valid for Group Term Life benefits. Note: when the value is not "unset", coverage amount and coverage salary multiplier are ignored.
                        </strong>
                    </p>
                    
                    <select class="form-control jsDeductionReducesTaxableIncome">
                        <option value="">Please Select</option>
                        <option value="unset" <?php echo $employeeBenefit['deduction_reduces_taxable_income'] == 'unset' ? "selected='selected'" : ""; ?>>Unset</option>
                        <option value="reduces_taxable_income" <?php echo $employeeBenefit['deduction_reduces_taxable_income'] == 'reduces_taxable_income' ? "selected='selected'" : ""; ?>>Reduces taxable income</option>
                        <option value="does_not_reduce_taxable_income" <?php echo $employeeBenefit['deduction_reduces_taxable_income'] == 'does_not_reduce_taxable_income' ? "selected='selected'" : ""; ?>>Does not reduce taxable income</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Currently active?''
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