<?php
for ($i = 1; $i <= 3; $i++) : ?>

    <div class="row <?= $i % 2 === 0 ? 'csBG5 p10' : '' ?>">
        <div class="col-lg-3 col-md-12 col-xs-12 col-sm-12">
            <div class="form-group autoheight">
                <strong>B. </strong><em>Date of Rehire (if applicable)</em>
            </div>
            <div class="form-group">
                <label>Date (mm/dd/yyyy) <i class="fa fa-question-circle-o modalShow" src="section_8_date"></i></label>
                <input type="text" class="form-control date_picker2" readonly
                        value="<?= isset($authorizedArray[$i]['section3_rehire_date']) && !empty($authorizedArray[$i]['section3_rehire_date']) ? date('m-d-Y',strtotime($authorizedArray[$i]['section3_rehire_date'])) : "";?>" name="section3_authorized_rehire_date_<?= $i; ?>" autocomplete="off">
            </div>
        </div>

        <div class="col-lg-9 col-md-12 col-xs-12 col-sm-12">
            <div class="row">
                <div class="col-lg-12 form-group autoheight">
                    <strong>A. </strong><em>New Name (if applicable) <i class="fa fa-question-circle-o modalShow" src="section_8_new_name"></i></em>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>Last Name <i class="fa fa-question-circle-o modalShow" src="section_8_last_name"></i></label>
                        <input type="text" class="form-control"
                                value="<?= isset($authorizedArray[$i]['section3_last_name']) && !empty($authorizedArray[$i]['section3_last_name']) ? $authorizedArray[$i]['section3_last_name'] : "";?>" name="section3_authorized_last_name_<?= $i; ?>" autocomplete="nope">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>First Name <i class="fa fa-question-circle-o modalShow" src="section_8_first_name"></i></label>
                        <input type="text" class="form-control"
                                value="<?= isset($authorizedArray[$i]['section3_first_name']) && !empty($authorizedArray[$i]['section3_first_name']) ? $authorizedArray[$i]['section3_first_name'] : "";?>" name="section3_authorized_first_name_<?= $i; ?>" autocomplete="nope">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>Middle Initial <i class="fa fa-question-circle-o modalShow" src="section_8_middle_initial"></i></label>
                        <input type="text" class="form-control"
                                value="<?= isset($authorizedArray[$i]['section3_middle_initial']) && !empty($authorizedArray[$i]['section3_middle_initial']) ? $authorizedArray[$i]['section3_middle_initial'] : "";?>" name="section3_authorized_middle_initial_<?= $i; ?>" autocomplete="nope">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="hr-box">
                <div class="hr-box-header">
                    <strong>Reverification: </strong> If the employee's requires reverification, your employee can choose to present any acceptable list A or List C 
                    documentation to show continued employment authorization. Enter the document information in the space below.
                </div>
                <div class="hr-innerpadding">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Document Title <i
                                        class="fa fa-question-circle-o modalShow" src="section_8_document_title"></i></label>
                                <?php $selected = isset($authorizedArray[$i]['section3_document_title']) && !empty($authorizedArray[$i]['section3_document_title']) ? $authorizedArray[$i]['section3_document_title'] : "";?>
                                <div class="select">
                                    <select class="form-control"
                                            name="section3_authorized_document_title_<?= $i; ?>">
                                        <option value="N/A">N/A</option>
                                        <option value="U.S. Passport" <?= $selected == 'U.S. Passport' ? 'selected' : ''?>>U.S.
                                            Passport
                                        </option>
                                        <option value="U.S. Passport Card" <?= $selected == 'U.S. Passport Card' ? 'selected' : ''?>>U.S.
                                            Passport Card
                                        </option>
                                        <option
                                            value="Perm. Resident Card (Form I-551)" <?= $selected == 'Perm. Resident Card (Form I-551)' ? 'selected' : ''?>>
                                            Perm. Resident Card (Form I-551)
                                        </option>
                                        <option
                                            value="Alien Reg. Receipt Card (Form I-551)" <?= $selected == 'Alien Reg. Receipt Card (Form I-551)' ? 'selected' : ''?>>
                                            Alien Reg. Receipt Card (Form I-551)
                                        </option>
                                        <option
                                            value="Foreign Passport with Temp. I-551 Stamp" <?= $selected == 'Foreign Passport with Temp. I-551 Stamp' ? 'selected' : ''?>>
                                            Foreign Passport with Temp. I-551
                                            Stamp
                                        </option>
                                        <option
                                            value="Foreign Passport with Temp. I-551 MRIV" <?= $selected == 'Foreign Passport with Temp. I-551 MRIV' ? 'selected' : ''?>>
                                            Foreign Passport with Temp. I-551
                                            MRIV
                                        </option>
                                        <option
                                            value="Employment Auth. Document (Form I-766)" <?= $selected == 'Employment Auth. Document (Form I-766)' ? 'selected' : ''?>>
                                            Employment Auth. Document (Form
                                            I-766)
                                        </option>
                                        <option
                                            value="Foreign Passport with Form I-94, endorsement" <?= $selected == 'Foreign Passport with Form I-94, endorsement' ? 'selected' : ''?>>
                                            Foreign Passport with Form I-94,
                                            endorsement
                                        </option>
                                        <option
                                            value="FSM Passport with Form I-94" <?= $selected == 'FSM Passport with Form I-94' ? 'selected' : ''?>>
                                            FSM Passport with Form I-94
                                        </option>
                                        <option
                                            value="RMI Passport with Form I-94" <?= $selected == 'RMI Passport with Form I-94' ? 'selected' : ''?>>
                                            RMI Passport with Form I-94
                                        </option>
                                        <option
                                            value="Receipt Form I-94/I-94A w/I-551 stamp, photo" <?= $selected == 'Receipt Form I-94/I-94A w/I-551 stamp, photo' ? 'selected' : ''?>>
                                            Receipt Form I-94/I-94A w/I-551
                                            stamp, photo
                                        </option>
                                        <option
                                            value="Receipt Form I-94/I-94A w/refugee stamp (or RE class of admission)" <?= $selected == 'Receipt Form I-94/I-94A w/refugee stamp (or RE class of admission)' ? 'selected' : ''?>>
                                            Receipt Form I-94/I-94A w/refugee
                                            stamp (or RE class of admission)
                                        </option>
                                        <option
                                            value="Social Security Card (Unrestricted)" <?= $selected == 'Social Security Card (Unrestricted)' ? 'selected' : ''?>>
                                            Social Security Card (Unrestricted)
                                        </option>
                                        <option value="Form FS-545" <?= $selected == 'Form FS-545' ? 'selected' : ''?>>Form
                                            FS-545
                                        </option>
                                        <option value="Form DS-1350" <?= $selected == 'Form DS-1350' ? 'selected' : ''?>>Form
                                            DS-1350
                                        </option>
                                        <option value="Form FS-240" <?= $selected == 'Form FS-240' ? 'selected' : ''?>>Form
                                            FS-240
                                        </option>
                                        <option value="U.S. Birth certificate" <?= $selected == 'U.S. Birth certificate' ? 'selected' : ''?>>
                                            U.S. Birth certificate
                                        </option>
                                        <option
                                            value="Native American tribal document" <?= $selected == 'Native American tribal document' ? 'selected' : ''?>>
                                            Native American tribal document
                                        </option>
                                        <option value="Form I-197" <?= $selected == 'Form I-197' ? 'selected' : ''?>>Form I-197
                                        </option>
                                        <option value="Form I-179" <?= $selected == 'Form I-179' ? 'selected' : ''?>>Form I-179
                                        </option>
                                        <option
                                            value="Employment auth. document (DHS)" <?= $selected == 'Employment auth. document (DHS)' ? 'selected' : ''?>>
                                            Employment auth. document (DHS)
                                        </option>
                                        <option
                                            value="Receipt Replacement Perm. Resident Card (Form I-551)" <?= $selected == 'Receipt Replacement Perm. Resident Card (Form I-551)' ? 'selected' : ''?>>
                                            Receipt Replacement Perm. Resident
                                            Card (Form I-551)
                                        </option>
                                        <option
                                            value="Receipt Replacement Employment Auth. Document (Form I-766)" <?= $selected == 'Receipt Replacement Employment Auth. Document (Form I-766)' ? 'selected' : ''?>>
                                            Receipt Replacement Employment Auth.
                                            Document (Form I-766)
                                        </option>
                                        <option
                                            value="Receipt Replacement Foreign Passport with Form I-94, endorsement" <?= $selected == 'Receipt Replacement Foreign Passport with Form I-94, endorsement' ? 'selected' : ''?>>
                                            Receipt Replacement Foreign Passport
                                            with Form I-94, endorsement
                                        </option>
                                        <option
                                            value="Receipt Replacement FSM passport/Form I-94" <?= $selected == 'Receipt Replacement FSM passport/Form I-94' ? 'selected' : ''?>>
                                            Receipt Replacement FSM
                                            passport/Form I-94
                                        </option>
                                        <option
                                            value="Receipt Replacement RMI passport/Form I-94" <?= $selected == 'Receipt Replacement RMI passport/Form I-94' ? 'selected' : ''?>>
                                            Receipt Replacement RMI
                                            passport/Form I-94
                                        </option>
                                        <option
                                            value="Receipt Replacement Social Security Card" <?= $selected == 'Receipt Replacement Social Security Card' ? 'selected' : ''?>>
                                            Receipt Replacement Social Security
                                            Card
                                        </option>
                                        <option
                                            value="Receipt Replacement Birth Certificate" <?= $selected == 'Receipt Replacement Birth Certificate' ? 'selected' : ''?>>
                                            Receipt Replacement Birth
                                            Certificate
                                        </option>
                                        <option
                                            value="Receipt Replacement Native American Tribal Document" <?= $selected == 'Receipt Replacement Native American Tribal Document' ? 'selected' : ''?>>
                                            Receipt Replacement Native American
                                            Tribal Document
                                        </option>
                                        <option
                                            value="Receipt Replacement Employment Auth. Doc. (DHS)" <?= $selected == 'Receipt Replacement Employment Auth. Doc. (DHS)' ? 'selected' : ''?>>
                                            Receipt Replacement Employment Auth.
                                            Doc. (DHS)
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Document Number <i
                                        class="fa fa-question-circle-o modalShow" src="section_8_document_number"></i></label>
                                <input type="text" class="form-control"
                                        value="<?= isset($authorizedArray[$i]['section3_document_number']) && !empty($authorizedArray[$i]['section3_document_number']) ? $authorizedArray[$i]['section3_document_number'] : "";?>" name="section3_authorized_document_number_<?= $i; ?>">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>Expiration Date (if any) (mm/dd/yyyy) <i
                                        class="fa fa-question-circle-o modalShow" src="section_8_expiration"></i></label>
                                <input type="text" value="<?= isset($authorizedArray[$i]['section3_expiration_date']) && !empty($authorizedArray[$i]['section3_expiration_date']) ? date('m-d-Y',strtotime($authorizedArray[$i]['section3_expiration_date'])) : "";?>"
                                        class="form-control date_picker2" readonly
                                        name="section3_authorized_expiration_date_<?= $i; ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p><strong>I attest, under penalty of perjury, that to the best of
                    my knowledge, this employee is authorized to work in the
                    United States, and if
                    the employee presented document(s), the document(s) I have
                    examined appear to be genuine and to relate to the
                    individual who presented it.</strong></p>
        </div>

        <div class="col-lg-12">
            <div class="hr-box">
                <div class="hr-innerpadding">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>Name of Authorized Representative <span class="staric">*</span> <i
                                        class="fa fa-question-circle-o modalShow" src="section_8_authorized"></i></label>
                                <input type="text" class="form-control"
                                        name="section3_authorized_name_of_emp_<?= $i; ?>" value="<?= $first_name.' '.$last_name?>">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>Signature of Authorized Representative <span class="staric">*</span> <i class="fa fa-question-circle-o modalShow" src="section_8_signature"></i></label>

                                <!-- the below loaded view add e-signature -->
                                <a class="btn btn-success btn-sm jsSetAuthorizedSignature jsSetAuthorizedSignature_<?= $i; ?>" data-key="<?= $i; ?>">
                                    Create E-Signature
                                </a>
                                <div class=" img-full">
                                    <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>" alt="" class="authorized_signature_img_<?= $i; ?>" />
                                </div>
                                <input type="hidden" id="section3_authorized_signature_<?= $i; ?>" name="section3_authorized_signature_<?= $i; ?>" />

                               
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>Today's Date (mm/dd/yyyy) <span class="staric">*</span> <i
                                        class="fa fa-question-circle-o modalShow" src="section_8_today_date"></i></label>
                                <input type="text" readonly value="<?= isset($authorizedArray[$i]['section3_signature_date']) && !empty($authorizedArray[$i]['section3_signature_date']) ? date('m-d-Y',strtotime($authorizedArray[$i]['section3_signature_date'])) : date('m-d-Y');?>"
                                        class="form-control date_picker" readonly
                                        name="section3_authorized_today_date_<?= $i; ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9 col-md-12 col-xs-12 col-sm-12">
                            <label>Additional Information (Initial and date each notation.) </label>
                            <textarea name="section3_authorized_additional_information_<?= $i; ?>" class="invoice-fields-textarea" rows="8"><?php echo $authorizedArray[$i]['section3_additional_information'] ?></textarea>
                        </div>
                        <div class="col-lg-3 col-md-12 col-xs-12 col-sm-12">
                            <label for="page_status">
                                <input type="checkbox" name="section3_authorized_alternative_procedure_<?= $i; ?>" value="yes" <?php echo ($authorizedArray[$i]['section3_alternative_procedure'] == 1 ? 'checked="checked"' : ''); ?>  />
                                Check here if you used an alternative procedure authorized by DHS to examine documents.
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
<?php endfor; ?>