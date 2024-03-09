<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title); ?>"><?php echo $panel_title; ?> <span class="glyphicon glyphicon-plus"></span></a>
        </h4>
    </div>
    <div id="collapse-<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title); ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="universal-form-style-v2">
                        <form id="box-<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title); ?>" method="post" enctype="multipart/form-data">
                            <ul>
                                <li class="form-col-50-left">
                                    <label>Title<!--<span class="staric">*</span>--></label>
                                    <?php echo form_input('title', set_value('title', $box['title']), 'class="invoice-fields"'); ?>
                                    <?php echo form_error('title'); ?>
                                </li>
                                <li class="form-col-50-right">
                                    <label>Status</label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="status">
                                            <option value="0" <?php echo  !$box['status'] ? 'selected="selected"' : ''; ?>>In Active</option>
                                            <option value="1" <?php echo  $box['status'] ? 'selected="selected"' : ''; ?>>Active</option>
                                        </select>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label for="footer_content">Content<!--<span class="staric">*</span>--></label>
                                    <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                    <textarea class="ckeditor" id="<?php echo $box['sid'] ?>" name="content" rows="8" cols="60"><?php echo set_value('content', $box['content']); ?></textarea>
                                </li>
                                <div id="jsHomeImg_<?php echo $box['sid'] ?>">
                                    <li class="form-col-100 autoheight">
                                        <label>Banner Image</label>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php if (isset($box['show_video_or_image']) &&  $box['show_video_or_image']=='image' && $box['image'] != '') { ?>
                                                    <div class="well well-sm">
                                                        <img style="width: 100%;" class="img-responsive" src="<?php echo (isset($box['image']) ? AWS_S3_BUCKET_URL . $box['image'] : ''); ?>" alt="" />
                                                    </div>
                                                <?php } else {
                                                    echo '<h3>No Banner Uploaded</h3>';
                                                } ?>
                                            </div>
                                        </div>
                                    </li>
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
                                            <option value="left_right" <?php echo $box['column_type'] == "left_right" ? "selected='selected'" : '' ?>>Left Right</option>
                                            <option value="right_left" <?php echo $box['column_type'] == "right_left" ? "selected='selected'" : '' ?>>Right Left</option>
                                            <option value="top_down" <?php echo $box['column_type'] == "top_down" ? "selected='selected'" : '' ?>>Top Down</option>
                                        </select>
                                    </div>
                                </li>


                                <div id="jsHomeYoutube_<?php echo $box['sid'] ?>">
                                    <?php $video_url = isset($box['show_video_or_image']) && $box['show_video_or_image']=='video' &&  !empty($box['video']) ? 'https://www.youtube.com/embed/' . $box['video'] : ''; ?>
                                    <?php if (!empty($video_url)) { ?>
                                        <li class="form-col-100 autoheight">
                                            <h2>Youtube Video</h2>
                                            <div class="well well-sm">
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" src="<?php echo $video_url; ?>"></iframe>
                                                </div>
                                            </div>
                                            <br />
                                        </li>
                                    <?php } ?>
                                    <li class="form-col-100">
                                        <label>Youtube Video URL </label>
                                        <?php $video_url = isset($box['show_video_or_image']) && $box['show_video_or_image']=='video' && !empty($box['video']) ? 'https://www.youtube.com/watch?v=' . $box['video'] : ''; ?>
                                        <?php echo form_input('video', set_value('video', $video_url), 'class="invoice-fields video-url"'); ?>
                                        <?php echo form_error('video'); ?>
                                    </li>
                                </div>



                                <div id="jsHomeVimeo_<?php echo $box['sid'] ?>">

                                    <?php $vimeo_video_url = isset($box['show_video_or_image']) && $box['show_video_or_image']=='vimeo_video' && !empty($box['video']) ? 'https://player.vimeo.com/video/' . $box['video'] : ''; ?>
                                    <?php if (!empty($vimeo_video_url)) { ?>
                                        <li class="form-col-100 autoheight">
                                            <h2>Vimeo Video</h2>
                                            <div class="well well-sm">
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe id="vimeo_player" src="<?php echo $vimeo_video_url; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                </div>
                                            </div>
                                            <br />
                                        </li>
                                    <?php } ?>

                                    <li class="form-col-100">
                                        <label>Vimeo Video URL</label>
                                        <?php $vimeo_video_url = isset($box['show_video_or_image']) && $box['show_video_or_image']=='vimeo_video' && !empty($box['video']) ? 'https://vimeo.com/' . $box['video'] : ''; ?>
                                        <?php echo form_input('vimeo_video', set_value('vimeo_video', $vimeo_video_url), 'class="invoice-fields video-url"'); ?>
                                        <?php echo form_error('vimeo_video'); ?>
                                    </li>

                                </div>


                                <div id="jsHomeUploaded_<?php echo $box['sid'] ?>">
                                

                                    <?php if (isset($box['show_video_or_image']) && $box['show_video_or_image']=='uploaded_video' && !empty($box['video'])) { ?>
                                        <li class="form-col-100 autoheight">
                                            <input type="hidden" value="<?php echo $box['video']; ?>" name="uploaded_video_section_02_old">

                                            <div class="well well-sm">
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <video controls width="100%">
                                                        <source src="<?php echo base_url('assets/uploaded_videos/' . $box['video']); ?>" type='video/mp4'>
                                                    </video>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>

                                    <li class="form-col-100">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="up_video_container">
                                            <label for="uploaded_video_section_<?php echo $box['sid'] ?>">Upload Video<span class="hr-required">*</span></label>
                                            <div class="upload-file invoice-fields">
                                                <span class="selected-file" id="name_uploaded_video_section_<?php echo $box['sid'] ?>"></span>
                                                <input type="file" name="uploaded_video_section_02" id="uploaded_video_section_<?php echo $box['sid'] ?>" onchange="check_video_file('uploaded_video_section_<?php echo $box['sid'] ?>');">
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
                                                    <input type="radio" name="show_video_or_image" id="show_video_<?php echo $box['sid'] ?>" value="video" <?php echo $box['show_video_or_image'] == 'video' ? 'checked="checked"' : ''; ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>


                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label class="control control--radio">
                                                    Vimeo Video
                                                    <input type="radio" name="show_video_or_image" id="show_vimeo_video_<?php echo $box['sid'] ?>" value="vimeo_video" <?php echo $box['show_video_or_image'] == 'vimeo_video' ? 'checked="checked"' : ''; ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label class="control control--radio">
                                                    Uploaded Video
                                                    <input type="radio" name="show_video_or_image" id="show_uploaded_video_<?php echo $box['sid'] ?>" value="uploaded_video" <?php echo $box['show_video_or_image'] == 'uploaded_video' ? 'checked="checked"' : ''; ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>


                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                <label class="control control--radio">
                                                    Image
                                                    <input type="radio" name="show_video_or_image" id="show_image_<?php echo $box['sid'] ?>" value="image" <?php echo $box['show_video_or_image'] == 'image' ? 'checked="checked"' : ''; ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="do_show_image" <?php echo !isset($box['do_show_image']) || $box['do_show_image'] == 'on' ? 'checked="checked"' : ''; ?> /> Enable banner/video
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>
                            </ul>
                            <div class="btn-panel text-right">
                                <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>" />
                                <input type="hidden" name="box-sid" value="<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title); ?>">
                                <input type="hidden" id="page_name" name="page_name" value="home" />
                                <input type="hidden" id="perform_action" name="perform_action" value="<?php echo $perform_action; ?>" />
                                <input type="hidden" id="section_id" name="section_id" value="<?php echo $section_id; ?>" />
                                <button type="submit" class="btn btn-success">Save Section</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

        //        $(function () {
        //            $.validator.setDefaults({
        //                debug: true,
        //                success: "valid"
        //            });
        $("#box-" + "<?php echo isset($box['sid']) ? $box['sid'] : str_replace(' ', '_', $panel_title); ?>").validate({
            ignore: ":hidden:not(select)",
            rules: {
                status: {
                    required: true
                },
                //                    title: {
                //                        required: true
                //                    },
                video: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            },
            messages: {
                status: {
                    required: 'Status is required'
                },
                //                    title: {
                //                        required: 'Title is required'
                //                    },
                video: {
                    pattern: 'Provide a valid Youtube video Url(i.e. https://www.youtube.com/watch?v=xxxxxxxxxxx )'
                }
            },
            submitHandler: function(form) {
                //                var instances = $.trim(CKEDITOR.instances[<?= $box['sid'] ?>].getData());
                //                if (instances.length === 0) {
                //                    alertify.error('Error! Content Missing', "Content cannot be Empty");
                //                    return false;
                //                }
                form.submit();
            }
        });
        //        });
    });




    $("<?php echo '#show_video_' . $box['sid'] ?>").click(function() {
        var sectionValues = $(this).val();

        if (sectionValues == 'video') {
            $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").show();
            $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").hide();
        }

    });
    $("<?php echo '#show_vimeo_video_' . $box['sid'] ?>").click(function() {
        var sectionValues = $(this).val();

        if (sectionValues == 'vimeo_video') {
            $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").show();
            $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").hide();
        }


    });
    $("<?php echo '#show_uploaded_video_' . $box['sid'] ?>").click(function() {
        var sectionValues = $(this).val();
        if (sectionValues == 'uploaded_video') {
            $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").show();
        }
    });
    $("<?php echo '#show_image_' . $box['sid'] ?>").click(function() {
        var sectionValues = $(this).val();
        if (sectionValues == 'image') {
            $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").show();
            $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").hide();
            $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").hide();
        }
    });


    //

    <?php if ($box['show_video_or_image'] == 'video') { ?>
        $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").show();
        $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").hide();

    <? } ?>
    <?php if ($box['show_video_or_image'] == 'image') { ?>
        $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").show();
        $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").hide();
    <?php } ?>

    <?php if ($box['show_video_or_image'] == 'vimeo_video') { ?>
        $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").show();
        $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").hide();

    <? } ?>
    <?php if ($box['show_video_or_image'] == 'uploaded_video') { ?>
        $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").show();

    <? } ?>


    <?php if ($box['show_video_or_image'] == '') { ?>
        $("<?php echo '#jsHomeImg_' . $box['sid'] ?>").show();
        $("<?php echo '#jsHomeYoutube_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeVimeo_' . $box['sid'] ?>").hide();
        $("<?php echo '#jsHomeUploaded_' . $box['sid'] ?>").hide();
    <?php } ?>



</script>