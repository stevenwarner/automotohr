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

<?php
$formData = json_decode(
    unserialize($document["form_input_data"]),
    true
);
?>

<body cz-shortcut-listen="true">
    <main role="main" class="container">
        <section class="sheet padding-10mm" id="download_generated_document">
            <div class="main-content">
                <div class="dashboard-wrp">
                    <div class="container-fluid">
                        <div class="row" id="jsContentArea" style="word-break: break-all;">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <p>
                                    <strong>Status and Payroll Change</strong>
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
                                        <?= $formData["employee_name"]; ?>
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Effective Date:
                                    </strong>
                                    <span>
                                        <?= formatDateToDB(
                                            $formData["last_work_date"],
                                            "m/d/Y",
                                            DATE
                                        ); ?>
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Department:
                                    </strong>
                                    <span>
                                        <?= $formData["department"]; ?>
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Supervisor:
                                    </strong>
                                    <span>
                                        <?= $formData["supervisor"]; ?>
                                    </span>
                                    <br />
                                    <br />
                                </p>


                                <p>
                                    <strong>
                                        Indicate All Changes:
                                    </strong>
                                </p>
                                <br />

                                <!--  -->
                                <p>
                                    <strong>
                                        Rate:
                                    </strong>
                                    <span>
                                        <input type="checkbox" <?= $formData["fillable_rate"] === "yes" ? "checked" : ""; ?> disabled />
                                    </span>
                                    <br />
                                    <span>
                                        From: $<?= $formData["fillable_from_rate"]; ?> - To: $<?= $formData["fillable_to_rate"]; ?>
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Job:
                                    </strong>
                                    <span>
                                        <input type="checkbox" <?= $formData["fillable_job"] === "yes" ? "checked" : ""; ?> disabled />
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Dept:
                                    </strong>
                                    <span>
                                        <input type="checkbox" <?= $formData["fillable_department"] === "yes" ? "checked" : ""; ?> disabled />
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Location:
                                    </strong>
                                    <span>
                                        <input type="checkbox" <?= $formData["fillable_location"] === "yes" ? "checked" : ""; ?> disabled />
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Shift:
                                    </strong>
                                    <span>
                                        <input type="checkbox" <?= $formData["fillable_shift"] === "yes" ? "checked" : ""; ?> disabled />
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Other:
                                    </strong>
                                    <span>
                                        <input type="checkbox" <?= $formData["fillable_other"] === "yes" ? "checked" : ""; ?> disabled />
                                    </span>
                                    <br />
                                </p>


                                <p>
                                    <strong>
                                        Indicate All Reasons for Changes below:
                                    </strong>
                                </p>
                                <br />

                                <p>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Seniority increase", $formData["fillable_all_reasons"]) ? "checked" : ""; ?> value="Seniority increase" />
                                            Seniority increase
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Retirement", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Retirement" />
                                            Retirement
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Layoff", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Layoff" />
                                            Layoff
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Contract change", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Contract change" />
                                            Contract change
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Resignation", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Resignation" />
                                            Resignation
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Discharge", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Discharge" />
                                            Discharge
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Re-evaluation", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Re-evaluation" />
                                            Re-evaluation
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Demotion", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Demotion" />
                                            Demotion
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Leave of absence", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Leave of absence" />
                                            Leave of absence
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Transfer", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Transfer" />
                                            Transfer
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Promotion", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Promotion" />
                                            Promotion
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Merit Increase", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Merit Increase" />
                                            Merit Increase
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Probation period end", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Probation period end" />
                                            Probation period end
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Re-hired", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Re-hired" />
                                            Re-hired
                                        </td>
                                        <td>
                                            <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" <?= in_array("Hired", $formData["fillable_all_reasons"]) ? "checked" : ""; ?>  value="Hired" />
                                            Hired
                                        </td>
                                    </tr>

                                </table>
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