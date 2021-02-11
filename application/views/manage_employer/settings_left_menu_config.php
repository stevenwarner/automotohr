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
        <?php   if(check_access_permissions_for_view($security_details, 'appearance')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('appearance')) { echo 'class="active"'; } ?> href="<?php echo base_url('appearance'); ?>">
                        <figure><i class="fa fa-laptop"></i></figure>Themes & Appearance</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'career_logo_management')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('appearance/career_logo_management')) { echo 'class="active"'; } ?> href="<?php echo base_url('appearance/career_logo_management'); ?>">
                        <figure><i class="fa fa-image"></i></figure>Career Page Logo</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'seo_tags')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('seo_tags')) { echo 'class="active"'; } ?> href="<?php echo base_url('seo_tags'); ?>">
                        <figure><i class="fa fa-search-plus"></i></figure>SEO Tags</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'embedded_code')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('embedded_code')) { echo 'class="active"'; } ?> href="<?php echo base_url('embedded_code'); ?>">
                        <figure><i class="fa fa-chain"></i></figure>Embedded Code</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'portal_widget')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('portal_widget')) { echo 'class="active"'; } ?> href="<?php echo base_url('portal_widget'); ?>">
                        <figure><i class="fa fa-map-signs"></i></figure>Career Page Widget</a>
                    </li>
        <?php   } ?>
        <?php   //if(check_access_permissions_for_view($security_details, 'web_services')) { ?>
                    <!--<li>
                        <a <?php if (base_url(uri_string()) == site_url('web_services')) { echo 'class="active"'; } ?> href="<?php echo base_url('web_services'); ?>">
                        <figure><i class="fa fa-server"></i></figure>Career Page XML WEBSERVICE</a>
                    </li>-->
        <?php   //} ?>
        <?php   if(check_access_permissions_for_view($security_details, 'xml_export')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('xml_export')) { echo 'class="active"'; } ?> href="<?php echo base_url('xml_export'); ?>">
                        <figure><i class="fa fa-server"></i></figure>XML Jobs feed</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'domain_management')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('domain_management')) { echo 'class="active"'; } ?> href="<?php echo base_url('domain_management'); ?>">
                        <figure><i class="fa fa-laptop"></i></figure>Domain Management</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'social_links')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('social_links')) { echo 'class="active"'; } ?> href="<?php echo base_url('social_links'); ?>">
                        <figure><i class="fa fa-share-alt"></i></figure>Social Links Management</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'Employee_login_text')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('employee_login_link')) { echo 'class="active"'; } ?> href="<?php echo base_url('employee_login_link'); ?>">
                        <figure><i class="fa fa-lock"></i></figure>Employee Log In Text</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'talent_network_content_configuration')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('talent_network_content_configuration')) { echo 'class="active"'; } ?> href="<?php echo base_url('talent_network_content_configuration'); ?>">
                        <figure><i class="fa fa-users"></i></figure>Talent Content Configuration</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'job_fair_config')) { ?>
                    <li>
                        <a <?=in_array('job_fair_configuration', $this->uri->segment_array()) ? 'class="active"' : '';?> href="<?php echo base_url('job_fair_configuration'); ?>">
                        <figure><i class="fa fa-users"></i></figure>Job Fair Configuration</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'manage_career_page_maintenance_mode')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('settings/manage_career_page_maintenance_mode')) { echo 'class="active"'; } ?> href="<?php echo base_url('settings/manage_career_page_maintenance_mode'); ?>">
                            <figure><i class="fa fa-users"></i></figure>Career Page Maintenance Mode</a>
                    </li>
        <?php   } ?>
        <?php    if(check_access_permissions_for_view($security_details, 'photo_gallery')) { ?>
                    <li>
                        <a <?php if ((base_url(uri_string()) == site_url('photo_gallery')) || (base_url(uri_string()) == site_url('photo_gallery/add'))) { echo 'class="active"'; } ?> href="<?php echo base_url('photo_gallery'); ?>">
                            <figure><i class="fa fa-picture-o"></i></figure>Photo Gallery</a>
                    </li>
        <?php    } ?>
        <?php    if(check_access_permissions_for_view($security_details, 'preview_job_listing')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('settings/preview_job_listing_template')) { echo 'class="active"'; } ?> href="<?php echo base_url('settings/preview_job_listing_template'); ?>">
                            <figure><i class="fa fa-eye"></i></figure>Preview Job Listing Template</a>
                    </li>
        <?php    } ?>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('resource_page')) { echo 'class="active"'; } ?> href="<?php echo base_url('resource_pages'); ?>">
                <figure><i class="fa fa-files-o"></i></figure>Resources</a>
        </li>
    </ul>
</div>