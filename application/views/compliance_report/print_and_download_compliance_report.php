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
    body.A3           .sheet { width: 297mm; height: 419mm }
    body.A3.landscape .sheet { width: 420mm; height: 296mm }
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
<body class="A4" id="safety_checklist">
    <section class="sheet padding-10mm">
        <article class="sheet-header">
            <div class="header-logo">
                <h2 style="margin: 0;"><?php echo $company_name; ?></h2>
                <small>
                    <?php echo $action_date; ?>: <b><?php echo reset_datetime(array('datetime' => date('Y-m-d'), '_this' => $this)); ?></b><br>
                    <?php echo $action_by; ?>: <b><?php echo $employee_name; ?></b>
                </small>
            </div>
            <div class="center-col">
                <h2><?php echo $incident_name; ?></h2>
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
                    <td class="text-center"><?php echo $incident_reporter_name; ?></td>
                    <td class="text-center"><?php echo $incident_reporter_email; ?></td>
                    <td class="text-center"><?php echo $incident_reporter_title; ?></td>
                    <td class="text-center"><?php echo $incident_reporter_phone; ?></td>
                </tr>
            </tbody>
        </table>
        <!-- Reporter Information section End -->

        <?php if ((!empty($questions) && sizeof($questions) > 0) && $section == 'questions') { ?>
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
                    <?php foreach ($questions as $key => $question) { ?>
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
                </tbody>
            </table>
            <!-- Question section End -->

            <!-- Witness section Start -->
            <table class="incident-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="5">
                            <strong>Witnesses</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($witnesses)) { ?>
                        <tr>
                            <th class="text-center">Witness Name</th>
                            <th class="text-center">Witness Phone</th>
                            <th class="text-center">Witness Email</th>
                            <th class="text-center">Witness Title</th>
                            <th class="text-center">Can Provide Information</th>
                        </tr>
                        <?php foreach ($witnesses as $key => $witness) { ?>
                            <tr>
                                <td class="text-center"><?php echo $witness['witness_name']; ?></td>
                                <td class="text-center"><?php echo $witness['witness_phone']; ?></td>
                                <td class="text-center"><?php echo $witness['witness_email']; ?></td>
                                <td class="text-center"><?php echo $witness['witness_title']; ?></td>
                                <td class="text-center">
                                    <?php
                                        $can_provide = $witness['can_provide_info'];
                                        if ($can_provide == 'yes') {
                                            echo '<b>YES</b>';
                                        } else if ($can_provide == 'no') {
                                            echo '<b>NO</b>';
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td>
                                <div class="center-col">
                                    <h2>No witnesses Found</h2>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- Witness section End -->
        <?php } else if ((!empty($single_email) && sizeof($single_email) > 0) && $section == 'single_email') { ?>
            <!-- Single Email Section Start -->
            <table class="incident-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="3">
                            <strong>Emails</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table class="incident-table">
                                <thead>
                                    <tr class="bg-gray">
                                        <th colspan="3">
                                            <strong>
                                                <?php
                                                    $email_type = '';

                                                    if ($single_email['receiver_sid'] == $current_user || $single_email['receiver_sid'] == 0) {
                                                        $email_type = 'Received';

                                                    } else {
                                                        $email_type = 'Sent';
                                                    }

                                                    echo $email_type.' ( '.my_date_format($single_email['send_date']).' )';
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
                                                    $receiver_sid = $single_email['receiver_sid'];
                                                    if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                        $witness_id = str_replace('_wid', '', $receiver_sid);
                                                        $receiver_name = get_witness_name_by_id($witness_id);
                                                        echo $receiver_name;
                                                    } else {
                                                        if ($incident_type == 'anonymous' && $receiver_sid != $current_user) {
                                                            echo 'Anonymous';
                                                        } else {
                                                            if($receiver_sid == 0){
                                                                echo $single_email['manual_email'];
                                                            } else{
                                                                $receiver_info = db_get_employee_profile($receiver_sid);
                                                                $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                echo $receiver_name;
                                                            }
                                                        }
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
                                                    $sender_sid = $single_email['sender_sid'];
                                                    if (str_replace('_wid', '', $sender_sid) != $sender_sid || $sender_sid == 0) {
                                                        if($sender_sid == 0){
                                                            echo $single_email['manual_email'];
                                                        } else{
                                                            $witness_id = str_replace('_wid', '', $sender_sid);
                                                            $receiver_name = get_witness_name_by_id($witness_id);
                                                            echo $receiver_name;
                                                        }
                                                    } else {
                                                        if ($incident_type == 'anonymous' && $sender_sid != $current_user) {
                                                            echo 'Anonymous';
                                                        } else {
                                                            $receiver_info = db_get_employee_profile($sender_sid);
                                                            $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                            echo $receiver_name;
                                                        }
                                                    }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <label>
                                                <strong>Subject :</strong>
                                            </label>
                                            <span class="value-box bg-gray">
                                                <?php echo $single_email['subject']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <?php echo $single_email['message_body']; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Single Email Section End -->
        <?php } else if ((!empty($emails) && sizeof($emails) > 0) && $section == 'emails') { ?>
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
                    <?php foreach ($emails as $key => $email) { ?>
                        <tr>
                            <td>
                                <table class="incident-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="3">
                                                <strong>
                                                    <?php
                                                        $email_type = '';

                                                        if ($email['receiver_sid'] == $current_user || $email['receiver_sid'] == 0) {
                                                            $email_type = 'Received';

                                                        } else {
                                                            $email_type = 'Sent';
                                                        }

                                                        echo $email_type.' ( '.my_date_format($email['send_date']).' )';
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
                                                        $receiver_sid = $email['receiver_sid'];
                                                        if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                            $witness_id = str_replace('_wid', '', $receiver_sid);
                                                            $receiver_name = get_witness_name_by_id($witness_id);
                                                            echo $receiver_name;
                                                        } else {
                                                            if ($incident_type == 'anonymous' && $receiver_sid != $current_user) {
                                                                echo 'Anonymous';
                                                            } else {
                                                                if($receiver_sid == 0){
                                                                    echo $email['manual_email'];
                                                                } else{
                                                                    $receiver_info = db_get_employee_profile($receiver_sid);
                                                                    $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                    echo $receiver_name;
                                                                }
                                                            }
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
                                                        $sender_sid = $email['sender_sid'];
                                                        if (str_replace('_wid', '', $sender_sid) != $sender_sid || $sender_sid == 0) {
                                                            if($sender_sid == 0){
                                                                echo $email['manual_email'];
                                                            } else{
                                                                $witness_id = str_replace('_wid', '', $sender_sid);
                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                echo $receiver_name;
                                                            }
                                                        } else {
                                                            if ($incident_type == 'anonymous' && $sender_sid != $current_user) {
                                                                echo 'Anonymous';
                                                            } else {
                                                                $receiver_info = db_get_employee_profile($sender_sid);
                                                                $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                echo $receiver_name;
                                                            }
                                                        }
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <label>
                                                    <strong>Subject :</strong>
                                                </label>
                                                <span class="value-box bg-gray">
                                                    <?php echo $email['subject']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <?php echo $email['message_body']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- Email Section End -->
        <?php } else if ((!empty($manual_emails) && sizeof($manual_emails) > 0) && $section == 'emails') { ?>
            <!-- Manual Email Section Start -->
            <table class="incident-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="3">
                            <strong>Emails</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($manual_emails as $key => $email) { ?>
                        <tr>
                            <td>
                                <table class="incident-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="3">
                                                <strong>
                                                    <?php
                                                        $email_type = '';

                                                        if ($email['receiver_sid'] == $current_user) {
                                                            $email_type = 'Received';

                                                        } else {
                                                            $email_type = 'Sent';
                                                        }

                                                        echo $email_type.' ( '.my_date_format($email['send_date']).' )';
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
                                                        $receiver_sid = $email['receiver_sid'];
                                                        if (!$receiver_sid == 0) {
                                                            if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                echo $receiver_name;
                                                            } else {
                                                                $receiver_info = db_get_employee_profile($receiver_sid);
                                                                $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                echo $receiver_name;
                                                            }
                                                        } else {
                                                            echo $email['manual_email'];
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
                                                        $sender_sid = $email['sender_sid'];
                                                        if (!$sender_sid == 0) {
                                                            if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                $witness_id = str_replace('_wid', '', $sender_sid);
                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                echo $receiver_name;
                                                            } else {
                                                                $receiver_info = db_get_employee_profile($sender_sid);
                                                                $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                echo $receiver_name;
                                                            }
                                                        } else {
                                                            echo $email['manual_email'];
                                                        }
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <label>
                                                    <strong>Subject :</strong>
                                                </label>
                                                <span class="value-box bg-gray">
                                                    <?php echo $email['subject']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <?php echo $email['message_body']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- Manual Email Section End -->
        <?php } else if ((!empty($comments) && sizeof($comments) > 0) && $section == 'comments') { ?>
            <!-- Comment Section Start -->
            <table class="incident-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="2">
                            <strong>Notes</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $key => $comment) { ?>
                        <?php
                            $comment_type = !empty($comment['employer_sid']) ? 'Reply' : 'Response';
                            // $comment_type = !empty($comment['employer_sid']) ? $comment['employer_sid'] : $comment['applicant_sid'];
                            $user_sid = !empty($comment['employer_sid']) ? $comment['employer_sid'] : $comment['applicant_sid'];
                            $user_info = db_get_employee_profile($user_sid);
                            if ($incident_type == 'anonymous') {
                                if (!empty($comment['employer_sid'])) {
                                    $name = 'Anonymous';
                                } else {
                                    $name = $user_info[0]['first_name'].' '.$user_info[0]['last_name'];
                                }

                            } else {
                                $name = $user_info[0]['first_name'].' '.$user_info[0]['last_name'];
                            }

                        ?>
                        <tr>
                            <td>
                                <table class="incident-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="3">
                                                <strong>
                                                    <?php
                                                        echo $comment_type;
                                                    ?>
                                                </strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label>
                                                    <strong>Name :</strong>

                                                </label>
                                                <span>
                                                    <?php echo $name; ?>
                                                </span>

                                            </td>
                                            <td>
                                                <label>
                                                    <strong>Date :</strong>
                                                </label>
                                                <span>
                                                    <time><?php echo my_date_format($comment['date_time']); ?></time>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <label>
                                                    <strong>note :</strong>
                                                </label>
                                                <span class="value-box bg-gray">
                                                    <?php echo strip_tags($comment['comment']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- Comment Section End -->
        <?php } else if ($section == 'all_emails') { ?>
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
                    <tr>
                        <td>
                            <?php
                            foreach ($incident_all_emails as $incident_all_email) {
                                $manager_sid            = $incident_all_email['manager_sid'];
                                $manager_name           = $incident_all_email['manger_name'];
                                $incident_emails        = $incident_all_email['incident_emails'];
                                $incident_manual_emails = $incident_all_email['incident_manual_emails'];
                            ?>
                                <table class="incident-table">
                                    <thead>
                                        <tr>
                                            <th class="bg-black">
                                                <h2>

                                                <?php echo $manager_name . ' ( Emails )'; ?>
                                                </h2>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php foreach ($incident_emails as $key => $incident_email) { ?>
                                                    <table class="incident-table">
                                                        <thead>
                                                            <tr>
                                                                <th class="bg-black">
                                                                    <h3>
                                                                        <?php echo $incident_email['section_name']; ?>
                                                                    </h3>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($incident_email['emails'])) { ?>
                                                                <?php foreach ($incident_email['emails']as $key => $email) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <table class="incident-table">
                                                                                <thead>
                                                                                    <tr class="bg-gray">
                                                                                        <th colspan="3">
                                                                                            <strong>
                                                                                                <?php
                                                                                                    $email_type = '';

                                                                                                    if ($email['receiver_sid'] == $manager_sid) {
                                                                                                        $email_type = 'Received';
                                                                                                    } else {
                                                                                                        $email_type = 'Sent';
                                                                                                    }

                                                                                                    echo $email_type.' ( '.my_date_format($email['send_date']).' )';
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
                                                                                                    $receiver_sid = $email['receiver_sid'];
                                                                                                    if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                                                        $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                                                        $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                        echo $receiver_name;
                                                                                                    } else {
                                                                                                        if ($incident_type == 'anonymous' && $receiver_sid == $incident_reporter_sid) {
                                                                                                            echo 'Anonymous';
                                                                                                        } else {
                                                                                                            $receiver_info = db_get_employee_profile($receiver_sid);
                                                                                                            $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                            echo $receiver_name;
                                                                                                        }
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
                                                                                                    $sender_sid = $email['sender_sid'];
                                                                                                    if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                        $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                        $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                        echo $receiver_name;
                                                                                                    } else {
                                                                                                        if ($incident_type == 'anonymous' && $sender_sid == $incident_reporter_sid) {
                                                                                                            echo 'Anonymous';
                                                                                                        } else {
                                                                                                            $receiver_info = db_get_employee_profile($sender_sid);
                                                                                                            $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                            echo $receiver_name;
                                                                                                        }
                                                                                                    }
                                                                                                ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <label>
                                                                                                <strong>Subject :</strong>
                                                                                            </label>
                                                                                            <span class="value-box bg-gray">
                                                                                                <?php echo $email['subject']; ?>
                                                                                            </span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="3">
                                                                                            <?php echo $email['message_body']; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>

                                                <?php if (!empty($incident_manual_emails)) { ?>
                                                    <?php foreach ($incident_manual_emails as $key => $incident_email) { ?>
                                                        <table class="incident-table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="bg-black">
                                                                        <h3>
                                                                            <?php
                                                                                $user_1 = $incident_email['name'];
                                                                                $user_1_name = explode("(",$user_1);
                                                                                $user_one_name = $user_1_name[0];
                                                                                $user_2 = $incident_email['user_one_email'];
                                                                                $user_2_name = explode("@",$user_2);
                                                                                $user_two_name = $user_2_name[0];

                                                                                echo $manager_name.' & '.$user_two_name;
                                                                            ?>
                                                                        </h3>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($incident_email['emails'])) { ?>
                                                                    <?php foreach ($incident_email['emails']as $key => $email) { ?>
                                                                        <tr>
                                                                            <td>
                                                                                <table class="incident-table">
                                                                                    <thead>
                                                                                        <tr class="bg-gray">
                                                                                            <th colspan="3">
                                                                                                <strong>
                                                                                                    <?php
                                                                                                        $email_type = '';

                                                                                                        if ($email['receiver_sid'] == $manager_sid) {
                                                                                                            $email_type = 'Received';
                                                                                                        } else {
                                                                                                            $email_type = 'Sent';
                                                                                                        }

                                                                                                        echo $email_type.' ( '.my_date_format($email['send_date']).' )';
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
                                                                                                        $receiver_sid = $email['receiver_sid'];
                                                                                                        if (!$receiver_sid == 0) {
                                                                                                            if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                                                                $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                                echo $receiver_name;
                                                                                                            } else {
                                                                                                                $receiver_info = db_get_employee_profile($receiver_sid);
                                                                                                                $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                echo $receiver_name;
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo $email['manual_email'];
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
                                                                                                        $sender_sid = $email['sender_sid'];
                                                                                                        if (!$sender_sid == 0) {
                                                                                                            if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                                $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                                echo $receiver_name;
                                                                                                            } else {
                                                                                                                $receiver_info = db_get_employee_profile($sender_sid);
                                                                                                                $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                echo $receiver_name;
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo $email['manual_email'];
                                                                                                        }
                                                                                                    ?>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td>
                                                                                                <label>
                                                                                                    <strong>Subject :</strong>
                                                                                                </label>
                                                                                                <span class="value-box bg-gray">
                                                                                                    <?php echo $email['subject']; ?>
                                                                                                </span>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3">
                                                                                                <?php echo $email['message_body']; ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Email Section End -->
        <?php } else if ($section == 'all') { ?>
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
                    <?php foreach ($questions as $key => $question) { ?>
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
                </tbody>
            </table>
            <!-- Question section End -->

            <!-- Social Video section Start -->
            <table class="incident-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="4">
                            <strong>Videos</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($videos)) { ?>
                        <?php foreach ($videos as $key => $video) { ?>
                            <tr>
                                <td>
                                    <label>
                                        <strong>Video Title :</strong>
                                    </label>
                                    <span class="value-box bg-gray">
                                        <?php
                                            echo $video['video_title'];
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <label>
                                        <strong>Video Type :</strong>
                                    </label>
                                    <span class="value-box bg-gray">
                                        <?php
                                            $video_type = $video['video_type'];
                                            echo ucfirst($video_type)
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <label>
                                        <strong>Uploaded By :</strong>
                                    </label>
                                    <span class="value-box bg-gray">
                                        <?php
                                            $uploaded_by = $video['uploaded_by'];
                                            $uploaded_by_info = db_get_employee_profile($uploaded_by);
                                            $user_name = $uploaded_by_info[0]['first_name'].' '.$uploaded_by_info[0]['last_name'];
                                            echo $user_name;
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <label>
                                        <strong>Uploaded Date :</strong>
                                    </label>
                                    <span class="value-box bg-gray">
                                        <?php echo my_date_format($video['uploaded_date']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <label>
                                        <strong>Video URL :</strong>
                                    </label>
                                    <span class="bg-gray">
                                        <?php
                                            if ($video['video_type'] == 'youtube') {
                                                echo "https://www.youtube.com/embed/".$video['video_url'];
                                            } else if ($video['video_type'] == 'vimeo') {
                                                echo "https://player.vimeo.com/video/".$video['video_url'];
                                            }
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td>
                                <div class="center-col">
                                    <h2>
                                        No Youtube and Vimeo Video Found
                                    </h2>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- Social Video section End -->

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
                    <tr>
                        <td>
                            <?php if (!empty($incident_all_emails)) { ?>
                                <?php foreach ($incident_all_emails as $incident_all_email) { ?>
                                    <?php
                                    $manager_sid            = $incident_all_email['manager_sid'];
                                    $manager_name           = $incident_all_email['manager_name'];
                                    $incident_emails        = $incident_all_email['incident_emails'];
                                    $incident_manual_emails = $incident_all_email['incident_manual_emails'];
                                    ?>

                                    <table class="incident-table">
                                        <thead>
                                            <tr>
                                                <th class="bg-black">
                                                    <h2>
                                                        <?php echo $manager_name . ' ( Emails )'; ?>
                                                    </h2>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <?php foreach ($incident_emails as $key => $incident_email) { ?>
                                                        <table class="incident-table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="bg-black">
                                                                        <h3>
                                                                            <?php echo $incident_email['section_name']; ?>
                                                                        </h3>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($incident_email['emails'])) { ?>
                                                                    <?php foreach ($incident_email['emails']as $key => $email) { ?>
                                                                        <tr>
                                                                            <td>
                                                                                <table class="incident-table">
                                                                                    <thead>
                                                                                        <tr class="bg-gray">
                                                                                            <th colspan="3">
                                                                                                <strong>
                                                                                                    <?php
                                                                                                        $email_type = '';

                                                                                                        if ($email['receiver_sid'] == $manager_sid) {
                                                                                                            $email_type = 'Received';
                                                                                                        } else {
                                                                                                            $email_type = 'Sent';
                                                                                                        }

                                                                                                        echo $email_type.' ( '.my_date_format($email['send_date']).' )';
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
                                                                                                        $receiver_sid = $email['receiver_sid'];
                                                                                                        if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                                                            $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                                                            $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                            echo $receiver_name;
                                                                                                        } else {
                                                                                                            if ($incident_type == 'anonymous' && $receiver_sid == $incident_reporter_sid) {
                                                                                                                echo 'Anonymous';
                                                                                                            } else {
                                                                                                                $receiver_info = db_get_employee_profile($receiver_sid);
                                                                                                                $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                echo $receiver_name;
                                                                                                            }
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
                                                                                                        $sender_sid = $email['sender_sid'];
                                                                                                        if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                            $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                            $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                            echo $receiver_name;
                                                                                                        } else {
                                                                                                            if ($incident_type == 'anonymous' && $sender_sid == $incident_reporter_sid) {
                                                                                                                echo 'Anonymous';
                                                                                                            } else {
                                                                                                                $receiver_info = db_get_employee_profile($sender_sid);
                                                                                                                $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                echo $receiver_name;
                                                                                                            }
                                                                                                        }
                                                                                                    ?>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td>
                                                                                                <label>
                                                                                                    <strong>Subject :</strong>
                                                                                                </label>
                                                                                                <span class="value-box bg-gray">
                                                                                                    <?php echo $email['subject']; ?>
                                                                                                </span>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3">
                                                                                                <?php echo $email['message_body']; ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } ?>

                                                    <?php if (!empty($incident_manual_emails)) { ?>
                                                        <?php foreach ($incident_manual_emails as $key => $incident_email) { ?>
                                                            <table class="incident-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="bg-black">
                                                                            <h3>
                                                                                <?php
                                                                                    $user_1 = $incident_email['name'];
                                                                                    $user_1_name = explode("(",$user_1);
                                                                                    $user_one_name = $user_1_name[0];
                                                                                    $user_2 = $incident_email['user_one_email'];
                                                                                    $user_2_name = explode("@",$user_2);
                                                                                    $user_two_name = $user_2_name[0];

                                                                                    echo $manager_name.' & '.$user_two_name;
                                                                                ?>
                                                                            </h3>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (!empty($incident_email['emails'])) { ?>
                                                                        <?php foreach ($incident_email['emails']as $key => $email) { ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <table class="incident-table">
                                                                                        <thead>
                                                                                            <tr class="bg-gray">
                                                                                                <th colspan="3">
                                                                                                    <strong>
                                                                                                        <?php
                                                                                                            $email_type = '';

                                                                                                            if ($email['receiver_sid'] == $manager_sid) {
                                                                                                                $email_type = 'Received';
                                                                                                            } else {
                                                                                                                $email_type = 'Sent';
                                                                                                            }

                                                                                                            echo $email_type.' ( '.my_date_format($email['send_date']).' )';
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
                                                                                                            $receiver_sid = $email['receiver_sid'];
                                                                                                            if (!$receiver_sid == 0) {
                                                                                                                if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                                                                    $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                                                                    $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                                    echo $receiver_name;
                                                                                                                } else {
                                                                                                                    $receiver_info = db_get_employee_profile($receiver_sid);
                                                                                                                    $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                    echo $receiver_name;
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo $email['manual_email'];
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
                                                                                                            $sender_sid = $email['sender_sid'];
                                                                                                            if (!$sender_sid == 0) {
                                                                                                                if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                                    $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                                    $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                                    echo $receiver_name;
                                                                                                                } else {
                                                                                                                    $receiver_info = db_get_employee_profile($sender_sid);
                                                                                                                    $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                    echo $receiver_name;
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo $email['manual_email'];
                                                                                                            }
                                                                                                        ?>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <label>
                                                                                                        <strong>Subject :</strong>
                                                                                                    </label>
                                                                                                    <span class="value-box bg-gray">
                                                                                                        <?php echo $email['subject']; ?>
                                                                                                    </span>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td colspan="3">
                                                                                                    <?php echo $email['message_body']; ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            <?php } else { ?>
                                <table class="incident-table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="center-col">
                                                    <h2>
                                                        No Emails Found
                                                    </h2>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Email Section End -->

            <!-- Comment Section Start -->
            <table class="incident-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="2">
                            <strong>Notes</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($comments)) { ?>
                        <?php foreach ($comments as $key => $comment) { ?>
                            <?php
                                $comment_type = !empty($comment['employer_sid']) ? 'Reply' : 'Response';
                                // $comment_type = !empty($comment['employer_sid']) ? $comment['employer_sid'] : $comment['applicant_sid'];
                                $user_sid = !empty($comment['employer_sid']) ? $comment['employer_sid'] : $comment['applicant_sid'];
                                $user_info = db_get_employee_profile($user_sid);
                                if ($incident_type == 'anonymous') {
                                    if (!empty($comment['employer_sid'])) {
                                        $name = 'Anonymous';
                                    } else {
                                        $name = $user_info[0]['first_name'].' '.$user_info[0]['last_name'];
                                    }

                                } else {
                                    $name = $user_info[0]['first_name'].' '.$user_info[0]['last_name'];
                                }

                            ?>
                            <tr>
                                <td>
                                    <table class="incident-table">
                                        <thead>
                                            <tr class="bg-gray">
                                                <th colspan="3">
                                                    <strong>
                                                        <?php
                                                            echo $comment_type;
                                                        ?>
                                                    </strong>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <strong>Name :</strong>

                                                    </label>
                                                    <span>
                                                        <?php echo $name; ?>
                                                    </span>

                                                </td>
                                                <td>
                                                    <label>
                                                        <strong>Date :</strong>
                                                    </label>
                                                    <span>
                                                        <time><?php echo my_date_format($comment['date_time']); ?></time>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <label>
                                                        <strong>note :</strong>
                                                    </label>
                                                    <span class="value-box bg-gray">
                                                        <?php echo strip_tags($comment['comment']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td>
                                <div class="center-col">
                                    <h2>No Notes Found Against This Incident</h2>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- Comment Section End -->
        <?php } else { ?>
            <article class="sheet-header">
                <div class="center-col">
                    <h2>Records Not Founds</h2>
                </div>
            </article>
        <?php } ?>
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
