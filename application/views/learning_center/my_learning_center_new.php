<?php
    $company_sid = 0;
    $users_type = '';
    $users_sid = 0;
    $back_url = '';
    $dependants_arr = array();
    $delete_post_url = '';
    $save_post_url = '';
    $watch_video_base_url = '';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/dashboard/' . $unique_sid);
    $watch_video_base_url = base_url('onboarding/watch_video/' . $unique_sid);
    $delete_post_url = current_url();
    $save_post_url = current_url();
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $watch_video_base_url = base_url('learning_center/watch_video/');
    $delete_post_url = current_url();
    $save_post_url = current_url();
} ?>

<style>
    .completed {
        background: #ffc107 !important;
    }

    .pending {
        background: #444 !important;
    }

    .badge {
        padding: 8px 10px !important;
        margin-left: 5px;
    }

    .note {
        font-size: 14px !important;
    }

    .badge-success {
        margin-bottom: 5px;
        background: #fd7a2a !important;
    }

    .badge-warning {
        margin-bottom: 5px;
        background: #444 !important;
    }

    .video_time_log {
        display: block !important;
    }

    .video_time_log:after {
        content: "" !important;
        margin: 0 3px 0 6px;
    }
</style>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">Learning Center</h1>
                </div>
                <div class="panel panel-default" id="online_videos_notes">
                    <div class="panel-heading">
                        <strong>Notes</strong>
                    </div>
                    <div class="panel-body">
                        <!-- / -->
                        <div class="row">
                            <div class="col-sm-3 col-xs-12">
                                <span class="badge badge-warning completed"><i class="fa fa-check" aria-hidden="true"></i> Video Not Watched</span>
                            </div>
                            <div class="col-sm-9 col-xs-12">
                                <?=getUserHint('video_not_watched_ems');?>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-3 col-xs-12">
                                <span class="badge badge-warning completed"><i class="fa fa-check" aria-hidden="true"></i> Questionnaire Not Completed</span>
                            </div>
                            <div class="col-sm-9 col-xs-12">
                                <?=getUserHint('questionnaire_not_watched_ems');?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-xs-12">
                                <span class="badge badge-success completed"><i class="fa fa-check" aria-hidden="true"></i> Video Watched</span>
                            </div>
                            <div class="col-sm-9 col-xs-12">
                                <?=getUserHint('video_watched_ems');?>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-3 col-xs-12">
                                <span class="badge badge-success completed"><i class="fa fa-check" aria-hidden="true"></i>  Questionnaire Completed</span>
                            </div>
                            <div class="col-sm-9 col-xs-12">
                                <?=getUserHint('questionnaire_watched_ems');?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default lc-tabs-panel">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a href="#online_videos" data-toggle="tab" id="OV_tab">Online Videos <?php echo !empty($videos) ? '('.$pendingVideo.')' : ""; ?></a></li>
                            <li><a href="#training_sessions" data-toggle="tab" id="TS_tab">Training Sessions <?php echo !empty($assigned_sessions) ? '('.$pendingSessions.')' : ""; ?></a></li>
                        </ul>
                    </div>
                    
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="online_videos">
                                
                                <!--<div class="table-responsive">-->
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="dashboard-conetnt-wrp">
                                                <?php if($videos) { //echo '<pre>'; print_r($videos); echo '</pre>'; exit;?>
                                                        <div class="announcements-listing">
                                                <?php   foreach ($videos as $video) { ?>
                                                            <article class="listing-article">
                                                                <figure>
                                                            <?php if($video['video_source'] == 'youtube') { ?>
                                                                        <a href="<?php echo $watch_video_base_url . '/' . $video['sid']; ?>">
                                                                            <img src="https://img.youtube.com/vi/<?php echo $video['video_id']; ?>/hqdefault.jpg"/>
                                                                        </a>
                                                            <?php } if($video['video_source'] == 'vimeo') { 
                                                                        $thumbnail_image = vimeo_video_data($video['video_id']); ?> 
                                                                        <a href="<?php echo $watch_video_base_url . '/' . $video['sid']; ?>"><img src="<?php echo $thumbnail_image;?>"/></a>
                                                            <?php   } else { ?>
                                                                        <a href="<?php echo $watch_video_base_url . '/' . $video['sid']; ?>">
                                                                            <video id="video" width="214" height="145">
                                                                                <source src="<?php echo base_url('assets/uploaded_videos/'.$video['video_id']); ?>" type="video/mp4">
                                                                            </video>
                                                                        </a>
                                                            <?php   } ?>
                                                                </figure>
                                                                <div class="text">
                                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4" style="padding-left: 0px !important;">
                                                                        <h3><a href="<?php echo $watch_video_base_url . '/' . $video['sid']; ?>"><?php echo $video['video_title']; ?></a></h3>
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">      
                                                                        <?php if ($video['video_have_question'] == "yes") { ?>
                                                                            <?php if ($video['video_question_completed'] == "pending") { ?>
                                                                                <span class="pull-right badge badge-warning pending">
                                                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                                                    Questionnaire Not Completed
                                                                                </span>
                                                                            <?php } else { ?>
                                                                                <span class="pull-right badge badge-success completed">
                                                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                                                    Questionnaire Completed
                                                                                </span>
                                                                            <?php } ?>
                                                                        <?php } ?>

                                                                        <?php if ($video['video_watched_status'] == "pending") { ?>
                                                                            <span class="pull-right badge badge-warning pending">
                                                                                <i class="fa fa-ban" aria-hidden="true"></i>
                                                                                Video Not Watched
                                                                            </span>
                                                                        <?php } else { ?>
                                                                            <span class="pull-right badge badge-success completed">
                                                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                                                Video Watched
                                                                            </span>
                                                                        <?php } ?>
                                                                    </div>
                                                                   
                                                                    
                                                                    
                                                                    <div class="post-options">
                                                                        <ul>
                                                                            <li class="video_time_log"><b>Video Assigned On : </b><?php echo date("M d Y, D", strtotime($video['created_date'])); ?></li>
                                                                            <li class="video_time_log"><b>Video Watched On : </b><?php echo $video['video_watched_date']; ?></li>
                                                                            <?php if ($video['video_have_question'] == "yes") { ?>
                                                                                <li class="video_time_log"><b>Questionnaire Completed On : </b><?php echo $video['video_question_completed_date']; ?></li>
                                                                            <?php } ?>
                                                                        </ul>
                                                                        <span class="post-author"><a href="<?php echo $watch_video_base_url . '/' . $video['sid']; ?>" class="btn btn-block btn-info">Watch Video</a></span>
                                                                    </div>
                                                                    <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                                        <?php echo strlen($video['video_description']) > 100 ? substr($video['video_description'],0,100)." ..." : $video['video_description']; ?>
                                                                    </div>
                                                                </div>
                                                            </article>
                                                <?php   } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="tab-pane fade" id="training_sessions">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="col-xs-3 valign-middle">Topic</th>
                                            <th rowspan="2" class="col-xs-2 text-center valign-middle">Date</th>
                                            <th colspan="2" class="col-xs-1 text-center valign-middle">Time</th>
                                            <th rowspan="2" class="col-xs-1 text-center valign-middle">Session Status</th>
                                            <th rowspan="2" class="col-xs-2 text-center valign-middle" colspan="3">Actions</th>
                                        </tr>
                                        <tr>
                                            <th class="col-xs-1 text-center valign-middle">Start</th>
                                            <th class="col-xs-1 text-center valign-middle">End</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($assigned_sessions)) { ?>
                                            <?php foreach($assigned_sessions as $session) { if($session['sid'] == '')  continue;  ?>
                                                <tr>
                                                    <td><?php echo $session['session_topic']; ?></td>
<!--                                                    <td class="text-center">--><?php //echo ucwords($session['session_status']);?><!--</td>-->
                                                    <td class="text-center"><?=reset_datetime(array( 'datetime' => $session['session_date'], 'from_format' => 'Y-m-d', '_this' => $this)); ?></td>
                                                    <td class="text-center"><?=reset_datetime(array( 'datetime' => $session['session_date'].''.$session['session_start_time'], 'from_format' => 'Y-m-dH:i:s', '_this' => $this, 'format' => 'h:iA')); ?></td>
                                                    <td class="text-center"><?=reset_datetime(array( 'datetime' => $session['session_date'].''.$session['session_end_time'], 'from_format' => 'Y-m-dH:i:s', '_this' => $this, 'format' => 'h:iA')); ?></td>
                                                    <td class="text-center"><?php echo ucwords($session['session_status'] == 'pending' ? 'scheduled' : $session['session_status']); ?></td>
                                                    <td class="text-center"><a class="btn btn-block btn-info" href="<?= base_url('learning_center/view_training_session/'.$session['sid']);?>">View</a></td>
                                                    
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="text-center" colspan="8">
                                                    <span class="no-data">No Sessions found!</span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    $('#TS_tab').on('click', function () {
        $('#online_videos_notes').hide();
    });

    $('#OV_tab').on('click', function () {
        $('#online_videos_notes').show();
    });

    function func_submit_form(form_id, alert_message){
        alertify.confirm(
            'Are you sure?',
            alert_message,
            function(){
                $('#' + form_id).submit();
            },
            function(){
                alertify.error('Cancelled!');
            });
    }
</script>
