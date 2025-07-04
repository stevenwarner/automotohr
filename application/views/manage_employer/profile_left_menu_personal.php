<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('dashboard')) {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('my_settings')) {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('my_settings') ?>">
                <figure><i class="fa fa-sliders"></i></figure>Setting</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('my_profile')) {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('my_profile') ?>">
                <figure><i class="fa fa-child"></i></figure>Personal Details</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('login_password')) {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('login_password') ?>">
                <figure><i class="fa fa-unlock-alt"></i></figure>Login Credentials</a>
        </li><!--fa fa-unlock-alt    fa fa-unlock"-->
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('full_employment_application')) {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('full_employment_application') ?>">
                <figure><i class="fa fa-file-text"></i></figure>Full Employment Application</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('1')) {
                echo 'class="active"';
            }
            ?> 
                href="javascript:;<?php //echo base_url('login_password')    ?>">
                <figure><i class="fa fa-paperclip"></i></figure>WOTC New HireTax Credits</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('emergency_contacts') || $this->uri->segment(1) == 'add_emergency_contacts' || $this->uri->segment(1) == 'edit_emergency_contacts') {
                echo 'class="active"';
            }
            ?> 
                href="<?php echo base_url('emergency_contacts') ?>">
                <figure><i class="fa fa-ambulance"></i></figure>Emergency Contacts</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('occupational_license_info')) {
                echo 'class="active"';
            }
            ?>href="<?php echo base_url('occupational_license_info'); ?>">
                <figure><i class="fa fa-arrow-circle-right"></i></figure>Occupational License Info</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('drivers_license_info')) {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('drivers_license_info'); ?>">
                <figure><i class="fa fa-car"></i></figure>Drivers License Info</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('equipment_info')) {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('equipment_info'); ?>">
                <figure><i class="fa fa-laptop"></i></figure>Equipment Info</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('1')) {
                echo 'class="active"';
            }
            ?> href="javascript:;<?php //echo base_url('login_password')   ?>">
                <figure><i class="fa fa-files-o"></i></figure>W4 form and Tax withholding</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('i9form')) {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('i9form') ?>">
                <figure><i class="fa fa-pencil-square"></i></figure>i9 Employment Verification</a>
        </li>
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('dependants') || $this->uri->segment(1) == 'add_dependant_information' || $this->uri->segment(1) == 'edit_dependant_information') {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('dependants'); ?>">
                <figure><i class="fa fa-user-plus"></i></figure>Dependents</a>
        </li>
        <!--
        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('1')) {
                echo 'class="active"';
            }
            ?> href="javascript:;<?php //echo base_url('login_password')   ?>">
                <figure><i class="fa fa-thumbs-o-up"></i></figure>Benefit Elections</a>
        </li>

        <li>
            <a <?php
            if (base_url(uri_string()) == site_url('1')) {
                echo 'class="active"';
            }
            ?> href="javascript:;<?php //echo base_url('login_password')   ?>">
                <figure><i class="fa fa-dollar"></i></figure>Payroll</a>
        </li>
        -->

        <li>
            <a <?php
            if ($this->uri->segment(1) == 'reference_checks') {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('reference_checks') ?>">
                <figure><i class="fa fa-child"></i></figure>Reference Checks</a>
        </li>
         <li>
            <a <?php
            if ($this->uri->segment(1) == 'my_hr_documents') {
                echo 'class="active"';
            }
            ?> href="<?php echo base_url('my_hr_documents') ?>">
                <figure><i class="fa fa-child"></i></figure>HR Documents</a>
        </li>
    </ul>
</div> 