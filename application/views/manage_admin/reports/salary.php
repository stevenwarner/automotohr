<div class="container">
    <div class="panel panel-success">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <form action="" id="jsSalaryForm">
                <?php
                $salaryArray = setTheSalary(
                    $listing["Salary"],
                    $listing["SalaryType"]
                );

                $maxsalary = '';
                if ($salaryArray["max"] != $salaryArray["min"]) {
                    $maxsalary = $salaryArray["max"];
                }
             //   _e($salaryArray, true);
                ?>
                <div class="form-group">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3" style="padding-left: 0px;">
                        <label>Min Salary:</label>
                        <input class="invoice-fields" type="text" name="jsMinSalary" id="jsMinSalary" value="<?php echo set_value('Salary', $salaryArray["min"]); ?>">
                        <div class="video-link" style='font-style: italic;'><b></b>
                        </div>
                        <?php echo form_error('MinSalary'); ?>
                    </div>

                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <label>Max Salary:</label>
                        <input class="invoice-fields" type="text" name="jsMaxSalary" id="jsMaxSalary" value="<?php echo set_value('Salary',$maxsalary); ?>">
                        <div class="video-link" style='font-style: italic;'><b></b>
                        </div>
                        <?php echo form_error('MaxSalary'); ?>
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <label class="" for="jsSalaryType">Salary Type:</label>
                        <div class="hr-select-dropdown">
                            <select class="invoice-fields" name="jsSalaryType" id="jsSalaryType">
                                <option value="per_hour" <?php if ($listing["SalaryType"] == "per_hour") { ?>selected<?php } ?>>per hour</option>
                                <option value="per_week" <?php if ($listing["SalaryType"] == "per_week") { ?>selected<?php } ?>>per week</option>
                                <option value="per_month" <?php if ($listing["SalaryType"] == "per_month") { ?>selected<?php } ?>>per month</option>
                                <option value="per_year" <?php if ($listing["SalaryType"] == "per_year") { ?>selected<?php } ?>>per year</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                </div>
            </form>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-warning jsSalaryUpdate">
                <i class="fa fa-edit"></i>
                Update
            </button>
        </div>
    </div>
</div>