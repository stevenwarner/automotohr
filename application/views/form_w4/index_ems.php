<?php
    $company_name = ucwords($session['company_detail']['CompanyName']);
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xs-12 cs-padding-zero" >
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-group col-xs-12 col-sm-4 col-md-6 col-lg-4 cs-full-width">
                    <a href="<?php echo base_url('hr_documents_management/my_documents'); ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i>  Documents</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile">Form W-4 (<?php echo date('Y'); ?>)</h2>
                    <div class="row mb-2">
                        <?php if ($pre_form['uploaded_file'] != NULL) { ?>
<!--                            <div class="col-lg-2">-->
<!--                                <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);" class="btn blue-button btn-block">Preview</a>-->
<!--                            </div>-->
                            <?php
                            $document_filename = $pre_form['uploaded_file'];
                            $document_file = pathinfo($document_filename);
                            $document_extension = $document_file['extension'];
                            $name = explode(".",$document_filename);
                            $url_segment_original = $name[0];
                            ?>
                            <div class="col-lg-2 cs-btn-setting">
                                <?php if ($document_extension == 'pdf') { ?>

                                    <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_original.'.pdf' ?>" class="btn blue-button btn-sm btn-block">Print</a>

                                <?php } else if ($document_extension == 'docx') { ?>
                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edocx&wdAccPdf=0' ?>" class="btn blue-button btn-sm btn-block">Print</a>
                                <?php } else if ($document_extension == 'doc') { ?>
                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edoc&wdAccPdf=0' ?>" class="btn blue-button btn-sm btn-block">Print</a>
                                <?php } else if ($document_extension == 'xls') { ?>
                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exls' ?>" class="btn blue-button btn-sm btn-block">Print</a>
                                <?php } else if ($document_extension == 'xlsx') { ?>
                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exlsx' ?>" class="btn blue-button btn-sm btn-block">Print</a>
                                <?php } ?>
                            </div>
<!--                            <div class="col-lg-3 pull-right">-->
<!--                                <a class="btn blue-button btn-sm btn-block"-->
<!--                                   href="javascript:void(0);"-->
<!--                                   onclick="fLaunchModal(this);"-->
<!--                                   data-preview-url="--><?//= AWS_S3_BUCKET_URL . $pre_form['uploaded_file']; ?><!--"-->
<!--                                   data-download-url="--><?//= AWS_S3_BUCKET_URL . $pre_form['uploaded_file']; ?><!--"-->
<!--                                   data-file-name="--><?php //echo $pre_form['uploaded_file']; ?><!--"-->
<!--                                   data-document-title="--><?php //echo $pre_form['uploaded_file']; ?><!--"-->
<!--                                   data-preview-ext="--><?php //echo $document_extension ?><!--">Preview</a>-->
                                <!--                            <a target="_blank"-->
                                <!--                               href="--><?php //echo base_url('form_i9/preview_i9form/' . $pre_form['user_type'] . '/' . $pre_form['user_sid']) ?><!--"-->
                                <!--                               class="btn blue-button btn-block">Preview</a>-->
