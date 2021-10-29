<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "bank_verification", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Verify Bank Account
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            We'll verify your bank account with two small deposits
                        </h1>
                        <p class="csF16">
                            Please allow us to 2 business days to receive your test deposits. Once you see the two small deposits Gusto made into your company bank account, input the values below and click "verify deposits" to continue setup.
                        </p>
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
                        Test deposit #1 <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsDepositOne" placeholder="$0.00"  value=""/>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                        Test deposit #2 <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsDepositTwo" placeholder="$0.00"  value=""/>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-orange csF16 csB7 jsVerifyBankDeposit">
                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;
                            Verify Deposits
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
