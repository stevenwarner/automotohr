<!--  -->
<div class="container">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee", "subIndex" =>"employee_payment"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Payment Method
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                        Go paperless with our free Direct Deposit service. Did you know that you now have the option to split your net pay into multiple accounts? Add another bank account to see this in action. Like every additional feature, it comes free of charge.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <img src="<?php echo base_url('assets/images/payroll/bank_info.jpeg');?>" alt="">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Fields marked with an asterisk (<span class="csRequired"></span>) are mandatory.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                        Bank name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsBankName" placeholder="" value="<?=!empty($bank_detail) ? $bank_detail['name'] : '';?>"/>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                        Routing number (9 digits) <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsRoutingNumber" placeholder=""  value="<?=!empty($bank_detail) ? $bank_detail['routing_number'] : '';?>"/>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                        Account number <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsAccountNumber" placeholder=""  value="<?=!empty($bank_detail) ? $bank_detail['account_number'] : '';?>"/>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Account type <span class="csRequired"></span>
                        </label>
                        <select class="form-control jsAccountType">
                            <option value="0">[Select]</option>
                            <option value="Checking" <?=!empty($bank_detail) &&  $bank_detail['account_type'] === "Checking" ? 'selected="selected"' : '';?>>Checking</option>
                            <option value="Savings" <?=!empty($bank_detail) &&  $bank_detail['account_type'] === "Savings" ? 'selected="selected"' : '';?>>Savings</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsBackToPaymentMethod">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsSaveEmployeeBankInfo">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
