<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong>Payment details</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin-top: 0">
            <strong>Employee bank account</strong>
        </h4>
        <p class="csF16">
            Enter the details of the bank account the employee wishes to be paid with. We’ll send a small test deposit to confirm the account.
        </p>
        <img src="<?= base_url('images/check_bank_account.png'); ?>" alt="Bank check" style="width: 100%;" />
        <br>
        <br>
        <form action="javascript:void(0)">
            <!--  -->
            <div class="form-group">
                <label class="csF16">Account title&nbsp;
                    <strong class="text-danger">*</strong>
                </label>
                <input type="number" class="form-control jsEmployeeFlowBankAccountTitle" />
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Routing number (9 digits)&nbsp;
                    <strong class="text-danger">*</strong>
                </label>
                <input type="number" class="form-control jsEmployeeFlowBankAccountRoutingNumber" />
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Account number&nbsp;
                    <strong class="text-danger">*</strong>
                </label>
                <input type="number" class="form-control jsEmployeeFlowBankAccountAccountNumber" />
            </div>
            <!--  -->
            <div class="form-group">
                <label class="csF16">Account type&nbsp;
                    <strong class="text-danger">*</strong>
                </label>
                <select class="form-control jsEmployeeFlowBankAccountType">
                    <option value="Checking">Checking</option>
                    <option value="Savings">Savings</option>
                </select>
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