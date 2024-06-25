<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Section Four <span class="glyphicon glyphicon-plus"></span></a>

        </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">

                    <div>
                        <h2>Video</h2>
                        <div class="well well-sm">
                            <div class="embed-responsive embed-responsive-16by9">

                                <?php if (trim($section_04_meta['video']) != '') { ?>
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo (isset($section_04_meta['video']) && $section_04_meta['video'] != '' ? $section_04_meta['video'] : ''); ?>"></iframe>
                                <?php } ?>

                                <?php if (trim($section_04_meta['vimeo_video']) != '') { ?>
                                    <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo (isset($section_04_meta['vimeo_video']) && $section_04_meta['vimeo_video'] != '' ? $section_04_meta['vimeo_video'] : ''); ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                <?php } ?>

                                <?php if (trim($section_04_meta['uploaded_video']) != '') { ?>
                                    <video controls width="100%">
                                        <source src="<?php echo base_url('assets/uploaded_videos/' . $section_04_meta['uploaded_video']); ?>" type='video/mp4'>
                                    </video>
                                <?php } ?>
                            </div>

                        </div>
                        <br />
                        <div class="universal-form-style-v2">
                            <ul>
                                <form id="form_video_section_04" enctype="multipart/form-data" method="post">
                                    <!-- Required Hidden Fields Start -->
                                    <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>" />
                                    <input type="hidden" name="perform_action" id="perform_action" value="save_section_04_video" />
                                    <input type="hidden" id="page_name" name="page_name" value="home" />
                                    <!-- Required Hidden Fields end -->

                                    <li class="form-col-100 autoheight">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="video-link">
                                                <label for="video_source">Video Source</label>
                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                    <?php echo YOUTUBE_VIDEO; ?>
                                                    <input <?php echo (isset($section_04_meta['video']) && trim($section_04_meta['video']) != '' ? 'checked="checked"' : ''); ?> class="video_source_section_04" type="radio" id="video_source_youtube" name="video_source_section_04" value="youtube">
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                    <?php echo VIMEO_VIDEO; ?>
                                                    <input <?php echo (isset($section_04_meta['vimeo_video']) && trim($section_04_meta['vimeo_video']) != '' ? 'checked="checked"' : ''); ?> type="radio" id="video_source_vimeo_section_04" name="video_source_section_04" value="vimeo" class="video_source_section_04">
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                    <?php echo UPLOAD_VIDEO; ?>
                                                    <input <?php echo (isset($section_04_meta['uploaded_video']) && trim($section_04_meta['uploaded_video']) != '' ? 'checked="checked"' : ''); ?> class="video_source_section_04" type="radio" id="video_source_upload" name="video_source_section_04" value="upload">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $pre_source == 'upload' ? $job_fair_data['video_id'] : ''; ?>">
                                                <?php 
                                                    //
                                                    $previous_video_id = "";
                                                    //
                                                    if (isset($section_04_meta['video']) && trim($section_04_meta['video']) != '') {
                                                        $previous_video_id = "https://www.youtube.com/watch?v=".$section_04_meta['video'];
                                                    } else if (isset($section_04_meta['vimeo_video']) && trim($section_04_meta['vimeo_video']) != '') {
                                                        $previous_video_id = "https://vimeo.com/".$section_04_meta['vimeo_video'];
                                                    }
                                                ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="yt_vm_video_container">
                                                    <label for="YouTube_Video_Section_04" id="label_youtube">Youtube Video URL <span class="staric">*</span></label>
                                                    <label for="Vimeo_Video_Section_04" id="label_vimeo" style="display: none">Vimeo Video <span class="staric">*</span></label>
                                                    <input type="text" name="yt_vm_video_url_section_04" value="<?php echo $previous_video_id; ?>" class="invoice-fields" id="yt_vm_video_url_section_04">
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="up_video_container_section_04" style="display: none">
                                                    <label>Upload Video <span class="hr-required">*</span></label>
                                                    <div class="upload-file invoice-fields">
                                                        <span class="selected-file" id="name_video_upload_section_04"></span>
                                                        <input type="file" name="video_upload_section_04" id="video_upload_section_04" onchange="check_video_file('video_upload_section_04')">
                                                        <a href="javascript:;">Choose Video</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>


                                    <li class="form-col-100 autoheight">
                                        <div>
                                            <label for="status_section_04">Status</label>
                                            <input type="hidden" id="status_section_04" name="status_section_04" value="" />
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="status_section_04" id="status_section_04_enabled" value="1" <?php echo (isset($section_04_meta['status']) && intval($section_04_meta['status']) == 1 ? 'checked="checked"' : ''); ?> />
                                            <label for="status_section_04_enabled">
                                                Enabled
                                            </label>
                                        </div>
                                        <div class="questionair_radio_container">
                                            <input type="radio" name="status_section_04" id="status_section_04_disabled" value="0" <?php echo (isset($section_04_meta['status']) && intval($section_04_meta['status']) == 0 ? 'checked="checked"' : ''); ?> />
                                            <label for="status_section_04_disabled">
                                                Disabled
                                            </label>
                                        </div>

                                    </li>
                                </form>
                            </ul>
                        </div>
                        <div class="btn-panel">
                            <button type="button" class="delete-all-btn active-btn" onclick="fSaveVideoSection04();"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function fValidateVideoSection04() {
        $('#form_video_section_04').validate({
            rules: {
                yt_vm_video_url_section_04: {
                    required: true
                }
            }
        });
    }

    function fSaveVideoSection04() {

        let videoSource = $('input[name="video_source_section_04"]:checked').val();

        if (videoSource == 'upload') {
            let uploadVal = $("#video_upload_section_04").val();
            if (uploadVal == '') {
                alertify.error("No video selected");
                $('#name_video_upload_section_04').html('<p class="red">Please select video</p>');
                return;
            }
        }

        fValidateVideoSection04();

        if ($('#form_video_section_04').valid()) {
            $('#form_video_section_04').submit();
        }
    }


    $('.video_source_section_04').on('click', function() {
        var selected = $(this).val();
        if (selected == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#yt_vm_video_container').show();
            $('#up_video_container_section_04').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#yt_vm_video_container').show();
            $('#up_video_container_section_04').hide();
        } else if (selected == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container_section_04').show();
        }
    });
    <?php if (trim($section_04_meta['vimeo_video']) != '') { ?>
        $('#label_youtube').hide();
        $('#label_vimeo').show();
        $('#yt_vm_video_container').show();
        $('#up_video_container_section_04').hide();
    <?php } ?>

    <?php if (trim($section_04_meta['uploaded_video']) != '') { ?>
        $('#yt_vm_video_container').hide();
        $('#up_video_container_section_04').show();
    <?php } ?>

    <?php if (trim($section_04_meta['video']) != '') { ?>
        $('#label_youtube').show();
        $('#label_vimeo').hide();
        $('#yt_vm_video_container').show();
        $('#up_video_container_section_04').hide();
    <?php } ?>
</script>