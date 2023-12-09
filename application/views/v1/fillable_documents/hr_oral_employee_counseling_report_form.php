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
<div class="sheet padding-10mm" id="pdf" style="width: 800px; margin: 0 auto;">
    <section class="pdf-cover-page">
        <table class="table-border-collapse">
            <tbody>
                <tr>
                    <td>
                        <strong style="font-size: 20px;">Oral Employee Counseling Report Form</strong><br><br>
                        <strong style="font-size: 10px;"> Instructions: Use this form to document the need for improvement in job performance or to document disciplinary action for misconduct. This form is intended to be used as part of a progressive disciplinary process to help improve an employee’s performance or behavior in a formal and systematic manner. Before completing this form, review the Progressive Discipline Policy and any relevant supervisor guidances.</strong><br><br>
                        <strong style="font-size: 10px;">If the employee is being terminated, review the policy on Immediate termination and related guidelines, and use the Termination Without Notice Form to process.</strong><br>
                    </td>

                </tr>
            </tbody>
        </table>
    </section>

    <br>

    <section class="pdf-cover-page">

        <table class="table table-border-collapse">
            <tbody>

                <tr>
                    <td class="" width="100%" style="border-top:0px;"><br>
                        <strong style="font-size: 14px;">Employee name: </strong>
                        <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_0'] ? $formInputData['short_textbox_0'] : '' ?>" name="name" readonly style="width: 70%;" />
                    </td>
                </tr>

                <tr>
                    <td class="" width="100%" style="border-top:0px;"><br>
                        <strong style="font-size: 14px;">Department: </strong>
                        <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_1'] ? $formInputData['short_textbox_1'] : '' ?>" name="department" readonly style="width: 73%;" />
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Date of occurrence: </strong>
                        <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_2'] ? $formInputData['short_textbox_2'] : '' ?>" name="date_of_occurrence" readonly style="width: 68%;" />
                    </td>
                </tr>

                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Supervisor: </strong>
                        <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_3'] ? $formInputData['short_textbox_3'] : '' ?>" name="supervisor" readonly style="width: 74%;" />
                    </td>
                </tr>

                <tr>
                    <td width="50%" style="border-top:0px;"><br><br>
                        <strong style="font-size: 14px;">The following counseling has taken place (check all boxes that apply) and provide details in the summary section below: Indicate: (x)
                        </strong>
                    </td>
                </tr>

                <tr>
                    <td width="50%" style="border-top:0px;">
                        <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                            <tbody>

                                <tr>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_0'] == 'yes' ? 'X' : '' ?> )
                                        &nbsp; <strong> Absence</strong>

                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_1'] == 'yes' ? 'X' : '' ?> )
                                        &nbsp; <strong> Harassment</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_2'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Tardiness</strong>

                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_3'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Dishonesty</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_4'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Violation of company policies and/or
                                            procedures</strong>
                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_5'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Violation of safety rules</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_6'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Horseplay</strong>
                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_7'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Leaving work without authorization</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_8'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Smoking in unauthorized areas</strong>
                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_9'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Unsatisfactory job performance</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_10'] == 'yes' ? 'X' : '' ?> ) &nbsp; <label> Failure to follow instructions</label>
                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_11'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Insubordination</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_12'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Unauthorized use of equipment,materials</strong>
                                    </td>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_13'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Falsification of records</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="50%" style="border: 1px solid; font-size: 14px;">
                                        ( <?= $formInputData['checkbox_14'] == 'yes' ? 'X' : '' ?> ) &nbsp; <strong> Other</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </tr>

                <tr>
                    <td width="100%" style="border-top:0px;"><br>
                        <strong style="font-size: 14px;">Summary of violation:</strong><br><br>
                        <?= $formInputData['long_textbox_0'] ? $formInputData['long_textbox_0'] : '' ?>
                    </td>
                </tr>

                <tr>
                    <td width="100%" style="border-top:0px;"><br> <br>
                        <strong style="font-size: 14px;">Summary of corrective plan:</strong><br><br>
                        <?= $formInputData['long_textbox_1'] ? $formInputData['long_textbox_1'] : '' ?>
                    </td>
                </tr>

                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Follow up dates: </strong>
                        <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_4'] ? $formInputData['short_textbox_4'] : '' ?>" name="follow_up_dates" readonly style="width: 30%;" />
                    </td>
                </tr>

            </tbody>
        </table>
</div>