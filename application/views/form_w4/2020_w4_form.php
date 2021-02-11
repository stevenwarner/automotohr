<?php
    $company_name = ucwords($session['company_detail']['CompanyName']);
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 ">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a href="<?php echo base_url('onboarding/hr_documents/'.$unique_sid); ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i>  Documents</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile">Form W-4 (<?php echo date('Y'); ?>)</h2>
                    <div class="row mb-2">
                        <!-- <?php //if ($pre_form['user_consent'] == 1) { ?> -->
                            <?php if ($pre_form['uploaded_file'] != NULL) { ?>
                                <div class="col-lg-7"></div>
                                <div class="col-lg-2">
                                    <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);" class="btn blue-button btn-block">Preview</a>
                                </div>
                                <div class="col-lg-3">
                                    <a download="W4 Submitted Form" href="<?php echo AWS_S3_BUCKET_URL.$pre_form['uploaded_file'];?>" class="btn blue-button btn-block">Download Submitted Form</a>
                                </div>
                            <?php } else{ ?>
                                <div class="col-lg-6"></div>
                                <div class="col-lg-2">
                                    <form action="<?php echo current_url()?>" method="get">
                                        <input class="btn blue-button btn-block" id="download-pdf" value="Download PDF" name="submit" type="submit">
                                    </form>
                                </div>
                                <div class="col-lg-2">
                                    <a target="_blank" href="<?php echo base_url('onboarding/print_form_w4/'.$unique_sid); ?>" class="btn blue-button btn-block">Print PDF</a>
                                </div>
                                <div class="col-lg-2">
                                    <a data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);" class="btn blue-button btn-block">Preview PDF</a>
                                </div>
                            <?php }?>
                        <!-- <?php// }?> -->
                    </div>
                </div>
                <div class="form-wrp">

                  
                        
                        <div class="col-lg-2">
                            <strong>Form W-4</strong>
                            <p>Department of the Treasury Internal Revenue Service</p>
                        </div>
                        <div class="col-lg-8 text-center">
                            <h2 style="margin-top: 0;">Employee’s Withholding Allowance Certificate</h2>
                            <p>▶ Complete Form W-4 so that your employer can withhold the correct federal income tax from your pay.</p>
                             <p>▶  Give Form W-4 to your employer</p>
                             <p>▶  Your withholding is subject to review by the IRS.</p>
                        </div>
                        <div class="col-lg-2 text-center">
                            <p>OMB No. 1545-0074</p>
                            <strong><?php echo date('Y'); ?></strong>
                        </div>
                    </div>
                    <form id="w4-form" action="" method="post">
                      <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="panel panel-blue">
                                <div class="panel-heading incident-panal-heading">
                                    <b> Step 1:Enter Personal Information</b>
                                </div>
                                <div class="panel-body">
                                   <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>(a) First name and middle initial</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['first_name']: ''?>" name="w4_first_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Last name</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['last_name']: ''?>" name="w4_middle_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>(b) Social security number</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['last_name']: ''?>" name="w4_last_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['home_address']: ''?>" name="home_address" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>City or town, state, and ZIP code</label>
                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="state" class="form-control" />
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
                                            <input type="radio" name="marriage_status" value="single" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'single' ? 'checked="checked"': ''?>>
                                            <div class="control__indicator"></div>
                                    </label>
                                </div>
                             </div>
                             <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label class="control control--checkbox">
                                            Married filing jointly (or Qualifying widow(er))
                                            <input type="radio" name="marriage_status" value="single" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'single' ? 'checked="checked"': ''?>>
                                            <div class="control__indicator"></div>
                                    </label>
                                </div>
                             </div>
                             <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                 
                                    <label class="control control--checkbox">
                                           Head of household (Check only if you’re unmarried and pay more than half the costs of keeping up a home for yourself and a qualifying individual.)
                                            <input type="radio" name="marriage_status" value="single" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'single' ? 'checked="checked"': ''?>>
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
                                                  <p>Complete this step if you (1) hold more than one job at a time, or (2) are married filing jointly and your spouse
                                                     also works. The correct amount of withholding depends on income earned from all of these jobs.</p>
                                                  <p>Do <b>only one</b> of the following.</p>
                                                   <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <b>(a) </b>Use the estimator at www.irs.gov/W4App for most accurate withholding for this step (and Steps 3–4)</b>
                                                                    <input type="radio" name="marriage_status" value="single" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'single' ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                     <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                (b) Use the Multiple Jobs Worksheet on page 3 and enter the result in Step 4(c) below for roughly accurate withholding
                                                                    <input type="radio" name="marriage_status" value="single" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'single' ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <b>(c) </b>If there are only two jobs total, you may check this box. Do the same on Form W-4 for the other job. This option
                                                                           is accurate for jobs with similar pay; otherwise, more tax than necessary may be withheld
                                                                    <input type="radio" name="marriage_status" value="single" <?php echo sizeof($pre_form)>0 && $pre_form['marriage_status'] == 'single' ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                  <p><b>TIP:</b> To be accurate, submit a 2020 Form W-4 for all other jobs. If you (or your spouse) have self-employment
                                                     income, including as an independent contractor, use the estimator.</p>
                                                  <p><b>Complete Steps 3–4(b) on Form W-4 for only ONE of these jobs. </b>Leave those steps blank for the other jobs. (Your withholding will
                                                       be most accurate if you complete Steps 3–4(b) on the Form W-4 for the highest paying job.)</p>
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
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="qualifying_children" class="form-control" />
                                                 </div>
                                                  <div>
                                                    <p>Multiply the number of other dependents by $500 </p>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="other_dependents" class="form-control" />
                                                 </div>
                                                  <div>
                                                     <p>Add the amounts above and enter the total here</p>
                                                     <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="total_amount" class="form-control" />
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
                                                     <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="other_income" class="form-control" />
                                                  </div>
                                                  <div>
                                                     <p><b>(b) Deductions</b>. If you expect to claim deductions other than the standard deduction
                                                            and want to reduce your withholding, use the Deductions Worksheet on page 3 and
                                                            enter the result here . .</p>
                                                     <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="worksheet_deductions" class="form-control" />
                                                  </div>
                                                   <div>
                                                     <p>Extra withholding. Enter any additional tax you want withheld each pay period </p>
                                                     <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="additional_tax" class="form-control" />
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
                                                 <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                      <span><b>Employee's signature</b> (This form is not valid unless you sign it.)</span>
                                                            <?php if($signed_flag == true) { ?>
                                                                <img style="max-height: <?= SIGNATURE_MAX_HEIGHT?>;" src="<?php echo $pre_form['signature_bas64_image']; ?>"  />
                                                            <?php } else { ?>
                                                                <!-- the below loaded view add e-signature -->
                                                                <?php $this->load->view('static-pages/e_signature_button'); ?>
                                                            <?php } ?>  
                                                 </div>
                                                 <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                      <span><b>Date</b></span>
                                                      <input type="text" name="signature_date" class="form-control first_date_of_employment cs_date_setting"/>
                                                 </div>
                                             </div>
                                        </div>
                                </div>
                        </div>
                        
                         <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                        <div class="panel-heading incident-panal-heading">
                                            <b>Employers Only</b>
                                        </div>
                                        <div class="panel-body">
                                             <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label>Employer’s name</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="emp_name" class="form-control" />
                                                </div>
                                             </div>
                                              <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                        <label>Employer’s Address</label>
                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="emp_address" class="form-control" />
                                                </div>
                                              </div>
                                              <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                        <label>First date of employment</label>
                                                        <input type="text" name="signature_date" class="form-control first_date_of_employment "/>
                                                </div>
                                              </div>
                                              <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <label>Employer identification number (EIN)</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="EIN" class="form-control" />
                                                </div>
                                             </div>
                                        </div>
                                </div>
                         </div>
                            <div>
                                <p><b>For Privacy Act and Paperwork Reduction Act Notice, see page 3</b></p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-blue">
                                        <div class="panel-heading incident-panal-heading">
                                            <b>Step 2(b)—Multiple Jobs Worksheet (Keep for your records.)</b>
                                        </div>
                                        <div class="panel-body">
                                             <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <p>If you choose the option in Step 2(b) on Form W-4, complete this worksheet (which calculates the total extra tax for all jobs) on<b> only ONE</b>
                                                    Form W-4. Withholding will be most accurate if you complete the worksheet and enter the result on the Form W-4 for the highest paying job.</p>
                                                <p><b>Note:</b>If more than one job has annual wages of more than $120,000 or there are more than three jobs, see Pub. 505 for additional
                                                   tables; or, you can use the online withholding estimator at www.irs.gov/W4App.</p>
                                                   <div>
                                                   
                                                        <div>
                                                            <div class="form-group">
                                                                <p> <b>1</b>&nbsp Two jobs. If you have two jobs or you’re married filing jointly and you and your spouse each have one
                                                                job, find the amount from the appropriate table on page 4. Using the “Higher Paying Job” row and the
                                                                “Lower Paying Job” column, find the value at the intersection of the two household salaries and enter
                                                                that value on line 1. Then, skip to line 3 </p>
                                                                <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="additional_tax" class="form-control" />
                                                                </div>
                                                            </div>
                                                        <div>
                                                            <p><b>2&nbsp; Three jobs.</b> If you and/or your spouse have three jobs at the same time, complete lines 2a, 2b, and
                                                            2c below. Otherwise, skip to line 3</p>
                                                        </div>
                                                               <div>
                                                                    <div class="form-group">
                                                                        &nbsp;&nbsp; &nbsp;&nbsp;<p><b>a</b>&nbsp;
                                                                                Find the amount from the appropriate table on page 4 using the annual wages from the highest
                                                                                paying job in the “Higher Paying Job” row and the annual wages for your next highest paying job
                                                                                in the “Lower Paying Job” column. Find the value at the intersection of the two household salaries
                                                                                and enter that value on line 2a <p>
                                                                                <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="additional_tax" class="form-control" />
                                                                        </div>
                                                                <div>
                                                                   <div class="form-group">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;<p><b>b</b>&nbsp;
                                                                        Add the annual wages of the two highest paying jobs from line 2a together and use the total as the
                                                                        wages in the “Higher Paying Job” row and use the annual wages for your third job in the “Lower
                                                                        Paying Job” column to find the amount from the appropriate table on page 4 and enter this amount
                                                                        on line 2b </p>
                                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="additional_tax" class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                <div class="form-group">
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;<p><b>c</b>&nbsp;
                                                                        Add the amounts from lines 2a and 2b and enter the result on line 2c</p>
                                                                        <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="additional_tax" class="form-control" /> 
                                                                 </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <div>
                                                         <div class="form-group">
                                                             <p><b>3&nbsp;&nbsp;</b>Enter the number of pay periods per year for the highest paying job. For example, if that job pays
                                                                weekly, enter 52; if it pays every other week, enter 26; if it pays monthly, enter 12, etc. </p>
                                                                <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="additional_tax" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                        <div class="form-group">
                                                                <p><b>4 &nbsp;Divide</b> the annual amount on line 1 or line 2c by the number of pay periods on line 3. Enter this
                                                                amount here and in <b>Step 4(c)</b> of Form W-4 for the highest paying job (along with any other additional
                                                                amount you want withheld) </p>
                                                                <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['state']: ''?>" name="additional_tax" class="form-control" />
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
                                                    <label>1. Enter an estimate of your 2020 itemized deductions (from Schedule A (Form 1040 or 1040-SR)). Such
                                                            deductions may include qualifying home mortgage interest, charitable contributions, state and local
                                                            taxes (up to $10,000), and medical expenses in excess of 7.5% of your income</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_estimate']: ''?>" name="daaiw_estimate" class="form-control" />
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
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_enter_status']: ''?>" name="daaiw_enter_status" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>3. If line 1 is greater than line 2, subtract line 2 from line 1. If line 2 is greater than line 1, enter “-0-” </label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_subtract_line_2']: ''?>" name="daaiw_subtract_line_2" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>4. Enter an estimate of your <?php echo date('Y'); ?> adjustments to income and any additional standard deduction for age or blindness (see Pub. 505 for information about these items) </label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_estimate_of_adjustment']: ''?>" name="daaiw_estimate_of_adjustment" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>5. Add lines 3 and 4. Enter the result here and in Step 4(b) of Form W-4</label>
                                                    <input type="text" value="<?php echo sizeof($pre_form)>0 ? $pre_form['daaiw_add_line_3_4']: ''?>" name="daaiw_add_line_3_4" class="form-control" />
                                                </div>
                                            </div>
                                             </div>
                                        </div>
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
                                    <div class="col-xs-12">
                                        <?php $consent = isset($e_signature_data['user_consent']) ? $e_signature_data['user_consent'] : 0; ?>
                                        <label class="control control--checkbox">
                                            <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                            <input <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?>  <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo sizeof($pre_form)>0 && $pre_form['user_consent'] == 1 ? 'disabled="disabled"' : '' ?>/>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <hr />

                                <?php if( $pre_form['user_consent'] == 0) { ?>
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <button <?php //echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text" <?php echo sizeof($pre_form)>0 && $pre_form['user_consent'] == 1 ? 'disabled="disabled"' : '' ?>><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php //} ?>
                    </form>
                </div>
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
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript">
    $("#w4-form").validate({
        ignore: ":hidden:not(select)",
        rules: {
            w4_first_name: {
                required: true
            },
            w4_middle_name: {
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
            w4_middle_name: {
                required: 'Middle Name is required'
            },
            user_consent: {
                required: 'Please check to accept the consent'
            },
            number_of_allowance: {
                required: 'Number of Allowance is required'
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

        $('.first_date_of_employment').datepicker({
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
<style>
.cs_date_setting{
    display: inline-block;
    width:60%;
}
</style>