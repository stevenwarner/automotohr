<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong> Compensation</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin-top: 0">
            <strong>Employee compensation details</strong>
        </h4>
        <p class="text-danger csF16">
            <strong>
                <em>
                    Enter the title, type and salary amount of this employee.
                </em>
            </strong>
        </p>

        <br>
        <form action="javascript:void(0)">
            <!--  -->
            <div class="form-group">
                <label class="csF16">Job title <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control input-lg jsEmployeeFlowJobTitle" placeholder="Technician" value="<?= $primaryJob['title']; ?>" />
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Employee classification <strong class="text-danger">*</strong></label>
                <select class="form-control input-lg jsEmployeeFlowEmployeeClassification">
                    <option <?= $primaryJob['compensation']['flsa_status'] === 'Exempt' ? 'selected' : ''; ?> value="Exempt">Salary/No overtime</option>
                    <option <?= $primaryJob['compensation']['flsa_status'] === 'Salaried nonexempt' ? 'selected' : ''; ?> value="Salaried Nonexempt">Salary/Eligible for overtime</option>
                    <option <?= $primaryJob['compensation']['flsa_status'] === 'Nonexempt' ? 'selected' : ''; ?> value="Nonexempt">Paid by the hour</option>
                    <option <?= $primaryJob['compensation']['flsa_status'] === 'Owner' ? 'selected' : ''; ?> value="Owner">Owner's draw</option>
                </select>
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Amount <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-dollar" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control input-lg jsEmployeeFlowAmount" placeholder="0.0" value="<?= $primaryJob['compensation']['rate']; ?>" />
                </div>
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Per <strong class="text-danger">*</strong></label>
                <select class="form-control input-lg jsEmployeeFlowPer">
                    <option <?= $primaryJou['compensation']['payment_unit'] === 'Hour' ? 'selected' : ''; ?> value="Hour">Per hour</option>
                    <option <?= $primaryJou['compensation']['payment_unit'] === 'Week' ? 'selected' : ''; ?> value="Week">Per week</option>
                    <option <?= $primaryJou['compensation']['payment_unit'] === 'Month' ? 'selected' : ''; ?> value="Month">Per month</option>
                    <option <?= $primaryJou['compensation']['payment_unit'] === 'Year' ? 'selected' : ''; ?> value="Year">Per year</option>
                    <option <?= $primaryJou['compensation']['payment_unit'] === 'Paycheck' ? 'selected' : ''; ?> value="Paycheck">Per paycheck</option>
                </select>
            </div>

        </form>
    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG3 csF16 jsEmployeeFlowSaveCompensationBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>