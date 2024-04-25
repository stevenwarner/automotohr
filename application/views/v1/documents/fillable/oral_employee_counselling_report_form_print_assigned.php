<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
    <link rel="stylesheet" type="https://printjs-4de6.kxcdn.com/print.min.css" href="print.css">
    <title>Generated Document</title>
    <style>
        .center-col {
            float: left;
            width: 100%;
            text-align: center;
            margin-top: 14px;
        }

        .center-col h2,
        .center-col p {
            margin: 0 0 5px 0;
        }

        .sheet-header {
            float: left;
            width: 100%;
            padding: 0 0 2px 0;
            margin: 0 0 5px 0;
            border-bottom: 5px solid #000;
        }

        .sheet.padding-10mm {
            padding: 10mm
        }

        .header-logo {
            float: left;
            width: 100%;
        }

        input[type='checkbox'].user_checkbox {
            margin-top: -30px;
        }

        input[type='checkbox'].user_checkbox {
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeSpeed;
            width: 25px;
            height: 25px;
            margin: 0;
            margin-right: 10px !important;
            display: block;
            float: left;
            position: relative;
            cursor: pointer;
        }

        input[type='checkbox'].user_checkbox:after {
            content: "";
            vertical-align: middle;
            text-align: center;
            line-height: 25px;
            position: absolute;
            cursor: pointer;
            height: 25px;
            width: 25px;
            left: 0;
            top: 0;
            font-size: 14px;
            background: #999999;
        }

        input[type='checkbox'].user_checkbox:hover:after,
        input[type='checkbox'].user_checkbox:checked:hover:after {
            background: #999999;
            content: '\2714';
            color: #fff;
        }

        input[type='checkbox'].user_checkbox:checked:after {
            background: #999999;
            content: '\2714';
            color: #fff;
        }

        #download_generated_document ul,
        #download_generated_document ol {
            padding-left: 20px;
        }
    </style>
</head>

<body cz-shortcut-listen="true">
    <main role="main" class="container">
        <section class="sheet padding-10mm" id="download_generated_document">
            <div class="main-content">
                <div class="dashboard-wrp">
                    <div class="container-fluid">
                        <div class="row" id="jsContentArea" style="word-break: break-all;">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <p>
                                    <strong>Oral Employee Counselling Report Form</strong>
                                </p>
                                <br>
                                <p>
                                    <strong>Oral Employee Counselling Report Form</strong>
                                </p>
                                <br>
                                <p>
                                    <strong>Instructions</strong>
                                    Use this form to document the need for improvement in job performance or to document
                                    disciplinary action for misconduct. This form is intended to be used as part of a progressive
                                    disciplinary process to help improve an employee's performance or behavior in a formal and
                                    systematic manner. Before completing this form, review the Progressive Discipline Policy and any
                                    relevant supervisor guidances.
                                </p>
                                <p>
                                    If the employee is being terminated, review the policy on Immediate termination and related
                                    guidelines, and use the Termination Without Notice Form to process.
                                </p>
                                <br />
                                <!--  -->
                                <p>
                                    <strong>
                                        Employee name:
                                    </strong>
                                    <span>
                                        ---------------
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Department:
                                    </strong>
                                    <span>
                                        ---------------
                                    </span>
                                    <br />
                                </p>
                                <!--  -->
                                <p>
                                    <strong>
                                        Date of occurrence:
                                    </strong>
                                    <span>
                                        --/--/----
                                    </span>
                                    <br />
                                </p>
                                <!--  -->
                                <p>
                                    <strong>
                                        Supervisor:
                                    </strong>
                                    <span>
                                        ---------------
                                    </span>
                                    <br />
                                    <br />
                                </p>

                                <p>
                                    The following counseling has taken place (check all boxes that apply) and provide details in the
                                    summary section below:
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Absence", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Absence" />
                                            Absence
                                        </td>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Harassment", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Harassment" />
                                            Harassment
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Tardiness", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Tardiness" />
                                            Tardiness
                                        </td>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Dishonesty", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Dishonesty" />
                                            Dishonesty
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Violation of company policies and/or procedures", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Violation of company policies and/or procedures" />
                                            Violation of company policies and/or procedures
                                        </td>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Violation of safety rules", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Violation of safety rules" />
                                            Violation of safety rules
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Horseplay", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Horseplay" />
                                            Horseplay
                                        </td>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Leaving work without authorization", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Leaving work without authorization" />
                                            Leaving work without authorization
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Smoking in unauthorized areas", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Smoking in unauthorized areas" />
                                            Smoking in unauthorized areas
                                        </td>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Unsatisfactory job performance", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Unsatisfactory job performance" />
                                            Unsatisfactory job performance
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Failure to follow instructions", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Failure to follow instructions" />
                                            Failure to follow instructions
                                        </td>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Insubordination", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Insubordination" />
                                            Insubordination
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Unauthorized use of equipment, materials", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Unauthorized use of equipment, materials" />
                                            Unauthorized use of equipment, materials
                                        </td>
                                        <td>
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Falsification of records", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Falsification of records" />
                                            Falsification of records
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Other", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Other" />
                                            Other:
                                            <textarea rows="5" class="form-control input-grey gray-background  <?= in_array("Other", $formData["counselling_form_fields"]) ? "" : "hidden"; ?> " name="counselling_form_fields_textarea"><?= in_array("Other", $formData["counselling_form_fields"]) ? $formData["counselling_form_fields_textarea"] : ""; ?></textarea>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <br>
                                </p>

                                <p>
                                    <strong>
                                        Summary of violation:
                                    </strong>
                                <p>
                                    ----------------------------------------------------------------
                                    ----------------------------------------------------------------
                                </p>
                                <br>
                                </p>

                                <p>
                                    <strong>
                                        Summary of corrective plan:
                                    </strong>
                                <p>
                                    ----------------------------------------------------------------
                                    ----------------------------------------------------------------
                                </p>
                                <br>
                                </p>
                                <p>
                                    <strong>
                                        Follow up dates:
                                    </strong>
                                <p>
                                    ----------------------------------------------------------------
                                    ----------------------------------------------------------------
                                </p>
                                <br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

    <script>
        $(document).ready(function() {
            var perform_action = '<?php echo $perform_action; ?>';

            if (perform_action == 'print') {
                window.print();
            } else {
                //
                var draw = kendo.drawing;
                draw.drawDOM($("#download_generated_document"), {
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
                        var pdf = data;

                        $('#myiframe').attr("src", data);

                        kendo.saveAs({
                            dataURI: pdf,
                            fileName: '<?php echo $file_name . ".pdf"; ?>',
                        });
                        //
                        setTimeout(() => {
                            if (document_type == "yes") {
                                var document_path = '<?php echo $document_path; ?>';
                                window.open(document_path, '_blank');
                                setTimeout(() => {
                                    window.close();
                                }, 5000)
                            } else {
                                window.close();
                            }

                        }, 5000);
                    });
            }
        });


        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>

</html>