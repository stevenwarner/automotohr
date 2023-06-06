<div class="container">
    <div class="csPageWrap">
        <div class="row">
            <div class="col-xs-12">
                <?php if (empty($employees)) { ?> 
                    <p class="alert alert-info text-center"><strong>Looks like there are no employees that need to be on payroll.</strong></p>
                <?php } else { ?>
                    <?php if ($location == "employee_onboarding") { ?> 
                        <h3 class="alert pl0">Please select the employees that you want to be part of payroll.</h3>
                    <?php } else { ?> 
                        <h3 class="alert pl0">Welcome to Payroll. Which of your existing users do you want to run payroll for?</h3>
                    <?php } ?>          
                <?php } ?>        
            </div>
        </div>
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
                <div class="row" id="jsPayrollEmployeeRow<?= $employee['sid']; ?>">
                    <div class="col-xs-12 col-md-12">
                        <label class="control control--checkbox">
                            <input type="checkbox" <?= $isDisabled == 1 ? 'disabled' : '';?> class="jsEmployeesList" value="<?= $employee['sid']; ?>" name=<?= $isDisabled == 1 ? 'disabled' : 'jsEmployeesList[]';?> /> <?= $employee['full_name_with_role'].$missingInfo; ?>
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
            <?php if ($location == "employee_onboarding") { ?> 
                <button class="btn btn-success" id="jsMoveEmployeesToPayroll"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Onboard Selected Employees To Payroll</button>
            <?php } else { ?> 
                <button class="btn btn-black csF16 csB7 jsPayrollBackToWelcome">
                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;
                    Back
                </button>
                <button class="btn btn-orange csF16 csB7 jsPayrollLoadOnboaard">
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;
                    Continue
                </button>
            <?php } ?>
            </div>
        </div> 
    </div>         
</div>   