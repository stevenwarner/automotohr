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
                                    <div class="dashboard-content">
                                        <div class="dash-inner-block">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                                    </div>
                                                    <div class="hr-setting-page">
                                                        <?php echo form_open('manage_admin/job_templates/add'); ?>
                                                        <input type="hidden" id="sid" name="sid" value="<?php echo $template['sid']; ?>" />
                                                        <input type="hidden" id="action" name="action" value="save_job_listing_template" />
                                                        <ul>
                                                            <li>
                                                                <label>Template Title</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class'=>'hr-form-fileds','name'=>'title'), set_value('text',$template['title']));?>
                                                                    <?php echo form_error('title'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Description</label>
                                                                <div class="hr-fields-wrap">
                                                                    <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                                    <textarea class="ckeditor" name="description" rows="8" cols="60" ><?php echo set_value('text', html_entity_decode($template['description'])); ?></textarea>
                                                                    <?php //echo form_input(array('class'=>'ckeditor','name'=>'text','type'=>'textarea'), set_value('text',$data['text']));  ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Requirements</label>
                                                                <div class="hr-fields-wrap">
                                                                    <textarea class="ckeditor" name="requirements" rows="8" cols="60" ><?php echo set_value('text', html_entity_decode($template['requirements'])); ?></textarea>
                                                                    <?php //echo form_input(array('class'=>'ckeditor','name'=>'text','type'=>'textarea'), set_value('text',$data['text']));  ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Sort Order</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class'=>'hr-form-fileds','name'=>'sort_order'), set_value('text',$template['sort_order']));?>
                                                                    <?php echo form_error('title'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo base_url('manage_admin/job_templates'); ?>" class="site-btn"><i class="fa fa-reply"></i>&nbsp;Back</a>
                                                                <?php echo form_submit('setting_submit','Save',array('class'=>'site-btn'));?>
                                                            </li>
                                                        </ul>
                                                        <?php echo form_close(); ?>
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

