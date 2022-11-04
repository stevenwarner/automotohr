<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
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
                    <div class="form-wrp">
                        <form id="form_save_video" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="perform_action" name="perform_action" value="save_video_training_info" />
                            <input type="hidden" id="video_sid" name="video_sid" value="<?php echo isset($video_sid) ? $video_sid : 0; ?>" />
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                    <br><br><br>


                                    <div class="form-group autoheight" id="up_video_container" style="display: block;">

                                        <label>Upload Video <span class="staric">*</span></label>
                                        <div class="upload-file form-control">
                                            <span class="selected-file" id="name_video">No video selected</span>
                                            <input type="file" name="video_upload" id="video" onchange="check_file('video')" <?php echo set_value($video_url); ?>>
                                            <a href="javascript:;">Choose Video</a>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                    <button id="add_edit_submit" class="btn btn-success" type="submit">Save Video</button>
                                    <a href="<?php echo base_url('learning_center/online_videos'); ?>" class="btn black-btn" type="submit">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'video') {
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



    $('#add_edit_submit').click(function() {
        var required_condition = true;

        $("#form_save_video").validate({
            ignore: [],
            rules: {
                video_upload: {
                    required: required_condition,
                }
            },
            messages: {


                video_upload: {
                    required: 'Please upload video',
                }
            },
            submitHandler: function(form) {
                var flag = 0;

                if (flag == 0) {
                    $('#my_loader').show();
                    $("#add_edit_submit").attr("disabled", true);
                    form.submit();
                }


            }
        });

    });

    $(document).ready(function() {


        $('input[type=radio]:checked').trigger('click');

        $('.edit_filter_check').on('click', function() {
            $('.edit_filter_check').hide();
            <?php if (isset($video_source)) { ?>
                <?php if ($video_source == 'upload') { ?>

                    $('.radio_btn_video_source').show();
                    $('#yt_vm_video_container input').prop('disabled', true);
                    $('#yt_vm_video_container').hide();

                    $('#up_video_container input').prop('disabled', false);
                    $('#up_video_container').show();

                    $('#add_edit_submit').attr('onClick', 'check_file("video");');
                <?php } else {  ?>

                    $('#yt_vm_video_container input').prop('disabled', false);
                    $('#yt_vm_video_container').show();
                    $('.radio_btn_video_source').show();

                    $('#up_video_container input').prop('disabled', true);
                    $('#up_video_container').hide();
                    $('#add_edit_submit').removeAttr('onClick');
                <?php } ?>
            <?php } else {  ?>
                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();
                $('#add_edit_submit').removeAttr('onClick');
            <?php } ?>
        });


        $('.video_source').on('click', function() {
            var selected = $(this).val();
            if (selected == 'youtube') {
                $('#yt_vm_video_container input').prop('disabled', false);
                $('#yt_vm_video_container').show();

                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();
                $('#add_edit_submit').removeAttr('onClick');
            } else if (selected == 'vimeo') {
                $('#yt_vm_video_container input').prop('disabled', false);
                $('#yt_vm_video_container').show();

                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();
                $('#add_edit_submit').removeAttr('onClick');
            } else if (selected == 'upload') {
                $('#yt_vm_video_container input').prop('disabled', true);
                $('#yt_vm_video_container').hide();

                $('#up_video_container input').prop('disabled', false);
                $('#up_video_container').show();

                $('#add_edit_submit').attr('onClick', 'check_file("video");');
            } else if (selected == 'do_not_change') {
                $('#up_video_container input').prop('disabled', true);
                $('#yt_vm_video_container input').prop('disabled', true);
                $('#add_edit_submit').removeAttr('onClick');
            }
        });
    });


</script>