<div class="container-fluid">
    <!--  -->
    <div class="row">
        <!-- Left sidebar -->
           
        <!-- Content Area -->
        <div class="col-sm-12 col-xs-12">
            <div class="csPageBoxHeader bbn">
                <div class="">
                    <a href="<?=base_url('performance-management/lms/reviews');?>" class="btn btn-black csF16 csB7 ma10"><i class="fa fa-long-arrow-left"></i> Back To Reviews</a>
                </div>
            </div>
            <!-- Main Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Header -->
                <div class="csPageBoxHeader pl10">
                    <h3><strong><?=$review['review_title'];?></strong></h3>
                </div>
                <!-- Body -->
                <div class="csPageBoxBody">
                    <!-- Loader -->
                    <div class="csIPLoader jsIPLoader dn" data-page="review_listing"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></div>
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
                    <div class="csFeedbackViewBox p10">
                        <h4 class="pa10 pb10 csF16 csB7">Question <?=$key +1;?></h4>
                        
                        <h4><strong><?=$ques['title'];?></strong></h4>
                        <?php if(!empty($ques['description'])): ?>
                        <p><?=$ques['description'];?></p>
                        <?php endif;?>
                        <?php if(!empty($ques['video_help']) && $ques['video_help'] == 1 && getVideoURL($pid, $key) !== FALSE): ?>
                            <video src="<?=getVideoURL($pid, $key);?>" controls="true" style="width: 100%;"></video>
                        <?php endif;?>
                        <div class="jsQuestionBox" data-id="<?=$question['sid'];?>">
                            <?php echo getQuestionBody($ques, $answ); ?>
                        </div>
                        <!--  -->
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if($isAllowed){ ?>
                <!-- Footer -->
                <div class="csPageBoxFooter p10">
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="csBTNBoxLeft ma10">
                                <button class="btn btn-orange csF16 dn" data-review-id="<?=$pid?>"><em class="fa fa-plus-circle csF16"></em> Add</button>
                            </span>
                            <span class="csBTNBox ma10">
                                <a href="javascript:void(0);" class="btn btn-orange btn-lg jsQuestionSaveBtn csF16"><i class="fa fa-save csF16"></i> Save</a>
                                <a href="<?=base_url('performance-management/lms/reviews');?>" class="btn btn-black btn-lg csF16"><i class="fa fa-pencil-square-o csF16"></i> Finish Later</a>
                            </span>
                        </div>
                    </div>
                </div>
                <?php } ?>
              
            </div>
        </div>
    </div>
</div>
</div>


<script>
    answers = <?=json_encode($answers);?>;
</script>