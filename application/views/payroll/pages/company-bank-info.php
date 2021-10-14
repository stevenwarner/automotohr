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
                            Company bank account information
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Company bank account
                        </h1>
                        <p class="csF16">
                            We'll use your checking acount into to debit for wagesand taxes. Your account must be linked to a checking bank account. Credit payments, credit cards, and savings accounts are not accepted.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Fields marked with asterisk (<span class="csRequired"></span>) are mendatory.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                        Routing number (9 digits) <span class="csRequired"></span>
                        </label>
                        <p class="csF14">Your Company's federal Employer identification number(EIN). If you do not have one, please apply online.</p>
                        <input type="text" class="form-control jsRoutingNumber" placeholder=""  value="<?=!empty($bankInfo) ? $bankInfo['routing_number'] : '';?>"/>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                        Account number <span class="csRequired"></span>
                        </label>
                        <p class="csF14">Your Company's federal Employer identification number(EIN). If you do not have one, please apply online.</p>
                        <input type="text" class="form-control jsAccountNumber" placeholder=""  value="<?=!empty($bankInfo) ? $bankInfo['account_number'] : '';?>"/>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Acount type <span class="csRequired"></span>
                        </label>
                        <select class="form-control jsAccountType">
                            <option value="0">[Select]</option>
                            <option value="checking" <?=!empty($bankInfo) &&  $bankInfo['account_type'] === "checking" ? 'selected="selected"' : '';?>>Checking</option>
                            <option value="savings" <?=!empty($bankInfo) &&  $bankInfo['account_type'] === "savings" ? 'selected="selected"' : '';?>>Savings</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14">By selecting continue I acknowledge I won't be able to run payroll for up to 2 business days untill the bank varification completes.</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsBankInfoCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsBankInfoUpdate">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
