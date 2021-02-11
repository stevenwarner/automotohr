<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">Personal Settings</span>
                        </div>
                        <div class="setting-grid">
                            <article class="setting-box">
                                <h2>My Account</h2>
                                <div class="description-text">
                                    <p>Edit your personal info & set or change <br/> your login Credentials.</p>
                                </div>
                                <ul>
                                    <li><a href="<?= base_url() ?>my_profile">My Profile</a></li>
                                    <li><a href="<?php echo base_url(); ?>login_password">Login & Password</a></li>
                                    <li><a href="<?php echo base_url(); ?>my_referral_network">My Referrals</a></li>
                                    <!--<li><a href="javascript:;">Email Preferences</a></li>-->
                                </ul>
                            </article>
                            <article class="setting-box">
                                <h2>My Purchases</h2>
                                <div class="description-text">
                                    <p>Your purchase history.</p>
                                </div>
                                <ul>
                                    <li><a href="<?php echo base_url('order_history'); ?>">Marketplace Orders History</a></li>
                                    <li><a href="<?php echo base_url('settings/list_packages_addons_invoices'); ?>">Platform Packages and Admin Invoices</a></li>
                                    <!--<li><a href="javascript:;">Credits</a></li>
                                    <li><a href="javascript:;">Payment Methods</a></li>-->
                                </ul>
                            </article>
                            <hr>
                            <article class="setting-box address-box">
                                <h2>How to Contact Us at <?php echo STORE_NAME; ?></h2>
                                <div class="description-text">
                                    <p>Contact one of our Talent Network Partners at</p>
                                    <p>Sales Support:</p>
                                    <address>
	                                    <a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>"><?php echo TALENT_NETWORK_SALES_EMAIL; ?></a><br>
	                                    <div class="separator">
                                                <div class="separator-inner"><span>or</span></div>
                                            </div>
	                                    Technical Support:<br>
                                            <?php echo TALENT_NETWORK_SUPPORT_CONTACTNO; ?><br>
                                            <?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?>
                                    </address>
                                </div>                               
                            </article>
                        </div> 
                        <div class="company-setting-grid">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">Career Page Settings</span>
                            </div>
                            <article class="setting-box">
                                <h2>Administration</h2>
                                <div class="description-text">
                                    <p>View / edit your company profile and company contact details.</p>
                                </div>
                                <ul>
                                    <li><a href="<?php echo base_url(); ?>company_profile">Company Profile</a></li>
                                    <li><a href="<?php echo base_url(); ?>company_address">Company Contact Details</a></li>
                                    <li><a href="<?php echo base_url(); ?>security_access_level">Security Access Manager</a></li>
                                    <li><a href="<?php echo base_url(); ?>expirations_manager">Expirations Manager</a></li>
                                    <li><a href="<?php echo base_url(); ?>kpa_onboarding">Outsourced HR Compliance and Onboarding</a></li>
                                    <li><a href="<?php echo base_url(); ?>facebook_configuration">Facebook Job Listing API</a></li>
                                    <li><a href="<?php echo base_url(); ?>import_csv">Import Employees Using CSV File</a></li>
                                    <li><a href="<?php echo base_url(); ?>job_listing_categories">Job Listing Categories</a></li>
                                    <li><a href="<?php echo base_url(); ?>job_approval_rights">Job Listing Approval Rights</a></li>
                                </ul>
                            </article>
                            <article class="setting-box">
                                <h2>Career Page Configuration</h2>
                                <div class="description-text">
                                    <p>Technical Configurations related to your Career Page.</p>
                                </div>
                                <ul>
                                    <li><a href="<?php echo base_url(); ?>appearance">Themes & Appearance</a></li>
                                    <li><a href="<?php echo base_url(); ?>seo_tags">SEO Tags</a></li>
                                    <li><a href="<?php echo base_url(); ?>embedded_code">Embedded Code</a></li>
                                    <!--<li><a href="<?php echo base_url(); ?>screening_questionnaires">Screening Questions</a></li>-->
                                    <li><a href="<?php echo base_url(); ?>portal_widget">Career Page Widget</a></li>
<!--                                    <li><a href="<?php echo base_url(); ?>web_services">Career Page XML WEBSERVICE</a></li>-->
                                    <li><a href="<?php echo base_url('xml_export') ?>">XML Jobs feed</a></li>
                                    <li><a href="<?php echo base_url('domain_management') ?>">Domain Management</a></li>
                                    <li><a href="<?php echo base_url('social_links') ?>">Social Links Management</a></li>
                                    <!-- <li><a href="javascript:;">API / Integration</a></li>
                                    <li><a href="javascript:;">Web SSO</a></li>
                                    <li><a href="javascript:;">Hiring Process</a></li>
                                    <li><a href="javascript:;">Job Approvals</a></li>
                                    <li><a href="javascript:;">Brand & Logos</a></li>
                                    <li><a href="javascript:;">Rejection /  Withdrawal Reasons</a></li>
                                    <li><a href="javascript:;">Candidate Fields</a></li>-->
                                </ul>
                            </article>
                            <article class="setting-box">
                                <h2>Reporting</h2>
                                <div class="description-text">
                                    <p>Generate reports related to jobs and Equal Employment Opportunity </p>
                                </div>
                                <ul>
                                    <li><a href="<?php echo base_url('eeo') ?>">EEO Report</a></li>
                                    <li><a href="<?php echo base_url('accurate_background') ?>">Accurate Background Report</a></li>
                                    <!--<li><a href="javascript:;">Application Flow Report</a></li>-->
                                    <li><a href="<?php echo base_url('reports') ?>">Advanced Reports</a></li>
                                </ul>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>