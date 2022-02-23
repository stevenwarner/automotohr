<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Side Menu -->
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- profile_left_menu_company -->
            </div>
            <!-- Main Content Area -->
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <br>
                <div class="row">
                    <!-- Content Header -->
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow csF16 csB7">
                                <?=$title;?>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Main -->
                <div class="mainContent">
                    <div class="csPR">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->load->view(
                                    'steps',[
                                        'steps' => [
                                            [
                                                'text' => 'Taxes',
                                                'sub_text' => '&nbsp;',
                                                'slug' => 'taxes',
                                                'url' => base_url("company_payroll?section=taxes")
                                            ],
                                            [
                                                'text' => 'Bank',
                                                'sub_text' => 'Accounts',
                                                'slug' => 'bank_accounts',
                                                'url' => base_url("company_payroll?section=bank_accounts")
                                            ],
                                            [
                                                'text' => 'Locations',
                                                'sub_text' => '&nbsp;',
                                                'slug' => 'locations',
                                                'url' => base_url("company_payroll?section=locations")
                                            ],
                                            [
                                                'text' => 'Pay Periods',
                                                'sub_text' => '&nbsp;',
                                                'slug' => 'pay_periods',
                                                'url' => base_url("company_payroll?section=pay_periods")
                                            ]
                                        ],
                                        'prop_class' => 'arrow-green',
                                        'active' => $section
                                    ]
                                ); ?>
                            </div>
                        </div>
                        <br>
                        <?php $this->load->view('payroll/includes/'.($section).''); ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.API_KEY = "<?=getAPIKey();?>";
</script>