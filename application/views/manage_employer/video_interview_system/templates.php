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
                            <a class="dashboard-link-btn" href="<?php echo base_url('video_interview_system'); ?>">
                                <i class="fa fa-chevron-left"></i>
                                Back
                            </a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="row custom-btns">
                            <a style="float:right;" href="<?php echo base_url('video_interview_system/add_template'); ?>" class="btn btn-success">+ New Video Question Template</a>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <!-- table -->
                            <div class="table-responsive table-outer">
                                <div class="table-wrp mylistings-wrp border-none">
                                    <table class="table table-bordered table-stripped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-xs-5">Template Title</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($templates)) { ?>
                                                <?php foreach ($templates as $template) { ?>
                                                    <tr id="<?php echo 'q_' . $template['sid']; ?>">
                                                        <td><?php echo ucwords($template['title']); ?></td>
                                                        <td id='<?php echo 'r_' . $template['sid']; ?>' class="<?php echo ($template['status'] == 'active') ? 'green' : 'red'; ?>">
                                                            <?php echo ucwords($template['status']); ?>
                                                        </td>
                                                        <td><?php echo date_with_time($template['created_date']); ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo base_url('video_interview_system/edit_template/' . $template['sid']); ?>" class="btn btn-success">
                                                                View/Edit
                                                                <!--<i class="fa fa-pencil"></i>-->
                                                            </a>
                                                            <button onclick="delete_question(<?php echo $template['sid']; ?>);" type="button" class="btn btn-danger">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            <span id='<?php echo 'b_' . $template['sid']; ?>'>
                                                                <?php if ($template['status'] == 'active') { ?>
                                                                    <a href="javascript:;" class="btn btn-success deactivate_question" id="<?php echo $template['sid']; ?>">Deactivate</a>
                                                                <?php } else { ?>
                                                                    <a href="javascript:;" class="btn btn-danger activate_question" id="<?php echo $template['sid']; ?>">Activate</a>
                                                                <?php } ?>
                                                            </span>
                                                            <a href="<?php echo base_url('video_interview_system/manage_template') . '/' . $template['sid']; ?>" class="btn btn-success">
                                                                Manage
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="4" class="text-center"><span class="no-data">No Questions Templates found!</span></td>
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
<script>
    function delete_question(template_sid) {
        alertify.confirm('Confirm Delete', "Are you sure you want to delete this Question Template?",
                function () {
                    url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

                    $.post(url_to, {perform_action: "delete_question_template", template_sid: template_sid})
                            .done(function (response) {
                                var resp = JSON.parse(response);

                                if (resp == 'success') {
                                    $('#q_' + template_sid).remove();
                                    alertify.success('Question Template deleted successfully.');
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
            var template_sid = $(this).attr('id');

            alertify.confirm('Confirm Deactivation', "Are you sure you want to deactivate this Question Template?",
                    function () {
                        url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

                        $.post(url_to, {perform_action: "deactivate_question_template", template_sid: template_sid})
                                .done(function (response) {
                                    var resp = JSON.parse(response);

                                    if (resp == 'success') {
                                        $('#r_' + template_sid).removeClass('green');
                                        $('#r_' + template_sid).addClass('red');
                                        $('#r_' + template_sid).text('Inactive');
                                        $('#b_' + template_sid + ' a').removeClass('btn-success deactivate_question');
                                        $('#b_' + template_sid + ' a').addClass('btn-danger activate_question');
                                        $('#b_' + template_sid + ' a').text('Activate');
                                        alertify.success('Question Template deactivated successfully.');

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
            var template_sid = $(this).attr('id');

            alertify.confirm('Confirm Activation', "Are you sure you want to activate this Question Template?",
                    function () {
                        url_to = "<?= base_url() ?>video_interview_system/ajax_responder";

                        $.post(url_to, {perform_action: "activate_question_template", template_sid: template_sid})
                                .done(function (response) {
                                    var resp = JSON.parse(response);

                                    if (resp == 'success') {
                                        $('#r_' + template_sid).removeClass('red');
                                        $('#r_' + template_sid).addClass('green');
                                        $('#r_' + template_sid).text('Active');
                                        $('#b_' + template_sid + ' a').removeClass('btn-danger activate_question');
                                        $('#b_' + template_sid + ' a').addClass('btn-success deactivate_question');
                                        $('#b_' + template_sid + ' a').text('Deactivate');
                                        alertify.success('Question Template activated successfully.');

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