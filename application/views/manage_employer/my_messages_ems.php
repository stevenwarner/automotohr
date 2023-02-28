<style>
    .btn-succes-selected{
        color: #fff;
        background-color: #068206;
        pointer-events: none;
        
    }
    </style>

<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                        <?php if ($this->uri->segment(1) == 'private_messages') { ?>
                            <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                        <?php } else { ?>
                            <a href="<?php echo base_url('private_messages'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Back</a>
                        <?php } ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn  <?php echo ($this->uri->segment(1) == 'private_messages')? ' btn-succes-selected':' btn-success' ?> btn-block mb-2" href="<?= base_url('private_messages') ?>"><i class="fa fa-envelope-o"></i> Inbox <?php if ($total_messages > 0) { ?><span>(<?= $total_messages ?>)</span><?php } ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn <?php echo ($this->uri->segment(1) == 'outbox')? ' btn-succes-selected':' btn-success' ?> btn-block mb-2" href="<?= base_url('outbox') ?>"><i class="fa fa-inbox"></i> Outbox</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn <?php echo ($this->uri->segment(1) == 'compose_message')? ' btn-succes-selected':' btn-success' ?> btn-block mb-2" href="<?= base_url('compose_message') ?>"><i class="fa fa-pencil-square-o"></i> Compose new Message</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">Private Messages (<?= $page ?>)</h1>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="btn-panel">
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
                                                                <a href="<?php echo $page == 'inbox' ? base_url('private_messages') : base_url('outbox'); ?>" class="btn btn-black">Clear</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="dashboard-conetnt-wrp">
                            <?php if ($messages) { ?>
                                <div class="table-responsive table-outer">
                                    <div class="table-wrp data-table">
                                        <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-4">
                                                        <?php if ($page == 'inbox') { ?>
                                                            From
                                                        <?php } else { ?>
                                                            To
                                                        <?php } ?></th>
                                                    <th class="col-xs-4">Subject</th>
                                                    <th class="col-xs-2">Received on</th>
                                                    <th class="col-xs-2 text-center" colspan="2">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($messages as $message) { ?>
                                                    <tr>
                                                        <td><?php if ($page == 'inbox') {
                                                                echo $message['username'] . '<br>' . $message['email'];
                                                            } else {
                                                                echo $message['to_id'] . '<br>' . $message['email'];
                                                            } ?>
                                                        </td>
                                                        <td><?php echo $message['subject']; ?></td>
                                                        <td><?= reset_datetime(array('datetime' => $message['date'], '_this' => $this)); ?></td>
                                                        <td><?php if ($page == 'inbox') { ?>
                                                                <a href="<?= base_url('inbox_message_detail') ?>/<?= $message["msg_id"] ?>" class="btn btn-info btn-sm action-btn manage-btn bg-btn btn-block">
                                                                    <i class="fa fa-eye fa-fw" style="font-size: 12px;"></i>
                                                                    View
                                                                </a>
                                                            <?php } else { ?>
                                                                <a href="<?= base_url('outbox_message_detail') ?>/<?= $message["msg_id"] ?>" class="btn btn-info btn-sm action-btn manage-btn bg-btn btn-block">
                                                                    <i class="fa fa-eye fa-fw" style="font-size: 12px;"></i>
                                                                    View
                                                                </a>
                                                            <?php } ?>

                                                            <?php if ($page == 'inbox' && $message["status"] == 0) { ?>
                                                                <img src="<?= base_url() ?>assets/images/new_msg.gif">
                                                            <?php   } ?>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-danger btn-sm btn-block" href="javascript:;" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);">
                                                                <i class="fa fa-trash fa-fw" style="font-size: 12px;"></i>
                                                                <span class="btn-tooltip">Delete</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div id="show_no_jobs" class="table-wrp">
                                    <div class="table-responsive table-outer">
                                        <div class="table-wrp data-table">
                                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <?php if ($page == 'inbox') { ?>
                                                                From
                                                            <?php } else { ?>
                                                                To
                                                            <?php } ?></th>
                                                        <th>Subject</th>
                                                        <th>Received on</th>
                                                        <th colspan="3">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="4" class="text-center"><span style="color:red;">No Messages found!</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
            <?php //$this->load->view('manage_employer/employee_hub_right_menu'); 
            ?>
            <!-- </div> -->
        </div>
    </div>
</div>
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