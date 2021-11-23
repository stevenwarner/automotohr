<div class="container">
    <div class="csPageWrap">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-12 col-sm-12">
                <!-- Actino Button -->
                <div class="row mb12">
                    <div class="col-md-12 col-xs-12">
                        <button class="btn btn-danger csF16 csB7 jsSelectedEmployeesAction pull-right" data-action_type="delete">
                            <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;
                            Delete Employees
                        </button>
                        <button class="btn btn-success csF16 csB7 jsSelectedEmployeesAction pull-right mr10" data-action_type="add">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                            Add Employees
                        </button>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <caption></caption>
                                <thead>
                                    <tr>
                                        <th scope="col" class="csBG1 csF16 csB7 csW">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" class="jsSelectAllEmployees" />
                                            <div class="control__indicator header-checkbox"></div>
                                        </label>
                                        </th>
                                        <th scope="col" class="csBG1 csF16 csB7 csW">Name</th>
                                        <th scope="col" class="csBG1 csF16 csB7 csW">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($companyEmployees)) { ?>
                                        <?php foreach ($companyEmployees as $employee) { ?>
                                            <tr>
                                                <td>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" class=" jsSelectedEmployeesList <?php echo $employee['onboard'] == "yes" ? "jsDeleteEmployees": "jsAddEmployees"; ?>" id="emp_<?php echo $employee['onboard'] == "yes" ? $employee['onboard_id'] : $employee['sid']; ?>" data-employee_name="<?php echo $employee['name']; ?>" value="<?php echo $employee['onboard'] == "yes" ? $employee['onboard_id'] : $employee['sid']; ?>" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?php echo $employee['name']; ?>
                                                </td>
                                                <td>
                                                    <?php if ($employee['onboard'] == "yes") { ?>
                                                        <button class="btn btn-danger csF16 csB7 jsDeleteEmployeeFromGusto pull-right" data-employee_id="<?php echo $employee['onboard_id']; ?>">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;
                                                            Delete from Gusto
                                                        </button>
                                                    <?php } else { ?>
                                                        <button class="btn btn-success csF16 csB7 jsAddEmployeeOnGusto pull-right" data-employee_id="<?php echo $employee['sid']; ?>">
                                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                                                            Add to Gusto
                                                        </button>
                                                    <?php } ?> 
                                                </td>
                                            </tr>
                                        <?php } ?>    
                                    <?php } else { ?> 
                                        <tr>
                                            <td>No Employee Found</td>
                                        </tr>
                                    <?php } ?>        
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="col-sm-8">
                            <label class="control control--checkbox">
                                <input type="checkbox" class="jsSelectAllEmployees" /> Select All Employees
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-sm-4 pr0">
                        </div>        -->
                    </div>    
                </div>    
                
                <?php foreach ($companyEmployees as $employee) { ?>
                    <!-- <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-8">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class=" jsSelectedEmployeesList <?php echo $employee['onboard'] == "yes" ? "jsDeleteEmployees": "jsAddEmployees"; ?>" id="emp_<?php echo $employee['onboard'] == "yes" ? $employee['onboard_id'] : $employee['sid']; ?>" data-employee_name="<?php echo $employee['name']; ?>" value="<?php echo $employee['onboard'] == "yes" ? $employee['onboard_id'] : $employee['sid']; ?>" /> <?php echo $employee['name']; ?>
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="col-sm-4 pr0">
                                <?php if ($employee['onboard'] == "yes") { ?>
                                    <button class="btn btn-danger csF16 csB7 jsDeleteEmployeeFromGusto pull-right" data-employee_id="<?php echo $employee['onboard_id']; ?>">
                                        <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;
                                        Delete from Gusto
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-success csF16 csB7 jsAddEmployeeOnGusto pull-right" data-employee_id="<?php echo $employee['sid']; ?>">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                                        Add to Gusto
                                    </button>
                                <?php } ?>    
                            </div>
                        </div>    
                    </div>
                    <br> -->
                <?php } ?> 
            </div>
        </div>
    </div>
</div>
<style>
    .csModalBody {
        top: 80px !important;
    }

    .header-checkbox{
        top: -5px !important;
    }
</style>