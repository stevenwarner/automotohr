<?php 
    //
    $completedQuestionsOBJ = [];
    //
    if($selectedPage == 'feedback' ){
        $selectedPage = 0;
    }
    //
    $completedQuestionsCount = 0;
    $totalQuestions = count($review['QA']);
    $totalPages = $totalQuestions;
    //
    if($selectedPage == 0){
        $totalPages ++; 
        $totalQuestions ++;
        $question = $review['Feedback'];
    } else{
        //
        $question = $review['QA'][$selectedPage -1];
    }
    //
    foreach($review['QA'] as $index => $q){
        if(!empty($q['answer']['text']) || !empty($q['answer']['multiple_choice']) ||!empty($q['answer']['rating'])){
            $completedQuestionsOBJ[++$index] = true;
            $completedQuestionsCount++;
        }
    }
?>

<script>
var page = <?=$selectedPage;?>;
var totalPages = <?=$totalPages;?>;
var completedPages = <?=$completedQuestionsCount;?>;
var isManager = <?=$review['is_manager'];?>;
var question = {
    questionId: <?=$selectedPage == 'feedback' ? 0 : $review['QA'][$selectedPage -1]['question_id'];?>,
    reviewId: <?=$reviewId;?>,
    revieweeId: <?=$revieweeId;?>,
    reviewerId: <?=$reviewerId;?>,
    attachments: <?=empty($question['attachments']) ? '[]' : $question['attachments'];?>,
    multiple_choice: undefined,
    rating: undefined,
    text: undefined
};

<?php
        if(!empty($question['answer']['text'])){
            ?>
question.text = "<?=$question['answer']['text'];?>";
<?php
        }
        if(!empty($question['answer']['rating'])){
            ?>
question.rating = "<?=$question['answer']['rating'];?>";
<?php
        }
        if(!empty($question['answer']['multiple_choice'])){
            ?>
question.multiple_choice = "<?=$question['answer']['multiple_choice'];?>";
<?php
        }
    ?>
</script>

