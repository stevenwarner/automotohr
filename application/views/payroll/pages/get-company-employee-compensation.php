<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee", "subIndex" =>"employee_address"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Compensation
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Employee compensation details
                        </h1>
                        <p class="csF16">
                            Enter the title, type and salary amount of this employee.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Fields marked with an asterisk (<span class="csRequired"></span>) are mandatory.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Job title <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsJobTitle" value="<?=!empty($employee_job_info) ? $employee_job_info['title'] : '';?>" placeholder="What is this person's job" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Employee type <span class="csRequired"></span>
                        </label>
                        <p class="csF16">
                            Salaried ('Exempt') employees are paid a fixed salary every pay period.
                        </p>
                        <p class="csF16">
                            Salaried with overtime ('Salaried Nonexempt') employees are paid a fixed salary every pay period, and receive overtime pay when applicable.
                        </p>
                        <p class="csF16">
                            Hourly ('Nonexempt') employees are paid for the hours they work, and receive overtime pay when applicable.
                        </p>
                        <p class="csF16">
                            Owners ('Owner') are employees that own at least twenty percent of the company.
                        </p>
                        <select class="form-control jsEmployeeType">
                            <option value="0">[Select]</option>
                            <option value="Exempt" <?=!empty($employee_job_info) &&  $employee_job_info['flsa_status'] === "Exempt" ? 'selected="selected"' : '';?>>Exempt</option>
                            <option value="Salaried Nonexempt" <?=!empty($employee_job_info) &&  $employee_job_info['flsa_status'] === "Salaried Nonexempt" ? 'selected="selected"' : '';?>>Salaried Nonexempt</option>
                            <option value="Nonexempt" <?=!empty($employee_job_info) &&  $employee_job_info['flsa_status'] === "Nonexempt" ? 'selected="selected"' : '';?>>Nonexempt</option>
                            <option value="Owner" <?=!empty($employee_job_info) &&  $employee_job_info['flsa_status'] === "Owner" ? 'selected="selected"' : '';?>>Owner</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Amount
                        </label>
                        <input type="text" class="form-control jsAmount" value="<?=!empty($employee_job_info)  ? $employee_job_info['rate'] : '';?>" placeholder="Enter salary amount" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Salary type <span class="csRequired"></span>
                        </label>
                        <select class="form-control jsSalaryType">
                            <option value="0">[Select]</option>
                            <option value="Hour" <?=!empty($employee_job_info) &&  $employee_job_info['payment_unit'] === "Hour" ? 'selected="selected"' : '';?>>Hour</option>
                            <option value="Week" <?=!empty($employee_job_info) &&  $employee_job_info['payment_unit'] === "Week" ? 'selected="selected"' : '';?>>Week</option>
                            <option value="Month" <?=!empty($employee_job_info) &&  $employee_job_info['payment_unit'] === "Month" ? 'selected="selected"' : '';?>>Month</option>
                            <option value="Year" <?=!empty($employee_job_info) &&  $employee_job_info['payment_unit'] === "Year" ? 'selected="selected"' : '';?>>Year</option>
                            <option value="paycheck" <?=!empty($employee_job_info) &&  $employee_job_info['payment_unit'] === "paycheck" ? 'selected="selected"' : '';?>>paycheck</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsPayrollEmployeeOnboard" data-employee_id="<?php echo $employee_sid; ?>" data-level="1">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveEmployeeJobInfo">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
