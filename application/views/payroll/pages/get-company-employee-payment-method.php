<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee_payment", "subIndex" => ""]);?>
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
                            <option value="Direct Deposit" <?=!empty($payment_method) &&  $payment_method['payment_method'] === "Direct Deposit" ? 'selected="selected"' : 'selected="selected"';?>>Direct Deposit</option>
                            <option value="Check" <?=!empty($payment_method) &&  $payment_method['payment_method'] === "Check" ? 'selected="selected"' : '';?>>Check</option>
                        </select>
                    </div>
                </div>
                <br>  
                <?php
                    $addBankAccount = "";
                    $addSplitType = "";
                    $jsBaseOnDD = "";

                    if (!empty($payment_method) && $payment_method['payment_method'] === "Check") {
                        $addBankAccount = 'style="display:none;"';
                        $addSplitType = 'style="display:none;"';
                        $jsBaseOnDD = 'style="display:none;"';
                    }
                ?>
                <!--  -->
                <div class="row jsBaseOnC" <?=$payment_method['payment_method'] != 'Check' ? 'style="display: none;"' : '';?>>
                    <div class="col-sm-12">
                        <p class="csF16">By selecting Check as the payment method you will be required to write a physical check to this employee every payday (we will tell you the exact amount to pay).</p>
                    </div>
                </div>
                <!--  -->
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
                <div class="jsBaseOnDD" <?=$jsBaseOnDD;?>>
                <?php
                    $payroll_bank_accounts = $payment_method['splits'] ? json_decode($payment_method['splits'], true) : [];
                ?>
                    <?php if (!empty($payroll_bank_accounts)) { ?>
                        <?php foreach ($payroll_bank_accounts as $account) { ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="csF16">
                                        <span>Bank name</span>
                                        <br>
                                        <b><?php echo $account['name']; ?></b>
                                    </p>
                                    <p class="csF16">
                                        <span>Routing number</span>
                                        <br>
                                        <b><?php echo $account['routing_number']; ?></b>
                                    </p>
                                    <p class="csF16">
                                        <span>Account number</span>
                                        <br>
                                        <b><?php echo $account['account_number']; ?></b>
                                    </p>
                                    <p class="csF16">
                                        <span>Account type</span>
                                        <br>
                                        <b><?php echo ucfirst($account['account_type']); ?></b>
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