<!--                            </div>-->
                            <div class="col-lg-3 cs-btn-setting">
                                <a download="W4 Submitted Form" href="<?php echo base_url('hr_documents_management/download_upload_document').'/'.$pre_form['uploaded_file'];?>" class="btn blue-button btn-block">Download Submitted Form</a>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 cs-btn-setting"></div>
                            <div class="col-lg-2 cs-btn-setting">
                                <form action="<?php echo current_url()?>" method="get">
                                    <input class="btn blue-button btn-block" id="download-pdf" value="Download PDF" name="submit" type="submit">
                                </form>
                            </div>
                            <div class="col-lg-2 cs-btn-setting">
                                <a target="_blank" href="<?php echo base_url('form_w4/print_w4_form'.'/'. $pre_form['user_type'] . '/' . $pre_form['employer_sid']); ?>" class="btn blue-button btn-block">
                                    Print PDF
                                </a>
                            </div>
                            <div class="col-lg-2 cs-btn-setting">
                                <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);" class="btn blue-button btn-block">Preview PDF</a>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <?php if (!empty($pre_form['uploaded_file']) && $pre_form['uploaded_file'] != NULL) { ?>
                    <div class="form-wrp">
                        <iframe src="<?=AWS_S3_BUCKET_URL.$pre_form['uploaded_file'];?>?embedded=true" style="width: 100%; height: 80rem;"></iframe>
                    </div>
                <?php }else{?>
                <div class="form-wrp" >
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-word-break cs-full-width">
                            <p class="form-w4-text">
                                <strong>Future developments.</strong> For the latest information about any future developments related to Form W-4, such as legislation enacted after it was published, go to www.irs.gov/FormW4.
                            </p>
                            <p class="form-w4-text">
                                <strong>Purpose</strong>. Complete Form W-4 so that your employer can withhold the correct federal income tax from your pay. Consider completing a new Form W-4 each year and when your personal or financial situation changes.
                            </p>
                            <p class="form-w4-text">
                                <strong>Exemption from withholding.</strong> You may claim exemption from withholding for <?php echo date('Y'); ?> if both of the following apply.
                            </p>
                            <p class="form-w4-text">
                                • For <?php echo date('Y')-1; ?> you had a right to a refund of all federal income tax withheld because you had no tax liability, and
                            </p>
                            <p class="form-w4-text">
                                • For <?php echo date('Y'); ?> you expect a refund of all federal income tax withheld because you expect to have no tax liability.
                            </p>
                            <p>If you’re exempt, complete only lines 1, 2, 3, 4, and 7 and sign the form to validate it. Your exemption for <?php echo date('Y'); ?> expires February 15, <?php echo date('Y') + 1; ?>. See Pub. 505, Tax Withholding and Estimated Tax, to learn more about whether you qualify for exemption from withholding.</p>
                            <h3>General Instructions</h3>
                            <p>If you aren’t exempt, follow the rest of these instructions to determine the number of withholding allowances you should claim for withholding for <?php echo date('Y'); ?> and any additional amount of tax to have withheld. For regular wages, withholding must be based on allowances you claimed and may not be a flat amount or percentage of wages.</p>
                            <p>You can also use the calculator at <strong>www.irs.gov/W4App</strong> to determine your tax withholding more accurately. Consider</p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-word-break cs-full-width">
                            <p class="form-w4-text">
                                using this calculator if you have a more complicated tax situation, such as if you have a working spouse, more than one job, or a large amount of nonwage income outside of your job. After your Form W-4 takes effect, you can also use this calculator to see how the amount of tax you’re having withheld compares to your projected total tax for <?php echo date('Y'); ?>. If you use the calculator, you don’t need to complete any of the worksheets for Form W-4.
                            </p>
                            <p class="form-w4-text">
                                Note that if you have too much tax withheld, you will receive a refund when you file your tax return. If you have too little tax withheld, you will owe tax when you file your tax return, and you might owe a penalty.
                            </p>
                            <p class="form-w4-text">
                                <strong>Filers with multiple jobs or working spouses.</strong> If you have more than one job at a time, or if you’re married and your spouse is also working, read all of the instructions including the instructions for the Two-Earners/Multiple Jobs Worksheet before beginning.
                            </p>
                            <p class="form-w4-text">
                                <strong>Nonwage income.</strong> If you have a large amount of nonwage income, such as interest or dividends, consider making estimated tax payments using Form 1040-ES, Estimated Tax for Individuals. Otherwise, you might owe additional tax. Or, you can use the Deductions, Adjustments, and Other Income Worksheet on page 3 or the calculator at www.irs.gov/W4App to make sure you have enough tax withheld from your paycheck. If you have pension or annuity income, see Pub. 505 or use the calculator at www.irs.gov/W4App to find out if you should adjust your withholding on Form W-4 or W-4P.
                            </p>
                            <p class="form-w4-text">
                                <strong>Nonresident alien.</strong> If you’re a nonresident alien, see Notice 1392, Supplemental Form W-4 Instructions for Nonresident Aliens, before completing this form.
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-word-break cs-full-width">
                            <h3 style="margin-top: 0;" class="cs-word-break">Specific Instructions Personal Allowances Worksheet</h3>
                            <p class="form-w4-text">
                                Complete this worksheet on page 3 first to determine the number of withholding allowances to claim.
                            </p>
                            <p class="form-w4-text">
                                <strong>Line C. Head of household please note:</strong> Generally, you can claim head of household filing status on your tax return only if you’re unmarried and pay more than 50% of the costs of keeping up a home for yourself and a qualifying individual. See Pub. 501 for more information about filing status.
                            </p>
                            <p class="form-w4-text">
                                <strong>Line E. Child tax credit.</strong> When you file your tax return, you might be eligible to claim a credit for each of your qualifying children. To qualify, the child must be under age 17 as of December 31 and must be your dependent who lives with you for more than half the year. To learn more about this credit, see Pub. 972, Child Tax Credit. To reduce the tax withheld from your pay by taking this credit into account, follow the instructions on line E of the worksheet. On the worksheet you will be asked about your total income. For this purpose, total income includes all of your wages and other income, including income earned by a spouse, during the year.
                            </p>
                            <p class="form-w4-text">
                                <strong>Line F. Credit for other dependents.</strong> When you file your tax return, you might be eligible to claim a credit for each of your dependents that don’t qualify for the child tax credit, such as any dependent children age 17 and older. To learn more about this credit, see Pub. 505. To reduce the tax withheld from your pay by taking this credit into account, follow the instructions on line F of the worksheet. On the worksheet, you will be asked about your total income. For this purpose, total income includes all of
                            </p>
                        </div>
                        <div class="col-lg-12 text-center cs-word-break cs-full-width">
                            <h4>Separate here and give Form W-4 to your employer. Keep the worksheet(s) for your records.</h4>
                        </div>
                        <div class="col-lg-2 cs-word-break cs-full-width cs-full-width">
                            <strong>Form W-4</strong>
                            <p>Department of the Treasury Internal Revenue Service</p>
                        </div>
                        <div class="col-lg-8 text-center cs-word-break cs-full-width">
                            <h2 style="margin-top: 0;">Employee’s Withholding Allowance Certificate</h2>
                            <p>▶ Whether you’re entitled to claim a certain number of allowances or exemption from withholding is subject to review by the IRS. Your employer may be required to send a copy of this form to the IRS.</p>
                        </div>
                        <div class="col-lg-2 text-center cs-full-width cs-word-break">
                            <p>OMB No. 1545-0074</p>
                            <strong><?php echo date('Y'); ?></strong>
                        </div>
                    </div>
                    <form id="w4-form" action="" method="post">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                <div class="form-group">
                                    <label>1. Your first name</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['first_name']: ''?>" name="w4_first_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                <div class="form-group">
                                    <label>Your middle initial</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['middle_name']: ''?>" name="w4_middle_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                <div class="form-group">
                                    <label>Last name</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['last_name']: ''?>" name="w4_last_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                <div class="form-group">
                                    <label>2. Your social security number</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['ss_number']: ''?>" name="ss_number" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group">
                                    <label>Home address (number and street or rural route)</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['home_address']: ''?>" name="home_address" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group autoheight">
                                    <label>3. Marital Status</label>
                                    <div class="row">
                                        <div class="col-lg-4 cs-full-width">
                                            <div class="form-group autoheight">
                                                <label class="control control--checkbox">
                                                    Single
                                                    <input type="radio" name="marriage_status" value="single" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'single' ? 'checked="checked"': ''?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 cs-full-width">
                                            <div class="form-group autoheight">
                                                <label class="control control--checkbox">
                                                    Married
                                                    <input type="radio" name="marriage_status" value="married" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'married' ? 'checked="checked"': ''?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 cs-full-width">
                                            <div class="form-group autoheight">
                                                <label class="control control--checkbox">
                                                    Married, but withhold at higher Single rate.
                                                    <input type="radio" name="marriage_status" value="complex" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'complex' ? 'checked="checked"': ''?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <p><strong>Note:</strong> If married filing separately, check “Married, but withhold at higher Single rate.”</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                <div class="form-group">
                                    <label>City or town</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['city']: ''?>" name="city" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="state" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group">
                                    <label>ZIP Code</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['zip']: ''?>" name="zip" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        4. If your last name differs from that shown on your social security card, check here. You must call 800-772-1213 for a replacement card.
                                        <input type="checkbox" name="different_last_name" value="1" <?php echo sizeof($pre_form)>0 && $pre_form['different_last_name'] == 1 ? 'checked="checked"': ''?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group">
                                    <label>5. Total number of allowances you’re claiming (from the applicable worksheet on the following pages)</label>
                                    <input type="number" value="<?php echo sizeof($pre_form)>0 && !empty($pre_form['number_of_allowance']) ? $pre_form['number_of_allowance']: 0; ?>" min="0" max="5" name="number_of_allowance" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group">
                                    <label>6. Additional amount, if any, you want withheld from each paycheck</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['additional_amount']: ''?>" name="additional_amount" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group autoheight">
                                    <label>7. I claim exemption from withholding for <?php echo date('Y'); ?>, and I certify that I meet both of the following conditions for exemption.</label>
                                    <p>
                                        &nbsp;&nbsp;• Last year I had a right to a refund of all federal income tax withheld because I had no tax liability, and<br>
                                        &nbsp;&nbsp;• This year I expect a refund of all federal income tax withheld because I expect to have no tax liability.<br>
                                        If you meet both conditions, write “Exempt” here
                                    </p>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['claim_exempt']: ''?>" name="claim_exempt" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group autoheight">
                                    <label>Under penalties of perjury, I declare that I have examined this certificate and, to the best of my knowledge and belief, it is true, correct, and complete.</label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="form-group autoheight">
                                    <span><b>Employee's signature</b> (This form is not valid unless you sign it.)</span>
                                    <?php if($signed_flag == true) { ?>
                                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT?>;" src="<?php echo $pre_form['signature_bas64_image']; ?>" class="esignaturesize" />
                                    <?php } else { ?>
                                        <!-- the below loaded view add e-signature -->
                                        <?php $this->load->view('static-pages/e_signature_button'); ?>
                                    <?php } ?>  
                                </div>
                            </div>

<!--                                                        For Employer Section - Commented because employer need to save it after employee concent it-->
<!--                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
<!--                                <div class="form-group autoheight">-->
<!--                                    <label>8. Employer’s name</label>-->
<!--                                    <input type="text" value="--><?php //echo sizeof($pre_form)>0 ? $pre_form['emp_name']: ''?><!--" name="emp_name" class="form-control" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
<!--                                <div class="form-group autoheight">-->
<!--                                    <label> Employer’s address </label>-->
<!--                                    <input type="text" value="--><?php //echo sizeof($pre_form)>0 ? $pre_form['emp_address']: ''?><!--" name="emp_address" class="form-control" />-->
<!--                                </div>-->
<!--                            </div>-->
<!---->
<!--                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">-->
<!--                                <div class="form-group autoheight">-->
<!--                                    <label>Note: (Employer: Complete boxes 8 and 10 if sending to IRS and complete boxes 8, 9, and 10 if sending to State Directory of New Hires.)</label>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
<!--                                <div class="form-group autoheight">-->
<!--                                    <label>9. First date of employment</label>-->
<!--                                    <input type="text" value="--><?php //echo isset($pre_form) && !empty($pre_form['first_date_of_employment']) && $pre_form['first_date_of_employment'] != '0000-00-00' ? date("m-d-Y",strtotime($pre_form['first_date_of_employment'])): ''?><!--" name="first_date_of_employment" class="form-control" id="first_date_of_employment" readonly/>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
<!--                                <div class="form-group">-->
<!--                                    <label>10. Employer identification number (EIN)</label>-->
<!--                                    <input type="text" value="--><?php //echo sizeof($pre_form)>0 ? $pre_form['emp_identification_number']: ''?><!--" name="emp_identification_number" class="form-control" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                                                        End of Employer Section-->
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-word-break cs-full-width">
                                <p class="form-w4-text">
                                    your wages and other income, including income earned by a spouse, during the year.
                                </p>
                                <p class="form-w4-text">
                                    <strong>Line G. Other credits.</strong>. You might be able to reduce the tax withheld from your paycheck if you expect to claim other tax credits, such as the earned income tax credit and tax credits for education and child care expenses. If you do so, your paycheck will be larger but the amount of any refund that you receive when you file your tax return will be smaller. Follow the instructions for Worksheet 1-6 in Pub. 505 if you want to reduce your withholding to take these credits into account.
                                </p>
                                <p class="form-w4-text">
                                    <h4><strong>Deductions, Adjustments, and Additional Income Worksheet</strong></h4>Complete this worksheet to determine if you’re able to reduce the tax withheld from your paycheck to account for your itemized deductions and other adjustments to income such as IRA contributions. If you do so, your refund at the end of the year will be smaller, but your paycheck will be larger. You’re not required to complete this worksheet or reduce your withholding if you don’t wish to do so.
                                </p>
                                <p class="form-w4-text">
                                    You can also use this worksheet to figure out how much to increase the tax withheld from your paycheck if you have a large amount of nonwage income, such as interest or dividends.
                                </p>
                                <p class="form-w4-text">
                                    Another option is to take these items into account and make your withholding more accurate by using the calculator at www.irs.gov/W4App. If you use the calculator, you don’t need to complete any of the worksheets for Form W-4.
                                </p>
                                <p class="form-w4-text">
                                    <strong>Two-Earners/Multiple Jobs Worksheet</strong>
                                </p>
                                <p class="form-w4-text">
                                    Complete this worksheet if you have more
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-word-break cs-full-width">
                                <p class="form-w4-text">
                                    than one job at a time or are married filing jointly and have a working spouse. If you don’t complete this worksheet, you might have too little tax withheld. If so, you will owe tax when you file your tax return and might be subject to a penalty.
                                </p>
                                <p class="form-w4-text">
                                    Figure the total number of allowances you’re entitled to claim and any additional amount of tax to withhold on all jobs using worksheets from only one Form W-4. Claim all allowances on the W-4 that you or your spouse file for the highest paying job in your family and claim zero allowances on Forms W-4 filed for all other jobs. For example, if you earn $60,000 per year and your spouse earns $20,000, you should complete the worksheets to determine what to enter on lines 5 and 6 of your Form W-4, and your spouse should enter zero (“-0-”) on lines 5 and 6 of his or her Form W-4. See Pub. 505 for details.
                                </p>
                                <p class="form-w4-text">
                                    Another option is to use the calculator at www.irs.gov/W4App to make your withholding more accurate.
                                </p>
                                <p class="form-w4-text">
                                    <strong>Tip:</strong> If you have a working spouse and your incomes are similar, you can check the “Married, but withhold at higher Single rate” box instead of using this worksheet. If you choose this option, then each spouse should fill out the Personal Allowances Worksheet and check the “Married, but withhold at higher Single rate” box on Form W-4, but only one spouse should claim any allowances for credits or fill out the Deductions, Adjustments, and Additional Income Worksheet.
                                </p>
                                <p class="form-w4-text">
                                    <strong>Instructions for Employer</strong> Employees, do not complete box 8, 9, or 10. Your employer will complete these boxes if necessary.
                                </p>
                                <p class="form-w4-text">
                                    New hire reporting. Employers are
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-word-break cs-full-width">
                                <p class="form-w4-text">
                                    required by law to report new employees to a designated State Directory of New Hires. Employers may use Form W-4, boxes 8, 9, and 10 to comply with the new hire reporting requirement for a newly hired employee. A newly hired employee is an employee who hasn’t previously been employed by the employer, or who was previously employed by the employer but has been separated from such prior employment for at least 60 consecutive days. Employers should contact the appropriate State Directory of New Hires to find out how to submit a copy of the completed Form W-4. For information and links to each designated State Directory of New Hires (including for U.S. territories), go to www.acf.hhs.gov/programs/css/employers.
                                </p>
                                <p class="form-w4-text">
                                    If an employer is sending a copy of Form W-4 to a designated State Directory of New Hires to comply with the new hire reporting requirement for a newly hired employee, complete boxes 8, 9, and 10 as follows.
                                </p>
                                <p class="form-w4-text">
                                    <strong>Box 8.</strong> Enter the employer’s name and address. If the employer is sending a copy of this form to a State Directory of New Hires, enter the address where child support agencies should send income withholding orders.
                                </p>
                                <p class="form-w4-text">
                                    <strong>Box 9.</strong> If the employer is sending a copy of this form to a State Directory of New Hires, enter the employee’s first date of employment, which is the date services for payment were first performed by the employee. If the employer rehired the employee after the employee had been separated from the employer’s service for at least 60 days, enter the rehire date.
                                </p>
                                <p class="form-w4-text">
                                    <strong>Box 10.</strong> Enter the employer’s employer identification number (EIN).
                                </p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 cs-word-break cs-full-width">
                                <div class="form-group"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-default">
                                    <div class="panel-heading text-center">
                                        Personal Allowances Worksheet (Keep for your records.)
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-word-brea cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>A. Enter “1” for yourself</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['paw_yourself']: ''?>" name="paw_yourself" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-word-break cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>B. Enter “1” if you will file as married filing jointly</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['paw_married']: ''?>" name="paw_married" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>C. Enter “1” if you will file as head of household</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['paw_head']: ''?>" name="paw_head" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>D. Enter “1” if: </label>
                                                    <p>
                                                        &nbsp;&nbsp;• You’re single, or married filing separately, and have only one job; or<br>
                                                        &nbsp;&nbsp;• You’re married filing jointly, have only one job, and your spouse doesn’t work; or<br>
                                                        &nbsp;&nbsp;• Your wages from a second job or your spouse’s wages (or the total of both) are $1,500 or less.
                                                    </p>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['paw_single_wages']: ''?>" name="paw_single_wages" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>E. <strong>Child tax credit.</strong> See Pub. 972, Child Tax Credit, for more information.</label>
                                                    <p>
                                                        &nbsp;&nbsp;• If your total income will be less than $69,801 ($101,401 if married filing jointly), enter “4” for each eligible child.<br>
                                                        &nbsp;&nbsp;• If your total income will be from $69,801 to $175,550 ($101,401 to $339,000 if married filing jointly), enter “2” for each eligible child.<br>
                                                        &nbsp;&nbsp;• If your total income will be from $175,551 to $200,000 ($339,001 to $400,000 if married filing jointly), enter “1” for each eligible child.
                                                        &nbsp;&nbsp;• If your total income will be higher than $200,000 ($400,000 if married filing jointly), enter “-0-”.
                                                    </p>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['paw_child_tax']: ''?>" name="paw_child_tax" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>F. <strong>Credit for other dependents.</strong></label>
                                                    <p>
                                                        &nbsp;&nbsp;• If your total income will be less than $69,801 ($101,401 if married filing jointly), enter “1” for each eligible dependent.<br>
                                                        &nbsp;&nbsp;• If your total income will be from $69,801 to $175,550 ($101,401 to $339,000 if married filing jointly), enter “1” for every two dependents (for example, “-0-” for one dependent, “1” if you have two or three dependents, and “2” if you have four dependents).<br>
                                                        &nbsp;&nbsp;• If your total income will be higher than $175,550 ($339,000 if married filing jointly), enter “-0-”
                                                    </p>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['paw_dependents']: ''?>" name="paw_dependents" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>G. <strong>Other credits.</strong>If you have other credits, see Worksheet 1-6 of Pub. 505 and enter the amount from that worksheet here</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['paw_other_credit']: ''?>" name="paw_other_credit" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>H. Add lines A through G and enter the total here</label>
                                                    <span>(For accuracy, complete all worksheets that apply.)</span>
                                                    <p>
                                                        &nbsp;&nbsp;• If you plan to itemize or claim adjustments to income and want to reduce your withholding, or if you have a large amount of nonwage income and want to increase your withholding, see the Deductions, Adjustments, and Additional Income Worksheet below.<br>
                                                        &nbsp;&nbsp;• If you have more than one job at a time or are married filing jointly and you and your spouse both work, and the combined earnings from all jobs exceed $52,000 ($24,000 if married filing jointly), see the Two-Earners/Multiple Jobs Worksheet on page 4 to avoid having too little tax withheld.<br>
                                                        &nbsp;&nbsp;• If neither of the above situations applies, stop here and enter the number from line H on line 5 of Form W-4 above.
                                                    </p>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['paw_accuracy']: ''?>" name="paw_accuracy" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-default">
                                    <div class="panel-heading text-center">
                                        <strong>Deductions, Adjustments, and Additional Income Worksheet</strong><br>
                                        <strong>Note:</strong> Use this worksheet only if you plan to itemize deductions, claim certain adjustments to income, or have a large amount of nonwage income.
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>1. Enter an estimate of your <?php echo date('Y'); ?> itemized deductions. These include qualifying home mortgage interest, charitable contributions, state and local taxes (up to $10,000), and medical expenses in excess of 7.5% of your income. See Pub. 505 for details</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_estimate']: ''?>" name="daaiw_estimate" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>2. Enter: </label>
                                                    <p>
                                                        &nbsp;&nbsp;• $24,000 if you’re married filing jointly or qualifying widow(er)<br>
                                                        &nbsp;&nbsp;• $18,000 if you’re head of household<br>
                                                        &nbsp;&nbsp;• $12,000 if you’re single or married filing separately
                                                    </p>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_enter_status']: ''?>" name="daaiw_enter_status" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>3. <strong>Subtract</strong> line 2 from line 1. If zero or less, enter “-0-”</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_subtract_line_2']: ''?>" name="daaiw_subtract_line_2" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>4. Enter an estimate of your <?php echo date('Y'); ?> adjustments to income and any additional standard deduction for age or blindness (see Pub. 505 for information about these items) </label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_estimate_of_adjustment']: ''?>" name="daaiw_estimate_of_adjustment" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>5. <strong>Add</strong> lines 3 and 4 and enter the total</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_add_line_3_4']: ''?>" name="daaiw_add_line_3_4" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>6. Enter an estimate of your <?php echo date('Y'); ?> nonwage income (such as dividends or interest)</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_estimate__of_nonwage']: ''?>" name="daaiw_estimate__of_nonwage" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>7. <strong>Subtract</strong>line 6 from line 5. If zero, enter “-0-”. If less than zero, enter the amount in parentheses</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_subtract_line_6']: ''?>" name="daaiw_subtract_line_6" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>8. <strong>Divide</strong> the amount on line 7 by $4,150 and enter the result here. If a negative amount, enter in parentheses. Drop any fraction</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_divide_line_7']: ''?>" name="daaiw_divide_line_7" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>9. Enter the number from the <strong>Personal Allowances Worksheet, line H above</strong></label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_enter_number_personal_allowance']: ''?>" name="daaiw_enter_number_personal_allowance" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>10. <strong>Add</strong> lines 8 and 9 and enter the total here. If zero or less, enter “-0-”. If you plan to use the <strong>Two-Earners/Multiple Jobs Worksheet</strong>, also enter this total on line 1, page 4. Otherwise, <strong>stop here</strong> and enter this total on Form W-4, line 5, page 1</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_add_line_8_9']: ''?>" name="daaiw_add_line_8_9" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                <div class="panel panel-default">
                                    <div class="panel-heading text-center">
                                        <strong>Two-Earners/Multiple Jobs Worksheet</strong><br>
                                        <strong>Note:</strong> Use this worksheet only if the instructions under line H from the Personal Allowances Worksheet direct you here.
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>1. Enter the number from the <strong>Personal Allowances Worksheet,</strong> line H, page 3 (or, if you used the <strong>Deductions, Adjustments, and Additional Income Worksheet</strong> on page 3, the number from line 10 of that worksheet)</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_personal_allowance']: ''?>" name="temjw_personal_allowance" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>2. Find the number in <strong>Table 1</strong> below that applies to the <strong>LOWEST</strong> paying job and enter it here. <strong>However,</strong> if you’re married filing jointly and wages from the highest paying job are $75,000 or less and the combined wages for you and your spouse are $107,000 or less, don’t enter more than “3” </label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_num_in_table_1']: ''?>" name="temjw_num_in_table_1" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>3. If line 1 is <strong>more than or equal to</strong> line 2, subtract line 2 from line 1. Enter the result here (if zero, enter “-0-”) and on Form W-4, line 5, page 1. <strong>Do not</strong> use the rest of this worksheet</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_more_line2']: ''?>" name="temjw_more_line2" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label><strong>Note:</strong> If line 1 is <strong>less than</strong> line 2, enter “-0-” on Form W-4, line 5, page 1. Complete lines 4 through 9 below to figure the additional withholding amount necessary to avoid a year-end tax bill.</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>4. Enter the number from line 2 of this worksheet</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_num_from_line2']: ''?>" name="temjw_num_from_line2" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>5. Enter the number from line 1 of this worksheet</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_num_from_line1']: ''?>" name="temjw_num_from_line1" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>6. <strong>Subtract</strong> line 5 from line 4</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_subtract_5_from_4']: ''?>" name="temjw_subtract_5_from_4" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>7. Find the amount in <strong>Table 2</strong> below that applies to the <strong>HIGHEST</strong> paying job and enter it here</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_amount_in_table_2']: ''?>" name="temjw_amount_in_table_2" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>8. <strong>Multiply</strong> line 7 by line 6 and enter the result here. This is the additional annual withholding needed</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_multiply_7_by_6']: ''?>" name="temjw_multiply_7_by_6" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 cs-full-width">
                                                <div class="form-group autoheight">
                                                    <label>9. <strong>Divide</strong> line 8 by the number of pay periods remaining in <?php echo date('Y'); ?>. For example, divide by 18 if you’re paid every 2 weeks and you complete this form on a date in late April when there are 18 pay periods remaining in <?php echo date('Y'); ?>. Enter the result here and on Form W-4, line 6, page 1. This is the additional amount to be withheld from each paycheck</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['temjw_divide_8_by_period']: ''?>" name="temjw_divide_8_by_period" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">

                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center">
                                                <strong>Table 1</strong>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center" colspan="2">Married Filing Jointly</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-center">If wages from LOWEST paying job are—</td>
                                                                        <td class="text-center">Enter on line 2 above</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-center">$0 - $5,000 <br> 5,001 - 9,500 <br> 9,501 - 19,000 <br> 19,001 - 26,500 <br> 26,501 - 37,000 <br> 37,001 - 43,500 <br> 43,501 - 55,000 <br> 55,001 - 60,000 <br> 60,001 - 70,000 <br> 70,001 - 75,000 <br> 75,001 - 85,000 <br> 85,001 - 95,000 <br> 95,001 - 130,000 <br> 130,001 - 150,000 <br> 150,001 - 160,000 <br> 160,001 - 170,000 <br> 170,001 - 180,000 <br> 180,001 - 190,000 <br> 190,001 - 200,000 <br> 200,001 and over</td>
                                                                        <td class="text-center">0<br>1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center" colspan="2">All Others</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-center">If wages from LOWEST paying job are—</td>
                                                                        <td class="text-center">Enter on line 2 above</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-center">$0 - $7,000 <br> 7,001 - 12,500 <br> 12,501 - 24,500 <br> 24,501 - 31,500 <br> 31,501 - 39,000<br> 39,001 - 55,000 <br> 55,001 - 70,000 <br> 70,001 - 85,000 <br> 85,001 - 90,000 <br> 90,001 - 100,000 <br> 100,001 - 105,000 <br> 105,001 - 115,000 <br> 115,001 - 120,000 <br> 120,001 - 130,000 <br> 130,001 - 145,000 <br> 145,001 - 155,000 <br> 155,001 - 185,000 <br> 185,001 and over</td>
                                                                        <td class="text-center">0<br>1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">

                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center">
                                                <strong>Table 2</strong>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center" colspan="2">Married Filing Jointly</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-center">If wages from HIGHEST paying job are—</td>
                                                                        <td class="text-center">Enter on line 7 above</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-center">$0 - $24,375 <br> 24,376 - 82,7250 <br> 82,726 - 170,325 <br> 170,326 - 320,325 <br> 320,326 - 405,325 <br> 405,326 - 605,325 <br> 605,326 and over</td>
                                                                        <td class="text-center">420<br>500<br>910<br>1,000<br>1,330<br>1,450<br>1,540</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 cs-full-width">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center" colspan="2">Married Filing Jointly</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-center">If wages from HIGHEST paying job are—</td>
                                                                        <td class="text-center">Enter on line 7 above</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-center">$0 - $7,000 <br> 7,001 - 36,175 <br> 36,176 - 79,975 <br> 79,976 - 154,975 <br> 154,976 - 197,475 <br> 197,476 - 497,475 <br> 497,476 and over</td>
                                                                        <td class="text-center">$420<br>500<br>910<br>1,000<br>1,330<br>1,450<br>1,540</td>
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
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-full-width">
                                <p class="form-w4-text">
                                    <strong>Privacy Act and Paperwork Reduction Act Notice.</strong> We ask for the information on this form to carry out the Internal Revenue laws of the United States. Internal Revenue Code sections 3402(f)(2) and 6109 and their regulations require you to provide this information; your employer uses it to determine your federal income tax withholding. Failure to provide a properly completed form will result in your being treated as a single person who claims no withholding allowances; providing fraudulent information may subject you to penalties. Routine uses of this information include giving it to the Department of Justice for civil and criminal litigation; to cities, states, the District of Columbia,
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-full-width">
                                <p class="form-w4-text">
                                    and U.S. commonwealths and possessions for use in administering their tax laws; and to the Department of Health and Human Services for use in the National Directory of New Hires. We may also disclose this information to other countries under a tax treaty, to federal and state agencies to enforce federal nontax criminal laws, or to federal law enforcement and intelligence agencies to combat terrorism.
                                </p>
                                <p class="form-w4-text">
                                    You aren’t required to provide the information requested on a form that’s subject to the Paperwork Reduction Act unless the form displays a valid OMB control number. Books or records relating to a form or its instructions must be
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 cs-full-width">
                                <p class="form-w4-text">
                                    retained as long as their contents may become material in the administration of any Internal Revenue law. Generally, tax returns and return information are confidential, as required by Code section 6103.
                                </p>
                                <p class="form-w4-text">
                                    The average time and expenses required to complete and file this form will vary depending on individual circumstances. For estimated averages, see the instructions for your income tax return.
                                </p>
                                <p class="form-w4-text">
                                    If you have suggestions for making this form simpler, we would be happy to hear from you. See the instructions for your income tax return.
                                </p>
                            </div>
                        </div>
                        <?php //if(!empty($e_signature_data)) { ?>
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

                                <input type="hidden" id="active_signature" name="active_signature" value="<?php echo isset($active_signature)? $active_signature:''; ?>" />

                                <input type="hidden" id="signature" name="signature" value="<?php echo isset($signature)? $signature:''; ?>" />

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
                                    <div class="col-xs-12 cs-full-width">
                                        <?php $consent = isset($e_signature_data['user_consent']) ? $e_signature_data['user_consent'] : 0; ?>
                                        <label class="control control--checkbox">
                                            <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                            <input <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?>  <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo sizeof($pre_form)>0 && $pre_form['user_consent'] == 1 ? 'disabled="disabled"' : '' ?>/>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <hr />

                                <?php if($signed_flag == false) { ?>
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <button <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text"><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php //} ?>
                    </form>
                </div>
                <?php }?>
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
                $view = $this->load->view('form_w4/index-pdf', $form_values, TRUE);
                echo $view; ?>
            </div>
            <div id="review_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<?php $this->load->view('static-pages/e_signature_popup'); ?>
