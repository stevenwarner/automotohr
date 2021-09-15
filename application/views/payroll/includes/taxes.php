<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Side Menu -->
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- profile_left_menu_company -->
            </div>
            <!-- Main Content Area -->
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <!-- Content Header -->
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?=$title;?></span>
                        </div>
                    </div>
                </div>
                <!-- Main -->
                <div class="mainContent">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="csInfo csB7 text-justify">
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
                            <div class="col-md-6 col-xs-12 text-left">
                                <label class="csF16">Verification Status: <strong class="text-warning jsStatus">-</strong></label>
                                <p class="csF14 jsLastModified dn">Last modified by <strong class="jsLastModifiedPerson"></strong> on <strong class="jsLastModifiedTime"></strong></p>
                            </div>
                            <div class="col-md-6 col-xs-12 text-right">
                                <?php if(checkIfAppIsEnabled('payroll')): ?>
                                <button class="btn btn-success csF14 csB7 jsVerifyBankAccount dn"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Verify Bank Account</button>
                                <?php endif; ?>
                                <button class="btn btn-success csF14 csB7 jsBankAccountHistory"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Show Bank Account History</button>
                            </div>
                        </div>
                        <br>
                        <!--  -->
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <p>Fields marked with an asterisk (<span class="csRequired"></span>) are mandatory.</p>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label class="csF14 csB7">
                                    Routing Number <span class="csRequired"></span>
                                </label>
                                <input type="text" class="form-control jsBankAccountRoutingNumber" placeholder="115092013" />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label class="csF14 csB7">
                                    Account Number <span class="csRequired"></span>
                                </label>
                                <input type="text" class="form-control jsBankAccountNumber" placeholder="9775014007" />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label class="csF14 csB7">
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
                                <button class="btn btn-success csF14 csB7 jsBankAccountUpdate">
                                    <i class="fa fa-edit csF14" aria-hidden="true"></i>&nbsp;Update Bank Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.API_URL = "<?=getAPIUrl('tax');?>"; 
    window.API_KEY = "<?=getAPIKey();?>"; 
</script>