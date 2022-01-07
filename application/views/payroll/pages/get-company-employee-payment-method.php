<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee_payment", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Payment selection
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Employee payment details
                        </h1>
                        <p class="csF16">
                            We recommend direct deposit — we can deposit paychecks directly into your employees' bank accounts.
                        </p>
                    </div>
                </div>
                <br>
                <!-- Body -->
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Select payment method <span class="csRequired"></span>
                        </label>
                        <select class="form-control jsPaymentMethod">
                            <option value="0">[Select]</option>
                            <option value="Direct Deposit" <?=!empty($payment_method) &&  $payment_method['payment_method'] === "Direct Deposit" ? 'selected="selected"' : 'selected="selected"';?>>Direct Deposit</option>
                            <option value="Check" <?=!empty($payment_method) &&  $payment_method['payment_method'] === "Check" ? 'selected="selected"' : '';?>>Check</option>
                        </select>
                    </div>
                </div>
                <br>  
                <?php
                    $addBankAccount = "";
                    $addSplitType = "";

                    if (!empty($payment_method) && $payment_method['payment_method'] === "Check") {
                        $addBankAccount = 'style="display:none;"';
                        $addSplitType = 'style="display:none;"';
                    }
                ?>
                <div class="row jsBaseOnDD" <?=$addSplitType?>>
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Split method
                        </label>
                        <select class="form-control jsSplitType">
                            <option value="Percentage" <?=!empty($payment_method) &&  $payment_method['split_method'] === "Percentage" ? 'selected="selected"' : '';?>>Percentage</option>
                            <option value="Amount" <?=!empty($payment_method) &&  $payment_method['split_method'] === "Amount" ? 'selected="selected"' : '';?>>Amount</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row jsBaseOnDD" <?=$addBankAccount?>>
                    <div class="col-md-12 col-xs-12">
                        <label class="csF18 csB7">
                            Employee bank account
                        </label>
                        <p class="csF16">
                            Enter the details of the bank account the employee wishes to be paid with. Multiple accounts can be added after continuing this page.
                        </p>
                        <span style="<?=!empty($payment_method) ? 'displayed:none;' : '';?>">
                            <i class="fa fa-plus-circle" aria-hidden="true">&nbsp;&nbsp;</i><a href="javascript:;" class="jsAddEmployeeBankAccount" data-account_id="0">Add bank account</a>
                        </span>
                    </div>
                </div>
                <br>
                <div class="jsBaseOnDD">
                    <?php if (!empty($bank_account)) { ?>
                        <?php foreach ($bank_account as $account) { ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="csF16">
                                        <b><?php echo $account['routing_transaction_number']; ?></b>
                                        <br>
                                        <span>Routing number</span>
                                    </p>
                                    <p class="csF16">
                                        <b><?php echo $account['account_number']; ?></b>
                                        <br>
                                        <span>Account number</span>
                                    </p>
                                    <p class="csF16">
                                        <b><?php echo ucfirst($account['account_type']); ?></b>
                                        <br>
                                        <span>Account type</span>
                                    </p>
                                </div>
                                <div class="col-sm-6 ">
                                    <button class="btn btn-orange csF16 csB7 jsAddEmployeeBankAccount" data-account_id="<?php echo $account['sid']; ?>">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;
                                        Edit
                                    </button>
                                </div>
                            </div>
                            <br>
                        <?php } ?>
                    <?php } ?>
                    <?php if (!empty($payroll_bank_account)) { ?>
                        <?php foreach ($payroll_bank_account as $account) { ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="csF16">
                                        <b><?php echo $account['routing_number']; ?></b>
                                        <br>
                                        <span>Routing number (9 digits)</span>
                                    </p>
                                    <p class="csF16">
                                        <b><?php echo $account['account_number']; ?></b>
                                        <br>
                                        <span>Account number</span>
                                    </p>
                                    <p class="csF16">
                                        <b><?php echo ucfirst($account['account_type']); ?></b>
                                        <br>
                                        <span>Account type</span>
                                    </p>
                                    <p class="csF16">
                                        <b><?php echo $account['account_percentage']; ?></b>
                                        <br>
                                        <span>percentage/Amount</span>
                                    </p>
                                </div>
                                <div class="col-sm-6 ">
                                    <button class="btn btn-orange csF16 csB7 jsDeleteEmployeeBankAccount" data-account_id="<?php echo $account['payroll_bank_uuid']; ?>" data-ddid="<?php echo $account['direct_deposit_id']; ?>">
                                        <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <br>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <button class="btn btn-black csF16 csB7 jsPayrollEmployeeOnboard" data-employee_id="<?php echo $employee_sid; ?>" data-level="4">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollEmployeePaymentMethod" data-id="3">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            Save & continue
                        </button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
