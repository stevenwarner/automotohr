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
            <?php if (!empty($employees)) { ?> 
                <?php foreach ($employees as $employee) { ?>
                    <?php 
                        $isDisabled = 0;
                        $missingInfo = '';
                        //
                        if (!empty($employee['missing_fields'])) {
                            $isDisabled = 1;
                            $missingInfo = '--- <span class="text-danger">Missing Fields ['.implode(", ", $employee['missing_fields']).']</span> ';
                        }
                    ?>
                    <div class="col-md-12 col-xs-12">
                        <label class="control control--checkbox csF16">
                            <input type="checkbox" <?= $isDisabled == 1 ? 'disabled' : '';?> class="jsPayrollEmployees" value="<?= $employee['sid']; ?>" name="employees[]" /> <?= $employee['full_name_with_role'].$missingInfo; ?>
                            <div class="control__indicator"></div>
                            <!-- -->
                        </label>
                    </div>
                <?php } ?>
            <?php } ?>
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