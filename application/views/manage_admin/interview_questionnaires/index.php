<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-question-circle"></i><?php echo $title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin')?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Dashboard</a>
                                    </div>
                                    <div class="add-new-company">
                                        <?php $function_names = array('add_questionnaire', 'manage_default_questions'); ?>
                                        <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="add-new-promotions">
                                                            <?php if(check_access_permissions_for_view($security_details, 'add_questionnaire')){ ?>
                                                                    <a class="btn btn-success" href="<?php echo base_url('manage_admin/interview_questionnaires/add_questionnaire/'); ?>">Add New Interview Questionnaire</a>
                                                            <?php } if(check_access_permissions_for_view($security_details, 'manage_default_questions')){ ?>
                                                                    <a class="btn btn-success" href="<?php echo base_url('manage_admin/interview_questionnaires/manage_default_questions/'); ?>">Manage Default Questions</a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php } ?>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <h1 class="hr-registered pull-left"><span class="">List of All Interview Questionnaires</span></h1>
                                                    </div>
                                                    <div class="table-responsive hr-innerpadding daily-activity">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-left col-xs-4">Title</th>
                                                                    <th class="text-left col-xs-4">Description</th>
                                                                    <th class="text-left col-xs-1">Sections</th>
                                                                    <th class="text-left col-xs-1">Status</th>
                                                                    <?php $function_names = array('add_questionnaire', 'manage_questionnaire'); ?>
                                                                    <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                            <th class="text-left col-xs-2 text-center" colspan="3">Actions</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if(!empty($questionnaires)) { ?>
                                                                    <?php foreach($questionnaires as $questionnaire) { ?>
                                                                        <tr>
                                                                            <td class="text-left"><?php echo $questionnaire['title']; ?></td>
                                                                            <td class="text-left">
                                                                                <?php echo $questionnaire['short_description']; ?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php echo $questionnaire['sections_count']; ?>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <?php echo ucwords($questionnaire['status']); ?>
                                                                            </td>
                                                                            <?php $function_names = array('add_questionnaire', 'manage_questionnaire'); ?>
                                                                            <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                            <?php if(check_access_permissions_for_view($security_details, 'add_questionnaire')){ ?>
                                                                                <td class="text-center">
                                                                                    <a href="<?php echo base_url('manage_admin/interview_questionnaires/edit_questionnaire/' . $questionnaire['sid']); ?>" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if(check_access_permissions_for_view($security_details, 'manage_questionnaire')){ ?>
                                                                                <td class="text-center">
                                                                                    <a href="<?php echo base_url('manage_admin/interview_questionnaires/manage_questionnaire/' . $questionnaire['sid']); ?>" class="btn btn-sm btn-success">Manage</a>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <!--<td class="text-center">
                                                                                <button type="button" onclick="func_preview_questionnaire(<?php /*echo $questionnaire['sid']; */?>);"  class="btn btn-sm btn-success">Preview</button>
                                                                            </td>-->
                                                                            <?php if(check_access_permissions_for_view($security_details, 'add_questionnaire')){ ?>
                                                                                <td class="text-center">
                                                                                    <form id="form_delete_questionnaire_<?php echo $questionnaire['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url();?>" >
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_questionnaire" />
                                                                                        <input type="hidden" id="questionnaire_sid" name="questionnaire_sid" value="<?php echo $questionnaire['sid']; ?>" />
                                                                                        <button onclick="func_delete_questionnaire(<?php echo $questionnaire['sid']; ?>);" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                                                    </form>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php } ?>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="5"><span class="no-data">No Questionnaires Created</span></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
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
    </div>
</div>

<script>
    function func_preview_questionnaire(questionnaire_sid){
        var my_request;

        my_request = $.ajax({
            url: '<?php echo base_url("manage_admin/interview_questionnaires/ajax_responder")?>',
            type: 'POST',
            data: { 'perform_action': 'generate_questionnaire_preview', 'questionnaire_sid': questionnaire_sid },
            dataType: 'json'
        });

        my_request.done(function (response) {
            console.log(response);

            $('.modal-dialog').addClass('modal-lg');

            $('#file_preview_modal h4#document_modal_title').html(response.questionnaire_title);
            $('#file_preview_modal div#document_modal_body').html(response.view_data);


            $('#file_preview_modal').modal('toggle');

        });
    }

    function func_delete_questionnaire(questionnaire_sid){
        alertify.confirm(
            'Are You Sure?',
            'Are you sure you want to delete this Questionnaire?',
            function () {
                $('#form_delete_questionnaire_' + questionnaire_sid).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }
</script>