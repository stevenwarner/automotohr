<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <title>Performance Evaluation Document</title>
    <link rel="icon" type="image/png" sizes="32x32" href="http://automotohr.local/public/v1/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="http://automotohr.local/public/v1/images/favicon_io/favicon-16x16.png">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css">
    <style>
        body {
            line-height: 108%;
            font-family: Calibri;
            font-size: 11pt
        }

        h1,
        h2,
        p {
            margin: 0pt 0pt 8pt
        }

        li,
        table {
            margin-top: 0pt;
            margin-bottom: 8pt
        }

        h1 {
            margin-left: 0.5pt;
            margin-bottom: 0pt;
            text-indent: -0.5pt;
            page-break-inside: avoid;
            page-break-after: avoid;
            line-height: 108%;
            font-family: Arial;
            font-size: 14pt;
            font-weight: bold;
            color: #c00000
        }

        h2 {
            margin-left: 0.5pt;
            margin-bottom: 0pt;
            text-indent: -0.5pt;
            page-break-inside: avoid;
            page-break-after: avoid;
            line-height: 108%;
            font-family: Arial;
            font-size: 12pt;
            font-weight: bold;
            color: #1f497d
        }

        .Footer {
            margin-bottom: 0pt;
            line-height: normal;
            font-family: Calibri;
            font-size: 11pt;
            color: #000000
        }

        .ListParagraph {
            margin-left: 36pt;
            margin-bottom: 0pt;
            line-height: normal;
            font-family: 'Times New Roman';
            font-size: 12pt;
            color: #000000
        }

        .NoSpacing {
            margin-bottom: 0pt;
            line-height: normal;
            font-family: Calibri;
            font-size: 11pt;
            color: #000000
        }

        span.FooterChar {
            font-family: Calibri;
            color: #000000
        }

        span.Heading1Char {
            font-family: Arial;
            font-size: 14pt;
            font-weight: bold;
            color: #c00000
        }

        span.Heading2Char {
            font-family: Arial;
            font-size: 12pt;
            font-weight: bold;
            color: #1f497d
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row" id="download_performance_document">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                    <h1 style="margin-left:0pt; text-indent:0pt">
                        <span style="color:#2f5496">EMPLOYEE PERFORMANCE EVALUATION </span>
                    </h1>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php if ($userType != "employee") { ?>
                        <p style="margin-bottom:0pt">
                            &#xa0; &#xa0;
                        </p>
                        <p style="margin-bottom:2.85pt; line-height:108%; font-size:12pt">
                            How to complete the Employee Performance Evaluation Process:
                        </p>
                        <h2 style="margin-left:36pt; text-indent:-18pt; line-height:normal">
                            <span style="font-family:Calibri; font-weight:normal; color:#ff0000"><span style="font-family:Symbol"></span></span><span style="width:12.48pt; font:7pt 'Times New Roman'; display:inline-block">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span style="font-family:Calibri; color:#000000">Section 1</span><span style="font-family:Calibri; font-weight:normal; color:#000000">: This section will be completed by the immediate Manager of the employee. The Manager will complete this section ahead of the scheduled performance evaluation meeting with the employee.</span><span style="font-family:Calibri; font-weight:normal; color:#000000">&#xa0; </span><em><span style="font-family:Calibri; font-weight:normal; color:#ff0000">The manager must send their completed portion of the performance evaluation to human resources for review PRIOR to the meeting with the employee. Once HR has reviewed the Performance Evaluation and has sent this back to you, you can then meet with the employee.</span></em><em><span style="font-family:Calibri; color:#ff0000"> </span></em>
                        </h2>
                        <h2 style="margin-left:36pt; text-indent:-18pt; line-height:normal">
                            <span style="font-family:Calibri; font-weight:normal; color:#000000"><span style="font-family:Symbol"></span></span><span style="width:12.48pt; font:7pt 'Times New Roman'; display:inline-block">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span style="font-family:Calibri; color:#000000">Section 2</span><span style="font-family:Calibri; font-weight:normal; color:#000000">: The Manager will send Section 2 to the employee ahead of the scheduled performance evaluation meeting. The employee will complete this section on their own and hold onto this until their scheduled performance evaluation meeting with their manager.</span>
                        </h2>
                        <p class="ListParagraph" style="text-indent:-18pt">
                            <span style="font-family:Calibri"><span style="font-family:Symbol"></span></span><span style="width:12.48pt; font:7pt 'Times New Roman'; display:inline-block">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><strong><span style="font-family:Calibri; ">Section 3:</span></strong><span style="font-family:Calibri"> The Manager will schedule the performance evaluation meeting with the employee. The Manager and the employee will complete section 3 together by providing any additional commitments, goals, and feedback. </span>
                        </p>
                        <p class="ListParagraph" style="text-indent:-18pt">
                            <span style="font-family:Calibri"><span style="font-family:Symbol"></span></span><span style="width:12.48pt; font:7pt 'Times New Roman'; display:inline-block">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><strong><span style="font-family:Calibri; ">Section 4</span></strong><span style="font-family:Calibri">: Once the Manager and the employee have met and completed the performance evaluation process, they will both sign the completed form. </span>
                        </p>
                        <p class="ListParagraph" style="text-indent:-18pt">
                            <span style="font-family:Calibri"><span style="font-family:Symbol"></span></span><span style="width:12.48pt; font:7pt 'Times New Roman'; display:inline-block">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><strong><span style="font-family:Calibri; ">Section 5</span></strong><span style="font-family:Calibri">: The Manager may make a recommendation for salary changes. The form will then be sent to Human Resources for final approval and a review of the salary recommendation will be sent to the Director. The performance evaluation then becomes part of the employee’s personnel file. </span>
                        </p>
                        <p class="NoSpacing" style="font-size:12pt">
                            &#xa0;
                        </p>
                        <p class="NoSpacing" style="font-size:12pt">
                            <strong>Tips:</strong> The employee should have the Manager’s undivided attention during the performance evaluation meeting. The Manager should articulate the employee’s strengths and, if there are any improvement opportunities, the Manager should propose suggestions on how the employee can improve. During the dialog with the employee, the Manager will review the employee’s feedback on the form they filled out and that of the Manager.
                        </p>
                        <p class="NoSpacing" style="font-size:12pt">
                            &#xa0;
                        </p>
                        <p class="NoSpacing" style="font-size:12pt">
                            This performance evaluation discussion is intended to be a constructive exchange relative to the individual's past performance, improvement opportunities, and future expectations. It offers a chance for the employee to improve in areas that are needed. The dialogue should be a two-way conversation between the employee and the Manager.
                        </p>
                        <p style="line-height:108%; font-size:12pt">
                            &#xa0;
                        </p>
                    <?php } ?>

                    <p>
                        <br style="page-break-before:always; clear:both" />
                    </p>

                    <?php if ($userType != "employee") { ?>

                        <!-- Section 1 Start -->
                        <?php
                        $section1 = [];
                        if ($sectionData['section_1_json']) {
                            $section1 = json_decode($sectionData['section_1_json'], true)['data'];
                        }
                        ?>
                        <h2 style="line-height:normal">
                            <span style="font-family:Calibri; color:#000000">Manager Section 1: Employee Year in Review Evaluation</span>
                        </h2>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:10.15pt">
                                <td style="width:241.6pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Employee Name</strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Job Title</strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:22pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section1['epe_employee_name'] ?? $defaultData['epe_employee_name']; ?>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section1['epe_job_title'] ?? $defaultData['epe_job_title']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:10.15pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Department </strong>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Manager</strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:20.15pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section1['epe_department'] ?? $defaultData['epe_department']; ?>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section1['epe_manager'] ?? $defaultData['epe_manager']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:10.1pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Hire Date with <?= $companyName ?></strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Start Date in Current Position</strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:17.05pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section1['epe_hire_date'] ?? $defaultData['epe_hire_date']; ?>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section1['epe_start_date'] ?? $defaultData['epe_start_date']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:10.15pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Review Period Start</strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Review Period End</strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:20.15pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section1['epe_review_start']; ?>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section1['epe_review_end']; ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p>
                            &#xa0;
                        </p>
                        <h2 style="line-height:normal">
                            <span style="font-family:Calibri; color:#000000">Rate the employee in each area below. Comments are required for each section. </span>
                        </h2>
                        <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong><u>POSITION KNOWLEDGE</u></strong>:&#xa0;&#xa0; To what level is this employee knowledgeable of the job duties of the position to include methods, procedures, standard practices, and techniques? This may have been acquired through formal training, education and/or experience.
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:64.4pt">
                                <td style="width:152.95pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="line-height:108%; font-size:12pt">
                                        <em>Knowledge is below the minimum requirements of the position. Improvement is mandatory.</em>
                                    </p>
                                </td>
                                <td style="width:156.25pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="line-height:108%; font-size:12pt">
                                        <em>Knowledge is sufficient to perform the requirements of the position.</em>
                                    </p>
                                </td>
                                <td style="width:156.15pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="line-height:108%; font-size:12pt">
                                        <em>Employee is exceptionally well informed and competent in all aspects of the position.</em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:20.65pt">
                                <td style="width:152.95pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="text-align:center; line-height:108%; font-size:12pt">
                                        <?php echo $section1['position_knowledgeable_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                    <p style="text-align:center; line-height:108%; font-size:12pt">
                                        <strong>&#xa0;</strong>
                                    </p>
                                </td>
                                <td style="width:156.25pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="text-align:center; line-height:108%; font-size:12pt">
                                        <?php echo $section1['position_knowledgeable_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:156.15pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="text-align:center; line-height:108%; font-size:12pt">
                                        <?php echo $section1['position_knowledgeable_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                            <strong>Comment</strong>:
                            <br />
                            <?= $section1['position_knowledgeable_comments']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                            <strong>How may the employee’s position knowledge be improved?</strong>
                            <br />
                            <?= $section1['position_improved']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            <strong><u>QUANTITY OF WORK</u></strong><strong>:</strong><strong>&#xa0; </strong>Evaluate the quantity of work produced.
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:46.7pt">
                                <td style="width:159.45pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                                        <em>Output is below that required of the position. Improvement is mandatory.</em>
                                    </p>
                                </td>
                                <td style="width:160.35pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                                        <em>Output meets that required of the position.</em>
                                    </p>
                                </td>
                                <td style="width:162.05pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                                        <em>Output consistently exceeds that required of the position.</em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:31.25pt">
                                <td style="width:159.45pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt">
                                        <?php echo $section1['position_improved_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                    <p style="margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt">
                                        <strong>&#xa0;</strong>
                                    </p>
                                </td>
                                <td style="width:160.35pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt">
                                        <?php echo $section1['position_improved_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:162.05pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt">
                                        <?php echo $section1['position_improved_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            <strong>Comment:</strong>
                            <br>
                            <?= $section1['position_improved_comment']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            <strong>How may the employee’s quantity of work be improved?</strong>
                            <br>
                            <?= $section1['quantity_improved']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="line-height:115%; font-size:12pt">
                            <strong>&#xa0;</strong><strong><u>QUALITY OF WORK</u></strong><strong>:</strong>&#xa0; Evaluate the quality of work produced in accordance with requirements for accuracy, completeness, and attention to detail.
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:49.4pt">
                                <td style="width:159.35pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                                        <em>Quality of work is frequently below position requirements. Improvement is mandatory.</em>
                                    </p>
                                </td>
                                <td style="width:162.85pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                                        <em>Quality of work meets position requirements.</em>
                                    </p>
                                </td>
                                <td style="width:162.85pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                                        <em>Quality of work consistently exceeds position requirements.</em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:33.05pt">
                                <td style="width:159.35pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt">
                                        <?php echo $section1['quantity_improved_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                    <p style="margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt">
                                        <strong>&#xa0;</strong>
                                    </p>
                                </td>
                                <td style="width:162.85pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt">
                                        <?php echo $section1['quantity_improved_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:162.85pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt">
                                        <?php echo $section1['quantity_improved_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            <strong>Comment:</strong>
                            <br>
                            <?= $section1['quantity_improved_comment']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:115%; font-size:12pt">
                            <strong>How may the employees’ quality of work be improved?</strong>
                            <br>
                            <?= $section1['quality_improved']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>&#xa0;</strong>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong><u>INTERPERSONAL RELATIONS</u></strong><strong>:</strong><strong>&#xa0; </strong>To what level does this individual demonstrate cooperative behavior and contribute to a supportive work environment?
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:42.25pt">
                                <td style="width:157.8pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee is frequently non-supportive.</em><em>&#xa0; </em><em>Improvement is mandatory.</em>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee adequately contributes to supportive environment.</em>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee consistently contributes to supportive work environment.</em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:28.15pt">
                                <td style="width:157.8pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['quality_improved_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['quality_improved_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['quality_improved_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>Comment:</strong>
                            <br>
                            <?= $section1['quality_improved_comment']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>How may the employee’s interpersonal relations be improved?</strong>
                            <br>
                            <?= $section1['relations_improved']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong><u>Mission</u></strong><strong>:</strong><strong>&#xa0; </strong>To what level does the employees work support the Mission of the organization; To what level does the employee make themselves available to respond to needs of others both internally and externally?
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:35.4pt">
                                <td style="width:163.8pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Level of mission focus is often below the required/acceptable standard. Improvement is mandatory.</em>
                                    </p>
                                </td>
                                <td style="width:158.45pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee adequately contributes to high quality mission.</em>
                                    </p>
                                </td>
                                <td style="width:159.95pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee consistently demonstrates exceptional commitment to the mission. </em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:17.7pt">
                                <td style="width:163.8pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['relations_improved_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <strong>&#xa0;</strong>
                                    </p>
                                </td>
                                <td style="width:158.45pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['relations_improved_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:159.95pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['relations_improved_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        &#xa0;
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>Comment:</strong>
                            <br>
                            <?= $section1['relations_improved_comment']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>How may the employee’s customer service skills/delivery be improved?</strong>
                            <br>
                            <?= $section1['skill_improved']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong><u>DEPENDABILITY</u></strong><strong>:</strong><strong>&#xa0; </strong>To what level is the employee dependable; How often does the employee show up to work on time and complete their scheduled shifts? Can the employee be counted on to complete tasks and meet deadlines consistently?
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:42.25pt">
                                <td style="width:157.8pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee is late, absent, misses deadlines.</em><em>&#xa0; </em><em>Improvement is mandatory.</em>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee adequately attends work, rarely misses or late, meets deadlines.</em>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee consistently on time, at work and completes deadlines ahead of schedule.</em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:28.15pt">
                                <td style="width:157.8pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['skill_improved_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['skill_improved_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['skill_improved_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>Comment:</strong>
                            <br>
                            <?= $section1['skill_improved_comment']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>How may the employee’s dependability be improved?</strong>
                            <br>
                            <?= $section1['dependability_improved']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong><u>ADHERENCE TO POLICY &amp; PROCEDURE</u></strong><strong>:</strong><strong>&#xa0; </strong>To what level does the employee adhere to standard operating policies and procedures?
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:42.25pt">
                                <td style="width:157.8pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee is frequently coached on standard operating policies and procedures. Improvement is mandatory.</em>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee adequately adheres to standard operating policies and procedures with few reminders. </em>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee is consistently exceptional in following standard operating policies and procedures.</em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:28.15pt">
                                <td style="width:157.8pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['dependability_improved_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['dependability_improved_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:161.1pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['dependability_improved_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>Comment:</strong>
                            <br>
                            <?= $section1['dependability_improved_comment']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>How may the employee’s adherence to policy and procedure be improved?</strong>
                            <br>
                            <?= $section1['policy_procedure_improved']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong><u>OTHER</u></strong><strong>:</strong><strong>&#xa0; </strong>
                            <br>
                            <?= $section1['policy_procedure_improved_other']; ?>
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:37.15pt">
                                <td style="width:161.7pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <em>Employee frequently falls below acceptable standard as outlined above.</em>
                                    </p>
                                </td>
                                <td style="width:161.9pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee adequately meets standard as outlined above.</em>
                                    </p>
                                </td>
                                <td style="width:162.75pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee is consistently exceptional in meeting performance standard.</em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:12.35pt">
                                <td style="width:161.7pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['policy_procedure_improved_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        &#xa0;
                                    </p>
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <strong>&#xa0;</strong>
                                    </p>
                                </td>
                                <td style="width:161.9pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['policy_procedure_improved_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:162.75pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['policy_procedure_improved_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>Comment</strong>:
                            <br>
                            <?= $section1['policy_procedure_improved_comment']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>How may employee’s performance in meeting this standard be improved?</strong>
                            <br>
                            <?= $section1['standard_improved']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>&#xa0;</strong>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <br /><strong><u>OTHER</u></strong><strong>:</strong><strong>&#xa0; </strong>
                            <br>
                            <?= $section1['standard_improved_other']; ?>
                        </p>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:37.15pt">
                                <td style="width:161.7pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <em>Employee frequently falls below acceptable standard as outlined above.</em>
                                    </p>
                                </td>
                                <td style="width:161.9pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee adequately meets standard as outlined above.</em>
                                    </p>
                                </td>
                                <td style="width:162.75pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d9e2f3">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <em>Employee is consistently exceptional in meeting performance standard.</em>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:12.35pt">
                                <td style="width:161.7pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['standard_improved_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        &#xa0;
                                    </p>
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <strong>&#xa0;</strong>
                                    </p>
                                </td>
                                <td style="width:161.9pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['standard_improved_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                                <td style="width:162.75pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
                                        <?php echo $section1['standard_improved_radio'] == 3 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            <strong>Comment</strong>:
                            <br>
                            <?= $section1['standard_improved_comment']; ?>
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                            &#xa0;
                        </p>
                        <h2>
                            <span style="font-family:Calibri; color:#000000">&#xa0;</span>
                        </h2>
                        <h2>
                            <span style="font-family:Calibri; color:#000000">Managers Additional Comments for the Review Period: </span>
                        </h2>
                        <?= $section1['managers_additional_comment']; ?>
                        <p style="margin-bottom:0pt; line-height:normal">
                            <strong>&#xa0;</strong>
                        </p>
                    <?php } ?>

                    <p style="line-height:108%; font-size:14pt">
                        <br style="page-break-before:always; clear:both" />
                    </p>
                    <!-- Section 1 End -->

                    <!-- Section 2 Start -->
                    <?php
                    $section2 = [];
                    if ($sectionData['section_2_json']) {
                        $section2 = json_decode($sectionData['section_2_json'], true)['data'];
                    }
                    ?>
                    <p style="line-height:108%; font-size:14pt">
                        <strong>Employee Section 2: The Year in Review</strong>
                    </p>
                    <p style="margin-bottom:1.05pt; line-height:108%; font-size:12pt">
                        <strong>List 3-4 top accomplishments &amp; add a reflection on overall performance for the year.</strong>
                    </p>
                    <table class="table table-responsive table-bordered">
                        <tr style="height:22.55pt">
                            <td style="width:16.5pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>&#xa0;</strong>
                                </p>
                            </td>
                            <td style="width:151.1pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Accomplishment</strong>
                                </p>
                            </td>
                            <td style="width:316.3pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Employee Comments/Reflection</strong>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:22.55pt">
                            <td style="width:16.5pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    1
                                </p>
                            </td>
                            <td style="width:151.1pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['accomplishment_1']; ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:316.3pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['accomplishment_comment_1']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:22.55pt">
                            <td style="width:16.5pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    2
                                </p>
                            </td>
                            <td style="width:151.1pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['accomplishment_2']; ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:316.3pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['accomplishment_comment_2']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:22.6pt">
                            <td style="width:16.5pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    3
                                </p>
                            </td>
                            <td style="width:151.1pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['accomplishment_3']; ?>
                                </p>
                            </td>
                            <td style="width:316.3pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['accomplishment_comment_3']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:22.6pt">
                            <td style="width:16.5pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    4
                                </p>
                            </td>
                            <td style="width:151.1pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['accomplishment_4']; ?>
                                </p>
                            </td>
                            <td style="width:316.3pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['accomplishment_comment_4']; ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                        <strong><span style="color:#1f497d">&#xa0;</span></strong>
                    </p>
                    <p style="line-height:108%; font-size:12pt">
                        <strong>Opportunities for Improved Performance: List 2-4 areas of improvement &amp; how you will work on these improvements over the coming year.</strong>
                    </p>
                    <table class="table table-responsive table-bordered">
                        <tr style="height:17.55pt">
                            <td style="width:29.25pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>&#xa0;</strong>
                                </p>
                            </td>
                            <td style="width:191.25pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Opportunity for Improvement</strong>
                                </p>
                            </td>
                            <td style="width:260.95pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Employee Comments</strong>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:24.2pt">
                            <td style="width:29.25pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    1
                                </p>
                            </td>
                            <td style="width:191.25pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['opportunities_1']; ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:260.95pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['opportunities_comment_1']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:17.55pt">
                            <td style="width:29.25pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    2
                                </p>
                            </td>
                            <td style="width:191.25pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['opportunities_2']; ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:260.95pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['opportunities_comment_2']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:17.6pt">
                            <td style="width:29.25pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    3
                                </p>
                            </td>
                            <td style="width:191.25pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['opportunities_3']; ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:260.95pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['opportunities_comment_3']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:17.6pt">
                            <td style="width:29.25pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    4
                                </p>
                            </td>
                            <td style="width:191.25pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['opportunities_4']; ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:260.95pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['opportunities_comment_4']; ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p style="line-height:108%; font-size:12pt">
                        <strong>&#xa0;</strong>
                    </p>
                    <p style="line-height:108%; font-size:12pt">
                        <strong>List 2-3 goals for the upcoming year. </strong><strong><u>One</u></strong><strong> must reflect a personal development goal.</strong>
                    </p>
                    <table class="table table-responsive table-bordered">
                        <tr style="height:32.35pt">
                            <td style="width:21.88pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top; background-color:#d9e2f3">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>&#xa0;</strong>
                                </p>
                            </td>
                            <td style="width:143.52pt; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top; background-color:#d9e2f3">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Goal</strong><strong>&#xa0; </strong>
                                </p>
                            </td>
                            <td style="width:329.67pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top; background-color:#d9e2f3">
                                <p style="margin-right:4.35pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>General comments and summary relating to the status of the goal, attainment, difficulty of goal, and impacting factors. </strong>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:22.85pt">
                            <td style="width:21.88pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    1
                                </p>
                            </td>
                            <td style="width:143.52pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['goal_1']; ?>
                                </p>
                            </td>
                            <td style="width:329.67pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['goal_comment_1']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:21.05pt">
                            <td style="width:21.88pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    2
                                </p>
                            </td>
                            <td style="width:143.52pt; border:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['goal_2']; ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:329.67pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['goal_comment_2']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:32.5pt">
                            <td style="width:21.88pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    3
                                </p>
                            </td>
                            <td style="width:143.52pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['goal_3']; ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:329.67pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-top:0.55pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?= $section2['goal_comment_3']; ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>
                    <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>
                    <h2>
                        <span style="font-family:Calibri; color:#000000">&#xa0;</span>
                    </h2>
                    <ol style="margin:0pt; padding-left:0pt">
                        <li class="ListParagraph" style="margin-left:14.11pt; padding-left:3.89pt; font-family:Calibri">
                            <strong>Have you and your manager reviewed your job description for this review period</strong>?&#xa0;&#xa0; <span style="width:33.8pt; display:inline-block">&#xa0;</span>                           
                        </li>
                    </ol>
                    <p style="margin-left:0pt; text-indent:18pt; line-height:108%; font-size:12pt">
                        (Please attach a copy of your job description for review with your manager)
                    </p>
                    <p style="text-indent:36pt; line-height:108%; font-size:12pt">
                        Yes
                        <span style="width:16.78pt; text-indent:0pt; display:inline-block">
                            <?php echo $section2['review_period_radio'] == 1 ? '<i class="fa fa-check-circle" style="display: contents !important;" aria-hidden="true"></i>' : '' ?>
                        </span>
                        <span style="width:36pt; text-indent:0pt; display:inline-block">&#xa0;</span>
                        No
                        <span style="width:16.78pt; text-indent:0pt; display:inline-block">
                            <?php echo $section2['review_period_radio'] == 2 ? '<i class="fa fa-check-circle" tyle="display: contents !important;" aria-hidden="true"></i>' : '' ?>
                        </span>
                    </p>
                    <p style="margin-left:18pt; text-indent:18pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>
                    <ol start="2" style="margin:0pt; padding-left:0pt">
                        <li class="ListParagraph" style="margin-left:14.11pt; padding-left:3.71pt; font-family:Calibri; font-weight:bold">
                            Do you have access to equipment and resources necessary to perform your job function?&#xa0; <span style="width:2.16pt; display:inline-block">&#xa0;</span>
                        </li>
                    </ol>
                    <p style="margin-left:20pt; line-height:108%; font-size:12pt">
                        (If No, please list the equipment you deem necessary subject to Managers approval and budgeting)
                    </p>
                    <p style="text-indent:36pt; line-height:108%; font-size:12pt">
                        Yes
                        <span style="width:16.78pt; text-indent:0pt; display:inline-block">
                            <?php echo $section2['equipment_resources_radio'] == 1 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                        </span>
                        <span style="width:36pt; text-indent:0pt; display:inline-block">&#xa0;</span>
                        No
                        <span style="width:16.78pt; text-indent:0pt; display:inline-block">
                            <?php echo $section2['equipment_resources_radio'] == 2 ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '' ?>
                        </span>
                    </p>
                    <p style="line-height:108%; font-size:12pt">
                        <strong>Necessary Equipment or Resources Needed</strong>:
                        <br>
                        <?= $section2['equipment_resources_needed']; ?>
                    </p>
                    <p style="text-indent:36pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>
                    <ol start="3" style="margin:0pt; padding-left:0pt">
                        <li class="ListParagraph" style="margin-left:14.11pt; padding-left:3.71pt; font-family:Calibri; font-weight:bold">
                            Is there any additional support or training you feel would be helpful for <?= $companyName ?> to provide for you to help you succeed in your current role?<span style="color:#1f497d"> </span>
                        </li>
                    </ol>
                    <?= $section2['additional_support']; ?>
                    <p class="ListParagraph">
                        <span style="font-family:Calibri">&#xa0;</span>
                    </p>
                    <ol start="4" style="margin:0pt; padding-left:0pt">
                        <li class="ListParagraph" style="margin-left:14.11pt; padding-left:3.71pt; font-family:Calibri; font-weight:bold">
                            Employee Additional Comments:
                        </li>
                    </ol>
                    <?= $section2['additional_comment']; ?>
                    <!-- Section 2 End -->


                    <p style="line-height:108%; font-size:14pt">
                        <br style="page-break-before:always; clear:both" />
                    </p>

                    <!-- Section 3 Start -->
                    <?php
                    $section3 = [];
                    if ($sectionData['section_3_json']) {
                        $section3 = json_decode($sectionData['section_3_json'], true)['data'];
                    }
                    ?>

                    <h2 style="line-height:108%; font-size:14pt">
                        <span style="font-family:Calibri; color:#000000">Manager &amp; Employee Section 3: The Year in Review </span>
                    </h2>
                    <h2>
                        <span style="font-family:Calibri; color:#000000">&#xa0;</span>
                    </h2>
                    <h2>
                        <span style="font-family:Calibri; color:#000000">Additional Comments, Feedback - Managers Comments: </span>
                    </h2>
                    <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                        <?= $section3['additional_comment_one'] ?>
                    </p>
                    <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>
                    <p style="margin-bottom:1.05pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>
                    <h2>
                        <span style="font-family:Calibri; color:#000000">Additional Comments, Feedback - Managers Comments: </span>
                    </h2>
                    <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                        <?= $section3['additional_comment_two'] ?>
                    </p>
                    <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>
                    <!-- Section 3 End -->

                    <!-- Section 4 Start -->
                    <p style="margin-bottom:1.05pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>

                    <h2>
                        <u><span style="font-family:Calibri; color:#000000">Section 4: Signatures</span></u>
                    </h2>
                    <p style="margin-bottom:0pt; line-height:108%; font-size:12pt">
                        &#xa0;
                    </p>
                    <table class="table table-responsive table-bordered">
                        <tr style="height:11.9pt">
                            <td style="width:240.9pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Employee</strong><strong>&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </strong><strong>Date</strong><strong>&#xa0; </strong>
                                </p>
                            </td>
                            <td style="width:240.9pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Manager</strong><strong>&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </strong><strong>Date</strong><strong>&#xa0; </strong>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:23.65pt">
                            <td style="width:240.9pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?php if ($sectionData['employee_signature']) { ?>
                                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $sectionData['employee_signature'] ?>" />
                                    <?php } ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?php if ($sectionData['employee_signature']) { ?>
                                        <?php $signDate = json_decode($sectionData['section_4_json'], true)['employee_signature_at']; ?>
                                        <strong>Sign Date:</strong> <?= formatDateToDB($signDate, DB_DATE_WITH_TIME, 'm-d-Y'); ?>
                                    <?php } ?>
                                </p>
                            </td>
                            <td style="width:240.9pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?php if ($sectionData['manager_signature']) { ?>
                                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $sectionData['manager_signature'] ?>" />
                                    <?php } ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?php if ($sectionData['employee_signature']) { ?>
                                        <?php $signDate = json_decode($sectionData['section_4_json'], true)['manager_signature_at']; ?>
                                        <strong>Sign Date:</strong> <?= formatDateToDB($signDate, DB_DATE_WITH_TIME, 'm-d-Y'); ?>
                                    <?php } ?>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:11.9pt">
                            <td style="width:240.9pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Next Level Approval</strong><strong>&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </strong><strong>Date</strong><strong>&#xa0; </strong>
                                </p>
                            </td>
                            <td style="width:240.9pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <strong>Human Resources</strong><strong>&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </strong><strong>Date</strong><strong>&#xa0; </strong>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:23.6pt">
                            <td style="width:240.9pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    &#xa0;
                                </p>
                            </td>
                            <td style="width:240.9pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-top:0.55pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?php if ($sectionData['hr_signature']) { ?>
                                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $sectionData['hr_signature'] ?>" />
                                    <?php } ?>
                                </p>
                                <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                    <?php if ($sectionData['hr_signature']) { ?>
                                        <?php $signDate = json_decode($sectionData['section_5_json'], true)['hr_manager_completed_at']; ?>
                                        <strong>Sign Date:</strong> <?= formatDateToDB($signDate, DB_DATE_WITH_TIME, 'm-d-Y'); ?>
                                    <?php } ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <!-- Section 4 End -->

                    <?php if ($userType != "employee") { ?>
                        <!-- Section 5 Start -->
                        <p style="margin-bottom:0pt; text-align:justify; line-height:108%; font-size:12pt">
                            <strong><span style="color:#1f497d">&#xa0;</span></strong>
                        </p>
                        <p style="margin-bottom:1.05pt; line-height:108%; font-size:12pt">
                            <strong><u>Section 5: Salary Recommendation</u></strong><strong>:</strong> For Manager Use Only:
                        </p>
                        <p style="margin-bottom:1.05pt; line-height:108%; font-size:12pt">
                            &#xa0;
                        </p>
                        <?php
                        $section5 = [];
                        if ($sectionData['section_5_json']) {
                            $section5 = json_decode($sectionData['section_5_json'], true);
                        }
                        ?>
                        <table class="table table-responsive table-bordered">
                            <tr style="height:10.15pt">
                                <td style="width:241.6pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Employees Current Pay Rate</strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Recommended Pay Increase</strong><strong>&#xa0; </strong>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:22pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section5['current_pay']; ?>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section5['recommended_pay']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:10.15pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Approved Amount</strong>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Approved by</strong>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:20.15pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section5['approved_amount']; ?>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= getUserNameBySID($section5['hr_manager_completed_by']); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:10.1pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Approved Date</strong>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top; background-color:#dbe5f1">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <strong>Effective Date of Increase</strong>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:17.05pt">
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= formatDateToDB($section5['hr_manager_completed_at'], DB_DATE_WITH_TIME, 'm-d-Y'); ?>
                                    </p>
                                </td>
                                <td style="width:241.6pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-top:0.5pt; padding-right:5.38pt; padding-left:4.97pt; vertical-align:top">
                                    <p style="margin-left:0.05pt; margin-bottom:0pt; line-height:normal; font-size:12pt">
                                        <?= $section5['effective_increase_date']; ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <!-- Section 5 End -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script>
        $(document).ready(function() {

            var perform_action = '<?php echo $action; ?>';

            if (perform_action == 'download') {
                var draw = kendo.drawing;
                draw.drawDOM($("#download_performance_document"), {
                        avoidLinks: false,
                        paperSize: "A4",
                        multiPage: true,
                        height: 500,
                        margin: {
                            bottom: "1cm",
                            left: "1cm",
                            top: ".3cm",
                            right: "1cm"
                        },
                        scale: 0.8
                    })
                    .then(function(root) {
                        return draw.exportPDF(root);
                    })
                    .done(function(data) {
                        var pdf;
                        pdf = data;

                        $('#myiframe').attr("src", data);
                        kendo.saveAs({
                            dataURI: pdf,
                            fileName: 'performance_evaluation_document.pdf',
                        });
                        window.close();
                    });
            } else {
                window.print();

                window.onafterprint = function() {
                    window.close();
                }
            }
        });
    </script>
</body>

</html>