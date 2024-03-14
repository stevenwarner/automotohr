<?php
    $company_name = ucwords($session['company_detail']['CompanyName']);
?>
<?php if (!$load_view) { ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/mFileUploader/index.css" /> 
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <!-- start upload -->
                            <div class="row" style="padding: 13px 1px; display: none;">
                                <div class="col-xs-12">
                                    <label>Upload I9 Form d</label>
                                    <input style="display: none;" type="file" name="document" id="uploar_i9_form">
                                    <button type="button" id="btn_eev_document" class="btn btn-success pull-right" style="display: none; margin:10px 0px;">Upload I9</button>
                                </div>
                            </div>
                            <!-- end upload -->
                            <div class="form-wrp">
                                <?php if(sizeof($pre_form)>0  && $pre_form['user_consent']!=NULL && $pre_form['user_consent']!=0){ ?>
                                    <div class="row mb-2">
                                        <div class="col-lg-3 pull-right">
                                            <!-- <a target="_blank" href="--><?php //echo base_url('form_i9/download_i9form/' . $pre_form['user_type'] . '/' . $pre_form['user_sid'])?><!--" class="btn btn-success btn-block">Download PDF</a>-->
                                        </div>
                                        <div class="col-lg-3 pull-right">
                                            <!-- <a target="_blank" href="--><?php //echo base_url('form_i9/preview_i9form/' . $pre_form['user_type'] . '/' .$pre_form['user_sid'])?><!--" class="btn btn-success btn-block">Preview</a>-->
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- <form action="<?php echo current_url();?>" id="i9-form" method="post" autocomplete="nope"> -->
                                <form action="<?php echo base_url("forms/i9/authorized/section/").$pre_form['sid'];?>" id="i9-form" method="post" autocomplete="nope">
                                    <p><strong>START HERE:</strong> Read instructions carefully before completing this form. The instructions must be available, either in paper or electronically, during completion of this form. Employers are liable for errors in the completion of this form.</p>
                                    <p><strong>ANTI-DISCRIMINATION NOTICE:</strong> It is illegal to discriminate against work-authorized individuals. Employers CANNOT specify which document(s) an employee may present to establish employment authorization and identity. The refusal to hire or continue to employ an individual because the documentation presented has a future expiration date may also constitute illegal discrimination.</p>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <strong>Section 1. Employee Information and Attestation</strong> (Employees must complete and sign Section 1 of Form I-9 no later
                                            than the first day of employment, but not before accepting a job offer.)
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Last Name (Family Name) <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_1_last_name"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_last_name']: ''?>" name="section1_last_name" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>First Name (Given Name) <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow"  src="section_1_first_name"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_first_name']: ''?>" name="section1_first_name" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Middle Initial <i class="fa fa-question-circle-o modalShow" src="section_1_middle_initial"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_middle_initial']: ''?>" name="section1_middle_initial" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Other Last Names Used (if any) <i class="fa fa-question-circle-o modalShow" src="section_1_other_last_names_used"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_other_last_names']: ''?>" name="section1_other_last_names" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Address (Street Number and Name) <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_1_address"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_address']: ''?>" name="section1_address" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Apt. Number <i class="fa fa-question-circle-o modalShow" src="section_1_Apt_number"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 && !empty($pre_form['section1_apt_number']) ? $pre_form['section1_apt_number']: 'N/A'?>" name="section1_apt_number" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label>City or Town <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_1_city_or_town "></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_city_town']: ''?>" name="section1_city_town" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label>State <i class="fa fa-question-circle-o modalShow" src="section_1_state"></i></label>
                                                        <div class="select">
                                                            <select name="section1_state" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                               
                                                            <option value=""> </option>

                                                               <?php foreach($states as $state){
                                                                    $select = sizeof($pre_form)>0 && $state['state_code'] == $pre_form['section1_state'] ? 'selected="selected"':"";
                                                                    echo '<option value="'.$state['state_code']. '"'. $select .'>'.$state['state_name'].'</option>';
                                                                }?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label>ZIP Code <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_1_zip_code"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_zip_code']: ''?>" name="section1_zip_code" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Date of Birth <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_1_date_of_birth"></i></label>
                                                        <input type="text" autocomplete="off" readonly value="<?php echo sizeof($pre_form)>0 && !empty($pre_form['section1_date_of_birth']) ? date('m-d-Y',strtotime($pre_form['section1_date_of_birth'])): ''?>" name="section1_date_of_birth" class="form-control date_of_birth" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>U.S. Social Security Number <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_1_us_social_security_number"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_social_security_number']: ''?>"  name="section1_social_security_number" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Employee's E-mail Address <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_1_employees_email_address"></i></label>
                                                        <input type="email" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_emp_email_address']: ''?>" name="section1_emp_email_address" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Employee's Telephone Number <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_1_employees_telephone_number"></i></label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['section1_emp_telephone_number']: ''?>" name="section1_emp_telephone_number" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p><strong>I am aware that federal law provides for imprisonment and/or fines for false statements, or the use of false documents, in connection with the completion of this form. I attest, under penalty of perjury, that this information, including my selection of the box attesting to my citizenship or immigration status, is true and correct.</strong></p>
                                    <p><strong>Check one of the following boxes to attest to your citizenship or immigration status (See page 2 and 3 of the instructions.):</strong></p>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <label class="control control--radio">
                                                        1. A citizen of the United States
                                                        <input class="" name="section1_penalty_of_perjury" value="citizen" type="radio" checked <?php echo sizeof($pre_form)>0 && $pre_form['section1_penalty_of_perjury'] == 'citizen' ? 'checked': ''?> <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <i class="fa fa-question-circle-o modalShow" src="section_2_citizen_of_the_us"></i>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <label class="control control--radio">
                                                        2. A noncitizen of the United States
                                                        <input class="" name="section1_penalty_of_perjury" value="noncitizen" type="radio" <?php echo sizeof($pre_form)>0 && $pre_form['section1_penalty_of_perjury'] == 'noncitizen' ? 'checked': ''?> <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <i class="fa fa-question-circle-o modalShow" src="section_2_noncitizen_of_the_us"></i>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <label class="control control--radio">
                                                        3. A lawful permanent resident
                                                        <input class="" name="section1_penalty_of_perjury" value="permanent-resident" type="radio" <?php echo sizeof($pre_form)>0 && $pre_form['section1_penalty_of_perjury'] == 'permanent-resident' ? 'checked': ''?> <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <i class="fa fa-question-circle-o modalShow" src="section_2_lawful_permanent_resident"></i>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <label class="control control--radio">
                                                        4. An alien authorized to work
                                                        <input class="" name="section1_penalty_of_perjury" value="alien-work" id="alien_authorized_to_work" type="radio" <?php echo sizeof($pre_form)>0 && $pre_form['section1_penalty_of_perjury'] == 'alien-work' ? 'checked': ''?> <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <i class="fa fa-question-circle-o modalShow" src="section_2_alien_authorized_to_work"></i>
                                                </div>

                                                <!--                                        Un Serialize Options Data -->
                                                <?php
                                                $db_serialized_data = sizeof($pre_form)>0 ? unserialize($pre_form['section1_alien_registration_number']) : array();
                                                ?>


                                                <div id="option-3">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Alien Number/USCIS Number): <i class="fa fa-question-circle-o modalShow" src="section_2_alien_number"></i></label>
                                                            <input type="number" value="<?php echo sizeof($db_serialized_data)>0 ? $db_serialized_data['section1_alien_registration_number_one']: ''?>" name="section1_alien_registration_number_one" id="section1_alien_registration_number_one" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Number Type/Number Type): </label>
                                                            <div class="select">
                                                                <select class="form-control" name="section1_alien_registration_number_two" id="section1_alien_registration_number_two" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                                    <option value="Alien-Number" value="<?php echo sizeof($db_serialized_data)>0 && $db_serialized_data['section1_alien_registration_number_two'] == 'Alien-Number' ? 'selected="selected"' : ''?>">Alien Number</option>
                                                                    <option value="USCIS-Number" value="<?php echo sizeof($db_serialized_data)>0 && $db_serialized_data['section1_alien_registration_number_two'] == 'USCIS-Number' ? 'selected="selected"' : ''?>">USCIS Number</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div id="option-4">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>An alien authorized to work (expiration date) <i class="fa fa-question-circle-o modalShow" src="section_2_expiration_date"></i></label>
                                                            <input type="text" name="alien_authorized_expiration_date" value="<?php echo sizeof($db_serialized_data)>0 && $pre_form['section1_penalty_of_perjury'] == 'alien-work'? date('m-d-Y',strtotime($db_serialized_data['alien_authorized_expiration_date'])): ''?>" id="alien_authorized_expiration_date" autocomplete="off" class="form-control date_picker2" readonly <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?> />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Form I-94 Admission Number <i class="fa fa-question-circle-o modalShow" src="section_2_admission_number"></i></label>
                                                            <input type="text" name="form_admission_number" value="<?php echo sizeof($db_serialized_data)>0 && $pre_form['section1_penalty_of_perjury'] == 'alien-work'? $db_serialized_data['form_admission_number']: ''?>" id="form_admission_number" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?> />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group autoheight">
                                                            <label>Foreign Passport Number <i class="fa fa-question-circle-o modalShow" src="section_2_passport_number"></i></label>
                                                            <input type="text" name="foreign_passport_number" value="<?php echo sizeof($db_serialized_data)>0 && $pre_form['section1_penalty_of_perjury'] == 'alien-work'? $db_serialized_data['foreign_passport_number']: ''?>" id="foreign_passport_number" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?> />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group autoheight">
                                                            <label>Country of Issuance <i class="fa fa-question-circle-o modalShow" src="section_2_country_of_issuance"></i></label>
                                                            <input type="text" name="country_of_issuance" value="<?php echo sizeof($db_serialized_data)>0 && $pre_form['section1_penalty_of_perjury'] == 'alien-work'? $db_serialized_data['country_of_issuance']: ''?>" id="country_of_issuance" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?> />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <?php if($signed_flag == true){ ?>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Signature of Employee <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_3_signature_of_employee"></i></label>
                                                        <?php if($signed_flag == true) { ?>
                                                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT?>;" src="<?php echo $pre_form['section1_emp_signature']; ?>" class="esignaturesize" />
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Today's Date (mm/dd/yyyy) <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_3_today_date"></i></label>
                                                        <input type="text" name="section1_today_date" value="<?php echo sizeof($pre_form)>0 && !empty($pre_form['section1_today_date']) ? date('m-d-Y',strtotime($pre_form['section1_today_date'])): date('m-d-Y'); ?>" class="form-control" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'readonly';}?>/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <?php if($section2_flag && $signed_flag == true) { ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <strong>Section 2. </strong> Employer Review and Verification: Employers or their authorized representative must complete and sign Section 2 within three
                                                business days after the employee's first day of employment, and must physically examine, or examine consistent with an alternative procedure 
                                                authorized by the Secretary of DHS, documentation from List A OR a combination of documentation from List B and List C. Enter any additional 
                                                documentation in the Additional Information box; see Instructions.
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="row list-a-fields">
                                                    <div class="col-lg-12">
                                                        <div class="col-header text-center">
                                                            <strong>List A <br> Identity and Employment
                                                                Authorization</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Document Title <i class="fa fa-question-circle-o modalShow" src="section_5_document_title"></i></label>

                                                            <div class="lista_part1_doc">
                                                                <label>
                                                                    <input type="radio" name="lista_part1_doc_select_input" value="select" <?= $pre_form['lista_part1_doc_select_input'] == 'select' ? 'checked' : ''?>> Select from List
                                                                </label> &nbsp;
                                                                <label>
                                                                    <input type="radio" name="lista_part1_doc_select_input" value="input" <?= $pre_form['lista_part1_doc_select_input'] == 'input' ? 'checked' : ''?>> Text
                                                                </label>
                                                                <div id="lista_part1_doc_text" style="display: none">
                                                                    <input type="text" id="lista_part1_doc_text_val" placeholder="Write Here" name="section2_lista_part1_document_title_text_val" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="select lista_part1_doc" id="lista_part1_doc_select">
                                                                <select class="form-control"
                                                                        name="section2_lista_part1_document_title"
                                                                        id="section2_lista_part1_document_title">
                                                                    <option value="n_a">N/A</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Issuing Authority <i class="fa fa-question-circle-o modalShow" src="section_5_issuing_authority"></i></label>

                                                            <div class="lista_part1_issuing">
                                                                <label>
                                                                    <input type="radio" name="lista_part1_issuing_select_input" value="select" <?= $pre_form['lista_part1_issuing_select_input'] == 'select' ? 'checked' : ''?>> Select from List
                                                                </label> &nbsp;
                                                                <label>
                                                                    <input type="radio" name="lista_part1_issuing_select_input" value="input" <?= $pre_form['lista_part1_issuing_select_input'] == 'input' ? 'checked' : ''?>> Text
                                                                </label>
                                                                <div id="lista_part1_issuing_text" style="display: none">
                                                                    <input type="text" id="lista_part1_issuing_text_val" placeholder="Write Here" name="section2_lista_part1_issuing_authority_text_val" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="select lista_part1_issuing" id="lista_part1_issuing_select">
                                                                <select class="form-control"
                                                                        name="section2_lista_part1_issuing_authority"
                                                                        id="section2_lista_part1_issuing_authority">
                                                                    <option value="n_a">N/A</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Document Number <i class="fa fa-question-circle-o modalShow" src="section_5_document_number"></i></label>
                                                            <input type="text" name="section2_lista_part1_document_number"
                                                                    value="<?= isset($pre_form['section2_lista_part1_document_number']) && !empty($pre_form['section2_lista_part1_document_number']) ? $pre_form['section2_lista_part1_document_number'] : "";?>"
                                                                    id="section2_lista_part1_document_number"
                                                                    class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Expiration Date <i class="fa fa-question-circle-o modalShow" src="section_5_expiration_date"></i></label>
                                                            <input type="text" 
                                                            
                                                            readonly
                                                            name="section2_lista_part1_expiration_date"
                                                                    id="section2_lista_part1_expiration_date"
                                                                    value="<?= isset($pre_form['section2_lista_part1_expiration_date']) && !empty($pre_form['section2_lista_part1_expiration_date']) && $pre_form['section2_lista_part1_expiration_date'] != null ? date('m-d-Y',strtotime($pre_form['section2_lista_part1_expiration_date'])) : "";?>"
                                                                    class="form-control date_picker2" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row bg-gray list-a-fields">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Document Title <i class="fa fa-question-circle-o modalShow" src="section_51_document_title"></i></label>
                                                            <!--                                                    <input type="text"  class="form-control">-->
                                                            <div class="lista_part2_doc">
                                                                <label>
                                                                    <input type="radio" name="lista_part2_doc_select_input" value="select" <?= $pre_form['lista_part2_doc_select_input'] == 'select' ? 'checked' : ''?>> Select from List
                                                                </label> &nbsp;
                                                                <label>
                                                                    <input type="radio" name="lista_part2_doc_select_input" value="input" <?= $pre_form['lista_part2_doc_select_input'] == 'input' ? 'checked' : ''?>> Text
                                                                </label>
                                                                <div id="lista_part2_doc_text" style="display: none">
                                                                    <input type="text" id="lista_part2_doc_text_val" placeholder="Write Here" name="section2_lista_part2_document_title_text_val" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="select lista_part2_doc" id="lista_part2_doc_select">
                                                                <select class="form-control"
                                                                        name="section2_lista_part2_document_title"
                                                                        id="section2_lista_part2_document_title">
                                                                    <option value="n_a">N/A</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Issuing Authority <i class="fa fa-question-circle-o modalShow" src="section_51_issuing_authority"></i></label>
                                                            <!--                                                    <input type="text"  class="form-control">-->
                                                            <div class="lista_part2_issuing">
                                                                <label>
                                                                    <input type="radio" name="lista_part2_issuing_select_input" value="select" <?= $pre_form['lista_part2_issuing_select_input'] == 'select' ? 'checked' : ''?>> Select from List
                                                                </label> &nbsp;
                                                                <label>
                                                                    <input type="radio" name="lista_part2_issuing_select_input" value="input" <?= $pre_form['lista_part2_issuing_select_input'] == 'input' ? 'checked' : ''?>> Text
                                                                </label>
                                                                <div id="lista_part2_issuing_text" style="display: none">
                                                                    <input type="text" id="lista_part2_issuing_text_val" placeholder="Write Here" name="section2_lista_part2_issuing_authority_text_val" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="select lista_part2_issuing" id="lista_part2_issuing_select">
                                                                <select class="form-control"
                                                                        name="section2_lista_part2_issuing_authority"
                                                                        id="section2_lista_part2_issuing_authority">
                                                                    <option value="n_a">N/A</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Document Number <i class="fa fa-question-circle-o modalShow" src="section_51_document_number"></i></label>
                                                            <input type="text" name="section2_lista_part2_document_number"
                                                                    id="section2_lista_part2_document_number"
                                                                    value="<?= isset($pre_form['section2_lista_part2_document_number']) && !empty($pre_form['section2_lista_part2_document_number']) ? $pre_form['section2_lista_part2_document_number'] : "";?>"
                                                                    class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Expiration Date <i class="fa fa-question-circle-o modalShow" src="section_51_expiration_date"></i></label>
                                                            <input type="text" name="section2_lista_part2_expiration_date"
                                                                    id="section2_lista_part2_expiration_date"
                                                                    value="<?= isset($pre_form['section2_lista_part2_expiration_date']) && !empty($pre_form['section2_lista_part2_expiration_date']) && $pre_form['section2_lista_part2_expiration_date'] != null ? date('m-d-Y',strtotime($pre_form['section2_lista_part2_expiration_date'])) : "";?>"
                                                                    class="form-control date_picker2" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row list-a-fields">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Document Title <i class="fa fa-question-circle-o modalShow" src="section_52_document_title"></i></label>
                                                            <!--                                                    <input type="text"  class="form-control">-->
                                                            <div class="lista_part3_doc">
                                                                <label>
                                                                    <input type="radio" name="lista_part3_doc_select_input" value="select" <?= $pre_form['lista_part3_doc_select_input'] == 'select' ? 'checked' : ''?>> Select from List
                                                                </label> &nbsp;
                                                                <label>
                                                                    <input type="radio" name="lista_part3_doc_select_input" value="input" <?= $pre_form['lista_part3_doc_select_input'] == 'input' ? 'checked' : ''?>> Text
                                                                </label>
                                                                <div id="lista_part3_doc_text" style="display: none">
                                                                    <input type="text" id="lista_part3_doc_text_val" placeholder="Write Here" name="section2_lista_part3_document_title_text_val" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="select lista_part3_doc" id="lista_part3_doc_select">
                                                                <select class="form-control"
                                                                        name="section2_lista_part3_document_title"
                                                                        id="section2_lista_part3_document_title">
                                                                    <option value="n_a">N/A</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Issuing Authority <i class="fa fa-question-circle-o modalShow" src="section_52_issuing_authority"></i></label>
                                                            <!--                                                    <input type="text" class="form-control">-->
                                                            <div class="lista_part3_issuing">
                                                                <label>
                                                                    <input type="radio" name="lista_part3_issuing_select_input" value="select" <?= $pre_form['lista_part3_issuing_select_input'] == 'select' ? 'checked' : ''?>> Select from List
                                                                </label> &nbsp;
                                                                <label>
                                                                    <input type="radio" name="lista_part3_issuing_select_input" value="input" <?= $pre_form['lista_part3_issuing_select_input'] == 'input' ? 'checked' : ''?>> Text
                                                                </label>
                                                                <div id="lista_part3_issuing_text" style="display: none">
                                                                    <input type="text" id="lista_part3_issuing_text_val" placeholder="Write Here" name="section2_lista_part3_issuing_authority_text_val" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="select lista_part3_issuing" id="lista_part3_issuing_select">
                                                                <select class="form-control"
                                                                        name="section2_lista_part3_issuing_authority"
                                                                        id="section2_lista_part3_issuing_authority">
                                                                    <option value="n_a">N/A</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Document Number <i class="fa fa-question-circle-o modalShow" src="section_52_document_number"></i></label>
                                                            <input type="text" name="section2_lista_part3_document_number"
                                                                    id="section2_lista_part3_document_number"
                                                                    value="<?= isset($pre_form['section2_lista_part3_document_number']) && !empty($pre_form['section2_lista_part3_document_number']) ? $pre_form['section2_lista_part3_document_number'] : "";?>"
                                                                    class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Expiration Date <i class="fa fa-question-circle-o modalShow" src="section_52_expiration_date"></i></label>
                                                            <input type="text" name="section2_lista_part3_expiration_date"
                                                                    id="section2_lista_part3_expiration_date"
                                                                    value="<?= isset($pre_form['section2_lista_part3_expiration_date']) && !empty($pre_form['section2_lista_part3_expiration_date']) && $pre_form['section2_lista_part3_expiration_date'] != null ? date('m-d-Y',strtotime($pre_form['section2_lista_part3_expiration_date'])) : "";?>"
                                                                    class="form-control date_picker2" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="form-group autoheight">
                                                            <label>Additional Information <i class="fa fa-question-circle-o modalShow" src="section_52_additional_information"></i></label>
                                                            <textarea class="form-control textarea" name="section2_additional_information" id="section2_additional_information">
                                                                <?= isset($pre_form['section2_additional_information']) && !empty($pre_form['section2_additional_information']) && $pre_form['section2_additional_information'] != null ? $pre_form['section2_additional_information'] : "";?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <label for="page_status">
                                                            <input type="checkbox" name="section2_authorized_alternative_procedure" value="yes" <?php echo ($pre_form['section2_alternative_procedure'] == 1 ? 'checked="checked"' : ''); ?>  />
                                                            Check here if you used an alternative procedure authorized by DHS to examine documents.
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 list-b-fields">
                                                        <div class="col-header text-center">
                                                            <strong>List B <br> Identity</strong>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Document Title <i class="fa fa-question-circle-o modalShow" src="section_53_document_title"></i></label>

                                                            <div class="select">
                                                                <select class="form-control"
                                                                        name="section2_listb_document_title"
                                                                        id="section2_listb_document_title"></select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Issuing Authority <i class="fa fa-question-circle-o modalShow" src="section_53_issuing_authority"></i></label>
                                                            <!--                                                    <input type="text"class="form-control">-->
                                                            <div class="list_b_auth">
                                                                <label>
                                                                    <input type="radio" name="listb-auth-select-input" value="select" <?= $pre_form['listb_auth_select_input'] == 'select' ? 'checked' : ''?>> Select from List
                                                                </label> &nbsp;
                                                                <label>
                                                                    <input type="radio" name="listb-auth-select-input" value="input" <?= $pre_form['listb_auth_select_input'] == 'input' ? 'checked' : ''?>> Text
                                                                </label>
                                                                <div id="list_b_auth_text" style="display: none">
                                                                    <input type="text" id="list_b_auth_text_val" placeholder="Write Here" name="section2_listb_issuing_authority_text_val" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="select list_b_auth" id="list_b_auth_select">
                                                                <select class="form-control"
                                                                        name="section2_listb_issuing_authority"
                                                                        id="section2_listb_issuing_authority">
                                                                    <option value="n_a">N/A</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Document Number <i class="fa fa-question-circle-o modalShow" src="section_53_document_number"></i></label>
                                                            <input type="text" name="section2_listb_document_number"
                                                                    value="<?= isset($pre_form['section2_listb_document_number']) && !empty($pre_form['section2_listb_document_number']) ? $pre_form['section2_listb_document_number'] : "";?>"
                                                                    id="section2_listb_document_number" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Expiration Date <i class="fa fa-question-circle-o modalShow" src="section_53_expiration_date"></i></label>
                                                            <input type="text" name="section2_listb_expiration_date"
                                                                    id="section2_listb_expiration_date"
                                                                    value="<?= isset($pre_form['section2_listb_expiration_date']) && !empty($pre_form['section2_listb_expiration_date']) && $pre_form['section2_listb_expiration_date'] != null ? date('m-d-Y',strtotime($pre_form['section2_listb_expiration_date'])) : "";?>"
                                                                    class="form-control date_picker2" readonly autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 list-b-fields">
                                                        <div class="col-header text-center">
                                                            <strong>List C <br> Employment Authorization</strong>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Document Title <i class="fa fa-question-circle-o modalShow" src="section_54_document_title"></i></label>

                                                            <div class="select">
                                                                <select class="form-control"
                                                                        name="section2_listc_document_title"
                                                                        id="section2_listc_document_title"></select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="listc_extra_field"
                                                                style="display: none">
                                                            <label>&nbsp;</label>
                                                            <input type="text" name="section2_listc_dhs_extra_field"
                                                                    id="section2_listc_extra_field" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Issuing Authority <i class="fa fa-question-circle-o modalShow" src="section_54_issuing_authority"></i></label>
                                                            <div class="list_c_auth">
                                                                <label>
                                                                    <input type="radio" name="listc-auth-select-input" value="select" <?= $pre_form['listc_auth_select_input'] == 'select' ? 'checked' : ''?>> Select from List
                                                                </label> &nbsp;
                                                                <label>
                                                                    <input type="radio" name="listc-auth-select-input" value="input" <?= $pre_form['listc_auth_select_input'] == 'input' ? 'checked' : ''?>> Text
                                                                </label>
                                                                <div id="list_c_auth_text" style="display: none">
                                                                    <input type="text" id="list_c_auth_text_val" placeholder="Write Here" name="section2_listc_issuing_authority_text_val" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="select list_c_auth" id="list_c_auth_select">
                                                                <select class="form-control"
                                                                        name="section2_listc_issuing_authority"
                                                                        id="section2_listc_issuing_authority">
                                                                    <option value="n_a" selected>N/A</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Document Number <i class="fa fa-question-circle-o modalShow" src="section_54_document_number"></i></label>
                                                            <input type="text" name="section2_listc_document_number"
                                                                    value="<?= isset($pre_form['section2_listc_document_number']) && !empty($pre_form['section2_listc_document_number']) ? $pre_form['section2_listc_document_number'] : "";?>"
                                                                    id="section2_listc_document_number" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Expiration Date <i class="fa fa-question-circle-o modalShow" src="section_54_expiration_date"></i></label>
                                                            <input type="text" name="section2_listc_expiration_date"
                                                                    id="section2_listc_expiration_date"
                                                                    value="<?= isset($pre_form['section2_listc_expiration_date']) && !empty($pre_form['section2_listc_expiration_date']) && $pre_form['section2_listc_expiration_date'] != null ? date('m-d-Y',strtotime($pre_form['section2_listc_expiration_date'])) : "";?>"
                                                                    class="form-control date_picker2" readonly autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <p><strong>Certification: I attest, under penalty of perjury, that (1) I have
                                                examined the document(s) presented by the above-named employee,
                                                (2) the above-listed document(s) appear to be genuine and to relate to the
                                                employee named, and (3) to the best of my knowledge the
                                                employee is authorized to work in the United States.</strong></p>

                                        <div class="form-group autoheight">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    <p><strong>The employee's first day of employment (mm/dd/yyyy):<i class="fa fa-question-circle-o modalShow" src="section_6_employee_1st_day_of_employment "></i> (See
                                                            instructions for exemptions) <span class="staric">*</span> </strong></p>
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="text" name="section2_firstday_of_emp_date"
                                                            id="section2_firstday_of_emp_date"
                                                            class="form-control date_picker2" readonly autocomplete="off" value="<?= isset($pre_form['section2_firstday_of_emp_date']) && !empty($pre_form['section2_firstday_of_emp_date']) ? date('m-d-Y',strtotime($pre_form['section2_firstday_of_emp_date'])) : "";?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hr-box">
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Signature of Employer or Authorized Representative <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_6_signature"></i></label>
                                                            <?php if(isset($pre_form['section3_emp_sign']) && !empty($pre_form['section3_emp_sign'])) { ?>
                                                                <img style="max-height: <?= SIGNATURE_MAX_HEIGHT?>;" src="<?php echo $pre_form['section3_emp_sign']; ?>" class="esignaturesize" />
                                                            <?php } else { ?>
                                                                <!-- the below loaded view add e-signature -->
                                                                <a class="btn btn-success btn-sm sign_of_emp_or_aut_rep" href="javascript:;">Create E-Signature</a>
                                                                <div class="img-full">
                                                                    <img style="max-height: <?= SIGNATURE_MAX_HEIGHT?>;" src=""  id="sign_of_emp_or_aut_rep_img" />
                                                                </div>
                                                                <input type="hidden" name="section2_sig_emp_auth_rep" id="section2_emp_sign">
                                                            <?php } ?> 
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Today's Date (mm/dd/yyyy) <span class="staric">*</span> <i
                                                                    class="fa fa-question-circle-o modalShow" src="section_6_today_date"></i></label>
                                                            <input name="section2_today_date" id="section2_today_date" readonly
                                                                    class="form-control date_picker" readonly type="text" autocomplete="off"  value="<?= date('m-d-Y');?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Title of Employer or Authorized Representative <span class="staric">*</span> <i
                                                                    class="fa fa-question-circle-o modalShow" src="section_6_title"></i></label>
                                                            <input name="section2_title_of_emp" id="section2_title_of_emp"
                                                                    class="form-control" type="text" autocomplete="nope" value="<?= isset($pre_form['section2_title_of_emp']) && !empty($pre_form['section2_title_of_emp']) ? $pre_form['section2_title_of_emp'] : "";?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Last Name of Employer or Authorized Representative <span class="staric">*</span> <i
                                                                    class="fa fa-question-circle-o modalShow" src="section_6_last_name"></i></label>
                                                            <input name="section2_last_name_of_emp"
                                                                    id="section2_last_name_of_emp" class="form-control"
                                                                    type="text" value="<?= $last_name;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>First Name of Employer or Authorized Representative <span class="staric">*</span> <i
                                                                    class="fa fa-question-circle-o modalShow" src="section_6_first_name"></i>
                                                            </label>
                                                            <input name="section2_first_name_of_emp"
                                                                    id="section2_first_name_of_emp" class="form-control"
                                                                    type="text" value="<?= $first_name;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Employer's Business or Organization Name <span class="staric">*</span> <i
                                                                    class="fa fa-question-circle-o modalShow" src="section_6_organization_name"></i></label>
                                                            <input name="section2_emp_business_name"
                                                                    id="section2_emp_business_name" class="form-control"
                                                                    type="text" value="<?= isset($pre_form['section2_emp_business_name']) && !empty($pre_form['section2_emp_business_name']) ? $pre_form['section2_emp_business_name'] : "";?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Employer's Business or Organization Address <span class="staric">*</span> <i
                                                                    class="fa fa-question-circle-o modalShow" src="section_6_organization_address"></i></label>
                                                            <input name="section2_emp_business_address" disableautocomplete
                                                                    id="section2_emp_business_address" class="form-control"
                                                                    type="text" autocomplete="nope" value="<?= isset($pre_form['section2_emp_business_address']) && !empty($pre_form['section2_emp_business_address']) ? $pre_form['section2_emp_business_address'] : "";?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>City or Town <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_6_city_or_town"></i></label>
                                                            <input name="section2_city_town" id="section2_city_town"
                                                                    class="form-control" type="text" autocomplete="nope" value="<?= isset($pre_form['section2_city_town']) && !empty($pre_form['section2_city_town']) ? $pre_form['section2_city_town'] : "";?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>State <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_6_state"></i></label>

                                                            <div class="select">
                                                                <select class="form-control" name="section2_state"
                                                                        id="section2_state">

                                                                        <option class="input-grey" value="" ></option>

                                                                    <?php foreach ($states as $state) {
                                                                        echo '<option value="' . $state['state_code'] . '"">' . $state['state_name'] . '</option>';
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>ZIP Code <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_6_zip_code"></i></label>
                                                            <input name="section2_zip_code" id="section2_zip_code"
                                                                    value="<?= isset($pre_form['section2_zip_code']) && !empty($pre_form['section2_zip_code']) ? $pre_form['section2_zip_code'] : "";?>" class="form-control" type="text" autocomplete="nope">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>    


                                    <!-- Un Serialize Preparer Options Data -->
                                    <?php
                                    $db_preparer_serialized_data = sizeof($pre_form)>0 && $pre_form['section1_preparer_or_translator'] != NULL ? unserialize($pre_form['section1_preparer_or_translator']) : array();
                                    ?>
                                    <div class="hr-box">
                                        <div class="hr-box-header text-center">
                                            <strong>
                                                Supplement A,
                                                <br> 
                                                Preparer and/or Translator Certification for Section 1
                                                <br><i class="fa fa-question-circle-o modalShow" src="section_4_preparer_translator_certification"></i>
                                            </strong>
                                            <em>Department of Homeland Security<br> U.S Citizenship and Immigration Services</em>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                    <label class="control control--radio">
                                                        I did not use a preparer or translator.
                                                        <input class="" name="section1_preparer_or_translator" value="not-used" type="radio" <?= $pre_form['section1_preparer_or_translator'] == 'not-used' ? 'checked' : ''; ?> <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                                    <label class="control control--radio">
                                                        A preparer(s) and/or translator(s) assisted the employee in completing Section 1
                                                        <input class="" name="section1_preparer_or_translator" value="used" type="radio" <?= $pre_form['section1_preparer_or_translator'] == 'used' ? 'checked' : ''; ?> <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <!-- <div class="preparer-number-div">
                                                    <div class="col-lg-12">
                                                        <div class="form-group autoheight">
                                                            <div class="select">
                                                                <select class="form-control" name="number-of-preparer" id="number-of-preparer" <?php if(!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']){echo 'disabled="disabled"';} ?>>
                                                                    <option value="1" <?php echo sizeof($db_preparer_serialized_data)>0 && $db_preparer_serialized_data['number-of-preparer'] == '1' ? 'selected="selected"': ''?>>1</option>
                                                                    <option value="2" <?php echo sizeof($db_preparer_serialized_data)>0 && $db_preparer_serialized_data['number-of-preparer'] == '2' ? 'selected="selected"': ''?>>2</option>
                                                                    <option value="3" <?php echo sizeof($db_preparer_serialized_data)>0 && $db_preparer_serialized_data['number-of-preparer'] == '3' ? 'selected="selected"': ''?>>3</option>
                                                                    <option value="4" <?php echo sizeof($db_preparer_serialized_data)>0 && $db_preparer_serialized_data['number-of-preparer'] == '4' ? 'selected="selected"': ''?>>4</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="col-lg-12 preparer-number-div">
                                                    <p class="full-width">(Fields below must be completed and signed when preparers and/or translators assist an employee in completing Section 1.)</p>
                                                </div>
                                            </div>

                                            <!--  -->
                                            <div class="jsTranslatorBlock <?= $pre_form['section1_preparer_or_translator'] == 'used' ? '' : 'hidden'; ?>">
                                                <!--  -->
                                                <div class="row">
                                                    <div class="col-sm-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Last Name (Family Name) from Section 1.
                                                            </label>
                                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_last_name']; ?>" class="form-control input-lg input-grey jsEmployeeLastName" disabled />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label>First Name (Given Name) from Section 1.
                                                            </label>
                                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_first_name']; ?>" class="form-control input-lg input-grey jsEmployeeFirstName" disabled />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Middle Initial (If Any) from Section 1.
                                                            </label>
                                                            <input autocomplete="nope" type="text" value="<?= $pre_form['section1_middle_initial']; ?>" class="form-control input-lg input-grey jsEmployeeMiddleInitial" disabled />
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
                                                    
                                                    <?php $this->load->view('form_i9/preparer', [
                                                        'preparerArray' => $pre_form['section1_preparer_json']
                                                            ? json_decode(
                                                                $pre_form['section1_preparer_json'],
                                                                true
                                                            ) : []
                                                    ]); ?>
                                                </div>
                                            </div>
                                            <!--  -->
                                        </div>
                                    </div>

                                   
                                  
                                    <?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus'] && empty($pre_form['section1_last_name']) && empty($pre_form['section1_first_name'])) { ?>
                                        <div class="row">
                                            <div class="col-lg-12 text-center">
                                                <button onclick="func_save_i9_section_1();" type="button" class="btn btn-success break-word-text">Save</button>
                                            </div>
                                        </div>
                                    <?php } ?>    

                                    <!-- Section 2 Starts    -->

                                    <?php if($section2_flag && $signed_flag == true) { ?>
                                        <div class="section-2">
                                            <div class="hr-box">
                                                <div class="hr-box-header text-center">
                                                    <strong>Supplement B,<br>Reverification and Rehire (formerly Section 3)</strong>
                                                    <br>
                                                    <em>Department of Homeland Security<br> U.S Citizenship and Immigration Services</em>
                                                </div>
                                                <div class="hr-innerpadding">
                                                    <!--  -->
                                                    <div class="row">
                                                        <div class="col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label>Last Name (Family Name) from Section 1.
                                                                </label>
                                                                <input autocomplete="nope" type="text" value="<?= $pre_form['section1_last_name']; ?>" class="form-control input-lg input-grey jsEmployeeLastName" disabled />
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label>First Name (Given Name) from Section 1.
                                                                </label>
                                                                <input autocomplete="nope" type="text" value="<?= $pre_form['section1_first_name']; ?>" class="form-control input-lg input-grey jsEmployeeFirstName" disabled />
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label>Middle Initial (If Any) from Section 1.
                                                                </label>
                                                                <input autocomplete="nope" type="text" value="<?= $pre_form['section1_middle_initial']; ?>" class="form-control input-lg input-grey jsEmployeeMiddleInitial" disabled />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <!--  -->
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p>
                                                                <strong>Instructions:</strong> This supplement replaces Section 3 on the previous version of Form I-9. Only use this page if your employee requires                                                     of Form I-9. The preparer and/or translator must enter the employee's name in the spaces provided above. Each preparer or translator
                                                                reverification, is rehired within three years of the date of the original Form I-9 was completed, or provides proof of a legal name change. Enter
                                                                the employee's name in the fields above. Use a new section for each reverification or rehire. Review the Form I-9 instruction before
                                                                completing this page. Keep this page as part of the employee's Form I-9 record. Additional guidance can be found in the_ <br>
                                                                <a href="https://www.uscis.gov/i-9-central/form-i-9-resources/handbook-for-employers-m-274">Handbook for Employee's: Guidance for Completing Form I-9 (M-274)</a>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <?php $this->load->view('form_i9/authorized', [
                                                        'authorizedArray' => $pre_form['section3_authorized_json']
                                                            ? json_decode(
                                                                $pre_form['section3_authorized_json'],
                                                                true
                                                            ) : []
                                                    ]); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>

                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="consent_and_notice_section">
                                        <input type="hidden" id="form-id" name="form-id" value="<?php echo empty($pre_form) ? 0 : $pre_form['sid']?>"/>
                                        <input type="hidden" id="perform_action" name="perform_action" value="sign_document" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <input type="hidden" id="user_type" name="user_type" value="<?php echo $users_type; ?>" />
                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $users_sid; ?>" />
                                        <input type="hidden" id="ip_address" name="ip_address" value="<?php echo getUserIP(); ?>" />
                                        <input type="hidden" id="user_agent" name="user_agent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                        <input type="hidden" id="first_name" name="first_name" value="<?php echo $first_name; ?>" />
                                        <input type="hidden" id="last_name" name="last_name" value="<?php echo $last_name; ?>" />
                                        <input type="hidden" id="email_address" name="email_address" value="<?php echo $email; ?>" />
                                        <input type="hidden" id="signature_timestamp" name="signature_timestamp" value="<?php echo date('d/m/Y H:i:s'); ?>" />

                                        <input type="hidden" id="active_signature" name="active_signature" value="" />
                                        <input type="hidden" id="signature" name="signature" value="" />
                                        <input type="hidden" id="signature_bas64_image" name="signature_bas64_image" value="" />

                                        <input type="hidden" id="init_signature_bas64_image" name="init_signature_bas64_image" value="" />

                                        <input type="hidden" id="signature_ip_address" name="signature_ip_address" value="" />

                                        <input type="hidden" id="signature_user_agent" name="signature_user_agent" value="" />

                                        <hr />

                                        <div class="row">
                                            <div class="col-xs-12 text-justify">
                                                <p>
                                                    <?php echo str_replace("{{company_name}}",$company_name,SIGNATURE_CONSENT_HEADING); ?>
                                                </p>
                                                <p>
                                                    <?php echo SIGNATURE_CONSENT_TITLE; ?>
                                                </p>
                                                <p>
                                                    <?php echo str_replace("{{company_name}}",$company_name,SIGNATURE_CONSENT_DESCRIPTION); ?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php $consent = isset($pre_form['employer_flag']) ? $pre_form['employer_flag'] : 0; ?>
                                                <label class="control control--checkbox">
                                                    <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                                    <input <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <hr />


                                            <div class="row">
                                                <div class="col-lg-12 text-center">
                                                    <button onclick="func_save_e_signature();" type="button" class="btn btn-success break-word-text"><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                                </div>
                                            </div>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>

            </div>
        </div>
    </div>
</div>
<?php $this->load->view('form_i9/pop_up_info'); ?>
<?php $this->load->view('static-pages/e_signature_popup'); ?>

<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;" >
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;">
            Please wait while we are uploading I9 form.
        </div>
    </div>
</div>
<!-- Loader End -->


    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/mFileUploader/index.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/i9-form.js"></script>
    <script type="text/javascript">
        $("#btn_eev_document").hide();
        //
        $('#uploar_i9_form').mFileUploader({
            fileLimit: -1, // Default is '2MB', Use -1 for no limit (Optional)
            allowedTypes: ['pdf'],  //(Optional)
            text: 'Click / Drag to upload', // (Optional)
            onSuccess: (file, event) => {
                $("#btn_eev_document").show();
            }, // file will the uploaded file object and event will be the actual event  (Optional)
            onError: (errorCode, event) => {
                $("#btn_eev_document").hide();
            }, // errorCode will either 'size' or 'type' and event will be the actual event  (Optional)
            placeholderImage: '' // Default is empty ('') but can be set any image  (Optional)
        });

        $("#btn_eev_document").on('click', function(e){
            e.preventDefault();
            var upload_file = $('#uploar_i9_form').mFileUploader('get');

            if ($.isEmptyObject(upload_file)) {
                alertify.alert('ERROR!', 'Please select a file to upload.');
                return false;
            } else if (upload_file.hasError == true) {
                alertify.alert('ERROR!', 'Please select a valid file format.');
                return false;
            } else {
                $('#document_loader').show();

                var form_data = new FormData();
                form_data.append('document', upload_file);
                form_data.append('document_sid', '<?php echo $pre_form['sid']; ?>');
                form_data.append('user_sid', '<?php echo $pre_form['user_sid']; ?>');
                form_data.append('user_type', '<?php echo $pre_form['user_type']; ?>');
                form_data.append('perform_action', 'upload_i9_form');

                $.ajax({
                    url: '<?= base_url('form_i9/ajax_responder') ?>',
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function (resp) {
                        $('#document_loader').hide();
                        if(resp.Status === false){
                            alertify.alert('ERROR!', resp.Response);
                            return;
                        }
                        // On success
                        alertify.alert('SUCCESS!', 'You have successfully uploaded the I9 form.', function(){
                            window.location.href = "<?=base_url("hr_documents_management/documents_assignment/employee/".( $pre_form['user_sid'])."");?>";
                        });
                        
                    },
                    error: function () {
                    }
                });
            }
        });

        $('.modalShow').click(function(event){
            
            event.preventDefault();
            var info_id = $(this).attr("src");
            var title_string = $(this).parent().text(); 
            var model_title = title_string.replace("*", "");
            if (info_id == "section_2_alien_number") {
                if($('#alien_authorized_to_work').is(':checked')) { 
                    info_id = 'section_21_alien_number'; 
                }
            }
            var model_content =  $('#'+info_id).html(); 
            var mymodal = $('#myPopupModal');
            mymodal.find('#popup-modal-title').text(model_title);
            mymodal.find('#feed_me_a_text').html(model_content);
            mymodal.modal('show');
        });

        $(document).ready(function(){
           
            $(document).on('click','#close-popup-modal',function(){
                $('#myPopupModal').modal('toggle');
            });
            var radio_val = '<?php echo $pre_form['section1_penalty_of_perjury']?>';
            radio_val != '' ? i9_manager.fill_part1_title(radio_val) : '';
            radio_val != '' ? i9_manager.fill_list_c(radio_val) : '';


            var access_level_plus = '<?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {echo 1;} else {echo 0;} ?>';
            var section1_validate = '<?php if (empty($pre_form['section1_last_name']) && empty($pre_form['user_consent']) ) {echo 1;} else {echo 0;} ?>';
            var exist_e_signature_data = '<?php if(!empty($e_signature_data)) { echo true; } else {echo false; }?>';
            var filler_e_signature_exist = '<?php if(!empty($filler_e_signature_data)) { echo true; } else {echo false; }?>';

            
            
            if(exist_e_signature_data == 1){
                // $('#consent_and_notice_section').show();
                var signature = '<?php echo isset($signature)? $signature : ""; ?>';
                var base64_url = '<?php echo isset($e_signature_data['signature_bas64_image']) ? '' : ""; ?>';
                var signature_type = '<?php echo isset($active_signature) ? $active_signature : ""; ?>';
                $('#signature_bas64_image').val(base64_url);
                $('#signature').val(signature);
                $('#active_signature').val(signature_type);
            } else {
                // $('#consent_and_notice_section').hide();
            }

            $('.verify-doc').click(function(){
                var doc_id = $(this).attr('data-id');
                $.ajax({
                    url: '<?= base_url('form_i9/verify_docs') ?>',
                    type: 'post',
                    data: {
                        id:doc_id
                    },
                    success: function(data){
                        alertify.alert('Document verified successfully');
                        $('#'+doc_id).html('Verified');
                    },
                    error: function(){

                    }

                })
            });

            $('.date_picker').datepicker({
                dateFormat: 'mm-dd-yy',
                setDate: new Date(),
                maxDate: new Date,
                minDate: new Date()
            });

            $('.date_picker2').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo STARTING_DATE_LIMIT; ?>"
            });

            $('.date_of_birth').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
            }).val();

            var option_val = '<?php echo sizeof($pre_form)>0 ? $pre_form['section1_penalty_of_perjury'] : '' ?>';
            if(option_val == 'alien-work'){
                $('#option-4').show();
            } else if(option_val == 'permanent-resident'){
                $('#option-3').show();
                $('#option-4').hide();
            } else{
                $('#option-3').hide();
                $('#option-4').hide();
            }
            var prep_div = '<?php echo $pre_form['section1_preparer_or_translator']; ?>';
            if(prep_div == 'used'){
                $('.preparer-number-div').show();
            } else{
                $('.preparer-number-div').hide();
            }

            $('input[name="section1_preparer_or_translator"]').on('change',function(){
                if(filler_e_signature_exist == 1){
                    var base64_url = '<?php echo isset($filler_e_signature_data['signature_bas64_image']) ? $filler_e_signature_data['signature_bas64_image']: ""; ?>';
                    $('#section1_admin_preparer_signature').val(base64_url);
                } else {
                    if($(this).val() == 'used'){
                        common_get_e_signature();
                    }
                }

                if($(this).val()!='not-used'){
                    $('.preparer-number-div').show();
                }else{
                    $('.preparer-number-div').hide();
                }
            });

            $('input[name="section1_penalty_of_perjury"]').on('change',function(){
                var radio_val = $(this).val();
                if(radio_val=='citizen' || radio_val=='noncitizen'){
                    $('#option-3').hide();
                    $('#option-4').hide();
                } else if(radio_val=='permanent-resident'){
                    $('#option-3').show();
                    $('#option-4').hide();
                }else if(radio_val=='alien-work'){
                    $('#option-3').show();
                    $('#option-4').show();
                }
                i9_manager.fill_part1_title(radio_val);
                i9_manager.fill_list_c(radio_val);
            });

