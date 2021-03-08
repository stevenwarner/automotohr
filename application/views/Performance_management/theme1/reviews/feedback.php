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
                        <?php if(!empty($ques['video_link'])): ?>
                            <video src="<?=getVideoURL($ques['video_link'], $pid);?>" controls="true" style="width: 100%;"></video>
                        <?php endif;?>
                        <div class="csFeedbackViewBoxPreview">
                            <div class="csFeedbackViewBoxPreviewRow">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="csEBox">
                                            <figure>
                                                <img src="<?=randomData('img');?>" class="csRadius50" />
                                            </figure>
                                            <div class="csEBoxText">
                                                <h4 class="mb0"><strong><?=randomData('name');?></strong></h4>
                                                <p class="mb5">(QA) [Admin Plus]</p>
                                                <a href="" class="btn btn-orange btn-xs cdRadius5">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="ma10">
                                            <ul>
                                                <li style="height: auto;" class="active">1</li>
                                                <li style="height: auto;">2</li>
                                                <li style="height: auto;">3</li>
                                                <li style="height: auto;">4</li>
                                                <li style="height: auto;">5</li>
                                            </ul>
                                            <p>Some remarks will appear here.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="csFeedbackViewBoxPreviewRow bbn">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="csEBox">
                                            <figure>
                                                <img src="<?=randomData('img');?>" class="csRadius50" />
                                            </figure>
                                            <div class="csEBoxText">
                                                <h4 class="mb0"><strong>Ahmed Saleemi</strong></h4>
                                                <p class="mb0">(HR) [Manager]</p>
                                                <a href="" class="btn btn-orange btn-xs cdRadius5">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="ma10">Some remarks will appear here.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="jsQuestionBox" data-id="<?=$question['sid'];?>">
                            <?php echo getQuestionBody($ques, $answ); ?>
                        </div>
                        <!--  -->
                        <!-- <div class="clearfix"></div> -->
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Footer -->
                <div class="csPageBoxFooter p10">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <h5><strong>Overall Feedback</strong></h5>
                            
                            <div class="csShareBox">
                                <div class="csEBox">
                                    <figure>
                                        <img src="<?=randomData('img');?>" class="csRadius50" />
                                    </figure>
                                    <div class="csEBoxText">
                                        <h4><strong><?=randomData('name');?></strong></h4>
                                        <p>(QA) [Admin Plus]</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-8 col-xs-12 ma10">
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
                                <span class="csBTNBoxLeft ma10">
                                    <a href="" class="btn btn-link">Preview what Mubashir will see if you share</a>
                                </span>
                                <span class="csBTNBox ma10">
                                    <a href="" class="btn btn-black">Cancel</a>
                                    <a href="" class="btn btn-orange">Share Feedback</a>
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
                <h4 class="pa10"><strong>MUBASHIR'S GOALS</strong></h4>
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