<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><a class="dashboard-link-btn" href="<?php echo base_url('onboarding/configuration'); ?>"><i class="fa fa-chevron-left"></i>Onboarding Configuration</a>Welcome Video </span>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="hr-box">
                                    <div class="hr-box-header">
                                        Edit Welcome Video
                                    </div>
                                    <div class="hr-innerpadding">
                                        <form id="func_insert_welcome_video" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <?php $count = count($onboarding_welcome_video)?>
                                            <input type="hidden" id="perform_action" name="perform_action" value="update_welcome_video" />
                                            <input type="hidden" id="welcome_video_sid" name="welcome_video_sid" value="<?php echo isset($onboarding_welcome_video)? $onboarding_welcome_video['sid'] : ''; ?>" />
                                            <input type="hidden" id="welcome_video_old_url" name="welcome_video_old_url" value="<?php echo isset($onboarding_welcome_video)? $onboarding_welcome_video['video_url'] : ''; ?>" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <div class="universal-form-style-v2">
                                                <ul>
                                                    <li class="form-col-100">
                                                        <label for="video_status">Video Title <span class="hr-required">*</span></label>
                                                        <input class="invoice-fields" type="text" id="welcome_video_title" name="welcome_video_title" value="<?php echo $count > 0 && isset($onboarding_welcome_video['title'])? $onboarding_welcome_video['title'] : ''; ?>">
                                                    </li>
                                                    <li class="form-col-50-right autoheight">
                                                        <?php //$active = $count > 0 && isset($onboarding_welcome_video['is_active'])? $onboarding_welcome_video['is_active'] : '1'; ?>
                                                        <!-- <label for="video_status">Video Status</label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            Enable
                                                            <input class="video_status" type="radio" id="welcome_video_status_enable" name="welcome_video_status" value="1" <?php //echo $active == '1' ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            Disable
                                                            <input class="video_status" type="radio" id="welcome_video_status_disable" name="welcome_video_status" value="0" <?php //echo $active == '0' ? 'checked="checked"' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label> -->
                                                    </li>
                                                    <li class="form-col-50-left autoheight edit_filter">
                                                        <?php $source = $count > 0 && isset($onboarding_welcome_video['video_source'])? $onboarding_welcome_video['video_source'] : 'youtube'; ?>
                                                        <label for="video_source">Video Source <span class="hr-required">*</span></label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            <?php echo YOUTUBE_VIDEO; ?>
                                                            <input <?php echo $source == 'youtube' ? 'checked="checked"' : ''; ?> class="welcome_video_source" type="radio" id="welcome_video_source_youtube" name="welcome_video_source" value="youtube">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            <?php echo VIMEO_VIDEO; ?>
                                                            <input <?php echo $source == 'vimeo' ? 'checked="checked"' : ''; ?> class="welcome_video_source" type="radio" id="welcome_video_source_vimeo" name="welcome_video_source" value="vimeo">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            <?php echo UPLOAD_VIDEO; ?>
                                                            <input <?php echo $source == 'upload' ? 'checked="checked"' : ''; ?> class="welcome_video_source" type="radio" id="welcome_video_source_upload" name="welcome_video_source" value="upload">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li class="form-col-100" id="welcome_yt_vm_video_container">
                                                        <?php if ($count > 0) {
                                                                if($source == 'youtube' && !empty($onboarding_welcome_video['video_url'])) {
                                                                    $yt_vm_link_url = 'https://www.youtube.com/watch?v='.$onboarding_welcome_video['video_url'];
                                                                } elseif ($source == 'vimeo' && !empty($onboarding_welcome_video['video_url'])) {
                                                                    $yt_vm_link_url = 'https://vimeo.com/'.$onboarding_welcome_video['video_url'];
                                                                } else {
                                                                    $yt_vm_link_url = '';
                                                                }
                                                              } else {
                                                                $yt_vm_link_url = '';
                                                              }
                                                        ?>
                                                        <input type="text" name="yt_vm_video_url" value="<?php echo $yt_vm_link_url; ?>" class="invoice-fields" id="yt_vm_video_url">
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
                                                    <?php if ($count > 0) { ?> 
                                                        <li class="form-col-100 video_preview autoheight">
                                                            <label>Video Preview </label>
                                                            <?php if($source == 'youtube') { ?>
                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $onboarding_welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                            <?php } elseif($source == 'vimeo') { ?>
                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $onboarding_welcome_video['video_url']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                            <?php } else {?> 
                                                                <video controls width="100%">
                                                                    <source src="<?php echo base_url().'assets/uploaded_videos/'.$onboarding_welcome_video['video_url']; ?>" type='video/mp4'>
                                                                </video>
                                                            <?php } ?>
                                                        </li> 
                                                    <?php }?>
                                                    <li class="form-col-100">
                                                        <button class="submit-btn btn-success" id="add_welcome_video_submit">Update Welcome Video</button>
                                                        <input type="button" value="Cancel" class="submit-btn btn-cancel" onclick="document.location.href = '<?php echo base_url('onboarding/configuration'); ?>'">
                                                    </li>
                                                </ul>
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
            var old_uploaded_video = $('#welcome_video_old_url').val();
            var old_video_type = '<?php echo $count > 0 && isset($onboarding_welcome_video['video_source'])? $onboarding_welcome_video['video_source'] : 'youtube';?>';
            if(old_uploaded_video != '' && old_video_type == 'upload'){
                flag = 1;
            } else{
                var file = welcome_video_check('welcome_video_upload');
                if (file == false){
                    // alertify.error('Please upload welcome video');
                    flag = 0;
                    return false;    
                } else {
                    flag = 1;
                }
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

    $(document).ready(function(){
        var selected = '<?php echo $count > 0 && isset($onboarding_welcome_video['video_source'])? $onboarding_welcome_video['video_source'] : 'youtube';?>';
        if(selected == 'youtube' || selected == 'vimeo'){
            $('#welcome_yt_vm_video_container').show();
            $('#welcome_up_video_container').hide();
        } else if(selected == 'upload'){
            $('#welcome_yt_vm_video_container').hide();
            $('#welcome_up_video_container').show();
        }
    });

</script>