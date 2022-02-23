<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('dashboard')) { echo 'class="active"'; } ?> href="<?php echo base_url('dashboard'); ?>">
            <figure><i class="fa fa-th"></i></figure>Dashboard</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('my_settings')) { echo 'class="active"'; } ?> href="<?php echo base_url('my_settings') ?>">
            <figure><i class="fa fa-sliders"></i></figure>Settings</a>
        </li>
        <?php if(check_access_permissions_for_view($security_details, 'company_profile')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('company_profile')) { echo 'class="active"'; } ?> href="<?php echo base_url('company_profile'); ?>">
            <figure><i class="fa fa-diamond"></i></figure>Company Profile</a>
        </li>
        <?php } ?>
        <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] == 0) { ?>
            <li>
                <a <?php if ($this->uri->segment(1) == 'department_management') { echo 'class="active"'; } ?> href="<?php echo base_url('department_management'); ?>">
                <figure><i class="fa fa-university"></i></figure>Department Management</a>
            </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'company_address')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('company_address')) { echo 'class="active"'; } ?> href="<?php echo base_url('company_address'); ?>">
            <figure><i class="fa fa-building"></i></figure>Company Contact Details</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'security_access_level')) { ?>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('security_access_level')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('security_access_level'); ?>">
            <figure><i class="fa fa-unlock-alt"></i></figure>Security Access Manager</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'Expiries_manager')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('expirations_manager')) { echo 'class="active"'; } ?> href="<?php echo base_url('expirations_manager'); ?>">
            <figure><i class="fa fa-clock-o"></i></figure>Expirations Manager</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'kpa_onboarding')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('outsourced_hr_compliance_and_onboarding')) { echo 'class="active"'; } ?> href="<?php echo base_url('outsourced_hr_compliance_and_onboarding'); ?>">
            <figure><i class="fa fa-external-link"></i></figure>Outsourced HR Onboarding</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'facebook_configuration')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('facebook_configuration')) { echo 'class="active"'; } ?> href="<?php echo base_url('facebook_configuration'); ?>">
            <figure><i class="fa fa-facebook-f"></i></figure>Facebook Job Listing API</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'import_csv')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('import_csv')) { echo 'class="active"'; } ?> href="<?php echo base_url('import_csv'); ?>">
            <figure><i class="fa fa-file-text"></i></figure>Import Employees Using CSV</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'import_applicants_csv')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('import_applicants_csv')) { echo 'class="active"'; } ?> href="<?php echo base_url('import_applicants_csv'); ?>">
                <figure><i class="fa fa-file-text"></i></figure>Import Applicants Using CSV</a>
        </li>
        <?php } ?>
        <?php $my_company_id = $this->session->userdata('logged_in')['company_detail']['sid'];
        if($my_company_id == 51 || $my_company_id == 57) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('assign_bulk_documents')) { echo 'class="active"'; } ?> href="<?php echo base_url('assign_bulk_documents'); ?>">
                <figure><i class="fa fa-file-text"></i></figure>Assign Bulk Documents</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'job_listing_categories_manager')) { ?>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('job_listing_categories')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('job_listing_categories'); ?>">
            <figure><i class="fa fa-list-alt"></i></figure>Job Listing Categories</a>
        </li> 
        <?php } ?>       
        <?php if(check_access_permissions_for_view($security_details, 'export_emp_csv')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('export_employees_csv')) { echo 'class="active"'; } ?> href="<?php echo base_url('export_employees_csv'); ?>">
                <figure><i class="fa fa-file-text"></i></figure>Export Employees to CSV File</a>
        </li>
        <?php } ?>            
        <?php if(check_access_permissions_for_view($security_details, 'export_app_csv')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('export_applicants_csv')) { echo 'class="active"'; } ?> href="<?php echo base_url('export_applicants_csv'); ?>">
                <figure><i class="fa fa-file-text"></i></figure>Export Applicants to CSV File</a>
        </li>
        <?php } ?>  
        <?php if(check_access_permissions_for_view($security_details, 'bulk_resume_download')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('bulk_resume_download')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('bulk_resume_download'); ?>">
                    <figure><i class="fa fa-download"></i></figure>Bulk Resume Download</a>
            </li>
        <?php } ?>     
        <?php if(check_access_permissions_for_view($security_details, 'approval_rights_management')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('approval_rights_management')) { echo 'class="active"'; } ?> href="<?php echo base_url('approval_rights_management'); ?>">
            <figure><i class="fa fa-list-alt"></i></figure>Approval Rights Management</a>
        </li>   
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'portal_email_templates')) { ?>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('portal_email_templates')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('portal_email_templates'); ?>">
            <figure><i class="fa fa-envelope"></i></figure>Email Templates Module</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'notification_email')) { ?>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('notification_emails')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('notification_emails'); ?>">
            <figure><i class="fa fa-envelope"></i></figure>Notification Emails</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'applicant_status_bar')) { ?>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('application_status')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('application_status'); ?>">
            <figure><i class="fa fa-list-alt"></i></figure>Applicant Status Bar Module</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'onboarding_configuration')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('onboarding/configuration') || $this->uri->segment(2) == 'edit_ems_notification' || $this->uri->segment(2) == 'edit_welcome_video') { echo 'class="active"'; } ?> href="<?php echo base_url('onboarding/configuration') ?>">
                <figure><i class="fa fa-cogs"></i></figure>Onboarding Configuration</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 're_Assign_applicant')) { ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('re_assign_candidate')) { echo 'class="active"'; } ?> href="<?php echo base_url('re_assign_candidate') ?>">
                <figure><i class="fa fa-eraser"></i></figure>Re Assign Applicants</a>
        </li>
        <?php } ?>
        <?php if(check_access_permissions_for_view($security_details, 'company_Addresses')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('company_addresses')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('company_addresses'); ?>">
                    <figure><i class="fa fa-map-marker"></i></figure>Company Addresses</a>
            </li>
        <?php } ?>
        <?php if(checkIfAppIsEnabled("payroll")) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('company/taxes')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('company/taxes'); ?>">
                    <figure><i class="fa fa-money" aria-hidden="true"></i></figure>Company Tax Information</a>
            </li>
        <?php } ?>
        <?php if(checkIfAppIsEnabled("payroll")) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('company/bank_account')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('company/bank_account'); ?>">
                    <figure><i class="fa fa-bank" aria-hidden="true"></i></figure>Company Bank Account</a>
            </li>
        <?php } ?>
        <?php if(checkIfAppIsEnabled("payroll")) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('company/locations')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('company/locations'); ?>">
                    <figure><i class="fa fa-map" aria-hidden="true"></i></figure>Company Locations</a>
            </li>
        <?php } ?>
        <?php if(checkIfAppIsEnabled("payroll")) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('company/pay_periods')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('company/pay_periods'); ?>">
                    <figure><i class="fa fa-calendar-check-o" aria-hidden="true"></i></figure>Company Pay Pariods</a>
            </li>
        <?php } ?>
    </ul>
</div>