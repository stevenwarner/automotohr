<div class="container">
    <div class="">
        <?php if (!$employees) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <p class="alert alert-info text-center">
                        <strong>
                            Looks like there are no employees that need to be on payroll.
                        </strong>
                    </p>
                </div>
            </div>
        <?php } else { ?>
            <h3 class="alert pl0">Welcome to Payroll. Which of your existing users do you want to run payroll for?</h3>

            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-orange text-medium jsSelectAll">
                        Select all
                    </button>
                    <button class="btn btn-black text-medium jsUnSelectAll">
                        Remove all
                    </button>
                </div>
            </div>
            <hr />
            <?php foreach ($employees as $employee) {
                $isDisabled = 0;
                $missingInfo = '';
                //
                if (!empty($employee['missing_fields'])) {
                    $isDisabled = 1;
                    $missingInfo = ' --- <span class="text-danger">Missing Fields [' . implode(", ", $employee['missing_fields']) . ']</span> ';
                } ?>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <label class="control control--checkbox">
                            <input type="checkbox" <?= $isDisabled == 1 ? 'disabled' : ''; ?> class="<?= $isDisabled == 1 ? '' : 'jsSingleEmployee'; ?> jsEmployeesList" value="<?= $employee['sid']; ?>" name=<?= $isDisabled == 1 ? '' : 'jsEmployeesList[]'; ?> /> <?= $employee['full_name_with_role'] . $missingInfo; ?>
                            <div class="control__indicator"></div>
                            <!-- -->
                        </label>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <br />
        <div class="row">
            <div class="col-xs-12 <?php echo $location == 'employee_onboarding' ? 'text-right' : ''; ?>">
                <button class="btn btn-black text-medium csB7 jsBackToStep1">
                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;
                    Back
                </button>
                <button class="btn btn-orange text-medium csB7 jsPayrollLoadOnboard">
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;
                    Continue
                </button>
            </div>
        </div>
    </div>
</div>