//            $('#section2_lista_part1_document_title').on('change',function(){
//                var title = $(this).val();
//                i9_manager.fill_part1_authority(title);
//            });
//
//            $('#section2_lista_part3_document_title').on('change',function(){
//                var title = $(this).val();
//                i9_manager.fill_part3_auth(title);
//            });
//
//            $('#section2_listc_document_title').on('change',function(){
//                var title = $(this).val();
//                i9_manager.fill_list_c_auth(title);
//            });
//
//            $('#section2_listb_document_title').on('change',function(){
//                var title = $(this).val();
//                i9_manager.fill_list_b_auth(title);
//            });


            $('#section2_lista_part1_document_title').on('change',function(){
                var title = $(this).val();
                i9_manager.fill_part1_authority(title);
            });

            $('#section2_lista_part3_document_title').on('change',function(){
                var title = $(this).val();
                i9_manager.fill_part3_auth(title);
            });

            $('#section2_listc_document_title').on('change',function(){
                var title = $(this).val();
                i9_manager.fill_list_c_auth(title, section2_listc_issuing_authority);
            });

            $('#section2_listb_document_title').on('change',function(){
                var title = $(this).val();
                i9_manager.fill_list_b_auth(title, section2_listb_issuing_authority);
            });
            option_val != '' && option_val != null ? i9_manager.fill_part1_title(option_val): '';
            option_val != '' && option_val != null ? i9_manager.fill_list_c(option_val) : '';
            i9_manager.fill_listb();

            if (access_level_plus == 1 && section1_validate == 1) {
                $("#i9-form").validate({
                    ignore: ":hidden:not(select)",
                    rules: {
                        section1_last_name: {
                            required: true
                        },
                        section1_first_name: {
                            required: true
                        },
                        // section1_middle_initial: {
                        //     required: true
                        // },
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
                        // section1_middle_initial: {
                        //     required: 'Middle Initial is required.'
                        // },
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
                    submitHandler: function (form) {
                        var radio_val = $('input[name="section1_penalty_of_perjury"]:checked').val();
                        var prepare_radio = $('input[name="section1_preparer_or_translator"]:checked').val();
                        if (radio_val == 'permanent-resident') {
                            if ($('#section1_alien_registration_number_one').val() == '') {
                                alertify.alert("Warning", $('#section1_alien_registration_number_two').val() + ' is required');
                                return false;
                            }

                        } else if (radio_val == 'alien-work') {
                            if ($('#alien_authorized_expiration_date').val() == '') {
                                alertify.alert("Warning", 'An Alien Authorized To Work (Expiration Date) is required');
                                return false;
                            }
                            if ($('#section1_alien_registration_number_one').val() == '' && $('#form_admission_number').val() == '' && $('#foreign_passport_number').val() == '' && $('#country_of_issuance').val() == '') {
                                alertify.alert("Warning", 'You must provide Form I-94 Admission Number OR Alien/USCIS Number OR Foreign Passport Number OR Country Issuance');
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
            } else {
                $("#i9-form").validate({
                    ignore: ":hidden:not(select)",
                    rules: {
                        section2_firstday_of_emp_date: {
                            required: true
                        },
                        section2_today_date: {
                            required: true
                        },
                        section2_title_of_emp: {
                            required: true
                        },
                        section2_last_name_of_emp: {
                            required: true
                        },
                        section2_first_name_of_emp: {
                            required: true
                        },
                        section2_emp_business_name: {
                            required: true
                        },
                        section2_emp_business_address: {
                            required: true
                        },
                        section2_city_town: {
                            required: true
                        },
                        section2_state: {
                            required: true
                        },
                        section2_zip_code: {
                            required: true
                        },
                        section3_today_date: {
                            required: true
                        },
                        section3_name_of_emp: {
                            required: true
                        }
                    },
                    messages: {
                        section2_firstday_of_emp_date: {
                            required: 'Date of first day of employment is required.'
                        },
                        section2_today_date: {
                            required: 'Date of signature of employer or authorized representative is required.'
                        },
                        section2_title_of_emp: {
                            required: 'Title of employer or authorized representative is required.'
                        },
                        section2_last_name_of_emp: {
                            required: 'Last name of employer or authorized representative is required.'
                        },
                        section2_first_name_of_emp: {
                            required: 'First name of employer or authorized representative is required.'
                        },
                        section2_emp_business_name: {
                            required: 'Business name of employer or authorized representative is required.'
                        },
                        section2_emp_business_address: {
                            required: 'Business address of employer or authorized representative is required.'
                        },
                        section2_city_town: {
                            required: 'City/Town of employer or authorized representative is required.'
                        },
                        section2_state: {
                            required: 'State of employer or authorized representative is required.'
                        },
                        section2_zip_code: {
                            required: 'Zip Code of employer or authorized representative is required.'
                        },
                        section3_today_date: {
                            required: 'Date of signature of authorized representative is required.'
                        },
                        section3_name_of_emp: {
                            required: 'Full name of authorized representative is required.'
                        }
                    },
                    submitHandler: function(form){
                        var list_a_document = $('input[name=section2_lista_part1_document_title]').val();
                        var list_b_document = $('#section2_listb_document_title').val();
                        var list_c_document = $('#section2_listc_document_title').val();

                        if (list_a_document == 'n_a') {
                            if (list_b_document == 'n_a' || list_c_document == 'n_a') {
                                alertify.alert("Warning", 'You must physically examine one document from List A OR a combination of one document from List B and one document from List C as listed on the "Lists of Acceptable Documents."');
                                return false;
                            }
                        }
                        form.submit();
                    }
                });

                // A Lists
                var section2_lista_part1_document_title = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_lista_part1_document_title'] : '' ?>";
                var section2_lista_part1_issuing_authority = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_lista_part1_issuing_authority'] : '' ?>";
                var section2_lista_part2_document_title = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_lista_part2_document_title'] : '' ?>";
                var section2_lista_part2_issuing_authority = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_lista_part2_issuing_authority'] : '' ?>";
                var section2_lista_part3_document_title = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_lista_part3_document_title'] : '' ?>";
                var section2_lista_part3_issuing_authority = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_lista_part3_issuing_authority'] : '' ?>";

                // B Lists
                var section2_listb_document_title = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_listb_document_title'] : '' ?>";
                var section2_listb_auth_select_input = "<?php echo sizeof($pre_form)>0 ? $pre_form['listb_auth_select_input'] : '' ?>";
                var section2_listb_issuing_authority = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_listb_issuing_authority'] : '' ?>";

                // C Lists
                var section2_listc_document_title = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_listc_document_title'] : '' ?>";
                var section2_listc_auth_select_input = "<?php echo sizeof($pre_form)>0 ? $pre_form['listc_auth_select_input'] : '' ?>";
                var section2_listc_issuing_authority = "<?php echo sizeof($pre_form)>0 ? $pre_form['section2_listc_issuing_authority'] : '' ?>";

                var lista_part1_doc_select_input = "<?php echo sizeof($pre_form)>0 ? $pre_form['lista_part1_doc_select_input'] : '' ?>";
                var lista_part1_issuing_select_input = "<?php echo sizeof($pre_form)>0 ? $pre_form['lista_part1_issuing_select_input'] : '' ?>";
                var lista_part2_doc_select_input = "<?php echo sizeof($pre_form)>0 ? $pre_form['lista_part2_doc_select_input'] : '' ?>";
                var lista_part2_issuing_select_input = "<?php echo sizeof($pre_form)>0 ? $pre_form['lista_part2_issuing_select_input'] : '' ?>";
                var lista_part3_doc_select_input = "<?php echo sizeof($pre_form)>0 ? $pre_form['lista_part3_doc_select_input'] : '' ?>";
                var lista_part3_issuing_select_input = "<?php echo sizeof($pre_form)>0 ? $pre_form['lista_part3_issuing_select_input'] : '' ?>";

                console.log(section2_lista_part1_document_title);
                console.log(section2_lista_part1_issuing_authority);
                console.log(section2_lista_part2_document_title);
                console.log(section2_lista_part2_issuing_authority);
                console.log(section2_lista_part3_document_title);
                console.log(section2_lista_part3_issuing_authority);

                console.log(section2_listb_document_title);
                console.log(section2_listb_issuing_authority);

                console.log(section2_listc_document_title);
                console.log(section2_listc_issuing_authority);

                preFillLists('section2_lista_part1_document_title',section2_lista_part1_document_title);
                preFillLists('section2_lista_part1_issuing_authority',section2_lista_part1_issuing_authority);
                preFillLists('section2_lista_part2_document_title',section2_lista_part2_document_title);
                preFillLists('section2_lista_part2_issuing_authority',section2_lista_part2_issuing_authority);
                preFillLists('section2_lista_part3_document_title',section2_lista_part3_document_title);
                preFillLists('section2_lista_part3_issuing_authority',section2_lista_part3_issuing_authority);


                preFillLists('section2_listb_document_title',section2_listb_document_title);
                preFillLists('section2_listb_issuing_authority',section2_listb_issuing_authority);


                preFillLists('section2_listc_document_title',section2_listc_document_title);
                preFillLists('section2_listc_issuing_authority',section2_listc_issuing_authority);

                setTimeout(function(){
                    if(section2_listb_auth_select_input != '' && section2_listb_auth_select_input == 'input'){
                        $('input[name="listb-auth-select-input"][value="input"]').prop('checked', true);
                        $('#list_b_auth_text_val').val(section2_listb_issuing_authority);
                        $('#list_b_auth_select').hide(0);
                        $('#list_b_auth_text').show(0);
                    }else{
                        $('input[name="listb-auth-select-input"][value="select"]').prop('checked', true);
                        $('#section2_listb_issuing_authority option[value="'+(section2_listb_issuing_authority)+'"]').prop('selected', true);
                        $('#list_b_auth_text').hide(0);
                    }
                }, 3000);

                setTimeout(function(){
                    if(section2_listc_auth_select_input != '' && section2_listc_auth_select_input == 'input'){
                        $('input[name="listc-auth-select-input"][value="input"]').prop('checked', true);
                        $('#list_c_auth_text_val').val(section2_listc_issuing_authority);
                        $('#list_c_auth_select').hide(0);
                        $('#list_c_auth_text').show(0);
                    }else{
                        $('input[name="listc-auth-select-input"][value="select"]').prop('checked', true);
                        $('#section2_listc_issuing_authority option[value="'+(section2_listc_issuing_authority)+'"]').prop('selected', true);
                        $('#list_c_auth_text').hide(0);
                    }
                }, 3000);
                setTimeout(function(){
                    if(lista_part1_doc_select_input != '' && lista_part1_doc_select_input == 'input'){
                        $('input[name="lista_part1_doc_select_input"][value="input"]').prop('checked', true);
                        $('#lista_part1_doc_text_val').val(section2_lista_part1_document_title);
                        $('#lista_part1_doc_select').hide(0);
                        $('#lista_part1_doc_text').show(0);
                    }else{
                        $('input[name="lista_part1_doc_select_input"][value="select"]').prop('checked', true);
                        $('#section2_lista_part1_document_title option[value="'+(section2_lista_part1_document_title)+'"]').prop('selected', true);
                        $('#lista_part1_doc_text').hide(0);
                    }
                }, 3000);
                setTimeout(function(){
                    if(lista_part1_issuing_select_input != '' && lista_part1_issuing_select_input == 'input'){
                        $('input[name="lista_part1_issuing_select_input"][value="input"]').prop('checked', true);
                        $('#lista_part1_issuing_text_val').val(section2_lista_part1_issuing_authority);
                        $('#lista_part1_issuing_select').hide(0);
                        $('#lista_part1_issuing_text').show(0);
                    }else{
                        $('input[name="lista_part1_issuing_select_input"][value="select"]').prop('checked', true);
                        $('#section2_lista_part1_issuing_authority option[value="'+(section2_lista_part1_issuing_authority)+'"]').prop('selected', true);
                        $('#lista_part1_issuing_text').hide(0);
                    }
                }, 3000);
                
                setTimeout(function(){
                    if(lista_part2_doc_select_input != '' && lista_part2_doc_select_input == 'input'){
                        $('input[name="lista_part2_doc_select_input"][value="input"]').prop('checked', true);
                        $('#lista_part2_doc_text_val').val(section2_lista_part2_document_title);
                        $('#lista_part2_doc_select').hide(0);
                        $('#lista_part2_doc_text').show(0);
                    }else{
                        $('input[name="lista_part2_doc_select_input"][value="select"]').prop('checked', true);
                        $('#section2_lista_part2_document_title option[value="'+(section2_lista_part2_document_title)+'"]').prop('selected', true);
                        $('#lista_part2_doc_text').hide(0);
                    }
                }, 3000);
                setTimeout(function(){
                    if(lista_part2_issuing_select_input != '' && lista_part2_issuing_select_input == 'input'){
                        $('input[name="lista_part2_issuing_select_input"][value="input"]').prop('checked', true);
                        $('#lista_part2_issuing_text_val').val(section2_lista_part2_issuing_authority);
                        $('#lista_part2_issuing_select').hide(0);
                        $('#lista_part2_issuing_text').show(0);
                    }else{
                        $('input[name="lista_part2_issuing_select_input"][value="select"]').prop('checked', true);
                        $('#section2_lista_part2_issuing_authority option[value="'+(section2_lista_part2_issuing_authority)+'"]').prop('selected', true);
                        $('#lista_part2_issuing_text').hide(0);
                    }
                }, 3000);

                setTimeout(function(){
                    if(lista_part3_doc_select_input != '' && lista_part3_doc_select_input == 'input'){
                        $('input[name="lista_part3_doc_select_input"][value="input"]').prop('checked', true);
                        $('#lista_part3_doc_text_val').val(section2_lista_part3_document_title);
                        $('#lista_part3_doc_select').hide(0);
                        $('#lista_part3_doc_text').show(0);
                    }else{
                        $('input[name="lista_part3_doc_select_input"][value="select"]').prop('checked', true);
                        $('#section2_lista_part3_document_title option[value="'+(section2_lista_part3_document_title)+'"]').prop('selected', true);
                        $('#lista_part3_doc_text').hide(0);
                    }
                }, 3000);
                setTimeout(function(){
                    if(lista_part3_issuing_select_input != '' && lista_part3_issuing_select_input == 'input'){
                        $('input[name="lista_part3_issuing_select_input"][value="input"]').prop('checked', true);
                        $('#lista_part3_issuing_text_val').val(section2_lista_part3_issuing_authority);
                        $('#lista_part3_issuing_select').hide(0);
                        $('#lista_part3_issuing_text').show(0);
                    }else{
                        $('input[name="lista_part3_issuing_select_input"][value="select"]').prop('checked', true);
                        $('#section2_lista_part3_issuing_authority option[value="'+(section2_lista_part3_issuing_authority)+'"]').prop('selected', true);
                        $('#lista_part3_issuing_text').hide(0);
                    }
                }, 3000);
            }

        });

        function preFillLists(id, value){
            if(value != 'n_a' && value != '' && value != null){
                $('#'+id).val(value);
                $('#'+id).change();
            }
        }

        function check_file(val) {
            var fileName = $("#" + val).val();
            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                $('.upload-file').hide();
                $('#uploaded-files').hide();
                $('#file-upload-div').append('<div class="form-group form-control autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">'+fileName+'</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
            } else {
                $('#name_' + val).html('No file selected');
            }
        }

        function CancelUpload(){
            $('.upload-file').show();
            if($('#uploaded-files').html() != ''){
                $('#uploaded-files').show();
            }
            $('#file-upload-div').html("");
            $('#name_docs').html("No file selected");
        }

        function DoUpload(){
            var file_data = $('#docs').prop('files')[0];
            var uploader  = $('#uploader').val();
            var form_id  = $('#form-id').val();
            var prefill_flag  = '<?php echo sizeof($pre_form)>0 ? $pre_form['sid'] : -1;?>';
            var form_data = new FormData();
            form_data.append('docs', file_data);
            form_data.append('form_id', prefill_flag == -1 ? form_id : prefill_flag);
            form_data.append('app_id', <?php echo sizeof($pre_form)>0 ? $pre_form['emp_app_sid'] : $employer_sid;?>);
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
                success: function(data){
                    $('#loader').hide();
                    $('#upload').removeClass('disabled-btn');
                    $('#upload').prop('disabled', false);
                    alertify.alert("Success", 'New document has been uploaded');
                    $('.upload-file').show();
                    $('#uploaded-files').show();
                    $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> '+file_data['name']+'</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
                    $('#file-upload-div').html("");
                    $('#name_docs').html("No file selected");
                    if(data!="error"){
                        $('#form-id').val(data);
                    }
                    else{
                        alert('Document Error');
                    }
                },
                error: function(){
                }
            });
        }

        function func_save_e_signature() {
            var section2_signature_exist = $('#section2_emp_sign').val();
            var section3_signature_exist = $('#section3_emp_sign').val();
            
            if(section2_signature_exist == ""){
                alertify.alert("Warning", 'Please Add Employer Or Authorized Representative!');
                return false;
            }

            if(section3_signature_exist == ""){
                alertify.alert("Warning", 'Please Add Authorized Representative!');
                return false;
            }

            if (
				$("#section3_authorized_name_of_emp_1").val().trim() == "" ||
				$("#section3_authorized_signature_1").val().trim() == "" ||
				$("#section3_authorized_today_date_1").val().trim() == ""
			) {
				let errorsArray = [];
				//
				if ($("#section3_authorized_signature_1").val().trim() == "") {
					errorsArray.push("(1) Authorized signature is required.");
				}
				//
				if ($("#section3_authorized_today_date_1").val().trim() == "") {
					errorsArray.push("(1) Authorized today date is required.");
				}
				//
				if ($("#section3_authorized_name_of_emp_1").val().trim() == "") {
					errorsArray.push("(1) Authorized Representative name is required.");
				}
				//
				if (errorsArray.length) {
                    return alertify.alert(
						"Error",
						getErrorsStringFromArray(errorsArray)
					);
				}
			}
			//
            if (
				$("#section3_authorized_name_of_emp_2").val().trim() == "" ||
				$("#section3_authorized_signature_2").val().trim() == "" ||
				$("#section3_authorized_today_date_2").val().trim() == ""
			) {
				let errorsArray = [];
				//
				if ($("#section3_authorized_signature_2").val().trim() == "") {
					errorsArray.push("(2) Authorized signature is required.");
				}
				//
				if ($("#section3_authorized_today_date_2").val().trim() == "") {
					errorsArray.push("(2) Authorized today date is required.");
				}
				//
				if ($("#section3_authorized_name_of_emp_2").val().trim() == "") {
					errorsArray.push("(2) Authorized Representative name is required.");
				}
				//
				if (errorsArray.length) {
                    return alertify.alert(
						"Error",
						getErrorsStringFromArray(errorsArray)
					);
				}
			}
            //
            if (
				$("#section3_authorized_name_of_emp_3").val().trim() == "" ||
				$("#section3_authorized_signature_3").val().trim() == "" ||
				$("#section3_authorized_today_date_3").val().trim() == ""
			) {
				let errorsArray = [];
				//
				if ($("#section3_authorized_signature_3").val().trim() == "") {
					errorsArray.push("(3) Authorized signature is required.");
				}
				//
				if ($("#section3_authorized_today_date_3").val().trim() == "") {
					errorsArray.push("(3) Authorized today date is required.");
				}
				//
				if ($("#section3_authorized_name_of_emp_3").val().trim() == "") {
					errorsArray.push("(3) Authorized Representative name is required.");
				}
				//
				if (errorsArray.length) {
                    return alertify.alert(
						"Error",
						getErrorsStringFromArray(errorsArray)
					);
				}
			}

            if ($('#i9-form').valid()) {
                alertify.confirm(
                    'Are you Sure?',
                    'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                    function () {
                        $('#i9-form').submit();
                    },
                    function () {
                        alertify.alert("Warning", 'Cancelled!');
                    }).set('labels', {ok: 'I Consent and Accept!', cancel: 'Cancel'});
            }
        }

        function getErrorsStringFromArray(errorArray, errorMessage) {
            return (
                "<strong><p>" +
                (errorMessage
                    ? errorMessage
                    : "Please, resolve the following errors") +
                "</p></strong><br >" +
                errorArray.join("<br />")
            );
        }

        $('#section2_lista_part1_document_title').on('change', function() {
            var selecct_val =  this.value ;
            if (selecct_val == 'n_a') {
                $(".list_a_doc_1").prop('required',false);
            } else {
                $(".list_a_doc_1").prop('required',true);
            }
        });

        $('#section2_lista_part2_document_title').on('change', function() {
            var selecct_val =  this.value ;
            if (selecct_val == 'n_a') {
                $(".list_a_doc_2").prop('required',false);
            } else {
                $(".list_a_doc_2").prop('required',true);
            }
        });

        $('#section2_lista_part3_document_title').on('change', function() {
            var selecct_val =  this.value ;
            if (selecct_val == 'n_a') {
                $(".list_a_doc_3").prop('required',false);
            } else {
                $(".list_a_doc_3").prop('required',true);
            }
        });

        $('#section2_listb_document_title').on('change', function() {
            var selecct_val =  this.value ;
            if (selecct_val == 'n_a') {
                $(".list_b_doc").prop('required',false);
            } else {
                $(".list_b_doc").prop('required',true);
            }
        });

        $('#section3_document_title').on('change', function() {
            var selecct_val =  this.value ;
            if (selecct_val == 'n_a') {
                $(".section3_doc").prop('required',false);
            } else {
                $(".section3_doc").prop('required',true);
            }
        });

        function func_save_i9_section_1 () {
            var radio_val = $('input[name="section1_penalty_of_perjury"]:checked').val();
            var prepare_radio = $('input[name="section1_preparer_or_translator"]:checked').val();
            if (radio_val == 'permanent-resident') {
                if ($('#section1_alien_registration_number_one').val() == '') {
                    alertify.alert("Warning", $('#section1_alien_registration_number_two').val() + ' is required');
                    return false;
                }

            } else if (radio_val == 'alien-work') {
                if ($('#alien_authorized_expiration_date').val() == '') {
                    alertify.alert("Warning", 'An Alien Authorized To Work (Expiration Date) is required');
                    return false;
                }
                if ($('#section1_alien_registration_number_one').val() == '' && $('#form_admission_number').val() == '' && $('#foreign_passport_number').val() == '' && $('#country_of_issuance').val() == '') {
                    alertify.alert("Warning", 'You must provide Form I-94 Admission Number OR Alien/USCIS Number OR Foreign Passport Number OR Country Issuance');
                    return false;
                }

            }

            if (prepare_radio == 'used') {
                
                if ($('#section1_admin_preparer_signature').val() == '') {
                    alertify.alert('Please add your e_signature');
                    return false;
                }

                if ($('#section1_preparer_today_date').val() == '' || $('#section1_preparer_last_name').val() == '' || $('#section1_preparer_first_name').val() == '' || $('#section1_preparer_address').val() == '' || $('#section1_preparer_city_town').val() == '' || $('#section1_preparer_state').val() == '' || $('#section1_preparer_zip_code').val() == '') {
                    alertify.alert('Please fill all the information of preparer or translator');
                    return false;
                }
            }

            if ($('#i9-form').valid()) {
                alertify.confirm(
                    'Are you Sure?',
                    'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                    function () {
                        $('#i9-form').submit();
                    },
                    function () {
                        alertify.alert("Warning", 'Cancelled!');
                    }).set('labels', {ok: 'I Consent and Accept!', cancel: 'Cancel'});
            }
        }

    </script>
<?php }  else if ($load_view == 'new') { ?>
    <?php //$this->load->view('form_i9/index_ems'); ?>
    <?php redirect('forms/i9/my'); ?>
<?php } ?>
