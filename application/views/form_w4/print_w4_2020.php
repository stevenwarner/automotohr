<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 { margin: 0 0 10px; font-family: 'Source Sans Pro', sans-serif !important; }
        p,
        a,
        span,
        table,
        tr,
        td,
        th { font-family: 'Source Sans Pro'; }
        .text-right { text-align: right; }
        .row { clear: both; }
        .row::after,
        .row::before { display: table; content: " "; }
        * { box-sizing: border-box; }
        /*.row{ margin:0 -15px;}*/
        body { font-family: 'Source Sans Pro' !important; }
        .main-wrapper-pdf { font-family: 'Source Sans Pro' !important; }
        .main-page-heading h1 { font-size: 22px !important; font-weight: bold; margin-bottom: 20px; margin-top: 30px; }
        .footer-heading h1 { font-size: 22px !important; font-weight: bold; margin-bottom: 30px; margin-top: 30px; }
        .col-md-4 { width: 33.33333333%; }
        .col-md-12,
        .col-lg-12 { width: 100%; }
        .clearfix { clear: both; overflow: hidden; }
        .col-lg-1,
        .col-lg-10,
        .col-lg-11,
        .col-lg-12,
        .col-lg-2,
        .col-lg-3,
        .col-lg-4,
        .col-lg-5,
        .col-lg-6,
        .col-lg-7,
        .col-lg-8,
        .col-lg-9,
        .col-md-1,
        .col-md-10,
        .col-md-11,
        .col-md-12,
        .col-md-2,
        .col-md-3,
        .col-md-4,
        .col-md-5,
        .col-md-6,
        .col-md-7,
        .col-md-8,
        .col-md-9,
        .col-sm-1,
        .col-sm-10,
        .col-sm-11,
        .col-sm-12,
        .col-sm-2,
        .col-sm-3,
        .col-sm-4,
        .col-sm-5,
        .col-sm-6,
        .col-sm-7,
        .col-sm-8,
        .col-sm-9,
        .col-xs-1,
        .col-xs-10,
        .col-xs-11,
        .col-xs-12,
        .col-xs-2,
        .col-xs-3,
        .col-xs-4,
        .col-xs-5,
        .col-xs-6,
        .col-xs-7,
        .col-xs-8,
        .col-xs-9 { float: left; padding-right: 15px; padding-left: 15px; }
        .text-center { text-align: center; }
        .icon-col img { display: block; }
        .table { width: 100%; max-width: 100%; background-color: transparent; }
        .table > thead > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > th,
        .table > tbody > tr > td { vertical-align: top; font-size: 11px; padding: 5px; }
        .table-striped > tbody > tr:nth-of-type(2n+1) { background-color: #f9f9f9; }
        #footer-table tbody tr td{ padding:5px;}
        .pdf-page-break{ page-break-inside:avoid; page-break-after:always; }
        .default-border-disable{border:0 none !important}
        .col-lg-2 {
            width: 16.66666667%;
        }
        .col-lg-1 {
            width: 8.33333333%;
        }
       /* .col-lg-9 {
            width: 75%;
        }*/
        .col-lg-10 {
            width: 83.33333333%;
        }
        .heading-text{
            font-weight: bold;
            font-size: 14px;
        }
        .text-bold{
            font-weight: bold;
        }
        .border-right-bottom-bold{
            border-right: 2px solid #000;
            border-bottom: 2px solid #000;
        }
        .border-bottom-bold{
            border-bottom: 2px solid #000;
        }
        .border-right-bottom{
            border-right: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .border-bottom{
            border-bottom: 1px solid #000;
        }
        .border-right{
            border-right: 1px solid #000;
        }
        .table-border-collapse{
            border-collapse: collapse;
        }
        .plane-input{
            border: none;
            font-weight: bold;
            display: block;
        }
        .input-with-bottom-border{
            border: none;
            border-bottom: 1px solid #000;
            width: 100%;
            height: 20px;
        }
        .main-heading{
            font-size: 26px;
            border-bottom: 2px solid #000;
        }
        .bordered-table{
            border: 2px solid #000;
        }
        .indicator-box{
            display: inline-block;
            margin: 5px 0 0 0;
        }
        .indicator-box-2{
            display: inline-block;
            margin: 9px 0 0 0;
        }
        .row {
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-lg-6 {
            width: 50%;
        }
        .display-block{
            display: block;
            height: 20px;
        }
        .signature{
            font-size: 26px;
            font-family: 'Conv_SCRIPTIN' !important;
        }
    </style>
</head>
<body class="A4">
    <div class="sheet padding-10mm" id="w4_pdf" style="width: 800px; margin: 0 auto;">
        <section class="pdf-cover-page" >
            <table class="table-border-collapse">
                <tbody>
                    <tr>
                        <td class="border-right-bottom-bold" style="width: 20%;">
                            Form <strong style="font-size: 32px;">W-4</strong><br>
                            Department of the Treasury<br> Internal Revenue Service
                        </td>
                        <td class="text-center border-right-bottom-bold">
                            <strong style="font-size: 20px;">Employee’s Withholding Certificate</strong><br>
                            <strong style="font-size: 10px;">▶ Complete Form W-4 so that your employer can withhold the correct federal income tax from your pay.</strong><br>
                            <strong style="font-size: 10px;">▶ Give Form W-4 to your employer.</strong><br>
                            <strong style="font-size: 10px;">▶  Your withholding is subject to review by the IRS.</strong><br>
                        </td>
                        <td class="border-bottom-bold" style="width: 20%; text-align: center;">
                            <div class="border-bottom">OMB No. 1545-0074</div>
                            <strong style="font-size: 32px;"><?php echo date('Y'); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page" >
            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                <tbody>
                    <tr>
                        <td class="border-right-bottom" style="width: 15%;">
                            <strong style="font-size: 15px;">Step 1: <br>Enter <br>Personal <br>Information</strong>
                        </td>
                        <td class="border-right-bottom">
                            <div style="width: 100%;" class="border-bottom">
                                <div style="display:inline-block;">
                                    <span><strong>(a)</strong> First name and middle initial</span>
                                    <input class="plane-input" type="text" value="<?php echo !empty($pre_form['first_name']) ? $pre_form['first_name']: ''?> <?php echo !empty($pre_form['middle_name']) ? $pre_form['middle_name']: ''?>" readonly style="border: none; font-weight: bold; display: block;"/>
                                </div>
                                <div style="width: 50%; float:right; border-left: 1px solid #000; padding-left: 5px;">
                                    <span>Last name</span>
                                    <input class="plane-input" type="text" value="<?php echo !empty($pre_form['last_name']) ? $pre_form['last_name']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                                </div>
                            </div>
                            <div style="width: 100%;" class="border-bottom">
                                <span>Address</span>
                                <input class="plane-input" type="text" value="<?php echo !empty($pre_form['home_address']) ? $pre_form['home_address']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                            </div> 
                            <div style="width: 100%;" class="border-bottom">
                                <span>City or town, state, and ZIP code</span>
                                <input class="plane-input" type="text" value="<?php echo !empty($pre_form['city']) ? $pre_form['city'].',': ''?> <?php echo !empty($pre_form['state']) ? $pre_form['state'].',': ''?> <?php echo !empty($pre_form['zip']) ? $pre_form['zip']: ''?> " readonly style="display: block; width: 100%; border: none; font-weight: bold; display: block;"/>
                            </div> 
                            <div style="width: 100%;">
                                <div style="display:inline-block;">
                                    <span><strong>(c)</strong></span>
                                </div>
                                <div style="width: 95%; float:right; padding-left: 5px;">
                                   <div><input type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'separately' ? 'checked="checked"': ''?> /><strong>Single or Married filing separately</strong></div>
                                   <div><input type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'jointly' ? 'checked="checked"': ''?> /><strong>Married filing jointly</strong> (or Qualifying widow(er))</div>
                                   <div><input type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'head' ? 'checked="checked"': ''?> /><strong>Head of household</strong> (Check only if you’re unmarried and pay more than half the costs of keeping up a home for yourself and a qualifying individual.)</div>
                                </div>
                            </div>  
                        </td>
                        <td class="border-bottom" style="width: 22%;">
                            <div class="border-bottom">
                                <strong><span>(b) Social security number</span></strong>
                                <input class="plane-input" type="text" value="<?php echo !empty($pre_form['ss_number']) ? $pre_form['ss_number']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                            </div>
                            <div>
                                <strong style="font-size: 8px;">▶ Does your name match the name on your social security card?</strong><p>If not, to ensure you get credit for your earnings, contact SSA at 800-772-1213 or go to www.ssa.gov.</p>
                            </div>
                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page" >
            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                <tbody>            
                    <tr>
                        <td colspan="3" class="border-bottom">
                            <strong>Complete Steps 2–4 ONLY if they apply to you; otherwise, skip to Step 5.</strong>See page 2 for more information on each step, who can claim exemption from withholding, when to use the online estimator, and privacy.
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page" >
            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                <tbody>
                    <tr>
                        <td style="width: 20%;">
                            <strong style="font-size: 15px;">Step 2: <br>Multiple Jobs <br>or Spouse <br>Works</strong>
                        </td>
                        <td>
                            <p>Complete this step if you (1) hold more than one job at a time, or (2) are married filing jointly and your spouse also works. The correct amount of withholding depends on income earned from all of these jobs.</p>
                            <p>Do <strong>only one</strong> of the following.</p>
                            <p><strong>(a)</strong> Use the estimator at www.irs.gov/W4App for most accurate withholding for this step (and Steps 3–4); <strong>or</strong></p>
                            <p><strong>(b)</strong> Use the Multiple Jobs Worksheet on page 3 and enter the result in Step 4(c) below for roughly accurate withholding; <strong>or</strong></p>
                            <p><strong>(c)</strong> If there are only two jobs total, you may check this box. Do the same on Form W-4 for the other job. This option is accurate for jobs with similar pay; otherwise, more tax than necessary may be withheld . . . . . ▶ <input type="checkbox" value="" readonly <?php echo !empty($pre_form['mjsw_status']) && isset($pre_form['marriage_status']) ? 'checked="checked"': ''?> /></p>
                            <br>
                            <p><strong>TIP:</strong> To be accurate, submit a <?php echo W4_YEAR; ?> Form W-4 for all other jobs. If you (or your spouse) have self-employmentincome, including as an independent contractor, use the estimator.</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border-bottom">
                            <strong>Complete Steps 3–4(b) on Form W-4 for only ONE of these jobs.</strong> Leave those steps blank for the other jobs. (Your withholding will be most accurate if you complete Steps 3–4(b) on the Form W-4 for the highest paying job.)
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page" >   
            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                <tbody>     
                    <tr>
                        <td style="width: 20%;" class="border-bottom">
                            <strong style="font-size: 15px;">Step 3: <br>Claim <br>Dependents</strong>
                        </td>
                        <td class="border-bottom">
                            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                                <tbody>
                                    <tr>
                                        <td>
                                            If your income will be $200,000 or less ($400,000 or less if married filing jointly):
                                        </td>
                                        <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            
                                        </td>
                                        <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td>
                                            &nbsp;&nbsp;Multiply the number of qualifying children under age 17 by $2,000 ▶ <input type="text" value="<?php echo isset($pre_form['dependents_children']) ? $pre_form['dependents_children']: ''?>" readonly  style="border: none; font-weight: bold; width: 50px;"/>
                                        </td>
                                        <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            
                                        </td>
                                        <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            
                                        </td>
                                    </tr>  
                                    <tr>
                                        <td>
                                            &nbsp;&nbsp;Multiply the number of other dependents by $500 . . . . ▶ <input type="text" value="<?php echo isset($pre_form['other_dependents']) ? $pre_form['other_dependents']: ''?>" readonly  style="border: none; font-weight: bold; width: 50px;"/>
                                        </td>
                                        <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            
                                        </td>
                                        <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Add the amounts above and enter the total here . . . . . . . . . . . . .
                                        </td>
                                        <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            <strong>3</strong>
                                        </td>
                                        <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['claim_total_amount']) ? '$ '.$pre_form['claim_total_amount']: ''?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>          
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <section class="pdf-cover-page">    
            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                <tbody>     
                    <tr>
                        <td style="width: 20%;"  class="border-bottom">
                            <strong style="font-size: 15px;">Step 4<br> (optional): <br>Other <br>Adjustments</strong>
                        </td>
                        <td  class="border-bottom">
                            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>(a) Other income (not from jobs).</strong> If you want tax withheld for other income you expect this year that won’t have withholding, enter the amount of other income here. This may include interest, dividends, and retirement income . . . . . . . . . . . .
                                        </td>
                                        <td style="width: 6%; border-left: 1px solid #000; border-bottom: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            <strong>4(a)</strong>
                                        </td>
                                        <td style="width: 16%; border-left: 1px solid #000; border-bottom: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['other_income']) ? '$ '.$pre_form['other_income']: ''?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/>
                                        </td>
                                    </tr>    
                                    <tr>
                                        <td>
                                        <strong>(b) Deductions.</strong> If you expect to claim deductions other than the standard deduction and want to reduce your withholding, use the Deductions Worksheet on page 3 and enter the result here . . . . . . . . . . . . . . . . . . . . .
                                        </td>
                                        <td style="width: 6%; border-left: 1px solid #000; border-bottom: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            <strong>4(b)</strong>
                                        </td>
                                        <td style="width: 16%; border-left: 1px solid #000; border-bottom: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['other_deductions']) ? '$ '.$pre_form['other_deductions']: ''?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>(c) Extra withholding.</strong>  Enter any additional tax you want withheld each pay period .
                                        </td>
                                        <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            <strong>4(c)</strong>
                                        </td>
                                        <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['additional_tax']) ? '$ '.$pre_form['additional_tax']: ''?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <section class="pdf-cover-page">    
            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                <tbody>
                    <tr>
                        <td class="border-right-bottom" style="width: 15%;">
                            <strong style="font-size: 15px;">Step 5: <br>Sign <br>Here </strong>
                        </td>
                        <td class="border-bottom">
                            <table >
                                <tbody> 
                                    <tr>
                                        <td>
                                            Under penalties of perjury, I declare that this certificate, to the best of my knowledge and belief, is true, correct, and complete.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT?>;" src="<?php echo $pre_form['signature_bas64_image']; ?>" class="esignaturesize" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 15px;"><strong>Employee’s signature</strong> (This form is not valid unless you sign it.)</p>

                                        </td>
                                        <td style="width: 30%;">
                                            <strong style="font-size: 15px;">Date</strong>
                                            <span><input class="plane-input" type="text" value="<?php echo !empty($pre_form['signature_timestamp']) && $pre_form['signature_timestamp'] != '0000-00-00 00:00:00' ? reset_datetime(array('datetime' => $pre_form['signature_timestamp'], '_this' => $this)) : ''; ?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>        
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <section class="pdf-cover-page">    
            <table class="table table-border-collapse" style="margin-bottom: 0px;">
            <?php
                $companySid = $this->session->userdata('logged_in')['company_detail']['sid'];
                $employerPrefill = getDataForEmployerPrefill($companySid, $EmployeeSid);
                ?>

                <tbody>
                    <tr>
                        <td class="border-right-bottom" style="width: 15%;">
                            <strong style="font-size: 15px;">Employers  <br>Only </strong>
                        </td>
                        <td class="border-right-bottom"> 
                            Employer’s name and address
                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['emp_name']) ? $pre_form['emp_name']: $employerPrefill['CompanyName'];?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/> , <input class="plane-input" type="text" value="<?php echo !empty($pre_form['emp_address']) ? $pre_form['emp_address']: $employerPrefill['Location_Address'] .', '.$employerPrefill['Location_City'].', '.$employerPrefill['state_name'].', '.$employerPrefill['Location_ZipCode']; ?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/>      
                        </td>
                        <td style="width: 20%;" class="border-right-bottom">
                            First date of employment
                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['first_date_of_employment']) && $pre_form['first_date_of_employment'] != '0000-00-00 00:00:00' ? reset_datetime(array('datetime' => $pre_form['first_date_of_employment'], '_this' => $this)) : $employerPrefill['first_day_of_employment']; ?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/>
                        </td>
                        <td style="width: 30%;" class="border-bottom">
                            Employer identification number (EIN)
                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['emp_identification_number']) ? $pre_form['emp_identification_number']: $employerPrefill['ssn'];?>" readonly  style="border: none; font-weight: bold; display: inline-block;"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page" >
            <table class="table table-border-collapse" style="margin-bottom: 0px;">
                <tbody>
                    <tr>
                        <td style="width: 50%;">
                            <h3>General Instructions</h3>
                            <span class="heading-text">Future Developments</span><br> For the latest information about developments related to Form W-4, such as legislation enacted after it was published, go to www.irs.gov/FormW4.<br>
                            <span class="heading-text">Purpose of Form</span><br> Complete Form W-4 so that your employer can withhold the correct federal income tax from your pay. If too little is withheld, you will generally owe tax when you file your tax return and may owe a penalty. If too much is withheld, you will generally be due a refund. Complete a new Form W-4 when changes to your personal or financial situation would change the entries on the form. For more information on withholding and when you must furnish a new Form W-4, see Pub. 505.<br>
                            <span class="heading-text">Exemption from withholding.</span> <?php echo W4_EXEMPTION_FROM_WITHHOLDING; ?><br>
                            <span class="heading-text">Your privacy.</span> If you prefer to limit information provided in Steps 2 through 4, use the online estimator, which will also increase accuracy.<br>
                            &nbsp;&nbsp;As an alternative to the estimator: if you have concerns with Step 2(c), you may choose Step 2(b); if you have concerns with Step 4(a), you may enter an additional amount you want withheld per pay period in Step 4(c). If this is the only job in your household, you may instead check the box in Step 2(c), which will increase your withholding and significantly reduce your paycheck (often by thousands of dollars over the year).<br>
                            <span class="heading-text">When to use the estimator.</span> Consider using the estimator at www.irs.gov/W4App if you:<br>
                            1. Expect to work only part of the year; <br>
                            2. Have dividend or capital gain income, or are subject to additional taxes, such as the additional Medicare tax; <br>
                            3. Have self-employment income (see below); or <br>
                            4. Prefer the most accurate withholding for multiple job situations. <br>
                            <span class="heading-text">Self-employment.</span>  Generally, you will owe both income and self-employment taxes on any self-employment income you receive separate from the wages you receive as an employee. If you want to pay these taxes through withholding from your wages, use the estimator at www.irs.gov/W4App to figure the amount to have withheld. <br>
                            <span class="heading-text">Nonresident alien.</span>   If you’re a nonresident alien, see Notice 1392, Supplemental Form W-4 Instructions for Nonresident Aliens, before completing this form <br>
                        </td>
                        <td style="width: 50%;">
                            <h3>Specific Instructions</h3>
                            <span class="heading-text">Step 1(c).</span> Check your anticipated filing status. This will determine the standard deduction and tax rates used to compute your withholding.<br>
                            <span class="heading-text">Step 2. </span> Use this step if you (1) have more than one job at the same time, or (2) are married filing jointly and you and your spouse both work. .<br>
                            &nbsp;&nbsp;Option <strong>(a)</strong> most accurately calculates the additional tax you need to have withheld, while option <strong>(b)</strong> does so with a little less accuracy.<br>
                            &nbsp;&nbsp;If you (and your spouse) have a total of only two jobs, you may instead check the box in option (c). The box must also be checked on the Form W-4 for the other job. If the box is checked, the standard deduction and tax brackets will be cut in half for each job to calculate withholding. This option is roughly accurate for jobs with similar pay; otherwise, more tax than necessary may be withheld, and this extra amount will be larger the greater the difference in pay is between the two jobs<br>
                            ▲! CAUTION Multiple jobs. Complete Steps 3 through 4(b) on only one Form W-4. Withholding will be most accurate if you do this on the Form W-4 for the highest paying job. <br>
                            <span class="heading-text">Step 3.</span>  This step provides instructions for determining the amount of the child tax credit and the credit for other dependents that you may be able to claim when you file your tax return. To qualify for the child tax credit, the child must be under age 17 as of December 31, must be your dependent who generally lives with you for more than half the year, and must have the required social security number. You may be able to claim a credit for other dependents for whom a child tax credit can’t be claimed, such as an older child or a qualifying relative. For additional eligibility requirements for these credits, see Pub. 501, Dependents, Standard Deduction, and Filing Information. You can also include other tax credits for which you are eligible in this step, such as the foreign tax credit and the education tax credits. To do so, add an estimate of the amount for the year to your credits for dependents and enter the total amount in Step 3. Including these credits will increase your paycheck and reduce the amount of any refund you may receive when you file your tax return.<br>
                            <span class="heading-text">Step 4 (optional).</span><br>
                            <span class="heading-text">Step 4(a).</span>  Enter in this step the total of your other estimated income for the year, if any. You shouldn’t include income from any jobs or self-employment. If you complete Step 4(a), you likely won’t have to make estimated tax payments for that income. If you prefer to pay estimated tax rather than having tax on other income withheld from your paycheck, see Form 1040-ES, Estimated Tax for Individuals.<br>
                            <span class="heading-text">Step 4 (optional).</span><br>
                            <span class="heading-text">Step 4(b).</span>  Enter in this step the amount from the Deductions Worksheet, line 5, if you expect to claim deductions other than the basic standard deduction on your <?php echo W4_YEAR; ?> tax return and want to reduce your withholding to account for these deductions. This includes both itemized deductions and other deductions such as for student loan interest and IRAs.<br>
                            <span class="heading-text">Step 4(c).</span>  Enter in this step any additional tax you want withheld from your pay each pay period, including any amounts from the Multiple Jobs Worksheet, line 4. Entering an amount here will reduce your paycheck and will either increase your refund or reduce any amount of tax that you owe<br>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table bordered-table table-border-collapse" id="footer-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center border-bottom">
                                <span class="heading-text">Step 2(b)—Multiple Jobs Worksheet (Keep for your records.)</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center border-bottom">
                                If you choose the option in Step 2(b) on Form W-4, complete this worksheet (which calculates the total extra tax for all jobs) on <strong>only ONE</strong> Form W-4. Withholding will be most accurate if you complete the worksheet and enter the result on the Form W-4 for the highest paying job.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center border-bottom">
                               <strong>Note:</strong> If more than one job has annual wages of more than $120,000 or there are more than three jobs, see Pub. 505 for additional tables; or, you can use the online withholding estimator at www.irs.gov/W4App.
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">1</strong>
                            </td>
                            <td width="86%">
                                <strong>Two jobs.</strong> If you have two jobs or you’re married filing jointly and you and your spouse each have one job, find the amount from the appropriate table on page 4. Using the “Higher Paying Job” row and the “Lower Paying Job” column, find the value at the intersection of the two household salaries and enter that value on line 1. Then, skip to line 3 . . . . . . . . . . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">1</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_two_jobs']) ? $pre_form['mjw_two_jobs']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">2</strong>
                            </td>
                            <td width="86%">
                                <strong>Three jobs.</strong> If you and/or your spouse have three jobs at the same time, complete lines 2a, 2b, and 2c below. Otherwise, skip to line 3.
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2"></strong>
                            </td>
                            <td width="10%">
                                
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">a</strong>
                            </td>
                            <td width="86%">
                                Find the amount from the appropriate table on page 4 using the annual wages from the highest paying job in the “Higher Paying Job” row and the annual wages for your next highest paying job in the “Lower Paying Job” column. Find the value at the intersection of the two household salaries and enter that value on line 2a . . . . . . . . . . . . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">2a</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_three_jobs_a']) ? $pre_form['mjw_three_jobs_a']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">b</strong>
                            </td>
                            <td width="86%">
                                Add the annual wages of the two highest paying jobs from line 2a together and use the total as the wages in the “Higher Paying Job” row and use the annual wages for your third job in the “Lower Paying Job” column to find the amount from the appropriate table on page 4 and enter this amount on line 2b . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">2b</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_three_jobs_b']) ? $pre_form['mjw_three_jobs_b']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">c</strong>
                            </td>
                            <td width="86%">
                                Add the amounts from lines 2a and 2b and enter the result on line 2c . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">2c</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_three_jobs_c']) ? $pre_form['mjw_three_jobs_c']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">3</strong>
                            </td>
                            <td width="86%">
                                Enter the number of pay periods per year for the highest paying job. For example, if that job pays weekly, enter 52; if it pays every other week, enter 26; if it pays monthly, enter 12, etc. . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">3</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_pp_py']) ? $pre_form['mjw_pp_py']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">4</strong>
                            </td>
                            <td width="86%">
                                <strong>Divide</strong> the annual amount on line 1 or line 2c by the number of pay periods on line 3. Enter this amount here and in <strong>Step 4(c)</strong> of Form W-4 for the highest paying job (along with any other additional amount you want withheld) . . . . . . . . . . . . . . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">4</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_divide']) ? $pre_form['mjw_divide']: ''?>" readonly />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table bordered-table table-border-collapse" id="footer-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center border-bottom">
                                <span class="heading-text">Step 4(b)—Deductions Worksheet (Keep for your records.)</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">1</strong>
                            </td>
                            <td width="86%">
                                Enter an estimate of your <?php echo W4_YEAR; ?> itemized deductions (from Schedule A (Form 1040 or 1040-SR)). Such deductions may include qualifying home mortgage interest, charitable contributions, state and local taxes (up to $10,000), and medical expenses in excess of 7.5% of your income . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">1</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_1']) ? $pre_form['dw_input_1']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">2</strong>
                            </td>
                            <td width="86%">
                                Enter:<br>
                                    $24,800 if you’re married filing jointly or qualifying widow(er)<br>
                                    $18,650 if you’re head of household<br>
                                    $12,400 if you’re single or married filing separately
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">2</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_2']) ? $pre_form['dw_input_2']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">3</strong>
                            </td>
                            <td width="86%">
                                If line 1 is greater than line 2, subtract line 2 from line 1. If line 2 is greater than line 1, enter “-0-” . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">3</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_3']) ? $pre_form['dw_input_3']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">4</strong>
                            </td>
                            <td width="86%">
                                Enter an estimate of your student loan interest, deductible IRA contributions, and certain other adjustments (from Part II of Schedule 1 (Form 1040 or 1040-SR)). See Pub. 505 for more information 
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">4</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_4']) ? $pre_form['dw_input_4']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">5</strong>
                            </td>
                            <td width="86%">
                                <strong>Add</strong> lines 3 and 4. Enter the result here and in <strong>Step 4(b)</strong> of Form W-4 . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">5</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_5']) ? $pre_form['dw_input_5']: ''?>" readonly />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table" id="footer-table">
                    <tbody>
                        <tr>
                            <td style="width: 50%;">
                                <span class="heading-text">Privacy Act and Paperwork Reduction Act Notice. </span> We ask for the information on this form to carry out the Internal Revenue laws of the United States. Internal Revenue Code sections 3402(f)(2) and 6109 and their regulations require you toprovide this information; your employer uses it to determine your federal income tax withholding. Failure to provide a properly completed form will result in your being treated as a single person with no other entries on the form; providing fraudulent information may subject you to penalties. Routine uses of this information include giving it to the Department of Justice for civil and criminal litigation; to cities, states, the District of Columbia, and U.S. commonwealths and possessions for use in administering their tax laws; and to the Department of Health and Human Services for use in the National Directory of New Hires. We may also disclose this information to other countries under a tax treaty, to federal and state agencies to enforce federal nontax criminal laws, or to federal law enforcement and intelligence agencies to combat terrorism.
                            </td>
                            <td style="width: 50%;">
                                &nbsp;&nbsp;You are not required to provide the information requested on a form that is subject to the Paperwork Reduction Act unless the form displays a valid OMB control number. Books or records relating to a form or its instructions must be retained as long as their contents may become material in the administration of any Internal Revenue law. Generally, tax returns and return information are confidential, as required by Code section 6103.<br> 
                                &nbsp;&nbsp;The average time and expenses required to complete and file this form will vary depending on individual circumstances. For estimated averages, see the instructions for your income tax return.<br>
                                &nbsp;&nbsp;If you have suggestions for making this form simpler, we would be happy to hear from you. See the instructions for your income tax return.<br>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table bordered-table table-border-collapse" id="footer-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center border-bottom">
                                <span class="heading-text">Married Filing Jointly or Qualifying Widow(er)</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center border-bottom">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr> 
                                            <th rowspan="2" class="cs_th_setting" >Higher Paying Job Annual Taxable Wage & Salary</th> 
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
                                            <td class="cs_td_setting">$0 - 9,999<br/>
                                                $10,000 - 19,999<br/>
                                                $20,000 - 29,999</td>
                                            <td class="cs_td_setting">$0<br/>
                                                220<br/>
                                                850 </td> 
                                            <td class="cs_td_setting">$220<br/>
                                                1,220<br/>
                                                1,900 </td> 
                                            <td class="cs_td_setting">$850<br/>
                                                1,900<br/>
                                                2,730 </td>
                                            <td class="cs_td_setting">$900<br/>
                                                2,100<br/>
                                                2,930 </td>
                                        <td class="cs_td_setting">$1,020<br/>
                                                2,220<br/>
                                                3,050 </td>
                                        <td class="cs_td_setting">$1,020<br/>
                                                2,220<br/>
                                                3,050 </td>
                                            <td class="cs_td_setting">$1,020<br/>
                                                2,220<br/>
                                                3,050 </td>
                                            <td class="cs_td_setting">$1,020<br/>
                                                2,220<br/>
                                                3,240 </td>
                                            <td class="cs_td_setting">$1,020<br/>
                                                2,410<br/>
                                                4,240</td>
                                            <td class="cs_td_setting">$1,210<br/>
                                                3,410<br/>
                                                5,240</td>
                                            <td class="cs_td_setting">$1,870<br/>
                                                4,070<br/>
                                                5,900 </td>
                                            <td class="cs_td_setting">$1,870<br/>
                                                4,070<br/>
                                                5,900 </td>
                                            
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$30,000 - 39,999<br/>
                                                $40,000 - 49,999<br/>
                                                $50,000 - 59,999</td>
                                            <td class="cs_td_setting">900<br/>
                                                1,020<br/>
                                                1,020</td> 
                                            <td class="cs_td_setting">2,100<br/>
                                                2,220<br/>
                                                2,220</td> 
                                            <td class="cs_td_setting">2,930<br/>
                                                3,050<br/>
                                                3,050</td>
                                            <td class="cs_td_setting">3,130<br/>
                                                3,250<br/>
                                                3,250</td>
                                        <td class="cs_td_setting">3,250<br/>
                                                3,370<br/>
                                                3,570 </td>
                                        <td class="cs_td_setting">3,250<br/>
                                                3,570<br/>
                                                4,570</td>
                                            <td class="cs_td_setting">3,440<br/>
                                                4,570<br/>
                                                5,570 </td>
                                            <td class="cs_td_setting">4,440<br/>
                                                5,570<br/>
                                                6,570</td>
                                            <td class="cs_td_setting">5,440<br/>
                                                6,570<br/>
                                                7,570</td>
                                            <td class="cs_td_setting">6,440<br/>
                                                7,570<br/>
                                                8,570</td>
                                            <td class="cs_td_setting">7,100<br/>
                                                8,220<br/>
                                                9,220  </td>
                                            <td class="cs_td_setting">7,100<br/>
                                                8,220<br/>
                                                9,220</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$60,000 - 69,999<br/>
                                                $70,000 - 79,999<br/>
                                                $80,000 - 99,999</td>
                                            <td class="cs_td_setting">1,020<br/>
                                                1,020<br/>
                                                1,060</td> 
                                            <td class="cs_td_setting">2,220<br/>
                                                2,220<br/>
                                                3,260 </td> 
                                            <td class="cs_td_setting">3,050<br/>
                                                3,240<br/>
                                                5,090</td>
                                            <td class="cs_td_setting">3,440<br/>
                                                4,440<br/>
                                                6,290</td>
                                        <td class="cs_td_setting">4,570<br/>
                                                5,570<br/>
                                                7,420</td>
                                        <td class="cs_td_setting">5,570<br/>
                                                6,570<br/>
                                            8,420</td>
                                            <td class="cs_td_setting">6,570<br/>
                                                7,570<br/>
                                                9,420</td>
                                            <td class="cs_td_setting">7,570<br/>
                                                8,570<br/>
                                                10,420</td>
                                            <td class="cs_td_setting">8,570<br/>
                                                9,570<br/>
                                                11,420</td>
                                            <td class="cs_td_setting">9,570<br/>
                                                10,570<br/>
                                                12,420</td>
                                            <td class="cs_td_setting">10,220<br/>
                                                11,220<br/>
                                                13,260</td>
                                            <td class="cs_td_setting">10,220<br/>
                                                11,240<br/>
                                                13,460</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting"> $100,000 - 149,999<br/>
                                                $150,000 - 239,999<br/>
                                                $240,000 - 259,999</td>
                                            <td class="cs_td_setting">1,870<br/>
                                                1,020<br/>
                                                2,040</td> 
                                            <td class="cs_td_setting">4,070<br/>
                                                4,440<br/>
                                                4,440 </td> 
                                            <td class="cs_td_setting">5,900<br/>
                                                6,470<br/>
                                                6,470</td>
                                            <td class="cs_td_setting">7,100<br/>
                                                7,870<br/>
                                                7,870</td>
                                        <td class="cs_td_setting">8,220<br/>
                                                9,190<br/>
                                                9,190</td>
                                        <td class="cs_td_setting"> 9,320<br/>
                                                10,390<br/>
                                            10,390</td>
                                            <td class="cs_td_setting">10,520<br/>
                                                11,590<br/>
                                                11,590</td>
                                            <td class="cs_td_setting">11,720<br/>
                                                12,790<br/>
                                            12,790</td>
                                            <td class="cs_td_setting">12,920<br/>
                                                13,990<br/>
                                                13,990</td>
                                            <td class="cs_td_setting">14,120<br/>
                                                15,190<br/>
                                                15,520</td>
                                            <td class="cs_td_setting">14,980<br/>
                                                16,050<br/>
                                                17,170</td>
                                            <td class="cs_td_setting">15,180<br/>
                                                16,250<br/>
                                                18,170</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$260,000 - 279,999<br/>
                                                $280,000 - 299,999<br/>
                                                $300,000 - 319,999</td>
                                            <td class="cs_td_setting">2,040<br/>
                                                2,040<br/>
                                                2,040</td> 
                                            <td class="cs_td_setting">4,440<br/>
                                                4,440<br/>
                                                4,440 </td> 
                                            <td class="cs_td_setting">6,470<br/>
                                                6,470<br/>
                                                6,470</td>
                                            <td class="cs_td_setting">7,870<br/>
                                                7,870<br/>
                                                8,200</td>
                                            <td class="cs_td_setting">9,190<br/>
                                                9,190<br/>
                                            10,320</td>
                                            <td class="cs_td_setting">10,390<br/>
                                                11,720<br/>
                                                12,320</td>
                                            <td class="cs_td_setting">11,590<br/>
                                                12,720<br/>
                                            14,320</td>
                                            <td class="cs_td_setting">13,120<br/>
                                                14,720<br/>
                                                16,320</td>
                                            <td class="cs_td_setting">15,120<br/>
                                                16,720<br/>
                                                18,320</td>
                                            <td class="cs_td_setting">17,120<br/>
                                                18,720<br/>
                                                20,320</td>
                                            <td class="cs_td_setting">18,770<br/>
                                                20,370<br/>
                                                21,970</td>
                                            <td class="cs_td_setting">19,770<br/>
                                                21,370<br/>
                                                22,970</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$320,000 - 364,999<br/>
                                                $365,000 - 524,999<br/>
                                                $525,000 and over</td>
                                            <td class="cs_td_setting">2,720<br/>
                                                2,970<br/>
                                                3,140</td> 
                                            <td class="cs_td_setting">5,920<br/>
                                                6,470<br/>
                                                6,840 </td> 
                                            <td class="cs_td_setting">8,750<br/>
                                                9,600<br/>
                                                10,170</td>
                                            <td class="cs_td_setting">10,950<br/>
                                                12,100<br/>
                                                12,870</td>
                                            <td class="cs_td_setting">13,070<br/>
                                               14,530<br/>
                                            15,500</td>
                                            <td class="cs_td_setting">15,070<br/>
                                                16,830<br/>
                                                18,000</td>
                                            <td class="cs_td_setting">17,070<br/>
                                                19,130<br/>
                                            20,500</td>
                                            <td class="cs_td_setting">19,070<br/>
                                                21,430<br/>
                                                23,000</td>
                                            <td class="cs_td_setting">21,290<br/>
                                                23,730<br/>
                                                25,500</td>
                                            <td class="cs_td_setting">23,590<br/>
                                                26,030<br/>
                                                28,000</td>
                                            <td class="cs_td_setting">25,540<br/>
                                                27,980<br/>
                                                30,150</td>
                                            <td class="cs_td_setting">26,840<br/>
                                                29,280<br/>
                                                31,650</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>

        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table bordered-table table-border-collapse" id="footer-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center border-bottom">
                                <span class="heading-text">Single or Married Filing Separately</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center border-bottom">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr> 
                                            <th rowspan="2" class="cs_th_setting" >Higher Paying Job Annual Taxable Wage & Salary</th> 
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
                                            <td class="cs_td_setting">$0 - 9,999<br/>
                                                $10,000 - 19,999<br/>
                                                $20,000 - 29,999</td>
                                            <td class="cs_td_setting">$460<br/>
                                                940<br/>
                                                1,020 </td> 
                                            <td class="cs_td_setting">$940<br/>
                                                1,530<br/>
                                                1,610 </td> 
                                            <td class="cs_td_setting">$1,020<br/>
                                                1,610<br/>
                                                2,130 </td>
                                        <td class="cs_td_setting">$1,020<br/>
                                                2,060<br/>
                                                3,130 </td>
                                        <td class="cs_td_setting">$1,470<br/>
                                                3,060<br/>
                                               4,130 </td>
                                            <td class="cs_td_setting">$1,870<br/>
                                                3,460<br/>
                                                4,720 </td>
                                            <td class="cs_td_setting">$1,870<br/>
                                                3,640<br/>
                                               4,920 </td>
                                                <td class="cs_td_setting">$1,870<br/>
                                               3,460<br/>
                                                4,720 </td>
                                            <td class="cs_td_setting">$2,040<br/>
                                                3,830<br/>
                                               5,110</td>
                                            <td class="cs_td_setting">$2,040<br/>
                                               3,830<br/>
                                                5,110</td>
                                            <td class="cs_td_setting">$2,040<br/>
                                               3,830<br/>
                                                5,110 </td>
                                            <td class="cs_td_setting">$1,870<br/>
                                                4,070<br/>
                                                5,900 </td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$30,000 - 39,999<br/>
                                                $40,000 - 59,999<br/>
                                                $60,000 - 79,999</td>
                                            <td class="cs_td_setting">1,020<br/>
                                                1,870<br/>
                                                1,870</td> 
                                            <td class="cs_td_setting">2,060<br/>
                                                3,460<br/>
                                                3,460</td> 
                                            <td class="cs_td_setting">3,130<br/>
                                                4,540<br/>
                                                4,690</td>
                                            <td class="cs_td_setting">4,130<br/>
                                                5,540<br/>
                                                5,890</td>
                                        <td class="cs_td_setting">5,130<br/>
                                               6,690<br/>
                                               7,090 </td>
                                        <td class="cs_td_setting">5,540<br/>
                                                7,290<br/>
                                                7,690</td>
                                            <td class="cs_td_setting">5,720<br/>
                                                7,490<br/>
                                               7,890</td>
                                            <td class="cs_td_setting">5,920<br/>
                                                7,690<br/>
                                                8,090</td>
                                            <td class="cs_td_setting">6,120<br/>
                                                7,890<br/>
                                                8,290</td>
                                            <td class="cs_td_setting">6,310<br/>
                                               8,080<br/>
                                               8,480</td>
                                            <td class="cs_td_setting">6,310<br/>
                                                8,080<br/>
                                                9,260 </td>
                                            <td class="cs_td_setting">6,310<br/>
                                               8,080<br/>
                                                10,060</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$80,000 - 99,999<br/>
                                                $100,000 - 124,999<br/>
                                                $125,000 - 149,999</td>
                                            <td class="cs_td_setting">2,020<br/>
                                                2,040<br/>
                                                2,040</td> 
                                            <td class="cs_td_setting">3,810<br/>
                                                3,830<br/>
                                                3,830 </td> 
                                            <td class="cs_td_setting">5,090<br/>
                                                5,110<br/>
                                                5,110</td>
                                            <td class="cs_td_setting">6,290<br/>
                                                6,310<br/>
                                                7,030</td>
                                        <td class="cs_td_setting">7,490<br/>
                                                7,510<br/>
                                                9,030</td>
                                        <td class="cs_td_setting">8,090<br/>
                                                8,430<br/>
                                            10,430</td>
                                            <td class="cs_td_setting">8,290<br/>
                                               9,430<br/>
                                                11,430</td>
                                            <td class="cs_td_setting">8,490<br/>
                                                10,430<br/>
                                                12,580</td>
                                            <td class="cs_td_setting">9,470<br/>
                                                11,430<br/>
                                               13,880</td>
                                            <td class="cs_td_setting">10,460<br/>
                                                12,420<br/>
                                                15,170</td>
                                            <td class="cs_td_setting">11,260<br/>
                                                13,520<br/>
                                                16,270</td>
                                            <td class="cs_td_setting">12,060<br/>
                                                14,620<br/>
                                               17,370</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting"> $150,000 - 174,999<br/>
                                                $175,000 - 199,999<br/>
                                                $200,000 - 249,999</td>
                                            <td class="cs_td_setting">2,360<br/>
                                                2,720<br/>
                                                2,970</td> 
                                            <td class="cs_td_setting">4,950<br/>
                                                5,310<br/>
                                                5,860 </td> 
                                            <td class="cs_td_setting">7,030<br/>
                                                7,540<br/>
                                                8,240</td>
                                            <td class="cs_td_setting">9,030<br/>
                                               9,840<br/>
                                                10,540</td>
                                        <td class="cs_td_setting">11,030<br/>
                                                 12,140<br/>
                                                12,840</td>
                                        <td class="cs_td_setting">12,730<br/>
                                                13,840<br/>
                                            14,540</td>
                                            <td class="cs_td_setting">14,030<br/>
                                                15,140<br/>
                                                15,840</td>
                                            <td class="cs_td_setting">15,330<br/>
                                                16,440<br/>
                                            17,140</td>
                                            <td class="cs_td_setting">16,630<br/>
                                               17,740<br/>
                                                18,440</td>
                                            <td class="cs_td_setting">17,920<br/>
                                                19,030<br/>
                                                19,730</td>
                                            <td class="cs_td_setting">19,020<br/>
                                                20,130<br/>
                                                20,830</td>
                                            <td class="cs_td_setting">20,120<br/>
                                                21,230<br/>
                                                21,930</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$250,000 - 399,999<br/>
                                                $400,000 - 449,999<br/>
                                                $450,000 and over</td>
                                            <td class="cs_td_setting">2,970 <br/>
                                                2,970 <br/>
                                                3,140</td> 
                                            <td class="cs_td_setting">5,860<br/>
                                                5,860<br/>
                                                6,230 </td> 
                                            <td class="cs_td_setting">8,240<br/>
                                                8,240<br/>
                                                8,810</td>
                                            <td class="cs_td_setting">10,540<br/>
                                                10,540<br/>
                                                11,310</td>
                                            <td class="cs_td_setting">12,840<br/>
                                                12,840<br/>
                                            13,810</td>
                                            <td class="cs_td_setting">14,540<br/>
                                                14,540<br/>
                                                15,710</td>
                                            <td class="cs_td_setting">15,840<br/>
                                                15,840<br/>
                                            17,210</td>
                                            <td class="cs_td_setting">17,140<br/>
                                                17,140<br/>
                                                18,710</td>
                                            <td class="cs_td_setting">18,440<br/>
                                                18,450<br/>
                                                20,210</td>
                                            <td class="cs_td_setting">19,730<br/>
                                                19,940<br/>
                                                21,700</td>
                                            <td class="cs_td_setting">20,830<br/>
                                               21,240<br/>
                                                23,000</td>
                                            <td class="cs_td_setting">21,930<br/>
                                                22,540<br/>
                                                24,300</td>
                                        </tr>
                                      
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>

        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table bordered-table table-border-collapse" id="footer-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center border-bottom">
                                <span class="heading-text">Head of Household</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center border-bottom">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr> 
                                            <th rowspan="2" class="cs_th_setting" >Higher Paying Job Annual Taxable Wage & Salary</th> 
                                            <th colspan="12" class="text-center">Lower Paying Job Annual Taxable Wage & Salary</th>
                                        </tr> 
                                        <tr > 
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
                                            <td class="cs_td_setting">$0 - 9,999<br/>
                                                $10,000 - 19,999<br/>
                                                $20,000 - 29,999</td>
                                            <td class="cs_td_setting">$0<br/>
                                                830<br/>
                                                930 </td> 
                                            <td class="cs_td_setting">$830<br/>
                                                1,920<br/>
                                                2,130 </td> 
                                            <td class="cs_td_setting">$930<br/>
                                                2,130<br/>
                                                2,350 </td>
                                        <td class="cs_td_setting">$1,020<br/>
                                                2,220<br/>
                                                2,430 </td>
                                        <td class="cs_td_setting">$1,020<br/>
                                                2,220<br/>
                                               2,900 </td>
                                            <td class="cs_td_setting">$1,480<br/>
                                                3,680<br/>
                                                4,900 </td>
                                            <td class="cs_td_setting">$1,870<br/>
                                                4,070<br/>
                                               5,340 </td>
                                                <td class="cs_td_setting">$1,870<br/>
                                               4,130<br/>
                                                5,540 </td>
                                            <td class="cs_td_setting">$2,040<br/>
                                                3,830<br/>
                                               5,110</td>
                                            <td class="cs_td_setting">$1,930<br/>
                                               4,330<br/>
                                                5,740</td>
                                            <td class="cs_td_setting">$2,040<br/>
                                               4,440 <br/>
                                                5,850 </td>
                                            <td class="cs_td_setting">$2,040<br/>
                                              4,440<br/>
                                                5,850 </td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$30,000 - 39,999<br/>
                                                $40,000 - 59,999<br/>
                                                $60,000 - 79,999</td>
                                            <td class="cs_td_setting">1,020<br/>
                                                1,020<br/>
                                                1,870</td> 
                                            <td class="cs_td_setting">2,220<br/>
                                                2,530<br/>
                                                4,070</td> 
                                            <td class="cs_td_setting">2,430<br/>
                                                3,750<br/>
                                                5,310</td>
                                            <td class="cs_td_setting">2,980<br/>
                                                4,830<br/>
                                                6,600</td>
                                        <td class="cs_td_setting">3,980<br/>
                                               5,860<br/>
                                               7,800 </td>
                                        <td class="cs_td_setting">4,980<br/>
                                               7,060<br/>
                                               9,000</td>
                                            <td class="cs_td_setting">6,040<br/>
                                                8,260<br/>
                                               10,200</td>
                                            <td class="cs_td_setting">6,630<br/>
                                                8,850<br/>
                                                10,780</td>
                                            <td class="cs_td_setting">6,830<br/>
                                                9,050<br/>
                                                10,980</td>
                                            <td class="cs_td_setting">7,030<br/>
                                               9,250<br/>
                                               11,180</td>
                                            <td class="cs_td_setting">7,140<br/>
                                                9,360<br/>
                                                11,580 </td>
                                            <td class="cs_td_setting">7,140<br/>
                                               9,360<br/>
                                                12,380</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$80,000 - 99,999<br/>
                                                $100,000 - 124,999<br/>
                                                $125,000 - 149,999</td>
                                            <td class="cs_td_setting">1,900<br/>
                                                2,040<br/>
                                                2,040</td> 
                                            <td class="cs_td_setting">4,300<br/>
                                                4,440<br/>
                                                4,440 </td> 
                                            <td class="cs_td_setting">5,710<br/>
                                                5,850<br/>
                                                5,850</td>
                                            <td class="cs_td_setting">7,000<br/>
                                               7,140<br/>
                                                7,360</td>
                                        <td class="cs_td_setting">8,200<br/>
                                                8,340<br/>
                                                9,360</td>
                                        <td class="cs_td_setting">9,400<br/>
                                                9,540<br/>
                                            11,360</td>
                                            <td class="cs_td_setting">10,600<br/>
                                               11,360<br/>
                                                13,360</td>
                                            <td class="cs_td_setting">11,180<br/>
                                                12,750<br/>
                                                14,750</td>
                                            <td class="cs_td_setting">11,670<br/>
                                                13,750<br/>
                                               16,010</td>
                                            <td class="cs_td_setting">12,670<br/>
                                                14,750<br/>
                                                17,310</td>
                                            <td class="cs_td_setting">13,580<br/>
                                                15,770<br/>
                                                18,520</td>
                                            <td class="cs_td_setting">14,380<br/>
                                               16,870<br/>
                                               19,620</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting"> $150,000 - 174,999<br/>
                                                $175,000 - 199,999<br/>
                                                $200,000 - 249,999</td>
                                            <td class="cs_td_setting">2,040<br/>
                                                2,720<br/>
                                                2,970</td> 
                                            <td class="cs_td_setting">5,060<br/>
                                                5,920<br/>
                                               6,470 </td> 
                                            <td class="cs_td_setting">7,280<br/>
                                                8,130<br/>
                                                8,990</td>
                                            <td class="cs_td_setting">9,360<br/>
                                               10,480<br/>
                                                11,370</td>
                                        <td class="cs_td_setting">11,360<br/>
                                                 12,780<br/>
                                                13,670</td>
                                        <td class="cs_td_setting">13,480<br/>
                                                15,080<br/>
                                            15,970</td>
                                            <td class="cs_td_setting">15,780<br/>
                                                17,380<br/>
                                                18,270</td>
                                            <td class="cs_td_setting">17,460<br/>
                                                19,070<br/>
                                            19,960</td>
                                            <td class="cs_td_setting">18,760<br/>
                                               20,370<br/>
                                                21,260</td>
                                            <td class="cs_td_setting">20,060<br/>
                                                21,670<br/>
                                               22,560</td>
                                            <td class="cs_td_setting">21,270<br/>
                                                22,880<br/>
                                                23,770</td>
                                            <td class="cs_td_setting">22,370<br/>
                                                23,980<br/>
                                                24,870</td>
                                        </tr>
                                        <tr> 
                                            <td class="cs_td_setting">$250,000 - 399,999<br/>
                                                $400,000 - 449,999<br/>
                                                $450,000 and over</td>
                                            <td class="cs_td_setting">2,970 <br/>
                                                2,970 <br/>
                                                3,140</td> 
                                            <td class="cs_td_setting">6,470<br/>
                                                6,470<br/>
                                                6,840 </td> 
                                            <td class="cs_td_setting">8,990<br/>
                                                8,990<br/>
                                                9,560</td>
                                            <td class="cs_td_setting">11,370<br/>
                                                11,370<br/>
                                                12,140</td>
                                            <td class="cs_td_setting">13,670<br/>
                                                13,670<br/>
                                            14,640</td>
                                            <td class="cs_td_setting">15,970<br/>
                                                15,970<br/>
                                            17,140</td>
                                            <td class="cs_td_setting">18,270<br/>
                                               18,270<br/>
                                               19,640</td>
                                            <td class="cs_td_setting">19,960<br/>
                                                19,960<br/>
                                                21,530</td>
                                            <td class="cs_td_setting">21,260<br/>
                                               21,260<br/>
                                                23,030</td>
                                            <td class="cs_td_setting">22,560<br/>
                                               22,560<br/>
                                               24,530</td>
                                            <td class="cs_td_setting">23,770<br/>
                                                23,770<br/>
                                                25,940</td>
                                            <td class="cs_td_setting">24,870<br/>
                                                25,200<br/>
                                               27,240</td>
                                        </tr>
                                      
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
    <script type="text/javascript">
        $(window).on( "load", function() { 
            setTimeout(function(){
                window.print();
            }, 1000);  
        });

        window.onafterprint = function(){
            // window.close();
        }
    </script>
</body>
</html>