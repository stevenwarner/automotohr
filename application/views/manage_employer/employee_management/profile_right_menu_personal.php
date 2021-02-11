<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <aside class="side-bar">
        <a href="<?php echo base_url('my_settings') ?>">
            <header class="sidebar-header">
                <h1>Settings</h1>
            </header>
        </a>
        <div class="widget-wrp">
            <div class="hr-widget">
                <div class="browse-attachments">
                    <ul>
                        <li>
                            <h4>My Profile</h4>
                            <a href="<?php echo base_url('my_profile'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>                            
                        </li>
                        <!--<li>
                            <h4>Clock My Day</h4>
                            <a href="<?php /*echo base_url('attendance/my_day'); */?>">View<i class="fa fa-chevron-circle-right"></i></a>
                        </li>-->
                        <li>
                            <h4>Login Credentials</h4>
                            <a href="<?php echo base_url('login_password'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>              
                        </li>
                        <li>
                            <h4>EEOC Form</h4>
                            <a href="<?php echo base_url('eeo/form'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>
                        </li>
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
                                <a href="<?php echo base_url('full_employment_application') ; ?>">View<i class="fa fa-chevron-circle-right"></i></a>
                                <?php $user_info = $this->session->userdata('logged_in');?>
                                    <?php $employer_sid = $user_info['employer_detail']['sid'];?>
                                    <?php $full_employment_application_status = get_full_employment_application_status($employer_sid, 'employee'); ?>

                                    <?php if($full_employment_application_status == 'signed') { ?>
                                        <img title="Signed" data-toggle="tooltip" data-placement="top" class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                    <?php } else { ?>
                                        <img title="Unsigned" data-toggle="tooltip" data-placement="top" class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                    <?php } ?>
                            </li>
                            <li>
                                <span class="left-addon">
                                    <i class="fa fa-ambulance"></i>
                                </span>
                                <h4>Emergency Contacts</h4>
                                <a href="<?php echo base_url('emergency_contacts') ; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $emergency_contacts_count = count_emergency_contacts($employer_sid);  ?>
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
                                <a href="<?php echo base_url('occupational_license_info') ; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $occ_licenses_count = count_licenses($employer_sid, 'occupational'); ?>
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
                                <a href="<?php echo base_url('drivers_license_info') ; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $drv_licenses_count = count_licenses($employer_sid, 'drivers'); ?>
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
                                <a href="<?php echo base_url('equipment_info'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $equipments_count = count_equipments($employer_sid); ?>
                                <?php if(intval($equipments_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Equipments Assigned" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Equipments Assinged" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->

                            </li>
<!--                            <li>
                                <h4>W4 form and Tax withholding</h4>
                                <a href="javascript:;">View<i class="fa fa-chevron-circle-right"></i></a>
                            </li>-->
<!--                            <li>
                                <h4>i9 Employment Verification</h4>
                                <a href="<?php //echo base_url('i9form') . '/applicant/' . $applicant_info['sid']; ?>">View<i class="fa fa-chevron-circle-right"></i></a>
                            </li>-->
                            <li>
                                <span class="left-addon">
                                    <i class="fa fa-child"></i>
                                </span>
                                <h4>Dependents</h4>
                                <a href="<?php echo base_url('dependants') ; ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $dependant_count = count_dependants($employer_sid); ?>
                                <?php if(intval($dependant_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Dependents" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Dependents Information Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->

                            </li>
                            <li>
                                <span class="left-addon">
                                    <i class="fa fa-file-text"></i>
                                </span>
                                <h4>HR Documents</h4>
<!--                                <a href="--><?php //echo base_url('documents_management/my_documents'); ?><!--">View<i class="fa fa-chevron-circle-right"></i></a>-->
                                <a href="<?php echo base_url('my_hr_documents'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $show_hr_documents_bulb = show_hr_documents_light_bulb($employer_sid); ?>
                                <?php if($show_hr_documents_bulb == true) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="All Documents Acknowledged" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Documents Acknowledgment Pending" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->

                            </li>
                            <li>
                                <span class="left-addon">
                                    <i class="fa fa-group"></i>
                                </span>
                                <h4>My Referral Network</h4>
                                <a href="<?php echo base_url('my_referral_network'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $referrals_count = count_referrals($employer_sid); ?>
                                <?php if(intval($referrals_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Referred Jobs" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Referrals Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->

                            </li>
                            <li>
                                <span class="left-addon"><i class="fa fa-bank"></i></span>
                                <h4>Direct Deposit Information</h4>
                                <a href="<?php echo base_url('direct_deposit'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $direct_deposit_count = count_direct_deposit($employer_sid); ?>
                                <?php if(intval($direct_deposit_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="Has Referred Jobs" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="No Referrals Found" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->

                            </li>
                            <?php
                                $session= $this->session->userdata('logged_in'); 
                                $ems_status = $session["company_detail"]["ems_status"]; 
                            ?> 

                            <?php if($ems_status == 1) { ?>
                            <li>
                                <span class="left-addon"><i class="fa fa-pencil"></i></span>
                                <h4>E-Signature</h4>
                                <a href="<?php echo base_url('e_signature'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                <!-- Light Bulb Code - Start -->
                                <?php $e_signature_count = count_e_signature('employee', $employer_sid); ?>
                                <?php if(intval($e_signature_count) > 0) { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="E-Signature Not Added" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                <?php } else { ?>
                                    <img class="img-responsive pull-right" style=" width: 22px; height: 22px; margin-right:5px;" title="E-Signature Added" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                <?php } ?>
                                <!-- Light Bulb Code - End -->

                            </li>
                            <?php } ?>
                            <li>
                                <span class="left-addon"><i class="fa fa-book"></i></span>
                                <h4>Learning Center</h4>
                                <a href="<?php echo base_url('learning_center/my_learning_center'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                            </li>

                            <?php $incident = $this->session->userdata('incident_config'); if($incident > 0){ ?>
                                <li>
                                    <span class="left-addon"><i class="fa fa-newspaper-o"></i></span>
                                    <h4>Report An incident</h4>
                                    <a href="<?php echo base_url('incident_reporting_system/'); ?>">View<i class="fa fa-chevron-circle-right"></i></a>

                                </li>
<!--                                <li>-->
<!--                                    <span class="left-addon"><i class="fa fa-book"></i></span>-->
<!--                                    <h4>Assigned Incident</h4>-->
<!--                                    <a href="--><?php //echo base_url('incident_reporting_system/assigned_incidents/'); ?><!--">View<i class="fa fa-chevron-circle-right"></i></a>-->
<!---->
<!--                                </li>-->
                            <?php }?>
                        </ul>
                </div>
            </div>
        </div>
    </aside>										
</div>