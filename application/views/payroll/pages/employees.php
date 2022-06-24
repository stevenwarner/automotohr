<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Body -->
        <div class="row">
            <div class="col-sm-12">
                <p class="csF28 csB7">
                Which of your existing employees do you want to run payroll for?
                </p>
            </div>
        </div>
        <br>
        <div class="row">
            <?php 
            if(!empty($employees)):
                foreach($employees as $employee):
                ?>
                <div class="col-md-6 col-xs-12">
                    <label class="control control--checkbox csF16">
                        <input type="checkbox" class="jsPayrollEmployees" <?=$employee['can_onboard'] == 0 ? 'disabled' : '';?> value="<?=$employee['sid'];?>" name="employees[]" /> <?=remakeEmployeeName($employee);?> 
                        <?php if($employee['missing_info']): ?>
                            <span class="text-danger"><i aria-hidden="true"> (<?=implode(', ',$employee['missing_info']);?></i>)</span>
                        <?php endif; ?>
                        <div class="control__indicator"></div>
                    </label>
                    
                </div>
            <?php endforeach;
            else:
                ?>
                <div class="col-sm-12">
                    <p class="alert alert-info csF20 text-center">
                        <strong>No employees found!</strong>
                    </p>
                </div>
                <?php
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