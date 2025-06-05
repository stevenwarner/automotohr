<?php
if ($this->session->userdata('logged_in')) {
    $data['session'] = $this->session->userdata('logged_in');
    $company_sid = $data['session']['company_detail']['sid'];
    if(!isset($applicant_jobs)){
        $applicant_jobs = $this->application_tracking_system_model->get_single_applicant_all_jobs($id, $company_sid);
    } 
    $employer_sid = $session['employer_detail']['sid'];
    $employer_access_level = $session['employer_detail']['access_level_plus'];
    $applicant_sid = $applicant_info['sid'];
    $form_full_application_flag = 0;

    if ($employer_access_level == 1 || !in_array(strtolower(trim($session['employer_detail']['access_level'])), ['hiring manager', 'manager'])) {
        $form_full_application_flag = 1;
    } else {
        $is_job_assign = getEmployerAssignJobs($employer_sid, $applicant_sid);
        //
        if ($is_job_assign) {
            $form_full_application_flag = 1;
        }
    }
    //
    $is_hiring_manager = $session['employer_detail']['access_level'] == "Hiring Manager" ? true : false;

}

?>

<style type="text/css">
    .upload-resume{
            float: left;
    width: 100%;
    height: 36px;
    line-height: 36px;
    padding: 0;
    text-align: center;
    border-radius: 4px;
    background-color: transparent;
    border: 1px solid #ddd;
    color: #000;
    }
