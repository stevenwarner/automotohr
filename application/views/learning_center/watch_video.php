
<?php if (!$load_view) { ?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
<!--                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">-->
<!--                    --><?php //$this->load->view('main/employer_column_left_view'); ?>
<!--                </div>-->
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php if($top_view) {
                        $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top');
                    }
                    else { ?>

                        <div class="application-header">
                                <article>
                                    <figure>
                                        <img src="<?php echo AWS_S3_BUCKET_URL;
                                        if (isset($applicant_info['pictures']) && $applicant_info['pictures'] != "") {
                                            echo $applicant_info['pictures'];
                                        } else {
                                            ?>default_pic-ySWxT.jpg<?php } ?>" alt="Profile Picture">
                                    </figure>
                                    <div class="text">
                                        <h2><?php echo $applicant_info["first_name"]; ?> <?= $applicant_info["last_name"] ?></h2>

                                        <div class="start-rating">
                                            <input readonly="readonly"
                                                   id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?>
                                                   type="number" name="rating" class="rating" min=0 max=5
                                                   step=0.2
                                                   data-size="xs">
                                        </div>
                                        <?php if (check_blue_panel_status() && $applicant_info['is_onboarding'] == 1) { ?>
                                            <span class="badge" style="padding:8px; background-color: red;">Onboarding Request Sent</span>
                                        <?php } else { ?>
                                            <span class=""
                                                  style="padding:8px;"><?php echo $applicant_info["applicant_type"]; ?></span>
                                        <?php } ?>
                                    </div>
                                </article>
                            </div>
                    <?php }?>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $back_url; ?>"><i class="fa fa-chevron-left"></i>Online Videos</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="well well-sm">
                                <?php if($video['video_source'] == 'youtube') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $video['video_id']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } else if($video['video_source'] == 'vimeo') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $video['video_id']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } else { ?>
                                    <video controls style="width:100%; height:auto;">
                                        <source src="<?php echo base_url('assets/uploaded_videos/').$video['video_id']; ?>" type='video/mp4'>
                                    </video>
                                <?php } ?>    
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <strong style="font-size: 20px;"><?php echo $video['video_title']; ?></strong>
                                    <p><?php echo $video['video_description']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"></div>
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <form id="form_mark_video_as_watched" enctype="multipart/form-data" method="post">
                                <input type="hidden" id="perform_action" name="perform_action" value="mark_video_as_watched" />
                                <input type="hidden" id="video_sid" name="video_sid" value="<?php echo $video['sid']; ?>" />
                                <input type="hidden" id="user_type" name="user_type" value=<?= $user_type?> />
                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $employer_sid; ?>" />
                            </form>
                            <?php if($assignment['watched'] == 0) { ?>
<!--                                <button type="button" class="btn btn-success btn-block" onclick="func_mark_video_as_watched();">Mark as Watched</button>-->
                            <?php } else { ?>
<!--                                <button type="button" class="btn btn-success btn-block disabled" disabled="disabled">Watched on --><?php //echo DateTime::createFromFormat('Y-m-d H:i:s', $assignment['date_watched'])->format('m-d-Y h:i A'); ?><!--</button>-->
                            <?php } ?>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"></div>
                    </div>
                </div>
                <?php if(isset($left_navigation)){
                    $this->load->view($left_navigation);
                }?>
            </div>
        </div>
    </div>
</div>

<script>
    function func_mark_video_as_watched() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to mark this video as watched?',
            function () {
                $('#form_mark_video_as_watched').submit();
            },
            function () {
                alertify.error('Cancelled!');
            }
        );
    }
</script>

<?php } else { ?>
    <?php $this->load->view('learning_center/watch_video_new'); ?>
<?php } ?>