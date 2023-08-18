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
                    <input type="radio" name="jsEmployeeFlowPaymentMethodType" class="jsEmployeeFlowPaymentMethodType" <?= $record && $record['type'] == 'Direct Deposit' ? 'checked' : ''; ?> value="Direct Deposit" /> Direct Deposit
                    <div class="control__indicator"></div>
                </label>
                <br>
                <label class="control control--radio">
                    <input type="radio" name="jsEmployeeFlowPaymentMethodType" class="jsEmployeeFlowPaymentMethodType" <?= $record && $record['type'] == 'Check' ? 'checked' : ''; ?> value="Check" /> Check
                    <div class="control__indicator"></div>
                </label>
            </div>
        </form>

        <div class="jsEmployeeFlowPaymentMethodAccountBox <?= $record && $record['type'] === 'Direct Deposit' ? '' : 'hidden'; ?>">
            <!--  -->
            <br />
            <h4>
                <strong>
                    Employee bank account
                </strong>
            </h4>
            <p class="csF16">
                Enter the details of the bank account the employee wishes to be paid with. Multiple accounts can be added after continuing this page.
            </p>

            <?php if ($record) {
                $bankAccounts = json_decode($record['splits'], true);
                //
                if ($bankAccounts) {
                    foreach ($bankAccounts as $index => $account) {
            ?>
                        <div class="alert alert-<?= $index === 0 ? 'success' : 'info' ?>">>
                            <p>
                                <strong><?= $record['name']; ?></strong>
                                <sup>Account Name</sup>
                            </p>
                            <p>
                                <strong><?= $record['hidden_account_number']; ?></strong>
                                <sup>Account Number</sup>
                            </p>
                        </div>
            <?php
                    }
                }
            } ?>
            <?php if ($record && json_decode($record['splits'], true) <= 2) { ?>
                <button class="btn csBG4 csW csF16 jsEmployeeFlowPaymentMethodAddBankAccountBtn">
                    <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                    &nbsp;Add a bank account
                </button>
            <?php } ?>
        </div>


    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG3 csF16 jsEmployeeFlowPaymentMethodSaveBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>