<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <aside class="side-bar">
        <a href="<?php echo base_url('application_tracking_system/active/all/all/all/all') ?>">
            <header class="sidebar-header">
                <h1>Application Tracking</h1>
            </header>
        </a>
        <div class="next-applicant">
            <ul>
                <li class="previous-btn"><a href="<?php echo $prev_app ?>"><i class="fa fa-chevron-left"></i>Prev</a></li>
                <li class="next-btn"><a href="<?php echo $next_app ?>">next<i class="fa fa-chevron-right"></i></a></li>
            </ul>
        </div>
        <div class="widget-wrp">
            <div class="hr-widget">
                <h2>Rate This Applicant</h2>
                <div class="start-rating">
                    <form action="<?php echo base_url('applicant_profile/save_rating'); ?>" method="post" >
                        <input type="hidden" name="applicant_job_sid" value="<?= $id ?>" >
                        <input type="hidden" name="applicant_email" value="<?= $email ?>" >
                        <input id="input-21b" <?php if (!empty($applicant_rating)) { ?> value="<?php echo $applicant_rating['rating']; ?>" <?php } ?> type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                        <div class="rating-comment">
                            <h4>comment<samp class="red"> * </samp></h4>
                            <textarea name="comment" required><?php if (!empty($applicant_rating)){ echo $applicant_rating['comment']; } ?></textarea>
                            <input type="submit" value="submit">
                        </div>
                    </form>
                </div>
            </div>
