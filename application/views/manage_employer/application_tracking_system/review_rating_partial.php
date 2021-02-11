<div class="universal-form-style-v2">
    <div class="form-title-section">
        <h2>Reviews and Ratings</h2>
        <div class="form-btns">
        </div>
    </div>
<?php   $review_popup_view = check_access_permissions_for_view($security_details, 'review_score'); 
        
        if($review_popup_view == true) { ?>
            <div class="hr-widget">
                <div class="start-rating text-left">
                    <form action="<?php echo base_url('applicant_profile/save_rating'); ?>" id="js-review-form" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="applicant_job_sid" value="<?= $id ?>" >
                        <input type="hidden" name="applicant_email" value="<?= $email ?>" >
                        <input type="hidden" name="users_type" value="applicant" >
                        
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Rate This Applicant</label>
                                <input id="input-21b" <?php if (!empty($applicant_rating)) { ?> value="<?php echo $applicant_rating['rating']; ?>" <?php } ?> type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>Comment <span class="red">*</span></label>
                                    <textarea id="rating_comment" name="comment" class="form-fields" required><?php echo !empty($applicant_rating) ? $applicant_rating['comment'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
<?php                   if(!empty($applicant_rating['rating'])){ ?>
                            <div class="row" style="margin-top: 25px;">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                    <?php $attachment = $applicant_rating['attachment']; ?>
                                    <?php $extension = $applicant_rating['attachment_extension']; ?>
                                    <label class="autoheight">Current Attachment : <?php echo !empty($attachment) ? '<span class="text-success">' . $attachment . '</span>' : '<span class="text-danger">None</span>';?></label>
                                    <br />
                                    <br />
                                    <div class="clearfix"></div>
                                    
<?php                               if(!empty($attachment)) { ?>
                                        <div class="well well-sm text-center">
<?php                                       if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpe' || $extension == 'jpeg' || $extension == 'gif') { ?>
                                                <div class="img-thumbnail" style="max-width: 800px;">
                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" />
                                                </div>
<?php                                       } elseif($extension == 'doc' || $extension == 'docx') { ?>
                                                <iframe style="width: 100%; height: 600px" class="uploaded-file-preview" src="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo AWS_S3_BUCKET_URL . $attachment ?>" frameborder="0"></iframe>
<?php                                       } elseif($extension == 'mp3' || $extension == 'aac') { ?>
                                                <audio width="800" controls>
                                                    <?php if($extension == 'mp3') { ?>
                                                        <source src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" type="audio/mpeg">
                                                    <?php } else if($extension == 'ogg') { ?>
                                                        <source src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" type="audio/ogg">
                                                    <?php } else if($extension == 'wav') { ?>
                                                        <source src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" type="audio/wav">
                                                    <?php } ?>
                                                    Your browser does not support the audio element.
                                                </audio>
<?php                                       } elseif($extension == 'pdf') { ?>
                                                <iframe style="width: 100%; height: 600px" class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . $attachment ?>&embedded=true" frameborder="0"></iframe>
<?php                                       } ?>

                                            <br />
                                            <br />
                                            <a class="btn btn-success" href="<?php echo base_url('applicant_profile/downloadFile/' . $attachment); ?>">Download Attachment</a>
                                            <br />
                                            <br />
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php 
                            $sourceType = $applicant_rating['source_type'];
                            $sourceValue = $applicant_rating['source_value'];
                        ?>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Attachment</label>
                                <input type="file" class="filestyle" id="review_attachment" name="review_attachment" />
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3" style="padding: 0px;">
                                    <label for="YouTubeVideo">Select Video:</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <label class="control control--radio"><?php echo NO_VIDEO; ?>
                                                <input type="radio" name="video_source" class="review_video_source" value="no_video" <?=$sourceType == 'no_video' ? 'checked="true"' : '';?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <label class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                                <input type="radio" name="video_source" class="review_video_source" value="youtube" <?=$sourceType == 'youtube' ? 'checked="true"' : '';?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <label class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                                <input type="radio" name="video_source" class="review_video_source" value="vimeo" <?=$sourceType == 'vimeo' ? 'checked="true"' : '';?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <label class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                                <input type="radio" name="video_source" class="review_video_source" value="uploaded" <?=$sourceType == 'uploaded' ? 'checked="true"' : '';?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="review_youtube_vimeo_input">
                                <label for="YouTube_Video" id="review_label_youtube">Youtube Video For This Job:</label>
                                <label for="Vimeo_Video" id="review_label_vimeo" style="display: none">Vimeo Video For This Job:</label>
                                <input type="text" name="yt_vm_video_url"  class="invoice-fields" id="review_yt_vm_video_url" value="<?=$sourceType != 'uploaded' && $sourceValue != '' ? $sourceValue : '';?>" />
                                <div id="review_YouTube_Video_hint" class="video-link" style='font-style: italic;'><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX OR https://www.youtube.com/embed/XXXXXXXXXXX </div>
                                <div id="review_Vimeo_Video_hint"  class="video-link" style='font-style: italic; display: none'><b>e.g.</b> https://vimeo.com/XXXXXXX OR https://player.vimeo.com/video/XXXXXXX </div> 
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight" id="review_upload_input">
                                <label for="YouTubeVideo">Upload Video For This Job:</label>          
                                <div class="upload-file invoice-fields">
                                    <span class="selected-file" id="review_name_upload_video">No video selected</span>
                                    <input name="upload_video" id="review_upload_video" onchange="upload_video_checker('review_upload_video')" type="file">
                                    <a href="javascript:;">Choose Video</a>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="submit" value="submit" class="btn btn-success">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
<?php   } else { ?>
        <div class="applicant-notes">
            <span class="notes-not-found "><b>You are not Authorised</b></span>
        </div>
<?php }?>
    
<?php if($review_popup_view == true) { ?>
    <hr />

    <?php if ($applicant_ratings_count !== NULL) { ?>
        <div class="start-rating yellow-stars">
            <input readonly="readonly" id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?>
                   type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
            <p class="rating-count"><?php echo $applicant_average_rating; ?></p>
            <p><?php echo $applicant_ratings_count; ?> review(s)</p>
        </div>
        <div class="tab-header-sec">
            <p class="questionnaire-heading">Rating By All Employers</p>
        </div>
        <div class="attachements-wrp full-width">
            <?php foreach ($applicant_all_ratings as $rating) { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="well well-sm">
                            <h2 class="text-left" style="margin-top: 0;"><?php echo $rating['first_name'].'&nbsp;'.$rating['last_name']; ?></h2>
                            <div class="start-rating">
                                <input readonly="readonly" id="input-21b"
                                       value="<?php echo $rating['rating']; ?>" type="number"
                                       name="rating" class="rating" min=0 max=5 step=0.2
                                       data-size="xs">
                            </div>
                            <p><?php echo $rating['comment']; ?></p>
                            <p><?=reset_datetime(array( 'datetime' => $rating['date_added'], '_this' => $this, 'from_format' => 'b d Y H:i a', 'format' => 'default')); ?></p>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                        <?php $attachment = $rating['attachment']; ?>
                        <?php $extension = $rating['attachment_extension']; ?>
                        <div class="clearfix"></div>
                        <?php if(!empty($attachment)) { ?>
                            <div class="well well-sm text-center">
                                <?php if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpe' || $extension == 'jpeg' || $extension == 'gif') { ?>
                                    <div class="img-thumbnail" style="max-width: 100%;">
                                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" />
                                    </div>
                                <?php } elseif($extension == 'doc' || $extension == 'docx') { ?>
                                    <iframe style="width: 100%; height: 600px" class="uploaded-file-preview" src="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo AWS_S3_BUCKET_URL . $attachment ?>" frameborder="0"></iframe>
                                <?php } elseif($extension == 'mp3' || $extension == 'aac') { ?>
                                    <audio controls>
                                        <?php if($extension == 'mp3') { ?>
                                            <source src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" type="audio/mpeg">
                                        <?php } else if($extension == 'ogg') { ?>
                                            <source src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" type="audio/ogg">
                                        <?php } else if($extension == 'wav') { ?>
                                            <source src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" type="audio/wav">
                                        <?php } ?>
                                        Your browser does not support the audio element.
                                    </audio>
                                <?php } elseif($extension == 'pdf') { ?>
                                    <iframe style="width: 100%; height: 600px" class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . $attachment ?>&embedded=true" frameborder="0"></iframe>
                                <?php } ?>
                                <a class="btn btn-success" href="<?php echo base_url('applicant_profile/downloadFile/' . $attachment); ?>">Download Attachment</a>
                            </div>
                        <?php } ?>
                        
                        <?php if($rating['source_value'] != ''){ ?>
                        <div class="well well-sm text-center">
                        <?php if($rating['source_type'] == 'youtube' || $rating['source_type'] == 'vimeo'){ ?>
                            <iframe style="width: 100%;" class="uploaded-file-preview" src="<?=$rating['source_value'];?>" frameborder="0"></iframe>
                        <?php }else if($rating['source_type'] == 'uploaded'){ ?>
                            <video controls autopla name="media"  style="width: 100%;" >
                                <source src="<?=AWS_S3_BUCKET_URL.$rating['source_value'];?>" type="video/mp4" >
                            </video>
                        <?php } ?>
                        </div>
                        <br />
                        <?php } ?>
                    </div>
                </div>
                <hr />
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="applicant-notes">
            <span class="notes-not-found ">No Review Found</span>
        </div>
    <?php } ?>
    <?php } ?>
</div>

