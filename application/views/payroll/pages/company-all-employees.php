<div class="container">
    <div class="csPageWrap">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Company Employees
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <?php foreach ($companyEmployees as $employee) { ?>
                    <div class="row">
                        <div class="col-sm-8">
                            <label class="control control--checkbox">
                                <input type="checkbox" class=" jsSelectedEmployeesList <?php echo $employee['onboard'] == "yes" ? "jsDeleteEmployees": "jsAddEmployees"; ?>" id="emp_<?php echo $employee['onboard'] == "yes" ? $employee['onboard_id'] : $employee['sid']; ?>" data-employee_name="<?php echo $employee['name']; ?>" value="<?php echo $employee['onboard'] == "yes" ? $employee['onboard_id'] : $employee['sid']; ?>" /> <?php echo $employee['name']; ?>
                                <div class="control__indicator"></div>
                            </label>
                            <!-- <p class="csF16 mb0">
                                
                            </p> -->
                        </div>
                        <div class="col-sm-4 ">
                            <?php if ($employee['onboard'] == "yes") { ?>
                                <button class="btn btn-orange csF16 csB7 jsDeleteEmployeeFromGusto" data-employee_id="<?php echo $employee['onboard_id']; ?>">
                                    <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;
                                    Delete
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-orange csF16 csB7 jsAddEmployeeOnGusto" data-employee_id="<?php echo $employee['sid']; ?>">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                                    Add
                                </button>
                            <?php }  ?>    
                        </div>
                    </div>
                    <br>
                <?php } ?> 
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <button class="btn btn-orange csF16 csB7 jsSelectedEmployeesAction" data-action_type="delete">
                            <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;
                            Delete Employees
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsSelectedEmployeesAction" data-action_type="add">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                            Add Employees
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>