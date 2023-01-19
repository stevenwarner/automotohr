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
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="row custom-btns">
                            <a href="<?php echo base_url('video_interview_system/templates'); ?>" class="btn btn-success">Video Template Management</a>
                            <!-- <a href="<?php echo base_url('video_interview_system/how_to'); ?>" class="btn btn-success">How To</a>-->
                            <button style="float:right;margin-right:5px;" onclick="show_add_default_question_modal();" class="btn btn-success" name="template_button" id="template_button">+ Add Default Questions</button>
                            <a style="float:right;margin-right:5px;" href="<?php echo base_url('video_interview_system/add'); ?>" class="btn btn-success">+ Create New Video Question</a>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-xs-12 col-sm-12">
                                <?php echo $links;  ?>
                            </div>
                            <div class="table-responsive table-outer">
                                <div class="table-wrp mylistings-wrp border-none">
                                    <table class="table table-bordered table-stripped table-hover">
                                        <thead>
                                        <tr>
                                            <th class="col-xs-5">Default Question</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (!empty($video_questions)) { 
                                                foreach ($video_questions as $question) { ?>
                                                    <tr id="<?php echo 'q_' . $question['sid']; ?>">
                                                        <td><?php echo ($question['question_type'] == 'text') ? $question['question_text'] : $question['video_title']; ?></td>
                                                        <td><?php echo ucwords($question['question_type']); ?></td>
                                                        <td id='<?php echo 'r_' . $question['sid']; ?>' class="<?php echo ($question['status'] == 'active') ? 'green' : 'red'; ?>"><?php echo ucwords($question['status']); ?></td>
                                                        <td><?php echo date_with_time($question['created_date']); ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo base_url('video_interview_system/edit/' . $question['sid']); ?>" class="btn btn-success">
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
                                                <td colspan="5" class="text-center"><span class="no-data">No Video Questions found!</span></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <?php echo $links;  ?>
                            </div>
                            <!-- table -->
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
    function show_add_default_question_modal(){
    $('#file_preview_modal #document_modal_body').html('');
    url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

    $.post(url_to, {perform_action: "get_default_question_form"})
        .done(function (response) {
            var resp = JSON.parse(response);
                $('#file_preview_modal .modal-dialog').addClass('modal-lg');
                $('#file_preview_modal #document_modal_body').html(resp.html);
                $('#file_preview_modal #document_modal_title').html(resp.title);
                $('#file_preview_modal').modal('toggle');
        });
    }
    
    function delete_question(question_sid) {
        alertify.confirm('Confirm Delete', "Are you sure you want to delete this Video Interview Question?",
                function () {
                    url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

                    $.post(url_to, {perform_action: "delete_question", question_sid: question_sid})
                            .done(function (response) {
                                var resp = JSON.parse(response);

                                if (resp == 'success') {
                                    $('#q_' + question_sid).remove();
                                    alertify.success('Video Interview Question deleted successfully.');
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

            alertify.confirm('Confirm Deactivation', "Are you sure you want to deactivate this Video Interview Question?",
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
                                        alertify.success('Video Interview Question deactivated successfully.');

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

            alertify.confirm('Confirm Activation', "Are you sure you want to activate this Video Interview Question?",
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
                                        alertify.success('Video Interview Question activated successfully.');

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

    function func_launch_how_to_modal(){
        var my_request;
        my_request = $.ajax({
            url: '<?php echo base_url('video_interview_system/ajax_responder'); ?>',
            type: 'POST',
            data: {'perform_action': 'load_help_information'}
        });

        my_request.done(function (response) {
            $('#popupmodalbody').html(response);
            $('#popupmodal').modal('toggle');
        });

    }
</script>