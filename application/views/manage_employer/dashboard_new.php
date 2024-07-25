    <?php if (!$load_view) { ?>
        <div class="main-content">
            <div class="dashboard-wrp">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                            <div class="dash-box">
                                <div class="admin-info">
                                    <a href="<?php echo base_url('my_profile'); ?>">
                                        <h2>My Contact Info</h2>
                                    </a>
                                    <div class="profile-pic-area">
                                        <figure>
                                            <img src="<?php echo AWS_S3_BUCKET_URL;
                                                        if (isset($employerData['profile_picture']) && !empty($employerData['profile_picture'])) {
                                                            echo $employerData['profile_picture'];
                                                        } else {
                                                        ?>default_pic-ySWxT.jpg<?php } ?>" alt="" />
                                        </figure>
                                        <div class="text">
                                            <ul class="admin-contact-info">
                                                <li>
                                                    <label>Primary</label>
                                                    <?php if ($employerData['is_executive_admin'] == 1) { ?>
                                                        <span><?php echo $employerData['first_name'] . ' ' . $employerData['last_name']; ?></span>
                                                    <?php } else {
                                                        if ($employerData['first_name'] != NULL || $employerData['first_name'] != '' || $employerData['last_name'] != NULL || $employerData['last_name'] != '') {
                                                            $employer_name = $employerData['first_name'] . ' ' . $employerData['last_name'];
                                                        } else {
                                                            $employer_name = $employerData['username'];
                                                        } ?>
                                                        <span><?php echo $employer_name; ?></span>
                                                    <?php } ?>
                                                </li>
                                                <?php if ($employerData['is_executive_admin'] == 1) { ?>
                                                    <li>
                                                        <label>Login Type</label>
                                                        <span>Executive Administrator</span>
                                                    </li>
                                                <?php } else { ?>
                                                    <?php if ($employerData['PhoneNumber'] != NULL) { ?>
                                                        <li>
                                                            <label>Telephone</label>
                                                            <span><?php echo $employerData['PhoneNumber'] ?></span>
                                                        </li>
                                                    <?php } ?>
                                                <?php } ?>

                                            </ul>
                                        </div>
                                        <div class="form-col-100">
                                            <ul class="admin-contact-info">
                                                <li>
                                                    <label>Job Title</label>
                                                    <?php if ($employerData['job_title'] != NULL) { ?>
                                                        <span><?php echo $employerData['job_title'] ?></span>
                                                    <?php } else {
                                                        echo '<b><i>n/a</i></b>';
                                                    } ?>
                                                </li>
                                                <li>
                                                    <label>Work Location</label>
                                                    <span>
                                                        <?php if ($companyData['Location_Address'] != NULL) {
                                                            echo $companyData['Location_Address'];
                                                        }

                                                        if ($companyData['Location_City'] != NULL) {
                                                            echo ',' . $companyData['Location_City'];
                                                        }

                                                        if ($companyData['locationDetail']['state_code'] != NULL) {
                                                            echo ',' . $companyData['locationDetail']['state_code'];
                                                        }

                                                        if ($companyData['locationDetail']['country_name'] != NULL) {
                                                            echo ',' . $companyData['locationDetail']['country_name'];
                                                        } ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <label>Email</label>
                                                    <span><a><?php echo $employerData['email'] ?></a></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $this->load->view('main/employer_column_left_view'); ?>
                        </div>
                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                            <div class="create-job-box">
                                <div class="dash-box">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h1>Welcome <?php echo ucfirst($employerData['first_name']) . ' ' . ucfirst($employerData['last_name']); ?>!</h1>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                            <?php if ($companyData['Logo'] != '' && get_company_logo_status($session['company_detail']['sid']) == 1) { ?>
                                                <figure><img width="100" height="80" src="<?php echo AWS_S3_BUCKET_URL . $companyData['Logo']; ?>" alt="" /></figure>
                                            <?php } ?>
                                            <span class="registered-company-name"><?php echo $companyData['CompanyName']; ?>
                                                <?php if (isCompanyClosed() && isPayrollOrPlus(true)) { ?>
                                                    <label class="label label-danger" title="The store is closed." placement="top">
                                                        Closed
                                                    </label> <?php } ?></span>
                                        </div>
                                        <?php if ($employerData['is_executive_admin'] == 1) { ?>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <span>You have Full Access</span>
                                                <?php if ($employee['is_executive_admin'] == 0) { ?>
                                                    <div class="button-panel">
                                                        <a class="site-btn auto-width" href="<?php echo base_url('my_profile') ?>">View your Profile</a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (check_access_permissions_for_view($security_details, 'my_profile')) { ?>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <span>Ready to get Started?</span>
                                                    <div class="button-panel">
                                                        <a class="site-btn btn-warning" href="<?php echo base_url('employee_management_system') ?>" style="">My Personal EMS Dashboard</a>
                                                    </div>
                                                    <p>You can manage your profile and login credentials</p>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">

                                            <?php if ($task_management_module_status == 1) { ?>
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure><i class="fa fa-tasks"></i></figure>
                                                        <h2 class="post-title"><a href="<?php echo base_url('task_management') ?>">Task Management</a></h2>
                                                        <div class="count-box">
                                                            <small>Your Priority Tasks List</small>
                                                        </div>
                                                        <div class="form-col-100 view-task-btn">
                                                            <a class="btn btn-success btn-block" href="<?php echo base_url('task_management') ?>" class="">View</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php $this->load->view("v1/attendance/partials/clocks/green/welcome_block"); ?>
                                        </div>
                                    </div>
                                    <div class="example-link-green">Because of recent Google Security updates, we suggest that you only use Google Chrome to access your AutomotoHR account. Internet Explorer is not supported and may cause certain feature glitches and security issues.</div>
                                    <?php if ($job_approval_module_status == 1) { ?>
                                        <!--                                    --><?php //if (in_array($employer_sid, $users_with_job_approval_rights) || is_admin($employer_sid)) { 
                                                                                    ?>
                                        <?php if (in_array($employer_sid, $users_with_job_approval_rights)) { ?>
                                            <div class="row approvals_row">
                                                <div class="col-lg-3 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-heading job-approval-heading count-box">Job Listings Approvals</span>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="page-heading no-margin count-box count-box-pending">
                                                        <i><?php echo $all_unapproved_jobs_count; ?></i> Pending
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="page-heading no-margin count-box count-box-approved">
                                                        <i><?php echo $all_approved_jobs_count; ?></i> Approved
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="page-heading reject-color no-margin count-box count-box-rejected">
                                                        <i><?php echo $all_rejected_jobs_count; ?></i> Rejected
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                                    <a class="site-btn manage-approvals no-margin" href="<?php echo base_url('job_approval_management') ?>"><i></i>
                                                        Manage</a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($applicant_approval_module_status == 1) { ?>
                                        <!--                                    --><?php //if (in_array($employer_sid, $users_with_applicant_approval_rights) || is_admin($employer_sid)) { 
                                                                                    ?>
                                        <?php if (in_array($employer_sid, $users_with_applicant_approval_rights)) { ?>
                                            <div class="row approvals_row">
                                                <div class="col-lg-3 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-heading job-approval-heading count-box">Applicant Approvals</span>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="page-heading no-margin count-box count-box-pending">
                                                        <i><?php echo $pending_applicants_count ?></i> Pending
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="page-heading no-margin count-box count-box-approved">
                                                        <i><?php echo $approved_applicants_count ?></i> Approved
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="page-heading reject-color no-margin count-box count-box-rejected">
                                                        <i><?php echo $rejected_applicants_count ?></i> Rejected
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                                    <a class="site-btn manage-approvals no-margin" href="<?php echo base_url('applicant_approval_management') ?>"><i></i>
                                                        Manage</a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="box-wrapper">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="row">
                                    <?php if (check_access_permissions_for_view($security_details, 'add_listing')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-pencil-square-o"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('add_listing') ?>">Create A New Job</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Unlimited Free Jobs</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('add_listing') ?>" class="site-btn">Create
                                                            A New Job</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--1-->
                                    <?php if (check_access_permissions_for_view($security_details, 'market_place')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-shopping-cart"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('market_place') ?>">My Marketplace</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Popular Job Boards</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('market_place') ?>" class="site-btn">Visit</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--2-->
                                    <?php if (check_access_permissions_for_view($security_details, 'my_listings')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-list-alt"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('my_listings/active') ?>">My Jobs</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <span class="green">'<?php echo $jobCountActive; ?>
                                                            ' Active Jobs</span><br>
                                                        <small><?php echo $jobCount; ?> Job(s)</small>
                                                    </div>
                                                    <div class="count-box">
                                                        <small></small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('my_listings/active') ?>" class="site-btn">View All</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--3-->
                                    <?php if (check_access_permissions_for_view($security_details, 'application_tracking')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-line-chart"></i></figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?php echo base_url('application_tracking_system/active/all/all/all/all/all/all/all/all/all') ?>">Application
                                                            Tracking System</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <span class="green">'<?php echo $applicants_today; ?>' New Application(s) Today</span><br>
                                                        <small style="font-size: 12px"><?php echo $applicants; ?> Active Application(s)</small><br>
                                                        <small style="font-size: 12px"><?php echo $all_active_inactive_applicants; ?> Total Application(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('application_tracking_system/active/all/all/all/all/all/all/all/all/all') ?>" class="site-btn">View Applicants</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--4-->
                                    <?php if (check_access_permissions_for_view($security_details, 'my_events')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-calendar"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('calendar/my_events') ?>">Calendar / Events</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <span class="green">'<?php echo $eventCountToday; ?>' Events Scheduled Today</span><br>
                                                        <small><?php echo $eventCount; ?> Event(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('calendar/my_events') ?>" class="site-btn">Show
                                                            Events</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--5-->
                                    <?php if (check_access_permissions_for_view($security_details, 'private_messages')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure>
                                                        <?php if ($unreadMessageCount > 0) { ?>
                                                            <img src="<?= base_url('assets/images/new_msg.gif') ?>">
                                                        <?php } else { ?>
                                                            <i class="fa fa-envelope"></i>
                                                        <?php } ?>
                                                    </figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('private_messages') ?>">Private Message</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small><?php echo $unreadMessageCount; ?> New Message(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('private_messages') ?>" class="site-btn">Show Messages</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--6-->
                                    <?php
                                    $canAccessDocument = hasDocumentsAssigned($session['employer_detail']);
                                    ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_management') || $canAccessDocument) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-users"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url(); ?>employee_management">Employee / Team Members</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Team Management</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url(); ?>employee_management" class="site-btn">Access & Manage</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--7-->
                                    <?php if (check_blue_panel_status_for_view()) { ?>
                                        <!--                                    --><?php //if (check_access_permissions_for_view($security_details, 'documents_management')) { 
                                                                                    ?>
                                        <!--                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                        <!--                                            <div class="dash-box">-->
                                        <!--                                                <div class="dashboard-widget-box">-->
                                        <!--                                                    <figure><i class="fa fa-file-text-o"></i></figure>-->
                                        <!--                                                    <h2 class="post-title">-->
                                        <!--                                                        <a href="--><?php //echo base_url('hr_documents_management') 
                                                                                                                ?>
                                        <!--">Document Management</a>-->
                                        <!--                                                    </h2>-->
                                        <!--                                                    <div class="count-box">-->
                                        <!--                                                        <small>Document Management</small>-->
                                        <!--                                                    </div>-->
                                        <!--                                                    <div class="button-panel">-->
                                        <!--                                                        <a href="--><?php //echo base_url('hr_documents_management') 
                                                                                                                ?>
                                        <!--" class="site-btn">Manage</a>-->
                                        <!--                                                    </div>-->
                                        <!--                                                </div>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                    --><?php //} 
                                                                                    ?>
                                    <?php } ?>
                                    <!--8-->
                                    <?php //if(check_access_permissions_for_view($security_details, 'resume_database')) { 
                                    ?>
                                    <!--                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box">
                                            <div class="dashboard-widget-box">
                                                <figure><i class="fa fa-database"></i></figure>
                                                <h2 class="post-title">
                                                    <a href="<?php echo base_url(); ?>resume_database">Resume Database</a>
                                                </h2>
                                                <div class="count-box">
                                                    <small>Over 5000 Resumes</small>
                                                </div>
                                                <div class="button-panel">
                                                    <a href="<?php echo base_url('resume_database'); ?>" class="site-btn">Search Resumes</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <?php //} 
                                    ?>
                                    <!--9-->
                                    <?php if (check_access_permissions_for_view($security_details, 'my_settings')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure>
                                                        <i class="fa fa-sliders"></i>
                                                    </figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('my_settings') ?>">Settings</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small>Manage Career Site</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('my_settings') ?>" class="site-btn">Configure</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--10-->
                                    <?php if (check_access_permissions_for_view($security_details, 'screening_questionnaires')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('screening_questionnaires') ?>">Candidate
                                                            Questionnaires</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small><?php echo $questionnairCount; ?> Questionnaires</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('screening_questionnaires') ?>" class="site-btn">Create New</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--11-->
                                    <?php if (check_access_permissions_for_view($security_details, 'interview_questionnaire')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('interview_questionnaire'); ?>">Interview
                                                            Questionnaires</a>
                                                    </h2>
                                                    <!--<div class="count-box">
                                                    <small><?php /*echo $unread_tickets_count; */ ?> Unread Ticket(s)</small>
                                                </div>-->
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('interview_questionnaire'); ?>" class="site-btn">Interview Questionnaires</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--12-->
                                    <?php if (check_access_permissions_for_view($security_details, 'background_check')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-file"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('accurate_background'); ?>">Background
                                                            Checks Report</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <span class="green">'<?php echo $checks_monthly_count; ?>' New Check(s) This Month</span><br>
                                                        <small><?php echo $checks_total_count; ?> Check(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('accurate_background'); ?>" class="site-btn">View Report</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--13-->
                                    <?php if (check_access_permissions_for_view($security_details, 'support_tickets')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-tags"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('support_tickets'); ?>">Support
                                                            Tickets</a>
                                                    </h2>
                                                    <div class="count-box">
                                                        <small><?php echo $unread_tickets_count; ?> Unread Ticket(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('support_tickets'); ?>" class="site-btn">Access Support Tickets</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--14-->
                                    <!--
                            <?php /*if(check_access_permissions_for_view($security_details, 'organizational_hierarchy')) { */ ?>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                    <div class="dash-box">
                                        <div class="dashboard-widget-box">
                                            <figure><i class="fa fa-users"></i></figure>
                                            <h2 class="post-title">
                                                <a href="<?php /*echo base_url('organizational_hierarchy'); */ ?>">Organizational Hierarchy</a>
                                            </h2>
                                            <div class="count-box">
                                                <small>&nbsp;</small>
                                            </div>
                                            <div class="button-panel">
                                                <a href="<?php /*echo base_url('organizational_hierarchy'); */ ?>" class="site-btn">Manage</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            --><?php /*} */ ?>
                                    <!--15-->

                                    <?php if ($EmsStatus == 1) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-file-text-o <?= $all_documents_approval ? 'start_animation' : ''; ?>"></i></figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?php echo base_url('hr_documents_management/approval_documents') ?>">Approval Documents</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <small style="font-size: 12px"><?php echo $all_documents_approval; ?> Total Document(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('hr_documents_management/approval_documents') ?>" class="site-btn">View Documents</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php $data['session'] = $this->session->userdata('logged_in'); ?>
                                    <?php $company_sid = $data["session"]["company_detail"]["sid"]; ?>

                                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] && check_access_permissions_for_view($security_details, 'ems_portal')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-file-text-o"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('manage_ems'); ?>">Employee Management System</a>
                                                    </h2>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('manage_ems'); ?>" class="site-btn">Manage</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--                                    --><?php //if($access_level == 'Employee') { 
                                                                                    ?>
                                        <!--                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                        <!--                                            <div class="dash-box">-->
                                        <!--                                                <div class="dashboard-widget-box">-->
                                        <!--                                                    <figure><i class="fa fa-book"></i></figure>-->
                                        <!--                                                    <h2 class="post-title">-->
                                        <!--                                                        <a href="--><?php //echo base_url('learning_center/my_learning_center'); 
                                                                                                                ?>
                                        <!--">My Learning Center</a>-->
                                        <!--                                                    </h2>-->
                                        <!--                                                    <div class="count-box">-->
                                        <!--                                                        <small>&nbsp;</small>-->
                                        <!--                                                    </div>-->
                                        <!--                                                    <div class="button-panel">-->
                                        <!--                                                        <a href="--><?php //echo base_url('learning_center/my_learning_center'); 
                                                                                                                ?>
                                        <!--" class="site-btn">Manage</a>-->
                                        <!--                                                    </div>-->
                                        <!--                                                </div>-->
                                        <!--                                                <span class="beta-label">beta</span>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                    --><?php //} else { 
                                                                                    ?>
                                        <!--                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">-->
                                        <!--                                            <div class="dash-box">-->
                                        <!--                                                <div class="dashboard-widget-box">-->
                                        <!--                                                    <figure><i class="fa fa-book"></i></figure>-->
                                        <!--                                                    <h2 class="post-title">-->
                                        <!--                                                        <a href="--><?php //echo base_url('learning_center'); 
                                                                                                                ?>
                                        <!--">Learning Center</a>-->
                                        <!--                                                    </h2>-->
                                        <!--                                                    <div class="count-box">-->
                                        <!--                                                        <small>&nbsp;</small>-->
                                        <!--                                                    </div>-->
                                        <!--                                                    <div class="button-panel">-->
                                        <!--                                                        <a href="--><?php //echo base_url('learning_center'); 
                                                                                                                ?>
                                        <!--" class="site-btn">Manage</a>-->
                                        <!--                                                    </div>-->
                                        <!--                                                </div>-->
                                        <!--                                                <span class="beta-label">beta</span>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                    --><?php //} 
                                                                                    ?>
                                    <?php } ?>
                                    <?php //} 
                                    ?>
                                    <!--17-->

                                    <?php if ($EmsStatus == 1) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-clipboard <?php echo !empty($total_pending_auth_doc) && $total_pending_auth_doc > 0 ? 'start_animation' : ''; ?>"></i></figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?php echo base_url('authorized_document'); ?>">Pending Authorized Documents</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <span class="green"><?php echo $total_assigned_today_doc; ?> Assigned Document(s) Today</span><br>
                                                        <small style="font-size: 12px"><?php echo $total_pending_auth_doc; ?> Pending Document(s)</small><br>
                                                        <small style="font-size: 12px"><?php echo $total_assigned_auth_doc; ?> Total Document(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('authorized_document'); ?>" class="site-btn">Show Documents</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($EmsStatus == 1) { ?>
                                        <?php if ($session['employer_detail']['access_level'] == 'Admin' || $session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure><i class="fa fa-clipboard <?php echo !empty($PendingEmployerSection['Total']) && $PendingEmployerSection['Total'] > 0 ? 'start_animation' : ''; ?>"></i></figure>
                                                        <h2 class="post-title" style="margin: 2px 0;">
                                                            <a href="<?php echo base_url('hr_documents_management/company_varification_document'); ?>">Pending Employer Section</a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <span class="green"><?= $PendingEmployerSection['Total']; ?> Total</span><br>
                                                            <small style="font-size: 12px"><?= $PendingEmployerSection['Employee']; ?> Employee(s)</small><br>
                                                            <small style="font-size: 12px"><?= $PendingEmployerSection['Applicant']; ?> Applicant(s)</small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="<?php echo base_url('hr_documents_management/company_varification_document'); ?>" class="site-btn">Show Documents</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>

                                    <!-- Employee Information Change -->
                                    <?php if ($EmsStatus == 1) { ?>
                                        <?php if ($access_level_plus == 1 || $pay_plan_flag == 1) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure>
                                                            <i class="fa fa-users <?= $employeeInformationChange['daily'] > 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i>
                                                        </figure>
                                                        <h2 class="post-title" style="margin: 2px 0;">
                                                            <a href="<?php echo base_url('employee/information/report'); ?>">
                                                                Employee Information Change
                                                            </a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <span class="green">Today <?= $employeeInformationChange['daily']; ?> changed information</span><br>
                                                            <small style="font-size: 12px">This week <?= $employeeInformationChange['week']; ?> changed information</small><br>
                                                            <small style="font-size: 12px">This month <?= $employeeInformationChange['month']; ?> changed information</small><br>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="<?php echo base_url('employee/information/report'); ?>" class="site-btn">Show Changes</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php  } ?>

                                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] && checkIfAppIsEnabled('documentlibrary')) { ?>
                                        <!-- Documents Library -->
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-clipboard" aria-hidden="true"></i></figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?php echo base_url('library_document'); ?>">Employee Forms Library</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <small style="font-size: 12px"><?php echo $total_library_doc; ?> Total Form(s)<br></small>
                                                        <strong style="font-size: 12px" class="text-danger">Forms allows Employees the ability to initiate and complete documents on their own.</strong>

                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('library_document'); ?>" class="site-btn">View Forms</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>



                                    <?php $comply_status = $data["session"]["company_detail"]["complynet_status"];
                                    $employee_comply_status = $data["session"]["employer_detail"]["complynet_status"];
                                    $complynet_dashboard_link = $session["company_detail"]["complynet_dashboard_link"];
                                    $access_level  = $session["employer_detail"]['access_level'];
                                    ?>

                                    <?php if (check_access_permissions_for_view($security_details, 'complynet') && $comply_status && $employee_comply_status) { ?>
                                        <?php $complyNetLink = getComplyNetLink($company_sid, $employee_sid); ?>
                                        <?php if ($complyNetLink) {
                                        ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <a href="<?php echo base_url('cn/redirect'); ?>" target="_blank">
                                                        <div class="dashboard-widget-box">
                                                            <figure>
                                                                <img src="<?= base_url('assets/images/complynet_logo.png'); ?>" alt="ComplyNet Image">
                                                            </figure>
                                                            <h2 class="post-title">
                                                                <a href="<?php echo base_url('cn/redirect'); ?>">Compliance <br /> Management System</a>
                                                            </h2>
                                                            <div class="button-panel col-lg-12">
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                        <a href="<?php echo base_url('cn/redirect'); ?>" target="_blank" class="btn btn-success btn-block">Dashboard</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php
                                    $pto_user_access = get_pto_user_access($session['employer_detail']['parent_sid'], $session['employer_detail']['sid']);
                                    ?>

                                    <!-- Performance Review -->

                                    <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-pencil-square-o <?php echo $review['Reviews'] || $review['Feedbacks'] > 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i></figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?php echo base_url('performance-management/dashboard'); ?>">Performance Management</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <small style="font-size: 12px"><?php echo $review['Reviews']; ?> Pending Review(s)</small><br>
                                                        <small style="font-size: 12px"><?php echo $review['Feedbacks']; ?> Pending Feedback(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?= base_url("performance-management/dashboard"); ?>" class="site-btn">Show</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (checkIfAppIsEnabled(SCHEDULE_MODULE) && isPayrollOrPlus()) { ?>
                                        <!-- Schedule -->
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure>
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?= base_url("settings/shifts/manage"); ?>">Shifts</a>
                                                    </h2>
                                                    <div class="button-panel">
                                                        <a href="<?= base_url("settings/shifts/manage"); ?>" class="site-btn">Manage</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (checkIfAppIsEnabled(SCHEDULE_MODULE)) { ?>
                                        <!-- Schedule -->
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure>
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?= base_url("shifts/my"); ?>">My Shifts</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <small style="font-size: 12px"><?php echo $myAssignedShifts; ?> Scheduled</small><br>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?= base_url("shifts/my"); ?>" class="site-btn">Show</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Schedule -->
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure>
                                                        <i class="fa fa-users" aria-hidden="true"></i>
                                                    </figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?= base_url("shifts/my/subordinates"); ?>">My Team Shifts</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <small style="font-size: 12px"><?php echo count($mySubordinatesCount); ?> My Team</small><br>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?= base_url("shifts/my/subordinates"); ?>" class="site-btn">Show</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure>
                                                        <i class="fa fa-exchange <?php echo !empty($awaitingShiftRequests) && $awaitingShiftRequests > 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i>
                                                    </figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?= base_url("shifts/myTrade"); ?>">Shift Swap Requests</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <small style="font-size: 12px"><?php echo $awaitingShiftRequests ?? 0; ?> Pending Request(s) </small><br>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?= base_url("shifts/myTrade"); ?>" class="site-btn">Show Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (isPayrollOrPlus()) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure>
                                                            <i class="fa fa-exchange <?php echo !empty($awaitingShiftsApprovals) && $awaitingShiftsApprovals > 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i>
                                                        </figure>
                                                        <h2 class="post-title" style="margin: 2px 0;">
                                                            <a href="<?= base_url("settings/shifts/trade"); ?>">Shift Swap Approvals</a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <small style="font-size: 12px"><?php echo $awaitingShiftsApprovals ?? 0; ?> Pending Approval(s)</small><br>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="<?= base_url("settings/shifts/trade"); ?>" class="site-btn">Show Details</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>


                                    <?php } ?>

                                    <?php if (checkIfAppIsEnabled('timeoff') && $pto_user_access['dashboard'] == 1) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure>
                                                        <i class="fa fa-clock-o <?php echo !empty($pending_time_off) && $pending_time_off > 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i>
                                                    </figure>
                                                    <h2 class="post-title" style="margin: 2px 0;">
                                                        <a href="<?php echo $pto_user_access['url']; ?>">Time Off</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <span class="green">'<?php echo $TodaysRequests; ?>' Time Off Request(s) Today</span><br>
                                                        <small style="font-size: 12px"><?php echo $TotalEmployeeOffToday; ?> Employees Off Today</small><br>
                                                        <small style="font-size: 12px"><?php echo $TotalRequests; ?> Total Time Off Request(s)</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo $pto_user_access['url']; ?>" class="site-btn">Show</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (checkIfAppIsEnabled('timeoff')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-calendar-check-o" aria-hidden="true"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?= base_url('timeoff/lms'); ?>">My Time Off</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <span class="green" id="jsRemainingTime">0 hour(s)</span>
                                                        <span class="green">remaining</span><br>
                                                        <small id="jsConsumedTime" style="font-size: 12px">0 hour(s)</small>
                                                        <small style="font-size: 12px">consumed</small> <br />
                                                        <small id="jsTotalTimeoffs" style="font-size: 12px">0 Time-offs approved</small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <div class="col-lg-6 col-xs-12">
                                                            <a href="#" data-id="<?= $employee_sid; ?>" class="btn btn-success form-control jsBreakdownRequest">View Details</a>
                                                        </div>
                                                        <div class="col-lg-6 col-xs-12 pl0">
                                                            <a href="#" data-id="<?= $employee_sid; ?>" class="btn btn-success form-control jsCreateRequest">Create Request</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (checkIfAppIsEnabled('payroll')) { ?>
                                        <?php
                                        $isCompanyOnPayroll = isCompanyOnBoard($session['company_detail']['sid']);
                                        $isTermsAgreed = hasAcceptedPayrollTerms($session['company_detail']['sid']);
                                        ?>

                                        <?php if (!$isCompanyOnPayroll && isPayrollOrPlus(true)) { ?>
                                            <!-- Set up -->
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure>
                                                            <i class="fa fa-dollar" aria-hidden="true"></i>
                                                        </figure>
                                                        <h2 class="post-title">
                                                            <a href="#" class="jsCreatePartnerCompanyBtn" data-cid="<?= $this->session->userdata('logged_in')['company_detail']['sid']; ?>">Payroll</a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <small style="font-size: 12px"></small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="#" class="site-btn jsCreatePartnerCompanyBtn" data-cid="<?= $this->session->userdata('logged_in')['company_detail']['sid']; ?>">Set-up Payroll</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($isCompanyOnPayroll && !$isTermsAgreed) { ?>
                                            <!-- Service agreement -->
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure>
                                                            <i class="fa fa-dollar" aria-hidden="true"></i>
                                                        </figure>
                                                        <h2 class="post-title">
                                                            <a href="#" class="jsServiceAgreement" data-cid="<?= $this->session->userdata('logged_in')['company_detail']['sid']; ?>">Payroll</a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <small style="font-size: 12px"></small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="#" class="site-btn jsServiceAgreement" data-cid="<?= $this->session->userdata('logged_in')['company_detail']['sid']; ?>">Payroll Service Agreement</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($isCompanyOnPayroll && $isTermsAgreed && isPayrollOrPlus()) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure>
                                                            <i class="fa fa-calendar-o" aria-hidden="true"></i>
                                                        </figure>
                                                        <h2 class="post-title">
                                                            <a href="<?= base_url('payrolls/dashboard'); ?>">Payroll Dashboard</a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <small style="font-size: 12px"></small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="<?= base_url('payrolls/dashboard'); ?>" class="site-btn">View Dashboard</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($isCompanyOnPayroll && $isTermsAgreed) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure>
                                                            <i class="fa fa-dollar" aria-hidden="true"></i>
                                                        </figure>
                                                        <h2 class="post-title">
                                                            <a href="<?= base_url('payrolls/pay-stubs'); ?>">Pay Stubs</a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <small style="font-size: 12px"><?= $employeePayStubsCount; ?> pay stubs</small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="<?= base_url('payrolls/pay-stubs'); ?>" class="site-btn">View Pay stubs</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>



                                    <?php $this->load->view('v1/attendance/partials/dashboard_employer_tabs'); ?>

                                    <?php if (checkIfAppIsEnabled(EMPLOYEE_SURVEYS)) { ?>
                                        <!-- Employee Surveyss -->
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-pie-chart" aria-hidden="true"></i></figure>
                                                    <h2 class="post-title">
                                                        <a href="<?= base_url('employee/surveys/overview'); ?>">Employee Surveys</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <small style="font-size: 12px"></small>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?= base_url('employee/surveys/overview'); ?>" class="site-btn">Overview</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <!-- Incident Reporting -->

                                    <?php if (checkIfAppIsEnabled('incidents')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-exclamation-triangle <?= $incident_count > 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i>
                                                    </figure>
                                                    <h2 class="post-title">
                                                        <a href="<?= base_url('incident_reporting_system'); ?>">Incidents</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <span class="green"><?= $incident_count; ?> Pending</span><br>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?= base_url('incident_reporting_system'); ?>" class="site-btn">Report an Incident</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>


                                    <?php if ($isLMSModuleEnabled) : ?>
                                        <!--  -->
                                        <?php if ($session['employer_detail']['access_level_plus'] == 1) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure>
                                                            <i class="fa fa-file <?= $coursesInfo['expire_soon'] > 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i>
                                                        </figure>
                                                        <h2 class="post-title" style="margin: 2px 0;">
                                                            <a href="<?= base_url('lms/courses/company_courses'); ?>">
                                                                Course Management
                                                            </a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <span class="red">Due soon <?= $coursesInfo['expire_soon']; ?> course(s)</span><br>
                                                            <small style="font-size: 12px">Upcoming <?= $coursesInfo['upcoming']; ?> course(s)</small><br>
                                                            <small style="font-size: 12px">Expired <?= $coursesInfo['expired']; ?> course(s)</small><br>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="<?= base_url('lms/courses/company_courses'); ?>" class="site-btn">Manage</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!--  -->
                                        <?php if ($haveSubordinate == "yes") { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                <div class="dash-box">
                                                    <div class="dashboard-widget-box">
                                                        <figure>
                                                            <i class="fa fa-file" aria-hidden="true"></i>
                                                        </figure>
                                                        <h2 class="post-title" style="margin: 2px 0;">
                                                            <a href="<?= base_url('lms/courses/report'); ?>">
                                                                Team Courses
                                                            </a>
                                                        </h2>
                                                        <div class="count-box" style="font-size: 12px">
                                                            <small style="font-size: 12px"><?= $subordinateCount; ?> Employees</small>
                                                        </div>
                                                        <div class="button-panel">
                                                            <a href="<?= base_url('lms/courses/report'); ?>" class="site-btn">Manage</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!--  -->
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure><i class="fa fa-file <?= $pendingTrainings != 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i>
                                                    </figure>
                                                    <h2 class="post-title">
                                                        <a href="<?= base_url('lms/courses/my'); ?>">My Courses</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <span class="green"><?= $pendingTrainings ?? 0; ?> course(s) pending</span><br>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?= base_url('lms/courses/my'); ?>" class="site-btn">Show</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  -->
                                    <?php endif; ?>

                                    <?php if (checkIfAppIsEnabled('performanceevaluation')) { ?>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="dash-box">
                                                <div class="dashboard-widget-box">
                                                    <figure>
                                                        <i class="fa fa-file <?= $pendingVerificationPerformanceDocument != 0 ? 'start_animation' : ''; ?>" aria-hidden="true"></i>
                                                    </figure>
                                                    <h2 class="post-title">
                                                        <a href="<?php echo base_url('fillable/epe/verification/documents'); ?>">Employee Performance Evaluation</a>
                                                    </h2>
                                                    <div class="count-box" style="font-size: 12px">
                                                        <span class="green"><?= $pendingVerificationPerformanceDocument ?? 0; ?> pending</span><br>
                                                    </div>
                                                    <div class="button-panel">
                                                        <a href="<?php echo base_url('fillable/epe/verification/documents'); ?>" class="site-btn">Show</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <!-- Account Activity -->
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                        <div class="dash-box activity-box-wrp">
                                            <div class="activity-box">
                                                <h2>Account Activity</h2>
                                                <article class="activity-count">
                                                    <label>Jobs Active</label>
                                                    <div class="progress v3">
                                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                            <span class="number-of-count"><?php echo $jobCountActive; ?></span>
                                                        </div>
                                                    </div>
                                                </article>
                                                <article class="activity-count">
                                                    <label>Jobs posted</label>
                                                    <div class="progress v1">
                                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                            <span class="number-of-count"><?php echo $jobCount; ?></span>
                                                        </div>
                                                    </div>
                                                </article>
                                                <article class="activity-count">
                                                    <label>Job page visitors</label>
                                                    <div class="progress v2">
                                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                            <span class="number-of-count"><?php echo $visitors; ?></span>
                                                        </div>
                                                    </div>
                                                </article>
                                                <article class="activity-count">
                                                    <label>Applications received</label>
                                                    <div class="progress v3">
                                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                            <span class="number-of-count"><?php echo $applicants; ?></span>
                                                        </div>
                                                    </div>
                                                </article>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>

            <?php $this->load->view('onboarding/getting_started'); ?>

        <?php } ?>

        <style>
            .start_animation {
                animation-name: icon_alert;
                animation-duration: 0.8s;
                animation-iteration-count: infinite;
            }

            @keyframes icon_alert {
                75% {
                    color: #dc3545;
                }
            }
        </style>