<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong> Federal Tax</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin-top: 0">
            <strong>Enter federal tax withholdings</strong>
        </h4>
        <h4 style="margin-top: 0">
            <strong>Step 1: Go to the IRS calculator</strong>
        </h4>
        <p class="csF16">
            A portion of each paycheck is withheld to pay income taxes. To determine how much, first go to the IRS calculator to figure out the answers for each field below.
        </p>
        <p class="csF16">
            <strong>What to have ready:</strong>
        </p>
        <p class="csF16">
            <i class="fa fa-check csF16 text-success" aria-hidden="true"></i>&nbsp;
            Total household income
        </p>
        <p class="csF16">
            <i class="fa fa-check csF16 text-success" aria-hidden="true"></i>&nbsp;
            Most recent pay stub (if any)
        </p>
        <p class="csF16">
            <i class="fa fa-check csF16 text-success" aria-hidden="true"></i>&nbsp;
            Most recent tax return (if any)
        </p>
        <p class="csF16">
            <i class="fa fa-check csF16 text-success" aria-hidden="true"></i>&nbsp;
            W-4 form
        </p>
        <h4 style="margin-top: 0">
            <strong>Step 2: Set up withholdings</strong>
        </h4>


        <br>
        <form action="javascript:void(0)">
            <!--  -->
            <div class="form-group">
                <label class="csF16">Federal filing status (1c)<strong class="text-danger">*</strong></label>
                <p class="text-danger">
                    <strong>
                        <em>
                            If you select Exempt from withholding, we won't withhold federal income taxes, but we'll still report taxable wages on a W-2. Keep in mind that anyone who claims exemption from withholding needs to submit a new W-4 each year.
                        </em>
                    </strong>
                </p>
                <select class="form-control jsEmployeeFlow">
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Head of Household">Head of Household</option>
                    <option value="Exempt from withholding">Exempt from withholding</option>
                </select>
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Multiple jobs (2c)</label>
                <p class="text-danger">
                    <strong>
                        <em>
                            Includes spouse (if applicable). Answering 2c results in higher withholding, but to preserve privacy, this can be left unchecked. To learn more, read the <a href="https://www.irs.gov/newsroom/faqs-on-the-2020-form-w-4">IRS's instructions</a>.
                        </em>
                    </strong>
                </p>
                <label class="control control--radio">
                    <input type="radio" name="jsEmployeeFlowMultipleJobs" class="jsEmployeeFlowMultipleJobs" value="yes" />Yes
                    <div class="control__indicator"></div>
                </label>
                <br />
                <label class="control control--radio">
                    <input type="radio" name="jsEmployeeFlowMultipleJobs" class="jsEmployeeFlowMultipleJobs" value="no" />No
                    <div class="control__indicator"></div>
                </label>
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Dependents total (3) (if applicable)</label>
                <p class="text-danger">
                    <strong>
                        <em>
                            Enter the results for line 3 from the <a href="https://www.irs.gov/individuals/tax-withholding-estimator">IRS calculator</a> or <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">form W-4</a>.
                        </em>
                    </strong>
                </p>
                <input type="text" class="form-control jsEmployeeFlow" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Other income (4a)</label>
                <p class="text-danger">
                    <strong>
                        <em>
                            Enter the results for line 4a from the <a href="https://www.irs.gov/individuals/tax-withholding-estimator">IRS calculator</a> or <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">form W-4</a>.
                        </em>
                    </strong>
                </p>
                <input type="text" class="form-control jsEmployeeFlow" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Deductions (4b)</label>
                <p class="text-danger">
                    <strong>
                        <em>
                            Enter the results for line 4b from the <a href="https://www.irs.gov/individuals/tax-withholding-estimator">IRS calculator</a> or <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">form W-4</a>.
                        </em>
                    </strong>
                </p>
                <input type="text" class="form-control jsEmployeeFlow" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Extra withholding (4c)</label>
                <p class="text-danger">
                    <strong>
                        <em>
                            Enter the results for line 4c from the <a href="https://www.irs.gov/individuals/tax-withholding-estimator">IRS calculator</a> or <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">form W-4</a>.
                        </em>
                    </strong>
                </p>
                <input type="text" class="form-control jsEmployeeFlow" />
            </div>

        </form>
    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG3 csF16 jsEmployeeFlowSaveFederalTaxBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>