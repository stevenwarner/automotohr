<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('interview_questionnaire'); ?>"><i class="fa fa-chevron-left"></i>Interview Questionnaires</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <h1 class="hr-registered pull-left">
                                                    <span class=""><?php echo $subtitle; ?></span>
                                                </h1>
                                            </div>
                                            <div class="hr-box-body hr-innerpadding">
                                                <div class="universal-form-style-v2">
                                                    <form method="post" enctype="multipart/form-data" id="form_add_new_interview_questionnaire" action="<?php echo current_url(); ?>">
                                                        <ul>
                                                            <li class="form-col-100 autoheight">
                                                                <?php $temp = (isset($questionnaire['title']) ? $questionnaire['title'] : ''); ?>
                                                                <?php echo form_label('Title <span class="hr-required">*</span>', 'title'); ?>
                                                                <?php echo form_input('title', set_value('title', $temp), 'class="invoice-fields"'); ?>
                                                                <?php echo form_error('title'); ?>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <?php $temp = (isset($questionnaire['short_description']) ? $questionnaire['short_description'] : ''); ?>
                                                                <?php echo form_label('Short Description', 'title'); ?>
                                                                <?php echo form_textarea('short_description', set_value('short_description', $temp), 'class="invoice-fields-textarea"'); ?>
                                                                <?php echo form_error('short_description'); ?>
                                                            </li>
                                                        </ul>

                                                    </form>
                                                </div>
                                            </div>
                                            <div class="hr-box-footer hr-innerpadding">
                                                <button type="button" class="btn btn-success" onclick="f_save_interview_questionnaire();"><?php echo $submit_btn_text; ?></button>
                                                <a class="btn btn-default" href="<?php echo base_url('interview_questionnaire'); ?>" >Cancel</a>
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