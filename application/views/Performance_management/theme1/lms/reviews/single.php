<div class="container-fluid">
    <!--  -->
    <div class="row">
        <!-- Left sidebar -->
           
        <!-- Content Area -->
        <div class="col-sm-12 col-xs-12">
            <div class="csPageBoxHeader bbn">
                <div class="csPageBoxReviewPeriod">
                    <span class="csBTNBoxLeft">
                        <select id="jsFilterReviewPeriod" class="dn">
                        </select>
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
                        <?php if(!empty($ques['video_help']) && $ques['video_help'] == 1 && getVideoURL($pid, $key) !== FALSE): ?>
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
              
            </div>
        </div>
    </div>
</div>
</div>


<script>
    answers = <?=json_encode($answers);?>;
</script>