<div class="main jsmaincontent">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                        <a class="btn btn-info btn-block csRadius5" href="<?php echo $backbtn; ?>"><i class="fa fa-arrow-left"></i> back</a>    
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn btn-success btn-block mb-2" href="<?=base_url('private_messages')?>"><i class="fa fa-envelope-o"></i> Inbox <?php if($total_messages>0 ) { ?><span>(<?= $total_messages ?>)</span><?php } ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn btn-success btn-block mb-2" href="<?=base_url('outbox') ?>"><i class="fa fa-inbox"></i> Outbox</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn btn-success btn-block mb-2" href="<?=base_url('compose_message')?>"><i class="fa fa-pencil-square-o"></i> Compose new Message </a>
                    </div>
                </div>    
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">Private Messages (<?= $page ?>)</h1>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="dashboard-conetnt-wrp">
                            <div class="table-responsive table-outer">
                            <form method="post">
                                <div class="table-wrp data-table">
                                    <table class="table table-bordered table-hover table-stripped">
                                        <tbody>
                                        <tr>
                                            <td><b>Date</b></td>
                                            <td><?=reset_datetime(array( 'datetime' => $message['date'], '_this' => $this)); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>From <?php if ($page == 'Inbox') { echo 'Name'; } ?></b></td>
                                            <td><?php echo $contact_details["from_name"]; ?>&nbsp;
                                                <?php if ($page == 'Inbox' && $contact_details['message_type'] == 'applicant') { echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $contact_details['from_profile_link'] . '" target="_blank">View Profile</a>'; } ?>
                                            </td>
                                        </tr>
                                        <?php if ($page == 'Inbox') { ?>
                                        <tr>
                                            <td><b>From Email</b></td>
                                            <td><?php echo $contact_details["from_email"]; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><b>To <?php if ($page != 'Inbox') { echo 'Name'; } ?></b></td>
                                            <td><?php echo $contact_details["to_name"]; ?>&nbsp;
                                                <?php if ($page != 'Inbox' && $contact_details['message_type'] == 'applicant') { echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $contact_details['to_profile_link'] . '" target="_blank">View Profile</a>'; } ?>
                                            </td>
                                        </tr>
                                        <?php if ($page != 'Inbox') { ?>
                                        <tr>
                                            <td><b>To Email</b></td>
                                            <td><?php echo $contact_details["to_email"]; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><b>Subject</b></td>
                                            <td><?php echo $message["subject"]; ?></td>
                                        </tr>
                                        <tr>
                                             <td><b>Message</b></td>
                                            <td><?php echo $message["message"]; ?></td>
                                        </tr>
                                        <?php if(isset($message['attachment']) && $message['attachment'] != NULL && !empty($message['attachment'])){?>
                                            <tr>
                                                <td><b>Attachment</b></td>
                                                <td>
                                                    <a class="btn btn-primary" download="Attachment" href="<?php echo AWS_S3_BUCKET_URL . $message['attachment']?>">Download Attachment</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="2">
                                                <div class="message-action-btn">
                                                    <?php if ($page == 'Inbox') { ?>
                                                        <a href="<?= base_url('reply_message') ?>/<?=$message["msg_id"]?>"><input type="button" class="btn btn-success" value="Reply"></a>
                                                    <?php } ?>
                                                    <input class="btn btn-danger" type="button" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);" value="DELETE"> 
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>

<script>
    function todo(action, id) {
        url = "<?= base_url() ?>private_messages/message_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Message?",
                function () {
                    $.post(url, {action: action, sid: id})
                            .done(function (data) {
                                alertify.success('Selected message have been ' + action + 'd.');
                                $("#parent_" + id).remove();
                            });

                },
                function () {
                    alertify.error('Canceled');
                });
    }
</script>