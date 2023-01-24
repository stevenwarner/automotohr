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
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a class="dashboard-link-btn" href="<?php 
                            if(isset($template_sid)){
                                echo base_url('video_interview_system/manage_template') . '/' . $template_sid; 
                            } else {
                                echo base_url('video_interview_system'); 
                            }
                            ?>">
                                <i class="fa fa-chevron-left"></i>
                                Back
                            </a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                </div>      
                <div class="row">
                    <div class="col-lg-12">
                        <div class="multistep-progress-form">
                            <div class="universal-form-style-v2">
                                <form id="edit_question" name='edit_question' class="msform" method="post" enctype="multipart/form-data">
                                    <fieldset id="create_div">

                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <label>Answer type: (This is where you ask the candidate):</label>
                                                <div class="field-row">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-4">
                                                            <label class="control control--radio">
                                                                <input type="radio" name="question_type" value="text" <?php echo ($question['question_type'] == 'text') ? 'checked' : ''; ?>>
                                                                Text
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-4">
                                                            <label class="control control--radio">
                                                                <input type="radio" name="question_type" value="video" <?php echo ($question['question_type'] == 'video') ? 'checked' : ''; ?>>
                                                                Video
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php echo form_error('question_type'); ?>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight" id="text_div">
                                                <div class="description-editor">
                                                    <label>Question : <span class="staric">*</span></label>
                                                        <textarea class="invoice-fields-textarea" name="question_text" id="question_text" cols="60" rows="10"><?php echo (isset($question['question_text'])) ? $question['question_text'] : ''; ?></textarea>
                                                    <?php echo form_error('question_text'); ?>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight" id="video_div">
                                                <div class="description-editor">
                                                    <label>Video Question Title: <span class="staric">*</span></label>
                                                    <input type="text" class="invoice-fields" name="video_title" id="video_title" value="<?php echo (isset($question['video_title'])) ? $question['video_title'] : ''; ?>"/>
                                                    <?php echo form_error('video_title'); ?>
                                                </div>
                                            </li>
                                        </ul>

                                    </fieldset>
                                    <input type="hidden" name="video" id="video" value="" />
                                    <!-- current video div -->

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group autoheight">
                                                <label>Do you want to add video with question? <span class="staric">*</span></label>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-xs-4 col-sm-3">
                                                        <label class="control control--radio">
                                                            <input class="video_source" type="radio" name="video_source" value="recorded" <?php echo ($question['video_source'] == 'recorded') ? 'checked' : ''; ?> /> Record
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-4 col-sm-3">
                                                        <label class="control control--radio">
                                                            <input class="video_source" type="radio" name="video_source" value="youtube" <?php echo ($question['video_source'] == 'youtube') ? 'checked' : ''; ?>/> Youtube
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-4 col-sm-3">
                                                        <label class="control control--radio">
                                                            <input class="video_source" type="radio" name="video_source" value="vimeo" <?php echo ($question['video_source'] == 'vimeo') ? 'checked' : ''; ?>/> Vimeo
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


