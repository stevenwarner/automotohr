<form action="<?php echo base_url("forms/i9/authorized/section/") . $pre_form['sid']; ?>" id="i9-form" method="post" autocomplete="nope">
    <div class="row">
        <div class="col-xs-12">
            <div class="section-2">
                <div class="hr-box">
                    <div class="hr-box-header">
                        <strong>Section 2. </strong> Employer Review and Verification: Employers or their authorized representative must complete and sign Section 2 within three
                        business days after the employee's first day of employment, and must physically examine, or examine consistent with an alternative procedure
                        authorized by the Secretary of DHS, documentation from List A OR a combination of documentation from List B and List C. Enter any additional
                        documentation in the Additional Information box; see Instructions.
                    </div>
                    <div class="hr-innerpadding">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group autoheight">
                                    <label>Employee Info from Section 1 <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_4_employee_info_from"></i></label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Last Name (Family Name) <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_4_last_name"></i></label>
                                    <input type="text" value="<?php echo sizeof($pre_form) > 0 ? $pre_form['section1_last_name'] : '' ?>" name="section2_last_name" class="form-control" <?php if (!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {
                                                                                                                                                                                                echo 'readonly';
                                                                                                                                                                                            } ?> />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>First Name (Given Name) <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_4_first_name"></i></label>
                                    <input type="text" value="<?php echo sizeof($pre_form) > 0 ? $pre_form['section1_first_name'] : '' ?>" name="section2_first_name" class="form-control" <?php if (!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {
                                                                                                                                                                                                echo 'readonly';
                                                                                                                                                                                            } ?> />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>M.I. <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_4_mi"></i></label>
                                    <input type="text" name="section2_middle_initial" value="<?php echo sizeof($pre_form) > 0 ? $pre_form['section1_middle_initial'] : '' ?>" class="form-control" <?php if (!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {
                                                                                                                                                                                                        echo 'readonly';
                                                                                                                                                                                                    } ?> />
                                </div>
                            </div>
                            <?php
                            $citizen = '';
                            if (sizeof($pre_form) > 0) {
                                $citizen = $pre_form['section1_penalty_of_perjury'] == 'citizen' ? '1' : ($pre_form['section1_penalty_of_perjury'] == 'noncitizen' ? '2' : ($pre_form['section1_penalty_of_perjury'] == 'permanent-resident' ? '3' : ($pre_form['section1_penalty_of_perjury'] == 'alien-work' ? '4' : '')));
                            }
                            ?>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group" style="margin-bottom: 0 !important;">
                                    <label>Citizenship/Immigration Status <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_4_citizenship"></i></label>
                                    <input type="text" name="section2_citizenship" value="<?php echo $citizen ?>" class="form-control" <?php if (!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {
                                                                                                                                            echo 'readonly';
                                                                                                                                        } ?> />
                                </div>
                                <div>
                                    <strong>
                                        <p class="text-danger">1- A citizen of the United States<br />
                                            2- A noncitizen of the United States <br />
                                            3- A lawful permanent resident <br />
                                            4- An alien authorized to work </p>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hr-box">
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
                                    <label>Document Title <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_5_document_title"></i></label>
                                    <div class="lista_part1_doc">
                                        <label>
                                            <input type="radio" name="lista_part1_doc_select_input" value="select" <?= $pre_form['lista_part1_doc_select_input'] == 'select' ? 'checked' : '' ?>> Select from List
                                        </label> &nbsp;
                                        <label>
                                            <input type="radio" name="lista_part1_doc_select_input" value="input" <?= $pre_form['lista_part1_doc_select_input'] == 'input' ? 'checked' : '' ?>> Text
                                        </label>
                                        <div id="lista_part1_doc_text" style="display: none">
                                            <input type="text" id="lista_part1_doc_text_val" placeholder="Write Here" name="section2_lista_part1_document_title_text_val" class="form-control">
                                        </div>
                                    </div>

                                    <div class="select lista_part1_doc" id="lista_part1_doc_select">
                                        <select class="form-control" name="section2_lista_part1_document_title" id="section2_lista_part1_document_title">
                                            <option value="n_a">N/A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Issuing Authority <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_5_issuing_authority"></i></label>
                                    <div class="lista_part1_issuing">
                                        <label>
                                            <input type="radio" name="lista_part1_issuing_select_input" value="select" <?= $pre_form['lista_part1_issuing_select_input'] == 'select' ? 'checked' : '' ?>> Select from List
                                        </label> &nbsp;
                                        <label>
                                            <input type="radio" name="lista_part1_issuing_select_input" value="input" <?= $pre_form['lista_part1_issuing_select_input'] == 'input' ? 'checked' : '' ?>> Text
                                        </label>
                                        <div id="lista_part1_issuing_text" style="display: none">
                                            <input type="text" id="lista_part1_issuing_text_val" placeholder="Write Here" name="section2_lista_part1_issuing_authority_text_val" class="form-control">
                                        </div>
                                    </div>
                                    <div class="select lista_part1_issuing" id="lista_part1_issuing_select">
                                        <select class="form-control" name="section2_lista_part1_issuing_authority" id="section2_lista_part1_issuing_authority">
                                            <option value="n_a">N/A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Document Number <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_5_document_number"></i></label>
                                    <input type="text" name="section2_lista_part1_document_number" id="section2_lista_part1_document_number" value="<?= isset($pre_form['section2_lista_part1_document_number']) && !empty($pre_form['section2_lista_part1_document_number']) ? $pre_form['section2_lista_part1_document_number'] : ""; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Expiration Date <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_5_expiration_date"></i></label>
                                    <input type="text" readonly name="section2_lista_part1_expiration_date" id="section2_lista_part1_expiration_date" value="<?= isset($pre_form['section2_lista_part1_expiration_date']) && !empty($pre_form['section2_lista_part1_expiration_date']) && $pre_form['section2_lista_part1_expiration_date'] != null ? date('m-d-Y', strtotime($pre_form['section2_lista_part1_expiration_date'])) : ""; ?>" class="form-control date_picker2">
                                </div>
                            </div>
                        </div>
                        <div class="row bg-gray list-a-fields">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Document Title <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_51_document_title"></i></label>
                                    <!--                                                    <input type="text"  class="form-control">-->
                                    <div class="lista_part2_doc">
                                        <label>
                                            <input type="radio" name="lista_part2_doc_select_input" value="select" <?= $pre_form['lista_part2_doc_select_input'] == 'select' ? 'checked' : '' ?>> Select from List
                                        </label> &nbsp;
                                        <label>
                                            <input type="radio" name="lista_part2_doc_select_input" value="input" <?= $pre_form['lista_part2_doc_select_input'] == 'input' ? 'checked' : '' ?>> Text
                                        </label>
                                        <div id="lista_part2_doc_text" style="display: none">
                                            <input type="text" id="lista_part2_doc_text_val" placeholder="Write Here" name="section2_lista_part2_document_title_text_val" class="form-control">
                                        </div>
                                    </div>
                                    <div class="select lista_part2_doc" id="lista_part2_doc_select">
                                        <select class="form-control" name="section2_lista_part2_document_title" id="section2_lista_part2_document_title">
                                            <option value="n_a">N/A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Issuing Authority <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_51_issuing_authority"></i></label>
                                    <!--                                                    <input type="text"  class="form-control">-->
                                    <div class="lista_part2_issuing">
                                        <label>
                                            <input type="radio" name="lista_part2_issuing_select_input" value="select" <?= $pre_form['lista_part2_issuing_select_input'] == 'select' ? 'checked' : '' ?>> Select from List
                                        </label> &nbsp;
                                        <label>
                                            <input type="radio" name="lista_part2_issuing_select_input" value="input" <?= $pre_form['lista_part2_issuing_select_input'] == 'input' ? 'checked' : '' ?>> Text
                                        </label>
                                        <div id="lista_part2_issuing_text" style="display: none">
                                            <input type="text" id="lista_part2_issuing_text_val" placeholder="Write Here" name="section2_lista_part2_issuing_authority_text_val" class="form-control">
                                        </div>
                                    </div>
                                    <div class="select lista_part2_issuing" id="lista_part2_issuing_select">
                                        <select class="form-control" name="section2_lista_part2_issuing_authority" id="section2_lista_part2_issuing_authority">
                                            <option value="n_a">N/A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Document Number <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_51_document_number"></i></label>
                                    <input type="text" name="section2_lista_part2_document_number" value="<?= isset($pre_form['section2_lista_part2_document_number']) && !empty($pre_form['section2_lista_part2_document_number']) ? $pre_form['section2_lista_part2_document_number'] : ""; ?>" id="section2_lista_part2_document_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Expiration Date <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_51_expiration_date"></i></label>
                                    <input type="text" readonly name="section2_lista_part2_expiration_date" id="section2_lista_part2_expiration_date" value="<?= isset($pre_form['section2_lista_part2_expiration_date']) && !empty($pre_form['section2_lista_part2_expiration_date']) && $pre_form['section2_lista_part2_expiration_date'] != null ? date('m-d-Y', strtotime($pre_form['section2_lista_part2_expiration_date'])) : ""; ?>" class="form-control date_picker2">
                                </div>
                            </div>
                        </div>
                        <div class="row list-a-fields">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Document Title <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_52_document_title"></i></label>
                                    <!--                                                    <input type="text"  class="form-control">-->
                                    <div class="lista_part3_doc">
                                        <label>
                                            <input type="radio" name="lista_part3_doc_select_input" value="select" <?= $pre_form['lista_part3_doc_select_input'] == 'select' ? 'checked' : '' ?>> Select from List
                                        </label> &nbsp;
                                        <label>
                                            <input type="radio" name="lista_part3_doc_select_input" value="input" <?= $pre_form['lista_part3_doc_select_input'] == 'input' ? 'checked' : '' ?>> Text
                                        </label>
                                        <div id="lista_part3_doc_text" style="display: none">
                                            <input type="text" id="lista_part3_doc_text_val" placeholder="Write Here" name="section2_lista_part3_document_title_text_val" class="form-control">
                                        </div>
                                    </div>

                                    <div class="select lista_part3_doc" id="lista_part3_doc_select">
                                        <select class="form-control" name="section2_lista_part3_document_title" id="section2_lista_part3_document_title">
                                            <option value="n_a">N/A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Issuing Authority <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_52_issuing_authority"></i></label>
                                    <!--                                                    <input type="text" class="form-control">-->
                                    <div class="lista_part3_issuing">
                                        <label>
                                            <input type="radio" name="lista_part3_issuing_select_input" value="select" <?= $pre_form['lista_part3_issuing_select_input'] == 'select' ? 'checked' : '' ?>> Select from List
                                        </label> &nbsp;
                                        <label>
                                            <input type="radio" name="lista_part3_issuing_select_input" value="input" <?= $pre_form['lista_part3_issuing_select_input'] == 'input' ? 'checked' : '' ?>> Text
                                        </label>
                                        <div id="lista_part3_issuing_text" style="display: none">
                                            <input type="text" id="lista_part3_issuing_text_val" placeholder="Write Here" name="section2_lista_part3_issuing_authority_text_val" class="form-control">
                                        </div>
                                    </div>
                                    <div class="select lista_part3_issuing" id="lista_part3_issuing_select">
                                        <select class="form-control" name="section2_lista_part3_issuing_authority" id="section2_lista_part3_issuing_authority">
                                            <option value="n_a">N/A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Document Number <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_52_document_number"></i></label>
                                    <input type="text" name="section2_lista_part3_document_number" id="section2_lista_part3_document_number" value="<?= isset($pre_form['section2_lista_part3_document_number']) && !empty($pre_form['section2_lista_part3_document_number']) ? $pre_form['section2_lista_part3_document_number'] : ""; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Expiration Date <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_52_expiration_date"></i></label>
                                    <input type="text" readonly name="section2_lista_part3_expiration_date" id="section2_lista_part3_expiration_date" value="<?= isset($pre_form['section2_lista_part3_expiration_date']) && !empty($pre_form['section2_lista_part3_expiration_date']) && $pre_form['section2_lista_part3_expiration_date'] != null ? date('m-d-Y', strtotime($pre_form['section2_lista_part3_expiration_date'])) : ""; ?>" class="form-control date_picker2">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Additional Information <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_52_additional_information"></i></label>
                                    <textarea class="form-control textarea" name="section2_additional_information" id="section2_additional_information"><?= isset($pre_form['section2_additional_information']) && !empty($pre_form['section2_additional_information']) && $pre_form['section2_additional_information'] != null ? $pre_form['section2_additional_information'] : ""; ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <label for="page_status">
                                    <input type="checkbox" name="section2_authorized_alternative_procedure" value="yes" <?php echo ($pre_form['section2_alternative_procedure'] == 1 ? 'checked="checked"' : ''); ?> />
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
                                    <label>Document Title <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_53_document_title"></i></label>

                                    <div class="select">
                                        <select class="form-control" name="section2_listb_document_title" id="section2_listb_document_title"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Issuing Authority <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_53_issuing_authority"></i></label>
                                    <!--                                                    <input type="text"class="form-control">-->
                                    <div class="list_b_auth">
                                        <label>
                                            <input type="radio" name="listb-auth-select-input" value="select" <?= $pre_form['listb_auth_select_input'] == 'select' ? 'checked' : '' ?>> Select from List
                                        </label> &nbsp;
                                        <label>
                                            <input type="radio" name="listb-auth-select-input" value="input" <?= $pre_form['listb_auth_select_input'] == 'input' ? 'checked' : '' ?>> Text
                                        </label>
                                        <div id="list_b_auth_text" style="display: none">
                                            <input type="text" id="list_b_auth_text_val" placeholder="Write Here" name="section2_listb_issuing_authority_text_val" class="form-control">
                                        </div>
                                    </div>
                                    <div class="select list_b_auth" id="list_b_auth_select">
                                        <select class="form-control" name="section2_listb_issuing_authority" id="section2_listb_issuing_authority">
                                            <option value="n_a">N/A</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Document Number <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_53_document_number"></i></label>
                                    <input type="text" name="section2_listb_document_number" value="<?= isset($pre_form['section2_listb_document_number']) && !empty($pre_form['section2_listb_document_number']) ? $pre_form['section2_listb_document_number'] : ""; ?>" id="section2_listb_document_number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Expiration Date <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_53_expiration_date"></i></label>
                                    <input type="text" readonly name="section2_listb_expiration_date" value="<?= isset($pre_form['section2_listb_expiration_date']) && !empty($pre_form['section2_listb_expiration_date']) && $pre_form['section2_listb_expiration_date'] != null ? date('m-d-Y', strtotime($pre_form['section2_listb_expiration_date'])) : ""; ?>" id="section2_listb_expiration_date" class="form-control date_picker2" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 list-b-fields">
                                <div class="col-header text-center">
                                    <strong>List C <br> Employment Authorization</strong>
                                </div>
                                <div class="form-group">
                                    <label>Document Title <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_54_document_title"></i></label>

                                    <div class="select">
                                        <select class="form-control" name="section2_listc_document_title" id="section2_listc_document_title"></select>
                                    </div>
                                </div>
                                <div class="form-group" id="listc_extra_field" style="display: none">
                                    <label>&nbsp;</label>
                                    <input type="text" name="section2_listc_dhs_extra_field" id="section2_listc_extra_field" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Issuing Authority <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_54_issuing_authority"></i></label>
                                    <div class="list_c_auth">
                                        <label>
                                            <input type="radio" name="listc-auth-select-input" value="select" <?= $pre_form['listc_auth_select_input'] == 'select' ? 'checked' : '' ?>> Select from List
                                        </label> &nbsp;
                                        <label>
                                            <input type="radio" name="listc-auth-select-input" value="input" <?= $pre_form['listc_auth_select_input'] == 'input' ? 'checked' : '' ?>> Text
                                        </label>
                                        <div id="list_c_auth_text" style="display: none">
                                            <input type="text" id="list_c_auth_text_val" placeholder="Write Here" name="section2_listc_issuing_authority_text_val" class="form-control">
                                        </div>
                                    </div>

                                    <div class="select list_c_auth" id="list_c_auth_select">
                                        <select class="form-control" name="section2_listc_issuing_authority" id="section2_listc_issuing_authority">
                                            <option value="n_a" selected>N/A</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Document Number <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_54_document_number"></i></label>
                                    <input type="text" name="section2_listc_document_number" value="<?= isset($pre_form['section2_listc_document_number']) && !empty($pre_form['section2_listc_document_number']) ? $pre_form['section2_listc_document_number'] : ""; ?>" id="section2_listc_document_number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Expiration Date <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_54_expiration_date"></i></label>
                                    <input type="text" readonly name="section2_listc_expiration_date" id="section2_listc_expiration_date" value="<?= isset($pre_form['section2_listc_expiration_date']) && !empty($pre_form['section2_listc_expiration_date']) && $pre_form['section2_listc_expiration_date'] != null ? date('m-d-Y', strtotime($pre_form['section2_listc_expiration_date'])) : ""; ?>" class="form-control date_picker2" autocomplete="off">
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
                            <p><strong>The employee's first day of employment (mm/dd/yyyy):<i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_employee_1st_day_of_employment "></i> (See
                                    instructions for exemptions) <span class="staric">*</span> </strong></p>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" readonly name="section2_firstday_of_emp_date" id="section2_firstday_of_emp_date" class="form-control date_picker2" autocomplete="off" value="<?= isset($pre_form['section2_firstday_of_emp_date']) && !empty($pre_form['section2_firstday_of_emp_date']) ? date('m-d-Y', strtotime($pre_form['section2_firstday_of_emp_date'])) : $employerPrefill['first_day_of_employment']; ?>" />
                        </div>
                    </div>
                </div>
                <div class="hr-box">
                    <div class="hr-innerpadding">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Signature of Employer or Authorized Representative <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_signature"></i></label>
                                    <?php if (isset($pre_form['section3_emp_sign']) && !empty($pre_form['section3_emp_sign'])) { ?>
                                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?php echo $pre_form['section3_emp_sign']; ?>" class="esignaturesize" />
                                    <?php } else if (!empty($employeePrefillSignature)) { ?>
                                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?php echo $employeePrefillSignature; ?>" class="esignaturesize" />

                                    <?php } else { ?>
                                        <!-- the below loaded view add e-signature -->
                                        <a class="btn btn-success btn-sm sign_of_emp_or_aut_rep" href="javascript:;">Create E-Signature</a>
                                        <div class="img-full">
                                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="" id="sign_of_emp_or_aut_rep_img" />
                                        </div>
                                        <input type="hidden" name="section2_sig_emp_auth_rep" id="section2_emp_sign">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Today's Date (mm/dd/yyyy) <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_today_date"></i></label>
                                    <input readonly name="section2_today_date" id="section2_today_date" class="form-control date_picker" type="text" autocomplete="off" value="<?= date('m-d-Y'); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Title of Employer or Authorized Representative <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_title"></i></label>
                                    <input name="section2_title_of_emp" id="section2_title_of_emp" class="form-control" type="text" value="<?= isset($pre_form['section2_title_of_emp']) && !empty($pre_form['section2_title_of_emp']) ? $pre_form['section2_title_of_emp'] : ""; ?>" autocomplete="nope">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Last Name of Employer or Authorized Representative <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_last_name"></i></label>
                                    <input name="section2_last_name_of_emp" id="section2_last_name_of_emp" class="form-control" type="text" value="<?= $last_name; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>First Name of Employer or Authorized Representative <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_first_name"></i>
                                    </label>
                                    <input name="section2_first_name_of_emp" id="section2_first_name_of_emp" class="form-control" type="text" value="<?= $first_name; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Employer's Business or Organization Name <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_organization_name"></i></label>
                                    <input name="section2_emp_business_name" id="section2_emp_business_name" class="form-control" type="text" value="<?= isset($pre_form['section2_emp_business_name']) && !empty($pre_form['section2_emp_business_name']) ? $pre_form['section2_emp_business_name'] : ""; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Employer's Business or Organization Address <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_organization_address"></i></label>
                                    <input name="section2_emp_business_address" disableautocomplete id="section2_emp_business_address" class="form-control" type="text" autocomplete="nope" value="<?= isset($pre_form['section2_emp_business_address']) && !empty($pre_form['section2_emp_business_address']) ? $pre_form['section2_emp_business_address'] : ""; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>City or Town <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_city_or_town"></i></label>
                                    <input name="section2_city_town" id="section2_city_town" class="form-control" value="<?= isset($pre_form['section2_city_town']) && !empty($pre_form['section2_city_town']) ? $pre_form['section2_city_town'] : ""; ?>" type="text" autocomplete="nope">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>State <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_state"></i></label>
                                    <?php $states = db_get_active_states(227); ?>
                                    <div class="select">
                                        <select class="form-control" name="section2_state" id="section2_state">
                                            <?php foreach ($states as $state) {
                                                $selected =  isset($pre_form['section2_state']) && !empty($pre_form['section2_state']) && $pre_form['section2_state'] == $state['state_code'] ? 'selected' : '';
                                                echo '<option value="' . $state['state_code'] . '" ' . $selected . '>' . $state['state_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>ZIP Code <span class="staric">*</span> <i aria-hidden="true" class="fa fa-question-circle-o modalShow" src="section_6_zip_code"></i></label>
                                    <input name="section2_zip_code" id="section2_zip_code" value="<?= isset($pre_form['section2_zip_code']) && !empty($pre_form['section2_zip_code']) ? $pre_form['section2_zip_code'] : ""; ?>" class="form-control" type="text" autocomplete="nope">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                        <strong>Instructions:</strong> This supplement replaces Section 3 on the previous version of Form I-9. Only use this page if your employee requires of Form I-9. The preparer and/or translator must enter the employee's name in the spaces provided above. Each preparer or translator
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

            </div>
            <input type="hidden" id="current-url" name="current-url" value="<?php echo current_url(); ?>" />
            <!--                            Section 2 Ends    -->


            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="consent_and_notice_section">
                <input type="hidden" id="perform_action" name="perform_action" value="sign_document" />
                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />
                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $user_sid; ?>" />
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
                            <?php echo str_replace("{{company_name}}", $company_name, SIGNATURE_CONSENT_HEADING); ?>
                        </p>
                        <p>
                            <?php echo SIGNATURE_CONSENT_TITLE; ?>
                        </p>
                        <p>
                            <?php echo str_replace("{{company_name}}", $company_name, SIGNATURE_CONSENT_DESCRIPTION); ?>
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

        </div>
    </div>
</form>