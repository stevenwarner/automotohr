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
                                        <h1 class="page-title"><i class="fa fa-users"></i>Edit Email Template</h1>

                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $template['company_sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Company</a>
                                        <a href="<?php echo base_url('manage_admin/portal_email_templates/' . $template['company_sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Templates</a>
                                    </div>
                                    <div class="add-new-company">
                                        <form id="form_portal_email_template" enctype="multipart/form-data" method="post">
                                            <div class="field-row">
                                                <?php echo form_label('Template Name <span class="hr-required">*</span>', 'template_name'); ?>
                                                <?php echo form_input('template_name', set_value('template_name', $template['template_name']), 'class="hr-form-fileds" id="template_name"');?>
                                                <?php echo form_error('template_name'); ?>
                                            </div>
                                            <div class="field-row">
                                                <?php echo form_label('From Name <span class="hr-required">*</span>', 'from_name');?>
                                                <?php echo form_input('from_name', set_value('from_name', $template['from_name']), 'class="hr-form-fileds" id="from_name"'); ?>
                                                <?php echo form_error('from_name'); ?>
                                            </div>
                                            <div class="field-row">
                                                <?php echo form_label('Subject <span class="hr-required">*</span>', 'subject'); ?>
                                                <?php echo form_input('subject', set_value('subject', $template['subject']), 'class="hr-form-fileds" id="subject"'); ?>
                                                <?php echo form_error('subject'); ?>
                                            </div>
                                            <div class="field-row field-row-autoheight">
                                                <label for="message_body">Email Body <span class="hr-required">*</span></label>
                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                <textarea class="ckeditor" name="message_body" id="message_body" rows="8" cols="60" ><?php echo set_value('message_body', $template['message_body'])?></textarea>
                                            </div>

                                            <div class="field-row field-row-autoheight">
                                                <?php
                                                    $default_checked = false;
                                                    if($template['enable_auto_responder'] == 1){
                                                        $default_checked = true;
                                                    }else{
                                                        $default_checked = false;
                                                    }

                                                ?>

                                                <label class="control control--checkbox">
                                                    Enable Auto Responder
                                                    <input name="enable_auto_responder" value="1" type="checkbox" <?php echo set_checkbox('enable_auto_responder', 1, $default_checked)?> />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="field-row field-row-autoheight">
                                                <input value="Save" class="site-btn" type="submit">
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
