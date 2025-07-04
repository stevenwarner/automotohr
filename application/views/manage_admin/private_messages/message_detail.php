<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="glyphicon glyphicon-envelope"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <?php $this->load->view('templates/_parts/admin_message_panel_header'); ?>
                                    <div class="hr-promotions table-responsive">
                                        <form method="post">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td width="20%"><b>Message<?php if ($page == 'inbox') { ?> From <?php } else { ?> To <?php } ?></b></td>
                                                        <td><?= $message["username"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Date</b></td>
                                                        <td><?= date_with_time($message["date"]) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Subject</b></td>
                                                        <td><?= $message["subject"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <?= $message["message"] ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <p class="msg_setting">
                                                                <?php if ($page == 'inbox') { ?>
                                                                    <?php if(in_array('full_access', $security_details) || in_array('reply_message', $security_details)){ ?>
                                                                        <a href="<?= base_url('manage_admin/reply_message') ?>/<?= $message["from_id"] ?>"><input type="button" class="site-btn" value="Reply"></a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <?php if(in_array('full_access', $security_details) || in_array('delete_private_message', $security_details)){ ?>    
                                                                    <input type="button" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);" class="hr-delete-btn" value="DELETE">
                                                                <?php } ?>
                                                            </p>
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
        </div>
    </div>
</div>
<script>
    function todo(action, id) {
        url = "<?= base_url() ?>manage_admin/private_messages/message_task";
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