<div class="main jsmaincontent" style="background: #fff;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-right">
                <!-- back to dashboard -->
                <a href="<?= base_url("dashboard"); ?>" class="btn btn-black csRadius5">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    &nbsp;
                    Dashboard
                </a>
                <!-- back to forms -->
                <a href="<?= base_url("hr_documents_management/my_documents"); ?>" class="btn btn-info csRadius5">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    &nbsp;
                    Document Management
                </a>
                <!-- print form -->
                <a href="<?= base_url("hr_documents_management/my_documents"); ?>" class="btn btn-info csRadius5">
                    <i class="fa fa-print" aria-hidden="true"></i>
                    &nbsp;
                    Print
                </a>
                <!-- download form -->
                <a href="<?= base_url("hr_documents_management/my_documents"); ?>" class="btn btn-info csRadius5">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    &nbsp;
                    Download
                </a>
            </div>
        </div>
        <hr>
        <!-- logo -->
        <div class="row">
            <div class="col-sm-4">
                <img src="<?= image_url("forms/w4_mn.svg"); ?>" alt="sd" style="filter: grayscale(100%)" />
            </div>
        </div>
        <form action="javascript:void(0)" autocomplete="off" id="jsStateFormW4Form">
            <!-- text -->
            <div class="row">
                <div class="col-sm-12">
                    <h2>
                        <strong>
                            2023 W-4MN, Minnesota Withholding Allowance/Exemption Certificate
                        </strong>
                    </h2>
                    <h3>
                        <strong>
                            Employees
                        </strong>
                    </h3>
                    <p>
                        Complete Form W-4MN so your employer can withhold the correct Minnesota income tax from your pay. Consider completing a new Form W-4MN each
                        year and when your personal or financial situation changes. If no Form W-4MN is in effect, the number of withholding allowances claimed will be zero.
                    </p>
                </div>
            </div>

            <!-- text -->
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>
                            First Name
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="first_name" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>
                            Initial
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="initial" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>
                            Last Name
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="last_name" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            Social Security Number
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="number" name="ssn" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            Street 1
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="street_1" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            Street 2
                        </label>
                        <input type="text" name="street_2" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            City
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="city" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            State
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="state" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            Zip code
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="number" name="zip_code" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            Country
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="country" class="form-control" value="USA" disabled />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>
                            Marital Status (Check one):
                            <strong class="text-danger">*</strong>
                        </label>
                        <br>
                        <label class="control control--radio">
                            <input type="radio" name="martial_status" value="1">
                            Single; Married, but legally separated; or Spouse is a nonresident alien
                            <div class="control__indicator"></div>
                        </label>
                        <br>
                        <label class="control control--radio">
                            <input type="radio" name="martial_status" value="2">
                            Married
                            <div class="control__indicator"></div>
                        </label>
                        <br>
                        <label class="control control--radio">
                            <input type="radio" name="martial_status" value="3">
                            Married, but withhold at higher Single rate
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <h3>
                        <strong>
                            Complete Section 1 OR Section 2, then sign the bottom and give the completed form to your employer.
                        </strong>
                    </h3>
                </div>
            </div>

            <!-- section 1 -->
            <div class="row">
                <div class="col-sm-12">
                    <h3>
                        <strong>
                            <label class="control control--checkbox">
                                <input type="checkbox" name="section_1" />
                                Section 1 — Determining Minnesota Allowances
                                <div class="control__indicator"></div>
                            </label>
                        </strong>
                    </h3>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <strong>
                            A
                        </strong>
                        Enter “1” if no one else can claim you as a dependent
                    </p>
                    <input type="text" class="form-control" name="section_1_a" />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <strong>
                            B
                        </strong>
                        Enter “1” if any of the following apply:
                    </p>
                    <ul style="padding-left: 25px;">
                        <li>
                            You are single and have only one job
                        </li>
                        <li>
                            You are married, have only one job, and your spouse does not work
                        </li>
                        <li>
                            Your wages from a second job or your spouse’s wages are $1500 or less
                        </li>
                    </ul>
                    <input type="text" class="form-control" name="section_1_b" />
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <strong>
                            C
                        </strong>
                        Enter “1” if you are married. Or choose to enter “0” if you are married and have either a working spouse or more than one job. (Entering “0” may help you avoid having too little tax withheld.) .
                    </p>
                    <input type="text" class="form-control" name="section_1_c" />
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <strong>
                            D
                        </strong>
                        Enter the number of dependents (other than your spouse or yourself) you will claim on your tax return.
                    </p>
                    <input type="text" class="form-control" name="section_1_d" />
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <strong>
                            E
                        </strong>
                        Enter “1” if you will use the filing status Head of Household (see instructions).
                    </p>
                    <input type="text" class="form-control" name="section_1_e" />
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <strong>
                            F
                        </strong>
                        Add steps A through E. If you plan to itemize deductions on your 2023 Minnesota income tax return, you may also complete the Itemized Deductions and Additional Income Worksheet.
                    </p>
                    <input type="text" class="form-control" name="section_1_f" />
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <strong>
                            1
                        </strong>
                        Minnesota Allowances. Enter Step F from Section 1 above or Step 10 of the Itemized Deductions Worksheet
                    </p>
                    <input type="text" class="form-control" name="section_1_allowances" />
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <strong>
                            2
                        </strong>
                        Additional Minnesota withholding you want deducted for each pay period (see instructions)
                    </p>

                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" class="form-control" name="section_1_additional_withholding" />
                    </div>

                </div>
            </div>


            <!-- section 2 -->
            <div class="row">
                <div class="col-sm-12">
                    <h3>
                        <strong>
                            <label class="control control--checkbox">
                                <input type="checkbox" name="section_2" />
                                Section 2 — Exemption From Minnesota Withholding
                                <div class="control__indicator"></div>
                            </label>
                        </strong>
                    </h3>
                    <p>
                        Complete Section 2 if you claim to be exempt from Minnesota income tax withholding (see Section 2 instructions for qualifications). If applicable,
                        check one box below to indicate why you believe you are exempt:
                    </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-12">
                    <label class="control control--checkbox">
                        <input type="checkbox" name="section_2_a" />
                        A I meet the requirements and claim exempt from both federal and Minnesota income tax withholding
                        <div class="control__indicator"></div>
                    </label>
                    <br>
                    <label class="control control--checkbox">
                        <input type="checkbox" name="section_2_b" />
                        B Even though I did not claim exempt from federal withholding, I claim exempt from Minnesota withholding, because:
                        <ul style="padding-left: 25px;">
                            <li>I had no Minnesota income tax liability last year</li>
                            <li>I received a refund of all Minnesota income tax withheld</li>
                            <li>I expect to have no Minnesota income tax liability this year</li>
                        </ul>
                        <div class="control__indicator"></div>
                    </label>

                    <br>
                    <label class="control control--checkbox">
                        <input type="checkbox" name="section_2_c" />
                        C All of these apply:
                        <div class="control__indicator"></div>
                    </label>
                    <ul style="padding-left: 25px;">
                        <li>My spouse is a military service member assigned to a military location in Minnesota</li>
                        <li>My domicile (legal residence) is in another state</li>
                        <li>
                            I am in Minnesota solely to be with my spouse. My state of domicile is
                            <input type="text" class="form-control" name="section_2_c_3_value">
                        </li>
                    </ul>

                    <br>
                    <label class="control control--checkbox">
                        <input type="checkbox" name="section_2_d" />
                        D I am an American Indian that resides and works on a reservation for which I am enrolled (see instructions).
                        <div class="control__indicator"></div>
                    </label>
                    <ul style="padding-left: 25px;">
                        <li>
                            Enter the reservation name:
                            <input type="text" class="form-control" name="section_2_d_1_value">
                        </li>
                        <li>
                            Enter your Certificate of Degree of Indian Blood (CDIB)/Enrollment number:
                            <input type="text" class="form-control" name="section_2_d_2_value">
                        </li>
                    </ul>

                    <br>
                    <label class="control control--checkbox">
                        <input type="checkbox" name="section_2_e" />
                        E I am a member of the Minnesota National Guard or an active-duty U.S. military member and claim exempt from Minnesota withholding
                        on my military pay
                        <div class="control__indicator"></div>
                    </label>

                    <br>
                    <label class="control control--checkbox">
                        <input type="checkbox" name="section_2_f" />
                        F I receive a military pension or other military retirement pay as calculated under U.S. Code, title 10, sections 1401 through 1414, 1447
                        through 1455, and 12733, and I claim exempt from Minnesota withholding on this retirement pay
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>

            <hr>


            <!-- Signature -->
            <div class="row">
                <div class="col-sm-12">
                    <label class="control control--checkbox">
                        <input type="checkbox" name="user_consent">
                        <em>
                            I certify that all information provided in Section 1 OR Section 2 is correct. I understand there is a $500 penalty for filing a false Form W-4MN.
                        </em>
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>

            <hr>


            <div class="row">
                <div class="col-sm-4">
                    <label>
                        Employee's Signature
                        <strong class="text-danger">*</strong>
                    </label>
                    <div>
                        <img src="" alt="" id="prepare_signature_img">
                        <input type="text" id="section1_preparer_signature">
                        <button type="button" class="btn blue-button break-word-text jsLoadSignature"><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label>
                        Date
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" readonly name="date">
                </div>
                <div class="col-sm-4">
                    <label>
                        Daytime Phone Number
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="day_time_phone_number">
                </div>
            </div>

            <input type="hidden" id="is_signature" value="false">
            <input type="hidden" id="is_signature_initial" value="false">
            <input type="hidden" id="is_signature_date" value="false">

            <hr>

            <!--  -->
            <div class="row">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-info csRadius5 jsStateFormW4Btn">
                        I certify that all information provided in Section 1 OR Section 2 is correct. I understand there is a $500 penalty for filing a false Form W-4MN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<?php $this->load->view('static-pages/e_signature_popup_2023'); ?>