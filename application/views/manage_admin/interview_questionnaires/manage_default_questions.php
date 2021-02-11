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
                                        <a href="<?php echo base_url('manage_admin/interview_questionnaires')?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Interview Questionnaires</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="add-new-promotions">
                                                    <a class="btn btn-success" href="<?php echo base_url('manage_admin/interview_questionnaires/add_default_question/'); ?>">Add New Default Question</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <h1 class="hr-registered pull-left"><span class=""><?php echo $subtitle; ?></span></h1>
                                                    </div>
                                                    <div class="hr-box-body hr-innerpadding">

                                                        <?php if(!empty($default_questions)) { ?>
                                                            <?php foreach($default_questions as $category_questions) { ?>
                                                                <div class="heading-title page-title">
                                                                    <h1 class="page-title"><?php echo $category_questions['name']; ?></h1>
                                                                </div>
                                                                <div class="table-responsive hr-innerpadding daily-activity">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-left col-xs-11">Title</th>
                                                                            <th class="text-left col-xs-1 text-center" colspan="2">Actions</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php if(!empty($category_questions['questions'])) { ?>
                                                                            <?php foreach($category_questions['questions'] as $question){ ?>
                                                                                <tr>
                                                                                    <td><?php echo $question['question_text']; ?></td>
                                                                                    <td>
                                                                                        <a href="<?php echo base_url('manage_admin/interview_questionnaires/edit_default_question/' . $question['sid']); ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                                                                                    </td>
                                                                                    <td>
                                                                                        <form id="form_delete_default_question_<?php echo $question['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                            <input type="hidden" name="perform_action" id="perform_action" value="delete_default_question" />
                                                                                            <input type="hidden" name="question_sid" id="question_sid" value="<?php echo $question['sid']; ?>" />
                                                                                            <button type="button" onclick="func_delete_default_question(<?php echo $question['sid']; ?>)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                                        </form>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td class="text-center" colspan="4"><span class="no-data">No Questions Created</span></td>
                                                                            </tr>
                                                                        <?php } ?>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>

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
    function func_delete_default_question(question_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this question?',
            function () {
                $('#form_delete_default_question_' + question_sid).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }
</script>