<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a href="<?php echo base_url('job_fair_configuration/customize_form_listing'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Forms Listing</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                </div>
                <div class="form-wrp">
                    <form name="add_new_job_fair_form" id="add_new_job_fair_form" action="<?php echo $action_url; ?>" method="POST" enctype="multipart/form-data">
                        <div class="box-view">
                            <div class="talent-network-config">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Page title <span class="required">*</span></label>
                                            <input name="title" id="title" value="<?php echo isset($job_fair_data['title']) ? $job_fair_data['title'] : ''; ?>" class="form-control bg-white" type="text">
                                            <?php echo form_error('title'); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Content <span class="required">*</span></label>
                                            <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                            <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                            <textarea class="ckeditor textarea bg-white" name="content" id="content" rows="8" cols="60"><?php echo isset($job_fair_data['content']) ? $job_fair_data['content'] : ''; ?></textarea>
                                            <?php echo form_error('content'); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <div class="form-group autoheight">
                                                    <label class="control control--radio">
                                                        Show Image
                                                        <input type="radio" value="picture" name="picture_or_video" <?php if (isset($job_fair_data['picture_or_video']) && $job_fair_data['picture_or_video'] == 'picture') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <div class="form-group autoheight">
                                                    <label class="control control--radio">
                                                        Show Video
                                                        <input type="radio" value="video" name="picture_or_video" <?php if (isset($job_fair_data['picture_or_video']) && $job_fair_data['picture_or_video'] == 'video') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <div class="form-group autoheight">
                                                    <label class="control control--radio">
                                                        None
                                                        <input type="radio" value="none" name="picture_or_video" <?php if (isset($job_fair_data['picture_or_video']) && ($job_fair_data['picture_or_video'] == 'none')) {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <?php echo form_error('picture_or_video'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="video_div">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <label>Manage Video</label>
                                            </div>
                                            <div class="hr-box-body hr-innerpadding">
                                                <div class="row">
                                                    <?php
                                                    $pre_source = isset($job_fair_data['video_type']) && !empty($job_fair_data['video_type']) ? $job_fair_data['video_type'] : 'youtube';
                                                    $previous_video_id = '';
                                                    if (empty($job_fair_data['video_id'])) {
                                                        $previous_video_id = '';
                                                    } else if (!empty($job_fair_data['video_id']) && $pre_source == 'youtube') {
                                                        $previous_video_id = 'https://www.youtube.com/watch?v=' . $job_fair_data['video_id'];
                                                    } else if (!empty($job_fair_data['video_id']) && $pre_source == 'vimeo') {
                                                        $previous_video_id = 'https://vimeo.com/' . $job_fair_data['video_id'];
                                                    }
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="video-link">
                                                            <label for="video_source">Video Source</label>
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo YOUTUBE_VIDEO; ?>
                                                                <input <?php echo $pre_source == 'youtube' ? 'checked="checked"' : '';  ?> class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo VIMEO_VIDEO; ?>
                                                                <input <?php echo $pre_source == 'vimeo' ? 'checked="checked"' : '';  ?> class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                <?php echo UPLOAD_VIDEO; ?>
                                                                <input <?php echo $pre_source == 'upload' ? 'checked="checked"' : '';  ?> class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="row">
                                                            <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $pre_source == 'upload' ? $job_fair_data['video_id'] : ''; ?>">

                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="yt_vm_video_container">
                                                                <label for="YouTube_Video" id="label_youtube">Youtube Video:</label>
                                                                <label for="Vimeo_Video" id="label_vimeo" style="display: none">Vimeo Video:</label>
                                                                <input type="text" name="yt_vm_video_url" value="<?php echo $previous_video_id; ?>" class="form-control" id="yt_vm_video_url">
                                                            </div>

                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="up_video_container" style="display: none">
                                                                <label>Upload Video <span class="hr-required">*</span></label>
                                                                <div class="upload-file invoice-fields">
                                                                    <span class="selected-file" id="name_video_upload"></span>
                                                                    <input type="file" name="video_upload" id="video_upload" onchange="check_upload_video_file('video_upload')">
                                                                    <a href="javascript:;">Choose Video</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if (isset($job_fair_data['video_id']) && !empty($job_fair_data['video_id'])) { ?>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="well well-sm" style="margin-top: 16px;">
                                                                <?php if ($pre_source == 'youtube') { ?>
                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $job_fair_data['video_id']; ?>"></iframe>
                                                                    </div>
                                                                <?php } else if ($pre_source == 'vimeo') { ?>
                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                        <iframe src="https://player.vimeo.com/video/<?php echo $job_fair_data['video_id']; ?>" frameborder="0"></iframe>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <video controls width="100%">
                                                                        <source src="<?php echo base_url('assets/uploaded_videos/' . $job_fair_data['video_id']); ?>" type='video/mp4'>
                                                                    </video>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (isset($job_fair_data['banner_image']) && $job_fair_data['banner_image'] != '' && $job_fair_data['banner_image'] != NULL) { ?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight no-margin" id="pic_display">
                                            <div class="well well-sm">
                                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $job_fair_data['banner_image']; ?>" alt="">
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="picture_div">
                                        <div class="form-group autoheight">
                                            <label>upload image</label>
                                            <div class="upload-file form-control">
                                                <span class="selected-file" id="name_pictures">No file selected</span>
                                                <input name="banner_image" id="pictures" onchange="check_file('pictures')" type="file">
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <label>customize Button Colors</label>
                                            </div>
                                            <div class="hr-box-body hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="video-link" style='font-style: italic; font-weight: normal; padding-bottom: 10px;'><b></b>
                                                            Please leave it blank if you want to use default colors
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <label for="button_background_color">Button Background Color</label>
                                                                <div class="input-group colorcustompicker">
                                                                    <input type="text" class="form-control" name="button_background_color" value="<?php echo isset($job_fair_data['button_background_color']) ? $job_fair_data['button_background_color'] : ''; ?>">
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                                <label for="button_text_color">Button Text Color</label>
                                                                <div class="input-group colorcustompicker">
                                                                    <input type="text" class="form-control" name="button_text_color" value="<?php echo isset($job_fair_data['button_text_color']) ? $job_fair_data['button_text_color'] : ''; ?>">
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <label>Auto-Responder Email Template</label>
                                            </div>
                                            <div class="hr-box-body hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--radio">
                                                                            Disable
                                                                            <input type="radio" value="0" name="template-status" checked>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--radio">
                                                                            Enable
                                                                            <input type="radio" value="1" name="template-status" <?php if (isset($job_fair_data['template_status']) && $job_fair_data['template_status'] == 1) {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                                <label>Template</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="form-control" name="template-id" id="template-id" <?php if (isset($job_fair_data['template_status']) && $job_fair_data['template_status'] == 0) {
                                                                                                                                            echo 'disabled="disabled"';
                                                                                                                                        } ?>>
                                                                        <option value="0">Please Select Template</option>
                                                                        <?php foreach ($custom_temp as $temp) { ?>
                                                                            <option value="<?= $temp['sid']; ?>" <?php if (isset($job_fair_data['template_sid']) && $job_fair_data['template_sid'] == $temp['sid']) { ?>selected<?php } ?>><?= $temp['template_name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--                                        <li class="form-col-100 autoheight">-->
                                    <!--                                            <input value="Save" class="submit-btn" type="submit">-->
                                    <!--                                        </li>-->
                                </div>
                            </div>
                        </div>

                        <div class="box-view">
                            <h4 class="section-title">Make Applicant For This Job Fair Only Visible To Following Employees:</h4>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <select class="select2" name="visibility[]" multiple>
                                            <?php
                                            $preSelected = isset($job_fair_data['visibility_employees']) ? explode(',', $job_fair_data['visibility_employees']) : [];
                                            foreach ($employees as $emp) { ?>
                                                <option value="<?= $emp['sid']; ?>" <?php echo in_array($emp['sid'], $preSelected) ? 'selected="true"' : ''; ?>><?= remakeEmployeeName($emp, true); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-view">
                            <h4 class="section-title">Mandatory Fields <span class="required">*</span></h4>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="form-group autoheight">
                                        <label>First Name <span class="required">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="form-group autoheight">
                                        <label>Last Name <span class="required">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="form-group autoheight">
                                        <label>Email Address <span class="required">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                    <div class="form-group autoheight">
                                        <label>Form Title <span class="required">*</span></label>
                                        <input type="text" name="form_name" value="<?php echo $form_name; ?>" class="form-control bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-view">
                            <h4 class="section-title">Optional Fields</h4>
                            <p>Following are the default form fields. You can enable any field and set it as either Required / Not Required field. You can also rename the fields.</p>
                            <?php //echo '<pre>'; print_r($form_default_fields); exit;
                            foreach ($form_default_fields as $key => $field) {
                                if ($field['field_priority'] == 'optional' && $field['field_id'] != 'desired_job_title') { ?>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-2 col-xs-12 col-sm-2">
                                            <div class="form-group autoheight checkbox-valign-center">
                                                <label class="control control--checkbox">
                                                    Enable
                                                    <input value="1" name="<?php echo $field['field_id'] . '-display_status'; ?>" type="checkbox" <?php if ($field['field_display_status'] == 1) {
                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                    } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-md-7 col-xs-12 col-sm-7">
                                            <div class="form-group autoheight">
                                                <input type="text" class="form-control bg-white" value="<?php echo $field['field_name']; ?>" name="<?php echo $field['field_id']; ?>" <?php echo $field['field_name'] == 'Country' || $field['field_name'] == 'State' || $field['field_name'] == 'Upload a Resume (.pdf .docx .doc .rtf .jpg .jpe .jpeg .png .gif)' || $field['field_name'] == 'Upload a Profile Picture (.jpg .jpe .jpeg .png .gif)' ? 'disabled="disabled"' : ''; ?> />
                                                <?php if ($field['question_type'] == 'list') {
                                                    echo '<em class="example-link-green">Field Type: Single Select List</em>';
                                                } else if ($field['question_type'] == 'string') {
                                                    echo '<em class="example-link-green">Field Type: Text Field</em>';
                                                } else if ($field['field_id'] == 'video_resume') {
                                                    echo '<em class="example-link-green">Field Type: Upload Video Resume or Bio </em>';
                                                } else if ($field['question_type'] == 'file') {
                                                    echo '<em class="example-link-green">Field Type: Upload Video</em>';
                                                } else if ($field['question_type'] == 'file') {
                                                    echo '<em class="example-link-green">Field Type: Upload Resume File Type</em>';
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <div class="form-group autoheight checkbox-valign-center">
                                                <label class="control control--checkbox">
                                                    Is Required
                                                    <input id="is_required" value="1" name="<?php echo $field['field_id'] . '-is_required'; ?>" type="checkbox" <?php if ($field['is_required'] == 1) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <?php if ($this->uri->segment(2) == 'add_new') { ?>
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-xs-12 col-sm-2">
                                        <div class="form-group autoheight checkbox-valign-center">
                                            <label class="control control--checkbox">
                                                Enable
                                                <input value="1" name="video_resume-display_status" type="checkbox">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-7 col-xs-12 col-sm-7">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control bg-white" value="Allow Applicant to Upload an MP3, MP4, Youtube or Vimeo video in their application" name="video_resume">
                                            <em class="example-link-green">Field Type: Upload Video Resume or Bio</em>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <div class="form-group autoheight checkbox-valign-center">
                                            <label class="control control--checkbox">
                                                Is Required
                                                <input id="is_required" value="1" name="video_resume-is_required" type="checkbox">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($form_custom_fields)) { ?>
                                <div class="box-view jsDraggable">
                                    <h4 class="section-title">Custom Form Fields</h4>
                                    <p>You can modify the display status and control whether it is a mandatory field.</p>
                                    <?php foreach ($form_custom_fields as $key => $field) {
                                        if ($field['field_priority'] == 'optional' && ($field['question_type'] != 'list' && $field['question_type'] != 'multilist')) { ?>
                                            <div class="row jsResourcesSortOrder" data-key="<?= $field['sid']; ?>">
                                                <div class="col-lg-1 col-md-2 col-xs-12 col-sm-2">
                                                    <div class="form-group autoheight checkbox-valign-center">
                                                        <label class="control control--checkbox">
                                                            Enable
                                                            <input value="1" name="<?php echo $field['field_id'] . '-display_status'; ?>" type="checkbox" <?php if ($field['field_display_status'] == 1) {
                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                            } ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-7 col-xs-12 col-sm-7">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control bg-white" value="<?php echo $field['field_name']; ?>" name="<?php echo $field['field_id']; ?>" />
                                                        <?php if ($field['question_type'] == 'string') {
                                                            echo '<em class="example-link-green">Field Type: Text Field</em>';
                                                        } else {
                                                            echo '<em class="example-link-green">Field Type: True/Yes or False/No Choice</em>';
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                                    <div class="form-group autoheight checkbox-valign-center">
                                                        <label class="control control--checkbox">
                                                            Is Required
                                                            <input id="is_required" value="1" name="<?php echo $field['field_id'] . '-is_required'; ?>" type="checkbox" <?php if ($field['is_required'] == 1) {
                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                        } ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <a href="javascript:;" class="btn btn-danger btn-xs delete-field" data-attr="<?php echo $field['sid']; ?>"><i class="fa fa-trash"></i></a>
                                                    <a href="<?php echo base_url('job_fair_configuration/edit_custom_field/' . $id . '/' . $field['sid']) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                                                </div>
                                            </div>
                                        <?php       } else { ?>
                                            <div class="row jsResourcesSortOrder" data-key="<?= $field['sid']; ?>">
                                                <div class="col-lg-1 col-md-2 col-xs-12 col-sm-2">
                                                    <div class="form-group autoheight checkbox-valign-center" style="margin: 35px 0 0 0;">
                                                        <label class="control control--checkbox">
                                                            Enable
                                                            <input value="1" name="<?php echo $field['field_id'] . '-display_status'; ?>" type="checkbox" <?php if ($field['field_display_status'] == 1) {
                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                            } ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-7 col-xs-12 col-sm-7">
                                                    <div class="form-group autoheight">
                                                        <label><?php echo $field['field_name']; ?></label>
                                                        <!--                                                    <input type="text" class="form-control bg-white" value="<?php //echo $field['field_name'];
                                                                                                                                                                        ?>" name="<?php //echo $field['field_id'];
                                                                                                                                                                                    ?>" />-->
                                                        <div class="select">
                                                            <select name="<?php echo $field['field_id']; ?>" class="form-control bg-white">
                                                                <?php foreach ($form_custom_questions_options as $fcqo) {
                                                                    if ($fcqo['questions_sid'] == $field['sid']) {
                                                                        $fcqo_value = $fcqo['value'];
                                                                        echo "<option>$fcqo_value</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <?php if ($field['question_type'] == 'list') {
                                                                echo '<em class="example-link-green">Field Type: Single Select List</em>';
                                                            } else {
                                                                echo '<em class="example-link-green">Field Type: Multiple Select</em>';
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3 text-center">
                                                    <div class="form-group autoheight checkbox-valign-center">
                                                        <label class="control control--checkbox">
                                                            Is Required
                                                            <input id="is_required" value="1" name="<?php echo $field['field_id'] . '-is_required'; ?>" type="checkbox" <?php if ($field['is_required'] == 1) {
                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                        } ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <a href="javascript:;" class="btn btn-danger btn-xs delete-field" data-attr="<?php echo $field['sid']; ?>"><i class="fa fa-trash"></i></a>
                                                    <a href="<?php echo base_url('job_fair_configuration/edit_custom_field/' . $id . '/' . $field['sid']) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                                                </div>
                                            </div>
                                    <?php               }
                                    } ?>
                                </div>
                            <?php   } ?>
                            <input type="hidden" name="perform_action" value="add_new_form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    &nbsp;
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <a href="<?php echo base_url('job_fair_configuration/customize_form_listing'); ?>" class="submit-btn btn-cancel">Cancel</a>
                                </div>
                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                    <input type="submit" value="<?php echo $submit_name; ?>" class="btn btn-success pull-right" onclick="job_fair_form()">
                                </div>
                                <?php if ($view_type == 'edit') { ?>
                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                        <input type="button" value="Add New Field" onclick="location.href='<?php echo base_url('job_fair_configuration/add_custom_field/' . $id); ?>';" class="btn btn-success">
                                    </div>
                                <?php   } ?>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-colorpicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-colorpicker.min.css " />
<script>
    function youtube_check() {
        var matches = $('#youtubevideo').val().match(/https:\/\/(?:www\.)?youtube.*watch\?v=([a-zA-Z0-9\-_]+)/);
        data = $('#youtubevideo').val();

        if (matches || data == '') {
            $("#video_link_error").html("");
            $(':input[type="submit"]').prop('disabled', false);
            $(".search-btn").css("background", "#81b431");
            return true;
        } else {
            $("#video_link_error").html("Please enter a Valid Youtube Link");
            $(':input[type="submit"]').prop('disabled', true);
            $(".search-btn").css("background", "#ccc");
            return false;
        }
    }

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function display(value) {
        if (value == 'picture') {
            $('#picture_div').show();
            $('#pic_display').show();
            $('#video_div').hide();
        } else if (value == 'video') {
            $('#video_div').show();
            $('#picture_div').hide();
            $('#pic_display').hide();
        } else {
            $('#video_div').hide();
            $('#picture_div').hide();
            $('#pic_display').hide();
        }
    }

    $(document).ready(function() {
        //
        $('.select2').select2({
            closeOnSelect: false
        });
        var value = $("input[name='picture_or_video']:checked").val();
        display(value);

        $('.colorcustompicker').colorpicker();

        var previous_source = '<?php echo isset($job_fair_data['video_type']) && !empty($job_fair_data['video_type']) ? $job_fair_data['video_type'] : 'youtube'; ?>';

        if (previous_source == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (previous_source == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (previous_source == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        }
    });
    $("input[type='radio']").click(function() {
        var value = $("input[name='picture_or_video']:checked").val();
        display(value);
    });
    $("input[name='template-status']").change(function() {
        var value = $(this).val();
        if (value == '0') {
            //            $('#template-id').val('');
            $('#template-id').prop('disabled', 'disabled');
        } else {
            $('#template-id').removeAttr('disabled');
        }
    });

    function job_fair_form() {
        $("#add_new_job_fair_form").validate({
            ignore: ":hidden:not(select)",
            rules: {
                form_name: {
                    required: true
                },
                title: {
                    required: true
                }
            },
            messages: {
                form_name: {
                    required: 'Form Title is required!'
                },
                title: {
                    required: 'Page Title is required!'
                }
            },
            submitHandler: function(form) {
                var instances = $.trim(CKEDITOR.instances.content.getData());
                if (instances.length === 0 || instances.length === 25) {
                    alertify.alert('Error! Content Missing', "Content cannot be Empty");
                    return false;
                }
                if ($("input[name='template-status']:checked").val() == 1 && $('#template-id').val() == '') {
                    alertify.error('Please Select Auto-Responder Template');
                    return false;
                }

                var vlidate_status = $('input[name="picture_or_video"]:checked').val();

                if (vlidate_status == 'video') {
                    var video_source = $('input[name="video_source"]:checked').val();
                    var flag = 0;

                    if (video_source == 'youtube') {
                        if ($('#yt_vm_video_url').val() != '') {
                            var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                            if (!$('#yt_vm_video_url').val().match(p)) {
                                alertify.error('Not a Valid Youtube URL');
                                flag = 1;
                                return false;
                            }
                        } else {
                            alertify.error('Video URL is required');
                            flag = 1;
                            return false;
                        }
                    }

                    if (video_source == 'vimeo') {
                        if ($('#yt_vm_video_url').val() != '') {
                            var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                            $.ajax({
                                type: "POST",
                                url: myurl,
                                data: {
                                    url: $('#yt_vm_video_url').val()
                                },
                                async: false,
                                success: function(data) {
                                    if (data == false) {
                                        alertify.error('Not a Valid Vimeo URL');
                                        flag = 1;
                                        return false;
                                    }
                                },
                                error: function(data) {}
                            });
                        } else {
                            alertify.error('Video URL is required');
                            flag = 1;
                            return false;
                        }
                    }

                    if (video_source == 'upload') {
                        if ($('#video_upload').val() == '') {
                            if ($('#old_upload_video').val() == '') {
                                alertify.error('Please Choose Video');
                                flag = 1;
                                return false;
                            }
                        }
                    }

                    if (flag != 1) {
                        $('#my_loader').show();
                        form.submit();
                    }
                } else {
                    form.submit();
                }
            }
        });
    }

    $(document).ready(function() {
        $('.delete-field').click(function() {
            var del_id = $(this).attr('data-attr');
            alertify.confirm(
                'Please Confirm Delete',
                'Are you sure you want to delete!',
                function() {
                    $.ajax({
                        url: '<?php echo base_url('job_fair_configuration/delete_field') ?>',
                        type: 'POST',
                        data: {
                            sid: del_id
                        },
                        success: function() {
                            alertify.success('Deleted Successfully');
                            window.location.href = '<?php echo current_url(); ?>';
                        },
                        error: function() {

                        }
                    });
                },
                function() {
                    alertify.warning('Cancelled!');
                });
        });
    });

    $('.video_source').on('click', function() {
        var selected = $(this).val();

        if (selected == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (selected == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        }
    });

    function check_upload_video_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'video_upload') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }
            }
        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');

        }
    }





    //
    let XHR = null;

    $(".jsDraggable").sortable({
        update: function(event, ui) {
            //
            var orderList = [];
            //
            $(".jsResourcesSortOrder").map(function(i) {
                orderList.push($(this).data("key"));
            });
            // 
            var obj = {};
            obj.sortOrders = orderList;
            //
            updateCardsSortOrder(obj);
        }
    });

    //
    function updateCardsSortOrder(data) {
        // check if XHR already in progress
        if (XHR !== null) {
            XHR.abort();
        }
        //
        $('#my_loader').show();

        XHR = $.ajax({
                url: '<?php echo base_Url("job_fair_configuration/updateSortOrder") ?>',
                method: "post",
                data,
            })
            .always(function() {
                XHR = null;
            })
            .done(function(resp) {
                $('#my_loader').hide();
            });
    }
</script>