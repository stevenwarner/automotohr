<?php if (!isset($doUpload)) { ?>
    <html>

    <head>
    <?php } ?>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0 0 10px;
            font-family: 'Source Sans Pro', sans-serif !important;
        }

        p,
        a,
        span,
        table,
        tr,
        td,
        th {
            font-family: 'Source Sans Pro';
        }

        .text-right {
            text-align: right;
        }

        .row {
            clear: both;
        }

        .row::after,
        .row::before {
            display: table;
            content: " ";
        }

        * {
            box-sizing: border-box;
        }

        /*.row{ margin:0 -15px;}*/
        body {
            font-family: 'Source Sans Pro' !important;
        }

        .main-wrapper-pdf {
            font-family: 'Source Sans Pro' !important;
        }

        .main-page-heading h1 {
            font-size: 22px !important;
            font-weight: bold;
            margin-bottom: 20px;
            margin-top: 30px;
        }

        .footer-heading h1 {
            font-size: 22px !important;
            font-weight: bold;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .col-md-4 {
            width: 33.33333333%;
        }

        .col-md-12,
        .col-lg-12 {
            width: 100%;
        }

        .clearfix {
            clear: both;
            overflow: hidden;
        }

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
        .col-xs-9 {
            float: left;
            padding-right: 15px;
            padding-left: 15px;
        }

        .text-center {
            text-align: center;
        }

        .icon-col img {
            display: block;
        }

        .table {
            width: 100%;
            max-width: 100%;
            background-color: transparent;
        }

        .table>thead>tr>th,
        .table>thead>tr>td,
        .table>tbody>tr>th,
        .table>tbody>tr>td {
            vertical-align: top;
            font-size: 11px;
            padding: 5px;
        }

        .table-striped>tbody>tr:nth-of-type(2n+1) {
            background-color: #f9f9f9;
        }

        #footer-table tbody tr td {
            padding: 5px;
        }

        .pdf-page-break {
            page-break-inside: avoid;
            page-break-after: always;
        }

        .default-border-disable {
            border: 0 none !important
        }

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

        .heading-text {
            font-weight: bold;
            font-size: 15px;
        }

        .text-bold {
            font-weight: bold;
        }

        .border-right-bottom-bold {
            border-right: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .border-bottom-bold {
            border-bottom: 2px solid #000;
        }

        .border-right-bottom {
            border-right: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .border-bottom {
            border-bottom: 1px solid #000;
        }

        .border-right {
            border-right: 1px solid #000;
        }

        .table-border-collapse {
            border-collapse: collapse;
        }

        .plane-input {
            border: none;
            font-weight: bold;
            display: block;
        }

        .input-with-bottom-border {
            border: none;
            border-bottom: 1px solid #000;
            width: 100%;
            height: 20px;
        }

        .main-heading {
            font-size: 26px;
            border-bottom: 2px solid #000;
        }

        .bordered-table {
            border: 2px solid #000;
        }

        .indicator-box {
            display: inline-block;
            margin: 5px 0 0 0;
        }

        .indicator-box-2 {
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

        .display-block-value {
            display: block;
            height: 20px;
        }

        .signature {
            font-size: 26px;
            font-family: 'Conv_SCRIPTIN' !important;
        }
    </style>
    <?php if (!isset($doUpload)) { ?>
    </head>

    <body class="A4">
    <?php } else { ?>
        <div class="A4 jsw4" style="overflow: hidden; margin-left: -1000px;">
        <?php } ?>

        <div class="sheet padding-10mm" style="width: 800px;padding: 10px 10mm; margin: 0 auto;">
            <?php if ($pre_form['user_consent'] != 0) { ?>
                <div class="row mb-2">
                    <?php if ($pre_form['uploaded_file'] != NULL) { ?>
                        <div class="col-lg-3 pull-right">
                            <a target="_blank" href="<?php echo base_url('hr_documents_management/download_upload_document/' . $pre_form['uploaded_file']); ?>" class="btn btn-success">Download Submitted Form</a>
                        </div>
                        <div class="col-lg-3 pull-right">
                            <?php $dcoument_extension = pathinfo($pre_form['uploaded_file'], PATHINFO_EXTENSION);

                            if (in_array($dcoument_extension, ['pdf'])) { ?>
                                <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $pre_form['uploaded_file']; ?>" class="btn btn-success btn-block">Print</a>
                            <?php } else if (in_array($dcoument_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                <a target="_blank" href="javascript:;" onclick="PrintElem('#w4_pdf')" class="btn btn-success btn-block">Print</a>
                            <?php } else if (in_array($dcoument_extension, ['doc', 'docx'])) { ?>
                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . AWS_S3_BUCKET_URL . $pre_form['uploaded_file']; ?>" class="btn btn-success btn-block">Print</a>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="col-lg-3 pull-right">
                            <a target="_blank" href="<?php echo base_url('form_w4/download_w4_form_2020' . '/' . $pre_form['user_type'] . '/' . $pre_form['employer_sid']); ?>" class="btn btn-success btn-block">Download PDF</a>
                        </div>
                        <div class="col-lg-3 pull-right">
                            <a target="_blank" href="<?php echo base_url('form_w4/print_w4_form_2020' . '/' . $pre_form['user_type'] . '/' . $pre_form['employer_sid']); ?>" class="btn btn-success btn-block">
                                Print PDF
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="row mb-8">
                    <div class="form">
                        <form method="post" action="<?php echo base_url('form_w4/upload_signed_form') ?>" enctype="multipart/form-data">
                            <div class="col-lg-10">
                                <label> Upload Signed Form</label>
                                <input type="file" name="upload_file" required="required" id="upload_file_w4" class="choose-file">

                            </div>
                            <div class="col-lg-2 pull-right">
                                <label class="transparent-label">test</label>
                                <input type="submit" class="btn btn-success btn-block btn-equalizer" value="Upload">
                            </div>
                            <input type="hidden" name="user_sid" value="<?php echo $pre_form['user_type'] == 'applicant' ? $applicant_info['sid'] : $employer['sid']; ?>">
                            <input type="hidden" name="current_url" value="<?php echo current_url(); ?>">
                            <input type="hidden" name="form_sid" value="<?php echo $pre_form['sid']; ?>">
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="sheet padding-10mm" id="w4_pdf" style="width: 800px; margin: 0 auto;">

            <?php if ($pre_form['uploaded_file'] == NULL) { ?>
                <section class="pdf-cover-page">
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
                                    <strong style="font-size: 10px;">▶ Your withholding is subject to review by the IRS.</strong><br>
                                </td>
                                <td class="border-bottom-bold" style="width: 20%; text-align: center;">
                                    <div class="border-bottom">OMB No. 1545-0074</div>
                                    <strong style="font-size: 32px;"><?php echo date('Y'); ?></strong>
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
                                    <strong style="font-size: 15px;">Step 1: <br>Enter <br>Personal <br>Information</strong>
                                </td>
                                <td class="border-right-bottom">
                                    <div style="width: 100%;" class="border-bottom">
                                        <div style="display:inline-block;">
                                            <span><strong>(a)</strong> First name and middle initial</span>
                                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['first_name']) ? $pre_form['first_name'] : '' ?> <?php echo !empty($pre_form['middle_name']) ? $pre_form['middle_name'] : '' ?>" readonly style="border: none; font-weight: bold; display: block;" />
                                        </div>
                                        <div style="width: 50%; float:right; border-left: 1px solid #000; padding-left: 5px;">
                                            <span>Last name</span>
                                            <input class="plane-input" type="text" value="<?php echo !empty($pre_form['last_name']) ? $pre_form['last_name'] : '' ?>" readonly style="border: none; font-weight: bold; display: block;" />
                                        </div>
                                    </div>
                                    <div style="width: 100%;" class="border-bottom">
                                        <span>Address</span>
                                        <input class="plane-input" type="text" value="<?php echo !empty($pre_form['home_address']) ? $pre_form['home_address'] : '' ?>" readonly style="border: none; font-weight: bold; display: block;" />
                                    </div>
                                    <div style="width: 100%;" class="border-bottom">
                                        <span>City or town, state, and ZIP code</span>
                                        <input class="plane-input" type="text" value="<?php echo !empty($pre_form['city']) ? $pre_form['city'] . ',' : '' ?> <?php echo !empty($pre_form['state']) ? $pre_form['state'] . ',' : '' ?> <?php echo !empty($pre_form['zip']) ? $pre_form['zip'] : '' ?> " readonly style="display: block; width: 100%; border: none; font-weight: bold; display: block;" />
                                    </div>
                                    <div style="width: 100%;">
                                        <div style="display:inline-block;">
                                            <span><strong>(c)</strong></span>
                                        </div>
                                        <div style="width: 95%; float:right; padding-left: 5px;">
                                            <div><input disabled type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'separately' ? 'checked="checked"' : '' ?> /><strong>Single or Married filing separately</strong></div>
                                            <div><input disabled type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'jointly' ? 'checked="checked"' : '' ?> /><strong>Married filing jointly</strong> (or Qualifying surviving spouse)</div>
                                            <div><input disabled type="checkbox" value="" readonly <?php echo !empty($pre_form['marriage_status']) && $pre_form['marriage_status'] == 'head' ? 'checked="checked"' : '' ?> /><strong>Head of household</strong> (Check only if you’re unmarried and pay more than half the costs of keeping up a home for yourself and a qualifying individual.)</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-bottom" style="width: 22%;">
                                    <div class="border-bottom">
                                        <strong><span>(b) Social security number</span></strong>
                                        <input class="plane-input" type="text" value="<?php echo !empty($pre_form['ss_number']) ? $pre_form['ss_number'] : '' ?>" readonly style="border: none; font-weight: bold; display: block;" />
                                    </div>
                                    <div>
                                        <strong style="font-size: 8px;">▶ Does your name match the name on your social security card?</strong>
                                        <p>If not, to ensure you get credit for your earnings, contact SSA at 800-772-1213 or go to www.ssa.gov.</p>
                                    </div>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="pdf-cover-page">
                    <table class="table table-border-collapse" style="margin-bottom: 0px;">
                        <tbody>
                            <tr>
                                <td colspan="3" class="border-bottom">
                                    <strong>Complete Steps 2–4 ONLY if they apply to you; otherwise, skip to Step 5.</strong>See page 2 for more information on each step, who can
                                    claim exemption from withholding, and when to use the estimator at www.irs.gov/W4App.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="pdf-cover-page">
                    <table class="table table-border-collapse" style="margin-bottom: 0px;">
                        <tbody>
                            <tr>
                                <td style="width: 20%;">
                                    <strong style="font-size: 15px;">Step 2: <br>Multiple Jobs <br>or Spouse <br>Works</strong>
                                </td>
                                <td>
                                    <p>Complete this step if you (1) hold more than one job at a time, or (2) are married filing jointly and your spouse
                                        also works. The correct amount of withholding depends on income earned from all of these jobs.</p>
                                    <p>Do <strong>only one</strong> of the following.</p>
                                    <p><strong>(a)</strong> Use the estimator at www.irs.gov/W4App for most accurate withholding for this step (and Steps 3–4). If you
                                        or your spouse have self-employment income, use this option; or</p>
                                    <p><strong>(b)</strong> Use the Multiple Jobs Worksheet on page 3 and enter the result in Step 4(c) below; <strong>or</strong></p>
                                    <p><strong>(c)</strong> If there are only two jobs total, you may check this box. Do the same on Form W-4 for the other job. This
                                        option is generally more accurate than (b) if pay at the lower paying job is more than half of the pay at the
                                        higher paying job. Otherwise, (b) is more accurate . . . . . ▶ <input type="checkbox" value="" disabled <?php echo !empty($pre_form['mjsw_status']) && isset($pre_form['marriage_status']) ? 'checked="checked"' : '' ?> /></p>
                                    <br>
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

                <section class="pdf-cover-page">
                    <table class="table table-border-collapse" style="margin-bottom: 0px;">
                        <tbody>
                            <tr>
                                <td style="width: 20%;" class="border-bottom">
                                    <strong style="font-size: 15px;">Step 3: <br>Claim <br>Dependents</strong>
                                </td>
                                <td class="border-bottom">
                                    <table>
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
                                                    &nbsp;&nbsp;Multiply the number of qualifying children under age 17 by $2,000 ▶ <input class="plane-input" type="text" value="<?php echo isset($pre_form['dependents_children']) ? $pre_form['dependents_children'] : '' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
                                                </td>
                                                <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">

                                                </td>
                                                <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;&nbsp;Multiply the number of other dependents by $500 . . . . ▶ <input class="plane-input" type="text" value="<?php echo isset($pre_form['other_dependents']) ? $pre_form['other_dependents'] : '' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
                                                </td>
                                                <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">

                                                </td>
                                                <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Add the amounts above for qualifying children and other dependents. You may add to
                                                    this the amount of any other credits. Enter the total here . . . . . . . . . .
                                                </td>
                                                <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                                    <strong>3</strong>
                                                </td>
                                                <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                                    <input class="plane-input" type="text" value="<?php echo isset($pre_form['claim_total_amount']) ? '$ ' . $pre_form['claim_total_amount'] : '$' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
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
                                <td style="width: 20%;" class="border-bottom">
                                    <strong style="font-size: 15px;">Step 4<br> (optional): <br>Other <br>Adjustments</strong>
                                </td>
                                <td class="border-bottom">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>(a) Other income (not from jobs).</strong> If you want tax withheld for other income you expect this year that won’t have withholding, enter the amount of other income here. This may include interest, dividends, and retirement income . . . . . . . . . . . .
                                                </td>
                                                <td style="width: 6%; border-left: 1px solid #000; border-bottom: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                                    <strong>4(a)</strong>
                                                </td>
                                                <td style="width: 16%; border-left: 1px solid #000; border-bottom: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                                    <input class="plane-input" type="text" value="<?php echo isset($pre_form['other_income']) ? '$ ' . $pre_form['other_income'] : '$' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
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
                                                    <input class="plane-input" type="text" value="<?php echo isset($pre_form['other_deductions']) ? '$ ' . $pre_form['other_deductions'] : '$' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>(c) Extra withholding.</strong> Enter any additional tax you want withheld each pay period .
                                                </td>
                                                <td style="width: 6%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                                    <strong>4(c)</strong>
                                                </td>
                                                <td style="width: 16%; border-left: 1px solid #000; padding-left: 5px; vertical-align:bottom;">
                                                    <input class="plane-input" type="text" value="<?php echo isset($pre_form['additional_tax']) ? '$ ' . $pre_form['additional_tax'] : '$' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
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
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Under penalties of perjury, I declare that this certificate, to the best of my knowledge and belief, is true, correct, and complete.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?php echo $pre_form['signature_bas64_image']; ?>" />
                                                </td>
                                                <td style="width: 30%;">
                                                    <input class="plane-input" type="text" value="<?php echo !empty($pre_form['signature_timestamp']) && $pre_form['signature_timestamp'] != '0000-00-00 00:00:00' ? reset_datetime(array('datetime' => $pre_form['signature_timestamp'], '_this' => $this)) : ''; ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p style="font-size: 15px;"><strong>Employee’s signature</strong> (This form is not valid unless you sign it.)</p>

                                                </td>
                                                <td style="width: 30%;">
                                                    <strong style="font-size: 15px;">Date</strong>
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
                                    <strong style="font-size: 15px;">Employers <br>Only </strong>
                                </td>
                                <td class="border-right-bottom">
                                    Employer’s name and address
                                    <input class="plane-input" type="text" value="<?php echo !empty($pre_form['emp_name']) ? $pre_form['emp_name'] : '' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" /> , <input class="plane-input" type="text" value="<?php echo !empty($pre_form['emp_address']) ? $pre_form['emp_address'] : '' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
                                </td>
                                <td style="width: 20%;" class="border-right-bottom">
                                    First date of employment
                                    <input class="plane-input" type="text" value="<?php echo !empty($pre_form['first_date_of_employment']) && $pre_form['first_date_of_employment'] != '0000-00-00 00:00:00' ? reset_datetime(array('datetime' => $pre_form['first_date_of_employment'], '_this' => $this)) : ''; ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
                                </td>
                                <td style="width: 30%;" class="border-bottom">
                                    Employer identification number (EIN)
                                    <input class="plane-input" type="text" value="<?php echo isset($pre_form['emp_identification_number']) ? $pre_form['emp_identification_number'] : '' ?>" readonly style="border: none; font-weight: bold; display: inline-block;" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="pdf-cover-page">
                    <table class="table table-border-collapse" style="margin-bottom: 0px;">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    <h3>General Instructions</h3>
                                    <br> Section references are to the Internal Revenue Code.<br>
                                    <span class="heading-text">Future Developments</span><br> For the latest information about developments related to Form W-4, such as legislation enacted after it was published, go to www.irs.gov/FormW4.<br>
                                    <span class="heading-text">Purpose of Form</span><br> Complete Form W-4 so that your employer can withhold the correct federal income tax from your pay. If too little is withheld, you will generally owe tax when you file your tax return and may owe a penalty. If too much is withheld, you will generally be due a refund. Complete a new Form W-4 when changes to your personal or financial situation would change the entries on the form. For more information on withholding and when you must furnish a new Form W-4, see Pub. 505.Tax Withholding and Estimated Tax.<br>
                                    <span class="heading-text">Exemption from withholding.</span> <?php echo W4_EXEMPTION_FROM_WITHHOLDING_24; ?><br>
                                    <span class="heading-text">Your privacy.</span>
                                    Steps 2(c) and 4(a) ask for information
                                    regarding income you received from sources other than the
                                    job associated with this Form W-4. If you have concerns with
                                    providing the information asked for in Step 2(c), you may
                                    choose Step 2(b) as an alternative; if you have concerns with
                                    providing the information asked for in Step 4(a), you may
                                    enter an additional amount you want withheld per pay period
                                    in Step 4(c) as an alternative. <br>


                                    <span class="heading-text">When to use the estimator.</span> Consider using the estimator at www.irs.gov/W4App if you:<br>
                                    1. Expect to work only part of the year; <br>
                                    2. Receive dividends, capital gains, social security, bonuses,
                                    or business income, or are subject to the Additional
                                    Medicare Tax or Net Investment Income Tax; or <br>
                                    3. Prefer the most accurate withholding for multiple job
                                    situations.<br>

                                    <span class="heading-text">Self-employment.</span>
                                    Generally, you will owe both income and
                                    self-employment taxes on any self-employment income you
                                    receive separate from the wages you receive as an
                                    employee. If you want to pay these taxes through
                                    withholding from your wages, use the estimator at
                                    www.irs.gov/W4App to figure the amount to have withheld.
                                    <br>
                                    <span class="heading-text">Nonresident alien.</span> If you’re a nonresident alien, see Notice 1392, Supplemental Form W-4 Instructions for Nonresident Aliens, before completing this form <br>
                                </td>
                                <td style="width: 50%;">
                                    <h3>Specific Instructions</h3>
                                    <span class="heading-text">Step 1(c).</span> Check your anticipated filing status. This will determine the standard deduction and tax rates used to compute your withholding.<br>
                                    <span class="heading-text">Step 2. </span>

                                    Use this step if you (1) have more than one job at the
                                    same time, or (2) are married filing jointly and you and your
                                    spouse both work.<br>

                                    Option (a) most accurately calculates the additional tax
                                    you need to have withheld, while option (b) does so with a
                                    little less accuracy

                                    <br>
                                    &nbsp;&nbsp;Instead, if you (and your spouse) have a total of only two
                                    jobs, you may check the box in option (c). The box must also
                                    be checked on the Form W-4 for the other job. If the box is
                                    checked, the standard deduction and tax brackets will be
                                    cut in half for each job to calculate withholding. This option
                                    is accurate for jobs with similar pay; otherwise, more tax
                                    than necessary may be withheld, and this extra amount will
                                    be larger the greater the difference in pay is between the two
                                    jobs.
                                    ▲!
                                    CAUTION
                                    Multiple jobs. Complete Steps 3 through 4(b) on only
                                    one Form W-4. Withholding will be most accurate if
                                    you do this on the Form W-4 for the highest paying job. <br>

                                    <span class="heading-text">Step 3.</span> This step provides instructions for determining the amount of the child tax credit and the credit for other dependents that you may be able to claim when you file your tax return. To qualify for the child tax credit, the child must be under age 17 as of December 31, must be your dependent who generally lives with you for more than half the year, and must have the required social security number. You may be able to claim a credit for other dependents for whom a child tax credit can’t be claimed, such as an older child or a qualifying relative. For additional eligibility requirements for these credits, see Pub. 501, Dependents, Standard Deduction, and Filing Information. You can also include other tax credits for which you are eligible in this step, such as the foreign tax credit and the education tax credits. To do so, add an estimate of the amount for the year to your credits for dependents and enter the total amount in Step 3. Including these credits will increase your paycheck and reduce the amount of any refund you may receive when you file your tax return.<br>

                                    <span class="heading-text">Step 4 (optional).</span><br>
                                    <span class="heading-text">Step 4(a).</span> Enter in this step the total of your other
                                    estimated income for the year, if any. You shouldn’t include
                                    income from any jobs or self-employment. If you complete
                                    Step 4(a), you likely won’t have to make estimated tax
                                    payments for that income. If you prefer to pay estimated tax
                                    rather than having tax on other income withheld from your
                                    paycheck, see Form 1040-ES, Estimated Tax for Individuals.<br>
                                    <span class="heading-text">Step 4(b).</span> Enter in this step the amount from the
                                    Deductions Worksheet, line 5, if you expect to claim
                                    deductions other than the basic standard deduction on your
                                    2024 tax return and want to reduce your withholding to
                                    account for these deductions. This includes both itemized
                                    deductions and other deductions such as for student loan
                                    interest and IRAs.<br>
                                    <span class="heading-text">Step 4(c).</span>Enter in this step any additional tax you want
                                    withheld from your pay each pay period, including any
                                    amounts from the Multiple Jobs Worksheet, line 4. Entering
                                    an amount here will reduce your paycheck and will either
                                    increase your refund or reduce any amount of tax that you
                                    owe.<br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="pdf-cover-page">
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
                                        If you choose the option in Step 2(b) on Form W-4, complete this worksheet (which calculates the total extra tax for all jobs) on <strong>only ONE</strong> Form W-4. Withholding will be most accurate if you complete the worksheet and enter the result on the Form W-4 for the highest paying job.To be accurate, submit a new Form W-4 for all other jobs if you have not updated your withholding since 2019.
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-center border-bottom">
                                        <strong>Note:</strong> If more than one job has annual wages of more than $120,000 or there are more than three jobs, see Pub. 505 for additional tables.
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
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_two_jobs']) ? '$ ' . $pre_form['mjw_two_jobs'] : '$' ?>" readonly />
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
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_three_jobs_a']) ? '$ ' . $pre_form['mjw_three_jobs_a'] : '$' ?>" readonly />
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
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_three_jobs_b']) ? '$ ' . $pre_form['mjw_three_jobs_b'] : '$' ?>" readonly />
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
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_three_jobs_c']) ? '$ ' . $pre_form['mjw_three_jobs_c'] : '$' ?>" readonly />
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
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_pp_py']) ? $pre_form['mjw_pp_py'] : '' ?>" readonly />
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
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['mjw_divide']) ? '$ ' . $pre_form['mjw_divide'] : '$' ?>" readonly />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
                <br>
                <section class="pdf-cover-page">
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
                                        Enter an estimate of your 2024 itemized deductions (from Schedule A (Form 1040 or 1040-SR)). Such deductions may include qualifying home mortgage interest, charitable contributions, state and local taxes (up to $10,000), and medical expenses in excess of 7.5% of your income . . . . . . .
                                    </td>
                                    <td width="2%">
                                        <strong class="indicator-box-2">1</strong>
                                    </td>
                                    <td width="10%">
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_1']) ? '$ ' . $pre_form['dw_input_1'] : '$' ?>" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">
                                        <strong class="indicator-box">2</strong>
                                    </td>
                                    <td width="86%">
                                        Enter:<br>
                                        $29,200 if you’re married filing jointly or a qualifying surviving spouse <br>
                                        $21,900 if you’re head of household <br>
                                        $14,600 if you’re single or married filing separately <br>
                                    </td>
                                    <td width="2%">
                                        <strong class="indicator-box-2">2</strong>
                                    </td>
                                    <td width="10%">
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_2']) ? '$ ' . $pre_form['dw_input_2'] : '$' ?>" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">
                                        <strong class="indicator-box">3</strong>
                                    </td>
                                    <td width="86%">
                                        If line 1 is greater than line 2, subtract line 2 from line 1 and enter the result here. If line 2 is greater
                                        than line 1, enter “-0-” ..
                                    </td>
                                    <td width="2%">
                                        <strong class="indicator-box-2">3</strong>
                                    </td>
                                    <td width="10%">
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_3']) ? '$ ' . $pre_form['dw_input_3'] : '$' ?>" readonly />
                                    </td>
                                </tr>
                                <tr>
                                    <td width="2%">
                                        <strong class="indicator-box">4</strong>
                                    </td>
                                    <td width="86%">
                                        Enter an estimate of your student loan interest, deductible IRA contributions, and certain other
                                        adjustments (from Part II of Schedule 1 (Form 1040)). See Pub. 505 for more information
                                    </td>
                                    <td width="2%">
                                        <strong class="indicator-box-2">4</strong>
                                    </td>
                                    <td width="10%">
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_4']) ? '$ ' . $pre_form['dw_input_4'] : '$' ?>" readonly />
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
                                        <input class="input-with-bottom-border" style="border: none; border-bottom: 1px solid #000; width: 100%; height: 20px;" type="text" value="<?php echo isset($pre_form['dw_input_5']) ? '$ ' . $pre_form['dw_input_5'] : '$' ?>" readonly />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="pdf-cover-page">
                    <div class="container-fluid">
                        <table class="table" id="footer-table">
                            <tbody>
                                <tr>
                                    <td style="width: 50%;">
                                        <span class="heading-text">Privacy Act and Paperwork Reduction Act Notice. </span>
                                        We ask for the information
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
                                        and intelligence agencies to combat terrorism
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

                <?php $this->load->view("form_w4/partials/married_filing_2024"); ?>

                <br>
                <?php $this->load->view("form_w4/partials/single_married_2024"); ?>

                <br>
                <?php $this->load->view("form_w4/partials/head_of_household_2024"); ?>

            <?php } else { ?>
                <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em;">
                    <?php $document_filename = !empty($pre_form['uploaded_file']) ? $pre_form['uploaded_file'] : '';
                    $dcoument_extension = pathinfo($document_filename, PATHINFO_EXTENSION);

                    if (in_array($dcoument_extension, ['pdf'])) { ?>
                        <iframe src="<?php echo 'https://docs.google.com/gview?url=' . urlencode(AWS_S3_BUCKET_URL . $document_filename) . '&embedded=true'; ?>" id="preview_iframe" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                    <?php } else if (in_array($dcoument_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>" />
                    <?php } else if (in_array($dcoument_extension, ['doc', 'docx'])) { ?>
                        <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" id="preview_iframe" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php if (isset($doUpload)) { ?>
            <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
        <?php } ?>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
        <script type="text/javascript">
            <?php if (isset($doUpload)) { ?>

                function uploadPDF(data, typo) {
                    $.post("<?= base_url('hr_documents_management/upload'); ?>", {
                        data: data,
                        token: "<?= $token; ?>",
                        employeeSid: "<?= $employeeSid; ?>",
                        userFullNameSlug: "<?= $userFullNameSlug; ?>",
                        type: typo,
                    }, () => {
                        has['W4'] = true;
                    });
                }
            <?php } ?>

            $('#w4_download_pdf').on("click", pdfGen);


            function pdfGen() {
                var draw = kendo.drawing;
                draw.drawDOM($("#w4_pdf"), {
                        avoidLinks: false,
                        paperSize: "auto",
                        multiPage: true,
                        margin: {
                            bottom: "1cm"
                        },
                        scale: 0.8
                    })
                    .then(function(root) {
                        return draw.exportPDF(root);
                    })
                    .done(function(data) {
                        <?php if (isset($doUpload)) { ?>
                            uploadPDF(data, 'w4');
                        <?php } else { ?>
                            $('#myiframe').attr("src", data);
                            kendo.saveAs({
                                dataURI: data,
                                fileName: '<?php echo "W4form.pdf"; ?>',
                            });
                        <?php } ?>
                    });
            }



            <?php if (isset($doUpload)) { ?>
                pdfGen();
            <?php } ?>

            function PrintElem(elem) {
                $(".bottom-buttons").hide();
                Popup($(elem).html());
                $(".bottom-buttons").show();
            }

            function Popup(data) {
                var mywindow = window.open('', 'Print Invoice', 'height=800,width=1200');
                mywindow.document.write('<html><head><title>Form W4</title>');
                /*optional stylesheet*/
                mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');
                mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
                mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
                mywindow.document.write('</head><body >');
                mywindow.document.write(data);
                mywindow.document.write('</body></html>');
                mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
                mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10
                return true;
            }
        </script>
        <?php if (!isset($doUpload)) { ?>
    </body>

    </html>
<?php } else { ?>
    </div>
<?php } ?>