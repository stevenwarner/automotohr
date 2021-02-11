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
        <li>
            <a <?php if (base_url(uri_string()) == site_url('company_profile')) { echo 'class="active"'; } ?> href="<?php echo base_url('company_profile'); ?>">
            <figure><i class="fa fa-diamond"></i></figure>Company Profile</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('company_address')) { echo 'class="active"'; } ?> href="<?php echo base_url('company_address'); ?>">
            <figure><i class="fa fa-building"></i></figure>Company Contact</a>
        </li>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('security_access_level')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('security_access_level'); ?>">
            <figure><i class="fa fa-unlock-alt"></i></figure>Security Access Manager</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('expirations_manager')) { echo 'class="active"'; } ?> href="<?php echo base_url('expirations_manager'); ?>">
            <figure><i class="fa fa-clock-o"></i></figure>Expirations Manager</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('kpa_onboarding')) { echo 'class="active"'; } ?> href="<?php echo base_url('kpa_onboarding'); ?>">
            <figure><i class="fa fa-external-link"></i></figure>Outsourced HR Onboarding</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('facebook_configuration')) { echo 'class="active"'; } ?> href="<?php echo base_url('facebook_configuration'); ?>">
            <figure><i class="fa fa-facebook-f"></i></figure>Facebook App Configuration</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('import_csv')) { echo 'class="active"'; } ?> href="<?php echo base_url('import_csv'); ?>">
            <figure><i class="fa fa-file-text"></i></figure>Import Employees Using CSV</a>
        </li>        
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('job_listing_categories')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('job_listing_categories'); ?>">
            <figure><i class="fa fa-list-alt"></i></figure>Job Listing Categories</a>
        </li>      
        <li>
            <a <?php if (base_url(uri_string()) == site_url('approval_rights_management')) { echo 'class="active"'; } ?> href="<?php echo base_url('approval_rights_management'); ?>">
            <figure><i class="fa fa-list-alt"></i></figure>Approval Rights Management</a>
        </li>    
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('portal_email_templates')) !== false) { echo 'class="active"'; } ?> href="<?php echo base_url('portal_email_templates'); ?>">
            <figure><i class="fa fa-envelope"></i></figure>Email Templates Module</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('seo_tags')) { echo 'class="active"'; } ?> href="<?php echo base_url('seo_tags'); ?>">
            <figure><i class="fa fa-search-plus"></i></figure>SEO Tags</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('embedded_code')) { echo 'class="active"'; } ?> href="<?php echo base_url('embedded_code'); ?>">
            <figure><i class="fa fa-chain"></i></figure>Embedded Code</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('portal_widget')) { echo 'class="active"'; } ?> href="<?php echo base_url('portal_widget'); ?>">
            <figure><i class="fa fa-map-signs"></i></figure>Career Page Widget</a>
        </li>
<!--        <li>
            <a <?php if (base_url(uri_string()) == site_url('web_services')) { echo 'class="active"'; } ?> href="<?php echo base_url('web_services'); ?>">
            <figure><i class="fa fa-server"></i></figure>Career Page XML WEBSERVICE</a>
        </li>-->
        <li>
            <a <?php if (base_url(uri_string()) == site_url('domain_management')) { echo 'class="active"'; } ?> href="<?php echo base_url('domain_management'); ?>">
            <figure><i class="fa fa-laptop"></i></figure>Domain Management</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('social_links')) { echo 'class="active"'; } ?> href="<?php echo base_url('social_links'); ?>">
            <figure><i class="fa fa-share-alt"></i></figure>Social Links</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('talent_network_content_configuration')) { echo 'class="active"'; } ?> href="<?php echo base_url('talent_network_content_configuration'); ?>">
            <figure><i class="fa fa-users"></i></figure>Talent Content Configuration</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('eeo')) { echo 'class="active"'; } ?> href="<?php echo base_url('eeo'); ?>">
            <figure><i class="fa fa-th-list"></i></figure>EEO form Candidates</a>
        </li>
    </ul>
</div>