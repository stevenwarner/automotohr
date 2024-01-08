 <?php
    $minimumWagesGroup = [];
    if ($minimumWages) {
        foreach ($minimumWages as $v0) {
            //
            if (!$minimumWagesGroup[$v0["authority"]]) {
                $minimumWagesGroup[$v0["authority"]] = [];
            }
            //
            $minimumWagesGroup[$v0["authority"]][] = $v0;
        }
    }

    _e($jobWageData);
    ?>

 <div class="container">
     <form action="javascript:void(0)" id="jsPagePayScheduleForm">
         <!--  -->
         <div class="panel panel-default">
             <div class="panel-heading">
                 <h2 class="text-medium panel-heading-text">
                     <i class="fa fa-edit text-orange" aria-hidden="true"></i>
                     Manage Job & Wage
                 </h2>
             </div>
             <div class="panel-body">
                 <div class="form-group">
                     <label class="text-medium">
                         Employment type
                         <strong class="text-red">
                             *
                         </strong>
                     </label>
                     <select name="employment_type" class="form-control jsEmploymentType">
                         <option value=""></option>
                         <option <?= $jobWageData["employmentType"] == "fulltime" ? "selected" : ""; ?> value="fulltime">Full time</option>
                         <option <?= $jobWageData["employmentType"] == "parttime" ? "selected" : ""; ?> value="parttime">Part time</option>
                         <option <?= $jobWageData["employmentType"] == "contractual" ? "selected" : ""; ?> value="contractual">Contractual</option>
                     </select>
                 </div>

                 <div class="form-group">
                     <label class="text-medium">
                         FLSA status
                         <strong class="text-red">
                             *
                         </strong>
                         <p class="text-red text-small">

                             The FLSA status for this compensation. <br>
                             Salaried ('Exempt') employees are paid a fixed salary every pay period. <br>
                             Salaried with overtime ('Salaried Nonexempt') employees are paid a fixed salary every pay period, and receive overtime pay when applicable.<br>
                             Hourly ('Nonexempt') employees are paid for the hours they work, and receive overtime pay when applicable.<br>
                             Owners ('Owner') are employees that own at least twenty percent of the company.
                         </p>
                     </label>
                     <select name="flsa_status" class="form-control jsFLSAStatus">
                         <option <?= $jobWageData["flsa_status"] == "Exempt" ? "selected" : ""; ?> value="Exempt">Salary/No overtime</option>
                         <option <?= $jobWageData["flsa_status"] == "Salaried Commission" ? "selected" : ""; ?> value="Salaried Commission">Salary/Commission</option>
                         <option <?= $jobWageData["flsa_status"] == "Salaried Nonexempt" ? "selected" : ""; ?> value="Salaried Nonexempt">Salary/Eligible for overtime</option>
                         <option <?= $jobWageData["flsa_status"] == "Nonexempt" ? "selected" : ""; ?> value="Nonexempt">Paid by the hour</option>
                         <option <?= $jobWageData["flsa_status"] == "Owner" ? "selected" : ""; ?> value="Owner">Owner's draw</option>
                     </select>
                 </div>

                 <div class="form-group">
                     <label class="text-medium">
                         Per
                         <strong class="text-red">
                             *
                         </strong>
                         <p class="text-red text-small">
                             The unit accompanying the compensation rate. If the employee is an owner, rate should be 'Paycheck'.
                         </p>
                     </label>
                     <select name="per" class="form-control jsPayType">
                         <option <?= $jobWageData["per"] == "Hour" ? "selected" : ""; ?> value="Hour">Per hour</option>
                         <option <?= $jobWageData["per"] == "Week" ? "selected" : ""; ?> value="Week">Per week</option>
                         <option <?= $jobWageData["per"] == "Month" ? "selected" : ""; ?> value="Month">Per month</option>
                         <option <?= $jobWageData["per"] == "Year" ? "selected" : ""; ?> value="Year">Per year</option>
                         <option <?= $jobWageData["per"] == "Paycheck" ? "selected" : ""; ?> value="Paycheck">Per paycheck</option>
                     </select>
                 </div>

                 <div class="form-group">
                     <label class="text-medium">
                         Hire date
                         <strong class="text-red">
                             *
                         </strong>
                     </label>
                     <input type="text" class="form-control readonly jsHireDate" name="hire_date" value="<?= $jobWageData["hireDate"] ? formatDateToDB($jobWageData["hireDate"],DB_DATE,SITE_DATE): ''; ?>" readonly placeholder="MM/DD/YYYY" />
                 </div>

                 <div class="form-group">
                     <label class="text-medium">
                         Rate
                         <strong class="text-red">
                             *
                         </strong>
                     </label>
                     <input type="number" class="form-control readonly jsEmployeeRate" name="rate" placeholder="0.00" value="<?= $jobWageData["rate"]; ?>" />
                 </div>
                 <div class="form-group">
                     <label class="text-medium">
                         Overtime Rule
                         <strong class="text-red">
                             *
                         </strong>
                     </label>
                     <select name="overtime_rule" class="form-control jsOvertimeRule">
                         <option value="">Please select over time rule</option>
                         <?php
                            if ($overtimeRules) {
                                foreach ($overtimeRules as $v0) {
                            ?>
                                 <option value="<?= $v0["sid"]; ?>" <?= $jobWageData["overtimeRule"] == $v0["sid"] ? "selected" : ""; ?>><?= $v0["rule_name"]; ?></option>
                         <?php
                                }
                            }
                            ?>
                     </select>
                 </div>

                 <div class="form-group">
                     <label class="control control--checkbox">
                         <input type="checkbox" name="adjust_for_minimum_wage" class="jsAdjustForMinimumWage" <?= $jobWageData["adjustForMinimumWage"] == 1 ? "checked" : ""; ?> />
                         Adjust for minimum wages
                         <div class="control__indicator"></div>
                     </label>
                     <p class="text-red text-small">
                         <strong>
                             Determines whether the compensation should be adjusted for minimum wage. Only applies to Nonexempt employees.
                         </strong>
                     </p>
                 </div>
                 <div class="form-group jsMinimumWagesBox  <?= $jobWageData["adjustForMinimumWage"] == 1 ? "" : "hidden"; ?>">
                     <label class="text-medium">
                         Minimum wages
                         <strong class="text-red">
                             *
                         </strong>
                     </label>
                     <select name="minimum_wages[]" class="form-control jsMinimumWages" multiple>
                         <?php
                            if ($minimumWagesGroup) {
                                foreach ($minimumWagesGroup as $index => $v0) {
                            ?>
                                 <optgroup label="<?= $index; ?>"></optgroup>
                                 <?php foreach ($v0 as $v1) { ?>
                                     <option value="<?= $v1["sid"]; ?>"><?= $v1["wage_type"]; ?> $<?= $v1["wage"]; ?> effected at <?= formatDateToDB($v1["effective_date"], DB_DATE, DATE); ?></option>
                                 <?php } ?>
                         <?php
                                }
                            }
                            ?>
                     </select>
                 </div>
             </div>
             <div class="panel-footer text-center">
                 <button class="btn btn-orange jsPagePayScheduleBtn">
                     <i class="fa fa-edit" aria-hidden="true"></i>
                     Update
                 </button>
                 <button class="btn btn-black jsModalCancel">
                     <i class="fa fa-times-circle" aria-hidden="true"></i>
                     Cancel
                 </button>
             </div>
         </div>
     </form>
 </div>