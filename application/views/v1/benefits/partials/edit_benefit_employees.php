<br />
<br />
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="csF16 m0">
                <strong>
                    Add employees to benefit
                </strong>
            </h1>
        </div>
        <div class="panel-body">
            <p class="csF16">
                <strong>
                    Employees
                </strong>
            </p>
            <p class="csF16">
                Select the employees that you would like to add to this benefit plan. You can also set the default employee deduction and company contribution amount for each pay period.
            </p>
            <p class="csF16">
                This is a bulk action. You will be able to edit individual employee benefits after creation.
            </p>
            <p class="csF16">
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
                    <button class="btn csW csBG3 csF16 jsSelectAll">
                        Select All
                    </button>
                    <button class="btn csW csBG4 csF16 jsDeSelectAll">
                        Clear All
                    </button>
                </div>
            </div>

            <hr />

            <form action="">
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
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
                    <label class="csF16">
                        Company contribution per pay period
                        <strong class="text-danger"></strong>
                    </label>
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control jsCompanyContribution" />
                    </div>
                </div>
            </form>
        </div>

        <div class="panel-footer text-right">
            <button class="btn csW csBG4 csF16 jsModalCancel">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
            <button class="btn csW csBG3 csF16 jsUpdateBenefitEmployees" data-key="<?=$benefitId;?>">
                <i class="fa fa-save" aria-hidden="true"></i>
                &nbsp;Update
            </button>
        </div>
    </div>
</div>