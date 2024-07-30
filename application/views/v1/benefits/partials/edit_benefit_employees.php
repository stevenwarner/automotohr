<br />
<br />
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>
                    Add employees to benefit
                </strong>
            </h1>
        </div>
        <div class="panel-body">
            <p class="text-medium">
                <strong>
                    Employees
                </strong>
            </p>
            <p class="text-medium">
                Select the employees that you would like to add to this benefit plan. You can also set the default employee deduction and company contribution amount for each pay period.
            </p>
            <p class="text-medium">
                This is a bulk action. You will be able to edit individual employee benefits after creation.
            </p>
            <p class="text-medium">
                Please note, only employees that have completed onboarding can be added to benefits:
            </p>


            <!--  -->
            <br />
            <div class="row">
                <?php if ($employees) { ?>
                    <?php foreach ($employees as $value) { ?>
                        <div class="col-sm-6 col-xs-12">
                            <label class="control control--checkbox">
                                <input type="checkbox" name="employees[]" value="<?= $value['id'] ?>" />
                                <?= $value['name'] ?>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <br />
            <div class="row">
                <div class="col-sm-12 text-right ">
                    <button class="btn btn-orange jsSelectAll">
                        Select All
                    </button>
                    <button class="btn btn-black jsDeSelectAll">
                        Clear All
                    </button>
                </div>
            </div>

            <hr />

            <form action="">
                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Employee deduction per pay period
                        <strong class="text-danger"></strong>
                    </label>

                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control jsDeduction" />
                    </div>
                </div>

                <hr />

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Company contribution per pay period
                        <strong class="text-danger"></strong>
                    </label>
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control jsCompanyContribution" />
                    </div>
                </div>

                <hr />

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
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
                        <input type="number" class="form-control jsCompanyContributionAnnualMaximum" />
                    </div>
                </div>

                <hr />

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Limit Option
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Some benefits require additional information to determine their limit. For example, for an HSA benefit, the limit option should be either "Family" or "Individual". For a Dependent Care FSA benefit, the limit option should be either "Joint Filing or Single" or "Married and Filing Separately".
                        </strong>
                    </p>

                    <input type="text" class="form-control jsCompanyLimitOption" />
                </div>

                <hr>

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Catch Up
                        <strong class="text-danger"></strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Whether the employee should use a benefit’s "catch up" rate. Only Roth 401k and 401k benefits use this value for employees over 50.
                        </strong>
                    </p>

                    <select class="form-control jsCompanyCatchUp">
                        <option value="false">False</option>
                        <option value="true">True</option>
                    </select>
                </div>

                <hr />

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
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
                        <input type="number" class="form-control jsCoverageAmount" />
                    </div>
                </div>

                <hr />

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
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
                        <input type="number" class="form-control jsCoverageSalaryMultiplier" />
                    </div>
                </div>

                <hr>

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
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
                        <option value="unset">Unset</option>
                        <option value="reduces_taxable_income">Reduces taxable income</option>
                        <option value="does_not_reduce_taxable_income">Does not reduce taxable income</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="panel-footer text-right">
            <button class="btn btn-black jsModalCancel">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
            <button class="btn btn-orange jsUpdateBenefitEmployees" data-key="<?= $benefitId; ?>">
                <i class="fa fa-save" aria-hidden="true"></i>
                &nbsp;Update
            </button>
        </div>
    </div>
</div>