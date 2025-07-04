<div class="row">
    <div class="col-xs-12">
        <div class="dashboard-conetnt-wrp">
            <div class="announcements-listing">
        <?php   if (!empty($onboarding_welcome_video)) {
                    foreach ($onboarding_welcome_video as $welcome_video) { ?>
                        <article class="listing-article">
                            <figure>
        <?php                   if ($welcome_video['video_source'] == 'youtube') { ?>
                                    <img class="thumbnail_video_url" src="https://img.youtube.com/vi/<?php echo $welcome_video['video_url']; ?>/hqdefault.jpg"/>
        <?php                   } else if ($welcome_video['video_source'] == 'vimeo') { 
                                    $thumbnail_image = vimeo_video_data($welcome_video['video_url']); ?>
                                    <img class="thumbnail_video_url"  src="<?php echo $thumbnail_image; ?>"/>
        <?php                   }  else { ?>
                                    <video width="214" height="auto">
                                        <source src="<?php echo base_url('assets/uploaded_videos/'.$welcome_video['video_url']); ?>" type='video/mp4'>
                                    </video>
        <?php                   } ?>
                            </figure>
                            <div class="text">
                                <h3><?php echo $welcome_video['title']; ?></h3>
                                <div class="post-options">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-3 col-xs-12 col-sm-3">
                                            <ul>
                                                <li><?php echo date_with_time($welcome_video['insert_date']); ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <a class="btn btn-success btn-sm btn-block status-toggle" href="<?= base_url('onboarding/edit_welcome_video/' . $welcome_video['sid']); ?>">Edit</a>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <?php if ($welcome_video['is_default'] == 0) { ?>
                                                <form id="form_enable_disable_welcome_video_<?php echo $welcome_video['sid']; ?>" method="post" action="<?php echo current_url(); ?>">
                                                    <input type="hidden" name="perform_action" value="enable_disable_welcome_video" />
                                                    <input type="hidden" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                    <input type="hidden" name="welcome_video_sid" value="<?php echo $welcome_video['sid']; ?>" />
                                                    <input type="hidden" name="enable_status" value="<?php echo $welcome_video['is_active']; ?>" />
                                                    <button type="submit" class="btn <?php echo $welcome_video['is_active'] == 1 ? 'btn-warning' : 'btn-primary' ?> btn-sm btn-block" ><?php echo $welcome_video['is_active'] == 1 ? 'Disable' : 'Enable' ?></button>
                                                </form>
                                            <?php } else if ($welcome_video['is_default'] == 1) { ?>
                                                <button class="btn <?php echo $welcome_video['is_active'] == 1 ? 'btn-warning' : 'btn-primary' ?> btn-sm btn-block" onclick="func_enable_disable_video_error();"><?php echo $welcome_video['is_active'] == 1 ? 'Disable' : 'Enable' ?></button>
                                            <?php } ?>                                                 
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <form id="form_delete_welcome_video_<?php echo $welcome_video['sid']; ?>" method="post" action="<?php echo current_url(); ?>">
                                                <input type="hidden" name="perform_action" value="delete_welcome_video" />
                                                <input type="hidden" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <input type="hidden" name="welcome_video_sid" value="<?php echo $welcome_video['sid']; ?>" />
                                                <button type="button" class="btn btn-danger btn-sm btn-block" onclick="func_delete_welcome_video(<?php echo $welcome_video['sid']; ?>);">Delete</button>
                                            </form>
                                        </div>
                                    </div>                                                                
                                </div>
                                <div class="full-width announcement-des">
                                    <?php 
                                        $btn_text = isset($welcome_video['is_default']) && $welcome_video['is_default'] == 0 ? 'Make It Default Welcome Video' : 'Make It Un-Default';
                                        $btn_color = isset($welcome_video['is_default']) && $welcome_video['is_default'] == 0 ? 'btn-success' : 'btn-warning';
                                        if ($welcome_video['is_active'] == 1) { 
                                    ?>
                                            <form id="form_default_welcome_video_<?php echo $welcome_video['sid']; ?>" method="post" action="<?php echo current_url(); ?>">
                                                <input type="hidden" name="perform_action" value="make_welcome_video_default_undefault" />
                                                <input type="hidden" name="welcome_video_sid" value="<?php echo $welcome_video['sid']; ?>" />
                                                <input type="hidden" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <input type="hidden" id="default_status_<?php echo $welcome_video['sid']; ?>" name="default_status" value="<?php echo $welcome_video['is_default']; ?>" />
                                                <button type="button" class="btn <?php echo $btn_color; ?> pull-right" onclick="func_default_welcome_video(<?php echo $welcome_video['sid']; ?>);"><?php echo $btn_text; ?></button>
                                            </form>
                                    <?php } else if ($welcome_video['is_active'] == 0) { ?>
                                        <button type="button" class="btn btn-success pull-right" onclick="func_default_welcome_video_error();"><?php echo $btn_text; ?></button>
                                    <?php } ?>    
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                } ?>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                Add New Welcome Video for Onboarding
            </div>
            <div class="hr-innerpadding">
                <form id="func_insert_welcome_video" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="insert_welcome_video" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <label for="video_status">Video Title <span class="hr-required">*</span></label>
                                <input class="invoice-fields" type="text" id="welcome_video_title" name="welcome_video_title" value="">
                            </li>
                            <li class="form-col-50-right autoheight">
                                <label for="video_status">Video Status <span class="hr-required">*</span></label>
                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                    Enable
                                    <input class="video_status" type="radio" id="welcome_video_status_enable" name="welcome_video_status" value="1">
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                    Disable
                                    <input class="video_status" type="radio" id="welcome_video_status_disable" name="welcome_video_status" value="0">
                                    <div class="control__indicator"></div>
                                </label>
                            </li>
                            <li class="form-col-50-left autoheight edit_filter">
                                <label for="video_source">Video Source <span class="hr-required">*</span></label>
                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                    <?php echo YOUTUBE_VIDEO; ?>
                                    <input class="welcome_video_source" type="radio" id="welcome_video_source_youtube" name="welcome_video_source" value="youtube">
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                    <?php echo VIMEO_VIDEO; ?>
                                    <input class="welcome_video_source" type="radio" id="welcome_video_source_vimeo" name="welcome_video_source" value="vimeo">
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                    <?php echo UPLOAD_VIDEO; ?>
                                    <input class="welcome_video_source" type="radio" id="welcome_video_source_upload" name="welcome_video_source" value="upload">
                                    <div class="control__indicator"></div>
                                </label>
                            </li>
                            <li class="form-col-100" id="welcome_yt_vm_video_container">
                                <input type="text" name="yt_vm_video_url" value="" class="invoice-fields" id="yt_vm_video_url">
                                <?php echo form_error('yt_vm_video_url'); ?>
                            </li>
                            <li class="form-col-100 autoheight edit_filter" id="welcome_up_video_container" style="display: none">
                                <label>Upload Video <span class="hr-required">*</span></label>
                                <div class="upload-file invoice-fields">
                                    <span class="selected-file" id="name_welcome_video_upload"></span>
                                    <input type="file" name="welcome_video_upload" id="welcome_video_upload" onchange="welcome_video_check('welcome_video_upload')" >
                                    <a href="javascript:;">Choose Video</a>
                                </div>
                            </li>
                            <li class="form-col-100">
                                <button type="button" class="btn btn-success" id="add_welcome_video_submit">Add Welcome Video</button>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we Upload video or save record...
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('.welcome_video_source').on('click', function(){
        if (!$("input[name='welcome_video_status']:checked").val()) {
            $('#welcome_video_status_enable').click();
        }
        
        var selected = $(this).val();
        if(selected == 'youtube' || selected == 'vimeo'){
            $('#welcome_yt_vm_video_container').show();
            $('#welcome_up_video_container').hide();
        } else if(selected == 'upload'){
            $('#welcome_yt_vm_video_container').hide();
            $('#welcome_up_video_container').show();
        }
    });

    function welcome_video_check(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'welcome_video_upload') {
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
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
            return false;

        }
    }

    $('#add_welcome_video_submit').click(function () {

        var flag = 0;
        var welcome_video_title = $('#welcome_video_title').val();

        if (welcome_video_title == '') {
            alertify.error('Welcome video title is required');
            flag = 0;
            return false;
        }

        if($('input[name="welcome_video_source"]:checked').val() == 'youtube'){
            
            
            if($('#yt_vm_video_url').val() != '') { 

                var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                if (!$('#yt_vm_video_url').val().match(p)) {
                    alertify.error('Not a Valid Youtube URL');
                    flag = 0;
                    return false;
                } else {
                    flag = 1;
                }
            } else {
                flag = 0;
            }
            
        } else if($('input[name="welcome_video_source"]:checked').val() == 'vimeo'){
            
            if($('#yt_vm_video_url').val() != '') {              
                var flag = 0;
                var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {url: $('#yt_vm_video_url').val()},
                    async : false,
                    success: function (data) {
                        if (data == false) {
                            alertify.error('Not a Valid Vimeo URL');
                            flag = 0;
                            return false;
                        } else {
                            flag = 1;
                        }
                    },
                    error: function (data) {
                    }
                });
            } else {
                flag = 0;
            }
        } else if ($('input[name="welcome_video_source"]:checked').val() == 'upload') {
            var file = welcome_video_check('welcome_video_upload');
            if (file == false){
                // alertify.error('Please upload welcome video');
                flag = 0;
                return false;    
            } else {
                flag = 1;
            }
        } else {
            flag = 0
        }

        if(flag == 1){
            $('#my_loader').show(); 
            $("#func_insert_welcome_video").submit(); 
        } else {
            alertify.error('Please provide welcome video data');
        }

        
      
    });

    function func_delete_welcome_video(welcome_video_sid) {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this welcome video',
                function () {
                    $('#form_delete_welcome_video_' + welcome_video_sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    function func_default_welcome_video(welcome_video_sid) {
        var message = '';
        var status = $('#default_status_'+ welcome_video_sid).val();

        if (status == 0) {
            message = 'Are you sure you want to make this welcome video as default video.';
        } else {
            message = 'Are you sure you want to make this welcome video as un-default.';
        }

        alertify.confirm(
                'Are you sure?',
                message,
                function () {
                    $('#form_default_welcome_video_' + welcome_video_sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    function func_default_welcome_video_error() {

        alertify.alert('Error !', 'To make this welcome video as default video, First Enable this video and then make it default!', function(){
            alertify.success('Ok Great'); 
        });
    }

    function func_enable_disable_video_error() {

        alertify.alert('Error !', 'To disable this video, First make this video to un-defaullt!', function(){
            alertify.success('Ok Great'); 
        });
    }

</script>