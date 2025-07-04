<?php $onbaording_section = $this->uri->segment(2); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <div class="dash-box margin-top-10">
            <div class="admin-info">
                <div class="profile-pic-area">
                    <figure>
                        <?php if(isset($applicant_info['pictures']) && !empty($applicant_info['pictures'])) { ?>
                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $applicant_info['pictures']; ?>">
                        <?php } else { ?>
                            <img class="img-responsive" src="<?php echo base_url('assets/images/img-applicant.jpg'); ?>" />
                        <?php } ?>
                    </figure>             
                    <ul class="admin-contact-info">         
                        <li class="text-color">
                            <span><?php echo $applicant_info['first_name'] . ' ' . $applicant_info['last_name']; ?></span>
                        </li>
                        <li><small><i class="fa fa-envelope"></i> <?php echo $applicant_info['email']; ?></small></li>
                        <li><small><i class="fa fa-phone"></i> <?php echo $applicant_info['phone_number']; ?></small></li>
                    </ul>                    
                </div>
            </div>
        </div>
        <div class="form-col-100">
            <label>Overall Progress</label>
        </div>
        <div class="progress customized-progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                 aria-valuemin="0" aria-valuemax="100" style="width:<?php echo number_format($onboarding_progress); ?>%">
                <?php echo number_format($onboarding_progress); ?>%
            </div>
        </div>
        <div class="dashboard-menu">
            <ul>
                <li>
                    <a class="<?php echo $onbaording_section == 'dashboard'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/dashboard/' . $unique_sid); ?>">
                        <figure><i class="fa fa-th"></i></figure>Dashboard
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'getting_started'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/getting_started/' . $unique_sid); ?>">
                        <figure><i class="fa fa-forward"></i></figure>Getting Started
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'general_information'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/general_information/' . $unique_sid); ?>">
                        <figure><i class="fa fa-info"></i></figure>general information
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'bank_details'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/bank_details/' . $unique_sid); ?>">
                        <figure><i class="fa fa-bank"></i></figure>bank details
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'emergency_contacts'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/emergency_contacts/' . $unique_sid); ?>">
                        <figure><i class="fa fa-ambulance"></i></figure>emergency contacts
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'occupational_license'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/occupational_license/' . $unique_sid); ?>">
                        <figure><i class="fa fa-industry"></i></figure>occupational license
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'drivers_license'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/drivers_license/' . $unique_sid); ?>">
                        <figure><i class="fa fa-automobile"></i></figure>drivers license
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'dependents'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/dependents/' . $unique_sid); ?>">
                        <figure><i class="fa fa-child"></i></figure>dependents
                    </a>
                </li>
                <!-- <li>
            <a class="<?php //echo $onbaording_section == 'i9form_section_01'? 'active' : ''; ?>" href="<?php //echo base_url('onboarding/i9form_section_01') ?>">
                <figure><i class="fa fa-file-text"></i></figure>i9form section 01
            </a>
        </li>
        <li>
            <a class="<?php //echo $onbaording_section == 'i9form_section_02'? 'active' : ''; ?>" href="<?php //echo base_url('onboarding/i9form_section_02') ?>">
                <figure><i class="fa fa-file-text"></i></figure>i9form section 02
            </a>
        </li> -->
                <li>
                    <a class="<?php echo $onbaording_section == 'full_employment_application'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/full_employment_application/' . $unique_sid); ?>">
                        <figure><i class="fa fa-file-text"></i></figure>full employment app
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'eeoc_form'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/eeoc_form/' . $unique_sid); ?>">
                        <figure><i class="fa fa-file-text"></i></figure>eeoc form
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'required_equipment'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/required_equipment/' . $unique_sid); ?>">
                        <figure><i class="fa fa-laptop"></i></figure>required equipment
                    </a>
                </li>
                <li>
                    <a class="<?php echo $onbaording_section == 'documents'? 'active' : ''; ?>" href="<?php echo base_url('onboarding/documents/' . $unique_sid); ?>">
                        <figure><i class="fa fa-files-o"></i></figure>Documents
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>