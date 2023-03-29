<!--  -->
<div class="container">
    <div class="csPageWrap">
        <!-- Body -->
        <div class="row">
            <div class="col-sm-12">
                <p class="csF16">
                    Welcome to Payroll. Which of your existing users do you want to run payroll for?
                </p>
            </div>
        </div>
        <br>
        <div class="row">
            <?php
            if (!empty($employees)) :
                foreach ($employees as $employee) :
                    //
                    $canBeOnboard = getEmployeeOnboardCheckForPayroll($employee, true);
            ?>
                    <div class="col-md-6 col-xs-12">
                        <label class="control control--checkbox csF16">
                            <input type="checkbox" <?=$canBeOnboard ? 'disabled' : '';?> class="jsPayrollEmployees" value="<?= $employee['sid']; ?>" name="employees[]" /> <?= remakeEmployeeName($employee); ?>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
            <?php endforeach;
            endif;
            ?>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-black csF16 csB7 jsPayrollBackToWelcome">
                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;
                    Back
                </button>
                <button class="btn btn-orange csF16 csB7 jsPayrollLoadOnboaard">
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;
                    Continue
                </button>
            </div>
        </div>
    </div>
</div>