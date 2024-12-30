<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Dashboard
                                    </a>
                                    Employee Management System
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="box-wrapper">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="row">
                            <!-- First -->
                            <?php if (checkIfAppIsEnabled('emsdocumentmanagement')) { ?>
                                <?php if (check_access_permissions_for_view($security_details, 'documents_management')) { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-file-text-o"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('hr_documents_management') ?>">Document
                                                        Management</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>Document Management</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('hr_documents_management') ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <!-- Second -->
                            <?php if (checkIfAppIsEnabled('onboardingconfiguration')) { ?>

                                <?php if (check_access_permissions_for_view($security_details, 'onboarding_configuration')) { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-shield"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('onboarding/configuration'); ?>">Onboarding
                                                        Configuration</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('onboarding/configuration'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <!-- Third -->
                            <?php if (checkIfAppIsEnabled('employeeemsnotification')) { ?>
                                <?php if (check_access_permissions_for_view($security_details, 'employee_ems_notification')) { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-bell"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('manage_ems/ems_notification'); ?>">Employee
                                                        EMS Notification</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('manage_ems/ems_notification'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <!-- Fourth -->

                            <?php if (checkIfAppIsEnabled('announcements')) { ?>
                                <?php $load_view = check_blue_panel_status(false, 'self'); ?>
                                <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-bullhorn"></i></figure>
                                                <h2 class="post-title">
                                                    <a
                                                        href="<?php echo $load_view ? base_url("announcements") : base_url('list_announcements'); ?>">Announcements</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo $load_view ? base_url("announcements") : base_url('list_announcements'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <!-- Fifth -->
                            <?php if (checkIfAppIsEnabled('learningcenter')) { ?>
                                <?php if ($access_level == 'Employee') { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-book"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('learning_center/my_learning_center'); ?>">My
                                                        Learning Center</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('learning_center/my_learning_center'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-book"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('learning_center'); ?>">Learning Center</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('learning_center'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <!--1-->

                            <!-- Sixth -->
                            <?php if (checkIfAppIsEnabled('safetysheets')) { ?>
                                <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-files-o"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('safety_sheets/manage_safety_sheets'); ?>">Safety
                                                        Sheets</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('safety_sheets/manage_safety_sheets'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <!-- Seventh -->
                            <?php if (checkIfAppIsEnabled('departmentmanagement')) { ?>
                                <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-university"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('department_management'); ?>">Department
                                                        Management</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('department_management'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <!-- Eight -->
                            <?php if (checkIfAppIsEnabled('onboardinghelpbox')) { ?>
                                <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-info-circle"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('onboarding_block'); ?>">Onboarding Help Box</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('onboarding_block'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <?php if (checkIfAppIsEnabled('companyhelpbox')) { ?>
                                <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-info-circle"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url('onboarding_block/manage_company_help_box'); ?>">Company Help Box</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>&nbsp;</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('onboarding_block/manage_company_help_box'); ?>"
                                                        class="site-btn">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <!--end of all-divs-->

                            <!--end of form div-->

                        </div>
                        <!--all remaining ending divs-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>