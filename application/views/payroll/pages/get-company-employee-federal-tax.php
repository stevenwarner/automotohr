<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee", "subIndex" =>"employee_federal_tax"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Federal tax withhoaldings
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Step 1: go to the IRS calculator
                        </h1>
                        <p class="csF16">
                            The IRS has a new W-4 form to make withholdings more accurate. If you’re updating or filling out a new W-4 after Jan 1, 2020, you have to use the new form. <a href="https://gusto.com/blog/payroll/how-fill-out-w-4">Learn more on our blog</a>
                        </p>
                        <a class="btn btn-orange csF16 csB7" href="https://www.irs.gov/individuals/tax-withholding-estimator" target="_blank">
                            Go to the IRS Calculator
                        </a>
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
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Step 2: update withholdings
                        </h1>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Federal filing status (1c) (required) <span class="csRequired"></span>
                        </label>
                        <p class="csF16">
                            If you select Exempt from withholding, we won't withhold federal income taxes, but we'll still report taxable wages on a W-2. Keep in mind that anyone who claims exemption from withholding needs to submit a new W-4 each year.
                        </p>
                        <select class="form-control jsFederalFilingStatus">
                            <option value="0">Select…</option>
                            <option value="Single" <?=!empty($federal_tax_info) &&  $federal_tax_info['filing_status'] === "Single" ? 'selected="selected"' : '';?>>Single or married filing separately</option>
                            <option value="Married" <?=!empty($federal_tax_info) &&  $federal_tax_info['filing_status'] === "Married" ? 'selected="selected"' : '';?>>Married filing jointly</option>
                            <option value="Head of Household" <?=!empty($federal_tax_info) &&  $federal_tax_info['filing_status'] === "Head of Household" ? 'selected="selected"' : '';?>>Head of household</option>
                            <option value="Exempt from withholding" <?=!empty($federal_tax_info) &&  $federal_tax_info['filing_status'] === "Exempt from withholding" ? 'selected="selected"' : '';?>>Exempt from withholding</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Multiple jobs (2c)
                        </label>
                        <p class="csF16">
                            Includes spouse (if applicable). Answering 2c results in higher withholding, but to preserve privacy, this can be left unchecked. To learn more, read <a href="https://www.irs.gov/newsroom/faqs-on-the-2020-form-w-4">the IRS's instructions</a>
                        </p>
                        <select class="form-control jsMultipleJobs">
                            <option value="0">Select…</option>
                            <option value="true" <?=!empty($federal_tax_info) &&  $federal_tax_info['multiple_jobs'] === "true" ? 'selected="selected"' : '';?>>Household has only 2 jobs total (see instructions)</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Dependent total (3) (if applicable)
                        </label>
                        <p class="csF16">
                            Enter the results for line 3 from the <a href="https://www.irs.gov/individuals/tax-withholding-estimator">IRS calculator</a> or <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">form W-4.</a>
                        </p>
                        <div class="input-group">
                            <span class="input-group-addon text-module__inputGroupAddon___2DuMQ">$</span>
                            <input type="text" class="form-control jsDependentTotal" placeholder="0.00" value="<?=!empty($federal_tax_info)  ? $federal_tax_info['dependent'] : '';?>">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                        Other income (4a) (if applicable)
                        </label>
                        <p class="csF16">
                            Enter the results for line 4a from the <a href="https://www.irs.gov/individuals/tax-withholding-estimator">IRS calculator</a> or <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">form W-4.</a>
                        </p>
                        <div class="input-group">
                            <span class="input-group-addon text-module__inputGroupAddon___2DuMQ">$</span>
                            <input type="text" class="form-control jsOtherIncome" placeholder="0.00" value="<?=!empty($federal_tax_info)  ? $federal_tax_info['other_income'] : '';?>">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Deductions (4b)
                        </label>
                        <p class="csF16">
                            Enter the results for line 4b from the <a href="https://www.irs.gov/individuals/tax-withholding-estimator">IRS calculator</a> or <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">form W-4.</a>
                        </p>
                        <div class="input-group">
                            <span class="input-group-addon text-module__inputGroupAddon___2DuMQ">$</span>
                            <input type="text" class="form-control jsDeductions" placeholder="0.00" value="<?=!empty($federal_tax_info)  ? $federal_tax_info['deductions'] : '';?>">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Extra withholding (4c) 
                        </label>
                        <p class="csF16">
                            Enter the results for line 4c from the <a href="https://www.irs.gov/individuals/tax-withholding-estimator">IRS calculator</a> or <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">form W-4.</a>
                        </p>
                        <div class="input-group">
                            <span class="input-group-addon text-module__inputGroupAddon___2DuMQ">$</span>
                            <input type="text" class="form-control jsExtraWithholding" placeholder="0.00" value="<?=!empty($federal_tax_info)  ? $federal_tax_info['extra_withholding'] : '';?>">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsPayrollEmployeeOnboard" data-employee_id="<?php echo $employee_sid; ?>" data-level="2">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveEmployeeFederalTax">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
