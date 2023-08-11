<?php
$company_name = ucwords($session['company_detail']['CompanyName']);
// $form = $pre_form;
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header"><span class="section-ttile"><?php echo $title; ?></span></div>
                <div class="form-wrp">
                    <?php if (sizeof($pre_form) > 0  && $pre_form['user_consent'] != NULL && $pre_form['user_consent'] != 0) { ?>
                        <div class="row mb-2">
                            <div class="col-lg-3 pull-right">
                                <a target="_blank" href="<?php echo base_url('form_i9/download_i9form/' . $pre_form['user_type'] . '/' . $pre_form['user_sid']) ?>" class="btn blue-button btn-block">Download PDF</a>
                            </div>
                            <div class="col-lg-3 pull-right">
                                <a target="_blank" href="<?php echo base_url('form_i9/preview_i9form/' . $pre_form['user_type'] . '/' . $pre_form['user_sid']) ?>" class="btn blue-button btn-block">Preview</a>
                            </div>
                        </div>
                    <?php } ?>
                    <form action="javascript:void(0)" id="jsI9Form">
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
                                            <input autocomplete="nope" type="text" name="section1_last_name" value="<?= $pre_form['section1_last_name']; ?>" class=" form-control input-lg input-grey" <?= $disabled; ?> />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>First Name (Given Name)
                                                &nbsp;<strong class="text-danger">*</strong>
                                                &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_first_name"></i>
                                            </label>
                                            <input autocomplete="nope" type="text" name="section1_first_name" value="<?= $pre_form['section1_first_name']; ?>" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_middle_initial']; ?>" name="section1_middle_initial" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Other Last Names Used (if any)
                                                &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_other_last_names_used"></i>
                                            </label>
                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_other_last_names']; ?>" name="section_1_other_last_names_used" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_address']; ?>" name="section1_address" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Apt. Number
                                                &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_Apt_number"></i>
                                            </label>
                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_apt_number']; ?>" name="section1_apt_number" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_city_town']; ?>" name="section1_city_town" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                                        echo '<option class="input-grey" value="' . $state['state_code'] . '" ' . ($state['state_code'] === $pre_form['section1_state'] ? 'selected' : '') . '>' . $state['state_name'] . '</option>';
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
                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_zip_code']; ?>" name="section1_zip_code" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                            <input type="text" value="<?= $pre_form['section1_date_of_birth'] ? formatDateToDB($pre_form['section1_date_of_birth'], DB_DATE, SITE_DATE) : ''; ?>" name="section1_date_of_birth" <?= $disabled; ?> class="form-control input-lg date_of_birth input-grey" readonly />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>U.S. Social Security Number
                                                &nbsp;<strong class="text-danger">*</strong>
                                                &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_us_social_security_number"></i>
                                            </label>
                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_social_security_number']; ?>" name="section1_social_security_number" <?= $disabled; ?> class="form-control input-lg input-grey" />
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
                                            <input autocomplete="nope" type="email" value="<?= $pre_form['section1_emp_email_address']; ?>" name="section1_emp_email_address" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Employee's Telephone Number
                                                &nbsp;<strong class="text-danger">*</strong>
                                                &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_1_employees_telephone_number"></i>
                                            </label>
                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_emp_telephone_number']; ?>" name="section1_emp_telephone_number" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                            <input class="section1_penalty_of_perjury" <?= $pre_form['section1_penalty_of_perjury'] == 'citizen' ? 'checked' : ''; ?> name="section1_penalty_of_perjury" value="citizen" type="radio" <?= $disabled; ?> <?= $pre_form['user_consent'] == 1 ? '' : 'checked'; ?>>
                                            <div class="control__indicator "></div>
                                        </label>

                                        &nbsp; <i class="fa fa-question-circle-o modalShow" src="section_2_citizen_of_the_us"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <label class="control control--radio">
                                            2. A noncitizen of the United States
                                            <input class="section1_penalty_of_perjury" <?= $pre_form['section1_penalty_of_perjury'] == 'noncitizen' ? 'checked' : ''; ?> name="section1_penalty_of_perjury" value="noncitizen" <?= $disabled; ?> type="radio">
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
                                            <input class="section1_penalty_of_perjury" <?= $pre_form['section1_penalty_of_perjury'] == 'permanent-resident' ? 'checked' : ''; ?> name="section1_penalty_of_perjury" value="permanent-resident" <?= $disabled; ?> type="radio">
                                            <div class="control__indicator"></div>
                                        </label>

                                        &nbsp; <i class="fa fa-question-circle-o modalShow" src="section_2_lawful_permanent_resident"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <label class="control control--radio">
                                            4. An noncitizen authorized to work
                                            <input class="section1_penalty_of_perjury" <?= $pre_form['section1_penalty_of_perjury'] == 'alien-work' ? 'checked' : ''; ?> name="section1_penalty_of_perjury" value="alien-work" id="alien_authorized_to_work" <?= $disabled; ?> type="radio">
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
                                                    <option value="Alien-Number" <?= $pre_form['section1_alien_registration_number_two'] === 'Alien-Number' ? 'selected' : ''; ?>>
                                                        Alien Number
                                                    </option>
                                                    <option value="USCIS-Number" <?= $pre_form['section1_alien_registration_number_two'] === 'USCIS-Number' ? 'selected' : ''; ?>>
                                                        USCIS Number
                                                    </option>
                                                    <option value="Foreign-Number" <?= $pre_form['section1_alien_registration_number_two'] === 'Foreign-Number' ? 'selected' : ''; ?>>
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
                                            <input autocomplete="nope" type="text" name="alien_authorized_expiration_date" value="<?= $pre_form['alien_authorized_expiration_date'] ? formatDateToDB($pre_form['alien_authorized_expiration_date'], DB_DATE, SITE_DATE) : ''; ?>" <?= $disabled; ?> id="alien_authorized_expiration_date" class="form-control input-lg date_picker2 input-grey" />
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
                                            <input type="number" value="<?= $pre_form['section1_alien_registration_number_one']; ?>" name="section1_uscis_registration_number_one" id="section1_uscis_registration_number_one" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                            <input autocomplete="nope" type="text" name="foreign_passport_number" value="<?= $pre_form['foreign_passport_number']; ?>" id="foreign_passport_number" class="form-control input-lg input-grey" <?= $disabled; ?> />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group autoheight">
                                            <label>Country of Issuance
                                                &nbsp;<strong class="text-danger">*</strong>
                                                &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_2_country_of_issuance"></i>
                                            </label>
                                            <input autocomplete="nope" type="text" name="country_of_issuance" value="<?= $pre_form['country_of_issuance']; ?>" id="country_of_issuance" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                            <input autocomplete="nope" type="text" name="form_admission_number" value="<?= $pre_form['form_admission_number']; ?>" id="form_admission_number" class="form-control input-lg input-grey" <?= $disabled; ?> />
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
                                            <?php if ($pre_form['section1_emp_signature'] && $pre_form['user_consent'] == 1) { ?>
                                                <br />
                                                <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $pre_form['section1_emp_signature']; ?>" id="draw_upload_img" />
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
                                            <input type="text" name="section1_today_date" value="<?= $pre_form['section1_today_date'] ? formatDateToDB($pre_form['section1_today_date'], DB_DATE, SITE_DATE) : ''; ?>" class="form-control input-lg date_picker input-grey" readonly <?= $disabled; ?> />
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
                                            <input class="section1_preparer_or_translator" name="section1_preparer_or_translator" value="not-used" type="radio" <?= $pre_form['section1_preparer_or_translator'] == 'not-used' ? 'checked' : ''; ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <label class="control control--radio">
                                            A preparer(s) and/or translator(s) assisted the employee in completing
                                            Section 1
                                            <input class="section1_preparer_or_translator" name="section1_preparer_or_translator" value="used" type="radio" <?= $pre_form['section1_preparer_or_translator'] == 'used' ? 'checked' : ''; ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="jsTranslatorBlock <?= $pre_form['section1_preparer_or_translator'] == 'used' ? '' : 'hidden'; ?>">
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
                                                <input autocomplete="nope" type="text" value="<?= $pre_form['section1_last_name']; ?>" class="form-control input-lg input-grey jsEmployeeLastName" />
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>First Name (Given Name) from Section 1.
                                                </label>
                                                <input autocomplete="nope" type="text" value="<?= $pre_form['section1_first_name']; ?>" class="form-control input-lg input-grey jsEmployeeFirstName" />
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Middle Initial (If Any) from Section 1.
                                                </label>
                                                <input autocomplete="nope" type="text" value="<?= $pre_form['section1_middle_initial']; ?>" class="form-control input-lg input-grey jsEmployeeMiddleInitial" />
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
                                            'preparerArray' => $pre_form['section1_preparer_json']
                                                ? json_decode(
                                                    $pre_form['section1_preparer_json'],
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
                                    <input <?php echo set_checkbox('user_consent', 1, 0); ?> <?= $pre_form['user_consent'] == 1 ?  'checked' : '' ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <?php if ($pre_form['user_consent'] != 1) { ?>
                            <hr />

                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <input type="hidden" class="jsFormCode" value="<?= $pre_form['sid']; ?>" />
                                    <button type="submit" onclick="" type="button" class="btn btn-info break-word-text"><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                </div>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('form_i9/pop_up_info'); ?>
<?php $this->load->view('static-pages/e_signature_popup'); ?>

<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/i9-form.js?v=1"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script type="text/javascript">
    // $('#consent_and_notice_section').hide();
    $(document).ready(function() {
        var exist_e_signature_data = '<?php if (!empty($e_signature_data)) {
                                            echo true;
                                        } else {
                                            echo false;
                                        } ?>';
        if (exist_e_signature_data == 1) {
            // $('#consent_and_notice_section').show();
            var signature = '<?php echo isset($signature) ? $signature : ""; ?>';
            var base64_url = '<?php echo isset($e_signature_data['signature_bas64_image']) ? '' : ""; ?>';
            var signature_type = '<?php echo isset($active_signature) ? $active_signature : ""; ?>';
            $('#signature_bas64_image').val(base64_url);
            $('#signature').val(signature);
            $('#active_signature').val(signature_type);
        } else {
            // $('#consent_and_notice_section').hide();
        }


        $('.modalShow').click(function(event) {

            event.preventDefault();

            var info_id = $(this).attr("src");
            var title_string = $(this).parent().text();
            var model_title = title_string.replace("*", "");
            if (info_id == "section_2_alien_number") {
                if ($('#alien_authorized_to_work').is(':checked')) {
                    info_id = 'section_21_alien_number';
                }
            }

            var model_content = $('#' + info_id).html();

            var mymodal = $('#myPopupModal');

            mymodal.find('.modal-title').text(model_title);
            mymodal.find('.modal-body').html(model_content);
            mymodal.modal('show');
        });

        $('.verify-doc').click(function() {
            var doc_id = $(this).attr('data-id');
            $.ajax({
                url: '<?= base_url('form_i9/verify_docs') ?>',
                type: 'post',
                data: {
                    id: doc_id
                },
                success: function(data) {
                    alertify.success('Document verified successfully');
                    $('#' + doc_id).html('Verified');
                },
                error: function() {

                }

            })
        });

        $('.date_picker').datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            maxDate: new Date,
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });
        $('.date_picker2').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo STARTING_DATE_LIMIT; ?>"
        });
        var option_val = '<?php echo sizeof($pre_form) > 0 ? $pre_form['section1_penalty_of_perjury'] : '' ?>';
        if (option_val == 'alien-work') {
            $('#option-4').show();
        } else if (option_val == 'permanent-resident') {
            $('#option-3').show();
            $('#option-4').hide();
        } else {
            $('#option-3').hide();
            $('#option-4').hide();
        }
        var prep_div = '<?php echo sizeof($db_preparer_serialized_data) > 0 ? $db_preparer_serialized_data['section1_preparer_or_translator'] : '' ?>';
        if (prep_div == 'used') {
            $('.preparer-number-div').show();
        } else {
            $('.preparer-number-div').hide();
        }
        $("#i9-form").validate({
            ignore: ":hidden:not(select)",
            rules: {
                section1_last_name: {
                    required: true
                },
                section1_first_name: {
                    required: true
                },
                section1_middle_initial: {
                    required: true
                },
                section1_address: {
                    required: true
                },
                section1_city_town: {
                    required: true
                },
                section1_state: {
                    required: true
                },
                section1_zip_code: {
                    required: true
                },
                section1_date_of_birth: {
                    required: true
                },
                section1_social_security_number: {
                    required: true
                },
                section1_emp_email_address: {
                    required: true
                },
                section1_emp_telephone_number: {
                    required: true
                },
                section1_emp_signature: {
                    required: true
                },
                section1_today_date: {
                    required: true
                }
            },
            messages: {
                section1_last_name: {
                    required: 'Last Name is required.'
                },
                section1_first_name: {
                    required: 'First Name is required.'
                },
                section1_middle_initial: {
                    required: 'Middle Initial is required.'
                },
                section1_address: {
                    required: 'Address is required'
                },
                section1_city_town: {
                    required: 'City/Town is required'
                },
                section1_state: {
                    required: 'State is required'
                },
                section1_zip_code: {
                    required: 'Zip Code is required'
                },
                section1_date_of_birth: {
                    required: 'Date of Birth is required'
                },
                section1_social_security_number: {
                    required: 'Social Security number is required'
                },
                section1_emp_email_address: {
                    required: 'This email address is required'
                },
                section1_emp_telephone_number: {
                    required: 'This telephone number is required'
                },
                section1_emp_signature: {
                    required: 'Employee signature is requiered'
                },
                section1_today_date: {
                    required: 'Date is required'
                }
            },
            submitHandler: function(form) {
                var radio_val = $('input[name="section1_penalty_of_perjury"]:checked').val();
                var prepare_radio = $('input[name="section1_preparer_or_translator"]:checked').val();
                if (radio_val == 'permanent-resident') {
                    if ($('#section1_alien_registration_number_one').val() == '') {
                        alertify.error($('#section1_alien_registration_number_two').val() + ' is required');
                        return false;
                    }

                } else if (radio_val == 'alien-work') {
                    if ($('#alien_authorized_expiration_date').val() == '') {
                        alertify.error('An Alien Authorized To Work (Expiration Date) is required');
                        return false;
                    }
                    if ($('#section1_alien_registration_number_one').val() == '' && $('#form_admission_number').val() == '' && $('#foreign_passport_number').val() == '' && $('#country_of_issuance').val() == '') {
                        alertify.error('You must provide Form I-94 Admission Number OR Alien/USCIS Number OR Foreign Passport Number OR Country Issuance');
                        return false;
                    }

                }

                if (prepare_radio == 'used') {
                    if ($('#section1_preparer_signature').val() == '' || $('#section1_preparer_today_date').val() == '' || $('#section1_preparer_last_name').val() == '' || $('#section1_preparer_first_name').val() == '' || $('#section1_preparer_address').val() == '' || $('#section1_preparer_city_town').val() == '' || $('#section1_preparer_state').val() == '' || $('#section1_preparer_zip_code').val() == '') {
                        alertify.alert('Please fill all the information of preparer or translator');
                        return false;
                    }
                }

                form.submit();
            }
        });

        $('input[name="section1_preparer_or_translator"]').on('change', function() {
            if ($(this).val() != 'not-used') {
                $('.preparer-number-div').show();
            } else {
                $('.preparer-number-div').hide();
            }
        });

        $('input[name="section1_penalty_of_perjury"]').on('change', function() {
            var radio_val = $(this).val();
            if (radio_val == 'citizen' || radio_val == 'noncitizen') {
                $('#option-3').hide();
                $('#option-4').hide();
            } else if (radio_val == 'permanent-resident') {
                $('#option-3').show();
                $('#option-4').hide();
            } else if (radio_val == 'alien-work') {
                $('#option-3').show();
                $('#option-4').show();
            }
            i9_manager.fill_part1_title(radio_val);
            i9_manager.fill_list_c(radio_val);
        });

        $('#section2_lista_part1_document_title').on('change', function() {
            var title = $(this).val();
            i9_manager.fill_part1_authority(title);
        });

        $('#section2_lista_part3_document_title').on('change', function() {
            var title = $(this).val();
            i9_manager.fill_part3_auth(title);
        });

        $('#section2_listc_document_title').on('change', function() {
            var title = $(this).val();
            i9_manager.fill_list_c_auth(title);
        });

        $('#section2_listb_document_title').on('change', function() {
            var title = $(this).val();
            i9_manager.fill_list_b_auth(title);
        });

        option_val != '' ? i9_manager.fill_part1_title(option_val) : '';
        option_val != '' ? i9_manager.fill_list_c(option_val) : '';
        i9_manager.fill_listb();

    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-group form-control autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">' + fileName + '</span> </div> <div class="pull-right"> <input class="submit-btn btn blue-button" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn blue-button" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload() {
        $('.upload-file').show();
        if ($('#uploaded-files').html() != '') {
            $('#uploaded-files').show();
        }
        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload() {
        var file_data = $('#docs').prop('files')[0];
        var uploader = $('#uploader').val();
        var form_id = $('#form-id').val();
        var prefill_flag = '<?php echo sizeof($pre_form) > 0 ? $pre_form['sid'] : -1; ?>';
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('form_id', prefill_flag == -1 ? form_id : prefill_flag);
        form_data.append('app_id', <?php echo sizeof($pre_form) > 0 ? $pre_form['emp_app_sid'] : $employer_sid; ?>);
        form_data.append('uploader', uploader);
        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('form_i9/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();
                $('#uploaded-files').show();
                $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> ' + file_data['name'] + '</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                if (data != "error") {
                    $('#form-id').val(data);
                } else {
                    alert('Document Error');
                }
            },
            error: function() {}
        });
    }


    function func_save_e_signature() {

        var is_signature_exist = $('#signature_bas64_image').val();
        if (is_signature_exist == "") {
            alertify.error('Please Add Your Signature!');
            return false;
        }

        if ($('#i9-form').validate()) {
            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                function() {
                    $('#i9-form').submit();
                },
                function() {
                    alertify.error('Cancelled!');
                }).set('labels', {
                ok: 'I Consent and Accept!',
                cancel: 'Cancel'
            });
        }
    }
</script>

<script type="text/javascript" src="<?= base_url('assets/js/jquery.validate.min.js') ?>"></script>
<script type="text/javascript" src="http://automotohr.local/assets/js/app_helper.js?v=1691667825"></script>
<script type="text/javascript" src="http://automotohr.local/assets/v1/forms/i9/main.js?v=1691667825"></script>

