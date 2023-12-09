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
                            <strong style="font-size: 20px;">Payroll/Status Change Form</strong><br><br>
                            <strong style="font-size: 10px;"> Use this form to change an employee’s status and/or payroll information.</strong><br><br>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">

            <table class="table table-border-collapse">
                <tbody>

                    <tr>
                        <td class="" width="50%" style="border-top:0px;">
                            <h4><strong>General Information</strong></h4>
                        </td>
                    </tr>

                    <tr>
                        <td class="" width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Employee name:</strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_0'] ? $formInputData['short_textbox_0'] : '' ?>" name="name" readonly style="width: 70%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Effective Date:</strong>
                            <input class="invoice-fields-line date_picker" type="text" value="<?= $formInputData['short_textbox_1'] ? $formInputData['short_textbox_1'] : '' ?>" name="effective_date" readonly style="width: 72%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Department:</strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_2'] ? $formInputData['short_textbox_2'] : '' ?>" name="Department" readonly style="width: 73%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Supervisor:</strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_3'] ? $formInputData['short_textbox_3'] : '' ?>" name="supervisor" readonly style="width: 74%;" />
                        </td>
                    </tr>

                    <br>
                    <tr>
                        <td width="50%" style="border-top:0px;"><br>
                            <h4><strong>Indicate All Changes</strong></h4>
                        </td>
                    </tr>

                    <tr>
                        <td class="" width="50%" style="border-top:0px;">
                            <table class="table" style="margin-bottom:0px; padding-left: 0px;">
                                <tbody>
                                    <tr>
                                        <td width="30%" style="border-top:0px;">
                                            <strong style="font-size: 14px;">Rate From: </strong>
                                            <input class="invoice-fields-line" type="text" value="$<?= $formInputData['short_textbox_4'] ? $formInputData['short_textbox_4'] : '' ?> " name="supervisor" />
                                        </td>
                                        <td width="30%" style="border-top:0px;">
                                            <strong style="font-size: 14px;">Rate To: </strong>
                                            <input class="invoice-fields-line" type="text" value="$<?= $formInputData['short_textbox_5'] ? $formInputData['short_textbox_5'] : '' ?> " name="supervisor" />
                                        </td>
                                        <td style="border-top:0px;">

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Job:</strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_6'] ? $formInputData['short_textbox_6'] : '' ?>" name="job" readonly style="width: 79%;" />
                        </td>

                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Department:</strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_7'] ? $formInputData['short_textbox_7'] : '' ?>" name="Department_shift" readonly style="width: 73%;" />
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Location:</strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_8'] ? $formInputData['short_textbox_8'] : '' ?>" name="location" readonly style="width: 76%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Shift:</strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_9'] ? $formInputData['short_textbox_9'] : '' ?>" name="shift" readonly style="width: 79%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Other:</strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_10'] ? $formInputData['short_textbox_10'] : '' ?>" name="other" readonly style="width: 78%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <h4><strong>Indicate All Reasons for Changes below</strong></h4>
                        </td>
                    </tr>


                    <tr>
                        <td width="100%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_0']=='yes' ? 'X' : '' ?> )
                                            &nbsp; <strong> Seniority increase</strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">

                                            ( <?= $formInputData['checkbox_1']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Retirement</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_2']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Layoff</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_3']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Contract change</strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_4']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Resignation</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_5']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Discharge</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_6']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Re-evaluation</strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_7']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Demotion</strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_8']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Leave of absence</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_9']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Transfer</strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_10']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Promotion</strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_11']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Merit Increase</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_12']=='yes' ? 'X' : '' ?>  ) &nbsp; <strong> Probation period end</strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_13']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Re-hired</strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            ( <?= $formInputData['checkbox_14']=='yes' ? 'X' : '' ?> ) &nbsp; <strong> Hired</strong>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </td>
                    </tr>

                </tbody>
            </table>
    </div>
