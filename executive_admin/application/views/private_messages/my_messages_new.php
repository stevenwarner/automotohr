<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php $this->load->view('flashmessage/flash_message'); ?>
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-envelope"></i><?php echo $title; ?> (<?= $page ?>)</h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Dashboard</a>
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
                                <form method="GET" action="">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>From Name</label>
                                                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label>Subject</label>
                                                <input type="text" name="subject" class="form-control" value="<?php echo $subject; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Date From</label>
                                                        <input id="start_date_applied" type="text" name="start_date" class="form-control" value="<?php echo $start_date; ?>" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Date To</label>
                                                        <input id="end_date_applied" type="text" name="end_date" class="form-control" value="<?php echo $end_date; ?>" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="button-panel text-right">
                                                <input type="submit" name="filter" value="Search" class="btn btn-success" />
                                                <a href="<?php echo base_url('private_messages') . '/' . $company_id; ?>" class="btn btn-success">Clear</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bt-panel">
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-xs-12 col-sm-5">
                            <div class="company-name pull-left">
                                <!--                                Company Name: <strong><?php //echo $company_name; 
                                                                                            ?></strong>-->
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-xs-12 col-sm-7">
                            <a href="<?php echo base_url('private_messages') . '/' . $company_id; ?>" class="btn btn-success">Inbox <?php if ($total_messages > 0) { ?><span>(<?= $total_messages ?>)</span><?php } ?></a>
                            <a href="<?php echo base_url('outbox') . '/' . $company_id; ?>" class="btn btn-success">Outbox</a>
                            <a href="<?php echo base_url('compose_message') . '/' . $company_id; ?>" class="btn btn-success">Compose new Message</a>
                        </div>
                    </div>
                </div>
                <?php if ($messages) { ?>
                    <div class="panel panel-default full-width">
                        <div class="panel-heading"><strong><?php echo $company_name; ?> (<?= $page ?>)</strong></div>
                        <div class="panel-body">
                            <table class="table basic-table table-bordered">
                                <thead>
                                    <tr class="success">
                                        <th>
                                            <?php if ($page == 'inbox') { ?>
                                                From
                                            <?php } else { ?>
                                                To
                                            <?php } ?>
                                        </th>
                                        <th>Subject</th>
                                        <th>Received on</th>
                                        <th class="message-btn text-center col-lg-3" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message) { ?>
                                        <tr <?php if ($page == 'inbox' && $message['status'] == 0) { ?>class="unread" <?php } ?> id="parent_<?= $message['msg_id'] ?>">
                                            <td><?php if ($page == 'inbox') {
                                                    echo $message['username'] . '<br>' . $message['email'];
                                                } else {
                                                    echo $message['to_id'] . '<br>' . $message['email'];
                                                } ?>
                                            </td>
                                            <td><?php echo $message['subject']; ?></td>
                                            <td>
                                                <!--                                                --><?php //echo my_date_format($message['date']); 
                                                                                                        ?>
                                                <?php echo reset_datetime(array(
                                                    'datetime' => $message['date'],
                                                    // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                    // 'format' => 'h:iA', //
                                                    'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                    'from_timezone' => $executive_user['timezone'], //
                                                    '_this' => $this
                                                )) ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-msg-actions display-block">
                                                    <?php if ($page == 'inbox') { ?>
                                                        <a href="<?= base_url('inbox_message_detail') ?>/<?= $company_id ?>/<?= $message["msg_id"] ?>" class="btn btn-success btn-sm">View Message</a>
                                                    <?php   } else { ?>
                                                        <a href="<?= base_url('outbox_message_detail') ?>/<?= $company_id ?>/<?= $message["msg_id"] ?>" class="btn btn-success btn-sm">View Message</a>
                                                    <?php   } ?>
                                                    <a class="btn btn-danger btn-sm" href="javascript:;" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);">
                                                        <i class="fa fa-remove"></i>
                                                        <span class="btn-tooltip">Delete</span>
                                                    </a>
                                                    <?php if ($page == 'inbox' && $message['status'] == 0) { ?>
                                                        <img class="icon-msg-new" src="<?= base_url() ?>assets/images/new_msg.gif">
                                                    <?php   } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="panel panel-default full-width">
                        <div class="panel-heading"><strong>Message Listing</strong></div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <?php if ($page == 'inbox') { ?>
                                                From
                                            <?php } else { ?>
                                                To
                                            <?php } ?>
                                        </th>
                                        <th>Subject</th>
                                        <th>Received on</th>
                                        <th class="text-center col-lg-3" colspan="3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr <?php if ($page == 'inbox' && $message['status'] == 0) { ?>class="unread" <?php } ?> id="parent_<?= $message['msg_id'] ?>">
                                        <td colspan="7" class="text-center"><span style="color:red;">No Messages found!</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />
<script>
    $(document).ready(function() {
        //      $('#start_date_applied').datepicker();
        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) { //console.log(value);
                $('#end_date_applied').datepicker('option', 'minDate', value);
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        //      $('#end_date_applied').datepicker();
        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) { //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());

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