<?php defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($security_details)) {
    $admin_id = $this->session->userdata('user_id');
    $security_details = db_get_admin_access_level_details($admin_id);
}

$admin_management_menu = false;
$company_employers_menu = false;
$billing_menu = false;
$system_configuration_menu = false;
$accurate_background_menu = false;
$customize_pages = false;
$oem_manufacturers = false;
$ticket_support = false;
$reports_menu = false;
$activity_reports_menu = false;
$financial_reports_menu = false;
$incident_reporting_menu = false;
$safety_data_sheet_menu = false;
$documents_library_menu = false;
$affiliate_program_menu = false;
$email_template_module = false;
$job_feed = false;
$modules = false;
$compliance_safty_reporting_menu = false;


if (
    base_url(uri_string()) == site_url('manage_admin/users') ||
    base_url(uri_string()) == site_url('manage_admin/documents') ||
    base_url(uri_string()) == site_url('manage_admin/users/edit_profile') ||
    base_url(uri_string()) == site_url('manage_admin/groups') ||
    base_url(uri_string()) == site_url('manage_admin/users/add_subaccount') ||
    $this->uri->segment(3) == 'edit_profile' ||
    ($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'groups') ||
    ($this->uri->segment(2) == 'documents' && $this->uri->segment(3) == 0)
) {
    $admin_management_menu = true;
} else if (
    base_url(uri_string()) == site_url('manage_admin/companies') ||
    base_url(uri_string()) == site_url('manage_admin/employers') ||
    $this->uri->segment(3) == 'search_company' ||
    $this->uri->segment(3) == 'edit_company' ||
    $this->uri->segment(3) == 'manage_company' ||
    $this->uri->segment(3) == 'cc_management' ||
    $this->uri->segment(3) == 'edit_employer' ||
    $this->uri->segment(3) == 'add_company' ||
    $this->uri->segment(3) == 'executive_administrators' ||
    $this->uri->segment(3) == 'add_executive_administrator' ||
    $this->uri->segment(3) == 'manage_packages' ||
    $this->uri->segment(3) == 'manage_addons' ||
    $this->uri->segment(3) == 'list_company_notes' ||
    $this->uri->segment(2) == 'company_security_settings' ||
    $this->uri->segment(3) == 'edit_executive_administrator' ||
    $this->uri->segment(3) == 'manage_executive_administrators' ||
    $this->uri->segment(3) == 'add_admin_company' ||
    $this->uri->segment(2) == 'automotive_groups' ||
    $this->uri->segment(2) == 'notification_emails' ||
    $this->uri->segment(2) == 'employers' ||
    $this->uri->segment(3) == 'who_is_online' ||
    $this->uri->segment(3) == 'company_brands' ||
    $this->uri->segment(2) == 'corporate_management' ||
    $this->uri->segment(2) == 'corporate_groups' ||
    $this->uri->segment(2) == 'companies' ||
    $this->uri->segment(2) == 'bulk_email' ||
    $this->uri->segment(2) == 'copy_applicants' ||
    $this->uri->segment(2) == 'copy_documents' ||
    $this->uri->segment(2) == 'copy_policies' ||
    $this->uri->segment(2) == 'copy_employees' ||
    $this->uri->segment(1) == 'migrate_company_groups' ||
    $this->uri->segment(2) == 'merge_employees' ||
    $this->uri->segment(1) == 'cn' ||
    $this->uri->segment(2) == 'pending_documents' ||
    ($this->uri->segment(2) == 'documents' && $this->uri->segment(3) > 0)

) {
    $company_employers_menu = true;
} else if (
    base_url(uri_string()) == site_url('manage_admin/promotions') ||
    base_url(uri_string()) == site_url('manage_admin/products') ||
    base_url(uri_string()) == site_url('manage_admin/invoice') ||
    base_url(uri_string()) == site_url('manage_admin/invoice/add_new_invoice') ||
    (
        ($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'products') ||
        ($this->uri->segment(3) == 'add_new_product' && $this->uri->segment(2) == 'products')
    ) ||
    ($this->uri->segment(3) == 'edit_invoice') ||
    ($this->uri->segment(3) == 'edit_promotion' ||
        $this->uri->segment(3) == 'add_new_promotion' && $this->uri->segment(2) == 'promotions') ||
    ($this->uri->segment(3) == 'list_admin_invoices' ||
        $this->uri->segment(3) == 'view_admin_invoice' ||
        $this->uri->segment(3) == 'apply_discount_admin_invoice' ||
        $this->uri->segment(3) == 'process_payment_admin_invoice') ||
    (
        ($this->uri->segment(2) == 'recurring_payments') ||
        ($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'recurring_payments') ||
        ($this->uri->segment(3) == 'add' && $this->uri->segment(2) == 'recurring_payments')
    ) ||
    (strpos(base_url(uri_string()), site_url('manage_admin/exclude_companies')) !== false) ||
    $this->uri->segment(2) == 'marketing_agencies' ||
    $this->uri->segment(2) == 'marketing_agency_documents' ||
    $this->uri->segment(3) == 'pending_invoices' ||
    $this->uri->segment(3) == 'view_pending_invoices' ||
    $this->uri->segment(3) == 'pending_commissions' ||
    $this->uri->segment(3) == 'view_pending_commissions' ||
    $this->uri->segment(2) == 'cc_expires'
) {
    $billing_menu = true;
} else if (
    base_url(uri_string()) == site_url('manage_admin/settings') ||
    base_url(uri_string()) == site_url('manage_admin/security_settings') ||
    base_url(uri_string()) == site_url('manage_admin/admin_status_bar') ||
    $this->uri->segment(3) == 'manage_permissions' ||
    $this->uri->segment(3) == 'demo_affiliate_configurations' ||
    $this->uri->segment(3) == 'edit_demo_affiliate_configurations' ||
    base_url(uri_string()) == site_url('manage_admin/email_templates') ||
    base_url(uri_string()) == site_url('manage_admin/performance_management_templates') ||
    $this->uri->segment(3) == 'edit_performance_template' ||
    base_url(uri_string()) == site_url('manage_admin/free_demo') ||
    $this->uri->segment(2) == 'edit_demo_request' ||
    $this->uri->segment(2) == 'demo_admin_reply' ||
    $this->uri->segment(2) == 'enquiry_message_details' ||
    $this->uri->segment(2) == 'free_demo' ||
    $this->uri->segment(2) == 'email_enquiries' ||
    $this->uri->segment(2) == 'modules' ||
    $this->uri->segment(2) == 'edit_module' ||
    $this->uri->segment(2) == 'company_module' ||
    $this->uri->segment(2) == 'sms_enquiries' ||
    base_url(uri_string()) == site_url('manage_admin/email_enquiries') ||
    base_url(uri_string()) == site_url('manage_admin/sms_enquiries') ||
    base_url(uri_string()) == site_url('manage_admin/indeed/disposition/status/map') ||
    $this->uri->segment(2) == 'notification_email_log' ||
    $this->uri->segment(2) == 'notification_email_log_view' ||
    base_url(uri_string()) == site_url('manage_admin/private_messages') ||
    $this->uri->segment(3) == 'email_templates_view' ||
    $this->uri->segment(3) == 'edit_email_templates_view' ||
    $this->uri->segment(2) == 'log_detail' ||
    $this->uri->segment(2) == 'email_log' ||
    $this->uri->segment(2) == 'sms_log' ||
    $this->uri->segment(2) == 'outbox' ||
    $this->uri->segment(2) == 'compose_message' ||
    $this->uri->segment(2) == 'outbox_message_detail' ||
    $this->uri->segment(2) == 'inbox_message_detail' ||
    $this->uri->segment(2) == 'reply_message' ||
    $this->uri->segment(2) == 'job_templates' ||
    $this->uri->segment(2) == 'job_template_groups' ||
    $this->uri->segment(2) == 'job_categories_manager' ||
    $this->uri->segment(2) == 'document_categories_manager' ||
    $this->uri->segment(2) == 'logs' ||
    $this->uri->segment(2) == 'interview_questionnaires' ||
    $this->uri->segment(2) == 'system_notification_emails' ||
    $this->uri->segment(2) == 'turnover_cost_calculator_logs' ||
    $this->uri->segment(2) == 'blocked_applicants' ||
    $this->uri->segment(2) == 'blocked_ips' ||
    $this->uri->segment(2) == 'cms' ||
    $this->uri->segment(2) == 'edit_page' ||
    $this->uri->segment(2) == 'resources' ||

    base_url(uri_string()) == site_url('sa/lms/courses') ||
    $this->uri->segment(2) == 'benefits' ||
    $this->uri->segment(2) == 'job_title_templates' ||
    (
        ($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'job_templates') ||
        ($this->uri->segment(3) == 'add' && $this->uri->segment(2) == 'job_templates' ||
            $this->uri->segment(2) == 'social_settings')
    )
) {
    $system_configuration_menu = true;
} else if (
    base_url(uri_string()) == site_url('manage_admin/accurate_background') ||
    base_url(uri_string()) == site_url('manage_admin/accurate_background') ||
    base_url(uri_string()) == site_url('manage_admin/accurate_background/activation_orders') ||
    $this->uri->segment(3) == 'order_status' ||
    $this->uri->segment(3) == 'report' ||
    $this->uri->segment(3) == 'manage_document'
) {
    $accurate_background_menu = true;
} else if (
    base_url(uri_string()) == site_url('manage_admin/home_page') ||
    base_url(uri_string()) == site_url('manage_admin/home_page/customize_home_page') ||
    base_url(uri_string()) == site_url('manage_admin/resource_page') ||
    $this->uri->segment(2) == 'hr_documents_content'
) {
    $customize_pages = true;
} else if (
    base_url(uri_string()) == site_url('manage_admin/oem_manufacturers') ||
    $this->uri->segment(3) == 'add_oem_manufacturer' ||
    $this->uri->segment(3) == 'manage_oem_manufacturer' ||
    $this->uri->segment(3) == 'edit_oem_manufacturer' ||
    $this->uri->segment(3) == 'add_manufacturer_company'
) {
    $oem_manufacturers = true;
} else if (
    base_url(uri_string()) == site_url('manage_admin/support_tickets') ||
    $this->uri->segment(3) == 'view_ticket' ||
    $this->uri->segment(3) == 'lists'
) {
    $ticket_support = true;
} else if (
    $this->uri->segment(1) == 'employee_profile_data_report' ||
    $this->uri->segment(3) == 'facebook_job_report' ||
    $this->uri->segment(3) == 'blacklist_email' ||
    $this->uri->segment(3) == 'job_products_report' ||
    $this->uri->segment(3) == 'applicants_report' ||
    $this->uri->segment(3) == 'copy_applicants_report' ||
    $this->uri->segment(3) == 'jobs_per_month_report' ||
    $this->uri->segment(3) == 'interviews_report' ||
    $this->uri->segment(3) == 'applicants_between_period_report' ||
    $this->uri->segment(3) == 'time_to_fill_job_report' ||
    //    $this->uri->segment(3) == 'time_to_hire_job_report' ||
    $this->uri->segment(3) == 'job_categories_report' ||
    $this->uri->segment(3) == 'new_hires_report' ||
    $this->uri->segment(3) == 'new_hires_onboarding_report' ||
    $this->uri->segment(3) == 'job_views_report' ||
    $this->uri->segment(3) == 'advanced_jobs_report' ||
    $this->uri->segment(3) == 'applicants_referrals_report' ||
    $this->uri->segment(3) == 'applicant_status_report' ||
    $this->uri->segment(3) == 'applicant_offers_report' ||
    $this->uri->segment(3) == 'applicant_interview_scores' ||
    $this->uri->segment(3) == 'applicant_origination_tracker' ||
    $this->uri->segment(3) == 'applicant_source_report' ||
    $this->uri->segment(3) == 'applicant_source_report_daily' ||
    $this->uri->segment(3) == 'applicant_origination_report' ||
    $this->uri->segment(3) == 'applicant_origination_statistics' ||
    $this->uri->segment(3) == 'accurate_background' ||
    $this->uri->segment(3) == 'invoice_item_usage' ||
    $this->uri->segment(3) == 'complynet_report' ||
    $this->uri->segment(3) == 'employees_termination_report'
) {
    $reports_menu = true;
} else if (
    $this->uri->segment(2) == 'employer_login_duration' ||
    $this->uri->segment(3) == 'company_daily_activity_report' ||
    $this->uri->segment(3) == 'company_weekly_activity_report' ||
    $this->uri->segment(3) == 'daily_activity_report' ||
    $this->uri->segment(3) == 'weekly_activity_report' ||
    $this->uri->segment(3) == 'applicants_referrals_report' ||
    $this->uri->segment(3) == 'daily_inactivity_report' ||
    $this->uri->segment(3) == 'weekly_inactivity_report' ||
    $this->uri->segment(3) == 'daily_activity_overview_report' ||
    $this->uri->segment(3) == 'weekly_activity_overview_report' ||
    $this->uri->segment(3) == 'daily_activity_detailed_overview_report'
) {
    $activity_reports_menu = true;
} else if (
    $this->uri->segment(2) == 'financial_reports' ||
    $this->uri->segment(3) == 'monthly_unpaid_invoices' ||
    $this->uri->segment(3) == 'monthly_marketplace_products_sales' ||
    $this->uri->segment(3) == 'monthly_marketplace_product_statistics' ||
    $this->uri->segment(3) == 'monthly_profit_report' ||
    $this->uri->segment(3) == 'yearly_sales' ||
    $this->uri->segment(3) == 'yearly_sales_comparison' ||
    $this->uri->segment(3) == 'monthly_sales' ||
    $this->uri->segment(3) == 'yearly_sales_comparison' ||
    $this->uri->segment(3) == 'sms_service_report'
) {
    $financial_reports_menu = true;
} else if ($this->uri->segment(3) == 'incident_reporting') {
    $incident_reporting_menu = true;
} elseif ($this->uri->segment(3) == 'compliance_reporting') {
    $compliance_safty_reporting_menu = true;
} else if ($this->uri->segment(2) == 'safety_data_sheet') {
    $safety_data_sheet_menu = true;
} else if ($this->uri->segment(2) == 'default_categories') {
    $documents_library_menu = true;
} else if ($this->uri->segment(2) == 'documents_library') {
    $documents_library_menu = true;
} else if (
    base_url(uri_string()) == site_url('manage_admin/affiliates') ||
    $this->uri->segment(2) == 'affiliates' ||
    $this->uri->segment(2) == 'referred_affiliates' ||
    $this->uri->segment(2) == 'referred_clients'
) {
    $affiliate_program_menu = true;
} else if (
    $this->uri->segment(2) == 'email_templates_listing' ||
    $this->uri->segment(2) == 'add_email_template' ||
    $this->uri->segment(2) == 'edit_email_template'
) {
    $email_template_module = true;
} else if (
    $this->uri->segment(2) == 'job_feeds_management' ||
    $this->uri->segment(2) == 'job-feed'
) {
    $job_feed = true;
}

