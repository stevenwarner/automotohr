<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="glyphicon glyphicon-envelope"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <?php $this->load->view('templates/_parts/admin_message_panel_header'); ?>
                                    <?php if ($page == 'outbox') { ?>
                                    <div class="hr-fields-wrap">
                                        <div class="invoice-field-wrap">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label class="control control--radio">Employees
                                                    <input type="radio" name="receiver" value="to_employees" id="to_employees" checked>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label class="control control--radio">Custom Email
                                                    <input type="radio" name="receiver" value="to_email" id="to_email">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="hr-promotions table-responsive">
                                        <form name="users_form" method="post">
                                            <div class="hr-box-header">
                                                <div class="hr-items-count">
                                                        <strong class="messagesCounter employees"><?php echo $total; ?></strong>
                                                        <strong class="messagesCounter custom_email" style="display: none"><?php echo sizeof($manual_outbox); ?></strong> Messages
                                                </div>
                                            </div>
                                        </form>
                                        <form method="post" class="private-msg">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <?php if ($page == 'inbox') { ?>
                                                                    <th>From</th>
                                                        <?php   } else { ?> 
                                                                    <th>To</th>
                                                        <?php   } ?>
                                                        <th>Subject</th>
                                                        <th>Date</th>
                                                        <th width="1%" class="actions" colspan="5">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="employees">
                                                    <?php foreach ($messages as $message) { ?>
                                                        <tr <?php if ($page == 'inbox' && $message["status"] == 0) { ?>class="unread"<?php } ?> id="parent_<?=$message["msg_id"] ?>">
                                                            <td><?=$message["username"] ?></td>
                                                            <td><?=$message["subject"] ?></td>
                                                            <td><?=date_with_time($message["date"]); ?></td>
                                                            <?php if ($page == 'inbox') { ?>
                                                                    <td>
                                                                        <?php if ($page == 'inbox' && $message["status"] == 0) { ?>
                                                                            <img src="<?= base_url() ?>assets/images/new_msg.gif">&nbsp;
                                                                        <?php   } ?>
                                                                        <a href="<?= base_url('manage_admin/inbox_message_detail') ?>/<?= $message["msg_id"] ?>" class="btn btn-success">View Message</a>
                                                                    </td>
                                                            <?php } else { ?>                                                      
                                                                    <td>
                                                                        <a href="<?= base_url('manage_admin/outbox_message_detail') ?>/<?= $message["msg_id"] ?>/<?= $message["anonym"] ?>" class="btn btn-success">View Message</a>
                                                                    </td>
                                                            <?php } ?>
                                                            <?php if(check_access_permissions_for_view($security_details, 'delete_private_message')){ ?>
                                                                <td>
                                                                    <input type="button" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);" class="hr-delete-btn" value="DELETE">
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <!--<td colspan="5"><input type="button" class="site-btn" value="MARK AS READ">-->
                                                    </tr>
                                                </tbody>
                                                <?php if ($page == 'outbox') { ?>
                                                    <tbody class="custom_email" style="display: none;">
                                                        <?php foreach ($manual_outbox as $message) { ?>
                                                            <tr>
                                                                <td><?=$message["username"] ?></td>
                                                                <td><?=$message["subject"] ?></td>
                                                                <td><?=date_with_time($message["date"]); ?></td>
                                                                <td>
                                                                    <a href="<?= base_url('manage_admin/outbox_message_detail') ?>/<?= $message["msg_id"] ?>/<?= $message["anonym"] ?>" class="btn btn-success">View Message</a>
                                                                </td>
                                                                <?php if(check_access_permissions_for_view($security_details, 'delete_private_message')){ ?>
                                                                    <td>
                                                                        <input type="button" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);" class="hr-delete-btn" value="DELETE">
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <!--<td colspan="5"><input type="button" class="site-btn" value="MARK AS READ">-->
                                                        </tr>
                                                    </tbody>
                                                <?php } ?>
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
                                messagesCount = parseInt($(".messagesCounter").html());
                                messagesCount--;
                                $(".messagesCounter").html(messagesCount);

                                alertify.success('Selected message have been ' + action + 'd.');
                                $("#parent_" + id).remove();
                            });

                },
                function () {
                    alertify.error('Canceled');
                });
    }
    $('input[name="receiver"]').change(function (e) {
        var div_to_show = $(this).val();
        display(div_to_show);
    });

    function display(div_to_show) {
        if (div_to_show == 'to_email') {
            $('.employees').hide();
            $('.custom_email').show();
        } else if (div_to_show == 'to_employees') {
            $('.custom_email').hide();
            $('.employees').show();
        }
    }
</script>