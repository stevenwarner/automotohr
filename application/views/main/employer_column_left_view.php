<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('dashboard')) !== false) {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
        <?php if (check_access_permissions_for_view($security_details, 'add_listing')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('add_listing')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('add_listing') ?>">
                    <figure><i class="fa fa-pencil-square-o"></i></figure>Create A New Job
                </a>
            </li>
        <?php } ?> <!--1-->
        <?php if (check_access_permissions_for_view($security_details, 'market_place')) { ?>
            <li>
                <a <?php if (base_url(uri_string()) == site_url('market_place')) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('market_place') ?>">
                    <figure><i class="fa fa-shopping-cart"></i></figure>My Marketplace
                </a>
            </li>
        <?php } ?> <!--2-->
        <?php if (check_access_permissions_for_view($security_details, 'my_listings')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('my_listings')) !== false || strpos(base_url(uri_string()), site_url('edit_listing')) !== false || strpos(base_url(uri_string()), site_url('clone_listing')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('my_listings/active') ?>">
                    <figure><i class="fa fa-list-alt"></i></figure>My Jobs
                </a>
            </li>
        <?php } ?> <!--3-->
        <?php if (check_access_permissions_for_view($security_details, 'application_tracking')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('application_tracking_system')) !== false || strpos(base_url(uri_string()), site_url('manual_candidate')) !== false || strpos(base_url(uri_string()), site_url('archived_applicants')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('application_tracking_system/active/all/all/all/all/all/all/all/all/all') ?>">
                    <figure><i class="fa fa-line-chart"></i></figure>Application Tracking
                </a>
            </li>
        <?php } ?> <!--4-->
        <?php if (check_access_permissions_for_view($security_details, 'my_events')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('calendar/my_events')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('calendar/my_events') ?>">
                    <figure><i class="fa fa-calendar"></i></figure>Calendar / Events
                </a>
            </li>
        <?php } ?> <!--5-->
        <?php if (check_access_permissions_for_view($security_details, 'private_messages')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('private_messages')) !== false || strpos(base_url(uri_string()), site_url('inbox_message_detail')) !== false || strpos(base_url(uri_string()), site_url('outbox')) !== false || strpos(base_url(uri_string()), site_url('compose_message')) !== false || strpos(base_url(uri_string()), site_url('reply_message')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('private_messages') ?>">
                    <figure><i class="fa fa-envelope"></i></figure>Private Message
                </a>
            </li>
        <?php } ?> <!--6-->
        <?php
        $canAccessDocument = hasDocumentsAssigned($session['employer_detail']);
        ?>
        <?php if (check_access_permissions_for_view($security_details, 'employee_management') || $canAccessDocument) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('employee_management')) !== false || strpos(base_url(uri_string()), site_url('invite_colleagues')) !== false || strpos(base_url(uri_string()), site_url('send_offer_letter_documents')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url(); ?>employee_management">
                    <figure><i class="fa fa-users"></i></figure>Employee / Team Members
                </a>
            </li>
        <?php } ?> <!--7-->
        <?php if (check_company_ems_status($this->session->userdata('logged_in')['company_detail']['sid'])) { ?>
            <!--            --><?php //if(check_access_permissions_for_view($security_details, 'documents_management')) { 
                                ?>
            <!--                <li>-->
            <!--                    <a --><?php //if (base_url(uri_string()) == site_url('hr_documents_management')) {
                                            //                        echo 'class="active"';
                                            //                    } 
                                            ?><!-- href="--><?php //echo base_url('hr_documents_management') 
                                                            ?><!--">-->
            <!--                        <figure><i class="fa fa-file"></i></figure>-->
            <!--                        Document Management</a>-->
            <!--                </li>-->
            <!--            --><?php //} 
                                ?>

        <?php } ?>
        <?php //if(check_access_permissions_for_view($security_details, 'documents_management')) { 
        ?>
        <?php //if(false) { 
        ?>
        <!--                <li>-->
        <!--                    <a --><?php //if (strpos(base_url(uri_string()), site_url('documents_management')) !== false ) { echo 'class="active"'; } 
                                        ?>
        <!--                        href="--><?php //echo base_url('documents_management') 
                                                ?><!--">-->
        <!--                        <figure><i class="fa fa-file"></i></figure>Document Management-->
        <!--                    </a>-->
        <!--                </li>-->
        <?php //} 
        ?> <!--8-->
        <?php //if(check_access_permissions_for_view($security_details, 'resume_database')) { 
        ?>
        <!--                    <li>
                        <a <?php if (strpos(base_url(uri_string()), site_url('resume_database')) !== false) {
                                echo 'class="active"';
                            } ?>
                            href="<?php echo base_url("resume_database"); ?>">
                            <figure><i class="fa fa-database"></i></figure>Resume Database
                        </a>
                    </li>-->
        <?php  //} 
        ?> <!--9-->
        <?php if (check_access_permissions_for_view($security_details, 'my_settings')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('my_settings')) !== false || strpos(base_url(uri_string()), site_url('order_history')) !== false || strpos(base_url(uri_string()), site_url('order_detail')) !== false || strpos(base_url(uri_string()), site_url('job_products_report')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('my_settings') ?>">
                    <figure><i class="fa fa-sliders"></i></figure>Settings
                </a>
            </li>
        <?php } ?> <!--10-->
        <?php if (check_access_permissions_for_view($security_details, 'screening_questionnaires')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('screening_questionnaires')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('screening_questionnaires') ?>">
                    <figure><i class="fa fa-file-text-o"></i></figure>Candidate Questionnaires
                </a>
            </li>
        <?php } ?> <!--11-->
        <?php if (check_access_permissions_for_view($security_details, 'video_interview_system')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('video_interview_system')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url('video_interview_system') ?>">
                    <figure><i class="fa fa-video-camera"></i></figure>Video Interview System
                </a>
            </li>
        <?php } ?>
        <?php if (check_access_permissions_for_view($security_details, 'interview_questionnaire')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('interview_questionnaire')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url("interview_questionnaire"); ?>">
                    <figure><i class="fa fa-file-text-o"></i></figure>Interview Questionnaires
                </a>
            </li>
        <?php } ?> <!--12-->
        <?php if (check_access_permissions_for_view($security_details, 'background_check')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('background_check')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url("accurate_background"); ?>">
                    <figure><i class="fa fa-file"></i></figure>Background Checks Report
                </a>
            </li>
        <?php  } ?><!--13-->

        <?php if (check_access_permissions_for_view($security_details, 'referral_network')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('referral_network')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url(); ?>referral_network">
                    <figure><i class="fa fa-users"></i></figure>Referral Network
                </a>
            </li>
        <?php } ?>
        <!--14-->

        <!--
        <?php /*if(check_access_permissions_for_view($security_details, 'organizational_hierarchy')) { */ ?>
        <li>
            <a <?php /*if (strpos(base_url(uri_string()), site_url('organizational_hierarchy')) !== false) { echo 'class="active"'; } */ ?>
                href="<?php /*echo base_url("organizational_hierarchy"); */ ?>">
                <figure><i class="fa fa-users"></i></figure>Organizational Hierarchy
            </a>
        </li>
        --><?php /* } */ ?><!--15-->

        <?php if (check_access_permissions_for_view($security_details, 'attendance_management')) { ?>
            <?php $data['session'] = $this->session->userdata('logged_in'); ?>
            <?php $company_sid = $data["session"]["company_detail"]["sid"]; ?>
            <?php //if(in_array($company_sid, explode(',', TEST_COMPANIES))) { 
            ?>
            <?php if (in_array($company_sid, array())) { ?>
                <li>
                    <a <?php if (strpos(base_url(uri_string()), site_url('attendance')) !== false) {
                            echo 'class="active"';
                        } ?> href="<?php echo base_url("attendance"); ?>">
                        <figure><i class="fa fa-calendar"></i></figure>Time & Attendance
                        <span class="beta-label">beta</span>
                    </a>
                </li>
            <?php  } ?>
        <?php  } ?><!--16-->

        <?php if (check_access_permissions_for_view($security_details, 'support_tickets')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('support_tickets')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url("support_tickets"); ?>">
                    <figure><i class="fa fa-tags"></i></figure>Support Tickets
                </a>
            </li>
        <?php  } ?>
        <!--17-->


        <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] && check_access_permissions_for_view($security_details, 'ems_portal')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('manage_ems')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url("manage_ems"); ?>">
                    <figure><i class="fa fa-file-text-o"></i></figure>Employee Management System
                </a>
            </li>
        <?php  } ?>
        <!--            --><?php //$data['session'] = $this->session->userdata('logged_in'); 
                            ?>
        <!--            --><?php //$company_sid = $data["session"]["company_detail"]["sid"]; 
                            ?>
        <!--            --><?php //if(in_array($company_sid, explode(',', TEST_COMPANIES))) { 
                            ?>
        <!--            <li>-->
        <!--                <a --><?php //if (strpos(base_url(uri_string()), site_url('learning_center')) !== false) { echo 'class="active"'; } 
                                    ?>
        <!--                    href="--><?php //echo base_url("learning_center"); 
                                            ?><!--">-->
        <!--                    <figure><i class="fa fa-book"></i></figure>Learning Center-->
        <!--                    <span class="beta-label">beta</span>-->
        <!--                </a>-->
        <!--            </li>-->
        <!--            --><?php // } 
                            ?>

        <li>
            <a <?php echo $this->uri->segment(1) == 'authorized_document' || $this->uri->segment(1) == 'view_assigned_authorized_document' ? 'class="active"' : ''; ?> href="<?php echo base_url("authorized_document"); ?>">
                <figure><i class="fa fa-clipboard"></i></figure>Assigned Documents
            </a>
        </li>

        <?php if (check_resource_permission($session['company_detail']['sid']) && check_access_permissions_for_view($security_details, 'resource_center_panel')) { ?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('resource_center')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url("resource_center"); ?>">
                    <figure><i class="fa fa-files-o"></i></figure>Resource Center
                </a>
            </li>
        <?php  } ?>


        <?php
        $comply_status = $data["session"]["company_detail"]["complynet_status"];
        $employee_comply_status = $data["session"]["employer_detail"]["complynet_status"];
        ?>
        <?php if (check_access_permissions_for_view($security_details, 'complynet') && $comply_status && $employee_comply_status) { ?>
            <?php $complyNetLink = getComplyNetLink($this->session->userdata('logged_in')['company_detail']['sid'], $this->session->userdata('logged_in')['employer_detail']['sid']); ?>
            <?php if ($complyNetLink) {
            ?>

                <li>
                    <a href="<?php echo base_url('cn/redirect'); ?>" target="_blank">
                        <figure><i class="fa fa-book"></i></figure>ComplyNet
                    </a>
                </li>
        <?php
            }
        }
        ?>



        <!--        --><?php // $load_view = check_blue_panel_status(false, 'self');
                        ?>
        <!--        --><?php //if($this->session->userdata('logged_in')['company_detail']['ems_status']){
                        ?>
        <!--            <li>-->
        <!--                <a --><?php //if (strpos(base_url(uri_string()), site_url('announcements')) !== false || strpos(base_url(uri_string()), site_url('list_announcements')) !== false) { echo 'class="active"'; } 
                                    ?>
        <!--                        href="--><?php //echo $load_view ? base_url("announcements") : base_url('list_announcements'); 
                                                ?><!--">-->
        <!--                    <figure><i class="fa fa-bullhorn"></i></figure>Announcements-->
        <!--                </a>-->
        <!--            </li>-->
        <!--        --><?php // } 
                        ?>

        <!--        --><?php //if($this->session->userdata('logged_in')['company_detail']['ems_status']){
                        ?>
        <!--           <li>-->
        <!--                <a --><?php //if (strpos(base_url(uri_string()), site_url('safety_sheets')) !== false) { echo 'class="active"'; } 
                                    ?>
        <!--                        href="--><?php //echo base_url("safety_sheets/manage_safety_sheets"); 
                                                ?><!--">-->
        <!--                    <figure><i class="fa fa-files-o"></i></figure>Manage Safety Sheets-->
        <!--                </a>-->
        <!--            </li>-->
        <!--        --><?php // } 
                        ?>

        <?php /*if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('incident_reported')) !== false) { echo 'class="active"'; } ?>
                        href="<?php echo base_url("incident_reported"); ?>">
                    <figure><i class="fa fa-files-o"></i></figure>Incident Reported
                </a>
            </li>
        <?php  } */ ?>

        <!--        <li>-->
        <!--            <a --><?php //if (strpos(base_url(uri_string()), site_url('incident_reporting_system/assigned_incidents')) !== false || strpos(base_url(uri_string()), site_url('incident_reporting_system/view_single_assign')) !== false) { echo 'class="active"'; } 
                                ?>
        <!--                href="--><?php //echo base_url("incident_reporting_system/assigned_incidents"); 
                                        ?><!--">-->
        <!--                <figure><i class="fa fa-ambulance"></i></figure>Assigned Incidents-->
        <!--            </a>-->
        <!--        </li>-->
        <!---->
        <!--        <li>-->
        <!--            <a --><?php //if (strpos(base_url(uri_string()), site_url('incident_reporting_system/')) !== false || strpos(base_url(uri_string()), site_url('incident_reporting_system/list_incidents')) !== false || strpos(base_url(uri_string()), site_url('incident_reporting_system/view_incident')) !== false || strpos(base_url(uri_string()), site_url('incident_reporting_system/view_general_guide')) !== false || strpos(base_url(uri_string()), site_url('incident_reporting_system/view_guide')) !== false || strpos(base_url(uri_string()), site_url('incident_reporting_system/report')) !== false) { echo 'class="active"'; } 
                                ?>
        <!--                href="--><?php //echo base_url("incident_reporting_system/index"); 
                                        ?><!--">-->
        <!--                <figure><i class="fa fa-tasks"></i></figure>Report An Incident-->
        <!--            </a>-->
        <!--        </li>-->
        <?php if (check_access_permissions_for_view($security_details, 'govt_user')) { ?>
            <li>
                <a<?php if (strpos(base_url(uri_string()), site_url('govt_user/')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url("govt_user"); ?>">
                    <figure><i class="fa fa-user"></i></figure>Government Agent Credentials
                    </a>
            </li>
        <?php  } ?>


        <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(SCHEDULE_MODULE)) { ?>

            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('settings/shifts/manage')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?php echo base_url("settings/shifts/manage"); ?>">
                    <figure><i class="fa fa-calendar"></i></figure>Manage Shifts
                </a>
            </li>
        <?php  } ?>




        <?php if (checkIfAppIsEnabled(PAYROLL)) { ?>
            <?php
            $isCompanyOnPayroll = isCompanyOnBoard($session['company_detail']['sid']);
            $isTermsAgreed = hasAcceptedPayrollTerms($session['company_detail']['sid']);
            ?>
            <?php if (!$isCompanyOnPayroll && isPayrollOrPlus(true)) { ?>
                <li><a href="javascript:void(0)" class="jsCreatePartnerCompanyBtn" data-cid="<?= $session['company_detail']['sid']; ?>">
                        <figure><i class="fa fa-cogs"></i></figure>Set-up Payroll
                    </a></li>
            <?php } ?>
            <?php if ($isCompanyOnPayroll && !$isTermsAgreed) { ?>
                <li><a href="javascript:void(0)" class="jsServiceAgreement" data-cid="<?= $session['company_detail']['sid']; ?>">
                        <figure><i class="fa fa-sign"></i></figure>Payroll Service Agreement
                    </a></li>
            <?php } ?>
            <?php if ($isCompanyOnPayroll && $isTermsAgreed) { ?>
                <li><a href="<?= base_url('payrolls/dashboard'); ?>">
                        <figure><i class="fa fa-cogs"></i></figure>Payroll Dashboard
                    </a></li>
            <?php } ?>
        <?php } ?>
        <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(MODULE_ATTENDANCE)) { ?>
            <!-- Attendance module settings -->
            <li>
                <a href="<?= base_url("attendance/dashboard"); ?>">
                    <figure><i class="fa fa-calendar"></i></figure>
                    Attendance Management
                </a>
            </li>
        <?php } ?>
        <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(MODULE_ATTENDANCE)) { ?>
            <!-- Attendance module settings -->
            <li>
                <a <?php if (strpos(base_url(uri_string()), site_url('attendance/settings')) !== false) {
                        echo 'class="active"';
                    } ?> href="<?= base_url("attendance/settings"); ?>">
                    <figure><i class="fa fa-cogs"></i></figure>
                    Attendance Settings
                </a>
            </li>
        <?php } ?>

    </ul>
