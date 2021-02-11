<div class="universal-form-style-v2">
    <div class="form-title-section">
        <h2>Reviews and Ratings</h2>
        <div class="form-btns">
        </div>
    </div>

    <div class="hr-widget">
        <div class="start-rating text-left">
            <form action="<?php echo base_url('applicant_profile/save_rating'); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="applicant_job_sid" value="<?= $id ?>" >
                <input type="hidden" name="applicant_email" value="<?= $email ?>" >
                <input type="hidden" name="users_type" value="employee" >

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
                <?php if(!empty($applicant_rating['rating'])){ ?>
                    <div class="row" style="margin-top: 25px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            <?php $attachment = $applicant_rating['attachment']; ?>
                            <?php $extension = $applicant_rating['attachment_extension']; ?>
                            <label>Current Attachment : <?php echo !empty($attachment) ? '<span class="text-success">' . $attachment . '</span>' : '<span class="text-danger">None</span>';?></label>
                            <br />
                            <br />
                            <div class="clearfix"></div>
                            <?php if(!empty($attachment)) { ?>
                                <div class="well well-sm text-center">
                                    <?php if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpe' || $extension == 'jpeg' || $extension == 'gif') { ?>
                                        <div class="img-thumbnail" style="max-width: 800px; max-height: 600px;">
                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $attachment; ?>" />
                                        </div>
                                    <?php } elseif($extension == 'doc' || $extension == 'docx') { ?>
                                        <iframe style="width: 100%; height: 600px" class="uploaded-file-preview" src="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo AWS_S3_BUCKET_URL . $attachment ?>" frameborder="0"></iframe>
                                    <?php } elseif($extension == 'mp3' || $extension == 'aac') { ?>
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
                                    <?php } elseif($extension == 'pdf') { ?>
                                        <iframe style="width: 100%; height: 600px" class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . $attachment ?>&embedded=true" frameborder="0"></iframe>
                                    <?php } ?>
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

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Attachment</label>
                        <input type="file" class="filestyle" id="review_attachment" name="review_attachment" />
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
        <div class="applicant-notes">
            <?php foreach ($applicant_all_ratings as $rating) { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="well well-sm">
                            <h2 class="text-left"><?php echo $rating['username']; ?></h2>
                            <div class="start-rating">
                                <input readonly="readonly" id="input-21b"
                                       value="<?php echo $rating['rating']; ?>" type="number"
                                       name="rating" class="rating" min=0 max=5 step=0.2
                                       data-size="xs">
                            </div>
                            <p><?php echo $rating['comment']; ?></p>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                        <?php $attachment = $rating['attachment']; ?>
                        <?php $extension = $rating['attachment_extension']; ?>
                        <div class="clearfix"></div>
                        <?php if(!empty($attachment)) { ?>
                            <div class="well well-sm text-center">
                                <?php if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpe' || $extension == 'jpeg' || $extension == 'gif') { ?>
                                    <div class="img-thumbnail" style="max-width: 100%; max-height: 600px;">
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
                                <br />
                                <a class="btn btn-success" href="<?php echo base_url('applicant_profile/downloadFile/' . $attachment); ?>">Download Attachment</a>

                            </div>
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
</div>