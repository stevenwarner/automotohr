<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><a class="dashboard-link-btn" href="<?php echo base_url('customize_appearance/' . $theme) ?>"><i class="fa fa-chevron-left"></i>Back</a>Add Additional Section</span>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="tabs-wrp paid-theme">
                                    <div class="tab_container">
                                        <div class="panel-group-wrp">
                                            <div class="panel-group">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="universal-form-style-v2">

                                                                <form id="box" method="post" enctype="multipart/form-data">
                                                                    <ul>
                                                                        <li class="form-col-50-left">
                                                                            <label>Title<!--<span class="staric">*</span>--></label>
                                                                            <?php echo form_input('title', set_value('title'), 'class="invoice-fields"'); ?>
                                                                            <?php echo form_error('title'); ?>
                                                                        </li>
                                                                        <li class="form-col-50-right">
                                                                            <label>Status</label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="status">
                                                                                    <option value="0">In Active</option>
                                                                                    <option value="1">Active</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                        <li class="form-col-100 autoheight">
                                                                            <label for="footer_content">Content<!--<span class="staric">*</span>--></label>
                                                                            <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                                            <textarea class="ckeditor" name="content" rows="8" cols="60"><?php echo set_value('content', $box['content']); ?></textarea>
                                                                        </li>



                                                                        <div id="jsHomeImg">

                                                                            <li class="form-col-50-left">
                                                                                <label>Image</label>
                                                                                <div class="upload-file invoice-fields">
                                                                                    <span class="selected-file" class="name_image">No file selected</span>
                                                                                    <input type="file" name="image" id="image" class="image">
                                                                                    <a href="javascript:;">Choose File</a>
                                                                                </div>
                                                                            </li>
                                                                        </div>


                                                                        <li class="form-col-50-right">
                                                                            <label>Column Type</label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="column_type">
                                                                                    <option value="left_right">Left Right</option>
                                                                                    <option value="right_left">Right Left</option>
                                                                                    <option value="top_down">Top Down</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>


                                                                        <div id="jsHomeYoutube">
                                                                            <li class="form-col-100">
                                                                                <label>Youtube Video URL </label>
                                                                                <?php echo form_input('video', set_value('video', ''), 'class="invoice-fields video-url"'); ?>
                                                                                <?php echo form_error('video'); ?>
                                                                            </li>
                                                                        </div>


                                                                        <div id="jsHomeVimeo">
                                                                            <li class="form-col-100">
                                                                                <label>Vimeo Video URL</label>
                                                                                <?php echo form_input('vimeo_video', set_value('vimeo_video', ''), 'class="invoice-fields video-url"'); ?>
                                                                                <?php echo form_error('vimeo_video'); ?>
                                                                            </li>

                                                                        </div>


                                                                        <div id="jsHomeUploaded">
                                                                            <li class="form-col-100">
                                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="up_video_container">
                                                                                    <label for="uploaded_video_section_1">Upload Video<span class="hr-required">*</span></label>
                                                                                    <div class="upload-file invoice-fields">
                                                                                        <span class="selected-file" id="name_uploaded_video_section_1"></span>
                                                                                        <input type="file" name="uploaded_video_section_02" id="uploaded_video_section_1" onchange="check_video_file('uploaded_video_section_1');">
                                                                                        <a href="javascript:;">Choose Video</a>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </div>


                                                                        <li class="form-col-60-left">
                                                                            <label>Show Image/Video</label>
                                                                            <div class="hr-box-body hr-innerpadding">
                                                                                <div class="row">
                                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                        <label class="control control--radio">
                                                                                            Youtube Video
                                                                                            <input type="radio" name="show_video_or_image" id="show_video_1" value="video">
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>


                                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                        <label class="control control--radio">
                                                                                            Vimeo Video
                                                                                            <input type="radio" name="show_video_or_image" id="show_vimeo_video_1" value="vimeo_video">
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                        <label class="control control--radio">
                                                                                            Uploaded Video
                                                                                            <input type="radio" name="show_video_or_image" id="show_uploaded_video_1" value="uploaded_video">
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>


                                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                                        <label class="control control--radio">
                                                                                            Image
                                                                                            <input type="radio" name="show_video_or_image" id="show_image_1" value="image">
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                     
                                                                    </ul>
                                                                    <div class="btn-panel text-right">
                                                                        <input type="submit" class="btn btn-success" value="Save Section" name="perform_action" />
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Testimonials Modal -->
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/additional-methods.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.image').on('change', function() {
            var fileName = $(this).val();
            if (fileName.length > 0) {
                $(this).prev().html(fileName.substring(0, 45));
            } else {
                $(this).prev().html('No file selected');
            }
        });
    });

    $(function() {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#box").validate({
            ignore: ":hidden:not(select)",
            rules: {
                content: {
                    required: function() {
                        CKEDITOR.instances.content.updateElement();
                    }
                },
                status: {
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
                video: {
                    pattern: 'Provide a valid Youtube video Url(i.e. https://www.youtube.com/watch?v=xxxxxxxxxxx )'
                }
            },
            submitHandler: function(form) {
                //                var instances = $.trim(CKEDITOR.instances.content.getData());
                //                if (instances.length === 0) {
                //                    alertify.alert('Error! Content Missing', "Content cannot be Empty");
                //                    return false;
                //                }
                form.submit();
            }
        });
    });


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


    function check_video_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
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

        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
        }
    }




    $("#show_video_1").click(function() {
        var sectionValues = $(this).val();
        if (sectionValues == 'video') {
            $("#jsHomeImg").hide();
            $("#jsHomeYoutube").show();
            $("#jsHomeVimeo").hide();
            $("#jsHomeUploaded").hide();
        }

    });
    $("#show_vimeo_video_1").click(function() {
        var sectionValues = $(this).val();

        if (sectionValues == 'vimeo_video') {
            $("#jsHomeImg_1").hide();
            $("#jsHomeYoutube").hide();
            $("#jsHomeVimeo").show();
            $("#jsHomeUploaded").hide();
        }

    });
    $("#show_uploaded_video_1").click(function() {
        var sectionValues = $(this).val();
        if (sectionValues == 'uploaded_video') {
            $("#jsHomeImg").hide();
            $("#jsHomeYoutube").hide();
            $("#jsHomeVimeo").hide();
            $("#jsHomeUploaded").show();
        }
    });
    $("#show_image_1").click(function() {
        var sectionValues = $(this).val();
        if (sectionValues == 'image') {
            $("#jsHomeImg").show();
            $("#jsHomeYoutube").hide();
            $("#jsHomeVimeo").hide();
            $("#jsHomeUploaded").hide();
        }
    });

  // $('input:radio[id=show_video_1]').prop('checked', true);

   $("#jsHomeImg").hide();
    $("#jsHomeVimeo").hide();
    $("#jsHomeUploaded").hide();
</script>