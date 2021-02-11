<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>
            <?php echo form_open_multipart('', array('id' => 'talent-network-config')); ?>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page-header-area">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back to Settings</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8"></div>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <a href="<?php echo base_url(); ?>job_fair_configuration/customize_form_listing" class="btn btn-success pull-right">Multiple Forms Listing</a><br/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="universal-form-style-v2 talent-network-config">
                                <ul>
                                    <li class="form-col-100">
                                        <label>title <span class="hr-required">*</span></label>
                                        <input name="title" id="title" value="<?php echo isset($job_fair_data['title']) ? $job_fair_data['title'] : ''; ?>" class="invoice-fields" type="text">
                                        <?php echo form_error('title'); ?>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <label>Content <span class="hr-required">*</span></label>
                                        <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                        <textarea class="ckeditor" name="content" id="content" rows="8" cols="60" ><?php echo isset($job_fair_data['content']) ? $job_fair_data['content'] : ''; ?></textarea>
                                        <?php echo form_error('content'); ?>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <label class="control control--radio">
                                                    Show Image
                                                    <input type="radio" value="picture" name="picture_or_video" <?php if(isset($job_fair_data['picture_or_video']) && $job_fair_data['picture_or_video'] == 'picture'){ echo 'checked'; } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <label class="control control--radio">
                                                    Show Video
                                                    <input type="radio" value="video" name="picture_or_video" <?php if(isset($job_fair_data['picture_or_video']) && $job_fair_data['picture_or_video'] == 'video'){ echo 'checked'; } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                <label class="control control--radio">
                                                    None
                                                    <input type="radio" value="none" name="picture_or_video" <?php if(isset($job_fair_data['picture_or_video']) && ($job_fair_data['picture_or_video'] == 'none')){ echo 'checked'; } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-12">
                                            	<?php echo form_error('picture_or_video'); ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="form-col-100 autoheight" id="video_div">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <label>Manage Video</label>
                                            </div>
                                            <div class="hr-box-body hr-innerpadding">
                                                <div class="row">
                                                    <?php 
                                                        $pre_source = isset($job_fair_data['video_type']) && !empty($job_fair_data['video_type']) ? $job_fair_data['video_type'] : 'youtube'; 
                                                        $previous_video_id = '';
                                                        if ($pre_source == 'youtube') {
                                                            $previous_video_id = 'https://www.youtube.com/watch?v='.$job_fair_data['video_id'];
                                                        } else if ($pre_source == 'vimeo') {
                                                            $previous_video_id = 'https://vimeo.com/'.$job_fair_data['video_id'];
                                                        }
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="video-link" >
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
                                                                    <input type="file" name="video_upload" id="video_upload" onchange="check_file('video_upload')" >
                                                                    <a href="javascript:;">Choose Video</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ( isset($job_fair_data['video_id']) && !empty($job_fair_data['video_id'])) { ?>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="well well-sm" style="margin-top: 16px;">
                                                                <?php if($pre_source == 'youtube') { ?>
                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $job_fair_data['video_id']; ?>"></iframe>
                                                                    </div>
                                                                <?php } else if($pre_source == 'vimeo') { ?>
                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                        <iframe src="https://player.vimeo.com/video/<?php echo $job_fair_data['video_id']; ?>" frameborder="0"></iframe>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <video controls width="100%">
                                                                        <source src="<?php echo base_url('assets/uploaded_videos/'.$job_fair_data['video_id']); ?>" type='video/mp4'>   
                                                                    </video>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>    
                                                </div>
                                            </div>
                                        </div> 
                                    </li>
                                    <?php if(isset($job_fair_data['banner_image']) && $job_fair_data['banner_image'] != '' && $job_fair_data['banner_image'] != NULL){ ?>
                                    <li class="form-col-100 autoheight no-margin" id="pic_display">
                                        <div class="well well-sm">
                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $job_fair_data['banner_image']; ?>" alt="">
                                        </div>
                                    </li>
                                    <?php } ?>
                                    <li class="form-col-100" id="picture_div">
                                        <label>upload image</label>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_pictures">No file selected</span>
                                            <input name="banner_image" id="pictures" onchange="check_file('pictures')" type="file">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </li>
                                    <li class="form-col-100 autoheight">
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
                                                                    <input type="text" class="form-control" name="button_background_color" value="<?php echo $job_fair_data['button_background_color']; ?>">
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                                <label for="button_text_color">Button Text Color</label>
                                                                <div class="input-group colorcustompicker"> 
                                                                    <input type="text" class="form-control" name="button_text_color" value="<?php echo $job_fair_data['button_text_color']; ?>">
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <input value="Save" class="submit-btn" type="submit">
                                    </li>
                                </ul>
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
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
<script type="text/javascript">
    
    
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
    
    function display(value)
    {
        if (value == 'picture')
        {
            $('#picture_div').show();
            $('#pic_display').show();
            $('#video_div').hide();
        }
        else if (value == 'video')
        {
            $('#video_div').show();
            $('#picture_div').hide();
            $('#pic_display').hide();
        }
        else
        {
            $('#video_div').hide();
            $('#picture_div').hide();
            $('#pic_display').hide();
        }
    }
    
    $(document).ready(function () {
        var value = $("input[name='picture_or_video']:checked").val();
        display(value);
        
        $('.colorcustompicker').colorpicker();

        var previous_source = '<?php echo isset($job_fair_data['video_type']) && !empty($job_fair_data['video_type']) ? $job_fair_data['video_type'] : 'youtube'; ?>';
        if(previous_source == 'youtube'){
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (previous_source == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if(previous_source == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        }
     

        $("#talent-network-config").validate({
            ignore: [],
            rules: {
                title: {
                    required: true
                },
                content: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Title is required.'
                },
                content: {
                    required: 'Content is required.'
                }
            },
            submitHandler: function (form) {
                var vlidate_status = $('input[name="picture_or_video"]:checked').val();

                if (vlidate_status  == 'video') {
                    var video_source = $('input[name="video_source"]:checked').val();
                    var flag = 0;
                        
                    if (video_source  == 'youtube') {
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
                    
                    if (video_source  == 'vimeo') {
                        if ($('#yt_vm_video_url').val() != '') {
                            var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                            $.ajax({
                                type: "POST",
                                url: myurl,
                                data: {url: $('#yt_vm_video_url').val()},
                                async: false,
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
                        } else {
                            alertify.error('Video URL is required');
                            flag = 1;
                            return false;
                        }
                    }

                    if (video_source  == 'upload') {
                        if ($('#video_upload').val() == '') {
                            if ($('#old_upload_video').val() == '') {
                                alertify.error('Please Choose Video');
                                flag = 1;
                                return false;
                            }    
                        } 
                    }
            
                    if(flag != 1){ 
                        $('#my_loader').show();
                        form.submit();
                    }
                }  else {
                    form.submit();
                }  
                
            }
        });

        $('.status-toggle').click(function () {
            var id = $(this).attr('id');
            var status = $(this).html();

            if (status == 'Disable') {
                $.ajax({
                    type: 'GET',
                    data: {
                        status: 0
                    },
                    url: '<?= base_url('manage_ems/enable_disable_notification') ?>/' + id,
                    success: function (data) {
                        data = JSON.parse(data);

                        if (data.message == 'updated') {
                            $('#' + id).removeClass('btn-warning');
                            $('#' + id).addClass('btn-primary');
                            $('#' + id).html('Enable');
                        }
                    },
                    error: function () {
                    }
                });
            } else if (status == 'Enable') {
                $.ajax({
                    type: 'GET',
                    data: {
                        status: 1
                    },
                    url: '<?= base_url('manage_ems/enable_disable_notification') ?>/' + id,
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.message == 'updated') {
                            $('#' + id).removeClass('btn-primary');
                            $('#' + id).addClass('btn-warning');
                            $('#' + id).html('Disable');
                        }
                    },
                    error: function () {
                    }
                });
            }
        });
    });

    $("input[type='radio']").click(function(){
        var value = $("input[name='picture_or_video']:checked").val();
        display(value);
    });

    $('.video_source').on('click', function () {
        var selected = $(this).val();

        if(selected == 'youtube'){
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if(selected == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        }
    });

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();

            if (val == 'video_upload') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
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
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');

        }
    }
</script>