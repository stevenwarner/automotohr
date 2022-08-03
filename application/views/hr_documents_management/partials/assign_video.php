<div class="row">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                <strong>Assign Video:</strong>
            </div>
            <div class="hr-innerpadding">
                <div class="universal-form-style-v2">
                    <ul>
                        <?php if (isset($document_info['video_source']) && !empty($document_info['video_source']) && $document_info['video_required'] == 1) { ?>
                            <input type="hidden" id="old_doc_video_url" value="<?php echo $document_info['video_url']; ?>">
                            <input type="hidden" id="old_doc_video_source" value="<?php echo $document_info['video_source']; ?>">
                            <li class="form-col-100 autoheight" style="width:100%; height:500px !important;">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <figure class="">
                                        <?php $source = $document_info['video_source']; ?>
                                        <?php if ($source == 'youtube') { ?>
                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $document_info['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                        <?php } elseif ($source == 'vimeo') { ?>
                                            <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $document_info['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                        <?php } else { ?>
                                            <video controls style="width:100%; height:500px !important;">
                                                <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $document_info['video_url']; ?>" type='video/mp4'>
                                                <p class="vjs-no-js">
                                                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                                </p>
                                            </video>
                                        <?php } ?>
                                    </figure>
                                </div>
                            </li>
                        <?php } ?>
                        <li class="form-col-100 autoheight edit_filter">
                            <label for="video_source">Video Source</label>
                            <?php $document_video_source = 'not_required';

                            if (isset($document_info['video_required']) && $document_info['video_required'] == 1) {
                                $document_video_source = $document_info['video_source'];
                            }
                            ?>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                <?php echo NO_VIDEO; ?>
                                <input class="video_source" type="radio" id="video_source_youtube" name="video_source" <?php echo $document_video_source == 'not_required' ? 'checked="checked"' : ''; ?> value="not_required">
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                <?php echo YOUTUBE_VIDEO; ?>
                                <input class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube" <?php echo $document_video_source == 'youtube' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                <?php echo VIMEO_VIDEO; ?>
                                <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo" <?php echo $document_video_source == 'vimeo' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                <?php echo UPLOAD_VIDEO; ?>
                                <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload" <?php echo $document_video_source == 'upload' ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </li>
                        <li class="form-col-100" id="yt_vm_video_container">
                            <input type="text" name="yt_vm_video_url" value="" class="invoice-fields" id="yt_vm_video_url">
                            <?php echo form_error('yt_vm_video_url'); ?>
                        </li>
                        <li class="form-col-100 autoheight edit_filter" id="up_video_container" style="display: none">
                            <?php
                            if (!empty($document_info['video_url']) && $document_info['video_source'] == 'upload') {
                            ?>
                                <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $document_info['video_url']; ?>">
                            <?php
                            } else {
                            ?>
                                <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="">
                            <?php
                            }
                            ?>
                            <div class="upload-file invoice-fields">
                                <span class="selected-file" id="name_video_upload"></span>
                                <input type="file" name="video_upload" id="video_upload" onchange="video_check('video_upload')">
                                <a href="javascript:;">Choose Video</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>