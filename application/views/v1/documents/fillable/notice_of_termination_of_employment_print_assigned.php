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
                                    <strong>Notice Of Termination of Employment</strong>
                                </p>
                                <br>
                                <p>
                                    <strong>Instructions</strong>
                                    Use this form to officially notify the Company that an employee who reports to you is
                                    now Terminated. This will insure that appropriate action is taken and that the employee receives their
                                    final check.
                                </p>
                                <p>
                                    After you have selected the employee that you are terminating, take care to record the details of this
                                    termination as accurately as possible.
                                </p>
                                <br />
                                <p>
                                    <strong>
                                        General Information:
                                    </strong>
                                </p>
                                <br>
                                <!--  -->
                                <p>
                                    <strong>
                                        Employee name:
                                    </strong>
                                    <span>
                                        --------------- ---------------
                                    </span>
                                    <br />
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Title:
                                    </strong>
                                    <span>
                                        ---------------
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
                                </p>

                                <!--  -->
                                <p>
                                    <strong>
                                        Last Day of Work:
                                    </strong>
                                    <span>
                                        --/--/----
                                    </span>
                                    <br />
                                </p>

                                <br>
                                <!--  -->
                                <p>
                                    <strong>
                                        Is the termination Voluntary?
                                    </strong>
                                <p>
                                    <input type="radio" name="v" /> Yes
                                    <br />
                                    <input type="radio" name="v" /> No
                                </p>
                                <br />
                                </p>

                                <br>

                                <!--  -->
                                <p>
                                    <strong>
                                        Summarize the notifications or warnings that were given to the employee prior to termination.
                                        Include dates of each warning, whether the warning was documented, and where all
                                        documentation is kept
                                    </strong>
                                </p>

                                <br>
                                <br>
                                <!--  -->
                                <p>
                                    <strong>
                                        All Dealership property has been returned?
                                    </strong>
                                <p>
                                    ----------------------------------------------------------------
                                    ----------------------------------------------------------------
                                </p>
                                <br />
                                </p>


                                <p>
                                    <strong>
                                        Would you consider re-employing this person in your department?
                                    </strong>
                                <p>
                                    ----------------------------------------------------------------
                                    ----------------------------------------------------------------
                                </p>
                                <br />
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