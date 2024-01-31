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
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back to Settings</a>
                                    Join Our Talent Network
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="universal-form-style-v2 talent-network-config">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Title <span class="hr-required">*</span></label>
                                        <input name="title" id="title" value="<?php echo isset($talent_data['title']) ? $talent_data['title'] : ''; ?>" class="invoice-fields" type="text">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-12 ">
                                        <label>Content <span class="hr-required">*</span></label>
                                        <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                        <textarea class="ckeditor" name="content" id="content" rows="8" cols="60"><?php echo isset($talent_data['content']) ? $talent_data['content'] : ''; ?></textarea>
                                        <?php echo form_error('content'); ?>
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <div class="col-sm-12" id="video_div">
                                        <label>YouTube Video&nbsp; <span class="example-link">e.g. https://www.youtube.com/watch?v=XXXXXXXXXXX</span></label>
                                        <input name="youtube_link" id="youtube_link" value="<?php echo !empty($talent_data['youtube_link']) ? 'https://www.youtube.com/watch?v=' . $talent_data['youtube_link'] : ''; ?>" class="invoice-fields" onblur="return youtube_check()" type="text">
                                        <?php echo form_error('youtube_link'); ?><br>
                                        <!--                                        <p id="video_link_error"></p>-->
                                    </div>
                                </div>
                                <br />
                                <?php if (isset($talent_data['picture']) && $talent_data['picture'] != '' && $talent_data['picture'] != NULL) { ?>
                                    <div class="row">
                                        <div class="col-sm-12  no-margin" id="pic_display">
                                            <div class="well well-sm">
                                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $talent_data['picture']; ?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                <?php } ?>





                                <?php if (isset($video_source)) { ?>
                                    <div class="form-group video_preview autoheight">
                                        <label>Video Preview </label>
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <?php if ($video['video_source'] == 'youtube') { ?>
                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $video_url ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            <?php } elseif ($video['video_source'] == 'vimeo') { ?>
                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $video_url ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            <?php } else { ?>
                                                <video controls style="width:100%; height:auto;">
                                                    <source src="<?php echo $video_url ?>" type='video/mp4'>
                                                </video>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (isset($video_source)) { ?>
                                    <div class="form-group edit_filter_check autoheight">
                                        <label class="control control--radio" style="margin-left:0px; margin-top:10px;">
                                            Change Video Source
                                            <input class="" type="radio" id="change_video_source" name="want_change" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                <?php } ?>
                                <div class="radio_btn_video_source">

                                    <div class="form-group edit_filter autoheight">



                                        <?php $field_name = 'video_source' ?>
                                        <?php $source = isset($video[$field_name]) && !empty($video[$field_name]) ? $video[$field_name] : 'youtube'; ?>
                                        <?php $temp = isset($video[$field_name]) && !empty($video[$field_name]) ? $video[$field_name] : 'youtube'; ?>
                                        <?php //echo form_label('Video Source', $field_name); 
                                        ?>
                                        <?php $default_selected = $temp == 'youtube' ? true : false; ?>
                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                            <?php echo YOUTUBE_VIDEO; ?>
                                            <input checked="checked" class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube" <?php if (isset($talent_data['youtube_link']) && $talent_data['youtube_link'] != '') {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <?php $default_selected = $temp == 'vimeo' ? true : false; ?>
                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                            <?php echo VIMEO_VIDEO; ?>
                                            <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo" <?php if (isset($talent_data['vimeo_link']) && $talent_data['vimeo_link'] != '') {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <?php $default_selected = $temp == 'upload' ? true : false; ?>
                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                            <?php echo UPLOAD_VIDEO; ?>
                                            <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload" <?php if (isset($talent_data['video']) && $talent_data['video'] != '') {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <?php if ($this->uri->segment(2) == 'edit_online_video') { ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                Do Not Change
                                                <input class="video_source" type="radio" id="do_not_change" name="video_source" value="do_not_change" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        <?php } ?>


                                        <label class="control control--radio">
                                            Show Image
                                            <input type="radio" value="picture" name="video_source" <?php if (isset($talent_data['picture']) && $talent_data['picture'] != '') {
                                                                                                        echo 'checked';
                                                                                                    } ?> class="video_source">
                                            <div class="control__indicator"></div>
                                        </label>

                                        <label class="control control--radio">
                                            None
                                            <input type="radio" value="none" name="video_source" <?php if ($talent_data['picture'] == '' && $talent_data['youtube_link'] == '' && $talent_data['vimeo_link'] == '' && $talent_data['video'] == '') {
                                                                                                        echo 'checked';
                                                                                                    } ?> class="video_source">
                                            <div class="control__indicator"></div>
                                        </label>


                                    </div>
                                </div>

                                <?php if (isset($video_source)) {
                                    if ($video_source == 'upload') { ?>
                                        <div class="radio_video_source_upload">
                                            <div class="form-group autoheight edit_filter" id="up_video_container">
                                                <label>Upload Video <span class="staric">*</span></label>
                                                <?php
                                                if (!empty($video['video_id']) && $video['video_source'] == 'uploaded') {
                                                ?>
                                                    <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $video['video_id']; ?>">
                                                <?php
                                                } else {
                                                ?>
                                                    <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="">
                                                <?php
                                                }
                                                ?>
                                                <div class="upload-file form-control">
                                                    <span class="selected-file" id="name_video"></span>
                                                    <input type="file" name="video_upload" id="video" onchange="check_file('video')">
                                                    <a href="javascript:;">Choose Video</a>
                                                </div>
                                            </div>
                                            <div class="form-group autoheight edit_filter" id="yt_vm_video_container">
                                                <?php $field_name = 'video_id' ?>
                                                <?php echo form_label('Video Url <span class="staric">*</span>', $field_name); ?>
                                                <?php echo form_input($field_name, set_value($field_name, ''), 'class="form-control" id="' . $field_name . '" data-rule-required="true"'); ?>
                                                <?php echo form_error($field_name); ?>
                                            </div>
                                        </div>
                                        <?php if ($this->uri->segment(2) == 'edit_online_video') { ?>
                                            <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $old_upload_video; ?>">
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="radio_video_source_links">
                                            <div class="form-group autoheight edit_filter" id="yt_vm_video_container">
                                                <?php $field_name = 'video_id' ?>
                                                <?php echo form_label('Video Url <span class="staric">*</span>', $field_name); ?>
                                                <?php echo form_input($field_name, set_value($field_name, ''), 'class="form-control " id="' . $field_name . '" data-rule-required="true"'); ?>
                                                <?php echo form_error($field_name); ?>
                                            </div>
                                            <div class="form-group autoheight edit_filter" id="up_video_container">
                                                <label>Upload Video <span class="staric">*</span></label>
                                                <div class="upload-file form-control">
                                                    <span class="selected-file" id="name_video"></span>
                                                    <input type="file" name="video_upload" id="video" onchange="check_file('video')">
                                                    <a href="javascript:;">Choose Video</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }  ?>
                                <?php } else { ?>
                                    <div class="form-group autoheight" id="yt_vm_video_container">
                                        <?php $field_name = 'video_id' ?>
                                        <?php echo form_label('Video Url <span class="staric">*</span>', $field_name); ?>
                                        <?php echo form_input($field_name, set_value($field_name, $video_url), 'class="form-control" id="' . $field_name . '" data-rule-required="true"'); ?>
                                        <?php echo form_error($field_name); ?>
                                    </div>
                                    <div class="form-group autoheight" id="up_video_container">
                                        <label>Upload Video <span class="staric">*</span></label>
                                        <div class="upload-file form-control">
                                            <span class="selected-file" id="name_video">No video selected</span>
                                            <input type="file" name="video_upload" id="video" onchange="check_file('video')" <?php echo set_value($video_url); ?>>
                                            <a href="javascript:;">Choose Video</a>
                                        </div>
                                    </div>
                                <?php }  ?>


                                <div class="row">
                                    <div class="col-sm-12" id="picture_div">
                                        <label>upload image</label>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_pictures">No file selected</span>
                                            <input name="pictures" id="pictures" onchange="check_file('pictures')" type="file">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </div>
                                </div>



                                <br />
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Make Applicant For This Job Fair Only Visible To Following Employees:</label>
                                        <div class="">
                                            <select id="jsVE" name="visibility[]" multiple>
                                                <?php if (!empty($employees)) {
                                                    $ids = !empty($talent_data['visibility_employees']) ?
                                                        explode(',', $talent_data['visibility_employees']) : [];

                                                    foreach ($employees as $emp) {
                                                ?>
                                                        <option value="<?= $emp['sid']; ?>" <?= in_array($emp['sid'], $ids) ? 'selected="true"' : ''; ?>><?= remakeEmployeeName($emp); ?> </option>
                                                <?php
                                                    }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-12 ">
                                        <input value="Save" class="submit-btn" type="submit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe") {
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
        $('#jsVE').select2({
            closeOnSelect: false
        });
        //var value = $("input[name='picture_or_video']:checked").val();
      //  var value = $(".video_source").val();
       

       //alert(value);
       $('#picture_div').hide();

       $('#yt_vm_video_container').hide();
       $('#up_video_container').hide();

       



       <?php if($talent_data['picture']!=''){?>
         display('picture');
         <?php }?>

    });

    $("input[type='radio']").click(function() {
        var value = $("input[name='picture_or_video']:checked").val();
        //  display(value);
    });



    <?php if (isset($video_source)) { ?>
        $('.radio_btn_video_source').hide();
        <?php if ($video_source == 'upload') { ?>
            $('#yt_vm_video_container input').prop('disabled', true);
            $('#yt_vm_video_container').hide();

            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();

        <?php } else { ?>
            $('#yt_vm_video_container input').prop('disabled', true);
            $('#yt_vm_video_container').hide();

            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();
            $('#add_edit_submit').removeAttr('onClick');
        <?php }
    } else { ?>
        $('#up_video_container input').prop('disabled', true);
        $('#up_video_container').hide();
        $('#add_edit_submit').removeAttr('onClick');
    <?php } ?>




    //
    $('.video_source').on('click', function() {
        var selected = $(this).val();
        if (selected == 'youtube') {
            $('#picture_div').hide();

            $('#yt_vm_video_container input').prop('disabled', false);
            $('#yt_vm_video_container').show();

            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();
            $('#add_edit_submit').removeAttr('onClick');
        } else if (selected == 'vimeo') {
            $('#picture_div').hide();

            $('#yt_vm_video_container input').prop('disabled', false);
            $('#yt_vm_video_container').show();

            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();
            $('#add_edit_submit').removeAttr('onClick');
        } else if (selected == 'upload') {
            $('#picture_div').hide();

            $('#yt_vm_video_container input').prop('disabled', true);
            $('#yt_vm_video_container').hide();

            $('#up_video_container input').prop('disabled', false);
            $('#up_video_container').show();

            $('#add_edit_submit').attr('onClick', 'check_file("video");');
        } else if (selected == 'do_not_change') {
            $('#up_video_container input').prop('disabled', true);
            $('#yt_vm_video_container input').prop('disabled', true);
            $('#add_edit_submit').removeAttr('onClick');
        } else if (selected == 'picture') {
            display('picture');

            $('#yt_vm_video_container input').prop('disabled', true);
            $('#yt_vm_video_container').hide();
            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();
            $('#add_edit_submit').removeAttr('onClick');

        } else if (selected == 'none') {
            //  display('picture');
            $('#picture_div').hide();

            $('#yt_vm_video_container input').prop('disabled', true);
            $('#yt_vm_video_container').hide();
            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();
            $('#add_edit_submit').removeAttr('onClick');
        }
    });



    //
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
</script>