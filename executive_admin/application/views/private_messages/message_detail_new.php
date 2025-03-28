<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php $this->load->view('flashmessage/flash_message'); ?>

                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-envelope"></i><?php echo $title; ?> (<?= $page ?>)</h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Dashboard</a>
                </div>
                <div class="bt-panel">
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-xs-12 col-sm-5">
                            <div class="company-name pull-left">
                                Company Name: <strong><?php echo $company_name; ?></strong>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-xs-12 col-sm-7">
                            <a href="<?php echo base_url('private_messages') . '/' . $company_id; ?>" class="btn btn-success">Inbox <span>(<?= $total_messages ?>)</span></a>
                            <a href="<?php echo base_url('outbox') . '/' . $company_id; ?>" class="btn btn-success">Outbox</a>
                            <a href="<?php echo base_url('compose_message') . '/' . $company_id; ?>" class="btn btn-success">Compose new Message</a>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default full-width">
                    <div class="panel-heading"><strong>Message Detail</strong></div>
                    <div class="panel-body">
                        <div class="table-responsive full-width">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="col-lg-2 success"><b>Date</b></th>
                                        <td><?php echo $message["date"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="success"><b>From <?php if ($page == 'Inbox') {
                                                                        echo 'Name';
                                                                    } ?></b></th>
                                        <td><?php echo $contact_details["from_name"]; ?>&nbsp;

                                            <?php
                                            if (!empty($contact_details["from_profile_link"])) {
                                                if ($page == 'Inbox' && $contact_details['message_type'] == 'applicant') {
                                                    echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . (str_replace('executive_admin/', '', $contact_details["from_profile_link"])) . '" class="jsToCompany btn btn-info" target="_blank">View Profile</a>';
                                                } else if ($page == 'Inbox' && $contact_details['message_type'] == 'employee') {
                                                    echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . (str_replace('executive_admin/', '', $contact_details["from_profile_link"])) . '" class="jsToCompany btn btn-info" target="_blank">View Profile</a>';
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php if ($page == 'Inbox') { ?>
                                        <tr>
                                            <th class="success"><b>From Email</b></th>
                                            <td><?php echo $contact_details['from_email']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th class="success"><b>To <?php if ($page != 'Inbox') {
                                                                        echo 'Name';
                                                                    } ?></b></th>
                                        <td>
                                            <?php echo $contact_details["to_name"]; ?>&nbsp;
                                            <?php
                                            if (!empty($contact_details["to_profile_link"])) {
                                                if ($page != 'Inbox' && $contact_details['message_type'] == 'applicant') {
                                                    echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . (str_replace('executive_admin/', '', $contact_details["to_profile_link"])) . '" class="jsToCompany btn btn-info" target="_blank">View Profile</a>';
                                                } else if ($page != 'Inbox' && $contact_details['message_type'] == 'employee') {
                                                    echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . (str_replace('executive_admin/', '', $contact_details["to_profile_link"])) . '" class="jsToCompany btn btn-info" target="_blank">View Profile</a>';
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php if ($page != 'Inbox') { ?>
                                        <tr>
                                            <th class="success"><b>To Email</b></th>
                                            <td><?php echo $contact_details['to_email']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th class="success"><b>Subject</b></th>
                                        <td><?php echo $message['subject']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="success"><b>Message</b></th>
                                        <td><?php echo $message['message']; ?></td>
                                    </tr>
                                    <?php
                                    $attachments = $message["attachment"] ? explode(",", $message["attachment"]) : [];
                                    ?>
                                    <?php if ($attachments) { ?>
                                        <tr>
                                            <td><b>Attachment</b></td>
                                            <td>
                                                <?php foreach ($attachments as $attachment) { ?>
                                                    <a class="btn btn-primary" download="Attachment" href="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>">Download Attachment</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <?php if ($page == 'Inbox') { ?>
                        <a href="<?= base_url('reply_message') ?>/<?= $company_id ?>/<?= $message['msg_id'] ?>"><input type="button" class="btn btn-success" value="Reply"></a>
                    <?php } ?>
                    <input class="btn btn-danger" type="button" id="<?= $message['msg_id'] ?>" onclick="todo('delete', this.id);" value="Delete">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function todo(action, id) {
        url = "<?= base_url() ?>private_messages/message_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Message?",
            function() {
                $.post(url, {
                        action: action,
                        sid: id
                    })
                    .done(function(data) {
                        alertify.success('Selected message have been ' + action + 'd.');
                        $("#parent_" + id).remove();
                    });

            },
            function() {
                alertify.error('Canceled');
            });
    }
</script>


<script>
    $('.jsToCompany').click(function(event) {
        //
        event.preventDefault();
        //
        const toLink = $(this).prop('href');
        //
        companyLogin(
            <?= $company_id; ?>,
            <?= $employer_id; ?>,
            toLink
        );
    });

    function companyLogin(company_sid, logged_in_sid, toLink) {

        url_to = "<?= base_url() ?>dashboard/company_login";

        $.post(url_to, {
                action: "login",
                company_sid: company_sid,
                logged_in_sid: logged_in_sid
            })
            .done(function(data) {
                const responseData = JSON.parse(data);
                // logedin
                if (responseData.logedin == 1) {
                    window.open(toLink, '_blank');
                } else {
                    alert('Account Is De-Activated');
                }


            });
    }
</script>