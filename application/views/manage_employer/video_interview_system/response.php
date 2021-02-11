<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="row" style="margin-top: 10px;">
                            <!-- response -->
                            <div class="table-responsive table-outer">
                                <?php if (!empty($video_question)) { ?>
                                        <?php if($video_question['question_type'] == 'video') { ?>
                                            <p>
                                                <b>Video Title : </b>
                                                <?php echo $video_question['video_title']; ?>
                                            </p><br/>
                                            <p>
                                                <b>Response Date : </b>
                                                <?php echo date_with_time($video_question['sent_date']); ?>
                                            </p><br/>
                                            <p>
                                                <b>Applicant Name : </b>
                                                <?php echo ucwords($video_question['first_name'] . ' ' . $video_question['last_name']); ?>
                                            </p><br/>
                                            <div>
                                                <p><b>Applicant Response : </b></p>
                                                <br/><br/>
                                                <div class="text-center">
                                                    <video style="width:60%;" controls>
                                                        <source src="<?php echo STORE_PROTOCOL_SSL . CLOUD_VIDEO_LIBRARY . '.s3.amazonaws.com/' . $video_question['video_response']; ?>" type="video/webm">
                                                        Your browser does not support HTML5 video.
                                                    </video>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <p>
                                                <b>Question : </b>
                                                <?php echo $video_question['question_text']; ?>
                                            </p><br/>
                                            <p>
                                                <b>Response Date : </b>
                                                <?php echo date_with_time($video_question['sent_date']); ?>
                                            </p><br/>
                                            <p>
                                                <b>Applicant Name : </b>
                                                <?php echo ucwords($video_question['first_name'] . ' ' . $video_question['last_name']); ?>
                                            </p><br/>
                                            <div>
                                                <p>
                                                    <b>Applicant Response : </b>
                                                    <?php echo $video_question['text_response']; ?>
                                                </p>
                                            </div>
                                        <?php } ?>
                                <?php } else { ?>
                                    <div class="no-job-found">
                                        <ul>
                                            <li>
                                                <h3 style="text-align: center;">No Video Question found! </h3>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>                        
                            </div>
                            <!-- response -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>