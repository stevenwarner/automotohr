<?php
    $company_sid = 0;
    $users_type = '';
    $users_sid = 0;
    $back_url = '';
    $dependants_arr = array();
    $delete_post_url = '';
    $save_post_url = '';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/learning_center/' . $unique_sid);

    $delete_post_url = current_url();
    $save_post_url = current_url();
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = base_url('learning_center/my_learning_center');

    $delete_post_url = current_url();
    $save_post_url = current_url();
}

?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo $back_url; ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> My Learning Center</a>
                </div>
                <div class="page-header">
                  <h1 class="section-ttile">Watch Videos</h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="well well-sm">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <?php if($video['video_source'] == 'youtube') { ?>
                                            <div id="youtube-video-placeholder" class="embed-responsive-item">
                                            </div>
                                        <?php } elseif ($video['video_source'] == 'vimeo') { ?>
                                            <div id="vimeo-video-placeholder"></div>
                                        <?php } else { ?>
                                            <video id="my-video" class="video-js" controls preload="auto" width="300" height="151"
                                                poster="<?php echo base_url('assets/uploaded_videos/MY_VIDEO_POSTER.jpg'); ?>" data-setup="{}">
                                                <p class="vjs-no-js">
                                                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                                </p>
                                            </video>
                                        <?php } ?>
                                    </div>
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
                        <?php if(!empty($supported_documents)) { ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="panel panel-default ems-documents">
                                        <div class="panel-heading">
                                            <strong>Supported Documents</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-plane">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-lg-3">Document Name</th>
                                                            <th class="col-lg-3 text-center">Type</th>
                                                            <th class="col-lg-3 text-center">Attached Date</th>
                                                            <th class="col-lg-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>   
                                                        <?php foreach($supported_documents as $document) { ?>
                                                            <tr>
                                                                <td class="col-lg-3"><?php echo $document['upload_file_title']; ?></td>
                                                                <td class="col-lg-1 text-center">
                                                                    <?php $doc_type = $document['upload_file_extension']; ?>
                                                                    <?php if($doc_type == 'pdf'){ ?>
                                                                        <i class="fa fa-2x fa-file-pdf-o"></i>
                                                                    <?php } else if(in_array($doc_type, ['ppt', 'pptx'])){ ?>
                                                                       <i class="fa fa-2x fa-file-powerpoint-o"></i>                          
                                                                    <?php } else if(in_array($doc_type, ['doc', 'docx'])){ ?>
                                                                       <i class="fa fa-2x fa-file-o"></i>
                                                                    <?php } else if(in_array($doc_type, ['xlsx'])){ ?> 
                                                                        <i class="fa fa-2x fa-file-excel-o"></i>
                                                                    <?php } else if($doc_type == ''){ ?> 
                                                                        <i class="fa fa-2x fa-file-text"></i>         
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['attached_date'])) { ?>
                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['attached_date']), 'M d Y h:i a'); ?>
                                                                            </div>
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-1 text-center">
                                                                    <a href="<?php echo base_url('onboarding/view_supported_attachment_document/'.$unique_sid.'/'.$document['sid']); ?>" class="btn btn-info">View</a>
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
                        <?php } ?>
                        <?php if(isset($job_details) && sizeof($job_details)>0){?>

                            <div class="row" id="que-div" style="display : none">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="form-wrp">
                                                <form method="POST" id="register-form" action="<?php echo current_url();?>">
                                                    <input type='hidden' name="q_name" value="<?php echo $job_details['q_name']; ?>">
                                                    <input type='hidden' name="q_passing" value="<?php echo $job_details['q_passing']; ?>">
                                                    <input type='hidden' name="q_send_pass" value="<?php echo $job_details['q_send_pass']; ?>">
                                                    <input type='hidden' name="q_pass_text" value="<?php echo $job_details['q_pass_text']; ?>">
                                                    <input type='hidden' name="q_send_fail" value="<?php echo $job_details['q_send_fail']; ?>">
                                                    <input type='hidden' name="q_fail_text" value="<?php echo $job_details['q_fail_text']; ?>">
                                                    <input type='hidden' name="my_id" value="<?php echo $job_details['my_id']; ?>">
                                                    <?php $my_id = $job_details['my_id'];
                                                        $iterate = 0;
                                                        foreach ($job_details[$my_id] as $questions_list) { ?>
                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                    <input type="hidden" name="all_questions_ids[]" value="<?php echo $questions_list['questions_sid']; ?>">
                                                                    <input type="hidden" name="caption<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['caption']; ?>">
                                                                    <input type="hidden" name="type<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['question_type']; ?>">
                                                                    <input type="hidden" name="perform_action" value="questionnaire">
                                                                    <div class="form-group autoheight">
                                                                    <label><?php echo $questions_list['caption']; ?>: <?php if ($questions_list['is_required'] == 1) { ?><span class="required"> * </span><?php } ?></label>
                                                                    <?php if ($questions_list['question_type'] == 'string') { ?>
                                                                        <input type="text" class="form-control" name="string<?php echo $questions_list['questions_sid']; ?>" placeholder="<?php echo $questions_list['caption']; ?>" value="" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                                    <?php } ?>
                                                                    <?php if ($questions_list['question_type'] == 'boolean') { ?>
                                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                        <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                            <label class="control control--radio">
                                                                                <input type="radio" name="boolean<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>> <?php echo $answer_list['value']; ?>&nbsp;
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        <?php } ?>
                                                                        <?php } ?>
                                                                        <?php if ($questions_list['question_type'] == 'list') { ?>
                                                                            <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                        <select name="list<?php echo $questions_list['questions_sid']; ?>" class="form-control" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                                            <option value="">-- Please Select --</option>
                                                                        <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                                <option value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>"> <?php echo $answer_list['value']; ?></option>
                                                                        <?php } ?>
                                                                        </select>
                                                                    <?php } ?>
                                                                <?php if ($questions_list['question_type'] == 'multilist') { ?>
                                                                <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                <div class="row">
                                                                    <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                            <label for="squared<?php echo $iterate; ?>" class="control control--checkbox">
                                                                                <?php echo $answer_list['value']; ?>
                                                                                <input type="checkbox" class="checkbox-<?php echo $questions_list['questions_sid']; ?>" onclick="unCheckAll('checkbox-<?php echo $questions_list['questions_sid']; ?>',this)" name="multilist<?php echo $questions_list['questions_sid']; ?>[]" id="squared<?php echo $iterate++; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <?php } ?>
                                                                    </div>
                                                                </div>
                                                        <?php }?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <input id="mySubmitBtn" class="btn btn-info" type="submit" value="<?php echo $attempt_status ? 'Questionnaire Already Submitted' : "Save";?>" <?php echo $attempt_status ? 'disabled="disabled"' : '';?>>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div >

                        <?php } ?>
                        <hr />
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"></div>
                            <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                <form id="form_mark_video_as_watched" enctype="multipart/form-data" method="post">
                                    <input type="hidden" id="perform_action" name="perform_action" value="mark_video_as_watched" />
                                    <input type="hidden" id="video_sid" name="video_sid" value="<?php echo $video['sid']; ?>" />
                                    <input type="hidden" id="user_type" name="user_type" value="<?php echo $users_type?>" />
                                    <input type="hidden" id="video_id" name="video_id" value="<?php echo $video['video_id']; ?>" />
                                    <input type="hidden" id="video_url" name="video_url" value="<?php echo $video_url; ?>" />
                                    <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $users_sid; ?>" />
                                    <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                </form>
                                <?php if($assignment['watched'] == 0) { ?>
                                    <button type="button" class="btn btn-success btn-block" onclick="func_mark_video_as_watched();" id="btn_disable">Mark as Watched</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-success btn-block disabled" disabled="disabled">Watched on <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $assignment['date_watched'])->format('m-d-Y h:i A'); ?></button>
                                <?php }?>
                            </div>
                            <!-- <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"></div> -->
                             <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4" id="que-btn-div">

                                <?php if(isset($job_details)  && sizeof($job_details)>0 && isset($applicant)){
                                    if(!$attempt_status){?>
                                        <button type="button" class="btn btn-success btn-block" id="que_btn_disable">Answer Questions</button>
                                    <?php } else{
                                        echo '<button type="button" class="btn btn-success btn-block" disabled="disabled">Attempted On ' . DateTime::createFromFormat('Y-m-d H:i:s', $attempted_questionnaire_timestamp)->format('m-d-Y h:i A') . '</button>';
                                    }
                                }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://player.vimeo.com/api/player.js"></script>
