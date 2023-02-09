<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$delete_post_url = '';
$save_post_url = '';

    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = base_url('learning_center/my_learning_center');
    $delete_post_url = current_url();
    $save_post_url = current_url();
 ?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
<!--            <div class="applicant-profile-wrp">-->
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                        <?php $this->load->view('templates/_parts/admin_flash_message');
                        if($top_view) {
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
                            <div class="col-xs-12">
                                <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('learning_center/my_learning_center') . $watch_url ; ?>"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?>
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <strong>Topic - </strong><?php echo ucwords($assignment['session_topic']); ?>
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <th class="col-xs-4">Date</th>
                                                            <td class="col-xs-8"><?php echo date('m-d-Y', strtotime($assignment['session_date'])); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Start Time</th>
                                                            <td class="col-xs-8"><?php echo date('H:i', strtotime($assignment['session_start_time'])); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">End Time</th>
                                                            <td class="col-xs-8"><?php echo date('H:i', strtotime($assignment['session_end_time'])); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Session Status</th>
                                                            <td class="col-xs-8"><?php echo ucwords($assignment['session_status']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Attend Status</th>
                                                            <td class="col-xs-8"><?php echo ucwords(str_replace('_', ' ', $assignment['attend_status'])); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Description</th>
                                                            <td class="col-xs-8"><?php echo $assignment['session_description']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-4">Location</th>
                                                            <td class="col-xs-8"><?php echo $assignment['session_location']; ?></td>
                                                        </tr>
                                                        <?php if(sizeof($assignment['online_video_sid'])>0) { ?>

                                                            <?php foreach ($assignment['online_video_sid'] as $key => $vid_tit) { ?>
                                                                <tr>
                                                                    <th class="col-xs-4"><?php echo $vid_tit;?></th><td class="col-xs-8">
                                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                            <a class="btn btn-block btn-info watched_video_now" src="<?= base_url('learning_center/training_session_watch_video/'.$key.'/'. $assignment['sid']);?>">Watch Video</a>
                                                                        </div>
                                                                        <?php $this->load->view('learning_center/popup_watched_video'); ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

<!--                                            <div class="btn-wrp full-width">-->
<!--                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">-->
<!--                                                    <form id="form_unable_to_attend_--><?php //echo $assignment['sid']; ?><!--" enctype="multipart/form-data" method="post">-->
<!--                                                        <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />-->
<!--                                                        <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="--><?php //echo $assignment['assigned_sid']; ?><!--" />-->
<!--                                                        <input type="hidden" id="user_type" name="user_type" value="--><?php //echo $user_type; ?><!--" />-->
<!--                                                        <input type="hidden" id="user_sid" name="user_sid" value="--><?php //echo $assignment['user_sid']; ?><!--" />-->
<!--                                                        <input type="hidden" id="attend_status" name="attend_status" value="unable_to_attend" />-->
<!--                                                        <input type="hidden" id="unique_sid" name="unique_sid" value="--><?php //echo isset($unique_sid) ? $unique_sid : ''; ?><!--" />-->
<!--                                                    </form>-->
<!--                                                    <button --><?php //echo $assignment['uta_btn_status']; ?><!-- onclick="func_submit_form('form_unable_to_attend_--><?php //echo $assignment['sid']; ?><!--//', 'Are you sure you are unable to attend?');" class="btn btn-block btn-danger btn-sm <?php //echo $assignment['uta_btn_status']; ?><!--">Unable To Attend</button>-->
<!--                                                </div>-->
<!--                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">-->
<!--                                                    <form id="form_will_attend_--><?php //echo $assignment['sid']; ?><!--" enctype="multipart/form-data" method="post">-->
<!--                                                        <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />-->
<!--                                                        <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="--><?php //echo $assignment['assigned_sid']; ?><!--" />-->
<!--                                                        <input type="hidden" id="user_type" name="user_type" value="--><?php //echo $user_type; ?><!--" />-->
<!--                                                        <input type="hidden" id="user_sid" name="user_sid" value="--><?php //echo $assignment['user_sid']; ?><!--" />-->
<!--                                                        <input type="hidden" id="attend_status" name="attend_status" value="will_attend" />-->
<!--                                                        <input type="hidden" id="unique_sid" name="unique_sid" value="--><?php //echo isset($unique_sid) ? $unique_sid : ''; ?><!--" />-->
<!--                                                    </form>-->
<!--                                                    <button --><?php //echo $assignment['wa_btn_status']; ?><!-- onclick="func_submit_form('form_will_attend_--><?php //echo $assignment['sid']; ?><!--//', 'Are you sure you will attend?');" class="btn btn-block btn-warning btn-sm <?php //echo $assignment['wa_btn_status']; ?><!--">Will Attend</button>-->
<!--                                                </div>-->
<!--                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">-->
<!--                                                    <form id="form_attended_--><?php //echo $assignment['sid']; ?><!--" enctype="multipart/form-data" method="post">-->
<!--                                                        <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />-->
<!--                                                        <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="--><?php //echo $assignment['assigned_sid']; ?><!--" />-->
<!--                                                        <input type="hidden" id="user_type" name="user_type" value="--><?php //echo $user_type; ?><!--" />-->
<!--                                                        <input type="hidden" id="user_sid" name="user_sid" value="--><?php //echo $assignment['user_sid']; ?><!--" />-->
<!--                                                        <input type="hidden" id="attend_status" name="attend_status" value="attended" />-->
<!--                                                        <input type="hidden" id="unique_sid" name="unique_sid" value="--><?php //echo isset($unique_sid) ? $unique_sid : ''; ?><!--" />-->
<!--                                                    </form>-->
<!--                                                    <button --><?php //echo $assignment['a_btn_status']; ?><!-- onclick="func_submit_form('form_attended_--><?php //echo $assignment['sid']; ?><!--//', 'Are you sure you want to mark this session as attended?');" class="btn btn-block btn-success btn-sm <?php //echo $assignment['a_btn_status']; ?><!--">Attended</button>-->
<!--                                                </div>-->
<!--                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

<!--                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">-->
                        <?php $this->load->view($left_navigation); ?>
<!--                    </div>-->
                </div>
<!--            </div>-->
        </div>
    </div>
</div>

<script src="https://www.youtube.com/iframe_api"></script>
<script src="https://player.vimeo.com/api/player.js"></script>
<script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
<script>
    $(document).ready(function(){
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        $('#popup1').on('hidden.bs.modal', function () {
            $('#youtube-section').hide();
            $('#vimeo-section').hide();
            $('#video-section').hide();
            $('#not_watched_video').hide();
            $('#watched_video').hide();
        });
    });

    function func_submit_form(form_id, alert_message) {
        alertify.confirm(
            'Are you sure?',
            alert_message,
            function() {
                $('#' + form_id).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    $('.watched_video_now').on('click', function () {
        var myurl = $(this).attr('src');
        
        $.ajax({
            type: "GET",
            url: myurl,
            success: function (response) {
                var obj = jQuery.parseJSON(response);
                var user_id = obj['employer_sid'];
                var user_type = obj['user_type'];
                var video_id = obj['video_id'];
                var video_sid = obj['video_sid'];
                var video_url = obj['video_url'];
                var watched = obj['watched'];
                var video_source = obj['video_source'];
                var video_title = obj['video_title'];
                var video_description = obj['video_description'];
                var date_watched = obj['date_watched'];

                $("#popup_user_sid").val(user_id);
                $("#popup_user_type").val(user_type);
                $("#popup_video_id").val(video_id);
                $("#popup_video_sid").val(video_sid);
                $("#popup_video_title").html(video_title);
                $("#popup_video_description").html(video_description);

                if (video_source == 'youtube') {
                    $('#popup1').modal('show');
                    $('#youtube-section').show();
                    onYouTubeIframeAPIReady(video_id);
                } else if (video_source == 'vimeo') {
                    $('#popup1').modal('show');
                    $('#vimeo-section').show();
                    var vimeo_vid = video_id;
                    var options = {
                        id: vimeo_vid
                    };

                    onVimeoIframeAPIReady(options);
                } else {
                    $('#popup1').modal('show');
                    $('#video-section').show();
                    var video = document.getElementById('my-video');
                    var source = document.createElement('source');
                    source.setAttribute('src', video_url);
                    video.appendChild(source);
                }

                if (watched != 0) {
                    $('#watched_video').show();
                    $("#dutton_watched_video").html('Watched on '+date_watched);
                } else {
                    $('#not_watched_video').show();
                }
            },
            error: function (data) {
            }
        });
    });

    var vid = document.getElementById("my-video");
    vid.onplay = function() {
        var duration = formatTime( vid.currentTime ) +'/'+ formatTime( vid.duration );
        var completed = 0;
        updateRecord (duration,completed) ;
    };

    vid.onpause = function() {
        var duration = formatTime( vid.currentTime ) +'/'+ formatTime( vid.duration );
        var completed = 0;
        updateRecord (duration,completed) ;
    };

    vid.onended = function() {
        var duration = formatTime( vid.currentTime ) +'/'+ formatTime( vid.duration );
        var completed = 1;
        updateRecord (duration,completed) ;
    };   

    function func_mark_video_as_watched() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to mark this video as watched?',
            function () {
                var myurl = "<?= base_url() ?>learning_center/mark_as_watched";
                var user_id = $('#popup_user_sid').val();
                var user_type = $('#popup_user_type').val();
                var video_id = $('#popup_video_sid').val();

                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {id: user_id, type: user_type, v_id: video_id},
                    async : false,
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                        $("#btn_disable").html('Watched on '+obj);
                        $('#btn_disable').attr('disabled', 'disabled');
                    },
                    error: function (data) {

                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            }
        );
    }
        
    var youtube_player,
        playing = false;

    function onYouTubeIframeAPIReady(video_id) {
        var videoID = video_id;
        youtube_player = new YT.Player('youtube-video-placeholder', {
            width: 600,
            height: 400,
            videoId: videoID,
            host: 'https://www.youtube.com',
            playerVars: {
                color: 'white',
                // playlist: 'taJ60kskkns,FG0fTKAqZ5g',
            },
            events: {
                onStateChange: onPlayerStateChange
            },
        });
    }

    function formatTime(time){
        time = Math.round(time);
        var minutes = Math.floor(time / 60),
            seconds = time - minutes * 60;

        seconds = seconds < 10 ? '0' + seconds : seconds;
        return minutes + ":" + seconds;
    }

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {  
            var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
            var completed = 0;
            updateRecord (duration,completed) ;
            var time = "Watched On "+moment().format('MM-DD-YYYY hh:mm A');
            $("#btn_disable").html(time);
            $('#btn_disable').attr('disabled', 'disabled');

        } else if (event.data == YT.PlayerState.PAUSED) { 
            var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
            var completed = 0;
            updateRecord (duration,completed) ;
        } else if (event.data === 0) { 
            var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
            var completed = 1;
            updateRecord (duration,completed) ;
        }
    }

    var v_c_t,
        v_d;

    function onVimeoIframeAPIReady(options) {
        var player = new Vimeo.Player('vimeo-video-placeholder', options);

        player.on('play', function() {
        var currentPromise = player.getCurrentTime();
        var durationPromise = player.getDuration();
        currentPromise.then(function (val) {
          v_c_t = formatTime(val);
        });
        
        durationPromise.then(function (val) {
          v_d = formatTime(val);
        });
        
        setTimeout(function(){ 
            var duration = v_c_t + '/' +v_d;
            var completed = 0;
            updateRecord (duration,completed) ; 
            var currentdate = new Date(); 
            var time = "Watched On "+moment().format('MM-DD-YYYY hh:mm A');
            $("#btn_disable").html(time);
            $('#btn_disable').attr('disabled', 'disabled');
        }, 50);
    });

    player.on('pause', function() {
        var currentPromise = player.getCurrentTime();
        var durationPromise = player.getDuration();
        
        currentPromise.then(function (val) {
          v_c_t = formatTime(val);
        });
        
        durationPromise.then(function (val) {
          v_d = formatTime(val);
        });
        
        setTimeout(function(){ 
            var duration = v_c_t + '/' +v_d;
            var completed = 0;
            updateRecord (duration,completed) ;  
        }, 50);
    });

    player.on('ended', function() {
        var currentPromise = player.getCurrentTime();
        var durationPromise = player.getDuration();
        
        currentPromise.then(function (val) {
          v_c_t = formatTime(val);
        });
        
        durationPromise.then(function (val) {
          v_d = formatTime(val);
        });
        
        setTimeout(function(){ 
            var duration = v_c_t + '/' +v_d;
            var completed = 1;
            updateRecord (duration,completed) ;  
        }, 50);
    });
    }

    function updateRecord (duration,completed) {
        var myurl = "<?= base_url() ?>learning_center/track_youtube";
        var user_id = $('#popup_user_sid').val();
        var user_type = $('#popup_user_type').val();
        var video_id = $('#popup_video_sid').val();

        $.ajax({
            type: "POST",
            url: myurl,
            data: {id: user_id, type: user_type, v_id: video_id, v_duration: duration, v_completed : completed},
            async : false,
            success: function (data) {
                return 'datamydate';
            },
            error: function (data) {

            }
        });
    }
</script>