?>
<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 no-padding jsExpandSideBar">
    <div class="nav-menu">
        <ul>
            <?php $functions_names = array('list_admin_users', 'add_subaccount', 'edit_my_account', 'list_admin_groups', 'create_admin_groups', 'automotive_groups', 'financial_reports', 'financial_reports_index'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="hr-closed-menu <?php if ($admin_management_menu) {
                                                    echo 'hr-opened-menu';
                                                } ?>" href="javascript:;"> Admin Management</a>

                    <div class="submenu" <?php if ($admin_management_menu) {
                                                echo 'style="display:block;"';
                                            } ?>>
                        <?php if (check_access_permissions_for_view($security_details, 'list_admin_users')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/users') || ($this->uri->segment(3) == 'edit_profile' && $this->uri->segment(2) == 'users' && $this->uri->segment(4) == TRUE)) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/users'); ?>">List Users</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'edit_my_account')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/users/edit_profile')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/users/edit_profile'); ?>">Edit My Profile</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'add_subaccount')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/users/add_subaccount')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/users/add_subaccount'); ?>">Add Sub-Account</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'list_admin_groups')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/groups') || $this->uri->segment(3) == 'edit') {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/groups'); ?>">List Groups</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'admin_forms_and_documents')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/documents') &&  (empty($this->uri->segment(3)) || $this->uri->segment(3) == 0)) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/documents'); ?>">Documents</a>
                            </div>
                        <?php } ?>

                    </div>
                </li>
            <?php } ?>

            <?php $functions_names = array('add_new_company', 'edit_company', 'list_companies', 'list_employers', 'executive_administrators', 'who_is_online', 'bulk_emails_index'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $company_employers_menu ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">Companies & Employers</a>
                    <div class="submenu" <?php echo $company_employers_menu ? 'style="display:block;"' : ''; ?>>
                        <?php if (check_access_permissions_for_view($security_details, 'add_new_company')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (
                                        base_url(uri_string()) == site_url('manage_admin/companies/add_company') || $this->uri->segment(3) == 'add_company'
                                    ) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/companies/add_company'); ?>">Add New Company</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'list_companies')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/companies') || $this->uri->segment(3) == 'search_company' || $this->uri->segment(3) == 'edit_company' || $this->uri->segment(3) == 'edit_approver' || $this->uri->segment(3) == 'add_approver' || $this->uri->segment(3) == 'timeoff_approvers' || $this->uri->segment(3) == 'manage_packages' || $this->uri->segment(3) == 'manage_company' || $this->uri->segment(2) == 'company_security_settings' || $this->uri->segment(3) == 'cc_management' || $this->uri->segment(3) == 'manage_addons' || $this->uri->segment(3) == 'list_company_notes' || $this->uri->segment(2) == 'notification_emails' || $this->uri->segment(3) == 'company_brands' || ($this->uri->segment(2) == 'documents' && $this->uri->segment(3) > 0)) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/companies'); ?>">Manage Companies</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'list_employers')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/employers') || $this->uri->segment(3) == 'edit_employer' || $this->uri->segment(2) == 'employers') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/employers'); ?>">Manage Employers</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'executive_administrators')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if ($this->uri->segment(3) == 'add_executive_administrator' || $this->uri->segment(3) == 'executive_administrators' || $this->uri->segment(3) == 'edit_executive_administrator' || $this->uri->segment(3) == 'manage_executive_administrators' || $this->uri->segment(3) == 'add_admin_company') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/companies/executive_administrators'); ?>">Manage Executive Admin</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'corporate_panel')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (
                                        base_url(uri_string()) == site_url('manage_admin/corporate_management') || $this->uri->segment(3) == 'corporate_management' || in_array('edit_corporate_site', $this->uri->segment_array())
                                    ) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/corporate_management'); ?>">Corporate Management</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'automotive_groups')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (
                                        base_url(uri_string()) == site_url('manage_admin/automotive_groups') || $this->uri->segment(2) == 'automotive_groups' || $this->uri->segment(2) == 'corporate_groups'
                                    ) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/corporate_groups'); ?>">Corporate Groups</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'who_is_online')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (
                                        base_url(uri_string()) == site_url('manage_admin/users/who_is_online')
                                    ) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/users/who_is_online'); ?>">List Online Employers</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'bulk_emails_index')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (
                                        base_url(uri_string()) == site_url('manage_admin/bulk_email')
                                    ) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/bulk_email'); ?>">Bulk Email Module</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'copy_applicants')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/copy_applicants')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/copy_applicants'); ?>">Copy Applicants</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'copy_employees')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/copy_employees')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/copy_employees'); ?>">Copy Employees</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'copy_documents')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/copy_documents')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/copy_documents'); ?>">Copy Documents</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'copy_policies')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/copy_policies')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/copy_policies'); ?>">Copy Time Off</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'copy_employees')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/pending_documents')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/pending_documents'); ?>">Pending Documents</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'copy_employees')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('migrate_company_groups')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('migrate_company_groups'); ?>">Copy Groups With Documents</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'merge_employees')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/merge_employees')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/merge_employees'); ?>">Merge Employees</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'complynet')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('cn/dashboard')) !== false || strpos(base_url(uri_string()), site_url('cn/manage/job_roles')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('cn/dashboard'); ?>">ComplyNet</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('list_admin_invoices', 'invoices_panel', 'list_products', 'list_promotions', 'recurring_payments', 'exclude_companies', 'list_marketing_agencies', 'add_edit_marketing_agency', 'manage_commissions', 'pending_invoices', 'view_pending_invoices'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $billing_menu ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">Billing</a>
                    <div class="submenu" <?php echo $billing_menu ? 'style="display:block;"' : ''; ?>>

                        <?php if (check_access_permissions_for_view($security_details, 'list_admin_invoices')) { ?>
                            <div class="menu-item">
                                <a <?php if ((base_url(uri_string()) == site_url('manage_admin/invoice/list_admin_invoices') || ($this->uri->segment(2) == 'invoice' && $this->uri->segment(3) == 'apply_discount_admin_invoice') || ($this->uri->segment(2) == 'invoice' && $this->uri->segment(3) == 'view_admin_invoice') || ($this->uri->segment(2) == 'misc' && $this->uri->segment(3) == 'process_payment_admin_invoice')) || ($this->uri->segment(3) == 'list_admin_invoices')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/invoice/list_admin_invoices'); ?>">Admin Invoices</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'invoices_panel')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/invoice') || base_url(uri_string()) == site_url('manage_admin/invoice/add_new_invoice') || ($this->uri->segment(3) == 'edit_invoice')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/invoice'); ?>">marketplace Invoices</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'list_products')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/products') || (($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'products') || ($this->uri->segment(3) == 'add_new_product' && $this->uri->segment(2) == 'products'))) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/products'); ?>">Market Place Products</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'list_promotions')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/promotions') || ($this->uri->segment(3) == 'edit_promotion' || $this->uri->segment(3) == 'add_new_promotion' && $this->uri->segment(2) == 'promotions')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/promotions'); ?>">Promotions</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'recurring_payments')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/recurring_payments') || (($this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'add') && $this->uri->segment(2) == 'recurring_payments')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/recurring_payments'); ?>">Recurring Payments</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'exclude_companies')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/exclude_companies')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/exclude_companies'); ?>">Exclude Companies</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'list_marketing_agencies')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/marketing_agencies')) !== false || $this->uri->segment(2) == 'marketing_agency_documents') {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/marketing_agencies'); ?>">Marketing Agencies</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'unpaid_invoice')) { ?>
                            <div class="menu-item">
                                <a <?php if (in_array($this->uri->segment(3), array('pending_invoices', 'view_pending_invoices'))) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/invoice/pending_invoices'); ?>">Unpaid Invoices</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'pending_commissions')) { ?>
                            <div class="menu-item">
                                <a <?php if (in_array($this->uri->segment(3), array('pending_commissions', 'view_pending_commissions'))) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/invoice/pending_commissions'); ?>">Unpaid Commissions</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'cc_status')) { ?>
                            <div class="menu-item">
                                <a <?php echo  $this->uri->segment(2) == 'cc_expires' ?  'class="active"' : ''; ?> href="<?php echo site_url('manage_admin/cc_expires/active_cards'); ?>">Credit Card Status</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('system_settings', 'social_settings', 'demo_affiliate_configurations', 'admin_status_bar', 'security_settings', 'email_templates', 'free_demo_enquiries', 'email_enquiries_log', 'notification_email_log', 'notification_email_log_view', 'private_messages', 'job_listing_templates', 'job_categories_manager', 'interview_questionnaires', 'system_notification_emails', 'blocked_applicants', 'block_ips', 'modules', 'document_categories_manager'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $system_configuration_menu ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">System Configuration</a>
                    <div class="submenu" <?php echo $system_configuration_menu ? 'style="display:block;"' : ''; ?>>

                        <?php if (check_access_permissions_for_view($security_details, 'system_settings')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/settings')) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/settings'); ?>">System Settings</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'system_settings')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if ($this->uri->segment(2) == "modules") {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/modules'); ?>">Modules</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'social_settings')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/social_settings')) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/social_settings'); ?>">Social Settings</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'demo_affiliate_configurations')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if ($this->uri->segment(3) == 'demo_affiliate_configurations' || $this->uri->segment(3) == 'edit_demo_affiliate_configurations') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/settings/demo_affiliate_configurations'); ?>">Demo & Affiliate Configurations</a>
                            </div>
                        <?php } ?>
                        <?php //if (check_access_permissions_for_view($security_details, 'admin_status_bar')) { 
                        ?>
                        <div class="menu-item">
                            <a <?php

                                if (base_url(uri_string()) == site_url('manage_admin/admin_status_bar')) {
                                    echo 'class="active"';
                                }
                                ?> href="<?php echo site_url('manage_admin/admin_status_bar'); ?>">Admin Status Bar</a>
                        </div>
                        <?php //} 
                        ?>

                        <?php if (check_access_permissions_for_view($security_details, 'security_settings')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/security_settings') || $this->uri->segment(3) == 'manage_permissions') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/security_settings'); ?>">Security
                                    Settings</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'email_templates')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/email_templates') || $this->uri->segment(3) == 'email_templates_view' || $this->uri->segment(3) == 'edit_email_templates_view') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/email_templates'); ?>">Email Templates</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'performance_management')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/performance_management') || $this->uri->segment(3) == 'edit_performance_template') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/performance_management'); ?>">Performance Management Templates</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'free_demo_enquiries')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/free_demo') || $this->uri->segment(2) == 'free_demo' || $this->uri->segment(2) == 'enquiry_message_details' || $this->uri->segment(2) == 'edit_demo_request' || $this->uri->segment(2) == 'demo_admin_reply') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/free_demo'); ?>">Free Demo Enquiries</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'email_enquiries_log')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/email_enquiries') || $this->uri->segment(2) == 'email_log' || $this->uri->segment(2) == 'email_enquiries' || $this->uri->segment(2) == 'logs') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/email_enquiries'); ?>">Email Enquiries
                                    Log</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'sms_enquiries_log')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    //if (base_url(uri_string()) == site_url('manage_admin/sms_enquiries') || $this->uri->segment(2) == 'sms_log' || $this->uri->segment(2) == 'sms_enquiries' || $this->uri->segment(2) == 'logs') {
                                    //echo 'class="active"';
                                    //}
                                    ?> href="<?php //echo site_url('manage_admin/sms_enquiries'); 
                                                ?>">SMS Enquiries
                                    Log</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'notification_email_log')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/notification_email_log') || $this->uri->segment(2) == 'notification_email_log_view' || $this->uri->segment(2) == 'notification_email_log' || $this->uri->segment(2) == 'logs') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/notification_email_log'); ?>">Notification Email
                                    Log</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'private_messages')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/private_messages') || $this->uri->segment(2) == 'outbox' || $this->uri->segment(2) == 'compose_message' || $this->uri->segment(2) == 'reply_message' || $this->uri->segment(2) == 'outbox_message_detail' || $this->uri->segment(2) == 'inbox_message_detail') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/private_messages'); ?>">Private Messages</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'job_listing_templates')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/job_templates') || (($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'job_templates') || ($this->uri->segment(3) == 'add' && $this->uri->segment(2) == 'job_templates')) || (($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'job_template_groups') || ($this->uri->segment(3) == 'add' && $this->uri->segment(2) == 'job_template_groups'))) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/job_templates'); ?>">Job Listing
                                    Templates</a>
                            </div>
                        <?php } ?>


                        <div class="menu-item">
                            <a <?php
                                if (base_url(uri_string()) == site_url('manage_admin/job_title_templates') || (($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'job_title_templates') || ($this->uri->segment(3) == 'add' && $this->uri->segment(2) == 'job_title_templates')) || (($this->uri->segment(3) == 'edit' && $this->uri->segment(2) == 'job_title_template_groups') || ($this->uri->segment(3) == 'add' && $this->uri->segment(2) == 'job_title_template_groups'))) {
                                    echo 'class="active"';
                                }
                                ?> href="<?php echo site_url('manage_admin/job_title_templates'); ?>">Job Titles</a>
                        </div>




                        <?php if (check_access_permissions_for_view($security_details, 'job_categories_manager')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/job_categories_manager') || $this->uri->segment(2) == 'job_categories_manager') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/job_categories_manager'); ?>">Job Categories Manager</a>
                            </div>
                        <?php } ?>


                        <?php if (check_access_permissions_for_view($security_details, 'job_categories_manager')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/document_categories_manager') || $this->uri->segment(2) == 'document_categories_manager') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/document_categories_manager'); ?>">Document Categories Manager</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'interview_questionnaires')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/interview_questionnaires') || $this->uri->segment(2) == 'interview_questionnaires') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/interview_questionnaires'); ?>">Interview Questionnaires</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'system_notification_emails')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/system_notification_emails') || $this->uri->segment(2) == 'system_notification_emails') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/system_notification_emails'); ?>">System Notification Emails</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'turnover_cost_logs')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/turnover_cost_calculator_logs') || $this->uri->segment(2) == 'turnover_cost_calculator_logs') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/turnover_cost_calculator_logs'); ?>">Turnover Cost Calculator Logs</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'blocked_app')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/blocked_applicants') || $this->uri->segment(2) == 'blocked_applicants') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/blocked_applicants'); ?>">Blocked Applicants</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'blocked_app')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/blocked_ips') || $this->uri->segment(2) == 'blocked_ips') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/blocked_ips'); ?>">Blocked IPs</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'lms_courses')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('sa/lms/courses') || $this->uri->segment(2) == 'sa') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('sa/lms/courses'); ?>">LMS Courses</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'benefits')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if ($this->uri->segment(2) == 'benefits') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('sa/benefits'); ?>">Benefits</a>
                            </div>
                        <?php } ?>

                        <div class="menu-item">
                            <a <?php
                                if (base_url(uri_string()) == site_url('manage_admin/cms') || $this->uri->segment(2) == 'cms' || $this->uri->segment(2) == 'edit_page') {
                                    echo 'class="active"';
                                }
                                ?> href="<?php echo site_url('manage_admin/cms'); ?>">Content Management System</a>
                        </div>
                        <div class="menu-item">
                            <a <?php
                                if (base_url(uri_string()) == site_url('manage_admin/resources') || $this->uri->segment(2) == 'resources') {
                                    echo 'class="active"';
                                }
                                ?> href="<?php echo site_url('manage_admin/resources'); ?>">Resources</a>
                        </div>
                        <div class="menu-item">
                            <a <?php
                                if (base_url(uri_string()) == site_url('manage_admin/indeed/disposition/status/map')) {
                                    echo 'class="active"';
                                }
                                ?> href="<?php echo site_url('manage_admin/indeed/disposition/status/map'); ?>">Indeed Disposition Status</a>
                        </div>
                    </div>

                </li>
            <?php } ?>
            <?php $functions_names = array('activation_orders', 'accurate_background', 'manage_document'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $accurate_background_menu ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">Accurate Background</a>
                    <div class="submenu" <?php echo $accurate_background_menu ? 'style="display:block;"' : ''; ?>>
                        <?php if (check_access_permissions_for_view($security_details, 'activation_orders')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/accurate_background/activation_orders') || $this->uri->segment(3) == 'manage_document') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/accurate_background/activation_orders'); ?>">Activation
                                    Requests</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'accurate_background')) { ?>
                            <div class="menu-item">
                                <a <?php
                                    if (base_url(uri_string()) == site_url('manage_admin/accurate_background') || $this->uri->segment(3) == 'order_status' || $this->uri->segment(3) == 'report') {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/accurate_background'); ?>">Orders</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('customize_home_page', 'resource_page', 'hr_documents_content'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $customize_pages ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">Customize Pages</a>
                    <div class="submenu" <?php echo $customize_pages ? 'style="display:block;"' : ''; ?>>
                        <?php if (check_access_permissions_for_view($security_details, 'customize_home_page')) { ?>
                            <div class="menu-item">
                                <a <?php if (base_url(uri_string()) == site_url('manage_admin/home_page/customize_home_page')) {
                                        echo 'class="active"';
                                    }
                                    ?> href="<?php echo site_url('manage_admin/home_page/customize_home_page'); ?>">Customize Home Page</a>
                            </div>
                        <?php } ?>

                        <?php  //if (check_access_permissions_for_view($security_details, 'customize_home_page')) { 
                        ?>
                        <div class="menu-item">
                            <a <?php if (base_url(uri_string()) == site_url('manage_admin/resource_page')) {
                                    echo 'class="active"';
                                }
                                ?> href="<?php echo site_url('manage_admin/resource_page'); ?>">Customize Resource Page</a>
                        </div>
                        <?php //} 
                        ?>

                        <?php  //if (check_access_permissions_for_view($security_details, 'customize_home_page')) { 
                        ?>
                        <div class="menu-item">
                            <a <?php if ($this->uri->segment(2) == 'hr_documents_content') {
                                    echo 'class="active"';
                                }
                                ?> href="<?php echo site_url('manage_admin/hr_documents_content'); ?>">HR Documents Page Content</a>
                        </div>
                        <?php //} 
                        ?>
                    </div>
                </li>
            <?php } ?>

            <?php $functions_names = array('oem_manufacturers'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $oem_manufacturers ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">OEM, Independent, Vendor</a>
                    <div class="submenu" <?php echo $oem_manufacturers ? 'style="display:block;"' : ''; ?>>
                        <?php if (check_access_permissions_for_view($security_details, 'oem_manufacturers')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/oem_manufacturers')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/oem_manufacturers'); ?>">OEM, Independent, Vendor</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('support_tickets'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $ticket_support ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">Technical Support</a>
                    <div class="submenu" <?php echo $ticket_support ? 'style="display:block;"' : ''; ?>>
                        <?php if (check_access_permissions_for_view($security_details, 'support_tickets')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/support_tickets')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/support_tickets'); ?>">Support Tickets</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('financial_reports_index'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $financial_reports_menu ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">Financial Reports</a>

                    <div class="submenu" <?php echo $financial_reports_menu ? 'style="display:block;"' : ''; ?>>
                        <?php if (check_access_permissions_for_view($security_details, 'yearly_sales_comparison')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), 'sales_comparison') !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/yearly_sales_comparison'); ?>">Yearly Sales Comparison</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'yearly_sales')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), 'manage_admin/financial_reports/yearly_sales') !== false && current_url() != base_url('manage_admin/financial_reports/yearly_sales_comparison')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/yearly_sales'); ?>">Yearly Sales</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'monthly_sales')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), site_url('manage_admin/financial_reports/monthly_sales')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/monthly_sales'); ?>">Monthly Sales</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'monthly_marketplace_products_usage')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), site_url('manage_admin/financial_reports/monthly_marketplace_products_usage')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/monthly_marketplace_products_usage'); ?>">Monthly Marketplace Products Usage</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'monthly_marketplace_products_sales')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), site_url('manage_admin/financial_reports/monthly_marketplace_products_sales')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/monthly_marketplace_products_sales'); ?>">Monthly Marketplace Products Sales</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'monthly_marketplace_product_statistics')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), site_url('manage_admin/financial_reports/monthly_marketplace_product_statistics')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/monthly_marketplace_product_statistics'); ?>">Monthly Marketplace Products Statistics</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'monthly_profit_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), site_url('manage_admin/financial_reports/monthly_profit_report')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/monthly_profit_report'); ?>">Monthly Profit</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'monthly_unpaid_invoices')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), site_url('manage_admin/financial_reports/monthly_unpaid_invoices')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/monthly_unpaid_invoices'); ?>">Monthly Unpaid Invoices</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'sms_service_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(current_url(), site_url('manage_admin/financial_reports/sms_service_report')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/financial_reports/sms_service_report'); ?>">SMS Service Report</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>

            <?php $functions_names = array('job_products_report', 'applicants_report', 'applicant_status_report', 'applicant_offers_report', 'applicants_referrals_report', 'jobs_per_month_report', 'interviews_report', 'applicants_between_period_report', 'time_to_fill_job_report', 'job_categories_report', 'new_hires_report', 'new_hires_onboarding_report', 'job_views_report', 'advanced_jobs_report', 'employees_termination_report'); ?>
            <!--            --><?php //$functions_names = array('job_products_report', 'applicants_report', 'applicant_status_report', 'applicant_offers_report', 'applicants_referrals_report', 'jobs_per_month_report', 'interviews_report', 'applicants_between_period_report', 'time_to_fill_job_report', 'time_to_hire_job_report', 'job_categories_report', 'new_hires_report', 'new_hires_onboarding_report', 'job_views_report', 'advanced_jobs_report'); 
                                ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="<?php echo $reports_menu ? 'hr-opened-menu' : 'hr-closed-menu'; ?>" href="javascript:;">Advanced Reports</a>
                    <div class="submenu" <?php echo $reports_menu ? 'style="display:block;"' : ''; ?>>

                        <div class="menu-item">
                            <a <?php if (strpos(base_url(uri_string()), site_url('employee_profile_data_report')) !== false || ($this->uri->segment(3) == 'employee_profile_data_report')) {
                                    echo 'class="active"';
                                } ?> href="<?php echo site_url('employee_profile_data_report'); ?>">Employee Profile Data Report</a>
                        </div>
                        <div class="menu-item">
                            <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/main/facebook_job_report')) !== false || ($this->uri->segment(3) == 'main/facebook_job_report')) {
                                    echo 'class="active"';
                                } ?> href="<?php echo site_url('manage_admin/reports/main/facebook_job_report'); ?>">Facebook Jobs Report</a>
                        </div>
                        <div class="menu-item">
                            <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/main/blacklist_email')) !== false || ($this->uri->segment(3) == 'main/blacklist_email')) {
                                    echo 'class="active"';
                                } ?> href="<?php echo site_url('manage_admin/reports/main/blacklist_email'); ?>">Blacklist emails</a>
                        </div>
                        <div class="menu-item">
                            <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/job_status_report')) !== false || ($this->uri->segment(3) == 'job_status_report')) {
                                    echo 'class="active"';
                                } ?> href="<?php echo site_url('manage_admin/reports/job_status_report'); ?>">Job Status Report</a>
                        </div>
                        <?php if (check_access_permissions_for_view($security_details, 'job_products_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/job_products_report')) !== false || ($this->uri->segment(3) == 'job_products_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/job_products_report'); ?>">Job Products Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicants_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicants_report')) !== false || ($this->uri->segment(3) == 'applicants_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicants_report'); ?>">Applicants Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicant_status_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicant_status_report')) !== false || ($this->uri->segment(3) == 'applicant_status_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicant_status_report'); ?>">Applicant Status Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicant_offers_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicant_offers_report')) !== false || ($this->uri->segment(3) == 'applicant_offers_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicant_offers_report'); ?>">Applicant Offers Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicant_source_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (($this->uri->segment(3) == 'applicant_source_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicant_source_report'); ?>">Applicant Source Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicant_source_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (($this->uri->segment(3) == 'copy_applicants_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/copy_applicants_report'); ?>">Copy Applicant Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'daily_applicant_source_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicant_source_report_daily')) !== false || ($this->uri->segment(3) == 'applicant_source_report_daily')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicant_source_report_daily'); ?>">Daily Based Applicants Source Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicant_origination_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicant_origination_report')) !== false || ($this->uri->segment(3) == 'applicant_origination_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicant_origination_report'); ?>">Applicant Origination Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicant_origination_statistics')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicant_origination_statistics')) !== false || ($this->uri->segment(3) == 'applicant_origination_statistics')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicant_origination_statistics'); ?>">Applicant Origination Statistics</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicants_referrals_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicants_referrals_report')) !== false || ($this->uri->segment(3) == 'applicants_referrals_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicants_referrals_report'); ?>">Company Referrals Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'jobs_per_month_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/jobs_per_month_report')) !== false || ($this->uri->segment(3) == 'jobs_per_month_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/jobs_per_month_report'); ?>">Jobs Per Month Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'interviews_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/interviews_report')) !== false || ($this->uri->segment(3) == 'interviews_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/interviews_report'); ?>">Interviews Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicants_between_period_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicants_between_period_report')) !== false || ($this->uri->segment(3) == 'applicants_between_period_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicants_between_period_report'); ?>">Applicants Between Period Report</a>
                            </div>
                        <?php } ?>
                        <!--
                        <?php /*if (check_access_permissions_for_view($security_details, 'time_to_fill_job_report')) { */ ?>
                            <div class="menu-item">
                                <a <?php /*if(strpos(base_url(uri_string()), site_url('manage_admin/reports/time_to_fill_job_report')) !== false || ($this->uri->segment(3) == 'time_to_fill_job_report')) {
                                     echo 'class="active"';
                                } */ ?> href="<?php /*echo site_url('manage_admin/reports/time_to_fill_job_report'); */ ?>">Time to Fill a Posted Job Report</a>
                            </div>
                        <?php /*} */ ?>
                        -->

                        <!--                        --><?php //if (check_access_permissions_for_view($security_details, 'time_to_hire_job_report')) { 
                                                        ?>
                        <!--                            <div class="menu-item">-->
                        <!--                                <a --><?php //if(strpos(base_url(uri_string()), site_url('manage_admin/reports/time_to_hire_job_report')) !== false || ($this->uri->segment(3) == 'time_to_hire_job_report')) {
                                                                    //                                     echo 'class="active"';
                                                                    //                                } 
                                                                    ?>
                        <!-- href="--><?php //echo site_url('manage_admin/reports/time_to_hire_job_report'); 
                                        ?>
                        <!--">Time to Hire a Candidate for Job Report</a>-->
                        <!--                            </div>-->
                        <!--                        --><?php //} 
                                                        ?>
                        <!--
                        <?php /*if (check_access_permissions_for_view($security_details, 'job_categories_report')) { */ ?>
                            <div class="menu-item">
                                <a <?php /*if(strpos(base_url(uri_string()), site_url('manage_admin/reports/job_categories_report')) !== false || ($this->uri->segment(3) == 'job_categories_report')) {
                                     echo 'class="active"';
                                } */ ?> href="<?php /*echo site_url('manage_admin/reports/job_categories_report'); */ ?>">Recently Hired from Job Categories Report</a>
                            </div>
                        <?php /*} */ ?>
                        -->
                        <!--
                        <?php /*if (check_access_permissions_for_view($security_details, 'new_hires_report')) { */ ?>
                            <div class="menu-item">
                                <a <?php /*if(strpos(base_url(uri_string()), site_url('manage_admin/reports/new_hires_report')) !== false || ($this->uri->segment(3) == 'new_hires_report')) {
                                     echo 'class="active"';
                                } */ ?> href="<?php /*echo site_url('manage_admin/reports/new_hires_report'); */ ?>">New Hires Report</a>
                            </div>
                        <?php /*} */ ?>
                        -->
                        <!--
                        <?php /*if (check_access_permissions_for_view($security_details, 'new_hires_onboarding_report')) { */ ?>
                            <div class="menu-item">
                                <a <?php /*if(strpos(base_url(uri_string()), site_url('manage_admin/reports/new_hires_onboarding_report')) !== false || ($this->uri->segment(3) == 'new_hires_onboarding_report')) {
                                     echo 'class="active"';
                                } */ ?> href="<?php /*echo site_url('manage_admin/reports/new_hires_onboarding_report'); */ ?>">New Hires On-Boarding Report</a>
                            </div>
                        <?php /*} */ ?>
                        -->
                        <?php if (check_access_permissions_for_view($security_details, 'job_views_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/job_views_report')) !== false || ($this->uri->segment(3) == 'job_views_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/job_views_report'); ?>">Job Views Report</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'advanced_jobs_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/advanced_jobs_report')) !== false || ($this->uri->segment(3) == 'advanced_jobs_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/advanced_jobs_report'); ?>">Advanced Jobs Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'invoice_item_usage')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/invoice_item_usage')) !== false || ($this->uri->segment(3) == 'invoice_item_usage')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/invoice_item_usage'); ?>">Invoice Item Usage</a>
                            </div>
                        <?php } ?>
                        <!-- <?php if (check_access_permissions_for_view($security_details, 'accurate_background_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/accurate_background')) !== false || ($this->uri->segment(3) == 'accurate_background')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/accurate_background'); ?>">Accurate Background Report</a>
                            </div>
                        <?php } ?> -->
                        <?php if (check_access_permissions_for_view($security_details, 'applicant_interview_scores')) { ?>
                            <!--                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicant_interview_scores/all/all')) !== false || ($this->uri->segment(3) == 'applicant_interview_scores')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/applicant_interview_scores/all/all'); ?>">Applicant Interview Scores</a>
                            </div>-->
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'applicant_origination_tracker')) { ?>
                            <!--                        <div class="menu-item">
                            <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/applicant_origination_tracker/all/all/all/all')) !== false || ($this->uri->segment(3) == 'applicant_origination_tracker')) {
                                    echo 'class="active"';
                                } ?> href="<?php echo site_url('manage_admin/reports/applicant_origination_tracker/all/all/all/all'); ?>">Applicant Origination Tracker Report</a>
                        </div>-->
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'complynet_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/complynet_report')) !== false || ($this->uri->segment(3) == 'complynet_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/complynet_report'); ?>">ComplyNet Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'employees_termination_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/employees_termination_report')) !== false || ($this->uri->segment(3) == 'employees_termination_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/employees_termination_report'); ?>">Employees Termination Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'indeed')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/indeed')) !== false || ($this->uri->segment(3) == 'indeed')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/indeed'); ?>">Indeed Reports</a>
                            </div>
                        <?php } ?>

                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('employer_login_duration', 'company_daily_activity_report', 'company_weekly_activity_report', 'daily_activity_report', 'weekly_activity_report', 'daily_inactivity_report', 'weekly_inactivity_report', 'daily_activity_overview_report', 'weekly_activity_overview_report', 'daily_activity_detailed_overview_report'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="hr-closed-menu <?php if ($activity_reports_menu) {
                                                    echo 'hr-opened-menu';
                                                } ?>" href="javascript:;">Employer Activity Reports</a>
                    <div class="submenu" <?php if ($activity_reports_menu) {
                                                echo 'style="display:block;"';
                                            } ?>>

                        <?php if (check_access_permissions_for_view($security_details, 'employer_login_duration')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/employer_login_duration')) !== false || ($this->uri->segment(2) == 'employer_login_duration')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/employer_login_duration'); ?>">Employer Login Duration</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'company_daily_activity_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/company_daily_activity_report')) !== false || ($this->uri->segment(3) == 'company_daily_activity_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/company_daily_activity_report'); ?>">Company Daily Activity Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'company_weekly_activity_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/company_weekly_activity_report')) !== false || ($this->uri->segment(3) == 'company_weekly_activity_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/company_weekly_activity_report'); ?>">Company Weekly Activity Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'daily_activity_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/daily_activity_report')) !== false || ($this->uri->segment(3) == 'daily_activity_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/daily_activity_report'); ?>">Daily Activity Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'weekly_activity_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/weekly_activity_report')) !== false || ($this->uri->segment(3) == 'weekly_activity_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/weekly_activity_report'); ?>">Weekly Activity Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'daily_inactivity_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/daily_inactivity_report')) !== false || ($this->uri->segment(3) == 'daily_inactivity_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/daily_inactivity_report'); ?>">Daily Inactivity Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'weekly_inactivity_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/weekly_inactivity_report')) !== false || ($this->uri->segment(3) == 'weekly_inactivity_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/weekly_inactivity_report'); ?>">Weekly Inactivity Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'daily_activity_overview_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/daily_activity_overview_report')) !== false || ($this->uri->segment(3) == 'daily_activity_overview_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/daily_activity_overview_report'); ?>">Daily Activity Overview Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'weekly_activity_overview_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/weekly_activity_overview_report')) !== false || ($this->uri->segment(3) == 'weekly_activity_overview_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/weekly_activity_overview_report'); ?>">Weekly Activity Overview Report</a>
                            </div>
                        <?php } ?>
                        <?php if (check_access_permissions_for_view($security_details, 'daily_activity_detailed_overview_report')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/daily_activity_detailed_overview_report')) !== false || ($this->uri->segment(3) == 'daily_activity_detailed_overview_report')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/daily_activity_detailed_overview_report'); ?>">Daily Activity Detailed Overview Report</a>
                            </div>
                        <?php } ?>

                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('incident_reporting', 'add_new_question', 'view_incident_questions', 'edit_question', 'add_new_type', 'reported_incidents'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="hr-closed-menu <?php if ($incident_reporting_menu) {
                                                    echo 'hr-opened-menu';
                                                } ?>" href="javascript:;">Incidents Reports</a>
                    <div class="submenu" <?php if ($incident_reporting_menu) {
                                                echo 'style="display:block;"';
                                            } ?>>

                        <?php if (check_access_permissions_for_view($security_details, 'list_types')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/incident_reporting')) !== false && ($this->uri->segment(4) == '')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/incident_reporting'); ?>">List Incident Types</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'reported_incident')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/incident_reporting/reported_incidents')) !== false || ($this->uri->segment(4) == 'reported_incidents')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/incident_reporting/reported_incidents'); ?>">Reported Incidents</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>



            <!-- -->
            <li>
                <a class="hr-closed-menu <?php if ($compliance_safty_reporting_menu) {
                                                echo 'hr-opened-menu';
                                            } ?>" href="javascript:;">Compliance Safety Reports</a>
                <div class="submenu" <?php if ($compliance_safty_reporting_menu) {
                                            echo 'style="display:block;"';
                                        } ?>>

                    <?php if (check_access_permissions_for_view($security_details, 'list_types')) { ?>
                        <div class="menu-item">
                            <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/compliance_reporting')) !== false && ($this->uri->segment(4) == '')) {
                                    echo 'class="active"';
                                } ?> href="<?php echo site_url('manage_admin/reports/compliance_reporting'); ?>">Compliance Safety Types</a>
                        </div>
                    <?php } ?>

                </div>
            </li>




            <?php $functions_names = array('safety_sheet', 'category_management');
            if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="hr-closed-menu <?php if ($safety_data_sheet_menu) {
                                                    echo 'hr-opened-menu';
                                                } ?>" href="javascript:;">Safety Data Sheet</a>
                    <div class="submenu" <?php if ($safety_data_sheet_menu) {
                                                echo 'style="display:block;"';
                                            } ?>>

                        <?php if (check_access_permissions_for_view($security_details, 'safety_sheet')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/safety_data_sheet')) !== false && ($this->uri->segment(3) == '')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/safety_data_sheet'); ?>">Safety Sheet</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'category_management')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/safety_data_sheet/category_management')) !== false || ($this->uri->segment(4) == 'category_management')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/safety_data_sheet/category_management'); ?>">Category Management</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('document_library', 'view_details', 'add_new_menu', 'edit_sub_menu', 'view_sub_heading', 'add_new_heading', 'add_default_category', 'edit_default_category', 'default_categories_listing'); ?>
            <?php if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="hr-closed-menu <?php if ($documents_library_menu) {
                                                    echo 'hr-opened-menu';
                                                } ?>" href="javascript:;">Resource Center</a>
                    <div class="submenu" <?php if ($documents_library_menu) {
                                                echo 'style="display:block;"';
                                            } ?>>

                        <?php if (check_access_permissions_for_view($security_details, 'document_library')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/documents_library')) !== false) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/documents_library'); ?>">Resource Center Management</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <?php $functions_names = array('affiliate_request', 'referred_affiliate', 'referred_clients');
            if (check_access_permissions_for_view($security_details, $functions_names)) { ?>
                <li>
                    <a class="hr-closed-menu <?php if ($affiliate_program_menu) {
                                                    echo 'hr-opened-menu';
                                                } ?>" href="javascript:;">Affiliate Program</a>
                    <div class="submenu" <?php if ($affiliate_program_menu) {
                                                echo 'style="display:block;"';
                                            } ?>>

                        <div class="menu-item">
                            <?php if (check_access_permissions_for_view($security_details, 'affiliate_request')) { ?>
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/affiliates')) !== false && $this->uri->segment(2)  != 'referred_clients' && $this->uri->segment(2)  != 'referred_affiliates') {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/affiliates'); ?>">Affiliate Request</a>
                            <?php } ?>
                            <!-- <a <? php // echo $this->uri->segment(2)  == 'referred' ? 'class="active"' : ''; 
                                    ?> href="<?php echo site_url('manage_admin/referred'); ?>">Referred</a> -->
                            <?php if (check_access_permissions_for_view($security_details, 'referred_affiliate')) { ?>
                                <a <?php echo $this->uri->segment(2)  == 'referred_affiliates' ? 'class="active"' : ''; ?> href="<?php echo site_url('manage_admin/referred_affiliates'); ?>">Referred Affiliate</a>
                            <?php }
                            if (check_access_permissions_for_view($security_details, 'referred_clients')) { ?>
                                <a <?php echo $this->uri->segment(2)  == 'referred_clients' ? 'class="active"' : ''; ?> href="<?php echo site_url('manage_admin/referred_clients'); ?>">Referred Clients</a>
                            <?php } ?>
                        </div>
                </li>
            <?php } ?>
            <!--                <li>
                    <a class="hr-closed-menu <?php if ($incident_reporting_menu) {
                                                    echo 'hr-opened-menu';
                                                } ?>" href="javascript:;">Incidents Reports</a>
                    <div class="submenu" <?php if ($incident_reporting_menu) {
                                                echo 'style="display:block;"';
                                            } ?>>

                        <?php if (check_access_permissions_for_view($security_details, 'incident_reporting')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/incident_reporting')) !== false && ($this->uri->segment(4) == '')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/incident_reporting'); ?>">List Incident Types</a>
                            </div>
                        <?php } ?>

                        <?php if (check_access_permissions_for_view($security_details, 'reported_incidents')) { ?>
                            <div class="menu-item">
                                <a <?php if (strpos(base_url(uri_string()), site_url('manage_admin/reports/incident_reporting/reported_incidents')) !== false || ($this->uri->segment(4) == 'reported_incidents')) {
                                        echo 'class="active"';
                                    } ?> href="<?php echo site_url('manage_admin/reports/incident_reporting/reported_incidents'); ?>">Reported Incidents</a>
                            </div>
                        <?php } ?>
                    </div>
                </li>-->

            <!--  -->
            <?php if (check_access_permissions_for_view($security_details, 'affiliate_request')) { ?>
                <li>
                    <a class="hr-closed-menu hr-opened-menu>" href="javascript:;">Calendar</a>
                    <div class="submenu" <?= strpos(base_url(uri_string()), site_url('manage_admin/my-events')) !== false  ? 'style="display:block;"' : ''; ?>>
                        <div class="menu-item">
                            <a href="<?= site_url('manage_admin/my-events'); ?>" <?= strpos(base_url(uri_string()), site_url('manage_admin/my-events')) !== false ? 'class="active"' : ''; ?>>Events</a>
                        </div>
                    </div>
                </li>
            <?php } ?>


            <!--
                SMS
                TODO: Set proper permission
            -->
            <?php if (check_access_permissions_for_view($security_details, 'affiliate_request')) { ?>
                <li>
                    <a class="hr-closed-menu hr-opened-menu>" href="javascript:;">SMS MODULE</a>
                    <div class="submenu" <?= strpos(base_url(uri_string()), site_url('manage_admin/sms')) !== false  ? 'style="display:block;"' : ''; ?>>
                        <div class="menu-item">
                            <a href="<?= site_url('manage_admin/sms/send'); ?>" <?= strpos(base_url(uri_string()), site_url('manage_admin/sms/send')) !== false ? 'class="active"' : ''; ?>>Send</a>
                            <a href="<?= site_url('manage_admin/sms/view'); ?>" <?= strpos(base_url(uri_string()), site_url('manage_admin/sms/view')) !== false ? 'class="active"' : ''; ?>>View</a>
                        </div>
                    </div>
                </li>
            <?php } ?>
            <!--
                Job Feeds
                TODO: Set proper permission
            -->
            <?php if (check_access_permissions_for_view($security_details, 'job_feeds_management')) { ?>
                <li>
                    <a class="hr-closed-menu <?php if ($job_feed) {
                                                    echo 'hr-opened-menu';
                                                } ?>" href="javascript:;">Job Feed</a>

                    <div class="submenu" <?= strpos(base_url(uri_string()), site_url('manage_admin/job_feeds_management')) !== false || strpos(base_url(uri_string()), site_url('manage_admin/custom_job_feeds_management')) !== false || strpos(base_url(uri_string()), site_url('manage-admin')) !== false ? 'style="display:block;"' : ''; ?>>
                        <div class="menu-item">
                            <a <?php echo in_array($this->uri->segment(3), array('jobs_active_on_feeds', 'refunded_requests')) ||  $this->uri->segment(2) == 'job_feeds_management' ?  'class="active"' : ''; ?> href="<?php echo site_url('manage_admin/job_feeds_management'); ?>">Job Feeds Management</a>
                            <a <?php echo $this->uri->segment(2) == 'custom_job_feeds_management' ?  'class="active"' : ''; ?> href="<?php echo site_url('manage_admin/custom_job_feeds_management'); ?>">Custom Job Feeds</a>
                            <a <?php echo $this->uri->segment(2) == 'job-feed' ?  'class="active"' : ''; ?> href="<?php echo site_url('manage-admin/job-feed'); ?>">Job Feeds URLs</a>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>