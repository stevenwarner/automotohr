<div class="panel panel-success">
    <div class="panel-heading">
        <p class="csW csF18" style="margin-bottom: 0">
            <strong>Payment details</strong>
        </p>
    </div>
    <div class="panel-body">
        <form action="javascript:void(0)">
            <!--  -->
            <div class="form-group">
                <label class="csF16">
                    Select payment method
                    &nbsp;<strong class="text-danger">*</strong>
                </label>
                <br />
                <label class="control control--radio">
                    <input type="radio" name="jsContractorPaymentMethod" class="jsContractorPaymentMethod" <?= $payment_method['payment_method_type'] === 'Check' ? 'checked' : ''; ?> value="Check" /> Check
                    <div class="control__indicator"></div>
                </label>
                <br />
                <label class="control control--radio">
                    <input type="radio" name="jsContractorPaymentMethod" class="jsContractorPaymentMethod" <?= $payment_method['payment_method_type'] === 'Direct Deposit' ? 'checked' : ''; ?> value="Direct Deposit" /> Direct deposit
                    <div class="control__indicator"></div>
                </label>
                <br>
                <p class="csF16 text-danger jsCheck hidden">
                    <strong>
                        <em>
                            Selecting check as the payment method will require your employer to write a physical check for you every payday.
                        </em>
                    </strong>
                </p>
            </div>

            <div class="jsDirectDeposit hidden">
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Contractor bank account
                    </label>
                    <p class="text-danger csF16">
                        <strong>
                            <em>
                                Enter the details of the bank account the contractor wishes to be paid with.
                            </em>
                        </strong>
                    </p>
                    <img src="<?= base_url('images/check_bank_account.png'); ?>" alt="Bank check" style="width: 100%;" />
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Account name
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control input-lg" id="jsContractorAccountName" value="<?= $bank['name'] ?? ''; ?>" />
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Routing number
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control input-lg" id="jsContractorRoutingNumber" value="<?= $bank['routing_number'] ?? ''; ?>" />
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Account number
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control input-lg" id="jsContractorAccountNumber" value="<?= $bank['account_number'] ?? ''; ?>" />
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Account type
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <br />
                    <label class="control control--radio">
                        <input type="radio" name="jsContractorType" class="jsContractorType" <?= $bank['account_type'] === 'Checking' ? 'checked' : ''; ?> value="Checking" /> Checking
                        <div class="control__indicator"></div>
                    </label>
                    <br />
                    <label class="control control--radio">
                        <input type="radio" name="jsContractorType" class="jsContractorType" <?= $bank['account_type'] === 'Savings' ? 'checked' : ''; ?> value="Savings" /> Savings
                        <div class="control__indicator"></div>
                    </label>
                    <br>
                </div>
            </div>
        </form>
    </div>
    <div class="panel-footer text-right">
        <button class="btn btn-success csF16 jsContractorPaymentMethodSaveBtn">
            <i class="fa fa-save csF16" aria-hidden="true"></i>
            &nbsp;Save
        </button>
        <button class="btn csW csBG4 csF16 jsModalCancel">
            <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
            &nbsp;Cancel
        </button>
    </div>
</div>