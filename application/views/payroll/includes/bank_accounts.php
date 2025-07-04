
<!-- Main -->
<div class="mainContent">
    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <p class="csInfo csF14 csB7 text-justify">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                If a default bank account exists, the new bank account will replace it as the company's default funding method. Upon being created, two verification deposits are automatically sent to the bank account, and the bank account's verification_status is 'Awaiting Deposits'. When the deposits are successfully transferred, the verification_status changes to 'Ready For Verification', at which point the verify endpoint can be used to verify the bank account. After successful verification, the bank account's verification_status is 'Verified'.
            </p>
        </div>
    </div>
    <br>
    <div class="csPR">
        <?php $this->load->view('loader_new', ['id' => 'company_bank_account']); ?>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12 text-right">
                <?php if(checkIfAppIsEnabled('payroll')): ?>
                <button class="btn btn-success csF16 csB7 jsVerifyBankAccount dn" title="Verify Bank Account For Payroll" placement="top"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Verify Bank Account</button>
                <button class="btn btn-success csF16 csB7 jsRefreshBankAccount dn" title="Refresh Verification Status" placement="top"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Refresh Status</button>
                <?php endif; ?>
                <button class="btn btn-success csF16 csB7 jsBankAccountHistory"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Show Bank Account History</button>
            </div>
        </div>
        <br>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12 text-left">
                <label class="csF16">Verification Status: <strong class="text-warning jsStatus">-</strong></label>
                <p class="csF14 jsLastModified dn">Last modified by <strong class="jsLastModifiedPerson"></strong> on <strong class="jsLastModifiedTime"></strong></p>
            </div>
        </div>
        <br>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <p class="csF14">Fields marked with an asterisk (<span class="csRequired"></span>) are mandatory.</p>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="csF16 csB7">
                    Routing Number <span class="csRequired"></span>
                </label>
                <input type="text" class="form-control jsBankAccountRoutingNumber" placeholder="115092013" />
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="csF16 csB7">
                    Account Number <span class="csRequired"></span>
                </label>
                <input type="text" class="form-control jsBankAccountNumber" placeholder="9775014007" />
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="csF16 csB7">
                    Account Type <span class="csRequired"></span>
                </label>
                <select class="form-control jsBankAccountType">
                    <option value="checking">Checking</option>
                    <option value="savings">Savings</option>
                </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12 text-right">
                <button class="btn btn-success csF16 csB7 jsBankAccountUpdate">
                    <i class="fa fa-edit csF14" aria-hidden="true"></i>&nbsp;Update Bank Account
                </button>
            </div>
        </div>
    </div>
</div>
            

<script>
    window.API_URL = "<?=getAPIUrl('bank_account');?>"; 
</script>