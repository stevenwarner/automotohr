<?php if (!isset($doUpload)) { ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>I-9 Form</title>
<?php } ?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/i9-style.css')?>">
<?php if (!isset($doUpload)) { ?>
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
<?php } else{ ?>
    <div class="A4">
    <?php } ?>

        <div class="" id="i9_pdf">
            <section class="sheet padding-10mm" id="i9_pdf_section_1">
                <article class="sheet-header">
                    <div class="header-logo"><img src="<?php echo base_url('assets/images/i9-header.png')?>"></div>
                    <div class="center-col">
                        <h2>Employment Eligibility Verification<br>Department of Homeland Security</h2>
                        <p>U.S. Citizenship and Immigration Services</p>
                    </div>
                    <div class="right-header">
                        <h3>USCIS <br> Form I-9</h3>
                        <p>OMB No. 1615-0047<br>Expires <?php echo I9_EXPIRES; ?></p>
                    </div>
                </article>
                <p><strong>START HERE:</strong> Read instructions carefully before completing this form. The instructions must be available, either in paper or electronically, during completion of this form. Employers are liable for errors in the completion of this form.</p>
                <p><strong>ANTI-DISCRIMINATION NOTICE:</strong> It is illegal to discriminate against work-authorized individuals. Employers CANNOT specify which document(s) an employee may present to establish employment authorization and identity. The refusal to hire or continue to employ an individual because the documentation presented has a future expiration date may also constitute illegal discrimination.</p>
                <table class="i9-table">
                    <thead>
                    <tr class="bg-gray">
                        <th colspan="5">
                            <strong>Section 1. Employee Information and Attestation </strong><small>(Employees must complete and sign Section 1 of Form I-9 no later than the first day of employment, but not before accepting a job offer.)</small>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <label>Last Name (Family Name)</label>
                            <span class="value-box" id="last_name"><?php echo $pre_form['section1_last_name']?></span>
                        </td>
                        <td>
                            <label>First Name (Given Name)</label>
                            <span class="value-box" id="first_name"><?php echo $pre_form['section1_first_name']?></span>
                        </td>
                        <td>
                            <label>Middle Initial</label>
                            <span class="value-box" id="middle_initial"><?php echo $pre_form['section1_middle_initial']?></span>
                        </td>
                        <td colspan="2">
                            <label>Other Last Names Used (if any)</label>
                            <span class="value-box" id="other_last_name"><?php echo $pre_form['section1_other_last_names']?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Address (Street Number and Name)</label>
                            <span class="value-box" id="street_number"><?php echo $pre_form['section1_address']?></span>
                        </td>
                        <td>
                            <label>Apt. Number</label>
                            <span class="value-box" id="apt_number"><?php echo $pre_form['section1_apt_number']?></span>
                        </td>
                        <td>
                            <label>City or Town</label>
                            <span class="value-box" id="city_town"><?php echo $pre_form['section1_city_town']?></span>
                        </td>
                        <td>
                            <label>State</label>
                            <span class="value-box" id="state"><?php echo $pre_form['section1_state']?></span>
                        </td>
                        <td>
                            <label>ZIP Code</label>
                            <span class="value-box" id="zip_code"><?php echo $pre_form['section1_zip_code']?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Date of Birth (mm/dd/yyyy)</label>
                            <span class="value-box" id="date_of_birth"><?php echo date('m/d/Y',strtotime($pre_form['section1_date_of_birth'])); ?></span>
                        </td>
                        <td>
                            <label>U.S. Social Security Number</label>
                            <span class="value-box" id="social_security_number"><?php echo $pre_form['section1_social_security_number']?></span>
                        </td>
                        <td>
                            <label>Employee's E-mail Address</label>
                            <span class="value-box" id="employee_email"><?php echo $pre_form['section1_emp_email_address']?></span>
                        </td>
                        <td colspan="2">
                            <label>Employee's Telephone Number</label>
                            <span class="value-box" id="employee_telephone"><?php echo $pre_form['section1_emp_telephone_number']?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="mb-2"><strong>I am aware that federal law provides for imprisonment and/or fines for false statements or use of false documents in connection with the completion of this form.</strong></div>
                <div class="mb-2"><strong>I attest, under penalty of perjury, that I am (check one of the following boxes):</strong></div>
                <?php
                    $section1_alien_registration_number = @unserialize($pre_form['section1_alien_registration_number']);
                ?>
                <table class="i9-table">
                    <tbody>
                    <tr>
                        <td colspan="2">
                            <input type="checkbox" name="" <?php echo $pre_form['section1_penalty_of_perjury'] == 'citizen' ? 'checked' : '' ?> disabled> 1. A citizen of the United States
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="checkbox" name="" <?php echo $pre_form['section1_penalty_of_perjury'] == 'noncitizen' ? 'checked' : '' ?> disabled> 2. A noncitizen national of the United States (See instructions)
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="checkbox" name="" <?php echo $pre_form['section1_penalty_of_perjury'] == 'permanent-resident' ? 'checked' : '' ?> disabled> 3. A lawful permanent resident (Alien Registration Number/USCIS Number):
                            <div class="inline-value-box">
                                <span class="value-box" id=""><?php echo $pre_form['section1_penalty_of_perjury'] == 'permanent-resident' && isset($section1_alien_registration_number['section1_alien_registration_number_one']) ? $section1_alien_registration_number['section1_alien_registration_number_one'] : '' ?></span>
                            </div>
                            <div class="inline-value-box" >
                                <span class="value-box" id=""><?php echo $pre_form['section1_penalty_of_perjury'] == 'permanent-resident' && isset($section1_alien_registration_number['section1_alien_registration_number_two']) ? $section1_alien_registration_number['section1_alien_registration_number_two'] : '' ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70%;">
                            <input type="checkbox" name="" <?php echo $pre_form['section1_penalty_of_perjury'] == 'alien-work' ? 'checked' : '' ?> disabled> 4. An alien authorized to work until (expiration date, if applicable, mm/dd/yyyy): Some aliens may write "N/A" in the expiration date field. (See instructions)
                            <div class="inline-value-box">
                                <span class="value-box" id=""><?php echo $pre_form['section1_penalty_of_perjury'] == 'alien-work' && isset($section1_alien_registration_number['alien_authorized_expiration_date']) ?  date('m/d/Y',strtotime($section1_alien_registration_number['alien_authorized_expiration_date'])) : '' ?></span>
                            </div>
                            <p class="text-center italic">Aliens authorized to work must provide only one of the following document numbers to complete Form I-9: An Alien Registration Number/USCIS Number OR Form I-94 Admission Number OR Foreign Passport Number.</p>
                            <div class="full-width">
                                1. Alien Registration Number/USCIS Number:
                                <div class="inline-value-box">
                                    <span class="value-box" id=""><?php echo $pre_form['section1_penalty_of_perjury'] == 'alien-work' && isset($section1_alien_registration_number['section1_alien_registration_number_one']) ? $section1_alien_registration_number['section1_alien_registration_number_one'] : '' ?></span>
                                </div>
                                <div class="inline-value-box">
                                    <span class="value-box" id=""><?php echo $pre_form['section1_penalty_of_perjury'] == 'alien-work' && isset($section1_alien_registration_number['section1_alien_registration_number_two']) ? $section1_alien_registration_number['section1_alien_registration_number_two'] : '' ?></span>
                                </div>
                            </div>
                            <div class="full-width text-center">
                                <strong>OR</strong>
                            </div>
                            <div class="full-width">
                                2. Form I-94 Admission Number
                                <div class="inline-value-box">
                                    <span class="value-box" id=""><?php echo $pre_form['section1_penalty_of_perjury'] == 'alien-work' && isset($section1_alien_registration_number['form_admission_number']) ? $section1_alien_registration_number['form_admission_number'] : '' ?></span>
                                </div>
                            </div>
                            <div class="full-width text-center">
                                <strong>OR</strong>
                            </div>
                            <div class="full-width">
                                3. Foreign Passport Number:
                                <div class="inline-value-box">
                                    <span class="value-box" id=""><?php echo $pre_form['section1_penalty_of_perjury'] == 'alien-work' && isset($section1_alien_registration_number['foreign_passport_number']) ? $section1_alien_registration_number['foreign_passport_number'] : '' ?></span>
                                </div>
                            </div>
                            <div class="full-width">
                                Country of Issuance:
                                <div class="inline-value-box">
                                    <span class="value-box" id=""><?php echo $pre_form['section1_penalty_of_perjury'] == 'alien-work' && isset($section1_alien_registration_number['country_of_issuance']) ? $section1_alien_registration_number['country_of_issuance'] : '' ?></span>
                                </div>
                            </div>
                        </td>
                        <td style="width: 30%;">
                            <div class="qr-code-box">
                                QR Code - Section 1<br>Do Not Write In This Space
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="i9-table">
                    <tr>
                        <td style="width: 60%;">
                            <label>Signature of Employee</label>
                            <span class="value-box" id="">
                                <div>
                                    <?php if(!empty($pre_form)) { ?>
                                        <div >
                                            <img  src="<?php echo isset($pre_form['section1_emp_signature']) && !empty($pre_form['section1_emp_signature']) ? $pre_form['section1_emp_signature'] : ''; ?>"  />
                                        </div>
                                    <?php } ?>
                                </div>

                            </span>
                        </td>
                        <td style="width: 40%;">
                            <label>Today's Date (mm/dd/yyyy)</label>
                            <span class="value-box" id=""><?php echo isset($pre_form['section1_today_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section1_today_date'])->format('m/d/Y') : ''; ?></span>
                        </td>
                    </tr>
                </table>
                <?php
                $section1_preparer_or_translator = @unserialize($pre_form['section1_preparer_or_translator']);
                ?>
                <div class="bg-gray gray-box">
                    <strong>Preparer and/or Translator Certification (check one):</strong>
                    <div class="inline-element-wrp full-width">
                        <div class="element-box">
                            <input disabled type="checkbox" id="no_preparer_tranlator" name="" <?php echo $section1_preparer_or_translator['section1_preparer_or_translator'] == 'not-used' ? 'checked' : '' ?>>
                            <label for="no_preparer_tranlator">did not use a preparer or translator</label>
                        </div>
                        <div class="element-box">
                            <input disabled type="checkbox" id="preparer_tranlator" name="" <?php echo $section1_preparer_or_translator['section1_preparer_or_translator'] == 'used' ? 'checked' : '' ?>>
                            <label for="preparer_tranlator">A preparer(s) and/or translator(s) assisted the employee in completing Section 1.</label>
                        </div>
                        <!-- <div class="element-box">
                            How many?
                            <div class="inline-value-box">
                                <span class="value-box" id=""><?php echo $section1_preparer_or_translator['section1_preparer_or_translator'] == 'used' ? $section1_preparer_or_translator['number-of-preparer'] : '' ?></span>
                            </div>
                        </div> -->
                    </div>
                    <strong>(Fields below must be completed and signed when preparers and/or translators assist an employee in completing Section 1.)</strong>
                </div>
                <strong>I attest, under penalty of perjury, that I have assisted in the completion of Section 1 of this form and that to the best of my knowledge the information is true and correct.</strong>
                <table class="i9-table">
                    <tbody>
                    <tr>
                        <td style="width: 40%">
                            <label>Signature of Preparer or Translator</label>
                            <?php if(!empty($pre_form)) { ?>
                                <div >
                                    <img  src="<?php echo isset($pre_form['section1_preparer_signature']) && !empty($pre_form['section1_preparer_signature']) ? $pre_form['section1_preparer_signature'] : ''; ?>"  />
                                </div>
                            <?php } ?>
                        </td>
                        <td style="width: 30%" colspan="3">
                            <label>Today's Date (mm/dd/yyyy)</label>
                            <span class="value-box" id=""><?php echo isset($pre_form['section1_preparer_today_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section1_preparer_today_date'])->format('m/d/Y') : ''; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <label>Last Name (Family Name)</label>
                            <span class="value-box" id=""><?php echo $pre_form['section1_preparer_last_name'] ?></span>
                        </td>
                        <td style="width: 50%" colspan="3">
                            <label>First Name (Given Name)</label>
                            <span class="value-box" id=""><?php echo $pre_form['section1_preparer_first_name'] ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">
                            <label>Address (Street Number and Name)</label>
                            <span class="value-box" id=""><?php echo $pre_form['section1_preparer_address'] ?></span>
                        </td>
                        <td style="width: 20%">
                            <label>City or Town</label>
                            <span class="value-box" id=""><?php echo $pre_form['section1_preparer_city_town'] ?></span>
                        </td>
                        <td style="width: 10%">
                            <label>State</label>
                            <span class="value-box" id=""><?php echo $pre_form['section1_preparer_state'] ?></span>
                        </td>
                        <td style="width: 20%">
                            <label>ZIP Code</label>
                            <span class="value-box" id=""><?php echo $pre_form['section1_preparer_zip_code'] ?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="sheet padding-10mm">
                <article class="sheet-header">
                    <div class="header-logo"><img src="<?php echo base_url('assets/images/i9-header.png')?>"></div>
                    <div class="center-col">
                        <h2>Employment Eligibility Verification<br>Department of Homeland Security</h2>
                        <p>U.S. Citizenship and Immigration Services</p>
                    </div>
                    <div class="right-header">
                        <h3>USCIS <br> Form I-9</h3>
                        <p>OMB No. 1615-0047<br>Expires <?php echo I9_EXPIRES; ?></p>
                    </div>
                </article>
                <div class="bg-gray gray-box">
                    <strong>Section 2. Employer or Authorized Representative Review and Verification</strong>
                    <div class="italic">(Employers or their authorized representative must complete and sign Section 2 within 3 business days of the employee's first day of employment. You must physically examine one document from List A OR a combination of one document from List B and one document from List C as listed on the "Lists of Acceptable Documents.")</div>
                </div>
                <table class="i9-table">
                    <tbody>
                    <tr>
                        <td style="width: 20%">
                            <strong>Employee Info from Section 1</strong>
                        </td>
                        <td style="width: 20%">
                            <label>Last Name (Family Name)</label>
                            <span class="value-box" id=""><?php echo $pre_form['section2_last_name']?></span>
                        </td>
                        <td style="width: 20%">
                            <label>First Name (Given Name)</label>
                            <span class="value-box" id=""><?php echo $pre_form['section2_first_name']?></span>
                        </td>
                        <td style="width: 7%">
                            <label>M.I.</label>
                            <span class="value-box" id=""><?php echo $pre_form['section2_middle_initial']?></span>
                        </td>
                        <td style="width: 25%">
                            <label>Citizenship/Immigration Status</label>
                            <span class="value-box" id=""><?php echo $pre_form['section2_citizenship']?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>


                <table class="i9-table border-less-head">
                    <thead>
                    <tr>
                        <th style="text-align: center;">
                            <strong>List A<br><small>Identity and Employment Authorization</small></strong>
                        </th>
                        <th style="text-align: center; width: 15px">
                            <strong>OR</strong>
                        </th>
                        <th style="text-align: center;">
                            <strong>List B<br><small>Identity</small></strong>
                        </th>
                        <th style="text-align: center;">
                            <strong>AND</strong>
                        </th>
                        <th style="text-align: center;" colspan="3">
                            <strong>List C<br><small>Employment Authorization</small></strong>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 45%;">
                            <table class="nested-table">
                                <tr>
                                    <td>
                                        <label>Document Title</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part1_document_title'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part1_document_title']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Issuing Authority</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part1_issuing_authority'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part1_issuing_authority']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Document Number</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part1_document_number'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part1_document_number']?></span>
                                    </td>
                                </tr>
                                <tr class="thick-border">
                                    <td class="thick-border">
                                        <label>Expiration Date (if any)(mm/dd/yyyy)</label>
                                        <span class="value-box no-border" id="">
                                            <?php echo isset($pre_form['section2_lista_part1_expiration_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section2_lista_part1_expiration_date'])->format('m/d/Y') : ''; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Document Title</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part2_document_title'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part2_document_title']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Issuing Authority</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part2_issuing_authority'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part2_issuing_authority']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Document Number</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part2_document_number'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part2_document_number']?></span>
                                    </td>
                                </tr>
                                <tr class="thick-border">
                                    <td class="thick-border">
                                        <label>Expiration Date (if any)(mm/dd/yyyy)</label>
                                        <span class="value-box no-border" id="">
                                            <?php echo isset($pre_form['section2_lista_part2_expiration_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section2_lista_part2_expiration_date'])->format('m/d/Y') : ''; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Document Title</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part3_document_title'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part3_document_title']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Issuing Authority</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part3_issuing_authority'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part3_issuing_authority']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Document Number</label>
                                        <span class="value-box no-border" id=""><?php echo $pre_form['section2_lista_part3_document_number'] == 'n_a' ? 'N/A' : $pre_form['section2_lista_part3_document_number']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="thick-border">
                                        <label>Expiration Date (if any)(mm/dd/yyyy)</label>
                                        <span class="value-box no-border" id="">
                                            <?php echo isset($pre_form['section2_lista_part3_expiration_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section2_lista_part3_expiration_date'])->format('m/d/Y') : ''; ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="bg-gray no-border-bottom" style="width: 2%;"></td>
                        <td colspan="3" style="width: 53%;">
                            <table class="nested-table no-border">
                                <tr>
                                    <td>
                                        <label>Document Title</label>
                                        <span class="value-box" id=""><?php echo $pre_form['section2_listb_document_title'] == 'n_a' ? 'N/A' : $pre_form['section2_listb_document_title']?></span>
                                    </td>
                                    <td>
                                        <label>Document Title</label>
                                        <span class="value-box" id=""><?php echo $pre_form['section2_listc_document_title'] == 'n_a' ? 'N/A' : $pre_form['section2_listc_document_title']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Issuing Authority</label>
                                        <span class="value-box" id=""><?php echo $pre_form['section2_listb_issuing_authority'] == 'n_a' ? 'N/A' : $pre_form['section2_listb_issuing_authority']?></span>
                                    </td>
                                    <td>
                                        <label>Issuing Authority</label>
                                        <span class="value-box" id=""><?php echo $pre_form['section2_listc_issuing_authority'] == 'n_a' ? 'N/A' : $pre_form['section2_listc_issuing_authority']?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Document Number</label>
                                        <span class="value-box" id=""><?php echo $pre_form['section2_listb_document_number'] == 'n_a' ? 'N/A' : $pre_form['section2_listb_document_number']?></span>
                                    </td>
                                    <td>
                                        <label>Document Number</label>
                                        <span class="value-box" id=""><?php echo $pre_form['section2_listc_document_number'] == 'n_a' ? 'N/A' : $pre_form['section2_listc_document_number']?></span>
                                    </td>
                                </tr>
                                <tr class="thick-border">
                                    <td>
                                        <label>Expiration Date (if any)(mm/dd/yyyy)</label>
                                        <span class="value-box no-border" id="">
                                            <?php echo isset($pre_form['section2_listb_expiration_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section2_listb_expiration_date'])->format('m/d/Y') : ''; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <label>Expiration Date (if any)(mm/dd/yyyy)</label>
                                        <span class="value-box no-border" id="">
                                            <?php echo isset($pre_form['section2_listc_expiration_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section2_listc_expiration_date'])->format('m/d/Y') : ''; ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <table class="nested-table no-border" style="height: 371px;">
                                <tr>
                                    <td style="width: 60%">
                                        <div class="qr-code-box box-lg">
                                            Additional Information<br>
                                            <?php echo $pre_form['section2_additional_information']?>
                                        </div>
                                    </td>
                                    <td style="width: 40%">
                                        <div class="qr-code-box">
                                            QR Code - Section 1<br>Do Not Write In This Space
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="mb-2">
                    <strong>Certification: I attest, under penalty of perjury, that (1) I have examined the document(s) presented by the above-named employee, (2) the above-listed document(s) appear to be genuine and to relate to the employee named, and (3) to the best of my knowledge the employee is authorized to work in the United States.</strong>
                </div>
                <div class="mb-2">
                    <strong>The employee's first day of employment (mm/dd/yyyy): <div class="inline-value-box">
                            <span class="value-box" id=""><?php echo isset($pre_form['section2_firstday_of_emp_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section2_firstday_of_emp_date'])->format('m/d/Y') : ''; ?></span>
                        </div> (See instructions for exemptions)</strong>
                </div>
                <table class="i9-table">
                    <tbody>
                    <tr>
                        <td>
                            <label>Signature of Employer or Authorized Representative</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_sig_emp_auth_rep']?></span>
                        </td>
                        <td>
                            <label>Today's Date (mm/dd/yyyy)</label>
                            <span class="value-box no-border" id="">
                                <?php echo isset($pre_form['section2_today_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section2_today_date'])->format('m/d/Y') : ''; ?>
                            </span>
                        </td>
                        <td colspan="2">
                            <label>Title of Employer or Authorized Representative</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_title_of_emp']?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Last Name of Employer or Authorized Representative</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_last_name_of_emp']?></span>
                        </td>
                        <td>
                            <label>First Name of Employer or Authorized Representative</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_first_name_of_emp']?></span>
                        </td>
                        <td colspan="2">
                            <label>Employer's Business or Organization Name</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_emp_business_name']?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Employer's Business or Organization Address (Street Number and Name)</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_emp_business_address']?></span>
                        </td>
                        <td>
                            <label>City or Town</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_city_town']?></span>
                        </td>
                        <td>
                            <label>State</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_state']?></span>
                        </td>
                        <td>
                            <label>ZIP Code</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section2_zip_code']?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="sheet padding-10mm">
                <article class="sheet-header">
                    <div class="header-logo"><img src="<?php echo base_url('assets/images/i9-header.png')?>"></div>
                    <div class="center-col">
                        <h2>Employment Eligibility Verification<br>Department of Homeland Security</h2>
                        <p>U.S. Citizenship and Immigration Services</p>
                    </div>
                    <div class="right-header">
                        <h3>USCIS <br> Form I-9</h3>
                        <p>OMB No. 1615-0047<br>Expires <?php echo I9_EXPIRES; ?></p>
                    </div>
                </article>
                <!-- <table class="i9-table">
                    <tbody>
                    <tr>
                        <td style="width: 20%">
                            <strong>Employee Name from Section 1:</strong>
                        </td>
                        <td style="width: 30%">
                            <label>Last Name (Family Name)</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_pre_last_name']?></span>
                        </td>
                        <td style="width: 30%">
                            <label>First Name (Given Name)</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_pre_first_name']?></span>
                        </td>
                        <td style="width: 20%">
                            <label>Middle Initial</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_pre_middle_initial']?></span>
                        </td>
                    </tr>
                    </tbody>
                </table> -->
                <table class="i9-table">
                    <thead>
                    <tr class="bg-gray">
                        <th colspan="4">
                            <strong>Section 3. Reverification and Rehires </strong><small>(To be completed and signed by employer or authorized representative.)</small>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-gray">
                        <td colspan="3">
                            <strong>A. </strong>New Name (if applicable)
                        </td>
                        <td colspan="1">
                            <strong>B. </strong>Date of Rehire (if applicable)
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Last Name (Family Name)</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_last_name']?></span>
                        </td>
                        <td>
                            <label>First Name (Given Name)</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_first_name']?></span>
                        </td>
                        <td>
                            <label>Middle Initial</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_middle_initial']?></span>
                        </td>
                        <td>
                            <label>Date (mm/dd/yyyy)</label>
                            <span class="value-box no-border" id="">
                                <?php echo isset($pre_form['section3_rehire_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section3_rehire_date'])->format('m/d/Y') : ''; ?>
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="i9-table">
                    <tbody>
                    <tr class="bg-gray">
                        <td colspan="3">
                            <strong>C. </strong>If the employee's previous grant of employment authorization has expired, provide the information for the document or receipt that establishes continuing employment authorization in the space provided below.
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <label>Document Title</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_document_title']?></span>
                        </td>
                        <td style="width: 15%;">
                            <label>Document Number</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_document_number']?></span>
                        </td>
                        <td style="width: 35%;">
                            <label>Expiration Date (if any) (mm/dd/yyyy)</label>
                            <span class="value-box no-border" id="">
                                <?php echo isset($pre_form['section3_expiration_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section3_expiration_date'])->format('m/d/Y') : ''; ?>
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <strong>I attest, under penalty of perjury, that to the best of my knowledge, this employee is authorized to work in the United States, and if the employee presented document(s), the document(s) I have examined appear to be genuine and to relate to the individual.</strong>
                <table class="i9-table">
                    <tbody>
                    <tr>
                        <td style="width: 40%;">
                            <label>Signature of Employer or Authorized Representative</label>
                            <span class="value-box no-border" id="">
                                <?php if(!empty($pre_form)) { ?>
                                    <div >
                                        <img  src="<?php echo isset($pre_form['section3_emp_sign']) && !empty($pre_form['section3_emp_sign']) ? $pre_form['section3_emp_sign'] : ''; ?>"  />
                                    </div>
                                <?php } ?>
                            </span>
                        </td>
                        <td style="width: 20%;">
                            <label>Today's Date (mm/dd/yyyy)</label>
                            <span class="value-box no-border" id="">
                                <?php echo isset($pre_form['section3_today_date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['section3_today_date'])->format('m/d/Y') : ''; ?>
                            </span>
                        </td>
                        <td style="width: 40%;">
                            <label>Name of Employer or Authorized Representative</label>
                            <span class="value-box no-border" id=""><?php echo $pre_form['section3_name_of_emp']?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="sheet padding-10mm">
                <article class="sheet-header acceptable-docs">
                    <h2>LISTS OF ACCEPTABLE DOCUMENTS<br>All documents must be UNEXPIRED</h2>
                    <p>Employees may present one selection from List A <br>or a combination of one selection from List B and one selection from List C.</p>
                </article>
                <table class="i9-table table-padding-none">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 30%;">
                            <strong>LIST A <br>Documents that Establish<br>Both Identity and<br>Employment Authorization</strong>
                        </th>
                        <th class="bg-gray text-center"><strong>OR</strong></th>
                        <th class="text-center">
                            <strong>LIST B <br>Documents that Establish<br>Identity</strong>
                        </th>
                        <th class="text-center"><strong>AND</strong></th>
                        <th class="text-center">
                            <strong>LIST C <br>Documents that Establish<br>Employment Authorization</strong>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <ol class="ordered-list">
                                <li>U.S. Passport or U.S. Passport Card</li>
                                <li>Permanent Resident Card or Alien Registration Receipt Card (Form I-551)</li>
                                <li>Foreign passport that contains a temporary I-551 stamp or temporary I-551 printed notation on a machinereadable immigrant visa</li>
                                <li>Employment Authorization Document that contains a photograph (Form I-766)</li>
                                <li>
                                    For a nonimmigrant alien authorized to work for a specific employer because of his or her status:
                                    <p><strong>a.</strong> Foreign passport; and</p>
                                    <p><strong>b.</strong> Form I-94 or Form I-94A that has the following:</p>
                                    <p>(1) The same name as the passport; <br><br>and</p>
                                    <p>(2) An endorsement of the alien's nonimmigrant status as long as that period of endorsement has not yet expired and the proposed employment is not in conflict with any restrictions or limitations identified on the form.</p>
                                </li>
                                <li>Passport from the Federated States of Micronesia (FSM) or the Republic of the Marshall Islands (RMI) with Form I-94 or Form I-94A indicating nonimmigrant admission under the Compact of Free Association Between the United States and the FSM or RMI</li>
                            </ol>
                        </td>
                        <td class="bg-gray"></td>
                        <td colspan="3" class="no-cell-padding">
                            <table>
                                <tr>
                                    <td style="width: 50%;">
                                        <ol class="ordered-list">
                                            <li>Driver's license or ID card issued by a State or outlying possession of the United States provided it contains a photograph or information such as name, date of birth, gender, height, eye color, and address</li>
                                            <li>ID card issued by federal, state or local government agencies or entities, provided it contains a photograph or information such as name, date of birth, gender, height, eye color, and address</li>
                                            <li>School ID card with a photograph</li>
                                            <li>Voter's registration card</li>
                                            <li>U.S. Military card or draft record</li>
                                            <li>Military dependent's ID card</li>
                                            <li>U.S. Coast Guard Merchant Mariner Card</li>
                                            <li>Native American tribal document</li>
                                            <li>Driver's license issued by a Canadian government authority</li>
                                            <div class="text-center">
                                                <strong>For persons under age 18 who are unable to present a document listed above:</strong>
                                            </div>
                                            <li>School record or report card</li>
                                            <li>Clinic, doctor, or hospital record</li>
                                            <li>Day-care or nursery school record</li>
                                        </ol>
                                    </td>
                                    <td style="width: 50%;">
                                        <ol class="ordered-list">
                                            <li>
                                                A Social Security Account Number card, unless the card includes one of the following restrictions:
                                                <p>(1) NOT VALID FOR EMPLOYMENT</p>
                                                <p>(2) VALID FOR WORK ONLY WITH INS AUTHORIZATION</p>
                                                <p>(3) VALID FOR WORK ONLY WITH DHS AUTHORIZATION</p>
                                            </li>
                                            <li>Certification of report of birth issued by the Department of State (Forms DS-1350, FS-545, FS-240)</li>
                                            <li>Original or certified copy of birth certificate issued by a State, county, municipal authority, or territory of the United States bearing an official seal</li>
                                            <li>Native American tribal document</li>
                                            <li>U.S. Citizen ID Card (Form I-197)</li>
                                            <li>Identification Card for Use of Resident Citizen in the United States (Form I-179)</li>
                                            <li>Employment authorization document issued by the Department of Homeland Security</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center mb-2">
                    <strong>Examples of many of these documents appear in Part 13 of the Handbook for Employers (M-274).</strong>
                </div>
                <div class="text-center mb-2">
                    <strong>Refer to the instructions for more information about acceptable receipts.</strong>
                </div>
            </section>
        </div>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
        <script type="text/javascript">
            $( window ).on( "load", function() {
                var draw = kendo.drawing;
                    draw.drawDOM($("#i9_pdf_section_1"), {
                        avoidLinks: false,
                        paperSize: "auto",
                        multiPage: true,
                        margin: { bottom: "1cm" },
                        scale: 0.8
                    })
                    .then(function(root) {
                        return draw.exportPDF(root);
                    })
                    .done(function(data) {
                        <?php if(!isset($doUpload)){ ?>
                        $('#myiframe').attr("src",data);
                        kendo.saveAs({
                            dataURI: data,
                            fileName: '<?php echo "i9.pdf"; ?>',
                        });
                        <?php } else { ?>
                        uploadPDF(data, 'i9');
                        <?php } ?>
                    });

                <?php if(!isset($doUpload)){ ?>
                setTimeout(function(){
                    window.close();
                }, 2000);
                <?php } else{ ?>
                function uploadPDF(data, typo){
                    $.post("<?=base_url('hr_documents_management/upload');?>", {
                        data: data,
                        token: "<?=$token;?>",
                        employeeSid: "<?= $employeeSid; ?>",
                        type: typo,
                    }, () => {
                        $("#i9_pdf_section_1").closest('html').remove();
                        has['I9'] = true;
                    });
                }
                <?php } ?>
                
            });
        </script>
        <?php if (!isset($doUpload)) { ?>
    </body>
</html>
        <?php } else{ ?>
        </div>
        <?php }?>
