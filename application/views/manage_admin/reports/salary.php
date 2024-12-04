<?php
$salary = breakSalary(
    $listing["Salary"],
    $listing["SalaryType"],
    true
);
?>
<div class="container">
    <div class="panel panel-success">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <form action="" id="jsSalaryForm">

                <div class="form-group">
                    <label>Minimum Salary:
                        <span class="text-danger">*</span>
                        <input class="invoice-fields" type="number" name="minSalary" id="minSalary" required value="<?php echo set_value('minSalary', $salary["min"]); ?>" placeholder="20">
                        <?php echo form_error('minSalary'); ?>
                </div>
                <div class="form-group">
                    <label>Maximum Salary:
                        <input class="invoice-fields" type="number" name="maxSalary" id="maxSalary" value="<?php echo set_value('maxSalary', $salary["max"]); ?>" placeholder="30">
                        <?php echo form_error('maxSalary'); ?>
                </div>

                <div class="form-group">
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