<style>
@media only screen and (max-width: 576px) {
   .cs-full-width{
       width:100%;
   }
   .cs-btn-setting{
      width:100%;
      margin-bottom:10px;

   }
   .cs-word-break{
        word-wrap:break-word;
    }
    .cs-padding-zero{
        padding:0px !important;
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
        var base_url = '<?= base_url()?>';

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
                default :
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
        $('#document_modal').on("shown.bs.modal", function () {
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

    $(document).ready(function(){
        $('input[name=w4_federaltax_classification]').on('change', function() {
            $('input[name=w4_federaltax_classification]').not(this).prop('checked', false);
        });

        <?php if($signed_flag == true) { ?>
//        $('input[type=checkbox]:not(:checked)').prop('disabled', true);
        <?php } ?>

        $('#first_date_of_employment').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();
    });

    function func_save_e_signature() {
        
        var is_signature_exist = $('#signature_bas64_image').val();
        if(is_signature_exist == ""){
            alertify.error('Please Add Your Signature!');
            return false;
        }
        
        if ($('#w4-form').validate()) {
            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                function () {
                    $('#w4-form').submit();
                },
                function () {
                    alertify.error('Cancelled!');
                }).set('labels', {ok: 'I Consent and Accept!', cancel: 'Cancel'});
        }
    }

</script>