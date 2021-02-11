<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo ucwords($title); ?> - Interview Questionnaires</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/alertifyjs/css/alertify.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/alertifyjs/css/themes/default.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/alertifyjs/alertify.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
</head>

<body>
    <div class="main-content blue-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="main_div">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow largerfont">
                                <?php echo ucwords($title); ?>
                                - Interview Questionnaires
                            </span>
                        </div>
                        <div class="dash-box">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-4 pull-right text-center">
                                    <div class="company-branding">
                                        <img src="<?php echo $logo; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-8 pull-left">
                                    <strong>Instructions:</strong>
                                    <div class="demo-page-list order-list">
                                        <ul>
                                            <li>When you are ready to answer the question, press the record button</li>
                                            <li>When you are finished answering the question click the Stop recording button.</li>
                                            <li>To the right you will see your recording. Click the play button to watch your recorded answer.</li>
                                            <li>If you like your answer click the <strong>"Submit Your Recording"</strong> button and you will be finished with this question.</li>
                                            <li>If you do not like your recorded answer click on record and try again.</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Please Note:</strong>
                                        <ul class="unordered-list">
                                            <li>If you are unable to get the Video Interview to record on your mobile device, it may be necessary to complete the recording using a desktop/laptop computer. You will need a Webcam and Flash player</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($invalid == true) { ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="row">
                                    <div id="container">
                                        <p class="text-center"><b>No Interview Questionnaires Found</b></p>
                                    </div>
                                </div>
                            </div>
                        <?php } else if ($complete == true) { ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="row">
                                    <div id="container">
                                        <p class="text-center"><b>Thank you for taking the time.<br>All of your Interview Questions have been completed and submitted!</b></p>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="row">
                                    <div id="container">
                                        <p>
                                            <span><b>Answered Question(s) : </b><?php echo $answered_total; ?></span>
                                            <span style="float:right;">
                                                <?php if (($unanswered_total - 1) == 0) {
                                                    echo '<b>Last Question</b>';
                                                } else {
                                                    echo '<b>Remaining Question(s) : </b>' . ($unanswered_total - 1);
                                                } ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="row">
                                    <div id="container">
                                        <div class="dash-box">
                                            <span class="no-margin" style="font-size: 22px;">
                                                <?php if ($questionnaire['question_type'] == 'video') { ?>
                                                    <strong>Q:</strong>&nbsp;&nbsp;<?php echo ucwords($questionnaire['video_title']); ?>
                                                <?php } else { ?>
                                                    <strong>Q:</strong>&nbsp;&nbsp;<?php echo ucwords($questionnaire['question_text']); ?>
                                                <?php } ?>
                                            </span>
                                            <?php if ($questionnaire['resent_status'] == 1) { ?>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <p>Our Hiring Manager has decided to resend this question to you, for further evaluation, his / her Notes or Instructions are as follows:</p>
                                                        <b><?php echo !empty($questionnaire['resent_note']) ? $questionnaire['resent_note'] : 'Please resubmit your answer with more details!'; ?></b>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if ($questionnaire['question_type'] == 'video') { ?>
                                                <?php if (!empty($questionnaire['video_name']) || !empty($questionnaire['video_id'])) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
                                                            <div class="video-interview-sec">
                                                                <div class="video-box dash-box text-center">
                                                                    <?php if ($questionnaire['video_source'] == 'recorded' && !empty($questionnaire['video_name'])) { ?>
                                                                        <?php if (!empty($questionnaire['video_name'])) { ?>
                                                                            <p><b>Question Video : </b></p>

                                                                            <video controls>
                                                                                <source src="<?php echo STORE_PROTOCOL_SSL . CLOUD_VIDEO_LIBRARY . '.s3.amazonaws.com/' . $questionnaire['video_name']; ?>" type="video/webm">
                                                                                Your browser does not support HTML5 video.
                                                                            </video>

                                                                            <p><b>Click on this video to watch and listen to the Question</b></p>

                                                                        <?php } ?>
                                                                    <?php } else if ($questionnaire['video_source'] == 'youtube' && !empty($questionnaire['video_id'])) { ?>
                                                                        <div class="embed-responsive embed-responsive-16by9">
                                                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $questionnaire['video_id']; ?>"></iframe>
                                                                        </div>

                                                                        <p><b>Click on this video to watch and listen to the Question</b></p>
                                                                    <?php } else if ($questionnaire['video_source'] == 'vimeo' && !empty($questionnaire['video_id'])) { ?>
                                                                        <div class="embed-responsive embed-responsive-16by9">
                                                                            <iframe src="https://player.vimeo.com/video/<?php echo $questionnaire['video_id']; ?>?title=0&byline=0&portrait=0&loop=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                            <script src="https://player.vimeo.com/api/player.js"></script>
                                                                        </div>

                                                                        <p><b>Click on this video to watch and listen to the Question</b></p>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <form id="video_form" name="video_form" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" id="sent_record_sid" name="sent_record_sid" value="<?php echo $questionnaire['sent_record_sid']; ?>" />
                                                    <div class="hr-box">
                                                        <div class="hr-box-header text-center bg-header-green">
                                                            <?php if (($unanswered_total - 1) != 0) { ?>
                                                                <strong>Please Submit your Recording to proceed to the next question</strong>
                                                            <?php } else {  ?>
                                                                <strong>Please Submit your Recording to finish</strong>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="hr-innerpadding">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <div class="form-group autoheight">
                                                                                <label>Response Video: <span class="staric">*</span></label>
                                                                                <div class="row">
                                                                                    <div class="col-lg-2 col-md-2 col-xs-4 col-sm-3">
                                                                                        <label class="control control--radio" id="record-vid-radio">
                                                                                            <input class="video_source" type="radio" name="video_source" value="recorded" checked="checked" /> Record
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <!--
                                                                                <div class="col-lg-2 col-md-2 col-xs-4 col-sm-3">
                                                                                    <label class="control control--radio">
                                                                                        <input class="video_source" type="radio" name="video_source" value="youtube" /> Youtube
                                                                                        <div class="control__indicator"></div>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-2 col-xs-4 col-sm-3">
                                                                                    <label class="control control--radio">
                                                                                        <input class="video_source" type="radio" name="video_source" value="vimeo" /> Vimeo
                                                                                        <div class="control__indicator"></div>
                                                                                    </label>
                                                                                </div>
                                                                                -->
                                                                                </div>
                                                                                <div class="row" style="margin-top: 8px;">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="video_record_note">
                                                                                        <strong>Note:</strong> &nbsp;&nbsp;You can click on the "Start Recording" button to record you answer and click on "Stop Recording" to stop the video. After this you can see the recorded video on the second screen for preview. Now, you can submit the video by clicking on the "Submit" button.
                                                                                    </div>    
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="video_upload_note">
                                                                                        <strong>Note:</strong> &nbsp;&nbsp;You can either directly record the video answer from camera or upload a pre-recorded answer by clicking on the "Upload" button. After this you can see the recorded video on the next screen for preview. Now, you can submit the video by clicking on the "Submit" button.
                                                                                    </div>
                                                                                </div>    
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" id="yt_video_container">
                                                                        <div class="col-xs-12">
                                                                            <div class="form-group autoheight">
                                                                                <label>Youtube video for this job:</label>
                                                                                <input class="form-control invoice-fields" type="text" name="youtube_video" id="youtube_video" value="<?php echo set_value('youtube_video'); ?>" placeholder="Youtube Video URL">
                                                                                <div class="video-link"><em><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX</em></div>
                                                                                <?php echo form_error('youtube_video'); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" id="vm_video_container">
                                                                        <div class="col-xs-12">
                                                                            <div class="form-group autoheight">
                                                                                <label>Vimeo video for this job:</label>
                                                                                <input class="form-control invoice-fields" type="text" name="vimeo_video" id="vimeo_video" value="<?php echo set_value('vimeo_video'); ?>" placeholder="Vimeo Video URL">
                                                                                <div class="video-link"><em><b>e.g.</b> https://vimeo.com/XXXXXXX </em></div>
                                                                                <?php echo form_error('vimeo_video'); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="row" id="rec_video_container">
                                                                <div class="col-xs-12">
                                                                    <div class="video-interview-sec">
                                                                        <div class="row">
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <div id="record-video-box" class="video-box dash-box">
                                                                                    <video id="gum" autoplay muted></video>
                                                                                </div>
                                                                                <div id="upload-video-box" style="display:none" class="dash-box">
                                                                                    <input type="file" id="upload-video-file" name="uploaded_video">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <div class="video-box dash-box">
                                                                                    <video id="recorded"></video>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="row" style="margin-top: 10px;">
                                                                <div class="col-lg-12">
                                                                    <div class="multistep-progress-form">
                                                                        <ul style="list-style: none;">
                                                                            <input type="hidden" name="video" id="video" value="" />
                                                                            <li class="form-col-50-left">
                                                                                <input type="submit" name="submit" id="submit" class="submit-btn" value="Continue" />
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="dash-box">
                                                                        <div class="btn-panel no-padding" id="rec_controls">
                                                                            <button type="button" id="record" class="btn btn-success">Start Recording</button>
                                                                            <button type="button" id="play" disabled class="btn btn-success">Play</button>
                                                                            <button type="button" id="download" disabled class="btn btn-success">Submit Your Recording</button>
                                                                        </div>
                                                                        <div class="btn-panel no-padding" id="yt_vm_controls">
                                                                            <input type="submit" name="submit" id="submit_youtube_vimeo_video" class="btn btn-success" value="Continue" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php } else { ?>
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-lg-12">
                                                        <div class="multistep-progress-form">
                                                            <form id="video_form" name="video_form" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" id="sent_record_sid" name="sent_record_sid" value="<?php echo $questionnaire['sent_record_sid']; ?>" />
                                                                <ul style="list-style: none;">
                                                                    <li>
                                                                        <br />
                                                                        <br />
                                                                        <!--<label><?php /*echo $questionnaire['question_text']; */ ?></label>-->
                                                                        <?php if (!empty($questionnaire['video_name'])) { ?>
                                                                            <br />
                                                                            <p><b>Question Video : </b></p>
                                                                            <div class="video-interview-sec">
                                                                                <div class="video-box dash-box text-center">
                                                                                    <video controls>
                                                                                        <source src="<?php echo STORE_PROTOCOL_SSL . CLOUD_VIDEO_LIBRARY . '.s3.amazonaws.com/' . $questionnaire['video_name']; ?>" type="video/webm">
                                                                                        Your browser does not support HTML5 video.
                                                                                    </video>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <br />
                                                                        <div class="upload-file">
                                                                            <textarea class="invoice-fields-textarea" name="text_response" id="text_response" cols="60" rows="10"></textarea>
                                                                            <?php echo form_error('text_response'); ?>
                                                                        </div>
                                                                    </li>
                                                                    <li class="form-col-50-left">
                                                                        <input type="submit" name="submit" id="submit" class="submit-btn" value="Continue" />
                                                                    </li>
                                                                </ul>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>

    <script>
        $(document).ready(function() {
            var type = '<?php echo $questionnaire['question_type']; ?>';

            if (type == 'video') {
                $('#submit').hide();
            }

            if (type == 'text') {
                var text = ($('#text_response').val()).trim();
                $('#text_response').val(text);
            }

            video_source_selection();

            $('.video_source').on('click', function() {
                video_source_selection();
            });

            var type = '<?php echo $questionnaire['question_type']; ?>';

            $("#video_form").validate({
                ignore: [],
                rules: {
                    youtube_video: {
                        required: function(element) {
                            if (type === 'video') {
                                return $('input[name="video_source"]:checked').val() === 'youtube';
                            } else {
                                return false;
                            }
                        },
                        pattern: /^(http|https):\/\/(?:www\.)?youtube.com\/watch\?(?=.*v=\w+)(?:\S+)?$/i
                    },
                    vimeo_video: {
                        required: function(element) {
                            if (type === 'video') {
                                return $('input[name="video_source"]:checked').val() === 'vimeo';
                            } else {
                                return false;
                            }
                        },
                        pattern: /(http|https)?:\/\/(?:www\.|player\.)?vimeo.com\/(\d+)(?:$|\/|\?)/i
                    },
                    video: {
                        required: function(element) {
                            if (type === 'video') {
                                return $('input[name="video_source"]:checked').val() === 'recorded';
                            } else {
                                return false;
                            }
                        }
                    }
                },
                messages: {
                    video: {
                        required: 'Response Video is required'
                    }
                }
            });
        });

        function video_source_selection() {
            var video_source = $('input[name="video_source"]:checked').val();

            if (video_source === 'youtube') {
                $('#yt_video_container').show();
                $('#vm_video_container').hide();
                $('#rec_video_container').hide();
                $('#rec_controls').hide();
                $('#yt_vm_controls').show();
            } else if (video_source === 'vimeo') {
                $('#yt_video_container').hide();
                $('#vm_video_container').show();
                $('#rec_video_container').hide();
                $('#rec_controls').hide();
                $('#yt_vm_controls').show();
            } else if (video_source === 'recorded') {
                $('#yt_video_container').hide();
                $('#vm_video_container').hide();
                $('#rec_video_container').show();
                $('#rec_controls').show();
                $('#yt_vm_controls').hide();
            }
        }

        function check_file(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();

                if (val == 'video') {
                    if (ext != "webm") {
                        $("#" + val).val(null);
                        alertify.error("Please select a webm video file.");
                        $('#name_' + val).html('<p class="red">Only (.webm) allowed!</p>');
                        return false;
                    } else
                        $('#video-error').text('');
                    return true;
                }
            } else {
                $('#name_' + val).html('No file selected');
            }
        }

        //             'use strict';
        //             var mediaSource = new MediaSource();
        //             mediaSource.addEventListener('sourceopen', handleSourceOpen, false);
        //             var mediaRecorder;
        //             var recordedBlobs;
        //             var sourceBuffer;
        //             var gumVideo = document.querySelector('video#gum');
        //             var recordedVideo = document.querySelector('video#recorded');
        //             var recordButton = document.querySelector('button#record');
        //             var playButton = document.querySelector('button#play');
        //             var downloadButton = document.querySelector('button#download');
        //             recordButton.onclick = toggleRecording;
        //             playButton.onclick = play;
        //             downloadButton.onclick = download;

        //             var isSecureOrigin = location.protocol === 'https:' || location.host === 'localhost';

        //             if (!isSecureOrigin) {
        //                 alert('getUserMedia() must be run from a secure origin: HTTPS or localhost.' +
        //                     '\n\nChanging protocol to HTTPS');
        //                 location.protocol = 'HTTPS';
        //             }

        //             navigator.getUserMedia = navigator.getUserMedia ||
        //                 navigator.webkitGetUserMedia ||
        //                 navigator.mozGetUserMedia ||
        //                 navigator.msGetUserMedia;

        //             var constraints = {
        //                 audio: true,
        //                 video: true
        //             };

        //             navigator.getUserMedia(constraints, successCallback, errorCallback);

        //             function successCallback(stream) {
        // //                console.log('getUserMedia() got stream: ', stream);
        //                 window.stream = stream;
        //                 if (window.URL) {
        //                     gumVideo.src = window.URL.createObjectURL(stream);
        //                 } else {
        //                     gumVideo.src = stream;
        //                 }
        //             }

        //             function errorCallback(error) {
        //                 console.log('navigator.getUserMedia error: ', error);
        //             }

        //             function handleSourceOpen(event) {
        // //                console.log('MediaSource opened');
        //                 sourceBuffer = mediaSource.addSourceBuffer('video/webm; codecs="vp8"');
        // //                console.log('Source buffer: ', sourceBuffer);
        //             }

        //             function handleDataAvailable(event) {
        //                 if (event.data && event.data.size > 0) {
        //                     recordedBlobs.push(event.data);
        //                 }
        //             }

        //             function handleStop(event) {
        //                 console.log('Recorder stopped: ', event);
        //             }

        //             function toggleRecording() {
        //                 if (recordButton.textContent === 'Start Recording') {
        //                     startRecording();
        //                 } else {
        //                     stopRecording();
        //                     recordButton.textContent = 'Start Recording';
        //                     playButton.disabled = false;
        //                     downloadButton.disabled = false;
        //                     play();
        //                 }
        //             }

        //             function startRecording() {
        //                 var options = {mimeType: 'video/webm', bitsPerSecond: 100000};
        //                 recordedBlobs = [];
        //                 try {
        //                     mediaRecorder = new MediaRecorder(window.stream, options);
        //                 } catch (e0) {
        // //                    console.log('Unable to create MediaRecorder with options Object: ', e0);
        //                     try {
        //                         options = {mimeType: 'video/webm,codecs=vp9', bitsPerSecond: 100000};
        //                         mediaRecorder = new MediaRecorder(window.stream, options);
        //                     } catch (e1) {
        // //                        console.log('Unable to create MediaRecorder with options Object: ', e1);
        //                         try {
        //                             options = 'video/vp8';
        //                             mediaRecorder = new MediaRecorder(window.stream, options);
        //                         } catch (e2) {
        //                             alert('MediaRecorder is not supported by this browser.\n\n' +
        //                                 'Try Firefox 29 or later, or Chrome 47 or later, with Enable experimental Web Platform features enabled from chrome://flags.');
        // //                            console.error('Exception while creating MediaRecorder:', e2);
        //                             return;
        //                         }
        //                     }
        //                 }
        // //                console.log('Created MediaRecorder', mediaRecorder, 'with options', options);
        //                 recordButton.textContent = 'Stop Recording';
        //                 playButton.disabled = true;
        //                 downloadButton.disabled = true;
        //                 mediaRecorder.onstop = handleStop;
        //                 mediaRecorder.ondataavailable = handleDataAvailable;
        //                 mediaRecorder.start(10);
        // //                console.log('MediaRecorder started', mediaRecorder);
        //             }

        //             function stopRecording() {
        //                 mediaRecorder.stop();
        // //                console.log('Recorded Blobs: ', recordedBlobs);
        //                 recordedVideo.controls = true;
        //             }

        //             function play() {
        //                 var superBuffer = new Blob(recordedBlobs, {type: 'video/webm'});
        //                 recordedVideo.src = window.URL.createObjectURL(superBuffer);
        //             }

        //             function download() {
        //                 var blob = new Blob(recordedBlobs, {type: 'video/webm'});
        //                 var url = window.URL.createObjectURL(blob);
        //                 var a = document.createElement('a');
        //                 var reader = new FileReader();

        //                 reader.onload = function (event) {
        //                     $('#download').text('Uploading...');
        //                     $("#download").prop("disabled", true);
        //                     var fd = new FormData();
        //                     fd.append('fname', 'test.webm');
        //                     fd.append('data', event.target.result);
        //                     $.ajax({
        //                         type: 'POST',
        //                         url: '<?php //echo base_url() . "video_interview_system/upload"; 
                                            ?>',
        //                         data: fd,
        //                         processData: false,
        //                         contentType: false
        //                     }).done(function (data) {
        //                         if (data == 'error') {
        //                             // do something on error
        //                         } else {
        //                             $("#record").prop("disabled", true);
        //                             $("#play").prop("disabled", true);
        //                             $('#video').val(data);
        //                             //$('#download').text('Uploaded');
        //                             $('#submit').click();
        //                         }
        //                     });
        //                 };
        //                 reader.readAsDataURL(blob);
        //             }




        // WEBRTC Base Player
        // Updated by M
        function iffyByM(
            opt
        ) {
            //
            let constraints = {
                audio: true,
                video: {
                    width: 1280,
                    height: 720
                    // facingMode: true == false ? 'user' : 'environment' 
                }
            };
            let fromPlayer = document.querySelector('video#gum');
            let recordedVideo = document.querySelector('video#recorded');
            let recordButton = document.querySelector('button#record');
            let playButton = document.querySelector('button#play');
            let downloadButton = document.querySelector('button#download');
            let recordedBlobs = [];
            let recordedBlobType = 'video/webm';
            let sourceBuffer = null;
            let stream = null;
            let video = {};

            let mediaSource = window.MediaSource === undefined ? undefined : new MediaSource();
            let mediaRecorder = null;


            //
            var handleSuccess = function(stream) {
                video = fromPlayer;
                const videoTracks = stream.getVideoTracks();
                video.srcObject = stream;
                window.stream = stream;
                mediaRecorder = new MediaRecorder(stream);
                recordButton.onclick = toggleRecording;
                playButton.onclick = play;
                downloadButton.onclick = download;
            }

            //
            var handleError = function(error) {
                if (error.name === 'ConstraintNotSatisfiedError') {
                    const v = constraints.video;
                    errorMsg(`The resolution ${v.width.exact}x${v.height.exact} px is not supported by your device.`);
                } else if (error.name === 'PermissionDeniedError') {
                    errorMsg('Permissions have not been granted to use your camera and ' +
                        'microphone, you need to allow the page access to your devices in ' +
                        'order for the demo to work.');
                }
                errorMsg(`getUserMedia error: ${error.name}`, error);
            }

            //
            var startRecording = function() {
                var options = {
                    mimeType: 'video/webm',
                    bitsPerSecond: 100000
                };
                recordedBlobs = [];
                recordButton.textContent = 'Stop Recording';
                playButton.disabled = true;
                downloadButton.disabled = true;
                mediaRecorder.onstop = handleStop;
                mediaRecorder.ondataavailable = handleDataAvailable;
                mediaRecorder.start(10);
            }

            //
            var toggleRecording = function() {
                if (recordButton.textContent === 'Start Recording') {
                    recordedVideo.src = null;
                    startRecording();
                } else {
                    stopRecording();
                    recordButton.textContent = 'Start Recording';
                    playButton.disabled = false;
                    downloadButton.disabled = false;
                    play();
                }
            }

            //
            var stopRecording = function() {
                mediaRecorder.stop();
                recordedVideo.controls = true;
            }

            //
            var play = function(e) {
                var superBuffer = new Blob(recordedBlobs, {
                    type: recordedBlobType
                });
                recordedVideo.src = window.URL.createObjectURL(superBuffer);
                if (e !== undefined) recordedVideo.play();
            }

            //
            var handleStop = function(event) {
                console.log('Recorder stopped: ', event);
            }

            //
            var handleSourceOpen = function(event) {
                sourceBuffer = mediaSource.addSourceBuffer('video/webm; codecs="vp8"');
            }

            //
            var handleDataAvailable = function(event) {
                if (event.data && event.data.size > 0) {
                    recordedBlobs.push(event.data);
                }
            }

            //
            var download = function() {
                if (recordedBlobs[0].name !== undefined) {
                    uploadVideoToServer(recordedBlobs[0]);
                    return;
                } else {
                    let blob = new Blob(recordedBlobs, {
                        type: recordedBlobType
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        uploadVideoToServer(event.target.result);
                    }
                    reader.readAsDataURL(blob);
                }

            }

            function uploadVideoToServer(videoOBJ) {
                $('#download').text('Uploading...');
                $("#download").prop("disabled", true);
                var fd = new FormData();
                fd.append('fname', 'test.webm');
                fd.append('data', videoOBJ);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url() . "video_interview_system/upload"; ?>',
                    data: fd,
                    processData: false,
                    contentType: false
                }).done(function(data) {
                    if (data == 'error') {
                        // do something on error
                    } else {
                        $("#record").prop("disabled", true);
                        $("#play").prop("disabled", true);
                        $('#video').val(data);
                        //$('#download').text('Uploaded');
                        $('#submit').click();
                    }
                });
            }

            //
            var errorMsg = function(msg, error) {
                alert(error);
            }

            //
            var init = async function() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia(
                        constraints
                    );
                    handleSuccess(stream);
                } catch (e) {
                    handleError(e);
                }
            }


            $(document).on("change", "#upload-video-file", function() {
                //
                recordedBlobs = [];
                //
                $('.video-box').html('<video id="recorded" controls="true"></video>');
                //var video = document.getElementById('recorded');
                var videoURL = URL.createObjectURL(this.files[0]);
                $('video#recorded').html('<source src="' + (videoURL) + '"></source>');
                recordedBlobType = 'video/mp4';
                // var source = document.createElement('source');
                // source.setAttribute('src', videoURL);
                // recordedVideo.setAttribute('controls', true);
                recordedBlobs.push(this.files[0]);
                playButton.onclick = play;
                downloadButton.onclick = download;
                $("#download").prop("disabled", false);
                $("#play").prop("disabled", false);
            });



            //
            var isWebRTCSupported = navigator.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia ||
                navigator.msGetUserMedia ||
                window.RTCPeerConnection;

            if (isWebRTCSupported && mediaSource !== undefined) {
                <?php if ($questionnaire['question_type'] === 'video') { ?>
                    init();
                <?php } ?>
                $("#video_upload_note").hide();
                $("#video_record_note").show();
            } else {
                $("#upload-video-box").show();
                $("#record-video-box").hide();
                $("#record").hide();
                $("#record-vid-radio").hide();
                $("#video_upload_note").show();
                $("#video_record_note").hide();
                //$('#play').hide();

            }

        };

        iffyByM();
    </script>
    <style>
        .upload-file a {
            height: 38px;
        }

        .form-col-50-left {
            margin-top: 10px;
        }

        @media screen and (max-width: 767px) {}

        .upload-file.invoice-fields {
            margin-bottom: 35px;
        }

        #video-error {
            margin-top: 10px;
            margin-left: -5px;
        }
    </style>
</body>

</html>