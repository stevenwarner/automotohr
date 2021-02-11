<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php if(strpos(base_url(uri_string()), site_url('dashboard')) !== false) { echo 'class="active"'; } ?>
                href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('manage_ems')) !== false) { echo 'class="active"'; } ?>
                href="<?php echo base_url("manage_ems"); ?>">
                <figure><i class="fa fa-file-text-o"></i></figure>Employee Management System
            </a>
        </li>
        <!-- First -->
        <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
            <?php if(check_access_permissions_for_view($security_details, 'document_management_portal')) { ?>
                <li>
                    <a <?php if (strpos(current_url(),'hr_documents_management')) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('hr_documents_management') ?>">
                        <figure><i class="fa fa-file"></i></figure>
                        Document Management</a>
                </li>
            <?php } ?>
        <?php  } ?>
        
        <!-- Second -->
        <?php if(check_access_permissions_for_view($security_details, 'onboarding_configuration')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('onboarding/configuration')) !== false || strpos(base_url(uri_string()), site_url('onboarding/edit_welcome_video')) !== false) { echo 'class="active"'; } ?>
                    href="<?php echo base_url("onboarding/configuration"); ?>">
                    <figure><i class="fa fa-shield"></i></figure>Onboarding Configuration
                </a>
            </li>
        <?php  } ?>
        <!-- Third -->
        <?php if (check_access_permissions_for_view($security_details, 'employee_ems_notification')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('add_ems_notification')) !== false || strpos(base_url(uri_string()), site_url('edit_ems_notification')) !== false) { echo 'class="active"'; } ?>
                    href="<?php echo base_url("add_ems_notification"); ?>">
                    <figure><i class="fa fa-bell"></i></figure>Employee EMS Notification
                </a>
            </li>
        <?php  } ?>
        <!-- Fourth -->
        <?php if (check_access_permissions_for_view($security_details, 'announcements')) { ?>
            <?php  $load_view = check_blue_panel_status(false, 'self');?>
            <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
                <li>
                    <a <?php if (strpos(base_url(uri_string()), site_url('announcements')) !== false || strpos(base_url(uri_string()), site_url('list_announcements')) !== false) { echo 'class="active"'; } ?>
                            href="<?php echo $load_view ? base_url("announcements") : base_url('list_announcements'); ?>">
                        <figure><i class="fa fa-bullhorn"></i></figure>Announcements
                    </a>
                </li>
            <?php  } ?>
        <?php  } ?>

        <!-- Fifth -->
        <?php if (check_access_permissions_for_view($security_details, 'Learning_center_panel')) { ?>
            <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                <?php $data['session'] = $this->session->userdata('logged_in'); ?>
                <?php $company_sid = $data["session"]["company_detail"]["sid"]; ?>
                <?php if(in_array($company_sid, explode(',', TEST_COMPANIES))) { ?>
                    <li>
                        <a <?php if (strpos(base_url(uri_string()), site_url('learning_center')) !== false) { echo 'class="active"'; } ?>
                            href="<?php echo base_url("learning_center"); ?>">
                            <figure><i class="fa fa-book"></i></figure>Learning Center
                        </a>
                    </li>
                <?php  } ?>
            <?php  } ?>
        <?php  } ?>

        <!-- Sixth -->
        <?php if (check_access_permissions_for_view($security_details, 'safety_sheet_portal')) { ?>
            <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
               <li>
                    <a <?php if (strpos(base_url(uri_string()), site_url('safety_sheets')) !== false) { echo 'class="active"'; } ?>
                            href="<?php echo base_url("safety_sheets/manage_safety_sheets"); ?>">
                        <figure><i class="fa fa-files-o"></i></figure>Manage Safety Sheets
                    </a>
                </li>
            <?php  } ?>
        <?php  } ?>
        <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
           <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('department_management')) !== false) { echo 'class="active"'; } ?>
                        href="<?php echo base_url("department_management"); ?>">
                    <figure><i class="fa fa-university"></i></figure>Department Management
                </a>
            </li>
        <?php  } ?>
        <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
           <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('onboarding_block')) !== false) { echo 'class="active"'; } ?>
                        href="<?php echo base_url("onboarding_block"); ?>">
                    <figure><i class="fa fa-info-circle"></i></figure>Onboarding Help Box
                </a>
            </li>
        <?php  } ?>

    </ul>
</div>