<div class="col-md-9 col-sm-12">
    <!--  -->
    <div class="csIPLoader jsIPLoader" data-page="save_question">
        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
    </div>
    
    <!-- Questions -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-body">
            <!-- Basic -->
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div>
                        <p class="csF16 csB7">
                            <?=$review['review_title'];?>
                        </p>
                        <p class="csF14">
                            <?= formatDateToDB($review['start_date'], 'Y-m-d', 'M d Y, D').' - '.formatDateToDB($review['end_date'], 'Y-m-d', 'M d Y, D');?>
                            <br /><?=getDueText($review['start_date'], true);?>
                        </p>
                        <p class="csF14 csB7">
                            <?=ucwords($review['reviewer_first_name'].' '.$review['reviewer_last_name']);?>
                            Reviewing
                            <?=ucwords($review['first_name'].' '.$review['last_name']);?>
                        </p>
                    </div>
                </div>
                <!--  -->
                <div class="col-md-6 col-xs-12">
                    <span class="pull-right csF16 csB7">
                        <?php if($review['is_started'] == 1){?>
                        <strong class="text-success">STARTED</strong>
                        <?php } else if($review['is_started'] == 2) { ?>
                        <strong class="text-danger">ENDED</strong>
                        <?php } else { ?>
                        <strong class="text-warning">PENDING</strong>
                        <?php }?>
                    </span>
                </div>
            </div>
            <hr>
            <!-- Basic -->
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div>
                        <p class="csF16 csB7">
                            Completed <?=$completedQuestionsCount;?> out of <?=$totalQuestions;?> Question(s)
                        </p>
                    </div>
                </div>
            </div>
            <!-- Basic -->
            <div class="row">
                <br>
                <div class="col-md-12 col-xs-12">
                    <div>
                        <ul class="csPaginationMenu text-left">
                            <?php for($i = 1; $i <= $totalQuestions; $i++): ?>
                            <li class="<?=$i == $selectedPage ? 'active' : '';?> <?=isset($completedQuestionsOBJ[$i]) ? ' active' : '';?>">
                                <a href="<?=current_url();?>?page=<?=$i;?>" title="<?=isset($completedQuestionsOBJ[$i]) ? 'Completed' : 'Pending';?>" placement="top"><?=$i;?></a>
                            </li>
                            <?php endfor; ?>
                            <?php 
                                if($review['is_manager']){
                                    ?>
                            <li <?='feedback' == $selectedPage ? 'class="active"' : '';?>>
                                <a href="<?=current_url();?>?page=feedback">Feedback</a>
                            </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($selectedPage != 'feedback' && !empty($question['other_answers'])) {?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-theme">
                    <div class="panel-heading">
                        <p class="csF16 csB7 csW mb0">
                        Reviewer(s) Feedback
                        </p>
                    </div>
                    <!--  -->
                    <div class="panel-body">
                        <?php
                            foreach($question['other_answers'] as $answer){
                                //
                                $cls = 'success';
                                $txt = 'ANSWERED';
                                //
                                if(
                                    empty($answer['multiple_choice']) &&
                                    empty($answer['rating']) &&
                                    empty($answer['text_answer'])
                                ){
                                    $cls = 'warning';
                                    $txt = 'PENDING';
                                }
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <p class="csF16 csW mb0">
                                            <?=$company_employees_index[$answer['reviewer_sid']]['Name'];?>'s Feedback
                                            <span class="pull-right"><button class="btn btn-<?=$cls;?> btn-xs csF16 csRadius5"><?=$txt;?></button></span>
                                        </p>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <p class="csF16 csB7">Multiple Choice</p>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <p class="csF16">
                                                    <?=empty($answer['multipe_choice']) ? 'N/A' : $answer['multipe_choice'];?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <p class="csF16 csB7">Rating</p>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <p class="csF16">
                                                <?=empty($answer['rating']) ? 'N/A' : $answer['rating'];?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <p class="csF16 csB7">Feedback</p>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <p class="csF16">
                                                <?=empty($answer['text_answer']) ? 'N/A' : $answer['text_answer'];?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <p class="csF16 csB7">Attachment(s)</p>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <p class="csF16">
                                                    <?php
                                                        if(!empty($answer['attachments'])){
                                                            ?>
                                                            <table class="table table-striped table-condensed">
                                                            <?php
                                                            foreach(json_decode($answer['attachments'], true) as $attachment){
                                                                ?>
                                                                <tr data-id="<?=$attachment;?>">
                                                                    <td style="vertical-align: middle">
                                                                        <p class="csF16"><?=$attachment;?></p>
                                                                    </td>
                                                                    <td style="vertical-align: middle">
                                                                        <button class="btn btn-orange csF14 jsPreviewAttachment">
                                                                            <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Preview
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            </table>
                                                            <?php
                                                        } 
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }                        
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>

    

    <!-- Question Screen -->
    <div class="row">
        <!--  -->
        <br />
        <div class="col-xs-12">
            <!--  -->
            <div class="panel panel-theme">
                <?php 
                    if($selectedPage != 'feedback'){
                        ?>
                            <div class="panel-heading">
                                <h5 class="csF14 csB7 csW">
                                    Q<?=$selectedPage;?>: <?=$question['question']['title'];?>
                                </h5>
                            </div>
                            <div class="panel-body">
                                <!-- Description -->
                                <div class="row">
                                    <div class="col-md-8 col-xs-12">
                                        <p class="csF14">
                                            <?=$question['question']['description'];?>
                                        </p>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <?php
                                                if(!empty($question['question']['video'])){
                                                    ?>
                                        <video controls style="width: 100%;" preload="metadata">
                                            <source src="<?=getVideoURL($reviewId,$question['question']['video']);?>"
                                                type="video/mp4">
                                            </source>
                                            <track label="English" kind="captions" srclang="en" default />
                                        </video>
                                        <?php
                                                }
                                            ?>
                                    </div>
                                </div>
                                <?php  if(preg_match('/multiple/i', $question['question']['question_type'])){ ?>
                                <!-- Multiple Choice -->
                                <div class="row">
                                    <br />
                                    <div class="col-xs-12">
                                        <label class="control control--radio csF14">
                                            <input type="radio" class="jsReviewChoice" name="jsReviewChoice" value="1"
                                                <?=!empty($question['answer']['multiple_choice']) && $question['answer']['multiple_choice'] == 1 ? 'checked' : '';?> />
                                            Yes
                                            <span class="control__indicator"></span>
                                        </label> <br />
                                        <label class="control control--radio csF14">
                                            <input type="radio" class="jsReviewChoice" name="jsReviewChoice" value="0"
                                                <?=!empty($question['answer']['multiple_choice']) && $question['answer']['multiple_choice'] == 0 ? 'checked' : '';?> />
                                            No
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php  if(preg_match('/rating/i', $question['question']['question_type'])){ ?>
                                <!-- Rating -->
                                <div class="row">
                                    <br />
                                    <ul class="csRatingBar pl10 pr10">
                                        <li data-id="1"
                                            class="jsReviewRating <?=$question['answer']['rating'] == 1 ? 'active' : '';?>">
                                            <p class="csF20 csB9">1</p>
                                            <p class="csF14 csB6">Strongly Agree</p>
                                        </li>
                                        <li data-id="2"
                                            class="jsReviewRating <?=$question['answer']['rating'] == 2 ? 'active' : '';?>">
                                            <p class="csF20 csB9">2</p>
                                            <p class="csF14 csB6">Agree</p>
                                        </li>
                                        <li data-id="3"
                                            class="jsReviewRating <?=$question['answer']['rating'] == 3 ? 'active' : '';?>">
                                            <p class="csF20 csB9">3</p>
                                            <p class="csF14 csB6">Neutral</p>
                                        </li>
                                        <li data-id="4"
                                            class="jsReviewRating <?=$question['answer']['rating'] == 4 ? 'active' : '';?>">
                                            <p class="csF20 csB9">4</p>
                                            <p class="csF14 csB6">Disagree</p>
                                        </li>
                                        <li data-id="5"
                                            class="jsReviewRating <?=$question['answer']['rating'] == 5 ? 'active' : '';?>">
                                            <p class="csF20 csB9">5</p>
                                            <p class="csF14 csB6">Strongly Disagree</p>
                                        </li>
                                    </ul>
                                </div>
                                <?php } ?>

                                <?php  if(preg_match('/text/i', $question['question']['question_type'])){ ?>
                                <!-- Text -->
                                <div class="row">
                                    <br />
                                    <div class="col-xs-12">
                                        <p class="csF14 csB7">Feedback (Elaborate)</p>
                                        <textarea rows="5"
                                            class="form-control jsReviewText"><?=$question['answer']['text'];?></textarea>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        <?php
                    } else{
                        ?>
                        <div class="panel-heading">
                            <h5 class="csF14 csB7 csW">
                                Provide a summary
                            </h5>
                        </div>
                        <div class="panel-body">
                            <!-- Rating -->
                            <div class="row">
                                    <br />
                                    <ul class="csRatingBar pl10 pr10">
                                        <li data-id="1"
                                            class="jsReviewRating <?=$question['rating'] == 1 ? 'active' : '';?>">
                                            <p class="csF20 csB9">1</p>
                                            <p class="csF14 csB6">Strongly Agree</p>
                                        </li>
                                        <li data-id="2"
                                            class="jsReviewRating <?=$question['rating'] == 2 ? 'active' : '';?>">
                                            <p class="csF20 csB9">2</p>
                                            <p class="csF14 csB6">Agree</p>
                                        </li>
                                        <li data-id="3"
                                            class="jsReviewRating <?=$question['rating'] == 3 ? 'active' : '';?>">
                                            <p class="csF20 csB9">3</p>
                                            <p class="csF14 csB6">Neutral</p>
                                        </li>
                                        <li data-id="4"
                                            class="jsReviewRating <?=$question['rating'] == 4 ? 'active' : '';?>">
                                            <p class="csF20 csB9">4</p>
                                            <p class="csF14 csB6">Disagree</p>
                                        </li>
                                        <li data-id="5"
                                            class="jsReviewRating <?=$question['rating'] == 5 ? 'active' : '';?>">
                                            <p class="csF20 csB9">5</p>
                                            <p class="csF14 csB6">Strongly Disagree</p>
                                        </li>
                                    </ul>
                                </div>
                            <!-- Text -->
                            <div class="row">
                                <br />
                                <div class="col-xs-12">
                                    <p class="csF14 csB7">Feedback (Elaborate)</p>
                                    <textarea rows="5" class="form-control jsReviewText"><?=$question['text'];?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>

                <?php 
                    if($employerId == $reviewerId){
                        ?>
                    <!--  -->
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- <button class="btn btn-black jsReviewFinishLater">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp; Finish Later
                                </button> -->
                                <span class="pull-right">
                                    <button class="btn btn-orange jsReviewSave">
                                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp; Save & <?=$selectedPage == $totalPages ? "Finish" : "Next";?>
                                    </button>
                                </span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                        <?php
                    } else{
                        ?>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="csF16 csB7 csInfo text-right">
                                        Your are not a reviewer. Only an assigned reviewer can submit the answer.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    

    <?php if($selectedPage != 'feedback') {?>
        <!-- Attachments -->
        <div class="row">
            <br />
            <div class="col-xs-12">
                <div class="panel panel-theme">
                    <div class="panel-heading">
                        <h5 class="csF16 csB7 csW">
                            Attachment(s)
                        </h5>
                    </div>
                    <div class="panel-body">
                        <p class="csF16 csB7 csInfo">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp; Attach supporting documents. The documents will be visible to the Reporting Managers.
                        </p>
                        <?php if($employerId == $reviewerId){ ?>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="file" name="attachment" id="jsQuestionAttachmentUpload" class="hidden" />
                            </div>
                        </div>
                        <!--  -->
                        <div class="row dn" id="jsQuestionAttachmentUploadRow">
                            <br />
                            <div class="col-sm-12">
                                <button class="btn btn-orange csF16 csB7 pull-right">
                                    <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Upload File
                                </button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php } ?>
                        <!--  -->
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-striped table-condensed">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th class="csF16" scope="col">Filename</th>
                                            <th class="csF16" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsAttachmentBody">
                                        <?php
                                            if(!empty($question['attachments'])){
                                                foreach(json_decode($question['attachments'], true) as $attachment){
                                                    ?>
                                        <tr data-id="<?=$attachment;?>">
                                            <td style="vertical-align: middle">
                                                <p class="csF16"><?=$attachment;?></p>
                                            </td>
                                            <td style="vertical-align: middle">
                                                <button class="btn btn-orange csF14 jsPreviewAttachment">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Preview
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                                }
                                            } else{
                                                ?>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    

    
</div>
