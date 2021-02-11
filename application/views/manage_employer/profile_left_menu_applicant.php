<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('dashboard')) {
                echo 'class="active"';
            }
            ?> 
                href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('application_tracking_system')) {
                echo 'class="active"';
            }
            ?> 
                href="<?php echo base_url('application_tracking_system/active/all/all/all/all') ?>">
                <figure><i class="fa fa-line-chart"></i></figure>Application Tracking</a>
        </li>
        <li>
            <a <?php
            if ($this->uri->segment(1) == 'applicant_profile') {
                echo 'class="active"';
            }
            ?> 
                href="<?php echo base_url('applicant_profile') . '/' . $employer["sid"]; ?>">
                <figure><i class="fa fa-child"></i></figure>Personal Details</a>
        </li>
        <?php if ($full_access) { ?>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'applicant_full_employment_application') {
                    echo 'class="active"';
                }
                ?> 
                    href="<?php echo base_url('applicant_full_employment_application') . '/' . $employer["sid"]; ?>">
                    <figure><i class="fa fa-file-text"></i></figure>Full Employment Application</a>
            </li>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'background_check' || $this->uri->segment(1) == 'background_report') {
                    echo 'class = "active"';
                }
                ?> 
                    href="<?php echo base_url('background_check') . '/applicant/' . $employer["sid"]; ?>">
                    <figure><i class="fa fa-flag-checkered"></i></figure>Background Check</a>
            </li>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'drug_test' || $this->uri->segment(1) == 'drug_report') {
                    echo 'class = "active"';
                }
                ?> 
                    href="<?php echo base_url('drug_test') . '/applicant/' . $employer["sid"]; ?>">
                    <figure><i class="fa fa-medkit"></i></figure>Drug Test</a>
            </li>
            <li>
                <a <?php
                if (base_url(uri_string()) == site_url('1')) {
                    echo 'class = "active"';
                }
                ?> 
                    href="javascript:;<?php //echo base_url('login_password')                    ?>">
                    <figure><i class="fa fa-paperclip"></i></figure>WOTC New HireTax Credits</a>
            </li>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'emergency_contacts' || $this->uri->segment(1) == 'add_emergency_contacts' || $this->uri->segment(1) == 'edit_emergency_contacts') {
                    echo 'class = "active"';
                }
                ?> 
                    href="<?php echo base_url('emergency_contacts') . '/applicant/' . $employer["sid"]; ?>">
                    <figure><i class="fa fa-ambulance"></i></figure>Emergency Contacts</a>
            </li>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'occupational_license_info') {
                    echo 'class = "active"';
                }
                ?> 
                    href="<?php echo base_url('occupational_license_info') . '/applicant/' . $employer['sid']; ?>">
                    <figure><i class="fa fa-arrow-circle-right"></i></figure>Occupational License Info</a>
            </li>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'drivers_license_info') {
                    echo 'class = "active"';
                }
                ?> 
                    href="<?php echo base_url('drivers_license_info') . '/applicant/' . $employer['sid']; ?>">
                    <figure><i class="fa fa-car"></i></figure>Drivers License Info</a>
            </li>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'equipment_info') {
                    echo 'class = "active"';
                }
                ?> 
                    href="<?php echo base_url('equipment_info') . '/applicant/' . $employer['sid']; ?>">
                    <figure><i class="fa fa-laptop"></i></figure>Equipment Info</a>
            </li>
            <li>
                <a <?php
                if (base_url(uri_string()) == site_url('1')) {
                    echo 'class = "active"';
                }
                ?> href="javascript:;<?php //echo base_url('login_password')   ?>">
                    <figure><i class="fa fa-files-o"></i></figure>W4 form and Tax withholding</a>
            </li>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'i9form') {
                    echo 'class = "active"';
                }
                ?> 
                    href="<?php echo base_url('i9form') ?>">
                    <figure><i class="fa fa-pencil-square"></i></figure>i9 Employment Verification</a>
            </li>
            <li>
                <a <?php
                if ($this->uri->segment(1) == 'dependants' || $this->uri->segment(1) == 'add_dependant_information' || $this->uri->segment(1) == 'edit_dependant_information') {
                    echo 'class = "active"';
                }
                ?> 
                    href="<?php echo base_url('dependants') . '/applicant/' . $employer['sid']; ?>">
                    <figure><i class="fa fa-user-plus"></i></figure>Dependents</a>
            </li>
            <!--
            <li>
                <a <?php
                if (base_url(uri_string()) == site_url('1')) {
                    echo 'class = "active"';
                }
                ?> 
                    href="javascript:;<?php //echo base_url('login_password')                    ?>">
                    <figure><i class="fa fa-thumbs-o-up"></i></figure>Benefit Elections</a>
            </li>

            <li>
                <a <?php
                if (base_url(uri_string()) == site_url('1')) {
                    echo 'class = "active"';
                }
                ?> 
                    href="javascript:;<?php //echo base_url('login_password')                    ?>">
                    <figure><i class="fa fa-dollar"></i></figure>Payroll</a>
            </li>
            -->
            <li>
                <a <?php
                    $uriSegmentOne = $this->uri->segment(1);
                if ($uriSegmentOne == 'reference_checks' || $uriSegmentOne == 'reference_checks_questionnaire' || $uriSegmentOne == 'add_reference_checks' || $uriSegmentOne == 'edit_reference_checks' ) {
                    echo 'class="active"';
                }
                ?> href="<?php echo base_url('reference_checks/applicant') . '/' . $employer["sid"]; ?>">
                    <figure><i class="fa fa-child"></i></figure>Reference Checks</a>
            </li>
        <?php } ?>
    </ul>
</div> 