</div>
<div class="dash-box service-contacts hidden-xs">
    <div class="admin-info">
        <h2>Need help with your AutomotoHR Platform? <br />Contact one of our Talent Network Partners at</h2>
        <div class="profile-pic-area">
            <div class="form-col-100">
                <ul class="admin-contact-info">
                    <li>
                        <label>Sales Support</label>
                        <?php $data['session'] = $this->session->userdata('logged_in'); ?>
                        <?php $company_sid = $data["session"]["company_detail"]["sid"]; ?>
                        <?php $company_info = get_contact_info($company_sid); ?>
                        <span><i class="fa fa-phone"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_phone_no'] : TALENT_NETWORK_SALE_CONTACTNO; ?></span>
                        <span><a href="mailto:<?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope-o"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?></a></span>
                    </li>
                    <li>
                        <label>Technical Support</label>
                        <span><i class="fa fa-phone"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_phone_no'] : TALENT_NETWORK_SUPPORT_CONTACTNO; ?></span>
                        <span><a href="mailto:<?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i class="fa fa-envelope-o"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- -->
<?php
$getCompanyHelpboxInfo = get_company_helpbox_info($company_sid);
if ($getCompanyHelpboxInfo[0]['box_status'] == 1) {
?>
    <div class="dash-box service-contacts hidden-xs">
        <div class="admin-info">
            <h2><?php echo $getCompanyHelpboxInfo[0]['box_title']; ?></h2>
            <div class="profile-pic-area">
                <div class="form-col-100">
                    <ul class="admin-contact-info">
                        <li>
                            <label>Support</label>
                            <?php if ($getCompanyHelpboxInfo[0]['box_support_phone_number']) { ?>
                                <span><i class="fa fa-phone"></i> <?php echo $getCompanyHelpboxInfo[0]['box_support_phone_number']; ?></span><br>
                            <?php } ?>
                            <span>
                                <button class="btn btn-orange jsCompanyHelpBoxBtn">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;<?= $getCompanyHelpboxInfo[0]['button_text']; ?>
                                </button>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('company_help_box_script'); ?>

<?php } ?>