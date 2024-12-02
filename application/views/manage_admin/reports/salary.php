<div class="container">
    <div class="panel panel-success">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <form action="" id="jsSalaryForm">
                <div class="form-group">
                    <label class="" for="jsSalary">Salary:</label>
                    <input class="invoice-fields" type="text" name="jsSalary" id="jsSalary" value="<?= $listing["Salary"]; ?>">
                    <p class="text-danger">e.g. $45000 - $65000</p>
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