</style>
<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 hidden-print">
    <aside class="side-bar">
        <a href="<?php echo isset($ats_full_url) ? $ats_full_url : base_url('application_tracking_system/active/all/all/all/all/all/all/all/all/all'); ?>">
            <header class="sidebar-header">
                <h1>Application Tracking </h1>
            </header>
        </a>
        <div class="next-applicant">
            <ul>
                <li class="previous-btn"><a href="<?php echo $prev_app ?>"><i aria-hidden="true" class="fa fa-chevron-left"></i>Prev</a></li>
                <li class="next-btn"><a href="<?php echo $next_app ?>">next<i aria-hidden="true" class="fa fa-chevron-right"></i></a></li>
            </ul>
        </div>
        
        <?php if($assignment_status == 'assigned'){ ?>
                <a href="<?php echo base_url('task_management'); ?>">
                    <header class="sidebar-header">
                        <h1>Task Management </h1>
                    </header>
                </a>
        <?php } ?>
        <div class="widget-wrp">
            <?php if(strpos(current_url(),'applicant_profile')){?>
                <div class="hr-widget">
                    <div class="info-area">
                        <h2>Applicant Reviews</h2>
                    </div>
                    <div class="start-rating">
                        <form action="<?php echo base_url('applicant_profile/save_rating'); ?>" method="post" >
                            <input type="hidden" name="applicant_job_sid" value="<?= $id ?>" >
                            <input type="hidden" name="applicant_email" value="<?= $email ?>" >
                            <input type="hidden" name="users_type" value="applicant" >
                            <div class="text-center applicant-rating-container">
                                <input readonly="readonly" id="input-21b" <?php if (!empty($applicant_rating)) { ?> value="<?php echo $applicant_rating['rating']; ?>" <?php } ?> type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                            </div>
                            <div class="rating-comment">
    <!--                            <h4>comment<samp class="red"> * </samp></h4>-->
                                <input type="button" id="trigger-review" value="Review Comments" class="btn btn-success" style="width: 100%; margin-top: 5px;">
    <!--                            <textarea id="rating_comment" name="comment" required>--><?php //if (!empty($applicant_rating)){ echo $applicant_rating['comment']; } ?><!--</textarea>-->
    <!--                            <input type="submit" value="submit">-->
                            </div>
                        </form>
                    </div>
                </div>
            <?php }?>
            <div class="hr-widget">
                <div class="applicant-status">
                    <div class="info-area">
                        <h2>Job Fit Categories</h2>
                        <?php if(!empty($applicant_info['job_fit_category_sid'])) { ?>
                            <?php
                                $job_fit_category_sid = explode(',', $applicant_info['job_fit_category_sid'])
                            ?>
                            <?php foreach($job_categories as $category) { ?>
                                <?php if (in_array($category['id'], $job_fit_category_sid)) { ?>
                                    <span class="custom-label"><?php echo $category['value']; ?></span>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <span class="no-data">No Job Fit Category Set</span>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php $function_names = array('applicant_profile', 'background_check', 'drug_test', 'reference_checks', 'send_reference_request_email', 'send_kpa_onboarding'); ?>
            <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
            <div class="hr-widget">
                <div class="browse-attachments">
                    <ul>
                        <?php if(check_access_permissions_for_view($security_details, 'applicant_profile')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-user"></i>
                            </span>
                            <h4>Applicant Profile</h4>
                            <a href="<?php echo base_url('applicant_profile') . '/' . $applicant_info['sid']. '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'background_check')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-check"></i>
                            </span>
                            <h4>Background Check</h4>
                            <?php   $_SESSION['applicant_id'] = $applicant_info['sid'];
                                    $_SESSION['applicant_type'] = 'applicant_profile';
                                    if ($company_background_check == 1) { ?>
                                        <a href="<?php echo base_url('background_check') . '/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid ; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <?php   } else { ?>
                                        <a href="<?php echo base_url('background_check/activate') ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <?php   } ?>

                            <!-- Light Bulb Code - Start -->
                            <?php $background_check_count = count_accurate_background_orders($applicant_info['sid']); ?>
                            <?php if(intval($background_check_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Background Check Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Background Check Not Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'drug_test')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-medkit"></i>
                            </span>
                            <h4>Drug Testing</h4>
                            <?php   if ($company_background_check == 1) { ?>
                                        <a href="<?php echo base_url('drug_test') . '/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <?php   } else {
                                        $_SESSION['applicant_id'] = $applicant_info['sid']; ?>
                                        <a href="<?php echo base_url('background_check/activate') ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <?php   } ?>

                            <!-- Light Bulb Code - Start -->
                            <?php $background_check_count = count_accurate_background_orders($applicant_info['sid'], 'drug-testing'); ?>
                            <?php if(intval($background_check_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Drug Test Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Drug Test Not Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>
                        <!--<li>
                            <h4>Behavioral Assessment</h4>
                            <a href="javascript:;">Browse<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <?php if(check_access_permissions_for_view($security_details, 'reference_checks')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-link"></i>
                            </span>
                            <h4>Reference Check</h4>
                            <a href="<?php echo base_url('reference_checks') . '/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <!-- Light Bulb Code - Start -->
                            <?php $references_count = count_references_records($applicant_info['sid']);?>
                            <?php if(intval($references_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has References Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No References Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'send_reference_request_email')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-link"></i>
                            </span>
                            <form action="<?php echo base_url('applicant_profile/send_reference_request_email'); ?>" method="post" id="form_request_references_<?php echo $applicant_info['sid']; ?>">
                                <input type="hidden" id="perform_action" name="perform_action" value="send_add_reference_request_email" />
                                <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_info['sid']; ?>" />
                            </form>
                            <h4>References Request</h4>
                            <a href="javascript:;" onclick="fSendAddReferencesRequestEmail(<?php echo $applicant_info['sid']; ?>);">Send<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <!-- Light Bulb Code - Start -->
                            <?php $reference_check_request_status = get_reference_checks_request_sent_status($applicant_info['sid']); ?>

                            <?php if($reference_check_request_status == 'sent') { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'send_kpa_onboarding')) { ?>
                            <?php if($kpa_onboarding_check == 1) { ?>
                            <li>
                                <span class="left-addon">
                                    <i aria-hidden="true" class="fa fa-cog"></i>
                                </span>
                                <h4>Outsourced HR Onboarding</h4>
                                <form action="<?php echo base_url('applicant_profile/send_kpa_onboarding'); ?>" method="post" id="form_kpa_onboarding_<?php echo $applicant_info['sid']; ?>">
                                    <input type="hidden"  name="kpa_action" value="send_kpa_onboarding_email" />
                                    <input type="hidden" name="applicant_sid" value="<?php echo $applicant_info['sid']; ?>" />
                                </form>
                                <a href="javascript:;" onclick="fSendKpaOnboardingEmail(<?php echo $applicant_info['sid']; ?>);">Send<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                
                                <?php if($kpa_email_sent == true) { ?>
                                        <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                        <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                            </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if($this->session->userdata('logged_in')['company_detail']['ems_status'] == 1){?>
                            <li>
                                <span class="left-addon">
                                    <i aria-hidden="true" class="fa fa-envelope"></i>
                                </span>
                                <h4>Send Offer Letter / Pay Plans</h4>
                                <a href="<?php echo base_url('onboarding/send_offer_letter/applicant') . '/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                <?php $offer_letter_ststus = count_offer_letter('applicant', $applicant_info['sid']);?>
                                <!-- Light Bulb Code - Start -->
                                <?php if ($offer_letter_ststus == 'sign' || $offer_letter_ststus == 'sent') { ?>
                                        <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else if ($offer_letter_ststus == 'not_sent') { ?>
                                        <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->
                            </li>
                        <?php }?>
                        <?php if($this->session->userdata('logged_in')['company_detail']['ems_status'] == 1){?>
                            <li>
                                <span class="left-addon">
                                    <i aria-hidden="true" class="fa fa-envelope"></i>
                                </span>
                                <h4>View Offer Letter / Pay Plans</h4>
                                <a href="<?php echo base_url('onboarding/view_offer_letter/applicant') . '/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                <?php $offer_letter_ststus = count_offer_letter('applicant', $applicant_info['sid']);?>
                                <!-- Light Bulb Code - Start -->
                                <?php if ($offer_letter_ststus == 'sign') { ?>
                                        <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else if ($offer_letter_ststus == 'sent' || $offer_letter_ststus == 'not_sent') { ?>
                                        <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">   
                                <?php } ?>
                                <!-- Light Bulb Code - End -->
                            </li>
                        <?php }?>
                        <?php if($session['employer_detail']['access_level_plus'] == 1 || check_access_permissions_for_view($security_details, 'merge_applicant_with_employee')){?>
                            <li>
                                <span class="left-addon">
                                    <i aria-hidden="true" class="fa fa-star"></i>
                                </span>
                                <h4>Merge Into Employee</h4>
                                <a class="" href="javascript:0;" data-toggle="modal" data-target="#merge_modal">Merge<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>

                            </li>
                        <?php }?>
                        <?php if(($session['company_detail']['has_applicant_approval_rights'] == 0 || $session['employer_detail']['has_applicant_approval_rights'] == 1)){?>
                            <?php if($session['employer_detail']['access_level_plus'] == 1 || check_access_permissions_for_view($security_details, 'hire_applicant_manually')) { ?>
                                <li>
                                    <span class="left-addon">
                                        <i aria-hidden="true" class="fa fa-user-plus"></i>
                                    </span>
                                    <h4>Direct Hire Candidate</h4>
                                    <a href="javascript:;" onclick="fun_hire_applicant();">Hire<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                </li>
                            <?php }?>
                        <?php }?>
                        <?php if($session['company_detail']['has_applicant_approval_rights'] == 0 || $session['employer_detail']['has_applicant_approval_rights'] == 1){?>
                            <?php if($session['employer_detail']['access_level_plus'] == 1 || check_access_permissions_for_view($security_details, 'setup')) { ?>
                                <li>
                                    <span class="left-addon">
                                        <i aria-hidden="true" class="fa fa-star"></i>
                                    </span>
                                    <h4>Setup Onboarding Panel</h4>
                                    <a href="<?php echo base_url('onboarding/setup/applicant') . '/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    <!-- Light Bulb Code - Start -->
                                    <?php $applicant_panel_config_count = count_onboarding_panel_records('applicant', $applicant_info['sid']);?>
                                    <?php if(intval($applicant_panel_config_count) > 0) { ?>
                                        <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Applicant Panel Set-up" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                    <?php } else { ?>
                                        <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Applicant Panel Not Set-up" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                    <?php } ?>
                                    <!-- Light Bulb Code - End -->
                                </li>
                            <?php }?>
                        <?php }?>

                        <?php if(isset($applicant_job_queue) && $applicant_job_queue) { ?>
                            <li>
                                <span class="left-addon">
                                    <i aria-hidden="true" class="fa fa-star"></i>
                                </span>
                                <h4>Submited Application</h4>
                                <a href="<?php echo base_url('applicant_profile/submitted/resume') . '/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                <!-- Light Bulb Code - Start -->
                                <?php $applicant_panel_config_count = count_onboarding_panel_records('applicant', $applicant_info['sid']);?>
                                <?php if(intval($applicant_panel_config_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Applicant Panel Set-up" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Applicant Panel Not Set-up" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->
                            </li>
                        <?php } ?>
                        
                        <?php if(
                                $applicant_info['is_onboarding'] == 1 &&
                                (
                                    (
                                        (
                                            $session['company_detail']['has_applicant_approval_rights'] == 0 || 
                                            $session['employer_detail']['has_applicant_approval_rights'] == 1
                                        ) && check_access_permissions_for_view($security_details, 'setup')
                                    ) 
                                    || $session['employer_detail']['access_level_plus']
                                )
                            ){?>
                            <li>
                                <span class="left-addon">
                                    <i aria-hidden="true" class="fa fa-history"></i>
                                </span>
                                <h4>Revert Onboarding</h4>
                                <a href="#" data-id="<?=$applicant_info['sid'];?>" class="jsRevertOnboarding">Revert<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            </li>
                        <?php }?>
                        <input type="hidden" value="<?= $applicant_info['sid']?>" id="app-id">
                    </ul>
                </div>
            </div>
            <?php } ?>
            <?php $function_names = array('send_applicant_full_employement_application', 'view_applicant_full_employement_application', 'applicant_emergency_contacts', 'applicant_occupational_license_info', 'applicant_drivers_license_info', 'applicant_equipment_info', 'applicant_dependants'); ?>
            <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
            <div class="hr-widget">
                <div class="browse-attachments">
                    <ul>
                        <?php if ($form_full_application_flag == 1) { ?>
                            <?php if(check_access_permissions_for_view($security_details, 'send_applicant_full_employment_application')) { ?>
                                <li>
                                    <span class="left-addon">
                                        <i aria-hidden="true" class="fa fa-file-text"></i>
                                    </span>
                                    <h4>Full Employment Application</h4>
                                    <?php $full_emp_form_status = get_full_emp_app_form_status($applicant_info['sid'],'applicant'); ?>
                                        <form id="form_send_full_employment_application" enctype="multipart/form-data" method="post" action="<?php echo base_url('form_full_employment_application/send_form'); ?>">
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $applicant_info['employer_sid']; ?>" />
                                        <input type="hidden" id="user_type" name="user_type" value="applicant" />
                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $applicant_info['sid']; ?>" />
                                        <input type="hidden" name="list_sid" value="<?php echo $job_list_sid; ?>" />
                                    </form>
                                    <div <?= $full_emp_form_status == 'sent' || $full_emp_form_status == 'signed' ? 'class="view-btn"':''?>>
                                        <a href="javascript:void(0);" onclick="fSendFullEmploymentForm();"><?= $full_emp_form_status == 'sent' || $full_emp_form_status == 'signed' ? 'Resend' :'Send';?><i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                        <!-- Light Bulb Code - Start -->

                                        <?php if($full_emp_form_status == 'sent' || $full_emp_form_status == 'signed') { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                        <?php } else { ?>
                                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                        <?php } ?>
                                    </div>
                                    <!-- Light Bulb Code - End -->
                                </li>
                            <?php } ?>
                            <?php if(check_access_permissions_for_view($security_details, 'view_applicant_full_employment_application')) { ?>
                                <li>
                                    <span class="left-addon">
                                        <i aria-hidden="true" class="fa fa-file-text"></i>
                                    </span>
                                    <h4>Full Employment Application</h4>
                                    <a href="<?php echo base_url('applicant_full_employment_application') . '/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    <!-- --><?php //$full_employment_application_status = get_full_employment_application_status($applicant_info['sid'], 'applicant'); ?>
                                    <?php $full_employment_application_status = get_full_emp_app_form_status($applicant_info['sid'],'applicant'); ?>

                                    <?php if($full_employment_application_status == 'signed') { ?>
                                            <img title="Signed" style="width: 22px; height: 22px; margin-right:5px;" data-toggle="tooltip" data-placement="top" class="img-responsive pull-right" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                    <?php } else { ?>
                                            <img title="Unsigned" style="width: 22px; height: 22px; margin-right:5px;" data-toggle="tooltip" data-placement="top" class="img-responsive pull-right" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                    <?php } ?>

                                </li>
                            <?php } ?>
                        <?php } ?>    

                        <?php $w4_form = get_fillable_info('w4','applicant',$applicant_info['sid']);
                        if(sizeof($w4_form)>0) { ?>
<!--                            <li>-->
<!--                            <span class="left-addon">-->
<!--                                <i aria-hidden="true" class="fa fa-file-text"></i>-->
<!--                            </span>-->
<!--                                <h4>Fillable W4 Form</h4>-->
<!--                                <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
                                <!-- Light Bulb Code - Start -->
<!--                                --><?php //if($w4_form['user_consent']) { ?>
<!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/on.gif'); ?><!--">-->
<!--                                --><?php //} else { ?>
<!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/off.gif'); ?><!--">-->
<!--                                --><?php //} ?>
                                <!-- Light Bulb Code - End -->
<!--                            </li>-->
                        <?php } ?>
                        <?php $w9_form = get_fillable_info('w9','applicant',$applicant_info['sid']);
                        if(sizeof($w9_form)>0) { ?>
<!--                            <li>-->
<!--                            <span class="left-addon">-->
<!--                                <i aria-hidden="true" class="fa fa-file-text"></i>-->
<!--                            </span>-->
<!--                                <h4>Fillable W9 Form</h4>-->
<!--                                <a data-toggle="modal" data-target="#w9_modal" href="javascript:void(0);">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
                                <!-- Light Bulb Code - Start -->
<!--                                --><?php //if($w9_form['user_consent']) { ?>
<!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/on.gif'); ?><!--">-->
<!--                                --><?php //} else { ?>
<!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/off.gif'); ?><!--">-->
<!--                                --><?php //} ?>
                                <!-- Light Bulb Code - End -->
<!--                            </li>-->
                        <?php } ?>
                        <?php $i9_form = get_fillable_info('i9','applicant',$applicant_info['sid']);
                        if(sizeof($i9_form)>0) { ?>
<!--                            <li>-->
<!--                            <span class="left-addon">-->
<!--                                <i aria-hidden="true" class="fa fa-file-text"></i>-->
<!--                            </span>-->
<!--                                <h4>Fillable I9 Form</h4>-->
<!--                                --><?php //if($i9_form['employer_flag']){ ?>
<!--                                    <a data-toggle="modal" data-target="#i9_modal" href="javascript:void(0);">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
<!--                                --><?php //} else{ ?>
<!--                                    <a href="--><?php //echo base_url('form_i9/applicant') . '/' . $applicant_info['sid'] . "/" . $job_list_sid; ?><!--">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>-->
<!--                                --><?php //} ?>
                                <!-- Light Bulb Code - Start -->
<!--                                --><?php //if($i9_form['user_consent']) { ?>
<!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/on.gif'); ?><!--">-->
<!--                                --><?php //} else { ?>
<!--                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="--><?php //echo site_url('assets/manage_admin/images/off.gif'); ?><!--">-->
<!--                                --><?php //} ?>
                                <!-- Light Bulb Code - End -->
<!--                            </li>-->
                        <?php } ?>
                        <!--<li>
                            <h4>WOTC New Hire Tax Credits</h4>
                            <a href="javascript:;">Browse<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <?php if(check_access_permissions_for_view($security_details, 'applicant_emergency_contacts')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-ambulance"></i>
                            </span>
                            <h4>Emergency Contacts</h4>
                            <a href="<?php echo base_url('emergency_contacts') . '/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <!-- Light Bulb Code - Start -->
                            <?php $emergency_contacts_count = count_emergency_contacts($applicant_info['sid']);  ?>
                            <?php if(intval($emergency_contacts_count > 0)) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Emergency Contacts Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Emergency Contacts Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'applicant_occupational_license_info')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-industry"></i>
                            </span>
                            <h4>Occupational License Info</h4>
                            <a href="<?php echo base_url('occupational_license_info') . '/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <!-- Light Bulb Code - Start -->
                            <?php $occ_licenses_count = count_licenses($applicant_info['sid'], 'occupational'); ?>
                            <?php if(intval($occ_licenses_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Occupational License Saved" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Occupational License Information" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'applicant_drivers_license_info')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-automobile"></i>
                            </span>
                            <h4>Drivers License Info</h4>
                            <a href="<?php echo base_url('drivers_license_info') . '/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <!-- Light Bulb Code - Start -->
                            <?php $drv_licenses_count = count_licenses($applicant_info['sid'], 'drivers'); ?>
                            <?php if(intval($drv_licenses_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Drivers License Saved" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Drivers License Information" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'applicant_equipment_info')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-laptop"></i>
                            </span>
                            <h4>Equipment Info</h4>
                            <a href="<?php echo base_url('equipment_info') . '/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <!-- Light Bulb Code - Start -->
                            <?php $equipments_count = count_equipments($applicant_info['sid']); ?>
                            <?php if(intval($equipments_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Equipment Assigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Equipment Assigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'applicant_i9form')) { ?>
<!--                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-file-text"></i>
                            </span>
                            <h4>i9 Employment Verification</h4>
                            <a href="<?php echo base_url('i9form') . '/applicant/' . $applicant_info['sid']; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'applicant_dependants')) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-child"></i>
                            </span>
                            <h4>Dependents</h4>
                            <a href="<?php echo base_url('dependants') . '/applicant/' . $applicant_info['sid'] . "/" . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <!-- Light Bulb Code - Start -->
                            <?php $dependant_count = count_dependants($applicant_info['sid']); ?>
                            <?php if(intval($dependant_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Dependents" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Dependents Information Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php } ?>

                        <!--<li>-->
                            <!--
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-question-circle"></i>
                            </span>
                            <h4>Interview Questionnaire</h4>
                            <a href="<?php /*echo base_url('interview_questionnaires') . '/applicant/' . $applicant_info['sid']; */?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
-->
                            <!-- Light Bulb Code - Start -->
                            <?php /*$interview_score_count = count_interview_score_records($applicant_info['sid']); */?><!--
                            <?php /*if(intval($interview_score_count) > 0) { */?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Dependents" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php /*echo site_url('assets/manage_admin/images/on.gif'); */?>">
                            <?php /*} else { */?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Dependents Information Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php /*echo site_url('assets/manage_admin/images/off.gif'); */?>">
                            --><?php /*} */?>
                            <!-- Light Bulb Code - End -->
                        <!--</li>-->
                        <!--
                        <li>
                            <h4>Benefit Elections</h4>
                            <a href="javascript:;">Browse<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                        </li>

                        <li>
                            <h4>Payroll</h4>
                            <a href="javascript:;">Browse<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                        </li>
                        -->
                        <?php if(check_access_permissions_for_view($security_details, 'send_video_interview_questionnaire') || $is_hiring_manager) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-video-camera"></i>
                            </span>
                            <h4>Video Interview Questions</h4>
                            <!-- change to bulb to on/off state based on previous records whether the questions have been sent or not -->
                            <a href="<?php echo base_url() . 'video_interview_system/send_questions/' . $applicant_info['sid'] . '/' . $job_list_sid . '/' ; ?>" >Send<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <?php if ($questions_sent == false) { ?>
                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Send Video Interview Questions" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } else { ?>
                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Video Interview Questions Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } ?>
                        </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'view_video_interview_questionnaire') || $is_hiring_manager) { ?>
                        <li>
                            <span class="left-addon">
                                <i aria-hidden="true" class="fa fa-video-camera"></i>
                            </span>
                            <h4>Video Interview Questions</h4>
                            <!-- change to bulb to on/off state based on previous records whether the questions have been sent or not -->
                            <a href="<?php echo base_url() . 'video_interview_system/question_responses/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>" >View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <?php if ($questions_answered == false) { ?>
                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Video Interview Response" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } else { ?>
                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="View Video Interview Response" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } ?>
                        </li>
                        <?php } ?>

                        <?php if(check_access_permissions_for_view($security_details, 'ats_direct_deposit') || $is_hiring_manager) { ?>
                            <li>
                                <span class="left-addon"><i aria-hidden="true" class="fa fa-bank"></i></span>
                                <h4>Direct Deposit Information</h4>
                                <a href="<?php echo base_url('direct_deposit') . '/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid; ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                <!-- Light Bulb Code - Start -->
                                <?php $direct_deposit_count = count_direct_deposit($applicant_info['sid']); ?>
                                <?php if(intval($direct_deposit_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Direct Deposit Info Added" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Direct Deposit Info Not Added" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->
                            </li>
                        <?php } ?>

                        <?php if(false) { ?>
                            <li>
                                <span class="left-addon"><i aria-hidden="true" class="fa fa-file"></i></span>
                                <h4>Documents</h4>
                                <a href="<?php echo base_url('hr_documents_management/documents_assignment/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                <?php $direct_deposit_count = count_assigned_documents('applicant', $applicant_info['sid']); ?>
                                <?php if(intval($direct_deposit_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Documents Assigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Documents Assigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->
                            </li>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'ats_learning_center') || $is_hiring_manager) { ?>
                        <li>
                            <span class="left-addon"><i aria-hidden="true" class="fa fa-file"></i></span>
                            <h4>Learning Center</h4>
                            <a href="<?php echo base_url('learning_center/my_learning_center/' . $applicant_info['sid'] . '/' . $job_list_sid); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                            <?php $learning_center_count = count_learning_center($applicant_info['sid'], $company_sid, 'applicant'); ?>
                            <?php if(intval($learning_center_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Direct Deposit Info Added" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Direct Deposit Info Not Added" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                        </li>
                        <?php } if((check_access_permissions_for_view($security_details, 'ats_documents') || $is_hiring_manager) && $this->session->userdata('logged_in')['company_detail']['ems_status']){ ?>
                        <li>
                            <span class="left-addon"><i aria-hidden="true" class="fa fa-file-text"></i></span>
                            <h4>Documents</h4>
                            <a href="<?php echo base_url('hr_documents_management/documents_assignment/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid); ?>">View<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                        </li>
                        <?php } ?>
                        <!-- Reminded Emails Send -->
                        <li style="cursor: pointer;">
                            <span class="left-addon"><i aria-hidden="true" class="fa fa-envelope"></i></span>
                            <h4>Send An Email Reminder</h4>
                            <a href="javascript:void(0)" title="Send An Email Reminder" id="JsSendReminderEmail">Send <i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a><br>
                            <a href="javascript:void(0)" title="View Email Reminder History" id="JsSendReminderEmailHistory">View <i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a> 
                        </li>
                    </ul>
                </div>
            </div>
            <?php } ?>

            <div class="hr-widget" id="attachment_view" >
                <div class="attachment-header">
                    <div class="form-title-section">
                        <h4>Attachments</h4>
                        <div class="form-btns">
                            <input type="submit" value="edit" id="attachment_edit_button">
                        </div>                                              
                    </div>
                    <div class="file-container">
                        <a href="<?php echo base_url('onboarding/view_applicant_resume/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid); ?>"  title="View Resume Library">
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
                    
                    <div class="browse-attachments">
                        <ul>
                            <li>
                                <span class="left-addon"><i aria-hidden="true" class="fa fa-envelope"></i></span>
                                <h4>Send Resume Request</h4>
                                <?php //if (in_array($applicant_info['employer_sid'], array("7", "51"))) { ?>
                                <?php if (!in_array($applicant_info['employer_sid'], array("0"))) { ?>
                                    <?php if(count($applicant_jobs) == 1){ ?>
                                        <a href="javascript:;" class="confirmation" >Send<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                        <div class="" style="display: none;">
                                            <form name="send_resume_request_form" id="send_resume_request_form" method="post" action="<?php echo base_url('onboarding/send_applicant_resume_request/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid); ?>">
                                                <?php if(isset($applicant_jobs[0]['job_sid']) && $applicant_jobs[0]['job_sid'] != 0){
                                                    $job_sid = $applicant_jobs[0]['job_sid'];
                                                    $job_type = "job";
                                                }else{
                                                    $job_sid = $job_list_sid;
                                                    $job_type = "desired_job";
                                                } ?>

                                                <?php
                                                    $job_sid            = $applicant_jobs[0]['job_sid'];
                                                    $job_title          = $applicant_jobs[0]['job_title'];
                                                    $job_listing_sid    = $applicant_jobs[0]['sid'];
                                                    $requested_job_sid     = '';
                                                    $requested_job_type    = '';

                                                    if (!empty($job_sid) || $job_sid > 0) {
                                                        $requested_job_sid     = $job_sid;
                                                        $requested_job_type    = 'job';
                                                    } else if ($job_sid == 0 && !empty($job_title) && $job_title != 'Job Not Applied') {
                                                        $requested_job_sid     = $job_listing_sid;
                                                        $requested_job_type    = 'desired_job';
                                                    } else {
                                                        $requested_job_sid     = '0';
                                                        $requested_job_type    = 'job_not_applied';
                                                    }
                                                ?>
                                                <input type="hidden" name="job_sid" value="<?php echo $requested_job_sid; ?>">
                                                <input type="hidden" name="job_type" value="<?php echo $requested_job_type; ?>">
                                                <input type="submit" id="send_submit" name="send_submit">
                                            </form>
                                        </div>
                                    <?php } else { ?>
                                            <!-- If applicant job is more then one then we call "send_resume_request" modal -->
                                            <a class="" href="javascript:0;" data-toggle="modal" data-target="#send_resume_request">Send<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a href="javascript:;" class="confirmation_old" src="<?php echo base_url('onboarding/send_applicant_resume_request/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid); ?>">Send<i aria-hidden="true" class="fa fa-chevron-circle-right"></i></a>
                                <?php } ?>
                                

                                <?php $resume = check_resume_exist($applicant_info['employer_sid'] , 'applicant',$applicant_info['sid']); ?>
                                <?php if ($resume == 'not_empty') { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Resume Request Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else if ($resume == 'empty') { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Resume Request Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                            </li>
                            <?php 
                                if(isset($applicant_info) && !empty($applicant_info)){
                                    $companyId  = $applicant_info['employer_sid'];
                                    $userType   = $applicant_info['applicant_type'];
                                    $userId     = $applicant_info['sid'];
                                    $last_send_request_date = get_resume_lsq_date($companyId,$userType,$userId);
                                if(!empty($last_send_request_date) && $last_send_request_date != NULL){
                            ?>
                            <li>
                                 <p class="text-left">The last resume request was sent on <br><strong><?php echo date('M d Y, D H:i:s',strtotime($last_send_request_date)); ?></strong></p>
                            </li>
                            <?php } } ?>
                            
                        </ul>
                    </div>
                </div>
            </div>

            <div class="hr-widget" id="attachment_edit" style="display: none;">
                <form action="<?php echo base_url('applicant_profile/upload_attachment') ?>/<?php echo $applicant_info['sid'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-title-section">
                        <div class="form-btns">
                            <input type="submit" value="save">
                            <input type="submit" value="cancel" id="attachment_view_button">
                        </div>                                              
                    </div>
                    <div class="attachment-header attachment_edit">
                        <div class="remove-file">
                            <p id="name_resume"><?php echo substr($resume_link_title, 0, 28); ?></p>
                            <?php if ($resume_link_title != "No Resume found!") { ?>
                                <a class="remove-icon" href="javascript:void(0);" onclick="file_remove(<?php echo $applicant_info['sid'] ?>, 'Resume')"><i aria-hidden="true" class="fa fa-remove"></i></a>
                            <?php } ?>
                        </div>
                        <h4>Resume</h4>
                        <div class="btn-inner">
                            <?php //if (in_array($applicant_info['employer_sid'], array("7", "51"))) { ?>
                            <?php if (!in_array($applicant_info['employer_sid'], array("0"))) { ?>    
                                <?php if(count($applicant_jobs) == 1){ ?>
                                    <input type="file" name="resume" id="resume"   onchange="check_file('resume')" class="choose-file-filed"> 
                                    <a href="" class="select-photo">
                                        <i aria-hidden="true" class="fa fa-plus"></i>
                                    </a>
                                <?php } else{ ?>
                                    <!-- If applicant job is more then one then we call "upload_resume" modal -->
                                    <a href="javascript:0;" data-toggle="modal" data-target="#upload_resume" class="">
                                        <span class="upload-resume">
                                            <i aria-hidden="true" class="fa fa-plus"></i>
                                        </span>
                                    </a>
                                <?php } ?>
                            <?php } else { ?>
                                <input type="file" name="resume" id="resume"   onchange="check_file('resume')" class="choose-file-filed"> 
                                <a href="" class="select-photo"><i aria-hidden="true" class="fa fa-plus"></i></a>
                            <?php } ?>    
                        </div>
                    </div>
                    <div class="attachment-header attachment_edit">
                        <div class="remove-file">
                            <p id="name_cover_letter"><?php echo substr($cover_letter_title, 0, 28); ?></p>
                            <?php if ($cover_letter_title != "No Cover Letter found!") { ?>
                                <a class="remove-icon" href="javascript:void(0);" onclick="file_remove(<?php echo $applicant_info['sid'] ?>, 'Cover Letter')"><i aria-hidden="true" class="fa fa-remove"></i></a>
                            <?php } ?>
                        </div>
                        <h4>Cover Letter</h4>
                        <div class="btn-inner">
                            <input type="file"  id="cover_letter" name="cover_letter" onchange="check_file('cover_letter')" class="choose-file-filed"> 
                            <a href="" class="select-photo"><i aria-hidden="true" class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    
                    <?php
                        $job_sid            = $applicant_jobs[0]['job_sid'];
                        $job_title          = $applicant_jobs[0]['job_title'];
                        $job_listing_sid    = $applicant_jobs[0]['sid'];
                        $upload_job_sid     = '';
                        $upload_job_type    = '';

                        if (!empty($job_sid) || $job_sid > 0) {
                            $upload_job_sid     = $job_sid;
                            $upload_job_type    = 'job';
                        } else if ($job_sid == 0 && !empty($job_title) && $job_title != 'Job Not Applied') {
                            $upload_job_sid     = $job_listing_sid;
                            $upload_job_type    = 'desired_job';
                        } else {
                            $upload_job_sid     = '0';
                            $upload_job_type    = 'job_not_applied';
                        }
                    ?>

                    <input type="hidden" name="job_sid" id="job_sid" value="<?php echo $upload_job_sid; ?>">
                    <input type="hidden" name="job_type" id="job_type" value="<?php echo $upload_job_type; ?>">
                    <input type="hidden" name="old_resume" id="action" value="<?php echo $applicant_info['resume']; ?>">
                    <input type="hidden" name="old_letter" id="action" value="<?php echo $applicant_info['cover_letter']; ?>">
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
                                <?php 
                                    foreach ($applicant_extra_attachments as $attachment) {
                                        if($attachment['status'] != 'deleted') { ?>
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
                    <input type="hidden" name="applicant_job_sid" value="<?= $id ?>" > 
                    <input type="hidden" name="users_type" value="applicant" > 
                </form>
            </div>
            <!--Extra attachments panel ends-->
        </div>
    </aside>                                        
</div>

<div id="send_resume_request" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Send Resume Request
                </h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <form id="" action="<?php echo base_url('onboarding/send_applicant_resume_request/applicant/' . $applicant_info['sid'] . '/' . $job_list_sid); ?>" method="post">
                            <div class="universal-form-style-v2">
                                <ul>
                                    <li class="form-col-100">
                                        <label>Select Job :</label>
                                        <div class="hr-select-dropdown">
                                            <select class="invoice-fields" name="job_sid" id="job_sid_send" required>
                                                <option value="">Please Select Job</option>
                                                <?php
                                                    $continue = 'on';
                                                ?>
                                                <?php foreach($applicant_jobs as $value){ ?>
                                                    <?php
                                                        $job_sid            = $value['job_sid'];
                                                        $job_title          = $value['job_title'];
                                                        $job_listing_sid    = $value['sid'];
                                                    ?>
                                                    <?php if ((!empty($job_sid) || $job_sid > 0) && $job_title != 'Job Not Applied') { ?>
                                                        <option value="<?php echo $job_sid; ?>" data-type="job"> <?php echo $job_title; ?></option>
                                                    <?php } else if ($job_sid == 0 && $job_title != 'Job Not Applied') { ?>
                                                        <option value="<?php echo $job_listing_sid; ?>" data-type="desired_job"> <?php echo $job_title; ?></option>
                                                    <?php } else { ?>
                                                        <?php if ($continue == 'on') { ?>
                                                            <option value="0" data-type="job_not_applied"> <?php echo $job_title; ?></option>
                                                            <?php $continue = 'off' ?>
                                                        <?php } ?>
                                                        
                                                    <?php } ?>
                                                <?php } ?> 
                                            </select>
                                        </div>
                                    </li>
                                    <div class="btn-panel">
                                        <input type="hidden" name="job_type" id="job_type_send" value="">
                                        <input type="submit" class="submit-btn" value="Send">
                                        <input type="button" value="Cancel" class="submit-btn btn-cancel" data-dismiss="modal"/>
                                    </div>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="upload_resume" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Upload Resume
                </h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <form action="<?php echo base_url('applicant_profile/upload_attachment') ?>/<?php echo $applicant_info['sid'] ?>" method="POST" enctype="multipart/form-data">
                            <div class="universal-form-style-v2">
                                <ul>
                                    <li class="form-col-100">
                                        <label>Select Job :</label>
                                        <div class="hr-select-dropdown">
                                            <select class="invoice-fields" name="job_sid" id="job_sid_upload" required>
                                                <option value="">Please Select Job</option>
                                                <?php
                                                    $continue = 'on';
                                                ?>
                                                <?php foreach($applicant_jobs as $value){ ?>
                                                    <?php
                                                        $job_sid            = $value['job_sid'];
                                                        $job_title          = $value['job_title'];
                                                        $job_listing_sid    = $value['sid'];
                                                    ?>
                                                    <?php if ((!empty($job_sid) || $job_sid > 0) && $job_title != 'Job Not Applied') { ?>
                                                        <option value="<?php echo $job_sid; ?>" data-type="job"> <?php echo $job_title; ?></option>
                                                    <?php } else if ($job_sid == 0 && $job_title != 'Job Not Applied') { ?>
                                                        <option value="<?php echo $job_listing_sid; ?>" data-type="desired_job"> <?php echo $job_title; ?></option>
                                                    <?php } else { ?>
                                                        <?php if ($continue == 'on') { ?>
                                                            <option value="0" data-type="job_not_applied"> <?php echo $job_title; ?></option>
                                                            <?php $continue = 'off' ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="form-col-100">
                                        <label>Select Resume :</label>
                                        <div class="hr-select-dropdown">
                                            <input type="file" name="resume" required="required" id="resume" class="choose-file">
                                        </div>
                                    </li>
                                    <div class="btn-panel">
                                        <input type="hidden" name="job_type" id="job_type_upload" value="">
                                        <input type="submit" class="submit-btn" value="Upload">
                                        <input type="button" value="Cancel" class="submit-btn btn-cancel" data-dismiss="modal"/>
                                    </div>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="resume_modal" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog modal-lg" style="min-height: 500px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Resume</h4>
                <?php if ($resume_link_title != "No Resume found!") { ?>
                    <a href="<?php echo $applicant_info["resume_link"] ; ?>" target="_blank">Download</a>
                <?php } ?>
            </div>
            <?php if ($resume_link_title != "No Resume found!") {
                $type = explode(".", $resume_link_title);
                $type = $type[1];

                if ($type == 'png' || $type == 'jpg' || $type == 'jpe' || $type == 'jpeg' || $type == 'gif') { ?>
                    <img src="<?php echo AWS_S3_BUCKET_URL . $resume_link_title; ?>"
                         style="width:600px; height:500px;"/>
                <?php } else { ?>
                    <iframe class="uploaded-file-preview"
                            src="https://docs.google.com/gview?url=<?= $applicant_info["resume_link"] ?>&embedded=true"
                            style="width:600px; height:500px;" frameborder="0"></iframe>
                <?php }
            } else { ?>
                <span class="nofile-found">No Resume Found!</span>
            <?php } ?>
        </div>
    </div>
</div>

<div id="cover_letter_modal" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog modal-lg" style="min-height: 500px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cover Letter</h4>
                <?php if ($cover_letter_title != "No Cover Letter found!") { ?>
                    <a href="<?php echo $applicant_info["cover_link"]; ?>" target="_blank">Download</a>
                <?php } ?>
            </div>
            <?php if ($cover_letter_title != "No Cover Letter found!") {
                $cover_type = explode(".", $cover_letter_title);
                $cover_type = $cover_type[1];

                if ($cover_type == 'png' || $cover_type == 'jpg' || $cover_type == 'jpe' || $cover_type == 'jpeg' || $cover_type == 'gif') { ?>
                    <img src="<?php echo AWS_S3_BUCKET_URL . $cover_letter_title; ?>"
                         style="width:600px; height:500px;"/>
                <?php } else { ?>
                    <iframe class="uploaded-file-preview"
                            src="https://docs.google.com/gview?url=<?= $applicant_info["cover_link"] ?>&embedded=true"
                            style="width:600px; height:500px;" frameborder="0"></iframe>
                <?php }
            } else { ?>
                <span class="nofile-found">No Cover Letter Found!</span>
            <?php } ?>
        </div>
    </div>
</div>

<div id="merge_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="review_modal_title">Merge Applicant (<?= $applicant_info['first_name'] . ' ' . $applicant_info['last_name']?>)</h4>
            </div>
            <div id="review_modal_body" class="modal-body">
                <div class="table-responsive">
                    <div class="container-fluid">
                        <label >Select Employee to Merge: </label>
                        <select class="invoice-fields" id="employees-list">
                            <option value="">[Select Employee to Merge]</option>
                            <?php $employees = fetchEmployees($this->session->userdata('logged_in')['company_detail']['sid'], $this);
                            foreach($employees as $emp){?>
                                <option value="<?= $emp['sid']?>"><?= ucwords($emp['first_name'].' '.$emp['last_name'])." (".$emp['email'].")"?></option>
                            <?php }?>
                        </select>
                        <input class="btn btn-success pull-right" type="button" id="merge-emp" value="Merge">
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

<?php if(sizeof($w4_form)>0) { ?>
    <div id="w4_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned W4 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <div class="table-responsive">
                        <div class="container">
                            <?php $view = get_form_view('w4',$w4_form);
                            echo $view; ?>
                        </div>
                    </div>        
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(sizeof($w9_form)>0) { ?>
    <div id="w9_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned W9 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <div class="table-responsive">
                        <div class="container-fluid">
                            <?php $view = get_form_view('w9',$w9_form);
                            echo $view; ?>
                        </div>
                    </div>        
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(sizeof($i9_form)>0) { ?>
    <div id="i9_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned I9 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <div class="table-responsive">
                        <div class="container-fluid">
                            <?php $view = get_form_view('i9',$i9_form);
                            echo $view; ?>
                        </div>
                    </div>
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    function fSendFullEmploymentForm(){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to send a Full Employment Application to this Applicant?',
            function () {
                $('#form_send_full_employment_application').submit();
            },
            function () {
                //Cancel
            }
        );
    }

    function file_remove(id, type) {
        url = "<?= base_url() ?>applicant_profile/delete_file";
        alertify.confirm('Confirmation', "Are you sure you want to delete this " + type + "?",
            function () {
                $.post(url, {type: type, id: id})
                    .done(function (data) {
                        location.reload();
                    });
            },
            function () {

            });
    }

    $(document).ready(function () {

        $('#trigger-review').click(function(){
            $('#tab5_nav').click();
        });

        $('#attachment_edit_button').click(function (event) {
            event.preventDefault();
            $('#attachment_edit').fadeIn();
            $('#attachment_view').hide();
        });

        $('#attachment_view_button').click(function (event) {
            event.preventDefault();
            $('#attachment_edit').hide();
            $('#attachment_view').fadeIn();
        });

        $('#w4_modal').find('div.container').removeClass('container');
        $('#employees-list').select2();

        $(document).on('click','#merge-emp',function(){
            var _this = $(this);
            var emp = $('#employees-list').val();
            var app = '<?= $applicant_info['sid']?>';
            var email = '<?= $applicant_info['email']?>';
            if(emp == '' || emp == undefined){
                alertify.alert('Message','Please Select Employee To Merge!');
                return false;
            }
            $('#submit-loader').show();
            _this.attr('disabled','disabled');
            $.ajax({
                type:'POST',
                url:'<?= base_url("hire_onboarding_applicant/merge_applicant_with_employee")?>',
                data:{
                    employee: emp,
                    applicant: app,
                    email: email,
                    company_sid: '<?= $this->session->userdata('logged_in')['company_detail']['sid']?>'
                },
                success: function(resp){
                    $('#submit-loader').hide();
                    _this.removeAttr('disabled');
                    var result = JSON.parse(resp);
                    if(result.status == 'error'){
                        alertify.alert(result.message);
//                        $('#merge_modal').modal('toggle');
                    }else{
                        window.location.href = '<?= base_url("employee_profile").'/'?>'+emp;
                    }
                },
                error: function(){

                }
            })
        });

//        $('#w9_modal').find('div.container').removeClass('container');
    });

    function fSendAddReferencesRequestEmail(iApplicantID) {
        alertify.confirm(
            'Are You Sure?',
            'Are You Sure You Want to Send Add References Request Email to This Applicant?',
            function () {
                // console.log('OK.' + iApplicantID);
                $('#form_request_references_' + iApplicantID).submit();
            },
            function () {
                //Cancel Scripts
            }).set({
                labels: {
                    ok: 'Yes!'
                }
            });
    }

    function fSendKpaOnboardingEmail(iApplicantID) {
        alertify.confirm(
            'Are You Sure?',
            'Are You Sure You Want to Send Outsourced HR Onboarding Request Email to This Applicant?',
            function () {
                $('#form_kpa_onboarding_' + iApplicantID).submit();
            },
            function () {
                //Cancel Scripts
            }).set({
                labels: {
                    ok: 'Yes!'
                }
            });
    }

    $('.confirmation').on('click', function (e) {
        // var url = $("#send_resume_request_form").attr('action');
        alertify.confirm(
        'Confirm',
        'Are you sure you want to send a resume request to this applicant?',
        function () { 
            console.log("form submit ");
            $("#send_resume_request_form").submit();
            // window.location.replace(url);
        },
        function () {
            alertify.error('Cancelled!');
        });
        
    });

    $('.confirmation_old').on('click', function () {
        var url = $(this).attr('src');
        alertify.confirm(
        'Are you sure?',
        'Are You Sure You Want to Send Resume Request to This Applicant?',
        function () {
            window.location.replace(url);
        },
        function () {
            alertify.error('Cancelled!');
        });
        
    });

    function fun_hire_applicant () {
        alertify.confirm(
            'Are you Sure?',
            'By selecting this option the Candidate will skip the onboarding process. Are you sure you want to directly hire this Candidate?',
            function () {
                var hiring_url = "<?php echo base_url('hire_onboarding_applicant/hire_applicant_manually'); ?>";

                $.ajax({
                    type: 'POST',
                    data:{
                       applicant_sid: '<?php echo isset($applicant_info['sid']) && !empty($applicant_info['sid']) ? $applicant_info['sid'] : ""; ?>',
                       applicant_job_sid: '<?php echo isset($job_list_sid) && !empty($job_list_sid) ? $job_list_sid : ""; ?>',
                       company_sid: '<?php echo isset($company_sid) && !empty($company_sid) ? $company_sid : ""; ?>'
                    },
                    url: hiring_url,
                    success: function(data){
                        data = JSON.parse(data);
                        if (data.status == 'success') {
                            alertify.alert('Applicant is successfully hired!');
                            window.location.href = '<?php echo base_url("employee_profile"); ?>/'+data.adid;
                            // setTimeout(function(){
                            //     window.location.href = '<?php //echo base_url("application_tracking_system/active/all/all/all/all/all/all/all/all/all"); ?>';
                            // }, 1000);
                        } else if (data.status == 'failure_e') {
                            alertify.alert('Error! The E-Mail address of the applicant is already registered at your company as employee!');
                        } else if (data.status == 'failure_f') {
                            alertify.alert('Could not found applicant data, Please try again!');
                        } else if (data.status == 'failure_i') {
                            alertify.alert('Could not move applicant to employee due to database error, Please try again!');
                        } else if (data.status == 'error') {
                            alertify.alert('Could not found applicant information, Please try again!');
                        }
                    },
                    error: function(){

                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            }).set('labels', {ok: 'YES!', cancel: 'NO'});
    }

    $("#job_sid_upload").on('change',function(){
            var job_type = $("#job_sid_upload option:selected").attr('data-type');
            document.getElementById("job_type_upload").value = job_type;
    });

    $("#job_sid_send").on('change',function(){
            var job_type = $("#job_sid_send option:selected").attr('data-type');
            document.getElementById("job_type_send").value = job_type;
    });
</script>

<script>
    window.sre = {};
    window.sre.url = "<?=base_url();?>";
    window.sre.userId = <?=$applicant_info['sid'];?>;
    window.sre.userType = 'applicant';
</script>

<style>.select2-container--default .select2-selection--multiple .select2-selection__rendered{ height: auto !important; }</style>

<script>
    $(function RevertOnboarding(){
        
        $('.jsRevertOnboarding').click(function(event){
            //
            event.preventDefault();
            //
            var aid = $(this).data('id');
            //
            return alertify.confirm(
                'This action will remove the applicant from onboarding. <br /> Do you wish to continue?',
                function(){
                    removeApplicantFromOnboarding(aid);
                },
                function(){}
            ).setHeader('Confirm');
        });

        function removeApplicantFromOnboarding(aid){
            //
            $.ajax({
                method: "DELETE",
                url: "<?=base_url("revert_applicant/");?>/"+aid
            })
            .success(function(response){
                return alertify.alert(
                    'Success!',
                    response.Response,
                    function(){
                        window.location.reload();
                    }
                );
            })
            .fail(function(){
                return alertify.alert('Error!', 'Something went wrong while processing your request.', function(){});
            });
        }
    });
</script>