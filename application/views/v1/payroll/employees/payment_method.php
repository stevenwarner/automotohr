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
                //
                if ($bankAccounts) {
                    foreach ($bankAccounts as $index => $account) {
            ?>
                        <div class="jsEmployeeFlowDeleteBankAccountRow alert alert-<?= $index === 0 ? 'success' : 'info' ?>" data-id="<?= $account['sid']; ?>">
                            <p>
                                <strong class="csF16"><?= $account['account_title']; ?></strong>
                                <br>
                                <sup>Account Name</sup>
                            </p>
                            <p>
                                <strong class="csF16"><?= $account['account_number']; ?></strong>
                                <br>
                                <sup>Account Number</sup>
                            </p>
                            <?php if ($account['gusto_uuid']) { ?>
                                <hr />
                                <button class="btn btn-danger csF16 jsEmployeeFlowDeleteBankAccount">
                                    <i class="fa fa-times-circle csF16"></i>
                                    &nbsp;Delete
                                </button>
                            <?php } else { ?>
                                <hr />
                                <button class="btn csW csBG3 csF16 jsEmployeeFlowUseBankAccount">
                                    <i class="fa fa-check-circle csF16"></i>
                                    &nbsp;Use for payroll
                                </button>
                            <?php } ?>
                        </div>
            <?php
                    }
                }
            } ?>
            <?php if ($record && count($bankAccounts) < 2) { ?>
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