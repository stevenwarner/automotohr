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
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="glyphicon glyphicon-envelope"></i>Enquiry Message Details</h1>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form method="post">
                                                    <table class="table table-bordered table-striped">
                                                        <tbody>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Contact Status</b></th>
                                                                <td>
                                                                    <div class="candidate-status applicat-status-edit">
                                                                        <div class="label-wrapper-outer">
                                <?php
                                                                            if ($status_name != 'No Status Found!') {
                                                                                foreach ($application_status as $status_code) { ?>
                                                                                        <?php if ($status_code['css_class'] == $status) { ?>
                                                                                        <div class="selected <?php echo $status ?>">
                                                                                        <?= $status_name ?>
                                                                                        </div>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                            } else { ?>
                                                                                <div class="selected not_contacted">
                                                                                <?= $status_name ?>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <div class="show-status-box" title="Edit Contact Status">
                                                                                <i class="fa fa-pencil"></i>
                                                                            </div>
                                                                            <div class="lable-wrapper">
                                                                                <div id="id" style="display:none;"><?= $employer_job["sid"] ?></div>
                                                                                <div style="height:20px;">
                                                                                    <i class="fa fa-times cross"></i>
                                                                                </div>

                                <?php                                           foreach ($application_status as $status) { ?>
                                                                                    <div data-status_sid="<?php echo $status['sid']; ?>" data-status_class="<?php echo $status['css_class']; ?>" data-status_name="<?php echo $status['css_class']; ?>" class="label applicant <?php echo $status['css_class']; ?>">
                                                                                        <div id="status"><?php echo $status['name']; ?></div>
                                                                                        <i class="fa fa-check-square check"></i>
                                                                                    </div>
                                <?php                                           } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>From Name</b></th>
                                                                <td><?php echo $message["first_name"]; ?> <?php echo $message["last_name"]; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Company Name</b></th>
                                                                <td><?php echo $message["company_name"]; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Contact No</b></th>
                                                                <td><?=phonenumber_format($message["phone_number"]);?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Company Email</b></th>
                                                                <td><?php echo $message["email"]; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Date Requested</b></th>
                                                                <td><?php echo date_with_time($message["date_requested"], true) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Schedule Requested Date</b></th>
                                                                <td>
                            <?php                                   if ($message["schedule_date"] != NULL) {
                                                                        echo date_with_time($message["schedule_date"], true);
                                                                    } else { ?>
                                                                        NULL
                            <?php                                   } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Company Size</b></th>
                                                                <td><?php echo $message["company_size"]; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Subscribed to Newsletter</b></th>
                                                                <td><?php echo ($message["newsletter_subscribe"]==1) ? 'Yes': 'No'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Title</b></th>
                                                                <td><?php echo $message["job_role"]; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>State, Country</b></th>
                                                                <td><?php echo $message["state"]; ?>, <?php echo $message["country"]; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Hear About US Source</b></th>
                                                                <td><?php echo!empty($message["client_source"]) ? $message["client_source"] : 'N/A'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Message</b></th>
                                                                <td><?php echo!empty($message["client_message"]) ? html_entity_decode($message["client_message"]) : 'N/A'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>IP Address</b></th>
                                                                <td><?php echo $message["ip_address"] != NULL ? $message["ip_address"] : 'N/A'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>Timezone</b></th>
                                                                <td><?php echo $message["timezone"] != NULL ? get_timezones($message["timezone"], 'name') : 'N/A'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3"><b>User Agent</b></th>
                                                                <td><?php echo $message["user_agent"] != NULL ? $message["user_agent"] : 'N/A'; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-2">
                                                    <?php if ($this->uri->segment(2) == 'enquiry_message_details') { ?>
                                                        <a class="btn btn-success btn-block" href="<?php echo site_url('manage_admin/free_demo'); ?>">Back</a>
                                                    <?php } else if ($this->uri->segment(2) == 'referred_clients') { ?>
                                                       <a class="btn btn-success btn-block" href="<?php echo site_url('manage_admin/referred_clients'); ?>">Back</a>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-xs-2">
                                                    <?php if ($this->uri->segment(2) == 'enquiry_message_details') { ?>
                                                        <a class="btn btn-success btn-block" href="<?php echo site_url('manage_admin/demo_admin_reply/' . $message["sid"]); ?>">Send Reply</a>
                                                    <?php } else if ($this->uri->segment(2) == 'referred_clients') { ?>
                                                       <a class="btn btn-success btn-block" href="<?php echo site_url('manage_admin/referred_clients/demo_admin_reply/' . $message["sid"]); ?>">Send Reply</a>
                                                    <?php } ?>
                                                </div>
                                                <?php if ($message["is_reffered"] == 0) { ?>
                                                <div class="col-xs-3">
                                                    <form id="form_delete_demo_request" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_demo_request" />
                                                        <input type="hidden" name="user_sid" value="<?php echo $message["sid"]; ?>" />
                                                        <button type="button" onclick="func_delete_demo_request();" class="btn btn-danger btn-block">Delete Demo Request</button>
                                                    </form>
                                                </div>
                                                <?php } ?>
                                                <div class="col-xs-2">
                                                    <?php if ($this->uri->segment(2) == 'enquiry_message_details') { ?>
                                                        <a class="btn btn-primary btn-block" href="<?php echo site_url('manage_admin/edit_demo_request/' . $message["sid"]); ?>">Edit Details</a>
                                                    <?php } else if ($this->uri->segment(2) == 'referred_clients') { ?>
                                                        <a class="btn btn-primary btn-block" href="<?php echo site_url('manage_admin/referred_clients/edit_demo_request/' . $message["sid"]); ?>">Edit Details</a>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-xs-5"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <strong>Demo Reply:</strong>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <form method="post" class="private-msg">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Replied On</th>
                                                                    <th class="text-center">Subject</th>
                                                                    <th class="text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                        <?php                                   if (sizeof($demo_reply) > 0) {
                                                                foreach ($demo_reply as $reply) { ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo!empty($reply["reply_date"]) && $reply["reply_date"] != '0000-00-00 00:00:00' ? my_date_format($reply["reply_date"]) : 'N/A'; ?></td>
                                                                            <td class="text-center"><?php echo $reply["subject"]; ?></td>
                                                                            
                                                                            <?php if ($this->uri->segment(2) == 'enquiry_message_details') { ?>
                                                                                <td class="text-center"><a href="<?php echo base_url('manage_admin/free_demo/view_demo_email_reply'); ?>/<?= $reply["sid"] ?>"><input type="button" class="site-btn" value="View Message"></a></td>
                                                                            <?php } else if ($this->uri->segment(2) == 'referred_clients') { ?>
                                                                                <td class="text-center"><a href="<?php echo base_url('manage_admin/referred_clients/view_demo_email_reply'); ?>/<?= $reply["sid"] ?>"><input type="button" class="site-btn" value="View Message"></a></td>
                                                                            <?php } ?>
                                                                        </tr>
                                                                    <?php }
                                                                } else { ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="3">Not Replied Yet!</td>
                                                                    </tr>
                        <?php                                   } ?>
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <strong>Saved Notes:</strong>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <?php                       if (!empty($notes)) { ?>
                                                        <ul class="list-group">
                        <?php                               foreach ($notes as $key => $note) { ?>
                                                                <li class="list-group-item">
                                                                    <div class="row">
                                                                        <div class="col-xs-10">
                                                                            <p><strong><?php echo $note['first_name'] . ' ' . $note['last_name']; ?></strong>&nbsp;<small>( <?php echo date_with_time($note['created_date'], true); ?> )</small></p>
                                                                            <div id="<?php echo 'note_' . $note['sid']; ?>"><?php echo $note['note_text']; ?></div>
                                                                        </div>
                                                                        <div class="col-xs-1">
                                                                            <a href="javascript:void(0);" id="edit-note" onclick="fLaunchModal(this);" data-id="<?php echo $note['sid']; ?>" data-attr="<?php echo '#note_' . $note['sid']; ?>" class="btn btn-success btn-block btn-sm"><i class="fa fa-pencil"></i></a>
                                                                        </div>
                                                                        <div class="col-xs-1">
                                                                            <form id="form_delete_note_<?php echo $note['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_note" />
                                                                                <input type="hidden" id="note_sid" name="note_sid" value="<?php echo $note['sid']; ?>" />
                                                                                <input type="hidden" id="demo_sid" name="demo_sid" value="<?php echo $note['demo_sid']; ?>" />

                                                                                <button onclick="func_delete_note(<?php echo $note['sid']; ?>);" type="button" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash"></i></button>
                                                                            </form>
                                                                        </div>
                                                                    </div>

                                                                </li>
                                                        <?php } ?>
                                                        </ul>
                        <?php                   } else { ?>
                                                        <div class="text-center">
                                                            <span class="no-data">No Notes</span>
                                                        </div>
                        <?php                   } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <form id="form_add_new_note" method="post" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="add_new_note" />
                                                        <div class="field-row field-row-autoheight">
                                                            <label>Add New Note</label>
                                                            <textarea data-rule-required="true" id="note_text" name="note_text" cols="40" rows="10" class="hr-form-fileds field-row-autoheight enquiry-ckedit"></textarea>
                                                        </div>
                                                        <div class="">
                                                            <button type="submit" class="btn btn-success">Save Note</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr />

                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <strong>Scheduled Reminders</strong>
                                        </div>
                                        <div class="hr-innerpadding">
                            <?php           if (!empty($scheduled_tasks)) { ?>
                                                <ul class="list-group">
                            <?php                   foreach ($scheduled_tasks as $task) { ?>
                                                        <li class="list-group-item">
                                                            <div class="row">
                            <?php                           if ($task['schedule_type'] == 'call') { ?>
                                                                    <div class="col-xs-1 text-center">
                                                                        <h5><i style="font-size: 40px;" class="fa fa-phone"></i></h5>
                                                                    </div>
                            <?php                           } else if ($task['schedule_type'] == 'email') { ?>
                                                                    <div class="col-xs-1 text-center">
                                                                        <h5><i style="font-size: 40px;" class="fa fa-envelope"></i></h5>
                                                                    </div>
                            <?php                           } else if ($task['schedule_type'] == 'meeting') { ?>
                                                                    <div class="col-xs-1 text-center">
                                                                        <h5><i style="font-size: 40px;" class="fa fa-group"></i></h5>
                                                                    </div>
                            <?php                           } else if ($task['schedule_type'] == 'demo') { ?>
                                                                    <div class="col-xs-1 text-center">
                                                                        <h5><i style="font-size: 40px;" class="fa fa-laptop"></i></h5>
                                                                    </div>
                            <?php                           } ?>
                                                                <div class="col-xs-9">
                                                                    <h5><strong><?php echo ucwords($task['schedule_type']); ?> ( <?php echo date('m/d/Y @ h:i A', strtotime($task['schedule_datetime'])); ?> )</strong></h5>
                                                                    <p><?php echo $task['schedule_description']; ?></p>
                                                                    <p class="<?php echo ($task['schedule_status'] == 'pending' ? 'text-danger' : 'text-success'); ?>">( <?php echo ucwords($task['schedule_status']); ?> )</p>
                                                                </div>
                                                                <div class="col-xs-2">
                            <?php                           if ($task['schedule_status'] == 'pending') { ?>
                                                                    <span style="margin-left: 5px;" class="pull-right">
                                                                        <form id="form_complete_schedule_record_<?php echo $task['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="complete_schedule_record" />
                                                                            <input type="hidden" id="schedule_sid" name="schedule_sid" value="<?php echo $task['sid']; ?>" />
                                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $task['user_sid']; ?>" />
                                                                            <button type="button" onclick="func_complete_schedule_record(<?php echo $task['sid']; ?>);" class="btn btn-success btn-sm"><i class="fa fa-check-square"></i></button>
                                                                        </form>
                                                                    </span>
                            <?php                           } else { ?>
                                                                    <span style="margin-left: 5px;" class="pull-right">
                                                                        <button type="button" onclick="javascript:void(0);" class="btn btn-success btn-sm disabled"><i class="fa fa-check-square"></i></button>
                                                                    </span>
                            <?php                           } ?>
                                                                    &nbsp;
                                                                    <span style="margin-left: 5px;" class="pull-right">
                                                                        <form id="form_delete_schedule_record_<?php echo $task['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_schedule_record" />
                                                                            <input type="hidden" id="schedule_sid" name="schedule_sid" value="<?php echo $task['sid']; ?>" />
                                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $task['user_sid']; ?>" />
                                                                            <button type="button" onclick="func_delete_schedule_record(<?php echo $task['sid']; ?>);" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                        </form>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </li>
                            <?php                   } ?>
                                                </ul>
                            <?php           } else { ?>
                            <?php   } ?>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <strong>New Schedule</strong>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="add-new-company">
                                                <form id="form_add_new_scheduled_task" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                    <input type="hidden" id="perform_action" name="perform_action" value="schedule_a_task" />
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label for="country">Schedule Type <span class="hr-required">*</span></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select data-rule-required="true" class="invoice-fields" id="schedule_type" name="schedule_type" >
                                                                        <option value="">Please Select</option>
                                                                        <option value="call">Call</option>
                                                                        <option value="demo">Demo</option>
                                                                        <option value="email">Email</option>
                                                                        <option value="meeting">Meeting</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label  for="schedule_datetime">Date and Time <span class="hr-required">*</span></label>
                                                                <input data-rule-required="true" name="schedule_datetime" id="schedule_datetime" value="<?php echo date('m/d/Y H:00'); ?>" class="hr-form-fileds" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="field-row field-row-autoheight">
                                                                <label for="schedule_description">Description</label>
                                                                <textarea id="schedule_description" name="schedule_description" cols="40" rows="6" class="hr-form-fileds field-row-autoheight enquiry-ckedit"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <button type="submit" class="btn btn-success">Create</button>
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#form_add_new_note').validate();
        $('#form_add_new_scheduled_task').validate();
        $('#schedule_datetime').datetimepicker({
            format: 'm/d/Y H:i',
            minDate: new Date()
        });
        
        CKEDITOR.replace('schedule_description');
        CKEDITOR.replace('note_text');
    });

    function fLaunchModal(source) {
        var element_id = $(source).attr('data-attr');
        var get_element = $(element_id).html();
        var modal_content = '<label>Edit Notes</label><form id="form_edit_note" method="post" action="<?php echo current_url(); ?>"><input type="hidden"name="perform_action" value="edit_new_note"/><input type="hidden" name="note_sid" value="' + $(source).attr('data-id') + '"/><textarea id="edit-modal" name="note_text" cols="40" rows="10" class="hr-form-fileds field-row-autoheight edit-modal">' + get_element + '</textarea><div class="btn-enquiry-note"><button type="submit" class="btn btn-success">Save Note</button></div></form>';
        var footer_content = '';
        
        $('#document_modal_body').html(modal_content);
//        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html('Edit Enquiry Notes');
        $('#file_preview_modal').modal("toggle");
        CKEDITOR.replace('edit-modal');

    }

    function func_complete_schedule_record(schedule_sid) {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to Mark Schedule Task as Completed?',
                function () {
                    $('#form_complete_schedule_record_' + schedule_sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    function func_delete_schedule_record(schedule_sid) {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete Schedule request?',
                function () {
                    $('#form_delete_schedule_record_' + schedule_sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    function func_delete_demo_request() {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this demo request?',
                function () {
                    $('#form_delete_demo_request').submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    function func_delete_note(note_sid) {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this note?',
                function () {
                    $('#form_delete_note_' + note_sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    $(document).ready(function () {
        $('.show-status-box').click(function () {
            $(this).next().show();
        });

        $('.selected').click(function () {
            $(this).next().next().css("display", "block");
        });

        $('.candidate').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");
        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.applicant').click(function () {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().prev().html($(this).find('#status').html());
            $(this).parent().prev().prev().css("background-color", $(this).css("background-color"));
            var id = $(this).parent().find('#id').html();
            var status_name = $(this).attr('data-status_name');
            var message_sid = '<?php echo $message["sid"] ?>';
            var my_url = "<?= base_url() ?>manage_admin/free_demo/ajax_handler";
            var my_request;

            my_request = $.ajax({
                url: my_url,
                type: 'POST',
                data: {
                    "id": id,
                    "status": status_name,
                    "message_sid": message_sid,
                    "action": "ajax_update_status"}
            });

            my_request.done(function (response) {
                if (response == 'success') {
                    alertify.success("Contact status updated successfully.");
                } else {
                    alertify.error("Could not update Contact Status.");
                }
            });
        });

        $('.applicant').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function () {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function () {
            $(this).parent().css("display", "none");
        });

        $.each($(".selected"), function () {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });
    });
</script>
<script src="<?php echo base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.datetimepicker.css') ?>"/>
