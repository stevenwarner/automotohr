<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong>Payment method</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin-top: 0">
            <strong>Employee payment details</strong>
        </h4>
        <p class="csF16">
            We recommend direct deposit — we can deposit paychecks directly into your employees' bank accounts.
        </p>
        <p class="csF16">
            By selecting check as the payment method you will be required to write a physical check to this employee every payday (we will tell you the exact amount to pay).
        </p>
        <br>
        <form action="javascript:void(0)">


            <!--  -->
            <div class="form-group">
                <label class="csF16">Select payment method&nbsp;
                    <strong class="text-danger">*</strong>
                </label>
                <br>
                <label class="control control--radio">
                    <input type="radio" name="jsEmployeeFlowPaymentMethod" class="jsEmployeeFlowPaymentMethod" value="Direct Deposit" /> Direct Deposit
                    <div class="control__indicator"></div>
                </label>
                <br>
                <label class="control control--radio">
                    <input type="radio" name="jsEmployeeFlowPaymentMethod" class="jsEmployeeFlowPaymentMethod" value="Check Deposit" /> Check
                    <div class="control__indicator"></div>
                </label>
            </div>

        </form>
    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG3 csF16 jsEmployeeFlowSavePaymentMethodBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>