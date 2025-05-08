<?php
$canAccessDocument = hasDocumentsAssigned($session['employer_detail']);
$canEMSPermission = hasEMSPermission($session['employer_detail']);
?>
<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <aside class="side-bar">
        <a href="<?php echo base_url('employee_management') ?>">
            <header class="sidebar-header">
                <h1>Employee / Team Members</h1>
            </header>
        </a>
        <div class="next-applicant">
            <ul>
                <li class="previous-btn"><a href="<?php echo $prev_app ?>"><i aria-hidden="true" class="fa fa-chevron-left"></i>Prev</a></li>
                <li class="next-btn"><a href="<?php echo $next_app ?>">next<i aria-hidden="true" class="fa fa-chevron-right"></i></a></li>
            </ul>
        </div>
        <div class="widget-wrp">

            <?php if (strpos(current_url(), 'employee_profile')) { ?>
                <div class="hr-widget">
                    <div class="info-area">
                        <h2>Employee Reviews</h2>
                    </div>
                    <div class="start-rating">
                        <form action="<?php echo base_url('applicant_profile/save_rating'); ?>" method="post">
                            <input type="hidden" name="applicant_job_sid" value="<?= $id ?>">
                            <input type="hidden" name="applicant_email" value="<?= $email ?>">
                            <input type="hidden" name="users_type" value="employee">
                            <div class="text-center applicant-rating-container">
                                <input readonly="readonly" id="input-21b" <?php if (!empty($applicant_rating)) { ?> value="<?php echo $applicant_rating['rating']; ?>" <?php } ?> type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                            </div>
                            <div class="rating-comment">
                                <input type="button" id="trigger-review" value="Review Comments" class="btn btn-success" style="width: 100%; margin-top: 5px;">
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <div class="hr-widget">
                <div class="applicant-status">
                    <div class="info-area">
                        <h2>Status</h2>
                        <ul>
                            <li>
                                <?php if ($employer['active']) { ?>
                                    <div class="label-wrapper-outer">
                                        <div class="selected placed"><?= empty($employee_terminate_status) ? 'Active Employee' : $employee_terminate_status; ?></div>
                                    </div>
                                <?php } else { ?>
                                    <div class="label-wrapper-outer">
                                        <div class="selected interviewing"><?= empty($employee_terminate_status) ? 'Onboarding or Deactivated Employee' : $employee_terminate_status; ?></div>
                                    </div>
                                <?php } ?>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if ($canEMSPermission) { ?>
                <?php $function_names = array('employee_profile', 'employee_login_credentials', 'background_check', 'drug_test', 'reference_checks'); ?>
                <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                    <div class="hr-widget">
                        <div class="browse-attachments">
                            <ul>
                                <?php if (isPayrollOrPlus()) {  ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_profile')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-user"></i>
                                            </span>
                                            <h4>Employee Profile</h4>
                                            <a href="<?php echo base_url('employee_profile') . '/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_login_credentials')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-lock"></i>
                                            </span>
                                            <h4>Login Credentials</h4>
                                            <a href="<?php if (!$employer['is_executive_admin']) {
                                                            echo base_url('employee_login_credentials') . '/' . $employer["sid"];
                                                        } else {
                                                            echo '#';
                                                        } ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'background_check')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-check"></i>
                                            </span>
                                            <h4>Background Check</h4>
                                            <?php
                                            $_SESSION['applicant_id'] = $employer['sid'];
                                            $_SESSION['applicant_type'] = 'employee_profile';
                                            if ($company_background_check == 1) {
                                            ?>
                                                <a href="<?php echo base_url('background_check') . '/employee/' . $employer['sid']; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <?php
                                            } else {
                                            ?>
                                                <a href="<?php echo base_url('background_check/activate') ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <?php } ?>

                                            <!-- Light Bulb Code - Start -->
                                            <?php $background_check_count = count_accurate_background_orders($employer['sid']); ?>
                                            <?php if (intval($background_check_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Background Check Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                <?php } else { ?>:
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Background Check Not Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'drug_test')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-medkit"></i>
                                            </span>
                                            <h4>Drug Testing</h4>
                                            <a href="<?php echo base_url('drug_test') . '/employee/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>

                                            <!-- Light Bulb Code - Start -->
                                            <?php $background_check_count = count_accurate_background_orders($employer['sid'], 'drug-testing'); ?>
                                            <?php if (intval($background_check_count) > 0) { ?>F
                                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Drug Test Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                        <?php } else { ?>
                                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Drug Test Not Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                        <?php } ?>
                                        <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'reference_checks')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-link"></i>
                                            </span>
                                            <h4>Reference Check</h4>
                                            <a href="<?php echo base_url('reference_checks/employee') . '/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <!-- Light Bulb Code - Start -->
                                            <?php $references_count = count_references_records($employer['sid']); ?>
                                            <?php if (intval($references_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has References Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No References Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] == 1) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-envelope"></i>
                                            </span>
                                            <h4>Send Offer Letter / Pay Plans</h4>
                                            <a href="<?php echo base_url('onboarding/send_offer_letter/employee') . '/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <?php $offer_letter_ststus = count_offer_letter('employee', $employer['sid']); ?>
                                            <!-- Light Bulb Code - Start -->
                                            <?php if ($offer_letter_ststus == 'sign' || $offer_letter_ststus == 'sent') { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else if ($offer_letter_ststus == 'not_sent') { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status'] == 1) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-envelope"></i>
                                            </span>
                                            <h4>View Offer Letter / Pay Plans</h4>
                                            <a href="<?php echo base_url('onboarding/view_offer_letter/employee') . '/' . $employer['sid']; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <?php $offer_letter_ststus = count_offer_letter('employee', $employer['sid']); ?>
                                            <!-- Light Bulb Code - Start -->
                                            <?php if ($offer_letter_ststus == 'sign') { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else if ($offer_letter_ststus == 'sent' || $offer_letter_ststus == 'not_sent') { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-star"></i>
                                            </span>
                                            <h4>Setup Employee Panel</h4>
                                            <a href="<?php echo base_url('onboarding/setup/employee') . '/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <!-- Light Bulb Code - Start -->
                                            <?php $employee_panel_config_count = count_onboarding_panel_records('employee', $employer['sid']); ?>
                                            <?php if (intval($employee_panel_config_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Employee Panel Set-up" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Employee Panel Not Set-up" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                                <?php if (checkIfAppIsEnabled('timeoff') && ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'])) { ?>
                                    <li>
                                        <span class="left-addon">
                                            <i aria-hidden="true" class="fa fa-clock-o"></i>
                                        </span>
                                        <h4>Time Off</h4>
                                        <a href="<?php echo base_url('timeoff/create_employee') . '/' . $employer["sid"]; ?>">Create / View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>
                                <?php } ?>


                                <?php if (isPayrollOrPlus() && isEmployeeOnPayroll($employer["sid"])) { ?>
                                    <li>
                                        <span class="left-addon">
                                            <i aria-hidden="true" class="fa fa-dashboard"></i>
                                        </span>
                                        <h4>Payroll dashboard</h4>
                                        <a href="<?php echo base_url('payrolls/dashboard/employee/' . $employer["sid"]); ?>">Manage<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>
                                <?php } ?>

                                <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                    <li>
                                        <span class="left-addon">
                                            <i aria-hidden="true" class="fa fa-pencil-square-o"></i>
                                        </span>
                                        <h4>Performance Management</h4>
                                        <a href="<?php echo base_url('performance-management/employee/reviews/' . ($employer["sid"]) . ''); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>
                                <?php } ?>


                                <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                                    <li>
                                        <span class="left-addon">
                                            <i aria-hidden="true" class="fa fa-bullseye"></i>
                                        </span>
                                        <h4>Goals</h4>
                                        <a href="<?php echo base_url('performance-management/employee/goals/' . ($employer["sid"]) . ''); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>
                                <?php } ?>

                                <?php                                 
                                if (checkIfAppIsEnabled(MODULE_LMS)) { ?>
                                    <?php if (isPayrollOrPlus() || isLMSManagerDepartmentAndTeams($employer["sid"]) ) {  ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-file"></i>
                                            </span>
                                            <h4>LMS</h4>
                                            <a href="<?php echo base_url('lms/subordinate/dashboard/' . ($employer["sid"]) . ''); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                                <?php if (checkIfAnyIncidentIssueAssigned($employer["sid"])) { ?>
                                    <li>
                                        <span class="left-addon">
                                            <i aria-hidden="true" class="fa fa-file"></i>
                                        </span>
                                        <h4>Compliance Safety Reporting</h4>
                                        <!-- <a href="<?php echo base_url('compliance_safety_reporting/employee_dashboard/'.$employer["sid"]); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a> -->
                                        <a href="<?php echo base_url('compliance_safety_reporting/employee_dashboard/'.$employer["sid"]); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                <?php } ?>


                <?php $function_names = array('full_employment_application', 'employee_emergency_contacts', 'employee_occupational_license_info', 'employee_drivers_license_info', 'employee_equipment_info', 'employee_dependants', 'employee_hr_documents'); ?>
                <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                    <div class="hr-widget">
                        <div class="browse-attachments">
                            <ul>
                                <?php if (isPayrollOrPlus()) {  ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'send_employee_full_employment_application')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-file-text"></i>
                                            </span>
                                            <h4>Full Employment Application</h4>
                                            <?php $full_emp_form_status = get_full_emp_app_form_status($employer['sid'], 'employee'); ?>
                                            <form id="form_send_full_employment_application" enctype="multipart/form-data" method="post" action="<?php echo base_url('form_full_employment_application/send_form'); ?>">
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $employer['parent_sid']; ?>" />
                                                <input type="hidden" id="user_type" name="user_type" value="employee" />
                                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $employer['sid']; ?>" />
                                            </form>
                                            <div <?= $full_emp_form_status == 'sent' || $full_emp_form_status == 'signed' ? 'class="view-btn"' : '' ?>>
                                                <a href="javascript:void(0);" onclick="fSendFullEmploymentForm();"><?= $full_emp_form_status == 'sent' || $full_emp_form_status == 'signed' ? 'Resend' : 'Send'; ?><i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                                <!-- Light Bulb Code - Start -->

                                                <?php if ($full_emp_form_status == 'sent' || $full_emp_form_status == 'signed') { ?>
                                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                <?php } else { ?>
                                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                <?php } ?>
                                            </div>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'view_employee_full_employment_application')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-file-text"></i>
                                            </span>
                                            <h4>Full Employment Application</h4>
                                            <a href="<?php echo base_url('full_employment_application') . '/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>

                                            <!-- --><?php //$full_employment_application_status = get_full_employment_application_status($employer['sid'], 'employee'); 
                                                    ?>
                                            <?php $full_employment_application_status = get_full_emp_app_form_status($employer['sid'], 'employee'); ?>

                                            <?php if ($full_employment_application_status == 'signed') { ?>
                                                <img style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" class="img-responsive pull-right" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" class="img-responsive pull-right" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                    <?php $w4_form = get_fillable_info('w4', 'employee', $employer['sid']);
                                    if (sizeof($w4_form) > 0) { ?>
                                        <!--                            <li>-->
                                        <!--                            <span class="left-addon">-->
                                        <!--                                <i aria-hidden="true" class="fa fa-file-text"></i>-->
                                        <!--                            </span>-->
                                        <!--                                <h4>Fillable W4 Form</h4>-->
                                        <!--                                <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
                                        <!-- Light Bulb Code - Start -->
                                        <!--                                --><?php //if($w4_form['user_consent']) { 
                                                                                ?>
                                        <!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/on.gif'); 
                                                                                                                                                                                                                                                                        ?><!--">-->
                                        <!--                                --><?php //} else { 
                                                                                ?>
                                        <!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/off.gif'); 
                                                                                                                                                                                                                                                                        ?><!--">-->
                                        <!--                                --><?php //} 
                                                                                ?>
                                        <!-- Light Bulb Code - End -->
                                        <!--                            </li>-->
                                    <?php } ?>
                                    <?php $w9_form = get_fillable_info('w9', 'employee', $employer['sid']);
                                    if (sizeof($w9_form) > 0) { ?>
                                        <!--                            <li>-->
                                        <!--                            <span class="left-addon">-->
                                        <!--                                <i aria-hidden="true" class="fa fa-file-text"></i>-->
                                        <!--                            </span>-->
                                        <!--                                <h4>Fillable W9 Form</h4>-->
                                        <!--                                <a data-toggle="modal" data-target="#w9_modal" href="javascript:void(0);">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
                                        <!-- Light Bulb Code - Start -->
                                        <!--                                --><?php //if($w9_form['user_consent']) { 
                                                                                ?>
                                        <!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/on.gif'); 
                                                                                                                                                                                                                                                                        ?><!--">-->
                                        <!--                                --><?php //} else { 
                                                                                ?>
                                        <!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/off.gif'); 
                                                                                                                                                                                                                                                                        ?><!--">-->
                                        <!--                                --><?php //} 
                                                                                ?>
                                        <!-- Light Bulb Code - End -->
                                        <!--                            </li>-->
                                    <?php } ?>
                                    <?php $i9_form = get_fillable_info('i9', 'employee', $employer['sid']);
                                    if (sizeof($i9_form) > 0) { ?>
                                        <!--                            <li>-->
                                        <!--                            <span class="left-addon">-->
                                        <!--                                <i aria-hidden="true" class="fa fa-file-text"></i>-->
                                        <!--                            </span>-->
                                        <!--                                <h4>Fillable I9 Form</h4>-->
                                        <!--                                --><?php //if($i9_form['employer_flag']){ 
                                                                                ?>
                                        <!--                                    <a data-toggle="modal" data-target="#i9_modal" href="javascript:void(0);">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
                                        <!--                                --><?php //} else{ 
                                                                                ?>
                                        <!--                                    <a href="--><?php //echo base_url('form_i9/employee') . '/' . $employer['sid']; 
                                                                                            ?><!--">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
                                        <!--                                --><?php //} 
                                                                                ?>
                                        <!-- Light Bulb Code - Start -->
                                        <!--                                --><?php //if($i9_form['user_consent']) { 
                                                                                ?>
                                        <!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/on.gif'); 
                                                                                                                                                                                                                                                                        ?><!--">-->
                                        <!--                                --><?php //} else { 
                                                                                ?>
                                        <!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/off.gif'); 
                                                                                                                                                                                                                                                                        ?><!--">-->
                                        <!--                                --><?php //} 
                                                                                ?>
                                        <!-- Light Bulb Code - End -->
                                        <!--                            </li>-->
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_emergency_contacts')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-ambulance"></i>
                                            </span>
                                            <h4>Emergency Contacts</h4>
                                            <a href="<?php echo base_url('emergency_contacts') . '/employee/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <!-- Light Bulb Code - Start -->
                                            <?php $emergency_contacts_count = count_emergency_contacts($employer['sid']);  ?>
                                            <?php if (intval($emergency_contacts_count > 0)) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Emergency Contacts Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>" alt="">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Emergency Contacts Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>" alt="">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_occupational_license_info')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-industry"></i>
                                            </span>
                                            <h4>Occupational License Info</h4>
                                            <a href="<?php echo base_url('occupational_license_info') . '/employee/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <!-- Light Bulb Code - Start -->
                                            <?php $occ_licenses_count = count_licenses($employer['sid'], 'occupational'); ?>
                                            <?php if (intval($occ_licenses_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Occupational License Saved" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Occupational License Information" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_drivers_license_info')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-automobile"></i>
                                            </span>
                                            <h4>Drivers License Info</h4>
                                            <a href="<?php echo base_url('drivers_license_info') . '/employee/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <!-- Light Bulb Code - Start -->
                                            <?php $drv_licenses_count = count_licenses($employer['sid'], 'drivers'); ?>
                                            <?php if (intval($drv_licenses_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Drivers License Saved" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Drivers License Information" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_equipment_info')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-laptop"></i>
                                            </span>
                                            <h4>Equipment Info</h4>
                                            <a href="<?php echo base_url('equipment_info') . '/employee/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <!-- Light Bulb Code - Start -->
                                            <?php $equipments_count = count_equipments($employer['sid']); ?>
                                            <?php if (intval($equipments_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Equipment Assigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Equipment Assigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>

                                    <!--<li>
                                        <h4>W4 form and Tax withholding</h4>
                                        <a href="javascript:;">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                        </li>-->
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_i9form')) { ?>
                                        <!--<li>
                                        <span class="left-addon">
                                            <i aria-hidden="true" class="fa fa-file-text"></i>
                                        </span>
                                        <h4>i9 Employment Verification</h4>
                                        <a href="<?php echo base_url('i9form') . '/employee/' . $employer['sid']; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>-->
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_dependants')) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-child"></i>
                                            </span>
                                            <h4>Dependents</h4>
                                            <a href="<?php echo base_url('dependants') . '/employee/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <!-- Light Bulb Code - Start -->
                                            <?php $dependant_count = count_dependants($employer['sid']); ?>
                                            <?php if (intval($dependant_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Dependents" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Dependents Information Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>
                                    <!--
                                    <li>
                                        <h4>Benefit Elections</h4>
                                        <a href="javascript:;">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>

                                    <li>
                                        <h4>Payroll</h4>
                                        <a href="javascript:;">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>
                                    -->
                                    <?php if (check_access_permissions_for_view($security_details, 'employee_hr_documents') && !$this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                        <li>
                                            <span class="left-addon">
                                                <i aria-hidden="true" class="fa fa-file-text"></i>
                                            </span>
                                            <h4>HR Documents</h4>
                                            <a href="<?php echo base_url('my_hr_documents') . '/employee/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <!-- Light Bulb Code - Start -->
                                            <?php $show_hr_documents_bulb = show_hr_documents_light_bulb($employer["sid"]); ?>
                                            <?php if ($show_hr_documents_bulb == true) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="All Documents Acknowledged" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Documents Acknowledgment Pending" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->
                                        </li>
                                    <?php } ?>

                                    <?php if (check_access_permissions_for_view($security_details, 'direct_deposit_info')) { ?>
                                        <li>
                                            <span class="left-addon"><i aria-hidden="true" class="fa fa-bank"></i></span>
                                            <h4>Direct Deposit Information</h4>
                                            <a href="<?php echo base_url('direct_deposit') . '/employee/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>

                                            <!-- Light Bulb Code - Start -->
                                            <?php $direct_deposit_count = count_direct_deposit($employer["sid"]); ?>
                                            <?php if (intval($direct_deposit_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Direct Deposit Info Added" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Direct Deposit Info Not Added" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                            <!-- Light Bulb Code - End -->

                                        </li>
                                    <?php } ?>
                                    <?php if (checkIfAppIsEnabled(MODULE_LMS)) { ?>
                                        <li>
                                            <span class="left-addon"><i aria-hidden="true" class="fa fa-bank"></i></span>
                                            <h4>Learning Center</h4>
                                            <a href="<?php echo base_url('lms/courses/my_lms_dashboard'); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                    <?php } else if (check_access_permissions_for_view($security_details, 'emp_learning_center')) { ?>
                                        <li>
                                            <span class="left-addon"><i aria-hidden="true" class="fa fa-bank"></i></span>
                                            <h4>Learning Center</h4>

                                            <a href="<?php echo base_url('learning_center/my_learning_center/') . '/' . $employer["sid"]; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <?php $learning_center_count = count_learning_center($employer["sid"], $employer['parent_sid'], 'employee'); ?>
                                            <?php if (intval($learning_center_count) > 0) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Learning Center" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Learning Center" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                    <?php $incident = $this->session->userdata('incident_config');
                                    if ($incident > 0) { ?>
                                        <!--                            <li>-->
                                        <!--                                <span class="left-addon"><i aria-hidden="true" class="fa fa-newspaper-o"></i></span>-->
                                        <!--                                <h4>Report An incident</h4>-->
                                        <!--                                <a href="--><?php //echo base_url('incident_reporting_system/'); 
                                                                                        ?><!--">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
                                        <!--                            </li>-->

                                        <!--                            <li>-->
                                        <!--                                <span class="left-addon"><i aria-hidden="true" class="fa fa-bank"></i></span>-->
                                        <!--                                <h4>Assigned Incident</h4>-->
                                        <!--                                <a href="--><?php //echo base_url('incident_reporting_system/assigned_incidents/'); 
                                                                                        ?><!--">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
                                        <!--                            </li>-->
                                    <?php } ?>
                                    <?php if ($this->session->userdata('logged_in')['portal_detail']['eeo_form_profile_status']) { ?>
                                        <li>
                                            <span class="left-addon"><i aria-hidden="true" class="fa fa-file-text"></i></span>
                                            <h4>EEOC</h4>
                                            <a href="<?php echo base_url('EEOC/employee/' . $employer['sid']); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                            <?php $EEOC = CheckUserEEOCStatus('employee', $employer['sid']); ?>
                                            <?php if ($EEOC) { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="EEOC form completed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                            <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="EEOC form not completed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if (check_access_permissions_for_view($security_details, 'emp_documents') && $this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                                    <li>
                                        <span class="left-addon"><i aria-hidden="true" class="fa fa-file-text"></i></span>
                                        <h4>Documents</h4>
                                        <a href="<?php echo base_url('hr_documents_management/documents_assignment/employee/' . $employer['sid']); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>
                                <?php } ?>
                                <?php if (isPayrollOrPlus()) {  ?>
                                    <!-- Reminded Emails Send -->
                                    <li style="cursor: pointer;">
                                        <span class="left-addon"><i aria-hidden="true" class="fa fa-envelope"></i></span>
                                        <h4>Send An Email Reminder</h4>
                                        <a href="javascript:void(0)" title="Send An Email Reminder" id="JsSendReminderEmail">Send <i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a><br>
                                        <a href="javascript:void(0)" title="View Email Reminder History" id="JsSendReminderEmailHistory">View <i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isPayrollOrPlus()) {  ?>

                    <div class="hr-widget" id="attachment_view">
                        <div class="attachment-header">
                            <div class="form-title-section">
                                <h4>Attachments</h4>
                                <div class="form-btns">
                                    <input type="button" value="edit" id="attachment_edit_button">
                                </div>
                            </div>
                            <div class="file-container">
                                <a id="show_employee_resume_btn" href="javascript:;">
                                    <article>
                                        <figure><img src="<?php echo base_url() ?>assets/images/attachment-img.png"></figure>
                                        <div class="text">Resume</div>
                                    </article>
                                </a>
                                <a data-toggle="modal" data-target="#cover_letter_modal" href="javascript:void(0);" title="<?= $cover_letter_title ?>">
                                    <article>
                                        <figure><img src="<?php echo base_url() ?>assets/images/attachment-img.png"></figure>
                                        <div class="text">Cover Letter</div>
                                    </article>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="hr-widget" id="attachment_edit" style="display: none;">
                        <form action="<?php echo base_url('employee_management/upload_attachment') ?>/<?php echo $employer['sid'] ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-title-section">
                                <div class="form-btns">
                                    <input type="submit" value="save">
                                    <input type="button" value="cancel" id="attachment_view_button">
                                </div>
                            </div>
                            <div class="attachment-header attachment_edit">
                                <div class="remove-file">
                                    <p id="name_resume"><?php echo substr($resume_link_title, 0, 28); ?></p>
                                    <?php if ($resume_link_title != "No Resume found!") { ?>
                                        <a class="remove-icon" href="javascript:void(0);" onclick="file_remove(<?= $employer['sid'] ?>, 'Resume')"><i aria-hidden="true" class="fa fa-remove"></i></a>
                                    <?php } ?>
                                </div>
                                <h4>Resume</h4>
                                <div class="btn-inner">
                                    <input type="file" name="resume" id="resume" onchange="check_file('resume')" class="choose-file-filed">
                                    <a href="" class="select-photo"><i aria-hidden="true" class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="attachment-header attachment_edit">
                                <div class="remove-file">
                                    <p id="name_cover_letter"><?php echo substr($cover_letter_title, 0, 28); ?></p>
                                    <?php if ($cover_letter_title != "No Cover Letter found!") { ?>
                                        <a class="remove-icon" href="javascript:void(0);" onclick="file_remove(<?= $employer['sid'] ?>, 'Cover Letter')"><i aria-hidden="true" class="fa fa-remove"></i></a>
                                    <?php } ?>
                                </div>
                                <h4>Cover Letter</h4>
                                <div class="btn-inner">
                                    <input type="file" id="cover_letter" name="cover_letter" onchange="check_file('cover_letter')" class="choose-file-filed">
                                    <a href="" class="select-photo"><i aria-hidden="true" class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            <input type="hidden" name="old_resume" id="action" value="<?= $employer['resume'] ?>">
                            <input type="hidden" name="old_letter" id="action" value="<?= $employer['cover_letter'] ?>">
                        </form>
                    </div>

                    <!--Extra attachments panel starts-->
                    <div class="hr-widget">
                        <form action="<?php echo base_url('applicant_profile/upload_extra_attachment') ?>" method="POST" enctype="multipart/form-data">
                            <div class="attachment-header">
                                <div class="attachment-header">
                                    <p id="name_all_file"></p>
                                    <h4>Add File<samp class="red"> * </samp></h4>
                                    <div class="btn-inner">

                                        <input type="file" name="newlife" id="all_file" required="required" onchange="check_file('all_file')" class="choose-file-filed">
                                        <a href="" class="select-photo"><i aria-hidden="true" class="fa fa-plus"></i></a>
                                    </div>

                                </div>
                            </div>
                            <div class="form-title-section">
                                <div class="form-btns">
                                    <input type="submit" value="Upload">
                                </div>
                            </div>
                            <?php if (!empty($applicant_extra_attachments)) { ?>
                                <div class="browse-attachments">
                                    <ul>
                                        <?php foreach ($applicant_extra_attachments as $attachment) {
                                            if ($attachment['status'] != 'deleted') {
                                        ?>
                                                <li>
                                                    <h4><?php echo $attachment['original_name']; ?></h4>
                                                    <div class="remove-file remove-icon">
                                                        <a class="" href="javascript:void(0);" onclick="file_remove(<?= $attachment['sid'] ?>, 'file')"><i aria-hidden="true" class="fa fa-remove"></i></a>
                                                    </div>
                                                    <a href="<?php echo AWS_S3_BUCKET_URL . $attachment['uploaded_name']; ?>">Download<i aria-hidden="true" class="fa fa-chevron-circle-down"></i></a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            <input type="hidden" name="applicant_job_sid" value="<?= $id ?>">
                            <input type="hidden" name="users_type" value="employee">
                        </form>
                    </div>
                <?php } ?>
            <?php } ?>
            <!--Extra attachments panel ends-->
        </div>
    </aside>
</div>

<!-- Employee Merge Modal Start -->
<div id="merge_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="review_modal_title">Merge Employee</h4>
            </div>
            <div id="review_modal_body" class="modal-body">
                <div class="table-responsive">
                    <div class="container-fluid">
                        <label>Please select an employee to merge with <?= $employer['first_name'] . ' ' . $employer['last_name'] ?> </label>
                        <select class="invoice-fields" id="employees-list">
                            <option value="">[Select Employee to Merge]</option>
                            <?php $employees = fetchEmployees($this->session->userdata('logged_in')['company_detail']['sid'], $this);
                            foreach ($employees as $emp) { ?>
                                <?php if ($employer["sid"] != $emp['sid']) { ?>
                                    <option value="<?= $emp['sid'] ?>" id="selected_<?= $emp['sid'] ?>" data-secondary_emp_name="<?php echo $emp['first_name'] . ' ' . $emp['last_name']; ?>"><?= getUserNameBySID($emp['sid']) ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <br>
                        <br>
                        <br>
                        <br>
                        <p class="csF14">All the data of the selected employee will be transferred to <?= $employer['first_name'] ?>'s profile.</p>
                        <br>
                        <input class="btn btn-success pull-right" type="button" id="merge-employee" value="Merge Employee">
                        <div class="custom_loader pull-right">
                            <div id="submit-loader" class="loader" style="display: none">
                                <i style="font-size: 25px; color: #81b431;" aria-hidden="true" class="fa fa-cog fa-spin"></i>
                                <span>Please Wait...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="review_modal_footer" class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Employee Merge Modal End -->

<div id="resume_modal" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog modal-lg" style="min-height: 500px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Resume</h4>
                <?php if ($resume_link_title != "No Resume found!") { ?>
                    <a href="<?php echo AWS_S3_BUCKET_URL . $resume_link_title; ?>" download="download">Download</a>
                <?php   } ?>
            </div>
            <?php if ($resume_link_title != "No Resume found!") {
                $type = explode(".", $resume_link_title);
                $type = $type[1];

                if ($type == 'png' || $type == 'jpg' || $type == 'jpe' || $type == 'jpeg' || $type == 'gif') { ?>
                    <img src="<?php echo AWS_S3_BUCKET_URL . $resume_link_title; ?>" style="width:600px; height:500px;" />
                <?php       } else { ?>
                    <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?= $employer["resume_link"] ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
                <?php       }
            } else { ?>
                <span class="nofile-found">No Resume Found!</span>
            <?php   } ?>
        </div>
    </div>
</div>

<!-- Resume Modal Start -->
<div id="show_employee_resume" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Resume</h4>
            </div>
            <div class="modal-body">
                <?php
                $resume_url = get_employee_resume($employer['sid']);
                ?>
                <?php if ($resume_url != "not_found") { ?>
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="resume-pop-up-iframe-container" style="display:none;">
                            <div id="resume-iframe-holder" class="embed-responsive-item">

                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <span class="nofile-found">No Resume Found!</span>
                <?php } ?>
            </div>
            <div class="modal-footer" id="resume_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Resume Modal End -->

<div id="cover_letter_modal" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog modal-lg" style="min-height: 500px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cover Letter</h4>
                <?php if ($cover_letter_title != "No Cover Letter found!") { ?>
                    <a href="<?php echo AWS_S3_BUCKET_URL . $cover_letter_title; ?>" download="download">Download</a>
                <?php } ?>
            </div>
            <?php if ($cover_letter_title != "No Cover Letter found!") {
                $cover_type = explode(".", $cover_letter_title);
                $cover_type = $cover_type[1];

                if ($cover_type == 'png' || $cover_type == 'jpg' || $cover_type == 'jpe' || $cover_type == 'jpeg' || $cover_type == 'gif') {  ?>
                    <img src="<?php echo AWS_S3_BUCKET_URL . $cover_letter_title; ?>" style="width:600px; height:500px;" />
                <?php } else { ?>
                    <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?= $employer["cover_link"] ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
                <?php }
            } else { ?>
                <span class="nofile-found">No Cover Letter Found!</span>
            <?php } ?>
        </div>
    </div>
</div>

<?php if (isset($w4_form) && sizeof($w4_form) > 0) { ?>
    <div id="w4_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned W4 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <?php $view = get_form_view('w4', $w4_form);
                    echo $view; ?>
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($w9_form) && sizeof($w9_form) > 0) { ?>
    <div id="w9_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned W9 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <?php $view = get_form_view('w9', $w9_form);
                    echo $view; ?>
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($i9_form) && sizeof($i9_form) > 0) { ?>
    <div id="i9_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned I9 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <?php $view = get_form_view('i9', $i9_form);
                    echo $view; ?>
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    $(document).on('click', '#merge-employee', function() {
        var _this = $(this);
        var secondary_emp = $('#employees-list').val();
        var primary_emp = '<?= $employer['sid'] ?>';
        var primary_emp_email = '<?= $employer['email'] ?>';
        var primary_emp_name = '<?= $employer['first_name'] . ' ' . $employer['last_name'] ?>';
        var secondary_emp_name = $("#selected_" + secondary_emp).data('secondary_emp_name');

        if (secondary_emp == '' || secondary_emp == undefined) {
            alertify.alert('Error!', 'Please select an employee to merge with <strong>' + primary_emp_name + '</strong>.');
            return false;
        }
        //
        alertify.confirm('Confirmation', "Are you sure you want to merge <strong>" + secondary_emp_name + "</strong> into <strong>" + primary_emp_name + "</strong>?",
            function() {
                $('#submit-loader').show();
                _this.attr('disabled', 'disabled');
                //
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url("merge_company_employee") ?>',
                    data: {
                        secondary_employee: secondary_emp,
                        primary_applicant: primary_emp,
                        email: primary_emp_email,
                        company_sid: '<?= $this->session->userdata('logged_in')['company_detail']['sid'] ?>'
                    },
                    success: function(resp) {
                        $('#submit-loader').hide();
                        _this.removeAttr('disabled');
                        var result = JSON.parse(resp);
                        if (result.status == 'error') {
                            alertify.alert(result.message);
                        } else {
                            $("#merge_modal").hide();
                            alertify.alert("Success", "Employee <strong>" + secondary_emp_name + "</strong> merges into <strong>" + primary_emp_name + "</strong> successfully.", function() {
                                window.location.href = '<?= base_url("employee_profile") . '/' ?>' + primary_emp;
                            });
                        }
                    },
                    error: function() {

                    }
                });
            },
            function() {

            });
    });

    $(document).ready(function() {
        $('#trigger-review').click(function() {
            $('#tab5_nav').click();
            CKEDITOR.replace('rating_comment');
        });


        $('#attachment_edit_button').click(function(event) {
            event.preventDefault();
            $('#attachment_edit').fadeIn();
            $('#attachment_view').hide();
        });

        $('#attachment_view_button').click(function(event) {
            event.preventDefault();
            $('#attachment_edit').hide();
            $('#attachment_view').fadeIn();
        });

        $('#w4_modal').find('div.container').removeClass('container');
        $('#i9_modal').find('div.container').removeClass('container');
    });

    function fSendFullEmploymentForm() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to send a Full Employment Application to this Applicant?',
            function() {
                $('#form_send_full_employment_application').submit();
            },
            function() {
                //Cancel
            }
        );
    }

    function file_remove(id, type) {
        url = "<?= base_url() ?>employee_profile/delete_file";
        alertify.confirm('Confirmation', "Are you sure you want to delete this " + type + "?",
            function() {
                $.post(url, {
                        type: type,
                        id: id
                    })
                    .done(function(data) {
                        location.reload();
                    });
            },
            function() {

            });
    }

    $("#show_employee_resume_btn").on('click', function() {
        $('#show_employee_resume').modal('show');
        $('#resume-pop-up-iframe-container').show();
        var preview_document = 1;

        var file_s3_path = '<?php echo AWS_S3_BUCKET_URL . get_employee_resume($employer['sid']); ?>';
        var file_s3_name = '<?php echo get_employee_resume($employer['sid']); ?>';

        if (file_s3_name != 'not_found') {
            var file_extension = file_s3_name.substr(file_s3_name.lastIndexOf('.') + 1, file_s3_name.length);
            var document_file_name = file_s3_name.substr(0, file_s3_name.lastIndexOf('.'));
            var document_extension = file_extension.toLowerCase();


            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pdf';
                    break;
                case 'csv':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.csv';
                    break;
                case 'doc':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edoc&wdAccPdf=0';
                    break;
                case 'docx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edocx&wdAccPdf=0';
                    break;
                case 'ppt':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.ppt';
                    break;
                case 'pptx':
                    dpreview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pptx';
                    break;
                case 'xls':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    ocument_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exls';
                    break;
                case 'xlsx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exlsx';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    preview_document = 0;
                    preview_image_url = file_s3_path;
                    document_print_url = '<?php echo base_url("hr_documents_management/print_s3_image"); ?>' + '/' + file_s3_name;
                    break;
                default: //using google docs
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    break;
            }

            document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>' + '/' + file_s3_name;



            if (preview_document == 1) {
                model_contant = $("<iframe />")
                    .attr("id", "resume_document_iframe")
                    .attr("class", "uploaded-file-preview")
                    .attr("src", preview_iframe_url);

                loadIframe(preview_iframe_url, '#resume_document_iframe', true);
            } else {
                model_contant = $("<img />")
                    .attr("id", "resume_image_tag")
                    .attr("class", "img-responsive")
                    .css("margin-left", "auto")
                    .css("margin-right", "auto")
                    .attr("src", preview_image_url);
            }


            $("#resume-iframe-holder").append(model_contant);

            footer_content = '<a target="_blank" class="btn btn-success" href="' + document_print_url + '">Print</a>';
            footer_content += '<a target="_blank" class="btn btn-success" href="' + document_download_url + '">Download</a>';
            $("#resume_modal_footer").html(footer_content);
        }
    });

    function check_employee_resume_iframe_content(url) {
        try {
            if ($("#resume_document_iframe").contents().find("body").text() == '') {
                $("#resume_document_iframe").prop('src', url);
                setTimeout(function() {
                    check_employee_resume_iframe_content(url);
                }, 3000);
            }
        } catch (err) {
            console.log('iframe preview load successfully')
        }
    }

    //    $(document).on('click','.disable-btn',function(){
    //        alertify.confirm('ComplyNet Status','Are you sure you want to disable complynet status for employee?',function(){
    //                $.ajax({
    //                    url:'<? //= base_url('employee_management/change_complynet_status');
                                ?>//',
    //                    data:{
    //                        sid: <? //= $employer['sid'];
                                    ?>//,
    //                        status: 0
    //                    },
    //                    type: 'POST',
    //                    success: function () {
    //                        $('.disable-btn').addClass('enable-btn');
    //                        $('.disable-btn').html('Enable');
    //                        $('.compy-text').html('Disabled');
    //                        $('.disable-btn').removeClass('disable-btn');
    //                        alertify.success('ComplyNet Status Updated Successfully');
    //                    },
    //                    error: function (){
    //                        alertify.error('Something went wrong');
    //                    }
    //            });
    //        },
    //        function(){
    //            alertify.error('Cancelled');
    //        });
    //    });
    //    $(document).on('click','.enable-btn',function(){
    //        alertify.confirm('ComplyNet Status','Are you sure you want to enable complynet status for employee?',function(){
    //                $.ajax({
    //                    url:'<? //= base_url('employee_management/change_complynet_status');
                                ?>//',
    //                    data:{
    //                        sid: <? //= $employer['sid'];
                                    ?>//,
    //                        status: 1
    //                    },
    //                    type: 'POST',
    //                    success: function () {
    //                        $('.enable-btn').addClass('disable-btn');
    //                        $('.enable-btn').html('Disable');
    //                        $('.compy-text').html('Enabled');
    //                        $('.enable-btn').removeClass('enable-btn');
    //                        alertify.success('ComplyNet Status Updated Successfully');
    //                    },
    //                    error: function (){
    //                        alertify.error('Something went wrong');
    //                    }
    //            });
    //        },
    //        function(){
    //            alertify.error('Cancelled');
    //        });
    //    });
</script>

<script>
    $('#cover_letter_modal').on('show.bs.modal', function() {
        if ($(this).find('iframe').length !== 0) {
            loadIframe(
                $(this).find('iframe').prop('src'),
                $(this).find('iframe'),
                true
            );
        }
    });
</script>

<?php $this->load->view('iframeLoader'); ?>


<script>
    window.sre = {};
    window.sre.url = "<?= base_url(); ?>";
    window.sre.userId = <?= $employer['sid']; ?>;
    window.sre.userType = 'employee';
</script>

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: auto !important;
    }
</style>