<!--                                    --><?php //if(!empty($question['video_name'])) { ?>
<!--                                        <div id="current_div">-->
<!--                                            <p><b>Current Video : </b></p>-->
<!---->
<!--                                            <div class="video-interview-sec">-->
<!--                                                <div class="row">-->
<!--                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">-->
<!--                                                        <div class="video-box dash-box no-margin text-center">-->
<!--                                                            <video controls>-->
<!--                                                                <source src="--><?php //echo STORE_PROTOCOL_SSL . CLOUD_VIDEO_LIBRARY . '.s3.amazonaws.com/' . $question['video_name']; ?><!--" type="video/webm">-->
<!--                                                                Your browser does not support HTML5 video.-->
<!--                                                            </video>-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    --><?php //} ?>
                                    <div class="row" id="yt_video_container">
                                        <div class="col-xs-12">
                                            <div class="form-group autoheight">
                                                <label>Youtube video for this job:</label>
                                                <input  class="form-control invoice-fields"  type="text" name="youtube_video" id="youtube_video" value="<?php echo $question['video_source'] == 'youtube' ? 'https://www.youtube.com/watch?v='.$question['video_id'] : '' ?>" placeholder="Youtube Video URL" >
                                                <div class="video-link"><em><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX</em></div>
                                                <?php if($question['video_source'] == 'youtube'){?>
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe src="https://www.youtube.com/embed/<?php echo $question['video_id']; ?>"></iframe>
                                                    </div>
                                                <?php }?>
                                                <?php echo form_error('youtube_video'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="vm_video_container">
                                        <div class="col-xs-12">
                                            <div class="form-group autoheight">
                                                <label>Vimeo video for this job:</label>
                                                <input  class="form-control invoice-fields"  type="text" name="vimeo_video" id="vimeo_video" value="<?php echo $question['video_source'] == 'vimeo' ? 'https://vimeo.com/'.$question['video_id'] : '' ?>" placeholder="Vimeo Video URL" >
                                                <div class="video-link"><em><b>e.g.</b> https://vimeo.com/XXXXXXX </em></div>
                                                <?php if($question['video_source'] == 'vimeo'){?>
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe src="https://player.vimeo.com/video/<?php echo $question['video_id']; ?>?title=0&byline=0&portrait=0&loop=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                        <script src="https://player.vimeo.com/api/player.js"></script>
                                                    </div>
                                                <?php }?>
                                                <?php echo form_error('vimeo_video'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- current video div -->
                                    <!-- recording video div -->
                                    <div class="form-col-100" id="rec_video_container">
                                        <div class="field-row">
                                            <label>Do you want to Record a Video :</label>
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-4">
                                                    <label class="control control--radio">
                                                        <input type="radio" name="video_recorded" value="no" checked="checked"> No
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-6 col-sm-4">
                                                    <label class="control control--radio">
                                                        <input type="radio" name="video_recorded" value="yes"> Yes
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="video-interview-sec">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="video-box dash-box no-margin">
                                                        <video id="gum" autoplay muted></video>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="video-box dash-box no-margin">
                                                        <video id="recorded" autoplay loop controls>
                                                            <source src="<?php echo STORE_PROTOCOL_SSL . CLOUD_VIDEO_LIBRARY . '.s3.amazonaws.com/' . $question['video_name']; ?>" type="video/mp4">
                                                        </video>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-panel">
                                            <button type="button" id="record" class="btn btn-success">Start Recording</button>
                                            <button type="button" id="play" disabled class="btn btn-success">Play</button>
                                            <button type="button" id="download" disabled class="btn btn-success">Upload Video</button>
                                        </div>
                                    </div>
                                    <!-- recording video div -->
                                    <div class="dash-box text-center">
                                        <input type="submit" value="Save Question" class="btn btn-lg btn-success" id="edit_question_submit" name="edit_question_submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var baseurl_js = '<?php echo base_url(); ?>';
</script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<?php if ($question['question_type'] == 'video') { ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/webrtc.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/webrtc.css">
<?php } ?>
<script>

    $(document).ready(function(){
        adjust_radio_display();
        var ques_text = ($('#question_text').val()).trim();
        $('#question_text').val(ques_text);

        video_source_selection();

        $('.video_source').on('click', function(){
            video_source_selection();
        });
    });

    function video_source_selection(){
        var video_source = $('input[name="video_source"]:checked').val();
        if(video_source === 'youtube'){
            $('#yt_video_container').show();
            $('#vm_video_container').hide();
            $('#rec_video_container').hide();
        } else if (video_source === 'vimeo') {
            $('#yt_video_container').hide();
            $('#vm_video_container').show();
            $('#rec_video_container').hide();
        } else if (video_source === 'recorded') {
            $('#yt_video_container').hide();
            $('#vm_video_container').hide();
            $('#rec_video_container').show();
        }
    }

    $('#edit_question_submit').click(function () {
        var ques_text = ($('#question_text').val()).trim();
        $('#question_text').val(ques_text);

        $("#add_new_question").validate({
            ignore: [],
            rules: {
                question_text: {required: function (element) {
                    return $('input[name="question_type"]:checked').val() == 'text';
                }
                },
                video: {required: function (element) {
                    return $('input[name="video_recorded"]:checked').val() == 'yes';
                }
                },
                video_title: {required: function (element) {
                    return $('input[name="question_type"]:checked').val() == 'video';
                }
                }
            },
            messages: {
                question_text: {
                    required: 'Question Text is required'
                },
                video: {
                    required: 'Question Video is required'
                },
                video_title: {
                    required: 'Video Question Title is required'
                }
            }
        });
    });

    function adjust_radio_display(){
        var div_to_show = $('input[name="question_type"]:checked').val();
        if(div_to_show == 'video'){
            $('#text_div').hide();
            $('#video_div').show();
            //$('#record_div').show();
        } else {
            $('#text_div').show();
            $('#video_div').hide();
            //$('#record_div').hide();
        }
    }



    $('input[name="question_type"]').change(function (e) {
        adjust_radio_display();
    });

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

//    $(document).ready(function(){
//        adjust_radio_display();
//        var ques_text = ($('#question_text').val()).trim();
//        $('#question_text').val(ques_text);
//    });
    
//    $('#edit_question_submit').click(function () {
//        var ques_text = ($('#question_text').val()).trim();
//        $('#question_text').val(ques_text);
//
//        $("#edit_question").validate({
//            ignore: [],
//            rules: {
//                question_text: {required: function (element) {
//                        return $('input[name="question_type"]:checked').val() == 'text';
//                    }
//                },
//                video: {required: function (element) {
//                        return (($('#current_video').val() == 'no') && ($('input[name="question_type"]:checked').val() == 'video')) ;
//                    }
//                },
//                video_title: {required: function (element) {
//                        return $('input[name="question_type"]:checked').val() == 'video';
//                    }
//                }
//            },
//            messages: {
//                question_text: {
//                    required: 'Question Text is required'
//                },
//                video: {
//                    required: 'Question Video is required'
//                },
//                video_title: {
//                    required: 'Video Title is required'
//                }
//            }
//        });
//    });
    
//    function adjust_radio_display(){
//        var div_to_show = $('input[name="question_type"]:checked').val();
//        if(div_to_show == 'video'){
//            $('#text_div').hide();
//            $('#video_div').show();
//            $('#record_div').show();
//            $('#current_div').show();
//        } else {
//            $('#text_div').show();
//            $('#video_div').hide();
//            $('#record_div').hide();
//            $('#current_div').hide();
//        }
//    }
    
//    $('input[name="question_type"]').change(function (e) {
//        adjust_radio_display();
//    });
//
//            function check_file(val) {
//                var fileName = $("#" + val).val();
//                if (fileName.length > 0) {
//                    $('#name_' + val).html(fileName.substring(0, 45));
//                    var ext = fileName.split('.').pop();
//                    if (val == 'video') {
//                        if (ext != "webm") {
//                            $("#" + val).val(null);
//                            alertify.error("Please select a webm video file.");
//                            $('#name_' + val).html('<p class="red">Only (.webm) allowed!</p>');
//                            return false;
//                        } else
//                            $('#video-error').text('');
//                            return true;
//                    }
//                } else {
//                    $('#name_' + val).html('No file selected');
//                }
//            }
            
</script>