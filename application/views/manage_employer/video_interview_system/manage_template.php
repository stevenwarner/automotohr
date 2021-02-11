<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <a class="dashboard-link-btn" href="<?php echo base_url('video_interview_system/templates'); ?>">
                                <i class="fa fa-chevron-left"></i>
                                Back
                            </a>
                            <?php echo $title; ?>
                            <span class="beta-label">beta</span>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="row custom-btns">
                            <button style="float:right;" onclick="show_add_default_question_modal(<?php echo $template_sid; ?>);" class="btn btn-success" name="template_button" id="template_button">
                                + Add Default Questions
                            </button>
                            <a style="float:right;margin-right:5px;" href="<?php echo base_url('video_interview_system/add') . '/' . $template_sid; ?>" class="btn btn-success">
                                + Create New Template Question
                            </a>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <strong>Template Title:</strong> <?php echo $template['title']; ?>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-xs-12"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <!-- table -->
                                            <div class="table-responsive table-outer">
                                                <div class="table-wrp mylistings-wrp border-none">
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th class="col-xs-5">Question</th>
                                                            <th>Type</th>
                                                            <th>Status</th>
                                                            <th>Created</th>
                                                            <th class="text-center">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if (!empty($template_questions)) { ?>
                                                            <?php foreach ($template_questions as $question) { ?>
                                                                <tr id="<?php echo 'q_' . $question['sid']; ?>">
                                                                    <td><?php echo ($question['question_type'] == 'text') ? $question['question_text'] : $question['video_title']; ?></td>
                                                                    <td><?php echo ucwords($question['question_type']); ?></td>
                                                                    <td id='<?php echo 'r_' . $question['sid']; ?>' class="<?php echo ($question['status'] == 'active') ? 'green' : 'red'; ?>">
                                                                        <?php echo ucwords($question['status']); ?>
                                                                    </td>
                                                                    <td><?php echo date_with_time($question['created_date']); ?></td>
                                                                    <td class="text-center">
                                                                        <a href="<?php echo base_url('video_interview_system/edit/' . $question['sid'] . '/' . $template_sid); ?>" class="btn btn-success">
                                                                            View/Edit
                                                                        </a>
                                                                        <button onclick="delete_question(<?php echo $question['sid']; ?>);" type="button" class="btn btn-danger">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                            <span id='<?php echo 'b_' . $question['sid']; ?>'>
                                                                <?php if ($question['status'] == 'active') { ?>
                                                                    <a href="javascript:;" class="btn btn-success deactivate_question" id="<?php echo $question['sid']; ?>">Deactivate</a>
                                                                <?php } else { ?>
                                                                    <a href="javascript:;" class="btn btn-danger activate_question" id="<?php echo $question['sid']; ?>">Activate</a>
                                                                <?php } ?>
                                                            </span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="5" class="text-center"><span class="no-data">No Template Questions found!</span></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                            <!-- table -->
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

<div id="file_preview_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script>
    function show_add_default_question_modal(template_sid){
    $('#file_preview_modal #document_modal_body').html('');
    url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

    $.post(url_to, {perform_action: "get_default_question_form", template_sid: template_sid})
        .done(function (response) {
            var resp = JSON.parse(response);
                $('#file_preview_modal .modal-dialog').addClass('modal-lg');
                $('#file_preview_modal #document_modal_body').html(resp.html);
                $('#file_preview_modal #document_modal_title').html(resp.title);
                $('#file_preview_modal').modal('toggle');
        });
    }
    
    function delete_question(question_sid) {
        alertify.confirm('Confirm Delete', "Are you sure you want to delete this Template Question?",
                function () {
                    url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

                    $.post(url_to, {perform_action: "delete_question", question_sid: question_sid})
                            .done(function (response) {
                                var resp = JSON.parse(response);

                                if (resp == 'success') {
                                    $('#q_' + question_sid).remove();
                                    alertify.success('Template Question deleted successfully.');
                                } else {
                                    alertify.error('An unknown error occured. Please try again.');
                                }
                            });
                },
                function () {
                    alertify.error('Cancelled');
                });
    }

    $(document).ready(function () {
        attach_activate_event();
        attach_deactivate_event();
    });


    function attach_deactivate_event() {
        $('.deactivate_question').click(function () {
            var question_sid = $(this).attr('id');

            alertify.confirm('Confirm Deactivation', "Are you sure you want to deactivate this Template Question?",
                    function () {
                        url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

                        $.post(url_to, {perform_action: "deactivate_question", question_sid: question_sid})
                                .done(function (response) {
                                    var resp = JSON.parse(response);

                                    if (resp == 'success') {
                                        $('#r_' + question_sid).removeClass('green');
                                        $('#r_' + question_sid).addClass('red');
                                        $('#r_' + question_sid).text('Inactive');
                                        $('#b_' + question_sid + ' a').removeClass('btn-success deactivate_question');
                                        $('#b_' + question_sid + ' a').addClass('btn-danger activate_question');
                                        $('#b_' + question_sid + ' a').text('Activate');
                                        alertify.success('Template Question deactivated successfully.');

                                        attach_activate_event();
                                    } else {
                                        alertify.error('An unknown error occured. Please try again.');
                                    }
                                });
                    },
                    function () {
                        alertify.error('Cancelled');
                    });
        });
    }

    function attach_activate_event() {
        $('.activate_question').click(function () {
            var question_sid = $(this).attr('id');

            alertify.confirm('Confirm Activation', "Are you sure you want to activate this Template Question?",
                    function () {
                        url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

                        $.post(url_to, {perform_action: "activate_question", question_sid: question_sid})
                                .done(function (response) {
                                    var resp = JSON.parse(response);

                                    if (resp == 'success') {
                                        $('#r_' + question_sid).removeClass('red');
                                        $('#r_' + question_sid).addClass('green');
                                        $('#r_' + question_sid).text('Active');
                                        $('#b_' + question_sid + ' a').removeClass('btn-danger activate_question');
                                        $('#b_' + question_sid + ' a').addClass('btn-success deactivate_question');
                                        $('#b_' + question_sid + ' a').text('Deactivate');
                                        alertify.success('Template Question activated successfully.');

                                        attach_deactivate_event();
                                    } else {
                                        alertify.error('An unknown error occured. Please try again.');
                                    }
                                });
                    },
                    function () {
                        alertify.error('Cancelled');
                    });
        });
    }
</script>