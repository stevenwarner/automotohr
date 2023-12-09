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
        font-size: 14px;
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

    .display-block {
        display: block;
        height: 20px;
    }

    .signature {
        font-size: 26px;
        font-family: 'Conv_SCRIPTIN' !important;
    }


    .invoice-fields-line {
        border-width: 2px;
        padding: 0;

        background-color: transparent;
        border: none;
        border-bottom: 1px solid #000;
        border-radius: 0;
        height: auto;
    }
</style>

<div class="sheet padding-10mm" id="pdf" style="margin: 0 auto;">
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
                                        <?= $formInputData['short_textbox_0'] ? $formInputData['short_textbox_0'] : '' ?>

                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_1'] ? $formInputData['short_textbox_1'] : '' ?>
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
                                        <?= $formInputData['short_textbox_2'] ? $formInputData['short_textbox_2'] : '' ?>

                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_3'] ? $formInputData['short_textbox_3'] : '' ?>
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
                                        <?= $formInputData['short_textbox_4'] ? $formInputData['short_textbox_4'] : '' ?>

                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_5'] ? $formInputData['short_textbox_5'] : '' ?>
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
                                        <?= $formInputData['short_textbox_6'] ? $formInputData['short_textbox_6'] : '' ?>

                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_7'] ? $formInputData['short_textbox_7'] : '' ?>
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
                                        <?= $formInputData['checkbox_0'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_1'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_2'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong> <br><?= $formInputData['long_textbox_0'] ? $formInputData['long_textbox_0'] : '' ?>
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
                                        <?= $formInputData['checkbox_3'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_4'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_5'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong> <br>
                                        <?= $formInputData['long_textbox_1'] ? $formInputData['long_textbox_1'] : '' ?>
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
                                        <?= $formInputData['checkbox_6'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_7'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_8'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong>
                                        <br>
                                        <?= $formInputData['long_textbox_2'] ? $formInputData['long_textbox_2'] : '' ?>
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
                                        <?= $formInputData['checkbox_7'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_8'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_9'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong>
                                        <br>
                                        <?= $formInputData['long_textbox_3'] ? $formInputData['long_textbox_3'] : '' ?>
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
                                        <?= $formInputData['checkbox_10'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_11'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_13'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong>
                                        <br>
                                        <?= $formInputData['long_textbox_4'] ? $formInputData['long_textbox_4'] : '' ?>
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
                                        <?= $formInputData['checkbox_14'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_15'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_16'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong>
                                        <br>
                                        <?= $formInputData['long_textbox_5'] ? $formInputData['long_textbox_5'] : '' ?>
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
                                        <?= $formInputData['checkbox_15'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_16'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_18'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong>
                                        <br>
                                        <?= $formInputData['long_textbox_6'] ? $formInputData['long_textbox_6'] : '' ?>
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
                                        <?= $formInputData['checkbox_19'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_20'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_21'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong>
                                        <br>
                                        <?= $formInputData['long_textbox_7'] ? $formInputData['long_textbox_7'] : '' ?>
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
                                        <?= $formInputData['checkbox_22'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_23'] == 'yes' ? 'Yes' : '' ?>
                                    </td>

                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['checkbox_24'] == 'yes' ? 'Yes' : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                        <strong>Comment:</strong>
                                        <br>
                                        <?= $formInputData['long_textbox_8'] ? $formInputData['long_textbox_8'] : '' ?>
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
                                        <?= $formInputData['long_textbox_9'] ? $formInputData['long_textbox_9'] : '' ?>
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
                                        <?= $formInputData['short_textbox_26'] ? $formInputData['short_textbox_26'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_27'] ? $formInputData['short_textbox_27'] : '' ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        2
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_28'] ? $formInputData['short_textbox_28'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_29'] ? $formInputData['short_textbox_29'] : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        3
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_30'] ? $formInputData['short_textbox_30'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_31'] ? $formInputData['short_textbox_31'] : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;"> 4
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_32'] ? $formInputData['short_textbox_32'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_33'] ? $formInputData['short_textbox_33'] : '' ?>
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
                                        <?= $formInputData['short_textbox_34'] ? $formInputData['short_textbox_34'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_35'] ? $formInputData['short_textbox_35'] : '' ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        2
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_36'] ? $formInputData['short_textbox_36'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_37'] ? $formInputData['short_textbox_37'] : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        3
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_38'] ? $formInputData['short_textbox_38'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_39'] ? $formInputData['short_textbox_39'] : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;"> 4
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_40'] ? $formInputData['short_textbox_40'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_41'] ? $formInputData['short_textbox_41'] : '' ?>
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
                                        <?= $formInputData['short_textbox_42'] ? $formInputData['short_textbox_42'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_43'] ? $formInputData['short_textbox_43'] : '' ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        2
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_44'] ? $formInputData['short_textbox_44'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_45'] ? $formInputData['short_textbox_45'] : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        3
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_46'] ? $formInputData['short_textbox_46'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_47'] ? $formInputData['short_textbox_47'] : '' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;"> 4
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_48'] ? $formInputData['short_textbox_48'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_49'] ? $formInputData['short_textbox_49'] : '' ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;" colspan="3">
                                        <strong> Have you and your manager reviewed your job description for this review period? </strong><br>
                                        <strong style="font-size: 14px;" name="termination_voluntary"> <?= $formInputData['selectDD0'] ? 'Yes' : '' ?> </strong>
                                        <strong style="font-size: 14px;" name="termination_voluntary"> <?= $formInputData['selectDD0'] ? 'No' : '' ?> </strong>
                                    </td>
                                </tr>

                                <tr>

                                    <td style="border: 1px solid; font-size: 14px;" colspan="3">
                                        <strong> 2.Do you have access to equipment and resources necessary to perform your job function?
                                            (If No, please list the equipment you deem necessary subject to Managers approval and budgeting) </strong>

                                        <strong style="font-size: 14px;" name="termination_voluntary"><?= $formInputData['selectDD1'] ? 'Yes' : '' ?></strong><br>
                                        <strong style="font-size: 14px;" name="termination_voluntary"><?= $formInputData['selectDD1'] ? 'No' : '' ?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid; font-size: 14px;" colspan="3">
                                        <strong> 3.Is there any additional support or training you feel would be helpful for DeFOUW Automotive to provide for you to help you succeed in your current role?</strong>
                                        <strong style="font-size: 14px;" name="termination_voluntary"><?= $formInputData['selectDD2'] ? 'Yes' : '' ?> </strong><br>
                                        <strong style="font-size: 14px;" name="termination_voluntary"><?= $formInputData['selectDD2'] ? 'No' : '' ?> </strong>
                                </tr>

                                <tr>

                                    <td style="border: 1px solid; font-size: 14px;" colspan="3">
                                        <strong>Employee Additional Comments:</strong> <br>
                                        <?= $formInputData['long_textbox_10'] ? $formInputData['long_textbox_10'] : '' ?>
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
                            <?= $formInputData['long_textbox_12'] ? $formInputData['long_textbox_11'] : '' ?>

                        </strong>
                    </td>
                </tr>

                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">
                            Additional Comments, Feedback - Employee Comments: <br>
                            <?= $formInputData['long_textbox_13'] ? $formInputData['long_textbox_12'] : '' ?>

                        </strong>
                    </td>
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
                                        <?= $formInputData['short_textbox_50'] ? $formInputData['short_textbox_50'] : '' ?>
                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_51'] ? $formInputData['short_textbox_51'] : '' ?>
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
                                        <?= $formInputData['short_textbox_52'] ? $formInputData['short_textbox_52'] : '' ?>

                                    </td>
                                    <td style="border: 1px solid; font-size: 14px;">
                                        <?= $formInputData['short_textbox_53'] ? $formInputData['short_textbox_53'] : '' ?>
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
                        <?= $formInputData['short_textbox_54'] ? $formInputData['short_textbox_54'] : '' ?>

                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Recommended Pay Increase: </strong>
                        <?= $formInputData['short_textbox_55'] ? $formInputData['short_textbox_55'] : '' ?>

                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Approved Amount:</strong>
                        <?= $formInputData['short_textbox_56'] ? $formInputData['short_textbox_56'] : '' ?>

                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Approved By:</strong>
                        <?= $formInputData['short_textbox_57'] ? $formInputData['short_textbox_57'] : '' ?>

                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Approved Date:</strong>
                        <?= $formInputData['short_textbox_58'] ? $formInputData['short_textbox_58'] : '' ?>

                    </td>
                </tr>

                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Effective Date of Increase:</strong>
                        <?= $formInputData['short_textbox_59'] ? $formInputData['short_textbox_59'] : '' ?>

                    </td>
                </tr>

            </tbody>
        </table>
    </section>





</div>