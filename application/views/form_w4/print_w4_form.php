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
            <div class="container-fluid">
                <table class="table" id="footer-table">
                    <thead>
                        <tr>
                            <th><span class="main-heading">Form W-4 (<?php echo date('Y'); ?>)</span></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 33.3333%;">
                            <span class="heading-text">Future developments.</span> For the latest information about any future developments related to Form W-4, such as legislation enacted after it was published, go to www.irs.gov/FormW4.<br>
                            <span class="heading-text">Purpose.</span> Complete Form W-4 so that your employer can withhold the correct federal income tax from your pay. Consider completing a new Form W-4 each year and when your personal or financial situation changes.<br>
                            <span class="heading-text">Exemption from withholding.</span> You may claim exemption from withholding for <?php echo date('Y'); ?> if  <strong>both</strong> of the following apply.
                            • For <?php echo date('Y')-1; ?> you had a right to a refund of all federal income tax withheld because you had no tax liability, and
                            • For <?php echo date('Y'); ?> you expect a refund of all federal income tax withheld because you expect to have no tax liability.
                            If you’re exempt, complete only lines 1, 2, 3, 4, and 7 and sign the form to validate it. Your exemption for <?php echo date('Y'); ?> expires February 15, <?php echo date('Y')+1; ?>. See Pub. 505, Tax Withholding and Estimated Tax, to learn more about whether you qualify for exemption from withholding.<br>
                            <span class="heading-text" style="font-size: 20px;">General Instructions</span><br>
                            If you aren’t exempt, follow the rest of these instructions to determine the number of withholding allowances you should claim for withholding for <?php echo date('Y'); ?> and any additional amount of tax to have withheld. For regular wages, withholding must be based on allowances you claimed and may not be a flat amount or percentage of wages.
                            You can also use the calculator at www.irs.gov/W4App to determine your tax withholding more accurately. Consider
                        </td>
                        <td style="width: 33.3333%;">
                            using this calculator if you have a more complicated tax situation, such as if you have a working spouse, more than one job, or a large amount of nonwage income outside of your job. After your Form W-4 takes effect, you can also use this calculator to see how the amount of tax you’re having withheld compares to your projected total tax for <?php echo date('Y'); ?>. If you use the calculator, you don’t need to complete any of the worksheets for Form W-4.
                            Note that if you have too much tax withheld, you will receive a refund when you file your tax return. If you have too little tax withheld, you will owe tax when you file your tax return, and you might owe a penalty.<br>
                            <span class="heading-text">Filers with multiple jobs or working spouses.</span> If you have more than one job at a time, or if you’re married and your spouse is also working, read all of the instructions including the instructions for the Two-Earners/Multiple Jobs Worksheet before beginning.<br>
                            <span class="heading-text">Nonwage income.</span> If you have a large amount of nonwage income, such as interest or dividends, consider making estimated tax payments using Form 1040-ES, Estimated Tax for Individuals. Otherwise, you might owe additional tax. Or, you can use the Deductions,
                            Adjustments, and Other Income Worksheet on page 3 or the calculator at www.irs.gov/W4App to make sure you have enough tax withheld from your paycheck. If you have pension or annuity income, see Pub. 505 or use the calculator at www.irs.gov/W4App to find out if you should adjust your withholding on Form W-4 or W-4P.<br>
                            <span class="heading-text">Nonresident alien.</span> If you’re a nonresident alien, see Notice 1392, Supplemental Form W-4 Instructions for Nonresident Aliens, before completing this form.
                        </td>
                        <td style="width: 33.3333%;">
                            <span class="heading-text" style="font-size: 20px;">Specific Instructions</span><br>
                            <span class="heading-text">Personal Allowances Worksheet.</span> Complete this worksheet on page 3 first to determine the number of withholding allowances to claim.<br>
                            <span class="heading-text">Line C. Head of household please note:</span> Generally, you can claim head of household filing status on your tax return only if you’re unmarried and pay more than 50% of the costs of keeping up a home for yourself and a qualifying individual. See Pub. 501 for more information about filing status.<br>
                            <span class="heading-text">Line E. Child tax credit.</span> When you file your tax return, you might be eligible to claim a credit for each of your qualifying children. To qualify, the child must be under age 17 as of December 31 and must be your dependent who lives with you for more than half the year.
                            To learn more about this credit, see Pub. 972, Child Tax Credit. To reduce the tax withheld from your pay by taking this credit into account, follow the instructions on line E of the worksheet.
                            On the worksheet you will be asked about your total income. For this purpose, total income includes all of your wages and other income, including income earned by a spouse, during the year.<br>
                            <span class="heading-text">Line F. Credit for other dependents.</span> When you file your tax return, you might be eligible to claim a credit for each of your dependents that don’t qualify for the child tax credit, such as any dependent children age 17 and older. To learn more about this credit, see Pub. 505. To reduce the tax withheld from your pay by taking this credit into account, follow the instructions on line F of the worksheet. On the worksheet, you will be asked about your total income. For this purpose, total income includes all of

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="pdf-cover-page" >
            <table class="table">
                <tbody>
                <tr>
                    <td style="width: 100%;" class="text-center">
                        <span class="text-bold">Separate here and give Form W-4 to your employer. Keep the worksheet(s) for your records.</span>
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td class="border-right-bottom-bold" style="width: 20%;">
                            Form <strong style="font-size: 32px;">W-4</strong><br>
                            Department of the Treasury<br> Internal Revenue Service
                        </td>
                        <td class="text-center border-right-bottom-bold">
                            <strong style="font-size: 20px;">Employee’s Withholding Allowance Certificate</strong><br>
                            <strong style="font-size: 11px;">▶ Whether you’re entitled to claim a certain number of allowances or exemption from withholding is subject to review by the IRS. Your employer may be required to send a copy of this form to the IRS</strong>
                        </td>
                        <td class="border-bottom-bold" style="width: 20%; text-align: center;">
                            OMB No. 1545-0074<br>
                            <strong style="font-size: 32px;"><?php echo date('Y'); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td class="border-right-bottom">
                            <span>1. Your first name and middle initial</span>
                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['first_name']) ? $pre_form['first_name']: ''?> <?php echo !empty($pre_form['middle_name']) ? $pre_form['middle_name']: ''?>" readonly style="border: none; font-weight: bold; display: block;"/>
                        </td>
                        <td class="border-right-bottom">
                            <span>Last name</span>
                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['last_name']) ? $pre_form['last_name']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                        </td>
                        <td class="border-bottom">
                            <span>2 Your social security number</span>
                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['ss_number']) ? $pre_form['ss_number']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-border-collapse">
                <tbody>
                <tr>
                    <td class="border-right-bottom" width="50%">
                        <span>Home address (number and street or rural route)</span>
                        <input class="plane-input" type="text" value="<?php echo !empty($pre_form['home_address']) ? $pre_form['home_address']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                    </td>
                    <td class="border-bottom" width="50%">
                        <table>
                            <tr>
                                <td>
                                    <span class="text-bold" style="font-weight: bold; float: left;">3</span>
                                </td>
                                <td>
                                    <div style="width: 75px;"><input type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'single' ? 'checked="checked"': ''?> />Single</div>
                                </td>
                                <td>
                                    <div style="width: 85px;"><input type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'married' ? 'checked="checked"': ''?> />Married</div>
                                </td>
                                <td>
                                    <div style="width: 320px;"><input type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'complex' ? 'checked="checked"': ''?> />Married, but withhold at higher Single rate.</div>
                                </td>
                            </tr>
                        </table>
                        <div><strong>Note:</strong> If married filing separately, check “Married, but withhold at higher Single rate.”</div>
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table table-border-collapse">
                <tbody>
                <tr>
                    <td class="border-right-bottom" width="50%">
                        <span>City or town, state, and ZIP code</span>
                        <input class="plane-input" type="text" value="<?php echo !empty($pre_form['city']) ? $pre_form['city'].',': ''?> <?php echo !empty($pre_form['state']) ? $pre_form['state'].',': ''?> <?php echo !empty($pre_form['zip']) ? $pre_form['zip']: ''?> " readonly style="display: block; width: 100%; border: none; font-weight: bold; display: block;"/>
                    </td>
                    <td class="border-bottom" width="50%">
                        <strong>4. If your last name differs from that shown on your social security card, check here. You must call 800-772-1213 for a replacement card.</strong>
                        <input type="checkbox" value="1" readonly <?php echo !empty($pre_form['different_last_name']) && $pre_form['different_last_name'] == 1 ? 'checked="checked"': ''?> />
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- <hr> -->
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td class="border-right">
                            <span>5. Total number of allowances you’re claiming (from the applicable worksheet on the following pages) ...</span>
                        </td>
                        <td class="border-bottom">
                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['number_of_allowance']) ? $pre_form['number_of_allowance']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-right">
                            <span>6. Additional amount, if any, you want withheld from each paycheck ..............</span>
                        </td>
                        <td class="border-bottom">
                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['additional_amount']) ? $pre_form['additional_amount']: ''?>" readonly style="border: none; font-weight: bold; display: block;" />
                        </td>
                    </tr>
                    <tr>
                        <td class="border-right-bottom">
                            <span>7. I claim exemption from withholding for <?php echo date('Y'); ?>, and I certify that I meet both of the following conditions for exemption.
                                • Last year I had a right to a refund of all federal income tax withheld because I had no tax liability, and
                                • This year I expect a refund of all federal income tax withheld because I expect to have no tax liability.
                                If you meet both conditions, write “Exempt” here ...............</span>
                        </td>
                        <td class="border-bottom">
                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['claim_exempt']) ? $pre_form['claim_exempt']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Under penalties of perjury, I declare that I have examined this certificate and, to the best of my knowledge and belief, it is true, correct, and complete.
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td class="border-bottom"><span class="heading-text">Employee’s signature <br></span>(This form is not valid unless you sign it.)
                            <?php $active_signature = isset($e_signature_data['active_signature']) ? $e_signature_data['active_signature'] : 'typed'; ?>
                            <?php $signature = isset($e_signature_data['signature']) ? $e_signature_data['signature'] : '';?>
                            <div class="img-full">
                                <img style="max-height: 50px;" src="<?php echo isset($pre_form['signature_bas64_image']) && !empty($pre_form['signature_bas64_image']) ? $pre_form['signature_bas64_image'] : ''; ?>"  />
                            </div>
                        </td>
                        <td colspan="2" class="border-bottom text-center" style="vertical-align: bottom"><span class="heading-text">Date</span>
                            <?php echo !empty($pre_form['signature_timestamp']) && $pre_form['signature_timestamp'] != '0000-00-00 00:00:00' ? reset_datetime(array('datetime' => $pre_form['signature_timestamp'], '_this' => $this)) : ''; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-right-bottom">
                            <span>8. Employer’s name and address (Employer: Complete boxes 8 and 10 if sending to IRS and complete boxes 8, 9, and 10 if sending to State Directory of New Hires.)</span>
                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['emp_name']) ? $pre_form['emp_name'].',': ''?> <?php echo !empty($pre_form['emp_address']) ? $pre_form['emp_address']: ''?>" readonly style="display: block; width: 100%; border: none; font-weight: bold; display: block;" />
                        </td>
                        <td class="border-right-bottom">
                            <span>9. First date of employment</span>
                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['first_date_of_employment']) && $pre_form['first_date_of_employment'] != '0000-00-00' ? reset_datetime(array('datetime' => $pre_form['first_date_of_employment'], '_this' => $this)) : ''; ?>" readonly />
                        </td>
                        <td class="border-bottom">
                            <span>10. Employer identification number (EIN)</span>
                            <input class="plane-input" type="text" value="<?php echo isset($pre_form['emp_identification_number']) ? $pre_form['emp_identification_number']: ''?>" readonly  style="border: none; font-weight: bold; display: block;"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <div class="pdf-page-break"></div>
        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table" id="footer-table">
                    <tbody>
                        <tr>
                            <td style="width: 30.3333%;">
                                your wages and other income, including income earned by a spouse, during the year.<br>
                                <span class="heading-text">Line G. Other credits.</span> You might be able to reduce the tax withheld from your paycheck if you expect to claim other tax credits, such as the earned income tax credit and tax credits for education and child care expenses. If you do so, your paycheck will be larger but the amount of any refund that you receive when you file your tax return will be smaller. Follow the instructions for Worksheet 1-6 in Pub. 505 if you want to reduce your withholding to take these credits into account.<br>
                                <span class="heading-text">Deductions, Adjustments, and Additional Income Worksheet</span> Complete this worksheet to determine if you’re able to reduce the tax withheld from your paycheck to account for your itemized deductions and other adjustments to income such as IRA contributions. If you do so, your refund at the end of the year will be smaller, but your paycheck will be larger. You’re not required to complete this worksheet or reduce your withholding if you don’t wish to do so.<br>
                                You can also use this worksheet to figure out how much to increase the tax withheld from your paycheck if you have a large amount of nonwage income, such as interest or dividends.<br>
                                Another option is to take these items into account and make your withholding more accurate by using the calculator at www.irs.gov/W4App. If you use the calculator, you don’t need to complete any of the worksheets for Form W-4.
                                <span class="heading-text" style="font-size: 28px;">Two-Earners/Multiple Jobs Worksheet</span><br>
                                Complete this worksheet if you have more
                            </td>
                            <td style="width: 30.3333%;">
                                than one job at a time or are married filing jointly and have a working spouse. If you don’t complete this worksheet, you might have too little tax withheld. If so, you will owe tax when you file your tax return and might be subject to a penalty.<br>
                                Figure the total number of allowances you’re entitled to claim and any additional amount of tax to withhold on all jobs using worksheets from only one Form W-4. Claim all allowances on the W-4 that you or your spouse file for the highest paying job in your family and claim zero allowances on Forms W-4 filed for all other jobs. For example, if you earn $60,000 per year and your spouse earns $20,000, you should complete the worksheets to determine what to enter on lines 5 and 6 of your Form W-4, and your spouse should enter zero (“-0-”) on lines 5 and 6 of his or her Form W-4. See Pub. 505 for details.<br>
                                Another option is to use the calculator at www.irs.gov/W4App to make your withholding more accurate.<br>
                                <span class="heading-text">Tip:</span> If you have a working spouse and your incomes are similar, you can check the “Married, but withhold at higher Single rate” box instead of using this worksheet. If you choose this option, then each spouse should fill out the Personal Allowances Worksheet and check the “Married, but withhold at higher Single rate” box on Form W-4, but only one spouse should claim any allowances for credits or fill out the Deductions, Adjustments, and Additional Income Worksheet.<br>
                                <span class="heading-text" style="font-size: 28px;">Instructions for Employer</span><br>
                                Employees, do not complete box 8, 9, or 10. Your employer will complete these boxes if necessary.<br>
                                <span class="heading-text">New hire reporting.</span> Employers are
                            </td>
                            <td style="width: 30.3333%;">
                                required by law to report new employees to a designated State Directory of New Hires. Employers may use Form W-4, boxes 8, 9, and 10 to comply with the new hire reporting requirement for a newly hired employee. A newly hired employee is an employee who hasn’t previously been employed by the employer, or who was previously employed by the employer but has been separated from such prior employment for at least 60 consecutive days. Employers should contact the appropriate State Directory of New Hires to find out how to submit a copy of the completed Form W-4. For information and links to each designated State Directory of New Hires (including for U.S. territories), go to www.acf.hhs.gov/programs/css/employers.<br>
                                If an employer is sending a copy of Form W-4 to a designated State Directory of New Hires to comply with the new hire reporting requirement for a newly hired employee, complete boxes 8, 9, and 10 as follows.<br>
                                <span class="heading-text">Box 8.</span> Enter the employer’s name and address. If the employer is sending a copy of this form to a State Directory of New Hires, enter the address where child support agencies should send income withholding orders.<br>
                                <span class="heading-text">Box 9.</span> If the employer is sending a copy of this form to a State Directory of New Hires, enter the employee’s first date of employment, which is the date services for payment were first performed by the employee. If the employer rehired the employee after the employee had been separated from the employer’s service for at least 60 days, enter the rehire date.<br>
                                <span class="heading-text">Box 10.</span> Enter the employer’s employer identification number (EIN).
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="pdf-page-break"></div>
        
        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table bordered-table table-border-collapse" id="footer-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center border-bottom">
                                <span class="heading-text">Personal Allowances Worksheet (Keep for your records.)</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">A</strong>
                            </td>
                            <td width="86%">
                                Enter “1” for yourself ..............................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">A</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['paw_yourself']) ? $pre_form['paw_yourself']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">B</strong>
                            </td>
                            <td width="86%">
                                Enter “1” if you will file as married filing jointly.......................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">B</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['paw_married']) ? $pre_form['paw_married']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">C</strong>
                            </td>
                            <td width="86%">
                                Enter “1” if you will file as head of household.......................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">C</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['paw_head']) ? $pre_form['paw_head']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">D</strong>
                            </td>
                            <td width="86%">
                                Enter “1” if:<br>
                                • You’re single, or married filing separately, and have only one job; or<br>
                                • You’re married filing jointly, have only one job, and your spouse doesn’t work; or<br>
                                • Your wages from a second job or your spouse’s wages (or the total of both) are $1,500 or less.
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">D</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['paw_single_wages']) ? $pre_form['paw_single_wages']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">E</strong>
                            </td>
                            <td width="86%">
                                <strong>Child tax credit.</strong> See Pub. 972, Child Tax Credit, for more information.<br>
                                • If your total income will be less than $69,801 ($101,401 if married filing jointly), enter “4” for each eligible child.<br>
                                • If your total income will be from $69,801 to $175,550 ($101,401 to $339,000 if married filing jointly), enter “2” for each eligible child.<br>
                                • If your total income will be from $175,551 to $200,000 ($339,001 to $400,000 if married filing jointly), enter “1” for each eligible child.<br>
                                • If your total income will be higher than $200,000 ($400,000 if married filing jointly), enter “-0-”.......
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">E</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['paw_child_tax']) ? $pre_form['paw_child_tax']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">F</strong>
                            </td>
                            <td width="86%">
                                <strong>Credit for other dependents.</strong><br>
                                • If your total income will be less than $69,801 ($101,401 if married filing jointly), enter “1” for each eligible dependent.<br>
                                • If your total income will be from $69,801 to $175,550 ($101,401 to $339,000 if married filing jointly), enter “1” for every two dependents (for example, “-0-” for one dependent, “1” if you have two or three dependents, and “2” if you have four dependents).<br>
                                • If your total income will be higher than $175,550 ($339,000 if married filing jointly), enter “-0-”.......
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">F</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['paw_dependents']) ? $pre_form['paw_dependents']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">G</strong>
                            </td>
                            <td width="86%">
                                <strong>Other credits.</strong> If you have other credits, see Worksheet 1-6 of Pub. 505 and enter the amount from that worksheet here ..
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">G</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['paw_other_credit']) ? $pre_form['paw_other_credit']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">H</strong>
                            </td>
                            <td width="86%">
                                Add lines A through G and enter the total here ......................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">H</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['paw_accuracy']) ? $pre_form['paw_accuracy']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">

                            </td>
                            <td width="86%">
                                <strong>For accuracy, complete all worksheets that apply.</strong><br>
                                • If you plan to <strong>itemize</strong> or <strong>claim adjustments to income</strong> and want to reduce your withholding, or if you have a large amount of nonwage income and want to increase your withholding, see the <strong>Deductions, Adjustments, and Additional Income Worksheet</strong> below.<br>
                                • If you <strong>have more than one job at a time</strong> or are <strong>married filing jointly and you and your spouse both work</strong>, and the combined earnings from all jobs exceed $52,000 ($24,000 if married filing jointly), see the <strong>Two-Earners/Multiple Jobs Worksheet</strong> on page 4 to avoid having too little tax withheld.<br>
                                • If <strong>neither</strong> of the above situations applies, <strong>stop here</strong> and enter the number from line H on line 5 of Form W-4 above.
                            </td>
                            <td width="2%">

                            </td>
                            <td width="10%">

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div style="height: 20px;"></div>

        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table bordered-table table-border-collapse" id="footer-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center border-bottom">
                                <span class="heading-text">Deductions, Adjustments, and Additional Income Worksheet</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">
                                <strong>Note:</strong> Use this worksheet only if you plan to itemize deductions, claim certain adjustments to income, or have a large amount of nonwage income.
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">1</strong>
                            </td>
                            <td width="86%">
                                Enter an estimate of your <?php echo date('Y'); ?> itemized deductions. These include qualifying home mortgage interest, charitable contributions, state and local taxes (up to $10,000), and medical expenses in excess of 7.5% of your income. See Pub. 505 for details ......................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">1</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_estimate']) ? $pre_form['daaiw_estimate']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">2</strong>
                            </td>
                            <td width="86%">
                                Enter:<br>
                                $24,000 if you’re married filing jointly or qualifying widow(er)<br>
                                $18,000 if you’re head of household<br>
                                $12,000 if you’re single or married filing separately ...........
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">2</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_enter_status']) ? $pre_form['daaiw_enter_status']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">3</strong>
                            </td>
                            <td width="86%">
                                <strong>Subtract</strong> line 2 from line 1. If zero or less, enter “-0-” .................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">3</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_subtract_line_2']) ? $pre_form['daaiw_subtract_line_2']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">4</strong>
                            </td>
                            <td width="86%">
                                Enter an estimate of your <?php echo date('Y'); ?> adjustments to income and any additional standard deduction for age or blindness (see Pub. 505 for information about these items)................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">4</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_estimate_of_adjustment']) ? $pre_form['daaiw_estimate_of_adjustment']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">5</strong>
                            </td>
                            <td width="86%">
                                <strong>Add</strong> lines 3 and 4 and enter the total ......................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">5</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_add_line_3_4']) ? $pre_form['daaiw_add_line_3_4']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">6</strong>
                            </td>
                            <td width="86%">
                                Enter an estimate of your <?php echo date('Y'); ?> nonwage income (such as dividends or interest).........
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">6</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_estimate__of_nonwage']) ? $pre_form['daaiw_estimate__of_nonwage']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">7</strong>
                            </td>
                            <td width="86%">
                                <strong>Subtract</strong> line 6 from line 5. If zero, enter “-0-”. If less than zero, enter the amount in parentheses ...
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">7</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_subtract_line_6']) ? $pre_form['daaiw_subtract_line_6']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">8</strong>
                            </td>
                            <td width="86%">
                                <strong>Divide</strong> the amount on line 7 by $4,150 and enter the result here. If a negative amount, enter in parentheses. Drop any fraction ............................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">8</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_divide_line_7']) ? $pre_form['daaiw_divide_line_7']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">9</strong>
                            </td>
                            <td width="86%">
                                Enter the number from the Personal Allowances Worksheet, line H above .........
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">9</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_enter_number_personal_allowance']) ? $pre_form['daaiw_enter_number_personal_allowance']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">10</strong>
                            </td>
                            <td width="86%">
                                Add lines 8 and 9 and enter the total here. If zero or less, enter “-0-”. If you plan to use the Two-Earners/Multiple Jobs Worksheet, also enter this total on line 1, page 4. Otherwise, stop here and enter this total on Form W-4, line 5, page 1 .........................
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">10</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['daaiw_add_line_8_9']) ? $pre_form['daaiw_add_line_8_9']: ''?>" readonly />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="pdf-page-break"></div>

        <section class="pdf-cover-page" >
            <div class="container-fluid">
                <table class="table bordered-table table-border-collapse" id="footer-table">
                    <thead>
                    <tr>
                        <th colspan="4" class="text-center border-bottom">
                            <span class="heading-text">Two-Earners/Multiple Jobs Worksheet</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">
                                <strong>Note:</strong> Note: Use this worksheet only if the instructions under line H from the Personal Allowances Worksheet direct you here.
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">1</strong>
                            </td>
                            <td width="86%">
                                Enter the number from the <strong>Personal Allowances Worksheet</strong>, line H, page 3 (or, if you used the
                                <strong>Deductions, Adjustments, and Additional Income Worksheet</strong> on page 3, the number from line 10 of that
                                worksheet) . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">1</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_personal_allowance']) ? $pre_form['temjw_personal_allowance']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">2</strong>
                            </td>
                            <td width="86%">
                                Find the number in <strong>Table 1</strong> below that applies to the <strong>LOWEST</strong> paying job and enter it here. <strong>However</strong>, if you’re
                                married filing jointly and wages from the highest paying job are $75,000 or less and the combined wages for
                                you and your spouse are $107,000 or less, don’t enter more than “3” . . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">2</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_num_in_table_1']) ? $pre_form['temjw_num_in_table_1']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">3</strong>
                            </td>
                            <td width="86%">
                                If line 1 is <strong>more than or equal to</strong> line 2, subtract line 2 from line 1. Enter the result here (if zero, enter “-0-”)
                                and on Form W-4, line 5, page 1. <strong>Do not</strong> use the rest of this worksheet . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">3</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_more_line2']) ? $pre_form['temjw_more_line2']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <strong>Note: </strong>If line 1 is less than line 2, enter “-0-” on Form W-4, line 5, page 1. Complete lines 4 through 9 below to
                                figure the additional withholding amount necessary to avoid a year-end tax bill.
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">4</strong>
                            </td>
                            <td width="86%">
                                Enter the number from line 2 of this worksheet . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">4</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_num_from_line2']) ? $pre_form['temjw_num_from_line2']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">5</strong>
                            </td>
                            <td width="86%">
                                Enter the number from line 1 of this worksheet . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">5</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_num_from_line1']) ? $pre_form['temjw_num_from_line1']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">6</strong>
                            </td>
                            <td width="86%">
                                <strong>Subtract</strong> line 5 from line 4 . . . . . . . . . . . . . . . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">6</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_subtract_5_from_4']) ? $pre_form['temjw_subtract_5_from_4']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">7</strong>
                            </td>
                            <td width="86%">
                                Find the amount in <strong>Table 2</strong> below that applies to the <strong>HIGHEST</strong> paying job and enter it here . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">7</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_amount_in_table_2']) ? $pre_form['temjw_amount_in_table_2']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">8</strong>
                            </td>
                            <td width="86%">
                                <strong>Multiply</strong> line 7 by line 6 and enter the result here. This is the additional annual withholding needed . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">8</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_multiply_7_by_6']) ? $pre_form['temjw_multiply_7_by_6']: ''?>" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td width="2%">
                                <strong class="indicator-box">9</strong>
                            </td>
                            <td width="86%">
                                <strong>Divide</strong> line 8 by the number of pay periods remaining in <?php echo date('Y'); ?>. For example, divide by 18 if you’re paid every
                                2 weeks and you complete this form on a date in late April when there are 18 pay periods remaining in
                                <?php echo date('Y'); ?>. Enter the result here and on Form W-4, line 6, page 1. This is the additional amount to be withheld
                                from each paycheck . . . . . . . . . . . . . . . . . . . . . . . . . . .
                            </td>
                            <td width="2%">
                                <strong class="indicator-box-2">9</strong>
                            </td>
                            <td width="10%">
                                <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['temjw_divide_8_by_period']) ? $pre_form['temjw_divide_8_by_period']: ''?>" readonly />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        
        <section class="pdf-cover-page" >
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-border-collapse">
                        <tr>
                            <th colspan="2" class="text-center"><strong>Table 1</strong></th>
                        </tr>
                        <tr>
                            <td>
                                <table class="table bordered-table table-border-collapse">
                                    <tr>
                                        <th colspan="2" class="text-center border-bottom"><strong>Married Filing Jointly</strong></th>
                                    </tr>
                                    <tr>
                                        <td class="border-right-bottom">If wages from LOWEST paying job are—</td>
                                        <td class="border-bottom">Enter on line 2 above</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="border-right text-center">
                                            <span class="display-block">$0 - $5,000</span>
                                            <span class="display-block">5,001 - 9,500</span>
                                            <span class="display-block">9,501 - 19,000</span>
                                            <span class="display-block">19,001 - 26,500</span>
                                            <span class="display-block">26,501 - 37,000</span>
                                            <span class="display-block">37,001 - 43,500</span>
                                            <span class="display-block">43,501 - 55,000</span>
                                            <span class="display-block">55,001 - 60,000</span>
                                            <span class="display-block">60,001 - 70,000</span>
                                            <span class="display-block">70,001 - 75,000</span>
                                            <span class="display-block">75,001 - 85,000</span>
                                            <span class="display-block">85,001 - 95,000</span>
                                            <span class="display-block">95,001 - 130,000</span>
                                            <span class="display-block">130,001 - 150,000</span>
                                            <span class="display-block">150,001 - 160,000</span>
                                            <span class="display-block">160,001 - 170,000</span>
                                            <span class="display-block">170,001 - 180,000</span>
                                            <span class="display-block">180,001 - 190,000</span>
                                            <span class="display-block">190,001 - 200,000</span>
                                            <span class="display-block">200,001 and over</span>
                                        </td>
                                        <td width="50%" class="border-right text-center">
                                            <span class="display-block">0</span>
                                            <span class="display-block">1</span>
                                            <span class="display-block">2</span>
                                            <span class="display-block">3</span>
                                            <span class="display-block">4</span>
                                            <span class="display-block">5</span>
                                            <span class="display-block">6</span>
                                            <span class="display-block">7</span>
                                            <span class="display-block">8</span>
                                            <span class="display-block">9</span>
                                            <span class="display-block">10</span>
                                            <span class="display-block">11</span>
                                            <span class="display-block">12</span>
                                            <span class="display-block">13</span>
                                            <span class="display-block">14</span>
                                            <span class="display-block">15</span>
                                            <span class="display-block">16</span>
                                            <span class="display-block">17</span>
                                            <span class="display-block">18</span>
                                            <span class="display-block">19</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table class="table bordered-table table-border-collapse">
                                    <tr>
                                        <th colspan="2" class="text-center border-bottom"><strong>All Others</strong></th>
                                    </tr>
                                    <tr>
                                        <td class="border-right-bottom">If wages from LOWEST paying job are—</td>
                                        <td class="border-bottom">Enter on line 2 above</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="border-right text-center">
                                            <span class="display-block">$0 - $7,000</span>
                                            <span class="display-block">7,001 - 12,500</span>
                                            <span class="display-block">12,501 - 24,500</span>
                                            <span class="display-block">24,501 - 31,500</span>
                                            <span class="display-block">31,501 - 39,000</span>
                                            <span class="display-block">39,001 - 55,000</span>
                                            <span class="display-block">55,001 - 70,000</span>
                                            <span class="display-block">70,001 - 85,000</span>
                                            <span class="display-block">85,001 - 90,000</span>
                                            <span class="display-block">90,001 - 100,000</span>
                                            <span class="display-block">100,001 - 105,000</span>
                                            <span class="display-block">105,001 - 115,000</span>
                                            <span class="display-block">115,001 - 120,000</span>
                                            <span class="display-block">120,001 - 130,000</span>
                                            <span class="display-block">130,001 - 145,000</span>
                                            <span class="display-block">145,001 - 155,000</span>
                                            <span class="display-block">155,001 - 185,000</span>
                                            <span class="display-block">185,001 and over</span>
                                            <span class="display-block"></span>
                                            <span class="display-block"></span>
                                        </td>
                                        <td width="50%" class="border-right text-center">
                                            <span class="display-block">0</span>
                                            <span class="display-block">1</span>
                                            <span class="display-block">2</span>
                                            <span class="display-block">3</span>
                                            <span class="display-block">4</span>
                                            <span class="display-block">5</span>
                                            <span class="display-block">6</span>
                                            <span class="display-block">7</span>
                                            <span class="display-block">8</span>
                                            <span class="display-block">9</span>
                                            <span class="display-block">10</span>
                                            <span class="display-block">11</span>
                                            <span class="display-block">12</span>
                                            <span class="display-block">13</span>
                                            <span class="display-block">14</span>
                                            <span class="display-block">15</span>
                                            <span class="display-block">16</span>
                                            <span class="display-block">17</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>

        <div class="pdf-page-break"></div>

        <section class="pdf-cover-page" >
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-border-collapse">
                        <tr>
                            <th colspan="2" class="text-center"><strong>Table 2</strong></th>
                        </tr>
                        <tr>
                            <td>
                                <table class="table bordered-table table-border-collapse">
                                    <tr>
                                        <th colspan="2" class="text-center border-bottom"><strong>Married Filing Jointly</strong></th>
                                    </tr>
                                    <tr>
                                        <td class="border-right-bottom">If wages from <strong>HIGHEST</strong> paying job are—</td>
                                        <td class="border-bottom">Enter on line 7 above</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="border-right text-center">
                                            <span class="display-block">$0 - $24,375</span>
                                            <span class="display-block">24,376 - 82,725</span>
                                            <span class="display-block">82,726 - 170,325</span>
                                            <span class="display-block">170,326 - 320,325</span>
                                            <span class="display-block">320,326 - 405,325</span>
                                            <span class="display-block">405,326 - 605,325</span>
                                            <span class="display-block">605,326 and over</span>
                                        </td>
                                        <td class="border-right text-center">
                                            <span class="display-block">$420</span>
                                            <span class="display-block">500</span>
                                            <span class="display-block">910</span>
                                            <span class="display-block">1,000</span>
                                            <span class="display-block">1,330</span>
                                            <span class="display-block">1,450</span>
                                            <span class="display-block">1,540</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table class="table bordered-table table-border-collapse">
                                    <tr>
                                        <th colspan="2" class="text-center border-bottom"><strong>All Others</strong></th>
                                    </tr>
                                    <tr>
                                        <td class="border-right-bottom">If wages from <strong>HIGHEST</strong> paying job are—</td>
                                        <td class="border-bottom">Enter on line 7 above</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="border-right text-center">
                                            <span class="display-block">$0 - $7,000</span>
                                            <span class="display-block">7,001 - 36,175</span>
                                            <span class="display-block">36,176 - 79,975</span>
                                            <span class="display-block">79,976 - 154,975</span>
                                            <span class="display-block">154,976 - 197,475</span>
                                            <span class="display-block">197,476 - 497,475</span>
                                            <span class="display-block">497,476 and over</span>
                                        </td>
                                        <td width="50%" class="border-right text-center">
                                            <span class="display-block">$420</span>
                                            <span class="display-block">500</span>
                                            <span class="display-block">910</span>
                                            <span class="display-block">1,000</span>
                                            <span class="display-block">1,330</span>
                                            <span class="display-block">1,450</span>
                                            <span class="display-block">1,540</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <table class="table" id="footer-table">
                        <tbody>
                            <tr>
                                <td style="width: 33.3333%">
                                    <span class="heading-text">Privacy Act and Paperwork Reduction Act Notice.</span>
                                    We ask for the information on this form to carry out the Internal Revenue laws of the United States. Internal Revenue Code sections 3402(f)(2) and 6109 and their regulations require you to provide this information; your employer uses it to determine your federal income tax withholding. Failure to provide a properly completed form will result in your being treated as a single person who claims no withholding allowances; providing fraudulent information may subject you to penalties. Routine uses of this information include giving it to the Department of Justice for civil and criminal litigation; to cities, states, the District of Columbia, and

                                </td>
                                <td style="width: 33.3333%">
                                    <p>U.S. commonwealths and possessions for use in administering their tax laws; and to the Department of Health and Human Services for use in the National Directory of New Hires. We may also disclose this information to other countries under a tax treaty, to federal and state agencies to enforce federal nontax criminal laws, or to federal law enforcement and intelligence agencies to combat terrorism.</p>
                                    <p>You aren’t required to provide the information requested on a form that’s subject to the Paperwork Reduction Act unless the form displays a valid OMB control number. Books or records relating to a form or its instructions must be</p>
                                </td>
                                <td style="width: 33.3333%">
                                    <p>retained as long as their contents may become material in the administration of any Internal Revenue law. Generally, tax returns and return information are confidential, as required by Code section 6103.</p>
                                    <p>The average time and expenses required to complete and file this form will vary depending on individual circumstances. For estimated averages, see the instructions for your income tax return.</p>
                                    <p>If you have suggestions for making this form simpler, we would be happy to hear from you. See the instructions for your income tax return.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
            window.close();
        }
    </script>
</body>
</html>