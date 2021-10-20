<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "bank", "subIndex" =>""]);?>
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
                            We recommend direct deposit -- we can deposit paychecks directly into your employee's bank accounts.
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
                            <option value="Direct Deposit" <?=!empty($bankInfo) &&  $bankInfo['account_type'] === "Direct Deposit" ? 'selected="selected"' : '';?>>Direct Deposit</option>
                            <option value="check" <?=!empty($bankInfo) &&  $bankInfo['account_type'] === "check" ? 'selected="selected"' : '';?>>check</option>
                        </select>
                    </div>
                </div>
                <br>  
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Split method <span class="csRequired"></span>
                        </label>
                        <select class="form-control jsSplitType">
                            <option value="0">[Select]</option>
                            <option value="Amount" <?=!empty($bankInfo) &&  $bankInfo['account_type'] === "Amount" ? 'selected="selected"' : '';?>>Amount</option>
                            <option value="Percentage" <?=!empty($bankInfo) &&  $bankInfo['account_type'] === "Percentage" ? 'selected="selected"' : '';?>>Percentage</option>
                        </select>
                    </div>
                </div>
                <br> 
                <?php if (!empty($bankInfo)) { ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="csF16">
                                <b><?php echo $bankInfo['routing_number']; ?></b>
                                <br>
                                <span>Routing number (9 digits)</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo $bankInfo['account_number']; ?></b>
                                <br>
                                <span>Account number</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo ucfirst($bankInfo['account_type']); ?></b>
                                <br>
                                <span>Account type</span>
                            </p>
                        </div>
                        <div class="col-sm-6 ">
                            <button class="btn btn-orange csF16 csB7 jsEditBankInfo" data-location_id="<?php echo $bankInfo['sid']; ?>">
                                <i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;
                                Edit
                            </button>
                        </div>
                    </div>
                    <br>
                <?php } ?></b>  
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <button class="btn btn-black csF16 csB7 jsPayrollEmployeeOnboard" data-employee_id="<?php echo $employee_sid; ?>" data-level="4">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollConfirmContinue" data-id="3">
                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;
                            Save & continue
                        </button>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
