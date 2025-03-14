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

        <!-- Question section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="2">
                        <strong>Question Answer</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($report['question_answers']) { ?>
                    <?php foreach ($report['question_answers'] as $question) { ?>
                        <?php if (!empty($question['question'])) { ?>
                            <tr>
                                <td colspan="3">
                                    <label>
                                        <strong><?php echo $question['question']; ?></strong>
                                    </label>
                                    <span class="value-box bg-gray">
                                        <?php
                                            $ans = @unserialize($question['answer']);
                                            if ($ans !== false) {
                                                echo implode(', ', $ans);
                                            } else {
                                                echo ucfirst($question['answer']);
                                            }
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?> 
                    <tr>
                        <td>
                            <div class="center-col">
                                <h2>No Questions Found</h2>
                            </div>
                        </td>
                    </tr> 
                <?php } ?>  
            </tbody>
        </table>
        <!-- Question section End -->

        <!-- Incident section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="4">
                        <strong>Incident(s)</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($report['incidents'])) { ?>
                    <?php 
                        $documentLinks = [];
                    ?>
                    <tr>
                        <th class="text-center">Type</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Completed Date</th>
                    </tr>
                    <?php foreach ($report['incidents'] as $dKey => $incident) { ?>
                        <tr>
                            <td class="text-center"><?php echo $incident['compliance_incident_type_name']; ?></td>
                            <td class="text-center"><?php echo ucwords($incident['status']); ?></td>
                            <td class="text-center"><?php echo $incident['first_name'] . ' ' . $incident['last_name']; ?></td>
                            <td class="text-center"><?php echo !empty($incident['completed_at']) ? formatDateToDB($incident['completed_at'], DB_DATE_WITH_TIME, DATE) : 'N/A'; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td>
                            <div class="center-col">
                                <h2>No incident Found</h2>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Incident section End -->

        <?php 
            $allReportLinks = [];
        ?>

        <!-- Report Document section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="4">
                        <strong>Document(s)</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($report['documents'])) { ?>
                    <?php 
                        $documentLinks = [];
                    ?>
                    <tr>
                        <th class="text-center">Title</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Added By</th>
                        <th class="text-center">Added Date</th>
                    </tr>
                    <?php foreach ($report['documents'] as $dKey => $document) { ?>
                        <tr>
                            <?php 
                                $documentAddedBy = '';
                                //
                                if ($document['created_by'] != 0) {
                                    $documentAddedBy = getEmployeeOnlyNameBySID($document['created_by']);
                                } else {
                                    $documentAddedBy = $document['manual_email'];
                                }
                                //
                                $documentURL = '';
                                //
                                if ($document["file_type"] === "image") {
                                    $documentURL = AWS_S3_BUCKET_URL . $document["s3_file_value"]; 
                                } elseif ($document["file_type"] === "document") {
                                    if (preg_match("/doc|docx|xls|xlsx|ppt|pptx/i", $document["s3_file_value"])) {
                                        $documentURL = 'https://view.officeapps.live.com/op/embed.aspx?src='.AWS_S3_BUCKET_URL . $document["s3_file_value"];
                                    } else {
                                        $documentURL = AWS_S3_BUCKET_URL . $document["s3_file_value"];
                                    }
                                } 
                                //
                                $documentLinks[$dKey]['title'] = $document['title'];
                                $documentLinks[$dKey]['type']  = ucwords($document['file_type']);
                                $documentLinks[$dKey]['url'] = $documentURL;
                                //
                                $allReportLinks[$document['sid']]['title'] = $document['title'];
                                $allReportLinks[$document['sid']]['type']  = ucwords($document['file_type']);
                                $allReportLinks[$document['sid']]['url'] = $documentURL;
                            ?>
                            <td class="text-center"><?php echo $document['title']; ?></td>
                            <td class="text-center"><?php echo ucwords($document['file_type']); ?></td>
                            <td class="text-center"><?php echo $documentAddedBy; ?></td>
                            <td class="text-center"><?php echo formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td>
                            <div class="center-col">
                                <h2>No Documents Found</h2>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Report Document section End -->   

        <!-- Internal Video Link section Start -->
        <?php if ($documentLinks) { ?>
            <table class="incident-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="2">
                            <strong>Document Link(s)</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-center">Title</th>
                        <th class="text-center">Link</th>
                    </tr>
                    <?php foreach ($documentLinks as $link) { ?>
                        <tr>
                            <td class="text-center"><?php echo $link['title']; ?></td>
                            <td class="text-center"><?php echo $link['url']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?> 
        <!-- Internal Video Link section End -->  
            
        <!-- Report Audio/Video section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="4">
                        <strong>Audio(s)/Video(s)</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($report['audios'])) { ?>
                    <?php 
                        $audioVideoLinks = [];
                    ?>
                    <tr>
                        <th class="text-center">Title</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Added By</th>
                        <th class="text-center">Added Date</th>
                    </tr>
                    <?php foreach ($report['audios'] as $mKey => $media) { ?>
                        <tr>
                            <?php 
                                //
                                $mediaAddedBy = '';
                                $mediaType = '';
                                $mediaURL = '';
                                //
                                if ($media['created_by'] != 0) {
                                    $mediaAddedBy = getEmployeeOnlyNameBySID($media['created_by']);
                                } else {
                                    $mediaAddedBy = $media['manual_email'];
                                }

                                if ($media['file_type'] == 'link') {
                                    if (strpos($media['file_value'], 'youtube') > 0) {
                                        $mediaType = 'Youtube Link';
                                    } elseif (strpos($media['file_value'], 'vimeo') > 0) {
                                        $mediaType = 'vimeo Link';
                                    } else {
                                        $mediaType = 'Unknown Link';
                                    }
                                    //
                                    $mediaURL = $media['file_value'];
                                } else {
                                    $mediaType = ucwords($media['file_type']);
                                    $mediaURL  = AWS_S3_BUCKET_URL . $media["s3_file_value"];
                                } 
                                //
                                $audioVideoLinks[$mKey]['title'] = $media['title'];
                                $audioVideoLinks[$mKey]['type'] = $mediaType;
                                $audioVideoLinks[$mKey]['url'] = $mediaURL;
                                //
                                $allReportLinks[$media['sid']]['title'] = $media['title'];
                                $allReportLinks[$media['sid']]['type'] = $mediaType;
                                $allReportLinks[$media['sid']]['url'] = $mediaURL;  
                            ?>
                            <td class="text-center"><?php echo $media['title']; ?></td>
                            <td class="text-center"><?php echo $mediaType; ?></td>
                            <td class="text-center"><?php echo $mediaAddedBy; ?></td>
                            <td class="text-center"><?php echo formatDateToDB($media['created_at'], DB_DATE_WITH_TIME, DATE); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td>
                            <div class="center-col">
                                <h2>No Audio(s)/Video(s) Found</h2>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Report Audio/Video section End -->

        <!-- Audio Video Link section Start -->
        <?php if ($audioVideoLinks) { ?>
            <table class="incident-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="2">
                            <strong>Audio/Video Link(s)</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-center">Title</th>
                        <th class="text-center">Link</th>
                    </tr>
                    <?php foreach ($audioVideoLinks as $link) { ?>
                        <tr>
                            <td class="text-center"><?php echo $link['title']; ?></td>
                            <td class="text-center"><?php echo $link['url']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?> 
        <!-- Audio Video Link section End -->    

        <!-- Internal Employees section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="5">
                        <strong>Internal Employee(s)</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($report['internal_employees'])) { ?>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Added By</th>
                        <th class="text-center">Added Date</th>
                    </tr>
                    <?php foreach ($report['internal_employees'] as $internalEmployee) { ?>
                        <tr>
                            <?php 
                                $employeeInfo = getEmployeeBasicInfo($internalEmployee['employee_sid']);
                                $addedBy = getEmployeeOnlyNameBySID($internalEmployee['created_by']);
                            ?>
                            <td class="text-center"><?php echo !empty($employeeInfo['name']) ? $employeeInfo['name'] : 'N/A'; ?></td>
                            <td class="text-center"><?php echo !empty($employeeInfo['phone']) ? $employeeInfo['phone'] : 'N/A'; ?></td>
                            <td class="text-center"><?php echo !empty($employeeInfo['email']) ? $employeeInfo['email'] : 'N/A'; ?></td>
                            <td class="text-center"><?php echo $addedBy; ?></td>
                            <td class="text-center"><?php echo formatDateToDB($internalEmployee['created_at'], DB_DATE_WITH_TIME, DATE); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td>
                            <div class="center-col">
                                <h2>No Internal Employee Found</h2>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Internal Employees section End -->

        <!-- External Employees section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="4">
                        <strong>External Employee(s)</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($report['internal_employees'])) { ?>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Added By</th>
                        <th class="text-center">Added Date</th>
                    </tr>
                    <?php foreach ($report['external_employees'] as $externalEmployee) { ?>
                        <tr>
                            <td class="text-center"><?php echo $externalEmployee['external_name']; ?></td>
                            <td class="text-center"><?php echo $externalEmployee['external_email']; ?></td>
                            <td class="text-center"><?php echo getEmployeeOnlyNameBySID($externalEmployee['created_by']); ?></td>
                            <td class="text-center"><?php echo formatDateToDB($externalEmployee['created_at'], DB_DATE_WITH_TIME, DATE); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td>
                            <div class="center-col">
                                <h2>No External Employee Found</h2>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- External Employees section End -->

        <!-- Email Section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="3">
                        <strong>Emails</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report['emails'] as $key => $email) { ?>
                    <tr>
                        <td>
                            <table class="incident-table">
                                <thead>
                                    <tr class="bg-gray">
                                        <th colspan="3">
                                            <strong>
                                                <?php
                                                    echo $email['userName'];
                                                ?>
                                            </strong>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($email['emails'] as $emailData) { ?>
                                        <tr>
                                            <td>
                                                <table class="incident-table">
                                                    <thead>
                                                        <tr class="bg-gray">
                                                            <th colspan="3">
                                                                <strong>
                                                                    <?php
                                                                        echo 'Sent Date: '. formatDateToDB($emailData['send_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                    ?>
                                                                </strong>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <strong>To :</strong>
                                                                </label>
                                                                <span class="value-box bg-gray">
                                                                    <?php
                                                                        if ($emailData['receiver_sid'] != 0) {
                                                                            echo getEmployeeOnlyNameBySID($emailData['receiver_sid']);
                                                                        } else {
                                                                            echo $emailData['manual_email'];
                                                                        }
                                                                    ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <strong>From :</strong>
                                                                </label>
                                                                <span class="value-box bg-gray">
                                                                    <?php
                                                                        if ($emailData['sender_sid'] != 0) {
                                                                            echo getEmployeeOnlyNameBySID($emailData['sender_sid']);
                                                                        } else {
                                                                            echo $emailData['manual_email'];
                                                                        }
                                                                    ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <strong>Subject :</strong>
                                                                </label>
                                                                <span class="value-box bg-gray">
                                                                    <?php echo $emailData['subject']; ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <?php echo $emailData['message_body']; ?>
                                                            </td>
                                                        </tr>
                                                        <?php if ($emailData['attachments']) { ?>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <table class="incident-table">
                                                                        <thead>
                                                                            <tr class="bg-gray">
                                                                                <th colspan="2">
                                                                                    <strong>
                                                                                        Attachment(s)
                                                                                    </strong>
                                                                                </th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Title</th>
                                                                                <th>Link</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach ($emailData['attachments'] as $attachment) { ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <?php
                                                                                            echo $allReportLinks[$attachment]['title'];
                                                                                        ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php
                                                                                            echo $allReportLinks[$attachment]['url'];
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>   
                                                                            <?php } ?>    
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>    
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>        
                                    <?php } ?>    
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Email Section End -->

        <!-- Report Notes section Start -->
        <table class="incident-table">
            <thead>
                <tr class="bg-gray">
                    <th colspan="2">
                        <strong>Note(s)</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($report['notes']) { ?>
                    <?php foreach ($report['notes'] as $note) { ?>
                        <tr>
                            <td>
                                <label>
                                    <strong>Name :</strong>

                                </label>
                                <span>
                                    <?php echo $note['first_name']. ' ' .$note['last_name']; ?>
                                </span>

                            </td>
                            <td>
                                <label>
                                    <strong>Date :</strong>
                                </label>
                                <span>
                                    <time><?php echo formatDateToDB($note['created_at'], DB_DATE_WITH_TIME, DATE); ?></time>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label>
                                    <strong>note :</strong>
                                </label>
                                <span class="value-box bg-gray">
                                    <?php echo strip_tags($note['notes']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td>
                            <div class="center-col">
                                <h2>No Note Found</h2>
                            </div>
                        </td>
                    </tr>
                <?php } ?>    
            </tbody>
        </table>
        <!-- Report Notes section End -->
    </section>
    <a href="<?php echo base_url('compliance_report/download_compliance_report_all_documents_and_videos/both').'/'.$report_type.'/'.$incident_sid; ?>"  id="download_all_files"></a>

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
                .done(function(incident_data) {
                    if ('<?php echo $section; ?>' == 'all') {
                        $.ajax({
                            type: 'POST',
                            data:{
                                incident_sid: '<?php echo $incident_sid; ?>',
                                incident_base64:incident_data
                            },
                            url: "<?php echo base_url('compliance_report/save_compliance_report_pdf'); ?>",
                            success: function(data){
                                $('#download_all_files')[0].click();
                                window.setTimeout(function(){
                                    window.close();
                                }, 5000);
                            },
                            error: function(){

                            }
                        });
                    } else {
                        kendo.saveAs({
                            dataURI: incident_data,
                            fileName: '<?php echo "Reported_incident.pdf"; ?>'
                        });
                        setTimeout(function(){
                            window.close();
                        }, 1000);
                    }
                });
            }
        });


        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    </script>
</body>
</html>
