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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title?></h1>
                                        <a href="<?php echo base_url('manage_admin/additional_content_boxes/'.$box['company_sid']) ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back</a>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST" id="box" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title">Box Details</h1>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">  
                                                    <div class="field-row">
                                                        <label for="title">Title <span class="hr-required">*</span></label>
                                                        <?php echo form_input('title', set_value('title',$box['title']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('title'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="status">Status <span class="hr-required">*</span></label>
                                                        <select name="status" class="hr-form-fileds valid" aria-invalid="false">
                                                            <option value="0" <?php echo  !$box['status'] ? 'selected="selected"' : '';?>>In Active</option>
                                                            <option value="1" <?php echo  $box['status'] ? 'selected="selected"' : '';?>>Active</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Content</label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="content" rows="8" cols="60" >
                                                            <?php echo set_value('content',$box['content']);?>
                                                        </textarea>
                                                        <?php echo form_error('content'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="profile_picture">Image:</label>
                                                        <div class="upload-file form-control">
                                                            <span class="selected-file" id="name_image">No file selected</span>
                                                            <input name="image" class="hr-form-fileds" id="image" type="file" onchange="check_file('image')">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Column Type</label>
                                                        <select name="column_type" class="hr-form-fileds valid" aria-invalid="false">
                                                            <option value="left_right" <?php echo $box['column_type'] == "left_right" ? "selected='selected'" : ''?>>Left Right</option>
                                                            <option value="right_left" <?php echo $box['column_type'] == "right_left" ? "selected='selected'" : ''?>>Right Left</option>
                                                            <option value="top_down" <?php echo $box['column_type'] == "top_down" ? "selected='selected'" : ''?>>Top Down</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label for="status">Video URL </label>
                                                        <?php echo form_input('video', set_value('video',$box['video']), 'class="hr-form-fileds video-url"'); ?>
                                                        <?php echo form_error('video'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Show Image/Video</label>
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <input id="video_id" type="radio" name="show_video_or_image" value="video" <?php echo $box['show_video_or_image'] == "video" ? "checked='checked'" : ''?>> <label for="video_id">Video</label> </input>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <input id="image_id" type="radio" name="show_video_or_image" value="image" <?php echo $box['show_video_or_image'] == "image" ? "checked='checked'" : ''?>> <label for="image_id">Image</label> </input>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <input type="submit" class="search-btn pull-right" value="Update" name="box_submit">
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
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.replace('content');
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    $(function () {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#box").validate({
            ignore: ":hidden:not(select)",
            rules: {
                content: {
                    required: function()
                    {
                        CKEDITOR.instances.content.updateElement();
                    }
                },
                status: {
                    required: true
                },
                title: {
                    required: true
                },
                video: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            },
            messages: {
                content: {
                    required: 'Content is required'
                },
                status: {
                    required: 'Status is required'
                },
                title: {
                    required: 'Title is required'
                },
                video:{
                    pattern: 'Provide a valid Youtube video Url(i.e. https://www.youtube.com/watch?v=xxxxxxxxxxx )'
                }
            },
            submitHandler: function (form) {

                var instances = $.trim(CKEDITOR.instances.content.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Content Missing', "Content cannot be Empty");
                    return false;
                }
                form.submit();
            }
        });
    });
</script>