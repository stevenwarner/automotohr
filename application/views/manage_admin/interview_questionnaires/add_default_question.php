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
                                        <a href="<?php echo base_url('manage_admin/interview_questionnaires/manage_default_questions'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Default Questions</a>
                                    </div>
                                    <div class="add-new-company">

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <h1 class="hr-registered pull-left"><span class=""><?php echo $subtitle; ?></span></h1>
                                                    </div>

                                                    <div class="hr-box-body hr-innerpadding">
                                                        <form id="form_add_default_question" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                            <?php if(isset($default_question)) { ?>
                                                                <input type="hidden" name="question_sid" id="question_sid" value="<?php echo $default_question['sid']; ?>" />
                                                            <?php } ?>

                                                            <div class="field-row field-row-autoheight">
                                                                <label for="question_category">Question Category</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select data-rule-required="true" class="invoice-fields" id="question_category" name="question_category">
                                                                        <?php $question_category = (isset($default_question)  ? $default_question['question_category'] : 0);  ?>

                                                                        <?php $default_selected = ( $question_category == 0 ? true : false ); ?>
                                                                        <option <?php echo set_select('question_category', 0, $default_selected); ?> value="">Please Select</option>

                                                                        <?php $default_selected = ( $question_category == 1 ? true : false ); ?>
                                                                        <option <?php echo set_select('question_category', 1, $default_selected); ?> value="1">Basic Interview Questions</option>

                                                                        <?php $default_selected = ( $question_category == 2 ? true : false ); ?>
                                                                        <option <?php echo set_select('question_category', 2, $default_selected); ?> value="2">Behavioral Interview Questions</option>

                                                                        <?php $default_selected = ( $question_category == 3 ? true : false ); ?>
                                                                        <option <?php echo set_select('question_category', 3, $default_selected); ?> value="3">Brainteasers</option>

                                                                        <?php $default_selected = ( $question_category == 4 ? true : false ); ?>
                                                                        <option <?php echo set_select('question_category', 4, $default_selected); ?> value="4">More Questions About You</option>

                                                                        <?php $default_selected = ( $question_category == 5 ? true : false ); ?>
                                                                        <option <?php echo set_select('question_category', 5, $default_selected); ?> value="5">Salary Questions</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="field-row field-row-autoheight">

                                                                <?php $question_text = (isset($default_question) ? $default_question['question_text'] : ''); ?>
                                                                <label for="question_text">Question Text</label>
                                                                <textarea data-rule-required="true" id="question_text" name="question_text" cols="40" rows="10" class="hr-form-fileds field-row-autoheight"><?php echo set_value('question_text', $question_text); ?></textarea>
                                                                <?php echo form_error('question_text'); ?>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="hr-box-footer hr-innerpadding">
                                                        <button type="button" class="btn btn-success" onclick="func_save_default_question();"><?php echo $submit_btn_text?></button>
                                                        <a class="btn black-btn" href="<?php echo base_url('manage_admin/interview_questionnaires/manage_default_questions'); ?>">Cancel</a>
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
    function func_save_default_question(){
        $('#form_add_default_question').validate();

        if($('#form_add_default_question').valid()){
            $('#form_add_default_question').submit();
        }
    }
</script>