<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-files-o"></i>Resource Page Section</h1>
                                        <a href="<?php echo base_url('manage_admin/hr_documents_content')?>" class="btn black-btn pull-right"><i class="fa fa-long-arrow-left"></i>Back</a>
                                    </div>
                                    <form id="form_add_section" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="add_section" />
                                        <input type="hidden" id="dynamic_pages_sid" name="dynamic_pages_sid" value="<?php echo $dynamic_pages_sid; ?>" />
                                        <input type="hidden" id="section_sid" name="section_sid" value="<?php echo isset($section['sid']) ? $section['sid'] : 0; ?>" />

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $temp = isset($section['title']) ? $section['title'] : ''; ?>
                                                    <?php echo form_label('Title', 'title'); ?>
                                                    <?php echo form_input('title', set_value('title', $temp), 'class="hr-form-fileds" id="title"'); ?>
                                                    <?php echo form_error('title');?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row autoheight">
                                                    <?php $temp = isset($section['description']) ? html_entity_decode($section['description']) : ''; ?>
                                                    <?php echo form_label('Description', 'description'); ?>
                                                    <?php echo form_textarea('description', set_value('description', $temp, false), 'class="hr-form-fileds ckeditor" id="description"'); ?>
                                                    <?php echo form_error('description');?>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php if(isset($section['video']) && !empty($section['video'])) { ?>
                                                    <div class="well well-sm">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div align="center" class="embed-responsive embed-responsive-16by9">
                                                                    <video controls class="embed-responsive-item">
                                                                        <source src="https://hr-documents-videos.s3.amazonaws.com/<?php echo $section['video']; ?>" type="video/mp4">
                                                                    </video>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="field-row">
                                                    <?php echo form_label('Video', 'video'); ?>
                                                    <input type="file" class="" id="video" name="video" />
                                                    <?php echo form_error('video');?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php $temp = isset($section['video_status']) ? $section['video_status'] : ''; ?>

                                                <div class="field-row">
                                                    <label class="control control--radio admin-access-level">
                                                        <?php $default_checked = $temp == 0 ? true :  false; ?>
                                                        <input name="video_status" id="video_status_0" class="video_status" type="radio" value="0" <?php echo set_radio('video_status', 0, $default_checked); ?>>
                                                        Disable Video
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <label class="control control--radio admin-access-level">
                                                        <?php $default_checked = $temp == 1 ? true :  false; ?>
                                                        <input name="video_status" id="video_status_1" class="status" type="radio" value="1" <?php echo set_radio('video_status', 1, $default_checked); ?>>
                                                        Enable Video
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $temp = isset($section['sort_order']) ? $section['sort_order'] : ''; ?>
                                                    <?php echo form_label('Sort Order', 'sort_order'); ?>
                                                    <input type="number" id="sort_order" name="sort_order" value="<?php echo set_value('sort_order', $temp)?>" class="hr-form-fileds" min="0" />
                                                    <?php echo form_error('sort_order');?>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php $temp = isset($section['youtube_video']) ? $section['youtube_video'] : ''; ?>
                                                <?php if(isset($section['youtube_video']) && !empty($section['youtube_video'])) { ?>
                                                    <div class="well well-sm">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div align="center" class="embed-responsive embed-responsive-16by9">
                                                                    <iframe src="https://www.youtube.com/embed/<?php echo $temp; ?>" frameborder="0" allowfullscreen></iframe>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="field-row">
                                                    <?php $temp = isset($section['youtube_video']) ? 'https://www.youtube.com/watch?v=' . $section['youtube_video'] : ''; ?>
                                                    <?php echo form_label('Youtube Video', 'youtube_video'); ?>
                                                    <?php echo form_input('youtube_video', set_value('youtube_video', $temp), 'class="hr-form-fileds" id="youtube_video"'); ?>
                                                    <?php echo form_error('youtube_video');?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php $temp = isset($section['youtube_video_status']) ? $section['youtube_video_status'] : ''; ?>

                                                <div class="field-row">
                                                    <label class="control control--radio admin-access-level">
                                                        <?php $default_checked = $temp == 0 ? true :  false; ?>
                                                        <input name="youtube_video_status" id="youtube_video_status_0" class="youtube_video_status" type="radio" value="0" <?php echo set_radio('youtube_video_status', 0, $default_checked); ?>>
                                                        Disable Youtube Video
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <label class="control control--radio admin-access-level">
                                                        <?php $default_checked = $temp == 1 ? true :  false; ?>
                                                        <input name="youtube_video_status" id="youtube_video_status_1" class="youtube_video_status" type="radio" value="1" <?php echo set_radio('youtube_video_status', 1, $default_checked); ?>>
                                                        Enable Youtube Video
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php $temp = isset($section['status']) ? $section['status'] : ''; ?>

                                                <div class="field-row">
                                                    <label class="control control--radio admin-access-level">
                                                        <?php $default_checked = $temp == 0 ? true :  false; ?>
                                                        <input name="status" id="status_0" class="status" type="radio" value="0" <?php echo set_radio('status', 0, $default_checked); ?>>
                                                        Disable Section
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <label class="control control--radio admin-access-level">
                                                        <?php $default_checked = $temp == 1 ? true :  false; ?>
                                                        <input name="status" id="status_1" class="status" type="radio" value="1" <?php echo set_radio('status', 1, $default_checked); ?>>
                                                        Enable Section
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                    <a href="<?php echo base_url('manage_admin/hr_documents_content'); ?>" class="btn black-btn">Cancel</a>
                                                </div>
                                            </div>
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