<!--            <div class="hr-widget">
                <div class="applicant-status">
                    <div class="info-area">
                        <h2>Status</h2>
                        <div class="label-wrapper-outer">
                            <?php if($have_status == false) { ?>
                                <?php if ($applicant_info["status"] == 'Contacted') { ?>
                                    <div class="selected contacted"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Candidate Responded') { ?>
                                    <div class="selected responded"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Qualifying') { ?>
                                    <div class="selected qualifying"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Submitted') { ?>
                                    <div class="selected submitted"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Interviewing') { ?>
                                    <div class="selected interviewing"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Offered Job') { ?>
                                    <div class="selected offered"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Not In Consideration') { ?>
                                    <div class="selected notin"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Client Declined') { ?>
                                    <div class="selected decline"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Placed/Hired' || $applicant_info["status"] == 'Ready to Hire') { ?>
                                    <div class="selected placed">Ready to Hire</div>
                                <?php } elseif ($applicant_info["status"] == 'Not Contacted Yet') { ?>
                                    <div class="selected not_contacted"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Future Opportunity') { ?>
                                    <div class="selected future_opportunity"><?= $applicant_info["status"] ?></div>
                                <?php } elseif ($applicant_info["status"] == 'Left Message') {?>
                                    <div class="selected left_message"><?= $applicant_info["status"] ?></div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="selected <?php echo (isset($applicant_info['status_css_class'])) ? $applicant_info['status_css_class'] : ''; ?>">
                                    <?php echo (isset($applicant_info['status_name'])) ? $applicant_info['status_name'] : ''; ?>
                                </div>
                            <?php } ?>
                            <div class="lable-wrapper">
                                <div id="id" style="display:none;"><?= $applicant_info['sid'] ?></div>
                                <div style="height:20px;"><i class="fa fa-times cross"></i></div>
                                <?php if($have_status == false) { ?>
                                    <div class="label applicant not_contacted">
                                        <div id="status">Not Contacted Yet</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant left_message">
                                        <div id="status">Left Message</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant contacted">
                                        <div id="status">Contacted</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant responded">
                                        <div id="status">Candidate Responded</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant interviewing">
                                        <div id="status">Interviewing</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant submitted">
                                        <div id="status">Submitted</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant qualifying">
                                        <div id="status">Qualifying</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant placed">
                                        <div id="status">Ready to Hire</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant offered">
                                        <div id="status">Offered Job</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant decline">
                                        <div id="status">Client Declined</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant notin">
                                        <div id="status">Not In Consideration</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                    <div class="label applicant future_opportunity">
                                        <div id="status">Future Opportunity</div>
                                        <i class="fa fa-check-square check"></i>
                                    </div>
                                <?php } else { ?>
                                    <?php foreach ($company_statuses as $status) { ?>
                                        <div class="label applicant <?php echo $status['css_class']; ?>">
                                            <div id="status"><?php echo $status['name']; ?></div>
                                            <i class="fa fa-check-square check"></i>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="hr-widget">
                <div class="applicant-status">
                    <div class="info-area">
                        <h2>Job Fit Categories</h2>
                        <?php if(!empty($applicant_info['job_fit_categories'])) { ?>
                            <?php foreach($applicant_info['job_fit_categories'] as $category) { ?>
                                <span class="custom-label"><?php echo $category['value']; ?></span>
                            <?php } ?>
                        <?php } else { ?>
                            <span class="no-data">No Job Fit Category Set</span>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="hr-widget">
                <div class="browse-attachments">
                    <ul>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <h4>Applicant Profile</h4>
                            <a href="<?php echo base_url('applicant_profile') . '/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                            <!-- Light Bulb Code - Start -->
                            <?php if(false) { ?>
                                <?php if(true) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Title" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Title" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-check"></i>
                            </span>
                            <h4>Background Check</h4>
                            <?php   $_SESSION['applicant_id'] = $applicant_info['sid'];
                                    $_SESSION['applicant_type'] = 'applicant_profile';
                                    if ($company_background_check == 1) { ?>
                                        <a href="<?php echo base_url('background_check') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>
                            <?php   } else { ?>
                                        <a href="<?php echo base_url('background_check/activate') ?>">View<i class="fa fa-chevron-circle-right"></i></a>
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
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-medkit"></i>
                            </span>
                            <h4>Drug Testing</h4>
                            <?php   if ($company_background_check == 1) { ?>
                                        <a href="<?php echo base_url('drug_test') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>
                            <?php   } else {
                                        $_SESSION['applicant_id'] = $applicant_info['sid']; ?>
                                        <a href="<?php echo base_url('background_check/activate') ?>">View<i class="fa fa-chevron-circle-right"></i></a>
                            <?php   } ?>

                            <!-- Light Bulb Code - Start -->
                            <?php $background_check_count = count_accurate_background_orders($applicant_info['sid'], 'drug-testing'); ?>
                            <?php if(intval($background_check_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Background Check Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Background Check Not Processed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <!--<li>
                            <h4>Behavioral Assessment</h4>
                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-link"></i>
                            </span>
                            <h4>Reference Check</h4>
                            <a href="<?php echo base_url('reference_checks') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                            <!-- Light Bulb Code - Start -->
                            <?php $references_count = count_references_records($applicant_info['sid']);?>
                            <?php if(intval($references_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has References Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No References Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <!--<li>
                            <h4>Skills Test</h4>
                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        </li>
                        <li>
                            <h4>Video Interview</h4>
                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <!--<li>
                            <h4>Add Schedule</h4>
                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-link"></i>
                            </span>
                            <form action="<?php echo base_url('applicant_profile/send_reference_request_email'); ?>" method="post" id="form_request_references_<?php echo $applicant_info['sid']; ?>">
                                <input type="hidden" id="perform_action" name="perform_action" value="send_add_reference_request_email" />
                                <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_info['sid']; ?>" />
                            </form>
                            <h4>References Request</h4>
                            <a href="javascript:;" onclick="fSendAddReferencesRequestEmail(<?php echo $applicant_info['sid']; ?>);">Send<i class="fa fa-chevron-circle-right"></i></a>

                            <!-- Light Bulb Code - Start -->
                            <?php $reference_check_request_status = get_reference_checks_request_sent_status($applicant_info['sid']); ?>

                            <?php if($reference_check_request_status == 'sent') { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>

                            <!-- Light Bulb Code - End -->
                        </li>
                        <?php if($kpa_onboarding_check == 1) { ?>
                            <li>
                                <span class="left-addon">
                                    <i class="fa fa-cog"></i>
                                </span>
                                <h4>Outsourced HR Onboarding</h4>
                                <form action="<?php echo base_url('applicant_profile/send_kpa_onboarding'); ?>" method="post" id="form_kpa_onboarding_<?php echo $applicant_info['sid']; ?>">
                                    <input type="hidden"  name="kpa_action" value="send_kpa_onboarding_email" />
                                    <input type="hidden" name="applicant_sid" value="<?php echo $applicant_info['sid']; ?>" />
                                </form>
                                <a href="javascript:;" onclick="fSendKpaOnboardingEmail(<?php echo $applicant_info['sid']; ?>);">Send<i class="fa fa-chevron-circle-right"></i></a>
                                
                                <?php if($kpa_email_sent == true) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="hr-widget">
                <div class="browse-attachments">
                    <ul>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-file-text"></i>
                            </span>
                            <h4>Full Employment Application</h4>
                                <form id="form_send_full_employment_application" enctype="multipart/form-data" method="post" action="<?php echo base_url('form_full_employment_application/send_form'); ?>">
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $applicant_info['employer_sid']; ?>" />
                                <input type="hidden" id="user_type" name="user_type" value="applicant" />
                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $applicant_info['sid']; ?>" />
                            </form>
                            <a href="javascript:void(0);" onclick="fSendFullEmploymentForm();">Send<i class="fa fa-chevron-circle-right"></i></a>

                            <!-- Light Bulb Code - Start -->
                            <?php $full_emp_form_status = get_full_emp_app_form_status($applicant_info['sid'],'applicant'); ?>

                            <?php if($full_emp_form_status == 'sent' || $full_emp_form_status == 'signed') { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Not Sent" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-file-text"></i>
                            </span>
                            <h4>Full Employment Application</h4>
                            <a href="<?php echo base_url('applicant_full_employment_application') . '/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>
                                
                            <?php $full_employment_application_status = get_full_employment_application_status($applicant_info['sid'], 'applicant'); ?>

                            <?php if($full_employment_application_status == 'signed') { ?>
                                <img title="Signed" style="width: 22px; height: 22px; margin-right:5px;" data-toggle="tooltip" data-placement="top" class="img-responsive pull-right" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img title="Unsigned" style="width: 22px; height: 22px; margin-right:5px;" data-toggle="tooltip" data-placement="top" class="img-responsive pull-right" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                                
                        </li>
                        <!--<li>
                            <h4>WOTC New Hire Tax Credits</h4>
                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-ambulance"></i>
                            </span>
                            <h4>Emergency Contacts</h4>
                            <a href="<?php echo base_url('emergency_contacts') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>


                            <!-- Light Bulb Code - Start -->
                            <?php $emergency_contacts_count = count_emergency_contacts($applicant_info['sid']);  ?>
                            <?php if(intval($emergency_contacts_count > 0)) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Emergency Contacts Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Emergency Contacts Setup" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->
                        </li>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-industry"></i>
                            </span>
                            <h4>Occupational License Info</h4>
                            <a href="<?php echo base_url('occupational_license_info') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                            <!-- Light Bulb Code - Start -->
                            <?php $occ_licenses_count = count_licenses($applicant_info['sid'], 'occupational'); ?>
                            <?php if(intval($occ_licenses_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Occupational License Saved" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Occupational License Information" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->

                        </li>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-automobile"></i>
                            </span>
                            <h4>Drivers License Info</h4>
                            <a href="<?php echo base_url('drivers_license_info') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                            <!-- Light Bulb Code - Start -->
                            <?php $drv_licenses_count = count_licenses($applicant_info['sid'], 'drivers'); ?>
                            <?php if(intval($drv_licenses_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Drivers License Saved" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Drivers License Information" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->

                        </li>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-laptop"></i>
                            </span>
                            <h4>Equipment Info</h4>
                            <a href="<?php echo base_url('equipment_info') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                            <!-- Light Bulb Code - Start -->
                            <?php $equipments_count = count_equipments($applicant_info['sid']); ?>
                            <?php if(intval($equipments_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Equipments Assigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Equipments Assinged" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->

                        </li>
                            <!--  <li>
                            <span class="left-addon">
                                <i class="fa fa-file-text"></i>
                            </span>
                            <h4>i9 Employment Verification</h4>
                            <a href="<?php echo base_url('i9form') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-child"></i>
                            </span>
                            <h4>Dependents</h4>
                            <a href="<?php echo base_url('dependants') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                            <!-- Light Bulb Code - Start -->
                            <?php $dependant_count = count_dependants($applicant_info['sid']); ?>
                            <?php if(intval($dependant_count) > 0) { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Dependents" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } else { ?>
                                <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Dependents Information Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } ?>
                            <!-- Light Bulb Code - End -->

                        </li>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-video-camera"></i>
                            </span>
                            <h4>Video Interview Questions</h4>
                            <!-- change to bulb to on/off state based on previous records whether the questions have been sent or not -->
                            <a href="<?php echo base_url() . 'video_interview_system/send/' . $applicant_info['sid'] . ((isset($job_list_sid)) ? '/'.$job_list_sid : ''); ?>" >Send<i class="fa fa-chevron-circle-right"></i></a>
                            <?php if ($questions_sent == false) { ?>
                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Send Video Interview Questions" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } else { ?>
                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Dependents" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } ?>
                        </li>
                        <li>
                            <span class="left-addon">
                                <i class="fa fa-video-camera"></i>
                            </span>
                            <h4>Video Interview Questions</h4>
                            <!-- change to bulb to on/off state based on previous records whether the questions have been sent or not -->
                            <a href="<?php echo base_url() . 'video_interview_system/responses/' . $applicant_info['sid'] . ((isset($job_list_sid)) ? '/'.$job_list_sid : ''); ?>" >View<i class="fa fa-chevron-circle-right"></i></a>
                            <?php if ($questions_answered == false) { ?>
                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Send Video Interview Questions" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                            <?php } else { ?>
                            <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Dependents" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                            <?php } ?>
                        </li>

                        <!--<li>-->
                            <!--<span class="left-addon">
                                <i class="fa fa-question-circle"></i>
                            </span>
                            <h4>Interview Questionnaire</h4>
                            <a href="<?php /*echo base_url('interview_questionnaires') . '/applicant/' . $applicant_info['sid']; */?>">View<i class="fa fa-chevron-circle-right"></i></a>
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
                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        </li>

                        <li>
                            <h4>Payroll</h4>
                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        </li>
                        -->
                    </ul>
                </div>
            </div>

            <div class="hr-widget" id="attachment_view" >
                <div class="attachment-header">
                    <div class="form-title-section">
                        <h4>Attachments</h4>
                        <div class="form-btns">
                            <input type="submit" value="edit" id="attachment_edit_button">
                        </div>                                              
                    </div>
                    <div class="file-container">
                        <a data-toggle="modal" data-target="#resume_modal" href="javascript:void(0);"  title="<?= $resume_link_title ?>">
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
                                <a class="remove-icon" href="javascript:void(0);" onclick="file_remove(<?= $applicant_info['sid'] ?>, 'Resume')"><i class="fa fa-remove"></i></a>
                            <?php } ?>
                        </div>
                        <h4>Resume</h4>
                        <div class="btn-inner">
                            <input type="file" name="resume" id="resume"   onchange="check_file('resume')" class="choose-file-filed"> 
                            <a href="" class="select-photo"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="attachment-header attachment_edit">
                        <div class="remove-file">
                            <p id="name_cover_letter"><?php echo substr($cover_letter_title, 0, 28); ?></p>
                            <?php if ($cover_letter_title != "No Cover Letter found!") { ?>
                                <a class="remove-icon" href="javascript:void(0);" onclick="file_remove(<?= $applicant_info['sid'] ?>, 'Cover Letter')"><i class="fa fa-remove"></i></a>
                            <?php } ?>
                        </div>
                        <h4>Cover Letter</h4>
                        <div class="btn-inner">
                            <input type="file"  id="cover_letter" name="cover_letter" onchange="check_file('cover_letter')" class="choose-file-filed"> 
                            <a href="" class="select-photo"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <input type="hidden" name="old_resume" id="action" value="<?= $applicant_info['resume'] ?>">
                    <input type="hidden" name="old_letter" id="action" value="<?= $applicant_info['cover_letter'] ?>">
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
                                <a href="" class="select-photo"><i class="fa fa-plus"></i></a>
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
                                        if($attachment['status'] != 'deleted') {
                                ?>
                                    <li>
                                        <h4><?php echo $attachment['original_name']; ?></h4>
                                        <div class="remove-file remove-icon">
                                            <a class="" href="javascript:void(0);" onclick="file_remove(<?= $attachment['sid'] ?>, 'file')"><i class="fa fa-remove"></i></a>
                                        </div>
                                        <a href="<?php echo AWS_S3_BUCKET_URL . $attachment['uploaded_name']; ?>">Download<i class="fa fa-chevron-circle-down"></i></a>
                                        
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
<div id="resume_modal" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Resume</h4>
            <?php   if ($resume_link_title != "No Resume found!") { ?>
                            <a href="<?= base_url('applicant_profile/downloadFile') ?>/<?= $resume_link_title ?>" download="download" >Download</a>
            <?php   } ?>
            </div>
            <?php   if($resume_link_title != "No Resume found!") { 
                        $type = explode(".",$resume_link_title);
                        $type = $type[1];
                    
                        if($type == 'png' || $type == 'jpg' || $type == 'jpe' || $type == 'jpeg' || $type == 'gif')  { ?>
                            <img src="<?php echo AWS_S3_BUCKET_URL . $resume_link_title; ?>" style="width:600px; height:500px;"/>
            <?php       } else  { ?>
                                    <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?= $applicant_info["resume_link"] ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
                    <?php       }                    
                    } else { ?>                
                        <span class="nofile-found">No Resume Found!</span>
            <?php   } ?>
        </div>
    </div>
</div>
<div id="cover_letter_modal" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cover Letter</h4>
                <?php if ($cover_letter_title != "No Cover Letter found!") { ?>
                    <a href="<?= base_url('applicant_profile/downloadFile') ?>/<?= $cover_letter_title ?>" download="download">Download</a>
                <?php } ?>
            </div>
            <?php if($cover_letter_title != "No Cover Letter found!") { 
                    $cover_type = explode(".",$cover_letter_title);
                    $cover_type = $cover_type[1];
                
                    if($cover_type == 'png' || $cover_type == 'jpg' || $cover_type == 'jpe' || $cover_type == 'jpeg' || $cover_type == 'gif')  {  ?>
                        <img src="<?php echo AWS_S3_BUCKET_URL . $cover_letter_title; ?>" style="width:600px; height:500px;"/>
              <?php } else { ?>
                        <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?= $applicant_info["cover_link"] ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
                    <?php } } else { ?>
                <span class="nofile-found">No Cover Letter Found!</span>
            <?php } ?>
        </div>
    </div>
</div>
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

        $('.selected').click(function () {
            $(this).next().css("display", "block");
        });
        
        $('.label').click(function () {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            var my_status = $(this).find('#status').html();
            var my_id = <?= $id ?>;
            var my_url = "<?= base_url() ?>/applicant_profile/updateEmployerStatus";

            console.log()
            var my_request;

            my_request = $.ajax({
                url :  my_url,
                type : "POST",
                data : { "sid" : my_id, "status" : my_status }
            });

            my_request.done(function (response) {
                console.log(response);
                alertify.success("Candidate status updated successfully.");
            });


            /*
            $.post(url, {sid : my_id, status : status})
                .done(function (data) {
                    alertify.success("Candidate status updated successfully.");
                });
                */
        });
        
        $('.label').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");
        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });
        
        $('.cross').click(function () {
            $(this).parent().parent().css("display", "none");
        });
        
        $.each($(".selected"), function () {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });
    });


    $('.cross').click(function () {
        $(this).parent().parent().css("display", "none");
    });
    
    $('.label').click(function () {
        $(this).parent().css("display", "none");
    });

    /*function myFunctionAjax() {
        $.ajax({
            url: "<?= base_url() ?>hire_applicant/hire_applicant",
            type: "POST",
            data: {
                id: "<?php echo $applicant_info['sid']; ?>",
                email: "<?php echo $applicant_info['email']; ?>",
                cid: "<?php echo $applicant_info['employer_sid']; ?>",
                action: "hire_now"
            },
            dataType: "json",
            success: function (response) {
                if (response[0] == 'error') {
                    alertify.error(response[1]);
                } else {
                    alertify.success(response[1]);
                    window.location.assign(response[2]);
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }*/
    
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
</script>