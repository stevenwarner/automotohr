<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <!--  -->
                <?php $this->load->view('loader', ['props' => 'id="jsPayrollLoader"']); ?>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <?php $function_names = array('my_profile', 'login_password', 'my_referral_network', 'order_history', 'list_packages_addons_invoices', 'cc_management', 'job_products_report'); ?>
                        <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Personal Settings</span>
                            </div>
                        <?php } ?>
                        <div class="setting-grid">
                            <?php $function_names = array('my_profile', 'login_password', 'my_referral_network'); ?>
                            <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                <article class="setting-box">
                                    <h2>My Account</h2>
                                    <div class="description-text">
                                        <p>Edit your personal info & set or change <br /> your login Credentials.</p>
                                    </div>
                                    <ul>
                                        <?php if ($employer['is_executive_admin'] == '0') { ?>
                                            <?php if (check_access_permissions_for_view($security_details, 'my_profile')) { ?>
                                                <li><a href="<?= base_url() ?>my_profile">My Profile</a></li>
                                            <?php } ?>
                                            <?php if (check_access_permissions_for_view($security_details, 'login_password')) { ?>
                                                <li><a href="<?php echo base_url(); ?>login_password">Login & Password</a></li>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'my_referral_network')) { ?>
                                            <li><a href="<?php echo base_url(); ?>my_referral_network">My Referrals</a></li>
                                        <?php } ?>
                                        <!--<li><a href="javascript:;">Email Preferences</a></li>-->
                                    </ul>
                                </article>
                            <?php } ?>
                            <?php $function_names = array('order_history', 'list_packages_addons_invoices', 'cc_management', 'job_products_report'); ?>
                            <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                <article class="setting-box">
                                    <h2>My Purchases</h2>
                                    <div class="description-text">
                                        <p>Your purchase history.</p>
                                    </div>
                                    <ul>
                                        <?php if (check_access_permissions_for_view($security_details, 'list_packages_addons_invoices')) { ?>
                                            <li><a href="<?php echo base_url('settings/list_packages_addons_invoices'); ?>">Platform Packages and Admin Invoices</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'order_history')) { ?>
                                            <li><a href="<?php echo base_url('order_history'); ?>">Marketplace Orders History</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'cc_management')) { ?>
                                            <li><a href="<?php echo base_url(); ?>cc_management">Credit Card Management</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'job_products_report')) { ?>
                                            <li><a href="<?php echo base_url('job_products_report'); ?>">Job Products Report</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'pending_invoices')) { ?>
                                            <li><a href="<?php echo base_url('settings/pending_invoices'); ?>">Overdue Invoices</a></li>
                                        <?php } ?>
                                        <!--<li><a href="javascript:;">Credits</a></li>
                                        <li><a href="javascript:;">Payment Methods</a></li>-->
                                    </ul>
                                </article>
                            <?php } ?>
                        </div>
                        <div class="address-panel">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">How to Contact Us at <?php echo STORE_NAME; ?></span>
                            </div>
                            <h4>Contact one of our Talent Network Partners at</h4>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <h5>Sales Support</h5>
                                <ul>
                                    <li><i class="fa fa-phone"></i><?php echo sizeof($company_info) > 0 ? $company_info[0]['exec_sales_phone_no'] : TALENT_NETWORK_SALE_CONTACTNO; ?></li>
                                    <li><a href="mailto:<?php echo sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo sizeof($company_info) > 1 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <h5>Technical Support</h5>
                                <ul>
                                    <li><i class="fa fa-phone"></i><?php echo sizeof($company_info) > 0 ? $company_info[0]['tech_support_phone_no'] : TALENT_NETWORK_SUPPORT_CONTACTNO; ?></li>
                                    <li><a href="mailto:<?php echo sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo sizeof($company_info) > 1 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="company-setting-grid">
                            <?php $function_names = array(
                                'company_profile',
                                'company_address',
                                'security_access_level',
                                'Expiries_manager',
                                'kpa_onboarding',
                                'facebook_configuration',
                                'import_csv',
                                'import_applicants_csv',
                                'assign_bulk_documents',
                                'job_listing_categories_manager',
                                'approval_rights_management',
                                'portal_email_templates',
                                'notification_emails',
                                'application_status',
                                'appearance',
                                'seo_tags',
                                'embedded_code',
                                'portal_widget',
                                'xml_export',
                                'domain_management',
                                'social_links',
                                'talent_network_content_configuration',
                                'manage_career_page_maintenance_mode',
                                'eeo',
                                'accurate_background',
                                'reports',
                                'export_employees_csv'
                            ); ?>
                            <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                <div class="page-header-area">
                                    <span class="page-heading down-arrow">Advanced Configurations</span>
                                </div>
                            <?php } ?>
                            <?php
                            $function_names = array(
                                'company_profile',
                                'govt_user',
                                'department_management',
                                'company_address',
                                'security_access_level',
                                'Expiries_manager',
                                'kpa_onboarding',
                                'facebook_configuration',
                                'import_csv',
                                'import_applicants_csv',
                                'assign_bulk_documents',
                                'job_listing_categories_manager',
                                'approval_rights_management',
                                'portal_email_templates',
                                'notification_emails',
                                'application_status',
                                'export_employees_csv'
                            ); ?>
                            <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                <article class="setting-box">
                                    <h2>Administration</h2>
                                    <div class="description-text">
                                        <p>View / edit your company profile and company contact details.</p>
                                    </div>
                                    <ul>
                                        <?php if (check_access_permissions_for_view($security_details, 'company_profile')) { ?>
                                            <li><a href="<?php echo base_url(); ?>company_profile">Company Profile</a></li>
                                        <?php } ?>
                                        <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] == 0) { ?>
                                            <li><a href="<?php echo base_url('department_management'); ?>">Department Management</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'company_address')) { ?>
                                            <li><a href="<?php echo base_url(); ?>company_address">Company Contact Details</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'security_access_level')) { ?>
                                            <li><a href="<?php echo base_url(); ?>security_access_level">Security Access Manager</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'expiries_manager')) { ?>
                                            <li><a href="<?php echo base_url(); ?>expirations_manager">Expirations Manager</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'kpa_onboarding')) { ?>
                                            <li><a href="<?php echo base_url(); ?>outsourced_hr_compliance_and_onboarding">Outsourced HR Compliance and Onboarding</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'facebook_configuration')) { ?>
                                            <li><a href="<?php echo base_url(); ?>facebook_configuration">Facebook Job Listing API</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'import_csv')) { ?>
                                            <li><a href="<?php echo base_url(); ?>import_csv">Import Employees Using CSV File</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'import_applicants_csv')) { ?>
                                            <li><a href="<?php echo base_url(); ?>import_applicants_csv">Import Applicants Using CSV File</a></li>
                                        <?php } ?>
                                        <?php $my_company_id = $this->session->userdata('logged_in')['company_detail']['sid'];
                                        //if($my_company_id == 51 || $my_company_id == 57) { 
                                        ?>
                                        <li><a href="<?php echo base_url(); ?>assign_bulk_documents">Assign Bulk Document To Applicant / Employee</a></li>
                                        <?php   //} 
                                        ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'job_listing_categories_manager')) { ?>
                                            <li><a href="<?php echo base_url(); ?>job_listing_categories">Job Listing Categories</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'export_employee_csv')) { ?>
                                            <li><a href="<?php echo base_url(); ?>export_employees_csv">Export Employees To CSV File</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'export_applicant_csv')) { ?>
                                            <li><a href="<?php echo base_url(); ?>export_applicants_csv">Export Applicants To CSV File</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'bulk_resume')) { ?>
                                            <li><a href="<?php echo base_url(); ?>bulk_resume_download">Bulk Resume Download</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'approval_rights_management')) { ?>
                                            <li><a href="<?php echo base_url(); ?>approval_rights_management">Approval Rights Management</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'portal_email_templates')) { ?>
                                            <li><a href="<?php echo base_url(); ?>portal_email_templates">Email Templates Module</a></li>
                                        <?php } ?>
                                        <li><a href="<?php echo base_url(); ?>portal_sms_templates">SMS Templates Module</a></li>
                                        <?php if (check_access_permissions_for_view($security_details, 'notification_email')) { ?>
                                            <li><a href="<?php echo base_url(); ?>notification_emails">Notifications Emails Management</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'applicant_status_bar')) { ?>
                                            <li><a href="<?php echo base_url(); ?>application_status">Applicant Status Bar Module</a></li>
                                        <?php } ?>
                                        <?php if ($reassign_flag && in_array('full_access', $security_details) && check_access_permissions_for_view($security_details, 're_assign_applicant')) { ?>
                                            <li><a href="<?php echo base_url(); ?>re_assign_candidate">Re Assign Applicant</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'company_addresses')) { ?>
                                            <li><a href="<?php echo base_url('company_addresses'); ?>">Company Addresses</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'govt_user')) { ?>
                                            <li><a href="<?php echo base_url('govt_user'); ?>">Government Agent Credentials</a></li>
                                        <?php } ?>

                                        <?php if (checkIfAppIsEnabled('timeoff') && ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'])) { ?>
                                            <li><a href="<?php echo base_url('timeoff/settings'); ?>">Time Off Settings</a></li>
                                        <?php } ?>
                                        <?php if (checkIfAppIsEnabled('timeoff') && ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'])) { ?>
                                            <li><a href="<?php echo base_url('timeoff/export'); ?>">Export Time Off</a></li>
                                        <?php } ?>
                                        <?php if (checkIfAppIsEnabled('timeoff') && ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'])) { ?>
                                            <li><a href="<?php echo base_url('timeoff/import'); ?>">Import Time Off</a></li>
                                        <?php } ?>
                                        <?php if (checkIfAppIsEnabled('attendance') && ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'])) { ?>
                                            <li><a href="<?php echo base_url('attendance/'); ?>">Attendance</a></li>
                                        <?php } ?>

                                        <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                            <li><a href="<?php echo base_url('performance-management/dashboard'); ?>">Performance Management</a></li>
                                        <?php } ?>
                                        <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                            <li><a href="<?php echo base_url('performance-management/goals'); ?>">Goals</a></li>
                                        <?php } ?>
                                        <li><a href="<?php echo base_url('export_documents/employee'); ?>">Bulk Download Documents</a></li>

                                        <?php if (isPayrollOrPlus(true)) { ?>
                                            <li><a href="<?php echo base_url('company/documents/secure/listing'); ?>">Company Secure Document Upload</a></li>
                                        <?php } ?>

                                        <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(PAYROLL)) { ?>
                                            <li>
                                                <a href="<?= base_url("schedules"); ?>">
                                                    Company Pay Schedules
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url("schedules/employees"); ?>">
                                                    Employees Pay Schedules
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(SCHEDULE_MODULE)) { ?>
                                            <li>
                                                <a href="<?= base_url("overtimerules"); ?>">
                                                    Company Overtime Rules
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url("minimum_wages"); ?>">
                                                    Company Minimum Wages
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url("settings/job_sites"); ?>">
                                                    Manage Job Sites
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url("settings/shifts/manage"); ?>">
                                                    Manage Shifts
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <?php if (checkIfAppIsEnabled(PAYROLL)) { ?>
                                            <?php
                                            $isCompanyOnPayroll = isCompanyOnBoard($session['company_detail']['sid']);
                                            $isTermsAgreed = hasAcceptedPayrollTerms($session['company_detail']['sid']);
                                            ?>
                                            <?php if (!$isCompanyOnPayroll && isPayrollOrPlus(true)) { ?>
                                                <li><a href="javascript:void(0)" class="jsCreatePartnerCompanyBtn" data-cid="<?= $session['company_detail']['sid']; ?>">Set-up Payroll</a></li>
                                            <?php } ?>
                                            <?php if ($isCompanyOnPayroll && !$isTermsAgreed) { ?>
                                                <li><a href="javascript:void(0)" class="jsServiceAgreement" data-cid="<?= $session['company_detail']['sid']; ?>">Payroll Service Agreement</a></li>
                                            <?php } ?>
                                            <?php if ($isCompanyOnPayroll && $isTermsAgreed) { ?>
                                                <li><a href="<?= base_url('payrolls/dashboard'); ?>">Payroll Dashboard</a></li>
                                            <?php } ?>
                                            <?php if (!$isCompanyOnPayroll && isPayrollOrPlus(true)) { ?>
                                                <li><a href="javascript:void(0)" class="jsCreatePartnerCompanyBtn" data-cid="<?= $session['company_detail']['sid']; ?>">General Ledger</a></li>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(MODULE_ATTENDANCE)) { ?>
                                            <!-- Attendance module settings -->
                                            <li>
                                                <a href="<?= base_url("attendance/dashboard"); ?>">
                                                    Attendance Management
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (isPayrollOrPlus(true)) { ?>
                                            <!-- general ledger -->
                                            <li>
                                                <a href="<?= base_url("general_ledger"); ?>">
                                                    General Ledger
                                                </a>
                                            </li>
                                            <!-- <li>
                                                <a href="<?= base_url("import_general_ledger"); ?>">
                                                    Import General Ledger
                                                </a>
                                            </li> -->
                                        <?php } ?>
                                    </ul>
                                </article>
                            <?php } ?>
                            <?php $function_names = array('appearance', 'career_logo_management', 'seo_tags', 'embedded_code', 'portal_widget', 'xml_export', 'domain_management', 'social_links', 'talent_network_content_configuration', 'manage_career_page_maintenance_mode'); ?>
                            <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                <article class="setting-box">
                                    <h2>Careper Page Configuration</h2>
                                    <div class="description-text">
                                        <p>Technical Configurations related to your Career Page.</p>
                                    </div>
                                    <ul>
                                        <?php if (check_access_permissions_for_view($security_details, 'appearance')) { ?>
                                            <li><a href="<?php echo base_url(); ?>appearance">Themes & Appearance</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'career_logo_management')) { ?>
                                            <li><a href="<?php echo base_url('appearance/career_logo_management'); ?>">Career Page Logo</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'seo_tags')) { ?>
                                            <li><a href="<?php echo base_url(); ?>seo_tags">SEO Tags</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'embedded_code')) { ?>
                                            <li><a href="<?php echo base_url(); ?>embedded_code">Embedded Code</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'portal_widget')) { ?>
                                            <li><a href="<?php echo base_url(); ?>portal_widget">Career Page Widget</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'web_services')) { ?>
                                            <!--<li><a href="<?php echo base_url(); ?>web_services">Career Page XML WEBSERVICE</a></li>-->
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'xml_export')) { ?>
                                            <li><a href="<?php echo base_url('xml_export'); ?>">XML Jobs feed</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'domain_management')) { ?>
                                            <li><a href="<?php echo base_url('domain_management'); ?>">Domain Management</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'social_links')) { ?>
                                            <li><a href="<?php echo base_url('social_links'); ?>">Social Links Management</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'emp_login_text')) { ?>
                                            <li><a href="<?php echo base_url('employee_login_link'); ?>">Employee Log In Text</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'talent_network_content_configuration')) { ?>
                                            <li><a href="<?php echo base_url('talent_network_content_configuration'); ?>">Talent Content Configuration</a></li>
                                        <?php } ?>
                                        <?php if ($job_fair_configuration && check_access_permissions_for_view($security_details, 'job_fair_config')) { ?>
                                            <li><a href="<?php echo base_url('job_fair_configuration'); ?>">Job Fair Configuration</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'manage_career_page_maintenance_mode')) { ?>
                                            <li><a href="<?php echo base_url('settings/manage_career_page_maintenance_mode'); ?>">Career Page Maintenance Mode</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'photo_gallery')) { ?>
                                            <li><a href="<?php echo base_url('photo_gallery'); ?>">Photo Gallery</a></li>
                                        <?php  } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'preview_job_listing')) {  ?>
                                            <li><a href="<?php echo base_url('settings/preview_job_listing_template'); ?>">Preview Job Listing Template</a></li>
                                        <?php  }  ?>

                                        <!-- <li><a href="javascript:;">API / Integration</a></li>
                                        <li><a href="javascript:;">Web SSO</a></li>
                                        <li><a href="javascript:;">Hiring Process</a></li>
                                        <li><a href="javascript:;">Job Approvals</a></li>
                                        <li><a href="javascript:;">Brand & Logos</a></li>
                                        <li><a href="javascript:;">Rejection /  Withdrawal Reasons</a></li>
                                        <li><a href="javascript:;">Candidate Fields</a></li>-->
                                    </ul>
                                </article>
                            <?php } ?>
                            <?php $function_names = array('eeo', 'accurate_background', 'reports'); ?>
                            <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                <article class="setting-box">
                                    <h2>Reporting</h2>
                                    <div class="description-text">
                                        <p>Generate reports related to jobs and Equal Employment Opportunity </p>
                                    </div>
                                    <ul>
                                        <?php if (check_access_permissions_for_view($security_details, 'eeo')) { ?>
                                            <li><a href="<?php echo base_url('eeo') ?>">EEO Report</a></li>
                                        <?php } ?>
                                        <?php if (check_access_permissions_for_view($security_details, 'accurate_background')) { ?>
                                            <li><a href="<?php echo base_url('accurate_background') ?>">Accurate Background Report</a></li>
                                        <?php } ?>
                                        <!--<li><a href="javascript:;">Application Flow Report</a></li>-->
                                        <?php if (check_access_permissions_for_view($security_details, 'reports')) { ?>
                                            <li><a href="<?php echo base_url('reports') ?>">Advanced Reports</a></li>
                                        <?php } ?>
                                    </ul>
                                </article>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>