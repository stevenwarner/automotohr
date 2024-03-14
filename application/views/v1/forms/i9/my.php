<?php
$disabled = $form['user_consent'] == 1 ? 'disabled' : '';

?>
<div class="main jsmaincontent">
    <div class="container-fluid">
        <!-- Header menu -->
        <div class="row">
            <?php if ($user_type == "employee") { ?> 
                <div class="col-sm-12 text-left">
                    <!-- Dashboard button -->
                    <a href="<?= base_url('employee_management_system') ?>" class="btn csBG2 csW csF16 csRadius5">

                        &nbsp;<i class="fa fa-arrow-left csF16"></i>
                        &nbsp;Dashboard
                    </a>
                    <!-- My documents button -->
                    <a href="<?= base_url('hr_documents_management/my_documents') ?>" class="btn csBG2 csF16 csRadius5">

                        &nbsp;<i class="fa fa-files-o csF16"></i>
                        &nbsp;My Documents
                    </a>
                    <?php if (checkIfAppIsEnabled('documentlibrary')) : ?>
                        <!-- My documents button -->
                        <a href="<?= base_url('library_document') ?>" class="btn csBG2 csF16 csRadius5">

                            &nbsp;<i class="fa fa-book csF16"></i>
                            &nbsp;Document Library
                        </a>
                    <?php endif; ?>
                </div>
            <?php } else if ($user_type == "applicant" && $formMode == "applicant_onboarding") { ?>  
                <div class="col-sm-12 text-left">
                    <!-- My documents button -->
                    <a href="<?= base_url('onboarding/hr_documents/'.$unique_sid) ?>" class="btn csBG2 csF16 csRadius5">

                        &nbsp;<i class="fa fa-files-o csF16"></i>
                        &nbsp;Documents
                    </a>
                </div>
            <?php } ?>        
        </div>
        <!-- Header menu ends -->

        <!-- Page header -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header"><span class="section-ttile">I9 form</span></div>
            </div>
        </div>
        <!-- Page header ends -->

        <!-- Page buttons -->
        <div class="row">
            <div class="col-sm-12 text-right">
                <a href="<?= base_url('forms/i9/my/print/' . ($form['sid']) . ''); ?>" target="_blank" class="btn csBG4 csW csRadius5 csF16">
                    &nbsp;<i class="fa fa-print csF16"></i>
                    &nbsp;Print I9 Form
                </a>
                <a href="<?= base_url('forms/i9/my/download/' . ($form['sid']) . ''); ?>" target="_blank" class="btn csBG3 csW csRadius5 csF16">
                    &nbsp;<i class="fa fa-download csF16"></i>
                    &nbsp;Download I9 Form
                </a>
            </div>
        </div>
        <!-- Page buttons ends -->
        <br>

        <div class="csMainArea">
            <?php $this->load->view('loader_new', ['id' => 'jsI9Section1']); ?>
            <!-- line 1 -->
            <?= $this->lang->line('i9_form_text_line_1'); ?>
            <?= $this->lang->line('i9_form_text_line_2'); ?>
            <!-- Form start -->
            <form action="javascript:void(0)" id="jsI9Form" data-formType="<?php echo $formType; ?>">
                <!-- helping material -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong><?= $this->lang->line('i9_form_text_line_3'); ?></strong>
                    </div> 
                    <div class="panel-body">
                        <!-- 1 -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Last Name (Family Name)
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_last_name"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" name="section1_last_name" value="<?= $form['section1_last_name']; ?>" class=" form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>First Name (Given Name)
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_first_name"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" name="section1_first_name" value="<?= $form['section1_first_name']; ?>" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>

                        <!-- 2 -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Middle Initial
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_middle_initial"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" value="<?= $form['section1_middle_initial']; ?>" name="section1_middle_initial" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Other Last Names Used (if any)
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_other_last_names_used"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" value="<?= $form['section1_other_last_names']; ?>" name="section_1_other_last_names_used" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>

                        <!-- 3 -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Address (Street Number and Name)
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_address"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" value="<?= $form['section1_address']; ?>" name="section1_address" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Apt. Number
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_Apt_number"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" value="<?= $form['section1_apt_number']; ?>" name="section1_apt_number" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>

                        <!-- 4 -->
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>City or Town
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_city_or_town "></i>
                                    </label>
                                    <input autocomplete="nope" type="text" value="<?= $form['section1_city_town']; ?>" name="section1_city_town" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>State
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_state"></i>
                                    </label>
                                    <div class="select input-grey">
                                        <select name="section1_state" class="form-control input-lg input-grey section1_state" <?= $disabled; ?>>
                                        
                                            <?php
                                            foreach ($states as $state) {
                                                echo '<option class="input-grey" value="' . $state['state_code'] . '" ' . ($state['state_code'] === $form['section1_state'] ? 'selected' : '') . '>' . $state['state_name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>ZIP Code
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_zip_code"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" value="<?= $form['section1_zip_code']; ?>" name="section1_zip_code" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>

                        <!-- 5 -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Date of Birth
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_date_of_birth"></i>
                                    </label>
                                    <input type="text" value="<?= $form['section1_date_of_birth'] ? formatDateToDB($form['section1_date_of_birth'], DB_DATE, SITE_DATE) : ''; ?>" name="section1_date_of_birth" <?= $disabled; ?> class="form-control input-lg date_of_birth input-grey" readonly />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>U.S. Social Security Number
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_us_social_security_number"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" value="<?= $form['section1_social_security_number']; ?>" name="section1_social_security_number" <?= $disabled; ?> class="form-control input-lg input-grey" />
                                </div>
                            </div>
                        </div>

                        <!-- 6 -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Employee's E-mail Address
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_employees_email_address"></i>
                                    </label>
                                    <input autocomplete="nope" type="email" value="<?= $form['section1_emp_email_address']; ?>" name="section1_emp_email_address" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Employee's Telephone Number
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_employees_telephone_number"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" value="<?= $form['section1_emp_telephone_number']; ?>" name="section1_emp_telephone_number" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>

                        <?= $this->lang->line('i9_form_text_line_4'); ?>
                        <?= $this->lang->line('i9_form_text_line_5'); ?>
                        <!-- 7 -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label class="control control--radio">
                                    1. A citizen of the United States
                                    <input class="section1_penalty_of_perjury" <?= $form['section1_penalty_of_perjury'] == 'citizen' ? 'checked' : ''; ?> name="section1_penalty_of_perjury" value="citizen" type="radio" <?= $disabled; ?> <?= $form['user_consent'] == 1 ? '' : 'checked'; ?>>
                                    <div class="control__indicator "></div>
                                </label>

                                &nbsp; <i class="fa fa-question-circle-o modalShow" src="section_2_citizen_of_the_us"></i>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label class="control control--radio">
                                    2. A noncitizen of the United States
                                    <input class="section1_penalty_of_perjury" <?= $form['section1_penalty_of_perjury'] == 'noncitizen' ? 'checked' : ''; ?> name="section1_penalty_of_perjury" value="noncitizen" <?= $disabled; ?> type="radio">
                                    <div class="control__indicator"></div>
                                </label>

                                &nbsp; <i class="fa fa-question-circle-o modalShow" src="section_2_noncitizen_of_the_us"></i>
                            </div>
                        </div>

                        <!-- 8 -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label class="control control--radio">
                                    3. A lawful permanent resident (Enter USCIS or A-Number.)
                                    <input class="section1_penalty_of_perjury" <?= $form['section1_penalty_of_perjury'] == 'permanent-resident' ? 'checked' : ''; ?> name="section1_penalty_of_perjury" value="permanent-resident" <?= $disabled; ?> type="radio">
                                    <div class="control__indicator"></div>
                                </label>

                                &nbsp; <i class="fa fa-question-circle-o modalShow" src="section_2_lawful_permanent_resident"></i>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label class="control control--radio">
                                    4. An noncitizen authorized to work
                                    <input class="section1_penalty_of_perjury" <?= $form['section1_penalty_of_perjury'] == 'alien-work' ? 'checked' : ''; ?> name="section1_penalty_of_perjury" value="alien-work" id="alien_authorized_to_work" <?= $disabled; ?> type="radio">
                                    <div class="control__indicator"></div>
                                </label>

                                &nbsp; <i class="fa fa-question-circle-o modalShow" src="section_2_alien_authorized_to_work"></i>
                            </div>
                        </div>
                        <br>
                        <!-- 9 -->
                        <div class="row hidden jsSection1Hide jsSection1Option4">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Number Type/Number Type </label>
                                    <div class="select">
                                        <select class="form-control input-lg input-grey" name="section1_alien_registration_number_two" id="section1_alien_registration_number_two" <?= $disabled; ?>>
                                            <option value="Alien-Number" <?= $form['section1_alien_registration_number_two'] === 'Alien-Number' ? 'selected' : ''; ?>>
                                                Alien Number
                                            </option>
                                            <option value="USCIS-Number" <?= $form['section1_alien_registration_number_two'] === 'USCIS-Number' ? 'selected' : ''; ?>>
                                                USCIS Number
                                            </option>
                                            <option value="Foreign-Number" <?= $form['section1_alien_registration_number_two'] === 'Foreign-Number' ? 'selected' : ''; ?>>
                                                Foreign Passport Number
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>An alien authorized to work (expiration date)
                                        <strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_2_expiration_date"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" name="alien_authorized_expiration_date" value="<?= $form['alien_authorized_expiration_date'] ? date('m/d/Y',strtotime($form['alien_authorized_expiration_date'])) : ''; ?>" <?= $disabled; ?> id="alien_authorized_expiration_date" class="form-control input-lg date_picker2 input-grey" />
                                </div>
                            </div>
                        </div>

                        <!-- 10 -->
                        <div class="row hidden jsSection1Hide jsSection1UICS">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>USCIS Number
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_2_alien_number"></i>
                                    </label>
                                    <input type="number" value="<?= $form['section1_alien_registration_number_one']; ?>" name="section1_uscis_registration_number_one" id="section1_uscis_registration_number_one" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>

                        <!-- 11 -->
                        <div class="row hidden jsSection1Hide jsSection1Foreign">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group autoheight">
                                    <label>Foreign Passport Number
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_2_passport_number"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" name="foreign_passport_number" value="<?= $form['foreign_passport_number']; ?>" id="foreign_passport_number" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group autoheight">
                                    <label>Country of Issuance
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_2_country_of_issuance"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" name="country_of_issuance" value="<?= $form['country_of_issuance']; ?>" id="country_of_issuance" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>

                        <!-- 12 -->
                        <div class="row hidden jsSection1Hide jsSectionAdmission">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Form I-94 Admission Number
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_2_admission_number"></i>
                                    </label>
                                    <input autocomplete="nope" type="text" name="form_admission_number" value="<?= $form['form_admission_number']; ?>" id="form_admission_number" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>

                        <br>

                        <!--13 -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Signature of Employee
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_3_signature_of_employee"></i>
                                    </label>
                                    <!-- the below loaded view add e-signature -->
                                    <?php if ($form['section1_emp_signature'] && $form['user_consent'] == 1) { ?>
                                        <br />
                                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $form['section1_emp_signature']; ?>" id="draw_upload_img" />
                                    <?php } else { ?>
                                        <?php $this->load->view('static-pages/e_signature_button'); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Today's Date (mm/dd/yyyy)
                                        &nbsp;<strong class="text-danger">*</strong>
                                        &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_3_today_date"></i>
                                    </label>
                                    <input type="text" name="section1_today_date" value="<?= $form['section1_today_date'] ? formatDateToDB($form['section1_today_date'], DB_DATE, SITE_DATE) : ''; ?>" class="form-control input-lg date_picker input-grey" readonly <?= $disabled; ?> />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preparer -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong><?= $this->lang->line('i9_form_text_line_6'); ?></strong>
                    </div>
                    <div class="panel-body">
                        <!--  -->
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="control control--radio">
                                    I did not use a preparer or translator.
                                    <input class="section1_preparer_or_translator" name="section1_preparer_or_translator" value="not-used" type="radio" <?= $form['section1_preparer_or_translator'] == 'not-used' ? 'checked' : ''; ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <label class="control control--radio">
                                    A preparer(s) and/or translator(s) assisted the employee in completing
                                    Section 1
                                    <input class="section1_preparer_or_translator" name="section1_preparer_or_translator" value="used" type="radio" <?= $form['section1_preparer_or_translator'] == 'used' ? 'checked' : ''; ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <!--  -->
                        <div class="jsTranslatorBlock <?= $form['section1_preparer_or_translator'] == 'used' ? '' : 'hidden'; ?>">
                            <hr />
                            <div class="row">
                                <div class="col-sm-12">
                                    <p>
                                        <strong>
                                            (Fields below must be completed and signed when preparers and/or translators assist an employee in completing Section 1.)
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <br>
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Last Name (Family Name) from Section 1.
                                        </label>
                                        <input autocomplete="nope" type="text" value="<?= $form['section1_last_name']; ?>" class="form-control input-lg input-grey jsEmployeeLastName" />
                                    </div>
                                </div>

                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>First Name (Given Name) from Section 1.
                                        </label>
                                        <input autocomplete="nope" type="text" value="<?= $form['section1_first_name']; ?>" class="form-control input-lg input-grey jsEmployeeFirstName" />
                                    </div>
                                </div>

                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Middle Initial (If Any) from Section 1.
                                        </label>
                                        <input autocomplete="nope" type="text" value="<?= $form['section1_middle_initial']; ?>" class="form-control input-lg input-grey jsEmployeeMiddleInitial" />
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <p>
                                        <strong>Instructions:</strong> This supplement must be completed by any preparer and/or translator who assists an employee in completing Section 1
                                        of Form I-9. The preparer and/or translator must enter the employee's name in the spaces provided above. Each preparer or translator
                                        must complete, sign, and date a separate certification area. Employers must retain completed supplement sheets with the employee's
                                        completed Form I-9
                                    </p>
                                </div>
                            </div>

                            <!--  -->
                            <div class="jsBlock">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>
                                            <strong>
                                                I attest, under penalty of perjury, that I have assisted in the completion of Section 1 of this form and that to the best of my
                                                knowledge the information is true and correct.
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <?php $this->load->view('v1/forms/i9/preparer', [
                                    'preparerArray' => $form['section1_preparer_json']
                                        ? json_decode(
                                            $form['section1_preparer_json'],
                                            true
                                        ) : []
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <hr />
                <div class="row">
                    <div class="col-xs-12 text-justify">
                        <p>
                            <?php echo str_replace("{{company_name}}", $companyName, SIGNATURE_CONSENT_HEADING); ?>
                        </p>
                        <p>
                            <?php echo SIGNATURE_CONSENT_TITLE; ?>
                        </p>
                        <p>
                            <?php echo str_replace("{{company_name}}", $companyName, SIGNATURE_CONSENT_DESCRIPTION); ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label class="control control--checkbox">
                            <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                            <input <?php echo set_checkbox('user_consent', 1, 0); ?> <?= $form['user_consent'] == 1 ?  'checked' : '' ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <?php if ($form['user_consent'] != 1) { ?>
                    <hr />

                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <input type="hidden" class="jsFormCode" value="<?= $form['sid']; ?>" />
                            <input type="hidden" class="jsFormMode" value="<?= $formMode; ?>" />
                            <button type="submit" onclick="" type="button" class="btn btn-info break-word-text"><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                        </div>
                    </div>
                <?php } ?>
            </form>
            <!-- Form ends -->
        </div>
    </div>
</div>

<!-- I9 helping popups -->
<?php $this->load->view('form_i9/pop_up_info'); ?>
<?php $this->load->view('static-pages/e_signature_popup'); ?>