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
                <input type="text" class="form-control jsEmployeeFlowJobTitle" placeholder="Technician" />
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Employee classification <strong class="text-danger">*</strong></label>
                <select class="form-control jsEmployeeFlowEmployeeClassification">
                    <option value="Exempt">Salary/No overtime</option>
                    <option value="Salaried Nonexempt">Salary/Eligible for overtime</option>
                    <option selected="selected" value="Nonexempt">Paid by the hour</option>
                    <option value="Owner">Owner's draw</option>
                </select>
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Amount <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-dollar" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control jsEmployeeFlowAmount" placeholder="0.0" />
                </div>
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Per <strong class="text-danger">*</strong></label>
                <select class="form-control jsEmployeeFlowPer">
                    <option selected="selected" value="Hour">Per hour</option>
                    <option value="Week">Per week</option>
                    <option value="Month">Per month</option>
                    <option value="Year">Per year</option>
                    <option value="Paycheck">Per paycheck</option>
                </select>
            </div>

        </form>
    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG3 csF16 jsEmployeeFlowSavePersonalDetailsBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>