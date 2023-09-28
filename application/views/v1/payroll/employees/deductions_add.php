<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong>Deductions</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin: 0;">
            <strong>External post-tax deductions</strong>
        </h4>
        <p class="csF16">
            Amounts are withheld from the net pay of an employee and reported on the paystub and within payroll receipts. Note that these deductions are always post-tax and will start with the next pay period.
        </p>
        <br>
        <!--  -->
        <form action="javascript:void(0)">
            <!--  -->
            <div class="form-group">
                <label>
                    Description
                    &nbsp;<strong class="text-danger">*</strong>
                </label>
                <input type="text" class="form-control" />
            </div>

            <!--  -->
            <div class="form-group">
                <label>
                    Frequency
                    &nbsp;<strong class="text-danger">*</strong>
                </label>
                <br />
                <label class="control control--radio">
                    <input type="radio" name="frequency" value="recurring" />Recurring (every payroll)
                    <div class="control__indicator"></div>
                </label>
                <br />
                <label class="control control--radio">
                    <input type="radio" name="frequency" value="one-time" />One-time (next payroll only)
                    <div class="control__indicator"></div>
                </label>
            </div>

            <!--  -->
            <div class="form-group">
                <label>
                    Deduct as
                    &nbsp;<strong class="text-danger">*</strong>
                </label>
                <br />
                <label class="control control--radio csF100">
                    <input type="radio" name="deduct_as" value="percentage_of_pay" />Percentage of pay
                    <div class="control__indicator"></div>
                </label>
                <br />
                <label class="control control--radio">
                    <input type="radio" name="deduct_as" value="fixed_amount" />Fixed amount ($)
                    <div class="control__indicator"></div>
                </label>
            </div>

            <!--  -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input type="number" class="form-control" />
                </div>
            </div>
            <!--  -->
            <div class="form-group">
                <div class="input-group">
                    <input type="number" class="form-control" />
                    <div class="input-group-addon">%</div>
                </div>
            </div>

            <!--  -->
            <div class="form-group">
                <label>
                    Annual maximum
                    &nbsp;<strong class="text-danger">*</strong>
                </label>
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input type="number" class="form-control" />
                </div>
            </div>

            <!--  -->
            <div class="form-group">
                <label class="control control--checkbox">
                    <input type="checkbox" value="fixed_amount" />This is a court-ordered deduction (Optional)
                    <div class="control__indicator"></div>
                </label>
            </div>
        </form>
    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG4 csF16 jsEmployeeFlowSavePaymentMethodBtn">
            <i class="fa fa-save csF16"></i>
            <span>Cancel</span>
        </button>
        <button class="btn csBG3 csF16 jsEmployeeFlowSavePaymentMethodBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>