<div class="container-fluid">
    <!--  -->
    <div class="row">
        <!-- Left sidebar -->
        <div class="col-sm-2 col-xs-12 csSticky csStickyTop">
            <!-- Heading -->
            <div class="csPageHeading">
                <div class="row">
                    <div class="col-sm-12">
                        <a href="<?=purl('review/'.($pid).'');?>" class="btn btn-black"><i class="fa fa-long-arrow-left"></i>
                            Review Details</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="csEVBox">
                            <figure>
                                <img src="<?=$employees[$pem]['img'];?>"
                                    class="csRadius50" />
                            </figure>
                            <div class="csEBoxText">
                                <h4 class="mb0"><strong><?=$employees[$pem]['name'];?></strong></h4>
                                <p class="mb0"><?=$employees[$pem]['role'];?></p>
                                <p><?=$employees[$pem]['joined'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Area -->
        <div class="col-sm-7 col-xs-12">
            <div class="csPageBoxHeader bbn">
                <div class="csPageBoxReviewPeriod">
                    <span class="csBTNBox">
                        <a href="javascript:void(0);" class="btn btn-orange btn-lg jsQuestionSaveBtn"><i class="fa fa-save"></i> Save</a>
                        <a href="javascript:void(0)" class="btn btn-black btn-lg jsQuestionFLBtn"><i class="fa fa-pencil-square-o"></i> Finish Later</a>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Main Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Header -->
                <div class="csPageBoxHeader pl10">
                    <h3><strong><?=$review['review_title'];?></strong></h3>
                </div>
                <?php if($review['share_feedback'] == 1): ?>
                <!-- Header -->
                <div class="csPageBoxHeader p10">
                    <h5 style="color: #cc1100;"><strong><i class="fa fa-eye"></i> Your feedback will be visible to <?=$employees[$pem]['name'];?> once submitted.</strong></h5>
                </div>
                <?php endif; ?>
                <div class="jsPic">
                <!-- Body -->
                <div class="csPageBoxBody p10">
                <?php 
                        $answers = [];
                        foreach($review['Questions'] as $key => $question):
                        $ques = json_decode($question['question'], true);
                        $answ = json_decode($question['answer'], true);
                        //
                        if(!empty($answ)) {
                            $answers[$question['sid']] = $answ;
                        }
                    ?>
                    <div class="csFeedbackViewBox">
                        <h4 class="pa10 pb10"><strong>Question <?=$key +1;?></strong></h4>
                        
                        <h4><strong><?=$ques['title'];?></strong></h4>
                        <?php if(!empty($ques['description'])): ?>
                        <p><?=$ques['description'];?></p>
                        <?php endif;?>
                        <?php if(!empty($ques['video_link']) && getVideoURL($ques['video_link'], $pid)): ?>
                            <video src="<?=getVideoURL($ques['video_link'], $pid);?>" controls="true" style="width: 100%;"></video>
                        <?php endif;?>
                        
                        <div class="jsQuestionBox" data-id="<?=$question['sid'];?>">
                            <?php echo getQuestionBody($ques, $answ); ?>
                        </div>
                        <!--  -->
                        <!-- <div class="clearfix"></div> -->
                    </div>
                    <?php endforeach; ?>
                </div>
                </div>
                <!-- Footer -->
                <div class="csPageBoxFooter p10">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <h5><strong>Overall Feedback</strong></h5>
                        </div>
                        <div class="col-sm-12 col-xs-12 ma10">
                            <div class="csFeedbackViewBox bbn">
                                <ul>
                                    <li>
                                        <div class="csFeedbackViewBoxTab">
                                            <p class="mb0">1</p>
                                            <p>Strongly Agree</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="csFeedbackViewBoxTab">
                                            <p class="mb0">2</p>
                                            <p>Agree</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="csFeedbackViewBoxTab">
                                            <p class="mb0">3</p>
                                            <p>Neutral</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="csFeedbackViewBoxTab">
                                            <p class="mb0">4</p>
                                            <p>Disagree</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="csFeedbackViewBoxTab">
                                            <p class="mb0">5</p>
                                            <p>Strongly disagree</p>
                                        </div>
                                    </li>
                                </ul>
                                <textarea rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="csBtnRow ma10">
                                <?php if($review['share_feedback'] == 1){ ?>
                                <span class="csBTNBoxLeft ma10">
                                    <a href="javascript:void(0);" class="btn btn-link jsDisplay" data-name="<?=$employees[$review['Reviewee'][0]['reviewee_sid']]['name'];?>">Preview what <?=$employees[$review['Reviewee'][0]['reviewee_sid']]['name'];?> will see if you share</a>
                                </span>
                                <?php } ?>
                                <span class="csBTNBox ma10">
                                    <a href="javascript:void(0)" class="btn btn-black jsQuestionFLBtn">Cancel</a>
                                    <a href="javascript:void(0)" class="btn btn-orange jsQuestionSaveBtn">Share Feedback</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Side Bar -->
        <div class="col-sm-3 col-xs-12">
            <div class="csPageBoxHeader bbn">
                <h4 class="pa10"><strong><?=$employees[$review['Reviewee'][0]['reviewee_sid']]['name'];?>'S GOALS</strong></h4>
            </div>
            <div class="csPageBoxBody">
                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ma10">
                            <select id="jsFilterGoalType">
                                <option value="">Ongoing Goals</option>
                                <option value="">Closed Goals</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <!-- Goal Box -->
                        <!-- Error Box -->
                        <div class="csErrorBox">
                            <i class="fa fa-info-circle"></i>
                            <p> Mubashir doesn't have any goals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    answers = <?=json_encode($answers);?>;

    $(function(){
        $('.jsDisplay').click(function(event){  
            event.preventDefault();
            Modal({
                Id: "jsOverview",
                Title: `What ${$(this).data().name} will see`,
                Body: `<div class="container">${$('.jsPic').html()}</div>`,
                Loader: 'jsOverviewLoader'
            }, function(){
                ml(false, 'jsOverviewLoader');
            });
        });
    })
</script>