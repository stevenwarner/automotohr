<?php
$company_name = ucwords($session['company_detail']['CompanyName']);
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a href="<?php echo base_url('onboarding/hr_documents/' . $unique_sid); ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i> Documents</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile">Form W-4 (<?php echo date('Y'); ?>)</h2>
                    <div class="row mb-2">
                        <!-- <?php //if ($pre_form['user_consent'] == 1) { 
                                ?> -->
                        <?php if ($pre_form['uploaded_file'] != NULL) { ?>
                            <div class="col-lg-7"></div>
                            <!--                                <div class="col-lg-2">-->
                            <!--                                    <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);" class="btn blue-button btn-block">Preview</a>-->
                            <!--                                </div>-->
                            <?php
                            $document_filename = $pre_form['uploaded_file'];
                            $document_file = pathinfo($document_filename);
                            $document_extension = $document_file['extension'];
                            $name = explode(".", $document_filename);
                            $url_segment_original = $name[0];
                            ?>
                            <div class="col-lg-2 cs-btn-setting">
                                <?php if ($document_extension == 'pdf') { ?>

                                    <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_original . '.pdf' ?>" class="btn blue-button btn-block">Print</a>

                                <?php } else if ($document_extension == 'docx') { ?>
                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edocx&wdAccPdf=0' ?>" class="btn blue-button btn-block">Print</a>
                                <?php } else if ($document_extension == 'doc') { ?>
                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edoc&wdAccPdf=0' ?>" class="btn blue-button btn-block">Print</a>
                                <?php } else if ($document_extension == 'xls') { ?>
                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exls' ?>" class="btn blue-button btn-block">Print</a>
                                <?php } else if ($document_extension == 'xlsx') { ?>
                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exlsx' ?>" class="btn blue-button btn-block">Print</a>
                                <?php } ?>
                            </div>
                            <div class="col-lg-3 cs-btn-setting">
                                <a download="W4 Submitted Form" href="<?php echo base_url('hr_documents_management/download_upload_document/') . $pre_form['uploaded_file']; ?>" class="btn blue-button btn-block">Download Submitted Form</a>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-6"></div>
                            <div class="col-lg-2">
                                <form action="<?php echo current_url() ?>" method="get">
                                    <input class="btn blue-button btn-block" id="download-pdf" value="Download PDF" name="submit" type="submit">
                                </form>
                            </div>
                            <div class="col-lg-2">
                                <a target="_blank" href="<?php echo base_url('onboarding/print_form_w4/' . $unique_sid); ?>" class="btn blue-button btn-block">Print PDF</a>
                            </div>
                            <div class="col-lg-2">
                                <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);" class="btn blue-button btn-block">Preview PDF</a>
                            </div>
                        <?php } ?>
                        <!-- <? php // }
                                ?> -->
                    </div>
                </div>
                <?php if (!empty($pre_form['uploaded_file']) && $pre_form['uploaded_file'] != NULL) { ?>
                    <div class="form-wrp">
                        <iframe src="<?= AWS_S3_BUCKET_URL . $pre_form['uploaded_file']; ?>?embedded=true" style="width: 100%; height: 80rem;"></iframe>
                    </div>
                <?php } else { ?>
                    <div class="form-wrp">
                        <div class="col-lg-2">
                            <strong>Form W-4</strong>
                            <p>Department of the Treasury Internal Revenue Service</p>
                        </div>
                        <div class="col-lg-8 text-center">
                            <h2 style="margin-top: 0;">Employee’s Withholding Allowance Certificate</h2>
                            <p>▶ Complete Form W-4 so that your employer can withhold the correct federal income tax from your pay.</p>
                            <p>▶ Give Form W-4 to your employer</p>
                            <p>▶ Your withholding is subject to review by the IRS.</p>
                        </div>
                        <div class="col-lg-2 text-center">
                            <p>OMB No. 1545-0074</p>
                            <strong><?php echo date('Y'); ?></strong>
                        </div>
                        <form id="w4-form" action="" method="post">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b> Step 1:Enter Personal Information</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>1. Your first name</label> <?php //_e($pre_form,true);
                                                                                    ?>
                                                <input type="text" value="<?php echo !empty($pre_form['first_name']) ? $pre_form['first_name'] : $applicant['first_name'] ?>" name="w4_first_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>Your middle initial</label>
                                                <input type="text" value="<?php echo !empty($pre_form['middle_name']) ? $pre_form['middle_name'] : $applicant['middle_name'] ?>" name="w4_middle_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>Your Last name</label>
                                                <input type="text" value="<?php echo !empty($pre_form['last_name']) ? $pre_form['last_name'] : $applicant['last_name'] ?>" name="w4_last_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>Social security number</label>
                                                <input type="text" value="<?php echo !empty($pre_form['ss_number']) ? $pre_form['ss_number'] : $applicant['ssn'] ?>" name="ss_number" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" value="<?php echo !empty($pre_form['home_address']) ? $pre_form['home_address'] : $applicant['address'] ?>" name="home_address" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <label>City or town</label>
                                                <input type="text" value="<?php echo !empty($pre_form['city']) ? $pre_form['city'] : $applicant['city'] ?>" name="city" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <?
                                                $stateName = '';
                                                if (!empty($applicant['state'])) {
                                                    $stateName = db_get_state_name_only($applicant['state']);
                                                }
                                                ?>
                                                <input type="text" value="<?php echo !empty($pre_form['state']) ? $pre_form['state'] : $stateName ?>" name="state" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>ZIP Code</label>
                                                <input type="text" value="<?php echo !empty($pre_form['zip']) ? $pre_form['zip'] : $applicant['zipcode'] ?>" name="zip" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <label>▶ Does your name match the name on your social security card? If not, to ensure you get credit for your earnings, contact SSA at 800-772-1213 or go to www.ssa.gov.</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="control control--checkbox">
                                                    Single or Married filing separately
                                                    <input type="radio" name="marriage_status" value="separately" <?php echo !empty($pre_form) && $pre_form['marriage_status'] == 'separately' ? 'checked="checked"' : '' ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="control control--checkbox">
                                                    Married filing jointly (or Qualifying widow(er))
                                                    <input type="radio" name="marriage_status" value="jointly" <?php echo !empty($pre_form) && $pre_form['marriage_status'] == 'jointly' ? 'checked="checked"' : '' ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="control control--checkbox">
                                                    Head of household (Check only if you’re unmarried and pay more than half the costs of keeping up a home for yourself and a qualifying individual.)
                                                    <input type="radio" name="marriage_status" value="head" <?php echo !empty($pre_form) && $pre_form['marriage_status'] == 'head' ? 'checked="checked"' : '' ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div>
                                    <p><b>Complete Steps 2–4 ONLY if they apply to you; otherwise, skip to Step 5.</b> See page 2 for more information on each step, who can
                                        claim exemption from withholding, when to use the online estimator, and privacy.</p>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 2:Multiple Jobs or Spouse Works</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <p>Complete this step if you (1) hold more than one job at a time, or (2) are married filing jointly and your spouse also works. The correct amount of withholding depends on income earned from all of these jobs.</p>
                                            <p>Do <b>only one</b> of the following.</p>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="control control--checkbox">
                                                        (a) Use the estimator at www.irs.gov/W4App for most accurate withholding for this step (and Steps 3–4)</b>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="control control--checkbox">
                                                        (b) Use the Multiple Jobs Worksheet on page 3 and enter the result in Step 4(c) below for roughly accurate withholding
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label class="control control--checkbox">
                                                        (c) If there are only two jobs total, you may check this box. Do the same on Form W-4 for the other job. This option is accurate for jobs with similar pay; otherwise, more tax than necessary may be withheld
                                                        <input type="checkbox" name="mjsw_status" value="similar_pay" <?php echo !empty($pre_form) && isset($pre_form['mjsw_status']) && $pre_form['mjsw_status'] == 'similar_pay' ? 'checked="checked"' : '' ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <p><b>TIP:</b> To be accurate, submit a 2020 Form W-4 for all other jobs. If you (or your spouse) have self-employment income, including as an independent contractor, use the estimator.</p>
                                            <p><b>Complete Steps 3–4(b) on Form W-4 for only ONE of these jobs. </b>Leave those steps blank for the other jobs. (Your withholding will be most accurate if you complete Steps 3–4(b) on the Form W-4 for the highest paying job.)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 3:Claim Dependents</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <p>If your income will be $200,000 or less ($400,000 or less if married filing jointly):</p>
                                            <div>
                                                <p>Multiply the number of qualifying children under age 17 by $2,000</p>
                                                <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dependents_children']) ? $pre_form['dependents_children'] : '' ?>" name="dependents_children" class="form-control" />
                                            </div>
                                            <div>
                                                <p>Multiply the number of other dependents by $500 </p>
                                                <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['other_dependents']) ? $pre_form['other_dependents'] : '' ?>" name="other_dependents" class="form-control" />
                                            </div>
                                            <div>
                                                <p>Add the amounts above and enter the total here</p>
                                                <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['claim_total_amount']) ? $pre_form['claim_total_amount'] : '' ?>" name="claim_total_amount" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 4 (optional) :Other Adjustments</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div>
                                                <p><b>(a) Other income (not from jobs)</b>. If you want tax withheld for other income you expect
                                                    this year that won’t have withholding, enter the amount of other income here. This may
                                                    include interest, dividends, and retirement income </p>
                                                <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['other_income']) ? $pre_form['other_income'] : '' ?>" name="other_income" class="form-control" />
                                            </div>
                                            <div>
                                                <p><b>(b) Deductions</b>. If you expect to claim deductions other than the standard deduction
                                                    and want to reduce your withholding, use the Deductions Worksheet on page 3 and
                                                    enter the result here . .</p>
                                                <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['other_deductions']) ? $pre_form['other_deductions'] : '' ?>" name="other_deductions" class="form-control" />
                                            </div>
                                            <div>
                                                <p>Extra withholding. Enter any additional tax you want withheld each pay period </p>
                                                <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['additional_tax']) ? $pre_form['additional_tax'] : '' ?>" name="additional_tax" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 5:Sign Here</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <p>Under penalties of perjury, I declare that this certificate, to the best of my knowledge and belief, is true, correct, and complete.</p>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <span><b>Employee's signature</b> (This form is not valid unless you sign it.)</span>
                                                <?php if ($signed_flag == true) { ?>
                                                    <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?php echo $pre_form['signature_bas64_image']; ?>" class="esignaturesize" />
                                                <?php } else { ?>
                                                    <!-- the below loaded view add e-signature -->
                                                    <?php $this->load->view('static-pages/e_signature_button'); ?>
                                                <?php } ?>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="row form-group">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label>Date</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <?php
                                                        $sign_date = '';
                                                        if (isset($pre_form) && !empty($pre_form['signature_timestamp']) && $pre_form['signature_timestamp'] != '0000-00-00') {
                                                            $sign_date = date("m-d-Y", strtotime($pre_form['signature_timestamp']));
                                                        }
                                                        ?>
                                                        <input type="text" name="signature_date" class="form-control sign_date" value="<?php echo $sign_date; ?>" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p>
                                    <b>For Privacy Act and Paperwork Reduction Act Notice, see page 3</b>
                                </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 2(b)—Multiple Jobs Worksheet (Keep for your records.)</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <p>If you choose the option in Step 2(b) on Form W-4, complete this worksheet (which calculates the total extra tax for all jobs) on<b> only ONE</b> Form W-4. Withholding will be most accurate if you complete the worksheet and enter the result on the Form W-4 for the highest paying job.</p>
                                            <p><b>Note:</b>If more than one job has annual wages of more than $120,000 or there are more than three jobs, see Pub. 505 for additional tables; or, you can use the online withholding estimator at www.irs.gov/W4App.</p>
                                            <div>
                                                <div>
                                                    <div class="form-group">
                                                        <p> <b>1&nbsp; Two jobs.</b> If you have two jobs or you’re married filing jointly and you and your spouse each have one job, find the amount from the appropriate table on page 4. Using the “Higher Paying Job” row and the “Lower Paying Job” column, find the value at the intersection of the two household salaries and enter that value on line 1. Then, skip to line 3 </p>
                                                        <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['mjw_two_jobs']) ? $pre_form['mjw_two_jobs'] : '' ?>" name="mjw_two_jobs" class="form-control" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <p><b>2&nbsp; Three jobs.</b> If you and/or your spouse have three jobs at the same time, complete lines 2a, 2b, and 2c below. Otherwise, skip to line 3</p>
                                                </div>
                                                <div>
                                                    <div class="form-group">
                                                        &nbsp;&nbsp; &nbsp;&nbsp;<p><b>a</b>&nbsp;
                                                            Find the amount from the appropriate table on page 4 using the annual wages from the highest paying job in the “Higher Paying Job” row and the annual wages for your next highest paying job in the “Lower Paying Job” column. Find the value at the intersection of the two household salaries and enter that value on line 2a
                                                        <p>
                                                            <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['mjw_three_jobs_a']) ? $pre_form['mjw_three_jobs_a'] : '' ?>" name="mjw_three_jobs_a" class="form-control" />
                                                    </div>
                                                    <div>
                                                        <div class="form-group">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;<p><b>b</b>&nbsp;
                                                                Add the annual wages of the two highest paying jobs from line 2a together and use the total as the wages in the “Higher Paying Job” row and use the annual wages for your third job in the “Lower Paying Job” column to find the amount from the appropriate table on page 4 and enter this amount on line 2b </p>
                                                            <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['mjw_three_jobs_b']) ? $pre_form['mjw_three_jobs_b'] : '' ?>" name="mjw_three_jobs_b" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="form-group">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;<p><b>c</b>&nbsp;
                                                                Add the amounts from lines 2a and 2b and enter the result on line 2c</p>
                                                            <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['mjw_three_jobs_c']) ? $pre_form['mjw_three_jobs_c'] : '' ?>" name="mjw_three_jobs_c" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-group">
                                                        <p><b>3&nbsp;&nbsp;</b>Enter the number of pay periods per year for the highest paying job. For example, if that job pays weekly, enter 52; if it pays every other week, enter 26; if it pays monthly, enter 12, etc. </p>
                                                        <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['mjw_pp_py']) ? $pre_form['mjw_pp_py'] : '' ?>" name="mjw_pp_py" class="form-control" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-group">
                                                        <p><b>4 &nbsp;Divide</b> the annual amount on line 1 or line 2c by the number of pay periods on line 3. Enter this amount here and in <b>Step 4(c)</b> of Form W-4 for the highest paying job (along with any other additional amount you want withheld) </p>
                                                        <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['mjw_divide']) ? $pre_form['mjw_divide'] : '' ?>" name="mjw_divide" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 4(b)—Deductions Worksheet (Keep for your records.)</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>1. Enter an estimate of your 2020 itemized deductions (from Schedule A (Form 1040 or 1040-SR)). Such deductions may include qualifying home mortgage interest, charitable contributions, state and local taxes (up to $10,000), and medical expenses in excess of 7.5% of your income</label>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_1']) ? $pre_form['dw_input_1'] : '' ?>" name="dw_input_1" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>2. Enter: </label>
                                                    <p>
                                                        &nbsp;&nbsp;• $24,000 if you’re married filing jointly or qualifying widow(er)<br>
                                                        &nbsp;&nbsp;• $18,000 if you’re head of household<br>
                                                        &nbsp;&nbsp;• $12,000 if you’re single or married filing separately
                                                    </p>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_2']) ? $pre_form['dw_input_2'] : '' ?>" name="dw_input_2" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>3. If line 1 is greater than line 2, subtract line 2 from line 1. If line 2 is greater than line 1, enter “-0-” </label>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_3']) ? $pre_form['dw_input_3'] : '' ?>" name="dw_input_3" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>4. Enter an estimate of your <?php echo date('Y'); ?> adjustments to income and any additional standard deduction for age or blindness (see Pub. 505 for information about these items) </label>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_4']) ? $pre_form['dw_input_4'] : '' ?>" name="dw_input_4" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>5. Add lines 3 and 4. Enter the result here and in Step 4(b) of Form W-4</label>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_5']) ? $pre_form['dw_input_5'] : '' ?>" name="dw_input_5" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <p><strong>Privacy Act and Paperwork Reduction Act Notice. </strong> We ask for the information
                                        on this form to carry out the Internal Revenue laws of the United States. Internal
                                        Revenue Code sections 3402(f)(2) and 6109 and their regulations require you to
                                        provide this information; your employer uses it to determine your federal income
                                        tax withholding. Failure to provide a properly completed form will result in your
                                        being treated as a single person with no other entries on the form; providing
                                        fraudulent information may subject you to penalties. Routine uses of this
                                        information include giving it to the Department of Justice for civil and criminal
                                        litigation; to cities, states, the District of Columbia, and U.S. commonwealths and
                                        possessions for use in administering their tax laws; and to the Department of
                                        Health and Human Services for use in the National Directory of New Hires. We
                                        may also disclose this information to other countries under a tax treaty, to federal
                                        and state agencies to enforce federal nontax criminal laws, or to federal law
                                        enforcement and intelligence agencies to combat terrorism.</p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <p>You are not required to provide the information requested on a form that is
                                        subject to the Paperwork Reduction Act unless the form displays a valid OMB
                                        control number. Books or records relating to a form or its instructions must be
                                        retained as long as their contents may become material in the administration of
                                        any Internal Revenue law. Generally, tax returns and return information are
                                        confidential, as required by Code section 6103</p>
                                    <p>The average time and expenses required to complete and file this form will vary
                                        depending on individual circumstances. For estimated averages, see the
                                        instructions for your income tax return.</p>
                                    <p>If you have suggestions for making this form simpler, we would be happy to hear
                                        from you. See the instructions for your income tax return.</p>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Married Filing Jointly or Qualifying Widow(er)</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div style="overflow-x:auto;">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th rowspan="2" class="cs_th_setting">Higher Paying Job Annual Taxable Wage & Salary</th>
                                                            <th colspan="12" class="text-center">Lower Paying Job Annual Taxable Wage & Salary</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$0 - 9,999</td>
                                                            <td class="cs_td_setting">$10,000 - 19,999</td>
                                                            <td class="cs_td_setting">$20,000 - 29,999</td>
                                                            <td class="cs_td_setting">$30,000 - 39,999</td>
                                                            <td class="cs_td_setting">$40,000 - 49,999</td>
                                                            <td class="cs_td_setting">$50,000 - 59,999</td>
                                                            <td class="cs_td_setting">$60,000 - 69,999</td>
                                                            <td class="cs_td_setting">$70,000 - 79,999</td>
                                                            <td class="cs_td_setting">$80,000 - 89,999</td>
                                                            <td class="cs_td_setting">$90,000 - 99,999</td>
                                                            <td class="cs_td_setting">$100,000 - 109,999</td>
                                                            <td class="cs_td_setting">$110,000 - 120,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$0 - 9,999<br />
                                                                $10,000 - 19,999<br />
                                                                $20,000 - 29,999</td>
                                                            <td class="cs_td_setting">$0<br />
                                                                220<br />
                                                                850 </td>
                                                            <td class="cs_td_setting">$220<br />
                                                                1,220<br />
                                                                1,900 </td>
                                                            <td class="cs_td_setting">$850<br />
                                                                1,900<br />
                                                                2,730 </td>
                                                            <td class="cs_td_setting">$900<br />
                                                                2,100<br />
                                                                2,930 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                2,220<br />
                                                                3,050 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                2,220<br />
                                                                3,050 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                2,220<br />
                                                                3,050 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                2,220<br />
                                                                3,240 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                2,410<br />
                                                                4,240</td>
                                                            <td class="cs_td_setting">$1,210<br />
                                                                3,410<br />
                                                                5,240</td>
                                                            <td class="cs_td_setting">$1,870<br />
                                                                4,070<br />
                                                                5,900 </td>
                                                            <td class="cs_td_setting">$1,870<br />
                                                                4,070<br />
                                                                5,900 </td>

                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$30,000 - 39,999<br />
                                                                $40,000 - 49,999<br />
                                                                $50,000 - 59,999</td>
                                                            <td class="cs_td_setting">900<br />
                                                                1,020<br />
                                                                1,020</td>
                                                            <td class="cs_td_setting">2,100<br />
                                                                2,220<br />
                                                                2,220</td>
                                                            <td class="cs_td_setting">2,930<br />
                                                                3,050<br />
                                                                3,050</td>
                                                            <td class="cs_td_setting">3,130<br />
                                                                3,250<br />
                                                                3,250</td>
                                                            <td class="cs_td_setting">3,250<br />
                                                                3,370<br />
                                                                3,570 </td>
                                                            <td class="cs_td_setting">3,250<br />
                                                                3,570<br />
                                                                4,570</td>
                                                            <td class="cs_td_setting">3,440<br />
                                                                4,570<br />
                                                                5,570 </td>
                                                            <td class="cs_td_setting">4,440<br />
                                                                5,570<br />
                                                                6,570</td>
                                                            <td class="cs_td_setting">5,440<br />
                                                                6,570<br />
                                                                7,570</td>
                                                            <td class="cs_td_setting">6,440<br />
                                                                7,570<br />
                                                                8,570</td>
                                                            <td class="cs_td_setting">7,100<br />
                                                                8,220<br />
                                                                9,220 </td>
                                                            <td class="cs_td_setting">7,100<br />
                                                                8,220<br />
                                                                9,220</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$60,000 - 69,999<br />
                                                                $70,000 - 79,999<br />
                                                                $80,000 - 99,999</td>
                                                            <td class="cs_td_setting">1,020<br />
                                                                1,020<br />
                                                                1,060</td>
                                                            <td class="cs_td_setting">2,220<br />
                                                                2,220<br />
                                                                3,260 </td>
                                                            <td class="cs_td_setting">3,050<br />
                                                                3,240<br />
                                                                5,090</td>
                                                            <td class="cs_td_setting">3,440<br />
                                                                4,440<br />
                                                                6,290</td>
                                                            <td class="cs_td_setting">4,570<br />
                                                                5,570<br />
                                                                7,420</td>
                                                            <td class="cs_td_setting">5,570<br />
                                                                6,570<br />
                                                                8,420</td>
                                                            <td class="cs_td_setting">6,570<br />
                                                                7,570<br />
                                                                9,420</td>
                                                            <td class="cs_td_setting">7,570<br />
                                                                8,570<br />
                                                                10,420</td>
                                                            <td class="cs_td_setting">8,570<br />
                                                                9,570<br />
                                                                11,420</td>
                                                            <td class="cs_td_setting">9,570<br />
                                                                10,570<br />
                                                                12,420</td>
                                                            <td class="cs_td_setting">10,220<br />
                                                                11,220<br />
                                                                13,260</td>
                                                            <td class="cs_td_setting">10,220<br />
                                                                11,240<br />
                                                                13,460</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting"> $100,000 - 149,999<br />
                                                                $150,000 - 239,999<br />
                                                                $240,000 - 259,999</td>
                                                            <td class="cs_td_setting">1,870<br />
                                                                1,020<br />
                                                                2,040</td>
                                                            <td class="cs_td_setting">4,070<br />
                                                                4,440<br />
                                                                4,440 </td>
                                                            <td class="cs_td_setting">5,900<br />
                                                                6,470<br />
                                                                6,470</td>
                                                            <td class="cs_td_setting">7,100<br />
                                                                7,870<br />
                                                                7,870</td>
                                                            <td class="cs_td_setting">8,220<br />
                                                                9,190<br />
                                                                9,190</td>
                                                            <td class="cs_td_setting"> 9,320<br />
                                                                10,390<br />
                                                                10,390</td>
                                                            <td class="cs_td_setting">10,520<br />
                                                                11,590<br />
                                                                11,590</td>
                                                            <td class="cs_td_setting">11,720<br />
                                                                12,790<br />
                                                                12,790</td>
                                                            <td class="cs_td_setting">12,920<br />
                                                                13,990<br />
                                                                13,990</td>
                                                            <td class="cs_td_setting">14,120<br />
                                                                15,190<br />
                                                                15,520</td>
                                                            <td class="cs_td_setting">14,980<br />
                                                                16,050<br />
                                                                17,170</td>
                                                            <td class="cs_td_setting">15,180<br />
                                                                16,250<br />
                                                                18,170</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$260,000 - 279,999<br />
                                                                $280,000 - 299,999<br />
                                                                $300,000 - 319,999</td>
                                                            <td class="cs_td_setting">2,040<br />
                                                                2,040<br />
                                                                2,040</td>
                                                            <td class="cs_td_setting">4,440<br />
                                                                4,440<br />
                                                                4,440 </td>
                                                            <td class="cs_td_setting">6,470<br />
                                                                6,470<br />
                                                                6,470</td>
                                                            <td class="cs_td_setting">7,870<br />
                                                                7,870<br />
                                                                8,200</td>
                                                            <td class="cs_td_setting">9,190<br />
                                                                9,190<br />
                                                                10,320</td>
                                                            <td class="cs_td_setting">10,390<br />
                                                                11,720<br />
                                                                12,320</td>
                                                            <td class="cs_td_setting">11,590<br />
                                                                12,720<br />
                                                                14,320</td>
                                                            <td class="cs_td_setting">13,120<br />
                                                                14,720<br />
                                                                16,320</td>
                                                            <td class="cs_td_setting">15,120<br />
                                                                16,720<br />
                                                                18,320</td>
                                                            <td class="cs_td_setting">17,120<br />
                                                                18,720<br />
                                                                20,320</td>
                                                            <td class="cs_td_setting">18,770<br />
                                                                20,370<br />
                                                                21,970</td>
                                                            <td class="cs_td_setting">19,770<br />
                                                                21,370<br />
                                                                22,970</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$320,000 - 364,999<br />
                                                                $365,000 - 524,999<br />
                                                                $525,000 and over</td>
                                                            <td class="cs_td_setting">2,720<br />
                                                                2,970<br />
                                                                3,140</td>
                                                            <td class="cs_td_setting">5,920<br />
                                                                6,470<br />
                                                                6,840 </td>
                                                            <td class="cs_td_setting">8,750<br />
                                                                9,600<br />
                                                                10,170</td>
                                                            <td class="cs_td_setting">10,950<br />
                                                                12,100<br />
                                                                12,870</td>
                                                            <td class="cs_td_setting">13,070<br />
                                                                14,530<br />
                                                                15,500</td>
                                                            <td class="cs_td_setting">15,070<br />
                                                                16,830<br />
                                                                18,000</td>
                                                            <td class="cs_td_setting">17,070<br />
                                                                19,130<br />
                                                                20,500</td>
                                                            <td class="cs_td_setting">19,070<br />
                                                                21,430<br />
                                                                23,000</td>
                                                            <td class="cs_td_setting">21,290<br />
                                                                23,730<br />
                                                                25,500</td>
                                                            <td class="cs_td_setting">23,590<br />
                                                                26,030<br />
                                                                28,000</td>
                                                            <td class="cs_td_setting">25,540<br />
                                                                27,980<br />
                                                                30,150</td>
                                                            <td class="cs_td_setting">26,840<br />
                                                                29,280<br />
                                                                31,650</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Single or Married Filing Separately</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div style="overflow-x:auto;">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th rowspan="2" class="cs_th_setting">Higher Paying Job Annual Taxable Wage & Salary</th>
                                                            <th colspan="12" class="text-center">Lower Paying Job Annual Taxable Wage & Salary</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$0 - 9,999</td>
                                                            <td class="cs_td_setting">$10,000 - 19,999</td>
                                                            <td class="cs_td_setting">$20,000 - 29,999</td>
                                                            <td class="cs_td_setting">$30,000 - 39,999</td>
                                                            <td class="cs_td_setting">$40,000 - 49,999</td>
                                                            <td class="cs_td_setting">$50,000 - 59,999</td>
                                                            <td class="cs_td_setting">$60,000 - 69,999</td>
                                                            <td class="cs_td_setting">$70,000 - 79,999</td>
                                                            <td class="cs_td_setting">$80,000 - 89,999</td>
                                                            <td class="cs_td_setting">$90,000 - 99,999</td>
                                                            <td class="cs_td_setting">$100,000 - 109,999</td>
                                                            <td class="cs_td_setting">$110,000 - 120,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$0 - 9,999<br />
                                                                $10,000 - 19,999<br />
                                                                $20,000 - 29,999</td>
                                                            <td class="cs_td_setting">$460<br />
                                                                940<br />
                                                                1,020 </td>
                                                            <td class="cs_td_setting">$940<br />
                                                                1,530<br />
                                                                1,610 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                1,610<br />
                                                                2,130 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                2,060<br />
                                                                3,130 </td>
                                                            <td class="cs_td_setting">$1,470<br />
                                                                3,060<br />
                                                                4,130 </td>
                                                            <td class="cs_td_setting">$1,870<br />
                                                                3,460<br />
                                                                4,720 </td>
                                                            <td class="cs_td_setting">$1,870<br />
                                                                3,640<br />
                                                                4,920 </td>
                                                            <td class="cs_td_setting">$1,870<br />
                                                                3,460<br />
                                                                4,720 </td>
                                                            <td class="cs_td_setting">$2,040<br />
                                                                3,830<br />
                                                                5,110</td>
                                                            <td class="cs_td_setting">$2,040<br />
                                                                3,830<br />
                                                                5,110</td>
                                                            <td class="cs_td_setting">$2,040<br />
                                                                3,830<br />
                                                                5,110 </td>
                                                            <td class="cs_td_setting">$1,870<br />
                                                                4,070<br />
                                                                5,900 </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$30,000 - 39,999<br />
                                                                $40,000 - 59,999<br />
                                                                $60,000 - 79,999</td>
                                                            <td class="cs_td_setting">1,020<br />
                                                                1,870<br />
                                                                1,870</td>
                                                            <td class="cs_td_setting">2,060<br />
                                                                3,460<br />
                                                                3,460</td>
                                                            <td class="cs_td_setting">3,130<br />
                                                                4,540<br />
                                                                4,690</td>
                                                            <td class="cs_td_setting">4,130<br />
                                                                5,540<br />
                                                                5,890</td>
                                                            <td class="cs_td_setting">5,130<br />
                                                                6,690<br />
                                                                7,090 </td>
                                                            <td class="cs_td_setting">5,540<br />
                                                                7,290<br />
                                                                7,690</td>
                                                            <td class="cs_td_setting">5,720<br />
                                                                7,490<br />
                                                                7,890</td>
                                                            <td class="cs_td_setting">5,920<br />
                                                                7,690<br />
                                                                8,090</td>
                                                            <td class="cs_td_setting">6,120<br />
                                                                7,890<br />
                                                                8,290</td>
                                                            <td class="cs_td_setting">6,310<br />
                                                                8,080<br />
                                                                8,480</td>
                                                            <td class="cs_td_setting">6,310<br />
                                                                8,080<br />
                                                                9,260 </td>
                                                            <td class="cs_td_setting">6,310<br />
                                                                8,080<br />
                                                                10,060</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$80,000 - 99,999<br />
                                                                $100,000 - 124,999<br />
                                                                $125,000 - 149,999</td>
                                                            <td class="cs_td_setting">2,020<br />
                                                                2,040<br />
                                                                2,040</td>
                                                            <td class="cs_td_setting">3,810<br />
                                                                3,830<br />
                                                                3,830 </td>
                                                            <td class="cs_td_setting">5,090<br />
                                                                5,110<br />
                                                                5,110</td>
                                                            <td class="cs_td_setting">6,290<br />
                                                                6,310<br />
                                                                7,030</td>
                                                            <td class="cs_td_setting">7,490<br />
                                                                7,510<br />
                                                                9,030</td>
                                                            <td class="cs_td_setting">8,090<br />
                                                                8,430<br />
                                                                10,430</td>
                                                            <td class="cs_td_setting">8,290<br />
                                                                9,430<br />
                                                                11,430</td>
                                                            <td class="cs_td_setting">8,490<br />
                                                                10,430<br />
                                                                12,580</td>
                                                            <td class="cs_td_setting">9,470<br />
                                                                11,430<br />
                                                                13,880</td>
                                                            <td class="cs_td_setting">10,460<br />
                                                                12,420<br />
                                                                15,170</td>
                                                            <td class="cs_td_setting">11,260<br />
                                                                13,520<br />
                                                                16,270</td>
                                                            <td class="cs_td_setting">12,060<br />
                                                                14,620<br />
                                                                17,370</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting"> $150,000 - 174,999<br />
                                                                $175,000 - 199,999<br />
                                                                $200,000 - 249,999</td>
                                                            <td class="cs_td_setting">2,360<br />
                                                                2,720<br />
                                                                2,970</td>
                                                            <td class="cs_td_setting">4,950<br />
                                                                5,310<br />
                                                                5,860 </td>
                                                            <td class="cs_td_setting">7,030<br />
                                                                7,540<br />
                                                                8,240</td>
                                                            <td class="cs_td_setting">9,030<br />
                                                                9,840<br />
                                                                10,540</td>
                                                            <td class="cs_td_setting">11,030<br />
                                                                12,140<br />
                                                                12,840</td>
                                                            <td class="cs_td_setting">12,730<br />
                                                                13,840<br />
                                                                14,540</td>
                                                            <td class="cs_td_setting">14,030<br />
                                                                15,140<br />
                                                                15,840</td>
                                                            <td class="cs_td_setting">15,330<br />
                                                                16,440<br />
                                                                17,140</td>
                                                            <td class="cs_td_setting">16,630<br />
                                                                17,740<br />
                                                                18,440</td>
                                                            <td class="cs_td_setting">17,920<br />
                                                                19,030<br />
                                                                19,730</td>
                                                            <td class="cs_td_setting">19,020<br />
                                                                20,130<br />
                                                                20,830</td>
                                                            <td class="cs_td_setting">20,120<br />
                                                                21,230<br />
                                                                21,930</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$250,000 - 399,999<br />
                                                                $400,000 - 449,999<br />
                                                                $450,000 and over</td>
                                                            <td class="cs_td_setting">2,970 <br />
                                                                2,970 <br />
                                                                3,140</td>
                                                            <td class="cs_td_setting">5,860<br />
                                                                5,860<br />
                                                                6,230 </td>
                                                            <td class="cs_td_setting">8,240<br />
                                                                8,240<br />
                                                                8,810</td>
                                                            <td class="cs_td_setting">10,540<br />
                                                                10,540<br />
                                                                11,310</td>
                                                            <td class="cs_td_setting">12,840<br />
                                                                12,840<br />
                                                                13,810</td>
                                                            <td class="cs_td_setting">14,540<br />
                                                                14,540<br />
                                                                15,710</td>
                                                            <td class="cs_td_setting">15,840<br />
                                                                15,840<br />
                                                                17,210</td>
                                                            <td class="cs_td_setting">17,140<br />
                                                                17,140<br />
                                                                18,710</td>
                                                            <td class="cs_td_setting">18,440<br />
                                                                18,450<br />
                                                                20,210</td>
                                                            <td class="cs_td_setting">19,730<br />
                                                                19,940<br />
                                                                21,700</td>
                                                            <td class="cs_td_setting">20,830<br />
                                                                21,240<br />
                                                                23,000</td>
                                                            <td class="cs_td_setting">21,930<br />
                                                                22,540<br />
                                                                24,300</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Head of Household</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div style="overflow-x:auto;">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th rowspan="2" class="cs_th_setting">Higher Paying Job Annual Taxable Wage & Salary</th>
                                                            <th colspan="12" class="text-center">Lower Paying Job Annual Taxable Wage & Salary</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$0 - 9,999</td>
                                                            <td class="cs_td_setting">$10,000 - 19,999</td>
                                                            <td class="cs_td_setting">$20,000 - 29,999</td>
                                                            <td class="cs_td_setting">$30,000 - 39,999</td>
                                                            <td class="cs_td_setting">$40,000 - 49,999</td>
                                                            <td class="cs_td_setting">$50,000 - 59,999</td>
                                                            <td class="cs_td_setting">$60,000 - 69,999</td>
                                                            <td class="cs_td_setting">$70,000 - 79,999</td>
                                                            <td class="cs_td_setting">$80,000 - 89,999</td>
                                                            <td class="cs_td_setting">$90,000 - 99,999</td>
                                                            <td class="cs_td_setting">$100,000 - 109,999</td>
                                                            <td class="cs_td_setting">$110,000 - 120,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$0 - 9,999<br />
                                                                $10,000 - 19,999<br />
                                                                $20,000 - 29,999</td>
                                                            <td class="cs_td_setting">$0<br />
                                                                830<br />
                                                                930 </td>
                                                            <td class="cs_td_setting">$830<br />
                                                                1,920<br />
                                                                2,130 </td>
                                                            <td class="cs_td_setting">$930<br />
                                                                2,130<br />
                                                                2,350 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                2,220<br />
                                                                2,430 </td>
                                                            <td class="cs_td_setting">$1,020<br />
                                                                2,220<br />
                                                                2,900 </td>
                                                            <td class="cs_td_setting">$1,480<br />
                                                                3,680<br />
                                                                4,900 </td>
                                                            <td class="cs_td_setting">$1,870<br />
                                                                4,070<br />
                                                                5,340 </td>
                                                            <td class="cs_td_setting">$1,870<br />
                                                                4,130<br />
                                                                5,540 </td>
                                                            <td class="cs_td_setting">$2,040<br />
                                                                3,830<br />
                                                                5,110</td>
                                                            <td class="cs_td_setting">$1,930<br />
                                                                4,330<br />
                                                                5,740</td>
                                                            <td class="cs_td_setting">$2,040<br />
                                                                4,440 <br />
                                                                5,850 </td>
                                                            <td class="cs_td_setting">$2,040<br />
                                                                4,440<br />
                                                                5,850 </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$30,000 - 39,999<br />
                                                                $40,000 - 59,999<br />
                                                                $60,000 - 79,999</td>
                                                            <td class="cs_td_setting">1,020<br />
                                                                1,020<br />
                                                                1,870</td>
                                                            <td class="cs_td_setting">2,220<br />
                                                                2,530<br />
                                                                4,070</td>
                                                            <td class="cs_td_setting">2,430<br />
                                                                3,750<br />
                                                                5,310</td>
                                                            <td class="cs_td_setting">2,980<br />
                                                                4,830<br />
                                                                6,600</td>
                                                            <td class="cs_td_setting">3,980<br />
                                                                5,860<br />
                                                                7,800 </td>
                                                            <td class="cs_td_setting">4,980<br />
                                                                7,060<br />
                                                                9,000</td>
                                                            <td class="cs_td_setting">6,040<br />
                                                                8,260<br />
                                                                10,200</td>
                                                            <td class="cs_td_setting">6,630<br />
                                                                8,850<br />
                                                                10,780</td>
                                                            <td class="cs_td_setting">6,830<br />
                                                                9,050<br />
                                                                10,980</td>
                                                            <td class="cs_td_setting">7,030<br />
                                                                9,250<br />
                                                                11,180</td>
                                                            <td class="cs_td_setting">7,140<br />
                                                                9,360<br />
                                                                11,580 </td>
                                                            <td class="cs_td_setting">7,140<br />
                                                                9,360<br />
                                                                12,380</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$80,000 - 99,999<br />
                                                                $100,000 - 124,999<br />
                                                                $125,000 - 149,999</td>
                                                            <td class="cs_td_setting">1,900<br />
                                                                2,040<br />
                                                                2,040</td>
                                                            <td class="cs_td_setting">4,300<br />
                                                                4,440<br />
                                                                4,440 </td>
                                                            <td class="cs_td_setting">5,710<br />
                                                                5,850<br />
                                                                5,850</td>
                                                            <td class="cs_td_setting">7,000<br />
                                                                7,140<br />
                                                                7,360</td>
                                                            <td class="cs_td_setting">8,200<br />
                                                                8,340<br />
                                                                9,360</td>
                                                            <td class="cs_td_setting">9,400<br />
                                                                9,540<br />
                                                                11,360</td>
                                                            <td class="cs_td_setting">10,600<br />
                                                                11,360<br />
                                                                13,360</td>
                                                            <td class="cs_td_setting">11,180<br />
                                                                12,750<br />
                                                                14,750</td>
                                                            <td class="cs_td_setting">11,670<br />
                                                                13,750<br />
                                                                16,010</td>
                                                            <td class="cs_td_setting">12,670<br />
                                                                14,750<br />
                                                                17,310</td>
                                                            <td class="cs_td_setting">13,580<br />
                                                                15,770<br />
                                                                18,520</td>
                                                            <td class="cs_td_setting">14,380<br />
                                                                16,870<br />
                                                                19,620</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting"> $150,000 - 174,999<br />
                                                                $175,000 - 199,999<br />
                                                                $200,000 - 249,999</td>
                                                            <td class="cs_td_setting">2,040<br />
                                                                2,720<br />
                                                                2,970</td>
                                                            <td class="cs_td_setting">5,060<br />
                                                                5,920<br />
                                                                6,470 </td>
                                                            <td class="cs_td_setting">7,280<br />
                                                                8,130<br />
                                                                8,990</td>
                                                            <td class="cs_td_setting">9,360<br />
                                                                10,480<br />
                                                                11,370</td>
                                                            <td class="cs_td_setting">11,360<br />
                                                                12,780<br />
                                                                13,670</td>
                                                            <td class="cs_td_setting">13,480<br />
                                                                15,080<br />
                                                                15,970</td>
                                                            <td class="cs_td_setting">15,780<br />
                                                                17,380<br />
                                                                18,270</td>
                                                            <td class="cs_td_setting">17,460<br />
                                                                19,070<br />
                                                                19,960</td>
                                                            <td class="cs_td_setting">18,760<br />
                                                                20,370<br />
                                                                21,260</td>
                                                            <td class="cs_td_setting">20,060<br />
                                                                21,670<br />
                                                                22,560</td>
                                                            <td class="cs_td_setting">21,270<br />
                                                                22,880<br />
                                                                23,770</td>
                                                            <td class="cs_td_setting">22,370<br />
                                                                23,980<br />
                                                                24,870</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="cs_td_setting">$250,000 - 399,999<br />
                                                                $400,000 - 449,999<br />
                                                                $450,000 and over</td>
                                                            <td class="cs_td_setting">2,970 <br />
                                                                2,970 <br />
                                                                3,140</td>
                                                            <td class="cs_td_setting">6,470<br />
                                                                6,470<br />
                                                                6,840 </td>
                                                            <td class="cs_td_setting">8,990<br />
                                                                8,990<br />
                                                                9,560</td>
                                                            <td class="cs_td_setting">11,370<br />
                                                                11,370<br />
                                                                12,140</td>
                                                            <td class="cs_td_setting">13,670<br />
                                                                13,670<br />
                                                                14,640</td>
                                                            <td class="cs_td_setting">15,970<br />
                                                                15,970<br />
                                                                17,140</td>
                                                            <td class="cs_td_setting">18,270<br />
                                                                18,270<br />
                                                                19,640</td>
                                                            <td class="cs_td_setting">19,960<br />
                                                                19,960<br />
                                                                21,530</td>
                                                            <td class="cs_td_setting">21,260<br />
                                                                21,260<br />
                                                                23,030</td>
                                                            <td class="cs_td_setting">22,560<br />
                                                                22,560<br />
                                                                24,530</td>
                                                            <td class="cs_td_setting">23,770<br />
                                                                23,770<br />
                                                                25,940</td>
                                                            <td class="cs_td_setting">24,870<br />
                                                                25,200<br />
                                                                27,240</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php //if(!empty($e_signature_data)) { 
                            ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
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

                                <input type="hidden" id="active_signature" name="active_signature" value="<?php echo isset($active_signature) ? $active_signature : ''; ?>" />

                                <input type="hidden" id="signature" name="signature" value="<?php echo isset($signature) ? $signature : ''; ?>" />

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
                                        <?php $consent = isset($e_signature_data['user_consent']) ? $e_signature_data['user_consent'] : 0; ?>
                                        <label class="control control--checkbox">
                                            <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                            <input <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo !empty($pre_form) && $pre_form['user_consent'] == 1 ? 'disabled="disabled"' : '' ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <hr />

                                <?php if ($pre_form['user_consent'] == 0) { ?>
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <button <?php //echo $signed_flag == true ? 'disabled="disabled"' : ''; 
                                                    ?> onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text" <?php echo !empty($pre_form) && $pre_form['user_consent'] == 1 ? 'disabled="disabled"' : '' ?>><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php //} 
                            ?>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div id="w4_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="review_modal_title">Submitted W4 Form</h4>
            </div>
            <div id="review_modal_body" class="modal-body">
                <?php $form_values['pre_form'] = $pre_form;
                $form_values['pre_form']['dated'] = !empty($pre_form['signature_timestamp']) ? DateTime::createFromFormat('Y-m-d H:i:s', $pre_form['signature_timestamp'])->format('M d Y') : '';
                //  $view = $this->load->view('form_w4/index-pdf', $form_values, TRUE);
                $view = $this->load->view('form_w4/preview_w4_2023', $form_values, TRUE);

                echo $view; ?>
            </div>
            <div id="review_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('static-pages/e_signature_popup'); ?>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript">
    $("#w4-form").validate({
        ignore: ":hidden:not(select)",
        rules: {
            w4_first_name: {
                required: true
            },
            user_consent: {
                required: true
            },
            number_of_allowance: {
                required: true
            }
        },
        messages: {
            w4_first_name: {
                required: 'First Name is required'
            },
            user_consent: {
                required: 'Please check to accept the consent'
            },
            number_of_allowance: {
                required: 'Number of Allowance is required'
            }
        }

    });

    $(document).ready(function() {
        $('input[name=w4_federaltax_classification]').on('change', function() {
            $('input[name=w4_federaltax_classification]').not(this).prop('checked', false);
        });

        <?php if ($signed_flag == true) { ?>
            //        $('input[type=checkbox]:not(:checked)').prop('disabled', true);
        <?php } ?>

        $('.sign_date').datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            maxDate: new Date,
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });

        $('.date_picker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();
    });

    function func_save_e_signature() {

        var is_signature_exist = $('#signature_bas64_image').val();
        if (is_signature_exist == "") {
            alertify.error('Please Add Your Signature!');
            return false;
        }

        if ($('#w4-form').validate()) {
            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                function() {
                    $('#w4-form').submit();
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