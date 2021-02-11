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

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">
                                                        <span class=""><?php echo $subtitle; ?></span>
                                                    </h1>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <form method="post" enctype="multipart/form-data" id="form_add_new_interview_questionnaire" action="<?php echo current_url(); ?>">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <?php $temp = (isset($questionnaire['title']) ? $questionnaire['title'] : ''); ?>
                                                                <?php echo form_label('Title <span class="hr-required">*</span>', 'title'); ?>
                                                                <?php echo form_input('title', set_value('title', $temp), 'class="hr-form-fileds"'); ?>
                                                                <?php echo form_error('title'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="field-row field-row-autoheight">
                                                                <?php $temp = (isset($questionnaire['short_description']) ? $questionnaire['short_description'] : ''); ?>
                                                                <?php echo form_label('Short Description', 'title'); ?>
                                                                <?php echo form_textarea('short_description', set_value('short_description', $temp), 'class="hr-form-fileds field-row-autoheight"'); ?>
                                                                <?php echo form_error('short_description'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-1">
                                                            <div class="field-row">
                                                                <?php $temp = (isset($questionnaire['status']) ? $questionnaire['status'] : ''); ?>
                                                                <?php $default_checked = ($temp == 'active' ? true : true); ?>
                                                                <label>Status</label>
                                                                <label class="control control--radio">
                                                                    <input name="status" id="status_active" value="active" class="" <?php echo set_radio('status', 'active', $default_checked); ?> type="radio">
                                                                    Active
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-1">
                                                            <div class="field-row">
                                                                <?php $default_checked = ($temp == 'inactive' ? true : false); ?>
                                                                <label>&nbsp;</label>
                                                                <label class="control control--radio">
                                                                    <input name="status" id="status_active" value="inactive" class="" <?php echo set_radio('status', 'inactive', $default_checked); ?> type="radio">
                                                                    Inactive
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </form>
                                                </div>
                                                <div class="hr-box-footer hr-innerpadding">
                                                    <button type="button" class="btn btn-success" onclick="f_save_interview_questionnaire();"><?php echo $submit_btn_text; ?></button>
                                                    <a class="btn black-btn" href="<?php echo base_url('manage_admin/interview_questionnaires'); ?>" >Cancel</a>
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
    function f_save_interview_questionnaire(){
        $('#form_add_new_interview_questionnaire').validate({
            rules:{
                title: {
                    required: true
                }
            },
            messages:{
                title: {
                    required: 'Please type a title for questionnaire!'
                }
            }
        });

        if($('#form_add_new_interview_questionnaire').valid()){
            $('#form_add_new_interview_questionnaire').submit();
        } else {
            console.log('Invalid Form');
        }
    }
</script>