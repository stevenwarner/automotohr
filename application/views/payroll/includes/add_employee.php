<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Side Menu -->
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- profile_left_menu_company -->
            </div>
            <!-- Main Content Area -->
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top', ['employer' => $Employee]); ?><!-- employee_profile_ats_view_top -->
                    </div>
                </div>
                <br>
                <div class="row">
                    <!-- Content Header -->
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow csF16 csB7">
                                <a class="dashboard-link-btn" href="<?=base_url("employee_profile/".($employeeId).""); ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i>Employee Profile</a>
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
                                                'text' => 'Basic',
                                                'sub_text' => 'Information',
                                                'slug' => 'basic_information',
                                                'url' => $isEmployeeOnPayroll ? base_url("employee/add/".($employeeId)."?section=basic_information") : 'javascript:void(0)'
                                            ],
                                            [
                                                'text' => 'Bank',
                                                'sub_text' => 'Accounts',
                                                'slug' => 'bank_accounts',
                                                'url' => $isEmployeeOnPayroll ? base_url("employee/add/".($employeeId)."?section=bank_accounts") : 'javascript:void(0)'
                                            ],
                                            [
                                                'text' => 'Jobs',
                                                'sub_text' => '&nbsp;',
                                                'slug' => 'jobs',
                                                'url' => $isEmployeeOnPayroll ? base_url("employee/add/".($employeeId)."?section=jobs") : 'javascript:void(0)'
                                            ],
                                            [
                                                'text' => 'Other',
                                                'sub_text' => 'Benefits',
                                                'slug' => 'benefits',
                                                'url' => $isEmployeeOnPayroll ? base_url("employee/add/".($employeeId)."?section=benefits") : 'javascript:void(0)'
                                            ]
                                        ],
                                        'prop_class' => 'arrow-green',
                                        'active' => $section
                                    ]
                                ); ?>
                            </div>
                        </div>
                        <?php $this->load->view('loader_new', ['id' => 'employee']); ?>
                        <br>
                        <br>
                        <!--  -->
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Fields marked with an asterisk (<span class="csRequired"></span>) are mandatory.</p>
                            </div>
                        </div>
                        <?php $this->load->view('payroll/partials/'.($section).''); ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.API_URL = "<?=getAPIUrl('employees');?>"; 
    window.API_KEY = "<?=getAPIKey();?>";
    //
    var page = "<?=$section;?>";
    //
    var employeeId = <?=$employeeId;?>;
    //
    var onPayroll = <?=$isEmployeeOnPayroll;?>;
</script>