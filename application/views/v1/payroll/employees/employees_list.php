<div class="container">
    <div class="csPageWrap">
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
            <h3 class="alert pl0">Please select the employees that needs to be onboard to payroll.</h3>
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
                            <input type="checkbox" <?= $isDisabled == 1 ? 'disabled' : ''; ?> class="jsEmployeesList" value="<?= $employee['sid']; ?>" name=<?= $isDisabled == 1 ? '' : 'jsEmployeesList[]'; ?> /> <?= $employee['full_name_with_role'] . $missingInfo; ?>
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
                <button class="btn btn-orange csF16 csB7 jsPayrollOnboardEmployees">
                    <i class="fa fa-save csF16" aria-hidden="true"></i>&nbsp;
                    Onboard Selected Employees
                </button>
            </div>
        </div>
    </div>
</div>