<script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
<script>
    var youtube_player,
        playing = false;

    $(document).ready(function(){
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        $('#que_btn_disable').click(function(){
            $(this).hide();
            $('#que-div').show();
        });

        
            
        <?php if($video['video_source'] == 'youtube') { ?>

            var v_id = $('#video_id').val();

            createVideo(v_id);

            function createVideo(video) {
               
                var youtubeScriptId = 'youtube-api';
                var youtubeScript = document.getElementById(youtubeScriptId);
                var videoId = video;

                if (youtubeScript === null) {
                    var tag = document.createElement('script');
                    var firstScript = document.getElementsByTagName('script')[0];

                    tag.src = 'https://www.youtube.com/iframe_api';
                    tag.id = youtubeScriptId;
                    firstScript.parentNode.insertBefore(tag, firstScript);
                    
                }

                window.onYouTubeIframeAPIReady = function() {
                    youtube_player = new window.YT.Player('youtube-video-placeholder', {
                        videoId: videoId,
                        playerVars: {
                            color: 'white',
                            // playlist: 'taJ60kskkns,FG0fTKAqZ5g',
                        },
                        events: {
                            onStateChange: onPlayerStateChange
                        },
                    });
                }
            }

            

        <?php } elseif ($video['video_source'] == 'vimeo') { ?>
            
            var v_id = $('#video_id').val();
            var vimeo_vid = v_id;
            var options = {
                id: vimeo_vid
            };

            onVimeoIframeAPIReady(options);

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

        <?php } else { ?>
            
            var video = document.getElementById('my-video');
            var source = document.createElement('source');
            var v_id = $('#video_url').val();
            source.setAttribute('src', v_id);
            video.appendChild(source);

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
            } 

        <?php } ?>    
    });

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING)
        {  
            var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
            var completed = 0;
            updateRecord (duration,completed) ;
            var time = "Watched On "+moment().format('MM-DD-YYYY hh:mm A');
            $("#btn_disable").html(time);
            $('#btn_disable').attr('disabled', 'disabled');

        }
        else if (event.data == YT.PlayerState.PAUSED)
        { 
            var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
            var completed = 0;
            updateRecord (duration,completed) ;

        }
       else if (event.data === 0)
        { 
            var duration = formatTime( youtube_player.getCurrentTime() ) +'/'+ formatTime( youtube_player.getDuration() );
            var completed = 1;
            updateRecord (duration,completed) ;
        }
    }

    function formatTime(time){

        time = Math.round(time);

        var minutes = Math.floor(time / 60),
            seconds = time - minutes * 60;

        seconds = seconds < 10 ? '0' + seconds : seconds;

        return minutes + ":" + seconds;
    }   

    function updateRecord (duration,completed) {
        var myurl = "<?= base_url() ?>onboarding/track_video";
        var user_id = $('#user_sid').val();
        var user_type = $('#user_type').val();
        var video_id = $('#video_sid').val();
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