<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
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
                                           type="number" name="rating" class="rating" min=0 max=5 step=0.2
                                           data-size="xs">
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="page-header-area margin-top">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <div class="row">
                                <div class="col-xs-4"></div>
                                <div class="col-xs-4">
                                    <?php echo $subtitle; ?>
                                </div>
                                <div class="col-xs-4"></div>
                            </div>
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <th class="col-xs-4">Name</th>
                                        <td class="col-xs-8"><?php echo $applicant_info["first_name"] . ' ' . $applicant_info["last_name"] ?></td>
                                    </tr>
                                    <tr>
                                        <th class="col-xs-4">Email</th>
                                        <td class="col-xs-8"><?php echo $applicant_info["email"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="col-xs-4">Phone Number</th>
                                        <td class="col-xs-8"><?=phonenumber_format($applicant_info["phone_number"]); ?></td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="applicant-respon-rating form-col-100">
                                <div class="start-rating">
                                    <form method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="save_applicant_video_question_rating" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                        <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_sid; ?>" />
                                        <input type="hidden" id="job_list_sid" name="job_list_sid" value="<?php echo $job_list_sid; ?>" />
                                        <input value="<?php echo $app_vq_rating; ?>" type="number" id="rating" name="rating" class="rating" min=0 max=5 step=0.2 data-size="md">
                                        <input class="btn btn-success btn-block" type="submit" value="Save Overall Rating">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php foreach($questions as $question) { ?>
                                <div class="hr-box">
                                    <div class="hr-box-header">
                                        <h3 class="hr-registered">
                                            <strong>Q:</strong> <?php echo $question['question_type'] == 'text' ? $question['question_text'] : $question['video_title']?>
                                        </h3>
                                    </div>
                                    <div class="hr-box-body hr-innerpadding">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <div class="list-group">
                                                    <?php foreach($question['sent'] as $sent_question) { ?>
                                                        <?php if($sent_question['status'] != 'unanswered') { ?>
                                                            <?php $converted_date = date_with_time($sent_question['answer_date']); ?>
<!--                                                            --><?php //$converted_date = convert_date0time_to_different_timezone($sent_question['sent_date'], 'America/Chicago', $company_timezone); ?>
                                                            <a href="javascript:show_response(<?php echo $sent_question['sid']; ?>);" class="question_date_link list-group-item">
                                                                <strong>Click To View Answer</strong>
                                                                <br />
                                                                <small><?php echo $converted_date; ?></small>
                                                            </a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                <?php foreach($question['sent'] as $sent_question) { ?>
                                                    <?php if($sent_question['status'] != 'unanswered') { ?>
                                                    <div id="sent_question_<?php echo $sent_question['sid']; ?>" class="panel panel-default response_panel" >
                                                        <div class="row">
                                                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                                <div  class="panel-body">
                                                                    <?php if($sent_question['question_type'] == 'video') { ?>
                                                                        <p>
                                                                            <strong>Ans:</strong>
                                                                        </p>
                                                                        <?php if ($sent_question['response_video_source'] == 'recorded') { ?>
                                                                            <video style="width:100%;" controls>
                                                                                <source
                                                                                    src="<?php echo STORE_PROTOCOL_SSL . CLOUD_VIDEO_LIBRARY . '.s3.amazonaws.com/' . $sent_question['video_response']; ?>"
                                                                                    type="video/webm">
                                                                                Your browser does not support HTML5
                                                                                video.
                                                                            </video>
                                                                        <?php } else if ($sent_question['response_video_source'] == 'youtube') {?>
                                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                                <iframe src="https://www.youtube.com/embed/<?php echo $sent_question['response_video_id']; ?>"></iframe>
                                                                            </div>
                                                                        <?php }
                                                                        else if ($sent_question['response_video_source'] == 'vimeo') {?>
                                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                                <iframe src="https://player.vimeo.com/video/<?php echo $sent_question['response_video_id']; ?>?title=0&byline=0&portrait=0&loop=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                <script src="https://player.vimeo.com/api/player.js"></script>
                                                                            </div>
                                                                        <?php }
                                                                    }else if ($sent_question['question_type'] == 'text') { ?>
                                                                        <p>
                                                                            <strong>Ans:</strong> <?php echo $sent_question['text_response']; ?>
                                                                        </p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                                <div class="panel-body">
                                                                    <div class="comm-scroll-list form-col-100">
                                                                        <strong>All Comments:</strong>
                                                                        <?php if(!empty($sent_question['scores'])) { ?>
                                                                            <ul class="list-unstyled">
                                                                            <?php foreach($sent_question['scores'] as $score) { ?>
                                                                                <li>
                                                                                    <small>
                                                                                        <strong><?php echo $score['first_name'] . ' ' . $score['last_name']; ?>: </strong>
                                                                                        <span><?php echo $score['comment']?></span><br />
                                                                                        <span><?php echo date('m/d/Y h:i A', strtotime($score['date_added']));?></span>
                                                                                    </small>
                                                                                </li>
                                                                            <?php } ?>
                                                                            </ul>
                                                                        <?php } else { ?>
                                                                            <small>
                                                                                <p>No Comments</p>
                                                                            </small>
                                                                        <?php } ?>
                                                                    </div>

                                                                    <div class="comment-text-box form-col-100">
                                                                        <form enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="save_comment_against_sent_question" />
                                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                                                            <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_sid; ?>" />
                                                                            <input type="hidden" id="question_sent_sid" name="question_sent_sid" value="<?php echo $sent_question['sid']; ?>" />
                                                                            <input type="hidden" id="job_list_sid" name="job_list_sid" value="<?php echo $job_list_sid; ?>" />
                                                                            
                                                                            <div class="form-group">
                                                                                <label>Comment</label>
                                                                                <textarea id="comment" name="comment" class="form-control"></textarea>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <button class="btn btn-default">Comment</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <?php } else { ?>

                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.response_panel').hide();

        $('.question_date_link').on('click', function(){
            $('.question_date_link').each(function(){
                $(this).removeClass('active');
            });

            $(this).addClass('active');
        });
    });
    function show_response(sent_question_sid){
        $('.response_panel').hide();
        $('#sent_question_' + sent_question_sid).show();
    }
</script>


