<?php
$company_name = ucwords($session['company_detail']['CompanyName']);
?>
<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 cs-padding-zero">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-group col-xs-12 col-sm-1 col-md-1 col-lg-1 cs-btn-setting">
                    <a href="<?= base_url('employee_management_system'); ?>" class="btn btn-info csRadius5">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard
                    </a>
                </div>

                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4 cs-btn-setting">
                    <a href="<?php echo base_url('hr_documents_management/my_documents'); ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i> Documents</a>
                </div>
                <?php if (checkIfAppIsEnabled('documentlibrary')) : ?>
                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <a href="<?php echo base_url('library_document'); ?>" class="btn btn-block blue-button"><i class="fa fa-angle-left"></i> Document Library</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                <div class="page-header">
                    <h2 class="section-ttile">Form W-4 (<?php echo date('Y'); ?>)</h2>
                    <div class="row mb-2">
                        <?php if ($pre_form['uploaded_file'] != NULL) { ?>
                            <div class="col-lg-7 cs-btn-setting"></div>
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
                                <a download="W4 Submitted Form" href="<?php echo base_url('hr_documents_management/download_upload_document') . '/' . $pre_form['uploaded_file']; ?>" class="btn blue-button btn-block">Download Submitted Form</a>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-6 cs-btn-setting"></div>
                            <div class="col-lg-2 cs-btn-setting">
                                <a target="_blank" href="<?php echo base_url('form_w4/download_w4_form_2020' . '/' . $pre_form['user_type'] . '/' . $pre_form['employer_sid']); ?>" class="btn blue-button btn-block ">
                                    Download PDF
                                </a>
                            </div>
                            <div class="col-lg-2 cs-btn-setting">
                                <a target="_blank" href="<?php echo base_url('form_w4/print_w4_form_2020' . '/' . $pre_form['user_type'] . '/' . $pre_form['employer_sid']); ?>" class="btn blue-button btn-block ">
                                    Print PDF
                                </a>
                            </div>
                            <div class="col-lg-2 cs-btn-setting">
                                <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);" class="btn blue-button btn-block ">Preview PDF</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if (!empty($pre_form['uploaded_file']) && $pre_form['uploaded_file'] != NULL) { ?>
                    <div class="form-wrp">
                        <iframe src="<?= AWS_S3_BUCKET_URL . $pre_form['uploaded_file']; ?>?embedded=true" style="width: 100%; height: 80rem;"></iframe>
                    </div>
                <?php } else { ?>
                    <div class="form-wrp">
                        <div class="col-lg-2 cs-full-width">
                            <strong>Form W-4</strong>
                            <p>Department of the Treasury Internal Revenue Service</p>
                        </div>
                        <div class="col-lg-8 text-center cs-full-width">
                            <h2 style="margin-top: 0;">Employee’s Withholding Certificate</h2>
                            <p>▶ Complete Form W-4 so that your employer can withhold the correct federal income tax from your pay.</p>
                            <p>▶ Give Form W-4 to your employer</p>
                            <p>▶ Your withholding is subject to review by the IRS.</p>
                        </div>
                        <div class="col-lg-2 text-center cs-full-width">
                            <p>OMB No. 1545-0074</p>
                            <strong><?php echo date('Y'); ?></strong>
                        </div>
                        <form id="w4-form" action="" method="post">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b> Step 1:Enter Personal Information</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                            <div class="form-group">
                                                <label>1. Your first name <span class="staric">*</span></label>
                                                <input type="text" value="<?php echo !empty($pre_form) ? $pre_form['first_name'] : '' ?>" name="w4_first_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                            <div class="form-group">
                                                <label>Your middle initial</label>
                                                <input type="text" value="<?php echo !empty($pre_form) ? $pre_form['middle_name'] : '' ?>" name="w4_middle_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                            <div class="form-group">
                                                <label>Your Last name</label>
                                                <input type="text" value="<?php echo !empty($pre_form) ? $pre_form['last_name'] : '' ?>" name="w4_last_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                            <div class="form-group">
                                                <label>Social security number</label>
                                                <input type="text" value="<?php echo !empty($pre_form) ? $pre_form['ss_number'] : '' ?>" name="ss_number" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" value="<?php echo !empty($pre_form) ? $pre_form['home_address'] : '' ?>" name="home_address" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <div class="form-group">
                                                <label>City or town</label>
                                                <input type="text" value="<?php echo !empty($pre_form) ? $pre_form['city'] : '' ?>" name="city" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                            <div class="form-group">
                                                <label>State</label>
                                                <input type="text" value="<?php echo !empty($pre_form) ? $pre_form['state'] : '' ?>" name="state" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                            <div class="form-group">
                                                <label>ZIP Code</label>
                                                <input type="text" value="<?php echo !empty($pre_form) ? $pre_form['zip'] : '' ?>" name="zip" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <div class="form-group">
                                                <label>▶ Does your name match the name on your social security card? If not, to ensure you get credit for your earnings, contact SSA at 800-772-1213 or go to www.ssa.gov.</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <div class="form-group">
                                                <label class="control control--checkbox">
                                                    Single or Married filing separately
                                                    <input type="radio" name="marriage_status" value="separately" <?php echo !empty($pre_form) && $pre_form['marriage_status'] == 'separately' ? 'checked="checked"' : '' ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <div class="form-group">
                                                <label class="control control--checkbox">
                                                    Married filing jointly or Qualifying surviving spouse
                                                    <input type="radio" name="marriage_status" value="jointly" <?php echo !empty($pre_form) && $pre_form['marriage_status'] == 'jointly' ? 'checked="checked"' : '' ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
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
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div>


                                <strong>Tip: </strong>Consider using the estimator at www.irs.gov/W4App to determine the most accurate withholding for the rest of the year if: you
                                    are completing this form after the beginning of the year; expect to work only part of the year; or have changes during the year in your
                                    marital status, number of jobs for you (and/or your spouse if married filing jointly), dependents, other income (not from jobs),
                                    deductions, or credits. Have your most recent pay stub(s) from this year available when using the estimator. At the beginning of next
                                    year, use the estimator again to recheck your withholding. <br><br>

                                    <p><b>Complete Steps 2–4 ONLY if they apply to you; otherwise, skip to Step 5.</b> See page 2 for more information on each step, who can
                                        claim exemption from withholding, and when to use the estimator at www.irs.gov/W4App.</p>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 2:Multiple Jobs or Spouse Works</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <p>Complete this step if you (1) hold more than one job at a time, or (2) are married filing jointly and your spouse
                                                also works. The correct amount of withholding depends on income earned from all of these jobs.</p>
                                            <p>Do <b>only one</b> of the following.</p>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group">
                                                    <label class="control control--checkbox">
                                                        (a) Use the estimator at www.irs.gov/W4App for most accurate withholding for this step (and Steps 3–4). If you
                                                        or your spouse have self-employment income, use this option or</b>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group">
                                                    <label class="control control--checkbox">
                                                        (b) Use the Multiple Jobs Worksheet on page 3 and enter the result in Step 4(c) below or
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group">
                                                    <label class="control control--checkbox">
                                                        (c) If there are only two jobs total, you may check this box. Do the same on Form W-4 for the other job. This
                                                        option is generally more accurate than (b) if pay at the lower paying job is more than half of the pay at the
                                                        higher paying job. Otherwise, (b) is more accurate
                                                        <input type="checkbox" name="mjsw_status" value="similar_pay" <?php echo !empty($pre_form) && isset($pre_form['mjsw_status']) && $pre_form['mjsw_status'] == 'similar_pay' ? 'checked="checked"' : '' ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <p><b>Complete Steps 3–4(b) on Form W-4 for only ONE of these jobs. </b>Leave those steps blank for the other jobs. (Your withholding will be most accurate if you complete Steps 3–4(b) on the Form W-4 for the highest paying job.)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 3:Claim Dependents</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
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
                                                <p>Add the amounts above for qualifying children and other dependents. You may add to
                                                    this the amount of any other credits. Enter the total here </p>
                                                <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['claim_total_amount']) ? $pre_form['claim_total_amount'] : '' ?>" name="claim_total_amount" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 4 (optional) :Other Adjustments</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
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
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 5:Sign Here</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <p>Under penalties of perjury, I declare that this certificate, to the best of my knowledge and belief, is true, correct, and complete.</p>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                                <span><b>Employee's signature</b> (This form is not valid unless you sign it.)</span>
                                                <?php if ($signed_flag == true) { ?>
                                                    <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?php echo $pre_form['signature_bas64_image']; ?>" />
                                                <?php } else { ?>
                                                    <!-- the below loaded view add e-signature -->
                                                    <?php $this->load->view('static-pages/e_signature_button'); ?>
                                                <?php } ?>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                                <div class="row form-group">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 cs-full-width">
                                                        <label>Date</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 cs-full-width">
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
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 2(b)—Multiple Jobs Worksheet (Keep for your records.)</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <p>If you choose the option in Step 2(b) on Form W-4, complete this worksheet (which calculates the total extra tax for all jobs) on<b> only ONE</b> Form W-4. Withholding will be most accurate if you complete the worksheet and enter the result on the Form W-4 for the highest paying job. To be accurate, submit a new Form W-4 for all other jobs if you have not updated your withholding since 2019.</p>
                                            <p><b>Note:</b>If more than one job has annual wages of more than $120,000 or there are more than three jobs, see Pub. 505 for additional tables; or, you can use the online withholding estimator at www.irs.gov/W4App.</p>
                                            <div>
                                                <div>
                                                    <div class="form-group">
                                                        <p> <b>1&nbsp; Two jobs.</b> If you have two jobs or you’re married filing jointly and you and your spouse each have one
                                                            job, find the amount from the appropriate table on page 4. Using the “Higher Paying Job” row and the
                                                            “Lower Paying Job” column, find the value at the intersection of the two household salaries and enter
                                                            that value on line 1. Then, skip to line 3 </p>
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
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Step 4(b)—Deductions Worksheet (Keep for your records.)</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>1. Enter an estimate of your 2024 itemized deductions (from Schedule A (Form 1040)). Such deductions may include qualifying home mortgage interest, charitable contributions, state and local taxes (up to $10,000), and medical expenses in excess of 7.5% of your income</label>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_1']) ? $pre_form['dw_input_1'] : '' ?>" name="dw_input_1" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>2. Enter: </label>
                                                    <p>
                                                        &nbsp;&nbsp;• $30,000 if you’re married filing jointly or a qualifying surviving spouse<br>
                                                        &nbsp;&nbsp;• $22,500 if you’re head of household.<br>
                                                        &nbsp;&nbsp;• $15,000 if you’re single or married filing separately.
                                                    </p>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_2']) ? $pre_form['dw_input_2'] : '' ?>" name="dw_input_2" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>3. If line 1 is greater than line 2, subtract line 2 from line 1 and enter the result here. If line 2 is greater
                                                        than line 1, enter “-0-” .. </label>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_3']) ? $pre_form['dw_input_3'] : '' ?>" name="dw_input_3" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>4. Enter an estimate of your <?php echo date('Y'); ?> adjustments to income and any additional standard deduction for age or blindness (see Pub. 505 for information) </label>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_4']) ? $pre_form['dw_input_4'] : '' ?>" name="dw_input_4" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>5. Add lines 3 and 4. Enter the result here and in Step 4(b) of Form W-4</label>
                                                    <input type="text" value="<?php echo !empty($pre_form) && isset($pre_form['dw_input_5']) ? $pre_form['dw_input_5'] : '' ?>" name="dw_input_5" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                    <p><strong>Privacy Act and Paperwork Reduction Act Notice. </strong> We ask for the information
                                        on this form to carry out the Internal Revenue laws of the United States. Internal
                                        Revenue Code sections 3402(f)(2) and 6109 and their regulations require you to
                                        provide this information; your employer uses it to determine your federal income
                                        tax withholding. Failure to provide a properly completed form will result in your
                                        being treated as a single person with no other entries on the form; providing
                                        fraudulent information may subject you to penalties. Routine uses of this
                                        information include giving it to the Department of Justice for civil and criminal
                                        litigation; to cities, states, the District of Columbia, and U.S. commonwealths and
                                        territories for use in administering their tax laws; and to the Department of Health
                                        and Human Services for use in the National Directory of New Hires. We may also
                                        disclose this information to other countries under a tax treaty, to federal and state
                                        agencies to enforce federal nontax criminal laws, or to federal law enforcement
                                        and intelligence agencies to combat terrorism.</p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
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
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">



                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <b>Married Filing Jointly or Qualifying Surviving Spouse</b>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                            <div style="overflow-x:auto;">


                                                <table style="border-collapse:collapse;margin-left:5.5pt; width: 100%;" cellspacing="0">

                                                    <tbody>
                                                        <tr>
                                                            <td colspan="4" class="text-center border-bottom">
                                                                <table class="table table-bordered">
                                                                    <tr style="height:12pt">
                                                                        <td class="cs_td_setting" colspan="14">
                                                                            <p class="s29" style="text-indent: 0pt;text-align: center;">Lower Paying Job Annual Taxable Wage &amp;
                                                                                Salary</p>
                                                                        </td>
                                                                    </tr>

                                                                    <tr style="height:23pt">
                                                                        <td class="cs_td_setting">
                                                                            <strong> Higher
                                                                                Paying Job Annual
                                                                                Taxable Wage &amp; Salary</strong>
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $0 - 9,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $10,000 - 19,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $20,000 - 29,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $30,000 - 39,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $40,000 - 49,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $50,000 - 59,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $60,000 - 69,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $70,000 - 79,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $80,000 - 89,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $90,000 - 99,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $100,000 - 109,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $110,000 - 120,000
                                                                        </td>
                                                                    </tr>
                                                                    <tr style="height:12pt">
                                                                        <td class="cs_td_setting">
                                                                            $0 - 9,999 <br>
                                                                            $10,000 -19,999 <br>
                                                                            $20,000 - 29,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $0 <br>
                                                                            0<br>
                                                                            780
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $0<br>
                                                                            700<br>
                                                                            1,700
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $700<br>
                                                                            1,700<br>
                                                                            2,760
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $850<br>
                                                                            1,910<br>
                                                                            3,110
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $910<br>
                                                                            2,110<br>
                                                                            3,310
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $1,020<br>
                                                                            2,220<br>
                                                                            3,420
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $1,020<br>
                                                                            2,220<br>
                                                                            3,420

                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $1,020<br>
                                                                            2,220<br>
                                                                            3,420
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $1,020<br>
                                                                            2,220<br>
                                                                            3,420
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $1,020<br>
                                                                            2,220<br>
                                                                            3,420
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $1,020<br>
                                                                            2,220<br>
                                                                            4,420
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            $1,020<br>
                                                                            3,220<br>
                                                                            5,420

                                                                        </td>
                                                                    </tr>

                                                                    <tr style="height:12pt">
                                                                        <td class="cs_td_setting">
                                                                            $30,000 - 39,999<br>
                                                                            $40,000 - 49,999<br>
                                                                            $50,000 - 59,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            850<br>
                                                                            910<br>
                                                                            1,020
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            1,910<br>
                                                                            2,110<br>
                                                                            2,220
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            3,110<br>
                                                                            3,310<br>
                                                                            3,420
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            3,460<br>
                                                                            3,660<br>
                                                                            3,770
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            3,660<br>
                                                                            3,860<br>
                                                                            3,970
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            3,770<br>
                                                                            3,970<br>
                                                                            4,080
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            3,770<br>
                                                                            3,970<br>
                                                                            4,080
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            3,770<br>
                                                                            3,970<br>
                                                                            5,080
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            37,70<br>
                                                                            4,970<br>
                                                                            6,080
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            4,770<br>
                                                                            5,970<br>
                                                                            7,080
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            5,770<br>
                                                                            6,970<br>
                                                                            8,080
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            6,770<br>
                                                                            7,970<br>
                                                                            9,080
                                                                        </td>
                                                                    </tr>

                                                                    <tr style="height:12pt">
                                                                        <td class="cs_td_setting">
                                                                            $60,000 - 69,999<br>
                                                                            $70,000 - 79,999<br>
                                                                            $80,000 - 99,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            1,020<br>
                                                                            1,020<br>
                                                                            1,020
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            2,220<br>
                                                                            2,220<br>
                                                                            2,220
                                                                        </td>

                                                                        <td class="cs_td_setting">
                                                                            3,420<br>
                                                                            3,420<br>
                                                                            3,420
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            3,770<br>
                                                                            3,770<br>
                                                                            4,620
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            3,970<br>
                                                                            3,970<br>
                                                                            5,820
                                                                        </td>

                                                                        <td class="cs_td_setting">
                                                                            4,080<br>
                                                                            5,080<br>
                                                                            6,930
                                                                        </td>

                                                                        <td class="cs_td_setting">
                                                                            5,080<br>
                                                                            6,080<br>
                                                                            7,930
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            6,080<br>
                                                                            7,080<br>
                                                                            8,930
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            7,080<br>
                                                                            8,080<br>
                                                                            9,930
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            8,080<br>
                                                                            9,080<br>
                                                                            10,930
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            9,080<br>
                                                                            10,080<br>
                                                                            11,930
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            10,080<br>
                                                                            11,080<br>
                                                                            12,930
                                                                        </td>
                                                                    </tr>


                                                                    <tr style="height:12pt">
                                                                        <td class="cs_td_setting">
                                                                            $100,000 - 149,999<br>
                                                                            $150,000 - 239,999<br>
                                                                            $240,000 - 259,999
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            1,870<br>
                                                                            1,870<br>
                                                                            2,040
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            4,070<br>
                                                                            4,240<br>
                                                                            4,440
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            6,270<br>
                                                                            6,640<br>
                                                                            6,840
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            7,620<br>
                                                                            8,190<br>
                                                                            8,390
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            8,820<br>
                                                                            9,590<br>
                                                                            9,790
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            9,930<br>
                                                                            10,890<br>
                                                                            11,100
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            10,930<br>
                                                                            12,090<br>
                                                                            12,300
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            11,930<br>
                                                                            13,290<br>
                                                                            13,500
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            12,930<br>
                                                                            14,490<br>
                                                                            14,700
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            14,010<br>
                                                                            15,690<br>
                                                                            15,900
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            15,210<br>
                                                                            16,890<br>
                                                                            17,100
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            16,410<br>
                                                                            18,090<br>
                                                                            18,300
                                                                        </td>
                                                                    </tr>


                                                                    <tr style="height:12pt">
                                                                        <td class="cs_td_setting">
                                                                            $260,000 - 279,999<br>
                                                                            $280,000 - 299,999<br>
                                                                            $300,000 - 319,999

                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            2,040<br>
                                                                            2,040<br>
                                                                            2,040
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            4,440<br>
                                                                            4,440<br>
                                                                            4,440
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            6,840<br>
                                                                            6,840<br>
                                                                            6,840
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            8,390<br>
                                                                            8,390<br>
                                                                            8,390
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            9,790<br>
                                                                            9,790<br>
                                                                            9,790
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            11,100<br>
                                                                            11,100<br>
                                                                            11,100
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            12,300<br>
                                                                            12,300<br>
                                                                            12,300
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            13,500<br>
                                                                            13,500<br>
                                                                            13,500
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            14,700<br>
                                                                            14,700<br>
                                                                            14,700
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            15,900<br>
                                                                            15,900<br>
                                                                            15,900
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            17,100<br>
                                                                            17,100<br>
                                                                            17,100
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            18,300<br>
                                                                            18,380<br>
                                                                            19,170
                                                                        </td>
                                                                    </tr>


                                                                    <tr style="height:12pt">
                                                                        <td class="cs_td_setting">
                                                                            $320,000 - 364,999<br>
                                                                            $365,000 - 524,999<br>
                                                                            $525,000 and over
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            2,040<br>
                                                                            2,790<br>
                                                                            3,140
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            4,440<br>
                                                                            6,290<br>
                                                                            6,840
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            6,840<br>
                                                                            9,790<br>
                                                                            10,540
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            8,390<br>
                                                                            12,440<br>
                                                                            13,390
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            9,790<br>
                                                                            14,940<br>
                                                                            16,090
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            11,100<br>
                                                                            17,350<br>
                                                                            18,700
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            12,470<br>
                                                                            19,650<br>
                                                                            21,200
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            14,470<br>
                                                                            21,950<br>
                                                                            23,700
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            16,470<br>
                                                                            24,250<br>
                                                                            26,200
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            18,470<br>
                                                                            26,550<br>
                                                                            28,700
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            20,470<br>
                                                                            28,850<br>
                                                                            31,200
                                                                        </td>
                                                                        <td class="cs_td_setting">
                                                                            22,470<br>
                                                                            31,150<br>
                                                                            33,700
                                                                        </td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                    </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                <div class="panel panel-blue">
                    <div class="panel-heading incident-panal-heading">
                        <b>Single or Married Filing Separately</b>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                            <div style="overflow-x:auto;">

                                <table style="border-collapse:collapse;margin-left:5.5pt; width: 100%;" cellspacing="0">

                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center border-bottom">
                                                <table class="table table-bordered">
                                                    <tr>

                                                        <td class="cs_td_setting" colspan="14">
                                                            <p class="s29" style="text-indent: 0pt;text-align: center;">Lower Paying Job Annual Taxable Wage &amp;
                                                                Salary</p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            <strong> Higher
                                                                Paying Job Annual
                                                                Taxable Wage &amp; Salary</strong>
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $0 - 9,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $10,000 - 19,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $20,000 - 29,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $30,000 - 39,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $40,000 - 49,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $50,000 - 59,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $60,000 - 69,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $70,000 - 79,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $80,000 - 89,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $90,000 - 99,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $100,000 - 109,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $110,000 - 120,000
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $0 - 9,999<br>
                                                            $10,000 - 19,999<br>
                                                            $20,000 - 29,999
                                                        </td>

                                                        <td class="cs_td_setting">
                                                            $200<br>
                                                            850<br>
                                                            1,020 </td>
                                                        <td class="cs_td_setting">
                                                            $850<br>
                                                            1,700<br>
                                                            1,870
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,020<br>
                                                            1,870<br>
                                                            2,040
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,020<br>
                                                            1,870<br>
                                                            2,390
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,020<br>
                                                            2,220<br>
                                                            3,390
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,370<br>
                                                            3,220<br>
                                                            4,390
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,870<br>
                                                            3,720<br>
                                                            4,890
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,870<br>
                                                            3,720<br>
                                                            4,890
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,870<br>
                                                            3,720<br>
                                                            4,890 </td>
                                                        <td class="cs_td_setting">
                                                            $1,870<br>
                                                            3,720<br>
                                                            5,060
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,870<br>
                                                            3,890<br>
                                                            5,260
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $2,040<br>
                                                            4,090<br>
                                                            5,460
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $30,000 - 39,999<br>
                                                            $40,000 - 59,999<br>
                                                            $60,000 - 79,999
                                                        </td>

                                                        <td class="cs_td_setting">
                                                            1,020<br>
                                                            1,220<br>
                                                            1,870
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            1,870<br>
                                                            3,070<br>
                                                            3,720
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            2,390<br>
                                                            4,240<br>
                                                            4,890
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            3,390<br>
                                                            5,240<br>
                                                            5,890
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            4,390<br>
                                                            6,240<br>
                                                            7,030
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            5,390<br>
                                                            7,240<br>
                                                            8,230
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            5,890<br>
                                                            7,880<br>
                                                            8,930
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            5,890<br>
                                                            8,080<br>
                                                            9,130
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,060<br>
                                                            8,280<br>
                                                            9,330
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,260<br>
                                                            8,480<br>
                                                            9,530
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,460<br>
                                                            8,680<br>
                                                            9,730
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,660<br>
                                                            8,880<br>
                                                            9,930
                                                        </td>
                                                    </tr>


                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $80,000 - 99,999<br>
                                                            $100,000 - 124,999<br>
                                                            $125,000 - 149,999
                                                        </td>

                                                        <td class="cs_td_setting">
                                                            1,870<br>
                                                            2,040<br>
                                                            2,040
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            3,720<br>
                                                            4,090<br>
                                                            4,090
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            5,030<br>
                                                            5,460<br>
                                                            5,460
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,230<br>
                                                            6,660<br>
                                                            6,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            7,430<br>
                                                            7,860<br>
                                                            7,860
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            8,630<br>
                                                            9,060<br>
                                                            9,060
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            9,330<br>
                                                            9,760<br>
                                                            9,950
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            9,530<br>
                                                            9,960<br>
                                                            10,950
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            9,730<br>
                                                            10,160<br>
                                                            11,950
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            9,930<br>
                                                            10,950<br>
                                                            12,950
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            10,130<br>
                                                            11,950<br>
                                                            13,950
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            10,580<br>
                                                            12,950<br>
                                                            14,950
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $150,000 - 174,999<br>
                                                            $175,000 - 199,999<br>
                                                            $200,000 - 249,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            2,040<br>
                                                            2,040<br>
                                                            2,720
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            4,090<br>
                                                            4,290<br>
                                                            5,570
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            5,460<br>
                                                            6,450<br>
                                                            7,900
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,660<br>
                                                            8,450<br>
                                                            10,200
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            8,450<br>
                                                            10,450<br>
                                                            12,500
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            10,450<br>
                                                            12,450<br>
                                                            14,800
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            11,950<br>
                                                            13,950<br>
                                                            16,600
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            12,950<br>
                                                            15,230<br>
                                                            17,900
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            13,950<br>
                                                            16,530<br>
                                                            19,200
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            15,080<br>
                                                            17,830<br>
                                                            20,500
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            16,380<br>
                                                            19,130<br>
                                                            21,800
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            17,680<br>
                                                            20,430<br>
                                                            23,100
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $250,000 - 399,999<br>
                                                            $400,000 - 449,999<br>
                                                            $450,000 and over
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            2,970<br>
                                                            2,970<br>
                                                            3,140
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,120<br>
                                                            6,120<br>
                                                            6,490
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            8,590<br>
                                                            8,590<br>
                                                            9,160
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            10,890<br>
                                                            10,890<br>
                                                            11,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            13,190<br>
                                                            13,190<br>
                                                            14,160
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            15,490<br>
                                                            15,490<br>
                                                            16,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            17,290<br>
                                                            17,290<br>
                                                            18,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            18,590<br>
                                                            18,590<br>
                                                            20,160
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            19,890<br>
                                                            19,890<br>
                                                            21,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            21,190<br>
                                                            21,190<br>
                                                            23,160
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            22,490<br>
                                                            22,490<br>
                                                            24,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            23,790<br>
                                                            23,790<br>
                                                            26,160
                                                        </td>
                                                    </tr>

                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>
                        </div>
                    </div>
                </div>
            </div>






            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                <div class="panel panel-blue">
                    <div class="panel-heading incident-panal-heading">
                        <b>Head of Household</b>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                            <div style="overflow-x:auto;">
                                <table style="border-collapse:collapse;margin-left:5.5pt; width: 100%;" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <table class="table table-bordered">

                                                    <tr>

                                                        <td class="cs_td_setting" colspan="14">
                                                            <p class="s29" style="text-indent: 0pt;text-align: center;"><strong>Lower Paying Job Annual Taxable Wage &amp;
                                                                    Salary</strong></p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            <p class="s29" style="padding-left: 5pt;text-indent: 0pt;line-height: 9pt;text-align: left;"> <strong> Higher
                                                                    Paying Job Annual
                                                                    Taxable Wage &amp; Salary</strong></p>
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $0 - 9,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $10,000 - 19,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $20,000 - 29,999
                                                        </td>
                                                        <td sclass="cs_td_setting">
                                                            $30,000 - 39,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $40,000 - 49,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $50,000 -
                                                            59,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $60,000 -
                                                            69,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $70,000 -
                                                            79,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $80,000 -
                                                            89,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $90,000 -
                                                            99,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $100,000 -
                                                            109,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $110,000 -
                                                            120,000
                                                        </td>
                                                    </tr>


                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $0 - 9,999 <br>
                                                            $10,000 - 19,999 <br>
                                                            $20,000 - 29,999

                                                        </td>

                                                        <td class="cs_td_setting">
                                                            $0 <br>
                                                            450 <br>
                                                            850
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $410 <br>
                                                            1,450 <br>
                                                            2,000
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $850 <br>
                                                            2,000<br>
                                                            2,600
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,000<br>
                                                            2,200<br>
                                                            2,800
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,020 <br>
                                                            2,220 <br>
                                                            2,820
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,020 <br>
                                                            2,220 <br>
                                                            2,820
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,020<br>
                                                            2,220<br>
                                                            3,780
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,020<br>
                                                            3,180<br>
                                                            4,780
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,870 <br>
                                                            4,070<br>
                                                            5,670
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,870<br>
                                                            4,070<br>
                                                            5,690
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,870 <br>
                                                            4,090<br>
                                                            5,890
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            $1,890<br>
                                                            4,290<br>
                                                            6,090
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $30,000 - 39,999 <br>
                                                            $40,000 - 59,999<br>
                                                            $60,000 - 79,999
                                                        </td>

                                                        <td class="cs_td_setting">
                                                            1,020 <br>
                                                            1,020<br>
                                                            1,020
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            2,200 <br>
                                                            2,220<br>
                                                            3,030

                                                        </td>
                                                        <td class="cs_td_setting">
                                                            2,800 <br>
                                                            2,820<br>
                                                            4,630
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            3,000<br>
                                                            3,830<br>
                                                            5,830
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            3,020<br>
                                                            4,850<br>
                                                            6,850
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            3,980<br>
                                                            5,850<br>
                                                            8,050
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            4,980<br>
                                                            6,850<br>
                                                            9,250
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            5,980<br>
                                                            8,050<br>
                                                            10,450
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,890<br>
                                                            9,130<br>
                                                            11,530
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            7,090<br>
                                                            9,330<br>
                                                            11,730
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            7,290<br>
                                                            9,530<br>
                                                            11,930
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            7,490 <br>
                                                            9,730<br>
                                                            12,130
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $80,000 -99,999<br>
                                                            $100,000 - 124,999<br>
                                                            $125,000 - 149,999
                                                        </td>

                                                        <td class="cs_td_setting">
                                                            1,870<br>
                                                            1,950<br>
                                                            2,040
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            4,070<br>
                                                            4,350<br>
                                                            4,440
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            5,670<br>
                                                            6,150<br>
                                                            6,240
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            7,060<br>
                                                            7,550<br>
                                                            7,640
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            8,280<br>
                                                            8,770<br>
                                                            8,860
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            9,480<br>
                                                            9,970<br>
                                                            10,060
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            10,680<br>
                                                            11,170<br>
                                                            11,260
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            11,880<br>
                                                            12,370<br>
                                                            13,860
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            12,970<br>
                                                            13,450<br>
                                                            14,770
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            13,170<br>
                                                            13,650<br>
                                                            15,740
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            13,370<br>
                                                            14,650<br>
                                                            16,740
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            13,570<br>
                                                            15,650<br>
                                                            17,740
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $150,000 - 174,999 <br>
                                                            $175,000 - 199,999<br>
                                                            $200,000 - 249,999
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            2,040<br>
                                                            2,040<br>
                                                            2,720
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            4,440<br>
                                                            4,440<br>
                                                            5,920
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,240<br>
                                                            6,640<br>
                                                            8,520
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            7,640<br>
                                                            8,840<br>
                                                            10,960
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            8,860<br>
                                                            10,860<br>
                                                            13,280
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            10,860<br>
                                                            12,860<br>
                                                            15,580
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            12,860<br>
                                                            14,860<br>
                                                            17,880
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            14,860<br>
                                                            16,910<br>
                                                            20,180
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            16,740<br>
                                                            19,090<br>
                                                            22,360
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            17,740<br>
                                                            20,390<br>
                                                            23,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            18,940<br>
                                                            21,690<br>
                                                            24,960
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            20,240<br>
                                                            22,990<br>
                                                            26,260
                                                        </td>
                                                    </tr>



                                                    <tr>
                                                        <td class="cs_td_setting" colspan="2">
                                                            $250,000 - 449,999<br>
                                                            $450,000 and
                                                            over
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            2,970<br> 3,140

                                                        </td>
                                                        <td class="cs_td_setting">
                                                            6,470<br>
                                                            6,840
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            9,370<br>
                                                            9,940
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            11,870<br>
                                                            12,640
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            14,190<br>
                                                            15,160
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            16,490<br>
                                                            17,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            18,790<br>
                                                            20,160
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            21,090<br>
                                                            22,660
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            23,280<br>
                                                            25,050
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            24,580<br>
                                                            26,550
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            25,880<br>
                                                            28,050
                                                        </td>
                                                        <td class="cs_td_setting">
                                                            27,180<br>
                                                            29,550
                                                        </td>
                                                    </tr>

                                                </table>
                                            </td>
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
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
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
                    <div class="col-xs-12 text-justify cs-full-width">
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
                    <div class="col-xs-12 cs-full-width">
                        <?php $consent = isset($e_signature_data['user_consent']) ? $e_signature_data['user_consent'] : 0; ?>
                        <label class="control control--checkbox">
                            <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                            <input <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo sizeof($pre_form) > 0 && $pre_form['user_consent'] == 1 ? 'disabled="disabled"' : '' ?> />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <hr />

                <?php if ($signed_flag == false) { ?>
                    <div class="row">
                        <div class="col-lg-12 text-center cs-full-width">
                            <button <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text"><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
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

                $assign_on = date("Y-m-d", strtotime($form_values['pre_form']['sent_date']));
                $compare_date_2024 = date("Y-m-d", strtotime('2024-01-01'));
                $compare_date_2025 = date("Y-m-d", strtotime('2025-01-01'));


                if ($assign_on >= $compare_date_2025) {
                    $view = $this->load->view('form_w4/preview_w4_2025', $form_values, TRUE);
                }elseif ($assign_on >= $compare_date_2024) {
                    $view = $this->load->view('form_w4/preview_w4_2024', $form_values, TRUE);
                } else {

                    $view = $this->load->view('form_w4/preview_w4_2023', $form_values, TRUE);
                }


                echo $view; ?>
            </div>
            <div id="review_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<!-- Document Modal -->
<div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<?php $this->load->view('static-pages/e_signature_popup'); ?>
<style>
    @media only screen and (max-width: 576px) {
        .cs-full-width {
            width: 100%;
        }

        .cs-btn-setting {
            width: 100%;
            margin-bottom: 10px;

        }

        .cs-word-break {
            word-wrap: break-word;
        }

        .cs-padding-zero {
            padding: 0px !important;
        }
    }
</style>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript">
    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var footer_content_print = '';
        var iframe_url = '';
        var base_url = '<?= base_url() ?>';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content_print = '<a target="_blank" class="btn btn-success" href="https://docs.google.com/gview?url=' + document_download_url + '&embedded=true">Print</a>';
                    break;
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content_print = '<a target="_blank" class="btn btn-success" href="https://view.officeapps.live.com/op/embed.aspx?src=' + document_download_url + '&embedded=true">Print</a>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default:
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a target="_blank" class="btn btn-success" href="' + base_url + 'hr_documents_management/download_upload_document/' + document_file_name + '">Download</a>';
            footer_content += footer_content_print;
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        // $('#document_modal_footer').html(footer_content);
        $('#document_modal .modal-footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }

    $("#w4-form").validate({
        ignore: ":hidden:not(select)",
        rules: {
            w4_first_name: {
                required: true
            },
            // w4_middle_name: {
            //     required: true
            // },
            number_of_allowance: {
                required: true
            },
            user_consent: {
                required: true
            }
        },
        messages: {
            w4_first_name: {
                required: 'First Name is required'
            },
            w4_middle_name: {
                required: 'Middle Name is required'
            },
            number_of_allowance: {
                required: 'Number of Allowance is required'
            },
            user_consent: {
                required: 'Please check to accept the consent'
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
            minDate: new Date()
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
            alertify.alert("Warning", 'Please Add Your Signature!');
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
                    alertify.alert("Warning", 'Cancelled!');
                }).set('labels', {
                ok: 'I Consent and Accept!',
                cancel: 'Cancel'
            });
        }
    }
</script>