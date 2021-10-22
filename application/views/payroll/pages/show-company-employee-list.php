<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Employees
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Select employee to add.
                        </p>
                    </div>
                </div>
                <br>
                <!-- Body -->
                <?php if (!empty($companyEmployees)) { ?>
                    <?php foreach ($companyEmployees as $employee) { ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="csF16">
                                <?=remakeEmployeeName($employee);?> 
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <p class="csF16">
                                <?php 
                                    if ($employee['onboarding_level'] != 0 && $employee['onboarding_level'] < 6) {
                                        echo "<b>Pending</b>";
                                    } else if ($employee['onboarding_level'] == 6) {
                                        echo "<b>Completed</b>";
                                    }
                                ?> 
                                </p>
                            </div>    
                            <div class="col-sm-2 ">
                                <button class="btn btn-orange csF16 csB7 jsPayrollEmployeeOnboard" data-employee_id="<?php echo $employee['sid']; ?>" data-level="<?php echo $employee['onboarding_level']; ?>">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                                    <?php echo $employee['onboarding_level'] == 0 ? "Add" : "Edit"; ?>
                                </button>
                            </div>
                        </div>
                    <?php } ?> 
                    <br>
                <?php } ?> 
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <button class="btn btn-orange csF16 csB7 jsEmployeeListCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
