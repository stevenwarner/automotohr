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
} ?>
<?php 
function getAnswer($answers_given, $question, $doReturn = FALSE, $compareValue = '', $isSelect = false){
    //
    if(!isset($answers_given[$question])){ return ''; }
    //
    if($doReturn){
        return $answers_given[$question]['answer'];
    }
    //
    $rt = 'checked="checked"';
    //
    if(is_array($answers_given[$question]['answer'])){
        if(in_array((int) trim($compareValue), array_values($answers_given[$question]['answer']))){
            return $rt;
        } else{
            return '';
        }
    } else if(trim($answers_given[$question]['answer']) == trim($compareValue)){
        return $isSelect ? 'selected="true"' : $rt;
    }
}
    
?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> My Learning Center</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile"><?php echo $title; ?></h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <div class="embed-responsive embed-responsive-16by9">
                                <?php       if($video['video_source'] == 'youtube') { ?>
                                                <div id="youtube-video-placeholder" class="embed-responsive-item"></div>
                                <?php       } elseif ($video['video_source'] == 'vimeo') { ?>
                                                <div id="vimeo-video-placeholder"></div>
                                <?php       } else { ?>
                                                <video id="my-video" controls></video>
                                <?php       } ?>
                                        </div>
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
                                                                    <button class="btn btn-info"
                                                                        onclick="preview_latest_generic_function(this);"
                                                                        data-title="<?php echo $document['upload_file_title']; ?>"
                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['upload_file_name']; ?>"
                                                                        data-s3-name="<?php echo $document['upload_file_name']; ?>">View</button>
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
                        
                        <?php if(!empty($job_details)) { ?>
                            <?php 
                                $show_qwestion_div = $attempt_status ? 'display: block;' : "display: none;";
                                $is_attemped = $attempt_status ? 'yes' : "no";
                            ?>
                            <div class="row" id="que-div" style="<?php echo $show_qwestion_div; ?>">
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
                                                    <?php   $my_id = $job_details['my_id'];
                                                    $iterate = 0;
                                                    
                                                    foreach ($job_details[$my_id] as $questions_list) { ?>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <input type="hidden" name="all_questions_ids[]" value="<?php echo $questions_list['questions_sid']; ?>">
                                                            <input type="hidden" name="caption<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['caption']; ?>">
                                                            <input type="hidden" name="type<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['question_type']; ?>">
                                                            <input type="hidden" name="perform_action" value="questionnaire">
                                                            <div class="form-group autoheight">
                                                                <label><?php echo $questions_list['caption']; ?>: <?php if ($questions_list['is_required'] == 1) { ?><span class="required"> * </span><?php } ?></label>
                                                                <?php $question_caption = $questions_list['caption']; ?>
                                                                <?php if ($questions_list['question_type'] == 'string') { ?>
                                                                    <?php 
                                                                        $string_answer = '';
                                                                        if ($is_attemped == 'yes') {
                                                                            $string_answer = getAnswer($answers_given[0], $question_caption, false, $answer_list['value']);
                                                                        }
                                                                    ?>
                                                                    <input type="text" class="form-control" name="string<?php echo $questions_list['questions_sid']; ?>" placeholder="<?php echo $questions_list['caption']; ?>" value="<?=$string_answer;?>" <?php echo $questions_list['is_required'] == 1 ? "required" : ""; ?> <?php echo $is_attemped == 'yes' ? "disabled " : ""; ?>>
                                                                <?php } ?>
                                                                <?php if ($questions_list['question_type'] == 'boolean') { ?>
                                                                    <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                    
                                                                    <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                        <?php 
                                                                            $boolean_answer = '';
                                                                            if ($is_attemped == 'yes') {
                                                                                $boolean_answer = getAnswer($answers_given[0], $question_caption, false, $answer_list['value']);
                                                                            }
                                                                        ?>
                                                                        <label class="control control--radio">
                                                                            <input type="radio" name="boolean<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?> <?=$boolean_answer;?> <?php echo $is_attemped == 'yes' ? "disabled " : ""; ?>> <?php echo $answer_list['value']; ?>&nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <?php if ($questions_list['question_type'] == 'list') { ?>
                                                                    <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                    <?php 
                                                                        $list_answer = '';
                                                                        if ($is_attemped == 'yes') {
                                                                            $list_answer = getAnswer($answers_given[0], $question_caption, false, $answer_list['value']);
                                                                        }
                                                                    ?>
                                                                    <select name="list<?php echo $questions_list['questions_sid']; ?>" class="form-control" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                                        <option value="">-- Please Select --</option>
                                                                        <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                            <option value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?=$list_answer;?> <?php echo $is_attemped == 'yes' ? "disabled " : ""; ?>> <?php echo $answer_list['value']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } ?>
                                                                <?php if ($questions_list['question_type'] == 'multilist') { ?>
                                                                    <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                    <?php 
                                                                        $multilist_answer = '';
                                                                        if ($is_attemped == 'yes') {
                                                                            $multilist_answer = getAnswer($answers_given[0], $question_caption, false, $answer_list['value']);
                                                                        }
                                                                    ?>
                                                                    <div class="row">
                                                                        <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <label for="squared<?php echo $iterate; ?>" class="control control--checkbox">
                                                                                    <?php echo $answer_list['value']; ?>
                                                                                    <input type="checkbox" class="checkbox-<?php echo $questions_list['questions_sid']; ?>" onclick="unCheckAll('checkbox-<?php echo $questions_list['questions_sid']; ?>',this)" name="multilist<?php echo $questions_list['questions_sid']; ?>[]" id="squared<?php echo $iterate++; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?=$multilist_answer;?> <?php echo $is_attemped == 'yes' ? "disabled " : ""; ?>>
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
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
                            
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <?php if($attempt_status){?>
                                                <!-- <label>Questionnaire Result: </label> <span><?php echo $questionnaire_result; ?></span> -->
                                            <?php }?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <form id="form_mark_video_as_watched" enctype="multipart/form-data" method="post">
                                                <input type="hidden" id="perform_action" name="perform_action" value="mark_video_as_watched" />
                                                <input type="hidden" id="video_sid" name="video_sid" value="<?php echo $video['sid']; ?>" />
                                                <!-- add hidden field for video id -->
                                                <input type="hidden" id="video_id" name="video_id" value="<?php echo $video['video_id']; ?>" />
                                                <input type="hidden" id="video_url" name="video_url" value="<?php echo $video_url; ?>" />
                                                <input type="hidden" id="user_type" name="user_type" value="<?php echo $users_type?>" />
                                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $users_sid; ?>" />
                                                <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                            </form>
                                            <?php if($assignment['watched'] == 0) { ?>
                                                <button type="button" class="btn btn-success btn-block mb-2" onclick="func_mark_video_as_watched();" id="btn_disable">Mark as Watched</button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-success btn-block mb-2 disabled" disabled="disabled">Watched on <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $assignment['date_watched'])->format('m-d-Y h:i A'); ?></button>
                                            <?php }?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6" id="que-btn-div">

                                            <?php       if(isset($job_details)  && sizeof($job_details)>0) {
                                                if(!$attempt_status){?>
                                                    <button type="button" class="btn btn-success btn-block" id="que_btn_disable">Answer questions</button>
                                                <?php           } else {
                                                    echo '<button type="button" class="btn btn-success btn-block" disabled="disabled">Attempted On ' . DateTime::createFromFormat('Y-m-d H:i:s', $attempted_questionnaire_timestamp)->format('m-d-Y h:i A') . '</button>';
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php /*if($users_type != 'applicant') { ?>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/employee_hub_right_menu'); ?>
                </div>
            <?php } */?>
        </div>
    </div>
</div>

<!-- Preview Latest Document Modal Start -->
<div id="show_latest_preview_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="latest_document_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div id="latest-iframe-container" style="display:none;">
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="latest-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div> 
                <div id="latest_assigned_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="latest_document_modal_footer">
                
            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<!-- Including Youtube player javascript API -->
<!-- <script src="https://www.youtube.com/iframe_api"></script> -->
<!-- Including Vimeo player javascript API -->
<script src="https://player.vimeo.com/api/player.js"></script>
<script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
<script>
    function unCheckAll(classID,obj){
//        $('.'+classID).not(obj).attr('checked', false);
    }

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

            // function onYouTubeIframeAPIReady(video_id) {
            //     var videoID = video_id;
                
            //     youtube_player = new YT.Player('youtube-video-placeholder', {
            //         width: 600,
            //         height: 400,
            //         videoId: videoID,
            //         host: 'https://www.youtube.com',
            //         playerVars: {
            //             color: 'white',
            //             // playlist: 'taJ60kskkns,FG0fTKAqZ5g',
            //         },
            //         events: {
            //             onStateChange: onPlayerStateChange
            //         },
                    
            //     });
            // }

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

    function formatTime(time) {
        time = Math.round(time);

        var minutes = Math.floor(time / 60),
            seconds = time - minutes * 60;

        seconds = seconds < 10 ? '0' + seconds : seconds;
        return minutes + ":" + seconds;
    }   

    function updateRecord (duration,completed) {
        var myurl = "<?= base_url() ?>learning_center/track_youtube";
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

    function validate_form() {
        $("#register-form").validate();
    }
    
    function preview_latest_generic_function (source) {
        var document_title = $(source).attr('data-title');
        
        var preview_document        = 1;
        var model_contant           = '';
        var preview_iframe_url      = '';
        var preview_image_url       = '';
        var document_print_url      = '';
        var document_download_url   = '';


        var file_s3_path            = $(source).attr('data-preview-url');
        var file_s3_name            = $(source).attr('data-s3-name');

        var file_extension          = file_s3_name.substr(file_s3_name.lastIndexOf('.') + 1, file_s3_name.length);
        var document_file_name      = file_s3_name.substr(0, file_s3_name.lastIndexOf('.'));
        var document_extension      = file_extension.toLowerCase();


        switch (file_extension.toLowerCase()) {
            case 'pdf':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.pdf';
                break;
            case 'csv':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.csv';
                break;
            case 'doc':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Edoc&wdAccPdf=0';
                break;
            case 'docx':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Edocx&wdAccPdf=0';
                break;
            case 'ppt':
                preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.ppt';
                break;
            case 'pptx':
                dpreview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+ document_file_name +'.pptx';
                break;
            case 'xls':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                ocument_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Exls';
                break;
            case 'xlsx':
                preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+ document_file_name +'%2Exlsx';
                break;
            case 'jpg':
            case 'jpe':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'JPG':
            case 'JPE':
            case 'JPEG':
            case 'PNG':
            case 'GIF':
                preview_document = 0;
                preview_image_url = file_s3_path;
                document_print_url = '<?php echo base_url("hr_documents_management/print_s3_image"); ?>'+'/'+file_s3_name;
                break;
            default : //using google docs
                preview_iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                break;
        }

        document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>'+'/'+file_s3_name;

        $('#show_latest_preview_document_modal').modal('show');
        $("#latest_document_modal_title").html(document_title);
        $('#latest-iframe-container').show();

        if (preview_document == 1) {
            model_contant = $("<iframe />")
                .attr("id", "latest_document_iframe")
                .attr("class", "uploaded-file-preview")
                .attr("src", preview_iframe_url);
        } else {
            model_contant = $("<img />")
                .attr("id", "latest_image_tag")
                .attr("class", "img-responsive")
                .css("margin-left", "auto")
                .css("margin-right", "auto")
                .attr("src", preview_image_url);
        }
        
        $("#latest-iframe-holder").append(model_contant);
        //
        footer_content = '<a target="_blank" class="btn btn-<?=$load_view ? 'info' : 'success' ;?>" href="' + document_download_url + '">Download</a>';
        footer_content += '<a target="_blank" class="btn btn-<?=$load_view ? 'info' : 'success' ;?>" href="' + document_print_url + '">Print</a>';
        //
        $("#latest_document_modal_footer").html(footer_content);
        //
        if (preview_document == 1) {
            loadIframe(
                preview_iframe_url,
                '#latest_document_iframe',
                true
            );
        }
    }
</script>