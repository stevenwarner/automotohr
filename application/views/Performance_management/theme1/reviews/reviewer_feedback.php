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
                    <span class="csBTNBoxLeft">
                        <select id="jsFilterReviewPeriod" class="dn">
                            <option value="">Jan 01 - Jan 15</option>
                        </select>
                    </span>
                    <span class="csBTNBox">
                        <a href="<?=purl('download/reviewer_feedback/'.($pid).'/'.($review['Reviewee'][0]['reviewee_sid']).''.'/'.($employerId).'');?>" class="btn btn-orange btn-lg"><i class="fa fa-download"></i> Download As PDF</a>
                    <?php if(in_array($employerId, array_column($review['Reviewer']['reviewer_sid']))){ ?>

                        <a href="javascript:void(0);" class="btn btn-orange btn-lg jsQuestionSaveBtn"><i class="fa fa-save"></i> Save</a>
                        <a href="javascript:void(0)" class="btn btn-black btn-lg jsQuestionFLBtn"><i class="fa fa-pencil-square-o"></i> Finish Later</a>
                    <?php } ?>
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
                <!-- Body -->
                <div class="csPageBoxBody p10">
                    <!-- Loader -->
                    <div class="csIPLoader jsIPLoader dn" data-page="review_listing"><i class="fa fa-circle-o-notch fa-spin"></i></div>
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
                        <?php if(!empty($ques['video_help'])): ?>
                            <video src="<?=getVideoURL($pid, $key);?>" controls="true" style="width: 100%;"></video>
                        <?php endif;?>
                        <div class="jsQuestionBox" data-id="<?=$question['sid'];?>">
                            <?php echo getQuestionBody($ques, $answ); ?>
                        </div>
                        <!--  -->
                        <!-- <div class="clearfix"></div> -->
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if(in_array($employerId, array_column($review['Reviewer']['reviewer_sid']))){ ?>
                <!-- Footer -->
                <div class="csPageBoxFooter p10">
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="csBTNBox ma10">
                                <a href="javascript:void(0);" class="btn btn-orange btn-lg jsQuestionSaveBtn"><i class="fa fa-save"></i> Save</a>
                                <a href="javascript:void(0)" class="btn btn-black btn-lg jsQuestionFLBtn"><i class="fa fa-pencil-square-o"></i> Finish Later</a>
                            </span>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<script>
    answers = <?=json_encode($answers);?>;
</script>