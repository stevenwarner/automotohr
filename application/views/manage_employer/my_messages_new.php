<?php if (!$load_view) { ?>
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
                            <span class="page-heading down-arrow">Private Messages (<?= $page ?>)</span>
                        </div>
                        <div class="panel-group panel-group-green full-width" id="accordion_" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-success">
                                <div class="panel-heading" role="tab" id="searchArea">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#search_area" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="more-less glyphicon <?php echo $glyphicon_class; ?>"></i>
                                            <!--plus or minus -->
                                            <strong>Click To Search Messages <?php //echo $glyphicon_class; 
                                                                                ?></strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="search_area" class="panel-collapse collapse <?php echo $collapse_class; ?>" role="tabpanel" aria-labelledby="searchArea">
                                    <!--in class to keep it open by default -->
                                    <div class="panel-body">
                                        <div class="form-wrp">
                                            <form method="GET" action="">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group autoheight">
                                                            <label>From Name</label>
                                                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group autoheight">
                                                            <label>Email</label>
                                                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group autoheight">
                                                            <label>Subject</label>
                                                            <input type="text" name="subject" class="form-control" value="<?php echo $subject; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <div class="form-group autoheight">
                                                                    <label>Date From</label>
                                                                    <input id="start_date_applied" type="text" name="start_date" class="form-control" value="<?php echo $start_date; ?>" readonly />
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <div class="form-group autoheight">
                                                                    <label>Date To</label>
                                                                    <input id="end_date_applied" type="text" name="end_date" class="form-control" value="<?php echo $end_date; ?>" readonly />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="full-width text-right">
                                                            <input type="submit" name="filter" value="Search" class="btn btn-success" />
                                                            <a href="<?php echo base_url('private_messages'); ?>" class="btn btn-success">Clear</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="message-action">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="hr-items-count">
                                        <strong class="messagesCounter"><?php echo $total; ?></strong> Messages
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                    <div class="message-action-btn">
                                        <a class="submit-btn" href="<?= base_url('private_messages') ?>">Inbox <?php if ($total_messages > 0) { ?><span>(<?= $total_messages ?>)</span><?php } ?></a>
                                        <a class="submit-btn" href="<?= base_url('outbox') ?>">Outbox</a>
                                        <a class="submit-btn" href="<?= base_url('compose_message') ?>">Compose new Message </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($messages) { ?>
                            <div class="table-responsive table-outer">
                                <div class="table-wrp">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <?php if ($page == 'inbox') { ?>
                                                        From
                                                    <?php                       } else { ?>
                                                        To
                                                    <?php                       } ?>
                                                </th>
                                                <th>Subject</th>
                                                <th>Received on</th>
                                                <th class="message-btn" colspan="3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($messages as $message) {; ?>
                                                <tr <?php if ($page == 'inbox' && $message['status'] == 0) { ?>class="unread" <?php } ?> id="parent_<?= $message['msg_id'] ?>">
                                                    <td><?php if ($page == 'inbox') {
                                                            echo $message['username'] . '<br>' . $message['email'];
                                                        } else {
                                                            echo $message['to_id'] . '<br>' . $message['email'];
                                                        } ?>
                                                    </td>
                                                    <td width="35%"><?php echo $message['subject']; ?></td>
                                                    <td><?= reset_datetime(array('datetime' => $message['date'], '_this' => $this)); ?></td>
                                                    <td class="message-btn">
                                                        <?php if ($page == 'inbox') { ?>
                                                            <a href="<?= base_url('inbox_message_detail') ?>/<?= $message["msg_id"] ?>" class="action-btn manage-btn bg-btn">View Message</a>
                                                        <?php } else { ?>
                                                            <a href="<?= base_url('outbox_message_detail') ?>/<?= $message["msg_id"] ?>" class="action-btn manage-btn bg-btn">View Message</a>
                                                        <?php } ?>
                                                        <a class="action-btn remove" href="javascript:;" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);">
                                                            <i class="fa fa-remove"></i>
                                                            <span class="btn-tooltip">Delete</span>
                                                        </a>
                                                        <?php if ($page == 'inbox' && $message["status"] == 0) { ?>
                                                            <img src="<?= base_url() ?>assets/images/new_msg.gif">
                                                        <?php   } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div id="show_no_jobs" class="table-wrp">
                                <span class="applicant-not-found">No Message found! </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#start_date_applied').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function(value) { //console.log(value);
                    $('#end_date_applied').datepicker('option', 'minDate', value);
                }
            }).datepicker('option', 'maxDate', $('#end_date_applied').val());

            $('#end_date_applied').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function(value) { //console.log(value);
                    $('#start_date_applied').datepicker('option', 'maxDate', value);
                }
            }).datepicker('option', 'minDate', $('#start_date_applied').val());

            // Accordion with plus/minus icon
            function toggleIcon(e) {
                $(e.target)
                    .prev('.panel-heading')
                    .find(".more-less")
                    .toggleClass('glyphicon-plus glyphicon-minus');
            }
            $('.panel-group').on('hidden.bs.collapse', toggleIcon);
            $('.panel-group').on('shown.bs.collapse', toggleIcon);
        });

        function todo(action, id) {
            url = "<?= base_url() ?>private_messages/message_task";
            alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Message?",
                function() {
                    $.post(url, {
                            action: action,
                            sid: id
                        })
                        .done(function(data) {
                            messagesCount = parseInt($(".messagesCounter").html());
                            messagesCount--;
                            $(".messagesCounter").html(messagesCount);
                            alertify.success('Selected message have been ' + action + 'd.');
                            $("#parent_" + id).remove();
                        });
                },
                function() {
                    alertify.error('Canceled');
                });
        }
    </script>
<?php } else {
    $this->load->view('manage_employer/my_messages_ems');
} ?>