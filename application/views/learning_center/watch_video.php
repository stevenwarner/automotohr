
<?php if (!$load_view) { ?>

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

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
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
                                    <a class="dashboard-link-btn" href="<?php echo $back_url; ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i>Online Videos</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <strong style="font-size: 20px;"><?php echo $video['video_title']; ?><span class="pull-right">
                                        <button class="btn btn-danger" id="jsRevokeVideo"><i class="fa fa-times-circle" style="font-size: 14px;" aria-hidden="true"></i>&nbsp;Revoke Video</button>
                                    </span></strong>
                                    <p><?php echo $video['video_description']; ?></p>
                                    
                                </div>
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
                    <?php 
                    if(!empty($job_details)) {
                        ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <div class="pull-left">
                                            <strong style="font-size: 20px;">Video watch status: </strong><span style="font-size: 20px;"> <?php echo empty($assignment['watched']) || $assignment['watched'] == 0 ? "Pending" : "Watched"; ?></span>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="pull-right">
                                            <strong style="font-size: 20px;">Questionnaire Result: </strong><span style="font-size: 20px;" class="<?=$questionnaire_result == 'Pass' ? 'text-success' : 'text-danger'; ?>"> <?php echo empty($questionnaire_result) ? "Pending" : $questionnaire_result; ?></span>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <strong style="font-size: 20px;">Questionnaire</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        //
                        $questionId = $job_details['my_id'];
                        foreach($job_details[$questionId] as $question){
                            //
                            $answer = isset($job_details['q_answer_'.$question['questions_sid']]) ? $job_details['q_answer_'.$question['questions_sid']] : [];
                        ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <label><?=$question['caption'];?></label> <br />
                                            <?php if($question['question_type'] == 'boolean') {
                                                foreach($answer as $a){ ?>
                                                <label class="control control--radio">
                                                    <input type="radio" disabled <?=getAnswer($answers_given[0], $question['caption'], false, $a['value']);?> /><?=$a['value'];?>
                                                    <div class="control__indicator"></div>
                                                </label> &nbsp;
                                            <?php 
                                                }
                                            } else if($question['question_type'] == 'string') { ?>
                                            <textarea class="form-control" disabled><?=getAnswer($answers_given[0], $question['caption'], true);?></textarea>
                                            <?php } else if($question['question_type'] == 'list') { ?>
                                            <select class="form-control" disabled>
                                                <option value="">Please Select</option>
                                                <?php 
                                                 foreach($answer as $a){ ?>
                                                    <option value="<?=$a['value'];?>" <?=getAnswer($answers_given[0], $question['caption'], false, $a['value'], true);?>><?=$a['value'];?></option>
                                                <?php } ?>
                                            </select>
                                            <?php } else if($question['question_type'] == 'multilist'){
                                                foreach($answer as $a){ 
                                                ?>
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" disabled <?=getAnswer($answers_given[0], $question['caption'], false, $a['value']);?> /><?=$a['value'];?>
                                                    <div class="control__indicator"></div>
                                                </label> 
                                                <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } ?>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"></div>
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <form id="form_mark_video_as_watched" enctype="multipart/form-data" method="post">
                                <input type="hidden" id="perform_action" name="perform_action" value="mark_video_as_watched" />
                                <input type="hidden" id="video_sid" name="video_sid" value="<?php echo $video['sid']; ?>" />
                                <input type="hidden" id="user_type" name="user_type" value=<?= $user_type?> />
                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $employer_sid; ?>" />
                            </form>
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

    $(function(){
        $('#jsRevokeVideo').click(function(event){
            //
            event.preventDefault();
            //
            alertify.confirm(
                "This action will remove the <?=$user_type;?> from this video and remove the saved data.",
                function(){
                    revokeVideoAccess();
                }
            ).setHeader('Confirm!').set('label', {
                ok: "Yes",
                cancel: "No"
            });
        });

        //
        function revokeVideoAccess(){
            $.post("<?=base_url("learning_center/video_access");?>", {
                action: 'revoke',
                userId: <?=$employer_sid;?>,
                userType: "<?=$user_type;?>",
                videoId: "<?=$video['sid'];?>"
            }).done(function(resp){
                //
                if(resp == 'success'){
                    alertify.alert('Success!', "You have successfully removed this <?=$user_type;?> from video.", function(){
                        window.location.href = "<?=base_url('/learning_center/my_learning_center/'.($employer_sid).'');?>"
                    });
                } else{
                    alertify.alert('Warning!', "Something went wrong. Please, try again in a few moments.");
                }
            });
        }
    });
</script>

<?php } else { ?>
    <?php $this->load->view('learning_center/watch_video_new'); ?>
<?php } ?>