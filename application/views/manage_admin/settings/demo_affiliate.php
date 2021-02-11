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
                                                    <div class="video-configuration-page">
                                                        <?php if (isset($data['video_source'])) { ?>
                                                            <ul>
                                                                <li class="video-configuration-page-left">
                                                                    <label>Selected Video Preview </label>
                                                                    <?php if($data['video_source'] == 'youtube_video') { ?>
                                                                       <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $data['youtube_video'] ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                    <?php } elseif($data['video_source'] == 'vimeo_video') { ?>
                                                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $data['vimeo_video']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                    <?php } else {?>
                                                                        <video controls width="100%">
                                                                            <source src="<?php echo base_url().'assets/uploaded_videos/super_admin' .$data['uploaded_video']; ?>" type='video/mp4'>
                                                                        </video>
                                                                    <?php } ?>
                       
                                                                </li>  
                                                            </ul>
                                                        <?php } ?>

                                                        <?php echo form_open('',array('id' =>'form_save_video_links', 'enctype' => 'multipart/form-data' )); ?>
                                                        <ul>
                                                            <li>
                                                                <label>YouTube Video</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'youtube_video', 'id' => 'youtube_video_id'), set_value('youtube_video', $youtube)); ?>
                                                                    <?php echo form_error('youtube_video'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Vimeo Video</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'vimeo_video', 'id' => 'vimeo_video_id'), set_value('vimeo_video', $vimeo)); ?>
                                                                    <?php echo form_error('vimeo_video'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Upload Video</label>
                                                                <div class="hr-fields-wrap">
                                                                    <div class="upload-file invoice-fields">
                                                                        <span class="selected-file" id="name_video_upload_id">No video selected</span>
                                                                        <input type="file" name="video_upload" id="video_upload_id" onchange="check_file('video_upload_id')" >
                                                                        <a href="javascript:;">Choose Video</a>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Select Video source</label>
                                                                <div class="hr-fields-wrap">
                                                                    <ul>
                                                                        <li class="video-configuration-page-left">
                                                                            <input type="radio" name="video_source" 
                                                                            <?php if ($data['video_source'] == 'youtube_video') { ?>
                                                                                checked="checked"
                                                                            <?php } ?>
                                                                            value="youtube_video" id="radio_youtube">&nbsp;<b><?php echo YOUTUBE_VIDEO; ?></b>
                                                                            &nbsp;&nbsp;
                                                                            <input type="radio" name="video_source"
                                                                            <?php if ($data['video_source'] == 'vimeo_video') { ?>
                                                                                checked="checked"
                                                                            <?php } ?>
                                                                            value="vimeo_video" id="radio_vimeo">&nbsp;<b><?php echo VIMEO_VIDEO; ?></b>
                                                                            &nbsp;&nbsp;
                                                                            <input type="radio" name="video_source"
                                                                            <?php if ($data['video_source'] == 'uploaded_video') { ?>
                                                                                checked="checked"
                                                                            <?php } ?>
                                                                            value="uploaded_video" id="radio_upload">&nbsp;<b><?php echo UPLOAD_VIDEO; ?></b><br/><br/>      
                                                                        </li>
                                                                    </ul>
                                                                    <?php echo form_error('video_source'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Status</label>
                                                                <div class="hr-fields-wrap">
                                                                    <ul>
                                                                        <li class="video-configuration-page-left">
                                                                            <input type="radio" name="status" 
                                                                            <?php if ($data['status'] == 0) { ?>
                                                                                checked="checked"
                                                                            <?php } ?>
                                                                            value="0">&nbsp;<b>Disable</b>
                                                                            &nbsp;&nbsp;
                                                                            <input type="radio" name="status"
                                                                            <?php if ($data['status'] == 1) { ?>
                                                                                checked="checked"
                                                                            <?php } ?>
                                                                            value="1">&nbsp;<b>Enable</b><br/><br/>      
                                                                        </li>
                                                                    </ul>
                                                                    <?php echo form_error('video_source'); ?>
                                                                </div>
                                                            </li>
                                                            <?php if ($data['sid'] == 3 || $data['sid'] == 4) { ?>
                                                                <li>
                                                                    <label>Column Type</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <ul>
                                                                            <li class="video-configuration-page-left">
                                                                                <input type="radio" name="column_type" 
                                                                                <?php if ($data['column_type'] == 'video_only') { ?>
                                                                                    checked="checked"
                                                                                <?php } ?>
                                                                                value="video_only" id="video_only">&nbsp;<b>Video Only</b>
                                                                                &nbsp;&nbsp;
                                                                                <input type="radio" name="column_type"
                                                                                <?php if ($data['column_type'] == 'left_right') { ?>
                                                                                    checked="checked"
                                                                                <?php } ?>
                                                                                value="left_right" id="left_right">&nbsp;<b>Left Right</b>
                                                                                &nbsp;&nbsp;
                                                                                <input type="radio" name="column_type"
                                                                                <?php if ($data['column_type'] == 'right_left') { ?>
                                                                                    checked="checked"
                                                                                <?php } ?>
                                                                                value="right_left" id="right_left">&nbsp;<b>Right Left</b>
                                                                                &nbsp;&nbsp;
                                                                                <input type="radio" name="column_type"
                                                                                <?php if ($data['column_type'] == 'top_bottom') { ?>
                                                                                    checked="checked"
                                                                                <?php } ?>
                                                                                value="top_bottom" id="top_bottom">&nbsp;<b>Top Bottom</b><br/><br/>      
                                                                            </li>
                                                                        </ul>
                                                                        <?php echo form_error('column_type'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Title</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'title', 'id' => 'title'), set_value('title', $data['title'])); ?>
                                                                        <?php echo form_error('title'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Content</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <textarea name="content" cols="40" rows="10" class="hr-form-fileds field-row-autoheight" id="content_ID">
                                                                            <?php echo $data['content']; ?>
                                                                        </textarea>
                                                                        <?php echo form_error('content'); ?>
                                                                    </div>
                                                                </li>             
                                                            <?php } ?>
                                                            
                                                            <li>
                                                                <input type="hidden" name="sid" value="<?php echo $data['sid']; ?>">
                                                                <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $data['uploaded_video']; ?>">
                                                                <input type="hidden" name="action" value="save">
                                                                <?php echo form_submit('', 'Save', array('class' => 'site-btn', 'id' =>'add_to_save_btn' )); ?>
                                                                <a href="<?php echo base_url('manage_admin/settings/demo_affiliate_configurations/');?>" class="black-btn">Cancel</a>
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
<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    $( document ).ready(function() {
        var pane = $('#content_ID');
        pane.val($.trim(pane.val()).replace(/\s*[\r\n]+\s*/g, '\n')
                               .replace(/(<[^\/][^>]*>)\s*/g, '$1')
                               .replace(/\s*(<\/[^>]+>)/g, '$1'));
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            
            if (val == 'video_upload_id') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else{
                    var file_size = Number(($("#" + val)[0].files[0].size/1024/1024).toFixed(2));
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
            alertify.error("No video selected to upload");
            return false;

        }    
    }

    $('#add_to_save_btn').click(function () {
        var youtube_required;
        var vimeo_required;
        var upload_required;

        if($('#radio_youtube').is(':checked')) { youtube_required = true; } else { youtube_required = false; }
        if($('#radio_vimeo').is(':checked')) { vimeo_required = true; } else { vimeo_required = false; }
        if($('#radio_upload').is(':checked')) { 
            if($('#old_upload_video').val()){
                    upload_required = false;
                } else {
                    upload_required = true;
                } 
        } else { upload_required = false; }

        <?php if ($data['sid'] == 3 || $data['sid'] == 4) { ?>
            
            if($('#video_only').is(':checked')) { 
                validate_title = false;
                validate_content = false;
            } else { 
                validate_title = true;
                validate_content = true;
                var pane = $('#content_ID');
                pane.val($.trim(pane.val()).replace(/\s*[\r\n]+\s*/g, '\n')
                               .replace(/(<[^\/][^>]*>)\s*/g, '$1')
                               .replace(/\s*(<\/[^>]+>)/g, '$1'));
            }

        <?php } ?>    
       
        $("#form_save_video_links").validate({
            ignore: [],
            rules: {
                video_title: {
                    required: false,
                },
                video_id: {
                    required: false,
                },
                video_upload:{
                    required: upload_required
                },
                youtube_video:{
                    required: youtube_required
                },
                vimeo_video:{
                    required: vimeo_required
                },
                title:{
                    required: validate_title
                },
                content:{
                    required: validate_title
                }
            },
            messages: {
                title: {
                    required: 'Video Title Is Required',
                },
                video_id: {
                    required: 'Please provide Valid Video URL',
                },
                video_upload: {
                    required: 'Please upload video',
                },
                youtube_video: {
                    required: 'Please Provide Youtube video link',
                },
                vimeo_video: {
                    required: 'Please Provide Vimeo video link',
                },
                title:{
                    required: 'Please Provide Title',
                },
                content:{
                    required: 'Please Provide Content',
                }
            },
            submitHandler: function (form) {
                var flag = 0;
                if($('#youtube_video_id').val() != '') { 

                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                    if (!$('#youtube_video_id').val().match(p)) {
                        alertify.error('Not a Valid Youtube URL');
                        flag = 1;
                        return false;
                    }
                }

             
                    
                if($('#vimeo_video_id').val() != '') { 

                    var flag = 0;
                    var myurl = "<?= base_url() ?>/manage_admin/settings/validate_vimeo";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {url: $('#vimeo_video_id').val()},
                        async : false,
                        success: function (data) {
                            if (data == false) {
                                alertify.error('Not a Valid Vimeo URL');
                                flag = 1;
                                return false;
                            }
                        },
                        error: function (data) {
                        }
                    });

                    
                }

                if($('#video_upload_id').val() != ''){
            
                    var is_true = check_file('video_upload_id');
                    if (is_true == false) {
                        flag = 1;
                        return false;
                    } else {
                        return true;
                    }
                    
                }

                if (flag == 0) {
                    $("#add_to_save_btn").attr("disabled", true); 
                    $('#my_loader').show();
                    form.submit();
                }
            }
        });        
    });

</script>



