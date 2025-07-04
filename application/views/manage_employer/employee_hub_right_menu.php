<div class="page-header">
    <h2 class="section-ttile">&nbsp;</h2>
</div>
<div class="box-widget">
    <div class="widget-links">
        <ul>
<!--                <li>-->
<!--                    <h4>Dashboard</h4>-->
<!--                    <a href="--><?php //echo base_url('dashboard'); ?><!--">Browse<i class="fa fa-chevron-circle-right"></i></a>-->
<!--                </li>-->
    <?php   if(in_array('login_password', $security_details) || check_access_permissions_for_view($security_details,'login_password')) { ?>
                <li>
                    <h4>Login Credentials</h4>
                    <a href="<?php echo base_url('login_password'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                </li>
    <?php   } ?>
            <li>
                <h4>HR Documents</h4>
                <a href="<?php echo base_url('hr_documents_management/my_documents'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
            </li>
    <?php   if((in_array('my_events', $security_details) || check_access_permissions_for_view($security_details,'my_events')) && $session['employer_detail']['access_level'] == 'Employee') { ?>
                <li>
                    <h4>Calendar</h4>
                    <a href="<?php echo base_url('calendar/my_events'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                </li>
    <?php   } ?>
    <?php   if(in_array('employee_emergency_contacts', $security_details) || check_access_permissions_for_view($security_details,'employee_emergency_contacts')) { ?>
                <li>
                    <h4>Emergency Contacts</h4>
                    <a href="<?php echo base_url('emergency_contacts'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                </li>
    <?php   } ?>
    <?php   if(in_array('employee_occupational_license_info', $security_details) || check_access_permissions_for_view($security_details,'employee_occupational_license_info')) { ?>
        <li>
            <h4>Occupational License Info</h4>
            <a href="<?php echo base_url('occupational_license_info'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
        </li>
    <?php   } ?>
    <?php   if(in_array('employee_drivers_license_info', $security_details) || check_access_permissions_for_view($security_details,'employee_drivers_license_info')) { ?>
        <li>
            <h4>Drivers License Info</h4>
            <a href="<?php echo base_url('drivers_license_info'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
        </li>
    <?php   } ?>
    <?php   if(in_array('employee_dependants', $security_details) || check_access_permissions_for_view($security_details,'employee_dependants')) { ?>
                <li>
                    <h4>Dependants</h4>
                    <a href="<?php echo base_url('dependants'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                </li>
    <?php   } ?>
            <li>
                <h4>Direct Deposit</h4>
                <a href="<?php echo base_url('direct_deposit'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
            </li>

            <li>
                <h4>E Signature</h4>
                <a href="<?php echo base_url('e_signature'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
            </li>
    <?php   //if(in_array('employee_dependants', $security_details)) { ?>

    <?php   //} ?>
    <?php   if(in_array('my_referral_network', $security_details) || check_access_permissions_for_view($security_details,'my_referral_network')) { ?>
                <li>
                    <h4>My Referral Network</h4>
                    <a href="<?php echo base_url('my_referral_network'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                </li>
    <?php   } ?>
    <?php $incident = $this->session->userdata('incident_config'); if($incident > 0){ ?>
            <li>
                <h4>Report An Incident</h4>
                <a href="<?php echo base_url('incident_reporting_system/'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
            </li>
<!--            <li>
                <h4>Assigned Incidents</h4>
                <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
            </li>-->
    <?php }?>
    <?php if($session['employer_detail']['access_level'] == 'Employee'){?>
            <li>
                <h4>Private Messages</h4>
                <a href="<?php echo base_url('private_messages'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
            </li>
    <?php }?>
            <li>
                <h4>Announcements</h4>
                <a href="<?php echo base_url('list_announcements'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
            </li>
            <li>
                <h4>Learning Center</h4>
                <a href="<?php echo base_url('learning_center/my_learning_center'); ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
            </li>
        </ul>
    </div>
</div>