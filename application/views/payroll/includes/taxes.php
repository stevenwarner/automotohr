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
                            <span class="page-heading down-arrow">Company Tax Information</span>
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
                                If a default bank account exists, the new bank account will replace it as the company's default funding method. Upon being created, two verification deposits are automatically sent to the bank account, and the bank account's verification_status is 'Awaiting Deposits'. When the deposits are successfully transferred, the verification_status changes to 'Ready For vVerification', at which point the verify endpoint can be used to verify the bank account. After successful verification, the bank account's verification_status is 'Verified'.
                            </p>
                        </div>
                    </div>
                    <br>
                    <!--  -->
                    <div class="row">
                        <div class="col-md-6 col-xs-12 text-left">
                            <label class="csF16">Verification Status: <strong class="text-warning">Awaiting Deposits</strong></label>
                        </div>
                        <div class="col-md-6 col-xs-12 text-right">
                            <button class="btn btn-success csF14 csB7"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Show Bank Account History</button>
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
                            <input type="text" class="form-control" placeholder="115092013" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF14 csB7">
                                Account Number <span class="csRequired"></span>
                            </label>
                            <input type="text" class="form-control" placeholder="9775014007" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF14 csB7">
                                Account Type <span class="csRequired"></span>
                            </label>
                            <select name="" id="" class="form-control">
                                <option value="checking">Checking</option>
                                <option value="saving">Savings</option>
                            </select>
                        </div>
                    </div>
                    <!--  -->
                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-success csF14 csB7">
                                <i class="fa fa-edit csF14" aria-hidden="true"></i>&nbsp;Update Bank Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>