<?php if(!$load_view){ ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Private Messages (<?= $page ?>)</span>
                    </div>
                    <div class="message-action">
                        <div class="row">                         
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="message-action-btn">
                                    <a class="submit-btn" href="<?=base_url('private_messages')?>">Inbox <?php if($total_messages>0 ) { ?><span>(<?= $total_messages ?>)</span><?php } ?></a>
                                    <a class="submit-btn" href="<?=base_url('outbox') ?>">Outbox</a>
                                    <a class="submit-btn" href="<?=base_url('compose_message')?>">Compose new Message </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="compose-message">
                        <div class="hr-promotions table-responsive">
                            <form method="post">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td><b>Date</b></td>
                                            <td><?=reset_datetime(array( 'datetime' => $message['date'], '_this' => $this)); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>From <?php if ($page == 'Inbox') { echo 'Name'; } ?></b></td>
                                            <td><?php echo $contact_details['from_name']; ?>&nbsp;
                                                <?php if ($page == 'Inbox' && $contact_details['message_type'] == 'applicant') { echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $contact_details['from_profile_link'] . '" target="_blank">View Profile</a>'; } ?>
                                                <?php if ($page == 'Inbox' && $contact_details['message_type'] == 'employee' && $employee['access_level_plus'] == 1) { echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $contact_details['from_profile_link'] . '" target="_blank">View Profile</a>'; } ?>

                                            </td>
                                        </tr>
                                        <?php if ($page == 'Inbox') { ?>
                                        <tr>
                                            <td><b>From Email</b></td>
                                            <td><?php echo $contact_details['from_email']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><b>To <?php if ($page != 'Inbox') { echo 'Name'; } ?></b></td>
                                            <td><?php echo $contact_details['to_name']; ?>&nbsp;
                                            <?php if (!empty($contact_details['from_profile_link'])) { ?>
                                                <?php if ($page != 'Inbox' && $contact_details['message_type'] == 'applicant') { echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $contact_details['to_profile_link'] . '" target="_blank">View Profile</a>'; } ?>
                                                <?php if ($page != 'Inbox' && $contact_details['message_type'] == 'employee' && $employee['access_level_plus'] == 1) { echo '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $contact_details['to_profile_link'] . '" target="_blank">View Profile</a>'; } ?>
                                            <?php } ?> 
                                            </td>
                                        </tr>
                                        <?php if ($page != 'Inbox') { ?>
                                        <tr>
                                            <td><b>To Email</b></td>
                                            <td><?php echo $contact_details['to_email']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><b>Subject</b></td>
                                            <td><?php echo $message['subject']; ?></td>
                                        </tr>
                                        <tr>
                                             <td><b>Message</b></td>
                                            <td><?php echo $message['message']; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="message-action-btn">
                                                    <?php if ($page == 'Inbox') { ?>
                                                        <a href="<?= base_url('reply_message') ?>/<?=$message["msg_id"]?>"><input type="button" class="submit-btn" value="Reply"></a>
                                                    <?php } ?>
                                                    <input class="submit-btn btn-del" type="button" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);" value="DELETE"> 
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>          
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
<?php } else{
    $this->load->view('manage_employer/message_detail_ems');
}?>