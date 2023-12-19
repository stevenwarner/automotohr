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
                            <strong style="font-size: 20px;">Notice of Termination of Employment</strong><br><br>
                            <strong style="font-size: 10px;"> Use this form to officially notify the Company that an employee who reports to you is
                                now Terminated. This will insure that appropriate action is taken and that the employee receives their
                                final check.</strong><br>
                            <strong style="font-size: 10px;">After you have selected the employee that you are terminating, take care to record the details of this
                                termination as accurately as possible.</strong><br>
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
                        <td class="" width="100%" style="border-top:0px;">
                            <strong style="font-size: 16px;">General Information</strong><br>
                        </td>
                    </tr>
                    <tr>
                        <td class="" width="100%" style="border-top:0px;"><br>
                            <strong style="font-size: 14px;">Employee name: </strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_0'] ? $formInputData['short_textbox_0'] : '' ?>" name="name" readonly style="width: 70%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Title: </strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_1'] ? $formInputData['short_textbox_1'] : '' ?>" name="job_title" readonly style="width: 79%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Supervisor: </strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_2'] ? $formInputData['short_textbox_2'] : '' ?>" name="supervisor" readonly style="width: 74%;" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Last day of work: </strong>
                            <input class="invoice-fields-line" type="text" value="<?= $formInputData['short_textbox_3'] ? $formInputData['short_textbox_3'] : '' ?>" name="last_day_of_work" readonly style="width: 74%;" /><br>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;"><br><br>
                            <strong style="font-size: 14px;">Is the termination Voluntary?</strong><br><br>
                            <div class="card-fields-row">
                                <strong style="font-size: 14px;" name="termination_voluntary">?( <?= $formInputData['selectDD0'] ? 'X' : '' ?>  ) Yes</strong><br>
                                <strong style="font-size: 14px;" name="termination_voluntary">?( <?= $formInputData['selectDD0'] ? '' : 'X' ?>  ) No</strong>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;"><br>
                            <strong style="font-size: 14px;">Summarize the notifications or warnings that were given to the employee prior to termination.
                                Include dates of each warning, whether the warning was documented, and where all
                                documentation is kept</strong><br>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;"><br>
                            <strong style="font-size: 14px;">All Dealership property has been returned? </strong><br><br>
                            <div class="card-fields-row">
                                <strong style="font-size: 14px;" name="dealership_property_returned">?( <?= $formInputData['selectDD1'] ? 'X' : '' ?> ) Yes</strong><br>
                                <strong style="font-size: 14px;" name="dealership_property_returned">?( <?= $formInputData['selectDD1'] ? 'X' : '' ?> ) No</strong>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Would you consider re-employing this person in your department? </strong><br><br>
                        <div class="card-fields-row">
                                <strong style="font-size: 14px;" name="re_employing">?( <?= $formInputData['selectDD2'] ? 'X' : '' ?>  ) Yes</strong><br>
                                <strong style="font-size: 14px;" name="re_employing">?( <?= $formInputData['selectDD2'] ? '' : 'X' ?> ) No</strong>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
            
    </div>
 