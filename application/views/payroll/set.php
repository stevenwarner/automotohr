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
                            <span class="page-heading down-arrow csF16 csB7">
                                Company - Payroll
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Main -->
                <div class="mainContent">
                    <div class="csPR">
                        <!-- Loader -->
                        <?php $this->load->view('loader_new', ['id' => 'company_onboard']); ?>
                        <!--  -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="csF16 csB7">Company Name <span class="csRequired"></span></label>
                                <p class="csF14">The legal name of the company.</p>
                                <input type="text" class="form-control" id="jsCompanyName" placeholder="AutomotoHR" value="<?=$session['company_detail']['CompanyName'];?>" />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="csF16 csB7">Trade Name <span class="csRequired"></span></label>
                                <p class="csF14">The name of the company.</p>
                                <input type="text" class="form-control" id="jsTradeName" placeholder="AutomotoHR" value="<?=$session['company_detail']['CompanyName'];?>" />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="csF16 csB7">EIN <span class="csRequired"></span></label>
                                <p class="csF14">The employer identification number (EIN) of the company.</p>
                                <input type="text" class="form-control" id="jsEin" placeholder="123654789" value="<?=$session['company_detail']['PhoneNumber'];?>" />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="csF16 csB7">Admin First name <span class="csRequired"></span></label>
                                <p class="csF14">The first name of the user who will be the primary payroll admin.</p>
                                <input type="text" class="form-control" id="jsFirstName" placeholder="John" value="<?=$PrimaryAdmin['first_name'];?>"  />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="csF16 csB7">Admin Last name <span class="csRequired"></span></label>
                                <p class="csF14">The last name of the user who will be the primary payroll admin.</p>
                                <input type="text" class="form-control" id="jsLastName" placeholder="Doe" value="<?=$PrimaryAdmin['last_name'];?>"  />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="csF16 csB7">Email <span class="csRequired"></span></label>
                                <p class="csF14">The email of the user who will be the primary payroll admin.</p>
                                <input type="text" class="form-control" id="jsEmail" placeholder="jhon.doe@example.com" value="<?=$PrimaryAdmin['email'];?>"  />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="csF16 csB7">Phone <span class="csRequired"></span></label>
                                <p class="csF14">The phone number of the user who will be the primary payroll admin.</p>
                                <input type="text" class="form-control" id="jsPhone" placeholder="112345678" value="<?=$PrimaryAdmin['PhoneNumber'];?>"  />
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 text-right">
                                <button class="btn btn-success csF16 csB7 jsSaveBTN">
                                    <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>&nbsp; Add Company To Payroll
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
    window.API_URL = "<?=getAPIUrl('partner');?>";
    window.API_KEY = "<?=getAPIKey();?>";
</script>