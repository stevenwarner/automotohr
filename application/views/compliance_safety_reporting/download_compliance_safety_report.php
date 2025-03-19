<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <title><?php echo $title; ?></title>
</head>

<style type="text/css">
    @font-face {
        font-family: 'Conv_SCRIPTIN';
        src: url('<?php echo base_url('assets/employee_panel/fonts/SCRIPTIN.eot') ?>');
        src: local('☺'), url('<?php echo base_url('assets/employee_panel/fonts/SCRIPTIN.woff') ?>') format('woff'),
        url('<?php echo base_url('assets/employee_panel/fonts/SCRIPTIN.ttf') ?>') format('truetype'),
        url('<?php echo base_url('assets/employee_panel/fonts/SCRIPTIN.svg') ?>') format('svg');
        font-weight: normal;
        font-style: normal;
    }
    @page { margin: 0 }
    body { margin: 0 }
    .sheet {
        margin: 0;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;
        page-break-after: always;
        font-size: 14px;
        font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
    }

    /** Paper sizes **/
    body.A3           .sheet { width: 297mm; /*height: 419mm;*/ }
    body.A3.landscape .sheet { width: 420mm; height: 296mm; }
    body.A4           .sheet { width: 210mm; /*height: 296mm;*/ margin: 5mm; }
    body.A4.landscape .sheet { width: 297mm; height: 209mm }
    body.A5           .sheet { width: 148mm; height: 209mm }
    body.A5.landscape .sheet { width: 210mm; height: 147mm }

    /** Padding area **/
    .sheet.padding-10mm { padding: 10mm }
    .sheet.padding-15mm { padding: 15mm }
    .sheet.padding-20mm { padding: 20mm }
    .sheet.padding-25mm { padding: 25mm }

    /** For screen preview **/
    @media screen {
        /*body { background: #e0e0e0 }*/
        .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
            margin: 5mm;
        }
    }

    /** Fix for Chrome issue #273306 **/
    @media print {
        body.A3.landscape { width: 420mm }
        body.A3, body.A4.landscape { width: 297mm }
        body.A4, body.A5.landscape { width: 210mm }
        body.A5                    { width: 148mm }
    }

    .sheet-header {
        float: left;
        width: 100%;
        padding: 0 0 2px 0;
        margin: 0 0 5px 0;
        border-bottom: 5px solid #000;
    }
    .header-logo{
        float: left;
        width: 100%;
    }
    .center-col{
        float: left;
        width: 100%;
        text-align: center;
        margin-top: 14px;
    }
    .center-col h2,
    .center-col p{
        margin: 0 0 5px 0;
    }
    .right-header{
        float: left;
        width: 20%;
        text-align: center;
    }
    .right-header h3,
    .right-header p{
        margin: 0 0 5px 0;
    }
    .right-header p{
        font-size: 14px;
    }
    .incident-table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 5px;
        border: 1px solid #000;
        text-align: left;
        border-collapse: collapse;
    }
    .incident-table thead > tr > th,
    .incident-table tbody > tr > th,
    .incident-table tfoot > tr > th,
    .incident-table thead > tr > td,
    .incident-table tbody > tr > td,
    .incident-table tfoot > tr > td {
        padding: 4px;
        border: 1px solid #000;
        vertical-align: top;
    }
    .bg-gray{
        background-color: #C9C9C9;
    }

    .bg-black {
        background-color: #000;
        color: #fff;
        text-align: center;
        font-style: oblique;
        line-height: 32px;
    }

    .value-box {
        float: left;
        width: 100%;
        min-height: 20px;
        padding: 3px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .incident-table.no-border{
        border:none !important;
    }
    .incident-table.no-border thead tr th,
    .incident-table.no-border tbody tr td{
        border:none !important;
    }
    ul{
        list-style: none;
    }
    .signature-field{
        border: 0;
        padding: 0 36px;
        font-size: 24px;
        font-weight: bold;
        font-family: 'Conv_SCRIPTIN';
        word-spacing: 15px;
        line-height: 56px;}
    .text-center{
        text-align: center;
    }
</style>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A3" id="safety_checklist">
    <section class="sheet padding-10mm">
        <article class="sheet-header">
            <div class="header-logo">
                <h2 style="margin: 0;"><?php echo $company_name; ?></h2>
                <small>
                    <?php echo $action_date; ?>: <b><?php echo reset_datetime(array('datetime' => date('Y-m-d'), '_this' => $this)); ?></b><br>
                    <?php echo $action_by; ?>: <b><?php echo $action_by_name; ?></b>
                </small>
            </div>
            <div class="center-col">
                <h2><?php echo $report['title']; ?></h2>
            </div>
        </article>

        <!-- Reporter Information section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="5">
                        <strong>Reporter Information</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Job Title</th>
                    <th class="text-center">Telephone Number</th>
                </tr>
                <tr>
                    <td class="text-center"><?php echo $report['first_name'].' '.$report['last_name']; ?></td>
                    <td class="text-center"><?php echo !empty($report['email']) ? $report['email'] : 'N/A'; ?></td>
                    <td class="text-center"><?php echo !empty($report['job_title']) ? $report['job_title'] : 'N/A'; ?></td>
                    <td class="text-center"><?php echo !empty($report['PhoneNumber']) ? $report['PhoneNumber'] : 'N/A'; ?></td>
                </tr>
            </tbody>
        </table>
        <!-- Reporter Information section End -->

        <!-- Reporter Information section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="5">
                        <strong>Compliance Report Information</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="text-center">Report Name</th>
                    <th class="text-center">Reported Date</th>
                    <th class="text-center">Completion Date</th>
                    <th class="text-center">Report Status</th>
                </tr>
                <tr>
                    <td class="text-center"><?php echo !empty($report['compliance_report_name']) ? $report['compliance_report_name'] : 'N/A'; ?></td>
                    <td class="text-center"><?php echo !empty($report['report_date']) ? formatDateToDB($report['report_date'], DB_DATE, DATE) : 'N/A'; ?></td>
                    <td class="text-center"><?php echo !empty($report['completion_date']) ? formatDateToDB($report['completion_date'], DB_DATE, DATE) : 'N/A'; ?></td>
                    <td class="text-center"><?php echo !empty($report['status']) ? strtoupper($report['status']) : 'N/A'; ?></td>
                </tr>
            </tbody>
        </table>
        <!-- Reporter Information section End -->

        <?php $this->load->view("compliance_safety_reporting/partials/download/question", ['questions' => $report['question_answers']]); ?>

        <?php $this->load->view("compliance_safety_reporting/partials/download/incidents", ['incidents' => $report['incidents']]); ?>

        <?php $this->load->view("compliance_safety_reporting/partials/download/documents", ['documents' => $report['documents']]); ?>
            
        <?php $this->load->view("compliance_safety_reporting/partials/download/media", ['audios' => $report['audios']]); ?> 

        <?php $this->load->view("compliance_safety_reporting/partials/download/internal", ['internalEmployees' => $report['internal_employees']]); ?> 

        <?php $this->load->view("compliance_safety_reporting/partials/download/external", ['internalEmployees' => $report['external_employees']]); ?> 

        <?php $this->load->view("compliance_safety_reporting/partials/download/emails", ['emails' => $report['emails']]); ?> 

        <?php $this->load->view("compliance_safety_reporting/partials/download/comments", ['notes' => $report['notes']]); ?> 

        <?php if (isset($report['incidentsDetail'])) { ?>
            <?php foreach ($report['incidentsDetail'] as $incidentDetail) { ?>
                <?php $this->load->view("compliance_safety_reporting/partials/download/incident_detail", ['incidentDetail' => $incidentDetail]); ?> 
            <?php } ?>
        <?php } ?>    

    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script type="text/javascript">

        $(window).on( "load", function() {
            var action = '<?php echo $action; ?>';

            if (action == 'print') {
                setTimeout(function(){
                    window.print();
                }, 1000);

                window.onafterprint = function(){
                    window.close();

                }
            } else if (action == 'download') {
                //
                var draw = kendo.drawing;
                draw.drawDOM($("#safety_checklist"), {
                    avoidLinks: false,
                    paperSize: "auto",
                    multiPage: true,
                    margin: { bottom: "1cm" },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(report_data) {
                    $.ajax({
                        type: 'POST',
                        data:{
                            report_sid: '<?php echo $report_sid; ?>',
                            report_base64: report_data,
                            file_links: <?= json_encode($report['fileToDownload']); ?>
                        },
                        url: "<?php echo base_url('compliance_safety_report/save_compliance_report_pdf'); ?>",
                        success: function(data){
                            // 
                            window.setTimeout(function(){
                                window.close();
                            }, 5000);
                        },
                        error: function(){

                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
