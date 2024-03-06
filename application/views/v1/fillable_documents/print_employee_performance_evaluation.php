<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <style>
        @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');

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

        .invoice-fields {
            border-width: 2px;
            padding: 0;

            background-color: transparent;
            border: none;
            border-bottom: 1px solid #000;
            border-radius: 0;
            height: auto;
        }
    </style>
</head>

<body class="A4">
    <div class="sheet padding-10mm" id="jsFormDownload" style="width: 800px; margin-left: 20; margin-right: 20; margin-top: 20;">
        <section class="pdf-cover-page">
            <table class="table-border-collapse">
                <tbody>
                    <tr>
                        <td>
                            <strong style="font-size: 20px;">Eemployee Performance Evaluation </strong><br><br>
                            <strong style="font-size: 10px;"> How to complete the Employee Performance Evaluation Process:
                                <br><strong style="font-size: 14px;"> Section 1: </strong> This section will be completed by the immediate Manager of the employee. The Manager will complete this section ahead of the scheduled performance evaluation meeting with the employee.
                                <br> <em style="color: #ea0000;"> The manager must send their completed portion of the performance evaluation to human resources for review PRIOR to the meeting with the employee. Once HR has reviewed the Performance Evaluation and has sent this back to you, you can then meet with the employee.</em></strong><br>

                            <strong style="font-size: 10px;"><br><strong style="font-size: 14px;"> Section 2: </strong>
                                The Manager will send Section 2 to the employee ahead of the scheduled performance evaluation meeting. The employee will complete this section on their own and hold onto this until their scheduled performance evaluation meeting with their manager.
                            </strong><br>

                            <strong style="font-size: 10px;"><br><strong style="font-size: 14px;"> Section 3: </strong>
                                The Manager will schedule the performance evaluation meeting with the employee. The Manager and the employee will complete section 3 together by providing any additional commitments, goals, and feedback.
                            </strong><br>

                            <strong style="font-size: 10px;"><br><strong style="font-size: 14px;"> Section 4: </strong>
                                Once the Manager and the employee have met and completed the performance evaluation process, they will both sign the completed form
                            </strong><br>

                            <strong style="font-size: 10px;"><br><strong style="font-size: 14px;"> Section 5: </strong>
                                The Manager may make a recommendation for salary changes. The form will then be sent to Human Resources for final approval and a review of the salary recommendation will be sent to the Director. The performance evaluation then becomes part of the employee’s personnel file.
                            </strong><br>

                            <strong style="font-size: 10px;"><br><strong style="font-size: 14px;"> Tips: </strong>
                                The employee should have the Manager’s undivided attention during the performance evaluation meeting. The Manager should articulate the employee’s strengths and, if there are any improvement opportunities, the Manager should propose suggestions on how the employee can improve. During the dialog with the employee, the Manager will review the employee’s feedback on the form they filled out and that of the Manager. <br>
                                This performance evaluation discussion is intended to be a constructive exchange relative to the individual's past performance, improvement opportunities, and future expectations. It offers a chance for the employee to improve in areas that are needed. The dialogue should be a two-way conversation between the employee and the Manager.

                            </strong><br>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;"><br><br>
                            <strong style="font-size: 14px;">Manager Section 1: Employee Year in Review Evaluation
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee Name</strong>
                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Job Title</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <?php echo $sectionsdata['section1']['data']['empName'] ? $sectionsdata['section1']['data']['empName'] : ''; ?>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <?php echo $sectionsdata['section1']['data']['empJobTitle'] ? $sectionsdata['section1']['data']['empJobTitle'] : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Department </strong>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Manager</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <?php echo $sectionsdata['section1']['data']['empDepartment'] ? $sectionsdata['section1']['data']['empDepartment'] : ''; ?>
                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <?php echo $sectionsdata['section1']['data']['empManager'] ? $sectionsdata['section1']['data']['empManager'] : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Hire Date with DeFOUW Automotive </strong>
                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Start Date in Current Position</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <?php echo $sectionsdata['section1']['data']['empHireDate'] ? $sectionsdata['section1']['data']['empHireDate'] : ''; ?>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <?php echo $sectionsdata['section1']['data']['empStartDate'] ? $sectionsdata['section1']['data']['empStartDate'] : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Review Period Start </strong>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Review Period End </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <?php echo $sectionsdata['section1']['data']['reviewPeriodStart'] ? $sectionsdata['section1']['data']['reviewPeriodStart'] : ''; ?>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <?php echo $sectionsdata['section1']['data']['reviewPeriodEnd'] ? $sectionsdata['section1']['data']['reviewPeriodEnd'] : ''; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Rate the employee in each area below. Comments are required for each section. </strong><br>
                            <strong style="font-size: 14px;"> POSITION KNOWLEDGE: </strong> To what level is this employee knowledgeable of the job duties of the position to include methods, procedures, standard practices, and techniques? This may have been acquired through formal training, education and/or experience.
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Knowledge is below the minimum requirements of the position. Improvement is mandatory. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Knowledge is sufficient to perform the requirements of the position.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is exceptionally well informed and competent in all aspects of the position..</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['knowledgeBelow'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['knowledgeSufficient'] == '1' ? 'checked' : '' ?>>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['knowledgeExceptionally'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['knowledgeComment'] ? $sectionsdata['section1']['data']['knowledgeComment'] : ''; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s position knowledge be improved?. <?= $formInputData['short_textbox_9'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>
                            <strong style="font-size: 14px;"> QUANTITY OF WORK: </strong> Evaluate the quantity of work produced.
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Output is below that required of the position. Improvement is mandatory. </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Output meets that required of the position.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Output consistently exceeds that required of the position.</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['outputBelow'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['outputMeets'] == '1' ? 'checked' : '' ?>>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['outputConsistently'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>

                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['outputComment'] ? $sectionsdata['section1']['data']['outputComment'] : ''; ?>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s quantity of work be improved?. <?= $formInputData['short_textbox_11'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>
                            <strong style="font-size: 14px;"> QUANTITY OF WORK: </strong> Evaluate the quality of work produced in accordance with requirements for accuracy, completeness, and attention to detail.
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Quality of work is frequently below position requirements. Improvement is mandatory. </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Quality of work meets position requirements.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Quality of work consistently exceeds position requirements.</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['qualityBelow'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['qualityMeets'] == '1' ? 'checked' : '' ?>>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['qualityConsistently'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['qualityComment'] ? $sectionsdata['section1']['data']['qualityComment'] : ''; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s quantity of work be improved?. <?= $formInputData['short_textbox_13'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>
                            <strong style="font-size: 14px;"> INTERPERSONAL RELATIONS: </strong> o what level does this individual demonstrate cooperative behavior and contribute to a supportive work environment?.
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is frequently non-supportive. Improvement is mandatory. </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately contributes to supportive environment.</strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee consistently contributes to supportive work environment.</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeFrequently'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeAdequately'] == '1' ? 'checked' : '' ?>>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeConsistently'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['employeeComment'] ? $sectionsdata['section1']['data']['employeeComment'] : ''; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s interpersonal relations be improved?. <?= $formInputData['short_textbox_15'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>
                            <strong style="font-size: 14px;"> Mission: </strong> To what level does the employees work support the Mission of the organization; To what level does the employee make themselves available to respond to needs of others both internally and externally?
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Level of mission focus is often below the required/acceptable standard. Improvement is mandatory. </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately contributes to high quality mission.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee consistently demonstrates exceptional commitment to the mission.</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['missionBelow'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['missionHigh'] == '1' ? 'checked' : '' ?>>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['missionExceptional'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['missionComment'] ? $sectionsdata['section1']['data']['missionComment'] : ''; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s customer service skills/delivery be improved?. <?= $formInputData['short_textbox_17'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>
                            <strong style="font-size: 14px;"> DEPENDABILITY: </strong> To what level is the employee dependable; How often does the employee show up to work on time and complete their scheduled shifts? Can the employee be counted on to complete tasks and meet deadlines consistently?
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is late, absent, misses deadlines. Improvement is mandatory. </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately attends work, rarely misses or late, meets deadlines.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee consistently on time, at work and completes deadlines ahead of schedule.</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeLate'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeAdequatelyAttends'] == '1' ? 'checked' : '' ?>>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeOnTime'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['employeeTimeComment'] ? $sectionsdata['section1']['data']['employeeTimeComment'] : ''; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s dependability be improved?. <?= $formInputData['short_textbox_19'] ? $formInputData['short_textbox_19'] : '' ?> </strong><br>
                            <strong style="font-size: 14px;"> ADHERENCE TO POLICY & PROCEDURE: </strong> To what level does the employee adhere to standard operating policies and procedures?
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is frequently coached on standard operating policies and procedures. Improvement is mandatory. </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately adheres to standard operating policies and procedures with few reminders.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is consistently exceptional in following standard operating policies and procedures..</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeFrequentlyCoached'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeAdequatelyAdheres'] == '1' ? 'checked' : '' ?>>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeConsistentlyExceptional'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['employeeFrequentlyCoachedComment'] ? $sectionsdata['section1']['data']['employeeFrequentlyCoachedComment'] : ''; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s adherence to policy and procedure be improved?. <?= $formInputData['short_textbox_21'] ? $formInputData['short_textbox_21'] : '' ?> </strong><br>
                            <strong style="font-size: 14px;"> OTHER: </strong> <?= $formInputData['short_textbox_22'] ? $formInputData['short_textbox_22'] : '' ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee frequently falls below acceptable standard as outlined above. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately meets standard as outlined above.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is consistently exceptional in meeting performance standard.</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeOutlinedAbove'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeOutlinedStandardAbove'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeeOutlinedStandard'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['employeeOutlinedComment'] ? $sectionsdata['section1']['data']['employeeOutlinedComment'] : ''; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;"> How may employee’s performance in meeting this standard be improved? <?= $formInputData['short_textbox_24'] ? $formInputData['short_textbox_24'] : '' ?> </strong><br>
                            <strong style="font-size: 14px;"> OTHER: </strong> <?= $formInputData['short_textbox_25'] ? $formInputData['short_textbox_25'] : '' ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee frequently falls below acceptable standard as outlined above. </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately meets standard as outlined above.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is consistently exceptional in meeting performance standard.</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeePerformanceOutlinedAbove'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeePerformanceOutlinedStandard'] == '1' ? 'checked' : '' ?>>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox' <?= $sectionsdata['section1']['data']['employeePerformanceOutlinedExceptional'] == '1' ? 'checked' : '' ?>>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['employeePerformanceComment'] ? $sectionsdata['section1']['data']['employeePerformanceComment'] : ''; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>How may employee’s performance in meeting this standard be improved?</strong> <?= $formInputData['short_textbox_27'] ? $formInputData['short_textbox_27'] : '' ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Managers Additional Comments for the Review Period: </strong>
                                            <br>
                                            <?php echo $sectionsdata['section1']['data']['managersAdditionalComments'] ? $sectionsdata['section1']['data']['managersAdditionalComments'] : ''; ?>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Manager Section 2: The Year in Review
                                <br>
                                List 3-4 top accomplishments & add a reflection on overall performance for the year.
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td width="10%" style="border: 1px solid; font-size: 14px;  width:10%">
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Accomplishment </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee Comments/Reflection </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            1
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['accomplishment1'] ? $sectionsdata['section2']['data']['accomplishment1'] : '' ?>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['accomplishment1_emp_comment'] ? $sectionsdata['section2']['data']['accomplishment1_emp_comment'] : '' ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            2
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['accomplishment2'] ? $sectionsdata['section2']['data']['accomplishment2'] : '' ?>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['accomplishment2_emp_comment'] ? $sectionsdata['section2']['data']['accomplishment2_emp_comment'] : '' ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            3
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['accomplishment3'] ? $sectionsdata['section2']['data']['accomplishment3'] : '' ?>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['accomplishment3_emp_comment'] ? $sectionsdata['section2']['data']['accomplishment3_emp_comment'] : '' ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;"> 4
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['accomplishment4'] ? $sectionsdata['section2']['data']['accomplishment4'] : '' ?>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['accomplishment4_emp_comment'] ? $sectionsdata['section2']['data']['accomplishment4_emp_comment'] : '' ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">

            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;"><br><br>
                            <strong style="font-size: 14px;">
                                Opportunities for Improved Performance: List 2-4 areas of improvement & how you will work on these improvements over the coming year. </strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td width="10%" style="border: 1px solid; font-size: 14px;  width:10%">
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Opportunity for Improvement </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee Comments </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            1
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['opportunity1'] ? $sectionsdata['section2']['data']['opportunity1'] : '' ?>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['opportunity1_emp_comment'] ? $sectionsdata['section2']['data']['opportunity1_emp_comment'] : '' ?> </td>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid; font-size: 14px;">
                            2
                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['opportunity2'] ? $sectionsdata['section2']['data']['opportunity2'] : '' ?>

                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['opportunity2_emp_comment'] ? $sectionsdata['section2']['data']['opportunity2_emp_comment'] : '' ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid; font-size: 14px;">
                            3
                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['opportunity3'] ? $sectionsdata['section2']['data']['opportunity3'] : '' ?>

                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['opportunity3_emp_comment'] ? $sectionsdata['section2']['data']['opportunity3_emp_comment'] : '' ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid; font-size: 14px;"> 4
                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['opportunity4'] ? $sectionsdata['section2']['data']['opportunity4'] : '' ?>
                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['opportunity4_emp_comment'] ? $sectionsdata['section2']['data']['opportunity4_emp_comment'] : '' ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            </tr>
            </tbody>
            </table>
        </section>
        <section class="pdf-cover-page">
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                List 2-3 goals for the upcoming year. One must reflect a personal development goal.
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td width="10%" style="border: 1px solid; font-size: 14px;  width:10%">
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Goal </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> General comments and summary relating to the status of the goal, attainment, difficulty of goal, and impacting factors. </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            1
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['goal1'] ? $sectionsdata['section2']['data']['goal1'] : '' ?>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <?= $sectionsdata['section2']['data']['goal1_emp_comment'] ? $sectionsdata['section2']['data']['goal1_emp_comment'] : '' ?> </td>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid; font-size: 14px;">
                            2
                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['goal2'] ? $sectionsdata['section2']['data']['goal2'] : '' ?>

                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['goal2_emp_comment'] ? $sectionsdata['section2']['data']['goal2_emp_comment'] : '' ?> </td>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid; font-size: 14px;">
                            3
                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['goal3'] ? $sectionsdata['section2']['data']['goal3'] : '' ?>

                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['goal3_emp_comment'] ? $sectionsdata['section2']['data']['goal3_emp_comment'] : '' ?> </td>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid; font-size: 14px;"> 4
                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['goal4'] ? $sectionsdata['section2']['data']['goal4'] : '' ?>

                        </td>
                        <td style="border: 1px solid; font-size: 14px;">
                            <?= $sectionsdata['section2']['data']['goal4_emp_comment'] ? $sectionsdata['section2']['data']['goal4_emp_comment'] : '' ?> </td>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid; font-size: 14px;" colspan="3">
                            <strong> Have you and your manager reviewed your job description for this review period? </strong><br>
                            <strong style="font-size: 14px;" name="termination_voluntary"> <?= $sectionsdata['section2']['data']['selectDD0'] == 1 ? 'Yes' : '' ?> </strong>
                            <strong style="font-size: 14px;" name="termination_voluntary"> <?= $sectionsdata['section2']['data']['selectDD0'] != 1 ? 'No' : '' ?> </strong>

                        </td>
                    </tr>

                    <tr>

                        <td style="border: 1px solid; font-size: 14px;" colspan="3">
                            <strong> 2.Do you have access to equipment and resources necessary to perform your job function?
                                (If No, please list the equipment you deem necessary subject to Managers approval and budgeting) </strong> <br>
                            <strong style="font-size: 14px;" name="termination_voluntary"><?= $sectionsdata['section2']['data']['selectDD1'] == 1 ? 'Yes' : '' ?></strong><br>
                            <strong style="font-size: 14px;" name="termination_voluntary"><?= $sectionsdata['section2']['data']['selectDD1'] != 1 ? 'No' : '' ?></strong>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid; font-size: 14px;" colspan="3">
                            <strong> 3.Is there any additional support or training you feel would be helpful for DeFOUW Automotive to provide for you to help you succeed in your current role?</strong><br>
                            <strong style="font-size: 14px;" name="termination_voluntary"><?= $sectionsdata['section2']['data']['selectDD2'] == 1 ? 'Yes' : '' ?> </strong><br>
                            <strong style="font-size: 14px;" name="termination_voluntary"><?= $sectionsdata['section2']['data']['selectDD2'] != 1  ? 'No' : '' ?> </strong>
                    </tr>
                    <tr>

                        <td style="border: 1px solid; font-size: 14px;" colspan="3">
                            <strong>Employee Additional Comments:</strong> <br>
                            <?php echo $sectionsdata['section2']['data']['additional_comment'] ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            </tr>
            </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Section 3: The Year in Review </strong><br>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Additional Comments, Feedback - Managers Comments: <br>
                                <?php echo $sectionsdata['section3']['data']['section3ManagerComment'] ?>
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Additional Comments, Feedback - Employee Comments: <br>
                                <?php echo $sectionsdata['section3']['data']['section3EmployeeComment'] ?>

                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse table">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Section 4: Signatures </strong>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee Date </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Manager Date. </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">

                                            <span><strong> Signature:</strong>
                                                <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>; vertical-align: middle;" src="<?php echo $sectionsdata['section4']['data']['section4employeeSignature']; ?>" /> </span>
                                            <br>
                                            <span><strong>Signature Date: </strong><?php echo $sectionsdata['section4']['data']['section4employeeSignatureDate'] ? formatDateToDB($sectionsdata['section4']['data']['section4employeeSignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?> </span>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <span><strong>Signature:</strong><img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>; vertical-align: middle;" src="<?php echo $sectionsdata['section4']['data']['section4managerSignature'] ? $sectionsdata['section4']['data']['section4managerSignature'] : ''; ?>" />
                                            </span><br>
                                            <span><strong>Signature Date: </strong><?php echo $sectionsdata['section4']['data']['section4employeeSignatureDate'] ? formatDateToDB($sectionsdata['section4']['data']['section4employeeSignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?> </span>

                                        </td>
                                    </tr>

                                    <tr>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Next Level Approval Date </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Human Resources Date. </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <span><strong>Signature:</strong><img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>; vertical-align: middle;" src="<?php echo $sectionsdata['section4']['data']['section4nextLevelSignature'] ? $sectionsdata['section4']['data']['section4nextLevelSignature'] : ''; ?>" />
                                            </span><br>
                                            <span><strong>Signature Date: </strong><?php echo $sectionsdata['section4']['data']['section4nextLevelSignatureDate'] ? formatDateToDB($sectionsdata['section4']['data']['section4nextLevelSignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?> </span>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <span><strong>Signature:</strong><img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>; vertical-align: middle;" src="<?php echo $sectionsdata['section4']['data']['section4hrSignature'] ? $sectionsdata['section4']['data']['section4hrSignature'] : ''; ?>" />
                                            </span><br>
                                            <span><strong>Signature Date: </strong><?php echo $sectionsdata['section4']['data']['section4hrSignatureDate'] ? formatDateToDB($sectionsdata['section4']['data']['section4hrSignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?> </span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">

            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Section 5: Salary Recommendation </strong>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Employees Current Pay Rate: </strong>
                            <?php echo $sectionsdata['section5']['data']['section5currentRate'] ? $sectionsdata['section5']['data']['section5currentRate'] : ''; ?>

                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Recommended Pay Increase: </strong>
                            <?php echo $sectionsdata['section5']['data']['section5recommendedIncrease'] ? $sectionsdata['section5']['data']['section5recommendedIncrease'] : ''; ?>

                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Approved Amount:</strong>
                            <?php echo $sectionsdata['section5']['data']['section5approvedAmount'] ? $sectionsdata['section5']['data']['section5approvedAmount'] : ''; ?>

                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Approved By:</strong>
                            <img style="max-height: 75px; vertical-align: middle;" alt="" class="authorized_signature_img_1" src="<?php echo $sectionsdata['section5']['data']['section5approvedBySignature'] ? $sectionsdata['section5']['data']['section5approvedBySignature'] : ''; ?>">

                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Approved Date:</strong>
                            <?php echo $sectionsdata['section5']['data']['section5approvedBySignatureDate'] ? formatDateToDB($sectionsdata['section5']['data']['section5approvedBySignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?>

                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Effective Date of Increase:</strong>
                            <?php echo $sectionsdata['section5']['data']['section5IncreaseEffectiveDate'] ? formatDateToDB($sectionsdata['section5']['data']['section5IncreaseEffectiveDate'], DB_DATE, SITE_DATE) : ''; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script type="text/javascript">
        let printDownload = '<?php echo  $printDownload ?>';
        $(window).on("load", function() {
            setTimeout(function() {
                if (printDownload == 'print') {
                    window.print();
                }

            }, 1000);
        });

        if (printDownload == 'download') {
            download_document();
        }

        function download_document() {
            var draw = kendo.drawing;
            draw.drawDOM($("#jsFormDownload"), {
                    avoidLinks: false,
                    paperSize: "A4",
                    multiPage: true,
                    margin: {
                        bottom: "2cm"
                    },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {

                    $('#myiframe').attr("src", data);
                    kendo.saveAs({
                        dataURI: data,
                        fileName: '<?php echo  $documentName ?>' + '.pdf'
                    });
                    window.close();
                });
        }
    </script>
</body>
</html>